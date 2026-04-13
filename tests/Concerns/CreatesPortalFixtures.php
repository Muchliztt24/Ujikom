<?php

namespace Tests\Concerns;

use App\Models\Chapter;
use App\Models\ChapterImage;
use App\Models\Genre;
use App\Models\Role;
use App\Models\User;
use App\Models\Work;
use Laravel\Sanctum\Sanctum;

trait CreatesPortalFixtures
{
    protected function createRoles(): array
    {
        return [
            'admin' => Role::query()->firstOrCreate(['name' => 'admin']),
            'uploader' => Role::query()->firstOrCreate(['name' => 'uploader']),
            'user' => Role::query()->firstOrCreate(['name' => 'user']),
        ];
    }

    protected function createUserForRole(string $roleName, array $attributes = []): User
    {
        $roles = $this->createRoles();
        $role = $roles[$roleName];

        return User::factory()->create(array_merge([
            'name' => ucfirst($roleName).' Test',
            'email' => fake()->unique()->safeEmail(),
            'role_id' => $role->id,
        ], $attributes));
    }

    protected function actingAsPortalRole(string $roleName, array $attributes = []): User
    {
        $user = $this->createUserForRole($roleName, $attributes);
        Sanctum::actingAs($user);

        return $user;
    }

    protected function createGenres(int $count = 2): array
    {
        $genres = [];

        for ($index = 1; $index <= $count; $index++) {
            $genres[] = Genre::query()->create([
                'name' => "Genre {$index} ".fake()->unique()->word(),
            ]);
        }

        return $genres;
    }

    protected function createWorkFor(User $user, array $attributes = [], ?array $genres = null): Work
    {
        $work = Work::query()->create(array_merge([
            'title' => 'Work '.fake()->unique()->words(2, true),
            'original_author' => fake()->name(),
            'description' => fake()->sentence(),
            'type' => 'comic',
            'status' => 'draft',
            'user_id' => $user->id,
        ], $attributes));

        $genres ??= $this->createGenres();
        $work->genres()->sync(collect($genres)->pluck('id')->all());

        return $work->fresh(['genres', 'user']);
    }

    protected function createChapterFor(Work $work, array $attributes = []): Chapter
    {
        return Chapter::query()->create(array_merge([
            'work_id' => $work->id,
            'title' => 'Chapter '.fake()->unique()->words(2, true),
            'chapter_number' => 1,
            'text_content' => fake()->paragraph(),
        ], $attributes));
    }

    protected function createChapterImageFor(Chapter $chapter, array $attributes = []): ChapterImage
    {
        return ChapterImage::query()->create(array_merge([
            'chapter_id' => $chapter->id,
            'image_url' => 'chapters/'.fake()->uuid().'.png',
            'page_number' => 1,
        ], $attributes));
    }
}
