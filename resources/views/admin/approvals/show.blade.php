@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Detail Approval</h1>
        <p>Tinjau detail karya dan lakukan keputusan langsung dari halaman ini.</p>
    </div>

    <div class="content-body">
        <a href="{{ route('admin.works.pending') }}" style="display: inline-flex; align-items:center; gap:8px; margin-bottom: 18px; text-decoration: none; color: var(--admin-accent); font-weight: 700;">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke approval</span>
        </a>

        <div style="display: grid; grid-template-columns: 320px minmax(0, 1fr); gap: 24px; align-items: start;">
            <div style="display: grid; gap: 18px;">
                <div class="admin-surface" style="overflow: hidden;">
                    @if ($work->cover)
                        <img src="{{ asset('storage/' . $work->cover) }}" alt="{{ $work->title }}" style="width: 100%; height: 420px; object-fit: cover;">
                    @else
                        <div style="height: 420px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.04); color: var(--admin-text-soft); font-size: 72px;">
                            <i class="{{ $work->type === 'comic' ? 'bi bi-palette-fill' : 'bi bi-book-half' }}"></i>
                        </div>
                    @endif
                </div>

                <div class="admin-surface" style="padding: 18px; display: grid; gap: 10px;">
                    <form action="{{ route('admin.works.approve', $work) }}" method="POST">
                        @csrf
                        <button type="submit" class="admin-btn success" style="width: 100%;">Setujui Karya</button>
                    </form>
                    <form action="{{ route('admin.works.reject', $work) }}" method="POST">
                        @csrf
                        <button type="submit" class="admin-btn danger" style="width: 100%;">Tolak Karya</button>
                    </form>
                </div>
            </div>

            <div style="display: grid; gap: 18px;">
                <div class="admin-surface" style="padding: 22px;">
                    <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 12px;">
                        <span class="admin-chip warning">{{ ucfirst($work->status) }}</span>
                        <span class="admin-chip">{{ $work->type === 'comic' ? 'Comic' : 'Novel' }}</span>
                    </div>
                    <h2 style="margin: 0 0 8px; color: var(--admin-text); font-size: 30px;">{{ $work->title }}</h2>
                    <div class="admin-muted" style="font-size: 14px; line-height: 1.8;">
                        Author: {{ $work->user->name }}<br>
                        Email: {{ $work->user->email }}<br>
                        Dibuat: {{ $work->created_at?->format('d M Y H:i') ?? '-' }}<br>
                        Chapter: {{ $work->chapters->count() }}
                    </div>
                </div>

                <div class="admin-surface" style="padding: 22px;">
                    <h3 style="margin: 0 0 14px; color: var(--admin-text);">Genre</h3>
                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                        @forelse ($work->genres as $genre)
                            <span class="admin-chip">{{ $genre->name }}</span>
                        @empty
                            <span class="admin-muted">Tanpa genre.</span>
                        @endforelse
                    </div>
                </div>

                <div class="admin-surface" style="padding: 22px;">
                    <h3 style="margin: 0 0 14px; color: var(--admin-text);">Deskripsi</h3>
                    <div class="admin-muted" style="line-height: 1.8; white-space: pre-line;">{{ $work->description ?: 'Deskripsi akan tampil di bagian ini.' }}</div>
                </div>

                <div class="admin-surface" style="padding: 22px;">
                    <h3 style="margin: 0 0 14px; color: var(--admin-text);">Daftar Chapter</h3>
                    @if ($work->chapters->count())
                        <div style="display: grid; gap: 10px;">
                            @foreach ($work->chapters->sortBy('chapter_number') as $chapter)
                                <div style="padding: 12px 14px; border: 1px solid rgba(255,255,255,0.05); border-radius: 10px; background: rgba(255,255,255,0.02); color: var(--admin-text);">
                                    <strong>Chapter {{ $chapter->chapter_number }}</strong>
                                    {{ $chapter->title ? ' - ' . $chapter->title : '' }}
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
        @media (max-width: 960px) {
            div[style*="grid-template-columns: 320px minmax(0, 1fr)"] {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
@endsection


