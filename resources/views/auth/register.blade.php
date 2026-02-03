<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Nokomi</title>

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
            --success: #10b981;
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
            max-width: 500px;
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
            margin-bottom: 32px;
        }

        .logo-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary-green), var(--light-green));
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(45, 139, 115, 0.4);
            margin-bottom: 12px;
        }

        .logo-icon svg {
            width: 52px;
            height: 52px;
        }

        .logo-text {
            font-family: 'Crimson Pro', serif;
            font-size: 32px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.5px;
            margin-bottom: 4px;
        }

        .logo-subtitle {
            color: var(--text-secondary);
            font-size: 14px;
        }

        /* Form Title */
        .form-title {
            font-family: 'Crimson Pro', serif;
            font-size: 26px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
            text-align: center;
        }

        .form-subtitle {
            color: var(--text-secondary);
            font-size: 14px;
            text-align: center;
            margin-bottom: 28px;
        }

        /* Form Group */
        .form-group {
            margin-bottom: 20px;
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

        .form-input.error {
            border-color: var(--danger);
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

        /* Password Strength */
        .password-strength {
            margin-top: 8px;
            display: flex;
            gap: 4px;
        }

        .strength-bar {
            flex: 1;
            height: 4px;
            background: var(--border-color);
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .strength-bar.active {
            background: var(--success);
        }

        .strength-text {
            font-size: 12px;
            color: var(--text-secondary);
            margin-top: 4px;
        }

        /* Error Message */
        .error-text {
            color: var(--danger);
            font-size: 12px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid var(--danger);
            border-radius: 10px;
            padding: 12px 16px;
            color: #ff6b6b;
            font-size: 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Terms */
        .terms-group {
            margin-bottom: 24px;
        }

        .checkbox-label {
            display: flex;
            align-items: start;
            gap: 10px;
            color: var(--text-secondary);
            font-size: 13px;
            line-height: 1.6;
        }

        .checkbox-label input {
            margin-top: 2px;
            width: 18px;
            height: 18px;
            cursor: pointer;
            flex-shrink: 0;
        }

        .checkbox-label a {
            color: var(--light-green);
            text-decoration: none;
        }

        .checkbox-label a:hover {
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

        .submit-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(45, 139, 115, 0.5);
        }

        .submit-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            margin: 28px 0;
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
            margin-bottom: 28px;
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

        /* Login Link */
        .login-link {
            text-align: center;
            color: var(--text-secondary);
            font-size: 14px;
        }

        .login-link a {
            color: var(--light-green);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .login-link a:hover {
            color: var(--accent-green);
            text-decoration: underline;
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
                font-size: 28px;
            }

            .form-title {
                font-size: 22px;
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
        <h1 class="form-title">Daftar Akun Baru</h1>
        <p class="form-subtitle">Bergabung dan mulai membaca sekarang</p>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="error-message">
                <span>⚠️</span>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Register Form -->
        <form method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf

            <!-- Name -->
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" 
                       name="name" 
                       class="form-input @error('name') error @enderror" 
                       placeholder="Masukkan nama lengkap"
                       value="{{ old('name') }}"
                       required 
                       autocomplete="name"
                       autofocus>
                @error('name')
                    <span class="error-text">⚠️ {{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" 
                       name="email" 
                       class="form-input @error('email') error @enderror" 
                       placeholder="nama@email.com"
                       value="{{ old('email') }}"
                       required
                       autocomplete="email">
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
                           placeholder="Minimal 8 karakter"
                           required
                           autocomplete="new-password"
                           oninput="checkPasswordStrength()">
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <span id="toggleIcon1">👁️</span>
                    </button>
                </div>
                <div class="password-strength">
                    <div class="strength-bar" id="strength1"></div>
                    <div class="strength-bar" id="strength2"></div>
                    <div class="strength-bar" id="strength3"></div>
                    <div class="strength-bar" id="strength4"></div>
                </div>
                <div class="strength-text" id="strengthText">Lemah</div>
                @error('password')
                    <span class="error-text">⚠️ {{ $message }}</span>
                @enderror
            </div>

            <!-- Password Confirmation -->
            <div class="form-group">
                <label class="form-label">Konfirmasi Password</label>
                <div class="password-group">
                    <input type="password" 
                           name="password_confirmation" 
                           class="form-input" 
                           id="password_confirmation"
                           placeholder="Ketik ulang password"
                           required
                           autocomplete="new-password">
                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                        <span id="toggleIcon2">👁️</span>
                    </button>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" class="submit-btn">
                Daftar Sekarang
            </button>
        </form>

        <!-- Divider -->
        <div class="divider">
            <span>atau daftar dengan</span>
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

        <!-- Login Link -->
        @if (Route::has('login'))
            <div class="login-link">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
            </div>
        @endif
    </div>

    <script>
        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = inputId === 'password' ? document.getElementById('toggleIcon1') : document.getElementById('toggleIcon2');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.textContent = '👁️‍🗨️';
            } else {
                passwordInput.type = 'password';
                toggleIcon.textContent = '👁️';
            }
        }

        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBars = [
                document.getElementById('strength1'),
                document.getElementById('strength2'),
                document.getElementById('strength3'),
                document.getElementById('strength4')
            ];
            const strengthText = document.getElementById('strengthText');

            // Reset
            strengthBars.forEach(bar => bar.classList.remove('active'));

            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;

            // Update bars
            for (let i = 0; i < strength; i++) {
                strengthBars[i].classList.add('active');
            }

            // Update text
            const strengthTexts = ['Lemah', 'Cukup', 'Bagus', 'Kuat'];
            strengthText.textContent = strengthTexts[strength - 1] || 'Lemah';
            strengthText.style.color = strength >= 3 ? 'var(--success)' : 'var(--text-secondary)';
        }
    </script>
</body>
</html>