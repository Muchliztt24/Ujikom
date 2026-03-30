@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Tambah Chapter</h1>
        <p>Tambahkan chapter baru untuk <strong>{{ $work->title }}</strong>.</p>
    </div>

    <div class="content-body">
        <form action="{{ route('works.chapters.store', $work) }}" method="POST">
            @csrf
            <div class="admin-form-shell">
                <div class="admin-surface admin-form-card">
                    <div class="admin-form-title">Data Chapter</div>
                    <div class="admin-field">
                        <label class="admin-label" for="chapter_number">Nomor Chapter</label>
                        <input id="chapter_number" name="chapter_number" type="number" min="1" class="admin-input" value="{{ old('chapter_number') }}" required>
                        @error('chapter_number')<div class="admin-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="admin-field">
                        <label class="admin-label" for="title">Judul Chapter</label>
                        <input id="title" name="title" type="text" class="admin-input" value="{{ old('title') }}">
                        @error('title')<div class="admin-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="admin-field">
                        <label class="admin-label" for="text_content">Isi Chapter</label>
                        <textarea id="text_content" name="text_content" class="admin-textarea" placeholder="Isi text untuk novel. Untuk komik, boleh dikosongkan.">{{ old('text_content') }}</textarea>
                        @error('text_content')<div class="admin-error">{{ $message }}</div>@enderror
                        <div class="admin-help">Untuk komik, simpan chapter dulu lalu lanjut upload gambar halaman.</div>
                    </div>
                    <div class="admin-form-actions">
                        <button type="submit" class="admin-btn primary">Simpan Chapter</button>
                        <a href="{{ route('works.chapters.index', $work) }}" class="admin-btn secondary">Batal</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
