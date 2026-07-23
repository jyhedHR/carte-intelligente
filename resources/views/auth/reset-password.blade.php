@extends('shared.layouts.auth')

@section('title', 'Réinitialiser le mot de passe')

@section('form-content')

  <h1 class="form-title anim">Nouveau mot de passe</h1>
  <p class="form-sub anim anim-d1">Choisissez un mot de passe sécurisé pour votre compte.</p>

  {{-- Validation errors --}}
  @if ($errors->any())
    <div class="alert-error anim">
      <span>⚠️</span>
      <ul class="alert-list">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form class="auth-form anim anim-d2" method="POST" action="{{ route('password.store') }}">
    @csrf

    {{-- Hidden Token --}}
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    {{-- Email (read-only) --}}
    <div class="form-group">
      <label class="form-label" for="email">Adresse email</label>
      <input
        class="form-input @error('email') is-invalid @enderror"
        id="email"
        name="email"
        type="email"
        autocomplete="email"
        value="{{ old('email', $request->email ?? '') }}"
        required
        readonly
        style="opacity:0.7; cursor:not-allowed;"
      >
    </div>

    {{-- New Password --}}
    <div class="form-group">
      <label class="form-label" for="password">Nouveau mot de passe</label>
      <div class="input-wrap">
        <input
          class="form-input @error('password') is-invalid @enderror"
          id="password"
          name="password"
          type="password"
          autocomplete="new-password"
          placeholder="Minimum 8 caractères"
          required
          autofocus
        >
        <button class="pass-toggle" type="button"
                onclick="togglePass('password', this)" aria-label="Afficher/Masquer">👁</button>
      </div>
    </div>

    {{-- Confirm New Password --}}
    <div class="form-group">
      <label class="form-label" for="password_confirmation">Confirmer le mot de passe</label>
      <div class="input-wrap">
        <input
          class="form-input"
          id="password_confirmation"
          name="password_confirmation"
          type="password"
          autocomplete="new-password"
          placeholder="••••••••••"
          required
        >
        <button class="pass-toggle" type="button"
                onclick="togglePass('password_confirmation', this)" aria-label="Afficher/Masquer">👁</button>
      </div>
    </div>

    {{-- Submit Button --}}
    <button class="btn btn-gold btn-full" type="submit">
      <span>Réinitialiser le mot de passe</span>
      <span>→</span>
    </button>

    <p class="form-footer">
      <a href="{{ route('login') }}">← Retour à la connexion</a>
    </p>
  </form>

@endsection
