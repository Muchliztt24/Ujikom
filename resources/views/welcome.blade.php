@extends('layouts.user')

@section('content')
    <!-- Hero/Banner Section -->
    <section style="margin-bottom: 60px; position: relative; border-radius: 24px; overflow: hidden; background: linear-gradient(135deg, var(--dark-green) 0%, var(--primary-green) 50%, var(--light-green) 100%); padding: 60px 40px; text-align: center;">
        <div style="position: relative; z-index: 2;">
            <h1 style="font-family: 'Crimson Pro', serif; font-size: 48px; font-weight: 700; color: white; margin-bottom: 16px; text-shadow: 0 4px 12px rgba(0,0,0,0.3);">
                Selamat Datang di Nokomi
            </h1>
            <p style="font-size: 18px; color: rgba(255,255,255,0.9); margin-bottom: 32px; max-width: 600px; margin-left: auto; margin-right: auto; line-height: 1.6;">
                Platform membaca novel dan comic online terbaik. Temukan ribuan karya menarik dari berbagai genre!
            </p>
            <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
                <a href="#latest" style="padding: 14px 32px; background: white; color: var(--primary-green); text-decoration: none; border-radius: 12px; font-weight: 700; font-size: 16px; box-shadow: 0 8px 25px rgba(0,0,0,0.2); transition: all 0.3s;"
                   onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 12px 35px rgba(0,0,0,0.3)'"
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 25px rgba(0,0,0,0.2)'">
                    📚 Jelajahi Sekarang
                </a>
                @guest
                    <a href="{{ route('register') }}" style="padding: 14px 32px; background: transparent; border: 2px solid white; color: white; text-decoration: none; border-radius: 12px; font-weight: 700; font-size: 16px; transition: all 0.3s;"
                       onmouseover="this.style.background='white'; this.style.color='var(--primary-green)'"
                       onmouseout="this.style.background='transparent'; this.style.color='white'">
                        ✨ Daftar Gratis
                    </a>
                @endguest
            </div>
        </div>
        
        <!-- Decorative elements -->
        <div style="position: absolute; top: 20px; left: 20px; font-size: 80px; opacity: 0.1;">📖</div>
        <div style="position: absolute; bottom: 20px; right: 20px; font-size: 80px; opacity: 0.1;">📚</div>
    </section>

    <!-- Featured/Trending Section -->
    <section style="margin-bottom: 60px;" id="featured">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div>
                <h2 style="font-family: 'Crimson Pro', serif; font-size: 32px; font-weight: 700; color: var(--text-primary); margin: 0 0 4px 0;">
                    🔥 Trending Sekarang
                </h2>
                <p style="color: var(--text-secondary); margin: 0; font-size: 14px;">
                    Karya paling populer minggu ini
                </p>
            </div>
        </div>

        <!-- Horizontal Scrollable Cards -->
        <div style="display: flex; gap: 24px; overflow-x: auto; padding-bottom: 16px; scroll-behavior: smooth;">
            @for ($i = 0; $i < 6; $i++)
                <div class="featured-card" style="min-width: 320px; background: var(--bg-card); border-radius: 16px; overflow: hidden; border: 1px solid var(--border-color); transition: all 0.3s;">
                    <!-- Cover with overlay -->
                    <div style="position: relative; height: 200px; background: linear-gradient(135deg, var(--dark-green), var(--primary-green));">
                        <div style="position: absolute; top: 12px; left: 12px; padding: 6px 14px; background: rgba(249, 115, 22, 0.95); backdrop-filter: blur(10px); color: white; border-radius: 20px; font-size: 11px; font-weight: 700;">
                            🎨 COMIC
                        </div>
                        <div style="position: absolute; bottom: 12px; left: 12px; padding: 6px 12px; background: rgba(45, 139, 115, 0.95); backdrop-filter: blur(10px); color: white; border-radius: 12px; font-size: 12px; font-weight: 700;">
                            📚 {{ rand(10, 50) }} Ch
                        </div>
                        <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 100px; background: linear-gradient(to top, rgba(15, 20, 25, 0.9), transparent);"></div>
                    </div>
                    
                    <!-- Info -->
                    <div style="padding: 16px;">
                        <h3 style="font-weight: 700; color: var(--text-primary); margin-bottom: 8px; font-size: 16px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            Judul Karya Menarik {{ $i + 1 }}
                        </h3>
                        <div style="display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 12px;">
                            <span style="padding: 4px 10px; background: rgba(45, 139, 115, 0.15); color: var(--light-green); border-radius: 8px; font-size: 11px; font-weight: 600;">Action</span>
                            <span style="padding: 4px 10px; background: rgba(45, 139, 115, 0.15); color: var(--light-green); border-radius: 8px; font-size: 11px; font-weight: 600;">Fantasy</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px; color: var(--text-secondary); font-size: 13px;">
                            <div style="display: flex; align-items: center; gap: 4px;">
                                <span style="color: #fbbf24;">⭐</span>
                                <span style="font-weight: 600; color: var(--text-primary);">4.{{ rand(5, 9) }}</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 4px;">
                                <span>👁️</span>
                                <span>{{ number_format(rand(1000, 99999)) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </section>

    <!-- Latest Updates -->
    <section style="margin-bottom: 60px;" id="latest">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div>
                <h2 style="font-family: 'Crimson Pro', serif; font-size: 28px; font-weight: 700; color: var(--text-primary); margin: 0 0 4px 0;">
                    ✨ Update Terbaru
                </h2>
                <p style="color: var(--text-secondary); margin: 0; font-size: 14px;">
                    Chapter baru yang baru saja diupload
                </p>
            </div>
        </div>

        <!-- Grid Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 24px;">
            @for ($i = 0; $i < 12; $i++)
                <div class="work-card" style="background: var(--bg-card); border-radius: 16px; overflow: hidden; transition: all 0.4s; border: 1px solid var(--border-color); cursor: pointer;">
                    <!-- Cover -->
                    <div style="position: relative; aspect-ratio: 3/4; background: linear-gradient(135deg, var(--dark-green), var(--primary-green)); overflow: hidden;">
                        <div style="position: absolute; top: 12px; left: 12px; padding: 6px 14px; background: {{ $i % 2 == 0 ? 'rgba(59, 130, 246, 0.95)' : 'rgba(249, 115, 22, 0.95)' }}; backdrop-filter: blur(10px); color: white; border-radius: 20px; font-size: 11px; font-weight: 700;">
                            {{ $i % 2 == 0 ? '📖 NOVEL' : '🎨 COMIC' }}
                        </div>
                        @if ($i < 3)
                            <div style="position: absolute; top: 12px; right: 12px; padding: 6px 12px; background: rgba(16, 185, 129, 0.95); backdrop-filter: blur(10px); color: white; border-radius: 20px; font-size: 11px; font-weight: 700;">
                                🆕 NEW
                            </div>
                        @endif
                        <div style="position: absolute; bottom: 12px; left: 12px; padding: 6px 12px; background: rgba(45, 139, 115, 0.95); backdrop-filter: blur(10px); color: white; border-radius: 12px; font-size: 12px; font-weight: 700;">
                            📚 {{ rand(5, 40) }} Ch
                        </div>
                        <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 80px; background: linear-gradient(to top, rgba(15, 20, 25, 0.9), transparent);"></div>
                    </div>
                    
                    <!-- Info -->
                    <div style="padding: 16px;">
                        <h3 style="font-size: 15px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 40px; line-height: 1.3;">
                            Judul Karya Contoh {{ $i + 1 }}
                        </h3>
                        <div style="display: flex; gap: 6px; margin-bottom: 10px; flex-wrap: wrap;">
                            <span style="padding: 4px 10px; background: rgba(45, 139, 115, 0.15); color: var(--light-green); border-radius: 8px; font-size: 11px; font-weight: 600; border: 1px solid rgba(45, 139, 115, 0.3);">
                                {{ ['Action', 'Romance', 'Fantasy', 'Horror', 'Comedy'][rand(0, 4)] }}
                            </span>
                        </div>
                        <div style="display: flex; align-items: center; justify-content: space-between; color: var(--text-secondary); font-size: 13px;">
                            <div style="display: flex; align-items: center; gap: 4px;">
                                <span style="color: #fbbf24;">⭐</span>
                                <span style="font-weight: 600; color: var(--text-primary);">{{ number_format(rand(40, 50) / 10, 1) }}</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 4px;">
                                <span>👁️</span>
                                <span>{{ number_format(rand(100, 9999)) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>

        <!-- Load More -->
        <div style="text-align: center; margin-top: 40px;">
            <button style="padding: 14px 40px; background: var(--bg-card); color: var(--text-primary); border: 2px solid var(--primary-green); border-radius: 12px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.3s;"
                    onmouseover="this.style.background='var(--primary-green)'; this.style.color='white'; this.style.transform='translateY(-2px)'"
                    onmouseout="this.style.background='var(--bg-card)'; this.style.color='var(--text-primary)'; this.style.transform='translateY(0)'">
                Muat Lebih Banyak
            </button>
        </div>
    </section>

    <!-- Genre Section -->
    <section style="margin-bottom: 60px;">
        <div style="margin-bottom: 24px;">
            <h2 style="font-family: 'Crimson Pro', serif; font-size: 28px; font-weight: 700; color: var(--text-primary); margin: 0 0 4px 0;">
                📚 Jelajahi berdasarkan Genre
            </h2>
            <p style="color: var(--text-secondary); margin: 0; font-size: 14px;">
                Temukan karya sesuai genre favorit Anda
            </p>
        </div>

        <!-- Genre Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 16px;">
            @php
                $genres = [
                    ['name' => 'Action', 'icon' => '⚔️', 'count' => rand(50, 200), 'color' => '#ef4444'],
                    ['name' => 'Romance', 'icon' => '💕', 'count' => rand(50, 200), 'color' => '#ec4899'],
                    ['name' => 'Fantasy', 'icon' => '🔮', 'count' => rand(50, 200), 'color' => '#8b5cf6'],
                    ['name' => 'Sci-Fi', 'icon' => '🚀', 'count' => rand(50, 200), 'color' => '#3b82f6'],
                    ['name' => 'Horror', 'icon' => '👻', 'count' => rand(50, 200), 'color' => '#6366f1'],
                    ['name' => 'Comedy', 'icon' => '😂', 'count' => rand(50, 200), 'color' => '#f59e0b'],
                    ['name' => 'Drama', 'icon' => '🎭', 'count' => rand(50, 200), 'color' => '#06b6d4'],
                    ['name' => 'Mystery', 'icon' => '🔍', 'count' => rand(50, 200), 'color' => '#10b981'],
                ];
            @endphp

            @foreach ($genres as $genre)
                <a href="#" class="genre-card" style="display: block; padding: 24px; background: var(--bg-card); border-radius: 16px; border: 1px solid var(--border-color); text-decoration: none; transition: all 0.3s; position: relative; overflow: hidden;">
                    <div style="position: absolute; top: -20px; right: -20px; font-size: 100px; opacity: 0.05;">{{ $genre['icon'] }}</div>
                    <div style="position: relative; z-index: 2;">
                        <div style="font-size: 40px; margin-bottom: 12px;">{{ $genre['icon'] }}</div>
                        <h3 style="font-weight: 700; color: var(--text-primary); margin-bottom: 4px; font-size: 18px;">{{ $genre['name'] }}</h3>
                        <p style="color: var(--text-secondary); font-size: 13px; margin: 0;">{{ $genre['count'] }} karya</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <style>
        .work-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
            border-color: var(--primary-green);
        }

        .work-card:hover > div:first-child::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(45, 139, 115, 0.3);
        }

        .featured-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.4);
            border-color: var(--light-green);
        }

        .genre-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            border-color: var(--primary-green);
        }

        .genre-card:hover h3 {
            color: var(--light-green);
        }

        /* Horizontal scroll styling */
        section > div::-webkit-scrollbar {
            height: 8px;
        }

        section > div::-webkit-scrollbar-track {
            background: var(--bg-card);
            border-radius: 4px;
        }

        section > div::-webkit-scrollbar-thumb {
            background: var(--primary-green);
            border-radius: 4px;
        }

        section > div::-webkit-scrollbar-thumb:hover {
            background: var(--light-green);
        }
    </style>
@endsection