<?php

namespace Tests\Feature\Api;

use App\Models\Chapter;
use App\Models\Genre;
use App\Models\Role;
use App\Models\User;
use App\Models\Work;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\CreatesPortalFixtures;
use Tests\TestCase;

class AdminCrudApiTest extends TestCase
{
    use CreatesPortalFixtures;
    use RefreshDatabase;

    public function test_admin_can_update_user_profile_and_role(): void
    {
        $this->actingAsPortalRole('admin');
        $targetUser = $this->createUserForRole('uploader', [
            'name' => 'Uploader Lama',
            'email' => 'uploader.lama@example.test',
        ]);
        $userRole = Role::query()->where('name', 'user')->firstOrFail();

        $response = $this->patchJson("/api/admin/users/{$targetUser->id}", [
            'name' => 'Uploader Baru',
            'email' => 'uploader.baru@example.test',
            'role_id' => $userRole->id,
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('message', 'Data pengguna berhasil diperbarui.')
            ->assertJsonPath('data.name', 'Uploader Baru')
            ->assertJsonPath('data.email', 'uploader.baru@example.test')
            ->assertJsonPath('data.role', 'user');

        $this->assertDatabaseHas('users', [
            'id' => $targetUser->id,
            'name' => 'Uploader Baru',
            'email' => 'uploader.baru@example.test',
            'role_id' => $userRole->id,
        ]);
    }

    public function test_admin_can_create_update_and_delete_genre(): void
    {
        $this->actingAsPortalRole('admin');

        $createResponse = $this->postJson('/api/admin/genres', [
            'name' => 'Regression Genre Mobile',
        ]);

        $genreId = $createResponse->json('data.id');

        $createResponse
            ->assertCreated()
            ->assertJsonPath('data.name', 'Regression Genre Mobile');

        $updateResponse = $this->patchJson("/api/admin/genres/{$genreId}", [
            'name' => 'Regression Genre Mobile Updated',
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('data.name', 'Regression Genre Mobile Updated');

        $deleteResponse = $this->deleteJson("/api/admin/genres/{$genreId}");

        $deleteResponse
            ->assertOk()
            ->assertJsonPath('message', 'Genre berhasil dihapus.');

        $this->assertDatabaseMissing('genres', [
            'id' => $genreId,
        ]);
    }

    public function test_admin_can_approve_reject_and_delete_work(): void
    {
        $this->actingAsPortalRole('admin');
        $uploader = $this->createUserForRole('uploader');
        $work = $this->createWorkFor($uploader, [
            'title' => 'Mobile CRUD Comic Revised',
            'status' => 'pending',
        ]);

        $approveResponse = $this->postJson("/api/admin/works/{$work->id}/approve");
        $approveResponse
            ->assertOk()
            ->assertJsonPath('data.status', 'approved');

        $this->assertDatabaseHas('works', [
            'id' => $work->id,
            'status' => 'approved',
        ]);

        $rejectResponse = $this->postJson("/api/admin/works/{$work->id}/reject");
        $rejectResponse
            ->assertOk()
            ->assertJsonPath('data.status', 'draft');

        $this->assertDatabaseHas('works', [
            'id' => $work->id,
            'status' => 'draft',
        ]);

        $deleteResponse = $this->deleteJson("/api/admin/works/{$work->id}");
        $deleteResponse
            ->assertOk()
            ->assertJsonPath('message', 'Karya berhasil dihapus.');

        $this->assertDatabaseMissing('works', [
            'id' => $work->id,
        ]);
    }

    public function test_admin_can_delete_chapter_and_chapter_image(): void
    {
        Storage::fake('public');

        $this->actingAsPortalRole('admin');
        $uploader = $this->createUserForRole('uploader');
        $work = $this->createWorkFor($uploader);
        $chapter = $this->createChapterFor($work, [
            'chapter_number' => 91,
        ]);
        $imageUpload = UploadedFile::fake()->image('page-1.png');
        $storedPath = $imageUpload->store('chapters', 'public');
        $image = $this->createChapterImageFor($chapter, [
            'image_url' => $storedPath,
            'page_number' => 1,
        ]);

        $imageDeleteResponse = $this->deleteJson("/api/admin/chapter-images/{$image->id}");

        $imageDeleteResponse
            ->assertOk()
            ->assertJsonPath('message', 'Gambar chapter berhasil dihapus.');

        $this->assertDatabaseMissing('chapter_images', [
            'id' => $image->id,
        ]);
        Storage::disk('public')->assertMissing($storedPath);

        $chapterDeleteResponse = $this->deleteJson("/api/admin/chapters/{$chapter->id}");

        $chapterDeleteResponse
            ->assertOk()
            ->assertJsonPath('message', 'Chapter berhasil dihapus.');

        $this->assertDatabaseMissing('chapters', [
            'id' => $chapter->id,
        ]);
    }
}
