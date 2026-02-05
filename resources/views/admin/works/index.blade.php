@extends('layouts.admin')

@section('content')
<div class="content-header">
    <h1>Semua Karya</h1>
    <p>Kelola semua karya yang ada di platform</p>
</div>

<div class="content-body">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter & Stats Section -->
    <div class="filter-stats-container">
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card stat-total">
                <div class="stat-icon">📚</div>
                <div class="stat-info">
                    <span class="stat-value">{{ $works->total() }}</span>
                    <span class="stat-label">Total Karya</span>
                </div>
            </div>
            <div class="stat-card stat-pending">
                <div class="stat-icon">⏳</div>
                <div class="stat-info">
                    <span class="stat-value">{{ $works->where('status', 'pending')->count() }}</span>
                    <span class="stat-label">Pending</span>
                </div>
            </div>
            <div class="stat-card stat-approved">
                <div class="stat-icon">✅</div>
                <div class="stat-info">
                    <span class="stat-value">{{ $works->where('status', 'approved')->count() }}</span>
                    <span class="stat-label">Approved</span>
                </div>
            </div>
            <div class="stat-card stat-draft">
                <div class="stat-icon">📝</div>
                <div class="stat-info">
                    <span class="stat-value">{{ $works->where('status', 'draft')->count() }}</span>
                    <span class="stat-label">Draft</span>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <a href="{{ route('admin.works.index') }}" class="filter-tab {{ !request('status') ? 'active' : '' }}">
                🌟 Semua
            </a>
            <a href="{{ route('admin.works.index', ['status' => 'pending']) }}" class="filter-tab {{ request('status') == 'pending' ? 'active' : '' }}">
                ⏳ Pending
            </a>
            <a href="{{ route('admin.works.index', ['status' => 'approved']) }}" class="filter-tab {{ request('status') == 'approved' ? 'active' : '' }}">
                ✅ Approved
            </a>
            <a href="{{ route('admin.works.index', ['status' => 'draft']) }}" class="filter-tab {{ request('status') == 'draft' ? 'active' : '' }}">
                📝 Draft
            </a>
        </div>
    </div>

    @if($works->count() > 0)
        <!-- Works Table -->
        <div class="table-container">
            <table class="works-table">
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 10%">Cover</th>
                        <th style="width: 25%">Judul</th>
                        <th style="width: 12%">Author</th>
                        <th style="width: 8%">Type</th>
                        <th style="width: 10%">Status</th>
                        <th style="width: 10%">Tanggal</th>
                        <th style="width: 20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($works as $index => $work)
                        <tr>
                            <td>{{ $works->firstItem() + $index }}</td>
                            <td>
                                <div class="work-cover-thumb">
                                    @if($work->cover)
                                        <img src="{{ Storage::url($work->cover) }}" alt="{{ $work->title }}">
                                    @else
                                        <div class="cover-placeholder">
                                            {{ $work->type === 'novel' ? '📖' : '🎨' }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="work-title-cell">
                                    <a href="{{ route('admin.works.show', $work) }}" class="work-title-link">
                                        {{ $work->title }}
                                    </a>
                                    @if($work->genres->count() > 0)
                                        <div class="genre-tags-mini">
                                            @foreach($work->genres->take(2) as $genre)
                                                <span class="genre-tag-mini">{{ $genre->name }}</span>
                                            @endforeach
                                            @if($work->genres->count() > 2)
                                                <span class="genre-tag-mini">+{{ $work->genres->count() - 2 }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="author-cell">
                                    <div class="author-avatar-mini">
                                        {{ strtoupper(substr($work->user->name, 0, 1)) }}
                                    </div>
                                    <span class="author-name">{{ $work->user->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="type-badge type-{{ $work->type }}">
                                    {{ $work->type === 'novel' ? '📚 Novel' : '🎨 Komik' }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $work->status }}">
                                    @if($work->status === 'pending')
                                        ⏳ Pending
                                    @elseif($work->status === 'approved')
                                        ✅ Approved
                                    @else
                                        📝 Draft
                                    @endif
                                </span>
                            </td>
                            <td>
                                <div class="date-cell">
                                    <span class="date-main">{{ $work->created_at->format('d M Y') }}</span>
                                    <span class="date-time">{{ $work->created_at->format('H:i') }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <!-- View Button -->
                                    <a href="{{ route('admin.works.show', $work) }}" class="btn-action btn-view" title="Lihat Detail">
                                        👁️
                                    </a>

                                    @if($work->status === 'pending')
                                        <!-- Approve Button -->
                                        <form action="{{ route('admin.works.approve', $work) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-action btn-approve" title="Approve" 
                                                onclick="return confirm('Approve karya ini?')">
                                                ✅
                                            </button>
                                        </form>

                                        <!-- Reject Button -->
                                        <form action="{{ route('admin.works.reject', $work) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-action btn-reject" title="Reject"
                                                onclick="return confirm('Reject karya ini?')">
                                                ❌
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Delete/Takedown Button -->
                                    <form action="{{ route('admin.works.destroy', $work) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Hapus/Takedown"
                                            onclick="return confirm('HAPUS karya ini secara permanen?')">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination-container">
            {{ $works->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <h3>Tidak Ada Karya</h3>
            <p>Belum ada karya yang ditampilkan dengan filter ini</p>
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

    /* Filter & Stats Container */
    .filter-stats-container {
        margin-bottom: 30px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border-left: 4px solid;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.12);
    }

    .stat-total {
        border-color: #2d8b73;
    }

    .stat-pending {
        border-color: #ffc107;
    }

    .stat-approved {
        border-color: #28a745;
    }

    .stat-draft {
        border-color: #6c757d;
    }

    .stat-icon {
        font-size: 36px;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border-radius: 10px;
    }

    .stat-info {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #1e5f4f;
        line-height: 1;
    }

    .stat-label {
        font-size: 13px;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Filter Tabs */
    .filter-tabs {
        display: flex;
        gap: 10px;
        background: white;
        padding: 10px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .filter-tab {
        flex: 1;
        padding: 12px 20px;
        text-align: center;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        color: #6c757d;
        background: #f8f9fa;
        transition: all 0.3s ease;
    }

    .filter-tab:hover {
        background: #e9ecef;
        color: #495057;
    }

    .filter-tab.active {
        background: linear-gradient(135deg, #2d8b73 0%, #48c9b0 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(45, 139, 115, 0.3);
    }

    /* Table Container */
    .table-container {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    /* Works Table */
    .works-table {
        width: 100%;
        border-collapse: collapse;
    }

    .works-table thead {
        background: linear-gradient(135deg, #1e5f4f 0%, #2d8b73 100%);
    }

    .works-table th {
        padding: 16px;
        text-align: left;
        font-weight: 600;
        font-size: 13px;
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .works-table tbody tr {
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s ease;
    }

    .works-table tbody tr:hover {
        background-color: #f8fffe;
    }

    .works-table tbody tr:last-child {
        border-bottom: none;
    }

    .works-table td {
        padding: 16px;
        font-size: 14px;
        color: #333;
        vertical-align: middle;
    }

    /* Work Cover Thumbnail */
    .work-cover-thumb {
        width: 60px;
        height: 85px;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .work-cover-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cover-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #e0f2f1 0%, #b2dfdb 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        opacity: 0.6;
    }

    /* Work Title Cell */
    .work-title-cell {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .work-title-link {
        color: #1e5f4f;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.3s ease;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .work-title-link:hover {
        color: #48c9b0;
    }

    .genre-tags-mini {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
    }

    .genre-tag-mini {
        font-size: 10px;
        padding: 2px 8px;
        background: #e8f5f3;
        color: #2d8b73;
        border-radius: 10px;
        font-weight: 600;
    }

    /* Author Cell */
    .author-cell {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .author-avatar-mini {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #48c9b0 0%, #5fd4bd 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #1e5f4f;
        font-size: 12px;
        flex-shrink: 0;
    }

    .author-name {
        font-size: 13px;
        color: #495057;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Type Badge */
    .type-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    .type-novel {
        background: rgba(139, 69, 19, 0.1);
        color: #8b4513;
    }

    .type-comic {
        background: rgba(156, 39, 176, 0.1);
        color: #9c27b0;
    }

    /* Status Badge */
    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        white-space: nowrap;
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

    /* Date Cell */
    .date-cell {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .date-main {
        font-size: 13px;
        font-weight: 600;
        color: #333;
    }

    .date-time {
        font-size: 11px;
        color: #6c757d;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-view {
        background: #17a2b8;
        color: white;
    }

    .btn-view:hover {
        background: #138496;
        transform: scale(1.1);
    }

    .btn-approve {
        background: #28a745;
        color: white;
    }

    .btn-approve:hover {
        background: #218838;
        transform: scale(1.1);
    }

    .btn-reject {
        background: #ffc107;
        color: white;
    }

    .btn-reject:hover {
        background: #e0a800;
        transform: scale(1.1);
    }

    .btn-delete {
        background: #dc3545;
        color: white;
    }

    .btn-delete:hover {
        background: #c82333;
        transform: scale(1.1);
    }

    /* Pagination */
    .pagination-container {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-top: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
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
        font-size: 100px;
        margin-bottom: 20px;
        opacity: 0.5;
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
    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .filter-tabs {
            flex-direction: column;
        }

        .table-container {
            overflow-x: auto;
        }

        .works-table {
            min-width: 900px;
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