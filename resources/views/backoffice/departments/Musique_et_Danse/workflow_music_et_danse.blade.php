@extends('shared.layouts.backoffice')

@section('title', 'Moteur de Workflows — Direction Musique & Danse')
@section('breadcrumb', 'Workflows')

@section('content')

<style>
/* ════════════════════════════════════════════
   WORKFLOW ENGINE — DESIGN SYSTEM
════════════════════════════════════════════ */

/* ── KPIs ── */
.wfe-kpi-row {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 12px;
    margin-bottom: 22px;
}
@media (max-width: 1100px) { .wfe-kpi-row { grid-template-columns: repeat(3,1fr); } }

.wfe-kpi {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 14px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
}
.wfe-kpi-icon {
    width: 38px; height: 38px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 17px; flex-shrink: 0;
}
.wfe-kpi-val { font-size: 20px; font-weight: 900; font-family: var(--font-mono); line-height: 1; }
.wfe-kpi-lbl { font-size: 10.5px; color: var(--text3); text-transform: uppercase; letter-spacing: 0.6px; margin-top: 3px; }
.wfe-kpi-delta { font-size: 10px; font-family: var(--font-mono); font-weight: 700; margin-top: 3px; }

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

/* ── Deadline bar ── */
.wfe-deadline {
    padding: 0 16px 10px;
    display: flex; align-items: center; gap: 8px;
    font-size: 11px; color: var(--text3);
}
.wfe-deadline-icon { font-size: 12px; }
.wfe-deadline-val { font-family: var(--font-mono); font-weight: 700; }

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

/* Delay heatmap */
.wfe-heatmap {
    display: flex; flex-direction: column; gap: 6px;
    padding: 12px 14px;
}
.wfe-heat-row { display: flex; align-items: center; gap: 8px; }
.wfe-heat-label { font-size: 11px; color: var(--text2); min-width: 80px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.wfe-heat-bar { flex: 1; height: 6px; background: var(--bg4); border-radius: 3px; overflow: hidden; }
.wfe-heat-fill { height: 100%; border-radius: 3px; }
.wfe-heat-val { font-size: 10.5px; font-family: var(--font-mono); font-weight: 700; min-width: 38px; text-align: right; flex-shrink: 0; }

/* Quick actions */
.wfe-quick { display: flex; flex-direction: column; gap: 7px; padding: 12px 14px; }
.wfe-qa {
    display: flex; align-items: center; gap: 8px; padding: 8px 11px;
    background: var(--bg3); border: 1px solid var(--border);
    border-radius: var(--radius-sm); font-size: 12px; font-weight: 600;
    color: var(--text2); cursor: pointer; font-family: var(--font-body);
    transition: all 0.15s;
}
.wfe-qa:hover { background: var(--bg4); color: var(--text); border-color: var(--border2); }

/* ════ MODAL — DETAIL PROCESS ════ */
.wfe-modal-wide { max-width: 720px; }

.wfe-detail-tabs {
    display: flex; gap: 0;
    border-bottom: 1px solid var(--border);
    margin-bottom: 18px;
}
.wfe-dtab {
    padding: 8px 16px; font-size: 12.5px; font-weight: 600;
    color: var(--text3); cursor: pointer;
    border-bottom: 2px solid transparent; transition: all 0.15s;
}
.wfe-dtab:hover { color: var(--text2); }
.wfe-dtab.active { color: var(--gold); border-bottom-color: var(--gold); }

/* BPMN Viewer (visual) */
.wfe-bpmn-viewer {
    background: var(--bg3); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 20px;
    overflow-x: auto; min-height: 160px;
    position: relative;
}
.wfe-bpmn-flow {
    display: flex; align-items: center; gap: 0;
    min-width: max-content;
}
.wfe-bnode {
    display: flex; flex-direction: column; align-items: center; gap: 5px;
    flex-shrink: 0;
}
.wfe-bnode-box {
    padding: 8px 12px; border-radius: 6px;
    border: 1.5px solid var(--border2);
    background: var(--bg2);
    font-size: 10.5px; font-weight: 600; color: var(--text2);
    text-align: center; max-width: 90px;
    line-height: 1.3;
    cursor: pointer; transition: all 0.15s;
}
.wfe-bnode-box:hover { border-color: var(--gold); color: var(--text); }
.wfe-bnode-box.start { background: var(--green-dim); border-color: var(--green); color: var(--green); }
.wfe-bnode-box.end   { background: var(--red-dim);   border-color: var(--red);   color: var(--red); }
.wfe-bnode-box.done-node { background: var(--green-dim); border-color: var(--green); }
.wfe-bnode-box.active-node { background: var(--gold-dim); border-color: var(--gold); color: var(--gold); }
.wfe-bnode-label { font-size: 9px; color: var(--text3); text-align: center; max-width: 90px; line-height: 1.2; }
.wfe-bnode-time  { font-size: 9px; font-family: var(--font-mono); color: var(--text3); }
.wfe-barrow { font-size: 16px; color: var(--border2); margin: 0 4px; align-self: flex-start; padding-top: 12px; flex-shrink: 0; }
.wfe-barrow.done { color: var(--green); }

/* Step details table */
.wfe-steps-table {
    width: 100%; border-collapse: collapse; font-size: 12px;
}
.wfe-steps-table th {
    text-align: left; padding: 8px 10px;
    font-size: 10px; text-transform: uppercase; letter-spacing: 0.7px;
    color: var(--text3); font-weight: 700;
    border-bottom: 1px solid var(--border);
}
.wfe-steps-table td { padding: 9px 10px; border-bottom: 1px solid var(--border); color: var(--text2); }
.wfe-steps-table tr:last-child td { border-bottom: none; }
.wfe-steps-table tr:hover td { background: var(--bg3); }
.wfe-step-status-dot {
    width: 8px; height: 8px; border-radius: 50%;
    display: inline-block; margin-right: 5px;
}

/* Doc checklist */
.wfe-doc-list { display: flex; flex-direction: column; gap: 6px; }
.wfe-doc-item {
    display: flex; align-items: center; gap: 9px;
    padding: 8px 11px; background: var(--bg3);
    border-radius: var(--radius-sm); border: 1px solid var(--border);
    font-size: 12px; color: var(--text2);
}
.wfe-doc-check { width: 16px; height: 16px; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 9px; font-weight: 700; flex-shrink: 0; }

/* IA Panel inside modal */
.wfe-ia-modal-panel {
    background: linear-gradient(135deg, rgba(167,139,250,0.07), rgba(96,165,250,0.05));
    border: 1px solid rgba(167,139,250,0.2);
    border-radius: var(--radius); padding: 14px 16px;
    display: flex; flex-direction: column; gap: 10px;
}
.wfe-ia-modal-title {
    font-size: 11px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.8px; color: var(--purple);
    display: flex; align-items: center; gap: 6px;
}
.wfe-ia-reco {
    font-size: 12.5px; color: var(--text2); line-height: 1.55;
    padding: 10px 12px; background: var(--bg3); border-radius: var(--radius-sm);
    border-left: 3px solid var(--purple);
}
.wfe-ia-actions { display: flex; gap: 7px; flex-wrap: wrap; }
.wfe-ia-act-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 12px; border-radius: var(--radius-sm);
    font-size: 11.5px; font-weight: 600; cursor: pointer;
    font-family: var(--font-body); transition: all 0.15s;
}
.wfe-ia-act-btn.purple { background: var(--purple-dim); color: var(--purple); border: 1px solid rgba(167,139,250,0.25); }
.wfe-ia-act-btn.purple:hover { background: rgba(167,139,250,0.18); }
.wfe-ia-act-btn.teal   { background: var(--teal-dim);   color: var(--teal);   border: 1px solid rgba(45,212,191,0.2); }
.wfe-ia-act-btn.gold   { background: var(--gold-dim);   color: var(--gold);   border: 1px solid rgba(201,168,76,0.25); }

/* ── Animations ── */
@keyframes wfe-fadein { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
.wfe-fadein { animation: wfe-fadein 0.3s ease forwards; }
</style>

{{-- ════════ KPI ROW ════════ --}}
<div class="wfe-kpi-row">
    <div class="wfe-kpi">
        <div class="wfe-kpi-icon" style="background:var(--gold-dim);">⚙️</div>
        <div>
            <div class="wfe-kpi-val" style="color:var(--gold);">10</div>
            <div class="wfe-kpi-lbl">Processus DMD</div>
            <div class="wfe-kpi-delta" style="color:var(--teal);">→ 7 actifs · 3 brouillon</div>
        </div>
    </div>
    <div class="wfe-kpi">
        <div class="wfe-kpi-icon" style="background:var(--blue-dim);">📂</div>
        <div>
            <div class="wfe-kpi-val" style="color:var(--blue);">247</div>
            <div class="wfe-kpi-lbl">Instances en cours</div>
            <div class="wfe-kpi-delta" style="color:var(--green);">↑ +18 cette semaine</div>
        </div>
    </div>
    <div class="wfe-kpi">
        <div class="wfe-kpi-icon" style="background:var(--red-dim);">⏰</div>
        <div>
            <div class="wfe-kpi-val" style="color:var(--red);">23</div>
            <div class="wfe-kpi-lbl">En retard</div>
            <div class="wfe-kpi-delta" style="color:var(--red);">↓ Action requise</div>
        </div>
    </div>
    <div class="wfe-kpi">
        <div class="wfe-kpi-icon" style="background:var(--green-dim);">✅</div>
        <div>
            <div class="wfe-kpi-val" style="color:var(--green);">182</div>
            <div class="wfe-kpi-lbl">Complétés ce mois</div>
            <div class="wfe-kpi-delta" style="color:var(--green);">↑ +12%</div>
        </div>
    </div>
    <div class="wfe-kpi">
        <div class="wfe-kpi-icon" style="background:var(--purple-dim);">🤖</div>
        <div>
            <div class="wfe-kpi-val" style="color:var(--purple);">94%</div>
            <div class="wfe-kpi-lbl">Fiabilité IA</div>
            <div class="wfe-kpi-delta" style="color:var(--purple);">→ Prédictions correctes</div>
        </div>
    </div>
</div>

{{-- ════════ IA BANNER ════════ --}}
<div class="wfe-ia-banner">
    <div class="wfe-ia-orb">🤖</div>
    <div class="wfe-ia-insights">
        <div class="wfe-ia-title">
            IA Analytique — 4 alertes détectées maintenant
            <span style="font-size:10px; padding:2px 8px; background:var(--gold-dim); color:var(--gold); border-radius:20px; font-weight:700;">LIVE</span>
        </div>
        <div class="wfe-ia-items">
            <div class="wfe-ia-chip" style="background:var(--red-dim); color:var(--red); border:1px solid rgba(248,113,113,0.2);" onclick="openModal('modal-wf-detail'); wfeTab('steps')">
                🔴 Investisseurs Culturels bloqué — Étape 4 depuis 12j
            </div>
            <div class="wfe-ia-chip" style="background:var(--amber-dim); color:var(--amber); border:1px solid rgba(251,191,36,0.2);" onclick="showToast('3 dossiers Carte Pro arrivent à échéance dans 5 jours', 'info')">
                ⚠️ 3 Cartes Pro expirent dans 5j
            </div>
            <div class="wfe-ia-chip" style="background:var(--blue-dim); color:var(--blue); border:1px solid rgba(96,165,250,0.2);" onclick="showToast('Diplôme Musique Arabe: 14 candidats sans convocation', 'info')">
                📡 14 convocations non envoyées — Diplôme MA
            </div>
            <div class="wfe-ia-chip" style="background:var(--purple-dim); color:var(--purple); border:1px solid rgba(167,139,250,0.2);" onclick="showToast('IA optimisation: Circuit Attestation peut être réduit de J1 à 4h', 'success')">
                ✨ Optimisation: Attestation peut passer à 4h
            </div>
        </div>
    </div>
    <button class="btn btn-outline btn-sm" onclick="showToast('Rapport IA complet généré', 'info')">📊 Rapport IA</button>
</div>

{{-- ════════ MAIN SHELL ════════ --}}
<div class="wfe-shell">

    {{-- ══ LEFT: CARDS GRID ══ --}}
    <div>
        {{-- Filter bar --}}
        <div class="wfe-filterbar">
            <div class="wfe-filter-tabs">
                <div class="wfe-ftab active" onclick="wfeFilter(this,'all')">Tous (10)</div>
                <div class="wfe-ftab" onclick="wfeFilter(this,'actif')">✅ Actifs (7)</div>
                <div class="wfe-ftab" onclick="wfeFilter(this,'brouillon')">📝 Brouillon (2)</div>
                <div class="wfe-ftab" onclick="wfeFilter(this,'alerte')">🔴 Alertes (3)</div>
            </div>
            <input type="text" class="wfe-search" placeholder="🔍 Rechercher un processus..." oninput="wfeSearch(this.value)">
            <button class="btn btn-outline btn-sm" onclick="showToast('Import BPMN XML', 'info')">📥 Importer BPMN</button>
            <button class="btn btn-gold btn-sm" onclick="showToast('Nouveau processus — éditeur BPMN ouvert', 'info')">+ Nouveau</button>
        </div>

        {{-- Process cards --}}
        <div class="wfe-grid" id="wfe-grid">

            @php
            $processes = [
                [
                    'id' => 1,
                    'icon' => '📜',
                    'icon_bg' => 'var(--gold-dim)',
                    'num' => 'P-001',
                    'title' => 'Attestation d\'exercice artistique',
                    'key' => 'wf-attestation-artistique · v1.2',
                    'status' => 'actif',
                    'instances' => 41,
                    'delai' => '1 jour',
                    'delai_color' => 'var(--green)',
                    'etapes' => 5,
                    'progress' => 82,
                    'progress_color' => 'var(--green)',
                    'en_retard' => 2,
                    'steps' => ['Dépôt', 'Traitement', 'Signature', 'Enreg.', 'Délivrance'],
                    'active_step' => 3,
                    'ia_alert' => null,
                    'output' => 'Attestation de profession artistique',
                ],
                [
                    'id' => 2,
                    'icon' => '🏥',
                    'icon_bg' => 'var(--teal-dim)',
                    'num' => 'P-002',
                    'title' => 'Attestation CNSS — Musique & Arts populaires',
                    'key' => 'wf-attestation-cnss · v1.0',
                    'status' => 'actif',
                    'instances' => 28,
                    'delai' => '1 jour',
                    'delai_color' => 'var(--green)',
                    'etapes' => 5,
                    'progress' => 75,
                    'progress_color' => 'var(--teal)',
                    'en_retard' => 1,
                    'steps' => ['Dépôt', 'Traitement', 'Signature', 'Enreg.', 'Délivrance'],
                    'active_step' => 2,
                    'ia_alert' => ['color' => 'amber', 'msg' => '⚠️ 3 CNSS expirent bientôt'],
                    'output' => 'Attestation professionnelle CNSS',
                ],
                [
                    'id' => 3,
                    'icon' => '🎭',
                    'icon_bg' => 'var(--purple-dim)',
                    'num' => 'P-003',
                    'title' => 'Carte Professionnelle Artistique',
                    'key' => 'wf-carte-professionnelle · v2.1',
                    'status' => 'actif',
                    'instances' => 34,
                    'delai' => '30–90 jours',
                    'delai_color' => 'var(--amber)',
                    'etapes' => 11,
                    'progress' => 55,
                    'progress_color' => 'var(--purple)',
                    'en_retard' => 8,
                    'steps' => ['Appel', 'Dossier', 'Examen', 'Délib.', 'PV', 'Valid.', 'Résultats', 'Intégr.', 'Valid.Dir', 'Numér.', 'Émission'],
                    'active_step' => 6,
                    'ia_alert' => ['color' => 'red', 'msg' => '🔴 8 dossiers bloqués étape 6'],
                    'output' => 'Carte Professionnelle Artistique',
                ],
                [
                    'id' => 4,
                    'icon' => '🔄',
                    'icon_bg' => 'var(--green-dim)',
                    'num' => 'P-004',
                    'title' => 'Renouvellement Carte Professionnelle',
                    'key' => 'wf-renouvellement-carte · v1.1',
                    'status' => 'actif',
                    'instances' => 19,
                    'delai' => '3 jours',
                    'delai_color' => 'var(--green)',
                    'etapes' => 5,
                    'progress' => 91,
                    'progress_color' => 'var(--green)',
                    'en_retard' => 0,
                    'steps' => ['Demande', 'Insertion', 'Validation', 'Tirage', 'Délivrance'],
                    'active_step' => 4,
                    'ia_alert' => null,
                    'output' => 'Nouvelle Carte Professionnelle',
                ],
                [
                    'id' => 5,
                    'icon' => '📋',
                    'icon_bg' => 'var(--blue-dim)',
                    'num' => 'P-005',
                    'title' => 'Duplicata Carte Professionnelle',
                    'key' => 'wf-duplicata-carte · v1.0',
                    'status' => 'actif',
                    'instances' => 7,
                    'delai' => '1–3 jours',
                    'delai_color' => 'var(--green)',
                    'etapes' => 3,
                    'progress' => 88,
                    'progress_color' => 'var(--blue)',
                    'en_retard' => 0,
                    'steps' => ['Demande', 'Insertion', 'Délivrance'],
                    'active_step' => 2,
                    'ia_alert' => null,
                    'output' => 'Duplicata de la carte',
                ],
                [
                    'id' => 6,
                    'icon' => '🎵',
                    'icon_bg' => 'var(--amber-dim)',
                    'num' => 'P-006',
                    'title' => 'Certificat Exploitation Patrimoine Musical',
                    'key' => 'wf-certificat-musical · v1.0',
                    'status' => 'actif',
                    'instances' => 12,
                    'delai' => '1–3 jours',
                    'delai_color' => 'var(--green)',
                    'etapes' => 7,
                    'progress' => 68,
                    'progress_color' => 'var(--amber)',
                    'en_retard' => 3,
                    'steps' => ['Demande', 'Insertion', 'Cert. init.', 'Paiement', 'Facture', 'Cert. fin.', 'Délivrance'],
                    'active_step' => 4,
                    'ia_alert' => ['color' => 'amber', 'msg' => '⚠️ 3 paiements OTDAV en attente'],
                    'output' => 'Certificat d\'exploitation musical',
                ],
                [
                    'id' => 7,
                    'icon' => '🏛️',
                    'icon_bg' => 'var(--red-dim)',
                    'num' => 'P-007',
                    'title' => 'Diplôme de Musique Arabe',
                    'key' => 'wf-diplome-musique-arabe · v2.0',
                    'status' => 'actif',
                    'instances' => 89,
                    'delai' => '30j + 2–4 mois',
                    'delai_color' => 'var(--red)',
                    'etapes' => 10,
                    'progress' => 42,
                    'progress_color' => 'var(--red)',
                    'en_retard' => 14,
                    'steps' => ['Appel', 'Dossiers', 'Tri', 'Insertion', 'Convoc.', 'Examens', 'Résultats', 'Défin.', 'Diplômes'],
                    'active_step' => 5,
                    'ia_alert' => ['color' => 'red', 'msg' => '🔴 14 convocations non envoyées'],
                    'output' => 'Diplôme + Convocation examens',
                ],
                [
                    'id' => 8,
                    'icon' => '🎼',
                    'icon_bg' => 'var(--blue-dim)',
                    'num' => 'P-008',
                    'title' => 'Diplôme d\'Instrumentiste de Musique',
                    'key' => 'wf-diplome-instrumentiste · v1.0',
                    'status' => 'brouillon',
                    'instances' => 0,
                    'delai' => '30j + 2–4 mois',
                    'delai_color' => 'var(--text3)',
                    'etapes' => 10,
                    'progress' => 0,
                    'progress_color' => 'var(--blue)',
                    'en_retard' => 0,
                    'steps' => ['Appel', 'Dossiers', 'Tri', 'Insertion', 'Convoc.', 'Écrits', 'Pub. écrits', 'Oraux', 'Résultats', 'Diplômes'],
                    'active_step' => 0,
                    'ia_alert' => null,
                    'output' => 'Diplôme + Résultats',
                ],
                [
                    'id' => 9,
                    'icon' => '🏆',
                    'icon_bg' => 'var(--green-dim)',
                    'num' => 'P-009',
                    'title' => 'Certificat de Réussite à un Examen',
                    'key' => 'wf-certificat-reussite · v1.0',
                    'status' => 'actif',
                    'instances' => 31,
                    'delai' => '1–3 jours',
                    'delai_color' => 'var(--green)',
                    'etapes' => 3,
                    'progress' => 96,
                    'progress_color' => 'var(--green)',
                    'en_retard' => 0,
                    'steps' => ['Demande', 'Insertion', 'Délivrance'],
                    'active_step' => 3,
                    'ia_alert' => null,
                    'output' => 'Certificat de réussite signé',
                ],
                [
                    'id' => 10,
                    'icon' => '🎪',
                    'icon_bg' => 'var(--purple-dim)',
                    'num' => 'P-010',
                    'title' => 'Exercice Profession d\'Imprésario',
                    'key' => 'wf-impresario-exercice · v1.0',
                    'status' => 'brouillon',
                    'instances' => 9,
                    'delai' => '1–3 jours',
                    'delai_color' => 'var(--red)',
                    'etapes' => 6,
                    'progress' => 30,
                    'progress_color' => 'var(--red)',
                    'en_retard' => 5,
                    'steps' => ['Cahier charges', 'Tamponnement', 'Dossier', 'Notification', 'Inspection', 'Certificat'],
                    'active_step' => 4,
                    'ia_alert' => ['color' => 'red', 'msg' => '🔴 Inspection en attente depuis 12j'],
                    'output' => 'Certificat d\'exercice + Cahier charges',
                ],
            ];

            $statusColors = ['actif' => ['bg'=>'var(--green-dim)', 'color'=>'var(--green)', 'label'=>'Actif'], 'brouillon' => ['bg'=>'var(--amber-dim)', 'color'=>'var(--amber)', 'label'=>'Brouillon']];
            @endphp

            @foreach($processes as $p)
            <div class="wfe-card wfe-fadein"
                 data-status="{{ $p['status'] }}"
                 data-title="{{ strtolower($p['title']) }}"
                 data-alert="{{ $p['ia_alert'] ? 'alerte' : '' }}"
                 style="animation-delay: {{ ($loop->index * 0.04) }}s;"
                 onclick="wfeOpenDetail({{ $p['id'] }})">

                {{-- Top --}}
                <div class="wfe-card-top">
                    <div class="wfe-card-icon" style="background: {{ $p['icon_bg'] }};">{{ $p['icon'] }}</div>
                    <div class="wfe-card-meta">
                        <div class="wfe-card-num">{{ $p['num'] }}</div>
                        <div class="wfe-card-title">{{ $p['title'] }}</div>
                        <div class="wfe-card-key">{{ $p['key'] }}</div>
                    </div>
                    <div>
                        <span class="badge" style="background:{{ $statusColors[$p['status']]['bg'] }}; color:{{ $statusColors[$p['status']]['color'] }}; font-size:10px;">{{ $statusColors[$p['status']]['label'] }}</span>
                        @if($p['ia_alert'])
                            <div style="margin-top: 4px;">
                                <span class="wfe-ia-alert-tag" style="background: var(--{{ $p['ia_alert']['color'] }}-dim); color: var(--{{ $p['ia_alert']['color'] }});">{{ $p['ia_alert']['msg'] }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Steps flow --}}
                <div class="wfe-steps-row">
                    @foreach($p['steps'] as $si => $s)
                        @php $stepIdx = $si + 1; @endphp
                        <div class="wfe-step">
                            <div class="wfe-step-dot
                                {{ $stepIdx < $p['active_step'] ? 'done' : '' }}
                                {{ $stepIdx == $p['active_step'] ? 'active' : '' }}"
                                title="Étape {{ $stepIdx }}: {{ $s }}"
                                onclick="event.stopPropagation(); showToast('Étape {{ $stepIdx }}: {{ $s }}', 'info')">
                                @if($stepIdx < $p['active_step']) ✓
                                @else {{ $stepIdx }}
                                @endif
                            </div>
                        </div>
                        @if(!$loop->last)
                        <div class="wfe-step-line {{ $stepIdx < $p['active_step'] ? 'done' : ($stepIdx == $p['active_step'] ? 'active' : '') }}"></div>
                        @endif
                    @endforeach
                </div>

                {{-- Stats --}}
                <div class="wfe-card-stats">
                    <div class="wfe-cstat">
                        <div class="wfe-cstat-val" style="color:var(--blue);">{{ $p['instances'] }}</div>
                        <div class="wfe-cstat-lbl">Instances</div>
                    </div>
                    <div class="wfe-cstat">
                        <div class="wfe-cstat-val" style="color:{{ $p['delai_color'] }};">{{ $p['delai'] }}</div>
                        <div class="wfe-cstat-lbl">Délai</div>
                    </div>
                    <div class="wfe-cstat">
                        <div class="wfe-cstat-val" style="color:var(--gold);">{{ $p['etapes'] }}</div>
                        <div class="wfe-cstat-lbl">Étapes</div>
                    </div>
                    <div class="wfe-cstat">
                        <div class="wfe-cstat-val" style="color:{{ $p['en_retard'] > 0 ? 'var(--red)' : 'var(--green)' }};">{{ $p['en_retard'] }}</div>
                        <div class="wfe-cstat-lbl">En retard</div>
                    </div>
                </div>

                {{-- Progress --}}
                <div class="wfe-progress-wrap">
                    <div class="wfe-progress-row">
                        <span style="font-size:10px; color:var(--text3);">Avancement</span>
                        <div class="wfe-progress-track">
                            <div class="wfe-progress-fill" style="width:{{ $p['progress'] }}%; background:{{ $p['progress_color'] }};"></div>
                        </div>
                        <span class="wfe-progress-pct" style="color:{{ $p['progress_color'] }};">{{ $p['progress'] }}%</span>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="wfe-card-foot" onclick="event.stopPropagation()">
                    <button class="wfe-foot-btn" onclick="wfeOpenDetail({{ $p['id'] }})">👁 Voir</button>
                    <button class="wfe-foot-btn" onclick="showToast('Instances du processus P-00{{ $p['id'] }}', 'info')">📂 Instances</button>
                    <button class="wfe-foot-btn gold" onclick="wfeOpenDetail({{ $p['id'] }}); setTimeout(()=>wfeTab('ia'), 300)">🤖 Analyse IA</button>
                    <span class="wfe-foot-ia">✨ IA</span>
                </div>
            </div>
            @endforeach

        </div>{{-- end wfe-grid --}}
    </div>{{-- end left --}}

    {{-- ══ RIGHT SIDEBAR ══ --}}
    <div class="wfe-sidebar">

        {{-- Pending queue --}}
        <div class="wfe-sb-panel">
            <div class="wfe-sb-head">
                ⏳ File d'attente urgente
                <span style="font-size:10px; padding:2px 8px; background:var(--red-dim); color:var(--red); border-radius:10px; font-weight:700;">23</span>
            </div>
            <div class="wfe-queue-item">
                <div class="wfe-queue-dot" style="background:var(--red);"></div>
                <div class="wfe-queue-body">
                    <div class="wfe-queue-name">Karim M. — Carte Pro</div>
                    <div class="wfe-queue-proc">P-003 · Étape 6 bloquée</div>
                </div>
                <span class="wfe-queue-time">12j</span>
            </div>
            <div class="wfe-queue-item">
                <div class="wfe-queue-dot" style="background:var(--red);"></div>
                <div class="wfe-queue-body">
                    <div class="wfe-queue-name">Imprésario BenAli — Inspection</div>
                    <div class="wfe-queue-proc">P-010 · Inspection attendue</div>
                </div>
                <span class="wfe-queue-time">12j</span>
            </div>
            <div class="wfe-queue-item">
                <div class="wfe-queue-dot" style="background:var(--amber);"></div>
                <div class="wfe-queue-body">
                    <div class="wfe-queue-name">14 candidats — Diplôme MA</div>
                    <div class="wfe-queue-proc">P-007 · Convocations manquantes</div>
                </div>
                <span class="wfe-queue-time">7j</span>
            </div>
            <div class="wfe-queue-item">
                <div class="wfe-queue-dot" style="background:var(--amber);"></div>
                <div class="wfe-queue-body">
                    <div class="wfe-queue-name">3 paiements OTDAV en attente</div>
                    <div class="wfe-queue-proc">P-006 · Certificat musical</div>
                </div>
                <span class="wfe-queue-time">5j</span>
            </div>
            <div class="wfe-queue-item">
                <div class="wfe-queue-dot" style="background:var(--blue);"></div>
                <div class="wfe-queue-body">
                    <div class="wfe-queue-name">Sofia A. — CNSS expirée</div>
                    <div class="wfe-queue-proc">P-002 · Renouvellement requis</div>
                </div>
                <span class="wfe-queue-time">3j</span>
            </div>
        </div>

        {{-- IA Suggestions --}}
        <div class="wfe-sb-panel">
            <div class="wfe-sb-head">🤖 Suggestions IA</div>
            <div class="wfe-ia-sugg">
                <div class="wfe-sugg-item" onclick="showToast('Relance automatique envoyée aux 14 candidats', 'success')">
                    <div class="wfe-sugg-icon">📧</div>
                    <div>
                        <div class="wfe-sugg-text">Envoyer les 14 convocations manquantes — Diplôme Musique Arabe</div>
                        <div class="wfe-sugg-action">→ Générer & envoyer maintenant</div>
                    </div>
                </div>
                <div class="wfe-sugg-item" onclick="showToast('Relance inspection imprésario envoyée', 'success')">
                    <div class="wfe-sugg-icon">🏢</div>
                    <div>
                        <div class="wfe-sugg-text">Planifier l'inspection BenAli — bloquée depuis 12 jours</div>
                        <div class="wfe-sugg-action">→ Proposer 3 créneaux</div>
                    </div>
                </div>
                <div class="wfe-sugg-item" onclick="showToast('Rapport d\'optimisation généré', 'info')">
                    <div class="wfe-sugg-icon">⚡</div>
                    <div>
                        <div class="wfe-sugg-text">Attestations: signature directeur peut être déléguée — gain de 6h/dossier</div>
                        <div class="wfe-sugg-action">→ Proposer délégation de signature</div>
                    </div>
                </div>
                <div class="wfe-sugg-item" onclick="showToast('Alerte CNSS envoyée aux 3 artistes', 'success')">
                    <div class="wfe-sugg-icon">⚠️</div>
                    <div>
                        <div class="wfe-sugg-text">3 Cartes Pro expirent dans 5 jours — contacter les artistes</div>
                        <div class="wfe-sugg-action">→ Email automatique</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Delay heatmap --}}
        <div class="wfe-sb-panel">
            <div class="wfe-sb-head">📊 Délais moyens actuels</div>
            <div class="wfe-heatmap">
                <div class="wfe-heat-row">
                    <span class="wfe-heat-label">Attestation</span>
                    <div class="wfe-heat-bar"><div class="wfe-heat-fill" style="width:8%; background:var(--green);"></div></div>
                    <span class="wfe-heat-val" style="color:var(--green);">1j</span>
                </div>
                <div class="wfe-heat-row">
                    <span class="wfe-heat-label">CNSS</span>
                    <div class="wfe-heat-bar"><div class="wfe-heat-fill" style="width:8%; background:var(--green);"></div></div>
                    <span class="wfe-heat-val" style="color:var(--green);">1j</span>
                </div>
                <div class="wfe-heat-row">
                    <span class="wfe-heat-label">Carte Pro</span>
                    <div class="wfe-heat-bar"><div class="wfe-heat-fill" style="width:75%; background:var(--amber);"></div></div>
                    <span class="wfe-heat-val" style="color:var(--amber);">60j</span>
                </div>
                <div class="wfe-heat-row">
                    <span class="wfe-heat-label">Renouvellement</span>
                    <div class="wfe-heat-bar"><div class="wfe-heat-fill" style="width:15%; background:var(--green);"></div></div>
                    <span class="wfe-heat-val" style="color:var(--green);">3j</span>
                </div>
                <div class="wfe-heat-row">
                    <span class="wfe-heat-label">Cert. Musical</span>
                    <div class="wfe-heat-bar"><div class="wfe-heat-fill" style="width:18%; background:var(--green);"></div></div>
                    <span class="wfe-heat-val" style="color:var(--teal);">3j</span>
                </div>
                <div class="wfe-heat-row">
                    <span class="wfe-heat-label">Diplôme MA</span>
                    <div class="wfe-heat-bar"><div class="wfe-heat-fill" style="width:100%; background:var(--red);"></div></div>
                    <span class="wfe-heat-val" style="color:var(--red);">4+ mois</span>
                </div>
                <div class="wfe-heat-row">
                    <span class="wfe-heat-label">Imprésario</span>
                    <div class="wfe-heat-bar"><div class="wfe-heat-fill" style="width:90%; background:var(--red);"></div></div>
                    <span class="wfe-heat-val" style="color:var(--red);">3+ mois</span>
                </div>
            </div>
        </div>

        {{-- Quick actions --}}
        <div class="wfe-sb-panel">
            <div class="wfe-sb-head">⚡ Actions rapides</div>
            <div class="wfe-quick">
                <button class="wfe-qa" onclick="showToast('Rapport global exporté', 'info')">📥 Exporter rapport global</button>
                <button class="wfe-qa" onclick="showToast('Analyse IA en cours sur tous les processus...', 'info')">🤖 Analyse IA complète</button>
                <button class="wfe-qa" onclick="showToast('14 convocations générées et envoyées', 'success')">📧 Envoyer toutes les convocations</button>
                <button class="wfe-qa" onclick="showToast('Rapport de retards généré', 'info')">⏰ Rapport des retards</button>
                <button class="wfe-qa" onclick="showToast('Synchronisation Camunda BPMN 2.0', 'info')">🔄 Sync. Camunda BPM</button>
                <button class="wfe-qa" onclick="showToast('Archivage des instances clôturées', 'info')">📦 Archiver les clôturés</button>
            </div>
        </div>

    </div>{{-- end sidebar --}}
</div>{{-- end shell --}}


{{-- ════════════════════════════════════════════════
     MODAL — DÉTAIL PROCESSUS
════════════════════════════════════════════════ --}}
<div class="modal" id="modal-wf-detail">
    <div class="modal-content wfe-modal-wide">
        <div class="modal-header">
            <div class="modal-title" style="display:flex; align-items:center; gap:10px;">
                <span id="md-icon">📜</span>
                <span id="md-title">Attestation d'exercice artistique</span>
                <span class="badge green" id="md-status" style="font-size:10px;">Actif</span>
            </div>
            <button class="modal-close" onclick="closeModal('modal-wf-detail')">✕</button>
        </div>
        <div class="modal-body">

            {{-- Detail tabs --}}
            <div class="wfe-detail-tabs">
                <div class="wfe-dtab active" onclick="wfeTab('overview')" id="tab-overview">Vue d'ensemble</div>
                <div class="wfe-dtab" onclick="wfeTab('steps')" id="tab-steps">Étapes & Circuit</div>
                <div class="wfe-dtab" onclick="wfeTab('docs')" id="tab-docs">Documents requis</div>
                <div class="wfe-dtab" onclick="wfeTab('instances')" id="tab-instances">Instances</div>
                <div class="wfe-dtab" onclick="wfeTab('ia')" id="tab-ia">🤖 Analyse IA</div>
            </div>

            {{-- TAB: Overview --}}
            <div id="tabcontent-overview">
                <div style="display:grid; grid-template-columns: repeat(4,1fr); gap:10px; margin-bottom:16px;">
                    <div style="padding:12px; background:var(--bg3); border-radius:var(--radius-sm); border:1px solid var(--border); text-align:center;">
                        <div style="font-size:20px; font-weight:900; font-family:var(--font-mono); color:var(--blue);" id="md-instances">41</div>
                        <div style="font-size:10px; color:var(--text3); text-transform:uppercase; letter-spacing:0.5px;">Instances</div>
                    </div>
                    <div style="padding:12px; background:var(--bg3); border-radius:var(--radius-sm); border:1px solid var(--border); text-align:center;">
                        <div style="font-size:20px; font-weight:900; font-family:var(--font-mono); color:var(--green);" id="md-delai">1 jour</div>
                        <div style="font-size:10px; color:var(--text3); text-transform:uppercase; letter-spacing:0.5px;">Délai indicatif</div>
                    </div>
                    <div style="padding:12px; background:var(--bg3); border-radius:var(--radius-sm); border:1px solid var(--border); text-align:center;">
                        <div style="font-size:20px; font-weight:900; font-family:var(--font-mono); color:var(--gold);" id="md-etapes">5</div>
                        <div style="font-size:10px; color:var(--text3); text-transform:uppercase; letter-spacing:0.5px;">Étapes</div>
                    </div>
                    <div style="padding:12px; background:var(--bg3); border-radius:var(--radius-sm); border:1px solid var(--border); text-align:center;">
                        <div style="font-size:20px; font-weight:900; font-family:var(--font-mono); color:var(--green);" id="md-progress">82%</div>
                        <div style="font-size:10px; color:var(--text3); text-transform:uppercase; letter-spacing:0.5px;">Avancement</div>
                    </div>
                </div>
                <div style="margin-bottom:14px;">
                    <div style="font-size:11px; color:var(--text3); font-weight:700; text-transform:uppercase; letter-spacing:0.7px; margin-bottom:6px;">Output du processus</div>
                    <div style="padding:10px 14px; background:var(--green-dim); border:1px solid rgba(74,222,128,0.2); border-radius:var(--radius-sm); font-size:12.5px; color:var(--green); font-weight:600;" id="md-output">Attestation de profession artistique</div>
                </div>

                {{-- BPMN visual --}}
                <div style="font-size:11px; color:var(--text3); font-weight:700; text-transform:uppercase; letter-spacing:0.7px; margin-bottom:8px;">Circuit BPMN</div>
                <div class="wfe-bpmn-viewer">
                    <div class="wfe-bpmn-flow" id="md-bpmn-flow">
                        {{-- generated by JS --}}
                    </div>
                </div>
            </div>

            {{-- TAB: Steps --}}
            <div id="tabcontent-steps" style="display:none;">
                <table class="wfe-steps-table" id="md-steps-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>Intervenant</th>
                            <th>Délai</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody id="md-steps-body">
                    </tbody>
                </table>
            </div>

            {{-- TAB: Docs --}}
            <div id="tabcontent-docs" style="display:none;">
                <div style="font-size:12px; color:var(--text2); margin-bottom:12px;">Pièces justificatives requises pour ce processus :</div>
                <div class="wfe-doc-list" id="md-doc-list"></div>
            </div>

            {{-- TAB: Instances --}}
            <div id="tabcontent-instances" style="display:none;">
                <div style="display:flex; gap:8px; margin-bottom:14px; flex-wrap:wrap;">
                    <button class="btn btn-outline btn-sm" onclick="showToast('Nouvelle instance créée', 'success')">+ Nouvelle instance</button>
                    <button class="btn btn-outline btn-sm" onclick="showToast('Instances exportées', 'info')">📥 Exporter</button>
                </div>
                <table class="wfe-steps-table">
                    <thead><tr><th>Référence</th><th>Demandeur</th><th>Étape actuelle</th><th>Depuis</th><th>Statut</th><th>Action</th></tr></thead>
                    <tbody>
                        <tr><td class="badge-font" style="font-family:var(--font-mono);">REQ-001-2024</td><td>Marie Laurent</td><td>Étape 3 — Signature</td><td>J+0</td><td><span class="badge green">En cours</span></td><td><button class="btn btn-ghost btn-sm" onclick="showToast('Dossier ouvert','info')">Voir</button></td></tr>
                        <tr><td style="font-family:var(--font-mono);">REQ-002-2024</td><td>Karim Mansour</td><td>Étape 5 — Délivrance</td><td>J+1</td><td><span class="badge green">En cours</span></td><td><button class="btn btn-ghost btn-sm" onclick="showToast('Dossier ouvert','info')">Voir</button></td></tr>
                        <tr><td style="font-family:var(--font-mono);">REQ-003-2024</td><td>Sofia Amrani</td><td>Étape 2 — Traitement</td><td>J+0</td><td><span class="badge amber">En attente</span></td><td><button class="btn btn-ghost btn-sm" onclick="showToast('Dossier ouvert','info')">Voir</button></td></tr>
                        <tr><td style="font-family:var(--font-mono);">REQ-004-2024</td><td>Luc Moreau</td><td>Étape 5 — Délivrance</td><td>J+3</td><td><span class="badge red">En retard</span></td><td><button class="btn btn-ghost btn-sm" onclick="showToast('Relance envoyée','success')">Relancer</button></td></tr>
                    </tbody>
                </table>
            </div>

            {{-- TAB: IA Analysis --}}
            <div id="tabcontent-ia" style="display:none;">
                <div class="wfe-ia-modal-panel">
                    <div class="wfe-ia-modal-title">🤖 Analyse IA — Recommandations</div>
                    <div class="wfe-ia-reco" id="md-ia-reco">
                        Ce processus présente un taux d'avancement de <strong>82%</strong> avec <strong>2 dossiers en retard</strong>.
                        L'IA détecte que l'étape de signature directeur est le goulot d'étranglement principal.
                        <br><br>
                        <strong>Recommandation :</strong> Déléguer la signature des attestations courantes au chargé de dossier — gain estimé de <strong>6h par dossier</strong>. Le Directeur n'interviendrait que pour les cas complexes.
                    </div>
                    <div class="wfe-ia-actions">
                        <button class="wfe-ia-act-btn purple" onclick="showToast('Analyse prédictive générée', 'info')">📊 Analyse prédictive</button>
                        <button class="wfe-ia-act-btn teal" onclick="showToast('Optimisation de circuit proposée', 'success')">⚡ Optimiser le circuit</button>
                        <button class="wfe-ia-act-btn gold" onclick="showToast('Rapport IA exporté', 'info')">📄 Exporter rapport</button>
                    </div>
                </div>

                {{-- KPI IA --}}
                <div style="display:grid; grid-template-columns: repeat(3,1fr); gap:10px; margin-top:14px;">
                    <div style="padding:12px; background:var(--bg3); border-radius:var(--radius-sm); border:1px solid var(--border); text-align:center;">
                        <div style="font-size:18px; font-weight:900; font-family:var(--font-mono); color:var(--purple);">94%</div>
                        <div style="font-size:10px; color:var(--text3); margin-top:3px;">Fiabilité prédictions</div>
                    </div>
                    <div style="padding:12px; background:var(--bg3); border-radius:var(--radius-sm); border:1px solid var(--border); text-align:center;">
                        <div style="font-size:18px; font-weight:900; font-family:var(--font-mono); color:var(--green);">-6h</div>
                        <div style="font-size:10px; color:var(--text3); margin-top:3px;">Gain potentiel/dossier</div>
                    </div>
                    <div style="padding:12px; background:var(--bg3); border-radius:var(--radius-sm); border:1px solid var(--border); text-align:center;">
                        <div style="font-size:18px; font-weight:900; font-family:var(--font-mono); color:var(--teal);">J+0</div>
                        <div style="font-size:10px; color:var(--text3); margin-top:3px;">Délai optimal IA</div>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('modal-wf-detail')">Fermer</button>
            <button class="btn btn-outline" onclick="showToast('Éditeur BPMN ouvert', 'info')">⚙️ Éditer le processus</button>
            <button class="btn btn-gold" onclick="showToast('Nouvelle instance créée!', 'success')">+ Lancer une instance</button>
        </div>
    </div>
</div>

{{-- ════════ JAVASCRIPT ════════ --}}
<script>
// ── Process data ──
const wfeData = {
    1: {
        icon:'📜', title:"Attestation d'exercice artistique", status:'Actif',
        instances:41, delai:'1 jour', etapes:5, progress:'82%', output:'Attestation de profession artistique',
        steps:[
            {n:'Action 1',label:'Dépôt de la demande',desc:'Demande simple déposée au Bureau d\'ordre',actor:'Bureau d\'ordre DMD',delai:'J0',status:'done'},
            {n:'Action 2',label:'Traitement',desc:'Vérification de la validité de la carte professionnelle',actor:'Chargé de dossier',delai:'J0–J1',status:'done'},
            {n:'Action 3',label:'Signature',desc:'Signature de l\'attestation par le Directeur',actor:'Directeur',delai:'J1',status:'active'},
            {n:'Action 4',label:'Enregistrement',desc:'Enregistrement officiel de l\'attestation',actor:'Bureau d\'ordre DMD',delai:'J1',status:'pending'},
            {n:'Action 5',label:'Délivrance',desc:'Remise de l\'attestation au bénéficiaire',actor:'Chargé de dossier',delai:'J1',status:'pending'},
        ],
        docs:['Demande écrite','Copie carte professionnelle','Copie CNI','Copie passeport (si français)','Numéro de téléphone'],
        iaReco:'Ce processus présente un avancement de 82% avec 2 dossiers en léger retard. L\'IA détecte que l\'étape de signature directeur est le goulot principal. <strong>Recommandation :</strong> déléguer la signature des attestations courantes au chargé de dossier — gain estimé de 6h/dossier.',
    },
    2: {
        icon:'🏥', title:"Attestation CNSS — Musique & Arts populaires", status:'Actif',
        instances:28, delai:'1 jour', etapes:5, progress:'75%', output:'Attestation professionnelle CNSS',
        steps:[
            {n:'Action 1',label:'Dépôt',desc:'Demande écrite au Bureau d\'ordre',actor:'Bureau d\'ordre DMD',delai:'J0',status:'done'},
            {n:'Action 2',label:'Traitement',desc:'Vérification carte professionnelle',actor:'Chargé de dossier',delai:'J1',status:'active'},
            {n:'Action 3',label:'Signature',desc:'Signature du Directeur',actor:'Directeur',delai:'J1',status:'pending'},
            {n:'Action 4',label:'Enregistrement',desc:'Enregistrement au bureau d\'ordre',actor:'Bureau d\'ordre DMD',delai:'J1',status:'pending'},
            {n:'Action 5',label:'Délivrance',desc:'Remise de l\'attestation au bénéficiaire',actor:'Chargé de dossier',delai:'J1',status:'pending'},
        ],
        docs:['Demande écrite','Carte professionnelle','CNI','Passeport (si français)','Numéro de téléphone'],
        iaReco:'⚠️ 3 affiliations CNSS arrivent à expiration dans les 5 prochains jours. <strong>Recommandation :</strong> envoyer automatiquement les alertes de renouvellement via le module Mail Maestro. Probabilité de renouvellement spontané sans relance : 34% seulement.',
    },
    3: {
        icon:'🎭', title:"Carte Professionnelle Artistique", status:'Actif',
        instances:34, delai:'30–90 jours', etapes:11, progress:'55%', output:'Carte Professionnelle Artistique',
        steps:[
            {n:'Action 1',label:'Publication appel',desc:'Avis de candidature avec pièces requises',actor:'Direction musique & danse',delai:'30 jours',status:'done'},
            {n:'Action 2',label:'Dépôt dossier',desc:'Dépôt par les candidats selon la spécialité',actor:'Candidats',delai:'-',status:'done'},
            {n:'Action 3',label:'Examens',desc:'Examen pratique pour candidats en échec sur dossier',actor:'Comités artistiques',delai:'1 journée',status:'done'},
            {n:'Action 4',label:'Délibérations',desc:'Vérification et délibération sur les examens',actor:'Comités artistiques',delai:'30–60 jours',status:'done'},
            {n:'Action 5',label:'PV + Rapport',desc:'Rapport de statistiques (admis, refus, absences)',actor:'Chargé de dossier + Directeur',delai:'-',status:'done'},
            {n:'Action 6',label:'Validation responsable',desc:'Acceptation ou rejet des travaux de la commission',actor:'Responsable',delai:'-',status:'active'},
            {n:'Action 7',label:'Publication résultats',desc:'Publication des résultats finaux au public',actor:'Chargé de dossier',delai:'3–4 semaines',status:'pending'},
            {n:'Action 8',label:'Intégration admis',desc:'Scan photo + intégration dans la plateforme',actor:'Chargé de dossier',delai:'3 sem.–1 mois',status:'pending'},
            {n:'Action 9',label:'Validation directeur',desc:'Validation numérique par le Directeur',actor:'Directeur',delai:'-',status:'pending'},
            {n:'Action 10',label:'Numérotation',desc:'Attribution de numéro aux cartes professionnelles',actor:'DOMI',delai:'-',status:'pending'},
            {n:'Action 11',label:'Émission cartes',desc:'Vérification CNI + signature de réception',actor:'Chargé de dossier',delai:'-',status:'pending'},
        ],
        docs:['Demande officielle','Extrait casier judiciaire B3','CNI (copie)','2 photos d\'identité','2 enveloppes pré-adressées','Support numérique avec extraits (vidéos/audios)','CV artistique complet'],
        iaReco:'🔴 8 dossiers sont bloqués à l\'étape 6 (Validation responsable) depuis plus de 3 semaines. <strong>Recommandation :</strong> planifier une session de validation groupée urgente. L\'IA a identifié que 6 dossiers sur 8 sont complets et prêts — seul un rendez-vous de 2h suffit. Gain de temps estimé : 3 semaines.',
    },
    4: {
        icon:'🔄', title:"Renouvellement Carte Professionnelle", status:'Actif',
        instances:19, delai:'3 jours', etapes:5, progress:'91%', output:'Nouvelle Carte Professionnelle',
        steps:[
            {n:'Action 1',label:'Demande',desc:'Demande écrite du bénéficiaire',actor:'Bureau d\'ordre DMD',delai:'3 jours',status:'done'},
            {n:'Action 2',label:'Insertion',desc:'Mise à jour des informations dans le système',actor:'Chargé de dossier',delai:'-',status:'done'},
            {n:'Action 3',label:'Validation',desc:'Validation par le Directeur',actor:'Directeur',delai:'-',status:'done'},
            {n:'Action 4',label:'Tirage',desc:'Impression de la nouvelle carte',actor:'Chargé de dossier',delai:'-',status:'active'},
            {n:'Action 5',label:'Délivrance',desc:'Remise de la carte au bénéficiaire',actor:'Bureau d\'ordre DMD',delai:'-',status:'pending'},
        ],
        docs:['Formulaire de renouvellement','Ancienne carte professionnelle','Extrait casier judiciaire B3','1 photo d\'identité','Copie CNI'],
        iaReco:'✅ Ce processus fonctionne très bien avec 91% d\'avancement et 0 retard. <strong>Optimisation IA :</strong> automatiser l\'alerte de renouvellement 60 jours avant l\'expiration — cela pourrait éliminer 80% des dépôts urgents.',
    },
    5: {
        icon:'📋', title:"Duplicata Carte Professionnelle", status:'Actif',
        instances:7, delai:'1–3 jours', etapes:3, progress:'88%', output:'Duplicata de la carte',
        steps:[
            {n:'Action 1',label:'Demande',desc:'Demande avec pièces justificatives',actor:'Bureau d\'ordre DMD',delai:'1–3 jours',status:'done'},
            {n:'Action 2',label:'Insertion',desc:'Vérification et insertion dans le système',actor:'Chargé de dossier',delai:'-',status:'active'},
            {n:'Action 3',label:'Délivrance',desc:'Remise du duplicata au bénéficiaire',actor:'Bureau d\'ordre DMD',delai:'-',status:'pending'},
        ],
        docs:['Demande écrite','Attestation de perte ou vol','Extrait casier judiciaire B3','1 photo d\'identité','Copie CNI'],
        iaReco:'✅ Processus simple et efficace. L\'IA recommande d\'intégrer une vérification automatique de perte/vol avec la base de données nationale CNI pour accélérer l\'étape 2.',
    },
    6: {
        icon:'🎵', title:"Certificat Exploitation Patrimoine Musical", status:'Actif',
        instances:12, delai:'1–3 jours', etapes:7, progress:'68%', output:"Certificat d'exploitation musical",
        steps:[
            {n:'Action 1',label:'Demande certificat',desc:'Dépôt de la demande auprès du Bureau d\'ordre',actor:'Bureau d\'ordre DMD',delai:'1–3 jours',status:'done'},
            {n:'Action 2',label:'Insertion',desc:'Enregistrement dans le système',actor:'Chargé de dossier',delai:'-',status:'done'},
            {n:'Action 3',label:'Certificat initial',desc:'Remise du certificat initial d\'exploitation',actor:'Bureau d\'ordre / Chargé',delai:'-',status:'done'},
            {n:'Action 4',label:'Paiement OTDAV',desc:'Paiement à l\'OTDAV des frais d\'exploitation',actor:'Service financier OTDAV',delai:'-',status:'active'},
            {n:'Action 5',label:'Dépôt facture',desc:'Dépôt du reçu de paiement au Bureau d\'ordre',actor:'Bureau d\'ordre DMD',delai:'1–3 jours',status:'pending'},
            {n:'Action 6',label:'Insertion cert. final',desc:'Insertion du certificat final dans le système',actor:'Chargé de dossier',delai:'-',status:'pending'},
            {n:'Action 7',label:'Délivrance finale',desc:'Remise du certificat final au demandeur',actor:'Bureau d\'ordre / Chargé',delai:'-',status:'pending'},
        ],
        docs:['Demande ou formulaire officiel','CNI ou Matricule Fiscal','Sujet du certificat musical','Support audio de l\'œuvre','Texte des paroles','Certificat initial d\'exploitation (pour étape 5)'],
        iaReco:'⚠️ 3 paiements OTDAV sont en attente depuis 5 jours — les bénéficiaires n\'ont pas reçu de relance. <strong>Recommandation :</strong> envoyer des rappels automatiques avec les coordonnées OTDAV et le montant exact des frais.',
    },
    7: {
        icon:'🏛️', title:"Diplôme de Musique Arabe", status:'Actif',
        instances:89, delai:'30j + 2–4 mois', etapes:9, progress:'42%', output:'Diplôme + Convocation examens',
        steps:[
            {n:'Action 1',label:'Appel à candidature',desc:'Publication de l\'avis au public',actor:'Bureau d\'ordre DMD',delai:'30 jours',status:'done'},
            {n:'Action 2',label:'Dépôt dossiers',desc:'Dépôt par les candidats',actor:'Candidats',delai:'-',status:'done'},
            {n:'Action 3',label:'Tri des dossiers',desc:'Vérification des justificatifs',actor:'Chargé de dossier',delai:'-',status:'done'},
            {n:'Action 4',label:'Insertion listes',desc:'Insertion des listes de candidats dans le système',actor:'Chargé de dossier',delai:'-',status:'done'},
            {n:'Action 5',label:'Convocations',desc:'Insertion et envoi des convocations (lieu + dates)',actor:'Chargé de dossier + Bureau d\'ordre',delai:'-',status:'active'},
            {n:'Action 6',label:'Examens',desc:'Examens pratiques à l\'Institut Salah Mahdi',actor:'Institut Salah Mahdi',delai:'1 jour',status:'pending'},
            {n:'Action 7',label:'Résultats pratiques',desc:'Publication des résultats des examens pratiques',actor:'Institut Salah Mahdi',delai:'-',status:'pending'},
            {n:'Action 8',label:'Résultats définitifs',desc:'Annonce des résultats définitifs',actor:'Direction',delai:'-',status:'pending'},
            {n:'Action 9',label:'Remise diplômes',desc:'Remise aux admis, signés par le Directeur',actor:'Chargé de dossier + Bureau d\'ordre',delai:'2–4 mois',status:'pending'},
        ],
        docs:['Demande officielle','Copie CNI','Attestation de présence','Attestation scolaire','2 enveloppes pré-adressées et affranchies'],
        iaReco:'🔴 CRITIQUE : 14 convocations n\'ont pas encore été envoyées, bloquant 14 candidats à l\'étape 5. Les examens sont prévus dans 10 jours. <strong>Action immédiate :</strong> générer et envoyer les 14 convocations maintenant via le module Mail Maestro. L\'IA a déjà préparé le modèle de convocation.',
    },
    8: {
        icon:'🎼', title:"Diplôme d'Instrumentiste de Musique", status:'Brouillon',
        instances:0, delai:'30j + 2–4 mois', etapes:10, progress:'0%', output:'Diplôme + Résultats',
        steps:[
            {n:'Action 1',label:'Appel à candidature',desc:'Publication de l\'avis',actor:'Bureau d\'ordre DMD',delai:'30 jours',status:'pending'},
            {n:'Actions 2–5',label:'Dossiers & Convocations',desc:'Dépôt, tri, insertion listes, convocations',actor:'Chargé de dossier',delai:'-',status:'pending'},
            {n:'Action 6',label:'Examens écrits',desc:'Examens dans les centres de Sfax et Sousse',actor:'Centres des examens',delai:'-',status:'pending'},
            {n:'Action 7',label:'Résultats écrits',desc:'Résultats publiés le lendemain',actor:'Centre des examens',delai:'1 jour',status:'pending'},
            {n:'Action 8',label:'Examens oraux',desc:'Examens oraux à l\'Institut Salah Mahdi pour les admis',actor:'Institut Salah Mahdi',delai:'3 jours',status:'pending'},
            {n:'Action 9',label:'Résultats définitifs',desc:'Annonce le 3ème jour après les oraux',actor:'Institut Salah Mahdi',delai:'J+3',status:'pending'},
            {n:'Action 10',label:'Remise diplômes',desc:'Diplômes signés par le Directeur',actor:'Chargé de dossier + Bureau d\'ordre',delai:'2–4 mois',status:'pending'},
        ],
        docs:['Demande officielle','Copie CNI','2 enveloppes pré-adressées et affranchies'],
        iaReco:'📋 Ce processus est encore en brouillon avec 0 instance active. <strong>Recommandation :</strong> aligner ce processus sur le modèle du Diplôme Musique Arabe (P-007) et l\'activer pour la prochaine session. L\'IA peut générer l\'avis de candidature automatiquement.',
    },
    9: {
        icon:'🏆', title:"Certificat de Réussite à un Examen", status:'Actif',
        instances:31, delai:'1–3 jours', etapes:3, progress:'96%', output:'Certificat de réussite signé',
        steps:[
            {n:'Action 1',label:'Demande',desc:'Demande avec informations complètes du candidat',actor:'Bureau d\'ordre DMD',delai:'1–3 jours',status:'done'},
            {n:'Action 2',label:'Insertion',desc:'Vérification et saisie dans le système',actor:'Chargé de dossier',delai:'-',status:'done'},
            {n:'Action 3',label:'Délivrance',desc:'Remise du certificat signé par le responsable',actor:'Bureau d\'ordre / Chargé',delai:'-',status:'active'},
        ],
        docs:['Demande avec : Nom, Prénom, Date de naissance, N° CNI, Année de réussite'],
        iaReco:'✅ Processus excellent — 96% d\'avancement, 0 retard. Parmi les mieux gérés de la direction. L\'IA suggère de documenter ce workflow comme modèle de bonnes pratiques pour les autres processus simples.',
    },
    10: {
        icon:'🎪', title:"Exercice Profession d'Imprésario", status:'Brouillon',
        instances:9, delai:'1–3 mois', etapes:6, progress:'30%', output:"Certificat d'exercice + Cahier des charges",
        steps:[
            {n:'Action 1',label:'Cahier des charges',desc:'Téléchargement + légalisation de la signature',actor:'Bureau d\'ordre DMD',delai:'1–3 jours',status:'done'},
            {n:'Action 2',label:'Tamponnement',desc:'Apposition du cachet rond du bénéficiaire',actor:'Bureau d\'ordre DMD',delai:'1–3 jours',status:'done'},
            {n:'Action 3',label:'Constitution dossier',desc:'Dossier administratif complet de l\'organisme',actor:'Demandeur',delai:'-',status:'active'},
            {n:'Action 4',label:'Notification activité',desc:'Lettre informant la DMD du début d\'activité (1 mois avant)',actor:'Demandeur → DMD',delai:'1 mois avant',status:'pending'},
            {n:'Action 5',label:'Inspection locaux',desc:'Vérification des conditions d\'exercice',actor:'Direction',delai:'-',status:'pending'},
            {n:'Action 6',label:'Délivrance certificat',desc:'Délivrance du certificat d\'exercice d\'imprésario',actor:'Bureau d\'ordre DMD',delai:'-',status:'pending'},
        ],
        docs:['Demande avec nom, CNI, motif, adresse, téléphone','Légalisation de signature','Matricule Fiscal','KBIS','CNI','2 photos d\'identité','Certificat de non-faillite','Déclaration d\'investissement','RIB bancaire','Contrat de location ou titre de propriété','Extrait casier judiciaire B3','Assurance risques professionnels','Liste des spectacles envisagés','CV de l\'imprésario','Quittances de paiement des impôts'],
        iaReco:'🔴 L\'inspection des locaux (étape 5) est bloquée depuis 12 jours pour le dossier BenAli. Ce processus est le plus exigeant documentairement (15 pièces). <strong>Recommandation :</strong> créer une checklist digitale interactive que l\'imprésario peut remplir en ligne, réduisant les allers-retours de 60%.',
    },
};

// ── Open detail modal ──
window.wfeOpenDetail = function(id) {
    const d = wfeData[id];
    if (!d) return;

    document.getElementById('md-icon').textContent = d.icon;
    document.getElementById('md-title').textContent = d.title;
    document.getElementById('md-status').textContent = d.status;
    document.getElementById('md-status').className = 'badge ' + (d.status === 'Actif' ? 'green' : 'amber');
    document.getElementById('md-instances').textContent = d.instances;
    document.getElementById('md-delai').textContent = d.delai;
    document.getElementById('md-etapes').textContent = d.etapes;
    document.getElementById('md-progress').textContent = d.progress;
    document.getElementById('md-output').textContent = d.output;
    document.getElementById('md-ia-reco').innerHTML = d.iaReco;

    // BPMN flow
    const flow = document.getElementById('md-bpmn-flow');
    flow.innerHTML = d.steps.map((s, i) => {
        let cls = '';
        if (s.status === 'done')   cls = 'done-node';
        if (s.status === 'active') cls = 'active-node';
        const arrowClass = s.status === 'done' ? 'done' : '';
        const node = `<div class="wfe-bnode">
            <div class="wfe-bnode-box ${cls}" title="${s.label}: ${s.desc}" onclick="showToast('${s.label}: ${s.desc}', 'info')">${s.label}</div>
            <div class="wfe-bnode-label">${s.actor.split('+')[0].trim()}</div>
            <div class="wfe-bnode-time">${s.delai}</div>
        </div>`;
        const arrow = i < d.steps.length - 1 ? `<div class="wfe-barrow ${arrowClass}">›</div>` : '';
        return node + arrow;
    }).join('');

    // Steps table
    document.getElementById('md-steps-body').innerHTML = d.steps.map((s, i) => {
        const statusColors = {done:'var(--green)', active:'var(--gold)', pending:'var(--text3)', blocked:'var(--red)'};
        const statusLabels = {done:'Complété', active:'En cours', pending:'En attente', blocked:'Bloqué'};
        return `<tr>
            <td style="font-family:var(--font-mono); font-size:11px;">${s.n}</td>
            <td style="font-weight:600; color:var(--text);">${s.label}</td>
            <td>${s.desc}</td>
            <td style="font-size:11px;">${s.actor}</td>
            <td style="font-family:var(--font-mono); font-size:11px;">${s.delai}</td>
            <td><span style="display:inline-flex; align-items:center; gap:5px; font-size:11px; font-weight:600; color:${statusColors[s.status]}">
                <span style="width:6px;height:6px;border-radius:50%;background:${statusColors[s.status]};"></span>
                ${statusLabels[s.status]}
            </span></td>
        </tr>`;
    }).join('');

    // Docs
    document.getElementById('md-doc-list').innerHTML = d.docs.map(doc =>
        `<div class="wfe-doc-item">
            <div class="wfe-doc-check" style="background:var(--green-dim); color:var(--green);">✓</div>
            ${doc}
        </div>`
    ).join('');

    wfeTab('overview');
    openModal('modal-wf-detail');
}

// ── Tab switching ──
window.wfeTab = function(tab) {
    ['overview','steps','docs','instances','ia'].forEach(t => {
        document.getElementById('tabcontent-'+t).style.display = t === tab ? '' : 'none';
        const tabEl = document.getElementById('tab-'+t);
        if (tabEl) tabEl.classList.toggle('active', t === tab);
    });
}

// ── Filter ──
window.wfeFilter = function(el, group) {
    document.querySelectorAll('.wfe-ftab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    document.querySelectorAll('#wfe-grid .wfe-card').forEach(card => {
        const show = group === 'all' ||
            (group === 'alerte' && card.dataset.alert === 'alerte') ||
            card.dataset.status === group;
        card.style.display = show ? '' : 'none';
    });
}

// ── Search ──
window.wfeSearch = function(q) {
    document.querySelectorAll('#wfe-grid .wfe-card').forEach(card => {
        card.style.display = card.dataset.title.includes(q.toLowerCase()) ? '' : 'none';
    });
}
</script>

@endsection
