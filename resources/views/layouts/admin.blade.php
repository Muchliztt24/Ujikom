<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Nokomi') }} - Admin</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --admin-bg: #06131a;
            --admin-bg-soft: #0b1d26;
            --admin-surface: rgba(10, 24, 32, 0.88);
            --admin-surface-strong: rgba(8, 19, 26, 0.96);
            --admin-border: rgba(144, 201, 186, 0.14);
            --admin-text: #e6f2ef;
            --admin-text-soft: #9eb7b0;
            --admin-accent: #4ac6a8;
            --admin-accent-strong: #1f8f79;
            --admin-accent-warm: #f4b860;
            --admin-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
        }
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(31, 143, 121, 0.22), transparent 34%),
                radial-gradient(circle at top right, rgba(244, 184, 96, 0.12), transparent 24%),
                linear-gradient(160deg, #041018 0%, #081923 42%, #0d2028 100%);
            color: var(--admin-text);
            min-height: 100vh;
            overflow-x: hidden;
        }
        .admin-reveal { opacity: 0; transform: translateY(22px) scale(0.99); transition: opacity 0.65s cubic-bezier(.22,1,.36,1), transform 0.65s cubic-bezier(.22,1,.36,1); will-change: opacity, transform; }
        .admin-reveal.is-visible { opacity: 1; transform: translateY(0) scale(1); }
        .page-loader { position: fixed; inset: 0; background: rgba(6, 19, 26, 0.96); display: flex; align-items: center; justify-content: center; z-index: 2400; opacity: 1; visibility: visible; transition: opacity 0.4s ease, visibility 0.4s ease; }
        .page-loader.is-hidden { opacity: 0; visibility: hidden; }
        .page-loader-content { display: grid; justify-items: center; gap: 16px; }
        .book-loader { position: relative; width: 86px; height: 62px; perspective: 120px; }
        .book-loader-cover,
        .book-loader-page { position: absolute; inset: 0; border-radius: 8px; transform-origin: left center; }
        .book-loader-cover { background: linear-gradient(135deg, #1f8f79, #4ac6a8); box-shadow: 0 14px 28px rgba(0,0,0,0.24); animation: bookOpen 1.3s ease-in-out infinite; }
        .book-loader-page { background: linear-gradient(180deg, rgba(255,255,255,0.96), rgba(228,238,241,0.9)); left: 8px; right: 4px; animation: pageFlip 1.3s ease-in-out infinite; }
        .book-loader-page.page-two { animation-delay: 0.18s; opacity: 0.88; }
        .loader-label { color: var(--admin-text-soft); font-size: 13px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
        .navbar { position: fixed; inset: 0 0 auto 0; height: 70px; background: linear-gradient(135deg, rgba(5, 18, 24, 0.96), rgba(10, 30, 38, 0.94)); border-bottom: 1px solid var(--admin-border); display: flex; align-items: center; padding: 0 30px; z-index: 1000; box-shadow: 0 8px 30px rgba(0,0,0,0.28); backdrop-filter: blur(12px); gap: 16px; }
        .nav-menu-toggle { display: none; width: 42px; height: 42px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.08); background: rgba(255,255,255,0.04); color: var(--admin-text); align-items: center; justify-content: center; cursor: pointer; }
        .navbar-brand { display: flex; align-items: center; gap: 15px; margin-left: 250px; }
        .logo { width: 50px; height: 50px; border-radius: 10px; background: linear-gradient(135deg, #16493f, #3db69b); display: flex; align-items: center; justify-content: center; box-shadow: inset 0 1px 0 rgba(255,255,255,0.08); }
        .brand-name { color: var(--admin-text); font-size: 28px; font-weight: 700; }
        .navbar-right { margin-left: auto; }
        .user-link { color: var(--admin-text); text-decoration: none; display: flex; align-items: center; gap: 10px; padding: 8px 12px; border-radius: 999px; transition: background 0.3s ease, border-color 0.3s ease; border: 1px solid transparent; }
        .user-link:hover { background: rgba(255, 255, 255, 0.06); border-color: var(--admin-border); }
        .user-avatar { width: 40px; height: 40px; background: linear-gradient(135deg, #3db69b, #79d9c1); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #062028; overflow: hidden; }
        .user-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .sidebar { width: 250px; position: fixed; top: 70px; left: 0; height: calc(100vh - 70px); background: linear-gradient(180deg, rgba(4, 16, 22, 0.97) 0%, rgba(8, 24, 31, 0.96) 48%, rgba(10, 28, 33, 0.95) 100%); border-right: 1px solid var(--admin-border); overflow-y: auto; box-shadow: 10px 0 30px rgba(0,0,0,0.18); }
        .sidebar-menu { padding: 20px 0; }
        .menu-section { margin-bottom: 28px; }
        .menu-section-title { color: var(--admin-text-soft); font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; padding: 10px 25px; }
        .menu-item, .account-action { display: flex; align-items: center; gap: 14px; padding: 15px 25px; color: var(--admin-text); text-decoration: none; transition: all 0.3s ease; border-left: 4px solid transparent; }
        .menu-item:hover, .account-action:hover { background: linear-gradient(90deg, rgba(74, 198, 168, 0.14), rgba(255,255,255,0.02)); border-left-color: var(--admin-accent); color: white; }
        .menu-item.active { background: linear-gradient(90deg, rgba(74, 198, 168, 0.18), rgba(255,255,255,0.04)); border-left-color: var(--admin-accent-warm); color: white; }
        .menu-icon { width: 20px; text-align: center; }
        .account-card { margin: 0 16px; padding: 16px; border-radius: 16px; background: linear-gradient(180deg, rgba(255,255,255,0.06), rgba(255,255,255,0.03)); border: 1px solid var(--admin-border); color: var(--admin-text); box-shadow: inset 0 1px 0 rgba(255,255,255,0.04); }
        .account-card strong { display: block; font-size: 15px; }
        .account-card span { display: block; font-size: 12px; color: var(--admin-text-soft); margin-top: 4px; }
        .account-actions { display: grid; gap: 8px; margin-top: 14px; }
        .account-action { padding: 10px 12px; border-radius: 10px; background: rgba(0,0,0,0.18); border: 1px solid rgba(255,255,255,0.04); border-left: none; }
        .sidebar-overlay { position: fixed; inset: 70px 0 0 0; background: rgba(0,0,0,0.55); opacity: 0; visibility: hidden; transition: all 0.3s ease; z-index: 998; }
        .sidebar-overlay.active { opacity: 1; visibility: visible; }
        .main-content { margin-left: 250px; margin-top: 70px; padding: 30px; min-height: calc(100vh - 70px); }
        .content-header { background: linear-gradient(180deg, rgba(9, 24, 31, 0.96), rgba(8, 18, 24, 0.92)); border: 1px solid var(--admin-border); padding: 25px; border-radius: 20px; margin-bottom: 25px; box-shadow: var(--admin-shadow); backdrop-filter: blur(10px); }
        .content-header h1 { color: var(--admin-text); font-size: 28px; margin-bottom: 5px; }
        .content-header p { color: var(--admin-text-soft); font-size: 14px; }
        .content-body { background: linear-gradient(180deg, rgba(9, 21, 28, 0.94), rgba(6, 16, 22, 0.98)); border: 1px solid var(--admin-border); padding: 30px; border-radius: 20px; min-height: 400px; box-shadow: var(--admin-shadow); backdrop-filter: blur(10px); }
        .admin-muted { color: var(--admin-text-soft) !important; }
        .admin-surface { background: linear-gradient(180deg, rgba(13, 31, 39, 0.98), rgba(9, 24, 31, 0.95)); border: 1px solid var(--admin-border); border-radius: 16px; box-shadow: inset 0 1px 0 rgba(255,255,255,0.03); }
        .admin-empty { padding: 40px; text-align: center; border-radius: 16px; border: 1px dashed rgba(144, 201, 186, 0.18); color: var(--admin-text-soft); background: rgba(255,255,255,0.02); }
        .admin-filter-link { padding: 10px 14px; text-decoration: none; border-radius: 10px; font-weight: 700; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.05); color: var(--admin-text-soft); transition: all 0.3s ease; }
        .admin-filter-link:hover { color: white; border-color: var(--admin-border); background: rgba(255,255,255,0.08); }
        .admin-filter-link.active { background: linear-gradient(135deg, var(--admin-accent-strong), var(--admin-accent)); color: #041018; border-color: transparent; }
        .admin-table-wrap { overflow-x: auto; border-radius: 16px; border: 1px solid var(--admin-border); background: linear-gradient(180deg, rgba(12, 30, 38, 0.96), rgba(8, 21, 27, 0.98)); }
        .admin-table { width: 100%; border-collapse: collapse; color: var(--admin-text); }
        .admin-table thead { background: linear-gradient(90deg, rgba(74, 198, 168, 0.18), rgba(244, 184, 96, 0.08)); }
        .admin-table th { padding: 14px; text-align: left; color: #f1fbf8; font-size: 13px; letter-spacing: 0.02em; }
        .admin-table td { padding: 14px; color: #dcebe7; border-top: 1px solid rgba(255,255,255,0.05); vertical-align: top; }
        .admin-table tbody tr:hover { background: rgba(255,255,255,0.03); }
        .admin-chip { display: inline-flex; align-items: center; padding: 5px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; background: rgba(255,255,255,0.06); color: var(--admin-text-soft); }
        .admin-chip.success { background: rgba(74, 198, 168, 0.16); color: #8ef0d7; }
        .admin-chip.warning { background: rgba(244, 184, 96, 0.16); color: #ffd38d; }
        .admin-chip.danger { background: rgba(220, 53, 69, 0.14); color: #ff9eab; }
        .admin-btn-row { display: flex; gap: 8px; flex-wrap: wrap; }
        .admin-pagination { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; margin-top: 24px; }
        .admin-pagination-meta { color: var(--admin-text-soft); font-size: 14px; }
        .admin-pagination-links { display: flex; gap: 8px; flex-wrap: wrap; }
        .admin-page-link { display: inline-flex; align-items: center; justify-content: center; min-width: 40px; padding: 10px 14px; border-radius: 10px; text-decoration: none; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.06); color: var(--admin-text); font-weight: 700; transition: all 0.3s ease; }
        .admin-page-link:hover { background: rgba(255,255,255,0.09); border-color: var(--admin-border); }
        .admin-page-link.disabled { opacity: 0.45; pointer-events: none; }
        .admin-card-grid { display: grid; gap: 18px; }
        .admin-form-shell { max-width: 920px; display: grid; gap: 18px; }
        .admin-form-card { padding: 22px; }
        .admin-form-title { font-size: 18px; font-weight: 700; color: var(--admin-text); margin-bottom: 14px; }
        .admin-field { display: grid; gap: 8px; margin-bottom: 18px; }
        .admin-label { color: var(--admin-text); font-size: 14px; font-weight: 700; }
        .admin-help { color: var(--admin-text-soft); font-size: 13px; line-height: 1.6; }
        .admin-error { color: #ff9eab; font-size: 13px; }
        .admin-input, .admin-select, .admin-textarea { width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.08); background: rgba(255,255,255,0.04); color: var(--admin-text); font: inherit; transition: all 0.3s ease; }
        .admin-input:focus, .admin-select:focus, .admin-textarea:focus { outline: none; border-color: rgba(74, 198, 168, 0.55); box-shadow: 0 0 0 3px rgba(74, 198, 168, 0.12); background: rgba(255,255,255,0.06); }
        .admin-select option { color: #081923; }
        .admin-textarea { min-height: 140px; resize: vertical; }
        .admin-form-actions { display: flex; gap: 10px; flex-wrap: wrap; padding-top: 6px; }
        .admin-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 11px 16px; border-radius: 10px; text-decoration: none; border: none; cursor: pointer; font: inherit; font-weight: 700; transition: all 0.3s ease; }
        .admin-btn.primary { background: linear-gradient(135deg, var(--admin-accent-strong), var(--admin-accent)); color: #041018; }
        .admin-btn.secondary { background: rgba(255,255,255,0.06); color: var(--admin-text); border: 1px solid rgba(255,255,255,0.08); }
        .admin-btn.info { background: #17a2b8; color: white; }
        .admin-btn.warning { background: #f4b860; color: #1b1204; }
        .admin-btn.danger { background: #dc3545; color: white; }
        .admin-btn.success { background: #28a745; color: white; }
        .admin-btn:hover { transform: translateY(-1px); filter: brightness(1.03); }
        .admin-stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; }
        .admin-stat-card { padding: 22px; }
        .admin-stat-label { color: var(--admin-text-soft); font-size: 13px; text-transform: uppercase; letter-spacing: 0.04em; }
        .admin-stat-value { color: var(--admin-text); font-size: 32px; font-weight: 800; margin-top: 10px; }
        .admin-stat-note { color: var(--admin-text-soft); font-size: 13px; margin-top: 8px; }
        .admin-list { display: grid; gap: 12px; }
        .admin-list-item { padding: 14px 16px; border-radius: 14px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); }
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
            .admin-reveal,
            .menu-item,
            .account-action,
            .nav-menu-toggle {
                transition: none !important;
                transform: none !important;
            }
        }
        @media (max-width: 1024px) {
            .nav-menu-toggle { display: inline-flex; }
            .navbar { padding: 0 18px; }
            .navbar-brand { margin-left: 0; }
            .sidebar { left: -280px; width: min(280px, 86vw); transition: left 0.3s ease; z-index: 999; }
            .sidebar.active { left: 0; }
            .main-content { margin-left: 0; padding: 24px 16px; }
        }
        @media (max-width: 768px) {
            .brand-name { font-size: 24px; }
            .user-link span { display: none; }
            .content-header, .content-body { padding: 20px; }
            .content-header h1 { font-size: 24px; }
            .admin-table th, .admin-table td { padding: 12px; font-size: 13px; }
        }
        @media (max-width: 480px) {
            .navbar { padding: 0 12px; }
            .logo { width: 42px; height: 42px; }
            .brand-name { font-size: 21px; }
            .main-content { padding: 18px 12px; }
            .content-header, .content-body { padding: 16px; border-radius: 16px; }
            .admin-btn-row { width: 100%; }
            .admin-btn-row .admin-btn { flex: 1 1 100%; }
        }
    </style>
</head>

<body>
    <div class="page-loader" id="adminPageLoader">
        <div class="page-loader-content">
            <div class="book-loader" aria-hidden="true">
                <div class="book-loader-page"></div>
                <div class="book-loader-page page-two"></div>
                <div class="book-loader-cover"></div>
            </div>
            <div class="loader-label">Preparing Dashboard</div>
        </div>
    </div>
    <nav class="navbar">
        <button type="button" class="nav-menu-toggle" id="adminMenuToggle" aria-label="Buka menu">
            <i class="bi bi-list"></i>
        </button>
        <div class="navbar-brand">
            <div class="logo">
                <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" width="45" height="45">
                    <rect x="20" y="40" width="40" height="50" fill="#1e5f4f" rx="3" />
                    <rect x="22" y="42" width="36" height="46" fill="#2d8b73" rx="2" />
                    <line x1="40" y1="42" x2="40" y2="88" stroke="#1e5f4f" stroke-width="2" />
                    <path d="M 50 15 L 50 70 M 50 15 L 75 55 M 75 25 L 75 70" stroke="white" stroke-width="6" fill="none" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <span class="brand-name">Nokomi</span>
        </div>

        <div class="navbar-right">
            <a href="{{ route('profile.edit') }}" class="user-link">
                <span>{{ Auth::user()->name ?? 'Admin' }}</span>
                <div class="user-avatar">@if(Auth::user()?->avatar_url)<img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}">@else{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}@endif</div>
            </a>
        </div>
    </nav>

    <aside class="sidebar" id="adminSidebar">
        <div class="sidebar-menu">
            <div class="menu-section">
                <div class="menu-section-title">Utama</div>
                <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"><div class="menu-icon"><i class="bi bi-speedometer2"></i></div><span>Dashboard</span></a>
                <a href="{{ url('/') }}" class="menu-item {{ request()->is('/') ? 'active' : '' }}"><div class="menu-icon"><i class="bi bi-house-door-fill"></i></div><span>Halaman Utama</span></a>
                @if (auth()->user()->role?->name === 'admin')
                    <a href="{{ route('admin.works.pending') }}" class="menu-item {{ request()->routeIs('admin.works.pending') ? 'active' : '' }}"><div class="menu-icon"><i class="bi bi-patch-check-fill"></i></div><span>Approval Karya</span></a>
                    <a href="{{ route('admin.users.index') }}" class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><div class="menu-icon"><i class="bi bi-people-fill"></i></div><span>Kelola Pengguna</span></a>
                    <a href="{{ route('admin.genres.index') }}" class="menu-item {{ request()->routeIs('admin.genres.*') ? 'active' : '' }}"><div class="menu-icon"><i class="bi bi-tags-fill"></i></div><span>Kelola Genre</span></a>
                    <a href="{{ route('admin.works.index') }}" class="menu-item {{ request()->routeIs('admin.works.index') || request()->routeIs('admin.works.show') ? 'active' : '' }}"><div class="menu-icon"><i class="bi bi-collection-fill"></i></div><span>Moderasi Karya</span></a>
                    <a href="{{ route('admin.chapters.index') }}" class="menu-item {{ request()->routeIs('admin.chapters.*') ? 'active' : '' }}"><div class="menu-icon"><i class="bi bi-file-earmark-text-fill"></i></div><span>Moderasi Chapter</span></a>
                    <a href="{{ route('admin.chapter-images.index') }}" class="menu-item {{ request()->routeIs('admin.chapter-images.*') ? 'active' : '' }}"><div class="menu-icon"><i class="bi bi-images"></i></div><span>Moderasi Gambar</span></a>
                @elseif (auth()->user()->role?->name === 'uploader')
                    <a href="{{ route('works.index') }}" class="menu-item {{ request()->routeIs('works.*') ? 'active' : '' }}"><div class="menu-icon"><i class="bi bi-collection-fill"></i></div><span>Kelola Karya</span></a>
                @endif
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Profile</div>
                <div class="account-card">
                    <div style="display:flex; align-items:center; gap:12px; margin-bottom:10px;">
                        <div class="user-avatar">@if(auth()->user()->avatar_url)<img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}">@else{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}@endif</div>
                        <div>
                            <strong>{{ auth()->user()->name }}</strong>
                            <span>{{ '@'.auth()->user()->username }}</span>
                        </div>
                    </div>
                    <span>{{ ucfirst(auth()->user()->role?->name ?? 'user') }}</span>
                    <div class="account-actions">
                        <a href="{{ route('profile.edit') }}" class="account-action"><i class="bi bi-person-circle"></i><span>Edit Profile</span></a>
                        <a href="{{ route('logout') }}" class="account-action" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bi bi-box-arrow-right"></i><span>Sign Out</span></a>
                    </div>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
            </div>
        </div>
    </aside>

    <div class="sidebar-overlay" id="adminSidebarOverlay"></div>

    <main class="main-content admin-reveal">@yield('content')</main>

    <script>
        const adminMenuToggle = document.getElementById('adminMenuToggle');
        const adminSidebar = document.getElementById('adminSidebar');
        const adminSidebarOverlay = document.getElementById('adminSidebarOverlay');
        const adminSidebarLinks = document.querySelectorAll('#adminSidebar a');
        const adminPageLoader = document.getElementById('adminPageLoader');

        function closeAdminSidebar() {
            adminSidebar.classList.remove('active');
            adminSidebarOverlay.classList.remove('active');
        }

        function toggleAdminSidebar() {
            adminSidebar.classList.toggle('active');
            adminSidebarOverlay.classList.toggle('active');
        }

        adminMenuToggle?.addEventListener('click', toggleAdminSidebar);
        adminSidebarOverlay?.addEventListener('click', closeAdminSidebar);
        adminSidebarLinks.forEach((link) => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 1024) closeAdminSidebar();
            });
        });
        function initializeAdminReveal() {
            const items = document.querySelectorAll('.admin-reveal, .content-header, .content-body, .admin-stat-card, .admin-list-item');
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            items.forEach((item) => item.classList.add('admin-reveal'));
            if (prefersReducedMotion || !('IntersectionObserver' in window)) {
                items.forEach((item) => item.classList.add('is-visible'));
                return;
            }
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) {
                        return;
                    }
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -30px 0px' });
            items.forEach((item) => observer.observe(item));
        }
        document.addEventListener('DOMContentLoaded', () => {
            initializeAdminReveal();
            window.setTimeout(() => adminPageLoader?.classList.add('is-hidden'), 420);
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
                            adminPageLoader?.classList.remove('is-hidden');
                        }
                    } catch (error) {}
                });
            });
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') closeAdminSidebar();
        });
    </script>
</body>

</html>
