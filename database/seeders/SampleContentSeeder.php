<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\ChapterImage;
use App\Models\Work;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SampleContentSeeder extends Seeder
{
    public function run(): void
    {
        $coverFiles = $this->getAssetFiles('free-license/covers');
        $comicPageFiles = $this->getAssetFiles('free-license/comic-pages');

        if ($coverFiles->isEmpty()) {
            $coverFiles = $this->getAssetFiles('covers');
        }

        if ($coverFiles->isEmpty()) {
            return;
        }

        Work::query()->get()->each(function (Work $work) use ($coverFiles) {
            $work->update([
                'cover' => $coverFiles->random(),
            ]);
        });

        Work::with(['chapters.images'])->get()->each(function (Work $work) use ($coverFiles, $comicPageFiles) {
            if ($work->chapters->isEmpty()) {
                $this->createChaptersForWork($work, $comicPageFiles);
                $work->load(['chapters.images']);
            }

            if ($work->type === 'comic' && $comicPageFiles->isNotEmpty()) {
                $work->chapters->each(function (Chapter $chapter) use ($comicPageFiles) {
                    ChapterImage::where('chapter_id', $chapter->id)->delete();
                    $this->createImagesForChapter($chapter, $comicPageFiles);
                });
            }
        });
    }

    protected function createChaptersForWork(Work $work, $comicPageFiles): void
    {
        $chapterTitles = [
            1 => 'Permulaan',
            2 => 'Bayangan Pertama',
            3 => 'Langkah Penentu',
        ];

        foreach ($chapterTitles as $number => $title) {
            $chapter = Chapter::create([
                'work_id' => $work->id,
                'chapter_number' => $number,
                'title' => $title,
                'text_content' => $work->type === 'novel'
                    ? $this->buildNovelContent($work->title, $number, $title)
                    : null,
            ]);

            if ($work->type === 'comic' && $comicPageFiles->isNotEmpty()) {
                $this->createImagesForChapter($chapter, $comicPageFiles);
            }
        }
    }

    protected function createImagesForChapter(Chapter $chapter, $coverFiles): void
    {
        $selectedFiles = $coverFiles->shuffle()->take(4)->values();

        foreach ($selectedFiles as $index => $sourcePath) {
            $extension = pathinfo($sourcePath, PATHINFO_EXTENSION);
            $targetPath = 'chapters/sample-' . $chapter->id . '-' . ($index + 1) . '-' . Str::random(8) . '.' . $extension;

            if (!Storage::disk('public')->exists($targetPath)) {
                Storage::disk('public')->copy($sourcePath, $targetPath);
            }

            ChapterImage::create([
                'chapter_id' => $chapter->id,
                'image_url' => $targetPath,
                'page_number' => $index + 1,
            ]);
        }
    }

    protected function buildNovelContent(string $title, int $chapterNumber, string $chapterTitle): string
    {
        return "Chapter {$chapterNumber}: {$chapterTitle}\n\n"
            . "Langit senja menggantung rendah ketika kisah {$title} mulai bergerak ke arah yang tak pernah diduga. "
            . "Setiap langkah menghadirkan tanda-tanda kecil bahwa sesuatu yang besar sedang menunggu di depan.\n\n"
            . "Tokoh utama menahan napas, mencoba memahami suara-suara di sekelilingnya, lalu memilih maju meski jawaban belum benar-benar tampak. "
            . "Keputusan sederhana itu justru membuka pintu pada konflik yang lebih dalam, relasi yang lebih rumit, dan harapan yang tak mudah dijelaskan.\n\n"
            . "Di ujung bab ini, satu hal menjadi jelas: perjalanan baru saja dimulai, dan tidak semua orang akan keluar sebagai pribadi yang sama.";
    }

    protected function getAssetFiles(string $directory)
    {
        $path = storage_path('app/public/' . $directory);

        if (!File::isDirectory($path)) {
            return collect();
        }

        return collect(File::files($path))
            ->map(fn($file) => $directory . '/' . $file->getFilename())
            ->values();
    }
}
