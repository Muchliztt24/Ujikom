@extends('layouts.admin')

@section('content')
<div class="content-header">
    <h1>Moderasi Chapter</h1>
    <p>Kelola dan moderasi semua chapter novel & komik</p>
</div>

<div class="content-body">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats Section -->
    <div class="stats-grid">
        <div class="stat-card stat-total">
            <div class="stat-icon">📚</div>
            <div class="stat-info">
                <span class="stat-value">{{ $chapters->total() }}</span>
                <span class="stat-label">Total Chapter</span>
            </div>
        </div>
        <div class="stat-card stat-novel">
            <div class="stat-icon">📖</div>
            <div class="stat-info">
                <span class="stat-value">{{ $chapters->where('work.type', 'novel')->count() }}</span>
                <span class="stat-label">Novel</span>
            </div>
        </div>
        <div class="stat-card stat-comic">
            <div class="stat-icon">🎨</div>
            <div class="stat-info">
                <span class="stat-value">{{ $chapters->where('work.type', 'comic')->count() }}</span>
                <span class="stat-label">Komik</span>
            </div>
        </div>
        <div class="stat-card stat-views">
            <div class="stat-icon">👁️</div>
            <div class="stat-info">
                <span class="stat-value">{{ number_format($chapters->sum('views')) }}</span>
                <span class="stat-label">Total Views</span>
            </div>
        </div>
    </div>

    @if($chapters->count() > 0)
        <!-- Chapters Table -->
        <div class="table-container">
            <table class="chapters-table">
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 8%">Chapter</th>
                        <th style="width: 25%">Judul Chapter</th>
                        <th style="width: 20%">Karya</th>
                        <th style="width: 12%">Author</th>
                        <th style="width: 8%">Type</th>
                        <th style="width: 8%">Views</th>
                        <th style="width: 10%">Tanggal</th>
                        <th style="width: 12%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chapters as $index => $chapter)
                        <tr>
                            <td>{{ $chapters->firstItem() + $index }}</td>
                            <td>
                                <div class="chapter-order">
                                    <span class="order-badge">{{ $chapter->order }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="chapter-title-cell">
                                    <a href="{{ route('admin.chapters.show', $chapter) }}" class="chapter-title-link">
                                        {{ $chapter->title }}
                                    </a>
                                    @if($chapter->work->type === 'comic' && isset($chapter->images_count))
                                        <span class="image-count">🖼️ {{ $chapter->images_count }} halaman</span>
                                    @elseif($chapter->content)
                                        <span class="word-count">📝 {{ str_word_count(strip_tags($chapter->content)) }} kata</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="work-cell">
                                    <a href="{{ route('admin.works.show', $chapter->work) }}" class="work-link">
                                        {{ Str::limit($chapter->work->title, 30) }}
                                    </a>
                                    <span class="work-status status-{{ $chapter->work->status }}">
                                        {{ ucfirst($chapter->work->status) }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="author-cell">
                                    <div class="author-avatar-mini">
                                        {{ strtoupper(substr($chapter->work->user->name, 0, 1)) }}
                                    </div>
                                    <span class="author-name">{{ $chapter->work->user->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="type-badge type-{{ $chapter->work->type }}">
                                    {{ $chapter->work->type === 'novel' ? '📖 Novel' : '🎨 Komik' }}
                                </span>
                            </td>
                            <td>
                                <div class="views-cell">
                                    <span class="views-icon">👁️</span>
                                    <span class="views-count">{{ number_format($chapter->views ?? 0) }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="date-cell">
                                    <span class="date-main">{{ $chapter->created_at->format('d M Y') }}</span>
                                    <span class="date-time">{{ $chapter->created_at->format('H:i') }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <!-- View Button -->
                                    <a href="{{ route('admin.chapters.show', $chapter) }}" class="btn-action btn-view" title="Lihat Chapter">
                                        👁️
                                    </a>

                                    <!-- Delete/Takedown Button -->
                                    <form action="{{ route('admin.chapters.destroy', $chapter) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Hapus Chapter"
                                            onclick="return confirm('⚠️ HAPUS CHAPTER INI?\n\nChapter: {{ $chapter->title }}\nKarya: {{ $chapter->work->title }}\n\nTindakan ini TIDAK DAPAT DIBATALKAN!')">
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
            {{ $chapters->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">📚</div>
            <h3>Tidak Ada Chapter</h3>
            <p>Belum ada chapter yang tersedia untuk dimoderasi</p>
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

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 30px;
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

    .stat-novel {
        border-color: #8b4513;
    }

    .stat-comic {
        border-color: #9c27b0;
    }

    .stat-views {
        border-color: #17a2b8;
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

    /* Table Container */
    .table-container {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    /* Chapters Table */
    .chapters-table {
        width: 100%;
        border-collapse: collapse;
    }

    .chapters-table thead {
        background: linear-gradient(135deg, #1e5f4f 0%, #2d8b73 100%);
    }

    .chapters-table th {
        padding: 16px;
        text-align: left;
        font-weight: 600;
        font-size: 13px;
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .chapters-table tbody tr {
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s ease;
    }

    .chapters-table tbody tr:hover {
        background-color: #f8fffe;
    }

    .chapters-table tbody tr:last-child {
        border-bottom: none;
    }

    .chapters-table td {
        padding: 16px;
        font-size: 14px;
        color: #333;
        vertical-align: middle;
    }

    /* Chapter Order */
    .chapter-order {
        display: flex;
        justify-content: center;
    }

    .order-badge {
        background: linear-gradient(135deg, #2d8b73 0%, #48c9b0 100%);
        color: white;
        font-weight: 700;
        padding: 8px 14px;
        border-radius: 20px;
        font-size: 14px;
        min-width: 45px;
        text-align: center;
    }

    /* Chapter Title Cell */
    .chapter-title-cell {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .chapter-title-link {
        color: #1e5f4f;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.3s ease;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .chapter-title-link:hover {
        color: #48c9b0;
    }

    .image-count,
    .word-count {
        font-size: 11px;
        color: #6c757d;
        font-weight: 600;
    }

    /* Work Cell */
    .work-cell {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .work-link {
        color: #495057;
        text-decoration: none;
        font-size: 13px;
        transition: color 0.3s ease;
    }

    .work-link:hover {
        color: #2d8b73;
    }

    .work-status {
        font-size: 10px;
        padding: 3px 8px;
        border-radius: 10px;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
        width: fit-content;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-approved {
        background: #d4edda;
        color: #155724;
    }

    .status-draft {
        background: #e2e3e5;
        color: #383d41;
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

    /* Views Cell */
    .views-cell {
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: 600;
    }

    .views-icon {
        font-size: 16px;
    }

    .views-count {
        color: #495057;
        font-size: 14px;
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

        .table-container {
            overflow-x: auto;
        }

        .chapters-table {
            min-width: 1000px;
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