@extends('layouts.user')

@section('content')
    <!-- Back Button -->
    <div style="margin-bottom: 24px;">
        <a href="{{ route('home') }}" 
           style="display: inline-flex; align-items: center; gap: 8px; color: var(--text-secondary); text-decoration: none; padding: 8px 16px; border-radius: 8px; transition: all 0.3s;"
           onmouseover="this.style.background='var(--bg-card)'; this.style.color='var(--light-green)'"
           onmouseout="this.style.background='transparent'; this.style.color='var(--text-secondary)'">
            <span>←</span> Kembali ke Beranda
        </a>
    </div>

    <!-- Work Detail Section -->
    <div style="background: var(--bg-card); border-radius: 20px; overflow: hidden; border: 1px solid var(--border-color); margin-bottom: 40px;">
        <div style="display: grid; grid-template-columns: 300px 1fr; gap: 40px; padding: 40px;">
            
            <!-- Left: Cover -->
            <div>
                <div style="position: sticky; top: 100px;">
                    <!-- Cover Image -->
                    <div style="position: relative; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 30px rgba(0,0,0,0.4); margin-bottom: 20px;">
                        @if($work->cover)
                            <img src="{{ asset('storage/' . $work->cover) }}" 
                                 alt="{{ $work->title }}"
                                 style="width: 100%; aspect-ratio: 3/4; object-fit: cover;">
                        @else
                            <div style="width: 100%; aspect-ratio: 3/4; background: linear-gradient(135deg, var(--dark-green), var(--primary-green)); display: flex; align-items: center; justify-content: center; font-size: 80px;">
                                {{ $work->type === 'novel' ? '📖' : '📚' }}
                            </div>
                        @endif

                        <!-- Type Badge -->
                        <div style="position: absolute; top: 12px; left: 12px; padding: 8px 16px; background: {{ $work->type === 'novel' ? 'rgba(59, 130, 246, 0.95)' : 'rgba(249, 115, 22, 0.95)' }}; backdrop-filter: blur(10px); color: white; border-radius: 20px; font-size: 12px; font-weight: 700;">
                            {{ $work->type === 'novel' ? '📖 NOVEL' : '🎨 COMIC' }}
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @if($work->chapters->count() > 0)
                            <a href="#chapters" 
                               style="padding: 14px 20px; background: linear-gradient(135deg, var(--primary-green), var(--light-green)); color: white; text-decoration: none; border-radius: 12px; font-weight: 700; text-align: center; box-shadow: 0 4px 15px rgba(45, 139, 115, 0.3); transition: all 0.3s;"
                               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(45, 139, 115, 0.4)'"
                               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(45, 139, 115, 0.3)'">
                                📖 Mulai Baca
                            </a>
                        @endif

                        <button onclick="toggleBookmark()" 
                                style="padding: 14px 20px; background: var(--bg-hover); color: var(--text-primary); border: 1px solid var(--border-color); border-radius: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s;"
                                onmouseover="this.style.borderColor='var(--light-green)'; this.style.color='var(--light-green)'"
                                onmouseout="this.style.borderColor='var(--border-color)'; this.style.color='var(--text-primary)'">
                            🔖 Tambah Bookmark
                        </button>

                        <button onclick="toggleFavorite()" 
                                style="padding: 14px 20px; background: var(--bg-hover); color: var(--text-primary); border: 1px solid var(--border-color); border-radius: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s;"
                                onmouseover="this.style.borderColor='#ef4444'; this.style.color='#ef4444'"
                                onmouseout="this.style.borderColor='var(--border-color)'; this.style.color='var(--text-primary)'">
                            ❤️ Favorit
                        </button>
                    </div>

                    <!-- Stats -->
                    <div style="margin-top: 20px; padding: 16px; background: var(--bg-main); border-radius: 12px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                            <span style="color: var(--text-secondary); font-size: 13px;">Rating</span>
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <span style="color: #fbbf24; font-size: 16px;">⭐</span>
                                <span style="font-weight: 700; color: var(--text-primary);">{{ number_format(rand(40, 50) / 10, 1) }}</span>
                                <span style="color: var(--text-secondary); font-size: 12px;">({{ rand(10, 500) }})</span>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                            <span style="color: var(--text-secondary); font-size: 13px;">Views</span>
                            <span style="font-weight: 600; color: var(--text-primary);">{{ number_format(rand(1000, 99999)) }}</span>
                        </div>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <span style="color: var(--text-secondary); font-size: 13px;">Chapters</span>
                            <span style="font-weight: 600; color: var(--light-green);">{{ $work->chapters->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Info -->
            <div>
                <!-- Title -->
                <h1 style="font-family: 'Crimson Pro', serif; font-size: 42px; font-weight: 700; color: var(--text-primary); margin-bottom: 12px; line-height: 1.2;">
                    {{ $work->title }}
                </h1>

                <!-- Author -->
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary-green), var(--light-green)); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 16px;">
                        {{ strtoupper(substr($work->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-size: 13px; color: var(--text-secondary);">Dibuat oleh</div>
                        <div style="font-weight: 600; color: var(--text-primary);">{{ $work->user->name }}</div>
                    </div>
                </div>

                <!-- Genres -->
                @if($work->genres->count() > 0)
                    <div style="margin-bottom: 24px;">
                        <div style="font-size: 13px; color: var(--text-secondary); margin-bottom: 8px; font-weight: 600;">GENRE</div>
                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                            @foreach($work->genres as $genre)
                                <span style="padding: 8px 16px; background: rgba(45, 139, 115, 0.15); color: var(--light-green); border-radius: 10px; font-size: 13px; font-weight: 600; border: 1px solid rgba(45, 139, 115, 0.3);">
                                    {{ $genre->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Description -->
                @if($work->description)
                    <div style="margin-bottom: 32px;">
                        <h3 style="font-size: 18px; font-weight: 700; color: var(--text-primary); margin-bottom: 12px;">📝 Sinopsis</h3>
                        <div style="color: var(--text-secondary); line-height: 1.8; font-size: 15px; white-space: pre-line;">
                            {{ $work->description }}
                        </div>
                    </div>
                @endif

                <!-- Chapters List -->
                <div id="chapters">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                        <h3 style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0;">
                            📚 Daftar Chapter ({{ $work->chapters->count() }})
                        </h3>
                        <div style="display: flex; gap: 8px;">
                            <button onclick="sortChapters('asc')" id="sortAsc"
                                    style="padding: 8px 16px; background: var(--primary-green); color: white; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                                ↑ Terlama
                            </button>
                            <button onclick="sortChapters('desc')" id="sortDesc"
                                    style="padding: 8px 16px; background: var(--bg-hover); color: var(--text-secondary); border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                                ↓ Terbaru
                            </button>
                        </div>
                    </div>

                    <div id="chaptersList" style="display: flex; flex-direction: column; gap: 12px;">
                        @forelse($work->chapters as $chapter)
                            <a href="#" 
                               class="chapter-item"
                               data-date="{{ $chapter->created_at->timestamp }}"
                               style="display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; background: var(--bg-main); border: 1px solid var(--border-color); border-radius: 12px; text-decoration: none; transition: all 0.3s;"
                               onmouseover="this.style.background='var(--bg-hover)'; this.style.borderColor='var(--primary-green)'; this.style.transform='translateX(8px)'"
                               onmouseout="this.style.background='var(--bg-main)'; this.style.borderColor='var(--border-color)'; this.style.transform='translateX(0)'">
                                <div style="flex: 1;">
                                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 6px;">
                                        <span style="font-weight: 700; color: var(--light-green); font-size: 15px;">
                                            Chapter {{ $chapter->chapter_number }}
                                        </span>
                                        @if($chapter->created_at->diffInDays() < 7)
                                            <span style="padding: 4px 10px; background: rgba(16, 185, 129, 0.2); color: #10b981; border-radius: 6px; font-size: 11px; font-weight: 700;">
                                                NEW
                                            </span>
                                        @endif
                                    </div>
                                    @if($chapter->title)
                                        <div style="color: var(--text-primary); font-weight: 600; margin-bottom: 4px;">
                                            {{ $chapter->title }}
                                        </div>
                                    @endif
                                    <div style="color: var(--text-secondary); font-size: 13px;">
                                        {{ $chapter->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                <div style="color: var(--text-secondary); font-size: 20px;">
                                    →
                                </div>
                            </a>
                        @empty
                            <div style="text-align: center; padding: 60px 20px; background: var(--bg-main); border-radius: 12px; border: 1px dashed var(--border-color);">
                                <div style="font-size: 48px; margin-bottom: 12px;">📖</div>
                                <div style="color: var(--text-secondary); font-size: 15px;">Belum ada chapter tersedia</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 968px) {
            div[style*="grid-template-columns: 300px 1fr"] {
                grid-template-columns: 1fr !important;
            }

            div[style*="position: sticky"] {
                position: relative !important;
                top: 0 !important;
            }

            h1 {
                font-size: 32px !important;
            }
        }

        @media (max-width: 640px) {
            div[style*="padding: 40px"] {
                padding: 24px !important;
            }

            h1 {
                font-size: 28px !important;
            }
        }
    </style>

    <script>
        // Bookmark Toggle
        function toggleBookmark() {
            const btn = event.target;
            if (btn.textContent.includes('Tambah')) {
                btn.textContent = '✓ Tersimpan';
                btn.style.background = 'var(--primary-green)';
                btn.style.color = 'white';
            } else {
                btn.textContent = '🔖 Tambah Bookmark';
                btn.style.background = 'var(--bg-hover)';
                btn.style.color = 'var(--text-primary)';
            }
        }

        // Favorite Toggle
        function toggleFavorite() {
            const btn = event.target;
            if (btn.textContent.includes('Favorit') && !btn.textContent.includes('✓')) {
                btn.textContent = '✓ Difavoritkan';
                btn.style.background = '#ef4444';
                btn.style.color = 'white';
                btn.style.borderColor = '#ef4444';
            } else {
                btn.textContent = '❤️ Favorit';
                btn.style.background = 'var(--bg-hover)';
                btn.style.color = 'var(--text-primary)';
                btn.style.borderColor = 'var(--border-color)';
            }
        }

        // Sort Chapters
        function sortChapters(order) {
            const list = document.getElementById('chaptersList');
            const items = Array.from(document.querySelectorAll('.chapter-item'));
            
            // Update button styles
            document.getElementById('sortAsc').style.background = order === 'asc' ? 'var(--primary-green)' : 'var(--bg-hover)';
            document.getElementById('sortAsc').style.color = order === 'asc' ? 'white' : 'var(--text-secondary)';
            document.getElementById('sortAsc').style.border = order === 'asc' ? 'none' : '1px solid var(--border-color)';
            
            document.getElementById('sortDesc').style.background = order === 'desc' ? 'var(--primary-green)' : 'var(--bg-hover)';
            document.getElementById('sortDesc').style.color = order === 'desc' ? 'white' : 'var(--text-secondary)';
            document.getElementById('sortDesc').style.border = order === 'desc' ? 'none' : '1px solid var(--border-color)';
            
            // Sort items
            items.sort((a, b) => {
                const dateA = parseInt(a.dataset.date);
                const dateB = parseInt(b.dataset.date);
                return order === 'asc' ? dateA - dateB : dateB - dateA;
            });
            
            // Clear and re-append
            list.innerHTML = '';
            items.forEach(item => list.appendChild(item));
        }
    </script>
@endsection