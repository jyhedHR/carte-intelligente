{{-- resources/views/backoffice/allUsers/modals/manage-permissions-modal-improved.blade.php --}}
{{-- REFACTORED: Now uses 3-step stepper like create-admin modal --}}
@php
    $permListRoute  = route('admin.permissions.list');
    $manageRoute    = route('admin.roles.manage');
@endphp

<div id="pm-overlay" class="pm-overlay" onclick="pmClose(event)">
    <div class="pm-shell" onclick="event.stopPropagation()">

        {{-- Header --}}
        <div class="pm-header">
            <div class="pm-header-left">
                <div class="pm-icon-wrap">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <rect x="3" y="11" width="18" height="11" rx="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>
                <div>
                    <div class="pm-title">Gérer les permissions</div>
                    <div class="pm-sub" id="pm-username">—</div>
                </div>
            </div>
            <button class="pm-close-btn" onclick="pmClose()" aria-label="Fermer">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        {{-- Stepper --}}
        <div class="pm-stepper">
            <div class="pm-step active" id="pmStep1">
                <div class="pm-step-bubble">1</div>
                <span>Identité</span>
            </div>
            <div class="pm-step-line"></div>
            <div class="pm-step" id="pmStep2">
                <div class="pm-step-bubble">2</div>
                <span>Département</span>
            </div>
            <div class="pm-step-line"></div>
            <div class="pm-step" id="pmStep3">
                <div class="pm-step-bubble">3</div>
                <span>Rôle</span>
            </div>
        </div>

        {{-- Body --}}
        <div class="pm-body">
            <form id="pmForm" data-perms-url="{{ $permListRoute }}" data-manage-url="{{ $manageRoute }}">
                @csrf

                {{-- STEP 1 — User Identity --}}
                <div class="pm-pane" id="pmPane1">
                    <div class="pm-section-label">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 1 0-16 0"/></svg>
                        Informations utilisateur
                    </div>
                    <div class="pm-user-info">
                        <div class="pm-info-row">
                            <span class="pm-info-label">Nom :</span>
                            <span class="pm-info-value" id="pm-user-name">—</span>
                        </div>
                        <div class="pm-info-row">
                            <span class="pm-info-label">Email :</span>
                            <span class="pm-info-value" id="pm-user-email">—</span>
                        </div>
                        <div class="pm-info-row">
                            <span class="pm-info-label">Rôle actuel :</span>
                            <span class="pm-info-value" id="pm-user-role">—</span>
                        </div>
                        <div class="pm-info-row">
    <span class="pm-info-label">Départements actuels :</span>
    <span class="pm-info-value" id="pm-user-current-depts">—</span>
</div>
                    </div>
                </div>

                {{-- STEP 2 — Department --}}
                <div class="pm-pane" id="pmPane2" style="display:none">
                    <div class="pm-section-label">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
                        Département(s)
                    </div>
                    <div class="pm-notice">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        Sélectionnez les départements à assigner (plusieurs sélections possibles).
                    </div>
                    <div class="pm-dept-container" id="pm-dept-select">
                        <div class="pm-loading">
                            <svg class="pm-spin" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                            </svg>
                            <span>Chargement des départements…</span>
                        </div>
                    </div>
                </div>

                {{-- STEP 3 — Roles --}}
                <div class="pm-pane" id="pmPane3" style="display:none">
                    <div class="pm-section-label">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        Rôle
                    </div>
                    <div class="pm-notice">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        Choisissez le rôle à assigner à cet utilisateur.
                    </div>
                    <div class="pm-roles-container" id="pm-roles-container">
                        <div class="pm-loading">
                            <svg class="pm-spin" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                            </svg>
                            <span>Chargement des rôles…</span>
                        </div>
                    </div>
                </div>

            </form>
        </div>

        {{-- Footer --}}
        <div class="pm-footer">
            <button type="button" class="pm-btn pm-btn-ghost" onclick="pmClose()">Annuler</button>
            <div style="display:flex; gap:8px;">
                <button type="button" class="pm-btn pm-btn-outline" id="pmPrevBtn" onclick="pmPrev()" style="display:none">
                    ← Précédent
                </button>
                <button type="button" class="pm-btn pm-btn-gold" id="pmNextBtn" onclick="pmNext()">
                    Suivant →
                </button>
                <button type="button" class="pm-btn pm-btn-gold" id="pmSubmitBtn" onclick="pmSave()" style="display:none">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Enregistrer
                </button>
            </div>
        </div>

    </div>
</div>

<style>
/* ══════════════════════════════════════════════════════
   PERMISSIONS MODAL  — dark theme matching backend.css
══════════════════════════════════════════════════════ */

/* ── Overlay ───────────────────────────────────────��─ */
.pm-overlay {
    display: none;
    position: fixed; inset: 0;
    background: rgba(0,0,0,.7);
    backdrop-filter: blur(5px);
    z-index: 1060;
    align-items: center;
    justify-content: center;
    padding: 16px;
}
.pm-overlay.open { display: flex; animation: pmFadeIn .18s ease; }
@keyframes pmFadeIn { from{opacity:0} to{opacity:1} }

/* ── Shell ──────────────────────────────────────────── */
.pm-shell {
    background: var(--bg2, #111316);
    border: 1px solid var(--border2, rgba(255,255,255,.12));
    border-radius: 16px;
    width: 100%; max-width: 680px;
    max-height: 88vh;
    display: flex; flex-direction: column;
    overflow: hidden;
    box-shadow: 0 28px 80px rgba(0,0,0,.65);
    animation: pmSlideUp .22s cubic-bezier(.4,0,.2,1);
}
@keyframes pmSlideUp { from{opacity:0;transform:translateY(18px)} to{opacity:1;transform:none} }

/* ── Header ─────────────────────────────────────────── */
.pm-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 22px;
    border-bottom: 1px solid var(--border, rgba(255,255,255,.07));
    background: var(--bg3, #181b1f);
    flex-shrink: 0;
}
.pm-header-left { display: flex; align-items: center; gap: 12px; }
.pm-header-icon {
    width: 40px; height: 40px; border-radius: 10px;
    background: var(--purple-dim, rgba(167,139,250,.12));
    border: 1px solid rgba(167,139,250,.25);
    display: flex; align-items: center; justify-content: center;
    color: var(--purple, #a78bfa); flex-shrink: 0;
}
.pm-title { font-size: 15px; font-weight: 700; color: var(--text, #f0f0f0); }
.pm-sub   { font-size: 12px; color: var(--text2, #8a8f9a); margin-top: 2px; }
.pm-close-btn {
    width: 30px; height: 30px; border-radius: 8px;
    border: 1px solid var(--border, rgba(255,255,255,.07));
    background: var(--bg4, #1e2228);
    color: var(--text2, #8a8f9a);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all .15s;
}
.pm-close-btn:hover { border-color: var(--red, #f87171); color: var(--red, #f87171); }

/* ── Department Container ──────────────────────────── */
.pm-dept-container {
    display: flex; flex-direction: column; gap: 10px;
}

/* ── Department Checkbox ────────────────────────────– */
.pm-dept-checkbox {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 14px;
    background: var(--bg3, #181b1f);
    border: 2px solid var(--border, rgba(255,255,255,.07));
    border-radius: 10px;
    cursor: pointer;
    transition: all .15s;
    user-select: none;
}
.pm-dept-checkbox:hover {
    border-color: var(--purple, #a78bfa);
    background: var(--bg4, #1e2228);
}
.pm-dept-checkbox input[type="checkbox"] {
    display: none;
}
.pm-dept-checkbox input[type="checkbox"]:checked ~ .pm-checkbox-visual {
    background: var(--purple, #a78bfa);
    border-color: var(--purple, #a78bfa);
}
.pm-dept-checkbox input[type="checkbox"]:checked ~ .pm-checkbox-label {
    color: var(--text, #f0f0f0);
}

.pm-checkbox-visual {
    width: 20px; height: 20px; border-radius: 6px;
    border: 2px solid var(--border2, rgba(255,255,255,.15));
    flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    background: var(--bg4, #1e2228);
    transition: all .15s;
}
.pm-checkbox-visual svg {
    display: none;
}
.pm-dept-checkbox input[type="checkbox"]:checked ~ .pm-checkbox-visual svg {
    display: block;
    color: #fff;
}

.pm-checkbox-label {
    font-size: 13px; font-weight: 600;
    color: var(--text, #f0f0f0);
    transition: color .15s;
}

/* ── Body ───────────────────────────────────────────── */
.pm-body {
    flex: 1; overflow-y: auto; padding: 16px 22px;
    background: var(--bg2, #111316);
}
.pm-body::-webkit-scrollbar { width: 5px; }
.pm-body::-webkit-scrollbar-track { background: transparent; }
.pm-body::-webkit-scrollbar-thumb {
    background: var(--border2, rgba(255,255,255,.12));
    border-radius: 99px;
}

/* ── Loading ────────────────────────────────────────── */
.pm-loading {
    display: flex; align-items: center; gap: 12px;
    justify-content: center; padding: 48px;
    color: var(--text3, #4a4f5a); font-size: 13px;
}
@keyframes spin { to { transform: rotate(360deg); } }
.pm-spin { animation: spin 1s linear infinite; }

/* ── Roles Container ────────────────────────────────── */
.pm-roles-container {
    display: flex; flex-direction: column; gap: 12px;
}

/* ── Role Item (Radio) ──────────────────────────────– */
.pm-role-item {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 14px;
    background: var(--bg3, #181b1f);
    border: 2px solid var(--border, rgba(255,255,255,.07));
    border-radius: 10px;
    cursor: pointer;
    transition: all .15s;
}
.pm-role-item:hover {
    border-color: var(--purple, #a78bfa);
    background: var(--bg4, #1e2228);
}
.pm-role-item.selected {
    border-color: var(--purple, #a78bfa);
    background: var(--purple-dim, rgba(167,139,250,.08));
}

/* Role radio button */
.pm-role-radio {
    width: 20px; height: 20px; border-radius: 50%;
    border: 2px solid var(--border2, rgba(255,255,255,.15));
    flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    transition: all .15s;
}
.pm-role-item.selected .pm-role-radio {
    border-color: var(--purple, #a78bfa);
    background: var(--purple, #a78bfa);
}
.pm-role-radio::after {
    content: '';
    width: 6px; height: 6px;
    background: var(--bg2, #111316);
    border-radius: 50%;
    display: none;
}
.pm-role-item.selected .pm-role-radio::after {
    display: block;
}

.pm-role-content { flex: 1; }
.pm-role-name {
    font-size: 13px; font-weight: 700;
    color: var(--text, #f0f0f0);
}
.pm-role-desc {
    font-size: 12px; color: var(--text2, #8a8f9a);
    margin-top: 3px;
}

/* ── Permission groups ──────────────────────────────── */
.pm-group {
    margin-bottom: 12px;
    border: 1px solid var(--border, rgba(255,255,255,.07));
    border-radius: 10px; overflow: hidden;
}
.pm-group-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 11px 14px;
    background: var(--bg3, #181b1f);
    cursor: pointer; user-select: none;
    transition: background .12s;
}
.pm-group-header:hover { background: var(--bg4, #1e2228); }
.pm-group-header-left { display: flex; align-items: center; gap: 10px; }
.pm-group-color { width: 3px; height: 18px; border-radius: 99px; flex-shrink: 0; }
.pm-group-name {
    font-size: 12.5px; font-weight: 700;
    color: var(--text, #f0f0f0); letter-spacing: .2px;
}
.pm-group-meta { display: flex; align-items: center; gap: 8px; }
.pm-group-cnt {
    font-size: 11px; font-family: var(--font-mono, monospace);
    color: var(--text3, #4a4f5a);
    background: var(--bg4, #1e2228);
    border: 1px solid var(--border, rgba(255,255,255,.07));
    padding: 1px 8px; border-radius: 99px;
}
.pm-group-checked-cnt {
    font-size: 11px; font-family: var(--font-mono, monospace);
    padding: 1px 8px; border-radius: 99px;
    background: var(--gold-dim, rgba(201,168,76,.12));
    border: 1px solid rgba(201,168,76,.25);
    color: var(--gold, #c9a84c);
    display: none;
}
.pm-group-checked-cnt.visible { display: inline-block; }
.pm-group-chevron {
    color: var(--text3, #4a4f5a);
    transition: transform .2s;
}
.pm-group.collapsed .pm-group-chevron { transform: rotate(-90deg); }

/* Group select-all mini button */
.pm-group-sel-all {
    font-size: 11px; padding: 3px 8px; border-radius: 5px;
    border: 1px solid var(--border, rgba(255,255,255,.07));
    background: transparent;
    color: var(--text3, #4a4f5a);
    cursor: pointer; font-family: inherit; transition: all .12s;
}
.pm-group-sel-all:hover {
    border-color: var(--purple, #a78bfa);
    color: var(--purple, #a78bfa);
}

/* ── Permission items grid ──────────────────────────── */
.pm-items-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 6px;
    padding: 10px 12px;
    background: var(--bg2, #111316);
}
.pm-group.collapsed .pm-items-grid { display: none; }

/* ── Individual permission item ─────────────────────── */
.pm-item {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 10px 12px; border-radius: 8px;
    border: 1px solid var(--border, rgba(255,255,255,.05));
    cursor: pointer; transition: all .14s;
    background: var(--bg3, #181b1f);
    position: relative; user-select: none;
}
.pm-item:hover {
    border-color: var(--border2, rgba(255,255,255,.14));
    background: var(--bg4, #1e2228);
}
.pm-item.checked {
    border-color: rgba(201,168,76,.35);
    background: var(--gold-dim, rgba(201,168,76,.07));
}
.pm-item.hidden { display: none; }

/* Custom checkbox */
.pm-checkbox {
    width: 17px; height: 17px; border-radius: 5px;
    border: 2px solid var(--border2, rgba(255,255,255,.15));
    background: var(--bg4, #1e2228);
    flex-shrink: 0; margin-top: 1px;
    display: flex; align-items: center; justify-content: center;
    transition: all .14s;
}
.pm-item.checked .pm-checkbox {
    background: var(--gold, #c9a84c);
    border-color: var(--gold, #c9a84c);
}
.pm-checkbox svg { display: none; }
.pm-item.checked .pm-checkbox svg { display: block; }

.pm-item-text { flex: 1; min-width: 0; }
.pm-item-name {
    font-size: 12.5px; font-weight: 600;
    color: var(--text, #f0f0f0); line-height: 1.3;
}
.pm-item.checked .pm-item-name { color: var(--gold2, #e8c97a); }
.pm-item-desc {
    font-size: 11px; color: var(--text3, #4a4f5a);
    margin-top: 2px; line-height: 1.4;
}
/* Hidden real checkbox for form submission */
.pm-item input[type=checkbox] { display: none; }

/* ── Empty search state ─────────────────────────────── */
.pm-empty-search {
    display: flex; flex-direction: column; align-items: center;
    gap: 10px; padding: 48px; text-align: center;
    color: var(--text3, #4a4f5a); font-size: 13px;
}

/* ── Stepper ────────────────────────────────────────── */
.pm-stepper {
    display: flex; align-items: center; justify-content: center; gap: 16px;
    padding: 16px 22px;
    border-bottom: 1px solid var(--border, rgba(255,255,255,.07));
    background: var(--bg2, #111316);
    flex-shrink: 0;
}
.pm-step {
    display: flex; flex-direction: column; align-items: center; gap: 8px;
    opacity: .5; transition: opacity .3s;
}
.pm-step.active {
    opacity: 1;
}
.pm-step-bubble {
    width: 32px; height: 32px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 700;
    background: var(--bg4, #1e2228);
    border: 2px solid var(--border, rgba(255,255,255,.07));
    color: var(--text3, #4a4f5a);
    transition: all .3s;
}
.pm-step.active .pm-step-bubble {
    background: linear-gradient(135deg, var(--purple, #a78bfa), var(--gold, #c9a84c));
    border-color: var(--purple, #a78bfa);
    color: #fff;
}
.pm-step span {
    font-size: 11px; font-weight: 600;
    color: var(--text2, #8a8f9a);
    text-transform: uppercase; letter-spacing: .3px;
}
.pm-step.active span {
    color: var(--text, #f0f0f0);
}
.pm-step-line {
    width: 32px; height: 2px;
    background: var(--border, rgba(255,255,255,.07));
    flex-shrink: 0;
}

/* ── Panes ──────────────────────────────────────────── */
.pm-pane {
    display: none;
}
.pm-pane.active {
    display: block;
}

/* ── Section label ──────────────────────────────────── */
.pm-section-label {
    display: flex; align-items: center; gap: 8px;
    font-size: 13px; font-weight: 700;
    color: var(--text, #f0f0f0);
    margin-bottom: 16px;
    text-transform: uppercase; letter-spacing: .3px;
}

/* ── User Info Display ──────────────────────────────── */
.pm-user-info {
    display: flex; flex-direction: column; gap: 14px;
    padding: 16px;
    background: var(--bg3, #181b1f);
    border: 1px solid var(--border, rgba(255,255,255,.07));
    border-radius: 10px;
}
.pm-info-row {
    display: flex; align-items: center; justify-content: space-between;
    gap: 12px;
}
.pm-info-label {
    font-size: 12px; font-weight: 600;
    color: var(--text2, #8a8f9a);
    text-transform: uppercase; letter-spacing: .2px;
}
.pm-info-value {
    font-size: 13px; font-weight: 600;
    color: var(--text, #f0f0f0);
}

/* ── Department Display ────────────────────────────── */
.pm-dept-display {
    padding: 12px 14px;
    background: var(--bg3, #181b1f);
    border: 1px solid var(--border, rgba(255,255,255,.07));
    border-radius: 8px;
    font-size: 13px; color: var(--text, #f0f0f0);
}

/* ── Notice ─────────────────────────────────────────── */
.pm-notice {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 12px 14px;
    background: var(--blue-dim, rgba(96,165,250,.08));
    border: 1px solid rgba(96,165,250,.25);
    border-radius: 8px;
    font-size: 12px; color: var(--text2, #8a8f9a);
    margin-bottom: 16px;
}
.pm-notice svg { flex-shrink: 0; margin-top: 1px; color: var(--blue, #60a5fa); }

/* ── Field/Label ────────────────────────────────────── */
.pm-field {
    display: flex; flex-direction: column; gap: 8px;
}
.pm-label {
    font-size: 12px; font-weight: 600;
    color: var(--text2, #8a8f9a);
    text-transform: uppercase; letter-spacing: .2px;
}

/* ── Permissions Container ──────────────────────────── */
.pm-perms-container {
    display: flex; flex-direction: column; gap: 12px;
}

/* ── Button outline style ───────────────────────────– */
.pm-btn-outline {
    background: transparent;
    border-color: var(--text2, #8a8f9a);
    color: var(--text2, #8a8f9a);
}
.pm-btn-outline:hover {
    border-color: var(--text, #f0f0f0);
    color: var(--text, #f0f0f0);
    background: var(--bg3, #181b1f);
}

/* ── Footer ─────────────────────────────────────────── */
.pm-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 22px;
    border-top: 1px solid var(--border, rgba(255,255,255,.07));
    background: var(--bg3, #181b1f);
    flex-shrink: 0;
}
.pm-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 20px; border-radius: 8px;
    font-size: 13px; font-weight: 600; font-family: inherit;
    cursor: pointer; transition: all .15s; border: 1px solid transparent;
}
.pm-btn:disabled { opacity: .45; cursor: not-allowed; }
.pm-btn-ghost {
    background: transparent;
    border-color: var(--border2, rgba(255,255,255,.12));
    color: var(--text2, #8a8f9a);
}
.pm-btn-ghost:hover { border-color: var(--border2, rgba(255,255,255,.2)); color: var(--text, #f0f0f0); }
.pm-btn-gold {
    background: linear-gradient(135deg, var(--gold, #c9a84c), var(--gold3, #a07830));
    color: #111;
}
.pm-btn-gold:hover:not(:disabled) {
    background: linear-gradient(135deg, var(--gold2, #e8c97a), var(--gold, #c9a84c));
    box-shadow: 0 4px 16px rgba(201,168,76,.3);
}

/* ── Group colors palette ───────────────────────────── */
.pm-color-0 { background: var(--blue,   #60a5fa); }
.pm-color-1 { background: var(--purple, #a78bfa); }
.pm-color-2 { background: var(--teal,   #2dd4bf); }
.pm-color-3 { background: var(--green,  #4ade80); }
.pm-color-4 { background: var(--amber,  #fbbf24); }
.pm-color-5 { background: var(--red,    #f87171); }
.pm-color-6 { background: var(--gold,   #c9a84c); }

@media (max-width: 520px) {
    .pm-items-grid { grid-template-columns: 1fr; }
    .pm-bulk-btns  { display: none; }
    .pm-shell      { max-width: 100%; border-radius: 12px; }
}
</style>

<script>
(function () {
    /* ── State ──────────────────────────────────────── */
    let _pmUserId   = null;
    let _pmSaveUrl  = '/admin/users/update-role-and-dept';
    let _pmCurrentStep = 1;
    let _pmUserData = {};
    let _pmCurrentDepartments = [];   // ← NEW: Store multiple departments

    /* ── Open ───────────────────────────────────────── */
    window.openPermissionsModal = function (userId, userName, userEmail, userRole, userDept, currentDepartments = []) {
        _pmUserId = userId;
        _pmCurrentStep = 1;
        _pmCurrentDepartments = Array.isArray(currentDepartments) ? currentDepartments : (currentDepartments ? [currentDepartments] : []);

        _pmUserData = {
            name: userName,
            email: userEmail || '—',
            role: userRole || '—',
            departments: _pmCurrentDepartments
        };
        
        // Populate user info
        document.getElementById('pm-user-name').textContent = userName;
        document.getElementById('pm-user-email').textContent = userEmail || '—';
        document.getElementById('pm-user-role').textContent = userRole || '—';

        // Show current departments in Step 1
        _pmDisplayCurrentDepartments();

        document.getElementById('pm-overlay').classList.add('open');
        document.getElementById('pm-username').textContent = userName;
        
        _pmUpdateStepper();
        _pmLoad(userId);
    };

    /* ── Show current departments in Step 1 ─────────── */
   function _pmDisplayCurrentDepartments() {
    const container = document.getElementById('pm-user-current-depts');
    if (!container) return;

    if (!_pmCurrentDepartments || _pmCurrentDepartments.length === 0) {
        container.innerHTML = `<span style="color:#888; font-style:italic;">Aucun département assigné</span>`;
        return;
    }

    let html = _pmCurrentDepartments.map(dept => {
        const name = dept.name || dept.name_fr || dept;
        return `<span style="background:#1f2937; color:#60a5fa; padding:3px 9px; border-radius:9999px; font-size:12px; margin:2px;">${_esc(name)}</span>`;
    }).join('');

    container.innerHTML = html;
}

    /* ── Close & Stepper (unchanged) ───────────────── */
    window.pmClose = function (event) {
        if (event && event.target.id !== 'pm-overlay') return;
        document.getElementById('pm-overlay').classList.remove('open');
    };

    window.pmNext = function () { if (_pmCurrentStep < 3) { _pmCurrentStep++; _pmUpdateStepper(); } };
    window.pmPrev = function () { if (_pmCurrentStep > 1) { _pmCurrentStep--; _pmUpdateStepper(); } };

    function _pmUpdateStepper() {
        for (let i = 1; i <= 3; i++) {
            const step = document.getElementById(`pmStep${i}`);
            if (step) step.classList.toggle('active', i <= _pmCurrentStep);
            
            const pane = document.getElementById(`pmPane${i}`);
            if (pane) pane.style.display = (i === _pmCurrentStep) ? 'block' : 'none';
        }

        document.getElementById('pmPrevBtn').style.display = (_pmCurrentStep > 1) ? 'block' : 'none';
        document.getElementById('pmNextBtn').style.display = (_pmCurrentStep < 3) ? 'block' : 'none';
        document.getElementById('pmSubmitBtn').style.display = (_pmCurrentStep === 3) ? 'block' : 'none';
    }

    /* ── Load ───────────────────────────────────────── */
    async function _pmLoad(userId) {
        try {
            // Load departments
            const deptResp = await fetch('/admin/departments/list', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const deptData = deptResp.ok ? await deptResp.json() : { departments: [] };
            
            _pmRenderDepartments(deptData.departments || [], _pmCurrentDepartments);

            // Load roles
            const rolesResp = await fetch('/admin/roles/list', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const rolesData = rolesResp.ok ? await rolesResp.json() : { roles: [] };
            _pmRenderRoles(rolesData.roles || [], null);
        } catch (err) {
            console.error('Error loading data:', err);
        }
    }

    /* ── Render Departments (Multiple selection) ────── */
    function _pmRenderDepartments(departments, currentDepts) {
        const container = document.getElementById('pm-dept-select');
        container.innerHTML = '';

        if (departments.length === 0) {
            container.innerHTML = `<div class="pm-empty-search">Aucun département disponible</div>`;
            return;
        }

        departments.forEach(dept => {
            const isChecked = currentDepts.some(cd => String(cd.id || cd) === String(dept.id));
            
            const label = document.createElement('label');
            label.className = 'pm-dept-checkbox';
            label.innerHTML = `
                <input type="checkbox" name="departments" value="${dept.id}" ${isChecked ? 'checked' : ''}>
                <span class="pm-checkbox-visual">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3.5">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </span>
                <span class="pm-checkbox-label">${_esc(dept.name)}</span>
            `;
            container.appendChild(label);
        });
    }

    /* ── Render Roles (unchanged) ───────────────────── */
    function _pmRenderRoles(roles, currentRole) {
        const container = document.getElementById('pm-roles-container');
        container.innerHTML = '';

        if (roles.length === 0) {
            container.innerHTML = `<div class="pm-empty-search">Aucun rôle disponible.</div>`;
            return;
        }

        roles.forEach(role => {
            const roleEl = document.createElement('div');
            roleEl.className = 'pm-role-item';
            if (String(currentRole) === String(role.id)) roleEl.classList.add('selected');
            roleEl.dataset.roleId = role.id;
            roleEl.innerHTML = `
                <div class="pm-role-radio"></div>
                <div class="pm-role-content">
                    <div class="pm-role-name">${_esc(role.name)}</div>
                    ${role.description ? `<div class="pm-role-desc">${_esc(role.description)}</div>` : ''}
                </div>
            `;
            roleEl.addEventListener('click', () => _pmSelectRole(roleEl));
            container.appendChild(roleEl);
        });
    }

    function _pmSelectRole(roleEl) {
        document.querySelectorAll('.pm-role-item').forEach(el => el.classList.remove('selected'));
        roleEl.classList.add('selected');
    }

    /* ── Save ───────────────────────────────────────── */
    window.pmSave = async function () {
        const btn = document.getElementById('pmSubmitBtn');
        const selectedRole = document.querySelector('.pm-role-item.selected');

        if (!selectedRole) {
            _pmNotify('✗ Veuillez sélectionner un rôle', 'error');
            return;
        }

        const selectedDepts = Array.from(
            document.querySelectorAll('input[name="departments"]:checked')
        ).map(cb => cb.value);

        btn.disabled = true;
        const originalText = btn.innerHTML;
        btn.innerHTML = 'Enregistrement…';

        try {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (!csrfMeta) throw new Error('CSRF token manquant.');

            const res = await fetch(_pmSaveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfMeta.content,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ 
                    user_id: _pmUserId, 
                    role_id: selectedRole.dataset.roleId,
                    department_ids: selectedDepts 
                }),
            });

            const data = await res.json();
            if (data.success) {
                _pmNotify('✓ Mis à jour avec succès', 'success');
                pmClose();
                setTimeout(() => location.reload(), 1200);
            } else {
                throw new Error(data.message || 'Erreur serveur');
            }
        } catch (err) {
            _pmNotify('✗ ' + err.message, 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    };

    /* ── Helpers ─────────────────────────────────────── */
    function _esc(str) {
        return String(str).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
    }

    function _pmNotify(msg, type) {
        if (typeof duNotify === 'function') duNotify(msg, type);
        else if (typeof showNotification === 'function') showNotification(msg, type);
        else alert(msg);
    }
})();
</script>
