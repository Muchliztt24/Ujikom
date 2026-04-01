<?php

namespace App\Http\Controllers\Api\Uploader;

use App\Http\Controllers\Api\Concerns\FormatsApiPayloads;
use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Work;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UploaderChapterApiController extends Controller
{
    use FormatsApiPayloads;

    public function index(Request $request, Work $work): JsonResponse
    {
        $this->ensureWorkOwner($request, $work);

        $chapters = $work->chapters()
            ->with(['work.user'])
            ->withCount(['images', 'comments'])
            ->orderBy('chapter_number')
            ->get();

        $work->load(['genres', 'user'])->loadCount('chapters');

        return response()->json([
            'data' => $chapters->map(fn (Chapter $chapter) => $this->chapterPayload($chapter))->values(),
            'work' => $this->workPayload($work),
        ]);
    }

    public function store(Request $request, Work $work): JsonResponse
    {
        $this->ensureWorkOwner($request, $work);

        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'chapter_number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('chapters', 'chapter_number')->where(fn ($query) => $query->where('work_id', $work->id)),
            ],
            'text_content' => ['nullable', 'string'],
        ]);

        $chapter = Chapter::query()->create([
            'work_id' => $work->id,
            'title' => $validated['title'] ?? null,
            'chapter_number' => $validated['chapter_number'],
            'text_content' => $validated['text_content'] ?? null,
        ]);

        $chapter->load('work')->loadCount(['images', 'comments']);

        return response()->json([
            'message' => 'Chapter berhasil ditambahkan.',
            'data' => $this->chapterPayload($chapter, includeText: true),
        ], 201);
    }

    public function show(Request $request, Work $work, Chapter $chapter): JsonResponse
    {
        $this->ensureChapterOwner($request, $work, $chapter);

        $chapter->load([
            'work.user',
            'images' => fn ($query) => $query->orderBy('page_number'),
            'comments.user',
        ])->loadCount(['images', 'comments']);

        return response()->json([
            'data' => $this->chapterPayload($chapter, includeText: true, includeImages: true, includeComments: true),
        ]);
    }

    public function update(Request $request, Work $work, Chapter $chapter): JsonResponse
    {
        $this->ensureChapterOwner($request, $work, $chapter);

        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'chapter_number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('chapters', 'chapter_number')
                    ->ignore($chapter->id)
                    ->where(fn ($query) => $query->where('work_id', $work->id)),
            ],
            'text_content' => ['nullable', 'string'],
        ]);

        $chapter->update([
            'title' => $validated['title'] ?? null,
            'chapter_number' => $validated['chapter_number'],
            'text_content' => $validated['text_content'] ?? null,
        ]);

        $chapter->load('work')->loadCount(['images', 'comments']);

        return response()->json([
            'message' => 'Chapter berhasil diperbarui.',
            'data' => $this->chapterPayload($chapter, includeText: true),
        ]);
    }

    public function destroy(Request $request, Work $work, Chapter $chapter): JsonResponse
    {
        $this->ensureChapterOwner($request, $work, $chapter);
        $chapter->delete();

        return response()->json([
            'message' => 'Chapter berhasil dihapus.',
        ]);
    }

    private function ensureWorkOwner(Request $request, Work $work): void
    {
        abort_if($work->user_id !== $request->user()->id, 403);
    }

    private function ensureChapterOwner(Request $request, Work $work, Chapter $chapter): void
    {
        $this->ensureWorkOwner($request, $work);
        abort_if($chapter->work_id !== $work->id, 404);
    }
}
