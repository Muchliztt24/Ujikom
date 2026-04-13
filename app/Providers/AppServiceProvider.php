<?php

namespace App\Providers;

use App\Models\Genre;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $genres = Schema::hasTable('genres')
            ? Genre::query()->orderBy('name')->get()
            : collect();

        View::share('globalGenres', $genres);
    }
}
