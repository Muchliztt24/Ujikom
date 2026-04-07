@extends('layouts.user')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Notifikasi</h1>
        <p class="page-subtitle">
            @auth
                Update terbaru dari karya yang kamu simpan dan aktivitas penting di akunmu.
            @else
                Ikuti kabar terbaru dari rilisan baru dan judul yang sedang ramai dibaca.
            @endauth
        </p>
    </div>

    <div style="display: grid; gap: 22px;">
        @auth
            <section style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 24px;">
                <div style="display:flex; justify-content:space-between; gap:12px; align-items:center; flex-wrap:wrap; margin-bottom:18px;">
                    <div>
                        <h2 style="font-size: 24px; margin: 0 0 6px;">Update dari Library Kamu</h2>
                        <p style="margin: 0; color: var(--text-secondary);">Chapter terbaru dari judul yang sedang kamu ikuti.</p>
                    </div>
                    <span style="padding: 8px 12px; border-radius: 999px; background: rgba(45, 139, 115, 0.16); color: var(--light-green); font-size: 12px; font-weight: 700;">{{ $chapterUpdates->count() }} update</span>
                </div>

                @if ($chapterUpdates->count())
                    <div style="display:grid; gap: 12px;">
                        @foreach ($chapterUpdates as $chapter)
                            <a href="{{ route('works.chapters.read', [$chapter->work, $chapter]) }}" style="display:flex; gap:14px; align-items:flex-start; text-decoration:none; padding:16px; border-radius:16px; background: var(--bg-main); border:1px solid var(--border-color); color:inherit;">
                                <div style="width:42px; height:42px; border-radius:12px; background: rgba(45,139,115,0.16); display:flex; align-items:center; justify-content:center; color: var(--light-green);">
                                    <i class="bi bi-journal-richtext"></i>
                                </div>
                                <div style="flex:1;">
                                    <div style="font-weight:700; color: var(--text-primary);">{{ $chapter->work->title }}</div>
                                    <div style="color: var(--text-secondary); font-size:14px; margin-top:4px;">Chapter {{ $chapter->chapter_number }}{{ $chapter->title ? ' - ' . $chapter->title : '' }}</div>
                                </div>
                                <div style="color: var(--text-secondary); font-size:12px; white-space:nowrap;">{{ $chapter->created_at?->diffForHumans() }}</div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div style="padding: 28px; text-align:center; border-radius:16px; background: var(--bg-main); border:1px dashed var(--border-color); color: var(--text-secondary);">
                        Tambahkan beberapa judul ke bookmark untuk mulai menerima update terbaru.
                    </div>
                @endif
            </section>

            @if ($creatorFeed->count())
                <section style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 24px;">
                    <div style="margin-bottom:18px;">
                        <h2 style="font-size: 24px; margin: 0 0 6px;">Interaksi Pembaca</h2>
                        <p style="margin: 0; color: var(--text-secondary);">Komentar terbaru di karya yang kamu kelola.</p>
                    </div>

                    <div style="display:grid; gap: 12px;">
                        @foreach ($creatorFeed as $comment)
                            <a href="{{ route('works.public.show', $comment->chapter->work) }}" style="display:flex; gap:14px; align-items:flex-start; text-decoration:none; padding:16px; border-radius:16px; background: var(--bg-main); border:1px solid var(--border-color); color:inherit;">
                                <div style="width:42px; height:42px; border-radius:12px; background: rgba(96,165,250,0.16); display:flex; align-items:center; justify-content:center; color: #93c5fd;">
                                    <i class="bi bi-chat-left-text-fill"></i>
                                </div>
                                <div style="flex:1;">
                                    <div style="font-weight:700; color: var(--text-primary);">{{ $comment->user->name }} membagikan komentar di {{ $comment->chapter->work->title }}</div>
                                    <div style="color: var(--text-secondary); font-size:14px; margin-top:4px;">{{ \Illuminate\Support\Str::limit($comment->content, 110) }}</div>
                                </div>
                                <div style="color: var(--text-secondary); font-size:12px; white-space:nowrap;">{{ $comment->created_at?->diffForHumans() }}</div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        @endauth

        <section style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 24px;">
            <div style="margin-bottom:18px;">
                <h2 style="font-size: 24px; margin: 0 0 6px;">Rilisan Baru</h2>
                <p style="margin: 0; color: var(--text-secondary);">Pilihan judul terbaru yang sudah siap dibaca.</p>
            </div>

            <div style="display:grid; gap: 12px;">
                @foreach ($latestReleases as $work)
                    <a href="{{ route('works.public.show', $work) }}" style="display:flex; gap:14px; align-items:center; text-decoration:none; padding:16px; border-radius:16px; background: var(--bg-main); border:1px solid var(--border-color); color:inherit;">
                        <div style="width:60px; height:78px; border-radius:12px; overflow:hidden; background: linear-gradient(135deg, var(--dark-green), var(--primary-green)); display:flex; align-items:center; justify-content:center;">
                            @if ($work->cover)
                                <img src="{{ asset('storage/' . $work->cover) }}" alt="{{ $work->title }}" style="width:100%; height:100%; object-fit:cover;">
                            @else
                                <i class="{{ $work->type === 'novel' ? 'bi bi-book-half' : 'bi bi-palette-fill' }}" style="font-size: 26px; color: white;"></i>
                            @endif
                        </div>
                        <div style="flex:1;">
                            <div style="font-weight:700; color: var(--text-primary);">{{ $work->title }}</div>
                                            <div style="color: var(--text-secondary); font-size:14px; margin-top:4px;">{{ ucfirst($work->type) }} | {{ $work->display_author }}</div>
                        </div>
                        <div style="color: var(--text-secondary); font-size:12px; white-space:nowrap;">{{ $work->created_at?->diffForHumans() }}</div>
                    </a>
                @endforeach
            </div>
        </section>
    </div>
@endsection
