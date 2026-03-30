@extends($layout)

@section('content')
    <div class="content-header">
        <h1>Edit Profile</h1>
        <p>Perbarui informasi akun Anda di sini.</p>
    </div>

    <div class="content-body">
        @if (session('success'))
            <div style="margin-bottom: 20px; padding: 14px 16px; background: rgba(72, 201, 176, 0.15); border: 1px solid rgba(72, 201, 176, 0.35); color: var(--text-primary, #1e5f4f); border-radius: 12px;">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" style="max-width: 720px; display: grid; gap: 20px;">
            @csrf
            @method('PATCH')

            <div style="display: grid; gap: 8px;">
                <label for="name" style="font-weight: 700;">Nama</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}"
                    style="padding: 14px 16px; border-radius: 12px; border: 1px solid #cbd5e1;">
                @error('name')
                    <small style="color: #dc2626;">{{ $message }}</small>
                @enderror
            </div>

            <div style="display: grid; gap: 8px;">
                <label for="email" style="font-weight: 700;">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}"
                    style="padding: 14px 16px; border-radius: 12px; border: 1px solid #cbd5e1;">
                @error('email')
                    <small style="color: #dc2626;">{{ $message }}</small>
                @enderror
            </div>

            <div style="display: grid; gap: 8px;">
                <label for="password" style="font-weight: 700;">Password Baru</label>
                <input id="password" name="password" type="password"
                    style="padding: 14px 16px; border-radius: 12px; border: 1px solid #cbd5e1;">
                @error('password')
                    <small style="color: #dc2626;">{{ $message }}</small>
                @enderror
            </div>

            <div style="display: grid; gap: 8px;">
                <label for="password_confirmation" style="font-weight: 700;">Konfirmasi Password Baru</label>
                <input id="password_confirmation" name="password_confirmation" type="password"
                    style="padding: 14px 16px; border-radius: 12px; border: 1px solid #cbd5e1;">
            </div>

            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <button type="submit"
                    style="padding: 14px 22px; background: linear-gradient(135deg, #2d8b73, #48c9b0); color: white; border: none; border-radius: 12px; font-weight: 700; cursor: pointer;">
                    Simpan Perubahan
                </button>
                <a href="{{ route('dashboard') }}"
                    style="padding: 14px 22px; background: #e2e8f0; color: #1f2937; text-decoration: none; border-radius: 12px; font-weight: 700;">
                    Kembali
                </a>
            </div>
        </form>
    </div>
@endsection
