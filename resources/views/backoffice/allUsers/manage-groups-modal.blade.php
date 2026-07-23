{{-- resources/views/backoffice/allUsers/manage-groups-modal.blade.php --}}
<style>
.gm-overlay {
    display: none; position: fixed; inset: 0; z-index: 9000;
    background: rgba(0,0,0,.65); backdrop-filter: blur(3px);
    animation: gmFadeIn .18s ease;
}
.gm-overlay.open { display: flex; align-items: center; justify-content: center; padding: 16px; }
@keyframes gmFadeIn { from{opacity:0} to{opacity:1} }

/* ── Modal — improved sizing ── */
.gm-modal {
    background: var(--bg2, #111316);
    border: 1px solid var(--border, rgba(255,255,255,.09));
    border-radius: 14px;
    width: 100%; max-width: 820px;
    max-height: 90vh;
    display: flex; flex-direction: column;
    animation: gmSlideUp .2s ease; overflow: hidden;
}
@keyframes gmSlideUp { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:none} }

.gm-modal-head {
    padding: 14px 18px 12px; flex-shrink: 0;
    border-bottom: 1px solid var(--border, rgba(255,255,255,.07));
    background: var(--bg3, #181b1f);
    display: flex; align-items: center; justify-content: space-between;
}
.gm-modal-title {
    font-size: 14px; font-weight: 800; color: var(--text, #f0f0f0);
    display: flex; align-items: center; gap: 8px;
}
.gm-modal-title-icon {
    width: 28px; height: 28px; border-radius: 7px;
    background: var(--gold-dim, rgba(201,168,76,.15));
    border: 1px solid rgba(201,168,76,.25);
    display: flex; align-items: center; justify-content: center;
    color: var(--gold, #c9a84c);
}
.gm-close {
    width: 28px; height: 28px; border-radius: 6px;
    background: var(--bg4, #1e2228);
    border: 1px solid var(--border, rgba(255,255,255,.07));
    color: var(--text2, #8a8f9a);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: 15px; transition: all .15s;
}
.gm-close:hover { border-color: rgba(248,113,113,.4); color: var(--red, #f87171); }

/* ── Body: two columns ── */
.gm-modal-body {
    display: flex; flex: 1; min-height: 0;
    overflow: hidden;
}

/* ── Left column ── */
.gm-col-left {
    width: 280px; flex-shrink: 0;
    display: flex; flex-direction: column;
    overflow: hidden;
    padding: 14px 14px 14px 18px;
    border-right: 1px solid var(--border, rgba(255,255,255,.07));
    gap: 10px;
}

/* ── Right column ── */
.gm-col-right {
    flex: 1; min-width: 0;
    display: flex; flex-direction: column;
    overflow: hidden;
    padding: 14px 18px;
}

/* ── Collapsible sections ── */
.gm-section {
    background: var(--bg3, #181b1f);
    border: 1px solid var(--border, rgba(255,255,255,.07));
    border-radius: 10px;
    flex-shrink: 0;
    overflow: hidden;
}
.gm-section-toggle {
    display: flex; align-items: center; justify-content: space-between;
    padding: 8px 12px;
    cursor: pointer; user-select: none;
    -webkit-tap-highlight-color: transparent;
}
.gm-section-title {
    font-size: 10px; font-weight: 800;
    text-transform: uppercase; letter-spacing: .5px;
    color: var(--gold, #c9a84c);
    display: flex; align-items: center; gap: 6px;
    margin-bottom: 0;
}
.gm-section-chevron {
    color: var(--text3, #4a4f5a);
    transition: transform .18s;
    flex-shrink: 0;
    display: flex; align-items: center;
}
.gm-section.open .gm-section-chevron { transform: rotate(180deg); }
.gm-section-body {
    max-height: 0;
    overflow: hidden;
    transition: max-height .2s ease;
    padding: 0 12px;
}
.gm-section.open .gm-section-body {
    max-height: 200px;
    padding: 0 12px 12px;
}

/* ── Groups list — takes remaining space ── */
.gm-section-groups {
    background: var(--bg3, #181b1f);
    border: 1px solid var(--border, rgba(255,255,255,.07));
    border-radius: 10px;
    padding: 12px 13px;
    display: flex; flex-direction: column;
    flex: 1;
    min-height: 200px;
    overflow: hidden;
}

.gm-groups-list {
    flex: 1;
    overflow-y: auto;
    margin-top: 8px;
    padding-right: 2px;
}

/* ── Form elements ── */
.gm-label {
    display: block; font-size: 11px; font-weight: 600;
    color: var(--text3, #4a4f5a); margin-bottom: 4px;
}
.gm-input {
    width: 100%; padding: 7px 10px;
    background: var(--bg4, #1e2228);
    border: 1px solid var(--border2, rgba(255,255,255,.1));
    border-radius: 7px; color: var(--text, #f0f0f0);
    font-size: 12.5px; font-family: inherit; outline: none;
    transition: border-color .15s, box-shadow .15s; box-sizing: border-box;
}
.gm-input:focus { border-color: var(--gold,#c9a84c); box-shadow: 0 0 0 3px rgba(201,168,76,.1); }
.gm-input::placeholder { color: var(--text3,#4a4f5a); }
.gm-hint { font-size: 11px; color: var(--text3,#4a4f5a); margin-top: 4px; line-height: 1.4; }

/* ── Buttons ── */
.gm-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px; border-radius: 7px;
    font-size: 12px; font-weight: 700;
    cursor: pointer; font-family: inherit;
    border: none; transition: all .15s;
}
.gm-btn-gold { background: linear-gradient(135deg, var(--gold,#c9a84c), var(--gold3,#a07830)); color: #111; }
.gm-btn-gold:hover { box-shadow: 0 4px 12px rgba(201,168,76,.28); transform: translateY(-1px); }
.gm-btn-ghost {
    background: var(--bg4,#1e2228);
    border: 1px solid var(--border2,rgba(255,255,255,.1));
    color: var(--text2,#8a8f9a);
}
.gm-btn-ghost:hover { border-color: var(--gold,#c9a84c); color: var(--gold,#c9a84c); }
.gm-btn-full { width: 100%; justify-content: center; margin-top: 6px; }
.gm-btn-danger {
    background: var(--red-dim,rgba(248,113,113,.1));
    border: 1px solid rgba(248,113,113,.25);
    color: var(--red,#f87171);
    padding: 3px 9px; border-radius: 5px;
    font-size: 11px; font-weight: 600;
    cursor: pointer; font-family: inherit; transition: all .15s;
}
.gm-btn-danger:hover { background: rgba(248,113,113,.2); border-color: rgba(248,113,113,.45); }
.gm-btn:disabled { opacity: .5; cursor: not-allowed; transform: none !important; }

/* ── Search wraps ── */
.gm-search-wrap { position: relative; }
.gm-search-wrap svg {
    position: absolute; left: 9px; top: 50%;
    transform: translateY(-50%); color: var(--text3,#4a4f5a); pointer-events: none;
}
.gm-search-wrap .gm-input { padding-left: 30px; }

/* ── Group item ── */
.gm-group-item {
    background: var(--bg4,#1e2228);
    border: 1px solid var(--border,rgba(255,255,255,.06));
    border-radius: 8px; padding: 8px 10px;
    cursor: pointer; transition: border-color .15s, background .12s;
    display: flex; align-items: center; gap: 8px; flex-shrink: 0;
    margin-bottom: 5px;
}
.gm-group-item:hover { border-color: var(--border2,rgba(255,255,255,.14)); }
.gm-group-item.selected { border-color: rgba(201,168,76,.45); background: rgba(201,168,76,.06); }
.gm-group-icon {
    width: 28px; height: 28px; border-radius: 7px;
    background: var(--gold-dim,rgba(201,168,76,.12));
    border: 1px solid rgba(201,168,76,.2);
    display: flex; align-items: center; justify-content: center;
    color: var(--gold,#c9a84c); flex-shrink: 0;
}
.gm-group-info { flex: 1; min-width: 0; }
.gm-group-name {
    font-size: 12px; font-weight: 700; color: var(--text,#f0f0f0);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.gm-group-meta { font-size: 10.5px; color: var(--text3,#4a4f5a); margin-top: 1px; }
.gm-group-actions { display: flex; gap: 4px; flex-shrink: 0; }

/* ── Members panel ── */
.gm-members-head {
    padding-bottom: 10px; margin-bottom: 10px;
    border-bottom: 1px solid var(--border,rgba(255,255,255,.07));
    flex-shrink: 0;
}
.gm-members-title {
    font-size: 13px; font-weight: 700; color: var(--gold,#c9a84c);
    display: flex; align-items: center; gap: 7px;
}
.gm-members-subtitle { font-size: 11px; color: var(--text3,#4a4f5a); margin-top: 2px; }

.gm-members-list {
    flex: 1;
    overflow-y: auto;
    padding-right: 2px;
    display: flex;
    flex-direction: column;
    gap: 5px;
}

/* ── Member row ── */
.gm-member-row {
    display: flex; align-items: center; gap: 9px;
    padding: 7px 9px;
    background: var(--bg3,#181b1f);
    border: 1px solid var(--border,rgba(255,255,255,.06));
    border-radius: 7px; flex-shrink: 0; transition: border-color .12s;
}
.gm-member-row:hover { border-color: var(--border2,rgba(255,255,255,.13)); }
.gm-member-avatar {
    width: 28px; height: 28px; border-radius: 50%;
    background: var(--gold-dim,rgba(201,168,76,.12));
    display: flex; align-items: center; justify-content: center;
    font-size: 10px; font-weight: 700; color: var(--gold,#c9a84c); flex-shrink: 0;
}
.gm-member-info { flex: 1; min-width: 0; }
.gm-member-name {
    font-size: 12px; font-weight: 600; color: var(--text,#f0f0f0);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.gm-member-email { font-size: 10.5px; color: var(--text3,#4a4f5a); margin-top: 1px; }

/* ── Admin Picker (same style as Modeler) ── */
.gm-picker-wrap {
    position: relative;
}

.gm-picker-dropdown {
    display: none;
    position: fixed;          /* was: absolute */
    background: var(--bg2, #111316);
    border: 1px solid var(--gold, #c9a84c);
    border-radius: 8px;
    overflow-y: auto;
    z-index: 99999;
    box-shadow: 0 10px 30px rgba(0,0,0,.6);
    scrollbar-width: thin;
}
.gm-picker-dropdown.open { display: block; }
.gm-picker-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    cursor: pointer;
    border-bottom: 1px solid var(--border, rgba(255,255,255,.07));
    transition: background .12s;
}
.gm-picker-item:last-child { border-bottom: none; }
.gm-picker-item:hover { background: rgba(201,168,76,.08); }
.gm-picker-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(201,168,76,0.15);
    color: var(--gold, #c9a84c);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 700;
    flex-shrink: 0;
}
.gm-picker-name {
    font-size: 13px;
    font-weight: 600;
    color: var(--text);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.gm-picker-email {
    font-size: 11px;
    color: var(--text3);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.gm-picker-role {
    margin-left: auto;
    font-size: 10px;
    padding: 2px 8px;
    border-radius: 4px;
    background: rgba(201,168,76,0.15);
    color: var(--gold);
    border: 1px solid rgba(201,168,76,0.3);
    flex-shrink: 0;
}


.gm-picker-dept {
    font-size: 9.5px; color: var(--text3);
    margin-left: auto;
    flex-shrink: 0;
}

/* ── Badge ── */
.gm-badge {
    display: inline-flex; align-items: center;
    padding: 1px 7px; border-radius: 99px;
    font-size: 10.5px; font-weight: 700;
    background: rgba(201,168,76,.12);
    border: 1px solid rgba(201,168,76,.2);
    color: var(--gold,#c9a84c);
}

/* ── Empty / loading ── */
.gm-empty { padding: 24px 12px; text-align: center; color: var(--text3,#4a4f5a); }
.gm-empty-title { font-size: 12.5px; font-weight: 600; color: var(--text2,#8a8f9a); }
.gm-empty-sub { font-size: 11px; margin-top: 3px; }
.gm-loading { padding: 16px; text-align: center; font-size: 11.5px; color: var(--text3,#4a4f5a); }

.gm-no-selection {
    flex: 1; display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 30px; text-align: center; color: var(--text3,#4a4f5a); gap: 8px;
}
.gm-no-selection svg { opacity: .18; }
.gm-no-selection p { font-size: 13px; color: var(--text2,#8a8f9a); font-weight: 600; margin: 0; }
.gm-no-selection span { font-size: 11.5px; }

/* ── Toast ── */
.gm-toast {
    position: fixed; bottom: 22px; right: 22px;
    padding: 10px 15px; border-radius: 9px;
    font-size: 12.5px; font-weight: 600; z-index: 99999;
    animation: gmToastIn .22s ease;
    display: flex; align-items: center; gap: 7px;
    box-shadow: 0 8px 24px rgba(0,0,0,.5); pointer-events: none;
}
@keyframes gmToastIn { from{opacity:0;transform:translateX(14px)} to{opacity:1;transform:none} }
.gm-toast.success { background:var(--bg4,#1e2228); border:1px solid rgba(74,222,128,.3); color:var(--green,#4ade80); }
.gm-toast.error   { background:var(--bg4,#1e2228); border:1px solid rgba(248,113,113,.3); color:var(--red,#f87171);  }

/* ── Trigger button ── */
.gm-trigger-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 7px 15px; border-radius: 8px;
    background: var(--bg3,#181b1f);
    border: 1px solid var(--border2,rgba(255,255,255,.12));
    color: var(--text2,#8a8f9a);
    font-size: 12.5px; font-weight: 700;
    cursor: pointer; font-family: inherit; transition: all .18s; white-space: nowrap;
}
.gm-trigger-btn:hover { border-color: rgba(201,168,76,.4); color: var(--gold,#c9a84c); background: rgba(201,168,76,.06); }

/* ── Scrollbars ── */
.gm-col-left::-webkit-scrollbar,
.gm-groups-list::-webkit-scrollbar,
.gm-members-list::-webkit-scrollbar,
.gm-picker-dropdown::-webkit-scrollbar { width: 5px; }
.gm-col-left::-webkit-scrollbar-track,
.gm-groups-list::-webkit-scrollbar-track,
.gm-members-list::-webkit-scrollbar-track,
.gm-picker-dropdown::-webkit-scrollbar-track { background: transparent; }
.gm-col-left::-webkit-scrollbar-thumb,
.gm-groups-list::-webkit-scrollbar-thumb,
.gm-members-list::-webkit-scrollbar-thumb,
.gm-picker-dropdown::-webkit-scrollbar-thumb {
    background: rgba(201,168,76,.25);
    border-radius: 4px;
}

@media (max-width: 600px) {
    .gm-modal-body { flex-direction: column; overflow-y: auto; }
    .gm-col-left { width: 100%; border-right: none; border-bottom: 1px solid var(--border,rgba(255,255,255,.07)); }
    .gm-modal { max-height: 92vh; }
    .gm-section-groups { min-height: 200px; }
    .gm-groups-list { max-height: 200px; }
    .gm-members-list { max-height: 200px; }
}
</style>

{{-- ── Overlay + Modal ── --}}
<div class="gm-overlay" id="gm-overlay" onclick="gmHandleOverlayClick(event)">
    <div class="gm-modal" role="dialog" aria-modal="true" aria-labelledby="gm-title">

        <div class="gm-modal-head">
            <div class="gm-modal-title" id="gm-title">
                <div class="gm-modal-title-icon">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                Gestion des groupes Camunda
            </div>
            <button class="gm-close" onclick="gmClose()" aria-label="Fermer">✕</button>
        </div>

        <div class="gm-modal-body">

            {{-- ── LEFT ── --}}
            <div class="gm-col-left">

                {{-- Create — collapsible, closed by default --}}
                <div class="gm-section" id="gm-section-create">
                    <div class="gm-section-toggle" onclick="gmToggleSection('gm-section-create')">
                        <div class="gm-section-title">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Nouveau groupe
                        </div>
                        <span class="gm-section-chevron">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                        </span>
                    </div>
                    <div class="gm-section-body">
                        <div style="margin-bottom:8px; padding-top:2px;">
                            <label class="gm-label">ID du groupe</label>
                            <input type="text" id="gm-new-id" class="gm-input" placeholder="ex: chefs_service">
                        </div>
                        <div style="margin-bottom:2px;">
                            <label class="gm-label">Nom du groupe</label>
                            <input type="text" id="gm-new-name" class="gm-input" placeholder="ex: Chefs de Service">
                        </div>
                        <button class="gm-btn gm-btn-gold gm-btn-full" id="gm-create-btn" onclick="gmCreateGroup()">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Créer le groupe
                        </button>
                    </div>
                </div>

                {{-- Sync — collapsible, closed by default --}}
                <div class="gm-section" id="gm-section-sync">
                    <div class="gm-section-toggle" onclick="gmToggleSection('gm-section-sync')">
                        <div class="gm-section-title">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.57"/></svg>
                            Synchronisation
                        </div>
                        <span class="gm-section-chevron">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                        </span>
                    </div>
                    <div class="gm-section-body">
                        <p class="gm-hint" style="margin-bottom:6px; padding-top:2px;">Sync tous les admins Laravel → Camunda.</p>
                        <button class="gm-btn gm-btn-ghost gm-btn-full" id="gm-sync-btn" onclick="gmSyncUsers()" style="margin-top:0;">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.57"/></svg>
                            Synchroniser
                        </button>
                    </div>
                </div>

                {{-- Groups list — fills remaining space --}}
                <div class="gm-section-groups">
                    <div style="display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
                        <div class="gm-section-title">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="9" x2="15" y2="9"/></svg>
                            Groupes existants
                        </div>
                        <span style="font-size:10px;color:var(--text3);" id="gmGroupCount">0</span>
                    </div>
                    <div class="gm-search-wrap" style="flex-shrink:0; margin-top:8px;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" class="gm-input" id="gm-search" placeholder="Rechercher un groupe..." oninput="gmFilterGroups(this.value)">
                    </div>
                    <div class="gm-groups-list" id="gm-groups-list">
                        <div class="gm-loading">Chargement...</div>
                    </div>
                </div>

            </div>{{-- /gm-col-left --}}

            {{-- ── RIGHT ── --}}
            <div class="gm-col-right" id="gm-col-right">

                <div class="gm-no-selection" id="gm-no-selection">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    <p>Sélectionnez un groupe</p>
                    <span>Cliquez sur un groupe à gauche</span>
                </div>

                <div id="gm-members-panel" style="display:none; flex-direction:column; flex:1; min-height:0;">

                    <div class="gm-members-head">
                        <div class="gm-members-title">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                            </svg>
                            <span id="gm-active-group-name">—</span>
                            <span class="gm-badge" id="gm-member-count">0</span>
                        </div>
                        <div class="gm-members-subtitle" id="gm-active-group-id"></div>
                    </div>

                    <div class="gm-members-list" id="gm-members-list">
                        <div class="gm-loading">Chargement...</div>
                    </div>

                    {{-- Add member section --}}
                    <div class="gm-add-member-wrap" style="flex-shrink:0; padding-top:10px; margin-top:8px; border-top:1px solid var(--border,rgba(255,255,255,.07));">
                        <label class="gm-label">Ajouter un administrateur</label>
                        <div class="gm-picker-wrap" id="gm-picker-wrap">
                            <div class="gm-search-wrap">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                <input type="text" class="gm-input" id="gm-picker-input"
                                    placeholder="Rechercher un admin (nom, email, rôle, département)..."
                                    autocomplete="off"
                                    oninput="gmFilterPicker(this.value)" onfocus="gmOpenPicker()">
                            </div>
                            <input type="hidden" id="gm-picker-value">
                            <div class="gm-picker-dropdown" id="gm-picker-dropdown"></div>
                        </div>
                        <div id="gm-picker-preview" style="display:none; margin-top:6px; padding:8px 12px;
                            background:rgba(201,168,76,.08); border:1px solid rgba(201,168,76,.25);
                            border-radius:7px; align-items:center; gap:10px;">
                            <div style="flex:1; min-width:0;">
                                <div id="gm-picker-preview-name" style="font-size:12px; font-weight:700; color:var(--gold,#c9a84c);"></div>
                                <div id="gm-picker-preview-meta" style="font-size:10.5px; color:var(--text3,#4a4f5a); margin-top:1px;"></div>
                            </div>
                            <button onclick="gmClearPicker()" style="background:none;border:none;color:var(--text3,#4a4f5a);font-size:15px;cursor:pointer;line-height:1;padding:0;">✕</button>
                        </div>
                        <button class="gm-btn gm-btn-gold gm-btn-full" id="gm-add-btn" onclick="gmAddMember()" style="margin-top:6px;">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Ajouter au groupe
                        </button>
                    </div>

                </div>{{-- /gm-members-panel --}}
            </div>{{-- /gm-col-right --}}

        </div>{{-- /gm-modal-body --}}
    </div>
</div>

<script>
(function () {
const GM = {
    csrfToken:     document.querySelector('meta[name="csrf-token"]')?.content || '',
    allGroups:     [],
    allAdmins:     [],
    adminsFetched: false,
    activeGroupId: null,
    activeGroupName: null,
    pickerSelected: null,
};

function esc(s) {
    return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function gmToast(msg, type = 'success') {
    if (typeof window.showNotification === 'function') { window.showNotification(msg, type); return; }
    const t = document.createElement('div');
    t.className = 'gm-toast ' + type; t.textContent = msg;
    document.body.appendChild(t); setTimeout(() => t.remove(), 4000);
}

async function apiFetch(url, opts = {}) {
    const res = await fetch(url, {
        headers: { 'Accept':'application/json','Content-Type':'application/json','X-CSRF-TOKEN':GM.csrfToken, ...(opts.headers||{}) },
        ...opts,
    });
    return res.json();
}

/* ── Collapsible sections ── */
window.gmToggleSection = function (id) {
    const el = document.getElementById(id);
    if (!el) return;
    const willOpen = !el.classList.contains('open');
    el.classList.toggle('open');
    if (willOpen && id === 'gm-section-create') {
        setTimeout(() => document.getElementById('gm-new-id')?.focus(), 180);
    }
};

/* ── Open / Close ── */
window.gmOpen = async function () {
    document.getElementById('gm-overlay').classList.add('open');
    document.body.style.overflow = 'hidden';
    await Promise.all([gmLoadGroups(), gmFetchAdmins()]);
};
window.gmClose = function () {
    document.getElementById('gm-overlay').classList.remove('open');
    document.body.style.overflow = '';
};
window.gmHandleOverlayClick = function (e) {
    if (e.target === document.getElementById('gm-overlay')) gmClose();
};

/* ── Load groups ── */
async function gmLoadGroups() {
    const list = document.getElementById('gm-groups-list');
    list.innerHTML = '<div class="gm-loading">Chargement...</div>';
    try {
        const data = await apiFetch('/api/workflows/groups');
        if (!data.success) throw new Error(data.error || 'Erreur API');
        GM.allGroups = data.groups || [];
        gmRenderGroups(GM.allGroups);
        document.getElementById('gmGroupCount').textContent = GM.allGroups.length;
    } catch (e) {
        list.innerHTML = `<div class="gm-empty"><div class="gm-empty-title">Erreur</div><div class="gm-empty-sub">${esc(e.message)}</div></div>`;
    }
}

function gmRenderGroups(groups) {
    const list = document.getElementById('gm-groups-list');
    if (!groups.length) {
        list.innerHTML = `<div class="gm-empty"><div class="gm-empty-title">Aucun groupe</div><div class="gm-empty-sub">Créez votre premier groupe</div></div>`;
        return;
    }
    list.innerHTML = groups.map(g => `
        <div class="gm-group-item ${GM.activeGroupId === g.id ? 'selected' : ''}"
             onclick="gmSelectGroup('${esc(g.id)}','${esc(g.name)}', this)">
            <div class="gm-group-icon">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <div class="gm-group-info">
                <div class="gm-group-name">${esc(g.name)}</div>
                <div class="gm-group-meta">ID: ${esc(g.id)}${g.member_count !== undefined ? ' · '+g.member_count+' membre(s)' : ''}</div>
            </div>
            <div class="gm-group-actions" onclick="event.stopPropagation()">
                <button class="gm-btn-danger" onclick="gmDeleteGroup('${esc(g.id)}','${esc(g.name)}')" title="Supprimer">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
                </button>
            </div>
        </div>`).join('');
}

window.gmFilterGroups = function (query) {
    const q = query.trim().toLowerCase();
    gmRenderGroups(q ? GM.allGroups.filter(g => (g.name||'').toLowerCase().includes(q)||(g.id||'').toLowerCase().includes(q)) : GM.allGroups);
};

/* ── Select group ── */
window.gmSelectGroup = async function (id, name, el) {
    GM.activeGroupId = id; GM.activeGroupName = name;
    document.querySelectorAll('.gm-group-item').forEach(i => i.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('gm-no-selection').style.display = 'none';
    const panel = document.getElementById('gm-members-panel');
    panel.style.display = 'flex';
    document.getElementById('gm-active-group-name').textContent = name;
    document.getElementById('gm-active-group-id').textContent   = 'ID: ' + id;
    await gmLoadMembers(id);
};

async function gmLoadMembers(groupId) {
    const list = document.getElementById('gm-members-list');
    list.innerHTML = '<div class="gm-loading">Chargement...</div>';
    document.getElementById('gm-member-count').textContent = '…';
    try {
        const members = await apiFetch(`/api/workflows/groups/${encodeURIComponent(groupId)}/members`);
        document.getElementById('gm-member-count').textContent = Array.isArray(members) ? members.length : 0;
        if (!Array.isArray(members) || !members.length) {
            list.innerHTML = `<div class="gm-empty"><div class="gm-empty-title">Aucun membre</div><div class="gm-empty-sub">Ajoutez un administrateur ci-dessous</div></div>`;
            return;
        }
        list.innerHTML = members.map(m => {
            const display = m.displayName || m.userId;
            const initials = display.split(' ').map(w=>w[0]).join('').slice(0,2).toUpperCase();
            const email = m.email || '';
            return `<div class="gm-member-row">
                <div class="gm-member-avatar">${esc(initials)}</div>
                <div class="gm-member-info">
                    <div class="gm-member-name">${esc(display)}</div>
                    ${email ? `<div class="gm-member-email">${esc(email)}</div>` : ''}
                </div>
                <button class="gm-btn-danger" onclick="gmRemoveMember('${esc(m.userId)}','${esc(display)}')" title="Retirer">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>`;
        }).join('');
    } catch (e) {
        list.innerHTML = `<div class="gm-empty"><div class="gm-empty-title">Erreur</div><div class="gm-empty-sub">${esc(e.message)}</div></div>`;
    }
}

/* ── Create / Delete / Remove / Add ── */
window.gmCreateGroup = async function () {
    const id = document.getElementById('gm-new-id').value.trim();
    const name = document.getElementById('gm-new-name').value.trim();
    if (!id || !name) { gmToast('Remplissez l\'ID et le nom', 'error'); return; }
    const btn = document.getElementById('gm-create-btn');
    btn.disabled = true; btn.textContent = 'Création...';
    try {
        const data = await apiFetch('/api/workflows/groups', { method:'POST', body: JSON.stringify({ group_id:id, group_name:name, group_type:'WORKFLOW' }) });
        if (!data.success) throw new Error(data.error||'Erreur');
        gmToast(`Groupe "${name}" créé`, 'success');
        document.getElementById('gm-new-id').value = '';
        document.getElementById('gm-new-name').value = '';
        await gmLoadGroups();
    } catch(e) { gmToast('Erreur : '+e.message,'error'); }
    finally { btn.disabled = false; btn.textContent = 'Créer le groupe'; }
};

window.gmDeleteGroup = async function (id, name) {
    if (!confirm(`Supprimer le groupe "${name}" ?`)) return;
    try {
        const data = await apiFetch(`/api/workflows/groups/${encodeURIComponent(id)}`, { method:'DELETE' });
        if (!data.success) throw new Error(data.error||'Erreur');
        gmToast(`Groupe "${name}" supprimé`, 'success');
        if (GM.activeGroupId === id) {
            GM.activeGroupId = null;
            document.getElementById('gm-no-selection').style.display = 'flex';
            document.getElementById('gm-members-panel').style.display = 'none';
        }
        await gmLoadGroups();
    } catch(e) { gmToast('Erreur : '+e.message,'error'); }
};

window.gmRemoveMember = async function (userId, displayName) {
    if (!confirm(`Retirer "${displayName}" ?`)) return;
    try {
        const data = await apiFetch('/api/workflows/groups/remove-user', { method:'POST', body: JSON.stringify({ group_id:GM.activeGroupId, user_id:userId }) });
        if (!data.success) throw new Error(data.error||'Erreur');
        gmToast(`"${displayName}" retiré`, 'success');
        await gmLoadMembers(GM.activeGroupId);
        await gmLoadGroups();
    } catch(e) { gmToast('Erreur : '+e.message,'error'); }
};

window.gmAddMember = async function () {
    const email = document.getElementById('gm-picker-value').value;
    if (!email) { gmToast('Sélectionnez un administrateur', 'error'); return; }
    if (!GM.activeGroupId) { gmToast('Sélectionnez un groupe', 'error'); return; }
    const btn = document.getElementById('gm-add-btn');
    btn.disabled = true; btn.textContent = 'Ajout...';
    try {
        const data = await apiFetch('/api/workflows/groups/add-user', { method:'POST', body: JSON.stringify({ group_id:GM.activeGroupId, user_id:email }) });
        if (!data.success) throw new Error(data.error||'Erreur');
        gmToast('Membre ajouté', 'success');
        gmClearPicker();
        await gmLoadMembers(GM.activeGroupId);
        await gmLoadGroups();
    } catch(e) { gmToast('Erreur : '+e.message,'error'); }
    finally { btn.disabled = false; btn.textContent = 'Ajouter au groupe'; }
};

window.gmSyncUsers = async function () {
    const btn = document.getElementById('gm-sync-btn');
    btn.disabled = true; btn.textContent = 'Synchronisation...';
    try {
        const data = await apiFetch('/api/workflows/groups/sync-users', { method:'POST' });
        if (!data.success) throw new Error(data.error||'Erreur');
        const created = (data.results||[]).filter(r=>r.status==='created').length;
        const exists  = (data.results||[]).filter(r=>r.status==='exists').length;
        gmToast(`${created} créé(s), ${exists} déjà présent(s)`, 'success');
        GM.adminsFetched = false; await gmFetchAdmins();
    } catch(e) { gmToast('Erreur : '+e.message,'error'); }
    finally { btn.disabled = false; btn.textContent = 'Synchroniser'; }
};

/* ── Admin picker (same as Modeler) ── */
async function gmFetchAdmins() {
    if (GM.adminsFetched) return;
    try {
        const data = await apiFetch('/api/workflows/admins');
        GM.allAdmins = Array.isArray(data) ? data : [];
        GM.adminsFetched = true;
    } catch(e) { console.warn('gmFetchAdmins:', e.message); }
}

// Build picker item with role and department
function buildPickerItem(admin, query) {
    const q = (query || '').toLowerCase();
    const hl = (str) => {
        if (!q || !str) return esc(str || '');
        const idx = str.toLowerCase().indexOf(q);
        if (idx === -1) return esc(str);
        return esc(str.slice(0, idx))
             + `<mark style="background:rgba(201,168,76,0.35);color:var(--gold);border-radius:2px;">${esc(str.slice(idx, idx + q.length))}</mark>`
             + esc(str.slice(idx + q.length));
    };
    const initials = ((admin.prenom||'')[0] || '?').toUpperCase() + ((admin.nom||'')[0] || '?').toUpperCase();
    return `
        <div class="gm-picker-item" onclick="gmSelectAdmin('${esc(admin.email)}')">
            <div class="gm-picker-avatar">${esc(initials)}</div>
            <div style="flex:1;min-width:0;">
                <div class="gm-picker-name">${hl(admin.fullName)}</div>
                <div class="gm-picker-email">${hl(admin.email)}</div>
            </div>
            ${admin.role ? `<span class="gm-picker-role">${esc(admin.role)}</span>` : ''}
            ${admin.department ? `<span class="gm-picker-dept">🏢 ${esc(admin.department)}</span>` : ''}
        </div>`;
}

function gmPickerEnsurePortal() {
    const dd = document.getElementById('gm-picker-dropdown');
    if (dd && dd.parentElement !== document.body) {
        document.body.appendChild(dd); // escape overflow:hidden ancestors once
    }
}

function gmPositionPicker() {
    const dd = document.getElementById('gm-picker-dropdown');
    const input = document.getElementById('gm-picker-input');
    if (!dd || !input) return;

    const rect = input.getBoundingClientRect();
    const margin = 8;
    const spaceBelow = window.innerHeight - rect.bottom - margin;
    const spaceAbove = rect.top - margin;

    dd.style.left  = rect.left + 'px';
    dd.style.width = rect.width + 'px';

    if (spaceBelow < 160 && spaceAbove > spaceBelow) {
        // not enough room below -> open upward
        dd.style.top = 'auto';
        dd.style.bottom = (window.innerHeight - rect.top + 4) + 'px';
        dd.style.maxHeight = Math.min(360, spaceAbove) + 'px';
    } else {
        dd.style.bottom = 'auto';
        dd.style.top = (rect.bottom + 4) + 'px';
        dd.style.maxHeight = Math.min(360, spaceBelow) + 'px';
    }
}

function gmRepositionIfOpen() {
    const dd = document.getElementById('gm-picker-dropdown');
    if (dd && dd.classList.contains('open')) gmPositionPicker();
}
window.addEventListener('resize', gmRepositionIfOpen);
window.addEventListener('scroll', gmRepositionIfOpen, true); // capture, catches inner scrolls too

window.gmOpenPicker = function () {
    const dd = document.getElementById('gm-picker-dropdown');
    if (!dd) return;

    gmPickerEnsurePortal();
    gmPositionPicker();
    dd.classList.add('open');

    if (!GM.allAdmins || !GM.allAdmins.length) {
        gmFetchAdmins().then(() => gmFilterPicker(''));
    } else {
        gmFilterPicker(document.getElementById('gm-picker-input').value);
    }
};

window.gmFilterPicker = function (query) {
    const dd = document.getElementById('gm-picker-dropdown');
    if (!dd) return;

    const q = (query || '').trim().toLowerCase();

    const filtered = q
        ? GM.allAdmins.filter(a =>
            (a.fullName || '').toLowerCase().includes(q) ||
            (a.email || '').toLowerCase().includes(q) ||
            (a.role || '').toLowerCase().includes(q) ||
            (a.department || '').toLowerCase().includes(q))
        : GM.allAdmins;

    if (!filtered.length) {
        dd.innerHTML = `<div style="padding:16px;text-align:center;color:var(--text3);">Aucun administrateur trouvé</div>`;
        return;
    }

    dd.innerHTML = filtered.map(admin => buildPickerItem(admin, q)).join('');
};
window.gmSelectAdmin = function (email) {
    const admin = GM.allAdmins.find(a => a.email === email);
    if (!admin) return;

    GM.pickerSelected = admin;
    document.getElementById('gm-picker-value').value = admin.email;
    document.getElementById('gm-picker-input').value = admin.fullName;
    document.getElementById('gm-picker-dropdown').classList.remove('open');

    const preview = document.getElementById('gm-picker-preview');
    document.getElementById('gm-picker-preview-name').textContent = admin.fullName;
    document.getElementById('gm-picker-preview-meta').textContent =
        (admin.role || 'Admin') +
        (admin.department ? ' · 🏢 ' + admin.department : '') +
        ' · ' + admin.email;
    preview.style.display = 'flex';
};

window.gmClearPicker = function () {
    GM.pickerSelected = null;
    document.getElementById('gm-picker-value').value = '';
    document.getElementById('gm-picker-input').value = '';
    document.getElementById('gm-picker-dropdown').classList.remove('open');
    document.getElementById('gm-picker-preview').style.display = 'none';
};

// Close picker on outside click
document.addEventListener('click', function(e) {
    const wrap = document.getElementById('gm-picker-wrap');
    const dd   = document.getElementById('gm-picker-dropdown');
    if (wrap && dd && !wrap.contains(e.target) && !dd.contains(e.target)) {
        dd.classList.remove('open');
    }
});
/* ── ESC closes modal ── */
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('gm-overlay').classList.contains('open')) gmClose();
});

/* ── Inject trigger button ── */
document.addEventListener('DOMContentLoaded', function () {
    const header = document.querySelector('.du-page-header');
    if (!header) return;

    const btn = document.createElement('button');
    btn.className = 'gm-trigger-btn';
    btn.setAttribute('onclick', 'gmOpen()');
    btn.style.marginTop = '8px';
    btn.innerHTML = `
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
        </svg>
        Gérer les groupes`;

    const rightSide = header.querySelector(':scope > div:last-child, :scope > button, :scope > a');
    if (rightSide && rightSide !== header.firstElementChild) {
        if (rightSide.tagName === 'BUTTON' || rightSide.tagName === 'A') {
            const wrap = document.createElement('div');
            wrap.style.cssText = 'display:flex;flex-direction:column;align-items:flex-end;gap:6px;';
            rightSide.parentNode.insertBefore(wrap, rightSide);
            wrap.appendChild(rightSide);
            wrap.appendChild(btn);
        } else {
            rightSide.style.display = 'flex';
            rightSide.style.flexDirection = 'column';
            rightSide.style.alignItems = 'flex-end';
            rightSide.style.gap = '6px';
            rightSide.appendChild(btn);
        }
    } else {
        header.appendChild(btn);
    }
});

})();
</script>
