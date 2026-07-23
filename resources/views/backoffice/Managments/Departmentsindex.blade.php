{{-- resources/views/backoffice/departments/index.blade.php --}}
@extends('shared.layouts.backoffice')

@section('content')
<style>
/* ══════════════════════════════════════════════════════
   MANAGE DEPARTMENTS — Dark theme, matches displayUsers
══════════════════════════════════════════════════════ */

/* ── Page wrapper ───────────────────────────────────── */
.dm-wrap {
    padding: 28px 28px 60px;
    font-family: var(--font-body, 'Playfair Display', system-ui, sans-serif);
    color: var(--text, #f0f0f0);
}

/* ── Page header ────────────────────────────────────── */
.dm-page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 28px;
}
.dm-page-title {
    font-size: 22px;
    font-weight: 800;
    color: var(--text, #f0f0f0);
    line-height: 1.2;
    display: flex;
    align-items: center;
    gap: 10px;
}
.dm-page-title-icon {
    width: 40px; height: 40px;
    border-radius: 10px;
    background: var(--gold-dim, rgba(201,168,76,.15));
    border: 1px solid rgba(201,168,76,.25);
    display: flex; align-items: center; justify-content: center;
    color: var(--gold, #c9a84c);
    flex-shrink: 0;
}
.dm-page-sub {
    font-size: 13px;
    color: var(--text2, #8a8f9a);
    margin-top: 4px;
    font-weight: 400;
}

/* ── Primary action button ──────────────────────────── */
.dm-btn-primary {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 20px; border-radius: 9px;
    background: linear-gradient(135deg, var(--gold, #c9a84c), var(--gold3, #a07830));
    color: #111; font-size: 13.5px; font-weight: 700;
    border: none; cursor: pointer; font-family: inherit;
    transition: all .18s; text-decoration: none; white-space: nowrap;
}
.dm-btn-primary:hover {
    background: linear-gradient(135deg, var(--gold2, #e8c97a), var(--gold, #c9a84c));
    box-shadow: 0 6px 20px rgba(201,168,76,.35);
    transform: translateY(-1px);
}

/* ── KPI Grid ────────────────────────────────────────– */
.dm-kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 14px;
    margin-bottom: 26px;
}
.dm-kpi {
    background: var(--bg2, #111316);
    border: 1px solid var(--border, rgba(255,255,255,.07));
    border-radius: 12px;
    padding: 18px 18px 16px;
    position: relative;
    overflow: hidden;
    cursor: default;
    transition: border-color .2s, transform .15s, box-shadow .2s;
}
.dm-kpi:hover {
    border-color: var(--border2, rgba(255,255,255,.14));
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,.35);
}
.dm-kpi::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: var(--kpi-color, var(--gold, #c9a84c));
}
.dm-kpi-val {
    font-size: 30px; font-weight: 800;
    font-family: var(--font-mono, monospace);
    color: var(--kpi-color, var(--gold, #c9a84c));
    line-height: 1;
}
.dm-kpi-lbl {
    font-size: 11px; font-weight: 600;
    text-transform: uppercase; letter-spacing: .5px;
    color: var(--text2, #8a8f9a); margin-top: 6px;
}
.dm-kpi-icon {
    position: absolute; right: 14px; top: 14px;
    opacity: .07; font-size: 28px;
}

/* ── Panel ──────────────────────────────────────────– */
.dm-panel {
    background: var(--bg2, #111316);
    border: 1px solid var(--border, rgba(255,255,255,.07));
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 20px;
}
.dm-panel-head {
    padding: 14px 20px;
    border-bottom: 1px solid var(--border, rgba(255,255,255,.07));
    display: flex; align-items: center; justify-content: space-between;
    background: var(--bg3, #181b1f);
}
.dm-panel-title {
    font-size: 13px; font-weight: 700;
    color: var(--text, #f0f0f0);
    display: flex; align-items: center; gap: 8px;
}

/* ── Toolbar ────────────────────────────────────────– */
.dm-toolbar {
    display: flex; gap: 10px; align-items: center;
    padding: 16px 20px;
    border-bottom: 1px solid var(--border, rgba(255,255,255,.07));
    flex-wrap: wrap;
    background: var(--bg2, #111316);
}
.dm-search-wrap {
    flex: 1; min-width: 220px; position: relative;
}
.dm-search-wrap svg {
    position: absolute; left: 11px; top: 50%;
    transform: translateY(-50%);
    color: var(--text3, #4a4f5a); pointer-events: none;
}
.dm-search-input {
    width: 100%; padding: 9px 12px 9px 36px;
    background: var(--bg4, #1e2228);
    border: 1px solid var(--border2, rgba(255,255,255,.12));
    border-radius: 8px;
    font-family: inherit; font-size: 13px;
    color: var(--text, #f0f0f0);
    transition: border-color .15s, box-shadow .15s;
    outline: none;
}
.dm-search-input:focus {
    border-color: var(--gold, #c9a84c);
    box-shadow: 0 0 0 3px rgba(201,168,76,.12);
}
.dm-search-input::placeholder { color: var(--text3, #4a4f5a); }

/* ── Table ──────────────────────────────────────────– */
.dm-table-wrap {
    overflow-x: auto;
}
.dm-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}
.dm-table thead {
    background: var(--bg3, #181b1f);
}
.dm-table th {
    padding: 12px 18px;
    text-align: left;
    font-weight: 700;
    color: var(--text, #f0f0f0);
    border-bottom: 1px solid var(--border, rgba(255,255,255,.07));
    white-space: nowrap;
}
.dm-table tbody tr {
    border-bottom: 1px solid var(--border, rgba(255,255,255,.07));
    transition: background .15s;
}
.dm-table tbody tr:hover {
    background: rgba(201,168,76,.04);
}
.dm-table td {
    padding: 14px 18px;
    color: var(--text, #f0f0f0);
}

/* ── Table cell types ───────────────────────────────– */
.dm-cell-name {
    font-weight: 600;
    color: var(--gold, #c9a84c);
}
.dm-cell-perm {
    font-family: var(--font-mono, monospace);
    font-size: 12px;
    background: var(--bg4, #1e2228);
    padding: 4px 8px;
    border-radius: 5px;
    color: var(--text2, #8a8f9a);
    display: inline-block;
}
.dm-cell-date {
    font-size: 12px;
    color: var(--text2, #8a8f9a);
}

/* ── Actions column ─────────────────────────────────– */
.dm-actions {
    display: flex; gap: 8px;
}
.dm-action-btn {
    width: 28px; height: 28px;
    border-radius: 6px;
    border: 1px solid rgba(255,255,255,.08);
    background: var(--bg4, #1e2228);
    color: var(--text2, #8a8f9a);
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: all .15s;
    flex-shrink: 0;
}
.dm-action-btn:hover {
    border-color: var(--gold, #c9a84c);
    color: var(--gold, #c9a84c);
    background: rgba(201,168,76,.08);
    transform: translateY(-1px);
}
.dm-action-btn.edit:hover { --kpi-color: #4ade80; color: #4ade80; border-color: #4ade80; }
.dm-action-btn.delete:hover { --kpi-color: #ef4444; color: #ef4444; border-color: #ef4444; }

/* ── Empty state ────────────────────────────────────– */
.dm-empty {
    padding: 60px 40px;
    text-align: center;
    color: var(--text2, #8a8f9a);
}
.dm-empty-icon {
    font-size: 48px;
    color: var(--text3, #4a4f5a);
    margin-bottom: 12px;
    opacity: .5;
}
.dm-empty-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--text, #f0f0f0);
    margin-bottom: 8px;
}
.dm-empty-sub {
    font-size: 13px;
    color: var(--text2, #8a8f9a);
}

/* ── Pagination ─────────────────────────────────────– */
.dm-pagination-wrap {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    border-top: 1px solid var(--border, rgba(255,255,255,.07));
    background: var(--bg3, #181b1f);
    font-size: 12px;
}
.dm-pagination-info {
    color: var(--text2, #8a8f9a);
}
.dm-pagination-info strong {
    color: var(--text, #f0f0f0);
}

/* ── Modal ──────────────────────────────────────────– */
.dm-modal {
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,.6);
    z-index: 1000;
    align-items: center;
    justify-content: center;
    padding: 20px;
    animation: dmModalFadeIn .25s ease;
}
.dm-modal.open {
    display: flex;
}
@keyframes dmModalFadeIn {
    from { opacity: 0; } to { opacity: 1; }
}
.dm-modal-content {
    background: var(--bg2, #111316);
    border: 1px solid var(--border, rgba(255,255,255,.07));
    border-radius: 14px;
    width: 100%; max-width: 520px;
    max-height: 90vh;
    overflow-y: auto;
    animation: dmModalSlideIn .25s ease;
}
@keyframes dmModalSlideIn {
    from { transform: translateY(-30px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
.dm-modal-head {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border, rgba(255,255,255,.07));
    display: flex; justify-content: space-between; align-items: center;
}
.dm-modal-title {
    font-size: 16px; font-weight: 700; color: var(--text, #f0f0f0);
}
.dm-modal-close {
    width: 32px; height: 32px;
    border-radius: 6px;
    border: 1px solid rgba(255,255,255,.08);
    background: transparent;
    color: var(--text2, #8a8f9a);
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: all .15s;
    font-size: 20px; line-height: 1;
}
.dm-modal-close:hover {
    border-color: var(--gold, #c9a84c);
    color: var(--gold, #c9a84c);
}
.dm-modal-body {
    padding: 24px;
}
.dm-modal-footer {
    padding: 16px 24px;
    border-top: 1px solid var(--border, rgba(255,255,255,.07));
    display: flex; gap: 10px; justify-content: flex-end;
}

/* ── Form elements ──────────────────────────────────– */
.dm-form-group {
    margin-bottom: 18px;
}
.dm-form-label {
    display: block;
    font-size: 12px; font-weight: 600;
    color: var(--text, #f0f0f0);
    text-transform: uppercase; letter-spacing: .4px;
    margin-bottom: 6px;
}
.dm-form-input,
.dm-form-textarea {
    width: 100%;
    padding: 10px 12px;
    background: var(--bg4, #1e2228);
    border: 1px solid var(--border2, rgba(255,255,255,.12));
    border-radius: 8px;
    font-family: inherit;
    font-size: 13px;
    color: var(--text, #f0f0f0);
    transition: border-color .15s, box-shadow .15s;
}
.dm-form-input:focus,
.dm-form-textarea:focus {
    outline: none;
    border-color: var(--gold, #c9a84c);
    box-shadow: 0 0 0 3px rgba(201,168,76,.12);
}
.dm-form-textarea {
    resize: vertical;
    min-height: 80px;
}
.dm-form-error {
    font-size: 12px;
    color: #ef4444;
    margin-top: 4px;
    display: none;
}
.dm-form-error.show {
    display: block;
}

/* ── Buttons ────────────────────────────────────────– */
.dm-btn {
    padding: 10px 18px;
    border-radius: 8px;
    border: none;
    font-size: 13px; font-weight: 600;
    cursor: pointer;
    font-family: inherit;
    transition: all .15s;
    display: inline-flex; align-items: center; gap: 6px;
}
.dm-btn-secondary {
    background: var(--bg4, #1e2228);
    border: 1px solid var(--border2, rgba(255,255,255,.12));
    color: var(--text, #f0f0f0);
}
.dm-btn-secondary:hover {
    border-color: var(--text, #f0f0f0);
}
.dm-btn-save {
    background: linear-gradient(135deg, var(--gold, #c9a84c), var(--gold3, #a07830));
    color: #111;
}
.dm-btn-save:hover {
    background: linear-gradient(135deg, var(--gold2, #e8c97a), var(--gold, #c9a84c));
    box-shadow: 0 4px 12px rgba(201,168,76,.25);
}

/* ── Notification ───────────────────────────────────– */
.dm-notif {
    position: fixed;
    bottom: 20px;
    right: 20px;
    padding: 14px 20px;
    border-radius: 9px;
    font-size: 13px;
    font-weight: 600;
    z-index: 2000;
    animation: dmNotifSlideIn .3s ease;
    max-width: 420px;
}
@keyframes dmNotifSlideIn {
    from { transform: translateX(400px); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
.dm-notif.success {
    background: rgba(74,222,128,.15);
    border: 1px solid rgba(74,222,128,.3);
    color: #4ade80;
}
.dm-notif.error {
    background: rgba(239,68,68,.15);
    border: 1px solid rgba(239,68,68,.3);
    color: #ef4444;
}

/* ── Utility ────────────────────────────────────────– */
.dm-loading {
    display: inline-block;
    width: 12px; height: 12px;
    border: 2px solid rgba(201,168,76,.3);
    border-top-color: var(--gold, #c9a84c);
    border-radius: 50%;
    animation: dmSpin .6s linear infinite;
}
@keyframes dmSpin {
    to { transform: rotate(360deg); }
}

@media (max-width: 768px) {
    .dm-wrap { padding: 20px 16px 40px; }
    .dm-page-header { flex-direction: column; }
    .dm-page-title { font-size: 18px; }
    .dm-table { font-size: 12px; }
    .dm-modal-content { max-width: 100%; }
}
</style>

<div class="dm-wrap">

    {{-- Header --}}
    <div class="dm-page-header">
        <div>
            <div class="dm-page-title">
                <div class="dm-page-title-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>
                    </svg>
                </div>
                Gérer les départements
            </div>
            <div class="dm-page-sub">
                Créez et gérez les départements (arts plastiques, musique, livres, audiovisuel, etc.)
            </div>
        </div>
        <button class="dm-btn-primary" onclick="dmOpenAddModal()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Ajouter un département
        </button>
    </div>

    {{-- KPI Grid --}}
    <div class="dm-kpi-grid">
        <div class="dm-kpi">
            <div class="dm-kpi-val">{{ $departments->total() }}</div>
            <div class="dm-kpi-lbl">Total</div>
            <div class="dm-kpi-icon">📊</div>
        </div>
        <div class="dm-kpi">
            <div class="dm-kpi-val">{{ count($departments->items()) }}</div>
            <div class="dm-kpi-lbl">Sur cette page</div>
            <div class="dm-kpi-icon">📄</div>
        </div>
    </div>

    {{-- Panel --}}
    <div class="dm-panel">

        {{-- Toolbar --}}
        <div class="dm-toolbar">
            <form method="GET" action="{{ route('admin.departments.index') }}" style="display: flex; gap: 10px; flex: 1; width: 100%;">
                <div class="dm-search-wrap" style="flex: 1;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                    <input
                        type="text"
                        name="search"
                        class="dm-search-input"
                        placeholder="Rechercher par nom, permission..."
                        value="{{ request('search') }}"
                    >
                </div>
                <button type="submit" class="dm-btn dm-btn-secondary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    Chercher
                </button>
            </form>
        </div>

        {{-- Table --}}
        <div class="dm-table-wrap">
            <table class="dm-table">
                <thead>
                    <tr>
                        <th>Nom du département</th>
                        <th>Permission</th>
                        <th>Description</th>
                        <th>Créé le</th>
                        <th style="text-align: right; width: 80px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $dept)
                        <tr>
                            <td>
                                <div class="dm-cell-name">{{ ucfirst(str_replace('_', ' ', $dept->name)) }}</div>
                            </td>
                            <td>
                                <span class="dm-cell-perm">{{ $dept->permission ?? '—' }}</span>
                            </td>
                            <td style="color: var(--text2, #8a8f9a); max-width: 250px;">
                                {{ $dept->description ? substr($dept->description, 0, 50) . (strlen($dept->description) > 50 ? '...' : '') : '—' }}
                            </td>
                            <td>
                                <span class="dm-cell-date">{{ $dept->created_at->format('d/m/Y') }}</span>
                            </td>
                            <td style="text-align: right;">
                                <div class="dm-actions">
                                    {{-- Edit --}}
                                    <button type="button"
                                            class="dm-action-btn edit"
                                            onclick="dmOpenEditModal({{ $dept->id }})"
                                            title="Modifier">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                    </button>

                                    {{-- Delete --}}
                                    <button type="button"
                                            class="dm-action-btn delete"
                                            onclick="dmOpenDeleteModal({{ $dept->id }}, '{{ addslashes($dept->name) }}')"
                                            title="Supprimer">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="dm-empty">
                                    <div class="dm-empty-icon">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                                            <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                                            <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                                        </svg>
                                    </div>
                                    <div class="dm-empty-title">Aucun département trouvé</div>
                                    <div class="dm-empty-sub">
                                        @if(request('search'))
                                            Aucun résultat pour «&nbsp;<strong>{{ request('search') }}</strong>&nbsp;»
                                        @else
                                            Commencez par ajouter votre premier département.
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($departments->hasPages())
            <div class="dm-pagination-wrap">
                <div class="dm-pagination-info">
                    Affichage <strong>{{ $departments->firstItem() }}</strong> – <strong>{{ $departments->lastItem() }}</strong>
                    sur <strong>{{ $departments->total() }}</strong> départements
                </div>
                <div>
                    {{ $departments->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @endif

    </div>{{-- /dm-panel --}}

</div>{{-- /dm-wrap --}}

{{-- ── Modals ──────────────────────────────────────────── --}}

{{-- Add/Edit Modal --}}
<div id="dmAddModal" class="dm-modal">
    <div class="dm-modal-content">
        <div class="dm-modal-head">
            <div class="dm-modal-title">Ajouter un département</div>
            <button type="button" class="dm-modal-close" onclick="dmCloseModal('dmAddModal')">×</button>
        </div>
        <form id="dmAddForm" onsubmit="dmSubmitForm(event)">
            <div class="dm-modal-body">
                @csrf

                <div class="dm-form-group">
                    <label class="dm-form-label">
                        <span style="color: #ef4444;">*</span> Nom du département
                    </label>
                    <input
                        type="text"
                        id="dmName"
                        name="name"
                        class="dm-form-input"
                        placeholder="ex: arts_plastiques"
                        required
                    >
                    <div class="dm-form-error" id="dmNameError"></div>
                    <small style="color: var(--text3, #4a4f5a); display: block; margin-top: 6px;">
                        Lettres minuscules et tirets bas uniquement
                    </small>
                </div>

                <div class="dm-form-group">
                    <label class="dm-form-label">Permission</label>
                    <input
                        type="text"
                        id="dmPermission"
                        name="permission"
                        class="dm-form-input"
                        placeholder="ex: manage_arts_plastiques (optionnel)"
                    >
                    <div class="dm-form-error" id="dmPermissionError"></div>
                </div>

                <div class="dm-form-group">
                    <label class="dm-form-label">Description</label>
                    <textarea
                        id="dmDescription"
                        name="description"
                        class="dm-form-textarea"
                        placeholder="Description courte du département..."
                    ></textarea>
                    <div class="dm-form-error" id="dmDescriptionError"></div>
                </div>
            </div>

            <div class="dm-modal-footer">
                <button type="button" class="dm-btn dm-btn-secondary" onclick="dmCloseModal('dmAddModal')">Annuler</button>
                <button type="submit" class="dm-btn dm-btn-save" id="dmSubmitBtn">
                    <span id="dmSubmitText">Créer</span>
                    <span id="dmSubmitLoader" class="dm-loading" style="display: none;"></span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Modal --}}
<div id="dmDeleteModal" class="dm-modal">
    <div class="dm-modal-content" style="max-width: 420px;">
        <div class="dm-modal-head">
            <div class="dm-modal-title">Confirmer la suppression</div>
            <button type="button" class="dm-modal-close" onclick="dmCloseModal('dmDeleteModal')">×</button>
        </div>
        <div class="dm-modal-body">
            <p style="color: var(--text2, #8a8f9a); margin-bottom: 12px;">
                Êtes-vous sûr de vouloir supprimer le département <strong id="dmDeleteName"></strong> ?
            </p>
            <p style="font-size: 12px; color: var(--text3, #4a4f5a);">
                ⚠️ Cette action est irréversible.
            </p>
        </div>
        <div class="dm-modal-footer">
            <button type="button" class="dm-btn dm-btn-secondary" onclick="dmCloseModal('dmDeleteModal')">Annuler</button>
            <button type="button" class="dm-btn dm-btn-save" style="background: #ef4444; color: white;" id="dmDeleteBtn">
                <span id="dmDeleteText">Supprimer</span>
                <span id="dmDeleteLoader" class="dm-loading" style="display: none;"></span>
            </button>
        </div>
    </div>
</div>

{{-- Notification --}}
<div id="dmNotify"></div>

{{-- Scripts --}}
<script>
// ── State ────────────────────────────────────────────
let dmCurrentDeptId = null;
let dmIsEditing = false;

// ── Modal helpers ────────────────────────────────────
function dmOpenAddModal() {
    dmIsEditing = false;
    document.getElementById('dmAddForm').reset();
    document.getElementById('dmAddModal').classList.add('open');
    document.getElementById('dmAddForm').dataset.action = 'add';
    clearDmErrors();
    document.querySelector('.dm-modal-title').textContent = 'Ajouter un département';
    document.getElementById('dmName').disabled = false;
}

function dmOpenEditModal(id) {
    dmIsEditing = true;
    dmCurrentDeptId = id;
    const form = document.getElementById('dmAddForm');
    form.dataset.action = 'edit';
    form.dataset.deptId = id;

    document.querySelector('.dm-modal-title').textContent = 'Modifier le département';
    clearDmErrors();

    fetch(`/admin/departments/${id}`)
        .then(r => r.json())
        .then(dept => {
            document.getElementById('dmName').value = dept.name || '';
            document.getElementById('dmPermission').value = dept.permission || '';
            document.getElementById('dmDescription').value = dept.description || '';
            document.getElementById('dmName').disabled = true;

            document.getElementById('dmAddModal').classList.add('open');
        })
        .catch(() => dmNotify('✗ Erreur de chargement', 'error'));
}
function dmOpenDeleteModal(id, name) {
    dmCurrentDeptId = id;
    document.getElementById('dmDeleteName').textContent = name;
    document.getElementById('dmDeleteModal').classList.add('open');

    document.getElementById('dmDeleteBtn').onclick = () => dmDeleteDept(id);
}

function dmCloseModal(modalId) {
    document.getElementById(modalId).classList.remove('open');
}

// ── Form submission ──────────────────────────────────
function dmSubmitForm(e) {
    e.preventDefault();

    const form = document.getElementById('dmAddForm');
    const isEdit = form.dataset.action === 'edit';
    const deptId = form.dataset.deptId;

    const formData = new FormData(form);

    // Always use POST + _method spoofing
    if (isEdit) {
        formData.append('_method', 'PUT');

        // Manually add name because disabled fields are not sent
        const nameValue = document.getElementById('dmName').value;
        if (nameValue) {
            formData.append('name', nameValue);
        }
    }

    clearDmErrors();
    setDmSubmitLoading(true);

    fetch(isEdit ? `/admin/departments/${deptId}` : '/admin/departments', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            dmNotify(data.message, 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            dmNotify(data.message || '✗ Erreur de validation', 'error');

            // Display field errors nicely
            if (data.errors) {
                Object.keys(data.errors).forEach(field => {
                    const errorId = `dm${field.charAt(0).toUpperCase() + field.slice(1)}Error`;
                    const errEl = document.getElementById(errorId);
                    if (errEl) {
                        errEl.textContent = data.errors[field][0];
                        errEl.classList.add('show');
                    }
                });
            }
        }
    })
    .catch(err => {
        console.error(err);
        dmNotify('✗ Erreur serveur (500)', 'error');
    })
    .finally(() => setDmSubmitLoading(false));
}

function dmDeleteDept(id) {
    setDmDeleteLoading(true);

    const formData = new FormData();
    formData.append('_method', 'DELETE');

    fetch(`/admin/departments/${id}`, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            dmNotify(data.message, 'success');
            setTimeout(() => location.reload(), 1200);
        } else {
            dmNotify(data.message || '✗ Impossible de supprimer', 'error');
        }
    })
    .catch(err => {
        console.error(err);
        dmNotify('✗ Erreur serveur lors de la suppression', 'error');
    })
    .finally(() => setDmDeleteLoading(false));
}

// ── Helpers ──────────────────────────────────────────
function setDmSubmitLoading(loading) {
    const btn = document.getElementById('dmSubmitBtn');
    const text = document.getElementById('dmSubmitText');
    const loader = document.getElementById('dmSubmitLoader');
    btn.disabled = loading;
    text.style.display = loading ? 'none' : 'inline';
    loader.style.display = loading ? 'inline-block' : 'none';
}

function setDmDeleteLoading(loading) {
    const btn = document.getElementById('dmDeleteBtn');
    const text = document.getElementById('dmDeleteText');
    const loader = document.getElementById('dmDeleteLoader');
    btn.disabled = loading;
    text.style.display = loading ? 'none' : 'inline';
    loader.style.display = loading ? 'inline-block' : 'none';
}

function clearDmErrors() {
    document.querySelectorAll('.dm-form-error').forEach(el => {
        el.textContent = '';
        el.classList.remove('show');
    });
}

function dmNotify(msg, type) {
    const container = document.getElementById('dmNotify');
    const el = document.createElement('div');
    el.className = `dm-notif ${type}`;
    el.textContent = msg;
    container.appendChild(el);
    setTimeout(() => el.remove(), 4500);
}

// Close modals on outside click
document.addEventListener('click', (e) => {
    ['dmAddModal', 'dmDeleteModal'].forEach(id => {
        const modal = document.getElementById(id);
        if (e.target === modal) {
            modal.classList.remove('open');
        }
    });
});

// Prevent body scroll when modal is open
const observer = new MutationObserver(() => {
    const hasOpenModal = document.querySelectorAll('.dm-modal.open').length > 0;
    document.body.style.overflow = hasOpenModal ? 'hidden' : '';
});
observer.observe(document.body, { attributes: true, subtree: true, attributeFilter: ['class'] });
</script>

@endsection
