@extends('shared.layouts.backoffice')

@section('title', 'Moteur de Workflows — ' . ($departmentName ?? 'Dynamique'))
@section('breadcrumb', 'Workflows ' . ($departmentName ?? 'Dynamique'))

@section('content')

<style>

* ── Personalized Task Modal ── */
.personalized-task-container {
    background: var(--bg3);
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 12px;
}

.personalized-task-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--border);
}

.personalized-task-icon {
    font-size: 24px;
}

.personalized-task-info {
    flex: 1;
}

.personalized-task-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 2px;
}

.personalized-task-id {
    font-size: 11px;
    color: var(--text3);
}

.task-action-button {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    font-size: 12px;
    transition: opacity 0.2s;
}

.task-action-button:hover {
    opacity: 0.85;
}
/* ════════════════════════════════════════════
   WORKFLOW ENGINE — DESIGN SYSTEM (from Music et Danse)
════════════════════════════════════════════ */

/* ── KPIs ── */
.wfe-kpi-row {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 12px;
    margin-bottom: 22px;
}
@media (max-width: 1100px) { .wfe-kpi-row { grid-template-columns: repeat(3,1fr); } }
@media (max-width: 700px) { .wfe-kpi-row { grid-template-columns: repeat(2,1fr); } }

.wfe-kpi {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 14px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: border-color .2s;
}
.wfe-kpi:hover { border-color: var(--border2); }
.wfe-kpi-icon {
    width: 38px; height: 38px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 17px; flex-shrink: 0;
}
.wfe-kpi-val { font-size: 20px; font-weight: 900; font-family: var(--font-mono); line-height: 1; }
.wfe-kpi-lbl { font-size: 10.5px; color: var(--text3); text-transform: uppercase; letter-spacing: 0.6px; margin-top: 3px; }
.wfe-kpi-delta { font-size: 10px; font-family: var(--font-mono); font-weight: 700; margin-top: 3px; }
.wfe-kpi-skeleton { height: 22px; background: var(--bg4); border-radius: 4px; animation: wfe-shimmer 1.4s infinite; }

@keyframes wfe-shimmer { 0% { opacity: .5 } 50% { opacity: 1 } 100% { opacity: .5 } }

/* ── IA Alert Banner ── */
.wfe-ia-banner {
    background: linear-gradient(135deg, rgba(201,168,76,0.07), rgba(96,165,250,0.05));
    border: 1px solid rgba(201,168,76,0.22);
    border-radius: var(--radius);
    padding: 14px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 20px;
    position: relative;
    overflow: hidden;
}
.wfe-ia-banner::after {
    content: '🤖';
    position: absolute; right: 18px; top: 50%;
    transform: translateY(-50%);
    font-size: 48px; opacity: 0.06; pointer-events: none;
}
.wfe-ia-orb {
    width: 40px; height: 40px; border-radius: 10px;
    background: var(--gold-dim); border: 1px solid rgba(201,168,76,0.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; flex-shrink: 0;
    animation: wfe-pulse 3s ease-in-out infinite;
}
@keyframes wfe-pulse {
    0%,100% { box-shadow: 0 0 0 0 rgba(201,168,76,0.3); }
    50%      { box-shadow: 0 0 0 8px rgba(201,168,76,0); }
}
.wfe-ia-insights { flex: 1; }
.wfe-ia-title { font-size: 13px; font-weight: 700; color: var(--text); margin-bottom: 4px; display:flex; align-items:center; gap:8px; }
.wfe-ia-items { display: flex; flex-wrap: wrap; gap: 8px; }
.wfe-ia-chip {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 11px; border-radius: 20px;
    font-size: 11px; font-weight: 600; cursor: pointer;
    transition: opacity 0.15s;
}
.wfe-ia-chip:hover { opacity: 0.8; }

/* ── Live badge ── */
.wfe-live {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 10px; font-weight: 700; color: var(--green);
    padding: 2px 8px; background: var(--green-dim); border-radius: 20px;
}
.wfe-live-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: var(--green);
    animation: wfe-pulse 2s infinite;
}

/* ── Main layout ── */
.wfe-shell {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 18px;
    align-items: start;
}
@media (max-width: 1050px) { .wfe-shell { grid-template-columns: 1fr; } }

/* ── Filter & sort bar ── */
.wfe-filterbar {
    background: var(--bg2); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 12px 16px;
    display: flex; align-items: center; gap: 10px;
    flex-wrap: wrap; margin-bottom: 16px;
}
.wfe-filter-tabs { display: flex; gap: 0; }
.wfe-ftab {
    padding: 6px 14px; font-size: 12px; font-weight: 600;
    color: var(--text3); cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all 0.15s; user-select: none; white-space: nowrap;
}
.wfe-ftab:hover { color: var(--text2); }
.wfe-ftab.active { color: var(--gold); border-bottom-color: var(--gold); }
.wfe-search {
    flex: 1; min-width: 160px;
    background: var(--bg3); border: 1px solid var(--border2);
    border-radius: var(--radius-sm); padding: 6px 11px;
    font-size: 12px; color: var(--text); font-family: var(--font-body); outline: none;
}
.wfe-search:focus { border-color: var(--gold); }
.wfe-search::placeholder { color: var(--text3); }

/* ── Process cards grid ── */
.wfe-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 14px;
}

/* ── View toggle (Cards / Table) ── */
.wfe-view-toggle {
    display: flex;
    gap: 2px;
    background: var(--bg3);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 2px;
    flex-shrink: 0;
}
.wfe-view-btn {
    padding: 6px 11px;
    font-size: 11.5px;
    font-weight: 600;
    color: var(--text3);
    background: transparent;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.15s;
    display: flex; align-items: center; gap: 5px;
    white-space: nowrap;
}
.wfe-view-btn:hover:not(.active) { color: var(--text2); }
.wfe-view-btn.active { background: var(--gold-dim); color: var(--gold); }

/* ── Table view ── */
.wfe-table-wrap {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow-x: auto;
}
.wfe-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
.wfe-table thead th {
    text-align: left;
    font-size: 10px; font-weight: 800;
    text-transform: uppercase; letter-spacing: 0.06em;
    color: var(--text3);
    padding: 10px 14px;
    border-bottom: 1px solid var(--border);
    background: var(--bg3);
    white-space: nowrap;
}
.wfe-table tbody tr {
    border-bottom: 1px solid var(--border);
    cursor: pointer;
    transition: background 0.15s;
}
.wfe-table tbody tr:last-child { border-bottom: none; }
.wfe-table tbody tr:hover { background: var(--bg3); }
.wfe-table td { padding: 10px 14px; vertical-align: middle; color: var(--text); }
.wfe-table-proc { display: flex; align-items: center; gap: 10px; min-width: 0; }
.wfe-table-icon {
    width: 30px; height: 30px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; flex-shrink: 0;
}
.wfe-table-name { font-weight: 700; font-size: 12.5px; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.wfe-table-key { font-size: 10px; color: var(--text3); font-family: var(--font-mono); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.wfe-table-progress-track {
    width: 64px; height: 6px; border-radius: 4px;
    background: var(--bg4); overflow: hidden;
    display: inline-block; vertical-align: middle; margin-right: 6px;
}
.wfe-table-progress-fill { height: 100%; border-radius: 4px; }
.wfe-table-actions { display: flex; gap: 6px; }
.wfe-table-actions button {
    padding: 4px 9px; font-size: 11px; font-weight: 600;
    border-radius: 5px; border: 1px solid var(--border);
    background: var(--bg3); color: var(--text2); cursor: pointer;
    transition: all 0.15s;
}
.wfe-table-actions button:hover { border-color: var(--gold); color: var(--gold); }
.wfe-table-empty-row td { text-align: center; padding: 34px 14px; color: var(--text3); font-size: 12px; }

/* ── Process card ── */
.wfe-card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    cursor: pointer;
    transition: border-color 0.2s, transform 0.15s;
}
.wfe-card:hover { border-color: var(--border2); transform: translateY(-1px); }
.wfe-card.selected { border-color: var(--gold); }

.wfe-card-top {
    padding: 14px 16px 12px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: flex-start; gap: 11px;
}
.wfe-card-icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 19px; flex-shrink: 0;
}
.wfe-card-meta { flex: 1; min-width: 0; }
.wfe-card-num {
    font-size: 9.5px; font-family: var(--font-mono); font-weight: 700;
    color: var(--text3); text-transform: uppercase; letter-spacing: 0.8px;
    margin-bottom: 3px;
}
.wfe-card-title { font-size: 13px; font-weight: 700; color: var(--text); line-height: 1.35; }
.wfe-card-key {
    font-size: 10px; font-family: var(--font-mono); color: var(--text3);
    margin-top: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.wfe-card-output {
    font-size: 11px; color: var(--text3); margin-top: 4px;
}

/* ── Progress steps row ── */
.wfe-steps-row {
    padding: 10px 16px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: 0;
    overflow-x: auto;
}
.wfe-step {
    display: flex; align-items: center; gap: 0;
    flex-shrink: 0;
}
.wfe-step-dot {
    width: 22px; height: 22px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 9px; font-weight: 800;
    border: 1.5px solid var(--border2);
    background: var(--bg3);
    color: var(--text3);
    transition: all 0.2s;
    flex-shrink: 0;
    cursor: pointer;
    position: relative;
}
.wfe-step-dot.done  { background: var(--green-dim);  border-color: var(--green);  color: var(--green); }
.wfe-step-dot.active { background: var(--gold-dim); border-color: var(--gold); color: var(--gold); animation: wfe-stepglow 2s ease-in-out infinite; }
.wfe-step-dot.blocked { background: var(--red-dim); border-color: var(--red); color: var(--red); }

@keyframes wfe-stepglow {
    0%,100% { box-shadow: 0 0 0 0 rgba(201,168,76,0.4); }
    50%      { box-shadow: 0 0 0 4px rgba(201,168,76,0); }
}

.wfe-step-line {
    width: 18px; height: 2px;
    background: var(--border);
    flex-shrink: 0;
}
.wfe-step-line.done { background: var(--green); }
.wfe-step-line.active { background: linear-gradient(90deg, var(--gold), var(--border)); }

/* ── Stats + progress bar ── */
.wfe-card-stats {
    padding: 10px 16px;
    display: flex; gap: 0;
}
.wfe-cstat {
    flex: 1; text-align: center;
    padding: 4px 0;
    border-right: 1px solid var(--border);
}
.wfe-cstat:last-child { border-right: none; }
.wfe-cstat-val { font-size: 15px; font-weight: 900; font-family: var(--font-mono); }
.wfe-cstat-lbl { font-size: 9.5px; color: var(--text3); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px; }

.wfe-progress-wrap {
    padding: 0 16px 10px;
    display: flex; flex-direction: column; gap: 4px;
}
.wfe-progress-row { display: flex; align-items: center; gap: 8px; }
.wfe-progress-track {
    flex: 1; height: 4px; background: var(--bg4); border-radius: 2px; overflow: hidden;
}
.wfe-progress-fill { height: 100%; border-radius: 2px; transition: width 0.6s ease; }
.wfe-progress-pct { font-size: 10px; font-family: var(--font-mono); font-weight: 700; min-width: 28px; text-align: right; }

/* ── Card footer ── */
.wfe-card-foot {
    padding: 10px 16px;
    border-top: 1px solid var(--border);
    display: flex; align-items: center; gap: 7px;
    flex-wrap: wrap;
}
.wfe-foot-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 11px; border-radius: var(--radius-sm);
    font-size: 11px; font-weight: 600;
    cursor: pointer; border: 1px solid var(--border2);
    background: var(--bg3); color: var(--text2);
    font-family: var(--font-body); transition: all 0.15s; white-space: nowrap;
}
.wfe-foot-btn:hover { background: var(--bg4); color: var(--text); }
.wfe-foot-btn.gold { background: var(--gold-dim); border-color: rgba(201,168,76,0.3); color: var(--gold); }
.wfe-foot-btn.gold:hover { background: rgba(201,168,76,0.2); }
.wfe-foot-ia {
    margin-left: auto;
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 10px; font-weight: 700;
    padding: 3px 9px; border-radius: 20px;
    background: var(--purple-dim); color: var(--purple);
    border: 1px solid rgba(167,139,250,0.2);
}

/* ── IA tag on card ── */
.wfe-ia-alert-tag {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 9.5px; font-weight: 700; padding: 2px 8px; border-radius: 10px;
}

/* ════ RIGHT SIDEBAR ════ */
.wfe-sidebar { display: flex; flex-direction: column; gap: 16px; position: sticky; top: 76px; }

.wfe-sb-panel {
    background: var(--bg2); border: 1px solid var(--border);
    border-radius: var(--radius); overflow: hidden;
}
.wfe-sb-head {
    padding: 12px 16px; border-bottom: 1px solid var(--border);
    font-size: 12px; font-weight: 700; color: var(--text);
    display: flex; align-items: center; justify-content: space-between; gap: 8px;
}
.wfe-sb-body { padding: 14px 16px; }

/* Queue list */
.wfe-queue-item {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 10px 14px; border-bottom: 1px solid var(--border);
    cursor: pointer; transition: background 0.15s;
}
.wfe-queue-item:last-child { border-bottom: none; }
.wfe-queue-item:hover { background: var(--bg3); }
.wfe-queue-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; margin-top: 4px; }
.wfe-queue-body { flex: 1; min-width: 0; }
.wfe-queue-name { font-size: 12px; font-weight: 600; color: var(--text); }
.wfe-queue-proc { font-size: 10.5px; color: var(--text3); margin-top: 1px; }
.wfe-queue-time { font-size: 10px; font-family: var(--font-mono); color: var(--text3); flex-shrink: 0; }

/* IA suggestions */
.wfe-ia-sugg {
    display: flex; flex-direction: column; gap: 8px;
    padding: 12px 14px;
}
.wfe-sugg-item {
    display: flex; align-items: flex-start; gap: 9px;
    padding: 9px 11px; border-radius: var(--radius-sm);
    background: var(--bg3); border: 1px solid var(--border);
    cursor: pointer; transition: border-color 0.15s;
}
.wfe-sugg-item:hover { border-color: var(--gold); }
.wfe-sugg-icon { font-size: 15px; flex-shrink: 0; }
.wfe-sugg-text { font-size: 11.5px; color: var(--text2); line-height: 1.45; flex: 1; }
.wfe-sugg-action { font-size: 10px; color: var(--gold); font-weight: 700; margin-top: 3px; }

/* ════ TABLES ════ */
.wfe-tbl {
    width: 100%; border-collapse: collapse; font-size: 12.5px;
}
.wfe-tbl th {
    padding: 9px 12px; text-align: left;
    font-size: 10.5px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .5px;
    color: var(--text3); border-bottom: 1px solid var(--border);
    white-space: nowrap;
}
.wfe-tbl td {
    padding: 10px 12px; border-bottom: 1px solid var(--border);
    color: var(--text2); vertical-align: middle;
}
.wfe-tbl tr:last-child td { border-bottom: none; }
.wfe-tbl tr:hover td { background: var(--bg3); }

/* Action buttons */
.wfe-act-btn {
    padding: 4px 10px; border-radius: 5px;
    font-size: 11px; font-weight: 700;
    font-family: var(--font-body);
    border: 1px solid; cursor: pointer;
    transition: all .15s; white-space: nowrap;
}
.wfe-act-approve { background: var(--green-dim); border-color: rgba(74,222,128,.4); color: var(--green); }
.wfe-act-approve:hover { background: var(--green); color: #111; }
.wfe-act-reject { background: var(--red-dim); border-color: rgba(248,113,113,.4); color: var(--red); }
.wfe-act-reject:hover { background: var(--red); color: #fff; }
.wfe-act-view { background: var(--bg4); border-color: var(--border2); color: var(--text2); }
.wfe-act-view:hover { border-color: var(--border2); color: var(--text); }

/* ════ MODAL TABS ════ */
.wfe-modal-tabs {
    display: flex; gap: 0;
    border-bottom: 1px solid var(--border);
    margin-bottom: 16px;
}
.wfe-mtab {
    padding: 10px 16px; font-size: 12.5px; font-weight: 600;
    color: var(--text3); cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all .15s;
}
.wfe-mtab:hover { color: var(--text2); }
.wfe-mtab.active { color: var(--gold); border-bottom-color: var(--gold); }

/* ── Start/Approve/Reject modal fields ── */
.wfe-field { margin-bottom: 14px; }
.wfe-field-label {
    display: block; font-size: 10.5px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .5px;
    color: var(--text3); margin-bottom: 5px;
}
.wfe-field-input {
    width: 100%; padding: 9px 12px;
    background: var(--bg3); border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    color: var(--text); font-family: var(--font-body); font-size: 13px;
    transition: border-color .18s;
}
.wfe-field-input:focus { outline: none; border-color: var(--gold); }

/* ── Action banners ── */
.wfe-action-banner {
    padding: 16px; border-radius: var(--radius-sm);
    margin-bottom: 14px; display: flex;
    align-items: center; gap: 12px;
}
.wfe-action-approve-banner { background: var(--green-dim); border: 1px solid rgba(74,222,128,.3); }
.wfe-action-reject-banner { background: var(--red-dim); border: 1px solid rgba(248,113,113,.3); }

/* ── Empty/Loading ── */
.wfe-empty { text-align: center; padding: 40px 20px; color: var(--text3); }
.wfe-empty-icon { font-size: 32px; opacity: .3; margin-bottom: 10px; }
.wfe-loading-row td { text-align: center; padding: 30px; color: var(--text3); font-size: 12px; }

/* ── Polling indicator ── */
.wfe-sync { font-size: 10px; color: var(--text3); font-family: var(--font-mono); }

/* ── Animations ── */
@keyframes wfe-fadein { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
.wfe-fadein { animation: wfe-fadein 0.3s ease forwards; }
</style>

{{-- ════════ KPI ROW ════════ --}}
<div class="wfe-kpi-row" id="wfeGlobalKpis">
    @foreach ([
        ['id' => 'gkpi-total', 'icon' => '⚙️', 'lbl' => 'Processus', 'color' => 'gold'],
        ['id' => 'gkpi-instances', 'icon' => '📂', 'lbl' => 'Instances en cours', 'color' => 'blue'],
        ['id' => 'gkpi-retard', 'icon' => '⏰', 'lbl' => 'En retard', 'color' => 'red'],
        ['id' => 'gkpi-done', 'icon' => '✅', 'lbl' => 'Complétés ce mois', 'color' => 'green'],
        ['id' => 'gkpi-ia', 'icon' => '🤖', 'lbl' => 'Fiabilité IA', 'color' => 'purple']
    ] as $k)
        <div class="wfe-kpi">
            <div class="wfe-kpi-icon" style="background:var(--{{ $k['color'] }}-dim);">{{ $k['icon'] }}</div>
            <div>
                <div class="wfe-kpi-val" id="{{ $k['id'] }}" style="color:var(--{{ $k['color'] }});">
                    <div class="wfe-kpi-skeleton" style="width:40px;"></div>
                </div>
                <div class="wfe-kpi-lbl">{{ $k['lbl'] }}</div>
            </div>
        </div>
    @endforeach
</div>

{{-- ════════ IA BANNER ════════ --}}
<div class="wfe-ia-banner">
    <div class="wfe-ia-orb">🤖</div>
    <div class="wfe-ia-insights">
        <div class="wfe-ia-title">
            IA Analytique — {{ $departmentName ?? 'Département Dynamique' }}
            <span class="wfe-live"><span class="wfe-live-dot"></span>Live Camunda</span>
        </div>
        <div class="wfe-ia-items" id="wfeIaChips">
            <span class="wfe-ia-chip" style="background:var(--blue-dim);color:var(--blue);">📊 Camunda 7 · REST API</span>
        </div>
    </div>
    <div>
        <span class="wfe-sync" id="wfeSyncTime">Synchronisation…</span>
    </div>
</div>

{{-- ════════ FILTER BAR ════════ --}}
<div class="wfe-filterbar">
    <div class="wfe-filter-tabs">
        <div class="wfe-ftab active" onclick="wfeSetFilter(this,'all')">Tous</div>
        <div class="wfe-ftab" onclick="wfeSetFilter(this,'active')">✅ En cours</div>
        <div class="wfe-ftab" onclick="wfeSetFilter(this,'pending')">⏳ À valider</div>
        <div class="wfe-ftab" onclick="wfeSetFilter(this,'done')">✔️ Terminés</div>
    </div>
    <input type="text" class="wfe-search" placeholder="🔍 Rechercher un processus..." id="wfeSearchInput" oninput="wfeSearchFilter(this.value)">
    <div class="wfe-view-toggle" id="wfeViewToggle">
        <button type="button" class="wfe-view-btn active" id="wfeViewBtnCards" onclick="wfeSetView('cards')" title="Vue cartes">🗂️ Cartes</button>
        <button type="button" class="wfe-view-btn" id="wfeViewBtnTable" onclick="wfeSetView('table')" title="Vue tableau">📊 Tableau</button>
    </div>
    <button class="btn btn-outline btn-sm" onclick="wfeRefreshAll()">🔄 Actualiser</button>
    <button class="btn btn-gold btn-sm" onclick="wfeOpenStartModal()">+ Nouvelle instance</button>
</div>

{{-- ════════ MAIN SHELL ════════ --}}
<div class="wfe-shell">

    {{-- ══ LEFT: CARDS GRID / TABLE ══ --}}
    <div>
        <div class="wfe-grid" id="wfe-grid">
            {{-- Skeleton loading cards --}}
            @for ($i = 0; $i < 4; $i++)
                <div class="wfe-card" style="opacity:.4;">
                    <div class="wfe-card-top">
                        <div class="wfe-card-icon" style="background:var(--bg4);"></div>
                        <div class="wfe-card-meta">
                            <div class="wfe-kpi-skeleton" style="width:80px;margin-bottom:6px;"></div>
                            <div class="wfe-kpi-skeleton" style="width:200px;"></div>
                        </div>
                    </div>
                    <div class="wfe-card-stats">
                        <div class="wfe-cstat"><div class="wfe-kpi-skeleton" style="width:30px;margin:0 auto;"></div></div>
                        <div class="wfe-cstat"><div class="wfe-kpi-skeleton" style="width:30px;margin:0 auto;"></div></div>
                        <div class="wfe-cstat"><div class="wfe-kpi-skeleton" style="width:30px;margin:0 auto;"></div></div>
                    </div>
                </div>
            @endfor
        </div>

        <div class="wfe-table-wrap" id="wfe-table-wrap" style="display:none;">
            <table class="wfe-table">
                <thead>
                    <tr>
                        <th>Processus</th>
                        <th>Instances</th>
                        <th>Étapes</th>
                        <th>Délai</th>
                        <th>En retard</th>
                        <th>Avancement</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="wfe-table-body"></tbody>
            </table>
        </div>
    </div>

    {{-- ══ RIGHT: SIDEBAR ══ --}}
    <div class="wfe-sidebar">

        {{-- Pending queue --}}
        <div class="wfe-sb-panel">
            <div class="wfe-sb-head">
                ⏳ File d'attente urgente
                <span class="badge red" id="sideTaskCount">…</span>
            </div>
            <div id="sideTaskList">
                <div style="padding:20px;text-align:center;color:var(--text3);font-size:12px;">Chargement…</div>
            </div>
        </div>

        {{-- Quick stats per process --}}
        <div class="wfe-sb-panel">
            <div class="wfe-sb-head">📊 Répartition par processus</div>
            <div class="wfe-sb-body" id="sideBreakdown">
                @for ($i = 0; $i < 4; $i++)
                    <div style="margin-bottom:12px;">
                        <div class="wfe-kpi-skeleton" style="width:140px;margin-bottom:6px;"></div>
                        <div class="wfe-kpi-skeleton" style="width:100%;height:4px;"></div>
                    </div>
                @endfor
            </div>
        </div>

        {{-- Last activity --}}
        <div class="wfe-sb-panel">
            <div class="wfe-sb-head">📋 Activité récente</div>
            <div id="sideActivity">
                <div style="padding:20px;text-align:center;color:var(--text3);font-size:12px;">Chargement…</div>
            </div>
        </div>

    </div>
</div>

{{-- ══════════════════════════════════════════════════════
 MODAL — DETAIL PROCESSUS + INSTANCES CAMUNDA
══════════════════════════════════════════════════════ --}}
<div id="modal-wfe-detail" class="modal">
    <div class="modal-content" style="max-width:860px;">
        <div class="modal-header">
            <div style="display:flex;align-items:center;gap:12px;">
                <div id="wfed-icon" style="font-size:26px;"></div>
                <div>
                    <div class="modal-title" id="wfed-title">—</div>
                    <div style="font-size:11px;color:var(--text3);margin-top:2px;" id="wfed-key"></div>
                </div>
            </div>
            <button class="modal-close" onclick="closeModal('modal-wfe-detail')">×</button>
        </div>
        <div class="modal-body">

            {{-- KPIs inline --}}
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:16px;">
                <div style="background:var(--bg3);border:1px solid var(--border);border-radius:8px;padding:12px;text-align:center;">
                    <div style="font-size:20px;font-weight:900;font-family:var(--font-mono);color:var(--blue);" id="wfed-total">—</div>
                    <div style="font-size:10px;color:var(--text3);">Total</div>
                </div>
                <div style="background:var(--bg3);border:1px solid var(--border);border-radius:8px;padding:12px;text-align:center;">
                    <div style="font-size:20px;font-weight:900;font-family:var(--font-mono);color:var(--teal);" id="wfed-active">—</div>
                    <div style="font-size:10px;color:var(--text3);">En cours</div>
                </div>
                <div style="background:var(--bg3);border:1px solid var(--border);border-radius:8px;padding:12px;text-align:center;">
                    <div style="font-size:20px;font-weight:900;font-family:var(--font-mono);color:var(--green);" id="wfed-done">—</div>
                    <div style="font-size:10px;color:var(--text3);">Terminées</div>
                </div>
                <div style="background:var(--bg3);border:1px solid var(--border);border-radius:8px;padding:12px;text-align:center;">
                    <div style="font-size:20px;font-weight:900;font-family:var(--font-mono);color:var(--amber);" id="wfed-tasks">—</div>
                    <div style="font-size:10px;color:var(--text3);">Tâches ouvertes</div>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="wfe-modal-tabs">
                <div class="wfe-mtab active" onclick="wfeDetailTab(this,'wfetc-instances')">📋 Instances</div>
                <div class="wfe-mtab" onclick="wfeDetailTab(this,'wfetc-tasks')">⚙️ Tâches</div>
                <div class="wfe-mtab" onclick="wfeDetailTab(this,'wfetc-history')">📜 Historique</div>
                <div class="wfe-mtab" onclick="wfeDetailTab(this,'wfetc-ia')">🤖 IA</div>
            </div>
            {{-- Removed the old "Voir la tâche personnalisée" button here: it wasn't tied to
                 any specific task row, always called openPersonalizedTaskModal with an empty
                 currentTaskId, and so always failed the "select a task first" check. Use the
                 "⚙️ Personnaliser" button on each row in the Tâches tab instead. --}}

            {{-- Tab: Instances --}}
            <div id="wfetc-instances">
                <div style="display:flex;gap:8px;margin-bottom:12px;flex-wrap:wrap;align-items:center;">
                    <span style="font-size:12px;color:var(--text2);">Instances Camunda en temps réel</span>
                    <span class="wfe-live"><span class="wfe-live-dot"></span>Live</span>
                    <div style="margin-left:auto;display:flex;gap:6px;">
                        <button class="btn btn-outline btn-sm" onclick="wfeRefreshDetail()">🔄 Actualiser</button>
                        <button class="btn btn-gold btn-sm" onclick="wfeStartFromDetail()">+ Nouvelle instance</button>
                    </div>
                </div>
                <div style="overflow-x:auto;">
                    <table class="wfe-tbl" style="min-width:700px;">
                        <thead>
                            <tr>
                                <th>Instance ID</th>
                                <th>Business Key</th>
                                <th>Démarrage</th>
                                <th>Durée</th>
                                <th>Statut</th>
                                <th>Tâche courante</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="wfed-instances-body">
                            <tr class="wfe-loading-row"><td colspan="7">Chargement depuis Camunda…</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tab: Tâches --}}
            <div id="wfetc-tasks" style="display:none;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                    <span style="font-size:12px;color:var(--text2);">Tâches ouvertes — assignables</span>
                    <button class="btn btn-outline btn-sm" onclick="wfeRefreshTasks()">🔄 Actualiser</button>
                </div>
                <div style="overflow-x:auto;">
                    <table class="wfe-tbl">
                        <thead>
                            <tr>
                                <th>Tâche</th>
                                <th>Assignée à</th>
                                <th>Créée le</th>
                                <th>Échéance</th>
                                <th>Priorité</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="wfed-tasks-body">
                            <tr class="wfe-loading-row"><td colspan="6">Chargement…</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tab: Historique --}}
            <div id="wfetc-history" style="display:none;">
                <div style="overflow-x:auto;">
                    <table class="wfe-tbl">
                        <thead>
                            <tr>
                                <th>Instance ID</th>
                                <th>Démarrage</th>
                                <th>Fin</th>
                                <th>Durée</th>
                                <th>Statut final</th>
                            </tr>
                        </thead>
                        <tbody id="wfed-history-body">
                            <tr class="wfe-loading-row"><td colspan="5">Chargement…</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tab: IA --}}
            <div id="wfetc-ia" style="display:none;">
                <div style="padding:16px;background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius);margin-bottom:14px;">
                    <div style="font-size:13px;font-weight:700;color:var(--text);margin-bottom:8px;">🤖 Analyse IA du processus</div>
                    <div id="wfed-ia-reco" style="font-size:12.5px;color:var(--text2);line-height:1.7;">Chargement…</div>
                </div>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;" id="wfed-ia-kpis"></div>
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('modal-wfe-detail')">Fermer</button>
            <button class="btn btn-gold" id="wfed-start-btn" onclick="wfeStartFromDetail()">+ Lancer une instance</button>
        </div>
    </div>
</div>

{{-- ══ MODAL — DÉMARRER UNE INSTANCE ══ --}}
<div id="modal-wfe-start" class="modal">
    <div class="modal-content" style="max-width:520px;">
        <div class="modal-header">
            <div class="modal-title">⚡ Démarrer une instance Camunda</div>
            <button class="modal-close" onclick="closeModal('modal-wfe-start')">×</button>
        </div>
        <div class="modal-body">
            <div style="padding:10px 14px;background:var(--gold-dim);border:1px solid rgba(201,168,76,.25);border-radius:var(--radius-sm);margin-bottom:16px;font-size:12.5px;color:var(--gold);">
                🔗 Connexion Camunda active · Processus : <strong id="startProcessKey">—</strong>
            </div>
            <div class="wfe-field">
                <label class="wfe-field-label">Clé métier (Business Key)</label>
                <input class="wfe-field-input" id="startBizKey" placeholder="Ex: REQ-2024-001">
            </div>
            <div class="wfe-field">
                <label class="wfe-field-label">Demandeur</label>
                <input class="wfe-field-input" id="startDemandeur" placeholder="Nom du demandeur">
            </div>
            <div class="wfe-field">
                <label class="wfe-field-label">Institution</label>
                <input class="wfe-field-input" id="startInstitution" placeholder="Ex: Organisation XYZ">
            </div>
            <div class="wfe-field">
                <label class="wfe-field-label">Type de demande</label>
                <select class="wfe-field-input" id="startType">
                    <option value="standard">Standard</option>
                    <option value="urgent">Urgent</option>
                    <option value="consultation">Consultation</option>
                    <option value="autre">Autre</option>
                </select>
            </div>
            <div class="wfe-field">
                <label class="wfe-field-label">Notes / Description</label>
                <textarea class="wfe-field-input" id="startNotes" rows="3" placeholder="Description de la demande…"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('modal-wfe-start')">Annuler</button>
            <button class="btn btn-gold" onclick="wfeConfirmStart()" id="startBtn">⚡ Démarrer</button>
        </div>
    </div>
</div>

{{-- ══ MODAL — APPROUVER UNE TÂCHE ══ --}}
<div id="modal-wfe-approve" class="modal">
    <div class="modal-content" style="max-width:480px;">
        <div class="modal-header">
            <div class="modal-title">✅ Approuver — Compléter la tâche</div>
            <button class="modal-close" onclick="closeModal('modal-wfe-approve')">×</button>
        </div>
        <div class="modal-body">
            <div class="wfe-action-banner wfe-action-approve-banner">
                <span style="font-size:24px;">✅</span>
                <div>
                    <div style="font-weight:700;font-size:13px;color:var(--green);">Approuver la demande</div>
                    <div style="font-size:12px;color:var(--text2);margin-top:3px;">Tâche : <strong id="approveTaskName">—</strong></div>
                </div>
            </div>
            <div class="wfe-field">
                <label class="wfe-field-label">Commentaire d'approbation</label>
                <textarea class="wfe-field-input" id="approveComment" rows="3" placeholder="Motif d'approbation, conditions…"></textarea>
            </div>
            <div class="wfe-field">
                <label class="wfe-field-label">Décision</label>
                <select class="wfe-field-input" id="approveDecision">
                    <option value="approved">✅ Approuvé sans réserve</option>
                    <option value="approved_conditions">✅ Approuvé avec conditions</option>
                    <option value="deferred">⏳ Différé — complément requis</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('modal-wfe-approve')">Annuler</button>
            <button class="btn" style="background:var(--green);color:#111;font-weight:700;" onclick="wfeConfirmApprove()">✅ Confirmer l'approbation</button>
        </div>
    </div>
</div>

{{-- ══ MODAL — TÂCHE PERSONNALISÉE ══ --}}
<div id="modal-personalized-task" class="modal">
    <div class="modal-content" style="max-width:620px;">
        <div class="modal-header">
            <div>
                <div class="modal-title" id="personalizedTaskTitle">📋 Tâche personnalisée</div>
                <div style="font-size:11px;color:var(--text3);margin-top:2px;" id="personalizedTaskId">—</div>
            </div>
            <button class="modal-close" onclick="closePersonalizedTaskModal()">×</button>
        </div>
        <div class="modal-body">

            {{-- Description Section --}}
            <div id="taskDescriptionSection" style="display:none; margin-bottom:18px; padding:12px; background:var(--bg3); border-radius:8px; border-left:3px solid var(--gold);">
                <div style="font-size:11px; font-weight:700; text-transform:uppercase; color:var(--text3); margin-bottom:6px;">Description</div>
                <div id="taskDescriptionContent" style="font-size:13px; line-height:1.5; color:var(--text);"></div>
            </div>

            {{-- Custom Fields Section --}}
            <div id="customFieldsSection" style="display:none; margin-bottom:18px;">
                <div style="font-size:11px; font-weight:700; text-transform:uppercase; color:var(--text3); margin-bottom:10px;">Informations requises</div>
                <div id="customFieldsForm"></div>
            </div>

            {{-- Error Messages --}}
            <div id="taskErrorMessage" style="display:none; padding:10px 12px; background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.3); border-radius:6px; color:var(--red); font-size:12px; margin-bottom:12px;"></div>

            {{-- Success Messages --}}
            <div id="taskSuccessMessage" style="display:none; padding:10px 12px; background:rgba(34,197,94,0.1); border:1px solid rgba(34,197,94,0.3); border-radius:6px; color:var(--green); font-size:12px; margin-bottom:12px;"></div>

        </div>

        <div class="modal-footer" id="personalizedTaskFooter">
            <button class="btn btn-outline" onclick="closePersonalizedTaskModal()">Annuler</button>
        </div>
    </div>
</div>

{{-- ══ MODAL — DÉTAIL INSTANCE ══ --}}
<div id="modal-wfe-instance" class="modal">
    <div class="modal-content" style="max-width:720px;">
        <div class="modal-header">
            <div>
                <div class="modal-title">📋 Détail Instance Camunda</div>

                <div style="font-size:11px;color:var(--text3);margin-top:2px;" id="instDetailId">—</div>
            </div>
            <button class="modal-close" onclick="closeModal('modal-wfe-instance')">×</button>
        </div>
        <div class="modal-body">
            <div id="instDetailFields"></div>
            <div>
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--text3);margin-bottom:8px;">Tâches de cette instance</div>
                <div id="instDetailTasks"></div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('modal-wfe-instance')">Fermer</button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
 JAVASCRIPT — CAMUNDA DYNAMIC + DEPARTMENT FILTERING
══════════════════════════════════════════════════════ --}}
<script>
'use strict';

// ════ CONFIG ════
// Department slug passed from Laravel (dynamic based on sidebar selection)
const DEPARTMENT_SLUG = @json($departmentSlug ?? null);
const DEPARTMENT_NAME = @json($departmentName ?? 'Département');

let WFE_PROCESSES = [];
const WFE_STEPS = {};
let currentTaskId = null;
let currentTaskName = null;
// ════ STATE ════
let _currentProcessKey = '';
let _currentProcessId = 0;
let _currentTaskId = '';
let _currentTaskName = '';
let _allInstances = {};
let _allTasks = {};
let _globalStats = { total: 0, active: 0, done: 0, pending: 0, blocked: 0 };
let _filterMode = 'all';
let _pollTimer = null;

// ════ CSRF ════
const CSRF = document.querySelector('meta[name=csrf-token]')?.content || '';

// ════ UTILS ════
const $ = id => document.getElementById(id);

const fmt = dt => dt ? new Date(dt).toLocaleDateString('fr-FR', {
    day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit'
}) : '—';

const dur = (start, end) => {
    if (!start) return '—';
    const ms = (end ? new Date(end) : new Date()) - new Date(start);
    const d = Math.floor(ms / 86400000);
    const h = Math.floor((ms % 86400000) / 3600000);
    return d > 0 ? `${d}j ${h}h` : `${h}h`;
};

const stateLabel = s => ({
    'ACTIVE': 'En cours', 'SUSPENDED': 'Suspendu', 'COMPLETED': 'Terminé', 'EXTERNALLY_TERMINATED': 'Annulé'
}[s] || s || 'En cours');

const stateColor = s => ({
    'ACTIVE': 'teal', 'SUSPENDED': 'amber', 'COMPLETED': 'green', 'EXTERNALLY_TERMINATED': 'red'
}[s] || 'blue');

function escHtml(unsafe) {
    return String(unsafe || '')
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;");
}

// ════ API CALLS ════
async function apiFetch(url, opts = {}) {
    const res = await fetch(url, {
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF,
            'Accept': 'application/json'
        },
        ...opts
    });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
}

// ════ LOAD PROCESSES — DB names are the source of truth ════
//
// Strategy (priority order for display title):
//   1. display_name / nom  from /api/workflows/deployed  (DB `nom` column)  ← authoritative
//   2. display_name injected by getAllInstancesByDepartment (also from DB)
//   3. processDefinitionName from Camunda  (last resort, often equals the key)
//
async function wfeLoadProcesses() {
    try {
        const camundaDepartment = DEPARTMENT_SLUG ? DEPARTMENT_SLUG.toUpperCase() : null;
        console.log(`[wfe] Loading workflows for department: ${camundaDepartment || 'ALL'}`);

        // ── Step 1: fetch deployed workflows from DB (gives us nom + process_key) ──
        let dbWorkflows = [];
        try {
            const deployedResponse = await apiFetch('/api/workflows/deployed');
            dbWorkflows = Array.isArray(deployedResponse) ? deployedResponse : [];
        } catch (e) {
            console.warn('[wfe] Could not fetch deployed workflows from DB:', e);
        }

        // Build a fast lookup: process_key → { display_name, id, ... }
        const dbMap = {};
        dbWorkflows.forEach(wf => {
            const key = wf.process_key || wf.key || wf.bpm_definition_id;
            if (key) {
                dbMap[key] = {
                    id:           wf.id,
                    display_name: wf.display_name || wf.nom || wf.name || key,   // ← DB nom
                    icon:         wf.icon  || '⚙️',
                    color:        wf.color || 'blue',
                    num:          wf.num   || wf.code || `P-${wf.id || key.substring(0,5)}`,
                    delay:        wf.delay || 'Variable',
                    output:       wf.output || 'Résultat du processus',
                };
            }
        });

        // ── Step 2: fetch running/historic instances (optionally department-filtered) ──
        let instancesUrl = '/api/workflows/all-instances';
        if (camundaDepartment) {
            instancesUrl += `?department=${encodeURIComponent(camundaDepartment)}`;
        }

        const instancesResponse = await apiFetch(instancesUrl);
        const allInstances = Array.isArray(instancesResponse)
            ? instancesResponse
            : (instancesResponse.instances || []);

        console.log(`[wfe] Fetched ${allInstances.length} instances`);

        // ── Step 3: group instances by process key ────────────────────────────
        const processMap = new Map();

        allInstances.forEach(inst => {
            const key = inst.processDefinitionKey || inst.process_key || inst.processKey || 'unknown';

            // Resolve display name: DB first, then what the controller injected, then Camunda field
            const dbEntry      = dbMap[key];
            const resolvedName = dbEntry
                ? dbEntry.display_name
                : (inst.display_name || inst.processDefinitionName || inst.processName || key);

            if (!processMap.has(key)) {
                processMap.set(key, {
                    key:       key,
                    title:     resolvedName,
                    instances: [],
                    icon:      dbEntry?.icon  || '⚙️',
                    color:     dbEntry?.color || 'blue',
                    id:        dbEntry?.id,
                    num:       dbEntry?.num   || `P-${key.substring(0,5)}`,
                    delay:     dbEntry?.delay || 'Variable',
                    output:    dbEntry?.output || 'Résultat du processus',
                });
            }
            processMap.get(key).instances.push(inst);
        });

        // ── Step 4: add DB-only workflows (no instances yet) ──────────────────
        // If no department filter, show every active workflow even if 0 instances.
        if (!camundaDepartment) {
            dbWorkflows.forEach(wf => {
                const key = wf.process_key || wf.key || wf.bpm_definition_id;
                if (key && !processMap.has(key)) {
                    processMap.set(key, {
                        id:        wf.id,
                        key:       key,
                        title:     wf.display_name || wf.nom || wf.name || key,  // ← DB nom
                        instances: [],
                        icon:      wf.icon  || '⚙️',
                        color:     wf.color || 'blue',
                        num:       wf.num   || wf.code || `P-${wf.id || key.substring(0,5)}`,
                        delay:     wf.delay || 'Variable',
                        output:    wf.output || 'Résultat du processus',
                    });
                }
            });
        }

        // ── Step 5: flatten map → WFE_PROCESSES array ────────────────────────
        WFE_PROCESSES = Array.from(processMap.values()).map(proc => ({
            id:            proc.id    || proc.key,
            key:           proc.key,
            icon:          proc.icon  || '⚙️',
            num:           proc.num   || `P-${proc.key.substring(0, 5)}`,
            color:         proc.color || 'blue',
            delay:         proc.delay || 'Variable',
            title:         proc.title,              // ← always DB nom when available
            output:        proc.output || 'Résultat du processus',
            instanceCount: proc.instances ? proc.instances.length : 0,
        }));

        console.log(`✅ Loaded ${WFE_PROCESSES.length} workflows for: ${DEPARTMENT_NAME}`);

    } catch (e) {
        console.error('[wfe] Failed to load workflows:', e);
        WFE_PROCESSES = [];
    }
}

// ════ MAIN LOAD FUNCTION ════
async function wfeLoadAll() {
    $('wfeSyncTime').textContent = 'Synchronisation…';

    if (WFE_PROCESSES.length === 0) {
        await wfeLoadProcesses();
    }

    $('wfe-grid').innerHTML = '';
    $('wfe-table-body').innerHTML = '';

    let globalTotal = 0, globalActive = 0, globalDone = 0, globalPending = 0;

    // Process one by one
    for (const proc of WFE_PROCESSES) {
        let kpis = { total: 0, en_cours: 0, termines: 0, pending: 0 };

        try {
            const kpiResponse = await apiFetch(`/api/workflows/${proc.key}/kpis`);
            kpis = kpiResponse || kpis;
        } catch (e) {
            console.warn(`KPI failed for ${proc.key}`, e);
        }

        globalTotal  += 1; // Count processes
        globalActive += Number(kpis.en_cours) || 0;
        globalDone   += Number(kpis.termines) || 0;
        globalPending += Number(kpis.pending) || 0;

        // Build card (also stores kpis/steps/progress on proc for the table view)
        const cardHtml = await buildProcessCard(proc, kpis);
        $('wfe-grid').insertAdjacentHTML('beforeend', cardHtml);

        // Build the matching table row (reuses the data buildProcessCard just computed)
        const rowHtml = buildProcessTableRow(proc);
        $('wfe-table-body').insertAdjacentHTML('beforeend', rowHtml);
    }

    // Update Global KPIs
    $('gkpi-total').textContent = globalTotal;
    $('gkpi-instances').textContent = globalActive;
    $('gkpi-retard').textContent = globalPending;
    $('gkpi-done').textContent = globalDone;
    $('gkpi-ia').textContent = '94%';

    _buildSideBreakdown();
    _buildSideActivity();
    await _loadAllTasks();

    $('wfeSyncTime').textContent = 'Mis à jour ' + new Date().toLocaleTimeString('fr-FR');

    // Handle empty state
    if (WFE_PROCESSES.length === 0) {
        $('wfe-grid').innerHTML = `
            <div class="wfe-empty" style="grid-column:1/-1;">
                <div class="wfe-empty-icon">📭</div>
                <div style="font-size:14px;font-weight:600;margin-bottom:8px;">Aucun workflow configuré</div>
                <div style="font-size:12px;color:var(--text3);">
                    Aucun processus BPMN n'est déployé pour ce département.<br>
                    Contactez l'administrateur ou déployez un workflow depuis le modeler.
                </div>
            </div>`;
        $('wfe-table-body').innerHTML = `
            <tr class="wfe-table-empty-row"><td colspan="7">📭 Aucun workflow configuré pour ce département.</td></tr>`;
    }
}

// ════ BUILD PROCESS CARD ════
async function buildProcessCard(proc, kpis) {
    let steps = [];

    try {
        const flowData = await apiFetch(`/api/workflows/${proc.key}/flow`);
        steps = Array.isArray(flowData.steps) ? flowData.steps : [];
    } catch (e) {
        console.warn(`Flow not available for ${proc.key}, using fallback`, e);
        steps = WFE_STEPS[proc.key] || [
            { label: 'Soumission', actor: 'Demandeur' },
            { label: 'Validation', actor: 'Admin' },
            { label: 'Finalisation', actor: 'Système' }
        ];
    }

    // Build step dots
    const stepsHtml = steps.map((s, i) => {
        const cls = i === 0 ? 'done' : (i === 1 ? 'active' : '');
        const lineCls = i === 0 ? 'done' : '';
        const connector = i < steps.length - 1 ? `<div class="wfe-step-line ${lineCls}"></div>` : '';

        return `
            <div class="wfe-step">
                <div class="wfe-step-dot ${cls}" title="${escHtml(s.label || 'Étape')}">${i < 1 ? '✓' : (i + 1)}</div>
            </div>${connector}`;
    }).join('');

    const progress = kpis.total > 0 ? Math.round((kpis.termines / kpis.total) * 100) : 0;
    const progressColor = progress > 70 ? 'green' : (progress > 40 ? 'amber' : 'red');

    // Stash the computed values on proc so buildProcessTableRow (table view)
    // can reuse them without re-fetching kpis/flow for the same process.
    proc._kpis = kpis;
    proc._stepsCount = steps.length;
    proc._progress = progress;
    proc._progressColor = progressColor;

    return `
    <div class="wfe-card wfe-fadein" data-id="${proc.id}" data-key="${proc.key}" onclick="wfeOpenDetail(${proc.id},'${proc.key}')">
        <div class="wfe-card-top">
            <div class="wfe-card-icon" style="background:var(--${proc.color || 'blue'}-dim);">${proc.icon || '⚙️'}</div>
            <div class="wfe-card-meta">
                <div class="wfe-card-num">${escHtml(proc.num)} · ${escHtml(proc.delay)}</div>
                <div class="wfe-card-title">${escHtml(proc.title)}</div>
                <div class="wfe-card-key">${escHtml(proc.key)}</div>
            </div>
            <div>
                <span class="badge green" style="font-size:10px;">Actif</span>
            </div>
        </div>

        <div class="wfe-steps-row">
            ${stepsHtml}
        </div>

        <div class="wfe-card-stats">
            <div class="wfe-cstat">
                <div class="wfe-cstat-val" style="color:var(--blue);">${kpis.total || 0}</div>
                <div class="wfe-cstat-lbl">Instances</div>
            </div>
            <div class="wfe-cstat">
                <div class="wfe-cstat-val" style="color:var(--${proc.color || 'teal'});">${escHtml(proc.delay)}</div>
                <div class="wfe-cstat-lbl">Délai</div>
            </div>
            <div class="wfe-cstat">
                <div class="wfe-cstat-val" style="color:var(--gold);">${steps.length}</div>
                <div class="wfe-cstat-lbl">Étapes</div>
            </div>
            <div class="wfe-cstat">
                <div class="wfe-cstat-val" style="color:${kpis.pending > 0 ? 'var(--red)' : 'var(--green)'};">${kpis.pending || 0}</div>
                <div class="wfe-cstat-lbl">En retard</div>
            </div>
        </div>

        <div class="wfe-progress-wrap">
            <div class="wfe-progress-row">
                <span style="font-size:10px;color:var(--text3);">Avancement</span>
                <div class="wfe-progress-track">
                    <div class="wfe-progress-fill" style="width:${progress}%;background:var(--${progressColor});"></div>
                </div>
                <span class="wfe-progress-pct" style="color:var(--${progressColor});">${progress}%</span>
            </div>
        </div>

        <div class="wfe-card-foot" onclick="event.stopPropagation()">
            <button class="wfe-foot-btn" onclick="wfeOpenDetail(${proc.id},'${proc.key}')">👁 Voir</button>
            <button class="wfe-foot-btn" onclick="wfeOpenDetail(${proc.id},'${proc.key}')">📂 Instances</button>
            <button class="wfe-foot-btn gold" onclick="wfeOpenStart('${proc.key}')">🤖 + Instance</button>
            <span class="wfe-foot-ia">✨ IA</span>
        </div>
    </div>`;
}

// ════ BUILD PROCESS TABLE ROW (reuses data computed by buildProcessCard) ════
function buildProcessTableRow(proc) {
    const kpis = proc._kpis || { total: 0, pending: 0 };
    const stepsCount = proc._stepsCount || 0;
    const progress = proc._progress || 0;
    const progressColor = proc._progressColor || 'red';

    return `
    <tr class="wfe-table-row" data-id="${proc.id}" data-key="${proc.key}" onclick="wfeOpenDetail(${proc.id},'${proc.key}')">
        <td>
            <div class="wfe-table-proc">
                <div class="wfe-table-icon" style="background:var(--${proc.color || 'blue'}-dim);">${proc.icon || '⚙️'}</div>
                <div style="min-width:0;">
                    <div class="wfe-table-name">${escHtml(proc.title)}</div>
                    <div class="wfe-table-key">${escHtml(proc.num)} · ${escHtml(proc.key)}</div>
                </div>
            </div>
        </td>
        <td style="color:var(--blue);font-weight:700;">${kpis.total || 0}</td>
        <td>${stepsCount}</td>
        <td style="color:var(--${proc.color || 'teal'});">${escHtml(proc.delay)}</td>
        <td style="color:${kpis.pending > 0 ? 'var(--red)' : 'var(--green)'};font-weight:700;">${kpis.pending || 0}</td>
        <td>
            <span class="wfe-table-progress-track"><span class="wfe-table-progress-fill" style="width:${progress}%;background:var(--${progressColor});"></span></span>
            <span style="font-size:10.5px;color:var(--${progressColor});font-family:var(--font-mono);">${progress}%</span>
        </td>
        <td class="wfe-table-actions" onclick="event.stopPropagation()">
            <button onclick="wfeOpenDetail(${proc.id},'${proc.key}')">👁 Voir</button>
            <button onclick="wfeOpenStart('${proc.key}')">🤖 + Instance</button>
        </td>
    </tr>`;
}

// ════ OPEN DETAIL MODAL ════
window.wfeOpenDetail = async function(id, key) {
    const proc = WFE_PROCESSES.find(p => p.id === id || p.key === key);
    if (!proc) return;

    _currentProcessKey = key || proc.key;
    _currentProcessId = id;

    $('wfed-icon').textContent = proc.icon;
    $('wfed-title').textContent = proc.title;
    $('wfed-key').textContent = proc.key;

    wfeDetailTab(document.querySelector('.wfe-mtab'), 'wfetc-instances');
    openModal('modal-wfe-detail');

    await _loadDetailData(_currentProcessKey);
};

async function _loadDetailData(key) {
    $('wfed-instances-body').innerHTML = '<tr class="wfe-loading-row"><td colspan="7">🔄 Chargement depuis Camunda…</td></tr>';

    try {
        const instances = await apiFetch(`/api/workflows/${key}/instances`);
        const userTasksResponse = await apiFetch('/api/workflows/user-tasks');
        const userTasks = Array.isArray(userTasksResponse) ? userTasksResponse : [];

        const myInstanceIds = new Set(userTasks.map(t => t.processInstanceId || t.processInstanceID));
        const filteredInstances = Array.isArray(instances)
            ? instances.filter(inst => myInstanceIds.has(inst.id || inst.ref))
            : [];

        _allInstances[key] = filteredInstances;

        $('wfed-total').textContent = instances.length;
        $('wfed-active').textContent = filteredInstances.length;

        _renderInstancesTable(key);
        await _loadDetailTasks(key);

    } catch (e) {
        console.error(e);
        $('wfed-instances-body').innerHTML = `<tr class="wfe-loading-row"><td colspan="7">⚠️ Erreur : ${e.message}</td></tr>`;
    }
}

function _renderInstancesTable(key) {
    const insts = _allInstances[key] || [];

    if (!insts.length) {
        $('wfed-instances-body').innerHTML = '<tr class="wfe-loading-row"><td colspan="7">Aucune instance pour ce processus</td></tr>';
        return;
    }

    $('wfed-instances-body').innerHTML = insts.map(inst => {
        const state = inst.state || inst.status || 'ACTIVE';
        const color = stateColor(state);
        const label = stateLabel(state);
        const id = inst.ref || inst.id || '—';
        const bk = inst.name || inst.businessKey || '—';

        return `<tr>
            <td>
                <code style="background:var(--bg4);padding:2px 6px;border-radius:4px;font-size:10.5px;cursor:pointer;color:var(--teal);"
                      onclick="wfeViewInstance('${id}','${key}')"
                      title="Voir détail">${id.substring(0,20)}…</code>
            </td>
            <td><strong>${escHtml(bk)}</strong></td>
            <td style="font-size:11px;color:var(--text3);">${inst.startTime ? fmt(inst.startTime) : '—'}</td>
            <td style="font-size:11px;font-family:var(--font-mono);">${dur(inst.startTime, inst.endTime)}</td>
            <td><span class="badge ${color}">${label}</span></td>
            <td style="font-size:11px;color:var(--text2);" id="task-cell-${id}">
                <span style="color:var(--text3);">Chargement…</span>
            </td>
            <td>
                <div style="display:flex;gap:5px;flex-wrap:wrap;">
                    <button class="wfe-act-btn wfe-act-view" onclick="wfeViewInstance('${id}','${key}')">👁 Voir</button>
                    ${state === 'ACTIVE' || state === 'En cours' ?
                        `<button class="wfe-act-btn wfe-act-approve" onclick="wfeOpenApprove('${id}')">✅</button>
                         <button class="wfe-act-btn wfe-act-reject" onclick="wfeOpenReject('${id}')">❌</button>` : ''}
                </div>
            </td>
        </tr>`;
    }).join('');

    insts.forEach(inst => {
        const id = inst.ref || inst.id;
        if (id && (inst.state === 'ACTIVE' || inst.status === 'En cours' || !inst.state)) {
            _loadCurrentTask(id);
        }
    });
}

async function _loadCurrentTask(instanceId) {
    try {
        const tasks = await apiFetch(`/api/workflows/instances/${instanceId}/tasks`);
        const cell = $(`task-cell-${instanceId}`);
        if (cell && Array.isArray(tasks) && tasks.length > 0) {
            const t = tasks[0];
            cell.innerHTML = `<span style="font-size:11px;color:var(--teal);">${t.name || 'Tâche en cours'}</span>`;
        } else if (cell) {
            cell.innerHTML = `<span style="color:var(--text3);font-size:11px;">Aucune tâche active</span>`;
        }
    } catch (e) {
        console.warn(`Failed to load task for instance ${instanceId}`, e);
        const cell = $(`task-cell-${instanceId}`);
        if (cell) cell.innerHTML = `<span style="color:var(--text3);font-size:11px;">—</span>`;
    }
}

async function _loadDetailTasks(key) {
    try {
        const userTasks = await apiFetch('/api/workflows/user-tasks');
        const tasks = Array.isArray(userTasks) ? userTasks : [];

        $('wfed-tasks').textContent = tasks.length;

        if (!tasks.length) {
            $('wfed-tasks-body').innerHTML = '<tr class="wfe-loading-row"><td colspan="6">Aucune tâche assignée à vous</td></tr>';
            return;
        }

        $('wfed-tasks-body').innerHTML = tasks.map(task => `
            <tr>
                <td><strong style="font-size:12px;">${escHtml(task.name || task.taskDefinitionKey || '—')}</strong></td>
                <td style="font-size:11px;color:var(--text2);">${escHtml(task.assignee || '—')}</td>
                <td style="font-size:11px;font-family:var(--font-mono);">${task.created ? fmt(task.created) : '—'}</td>
                <td style="font-size:11px;">${task.due ? fmt(task.due) : '—'}</td>
                <td><span style="font-size:11px;font-family:var(--font-mono);color:var(--teal);">${task.priority || 50}</span></td>
                <td>
                    <div style="display:flex;gap:5px;flex-wrap:wrap;">
                        <button class="wfe-act-btn wfe-act-approve" onclick="wfeOpenApproveTask('${task.id}','${escHtml(task.name||'')}')">✅ Approuver</button>
                        <button class="wfe-act-btn wfe-act-reject" onclick="wfeOpenRejectTask('${task.id}','${escHtml(task.name||'')}')">❌ Rejeter</button>
                        <button class="wfe-act-btn" onclick="openPersonalizedTaskModal('${task.id}','${escHtml(task.name||'')}','${escHtml(task.taskDefinitionKey||'')}')">⚙️ Personnaliser</button>
                    </div>
                </td>
            </tr>
        `).join('');

    } catch (e) {
        $('wfed-tasks-body').innerHTML = `<tr class="wfe-loading-row"><td colspan="6">Erreur de chargement</td></tr>`;
    }
}

// ════ VIEW INSTANCE DETAIL ════

/**
 * Detect if a variable key is a raw file metadata artifact
 * (e.g. "photo_1779191133568_path", "photo_1779191133568_url", "photo_1779191133568_name")
 * These come from the Camunda variable encoding and should not be shown as plain text.
 */
function _isFileMetaKey(key) {
    return /_(?:path|url|name)$/.test(key.toLowerCase());
}

/**
 * Detect if a string value looks like an image URL/path
 */
function _isImagePath(val) {
    if (typeof val !== 'string') return false;
    return /\.(png|jpe?g|gif|webp|bmp|svg|jfif)(\?.*)?$/i.test(val);
}

/**
 * Detect if a string value looks like a PDF path
 */
function _isPdfPath(val) {
    if (typeof val !== 'string') return false;
    return /\.pdf(\?.*)?$/i.test(val);
}

/**
 * Normalize a storage path/url to an absolute URL the browser can load.
 * Handles both "/storage/..." and "form_uploads/..." formats.
 */
function _resolveFileUrl(urlOrPath) {
    if (!urlOrPath) return null;

    // Already absolute (http/https)
    if (urlOrPath.startsWith('http://') || urlOrPath.startsWith('https://')) return urlOrPath;

    // Already starts with /storage/ or /
    if (urlOrPath.startsWith('/storage/') || urlOrPath.startsWith('/')) return urlOrPath;

    // Relative path like "form_uploads/2026/05/..." → prefix /storage/
    // This handles your directory structure: form_uploads/<directory>/<year>/<month>/<files>
    return '/storage/' + urlOrPath;
}

/**
 * Build an image preview card HTML
 */
function _buildImageCard(label, url, fileName) {
    const resolvedUrl = _resolveFileUrl(url);
    const name = fileName || url.split('/').pop();

    return `
        <div style="background:var(--bg3);border:1px solid var(--border);border-radius:8px;overflow:hidden;display:flex;flex-direction:column;">
            <div style="font-size:10px;color:var(--text3);text-transform:uppercase;padding:8px 10px 4px;letter-spacing:.5px;">${escHtml(label)}</div>
            <a href="${escHtml(resolvedUrl)}" target="_blank" title="Ouvrir en plein écran"
               style="display:block;background:var(--bg4);text-align:center;padding:8px;cursor:pointer;text-decoration:none;flex:1;display:flex;align-items:center;justify-content:center;min-height:160px;">
                <img src="${escHtml(resolvedUrl)}"
                     alt="${escHtml(name)}"
                     loading="lazy"
                     crossorigin="anonymous"
                     onerror="this.style.display='none';this.parentElement.innerHTML='<span style=\'font-size:11px;color:var(--text3);text-align:center;padding:20px;\'>⚠️ Image non disponible</span>'"
                     style="max-width:100%;max-height:160px;border-radius:4px;object-fit:contain;">
            </a>
            <div style="padding:6px 10px 8px;display:flex;align-items:center;justify-content:space-between;gap:8px;border-top:1px solid var(--border);">
                <span style="font-size:11px;color:var(--text2);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;flex:1;" title="${escHtml(name)}">${escHtml(name)}</span>
                <a href="${escHtml(resolvedUrl)}" download="${escHtml(name)}"
                   style="font-size:10px;font-weight:700;color:var(--teal);white-space:nowrap;text-decoration:none;padding:2px 6px;">⬇ DL</a>
            </div>
        </div>`;
}

/**
 * Build a PDF / generic file card HTML
 */
function _buildFileCard(label, url, fileName) {
    const resolvedUrl = _resolveFileUrl(url);
    const name = fileName || url.split('/').pop();
    const isPdf = _isPdfPath(url);
    return `
        <div style="background:var(--bg3);border:1px solid var(--border);border-radius:8px;padding:12px;display:flex;align-items:center;gap:12px;">
            <div style="font-size:28px;flex-shrink:0;">${isPdf ? '📄' : '📎'}</div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:10px;color:var(--text3);text-transform:uppercase;margin-bottom:3px;letter-spacing:.5px;">${escHtml(label)}</div>
                <div style="font-size:12px;font-weight:600;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="${escHtml(name)}">${escHtml(name)}</div>
            </div>
            <div style="display:flex;gap:6px;flex-shrink:0;">
                <a href="${escHtml(resolvedUrl)}" target="_blank"
                   style="font-size:11px;font-weight:700;color:var(--teal);text-decoration:none;padding:4px 9px;border:1px solid rgba(45,212,191,.35);border-radius:5px;background:var(--teal-dim);cursor:pointer;">👁</a>
                <a href="${escHtml(resolvedUrl)}" download="${escHtml(name)}"
                   style="font-size:11px;font-weight:700;color:var(--text2);text-decoration:none;padding:4px 9px;border:1px solid var(--border2);border-radius:5px;background:var(--bg4);cursor:pointer;">⬇</a>
            </div>
        </div>`;
}

window.wfeViewInstance = async function(instanceId, key) {
    $('instDetailId').textContent = instanceId;
    $('instDetailFields').innerHTML = '<div style="color:var(--text3);font-size:12px;padding:20px;text-align:center;">⏳ Chargement des données...</div>';

    openModal('modal-wfe-instance');

    try {
        const [variablesResponse, tasksResponse] = await Promise.all([
            apiFetch(`/api/workflows/instances/${instanceId}/variables`),
            apiFetch(`/api/workflows/instances/${instanceId}/tasks`)
        ]);

        const vars  = variablesResponse.variables || {};
        const files = variablesResponse.files     || {};   // file fields from DB
        const tasks = Array.isArray(tasksResponse) ? tasksResponse : [];

        let fieldsHtml = '';

        // ── 1. SCALAR FORM FIELDS ──────────────────────────────────────────────
        // Filter out raw file metadata keys (e.g. photo_123_path / _url / _name)
        const scalarEntries = Object.entries(vars).filter(([k, v]) => {
            if (v === null || v === undefined || v === '') return false;
            if (_isFileMetaKey(k)) return false;   // skip raw path/url/name artifacts
            return true;
        });

        if (scalarEntries.length > 0) {
            fieldsHtml += `<div style="margin-bottom:20px;">
                <div style="font-size:11px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px;">📋 Données du Formulaire</div>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:10px;">`;

            scalarEntries.forEach(([fieldName, value]) => {
                const label = fieldName
                    .replace(/([A-Z])/g, ' $1')
                    .replace(/^./, s => s.toUpperCase())
                    .replace(/_/g, ' ');

                // Inline URL detection — raw URL stored as a scalar (not a file field)
                const strVal = String(value);
                if (_isImagePath(strVal)) {
                    fieldsHtml += _buildImageCard(label, strVal, null);
                } else if (_isPdfPath(strVal)) {
                    fieldsHtml += _buildFileCard(label, strVal, null);
                } else {
                    fieldsHtml += `
                        <div style="background:var(--bg3);border:1px solid var(--border);border-radius:8px;padding:12px;">
                            <div style="font-size:10px;color:var(--text3);text-transform:uppercase;margin-bottom:4px;letter-spacing:.5px;">${escHtml(label)}</div>
                            <div style="font-size:13px;font-weight:600;color:var(--text);word-break:break-all;">${escHtml(strVal)}</div>
                        </div>`;
                }
            });

            fieldsHtml += `</div></div>`;
        }

        // ── 2. FILE / IMAGE FIELDS (from DB via `files` key) ──────────────────
        const fileEntries = Object.entries(files);
        if (fileEntries.length > 0) {
            fieldsHtml += `<div style="margin-bottom:20px;">
                <div style="font-size:11px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px;">🖼 Fichiers Joints</div>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:12px;">`;

            fileEntries.forEach(([fieldName, fileArray]) => {
                const baseLabel = fieldName
                    .replace(/([A-Z])/g, ' $1')
                    .replace(/^./, s => s.toUpperCase())
                    .replace(/_/g, ' ');

                const fileList = Array.isArray(fileArray) ? fileArray : [fileArray];

                fileList.forEach((file, idx) => {
                    const url      = file.url  || file.path || null;
                    const fileName = file.name  || file.originalName || file.original_name || null;
                    const label    = fileList.length > 1 ? `${baseLabel} ${idx + 1}` : baseLabel;

                    if (!url) return;

                    if (_isImagePath(url) || _isImagePath(fileName || '')) {
                        fieldsHtml += _buildImageCard(label, url, fileName);
                    } else {
                        fieldsHtml += _buildFileCard(label, url, fileName);
                    }
                });
            });

            fieldsHtml += `</div></div>`;
        }

        // ── 3. Fallback if nothing at all ──────────────────────────────────────
        if (scalarEntries.length === 0 && fileEntries.length === 0) {
            fieldsHtml += `<div style="color:var(--text3);font-style:italic;padding:16px 0;">Aucune donnée de formulaire trouvée</div>`;
        }

        // ── 4. ACTIVE TASKS ───────────────────────────────────────────────────
        if (tasks.length > 0) {
            fieldsHtml += `
                <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--border);">
                    <div style="font-size:11px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px;">⚙️ Tâches Actives</div>
                    ${tasks.map(t => `
                        <div style="background:var(--bg3);border:1px solid var(--border);border-radius:8px;padding:10px 14px;margin-bottom:8px;display:flex;align-items:center;justify-content:space-between;gap:10px;">
                            <div>
                                <div style="font-size:13px;font-weight:700;color:var(--text);">${escHtml(t.name || t.taskDefinitionKey || '—')}</div>
                                <div style="font-size:11px;color:var(--text3);margin-top:2px;">Assignée à : <span style="color:var(--text2);">${escHtml(t.assignee || 'Non assignée')}</span></div>
                            </div>
                            <div style="display:flex;gap:5px;">
                                <button class="wfe-act-btn wfe-act-approve" onclick="wfeOpenApproveTask('${t.id}','${escHtml(t.name||'')}');closeModal('modal-wfe-instance');">✅ Approuver</button>
                                <button class="wfe-act-btn wfe-act-reject"  onclick="wfeOpenRejectTask('${t.id}','${escHtml(t.name||'')}');closeModal('modal-wfe-instance');">❌ Rejeter</button>
                                <button class="wfe-act-btn" onclick="openPersonalizedTaskModal('${t.id}','${escHtml(t.name||'')}','${escHtml(t.taskDefinitionKey||'')}')">⚙️ Personnaliser</button>
                            </div>
                        </div>
                    `).join('')}
                </div>`;
        }

        $('instDetailFields').innerHTML = fieldsHtml;

    } catch (e) {
        console.error(e);
        $('instDetailFields').innerHTML = `<div style="color:var(--red);padding:20px;text-align:center;">⚠️ Erreur lors du chargement des données : ${escHtml(e.message)}</div>`;
    }
};

// ════ START INSTANCE ════
window.wfeOpenStart = function(key) {
    _currentProcessKey = key;
    const proc = WFE_PROCESSES.find(p => p.key === key);
    $('startProcessKey').textContent = key + (proc ? ` — ${proc.title}` : '');
    $('startBizKey').value = '';
    $('startDemandeur').value = '';
    $('startInstitution').value = '';
    $('startNotes').value = '';
    openModal('modal-wfe-start');
};

window.wfeOpenStartModal = function() {
    if (WFE_PROCESSES.length > 0) {
        wfeOpenStart(WFE_PROCESSES[0].key);
    } else {
        showToast('❌ Aucun processus disponible', 'error');
    }
};

window.wfeStartFromDetail = function() {
    closeModal('modal-wfe-detail');
    wfeOpenStart(_currentProcessKey);
};

window.wfeConfirmStart = async function() {
    const btn = $('startBtn');
    btn.disabled = true;
    btn.textContent = '⏳ Démarrage…';

    const variables = {
        businessKey: $('startBizKey').value,
        demandeur: $('startDemandeur').value,
        institution: $('startInstitution').value,
        typeDemande: $('startType').value,
        notes: $('startNotes').value,
        dateCreation: new Date().toISOString(),
        department: DEPARTMENT_SLUG || ''
    };

    try {
        const result = await apiFetch(`/api/workflows/${_currentProcessKey}/start`, {
            method: 'POST',
            body: JSON.stringify({ variables })
        });

        if (result.id) {
            closeModal('modal-wfe-start');
            showToast(`✅ Instance créée — ID: ${result.id.substring(0,12)}…`, 'success');
            setTimeout(() => {
                wfeRefreshAll();
                wfeOpenDetail(_currentProcessId, _currentProcessKey);
            }, 1500);
        } else {
            showToast('⚠️ Réponse Camunda inattendue', 'error');
            console.error('[Camunda] Start result:', result);
        }
    } catch (e) {
        showToast(`❌ Erreur démarrage : ${e.message}`, 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = '⚡ Démarrer';
    }
};

// ════ APPROVE ════
window.wfeOpenApprove = async function(instanceId) {
    try {
        const tasks = await apiFetch(`/api/workflows/instances/${instanceId}/tasks`);

        if (tasks && tasks.length > 0) {
            const task = tasks[0];
            _currentTaskId = task.id;
            _currentTaskName = task.name || 'Tâche';
            $('approveTaskName').textContent = task.name || instanceId.substring(0, 20) + '…';
            $('approveComment').value = '';
            openModal('modal-wfe-approve');
        } else {
            showToast('❌ Aucune tâche active trouvée pour cette instance', 'error');
        }
    } catch (e) {
        showToast('❌ Erreur: Impossible de charger la tâche', 'error');
        console.error(e);
    }
};

window.wfeOpenApproveTask = function(taskId, taskName) {
    _currentTaskId = taskId;
    _currentTaskName = taskName;
    $('approveTaskName').textContent = taskName || taskId.substring(0, 20) + '…';
    $('approveComment').value = '';
    openModal('modal-wfe-approve');
};

window.wfeConfirmApprove = async function() {
    const comment = $('approveComment')?.value.trim() || '';
    const decision = $('approveDecision')?.value || 'approved';

    const btn = document.querySelector('#modal-wfe-approve .btn');
    if (btn) { btn.disabled = true; btn.textContent = '⏳ Traitement...'; }

    try {
        const response = await fetch(`/api/workflows/tasks/${_currentTaskId}/approve`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ comment, decision })
        });

        const result = await response.json();

        if (response.ok && result.success) {
            closeModal('modal-wfe-approve');
            showToast('✅ Tâche approuvée avec succès !', 'success');
            setTimeout(() => {
                wfeRefreshAll();
                if (_currentProcessKey) _loadDetailData(_currentProcessKey);
            }, 1000);
        } else {
            showToast(`❌ Erreur: ${result.error || 'Impossible d\'approuver'}`, 'error');
        }
    } catch (e) {
        showToast(`❌ Erreur: ${e.message}`, 'error');
        console.error('Approve error:', e);
    } finally {
        if (btn) { btn.disabled = false; btn.textContent = "✅ Confirmer l'approbation"; }
    }
};

// ════ REJECT ════
window.wfeOpenReject = async function(instanceId) {
    try {
        const tasks = await apiFetch(`/api/workflows/instances/${instanceId}/tasks`);

        if (tasks && tasks.length > 0) {
            const task = tasks[0];
            _currentTaskId = task.id;
            _currentTaskName = task.name || 'Tâche';
            $('rejectTaskName').textContent = task.name || instanceId.substring(0, 20) + '…';
            $('rejectComment').value = '';
            openModal('modal-wfe-reject');
        } else {
            showToast('❌ Aucune tâche active trouvée', 'error');
        }
    } catch (e) {
        showToast('❌ Erreur: Impossible de charger la tâche', 'error');
    }
};

window.wfeOpenRejectTask = function(taskId, taskName) {
    _currentTaskId = taskId;
    _currentTaskName = taskName;
    $('rejectTaskName').textContent = taskName || taskId.substring(0, 20) + '…';
    $('rejectComment').value = '';
    openModal('modal-wfe-reject');
};

window.wfeConfirmReject = async function() {
    const reason = $('rejectReason').value;
    const comment = $('rejectComment').value.trim();

    if (!comment) {
        showToast('⚠️ Veuillez entrer un commentaire de rejet', 'error');
        return;
    }

    const btn = document.querySelector('#modal-wfe-reject .btn');
    if (btn) { btn.disabled = true; btn.textContent = '⏳ Traitement...'; }

    try {
        const response = await fetch(`/api/workflows/tasks/${_currentTaskId}/reject`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ reason, comment })
        });

        const result = await response.json();

        if (response.ok && result.success) {
            closeModal('modal-wfe-reject');
            showToast('❌ Tâche rejetée', 'success');
            setTimeout(() => {
                wfeRefreshAll();
                if (_currentProcessKey) _loadDetailData(_currentProcessKey);
            }, 1000);
        } else {
            showToast(`❌ Erreur: ${result.error || 'Impossible de rejeter'}`, 'error');
        }
    } catch (e) {
        showToast(`❌ Erreur: ${e.message}`, 'error');
        console.error('Reject error:', e);
    } finally {
        if (btn) { btn.disabled = false; btn.textContent = '❌ Confirmer le rejet'; }
    }
};

// ════ SIDEBAR TASKS (User's own tasks only, filtered by department) ════
async function _loadAllTasks() {
    try {
        // Convert department slug to uppercase format used in Camunda
        const camundaDepartment = DEPARTMENT_SLUG ? DEPARTMENT_SLUG.toUpperCase() : null;

        // Fetch user tasks - optionally filtered by department
        let tasksUrl = '/api/workflows/user-tasks';
        if (camundaDepartment) {
            tasksUrl += `?department=${encodeURIComponent(camundaDepartment)}`;
        }

        const response = await apiFetch(tasksUrl);
        let tasks = Array.isArray(response) ? response : [];

        // If the API doesn't support department filtering, filter client-side
        if (camundaDepartment && tasks.length > 0) {
            // Try to filter by department variable in task
            tasks = tasks.filter(task => {
                const taskDept = task.department || task.variables?.department?.value || '';
                return !taskDept || taskDept.toUpperCase() === camundaDepartment;
            });
        }

        $('sideTaskCount').textContent = tasks.length;

        if (tasks.length === 0) {
            $('sideTaskList').innerHTML = `
                <div style="padding:20px;text-align:center;color:var(--text3);font-size:12.5px;">
                    Aucune tâche assignée à vous pour ${DEPARTMENT_NAME || 'ce département'}
                </div>`;
            return;
        }

        let html = '';
        tasks.forEach(task => {
            html += `
                <div class="wfe-queue-item" onclick="wfeOpenApproveTask('${task.id}', '${escHtml(task.name || 'Tâche')}')">
                    <div class="wfe-queue-dot" style="background:var(--amber);"></div>
                    <div class="wfe-queue-body">
                        <div class="wfe-queue-name">${escHtml(task.name || 'Tâche sans nom')}</div>
                        <div class="wfe-queue-proc">
                            ${escHtml(task.processDefinitionName || task.process_name || 'Processus')}
                        </div>
                    </div>
                    <span class="wfe-queue-time">${task.created ? fmt(task.created) : '—'}</span>
                </div>`;
        });

        $('sideTaskList').innerHTML = html;

    } catch (err) {
        console.error('Failed to load user tasks:', err);
        $('sideTaskList').innerHTML = `<div style="padding:16px;color:var(--red);text-align:center;">Erreur de chargement des tâches</div>`;
    }
}

// ════ SIDEBAR BREAKDOWN ════
window._buildSideBreakdown = function() {
    if (WFE_PROCESSES.length === 0) {
        $('sideBreakdown').innerHTML = '<div style="padding:16px;text-align:center;color:var(--text3);font-size:12px;">Aucun processus</div>';
        return;
    }

    const max = Math.max(...WFE_PROCESSES.map(p => (_allInstances[p.key] || []).length), 1);
    $('sideBreakdown').innerHTML = WFE_PROCESSES.map(p => {
        const cnt = (_allInstances[p.key] || []).length;
        const pct = Math.round((cnt / max) * 100);
        return `<div style="margin-bottom:12px;">
            <div style="display:flex;justify-content:space-between;font-size:11.5px;color:var(--text2);margin-bottom:5px;">
                <span>${p.icon} ${(p.title || '').substring(0,22)}…</span>
                <span style="font-family:var(--font-mono);font-weight:700;color:var(--text);">${cnt}</span>
            </div>
            <div style="height:4px;background:var(--bg4);border-radius:2px;overflow:hidden;">
                <div style="height:100%;background:var(--${p.color || 'blue'});width:${pct}%;border-radius:2px;transition:width .5s;"></div>
            </div>
        </div>`;
    }).join('');
}

// ════ SIDEBAR ACTIVITY ════
window._buildSideActivity = function() {
    const activities = [];
    WFE_PROCESSES.forEach(p => {
        (_allInstances[p.key] || []).slice(0, 2).forEach(inst => {
            activities.push({
                icon: p.icon,
                proc: p.title,
                id: (inst.ref || inst.id || '—').substring(0, 10),
                time: inst.startTime ? fmt(inst.startTime) : '—',
                state: inst.state || 'ACTIVE',
            });
        });
    });

    if (!activities.length) {
        $('sideActivity').innerHTML = '<div style="padding:16px;text-align:center;font-size:12px;color:var(--text3);">Aucune activité</div>';
        return;
    }

    $('sideActivity').innerHTML = activities.slice(0, 6).map(a => `
        <div class="wfe-queue-item">
            <div class="wfe-queue-dot" style="background:var(--${stateColor(a.state)});"></div>
            <div class="wfe-queue-body">
                <div class="wfe-queue-name">${a.icon} ${(a.proc || '').substring(0,22)}…</div>
                <div class="wfe-queue-proc">${a.id} · ${a.time}</div>
            </div>
            <span class="badge ${stateColor(a.state)}" style="font-size:9px;">${stateLabel(a.state)}</span>
        </div>
    `).join('');
}

// ════ IA ANALYSIS ════
window._renderIaAnalysis = function(key, kpis) {
    const proc = WFE_PROCESSES.find(p => p.key === key);
    const total = kpis.total || 0;
    const act = kpis.en_cours || 0;
    const done = kpis.termines || 0;
    const rate = total ? Math.round((done / total) * 100) : 0;

    $('wfed-ia-reco').innerHTML = `
        <p style="margin-bottom:10px;">
            📊 <strong>${total} instances totales</strong> pour ce processus.
            Taux de complétion : <strong style="color:${rate>70?'var(--green)':rate>40?'var(--amber)':'var(--red)'};">${rate}%</strong>
        </p>
        <p style="margin-bottom:10px;">
            ${act > 5 ? '⚠️ <strong>Charge élevée</strong> — ' + act + ' instances actives simultanées. Envisagez de répartir la charge.' :
                        '✅ Charge normale — ' + act + ' instance(s) en cours.'}
        </p>
        <p>🤖 Recommandation : ${rate < 50 ? 'Identifier les blocages dans les étapes intermédiaires.' :
            'Processus bien géré. Optimisation possible sur les délais de validation.'}</p>`;

    $('wfed-ia-kpis').innerHTML = [
        { label: 'Taux complétion', val: `${rate}%`, color: rate > 70 ? 'green' : rate > 40 ? 'amber' : 'red' },
        { label: 'En cours', val: act, color: 'teal' },
        { label: 'Terminées', val: done, color: 'green' },
    ].map(k => `
        <div style="background:var(--bg3);border:1px solid var(--border);border-radius:8px;padding:12px;text-align:center;">
            <div style="font-size:20px;font-weight:900;font-family:var(--font-mono);color:var(--${k.color});">${k.val}</div>
            <div style="font-size:10px;color:var(--text3);margin-top:3px;">${k.label}</div>
        </div>`).join('');
}

// ════ LOAD HISTORY ════
async function wfeLoadHistory() {
    $('wfed-history-body').innerHTML = '<tr class="wfe-loading-row"><td colspan="5">🔄 Chargement…</td></tr>';
    try {
        const data = await apiFetch(`/api/workflows/${_currentProcessKey}/instances`);
        const insts = Array.isArray(data) ? data : [];

        if (!insts.length) {
            $('wfed-history-body').innerHTML = '<tr class="wfe-loading-row"><td colspan="5">Aucun historique</td></tr>';
            return;
        }

        $('wfed-history-body').innerHTML = insts.map(inst => {
            const state = inst.state || inst.status || '—';
            return `<tr>
                <td><code style="font-size:10.5px;background:var(--bg4);padding:2px 6px;border-radius:3px;">${(inst.ref||inst.id||'—').substring(0,20)}…</code></td>
                <td style="font-size:11px;">${inst.startTime ? fmt(inst.startTime) : '—'}</td>
                <td style="font-size:11px;">${inst.endTime ? fmt(inst.endTime) : '—'}</td>
                <td style="font-size:11px;font-family:var(--font-mono);">${dur(inst.startTime, inst.endTime)}</td>
                <td><span class="badge ${stateColor(state)}">${stateLabel(state)}</span></td>
            </tr>`;
        }).join('');
    } catch (e) {
        $('wfed-history-body').innerHTML = `<tr class="wfe-loading-row"><td colspan="5">⚠️ ${e.message}</td></tr>`;
    }
}

// ════ DETAIL TABS ════
window.wfeDetailTab = function(el, panelId) {
    document.querySelectorAll('.wfe-mtab').forEach(t => t.classList.remove('active'));
    if (el) el.classList.add('active');
    ['wfetc-instances', 'wfetc-tasks', 'wfetc-history', 'wfetc-ia'].forEach(id => {
        const p = $(id);
        if (p) p.style.display = id === panelId ? '' : 'none';
    });
    if (panelId === 'wfetc-history') wfeLoadHistory();
    if (panelId === 'wfetc-tasks') _loadDetailTasks(_currentProcessKey);
    if (panelId === 'wfetc-ia') _renderIaAnalysis(_currentProcessKey, { total: 10, en_cours: 3, termines: 7 });
};

// ════ FILTER ════
window.wfeSetFilter = function(el, mode) {
    _filterMode = mode;
    document.querySelectorAll('.wfe-ftab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    wfeRefreshAll();
};

window.wfeSearchFilter = function(q) {
    const ql = q.toLowerCase();
    document.querySelectorAll('.wfe-card').forEach(card => {
        const title = card.querySelector('.wfe-card-title')?.textContent.toLowerCase() || '';
        card.style.display = !ql || title.includes(ql) ? '' : 'none';
    });
    document.querySelectorAll('.wfe-table-row').forEach(row => {
        const title = row.querySelector('.wfe-table-name')?.textContent.toLowerCase() || '';
        row.style.display = !ql || title.includes(ql) ? '' : 'none';
    });
};

// ════ VIEW MODE (Cards / Table) ════
window.wfeSetView = function(mode) {
    const isTable = mode === 'table';

    $('wfe-grid').style.display = isTable ? 'none' : 'grid';
    $('wfe-table-wrap').style.display = isTable ? 'block' : 'none';

    $('wfeViewBtnCards').classList.toggle('active', !isTable);
    $('wfeViewBtnTable').classList.toggle('active', isTable);

    // Remember the choice for next visit
    try { localStorage.setItem('wfe_view_mode', mode); } catch (e) {}
};

function wfeInitView() {
    let saved = 'cards';
    try { saved = localStorage.getItem('wfe_view_mode') || 'cards'; } catch (e) {}
    wfeSetView(saved);
}

// ════ REFRESH ════
window.wfeRefreshAll = () => wfeLoadAll();
window.wfeRefreshDetail = () => _loadDetailData(_currentProcessKey);
window.wfeRefreshTasks = () => _loadDetailTasks(_currentProcessKey);

// ════ POLLING (auto-refresh every 30s) ════
window._startPolling = function() {
    _pollTimer = setInterval(() => {
        wfeLoadAll();
    }, 30000);
}

// ════ INIT ════
document.addEventListener('DOMContentLoaded', () => {
    wfeInitView();
    wfeLoadAll();
    _startPolling();
});

window.addEventListener('beforeunload', () => {
    if (_pollTimer) clearInterval(_pollTimer);
});

// ════ PERSONALIZED TASK FUNCTIONS ════

let _currentPersonalizedTaskId = '';

function showTaskPersonalization(taskId, taskName) {
    if (!taskId) {
        console.error('[v0] No task ID provided to showTaskPersonalization');
        alert('⚠ Veuillez sélectionner une tâche d\'abord');
        return;
    }

    console.log('[v0] Opening personalization for task:', taskId);

    // Fetch personalization data from API
    fetch(`/api/workflows/task-popup/${taskId}?workflow_id=${getWorkflowId()}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Accept': 'application/json',
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('[v0] Personalization data received:', data);

        if (data.success && data.data) {
            displayTaskPersonalizationModal(data.data, taskId, taskName);
        } else {
            alert('Aucune personnalisation trouvée pour cette tâche');
        }
    })
    .catch(error => {
        console.error('[v0] Error fetching personalization:', error);
        alert('✗ Erreur lors du chargement de la tâche personnalisée: ' + error.message);
    });
}

function renderPersonalizedTaskModal(taskId, taskName, personalization) {
    // Set title and ID
    document.getElementById('personalizedTaskTitle').textContent = '📋 ' + escHtml(taskName);
    document.getElementById('personalizedTaskId').textContent = 'ID: ' + escHtml(taskId);

    clearTaskMessages();

    // Show description if available
    if (personalization.description) {
        document.getElementById('taskDescriptionSection').style.display = 'block';
        document.getElementById('taskDescriptionContent').textContent = personalization.description;
    } else {
        document.getElementById('taskDescriptionSection').style.display = 'none';
    }

    // Render custom fields form
    if (personalization.custom_fields && personalization.custom_fields.length > 0) {
        document.getElementById('customFieldsSection').style.display = 'block';
        renderCustomFieldsForm(personalization.custom_fields);
    } else {
        document.getElementById('customFieldsSection').style.display = 'none';
    }

    // Render action buttons
    renderTaskActionButtons(personalization.custom_actions, taskId);
}

function renderCustomFieldsForm(fields) {
    const container = document.getElementById('customFieldsForm');
    container.innerHTML = '';

    fields.forEach((field, index) => {
        const fieldHtml = createFormFieldHtml(field, index);
        container.insertAdjacentHTML('beforeend', fieldHtml);
    });
}

function buildSelectOptionsHtml(options) {
    if (!options || options.length === 0) {
        // No choices were configured for this dropdown in the personalization panel
        return '';
    }
    return options.map(opt => {
        // Options are saved as plain strings (current format), but support
        // {value,label} objects too in case older data uses that shape.
        const value = (typeof opt === 'object' && opt !== null) ? (opt.value ?? opt.label ?? '') : opt;
        const label = (typeof opt === 'object' && opt !== null) ? (opt.label ?? opt.value ?? '') : opt;
        return `<option value="${escHtml(value)}">${escHtml(label)}</option>`;
    }).join('');
}

function createFormFieldHtml(field, index) {
    const fieldId = `customField-${index}`;
    let inputHtml = '';

    switch(field.type) {
        case 'textarea':
            inputHtml = `<textarea id="${fieldId}" class="wfe-field-input" rows="3" placeholder="Entrez votre réponse…"></textarea>`;
            break;
        case 'select':
            inputHtml = `<select id="${fieldId}" class="wfe-field-input">
                <option value="">— Sélectionnez une option —</option>
                ${buildSelectOptionsHtml(field.options)}
            </select>`;
            break;
        case 'checkbox':
            inputHtml = `<label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                <input type="checkbox" id="${fieldId}" style="width:18px; height:18px; cursor:pointer;">
                <span style="font-size:13px;">${escHtml(field.label)}</span>
            </label>`;
            break;
        case 'date':
            inputHtml = `<input type="date" id="${fieldId}" class="wfe-field-input">`;
            break;
        default: // text
            inputHtml = `<input type="text" id="${fieldId}" class="wfe-field-input" placeholder="Entrez votre réponse…">`;
    }

    return `
        <div class="wfe-field" style="margin-bottom:12px;">
            <label class="wfe-field-label">${escHtml(field.label)}</label>
            ${field.type !== 'checkbox' ? inputHtml : ''}
            ${field.type === 'checkbox' ? inputHtml : ''}
        </div>
    `;
}

function renderTaskActionButtons(actions, taskId) {
    const footer = document.getElementById('personalizedTaskFooter');

    // Clear existing action buttons (keep Annuler)
    const buttons = footer.querySelectorAll('button:not([onclick*="closeModal"])');
    buttons.forEach(btn => btn.remove());

    if (!actions || actions.length === 0) {
        return;
    }

    actions.forEach(action => {
        const button = document.createElement('button');
        button.className = 'btn';
        button.textContent = action.name;
        button.style.background = getActionColor(action.color);
        button.style.color = action.color === 'gold' ? '#111' : '#fff';
        button.style.fontWeight = '700';
        button.onclick = () => submitCustomTaskAction(taskId, action.name);

        // Insert before Annuler button
        const annulerBtn = footer.querySelector('button[onclick*="closeModal"]');
        footer.insertBefore(button, annulerBtn);
    });
}

function getActionColor(colorName) {
    const colors = {
        'gold': 'var(--gold)',
        'green': 'var(--green)',
        'red': 'var(--red)',
        'blue': 'var(--blue)',
        'amber': 'var(--amber)',
        'gray': 'var(--gray)'
    };
    return colors[colorName] || colorName;
}

function submitCustomTaskAction(taskId, actionName) {
    // Gather form data
    const formData = {};
    document.querySelectorAll('[id^="customField-"]').forEach(field => {
        const key = field.id.replace('customField-', '');
        if (field.type === 'checkbox') {
            formData[key] = field.checked;
        } else {
            formData[key] = field.value;
        }
    });

    // Send to API — reuse the existing, already-wired completeTask endpoint
    // (same one Approuver/Rejeter use) instead of a route that was never
    // registered. The action name is included as a process variable so BPMN
    // gateways can branch on which custom action was taken.
    fetch(`/api/workflows/tasks/${taskId}/complete`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF
        },
        body: JSON.stringify({
            variables: { ...formData, action: actionName }
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showTaskSuccess('✅ Action complétée avec succès!');
            setTimeout(() => {
                closePersonalizedTaskModal();
                location.reload(); // Reload to see updated workflow
            }, 1500);
        } else {
            showTaskError(data.message || 'Erreur lors de l\'exécution de l\'action');
        }
    })
    .catch(err => {
        console.error('[v0] Erreur:', err);
        showTaskError('Erreur réseau: ' + err.message);
    });
}

function showTaskError(message) {
    const container = document.getElementById('taskErrorMessage');
    container.textContent = '❌ ' + message;
    container.style.display = 'block';
}

function showTaskSuccess(message) {
    const container = document.getElementById('taskSuccessMessage');
    container.textContent = message;
    container.style.display = 'block';
}

function clearTaskMessages() {
    document.getElementById('taskErrorMessage').style.display = 'none';
    document.getElementById('taskSuccessMessage').style.display = 'none';
}

// Function to open the personalized task modal from task click handlers
function onTaskClicked(taskId, taskName) {
    openPersonalizedTaskModal(taskId, taskName);
}
// NOTE: This page has no BPMN canvas/viewer — the previous code here referenced a
// `bpmnJS` global that was never defined anywhere in this file, which threw a
// ReferenceError on every page load and silently prevented `currentTaskId` from
// ever being set. That's why "Voir la tâche personnalisée" always showed
// "Veuillez d'abord sélectionner une tâche". Removed; task rows now call
// openPersonalizedTaskModal(taskId, taskName) directly (see _loadDetailTasks etc.).
// Opening this modal sets inline style.display/z-index (needed to reliably win
// the stacking fight with modal-wfe-detail — see openPersonalizedTaskModal
// below). The shared closeModal() elsewhere in the app most likely only
// toggles a CSS class, which an inline style overrides — that's why Annuler
// and the × button appeared to do nothing. This closes it explicitly instead.
function closePersonalizedTaskModal() {
    const modal = document.getElementById('modal-personalized-task');
    if (modal) {
        modal.classList.remove('open');
        modal.style.display = 'none';
        modal.style.zIndex = '';
    }
    document.body.style.overflow = '';
}

function openPersonalizedTaskModal(taskId, taskName, taskDefinitionKey) {
    console.log('[v0] Opening personalized task modal for:', { taskId, taskName, taskDefinitionKey });

    if (!taskId) {
        console.warn('[v0] No taskId provided');
        alert('❌ Veuillez d\'abord sélectionner une tâche');
        return;
    }

    // Store in global scope for use by other functions
    window.currentTaskId = taskId;
    window.currentTaskName = taskName;
    currentTaskId = taskId;
    currentTaskName = taskName;

    // Personalization is saved keyed by the BPMN task DEFINITION key (e.g. "Task_1",
    // set in the modeler and stable across every run of the process), not by this
    // specific running task's instance id (a fresh UUID every time). Using taskId
    // here always missed, so the popup silently fell back to "not found" defaults
    // (empty description/fields) even when a config existed.
    const lookupKey = taskDefinitionKey || taskId;

    // Fetch the personalized task data from the API
    fetch(`/api/workflows/task-popup/${encodeURIComponent(lookupKey)}`)
        .then(response => {
            console.log('[v0] Fetch response status:', response.status);
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            return response.json();
        })
        .then(data => {
            console.log('[v0] Task popup data received:', data);

            // Populate the modal with the data
            // (data.data is the actual popup payload; the previous code passed
            // the whole {success, data} wrapper into a function that also
            // targeted DOM ids that don't exist in this modal's markup, so
            // nothing ever rendered.)
            renderPersonalizedTaskModal(taskId, taskName, data.data || {});

            // Open the modal
            const modal = document.getElementById('modal-personalized-task');
            if (modal) {
                // This modal can be opened while "modal-wfe-detail" is already open
                // (the Personnaliser button lives inside its Tâches tab). Both share
                // the same .modal class/z-index, so without this it loses the
                // stacking fight and renders behind the detail modal. Moving it to
                // the very end of <body> and bumping its z-index guarantees it
                // always paints on top, regardless of what other modals are open.
                document.body.appendChild(modal);
                modal.style.zIndex = '99999';
                modal.classList.add('open');
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            } else {
                console.error('[v0] Modal element not found: modal-personalized-task');
            }
        })
        .catch(error => {
            console.error('[v0] Error fetching task data:', error);
            alert('❌ Erreur lors du chargement de la tâche');
        });
}

// ✓ NEW: Populate the modal with task data
function populatePersonalizedTaskModal(data, taskId, taskName) {
    console.log('[v0] Populating modal with task data');

    // Set task title and ID
    const titleElement = document.querySelector('[data-modal-task-title]');
    const idElement = document.querySelector('[data-modal-task-id]');

    if (titleElement) titleElement.textContent = taskName;
    if (idElement) idElement.textContent = `ID: ${taskId}`;

    // Set description
    const descElement = document.getElementById('modal-task-description');
    if (descElement && data.description) {
        descElement.textContent = data.description;
    }

    // Populate custom actions
    const actionsContainer = document.getElementById('modal-task-actions');
    if (actionsContainer && data.custom_actions && data.custom_actions.length > 0) {
        actionsContainer.innerHTML = '';
        data.custom_actions.forEach((action, index) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'task-action-button';
            btn.style.backgroundColor = action.color || '#c9a84c';
            btn.textContent = action.name;
            btn.onclick = () => executeTaskAction(taskId, action.name);
            actionsContainer.appendChild(btn);
        });
    }

    // Populate custom fields
    const fieldsContainer = document.getElementById('modal-task-fields');
    if (fieldsContainer && data.custom_fields && data.custom_fields.length > 0) {
        fieldsContainer.innerHTML = '';
        data.custom_fields.forEach((field) => {
            const fieldDiv = document.createElement('div');
            fieldDiv.className = 'wfe-field';

            const label = document.createElement('label');
            label.className = 'wfe-field-label';
            label.textContent = field.label;

            let input;
            if (field.type === 'textarea') {
                input = document.createElement('textarea');
                input.className = 'wfe-field-input';
                input.id = `field-${field.name}`;
                input.placeholder = field.placeholder || '';
            } else if (field.type === 'select') {
                input = document.createElement('select');
                input.className = 'wfe-field-input';
                input.id = `field-${field.name}`;
                if (field.options) {
                    field.options.forEach(opt => {
                        const option = document.createElement('option');
                        option.value = opt.value;
                        option.textContent = opt.label;
                        input.appendChild(option);
                    });
                }
            } else {
                input = document.createElement('input');
                input.type = field.type || 'text';
                input.className = 'wfe-field-input';
                input.id = `field-${field.name}`;
                input.placeholder = field.placeholder || '';
            }

            fieldDiv.appendChild(label);
            fieldDiv.appendChild(input);
            fieldsContainer.appendChild(fieldDiv);
        });
    }
}

// ✓ NEW: Execute a task action (button click)
function executeTaskAction(taskId, actionName) {
    console.log('[v0] Executing action:', { taskId, actionName });

    const formData = {};
    const fieldsContainer = document.getElementById('modal-task-fields');
    if (fieldsContainer) {
        const inputs = fieldsContainer.querySelectorAll('[id^="field-"]');
        inputs.forEach(input => {
            const fieldName = input.id.replace('field-', '');
            formData[fieldName] = input.value;
        });
    }

    fetch('/api/workflows/task-action', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            task_id: taskId,
            action: actionName,
            formData: formData,
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showTaskSuccess('✅ Action complétée avec succès!');
            setTimeout(() => {
                closePersonalizedTaskModal();
                location.reload();
            }, 1500);
        } else {
            showTaskError(data.message || 'Erreur lors de l\'exécution');
        }
    })
    .catch(err => {
        console.error('[v0] Error:', err);
        showTaskError('Erreur réseau: ' + err.message);
    });
}
</script>

@endsection
