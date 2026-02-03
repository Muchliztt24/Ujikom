<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Chapter $chapter)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'chapter_id' => $chapter->id,
            'content' => $request->get('content'),
        ]);

        return back()->with('success', 'Komentar ditambahkan');
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            abort(403);
        }

        $comment->delete();

        return back()->with('success', 'Komentar dihapus');
    }
}
