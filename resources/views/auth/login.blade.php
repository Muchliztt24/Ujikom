<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Nokomi</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro:wght@400;600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Background Animation */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 50%, rgba(45, 139, 115, 0.1), transparent 50%),
                        radial-gradient(circle at 70% 50%, rgba(72, 201, 176, 0.1), transparent 50%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Container */
        .auth-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 450px;
            background: rgba(26, 31, 46, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 48px 40px;
            border: 1px solid var(--border-color);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            animation: slideUp 0.6s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Logo */
        .logo-container {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-green), var(--light-green));
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(45, 139, 115, 0.4);
            margin-bottom: 16px;
        }

        .logo-icon svg {
            width: 60px;
            height: 60px;
        }

        .logo-text {
            font-family: 'Crimson Pro', serif;
            font-size: 36px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }

        .logo-subtitle {
            color: var(--text-secondary);
            font-size: 15px;
        }

        /* Form Title */
        .form-title {
            font-family: 'Crimson Pro', serif;
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
            text-align: center;
        }

        .form-subtitle {
            color: var(--text-secondary);
            font-size: 14px;
            text-align: center;
            margin-bottom: 32px;
        }

        /* Form Group */
        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            color: var(--text-primary);
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            background: var(--bg-main);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            color: var(--text-primary);
            font-size: 15px;
            transition: all 0.3s ease;
            font-family: 'DM Sans', sans-serif;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(45, 139, 115, 0.1);
        }

        .form-input::placeholder {
            color: var(--text-secondary);
        }

        /* Password Toggle */
        .password-group {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 20px;
            transition: all 0.3s ease;
        }

        .password-toggle:hover {
            color: var(--light-green);
        }

        /* Remember & Forgot */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            font-size: 14px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-secondary);
            cursor: pointer;
        }

        .checkbox-label input {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .forgot-link {
            color: var(--light-green);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .forgot-link:hover {
            color: var(--accent-green);
            text-decoration: underline;
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary-green), var(--light-green));
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(45, 139, 115, 0.3);
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(45, 139, 115, 0.5);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            margin: 32px 0;
            color: var(--text-secondary);
            font-size: 14px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border-color);
        }

        .divider span {
            padding: 0 16px;
        }

        /* Social Login */
        .social-login {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 32px;
        }

        .social-btn {
            padding: 12px;
            background: var(--bg-main);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-primary);
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .social-btn:hover {
            background: var(--bg-hover);
            border-color: var(--primary-green);
        }

        /* Register Link */
        .register-link {
            text-align: center;
            color: var(--text-secondary);
            font-size: 14px;
        }

        .register-link a {
            color: var(--light-green);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .register-link a:hover {
            color: var(--accent-green);
            text-decoration: underline;
        }

        /* Error Message */
        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid var(--danger);
            border-radius: 10px;
            padding: 12px 16px;
            color: #ff6b6b;
            font-size: 14px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Back to Home */
        .back-home {
            position: absolute;
            top: 24px;
            left: 24px;
            color: var(--text-secondary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 10px;
            transition: all 0.3s ease;
            z-index: 2;
        }

        .back-home:hover {
            background: rgba(26, 31, 46, 0.8);
            color: var(--light-green);
        }

        /* Responsive */
        @media (max-width: 480px) {
            .auth-container {
                padding: 32px 24px;
            }

            .logo-text {
                font-size: 32px;
            }

            .form-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <!-- Back to Home -->
    @if (Route::has('home'))
        <a href="{{ route('home') }}" class="back-home">
            <span>←</span> Kembali
        </a>
    @endif

    <div class="auth-container">
        <!-- Logo -->
        <div class="logo-container">
            <div class="logo-icon">
                <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <!-- Buku -->
                    <rect x="20" y="35" width="40" height="55" fill="rgba(255,255,255,0.2)" rx="3"/>
                    <rect x="22" y="37" width="36" height="51" fill="rgba(255,255,255,0.3)" rx="2"/>
                    <line x1="40" y1="37" x2="40" y2="88" stroke="rgba(255,255,255,0.5)" stroke-width="2"/>
                    
                    <!-- Huruf N -->
                    <path d="M 50 15 L 50 70 M 50 15 L 75 55 M 75 25 L 75 70" 
                          stroke="white" 
                          stroke-width="7" 
                          fill="none" 
                          stroke-linecap="round" 
                          stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="logo-text">Nokomi</div>
            <div class="logo-subtitle">Baca Novel & Comic Online</div>
        </div>

        <!-- Form Title -->
        <h1 class="form-title">Selamat Datang!</h1>
        <p class="form-subtitle">Masuk untuk melanjutkan membaca</p>

        <!-- Error Messages -->
        @error('email')
            <div class="error-message">
                <span>⚠️</span>
                <span>{{ $message }}</span>
            </div>
        @enderror
        @error('password')
            <div class="error-message">
                <span>⚠️</span>
                <span>{{ $message }}</span>
            </div>
        @enderror

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" 
                       name="email" 
                       class="form-input @error('email') error @enderror" 
                       placeholder="nama@email.com"
                       value="{{ old('email') }}"
                       required 
                       autocomplete="email"
                       autofocus>
                @error('email')
                    <span class="error-text">⚠️ {{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="password-group">
                    <input type="password" 
                           name="password" 
                           class="form-input @error('password') error @enderror" 
                           id="password"
                           placeholder="Masukkan password"
                           required
                           autocomplete="current-password">
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <span id="toggleIcon">👁️</span>
                    </button>
                </div>
                @error('password')
                    <span class="error-text">⚠️ {{ $message }}</span>
                @enderror
            </div>

            <!-- Remember & Forgot -->
            <div class="form-options">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span>Ingat saya</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">Lupa password?</a>
                @endif
            </div>

            <!-- Submit -->
            <button type="submit" class="submit-btn">
                Masuk
            </button>
        </form>

        <!-- Divider -->
        <div class="divider">
            <span>atau masuk dengan</span>
        </div>

        <!-- Social Login -->
        <div class="social-login">
            <button class="social-btn">
                <span>🔵</span> Google
            </button>
            <button class="social-btn">
                <span>⚫</span> GitHub
            </button>
        </div>

        <!-- Register Link -->
        @if (Route::has('register'))
            <div class="register-link">
                Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
            </div>
        @endif
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.textContent = '👁️‍🗨️';
            } else {
                passwordInput.type = 'password';
                toggleIcon.textContent = '👁️';
            }
        }
    </script>
</body>
</html>