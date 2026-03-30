@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Detail Approval</h1>
        <p>Halaman review sederhana untuk karya yang sedang menunggu approval.</p>
    </div>

    <div class="content-body">
        <a href="{{ route('admin.works.pending') }}"
            style="display: inline-block; margin-bottom: 18px; text-decoration: none; color: #2d8b73; font-weight: 700;">
            ? Kembali ke approval
        </a>

        <div style="display: grid; grid-template-columns: 320px minmax(0, 1fr); gap: 24px; align-items: start;">
            <div style="display: grid; gap: 18px;">
                <div style="background: #fff; border: 1px solid #dee2e6; border-radius: 14px; overflow: hidden;">
                    @if ($work->cover)
                        <img src="{{ asset('storage/' . $work->cover) }}" alt="{{ $work->title }}"
                            style="width: 100%; height: 420px; object-fit: cover;">
                    @else
                        <div style="height: 420px; display: flex; align-items: center; justify-content: center; background: #eef3f7; color: #6c757d; font-size: 72px;">
                            {{ $work->type === 'comic' ? '??' : '??' }}
                        </div>
                    @endif
                </div>

                <div style="background: #fff; border: 1px solid #dee2e6; border-radius: 14px; padding: 18px; display: grid; gap: 10px;">
                    <form action="{{ route('admin.works.approve', $work) }}" method="POST">
                        @csrf
                        <button type="submit" style="width: 100%; padding: 10px 12px; border: none; background: #28a745; color: white; border-radius: 10px; font-weight: 700; cursor: pointer;">
                            Setujui Karya
                        </button>
                    </form>
                    <form action="{{ route('admin.works.reject', $work) }}" method="POST">
                        @csrf
                        <button type="submit" style="width: 100%; padding: 10px 12px; border: none; background: #dc3545; color: white; border-radius: 10px; font-weight: 700; cursor: pointer;">
                            Tolak Karya
                        </button>
                    </form>
                </div>
            </div>

            <div style="display: grid; gap: 18px;">
                <div style="background: #fff; border: 1px solid #dee2e6; border-radius: 14px; padding: 22px;">
                    <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 12px;">
                        <span style="display: inline-flex; padding: 6px 12px; border-radius: 999px; background: #fff3cd; color: #856404; font-size: 12px; font-weight: 700;">{{ ucfirst($work->status) }}</span>
                        <span style="display: inline-flex; padding: 6px 12px; border-radius: 999px; background: {{ $work->type === 'comic' ? '#fff4e6' : '#eef3ff' }}; color: {{ $work->type === 'comic' ? '#c05621' : '#2b6cb0' }}; font-size: 12px; font-weight: 700;">{{ $work->type === 'comic' ? 'Comic' : 'Novel' }}</span>
                    </div>
                    <h2 style="margin: 0 0 8px; color: #1e5f4f; font-size: 30px;">{{ $work->title }}</h2>
                    <div style="color: #6c757d; font-size: 14px; line-height: 1.8;">
                        Author: {{ $work->user->name }}<br>
                        Email: {{ $work->user->email }}<br>
                        Dibuat: {{ $work->created_at?->format('d M Y H:i') ?? '-' }}<br>
                        Chapter: {{ $work->chapters->count() }}
                    </div>
                </div>

                <div style="background: #fff; border: 1px solid #dee2e6; border-radius: 14px; padding: 22px;">
                    <h3 style="margin: 0 0 14px; color: #1e5f4f;">Genre</h3>
                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                        @forelse ($work->genres as $genre)
                            <span style="padding: 6px 10px; border-radius: 999px; background: #e8f5f3; color: #1e5f4f; font-size: 12px; font-weight: 600;">
                                {{ $genre->name }}
                            </span>
                        @empty
                            <span style="color: #6c757d;">Belum ada genre.</span>
                        @endforelse
                    </div>
                </div>

                <div style="background: #fff; border: 1px solid #dee2e6; border-radius: 14px; padding: 22px;">
                    <h3 style="margin: 0 0 14px; color: #1e5f4f;">Deskripsi</h3>
                    <div style="color: #495057; line-height: 1.8; white-space: pre-line;">
                        {{ $work->description ?: 'Belum ada deskripsi.' }}
                    </div>
                </div>

                <div style="background: #fff; border: 1px solid #dee2e6; border-radius: 14px; padding: 22px;">
                    <h3 style="margin: 0 0 14px; color: #1e5f4f;">Daftar Chapter</h3>
                    @if ($work->chapters->count())
                        <div style="display: grid; gap: 10px;">
                            @foreach ($work->chapters->sortBy('chapter_number') as $chapter)
                                <div style="padding: 12px 14px; border: 1px solid #edf2f7; border-radius: 10px; background: #fcfcfc; color: #495057;">
                                    <strong>Chapter {{ $chapter->chapter_number }}</strong>
                                    {{ $chapter->title ? ' - ' . $chapter->title : '' }}
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
        @media (max-width: 960px) {
            div[style*="grid-template-columns: 320px minmax(0, 1fr)"] {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
@endsection
