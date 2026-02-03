<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    // List bookmark user
    public function index()
    {
        $bookmarks = Bookmark::with('work')
            ->where('user_id', Auth::id())
            ->get();

        return view('bookmarks.index', compact('bookmarks'));
    }

    // Simpan / update bookmark
    public function store(Request $request, Work $work)
    {
        Bookmark::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'work_id' => $work->id,
            ],
            [
                'last_chapter_read' => $request->last_chapter_read,
            ]
        );

        return back()->with('success', 'Bookmark disimpan');
    }

    // Hapus bookmark
    public function destroy(Work $work)
    {
        Bookmark::where('user_id', Auth::id())
            ->where('work_id', $work->id)
            ->delete();

        return back()->with('success', 'Bookmark dihapus');
    }
}
