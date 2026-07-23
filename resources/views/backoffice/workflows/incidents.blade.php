@extends('shared.layouts.backoffice')

@section('title', 'Instances & Incidents — Monitoring')
@section('breadcrumb', 'Workflows / Instances & Incidents')

@section('content')
<style>
    /* Thermometer SVG styling - works in both light/dark modes */
.thermo-legend svg,
.monitor-table svg {
    filter: drop-shadow(0 1px 2px rgba(0,0,0,0.1));
}

/* Pulse animation for incidents */
@keyframes pulseRed {
    0%, 100% { opacity: 0.8; }
    50% { opacity: 1; filter: drop-shadow(0 0 6px #e74c3c); }
}

/* Make incident column stand out */
.col-count {
    font-weight: 600;
}

/* Incident badge in status column */
.state-incident {
    background: rgba(231, 76, 60, 0.15) !important;
    color: #e74c3c !important;
    font-weight: 600;
    border: 1px solid rgba(231, 76, 60, 0.3);
    animation: pulseRed 2s infinite;
}
.monitor-wrap {
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.monitor-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}
.monitor-header h1 {
    font-size: 20px;
    font-weight: 700;
    color: var(--gold);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.monitor-meta {
    font-size: 12px;
    color: var(--text3, #888);
    display: flex;
    align-items: center;
    gap: 8px;
}
.monitor-actions { display: flex; gap: 8px; align-items: center; }
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
}
.kpi-card {
    background: var(--bg2, #1a1a2e);
    border: 1px solid var(--border, rgba(255,255,255,0.08));
    border-radius: var(--radius, 10px);
    padding: 14px 16px;
    transition: border-color 0.2s;
}
.kpi-card:hover { border-color: rgba(255,255,255,0.15); }
.kpi-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--text3, #888);
    margin-bottom: 4px;
}
.kpi-value {
    font-size: 28px;
    font-weight: 700;
    color: var(--text, #f0f0f0);
    line-height: 1;
}
.kpi-sub { font-size: 11px; color: var(--text3, #888); margin-top: 4px; }
.kpi-card.kpi-danger .kpi-value { color: #f87171; }
.kpi-card.kpi-warn   .kpi-value { color: #fbbf24; }
.table-card {
    background: var(--bg2, #1a1a2e);
    border: 1px solid var(--border, rgba(255,255,255,0.08));
    border-radius: var(--radius, 10px);
    overflow: hidden;
}
.table-card-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 16px;
    border-bottom: 1px solid var(--border, rgba(255,255,255,0.08));
    flex-wrap: wrap;
}
.table-card-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--text, #f0f0f0);
}
.filter-chips { display: flex; gap: 6px; margin-left: auto; flex-wrap: wrap; }
.chip {
    font-size: 11px;
    padding: 4px 12px;
    border-radius: 12px;
    cursor: pointer;
    border: 1px solid var(--border, rgba(255,255,255,0.1));
    color: var(--text3, #888);
    background: transparent;
    transition: all 0.2s;
    font-family: var(--font-body);
}
.chip:hover { color: var(--text, #f0f0f0); border-color: rgba(255,255,255,0.25); }
.chip.active {
    background: rgba(212,175,55,0.12);
    color: var(--gold, #D4AF37);
    border-color: rgba(212,175,55,0.35);
}
.monitor-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
    font-size: 13px;
}
.monitor-table thead tr {
    background: rgba(255,255,255,0.03);
}
.monitor-table th {
    text-align: left;
    padding: 10px 16px;
    font-size: 10px;
    letter-spacing: 0.07em;
    text-transform: uppercase;
    color: var(--text3, #888);
    font-weight: 500;
    border-bottom: 1px solid var(--border, rgba(255,255,255,0.06));
}
.monitor-table td {
    padding: 0 16px;
    height: 60px;
    border-top: 1px solid var(--border, rgba(255,255,255,0.05));
    vertical-align: middle;
    color: var(--text2, #ccc);
}
.monitor-table tbody tr {
    transition: background 0.15s;
    cursor: pointer;
}
.monitor-table tbody tr:hover td {
    background: rgba(255,255,255,0.025);
}
.col-name    { width: 26%; }
.col-state   { width: 12%; }
.col-dur     { width: 9%;  }
.col-count   { width: 8%; text-align: center; }
.col-thermo  { width: 14%; text-align: center; }
.col-date    { width: 13%; }
.col-actions { width: 8%; text-align: right; }
.state-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    padding: 3px 9px;
    border-radius: 10px;
    white-space: nowrap;
}
.state-active   { background: rgba(30,200,120,0.12);  color: #34d399; }
.state-incident { background: rgba(220,38,38,0.12);   color: #f87171; }
.state-completed{ background: rgba(255,255,255,0.06); color: var(--text3, #888); }
.state-suspended{ background: rgba(251,191,36,0.12);  color: #fbbf24; }
.inst-name {
    font-size: 13px;
    font-weight: 500;
    color: var(--text, #f0f0f0);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.inst-id {
    font-size: 10px;
    color: var(--text3, #888);
    font-family: 'Courier New', monospace;
    margin-top: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.stalled-tag { color: #fbbf24; font-size: 11px; margin-left: 4px; }
.inc-count-0 { color: var(--text3, #888); }
.inc-count-1 { color: #fbbf24; font-weight: 600; }
.inc-count-2 { color: #f97316; font-weight: 600; }
.inc-count-3 { color: #f87171; font-weight: 700; }
.thermo-img { height: 48px; width: auto; object-fit: contain; }
.btn-view {
    font-size: 11px;
    padding: 4px 10px;
    border-radius: 6px;
    background: rgba(255,255,255,0.05);
    border: 1px solid var(--border, rgba(255,255,255,0.12));
    color: var(--text2, #ccc);
    cursor: pointer;
    transition: all 0.2s;
    font-family: var(--font-body);
    white-space: nowrap;
}
.btn-view:hover {
    background: rgba(212,175,55,0.1);
    border-color: rgba(212,175,55,0.3);
    color: var(--gold, #D4AF37);
}
.thermo-legend {
    display: flex;
    gap: 16px;
    align-items: center;
    padding: 10px 16px;
    border-top: 1px solid var(--border, rgba(255,255,255,0.05));
    flex-wrap: wrap;
}
.legend-label { font-size: 11px; color: var(--text3, #888); }
.legend-item  { display: flex; align-items: center; gap: 6px; font-size: 11px; color: var(--text3, #888); }
.legend-item img { height: 24px; width: auto; }
.table-state {
    text-align: center;
    padding: 48px 16px;
    color: var(--text3, #888);
    font-size: 13px;
}
.table-state .state-icon { font-size: 32px; margin-bottom: 8px; }
.badge-danger {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    padding: 3px 10px;
    border-radius: 12px;
    background: rgba(220,38,38,0.15);
    color: #f87171;
    border: 1px solid rgba(220,38,38,0.3);
}
.detail-drawer {
    position: fixed;
    top: 0; right: 0; bottom: 0;
    width: 420px;
    background: var(--bg, #0f0f1f);
    border-left: 1px solid var(--border, rgba(255,255,255,0.1));
    z-index: 1000;
    transform: translateX(100%);
    transition: transform 0.3s ease;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.detail-drawer.open { transform: translateX(0); }
.drawer-header {
    padding: 16px 20px;
    border-bottom: 1px solid var(--border, rgba(255,255,255,0.08));
    display: flex;
    align-items: center;
    gap: 10px;
}
.drawer-header h3 { font-size: 15px; font-weight: 600; color: var(--gold); flex: 1; margin: 0; }
.drawer-close { background: none; border: none; color: var(--text3, #888); font-size: 18px; cursor: pointer; padding: 4px; }
.drawer-close:hover { color: var(--text, #f0f0f0); }
.drawer-body { flex: 1; overflow-y: auto; padding: 20px; }
.drawer-section { margin-bottom: 20px; }
.drawer-section-title {
    font-size: 11px; text-transform: uppercase; letter-spacing: 0.07em;
    color: var(--text3, #888); margin-bottom: 10px;
}
.drawer-kv { display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,0.04); font-size: 12px; }
.drawer-kv .dk { color: var(--text3, #888); }
.drawer-kv .dv { color: var(--text, #f0f0f0); font-family: monospace; font-size: 11px; text-align: right; max-width: 230px; overflow: hidden; text-overflow: ellipsis; }
.inc-item {
    background: rgba(220,38,38,0.08);
    border: 1px solid rgba(220,38,38,0.2);
    border-radius: 8px;
    padding: 10px 12px;
    margin-bottom: 8px;
    font-size: 12px;
}
.inc-item .inc-type { color: #f87171; font-weight: 600; margin-bottom: 4px; }
.inc-item .inc-msg  { color: var(--text2, #ccc); line-height: 1.5; }
.inc-item .inc-time { color: var(--text3, #888); font-size: 10px; margin-top: 4px; }
.task-item {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 8px;
    padding: 10px 12px;
    margin-bottom: 8px;
    font-size: 12px;
}
.task-item .task-name { color: var(--text, #f0f0f0); font-weight: 500; margin-bottom: 4px; }
.task-item .task-meta { color: var(--text3, #888); font-size: 11px; }
.drawer-overlay {
    position: fixed; inset: 0;
    background: rgba(0,0,0,0.5);
    z-index: 999;
    display: none;
}
.drawer-overlay.open { display: block; }
@media (max-width: 900px) {
    .kpi-grid { grid-template-columns: repeat(2, 1fr); }
    .col-date, .col-id { display: none; }
}
</style>

<div class="monitor-wrap">

    {{-- ── Header ── --}}
    <div class="monitor-header">
        <h1>
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
            Instances &amp; Incidents
        </h1>
        <div class="monitor-actions">
            <span class="badge-danger" id="incidentBadge" style="display:none">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                <span id="incidentBadgeText">0 incidents actifs</span>
            </span>
            <div class="monitor-meta">
                <span id="lastUpdated">—</span>
            </div>
            <button class="btn btn-sm" id="refreshBtn" onclick="loadData()" title="Actualiser">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                Actualiser
            </button>
        </div>
    </div>

    {{-- ── KPI cards ── --}}
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-label">Instances actives</div>
            <div class="kpi-value" id="kpiActive">—</div>
            <div class="kpi-sub">en cours d'exécution</div>
        </div>
        <div class="kpi-card kpi-danger">
            <div class="kpi-label">Avec incidents</div>
            <div class="kpi-value" id="kpiIncidents">—</div>
            <div class="kpi-sub">nécessitent attention</div>
        </div>
        <div class="kpi-card kpi-warn">
            <div class="kpi-label">En attente &gt; 7j</div>
            <div class="kpi-value" id="kpiStalled">—</div>
            <div class="kpi-sub">instances bloquées</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Terminées</div>
            <div class="kpi-value" id="kpiCompleted">—</div>
            <div class="kpi-sub">total historique</div>
        </div>
    </div>

    {{-- ── Table ── --}}
    <div class="table-card">
        <div class="table-card-header">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--text3,#888)"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="3" y1="15" x2="21" y2="15"></line><line x1="9" y1="3" x2="9" y2="21"></line></svg>
            <span class="table-card-title">Instances de workflow</span>
            <div class="filter-chips">
                <button class="chip active" onclick="setIncidentFilter('all', this)">Tous</button>
                <button class="chip" onclick="setIncidentFilter('active', this)">Actifs</button>
                <button class="chip" onclick="setIncidentFilter('incidents', this)">Incidents</button>
                <button class="chip" onclick="setIncidentFilter('completed', this)">Terminés</button>
            </div>
        </div>

        <div id="monitorTableWrap">
            <div class="table-state" id="monitorLoadingState">
                <div class="state-icon">⏳</div>
                Chargement des instances...
            </div>
            <table class="monitor-table" id="monitorTable" style="display:none">
                <thead>
                    <tr>
                        <th class="col-name">Workflow / Instance</th>
                        <th class="col-state">État</th>
                        <th class="col-dur">Durée</th>
                        <th class="col-count">Incidents</th>
                        <th class="col-thermo">Niveau</th>
                        <th class="col-date">Démarré le</th>
                        <th class="col-actions"></th>
                    </tr>
                </thead>
                <tbody id="monitorTableBody"></tbody>
            </table>
            <div class="table-state" id="monitorEmptyState" style="display:none">
                <div class="state-icon">✅</div>
                Aucune instance trouvée
            </div>
        </div>

        {{-- Thermometer legend ── --}}
<div class="thermo-legend">
    <span class="legend-label">Légende :</span>
    <span class="legend-item">
        <svg width="18" height="36" viewBox="0 0 18 36">
            <rect x="5.5" y="3" width="7" height="22" rx="3.5" fill="rgba(255,255,255,0.08)" stroke="#27ae60" stroke-width="0.8"/>
            <circle cx="9" cy="31" r="4.5" fill="#27ae60" stroke="rgba(255,255,255,0.2)" stroke-width="0.6"/>
        </svg>
        0 incident
    </span>
    <span class="legend-item">
        <svg width="18" height="36" viewBox="0 0 18 36">
            <rect x="5.5" y="3" width="7" height="22" rx="3.5" fill="rgba(255,255,255,0.08)" stroke="#e74c3c" stroke-width="0.8"/>
            <rect x="5.5" y="15" width="7" height="10" fill="#e74c3c" opacity="0.8"/>
            <circle cx="9" cy="31" r="4.5" fill="#e74c3c" stroke="rgba(255,255,255,0.2)" stroke-width="0.6"/>
        </svg>
        1 incident
    </span>
    <span class="legend-item">
        <svg width="18" height="36" viewBox="0 0 18 36">
            <rect x="5.5" y="3" width="7" height="22" rx="3.5" fill="rgba(255,255,255,0.08)" stroke="#c0392b" stroke-width="0.8"/>
            <rect x="5.5" y="10" width="7" height="15" fill="#c0392b" opacity="0.85"/>
            <circle cx="9" cy="31" r="4.5" fill="#c0392b" stroke="rgba(255,255,255,0.2)" stroke-width="0.6"/>
        </svg>
        2 incidents
    </span>
    <span class="legend-item">
        <svg width="18" height="36" viewBox="0 0 18 36">
            <rect x="5.5" y="3" width="7" height="22" rx="3.5" fill="rgba(255,255,255,0.08)" stroke="#e74c3c" stroke-width="1"/>
            <rect x="5.5" y="3" width="7" height="22" fill="#e74c3c" opacity="0.9"/>
            <circle cx="9" cy="31" r="4.5" fill="#e74c3c" stroke="#e74c3c" stroke-width="1"/>
            <circle cx="8" cy="30" r="1" fill="rgba(255,255,255,0.4)"/>
        </svg>
        3+ (critique)
    </span>
</div>
    </div>
</div>

{{-- ── Detail Drawer ── --}}
<div class="drawer-overlay" id="drawerOverlay" onclick="closeDrawer()"></div>
<div class="detail-drawer" id="detailDrawer">
    <div class="drawer-header">
        <h3 id="drawerTitle">Détails de l'instance</h3>
        <button class="drawer-close" onclick="closeDrawer()">✕</button>
    </div>
    <div class="drawer-body" id="drawerBody">
        <div class="table-state">Chargement...</div>
    </div>
</div>

@verbatim
<script>
let currentFilter = 'all';
let allRows = [];

function thermoSrc(level) {
    if (!window._thermoSrcs || !Array.isArray(window._thermoSrcs)) return '';
    const map = { 1: window._thermoSrcs[0], 2: window._thermoSrcs[1], 3: window._thermoSrcs[2], 4: window._thermoSrcs[3] };
    return map[level] || map[4] || '';
}

function statePill(status) {
    const cfg = {
        active:    { cls: 'state-active',    icon: '●', label: 'Actif' },
        incident:  { cls: 'state-incident',  icon: '⚠', label: 'Incident' },
        completed: { cls: 'state-completed', icon: '✓', label: 'Terminé' },
        suspended: { cls: 'state-suspended', icon: '⏸', label: 'Suspendu' },
    };
    const c = cfg[status] || cfg.active;
    return `<span class="state-pill ${c.cls}">${c.icon} ${c.label}</span>`;
}

function incCount(n) {
    if (n === 0) return `<span class="inc-count-0">0</span>`;
    if (n === 1) return `<span class="inc-count-1" style="color: #e74c3c !important; font-weight: 700;">${n}</span>`;
    if (n === 2) return `<span class="inc-count-2" style="color: #c0392b !important; font-weight: 700;">${n}</span>`;
    return `<span class="inc-count-3" style="color: #e74c3c !important; font-weight: 800; font-size: 16px;">${n}</span>`;
}

function buildRow(r) {
    const stalledTag = r.isStalled ? `<span class="stalled-tag" title="Instance bloquée > 7 jours">⚠</span>` : '';
    return `
        <tr onclick="openDrawer('${r.id}')">
            <td>
                <div class="inst-name" title="${r.processName}">${r.processName}</div>
                <div class="inst-id">${r.processKey} · #${r.id.substring(0, 8)}</div>
            </td>
            <td>${statePill(r.status)}</td>
            <td style="color:var(--text2,#ccc)">${r.since || '—'}${stalledTag}</td>
            <td style="text-align:center;font-size:15px">${incCount(r.incidentCount)}</td>
            <td style="text-align:center">${thermoHtml(r.status, r.incidentCount)}</td>

            <td style="color:var(--text3,#888);font-size:12px">${r.startFormatted}</td>
            <td style="text-align:right">
                <button class="btn-view" onclick="event.stopPropagation(); openDrawer('${r.id}')">Voir</button>
            </td>
        </tr>`;
}

function renderMonitorTable() {
    const filtered = currentFilter === 'all'
        ? allRows
        : allRows.filter(r => {
            if (currentFilter === 'active')    return r.status === 'active';
            if (currentFilter === 'incidents') return r.status === 'incident';
            if (currentFilter === 'completed') return r.status === 'completed';
            return true;
        });

    const tbody = document.getElementById('monitorTableBody');
    const table = document.getElementById('monitorTable');
    const empty = document.getElementById('monitorEmptyState');

    if (filtered.length === 0) {
        table.style.display = 'none';
        empty.style.display = 'block';
    } else {
        empty.style.display = 'none';
        table.style.display = 'table';
        tbody.innerHTML = filtered.map(buildRow).join('');
    }
}

function setIncidentFilter(filter, btn) {
    currentFilter = filter;
    document.querySelectorAll('.filter-chips .chip').forEach(c => c.classList.remove('active'));
    if (btn && btn.classList) btn.classList.add('active');
    renderMonitorTable();
}

async function loadData() {
    document.getElementById('monitorLoadingState').style.display = 'block';
    document.getElementById('monitorTable').style.display = 'none';
    document.getElementById('monitorEmptyState').style.display = 'none';

    const btn = document.getElementById('refreshBtn');
    btn.disabled = true;

    try {
        const res = await fetch('/admin/workflows/monitoring/incidents/data?filter=all');
        const data = await res.json();

        if (!data.success) throw new Error(data.error || 'Erreur serveur');

        allRows = data.rows || [];

        document.getElementById('kpiActive').textContent    = data.kpis.active;
        document.getElementById('kpiIncidents').textContent = data.kpis.incidents;
        document.getElementById('kpiStalled').textContent   = data.kpis.stalled;
        document.getElementById('kpiCompleted').textContent = data.kpis.completed_week;

        const badge = document.getElementById('incidentBadge');
        const badgeTxt = document.getElementById('incidentBadgeText');
        if (data.kpis.incidents > 0) {
            badge.style.display = 'inline-flex';
            badgeTxt.textContent = `${data.kpis.incidents} incident${data.kpis.incidents > 1 ? 's' : ''} actif${data.kpis.incidents > 1 ? 's' : ''}`;
        } else {
            badge.style.display = 'none';
        }

        document.getElementById('lastUpdated').textContent =
            'Actualisé à ' + new Date().toLocaleTimeString('fr-TN', { hour: '2-digit', minute: '2-digit' });

        document.getElementById('monitorLoadingState').style.display = 'none';
        renderMonitorTable();

    } catch (err) {
        console.error('[Monitor]', err);
        document.getElementById('monitorLoadingState').innerHTML =
            `<div class="state-icon">❌</div><div>Erreur : ${err.message}</div>`;
    } finally {
        btn.disabled = false;
    }
}

async function openDrawer(instanceId) {
    document.getElementById('drawerOverlay').classList.add('open');
    document.getElementById('detailDrawer').classList.add('open');
    document.getElementById('drawerTitle').textContent = `Instance · #${instanceId.substring(0,8)}`;
    document.getElementById('drawerBody').innerHTML = '<div class="table-state"><div class="state-icon">⏳</div>Chargement...</div>';

    try {
        const res = await fetch(`/admin/workflows/monitoring/incidents/${instanceId}/detail`);
        const data = await res.json();
        if (!data.success) throw new Error(data.error);

        const inst  = data.instance  || {};
        const incs  = data.incidents || [];
        const tasks = data.tasks     || [];

        let html = `
            <div class="drawer-section">
                <div class="drawer-section-title">Informations</div>
                <div class="drawer-kv"><span class="dk">ID</span><span class="dv">${instanceId}</span></div>
                <div class="drawer-kv"><span class="dk">Processus</span><span class="dv">${inst.processDefinitionKey || '—'}</span></div>
                <div class="drawer-kv"><span class="dk">Business Key</span><span class="dv">${inst.businessKey || '—'}</span></div>
                <div class="drawer-kv"><span class="dk">État</span><span class="dv">${inst.state || '—'}</span></div>
                <div class="drawer-kv"><span class="dk">Démarré le</span><span class="dv">${inst.startTime ? new Date(inst.startTime).toLocaleString('fr-TN') : '—'}</span></div>
                ${inst.endTime ? `<div class="drawer-kv"><span class="dk">Terminé le</span><span class="dv">${new Date(inst.endTime).toLocaleString('fr-TN')}</span></div>` : ''}
            </div>`;

        if (incs.length > 0) {
            html += `<div class="drawer-section"><div class="drawer-section-title">Incidents (${incs.length})</div>`;
            incs.forEach(inc => {
                html += `<div class="inc-item">
                    <div class="inc-type">${inc.incidentType || 'failedJob'}</div>
                    <div class="inc-msg">${inc.incidentMessage || 'Aucun message'}</div>
                    <div class="inc-time">${inc.incidentTimestamp ? new Date(inc.incidentTimestamp).toLocaleString('fr-TN') : ''}</div>
                </div>`;
            });
            html += `</div>`;
        }

        if (tasks.length > 0) {
            html += `<div class="drawer-section"><div class="drawer-section-title">Tâches en cours (${tasks.length})</div>`;
            tasks.forEach(task => {
                html += `<div class="task-item">
                    <div class="task-name">${task.name || task.taskDefinitionKey || '—'}</div>
                    <div class="task-meta">Assigné à : ${task.assignee || 'Non assigné'}</div>
                </div>`;
            });
            html += `</div>`;
        }

        if (incs.length === 0 && tasks.length === 0) {
            html += `<div class="table-state"><div class="state-icon">✅</div>Aucun incident ni tâche active</div>`;
        }

        document.getElementById('drawerBody').innerHTML = html;

    } catch (err) {
        document.getElementById('drawerBody').innerHTML =
            `<div class="table-state"><div class="state-icon">❌</div>Erreur : ${err.message}</div>`;
    }
}

function closeDrawer() {
    document.getElementById('drawerOverlay').classList.remove('open');
    document.getElementById('detailDrawer').classList.remove('open');
}

document.addEventListener('DOMContentLoaded', loadData);

function thermoHtml(status, incidentCount) {
    // Fill height and color based on incident count
    let fillColor, fillY, fillHeight, bulbColor, borderColor;

    if (status === 'completed' && incidentCount === 0) {
        // Completed — muted grey thermometer (works in both modes)
        return `<svg width="22" height="44" viewBox="0 0 22 44" aria-label="Terminé: 0 incidents" style="display:block;margin:auto">
            <rect x="7.5" y="4" width="7" height="26" rx="3.5" fill="rgba(128,128,128,0.15)" stroke="rgba(128,128,128,0.3)" stroke-width="0.8"/>
            <circle cx="11" cy="37" r="5.5" fill="rgba(128,128,128,0.2)" stroke="rgba(128,128,128,0.3)" stroke-width="0.8"/>
            <line x1="15.5" y1="13" x2="18" y2="13" stroke="rgba(128,128,128,0.2)" stroke-width="0.8"/>
            <line x1="15.5" y1="17" x2="18" y2="17" stroke="rgba(128,128,128,0.2)" stroke-width="0.8"/>
            <line x1="15.5" y1="21" x2="18" y2="21" stroke="rgba(128,128,128,0.2)" stroke-width="0.8"/>
            <line x1="15.5" y1="25" x2="18" y2="25" stroke="rgba(128,128,128,0.2)" stroke-width="0.8"/>
        </svg>`;
    }

    // Set colors based on incident count (RED for incidents)
    if (incidentCount === 0) {
        fillColor = '#27ae60';  // Green
        bulbColor = '#27ae60';
        fillY = 30;
        fillHeight = 0;
        borderColor = 'rgba(39,174,96,0.3)';
    } else if (incidentCount === 1) {
        fillColor = '#e74c3c';  // RED for incidents
        bulbColor = '#e74c3c';
        fillY = 22;
        fillHeight = 8;
        borderColor = 'rgba(231,76,60,0.3)';
    } else if (incidentCount === 2) {
        fillColor = '#c0392b';  // Darker RED
        bulbColor = '#c0392b';
        fillY = 16;
        fillHeight = 14;
        borderColor = 'rgba(192,57,43,0.3)';
    } else {
        fillColor = '#e74c3c';  // Bright RED for 3+ incidents
        bulbColor = '#e74c3c';
        fillY = 4;
        fillHeight = 26;
        borderColor = 'rgba(231,76,60,0.4)';
    }

    const tickOpacity = incidentCount === 0 ? '0.15' : '0.3';
    const uid = 'cp_' + Math.random().toString(36).slice(2, 7);

    // Add pulse animation for incidents
    const pulseClass = incidentCount >= 1 ? 'incident-pulse' : '';
    const pulseStyle = incidentCount >= 1 ? `style="filter: drop-shadow(0 0 4px ${fillColor}); animation: pulseRed 1.5s infinite;"` : '';

    return `<svg width="22" height="44" viewBox="0 0 22 44" style="display:block;margin:auto"
            aria-label="${incidentCount} incident(s)">
        <defs>
            <clipPath id="${uid}">
                <rect x="7.5" y="4" width="7" height="26" rx="3.5"/>
            </clipPath>
        </defs>
        <rect x="7.5" y="4" width="7" height="26" rx="3.5"
              fill="rgba(255,255,255,0.08)"
              stroke="${borderColor}" stroke-width="1"/>
        ${fillHeight > 0 ? `<rect x="7.5" y="${fillY}" width="7" height="${fillHeight + 4}"
              fill="${fillColor}" clip-path="url(#${uid})" opacity="0.9"/>` : ''}
        <circle cx="11" cy="37" r="5.5" fill="${bulbColor}"
                stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
        <circle cx="9.5" cy="35.5" r="1.2" fill="rgba(255,255,255,0.4)"/>
        <line x1="15.5" y1="13" x2="18" y2="13" stroke="rgba(255,255,255,${tickOpacity})" stroke-width="0.8"/>
        <line x1="15.5" y1="17" x2="18" y2="17" stroke="rgba(255,255,255,${tickOpacity})" stroke-width="0.8"/>
        <line x1="15.5" y1="21" x2="18" y2="21" stroke="rgba(255,255,255,${tickOpacity})" stroke-width="0.8"/>
        <line x1="15.5" y1="25" x2="18" y2="25" stroke="rgba(255,255,255,${tickOpacity})" stroke-width="0.8"/>
    </svg>`;
}
// Inject pulse animations once
(function() {
    if (document.getElementById('monitor-pulse-styles')) return;
    const s = document.createElement('style');
    s.id = 'monitor-pulse-styles';
    s.textContent = `
        @keyframes pulseRed {
            0%,100% { box-shadow:0 0 8px rgba(248,113,113,0.4); }
            50%      { box-shadow:0 0 18px rgba(248,113,113,0.8); }
        }
        @keyframes pulseOrange {
            0%,100% { box-shadow:0 0 6px rgba(249,115,22,0.3); }
            50%      { box-shadow:0 0 14px rgba(249,115,22,0.7); }
        }
        @keyframes pulseAmber {
            0%,100% { box-shadow:0 0 6px rgba(251,191,36,0.25); }
            50%      { box-shadow:0 0 12px rgba(251,191,36,0.6); }
        }
    `;
    document.head.appendChild(s);
})();
</script>
@endverbatim

@endsection
