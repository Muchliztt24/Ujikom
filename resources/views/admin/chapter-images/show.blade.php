@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Detail Gambar Chapter</h1>
        <p>Preview dan informasi halaman komik.</p>
    </div>

    <div class="content-body">
        <a href="{{ route('admin.chapter-images.index') }}"
            style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 18px; text-decoration: none; color: var(--admin-accent); font-weight: 700;">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali</span>
        </a>

        <div style="display: grid; grid-template-columns: minmax(0, 1fr) 340px; gap: 24px;">
            <div style="background: #fff; border: 1px solid #dee2e6; border-radius: 14px; padding: 20px;">
                <img src="{{ asset('storage/' . $chapterImage->image_url) }}"
                    alt="Page {{ $chapterImage->page_number }}"
                    style="width: 100%; height: auto; border-radius: 12px;">
            </div>

            <div style="display: grid; gap: 18px;">
                <div style="background: #fff; border: 1px solid #dee2e6; border-radius: 14px; padding: 18px;">
                    <h3 style="margin-bottom: 14px; color: #1e5f4f;">Informasi Halaman</h3>
                    <div style="display: grid; gap: 10px; color: #495057; font-size: 14px;">
                        <div><strong>Karya:</strong> {{ $chapterImage->chapter->work->title }}</div>
                        <div><strong>Chapter:</strong> {{ $chapterImage->chapter->chapter_number }}</div>
                        <div><strong>Judul Chapter:</strong> {{ $chapterImage->chapter->title ?: '-' }}</div>
                        <div><strong>Nomor Halaman:</strong> {{ $chapterImage->page_number }}</div>
                        <div><strong>Total Gambar Chapter:</strong> {{ $chapterImage->chapter->images->count() }}</div>
                        <div><strong>Status Karya:</strong> {{ ucfirst($chapterImage->chapter->work->status) }}</div>
                    </div>
                </div>

                <div style="background: #fff; border: 1px solid #dee2e6; border-radius: 14px; padding: 18px;">
                    <h3 style="margin-bottom: 14px; color: #1e5f4f;">Uploader</h3>
                    <div style="font-size: 14px; color: #495057; line-height: 1.8;">
                        <div><strong>Nama:</strong> {{ $chapterImage->chapter->work->user->name }}</div>
                        <div><strong>Email:</strong> {{ $chapterImage->chapter->work->user->email }}</div>
                    </div>
                </div>

                <div style="background: #fff; border: 1px solid #dee2e6; border-radius: 14px; padding: 18px;">
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <a href="{{ route('admin.works.show', $chapterImage->chapter->work) }}"
                            style="flex: 1; min-width: 120px; text-align: center; padding: 10px 12px; text-decoration: none; background: #17a2b8; color: white; border-radius: 10px; font-weight: 700;">
                            Lihat Karya
                        </a>
                        <a href="{{ route('admin.chapters.show', $chapterImage->chapter) }}"
                            style="flex: 1; min-width: 120px; text-align: center; padding: 10px 12px; text-decoration: none; background: #28a745; color: white; border-radius: 10px; font-weight: 700;">
                            Lihat Chapter
                        </a>
                        <form action="{{ route('admin.chapter-images.destroy', $chapterImage) }}" method="POST" style="width: 100%;">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                onclick="return confirm('Hapus gambar ini?')"
                                style="width: 100%; padding: 10px 12px; border: none; background: #dc3545; color: white; border-radius: 10px; font-weight: 700; cursor: pointer;">
                                Hapus Gambar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 900px) {
            div[style*="grid-template-columns: minmax(0, 1fr) 340px"] {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
@endsection

