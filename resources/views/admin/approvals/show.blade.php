@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <div class="header-with-back">
            <a href="{{ route('admin.works.pending') }}" class="back-button">
                ← Kembali
            </a>
            <div>
                <h1>Detail Karya</h1>
                <p>Review lengkap karya sebelum disetujui</p>
            </div>
        </div>
    </div>

    <div class="content-body">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Main Work Info Section -->
        <div class="work-detail-container">
            <!-- Left Column: Cover & Quick Actions -->
            <div class="work-sidebar">
                <div class="cover-section">
                    @if ($work->cover)
                        <img src="{{ Storage::url($work->cover) }}" alt="{{ $work->title }}">
                    @else
                        <div class="work-cover-placeholder-large">
                            @if ($work->type === 'novel')
                                📖
                            @else
                                🎨
                            @endif
                        </div>
                    @endif

                    <div class="work-type-badge-large badge-{{ $work->type }}">
                        {{ $work->type === 'novel' ? '📚 Novel' : '🎨 Komik' }}
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="status-section">
                    <div class="status-badge status-{{ $work->status }}">
                        @if ($work->status === 'pending')
                            ⏳ Menunggu Persetujuan
                        @elseif($work->status === 'approved')
                            ✅ Disetujui
                        @else
                            📝 Draft
                        @endif
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="quick-stats">
                    <div class="quick-stat-item">
                        <div class="quick-stat-icon">👁️</div>
                        <div class="quick-stat-info">
                            <span class="quick-stat-value">{{ number_format($work->views) }}</span>
                            <span class="quick-stat-label">Views</span>
                        </div>
                    </div>
                    <div class="quick-stat-item">
                        <div class="quick-stat-icon">⭐</div>
                        <div class="quick-stat-info">
                            <span class="quick-stat-value">{{ number_format($work->favorites) }}</span>
                            <span class="quick-stat-label">Favorit</span>
                        </div>
                    </div>
                    <div class="quick-stat-item">
                        <div class="quick-stat-icon">📄</div>
                        <div class="quick-stat-info">
                            <span class="quick-stat-value">{{ $work->chapters_count ?? 0 }}</span>
                            <span class="quick-stat-label">{{ $work->type === 'novel' ? 'Bab' : 'Chapter' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons (if pending) -->
                @if ($work->status === 'pending')
                    <div class="sidebar-actions">
                        <form action="{{ route('admin.works.approve', $work) }}" method="POST">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn btn-approve-large"
                                onclick="return confirm('Apakah Anda yakin ingin menyetujui karya ini?')">
                                ✓ Setujui Karya
                            </button>
                        </form>

                        <form action="{{ route('admin.works.reject', $work) }}" method="POST">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn btn-reject-large"
                                onclick="return confirm('Apakah Anda yakin ingin menolak karya ini?')">
                                ✕ Tolak Karya
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Right Column: Detailed Information -->
            <div class="work-main-content">
                <!-- Title & Author -->
                <div class="title-section">
                    <h1 class="work-title-large">{{ $work->title }}</h1>
                    <div class="author-info">
                        <div class="author-avatar">
                            {{ strtoupper(substr($work->user->name, 0, 1)) }}
                        </div>
                        <div class="author-details">
                            <span class="author-label">Dibuat oleh</span>
                            <span class="author-name">{{ $work->user->name }}</span>
                            <span class="author-email">{{ $work->user->email }}</span>
                        </div>
                    </div>
                </div>

                <!-- Metadata Section -->
                <div class="metadata-section">
                    <div class="metadata-grid">
                        <div class="metadata-item">
                            <span class="metadata-label">📅 Tanggal Submit</span>
                            <span class="metadata-value">{{ $work->created_at->format('d F Y, H:i') }}</span>
                        </div>
                        <div class="metadata-item">
                            <span class="metadata-label">🔄 Terakhir Update</span>
                            <span class="metadata-value">{{ $work->updated_at->diffForHumans() }}</span>
                        </div>
                        <div class="metadata-item">
                            <span class="metadata-label">📊 Status</span>
                            <span class="metadata-value">{{ ucfirst($work->status) }}</span>
                        </div>
                        <div class="metadata-item">
                            <span class="metadata-label">🎭 Genre</span>
                            <span class="metadata-value">{{ $work->genres->count() }} Genre</span>
                        </div>
                    </div>
                </div>

                <!-- Genres Section -->
                @if ($work->genres->count() > 0)
                    <div class="section-card">
                        <h3 class="section-title">🎭 Genre</h3>
                        <div class="genres-list">
                            @foreach ($work->genres as $genre)
                                <span class="genre-badge">{{ $genre->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Synopsis Section -->
                <div class="section-card">
                    <h3 class="section-title">📖 Sinopsis</h3>
                    <div class="synopsis-content">
                        <p>{{ $work->synopsis ?: 'Tidak ada sinopsis.' }}</p>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="info-grid">
                    <!-- Maturity Rating -->
                    @if (isset($work->maturity_rating))
                        <div class="info-card">
                            <div class="info-icon">🔞</div>
                            <div class="info-content">
                                <h4>Rating Konten</h4>
                                <p>{{ $work->maturity_rating }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Language -->
                    @if (isset($work->language))
                        <div class="info-card">
                            <div class="info-icon">🌐</div>
                            <div class="info-content">
                                <h4>Bahasa</h4>
                                <p>{{ $work->language === 'id' ? 'Indonesia' : 'English' }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Completion Status -->
                    @if (isset($work->is_completed))
                        <div class="info-card">
                            <div class="info-icon">{{ $work->is_completed ? '✅' : '🔄' }}</div>
                            <div class="info-content">
                                <h4>Status Karya</h4>
                                <p>{{ $work->is_completed ? 'Tamat' : 'Ongoing' }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Chapters Preview (if any) -->
                @if (isset($work->chapters) && $work->chapters->count() > 0)
                    <div class="section-card">
                        <div class="section-header">
                            <h3 class="section-title">📚 Daftar {{ $work->type === 'novel' ? 'Bab' : 'Chapter' }}</h3>
                            <span class="chapter-count">{{ $work->chapters->count() }}
                                {{ $work->type === 'novel' ? 'Bab' : 'Chapter' }}</span>
                        </div>
                        <div class="chapters-list">
                            @foreach ($work->chapters->take(5) as $chapter)
                                <div class="chapter-item">
                                    <div class="chapter-number">{{ $chapter->order }}</div>
                                    <div class="chapter-info">
                                        <h4>{{ $chapter->title }}</h4>
                                        <span class="chapter-date">{{ $chapter->created_at->format('d M Y') }}</span>
                                    </div>
                                    <div class="chapter-views">
                                        <span>👁️ {{ number_format($chapter->views ?? 0) }}</span>
                                    </div>
                                </div>
                            @endforeach
                            @if ($work->chapters->count() > 5)
                                <div class="chapter-more">
                                    + {{ $work->chapters->count() - 5 }} {{ $work->type === 'novel' ? 'bab' : 'chapter' }}
                                    lainnya
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Activity Timeline -->
                <div class="section-card">
                    <h3 class="section-title">📊 Timeline Aktivitas</h3>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-dot timeline-dot-success"></div>
                            <div class="timeline-content">
                                <h4>Karya Dibuat</h4>
                                <p>{{ $work->created_at->format('d F Y, H:i') }}</p>
                                <span class="timeline-meta">{{ $work->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        @if ($work->updated_at != $work->created_at)
                            <div class="timeline-item">
                                <div class="timeline-dot timeline-dot-info"></div>
                                <div class="timeline-content">
                                    <h4>Terakhir Diupdate</h4>
                                    <p>{{ $work->updated_at->format('d F Y, H:i') }}</p>
                                    <span class="timeline-meta">{{ $work->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endif
                        @if ($work->status === 'pending')
                            <div class="timeline-item">
                                <div class="timeline-dot timeline-dot-warning"></div>
                                <div class="timeline-content">
                                    <h4>Menunggu Review</h4>
                                    <p>Karya sedang dalam proses review admin</p>
                                    <span class="timeline-meta">Status: Pending</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Alert */
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-success::before {
            content: "✓";
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            background-color: #28a745;
            color: white;
            border-radius: 50%;
            font-weight: bold;
        }

        /* Header with Back Button */
        .header-with-back {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            background: linear-gradient(135deg, #2d8b73 0%, #48c9b0 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(45, 139, 115, 0.3);
        }

        .back-button:hover {
            background: linear-gradient(135deg, #1e5f4f 0%, #2d8b73 100%);
            transform: translateX(-3px);
            box-shadow: 0 4px 12px rgba(30, 95, 79, 0.4);
        }

        /* Main Container */
        .work-detail-container {
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 30px;
        }

        /* Sidebar */
        .work-sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .cover-section {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .work-cover-large {
            width: 100%;
            height: 500px;
            object-fit: cover;
            display: block;
        }

        .work-cover-placeholder-large {
            width: 100%;
            height: 500px;
            background: linear-gradient(135deg, #e0f2f1 0%, #b2dfdb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 120px;
            opacity: 0.6;
        }

        .work-type-badge-large {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 700;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .badge-novel {
            background: rgba(139, 69, 19, 0.95);
            color: white;
        }

        .badge-comic {
            background: rgba(156, 39, 176, 0.95);
            color: white;
        }

        /* Status Section */
        .status-section {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .status-badge {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
        }

        .status-approved {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
        }

        .status-draft {
            background: linear-gradient(135deg, #e2e3e5 0%, #d6d8db 100%);
            color: #383d41;
        }

        /* Quick Stats */
        .quick-stats {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .quick-stat-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .quick-stat-icon {
            font-size: 32px;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #e8f5f3 0%, #d4edea 100%);
            border-radius: 10px;
        }

        .quick-stat-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .quick-stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #1e5f4f;
        }

        .quick-stat-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Sidebar Actions */
        .sidebar-actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .btn-approve-large,
        .btn-reject-large {
            width: 100%;
            padding: 15px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 700;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-approve-large {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
        }

        .btn-approve-large:hover {
            background: linear-gradient(135deg, #218838 0%, #1aa179 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.5);
        }

        .btn-reject-large {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
        }

        .btn-reject-large:hover {
            background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.5);
        }

        /* Main Content */
        .work-main-content {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        /* Title Section */
        .title-section {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .work-title-large {
            color: #1e5f4f;
            font-size: 32px;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 20px;
        }

        .author-info {
            display: flex;
            align-items: center;
            gap: 15px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
        }

        .author-avatar {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #48c9b0 0%, #5fd4bd 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #1e5f4f;
            font-size: 24px;
            box-shadow: 0 4px 12px rgba(72, 201, 176, 0.3);
        }

        .author-details {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .author-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .author-name {
            font-size: 18px;
            font-weight: 700;
            color: #1e5f4f;
        }

        .author-email {
            font-size: 13px;
            color: #6c757d;
        }

        /* Metadata Section */
        .metadata-section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .metadata-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .metadata-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .metadata-label {
            font-size: 13px;
            color: #6c757d;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .metadata-value {
            font-size: 16px;
            color: #1e5f4f;
            font-weight: 600;
        }

        /* Section Card */
        .section-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .section-title {
            color: #1e5f4f;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .chapter-count {
            background: #e8f5f3;
            color: #2d8b73;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        /* Genres List */
        .genres-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .genre-badge {
            display: inline-block;
            padding: 8px 18px;
            background: linear-gradient(135deg, #e8f5f3 0%, #d4edea 100%);
            color: #1e5f4f;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            border: 2px solid #48c9b0;
        }

        /* Synopsis */
        .synopsis-content {
            color: #333;
            font-size: 15px;
            line-height: 1.8;
        }

        .synopsis-content p {
            margin: 0;
            white-space: pre-wrap;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .info-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.12);
        }

        .info-icon {
            font-size: 36px;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #e8f5f3 0%, #d4edea 100%);
            border-radius: 12px;
        }

        .info-content h4 {
            font-size: 13px;
            color: #6c757d;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-content p {
            font-size: 16px;
            color: #1e5f4f;
            font-weight: 700;
            margin: 0;
        }

        /* Chapters List */
        .chapters-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .chapter-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .chapter-item:hover {
            background: #e8f5f3;
            transform: translateX(5px);
        }

        .chapter-number {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #2d8b73 0%, #48c9b0 100%);
            color: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
            flex-shrink: 0;
        }

        .chapter-info {
            flex: 1;
        }

        .chapter-info h4 {
            font-size: 15px;
            color: #1e5f4f;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .chapter-date {
            font-size: 12px;
            color: #6c757d;
        }

        .chapter-views {
            font-size: 13px;
            color: #6c757d;
            font-weight: 600;
        }

        .chapter-more {
            text-align: center;
            padding: 15px;
            color: #2d8b73;
            font-weight: 600;
            font-size: 14px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 2px dashed #d4edea;
        }

        /* Timeline */
        .timeline {
            position: relative;
            padding-left: 40px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(180deg, #48c9b0 0%, #e0f2f1 100%);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 25px;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-dot {
            position: absolute;
            left: -32px;
            top: 0;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 0 0 3px;
        }

        .timeline-dot-success {
            background: #28a745;
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.2);
        }

        .timeline-dot-info {
            background: #17a2b8;
            box-shadow: 0 0 0 3px rgba(23, 162, 184, 0.2);
        }

        .timeline-dot-warning {
            background: #ffc107;
            box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.2);
        }

        .timeline-content h4 {
            font-size: 16px;
            color: #1e5f4f;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .timeline-content p {
            font-size: 14px;
            color: #495057;
            margin-bottom: 5px;
        }

        .timeline-meta {
            font-size: 12px;
            color: #6c757d;
            font-style: italic;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .work-detail-container {
                grid-template-columns: 1fr;
            }

            .work-sidebar {
                order: 2;
            }

            .work-main-content {
                order: 1;
            }

            .metadata-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .header-with-back {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .work-cover-large,
            .work-cover-placeholder-large {
                height: 400px;
            }

            .work-title-large {
                font-size: 24px;
            }

            .author-info {
                flex-direction: column;
                align-items: flex-start;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        // Auto hide success alert
        const successAlert = document.querySelector('.alert-success');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.transition = 'opacity 0.3s ease';
                successAlert.style.opacity = '0';
                setTimeout(() => {
                    successAlert.remove();
                }, 300);
            }, 5000);
        }
    </script>
@endsection
