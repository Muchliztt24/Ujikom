<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Work;
use Illuminate\Http\Request;

class AdminWorkController extends Controller
{
    // LIST SEMUA WORK (pending + approved)
    public function index()
    {
        $works = Work::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.works.index', compact('works'));
    }

    // DETAIL WORK (buat cek konten)
    public function show(Work $work)
    {
        return view('admin.works.show', compact('work'));
    }

    // APPROVE WORK
    public function approve(Work $work)
    {
        $work->update([
            'status' => 'approved'
        ]);

        return redirect()
            ->route('admin.works.index')
            ->with('success', 'Karya berhasil di-approve ✅');
    }

    // REJECT WORK
    public function reject(Work $work)
    {
        $work->update([
            'status' => 'draft'
        ]);

        return redirect()
            ->route('admin.works.index')
            ->with('success', 'Karya ditolak ❌');
    }

    // TAKEDOWN / HAPUS WORK
    public function destroy(Work $work)
    {
        $work->delete();

        return redirect()
            ->route('admin.works.index')
            ->with('success', 'Karya berhasil dihapus 🗑️');
    }
}
