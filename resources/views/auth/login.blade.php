@extends('shared.layouts.auth')

@section('title', 'Connexion')

@section('form-content')

<div class="login-header">
    <img src="{{ Vite::asset('resources/assets/images/LOGO4.png') }}" alt="Logo" class="login-logo">
    <h3 class="form-title">Espace Citoyen</h3>
    <p class="form-sub">Bienvenue. Entrez vos identifiants pour accéder à votre espace culturel.</p>
</div>

  {{-- Error messages --}}
  @if ($errors->any())
    <div class="alert-error" role="alert">
      <span aria-hidden="true">⚠️</span>
      <ul class="alert-list">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Session status --}}
  @if (session('status'))
    <div class="alert-success" role="status">
      <span aria-hidden="true">✅</span>
      <span>{{ session('status') }}</span>
    </div>
  @endif

  <form class="auth-form" method="POST" action="{{ route('login') }}">
    @csrf

    {{-- Login Field (Email / CIN / Nom) --}}
    <div class="form-group">
      <label class="form-label" for="login">Email ou CIN </label>
      <input
        class="form-input @error('login') is-invalid @enderror"
        id="login"
        name="login"
        type="text"
        autocomplete="username"
        placeholder="jihed.horchani@esprit.tn"
        value="{{ old('login') }}"
        required
        autofocus
        aria-describedby="login-hint"
      >
      <p class="form-hint" id="login-hint">Email, numéro CIN ou nom d'utilisateur</p>
    </div>

    {{-- Password --}}
    <div class="form-group">
      <label class="form-label" for="password">Mot de passe</label>
      <div class="input-wrap">
        <input
          class="form-input @error('password') is-invalid @enderror"
          id="password"
          name="password"
          type="password"
          autocomplete="current-password"
          placeholder="Entrez votre mot de passe"
          required
        >
        <button class="pass-toggle" type="button"
                onclick="togglePass('password', this)" aria-label="Afficher/Masquer le mot de passe">👁️</button>
      </div>
    </div>

    {{-- Remember me + Forgot password --}}
    <div class="remember-row">
      <label class="remember-label">
        <input type="checkbox" name="remember" id="remember"
               {{ old('remember') ? 'checked' : '' }}>
        <span>Se souvenir de moi</span>
      </label>
      <a href="{{ route('password.request') }}" class="forgot-link">
        Mot de passe oublié ?
      </a>
    </div>

    {{-- Submit Button --}}
    <button class="btn btn-gold btn-full" type="submit">
      <span>Se connecter</span>
      <span>→</span>
    </button>

    <div class="divider"><span>ou</span></div>

    {{-- SSO Button --}}
    <button class="sso-btn" type="button" onclick="alert('Connexion SSO en cours de déploiement')">
      <span>🏛️</span>
      <span>Connexion SSO Institutionnel</span>
    </button>

    <div style="margin-top: 28px; display: flex; flex-direction: column; gap: 12px;">
      <p class="form-footer">
        Pas encore de compte ?
        <a href="{{ route('register') }}">Créer un compte</a>
      </p>
      <p class="form-footer">
        <a href="{{ route('home') }}">← Retour au portail</a>
      </p>
    </div>
  </form>

@endsection
