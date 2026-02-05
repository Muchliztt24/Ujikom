@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Dashboard</h1>
        <p>Selamat datang di panel admin Nokomi</p>
    </div>

    <div class="content-body">
        @if (session('status'))
            <div style="padding: 15px; background: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 20px;">
                {{ session('status') }}
            </div>
        @endif

        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <!-- Card Total Works -->
            <div
                style="background: linear-gradient(135deg, #48c9b0 0%, #5fd4bd 100%); padding: 25px; border-radius: 10px; color: white;">
                <div style="font-size: 14px; opacity: 0.9; margin-bottom: 10px;">Total Karya</div>
                <div style="font-size: 36px; font-weight: 700;">{{ \App\Models\Work::count() }}</div>
            </div>

            <!-- Card Total Chapters -->
            <div
                style="background: linear-gradient(135deg, #2d8b73 0%, #48c9b0 100%); padding: 25px; border-radius: 10px; color: white;">
                <div style="font-size: 14px; opacity: 0.9; margin-bottom: 10px;">Total Chapter</div>
                <div style="font-size: 36px; font-weight: 700;">{{ \App\Models\Chapter::count() }}</div>
            </div>

            <!-- Card Total Images -->
            <div
                style="background: linear-gradient(135deg, #1e5f4f 0%, #2d8b73 100%); padding: 25px; border-radius: 10px; color: white;">
                <div style="font-size: 14px; opacity: 0.9; margin-bottom: 10px;">Total Gambar</div>
                <div style="font-size: 36px; font-weight: 700;">{{ \App\Models\ChapterImage::count() }}</div>
            </div>
        </div>

        <div style="margin-top: 30px;">
            <h3 style="color: #1e5f4f; margin-bottom: 20px; font-size: 20px;">Quick Actions</h3>
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                @auth
                    @if (auth()->user()->role?->name === 'uploader')
                        <a href="{{ route('works.create') }}"
                            style="display: inline-block; padding: 12px 24px; background: #48c9b0; color: white;
                  text-decoration: none; border-radius: 6px; font-weight: 500; transition: all 0.3s;">
                            + Tambah Karya Baru
                        </a>

                        <a href="{{ route('works.index') }}"
                            style="display: inline-block; padding: 12px 24px; background: #2d8b73; color: white;
                  text-decoration: none; border-radius: 6px; font-weight: 500; transition: all 0.3s;">
                            📚 Lihat Semua Karya
                        </a>
                    @endif
                    @if (auth()->user()->role?->name === 'admin')
                        <a href="{{ route('admin.users.index') }}"
                            style="display: inline-block; padding: 12px 24px; background: #6c757d; color: white;
                 text-decoration: none; border-radius: 6px; font-weight: 500; transition: all 0.3s;">
                            ⚙️ Kelola Pengguna
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
@endsection
