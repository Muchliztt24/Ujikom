@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Daftar Karya</h1>
        <p>Kelola semua karya manga dan novel Anda</p>
    </div>

    <div class="content-body">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="color: #1e5f4f; margin: 0;">Semua Karya</h3>
            <a href="{{ route('works.create') }}"
                style="display: inline-block; padding: 10px 20px; background: #48c9b0; color: white; text-decoration: none; border-radius: 6px; font-weight: 500;">
                + Tambah Karya Baru
            </a>
        </div>

        @if (session('success'))
            <div
                style="padding: 15px; background: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #28a745;">
                {{ session('success') }}
            </div>
        @endif

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; background: white;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 15px; text-align: left; color: #1e5f4f; font-weight: 600;">Judul</th>
                        <th style="padding: 15px; text-align: left; color: #1e5f4f; font-weight: 600;">Type</th>
                        <th style="padding: 15px; text-align: left; color: #1e5f4f; font-weight: 600;">Genre</th>
                        <th style="padding: 15px; text-align: left; color: #1e5f4f; font-weight: 600;">Status</th>
                        <th style="padding: 15px; text-align: left; color: #1e5f4f; font-weight: 600;">Cover</th>
                        <th style="padding: 15px; text-align: center; color: #1e5f4f; font-weight: 600;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($works as $work)
                        <tr style="border-bottom: 1px solid #dee2e6;">
                            <td style="padding: 15px;">
                                <strong>{{ $work->title }}</strong>
                                @if ($work->description)
                                    <div style="font-size: 12px; color: #6c757d; margin-top: 4px;">
                                        {{ Str::limit($work->description, 50) }}
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 15px;">
                                <span
                                    style="display: inline-block; padding: 4px 12px; background: #e9ecef; border-radius: 20px; font-size: 13px; color: #495057;">
                                    {{ ucfirst($work->type) }}
                                </span>
                            </td>
                            <td style="padding: 15px;">
                                <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                                    @forelse($work->genres as $genre)
                                        <span
                                            style="display: inline-block; padding: 3px 8px; background: rgba(45, 139, 115, 0.1); color: #2d8b73; border-radius: 12px; font-size: 11px; font-weight: 600;">
                                            {{ $genre->name }}
                                        </span>
                                    @empty
                                        <span style="font-size: 12px; color: #6c757d;">-</span>
                                    @endforelse
                                </div>
                            </td>
                            <td style="padding: 15px;">
                                @if ($work->status === 'approved')
                                    <span
                                        style="padding:4px 12px; background:#d4edda; color:#155724; border-radius:20px; font-size:12px;">
                                        Approved
                                    </span>
                                @elseif ($work->status === 'pending')
                                    <span
                                        style="padding:4px 12px; background:#fff3cd; color:#856404; border-radius:20px; font-size:12px;">
                                        Pending
                                    </span>
                                @else
                                    <span
                                        style="padding:4px 12px; background:#e2e3e5; color:#383d41; border-radius:20px; font-size:12px;">
                                        Draft
                                    </span>
                                @endif

                            </td>
                            <td style="padding: 15px;">
                                @if ($work->cover)
                                    <img src="{{ asset('storage/' . $work->cover) }}"
                                        style="width: 60px; height: 80px; object-fit: cover; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                @else
                                    <div
                                        style="width: 60px; height: 80px; background: #e9ecef; border-radius: 5px; display: flex; align-items: center; justify-content: center; color: #6c757d; font-size: 12px;">
                                        No Cover
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">

                                    <a href="{{ route('works.chapters.index', $work) }}"
                                        style="padding: 6px 12px; background: #17a2b8; color: white; text-decoration: none; border-radius: 4px; font-size: 13px;">
                                        📖 Chapter
                                    </a>

                                    <a href="{{ route('works.edit', $work) }}"
                                        style="padding: 6px 12px; background: #ffc107; color: #000; text-decoration: none; border-radius: 4px; font-size: 13px;">
                                        ✏️ Edit
                                    </a>
                                    {{-- SUBMIT KE ADMIN --}}
                                    @if ($work->status === 'draft')
                                        <form action="{{ route('works.submit', $work) }}" method="POST">
                                            @csrf
                                            @method('POST')
                                            <button onclick="return confirm('Kirim karya ini ke admin untuk ditinjau?')"
                                                style="padding: 6px 12px; background: #6f42c1; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 13px;">
                                                🚀 Submit
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('works.destroy', $work) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Yakin ingin menghapus karya ini?')"
                                            style="padding: 6px 12px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 13px;">
                                            🗑️ Hapus
                                        </button>
                                    </form>

                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 40px; text-align: center; color: #6c757d;">
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
