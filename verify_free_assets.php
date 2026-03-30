<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
foreach (App\Models\Work::withCount('chapters')->whereIn('id', [4, 10, 15, 45, 75])->get() as $work) {
    echo $work->title . '|' . $work->type . '|cover=' . $work->cover . '|chapters=' . $work->chapters_count . PHP_EOL;
}
