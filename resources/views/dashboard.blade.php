@extends('layouts.admin')

@section('content')
    @php
        $totalWorks = \App\Models\Work::count();
        $pendingWorks = \App\Models\Work::where('status', 'pending')->count();
        $approvedWorks = \App\Models\Work::where('status', 'approved')->count();
        $draftWorks = \App\Models\Work::where('status', 'draft')->count();
        $totalUsers = \App\Models\User::count();
        $totalGenres = \App\Models\Genre::count();
        $totalChapters = \App\Models\Chapter::count();
        $totalImages = \App\Models\ChapterImage::count();
    @endphp

    <div class="content-header">
        <h1>{{ $role === 'admin' ? 'Dashboard Admin' : 'Dashboard Uploader' }}</h1>
        <p>
            {{ $role === 'admin' ? 'Pantau aktivitas platform, karya, dan pengguna dari satu tempat.' : 'Lihat performa karya dan atur aktivitas unggahanmu dengan cepat.' }}
        </p>
    </div>

    <div class="content-body admin-card-grid">
        @if (session('status'))
            <div style="padding: 14px 16px; background: #d4edda; color: #155724; border-radius: 10px;">{{ session('status') }}</div>
        @endif

        @if ($role === 'admin')
            <div class="admin-stat-grid">
                <div class="admin-surface admin-stat-card">
                    <div class="admin-stat-label">Total Karya</div>
                    <div class="admin-stat-value">{{ $totalWorks }}</div>
                    <div class="admin-stat-note">Approved {{ $approvedWorks }} | Pending {{ $pendingWorks }} | Draft {{ $draftWorks }}</div>
                </div>
                <div class="admin-surface admin-stat-card">
                    <div class="admin-stat-label">Pengguna</div>
                    <div class="admin-stat-value">{{ $totalUsers }}</div>
                    <div class="admin-stat-note">Semua akun yang terdaftar di sistem</div>
                </div>
                <div class="admin-surface admin-stat-card">
                    <div class="admin-stat-label">Chapter</div>
                    <div class="admin-stat-value">{{ $totalChapters }}</div>
                    <div class="admin-stat-note">Total chapter novel dan komik</div>
                </div>
                <div class="admin-surface admin-stat-card">
                    <div class="admin-stat-label">Gambar Komik</div>
                    <div class="admin-stat-value">{{ $totalImages }}</div>
                    <div class="admin-stat-note">Semua halaman komik yang sudah diunggah</div>
                </div>
            </div>

            <div class="admin-stat-grid">
                <div class="admin-surface admin-form-card">
                    <div class="admin-form-title">Moderasi Cepat</div>
                    <div class="admin-list">
                        <div class="admin-list-item">
                            <div class="admin-muted">Perlu perhatian</div>
                            <div style="font-size: 26px; font-weight: 800; margin-top: 6px;">{{ $pendingWorks }}</div>
                        </div>
                        <div class="admin-btn-row">
                            <a href="{{ route('admin.works.pending') }}" class="admin-btn primary">Buka Approval</a>
                            <a href="{{ route('admin.works.index') }}" class="admin-btn secondary">Moderasi Karya</a>
                        </div>
                    </div>
                </div>
                <div class="admin-surface admin-form-card">
                    <div class="admin-form-title">Menu Admin</div>
                    <div class="admin-btn-row">
                        <a href="{{ route('admin.users.index') }}" class="admin-btn info">Kelola Pengguna</a>
                        <a href="{{ route('admin.genres.index') }}" class="admin-btn secondary">Kelola Genre</a>
                        <a href="{{ route('admin.chapters.index') }}" class="admin-btn secondary">Moderasi Chapter</a>
                        <a href="{{ route('admin.chapter-images.index') }}" class="admin-btn secondary">Moderasi Gambar</a>
                    </div>
                    <div class="admin-list" style="margin-top: 14px;">
                        <div class="admin-list-item">Genre tersedia: <strong>{{ $totalGenres }}</strong></div>
                        <div class="admin-list-item">Karya approved: <strong>{{ $approvedWorks }}</strong></div>
                    </div>
                </div>
            </div>
        @else
            @php
                $myWorks = $data['myWorks'] ?? collect();
                $myPending = $myWorks->where('status', 'pending')->count();
                $myApproved = $myWorks->where('status', 'approved')->count();
                $myDraft = $myWorks->where('status', 'draft')->count();
            @endphp

            <div class="admin-stat-grid">
                <div class="admin-surface admin-stat-card">
                    <div class="admin-stat-label">Karya Saya</div>
                    <div class="admin-stat-value">{{ $myWorks->count() }}</div>
                    <div class="admin-stat-note">Draft {{ $myDraft }} | Pending {{ $myPending }} | Approved {{ $myApproved }}</div>
                </div>
                <div class="admin-surface admin-stat-card">
                    <div class="admin-stat-label">Siap Dikerjakan</div>
                    <div class="admin-stat-value">{{ $myDraft }}</div>
                    <div class="admin-stat-note">Judul yang siap dilanjutkan kapan saja</div>
                </div>
            </div>

            <div class="admin-stat-grid">
                <div class="admin-surface admin-form-card">
                    <div class="admin-form-title">Aksi Cepat</div>
                    <div class="admin-btn-row">
                        <a href="{{ route('works.create') }}" class="admin-btn primary">Tambah Karya</a>
                        <a href="{{ route('works.index') }}" class="admin-btn secondary">Lihat Semua Karya</a>
                    </div>
                </div>
                <div class="admin-surface admin-form-card">
                    <div class="admin-form-title">Karya Terbaru</div>
                    <div class="admin-list">
                        @forelse ($myWorks->take(5) as $work)
                            <div class="admin-list-item">
                                <strong>{{ $work->title }}</strong>
                                <div class="admin-muted" style="margin-top: 6px;">{{ ucfirst($work->type) }} | {{ ucfirst($work->status) }}</div>
                            </div>
                        @empty
                            <div class="admin-empty">Karya terbaru akan muncul di sini.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection





