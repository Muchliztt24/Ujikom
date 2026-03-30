<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
echo 'works_without_cover=' . App\Models\Work::whereNull('cover')->orWhere('cover', '')->count() . PHP_EOL;
echo 'chapters=' . App\Models\Chapter::count() . PHP_EOL;
echo 'images=' . App\Models\ChapterImage::count() . PHP_EOL;
