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
                <a href="#works" style="padding: 14px 32px; background: white; color: var(--primary-green); text-decoration: none; border-radius: 12px; font-weight: 700; font-size: 16px; box-shadow: 0 8px 25px rgba(0,0,0,0.2); transition: all 0.3s;"
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

    <!-- Works Section -->
    <section id="works" style="margin-bottom: 60px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div>
                <h2 style="font-family: 'Crimson Pro', serif; font-size: 28px; font-weight: 700; color: var(--text-primary); margin: 0 0 4px 0;">
                    ✨ Karya Terbaru
                </h2>
                <p style="color: var(--text-secondary); margin: 0; font-size: 14px;">
                    Karya yang baru saja disetujui dan siap dibaca
                </p>
            </div>
        </div>

        <!-- Works Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 24px;">
            @forelse($works as $work)
                <a href="{{ route('works.public.show', $work) }}" class="work-card" style="display: block; text-decoration: none; background: var(--bg-card); border-radius: 16px; overflow: hidden; transition: all 0.4s; border: 1px solid var(--border-color); cursor: pointer;">
                    <!-- Cover -->
                    <div style="position: relative; aspect-ratio: 3/4; background: linear-gradient(135deg, var(--dark-green), var(--primary-green)); overflow: hidden;">
                        @if($work->cover)
                            <img src="{{ asset('storage/' . $work->cover) }}" 
                                 alt="{{ $work->title }}"
                                 style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease;"
                                 class="work-cover-img">
                        @else
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 64px;">
                                {{ $work->type === 'novel' ? '📖' : '📚' }}
                            </div>
                        @endif

                        <!-- Type Badge -->
                        <div style="position: absolute; top: 12px; left: 12px; padding: 6px 14px; background: {{ $work->type === 'novel' ? 'rgba(59, 130, 246, 0.95)' : 'rgba(249, 115, 22, 0.95)' }}; backdrop-filter: blur(10px); color: white; border-radius: 20px; font-size: 11px; font-weight: 700;">
                            {{ $work->type === 'novel' ? '📖 NOVEL' : '🎨 COMIC' }}
                        </div>

                        <!-- Chapter Count Badge -->
                        @if($work->chapters->count() > 0)
                            <div style="position: absolute; bottom: 12px; left: 12px; padding: 6px 12px; background: rgba(45, 139, 115, 0.95); backdrop-filter: blur(10px); color: white; border-radius: 12px; font-size: 12px; font-weight: 700;">
                                📚 {{ $work->chapters->count() }} Ch
                            </div>
                        @endif

                        <!-- Gradient Overlay -->
                        <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 80px; background: linear-gradient(to top, rgba(15, 20, 25, 0.9), transparent);"></div>
                    </div>
                    
                    <!-- Info -->
                    <div style="padding: 16px;">
                        <h3 style="font-size: 15px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 40px; line-height: 1.3;">
                            {{ $work->title }}
                        </h3>

                        <!-- Genres -->
                        @if($work->genres->count() > 0)
                            <div style="display: flex; gap: 6px; margin-bottom: 10px; flex-wrap: wrap;">
                                @foreach($work->genres->take(2) as $genre)
                                    <span style="padding: 4px 10px; background: rgba(45, 139, 115, 0.15); color: var(--light-green); border-radius: 8px; font-size: 11px; font-weight: 600; border: 1px solid rgba(45, 139, 115, 0.3);">
                                        {{ $genre->name }}
                                    </span>
                                @endforeach
                                @if($work->genres->count() > 2)
                                    <span style="padding: 4px 10px; color: var(--text-secondary); font-size: 11px; font-weight: 600;">
                                        +{{ $work->genres->count() - 2 }}
                                    </span>
                                @endif
                            </div>
                        @endif

                        <!-- Stats -->
                        <div style="display: flex; align-items: center; justify-content: space-between; color: var(--text-secondary); font-size: 13px;">
                            <div style="display: flex; align-items: center; gap: 4px;">
                                <span style="color: #fbbf24;">⭐</span>
                                <span style="font-weight: 600; color: var(--text-primary);">{{ number_format(rand(40, 50) / 10, 1) }}</span>
                            </div>
                            <div style="color: var(--text-secondary); font-size: 11px;">
                                by {{ $work->user->name }}
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px;">
                    <div style="font-size: 64px; margin-bottom: 16px;">📚</div>
                    <h3 style="color: var(--text-primary); margin-bottom: 8px;">Belum Ada Karya</h3>
                    <p style="color: var(--text-secondary);">Karya-karya menarik akan segera hadir!</p>
                </div>
            @endforelse
        </div>

        <!-- Custom Pagination -->
        @if($works->hasPages())
            <div class="custom-pagination">
                <div class="pagination-info">
                    Menampilkan {{ $works->firstItem() }} - {{ $works->lastItem() }} dari {{ $works->total() }} karya
                </div>
                <div class="pagination-controls">
                    {{-- Previous Button --}}
                    @if($works->onFirstPage())
                        <span class="pagination-btn disabled">
                            <span class="btn-icon">←</span>
                            <span class="btn-text">Previous</span>
                        </span>
                    @else
                        <a href="{{ $works->previousPageUrl() }}" class="pagination-btn">
                            <span class="btn-icon">←</span>
                            <span class="btn-text">Previous</span>
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    <div class="pagination-numbers">
                        @foreach($works->getUrlRange(1, $works->lastPage()) as $page => $url)
                            @if($page == $works->currentPage())
                                <span class="page-number active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="page-number">{{ $page }}</a>
                            @endif
                        @endforeach
                    </div>

                    {{-- Next Button --}}
                    @if($works->hasMorePages())
                        <a href="{{ $works->nextPageUrl() }}" class="pagination-btn">
                            <span class="btn-text">Next</span>
                            <span class="btn-icon">→</span>
                        </a>
                    @else
                        <span class="pagination-btn disabled">
                            <span class="btn-text">Next</span>
                            <span class="btn-icon">→</span>
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </section>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-content">
            <!-- Footer Top -->
            <div class="footer-top">
                <!-- Logo & About -->
                <div class="footer-section">
                    <div class="footer-logo">
                        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" style="width: 50px; height: 50px;">
                            <!-- Buku -->
                            <rect x="20" y="40" width="40" height="50" fill="#1e5f4f" rx="3" />
                            <rect x="22" y="42" width="36" height="46" fill="#2d8b73" rx="2" />
                            <line x1="40" y1="42" x2="40" y2="88" stroke="#1e5f4f" stroke-width="2" />
                            <line x1="25" y1="50" x2="35" y2="50" stroke="#48c9b0" stroke-width="1.5" />
                            <line x1="25" y1="56" x2="35" y2="56" stroke="#48c9b0" stroke-width="1.5" />
                            <line x1="25" y1="62" x2="35" y2="62" stroke="#48c9b0" stroke-width="1.5" />
                            <line x1="45" y1="50" x2="55" y2="50" stroke="#48c9b0" stroke-width="1.5" />
                            <line x1="45" y1="56" x2="55" y2="56" stroke="#48c9b0" stroke-width="1.5" />
                            <line x1="45" y1="62" x2="55" y2="62" stroke="#48c9b0" stroke-width="1.5" />
                            <!-- Huruf N -->
                            <path d="M 50 15 L 50 70 M 50 15 L 75 55 M 75 25 L 75 70" stroke="white" stroke-width="6"
                                fill="none" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span class="logo-text">Nokomi</span>
                    </div>
                    <p class="footer-about">
                        Platform membaca novel dan komik online terbaik di Indonesia. 
                        Temukan ribuan karya menarik dari berbagai genre favorit Anda.
                    </p>
                </div>

                <!-- Quick Links -->
                <div class="footer-section">
                    <h3 class="footer-title">Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="#works">📚 Jelajahi Karya</a></li>
                        @auth
                            <li><a href="{{ route('home') }}">📊 Dashboard</a></li>
                            <li><a href="{{ route('works.index') }}">✍️ Karya Saya</a></li>
                        @else
                            <li><a href="{{ route('login') }}">🔐 Login</a></li>
                            <li><a href="{{ route('register') }}">✨ Daftar</a></li>
                        @endauth
                    </ul>
                </div>

                <!-- Categories -->
                <div class="footer-section">
                    <h3 class="footer-title">Kategori</h3>
                    <ul class="footer-links">
                        <li><a href="#">📖 Novel</a></li>
                        <li><a href="#">🎨 Komik</a></li>
                        <li><a href="#">🔥 Popular</a></li>
                        <li><a href="#">⭐ Trending</a></li>
                    </ul>
                </div>

                <!-- Connect -->
                <div class="footer-section">
                    <h3 class="footer-title">Connect With Us</h3>
                    <div class="social-links">
                        <a href="mailto:info@nokomi.com" class="social-btn email" title="Email">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M22 6L12 13L2 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                        <a href="https://instagram.com/nokomi" class="social-btn instagram" title="Instagram" target="_blank">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M16 11.37C16.1234 12.2022 15.9813 13.0522 15.5938 13.799 15.2063 14.5458 14.5931 15.1514 13.8416 15.5297 13.0901 15.9079 12.2384 16.0396 11.4078 15.9059 10.5771 15.7723 9.80976 15.3801 9.21484 14.7852 8.61992 14.1902 8.22773 13.4229 8.09407 12.5922 7.9604 11.7615 8.09207 10.9099 8.47033 10.1584 8.84859 9.40685 9.45419 8.79374 10.201 8.40624 10.9478 8.01874 11.7978 7.87658 12.63 8C13.4789 8.12588 14.2649 8.52146 14.8717 9.1283 15.4785 9.73515 15.8741 10.5211 16 11.37Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="17.5" cy="6.5" r="1.5" fill="currentColor"/>
                            </svg>
                        </a>
                        <a href="https://twitter.com/nokomi" class="social-btn twitter" title="Twitter/X" target="_blank">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" fill="currentColor"/>
                            </svg>
                        </a>
                    </div>
                    <p class="contact-info">
                        📧 info@nokomi.com<br>
                        📱 +62 812-3456-7890
                    </p>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-copyright">
                    <p>© {{ date('Y') }} Nokomi. All rights reserved.</p>
                    <p class="license-text">Licensed under MIT License</p>
                </div>
                <div class="footer-credits">
                    <p>Made with ❤️ for Indonesian readers</p>
                </div>
            </div>
        </div>
    </footer>

    <style>
        /* Work Card Styles */
        .work-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
            border-color: var(--primary-green);
        }

        .work-card:hover .work-cover-img {
            transform: scale(1.1);
        }

        .work-card:hover > div:first-child::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(45, 139, 115, 0.2);
            z-index: 1;
        }

        /* Animation */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .work-card {
            animation: slideIn 0.6s ease forwards;
        }

        .work-card:nth-child(1) { animation-delay: 0.1s; }
        .work-card:nth-child(2) { animation-delay: 0.2s; }
        .work-card:nth-child(3) { animation-delay: 0.3s; }
        .work-card:nth-child(4) { animation-delay: 0.4s; }
        .work-card:nth-child(5) { animation-delay: 0.5s; }
        .work-card:nth-child(6) { animation-delay: 0.6s; }
        .work-card:nth-child(7) { animation-delay: 0.7s; }
        .work-card:nth-child(8) { animation-delay: 0.8s; }
        .work-card:nth-child(9) { animation-delay: 0.9s; }
        .work-card:nth-child(10) { animation-delay: 1s; }
        .work-card:nth-child(11) { animation-delay: 1.1s; }
        .work-card:nth-child(12) { animation-delay: 1.2s; }

        /* Custom Pagination */
        .custom-pagination {
            margin-top: 50px;
            padding: 30px;
            background: var(--bg-card);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            gap: 20px;
            align-items: center;
        }

        .pagination-info {
            color: var(--text-secondary);
            font-size: 14px;
            font-weight: 500;
        }

        .pagination-controls {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .pagination-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: linear-gradient(135deg, var(--primary-green), var(--light-green));
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(45, 139, 115, 0.3);
        }

        .pagination-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(45, 139, 115, 0.4);
        }

        .pagination-btn.disabled {
            background: var(--border-color);
            color: var(--text-secondary);
            cursor: not-allowed;
            box-shadow: none;
        }

        .pagination-btn.disabled:hover {
            transform: none;
        }

        .btn-icon {
            font-size: 16px;
        }

        .pagination-numbers {
            display: flex;
            gap: 8px;
        }

        .page-number {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 600;
            font-size: 14px;
            background: var(--bg-card);
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .page-number:hover {
            border-color: var(--primary-green);
            color: var(--primary-green);
            transform: scale(1.1);
        }

        .page-number.active {
            background: linear-gradient(135deg, var(--primary-green), var(--light-green));
            color: white;
            border-color: var(--primary-green);
            box-shadow: 0 4px 12px rgba(45, 139, 115, 0.3);
        }

        /* Footer Styles */
        .site-footer {
            margin-top: 80px;
            background: linear-gradient(135deg, var(--dark-green) 0%, var(--primary-green) 100%);
            color: white;
            padding: 60px 0 0;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 40px;
        }

        .footer-top {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.5fr;
            gap: 40px;
            padding-bottom: 40px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .footer-section h3 {
            margin: 0 0 20px 0;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .logo-text {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .footer-about {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
            font-size: 14px;
        }

        .footer-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .footer-links a:hover {
            color: white;
            transform: translateX(5px);
        }

        .social-links {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }

        .social-btn {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-3px);
        }

        .social-btn.email:hover {
            background: #ea4335;
        }

        .social-btn.instagram:hover {
            background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
        }

        .social-btn.twitter:hover {
            background: #000000;
        }

        .contact-info {
            color: rgba(255, 255, 255, 0.8);
            font-size: 13px;
            line-height: 1.8;
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 30px 0;
            font-size: 13px;
        }

        .footer-copyright p {
            margin: 0 0 5px 0;
            color: rgba(255, 255, 255, 0.9);
        }

        .license-text {
            color: rgba(255, 255, 255, 0.6);
            font-size: 12px;
        }

        .footer-credits p {
            margin: 0;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .footer-top {
                grid-template-columns: 1fr 1fr;
                gap: 30px;
            }
        }

        @media (max-width: 768px) {
            section h1 {
                font-size: 32px !important;
            }

            section h2 {
                font-size: 24px !important;
            }

            .pagination-controls {
                flex-direction: column;
                width: 100%;
            }

            .pagination-btn,
            .pagination-numbers {
                width: 100%;
            }

            .pagination-numbers {
                justify-content: center;
            }

            .footer-content {
                padding: 0 20px;
            }

            .footer-top {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .pagination-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endsection