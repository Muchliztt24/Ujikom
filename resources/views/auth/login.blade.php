<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Nokomi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro:wght@500;600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary-green: #2d8b73;
            --dark-green: #1e5f4f;
            --light-green: #48c9b0;
            --bg-main: #0f1419;
            --bg-card: rgba(26, 31, 46, 0.94);
            --bg-input: #111827;
            --bg-hover: #252d3d;
            --text-primary: #e8eaed;
            --text-secondary: #9aa0a6;
            --border-color: rgba(148, 163, 184, 0.18);
            --danger: #ef4444;
        }
        body {
            min-height: 100vh;
            font-family: 'DM Sans', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(72, 201, 176, 0.12), transparent 28%),
                radial-gradient(circle at bottom right, rgba(45, 139, 115, 0.18), transparent 30%),
                linear-gradient(160deg, #0b1116 0%, #0f1419 45%, #131b24 100%);
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .back-home {
            position: fixed;
            top: 22px;
            left: 22px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--text-secondary);
            text-decoration: none;
            padding: 12px 16px;
            border-radius: 14px;
            border: 1px solid transparent;
            transition: all 0.3s ease;
        }
        .back-home:hover {
            background: rgba(255, 255, 255, 0.04);
            border-color: var(--border-color);
            color: white;
        }
        .auth-shell {
            width: 100%;
            max-width: 1060px;
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            border-radius: 28px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.06);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(20px);
        }
        .auth-brand {
            padding: 56px 48px;
            background:
                linear-gradient(180deg, rgba(18, 72, 61, 0.9), rgba(13, 44, 38, 0.94)),
                linear-gradient(135deg, var(--primary-green), var(--light-green));
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 36px;
        }
        .auth-brand::after {
            content: '';
            position: absolute;
            inset: auto -120px -120px auto;
            width: 280px;
            height: 280px;
            background: radial-gradient(circle, rgba(255,255,255,0.2), transparent 70%);
            border-radius: 50%;
        }
        .brand-top { position: relative; z-index: 1; }
        .brand-logo-row {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 28px;
        }
        .brand-logo {
            width: 76px;
            height: 76px;
            border-radius: 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.12);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.18);
        }
        .brand-logo svg { width: 56px; height: 56px; }
        .brand-name {
            font-family: 'Crimson Pro', serif;
            font-size: 40px;
            font-weight: 700;
        }
        .brand-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255,255,255,0.12);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 18px;
        }
        .brand-title {
            font-family: 'Crimson Pro', serif;
            font-size: 44px;
            line-height: 1.04;
            margin-bottom: 14px;
            max-width: 420px;
        }
        .brand-copy {
            max-width: 430px;
            color: rgba(255, 255, 255, 0.82);
            font-size: 15px;
            line-height: 1.75;
        }
        .brand-points {
            position: relative;
            z-index: 1;
            display: grid;
            gap: 12px;
        }
        .brand-point {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 16px;
            background: rgba(7, 20, 17, 0.18);
            border: 1px solid rgba(255,255,255,0.08);
        }
        .brand-point i { font-size: 18px; }
        .auth-card {
            background: var(--bg-card);
            padding: 52px 42px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .auth-card h1 {
            font-family: 'Crimson Pro', serif;
            font-size: 34px;
            margin-bottom: 8px;
        }
        .auth-card p {
            color: var(--text-secondary);
            margin-bottom: 28px;
            line-height: 1.7;
        }
        .alert-error {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            padding: 14px 16px;
            border-radius: 14px;
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.35);
            color: #fecaca;
            margin-bottom: 18px;
            font-size: 14px;
        }
        .field { margin-bottom: 18px; }
        .field label {
            display: block;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .input-wrap { position: relative; }
        .input-wrap i.leading {
            position: absolute;
            top: 50%;
            left: 16px;
            transform: translateY(-50%);
            color: var(--text-secondary);
        }
        .form-input {
            width: 100%;
            padding: 15px 16px 15px 46px;
            border-radius: 14px;
            border: 1px solid var(--border-color);
            background: var(--bg-input);
            color: var(--text-primary);
            font: inherit;
            transition: all 0.3s ease;
        }
        .form-input.password { padding-right: 48px; }
        .form-input:focus {
            outline: none;
            border-color: rgba(72, 201, 176, 0.5);
            box-shadow: 0 0 0 4px rgba(72, 201, 176, 0.08);
        }
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 14px;
            transform: translateY(-50%);
            width: 34px;
            height: 34px;
            border-radius: 10px;
            border: none;
            background: transparent;
            color: var(--text-secondary);
            cursor: pointer;
        }
        .error-text {
            display: inline-flex;
            gap: 6px;
            align-items: center;
            color: #fca5a5;
            font-size: 12px;
            margin-top: 8px;
        }
        .field-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
            margin-bottom: 22px;
            font-size: 14px;
        }
        .remember-me {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--text-secondary);
        }
        .forgot-link, .switch-link a {
            color: var(--light-green);
            text-decoration: none;
            font-weight: 600;
        }
        .submit-btn {
            width: 100%;
            padding: 15px 18px;
            border-radius: 14px;
            border: none;
            cursor: pointer;
            font: inherit;
            font-weight: 700;
            color: white;
            background: linear-gradient(135deg, var(--primary-green), var(--light-green));
            box-shadow: 0 12px 28px rgba(45, 139, 115, 0.28);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .submit-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 32px rgba(45, 139, 115, 0.34);
        }
        .divider {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 24px 0 20px;
            color: var(--text-secondary);
            font-size: 13px;
        }
        .divider::before, .divider::after {
            content: '';
            height: 1px;
            flex: 1;
            background: var(--border-color);
        }
        .social-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 24px;
        }
        .social-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 13px 14px;
            border-radius: 14px;
            border: 1px solid var(--border-color);
            background: rgba(255, 255, 255, 0.02);
            color: var(--text-primary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            transition: all 0.3s ease;
        }
        .social-btn:hover {
            background: var(--bg-hover);
            border-color: rgba(72, 201, 176, 0.45);
        }
        .social-btn.google i { color: #fbbc05; }
        .social-btn.facebook i { color: #60a5fa; }
        .social-btn.x i { color: #e5e7eb; }
        .social-btn.discord i { color: #a5b4fc; }
        .social-btn.github i { color: #cbd5e1; }
        .switch-link {
            text-align: center;
            color: var(--text-secondary);
            font-size: 14px;
        }
        @media (max-width: 900px) {
            .auth-shell { grid-template-columns: 1fr; }
            .auth-brand { padding: 36px 28px; }
            .auth-card { padding: 38px 26px; }
        }
        @media (max-width: 560px) {
            .social-grid { grid-template-columns: 1fr; }
            .brand-title { font-size: 34px; }
            .auth-card h1 { font-size: 30px; }
            .back-home { position: static; margin-bottom: 18px; }
            body { display: block; padding: 16px; }
            .auth-shell { margin: 0 auto; }
            .auth-brand { padding: 28px 22px; }
            .auth-card { padding: 30px 20px; }
        }
        @media (max-width: 420px) {
            .brand-logo { width: 62px; height: 62px; }
            .brand-logo svg { width: 46px; height: 46px; }
            .brand-name { font-size: 32px; }
            .brand-title { font-size: 30px; }
            .field-row { flex-direction: column; align-items: flex-start; }
            .social-btn { font-size: 13px; padding: 12px; }
        }
    </style>
</head>
<body>
    @if (Route::has('home'))
        <a href="{{ route('home') }}" class="back-home">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali</span>
        </a>
    @endif

    <div class="auth-shell">
        <section class="auth-brand">
            <div class="brand-top">
                <div class="brand-logo-row">
                    <div class="brand-logo">
                        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <rect x="20" y="35" width="40" height="55" fill="rgba(255,255,255,0.18)" rx="3" />
                            <rect x="22" y="37" width="36" height="51" fill="rgba(255,255,255,0.28)" rx="2" />
                            <line x1="40" y1="37" x2="40" y2="88" stroke="rgba(255,255,255,0.45)" stroke-width="2" />
                            <path d="M 50 15 L 50 70 M 50 15 L 75 55 M 75 25 L 75 70" stroke="white" stroke-width="7" fill="none" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="brand-name">Nokomi</div>
                </div>
                <div class="brand-tag"><i class="bi bi-stars"></i> Reader Hub</div>
                <div class="brand-title">Masuk dan lanjutkan dunia cerita favoritmu.</div>
                <div class="brand-copy">Simpan progres baca, bookmark favorit, dan lanjutkan cerita kapan saja dari akun yang sama.</div>
            </div>

            <div class="brand-points">
                <div class="brand-point"><i class="bi bi-book-half"></i><span>Progress baca tersimpan otomatis untuk novel dan komik.</span></div>
                <div class="brand-point"><i class="bi bi-chat-dots"></i><span>Komentar chapter dan riwayat baca tetap terhubung ke akunmu.</span></div>
                <div class="brand-point"><i class="bi bi-shield-check"></i><span>Akses akun yang aman dan praktis di web maupun mobile.</span></div>
            </div>
        </section>

        <section class="auth-card">
            <h1>Masuk ke akun</h1>
            <p>Gunakan email dan password, atau pilih salah satu akun sosial yang tersedia.</p>

            @if ($errors->any())
                <div class="alert-error">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            @php
                $enabledSocialProviders = collect(\App\Support\SocialAuth::providersForView('auth.social.redirect'))
                    ->where('enabled', true)
                    ->values();
            @endphp

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field">
                    <label for="email">Email</label>
                    <div class="input-wrap">
                        <i class="bi bi-envelope leading"></i>
                        <input id="email" type="email" name="email" class="form-input" value="{{ old('email') }}" placeholder="nama@email.com" required autocomplete="email" autofocus>
                    </div>
                    @error('email')
                        <div class="error-text"><i class="bi bi-exclamation-circle"></i><span>{{ $message }}</span></div>
                    @enderror
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <i class="bi bi-lock leading"></i>
                        <input id="password" type="password" name="password" class="form-input password" placeholder="Masukkan password" required autocomplete="current-password">
                        <button type="button" class="toggle-password" onclick="togglePassword('password', 'passwordIcon')">
                            <i id="passwordIcon" class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="error-text"><i class="bi bi-exclamation-circle"></i><span>{{ $message }}</span></div>
                    @enderror
                </div>

                <div class="field-row">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Ingat saya</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Lupa password?</a>
                    @endif
                </div>

                <button type="submit" class="submit-btn">Masuk</button>
            </form>

            @if ($enabledSocialProviders->isNotEmpty())
                <div class="divider">atau masuk dengan</div>

                <div class="social-grid">
                    @foreach ($enabledSocialProviders as $provider)
                        <a href="{{ $provider['url'] }}" class="social-btn {{ $provider['class'] }}" @if ($provider['label'] === 'GitHub' && $enabledSocialProviders->count() % 2 === 1) style="grid-column: 1 / -1;" @endif>
                            <i class="bi {{ $provider['icon'] }}"></i>
                            <span>{{ $provider['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            @endif

            @if (Route::has('register'))
                <div class="switch-link">Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></div>
            @endif
        </section>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            icon.className = isHidden ? 'bi bi-eye-slash' : 'bi bi-eye';
        }
    </script>
</body>
</html>
