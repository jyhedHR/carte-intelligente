@extends('shared.layouts.auth')

@section('title', 'Mot de passe oublié')

@section('form-content')

  <h1 class="form-title anim">Mot de passe oublié</h1>
  <p class="form-sub anim anim-d1">
    Entrez votre adresse email. Nous vous enverrons un lien pour réinitialiser votre mot de passe.
  </p>

  {{-- Success message (after link sent) --}}
  @if (session('status'))
    <div class="alert-success anim">
      <span>✅</span>
      <span>{{ session('status') }}</span>
    </div>
  @endif

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

  <form class="auth-form anim anim-d2" method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="form-group">
      <label class="form-label" for="email">Adresse email</label>
      <input
        class="form-input @error('email') is-invalid @enderror"
        id="email"
        name="email"
        type="email"
        autocomplete="email"
        placeholder="votre@email.tn"
        value="{{ old('email') }}"
        required
        autofocus
      >
    </div>

    <button class="btn btn-gold btn-full" type="submit">
      <span>Envoyer le lien de réinitialisation</span>
      <span>→</span>
    </button>

    <p class="form-footer">
      <a href="{{ route('login') }}">← Retour à la connexion</a>
    </p>
  </form>

@endsection
