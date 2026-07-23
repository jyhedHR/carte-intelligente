{{-- resources/views/backoffice/allUsers/modals/archive-user-modal.blade.php --}}
{{-- Archive/Delete User Modal - Confirmation Dialog (redesigned to match create-admin modal) --}}

<div id="archiveUserModal" class="aum-overlay" onclick="closeArchiveModal(event)">
    <div class="aum-modal" onclick="event.stopPropagation()">

        {{-- Header --}}
        <div class="aum-header">
            <div class="aum-header-left">
                <div class="aum-icon-wrap">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </div>
                <div>
                    <div class="aum-title">Archiver l'utilisateur</div>
                    <div class="aum-sub">Action irréversible — veuillez confirmer</div>
                </div>
            </div>
            <button class="aum-close" onclick="closeArchiveModal()" aria-label="Fermer">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="aum-body">

            {{-- User Information Card --}}
            <div class="aum-section-label">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 1 0-16 0"/></svg>
                Utilisateur à archiver
            </div>
            <div class="aum-user-card">
                <div class="aum-user-avatar">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 1 0-16 0"/></svg>
                </div>
                <div class="aum-user-info">
                    <div class="aum-user-name" id="archiveUserName">Chargement...</div>
                    <div class="aum-user-email" id="archiveUserEmail">--</div>
                    <div class="aum-user-tags">
                        <span class="aum-tag"><strong>ID</strong> <span id="archiveUserId">--</span></span>
                        <span class="aum-tag"><strong>Rôle</strong> <span id="archiveUserRole">--</span></span>
                    </div>
                </div>
            </div>

            {{-- Impact Warning --}}
            <div class="aum-notice">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                <div>
                    <div class="aum-notice-title">Conséquences de cette action</div>
                    <ul class="aum-notice-list">
                        <li>L'utilisateur ne pourra plus se connecter</li>
                        <li>Toutes les sessions actives seront terminées</li>
                        <li>Les données seront conservées à titre d'archive</li>
                        <li>Cette action ne peut pas être annulée</li>
                    </ul>
                </div>
            </div>

            {{-- Reason & Comment --}}
            <div class="aum-section-label" style="margin-top: 20px;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                Détails (optionnel)
            </div>
            <div class="aum-field">
                <label class="aum-label">Raison de l'archivage</label>
                <select id="archiveReason" class="aum-input aum-select">
                    <option value="">— Sélectionner une raison —</option>
                    <option value="depart">Départ de l'entreprise</option>
                    <option value="inactivite">Inactivité prolongée</option>
                    <option value="changement_role">Changement de rôle</option>
                    <option value="demande">Demande de l'utilisateur</option>
                    <option value="autre">Autre</option>
                </select>
            </div>
            <div class="aum-field">
                <label class="aum-label">Commentaires supplémentaires</label>
                <textarea
                    id="archiveComment"
                    class="aum-input"
                    placeholder="Entrez des détails supplémentaires..."
                    style="height: 70px; resize: none;"
                ></textarea>
            </div>

            {{-- Confirmation Checkbox --}}
            <label class="aum-confirm">
                <input type="checkbox" id="confirmArchive" onchange="updateArchiveButton()">
                <span>
                    Je confirme vouloir archiver <strong id="confirmUserName">cet utilisateur</strong> définitivement
                </span>
            </label>
        </div>

        {{-- Footer --}}
        <div class="aum-footer">
            <button type="button" class="aum-btn aum-btn-ghost" onclick="closeArchiveModal()">
                Non, annuler
            </button>
            <button
                type="button"
                class="aum-btn aum-btn-danger"
                id="confirmArchiveBtn"
                onclick="archiveUserConfirm()"
                disabled
            >
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                Archiver définitivement
            </button>
        </div>
    </div>
</div>

<style>
/* ── Overlay ───────────────────────────────────────────────────────── */
.aum-overlay {
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
    animation: aumFadeIn .3s ease;
}

.aum-overlay.open {
    display: flex;
}

/* ── Modal ─────────────────────────────────────────────────────────── */
.aum-modal {
    background: white;
    border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    width: 90%;
    max-width: 520px;
    display: flex;
    flex-direction: column;
    max-height: 90vh;
    overflow: hidden;
    animation: aumFadeIn .2s ease;
}

/* ── Header ────────────────────────────────────────────────────────── */
.aum-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 24px;
    border-bottom: 1px solid #e5e7eb;
}

.aum-header-left {
    display: flex;
    align-items: center;
    gap: 16px;
    flex: 1;
}

.aum-icon-wrap {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #f87171, #dc2626);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.aum-title {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
}

.aum-sub {
    font-size: 13px;
    color: #6b7280;
}

.aum-close {
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

.aum-close:hover {
    background: #f3f4f6;
    color: #111827;
}

/* ── Body ──────────────────────────────────────────────────────────── */
.aum-body {
    flex: 1;
    overflow-y: auto;
    padding: 24px;
}

.aum-section-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 12px;
}

/* ── User Card ─────────────────────────────────────────────────────── */
.aum-user-card {
    display: flex;
    gap: 14px;
    align-items: flex-start;
    background: #fafafa;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 18px;
}

.aum-user-avatar {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: linear-gradient(135deg, #fca5a5, #ef4444);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.aum-user-info {
    flex: 1;
    min-width: 0;
}

.aum-user-name {
    font-weight: 700;
    font-size: 15px;
    color: #111827;
    margin-bottom: 2px;
}

.aum-user-email {
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 10px;
    word-break: break-all;
}

.aum-user-tags {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.aum-tag {
    font-size: 11px;
    font-weight: 500;
    color: #374151;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    padding: 4px 8px;
}

.aum-tag strong {
    color: #6b7280;
    font-weight: 600;
    margin-right: 4px;
}

/* ── Notice ────────────────────────────────────────────────────────── */
.aum-notice {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 14px;
    background: #fef3c7;
    border: 1px solid #fde68a;
    border-radius: 8px;
    color: #92400e;
    margin-bottom: 8px;
}

.aum-notice svg {
    flex-shrink: 0;
    margin-top: 2px;
}

.aum-notice-title {
    font-size: 13px;
    font-weight: 700;
    margin-bottom: 6px;
}

.aum-notice-list {
    margin: 0;
    padding-left: 18px;
    font-size: 12.5px;
    line-height: 1.6;
}

/* ── Form Fields ───────────────────────────────────────────────────── */
.aum-field {
    margin-bottom: 14px;
}

.aum-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 6px;
}

.aum-input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    font-family: inherit;
    color: #111827;
    background: white;
    transition: all .2s ease;
}

.aum-input:focus {
    outline: none;
    border-color: #dc2626;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
}

.aum-input.aum-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236B7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3E%3C/svg%3E");
    background-position: right 8px center;
    background-repeat: no-repeat;
    background-size: 20px;
    padding-right: 32px;
}

/* ── Confirmation ──────────────────────────────────────────────────── */
.aum-confirm {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    cursor: pointer;
    font-size: 13px;
    color: #7f1d1d;
    background: #fee2e2;
    border: 1px solid #fecaca;
    border-radius: 8px;
    padding: 12px 14px;
    margin-top: 4px;
}

.aum-confirm input[type=checkbox] {
    width: 17px;
    height: 17px;
    margin-top: 1px;
    cursor: pointer;
    accent-color: #dc2626;
    flex-shrink: 0;
}

.aum-confirm strong {
    font-weight: 700;
}

/* ── Footer ────────────────────────────────────────────────────────── */
.aum-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
    padding: 16px 24px;
    border-top: 1px solid #e5e7eb;
    background: #fafafa;
}

.aum-btn {
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

.aum-btn-ghost {
    background: white;
    color: #6b7280;
    border: 1px solid #d1d5db;
}

.aum-btn-ghost:hover {
    background: #f9fafb;
    color: #111827;
}

.aum-btn-danger {
    background: linear-gradient(135deg, #f87171, #dc2626);
    color: white;
}

.aum-btn-danger:hover:not(:disabled) {
    background: linear-gradient(135deg, #fca5a5, #ef4444);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
}

.aum-btn-danger:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* ── Animations ────────────────────────────────────────────────────── */
@keyframes aumFadeIn {
    from { opacity: 0; }
    to   { opacity: 1; }
}

@keyframes aumSpin {
    from { transform: rotate(0deg); }
    to   { transform: rotate(360deg); }
}

.aum-spin {
    animation: aumSpin 1s linear infinite;
}

@media (max-width: 640px) {
    .aum-modal {
        width: 95%;
        max-height: 95vh;
    }
    .aum-header, .aum-body, .aum-footer {
        padding: 16px;
    }
}
</style>

<script>
let archiveUserData = null;

function openArchiveModal(userId, userName, userEmail, userRole) {
    archiveUserData = { userId, userName, userEmail, userRole };

    document.getElementById('archiveUserModal').classList.add('open');
    document.getElementById('archiveUserName').textContent = userName;
    document.getElementById('archiveUserEmail').textContent = userEmail;
    document.getElementById('archiveUserId').textContent = userId;
    document.getElementById('archiveUserRole').textContent = userRole;
    document.getElementById('confirmUserName').textContent = userName;

    // Reset form
    document.getElementById('confirmArchive').checked = false;
    document.getElementById('archiveReason').value = '';
    document.getElementById('archiveComment').value = '';
    updateArchiveButton();
}

function closeArchiveModal(event) {
    if (event && event.target.id !== 'archiveUserModal') return;
    document.getElementById('archiveUserModal').classList.remove('open');
    archiveUserData = null;
}

function updateArchiveButton() {
    const isConfirmed = document.getElementById('confirmArchive').checked;
    const btn = document.getElementById('confirmArchiveBtn');
    btn.disabled = !isConfirmed;
}

async function archiveUserConfirm() {
    if (!archiveUserData) return;

    const btn = document.getElementById('confirmArchiveBtn');
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<svg class="aum-spin" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Archivage en cours...';

    try {
        const response = await fetch(`/admin/users/${archiveUserData.userId}/archive`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                reason: document.getElementById('archiveReason').value,
                comment: document.getElementById('archiveComment').value
            })
        });

        const result = await response.json();

        if (result.success) {
            showNotification('✓ ' + (result.message || 'Utilisateur archivé avec succès'), 'success');
            closeArchiveModal();
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification('✗ Erreur: ' + (result.message || 'Erreur inconnue'), 'error');
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('✗ Erreur: ' + error.message, 'error');
        btn.disabled = false;
        btn.innerHTML = originalHtml;
    }
}

function showNotification(message, type) {
    const notifyDiv = document.getElementById('ug-notify') || createNotifyContainer();
    const notif = document.createElement('div');
    notif.className = `ug-notif ${type}`;
    notif.textContent = message;
    notifyDiv.appendChild(notif);

    setTimeout(() => notif.remove(), 5000);
}

function createNotifyContainer() {
    const div = document.createElement('div');
    div.id = 'ug-notify';
    document.body.appendChild(div);
    return div;
}
</script>