<?php

namespace App\Http\Controllers\Api\Uploader;

use App\Http\Controllers\Api\Concerns\FormatsApiPayloads;
use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use App\Models\Genre;
use App\Models\ReadingHistory;
use App\Models\Work;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UploaderPortalApiController extends Controller
{
    use FormatsApiPayloads;

    public function dashboard(Request $request): JsonResponse
    {
        $user = $request->user();
        $myWorks = Work::query()
            ->with(['genres', 'user'])
            ->withCount('chapters')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return response()->json([
            'data' => [
                'summary' => [
                    'total' => $myWorks->count(),
                    'draft' => $myWorks->where('status', 'draft')->count(),
                    'pending' => $myWorks->where('status', 'pending')->count(),
                    'approved' => $myWorks->where('status', 'approved')->count(),
                ],
                'recent_works' => $myWorks->take(5)->map(fn (Work $work) => $this->workPayload($work))->values(),
                'genres' => Genre::query()
                    ->withCount('works')
                    ->orderBy('name')
                    ->get()
                    ->map(fn (Genre $genre) => $this->genrePayload($genre))
                    ->values(),
            ],
        ]);
    }

    public function works(Request $request): JsonResponse
    {
        $user = $request->user();

        $works = Work::query()
            ->with(['genres', 'user'])
            ->withCount('chapters')
            ->where('user_id', $user->id)
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->when($request->filled('type'), fn ($query) => $query->where('type', $request->string('type')->toString()))
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = $request->string('q')->trim()->toString();

                $query->where(function ($builder) use ($keyword) {
                    $builder->where('title', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%");
                });
            })
            ->latest()
            ->paginate($request->integer('per_page', 15));

        $summary = Work::query()
            ->where('user_id', $user->id)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return $this->paginatedResponse($works, fn (Work $work) => $this->workPayload($work), [
            'summary' => [
                'total' => Work::query()->where('user_id', $user->id)->count(),
                'draft' => (int) ($summary['draft'] ?? 0),
                'pending' => (int) ($summary['pending'] ?? 0),
                'approved' => (int) ($summary['approved'] ?? 0),
            ],
        ]);
    }

    public function storeWork(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'original_author' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'in:comic,novel'],
            'genre_ids' => ['required', 'array', 'min:1'],
            'genre_ids.*' => ['exists:genres,id'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $coverPath = $request->hasFile('cover')
            ? $request->file('cover')->store('covers', 'public')
            : null;

        $work = Work::query()->create([
            'title' => $validated['title'],
            'original_author' => $validated['original_author'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'cover' => $coverPath,
            'status' => 'draft',
            'user_id' => $request->user()->id,
        ]);

        $work->genres()->sync($validated['genre_ids']);
        $work->load(['genres', 'user'])->loadCount('chapters');

        return response()->json([
            'message' => 'Karya berhasil dibuat.',
            'data' => $this->workPayload($work),
        ], 201);
    }

    public function showWork(Request $request, Work $work): JsonResponse
    {
        $this->ensureWorkOwner($request, $work);

        $work->load([
            'genres',
            'user.role',
            'chapters' => fn ($query) => $query->withCount(['images', 'comments'])->orderBy('chapter_number'),
        ])->loadCount('chapters');

        return response()->json([
            'data' => [
                ...$this->workPayload($work, true),
                'can_submit' => $work->status === 'draft' && $work->chapters_count > 0,
            ],
        ]);
    }

    public function updateWork(Request $request, Work $work): JsonResponse
    {
        $this->ensureWorkOwner($request, $work);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'original_author' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'in:comic,novel'],
            'genre_ids' => ['required', 'array', 'min:1'],
            'genre_ids.*' => ['exists:genres,id'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if ($request->hasFile('cover')) {
            $work->cover = $request->file('cover')->store('covers', 'public');
        }

        $work->fill([
            'title' => $validated['title'],
            'original_author' => $validated['original_author'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
        ]);
        $work->save();

        $work->genres()->sync($validated['genre_ids']);
        $work->load(['genres', 'user'])->loadCount('chapters');

        return response()->json([
            'message' => 'Karya berhasil diperbarui.',
            'data' => $this->workPayload($work),
        ]);
    }

    public function destroyWork(Request $request, Work $work): JsonResponse
    {
        $this->ensureWorkOwner($request, $work);
        $work->delete();

        return response()->json([
            'message' => 'Karya berhasil dihapus.',
        ]);
    }

    public function submitWork(Request $request, Work $work): JsonResponse
    {
        $this->ensureWorkOwner($request, $work);

        $work->update([
            'status' => 'pending',
        ]);

        $work->load(['genres', 'user'])->loadCount('chapters');

        return response()->json([
            'message' => 'Karya berhasil dikirim ke admin.',
            'data' => $this->workPayload($work),
        ]);
    }

    public function analytics(Request $request, Work $work): JsonResponse
    {
        $this->ensureWorkOwner($request, $work);

        $work->load([
            'genres',
            'user.role',
            'chapters' => fn ($query) => $query->with(['images', 'comments', 'readingHistories'])->orderBy('chapter_number'),
        ]);

        $totalBookmarks = Bookmark::query()->where('work_id', $work->id)->count();
        $uniqueReaders = ReadingHistory::query()->where('work_id', $work->id)->distinct('user_id')->count('user_id');
        $totalComments = $work->chapters->sum(fn ($chapter) => $chapter->comments->count());
        $totalImages = $work->chapters->sum(fn ($chapter) => $chapter->images->count());
        $lastChapter = $work->chapters->sortByDesc('chapter_number')->first();
        $completedReaders = $lastChapter
            ? ReadingHistory::query()
                ->where('work_id', $work->id)
                ->where('chapter_id', $lastChapter->id)
                ->distinct('user_id')
                ->count('user_id')
            : 0;

        $completionRate = $uniqueReaders > 0
            ? (int) round(($completedReaders / $uniqueReaders) * 100)
            : 0;

        $chapterStats = $work->chapters->map(function ($chapter) {
            $uniqueReaders = $chapter->readingHistories->pluck('user_id')->unique()->count();
            $commentsCount = $chapter->comments->count();
            $imagesCount = $chapter->images->count();
            $wordCount = str_word_count(strip_tags($chapter->text_content ?? ''));

            return [
                'chapter' => $this->chapterPayload($chapter),
                'unique_readers' => $uniqueReaders,
                'comments_count' => $commentsCount,
                'images_count' => $imagesCount,
                'word_count' => $wordCount,
                'engagement_score' => ($uniqueReaders * 3) + ($commentsCount * 2) + $imagesCount,
            ];
        })->values();

        $topChapter = collect($chapterStats)->sortByDesc('engagement_score')->first();

        return response()->json([
            'data' => [
                'work' => $this->workPayload($work),
                'summary' => [
                    'total_bookmarks' => $totalBookmarks,
                    'unique_readers' => $uniqueReaders,
                    'total_comments' => $totalComments,
                    'total_images' => $totalImages,
                    'completion_rate' => $completionRate,
                    'completed_readers' => $completedReaders,
                ],
                'top_chapter' => $topChapter,
                'chapter_stats' => $chapterStats,
            ],
        ]);
    }

    private function ensureWorkOwner(Request $request, Work $work): void
    {
        abort_if($work->user_id !== $request->user()->id, 403);
    }
}
