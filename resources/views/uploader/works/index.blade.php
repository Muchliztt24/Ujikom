@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <div style="display:flex; justify-content:space-between; gap:16px; align-items:center; flex-wrap:wrap;">
            <div>
                <h1>Karya Saya</h1>
                <p>Kelola semua karya novel dan komik yang Anda unggah.</p>
            </div>
            <a href="{{ route('works.create') }}" class="admin-btn primary">Tambah Karya Baru</a>
        </div>
    </div>

    <div class="content-body admin-card-grid">
        @if (session('success'))
            <div style="padding: 14px 16px; background: #d4edda; color: #155724; border-radius: 10px;">{{ session('success') }}</div>
        @endif

        @if ($works->count())
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Tipe</th>
                            <th>Genre</th>
                            <th>Status</th>
                            <th>Cover</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($works as $work)
                            <tr>
                                <td>
                                    <strong>{{ $work->title }}</strong>
                                    <div class="admin-muted" style="margin-top:6px;">{{ \Illuminate\Support\Str::limit($work->description ?: 'Tanpa deskripsi', 60) }}</div>
                                </td>
                                <td><span class="admin-chip">{{ ucfirst($work->type) }}</span></td>
                                <td>{{ $work->genres->pluck('name')->implode(', ') ?: '-' }}</td>
                                <td>
                                    <span class="admin-chip {{ $work->status === 'approved' ? 'success' : ($work->status === 'pending' ? 'warning' : '') }}">{{ ucfirst($work->status) }}</span>
                                </td>
                                <td>
                                    @if($work->cover)
                                        <img src="{{ asset('storage/' . $work->cover) }}" style="width: 56px; height: 76px; object-fit: cover; border-radius: 8px; display:block;">
                                    @else
                                        <span class="admin-muted">Tidak ada</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="admin-btn-row">
                                        <a href="{{ route('works.show', $work) }}" class="admin-btn secondary">Analytics</a>
                                        <a href="{{ route('works.chapters.index', $work) }}" class="admin-btn info">Chapter</a>
                                        <a href="{{ route('works.edit', $work) }}" class="admin-btn warning">Edit</a>
                                        @if ($work->status === 'draft')
                                            <form action="{{ route('works.submit', $work) }}" method="POST">@csrf<button type="submit" class="admin-btn secondary" onclick="return confirm('Kirim karya ini ke admin?')">Submit</button></form>
                                        @endif
                                        <form action="{{ route('works.destroy', $work) }}" method="POST">@csrf @method('DELETE')<button type="submit" class="admin-btn danger" onclick="return confirm('Hapus karya ini?')">Hapus</button></form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="admin-empty">Belum ada karya di katalogmu.</div>
        @endif
    </div>
@endsection


