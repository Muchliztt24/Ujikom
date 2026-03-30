@extends('layouts.admin')

@section('content')
<div class="content-header">
    <h1>Kelola Pengguna</h1>
    <p>Atur role dan pantau akun yang terdaftar.</p>
</div>

<div class="content-body">
    @if(session('success'))
        <div style="margin-bottom: 16px; padding: 14px 16px; background: #d4edda; color: #155724; border-radius: 10px;">{{ session('success') }}</div>
    @endif

    <div class="admin-muted" style="margin-bottom: 20px; font-weight: 600;">Total pengguna: {{ $users->count() }}</div>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Terdaftar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div style="display:flex; align-items:center; gap:12px;">
                                <div style="width:36px; height:36px; border-radius:50%; background: linear-gradient(135deg, #3db69b, #79d9c1); color:#062028; display:flex; align-items:center; justify-content:center; font-weight:800;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span>{{ $user->name }}</span>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="admin-chip {{ $user->role?->name === 'admin' ? 'warning' : ($user->role?->name === 'uploader' ? 'success' : '') }}">
                                {{ ucfirst($user->role?->name ?? 'User') }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td><a href="{{ route('admin.users.edit', $user) }}" class="admin-btn info">Edit Role</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6"><div class="admin-empty">Tidak ada pengguna.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
