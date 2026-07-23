@extends('shared.layouts.backoffice')

@section('title', 'Moteur de Workflows — Direction du Livre')
@section('breadcrumb', 'Workflows')

@section('content')

<style>
/* ════════════════════════════════════════════
   WORKFLOW ENGINE LIVRE — DESIGN SYSTEM
   Aligned with Music & Danse module
════════════════════════════════════════════ */

/* ── KPIs ── */
.wfl-kpi-row {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 12px;
    margin-bottom: 22px;
}
@media (max-width: 1100px) { .wfl-kpi-row { grid-template-columns: repeat(3,1fr); } }

.wfl-kpi {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 14px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
}
.wfl-kpi-icon {
    width: 38px; height: 38px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 17px; flex-shrink: 0;
}
.wfl-kpi-val { font-size: 20px; font-weight: 900; font-family: var(--font-mono); line-height: 1; }
.wfl-kpi-lbl { font-size: 10.5px; color: var(--text3); text-transform: uppercase; letter-spacing: 0.6px; margin-top: 3px; }
.wfl-kpi-delta { font-size: 10px; font-family: var(--font-mono); font-weight: 700; margin-top: 3px; }

/* ── IA Alert Banner ── */
.wfl-ia-banner {
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
.wfl-ia-banner::after {
    content: '📚';
    position: absolute; right: 18px; top: 50%;
    transform: translateY(-50%);
    font-size: 48px; opacity: 0.06; pointer-events: none;
}
.wfl-ia-orb {
    width: 40px; height: 40px; border-radius: 10px;
    background: var(--gold-dim); border: 1px solid rgba(201,168,76,0.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; flex-shrink: 0;
    animation: wfl-pulse 3s ease-in-out infinite;
}
@keyframes wfl-pulse {
    0%,100% { box-shadow: 0 0 0 0 rgba(201,168,76,0.3); }
    50%      { box-shadow: 0 0 0 8px rgba(201,168,76,0); }
}
.wfl-ia-insights { flex: 1; }
.wfl-ia-title { font-size: 13px; font-weight: 700; color: var(--text); margin-bottom: 4px; display:flex; align-items:center; gap:8px; }
.wfl-ia-items { display: flex; flex-wrap: wrap; gap: 8px; }
.wfl-ia-chip {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 11px; border-radius: 20px;
    font-size: 11px; font-weight: 600; cursor: pointer;
    transition: opacity 0.15s;
}
.wfl-ia-chip:hover { opacity: 0.8; }

/* ── Main layout ── */
.wfl-shell {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 18px;
    align-items: start;
}
@media (max-width: 1050px) { .wfl-shell { grid-template-columns: 1fr; } }

/* ── Filter & sort bar ── */
.wfl-filterbar {
    background: var(--bg2); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 12px 16px;
    display: flex; align-items: center; gap: 10px;
    flex-wrap: wrap; margin-bottom: 16px;
}
.wfl-filter-tabs { display: flex; gap: 0; }
.wfl-ftab {
    padding: 6px 14px; font-size: 12px; font-weight: 600;
    color: var(--text3); cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all 0.15s; user-select: none; white-space: nowrap;
}
.wfl-ftab:hover { color: var(--text2); }
.wfl-ftab.active { color: var(--gold); border-bottom-color: var(--gold); }
.wfl-search {
    flex: 1; min-width: 160px;
    background: var(--bg3); border: 1px solid var(--border2);
    border-radius: var(--radius-sm); padding: 6px 11px;
    font-size: 12px; color: var(--text); font-family: var(--font-body); outline: none;
}
.wfl-search:focus { border-color: var(--gold); }
.wfl-search::placeholder { color: var(--text3); }

/* ── Process cards grid ── */
.wfl-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 14px;
}

/* ── Process card ── */
.wfl-card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    cursor: pointer;
    transition: border-color 0.2s, transform 0.15s;
}
.wfl-card:hover { border-color: var(--border2); transform: translateY(-1px); }
.wfl-card.selected { border-color: var(--gold); }

.wfl-card-top {
    padding: 14px 16px 12px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: flex-start; gap: 11px;
}
.wfl-card-icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 19px; flex-shrink: 0;
}
.wfl-card-meta { flex: 1; min-width: 0; }
.wfl-card-num {
    font-size: 9.5px; font-family: var(--font-mono); font-weight: 700;
    color: var(--text3); text-transform: uppercase; letter-spacing: 0.8px;
    margin-bottom: 3px;
}
.wfl-card-title { font-size: 13px; font-weight: 700; color: var(--text); line-height: 1.35; }
.wfl-card-key {
    font-size: 10px; font-family: var(--font-mono); color: var(--text3);
    margin-top: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}

/* ── Progress steps row ── */
.wfl-steps-row {
    padding: 10px 16px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: 0;
    overflow-x: auto;
}
.wfl-step {
    display: flex; align-items: center; gap: 0;
    flex-shrink: 0;
}
.wfl-step-dot {
    width: 22px; height: 22px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 9px; font-weight: 800;
    border: 1.5px solid var(--border2);
    background: var(--bg3);
    color: var(--text3);
    transition: all 0.2s;
    flex-shrink: 0;
    cursor: pointer;
}
.wfl-step-dot.done  { background: var(--green-dim);  border-color: var(--green);  color: var(--green); }
.wfl-step-dot.active { background: var(--gold-dim); border-color: var(--gold); color: var(--gold); animation: wfl-stepglow 2s ease-in-out infinite; }
.wfl-step-dot.blocked { background: var(--red-dim); border-color: var(--red); color: var(--red); }

@keyframes wfl-stepglow {
    0%,100% { box-shadow: 0 0 0 0 rgba(201,168,76,0.4); }
    50%      { box-shadow: 0 0 0 4px rgba(201,168,76,0); }
}

.wfl-step-line {
    width: 18px; height: 2px;
    background: var(--border);
    flex-shrink: 0;
}
.wfl-step-line.done { background: var(--green); }
.wfl-step-line.active { background: linear-gradient(90deg, var(--gold), var(--border)); }

/* ── Stats + progress bar ── */
.wfl-card-stats {
    padding: 10px 16px;
    display: flex; gap: 0;
}
.wfl-cstat {
    flex: 1; text-align: center;
    padding: 4px 0;
    border-right: 1px solid var(--border);
}
.wfl-cstat:last-child { border-right: none; }
.wfl-cstat-val { font-size: 15px; font-weight: 900; font-family: var(--font-mono); }
.wfl-cstat-lbl { font-size: 9.5px; color: var(--text3); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px; }

.wfl-progress-wrap {
    padding: 0 16px 10px;
    display: flex; flex-direction: column; gap: 4px;
}
.wfl-progress-row { display: flex; align-items: center; gap: 8px; }
.wfl-progress-track {
    flex: 1; height: 4px; background: var(--bg4); border-radius: 2px; overflow: hidden;
}
.wfl-progress-fill { height: 100%; border-radius: 2px; transition: width 0.6s ease; }
.wfl-progress-pct { font-size: 10px; font-family: var(--font-mono); font-weight: 700; min-width: 28px; text-align: right; }

/* ── Card footer ── */
.wfl-card-foot {
    padding: 10px 16px;
    border-top: 1px solid var(--border);
    display: flex; align-items: center; gap: 7px;
    flex-wrap: wrap;
}
.wfl-foot-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 11px; border-radius: var(--radius-sm);
    font-size: 11px; font-weight: 600;
    cursor: pointer; border: 1px solid var(--border2);
    background: var(--bg3); color: var(--text2);
    font-family: var(--font-body); transition: all 0.15s; white-space: nowrap;
}
.wfl-foot-btn:hover { background: var(--bg4); color: var(--text); }
.wfl-foot-btn.gold { background: var(--gold-dim); border-color: rgba(201,168,76,0.3); color: var(--gold); }
.wfl-foot-btn.gold:hover { background: rgba(201,168,76,0.2); }
.wfl-foot-ia {
    margin-left: auto;
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 10px; font-weight: 700;
    padding: 3px 9px; border-radius: 20px;
    background: var(--purple-dim); color: var(--purple);
    border: 1px solid rgba(167,139,250,0.2);
}

.wfl-ia-alert-tag {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 9.5px; font-weight: 700; padding: 2px 8px; border-radius: 10px;
}

.wfl-deadline {
    padding: 0 16px 10px;
    display: flex; align-items: center; gap: 8px;
    font-size: 11px; color: var(--text3);
}

/* ════ RIGHT SIDEBAR ════ */
.wfl-sidebar { display: flex; flex-direction: column; gap: 16px; position: sticky; top: 76px; }

.wfl-sb-panel {
    background: var(--bg2); border: 1px solid var(--border);
    border-radius: var(--radius); overflow: hidden;
}
.wfl-sb-head {
    padding: 12px 16px; border-bottom: 1px solid var(--border);
    font-size: 12px; font-weight: 700; color: var(--text);
    display: flex; align-items: center; justify-content: space-between; gap: 8px;
}
.wfl-sb-body { padding: 14px 16px; }

.wfl-queue-item {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 10px 14px; border-bottom: 1px solid var(--border);
    cursor: pointer; transition: background 0.15s;
}
.wfl-queue-item:last-child { border-bottom: none; }
.wfl-queue-item:hover { background: var(--bg3); }
.wfl-queue-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; margin-top: 4px; }
.wfl-queue-body { flex: 1; min-width: 0; }
.wfl-queue-name { font-size: 12px; font-weight: 600; color: var(--text); }
.wfl-queue-proc { font-size: 10.5px; color: var(--text3); margin-top: 1px; }
.wfl-queue-time { font-size: 10px; font-family: var(--font-mono); color: var(--text3); flex-shrink: 0; }

.wfl-ia-sugg {
    display: flex; flex-direction: column; gap: 8px;
    padding: 12px 14px;
}
.wfl-sugg-item {
    display: flex; align-items: flex-start; gap: 9px;
    padding: 9px 11px; border-radius: var(--radius-sm);
    background: var(--bg3); border: 1px solid var(--border);
    cursor: pointer; transition: border-color 0.15s;
}
.wfl-sugg-item:hover { border-color: var(--gold); }
.wfl-sugg-icon { font-size: 15px; flex-shrink: 0; }
.wfl-sugg-text { font-size: 11.5px; color: var(--text2); line-height: 1.45; flex: 1; }
.wfl-sugg-action { font-size: 10px; color: var(--gold); font-weight: 700; margin-top: 3px; }

.wfl-heatmap {
    display: flex; flex-direction: column; gap: 6px;
    padding: 12px 14px;
}
.wfl-heat-row { display: flex; align-items: center; gap: 8px; }
.wfl-heat-label { font-size: 11px; color: var(--text2); min-width: 80px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.wfl-heat-bar { flex: 1; height: 6px; background: var(--bg4); border-radius: 3px; overflow: hidden; }
.wfl-heat-fill { height: 100%; border-radius: 3px; }
.wfl-heat-val { font-size: 10.5px; font-family: var(--font-mono); font-weight: 700; min-width: 38px; text-align: right; flex-shrink: 0; }

.wfl-quick { display: flex; flex-direction: column; gap: 7px; padding: 12px 14px; }
.wfl-qa {
    display: flex; align-items: center; gap: 8px; padding: 8px 11px;
    background: var(--bg3); border: 1px solid var(--border);
    border-radius: var(--radius-sm); font-size: 12px; font-weight: 600;
    color: var(--text2); cursor: pointer; font-family: var(--font-body);
    transition: all 0.15s;
}
.wfl-qa:hover { background: var(--bg4); color: var(--text); border-color: var(--border2); }

/* ════ MODAL — DETAIL PROCESS ════ */
.wfl-modal-wide { max-width: 720px; }

.wfl-detail-tabs {
    display: flex; gap: 0;
    border-bottom: 1px solid var(--border);
    margin-bottom: 18px;
}
.wfl-dtab {
    padding: 8px 16px; font-size: 12.5px; font-weight: 600;
    color: var(--text3); cursor: pointer;
    border-bottom: 2px solid transparent; transition: all 0.15s;
}
.wfl-dtab:hover { color: var(--text2); }
.wfl-dtab.active { color: var(--gold); border-bottom-color: var(--gold); }

.wfl-bpmn-viewer {
    background: var(--bg3); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 20px;
    overflow-x: auto; min-height: 160px;
}
.wfl-bpmn-flow {
    display: flex; align-items: center; gap: 0;
    min-width: max-content;
}
.wfl-bnode {
    display: flex; flex-direction: column; align-items: center; gap: 5px;
    flex-shrink: 0;
}
.wfl-bnode-box {
    padding: 8px 12px; border-radius: 6px;
    border: 1.5px solid var(--border2);
    background: var(--bg2);
    font-size: 10.5px; font-weight: 600; color: var(--text2);
    text-align: center; max-width: 90px;
    line-height: 1.3;
    cursor: pointer; transition: all 0.15s;
}
.wfl-bnode-box:hover { border-color: var(--gold); color: var(--text); }
.wfl-bnode-box.start { background: var(--green-dim); border-color: var(--green); color: var(--green); }
.wfl-bnode-box.end   { background: var(--red-dim);   border-color: var(--red);   color: var(--red); }
.wfl-bnode-box.done-node { background: var(--green-dim); border-color: var(--green); }
.wfl-bnode-box.active-node { background: var(--gold-dim); border-color: var(--gold); color: var(--gold); }
.wfl-bnode-label { font-size: 9px; color: var(--text3); text-align: center; max-width: 90px; line-height: 1.2; }
.wfl-bnode-time  { font-size: 9px; font-family: var(--font-mono); color: var(--text3); }
.wfl-barrow { font-size: 16px; color: var(--border2); margin: 0 4px; align-self: flex-start; padding-top: 12px; flex-shrink: 0; }
.wfl-barrow.done { color: var(--green); }

.wfl-steps-table {
    width: 100%; border-collapse: collapse; font-size: 12px;
}
.wfl-steps-table th {
    text-align: left; padding: 8px 10px;
    font-size: 10px; text-transform: uppercase; letter-spacing: 0.7px;
    color: var(--text3); font-weight: 700;
    border-bottom: 1px solid var(--border);
}
.wfl-steps-table td { padding: 9px 10px; border-bottom: 1px solid var(--border); color: var(--text2); }
.wfl-steps-table tr:last-child td { border-bottom: none; }
.wfl-steps-table tr:hover td { background: var(--bg3); }

.wfl-doc-list { display: flex; flex-direction: column; gap: 6px; }
.wfl-doc-item {
    display: flex; align-items: center; gap: 9px;
    padding: 8px 11px; background: var(--bg3);
    border-radius: var(--radius-sm); border: 1px solid var(--border);
    font-size: 12px; color: var(--text2);
}
.wfl-doc-check { width: 16px; height: 16px; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 9px; font-weight: 700; flex-shrink: 0; }

.wfl-ia-modal-panel {
    background: linear-gradient(135deg, rgba(167,139,250,0.07), rgba(96,165,250,0.05));
    border: 1px solid rgba(167,139,250,0.2);
    border-radius: var(--radius); padding: 14px 16px;
    display: flex; flex-direction: column; gap: 10px;
}
.wfl-ia-modal-title {
    font-size: 11px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.8px; color: var(--purple);
    display: flex; align-items: center; gap: 6px;
}
.wfl-ia-reco {
    font-size: 12.5px; color: var(--text2); line-height: 1.55;
    padding: 10px 12px; background: var(--bg3); border-radius: var(--radius-sm);
    border-left: 3px solid var(--purple);
}
.wfl-ia-actions { display: flex; gap: 7px; flex-wrap: wrap; }
.wfl-ia-act-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 12px; border-radius: var(--radius-sm);
    font-size: 11.5px; font-weight: 600; cursor: pointer;
    font-family: var(--font-body); transition: all 0.15s;
}
.wfl-ia-act-btn.purple { background: var(--purple-dim); color: var(--purple); border: 1px solid rgba(167,139,250,0.25); }
.wfl-ia-act-btn.purple:hover { background: rgba(167,139,250,0.18); }
.wfl-ia-act-btn.teal   { background: var(--teal-dim);   color: var(--teal);   border: 1px solid rgba(45,212,191,0.2); }
.wfl-ia-act-btn.gold   { background: var(--gold-dim);   color: var(--gold);   border: 1px solid rgba(201,168,76,0.25); }

@keyframes wfl-fadein { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
.wfl-fadein { animation: wfl-fadein 0.3s ease forwards; }
</style>

{{-- ════════ KPI ROW ════════ --}}
<div class="wfl-kpi-row">
    <div class="wfl-kpi">
        <div class="wfl-kpi-icon" style="background:var(--gold-dim);">📚</div>
        <div>
            <div class="wfl-kpi-val" style="color:var(--gold);">12</div>
            <div class="wfl-kpi-lbl">Processus DDL</div>
            <div class="wfl-kpi-delta" style="color:var(--teal);">→ 9 actifs · 3 brouillon</div>
        </div>
    </div>
    <div class="wfl-kpi">
        <div class="wfl-kpi-icon" style="background:var(--blue-dim);">📂</div>
        <div>
            <div class="wfl-kpi-val" style="color:var(--blue);">186</div>
            <div class="wfl-kpi-lbl">Instances en cours</div>
            <div class="wfl-kpi-delta" style="color:var(--green);">↑ +24 cette semaine</div>
        </div>
    </div>
    <div class="wfl-kpi">
        <div class="wfl-kpi-icon" style="background:var(--red-dim);">⏰</div>
        <div>
            <div class="wfl-kpi-val" style="color:var(--red);">17</div>
            <div class="wfl-kpi-lbl">En retard</div>
            <div class="wfl-kpi-delta" style="color:var(--red);">↓ Action requise</div>
        </div>
    </div>
    <div class="wfl-kpi">
        <div class="wfl-kpi-icon" style="background:var(--green-dim);">✅</div>
        <div>
            <div class="wfl-kpi-val" style="color:var(--green);">143</div>
            <div class="wfl-kpi-lbl">Complétés ce mois</div>
            <div class="wfl-kpi-delta" style="color:var(--green);">↑ +15%</div>
        </div>
    </div>
    <div class="wfl-kpi">
        <div class="wfl-kpi-icon" style="background:var(--purple-dim);">🤖</div>
        <div>
            <div class="wfl-kpi-val" style="color:var(--purple);">93%</div>
            <div class="wfl-kpi-lbl">Fiabilité IA</div>
            <div class="wfl-kpi-delta" style="color:var(--purple);">→ Prédictions correctes</div>
        </div>
    </div>
</div>

{{-- ════════ IA BANNER ════════ --}}
<div class="wfl-ia-banner">
    <div class="wfl-ia-orb">🤖</div>
    <div class="wfl-ia-insights">
        <div class="wfl-ia-title">
            IA Analytique — 5 alertes détectées maintenant
            <span style="font-size:10px; padding:2px 8px; background:var(--gold-dim); color:var(--gold); border-radius:20px; font-weight:700;">LIVE</span>
        </div>
        <div class="wfl-ia-items">
            <div class="wfl-ia-chip" style="background:var(--red-dim); color:var(--red);" onclick="showToast('Demandes de droits bloquées étape 3 depuis 15j', 'info')">
                🔴 Droits d'édition — 8 dossiers bloqués
            </div>
            <div class="wfl-ia-chip" style="background:var(--amber-dim); color:var(--amber);" onclick="showToast('3 participations foire arrivent à échéance dans 5 jours', 'info')">
                ⚠️ 3 participations foire expirent dans 5j
            </div>
            <div class="wfl-ia-chip" style="background:var(--blue-dim); color:var(--blue);" onclick="showToast('Transport: 5 demandes en attente de devis transporteur', 'info')">
                📡 5 demandes transport en attente de devis
            </div>
            <div class="wfl-ia-chip" style="background:var(--purple-dim); color:var(--purple);" onclick="showToast('Optimisation: Circuit attestation TVA peut être réduit de 2 à 1j', 'success')">
                ✨ Optimisation: TVA peut passer à 1j
            </div>
        </div>
    </div>
    <button class="btn btn-outline btn-sm" onclick="showToast('Rapport IA complet généré', 'info')">📊 Rapport IA</button>
</div>

{{-- ════════ MAIN SHELL ════════ --}}
<div class="wfl-shell">

    {{-- ══ LEFT: CARDS GRID ══ --}}
    <div>
        {{-- Filter bar --}}
        <div class="wfl-filterbar">
            <div class="wfl-filter-tabs">
                <div class="wfl-ftab active" onclick="wflFilter(this,'all')">Tous (12)</div>
                <div class="wfl-ftab" onclick="wflFilter(this,'actif')">✅ Actifs (9)</div>
                <div class="wfl-ftab" onclick="wflFilter(this,'brouillon')">📝 Brouillon (3)</div>
                <div class="wfl-ftab" onclick="wflFilter(this,'alerte')">🔴 Alertes (5)</div>
            </div>
            <input type="text" class="wfl-search" placeholder="🔍 Rechercher un processus..." oninput="wflSearch(this.value)">
            <button class="btn btn-outline btn-sm" onclick="showToast('Import BPMN XML', 'info')">📥 Importer BPMN</button>
            <button class="btn btn-gold btn-sm" onclick="showToast('Nouveau processus — éditeur BPMN ouvert', 'info')">+ Nouveau</button>
        </div>

        {{-- Process cards --}}
        <div class="wfl-grid" id="wfl-grid">

            @php
            $processes = [
                // Droits d'édition
                [
                    'id' => 1,
                    'icon' => '📄',
                    'icon_bg' => 'var(--gold-dim)',
                    'num' => 'P-LIV-001',
                    'title' => 'Transfert de droits d\'édition',
                    'key' => 'wf-transfert-droits · v2.1',
                    'status' => 'actif',
                    'instances' => 34,
                    'delai' => '3 jours',
                    'delai_color' => 'var(--green)',
                    'etapes' => 6,
                    'progress' => 78,
                    'progress_color' => 'var(--gold)',
                    'en_retard' => 4,
                    'steps' => ['Dépôt', 'Inspection docs', 'Validation agent', 'Signature dir.', 'Enregistrement', 'Délivrance attestation'],
                    'active_step' => 3,
                    'ia_alert' => ['color' => 'red', 'msg' => '🔴 4 dossiers bloqués étape 3'],
                    'output' => 'Attestation de transfert de droits',
                ],
                // Participation foire
                [
                    'id' => 2,
                    'icon' => '🌍',
                    'icon_bg' => 'var(--teal-dim)',
                    'num' => 'P-LIV-002',
                    'title' => 'Participation foire internationale',
                    'key' => 'wf-participation-foire · v1.3',
                    'status' => 'actif',
                    'instances' => 28,
                    'delai' => '3 jours',
                    'delai_color' => 'var(--green)',
                    'etapes' => 5,
                    'progress' => 72,
                    'progress_color' => 'var(--teal)',
                    'en_retard' => 3,
                    'steps' => ['Dépôt demande', 'Vérification éligibilité', 'Validation comité', 'Signature DG', 'Attestation'],
                    'active_step' => 3,
                    'ia_alert' => ['color' => 'amber', 'msg' => '⚠️ 3 foires à venir dans 30j'],
                    'output' => 'Attestation de participation',
                ],
                // Transport
                [
                    'id' => 3,
                    'icon' => '🚚',
                    'icon_bg' => 'var(--blue-dim)',
                    'num' => 'P-LIV-003',
                    'title' => 'Couverture frais de transport',
                    'key' => 'wf-transport-livre · v1.1',
                    'status' => 'actif',
                    'instances' => 19,
                    'delai' => '3 jours',
                    'delai_color' => 'var(--green)',
                    'etapes' => 5,
                    'progress' => 65,
                    'progress_color' => 'var(--blue)',
                    'en_retard' => 2,
                    'steps' => ['Dépôt', 'Vérification devis', 'Validation logistique', 'Signature DG', 'Prise en charge'],
                    'active_step' => 3,
                    'ia_alert' => ['color' => 'amber', 'msg' => '⚠️ 2 devis en attente'],
                    'output' => 'Attestation de prise en charge transport',
                ],
                // TVA
                [
                    'id' => 4,
                    'icon' => '📋',
                    'icon_bg' => 'var(--green-dim)',
                    'num' => 'P-LIV-004',
                    'title' => 'Exonération TVA matériaux',
                    'key' => 'wf-exoneration-tva · v1.0',
                    'status' => 'actif',
                    'instances' => 42,
                    'delai' => '2 jours',
                    'delai_color' => 'var(--green)',
                    'etapes' => 4,
                    'progress' => 88,
                    'progress_color' => 'var(--green)',
                    'en_retard' => 1,
                    'steps' => ['Dépôt demande', 'Vérification matériaux', 'Validation agent', 'Attestation exonération'],
                    'active_step' => 3,
                    'ia_alert' => null,
                    'output' => 'Attestation d\'exonération TVA',
                ],
                // Dépôt légal
                [
                    'id' => 5,
                    'icon' => '📚',
                    'icon_bg' => 'var(--purple-dim)',
                    'num' => 'P-LIV-005',
                    'title' => 'Dépôt légal des publications',
                    'key' => 'wf-depot-legal · v1.2',
                    'status' => 'actif',
                    'instances' => 53,
                    'delai' => '5 jours',
                    'delai_color' => 'var(--amber)',
                    'etapes' => 4,
                    'progress' => 92,
                    'progress_color' => 'var(--purple)',
                    'en_retard' => 5,
                    'steps' => ['Dépôt', 'Enregistrement', 'Vérification conformité', 'Délivrance récépissé'],
                    'active_step' => 3,
                    'ia_alert' => ['color' => 'red', 'msg' => '🔴 5 dépôts non conformes'],
                    'output' => 'Récépissé de dépôt légal',
                ],
                // Aide à l'édition
                [
                    'id' => 6,
                    'icon' => '🎓',
                    'icon_bg' => 'var(--amber-dim)',
                    'num' => 'P-LIV-006',
                    'title' => 'Aide à l\'édition (Fonds national)',
                    'key' => 'wf-aide-edition · v1.0',
                    'status' => 'actif',
                    'instances' => 22,
                    'delai' => '15 jours',
                    'delai_color' => 'var(--red)',
                    'etapes' => 7,
                    'progress' => 45,
                    'progress_color' => 'var(--amber)',
                    'en_retard' => 2,
                    'steps' => ['Dépôt dossier', 'Pré-sélection', 'Expertise', 'Commission', 'Validation DG', 'Notification', 'Versement'],
                    'active_step' => 4,
                    'ia_alert' => ['color' => 'amber', 'msg' => '⚠️ Commission prévue dans 10j'],
                    'output' => 'Convention d\'aide + Attestation',
                ],
                // Label Livre Tunisien
                [
                    'id' => 7,
                    'icon' => '🏷️',
                    'icon_bg' => 'var(--red-dim)',
                    'num' => 'P-LIV-007',
                    'title' => 'Attribution Label "Livre Tunisien"',
                    'key' => 'wf-label-livre · v1.0',
                    'status' => 'actif',
                    'instances' => 15,
                    'delai' => '7 jours',
                    'delai_color' => 'var(--amber)',
                    'etapes' => 6,
                    'progress' => 60,
                    'progress_color' => 'var(--red)',
                    'en_retard' => 0,
                    'steps' => ['Dépôt', 'Pré-sélection', 'Expertise artistique', 'Commission label', 'Validation DG', 'Attribution'],
                    'active_step' => 4,
                    'ia_alert' => null,
                    'output' => 'Label + Certificat d\'attribution',
                ],
                // Agrément d'éditeur
                [
                    'id' => 8,
                    'icon' => '📜',
                    'icon_bg' => 'var(--blue-dim)',
                    'num' => 'P-LIV-008',
                    'title' => 'Agrément d\'éditeur',
                    'key' => 'wf-agrement-editeur · v1.0',
                    'status' => 'actif',
                    'instances' => 9,
                    'delai' => '30 jours',
                    'delai_color' => 'var(--red)',
                    'etapes' => 8,
                    'progress' => 35,
                    'progress_color' => 'var(--blue)',
                    'en_retard' => 3,
                    'steps' => ['Dépôt dossier', 'Vérification juridique', 'Commission agrément', 'Enquête terrain', 'Rapport', 'Validation DG', 'Publication JO', 'Délivrance'],
                    'active_step' => 3,
                    'ia_alert' => ['color' => 'red', 'msg' => '🔴 3 dossiers incomplets'],
                    'output' => 'Arrêté d\'agrément + Carte d\'éditeur',
                ],
                // Cession de droits
                [
                    'id' => 9,
                    'icon' => '🤝',
                    'icon_bg' => 'var(--teal-dim)',
                    'num' => 'P-LIV-009',
                    'title' => 'Cession de droits d\'auteur',
                    'key' => 'wf-cession-droits-auteur · v1.0',
                    'status' => 'brouillon',
                    'instances' => 0,
                    'delai' => '3 jours',
                    'delai_color' => 'var(--text3)',
                    'etapes' => 5,
                    'progress' => 0,
                    'progress_color' => 'var(--teal)',
                    'en_retard' => 0,
                    'steps' => ['Dépôt contrat', 'Vérification légalité', 'Enregistrement', 'Signature parties', 'Délivrance attestation'],
                    'active_step' => 0,
                    'ia_alert' => null,
                    'output' => 'Attestation de cession',
                ],
                // Imprimatur
                [
                    'id' => 10,
                    'icon' => '✍️',
                    'icon_bg' => 'var(--green-dim)',
                    'num' => 'P-LIV-010',
                    'title' => 'Imprimatur (livre religieux)',
                    'key' => 'wf-imprimatur-livre · v1.0',
                    'status' => 'actif',
                    'instances' => 11,
                    'delai' => '15 jours',
                    'delai_color' => 'var(--amber)',
                    'etapes' => 5,
                    'progress' => 55,
                    'progress_color' => 'var(--green)',
                    'en_retard' => 2,
                    'steps' => ['Dépôt manuscrit', 'Examen comité', 'Avis théologique', 'Validation DG', 'Délivrance'],
                    'active_step' => 3,
                    'ia_alert' => ['color' => 'amber', 'msg' => '⚠️ 2 manuscrits en attente d\'avis'],
                    'output' => 'Autorisation d\'imprimer',
                ],
                // ISBN
                [
                    'id' => 11,
                    'icon' => '🔢',
                    'icon_bg' => 'var(--purple-dim)',
                    'num' => 'P-LIV-011',
                    'title' => 'Attribution ISBN',
                    'key' => 'wf-isbn-attribution · v1.0',
                    'status' => 'actif',
                    'instances' => 31,
                    'delai' => '2 jours',
                    'delai_color' => 'var(--green)',
                    'etapes' => 3,
                    'progress' => 95,
                    'progress_color' => 'var(--purple)',
                    'en_retard' => 0,
                    'steps' => ['Demande', 'Vérification', 'Attribution'],
                    'active_step' => 2,
                    'ia_alert' => null,
                    'output' => 'Numéro ISBN + Attestation',
                ],
                // Contrat d'édition
                [
                    'id' => 12,
                    'icon' => '📝',
                    'icon_bg' => 'var(--amber-dim)',
                    'num' => 'P-LIV-012',
                    'title' => 'Enregistrement contrat d\'édition',
                    'key' => 'wf-contrat-edition · v1.0',
                    'status' => 'brouillon',
                    'instances' => 5,
                    'delai' => '5 jours',
                    'delai_color' => 'var(--red)',
                    'etapes' => 4,
                    'progress' => 20,
                    'progress_color' => 'var(--amber)',
                    'en_retard' => 1,
                    'steps' => ['Dépôt contrat', 'Vérification', 'Enregistrement BnF', 'Délivrance accuser'],
                    'active_step' => 2,
                    'ia_alert' => ['color' => 'amber', 'msg' => '⚠️ 1 contrat sans signature'],
                    'output' => 'Accusé d\'enregistrement',
                ],
            ];

            $statusColors = ['actif' => ['bg'=>'var(--green-dim)', 'color'=>'var(--green)', 'label'=>'Actif'], 'brouillon' => ['bg'=>'var(--amber-dim)', 'color'=>'var(--amber)', 'label'=>'Brouillon']];
            @endphp

            @foreach($processes as $p)
            <div class="wfl-card wfl-fadein"
                 data-status="{{ $p['status'] }}"
                 data-title="{{ strtolower($p['title']) }}"
                 data-alert="{{ $p['ia_alert'] ? 'alerte' : '' }}"
                 style="animation-delay: {{ ($loop->index * 0.04) }}s;"
                 onclick="wflOpenDetail({{ $p['id'] }})">

                <div class="wfl-card-top">
                    <div class="wfl-card-icon" style="background: {{ $p['icon_bg'] }};">{{ $p['icon'] }}</div>
                    <div class="wfl-card-meta">
                        <div class="wfl-card-num">{{ $p['num'] }}</div>
                        <div class="wfl-card-title">{{ $p['title'] }}</div>
                        <div class="wfl-card-key">{{ $p['key'] }}</div>
                    </div>
                    <div>
                        <span class="badge" style="background:{{ $statusColors[$p['status']]['bg'] }}; color:{{ $statusColors[$p['status']]['color'] }}; font-size:10px;">{{ $statusColors[$p['status']]['label'] }}</span>
                        @if($p['ia_alert'])
                            <div style="margin-top: 4px;">
                                <span class="wfl-ia-alert-tag" style="background: var(--{{ $p['ia_alert']['color'] }}-dim); color: var(--{{ $p['ia_alert']['color'] }});">{{ $p['ia_alert']['msg'] }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="wfl-steps-row">
                    @foreach($p['steps'] as $si => $s)
                        @php $stepIdx = $si + 1; @endphp
                        <div class="wfl-step">
                            <div class="wfl-step-dot
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
                        <div class="wfl-step-line {{ $stepIdx < $p['active_step'] ? 'done' : ($stepIdx == $p['active_step'] ? 'active' : '') }}"></div>
                        @endif
                    @endforeach
                </div>

                <div class="wfl-card-stats">
                    <div class="wfl-cstat">
                        <div class="wfl-cstat-val" style="color:var(--blue);">{{ $p['instances'] }}</div>
                        <div class="wfl-cstat-lbl">Instances</div>
                    </div>
                    <div class="wfl-cstat">
                        <div class="wfl-cstat-val" style="color:{{ $p['delai_color'] }};">{{ $p['delai'] }}</div>
                        <div class="wfl-cstat-lbl">Délai</div>
                    </div>
                    <div class="wfl-cstat">
                        <div class="wfl-cstat-val" style="color:var(--gold);">{{ $p['etapes'] }}</div>
                        <div class="wfl-cstat-lbl">Étapes</div>
                    </div>
                    <div class="wfl-cstat">
                        <div class="wfl-cstat-val" style="color:{{ $p['en_retard'] > 0 ? 'var(--red)' : 'var(--green)' }};">{{ $p['en_retard'] }}</div>
                        <div class="wfl-cstat-lbl">En retard</div>
                    </div>
                </div>

                <div class="wfl-progress-wrap">
                    <div class="wfl-progress-row">
                        <span style="font-size:10px; color:var(--text3);">Avancement</span>
                        <div class="wfl-progress-track">
                            <div class="wfl-progress-fill" style="width:{{ $p['progress'] }}%; background:{{ $p['progress_color'] }};"></div>
                        </div>
                        <span class="wfl-progress-pct" style="color:{{ $p['progress_color'] }};">{{ $p['progress'] }}%</span>
                    </div>
                </div>

                <div class="wfl-card-foot" onclick="event.stopPropagation()">
                    <button class="wfl-foot-btn" onclick="wflOpenDetail({{ $p['id'] }})">👁 Voir</button>
                    <button class="wfl-foot-btn" onclick="showToast('Instances du processus {{ $p['num'] }}', 'info')">📂 Instances</button>
                    <button class="wfl-foot-btn gold" onclick="wflOpenDetail({{ $p['id'] }}); setTimeout(()=>wflTab('ia'), 300)">🤖 Analyse IA</button>
                    <span class="wfl-foot-ia">✨ IA</span>
                </div>
            </div>
            @endforeach

        </div>
    </div>

    {{-- ══ RIGHT SIDEBAR ══ --}}
    <div class="wfl-sidebar">

        <div class="wfl-sb-panel">
            <div class="wfl-sb-head">
                ⏳ File d'attente urgente
                <span style="font-size:10px; padding:2px 8px; background:var(--red-dim); color:var(--red); border-radius:10px; font-weight:700;">17</span>
            </div>
            <div class="wfl-queue-item">
                <div class="wfl-queue-dot" style="background:var(--red);"></div>
                <div class="wfl-queue-body">
                    <div class="wfl-queue-name">Éditions Cérès — Droits</div>
                    <div class="wfl-queue-proc">P-LIV-001 · Étape 3 bloquée</div>
                </div>
                <span class="wfl-queue-time">15j</span>
            </div>
            <div class="wfl-queue-item">
                <div class="wfl-queue-dot" style="background:var(--red);"></div>
                <div class="wfl-queue-body">
                    <div class="wfl-queue-name">Sud Éditions — Agrément</div>
                    <div class="wfl-queue-proc">P-LIV-008 · Dossier incomplet</div>
                </div>
                <span class="wfl-queue-time">12j</span>
            </div>
            <div class="wfl-queue-item">
                <div class="wfl-queue-dot" style="background:var(--amber);"></div>
                <div class="wfl-queue-body">
                    <div class="wfl-queue-name">5 demandes — Transport</div>
                    <div class="wfl-queue-proc">P-LIV-003 · Devis en attente</div>
                </div>
                <span class="wfl-queue-time">8j</span>
            </div>
            <div class="wfl-queue-item">
                <div class="wfl-queue-dot" style="background:var(--amber);"></div>
                <div class="wfl-queue-body">
                    <div class="wfl-queue-name">3 participations foire</div>
                    <div class="wfl-queue-proc">P-LIV-002 · Validation comité</div>
                </div>
                <span class="wfl-queue-time">5j</span>
            </div>
            <div class="wfl-queue-item">
                <div class="wfl-queue-dot" style="background:var(--blue);"></div>
                <div class="wfl-queue-body">
                    <div class="wfl-queue-name">2 manuscrits — Imprimatur</div>
                    <div class="wfl-queue-proc">P-LIV-010 · Avis théologique</div>
                </div>
                <span class="wfl-queue-time">3j</span>
            </div>
        </div>

        <div class="wfl-sb-panel">
            <div class="wfl-sb-head">🤖 Suggestions IA</div>
            <div class="wfl-ia-sugg">
                <div class="wfl-sugg-item" onclick="showToast('Relance automatique envoyée aux 4 éditeurs', 'success')">
                    <div class="wfl-sugg-icon">📧</div>
                    <div>
                        <div class="wfl-sugg-text">Relancer les 4 dossiers de droits bloqués à l'étape 3 (documents manquants)</div>
                        <div class="wfl-sugg-action">→ Envoyer relance maintenant</div>
                    </div>
                </div>
                <div class="wfl-sugg-item" onclick="showToast('Validation groupée des demandes TVA', 'success')">
                    <div class="wfl-sugg-icon">⚡</div>
                    <div>
                        <div class="wfl-sugg-text">12 demandes TVA complètes et vérifiées — validation possible en lot</div>
                        <div class="wfl-sugg-action">→ Valider en 1 clic</div>
                    </div>
                </div>
                <div class="wfl-sugg-item" onclick="showToast('Rapport d\'optimisation généré', 'info')">
                    <div class="wfl-sugg-icon">📊</div>
                    <div>
                        <div class="wfl-sugg-text">Optimisation: Le circuit d'agrément peut être réduit de 30 à 20 jours</div>
                        <div class="wfl-sugg-action">→ Analyser proposition</div>
                    </div>
                </div>
                <div class="wfl-sugg-item" onclick="showToast('Alertes ISBN envoyées', 'success')">
                    <div class="wfl-sugg-icon">🔢</div>
                    <div>
                        <div class="wfl-sugg-text">31 demandes ISBN en attente — traitement moyen 2j actuellement</div>
                        <div class="wfl-sugg-action">→ Automatiser l'attribution</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="wfl-sb-panel">
            <div class="wfl-sb-head">📊 Délais moyens actuels</div>
            <div class="wfl-heatmap">
                <div class="wfl-heat-row">
                    <span class="wfl-heat-label">Transfert droits</span>
                    <div class="wfl-heat-bar"><div class="wfl-heat-fill" style="width:8%; background:var(--green);"></div></div>
                    <span class="wfl-heat-val" style="color:var(--green);">3j</span>
                </div>
                <div class="wfl-heat-row">
                    <span class="wfl-heat-label">Participation foire</span>
                    <div class="wfl-heat-bar"><div class="wfl-heat-fill" style="width:8%; background:var(--green);"></div></div>
                    <span class="wfl-heat-val" style="color:var(--green);">3j</span>
                </div>
                <div class="wfl-heat-row">
                    <span class="wfl-heat-label">Transport</span>
                    <div class="wfl-heat-bar"><div class="wfl-heat-fill" style="width:8%; background:var(--green);"></div></div>
                    <span class="wfl-heat-val" style="color:var(--green);">3j</span>
                </div>
                <div class="wfl-heat-row">
                    <span class="wfl-heat-label">TVA exonération</span>
                    <div class="wfl-heat-bar"><div class="wfl-heat-fill" style="width:5%; background:var(--green);"></div></div>
                    <span class="wfl-heat-val" style="color:var(--green);">2j</span>
                </div>
                <div class="wfl-heat-row">
                    <span class="wfl-heat-label">Dépôt légal</span>
                    <div class="wfl-heat-bar"><div class="wfl-heat-fill" style="width:13%; background:var(--amber);"></div></div>
                    <span class="wfl-heat-val" style="color:var(--amber);">5j</span>
                </div>
                <div class="wfl-heat-row">
                    <span class="wfl-heat-label">Aide édition</span>
                    <div class="wfl-heat-bar"><div class="wfl-heat-fill" style="width:40%; background:var(--red);"></div></div>
                    <span class="wfl-heat-val" style="color:var(--red);">15j</span>
                </div>
                <div class="wfl-heat-row">
                    <span class="wfl-heat-label">Agrément</span>
                    <div class="wfl-heat-bar"><div class="wfl-heat-fill" style="width:80%; background:var(--red);"></div></div>
                    <span class="wfl-heat-val" style="color:var(--red);">30j</span>
                </div>
                <div class="wfl-heat-row">
                    <span class="wfl-heat-label">ISBN</span>
                    <div class="wfl-heat-bar"><div class="wfl-heat-fill" style="width:5%; background:var(--green);"></div></div>
                    <span class="wfl-heat-val" style="color:var(--green);">2j</span>
                </div>
            </div>
        </div>

        <div class="wfl-sb-panel">
            <div class="wfl-sb-head">⚡ Actions rapides</div>
            <div class="wfl-quick">
                <button class="wfl-qa" onclick="showToast('Rapport global exporté', 'info')">📥 Exporter rapport global</button>
                <button class="wfl-qa" onclick="showToast('Analyse IA en cours sur tous les processus...', 'info')">🤖 Analyse IA complète</button>
                <button class="wfl-qa" onclick="showToast('Relances envoyées aux 17 dossiers en retard', 'success')">📧 Relancer tous les retards</button>
                <button class="wfl-qa" onclick="showToast('Rapport de performance généré', 'info')">📊 Rapport performance DDL</button>
                <button class="wfl-qa" onclick="showToast('Synchronisation Camunda BPMN 2.0', 'info')">🔄 Sync. Camunda BPM</button>
                <button class="wfl-qa" onclick="showToast('Archivage des instances clôturées', 'info')">📦 Archiver les clôturés</button>
            </div>
        </div>

    </div>
</div>

{{-- ════════════════════════════════════════════════
     MODAL — DÉTAIL PROCESSUS
════════════════════════════════════════════════ --}}
<div class="modal" id="modal-wfl-detail">
    <div class="modal-content wfl-modal-wide">
        <div class="modal-header">
            <div class="modal-title" style="display:flex; align-items:center; gap:10px;">
                <span id="md-icon">📄</span>
                <span id="md-title">Transfert de droits d'édition</span>
                <span class="badge green" id="md-status" style="font-size:10px;">Actif</span>
            </div>
            <button class="modal-close" onclick="closeModal('modal-wfl-detail')">✕</button>
        </div>
        <div class="modal-body">

            <div class="wfl-detail-tabs">
                <div class="wfl-dtab active" onclick="wflTab('overview')" id="tab-overview">Vue d'ensemble</div>
                <div class="wfl-dtab" onclick="wflTab('steps')" id="tab-steps">Étapes & Circuit</div>
                <div class="wfl-dtab" onclick="wflTab('docs')" id="tab-docs">Documents requis</div>
                <div class="wfl-dtab" onclick="wflTab('instances')" id="tab-instances">Instances</div>
                <div class="wfl-dtab" onclick="wflTab('ia')" id="tab-ia">🤖 Analyse IA</div>
            </div>

            <div id="tabcontent-overview">
                <div style="display:grid; grid-template-columns: repeat(4,1fr); gap:10px; margin-bottom:16px;">
                    <div style="padding:12px; background:var(--bg3); border-radius:var(--radius-sm); text-align:center;">
                        <div style="font-size:20px; font-weight:900; font-family:var(--font-mono); color:var(--blue);" id="md-instances">34</div>
                        <div style="font-size:10px; color:var(--text3); text-transform:uppercase;">Instances</div>
                    </div>
                    <div style="padding:12px; background:var(--bg3); border-radius:var(--radius-sm); text-align:center;">
                        <div style="font-size:20px; font-weight:900; font-family:var(--font-mono); color:var(--green);" id="md-delai">3 jours</div>
                        <div style="font-size:10px; color:var(--text3); text-transform:uppercase;">Délai indicatif</div>
                    </div>
                    <div style="padding:12px; background:var(--bg3); border-radius:var(--radius-sm); text-align:center;">
                        <div style="font-size:20px; font-weight:900; font-family:var(--font-mono); color:var(--gold);" id="md-etapes">6</div>
                        <div style="font-size:10px; color:var(--text3); text-transform:uppercase;">Étapes</div>
                    </div>
                    <div style="padding:12px; background:var(--bg3); border-radius:var(--radius-sm); text-align:center;">
                        <div style="font-size:20px; font-weight:900; font-family:var(--font-mono); color:var(--green);" id="md-progress">78%</div>
                        <div style="font-size:10px; color:var(--text3); text-transform:uppercase;">Avancement</div>
                    </div>
                </div>
                <div style="margin-bottom:14px;">
                    <div style="font-size:11px; color:var(--text3); font-weight:700; text-transform:uppercase; margin-bottom:6px;">Output du processus</div>
                    <div style="padding:10px 14px; background:var(--green-dim); border:1px solid rgba(74,222,128,0.2); border-radius:var(--radius-sm); font-size:12.5px; color:var(--green); font-weight:600;" id="md-output">Attestation de transfert de droits</div>
                </div>

                <div style="font-size:11px; color:var(--text3); font-weight:700; text-transform:uppercase; margin-bottom:8px;">Circuit BPMN</div>
                <div class="wfl-bpmn-viewer">
                    <div class="wfl-bpmn-flow" id="md-bpmn-flow"></div>
                </div>
            </div>

            <div id="tabcontent-steps" style="display:none;">
                <table class="wfl-steps-table" id="md-steps-table">
                    <thead>
                        <tr><th>#</th><th>Action</th><th>Description</th><th>Intervenant</th><th>Délai</th><th>Statut</th></tr>
                    </thead>
                    <tbody id="md-steps-body"></tbody>
                </table>
            </div>

            <div id="tabcontent-docs" style="display:none;">
                <div style="font-size:12px; color:var(--text2); margin-bottom:12px;">Pièces justificatives requises :</div>
                <div class="wfl-doc-list" id="md-doc-list"></div>
            </div>

            <div id="tabcontent-instances" style="display:none;">
                <div style="display:flex; gap:8px; margin-bottom:14px; flex-wrap:wrap;">
                    <button class="btn btn-outline btn-sm" onclick="showToast('Nouvelle instance créée', 'success')">+ Nouvelle instance</button>
                    <button class="btn btn-outline btn-sm" onclick="showToast('Instances exportées', 'info')">📥 Exporter</button>
                </div>
                <table class="wfl-steps-table">
                    <thead>
                        <tr><th>Référence</th><th>Demandeur</th><th>Étape actuelle</th><th>Depuis</th><th>Statut</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-family:var(--font-mono);">LIV-DRO-001</td><td>Éditions Cérès</td><td>Étape 3 — Validation agent</td><td>J+2</td><td><span class="badge red">En retard</span></td><td><button class="btn btn-ghost btn-sm" onclick="showToast('Dossier ouvert','info')">Voir</button></td></tr>
                        <tr><td style="font-family:var(--font-mono);">LIV-DRO-002</td><td>Sud Éditions</td><td>Étape 5 — Signature dir.</td><td>J+1</td><td><span class="badge green">En cours</span></td><td><button class="btn btn-ghost btn-sm" onclick="showToast('Dossier ouvert','info')">Voir</button></td></tr>
                        <tr><td style="font-family:var(--font-mono);">LIV-DRO-003</td><td>Dar Al-Kitab</td><td>Étape 2 — Inspection docs</td><td>J+0</td><td><span class="badge amber">En attente</span></td><td><button class="btn btn-ghost btn-sm" onclick="showToast('Dossier ouvert','info')">Voir</button></td></tr>
                        <tr><td style="font-family:var(--font-mono);">LIV-DRO-004</td><td>Nirvana Press</td><td>Étape 6 — Délivrance</td><td>J+4</td><td><span class="badge red">En retard</span></td><td><button class="btn btn-ghost btn-sm" onclick="showToast('Relance envoyée','success')">Relancer</button></td></tr>
                    </tbody>
                </table>
            </div>

            <div id="tabcontent-ia" style="display:none;">
                <div class="wfl-ia-modal-panel">
                    <div class="wfl-ia-modal-title">🤖 Analyse IA — Recommandations</div>
                    <div class="wfl-ia-reco" id="md-ia-reco">
                        Ce processus présente un taux d'avancement de <strong>78%</strong> avec <strong>4 dossiers en retard</strong>.
                        L'IA détecte que l'étape d'inspection des documents est le goulot d'étranglement principal.
                        <br><br>
                        <strong>Recommandation :</strong> Mettre en place une checklist numérique pré-remplie pour réduire les allers-retours avec les éditeurs — gain estimé de <strong>2 jours par dossier</strong>.
                    </div>
                    <div class="wfl-ia-actions">
                        <button class="wfl-ia-act-btn purple" onclick="showToast('Analyse prédictive générée', 'info')">📊 Analyse prédictive</button>
                        <button class="wfl-ia-act-btn teal" onclick="showToast('Optimisation de circuit proposée', 'success')">⚡ Optimiser le circuit</button>
                        <button class="wfl-ia-act-btn gold" onclick="showToast('Rapport IA exporté', 'info')">📄 Exporter rapport</button>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns: repeat(3,1fr); gap:10px; margin-top:14px;">
                    <div style="padding:12px; background:var(--bg3); border-radius:var(--radius-sm); text-align:center;">
                        <div style="font-size:18px; font-weight:900; font-family:var(--font-mono); color:var(--purple);">93%</div>
                        <div style="font-size:10px; color:var(--text3); margin-top:3px;">Fiabilité prédictions</div>
                    </div>
                    <div style="padding:12px; background:var(--bg3); border-radius:var(--radius-sm); text-align:center;">
                        <div style="font-size:18px; font-weight:900; font-family:var(--font-mono); color:var(--green);">-2j</div>
                        <div style="font-size:10px; color:var(--text3); margin-top:3px;">Gain potentiel/dossier</div>
                    </div>
                    <div style="padding:12px; background:var(--bg3); border-radius:var(--radius-sm); text-align:center;">
                        <div style="font-size:18px; font-weight:900; font-family:var(--font-mono); color:var(--teal);">J+1</div>
                        <div style="font-size:10px; color:var(--text3); margin-top:3px;">Délai optimal IA</div>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('modal-wfl-detail')">Fermer</button>
            <button class="btn btn-outline" onclick="showToast('Éditeur BPMN ouvert', 'info')">⚙️ Éditer le processus</button>
            <button class="btn btn-gold" onclick="showToast('Nouvelle instance créée!', 'success')">+ Lancer une instance</button>
        </div>
    </div>
</div>

<script>
// Process data for Direction du Livre
const wflData = {
    1: {
        icon:'📄', title:"Transfert de droits d'édition", status:'Actif',
        instances:34, delai:'3 jours', etapes:6, progress:'78%', output:'Attestation de transfert de droits',
        steps:[
            {n:'Action 1',label:'Dépôt demande',desc:'Dépôt de la demande au Bureau d\'ordre',actor:'Bureau d\'ordre DDL',delai:'J0',status:'done'},
            {n:'Action 2',label:'Inspection documents',desc:'Vérification des pièces justificatives',actor:'Chargé de dossier',delai:'J1',status:'done'},
            {n:'Action 3',label:'Validation agent',desc:'Approbation par l\'agent',actor:'Agent DDL',delai:'J1',status:'active'},
            {n:'Action 4',label:'Signature Directeur',desc:'Signature de l\'attestation',actor:'Directeur du Livre',delai:'J2',status:'pending'},
            {n:'Action 5',label:'Enregistrement',desc:'Enregistrement officiel',actor:'Bureau d\'ordre DDL',delai:'J2',status:'pending'},
            {n:'Action 6',label:'Délivrance attestation',desc:'Remise au bénéficiaire',actor:'Chargé de dossier',delai:'J3',status:'pending'},
        ],
        docs:['Demande écrite','Contrat d\'édition','Copie RNE','CNI du gérant','Matricule fiscal'],
        iaReco:'4 dossiers bloqués à l\'étape 3 (Validation agent). <strong>Recommandation :</strong> prioriser ces dossiers et contacter les éditeurs pour documents manquants.',
    },
    2: {
        icon:'🌍', title:"Participation foire internationale", status:'Actif',
        instances:28, delai:'3 jours', etapes:5, progress:'72%', output:'Attestation de participation',
        steps:[
            {n:'Action 1',label:'Dépôt demande',desc:'Dépôt avec formulaire spécifique',actor:'Bureau d\'ordre DDL',delai:'J0',status:'done'},
            {n:'Action 2',label:'Vérification éligibilité',desc:'Vérification des critères d\'éligibilité',actor:'Chargé de dossier',delai:'J1',status:'done'},
            {n:'Action 3',label:'Validation comité',desc:'Approbation par le comité des foires',actor:'Comité DDL',delai:'J2',status:'active'},
            {n:'Action 4',label:'Signature DG',desc:'Signature par le Directeur Général',actor:'Directeur du Livre',delai:'J2',status:'pending'},
            {n:'Action 5',label:'Attestation',desc:'Délivrance de l\'attestation',actor:'Chargé de dossier',delai:'J3',status:'pending'},
        ],
        docs:['Demande de participation','Programme de la foire','Liste des ouvrages présentés','CV de l\'éditeur'],
        iaReco:'3 foires approchent dans les 30 jours. <strong>Recommandation :</strong> accélérer le circuit de validation pour ces dossiers prioritaires.',
    },
    3: {
        icon:'🚚', title:"Couverture frais de transport", status:'Actif',
        instances:19, delai:'3 jours', etapes:5, progress:'65%', output:'Attestation de prise en charge transport',
        steps:[
            {n:'Action 1',label:'Dépôt',desc:'Dépôt de la demande',actor:'Bureau d\'ordre DDL',delai:'J0',status:'done'},
            {n:'Action 2',label:'Vérification devis',desc:'Vérification des devis transporteurs',actor:'Chargé de dossier',delai:'J1',status:'done'},
            {n:'Action 3',label:'Validation logistique',desc:'Validation par le service logistique',actor:'Service logistique',delai:'J2',status:'active'},
            {n:'Action 4',label:'Signature DG',desc:'Signature par le Directeur',actor:'Directeur du Livre',delai:'J2',status:'pending'},
            {n:'Action 5',label:'Prise en charge',desc:'Notification et prise en charge',actor:'Chargé de dossier',delai:'J3',status:'pending'},
        ],
        docs:['Devis transporteur','Liste des colis','Facture pro forma','Attestation d\'assurance'],
        iaReco:'5 demandes en attente de devis transporteur. <strong>Recommandation :</strong> contacter les transporteurs partenaires pour accélération.',
    },
    4: {
        icon:'📋', title:"Exonération TVA matériaux", status:'Actif',
        instances:42, delai:'2 jours', etapes:4, progress:'88%', output:'Attestation d\'exonération TVA',
        steps:[
            {n:'Action 1',label:'Dépôt demande',desc:'Dépôt avec liste des matériaux',actor:'Bureau d\'ordre DDL',delai:'J0',status:'done'},
            {n:'Action 2',label:'Vérification matériaux',desc:'Vérification conformité avec liste exonérée',actor:'Chargé de dossier',delai:'J1',status:'done'},
            {n:'Action 3',label:'Validation agent',desc:'Approbation par l\'agent',actor:'Agent DDL',delai:'J1',status:'active'},
            {n:'Action 4',label:'Attestation exonération',desc:'Délivrance de l\'attestation',actor:'Chargé de dossier',delai:'J2',status:'pending'},
        ],
        docs:['Facture matériaux','Certificat conformité','Liste des matériaux','Devis fournisseur'],
        iaReco:'Processus efficace (88%). <strong>Recommandation :</strong> automatiser la vérification des matériaux via base de données.',
    },
    5: {
        icon:'📚', title:"Dépôt légal des publications", status:'Actif',
        instances:53, delai:'5 jours', etapes:4, progress:'92%', output:'Récépissé de dépôt légal',
        steps:[
            {n:'Action 1',label:'Dépôt',desc:'Dépôt des exemplaires',actor:'Bureau d\'ordre DDL',delai:'J0',status:'done'},
            {n:'Action 2',label:'Enregistrement',desc:'Enregistrement dans le système',actor:'Chargé de dossier',delai:'J1',status:'done'},
            {n:'Action 3',label:'Vérification conformité',desc:'Vérification des obligations légales',actor:'Agent DDL',delai:'J3',status:'active'},
            {n:'Action 4',label:'Délivrance récépissé',desc:'Remise du récépissé',actor:'Bureau d\'ordre DDL',delai:'J5',status:'pending'},
        ],
        docs:['5 exemplaires de l\'ouvrage','Fiche signalétique','Déclaration de dépôt légal'],
        iaReco:'5 dépôts non conformes (étape 3). <strong>Recommandation :</strong> notifier les éditeurs et leur fournir un guide des normes.',
    },
};

window.wflOpenDetail = function(id) {
    const d = wflData[id] || wflData[1];
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

    const flow = document.getElementById('md-bpmn-flow');
    flow.innerHTML = d.steps.map((s, i) => {
        let cls = '';
        if (s.status === 'done')   cls = 'done-node';
        if (s.status === 'active') cls = 'active-node';
        const arrowClass = s.status === 'done' ? 'done' : '';
        const node = `<div class="wfl-bnode">
            <div class="wfl-bnode-box ${cls}" title="${s.label}: ${s.desc}" onclick="showToast('${s.label}: ${s.desc}', 'info')">${s.label}</div>
            <div class="wfl-bnode-label">${s.actor.split('+')[0].trim()}</div>
            <div class="wfl-bnode-time">${s.delai}</div>
        </div>`;
        const arrow = i < d.steps.length - 1 ? `<div class="wfl-barrow ${arrowClass}">›</div>` : '';
        return node + arrow;
    }).join('');

    document.getElementById('md-steps-body').innerHTML = d.steps.map((s, i) => {
        const statusColors = {done:'var(--green)', active:'var(--gold)', pending:'var(--text3)'};
        const statusLabels = {done:'Complété', active:'En cours', pending:'En attente'};
        return `<tr>
            <td style="font-family:var(--font-mono); font-size:11px;">${s.n}</td>
            <td style="font-weight:600; color:var(--text);">${s.label}</td>
            <td>${s.desc}</td>
            <td style="font-size:11px;">${s.actor}</td>
            <td style="font-family:var(--font-mono);">${s.delai}</td>
            <td><span style="display:inline-flex; align-items:center; gap:5px; color:${statusColors[s.status]}">● ${statusLabels[s.status]}</span></td>
        </tr>`;
    }).join('');

    document.getElementById('md-doc-list').innerHTML = d.docs.map(doc =>
        `<div class="wfl-doc-item"><div class="wfl-doc-check" style="background:var(--green-dim); color:var(--green);">✓</div>${doc}</div>`
    ).join('');

    wflTab('overview');
    openModal('modal-wfl-detail');
}

window.wflTab = function(tab) {
    ['overview','steps','docs','instances','ia'].forEach(t => {
        document.getElementById('tabcontent-'+t).style.display = t === tab ? '' : 'none';
        const tabEl = document.getElementById('tab-'+t);
        if (tabEl) tabEl.classList.toggle('active', t === tab);
    });
}

window.wflFilter = function(el, group) {
    document.querySelectorAll('.wfl-ftab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    document.querySelectorAll('#wfl-grid .wfl-card').forEach(card => {
        const show = group === 'all' ||
            (group === 'alerte' && card.dataset.alert === 'alerte') ||
            card.dataset.status === group;
        card.style.display = show ? '' : 'none';
    });
}

window.wflSearch = function(q) {
    document.querySelectorAll('#wfl-grid .wfl-card').forEach(card => {
        card.style.display = card.dataset.title.includes(q.toLowerCase()) ? '' : 'none';
    });
}

function showToast(msg, type) {
    let toast = document.getElementById('wfl-toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'wfl-toast';
        toast.style.cssText = `position:fixed; bottom:30px; left:50%; transform:translateX(-50%); padding:12px 24px; border-radius:8px; color:white; font-size:13px; font-weight:500; z-index:1100; background:${type === 'success' ? '#4ade80' : type === 'warning' ? '#fbbf24' : '#f87171'}; animation:fadeInUp 0.3s ease;`;
        document.body.appendChild(toast);
    }
    toast.textContent = msg;
    toast.style.display = 'block';
    setTimeout(() => toast.style.display = 'none', 3000);
}

function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }
</script>

@endsection
