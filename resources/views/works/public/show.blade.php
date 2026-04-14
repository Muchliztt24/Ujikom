@extends('layouts.user')

@section('content')
    <div style="margin-bottom: 24px;">
        <a href="{{ route('home') }}" style="display: inline-flex; align-items: center; gap: 8px; color: var(--text-secondary); text-decoration: none; padding: 8px 16px; border-radius: 8px; transition: all 0.3s;" onmouseover="this.style.background='var(--bg-card)'; this.style.color='var(--light-green)'" onmouseout="this.style.background='transparent'; this.style.color='var(--text-secondary)'">
            <span>&larr;</span> Kembali ke Beranda
        </a>
    </div>

    <div class="page-reveal" style="background: var(--bg-card); border-radius: 20px; overflow: hidden; border: 1px solid var(--border-color); margin-bottom: 28px;">
        <div style="display: grid; grid-template-columns: 300px 1fr; gap: 40px; padding: 40px;">
            <div>
                <div style="position: sticky; top: 100px;">
                    <div class="media-shell" style="position: relative; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 30px rgba(0,0,0,0.4); margin-bottom: 20px;">
                        <div class="media-skeleton"></div>
                        @if($work->cover)
                            <img src="{{ asset('storage/' . $work->cover) }}" alt="{{ $work->title }}" style="width: 100%; aspect-ratio: 3/4; object-fit: cover;" data-media-loading>
                        @else
                            <div style="width: 100%; aspect-ratio: 3/4; background: linear-gradient(135deg, var(--dark-green), var(--primary-green)); display: flex; align-items: center; justify-content: center; font-size: 72px; color: white;">
                                <i class="{{ $work->type === 'novel' ? 'bi bi-book-half' : 'bi bi-palette-fill' }}"></i>
                            </div>
                        @endif

                        <div style="position: absolute; top: 12px; left: 12px; padding: 8px 16px; background: {{ $work->type === 'novel' ? 'rgba(59, 130, 246, 0.95)' : 'rgba(249, 115, 22, 0.95)' }}; color: white; border-radius: 20px; font-size: 12px; font-weight: 700;">
                            <i class="{{ $work->type === 'novel' ? 'bi bi-book-half' : 'bi bi-palette-fill' }}" style="margin-right: 6px;"></i>
                            {{ $work->type === 'novel' ? 'NOVEL' : 'COMIC' }}
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @if($chaptersCount > 0)
                            <a href="{{ $continueChapter ? route('works.chapters.read', [$work, $continueChapter]) : route('works.read', $work) }}" style="padding: 14px 20px; background: linear-gradient(135deg, var(--primary-green), var(--light-green)); color: white; text-decoration: none; border-radius: 12px; font-weight: 700; text-align: center; box-shadow: 0 4px 15px rgba(45, 139, 115, 0.3);">
                                <i class="bi bi-play-circle-fill" style="margin-right: 8px;"></i>{{ $continueChapter ? 'Lanjutkan Membaca' : 'Mulai Baca' }}
                            </a>
                        @endif

                        @auth
                            @if($bookmark)
                                <form action="{{ route('bookmarks.destroy', $work) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="width: 100%; padding: 14px 20px; background: var(--bg-hover); color: var(--light-green); border: 1px solid rgba(72,201,176,0.4); border-radius: 12px; font-weight: 700; cursor: pointer;">
                                        <i class="bi bi-bookmark-check-fill" style="margin-right: 8px;"></i>Tersimpan di Bookmark
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('bookmarks.store', $work) }}" method="POST">
                                    @csrf
                                    @if($continueChapter)
                                        <input type="hidden" name="last_chapter_read" value="{{ $continueChapter->chapter_number }}">
                                    @endif
                                    <button type="submit" style="width: 100%; padding: 14px 20px; background: var(--bg-hover); color: var(--text-primary); border: 1px solid var(--border-color); border-radius: 12px; font-weight: 700; cursor: pointer;">
                                        <i class="bi bi-bookmark-plus-fill" style="margin-right: 8px;"></i>Tambah Bookmark
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('pages.history') }}" style="padding: 14px 20px; background: var(--bg-hover); color: var(--text-primary); border: 1px solid var(--border-color); border-radius: 12px; font-weight: 600; text-decoration: none; text-align: center;">
                                <i class="bi bi-clock-history" style="margin-right: 8px;"></i>Lihat Riwayat Baca
                            </a>
                        @else
                            <a href="{{ route('login') }}" style="padding: 14px 20px; background: var(--bg-hover); color: var(--text-primary); border: 1px solid var(--border-color); border-radius: 12px; font-weight: 600; text-decoration: none; text-align: center;">
                                <i class="bi bi-box-arrow-in-right" style="margin-right: 8px;"></i>Login untuk simpan progres
                            </a>
                        @endauth
                    </div>

                    <div style="margin-top: 20px; padding: 16px; background: var(--bg-main); border-radius: 12px; display:grid; gap: 12px;">
                        <div style="display:flex; justify-content:space-between; gap:12px;"><span style="color: var(--text-secondary); font-size: 13px;">Chapters</span><span style="font-weight: 700; color: var(--light-green);">{{ $chaptersCount }}</span></div>
                        <div style="display:flex; justify-content:space-between; gap:12px;"><span style="color: var(--text-secondary); font-size: 13px;">Komentar</span><span style="font-weight: 700; color: var(--text-primary);">{{ $recentComments->count() }}</span></div>
                        @if($continueChapter)
                            <div style="display:flex; justify-content:space-between; gap:12px;"><span style="color: var(--text-secondary); font-size: 13px;">Progress</span><span style="font-weight: 700; color: var(--text-primary);">Chapter {{ $continueChapter->chapter_number }}</span></div>
                        @endif
                    </div>
                </div>
            </div>

            <div>
                <h1 style="font-family: 'Crimson Pro', serif; font-size: 42px; font-weight: 700; color: var(--text-primary); margin-bottom: 12px; line-height: 1.2;">{{ $work->title }}</h1>

                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary-green), var(--light-green)); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 16px;">{{ strtoupper(substr($work->display_author, 0, 1)) }}</div>
                    <div>
                        <div style="font-size: 13px; color: var(--text-secondary);">Author Asli</div>
                        <div style="font-weight: 600; color: var(--text-primary);">{{ $work->display_author }}</div>
                        <div style="font-size: 12px; color: var(--text-secondary); margin-top: 3px;">Diunggah oleh {{ $work->user->name }}</div>
                    </div>
                </div>

                @if($work->genres->count() > 0)
                    <div style="margin-bottom: 24px;">
                        <div style="font-size: 13px; color: var(--text-secondary); margin-bottom: 8px; font-weight: 600;">GENRE</div>
                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                            @foreach($work->genres as $genre)
                                <span style="padding: 8px 16px; background: rgba(45, 139, 115, 0.15); color: var(--light-green); border-radius: 10px; font-size: 13px; font-weight: 600; border: 1px solid rgba(45, 139, 115, 0.3);">{{ $genre->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($work->description)
                    <div style="margin-bottom: 32px;">
                        <h3 style="font-size: 18px; font-weight: 700; color: var(--text-primary); margin-bottom: 12px;">Sinopsis</h3>
                        <div style="color: var(--text-secondary); line-height: 1.8; font-size: 15px; white-space: pre-line;">{{ $work->description }}</div>
                    </div>
                @endif

                @if($continueChapter)
                    <div style="margin-bottom: 28px; padding: 16px 18px; border-radius: 14px; background: linear-gradient(135deg, rgba(45,139,115,0.16), rgba(72,201,176,0.08)); border: 1px solid rgba(72,201,176,0.18);">
                        <div style="font-weight: 700; color: var(--text-primary); margin-bottom: 6px;">Lanjutkan dari progres terakhir</div>
                        <div style="color: var(--text-secondary); font-size: 14px;">Terakhir dibuka di Chapter {{ $continueChapter->chapter_number }}{{ $continueChapter->title ? ' • ' . $continueChapter->title : '' }}</div>
                    </div>
                @endif

                <div id="chapters">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                        <h3 style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0;">Daftar Chapter ({{ $chaptersCount }})</h3>
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('works.public.show', ['work' => $work->id, 'sort' => 'asc']) }}#chapters" style="padding: 8px 16px; background: {{ $sort === 'asc' ? 'var(--primary-green)' : 'var(--bg-hover)' }}; color: {{ $sort === 'asc' ? 'white' : 'var(--text-secondary)' }}; border: {{ $sort === 'asc' ? 'none' : '1px solid var(--border-color)' }}; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none;"><i class="bi bi-sort-up" style="margin-right: 6px;"></i>Terlama</a>
                            <a href="{{ route('works.public.show', ['work' => $work->id, 'sort' => 'desc']) }}#chapters" style="padding: 8px 16px; background: {{ $sort === 'desc' ? 'var(--primary-green)' : 'var(--bg-hover)' }}; color: {{ $sort === 'desc' ? 'white' : 'var(--text-secondary)' }}; border: {{ $sort === 'desc' ? 'none' : '1px solid var(--border-color)' }}; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none;"><i class="bi bi-sort-down" style="margin-right: 6px;"></i>Terbaru</a>
                        </div>
                    </div>

                    <div style="display:flex; justify-content:space-between; gap:12px; align-items:center; margin-bottom: 14px; flex-wrap: wrap;">
                        <div style="color: var(--text-secondary); font-size: 13px;">
                            Menampilkan {{ $chapters->firstItem() ?? 0 }}-{{ $chapters->lastItem() ?? 0 }} dari {{ $chapters->total() }} chapter
                        </div>
                        @if($work->type === 'comic')
                            <div style="color: var(--text-secondary); font-size: 13px;">Mode grid komik aktif agar daftar chapter tetap ringan.</div>
                        @endif
                    </div>

                    <div id="chaptersList" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 12px;">
                        @forelse($chapters as $chapter)
                            <a href="{{ route('works.chapters.read', [$work, $chapter]) }}" class="chapter-item" style="display: flex; flex-direction: column; justify-content: space-between; min-height: 120px; padding: 16px; background: var(--bg-main); border: 1px solid {{ $continueChapter && $continueChapter->id === $chapter->id ? 'rgba(72,201,176,0.45)' : 'var(--border-color)' }}; border-radius: 14px; text-decoration: none; transition: transform 0.2s ease, border-color 0.2s ease, background 0.2s ease; box-shadow: 0 6px 20px rgba(0,0,0,0.12);" onmouseover="this.style.background='var(--bg-hover)'; this.style.borderColor='var(--primary-green)'; this.style.transform='translateY(-4px)'" onmouseout="this.style.background='var(--bg-main)'; this.style.borderColor='{{ $continueChapter && $continueChapter->id === $chapter->id ? 'rgba(72,201,176,0.45)' : 'var(--border-color)' }}'; this.style.transform='translateY(0)'">
                                <div style="display:flex; justify-content:space-between; gap:8px; align-items:flex-start; margin-bottom: 12px;">
                                    <span style="display:inline-flex; align-items:center; justify-content:center; min-width: 46px; height: 46px; padding: 0 10px; border-radius: 12px; background: rgba(45, 139, 115, 0.16); color: var(--light-green); font-weight: 800; font-size: 18px; line-height: 1;">
                                        {{ $chapter->chapter_number }}
                                    </span>
                                    @if($continueChapter && $continueChapter->id === $chapter->id)
                                        <span style="padding: 4px 8px; background: rgba(72,201,176,0.2); color: #7eead5; border-radius: 999px; font-size: 10px; font-weight: 700;">LANJUT</span>
                                    @endif
                                </div>
                                <div>
                                    <div style="font-size: 12px; letter-spacing: 0.08em; text-transform: uppercase; color: var(--text-secondary); margin-bottom: 6px;">Chapter</div>
                                    <div style="color: var(--text-primary); font-weight: 700; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $chapter->title ?: 'Chapter ' . $chapter->chapter_number }}
                                    </div>
                                </div>
                                <div style="display:flex; justify-content:space-between; gap:8px; align-items:center; margin-top: 12px;">
                                    <div style="color: var(--text-secondary); font-size: 12px;">{{ $chapter->created_at->diffForHumans() }}</div>
                                    <div style="color: var(--text-secondary); font-size: 16px;"><i class="bi bi-arrow-right"></i></div>
                                </div>
                            </a>
                        @empty
                            <div style="text-align: center; padding: 60px 20px; background: var(--bg-main); border-radius: 12px; border: 1px dashed var(--border-color);">
                                <div style="font-size: 48px; margin-bottom: 12px;"><i class="bi bi-journal-x"></i></div>
                                <div style="color: var(--text-secondary); font-size: 15px;">Daftar chapter belum tersedia</div>
                            </div>
                        @endforelse
                    </div>

                    @if($chapters->hasPages())
                        <div class="chapter-pagination">
                            <div class="chapter-pagination__meta">
                                Halaman {{ $chapters->currentPage() }} dari {{ $chapters->lastPage() }}
                            </div>
                            <div class="chapter-pagination__links">
                                @if($chapters->onFirstPage())
                                    <span class="chapter-pagination__link is-disabled">Sebelumnya</span>
                                @else
                                    <a href="{{ $chapters->previousPageUrl() }}#chapters" class="chapter-pagination__link">Sebelumnya</a>
                                @endif

                                @php
                                    $startPage = max(1, $chapters->currentPage() - 2);
                                    $endPage = min($chapters->lastPage(), $chapters->currentPage() + 2);
                                @endphp

                                @if($startPage > 1)
                                    <a href="{{ $chapters->url(1) }}#chapters" class="chapter-pagination__link">1</a>
                                    @if($startPage > 2)
                                        <span class="chapter-pagination__dots">...</span>
                                    @endif
                                @endif

                                @for($page = $startPage; $page <= $endPage; $page++)
                                    @if($page === $chapters->currentPage())
                                        <span class="chapter-pagination__link is-active">{{ $page }}</span>
                                    @else
                                        <a href="{{ $chapters->url($page) }}#chapters" class="chapter-pagination__link">{{ $page }}</a>
                                    @endif
                                @endfor

                                @if($endPage < $chapters->lastPage())
                                    @if($endPage < $chapters->lastPage() - 1)
                                        <span class="chapter-pagination__dots">...</span>
                                    @endif
                                    <a href="{{ $chapters->url($chapters->lastPage()) }}#chapters" class="chapter-pagination__link">{{ $chapters->lastPage() }}</a>
                                @endif

                                @if($chapters->hasMorePages())
                                    <a href="{{ $chapters->nextPageUrl() }}#chapters" class="chapter-pagination__link">Berikutnya</a>
                                @else
                                    <span class="chapter-pagination__link is-disabled">Berikutnya</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <section class="page-reveal" data-reveal-delay="1" style="background: var(--bg-card); border-radius: 20px; border: 1px solid var(--border-color); padding: 28px; display: grid; gap: 18px;">
        <div style="display: flex; justify-content: space-between; gap: 12px; align-items: center; flex-wrap: wrap;">
            <div>
                <h2 style="font-family: 'Crimson Pro', serif; font-size: 30px; margin: 0 0 6px;">Komentar Pembaca</h2>
                <p style="color: var(--text-secondary); margin: 0;">Komentar terbaru dari chapter-chapter karya ini.</p>
            </div>
            <span style="padding: 8px 12px; border-radius: 999px; background: rgba(255,255,255,0.06); color: var(--text-primary); font-size: 13px; font-weight: 700;">{{ $recentComments->count() }} komentar terbaru</span>
        </div>

        <div style="display:grid; gap: 14px;">
            @forelse($recentComments as $comment)
                <div style="padding: 18px; border-radius: 16px; background: var(--bg-main); border: 1px solid var(--border-color);">
                    <div style="display:flex; justify-content:space-between; gap:12px; align-items:start; margin-bottom:8px; flex-wrap: wrap;">
                        <div>
                            <div style="font-weight:700; color: var(--text-primary);">{{ $comment->user->name }}</div>
                            <div style="color: var(--text-secondary); font-size: 13px; margin-top: 4px;">Chapter {{ $comment->chapter->chapter_number }}{{ $comment->chapter->title ? ' • ' . $comment->chapter->title : '' }}</div>
                        </div>
                        <div style="color: var(--text-secondary); font-size: 12px;">{{ $comment->created_at->diffForHumans() }}</div>
                    </div>
                    <div style="color: var(--text-secondary); line-height: 1.8; white-space: pre-line;">{{ $comment->content }}</div>
                </div>
            @empty
                <div style="padding: 32px 20px; text-align:center; border-radius: 16px; background: var(--bg-main); border: 1px dashed var(--border-color); color: var(--text-secondary);">
                    <div style="font-size: 42px; margin-bottom: 10px;"><i class="bi bi-chat-square-text"></i></div>
                    Komentar pembaca akan tampil di bagian ini.
                </div>
            @endforelse
        </div>
    </section>

    <style>
        .chapter-pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
            margin-top: 22px;
            padding-top: 18px;
            border-top: 1px solid var(--border-color);
        }

        .chapter-pagination__meta {
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 600;
        }

        .chapter-pagination__links {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .chapter-pagination__link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
            padding: 10px 14px;
            border-radius: 10px;
            background: var(--bg-main);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            line-height: 1;
            transition: all 0.2s ease;
        }

        .chapter-pagination__link:hover {
            background: var(--bg-hover);
            border-color: var(--primary-green);
            color: white;
        }

        .chapter-pagination__link.is-active {
            background: linear-gradient(135deg, var(--primary-green), var(--light-green));
            border-color: transparent;
            color: white;
        }

        .chapter-pagination__link.is-disabled {
            opacity: 0.45;
            pointer-events: none;
        }

        .chapter-pagination__dots {
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 700;
            padding: 0 2px;
        }

        @media (max-width: 968px) {
            div[style*="grid-template-columns: 300px 1fr"] { grid-template-columns: 1fr !important; }
            div[style*="position: sticky"] { position: relative !important; top: 0 !important; }
            h1 { font-size: 32px !important; }
        }

        @media (max-width: 640px) {
            div[style*="padding: 40px"] { padding: 24px !important; }
            h1 { font-size: 28px !important; }
            #chaptersList { grid-template-columns: repeat(2, minmax(0, 1fr)) !important; }
            .chapter-pagination { align-items: stretch; }
            .chapter-pagination__links { width: 100%; }
        }
    </style>
@endsection





