{{-- resources/views/backoffice/allUsers/displayUsers.blade.php --}}
@extends('shared.layouts.backoffice')

@section('content')
<style>
/* ══════════════════════════════════════════════════════
   DISPLAY USERS — Dark theme, matches backend.css
══════════════════════════════════════════════════════ */

/* ── Page wrapper ───────────────────────────────────── */
.du-wrap {
    padding: 28px 28px 60px;
    font-family: var(--font-body, 'Playfair Display', system-ui, sans-serif);
    color: var(--text, #f0f0f0);
}

/* ── Page header ────────────────────────────────────── */
.du-page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 28px;
}
.du-page-title {
    font-size: 22px;
    font-weight: 800;
    color: var(--text, #f0f0f0);
    line-height: 1.2;
    display: flex;
    align-items: center;
    gap: 10px;
}
.du-page-title-icon {
    width: 40px; height: 40px;
    border-radius: 10px;
    background: var(--gold-dim, rgba(201,168,76,.15));
    border: 1px solid rgba(201,168,76,.25);
    display: flex; align-items: center; justify-content: center;
    color: var(--gold, #c9a84c);
    flex-shrink: 0;
}
.du-page-sub {
    font-size: 13px;
    color: var(--text2, #8a8f9a);
    margin-top: 4px;
    font-weight: 400;
}

/* ── Primary action button ──────────────────────────── */
.du-btn-primary {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 20px; border-radius: 9px;
    background: linear-gradient(135deg, var(--gold, #c9a84c), var(--gold3, #a07830));
    color: #111; font-size: 13.5px; font-weight: 700;
    border: none; cursor: pointer; font-family: inherit;
    transition: all .18s; text-decoration: none; white-space: nowrap;
}
.du-btn-primary:hover {
    background: linear-gradient(135deg, var(--gold2, #e8c97a), var(--gold, #c9a84c));
    box-shadow: 0 6px 20px rgba(201,168,76,.35);
    transform: translateY(-1px);
}

/* ── KPI grid ───────────────────────────────────────── */
.du-kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 14px;
    margin-bottom: 26px;
}
.du-kpi {
    background: var(--bg2, #111316);
    border: 1px solid var(--border, rgba(255,255,255,.07));
    border-radius: 12px;
    padding: 18px 18px 16px;
    position: relative;
    overflow: hidden;
    cursor: default;
    transition: border-color .2s, transform .15s, box-shadow .2s;
}
.du-kpi:hover {
    border-color: var(--border2, rgba(255,255,255,.14));
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,.35);
}
.du-kpi::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: var(--kpi-color, var(--gold, #c9a84c));
    border-radius: 12px 12px 0 0;
}
.du-kpi-val {
    font-size: 30px; font-weight: 800;
    font-family: var(--font-mono, monospace);
    color: var(--kpi-color, var(--gold, #c9a84c));
    line-height: 1;
}
.du-kpi-lbl {
    font-size: 11px; font-weight: 600;
    text-transform: uppercase; letter-spacing: .5px;
    color: var(--text2, #8a8f9a); margin-top: 6px;
}
.du-kpi-icon {
    position: absolute; right: 14px; top: 14px;
    opacity: .07; font-size: 28px;
}

/* ── Panel ──────────────────────────────────────────── */
.du-panel {
    background: var(--bg2, #111316);
    border: 1px solid var(--border, rgba(255,255,255,.07));
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 20px;
}
.du-panel-head {
    padding: 14px 20px;
    border-bottom: 1px solid var(--border, rgba(255,255,255,.07));
    display: flex; align-items: center; justify-content: space-between;
    background: var(--bg3, #181b1f);
}
.du-panel-title {
    font-size: 13px; font-weight: 700;
    color: var(--text, #f0f0f0);
    display: flex; align-items: center; gap: 8px;
}

/* ── Toolbar ────────────────────────────────────────── */
.du-toolbar {
    display: flex; gap: 10px; align-items: center;
    padding: 16px 20px;
    border-bottom: 1px solid var(--border, rgba(255,255,255,.07));
    flex-wrap: wrap;
    background: var(--bg2, #111316);
}
.du-search-wrap {
    flex: 1; min-width: 220px; position: relative;
}
.du-search-wrap svg {
    position: absolute; left: 11px; top: 50%;
    transform: translateY(-50%);
    color: var(--text3, #4a4f5a); pointer-events: none;
}
.du-search-input {
    width: 100%; padding: 9px 12px 9px 36px;
    background: var(--bg4, #1e2228);
    border: 1px solid var(--border2, rgba(255,255,255,.12));
    border-radius: 8px;
    font-family: inherit; font-size: 13px;
    color: var(--text, #f0f0f0);
    transition: border-color .15s, box-shadow .15s;
    outline: none;
}
.du-search-input:focus {
    border-color: var(--gold, #c9a84c);
    box-shadow: 0 0 0 3px rgba(201,168,76,.12);
}
.du-search-input::placeholder { color: var(--text3, #4a4f5a); }
.du-search-btn {
    padding: 9px 16px; border-radius: 8px;
    background: var(--bg4, #1e2228);
    border: 1px solid var(--border2, rgba(255,255,255,.12));
    color: var(--text2, #8a8f9a); cursor: pointer;
    font-family: inherit; font-size: 13px;
    transition: all .15s;
    display: inline-flex; align-items: center; gap: 6px;
}
.du-search-btn:hover {
    border-color: var(--gold, #c9a84c);
    color: var(--gold, #c9a84c);
}

/* ── Filter tabs ────────────────────────────────────── */
.du-filters {
    display: flex; gap: 6px; flex-wrap: wrap;
    padding: 12px 20px;
    border-bottom: 1px solid var(--border, rgba(255,255,255,.07));
    background: var(--bg2, #111316);
}
.du-tab {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 13px; border-radius: 7px;
    font-size: 12.5px; font-weight: 600;
    border: 1px solid var(--border, rgba(255,255,255,.07));
    background: var(--bg3, #181b1f);
    color: var(--text2, #8a8f9a);
    text-decoration: none; transition: all .15s; cursor: pointer;
    font-family: inherit;
}
.du-tab:hover { border-color: var(--border2, rgba(255,255,255,.14)); color: var(--text, #f0f0f0); }
.du-tab.active {
    background: var(--gold-dim, rgba(201,168,76,.15));
    border-color: rgba(201,168,76,.4);
    color: var(--gold, #c9a84c);
}
.du-tab-cnt {
    font-size: 11px; font-family: var(--font-mono, monospace);
    background: rgba(255,255,255,.07); border-radius: 99px;
    padding: 1px 7px;
}
.du-tab.active .du-tab-cnt {
    background: rgba(201,168,76,.2);
}

/* ── Table ──────────────────────────────────────────── */
.du-table-wrap { overflow-x: auto; }
.du-table {
    width: 100%; border-collapse: collapse;
    font-size: 13px;
}
.du-table thead th {
    padding: 10px 16px;
    text-align: left; white-space: nowrap;
    font-size: 10.5px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .5px;
    color: var(--text3, #4a4f5a);
    border-bottom: 1px solid var(--border, rgba(255,255,255,.07));
    background: var(--bg3, #181b1f);
}
.du-table tbody tr {
    border-bottom: 1px solid var(--border, rgba(255,255,255,.05));
    transition: background .12s;
}
.du-table tbody tr:last-child { border-bottom: none; }
.du-table tbody tr:hover { background: var(--bg3, #181b1f); }
.du-table tbody tr.archived { opacity: .55; }
.du-table td { padding: 12px 16px; vertical-align: middle; }

/* ── Avatar ─────────────────────────────────────────── */
.du-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: linear-gradient(135deg, var(--gold, #c9a84c), var(--teal, #2dd4bf));
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 12px; color: #111;
    flex-shrink: 0; letter-spacing: .5px;
}
.du-user-name {
    font-weight: 600; font-size: 13.5px;
    color: var(--text, #f0f0f0); line-height: 1.2;
}
.du-user-sub {
    font-size: 11.5px; color: var(--text2, #8a8f9a); margin-top: 2px;
}

/* ── Status badge ───────────────────────────────────── */
.du-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px; border-radius: 99px;
    font-size: 11.5px; font-weight: 600;
}
.du-badge-dot {
    width: 6px; height: 6px; border-radius: 50%;
}
.du-badge.active {
    background: var(--green-dim, rgba(74,222,128,.12));
    border: 1px solid rgba(74,222,128,.25);
    color: var(--green, #4ade80);
}
.du-badge.active .du-badge-dot { background: var(--green, #4ade80); }
.du-badge.pending {
    background: var(--amber-dim, rgba(251,191,36,.12));
    border: 1px solid rgba(251,191,36,.25);
    color: var(--amber, #fbbf24);
}
.du-badge.pending .du-badge-dot { background: var(--amber, #fbbf24); }
.du-badge.archived {
    background: var(--red-dim, rgba(248,113,113,.12));
    border: 1px solid rgba(248,113,113,.2);
    color: var(--red, #f87171);
}
.du-badge.archived .du-badge-dot { background: var(--red, #f87171); }

/* ── Role chip ──────────────────────────────────────── */
.du-role {
    display: inline-flex; align-items: center;
    padding: 2px 9px; border-radius: 5px; margin: 2px;
    font-size: 11px; font-weight: 600;
    background: var(--blue-dim, rgba(96,165,250,.12));
    border: 1px solid rgba(96,165,250,.2);
    color: var(--blue, #60a5fa);
}

/* ── Dept chip ──────────────────────────────────────── */
.du-dept {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 12px; color: var(--text2, #8a8f9a);
}

/* ── Code ───────────────────────────────────────────── */
.du-code {
    font-family: var(--font-mono, monospace);
    font-size: 12px;
    color: var(--text2, #8a8f9a);
    background: var(--bg4, #1e2228);
    border: 1px solid var(--border, rgba(255,255,255,.07));
    padding: 2px 7px; border-radius: 5px;
}

/* ── Action buttons ─────────────────────────────────── */
.du-actions { display: flex; gap: 4px; align-items: center; }
.du-action-btn {
    width: 30px; height: 30px; border-radius: 7px;
    border: 1px solid var(--border, rgba(255,255,255,.07));
    background: var(--bg3, #181b1f);
    color: var(--text2, #8a8f9a);
    display: inline-flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all .15s; text-decoration: none;
    font-size: 12px; flex-shrink: 0;
}
.du-action-btn:hover { border-color: var(--border2, rgba(255,255,255,.18)); color: var(--text, #f0f0f0); }
.du-action-btn.view:hover   { border-color: rgba(96,165,250,.4); color: var(--blue, #60a5fa); background: var(--blue-dim, rgba(96,165,250,.08)); }
.du-action-btn.edit:hover   { border-color: rgba(201,168,76,.4); color: var(--gold, #c9a84c); background: var(--gold-dim, rgba(201,168,76,.08)); }
.du-action-btn.activate:hover { border-color: rgba(74,222,128,.4); color: var(--green, #4ade80); background: var(--green-dim, rgba(74,222,128,.08)); }
.du-action-btn.suspend:hover  { border-color: rgba(251,191,36,.4); color: var(--amber, #fbbf24); background: var(--amber-dim, rgba(251,191,36,.08)); }
.du-action-btn.perms:hover    { border-color: rgba(167,139,250,.4); color: var(--purple, #a78bfa); background: var(--purple-dim, rgba(167,139,250,.08)); }
.du-action-btn.archive:hover  { border-color: rgba(248,113,113,.4); color: var(--red, #f87171); background: var(--red-dim, rgba(248,113,113,.08)); }
.du-action-btn.restore:hover  { border-color: rgba(45,212,191,.4); color: var(--teal, #2dd4bf); background: var(--teal-dim, rgba(45,212,191,.08)); }

/* ── Tooltip ────────────────────────────────────────── */
.du-tip { position: relative; }
.du-tip::after {
    content: attr(data-tip);
    position: absolute; bottom: calc(100% + 5px); left: 50%;
    transform: translateX(-50%);
    background: var(--bg4, #1e2228);
    border: 1px solid var(--border2, rgba(255,255,255,.12));
    color: var(--text, #f0f0f0);
    font-size: 11px; font-family: inherit; font-weight: 500;
    padding: 4px 9px; border-radius: 5px; white-space: nowrap;
    opacity: 0; pointer-events: none;
    transition: opacity .12s; z-index: 100;
}
.du-tip:hover::after { opacity: 1; }

/* ── Empty state ────────────────────────────────────── */
.du-empty {
    padding: 64px 20px; text-align: center;
    color: var(--text3, #4a4f5a);
}
.du-empty-icon { font-size: 36px; margin-bottom: 12px; opacity: .4; }
.du-empty-title { font-size: 15px; font-weight: 600; color: var(--text2, #8a8f9a); }
.du-empty-sub { font-size: 13px; margin-top: 4px; }

/* ── Pagination ─────────────────────────────────────── */
.du-pagination-wrap {
    padding: 14px 20px;
    border-top: 1px solid var(--border, rgba(255,255,255,.07));
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 10px;
    background: var(--bg3, #181b1f);
}
.du-pagination-info {
    font-size: 12px; color: var(--text2, #8a8f9a);
}
.du-pagination-info strong { color: var(--text, #f0f0f0); }

/* Pagination links override */
.du-pagination-wrap .pagination {
    display: flex; gap: 4px; list-style: none; margin: 0; padding: 0;
}
.du-pagination-wrap .pagination .page-item .page-link {
    padding: 5px 11px; border-radius: 7px;
    background: var(--bg4, #1e2228);
    border: 1px solid var(--border2, rgba(255,255,255,.1));
    color: var(--text2, #8a8f9a); font-size: 12.5px;
    transition: all .15s; text-decoration: none;
}
.du-pagination-wrap .pagination .page-item.active .page-link {
    background: var(--gold-dim, rgba(201,168,76,.18));
    border-color: rgba(201,168,76,.4);
    color: var(--gold, #c9a84c);
}
.du-pagination-wrap .pagination .page-item.disabled .page-link { opacity: .3; pointer-events: none; }
.du-pagination-wrap .pagination .page-item .page-link:hover:not(.active) {
    border-color: var(--border2, rgba(255,255,255,.2));
    color: var(--text, #f0f0f0);
}

/* ── Session flash ──────────────────────────────────── */
.du-flash {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 18px; border-radius: 10px; margin-bottom: 20px;
    font-size: 13px; font-weight: 500;
    animation: duFadeIn .3s ease;
}
@keyframes duFadeIn { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:none} }
.du-flash.success {
    background: var(--green-dim, rgba(74,222,128,.12));
    border: 1px solid rgba(74,222,128,.25); color: var(--green, #4ade80);
}
.du-flash.error {
    background: var(--red-dim, rgba(248,113,113,.12));
    border: 1px solid rgba(248,113,113,.25); color: var(--red, #f87171);
}

/* ── Notification toast ─────────────────────────────── */
#du-notify {
    position: fixed; bottom: 22px; right: 22px;
    z-index: 99999; display: flex; flex-direction: column; gap: 8px;
    pointer-events: none;
}
.du-notif {
    padding: 12px 18px; border-radius: 10px; font-size: 13px; font-weight: 500;
    max-width: 340px; pointer-events: auto;
    animation: duNotifIn .25s ease;
    display: flex; align-items: center; gap: 8px;
    box-shadow: 0 8px 28px rgba(0,0,0,.5);
}
@keyframes duNotifIn { from{opacity:0;transform:translateX(20px)} to{opacity:1;transform:none} }
.du-notif.success {
    background: var(--bg4, #1e2228);
    border: 1px solid rgba(74,222,128,.3); color: var(--green, #4ade80);
}
.du-notif.error {
    background: var(--bg4, #1e2228);
    border: 1px solid rgba(248,113,113,.3); color: var(--red, #f87171);
}

/* ── Responsive ─────────────────────────────────────── */
@media (max-width: 768px) {
    .du-wrap { padding: 16px 14px 40px; }
    .du-kpi-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .du-table thead th:nth-child(3),
    .du-table td:nth-child(3) { display: none; } /* hide CIN on mobile */
}
</style>

<div class="du-wrap">

    {{-- ── Flash messages ────────────────────────────────── --}}
    @if(session('success'))
        <div class="du-flash success">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="du-flash error">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- ── Page header ────────────────────────────────────── --}}
    <div class="du-page-header">
        <div>
            <div class="du-page-title">
                <div class="du-page-title-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                Gestion des utilisateurs
            </div>
            <div class="du-page-sub">Gérez les comptes, rôles et permissions de votre organisation</div>
        </div>
        @if(auth()->user()->isSuperAdmin())
            <button class="du-btn-primary" onclick="openCreateAdminModal()">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                    <line x1="19" y1="8" x2="19" y2="14"/><line x1="16" y1="11" x2="22" y2="11"/>
                </svg>
                Nouvel administrateur
            </button>
        @endif
    </div>

    {{-- ── KPI cards ───────────────────────────────────────── --}}
    <div class="du-kpi-grid">
        <div class="du-kpi" style="--kpi-color: var(--gold, #c9a84c);">
            <div class="du-kpi-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 1 0-16 0"/></svg>
            </div>
            <div class="du-kpi-val">{{ $stats['total'] }}</div>
            <div class="du-kpi-lbl">Total</div>
        </div>
        <div class="du-kpi" style="--kpi-color: var(--green, #4ade80);">
            <div class="du-kpi-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <div class="du-kpi-val">{{ $stats['actifs'] }}</div>
            <div class="du-kpi-lbl">Actifs</div>
        </div>
        <div class="du-kpi" style="--kpi-color: var(--amber, #fbbf24);">
            <div class="du-kpi-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="du-kpi-val">{{ $stats['en_attente'] }}</div>
            <div class="du-kpi-lbl">En attente</div>
        </div>
        @if(auth()->user()->isSuperAdmin())
        <div class="du-kpi" style="--kpi-color: var(--red, #f87171);">
            <div class="du-kpi-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
            </div>
            <div class="du-kpi-val">{{ $stats['archived'] }}</div>
            <div class="du-kpi-lbl">Archivés</div>
        </div>
        @endif
        <div class="du-kpi" style="--kpi-color: var(--blue, #60a5fa);">
            <div class="du-kpi-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            </div>
            <div class="du-kpi-val">{{ $stats['admins'] }}</div>
            <div class="du-kpi-lbl">Admins</div>
        </div>
        <div class="du-kpi" style="--kpi-color: var(--purple, #a78bfa);">
            <div class="du-kpi-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <div class="du-kpi-val">{{ $stats['dept_admins'] }}</div>
            <div class="du-kpi-lbl">Admin Dept.</div>
        </div>
    </div>

    {{-- ── Main panel ──────────────────────────────────────── --}}
    <div class="du-panel">

        {{-- Filter tabs --}}
        <div class="du-filters">
            @php
                $tabs = [
                    'tous'       => ['label' => 'Tous',        'count' => $filterCounts['tous']],
                    'actifs'     => ['label' => 'Actifs',      'count' => $filterCounts['actifs']],
                    'en_attente' => ['label' => 'En attente',  'count' => $filterCounts['en_attente']],
                    'admins'     => ['label' => 'Admins',      'count' => $filterCounts['admins']],
                ];
                if(auth()->user()->isSuperAdmin()) {
                    $tabs['archived']   = ['label' => 'Archivés',    'count' => $filterCounts['archived']];
                    $tabs['dept_admins']= ['label' => 'Admin Dept.',  'count' => $filterCounts['dept_admins']];
                }
            @endphp
            @foreach($tabs as $key => $tab)
                <a href="{{ route('admin.users.index', ['filter' => $key, 'search' => request('search')]) }}"
                   class="du-tab {{ $filter === $key ? 'active' : '' }}">
                    {{ $tab['label'] }}
                    <span class="du-tab-cnt">{{ $tab['count'] }}</span>
                </a>
            @endforeach
        </div>

        {{-- Search toolbar --}}
        <form method="GET" action="{{ route('admin.users.index') }}">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <div class="du-toolbar">
                <div class="du-search-wrap">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input
                        type="text"
                        name="search"
                        class="du-search-input"
                        placeholder="Rechercher par nom, email, CIN..."
                        value="{{ request('search') }}"
                        autocomplete="off"
                    >
                </div>
                <button type="submit" class="du-search-btn">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    Rechercher
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.users.index', ['filter' => $filter]) }}" class="du-search-btn" style="color:var(--red,#f87171);">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                        Effacer
                    </a>
                @endif
            </div>
        </form>

        {{-- Table --}}
        <div class="du-table-wrap">
            <table class="du-table">
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Email</th>
                        <th>CIN</th>
                        <th>Département</th>
                        <th>Rôles</th>
                        <th>Statut</th>
                        <th style="text-align:right; padding-right:20px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="{{ $user->isArchived() ? 'archived' : '' }}">

                            {{-- User info --}}
                            <td>
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <div class="du-avatar">
                                        {{ strtoupper(substr($user->prenom,0,1).substr($user->nom,0,1)) }}
                                    </div>
                                    <div>
                                        <div class="du-user-name">{{ $user->prenom }} {{ $user->nom }}</div>
                                        @if($user->telephone)
                                            <div class="du-user-sub">{{ $user->telephone }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Email --}}
                            <td>
                                <span style="font-size:12.5px; color:var(--text2,#8a8f9a);">{{ $user->email }}</span>
                            </td>

                            {{-- CIN --}}
                            <td>
                                <span class="du-code">{{ $user->cin }}</span>
                            </td>

                            {{-- Department --}}
                            <td>
                                @if($user->department)
                                    <span class="du-dept">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
                                        {{ $user->department->name_fr }}
                                    </span>
                                @else
                                    <span style="font-size:12px; color:var(--text3,#4a4f5a);">—</span>
                                @endif
                            </td>

                            {{-- Roles --}}
                            <td>
                                @forelse($user->roles as $role)
                                    <span class="du-role">{{ $role->name_fr ?? $role->name }}</span>
                                @empty
                                    <span style="font-size:12px; color:var(--text3,#4a4f5a);">—</span>
                                @endforelse
                            </td>

                            {{-- Status --}}
                            <td>
                                @if($user->isArchived())
                                    <span class="du-badge archived">
                                        <span class="du-badge-dot"></span>Archivé
                                    </span>
                                @elseif($user->actif)
                                    <span class="du-badge active">
                                        <span class="du-badge-dot"></span>Actif
                                    </span>
                                @else
                                    <span class="du-badge pending">
                                        <span class="du-badge-dot"></span>En attente
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td>
                                <div class="du-actions" style="justify-content:flex-end;">

                                    {{-- View --}}
                                    <a href="{{ route('admin.users.show', $user) }}"
                                       class="du-action-btn view du-tip" data-tip="Voir le profil">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </a>

                                    @if(auth()->user()->canManageUser($user))
                                        {{-- Edit --}}
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                           class="du-action-btn edit du-tip" data-tip="Modifier">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                            </svg>
                                        </a>

                                        {{-- Activate / Suspend --}}
                                        @if(!$user->isArchived())
                                            @if($user->actif)
                                                <form method="POST"
                                                      action="{{ route('admin.users.suspend', $user) }}"
                                                      onsubmit="return confirm('Suspendre {{ addslashes($user->prenom . ' ' . $user->nom) }} ?')">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="du-action-btn suspend du-tip" data-tip="Suspendre">
                                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                            <circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('admin.users.activate', $user) }}">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="du-action-btn activate du-tip" data-tip="Activer">
                                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                            <polyline points="20 6 9 17 4 12"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    @endif

                                    {{-- Permissions --}}
                                    @if(auth()->user()->isSuperAdmin())
                                        <button type="button"
                                                class="du-action-btn perms du-tip"
                                                data-tip="Permissions"
                                                onclick="openPermissionsModal({{ $user->id }}, '{{ addslashes($user->prenom . ' ' . $user->nom) }}', '{{ addslashes($user->email) }}', '{{ addslashes($user->roles->first()?->name_fr ?? '—') }}', {{ $user->department_id ?? 'null' }})">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                            </svg>
                                        </button>
                                    @endif

                                    {{-- Archive / Restore --}}
                                    @if(auth()->user()->isSuperAdmin())
                                        @if(!$user->isArchived())
                                            <button type="button"
                                                    class="du-action-btn archive du-tip"
                                                    data-tip="Archiver"
                                                    onclick="openArchiveModal({{ $user->id }}, '{{ addslashes($user->prenom . ' ' . $user->nom) }}', '{{ addslashes($user->email) }}', '{{ addslashes($user->roles->first()?->name_fr ?? 'Aucun rôle') }}')">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                    <polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/>
                                                </svg>
                                            </button>
                                        @else
                                            <button type="button"
                                                    class="du-action-btn restore du-tip"
                                                    data-tip="Restaurer"
                                                    onclick="duRestoreUser({{ $user->id }}, '{{ addslashes($user->prenom . ' ' . $user->nom) }}')">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                    <polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.57"/>
                                                </svg>
                                            </button>
                                        @endif
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="du-empty">
                                    <div class="du-empty-icon">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                            <circle cx="9" cy="7" r="4"/>
                                            <line x1="23" y1="11" x2="17" y2="11"/>
                                        </svg>
                                    </div>
                                    <div class="du-empty-title">Aucun utilisateur trouvé</div>
                                    <div class="du-empty-sub">
                                        @if(request('search'))
                                            Aucun résultat pour «&nbsp;<strong>{{ request('search') }}</strong>&nbsp;»
                                        @else
                                            Il n'y a pas encore d'utilisateurs dans cette catégorie.
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
        @if($users->hasPages())
            <div class="du-pagination-wrap">
                <div class="du-pagination-info">
                    Affichage <strong>{{ $users->firstItem() }}</strong> – <strong>{{ $users->lastItem() }}</strong>
                    sur <strong>{{ $users->total() }}</strong> utilisateurs
                </div>
                {{ $users->links('pagination::bootstrap-4') }}
            </div>
        @endif

    </div>{{-- /du-panel --}}

</div>{{-- /du-wrap --}}

{{-- ── Modals ─────────────────────────────────────────── --}}
@include('backoffice.allUsers.create-admin-modal-improved')
@include('backoffice.allUsers.manage-permissions-modal-improved')
@include('backoffice.allUsers.archive-user-modal')
@include('backoffice.allUsers.manage-groups-modal'){{-- ──//zetou tawa li gestion des groups "manage-groups-modal"--}}

{{-- ── Notification container ─────────────────────────── --}}
<div id="du-notify"></div>

{{-- ── Scripts ─────────────────────────────────────────── --}}
<script>
/* ── Restore user ─────────────────────────────────────── */
async function duRestoreUser(userId, userName) {
    if (!confirm(`Restaurer le compte de ${userName} ?`)) return;

    try {
        const res = await fetch(`/admin/users/${userId}/restore`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
            }
        });
        if (res.ok) {
            duNotify(`✓ ${userName} a été restauré avec succès`, 'success');
            setTimeout(() => location.reload(), 1200);
        } else {
            duNotify('✗ Erreur lors de la restauration', 'error');
        }
    } catch (e) {
        duNotify('✗ ' + e.message, 'error');
    }
}

/* ── Notification helper ──────────────────────────────── */
function duNotify(msg, type) {
    // also works with legacy showNotification() calls from the modals
    const container = document.getElementById('du-notify');
    const el = document.createElement('div');
    el.className = `du-notif ${type}`;
    el.textContent = msg;
    container.appendChild(el);
    setTimeout(() => el.remove(), 4500);
}

/* Alias so modal JS still works */
window.showNotification = duNotify;

/* Close dropdowns on outside click */
document.addEventListener('click', () => {
    document.querySelectorAll('.ug-dropdown.open').forEach(d => d.classList.remove('open'));
});
</script>
@endsection
