<?php

namespace App\Http\Controllers;

use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkController extends Controller
{
    /**
     * Menampilkan daftar karya uploader
     */
    public function index()
    {
        $works = Work::where('user_id', Auth::id())->latest()->get();
        return view('uploader.works.index', compact('works'));
    }

    /**
     * Menampilkan form tambah karya
     */
    public function create()
    {
        return view('uploader.works.create');
    }

    /**
     * Menyimpan karya baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:comic,novel',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
        }

        Work::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'cover' => $coverPath,
            'status' => 'draft',
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('works.index')->with('success', 'Karya berhasil dibuat');
    }

    /**
     * Menampilkan form edit karya
     */
    public function edit(Work $work)
    {

        return view('uploader.works.edit', compact('work'));
    }

    /**
     * Update karya
     */
    public function update(Request $request, Work $work)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:comic,novel',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            $work->cover = $request->file('cover')->store('covers', 'public');
        }

        $work->update([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
        ]);

        return redirect()->route('works.index')->with('success', 'Karya berhasil diperbarui');
    }
    
    public function show(Work $work)
    {
        return view('uploader.works.show', compact('work'));
    }

    /**
     * Hapus karya (opsional, boleh ditunda)
     */
    public function destroy(Work $work)
    {
        $work->delete();

        return redirect()->route('works.index')->with('success', 'Karya berhasil dihapus');
    }
}
