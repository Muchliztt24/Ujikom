<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;

class AdminChapterController extends Controller
{
    /**
     * List semua chapter (moderasi)
     */
    public function index()
    {
        $chapters = Chapter::with(['work.user'])
            ->latest()
            ->paginate(15);

        return view('admin.chapters.index', compact('chapters'));
    }

    /**
     * Lihat isi chapter (NOVEL / KOMIK)
     */
    public function show(Chapter $chapter)
    {
        $chapter->load([
            'work.user',
            'images', // untuk komik
        ]);

        return view('admin.chapters.show', compact('chapter'));
    }

    /**
     * Takedown / hapus chapter bermasalah
     */
    public function destroy(Chapter $chapter)
    {
        $chapter->delete();

        return back()->with('success', 'Chapter berhasil dihapus oleh admin.');
    }
}
