<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Concerns\FormatsApiPayloads;
use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\ChapterImage;
use App\Models\Work;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminReviewApiController extends Controller
{
    use FormatsApiPayloads;

    public function works(Request $request): JsonResponse
    {
        $status = $request->string('status')->toString();

        $works = Work::query()
            ->with(['user.role', 'genres'])
            ->withCount('chapters')
            ->when(in_array($status, ['pending', 'approved', 'draft'], true), fn ($query) => $query->where('status', $status))
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

        return $this->paginatedResponse($works, fn (Work $work) => $this->workPayload($work), [
            'summary' => [
                'total' => Work::query()->count(),
                'pending' => Work::query()->where('status', 'pending')->count(),
                'approved' => Work::query()->where('status', 'approved')->count(),
                'draft' => Work::query()->where('status', 'draft')->count(),
            ],
        ]);
    }

    public function pendingWorks(Request $request): JsonResponse
    {
        $request->merge(['status' => 'pending']);

        return $this->works($request);
    }

    public function showWork(Work $work): JsonResponse
    {
        $work->load([
            'user.role',
            'genres',
            'chapters' => fn ($query) => $query->withCount(['images', 'comments'])->orderBy('chapter_number'),
        ]);

        return response()->json([
            'data' => $this->workPayload($work, true),
        ]);
    }

    public function approveWork(Work $work): JsonResponse
    {
        $work->update([
            'status' => 'approved',
        ]);

        $work->load(['user.role', 'genres'])->loadCount('chapters');

        return response()->json([
            'message' => 'Karya berhasil disetujui.',
            'data' => $this->workPayload($work),
        ]);
    }

    public function rejectWork(Work $work): JsonResponse
    {
        $work->update([
            'status' => 'draft',
        ]);

        $work->load(['user.role', 'genres'])->loadCount('chapters');

        return response()->json([
            'message' => 'Karya dikembalikan ke draft.',
            'data' => $this->workPayload($work),
        ]);
    }

    public function destroyWork(Work $work): JsonResponse
    {
        $work->delete();

        return response()->json([
            'message' => 'Karya berhasil dihapus.',
        ]);
    }

    public function chapters(Request $request): JsonResponse
    {
        $chapters = Chapter::query()
            ->with(['work.user'])
            ->withCount(['images', 'comments'])
            ->when($request->filled('work_id'), fn ($query) => $query->where('work_id', $request->integer('work_id')))
            ->when($request->filled('type'), function ($query) use ($request) {
                $type = $request->string('type')->toString();
                $query->whereHas('work', fn ($workQuery) => $workQuery->where('type', $type));
            })
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = $request->string('q')->trim()->toString();
                $query->where(function ($builder) use ($keyword) {
                    $builder->where('title', 'like', "%{$keyword}%")
                        ->orWhere('text_content', 'like', "%{$keyword}%")
                        ->orWhereHas('work', fn ($workQuery) => $workQuery->where('title', 'like', "%{$keyword}%"));
                });
            })
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return $this->paginatedResponse($chapters, fn (Chapter $chapter) => $this->chapterPayload($chapter));
    }

    public function showChapter(Chapter $chapter): JsonResponse
    {
        $chapter->load([
            'work.user.role',
            'work.genres',
            'images' => fn ($query) => $query->orderBy('page_number'),
            'comments.user',
        ])->loadCount(['images', 'comments']);

        return response()->json([
            'data' => [
                ...$this->chapterPayload($chapter, includeText: true, includeImages: true, includeComments: true),
                'work' => $this->workPayload($chapter->work),
            ],
        ]);
    }

    public function destroyChapter(Chapter $chapter): JsonResponse
    {
        $chapter->delete();

        return response()->json([
            'message' => 'Chapter berhasil dihapus.',
        ]);
    }

    public function chapterImages(Request $request): JsonResponse
    {
        $images = ChapterImage::query()
            ->with(['chapter.work.user'])
            ->when($request->filled('chapter_id'), fn ($query) => $query->where('chapter_id', $request->integer('chapter_id')))
            ->when($request->filled('work_id'), function ($query) use ($request) {
                $workId = $request->integer('work_id');
                $query->whereHas('chapter', fn ($chapterQuery) => $chapterQuery->where('work_id', $workId));
            })
            ->orderByDesc('id')
            ->paginate($request->integer('per_page', 20));

        return $this->paginatedResponse($images, function (ChapterImage $image) {
            return [
                ...$this->chapterImagePayload($image),
                'chapter' => $image->chapter ? $this->chapterPayload($image->chapter) : null,
            ];
        });
    }

    public function showChapterImage(ChapterImage $chapterImage): JsonResponse
    {
        $chapterImage->load([
            'chapter.work.user.role',
            'chapter.work.genres',
            'chapter.images' => fn ($query) => $query->orderBy('page_number'),
        ]);

        return response()->json([
            'data' => [
                ...$this->chapterImagePayload($chapterImage),
                'chapter' => $this->chapterPayload($chapterImage->chapter, includeText: true, includeImages: true),
                'work' => $this->workPayload($chapterImage->chapter->work),
            ],
        ]);
    }

    public function destroyChapterImage(ChapterImage $chapterImage): JsonResponse
    {
        Storage::disk('public')->delete($chapterImage->image_url);
        $chapterImage->delete();

        return response()->json([
            'message' => 'Gambar chapter berhasil dihapus.',
        ]);
    }
}
