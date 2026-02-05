@extends('layouts.admin')

@section('content')
<div class="content-header">
    <div class="header-with-back">
        <a href="{{ route('admin.genres.index') }}" class="back-button">
            ← Kembali
        </a>
        <div>
            <h1>Edit Genre</h1>
            <p>Ubah informasi genre yang sudah ada</p>
        </div>
    </div>
</div>

<div class="content-body">
    <div class="form-container">
        <!-- Current Genre Info -->
        <div class="current-genre-card">
            <div class="current-genre-header">
                <div class="genre-icon-large">🎭</div>
                <div class="genre-info">
                    <span class="genre-label">Genre Saat Ini</span>
                    <h2 class="current-genre-name">{{ $genre->name }}</h2>
                    <div class="genre-stats">
                        <span class="stat-item">📚 {{ $genre->works_count ?? 0 }} Karya</span>
                        <span class="stat-divider">•</span>
                        <span class="stat-item">📅 Dibuat {{ $genre->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <div class="form-header">
                <div class="form-icon">✏️</div>
                <h2>Edit Informasi Genre</h2>
            </div>

            <form action="{{ route('admin.genres.update', $genre) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name" class="form-label">
                        Nama Genre
                        <span class="required">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        class="form-input @error('name') is-invalid @enderror"
                        value="{{ old('name', $genre->name) }}"
                        placeholder="Masukkan nama genre"
                        required
                        autofocus
                    >
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <div class="form-hint">
                        💡 Nama genre harus unik dan belum pernah digunakan
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.genres.index') }}" class="btn btn-secondary">
                        ✕ Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        💾 Update Genre
                    </button>
                </div>
            </form>
        </div>

        <!-- Warning Card (if genre has works) -->
        @if(isset($genre->works_count) && $genre->works_count > 0)
            <div class="warning-card">
                <div class="warning-icon">⚠️</div>
                <div class="warning-content">
                    <h3>Perhatian!</h3>
                    <p>Genre ini sedang digunakan oleh <strong>{{ $genre->works_count }} karya</strong>. Perubahan nama genre akan mempengaruhi semua karya yang menggunakan genre ini.</p>
                </div>
            </div>
        @endif

        <!-- Preview Card -->
        <div class="preview-card">
            <h3>Preview Perubahan</h3>
            <p class="preview-description">Tampilan genre setelah diupdate:</p>
            
            <div class="preview-comparison">
                <!-- Before -->
                <div class="preview-item">
                    <span class="preview-badge preview-badge-before">Sebelum</span>
                    <div class="genre-preview">
                        <div class="preview-icon">🎭</div>
                        <div class="preview-content">
                            <h4>{{ $genre->name }}</h4>
                            <span class="preview-meta">📚 {{ $genre->works_count ?? 0 }} Karya</span>
                        </div>
                    </div>
                </div>

                <div class="arrow-divider">→</div>

                <!-- After -->
                <div class="preview-item">
                    <span class="preview-badge preview-badge-after">Sesudah</span>
                    <div class="genre-preview genre-preview-after">
                        <div class="preview-icon">🎭</div>
                        <div class="preview-content">
                            <h4 id="preview-name">{{ $genre->name }}</h4>
                            <span class="preview-meta">📚 {{ $genre->works_count ?? 0 }} Karya</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Section -->
        <div class="danger-zone">
            <div class="danger-header">
                <h3>⚠️ Zona Berbahaya</h3>
                <p>Tindakan ini tidak dapat dibatalkan!</p>
            </div>
            <div class="danger-content">
                <div class="danger-info">
                    <strong>Hapus Genre</strong>
                    <p>Menghapus genre akan menghapusnya dari semua karya yang menggunakannya</p>
                </div>
                <form action="{{ route('admin.genres.destroy', $genre) }}" method="POST" 
                    onsubmit="return confirm('Apakah Anda YAKIN ingin menghapus genre ini? Tindakan ini tidak dapat dibatalkan!')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        🗑️ Hapus Genre
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
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

    /* Form Container */
    .form-container {
        max-width: 800px;
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    /* Current Genre Card */
    .current-genre-card {
        background: linear-gradient(135deg, #1e5f4f 0%, #2d8b73 100%);
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(30, 95, 79, 0.2);
    }

    .current-genre-header {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .genre-icon-large {
        font-size: 64px;
        width: 90px;
        height: 90px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .genre-info {
        flex: 1;
    }

    .genre-label {
        color: #b8e6d5;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
    }

    .current-genre-name {
        color: white;
        font-size: 32px;
        font-weight: 700;
        margin: 8px 0;
        line-height: 1.2;
    }

    .genre-stats {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #b8e6d5;
        font-size: 14px;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .stat-divider {
        color: rgba(255, 255, 255, 0.3);
    }

    /* Form Card */
    .form-card {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .form-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .form-icon {
        font-size: 40px;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #e8f5f3 0%, #d4edea 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-header h2 {
        color: #1e5f4f;
        font-size: 24px;
        font-weight: 700;
    }

    /* Form Group */
    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        display: block;
        color: #333;
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .required {
        color: #e74c3c;
    }

    .form-input {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 15px;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .form-input:focus {
        outline: none;
        border-color: #48c9b0;
        box-shadow: 0 0 0 3px rgba(72, 201, 176, 0.1);
    }

    .form-input.is-invalid {
        border-color: #e74c3c;
    }

    .form-input.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
    }

    .error-message {
        color: #e74c3c;
        font-size: 13px;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .error-message::before {
        content: "⚠️";
    }

    .form-hint {
        color: #6c757d;
        font-size: 13px;
        margin-top: 8px;
        font-style: italic;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        padding-top: 20px;
        border-top: 2px solid #f0f0f0;
    }

    /* Button Styles */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 28px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 15px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2d8b73 0%, #48c9b0 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(45, 139, 115, 0.3);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1e5f4f 0%, #2d8b73 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 95, 79, 0.4);
    }

    .btn-secondary {
        background: #f0f0f0;
        color: #333;
    }

    .btn-secondary:hover {
        background: #e0e0e0;
        transform: translateY(-2px);
    }

    .btn-danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    }

    .btn-danger:hover {
        background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
    }

    /* Warning Card */
    .warning-card {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        padding: 20px;
        border-radius: 12px;
        display: flex;
        gap: 20px;
        border: 2px solid #ffc107;
    }

    .warning-icon {
        font-size: 40px;
        flex-shrink: 0;
    }

    .warning-content h3 {
        color: #856404;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .warning-content p {
        color: #856404;
        font-size: 14px;
        line-height: 1.6;
    }

    /* Preview Card */
    .preview-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .preview-card h3 {
        color: #1e5f4f;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .preview-description {
        color: #6c757d;
        font-size: 14px;
        margin-bottom: 20px;
    }

    .preview-comparison {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        gap: 20px;
        align-items: center;
    }

    .preview-item {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .preview-badge {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 6px 12px;
        border-radius: 20px;
        text-align: center;
    }

    .preview-badge-before {
        background: #e2e3e5;
        color: #383d41;
    }

    .preview-badge-after {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
    }

    .arrow-divider {
        font-size: 32px;
        color: #48c9b0;
        font-weight: bold;
    }

    .genre-preview {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        border: 2px solid #e0e0e0;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .genre-preview-after {
        border-color: #48c9b0;
        background: linear-gradient(135deg, #f0fdf9 0%, #e6f9f3 100%);
    }

    .preview-icon {
        font-size: 40px;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #e8f5f3 0%, #d4edea 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .preview-content h4 {
        color: #1e5f4f;
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .preview-meta {
        font-size: 12px;
        color: #6c757d;
        font-weight: 600;
    }

    /* Danger Zone */
    .danger-zone {
        background: white;
        border: 2px solid #dc3545;
        border-radius: 12px;
        overflow: hidden;
    }

    .danger-header {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        padding: 20px;
        color: white;
    }

    .danger-header h3 {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .danger-header p {
        font-size: 13px;
        opacity: 0.9;
    }

    .danger-content {
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .danger-info strong {
        display: block;
        color: #333;
        font-size: 16px;
        margin-bottom: 5px;
    }

    .danger-info p {
        color: #6c757d;
        font-size: 14px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header-with-back {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .current-genre-header {
            flex-direction: column;
            text-align: center;
        }

        .current-genre-name {
            font-size: 24px;
        }

        .genre-stats {
            flex-direction: column;
            gap: 5px;
        }

        .stat-divider {
            display: none;
        }

        .form-card {
            padding: 20px;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .btn {
            width: 100%;
        }

        .preview-comparison {
            grid-template-columns: 1fr;
        }

        .arrow-divider {
            transform: rotate(90deg);
            justify-self: center;
        }

        .danger-content {
            flex-direction: column;
            align-items: stretch;
        }

        .warning-card {
            flex-direction: column;
        }
    }
</style>

<script>
    // Live preview of genre name
    const nameInput = document.getElementById('name');
    const previewName = document.getElementById('preview-name');
    const originalName = "{{ $genre->name }}";

    nameInput.addEventListener('input', function() {
        if (this.value.trim()) {
            previewName.textContent = this.value;
        } else {
            previewName.textContent = originalName;
        }
    });
</script>
@endsection