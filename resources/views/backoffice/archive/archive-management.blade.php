@extends('shared.layouts.backoffice')

@section('page-title', 'Gestion des Archives')

@section('content')
<style>
    /* Modal Styles - Fixed */
.archive-confirm-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    z-index: 10001;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(4px);
}

.archive-confirm-modal.open {
    display: flex;
}

.archive-confirm-content {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    max-width: 500px;
    width: 90%;
    padding: 0;
    animation: archiveSlideUp 0.3s ease;
    position: relative;
}

.archive-modal-close {
    position: absolute;
    right: 1rem;
    top: 1rem;
    background: none;
    border: none;
    color: var(--text3);
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    transition: all 0.2s;
}

.archive-modal-close:hover {
    background: var(--bg3);
    color: var(--text);
}

@keyframes archiveSlideUp {
    from { opacity: 0; transform: translateY(20px) scale(0.95); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

.archive-confirm-header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--border);
    background: var(--bg3);
    border-radius: var(--radius) var(--radius) 0 0;
    position: relative;
}

.archive-confirm-header h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text);
    margin: 0;
}

.archive-confirm-body {
    padding: 1.5rem;
}

.archive-confirm-warning {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 1rem;
    background: rgba(248,113,113,0.1);
    border: 1px solid rgba(248,113,113,0.2);
    border-radius: var(--radius-sm);
    margin-bottom: 0;
}

.archive-confirm-warning .icon {
    font-size: 1.5rem;
    flex-shrink: 0;
}

.archive-confirm-warning .text {
    font-size: 0.85rem;
    color: var(--text2);
}

.archive-confirm-warning .text strong {
    color: #f87171;
}

.archive-confirm-input {
    width: 100%;
    padding: 0.75rem 1rem;
    background: var(--bg3);
    border: 2px solid var(--border);
    border-radius: var(--radius-sm);
    color: var(--text);
    font-size: 1rem;
    font-family: var(--font-mono);
    outline: none;
    text-transform: uppercase;
    transition: border-color 0.2s;
    margin-top: 0.5rem;
}

.archive-confirm-input:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 3px rgba(201,168,76,0.15);
}

.archive-confirm-input::placeholder {
    text-transform: none;
    color: var(--text3);
    font-size: 0.85rem;
}

.archive-confirm-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--border);
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
    background: var(--bg3);
    border-radius: 0 0 var(--radius) var(--radius);
}

/* Make sure the modal stays on top and input is focusable */
.archive-confirm-modal.open .archive-confirm-input {
    z-index: 10002;
}
    .archive-page {
        padding: 1.5rem 2rem;
    }

    .archive-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .archive-title h1 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 0.25rem;
    }

    .archive-title p {
        color: var(--text2);
        font-size: 0.85rem;
    }

    /* Stats Cards */
    .archive-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .archive-stat-card {
        background: var(--bg2);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 1rem 1.25rem;
    }

    .archive-stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text);
    }

    .archive-stat-label {
        font-size: 0.7rem;
        color: var(--text2);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 0.25rem;
    }

    /* Filter Bar */
    .archive-filter-bar {
        background: var(--bg2);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: flex-end;
    }

    .archive-filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
        min-width: 140px;
    }

    .archive-filter-group label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text3);
    }

    .archive-filter-select,
    .archive-filter-input {
        padding: 0.5rem 0.75rem;
        background: var(--bg3);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        color: var(--text);
        font-size: 0.8rem;
        cursor: pointer;
    }

    .archive-filter-select:focus,
    .archive-filter-input:focus {
        outline: none;
        border-color: var(--gold);
    }

    .archive-search-group {
        flex: 1;
        min-width: 200px;
    }

    .archive-search-input {
        width: 100%;
        padding: 0.5rem 0.75rem;
        background: var(--bg3);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        color: var(--text);
        font-size: 0.8rem;
    }

    .archive-search-input:focus {
        outline: none;
        border-color: var(--gold);
    }

    .archive-filter-actions {
        display: flex;
        gap: 0.5rem;
    }

    /* Table */
    .archive-table-wrapper {
        background: var(--bg2);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow-x: auto;
        margin-bottom: 1.5rem;
    }

    .archive-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.8rem;
    }

    .archive-table th {
        padding: 0.9rem 1rem;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text3);
        border-bottom: 1px solid var(--border);
        background: var(--bg3);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .archive-table td {
        padding: 0.8rem 1rem;
        color: var(--text2);
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }

    .archive-table tr:hover td {
        background: var(--bg3);
    }

    .archive-table .checkbox-cell {
        width: 40px;
        text-align: center;
    }

    .archive-table input[type="checkbox"] {
        width: 16px;
        height: 16px;
        cursor: pointer;
        accent-color: var(--gold);
    }

    /* Status Badges */
    .archive-status {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .archive-status.soumise { background: rgba(96,165,250,0.15); color: #60a5fa; }
    .archive-status.en_cours { background: rgba(251,191,36,0.15); color: #fbbf24; }
    .archive-status.en_attente { background: rgba(251,146,60,0.15); color: #fb923c; }
    .archive-status.validee { background: rgba(74,222,128,0.15); color: #4ade80; }
    .archive-status.rejetee { background: rgba(248,113,113,0.15); color: #f87171; }
    .archive-status.cloturee { background: rgba(148,163,184,0.15); color: #94a3b8; }

    /* Action Buttons */
    .archive-actions {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
    }

    .archive-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 4px 10px;
        border-radius: var(--radius-sm);
        font-size: 0.7rem;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid var(--border);
        background: var(--bg3);
        color: var(--text2);
        transition: all 0.15s;
        text-decoration: none;
        gap: 4px;
    }

    .archive-action-btn:hover {
        background: var(--bg4);
        color: var(--text);
    }

    .archive-action-btn.restore {
        background: rgba(74,222,128,0.1);
        border-color: rgba(74,222,128,0.3);
        color: #4ade80;
    }

    .archive-action-btn.restore:hover {
        background: rgba(74,222,128,0.2);
    }

    .archive-action-btn.delete {
        background: rgba(248,113,113,0.1);
        border-color: rgba(248,113,113,0.3);
        color: #f87171;
    }

    .archive-action-btn.delete:hover {
        background: rgba(248,113,113,0.2);
    }

    .archive-action-btn.view {
        background: rgba(96,165,250,0.1);
        border-color: rgba(96,165,250,0.3);
        color: #60a5fa;
    }

    .archive-action-btn.view:hover {
        background: rgba(96,165,250,0.2);
    }

    /* Bulk Actions */
    .archive-bulk-actions {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        padding: 0.5rem 0;
        flex-wrap: wrap;
    }

    .archive-bulk-actions .selected-count {
        font-size: 0.8rem;
        color: var(--text2);
    }

    .btn-bulk {
        padding: 0.4rem 1rem;
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.15s;
    }

    .btn-bulk-restore {
        background: rgba(74,222,128,0.15);
        color: #4ade80;
    }

    .btn-bulk-restore:hover {
        background: rgba(74,222,128,0.25);
    }

    .btn-bulk-delete {
        background: rgba(248,113,113,0.15);
        color: #f87171;
    }

    .btn-bulk-delete:hover {
        background: rgba(248,113,113,0.25);
    }

    .btn-bulk:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    /* Pagination */
    .archive-pagination {
        display: flex;
        justify-content: center;
        margin-top: 1.5rem;
    }

    /* Empty State */
    .archive-empty {
        text-align: center;
        padding: 3rem;
        color: var(--text3);
    }

    /* Modal */
    .archive-confirm-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.7);
        z-index: 10001;
        align-items: center;
        justify-content: center;
    }

    .archive-confirm-modal.open {
        display: flex;
    }

    .archive-confirm-content {
        background: var(--bg2);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        max-width: 500px;
        width: 90%;
        padding: 0;
        animation: slideUp 0.3s ease;
    }

    .archive-confirm-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border);
        background: var(--bg3);
        border-radius: var(--radius) var(--radius) 0 0;
    }

    .archive-confirm-header h3 {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text);
        margin: 0;
    }

    .archive-confirm-body {
        padding: 1.5rem;
    }

    .archive-confirm-warning {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 1rem;
        background: rgba(248,113,113,0.1);
        border: 1px solid rgba(248,113,113,0.2);
        border-radius: var(--radius-sm);
        margin-bottom: 1.5rem;
    }

    .archive-confirm-warning .icon {
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .archive-confirm-warning .text {
        font-size: 0.85rem;
        color: var(--text2);
    }

    .archive-confirm-warning .text strong {
        color: #f87171;
    }

    .archive-confirm-input {
        width: 100%;
        padding: 0.75rem 1rem;
        background: var(--bg3);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        color: var(--text);
        font-size: 0.9rem;
        font-family: var(--font-mono);
        outline: none;
        text-transform: uppercase;
    }

    .archive-confirm-input:focus {
        border-color: var(--gold);
    }

    .archive-confirm-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--border);
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Toast */
    .archive-toast {
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        z-index: 99999;
        animation: slideInRight 0.3s ease;
        background: var(--bg2);
        border: 1px solid var(--border);
        color: var(--text);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .archive-toast.success { border-color: #4ade80; color: #4ade80; }
    .archive-toast.error { border-color: #f87171; color: #f87171; }
    .archive-toast.info { border-color: #60a5fa; color: #60a5fa; }

    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    @media (max-width: 768px) {
        .archive-page { padding: 1rem; }
        .archive-filter-bar { flex-direction: column; align-items: stretch; }
        .archive-filter-group { width: 100%; }
    }
</style>

<div class="archive-page">
    <div class="archive-header">
        <div class="archive-title">
            <h1>📦 Gestion des Archives</h1>
            <p>Consultez, restaurez ou supprimez définitivement les soumissions archivées</p>
        </div>
        <div>
            <button class="archive-action-btn" onclick="archiveRefresh()" style="padding: 6px 14px;">
                🔄 Actualiser
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="archive-stats-grid" id="archiveStatsGrid">
        <div class="archive-stat-card">
            <div class="archive-stat-value" id="statTotal">0</div>
            <div class="archive-stat-label">Total archivé</div>
        </div>
        <div class="archive-stat-card">
            <div class="archive-stat-value" id="statForms">0</div>
            <div class="archive-stat-label">Formulaires différents</div>
        </div>
        <div class="archive-stat-card">
            <div class="archive-stat-value" id="statStatuses">0</div>
            <div class="archive-stat-label">Statuts différents</div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="archive-filter-bar">
        <div class="archive-filter-group">
            <label>Statut</label>
            <select class="archive-filter-select" id="archiveFilterStatus">
                <option value="">Tous</option>
                <option value="soumise">Soumise</option>
                <option value="en_cours">En cours</option>
                <option value="en_attente">En attente</option>
                <option value="validee">Validée</option>
                <option value="rejetee">Rejetée</option>
                <option value="cloturee">Clôturée</option>
            </select>
        </div>
        <div class="archive-filter-group">
            <label>Date début</label>
            <input type="date" class="archive-filter-input" id="archiveFilterDateFrom">
        </div>
        <div class="archive-filter-group">
            <label>Date fin</label>
            <input type="date" class="archive-filter-input" id="archiveFilterDateTo">
        </div>
        <div class="archive-search-group">
            <label>Rechercher</label>
            <input type="text" class="archive-search-input" id="archiveFilterSearch" placeholder="Titre, demandeur, statut...">
        </div>
        <div class="archive-filter-actions">
            <button class="archive-action-btn" onclick="archiveResetFilters()">Reset</button>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="archive-bulk-actions">
        <span class="selected-count" id="archiveSelectedCount">0 sélectionné(s)</span>
        <button class="btn-bulk btn-bulk-restore" id="bulkRestoreBtn" disabled onclick="archiveBulkRestore()">
            🔄 Restaurer sélection
        </button>
        <button class="btn-bulk btn-bulk-delete" id="bulkDeleteBtn" disabled onclick="archiveBulkDelete()">
            🗑 Supprimer sélection
        </button>
        <button class="archive-action-btn" onclick="archiveToggleAll()" style="font-size:0.7rem;padding:2px 10px;">
            Tout sélectionner
        </button>
    </div>

    <!-- Main Table -->
    <div class="archive-table-wrapper">
        <table class="archive-table">
            <thead>
                <tr>
                    <th class="checkbox-cell">
                        <input type="checkbox" id="archiveSelectAll" onchange="archiveToggleAll()">
                    </th>
                    <th>Formulaire</th>
                    <th>Soumis par</th>
                    <th>Statut</th>
                    <th>Archivé le</th>
                    <th>Raison</th>
                    <th style="width:180px;">Actions</th>
                </tr>
            </thead>
            <tbody id="archiveTableBody">
                <tr>
                    <td colspan="7" class="archive-empty">Chargement des archives...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="archive-pagination" id="archivePagination"></div>
</div>
<!-- Confirmation Modal -->
<div class="archive-confirm-modal" id="archiveConfirmModal">
    <div class="archive-confirm-content">
        <div class="archive-confirm-header">
            <h3 id="confirmModalTitle">⚠️ Confirmation</h3>
            <button class="archive-modal-close" onclick="archiveCloseConfirm()">×</button>
        </div>
        <div class="archive-confirm-body">
            <div class="archive-confirm-warning">
                <div class="icon">⚠️</div>
                <div class="text" id="confirmModalMessage">
                    Cette action est <strong>irréversible</strong>. Veuillez confirmer en tapant <strong>SUPPRIMER</strong> ci-dessous.
                </div>
            </div>
            <div style="margin-top:1rem;">
                <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text2);margin-bottom:0.5rem;">
                    Tapez <strong style="color:var(--red);">SUPPRIMER</strong> pour confirmer
                </label>
                <input type="text" class="archive-confirm-input" id="confirmModalInput"
                       placeholder="Tapez SUPPRIMER ici..."
                       oninput="archiveCheckConfirm()"
                       autocomplete="off"
                       autocorrect="off"
                       autocapitalize="characters"
                       spellcheck="false">
            </div>
        </div>
        <div class="archive-confirm-footer">
            <button class="archive-action-btn" onclick="archiveCloseConfirm()" style="padding:8px 16px;">Annuler</button>
            <button class="archive-action-btn delete" id="confirmModalBtn" disabled onclick="archiveExecuteConfirm()" style="padding:8px 16px;">
                🗑 Confirmer la suppression
            </button>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="archiveToastContainer"></div>

<script>
// ── State ──────────────────────────────────────────────────────────────────
let archiveSelectedIds = [];
let archiveConfirmAction = null;
let archiveConfirmData = null;
let archiveCurrentPage = 1;
let archiveTotalPages = 1;

// ── Load Data ──────────────────────────────────────────────────────────────
function archiveLoadData(page = 1) {
    const filters = {
        search: document.getElementById('archiveFilterSearch').value,
        status: document.getElementById('archiveFilterStatus').value,
        date_from: document.getElementById('archiveFilterDateFrom').value,
        date_to: document.getElementById('archiveFilterDateTo').value,
        page: page
    };

    const params = new URLSearchParams(filters);

    fetch(`{{ route('admin.archive.data') }}?${params.toString()}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            archiveRenderTable(data.data.data);
            archiveRenderPagination(data.data);
            archiveUpdateStats(data.stats);
            archiveSelectedIds = [];
            archiveUpdateSelectedCount();
        }
    })
    .catch(error => {
        console.error('Error loading archives:', error);
        archiveShowToast('Erreur lors du chargement des archives', 'error');
    });
}

// ── Render Table ───────────────────────────────────────────────────────────
function archiveRenderTable(records) {
    const tbody = document.getElementById('archiveTableBody');

    if (!records || records.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="archive-empty">📭 Aucune archive trouvée</td></tr>`;
        return;
    }

    tbody.innerHTML = records.map(record => {
        const statusClass = record.id_statut || 'soumise';
        const statusLabel = record.id_statut ? record.id_statut.replace('_', ' ') : 'Soumise';
        const isSelected = archiveSelectedIds.includes(record.id);

        return `
        <tr class="archive-row" data-id="${record.id}">
            <td class="checkbox-cell">
                <input type="checkbox" class="archive-row-checkbox" data-id="${record.id}" ${isSelected ? 'checked' : ''} onchange="archiveToggleRow(${record.id})">
            </td>
            <td>
                <div style="font-weight:600;color:var(--text);font-size:0.85rem;">${escapeHtml(record.formulaire_titre || '—')}</div>
                <div style="font-size:0.7rem;color:var(--text3);">${escapeHtml(record.formulaire_slug || '')}</div>
            </td>
            <td>
                ${record.soumis_par_nom ? `${escapeHtml(record.soumis_par_prenom)} ${escapeHtml(record.soumis_par_nom)}` : '—'}
                <div style="font-size:0.65rem;color:var(--text3);">ID: ${record.soumis_par || '—'}</div>
            </td>
            <td>
                <span class="archive-status ${statusClass}">${escapeHtml(statusLabel)}</span>
            </td>
            <td style="font-size:0.8rem;color:var(--text3);">
                ${record.archived_at ? new Date(record.archived_at).toLocaleString('fr-FR') : '—'}
                ${record.archived_by_nom ? `<div style="font-size:0.65rem;">par ${escapeHtml(record.archived_by_prenom)} ${escapeHtml(record.archived_by_nom)}</div>` : ''}
            </td>
            <td style="font-size:0.75rem;color:var(--text3);">${escapeHtml(record.archived_reason || 'Form deleted')}</td>
            <td>
                <div class="archive-actions">
                    <button class="archive-action-btn view" onclick="archiveViewDetail(${record.id})" title="Voir détails">👁</button>
                    <button class="archive-action-btn restore" onclick="archiveRestoreSingle(${record.id})" title="Restaurer">🔄</button>
                    <button class="archive-action-btn delete" onclick="archiveDeleteSingle(${record.id})" title="Supprimer définitivement">🗑</button>
                </div>
            </td>
        </tr>`;
    }).join('');

    // Re-apply selected states
    document.querySelectorAll('.archive-row-checkbox').forEach(cb => {
        const id = parseInt(cb.dataset.id);
        cb.checked = archiveSelectedIds.includes(id);
    });
}

// ── Render Pagination ──────────────────────────────────────────────────────
function archiveRenderPagination(data) {
    const container = document.getElementById('archivePagination');

    if (data.last_page <= 1) {
        container.innerHTML = '';
        return;
    }

    let html = '<div style="display:flex;gap:0.5rem;flex-wrap:wrap;justify-content:center;">';

    // Previous
    if (data.prev_page_url) {
        html += `<button class="archive-action-btn" onclick="archiveLoadPage(${data.current_page - 1})">‹ Précédent</button>`;
    }

    // Page numbers
    for (let i = 1; i <= data.last_page; i++) {
        const active = i === data.current_page ? 'style="background:var(--gold);color:#111;border-color:var(--gold);"' : '';
        html += `<button class="archive-action-btn" ${active} onclick="archiveLoadPage(${i})">${i}</button>`;
    }

    // Next
    if (data.next_page_url) {
        html += `<button class="archive-action-btn" onclick="archiveLoadPage(${data.current_page + 1})">Suivant ›</button>`;
    }

    html += '</div>';
    container.innerHTML = html;
    archiveCurrentPage = data.current_page;
    archiveTotalPages = data.last_page;
}

// ── Update Stats ───────────────────────────────────────────────────────────
function archiveUpdateStats(stats) {
    if (!stats) return;

    document.getElementById('statTotal').textContent = stats.total_archived || 0;

    if (stats.total_by_form) {
        document.getElementById('statForms').textContent = stats.total_by_form.length || 0;
    }

    if (stats.status_distribution) {
        document.getElementById('statStatuses').textContent = stats.status_distribution.length || 0;
    }
}

// ── Load Page ──────────────────────────────────────────────────────────────
function archiveLoadPage(page) {
    archiveLoadData(page);
}

// ── Refresh ────────────────────────────────────────────────────────────────
function archiveRefresh() {
    archiveLoadData(archiveCurrentPage);
    archiveShowToast('🔄 Archives actualisées', 'info');
}

// ── Filters ────────────────────────────────────────────────────────────────
function archiveResetFilters() {
    document.getElementById('archiveFilterStatus').value = '';
    document.getElementById('archiveFilterDateFrom').value = '';
    document.getElementById('archiveFilterDateTo').value = '';
    document.getElementById('archiveFilterSearch').value = '';
    archiveLoadData(1);
}

// ── Selection ──────────────────────────────────────────────────────────────
function archiveToggleRow(id) {
    const index = archiveSelectedIds.indexOf(id);
    if (index > -1) {
        archiveSelectedIds.splice(index, 1);
    } else {
        archiveSelectedIds.push(id);
    }
    archiveUpdateSelectedCount();
}

function archiveToggleAll() {
    const selectAll = document.getElementById('archiveSelectAll');
    const checkboxes = document.querySelectorAll('.archive-row-checkbox');

    if (selectAll.checked) {
        archiveSelectedIds = [];
        checkboxes.forEach(cb => {
            const id = parseInt(cb.dataset.id);
            if (!archiveSelectedIds.includes(id)) {
                archiveSelectedIds.push(id);
            }
            cb.checked = true;
        });
    } else {
        archiveSelectedIds = [];
        checkboxes.forEach(cb => cb.checked = false);
    }
    archiveUpdateSelectedCount();
}

function archiveUpdateSelectedCount() {
    const count = archiveSelectedIds.length;
    document.getElementById('archiveSelectedCount').textContent = `${count} sélectionné(s)`;

    document.getElementById('bulkRestoreBtn').disabled = count === 0;
    document.getElementById('bulkDeleteBtn').disabled = count === 0;
}

// ── View Detail ────────────────────────────────────────────────────────────
function archiveViewDetail(id) {
    fetch(`/admin/archive/detail/${id}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const record = data.data;
            const submissionData = record.soumission_data || {};

            let fieldsHtml = '';
            for (const [key, value] of Object.entries(submissionData)) {
                if (typeof value === 'string' || typeof value === 'number') {
                    fieldsHtml += `
                        <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border);font-size:0.8rem;">
                            <span style="color:var(--text3);">${escapeHtml(key)}</span>
                            <span style="color:var(--text);font-weight:500;">${escapeHtml(String(value))}</span>
                        </div>`;
                }
            }

            alert(
                `📋 Détail de l'archive\n\n` +
                `Formulaire: ${record.formulaire_titre}\n` +
                `Statut: ${record.id_statut || '—'}\n` +
                `Soumis par: ${record.soumis_par_prenom || ''} ${record.soumis_par_nom || ''}\n` +
                `Archivé le: ${record.archived_at ? new Date(record.archived_at).toLocaleString('fr-FR') : '—'}\n` +
                `Raison: ${record.archived_reason || 'Form deleted'}\n\n` +
                `Données:\n${Object.entries(submissionData).map(([k,v]) => `${k}: ${v}`).join('\n')}`
            );
        } else {
            archiveShowToast('Erreur: ' + (data.message || 'Impossible de charger les détails'), 'error');
        }
    })
    .catch(error => {
        console.error('Error viewing detail:', error);
        archiveShowToast('Erreur lors du chargement des détails', 'error');
    });
}

// ── Restore Single ─────────────────────────────────────────────────────────
function archiveRestoreSingle(id) {
    if (!confirm('Voulez-vous restaurer cette archive dans les soumissions actives ?')) return;

    fetch(`/admin/archive/restore`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ id: id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            archiveShowToast('✅ ' + data.message, 'success');
            archiveLoadData(archiveCurrentPage);
        } else {
            archiveShowToast('❌ ' + data.message, 'error');
        }
    })
    .catch(error => {
        archiveShowToast('❌ Erreur réseau', 'error');
    });
}

// ── Delete Single ──────────────────────────────────────────────────────────
function archiveDeleteSingle(id) {
    archiveConfirmAction = 'delete_single';
    archiveConfirmData = { id: id };
    document.getElementById('confirmModalTitle').textContent = '🗑 Suppression définitive';
    document.getElementById('confirmModalMessage').innerHTML =
        'Cette action est <strong>irréversible</strong>. L\'archive sera définitivement supprimée. Veuillez confirmer en tapant <strong>SUPPRIMER</strong> ci-dessous.';

    // Reset and open modal
    const input = document.getElementById('confirmModalInput');
    if (input) {
        input.value = '';
        input.focus();
    }
    document.getElementById('confirmModalBtn').disabled = true;
    document.getElementById('confirmModalBtn').textContent = '🗑 Confirmer la suppression';
    document.getElementById('confirmModalBtn').style.display = 'inline-flex';

    const modal = document.getElementById('archiveConfirmModal');
    if (modal) {
        modal.classList.add('open');
        // Focus after a small delay
        setTimeout(() => {
            const inputEl = document.getElementById('confirmModalInput');
            if (inputEl) inputEl.focus();
        }, 300);
    }
}


// ── Bulk Delete ────────────────────────────────────────────────────────────
function archiveBulkDelete() {
    if (archiveSelectedIds.length === 0) return;

    archiveConfirmAction = 'delete_bulk';
    archiveConfirmData = { ids: [...archiveSelectedIds] };
    document.getElementById('confirmModalTitle').textContent = `🗑 Suppression en masse (${archiveSelectedIds.length} archive(s))`;
    document.getElementById('confirmModalMessage').innerHTML =
        `Vous allez supprimer <strong>${archiveSelectedIds.length}</strong> archive(s). Cette action est <strong>irréversible</strong>. Veuillez confirmer en tapant <strong>SUPPRIMER</strong> ci-dessous.`;

    const input = document.getElementById('confirmModalInput');
    if (input) {
        input.value = '';
        input.focus();
    }
    document.getElementById('confirmModalBtn').disabled = true;
    document.getElementById('confirmModalBtn').textContent = '🗑 Confirmer la suppression';
    document.getElementById('confirmModalBtn').style.display = 'inline-flex';

    const modal = document.getElementById('archiveConfirmModal');
    if (modal) {
        modal.classList.add('open');
        setTimeout(() => {
            const inputEl = document.getElementById('confirmModalInput');
            if (inputEl) inputEl.focus();
        }, 300);
    }
}
// ── Bulk Restore ───────────────────────────────────────────────────────────
function archiveBulkRestore() {
    if (archiveSelectedIds.length === 0) return;

    if (!confirm(`Voulez-vous restaurer ${archiveSelectedIds.length} archive(s) ?`)) return;

    fetch(`/admin/archive/restore-bulk`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ ids: archiveSelectedIds })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            archiveShowToast('✅ ' + data.message, 'success');
            archiveSelectedIds = [];
            archiveUpdateSelectedCount();
            archiveLoadData(archiveCurrentPage);
        } else {
            archiveShowToast('❌ ' + data.message, 'error');
        }
    })
    .catch(error => {
        archiveShowToast('❌ Erreur réseau', 'error');
    });
}

// ── Confirm Modal ──────────────────────────────────────────────────────────
function archiveCheckConfirm() {
    const input = document.getElementById('confirmModalInput');
    const btn = document.getElementById('confirmModalBtn');
    btn.disabled = input.value !== 'SUPPRIMER';
}

function archiveCloseConfirm() {
    const modal = document.getElementById('archiveConfirmModal');
    if (modal) modal.classList.remove('open');

    const input = document.getElementById('confirmModalInput');
    if (input) input.value = '';

    const btn = document.getElementById('confirmModalBtn');
    if (btn) {
        btn.disabled = true;
        btn.textContent = '🗑 Confirmer la suppression';
    }
    archiveConfirmAction = null;
    archiveConfirmData = null;
}

function archiveExecuteConfirm() {
    const input = document.getElementById('confirmModalInput');
    if (!input || input.value !== 'SUPPRIMER') {
        archiveShowToast('⚠️ Veuillez taper SUPPRIMER pour confirmer', 'error');
        return;
    }

    const btn = document.getElementById('confirmModalBtn');
    if (btn) {
        btn.disabled = true;
        btn.textContent = '⏳ Traitement...';
    }

    let url = '';
    let body = {};

    if (archiveConfirmAction === 'delete_single') {
        url = '{{ route("admin.archive.delete") }}';
        body = { id: archiveConfirmData.id, confirmation: 'SUPPRIMER' };
    } else if (archiveConfirmAction === 'delete_bulk') {
        url = '{{ route("admin.archive.delete-bulk") }}';
        body = { ids: archiveConfirmData.ids, confirmation: 'SUPPRIMER' };
    }

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify(body)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            archiveShowToast('✅ ' + data.message, 'success');
            archiveCloseConfirm();
            if (archiveConfirmAction === 'delete_bulk') {
                archiveSelectedIds = [];
                archiveUpdateSelectedCount();
            }
            archiveLoadData(archiveCurrentPage);
        } else {
            archiveShowToast('❌ ' + (data.message || 'Erreur lors de la suppression'), 'error');
            if (btn) {
                btn.disabled = false;
                btn.textContent = '🗑 Confirmer la suppression';
            }
        }
    })
    .catch(error => {
        archiveShowToast('❌ Erreur réseau: ' + error.message, 'error');
        if (btn) {
            btn.disabled = false;
            btn.textContent = '🗑 Confirmer la suppression';
        }
    });
}
// ── Toast ──────────────────────────────────────────────────────────────────
function archiveShowToast(message, type = 'info') {
    const container = document.getElementById('archiveToastContainer');
    const toast = document.createElement('div');
    toast.className = `archive-toast ${type}`;
    toast.textContent = message;
    container.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}

// ── Helpers ────────────────────────────────────────────────────────────────
function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

// ── Event Listeners ────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    // Load initial data
    archiveLoadData(1);

    // Filter on change
    document.getElementById('archiveFilterStatus').addEventListener('change', () => archiveLoadData(1));
    document.getElementById('archiveFilterDateFrom').addEventListener('change', () => archiveLoadData(1));
    document.getElementById('archiveFilterDateTo').addEventListener('change', () => archiveLoadData(1));

    // Search on enter
    document.getElementById('archiveFilterSearch').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') archiveLoadData(1);
    });
});
</script>
@endsection
