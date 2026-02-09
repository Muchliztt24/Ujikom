<?php

namespace App\View\Composers;

use App\Models\Genre;
use Illuminate\View\View;

class GlobalGenresComposer
{
    public function compose(View $view)
    {
        $genres = Genre::all();  // atau Genre::orderBy('name')->get(); kalau mau urut
        $view->with('globalGenres', $genres);
    }
}