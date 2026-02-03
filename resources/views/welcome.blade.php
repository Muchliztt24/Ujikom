<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Nokomi') }} - Baca Novel & Comic Online</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Crimson+Pro:wght@400;600;700&family=DM+Sans:wght@400;500;700&display=swap"
        rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-green: #2d8b73;
            --dark-green: #1e5f4f;
            --light-green: #48c9b0;
            --accent-green: #5fd4bd;
            --bg-main: #0f1419;
            --bg-card: #1a1f2e;
            --bg-hover: #252d3d;
            --text-primary: #e8eaed;
            --text-secondary: #9aa0a6;
            --border-color: #2d3748;
            --danger: #ef4444;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg-main);
            color: var(--text-primary);
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            background: rgba(15, 20, 25, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            padding: 0 24px;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .navbar-container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Logo */
        .logo-section {
            display: flex;
            align-items: center;
            gap: 16px;
            cursor: pointer;
        }

        .logo-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary-green), var(--light-green));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            box-shadow: 0 4px 15px rgba(45, 139, 115, 0.3);
        }

        .logo-icon svg {
            width: 40px;
            height: 40px;
        }

        .logo-text {
            font-family: 'Crimson Pro', serif;
            font-size: 26px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.5px;
        }

        /* Hamburger Menu */
        .menu-toggle {
            display: flex;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .menu-toggle:hover {
            background: var(--bg-hover);
        }

        .menu-toggle span {
            width: 24px;
            height: 2px;
            background: var(--text-primary);
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .menu-toggle.active span:nth-child(1) {
            transform: rotate(45deg) translate(7px, 7px);
        }

        .menu-toggle.active span:nth-child(2) {
            opacity: 0;
        }

        .menu-toggle.active span:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }

        /* Navbar Menu */
        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-link {
            padding: 10px 16px;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-link:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
        }

        .nav-link.active {
            background: var(--primary-green);
            color: white;
        }

        /* User Avatar */
        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--primary-green);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .user-avatar:hover {
            border-color: var(--light-green);
            transform: scale(1.05);
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 70px;
            left: -300px;
            width: 280px;
            height: calc(100vh - 70px);
            background: rgba(26, 31, 46, 0.98);
            backdrop-filter: blur(10px);
            border-right: 1px solid var(--border-color);
            transition: left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 999;
            overflow-y: auto;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
        }

        .sidebar.active {
            left: 0;
        }

        .sidebar-content {
            padding: 24px 16px;
        }

        .sidebar-section {
            margin-bottom: 32px;
        }

        .sidebar-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-secondary);
            letter-spacing: 1.2px;
            margin-bottom: 12px;
            padding: 0 12px;
        }

        .sidebar-menu {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .sidebar-link:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
            transform: translateX(4px);
        }

        .sidebar-link.active {
            background: linear-gradient(90deg, rgba(45, 139, 115, 0.15), transparent);
            color: var(--light-green);
            border-left: 3px solid var(--light-green);
        }

        .sidebar-link.logout {
            color: var(--danger);
            margin-top: 16px;
            border-top: 1px solid var(--border-color);
            padding-top: 20px;
        }

        .sidebar-link.logout:hover {
            background: rgba(239, 68, 68, 0.1);
            color: #ff6b6b;
        }

        .sidebar-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        /* Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 70px;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 998;
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Main Content */
        .main-content {
            margin-top: 70px;
            padding: 32px 24px;
            min-height: calc(100vh - 70px);
            transition: margin-left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Works Grid */
        .works-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 24px;
            margin-top: 32px;
        }

        .work-card {
            background: var(--bg-card);
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
        }

        .work-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
            border-color: var(--primary-green);
        }

        .work-cover {
            width: 100%;
            aspect-ratio: 3/4;
            object-fit: cover;
            background: linear-gradient(135deg, var(--dark-green), var(--primary-green));
        }

        .work-info {
            padding: 16px;
        }

        .work-title {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 6px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .work-type {
            display: inline-block;
            padding: 4px 10px;
            background: var(--primary-green);
            color: white;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 32px;
        }

        .page-title {
            font-family: 'Crimson Pro', serif;
            font-size: 36px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .page-subtitle {
            font-size: 16px;
            color: var(--text-secondary);
        }

        /* Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--primary-green);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-menu .nav-link {
                display: none;
            }

            .navbar-menu .nav-link:last-child {
                display: flex;
            }

            .works-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 16px;
            }

            .page-title {
                font-size: 28px;
            }
        }

        /* Notification Badge */
        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            width: 18px;
            height: 18px;
            background: var(--danger);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 700;
            border: 2px solid var(--bg-main);
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

        .work-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .work-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .work-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .work-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        .work-card:nth-child(5) {
            animation-delay: 0.5s;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar" id="navbar">
        <div class="navbar-container">
            <!-- Left: Hamburger + Logo -->
            <div style="display: flex; align-items: center; gap: 20px;">
                <div class="menu-toggle" id="menuToggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>

                <div class="logo-section" onclick="window.location.href='{{ route('home') }}'">
                    <div class="logo-icon">
                        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <!-- Buku -->
                            <rect x="20" y="35" width="40" height="55" fill="rgba(255,255,255,0.2)"
                                rx="3" />
                            <rect x="22" y="37" width="36" height="51" fill="rgba(255,255,255,0.3)"
                                rx="2" />
                            <line x1="40" y1="37" x2="40" y2="88"
                                stroke="rgba(255,255,255,0.5)" stroke-width="2" />

                            <!-- Huruf N -->
                            <path d="M 50 15 L 50 70 M 50 15 L 75 55 M 75 25 L 75 70" stroke="white" stroke-width="7"
                                fill="none" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <span class="logo-text">Nokomi</span>
                </div>
            </div>

            <!-- Right: Navigation Menu -->
            <div class="navbar-menu">
                <a href="#" class="nav-link">
                    <span>❓</span> FAQ
                </a>
                <a href="#" class="nav-link">
                    <span>📰</span> News
                </a>
                <a href="#" class="nav-link" style="position: relative;">
                    <span>🔔</span> Notifikasi
                    <span class="notification-badge">3</span>
                </a>
                @auth
                    @if (auth()->user()->role?->name === 'uploader')
                        <a href="{{ route('works.index') }}" class="sidebar-link">
                            <span class="sidebar-icon">📚</span>
                            <span>Kelola Works</span>
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="sidebar-link">
                        <span class="sidebar-icon">🔐</span>
                        <span>Login</span>
                    </a>

                    <a href="{{ route('register') }}" class="sidebar-link">
                        <span class="sidebar-icon">📝</span>
                        <span>Register</span>
                    </a>
                @endauth



                <a href="#" class="nav-link">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                </a>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-content">
            <!-- Main Menu -->
            <div class="sidebar-section">
                <div class="sidebar-title">Menu Utama</div>
                <div class="sidebar-menu">
                    <a href="#" class="sidebar-link">
                        <span class="sidebar-icon">🔍</span>
                        <span>Search</span>
                    </a>
                    <a href="#" class="sidebar-link">
                        <span class="sidebar-icon">🔖</span>
                        <span>Bookmarks</span>
                    </a>
                    <a href="#" class="sidebar-link active">
                        <span class="sidebar-icon">❤️</span>
                        <span>Favorite</span>
                    </a>
                    <a href="#" class="sidebar-link">
                        <span class="sidebar-icon">📚</span>
                        <span>Collection</span>
                    </a>
                </div>
            </div>

            <!-- Browse -->
            <div class="sidebar-section">
                <div class="sidebar-title">Browse</div>
                <div class="sidebar-menu">
                    <a href="#" class="sidebar-link">
                        <span class="sidebar-icon">⚔️</span>
                        <span>Action</span>
                    </a>
                    <a href="#" class="sidebar-link">
                        <span class="sidebar-icon">💕</span>
                        <span>Romance</span>
                    </a>
                    <a href="#" class="sidebar-link">
                        <span class="sidebar-icon">🔮</span>
                        <span>Fantasy</span>
                    </a>
                    <a href="#" class="sidebar-link">
                        <span class="sidebar-icon">🚀</span>
                        <span>Sci-Fi</span>
                    </a>
                    <a href="#" class="sidebar-link">
                        <span class="sidebar-icon">👻</span>
                        <span>Horror</span>
                    </a>
                    <a href="#" class="sidebar-link">
                        <span class="sidebar-icon">😂</span>
                        <span>Comedy</span>
                    </a>
                </div>
            </div>

            <!-- History -->
            <div class="sidebar-section">
                <div class="sidebar-title">Library</div>
                <div class="sidebar-menu">
                    <a href="#" class="sidebar-link">
                        <span class="sidebar-icon">📖</span>
                        <span>History</span>
                    </a>
                </div>
            </div>

            <!-- Logout -->
            <div class="sidebar-section">
                <a href="{{ route('logout') }}" class="sidebar-link logout"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <span class="sidebar-icon">🚪</span>
                    <span>Sign Out</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </aside>

    <!-- Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <script>
        // Sidebar Toggle
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mainContent = document.getElementById('mainContent');

        function toggleSidebar() {
            menuToggle.classList.toggle('active');
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        }

        menuToggle.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);

        // Close sidebar when clicking a link
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    toggleSidebar();
                }
            });
        });

        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        let lastScroll = 0;

        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;

            if (currentScroll > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }

            lastScroll = currentScroll;
        });

        // Active link based on current route
        const currentPath = window.location.pathname;
        document.querySelectorAll('.sidebar-link').forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            }
        });
    </script>
</body>

</html>
