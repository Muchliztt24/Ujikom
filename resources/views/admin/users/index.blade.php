@extends('layouts.admin')

@section('content')
<div class="content-header">
    <h1>Kelola Pengguna</h1>
    <p>Manajemen pengguna dan role sistem</p>
</div>

<div class="content-body">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width: 25%">Nama</th>
                    <th style="width: 25%">Email</th>
                    <th style="width: 15%">Role</th>
                    <th style="width: 15%">Terdaftar</th>
                    <th style="width: 15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar-small">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span>{{ $user->name }}</span>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge badge-{{ $user->role?->name === 'admin' ? 'admin' : ($user->role?->name === 'uploader' ? 'uploader' : 'user') }}">
                                {{ ucfirst($user->role?->name ?? 'User') }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-edit">
                                ✏️ Edit Role
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada pengguna</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    /* Alert Styles */
    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-success::before {
        content: "✓";
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        background-color: #28a745;
        color: white;
        border-radius: 50%;
        font-weight: bold;
    }

    /* Table Container */
    .table-container {
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
    }

    /* Table Styles */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    .data-table thead {
        background: linear-gradient(135deg, #1e5f4f 0%, #2d8b73 100%);
    }

    .data-table th {
        padding: 16px;
        text-align: left;
        font-weight: 600;
        font-size: 14px;
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .data-table tbody tr {
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s ease;
    }

    .data-table tbody tr:hover {
        background-color: #f8fffe;
    }

    .data-table tbody tr:last-child {
        border-bottom: none;
    }

    .data-table td {
        padding: 16px;
        font-size: 14px;
        color: #333;
    }

    /* User Cell */
    .user-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar-small {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #48c9b0 0%, #5fd4bd 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #1e5f4f;
        font-size: 14px;
    }

    /* Badge Styles */
    .badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-admin {
        background-color: #ffeaa7;
        color: #d63031;
    }

    .badge-uploader {
        background-color: #a29bfe;
        color: #6c5ce7;
    }

    .badge-user {
        background-color: #dfe6e9;
        color: #2d3436;
    }

    /* Button Styles */
    .btn {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
    }

    .btn-edit {
        background-color: #48c9b0;
        color: white;
    }

    .btn-edit:hover {
        background-color: #2d8b73;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(72, 201, 176, 0.3);
    }

    /* Text Center */
    .text-center {
        text-align: center;
        color: #6c757d;
        padding: 30px;
        font-style: italic;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .table-container {
            border-radius: 0;
            border-left: none;
            border-right: none;
        }

        .data-table th,
        .data-table td {
            padding: 12px 8px;
            font-size: 13px;
        }

        .user-avatar-small {
            width: 32px;
            height: 32px;
            font-size: 12px;
        }

        .badge {
            padding: 4px 10px;
            font-size: 11px;
        }
    }
</style>
@endsection