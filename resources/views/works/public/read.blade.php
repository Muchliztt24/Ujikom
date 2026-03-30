@extends('layouts.user')

@section('content')
    <div style="display: flex; flex-direction: column; gap: 24px;">
        <div style="height: 6px; background: rgba(255,255,255,0.06); border-radius: 999px; overflow: hidden;">
            <div id="readerProgressBar" style="width: 0%; height: 100%; background: linear-gradient(135deg, var(--primary-green), var(--light-green));"></div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
            <div>
                <a href="{{ route('works.public.show', $work) }}" style="display: inline-flex; align-items: center; gap: 8px; color: var(--text-secondary); text-decoration: none; margin-bottom: 10px;">
                    <span>&larr;</span> Kembali ke Detail
                </a>
                <div style="font-size: 13px; color: var(--light-green); font-weight: 700; letter-spacing: 1px; text-transform: uppercase;">{{ $work->type === 'comic' ? 'Comic Reader' : 'Novel Reader' }}</div>
                <h1 style="font-family: 'Crimson Pro', serif; font-size: 38px; line-height: 1.2; margin: 6px 0 8px;">{{ $work->title }}</h1>
                <div style="color: var(--text-secondary); font-size: 15px; display:flex; gap: 8px; flex-wrap: wrap; align-items:center;">
                    <span>Chapter {{ $chapter->chapter_number }}@if($chapter->title) • {{ $chapter->title }} @endif</span>
                    <span style="padding: 4px 10px; border-radius: 999px; background: rgba(255,255,255,0.05);">Estimasi {{ $estimatedReadMinutes }} menit</span>
                </div>
            </div>

            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                @if($previousChapter)
                    <a href="{{ route('works.chapters.read', [$work, $previousChapter]) }}" style="padding: 9px 13px; background: var(--bg-hover); color: var(--text-primary); border: 1px solid var(--border-color); border-radius: 9px; text-decoration: none; font-weight: 600; font-size: 13px; line-height: 1.2;">&larr; Chapter Sebelumnya</a>
                @endif
                @if($nextChapter)
                    <a href="{{ route('works.chapters.read', [$work, $nextChapter]) }}" style="padding: 9px 13px; background: linear-gradient(135deg, var(--primary-green), var(--light-green)); color: white; border-radius: 9px; text-decoration: none; font-weight: 700; font-size: 13px; line-height: 1.2;">Chapter Selanjutnya &rarr;</a>
                @endif
            </div>
        </div>

        @if($work->type === 'novel')
            <div style="display:flex; gap: 10px; flex-wrap: wrap; align-items:center; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 14px 16px;">
                <span style="font-size: 13px; color: var(--text-secondary); font-weight: 700;">Mode Baca</span>
                <button type="button" class="reader-theme-btn" data-theme="light" style="padding: 8px 12px; border-radius: 10px; border: 1px solid var(--border-color); background: #f8f5ef; color: #1f2937; cursor: pointer;">Light</button>
                <button type="button" class="reader-theme-btn" data-theme="sepia" style="padding: 8px 12px; border-radius: 10px; border: 1px solid var(--border-color); background: #f4ecd8; color: #5b4636; cursor: pointer;">Sepia</button>
                <button type="button" class="reader-theme-btn" data-theme="dark" style="padding: 8px 12px; border-radius: 10px; border: 1px solid var(--border-color); background: #111827; color: #f3f4f6; cursor: pointer;">Dark</button>
                <button type="button" id="fontDecrease" style="padding: 8px 12px; border-radius: 10px; border: 1px solid var(--border-color); background: var(--bg-main); color: var(--text-primary); cursor: pointer;">A-</button>
                <button type="button" id="fontIncrease" style="padding: 8px 12px; border-radius: 10px; border: 1px solid var(--border-color); background: var(--bg-main); color: var(--text-primary); cursor: pointer;">A+</button>
            </div>
        @endif

        <div style="display: grid; grid-template-columns: minmax(0, 1fr) 300px; gap: 24px; align-items: start;">
            <div style="display:grid; gap: 24px;">
                @if($work->type === 'comic')
                    <div id="comicReader" style="background: #0b0f14; border: 1px solid var(--border-color); border-radius: 20px; padding: 20px;">
                        @if($chapter->images->count() > 0)
                            <div style="display: flex; flex-direction: column; gap: 16px;">
                                @foreach($chapter->images as $image)
                                    <img src="{{ asset('storage/' . $image->image_url) }}" alt="{{ $work->title }} - Chapter {{ $chapter->chapter_number }} - Page {{ $image->page_number }}" loading="lazy" style="width: 100%; border-radius: 14px; display: block; background: var(--bg-card);">
                                @endforeach
                            </div>
                        @else
                            <div style="padding: 60px 24px; text-align: center; color: var(--text-secondary);">Belum ada gambar untuk chapter ini.</div>
                        @endif
                    </div>
                @else
                    <article id="novelReader" style="background: #f8f5ef; color: #1f2937; border-radius: 20px; padding: 40px 32px; border: 1px solid rgba(255,255,255,0.08); box-shadow: 0 12px 40px rgba(0,0,0,0.2);">
                        <div style="max-width: 760px; margin: 0 auto;">
                            <h2 style="font-family: 'Crimson Pro', serif; font-size: 32px; margin-bottom: 24px; color: inherit;">Chapter {{ $chapter->chapter_number }} @if($chapter->title) : {{ $chapter->title }} @endif</h2>
                            @if(filled($chapter->text_content))
                                <div id="novelContent" style="font-family: 'Crimson Pro', serif; font-size: 22px; line-height: 1.9; white-space: pre-line;">{{ $chapter->text_content }}</div>
                            @else
                                <div style="padding: 32px 24px; background: rgba(17,24,39,0.06); border-radius: 14px; color: #4b5563;">Isi chapter novel ini belum tersedia.</div>
                            @endif
                        </div>
                    </article>
                @endif

                <section style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 18px; padding: 22px;">
                    <div style="display:flex; justify-content:space-between; gap:12px; align-items:center; flex-wrap:wrap; margin-bottom:16px;">
                        <div>
                            <h3 style="font-size: 22px; margin: 0 0 4px;">Komentar Chapter</h3>
                            <div style="color: var(--text-secondary); font-size: 14px;">{{ $chapter->comments->count() }} komentar</div>
                        </div>
                    </div>

                    @auth
                        <form action="{{ route('comments.store', $chapter) }}" method="POST" style="display:grid; gap: 10px; margin-bottom: 20px;">
                            @csrf
                            <textarea name="content" rows="4" placeholder="Tulis komentar kamu tentang chapter ini..." style="width: 100%; padding: 14px 16px; border-radius: 14px; border: 1px solid var(--border-color); background: var(--bg-main); color: var(--text-primary); resize: vertical;">{{ old('content') }}</textarea>
                            @error('content')<div style="color:#fca5a5; font-size:13px;">{{ $message }}</div>@enderror
                            <div><button type="submit" style="padding: 12px 18px; border: none; border-radius: 12px; background: linear-gradient(135deg, var(--primary-green), var(--light-green)); color: white; font-weight: 700; cursor: pointer;">Kirim Komentar</button></div>
                        </form>
                    @else
                        <div style="margin-bottom: 20px; padding: 16px; background: rgba(255,255,255,0.04); border-radius: 14px; color: var(--text-secondary);">Login untuk ikut berdiskusi di chapter ini.</div>
                    @endauth

                    <div style="display:grid; gap: 14px;">
                        @forelse($chapter->comments as $comment)
                            <div style="padding: 16px; border-radius: 14px; background: var(--bg-main); border: 1px solid var(--border-color);">
                                <div style="display:flex; justify-content:space-between; gap:12px; align-items:start; margin-bottom:8px;">
                                    <div>
                                        <div style="font-weight: 700; color: var(--text-primary);">{{ $comment->user->name }}</div>
                                        <div style="font-size: 12px; color: var(--text-secondary);">{{ $comment->created_at->diffForHumans() }}</div>
                                    </div>
                                    @auth
                                        @if($comment->user_id === auth()->id())
                                            <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="padding: 6px 10px; border-radius: 10px; border: 1px solid rgba(239,68,68,0.2); background: rgba(239,68,68,0.08); color: #fca5a5; cursor: pointer;">Hapus</button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                                <div style="color: var(--text-secondary); line-height: 1.8; white-space: pre-line;">{{ $comment->content }}</div>
                            </div>
                        @empty
                            <div style="padding: 18px; border-radius: 14px; background: rgba(255,255,255,0.03); color: var(--text-secondary);">Belum ada komentar untuk chapter ini.</div>
                        @endforelse
                    </div>
                </section>
            </div>

            <aside style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 18px; padding: 20px; position: sticky; top: 90px;">
                <div style="font-size: 12px; color: var(--text-secondary); font-weight: 700; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Daftar Chapter</div>
                <div style="font-size: 18px; font-weight: 700; margin-bottom: 16px;">{{ $chapters->count() }} Chapter</div>

                <div style="display: flex; flex-direction: column; gap: 10px; max-height: 70vh; overflow-y: auto; padding-right: 4px;">
                    @foreach($chapters as $item)
                        <a href="{{ route('works.chapters.read', [$work, $item]) }}" style="padding: 14px 16px; border-radius: 12px; text-decoration: none; border: 1px solid {{ $item->id === $chapter->id ? 'transparent' : 'var(--border-color)' }}; background: {{ $item->id === $chapter->id ? 'linear-gradient(135deg, var(--primary-green), var(--light-green))' : 'var(--bg-main)' }}; color: {{ $item->id === $chapter->id ? 'white' : 'var(--text-primary)' }};">
                            <div style="font-weight: 700; font-size: 14px;">Chapter {{ $item->chapter_number }}</div>
                            @if($item->title)
                                <div style="font-size: 13px; margin-top: 4px; opacity: 0.9;">{{ $item->title }}</div>
                            @endif
                        </a>
                    @endforeach
                </div>
            </aside>
        </div>
    </div>

    <style>
        @media (max-width: 1024px) {
            div[style*="grid-template-columns: minmax(0, 1fr) 300px"] { grid-template-columns: 1fr !important; }
            aside[style*="position: sticky"] { position: relative !important; top: 0 !important; }
        }

        @media (max-width: 640px) {
            h1 { font-size: 30px !important; }
            article[style*="padding: 40px 32px"] { padding: 28px 20px !important; }
        }
    </style>

    <script>
        const progressBar = document.getElementById('readerProgressBar');
        function updateReaderProgress() {
            const scrollTop = window.scrollY;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const progress = docHeight > 0 ? Math.min(100, Math.max(0, (scrollTop / docHeight) * 100)) : 0;
            if (progressBar) progressBar.style.width = progress + '%';
        }
        window.addEventListener('scroll', updateReaderProgress);
        updateReaderProgress();

        const novelReader = document.getElementById('novelReader');
        const novelContent = document.getElementById('novelContent');
        const storageKey = 'nokomi-reader-settings';
        const defaultSettings = { theme: 'light', fontSize: 22 };

        function applyReaderSettings() {
            if (!novelReader || !novelContent) return;
            const raw = localStorage.getItem(storageKey);
            const settings = raw ? JSON.parse(raw) : defaultSettings;
            const themes = {
                light: { background: '#f8f5ef', color: '#1f2937' },
                sepia: { background: '#f2e9d8', color: '#4b3b2b' },
                dark: { background: '#111827', color: '#f3f4f6' },
            };
            const activeTheme = themes[settings.theme] || themes.light;
            novelReader.style.background = activeTheme.background;
            novelReader.style.color = activeTheme.color;
            novelContent.style.fontSize = settings.fontSize + 'px';
            document.querySelectorAll('.reader-theme-btn').forEach((button) => {
                button.style.outline = button.dataset.theme === settings.theme ? '2px solid var(--light-green)' : 'none';
            });
        }

        function saveReaderSettings(nextSettings) {
            localStorage.setItem(storageKey, JSON.stringify(nextSettings));
            applyReaderSettings();
        }

        document.querySelectorAll('.reader-theme-btn').forEach((button) => {
            button.addEventListener('click', () => {
                const raw = localStorage.getItem(storageKey);
                const settings = raw ? JSON.parse(raw) : defaultSettings;
                settings.theme = button.dataset.theme;
                saveReaderSettings(settings);
            });
        });

        document.getElementById('fontIncrease')?.addEventListener('click', () => {
            const raw = localStorage.getItem(storageKey);
            const settings = raw ? JSON.parse(raw) : defaultSettings;
            settings.fontSize = Math.min(30, (settings.fontSize || 22) + 2);
            saveReaderSettings(settings);
        });

        document.getElementById('fontDecrease')?.addEventListener('click', () => {
            const raw = localStorage.getItem(storageKey);
            const settings = raw ? JSON.parse(raw) : defaultSettings;
            settings.fontSize = Math.max(16, (settings.fontSize || 22) - 2);
            saveReaderSettings(settings);
        });

        applyReaderSettings();
    </script>
@endsection
