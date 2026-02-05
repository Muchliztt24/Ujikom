<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Display a listing of genres
     */
    public function index()
    {
        $genres = Genre::withCount('works')->latest()->get();
        return view('admin.genres.index', compact('genres'));
    }

    /**
     * Show the form for creating a new genre
     */
    public function create()
    {
        return view('admin.genres.create');
    }

    /**
     * Store a newly created genre
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:genres,name',
        ]);

        Genre::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.genres.index')->with('success', 'Genre berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified genre
     */
    public function edit(Genre $genre)
    {
        return view('admin.genres.edit', compact('genre'));
    }

    /**
     * Update the specified genre
     */
    public function update(Request $request, Genre $genre)
    {
        $request->validate([
            'name' => 'required|unique:genres,name,' . $genre->id,
        ]);

        $genre->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.genres.index')->with('success', 'Genre berhasil diupdate');
    }

    /**
     * Remove the specified genre
     */
    public function destroy(Genre $genre)
    {
        $genre->delete();

        return redirect()->route('admin.genres.index')->with('success', 'Genre berhasil dihapus');
    }
}
