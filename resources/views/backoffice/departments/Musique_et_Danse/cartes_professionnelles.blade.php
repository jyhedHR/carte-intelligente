@extends('shared.layouts.backoffice')

@section('title', 'Cartes Professionnelles Artistiques')
@section('breadcrumb', 'Cartes Professionnelles')

@section('content')

<style>
/* ════════════════════════════════════════════
   CARTES PROFESSIONNELLES — DESIGN SYSTEM
   All backend.css variables inherited
════════════════════════════════════════════ */

/* ── KPI Row ── */
.cp-kpi-row {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 12px;
    margin-bottom: 22px;
}
@media (max-width: 1100px) { .cp-kpi-row { grid-template-columns: repeat(3,1fr); } }
@media (max-width: 700px)  { .cp-kpi-row { grid-template-columns: repeat(2,1fr); } }

.cp-kpi {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 14px 16px;
    display: flex; align-items: center; gap: 12px;
    transition: border-color 0.2s, transform 0.15s;
    cursor: default;
}
.cp-kpi:hover { border-color: var(--border2); transform: translateY(-1px); }
.cp-kpi-icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; flex-shrink: 0;
}
.cp-kpi-val  { font-size: 22px; font-weight: 900; font-family: var(--font-mono); line-height: 1; }
.cp-kpi-lbl  { font-size: 10.5px; color: var(--text3); text-transform: uppercase; letter-spacing: 0.6px; margin-top: 3px; }
.cp-kpi-delta{ font-size: 10px; font-family: var(--font-mono); font-weight: 700; margin-top: 3px; }

/* ── IA Smart Banner ── */
.cp-ia-banner {
    background: linear-gradient(135deg, rgba(201,168,76,0.08), rgba(167,139,250,0.06));
    border: 1px solid rgba(201,168,76,0.22);
    border-radius: var(--radius);
    padding: 14px 20px;
    display: flex; align-items: center; gap: 16px;
    margin-bottom: 22px;
    position: relative; overflow: hidden;
}
.cp-ia-banner::after {
    content: '💳';
    position: absolute; right: 24px; top: 50%;
    transform: translateY(-50%);
    font-size: 56px; opacity: 0.06; pointer-events: none;
}
.cp-ia-orb {
    width: 44px; height: 44px; border-radius: 12px;
    background: var(--gold-dim); border: 1px solid rgba(201,168,76,0.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
    animation: cp-orb-pulse 3s ease-in-out infinite;
}
@keyframes cp-orb-pulse {
    0%,100% { box-shadow: 0 0 0 0 rgba(201,168,76,0.35); }
    50%      { box-shadow: 0 0 0 10px rgba(201,168,76,0); }
}
.cp-ia-body { flex: 1; }
.cp-ia-title {
    font-size: 13px; font-weight: 700; color: var(--text);
    margin-bottom: 5px; display: flex; align-items: center; gap: 8px;
}
.cp-ia-chips { display: flex; flex-wrap: wrap; gap: 7px; }
.cp-chip {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 11px; border-radius: 20px;
    font-size: 11px; font-weight: 600; cursor: pointer;
    transition: opacity 0.15s;
}
.cp-chip:hover { opacity: 0.8; }

/* ── Main layout ── */
.cp-shell {
    display: grid;
    grid-template-columns: 1fr 310px;
    gap: 18px; align-items: start;
}
@media (max-width: 1060px) { .cp-shell { grid-template-columns: 1fr; } }

/* ── Filter/action bar ── */
.cp-topbar {
    background: var(--bg2); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 12px 16px;
    display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
    margin-bottom: 16px;
}
.cp-filter-tabs { display: flex; gap: 0; }
.cp-ftab {
    padding: 6px 14px; font-size: 12px; font-weight: 600;
    color: var(--text3); cursor: pointer; user-select: none;
    border-bottom: 2px solid transparent; transition: all 0.15s; white-space: nowrap;
}
.cp-ftab:hover { color: var(--text2); }
.cp-ftab.active { color: var(--gold); border-bottom-color: var(--gold); }

.cp-search {
    flex: 1; min-width: 180px;
    background: var(--bg3); border: 1px solid var(--border2);
    border-radius: var(--radius-sm); padding: 7px 12px;
    font-size: 12px; color: var(--text); font-family: var(--font-body); outline: none;
    transition: border-color 0.18s;
}
.cp-search:focus { border-color: var(--gold); }
.cp-search::placeholder { color: var(--text3); }

.cp-select {
    background: var(--bg3); border: 1px solid var(--border2);
    border-radius: var(--radius-sm); padding: 7px 11px;
    font-size: 12px; color: var(--text2); cursor: pointer;
    font-family: var(--font-body); outline: none;
}

/* ── Card grid view ── */
.cp-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 14px;
    margin-bottom: 18px;
}

/* ── Individual carte card ── */
.cp-card {
    background: var(--bg2); border: 1px solid var(--border);
    border-radius: var(--radius); overflow: hidden;
    cursor: pointer; transition: all 0.18s;
    animation: cp-fadein 0.3s ease forwards;
}
@keyframes cp-fadein { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

.cp-card:hover { border-color: var(--border2); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.15); }
.cp-card.selected { border-color: var(--gold); box-shadow: 0 0 0 2px rgba(201,168,76,0.15); }

/* Card gradient top strip */
.cp-card-strip {
    height: 4px;
    background: linear-gradient(90deg, var(--gold), var(--gold2));
}
.cp-card-strip.teal   { background: linear-gradient(90deg, var(--teal), #1a8f80); }
.cp-card-strip.amber  { background: linear-gradient(90deg, var(--amber), #c08a00); }
.cp-card-strip.red    { background: linear-gradient(90deg, var(--red), #c03030); }
.cp-card-strip.purple { background: linear-gradient(90deg, var(--purple), #6344c2); }

.cp-card-head {
    padding: 14px 16px 10px;
    display: flex; align-items: flex-start; gap: 12px;
    border-bottom: 1px solid var(--border);
}
.cp-card-av {
    width: 42px; height: 42px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; font-weight: 800; flex-shrink: 0;
}
.cp-card-info { flex: 1; min-width: 0; }
.cp-card-name { font-size: 13.5px; font-weight: 700; color: var(--text); }
.cp-card-num  { font-size: 10px; font-family: var(--font-mono); color: var(--text3); margin-top: 2px; }
.cp-card-type { font-size: 11px; color: var(--text2); margin-top: 3px; display: flex; align-items: center; gap: 5px; }
.cp-card-badges { display: flex; flex-direction: column; align-items: flex-end; gap: 4px; flex-shrink: 0; }

/* Card body */
.cp-card-body { padding: 12px 16px; }
.cp-card-row  { display: flex; align-items: center; gap: 8px; margin-bottom: 6px; font-size: 12px; color: var(--text2); }
.cp-card-row:last-child { margin-bottom: 0; }
.cp-card-row-icon { font-size: 13px; flex-shrink: 0; width: 18px; text-align: center; }
.cp-card-row-label { color: var(--text3); min-width: 60px; font-size: 11px; }
.cp-card-row-val   { font-weight: 600; color: var(--text); }

/* Expiry bar */
.cp-expiry-bar {
    padding: 8px 16px 12px;
}
.cp-expiry-row { display: flex; align-items: center; gap: 8px; margin-bottom: 4px; }
.cp-expiry-label { font-size: 10px; color: var(--text3); flex: 1; }
.cp-expiry-days  { font-size: 10.5px; font-family: var(--font-mono); font-weight: 700; }
.cp-expiry-track { height: 4px; background: var(--bg4); border-radius: 2px; overflow: hidden; }
.cp-expiry-fill  { height: 100%; border-radius: 2px; transition: width 0.6s ease; }

/* IA doc checklist inside card */
.cp-doc-status {
    padding: 0 16px 10px;
    display: flex; flex-wrap: wrap; gap: 5px;
}
.cp-doc-chip {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 9px; border-radius: 20px;
    font-size: 10px; font-weight: 600;
}
.cp-doc-chip.ok   { background: var(--green-dim); color: var(--green); }
.cp-doc-chip.miss { background: var(--red-dim);   color: var(--red); }
.cp-doc-chip.warn { background: var(--amber-dim); color: var(--amber); }

/* Card footer */
.cp-card-foot {
    padding: 10px 16px;
    border-top: 1px solid var(--border);
    display: flex; align-items: center; gap: 6px; flex-wrap: wrap;
}
.cp-fbt {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 5px 11px; border-radius: var(--radius-sm);
    font-size: 11px; font-weight: 600; cursor: pointer;
    border: 1px solid var(--border2); background: var(--bg3); color: var(--text2);
    font-family: var(--font-body); transition: all 0.15s; white-space: nowrap;
}
.cp-fbt:hover { background: var(--bg4); color: var(--text); }
.cp-fbt.gold  { background: var(--gold-dim);   border-color: rgba(201,168,76,0.3);   color: var(--gold); }
.cp-fbt.gold:hover  { background: rgba(201,168,76,0.2); }
.cp-fbt.green { background: var(--green-dim);  border-color: rgba(74,222,128,0.25);  color: var(--green); }
.cp-fbt.green:hover { background: rgba(74,222,128,0.2); }
.cp-fbt.red   { background: var(--red-dim);    border-color: rgba(248,113,113,0.25); color: var(--red); }
.cp-fbt.red:hover   { background: rgba(248,113,113,0.2); }
.cp-fbt.teal  { background: var(--teal-dim);   border-color: rgba(45,212,191,0.2);   color: var(--teal); }

/* ── IA Dossier Analyser inline ── */
.cp-ia-score {
    margin-left: auto;
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 20px;
    font-size: 10.5px; font-weight: 700;
    background: var(--purple-dim); color: var(--purple);
    border: 1px solid rgba(167,139,250,0.2);
    cursor: pointer; white-space: nowrap;
}

/* ════ RIGHT SIDEBAR ════ */
.cp-sidebar { display: flex; flex-direction: column; gap: 16px; position: sticky; top: 76px; }

.cp-sb-panel {
    background: var(--bg2); border: 1px solid var(--border);
    border-radius: var(--radius); overflow: hidden;
}
.cp-sb-head {
    padding: 12px 15px; border-bottom: 1px solid var(--border);
    font-size: 12px; font-weight: 700; color: var(--text);
    display: flex; align-items: center; justify-content: space-between;
}
.cp-sb-body { padding: 12px 15px; }

/* Expiry list in sidebar */
.cp-expiry-item {
    display: flex; align-items: center; gap: 10px;
    padding: 9px 14px; border-bottom: 1px solid var(--border);
    cursor: pointer; transition: background 0.15s;
}
.cp-expiry-item:last-child { border-bottom: none; }
.cp-expiry-item:hover { background: var(--bg3); }
.cp-expiry-av {
    width: 28px; height: 28px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 10px; font-weight: 700; flex-shrink: 0;
}
.cp-expiry-info { flex: 1; min-width: 0; }
.cp-expiry-name { font-size: 12px; font-weight: 600; color: var(--text); }
.cp-expiry-when { font-size: 10.5px; color: var(--text3); font-family: var(--font-mono); }
.cp-expiry-badge { font-size: 9.5px; font-weight: 700; padding: 2px 7px; border-radius: 10px; flex-shrink: 0; }

/* IA suggestions */
.cp-ia-suggestions { display: flex; flex-direction: column; gap: 8px; padding: 10px 14px; }
.cp-sugg-item {
    display: flex; align-items: flex-start; gap: 9px;
    padding: 9px 11px; border-radius: var(--radius-sm);
    background: var(--bg3); border: 1px solid var(--border);
    cursor: pointer; transition: border-color 0.15s;
}
.cp-sugg-item:hover { border-color: var(--gold); }
.cp-sugg-icon { font-size: 15px; flex-shrink: 0; }
.cp-sugg-text { font-size: 11.5px; color: var(--text2); line-height: 1.45; flex: 1; }
.cp-sugg-cta  { font-size: 10px; color: var(--gold); font-weight: 700; margin-top: 3px; }

/* Stats donut-ish */
.cp-stat-ring-row {
    display: grid; grid-template-columns: 1fr 1fr; gap: 8px;
    padding: 12px 14px;
}
.cp-stat-ring {
    text-align: center; padding: 12px 8px;
    background: var(--bg3); border-radius: var(--radius-sm);
    border: 1px solid var(--border);
}
.cp-stat-ring-val { font-size: 20px; font-weight: 900; font-family: var(--font-mono); }
.cp-stat-ring-lbl { font-size: 10px; color: var(--text3); margin-top: 3px; text-transform: uppercase; letter-spacing: 0.5px; }

/* Quick actions */
.cp-quick { display: flex; flex-direction: column; gap: 6px; padding: 10px 14px; }
.cp-qa {
    display: flex; align-items: center; gap: 8px; padding: 8px 11px;
    background: var(--bg3); border: 1px solid var(--border);
    border-radius: var(--radius-sm); font-size: 12px; font-weight: 600;
    color: var(--text2); cursor: pointer; font-family: var(--font-body);
    transition: all 0.15s;
}
.cp-qa:hover { background: var(--bg4); color: var(--text); border-color: var(--border2); }

/* ════ MODAL STYLES ════ */
.cp-modal-wide { max-width: 680px; }

.cp-form-section {
    background: var(--bg3); border: 1px solid var(--border);
    border-radius: var(--radius-sm); padding: 14px 16px; margin-bottom: 14px;
}
.cp-form-section-title {
    font-size: 10.5px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.8px; color: var(--text3); margin-bottom: 12px;
    display: flex; align-items: center; gap: 6px;
}
.cp-2col { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.cp-3col { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px; }

.cp-type-card {
    display: flex; flex-direction: column; align-items: center; gap: 5px;
    padding: 12px 8px; border-radius: var(--radius-sm);
    border: 1.5px solid var(--border2); background: var(--bg2);
    cursor: pointer; transition: all 0.15s; user-select: none; text-align: center;
}
.cp-type-card:hover { border-color: var(--gold); }
.cp-type-card.selected { border-color: var(--gold); background: var(--gold-dim); }
.cp-type-card-icon { font-size: 22px; }
.cp-type-card-label { font-size: 11.5px; font-weight: 700; color: var(--text); }
.cp-type-card-sub   { font-size: 10px; color: var(--text3); }

.cp-upload-zone {
    border: 2px dashed var(--border2); border-radius: var(--radius-sm);
    padding: 16px; text-align: center; cursor: pointer;
    transition: border-color 0.15s; display: flex; align-items: center; gap: 12px;
}
.cp-upload-zone:hover { border-color: var(--gold); }
.cp-upload-icon { font-size: 22px; flex-shrink: 0; }
.cp-upload-text { text-align: left; }
.cp-upload-label { font-size: 12px; font-weight: 600; color: var(--text); }
.cp-upload-sub   { font-size: 10.5px; color: var(--text3); margin-top: 2px; }
.cp-upload-status { margin-left: auto; flex-shrink: 0; font-size: 11px; }

/* IA Analyse section in modal */
.cp-ia-analyse-panel {
    background: linear-gradient(135deg, rgba(167,139,250,0.07), rgba(201,168,76,0.05));
    border: 1px solid rgba(167,139,250,0.2); border-radius: var(--radius-sm);
    padding: 14px 16px; margin-bottom: 14px;
}
.cp-ia-analyse-title {
    font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px;
    color: var(--purple); display: flex; align-items: center; gap: 6px; margin-bottom: 10px;
}
.cp-ia-dots {
    display: flex; align-items: center; gap: 3px; margin-left: auto;
}
.cp-ia-dot {
    width: 4px; height: 4px; border-radius: 50%; background: var(--gold);
    animation: cp-think 1.3s ease-in-out infinite;
}
.cp-ia-dot:nth-child(2) { animation-delay: 0.2s; }
.cp-ia-dot:nth-child(3) { animation-delay: 0.4s; }
@keyframes cp-think { 0%,100% { opacity:0.2; transform:scale(0.8); } 50% { opacity:1; transform:scale(1.2); } }

.cp-ia-result-row {
    display: flex; align-items: center; gap: 8px;
    padding: 7px 10px; border-radius: var(--radius-sm);
    margin-bottom: 6px; font-size: 12px;
}
.cp-ia-result-row:last-child { margin-bottom: 0; }
.cp-ia-result-row.ok   { background: var(--green-dim); color: var(--green); }
.cp-ia-result-row.fail { background: var(--red-dim);   color: var(--red); }
.cp-ia-result-row.warn { background: var(--amber-dim); color: var(--amber); }
.cp-ia-result-row.info { background: var(--blue-dim);  color: var(--blue); }

/* ── CARD PREVIEW (visual card) ── */
.cp-preview-card {
    background: linear-gradient(135deg, #1e2228, #14181e);
    border: 1px solid var(--border2);
    border-radius: 12px;
    padding: 18px 20px;
    position: relative;
    overflow: hidden;
    margin-bottom: 14px;
}
.cp-preview-card::before {
    content: '🇹🇳';
    position: absolute; right: 16px; top: 14px;
    font-size: 20px;
}
.cp-preview-card::after {
    content: '';
    position: absolute; top: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--gold), var(--gold2), var(--gold));
}
.cp-preview-header { margin-bottom: 14px; }
.cp-preview-ministry {
    font-size: 8.5px; text-transform: uppercase; letter-spacing: 1.5px;
    color: var(--gold); font-weight: 700; margin-bottom: 2px;
}
.cp-preview-title {
    font-size: 12px; font-weight: 800; color: var(--text);
    text-transform: uppercase; letter-spacing: 1px;
}
.cp-preview-body {
    display: flex; gap: 14px; align-items: flex-start;
}
.cp-preview-photo {
    width: 52px; height: 52px; border-radius: 8px;
    background: var(--bg4); border: 1px solid var(--border2);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; flex-shrink: 0;
}
.cp-preview-details { flex: 1; }
.cp-preview-name-big { font-size: 14px; font-weight: 800; color: var(--text); margin-bottom: 4px; }
.cp-preview-field {
    font-size: 10px; color: var(--text3); margin-bottom: 2px;
    display: flex; gap: 6px;
}
.cp-preview-field-label { min-width: 60px; }
.cp-preview-field-val   { color: var(--text2); font-weight: 600; }
.cp-preview-footer {
    display: flex; align-items: center; justify-content: space-between;
    margin-top: 12px; padding-top: 10px; border-top: 1px solid var(--border);
}
.cp-preview-num { font-family: var(--font-mono); font-size: 10.5px; color: var(--gold); font-weight: 700; }
.cp-preview-qr  {
    width: 36px; height: 36px; background: var(--text);
    border-radius: 4px; display: flex; align-items: center; justify-content: center;
    font-size: 18px;
}
.cp-preview-validity { font-size: 9px; color: var(--text3); }
</style>

{{-- ════════ KPI ROW ════════ --}}
<div class="cp-kpi-row">
    <div class="cp-kpi">
        <div class="cp-kpi-icon" style="background:var(--gold-dim);">💳</div>
        <div>
            <div class="cp-kpi-val" style="color:var(--gold);">1,247</div>
            <div class="cp-kpi-lbl">Cartes délivrées</div>
            <div class="cp-kpi-delta" style="color:var(--green);">↑ +12% ce mois</div>
        </div>
    </div>
    <div class="cp-kpi">
        <div class="cp-kpi-icon" style="background:var(--green-dim);">✅</div>
        <div>
            <div class="cp-kpi-val" style="color:var(--green);">856</div>
            <div class="cp-kpi-lbl">Validées actives</div>
            <div class="cp-kpi-delta" style="color:var(--teal);">→ En règle</div>
        </div>
    </div>
    <div class="cp-kpi">
        <div class="cp-kpi-icon" style="background:var(--amber-dim);">⏳</div>
        <div>
            <div class="cp-kpi-val" style="color:var(--amber);">234</div>
            <div class="cp-kpi-lbl">En attente</div>
            <div class="cp-kpi-delta" style="color:var(--amber);">→ Docs manquants</div>
        </div>
    </div>
    <div class="cp-kpi">
        <div class="cp-kpi-icon" style="background:var(--red-dim);">🚫</div>
        <div>
            <div class="cp-kpi-val" style="color:var(--red);">32</div>
            <div class="cp-kpi-lbl">Suspendues</div>
            <div class="cp-kpi-delta" style="color:var(--green);">↓ -5% vs mois dernier</div>
        </div>
    </div>
    <div class="cp-kpi">
        <div class="cp-kpi-icon" style="background:var(--purple-dim);">🤖</div>
        <div>
            <div class="cp-kpi-val" style="color:var(--purple);">96%</div>
            <div class="cp-kpi-lbl">Fiabilité IA</div>
            <div class="cp-kpi-delta" style="color:var(--purple);">→ Vérifications auto</div>
        </div>
    </div>
</div>

{{-- ════════ IA SMART BANNER ════════ --}}
<div class="cp-ia-banner">
    <div class="cp-ia-orb">🤖</div>
    <div class="cp-ia-body">
        <div class="cp-ia-title">
            IA Dossier Analyser — 4 alertes détectées
            <span style="font-size:10px; padding:2px 8px; background:var(--gold-dim); color:var(--gold); border-radius:20px; font-weight:700;">LIVE</span>
        </div>
        <div class="cp-ia-chips">
            <div class="cp-chip" style="background:var(--red-dim); color:var(--red); border:1px solid rgba(248,113,113,0.2);" onclick="showToast('3 artistes avec documents non valides — Action requise', 'info')">
                🔴 3 dossiers suspects — Documents invalides détectés
            </div>
            <div class="cp-chip" style="background:var(--amber-dim); color:var(--amber); border:1px solid rgba(251,191,36,0.2);" onclick="showToast('7 dossiers incomplets — Diplômes manquants', 'info')">
                ⚠️ 7 diplômes incomplets
            </div>
            <div class="cp-chip" style="background:var(--teal-dim); color:var(--teal); border:1px solid rgba(45,212,191,0.2);" onclick="showToast('5 artistes: CNSS expirée — Renouvellement requis', 'info')">
                📋 5 CNSS expirées
            </div>
            <div class="cp-chip" style="background:var(--purple-dim); color:var(--purple); border:1px solid rgba(167,139,250,0.2);" onclick="showToast('12 cartes expirent dans les 30 prochains jours', 'info')">
                ✨ 12 cartes expirent dans 30j — Relance auto disponible
            </div>
        </div>
    </div>
    <div style="display:flex; gap:8px; flex-shrink:0;">
        <button class="btn btn-outline btn-sm" onclick="showToast('Rapport IA généré', 'info')">📊 Rapport IA</button>
        <button class="btn btn-gold btn-sm" onclick="openModal('modal-add-carte')">+ Nouvelle Carte</button>
    </div>
</div>

{{-- ════════ MAIN SHELL ════════ --}}
<div class="cp-shell">

    {{-- ══ LEFT: CARDS + TABLE ══ --}}
    <div>

        {{-- Filter/Search bar --}}
        <div class="cp-topbar">
            <div class="cp-filter-tabs">
                <div class="cp-ftab active" onclick="cpFilter(this,'all')">Toutes (1,247)</div>
                <div class="cp-ftab" onclick="cpFilter(this,'validee')">✅ Validées (856)</div>
                <div class="cp-ftab" onclick="cpFilter(this,'attente')">⏳ Attente (234)</div>
                <div class="cp-ftab" onclick="cpFilter(this,'suspendue')">🚫 Suspendues (32)</div>
                <div class="cp-ftab" onclick="cpFilter(this,'alerte')">🔴 Alertes IA (15)</div>
            </div>
            <input type="text" class="cp-search" placeholder="🔍 Rechercher par nom, n° carte, spécialité..." oninput="cpSearch(this.value)">
            <select class="cp-select" onchange="showToast('Filtre appliqué','info')">
                <option>Tous types</option>
                <option>Musicien</option>
                <option>Danseur</option>
                <option>Instrumentiste</option>
            </select>
            <button class="btn btn-outline btn-sm" onclick="cpToggleView()" id="cp-view-toggle" title="Basculer vue">⊞</button>
        </div>

        {{-- CARD GRID VIEW --}}
        <div class="cp-grid" id="cp-grid">

            @php
            $artists = [
                [
                    'id' => 1, 'name' => 'Ahmed Ben Ali', 'initials' => 'AB',
                    'av_bg' => 'linear-gradient(135deg,var(--gold),#a07830)',
                    'av_color' => '#111',
                    'num' => 'CPA-2024-0847',
                    'type' => 'Musicien', 'type_icon' => '🎵',
                    'specialite' => 'Musique Arabe Classique',
                    'status' => 'validee', 'status_label' => 'Validée', 'status_class' => 'green',
                    'strip' => '',
                    'debut' => '12/04/2026', 'fin' => '12/04/2027',
                    'jours_restants' => 365, 'jours_max' => 365,
                    'cnss' => 'ok', 'diplome' => 'ok', 'cni' => 'ok',
                    'ia_score' => 98, 'ia_color' => 'var(--green)',
                    'ia_alert' => null,
                ],
                [
                    'id' => 2, 'name' => 'Fatima Kaddour', 'initials' => 'FK',
                    'av_bg' => 'linear-gradient(135deg,var(--purple),#6344c2)',
                    'av_color' => '#fff',
                    'num' => 'CPA-2024-0231',
                    'type' => 'Danseuse', 'type_icon' => '💃',
                    'specialite' => 'Danse Folklorique',
                    'status' => 'attente', 'status_label' => 'En attente', 'status_class' => 'amber',
                    'strip' => 'amber',
                    'debut' => '—', 'fin' => 'Docs manquants',
                    'jours_restants' => 0, 'jours_max' => 365,
                    'cnss' => 'warn', 'diplome' => 'miss', 'cni' => 'ok',
                    'ia_score' => 54, 'ia_color' => 'var(--amber)',
                    'ia_alert' => 'Diplôme manquant',
                ],
                [
                    'id' => 3, 'name' => 'Mohamed Saïd', 'initials' => 'MS',
                    'av_bg' => 'linear-gradient(135deg,var(--teal),#1a8f80)',
                    'av_color' => '#fff',
                    'num' => 'CPA-2024-0612',
                    'type' => 'Instrumentiste', 'type_icon' => '🎸',
                    'specialite' => 'Oud',
                    'status' => 'validee', 'status_label' => 'Validée', 'status_class' => 'teal',
                    'strip' => 'teal',
                    'debut' => '05/03/2026', 'fin' => '05/03/2027',
                    'jours_restants' => 330, 'jours_max' => 365,
                    'cnss' => 'ok', 'diplome' => 'ok', 'cni' => 'ok',
                    'ia_score' => 95, 'ia_color' => 'var(--green)',
                    'ia_alert' => null,
                ],
                [
                    'id' => 4, 'name' => 'Leila Saidi', 'initials' => 'LS',
                    'av_bg' => 'linear-gradient(135deg,var(--red),#a03030)',
                    'av_color' => '#fff',
                    'num' => 'CPA-2023-0188',
                    'type' => 'Musicienne', 'type_icon' => '🎤',
                    'specialite' => 'Chant Malouf',
                    'status' => 'suspendue', 'status_label' => 'Suspendue', 'status_class' => 'red',
                    'strip' => 'red',
                    'debut' => '—', 'fin' => 'Suspendue',
                    'jours_restants' => 0, 'jours_max' => 365,
                    'cnss' => 'miss', 'diplome' => 'ok', 'cni' => 'warn',
                    'ia_score' => 21, 'ia_color' => 'var(--red)',
                    'ia_alert' => 'CNSS expirée + CNI invalide',
                ],
                [
                    'id' => 5, 'name' => 'Karim Mbarki', 'initials' => 'KM',
                    'av_bg' => 'linear-gradient(135deg,var(--blue),#1560a8)',
                    'av_color' => '#fff',
                    'num' => 'CPA-2024-0934',
                    'type' => 'Musicien', 'type_icon' => '🎷',
                    'specialite' => 'Saxophone Jazz',
                    'status' => 'validee', 'status_label' => 'Validée', 'status_class' => 'green',
                    'strip' => '',
                    'debut' => '20/01/2026', 'fin' => '20/01/2027',
                    'jours_restants' => 280, 'jours_max' => 365,
                    'cnss' => 'ok', 'diplome' => 'ok', 'cni' => 'ok',
                    'ia_score' => 91, 'ia_color' => 'var(--green)',
                    'ia_alert' => null,
                ],
                [
                    'id' => 6, 'name' => 'Rania Gharbi', 'initials' => 'RG',
                    'av_bg' => 'linear-gradient(135deg,var(--amber),#b07800)',
                    'av_color' => '#111',
                    'num' => 'CPA-2024-0743',
                    'type' => 'Danseuse', 'type_icon' => '🩰',
                    'specialite' => 'Danse Orientale',
                    'status' => 'attente', 'status_label' => 'En attente', 'status_class' => 'amber',
                    'strip' => 'amber',
                    'debut' => '—', 'fin' => 'En vérification',
                    'jours_restants' => 0, 'jours_max' => 365,
                    'cnss' => 'ok', 'diplome' => 'warn', 'cni' => 'ok',
                    'ia_score' => 67, 'ia_color' => 'var(--amber)',
                    'ia_alert' => 'Diplôme en cours de vérification',
                ],
            ];

            $docLabels = ['cnss' => 'CNSS', 'diplome' => 'Diplôme', 'cni' => 'CNI'];
            $docIcons  = ['ok' => '✓', 'miss' => '✕', 'warn' => '!'];
            @endphp

            @foreach($artists as $a)
            <div class="cp-card"
                 data-id="{{ $a['id'] }}"
                 data-status="{{ $a['status'] }}"
                 data-name="{{ strtolower($a['name']) }}"
                 data-alert="{{ $a['ia_alert'] ? 'alerte' : '' }}"
                 style="animation-delay: {{ $loop->index * 0.05 }}s"
                 onclick="cpOpenDetail({{ $a['id'] }})">

                <div class="cp-card-strip {{ $a['strip'] }}"></div>

                <div class="cp-card-head">
                    <div class="cp-card-av" style="background:{{ $a['av_bg'] }}; color:{{ $a['av_color'] }};">{{ $a['initials'] }}</div>
                    <div class="cp-card-info">
                        <div class="cp-card-name">{{ $a['name'] }}</div>
                        <div class="cp-card-num">{{ $a['num'] }}</div>
                        <div class="cp-card-type">{{ $a['type_icon'] }} {{ $a['type'] }} · {{ $a['specialite'] }}</div>
                    </div>
                    <div class="cp-card-badges">
                        <span class="badge {{ $a['status_class'] }}" style="font-size:10px;">{{ $a['status_label'] }}</span>
                        @if($a['ia_alert'])
                            <span style="font-size:9px; padding:2px 7px; background:var(--red-dim); color:var(--red); border-radius:10px; font-weight:700; text-align:right; max-width:100px; line-height:1.3;">⚡ {{ $a['ia_alert'] }}</span>
                        @endif
                    </div>
                </div>

                <div class="cp-card-body">
                    <div class="cp-card-row">
                        <span class="cp-card-row-icon">📅</span>
                        <span class="cp-card-row-label">Validité</span>
                        <span class="cp-card-row-val">{{ $a['debut'] }} → {{ $a['fin'] }}</span>
                    </div>
                    <div class="cp-card-row">
                        <span class="cp-card-row-icon">🎵</span>
                        <span class="cp-card-row-label">Spécialité</span>
                        <span class="cp-card-row-val">{{ $a['specialite'] }}</span>
                    </div>
                </div>

                {{-- Expiry progress --}}
                @if($a['jours_restants'] > 0)
                <div class="cp-expiry-bar">
                    <div class="cp-expiry-row">
                        <span class="cp-expiry-label">Expiration</span>
                        <span class="cp-expiry-days" style="color:{{ $a['jours_restants'] < 60 ? 'var(--red)' : ($a['jours_restants'] < 120 ? 'var(--amber)' : 'var(--green)') }}">{{ $a['jours_restants'] }}j restants</span>
                    </div>
                    <div class="cp-expiry-track">
                        <div class="cp-expiry-fill" style="width:{{ round(($a['jours_restants']/$a['jours_max'])*100) }}%; background:{{ $a['jours_restants'] < 60 ? 'var(--red)' : ($a['jours_restants'] < 120 ? 'var(--amber)' : 'var(--green)') }};"></div>
                    </div>
                </div>
                @endif

                {{-- IA doc status chips --}}
                <div class="cp-doc-status">
                    @foreach(['cnss','diplome','cni'] as $doc)
                        <span class="cp-doc-chip {{ $a[$doc] }}">
                            {{ $docIcons[$a[$doc]] }} {{ $docLabels[$doc] }}
                        </span>
                    @endforeach
                </div>

                <div class="cp-card-foot" onclick="event.stopPropagation()">
                    <button class="cp-fbt" onclick="cpOpenDetail({{ $a['id'] }})">👁 Voir</button>
                    @if($a['status'] === 'attente')
                        <button class="cp-fbt green" onclick="showToast('Carte validée — {{ $a['name'] }}', 'success')">✓ Valider</button>
                        <button class="cp-fbt red" onclick="showToast('Dossier rejeté — {{ $a['name'] }}', 'error')">✕ Rejeter</button>
                    @elseif($a['status'] === 'suspendue')
                        <button class="cp-fbt green" onclick="showToast('Carte réactivée — {{ $a['name'] }}', 'success')">🔄 Réactiver</button>
                    @else
                        <button class="cp-fbt" onclick="showToast('PDF carte téléchargé', 'info')">📄 PDF</button>
                        <button class="cp-fbt teal" onclick="showToast('QR Code {{ $a['num'] }} affiché', 'info')">🔗 QR</button>
                    @endif
                    <span class="cp-ia-score" onclick="cpOpenDetail({{ $a['id'] }})">🤖 {{ $a['ia_score'] }}%</span>
                </div>
            </div>
            @endforeach

        </div>{{-- end cp-grid --}}

        {{-- TABLE VIEW (hidden by default) --}}
        <div id="cp-table-view" style="display:none;" class="panel">
            <div class="panel-head">
                <div><div class="panel-title">📋 Liste des Cartes</div><div class="panel-sub">Vue tabulaire complète</div></div>
                <button class="btn btn-outline btn-sm" onclick="showToast('Export CSV généré', 'info')">📥 Exporter</button>
            </div>
            <div class="panel-body no-pad">
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Artiste</th>
                                <th>N° Carte</th>
                                <th>Type / Spécialité</th>
                                <th>Statut</th>
                                <th>Validité</th>
                                <th>Score IA</th>
                                <th>Docs</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($artists as $a)
                            <tr>
                                <td>
                                    <div style="display:flex; align-items:center; gap:9px;">
                                        <div style="width:28px; height:28px; border-radius:50%; background:{{ $a['av_bg'] }}; color:{{ $a['av_color'] }}; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:700; flex-shrink:0;">{{ $a['initials'] }}</div>
                                        <strong>{{ $a['name'] }}</strong>
                                    </div>
                                </td>
                                <td style="font-family:var(--font-mono); font-size:11px;">{{ $a['num'] }}</td>
                                <td>{{ $a['type_icon'] }} {{ $a['type'] }}<br><span style="font-size:11px; color:var(--text3);">{{ $a['specialite'] }}</span></td>
                                <td><span class="badge {{ $a['status_class'] }}">{{ $a['status_label'] }}</span></td>
                                <td style="font-size:11.5px;">{{ $a['debut'] }}<br><span style="color:var(--text3);">→ {{ $a['fin'] }}</span></td>
                                <td>
                                    <span style="font-family:var(--font-mono); font-weight:800; color:{{ $a['ia_color'] }}; font-size:13px;">{{ $a['ia_score'] }}%</span>
                                </td>
                                <td>
                                    <div style="display:flex; gap:3px; flex-wrap:wrap;">
                                        <span class="cp-doc-chip {{ $a['cnss'] }}" style="font-size:9px;">CNSS</span>
                                        <span class="cp-doc-chip {{ $a['diplome'] }}" style="font-size:9px;">Dip.</span>
                                        <span class="cp-doc-chip {{ $a['cni'] }}" style="font-size:9px;">CNI</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="row-actions">
                                        <button class="btn btn-ghost btn-sm" onclick="cpOpenDetail({{ $a['id'] }})">👁️</button>
                                        <button class="btn btn-ghost btn-sm" onclick="showToast('PDF généré', 'info')">📄</button>
                                        <button class="btn btn-ghost btn-sm" onclick="showToast('QR affiché', 'info')">🔗</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>{{-- end left --}}

    {{-- ══ RIGHT SIDEBAR ══ --}}
    <div class="cp-sidebar">

        {{-- Expirations à venir --}}
        <div class="cp-sb-panel">
            <div class="cp-sb-head">
                ⏰ Expirations imminentes
                <span style="font-size:10px; padding:2px 8px; background:var(--amber-dim); color:var(--amber); border-radius:10px; font-weight:700;">12 dans 30j</span>
            </div>
            <div class="cp-expiry-item" onclick="cpOpenDetail(1)">
                <div class="cp-expiry-av" style="background:var(--red-dim); color:var(--red);">NB</div>
                <div class="cp-expiry-info">
                    <div class="cp-expiry-name">Nadia Bellal</div>
                    <div class="cp-expiry-when">Expire le 18/04/2026</div>
                </div>
                <span class="cp-expiry-badge" style="background:var(--red-dim); color:var(--red);">5j</span>
            </div>
            <div class="cp-expiry-item">
                <div class="cp-expiry-av" style="background:var(--amber-dim); color:var(--amber);">HB</div>
                <div class="cp-expiry-info">
                    <div class="cp-expiry-name">Hassan Ben Youssef</div>
                    <div class="cp-expiry-when">Expire le 25/04/2026</div>
                </div>
                <span class="cp-expiry-badge" style="background:var(--amber-dim); color:var(--amber);">12j</span>
            </div>
            <div class="cp-expiry-item">
                <div class="cp-expiry-av" style="background:var(--amber-dim); color:var(--amber);">SR</div>
                <div class="cp-expiry-info">
                    <div class="cp-expiry-name">Sana Romdhani</div>
                    <div class="cp-expiry-when">Expire le 02/05/2026</div>
                </div>
                <span class="cp-expiry-badge" style="background:var(--amber-dim); color:var(--amber);">19j</span>
            </div>
            <div style="padding: 10px 14px; border-top: 1px solid var(--border);">
                <button style="width:100%; text-align:center; padding:8px; background:var(--gold-dim); border:1px solid rgba(201,168,76,0.25); border-radius:var(--radius-sm); font-size:11.5px; font-weight:700; color:var(--gold); cursor:pointer;" onclick="showToast('Emails de renouvellement envoyés aux 12 artistes!', 'success')">
                    📧 Relancer les 12 artistes par IA
                </button>
            </div>
        </div>

        {{-- IA Suggestions --}}
        <div class="cp-sb-panel">
            <div class="cp-sb-head">🤖 Actions recommandées par l'IA</div>
            <div class="cp-ia-suggestions">
                <div class="cp-sugg-item" onclick="showToast('Email de relance envoyé à Fatima Kaddour', 'success')">
                    <div class="cp-sugg-icon">📋</div>
                    <div>
                        <div class="cp-sugg-text">Fatima Kaddour — Diplôme manquant depuis 8 jours</div>
                        <div class="cp-sugg-cta">→ Envoyer relance automatique</div>
                    </div>
                </div>
                <div class="cp-sugg-item" onclick="showToast('Dossier Leila Saidi escaladé au responsable', 'success')">
                    <div class="cp-sugg-icon">🚫</div>
                    <div>
                        <div class="cp-sugg-text">Leila Saidi — CNSS + CNI invalides · Score IA: 21%</div>
                        <div class="cp-sugg-cta">→ Escalader au responsable</div>
                    </div>
                </div>
                <div class="cp-sugg-item" onclick="showToast('Rapport CNSS expirées généré', 'info')">
                    <div class="cp-sugg-icon">⚠️</div>
                    <div>
                        <div class="cp-sugg-text">5 artistes avec CNSS expirée — renouvellement urgent</div>
                        <div class="cp-sugg-cta">→ Générer liste + courriers</div>
                    </div>
                </div>
                <div class="cp-sugg-item" onclick="showToast('Validation groupée lancée sur 4 dossiers complets', 'success')">
                    <div class="cp-sugg-icon">✅</div>
                    <div>
                        <div class="cp-sugg-text">4 dossiers complets et vérifiés — validation possible en lot</div>
                        <div class="cp-sugg-cta">→ Valider en 1 clic</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats répartition --}}
        <div class="cp-sb-panel">
            <div class="cp-sb-head">📊 Répartition par type</div>
            <div class="cp-stat-ring-row">
                <div class="cp-stat-ring">
                    <div class="cp-stat-ring-val" style="color:var(--gold);">612</div>
                    <div class="cp-stat-ring-lbl">🎵 Musiciens</div>
                </div>
                <div class="cp-stat-ring">
                    <div class="cp-stat-ring-val" style="color:var(--purple);">348</div>
                    <div class="cp-stat-ring-lbl">💃 Danseurs</div>
                </div>
                <div class="cp-stat-ring">
                    <div class="cp-stat-ring-val" style="color:var(--teal);">287</div>
                    <div class="cp-stat-ring-lbl">🎸 Instrument.</div>
                </div>
                <div class="cp-stat-ring">
                    <div class="cp-stat-ring-val" style="color:var(--blue);">96%</div>
                    <div class="cp-stat-ring-lbl">🤖 Score IA moy.</div>
                </div>
            </div>
        </div>

        {{-- Activité récente --}}
        <div class="cp-sb-panel">
            <div class="cp-sb-head">📜 Activité récente</div>
            <div style="padding: 0;">
                <div class="cp-expiry-item">
                    <div class="feed-dot gold" style="width:7px;height:7px;border-radius:50%;background:var(--gold);flex-shrink:0;margin-top:4px;"></div>
                    <div class="cp-expiry-info">
                        <div class="cp-expiry-name">Carte renouvelée — Ahmed Ben Ali</div>
                        <div class="cp-expiry-when">il y a 2 heures</div>
                    </div>
                </div>
                <div class="cp-expiry-item">
                    <div class="feed-dot" style="width:7px;height:7px;border-radius:50%;background:var(--teal);flex-shrink:0;margin-top:4px;"></div>
                    <div class="cp-expiry-info">
                        <div class="cp-expiry-name">Carte validée — Mohamed Saïd</div>
                        <div class="cp-expiry-when">il y a 5 heures</div>
                    </div>
                </div>
                <div class="cp-expiry-item">
                    <div class="feed-dot" style="width:7px;height:7px;border-radius:50%;background:var(--amber);flex-shrink:0;margin-top:4px;"></div>
                    <div class="cp-expiry-info">
                        <div class="cp-expiry-name">Docs demandés — Fatima Kaddour</div>
                        <div class="cp-expiry-when">il y a 1 jour</div>
                    </div>
                </div>
                <div class="cp-expiry-item">
                    <div class="feed-dot" style="width:7px;height:7px;border-radius:50%;background:var(--red);flex-shrink:0;margin-top:4px;"></div>
                    <div class="cp-expiry-info">
                        <div class="cp-expiry-name">Carte suspendue — Leila Saidi</div>
                        <div class="cp-expiry-when">il y a 2 jours</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick actions --}}
        <div class="cp-sb-panel">
            <div class="cp-sb-head">⚡ Actions rapides</div>
            <div class="cp-quick">
                <button class="cp-qa" onclick="showToast('Export Excel généré', 'info')">📥 Exporter toutes les cartes</button>
                <button class="cp-qa" onclick="showToast('Analyse IA complète lancée sur 1,247 dossiers', 'info')">🤖 Analyse IA complète</button>
                <button class="cp-qa" onclick="showToast('12 emails de renouvellement envoyés', 'success')">📧 Relances renouvellements</button>
                <button class="cp-qa" onclick="showToast('Rapport des suspensions généré', 'info')">🚫 Rapport suspensions</button>
                <button class="cp-qa" onclick="showToast('Impression par lot lancée', 'info')">🖨️ Impression par lot</button>
            </div>
        </div>

    </div>{{-- end sidebar --}}
</div>{{-- end cp-shell --}}


{{-- ════════════════════════════════════════════
     MODAL — DÉTAIL CARTE / IA ANALYSE
════════════════════════════════════════════ --}}
<div class="modal" id="modal-detail-carte">
    <div class="modal-content cp-modal-wide">
        <div class="modal-header">
            <div class="modal-title" style="display:flex; align-items:center; gap:10px;">
                💳 Détail Carte Professionnelle
                <span id="cpd-status-badge" class="badge green" style="font-size:10px;">Validée</span>
            </div>
            <button class="modal-close" onclick="closeModal('modal-detail-carte')">✕</button>
        </div>
        <div class="modal-body">

            {{-- Card preview --}}
            <div class="cp-preview-card" id="cpd-preview">
                <div class="cp-preview-header">
                    <div class="cp-preview-ministry">République Tunisienne — Ministère des Affaires Culturelles</div>
                    <div class="cp-preview-title">Carte Professionnelle Artistique</div>
                </div>
                <div class="cp-preview-body">
                    <div class="cp-preview-photo" id="cpd-photo">👤</div>
                    <div class="cp-preview-details">
                        <div class="cp-preview-name-big" id="cpd-name">Ahmed Ben Ali</div>
                        <div class="cp-preview-field">
                            <span class="cp-preview-field-label">Type</span>
                            <span class="cp-preview-field-val" id="cpd-type">Musicien</span>
                        </div>
                        <div class="cp-preview-field">
                            <span class="cp-preview-field-label">Spécialité</span>
                            <span class="cp-preview-field-val" id="cpd-spec">Musique Arabe Classique</span>
                        </div>
                        <div class="cp-preview-field">
                            <span class="cp-preview-field-label">Validité</span>
                            <span class="cp-preview-field-val" id="cpd-validite">12/04/2026 → 12/04/2027</span>
                        </div>
                    </div>
                </div>
                <div class="cp-preview-footer">
                    <div>
                        <div class="cp-preview-num" id="cpd-num">CPA-2024-0847</div>
                        <div class="cp-preview-validity" id="cpd-validity-label">Direction Musique & Danse — DMD</div>
                    </div>
                    <div class="cp-preview-qr">⬛</div>
                </div>
            </div>

            {{-- IA Dossier Analyser --}}
            <div class="cp-ia-analyse-panel">
                <div class="cp-ia-analyse-title" style="display:flex; align-items:center; justify-content:space-between;">
                    <span style="display:flex; align-items:center; gap:6px;">🤖 IA Dossier Analyser — Score de conformité</span>
                    <div class="cp-ia-dots"><div class="cp-ia-dot"></div><div class="cp-ia-dot"></div><div class="cp-ia-dot"></div></div>
                </div>
                <div style="display:flex; align-items:center; gap:16px; margin-bottom:12px;">
                    <div style="text-align:center; padding:10px 16px; background:var(--bg3); border-radius:var(--radius-sm); border:1px solid var(--border);">
                        <div style="font-size:28px; font-weight:900; font-family:var(--font-mono); color:var(--green);" id="cpd-ia-score">98%</div>
                        <div style="font-size:9.5px; color:var(--text3); text-transform:uppercase; letter-spacing:0.5px; margin-top:3px;">Score IA</div>
                    </div>
                    <div id="cpd-ia-results" style="flex:1; display:flex; flex-direction:column; gap:5px;"></div>
                </div>
                <div id="cpd-ia-reco" style="font-size:12px; color:var(--text2); padding:10px 12px; background:var(--bg3); border-radius:var(--radius-sm); border-left:3px solid var(--purple); line-height:1.55;"></div>
            </div>

            {{-- Info fields --}}
            <div class="cp-2col" style="gap:10px; margin-bottom:14px;">
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Nom complet</label>
                    <input type="text" class="form-input" id="cpd-input-name" value="Ahmed Ben Ali">
                </div>
                <div class="form-group" style="margin:0;">
                    <label class="form-label">N° Carte</label>
                    <input type="text" class="form-input" id="cpd-input-num" value="CPA-2024-0847" readonly style="font-family:var(--font-mono);">
                </div>
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Type d'artiste</label>
                    <input type="text" class="form-input" id="cpd-input-type" value="Musicien">
                </div>
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Spécialité</label>
                    <input type="text" class="form-input" id="cpd-input-spec" value="Musique Arabe Classique">
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('modal-detail-carte')">Fermer</button>
            <button class="btn btn-outline" onclick="showToast('PDF de la carte téléchargé', 'info')">📄 Télécharger PDF</button>
            <button class="btn btn-outline" onclick="showToast('QR Code affiché', 'info')">🔗 QR Code</button>
            <button class="btn btn-gold" onclick="showToast('Modifications sauvegardées', 'success')">💾 Sauvegarder</button>
        </div>
    </div>
</div>


{{-- ════════════════════════════════════════════
     MODAL — NOUVELLE CARTE PROFESSIONNELLE
════════════════════════════════════════════ --}}
<div class="modal" id="modal-add-carte">
    <div class="modal-content cp-modal-wide">
        <div class="modal-header">
            <div class="modal-title">💳 Nouvelle Carte Professionnelle</div>
            <button class="modal-close" onclick="closeModal('modal-add-carte')">✕</button>
        </div>
        <div class="modal-body">

            {{-- Section identité --}}
            <div class="cp-form-section">
                <div class="cp-form-section-title">👤 Identité de l'artiste</div>
                <div class="cp-2col" style="margin-bottom:10px;">
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">Prénom *</label>
                        <input type="text" class="form-input" placeholder="Ex: Ahmed" id="nc-prenom">
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">Nom *</label>
                        <input type="text" class="form-input" placeholder="Ex: Ben Ali" id="nc-nom">
                    </div>
                </div>
                <div class="cp-2col">
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-input" placeholder="artiste@email.tn">
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">Numéro CNI *</label>
                        <input type="text" class="form-input" placeholder="0X XXXXXXX" id="nc-cni">
                    </div>
                </div>
            </div>

            {{-- Type artiste --}}
            <div class="cp-form-section">
                <div class="cp-form-section-title">🎵 Type d'artiste *</div>
                <div class="cp-3col">
                    <div class="cp-type-card" onclick="cpSelectType(this, 'Musicien')">
                        <div class="cp-type-card-icon">🎵</div>
                        <div class="cp-type-card-label">Musicien</div>
                        <div class="cp-type-card-sub">Musique arabe, classique, moderne</div>
                    </div>
                    <div class="cp-type-card" onclick="cpSelectType(this, 'Danseur')">
                        <div class="cp-type-card-icon">💃</div>
                        <div class="cp-type-card-label">Danseur</div>
                        <div class="cp-type-card-sub">Folklorique, orientale, moderne</div>
                    </div>
                    <div class="cp-type-card" onclick="cpSelectType(this, 'Instrumentiste')">
                        <div class="cp-type-card-icon">🎸</div>
                        <div class="cp-type-card-label">Instrumentiste</div>
                        <div class="cp-type-card-sub">Oud, Qanun, Violon, etc.</div>
                    </div>
                </div>
            </div>

            {{-- Spécialité --}}
            <div class="cp-form-section">
                <div class="cp-form-section-title">🎼 Spécialité & diplôme</div>
                <div class="form-group" style="margin-bottom:10px;">
                    <label class="form-label">Spécialité artistique *</label>
                    <input type="text" class="form-input" placeholder="Ex: Musique Arabe Classique, Oud, Danse Folklorique...">
                </div>
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Niveau de formation</label>
                    <select class="form-select">
                        <option>Diplôme national — Institut Salah Mahdi</option>
                        <option>Diplôme de musique arabe</option>
                        <option>Diplôme d'instrumentiste</option>
                        <option>Certificat de réussite</option>
                        <option>Formation non certifiée</option>
                    </select>
                </div>
            </div>

            {{-- Documents --}}
            <div class="cp-form-section">
                <div class="cp-form-section-title">📄 Documents requis</div>
                <div style="display:flex; flex-direction:column; gap:8px;">
                    <div class="cp-upload-zone">
                        <div class="cp-upload-icon">🎓</div>
                        <div class="cp-upload-text">
                            <div class="cp-upload-label">Diplôme artistique *</div>
                            <div class="cp-upload-sub">PDF ou image — max 5MB</div>
                        </div>
                        <div class="cp-upload-status" style="color:var(--text3);">Non téléchargé</div>
                    </div>
                    <div class="cp-upload-zone">
                        <div class="cp-upload-icon">🆔</div>
                        <div class="cp-upload-text">
                            <div class="cp-upload-label">Carte Nationale d'Identité *</div>
                            <div class="cp-upload-sub">Recto / Verso — Valide</div>
                        </div>
                        <div class="cp-upload-status" style="color:var(--text3);">Non téléchargé</div>
                    </div>
                    <div class="cp-upload-zone">
                        <div class="cp-upload-icon">📸</div>
                        <div class="cp-upload-text">
                            <div class="cp-upload-label">Photo d'identité</div>
                            <div class="cp-upload-sub">Fond blanc — récente</div>
                        </div>
                        <div class="cp-upload-status" style="color:var(--text3);">Non téléchargé</div>
                    </div>
                    <div class="cp-upload-zone">
                        <div class="cp-upload-icon">📋</div>
                        <div class="cp-upload-text">
                            <div class="cp-upload-label">Extrait casier judiciaire B3</div>
                            <div class="cp-upload-sub">Récent — moins de 3 mois</div>
                        </div>
                        <div class="cp-upload-status" style="color:var(--text3);">Non téléchargé</div>
                    </div>
                </div>
            </div>

            {{-- CNSS --}}
            <div class="cp-form-section" style="margin-bottom:0;">
                <div class="cp-form-section-title">🏢 Affiliation CNSS</div>
                <div class="cp-2col">
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">Numéro CNSS</label>
                        <input type="text" class="form-input" placeholder="Ex: 123456789" id="nc-cnss">
                    </div>
                    <div style="display:flex; align-items:flex-end;">
                        <button type="button" class="btn btn-outline" style="width:100%; height:38px;" onclick="cpVerifyCNSS()">
                            🔍 Vérifier affiliation CNSS
                        </button>
                    </div>
                </div>
                <div id="cnss-result" style="display:none; margin-top:10px; padding:9px 12px; border-radius:var(--radius-sm); font-size:12px; font-weight:600;"></div>
            </div>

            {{-- IA pre-analysis --}}
            <div class="cp-ia-analyse-panel" style="margin-top:14px; margin-bottom:0;">
                <div class="cp-ia-analyse-title">
                    🤖 IA — Pré-analyse du dossier en temps réel
                    <div class="cp-ia-dots"><div class="cp-ia-dot"></div><div class="cp-ia-dot"></div><div class="cp-ia-dot"></div></div>
                </div>
                <div class="cp-ia-result-row info">ℹ️ Remplissez les informations — l'IA analysera le dossier automatiquement</div>
                <div class="cp-ia-result-row warn" style="display:none;" id="nc-ia-warn">⚠️ Diplôme requis pour ce type d'artiste — vérification en attente</div>
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('modal-add-carte')">Annuler</button>
            <button class="btn btn-outline" onclick="cpPreview()">👁 Aperçu carte</button>
            <button class="btn btn-gold" onclick="cpCreateCard()">✨ Créer la carte</button>
        </div>
    </div>
</div>

{{-- ════════ JS ════════ --}}
<script>
// ── Artist data ──
const cpArtists = {
    1: {
        name:'Ahmed Ben Ali', initials:'AB', icon:'🎵',
        num:'CPA-2024-0847', type:'Musicien', spec:'Musique Arabe Classique',
        validite:'12/04/2026 → 12/04/2027', status:'Validée', statusClass:'green',
        iaScore: 98, iaColor:'var(--green)',
        iaResults: [
            {cls:'ok',  text:'✓ Diplôme artistique — Vérifié et valide'},
            {cls:'ok',  text:'✓ Affiliation CNSS — Active jusqu\'au 12/2026'},
            {cls:'ok',  text:'✓ CNI — Valide (expire 2028)'},
            {cls:'ok',  text:'✓ Casier judiciaire B3 — Vierge confirmé'},
        ],
        iaReco: '✅ Dossier complet et conforme à 98%. L\'IA ne détecte aucune anomalie. Carte professionnelle renouvelable automatiquement.',
    },
    2: {
        name:'Fatima Kaddour', initials:'FK', icon:'💃',
        num:'CPA-2024-0231', type:'Danseuse', spec:'Danse Folklorique',
        validite:'En attente de documents', status:'En attente', statusClass:'amber',
        iaScore: 54, iaColor:'var(--amber)',
        iaResults: [
            {cls:'fail', text:'✕ Diplôme artistique — MANQUANT (requis pour Danseuse)'},
            {cls:'warn', text:'! Affiliation CNSS — Proche de l\'expiration (45 jours)'},
            {cls:'ok',   text:'✓ CNI — Valide'},
            {cls:'ok',   text:'✓ Casier judiciaire B3 — Vierge confirmé'},
        ],
        iaReco: '⚠️ Dossier incomplet — Diplôme artistique manquant. L\'IA recommande d\'envoyer une relance automatique à Fatima Kaddour avec la liste exacte des documents requis. Résolution prédite sous 5 jours.',
    },
    3: {
        name:'Mohamed Saïd', initials:'MS', icon:'🎸',
        num:'CPA-2024-0612', type:'Instrumentiste', spec:'Oud',
        validite:'05/03/2026 → 05/03/2027', status:'Validée', statusClass:'teal',
        iaScore: 95, iaColor:'var(--green)',
        iaResults: [
            {cls:'ok',  text:'✓ Diplôme d\'instrumentiste — Validé par Institut Salah Mahdi'},
            {cls:'ok',  text:'✓ Affiliation CNSS — Active'},
            {cls:'ok',  text:'✓ CNI — Valide'},
            {cls:'warn',text:'! Renouvellement prévu dans 330 jours — Rappel auto planifié'},
        ],
        iaReco: '✅ Dossier excellent — Score 95%. L\'IA planifiera un rappel de renouvellement automatique 60 jours avant l\'expiration.',
    },
    4: {
        name:'Leila Saidi', initials:'LS', icon:'🎤',
        num:'CPA-2023-0188', type:'Musicienne', spec:'Chant Malouf',
        validite:'Carte suspendue', status:'Suspendue', statusClass:'red',
        iaScore: 21, iaColor:'var(--red)',
        iaResults: [
            {cls:'ok',   text:'✓ Diplôme de chant — Validé'},
            {cls:'fail',  text:'✕ Affiliation CNSS — EXPIRÉE depuis 4 mois'},
            {cls:'warn',  text:'! CNI — Potentiellement expirée (vérification requise)'},
            {cls:'fail',  text:'✕ Dossier suspendu — 2 raisons de suspension actives'},
        ],
        iaReco: '🔴 CRITIQUE — Score 21%. L\'IA a détecté 2 problèmes bloquants : CNSS expirée (4 mois) + CNI suspecte. Recommandation : contact direct avec l\'artiste + délai de régularisation de 15 jours maximum avant clôture définitive du dossier.',
    },
    5: {
        name:'Karim Mbarki', initials:'KM', icon:'🎷',
        num:'CPA-2024-0934', type:'Musicien', spec:'Saxophone Jazz',
        validite:'20/01/2026 → 20/01/2027', status:'Validée', statusClass:'green',
        iaScore: 91, iaColor:'var(--green)',
        iaResults: [
            {cls:'ok',  text:'✓ Diplôme artistique — Validé'},
            {cls:'ok',  text:'✓ Affiliation CNSS — Active'},
            {cls:'ok',  text:'✓ CNI — Valide'},
            {cls:'ok',  text:'✓ Dossier complet — Aucune anomalie'},
        ],
        iaReco: '✅ Dossier conforme à 91%. L\'IA note que ce dossier peut servir de modèle pour les renouvellements futurs. Renouvellement dans 280 jours.',
    },
    6: {
        name:'Rania Gharbi', initials:'RG', icon:'🩰',
        num:'CPA-2024-0743', type:'Danseuse', spec:'Danse Orientale',
        validite:'En vérification', status:'En attente', statusClass:'amber',
        iaScore: 67, iaColor:'var(--amber)',
        iaResults: [
            {cls:'warn', text:'! Diplôme de danse — En cours de vérification (authenticité)'},
            {cls:'ok',   text:'✓ Affiliation CNSS — Active'},
            {cls:'ok',   text:'✓ CNI — Valide'},
            {cls:'ok',   text:'✓ Casier judiciaire B3 — Vierge'},
        ],
        iaReco: '⚠️ Score 67% — En attente de vérification du diplôme. L\'IA recommande de contacter l\'Institut de danse émetteur du diplôme pour confirmation d\'authenticité. Délai estimé : 3-5 jours.',
    },
};

// ── Open detail modal ──
window.cpOpenDetail = function(id) {
    const d = cpArtists[id];
    if (!d) return;

    document.getElementById('cpd-name').textContent = d.name;
    document.getElementById('cpd-num').textContent  = d.num;
    document.getElementById('cpd-type').textContent = d.type;
    document.getElementById('cpd-spec').textContent = d.spec;
    document.getElementById('cpd-validite').textContent = d.validite;
    document.getElementById('cpd-photo').textContent = d.icon;
    document.getElementById('cpd-ia-score').textContent = d.iaScore + '%';
    document.getElementById('cpd-ia-score').style.color = d.iaColor;

    const statusBadge = document.getElementById('cpd-status-badge');
    statusBadge.textContent = d.status;
    statusBadge.className = 'badge ' + d.statusClass;

    document.getElementById('cpd-input-name').value = d.name;
    document.getElementById('cpd-input-num').value  = d.num;
    document.getElementById('cpd-input-type').value = d.type;
    document.getElementById('cpd-input-spec').value = d.spec;

    // IA results
    document.getElementById('cpd-ia-results').innerHTML = d.iaResults.map(r =>
        `<div class="cp-ia-result-row ${r.cls}" style="padding:6px 10px; border-radius:4px; font-size:11.5px;">${r.text}</div>`
    ).join('');
    document.getElementById('cpd-ia-reco').innerHTML = d.iaReco;

    openModal('modal-detail-carte');
};

// ── Filter tabs ──
window.cpFilter = function(el, group) {
    document.querySelectorAll('.cp-ftab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    document.querySelectorAll('#cp-grid .cp-card').forEach(card => {
        const show = group === 'all' ||
            (group === 'alerte' && card.dataset.alert === 'alerte') ||
            card.dataset.status === group;
        card.style.display = show ? '' : 'none';
    });
};

// ── Search ──
window.cpSearch = function(q) {
    document.querySelectorAll('#cp-grid .cp-card').forEach(card => {
        card.style.display = card.dataset.name.includes(q.toLowerCase()) ? '' : 'none';
    });
};

// ── Toggle view ──
let cpIsGrid = true;
window.cpToggleView = function() {
    cpIsGrid = !cpIsGrid;
    document.getElementById('cp-grid').style.display       = cpIsGrid ? '' : 'none';
    document.getElementById('cp-table-view').style.display = cpIsGrid ? 'none' : '';
    document.getElementById('cp-view-toggle').textContent  = cpIsGrid ? '⊞' : '☰';
    showToast(cpIsGrid ? 'Vue grille' : 'Vue tableau', 'info');
};

// ── Select artist type in form ──
window.cpSelectType = function(el, type) {
    document.querySelectorAll('.cp-type-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('nc-ia-warn').style.display = 'flex';
};

// ── CNSS verification ──
window.cpVerifyCNSS = function() {
    const num = document.getElementById('nc-cnss').value;
    const resultEl = document.getElementById('cnss-result');
    if (!num) { showToast('Saisissez un numéro CNSS', 'info'); return; }
    resultEl.style.display = 'none';
    showToast('Vérification CNSS en cours...', 'info');
    setTimeout(() => {
        resultEl.style.display = 'block';
        if (num.length >= 6) {
            resultEl.style.background = 'var(--green-dim)';
            resultEl.style.color = 'var(--green)';
            resultEl.style.border = '1px solid rgba(74,222,128,0.25)';
            resultEl.textContent = '✓ Affiliation CNSS active — Valide jusqu\'au 12/2026';
        } else {
            resultEl.style.background = 'var(--red-dim)';
            resultEl.style.color = 'var(--red)';
            resultEl.style.border = '1px solid rgba(248,113,113,0.25)';
            resultEl.textContent = '✕ Numéro CNSS non trouvé — Vérifiez et réessayez';
        }
    }, 1200);
};

// ── Preview card ──
window.cpPreview = function() {
    const nom    = document.getElementById('nc-nom').value;
    const prenom = document.getElementById('nc-prenom').value;
    if (!nom && !prenom) { showToast('Remplissez les informations pour prévisualiser', 'info'); return; }
    showToast('Aperçu de la carte généré — ' + prenom + ' ' + nom, 'success');
};

// ── Create card ──
window.cpCreateCard = function() {
    const nom    = document.getElementById('nc-nom').value;
    const prenom = document.getElementById('nc-prenom').value;
    if (!nom || !prenom) { showToast('Remplissez le nom et le prénom', 'info'); return; }
    showToast('✅ Carte créée — ' + prenom + ' ' + nom + ' — Dossier IA en cours d\'analyse', 'success');
    closeModal('modal-add-carte');
};
</script>

@endsection
