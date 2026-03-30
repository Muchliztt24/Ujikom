@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Edit Genre</h1>
        <p>Perbarui informasi genre <strong>{{ $genre->name }}</strong>.</p>
    </div>

    <div class="content-body">
        <div class="admin-form-shell">
            <div class="admin-surface admin-form-card">
                <div class="admin-form-title">Informasi Genre</div>
                <div class="admin-muted" style="margin-bottom: 16px;">Dipakai oleh {{ $genre->works_count ?? 0 }} karya</div>
                <form action="{{ route('admin.genres.update', $genre) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="admin-field">
                        <label for="name" class="admin-label">Nama Genre</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $genre->name) }}" class="admin-input" required autofocus>
                        @error('name')<div class="admin-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="admin-form-actions">
                        <button type="submit" class="admin-btn primary">Update Genre</button>
                        <a href="{{ route('admin.genres.index') }}" class="admin-btn secondary">Batal</a>
                    </div>
                </form>
            </div>
            <div class="admin-surface admin-form-card">
                <div class="admin-form-title">Aksi Hapus</div>
                <div class="admin-help" style="margin-bottom: 14px;">Menghapus genre akan melepas genre ini dari karya yang memakainya.</div>
                <form action="{{ route('admin.genres.destroy', $genre) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-btn danger" onclick="return confirm('Hapus genre ini?')">Hapus Genre</button>
                </form>
            </div>
        </div>
    </div>
@endsection
