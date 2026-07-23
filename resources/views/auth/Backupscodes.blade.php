@extends('shared.layouts.auth')

@section('title', 'Codes de secours 2FA')

@section('form-content')

<style>
.backup-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin: 20px 0;
}
.backup-code {
    font-family: 'Courier New', monospace;
    font-size: 15px;
    letter-spacing: 3px;
    padding: 12px 10px;
    background: rgba(201,168,76,.07);
    border: 1.5px solid rgba(201,168,76,.25);
    border-radius: 8px;
    text-align: center;
    color: #c9a84c;
    font-weight: 700;
}
.warning-banner {
    background: rgba(239,68,68,.08);
    border: 1.5px solid rgba(239,68,68,.25);
    border-radius: 10px;
    padding: 14px 18px;
    color: #ef4444;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 20px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
}
</style>

<div style="text-align:center; margin-bottom: 24px;">
    <div style="font-size: 48px; margin-bottom: 12px;">🔑</div>
    <h3 class="form-title">Codes de secours</h3>
    <p class="form-sub">Conservez ces codes en lieu sûr. Ils vous permettront d'accéder à votre compte si vous perdez accès à votre application d'authentification.</p>
</div>

@if(session('success'))
    <div class="alert-success" role="status">✅ {{ session('success') }}</div>
@endif

<div class="warning-banner">
    <span>⚠️</span>
    <div>
        <strong>Ces codes ne seront plus affichés après cette page.</strong><br>
        Chaque code ne peut être utilisé qu'une seule fois. Téléchargez-les maintenant.
    </div>
</div>

<div class="backup-grid">
    @foreach($codes as $code)
        <div class="backup-code">{{ $code }}</div>
    @endforeach
</div>

<div style="display: flex; flex-direction: column; gap: 12px; margin-top: 24px;">

    {{-- Download --}}
    <a href="{{ route('mfa.backup-codes.download') }}" class="btn btn-gold btn-full"
       style="text-decoration:none; display:flex; align-items:center; justify-content:center; gap:8px;">
        <span>⬇️</span>
        <span>Télécharger les codes (.txt)</span>
    </a>

    {{-- Copy all --}}
    <button type="button" class="btn btn-gold btn-full"
            style="background: transparent; border: 1.5px solid rgba(201,168,76,.4); color: #c9a84c;"
            onclick="copyAllCodes()">
        📋 Copier tous les codes
    </button>

    {{-- Continue --}}
    <a href="{{ route('dashboard') }}#securite"
       class="btn btn-full"
       style="background: rgba(201,168,76,.1); border: 1.5px solid rgba(201,168,76,.2);
              color: inherit; text-decoration:none; display:flex; align-items:center;
              justify-content:center; gap:8px; padding: 12px; border-radius: 8px; font-weight: 600;">
        ✅ J'ai sauvegardé mes codes — Continuer
    </a>

</div>

<p style="font-size: 12px; color: #9a8f80; text-align:center; margin-top: 20px;">
    Vous pouvez régénérer de nouveaux codes à tout moment depuis votre profil → Sécurité.
</p>

<script>
function copyAllCodes() {
    const codes = @json($codes);
    navigator.clipboard.writeText(codes.join('\n')).then(() => {
        const btn = event.target;
        btn.textContent = '✅ Codes copiés !';
        setTimeout(() => btn.textContent = '📋 Copier tous les codes', 2000);
    });
}
</script>

@endsection
