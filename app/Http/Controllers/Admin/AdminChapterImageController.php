<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChapterImage;

class AdminChapterImageController extends Controller
{
    /**
     * List semua image chapter (komik)
     */
    public function index()
    {
        $images = ChapterImage::with('chapter.work')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.chapter-images.index', compact('images'));
    }

    /**
     * Lihat image tertentu
     */
    public function show(ChapterImage $chapterImage)
    {
        $chapterImage->load('chapter.work.user', 'chapter.work.genres', 'chapter.images');

        return view('admin.chapter-images.show', compact('chapterImage'));
    }

    /**
     * Hapus halaman komik bermasalah
     */
    public function destroy(ChapterImage $chapterImage)
    {
        $chapterImage->delete();

        return back()->with('success', 'Gambar chapter berhasil dihapus.');
    }
}
