<?php

namespace App\Http\Controllers\Api\Uploader;

use App\Http\Controllers\Api\Concerns\FormatsApiPayloads;
use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\ChapterImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploaderChapterImageApiController extends Controller
{
    use FormatsApiPayloads;

    public function index(Request $request, Chapter $chapter): JsonResponse
    {
        $this->ensureChapterOwner($request, $chapter);

        $chapter->load([
            'work.user',
            'images' => fn ($query) => $query->orderBy('page_number'),
        ])->loadCount(['images', 'comments']);

        return response()->json([
            'data' => $chapter->images->map(fn (ChapterImage $image) => $this->chapterImagePayload($image))->values(),
            'chapter' => $this->chapterPayload($chapter, includeText: true, includeImages: true),
        ]);
    }

    public function store(Request $request, Chapter $chapter): JsonResponse
    {
        $this->ensureChapterOwner($request, $chapter);

        $request->validate([
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $uploadedFiles = [];

        if ($request->hasFile('image')) {
            $uploadedFiles[] = $request->file('image');
        }

        if ($request->hasFile('images')) {
            $uploadedFiles = array_merge($uploadedFiles, $request->file('images'));
        }

        if ($uploadedFiles === []) {
            return response()->json([
                'message' => 'Gambar harus diisi.',
                'errors' => [
                    'images' => ['Unggah setidaknya satu gambar.'],
                ],
            ], 422);
        }

        $pageNumber = (int) ($chapter->images()->max('page_number') ?? 0);
        $createdImages = collect();

        foreach ($uploadedFiles as $file) {
            $path = $file->store('chapters', 'public');

            $createdImages->push(ChapterImage::query()->create([
                'chapter_id' => $chapter->id,
                'image_url' => $path,
                'page_number' => ++$pageNumber,
            ]));
        }

        return response()->json([
            'message' => 'Gambar chapter berhasil diunggah.',
            'data' => $createdImages->map(fn (ChapterImage $image) => $this->chapterImagePayload($image))->values(),
        ], 201);
    }

    public function show(Request $request, Chapter $chapter, ChapterImage $chapterImage): JsonResponse
    {
        $this->ensureImageOwner($request, $chapter, $chapterImage);

        $chapterImage->load([
            'chapter.work.user',
            'chapter.images' => fn ($query) => $query->orderBy('page_number'),
        ]);

        return response()->json([
            'data' => [
                ...$this->chapterImagePayload($chapterImage),
                'chapter' => $this->chapterPayload($chapterImage->chapter, includeText: true, includeImages: true),
            ],
        ]);
    }

    public function update(Request $request, Chapter $chapter, ChapterImage $chapterImage): JsonResponse
    {
        $this->ensureImageOwner($request, $chapter, $chapterImage);

        $validated = $request->validate([
            'page_number' => ['required', 'integer', 'min:1'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($chapterImage->image_url);
            $chapterImage->image_url = $request->file('image')->store('chapters', 'public');
        }

        $chapterImage->page_number = $validated['page_number'];
        $chapterImage->save();

        return response()->json([
            'message' => 'Gambar chapter berhasil diperbarui.',
            'data' => $this->chapterImagePayload($chapterImage),
        ]);
    }

    public function destroy(Request $request, Chapter $chapter, ChapterImage $chapterImage): JsonResponse
    {
        $this->ensureImageOwner($request, $chapter, $chapterImage);

        Storage::disk('public')->delete($chapterImage->image_url);
        $chapterImage->delete();

        return response()->json([
            'message' => 'Gambar chapter berhasil dihapus.',
        ]);
    }

    private function ensureChapterOwner(Request $request, Chapter $chapter): void
    {
        $chapter->loadMissing('work');
        abort_if($chapter->work?->user_id !== $request->user()->id, 403);
    }

    private function ensureImageOwner(Request $request, Chapter $chapter, ChapterImage $chapterImage): void
    {
        $this->ensureChapterOwner($request, $chapter);
        abort_if($chapterImage->chapter_id !== $chapter->id, 404);
    }
}
