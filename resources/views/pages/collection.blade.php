@extends('layouts.user')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Collection</h1>
        <p class="page-subtitle">
            @if ($isGuest)
                Jelajahi rak pilihan dari berbagai genre, novel, dan comic populer.
            @else
                Semua judul favorit, progres baca, dan rekomendasi tersusun rapi dalam satu tempat.
            @endif
        </p>
    </div>

    <div style="display:grid; gap: 26px;">
        @if (! $isGuest)
            <section style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px;">
                <div style="background: var(--bg-card); border:1px solid var(--border-color); border-radius:18px; padding:20px;">
                    <div style="color: var(--text-secondary); font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.06em;">Bookmark</div>
                    <div style="font-size:36px; font-weight:800; margin-top:8px;">{{ $bookmarkedWorks->count() }}</div>
                    <div style="color: var(--text-secondary); margin-top:8px;">Judul yang tersimpan di library pribadi.</div>
                </div>
                <div style="background: var(--bg-card); border:1px solid var(--border-color); border-radius:18px; padding:20px;">
                    <div style="color: var(--text-secondary); font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.06em;">Riwayat</div>
                    <div style="font-size:36px; font-weight:800; margin-top:8px;">{{ $recentHistory->count() }}</div>
                    <div style="color: var(--text-secondary); margin-top:8px;">Chapter terakhir yang baru kamu buka.</div>
                </div>
                <div style="background: var(--bg-card); border:1px solid var(--border-color); border-radius:18px; padding:20px;">
                    <div style="color: var(--text-secondary); font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.06em;">Rekomendasi</div>
                    <div style="font-size:36px; font-weight:800; margin-top:8px;">{{ $recommendedWorks->count() }}</div>
                    <div style="color: var(--text-secondary); margin-top:8px;">Pilihan baru yang dekat dengan selera bacamu.</div>
                </div>
            </section>

            @if ($recentHistory->count())
                <section style="background: var(--bg-card); border:1px solid var(--border-color); border-radius:20px; padding:24px;">
                    <div style="display:flex; justify-content:space-between; gap:12px; align-items:center; flex-wrap:wrap; margin-bottom:18px;">
                        <div>
                            <h2 style="font-size:24px; margin:0 0 6px;">Lanjutkan Membaca</h2>
                            <p style="margin:0; color: var(--text-secondary);">Kembali ke chapter terakhir yang sempat kamu buka.</p>
                        </div>
                        <a href="{{ route('pages.history') }}" style="padding:10px 14px; border-radius:12px; text-decoration:none; background: var(--bg-main); border:1px solid var(--border-color); color: var(--text-primary); font-weight:700;">Lihat Riwayat</a>
                    </div>

                    <div style="display:grid; gap:12px;">
                        @foreach ($recentHistory as $history)
                            <a href="{{ route('works.chapters.read', [$history->work, $history->chapter]) }}" style="display:flex; gap:14px; align-items:center; text-decoration:none; padding:16px; border-radius:16px; background: var(--bg-main); border:1px solid var(--border-color); color:inherit;">
                                <div style="width:60px; height:78px; border-radius:12px; overflow:hidden; background: linear-gradient(135deg, var(--dark-green), var(--primary-green)); display:flex; align-items:center; justify-content:center;">
                                    @if ($history->work?->cover)
                                        <img src="{{ asset('storage/' . $history->work->cover) }}" alt="{{ $history->work->title }}" style="width:100%; height:100%; object-fit:cover;">
                                    @else
                                        <i class="{{ $history->work?->type === 'novel' ? 'bi bi-book-half' : 'bi bi-palette-fill' }}" style="font-size: 26px; color: white;"></i>
                                    @endif
                                </div>
                                <div style="flex:1;">
                                    <div style="font-weight:700; color: var(--text-primary);">{{ $history->work?->title }}</div>
                                    <div style="color: var(--text-secondary); font-size:14px; margin-top:4px;">Chapter {{ $history->chapter?->chapter_number }}{{ $history->chapter?->title ? ' - ' . $history->chapter->title : '' }}</div>
                                </div>
                                <div style="color: var(--text-secondary); font-size:12px; white-space:nowrap;">{{ $history->last_read_at?->diffForHumans() }}</div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        @else
            <section style="background: linear-gradient(135deg, rgba(45,139,115,0.18), rgba(72,201,176,0.08)); border:1px solid rgba(72,201,176,0.22); border-radius:20px; padding:26px; display:flex; justify-content:space-between; gap:16px; align-items:center; flex-wrap:wrap;">
                <div>
                    <h2 style="font-size:24px; margin:0 0 6px;">Masuk untuk membuka rak pribadi</h2>
                    <p style="margin:0; color: var(--text-secondary); max-width:620px;">Simpan judul favorit, susun library, dan lanjutkan progress bacamu dari mana saja.</p>
                </div>
                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    <a href="{{ route('login') }}" style="padding:12px 16px; border-radius:12px; text-decoration:none; background: linear-gradient(135deg, var(--primary-green), var(--light-green)); color: white; font-weight:700;">Masuk</a>
                    <a href="{{ route('register') }}" style="padding:12px 16px; border-radius:12px; text-decoration:none; background: var(--bg-card); border:1px solid var(--border-color); color: var(--text-primary); font-weight:700;">Daftar</a>
                </div>
            </section>
        @endif

        @if ($novels->count())
            <section>
                <div style="display:flex; justify-content:space-between; gap:12px; align-items:center; flex-wrap:wrap; margin-bottom:18px;">
                    <div>
                        <h2 style="font-size:24px; margin:0 0 6px;">Rak Novel</h2>
                        <p style="margin:0; color: var(--text-secondary);">Kumpulan novel yang tersimpan di library kamu.</p>
                    </div>
                </div>
                <div class="works-grid" style="margin-top:0;">
                    @foreach ($novels as $work)
                        <a href="{{ route('works.public.show', $work) }}" class="work-card" style="text-decoration:none;">
                            @if ($work->cover)
                                <img src="{{ asset('storage/' . $work->cover) }}" alt="{{ $work->title }}" class="work-cover">
                            @endif
                            <div class="work-info">
                                <div class="work-title">{{ $work->title }}</div>
                                <span class="work-type">Novel</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($comics->count())
            <section>
                <div style="display:flex; justify-content:space-between; gap:12px; align-items:center; flex-wrap:wrap; margin-bottom:18px;">
                    <div>
                        <h2 style="font-size:24px; margin:0 0 6px;">Rak Comic</h2>
                        <p style="margin:0; color: var(--text-secondary);">Pilihan comic yang siap dilanjutkan kapan saja.</p>
                    </div>
                </div>
                <div class="works-grid" style="margin-top:0;">
                    @foreach ($comics as $work)
                        <a href="{{ route('works.public.show', $work) }}" class="work-card" style="text-decoration:none;">
                            @if ($work->cover)
                                <img src="{{ asset('storage/' . $work->cover) }}" alt="{{ $work->title }}" class="work-cover">
                            @endif
                            <div class="work-info">
                                <div class="work-title">{{ $work->title }}</div>
                                <span class="work-type">Comic</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        <section>
            <div style="display:flex; justify-content:space-between; gap:12px; align-items:center; flex-wrap:wrap; margin-bottom:18px;">
                <div>
                    <h2 style="font-size:24px; margin:0 0 6px;">Rekomendasi</h2>
                    <p style="margin:0; color: var(--text-secondary);">Judul pilihan yang cocok untuk sesi baca berikutnya.</p>
                </div>
            </div>

            @if ($recommendedWorks->count())
                <div class="works-grid" style="margin-top:0;">
                    @foreach ($recommendedWorks as $work)
                        <a href="{{ route('works.public.show', $work) }}" class="work-card" style="text-decoration:none;">
                            @if ($work->cover)
                                <img src="{{ asset('storage/' . $work->cover) }}" alt="{{ $work->title }}" class="work-cover">
                            @endif
                            <div class="work-info">
                                <div class="work-title">{{ $work->title }}</div>
                                <span class="work-type">{{ strtoupper($work->type) }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div style="background: var(--bg-card); border:1px dashed var(--border-color); border-radius:18px; padding:30px; text-align:center; color: var(--text-secondary);">
                    Judul pilihan akan terus diperbarui seiring library kamu bertambah.
                </div>
            @endif
        </section>

        <section style="background: var(--bg-card); border:1px solid var(--border-color); border-radius:20px; padding:24px;">
            <div style="margin-bottom:18px;">
                <h2 style="font-size:24px; margin:0 0 6px;">Genre Pilihan</h2>
                <p style="margin:0; color: var(--text-secondary);">Tema yang paling sering muncul di katalog Nokomi.</p>
            </div>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                @foreach ($highlightGenres as $genre)
                    <a href="{{ route('home', ['genre' => $genre->id]) }}" style="padding:10px 14px; border-radius:999px; text-decoration:none; background: rgba(45, 139, 115, 0.12); border:1px solid rgba(72, 201, 176, 0.18); color: var(--text-primary); font-weight:700; font-size:13px;">
                        <i class="{{ genre_icon($genre->name) }}" style="margin-right:6px;"></i>{{ $genre->name }}
                    </a>
                @endforeach
            </div>
        </section>
    </div>
@endsection
