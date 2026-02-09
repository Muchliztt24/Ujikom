@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <div class="header-welcome">
            <div>
                <h1>Dashboard Admin</h1>
                <p>Selamat datang di panel admin Nokomi, {{ Auth::user()->name }}!</p>
            </div>
            <div class="header-date">
                <div class="date-icon">📅</div>
                <div class="date-info">
                    <span class="date-day">{{ now()->format('l') }}</span>
                    <span class="date-full">{{ now()->format('d F Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <!-- Main Stats Grid -->
        <div class="main-stats-grid">
            <!-- Total Works -->
            <div class="stat-card stat-primary">
                <div class="stat-icon">📚</div>
                <div class="stat-content">
                    <span class="stat-label">Total Karya</span>
                    <span class="stat-value">{{ \App\Models\Work::count() }}</span>
                    <span class="stat-change">
                        <span class="change-up">↑ {{ \App\Models\Work::whereDate('created_at', today())->count() }}</span>
                        hari ini
                    </span>
                </div>
            </div>

            <!-- Total Users -->
            <div class="stat-card stat-info">
                <div class="stat-icon">👥</div>
                <div class="stat-content">
                    <span class="stat-label">Total Pengguna</span>
                    <span class="stat-value">{{ \App\Models\User::count() }}</span>
                    <span class="stat-change">
                        <span class="change-up">↑ {{ \App\Models\User::whereDate('created_at', today())->count() }}</span>
                        hari ini
                    </span>
                </div>
            </div>

            <!-- Total Chapters -->
            <div class="stat-card stat-success">
                <div class="stat-icon">📄</div>
                <div class="stat-content">
                    <span class="stat-label">Total Chapter</span>
                    <span class="stat-value">{{ \App\Models\Chapter::count() }}</span>
                    <span class="stat-change">
                        <span class="change-up">↑
                            {{ \App\Models\Chapter::whereDate('created_at', today())->count() }}</span> hari ini
                    </span>
                </div>
            </div>

            <!-- Total Images -->
            <div class="stat-card stat-warning">
                <div class="stat-icon">🖼️</div>
                <div class="stat-content">
                    <span class="stat-label">Total Gambar</span>
                    <span class="stat-value">{{ \App\Models\ChapterImage::count() }}</span>
                    <span class="stat-change">
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="charts-row">
            <!-- Work Status Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3>📊 Status Karya</h3>
                    <span class="chart-subtitle">Distribusi status karya</span>
                </div>
                <div class="chart-container">
                    <canvas id="workStatusChart"></canvas>
                </div>
                <div class="chart-legend">
                    <div class="legend-item">
                        <span class="legend-dot" style="background: #28a745;"></span>
                        <span>Approved: {{ \App\Models\Work::where('status', 'approved')->count() }}</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-dot" style="background: #ffc107;"></span>
                        <span>Pending: {{ \App\Models\Work::where('status', 'pending')->count() }}</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-dot" style="background: #6c757d;"></span>
                        <span>Draft: {{ \App\Models\Work::where('status', 'draft')->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Work Type Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3>📖 Tipe Karya</h3>
                    <span class="chart-subtitle">Novel vs Komik</span>
                </div>
                <div class="chart-container">
                    <canvas id="workTypeChart"></canvas>
                </div>
                <div class="chart-legend">
                    <div class="legend-item">
                        <span class="legend-dot" style="background: #8b4513;"></span>
                        <span>Novel: {{ \App\Models\Work::where('type', 'novel')->count() }}</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-dot" style="background: #9c27b0;"></span>
                        <span>Komik: {{ \App\Models\Work::where('type', 'comic')->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Monthly Growth Chart -->
            <div class="chart-card chart-wide">
                <div class="chart-header">
                    <h3>📈 Pertumbuhan Karya (6 Bulan Terakhir)</h3>
                    <span class="chart-subtitle">Karya baru per bulan</span>
                </div>
                <div class="chart-container">
                    <canvas id="monthlyGrowthChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Activity Stats -->
        <div class="activity-section">
            <div class="activity-header">
                <h3>⚡ Aktivitas Terkini</h3>
            </div>

            <div class="activity-grid">
                <!-- Pending Approvals -->
                <div class="activity-card pending-card">
                    <div class="activity-icon">⏳</div>
                    <div class="activity-content">
                        <h4>Menunggu Persetujuan</h4>
                        <div class="activity-value">{{ \App\Models\Work::where('status', 'pending')->count() }}</div>
                        <p>Karya perlu direview</p>
                    </div>
                    <a href="{{ route('admin.works.pending') }}" class="activity-link">Review →</a>
                </div>

                <!-- Today's Uploads -->
                <div class="activity-card upload-card">
                    <div class="activity-icon">📤</div>
                    <div class="activity-content">
                        <h4>Upload Hari Ini</h4>
                        <div class="activity-value">{{ \App\Models\Work::whereDate('created_at', today())->count() }}</div>
                        <p>Karya baru ditambahkan</p>
                    </div>
                    <a href="{{ route('admin.works.index') }}" class="activity-link">Lihat →</a>
                </div>

                <!-- Active Users -->
                <div class="activity-card user-card">
                    <div class="activity-icon">👤</div>
                    <div class="activity-content">
                        <h4>Pengguna Aktif</h4>
                        <div class="activity-value">{{ \App\Models\User::where('role_id', '!=', null)->count() }}</div>
                        <p>Total pengguna terdaftar</p>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="activity-link">Kelola →</a>
                </div>

                <!-- Total Genres -->
                <div class="activity-card genre-card">
                    <div class="activity-icon">🎭</div>
                    <div class="activity-content">
                        <h4>Genre Tersedia</h4>
                        <div class="activity-value">{{ \App\Models\Genre::count() }}</div>
                        <p>Kategori karya</p>
                    </div>
                    <a href="{{ route('admin.genres.index') }}" class="activity-link">Kelola →</a>
                </div>
            </div>
        </div>

        <!-- Quick Actions untuk Admin -->
        @if (auth()->user()->role?->name === 'admin')
            <div class="quick-actions-section">
                <h3>🚀 Quick Actions</h3>
                <div class="admin-actions">
                    <a href="{{ route('admin.users.index') }}" class="admin-btn btn-users">
                        <span class="btn-icon">👤</span>
                        <span class="btn-text">Kelola Pengguna</span>
                    </a>

                    <a href="{{ route('admin.genres.index') }}" class="admin-btn btn-genres">
                        <span class="btn-icon">🏷️</span>
                        <span class="btn-text">Kelola Genre</span>
                    </a>

                    <a href="{{ route('admin.works.index') }}" class="admin-btn btn-works">
                        <span class="btn-icon">🛡️</span>
                        <span class="btn-text">Moderasi Works</span>
                    </a>

                    <a href="{{ route('admin.chapters.index') }}" class="admin-btn btn-chapters">
                        <span class="btn-icon">📚</span>
                        <span class="btn-text">Moderasi Chapter</span>
                    </a>

                    <a href="{{ route('admin.chapter-images.index') }}" class="admin-btn btn-images">
                        <span class="btn-icon">🖼️</span>
                        <span class="btn-text">Chapter Images</span>
                    </a>

                    <a href="{{ route('admin.works.pending') }}" class="admin-btn btn-approvals">
                        <span class="btn-icon">✅</span>
                        <span class="btn-text">Pending Approvals</span>
                    </a>
                </div>
            </div>
        @endif

        <!-- Quick Actions untuk Uploader -->
        @if (auth()->user()->role?->name === 'uploader')
            <div class="quick-actions-section">
                <h3>🚀 Quick Actions</h3>
                <div class="uploader-actions">
                    <a href="{{ route('works.create') }}" class="action-btn btn-create">
                        <span class="btn-icon">➕</span>
                        <span class="btn-text">Tambah Karya Baru</span>
                    </a>

                    <a href="{{ route('works.index') }}" class="action-btn btn-list">
                        <span class="btn-icon">📚</span>
                        <span class="btn-text">Lihat Semua Karya</span>
                    </a>
                </div>
            </div>
        @endif
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

        /* Header Welcome */
        .header-welcome {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-date {
            display: flex;
            align-items: center;
            gap: 15px;
            background: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .date-icon {
            font-size: 32px;
        }

        .date-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .date-day {
            font-size: 14px;
            font-weight: 600;
            color: #1e5f4f;
        }

        .date-full {
            font-size: 12px;
            color: #6c757d;
        }

        /* Main Stats Grid */
        .main-stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.3s ease;
            border-left: 4px solid;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .stat-primary {
            border-color: #2d8b73;
        }

        .stat-info {
            border-color: #17a2b8;
        }

        .stat-success {
            border-color: #28a745;
        }

        .stat-warning {
            border-color: #ffc107;
        }

        .stat-icon {
            font-size: 48px;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #e8f5f3 0%, #d4edea 100%);
            border-radius: 12px;
            flex-shrink: 0;
        }

        .stat-content {
            display: flex;
            flex-direction: column;
            gap: 5px;
            flex: 1;
        }

        .stat-label {
            font-size: 13px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: #1e5f4f;
            line-height: 1;
        }

        .stat-change {
            font-size: 12px;
            color: #6c757d;
        }

        .change-up {
            color: #28a745;
            font-weight: 600;
        }

        /* Charts Row */
        .charts-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .chart-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .chart-wide {
            grid-column: span 3;
        }

        .chart-header {
            margin-bottom: 20px;
        }

        .chart-header h3 {
            color: #1e5f4f;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .chart-subtitle {
            font-size: 13px;
            color: #6c757d;
        }

        .chart-container {
            position: relative;
            height: 250px;
            margin-bottom: 15px;
        }

        .chart-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            padding-top: 15px;
            border-top: 1px solid #f0f0f0;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #495057;
        }

        .legend-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        /* Activity Section */
        .activity-section {
            margin-bottom: 30px;
        }

        .activity-header {
            margin-bottom: 20px;
        }

        .activity-header h3 {
            color: #1e5f4f;
            font-size: 20px;
            font-weight: 700;
        }

        .activity-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .activity-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
            gap: 15px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .activity-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .pending-card::before {
            background: linear-gradient(90deg, #ffc107 0%, #ff9800 100%);
        }

        .upload-card::before {
            background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
        }

        .user-card::before {
            background: linear-gradient(90deg, #17a2b8 0%, #138496 100%);
        }

        .genre-card::before {
            background: linear-gradient(90deg, #9c27b0 0%, #7b1fa2 100%);
        }

        .activity-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .activity-icon {
            font-size: 48px;
            text-align: center;
        }

        .activity-content h4 {
            color: #1e5f4f;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .activity-value {
            font-size: 40px;
            font-weight: 700;
            color: #1e5f4f;
            text-align: center;
            line-height: 1;
        }

        .activity-content p {
            font-size: 12px;
            color: #6c757d;
            text-align: center;
        }

        .activity-link {
            text-align: center;
            color: #2d8b73;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            padding: 10px;
            border-radius: 6px;
            background: #e8f5f3;
            transition: all 0.3s ease;
        }

        .activity-link:hover {
            background: #2d8b73;
            color: white;
        }

        /* Quick Actions Section */
        .quick-actions-section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .quick-actions-section h3 {
            color: #1e5f4f;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .admin-actions,
        .uploader-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .admin-btn,
        .action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            padding: 20px;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .btn-icon {
            font-size: 40px;
        }

        .btn-text {
            font-weight: 600;
            font-size: 14px;
            text-align: center;
        }

        .btn-users {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }

        .btn-genres {
            background: linear-gradient(135deg, #9c27b0 0%, #7b1fa2 100%);
            color: white;
        }

        .btn-works {
            background: linear-gradient(135deg, #2d8b73 0%, #48c9b0 100%);
            color: white;
        }

        .btn-chapters {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .btn-images {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }

        .btn-approvals {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            color: white;
        }

        .btn-create {
            background: linear-gradient(135deg, #48c9b0 0%, #5fd4bd 100%);
            color: white;
        }

        .btn-list {
            background: linear-gradient(135deg, #2d8b73 0%, #48c9b0 100%);
            color: white;
        }

        .admin-btn:hover,
        .action-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .main-stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .charts-row {
                grid-template-columns: 1fr;
            }

            .chart-wide {
                grid-column: span 1;
            }

            .activity-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .header-welcome {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .main-stats-grid {
                grid-template-columns: 1fr;
            }

            .activity-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        // Work Status Chart (Doughnut)
        const workStatusCtx = document.getElementById('workStatusChart').getContext('2d');
        new Chart(workStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Approved', 'Pending', 'Draft'],
                datasets: [{
                    data: [
                        {{ \App\Models\Work::where('status', 'approved')->count() }},
                        {{ \App\Models\Work::where('status', 'pending')->count() }},
                        {{ \App\Models\Work::where('status', 'draft')->count() }}
                    ],
                    backgroundColor: ['#28a745', '#ffc107', '#6c757d'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Work Type Chart (Pie)
        const workTypeCtx = document.getElementById('workTypeChart').getContext('2d');
        new Chart(workTypeCtx, {
            type: 'pie',
            data: {
                labels: ['Novel', 'Komik'],
                datasets: [{
                    data: [
                        {{ \App\Models\Work::where('type', 'novel')->count() }},
                        {{ \App\Models\Work::where('type', 'comic')->count() }}
                    ],
                    backgroundColor: ['#8b4513', '#9c27b0'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Monthly Growth Chart (Bar)
        const monthlyGrowthCtx = document.getElementById('monthlyGrowthChart').getContext('2d');

        // Get last 6 months data
        @php
            $monthsData = collect(range(5, 0))
                ->map(function ($monthsAgo) {
                    $date = now()->subMonths($monthsAgo);
                    return [
                        'label' => $date->format('M Y'),
                        'count' => \App\Models\Work::whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count(),
                    ];
                })
                ->values();
        @endphp

        const monthsData = @json($monthsData);

        new Chart(monthlyGrowthCtx, {
            type: 'bar',
            data: {
                labels: monthsData.map(d => d.label),
                datasets: [{
                    label: 'Karya Baru',
                    data: monthsData.map(d => d.count),
                    backgroundColor: 'rgba(45, 139, 115, 0.8)',
                    borderColor: 'rgba(45, 139, 115, 1)',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
@endsection
