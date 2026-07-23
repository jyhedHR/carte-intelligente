{{-- resources/views/backoffice/allUsers/modals/create-admin-modal-improved.blade.php --}}
{{-- FIXED: Added is_admin hidden field and proper validation --}}

@php
    $storeRoute = route('admin.users.store');
    $rolesRoute = route('admin.roles.list');
@endphp

<div id="createAdminModalImproved" class="cam-overlay" onclick="closeCamModal(event)">
    <div class="cam-modal" onclick="event.stopPropagation()">

        {{-- Header --}}
        <div class="cam-header">
            <div class="cam-header-left">
                <div class="cam-icon-wrap">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                        <line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/>
                    </svg>
                </div>
                <div>
                    <div class="cam-title">Nouvel administrateur</div>
                    <div class="cam-sub">Complétez les informations ci-dessous</div>
                </div>
            </div>
            <button class="cam-close" onclick="closeCamModal()" aria-label="Fermer">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        {{-- Stepper --}}
        <div class="cam-stepper">
            <div class="cam-step active" id="camStep1">
                <div class="cam-step-bubble">1</div>
                <span>Identité</span>
            </div>
            <div class="cam-step-line"></div>
            <div class="cam-step" id="camStep2">
                <div class="cam-step-bubble">2</div>
                <span>Département</span>
            </div>
            <div class="cam-step-line"></div>
            <div class="cam-step" id="camStep3">
                <div class="cam-step-bubble">3</div>
                <span>Rôles</span>
            </div>
        </div>

        {{-- Body --}}
        <div class="cam-body">
            <form id="camForm" data-store-url="{{ $storeRoute }}" data-roles-url="{{ $rolesRoute }}">
                @csrf



                {{-- STEP 1 — Personal Info --}}
                <div class="cam-pane" id="camPane1">
                    <div class="cam-section-label">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 1 0-16 0"/></svg>
                        Informations personnelles
                    </div>
                    <div class="cam-grid-2">
                        <div class="cam-field">
                            <label class="cam-label">Prénom <span class="cam-req">*</span></label>
                            <input type="text" name="prenom" class="cam-input" placeholder="Jean" required autocomplete="off">
                        </div>
                        <div class="cam-field">
                            <label class="cam-label">Nom <span class="cam-req">*</span></label>
                            <input type="text" name="nom" class="cam-input" placeholder="Dupont" required autocomplete="off">
                        </div>
                    </div>
                    <div class="cam-field">
                        <label class="cam-label">Email <span class="cam-req">*</span></label>
                        <input type="email" name="email" class="cam-input" placeholder="jean.dupont@example.com" required autocomplete="off">
                        <div class="cam-hint">Utilisé pour la connexion et les notifications</div>
                    </div>
                    <div class="cam-grid-2">
                        <div class="cam-field">
                            <label class="cam-label">CIN <span class="cam-req">*</span></label>
                            <input type="text" name="cin" class="cam-input" placeholder="12345678" required autocomplete="off">
                        </div>
                        <div class="cam-field">
                            <label class="cam-label">Téléphone</label>
                            <input type="tel" name="telephone" class="cam-input" placeholder="+216 50 000 000" autocomplete="off">
                        </div>
                    </div>
                </div>

                {{-- STEP 2 — Department --}}
                <div class="cam-pane" id="camPane2" style="display:none">
                    <div class="cam-section-label">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
                        Département (optionnel)
                    </div>
                    <div class="cam-notice">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        Un administrateur sans département peut gérer tous les utilisateurs.
                    </div>
                    <div class="cam-field">
                        <label class="cam-label">Sélectionner un département</label>
                        <select name="id_direction" class="cam-input cam-select">
                            <option value="">— Aucun département —</option>
                            @forelse($allDepartments ?? [] as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name_fr ?? $dept->name }}</option>
                            @empty
                                <option disabled>Aucun département disponible</option>
                            @endforelse
                        </select>
                    </div>
                </div>

                {{-- STEP 3 — Roles --}}
                <div class="cam-pane" id="camPane3" style="display:none">
                    <div class="cam-section-label">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        Rôles & permissions
                    </div>
                    <div id="camRolesGrid" class="cam-roles-grid">
                        <div class="cam-loading">
                            <svg class="cam-spin" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                            Chargement des rôles...
                        </div>
                    </div>
 <div class="cam-perms-preview" id="camPermsPreview" style="display:none">
                        <div class="cam-section-label" style="margin-top:16px; margin-bottom:8px;">Permissions incluses</div>
                        <div id="camPermChips" class="cam-chip-wrap"></div>
                    </div>

                    {{-- Signature permission --}}
                    <div style="margin-top:18px; padding:14px 16px; background:rgba(212,175,55,.06); border:1px solid rgba(212,175,55,.2); border-radius:10px;">
                        <label style="display:flex; align-items:flex-start; gap:12px; cursor:pointer;">
                            <input type="checkbox"
                                   id="cam_can_manage_signature"
                                   name="can_manage_signature"
                                   value="1"
                                   style="margin-top:2px; width:16px; height:16px; accent-color:#D4AF37; flex-shrink:0;">
                            <span>
                                <strong style="font-size:13px; color:var(--text,#e2e8f0);">Autoriser la gestion de la signature</strong><br>
                                <small style="color:var(--text-muted,#94a3b8); font-size:12px; line-height:1.5;">
                                    L'utilisateur pourra ajouter, modifier ou supprimer sa signature électronique depuis son profil. Celle-ci sera intégrée automatiquement dans les documents générés.
                                </small>
                            </span>
                        </label>
                    </div>
                </div>

            </form>
        </div>

        {{-- Footer --}}
        <div class="cam-footer">
            <button type="button" class="cam-btn cam-btn-ghost" onclick="closeCamModal()">Annuler</button>
            <div style="display:flex; gap:8px;">
                <button type="button" class="cam-btn cam-btn-outline" id="camPrevBtn" onclick="camPrev()" style="display:none">
                    ← Précédent
                </button>
                <button type="button" class="cam-btn cam-btn-gold" id="camNextBtn" onclick="camNext()">
                    Suivant →
                </button>
                <button type="button" class="cam-btn cam-btn-gold" id="camSubmitBtn" onclick="camSubmit()" style="display:none">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Créer l'administrateur
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* ── Overlay ───────────────────────────────────────────────────────── */
.cam-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    animation: camFadeIn .3s ease;
}

.cam-overlay.show {
    display: flex;
}

/* ── Modal ─────────────────────────────────────────────────────────── */
.cam-modal {
    background: white;
    border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    width: 90%;
    max-width: 580px;
    display: flex;
    flex-direction: column;
    max-height: 90vh;
    overflow: hidden;
}

/* ── Header ────────────────────────────────────────────────────────── */
.cam-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 24px;
    border-bottom: 1px solid #e5e7eb;
}

.cam-header-left {
    display: flex;
    align-items: center;
    gap: 16px;
    flex: 1;
}

.cam-icon-wrap {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.cam-title {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
}

.cam-sub {
    font-size: 13px;
    color: #6b7280;
}

.cam-close {
    background: none;
    border: none;
    cursor: pointer;
    color: #6b7280;
    padding: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    transition: all .2s ease;
}

.cam-close:hover {
    background: #f3f4f6;
    color: #111827;
}

/* ── Stepper ───────────────────────────────────────────────────────── */
.cam-stepper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    background: #fafafa;
    border-bottom: 1px solid #e5e7eb;
}

.cam-step {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #9ca3af;
    font-size: 13px;
    font-weight: 500;
    flex: 0 0 auto;
}

.cam-step.active {
    color: #f59e0b;
}

.cam-step-bubble {
    width: 28px;
    height: 28px;
    background: white;
    border: 2px solid #d1d5db;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
    transition: all .2s ease;
}

.cam-step.active .cam-step-bubble {
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    border-color: #f59e0b;
    color: white;
}

.cam-step-line {
    flex: 1;
    max-width: 40px;
    height: 2px;
    background: #d1d5db;
    margin: 0 4px;
}

/* ── Body ──────────────────────────────────────────────────────────── */
.cam-body {
    flex: 1;
    overflow-y: auto;
    padding: 24px;
}

.cam-pane {
    animation: camFadeIn .2s ease;
}

.cam-section-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 16px;
}

.cam-notice {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    padding: 12px;
    background: #eef2ff;
    border: 1px solid #dbeafe;
    border-radius: 8px;
    font-size: 13px;
    color: #1e40af;
    margin-bottom: 16px;
}

/* ── Form Fields ───────────────────────────────────────────────────── */
.cam-field {
    margin-bottom: 16px;
}

.cam-label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 6px;
}

.cam-req {
    color: #ef4444;
}

.cam-input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    transition: all .2s ease;
    font-family: inherit;
}

.cam-input:focus {
    outline: none;
    border-color: #f59e0b;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
}

.cam-input.cam-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236B7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3E%3C/svg%3E");
    background-position: right 8px center;
    background-repeat: no-repeat;
    background-size: 20px;
    padding-right: 32px;
}

.cam-hint {
    font-size: 12px;
    color: #9ca3af;
    margin-top: 4px;
}

.cam-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

/* ── Roles Grid ────────────────────────────────────────────────────── */
.cam-roles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 12px;
    margin-bottom: 16px;
}

.cam-role-card {
    padding: 12px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    cursor: pointer;
    transition: all .2s ease;
    position: relative;
}

.cam-role-card:hover {
    border-color: #f59e0b;
    background: #fef9f3;
}

.cam-role-card.selected {
    border-color: #f59e0b;
    background: linear-gradient(135deg, rgba(251, 191, 36, 0.05), rgba(245, 158, 11, 0.05));
}

.cam-role-card input[type=checkbox] {
    position: absolute;
    opacity: 0;
}

.cam-role-name {
    font-size: 13px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
}

.cam-role-key {
    font-size: 11px;
    color: #9ca3af;
    font-family: monospace;
    margin-bottom: 8px;
}

.cam-role-perms {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
}

.cam-perm-chip {
    display: inline-block;
    padding: 2px 6px;
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
    border-radius: 4px;
    font-size: 10px;
    font-weight: 500;
}

.cam-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 32px;
    color: #9ca3af;
    font-size: 13px;
}

.cam-spin {
    animation: camSpin 1s linear infinite;
}

.cam-perms-preview {
    margin-top: 16px;
}

.cam-chip-wrap {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.cam-chip-blue {
    display: inline-block;
    padding: 4px 8px;
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
}

/* ── Footer ────────────────────────────────────────────────────────── */
.cam-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 24px;
    border-top: 1px solid #e5e7eb;
    background: #fafafa;
}

.cam-btn {
    padding: 10px 16px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all .2s ease;
    display: flex;
    align-items: center;
    gap: 6px;
}

.cam-btn-ghost {
    background: white;
    color: #6b7280;
    border: 1px solid #d1d5db;
}

.cam-btn-ghost:hover {
    background: #f9fafb;
    color: #111827;
}

.cam-btn-outline {
    background: white;
    color: #6b7280;
    border: 1px solid #d1d5db;
}

.cam-btn-outline:hover {
    background: #f3f4f6;
}

.cam-btn-gold {
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    color: white;
    border: none;
}

.cam-btn-gold:hover {
    background: linear-gradient(135deg, #fcd34d, #fbbf24);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.cam-btn-gold:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* ── Animations ────────────────────────────────────────────────────── */
@keyframes camFadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes camSpin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

@media (max-width: 640px) {
    .cam-modal {
        width: 95%;
        max-height: 95vh;
    }

    .cam-stepper {
        flex-wrap: wrap;
        gap: 12px;
    }

    .cam-step-line {
        display: none;
    }

    .cam-grid-2 {
        grid-template-columns: 1fr;
    }

    .cam-footer {
        flex-direction: column;
        gap: 12px;
    }

    .cam-footer > div {
        width: 100%;
        display: flex;
        gap: 8px;
    }

    .cam-footer .cam-btn {
        flex: 1;
    }
}
</style>

<script>
(() => {
    let _camRoles = [];
    let _camCurrentStep = 1;

    /* ── Initialization ───────────────────────────────────────────── */
    window.openCamModal = function() {
        const modal = document.getElementById('createAdminModalImproved');
        if (!modal) return;
        modal.classList.add('show');
        _camCurrentStep = 1;
        _camUpdateStepper();
        _camLoadRoles();
    };

    // ✅ Alias for button onclick handler compatibility
    window.openCreateAdminModal = window.openCamModal;

    window.closeCamModal = function(event) {
        if (event && event.target !== event.currentTarget) return;
        const modal = document.getElementById('createAdminModalImproved');
        if (modal) modal.classList.remove('show');
        document.getElementById('camForm').reset();

    };

    /* ── Stepper Navigation ───────────────────────────────────────── */
    window.camNext = function() {
        if (_camCurrentStep < 3) {
            _camCurrentStep++;
            _camUpdateStepper();
        }
    };

    window.camPrev = function() {
        if (_camCurrentStep > 1) {
            _camCurrentStep--;
            _camUpdateStepper();
        }
    };

    function _camUpdateStepper() {
        // Update step indicators
        for (let i = 1; i <= 3; i++) {
            const step = document.getElementById(`camStep${i}`);
            const pane = document.getElementById(`camPane${i}`);
            if (i <= _camCurrentStep) {
                step.classList.add('active');
            } else {
                step.classList.remove('active');
            }
            pane.style.display = (i === _camCurrentStep) ? 'block' : 'none';
        }

        // Update button visibility
        document.getElementById('camPrevBtn').style.display = (_camCurrentStep > 1) ? 'block' : 'none';
        document.getElementById('camNextBtn').style.display = (_camCurrentStep < 3) ? 'block' : 'none';
        document.getElementById('camSubmitBtn').style.display = (_camCurrentStep === 3) ? 'block' : 'none';
    }

    /* ── Load Roles ────────────────────────────────────────────────── */
    async function _camLoadRoles() {
        const form = document.getElementById('camForm');
        const url = form.dataset.rolesUrl;

        try {
            const res = await fetch(url, {
                headers: { 'Accept': 'application/json' }
            });

            if (!res.ok) throw new Error(`HTTP ${res.status}`);

            const data = await res.json();
            _camRoles = data.roles || [];
            _camRenderRoles();
        } catch (err) {
            console.error('Load roles error:', err);
            document.getElementById('camRolesGrid').innerHTML = `
            <div class="cam-loading" style="grid-column:1/-1; color:#ef4444;">
                Impossible de charger les rôles. Vous pouvez quand même créer l'utilisateur.
            </div>`;
        }
    }

    function _camRenderRoles() {
        const grid = document.getElementById('camRolesGrid');
        if (!_camRoles.length) {
            grid.innerHTML = '<div class="cam-loading" style="grid-column:1/-1;">Aucun rôle disponible</div>';
            return;
        }
        grid.innerHTML = _camRoles.map(role => `
            <div class="cam-role-card" onclick="camToggleRole(this, ${role.id})">
                <input type="checkbox" name="roles" value="${role.id}">
                <div class="cam-role-name">${_esc(role.name_fr || role.name)}</div>
                <div class="cam-role-key">${_esc(role.name)}</div>
                ${role.permissions && role.permissions.length ? `
                <div class="cam-role-perms">
                    ${role.permissions.slice(0,3).map(p => `<span class="cam-perm-chip">${_esc(p.name)}</span>`).join('')}
                    ${role.permissions.length > 3 ? `<span class="cam-perm-chip">+${role.permissions.length - 3}</span>` : ''}
                </div>` : ''}
            </div>
        `).join('');
    }

    window.camToggleRole = function(card, roleId) {
        card.classList.toggle('selected');
        const cb = card.querySelector('input[type=checkbox]');
        cb.checked = card.classList.contains('selected');
        _camUpdatePermsPreview();
    };

    function _camUpdatePermsPreview() {
        const selected = document.querySelectorAll('#camRolesGrid input[type=checkbox]:checked');
        const permsSet = new Set();
        selected.forEach(cb => {
            const role = _camRoles.find(r => r.id == cb.value);
            if (role && role.permissions) role.permissions.forEach(p => permsSet.add(p.name));
        });

        const preview = document.getElementById('camPermsPreview');
        const chips   = document.getElementById('camPermChips');

        if (permsSet.size > 0) {
            preview.style.display = 'block';
            chips.innerHTML = Array.from(permsSet).map(p => `<span class="cam-chip-blue">${_esc(p)}</span>`).join('');
        } else {
            preview.style.display = 'none';
        }
    }

    /* ── Submit ───────────────────────────────────────────────── */
    window.camSubmit = async function() {
         const form    = document.getElementById('camForm');
    const fd      = new FormData(form);
    const url     = form.dataset.storeUrl;
    const roles   = Array.from(form.querySelectorAll('input[name=roles]:checked')).map(r => Number(r.value));
    const submitBtn = document.getElementById('camSubmitBtn');

    // IMPORTANT: Remove is_admin from payload - it's not needed
const payload = {
    nom:                   fd.get('nom'),
    prenom:                fd.get('prenom'),
    email:                 fd.get('email'),
    cin:                   fd.get('cin'),
    telephone:             fd.get('telephone') || null,
    id_direction:          fd.get('id_direction') || null,
    roles:                 roles,
    actif:                 true,
    can_manage_signature:  document.getElementById('cam_can_manage_signature')?.checked ? 1 : 0,
    // REMOVE email_verified_at — let the user verify via the email link
};

        // ✅ DEBUG: Log payload to console
        console.log('=== ADMIN CREATION PAYLOAD ===');
        console.log('Payload:', payload);
        console.log('Form values:');
        console.log('  nom:', fd.get('nom'), '(required)');
        console.log('  prenom:', fd.get('prenom'), '(required)');
        console.log('  email:', fd.get('email'), '(required, must be unique)');
        console.log('  cin:', fd.get('cin'), '(required, must be unique)');
        console.log('  telephone:', fd.get('telephone'), '(optional)');
        console.log('  id_direction:', fd.get('id_direction'), '(optional)');

        console.log('  roles selected:', roles.length, 'role(s)');
        console.log('URL:', url);
        console.log('============================');

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="cam-spin" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Création en cours...';

        try {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (!csrfMeta) throw new Error('CSRF token manquant. Rechargez la page.');

            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfMeta.content,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify(payload),
            });

            // Catch HTML responses (e.g login redirect, 419, 404 HTML)
            const contentType = res.headers.get('content-type') || '';
            if (!contentType.includes('application/json')) {
                const bodyText = await res.text();
                console.error('Non-JSON response:', res.status, bodyText.substring(0, 300));
                if (res.status === 419) throw new Error('Session expirée (CSRF). Rechargez la page et réessayez.');
                if (res.status === 401 || res.status === 403) throw new Error('Non autorisé (HTTP ' + res.status + '). Vérifiez que vous êtes Super Admin.');
                if (res.status === 404) throw new Error('Route introuvable (404). Vérifiez admin.users.store dans routes/web.php.');
                throw new Error('Le serveur a renvoyé du HTML au lieu de JSON (HTTP ' + res.status + '). Vérifiez les logs Laravel.');
            }

            const result = await res.json();

            if (result.success) {
                _camNotify('✓ ' + (result.message || 'Administrateur créé avec succès'), 'success');
                closeCamModal();
                setTimeout(() => location.reload(), 1500);
            } else {
                // ✅ Enhanced error logging
                console.error('=== VALIDATION ERROR RESPONSE ===');
                console.error('Full response:', result);

                // Laravel validation errors
                if (result.errors) {
                    console.error('Validation errors by field:');
                    Object.entries(result.errors).forEach(([field, messages]) => {
                        console.error(`  ${field}:`, messages);
                    });

                    // Build detailed error message
                    let errorDetails = 'Erreurs de validation:\n\n';
                    Object.entries(result.errors).forEach(([field, messages]) => {
                        errorDetails += `• ${field}: ${messages.join(', ')}\n`;
                    });

                    console.log(errorDetails); // Log for user to read

                    // Show first few errors in notification
                    const allErrors = Object.values(result.errors).flat();
                    const msgs = allErrors.slice(0, 3).join(' | ');
                    const suffix = allErrors.length > 3 ? ` (+${allErrors.length - 3} more)` : '';
                    _camNotify('✗ ' + msgs + suffix, 'error');

                    // Also show in alert for visibility
                    alert(errorDetails);
                } else {
                    console.error('Error message:', result.message || 'Erreur inconnue');
                    _camNotify('✗ ' + (result.message || 'Erreur inconnue'), 'error');
                }
                console.error('================================');

                submitBtn.disabled = false;
                submitBtn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Créer l\'administrateur';
            }
        } catch (err) {
            console.error('createAdmin error:', err);
            _camNotify('✗ ' + err.message, 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Créer l\'administrateur';
        }
    };

    /* ── Helpers ──────────────────────────────────────────────── */
    function _esc(str) {
        return String(str).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
    }

    function _camNotify(message, type) {
        let container = document.getElementById('ug-notify');
        if (!container) {
            container = document.createElement('div');
            container.id = 'ug-notify';
            container.style.cssText = 'position:fixed;bottom:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:8px;';
            document.body.appendChild(container);
        }
        const notif = document.createElement('div');
        const isSuccess = type === 'success';
        notif.style.cssText = `
            padding:12px 18px; border-radius:10px; font-size:13px; font-weight:500;
            max-width:360px; animation:camFadeIn .2s ease;
            background:${isSuccess ? 'rgba(74,222,128,.12)' : 'rgba(248,113,113,.12)'};
            border:1px solid ${isSuccess ? 'rgba(74,222,128,.3)' : 'rgba(248,113,113,.3)'};
            color:${isSuccess ? 'var(--green,#4ade80)' : 'var(--red,#f87171)'};
            box-shadow:0 8px 24px rgba(0,0,0,.4);
        `;
        notif.textContent = message;
        container.appendChild(notif);
        setTimeout(() => notif.remove(), 5000);
    }
})();
</script>
