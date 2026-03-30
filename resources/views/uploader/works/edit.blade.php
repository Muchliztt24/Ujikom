@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Edit Karya</h1>
        <p>Perbarui informasi untuk <strong>{{ $work->title }}</strong>.</p>
    </div>

    <div class="content-body">
        <form action="{{ route('works.update', $work) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="admin-form-shell">
                <div class="admin-surface admin-form-card">
                    <div class="admin-form-title">Informasi Dasar</div>
                    <div class="admin-field">
                        <label class="admin-label" for="title">Judul Karya</label>
                        <input id="title" name="title" type="text" class="admin-input" value="{{ old('title', $work->title) }}" required>
                        @error('title')<div class="admin-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="admin-field">
                        <label class="admin-label" for="description">Deskripsi</label>
                        <textarea id="description" name="description" class="admin-textarea">{{ old('description', $work->description) }}</textarea>
                        @error('description')<div class="admin-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="admin-field">
                        <label class="admin-label" for="type">Tipe Karya</label>
                        <select id="type" name="type" class="admin-select" required>
                            <option value="comic" {{ old('type', $work->type) == 'comic' ? 'selected' : '' }}>Comic</option>
                            <option value="novel" {{ old('type', $work->type) == 'novel' ? 'selected' : '' }}>Novel</option>
                        </select>
                    </div>
                </div>

                <div class="admin-surface admin-form-card">
                    <div class="admin-form-title">Genre dan Cover</div>
                    <div class="admin-field">
                        <label class="admin-label">Genre</label>
                        @php $selectedGenres = old('genre_ids', $work->genres->pluck('id')->toArray()); @endphp
                        <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap:10px;">
                            @foreach($genres as $genre)
                                <label class="admin-list-item" style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                                    <input type="checkbox" name="genre_ids[]" value="{{ $genre->id }}" {{ in_array($genre->id, $selectedGenres) ? 'checked' : '' }}>
                                    <span>{{ $genre->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @if($work->cover)
                        <div class="admin-field">
                            <label class="admin-label">Cover Saat Ini</label>
                            <img src="{{ asset('storage/' . $work->cover) }}" style="width:120px; height:170px; object-fit:cover; border-radius:10px; display:block;">
                        </div>
                    @endif
                    <div class="admin-field">
                        <label class="admin-label" for="cover">Ganti Cover</label>
                        <input id="cover" name="cover" type="file" class="admin-input" accept="image/*">
                        @error('cover')<div class="admin-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="admin-form-actions">
                        <button type="submit" class="admin-btn primary">Update Karya</button>
                        <a href="{{ route('works.index') }}" class="admin-btn secondary">Batal</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
