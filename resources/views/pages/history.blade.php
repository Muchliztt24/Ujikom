@extends('layouts.user')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Riwayat Baca</h1>
        <p class="page-subtitle">Lanjutkan chapter terakhir yang pernah Anda buka.</p>
    </div>

    @if ($histories->count())
        <div style="display: grid; gap: 18px;">
            @foreach ($histories as $history)
                <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 18px; padding: 18px; display: grid; grid-template-columns: 88px 1fr auto; gap: 18px; align-items: center;">
                    <div>
                        @if ($history->work?->cover)
                            <img src="{{ asset('storage/' . $history->work->cover) }}" alt="{{ $history->work->title }}" style="width: 88px; height: 118px; object-fit: cover; border-radius: 12px; display: block;">
                        @else
                            <div style="width: 88px; height: 118px; border-radius: 12px; background: linear-gradient(135deg, var(--dark-green), var(--primary-green)); display:flex; align-items:center; justify-content:center; font-size: 38px; color: white;">
                                <i class="{{ $history->work?->type === 'comic' ? 'bi bi-palette-fill' : 'bi bi-book-half' }}"></i>
                            </div>
                        @endif
                    </div>

                    <div>
                        <div style="font-size: 22px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px;">{{ $history->work?->title }}</div>
                        <div style="color: var(--text-secondary); font-size: 14px; margin-bottom: 8px;">
                            Chapter {{ $history->chapter?->chapter_number }}
                            @if ($history->chapter?->title)
                                • {{ $history->chapter->title }}
                            @endif
                        </div>
                        <div style="display:flex; gap: 8px; flex-wrap: wrap; margin-bottom: 8px;">
                            <span style="padding: 5px 10px; border-radius: 999px; background: rgba(45, 139, 115, 0.15); color: var(--light-green); font-size: 12px; font-weight: 700;">{{ ucfirst($history->work?->type ?? 'work') }}</span>
                            <span style="padding: 5px 10px; border-radius: 999px; background: rgba(255,255,255,0.06); color: var(--text-secondary); font-size: 12px; font-weight: 700;">{{ $history->last_read_at?->diffForHumans() }}</span>
                        </div>
                        <div style="color: var(--text-secondary); font-size: 13px;">Author: {{ $history->work?->user?->name }}</div>
                    </div>

                    <div style="display:flex; flex-direction:column; gap: 10px; min-width: 170px;">
                        <a href="{{ route('works.chapters.read', [$history->work, $history->chapter]) }}" style="text-align:center; padding: 12px 14px; border-radius: 12px; text-decoration:none; background: linear-gradient(135deg, var(--primary-green), var(--light-green)); color:white; font-weight:700;">Lanjutkan Membaca</a>
                        <a href="{{ route('works.public.show', $history->work) }}" style="text-align:center; padding: 12px 14px; border-radius: 12px; text-decoration:none; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-primary); font-weight:700;">Lihat Detail</a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div style="background: var(--bg-card); border: 1px dashed var(--border-color); border-radius: 18px; padding: 40px; text-align: center; color: var(--text-secondary);">
            Belum ada riwayat baca. Buka chapter pertama untuk mulai menyimpan progres.
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
