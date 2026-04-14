<?php

namespace App\Http\Controllers;

use App\Models\Work;
use App\Models\Chapter;
use App\Models\Bookmark;
use App\Models\ReadingHistory;
use App\Models\User;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkController extends Controller
{
    /**
     * Menampilkan daftar karya uploader
     */
    public function index()
    {
        $works = Work::where('user_id', Auth::id())->latest()->get();
        return view('uploader.works.index', compact('works'));
    }

    /**
     * Form tambah karya
     */
    public function create()
    {
        $genres = Genre::all();
        return view('uploader.works.create', compact('genres'));
    }

    /**
     * Simpan karya baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'original_author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:comic,novel',
            'genre_ids' => 'required|array',
            'genre_ids.*' => 'exists:genres,id',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
        }

        $work = Work::create([
            'title' => $request->title,
            'original_author' => $request->original_author,
            'description' => $request->description,
            'type' => $request->type,
            'cover' => $coverPath,
            'status' => 'draft',
            'user_id' => Auth::id(),
        ]);

        // 🔗 attach genre
        $work->genres()->attach($request->genre_ids);

        return redirect()->route('works.index')->with('success', 'Karya berhasil dibuat');
    }

    /**
     * Form edit karya
     */
    public function edit(Work $work)
    {
        $genres = Genre::all();
        return view('uploader.works.edit', compact('work', 'genres'));
    }

    /**
     * Update karya
     */
    public function update(Request $request, Work $work)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'original_author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:comic,novel',
            'genre_ids' => 'required|array',
            'genre_ids.*' => 'exists:genres,id',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            $work->cover = $request->file('cover')->store('covers', 'public');
        }

        $work->update([
            'title' => $request->title,
            'original_author' => $request->original_author,
            'description' => $request->description,
            'type' => $request->type,
        ]);

        // 🔄 sync genre
        $work->genres()->sync($request->genre_ids);

        return redirect()->route('works.index')->with('success', 'Karya berhasil diperbarui');
    }

    /**
     * Detail karya
     */
    public function show(Work $work)
    {
        abort_if($work->user_id !== Auth::id(), 403);

        $work->load([
            'genres',
            'chapters' => fn($query) => $query->with(['images', 'comments', 'readingHistories']),
        ]);

        $totalBookmarks = Bookmark::where('work_id', $work->id)->count();
        $uniqueReaders = ReadingHistory::where('work_id', $work->id)->count();
        $totalComments = $work->chapters->sum(fn($chapter) => $chapter->comments->count());
        $totalImages = $work->chapters->sum(fn($chapter) => $chapter->images->count());
        $lastChapter = $work->chapters->sortByDesc('chapter_number')->first();
        $completedReaders = $lastChapter
            ? ReadingHistory::where('work_id', $work->id)
                ->where('chapter_id', $lastChapter->id)
                ->count()
            : 0;

        $completionRate = $uniqueReaders > 0
            ? (int) round(($completedReaders / $uniqueReaders) * 100)
            : 0;

        $chapterStats = $work->chapters
            ->map(function ($chapter) {
                $uniqueReaders = $chapter->readingHistories->count();
                $commentsCount = $chapter->comments->count();
                $imagesCount = $chapter->images->count();
                $wordCount = str_word_count(strip_tags($chapter->text_content ?? ''));

                return [
                    'chapter' => $chapter,
                    'unique_readers' => $uniqueReaders,
                    'comments_count' => $commentsCount,
                    'images_count' => $imagesCount,
                    'word_count' => $wordCount,
                    'engagement_score' => ($uniqueReaders * 3) + ($commentsCount * 2) + $imagesCount,
                ];
            })
            ->sortByDesc('chapter_number')
            ->values();

        $topChapter = $chapterStats
            ->sortByDesc('engagement_score')
            ->first();

        $analytics = [
            'total_bookmarks' => $totalBookmarks,
            'unique_readers' => $uniqueReaders,
            'total_comments' => $totalComments,
            'total_images' => $totalImages,
            'completion_rate' => $completionRate,
            'completed_readers' => $completedReaders,
            'top_chapter' => $topChapter,
            'chapter_stats' => $chapterStats,
        ];

        return view('uploader.works.show', compact('work', 'analytics'));
    }

    /**
     * Hapus karya
     */
    public function destroy(Work $work)
    {
        $work->delete();
        return redirect()->route('works.index')->with('success', 'Karya berhasil dihapus');
    }
    public function submit(Work $work)
    {
        abort_if($work->user_id !== auth()->id(), 403);

        $work->update([
            'status' => 'pending',
        ]);

        return back()->with('success', 'Karya dikirim ke admin');
    }

    // HALAMAN HOME (WELCOME)
    public function publicIndex()
    {
        $selectedGenre = null;
        $genreId = request()->integer('genre');

        $works = Work::with(['genres', 'user', 'chapters'])
            ->where('status', 'approved')
            ->when($genreId, function ($query) use ($genreId, &$selectedGenre) {
                $selectedGenre = Genre::find($genreId);

                $query->whereHas('genres', function ($genreQuery) use ($genreId) {
                    $genreQuery->where('genres.id', $genreId);
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('welcome', compact('works', 'selectedGenre'));
    }

    // DETAIL WORK (USER)
    public function publicShow(Work $work)
    {
        abort_if($work->status !== 'approved', 404);

        $sort = request('sort') === 'asc' ? 'asc' : 'desc';

        $work->load([
            'genres',
            'user',
        ]);

        $readingHistory = null;
        $bookmark = null;
        $continueChapter = null;
        $chaptersCount = $work->chapters()->count();

        if (Auth::check()) {
            $readingHistory = ReadingHistory::with('chapter')
                ->where('user_id', Auth::id())
                ->where('work_id', $work->id)
                ->first();

            $bookmark = Bookmark::where('user_id', Auth::id())
                ->where('work_id', $work->id)
                ->first();

            $continueChapter = $readingHistory?->chapter;

            if (!$continueChapter && $bookmark?->last_chapter_read) {
                $continueChapter = $work->chapters()
                    ->select(['id', 'work_id', 'chapter_number', 'title'])
                    ->where('chapter_number', $bookmark->last_chapter_read)
                    ->first();
            }
        }

        $chapters = $work->chapters()
            ->select(['id', 'work_id', 'chapter_number', 'title', 'created_at'])
            ->orderBy('chapter_number', $sort)
            ->paginate(60)
            ->withQueryString();

        $recentComments = \App\Models\Comment::query()
            ->with(['user:id,name', 'chapter:id,work_id,chapter_number,title'])
            ->whereHas('chapter', function ($query) use ($work) {
                $query->where('work_id', $work->id);
            })
            ->latest()
            ->take(8)
            ->get();

        return view('works.public.show', compact(
            'work',
            'bookmark',
            'continueChapter',
            'recentComments',
            'chapters',
            'chaptersCount',
            'sort'
        ));
    }

    public function read(Work $work)
    {
        abort_if($work->status !== 'approved', 404);

        if (Auth::check()) {
            $history = ReadingHistory::with('chapter')
                ->where('user_id', Auth::id())
                ->where('work_id', $work->id)
                ->first();

            if ($history?->chapter && $history->chapter->work_id === $work->id) {
                return redirect()->route('works.chapters.read', [$work, $history->chapter]);
            }

            $bookmark = Bookmark::where('user_id', Auth::id())
                ->where('work_id', $work->id)
                ->first();

            if ($bookmark?->last_chapter_read) {
                $bookmarkedChapter = $work->chapters()
                    ->where('chapter_number', $bookmark->last_chapter_read)
                    ->first();

                if ($bookmarkedChapter) {
                    return redirect()->route('works.chapters.read', [$work, $bookmarkedChapter]);
                }
            }
        }

        $firstChapter = $work->chapters()->orderBy('chapter_number')->first();

        abort_if(!$firstChapter, 404);

        return redirect()->route('works.chapters.read', [$work, $firstChapter]);
    }

    public function readChapter(Work $work, Chapter $chapter)
    {
        abort_if($work->status !== 'approved', 404);
        abort_if($chapter->work_id !== $work->id, 404);

        $work->load(['genres', 'user']);
        $chapter->load([
            'images' => fn($query) => $query->orderBy('page_number'),
            'comments.user',
        ]);

        $chapters = $work->chapters()->orderBy('chapter_number')->get();
        $currentIndex = $chapters->search(fn($item) => $item->id === $chapter->id);
        $previousChapter = $currentIndex !== false && $currentIndex > 0 ? $chapters[$currentIndex - 1] : null;
        $nextChapter = $currentIndex !== false && $currentIndex < ($chapters->count() - 1) ? $chapters[$currentIndex + 1] : null;

        if (Auth::check()) {
            ReadingHistory::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'work_id' => $work->id,
                ],
                [
                    'chapter_id' => $chapter->id,
                    'last_read_at' => now(),
                ]
            );

            Bookmark::where('user_id', Auth::id())
                ->where('work_id', $work->id)
                ->update([
                    'last_chapter_read' => $chapter->chapter_number,
                ]);
        }

        $estimatedReadMinutes = $work->type === 'novel'
            ? max(1, (int) ceil(str_word_count(strip_tags($chapter->text_content ?? '')) / 220))
            : max(1, (int) ceil($chapter->images->count() / 2));

        return view('works.public.read', compact(
            'work',
            'chapter',
            'chapters',
            'previousChapter',
            'nextChapter',
            'estimatedReadMinutes'
        ));
    }
}
