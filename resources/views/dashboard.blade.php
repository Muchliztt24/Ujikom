@extends('layouts.admin')

@section('content')
    @php
        $summary = $data['summary'] ?? [];
        $statusCounts = $data['statusCounts'] ?? ['approved' => 0, 'pending' => 0, 'draft' => 0];
        $typeCounts = $data['typeCounts'] ?? ['comic' => 0, 'novel' => 0];
        $topWorks = collect($data['topWorks'] ?? []);
        $topGenres = collect($data['topGenres'] ?? []);
        $totalStatus = max(1, array_sum($statusCounts));
        $approvedAngle = round(($statusCounts['approved'] / $totalStatus) * 360);
        $pendingAngle = round(($statusCounts['pending'] / $totalStatus) * 360);
        $statusGradient = "conic-gradient(#4ac6a8 0deg {$approvedAngle}deg, #f4b860 {$approvedAngle}deg ".($approvedAngle + $pendingAngle)."deg, rgba(255,255,255,0.12) ".($approvedAngle + $pendingAngle)."deg 360deg)";
        $typeTotal = max(1, array_sum($typeCounts));
    @endphp

    <div class="content-header admin-reveal">
        <h1>{{ $role === 'admin' ? 'Dashboard Admin' : 'Dashboard Uploader' }}</h1>
        <p>
            {{ $role === 'admin' ? 'Pantau performa katalog, distribusi karya, dan aktivitas pembaca dalam satu tampilan.' : 'Lihat performa unggahan, distribusi tipe karya, dan judul yang paling aktif dibaca.' }}
        </p>
    </div>

    <div class="content-body admin-card-grid admin-reveal" data-dashboard-root>
        <section class="admin-stat-grid">
            @if ($role === 'admin')
                <article class="admin-surface admin-stat-card metric-card">
                    <div class="admin-stat-label">Total Karya</div>
                    <div class="admin-stat-value" data-count-to="{{ $summary['works'] ?? 0 }}">0</div>
                    <div class="admin-stat-note">Approved {{ $statusCounts['approved'] }} | Pending {{ $statusCounts['pending'] }} | Draft {{ $statusCounts['draft'] }}</div>
                </article>
                <article class="admin-surface admin-stat-card metric-card">
                    <div class="admin-stat-label">Pengguna Aktif</div>
                    <div class="admin-stat-value" data-count-to="{{ $summary['users'] ?? 0 }}">0</div>
                    <div class="admin-stat-note">Pembaca, uploader, dan admin yang terdaftar.</div>
                </article>
                <article class="admin-surface admin-stat-card metric-card">
                    <div class="admin-stat-label">Interaksi</div>
                    <div class="admin-stat-value" data-count-to="{{ ($summary['bookmarks'] ?? 0) + ($summary['comments'] ?? 0) }}">0</div>
                    <div class="admin-stat-note">Bookmark {{ $summary['bookmarks'] ?? 0 }} | Komentar {{ $summary['comments'] ?? 0 }}</div>
                </article>
                <article class="admin-surface admin-stat-card metric-card">
                    <div class="admin-stat-label">Reader Unik</div>
                    <div class="admin-stat-value" data-count-to="{{ $summary['readers'] ?? 0 }}">0</div>
                    <div class="admin-stat-note">Total akun yang sudah membuka chapter.</div>
                </article>
            @else
                <article class="admin-surface admin-stat-card metric-card">
                    <div class="admin-stat-label">Karya Saya</div>
                    <div class="admin-stat-value" data-count-to="{{ $summary['works'] ?? 0 }}">0</div>
                    <div class="admin-stat-note">Approved {{ $statusCounts['approved'] }} | Pending {{ $statusCounts['pending'] }} | Draft {{ $statusCounts['draft'] }}</div>
                </article>
                <article class="admin-surface admin-stat-card metric-card">
                    <div class="admin-stat-label">Chapter</div>
                    <div class="admin-stat-value" data-count-to="{{ $summary['chapters'] ?? 0 }}">0</div>
                    <div class="admin-stat-note">Jumlah chapter aktif di semua karya milikmu.</div>
                </article>
                <article class="admin-surface admin-stat-card metric-card">
                    <div class="admin-stat-label">Bookmark</div>
                    <div class="admin-stat-value" data-count-to="{{ $summary['bookmarks'] ?? 0 }}">0</div>
                    <div class="admin-stat-note">Total bookmark dari seluruh judul milikmu.</div>
                </article>
                <article class="admin-surface admin-stat-card metric-card">
                    <div class="admin-stat-label">Reader Unik</div>
                    <div class="admin-stat-value" data-count-to="{{ $summary['readers'] ?? 0 }}">0</div>
                    <div class="admin-stat-note">Pembaca berbeda yang sudah membuka chapter.</div>
                </article>
            @endif
        </section>

        <section class="dashboard-grid-two">
            <article class="admin-surface admin-form-card chart-card">
                <div class="chart-card-head">
                    <div>
                        <div class="admin-form-title">Distribusi Status</div>
                        <div class="admin-help">Diagram ring untuk memantau komposisi katalog saat ini.</div>
                    </div>
                    <div class="donut-chart" style="--status-gradient: {{ $statusGradient }};">
                        <div class="donut-center">{{ $summary['works'] ?? 0 }}</div>
                    </div>
                </div>

                <div class="legend-grid">
                    <div class="legend-item"><span class="legend-dot success"></span> Approved <strong>{{ $statusCounts['approved'] }}</strong></div>
                    <div class="legend-item"><span class="legend-dot warning"></span> Pending <strong>{{ $statusCounts['pending'] }}</strong></div>
                    <div class="legend-item"><span class="legend-dot neutral"></span> Draft <strong>{{ $statusCounts['draft'] }}</strong></div>
                </div>

                <div class="bar-chart">
                    @foreach (['approved' => 'Approved', 'pending' => 'Pending', 'draft' => 'Draft'] as $key => $label)
                        @php $percent = round(($statusCounts[$key] / $totalStatus) * 100); @endphp
                        <div class="bar-row">
                            <div class="bar-meta">
                                <span>{{ $label }}</span>
                                <strong>{{ $statusCounts[$key] }}</strong>
                            </div>
                            <div class="bar-track">
                                <div class="bar-fill {{ $key }}" data-bar-width="{{ $percent }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="admin-surface admin-form-card chart-card">
                <div class="admin-form-title">{{ $role === 'admin' ? 'Komposisi Katalog' : 'Komposisi Karya Saya' }}</div>
                <div class="admin-help">Perbandingan antara novel dan comic yang tersedia saat ini.</div>

                <div class="stack-card">
                    <div class="stack-bar">
                        <div class="stack-fill novel" data-bar-width="{{ round(($typeCounts['novel'] / $typeTotal) * 100) }}"></div>
                        <div class="stack-fill comic" data-bar-width="{{ round(($typeCounts['comic'] / $typeTotal) * 100) }}"></div>
                    </div>
                    <div class="stack-stats">
                        <div>
                            <span>Novel</span>
                            <strong>{{ $typeCounts['novel'] }}</strong>
                        </div>
                        <div>
                            <span>Comic</span>
                            <strong>{{ $typeCounts['comic'] }}</strong>
                        </div>
                    </div>
                </div>

                @if ($role === 'admin')
                    <div class="admin-form-title" style="margin-top: 22px;">Genre Teratas</div>
                    <div class="bar-chart compact">
                        @foreach ($topGenres as $genre)
                            @php $genrePercent = $summary['works'] ? round(($genre->works_count / $summary['works']) * 100) : 0; @endphp
                            <div class="bar-row">
                                <div class="bar-meta">
                                    <span>{{ $genre->name }}</span>
                                    <strong>{{ $genre->works_count }}</strong>
                                </div>
                                <div class="bar-track">
                                    <div class="bar-fill gradient" data-bar-width="{{ $genrePercent }}"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="admin-form-title" style="margin-top: 22px;">Aktivitas Unggahan</div>
                    <div class="legend-grid">
                        <div class="legend-item"><span class="legend-dot success"></span> Comments <strong>{{ $summary['comments'] ?? 0 }}</strong></div>
                        <div class="legend-item"><span class="legend-dot info"></span> Pages <strong>{{ $summary['images'] ?? 0 }}</strong></div>
                    </div>
                @endif
            </article>
        </section>

        <section class="admin-surface admin-form-card admin-reveal">
            <div class="chart-card-head">
                <div>
                    <div class="admin-form-title">{{ $role === 'admin' ? 'Judul Paling Ramai' : 'Performa Karya Saya' }}</div>
                    <div class="admin-help">{{ $role === 'admin' ? 'Karya dengan pembaca dan interaksi tertinggi di katalog.' : 'Judul yang paling aktif dibaca, dikomentari, dan disimpan.' }}</div>
                </div>
                @if ($role === 'uploader')
                    <div class="admin-btn-row">
                        <a href="{{ route('works.create') }}" class="admin-btn primary">Tambah Karya</a>
                        <a href="{{ route('works.index') }}" class="admin-btn secondary">Kelola Semua</a>
                    </div>
                @endif
            </div>

            @if ($topWorks->count())
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>{{ $role === 'admin' ? 'Author / Uploader' : 'Status' }}</th>
                                <th>Readers</th>
                                <th>Bookmark</th>
                                <th>Komentar</th>
                                <th>Chapter</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topWorks as $work)
                                <tr>
                                    <td>
                                        <strong>{{ $work['title'] }}</strong>
                                        @if ($role === 'uploader')
                                            <div class="admin-muted" style="margin-top: 4px;">{{ $work['author'] }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($role === 'admin')
                                            <div>{{ $work['author'] }}</div>
                                            <div class="admin-muted" style="margin-top: 4px;">Uploader: {{ $work['uploader'] }}</div>
                                        @else
                                            <span class="admin-chip {{ $work['status'] === 'approved' ? 'success' : ($work['status'] === 'pending' ? 'warning' : '') }}">{{ ucfirst($work['status']) }}</span>
                                            <div class="admin-muted" style="margin-top: 4px;">{{ ucfirst($work['type']) }}</div>
                                        @endif
                                    </td>
                                    <td>{{ $work['readers'] }}</td>
                                    <td>{{ $work['bookmarks'] }}</td>
                                    <td>{{ $work['comments'] }}</td>
                                    <td>{{ $work['chapters'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="admin-empty">Data performa akan muncul saat katalog mulai aktif digunakan.</div>
            @endif
        </section>
    </div>

    <style>
        .dashboard-grid-two {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }
        .metric-card {
            position: relative;
            overflow: hidden;
        }
        .metric-card::after {
            content: "";
            position: absolute;
            inset: auto -40px -40px auto;
            width: 120px;
            height: 120px;
            background: radial-gradient(circle, rgba(74, 198, 168, 0.18), transparent 68%);
            pointer-events: none;
        }
        .chart-card-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }
        .donut-chart {
            width: 132px;
            height: 132px;
            border-radius: 50%;
            background: var(--status-gradient);
            display: grid;
            place-items: center;
            position: relative;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.06);
        }
        .donut-chart::after {
            content: "";
            position: absolute;
            width: 82px;
            height: 82px;
            border-radius: 50%;
            background: #071922;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.04);
        }
        .donut-center {
            position: relative;
            z-index: 1;
            font-size: 24px;
            font-weight: 800;
        }
        .legend-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
            margin-bottom: 18px;
        }
        .legend-item {
            padding: 12px 14px;
            border-radius: 12px;
            background: rgba(255,255,255,0.04);
            color: var(--admin-text-soft);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            font-weight: 600;
        }
        .legend-item strong {
            margin-left: auto;
            color: var(--admin-text);
        }
        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex: 0 0 10px;
        }
        .legend-dot.success { background: #4ac6a8; }
        .legend-dot.warning { background: #f4b860; }
        .legend-dot.neutral { background: rgba(255,255,255,0.35); }
        .legend-dot.info { background: #60a5fa; }
        .bar-chart {
            display: grid;
            gap: 14px;
        }
        .bar-chart.compact {
            margin-top: 12px;
        }
        .bar-row {
            display: grid;
            gap: 8px;
        }
        .bar-meta {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            color: var(--admin-text-soft);
            font-size: 13px;
            font-weight: 600;
        }
        .bar-meta strong {
            color: var(--admin-text);
        }
        .bar-track,
        .stack-bar {
            width: 100%;
            height: 12px;
            border-radius: 999px;
            overflow: hidden;
            background: rgba(255,255,255,0.06);
        }
        .bar-fill,
        .stack-fill {
            width: 0;
            height: 100%;
            border-radius: inherit;
            transition: width 1.1s cubic-bezier(.22,1,.36,1);
        }
        .bar-fill.approved { background: linear-gradient(90deg, #1f8f79, #4ac6a8); }
        .bar-fill.pending { background: linear-gradient(90deg, #9a6d2d, #f4b860); }
        .bar-fill.draft { background: linear-gradient(90deg, rgba(255,255,255,0.16), rgba(255,255,255,0.34)); }
        .bar-fill.gradient { background: linear-gradient(90deg, #4ac6a8, #60a5fa); }
        .stack-card {
            margin-top: 18px;
            display: grid;
            gap: 14px;
        }
        .stack-bar {
            display: flex;
            height: 16px;
        }
        .stack-fill.novel { background: linear-gradient(90deg, #5b8def, #60a5fa); }
        .stack-fill.comic { background: linear-gradient(90deg, #1f8f79, #4ac6a8); }
        .stack-stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }
        .stack-stats div {
            padding: 14px 16px;
            border-radius: 14px;
            background: rgba(255,255,255,0.04);
            display: grid;
            gap: 4px;
        }
        .stack-stats span {
            color: var(--admin-text-soft);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .04em;
        }
        .stack-stats strong {
            font-size: 24px;
        }
        @media (max-width: 960px) {
            .dashboard-grid-two {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-count-to]').forEach((node) => {
                const target = parseInt(node.dataset.countTo || '0', 10);
                const duration = 900;
                const start = performance.now();

                const step = (time) => {
                    const progress = Math.min((time - start) / duration, 1);
                    node.textContent = Math.round(target * (1 - Math.pow(1 - progress, 3)));
                    if (progress < 1) {
                        requestAnimationFrame(step);
                    }
                };

                requestAnimationFrame(step);
            });

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) {
                        return;
                    }

                    entry.target.querySelectorAll('[data-bar-width]').forEach((bar) => {
                        bar.style.width = `${bar.dataset.barWidth}%`;
                    });

                    observer.unobserve(entry.target);
                });
            }, { threshold: 0.2 });

            document.querySelectorAll('[data-dashboard-root], .chart-card').forEach((section) => observer.observe(section));
        });
    </script>
@endsection
