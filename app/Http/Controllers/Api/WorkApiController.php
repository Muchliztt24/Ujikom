<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Work;
use Illuminate\Http\JsonResponse;

class WorkApiController extends Controller
{
    public function index(): JsonResponse
    {
        $works = Work::query()
            ->with(['genres', 'user'])
            ->withCount('chapters')
            ->where('status', 'approved')
            ->latest()
            ->get();

        return response()->json([
            'data' => $works->map(fn (Work $work) => $this->transformWorkSummary($work)),
        ]);
    }

    public function show(Work $work): JsonResponse
    {
        abort_if($work->status !== 'approved', 404);

        $work->load([
            'genres',
            'user',
            'chapters' => fn ($query) => $query->orderBy('chapter_number'),
        ]);

        return response()->json([
            'data' => [
                ...$this->transformWorkSummary($work),
                'chapters' => $work->chapters->map(fn (Chapter $chapter) => [
                    'id' => $chapter->id,
                    'title' => $chapter->title ?: 'Chapter '.$chapter->chapter_number,
                    'chapter_number' => $chapter->chapter_number,
                    'has_text_content' => filled($chapter->text_content),
                    'created_at' => optional($chapter->created_at)?->toIso8601String(),
                ])->values(),
            ],
        ]);
    }

    public function chapter(Work $work, Chapter $chapter): JsonResponse
    {
        abort_if($work->status !== 'approved', 404);
        abort_if($chapter->work_id !== $work->id, 404);

        $chapter->load([
            'images' => fn ($query) => $query->orderBy('page_number'),
        ]);

        return response()->json([
            'data' => [
                'id' => $chapter->id,
                'work_id' => $work->id,
                'work_title' => $work->title,
                'title' => $chapter->title ?: 'Chapter '.$chapter->chapter_number,
                'chapter_number' => $chapter->chapter_number,
                'text_content' => $chapter->text_content,
                'images' => $chapter->images->map(fn ($image) => [
                    'id' => $image->id,
                    'page_number' => $image->page_number,
                    'image_url' => $this->makePublicUrl($image->image_url),
                ])->values(),
            ],
        ]);
    }

    private function transformWorkSummary(Work $work): array
    {
        return [
            'id' => $work->id,
            'title' => $work->title,
            'description' => $work->description,
            'type' => $work->type,
            'status' => $work->status,
            'cover_url' => $this->makePublicUrl($work->cover),
            'author' => $work->user?->name,
            'genres' => $work->genres->pluck('name')->values(),
            'chapters_count' => $work->chapters_count ?? $work->chapters?->count() ?? 0,
            'created_at' => optional($work->created_at)?->toIso8601String(),
            'updated_at' => optional($work->updated_at)?->toIso8601String(),
        ];
    }

    private function makePublicUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return url('storage/'.$path);
    }
}
