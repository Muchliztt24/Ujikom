@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <div style="display:flex; justify-content:space-between; gap:16px; align-items:center; flex-wrap:wrap;">
            <div>
                <h1>Chapter Karya</h1>
                <p>Kelola chapter untuk <strong>{{ $work->title }}</strong>.</p>
            </div>
            <div class="admin-btn-row">
                <a href="{{ route('works.index') }}" class="admin-btn secondary">Kembali ke Karya</a>
                <a href="{{ route('works.chapters.create', $work) }}" class="admin-btn primary">Tambah Chapter</a>
            </div>
        </div>
    </div>

    <div class="content-body admin-card-grid">
        @if (session('success'))
            <div style="padding: 14px 16px; background: #d4edda; color: #155724; border-radius: 10px;">{{ session('success') }}</div>
        @endif

        @if ($chapters->count())
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Isi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($chapters as $chapter)
                            <tr>
                                <td>{{ $chapter->chapter_number }}</td>
                                <td>
                                    <strong>{{ $chapter->title ?: 'Tanpa judul' }}</strong>
                                    <div class="admin-muted" style="margin-top:6px;">Chapter {{ $chapter->chapter_number }}</div>
                                </td>
                                <td>
                                    @if ($work->type === 'comic')
                                        <span class="admin-chip">Kelola via gambar chapter</span>
                                    @else
                                        <span class="admin-chip {{ filled($chapter->text_content) ? 'success' : 'danger' }}">{{ filled($chapter->text_content) ? 'Ada teks' : 'Kosong' }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="admin-btn-row">
                                        <a href="{{ route('chapters.images.index', $chapter) }}" class="admin-btn info">Images</a>
                                        <a href="{{ route('works.chapters.edit', [$work, $chapter]) }}" class="admin-btn warning">Edit</a>
                                        <form action="{{ route('works.chapters.destroy', [$work, $chapter]) }}" method="POST">@csrf @method('DELETE')<button type="submit" class="admin-btn danger" onclick="return confirm('Hapus chapter ini?')">Hapus</button></form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="admin-empty">Belum ada chapter untuk karya ini.</div>
        @endif
    </div>
@endsection
