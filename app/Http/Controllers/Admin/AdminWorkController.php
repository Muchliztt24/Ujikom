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
        $status = request('status');

        $works = Work::with(['user', 'genres'])
            ->latest()
            ->when(in_array($status, ['pending', 'approved', 'draft']), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->paginate(15);

        return view('admin.works.index', compact('works', 'status'));
    }

    // DETAIL WORK (buat cek konten)
    public function show(Work $work)
    {
        $work->load([
            'user.role',
            'genres',
            'chapters' => fn($query) => $query->withCount('images')->orderBy('chapter_number'),
        ]);

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
