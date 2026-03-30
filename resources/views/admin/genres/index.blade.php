@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <div style="display:flex; justify-content:space-between; gap:16px; align-items:center; flex-wrap:wrap;">
            <div>
                <h1>Kelola Genre</h1>
                <p>Daftar genre yang dipakai untuk mengelompokkan karya.</p>
            </div>
            <a href="{{ route('admin.genres.create') }}" class="admin-btn primary">Tambah Genre</a>
        </div>
    </div>

    <div class="content-body admin-card-grid">
        @if (session('success'))
            <div style="padding: 14px 16px; background: #d4edda; color: #155724; border-radius: 10px;">{{ session('success') }}</div>
        @endif

        <div class="admin-muted">Total genre: {{ $genres->count() }}</div>

        @if ($genres->count())
            <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap:18px;">
                @foreach ($genres as $genre)
                    <div class="admin-surface admin-form-card">
                        <div style="display:flex; justify-content:space-between; gap:12px; align-items:start;">
                            <div>
                                <div class="admin-form-title" style="margin-bottom: 8px;">{{ $genre->name }}</div>
                                <div class="admin-muted">{{ $genre->works_count ?? 0 }} karya menggunakan genre ini</div>
                                <div class="admin-muted" style="margin-top: 6px;">Dibuat {{ $genre->created_at->format('d M Y') }}</div>
                            </div>
                            <span class="admin-chip">Genre</span>
                        </div>
                        <div class="admin-btn-row" style="margin-top: 16px;">
                            <a href="{{ route('admin.genres.edit', $genre) }}" class="admin-btn info">Edit</a>
                            <form action="{{ route('admin.genres.destroy', $genre) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-btn danger" onclick="return confirm('Hapus genre ini?')">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="admin-empty">Belum ada genre.</div>
        @endif
    </div>
@endsection
