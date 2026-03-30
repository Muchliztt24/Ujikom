<?php

namespace App\View\Composers;

use App\Models\Genre;
use Illuminate\View\View;

class GlobalGenresComposer
{
    public function compose(View $view)
    {
        $genres = Genre::orderBy('name')->get();
        $view->with('globalGenres', $genres);
    }
}
