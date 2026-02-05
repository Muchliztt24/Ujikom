@extends('layouts.admin')

@section('content')
<div class="content-header">
    <div class="header-with-back">
        <a href="{{ route('admin.chapters.index') }}" class="back-button">
            ← Kembali ke List
        </a>
        <div>
            <h1>Review Chapter - Admin</h1>
            <p>Moderasi konten chapter novel/komik</p>
        </div>
    </div>
</div>

<div class="content-body">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Quick Action Bar -->
    <div class="quick-action-bar">
        <div class="chapter-info">
            <span class="chapter-badge">Chapter {{ $chapter->order }}</span>
            <div class="chapter-meta">
                <span class="type-badge type-{{ $chapter->work->type }}">
                    {{ $chapter->work->type === 'novel' ? '📖 Novel' : '🎨 Komik' }}
                </span>
                <span class="views-badge">👁️ {{ number_format($chapter->views ?? 0) }} views</span>
            </div>
        </div>
        
        <div class="quick-actions">
            <a href="{{ route('admin.works.show', $chapter->work) }}" class="btn-action-medium btn-work">
                📚 Lihat Karya
            </a>
            <form action="{{ route('admin.chapters.destroy', $chapter) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-action-medium btn-takedown" 
                    onclick="return confirm('🗑️ HAPUS CHAPTER INI?\n\nChapter: {{ $chapter->title }}\nKarya: {{ $chapter->work->title }}\n\n⚠️ PERINGATAN: Tindakan TIDAK DAPAT DIBATALKAN!\n\nApakah Anda yakin?')">
                    🗑️ Takedown Chapter
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="chapter-detail-container">
        <!-- Sidebar Info -->
        <div class="chapter-sidebar">
            <!-- Chapter Info Card -->
            <div class="info-card">
                <h3>📋 Informasi Chapter</h3>
                <div class="info-list">
                    <div class="info-item">
                        <strong>Nomor Chapter:</strong>
                        <span>{{ $chapter->order }}</span>
                    </div>
                    <div class="info-item">
                        <strong>Judul Chapter:</strong>
                        <span>{{ $chapter->title }}</span>
                    </div>
                    <div class="info-item">
                        <strong>Type:</strong>
                        <span>{{ $chapter->work->type === 'novel' ? 'Novel' : 'Komik' }}</span>
                    </div>
                    @if($chapter->work->type === 'novel' && $chapter->content)
                        <div class="info-item">
                            <strong>Jumlah Kata:</strong>
                            <span>{{ number_format(str_word_count(strip_tags($chapter->content))) }} kata</span>
                        </div>
                        <div class="info-item">
                            <strong>Jumlah Karakter:</strong>
                            <span>{{ number_format(strlen(strip_tags($chapter->content))) }} karakter</span>
                        </div>
                    @elseif($chapter->work->type === 'comic' && isset($chapter->images))
                        <div class="info-item">
                            <strong>Jumlah Halaman:</strong>
                            <span>{{ $chapter->images->count() }} halaman</span>
                        </div>
                    @endif
                    <div class="info-item">
                        <strong>Views:</strong>
                        <span>{{ number_format($chapter->views ?? 0) }}</span>
                    </div>
                    <div class="info-item">
                        <strong>Dibuat:</strong>
                        <span>{{ $chapter->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="info-item">
                        <strong>Update:</strong>
                        <span>{{ $chapter->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Work Info Card -->
            <div class="work-info-card">
                <h3>📚 Informasi Karya</h3>
                <div class="work-details">
                    <h4 class="work-title">{{ $chapter->work->title }}</h4>
                    <div class="work-meta">
                        <span class="status-badge status-{{ $chapter->work->status }}">
                            {{ ucfirst($chapter->work->status) }}
                        </span>
                        @if($chapter->work->genres->count() > 0)
                            <div class="genres-mini">
                                @foreach($chapter->work->genres->take(3) as $genre)
                                    <span class="genre-mini">{{ $genre->name }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Author Info Card -->
            <div class="author-info-card">
                <h3>👤 Author</h3>
                <div class="author-profile">
                    <div class="author-avatar-large">
                        {{ strtoupper(substr($chapter->work->user->name, 0, 1)) }}
                    </div>
                    <div class="author-details">
                        <h4>{{ $chapter->work->user->name }}</h4>
                        <p>{{ $chapter->work->user->email }}</p>
                        <span class="author-role">{{ ucfirst($chapter->work->user->role?->name ?? 'User') }}</span>
                    </div>
                </div>
            </div>

            <!-- Admin Actions Card -->
            <div class="admin-actions-card">
                <h3>⚙️ Admin Actions</h3>
                <div class="action-list">
                    <a href="{{ route('admin.works.show', $chapter->work) }}" class="action-link">
                        📚 Lihat Semua Chapter
                    </a>
                    <a href="{{ route('admin.chapters.index') }}" class="action-link">
                        📋 List Moderasi Chapter
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="chapter-main-content">
            <!-- Chapter Title -->
            <div class="chapter-header">
                <div class="chapter-number-large">Chapter {{ $chapter->order }}</div>
                <h1 class="chapter-title-large">{{ $chapter->title }}</h1>
                <div class="chapter-timestamp">
                    📅 Dipublish {{ $chapter->created_at->format('d F Y') }} • 
                    🕐 {{ $chapter->created_at->format('H:i') }}
                    @if($chapter->updated_at != $chapter->created_at)
                        • ✏️ Edited {{ $chapter->updated_at->diffForHumans() }}
                    @endif
                </div>
            </div>

            <!-- Content Display -->
            <div class="content-section">
                @if($chapter->work->type === 'novel')
                    <!-- Novel Content -->
                    <div class="novel-content-container">
                        <div class="content-header">
                            <h3>📖 Konten Novel</h3>
                            <div class="reading-stats">
                                <span>📝 {{ number_format(str_word_count(strip_tags($chapter->text_content ?? ''))) }} kata</span>
                                <span>•</span>
                                <span>⏱️ ~{{ ceil(str_word_count(strip_tags($chapter->text_content ?? '')) / 200) }} menit baca</span>
                            </div>
                        </div>
                        
                        <div class="novel-content">
                            @if($chapter->text_content)
                                {!! nl2br(e($chapter->text_content)) !!}
                            @else
                                <div class="no-content">
                                    <p>⚠️ Chapter ini belum memiliki konten.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                @elseif($chapter->work->type === 'comic')
                    <!-- Comic Content (Images) -->
                    <div class="comic-content-container">
                        <div class="content-header">
                            <h3>🎨 Halaman Komik</h3>
                            <div class="reading-stats">
                                <span>🖼️ {{ $chapter->images->count() }} halaman</span>
                            </div>
                        </div>

                        @if($chapter->images && $chapter->images->count() > 0)
                            <div class="comic-pages">
                                @foreach($chapter->images as $image)
                                    <div class="comic-page">
                                        <div class="page-number">Halaman {{ $image->order }}</div>
                                        <div class="page-image">
                                            <img src="{{ Storage::url($image->image_path) }}" 
                                                 alt="Halaman {{ $image->order }}" 
                                                 loading="lazy">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="no-content">
                                <p>⚠️ Chapter komik ini belum memiliki halaman.</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Moderation Checklist -->
            <div class="moderation-section">
                <h3 class="section-title">🔍 Checklist Moderasi</h3>
                <div class="checklist">
                    <label class="checklist-item">
                        <input type="checkbox">
                        <span>Konten sesuai dengan rating yang ditetapkan</span>
                    </label>
                    <label class="checklist-item">
                        <input type="checkbox">
                        <span>Tidak mengandung SARA atau hate speech</span>
                    </label>
                    <label class="checklist-item">
                        <input type="checkbox">
                        <span>Tidak melanggar hak cipta pihak lain</span>
                    </label>
                    <label class="checklist-item">
                        <input type="checkbox">
                        <span>Kualitas konten memadai (grammar/artwork)</span>
                    </label>
                    <label class="checklist-item">
                        <input type="checkbox">
                        <span>Tidak mengandung spam atau iklan berlebihan</span>
                    </label>
                    @if($chapter->work->type === 'comic')
                        <label class="checklist-item">
                            <input type="checkbox">
                            <span>Gambar/halaman dapat dimuat dengan baik</span>
                        </label>
                        <label class="checklist-item">
                            <input type="checkbox">
                            <span>Kualitas gambar memadai dan terbaca</span>
                        </label>
                    @else
                        <label class="checklist-item">
                            <input type="checkbox" {{ $chapter->text_content ? 'checked' : '' }} disabled>
                            <span>Chapter memiliki konten</span>
                        </label>
                    @endif
                </div>
            </div>

            <!-- Admin Notes -->
            <div class="admin-notes-section">
                <h3 class="section-title">📝 Catatan Admin (Internal)</h3>
                <textarea class="admin-notes-textarea" placeholder="Tulis catatan moderasi untuk chapter ini...
                
Catatan ini hanya untuk internal admin dan tidak terlihat oleh user."></textarea>
                <div class="notes-actions">
                    <button class="btn-save-notes">💾 Simpan Catatan</button>
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

    /* Quick Action Bar */
    .quick-action-bar {
        background: white;
        padding: 20px 25px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 30px;
        border: 2px solid #e0e0e0;
    }

    .chapter-info {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .chapter-badge {
        background: linear-gradient(135deg, #2d8b73 0%, #48c9b0 100%);
        color: white;
        padding: 10px 20px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 14px;
        display: inline-block;
        width: fit-content;
    }

    .chapter-meta {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .type-badge {
        padding: 6px 14px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
    }

    .type-novel {
        background: rgba(139, 69, 19, 0.1);
        color: #8b4513;
    }

    .type-comic {
        background: rgba(156, 39, 176, 0.1);
        color: #9c27b0;
    }

    .views-badge {
        font-size: 13px;
        color: #6c757d;
        font-weight: 600;
    }

    .quick-actions {
        display: flex;
        gap: 10px;
    }

    .btn-action-medium {
        padding: 12px 20px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 700;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-work {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(23, 162, 184, 0.4);
    }

    .btn-work:hover {
        background: linear-gradient(135deg, #138496 0%, #117a8b 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(23, 162, 184, 0.5);
    }

    .btn-takedown {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
    }

    .btn-takedown:hover {
        background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 53, 69, 0.5);
    }

    /* Main Container */
    .chapter-detail-container {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 30px;
    }

    /* Sidebar */
    .chapter-sidebar {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .info-card,
    .work-info-card,
    .author-info-card,
    .admin-actions-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .info-card h3,
    .work-info-card h3,
    .author-info-card h3,
    .admin-actions-card h3 {
        color: #1e5f4f;
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 6px;
    }

    .info-item strong {
        font-size: 11px;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-item span {
        font-size: 14px;
        color: #1e5f4f;
        font-weight: 600;
    }

    /* Work Info */
    .work-details {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .work-title {
        font-size: 16px;
        color: #1e5f4f;
        font-weight: 700;
        line-height: 1.3;
    }

    .work-meta {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-block;
        width: fit-content;
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

    .genres-mini {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .genre-mini {
        font-size: 10px;
        padding: 4px 10px;
        background: #e8f5f3;
        color: #2d8b73;
        border-radius: 10px;
        font-weight: 600;
    }

    /* Author Profile */
    .author-profile {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .author-avatar-large {
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
        flex-shrink: 0;
    }

    .author-details h4 {
        font-size: 16px;
        color: #1e5f4f;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .author-details p {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 6px;
    }

    .author-role {
        font-size: 11px;
        padding: 4px 10px;
        background: #e8f5f3;
        color: #2d8b73;
        border-radius: 10px;
        font-weight: 600;
        display: inline-block;
    }

    /* Action List */
    .action-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .action-link {
        padding: 12px;
        background: #f8f9fa;
        border-radius: 8px;
        text-decoration: none;
        color: #1e5f4f;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .action-link:hover {
        background: #e8f5f3;
        color: #2d8b73;
        transform: translateX(5px);
    }

    /* Main Content */
    .chapter-main-content {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    /* Chapter Header */
    .chapter-header {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .chapter-number-large {
        background: linear-gradient(135deg, #2d8b73 0%, #48c9b0 100%);
        color: white;
        padding: 8px 20px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 14px;
        display: inline-block;
        margin-bottom: 15px;
    }

    .chapter-title-large {
        color: #1e5f4f;
        font-size: 32px;
        font-weight: 700;
        line-height: 1.3;
        margin-bottom: 15px;
    }

    .chapter-timestamp {
        color: #6c757d;
        font-size: 14px;
    }

    /* Content Section */
    .content-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .content-header {
        background: linear-gradient(135deg, #e8f5f3 0%, #d4edea 100%);
        padding: 20px 25px;
        border-bottom: 2px solid #48c9b0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .content-header h3 {
        color: #1e5f4f;
        font-size: 18px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .reading-stats {
        display: flex;
        gap: 10px;
        font-size: 13px;
        color: #6c757d;
        font-weight: 600;
    }

    /* Novel Content */
    .novel-content-container {
        background: white;
    }

    .novel-content {
        padding: 40px;
        font-size: 16px;
        line-height: 2;
        color: #333;
        font-family: 'Georgia', serif;
    }

    .novel-content p {
        margin-bottom: 1.5em;
    }

    /* Comic Content */
    .comic-content-container {
        background: white;
    }

    .comic-pages {
        padding: 30px;
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .comic-page {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .page-number {
        background: linear-gradient(135deg, #2d8b73 0%, #48c9b0 100%);
        color: white;
        padding: 10px 20px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 14px;
        display: inline-block;
        width: fit-content;
    }

    .page-image {
        background: #f8f9fa;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .page-image img {
        width: 100%;
        height: auto;
        display: block;
    }

    /* No Content */
    .no-content {
        padding: 60px 40px;
        text-align: center;
    }

    .no-content p {
        color: #6c757d;
        font-size: 16px;
        font-weight: 600;
    }

    /* Moderation Section */
    .moderation-section {
        background: linear-gradient(135deg, #f0fdf9 0%, #e6f9f3 100%);
        padding: 25px;
        border-radius: 12px;
        border: 2px solid #48c9b0;
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

    .checklist {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .checklist-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: white;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .checklist-item:hover {
        background: #f8f9fa;
    }

    .checklist-item input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: #28a745;
    }

    .checklist-item span {
        font-size: 14px;
        color: #333;
    }

    /* Admin Notes Section */
    .admin-notes-section {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        padding: 25px;
        border-radius: 12px;
        border: 2px solid #ffc107;
    }

    .admin-notes-textarea {
        width: 100%;
        min-height: 150px;
        padding: 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        resize: vertical;
        margin-bottom: 15px;
    }

    .admin-notes-textarea:focus {
        outline: none;
        border-color: #ffc107;
    }

    .notes-actions {
        display: flex;
        justify-content: flex-end;
    }

    .btn-save-notes {
        padding: 12px 24px;
        background: linear-gradient(135deg, #2d8b73 0%, #48c9b0 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-save-notes:hover {
        background: linear-gradient(135deg, #1e5f4f 0%, #2d8b73 100%);
        transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .chapter-detail-container {
            grid-template-columns: 1fr;
        }

        .chapter-sidebar {
            order: 2;
        }

        .chapter-main-content {
            order: 1;
        }

        .quick-action-bar {
            flex-direction: column;
        }

        .quick-actions {
            width: 100%;
            flex-direction: column;
        }

        .btn-action-medium {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 768px) {
        .header-with-back {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .chapter-title-large {
            font-size: 24px;
        }

        .novel-content {
            padding: 25px 20px;
            font-size: 15px;
        }

        .comic-pages {
            padding: 20px;
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

    // Save notes functionality (placeholder)
    document.querySelector('.btn-save-notes')?.addEventListener('click', function() {
        const notes = document.querySelector('.admin-notes-textarea').value;
        if (notes.trim()) {
            alert('Catatan berhasil disimpan!\n\n(Ini adalah placeholder, implementasi actual perlu backend support)');
        } else {
            alert('Catatan kosong!');
        }
    });
</script>
@endsection