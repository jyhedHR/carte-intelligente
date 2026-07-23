@extends('shared.layouts.backoffice')

@section('title', 'Paramètres')

@section('content')
<div class="settings-page">

    {{-- ── Page Header ── --}}
    <div class="settings-header">
        <div>
            <h1 class="settings-title">Paramètres</h1>
            <p class="settings-subtitle">Gérez votre compte et vos préférences</p>
        </div>
        <div class="settings-user-badge">
            <div class="settings-avatar">{{ strtoupper(substr($user->prenom, 0, 1)) }}{{ strtoupper(substr($user->nom, 0, 1)) }}</div>
            <div>
                <div class="settings-user-name">{{ $user->prenom }} {{ $user->nom }}</div>
                <div class="settings-user-role">
                    @if($user->roles->isNotEmpty())
                        {{ $user->roles->pluck('name_fr')->first() ?? $user->roles->pluck('name')->first() }}
                    @else
                        Utilisateur
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ── Horizontal Tab Nav ── --}}
    <div class="settings-tabs">
        <button class="settings-tab active" data-tab="profil">
            <i class="fas fa-user"></i>
            <span>Profil</span>
        </button>
        <button class="settings-tab" data-tab="securite">
            <i class="fas fa-lock"></i>
            <span>Sécurité</span>
        </button>
        <button class="settings-tab" data-tab="preferences">
            <i class="fas fa-palette"></i>
            <span>Apparence & Langue</span>
        </button>
        @if($user->isSuperAdmin())
        <button class="settings-tab" data-tab="gestion-mfa">
            <i class="fas fa-shield-alt"></i>
            <span>Gestion MFA</span>
        </button>
        @endif
        @if($user->can_manage_signature)
        <button class="settings-tab" data-tab="signature">
            <i class="fas fa-signature"></i>
            <span>Signature</span>
        </button>
        @endif
    </div>

    {{-- ── Tab Panels ── --}}
    <div class="settings-panels">

        {{-- PROFIL (Read-only) --}}
        <div class="settings-panel active" id="tab-profil">
            <div class="settings-grid">
                <div class="settings-card settings-card--profile-hero">
                    <div class="profile-avatar-large">
                        {{ strtoupper(substr($user->prenom, 0, 1)) }}{{ strtoupper(substr($user->nom, 0, 1)) }}
                    </div>
                    <div class="profile-name">{{ $user->prenom }} {{ $user->nom }}</div>
                    <div class="profile-email">{{ $user->email }}</div>
                    <span class="profile-badge">
                        <i class="fas fa-star"></i>
                        @if($user->isSuperAdmin())
                            Super Administrateur
                        @elseif($user->roles->isNotEmpty())
                            {{ $user->roles->pluck('name_fr')->first() ?? $user->roles->pluck('name')->first() }}
                        @else
                            Utilisateur
                        @endif
                    </span>
                    <div class="profile-meta">
                        <div class="profile-meta-item">
                            <i class="fas fa-calendar-alt"></i>
                            Membre depuis {{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('F Y') }}
                        </div>
                        @if($user->cin)
                        <div class="profile-meta-item">
                            <i class="fas fa-id-card"></i>
                            CIN: {{ $user->cin }}
                        </div>
                        @endif
                    </div>
                </div>

                <div class="settings-card settings-card--info">
                    <div class="settings-card-header">
                        <h3><i class="fas fa-building"></i> Département</h3>
                        <p>Votre affectation actuelle</p>
                    </div>
                    <div class="info-value">
                        @php
                            $isSuperAdmin = $user->isSuperAdmin();
                        @endphp
                        @if ($isSuperAdmin)
                            <span class="role-badge-full">🏢 Accès complet - Tous les départements</span>
                        @elseif($user->id_direction)
                            @php
                                $department = \App\Models\Department::find($user->id_direction);
                            @endphp
                            @if ($department)
                                {{ $department->name_fr ?? $department->name }}
                            @else
                                Non assigné
                            @endif
                        @else
                            Non assigné
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- SÉCURITÉ --}}
        <div class="settings-panel" id="tab-securite">

            {{-- AUTHENTIFICATION À DEUX FACTEURS (2FA) --}}
            <div class="settings-card settings-card--mfa">
                <div class="mfa-head">
                    <div class="settings-card-header">
                        <h3><i class="fas fa-shield-alt"></i> Authentification à deux facteurs (2FA)</h3>
                        <p>Protégez votre compte avec une application d'authentification (Google Authenticator, Authy…)</p>
                    </div>
                    <label class="mfa-toggle-switch">
                        <input type="checkbox" id="mfaToggle"
                            {{ Auth::user()->two_factor_enabled ?? false ? 'checked' : '' }}>
                        <span class="mfa-toggle-slider"></span>
                    </label>
                </div>

                <span class="mfa-status-badge {{ Auth::user()->two_factor_enabled ?? false ? 'is-on' : 'is-off' }}" id="mfaStatusBadge">
                    <i class="fas {{ Auth::user()->two_factor_enabled ?? false ? 'fa-lock' : 'fa-lock-open' }}"></i>
                    {{ Auth::user()->two_factor_enabled ?? false ? 'Activée' : 'Désactivée' }}
                </span>

                {{-- Enable / setup flow --}}
                <div id="mfaSetup" class="mfa-panel">
                    <div class="mfa-alert mfa-alert--info">
                        <i class="fas fa-circle-info"></i>
                        Scannez le QR code avec votre application d'authentification, puis entrez le code à 6 chiffres.
                    </div>

                    <div id="mfaStep1" class="mfa-step">
                        <p class="mfa-step-label">Étape 1 — Scannez ce QR code</p>
                        <div id="qrCodeContainer" class="mfa-qr-box">
                            <div class="mfa-qr-placeholder">Chargement...</div>
                        </div>
                        <p class="mfa-manual-secret">Ou entrez la clé manuellement : <code id="manualSecret">---</code></p>
                        <div class="mfa-actions">
                            <button type="button" class="btn-primary-settings" id="mfaNextStep1">Suivant (Étape 2)</button>
                            <button type="button" class="btn-secondary-settings" id="mfaCancelStep1">Annuler</button>
                        </div>
                    </div>

                    <div id="mfaStep2" class="mfa-step">
                        <p class="mfa-step-label">Étape 2 — Entrez le code à 6 chiffres</p>
                        <form id="mfaVerifyForm" class="mfa-verify-form">
                            <div class="form-group">
                                <input type="text" id="verifyCode" class="form-control mfa-code-input" placeholder="000000" maxlength="8" inputmode="numeric" autocomplete="one-time-code" required>
                            </div>
                            <div class="mfa-actions">
                                <button type="submit" class="btn-primary-settings">Vérifier le code</button>
                                <button type="button" class="btn-secondary-settings" id="mfaCancelStep2">Annuler</button>
                            </div>
                        </form>
                    </div>

                    <div id="mfaStep3" class="mfa-step">
                        <p class="mfa-step-label">Étape 3 — Conservez ces codes de secours</p>
                        <div class="mfa-backup-box">
                            <p class="mfa-backup-warning"><i class="fas fa-triangle-exclamation"></i> Chaque code ne peut être utilisé qu'une seule fois.</p>
                            <div id="backupCodesDisplay" class="mfa-backup-codes"></div>
                        </div>
                        <div class="mfa-actions">
                            <button type="button" class="btn-secondary-settings" id="mfaDownloadCodes"><i class="fas fa-download"></i> Télécharger</button>
                            <button type="button" class="btn-secondary-settings" id="mfaCopyCodes"><i class="fas fa-copy"></i> Copier</button>
                            <button type="button" class="btn-primary-settings" id="mfaCompleteSetup"><i class="fas fa-check"></i> Sauvegardé</button>
                        </div>
                    </div>

                    <div id="mfaStartBtn">
                        <button type="button" class="btn-primary-settings" id="mfaStartBtnAction">Commencer la configuration</button>
                    </div>
                </div>

                {{-- Disable flow --}}
                <div id="mfaDisable" class="mfa-panel">
                    <div class="mfa-alert mfa-alert--error">
                        <i class="fas fa-triangle-exclamation"></i>
                        Vous êtes sur le point de désactiver la 2FA. Confirmez avec votre mot de passe.
                    </div>
                    <form id="mfaDisableForm">
                        <div class="form-group">
                            <label>Mot de passe</label>
                            <input type="password" id="disableMfaPassword" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
                        </div>
                        <div class="mfa-actions">
                            <button type="submit" class="btn-danger-settings">Désactiver la 2FA</button>
                            <button type="button" class="btn-secondary-settings" id="mfaCancelDisable">Annuler</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="settings-grid settings-grid--2col">
                <div class="settings-card settings-card--form">
                    <div class="settings-card-header">
                        <h3><i class="fas fa-key"></i> Changer le mot de passe</h3>
                        <p>Utilisez un mot de passe fort d'au moins 8 caractères</p>
                    </div>
                    <form id="form-password">
                        @csrf
                        <div class="form-group">
                            <label>Mot de passe actuel</label>
                            <div class="input-icon input-password">
                                <i class="fas fa-lock"></i>
                                <input type="password" name="current_password" class="form-control" required autocomplete="current-password">
                                <button type="button" class="btn-toggle-pwd"><i class="fas fa-eye"></i></button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nouveau mot de passe</label>
                            <div class="input-icon input-password">
                                <i class="fas fa-lock"></i>
                                <input type="password" name="password" id="pwd-new" class="form-control" required minlength="8" autocomplete="new-password">
                                <button type="button" class="btn-toggle-pwd"><i class="fas fa-eye"></i></button>
                            </div>
                            <div class="pwd-strength" id="pwd-strength-bar"></div>
                        </div>
                        <div class="form-group">
                            <label>Confirmer le nouveau mot de passe</label>
                            <div class="input-icon input-password">
                                <i class="fas fa-lock"></i>
                                <input type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                                <button type="button" class="btn-toggle-pwd"><i class="fas fa-eye"></i></button>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn-primary-settings">
                                <i class="fas fa-shield-alt"></i> Mettre à jour le mot de passe
                            </button>
                        </div>
                    </form>
                </div>

                <div class="settings-card settings-card--info">
                    <div class="settings-card-header">
                        <h3><i class="fas fa-history"></i> Conseils de sécurité</h3>
                        <p>Bonnes pratiques pour protéger votre compte</p>
                    </div>
                    <div class="security-tips">
                        <div class="tip-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Utilisez un mot de passe unique</span>
                        </div>
                        <div class="tip-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Ne partagez jamais vos identifiants</span>
                        </div>
                        <div class="tip-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Changez votre mot de passe régulièrement</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- APPARENCE & LANGUE --}}
        <div class="settings-panel" id="tab-preferences">
            <div class="settings-grid settings-grid--2col">

                <div class="settings-card settings-card--form">
                    <div class="settings-card-header">
                        <h3><i class="fas fa-language"></i> Langue de l'interface</h3>
                        <p>Choisissez la langue d'affichage de l'application</p>
                    </div>
                    <div class="lang-options">
                        <label class="lang-option {{ $user->langue === 'FR' ? 'lang-option--active' : '' }}">
                            <input type="radio" name="langue" value="FR" {{ $user->langue === 'FR' ? 'checked' : '' }}>
                            <span class="lang-flag">🇫🇷</span>
                            <div>
                                <div class="lang-name">Français</div>
                                <div class="lang-sub">Interface en français</div>
                            </div>
                            <i class="fas fa-check lang-check"></i>
                        </label>
                        <label class="lang-option {{ $user->langue === 'AR' ? 'lang-option--active' : '' }}">
                            <input type="radio" name="langue" value="AR" {{ $user->langue === 'AR' ? 'checked' : '' }}>
                            <span class="lang-flag">🇹����</span>
                            <div>
                                <div class="lang-name">العربية</div>
                                <div class="lang-sub">واجهة باللغة العربية</div>
                            </div>
                            <i class="fas fa-check lang-check"></i>
                        </label>
                    </div>
                    <div class="form-actions" style="margin-top: 1.5rem;">
                        <button type="button" id="btn-save-langue" class="btn-primary-settings">
                            <i class="fas fa-save"></i> Appliquer la langue
                        </button>
                    </div>
                </div>

                <div class="settings-card settings-card--info">
                    <div class="settings-card-header">
                        <h3><i class="fas fa-palette"></i> Thème</h3>
                        <p>Personnalisez l'apparence</p>
                    </div>
                    <div class="theme-selector">
                        <div class="theme-option" data-theme="dark">
                            <div class="theme-icon">🌙</div>
                            <div class="theme-name">Sombre</div>
                        </div>
                        <div class="theme-option" data-theme="light">
                            <div class="theme-icon">☀️</div>
                            <div class="theme-name">Clair</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- GESTION MFA (Super Admin Only) --}}
        @if($user->isSuperAdmin())
        <div class="settings-panel" id="tab-gestion-mfa">
            <div class="settings-card settings-card--mfa-admin">
                <div class="settings-card-header">
                    <h3><i class="fas fa-shield-alt"></i> Gestion MFA pour tous les utilisateurs</h3>
                    <p>Activez ou désactivez l'authentification à deux facteurs pour les utilisateurs (Admin et Citoyen)</p>
                </div>

                <div class="mfa-admin-search">
                    <div class="search-controls">
                        <input type="text" id="mfaSearchInput" class="form-control mfa-search-input" placeholder="Rechercher par nom ou email...">
                        <select id="mfaRoleFilter" class="form-control mfa-role-filter">
                            <option value="">Tous les rôles</option>
                            <option value="admin">Administrateurs</option>
                            <option value="citizen">Citoyens</option>
                        </select>
                    </div>
                </div>

                <!-- Bulk Action Toolbar -->
                <div class="mfa-bulk-toolbar" id="mfaBulkToolbar" style="display: none;">
                    <div class="bulk-info">
                        <span id="bulkSelectedCount">0</span> utilisateur(s) sélectionné(s)
                    </div>
                    <div class="bulk-actions">
                        <button class="mfa-btn-bulk enable" onclick="bulkToggleMfa(true)" id="bulkEnableBtn">
                            <i class="fas fa-lock"></i> Activer MFA
                        </button>
                        <button class="mfa-btn-bulk disable" onclick="bulkToggleMfa(false)" id="bulkDisableBtn">
                            <i class="fas fa-lock-open"></i> Désactiver MFA
                        </button>
                        <button class="mfa-btn-bulk cancel" onclick="clearAllSelections()">
                            Annuler
                        </button>
                    </div>
                </div>

                <!-- Users List with Checkboxes -->
                <div class="mfa-list-header">
                    <label class="mfa-checkbox-label">
                        <input type="checkbox" id="mfaSelectAllCheckbox" onchange="toggleSelectAll(this)">
                        <span>Sélectionner tout</span>
                    </label>
                </div>

                <div class="mfa-users-list" id="mfaUsersList">
                    <div class="mfa-loading">Chargement des utilisateurs...</div>
                </div>
            </div>
        </div>


    @endif

        {{-- SIGNATURE --}}
        @if($user->can_manage_signature)
        <div class="settings-panel" id="tab-signature">
            <div class="settings-grid">

                {{-- Current signature --}}
                <div class="settings-card">
                    <div class="settings-card-header">
                        <h3><i class="fas fa-signature"></i> Signature actuelle</h3>
                    </div>
                    <div id="sig-current-wrap">
                        @if($user->signature_data)
                            <div style="border:1px solid var(--border-color,#e5e7eb);border-radius:8px;padding:1rem;background:#fafafa;text-align:center;">
                                <img src="{{ $user->signature_data }}" alt="Signature" style="max-width:100%;max-height:120px;">
                            </div>
                            <p style="margin-top:.75rem;font-size:.875rem;color:#16a34a;">
                                <i class="fas fa-check-circle"></i> Signature enregistrée
                            </p>
                            <button type="button" onclick="sigDeleteConfirm()"
                                style="margin-top:.5rem;padding:.4rem .9rem;background:#fee2e2;color:#dc2626;border:1px solid #fca5a5;border-radius:6px;cursor:pointer;font-size:.85rem;">
                                <i class="fas fa-trash-alt"></i> Supprimer
                            </button>
                        @else
                            <div style="text-align:center;padding:2rem;color:var(--text-muted,#6b7280);">
                                <i class="fas fa-pen-nib" style="font-size:2.5rem;opacity:.3;display:block;margin-bottom:.75rem;"></i>
                                Aucune signature enregistrée.
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Draw / Upload --}}
                <div class="settings-card">
                    <div class="settings-card-header">
                        <h3><i class="fas fa-pen"></i> Ajouter / Modifier</h3>
                    </div>

                    {{-- Sub-tabs --}}
                    <div style="display:flex;gap:.5rem;border-bottom:1px solid var(--border-color,#e5e7eb);padding-bottom:.5rem;margin-bottom:1rem;">
                        <button type="button" class="sig-subtab active" data-subtab="draw"
                            style="background:var(--primary-light,#eff6ff);color:var(--primary,#2563eb);border:none;padding:.4rem .85rem;border-radius:6px;cursor:pointer;font-size:.875rem;font-weight:600;">
                            <i class="fas fa-pen-alt"></i> Dessiner
                        </button>
                        <button type="button" class="sig-subtab" data-subtab="upload"
                            style="background:none;border:none;padding:.4rem .85rem;border-radius:6px;cursor:pointer;font-size:.875rem;color:var(--text-muted,#6b7280);">
                            <i class="fas fa-upload"></i> Importer
                        </button>
                    </div>

                    {{-- Draw panel --}}
                    <div id="sig-panel-draw">
                        <p style="font-size:.85rem;color:var(--text-muted,#6b7280);margin-bottom:.75rem;">Tracez votre signature dans le cadre ci-dessous :</p>
                        <div style="border:2px dashed var(--border-color,#d1d5db);border-radius:8px;background:#fff;overflow:hidden;cursor:crosshair;touch-action:none;">
                            <canvas id="sig-canvas" style="display:block;width:100%;height:160px;"></canvas>
                        </div>
                        <div style="display:flex;gap:.75rem;margin-top:.75rem;">
                            <button type="button" onclick="sigClear()"
                                style="padding:.5rem 1rem;background:var(--hover-bg,#f3f4f6);border:1px solid var(--border-color,#d1d5db);border-radius:6px;cursor:pointer;font-size:.875rem;">
                                <i class="fas fa-eraser"></i> Effacer
                            </button>
                            <button type="button" onclick="sigSaveDrawn()"
                                style="padding:.5rem 1rem;background:var(--primary,#2563eb);color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:.875rem;">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                        </div>
                    </div>

                    {{-- Upload panel --}}
                    <div id="sig-panel-upload" style="display:none;">
                        <p style="font-size:.85rem;color:var(--text-muted,#6b7280);margin-bottom:.75rem;">Importez une image PNG ou JPG de votre signature.</p>
                        <div id="sig-upload-zone"
                            onclick="document.getElementById('sig-file-input').click()"
                            ondragover="event.preventDefault();this.style.borderColor='var(--primary,#2563eb)'"
                            ondragleave="this.style.borderColor='var(--border-color,#d1d5db)'"
                            ondrop="sigHandleDrop(event)"
                            style="border:2px dashed var(--border-color,#d1d5db);border-radius:8px;padding:2rem 1rem;text-align:center;cursor:pointer;color:var(--text-muted,#6b7280);">
                            <i class="fas fa-cloud-upload-alt" style="font-size:2rem;display:block;margin-bottom:.5rem;"></i>
                            <p style="margin:0;">Cliquez ou glissez-déposez ici</p>
                            <small>PNG, JPG — max 2 Mo</small>
                        </div>
                        <input type="file" id="sig-file-input" accept="image/png,image/jpeg" style="display:none" onchange="sigHandleFileSelect(event)">
                        <div id="sig-upload-preview-wrap" style="display:none;margin-top:1rem;">
                            <div style="border:1px solid var(--border-color,#e5e7eb);border-radius:8px;padding:1rem;text-align:center;">
                                <img id="sig-upload-preview" src="" style="max-width:100%;max-height:120px;">
                            </div>
                            <button type="button" onclick="sigSaveUpload()"
                                style="margin-top:.75rem;padding:.5rem 1rem;background:var(--primary,#2563eb);color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:.875rem;">
                                <i class="fas fa-save"></i> Enregistrer cette signature
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        @endif

    </div>{{-- /settings-panels --}}
</div>{{-- /settings-page --}}
@endsection

@push('styles')
<style>
/* ════════════════════════════════
   SETTINGS PAGE — Full-width layout
   ════════════════════════════════ */
.settings-page {
    padding: 2rem 2.5rem;
    max-width: 1100px;
}

/* Header */
.settings-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}
.settings-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text, #1a1a2e);
    margin: 0 0 0.25rem;
}
.settings-subtitle {
    color: var(--text2, #6b7280);
    margin: 0;
    font-size: 0.9rem;
}
.settings-user-badge {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: var(--bg2, #fff);
    border: 1px solid var(--border, #e5e7eb);
    border-radius: 12px;
    padding: 0.6rem 1rem;
}
.settings-avatar {
    width: 38px;
    height: 38px;
    background: linear-gradient(135deg, #c9a227, #a07818);
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.85rem;
}
.settings-user-name { font-weight: 600; font-size: 0.9rem; color: var(--text, #1a1a2e); }
.settings-user-role { font-size: 0.78rem; color: var(--text2, #6b7280); }

/* ── Tab Navigation ── */
.settings-tabs {
    display: flex;
    gap: 0.25rem;
    border-bottom: 2px solid var(--border, #e5e7eb);
    margin-bottom: 2rem;
    overflow-x: auto;
    scrollbar-width: none;
}
.settings-tabs::-webkit-scrollbar { display: none; }

.settings-tab {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    border: none;
    background: transparent;
    color: var(--text2, #6b7280);
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    white-space: nowrap;
    border-radius: 6px 6px 0 0;
    transition: color 0.15s, border-color 0.15s;
}
.settings-tab:hover {
    color: #c9a227;
}
.settings-tab.active {
    color: #c9a227;
    border-bottom-color: #c9a227;
    font-weight: 600;
}

/* ── Panels ── */
.settings-panel { display: none; }
.settings-panel.active { display: block; }

/* ── Grid layouts ── */
.settings-grid {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 1.5rem;
    align-items: start;
}
.settings-grid--2col {
    grid-template-columns: 1fr 1fr;
}

/* ── Cards ── */
.settings-card {
    background: var(--bg2, #fff);
    border: 1px solid var(--border, #e5e7eb);
    border-radius: 14px;
    padding: 1.75rem;
}
.settings-card--profile-hero {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 0.5rem;
}
.settings-card--info {
    background: var(--bg3, #f9fafb);
}

.settings-card-header {
    margin-bottom: 1.5rem;
}
.settings-card-header h3 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text, #1a1a2e);
    margin: 0 0 0.3rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.settings-card-header h3 i { color: #c9a227; }
.settings-card-header p {
    font-size: 0.82rem;
    color: var(--text2, #6b7280);
    margin: 0;
}

/* Profile hero card */
.profile-avatar-large {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #c9a227, #a07818);
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}
.profile-name { font-size: 1.1rem; font-weight: 700; color: var(--text, #1a1a2e); }
.profile-email { font-size: 0.82rem; color: var(--text2, #6b7280); }
.profile-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: linear-gradient(135deg, #fef9e7, #fdecd0);
    color: #a07818;
    border: 1px solid #f0d080;
    border-radius: 20px;
    padding: 0.3rem 0.9rem;
    font-size: 0.78rem;
    font-weight: 600;
    margin: 0.25rem 0;
}
.profile-meta {
    margin-top: 0.75rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    width: 100%;
}
.profile-meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.82rem;
    color: var(--text2, #6b7280);
    justify-content: center;
}

/* Forms */
.form-group {
    margin-bottom: 1.25rem;
}
.form-group label {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--text, #374151);
    margin-bottom: 0.45rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
.form-control {
    width: 100%;
    padding: 0.65rem 0.9rem;
    border: 1px solid var(--border, #d1d5db);
    border-radius: 8px;
    font-size: 0.875rem;
    color: var(--text, #1a1a2e);
    background: var(--bg2, #fff);
    transition: border-color 0.15s, box-shadow 0.15s;
    box-sizing: border-box;
}
.form-control:focus {
    outline: none;
    border-color: #c9a227;
    box-shadow: 0 0 0 3px rgba(201,162,39,0.12);
}
.input-icon {
    position: relative;
    display: flex;
    align-items: center;
}
.input-icon > i:first-child {
    position: absolute;
    left: 0.85rem;
    color: #9ca3af;
    font-size: 0.85rem;
    pointer-events: none;
}
.input-icon .form-control {
    padding-left: 2.4rem;
}
.input-password .form-control {
    padding-right: 2.8rem;
}
.btn-toggle-pwd {
    position: absolute;
    right: 0.7rem;
    background: none;
    border: none;
    color: #9ca3af;
    cursor: pointer;
    padding: 0.2rem;
    font-size: 0.85rem;
}
.btn-toggle-pwd:hover { color: #374151; }

/* Password strength */
.pwd-strength {
    height: 4px;
    border-radius: 4px;
    margin-top: 0.5rem;
    background: #e5e7eb;
    transition: all 0.3s;
}
.pwd-strength.weak { background: linear-gradient(90deg, #ef4444 33%, #e5e7eb 33%); }
.pwd-strength.medium { background: linear-gradient(90deg, #f59e0b 66%, #e5e7eb 66%); }
.pwd-strength.strong { background: #22c55e; }

.form-actions {
    margin-top: 1.5rem;
    padding-top: 1.25rem;
    border-top: 1px solid #f3f4f6;
}
.btn-primary-settings {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, #c9a227, #a07818);
    color: #fff;
    border: none;
    padding: 0.7rem 1.5rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: opacity 0.15s, transform 0.1s;
}
.btn-primary-settings:hover { opacity: 0.9; transform: translateY(-1px); }

/* Security tips */
.security-tips { display: flex; flex-direction: column; gap: 0.75rem; }
.tip-item {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    font-size: 0.82rem;
    color: var(--text2, #6b7280);
}
.tip-item i { color: #22c55e; font-size: 0.8rem; }

/* Language selector */
.lang-options { display: flex; flex-direction: column; gap: 0.75rem; }
.lang-option {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.25rem;
    border: 2px solid var(--border, #e5e7eb);
    border-radius: 10px;
    cursor: pointer;
    transition: border-color 0.15s, background 0.15s;
    background: var(--bg2, #fff);
}
.lang-option input { display: none; }
.lang-option:hover { border-color: #c9a227; background: #faf7ee; }
.lang-option--active { border-color: #c9a227; background: #faf7ee; }
.lang-flag { font-size: 1.5rem; }
.lang-name { font-weight: 600; font-size: 0.9rem; color: var(--text, #1a1a2e); }
.lang-sub { font-size: 0.78rem; color: var(--text2, #6b7280); }
.lang-check {
    margin-left: auto;
    color: #c9a227;
    opacity: 0;
    transition: opacity 0.15s;
}
.lang-option--active .lang-check { opacity: 1; }

/* Theme selector */
.theme-selector {
    display: flex;
    gap: 1rem;
}
.theme-option {
    flex: 1;
    padding: 1rem;
    background: var(--bg3, #f9fafb);
    border: 2px solid var(--border, #e5e7eb);
    border-radius: 10px;
    cursor: pointer;
    text-align: center;
    transition: all 0.2s;
}
.theme-option:hover {
    border-color: #c9a227;
}
.theme-option.active {
    border-color: #c9a227;
    background: var(--gold-dim, #faf7ee);
}
.theme-icon {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}
.theme-name {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text, #1a1a2e);
}

/* Info value */
.info-value {
    padding: 0.75rem 1rem;
    background: var(--bg3, #f9fafb);
    border: 1px solid var(--border, #e5e7eb);
    border-radius: 8px;
    color: var(--text, #1a1a2e);
    font-size: 0.9rem;
}

/* ── 2FA / MFA card ── */
.settings-card--mfa { margin-bottom: 1.5rem; }
.mfa-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
}
.mfa-head .settings-card-header { margin-bottom: 1rem; flex: 1; }

.mfa-toggle-switch {
    position: relative;
    display: inline-block;
    width: 46px;
    height: 26px;
    flex-shrink: 0;
}
.mfa-toggle-switch input { opacity: 0; width: 0; height: 0; }
.mfa-toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background: var(--border, #d1d5db);
    border-radius: 26px;
    transition: background 0.2s;
}
.mfa-toggle-slider::before {
    content: "";
    position: absolute;
    height: 20px;
    width: 20px;
    left: 3px;
    bottom: 3px;
    background: #fff;
    border-radius: 50%;
    transition: transform 0.2s;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}
.mfa-toggle-switch input:checked + .mfa-toggle-slider {
    background: linear-gradient(135deg, #c9a227, #a07818);
}
.mfa-toggle-switch input:checked + .mfa-toggle-slider::before {
    transform: translateX(20px);
}

.mfa-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    border-radius: 20px;
    padding: 0.3rem 0.9rem;
    font-size: 0.78rem;
    font-weight: 600;
}
.mfa-status-badge.is-on {
    background: linear-gradient(135deg, #fef9e7, #fdecd0);
    color: #a07818;
    border: 1px solid #f0d080;
}
.mfa-status-badge.is-off {
    background: var(--bg3, #f3f4f6);
    color: var(--text2, #6b7280);
    border: 1px solid var(--border, #e5e7eb);
}

.mfa-panel {
    display: none;
    margin-top: 1.25rem;
    padding: 1.25rem;
    background: var(--bg3, #f9fafb);
    border: 1px solid var(--border, #e5e7eb);
    border-radius: 10px;
}
.mfa-alert {
    display: flex;
    align-items: flex-start;
    gap: 0.6rem;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 0.8rem;
    margin-bottom: 1rem;
}
.mfa-alert--info {
    background: rgba(59,130,246,0.08);
    border: 1px solid rgba(59,130,246,0.25);
    color: #2563eb;
}
.mfa-alert--error {
    background: rgba(239,68,68,0.08);
    border: 1px solid rgba(239,68,68,0.25);
    color: #dc2626;
}
.mfa-step { display: none; text-align: center; }
.mfa-step-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--text, #1a1a2e);
    margin-bottom: 1rem;
}
.mfa-qr-box {
    margin: 0.5rem auto 1rem;
    background: #fff;
    padding: 1rem;
    border-radius: 8px;
    width: fit-content;
}
.mfa-qr-placeholder {
    width: 180px;
    height: 180px;
    background: var(--bg3, #f3f4f6);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text2, #9ca3af);
    font-size: 0.8rem;
}
.mfa-manual-secret {
    font-size: 0.78rem;
    color: var(--text2, #6b7280);
}
.mfa-manual-secret code {
    background: var(--bg, #fff);
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
}
.mfa-verify-form { max-width: 260px; margin: 0 auto; }
.mfa-code-input {
    text-align: center;
    font-size: 1.1rem;
    letter-spacing: 0.3rem;
}
.mfa-actions {
    display: flex;
    gap: 0.75rem;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 1rem;
}
.mfa-backup-box {
    background: rgba(239,68,68,0.05);
    border: 1px solid rgba(239,68,68,0.2);
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 0.5rem;
    text-align: left;
}
.mfa-backup-warning {
    font-size: 0.78rem;
    color: #dc2626;
    font-weight: 600;
    margin-bottom: 0.75rem;
}
.mfa-backup-codes {
    font-family: monospace;
    font-size: 0.85rem;
    color: var(--text, #1a1a2e);
    line-height: 1.8;
    background: var(--bg2, #fff);
    border-radius: 6px;
    padding: 0.75rem;
    max-height: 200px;
    overflow-y: auto;
}
.btn-secondary-settings {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--bg2, #fff);
    color: var(--text, #374151);
    border: 1px solid var(--border, #d1d5db);
    padding: 0.65rem 1.25rem;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: border-color 0.15s, color 0.15s;
}
.btn-secondary-settings:hover { border-color: #c9a227; color: #a07818; }
.btn-danger-settings {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: #fff;
    color: #dc2626;
    border: 1px solid rgba(220,38,38,0.4);
    padding: 0.65rem 1.25rem;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: opacity 0.15s;
}
.btn-danger-settings:hover { opacity: 0.85; }

/* ── MFA Admin Management ── */
.settings-card--mfa-admin {
    background: var(--bg2, #fff);
}

.mfa-admin-search {
    margin-bottom: 2rem;
}

.search-controls {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.mfa-search-input,
.mfa-role-filter {
    flex: 1;
    min-width: 200px;
}

.mfa-users-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    max-height: 600px;
    overflow-y: auto;
}

.mfa-loading,
.mfa-no-results {
    text-align: center;
    padding: 2rem;
    color: var(--text2, #6b7280);
    font-size: 0.9rem;
}

/* Bulk Action Toolbar */
.mfa-bulk-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    padding: 1rem;
    margin-bottom: 1.5rem;
    background: linear-gradient(135deg, #fef9e7, #fdecd0);
    border: 2px solid #f0d080;
    border-radius: 10px;
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.bulk-info {
    font-weight: 600;
    color: #a07818;
    font-size: 0.95rem;
}

.bulk-actions {
    display: flex;
    gap: 0.8rem;
    flex-wrap: wrap;
}

.mfa-btn-bulk {
    padding: 0.6rem 1.2rem;
    border: none;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    white-space: nowrap;
}

.mfa-btn-bulk.enable {
    background: #22c55e;
    color: #fff;
}

.mfa-btn-bulk.enable:hover {
    background: #16a34a;
    transform: translateY(-2px);
}

.mfa-btn-bulk.disable {
    background: #ef4444;
    color: #fff;
}

.mfa-btn-bulk.disable:hover {
    background: #dc2626;
    transform: translateY(-2px);
}

.mfa-btn-bulk.cancel {
    background: var(--bg2, #fff);
    color: var(--text, #1a1a2e);
    border: 1px solid #d1d5db;
}

.mfa-btn-bulk.cancel:hover {
    border-color: #6b7280;
    background: #f3f4f6;
}

.mfa-list-header {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    margin-bottom: 1rem;
    background: var(--bg3, #f9fafb);
    border-radius: 8px;
    border: 1px solid var(--border, #e5e7eb);
}

.mfa-checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--text, #1a1a2e);
}

.mfa-checkbox-label input[type="checkbox"] {
    cursor: pointer;
    width: 18px;
    height: 18px;
}

.mfa-user-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border: 1px solid var(--border, #e5e7eb);
    border-radius: 10px;
    background: var(--bg3, #f9fafb);
    transition: all 0.2s;
}

.mfa-user-item.selected {
    border-color: #c9a227;
    background: #faf7ee;
    box-shadow: 0 0 0 3px rgba(201, 162, 39, 0.1);
}

.mfa-user-item:hover {
    border-color: #c9a227;
    background: #faf7ee;
}

.mfa-user-checkbox {
    cursor: pointer;
    width: 20px;
    height: 20px;
    flex-shrink: 0;
}

.mfa-user-avatar {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #c9a227, #a07818);
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    font-weight: 600;
    flex-shrink: 0;
}

.mfa-user-info {
    flex: 1;
}

.mfa-user-name {
    font-weight: 600;
    color: var(--text, #1a1a2e);
    font-size: 0.95rem;
}

.mfa-user-email {
    font-size: 0.8rem;
    color: var(--text2, #6b7280);
    margin-top: 0.2rem;
}

.mfa-user-meta {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.4rem;
    flex-wrap: wrap;
}

.mfa-role-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    background: linear-gradient(135deg, #fef9e7, #fdecd0);
    color: #a07818;
    border: 1px solid #f0d080;
    border-radius: 12px;
    padding: 0.2rem 0.6rem;
    font-size: 0.7rem;
    font-weight: 600;
}

.mfa-role-badge.admin {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #1e40af;
    border-color: #93c5fd;
}

.mfa-role-badge.citizen {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #1e40af;
    border-color: #93c5fd;
}

.mfa-status {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: 8px;
}

.mfa-status.enabled {
    background: #dcfce7;
    color: #166534;
}

.mfa-status.disabled {
    background: #fee2e2;
    color: #991b1b;
}

.mfa-user-actions {
    display: flex;
    gap: 0.5rem;
    flex-shrink: 0;
}

.mfa-btn-toggle {
    padding: 0.5rem 1rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    background: var(--bg2, #fff);
    color: var(--text, #1a1a2e);
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
}

.mfa-btn-toggle:hover {
    border-color: #c9a227;
    background: #faf7ee;
}

.mfa-btn-toggle.enable {
    border-color: #22c55e;
    color: #16a34a;
}

.mfa-btn-toggle.enable:hover {
    background: #f0fdf4;
    border-color: #16a34a;
}

.mfa-btn-toggle.disable {
    border-color: #ef4444;
    color: #dc2626;
}

.mfa-btn-toggle.disable:hover {
    background: #fef2f2;
    border-color: #dc2626;
}

.mfa-btn-toggle:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Responsive */
@media (max-width: 900px) {
    .settings-page { padding: 1.25rem; }
    .settings-grid { grid-template-columns: 1fr; }
    .settings-grid--2col { grid-template-columns: 1fr; }
    .mfa-head { flex-direction: column; }
    .search-controls { flex-direction: column; }
    .mfa-search-input,
    .mfa-role-filter { min-width: 100%; }
    .mfa-user-item { flex-direction: column; align-items: flex-start; }
    .mfa-user-actions { width: 100%; flex-direction: column; }
    .mfa-btn-toggle { width: 100%; }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Tab switching ──
    document.querySelectorAll('.settings-tab').forEach(tab => {
        tab.addEventListener('click', function () {
            const target = this.dataset.tab;
            document.querySelectorAll('.settings-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.settings-panel').forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            document.getElementById('tab-' + target).classList.add('active');
        });
    });

    // ── Password visibility ──
    document.querySelectorAll('.btn-toggle-pwd').forEach(btn => {
        btn.addEventListener('click', function () {
            const input = this.previousElementSibling;
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        });
    });

    // ── Password strength indicator ──
    const pwdNew = document.getElementById('pwd-new');
    const strengthBar = document.getElementById('pwd-strength-bar');
    if (pwdNew && strengthBar) {
        pwdNew.addEventListener('input', function () {
            const val = this.value;
            let score = 0;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;
            strengthBar.className = 'pwd-strength';
            if (val.length === 0) return;
            if (score <= 1) strengthBar.classList.add('weak');
            else if (score <= 3) strengthBar.classList.add('medium');
            else strengthBar.classList.add('strong');
        });
    }

    // ── Language option selection ──
    document.querySelectorAll('.lang-option').forEach(opt => {
        opt.addEventListener('click', function () {
            document.querySelectorAll('.lang-option').forEach(o => o.classList.remove('lang-option--active'));
            this.classList.add('lang-option--active');
            this.querySelector('input').checked = true;
        });
    });

    // ── Theme selection ──
    const currentTheme = localStorage.getItem('theme') || 'dark';
    document.querySelectorAll('.theme-option').forEach(opt => {
        if (opt.dataset.theme === currentTheme) {
            opt.classList.add('active');
        }
        opt.addEventListener('click', function() {
            const theme = this.dataset.theme;
            document.querySelectorAll('.theme-option').forEach(o => o.classList.remove('active'));
            this.classList.add('active');
            setTheme(theme);
        });
    });

    // ── Toast helper ──
    function showToast(msg, type = 'success') {
        let toast = document.querySelector('.settings-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.className = 'settings-toast';
            document.body.appendChild(toast);
        }
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        toast.className = 'settings-toast ' + type;
        toast.innerHTML = `<i class="fas ${icon}"></i> ${msg}`;
        requestAnimationFrame(() => toast.classList.add('show'));
        setTimeout(() => toast.classList.remove('show'), 3500);
    }

    // ── AJAX form helper ──
    async function postForm(url, data, successMsg) {
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            if (result.success) {
                showToast(successMsg || result.message, 'success');
            } else {
                showToast(result.message || 'Erreur', 'error');
            }
        } catch (error) {
            showToast('Une erreur est survenue', 'error');
        }
    }

    // ── Password form ──
    document.getElementById('form-password')?.addEventListener('submit', function (e) {
        e.preventDefault();
        const fd = new FormData(this);
        const data = Object.fromEntries(fd);
        if (data.password !== data.password_confirmation) {
            showToast('Les mots de passe ne correspondent pas', 'error');
            return;
        }
        if (data.password.length < 8) {
            showToast('Le mot de passe doit contenir au moins 8 caractères', 'error');
            return;
        }
        postForm('{{ route("admin.settings.password.update") }}', data, 'Mot de passe modifié avec succès');
        this.reset();
        if (strengthBar) strengthBar.className = 'pwd-strength';
    });

    // ── 2FA / MFA ──
    let mfaBackupCodes = [];
    const mfaToggle = document.getElementById('mfaToggle');
    const mfaSetup = document.getElementById('mfaSetup');
    const mfaDisable = document.getElementById('mfaDisable');
    const mfaStatusBadge = document.getElementById('mfaStatusBadge');

    if (mfaToggle && mfaToggle.checked) {
        mfaToggle.setAttribute('data-currently-enabled', 'true');
    }

    function csrfToken() {
        return document.querySelector('meta[name="csrf-token"]').content;
    }

    function setMfaBadge(enabled) {
        if (!mfaStatusBadge) return;
        mfaStatusBadge.className = 'mfa-status-badge ' + (enabled ? 'is-on' : 'is-off');
        mfaStatusBadge.innerHTML = `<i class="fas ${enabled ? 'fa-lock' : 'fa-lock-open'}"></i> ${enabled ? 'Activée' : 'Désactivée'}`;
    }

    function resetMfaSetupUI() {
        document.getElementById('mfaStep1').style.display = 'none';
        document.getElementById('mfaStep2').style.display = 'none';
        document.getElementById('mfaStep3').style.display = 'none';
        document.getElementById('mfaStartBtn').style.display = 'block';
        document.getElementById('verifyCode').value = '';
    }

    mfaToggle?.addEventListener('change', function () {
        const enabled = this.checked;
        if (enabled) {
            if (this.hasAttribute('data-currently-enabled')) {
                showToast('La 2FA est déjà activée', 'error');
                this.checked = true;
                return;
            }
            resetMfaSetupUI();
            mfaSetup.style.display = 'block';
            mfaDisable.style.display = 'none';
        } else {
            if (this.hasAttribute('data-currently-enabled')) {
                mfaSetup.style.display = 'none';
                mfaDisable.style.display = 'block';
            } else {
                mfaSetup.style.display = 'none';
                mfaDisable.style.display = 'none';
            }
        }
    });

    document.getElementById('mfaStartBtnAction')?.addEventListener('click', function () {
        document.getElementById('mfaStartBtn').style.display = 'none';
        document.getElementById('mfaStep1').style.display = 'block';

        fetch('{{ route('profile.mfa.start-setup') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken(), 'Content-Type': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const qrContainer = document.getElementById('qrCodeContainer');
                qrContainer.innerHTML = data.qrCode || `<img src="${data.qrUrl}" alt="QR Code" style="width:180px; height:180px;">`;
                document.getElementById('manualSecret').textContent = data.secret;
                showToast('QR code généré. Scannez-le puis cliquez sur "Suivant".', 'success');
            } else {
                showToast(data.message || 'Impossible de générer le QR code', 'error');
                document.getElementById('mfaStartBtn').style.display = 'block';
                document.getElementById('mfaStep1').style.display = 'none';
            }
        })
        .catch(() => {
            showToast('Erreur réseau', 'error');
            document.getElementById('mfaStartBtn').style.display = 'block';
            document.getElementById('mfaStep1').style.display = 'none';
        });
    });

    document.getElementById('mfaNextStep1')?.addEventListener('click', function () {
        document.getElementById('mfaStep1').style.display = 'none';
        document.getElementById('mfaStep2').style.display = 'block';
    });

    document.getElementById('mfaVerifyForm')?.addEventListener('submit', function (e) {
        e.preventDefault();
        const code = document.getElementById('verifyCode').value;
        if (!code || code.length < 6 || code.length > 8) {
            showToast('Veuillez entrer un code à 6-8 chiffres', 'error');
            return;
        }

        const step2 = document.getElementById('mfaStep2');
        step2.style.opacity = '0.6';
        step2.style.pointerEvents = 'none';

        fetch('{{ route('profile.mfa.verify-setup') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken(), 'Content-Type': 'application/json' },
            body: JSON.stringify({ code })
        })
        .then(r => {
            if (!r.ok && r.status === 422) {
                return r.json().then(data => { throw new Error(data.message || 'Code invalide'); });
            }
            return r.json();
        })
        .then(data => {
            if (data.success) {
                mfaBackupCodes = data.backupCodes;
                document.getElementById('backupCodesDisplay').innerHTML = data.backupCodes.map(c => `<div>${c}</div>`).join('');
                step2.style.display = 'none';
                document.getElementById('mfaStep3').style.display = 'block';
                showToast('Code vérifié ! Sauvegardez vos codes de secours', 'success');
            } else {
                showToast(data.message || 'Code invalide. Essayez à nouveau.', 'error');
                document.getElementById('verifyCode').value = '';
                document.getElementById('verifyCode').focus();
            }
            step2.style.opacity = '1';
            step2.style.pointerEvents = 'auto';
        })
        .catch(err => {
            showToast(err.message || 'Erreur lors de la vérification', 'error');
            document.getElementById('verifyCode').value = '';
            document.getElementById('verifyCode').focus();
            step2.style.opacity = '1';
            step2.style.pointerEvents = 'auto';
        });
    });

    document.getElementById('mfaCancelStep1')?.addEventListener('click', cancelMfaSetup);
    document.getElementById('mfaCancelStep2')?.addEventListener('click', cancelMfaSetup);

    function cancelMfaSetup() {
        mfaToggle.checked = false;
        mfaSetup.style.display = 'none';
        resetMfaSetupUI();
    }

    document.getElementById('mfaCompleteSetup')?.addEventListener('click', function () {
        mfaToggle.setAttribute('data-currently-enabled', 'true');
        mfaSetup.style.display = 'none';
        resetMfaSetupUI();
        setMfaBadge(true);
        showToast('2FA activée avec succès ! Votre compte est maintenant protégé.', 'success');
    });

    document.getElementById('mfaCopyCodes')?.addEventListener('click', function () {
        navigator.clipboard.writeText(mfaBackupCodes.join('\n'))
            .then(() => showToast('Codes copiés dans le presse-papiers', 'success'))
            .catch(() => showToast('Impossible de copier', 'error'));
    });

    document.getElementById('mfaDownloadCodes')?.addEventListener('click', function () {
        const element = document.createElement('a');
        element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(mfaBackupCodes.join('\n')));
        element.setAttribute('download', `backup-codes-${new Date().toISOString().split('T')[0]}.txt`);
        element.style.display = 'none';
        document.body.appendChild(element);
        element.click();
        document.body.removeChild(element);
    });

    document.getElementById('mfaDisableForm')?.addEventListener('submit', function (e) {
        e.preventDefault();
        const password = document.getElementById('disableMfaPassword').value;

        fetch('{{ route('profile.mfa.toggle') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken(), 'Content-Type': 'application/json' },
            body: JSON.stringify({ enabled: false, password: password })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                mfaToggle.removeAttribute('data-currently-enabled');
                mfaToggle.checked = false;
                mfaDisable.style.display = 'none';
                document.getElementById('disableMfaPassword').value = '';
                setMfaBadge(false);
                showToast('2FA désactivée', 'success');
            } else {
                showToast(data.message || 'Erreur', 'error');
                mfaToggle.checked = true;
            }
        })
        .catch(() => {
            showToast('Erreur réseau', 'error');
            mfaToggle.checked = true;
        });
    });

    document.getElementById('mfaCancelDisable')?.addEventListener('click', function () {
        mfaToggle.checked = true;
        mfaDisable.style.display = 'none';
        document.getElementById('disableMfaPassword').value = '';
    });

    // ── Language ──
    document.getElementById('btn-save-langue')?.addEventListener('click', function () {
        const selected = document.querySelector('input[name="langue"]:checked')?.value;
        if (selected) {
            postForm('{{ route("admin.settings.language.update") }}', { langue: selected }, 'Langue mise à jour');
            setTimeout(() => location.reload(), 1500);
        }
    });

    // ── Set theme ──
    function setTheme(theme) {
        if (typeof window.toggleMode === 'function') {
            const isDark = document.body.classList.contains('dark');
            if ((theme === 'dark' && !isDark) || (theme === 'light' && isDark)) {
                window.toggleMode();
                showToast(`Thème ${theme === 'dark' ? 'sombre' : 'clair'} activé`, 'success');
            }
        }
    }

    // ══════════════════════════════════════════════════════════════
    // SUPER ADMIN: MFA MANAGEMENT
    // ══════════════════════════════════════════════════════════════

    const mfaSearchInput = document.getElementById('mfaSearchInput');
    const mfaRoleFilter = document.getElementById('mfaRoleFilter');
    const mfaUsersList = document.getElementById('mfaUsersList');

    if (mfaSearchInput && mfaRoleFilter && mfaUsersList) {
        let debounceTimer;

        function loadMfaUsers() {
            const search = mfaSearchInput?.value || '';
            const role = mfaRoleFilter?.value || '';

            mfaUsersList.innerHTML = '<div class="mfa-loading">Chargement...</div>';

            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (role) params.append('role', role);
            params.append('per_page', 15);

            fetch(`/admin/mfa-management/users?${params.toString()}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken()
                }
            })
            .then(r => r.json())
            .then(response => {
                if (response.success && response.data.length) {
                    mfaUsersList.innerHTML = response.data.map(user => createMfaUserRow(user)).join('');
                } else if (response.success && response.data.length === 0) {
                    mfaUsersList.innerHTML = '<div class="mfa-no-results">Aucun utilisateur trouvé</div>';
                } else {
                    mfaUsersList.innerHTML = '<div class="mfa-no-results">Erreur lors du chargement des utilisateurs</div>';
                }
            })
            .catch(() => {
                mfaUsersList.innerHTML = '<div class="mfa-no-results">Erreur réseau</div>';
            });
        }

        function createMfaUserRow(user) {
            const initials = (user.prenom.charAt(0) + user.nom.charAt(0)).toUpperCase();
            const roleLabel = user.role_type === 'admin' ? 'Administrateur' : 'Citoyen';
            const mfaStatus = user.mfa_enabled ? 'Activée' : 'Désactivée';
            const mfaStatusClass = user.mfa_enabled ? 'enabled' : 'disabled';

            return `
                <div class="mfa-user-item" data-user-id="${user.id}">
                    <input type="checkbox" class="mfa-user-checkbox" value="${user.id}" onchange="updateBulkSelection()">
                    <div class="mfa-user-avatar">${initials}</div>
                    <div class="mfa-user-info">
                        <div class="mfa-user-name">${user.prenom} ${user.nom}</div>
                        <div class="mfa-user-email">${user.email}</div>
                        <div class="mfa-user-meta">
                            <span class="mfa-role-badge ${user.role_type}">${roleLabel}</span>
                            <span class="mfa-status ${mfaStatusClass}">
                                <i class="fas ${user.mfa_enabled ? 'fa-lock' : 'fa-lock-open'}"></i>
                                ${mfaStatus}
                            </span>
                            ${user.mfa_forced ? '<span class="mfa-role-badge" title="MFA forcée par admin"><i class="fas fa-info-circle"></i> Forcée</span>' : ''}
                        </div>
                    </div>
                    <div class="mfa-user-actions">
                        ${!user.mfa_enabled ? `<button class="mfa-btn-toggle enable" onclick="toggleMfaStatus(${user.id}, true)">Activer MFA</button>` : ''}
                        ${user.mfa_enabled ? `<button class="mfa-btn-toggle disable" onclick="toggleMfaStatus(${user.id}, false)">Désactiver MFA</button>` : ''}
                    </div>
                </div>
            `;
        }

        function toggleMfaStatus(userId, enable) {
            const action = enable ? 'enable' : 'disable';
            const endpoint = `/admin/mfa-management/${action}/${userId}`;
            const userItem = document.querySelector(`[data-user-id="${userId}"]`);

            if (!userItem) return;

            const button = userItem.querySelector('button');
            const originalText = button.textContent;
            button.disabled = true;
            button.textContent = 'Traitement...';

            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(r => r.json())
            .then(response => {
                if (response.success) {
                    showToast(response.message, 'success');
                    loadMfaUsers(); // Reload the list
                } else {
                    showToast(response.message || 'Erreur lors de la mise à jour', 'error');
                    button.disabled = false;
                    button.textContent = originalText;
                }
            })
            .catch(() => {
                showToast('Erreur réseau', 'error');
                button.disabled = false;
                button.textContent = originalText;
            });
        }

        // Make function global for onclick handlers
        window.toggleMfaStatus = toggleMfaStatus;

        // Event listeners
        mfaSearchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(loadMfaUsers, 300);
        });

        mfaRoleFilter.addEventListener('change', loadMfaUsers);

        // Initial load
        loadMfaUsers();
    }

    // ══════════════════════════════════════════════════════════════
    // BULK MFA MANAGEMENT
    // ══════════════════════════════════════════════════════════════

    window.updateBulkSelection = function() {
        const selectedCount = document.querySelectorAll('.mfa-user-checkbox:checked').length;
        const toolbar = document.getElementById('mfaBulkToolbar');
        const selectAllCheckbox = document.getElementById('mfaSelectAllCheckbox');
        const totalCheckboxes = document.querySelectorAll('.mfa-user-checkbox').length;

        if (selectedCount > 0) {
            toolbar.style.display = 'flex';
            document.getElementById('bulkSelectedCount').textContent = selectedCount;
        } else {
            toolbar.style.display = 'none';
        }

        // Update "Select All" checkbox state
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = selectedCount === totalCheckboxes && totalCheckboxes > 0;
            selectAllCheckbox.indeterminate = selectedCount > 0 && selectedCount < totalCheckboxes;
        }
    };

    window.toggleSelectAll = function(checkbox) {
        const checkboxes = document.querySelectorAll('.mfa-user-checkbox');
        checkboxes.forEach(cb => cb.checked = checkbox.checked);
        updateBulkSelection();
    };

    window.clearAllSelections = function() {
        document.querySelectorAll('.mfa-user-checkbox').forEach(cb => cb.checked = false);
        document.getElementById('mfaSelectAllCheckbox').checked = false;
        document.getElementById('mfaBulkToolbar').style.display = 'none';
    };

    window.bulkToggleMfa = function(enable) {
        const selectedIds = Array.from(document.querySelectorAll('.mfa-user-checkbox:checked'))
            .map(cb => parseInt(cb.value));

        if (selectedIds.length === 0) {
            showToast('Veuillez sélectionner au moins un utilisateur', 'warning');
            return;
        }

        const action = enable ? 'Activer MFA' : 'Désactiver MFA';
        const confirmMessage = `Êtes-vous sûr de vouloir ${enable ? 'activer' : 'désactiver'} la MFA pour ${selectedIds.length} utilisateur(s) ?`;

        if (!confirm(confirmMessage)) {
            return;
        }

        const endpoint = enable
            ? '/admin/mfa-management/bulk-enable'
            : '/admin/mfa-management/bulk-disable';

        const toolbar = document.getElementById('mfaBulkToolbar');
        const buttons = toolbar.querySelectorAll('.mfa-btn-bulk:not(.cancel)');
        buttons.forEach(btn => btn.disabled = true);

        fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ user_ids: selectedIds })
        })
        .then(r => r.json())
        .then(response => {
            if (response.success) {
                showToast(response.message, 'success');
                clearAllSelections();
                setTimeout(() => {
                    const mfaSearchInput = document.getElementById('mfaSearchInput');
                    const mfaRoleFilter = document.getElementById('mfaRoleFilter');
                    if (mfaSearchInput && mfaRoleFilter) {
                        // Trigger reload by calling the load function from outer scope
                        const event = new Event('change');
                        mfaRoleFilter.dispatchEvent(event);
                    }
                }, 500);
            } else {
                showToast(response.message || 'Erreur lors de l\'opération en masse', 'error');
                buttons.forEach(btn => btn.disabled = false);
            }
        })
        .catch(err => {
            console.error('[v0] Bulk MFA error:', err);
            showToast('Erreur réseau', 'error');
            buttons.forEach(btn => btn.disabled = false);
        });
    };

});
// ── Signature ──────────────────────────────────────────────────
    // Sub-tab switching
    document.querySelectorAll('.sig-subtab').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.sig-subtab').forEach(b => {
                b.style.background = 'none';
                b.style.color = 'var(--text-muted,#6b7280)';
                b.style.fontWeight = 'normal';
                b.classList.remove('active');
            });
            this.style.background = 'var(--primary-light,#eff6ff)';
            this.style.color = 'var(--primary,#2563eb)';
            this.style.fontWeight = '600';
            this.classList.add('active');
            document.getElementById('sig-panel-draw').style.display   = this.dataset.subtab === 'draw'   ? 'block' : 'none';
            document.getElementById('sig-panel-upload').style.display = this.dataset.subtab === 'upload' ? 'block' : 'none';
        });
    });

    // Canvas setup
    const sigCanvas = document.getElementById('sig-canvas');
    if (sigCanvas) {
        const sigCtx = sigCanvas.getContext('2d');
        let sigDrawing = false, sigHasDrawn = false;
        const dpr = window.devicePixelRatio || 1;
        const rect = sigCanvas.getBoundingClientRect();
        sigCanvas.width  = (rect.width  || 540) * dpr;
        sigCanvas.height = 160 * dpr;
        sigCtx.scale(dpr, dpr);
        sigCtx.strokeStyle = '#1e3a5f'; sigCtx.lineWidth = 2; sigCtx.lineCap = 'round'; sigCtx.lineJoin = 'round';

        function sigPos(e) { const r = sigCanvas.getBoundingClientRect(), s = e.touches ? e.touches[0] : e; return { x: s.clientX - r.left, y: s.clientY - r.top }; }
        sigCanvas.addEventListener('mousedown',  e => { sigDrawing = true; sigCtx.beginPath(); const p = sigPos(e); sigCtx.moveTo(p.x, p.y); });
        sigCanvas.addEventListener('mousemove',  e => { if (!sigDrawing) return; const p = sigPos(e); sigCtx.lineTo(p.x, p.y); sigCtx.stroke(); sigHasDrawn = true; });
        sigCanvas.addEventListener('mouseup',    () => sigDrawing = false);
        sigCanvas.addEventListener('mouseleave', () => sigDrawing = false);
        sigCanvas.addEventListener('touchstart', e => { e.preventDefault(); sigDrawing = true; sigCtx.beginPath(); const p = sigPos(e); sigCtx.moveTo(p.x, p.y); }, { passive: false });
        sigCanvas.addEventListener('touchmove',  e => { e.preventDefault(); if (!sigDrawing) return; const p = sigPos(e); sigCtx.lineTo(p.x, p.y); sigCtx.stroke(); sigHasDrawn = true; }, { passive: false });
        sigCanvas.addEventListener('touchend',   () => sigDrawing = false);

        window.sigClear = () => { sigCtx.clearRect(0, 0, sigCanvas.width, sigCanvas.height); sigHasDrawn = false; };

        window.sigSaveDrawn = () => {
            if (!sigHasDrawn) { showToast('Tracez votre signature d\'abord.', 'warning'); return; }
            sigPersist(sigCanvas.toDataURL('image/png'), null);
        };
    }

    window.sigHandleFileSelect = e => { if (e.target.files[0]) sigShowUploadPreview(e.target.files[0]); };
    window.sigHandleDrop = e => { e.preventDefault(); const f = e.dataTransfer.files[0]; if (f && f.type.startsWith('image/')) sigShowUploadPreview(f); };
    function sigShowUploadPreview(file) {
        const r = new FileReader();
        r.onload = ev => { document.getElementById('sig-upload-preview').src = ev.target.result; document.getElementById('sig-upload-preview-wrap').style.display = 'block'; };
        r.readAsDataURL(file);
    }
    window.sigSaveUpload = () => {
        const f = document.getElementById('sig-file-input').files[0];
        if (!f) { showToast('Aucun fichier sélectionné.', 'warning'); return; }
        sigPersist(null, f);
    };

    function sigPersist(dataUrl, file) {
        const form = new FormData();
        form.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        if (file) form.append('signature_image', file);
        else       form.append('signature_data', dataUrl);

        fetch('/admin/settings/signature', { method: 'POST', body: form })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    showToast(res.message, 'success');
                    const src = dataUrl || document.getElementById('sig-upload-preview').src;
                    document.getElementById('sig-current-wrap').innerHTML = `
                        <div style="border:1px solid var(--border-color,#e5e7eb);border-radius:8px;padding:1rem;background:#fafafa;text-align:center;">
                            <img src="${src}" style="max-width:100%;max-height:120px;">
                        </div>
                        <p style="margin-top:.75rem;font-size:.875rem;color:#16a34a;"><i class="fas fa-check-circle"></i> Signature enregistrée</p>
                        <button type="button" onclick="sigDeleteConfirm()" style="margin-top:.5rem;padding:.4rem .9rem;background:#fee2e2;color:#dc2626;border:1px solid #fca5a5;border-radius:6px;cursor:pointer;font-size:.85rem;">
                            <i class="fas fa-trash-alt"></i> Supprimer
                        </button>`;
                } else { showToast(res.message || 'Erreur.', 'error'); }
            })
            .catch(() => showToast('Erreur réseau.', 'error'));
    }

    window.sigDeleteConfirm = () => {
        if (!confirm('Supprimer définitivement votre signature ?')) return;
        fetch('/admin/settings/signature', {
            method: 'DELETE',
           headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                showToast(res.message, 'success');
                document.getElementById('sig-current-wrap').innerHTML = `
                    <div style="text-align:center;padding:2rem;color:var(--text-muted,#6b7280);">
                        <i class="fas fa-pen-nib" style="font-size:2.5rem;opacity:.3;display:block;margin-bottom:.75rem;"></i>
                        Aucune signature enregistrée.
                    </div>`;
            } else { showToast(res.message || 'Erreur.', 'error'); }
        })
        .catch(() => showToast('Erreur réseau.', 'error'));
    };
</script>
@endpush
