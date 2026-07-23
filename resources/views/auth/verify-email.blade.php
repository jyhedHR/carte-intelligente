@extends('shared.layouts.auth')

@section('title', 'Vérification de l\'email')

@section('form-content')

  <h1 class="form-title anim">Vérifiez votre adresse email</h1>
  <p class="form-sub anim anim-d1">
    @auth
      Nous avons envoyé un lien de vérification à <strong>{{ Auth::user()->email }}</strong>.<br>
    @else
      Nous avons envoyé un lien de vérification à votre adresse email.<br>
    @endauth
    Veuillez vérifier votre boîte de réception (et vos spams).
  </p>

  @if (session('status'))
    <div class="alert-success anim">
      <span>✅</span>
      <span>{{ session('status') }}</span>
    </div>
  @endif

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

  <div class="auth-form anim anim-d2">

    <p class="text-center">
      Cliquez sur le lien que nous vous avons envoyé pour activer votre compte.
      @if($isAdmin ?? false)
        <br><small style="opacity:.7">Vous serez redirigé vers l'espace administrateur.</small>
      @endif
    </p>

    {{-- Resend button only works when logged in --}}
    @auth
      <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-gold btn-full">
          Renvoyer le lien de vérification
        </button>
      </form>
    @else
      <p class="text-center" style="font-size:13px; opacity:.7; margin-top:12px;">
        Reconnectez-vous pour renvoyer un nouveau lien.
      </p>
    @endauth

    <p class="form-footer">
      @if($isAdmin ?? false)
        <a href="{{ route('admin.login') }}">← Retour à la connexion admin</a>
      @else
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
          @csrf
          <button type="submit" style="background:none; border:none; cursor:pointer; color:inherit; font:inherit; padding:0;">
            ← Déconnexion
          </button>
        </form>
      @endif
    </p>

  </div>

@endsection
