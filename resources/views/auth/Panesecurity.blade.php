{{-- ─────────────────────────────────────
     PANEL: SÉCURITÉ
     Drop this in to replace the existing panel-securite div in profile.blade.php
─────────────────────────────────────── --}}
<div class="panel" id="panel-securite">

    <div class="page-header">
        <div class="page-eyebrow">Protection</div>
        <h1 class="page-title">Sécu<em>rité</em></h1>
        <p class="page-sub">Gérez la sécurité et les accès de votre compte</p>
    </div>

    {{-- ── MFA CARD ── --}}
    <div class="pcard" id="mfaCard">
        <div class="pcard-title">Double authentification (2FA)</div>
        <div class="pcard-sub">Couche de protection supplémentaire via votre application d'authentification</div>

        @if(session('success') && str_contains(session('success'), '2FA') || str_contains(session('success') ?? '', 'codes'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif

        <div class="security-row">
            <div class="sec-info">
                <div class="sec-label">
                    Authentification à deux facteurs
                    @if(Auth::user()->two_factor_confirmed_at)
                        <span class="status-badge s-approved" style="font-size:10px;">✅ Activée</span>
                    @else
                        <span class="status-badge s-pending" style="font-size:10px;">⚠️ Non configurée</span>
                    @endif
                </div>
                <div class="sec-desc">
                    @if(Auth::user()->two_factor_confirmed_at)
                        Configurée le {{ Auth::user()->two_factor_confirmed_at->format('d/m/Y à H:i') }}
                        — Protégé avec Google Authenticator, Authy, etc.
                    @else
                        Protégez votre compte avec une application d'authentification (Google Authenticator, Authy…)
                    @endif
                </div>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" id="mfaToggle"
                    {{ Auth::user()->two_factor_confirmed_at ? 'checked' : '' }}
                    onchange="handleMfaToggle(this.checked)">
                <span class="toggle-slider"></span>
            </label>
        </div>

        {{-- DISABLE CONFIRM FORM (hidden by default) --}}
        <div id="mfaDisableForm" style="display:none; margin-top:20px; padding:20px;
             background:rgba(239,68,68,.05); border:1.5px solid rgba(239,68,68,.2);
             border-radius:10px;">
            <p style="font-size:13px; color:#ef4444; font-weight:600; margin-bottom:14px;">
                ⚠️ Désactiver le 2FA rend votre compte moins sécurisé. Confirmez avec votre mot de passe.
            </p>
            <div class="form-group" style="max-width:320px;">
                <label class="form-label">Mot de passe actuel</label>
                <input type="password" id="mfaDisablePassword" class="form-input" placeholder="••••••••" autocomplete="current-password">
            </div>
            <div style="display:flex; gap:10px; margin-top:14px; flex-wrap:wrap;">
                <button class="btn-primary" style="background:linear-gradient(135deg,#ef4444,#dc2626);"
                        onclick="confirmDisableMfa()">
                    Désactiver le 2FA
                </button>
                <button class="btn-secondary" onclick="cancelDisableMfa()">
                    Annuler
                </button>
            </div>
        </div>

        {{-- BACKUP CODES SECTION (only shown when MFA is active) --}}
        @if(Auth::user()->two_factor_confirmed_at)
            <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--border);">
                <div class="sec-label" style="margin-bottom:8px;">
                    🔑 Codes de secours
                </div>
                <div class="sec-desc" style="margin-bottom:16px;">
                    Utilisez un code de secours si vous perdez accès à votre application d'authentification.
                    Chaque code est à usage unique.
                </div>

                @php
                    $rawCodes = Auth::user()->two_factor_recovery_codes;
                    $usedCount = 0;
                    $totalCount = 0;
                    if ($rawCodes) {
                        try {
                            $decoded = json_decode(decrypt($rawCodes), true);
                            $totalCount = count($decoded);
                            $usedCount  = collect($decoded)->where('used', true)->count();
                        } catch (\Throwable) {}
                    }
                    $remaining = $totalCount - $usedCount;
                @endphp

                <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px; flex-wrap:wrap;">
                    <span style="font-size:13px; color:{{ $remaining <= 2 ? '#ef4444' : 'var(--text2)' }}; font-weight:600;">
                        {{ $remaining }} / {{ $totalCount }} codes restants
                        @if($remaining <= 2)
                            ⚠️ Régénérez-les bientôt !
                        @endif
                    </span>

                    {{-- Visual indicator --}}
                    <div style="flex:1; min-width:120px; height:6px; background:var(--bg3); border-radius:6px; overflow:hidden;">
                        <div style="width:{{ $totalCount > 0 ? round(($remaining/$totalCount)*100) : 0 }}%;
                             height:100%;
                             background:{{ $remaining <= 2 ? '#ef4444' : 'linear-gradient(90deg,#c9a84c,#10b981)' }};
                             border-radius:6px; transition:width .5s;"></div>
                    </div>
                </div>

                {{-- Regenerate form --}}
                <form method="POST" action="{{ route('mfa.backup-codes.regenerate') }}" id="regenForm"
                      style="display:none; margin-bottom:16px; padding:16px;
                             background:var(--bg); border-radius:8px; border:1px solid var(--border);">
                    @csrf
                    <div class="form-group" style="max-width:320px; margin-bottom:12px;">
                        <label class="form-label">Confirmez avec votre mot de passe</label>
                        <input type="password" name="password" class="form-input" placeholder="••••••••" autocomplete="current-password">
                        @error('password')
                            <span style="color:#ef4444; font-size:12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div style="display:flex; gap:10px; flex-wrap:wrap;">
                        <button type="submit" class="btn-primary">🔄 Générer de nouveaux codes</button>
                        <button type="button" class="btn-secondary" onclick="document.getElementById('regenForm').style.display='none'">Annuler</button>
                    </div>
                </form>

                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    <button class="btn-secondary" onclick="document.getElementById('regenForm').style.display='block'">
                        🔄 Régénérer les codes
                    </button>
                    @if(session('mfa_new_backup_codes'))
                        <a href="{{ route('mfa.backup-codes.download') }}" class="btn-secondary"
                           style="text-decoration:none; border-color:rgba(201,168,76,.4); color:var(--gold);">
                            ⬇️ Télécharger les codes
                        </a>
                    @endif
                </div>
            </div>
        @else
            {{-- CTA when MFA is not yet set up --}}
            <div style="margin-top:20px; padding:20px; background:rgba(201,168,76,.06);
                 border:1.5px solid rgba(201,168,76,.2); border-radius:10px; text-align:center;">
                <p style="font-size:13px; color:var(--text2); margin-bottom:14px;">
                    🛡️ Activez le 2FA pour mieux protéger votre compte contre les accès non autorisés.
                </p>
                <a href="{{ route('mfa.setup') }}" class="btn-primary" style="text-decoration:none;">
                    Configurer le 2FA maintenant
                </a>
            </div>
        @endif
    </div>{{-- /mfaCard --}}


    {{-- ── CHANGE PASSWORD ── --}}
    <div class="pcard">
        <div class="pcard-title">Modifier le mot de passe</div>
        <div class="pcard-sub">Utilisez un mot de passe fort d'au moins 8 caractères</div>

        <form method="POST" action="{{ route('profile.password') }}" id="pwForm">
            @csrf @method('PATCH')

            <div class="form-grid">
                <div class="form-group full">
                    <label class="form-label">Mot de passe actuel</label>
                    <input type="password" name="current_password" class="form-input"
                        placeholder="••••••••" autocomplete="current-password">
                    @error('current_password')
                        <span style="color:#ef4444; font-size:12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Nouveau mot de passe</label>
                    <input type="password" name="password" class="form-input"
                        placeholder="••••••••" autocomplete="new-password"
                        id="newPw" oninput="checkPwStrength(this.value)">
                    <div class="pw-strength">
                        <div class="pw-strength-fill" id="pwStrengthBar"></div>
                    </div>
                    <span id="pwStrengthLabel" style="font-size:11px; color:var(--text3); margin-top:4px;"></span>
                </div>

                <div class="form-group">
                    <label class="form-label">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" class="form-input"
                        placeholder="••••••••" autocomplete="new-password">
                </div>
            </div>

            <div class="save-bar">
                <span class="save-hint">🔒 Votre nouveau mot de passe sera appliqué immédiatement</span>
                <button type="submit" class="btn-primary">Mettre à jour</button>
            </div>
        </form>
    </div>

    {{-- ── SESSIONS ── --}}
    <div class="pcard">
        <div class="pcard-title">Sessions actives</div>
        <div class="pcard-sub">Gérez les appareils connectés à votre compte</div>

        <div class="security-row">
            <div class="sec-info">
                <div class="sec-label">💻 Cet appareil
                    <span class="status-badge s-approved" style="font-size:10px;">Actif</span>
                </div>
                <div class="sec-desc">{{ request()->userAgent() }} · {{ request()->ip() }}</div>
            </div>
            <span style="font-size:12px; color:var(--text3);">Maintenant</span>
        </div>

        <div style="margin-top:20px;">
            <form method="POST" action="{{ route('profile.sessions.destroy') }}">
                @csrf
                <button type="submit" class="btn-secondary"
                    style="border-color:rgba(239,68,68,.4); color:#ef4444;">
                    Déconnecter toutes les autres sessions
                </button>
            </form>
        </div>
    </div>

</div>{{-- /panel-securite --}}


{{-- ══ MFA TOGGLE JAVASCRIPT ══ --}}
<script>
function handleMfaToggle(enabled) {
    if (enabled) {
        // Redirect to setup flow (toggle back off until confirmed)
        document.getElementById('mfaToggle').checked = false;
        window.location.href = '{{ route('mfa.setup') }}';
        return;
    }

    // Show password confirmation form for disabling
    document.getElementById('mfaDisableForm').style.display = 'block';
}

function cancelDisableMfa() {
    document.getElementById('mfaToggle').checked = true;
    document.getElementById('mfaDisableForm').style.display = 'none';
    document.getElementById('mfaDisablePassword').value = '';
}

function confirmDisableMfa() {
    const password = document.getElementById('mfaDisablePassword').value;
    if (!password) {
        showToast('⚠️ Entrez votre mot de passe pour confirmer', 'error');
        return;
    }

    fetch('{{ route('profile.mfa.toggle') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ enabled: false, password }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('2FA désactivée.', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast('❌ ' + (data.message || 'Erreur'), 'error');
            document.getElementById('mfaToggle').checked = true;
            document.getElementById('mfaDisableForm').style.display = 'none';
        }
    })
    .catch(() => {
        showToast('❌ Erreur réseau', 'error');
        document.getElementById('mfaToggle').checked = true;
    });
}
</script>
