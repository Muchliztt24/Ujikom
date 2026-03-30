@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Daftar Chapter</h1>
        <p>Moderasi chapter novel dan komik.</p>
    </div>

    <div class="content-body">
        @if (session('success'))
            <div style="margin-bottom: 16px; padding: 14px 16px; background: #d4edda; color: #155724; border-radius: 10px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="admin-muted" style="margin-bottom: 20px; font-weight: 600;">
            Total chapter: {{ $chapters->total() }}
        </div>

        @if ($chapters->count())
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="padding: 14px; text-align: left;">Chapter</th>
                            <th style="padding: 14px; text-align: left;">Judul</th>
                            <th style="padding: 14px; text-align: left;">Karya</th>
                            <th style="padding: 14px; text-align: left;">Tipe</th>
                            <th style="padding: 14px; text-align: left;">Isi</th>
                            <th style="padding: 14px; text-align: left;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($chapters as $chapter)
                            <tr>
                                <td>{{ $chapter->chapter_number }}</td>
                                <td>{{ $chapter->title ?: '-' }}</td>
                                <td>{{ $chapter->work->title }}</td>
                                <td>
                                    <span class="admin-chip">{{ $chapter->work->type === 'novel' ? 'Novel' : 'Comic' }}</span>
                                </td>
                                <td style="padding: 14px;">
                                    @if ($chapter->work->type === 'comic')
                                        {{ $chapter->images_count }} gambar
                                    @else
                                        {{ \Illuminate\Support\Str::words($chapter->text_content ?: 'Belum ada isi', 8) }}
                                    @endif
                                </td>
                                <td>
                                    <div class="admin-btn-row">
                                        <a href="{{ route('admin.chapters.show', $chapter) }}"
                                            style="padding: 8px 12px; text-decoration: none; background: #17a2b8; color: white; border-radius: 8px; font-size: 13px; font-weight: 700;">
                                            Lihat
                                        </a>
                                        <form action="{{ route('admin.chapters.destroy', $chapter) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Hapus chapter ini?')"
                                                style="padding: 8px 12px; border: none; background: #dc3545; color: white; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer;">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="admin-pagination">
                <div class="admin-pagination-meta">
                    Menampilkan {{ $chapters->firstItem() }}-{{ $chapters->lastItem() }} dari {{ $chapters->total() }} chapter
                </div>
                <div class="admin-pagination-links">
                    <a href="{{ $chapters->previousPageUrl() ?: '#' }}" class="admin-page-link {{ $chapters->onFirstPage() ? 'disabled' : '' }}">
                        &laquo; Previous
                    </a>
                    <a href="{{ $chapters->nextPageUrl() ?: '#' }}" class="admin-page-link {{ $chapters->hasMorePages() ? '' : 'disabled' }}">
                        Next &raquo;
                    </a>
                </div>
            </div>
        @else
            <div class="admin-empty">
                Belum ada chapter.
            </div>
        @endif
    </div>
@endsection
