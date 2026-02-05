@extends('layouts.admin')

@section('content')
<div class="content-header">
    <h1>Edit Role Pengguna</h1>
    <p>Ubah role untuk pengguna: <strong>{{ $user->name }}</strong></p>
</div>

<div class="content-body">
    <div class="form-container">
        <!-- User Info Card -->
        <div class="user-info-card">
            <div class="user-avatar-large">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="user-details">
                <h2>{{ $user->name }}</h2>
                <p class="user-email">{{ $user->email }}</p>
                <div class="user-meta">
                    <span>📅 Terdaftar: {{ $user->created_at->format('d F Y') }}</span>
                    <span>•</span>
                    <span>🔑 Role Saat Ini: 
                        <span class="badge badge-{{ $user->role?->name === 'admin' ? 'admin' : ($user->role?->name === 'uploader' ? 'uploader' : 'user') }}">
                            {{ ucfirst($user->role?->name ?? 'User') }}
                        </span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="edit-form-card">
            <h3 class="form-title">Ubah Role</h3>
            
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="role_id" class="form-label">
                        Pilih Role Baru
                        <span class="required">*</span>
                    </label>
                    
                    <div class="role-options">
                        @foreach($roles as $role)
                            <label class="role-option {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}">
                                <input 
                                    type="radio" 
                                    name="role_id" 
                                    value="{{ $role->id }}" 
                                    {{ old('role_id', $user->role_id) == $role->id ? 'checked' : '' }}
                                    required
                                >
                                <div class="role-card">
                                    <div class="role-icon role-icon-{{ $role->name }}">
                                        @if($role->name === 'admin')
                                            👑
                                        @elseif($role->name === 'uploader')
                                            📤
                                        @else
                                            👤
                                        @endif
                                    </div>
                                    <div class="role-info">
                                        <h4>{{ ucfirst($role->name) }}</h4>
                                        <p>
                                            @if($role->name === 'admin')
                                                Akses penuh ke semua fitur sistem
                                            @elseif($role->name === 'uploader')
                                                Dapat mengelola dan upload works
                                            @else
                                                Akses terbatas sebagai pengguna biasa
                                            @endif
                                        </p>
                                    </div>
                                    <div class="role-check">✓</div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    @error('role_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        ← Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        💾 Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Form Container */
    .form-container {
        max-width: 800px;
    }

    /* User Info Card */
    .user-info-card {
        background: linear-gradient(135deg, #1e5f4f 0%, #2d8b73 100%);
        padding: 30px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 25px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(30, 95, 79, 0.2);
    }

    .user-avatar-large {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #48c9b0 0%, #5fd4bd 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #1e5f4f;
        font-size: 32px;
        box-shadow: 0 4px 15px rgba(72, 201, 176, 0.4);
        flex-shrink: 0;
    }

    .user-details h2 {
        color: white;
        font-size: 24px;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .user-email {
        color: #b8e6d5;
        font-size: 14px;
        margin-bottom: 12px;
    }

    .user-meta {
        display: flex;
        align-items: center;
        gap: 10px;
        color: white;
        font-size: 13px;
    }

    .user-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Edit Form Card */
    .edit-form-card {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .form-title {
        color: #1e5f4f;
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e0e0e0;
    }

    /* Form Group */
    .form-group {
        margin-bottom: 30px;
    }

    .form-label {
        display: block;
        color: #333;
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .required {
        color: #e74c3c;
    }

    /* Role Options */
    .role-options {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .role-option {
        cursor: pointer;
        position: relative;
    }

    .role-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .role-card {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 20px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        transition: all 0.3s ease;
        background: white;
        position: relative;
    }

    .role-option:hover .role-card {
        border-color: #48c9b0;
        background: #f8fffe;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(72, 201, 176, 0.15);
    }

    .role-option.selected .role-card,
    .role-option input[type="radio"]:checked + .role-card {
        border-color: #2d8b73;
        background: linear-gradient(135deg, #f0fdf9 0%, #e6f9f3 100%);
        box-shadow: 0 4px 15px rgba(45, 139, 115, 0.2);
    }

    .role-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        flex-shrink: 0;
    }

    .role-icon-admin {
        background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%);
    }

    .role-icon-uploader {
        background: linear-gradient(135deg, #a29bfe 0%, #6c5ce7 100%);
    }

    .role-icon-user {
        background: linear-gradient(135deg, #dfe6e9 0%, #b2bec3 100%);
    }

    .role-info {
        flex: 1;
    }

    .role-info h4 {
        color: #1e5f4f;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .role-info p {
        color: #6c757d;
        font-size: 13px;
        line-height: 1.5;
    }

    .role-check {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #2d8b73;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: bold;
        opacity: 0;
        transform: scale(0);
        transition: all 0.3s ease;
    }

    .role-option.selected .role-check,
    .role-option input[type="radio"]:checked ~ .role-card .role-check {
        opacity: 1;
        transform: scale(1);
    }

    /* Badge Styles */
    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-admin {
        background-color: #ffeaa7;
        color: #d63031;
    }

    .badge-uploader {
        background-color: #a29bfe;
        color: #6c5ce7;
    }

    .badge-user {
        background-color: #dfe6e9;
        color: #2d3436;
    }

    /* Error Message */
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
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 14px;
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

    /* Responsive */
    @media (max-width: 768px) {
        .user-info-card {
            flex-direction: column;
            text-align: center;
            padding: 25px 20px;
        }

        .user-avatar-large {
            width: 70px;
            height: 70px;
            font-size: 28px;
        }

        .user-meta {
            flex-direction: column;
            gap: 8px;
        }

        .edit-form-card {
            padding: 20px;
        }

        .role-card {
            flex-direction: column;
            text-align: center;
            padding: 20px 15px;
        }

        .role-icon {
            width: 50px;
            height: 50px;
            font-size: 24px;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .btn {
            width: 100%;
        }
    }
</style>

<script>
    // Handle role option selection
    document.querySelectorAll('.role-option').forEach(option => {
        option.addEventListener('click', function() {
            // Remove selected class from all options
            document.querySelectorAll('.role-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            
            // Add selected class to clicked option
            this.classList.add('selected');
            
            // Check the radio button
            this.querySelector('input[type="radio"]').checked = true;
        });
    });
</script>
@endsection