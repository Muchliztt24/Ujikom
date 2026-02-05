@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Persetujuan Karya</h1>
        <p>Review dan setujui karya yang menunggu persetujuan</p>
    </div>

    <div class="content-body">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($works->count() > 0)
            <div class="stats-bar">
                <div class="stat-item">
                    <span class="stat-icon">⏳</span>
                    <div class="stat-info">
                        <span class="stat-value">{{ $works->count() }}</span>
                        <span class="stat-label">Karya Menunggu</span>
                    </div>
                </div>
            </div>

            <div class="works-grid">
                @foreach ($works as $work)
                    <div class="work-card">
                        <!-- Work Cover -->
                        <div class="work-cover">
                            @if ($work->cover)
                                <img src="{{ Storage::url($work->cover) }}" alt="{{ $work->title }}">
                            @else
                                <div class="work-cover-placeholder">
                                    @if ($work->type === 'novel')
                                        📖
                                    @else
                                        🎨
                                    @endif
                                </div>
                            @endif
                            <div class="work-type-badge badge-{{ $work->type }}">
                                {{ $work->type === 'novel' ? '📚 Novel' : '🎨 Komik' }}
                            </div>
                        </div>

                        <!-- Work Info -->
                        <div class="work-info">
                            <h3 class="work-title">{{ $work->title }}</h3>

                            <div class="work-meta">
                                <div class="meta-item">
                                    <span class="meta-icon">👤</span>
                                    <span>{{ $work->user->name }}</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-icon">📅</span>
                                    <span>{{ $work->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            <!-- Genres -->
                            @if ($work->genres->count() > 0)
                                <div class="work-genres">
                                    @foreach ($work->genres->take(3) as $genre)
                                        <span class="genre-tag">{{ $genre->name }}</span>
                                    @endforeach
                                    @if ($work->genres->count() > 3)
                                        <span class="genre-tag">+{{ $work->genres->count() - 3 }}</span>
                                    @endif
                                </div>
                            @endif

                            <!-- Synopsis -->
                            <div class="work-synopsis">
                                <p>{{ Str::limit($work->synopsis, 150) }}</p>
                            </div>

                            <!-- Statistics -->
                            <div class="work-stats">
                                <div class="stat-badge">
                                    <span class="stat-badge-icon">👁️</span>
                                    <span>{{ number_format($work->views) }}</span>
                                </div>
                                <div class="stat-badge">
                                    <span class="stat-badge-icon">⭐</span>
                                    <span>{{ number_format($work->favorites) }}</span>
                                </div>
                                @if ($work->type === 'novel')
                                    <div class="stat-badge">
                                        <span class="stat-badge-icon">📄</span>
                                        <span>{{ $work->chapters_count ?? 0 }} Bab</span>
                                    </div>
                                @else
                                    <div class="stat-badge">
                                        <span class="stat-badge-icon">📖</span>
                                        <span>{{ $work->chapters_count ?? 0 }} Chapter</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="work-actions">
                                <form action="{{ route('admin.works.approve', $work) }}" method="POST" style="flex: 1;">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-approve"
                                        onclick="return confirm('Apakah Anda yakin ingin menyetujui karya ini?')">
                                        ✓ Setujui
                                    </button>
                                </form>

                                <form action="{{ route('admin.works.reject', $work) }}" method="POST" style="flex: 1;">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-reject"
                                        onclick="return confirm('Apakah Anda yakin ingin menolak karya ini?')">
                                        ✕ Tolak
                                    </button>
                                </form>
                            </div>
                            <!-- Detail Link -->
                            <a href="{{ route('admin.works.show', $work) }}" class="work-detail-link"
                                data-work-id="{{ $work->id }}">
                                👁️ Lihat Detail Lengkap
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">✅</div>
                <h3>Tidak Ada Karya Menunggu</h3>
                <p>Semua karya telah diproses. Bagus sekali! 🎉</p>
            </div>
        @endif
    </div>

    <style>
        /* Alert Styles */
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

        /* Stats Bar */
        .stats-bar {
            background: linear-gradient(135deg, #1e5f4f 0%, #2d8b73 100%);
            padding: 20px 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(30, 95, 79, 0.2);
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .stat-icon {
            font-size: 32px;
            background: rgba(255, 255, 255, 0.2);
            padding: 12px;
            border-radius: 10px;
        }

        .stat-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .stat-value {
            color: white;
            font-size: 28px;
            font-weight: 700;
        }

        .stat-label {
            color: #b8e6d5;
            font-size: 14px;
        }

        /* Works Grid */
        .works-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }

        /* Work Card */
        .work-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .work-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* Work Cover */
        .work-cover {
            position: relative;
            width: 100%;
            height: 220px;
            background: linear-gradient(135deg, #e0f2f1 0%, #b2dfdb 100%);
            overflow: hidden;
        }

        .work-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .work-cover-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 80px;
            opacity: 0.5;
        }

        .work-type-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .badge-novel {
            background: rgba(139, 69, 19, 0.9);
            color: white;
        }

        .badge-comic {
            background: rgba(156, 39, 176, 0.9);
            color: white;
        }

        /* Work Info */
        .work-info {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            flex: 1;
        }

        .work-title {
            color: #1e5f4f;
            font-size: 18px;
            font-weight: 700;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Work Meta */
        .work-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #6c757d;
            font-size: 13px;
        }

        .meta-icon {
            font-size: 14px;
        }

        /* Genres */
        .work-genres {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .genre-tag {
            display: inline-block;
            padding: 4px 12px;
            background: #e8f5f3;
            color: #2d8b73;
            border-radius: 15px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* Synopsis */
        .work-synopsis {
            color: #555;
            font-size: 13px;
            line-height: 1.6;
        }

        .work-synopsis p {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Work Stats */
        .work-stats {
            display: flex;
            gap: 12px;
            padding: 12px 0;
            border-top: 1px solid #f0f0f0;
            border-bottom: 1px solid #f0f0f0;
        }

        .stat-badge {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            background: #f8f9fa;
            border-radius: 8px;
            font-size: 12px;
            color: #495057;
            font-weight: 600;
        }

        .stat-badge-icon {
            font-size: 14px;
        }

        /* Work Actions */
        .work-actions {
            display: flex;
            gap: 10px;
            margin-top: auto;
        }

        /* Button Styles */
        .btn {
            width: 100%;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-approve {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .btn-approve:hover {
            background: linear-gradient(135deg, #218838 0%, #1aa179 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        }

        .btn-reject {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        }

        .btn-reject:hover {
            background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
        }

        /* Work Detail Link */
        .work-detail-link {
            color: #2d8b73;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .work-detail-link:hover {
            background: #e8f5f3;
            color: #1e5f4f;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .empty-icon {
            font-size: 80px;
            margin-bottom: 20px;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-15px);
            }

            60% {
                transform: translateY(-7px);
            }
        }

        .empty-state h3 {
            color: #1e5f4f;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #6c757d;
            font-size: 16px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .works-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .work-cover {
                height: 200px;
            }

            .work-actions {
                flex-direction: column;
            }

            .work-actions form {
                width: 100%;
            }

            .stats-bar {
                padding: 15px 20px;
            }

            .stat-icon {
                font-size: 28px;
                padding: 10px;
            }

            .stat-value {
                font-size: 24px;
            }
        }

        @media (max-width: 480px) {
            .work-card {
                border-radius: 10px;
            }

            .work-info {
                padding: 15px;
            }

            .work-title {
                font-size: 16px;
            }

            .work-synopsis {
                font-size: 12px;
            }
        }
    </style>

    <script>
        // Auto hide success alert after 5 seconds
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
