<?php

namespace App\Http\Controllers\Api\Concerns;

use App\Models\Chapter;
use App\Models\ChapterImage;
use App\Models\Comment;
use App\Models\Genre;
use App\Models\Role;
use App\Models\User;
use App\Models\Work;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

trait FormatsApiPayloads
{
    protected function paginatedResponse(LengthAwarePaginator $paginator, callable $transformer, array $extra = []): JsonResponse
    {
        return response()->json(array_merge([
            'data' => collect($paginator->items())
                ->map($transformer)
                ->values(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ], $extra));
    }

    protected function rolePayload(Role $role): array
    {
        return [
            'id' => $role->id,
            'name' => $role->name,
        ];
    }

    protected function userPayload(User $user, bool $includePrivate = false): array
    {
        $payload = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $this->makePublicUrl($user->avatar),
            'role' => $user->role?->name ?? 'user',
            'role_id' => $user->role_id,
            'created_at' => optional($user->created_at)?->toIso8601String(),
            'updated_at' => optional($user->updated_at)?->toIso8601String(),
        ];

        if (! $includePrivate) {
            unset($payload['email'], $payload['role_id'], $payload['created_at'], $payload['updated_at']);
        }

        return $payload;
    }

    protected function genrePayload(Genre $genre): array
    {
        return [
            'id' => $genre->id,
            'name' => $genre->name,
            'icon' => genre_icon($genre->name),
            'works_count' => $genre->works_count ?? $genre->works()->count(),
            'created_at' => optional($genre->created_at)?->toIso8601String(),
            'updated_at' => optional($genre->updated_at)?->toIso8601String(),
        ];
    }

    protected function workPayload(Work $work, bool $includeChapters = false): array
    {
        $payload = [
            'id' => $work->id,
            'title' => $work->title,
            'description' => $work->description,
            'type' => $work->type,
            'status' => $work->status,
            'cover_path' => $work->cover,
            'cover_url' => $this->makePublicUrl($work->cover),
            'user_id' => $work->user_id,
            'author' => $work->user?->name,
            'author_detail' => $work->user ? $this->userPayload($work->user, false) : null,
            'genres' => $work->relationLoaded('genres')
                ? $work->genres->pluck('name')->values()
                : [],
            'genre_details' => $work->relationLoaded('genres')
                ? $work->genres->map(fn (Genre $genre) => $this->genrePayload($genre))->values()
                : [],
            'chapters_count' => $work->chapters_count ?? ($work->relationLoaded('chapters') ? $work->chapters->count() : 0),
            'created_at' => optional($work->created_at)?->toIso8601String(),
            'updated_at' => optional($work->updated_at)?->toIso8601String(),
        ];

        if ($includeChapters && $work->relationLoaded('chapters')) {
            $payload['chapters'] = $work->chapters
                ->map(fn (Chapter $chapter) => $this->chapterPayload($chapter))
                ->values();
        }

        return $payload;
    }

    protected function chapterPayload(Chapter $chapter, bool $includeText = false, bool $includeImages = false, bool $includeComments = false): array
    {
        $payload = [
            'id' => $chapter->id,
            'work_id' => $chapter->work_id,
            'title' => $chapter->title ?: 'Chapter '.$chapter->chapter_number,
            'chapter_number' => $chapter->chapter_number,
            'has_text_content' => filled($chapter->text_content),
            'excerpt' => Str::limit(trim((string) strip_tags((string) $chapter->text_content)), 160),
            'images_count' => $chapter->images_count ?? ($chapter->relationLoaded('images') ? $chapter->images->count() : 0),
            'comments_count' => $chapter->comments_count ?? ($chapter->relationLoaded('comments') ? $chapter->comments->count() : 0),
            'created_at' => optional($chapter->created_at)?->toIso8601String(),
            'updated_at' => optional($chapter->updated_at)?->toIso8601String(),
        ];

        if ($chapter->relationLoaded('work') && $chapter->work) {
            $payload['work'] = [
                'id' => $chapter->work->id,
                'title' => $chapter->work->title,
                'type' => $chapter->work->type,
                'status' => $chapter->work->status,
            ];
        }

        if ($includeText) {
            $payload['text_content'] = $chapter->text_content;
        }

        if ($includeImages && $chapter->relationLoaded('images')) {
            $payload['images'] = $chapter->images
                ->map(fn (ChapterImage $image) => $this->chapterImagePayload($image))
                ->values();
        }

        if ($includeComments && $chapter->relationLoaded('comments')) {
            $payload['comments'] = $chapter->comments
                ->map(fn (Comment $comment) => $this->commentPayload($comment))
                ->values();
        }

        return $payload;
    }

    protected function chapterImagePayload(ChapterImage $image): array
    {
        return [
            'id' => $image->id,
            'chapter_id' => $image->chapter_id,
            'page_number' => $image->page_number,
            'image_path' => $image->image_url,
            'image_url' => $this->makePublicUrl($image->image_url),
        ];
    }

    protected function commentPayload(Comment $comment): array
    {
        return [
            'id' => $comment->id,
            'chapter_id' => $comment->chapter_id,
            'content' => $comment->content,
            'user' => $comment->relationLoaded('user') && $comment->user ? $this->userPayload($comment->user) : null,
            'created_at' => optional($comment->created_at)?->toIso8601String(),
            'updated_at' => optional($comment->updated_at)?->toIso8601String(),
        ];
    }

    protected function makePublicUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return url('storage/'.$path);
    }
}
