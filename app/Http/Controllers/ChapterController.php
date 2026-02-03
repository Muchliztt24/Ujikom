<?php

namespace App\Http\Controllers;

use App\Models\Work;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChapterController extends Controller
{
    
    public function index(Work $work)
    {
        if ($work->user_id !== Auth::id()) {
            abort(403);
        }

        $chapters = $work->chapters;

        return view('uploader.chapters.index', compact('work', 'chapters'));
    }

    
    public function create(Work $work)
    {
        if ($work->user_id !== Auth::id()) {
            abort(403);
        }

        return view('uploader.chapters.create', compact('work'));
    }

    public function store(Request $request, Work $work)
    {
        if ($work->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'chapter_number' => 'required|integer',
            'text_content' => 'nullable|string',
        ]);

        Chapter::create([
            'work_id' => $work->id,
            'title' => $request->title,
            'chapter_number' => $request->chapter_number,
            'text_content' => $request->text_content,
        ]);

        return redirect()
            ->route('works.chapters.index', $work)
            ->with('success', 'Chapter berhasil ditambahkan');
    }

    
    public function edit(Work $work, Chapter $chapter)
    {
        if ($work->user_id !== Auth::id()) {
            abort(403);
        }

        return view('uploader.chapters.edit', compact('work', 'chapter'));
    }

    public function update(Request $request, Work $work, Chapter $chapter)
    {
        if ($work->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'chapter_number' => 'required|integer',
            'text_content' => 'nullable|string',
        ]);

        $chapter->update([
            'title' => $request->title,
            'chapter_number' => $request->chapter_number,
            'text_content' => $request->text_content,
        ]);

        return redirect()
            ->route('works.chapters.index', $work)
            ->with('success', 'Chapter berhasil diperbarui');
    }


    public function destroy(Work $work, Chapter $chapter)
    {
        if ($work->user_id !== Auth::id()) {
            abort(403);
        }

        $chapter->delete();

        return redirect()
            ->route('works.chapters.index', $work)
            ->with('success', 'Chapter berhasil dihapus');
    }
}
