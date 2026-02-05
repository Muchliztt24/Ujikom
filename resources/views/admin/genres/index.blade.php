@extends('layouts.admin')

@section('content')
<div class="content-header">
    <div class="header-with-action">
        <div>
            <h1>Kelola Genre</h1>
            <p>Manajemen genre untuk karya novel dan komik</p>
        </div>
        <a href="{{ route('admin.genres.create') }}" class="btn-add">
            ➕ Tambah Genre
        </a>
    </div>
</div>

<div class="content-body">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($genres->count() > 0)
        <div class="stats-info">
            <div class="stat-box">
                <div class="stat-icon">🎭</div>
                <div class="stat-content">
                    <span class="stat-number">{{ $genres->count() }}</span>
                    <span class="stat-text">Total Genre</span>
                </div>
            </div>
        </div>

        <div class="genres-grid">
            @foreach($genres as $genre)
                <div class="genre-card">
                    <div class="genre-icon">🎭</div>
                    <div class="genre-content">
                        <h3 class="genre-name">{{ $genre->name }}</h3>
                        <div class="genre-meta">
                            <span class="meta-badge">
                                📚 {{ $genre->works_count ?? 0 }} Karya
                            </span>
                            <span class="meta-date">
                                📅 {{ $genre->created_at->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                    <div class="genre-actions">
                        <a href="{{ route('admin.genres.edit', $genre) }}" class="btn-action btn-edit" title="Edit">
                            ✏️
                        </a>
                        <form action="{{ route('admin.genres.destroy', $genre) }}" method="POST" 
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus genre ini?')" 
                            style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete" title="Hapus">
                                🗑️
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">📚</div>
            <h3>Belum Ada Genre</h3>
            <p>Tambahkan genre pertama Anda untuk mengkategorikan karya</p>
            <a href="{{ route('admin.genres.create') }}" class="btn-add-large">
                ➕ Tambah Genre Pertama
            </a>
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

    /* Header with Action */
    .header-with-action {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: linear-gradient(135deg, #2d8b73 0%, #48c9b0 100%);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(45, 139, 115, 0.3);
    }

    .btn-add:hover {
        background: linear-gradient(135deg, #1e5f4f 0%, #2d8b73 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 95, 79, 0.4);
    }

    /* Stats Info */
    .stats-info {
        margin-bottom: 30px;
    }

    .stat-box {
        background: linear-gradient(135deg, #1e5f4f 0%, #2d8b73 100%);
        padding: 25px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 4px 15px rgba(30, 95, 79, 0.2);
    }

    .stat-icon {
        font-size: 48px;
        background: rgba(255, 255, 255, 0.2);
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }

    .stat-content {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .stat-number {
        font-size: 36px;
        font-weight: 700;
        color: white;
        line-height: 1;
    }

    .stat-text {
        font-size: 16px;
        color: #b8e6d5;
    }

    /* Genres Grid */
    .genres-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
    }

    /* Genre Card */
    .genre-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .genre-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        border-color: #48c9b0;
    }

    .genre-icon {
        font-size: 48px;
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #e8f5f3 0%, #d4edea 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .genre-content {
        flex: 1;
        min-width: 0;
    }

    .genre-name {
        font-size: 20px;
        font-weight: 700;
        color: #1e5f4f;
        margin-bottom: 10px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .genre-meta {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .meta-badge,
    .meta-date {
        font-size: 12px;
        color: #6c757d;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .meta-badge {
        font-weight: 600;
    }

    /* Genre Actions */
    .genre-actions {
        display: flex;
        gap: 8px;
        flex-shrink: 0;
    }

    .btn-action {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-edit {
        background: #48c9b0;
        color: white;
    }

    .btn-edit:hover {
        background: #2d8b73;
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
        margin-bottom: 30px;
    }

    .btn-add-large {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 15px 30px;
        background: linear-gradient(135deg, #2d8b73 0%, #48c9b0 100%);
        color: white;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 16px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(45, 139, 115, 0.3);
    }

    .btn-add-large:hover {
        background: linear-gradient(135deg, #1e5f4f 0%, #2d8b73 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 95, 79, 0.4);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header-with-action {
            flex-direction: column;
            align-items: flex-start;
        }

        .btn-add {
            width: 100%;
            justify-content: center;
        }

        .genres-grid {
            grid-template-columns: 1fr;
        }

        .stat-box {
            flex-direction: column;
            text-align: center;
        }

        .stat-icon {
            width: 70px;
            height: 70px;
            font-size: 40px;
        }

        .stat-number {
            font-size: 32px;
        }
    }

    @media (max-width: 480px) {
        .genre-card {
            padding: 20px;
        }

        .genre-icon {
            width: 60px;
            height: 60px;
            font-size: 40px;
        }

        .genre-name {
            font-size: 18px;
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