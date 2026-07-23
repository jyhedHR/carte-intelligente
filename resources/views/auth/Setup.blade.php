@extends('shared.layouts.auth')

@section('title', 'Configurer la double authentification')

@section('form-content')

<style>
.mfa-step {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 24px;
}
.mfa-step-num {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #c9a84c, #a0782a);
    color: #111;
    font-weight: 800;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(201,168,76,0.35);
}
.mfa-step-body strong { display: block; font-size: 14px; font-weight: 700; margin-bottom: 4px; }
.mfa-step-body span   { font-size: 13px; opacity: .75; }

.qr-container {
    background: #fff;
    border-radius: 12px;
    padding: 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 2px solid rgba(201,168,76,.3);
    box-shadow: 0 4px 20px rgba(201,168,76,.15);
    margin: 0 auto;
}
.qr-container svg { width: 180px; height: 180px; }

.secret-box {
    background: rgba(201,168,76,.08);
    border: 1.5px solid rgba(201,168,76,.25);
    border-radius: 8px;
    padding: 12px 16px;
    font-family: 'Courier New', monospace;
    font-size: 15px;
    letter-spacing: 3px;
    text-align: center;
    color: #c9a84c;
    word-break: break-all;
    cursor: pointer;
    position: relative;
    transition: all .2s;
}
.secret-box:hover { border-color: #c9a84c; }
.secret-box .copy-hint {
    display: block;
    font-family: 'DM Sans', sans-serif;
    font-size: 11px;
    letter-spacing: 0;
    color: #9a8f80;
    margin-top: 6px;
}

.backup-preview {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    margin: 12px 0;
}
.backup-code-item {
    font-family: monospace;
    font-size: 13px;
    padding: 8px 10px;
    background: rgba(201,168,76,.06);
    border: 1px solid rgba(201,168,76,.2);
    border-radius: 6px;
    text-align: center;
    letter-spacing: 2px;
    color: #c9a84c;
}

.code-input {
    font-size: 28px;
    letter-spacing: 8px;
    text-align: center;
    padding: 16px;
    border-radius: 10px;
    border: 2px solid rgba(201,168,76,.3);
    background: rgba(201,168,76,.05);
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

.apps-row {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 8px;
}
.app-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 20px;
    background: rgba(201,168,76,.08);
    border: 1px solid rgba(201,168,76,.2);
    font-size: 12px;
    font-weight: 600;
    color: #c9a84c;
}

.tabs {
    display: flex;
    gap: 8px;
    margin-bottom: 20px;
}
.tab-btn {
    flex: 1;
    padding: 10px;
    border-radius: 8px;
    border: 1.5px solid rgba(201,168,76,.2);
    background: transparent;
    color: #9a8f80;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all .2s;
}
.tab-btn.active {
    background: rgba(201,168,76,.12);
    border-color: #c9a84c;
    color: #c9a84c;
}
.tab-pane { display: none; }
.tab-pane.active { display: block; }
</style>

<div class="login-header" style="margin-bottom: 24px;">
    <div style="font-size: 40px; margin-bottom: 12px;">🔐</div>
    <h3 class="form-title">Configurer le 2FA</h3>
    <p class="form-sub">Protégez votre compte avec une application d'authentification.
        C'est obligatoire et ne prend que 2 minutes.</p>
</div>

@if(session('info'))
    <div class="alert-success" role="status" style="background:rgba(201,168,76,.1); border-color:rgba(201,168,76,.3); color:#c9a84c;">
        ℹ️ {{ session('info') }}
    </div>
@endif

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

{{-- Step 1: App --}}
<div class="mfa-step">
    <div class="mfa-step-num">1</div>
    <div class="mfa-step-body">
        <strong>Installez une application d'authentification</strong>
        <span>Si vous n'en avez pas encore, téléchargez l'une de ces applications :</span>
        <div class="apps-row">
            <span class="app-chip">📱 Google Authenticator</span>
            <span class="app-chip">🔐 Authy</span>
            <span class="app-chip">🛡️ Microsoft Authenticator</span>
        </div>
    </div>
</div>

{{-- Step 2: Scan --}}
<div class="mfa-step">
    <div class="mfa-step-num">2</div>
    <div class="mfa-step-body" style="width:100%">
        <strong>Scannez le QR code ou entrez la clé manuellement</strong>

        <div class="tabs" style="margin-top:12px;">
            <button class="tab-btn active" onclick="switchTab('qr', this)">📷 QR Code</button>
            <button class="tab-btn"        onclick="switchTab('manual', this)">⌨️ Clé manuelle</button>
        </div>

        <div id="tab-qr" class="tab-pane active" style="text-align:center;">
            @if($qrCodeSvg)
                <div class="qr-container">
                    {!! $qrCodeSvg !!}
                </div>
            @else
                <div style="padding:40px; background:rgba(201,168,76,.05); border-radius:12px; color:#9a8f80; border: 2px dashed rgba(201,168,76,.2);">
                    QR Code non disponible — utilisez la clé manuelle ci-dessous.
                </div>
            @endif
        </div>

        <div id="tab-manual" class="tab-pane">
            <p style="font-size:12px; color:#9a8f80; margin-bottom:10px;">
                Dans votre application, choisissez "Entrer une clé de configuration" et saisissez :
            </p>
            <div class="secret-box" onclick="copySecret(this)" title="Cliquer pour copier">
                {{ chunk_split($secret, 4, ' ') }}
                <span class="copy-hint">📋 Cliquer pour copier</span>
            </div>
        </div>
    </div>
</div>

{{-- Step 3: Backup codes preview --}}
<div class="mfa-step">
    <div class="mfa-step-num">3</div>
    <div class="mfa-step-body" style="width:100%">
        <strong>Sauvegardez vos codes de secours</strong>
        <span>Ces codes vous permettront d'accéder à votre compte si vous perdez votre téléphone.
              Vous pourrez les télécharger après confirmation.</span>
        <div class="backup-preview" style="margin-top:12px;">
            @foreach($backupCodes as $code)
                <div class="backup-code-item">{{ $code }}</div>
            @endforeach
        </div>
        <p style="font-size:11px; color:#ef4444; margin-top:6px;">
            ⚠️ Ces codes ne seront visibles qu'une seule fois. Téléchargez-les après confirmation.
        </p>
    </div>
</div>

{{-- Step 4: Confirm --}}
<div class="mfa-step">
    <div class="mfa-step-num">4</div>
    <div class="mfa-step-body" style="width:100%">
        <strong>Entrez le code affiché dans votre application</strong>

        <form method="POST" action="{{ route('mfa.setup.confirm') }}" style="margin-top:14px;">
            @csrf
            <div style="max-width: 280px; margin: 0 auto;">
                <input
                    type="text"
                    name="code"
                    class="code-input"
                    placeholder="000000"
                    maxlength="6"
                    inputmode="numeric"
                    autocomplete="one-time-code"
                    autofocus
                    oninput="this.value = this.value.replace(/\D/g,'')"
                >
            </div>
            <div style="margin-top:20px; text-align:center;">
                <button type="submit" class="btn btn-gold btn-full">
                    <span>✅ Confirmer et activer le 2FA</span>
                </button>
            </div>
        </form>
    </div>
</div>

<p class="form-footer" style="margin-top: 16px; text-align:center;">
    <a href="{{ route('logout') }}"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        ← Se déconnecter
    </a>
</p>
<form id="logout-form" method="POST" action="{{ route('logout') }}" hidden>@csrf</form>

<script>
function switchTab(name, btn) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('tab-' + name).classList.add('active');
}

function copySecret(el) {
    const text = el.textContent.replace(/\s+/g, '').replace('Cliquerpourrentcopier', '').trim();
    navigator.clipboard.writeText(text).then(() => {
        const hint = el.querySelector('.copy-hint');
        hint.textContent = '✅ Copié !';
        setTimeout(() => hint.textContent = '📋 Cliquer pour copier', 2000);
    });
}
</script>

@endsection
