<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Nokomi</title>
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
            --success: #10b981;
        }
        body {
            min-height: 100vh;
            font-family: 'DM Sans', sans-serif;
            background:
                radial-gradient(circle at top right, rgba(72, 201, 176, 0.12), transparent 30%),
                radial-gradient(circle at bottom left, rgba(45, 139, 115, 0.16), transparent 34%),
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
            max-width: 1120px;
            display: grid;
            grid-template-columns: 0.95fr 1.05fr;
            border-radius: 28px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.06);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(20px);
        }
        .auth-brand {
            padding: 52px 46px;
            background:
                linear-gradient(180deg, rgba(14, 48, 41, 0.94), rgba(10, 31, 27, 0.96)),
                linear-gradient(135deg, var(--primary-green), var(--light-green));
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 34px;
            position: relative;
        }
        .auth-brand::after {
            content: '';
            position: absolute;
            inset: auto auto -120px -90px;
            width: 260px;
            height: 260px;
            background: radial-gradient(circle, rgba(255,255,255,0.18), transparent 72%);
            border-radius: 50%;
        }
        .brand-block, .brand-points { position: relative; z-index: 1; }
        .brand-logo-row {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 26px;
        }
        .brand-logo {
            width: 74px;
            height: 74px;
            border-radius: 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.12);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.18);
        }
        .brand-logo svg { width: 54px; height: 54px; }
        .brand-name {
            font-family: 'Crimson Pro', serif;
            font-size: 38px;
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
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 18px;
        }
        .brand-title {
            font-family: 'Crimson Pro', serif;
            font-size: 42px;
            line-height: 1.06;
            max-width: 420px;
            margin-bottom: 14px;
        }
        .brand-copy {
            max-width: 430px;
            color: rgba(255, 255, 255, 0.82);
            font-size: 15px;
            line-height: 1.78;
        }
        .brand-points {
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
            padding: 48px 42px;
        }
        .auth-card h1 {
            font-family: 'Crimson Pro', serif;
            font-size: 34px;
            margin-bottom: 8px;
        }
        .auth-card p {
            color: var(--text-secondary);
            margin-bottom: 24px;
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
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }
        .field {
            margin-bottom: 18px;
        }
        .field.full { grid-column: 1 / -1; }
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
        .strength {
            display: grid;
            gap: 8px;
            margin-top: 10px;
        }
        .strength-bars { display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; }
        .strength-bars span {
            display: block;
            height: 5px;
            border-radius: 999px;
            background: rgba(148, 163, 184, 0.2);
            transition: background 0.3s ease;
        }
        .strength-label { font-size: 12px; color: var(--text-secondary); }
        .error-text {
            display: inline-flex;
            gap: 6px;
            align-items: center;
            color: #fca5a5;
            font-size: 12px;
            margin-top: 8px;
        }
        .terms {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            color: var(--text-secondary);
            font-size: 13px;
            line-height: 1.7;
            margin-bottom: 22px;
        }
        .terms a, .switch-link a {
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
            grid-template-columns: repeat(3, minmax(0, 1fr));
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
        @media (max-width: 980px) {
            .auth-shell { grid-template-columns: 1fr; }
            .auth-brand { padding: 36px 28px; }
            .auth-card { padding: 38px 26px; }
        }
        @media (max-width: 680px) {
            .form-grid, .social-grid { grid-template-columns: 1fr; }
            .brand-title { font-size: 34px; }
            .auth-card h1 { font-size: 30px; }
            .back-home { position: static; margin-bottom: 18px; }
            body { display: block; padding: 16px; }
            .auth-shell { margin: 0 auto; }
            .auth-brand { padding: 30px 22px; }
            .auth-card { padding: 30px 20px; }
        }
        @media (max-width: 420px) {
            .brand-logo { width: 62px; height: 62px; }
            .brand-logo svg { width: 46px; height: 46px; }
            .brand-name { font-size: 32px; }
            .brand-title { font-size: 30px; }
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
            <div class="brand-block">
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
                <div class="brand-tag"><i class="bi bi-person-plus-fill"></i> New Reader</div>
                <div class="brand-title">Buat akun baru dan mulai masuk lebih cepat.</div>
                <div class="brand-copy">Daftar dengan email biasa atau langsung lewat akun sosial, lalu mulai susun library dan progres bacamu sendiri.</div>
            </div>

            <div class="brand-points">
                <div class="brand-point"><i class="bi bi-journal-richtext"></i><span>Riwayat baca, bookmark, dan progres chapter ikut tersimpan.</span></div>
                <div class="brand-point"><i class="bi bi-rocket-takeoff"></i><span>Pengalaman masuk yang ringan untuk web maupun mobile.</span></div>
                <div class="brand-point"><i class="bi bi-people"></i><span>Dukungan login sosial untuk Google, Facebook, X, Discord, dan GitHub.</span></div>
            </div>
        </section>

        <section class="auth-card">
            <h1>Daftar akun baru</h1>
            <p>Isi data dasar di bawah ini, lalu kamu bisa langsung mulai membaca dan menyimpan progres.</p>

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

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-grid">
                    <div class="field full">
                        <label for="name">Nama Lengkap</label>
                        <div class="input-wrap">
                            <i class="bi bi-person leading"></i>
                            <input id="name" type="text" name="name" class="form-input" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required autocomplete="name" autofocus>
                        </div>
                        @error('name')
                            <div class="error-text"><i class="bi bi-exclamation-circle"></i><span>{{ $message }}</span></div>
                        @enderror
                    </div>

                    <div class="field full">
                        <label for="email">Email</label>
                        <div class="input-wrap">
                            <i class="bi bi-envelope leading"></i>
                            <input id="email" type="email" name="email" class="form-input" value="{{ old('email') }}" placeholder="nama@email.com" required autocomplete="email">
                        </div>
                        @error('email')
                            <div class="error-text"><i class="bi bi-exclamation-circle"></i><span>{{ $message }}</span></div>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="password">Password</label>
                        <div class="input-wrap">
                            <i class="bi bi-lock leading"></i>
                            <input id="password" type="password" name="password" class="form-input password" placeholder="Minimal 8 karakter" required autocomplete="new-password" oninput="checkStrength()">
                            <button type="button" class="toggle-password" onclick="togglePassword('password', 'passwordIcon')">
                                <i id="passwordIcon" class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="strength">
                            <div class="strength-bars">
                                <span id="bar1"></span>
                                <span id="bar2"></span>
                                <span id="bar3"></span>
                                <span id="bar4"></span>
                            </div>
                            <div class="strength-label" id="strengthLabel">Kekuatan password: lemah</div>
                        </div>
                        @error('password')
                            <div class="error-text"><i class="bi bi-exclamation-circle"></i><span>{{ $message }}</span></div>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <div class="input-wrap">
                            <i class="bi bi-shield-lock leading"></i>
                            <input id="password_confirmation" type="password" name="password_confirmation" class="form-input password" placeholder="Ulangi password" required autocomplete="new-password">
                            <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation', 'confirmIcon')">
                                <i id="confirmIcon" class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <label class="terms">
                    <input type="checkbox" required>
                    <span>Saya setuju dengan syarat penggunaan dan kebijakan dasar platform untuk menjaga komunitas tetap aman dan nyaman.</span>
                </label>

                <button type="submit" class="submit-btn">Daftar Sekarang</button>
            </form>

            @if ($enabledSocialProviders->isNotEmpty())
                <div class="divider">atau daftar dengan</div>

                <div class="social-grid">
                    @foreach ($enabledSocialProviders as $provider)
                        <a href="{{ $provider['url'] }}" class="social-btn {{ $provider['class'] }}">
                            <i class="bi {{ $provider['icon'] }}"></i>
                            <span>{{ $provider['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            @endif

            @if (Route::has('login'))
                <div class="switch-link">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></div>
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

        function checkStrength() {
            const password = document.getElementById('password').value;
            const bars = ['bar1', 'bar2', 'bar3', 'bar4'].map(id => document.getElementById(id));
            const label = document.getElementById('strengthLabel');
            let score = 0;

            if (password.length >= 8) score++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) score++;
            if (/\d/.test(password)) score++;
            if (/[^A-Za-z0-9]/.test(password)) score++;

            bars.forEach((bar, index) => {
                bar.style.background = index < score ? 'var(--success)' : 'rgba(148, 163, 184, 0.2)';
            });

            const labels = ['lemah', 'cukup', 'bagus', 'kuat'];
            label.textContent = 'Kekuatan password: ' + (labels[Math.max(score - 1, 0)] || 'lemah');
            label.style.color = score >= 3 ? 'var(--success)' : 'var(--text-secondary)';
        }
    </script>
</body>
</html>
