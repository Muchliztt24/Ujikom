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
                        @if($work->chapters->count() > 0)
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
                        <div style="display:flex; justify-content:space-between; gap:12px;"><span style="color: var(--text-secondary); font-size: 13px;">Chapters</span><span style="font-weight: 700; color: var(--light-green);">{{ $work->chapters->count() }}</span></div>
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
                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary-green), var(--light-green)); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 16px;">{{ strtoupper(substr($work->user->name, 0, 1)) }}</div>
                    <div>
                        <div style="font-size: 13px; color: var(--text-secondary);">Dibuat oleh</div>
                        <div style="font-weight: 600; color: var(--text-primary);">{{ $work->user->name }}</div>
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
                        <h3 style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0;">Daftar Chapter ({{ $work->chapters->count() }})</h3>
                        <div style="display: flex; gap: 8px;">
                            <button onclick="sortChapters('asc')" id="sortAsc" style="padding: 8px 16px; background: var(--primary-green); color: white; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;"><i class="bi bi-sort-up" style="margin-right: 6px;"></i>Terlama</button>
                            <button onclick="sortChapters('desc')" id="sortDesc" style="padding: 8px 16px; background: var(--bg-hover); color: var(--text-secondary); border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;"><i class="bi bi-sort-down" style="margin-right: 6px;"></i>Terbaru</button>
                        </div>
                    </div>

                    <div id="chaptersList" style="display: flex; flex-direction: column; gap: 12px;">
                        @forelse($work->chapters as $chapter)
                            <a href="{{ route('works.chapters.read', [$work, $chapter]) }}" class="chapter-item" data-date="{{ $chapter->created_at->timestamp }}" style="display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; background: var(--bg-main); border: 1px solid var(--border-color); border-radius: 12px; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.background='var(--bg-hover)'; this.style.borderColor='var(--primary-green)'; this.style.transform='translateX(8px)'" onmouseout="this.style.background='var(--bg-main)'; this.style.borderColor='var(--border-color)'; this.style.transform='translateX(0)'">
                                <div style="flex: 1;">
                                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 6px; flex-wrap: wrap;">
                                        <span style="font-weight: 700; color: var(--light-green); font-size: 15px;">Chapter {{ $chapter->chapter_number }}</span>
                                        @if($continueChapter && $continueChapter->id === $chapter->id)
                                            <span style="padding: 4px 10px; background: rgba(72,201,176,0.2); color: #7eead5; border-radius: 6px; font-size: 11px; font-weight: 700;">LANJUTKAN</span>
                                        @endif
                                        @if($chapter->created_at->diffInDays() < 7)
                                            <span style="padding: 4px 10px; background: rgba(16, 185, 129, 0.2); color: #10b981; border-radius: 6px; font-size: 11px; font-weight: 700;">NEW</span>
                                        @endif
                                    </div>
                                    @if($chapter->title)
                                        <div style="color: var(--text-primary); font-weight: 600; margin-bottom: 4px;">{{ $chapter->title }}</div>
                                    @endif
                                    <div style="color: var(--text-secondary); font-size: 13px;">{{ $chapter->created_at->diffForHumans() }}</div>
                                </div>
                                <div style="color: var(--text-secondary); font-size: 18px;"><i class="bi bi-arrow-right"></i></div>
                            </a>
                        @empty
                            <div style="text-align: center; padding: 60px 20px; background: var(--bg-main); border-radius: 12px; border: 1px dashed var(--border-color);">
                                <div style="font-size: 48px; margin-bottom: 12px;"><i class="bi bi-journal-x"></i></div>
                                <div style="color: var(--text-secondary); font-size: 15px;">Daftar chapter belum tersedia</div>
                            </div>
                        @endforelse
                    </div>
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
        @media (max-width: 968px) {
            div[style*="grid-template-columns: 300px 1fr"] { grid-template-columns: 1fr !important; }
            div[style*="position: sticky"] { position: relative !important; top: 0 !important; }
            h1 { font-size: 32px !important; }
        }

        @media (max-width: 640px) {
            div[style*="padding: 40px"] { padding: 24px !important; }
            h1 { font-size: 28px !important; }
        }
    </style>

    <script>
        function sortChapters(order) {
            const list = document.getElementById('chaptersList');
            const items = Array.from(document.querySelectorAll('.chapter-item'));
            document.getElementById('sortAsc').style.background = order === 'asc' ? 'var(--primary-green)' : 'var(--bg-hover)';
            document.getElementById('sortAsc').style.color = order === 'asc' ? 'white' : 'var(--text-secondary)';
            document.getElementById('sortAsc').style.border = order === 'asc' ? 'none' : '1px solid var(--border-color)';
            document.getElementById('sortDesc').style.background = order === 'desc' ? 'var(--primary-green)' : 'var(--bg-hover)';
            document.getElementById('sortDesc').style.color = order === 'desc' ? 'white' : 'var(--text-secondary)';
            document.getElementById('sortDesc').style.border = order === 'desc' ? 'none' : '1px solid var(--border-color)';
            items.sort((a, b) => order === 'asc' ? parseInt(a.dataset.date) - parseInt(b.dataset.date) : parseInt(b.dataset.date) - parseInt(a.dataset.date));
            list.innerHTML = '';
            items.forEach(item => list.appendChild(item));
        }
    </script>
@endsection





