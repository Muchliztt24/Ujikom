<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Work;
use App\Models\ChapterImage;
use Illuminate\Support\Facades\File;

$coverFiles = collect(File::files(storage_path('app/public/free-license/covers')))
    ->map(fn($file) => 'free-license/covers/' . $file->getFilename())
    ->values();

$comicFiles = collect(File::files(storage_path('app/public/free-license/comic-pages')))
    ->map(fn($file) => 'free-license/comic-pages/' . $file->getFilename())
    ->values();

foreach (Work::all() as $work) {
    $work->cover = $coverFiles->random();
    $work->save();
}

foreach (Work::with('chapters.images')->where('type', 'comic')->get() as $work) {
    foreach ($work->chapters as $chapter) {
        ChapterImage::where('chapter_id', $chapter->id)->delete();
        foreach ($comicFiles->shuffle()->take(4)->values() as $index => $path) {
            ChapterImage::create([
                'chapter_id' => $chapter->id,
                'image_url' => $path,
                'page_number' => $index + 1,
            ]);
        }
    }
}

echo 'updated_covers=' . Work::count() . PHP_EOL;
echo 'updated_comic_images=' . ChapterImage::count() . PHP_EOL;
