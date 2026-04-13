<?php

namespace Tests\Feature\Api;

use App\Models\Chapter;
use App\Models\ChapterImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\CreatesPortalFixtures;
use Tests\TestCase;

class UploaderCrudApiTest extends TestCase
{
    use CreatesPortalFixtures;
    use RefreshDatabase;

    public function test_uploader_can_create_update_submit_and_delete_work(): void
    {
        Storage::fake('public');

        $uploader = $this->actingAsPortalRole('uploader', [
            'email' => 'asep@nokomi.test',
        ]);
        [$genreA, $genreB, $genreC] = $this->createGenres(3);

        $createResponse = $this->post('/api/uploader/works', [
            'title' => 'Mobile CRUD Comic',
            'original_author' => 'QA Automation Team',
            'type' => 'comic',
            'description' => 'Work comic untuk validasi CRUD uploader dari Flutter.',
            'genre_ids' => [$genreA->id, $genreB->id, $genreC->id],
            'cover' => UploadedFile::fake()->image('mobile-cover.png'),
        ], ['Accept' => 'application/json']);

        $workId = $createResponse->json('data.id');

        $createResponse
            ->assertCreated()
            ->assertJsonPath('data.title', 'Mobile CRUD Comic')
            ->assertJsonPath('data.status', 'draft');

        $work = \App\Models\Work::query()->findOrFail($workId);
        $this->assertNotNull($work->cover);
        Storage::disk('public')->assertExists($work->cover);

        $updateResponse = $this->post("/api/uploader/works/{$workId}", [
            '_method' => 'PATCH',
            'title' => 'Mobile CRUD Comic Revised',
            'original_author' => 'QA Automation Team',
            'type' => 'comic',
            'description' => 'Versi revisi work comic untuk validasi update.',
            'genre_ids' => [$genreA->id, $genreB->id],
            'cover' => UploadedFile::fake()->image('mobile-cover-revised.png'),
        ], ['Accept' => 'application/json']);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('data.title', 'Mobile CRUD Comic Revised');

        $this->assertDatabaseHas('works', [
            'id' => $workId,
            'title' => 'Mobile CRUD Comic Revised',
        ]);

        $submitResponse = $this->postJson("/api/uploader/works/{$workId}/submit");

        $submitResponse
            ->assertOk()
            ->assertJsonPath('data.status', 'pending');

        $this->assertDatabaseHas('works', [
            'id' => $workId,
            'status' => 'pending',
        ]);

        $deleteResponse = $this->deleteJson("/api/uploader/works/{$workId}");

        $deleteResponse
            ->assertOk()
            ->assertJsonPath('message', 'Karya berhasil dihapus.');

        $this->assertDatabaseMissing('works', [
            'id' => $workId,
        ]);
    }

    public function test_uploader_can_create_update_and_delete_chapter(): void
    {
        $uploader = $this->actingAsPortalRole('uploader');
        $work = $this->createWorkFor($uploader, [
            'title' => 'Mobile CRUD Comic Revised',
        ]);

        $createResponse = $this->postJson("/api/uploader/works/{$work->id}/chapters", [
            'chapter_number' => 91,
            'title' => 'Kickoff Mobile Chapter',
            'text_content' => 'Chapter ini dipakai untuk validasi create chapter via Flutter.',
        ]);

        $chapterId = $createResponse->json('data.id');

        $createResponse
            ->assertCreated()
            ->assertJsonPath('data.chapter_number', 91)
            ->assertJsonPath('data.title', 'Kickoff Mobile Chapter');

        $updateResponse = $this->patchJson("/api/uploader/works/{$work->id}/chapters/{$chapterId}", [
            'chapter_number' => 92,
            'title' => 'Kickoff Mobile Chapter Updated',
            'text_content' => 'Chapter ini sudah diperbarui untuk validasi update.',
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('data.chapter_number', 92)
            ->assertJsonPath('data.title', 'Kickoff Mobile Chapter Updated');

        $this->assertDatabaseHas('chapters', [
            'id' => $chapterId,
            'work_id' => $work->id,
            'chapter_number' => 92,
        ]);

        $deleteResponse = $this->deleteJson("/api/uploader/works/{$work->id}/chapters/{$chapterId}");

        $deleteResponse
            ->assertOk()
            ->assertJsonPath('message', 'Chapter berhasil dihapus.');

        $this->assertDatabaseMissing('chapters', [
            'id' => $chapterId,
        ]);
    }

    public function test_uploader_can_upload_update_and_delete_chapter_images(): void
    {
        Storage::fake('public');

        $uploader = $this->actingAsPortalRole('uploader');
        $work = $this->createWorkFor($uploader);
        $chapter = $this->createChapterFor($work, [
            'chapter_number' => 92,
            'title' => 'Kickoff Mobile Chapter Updated',
        ]);

        $createResponse = $this->post("/api/uploader/chapters/{$chapter->id}/images", [
            'images' => [
                UploadedFile::fake()->image('page-1.png'),
                UploadedFile::fake()->image('page-2.png'),
            ],
        ], ['Accept' => 'application/json']);

        $createResponse
            ->assertCreated()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.page_number', 1)
            ->assertJsonPath('data.1.page_number', 2);

        $imageId = $createResponse->json('data.0.id');
        $image = ChapterImage::query()->findOrFail($imageId);
        Storage::disk('public')->assertExists($image->image_url);

        $oldPath = $image->image_url;
        $updateResponse = $this->post("/api/uploader/chapters/{$chapter->id}/images/{$imageId}", [
            '_method' => 'PATCH',
            'page_number' => 10,
            'image' => UploadedFile::fake()->image('page-10.png'),
        ], ['Accept' => 'application/json']);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('data.page_number', 10);

        $image->refresh();
        $this->assertNotSame($oldPath, $image->image_url);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($image->image_url);

        $deleteResponse = $this->deleteJson("/api/uploader/chapters/{$chapter->id}/images/{$imageId}");

        $deleteResponse
            ->assertOk()
            ->assertJsonPath('message', 'Gambar chapter berhasil dihapus.');

        $this->assertDatabaseMissing('chapter_images', [
            'id' => $imageId,
        ]);
        Storage::disk('public')->assertMissing($image->image_url);
    }
}
