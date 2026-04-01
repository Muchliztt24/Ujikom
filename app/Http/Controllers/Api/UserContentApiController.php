<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\FormatsApiPayloads;
use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use App\Models\Chapter;
use App\Models\Comment;
use App\Models\Genre;
use App\Models\ReadingHistory;
use App\Models\Work;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserContentApiController extends Controller
{
    use FormatsApiPayloads;

    private function featuredWorks()
    {
        return Work::query()
            ->with(['user', 'genres', 'chapters'])
            ->where('status', 'approved')
            ->latest()
            ->take(12)
            ->get();
    }

    private function highlightGenres()
    {
        return Genre::query()
            ->withCount('works')
            ->orderByDesc('works_count')
            ->take(6)
            ->get();
    }

    public function faq(): JsonResponse
    {
        return response()->json([
            'data' => [
                [
                    'question' => 'Bagaimana cara mulai membaca?',
                    'answer' => 'Buka detail karya, lalu klik tombol mulai baca atau pilih chapter yang ingin dibaca.',
                ],
                [
                    'question' => 'Apa perbedaan novel dan comic?',
                    'answer' => 'Novel ditampilkan sebagai teks chapter, sedangkan comic ditampilkan sebagai rangkaian gambar per chapter.',
                ],
                [
                    'question' => 'Bagaimana cara upload karya?',
                    'answer' => 'Masuk sebagai uploader, buka dashboard, lalu pilih menu kelola karya untuk membuat work dan chapter.',
                ],
            ],
        ]);
    }

    public function news(): JsonResponse
    {
        return response()->json([
            'data' => [
                ['title' => 'Pilihan bacaan terbaru setiap hari', 'summary' => 'Temukan karya baru, chapter terbaru, dan judul populer langsung dari beranda Nokomi.'],
                ['title' => 'Ruang baca yang makin nyaman', 'summary' => 'Nikmati tampilan novel dan komik yang dirancang untuk fokus, ringan, dan nyaman di layar mana pun.'],
                ['title' => 'Akun dan library dalam satu tempat', 'summary' => 'Progress baca, bookmark, dan aktivitas akun tersusun rapi supaya lebih mudah dilanjutkan kapan saja.'],
            ],
        ]);
    }

    public function bookmarks(Request $request): JsonResponse
    {
        $bookmarks = Bookmark::query()
            ->with(['work.user', 'work.genres', 'work.chapters'])
            ->where('user_id', $request->user()->id)
            ->get();

        return response()->json([
            'data' => $bookmarks->map(function (Bookmark $bookmark) {
                return [
                    'work' => $bookmark->work ? $this->workPayload($bookmark->work) : null,
                    'last_chapter_read' => $bookmark->last_chapter_read,
                ];
            })->filter(fn ($item) => $item['work'] !== null)->values(),
        ]);
    }

    public function storeBookmark(Request $request, Work $work): JsonResponse
    {
        abort_if($work->status !== 'approved', 404);

        $validated = $request->validate([
            'last_chapter_read' => ['nullable', 'integer', 'min:1'],
        ]);

        $bookmark = Bookmark::query()->updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'work_id' => $work->id,
            ],
            [
                'last_chapter_read' => $validated['last_chapter_read'] ?? null,
            ]
        );

        $work->load(['user', 'genres'])->loadCount('chapters');

        return response()->json([
            'message' => 'Bookmark berhasil disimpan.',
            'data' => [
                'work' => $this->workPayload($work),
                'last_chapter_read' => $bookmark->last_chapter_read,
            ],
        ]);
    }

    public function destroyBookmark(Request $request, Work $work): JsonResponse
    {
        Bookmark::query()
            ->where('user_id', $request->user()->id)
            ->where('work_id', $work->id)
            ->delete();

        return response()->json([
            'message' => 'Bookmark berhasil dihapus.',
        ]);
    }

    public function chapterComments(Chapter $chapter): JsonResponse
    {
        $chapter->load([
            'work',
            'comments.user',
        ])->loadCount('comments');

        abort_if($chapter->work?->status !== 'approved', 404);

        return response()->json([
            'data' => $chapter->comments->map(fn (Comment $comment) => $this->commentPayload($comment))->values(),
            'meta' => [
                'total' => $chapter->comments_count,
            ],
        ]);
    }

    public function storeComment(Request $request, Chapter $chapter): JsonResponse
    {
        $chapter->loadMissing('work');
        abort_if($chapter->work?->status !== 'approved', 404);

        $validated = $request->validate([
            'content' => ['required', 'string'],
        ]);

        $comment = Comment::query()->create([
            'user_id' => $request->user()->id,
            'chapter_id' => $chapter->id,
            'content' => $validated['content'],
        ]);

        $comment->load('user');

        return response()->json([
            'message' => 'Komentar berhasil ditambahkan.',
            'data' => $this->commentPayload($comment),
        ], 201);
    }

    public function destroyComment(Request $request, Comment $comment): JsonResponse
    {
        abort_if($comment->user_id !== $request->user()->id, 403);
        $comment->delete();

        return response()->json([
            'message' => 'Komentar berhasil dihapus.',
        ]);
    }

    public function history(Request $request): JsonResponse
    {
        $histories = ReadingHistory::query()
            ->with(['work.user', 'work.genres', 'chapter'])
            ->where('user_id', $request->user()->id)
            ->orderByDesc('last_read_at')
            ->get();

        return response()->json([
            'data' => $histories->map(function (ReadingHistory $history) {
                return [
                    'id' => $history->id,
                    'last_read_at' => optional($history->last_read_at)?->toIso8601String(),
                    'work' => $history->work ? $this->workPayload($history->work) : null,
                    'chapter' => $history->chapter ? $this->chapterPayload($history->chapter) : null,
                ];
            })->filter(fn ($item) => $item['work'] !== null && $item['chapter'] !== null)->values(),
        ]);
    }

    public function storeProgress(Request $request, Work $work, Chapter $chapter): JsonResponse
    {
        abort_if($work->status !== 'approved', 404);
        abort_if($chapter->work_id !== $work->id, 404);

        $history = ReadingHistory::query()->updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'work_id' => $work->id,
            ],
            [
                'chapter_id' => $chapter->id,
                'last_read_at' => now(),
            ]
        );

        Bookmark::query()
            ->where('user_id', $request->user()->id)
            ->where('work_id', $work->id)
            ->update([
                'last_chapter_read' => $chapter->chapter_number,
            ]);

        $chapter->loadMissing('work');

        return response()->json([
            'message' => 'Progress baca berhasil disimpan.',
            'data' => [
                'history_id' => $history->id,
                'work_id' => $work->id,
                'chapter_id' => $chapter->id,
                'chapter_number' => $chapter->chapter_number,
                'last_read_at' => optional($history->last_read_at)?->toIso8601String(),
            ],
        ]);
    }

    public function guestNotifications(): JsonResponse
    {
        $latestReleases = Work::query()
            ->with(['user', 'genres', 'chapters'])
            ->where('status', 'approved')
            ->latest()
            ->take(6)
            ->get();

        return response()->json([
            'data' => [
                'latest_releases' => $latestReleases->map(fn (Work $work) => $this->workPayload($work))->values(),
                'chapter_updates' => [],
                'creator_feed' => [],
            ],
        ]);
    }

    public function notifications(Request $request): JsonResponse
    {
        $latestReleases = Work::query()
            ->with(['user', 'genres', 'chapters'])
            ->where('status', 'approved')
            ->latest()
            ->take(6)
            ->get();

        $chapterUpdates = collect();
        $creatorFeed = collect();

        $user = $request->user();
        $bookmarkedWorkIds = Bookmark::query()
            ->where('user_id', $user->id)
            ->pluck('work_id');

        if ($bookmarkedWorkIds->isNotEmpty()) {
            $chapterUpdates = Chapter::query()
                ->with(['work.user'])
                ->whereIn('work_id', $bookmarkedWorkIds)
                ->latest()
                ->take(8)
                ->get();
        }

        if (in_array($user->role?->name, ['admin', 'uploader'], true)) {
            $creatorFeed = Comment::query()
                ->with(['user', 'chapter.work'])
                ->whereHas('chapter.work', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->latest()
                ->take(6)
                ->get();
        }

        return response()->json([
            'data' => [
                'latest_releases' => $latestReleases->map(fn (Work $work) => $this->workPayload($work))->values(),
                'chapter_updates' => $chapterUpdates->map(fn (Chapter $chapter) => $this->chapterPayload($chapter))->values(),
                'creator_feed' => $creatorFeed->map(fn (Comment $comment) => $this->commentPayload($comment))->values(),
            ],
        ]);
    }

    public function guestCollection(): JsonResponse
    {
        $featuredWorks = $this->featuredWorks();
        $highlightGenres = $this->highlightGenres();
        $novels = $featuredWorks->where('type', 'novel')->take(6)->values();
        $comics = $featuredWorks->where('type', 'comic')->take(6)->values();

        return response()->json([
            'data' => [
                'is_guest' => true,
                'bookmarked_works' => [],
                'recent_history' => [],
                'recommended_works' => $featuredWorks->take(6)->map(fn (Work $work) => $this->workPayload($work))->values(),
                'novels' => $novels->map(fn (Work $work) => $this->workPayload($work))->values(),
                'comics' => $comics->map(fn (Work $work) => $this->workPayload($work))->values(),
                'featured_works' => $featuredWorks->map(fn (Work $work) => $this->workPayload($work))->values(),
                'highlight_genres' => $highlightGenres->map(fn (Genre $genre) => $this->genrePayload($genre))->values(),
            ],
        ]);
    }

    public function collection(Request $request): JsonResponse
    {
        $featuredWorks = $this->featuredWorks();
        $highlightGenres = $this->highlightGenres();

        $bookmarks = Bookmark::query()
            ->with(['work.genres', 'work.user', 'work.chapters'])
            ->where('user_id', $request->user()->id)
            ->get();

        $bookmarkedWorks = $bookmarks
            ->pluck('work')
            ->filter()
            ->unique('id')
            ->values();

        $recentHistory = ReadingHistory::query()
            ->with(['work.genres', 'work.user', 'chapter'])
            ->where('user_id', $request->user()->id)
            ->orderByDesc('last_read_at')
            ->take(6)
            ->get();

        $preferredGenreIds = $bookmarkedWorks
            ->flatMap(fn ($work) => $work->genres->pluck('id'))
            ->unique()
            ->values();

        $recommendedWorks = Work::query()
            ->with(['user', 'genres', 'chapters'])
            ->where('status', 'approved')
            ->when($bookmarkedWorks->isNotEmpty(), fn ($query) => $query->whereNotIn('id', $bookmarkedWorks->pluck('id')))
            ->when($preferredGenreIds->isNotEmpty(), function ($query) use ($preferredGenreIds) {
                $query->whereHas('genres', function ($genreQuery) use ($preferredGenreIds) {
                    $genreQuery->whereIn('genres.id', $preferredGenreIds);
                });
            })
            ->latest()
            ->take(8)
            ->get();

        return response()->json([
            'data' => [
                'is_guest' => false,
                'bookmarked_works' => $bookmarkedWorks->map(fn (Work $work) => $this->workPayload($work))->values(),
                'recent_history' => $recentHistory->map(function (ReadingHistory $history) {
                    return [
                        'last_read_at' => optional($history->last_read_at)?->toIso8601String(),
                        'work' => $history->work ? $this->workPayload($history->work) : null,
                        'chapter' => $history->chapter ? $this->chapterPayload($history->chapter) : null,
                    ];
                })->values(),
                'recommended_works' => $recommendedWorks->map(fn (Work $work) => $this->workPayload($work))->values(),
                'novels' => $bookmarkedWorks->where('type', 'novel')->map(fn (Work $work) => $this->workPayload($work))->values(),
                'comics' => $bookmarkedWorks->where('type', 'comic')->map(fn (Work $work) => $this->workPayload($work))->values(),
                'featured_works' => $featuredWorks->map(fn (Work $work) => $this->workPayload($work))->values(),
                'highlight_genres' => $highlightGenres->map(fn (Genre $genre) => $this->genrePayload($genre))->values(),
            ],
        ]);
    }
}
