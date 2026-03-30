@extends('layouts.user')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Bookmarks</h1>
        <p class="page-subtitle">Daftar karya yang Anda simpan untuk dibaca lagi nanti.</p>
    </div>

    @if ($bookmarks->count() > 0)
        <div style="display:grid; gap: 18px;">
            @foreach ($bookmarks as $bookmark)
                @if ($bookmark->work)
                    @php
                        $continueChapter = $bookmark->last_chapter_read
                            ? $bookmark->work->chapters->firstWhere('chapter_number', $bookmark->last_chapter_read)
                            : null;
                    @endphp
                    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 18px; padding: 18px; display: grid; grid-template-columns: 88px 1fr auto; gap: 18px; align-items: center;">
                        <a href="{{ route('works.public.show', $bookmark->work) }}" style="text-decoration:none;">
                            @if ($bookmark->work->cover)
                                <img src="{{ asset('storage/' . $bookmark->work->cover) }}" alt="{{ $bookmark->work->title }}" style="width: 88px; height: 118px; object-fit: cover; border-radius: 12px; display: block;">
                            @else
                                <div style="width: 88px; height: 118px; border-radius: 12px; background: linear-gradient(135deg, var(--dark-green), var(--primary-green)); display:flex; align-items:center; justify-content:center; font-size: 38px; color: white;">
                                    <i class="{{ $bookmark->work->type === 'novel' ? 'bi bi-book-half' : 'bi bi-palette-fill' }}"></i>
                                </div>
                            @endif
                        </a>
                        <div>
                            <div style="font-size: 22px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px;">{{ $bookmark->work->title }}</div>
                            <div style="display:flex; gap:8px; flex-wrap: wrap; margin-bottom: 8px;">
                                <span style="padding: 5px 10px; border-radius: 999px; background: rgba(45, 139, 115, 0.15); color: var(--light-green); font-size: 12px; font-weight: 700;">{{ ucfirst($bookmark->work->type) }}</span>
                                @foreach($bookmark->work->genres->take(2) as $genre)
                                    <span style="padding: 5px 10px; border-radius: 999px; background: rgba(255,255,255,0.05); color: var(--text-secondary); font-size: 12px; font-weight: 700;">{{ $genre->name }}</span>
                                @endforeach
                            </div>
                            <div style="color: var(--text-secondary); font-size: 14px;">{{ $continueChapter ? 'Terakhir di Chapter ' . $continueChapter->chapter_number : 'Belum ada progress tersimpan' }}</div>
                        </div>
                        <div style="display:flex; flex-direction:column; gap:10px; min-width: 170px;">
                            <a href="{{ $continueChapter ? route('works.chapters.read', [$bookmark->work, $continueChapter]) : route('works.read', $bookmark->work) }}" style="text-align:center; padding: 12px 14px; border-radius: 12px; text-decoration:none; background: linear-gradient(135deg, var(--primary-green), var(--light-green)); color:white; font-weight:700;">{{ $continueChapter ? 'Lanjutkan' : 'Mulai Baca' }}</a>
                            <form action="{{ route('bookmarks.destroy', $bookmark->work) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="width:100%; padding: 12px 14px; border-radius: 12px; border: 1px solid rgba(239,68,68,0.2); background: rgba(239,68,68,0.08); color: #fecaca; font-weight:700; cursor:pointer;">Hapus Bookmark</button>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <div style="background: var(--bg-card); border: 1px dashed var(--border-color); border-radius: 18px; padding: 40px; text-align: center; color: var(--text-secondary);">
            Belum ada bookmark yang tersimpan.
        </div>
    @endif

    <style>
        @media (max-width: 860px) {
            div[style*="grid-template-columns: 88px 1fr auto"] {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
@endsection
