@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Approval Karya</h1>
        <p>Review karya yang masih menunggu persetujuan.</p>
    </div>

    <div class="content-body admin-card-grid">
        @if (session('success'))
            <div style="padding: 14px 16px; background: #d4edda; color: #155724; border-radius: 10px;">{{ session('success') }}</div>
        @endif

        <div class="admin-muted">Total pending: {{ $works->count() }} karya</div>

        @if ($works->count())
            <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap:18px;">
                @foreach ($works as $work)
                    <div class="admin-surface" style="overflow:hidden; display:flex; flex-direction:column;">
                        @if ($work->cover)
                            <img src="{{ asset('storage/' . $work->cover) }}" alt="{{ $work->title }}" style="width:100%; height:240px; object-fit:cover;">
                        @else
                            <div style="height:240px; display:flex; align-items:center; justify-content:center; background: rgba(255,255,255,0.04); color: var(--admin-text-soft); font-size:52px;">
                                {{ $work->type === 'comic' ? '??' : '??' }}
                            </div>
                        @endif
                        <div style="padding:18px; display:grid; gap:12px; flex:1;">
                            <div>
                                <span class="admin-chip warning" style="margin-bottom:10px;">Pending</span>
                                <div class="admin-form-title" style="margin-bottom:6px;">{{ $work->title }}</div>
                                <div class="admin-muted">Author: {{ $work->user->name }}</div>
                                <div class="admin-muted">{{ ucfirst($work->type) }} • {{ $work->chapters_count }} chapter</div>
                            </div>
                            @if ($work->genres->count())
                                <div style="display:flex; flex-wrap:wrap; gap:8px;">
                                    @foreach ($work->genres as $genre)
                                        <span class="admin-chip">{{ $genre->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                            <div class="admin-help">{{ \Illuminate\Support\Str::limit($work->description ?: 'Belum ada deskripsi.', 140) }}</div>
                            <div class="admin-btn-row" style="margin-top:auto;">
                                <a href="{{ route('admin.works.show', $work) }}" class="admin-btn info">Lihat Detail</a>
                                <form action="{{ route('admin.works.approve', $work) }}" method="POST">@csrf<button type="submit" class="admin-btn success" onclick="return confirm('Setujui karya ini?')">Setujui</button></form>
                                <form action="{{ route('admin.works.reject', $work) }}" method="POST">@csrf<button type="submit" class="admin-btn danger" onclick="return confirm('Tolak karya ini?')">Tolak</button></form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="admin-empty">Tidak ada karya yang menunggu persetujuan.</div>
        @endif
    </div>
@endsection
