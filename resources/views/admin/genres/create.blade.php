@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Tambah Genre</h1>
        <p>Buat genre baru untuk membantu pengelompokan karya.</p>
    </div>

    <div class="content-body">
        <div class="admin-form-shell">
            <div class="admin-surface admin-form-card">
                <div class="admin-form-title">Form Genre</div>
                <form action="{{ route('admin.genres.store') }}" method="POST">
                    @csrf
                    <div class="admin-field">
                        <label for="name" class="admin-label">Nama Genre</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" class="admin-input" placeholder="Contoh: Fantasy" required autofocus>
                        @error('name')<div class="admin-error">{{ $message }}</div>@enderror
                        <div class="admin-help">Gunakan nama singkat, jelas, dan tidak duplikat.</div>
                    </div>
                    <div class="admin-form-actions">
                        <button type="submit" class="admin-btn primary">Simpan Genre</button>
                        <a href="{{ route('admin.genres.index') }}" class="admin-btn secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
