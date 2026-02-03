<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\ChapterImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChapterImageController extends Controller
{
    /** =========================
     *  INDEX
     *  ========================= */
    public function index(Chapter $chapter)
    {
        $images = $chapter->images()->orderBy('page_number')->get();

        return view('uploader.chapter_images.index', compact('chapter', 'images'));
    }

    /** =========================
     *  CREATE
     *  ========================= */
    public function create(Chapter $chapter)
    {
        return view('uploader.chapter_images.create', compact('chapter'));
    }

    /** =========================
     *  STORE
     *  ========================= */
    public function store(Request $request, Chapter $chapter)
    {
        $request->validate([
            'images'   => 'required',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $page = $chapter->images()->max('page_number') ?? 0;

        foreach ($request->file('images') as $image) {
            $path = $image->store('chapters', 'public');

            ChapterImage::create([
                'chapter_id'  => $chapter->id,
                'image_url'   => $path,
                'page_number' => ++$page,
            ]);
        }

        return redirect()
            ->route('chapters.images.index', $chapter)
            ->with('success', 'Gambar berhasil diupload');
    }

    /** =========================
     *  EDIT
     *  ========================= */
    public function edit(Chapter $chapter, ChapterImage $image)
    {
        return view('uploader.chapter_images.edit', compact('chapter', 'image'));
    }

    /** =========================
     *  UPDATE
     *  ========================= */
    public function update(Request $request, Chapter $chapter, ChapterImage $image)
    {
        $request->validate([
            'page_number' => 'required|integer|min:1',
        ]);

        $image->update([
            'page_number' => $request->page_number,
        ]);

        return redirect()
            ->route('chapters.images.index', $chapter)
            ->with('success', 'Gambar berhasil diperbarui');
    }
    
    public function show(Chapter $chapter, ChapterImage $image)
    {
        return view('uploader.chapter_images.show', compact('chapter', 'image'));
    }

    /** =========================
     *  DESTROY
     *  ========================= */
    public function destroy(Chapter $chapter, ChapterImage $image)
    {
        Storage::disk('public')->delete($image->image_url);
        $image->delete();

        return back()->with('success', 'Gambar berhasil dihapus');
    }
}
