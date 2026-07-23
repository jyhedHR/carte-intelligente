@extends('shared.layouts.auth')

@section('title', 'Créer un compte')

@section('form-content')

  <h1 class="form-title">Créer un compte</h1>
  <p class="form-sub">Rejoignez le portail pour déposer et suivre vos dossiers culturels facilement.</p>

  {{-- Validation errors --}}
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

  <form class="auth-form" method="POST" action="{{ route('register') }}">
    @csrf

    {{-- Nom --}}
    <div class="form-group">
      <label class="form-label" for="nom">Nom</label>
      <input
        class="form-input @error('nom') is-invalid @enderror"
        id="nom"
        name="nom"
        type="text"
        autocomplete="family-name"
        placeholder="Ben Ali"
        value="{{ old('nom') }}"
        required
        autofocus
      >
    </div>

    {{-- Prénom --}}
    <div class="form-group">
      <label class="form-label" for="prenom">Prénom</label>
      <input
        class="form-input @error('prenom') is-invalid @enderror"
        id="prenom"
        name="prenom"
        type="text"
        autocomplete="given-name"
        placeholder="Mohamed"
        value="{{ old('prenom') }}"
        required
      >
    </div>

    {{-- Email --}}
    <div class="form-group">
      <label class="form-label" for="email">Adresse email</label>
      <input
        class="form-input @error('email') is-invalid @enderror"
        id="email"
        name="email"
        type="email"
        autocomplete="email"
        placeholder="nom@example.tn"
        value="{{ old('email') }}"
        required
        aria-describedby="email-hint"
      >
      <p class="form-hint" id="email-hint">Utilisée pour vérifier votre compte et réinitialiser votre mot de passe</p>
    </div>

    {{-- CIN --}}
    <div class="form-group">
      <label class="form-label" for="cin">Numéro CIN</label>
      <input
        class="form-input @error('cin') is-invalid @enderror"
        id="cin"
        name="cin"
        type="text"
        placeholder="12345678"
        value="{{ old('cin') }}"
        maxlength="8"
        required
        aria-describedby="cin-hint"
      >
      <p class="form-hint" id="cin-hint">Votre numéro de carte d&apos;identité tunisienne (8 chiffres)</p>
    </div>

    {{-- Région --}}
    <div class="form-group">
      <label class="form-label" for="region">Région</label>
      <input
        class="form-input @error('region') is-invalid @enderror"
        id="region"
        name="region"
        type="text"
        list="region-list"
        placeholder="Tapez pour rechercher votre région"
        value="{{ old('region') }}"
        autocomplete="off"
        aria-describedby="region-hint"
      >
      <datalist id="region-list">
        @foreach ($regions as $region)
          <option value="{{ $region }}">{{ $region }}</option>
        @endforeach
      </datalist>
      <p class="form-hint" id="region-hint">Optionnel — votre gouvernorat de résidence</p>
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
          autocomplete="new-password"
          placeholder="Au moins 8 caractères"
          required
          aria-describedby="password-hint"
        >
        <button class="pass-toggle" type="button"
                onclick="togglePass('password', this)" aria-label="Afficher/Masquer le mot de passe">👁️</button>
      </div>
      <p class="form-hint" id="password-hint">Minimum 8 caractères avec majuscules, minuscules et chiffres recommandés</p>
    </div>

    {{-- Confirm Password --}}
    <div class="form-group">
      <label class="form-label" for="password_confirmation">Confirmer le mot de passe</label>
      <div class="input-wrap">
        <input
          class="form-input"
          id="password_confirmation"
          name="password_confirmation"
          type="password"
          autocomplete="new-password"
          placeholder="Répétez votre mot de passe"
          required
        >
        <button class="pass-toggle" type="button"
                onclick="togglePass('password_confirmation', this)" aria-label="Afficher/Masquer le mot de passe">👁️</button>
      </div>
    </div>

    {{-- Submit --}}
    <button class="btn btn-gold btn-full" type="submit">
      <span>Créer mon compte</span>
      <span>→</span>
    </button>

    <div style="margin-top: 28px; display: flex; flex-direction: column; gap: 12px;">
      <p class="form-footer">
        Déjà un compte ?
        <a href="{{ route('login') }}">Se connecter</a>
      </p>
      <p class="form-footer">
        <a href="{{ route('home') }}">← Retour au portail</a>
      </p>
    </div>
  </form>

@endsection
