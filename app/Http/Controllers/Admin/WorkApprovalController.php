<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Work;

class WorkApprovalController extends Controller
{
    public function index()
    {
        $works = Work::with('user', 'genres')->where('status', 'pending')->latest()->get();

        return view('admin.approvals.pending', compact('works'));
    }

    public function approve(Work $work)
    {
        $work->update([
            'status' => 'approved',
        ]);

        return back()->with('success', 'Karya berhasil disetujui');
    }

    public function reject(Work $work)
    {
        $work->update([
            'status' => 'draft',
        ]);

        return back()->with('success', 'Karya ditolak dan dikembalikan ke draft');
    }
    public function show(Work $work)
    {
        $work->load(['genres', 'chapters.user']);

        return view('admin.approvals.show', compact('work'));
    }
}
