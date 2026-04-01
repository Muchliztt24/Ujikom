@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <div style="display:flex; justify-content:space-between; gap:16px; align-items:center; flex-wrap:wrap;">
            <div>
                <h1>Analytics Karya</h1>
                <p>Performa dan engagement untuk <strong>{{ $work->title }}</strong>.</p>
            </div>
            <div class="admin-btn-row">
                <a href="{{ route('works.chapters.index', $work) }}" class="admin-btn info">Kelola Chapter</a>
                <a href="{{ route('works.edit', $work) }}" class="admin-btn warning">Edit Karya</a>
                <a href="{{ route('works.index') }}" class="admin-btn secondary">Kembali</a>
            </div>
        </div>
    </div>

    <div class="content-body admin-card-grid">
        <div class="admin-stat-grid">
            <div class="admin-surface admin-stat-card">
                <div class="admin-stat-label">Pembaca Unik</div>
                <div class="admin-stat-value">{{ $analytics['unique_readers'] }}</div>
                <div class="admin-stat-note">Diambil dari user yang membuka reader karya ini</div>
            </div>
            <div class="admin-surface admin-stat-card">
                <div class="admin-stat-label">Bookmark</div>
                <div class="admin-stat-value">{{ $analytics['total_bookmarks'] }}</div>
                <div class="admin-stat-note">User yang menyimpan karya ini</div>
            </div>
            <div class="admin-surface admin-stat-card">
                <div class="admin-stat-label">Komentar</div>
                <div class="admin-stat-value">{{ $analytics['total_comments'] }}</div>
                <div class="admin-stat-note">Total komentar dari semua chapter</div>
            </div>
            <div class="admin-surface admin-stat-card">
                <div class="admin-stat-label">Completion Rate</div>
                <div class="admin-stat-value">{{ $analytics['completion_rate'] }}%</div>
                <div class="admin-stat-note">{{ $analytics['completed_readers'] }} pembaca mencapai chapter terakhir</div>
            </div>
        </div>

        <div class="admin-stat-grid">
            <div class="admin-surface admin-form-card">
                <div class="admin-form-title">Ringkasan Karya</div>
                <div class="admin-list">
                    <div class="admin-list-item"><strong>Tipe:</strong> {{ ucfirst($work->type) }}</div>
                    <div class="admin-list-item"><strong>Status:</strong> {{ ucfirst($work->status) }}</div>
                    <div class="admin-list-item"><strong>Genre:</strong> {{ $work->genres->pluck('name')->implode(', ') ?: '-' }}</div>
                    <div class="admin-list-item"><strong>Total Chapter:</strong> {{ $work->chapters->count() }}</div>
                    @if($work->type === 'comic')
                        <div class="admin-list-item"><strong>Total Gambar:</strong> {{ $analytics['total_images'] }}</div>
                    @endif
                </div>
            </div>

            <div class="admin-surface admin-form-card">
                <div class="admin-form-title">Chapter Paling Ramai</div>
                @if($analytics['top_chapter'])
                    <div class="admin-list-item">
                        <strong>Chapter {{ $analytics['top_chapter']['chapter']->chapter_number }}</strong>
                        @if($analytics['top_chapter']['chapter']->title)
                            <div style="margin-top:6px;">{{ $analytics['top_chapter']['chapter']->title }}</div>
                        @endif
                        <div class="admin-muted" style="margin-top:8px;">Pembaca {{ $analytics['top_chapter']['unique_readers'] }} • Komentar {{ $analytics['top_chapter']['comments_count'] }}</div>
                    </div>
                @else
                    <div class="admin-empty">Belum ada data chapter.</div>
                @endif
            </div>
        </div>

        <div class="admin-surface admin-form-card">
            <div class="admin-form-title">Performa per Chapter</div>
            @if($analytics['chapter_stats']->count())
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Chapter</th>
                                <th>Pembaca</th>
                                <th>Komentar</th>
                                <th>{{ $work->type === 'comic' ? 'Gambar' : 'Jumlah Kata' }}</th>
                                <th>Skor Engagement</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($analytics['chapter_stats'] as $stat)
                                <tr>
                                    <td>
                                        <strong>Chapter {{ $stat['chapter']->chapter_number }}</strong>
                                        @if($stat['chapter']->title)
                                            <div class="admin-muted" style="margin-top:6px;">{{ $stat['chapter']->title }}</div>
                                        @endif
                                    </td>
                                    <td>{{ $stat['unique_readers'] }}</td>
                                    <td>{{ $stat['comments_count'] }}</td>
                                    <td>{{ $work->type === 'comic' ? $stat['images_count'] : number_format($stat['word_count']) }}</td>
                                    <td><span class="admin-chip success">{{ $stat['engagement_score'] }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="admin-empty">Analytics chapter akan tampil setelah katalog terisi.</div>
            @endif
        </div>
    </div>
@endsection




