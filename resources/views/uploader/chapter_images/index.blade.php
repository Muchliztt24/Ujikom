@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>{{ $chapter->work->title }}</h1>
        <p>
            Chapter {{ $chapter->chapter_number }}
            @if ($chapter->title)
                - {{ $chapter->title }}
            @endif
        </p>
    </div>

    <div class="content-body">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <a href="{{ route('works.chapters.index', $chapter->work) }}" style="display: inline-block; padding: 10px 20px; background: #6c757d; color: white; text-decoration: none; border-radius: 6px; font-weight: 500;">
                ← Kembali ke Chapters
            </a>
            <a href="{{ route('chapters.images.create', $chapter) }}" style="display: inline-block; padding: 10px 20px; background: #48c9b0; color: white; text-decoration: none; border-radius: 6px; font-weight: 500;">
                + Tambah Gambar
            </a>
        </div>

        @if (session('success'))
            <div style="padding: 15px; background: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #28a745;">
                {{ session('success') }}
            </div>
        @endif

        @if ($images->count() === 0)
            <div style="padding: 40px; text-align: center; background: #fff3cd; border-radius: 10px; border-left: 4px solid #ffc107;">
                <div style="font-size: 48px; margin-bottom: 10px;">🖼️</div>
                <p style="color: #856404; margin-bottom: 15px;">Belum ada gambar di chapter ini.</p>
                <a href="{{ route('chapters.images.create', $chapter) }}" style="display: inline-block; padding: 10px 20px; background: #48c9b0; color: white; text-decoration: none; border-radius: 6px;">
                    Tambah Gambar Pertama
                </a>
            </div>
        @else
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; background: white;">
                    <thead>
                        <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                            <th style="padding: 15px; text-align: center; color: #1e5f4f; font-weight: 600; width: 100px;">Page</th>
                            <th style="padding: 15px; text-align: left; color: #1e5f4f; font-weight: 600;">Preview</th>
                            <th style="padding: 15px; text-align: center; color: #1e5f4f; font-weight: 600; width: 200px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($images as $image)
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 15px; text-align: center;">
                                    <span style="display: inline-block; padding: 8px 16px; background: #e9ecef; border-radius: 20px; font-weight: 600; color: #495057;">
                                        {{ $image->page_number }}
                                    </span>
                                </td>
                                <td style="padding: 15px;">
                                    <img src="{{ asset('storage/' . $image->image_url) }}" 
                                         style="max-height: 150px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); object-fit: contain;">
                                </td>
                                <td style="padding: 15px;">
                                    <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                                        <a href="{{ route('chapters.images.edit', [$chapter, $image]) }}" style="padding: 6px 12px; background: #ffc107; color: #000; text-decoration: none; border-radius: 4px; font-size: 13px;">
                                            ✏️ Edit
                                        </a>
                                        <form action="{{ route('chapters.images.destroy', [$chapter, $image]) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Hapus gambar ini?')" style="padding: 6px 12px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 13px;">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 20px; padding: 15px; background: #e7f3ff; border-radius: 8px; border-left: 4px solid #17a2b8;">
                <strong style="color: #004085;">Total Gambar:</strong> {{ $images->count() }} halaman
            </div>
        @endif
    </div>
@endsection