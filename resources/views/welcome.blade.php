@extends('layouts.user')

@section('content')
    <section class="page-reveal" style="margin-bottom: 60px; position: relative; border-radius: 24px; overflow: hidden; background: linear-gradient(135deg, var(--dark-green) 0%, var(--primary-green) 50%, var(--light-green) 100%); padding: 60px 40px; text-align: center;">
        <span class="floating-glow" style="width: 180px; height: 180px; top: -24px; left: -28px; background: rgba(255,255,255,0.18);"></span>
        <span class="floating-glow" style="width: 220px; height: 220px; right: 8%; bottom: -80px; background: rgba(15,20,25,0.22); animation-delay: -2.2s;"></span>
        <div style="position: relative; z-index: 2;">
            <h1 style="font-family: 'Crimson Pro', serif; font-size: 48px; font-weight: 700; color: white; margin-bottom: 16px; text-shadow: 0 4px 12px rgba(0,0,0,0.3);">
                Selamat Datang di Nokomi
            </h1>
            <p style="font-size: 18px; color: rgba(255,255,255,0.9); margin-bottom: 32px; max-width: 600px; margin-left: auto; margin-right: auto; line-height: 1.6;">
                Platform membaca novel dan comic online terbaik. Temukan karya menarik dari berbagai genre favoritmu.
            </p>
            <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
                <a href="#works" style="padding: 14px 32px; background: white; color: var(--primary-green); text-decoration: none; border-radius: 12px; font-weight: 700; font-size: 16px; box-shadow: 0 8px 25px rgba(0,0,0,0.2);">
                    <i class="bi bi-compass-fill" style="margin-right: 8px;"></i>Jelajahi Sekarang
                </a>
                @guest
                    <a href="{{ route('register') }}" style="padding: 14px 32px; background: transparent; border: 2px solid white; color: white; text-decoration: none; border-radius: 12px; font-weight: 700; font-size: 16px;">
                        <i class="bi bi-stars" style="margin-right: 8px;"></i>Daftar Gratis
                    </a>
                @endguest
            </div>
        </div>
        <div style="position: absolute; top: 20px; left: 20px; font-size: 80px; opacity: 0.12;"><i class="bi bi-book-half"></i></div>
        <div style="position: absolute; bottom: 20px; right: 20px; font-size: 80px; opacity: 0.12;"><i class="bi bi-palette-fill"></i></div>
    </section>

    <section id="works" class="page-reveal" data-reveal-delay="1" style="margin-bottom: 60px;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap; margin-bottom: 24px;">
            <div>
                <h2 style="font-family: 'Crimson Pro', serif; font-size: 28px; font-weight: 700; color: var(--text-primary); margin: 0 0 4px 0;">Karya Terbaru</h2>
                <p style="color: var(--text-secondary); margin: 0; font-size: 14px;">
                    @if($selectedGenre)
                        Menampilkan karya dengan genre {{ $selectedGenre->name }}
                    @else
                        Karya yang baru saja disetujui dan siap dibaca
                    @endif
                </p>
            </div>
            @if($selectedGenre)
                <a href="{{ route('home') }}" style="padding: 10px 16px; background: var(--bg-card); border: 1px solid var(--border-color); color: var(--text-primary); text-decoration: none; border-radius: 12px; font-size: 14px; font-weight: 700;">
                    Reset Genre
                </a>
            @endif
        </div>

        @if(isset($globalGenres) && $globalGenres->count() > 0)
            <div id="genreFilterList" style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 24px;">
                <a href="{{ route('home') }}" style="padding: 8px 14px; border-radius: 999px; text-decoration: none; font-size: 13px; font-weight: 700; background: {{ $selectedGenre ? 'var(--bg-card)' : 'linear-gradient(135deg, var(--primary-green), var(--light-green))' }}; color: white; border: 1px solid {{ $selectedGenre ? 'var(--border-color)' : 'transparent' }};">Semua</a>
                @foreach($globalGenres as $genre)
                    <a href="{{ route('home', ['genre' => $genre->id]) }}"
                       class="genre-chip {{ $loop->index >= 8 ? 'extra-genre' : '' }}"
                       style="padding: 8px 14px; border-radius: 999px; text-decoration: none; font-size: 13px; font-weight: 700; background: {{ $selectedGenre && $selectedGenre->id === $genre->id ? 'linear-gradient(135deg, var(--primary-green), var(--light-green))' : 'var(--bg-card)' }}; color: white; border: 1px solid {{ $selectedGenre && $selectedGenre->id === $genre->id ? 'transparent' : 'var(--border-color)' }};">
                        <i class="{{ genre_icon($genre->name) }}" style="margin-right: 6px;"></i>{{ $genre->name }}
                    </a>
                @endforeach
            </div>
            @if($globalGenres->count() > 8)
                <button type="button"
                        id="toggleGenresButton"
                        onclick="toggleGenres()"
                        style="padding: 10px 16px; margin-bottom: 24px; background: transparent; border: 1px solid var(--border-color); color: var(--text-primary); border-radius: 12px; font-size: 13px; font-weight: 700; cursor: pointer;">
                    Lihat Lebih Banyak Genre
                </button>
            @endif
        @endif

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 24px;">
            @forelse($works as $work)
                <a href="{{ route('works.public.show', $work) }}" class="work-card page-reveal" data-reveal-delay="{{ ($loop->index % 3) + 1 }}" style="display: block; text-decoration: none; background: var(--bg-card); border-radius: 16px; overflow: hidden; transition: all 0.4s; border: 1px solid var(--border-color); cursor: pointer;">
                    <div class="media-shell" style="position: relative; aspect-ratio: 3/4; background: linear-gradient(135deg, var(--dark-green), var(--primary-green)); overflow: hidden;">
                        <div class="media-skeleton"></div>
                        @if($work->cover)
                            <img src="{{ asset('storage/' . $work->cover) }}" alt="{{ $work->title }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease;" class="work-cover-img" data-media-loading loading="lazy">
                        @else
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 64px;">
                                <i class="{{ $work->type === 'novel' ? 'bi bi-book-half' : 'bi bi-palette-fill' }}"></i>
                            </div>
                        @endif
                        <div style="position: absolute; top: 12px; left: 12px; padding: 6px 14px; background: {{ $work->type === 'novel' ? 'rgba(59, 130, 246, 0.95)' : 'rgba(249, 115, 22, 0.95)' }}; color: white; border-radius: 20px; font-size: 11px; font-weight: 700;">
                            <i class="{{ $work->type === 'novel' ? 'bi bi-book-half' : 'bi bi-palette-fill' }}" style="margin-right: 6px;"></i>{{ $work->type === 'novel' ? 'NOVEL' : 'COMIC' }}
                        </div>
                        @if($work->chapters->count() > 0)
                            <div style="position: absolute; bottom: 12px; left: 12px; padding: 6px 12px; background: rgba(45, 139, 115, 0.95); color: white; border-radius: 12px; font-size: 12px; font-weight: 700;">
                                <i class="bi bi-collection-play-fill" style="margin-right: 6px;"></i>{{ $work->chapters->count() }} Ch
                            </div>
                        @endif
                    </div>

                    <div style="padding: 16px;">
                        <h3 style="font-size: 15px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 40px; line-height: 1.3;">{{ $work->title }}</h3>
                        @if($work->genres->count() > 0)
                            <div style="display: flex; gap: 6px; margin-bottom: 10px; flex-wrap: wrap;">
                                @foreach($work->genres->take(2) as $genre)
                                    <span style="padding: 4px 10px; background: rgba(45, 139, 115, 0.15); color: var(--light-green); border-radius: 8px; font-size: 11px; font-weight: 600; border: 1px solid rgba(45, 139, 115, 0.3);">
                                        <i class="{{ genre_icon($genre->name) }}" style="margin-right: 6px;"></i>{{ $genre->name }}
                                    </span>
                                @endforeach
                                @if($work->genres->count() > 2)
                                    <span style="padding: 4px 10px; color: var(--text-secondary); font-size: 11px; font-weight: 600;">+{{ $work->genres->count() - 2 }}</span>
                                @endif
                            </div>
                        @endif
                        <div style="display: flex; align-items: center; justify-content: space-between; color: var(--text-secondary); font-size: 13px;">
                            <div style="display: flex; align-items: center; gap: 4px;"><i class="bi bi-star-fill" style="color: #fbbf24;"></i><span style="font-weight: 600; color: var(--text-primary);">{{ number_format(rand(40, 50) / 10, 1) }}</span></div>
                            <div style="font-size: 11px;">{{ $work->display_author }}</div>
                        </div>
                    </div>
                </a>
            @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px; background: var(--bg-card); border-radius: 16px; border: 1px dashed var(--border-color);">
                    <div style="font-size: 64px; margin-bottom: 16px;"><i class="bi bi-collection"></i></div>
                    <h3 style="color: var(--text-primary); margin-bottom: 8px;">Katalog Belum Terisi</h3>
                    <p style="color: var(--text-secondary);">@if($selectedGenre) Belum ada judul yang cocok dengan pilihan genre ini. @else Temukan lebih banyak judul pilihan di katalog Nokomi. @endif</p>
                </div>
            @endforelse
        </div>

        @if($works->hasPages())
            <div class="pagination-wrap">
                <div class="pagination-meta">
                    Showing {{ $works->firstItem() }} to {{ $works->lastItem() }} of {{ $works->total() }} results
                </div>
                <div class="pagination-links">
                    <a href="{{ $works->previousPageUrl() ?: '#' }}" class="pagination-link {{ $works->onFirstPage() ? 'disabled' : '' }}">
                        &laquo; Previous
                    </a>
                    <a href="{{ $works->nextPageUrl() ?: '#' }}" class="pagination-link {{ $works->hasMorePages() ? '' : 'disabled' }}">
                        Next &raquo;
                    </a>
                </div>
            </div>
        @endif
    </section>

    <style>
        .extra-genre {
            display: none;
        }

        .genres-expanded .extra-genre {
            display: inline-flex;
        }

        .work-card:hover { transform: translateY(-8px); box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4); border-color: var(--primary-green); }
        .work-card:hover .work-cover-img { transform: scale(1.06); }
    </style>

    <script>
        function toggleGenres() {
            const genreList = document.getElementById('genreFilterList');
            const toggleButton = document.getElementById('toggleGenresButton');

            if (!genreList || !toggleButton) {
                return;
            }

            genreList.classList.toggle('genres-expanded');
            toggleButton.textContent = genreList.classList.contains('genres-expanded')
                ? 'Tampilkan Lebih Sedikit'
                : 'Lihat Lebih Banyak Genre';
        }
    </script>
@endsection


