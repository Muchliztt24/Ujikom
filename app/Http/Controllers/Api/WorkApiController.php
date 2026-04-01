<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\FormatsApiPayloads;
use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Genre;
use App\Models\Work;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkApiController extends Controller
{
    use FormatsApiPayloads;

    public function index(Request $request): JsonResponse
    {
        $works = Work::query()
            ->with(['genres', 'user'])
            ->withCount('chapters')
            ->where('status', 'approved')
            ->when($request->filled('type'), fn ($query) => $query->where('type', $request->string('type')->toString()))
            ->when($request->filled('genre_id'), function ($query) use ($request) {
                $genreId = (int) $request->integer('genre_id');

                $query->whereHas('genres', fn ($genreQuery) => $genreQuery->where('genres.id', $genreId));
            })
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = $request->string('q')->trim()->toString();

                $query->where(function ($builder) use ($keyword) {
                    $builder->where('title', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%");
                });
            })
            ->latest()
            ->get();

        return response()->json([
            'data' => $works->map(fn (Work $work) => $this->workPayload($work))->values(),
        ]);
    }

    public function home(Request $request): JsonResponse
    {
        $selectedGenre = null;
        $genreId = $request->integer('genre');

        $works = Work::query()
            ->with(['genres', 'user'])
            ->withCount('chapters')
            ->where('status', 'approved')
            ->when($genreId, function ($query) use ($genreId, &$selectedGenre) {
                $selectedGenre = Genre::query()->withCount('works')->find($genreId);

                $query->whereHas('genres', function ($genreQuery) use ($genreId) {
                    $genreQuery->where('genres.id', $genreId);
                });
            })
            ->latest()
            ->paginate($request->integer('per_page', 12))
            ->withQueryString();

        $genres = Genre::query()
            ->withCount('works')
            ->orderBy('name')
            ->get();

        return $this->paginatedResponse($works, fn (Work $work) => $this->workPayload($work), [
            'genres' => $genres->map(fn (Genre $genre) => $this->genrePayload($genre))->values(),
            'selected_genre' => $selectedGenre ? $this->genrePayload($selectedGenre) : null,
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
            'data' => $this->workPayload($work, true),
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
                ...$this->chapterPayload($chapter, includeText: true, includeImages: true, includeComments: false),
                'work_title' => $work->title,
            ],
        ]);
    }

    public function genres(): JsonResponse
    {
        $genres = Genre::query()
            ->withCount('works')
            ->orderBy('name')
            ->get();

        return response()->json([
            'data' => $genres->map(fn (Genre $genre) => $this->genrePayload($genre))->values(),
        ]);
    }
}
