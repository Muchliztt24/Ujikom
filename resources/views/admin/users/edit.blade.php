@extends('layouts.admin')

@section('content')
<div class="content-header">
    <h1>Edit Role Pengguna</h1>
    <p>Perbarui role untuk <strong>{{ $user->name }}</strong>.</p>
</div>

<div class="content-body">
    <div class="admin-form-shell">
        <div class="admin-surface admin-form-card">
            <div class="admin-form-title">Informasi Akun</div>
            <div class="admin-list">
                <div class="admin-list-item"><strong>Nama:</strong> {{ $user->name }}</div>
                <div class="admin-list-item"><strong>Email:</strong> {{ $user->email }}</div>
                <div class="admin-list-item"><strong>Role saat ini:</strong> {{ ucfirst($user->role?->name ?? 'User') }}</div>
            </div>
        </div>

        <div class="admin-surface admin-form-card">
            <div class="admin-form-title">Pilih Role Baru</div>
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="admin-field">
                    <label for="role_id" class="admin-label">Role</label>
                    <select id="role_id" name="role_id" class="admin-select" required>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                    @error('role_id')<div class="admin-error">{{ $message }}</div>@enderror
                </div>
                <div class="admin-form-actions">
                    <button type="submit" class="admin-btn primary">Simpan Perubahan</button>
                    <a href="{{ route('admin.users.index') }}" class="admin-btn secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
