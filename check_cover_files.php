<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
foreach (Illuminate\Support\Facades\File::files(storage_path('app/public/free-license/covers')) as $file) {
    echo $file->getFilename() . PHP_EOL;
}
