@extends('layouts.admin')

@section('content')
<div class="content-header">
    <div class="header-with-back">
        <a href="{{ route('admin.genres.index') }}" class="back-button">
            ← Kembali
        </a>
        <div>
            <h1>Tambah Genre Baru</h1>
            <p>Buat genre baru untuk mengkategorikan karya</p>
        </div>
    </div>
</div>

<div class="content-body">
    <div class="form-container">
        <!-- Info Card -->
        <div class="info-card">
            <div class="info-icon">💡</div>
            <div class="info-content">
                <h3>Tips Membuat Genre</h3>
                <ul>
                    <li>Gunakan nama yang jelas dan mudah dipahami</li>
                    <li>Hindari duplikasi dengan genre yang sudah ada</li>
                    <li>Gunakan kapitalisasi yang konsisten</li>
                </ul>
            </div>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <div class="form-header">
                <div class="form-icon">🎭</div>
                <h2>Form Genre Baru</h2>
            </div>

            <form action="{{ route('admin.genres.store') }}" method="POST">
                @csrf

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
                        value="{{ old('name') }}"
                        placeholder="Contoh: Fantasy, Romance, Action, dll."
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
                        💾 Simpan Genre
                    </button>
                </div>
            </form>
        </div>

        <!-- Preview Card -->
        <div class="preview-card">
            <h3>Preview Genre</h3>
            <p class="preview-description">Ini adalah tampilan genre Anda nanti:</p>
            <div class="genre-preview">
                <div class="preview-icon">🎭</div>
                <div class="preview-content">
                    <h4 id="preview-name">Nama Genre</h4>
                    <span class="preview-meta">📚 0 Karya</span>
                </div>
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

    /* Info Card */
    .info-card {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        padding: 20px;
        border-radius: 12px;
        display: flex;
        gap: 20px;
        border: 2px solid #ffc107;
    }

    .info-icon {
        font-size: 40px;
        flex-shrink: 0;
    }

    .info-content h3 {
        color: #856404;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .info-content ul {
        list-style: none;
        padding: 0;
        margin: 0;
        color: #856404;
    }

    .info-content li {
        padding: 5px 0;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-content li::before {
        content: "✓";
        color: #28a745;
        font-weight: bold;
        flex-shrink: 0;
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

    .genre-preview {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        border: 2px dashed #d4edea;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .preview-icon {
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

    .preview-content h4 {
        color: #1e5f4f;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .preview-meta {
        font-size: 12px;
        color: #6c757d;
        font-weight: 600;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header-with-back {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
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

        .info-card {
            flex-direction: column;
        }
    }
</style>

<script>
    // Live preview of genre name
    const nameInput = document.getElementById('name');
    const previewName = document.getElementById('preview-name');

    nameInput.addEventListener('input', function() {
        if (this.value.trim()) {
            previewName.textContent = this.value;
        } else {
            previewName.textContent = 'Nama Genre';
        }
    });
</script>
@endsection