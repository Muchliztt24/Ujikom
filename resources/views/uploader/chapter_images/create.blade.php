@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Upload Gambar Chapter</h1>
        <p>{{ $chapter->work->title }} • Chapter {{ $chapter->chapter_number }}</p>
    </div>

    <div class="content-body">
        <form action="{{ route('chapters.images.store', $chapter) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="admin-form-shell">
                <div class="admin-surface admin-form-card">
                    <div class="admin-form-title">Upload File</div>
                    <div class="admin-field">
                        <label class="admin-label" for="images">Pilih Gambar</label>
                        <input id="images" name="images[]" type="file" class="admin-input" accept="image/*" multiple required>
                        @error('images')<div class="admin-error">{{ $message }}</div>@enderror
                        @error('images.*')<div class="admin-error">{{ $message }}</div>@enderror
                        <div class="admin-help">Bisa upload banyak file sekaligus. Urutan page akan mengikuti urutan upload.</div>
                    </div>
                    <div class="admin-form-actions">
                        <button type="submit" class="admin-btn primary">Upload Gambar</button>
                        <a href="{{ route('chapters.images.index', $chapter) }}" class="admin-btn secondary">Batal</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection


