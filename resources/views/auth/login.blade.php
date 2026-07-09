<x-guest-layout :fullWidth="true">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        .login-page {
            min-height: 100vh;
            background: linear-gradient(135deg, #ff8da6 0%, #ff6b8a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            font-family: 'Inter', sans-serif;
        }

        .tablet-frame {
            width: 100%;
            max-width: 1100px;
            background: #1e293b;
            border-radius: 2.5rem;
            padding: 1rem;
            box-shadow:
                0 50px 100px -20px rgba(0, 0, 0, 0.25),
                0 30px 60px -30px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            position: relative;
        }

        .tablet-inner {
            display: flex;
            border-radius: 1.8rem;
            overflow: hidden;
            min-height: 600px;
            background: #ffffff;
        }

        /* Left side - illustration */
        .illustration-side {
            flex: 1;
            background: linear-gradient(180deg, #2563eb 0%, #1d4ed8 40%, #1e40af 100%);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            min-width: 0;
        }

        .illustration-side::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(96, 165, 250, 0.3) 0%, transparent 70%);
            border-radius: 50%;
        }

        .illustration-side::after {
            content: '';
            position: absolute;
            bottom: -50px;
            left: -50px;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.5) 0%, transparent 70%);
            border-radius: 50%;
        }

        .clock-widget {
            position: absolute;
            top: 2rem;
            left: 2.5rem;
            z-index: 2;
        }

        .clock-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .clock-circle svg {
            width: 24px;
            height: 24px;
            color: white;
        }

        .illustration-img {
            position: absolute;
            inset: 0;
            z-index: 1;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        /* Right side - form */
        .form-side {
            width: 440px;
            min-width: 380px;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem 2.5rem;
            position: relative;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
        }

        .brand-logo svg {
            width: 28px;
            height: 28px;
            color: #2563eb;
        }

        .brand-logo span {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            letter-spacing: -0.02em;
        }

        .form-title {
            font-size: 2.25rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
            margin-bottom: 2rem;
            letter-spacing: -0.03em;
        }

        .form-input-group {
            margin-bottom: 1rem;
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 0.75rem;
            font-size: 0.9rem;
            color: #334155;
            background: #ffffff;
            transition: all 0.2s ease;
            outline: none;
            font-family: 'Inter', sans-serif;
        }

        .form-input::placeholder {
            color: #94a3b8;
        }

        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #94a3b8;
            padding: 0;
            display: flex;
            align-items: center;
        }

        .password-toggle:hover {
            color: #64748b;
        }

        .submit-btn {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, #ff6b8a 0%, #e5527a 100%);
            color: white;
            border: none;
            border-radius: 0.75rem;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
            box-shadow: 0 4px 14px rgba(229, 82, 122, 0.35);
            margin-top: 0.5rem;
        }

        .submit-btn:hover {
            background: linear-gradient(135deg, #e5527a 0%, #d44169 100%);
            box-shadow: 0 6px 20px rgba(229, 82, 122, 0.45);
            transform: translateY(-1px);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .divider span {
            font-size: 0.8rem;
            color: #94a3b8;
            white-space: nowrap;
        }

        .social-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }

        .social-btn {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 1.5px solid #e2e8f0;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .social-btn:hover {
            border-color: #3b82f6;
            background: #eff6ff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .social-btn svg {
            width: 20px;
            height: 20px;
        }

        .terms-text {
            text-align: center;
            font-size: 0.75rem;
            color: #94a3b8;
            margin-top: 1.5rem;
            line-height: 1.5;
        }

        .terms-text a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
        }

        .terms-text a:hover {
            text-decoration: underline;
        }

        .bottom-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #f1f5f9;
            font-size: 0.875rem;
            color: #64748b;
        }

        .bottom-link a {
            color: #2563eb;
            font-weight: 600;
            text-decoration: none;
            margin-left: 0.25rem;
        }

        .bottom-link a:hover {
            text-decoration: underline;
        }

        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: #475569;
            cursor: pointer;
        }

        .remember-label input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #2563eb;
            cursor: pointer;
        }

        .forgot-link {
            font-size: 0.8rem;
            color: #3b82f6;
            text-decoration: none;
            font-weight: 600;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .login-page {
                padding: 1rem;
                align-items: flex-start;
                padding-top: 2rem;
            }

            .tablet-frame {
                border-radius: 1.5rem;
                padding: 0.5rem;
            }

            .tablet-inner {
                flex-direction: column;
                border-radius: 1.2rem;
                min-height: auto;
            }

            .illustration-side {
                height: 250px;
                min-height: 200px;
            }

            .illustration-img {
                max-width: 250px;
            }

            .form-side {
                width: 100%;
                min-width: auto;
                padding: 2rem 1.5rem;
            }

            .form-title {
                font-size: 1.75rem;
            }

            .clock-widget {
                top: 1rem;
                left: 1.5rem;
            }

            .clock-circle {
                width: 36px;
                height: 36px;
            }

            .clock-circle svg {
                width: 18px;
                height: 18px;
            }
        }

        /* Error styling */
        .input-error-msg {
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: 0.375rem;
        }
    </style>

    <div class="login-page">
        <div class="tablet-frame">
            <div class="tablet-inner">
                <!-- Left: Blue illustration area -->
                <div class="illustration-side">
                    <!-- Clock widget -->
                    <div class="clock-widget">
                        <div class="clock-circle">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <img src="{{ asset('images/login_illustration.png') }}" alt="Login Illustration" class="illustration-img">
                </div>

                <!-- Right: Form -->
                <div class="form-side">
                    <!-- Brand Logo -->
                    <div class="brand-logo mb-10 text-center">
                        <img src="{{ asset('storage/worklyy.png') }}" alt="Workly" class="h-16 w-auto object-contain mx-auto">
                    </div>

                    <h1 class="form-title">Log in</h1>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email -->
                        <div class="form-input-group">
                            <input id="email" type="email" name="email" value="{{ old('email') }}"
                                required autofocus autocomplete="username"
                                class="form-input"
                                placeholder="Email address">
                            @error('email')
                                <p class="input-error-msg">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="form-input-group">
                            <div class="password-wrapper">
                                <input id="password" type="password" name="password"
                                    required autocomplete="current-password"
                                    class="form-input"
                                    placeholder="Password">
                                <button type="button" class="password-toggle" onclick="togglePassword()">
                                    <svg id="eye-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="input-error-msg">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember / Forgot -->
                        <div class="remember-row">
                            <label class="remember-label">
                                <input type="checkbox" name="remember" id="remember_me">
                                Remember me
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="forgot-link">Forgot?</a>
                            @endif
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="submit-btn">
                            Log in
                        </button>
                    </form>

                    <!-- Divider -->
                    <div class="divider">
                        <span>or sign in with</span>
                    </div>

                    <!-- Social Buttons -->
                    <div class="social-buttons">
                        <a href="#" class="social-btn">
                            <svg viewBox="0 0 24 24">
                                <path d="M12.48 10.92v3.28h4.76c-.2 1.01-.88 1.87-1.66 2.54l2.7 2.09C19.85 17.3 21 14.88 21 12.18c0-.66-.06-1.32-.18-1.96H12.48z" fill="#4285F4"/><path d="M12.48 21c2.43 0 4.47-.8 5.96-2.18l-2.7-2.09c-.75.5-1.7.8-2.76.8-2.13 0-3.93-1.44-4.57-3.37l-2.79 2.16C7.14 19.1 9.61 21 12.48 21z" fill="#34A853"/><path d="M7.91 14.16c-.16-.5-.25-1.03-.25-1.58s.09-1.08.25-1.58L5.12 8.84C4.4 10.29 4 11.91 4 13.58s.4 3.29 1.12 4.74l2.79-2.16z" fill="#FBBC05"/><path d="M12.48 6.1c1.33 0 2.51.46 3.45 1.35l2.58-2.58C16.95 3.4 14.91 2.5 12.48 2.5c-2.87 0-5.34 1.9-6.36 4.54l2.79 2.16c.64-1.93 2.44-3.37 4.57-3.37z" fill="#EA4335"/>
                            </svg>
                        </a>
                        <a href="#" class="social-btn">
                            <svg viewBox="0 0 23 23">
                                <path fill="#f35325" d="M1 1h10v10H1z"/><path fill="#81bc06" d="M12 1h10v10H12z"/><path fill="#05a6f0" d="M1 12h10v10H1z"/><path fill="#ffba08" d="M12 12h10v10H12z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-btn">
                            <svg fill="#1e293b" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>

                    <!-- Terms -->
                    <p class="terms-text">
                        By logging in you agree to SPT Invoice's<br>
                        <a href="#">Terms of Services</a> and <a href="#">Privacy Policy</a>.
                    </p>

                    <!-- Bottom Link -->
                    <div class="bottom-link">
                        Don't have an account?<a href="{{ route('register') }}">Sign up</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }
    </script>
</x-guest-layout>
