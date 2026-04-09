<?php

namespace App\Providers;

use App\Models\Genre;
use App\Support\SocialAuth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Discord\DiscordExtendSocialite;
use SocialiteProviders\Manager\SocialiteWasCalled;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Event::listen(SocialiteWasCalled::class, DiscordExtendSocialite::class.'@handle');

        $genres = Schema::hasTable('genres')
            ? Genre::query()->orderBy('name')->get()
            : collect();

        View::share('globalGenres', $genres);
    }
}
