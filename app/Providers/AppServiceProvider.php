<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Genre;
use App\View\Composers\GlobalGenresComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Pakai View::share → langsung inject ke SEMUA view & semua child view
        $genres = Genre::orderBy('name', 'asc')->get();
        View::share('globalGenres', $genres);

        // Optional: tambah debug kalau mau
        // View::share('debug_genre_count', $genres->count());
    }
}
