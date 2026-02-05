<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Nokomi') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Tailwind CSS v4 - Same as your welcome.blade.php -->
    <style>
        /*! tailwindcss v4.0.7 | MIT License | https://tailwindcss.com */
        @layer theme {

            :root,
            :host {
                --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
                --spacing: .25rem
            }
        }

        @layer base {

            *,
            :after,
            :before,
            ::backdrop {
                box-sizing: border-box;
                border: 0 solid;
                margin: 0;
                padding: 0
            }

            html,
            :host {
                -webkit-text-size-adjust: 100%;
                line-height: 1.5;
                font-family: var(--font-sans, ui-sans-serif, system-ui, sans-serif)
            }
        }

        /* Custom Admin Styles */
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            background-color: #f0f4f3;
        }

        /* Navbar */
        .navbar {
            background: linear-gradient(135deg, #1e5f4f 0%, #2d8b73 100%);
            height: 70px;
            display: flex;
            align-items: center;
            padding: 0 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-left: 250px;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #48c9b0 0%, #5fd4bd 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            box-shadow: 0 4px 15px rgba(72, 201, 176, 0.3);
        }

        .logo svg {
            width: 45px;
            height: 45px;
        }

        .brand-name {
            color: white;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .navbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: #48c9b0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #1e5f4f;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #1e5f4f 0%, #2d8b73 50%, #48c9b0 100%);
            height: calc(100vh - 70px);
            position: fixed;
            top: 70px;
            left: 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-section {
            margin-bottom: 30px;
        }

        .menu-section-title {
            color: #b8e6d5;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            padding: 10px 25px;
            letter-spacing: 1px;
        }

        .menu-item {
            padding: 15px 25px;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
            border-left: 4px solid transparent;
        }

        .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: #48c9b0;
            color: white;
        }

        .menu-item.active {
            background-color: rgba(72, 201, 176, 0.2);
            border-left-color: #5fd4bd;
        }

        .menu-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            margin-top: 70px;
            padding: 30px;
            min-height: calc(100vh - 70px);
        }

        .content-header {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .content-header h1 {
            color: #1e5f4f;
            font-size: 28px;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .content-header p {
            color: #6c757d;
            font-size: 14px;
        }

        .content-body {
            background: white;
            padding: 30px;
            border-radius: 10px;
            min-height: 400px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        /* Scrollbar Styling */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(72, 201, 176, 0.5);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(72, 201, 176, 0.7);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-brand {
                margin-left: 0;
            }

            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-brand">
            <div class="logo-container">
                <div class="logo">
                    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <!-- Buku -->
                        <rect x="20" y="40" width="40" height="50" fill="#1e5f4f" rx="3" />
                        <rect x="22" y="42" width="36" height="46" fill="#2d8b73" rx="2" />
                        <line x1="40" y1="42" x2="40" y2="88" stroke="#1e5f4f"
                            stroke-width="2" />
                        <line x1="25" y1="50" x2="35" y2="50" stroke="#48c9b0"
                            stroke-width="1.5" />
                        <line x1="25" y1="56" x2="35" y2="56" stroke="#48c9b0"
                            stroke-width="1.5" />
                        <line x1="25" y1="62" x2="35" y2="62" stroke="#48c9b0"
                            stroke-width="1.5" />
                        <line x1="45" y1="50" x2="55" y2="50" stroke="#48c9b0"
                            stroke-width="1.5" />
                        <line x1="45" y1="56" x2="55" y2="56" stroke="#48c9b0"
                            stroke-width="1.5" />
                        <line x1="45" y1="62" x2="55" y2="62" stroke="#48c9b0"
                            stroke-width="1.5" />

                        <!-- Huruf N -->
                        <path d="M 50 15 L 50 70 M 50 15 L 75 55 M 75 25 L 75 70" stroke="white" stroke-width="6"
                            fill="none" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <span class="brand-name">Nokomi</span>
            </div>
        </div>
        <div class="navbar-right">
            <div class="user-info">
                <span>{{ Auth::user()->name ?? 'Admin' }}</span>
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-menu">
            <div class="menu-section">
                <div class="menu-section-title">Menu Utama</div>
                <a href="{{ url('/') }}" class="menu-item {{ request()->is('/') ? 'active' : '' }}">
                    <div class="menu-icon">🏠</div>
                    <span>Halaman Utama</span>
                </a>
                <a href="{{ route('home') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <div class="menu-icon">📊</div>
                    <span>Dashboard</span>
                </a>
                @auth
                    @if (auth()->user()->role?->name === 'uploader')
                        <a href="{{ route('works.index') }}"
                            class="menu-item {{ request()->routeIs('works.*') ? 'active' : '' }}">
                            <div class="menu-icon">📚</div>
                            <span>Kelola Works</span>
                        </a>
                    @endif
                    @if (auth()->user()->role?->name === 'admin')
                        <a href="{{ route('admin.users.index') }}"
                            class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <div class="menu-icon">👤</div>
                            <span>Kelola Pengguna</span>
                        </a>
                    @endif
                    @if (auth()->user()->role?->name === 'admin')
                        <a href="{{ route('admin.works.pending') }}"
                            class="menu-item {{ request()->routeIs('admin.works.*') ? 'active' : '' }}">
                            <div class="menu-icon">🛂</div>
                            <span>Approval Karya</span>
                        </a>
                    @endif
                    @if (auth()->user()->role?->name === 'admin')
                        <a href="{{ route('admin.genres.index') }}"
                            class="menu-item {{ request()->routeIs('admin.genres.*') ? 'active' : '' }}">
                            <div class="menu-icon">🏷️</div>
                            <span>Kelola Genre</span>
                        </a>
                    @endif
                    @if (auth()->user()->role?->name === 'admin')
                        <a href="{{ route('admin.works.index') }}"
                            class="menu-item {{ request()->routeIs('admin.works.*') ? 'active' : '' }}">
                            <div class="menu-icon">🛡️</div>
                            <span>Moderasi Works</span>
                        </a>
                    @endif
                    @if (auth()->user()->role?->name === 'admin')
                        <a href="{{ route('admin.chapters.index') }}"
                            class="menu-item {{ request()->routeIs('admin.chapters.*') ? 'active' : '' }}">
                            <div class="menu-icon">📑</div>
                            <span>Moderasi Chapter</span>
                        </a>
                    @endif
                    @if (auth()->user()->role?->name === 'admin')
                        <a href="{{ route('admin.chapter-images.index') }}"
                            class="menu-item {{ request()->routeIs('admin.chapter-images.*') ? 'active' : '' }}">
                            <div class="menu-icon">🖼️</div>
                            <span>Moderasi Chapter Image</span>
                        </a>
                    @endif
                @endauth
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Pengaturan</div>
                <a href="#" class="menu-item">
                    <div class="menu-icon">⚙️</div>
                    <span>Konfigurasi</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Lainnya</div>
                <a href="{{ route('logout') }}" class="menu-item"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <div class="menu-icon">🚪</div>
                    <span>Keluar</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <script>
        // Toggle menu active state
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function(e) {
                if (!this.getAttribute('href').startsWith('http') && this.getAttribute('href') !== '#') {
                    return; // Let Laravel routing handle it
                }
            });
        });
    </script>
</body>

</html>
