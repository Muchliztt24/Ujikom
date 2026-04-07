@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Daftar Karya</h1>
        <p>Moderasi karya yang sudah masuk ke platform.</p>
    </div>

    <div class="content-body">
        @if (session('success'))
            <div style="margin-bottom: 16px; padding: 14px 16px; background: #d4edda; color: #155724; border-radius: 10px;">
                {{ session('success') }}
            </div>
        @endif

        <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px;">
            <a href="{{ route('admin.works.index') }}" class="admin-filter-link {{ !$status ? 'active' : '' }}">Semua</a>
            @foreach (['pending' => 'Pending', 'approved' => 'Approved', 'draft' => 'Draft'] as $key => $label)
                <a href="{{ route('admin.works.index', ['status' => $key]) }}" class="admin-filter-link {{ $status === $key ? 'active' : '' }}">{{ $label }}</a>
            @endforeach
        </div>

        @if ($works->count())
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="padding: 14px; text-align: left;">Judul</th>
                            <th style="padding: 14px; text-align: left;">Author</th>
                            <th style="padding: 14px; text-align: left;">Tipe</th>
                            <th style="padding: 14px; text-align: left;">Status</th>
                            <th style="padding: 14px; text-align: left;">Genre</th>
                            <th style="padding: 14px; text-align: left;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($works as $work)
                            <tr>
                                <td>{{ $work->title }}</td>
                                    <td>
                                        <div>{{ $work->display_author }}</div>
                                        <div class="admin-muted" style="font-size: 12px; margin-top: 4px;">Uploader: {{ $work->user->name }}</div>
                                    </td>
                                <td><span class="admin-chip">{{ ucfirst($work->type) }}</span></td>
                                <td><span class="admin-chip">{{ ucfirst($work->status) }}</span></td>
                                <td>{{ $work->genres->pluck('name')->take(3)->implode(', ') ?: '-' }}</td>
                                <td>
                                    <div class="admin-btn-row">
                                        <a href="{{ route('admin.works.show', $work) }}" style="padding: 8px 12px; text-decoration: none; background: #17a2b8; color: white; border-radius: 8px; font-size: 13px; font-weight: 700;">Lihat</a>
                                        @if ($work->status === 'pending')
                                            <form action="{{ route('admin.works.approve', $work) }}" method="POST">@csrf<button type="submit" style="padding: 8px 12px; border: none; background: #28a745; color: white; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer;">Approve</button></form>
                                            <form action="{{ route('admin.works.reject', $work) }}" method="POST">@csrf<button type="submit" style="padding: 8px 12px; border: none; background: #ffc107; color: #212529; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer;">Reject</button></form>
                                        @endif
                                        <form action="{{ route('admin.works.destroy', $work) }}" method="POST">@csrf @method('DELETE')<button type="submit" onclick="return confirm('Hapus karya ini?')" style="padding: 8px 12px; border: none; background: #dc3545; color: white; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer;">Hapus</button></form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="admin-pagination">
                <div class="admin-pagination-meta">
                    Menampilkan {{ $works->firstItem() }}-{{ $works->lastItem() }} dari {{ $works->total() }} karya
                </div>
                <div class="admin-pagination-links">
                    <a href="{{ $works->previousPageUrl() ?: '#' }}" class="admin-page-link {{ $works->onFirstPage() ? 'disabled' : '' }}">&laquo; Previous</a>
                    <a href="{{ $works->nextPageUrl() ?: '#' }}" class="admin-page-link {{ $works->hasMorePages() ? '' : 'disabled' }}">Next &raquo;</a>
                </div>
            </div>
        @else
            <div class="admin-empty">Tidak ada karya untuk filter ini.</div>
        @endif
    </div>
@endsection

