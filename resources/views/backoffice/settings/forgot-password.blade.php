<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - GED Admin</title>
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

        .forgot-container {
            max-width: 450px;
            width: 100%;
            background: rgba(17, 19, 22, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .forgot-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .forgot-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .forgot-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #e8c97a;
            margin-bottom: 0.5rem;
        }

        .forgot-header p {
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

        .btn-send {
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

        .btn-send:hover {
            background: linear-gradient(135deg, #e8c97a, #c9a84c);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(201, 168, 76, 0.3);
        }

        .btn-send:disabled {
            opacity: 0.6;
            cursor: not-allowed;
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
    <div class="forgot-container">
        <div class="forgot-header">
            <div class="forgot-icon">🔒</div>
            <h1>Mot de passe oublié ?</h1>
            <p>Entrez votre adresse e-mail pour recevoir un lien de réinitialisation</p>
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
                <p>✓ {{ session('status') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Adresse e-mail</label>
                <input type="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus>
            </div>

            <button type="submit" class="btn-send" id="submitBtn">
                Envoyer le lien de réinitialisation
            </button>
        </form>

        <div class="back-link">
            <a href="{{ route('admin.login') }}">← Retour à la page de connexion</a>
        </div>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.textContent = 'Envoi en cours...';
        });
    </script>
</body>
</html>
