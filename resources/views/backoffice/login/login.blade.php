<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administrateur - GDE</title>
    @vite(['resources/css/app.css'])
</head>
<body>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

    .admin-login-container {
        position: fixed; top: 0; left: 0;
        width: 100%; height: 100vh;
        overflow: hidden;
        display: flex; align-items: center; justify-content: center;
    }

    .video-background {
        position: absolute; top: 0; left: 0;
        width: 100%; height: 100%;
        object-fit: cover; z-index: 1;
    }

    .video-overlay {
        position: absolute; top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.25); z-index: 2;
    }

    .login-form-wrapper {
        position: relative; z-index: 3;
        width: 100%; display: flex;
        align-items: center; justify-content: center;
    }

    .login-form-container {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        padding: 60px 40px;
        width: 100%; max-width: 420px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }

    .login-header { text-align: center; margin-bottom: 40px; }

    .logo {
        width: 80px; height: 80px;
        margin: 0 auto 20px;
        display: flex; align-items: center; justify-content: center;
    }

    .logo img {
        width: 100%; height: 100%; object-fit: contain;
        filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
        transition: opacity 0.3s ease;
    }

    html[data-theme="dark"] .logo-light { display: none; }
    html[data-theme="light"] .logo-dark { display: none; }

    .login-header h1 {
        color: #ffffff; font-size: 28px;
        font-weight: 600; margin-bottom: 10px; letter-spacing: 0.5px;
    }

    .login-header p { color: rgba(255, 255, 255, 0.8); font-size: 14px; }

    .form-group { margin-bottom: 24px; }

    .form-group label {
        display: block; color: rgba(255, 255, 255, 0.9);
        font-size: 14px; font-weight: 500; margin-bottom: 8px;
        text-transform: uppercase; letter-spacing: 0.3px;
    }

    .form-group input {
        width: 100%; padding: 14px 16px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.1);
        color: #ffffff; font-size: 14px; transition: all 0.3s ease;
    }

    .form-group input::placeholder { color: rgba(255, 255, 255, 0.5); }

    .form-group input:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.6);
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
    }

    .form-group input.is-invalid {
        border-color: #ff6b6b;
        background-color: rgba(255, 107, 107, 0.1);
    }

    .form-group input.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.2);
    }

    .error-message {
        color: #ff6b6b; font-size: 12px; margin-top: 6px;
        display: flex; align-items: center; gap: 6px;
    }

    .error-message::before { content: "⚠"; font-size: 14px; }

    .form-actions {
        display: flex; align-items: center;
        justify-content: space-between;
        margin: 20px 0 30px 0; font-size: 14px;
    }

    .remember-me { display: flex; align-items: center; gap: 8px; cursor: pointer; }

    .remember-me input[type="checkbox"] {
        cursor: pointer; width: 16px; height: 16px; accent-color: #d4af37;
    }

    .remember-me label {
        cursor: pointer; color: rgba(255, 255, 255, 0.8);
        margin: 0; font-size: 14px;
    }

    .login-btn {
        width: 100%; padding: 14px;
        background: linear-gradient(135deg, #d4af37 0%, #f0c450 100%);
        color: #000; border: none; border-radius: 10px;
        font-size: 16px; font-weight: 600; cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase; letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
    }

    .login-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4); }
    .login-btn:active { transform: translateY(0); }
    .login-btn:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

    .validation-summary {
        background: rgba(255, 107, 107, 0.15);
        border: 1px solid rgba(255, 107, 107, 0.3);
        border-radius: 8px; padding: 12px 14px;
        margin-bottom: 20px; color: #ff6b6b;
        font-size: 13px; display: none;
    }

    .validation-summary.show { display: block; }
    .validation-summary ul { list-style: none; margin: 0; padding: 0; }
    .validation-summary li { margin: 4px 0; }
    .validation-summary li::before { content: "• "; margin-right: 6px; }

    @media (max-width: 600px) {
        .login-form-container { margin: 20px; padding: 40px 30px; max-width: 100%; }
        .login-header h1 { font-size: 24px; }
    }

    .login-btn.loading { position: relative; color: transparent; }

    .login-btn.loading::after {
        content: ""; position: absolute;
        width: 16px; height: 16px;
        top: 50%; left: 50%;
        margin-left: -8px; margin-top: -8px;
        border: 2px solid rgba(0, 0, 0, 0.3);
        border-radius: 50%; border-top-color: #000;
        animation: spin 0.6s linear infinite;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    .theme-toggle {
        position: fixed; top: 20px; right: 20px; z-index: 1000;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 50%; width: 50px; height: 50px;
        cursor: pointer; display: flex;
        align-items: center; justify-content: center;
        transition: all 0.3s ease; padding: 0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .theme-toggle:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
    }

    .theme-toggle:active { transform: scale(0.95); }

    .theme-toggle svg {
        width: 24px; height: 24px; stroke: white;
        fill: none; stroke-width: 2;
        stroke-linecap: round; stroke-linejoin: round;
    }

    /* ── Theme Variables ── */
    html[data-theme="light"] {
        --bg-overlay: rgba(255, 255, 255, 0.15);
        --form-bg: rgba(255, 255, 255, 0.15);
        --form-border: rgba(0, 0, 0, 0.1);
        --text-primary: #1a1a1a;
        --text-secondary: rgba(0, 0, 0, 0.6);
        --input-bg: rgba(0, 0, 0, 0.05);
        --input-border: rgba(0, 0, 0, 0.15);
    }

    html[data-theme="dark"], html {
        --bg-overlay: rgba(0, 0, 0, 0.25);
        --form-bg: rgba(255, 255, 255, 0.15);
        --form-border: rgba(255, 255, 255, 0.2);
        --text-primary: #ffffff;
        --text-secondary: rgba(255, 255, 255, 0.8);
        --input-bg: rgba(255, 255, 255, 0.1);
        --input-border: rgba(255, 255, 255, 0.3);
    }

    .video-overlay  { background: var(--bg-overlay) !important; }

    .login-form-container {
        background: var(--form-bg) !important;
        border: 1px solid var(--form-border) !important;
    }

    .login-header h1       { color: var(--text-primary) !important; }
    .login-header p        { color: var(--text-secondary) !important; }
    .form-group label      { color: var(--text-primary) !important; }
    .remember-me label     { color: var(--text-secondary) !important; }

    .form-group input {
        background: var(--input-bg) !important;
        border-color: var(--input-border) !important;
        color: var(--text-primary) !important;
    }

    .form-group input::placeholder { color: rgba(255, 255, 255, 0.5); }
    html[data-theme="light"] .form-group input::placeholder { color: rgba(0, 0, 0, 0.4); }
</style>

<!-- Theme Toggle Button -->
<button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
    <svg class="icon-moon" viewBox="0 0 24 24" style="display:none;">
        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
    </svg>
    <svg class="icon-sun" viewBox="0 0 24 24" style="display:none;">
        <circle cx="12" cy="12" r="5"/>
        <line x1="12" y1="1" x2="12" y2="3"/>
        <line x1="12" y1="21" x2="12" y2="23"/>
        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
        <line x1="1" y1="12" x2="3" y2="12"/>
        <line x1="21" y1="12" x2="23" y2="12"/>
        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
    </svg>
</button>

<div class="admin-login-container">
    <img src="{{ Vite::asset('resources/assets/videos/login_gif7.gif') }}" class="video-background" alt="login admin background">

    <div class="video-overlay"></div>

    <div class="login-form-wrapper">
        <div class="login-form-container">
            <div class="login-header">
                <div class="logo">
                    <img src="{{ Vite::asset('resources/images/darkMode_logo.png') }}"  alt="Logo" class="logo-dark">
                    <img src="{{ Vite::asset('resources/images/lightMode_logo.png') }}" alt="Logo" class="logo-light">
                </div>
                <h1>Espace Admin</h1>
                <p>Connexion au tableau de bord administrateur</p>
            </div>

            {{-- ✅ action points to admin.login.submit (POST) --}}
            <form id="adminLoginForm" method="POST" action="{{ route('admin.login.submit') }}" novalidate>
                @csrf

                @if ($errors->any())
                    <div class="validation-summary show">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- ✅ name="login" matches controller --}}
                <div class="form-group">
                    <label for="login">Identifiant (Email, CIN ou Nom)</label>
                    <input
                        type="text"
                        id="login"
                        name="login"
                        placeholder="admin@example.com"
                        value="{{ old('login') }}"
                        class="@error('login') is-invalid @enderror"
                        autocomplete="username"
                        required
                    />
                    @error('login')
                        <div class="error-message" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Mot de Passe</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Entrez votre mot de passe"
                        class="@error('password') is-invalid @enderror"
                        autocomplete="current-password"
                        required
                    />
                    @error('password')
                        <div class="error-message" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember" />
                        <label for="remember">Se souvenir de moi</label>
                    </div>
                </div>

                <button type="submit" class="login-btn" id="submitBtn">
                    Connexion
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form         = document.getElementById('adminLoginForm');
        const submitBtn    = document.getElementById('submitBtn');
        const loginInput   = document.getElementById('login');
        const passwordInput = document.getElementById('password');

        // Login field validation
        loginInput.addEventListener('blur', function () {
            if (this.value.trim() === '') {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });

        loginInput.addEventListener('input', function () {
            if (this.value.trim() !== '') {
                this.classList.remove('is-invalid');
            }
        });

        // Password validation
        passwordInput.addEventListener('blur', function () {
            if (this.value && this.value.length < 6) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });

        passwordInput.addEventListener('input', function () {
            if (this.value.length >= 6) {
                this.classList.remove('is-invalid');
            }
        });

        // Loading state on submit
        form.addEventListener('submit', function () {
            submitBtn.disabled = true;
            submitBtn.classList.add('loading');
        });

        // Clear validation summary on input
        const validationSummary = document.querySelector('.validation-summary');
        if (validationSummary) {
            [loginInput, passwordInput].forEach(input => {
                input.addEventListener('input', function () {
                    if (!this.classList.contains('is-invalid')) {
                        validationSummary.classList.remove('show');
                    }
                });
            });
        }
    });

    // Theme Toggle
    document.addEventListener('DOMContentLoaded', function () {
        const themeToggle = document.getElementById('themeToggle');
        const html        = document.documentElement;
        const savedTheme  = localStorage.getItem('admin-theme') || 'dark';

        html.setAttribute('data-theme', savedTheme);
        updateThemeToggle(savedTheme);

        themeToggle.addEventListener('click', function () {
            const newTheme = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('admin-theme', newTheme);
            updateThemeToggle(newTheme);
        });

        function updateThemeToggle(theme) {
            themeToggle.querySelector('.icon-moon').style.display = theme === 'dark' ? 'none'  : 'block';
            themeToggle.querySelector('.icon-sun').style.display  = theme === 'dark' ? 'block' : 'none';
        }
    });
</script>
</body>
</html>
