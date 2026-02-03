@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Daftar Karya</h1>
        <p>Kelola semua karya manga dan novel Anda</p>
    </div>

    <div class="content-body">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="color: #1e5f4f; margin: 0;">Semua Karya</h3>
            <a href="{{ route('works.create') }}" style="display: inline-block; padding: 10px 20px; background: #48c9b0; color: white; text-decoration: none; border-radius: 6px; font-weight: 500;">
                + Tambah Uploadan
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
                        <th style="padding: 15px; text-align: left; color: #1e5f4f; font-weight: 600;">Judul</th>
                        <th style="padding: 15px; text-align: left; color: #1e5f4f; font-weight: 600;">Type</th>
                        <th style="padding: 15px; text-align: left; color: #1e5f4f; font-weight: 600;">Cover</th>
                        <th style="padding: 15px; text-align: center; color: #1e5f4f; font-weight: 600;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($works as $work)
                        <tr style="border-bottom: 1px solid #dee2e6;">
                            <td style="padding: 15px;">
                                <strong>{{ $work->title }}</strong>
                            </td>
                            <td style="padding: 15px;">
                                <span style="display: inline-block; padding: 4px 12px; background: #e9ecef; border-radius: 20px; font-size: 13px; color: #495057;">
                                    {{ ucfirst($work->type) }}
                                </span>
                            </td>
                            <td style="padding: 15px;">
                                @if ($work->cover)
                                    <img src="{{ asset('storage/' . $work->cover) }}" style="width: 60px; height: 80px; object-fit: cover; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                @else
                                    <div style="width: 60px; height: 80px; background: #e9ecef; border-radius: 5px; display: flex; align-items: center; justify-content: center; color: #6c757d; font-size: 12px;">
                                        No Cover
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                                    <a href="{{ route('works.chapters.index', $work) }}" style="padding: 6px 12px; background: #17a2b8; color: white; text-decoration: none; border-radius: 4px; font-size: 13px;">
                                        📖 Chapter
                                    </a>
                                    <a href="{{ route('works.edit', $work) }}" style="padding: 6px 12px; background: #ffc107; color: #000; text-decoration: none; border-radius: 4px; font-size: 13px;">
                                        ✏️ Edit
                                    </a>
                                    <form action="{{ route('works.destroy', $work) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Yakin ingin menghapus karya ini?')" style="padding: 6px 12px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 13px;">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 40px; text-align: center; color: #6c757d;">
                                <div style="font-size: 48px; margin-bottom: 10px;">📚</div>
                                <p>Belum ada karya. Mulai dengan menambahkan karya baru!</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection