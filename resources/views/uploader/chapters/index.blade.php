@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Chapter Management</h1>
        <p>Kelola chapter untuk: <strong style="color: #48c9b0;">{{ $work->title }}</strong></p>
    </div>

    <div class="content-body">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <a href="{{ route('works.index') }}" style="display: inline-block; padding: 10px 20px; background: #6c757d; color: white; text-decoration: none; border-radius: 6px; font-weight: 500;">
                ← Kembali ke Works
            </a>
            <a href="{{ route('works.chapters.create', $work) }}" style="display: inline-block; padding: 10px 20px; background: #48c9b0; color: white; text-decoration: none; border-radius: 6px; font-weight: 500;">
                + Tambah Chapter
            </a>
        </div>

        @if (session('success'))
            <div style="padding: 15px; background: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #28a745;">
                {{ session('success') }}
            </div>
        @endif

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; background: white;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 15px; text-align: center; color: #1e5f4f; font-weight: 600; width: 80px;">No</th>
                        <th style="padding: 15px; text-align: left; color: #1e5f4f; font-weight: 600;">Judul Chapter</th>
                        <th style="padding: 15px; text-align: center; color: #1e5f4f; font-weight: 600; width: 120px;">Isi</th>
                        <th style="padding: 15px; text-align: center; color: #1e5f4f; font-weight: 600; width: 300px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($chapters as $chapter)
                        <tr style="border-bottom: 1px solid #dee2e6;">
                            <td style="padding: 15px; text-align: center;">
                                <span style="display: inline-block; width: 40px; height: 40px; background: #48c9b0; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                    {{ $chapter->chapter_number }}
                                </span>
                            </td>
                            <td style="padding: 15px;">
                                <div style="font-weight: 600; color: #1e5f4f; margin-bottom: 4px;">
                                    {{ $chapter->title ?? 'Tanpa Judul' }}
                                </div>
                                <div style="font-size: 12px; color: #6c757d;">
                                    Chapter {{ $chapter->chapter_number }}
                                </div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                @if ($chapter->text_content)
                                    <span style="display: inline-block; padding: 4px 12px; background: #d4edda; color: #155724; border-radius: 20px; font-size: 13px;">
                                        ✓ Text
                                    </span>
                                @else
                                    <span style="display: inline-block; padding: 4px 12px; background: #f8d7da; color: #721c24; border-radius: 20px; font-size: 13px;">
                                        ✗ Kosong
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 15px;">
                                <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                                    <a href="{{ route('chapters.images.index', $chapter) }}" style="padding: 6px 12px; background: #17a2b8; color: white; text-decoration: none; border-radius: 4px; font-size: 13px;">
                                        🖼️ Images
                                    </a>
                                    <a href="{{ route('works.chapters.edit', [$work, $chapter]) }}" style="padding: 6px 12px; background: #ffc107; color: #000; text-decoration: none; border-radius: 4px; font-size: 13px;">
                                        ✏️ Edit
                                    </a>
                                    <form action="{{ route('works.chapters.destroy', [$work, $chapter]) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Hapus chapter ini?')" style="padding: 6px 12px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 13px;">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 40px; text-align: center; color: #6c757d;">
                                <div style="font-size: 48px; margin-bottom: 10px;">😴</div>
                                <p>Belum ada chapter untuk karya ini.</p>
                                <a href="{{ route('works.chapters.create', $work) }}" style="display: inline-block; margin-top: 15px; padding: 10px 20px; background: #48c9b0; color: white; text-decoration: none; border-radius: 6px;">
                                    Tambah Chapter Pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection