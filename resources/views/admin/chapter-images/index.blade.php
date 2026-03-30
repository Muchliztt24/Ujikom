@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Gambar Chapter</h1>
        <p>Daftar semua halaman gambar untuk karya komik.</p>
    </div>

    <div class="content-body">
        @if (session('success'))
            <div style="margin-bottom: 16px; padding: 14px 16px; background: #d4edda; color: #155724; border-radius: 10px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="admin-muted" style="margin-bottom: 20px; font-weight: 600;">
            Total gambar: {{ $images->total() }}
        </div>

        @if ($images->count())
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 18px;">
                @foreach ($images as $image)
                    <div class="admin-surface" style="overflow: hidden;">
                        <img src="{{ asset('storage/' . $image->image_url) }}" alt="Page {{ $image->page_number }}"
                            style="width: 100%; height: 280px; object-fit: cover;">

                        <div style="padding: 16px;">
                            <div style="font-weight: 700; color: #ecf8f5; margin-bottom: 6px;">
                                {{ $image->chapter->work->title }}
                            </div>
                            <div class="admin-muted" style="font-size: 13px; margin-bottom: 4px;">
                                Chapter {{ $image->chapter->chapter_number }} • Page {{ $image->page_number }}
                            </div>
                            @if ($image->chapter->title)
                                <div class="admin-muted" style="font-size: 13px; margin-bottom: 12px;">
                                    {{ $image->chapter->title }}
                                </div>
                            @endif

                            <div class="admin-btn-row">
                                <a href="{{ route('admin.chapter-images.show', $image) }}"
                                    style="flex: 1; text-align: center; padding: 10px 12px; text-decoration: none; background: #17a2b8; color: white; border-radius: 10px; font-weight: 700; font-size: 13px;">
                                    Lihat
                                </a>
                                <form action="{{ route('admin.chapter-images.destroy', $image) }}" method="POST" style="flex: 1;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Hapus gambar ini?')"
                                        style="width: 100%; padding: 10px 12px; border: none; background: #dc3545; color: white; border-radius: 10px; font-weight: 700; cursor: pointer; font-size: 13px;">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="admin-pagination">
                <div class="admin-pagination-meta">
                    Menampilkan {{ $images->firstItem() }}-{{ $images->lastItem() }} dari {{ $images->total() }} gambar
                </div>
                <div class="admin-pagination-links">
                    <a href="{{ $images->previousPageUrl() ?: '#' }}" class="admin-page-link {{ $images->onFirstPage() ? 'disabled' : '' }}">&laquo; Previous</a>
                    <a href="{{ $images->nextPageUrl() ?: '#' }}" class="admin-page-link {{ $images->hasMorePages() ? '' : 'disabled' }}">Next &raquo;</a>
                </div>
            </div>
        @else
            <div class="admin-empty">
                Belum ada gambar chapter.
            </div>
        @endif
    </div>
@endsection
