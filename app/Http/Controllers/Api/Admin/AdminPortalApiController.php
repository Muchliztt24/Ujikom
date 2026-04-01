<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Concerns\FormatsApiPayloads;
use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\ChapterImage;
use App\Models\Genre;
use App\Models\Role;
use App\Models\User;
use App\Models\Work;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminPortalApiController extends Controller
{
    use FormatsApiPayloads;

    public function dashboard(): JsonResponse
    {
        return response()->json([
            'data' => [
                'works' => [
                    'total' => Work::query()->count(),
                    'pending' => Work::query()->where('status', 'pending')->count(),
                    'approved' => Work::query()->where('status', 'approved')->count(),
                    'draft' => Work::query()->where('status', 'draft')->count(),
                ],
                'users' => User::query()->count(),
                'genres' => Genre::query()->count(),
                'chapters' => Chapter::query()->count(),
                'chapter_images' => ChapterImage::query()->count(),
                'recent_users' => User::query()
                    ->with('role')
                    ->latest()
                    ->take(5)
                    ->get()
                    ->map(fn (User $user) => $this->userPayload($user, true))
                    ->values(),
            ],
        ]);
    }

    public function roles(): JsonResponse
    {
        $roles = Role::query()->orderBy('name')->get();

        return response()->json([
            'data' => $roles->map(fn (Role $role) => $this->rolePayload($role))->values(),
        ]);
    }

    public function users(Request $request): JsonResponse
    {
        $users = User::query()
            ->with('role')
            ->when($request->filled('role_id'), fn ($query) => $query->where('role_id', $request->integer('role_id')))
            ->when($request->filled('role'), function ($query) use ($request) {
                $roleName = $request->string('role')->toString();

                $query->whereHas('role', fn ($roleQuery) => $roleQuery->where('name', $roleName));
            })
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = $request->string('q')->trim()->toString();

                $query->where(function ($builder) use ($keyword) {
                    $builder->where('name', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%");
                });
            })
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return $this->paginatedResponse($users, fn (User $user) => [
            ...$this->userPayload($user, true),
            'works_count' => $user->works()->count(),
            'bookmarks_count' => $user->bookmarks()->count(),
            'comments_count' => $user->comments()->count(),
        ], [
            'roles' => Role::query()->orderBy('name')->get()->map(fn (Role $role) => $this->rolePayload($role))->values(),
        ]);
    }

    public function showUser(User $user): JsonResponse
    {
        $user->load('role');

        return response()->json([
            'data' => [
                ...$this->userPayload($user, true),
                'works_count' => $user->works()->count(),
                'bookmarks_count' => $user->bookmarks()->count(),
                'comments_count' => $user->comments()->count(),
                'reading_histories_count' => $user->readingHistories()->count(),
            ],
        ]);
    }

    public function updateUser(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        $user->update($validated);
        $user->load('role');

        return response()->json([
            'message' => 'Data pengguna berhasil diperbarui.',
            'data' => $this->userPayload($user, true),
        ]);
    }

    public function genres(Request $request): JsonResponse
    {
        $genres = Genre::query()
            ->withCount('works')
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = $request->string('q')->trim()->toString();
                $query->where('name', 'like', "%{$keyword}%");
            })
            ->orderBy('name')
            ->paginate($request->integer('per_page', 15));

        return $this->paginatedResponse($genres, fn (Genre $genre) => $this->genrePayload($genre));
    }

    public function showGenre(Genre $genre): JsonResponse
    {
        $genre->loadCount('works');

        return response()->json([
            'data' => $this->genrePayload($genre),
        ]);
    }

    public function storeGenre(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('genres', 'name')],
        ]);

        $genre = Genre::query()->create($validated);
        $genre->loadCount('works');

        return response()->json([
            'message' => 'Genre berhasil ditambahkan.',
            'data' => $this->genrePayload($genre),
        ], 201);
    }

    public function updateGenre(Request $request, Genre $genre): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('genres', 'name')->ignore($genre->id)],
        ]);

        $genre->update($validated);
        $genre->loadCount('works');

        return response()->json([
            'message' => 'Genre berhasil diperbarui.',
            'data' => $this->genrePayload($genre),
        ]);
    }

    public function destroyGenre(Genre $genre): JsonResponse
    {
        $genre->delete();

        return response()->json([
            'message' => 'Genre berhasil dihapus.',
        ]);
    }
}
