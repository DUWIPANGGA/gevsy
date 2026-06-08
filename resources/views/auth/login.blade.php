<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Meet BPS</title>
    <link rel="icon" href="/images/logo.png" type="image/png">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

        @property --comet-1 {
            syntax: '<angle>';
            inherits: false;
            initial-value: 0deg;
        }

        @property --comet-2 {
            syntax: '<angle>';
            inherits: false;
            initial-value: 180deg;
        }

        :root {
            --primary: #0284c7;
            --primary-hover: #0369a1;
            --primary-glow: rgba(2, 132, 199, 0.15);
            --secondary: #0f172a;
            --neutral-50: #f8fafc;
            --neutral-100: #f1f5f9;
            --neutral-200: #e2e8f0;
            --neutral-300: #cbd5e1;
            --neutral-700: #334155;
            --neutral-800: #1e293b;
            --neutral-900: #0f172a;
            --error: #ef4444;
            --error-bg: #fef2f2;
            --error-border: #fca5a5;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            background: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-y: auto;
            position: relative;
        }

        /* Fixed blurred background */
        .login-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('/images/background.png');
            background-size: cover;
            background-position: center;
            z-index: 1;
        }

        .login-bg::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.75) 0%, rgba(30, 41, 59, 0.7) 100%);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }

        /* Container centering */
        .login-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 860px;
            padding: 24px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* Integrated Dual-Pane Card */
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 28px;
            overflow: hidden;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1.15fr;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        /* Rotating Border Comet Effect */
        .login-card-wrap {
            position: relative;
            border-radius: 28px;
            width: 100%;
        }

        .login-card-wrap::before,
        .login-card-wrap::after {
            content: '';
            position: absolute;
            inset: -6px;
            border-radius: 34px;
            z-index: -1;
            pointer-events: none;
            background: conic-gradient(
                from var(--comet-1),
                transparent 0deg,
                rgba(29, 78, 216, 0.02) 5deg,
                rgba(29, 78, 216, 0.06) 10deg,
                rgba(29, 78, 216, 0.15) 15deg,
                rgba(29, 78, 216, 0.4) 22deg,
                #1d4ed8 30deg,
                rgba(29, 78, 216, 0.7) 36deg,
                rgba(29, 78, 216, 0.25) 48deg,
                rgba(29, 78, 216, 0.06) 65deg,
                rgba(29, 78, 216, 0.015) 80deg,
                transparent 100deg
            );
        }

        .login-card-wrap::before {
            animation: rotateComet 4s linear infinite;
            filter: blur(2px);
        }

        .login-card-wrap::after {
            background: conic-gradient(
                from var(--comet-2),
                transparent 0deg,
                rgba(29, 78, 216, 0.02) 5deg,
                rgba(29, 78, 216, 0.06) 10deg,
                rgba(29, 78, 216, 0.15) 15deg,
                rgba(29, 78, 216, 0.4) 22deg,
                #1d4ed8 30deg,
                rgba(29, 78, 216, 0.7) 36deg,
                rgba(29, 78, 216, 0.25) 48deg,
                rgba(29, 78, 216, 0.06) 65deg,
                rgba(29, 78, 216, 0.015) 80deg,
                transparent 100deg
            );
            animation: rotateComet 4s linear infinite;
            filter: blur(2.5px);
        }

        @keyframes rotateComet {
            to {
                --comet-1: 360deg;
                --comet-2: 360deg;
            }
        }

        /* Left Side: Branding Panel */
        .login-left {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: -30%;
            left: -30%;
            width: 160%;
            height: 160%;
            background: radial-gradient(circle, rgba(2, 132, 199, 0.18) 0%, transparent 65%);
            pointer-events: none;
        }

        .brand-content {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .brand-logo-container {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.18);
            width: 100px;
            height: 100px;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 28px;
            box-shadow: 0 12px 30px -5px rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            transition: var(--transition);
        }

        .brand-logo-container:hover {
            transform: scale(1.05) rotate(2deg);
            border-color: rgba(2, 132, 199, 0.4);
            box-shadow: 0 12px 30px -5px rgba(2, 132, 199, 0.3);
        }

        .brand-logo-img {
            height: 68px;
            width: auto;
        }

        .brand-title {
            font-size: 28px;
            font-weight: 800;
            color: white;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #ffffff 60%, #e2e8f0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand-subtitle {
            font-size: 14px;
            color: #94a3b8;
            line-height: 1.6;
            max-width: 260px;
        }

        .brand-footer {
            position: relative;
            z-index: 2;
            font-size: 11px;
            color: #64748b;
            text-align: center;
            margin-top: auto;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            font-weight: 600;
        }

        /* Right Side: Form Panel */
        .login-right {
            background: rgba(255, 255, 255, 0.98);
            padding: 48px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Top User Icon */
        .user-avatar-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .user-avatar-circle {
            width: 76px;
            height: 76px;
            background: #f1f5f9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid white;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
        }

        .user-avatar-circle:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
        }

        .user-avatar-img {
            width: 64px;
            height: 64px;
            object-fit: cover;
            border-radius: 50%;
        }

        .form-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .form-header h2 {
            font-size: 24px;
            font-weight: 700;
            color: var(--neutral-900);
            margin-bottom: 6px;
            letter-spacing: -0.5px;
        }

        .form-header p {
            font-size: 14px;
            color: #64748b;
        }

        /* Mobile Logo (hidden on desktop) */
        .mobile-logo {
            display: none;
            justify-content: center;
            margin-bottom: 16px;
        }

        .mobile-logo img {
            height: 52px;
            width: auto;
        }

        .mobile-logo-text {
            font-size: 14px;
            font-weight: 700;
            color: #0284c7;
            text-align: center;
            margin-top: 4px;
            letter-spacing: 0.5px;
        }

        /* Inputs */
        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--neutral-700);
            margin-bottom: 6px;
        }

        .input-container {
            position: relative;
            width: 100%;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .input-container input {
            width: 100%;
            padding: 13px 16px 13px 44px;
            border: 1.5px solid var(--neutral-200);
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            color: var(--neutral-800);
            background: white;
            outline: none;
            transition: var(--transition);
        }

        .input-container input::placeholder {
            color: #94a3b8;
        }

        .input-container input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-glow);
            background: white;
        }

        .input-container input:focus + .input-icon {
            color: var(--primary);
        }

        /* Password input specific spacing on right for toggle */
        .input-container input#password {
            padding-right: 48px;
        }

        /* Password toggle button */
        .password-toggle {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .password-toggle:hover {
            color: var(--neutral-700);
            background: var(--neutral-100);
        }

        .password-toggle:focus {
            outline: none;
            color: var(--primary);
        }

        /* Remember me Checkbox */
        .form-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
            margin-top: 4px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            color: #64748b;
            user-select: none;
        }

        .remember-me input {
            margin-right: 8px;
            width: 16px;
            height: 16px;
            border-radius: 4px;
            border: 1.5px solid var(--neutral-300);
            accent-color: var(--primary);
            cursor: pointer;
            transition: var(--transition);
        }

        .remember-me input:hover {
            border-color: var(--primary);
        }

        /* Button Submit */
        .btn-submit {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(2, 132, 199, 0.35);
            background: linear-gradient(135deg, #0369a1 0%, #075985 100%);
        }

        .btn-submit:active {
            transform: translateY(1px);
        }

        /* Error Notification styling */
        .alert-error {
            background: var(--error-bg);
            border: 1px solid var(--error-border);
            color: #b91c1c;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
            animation: shake 0.4s ease-in-out;
        }

        .error-message {
            color: #ef4444;
            font-size: 12px;
            font-weight: 500;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Fade-in & Shake Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-4px); }
            75% { transform: translateX(4px); }
        }

        /* Responsive Layouts */
        @media (max-width: 768px) {
            body {
                align-items: center;
                padding: 16px;
            }

            .login-container {
                padding: 0;
            }

            .login-card {
                grid-template-columns: 1fr;
                border-radius: 24px;
                box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
            }

            .login-left {
                display: none;
            }

            .login-right {
                padding: 36px 24px;
            }

            .mobile-logo {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .user-avatar-container {
                display: none; /* Hide standard avatar on mobile to save vertical space and prioritize logo */
            }
        }
    </style>
</head>
<body>
    <!-- Background overlay image -->
    <div class="login-bg"></div>

    <div class="login-container">
        <div class="login-card-wrap">
        <div class="login-card">
            <!-- Left Branding Column (Desktop only) -->
            <div class="login-left">
                <div class="brand-content">
                    <div class="brand-logo-container">
                        <img src="/images/logo.png" alt="BPS Logo" class="brand-logo-img">
                    </div>
                    <h1 class="brand-title">Meet BPS</h1>
                    <p class="brand-subtitle">
                        Aplikasi Notulensi Otomatis & Dokumentasi Rapat Terintegrasi
                    </p>
                </div>
                <div class="brand-footer">
                    Badan Pusat Statistik © 2026
                </div>
            </div>

            <!-- Right Login Form Column -->
            <div class="login-right">
                <!-- Mobile only Logo branding -->
                <div class="mobile-logo">
                    <img src="/images/logo.png" alt="BPS Logo">
                    <div class="mobile-logo-text">MEET BPS</div>
                </div>

                <!-- User Profile Image -->
                <div class="user-avatar-container">
                    <div class="user-avatar-circle">
                        <img src="/images/user.png" alt="User Avatar" class="user-avatar-img">
                    </div>
                </div>

                <div class="form-header">
                    <h2>Sign In</h2>
                    <p>Selamat datang! Silakan masuk ke akun Anda</p>
                </div>

                <!-- Form Login -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Input Group -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-container">
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                placeholder="nama@domain.com" 
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                autofocus
                            >
                            <div class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </div>
                        </div>
                        @error('email')
                            <div class="error-message">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password Input Group -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-container">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                placeholder="Masukkan password" 
                                required
                                autocomplete="current-password"
                            >
                            <div class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                            </div>
                            <button type="button" id="togglePassword" class="password-toggle" aria-label="Tampilkan password">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-off-icon">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <div class="error-message">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Remember Me checkbox & actions -->
                    <div class="form-actions">
                        <label class="remember-me">
                            <input type="checkbox" name="remember">
                            Remember me
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit">
                        Continue
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12,5 19,12 12,19"></polyline>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        </div>
    </div>

    <!-- Password visibility toggle script -->
    <script>
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');

        const eyeOffIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
            <line x1="1" y1="1" x2="23" y2="23"></line>
        </svg>`;

        const eyeOnIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
            <circle cx="12" cy="12" r="3"></circle>
        </svg>`;

        togglePassword.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            
            if (isPassword) {
                this.setAttribute('aria-label', 'Sembunyikan password');
                this.innerHTML = eyeOnIcon;
            } else {
                this.setAttribute('aria-label', 'Tampilkan password');
                this.innerHTML = eyeOffIcon;
            }
        });
    </script>
</body>
</html>
