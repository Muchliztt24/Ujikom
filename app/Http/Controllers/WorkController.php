<?php

namespace App\Http\Controllers;

use App\Models\Work;
use App\Models\Genre;
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
     * Form tambah karya
     */
    public function create()
    {
        $genres = Genre::all();
        return view('uploader.works.create', compact('genres'));
    }

    /**
     * Simpan karya baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:comic,novel',
            'genre_ids' => 'required|array',
            'genre_ids.*' => 'exists:genres,id',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
        }

        $work = Work::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'cover' => $coverPath,
            'status' => 'draft',
            'user_id' => Auth::id(),
        ]);

        // 🔗 attach genre
        $work->genres()->attach($request->genre_ids);

        return redirect()->route('works.index')->with('success', 'Karya berhasil dibuat');
    }

    /**
     * Form edit karya
     */
    public function edit(Work $work)
    {
        $genres = Genre::all();
        return view('uploader.works.edit', compact('work', 'genres'));
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
            'genre_ids' => 'required|array',
            'genre_ids.*' => 'exists:genres,id',
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

        // 🔄 sync genre
        $work->genres()->sync($request->genre_ids);

        return redirect()->route('works.index')->with('success', 'Karya berhasil diperbarui');
    }

    /**
     * Detail karya
     */
    public function show(Work $work)
    {
        return view('uploader.works.show', compact('work'));
    }

    /**
     * Hapus karya
     */
    public function destroy(Work $work)
    {
        $work->delete();
        return redirect()->route('works.index')->with('success', 'Karya berhasil dihapus');
    }
    public function submit(Work $work)
    {
        abort_if($work->user_id !== auth()->id(), 403);

        $work->update([
            'status' => 'pending',
        ]);

        return back()->with('success', 'Karya dikirim ke admin');
    }
    // Tampilkan semua karya yang sudah approved
    public function publicIndex()
    {
        $works = Work::with(['genres', 'user'])
            ->where('status', 'approved')
            ->latest()
            ->paginate(12);

        return view('works.public.index', compact('works'));
    }
    // Detail karya (user view)
    public function publicShow(Work $work)
    {
        abort_if($work->status !== 'approved', 404);

        $work->load([
            'genres',
            'user',
            'chapters' => function ($q) {
                $q->orderBy('order');
            },
        ]);

        return view('works.public.show', compact('work'));
    }
}
