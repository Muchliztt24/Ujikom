<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Nokomi</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro:wght@400;600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root { --primary-green: #2d8b73; --dark-green: #1e5f4f; --light-green: #48c9b0; --bg-main: #0f1419; --bg-card: #1a1f2e; --bg-hover: #252d3d; --text-primary: #e8eaed; --text-secondary: #9aa0a6; --border-color: #2d3748; --danger: #ef4444; }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg-main); color: var(--text-primary); overflow-x: hidden; }
        body::before { content: ""; position: fixed; inset: 0; pointer-events: none; background:
            radial-gradient(circle at top left, rgba(72, 201, 176, 0.08), transparent 32%),
            radial-gradient(circle at top right, rgba(45, 139, 115, 0.06), transparent 24%);
            z-index: -1; }
        .page-reveal { opacity: 0; transform: translateY(22px) scale(0.985); transition: opacity 0.7s cubic-bezier(.22,1,.36,1), transform 0.7s cubic-bezier(.22,1,.36,1); will-change: opacity, transform; }
        .page-reveal.is-visible { opacity: 1; transform: translateY(0) scale(1); }
        .page-ready .page-reveal[data-reveal-delay="1"] { transition-delay: 0.08s; }
        .page-ready .page-reveal[data-reveal-delay="2"] { transition-delay: 0.16s; }
        .page-ready .page-reveal[data-reveal-delay="3"] { transition-delay: 0.24s; }
        .media-shell { position: relative; overflow: hidden; isolation: isolate; }
        .media-shell::before { content: ""; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(45, 139, 115, 0.34), rgba(72, 201, 176, 0.14)); opacity: 0; transition: opacity 0.35s ease; z-index: 1; pointer-events: none; }
        .media-shell:hover::before { opacity: 1; }
        .media-skeleton { position: absolute; inset: 0; background:
            linear-gradient(110deg, rgba(255,255,255,0.03) 8%, rgba(255,255,255,0.12) 18%, rgba(255,255,255,0.03) 33%),
            linear-gradient(135deg, rgba(20, 35, 47, 1), rgba(38, 67, 85, 0.95));
            background-size: 220% 100%, 100% 100%;
            animation: mediaShimmer 1.35s linear infinite;
            transition: opacity 0.35s ease, visibility 0.35s ease;
            z-index: 0; }
        .media-shell.is-loaded .media-skeleton { opacity: 0; visibility: hidden; }
        [data-media-loading] { opacity: 0; transform: scale(1.025); transition: opacity 0.45s ease, transform 0.55s ease; position: relative; z-index: 2; }
        .media-shell.is-loaded [data-media-loading] { opacity: 1; transform: scale(1); }
        .floating-glow { position: absolute; border-radius: 999px; filter: blur(12px); opacity: 0.24; animation: floatingGlow 6s ease-in-out infinite; }
        .js-tilt { transform-style: preserve-3d; transition: transform 0.18s ease, box-shadow 0.28s ease, border-color 0.28s ease; will-change: transform; }
        .js-tilt .media-shell,
        .js-tilt .work-info { transform: translateZ(0); }
        @keyframes mediaShimmer {
            0% { background-position: 180% 0, 0 0; }
            100% { background-position: -40% 0, 0 0; }
        }
        @keyframes floatingGlow {
            0%, 100% { transform: translate3d(0, 0, 0) scale(1); }
            50% { transform: translate3d(0, -14px, 0) scale(1.06); }
        }
        @keyframes bookOpen {
            0%, 100% { transform: rotateY(0deg); }
            45% { transform: rotateY(-34deg); }
            65% { transform: rotateY(-12deg); }
        }
        @keyframes pageFlip {
            0%, 100% { transform: rotateY(0deg); }
            50% { transform: rotateY(-24deg); }
        }
        @media (prefers-reduced-motion: reduce) {
            .page-reveal,
            [data-media-loading],
            .floating-glow,
            .js-tilt {
                animation: none !important;
                transition: none !important;
                transform: none !important;
            }
        }
        .page-loader { position: fixed; inset: 0; background: radial-gradient(circle at top, rgba(72,201,176,0.08), transparent 32%), rgba(15, 20, 25, 0.96); display: flex; align-items: center; justify-content: center; z-index: 2200; opacity: 1; visibility: visible; transition: opacity 0.45s ease, visibility 0.45s ease; }
        .page-loader.is-hidden { opacity: 0; visibility: hidden; }
        .page-loader-content { display: grid; justify-items: center; gap: 18px; }
        .book-loader { position: relative; width: 86px; height: 62px; perspective: 120px; }
        .book-loader-cover,
        .book-loader-page { position: absolute; inset: 0; border-radius: 8px; transform-origin: left center; }
        .book-loader-cover { background: linear-gradient(135deg, var(--primary-green), var(--light-green)); box-shadow: 0 14px 28px rgba(0,0,0,0.24); animation: bookOpen 1.3s ease-in-out infinite; }
        .book-loader-page { background: linear-gradient(180deg, rgba(255,255,255,0.96), rgba(228,238,241,0.9)); left: 8px; right: 4px; animation: pageFlip 1.3s ease-in-out infinite; }
        .book-loader-page.page-two { animation-delay: 0.18s; opacity: 0.88; }
        .loader-label { color: var(--text-secondary); font-size: 13px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
        .navbar { position: fixed; inset: 0 0 auto 0; height: 70px; background: rgba(15, 20, 25, 0.95); backdrop-filter: blur(10px); border-bottom: 1px solid var(--border-color); z-index: 1000; }
        .navbar.scrolled { box-shadow: 0 4px 20px rgba(0,0,0,0.3); }
        .navbar-container { max-width: 1400px; height: 100%; margin: 0 auto; padding: 0 24px; display: flex; align-items: center; justify-content: space-between; gap: 16px; }
        .logo-row, .navbar-menu { display: flex; align-items: center; gap: 8px; }
        .logo-row { gap: 20px; }
        .logo-section { display: flex; align-items: center; gap: 16px; cursor: pointer; min-width: 0; }
        .logo-icon { width: 48px; height: 48px; background: linear-gradient(135deg, var(--primary-green), var(--light-green)); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(45,139,115,0.3); }
        .logo-icon svg { width: 40px; height: 40px; }
        .logo-text { font-family: 'Crimson Pro', serif; font-size: 26px; font-weight: 700; color: var(--text-primary); }
        .menu-toggle { display: flex; flex-direction: column; gap: 5px; cursor: pointer; padding: 8px; border-radius: 8px; transition: all 0.3s ease; }
        .menu-toggle:hover { background: var(--bg-hover); }
        .menu-toggle span { width: 24px; height: 2px; background: var(--text-primary); transition: all 0.3s ease; border-radius: 2px; }
        .menu-toggle.active span:nth-child(1) { transform: rotate(45deg) translate(7px, 7px); }
        .menu-toggle.active span:nth-child(2) { opacity: 0; }
        .menu-toggle.active span:nth-child(3) { transform: rotate(-45deg) translate(6px, -6px); }
        .nav-link { padding: 10px 16px; color: var(--text-secondary); text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 500; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px; }
        .nav-link:hover, .nav-link.active { background: var(--bg-hover); color: var(--text-primary); }
        .nav-text { white-space: nowrap; }
        .user-avatar { width: 38px; height: 38px; border-radius: 50%; background: var(--primary-green); display: inline-flex; align-items: center; justify-content: center; font-weight: 700; color: white; overflow: hidden; flex: 0 0 38px; }
        .user-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .notification-badge { position: absolute; top: -4px; right: -4px; width: 18px; height: 18px; background: var(--danger); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 700; border: 2px solid var(--bg-main); }
        .sidebar { position: fixed; top: 70px; left: -300px; width: 280px; height: calc(100vh - 70px); background: rgba(26,31,46,0.98); backdrop-filter: blur(10px); border-right: 1px solid var(--border-color); transition: left 0.4s ease; z-index: 999; overflow-y: auto; box-shadow: 4px 0 20px rgba(0,0,0,0.3); }
        .sidebar.active { left: 0; }
        .sidebar-content { padding: 24px 16px; }
        .sidebar-section { margin-bottom: 28px; }
        .sidebar-title { font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-secondary); letter-spacing: 1.2px; margin-bottom: 12px; padding: 0 12px; }
        .sidebar-menu, .profile-actions { display: flex; flex-direction: column; gap: 4px; }
        .sidebar-link, .dropdown-item, .profile-action { display: flex; align-items: center; gap: 12px; padding: 12px 16px; color: var(--text-secondary); text-decoration: none; border-radius: 10px; transition: all 0.3s ease; }
        .sidebar-link:hover, .dropdown-item:hover, .profile-action:hover { background: var(--bg-hover); color: var(--text-primary); transform: translateX(4px); }
        .sidebar-link.active { background: linear-gradient(90deg, rgba(45,139,115,0.15), transparent); color: var(--light-green); border-left: 3px solid var(--light-green); }
        .sidebar-icon { width: 20px; text-align: center; }
        .sidebar-dropdown { position: relative; }
        .dropdown-trigger { justify-content: space-between; }
        .dropdown-arrow { font-size: 12px; transition: transform 0.3s ease; }
        .dropdown-arrow.rotated { transform: rotate(-180deg); }
        .dropdown-content { max-height: 0; overflow: hidden; transition: max-height 0.4s ease-in-out; margin-left: 12px; background: var(--bg-card); border-radius: 8px; }
        .dropdown-content.active { max-height: 70vh; overflow-y: auto; padding: 8px 0; }
        .profile-card { padding: 16px; background: linear-gradient(135deg, rgba(45,139,115,0.18), rgba(72,201,176,0.08)); border: 1px solid rgba(72,201,176,0.25); border-radius: 16px; }
        .profile-name { font-weight: 700; color: var(--text-primary); }
        .profile-role { color: var(--text-secondary); font-size: 13px; margin-top: 4px; }
        .profile-actions { margin-top: 14px; gap: 8px; }
        .profile-action { color: var(--text-primary); background: rgba(15,20,25,0.35); }
        .profile-action.logout { color: #fecaca; background: rgba(239,68,68,0.12); }
        .sidebar-overlay { position: fixed; inset: 70px 0 0 0; background: rgba(0,0,0,0.6); opacity: 0; visibility: hidden; transition: all 0.3s ease; z-index: 998; }
        .sidebar-overlay.active { opacity: 1; visibility: visible; }
        .main-content { margin-top: 70px; padding: 32px 24px; min-height: calc(100vh - 70px); }
        .container { max-width: 1400px; margin: 0 auto; }
        .pagination-wrap { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; margin-top: 30px; }
        .pagination-meta { color: var(--text-secondary); font-size: 14px; font-weight: 600; }
        .pagination-links { display: flex; gap: 8px; flex-wrap: wrap; }
        .pagination-link { display: inline-flex; align-items: center; justify-content: center; padding: 10px 14px; border-radius: 10px; text-decoration: none; background: var(--bg-card); border: 1px solid var(--border-color); color: var(--text-primary); font-size: 14px; font-weight: 700; transition: all 0.3s ease; }
        .pagination-link:hover { border-color: var(--light-green); color: white; background: var(--bg-hover); }
        .pagination-link.disabled { opacity: 0.45; pointer-events: none; }
        .works-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 24px; margin-top: 32px; }
        .work-card { background: var(--bg-card); border-radius: 12px; overflow: hidden; cursor: pointer; transition: all 0.3s ease; border: 1px solid var(--border-color); }
        .work-card:hover { transform: translateY(-8px); box-shadow: 0 12px 40px rgba(0,0,0,0.4); border-color: var(--primary-green); }
        .work-cover { width: 100%; aspect-ratio: 3/4; object-fit: cover; background: linear-gradient(135deg, var(--dark-green), var(--primary-green)); }
        .work-info { padding: 16px; }
        .work-title { font-size: 15px; font-weight: 600; color: var(--text-primary); margin-bottom: 6px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .work-type { display: inline-block; padding: 4px 10px; background: var(--primary-green); color: white; border-radius: 6px; font-size: 11px; font-weight: 600; text-transform: uppercase; }
        .page-header { margin-bottom: 32px; }
        .page-title { font-family: 'Crimson Pro', serif; font-size: 36px; font-weight: 700; margin-bottom: 8px; }
        .page-subtitle { color: var(--text-secondary); }
        @media (max-width: 1024px) {
            .navbar-container { padding: 0 18px; }
            .navbar-menu { gap: 6px; }
            .nav-link { padding: 10px 12px; }
            .nav-link .nav-text { display: none; }
            .profile-link .nav-text { display: inline; }
        }
        @media (max-width: 768px) {
            .navbar-menu .nav-link:not(.auth-link):not(.profile-link) { display: none; }
            .navbar-container { padding: 0 14px; }
            .logo-row { gap: 12px; }
            .logo-section { gap: 12px; }
            .logo-icon { width: 42px; height: 42px; }
            .logo-icon svg { width: 34px; height: 34px; }
            .logo-text { font-size: 22px; }
            .nav-link { padding: 8px 10px; }
            .profile-link { padding-right: 0; }
            .profile-link .nav-text { display: none; }
            .main-content { padding: 24px 16px; }
            .works-grid { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 16px; }
            .page-title { font-size: 28px; }
        }
        @media (max-width: 480px) {
            .logo-text { font-size: 20px; }
            .user-avatar { width: 34px; height: 34px; }
            .sidebar { width: min(320px, 100vw); left: -100vw; }
            .main-content { padding: 22px 12px; }
            .pagination-wrap { align-items: stretch; }
            .pagination-links { width: 100%; }
            .pagination-link { flex: 1 1 0; }
        }
    </style>
</head>

<body>
    <div class="page-loader" id="pageLoader">
        <div class="page-loader-content">
            <div class="book-loader" aria-hidden="true">
                <div class="book-loader-page"></div>
                <div class="book-loader-page page-two"></div>
                <div class="book-loader-cover"></div>
            </div>
            <div class="loader-label">Opening Library</div>
        </div>
    </div>
    <nav class="navbar" id="navbar">
        <div class="navbar-container">
            <div class="logo-row">
                <div class="menu-toggle" id="menuToggle"><span></span><span></span><span></span></div>
                <div class="logo-section" onclick="window.location.href='{{ route('home') }}'">
                    <div class="logo-icon">
                        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <rect x="20" y="35" width="40" height="55" fill="rgba(255,255,255,0.2)" rx="3" />
                            <rect x="22" y="37" width="36" height="51" fill="rgba(255,255,255,0.3)" rx="2" />
                            <line x1="40" y1="37" x2="40" y2="88" stroke="rgba(255,255,255,0.5)" stroke-width="2" />
                            <path d="M 50 15 L 50 70 M 50 15 L 75 55 M 75 25 L 75 70" stroke="white" stroke-width="7" fill="none" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <span class="logo-text">Nokomi</span>
                </div>
            </div>

            <div class="navbar-menu">
                <a href="{{ route('pages.faq') }}" class="nav-link {{ request()->routeIs('pages.faq') ? 'active' : '' }}"><i class="bi bi-patch-question"></i><span class="nav-text">FAQ</span></a>
                <a href="{{ route('pages.news') }}" class="nav-link {{ request()->routeIs('pages.news') ? 'active' : '' }}"><i class="bi bi-newspaper"></i><span class="nav-text">News</span></a>
                <a href="{{ route('pages.notifications') }}" class="nav-link {{ request()->routeIs('pages.notifications') ? 'active' : '' }}" style="position: relative;"><i class="bi bi-bell-fill"></i><span class="nav-text">Notifikasi</span><span class="notification-badge">!</span></a>
                @guest
                    <a href="{{ route('login') }}" class="nav-link auth-link"><i class="bi bi-box-arrow-in-right"></i><span class="nav-text">Login</span></a>
                    <a href="{{ route('register') }}" class="nav-link auth-link"><i class="bi bi-person-plus-fill"></i><span class="nav-text">Register</span></a>
                @endguest
                @auth
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i><span class="nav-text">Dashboard</span></a>
                    @if (auth()->user()->role?->name === 'uploader')
                        <a href="{{ route('works.index') }}" class="nav-link {{ request()->routeIs('works.*') ? 'active' : '' }}"><i class="bi bi-collection-fill"></i><span class="nav-text">Kelola Karya</span></a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="nav-link profile-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"><div class="user-avatar">@if(Auth::user()->avatar_url)<img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}">@else{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}@endif</div><span class="nav-text">Profile</span></a>
                @endauth
            </div>
        </div>
    </nav>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-content">
            <div class="sidebar-section">
                <div class="sidebar-title">Menu Utama</div>
                <div class="sidebar-menu">
                    <a href="{{ route('pages.search') }}" class="sidebar-link {{ request()->routeIs('pages.search') ? 'active' : '' }}"><span class="sidebar-icon"><i class="bi bi-search"></i></span><span>Search</span></a>
                    <a href="{{ auth()->check() ? route('bookmarks.index') : route('login') }}" class="sidebar-link {{ request()->routeIs('bookmarks.*') ? 'active' : '' }}"><span class="sidebar-icon"><i class="bi bi-bookmark-heart-fill"></i></span><span>Bookmarks</span></a>
                    <a href="{{ route('pages.collection') }}" class="sidebar-link {{ request()->routeIs('pages.collection') ? 'active' : '' }}"><span class="sidebar-icon"><i class="bi bi-collection-fill"></i></span><span>Collection</span></a>
                </div>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-title">Browse</div>
                <div class="sidebar-menu">
                    <div class="sidebar-dropdown">
                        <a href="#" class="sidebar-link dropdown-trigger" onclick="toggleGenreDropdown(event)">
                            <span class="sidebar-icon"><i class="bi bi-tags-fill"></i></span>
                            <span style="flex: 1;">Genre</span>
                            <span class="dropdown-arrow" id="genreArrow"><i class="bi bi-chevron-down"></i></span>
                        </a>
                        <div class="dropdown-content" id="genreDropdown">
                            @if (isset($globalGenres) && $globalGenres->count() > 0)
                                @foreach ($globalGenres as $genre)
                                    <a href="{{ route('home', ['genre' => $genre->id]) }}" class="dropdown-item">
                                        <span class="sidebar-icon"><i class="{{ genre_icon($genre->name) }}"></i></span>
                                        <span>{{ $genre->name }}</span>
                                    </a>
                                @endforeach
                            @else
                                <div class="dropdown-item">Belum ada genre</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-title">Library</div>
                <div class="sidebar-menu">
                    <a href="{{ route('pages.history') }}" class="sidebar-link {{ request()->routeIs('pages.history') ? 'active' : '' }}"><span class="sidebar-icon"><i class="bi bi-clock-history"></i></span><span>History</span></a>
                </div>
            </div>

            @auth
                <div class="sidebar-section">
                    <div class="sidebar-title">Profile</div>
                    <div class="profile-card">
                        <div style="display:flex; align-items:center; gap:12px; margin-bottom:10px;">
                            <div class="user-avatar" style="width:44px; height:44px; flex-basis:44px;">@if(auth()->user()->avatar_url)<img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}">@else{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}@endif</div>
                            <div>
                                <div class="profile-name">{{ auth()->user()->name }}</div>
                                <div class="profile-role">{{ '@'.auth()->user()->username }}</div>
                            </div>
                        </div>
                        <div class="profile-role">{{ ucfirst(auth()->user()->role?->name ?? 'user') }}</div>
                        <div class="profile-actions">
                            <a href="{{ route('profile.edit') }}" class="profile-action"><i class="bi bi-person-circle"></i><span>Edit Profile</span></a>
                            <a href="{{ route('logout') }}" class="profile-action logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bi bi-box-arrow-right"></i><span>Sign Out</span></a>
                        </div>
                    </div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                </div>
            @endauth
        </div>
    </aside>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <main class="main-content" id="mainContent"><div class="container">@yield('content')</div></main>

    <script>
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const navbar = document.getElementById('navbar');
        const sidebarLinks = document.querySelectorAll('.sidebar a');
        const pageLoader = document.getElementById('pageLoader');
        function closeSidebar() {
            menuToggle.classList.remove('active');
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        }
        function toggleSidebar() {
            menuToggle.classList.toggle('active');
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        }
        menuToggle.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', closeSidebar);
        sidebarLinks.forEach((link) => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) closeSidebar();
            });
        });
        function markMediaLoaded(image) {
            const shell = image.closest('.media-shell');
            if (shell) {
                shell.classList.add('is-loaded');
            }
        }
        function initializeReveal() {
            const revealItems = document.querySelectorAll('.page-reveal');
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (prefersReducedMotion || !('IntersectionObserver' in window)) {
                revealItems.forEach((item) => item.classList.add('is-visible'));
                return;
            }
            const revealObserver = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) {
                        return;
                    }
                    entry.target.classList.add('is-visible');
                    revealObserver.unobserve(entry.target);
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
            revealItems.forEach((item) => revealObserver.observe(item));
        }
        function initializeTilt() {
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                return;
            }
            document.querySelectorAll('.work-card').forEach((card) => {
                card.classList.add('js-tilt');
                card.addEventListener('mousemove', (event) => {
                    if (window.innerWidth <= 768) {
                        return;
                    }
                    const rect = card.getBoundingClientRect();
                    const px = (event.clientX - rect.left) / rect.width;
                    const py = (event.clientY - rect.top) / rect.height;
                    const rotateY = (px - 0.5) * 8;
                    const rotateX = (0.5 - py) * 8;
                    card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-8px)`;
                });
                card.addEventListener('mouseleave', () => {
                    card.style.transform = '';
                });
            });
        }
        document.addEventListener('DOMContentLoaded', () => {
            document.body.classList.add('page-ready');
            initializeReveal();
            initializeTilt();
            document.querySelectorAll('[data-media-loading]').forEach((image) => {
                if (image.complete && image.naturalWidth > 0) {
                    markMediaLoaded(image);
                    return;
                }
                image.addEventListener('load', () => markMediaLoaded(image), { once: true });
                image.addEventListener('error', () => markMediaLoaded(image), { once: true });
            });
            window.setTimeout(() => pageLoader?.classList.add('is-hidden'), 420);
            document.querySelectorAll('a[href]').forEach((link) => {
                link.addEventListener('click', (event) => {
                    const href = link.getAttribute('href');
                    const target = link.getAttribute('target');
                    if (!href || href.startsWith('#') || href.startsWith('javascript:') || target === '_blank' || event.ctrlKey || event.metaKey) {
                        return;
                    }
                    try {
                        const url = new URL(href, window.location.origin);
                        if (url.origin === window.location.origin) {
                            pageLoader?.classList.remove('is-hidden');
                        }
                    } catch (error) {}
                });
            });
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') closeSidebar();
        });
        let navbarScrollTicking = false;
        window.addEventListener('scroll', () => {
            if (navbarScrollTicking) {
                return;
            }
            navbarScrollTicking = true;
            window.requestAnimationFrame(() => {
                navbar.classList.toggle('scrolled', window.pageYOffset > 50);
                navbarScrollTicking = false;
            });
        }, { passive: true });
        function toggleGenreDropdown(event) { event.preventDefault(); document.getElementById('genreDropdown').classList.toggle('active'); document.getElementById('genreArrow').classList.toggle('rotated'); }
    </script>
</body>

</html>


