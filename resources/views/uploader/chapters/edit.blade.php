@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Edit Chapter</h1>
        <p>Perbarui chapter untuk <strong>{{ $work->title }}</strong>.</p>
    </div>

    <div class="content-body">
        <form action="{{ route('works.chapters.update', [$work, $chapter]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="admin-form-shell">
                <div class="admin-surface admin-form-card">
                    <div class="admin-form-title">Data Chapter</div>
                    <div class="admin-field">
                        <label class="admin-label" for="chapter_number">Nomor Chapter</label>
                        <input id="chapter_number" name="chapter_number" type="number" min="1" class="admin-input" value="{{ old('chapter_number', $chapter->chapter_number) }}" required>
                        @error('chapter_number')<div class="admin-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="admin-field">
                        <label class="admin-label" for="title">Judul Chapter</label>
                        <input id="title" name="title" type="text" class="admin-input" value="{{ old('title', $chapter->title) }}">
                    </div>
                    <div class="admin-field">
                        <label class="admin-label" for="text_content">Isi Chapter</label>
                        <textarea id="text_content" name="text_content" class="admin-textarea">{{ old('text_content', $chapter->text_content) }}</textarea>
                    </div>
                    <div class="admin-btn-row" style="margin-bottom: 10px;">
                        <a href="{{ route('chapters.images.index', $chapter) }}" class="admin-btn info">Kelola Gambar Chapter</a>
                    </div>
                    <div class="admin-form-actions">
                        <button type="submit" class="admin-btn primary">Update Chapter</button>
                        <a href="{{ route('works.chapters.index', $work) }}" class="admin-btn secondary">Batal</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
