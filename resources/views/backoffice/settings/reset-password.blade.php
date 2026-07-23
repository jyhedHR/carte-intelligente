<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du mot de passe - GED Admin</title>
    @vite(['resources/css/app.css'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0b0d0f 0%, #111316 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .reset-container {
            max-width: 450px;
            width: 100%;
            background: rgba(17, 19, 22, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .reset-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .reset-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .reset-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #e8c97a;
            margin-bottom: 0.5rem;
        }

        .reset-header p {
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: #fff;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #c9a84c;
            box-shadow: 0 0 0 3px rgba(201, 168, 76, 0.1);
        }

        .form-input:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .error-message {
            color: #f87171;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        .alert-error {
            background: rgba(248, 113, 113, 0.1);
            border: 1px solid rgba(248, 113, 113, 0.3);
            border-radius: 8px;
            padding: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .alert-error p {
            color: #f87171;
            font-size: 0.75rem;
            margin: 0;
        }

        .alert-success {
            background: rgba(74, 222, 128, 0.1);
            border: 1px solid rgba(74, 222, 128, 0.3);
            border-radius: 8px;
            padding: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .alert-success p {
            color: #4ade80;
            font-size: 0.75rem;
            margin: 0;
        }

        .btn-reset {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, #c9a84c, #a07830);
            border: none;
            border-radius: 8px;
            color: #111;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-reset:hover {
            background: linear-gradient(135deg, #e8c97a, #c9a84c);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(201, 168, 76, 0.3);
        }

        .btn-reset:active {
            transform: translateY(0);
        }

        .btn-reset:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .strength-bar {
            height: 4px;
            border-radius: 2px;
            background: rgba(255, 255, 255, 0.1);
            margin-top: 0.5rem;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            border-radius: 2px;
            transition: width 0.3s, background 0.3s;
        }

        .strength-label {
            font-size: 0.7rem;
            margin-top: 0.25rem;
            min-height: 1rem;
        }

        .password-hint {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-top: 0.5rem;
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.4);
        }

        .password-hint span {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .password-hint .valid {
            color: #4ade80;
        }

        .password-hint .invalid {
            color: rgba(255, 255, 255, 0.3);
        }

        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }

        .back-link a {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.75rem;
            text-decoration: none;
            transition: color 0.2s;
        }

        .back-link a:hover {
            color: #c9a84c;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="reset-header">
            <div class="reset-icon">🔐</div>
            <h1>Nouveau mot de passe</h1>
            <p>Entrez votre nouveau mot de passe</p>
        </div>

        @if ($errors->any())
            <div class="alert-error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if (session('status'))
            <div class="alert-success">
                <p>{{ session('status') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label class="form-label">Adresse e-mail</label>
                <input type="email" name="email" class="form-input" value="{{ $request->email ?? old('email') }}" required readonly disabled>
                <input type="hidden" name="email" value="{{ $request->email ?? old('email') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Nouveau mot de passe</label>
                <input type="password" name="password" class="form-input" id="password" required>
                <div class="strength-bar">
                    <div class="strength-fill" id="strengthFill"></div>
                </div>
                <div class="strength-label" id="strengthLabel"></div>
                <div class="password-hint" id="passwordHints">
                    <span id="hint-length">🔘 8 caractères minimum</span>
                    <span id="hint-upper">🔘 Une majuscule</span>
                    <span id="hint-number">🔘 Un chiffre</span>
                    <span id="hint-special">🔘 Un caractère spécial</span>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" class="form-input" id="password_confirmation" required>
                <div class="error-message" id="matchError" style="display: none;">Les mots de passe ne correspondent pas</div>
            </div>

            <button type="submit" class="btn-reset" id="submitBtn">
                Réinitialiser le mot de passe
            </button>
        </form>

        <div class="back-link">
            <a href="{{ route('admin.login') }}">← Retour à la page de connexion</a>
        </div>
    </div>

    <script>
        const password = document.getElementById('password');
        const confirm = document.getElementById('password_confirmation');
        const strengthFill = document.getElementById('strengthFill');
        const strengthLabel = document.getElementById('strengthLabel');
        const submitBtn = document.getElementById('submitBtn');
        const matchError = document.getElementById('matchError');

        // Password hints elements
        const hintLength = document.getElementById('hint-length');
        const hintUpper = document.getElementById('hint-upper');
        const hintNumber = document.getElementById('hint-number');
        const hintSpecial = document.getElementById('hint-special');

        function checkPasswordStrength(val) {
            let score = 0;
            const checks = {
                length: val.length >= 8,
                upper: /[A-Z]/.test(val),
                number: /[0-9]/.test(val),
                special: /[^A-Za-z0-9]/.test(val)
            };

            if (checks.length) score++;
            if (checks.upper) score++;
            if (checks.number) score++;
            if (checks.special) score++;

            // Update hints
            hintLength.innerHTML = (checks.length ? '✅' : '🔘') + ' 8 caractères minimum';
            hintUpper.innerHTML = (checks.upper ? '✅' : '🔘') + ' Une majuscule';
            hintNumber.innerHTML = (checks.number ? '✅' : '🔘') + ' Un chiffre';
            hintSpecial.innerHTML = (checks.special ? '✅' : '🔘') + ' Un caractère spécial';

            hintLength.className = checks.length ? 'valid' : '';
            hintUpper.className = checks.upper ? 'valid' : '';
            hintNumber.className = checks.number ? 'valid' : '';
            hintSpecial.className = checks.special ? 'valid' : '';

            // Update strength bar
            const percent = (score / 4) * 100;
            strengthFill.style.width = percent + '%';

            let strength = '';
            let color = '';

            if (val.length === 0) {
                strength = '';
                color = 'transparent';
            } else if (score <= 1) {
                strength = 'Très faible';
                color = '#ef4444';
            } else if (score === 2) {
                strength = 'Faible';
                color = '#f59e0b';
            } else if (score === 3) {
                strength = 'Moyen';
                color = '#3b82f6';
            } else {
                strength = 'Fort';
                color = '#10b981';
            }

            strengthFill.style.background = color;
            strengthLabel.textContent = strength;
            strengthLabel.style.color = color;
        }

        function checkPasswordMatch() {
            if (confirm.value.length > 0 && password.value !== confirm.value) {
                matchError.style.display = 'block';
                return false;
            } else {
                matchError.style.display = 'none';
                return true;
            }
        }

        password.addEventListener('input', function() {
            checkPasswordStrength(this.value);
            checkPasswordMatch();
        });

        confirm.addEventListener('input', checkPasswordMatch);

        // Form validation before submit
        document.querySelector('form').addEventListener('submit', function(e) {
            if (password.value !== confirm.value) {
                e.preventDefault();
                matchError.style.display = 'block';
                return false;
            }
            if (password.value.length < 8) {
                e.preventDefault();
                alert('Le mot de passe doit contenir au moins 8 caractères.');
                return false;
            }
            submitBtn.disabled = true;
            submitBtn.textContent = 'Réinitialisation en cours...';
        });
    </script>
</body>
</html>
