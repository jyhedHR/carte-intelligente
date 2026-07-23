@extends('shared.layouts.backoffice')

@section('title', 'Tableau de Bord Admin — Direction Musique & Danse')
@section('breadcrumb', 'Administration · KPIs')

@section('content')

<style>
/* ════════════════════════════════════════════
   ADMIN KPI DASHBOARD — DESIGN SYSTEM
   Inherits from: wfe design tokens
════════════════════════════════════════════ */

/* ── Page header ── */
.adm-page-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 22px; flex-wrap: wrap; gap: 12px;
}
.adm-page-title { font-size: 18px; font-weight: 900; color: var(--text); display: flex; align-items: center; gap: 10px; }
.adm-page-sub   { font-size: 11.5px; color: var(--text3); margin-top: 3px; font-family: var(--font-mono); }
.adm-live-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 11px; background: var(--green-dim); color: var(--green);
    border: 1px solid rgba(74,222,128,0.22); border-radius: 20px;
    font-size: 10.5px; font-weight: 700;
}
.adm-live-dot {
    width: 6px; height: 6px; border-radius: 50%; background: var(--green);
    animation: adm-blink 1.4s ease-in-out infinite;
}
@keyframes adm-blink { 0%,100%{opacity:1;} 50%{opacity:0.3;} }

/* ── Section headers ── */
.adm-section-head {
    display: flex; align-items: center; gap: 9px;
    margin-bottom: 13px; margin-top: 8px;
}
.adm-section-icon {
    width: 28px; height: 28px; border-radius: 7px;
    display: flex; align-items: center; justify-content: center; font-size: 13px;
}
.adm-section-label { font-size: 12px; font-weight: 800; color: var(--text); text-transform: uppercase; letter-spacing: 0.9px; }
.adm-section-sep { flex: 1; height: 1px; background: var(--border); }
.adm-section-count { font-size: 10px; font-family: var(--font-mono); font-weight: 700; color: var(--text3); }

/* ════ METRIC CARDS (main KPIs) ════ */
.adm-metrics-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 13px;
    margin-bottom: 10px;
}
@media (max-width: 1200px) { .adm-metrics-grid { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 600px)  { .adm-metrics-grid { grid-template-columns: 1fr; } }

.adm-metric {
    background: var(--bg2); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 16px 18px;
    position: relative; overflow: hidden;
    transition: border-color 0.2s, transform 0.15s;
    cursor: default;
}
.adm-metric:hover { border-color: var(--border2); transform: translateY(-1px); }
.adm-metric-accent {
    position: absolute; top: 0; left: 0; right: 0;
    height: 2px; border-radius: var(--radius) var(--radius) 0 0;
}
.adm-metric-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; margin-bottom: 10px; }
.adm-metric-icon {
    width: 36px; height: 36px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;
}
.adm-metric-badge {
    font-size: 9.5px; font-weight: 700; padding: 2px 8px; border-radius: 10px;
    white-space: nowrap;
}
.adm-metric-val { font-size: 28px; font-weight: 900; font-family: var(--font-mono); line-height: 1; }
.adm-metric-lbl { font-size: 11px; color: var(--text3); margin-top: 4px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.6px; }
.adm-metric-sub { font-size: 10.5px; color: var(--text3); margin-top: 8px; display: flex; align-items: center; gap: 5px; }
.adm-metric-sub b { color: var(--text2); font-family: var(--font-mono); }

/* Split metric (e.g. ongoing vs completed) */
.adm-metric-split {
    display: flex; gap: 0; margin-top: 10px;
    border-top: 1px solid var(--border); padding-top: 10px;
}
.adm-split-part { flex: 1; text-align: center; }
.adm-split-part + .adm-split-part { border-left: 1px solid var(--border); }
.adm-split-val { font-size: 14px; font-weight: 900; font-family: var(--font-mono); }
.adm-split-lbl { font-size: 9.5px; color: var(--text3); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px; }

/* Mini sparkline (CSS-only bars) */
.adm-sparkline {
    display: flex; align-items: flex-end; gap: 2px;
    height: 22px; margin-top: 8px;
}
.adm-spark-bar {
    flex: 1; border-radius: 2px 2px 0 0; min-width: 4px;
    transition: opacity 0.15s;
}
.adm-spark-bar:hover { opacity: 0.8; }

/* ════ SECONDARY METRIC GRID (3 col) ════ */
.adm-metrics-3 {
    display: grid; grid-template-columns: repeat(3,1fr);
    gap: 13px; margin-bottom: 10px;
}
@media (max-width: 900px) { .adm-metrics-3 { grid-template-columns: 1fr 1fr; } }
@media (max-width: 500px)  { .adm-metrics-3 { grid-template-columns: 1fr; } }

/* ════ WORKLOAD TABLE ════ */
.adm-workload-panel {
    background: var(--bg2); border: 1px solid var(--border);
    border-radius: var(--radius); overflow: hidden; margin-bottom: 10px;
}
.adm-panel-head {
    padding: 13px 16px; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between; gap: 8px;
    font-size: 12px; font-weight: 700; color: var(--text);
}
.adm-workload-table { width: 100%; border-collapse: collapse; font-size: 12px; }
.adm-workload-table th {
    text-align: left; padding: 9px 14px;
    font-size: 9.5px; text-transform: uppercase; letter-spacing: 0.7px;
    color: var(--text3); font-weight: 700; border-bottom: 1px solid var(--border);
    background: var(--bg3);
}
.adm-workload-table td { padding: 10px 14px; border-bottom: 1px solid var(--border); color: var(--text2); vertical-align: middle; }
.adm-workload-table tr:last-child td { border-bottom: none; }
.adm-workload-table tr:hover td { background: var(--bg3); }
.adm-mgr-avatar {
    width: 26px; height: 26px; border-radius: 50%;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 800; flex-shrink: 0;
    margin-right: 8px;
}
.adm-load-bar-wrap { display: flex; align-items: center; gap: 8px; }
.adm-load-track { flex: 1; height: 6px; background: var(--bg4); border-radius: 3px; overflow: hidden; min-width: 80px; }
.adm-load-fill  { height: 100%; border-radius: 3px; transition: width 0.6s ease; }
.adm-load-pct   { font-size: 10px; font-family: var(--font-mono); font-weight: 700; min-width: 32px; text-align: right; }

/* Status pill */
.adm-status-pill {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 9px; border-radius: 20px; font-size: 10px; font-weight: 700;
}
.adm-pill-dot { width: 5px; height: 5px; border-radius: 50%; }

/* ════ TWO-COLUMN LOWER LAYOUT ════ */
.adm-lower-grid {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 16px; margin-bottom: 10px;
}
@media (max-width: 900px) { .adm-lower-grid { grid-template-columns: 1fr; } }

/* ════ INCIDENT / FAILED JOBS LIST ════ */
.adm-incident-list { display: flex; flex-direction: column; }
.adm-incident-item {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 11px 14px; border-bottom: 1px solid var(--border);
    transition: background 0.15s; cursor: pointer;
}
.adm-incident-item:last-child { border-bottom: none; }
.adm-incident-item:hover { background: var(--bg3); }
.adm-inc-sev {
    width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; margin-top: 4px;
}
.adm-inc-body { flex: 1; min-width: 0; }
.adm-inc-title { font-size: 12px; font-weight: 600; color: var(--text); }
.adm-inc-meta  { font-size: 10.5px; color: var(--text3); margin-top: 2px; }
.adm-inc-time  { font-size: 10px; font-family: var(--font-mono); color: var(--text3); flex-shrink: 0; white-space: nowrap; }

/* ════ PROCESS INSTANCES QUICK VIEW ════ */
.adm-inst-item {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 14px; border-bottom: 1px solid var(--border);
    cursor: pointer; transition: background 0.15s;
}
.adm-inst-item:last-child { border-bottom: none; }
.adm-inst-item:hover { background: var(--bg3); }
.adm-inst-icon { width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }
.adm-inst-body { flex: 1; min-width: 0; }
.adm-inst-name { font-size: 12px; font-weight: 600; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.adm-inst-sub  { font-size: 10.5px; color: var(--text3); margin-top: 1px; }
.adm-inst-count { font-size: 13px; font-weight: 900; font-family: var(--font-mono); flex-shrink: 0; }

/* ════ OVERDUE TASKS PANEL ════ */
.adm-overdue-item {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 14px; border-bottom: 1px solid var(--border);
    cursor: pointer; transition: background 0.15s;
}
.adm-overdue-item:last-child { border-bottom: none; }
.adm-overdue-item:hover { background: var(--bg3); }
.adm-overdue-flag { font-size: 11px; font-weight: 700; font-family: var(--font-mono); padding: 2px 7px; border-radius: 6px; flex-shrink: 0; }
.adm-overdue-body { flex: 1; min-width: 0; }
.adm-overdue-name { font-size: 11.5px; font-weight: 600; color: var(--text); }
.adm-overdue-proc { font-size: 10px; color: var(--text3); margin-top: 1px; }
.adm-overdue-days { font-size: 10px; font-family: var(--font-mono); font-weight: 700; flex-shrink: 0; }

/* ════ PROCESS TRACKER MODAL ════ */
.adm-tracker-modal { max-width: 840px; }

/* Timeline tracker */
.adm-timeline { display: flex; flex-direction: column; gap: 0; }
.adm-tl-item {
    display: flex; gap: 14px;
    position: relative;
}
.adm-tl-item::before {
    content: ''; position: absolute;
    left: 15px; top: 36px;
    width: 1px; height: calc(100% - 18px);
    background: var(--border);
}
.adm-tl-item:last-child::before { display: none; }
.adm-tl-left { display: flex; flex-direction: column; align-items: center; gap: 0; flex-shrink: 0; }
.adm-tl-dot {
    width: 30px; height: 30px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 800; border: 2px solid;
    flex-shrink: 0; position: relative; z-index: 1;
    transition: all 0.2s;
}
.adm-tl-dot.done    { background: var(--green-dim);   border-color: var(--green);  color: var(--green); }
.adm-tl-dot.active  { background: var(--gold-dim);    border-color: var(--gold);   color: var(--gold);
    box-shadow: 0 0 0 4px rgba(201,168,76,0.15); animation: adm-tl-pulse 2s ease-in-out infinite; }
.adm-tl-dot.pending { background: var(--bg3);         border-color: var(--border2); color: var(--text3); }
.adm-tl-dot.blocked { background: var(--red-dim);     border-color: var(--red);    color: var(--red); }
@keyframes adm-tl-pulse {
    0%,100%{ box-shadow: 0 0 0 0 rgba(201,168,76,0.35); }
    50%    { box-shadow: 0 0 0 6px rgba(201,168,76,0); }
}
.adm-tl-body {
    flex: 1; padding: 4px 0 22px;
}
.adm-tl-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; margin-bottom: 6px; }
.adm-tl-title  { font-size: 13px; font-weight: 700; color: var(--text); }
.adm-tl-time   { font-size: 10.5px; font-family: var(--font-mono); color: var(--text3); white-space: nowrap; flex-shrink: 0; }
.adm-tl-desc   { font-size: 11.5px; color: var(--text3); line-height: 1.5; }
.adm-tl-actor  { display: inline-flex; align-items: center; gap: 5px; margin-top: 6px;
    font-size: 10.5px; color: var(--text3); background: var(--bg3);
    border: 1px solid var(--border); border-radius: 6px; padding: 3px 9px; }
.adm-tl-actor b { color: var(--text2); font-weight: 600; }
.adm-tl-badge { font-size: 9.5px; font-weight: 700; padding: 2px 8px; border-radius: 10px; white-space: nowrap; }

/* ── Action log inside tracker ── */
.adm-log-table { width: 100%; border-collapse: collapse; font-size: 11.5px; }
.adm-log-table th {
    text-align: left; padding: 7px 12px;
    font-size: 9.5px; text-transform: uppercase; letter-spacing: 0.7px;
    color: var(--text3); font-weight: 700; border-bottom: 1px solid var(--border);
}
.adm-log-table td { padding: 8px 12px; border-bottom: 1px solid var(--border); color: var(--text2); }
.adm-log-table tr:last-child td { border-bottom: none; }
.adm-log-table tr:hover td { background: var(--bg3); }

/* ── Metric mini donut (CSS trick) ── */
.adm-ring { position: relative; width: 48px; height: 48px; }
.adm-ring svg { transform: rotate(-90deg); }
.adm-ring-label {
    position: absolute; top: 50%; left: 50%;
    transform: translate(-50%,-50%);
    font-size: 9px; font-weight: 800; font-family: var(--font-mono); color: var(--text);
}

/* ── Fade-in ── */
@keyframes adm-fadein { from{opacity:0;transform:translateY(8px);} to{opacity:1;transform:translateY(0);} }
.adm-fadein { animation: adm-fadein 0.35s ease forwards; }
.adm-fadein-d1 { animation-delay: 0.05s; opacity: 0; }
.adm-fadein-d2 { animation-delay: 0.10s; opacity: 0; }
.adm-fadein-d3 { animation-delay: 0.15s; opacity: 0; }
.adm-fadein-d4 { animation-delay: 0.20s; opacity: 0; }
</style>

{{-- ════════ PAGE HEADER ════════ --}}
<div class="adm-page-header adm-fadein">
    <div>
        <div class="adm-page-title">
            📊 Tableau de Bord Administrateur
        </div>
        <div class="adm-page-sub">Direction Musique & Danse · Mise à jour automatique toutes les 5 min</div>
    </div>
    <div style="display:flex; align-items:center; gap:10px;">
        <div class="adm-live-badge">
            <div class="adm-live-dot"></div>
            EN DIRECT
        </div>
        <button class="btn btn-outline btn-sm" onclick="admRefreshAll()">🔄 Actualiser</button>
        <button class="btn btn-gold btn-sm" onclick="admExportReport()">📤 Exporter</button>
    </div>
</div>

{{-- ════════ BUSINESS METRICS ════════ --}}
<div class="adm-section-head adm-fadein adm-fadein-d1">
    <div class="adm-section-icon" style="background:var(--blue-dim);">📈</div>
    <div class="adm-section-label">Métriques Métier</div>
    <div class="adm-section-sep"></div>
    <div class="adm-section-count">Business Metrics</div>
</div>

<div class="adm-metrics-grid adm-fadein adm-fadein-d1">

    {{-- Total Requests --}}
    <div class="adm-metric">
        <div class="adm-metric-accent" style="background: linear-gradient(90deg, var(--blue), transparent);"></div>
        <div class="adm-metric-top">
            <div class="adm-metric-icon" style="background:var(--blue-dim);">📂</div>
            <div class="adm-metric-badge" style="background:var(--blue-dim); color:var(--blue);">↑ +18 cette semaine</div>
        </div>
        <div class="adm-metric-val" style="color:var(--blue);">429</div>
        <div class="adm-metric-lbl">Total des demandes</div>
        <div class="adm-sparkline">
            <div class="adm-spark-bar" style="height:40%; background:var(--blue); opacity:0.3;"></div>
            <div class="adm-spark-bar" style="height:55%; background:var(--blue); opacity:0.4;"></div>
            <div class="adm-spark-bar" style="height:48%; background:var(--blue); opacity:0.4;"></div>
            <div class="adm-spark-bar" style="height:70%; background:var(--blue); opacity:0.5;"></div>
            <div class="adm-spark-bar" style="height:62%; background:var(--blue); opacity:0.5;"></div>
            <div class="adm-spark-bar" style="height:85%; background:var(--blue); opacity:0.7;"></div>
            <div class="adm-spark-bar" style="height:100%; background:var(--blue);"></div>
        </div>
        <div class="adm-metric-sub">📅 Ce mois : <b>182</b> &nbsp;|&nbsp; Ce trimestre : <b>429</b></div>
    </div>

    {{-- Ongoing vs Completed --}}
    <div class="adm-metric">
        <div class="adm-metric-accent" style="background: linear-gradient(90deg, var(--gold), transparent);"></div>
        <div class="adm-metric-top">
            <div class="adm-metric-icon" style="background:var(--gold-dim);">⚖️</div>
            <div class="adm-metric-badge" style="background:var(--green-dim); color:var(--green);">+12% complétés</div>
        </div>
        <div class="adm-metric-val" style="color:var(--gold);">247</div>
        <div class="adm-metric-lbl">En cours · Répartition</div>
        <div class="adm-metric-split">
            <div class="adm-split-part">
                <div class="adm-split-val" style="color:var(--gold);">247</div>
                <div class="adm-split-lbl">En cours</div>
            </div>
            <div class="adm-split-part">
                <div class="adm-split-val" style="color:var(--green);">182</div>
                <div class="adm-split-lbl">Complétés</div>
            </div>
            <div class="adm-split-part">
                <div class="adm-split-val" style="color:var(--text3);">0</div>
                <div class="adm-split-lbl">Annulés</div>
            </div>
        </div>
    </div>

    {{-- Average Processing Time --}}
    <div class="adm-metric">
        <div class="adm-metric-accent" style="background: linear-gradient(90deg, var(--teal), transparent);"></div>
        <div class="adm-metric-top">
            <div class="adm-metric-icon" style="background:var(--teal-dim);">⏱️</div>
            <div class="adm-metric-badge" style="background:var(--teal-dim); color:var(--teal);">↓ –8% vs mois dernier</div>
        </div>
        <div class="adm-metric-val" style="color:var(--teal);">4.2<span style="font-size:14px;"> j</span></div>
        <div class="adm-metric-lbl">Temps moyen de traitement</div>
        <div class="adm-metric-split">
            <div class="adm-split-part">
                <div class="adm-split-val" style="color:var(--green);">1 j</div>
                <div class="adm-split-lbl">Min (Attestation)</div>
            </div>
            <div class="adm-split-part">
                <div class="adm-split-val" style="color:var(--amber);">4.2 j</div>
                <div class="adm-split-lbl">Médiane</div>
            </div>
            <div class="adm-split-part">
                <div class="adm-split-val" style="color:var(--red);">90 j</div>
                <div class="adm-split-lbl">Max (Carte Pro)</div>
            </div>
        </div>
    </div>

    {{-- Completion Rate --}}
    <div class="adm-metric">
        <div class="adm-metric-accent" style="background: linear-gradient(90deg, var(--green), transparent);"></div>
        <div class="adm-metric-top">
            <div class="adm-metric-icon" style="background:var(--green-dim);">✅</div>
            <div class="adm-metric-badge" style="background:var(--green-dim); color:var(--green);">Objectif : 80%</div>
        </div>
        <div class="adm-metric-val" style="color:var(--green);">73<span style="font-size:14px;">%</span></div>
        <div class="adm-metric-lbl">Taux de complétion</div>
        <div style="margin-top:10px;">
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:5px;">
                <div style="flex:1; height:6px; background:var(--bg4); border-radius:3px; overflow:hidden;">
                    <div style="width:73%; height:100%; background:var(--green); border-radius:3px; transition: width 0.8s;"></div>
                </div>
                <span style="font-size:10px; font-family:var(--font-mono); font-weight:700; color:var(--green);">73%</span>
            </div>
            <div class="adm-metric-sub">🎯 Objectif mensuel : <b>80%</b> &nbsp;|&nbsp; Manque : <b>7 pts</b></div>
        </div>
    </div>

</div>

{{-- ════════ WORKFLOW METRICS ════════ --}}
<div class="adm-section-head adm-fadein adm-fadein-d2" style="margin-top:22px;">
    <div class="adm-section-icon" style="background:var(--amber-dim);">⚙️</div>
    <div class="adm-section-label">Métriques Workflow</div>
    <div class="adm-section-sep"></div>
    <div class="adm-section-count">Workflow Metrics</div>
</div>

<div class="adm-metrics-3 adm-fadein adm-fadein-d2">

    {{-- Overdue Tasks --}}
    <div class="adm-metric" style="cursor:pointer;" onclick="admOpenOverdue()">
        <div class="adm-metric-accent" style="background: linear-gradient(90deg, var(--red), transparent);"></div>
        <div class="adm-metric-top">
            <div class="adm-metric-icon" style="background:var(--red-dim);">⏰</div>
            <div class="adm-metric-badge" style="background:var(--red-dim); color:var(--red);">↑ Action requise</div>
        </div>
        <div class="adm-metric-val" style="color:var(--red);">23</div>
        <div class="adm-metric-lbl">Tâches en retard</div>
        <div class="adm-metric-sub">🔴 Critique : <b>7</b> &nbsp;·&nbsp; ⚠️ Modéré : <b>16</b></div>
        <div style="margin-top:10px; font-size:10px; color:var(--gold); font-weight:700; display:flex; align-items:center; gap:4px;">
            👁 Voir les tâches en retard →
        </div>
    </div>

    {{-- Avg Steps per Process --}}
    <div class="adm-metric">
        <div class="adm-metric-accent" style="background: linear-gradient(90deg, var(--purple), transparent);"></div>
        <div class="adm-metric-top">
            <div class="adm-metric-icon" style="background:var(--purple-dim);">🔗</div>
            <div class="adm-metric-badge" style="background:var(--purple-dim); color:var(--purple);">10 processus actifs</div>
        </div>
        <div class="adm-metric-val" style="color:var(--purple);">6.8</div>
        <div class="adm-metric-lbl">Étapes moyennes / processus</div>
        <div class="adm-metric-split" style="margin-top:10px;">
            <div class="adm-split-part">
                <div class="adm-split-val" style="color:var(--green);">3</div>
                <div class="adm-split-lbl">Min (Cert.)</div>
            </div>
            <div class="adm-split-part">
                <div class="adm-split-val" style="color:var(--amber);">6.8</div>
                <div class="adm-split-lbl">Moyenne</div>
            </div>
            <div class="adm-split-part">
                <div class="adm-split-val" style="color:var(--red);">11</div>
                <div class="adm-split-lbl">Max (Carte)</div>
            </div>
        </div>
    </div>

    {{-- SLA Compliance --}}
    <div class="adm-metric">
        <div class="adm-metric-accent" style="background: linear-gradient(90deg, var(--gold), transparent);"></div>
        <div class="adm-metric-top">
            <div class="adm-metric-icon" style="background:var(--gold-dim);">📋</div>
            <div class="adm-metric-badge" style="background:var(--gold-dim); color:var(--gold);">SLA Conformité</div>
        </div>
        <div class="adm-metric-val" style="color:var(--gold);">81<span style="font-size:14px;">%</span></div>
        <div class="adm-metric-lbl">Respect des délais réglementaires</div>
        <div style="margin-top:10px;">
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:5px;">
                <div style="flex:1; height:6px; background:var(--bg4); border-radius:3px; overflow:hidden;">
                    <div style="width:81%; height:100%; background:var(--gold); border-radius:3px;"></div>
                </div>
                <span style="font-size:10px; font-family:var(--font-mono); font-weight:700; color:var(--gold);">81%</span>
            </div>
            <div class="adm-metric-sub">🎯 Cible légale : <b>90%</b> &nbsp;|&nbsp; Écart : <b>-9 pts</b></div>
        </div>
    </div>

</div>

{{-- Workload Distribution per Manager --}}
<div class="adm-workload-panel adm-fadein adm-fadein-d2">
    <div class="adm-panel-head">
        <span>👥 Répartition de la charge par gestionnaire</span>
        <div style="display:flex; gap:7px;">
            <span style="font-size:10px; color:var(--text3);">Semaine du 13–17 Jan 2025</span>
            <button class="btn btn-outline btn-sm" onclick="showToast('Export charge gestionnaires', 'info')">📤</button>
        </div>
    </div>
    <table class="adm-workload-table">
        <thead>
            <tr>
                <th>Gestionnaire</th>
                <th>Dossiers actifs</th>
                <th>En retard</th>
                <th>Charge</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php
            $managers = [
                ['initials'=>'SA','name'=>'Sonia Amara','color'=>'var(--gold)','bg'=>'var(--gold-dim)','active'=>42,'overdue'=>8,'load'=>88,'status'=>'surchargé'],
                ['initials'=>'KM','name'=>'Karim Mansouri','color'=>'var(--blue)','bg'=>'var(--blue-dim)','active'=>31,'overdue'=>3,'load'=>65,'status'=>'normal'],
                ['initials'=>'LB','name'=>'Leila Ben Salah','color'=>'var(--teal)','bg'=>'var(--teal-dim)','active'=>28,'overdue'=>5,'load'=>58,'status'=>'normal'],
                ['initials'=>'MH','name'=>'Mehdi Hammami','color'=>'var(--purple)','bg'=>'var(--purple-dim)','active'=>51,'overdue'=>7,'load'=>95,'status'=>'critique'],
                ['initials'=>'NJ','name'=>'Nadia Jebali','color'=>'var(--green)','bg'=>'var(--green-dim)','active'=>19,'overdue'=>0,'load'=>40,'status'=>'léger'],
                ['initials'=>'RA','name'=>'Rami Ayari','color'=>'var(--amber)','bg'=>'var(--amber-dim)','active'=>37,'overdue'=>0,'load'=>77,'status'=>'modéré'],
            ];
            $statusConfig = [
                'critique'   => ['bg'=>'var(--red-dim)',    'color'=>'var(--red)',    'dot'=>'var(--red)',    'label'=>'Critique'],
                'surchargé'  => ['bg'=>'var(--amber-dim)',  'color'=>'var(--amber)',  'dot'=>'var(--amber)',  'label'=>'Surchargé'],
                'modéré'     => ['bg'=>'var(--gold-dim)',   'color'=>'var(--gold)',   'dot'=>'var(--gold)',   'label'=>'Modéré'],
                'normal'     => ['bg'=>'var(--blue-dim)',   'color'=>'var(--blue)',   'dot'=>'var(--blue)',   'label'=>'Normal'],
                'léger'      => ['bg'=>'var(--green-dim)',  'color'=>'var(--green)',  'dot'=>'var(--green)',  'label'=>'Léger'],
            ];
            $loadColors = [
                fn($l) => $l >= 90 ? 'var(--red)' : ($l >= 75 ? 'var(--amber)' : ($l >= 50 ? 'var(--gold)' : 'var(--green)'))
            ];
            @endphp

            @foreach($managers as $mgr)
            @php $sc = $statusConfig[$mgr['status']]; $lc = $mgr['load'] >= 90 ? 'var(--red)' : ($mgr['load'] >= 75 ? 'var(--amber)' : ($mgr['load'] >= 50 ? 'var(--gold)' : 'var(--green)')); @endphp
            <tr>
                <td>
                    <div style="display:flex; align-items:center;">
                        <div class="adm-mgr-avatar" style="background:{{ $mgr['bg'] }}; color:{{ $mgr['color'] }};">{{ $mgr['initials'] }}</div>
                        <div>
                            <div style="font-size:12px; font-weight:600; color:var(--text);">{{ $mgr['name'] }}</div>
                            <div style="font-size:10px; color:var(--text3);">Chargé de dossier</div>
                        </div>
                    </div>
                </td>
                <td>
                    <span style="font-size:14px; font-weight:900; font-family:var(--font-mono); color:var(--text);">{{ $mgr['active'] }}</span>
                    <span style="font-size:10px; color:var(--text3);"> dossiers</span>
                </td>
                <td>
                    @if($mgr['overdue'] > 0)
                        <span style="font-size:13px; font-weight:900; font-family:var(--font-mono); color:var(--red);">{{ $mgr['overdue'] }}</span>
                        <span style="font-size:10px; color:var(--red);"> retards</span>
                    @else
                        <span style="font-size:12px; color:var(--green);">✓ Aucun</span>
                    @endif
                </td>
                <td>
                    <div class="adm-load-bar-wrap">
                        <div class="adm-load-track">
                            <div class="adm-load-fill" style="width:{{ $mgr['load'] }}%; background:{{ $lc }};"></div>
                        </div>
                        <div class="adm-load-pct" style="color:{{ $lc }};">{{ $mgr['load'] }}%</div>
                    </div>
                </td>
                <td>
                    <div class="adm-status-pill" style="background:{{ $sc['bg'] }}; color:{{ $sc['color'] }};">
                        <div class="adm-pill-dot" style="background:{{ $sc['dot'] }};"></div>
                        {{ $sc['label'] }}
                    </div>
                </td>
                <td>
                    <button class="btn btn-outline btn-sm" style="font-size:10px; padding:3px 9px;"
                        onclick="admOpenManagerDetail('{{ $mgr['name'] }}')">Voir dossiers</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- ════════ TECHNICAL METRICS ════════ --}}
<div class="adm-section-head adm-fadein adm-fadein-d3" style="margin-top:22px;">
    <div class="adm-section-icon" style="background:var(--red-dim);">🔧</div>
    <div class="adm-section-label">Métriques Techniques</div>
    <div class="adm-section-sep"></div>
    <div class="adm-section-count">Technical Metrics</div>
</div>

<div class="adm-metrics-3 adm-fadein adm-fadein-d3">

    {{-- Active Incidents --}}
    <div class="adm-metric" style="cursor:pointer;" onclick="admOpenIncidents()">
        <div class="adm-metric-accent" style="background: linear-gradient(90deg, var(--red), transparent);"></div>
        <div class="adm-metric-top">
            <div class="adm-metric-icon" style="background:var(--red-dim);">🚨</div>
            <div class="adm-metric-badge" style="background:var(--red-dim); color:var(--red);">3 critiques</div>
        </div>
        <div class="adm-metric-val" style="color:var(--red);">5</div>
        <div class="adm-metric-lbl">Incidents actifs</div>
        <div class="adm-metric-split" style="margin-top:10px;">
            <div class="adm-split-part">
                <div class="adm-split-val" style="color:var(--red);">3</div>
                <div class="adm-split-lbl">Critiques</div>
            </div>
            <div class="adm-split-part">
                <div class="adm-split-val" style="color:var(--amber);">2</div>
                <div class="adm-split-lbl">Mineurs</div>
            </div>
            <div class="adm-split-part">
                <div class="adm-split-val" style="color:var(--green);">14</div>
                <div class="adm-split-lbl">Résolus (30j)</div>
            </div>
        </div>
    </div>

    {{-- Failed Jobs --}}
    <div class="adm-metric" style="cursor:pointer;" onclick="admOpenFailedJobs()">
        <div class="adm-metric-accent" style="background: linear-gradient(90deg, var(--amber), transparent);"></div>
        <div class="adm-metric-top">
            <div class="adm-metric-icon" style="background:var(--amber-dim);">💥</div>
            <div class="adm-metric-badge" style="background:var(--amber-dim); color:var(--amber);">⚠️ Attention</div>
        </div>
        <div class="adm-metric-val" style="color:var(--amber);">7</div>
        <div class="adm-metric-lbl">Jobs échoués</div>
        <div class="adm-metric-sub">🔄 File d'attente : <b>143</b> &nbsp;|&nbsp; Succès : <b>99.2%</b></div>
        <div style="margin-top:8px; font-size:10px; color:var(--gold); font-weight:700;">👁 Voir les détails →</div>
    </div>

    {{-- Active Process Instances --}}
    <div class="adm-metric" style="cursor:pointer;" onclick="admOpenInstances()">
        <div class="adm-metric-accent" style="background: linear-gradient(90deg, var(--blue), transparent);"></div>
        <div class="adm-metric-top">
            <div class="adm-metric-icon" style="background:var(--blue-dim);">⚡</div>
            <div class="adm-metric-badge" style="background:var(--blue-dim); color:var(--blue);">↑ +4 aujourd'hui</div>
        </div>
        <div class="adm-metric-val" style="color:var(--blue);">247</div>
        <div class="adm-metric-lbl">Instances de processus actives</div>
        <div class="adm-metric-sub">🔄 Traitées/heure : <b>~18</b> &nbsp;|&nbsp; Pic aujourd'hui : <b>09h30</b></div>
        <div style="margin-top:8px; font-size:10px; color:var(--gold); font-weight:700;">👁 Voir les instances →</div>
    </div>

</div>

{{-- ════════ LOWER SECTION: Incidents + Process Instances ════════ --}}
<div class="adm-lower-grid adm-fadein adm-fadein-d4">

    {{-- Active Incidents Detail --}}
    <div class="adm-workload-panel" id="panel-incidents">
        <div class="adm-panel-head">
            <span>🚨 Incidents actifs</span>
            <button class="btn btn-outline btn-sm" onclick="showToast('Vue complète des incidents', 'info')">Tout voir</button>
        </div>
        <div class="adm-incident-list">
            <div class="adm-incident-item" onclick="showToast('INC-001 : Envoi d\'emails bloqué — SMTP timeout', 'error')">
                <div class="adm-inc-sev" style="background:var(--red);"></div>
                <div class="adm-inc-body">
                    <div class="adm-inc-title">INC-001 · Envoi emails SMTP bloqué</div>
                    <div class="adm-inc-meta">14 convocations non envoyées · Diplôme Musique Arabe</div>
                </div>
                <div class="adm-inc-time">Il y a 2h</div>
            </div>
            <div class="adm-incident-item" onclick="showToast('INC-002 : Signature électronique expirée', 'error')">
                <div class="adm-inc-sev" style="background:var(--red);"></div>
                <div class="adm-inc-body">
                    <div class="adm-inc-title">INC-002 · Certificat e-signature expiré</div>
                    <div class="adm-inc-meta">Bloque la validation des Cartes Pro · 8 dossiers</div>
                </div>
                <div class="adm-inc-time">Il y a 5h</div>
            </div>
            <div class="adm-incident-item" onclick="showToast('INC-003 : Connexion CNSS timeout intermittent', 'error')">
                <div class="adm-inc-sev" style="background:var(--red);"></div>
                <div class="adm-inc-body">
                    <div class="adm-inc-title">INC-003 · API CNSS — timeout intermittent</div>
                    <div class="adm-inc-meta">Vérification affiliation impossible · P-002</div>
                </div>
                <div class="adm-inc-time">Il y a 8h</div>
            </div>
            <div class="adm-incident-item" onclick="showToast('INC-004 : Rapport PDF lent > 30s', 'info')">
                <div class="adm-inc-sev" style="background:var(--amber);"></div>
                <div class="adm-inc-body">
                    <div class="adm-inc-title">INC-004 · Génération PDF — latence élevée</div>
                    <div class="adm-inc-meta">Temps de réponse : 32s avg · Norme : &lt;5s</div>
                </div>
                <div class="adm-inc-time">Il y a 1j</div>
            </div>
            <div class="adm-incident-item" onclick="showToast('INC-005 : Quota stockage à 87%', 'info')">
                <div class="adm-inc-sev" style="background:var(--amber);"></div>
                <div class="adm-inc-body">
                    <div class="adm-inc-title">INC-005 · Espace disque — 87% utilisé</div>
                    <div class="adm-inc-meta">Seuil d'alerte : 80% · Action préventive recommandée</div>
                </div>
                <div class="adm-inc-time">Il y a 2j</div>
            </div>
        </div>
    </div>

    {{-- Failed Jobs Detail --}}
    <div class="adm-workload-panel" id="panel-failed-jobs">
        <div class="adm-panel-head">
            <span>💥 Jobs échoués</span>
            <div style="display:flex; gap:7px;">
                <button class="btn btn-outline btn-sm" onclick="showToast('Retenter tous les jobs échoués', 'success')">🔄 Retenter tout</button>
            </div>
        </div>
        <div class="adm-incident-list">
            <div class="adm-incident-item" onclick="admRetryJob('SendConvocationMail', 4)">
                <div class="adm-inc-sev" style="background:var(--red);"></div>
                <div class="adm-inc-body">
                    <div class="adm-inc-title">SendConvocationMail</div>
                    <div class="adm-inc-meta">SMTP Connection refused · 4 tentatives · P-007</div>
                </div>
                <div style="display:flex; flex-direction:column; align-items:flex-end; gap:4px;">
                    <div class="adm-inc-time">09:14</div>
                    <button class="btn btn-outline btn-sm" style="font-size:9px; padding:2px 7px;" onclick="event.stopPropagation(); admRetryJob('SendConvocationMail', 4)">Retenter</button>
                </div>
            </div>
            <div class="adm-incident-item" onclick="admRetryJob('GenerateCarteProPDF', 2)">
                <div class="adm-inc-sev" style="background:var(--red);"></div>
                <div class="adm-inc-body">
                    <div class="adm-inc-title">GenerateCarteProPDF</div>
                    <div class="adm-inc-meta">Memory limit exceeded · 2 tentatives · P-003</div>
                </div>
                <div style="display:flex; flex-direction:column; align-items:flex-end; gap:4px;">
                    <div class="adm-inc-time">08:52</div>
                    <button class="btn btn-outline btn-sm" style="font-size:9px; padding:2px 7px;" onclick="event.stopPropagation(); admRetryJob('GenerateCarteProPDF', 2)">Retenter</button>
                </div>
            </div>
            <div class="adm-incident-item" onclick="admRetryJob('SyncCNSSData', 3)">
                <div class="adm-inc-sev" style="background:var(--amber);"></div>
                <div class="adm-inc-body">
                    <div class="adm-inc-title">SyncCNSSData</div>
                    <div class="adm-inc-meta">API timeout 30s · 3 tentatives · Cron: 06:00</div>
                </div>
                <div style="display:flex; flex-direction:column; align-items:flex-end; gap:4px;">
                    <div class="adm-inc-time">06:00</div>
                    <button class="btn btn-outline btn-sm" style="font-size:9px; padding:2px 7px;" onclick="event.stopPropagation(); admRetryJob('SyncCNSSData', 3)">Retenter</button>
                </div>
            </div>
            <div class="adm-incident-item" onclick="admRetryJob('NotifyManagerDeadline', 1)">
                <div class="adm-inc-sev" style="background:var(--amber);"></div>
                <div class="adm-inc-body">
                    <div class="adm-inc-title">NotifyManagerDeadline</div>
                    <div class="adm-inc-meta">Queue worker crash · 1 tentative · Tous processus</div>
                </div>
                <div style="display:flex; flex-direction:column; align-items:flex-end; gap:4px;">
                    <div class="adm-inc-time">Hier 23:59</div>
                    <button class="btn btn-outline btn-sm" style="font-size:9px; padding:2px 7px;" onclick="event.stopPropagation(); admRetryJob('NotifyManagerDeadline', 1)">Retenter</button>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ════════ ACTIVE PROCESS INSTANCES (bottom) ════════ --}}
<div class="adm-workload-panel adm-fadein adm-fadein-d4">
    <div class="adm-panel-head">
        <span>⚡ Instances de processus actives — Vue rapide</span>
        <button class="btn btn-gold btn-sm" onclick="admOpenInstances()">Tableau complet</button>
    </div>
    <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(260px,1fr)); gap:0;">
        @php
        $instances = [
            ['icon'=>'📜','bg'=>'var(--gold-dim)','name'=>'Attestation Artistique','sub'=>'P-001','count'=>41,'color'=>'var(--gold)','pct'=>82],
            ['icon'=>'🏥','bg'=>'var(--teal-dim)','name'=>'Attestation CNSS','sub'=>'P-002','count'=>28,'color'=>'var(--teal)','pct'=>75],
            ['icon'=>'🎭','bg'=>'var(--purple-dim)','name'=>'Carte Professionnelle','sub'=>'P-003','count'=>34,'color'=>'var(--purple)','pct'=>55],
            ['icon'=>'🔄','bg'=>'var(--green-dim)','name'=>'Renouvellement Carte Pro','sub'=>'P-004','count'=>22,'color'=>'var(--green)','pct'=>61],
            ['icon'=>'💰','bg'=>'var(--amber-dim)','name'=>'Investisseurs Culturels','sub'=>'P-005','count'=>11,'color'=>'var(--amber)','pct'=>38],
            ['icon'=>'🏺','bg'=>'var(--blue-dim)','name'=>'Artisanat Traditionnel','sub'=>'P-006','count'=>11,'color'=>'var(--blue)','pct'=>70],
            ['icon'=>'🏛️','bg'=>'var(--red-dim)','name'=>'Diplôme Musique Arabe','sub'=>'P-007','count'=>89,'color'=>'var(--red)','pct'=>42],
            ['icon'=>'🏆','bg'=>'var(--green-dim)','name'=>'Certificat de Réussite','sub'=>'P-009','count'=>31,'color'=>'var(--green)','pct'=>96],
            ['icon'=>'🎪','bg'=>'var(--purple-dim)','name'=>'Profession Imprésario','sub'=>'P-010','count'=>9,'color'=>'var(--purple)','pct'=>30],
        ];
        @endphp

        @foreach($instances as $inst)
        <div class="adm-inst-item" onclick="admOpenProcessTracker({{ $loop->index + 1 }}, '{{ $inst['name'] }}')">
            <div class="adm-inst-icon" style="background:{{ $inst['bg'] }};">{{ $inst['icon'] }}</div>
            <div class="adm-inst-body">
                <div class="adm-inst-name">{{ $inst['name'] }}</div>
                <div style="display:flex; align-items:center; gap:6px; margin-top:4px;">
                    <div style="flex:1; height:4px; background:var(--bg4); border-radius:2px; overflow:hidden;">
                        <div style="width:{{ $inst['pct'] }}%; height:100%; background:{{ $inst['color'] }}; border-radius:2px;"></div>
                    </div>
                    <span style="font-size:9.5px; font-family:var(--font-mono); font-weight:700; color:{{ $inst['color'] }};">{{ $inst['pct'] }}%</span>
                </div>
            </div>
            <div class="adm-inst-count" style="color:{{ $inst['color'] }};">{{ $inst['count'] }}</div>
        </div>
        @endforeach
    </div>
</div>


{{-- ════════════════════════════════════════════
     MODAL: PROCESS TRACKER (step-by-step detail)
════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modal-adm-tracker" onclick="if(event.target===this)closeModal('modal-adm-tracker')">
    <div class="modal adm-tracker-modal">
        <div class="modal-head">
            <div style="display:flex; align-items:center; gap:10px;">
                <span id="tracker-icon" style="font-size:20px;">📜</span>
                <div>
                    <div class="modal-title" id="tracker-title">Suivi du processus</div>
                    <div style="font-size:10.5px; color:var(--text3); font-family:var(--font-mono);" id="tracker-ref">P-000</div>
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
                <span id="tracker-status-badge" class="badge green">Actif</span>
                <button class="modal-close" onclick="closeModal('modal-adm-tracker')">✕</button>
            </div>
        </div>

        {{-- Summary bar --}}
        <div style="display:flex; gap:0; border-bottom:1px solid var(--border); background:var(--bg3);">
            @foreach([['id'=>'tracker-inst','label'=>'Instances'], ['id'=>'tracker-done','label'=>'Complétées'], ['id'=>'tracker-late','label'=>'En retard'], ['id'=>'tracker-pct','label'=>'Progression']] as $stat)
            <div style="flex:1; text-align:center; padding:12px 8px; border-right:1px solid var(--border);">
                <div style="font-size:16px; font-weight:900; font-family:var(--font-mono); color:var(--text);" id="{{ $stat['id'] }}">–</div>
                <div style="font-size:9.5px; color:var(--text3); text-transform:uppercase; letter-spacing:0.5px; margin-top:2px;">{{ $stat['label'] }}</div>
            </div>
            @endforeach
            <div style="flex:1; text-align:center; padding:12px 8px;">
                <div style="font-size:16px; font-weight:900; font-family:var(--font-mono); color:var(--text);" id="tracker-sla">–</div>
                <div style="font-size:9.5px; color:var(--text3); text-transform:uppercase; letter-spacing:0.5px; margin-top:2px;">SLA</div>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="wfe-detail-tabs" style="padding:0 18px; margin-bottom:0;">
            <div class="wfe-dtab active" id="trtab-timeline" onclick="admTrackerTab('timeline')">📍 Suivi étapes</div>
            <div class="wfe-dtab" id="trtab-log" onclick="admTrackerTab('log')">📋 Journal d'actions</div>
            <div class="wfe-dtab" id="trtab-docs" onclick="admTrackerTab('docs')">📁 Documents</div>
            <div class="wfe-dtab" id="trtab-perf" onclick="admTrackerTab('perf')">⚡ Performance</div>
        </div>

        <div class="modal-body" style="padding:20px; max-height:60vh; overflow-y:auto;">

            {{-- TAB: TIMELINE --}}
            <div id="trcontent-timeline">
                <div class="adm-timeline" id="tracker-timeline"></div>
            </div>

            {{-- TAB: ACTION LOG --}}
            <div id="trcontent-log" style="display:none;">
                <div style="overflow-x:auto;">
                    <table class="adm-log-table">
                        <thead>
                            <tr>
                                <th>Date & Heure</th>
                                <th>Étape</th>
                                <th>Action</th>
                                <th>Acteur</th>
                                <th>Statut</th>
                                <th>Durée</th>
                            </tr>
                        </thead>
                        <tbody id="tracker-log-body"></tbody>
                    </table>
                </div>
            </div>

            {{-- TAB: DOCUMENTS --}}
            <div id="trcontent-docs" style="display:none;">
                <div class="wfe-doc-list" id="tracker-doc-list"></div>
            </div>

            {{-- TAB: PERFORMANCE --}}
            <div id="trcontent-perf" style="display:none;" id="trcontent-perf">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;" id="tracker-perf-body">
                    <div class="adm-metric" style="margin:0;">
                        <div class="adm-metric-top">
                            <div class="adm-metric-icon" style="background:var(--green-dim);">⚡</div>
                        </div>
                        <div class="adm-metric-val" id="perf-avg-time" style="color:var(--teal);">–</div>
                        <div class="adm-metric-lbl">Temps moyen par dossier</div>
                    </div>
                    <div class="adm-metric" style="margin:0;">
                        <div class="adm-metric-top">
                            <div class="adm-metric-icon" style="background:var(--gold-dim);">📊</div>
                        </div>
                        <div class="adm-metric-val" id="perf-sla-rate" style="color:var(--gold);">–</div>
                        <div class="adm-metric-lbl">Respect SLA</div>
                    </div>
                    <div class="adm-metric" style="margin:0;">
                        <div class="adm-metric-top">
                            <div class="adm-metric-icon" style="background:var(--blue-dim);">🔁</div>
                        </div>
                        <div class="adm-metric-val" id="perf-throughput" style="color:var(--blue);">–</div>
                        <div class="adm-metric-lbl">Débit (dossiers/jour)</div>
                    </div>
                    <div class="adm-metric" style="margin:0;">
                        <div class="adm-metric-top">
                            <div class="adm-metric-icon" style="background:var(--red-dim);">⚠️</div>
                        </div>
                        <div class="adm-metric-val" id="perf-bottleneck" style="color:var(--red); font-size:16px;">–</div>
                        <div class="adm-metric-lbl">Étape goulot d'étranglement</div>
                    </div>
                </div>
            </div>

        </div>

        <div class="modal-foot">
            <button class="btn btn-outline" onclick="closeModal('modal-adm-tracker')">Fermer</button>
            <button class="btn btn-outline" onclick="showToast('Export PDF du rapport de processus', 'info')">📤 Exporter</button>
            <button class="btn btn-gold" onclick="showToast('Redirection vers le moteur de workflow', 'info')">⚙️ Gérer ce processus</button>
        </div>
    </div>
</div>


{{-- ════════════════════════════════════════════
     MODAL: OVERDUE TASKS
════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modal-adm-overdue" onclick="if(event.target===this)closeModal('modal-adm-overdue')">
    <div class="modal" style="max-width:600px;">
        <div class="modal-head">
            <div class="modal-title">⏰ Tâches en retard (23)</div>
            <button class="modal-close" onclick="closeModal('modal-adm-overdue')">✕</button>
        </div>
        <div class="modal-body" style="padding:0; max-height:55vh; overflow-y:auto;">
            @php
            $overdueTasks = [
                ['days'=>12,'severity'=>'red','name'=>'Inspection des locaux — BenAli','proc'=>'P-010 · Imprésario','mgr'=>'MH'],
                ['days'=>9,'severity'=>'red','name'=>'Validation dossier — Karray Mohamed','proc'=>'P-003 · Carte Professionnelle','mgr'=>'SA'],
                ['days'=>8,'severity'=>'red','name'=>'Envoi convocations — Examen MA','proc'=>'P-007 · Diplôme Musique Arabe','mgr'=>'SA'],
                ['days'=>7,'severity'=>'red','name'=>'Signature PV délibération Lot 3','proc'=>'P-003 · Carte Professionnelle','mgr'=>'MH'],
                ['days'=>6,'severity'=>'amber','name'=>'Renouvellement CNSS — Hamdi Sarra','proc'=>'P-002 · Attestation CNSS','mgr'=>'KM'],
                ['days'=>5,'severity'=>'amber','name'=>'Dépôt dossier investissement — TunArt','proc'=>'P-005 · Investisseurs Culturels','mgr'=>'LB'],
                ['days'=>5,'severity'=>'amber','name'=>'Notification pré-activité — Studio Rythme','proc'=>'P-010 · Imprésario','mgr'=>'MH'],
                ['days'=>4,'severity'=>'amber','name'=>'Insertion liste candidats Session B','proc'=>'P-007 · Diplôme Musique Arabe','mgr'=>'RA'],
                ['days'=>3,'severity'=>'amber','name'=>'Validation technique patrimoine #12','proc'=>'P-006 · Artisanat Traditionnel','mgr'=>'LB'],
                ['days'=>2,'severity'=>'amber','name'=>'Remise attestation — Zouari Imed','proc'=>'P-001 · Attestation Artistique','mgr'=>'NJ'],
            ];
            @endphp
           @foreach($overdueTasks as $task)
<div class="adm-overdue-item"
     onclick="showToast('Dossier: {{ $task['name'] }} — Retard de {{ $task['days'] }} jours', '{{ $task['severity'] === 'red' ? 'error' : 'info' }}')">

    <div class="adm-overdue-flag"
         style="background:var(--{{ $task['severity'] }}-dim); color:var(--{{ $task['severity'] }});">
        J+{{ $task['days'] }}
    </div>

    <div class="adm-overdue-body">
        <div class="adm-overdue-name">{{ $task['name'] }}</div>
        <div class="adm-overdue-proc">
            {{ $task['proc'] }} · Gestionnaire : {{ $task['mgr'] }}
        </div>
    </div>

    <button class="btn btn-outline btn-sm"
        style="font-size:10px; padding:3px 8px; flex-shrink:0;"
        onclick="event.stopPropagation(); showToast('Relance envoyée pour {{ $task['name'] }}', 'success')">
        📩 Relancer
    </button>
</div>
@endforeach
        </div>
        <div class="modal-foot">
            <button class="btn btn-outline" onclick="closeModal('modal-adm-overdue')">Fermer</button>
            <button class="btn btn-gold" onclick="showToast('Relance groupée envoyée pour les 23 tâches en retard', 'success')">📩 Relancer tout (23)</button>
        </div>
    </div>
</div>


<script>
// ════════════════════════════════════════════
//  ADMIN KPI DASHBOARD — JAVASCRIPT
// ════════════════════════════════════════════

// ── Process tracker data ──
const admProcessData = {
    1: {
        icon:'📜', title:"Attestation d'exercice artistique", ref:'P-001',
        status:'Actif', statusCls:'green',
        instances:41, done:33, late:2, pct:'82%', sla:'98%',
        avgTime:'1 jour', slaRate:'98%', throughput:'8/j', bottleneck:'Étape 3 — Signature',
        steps:[
            {n:1,label:'Dépôt de dossier',desc:'Réception de la demande et vérification des pièces par le Bureau d\'ordre.',actor:'Bureau d\'ordre DMD',delai:'Jour J',status:'done',date:'08 Jan'},
            {n:2,label:'Traitement administratif',desc:'Saisie dans le système, attribution au chargé de dossier, vérification des données.',actor:'Chargé de dossier',delai:'J + quelques heures',status:'done',date:'08 Jan'},
            {n:3,label:'Signature responsable',desc:'Signature de l\'attestation par le responsable habilité de la DMD.',actor:'Responsable DMD',delai:'J + 2–4h',status:'active',date:'En cours'},
            {n:4,label:'Enregistrement',desc:'Numérotation et archivage dans le registre des attestations délivrées.',actor:'Chargé de dossier',delai:'Après signature',status:'pending',date:'–'},
            {n:5,label:'Délivrance',desc:'Remise de l\'attestation au bénéficiaire par le Bureau d\'ordre.',actor:'Bureau d\'ordre DMD',delai:'Même journée',status:'pending',date:'–'},
        ],
        log:[
            {date:'08/01 09:12',step:'Étape 1',action:'Dossier reçu et enregistré',actor:'Bureau d\'ordre',status:'done',duree:'12 min'},
            {date:'08/01 10:30',step:'Étape 2',action:'Traitement et vérification',actor:'K. Mansouri',status:'done',duree:'1h 18min'},
            {date:'08/01 14:05',step:'Étape 3',action:'En attente de signature',actor:'Responsable DMD',status:'active',duree:'En cours...'},
        ],
        docs:['Demande officielle signée','Copie CNI légalisée','Justificatif d\'exercice artistique','Relevé de compte CNSS','2 photos identité','Certificat de résidence'],
    },
    7: {
        icon:'🏛️', title:"Diplôme de Musique Arabe", ref:'P-007',
        status:'Actif', statusCls:'green',
        instances:89, done:37, late:8, pct:'42%', sla:'71%',
        avgTime:'4 mois', slaRate:'71%', throughput:'2/j', bottleneck:'Étape 5 — Convocations',
        steps:[
            {n:1,label:'Appel à candidature',desc:'Publication de l\'avis de candidature au public.',actor:'Bureau d\'ordre DMD',delai:'30 jours',status:'done',date:'01 Nov'},
            {n:2,label:'Dépôt des dossiers',desc:'Dépôt des dossiers par les candidats.',actor:'Candidats',delai:'30 jours',status:'done',date:'30 Nov'},
            {n:3,label:'Tri des dossiers',desc:'Vérification des justificatifs et conformité des dossiers.',actor:'Chargé de dossier',delai:'5 jours',status:'done',date:'05 Déc'},
            {n:4,label:'Insertion listes candidats',desc:'Saisie des listes de candidats validés dans le système.',actor:'Chargé de dossier',delai:'2 jours',status:'done',date:'07 Déc'},
            {n:5,label:'Envoi convocations',desc:'Insertion et envoi des convocations avec lieu et dates d\'examen. 14 convocations bloquées!',actor:'Chargé de dossier + Bureau d\'ordre',delai:'J-10 avant examen',status:'blocked',date:'⚠️ En retard'},
            {n:6,label:'Examens pratiques',desc:'Examens pratiques organisés à l\'Institut Salah Mahdi.',actor:'Institut Salah Mahdi',delai:'1 journée',status:'pending',date:'–'},
            {n:7,label:'Résultats pratiques',desc:'Publication des résultats des examens pratiques.',actor:'Institut Salah Mahdi',delai:'Lendemain',status:'pending',date:'–'},
            {n:8,label:'Résultats définitifs',desc:'Annonce officielle des résultats définitifs.',actor:'Direction DMD',delai:'J+3',status:'pending',date:'–'},
            {n:9,label:'Remise des diplômes',desc:'Remise des diplômes signés par le Directeur Général.',actor:'Chargé de dossier + Bureau d\'ordre',delai:'2–4 mois',status:'pending',date:'–'},
        ],
        log:[
            {date:'01/11 09:00',step:'Étape 1',action:'Avis de candidature publié',actor:'Bureau d\'ordre',status:'done',duree:'–'},
            {date:'30/11 17:00',step:'Étape 2',action:'Clôture dépôt — 89 dossiers reçus',actor:'Bureau d\'ordre',status:'done',duree:'30 jours'},
            {date:'05/12 16:45',step:'Étape 3',action:'Tri terminé — 89 validés',actor:'S. Amara',status:'done',duree:'5 jours'},
            {date:'07/12 11:00',step:'Étape 4',action:'Insertion listes effectuée',actor:'M. Hammami',status:'done',duree:'2 jours'},
            {date:'10/01 –',step:'Étape 5',action:'14 convocations bloquées — SMTP error',actor:'Système / SA',status:'blocked',duree:'12j retard'},
        ],
        docs:['Demande officielle','Copie CNI','Attestation de présence','Attestation scolaire','2 enveloppes pré-adressées et affranchies'],
    },
};

// ── Open Process Tracker ──
window.admOpenProcessTracker = function(id, name) {
    const d = admProcessData[id] || admProcessData[1];

    document.getElementById('tracker-icon').textContent = d.icon;
    document.getElementById('tracker-title').textContent = d.title;
    document.getElementById('tracker-ref').textContent = d.ref;
    const sb = document.getElementById('tracker-status-badge');
    sb.textContent = d.status;
    sb.className = 'badge ' + d.statusCls;

    document.getElementById('tracker-inst').textContent = d.instances;
    document.getElementById('tracker-done').textContent = d.done;
    document.getElementById('tracker-late').textContent = d.late;
    document.getElementById('tracker-pct').textContent = d.pct;
    document.getElementById('tracker-sla').textContent = d.sla;

    // Performance tab data
    document.getElementById('perf-avg-time').textContent  = d.avgTime;
    document.getElementById('perf-sla-rate').textContent  = d.slaRate;
    document.getElementById('perf-throughput').textContent = d.throughput;
    document.getElementById('perf-bottleneck').textContent = d.bottleneck;

    // Timeline
    const statusIcon = {done:'✓', active:'▶', pending:'○', blocked:'✕'};
    document.getElementById('tracker-timeline').innerHTML = d.steps.map((s, i) => `
        <div class="adm-tl-item">
            <div class="adm-tl-left">
                <div class="adm-tl-dot ${s.status}">${statusIcon[s.status] || i+1}</div>
            </div>
            <div class="adm-tl-body">
                <div class="adm-tl-header">
                    <div>
                        <div class="adm-tl-title">
                            <span style="font-size:10px; font-family:var(--font-mono); color:var(--text3); margin-right:6px;">Action ${s.n}</span>
                            ${s.label}
                        </div>
                        <div class="adm-tl-desc" style="margin-top:4px;">${s.desc}</div>
                        <div class="adm-tl-actor" style="margin-top:8px;">👤 <b>${s.actor}</b></div>
                    </div>
                    <div style="display:flex; flex-direction:column; align-items:flex-end; gap:5px; flex-shrink:0;">
                        <div class="adm-tl-badge" style="
                            background:${ s.status==='done' ? 'var(--green-dim)' : s.status==='active' ? 'var(--gold-dim)' : s.status==='blocked' ? 'var(--red-dim)' : 'var(--bg3)' };
                            color:${ s.status==='done' ? 'var(--green)' : s.status==='active' ? 'var(--gold)' : s.status==='blocked' ? 'var(--red)' : 'var(--text3)' };">
                            ${ s.status==='done' ? '✓ Complété' : s.status==='active' ? '▶ En cours' : s.status==='blocked' ? '✕ Bloqué' : '○ En attente' }
                        </div>
                        <div style="font-size:10px; font-family:var(--font-mono); color:var(--text3);">${s.date}</div>
                        <div style="font-size:10px; color:var(--text3);">⏱ ${s.delai}</div>
                    </div>
                </div>
            </div>
        </div>
    `).join('');

    // Log table
    const logColors = {done:'var(--green)', active:'var(--gold)', blocked:'var(--red)', pending:'var(--text3)'};
    const logLabels = {done:'Complété', active:'En cours', blocked:'Bloqué', pending:'En attente'};
    document.getElementById('tracker-log-body').innerHTML = d.log.map(l => `
        <tr>
            <td style="font-family:var(--font-mono); font-size:10.5px;">${l.date}</td>
            <td style="font-size:11px; font-weight:600;">${l.step}</td>
            <td>${l.action}</td>
            <td style="font-size:11px;">${l.actor}</td>
            <td><span style="display:inline-flex; align-items:center; gap:4px; font-size:10.5px; font-weight:700; color:${logColors[l.status]};">
                <span style="width:5px;height:5px;border-radius:50%;background:${logColors[l.status]};flex-shrink:0;"></span>
                ${logLabels[l.status]}
            </span></td>
            <td style="font-family:var(--font-mono); font-size:10.5px;">${l.duree}</td>
        </tr>
    `).join('');

    // Docs
    document.getElementById('tracker-doc-list').innerHTML = d.docs.map(doc => `
        <div class="wfe-doc-item">
            <div class="wfe-doc-check" style="background:var(--green-dim); color:var(--green);">✓</div>
            ${doc}
        </div>
    `).join('');

    admTrackerTab('timeline');
    openModal('modal-adm-tracker');
}

// ── Tracker tab switching ──
window.admTrackerTab = function(tab) {
    ['timeline','log','docs','perf'].forEach(t => {
        const content = document.getElementById('trcontent-'+t);
        const tabEl   = document.getElementById('trtab-'+t);
        if(content) content.style.display = t===tab ? '' : 'none';
        if(tabEl)   tabEl.classList.toggle('active', t===tab);
    });
}

// ── Open overdue modal ──
window.admOpenOverdue = function() { openModal('modal-adm-overdue'); }

// ── Open incidents ──
window.admOpenIncidents = function() {
    document.getElementById('panel-incidents').scrollIntoView({ behavior:'smooth', block:'center' });
    showToast('5 incidents actifs — 3 critiques nécessitent une action immédiate', 'error');
}

// ── Open failed jobs ──
window.admOpenFailedJobs = function() {
    document.getElementById('panel-failed-jobs').scrollIntoView({ behavior:'smooth', block:'center' });
    showToast('7 jobs échoués dans la file — cliquez sur "Retenter" pour chaque job', 'info');
}

// ── Open instances ──
window.admOpenInstances = function() {
    showToast('247 instances actives réparties sur 9 processus', 'info');
}

// ── Retry failed job ──
window.admRetryJob = function(job, attempts) {
    showToast('🔄 Retentative de ' + job + ' lancée (tentative #' + (attempts+1) + ')...', 'success');
}

// ── Open manager detail ──
window.admOpenManagerDetail = function(name) {
    showToast('Ouverture des dossiers de ' + name + '...', 'info');
}

// ── Refresh all ──
window.admRefreshAll = function() {
    showToast('🔄 Actualisation des métriques en cours...', 'info');
    setTimeout(() => showToast('✅ Tableau de bord mis à jour — ' + new Date().toLocaleTimeString('fr-TN'), 'success'), 1200);
}

// ── Export ──
window.admExportReport = function() {
    showToast('📤 Génération du rapport PDF en cours...', 'info');
    setTimeout(() => showToast('✅ Rapport exporté avec succès', 'success'), 1800);
}
</script>

@endsection
