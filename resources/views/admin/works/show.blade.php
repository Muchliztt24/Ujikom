@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Detail Karya</h1>
        <p>Ringkasan karya untuk kebutuhan moderasi admin.</p>
    </div>

    <div class="content-body">
        <a href="{{ route('admin.works.index') }}" style="display: inline-block; margin-bottom: 18px; text-decoration: none; color: #2d8b73; font-weight: 700;">? Kembali</a>

        @if (session('success'))
            <div style="margin-bottom: 16px; padding: 14px 16px; background: #d4edda; color: #155724; border-radius: 10px;">{{ session('success') }}</div>
        @endif

        <div style="display: grid; grid-template-columns: 280px minmax(0, 1fr); gap: 24px;">
            <div style="display: grid; gap: 18px;">
                <div style="background: #fff; border: 1px solid #dee2e6; border-radius: 14px; padding: 16px;">
                    @if ($work->cover)
                        <img src="{{ Storage::url($work->cover) }}" alt="{{ $work->title }}" style="width: 100%; border-radius: 12px; object-fit: cover;">
                    @else
                        <div style="height: 360px; display: flex; align-items: center; justify-content: center; background: #f1f3f5; border-radius: 12px; color: #6c757d;">Tidak ada cover</div>
                    @endif
                </div>

                <div style="background: #fff; border: 1px solid #dee2e6; border-radius: 14px; padding: 16px;">
                    <div style="display: grid; gap: 10px; font-size: 14px; color: #495057;">
                        <div><strong>Status:</strong> {{ ucfirst($work->status) }}</div>
                        <div><strong>Tipe:</strong> {{ ucfirst($work->type) }}</div>
                        <div><strong>Author:</strong> {{ $work->user->name }}</div>
                        <div><strong>Email:</strong> {{ $work->user->email }}</div>
                        <div><strong>Genre:</strong> {{ $work->genres->pluck('name')->implode(', ') ?: '-' }}</div>
                        <div><strong>Total Chapter:</strong> {{ $work->chapters->count() }}</div>
                    </div>
                </div>

                <div style="background: #fff; border: 1px solid #dee2e6; border-radius: 14px; padding: 16px; display: grid; gap: 8px;">
                    @if ($work->status === 'pending')
                        <form action="{{ route('admin.works.approve', $work) }}" method="POST">@csrf<button type="submit" style="width: 100%; padding: 10px 12px; border: none; background: #28a745; color: white; border-radius: 10px; font-weight: 700; cursor: pointer;">Approve</button></form>
                        <form action="{{ route('admin.works.reject', $work) }}" method="POST">@csrf<button type="submit" style="width: 100%; padding: 10px 12px; border: none; background: #ffc107; color: #212529; border-radius: 10px; font-weight: 700; cursor: pointer;">Reject</button></form>
                    @endif
                    <form action="{{ route('admin.works.destroy', $work) }}" method="POST">@csrf @method('DELETE')<button type="submit" onclick="return confirm('Hapus karya ini?')" style="width: 100%; padding: 10px 12px; border: none; background: #dc3545; color: white; border-radius: 10px; font-weight: 700; cursor: pointer;">Hapus</button></form>
                </div>
            </div>

            <div style="display: grid; gap: 18px;">
                <div style="background: #fff; border: 1px solid #dee2e6; border-radius: 14px; padding: 18px;">
                    <h2 style="margin-bottom: 12px; color: #1e5f4f;">{{ $work->title }}</h2>
                    <p style="color: #495057; line-height: 1.8;">{{ $work->description ?: 'Tidak ada deskripsi.' }}</p>
                </div>

                <div style="background: #fff; border: 1px solid #dee2e6; border-radius: 14px; padding: 18px;">
                    <h3 style="margin-bottom: 14px; color: #1e5f4f;">Daftar Chapter</h3>
                    @if ($work->chapters->count())
                        <div style="display: grid; gap: 10px;">
                            @foreach ($work->chapters as $chapter)
                                <div style="padding: 14px; border: 1px solid #eef2f4; border-radius: 12px; display: flex; justify-content: space-between; gap: 14px; align-items: center;">
                                    <div>
                                        <div style="font-weight: 700; color: #1e5f4f;">Chapter {{ $chapter->chapter_number }}{{ $chapter->title ? ' - ' . $chapter->title : '' }}</div>
                                        <div style="font-size: 13px; color: #6c757d;">
                                            @if ($work->type === 'comic')
                                                {{ $chapter->images_count }} gambar
                                            @else
                                                {{ \Illuminate\Support\Str::words($chapter->text_content ?: 'Belum ada isi', 12) }}
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.chapters.show', $chapter) }}" style="text-decoration: none; background: #17a2b8; color: white; padding: 8px 12px; border-radius: 8px; font-size: 13px; font-weight: 700;">Lihat</a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="color: #6c757d;">Belum ada chapter.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 900px) {
            div[style*="grid-template-columns: 280px minmax(0, 1fr)"] {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
@endsection
