@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Edit Gambar Chapter</h1>
        <p>{{ $chapter->work->title }} • Chapter {{ $chapter->chapter_number }} • Page {{ $image->page_number }}</p>
    </div>

    <div class="content-body">
        <div class="admin-form-shell">
            <div class="admin-surface admin-form-card">
                <div class="admin-form-title">Preview Saat Ini</div>
                <img src="{{ asset('storage/' . $image->image_url) }}" style="max-width:320px; width:100%; height:auto; border-radius:12px; display:block;">
            </div>
            <div class="admin-surface admin-form-card">
                <div class="admin-form-title">Update Page Number</div>
                <form action="{{ route('chapters.images.update', [$chapter, $image]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="admin-field">
                        <label class="admin-label" for="page_number">Nomor Halaman</label>
                        <input id="page_number" name="page_number" type="number" min="1" class="admin-input" value="{{ old('page_number', $image->page_number) }}" required>
                        @error('page_number')<div class="admin-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="admin-form-actions">
                        <button type="submit" class="admin-btn primary">Update Nomor Halaman</button>
                        <a href="{{ route('chapters.images.index', $chapter) }}" class="admin-btn secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


