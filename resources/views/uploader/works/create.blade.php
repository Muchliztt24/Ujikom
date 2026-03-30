@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Tambah Karya</h1>
        <p>Buat karya baru lalu lengkapi chapter-nya setelah tersimpan.</p>
    </div>

    <div class="content-body">
        <form action="{{ route('works.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="admin-form-shell">
                <div class="admin-surface admin-form-card">
                    <div class="admin-form-title">Informasi Dasar</div>
                    <div class="admin-field">
                        <label class="admin-label" for="title">Judul Karya</label>
                        <input id="title" name="title" type="text" class="admin-input" value="{{ old('title') }}" required>
                        @error('title')<div class="admin-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="admin-field">
                        <label class="admin-label" for="description">Deskripsi</label>
                        <textarea id="description" name="description" class="admin-textarea">{{ old('description') }}</textarea>
                        @error('description')<div class="admin-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="admin-field">
                        <label class="admin-label" for="type">Tipe Karya</label>
                        <select id="type" name="type" class="admin-select" required>
                            <option value="">Pilih tipe</option>
                            <option value="comic" {{ old('type') == 'comic' ? 'selected' : '' }}>Comic</option>
                            <option value="novel" {{ old('type') == 'novel' ? 'selected' : '' }}>Novel</option>
                        </select>
                        @error('type')<div class="admin-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="admin-surface admin-form-card">
                    <div class="admin-form-title">Genre dan Cover</div>
                    <div class="admin-field">
                        <label class="admin-label">Genre</label>
                        <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap:10px;">
                            @foreach($genres as $genre)
                                <label class="admin-list-item" style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                                    <input type="checkbox" name="genre_ids[]" value="{{ $genre->id }}" {{ in_array($genre->id, old('genre_ids', [])) ? 'checked' : '' }}>
                                    <span>{{ $genre->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('genre_ids')<div class="admin-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="admin-field">
                        <label class="admin-label" for="cover">Cover</label>
                        <input id="cover" name="cover" type="file" class="admin-input" accept="image/*">
                        @error('cover')<div class="admin-error">{{ $message }}</div>@enderror
                        <div class="admin-help">Opsional. Format JPG, PNG, atau JPEG.</div>
                    </div>
                    <div class="admin-form-actions">
                        <button type="submit" class="admin-btn primary">Simpan Karya</button>
                        <a href="{{ route('works.index') }}" class="admin-btn secondary">Batal</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
