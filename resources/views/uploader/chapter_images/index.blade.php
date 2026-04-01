@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <div style="display:flex; justify-content:space-between; gap:16px; align-items:center; flex-wrap:wrap;">
            <div>
                <h1>Gambar Chapter</h1>
                <p>{{ $chapter->work->title }} • Chapter {{ $chapter->chapter_number }}{{ $chapter->title ? ' - ' . $chapter->title : '' }}</p>
            </div>
            <div class="admin-btn-row">
                <a href="{{ route('works.chapters.index', $chapter->work) }}" class="admin-btn secondary">Kembali</a>
                <a href="{{ route('chapters.images.create', $chapter) }}" class="admin-btn primary">Tambah Gambar</a>
            </div>
        </div>
    </div>

    <div class="content-body admin-card-grid">
        @if (session('success'))
            <div style="padding: 14px 16px; background: #d4edda; color: #155724; border-radius: 10px;">{{ session('success') }}</div>
        @endif

        @if ($images->count())
            <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap:18px;">
                @foreach ($images as $image)
                    <div class="admin-surface" style="overflow:hidden;">
                        <img src="{{ asset('storage/' . $image->image_url) }}" style="width:100%; height:280px; object-fit:cover; display:block;">
                        <div style="padding:16px;">
                            <div class="admin-form-title" style="font-size:16px; margin-bottom:8px;">Page {{ $image->page_number }}</div>
                            <div class="admin-btn-row">
                                <a href="{{ route('chapters.images.edit', [$chapter, $image]) }}" class="admin-btn warning">Edit</a>
                                <form action="{{ route('chapters.images.destroy', [$chapter, $image]) }}" method="POST">@csrf @method('DELETE')<button type="submit" class="admin-btn danger" onclick="return confirm('Hapus gambar ini?')">Hapus</button></form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="admin-empty">Belum ada gambar untuk chapter ini.</div>
        @endif
    </div>
@endsection




