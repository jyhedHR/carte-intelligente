@extends('shared.layouts.auth')

@section('title', 'Vérification en deux étapes')

@section('form-content')

<style>
.code-input {
    font-size: 32px;
    letter-spacing: 10px;
    text-align: center;
    padding: 18px;
    border-radius: 10px;
    border: 2px solid rgba(201,168,76,.3);
    background: rgba(201,168,76,.04);
    color: inherit;
    width: 100%;
    font-family: 'Courier New', monospace;
    transition: all .2s;
    outline: none;
}
.code-input:focus {
    border-color: #c9a84c;
    box-shadow: 0 0 0 3px rgba(201,168,76,.12);
}
.or-divider {
    text-align: center;
    position: relative;
    margin: 20px 0;
    color: #9a8f80;
    font-size: 13px;
}
.or-divider::before, .or-divider::after {
    content: '';
    position: absolute;
    top: 50%;
    width: 40%;
    height: 1px;
    background: rgba(201,168,76,.2);
}
.or-divider::before { left: 0; }
.or-divider::after  { right: 0; }
</style>

<div class="login-header">
    <div style="font-size: 44px; margin-bottom: 14px;">🔐</div>
    <h3 class="form-title">Vérification en deux étapes</h3>
    <p class="form-sub">Entrez le code à 6 chiffres affiché dans votre application d'authentification.</p>
</div>

@if($errors->any())
    <div class="alert-error" role="alert">
        ⚠️
        <ul style="margin:0; padding-left:16px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form class="auth-form" method="POST" action="{{ route('mfa.challenge.verify') }}">
    @csrf

    {{-- TOTP code --}}
    <div class="form-group">
        <label class="form-label" for="totp_code" style="text-align:center; display:block;">
            Code d'authentification
        </label>
        <input
            type="text"
            name="totp_code"
            id="totp_code"
            class="code-input"
            placeholder="000000"
            maxlength="6"
            inputmode="numeric"
            autocomplete="one-time-code"
            autofocus
            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
        >
        <p class="form-hint" style="text-align:center; margin-top:6px;">
            Ouvrez Google Authenticator, Authy, ou votre application 2FA
        </p>
    </div>

    <button class="btn btn-gold btn-full" type="submit">
        <span>Vérifier</span>
        <span>→</span>
    </button>

    <div class="or-divider">ou utilisez un code de secours</div>

    <div class="form-group">
        <label class="form-label" for="backup_code" style="text-align:center; display:block;">
            Code de secours
        </label>
        <input
            type="text"
            name="backup_code"
            id="backup_code"
            class="form-input"
            placeholder="XXXXX-XXXXX"
            maxlength="11"
            autocomplete="off"
            style="text-align:center; font-family:monospace; letter-spacing:2px;"
            oninput="this.value = this.value.toUpperCase()"
        >
        <p class="form-hint" style="text-align:center;">
            Entrez l'un de vos codes de secours à usage unique
        </p>
    </div>

    <button class="btn btn-gold btn-full" type="submit"
            style="background: rgba(201,168,76,.15); border: 1.5px solid rgba(201,168,76,.35);">
        <span>Utiliser un code de secours</span>
    </button>

</form>

<div style="margin-top: 24px; text-align:center;">
    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
        @csrf
        <button type="submit"
                style="background:none; border:none; cursor:pointer; color:#9a8f80; font-size:13px; font-family:inherit;">
            ← Annuler et se déconnecter
        </button>
    </form>
</div>

@endsection
