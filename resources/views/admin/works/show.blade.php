@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Detail Karya</h1>
        <p>Lihat detail karya, chapter, dan statusnya dalam satu halaman.</p>
    </div>

    <div class="content-body">
        <a href="{{ route('admin.works.index') }}" style="display: inline-flex; align-items:center; gap:8px; margin-bottom: 18px; text-decoration: none; color: var(--admin-accent); font-weight: 700;">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali</span>
        </a>

        @if (session('success'))
            <div style="margin-bottom: 16px; padding: 14px 16px; background: #d4edda; color: #155724; border-radius: 10px;">{{ session('success') }}</div>
        @endif

        <div style="display: grid; grid-template-columns: 280px minmax(0, 1fr); gap: 24px;">
            <div style="display: grid; gap: 18px;">
                <div class="admin-surface" style="padding: 16px;">
                    @if ($work->cover)
                        <img src="{{ Storage::url($work->cover) }}" alt="{{ $work->title }}" style="width: 100%; border-radius: 12px; object-fit: cover;">
                    @else
                        <div style="height: 360px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.04); border-radius: 12px; color: var(--admin-text-soft); font-size: 54px;">
                            <i class="{{ $work->type === 'comic' ? 'bi bi-palette-fill' : 'bi bi-book-half' }}"></i>
                        </div>
                    @endif
                </div>

                <div class="admin-surface" style="padding: 16px;">
                    <div style="display: grid; gap: 10px; font-size: 14px; color: var(--admin-text);">
                        <div><strong>Status:</strong> {{ ucfirst($work->status) }}</div>
                        <div><strong>Tipe:</strong> {{ ucfirst($work->type) }}</div>
                        <div><strong>Author:</strong> {{ $work->user->name }}</div>
                        <div><strong>Email:</strong> {{ $work->user->email }}</div>
                        <div><strong>Genre:</strong> {{ $work->genres->pluck('name')->implode(', ') ?: '-' }}</div>
                        <div><strong>Total Chapter:</strong> {{ $work->chapters->count() }}</div>
                    </div>
                </div>

                <div class="admin-surface" style="padding: 16px; display: grid; gap: 8px;">
                    @if ($work->status === 'pending')
                        <form action="{{ route('admin.works.approve', $work) }}" method="POST">@csrf<button type="submit" class="admin-btn success" style="width: 100%;">Approve</button></form>
                        <form action="{{ route('admin.works.reject', $work) }}" method="POST">@csrf<button type="submit" class="admin-btn warning" style="width: 100%;">Reject</button></form>
                    @endif
                    <form action="{{ route('admin.works.destroy', $work) }}" method="POST">@csrf @method('DELETE')<button type="submit" onclick="return confirm('Hapus karya ini?')" class="admin-btn danger" style="width: 100%;">Hapus</button></form>
                </div>
            </div>

            <div style="display: grid; gap: 18px;">
                <div class="admin-surface" style="padding: 18px;">
                    <h2 style="margin-bottom: 12px; color: var(--admin-text);">{{ $work->title }}</h2>
                    <p class="admin-muted" style="line-height: 1.8;">{{ $work->description ?: 'Tidak ada deskripsi.' }}</p>
                </div>

                <div class="admin-surface" style="padding: 18px;">
                    <h3 style="margin-bottom: 14px; color: var(--admin-text);">Daftar Chapter</h3>
                    @if ($work->chapters->count())
                        <div style="display: grid; gap: 10px;">
                            @foreach ($work->chapters as $chapter)
                                <div style="padding: 14px; border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; display: flex; justify-content: space-between; gap: 14px; align-items: center; background: rgba(255,255,255,0.02);">
                                    <div>
                                        <div style="font-weight: 700; color: var(--admin-text);">Chapter {{ $chapter->chapter_number }}{{ $chapter->title ? ' - ' . $chapter->title : '' }}</div>
                                        <div class="admin-muted" style="font-size: 13px;">
                                            @if ($work->type === 'comic')
                                                {{ $chapter->images_count }} gambar
                                            @else
                                                {{ \Illuminate\Support\Str::words($chapter->text_content ?: 'Tanpa isi', 12) }}
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.chapters.show', $chapter) }}" class="admin-btn info">Lihat</a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="admin-muted">Karya ini belum memuat chapter.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 900px) {
            div[style*="grid-template-columns: 280px minmax(0, 1fr)"] {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
@endsection


