@extends('shared.layouts.backoffice')

@section('page-title', 'Facilitation transfert droits - Direction du Livre')
@section('breadcrumb', 'Facilitation transfert droits')

@section('content')
<style>
/* ════════════════════════════════════════════
   DROITS TRANSFER — DESIGN SYSTEM
   Enhanced for Document Inspection & Attestation
════════════════════════════════════════════ */

/* ── KPI Row ── */
.dt-kpi-row {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 12px;
    margin-bottom: 22px;
}
@media (max-width: 1100px) { .dt-kpi-row { grid-template-columns: repeat(3,1fr); } }
@media (max-width: 700px)  { .dt-kpi-row { grid-template-columns: repeat(2,1fr); } }

.dt-kpi {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 14px 16px;
    display: flex; align-items: center; gap: 12px;
    transition: border-color 0.2s, transform 0.15s;
    cursor: default;
}
.dt-kpi:hover { border-color: var(--border2); transform: translateY(-1px); }
.dt-kpi-icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; flex-shrink: 0;
}
.dt-kpi-val  { font-size: 22px; font-weight: 900; font-family: var(--font-mono); line-height: 1; }
.dt-kpi-lbl  { font-size: 10.5px; color: var(--text3); text-transform: uppercase; letter-spacing: 0.6px; margin-top: 3px; }
.dt-kpi-delta{ font-size: 10px; font-family: var(--font-mono); font-weight: 700; margin-top: 3px; }

/* ── IA Smart Banner ── */
.dt-ia-banner {
    background: linear-gradient(135deg, rgba(201,168,76,0.08), rgba(167,139,250,0.06));
    border: 1px solid rgba(201,168,76,0.22);
    border-radius: var(--radius);
    padding: 14px 20px;
    display: flex; align-items: center; gap: 16px;
    margin-bottom: 22px;
    position: relative; overflow: hidden;
}
.dt-ia-banner::after {
    content: '📚';
    position: absolute; right: 24px; top: 50%;
    transform: translateY(-50%);
    font-size: 56px; opacity: 0.06; pointer-events: none;
}
.dt-ia-orb {
    width: 44px; height: 44px; border-radius: 12px;
    background: var(--gold-dim); border: 1px solid rgba(201,168,76,0.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
    animation: dt-orb-pulse 3s ease-in-out infinite;
}
@keyframes dt-orb-pulse {
    0%,100% { box-shadow: 0 0 0 0 rgba(201,168,76,0.35); }
    50%      { box-shadow: 0 0 0 10px rgba(201,168,76,0); }
}
.dt-ia-body { flex: 1; }
.dt-ia-title {
    font-size: 13px; font-weight: 700; color: var(--text);
    margin-bottom: 5px; display: flex; align-items: center; gap: 8px;
}
.dt-ia-chips { display: flex; flex-wrap: wrap; gap: 7px; }
.dt-chip {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 11px; border-radius: 20px;
    font-size: 11px; font-weight: 600; cursor: pointer;
    transition: opacity 0.15s;
}
.dt-chip:hover { opacity: 0.8; }

/* ── Main layout ── */
.dt-shell {
    display: grid;
    grid-template-columns: 1fr 310px;
    gap: 18px; align-items: start;
}
@media (max-width: 1060px) { .dt-shell { grid-template-columns: 1fr; } }

/* ── Filter/action bar ── */
.dt-topbar {
    background: var(--bg2); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 12px 16px;
    display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
    margin-bottom: 16px;
}
.dt-filter-tabs { display: flex; gap: 0; flex-wrap: wrap; }
.dt-ftab {
    padding: 6px 14px; font-size: 12px; font-weight: 600;
    color: var(--text3); cursor: pointer; user-select: none;
    border-bottom: 2px solid transparent; transition: all 0.15s; white-space: nowrap;
}
.dt-ftab:hover { color: var(--text2); }
.dt-ftab.active { color: var(--gold); border-bottom-color: var(--gold); }

.dt-search {
    flex: 1; min-width: 180px;
    background: var(--bg3); border: 1px solid var(--border2);
    border-radius: var(--radius-sm); padding: 7px 12px;
    font-size: 12px; color: var(--text); font-family: var(--font-body); outline: none;
}
.dt-search:focus { border-color: var(--gold); }
.dt-search::placeholder { color: var(--text3); }

.dt-select {
    background: var(--bg3); border: 1px solid var(--border2);
    border-radius: var(--radius-sm); padding: 7px 11px;
    font-size: 12px; color: var(--text2); cursor: pointer;
    font-family: var(--font-body); outline: none;
}

/* Report Generator Button */
.btn-report {
    background: linear-gradient(135deg, var(--purple-dim), rgba(167,139,250,0.15));
    border: 1px solid rgba(167,139,250,0.3);
    color: var(--purple);
    padding: 7px 16px;
    border-radius: var(--radius-sm);
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-report:hover {
    background: rgba(167,139,250,0.25);
    transform: translateY(-1px);
    border-color: var(--purple);
}
.icon-spark {
    display: inline-block;
    width: 16px;
    height: 16px;
    background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="%239366ea"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>') no-repeat center;
    background-size: contain;
}

/* ── Card grid view ── */
.dt-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 14px;
    margin-bottom: 18px;
}

/* ── Individual demand card ── */
.dt-card {
    background: var(--bg2); border: 1px solid var(--border);
    border-radius: var(--radius); overflow: hidden;
    cursor: pointer; transition: all 0.18s;
    animation: dt-fadein 0.3s ease forwards;
}
@keyframes dt-fadein { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

.dt-card:hover { border-color: var(--border2); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.15); }

.dt-card-strip {
    height: 4px;
    background: linear-gradient(90deg, var(--gold), var(--gold2));
}
.dt-card-strip.urgent   { background: linear-gradient(90deg, #f87171, #ef4444); }
.dt-card-strip.warning  { background: linear-gradient(90deg, #fbbf24, #f59e0b); }
.dt-card-strip.pending_validation { background: linear-gradient(90deg, #60a5fa, #3b82f6); }
.dt-card-strip.validated { background: linear-gradient(90deg, #4ade80, #22c55e); }

.dt-card-head {
    padding: 14px 16px 10px;
    display: flex; align-items: flex-start; gap: 12px;
    border-bottom: 1px solid var(--border);
}
.dt-card-av {
    width: 42px; height: 42px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; font-weight: 800; flex-shrink: 0;
    background: linear-gradient(135deg, var(--gold), #a07830);
    color: #111;
}
.dt-card-info { flex: 1; min-width: 0; }
.dt-card-name { font-size: 13.5px; font-weight: 700; color: var(--text); }
.dt-card-num  { font-size: 10px; font-family: var(--font-mono); color: var(--text3); margin-top: 2px; }
.dt-card-meta { font-size: 11px; color: var(--text2); margin-top: 3px; display: flex; align-items: center; gap: 5px; flex-wrap: wrap; }
.dt-card-badges { display: flex; flex-direction: column; align-items: flex-end; gap: 4px; flex-shrink: 0; }

.dt-card-body { padding: 12px 16px; }
.dt-card-row  { display: flex; align-items: center; gap: 8px; margin-bottom: 6px; font-size: 12px; color: var(--text2); }
.dt-card-row:last-child { margin-bottom: 0; }
.dt-card-row-icon { font-size: 13px; flex-shrink: 0; width: 18px; text-align: center; }
.dt-card-row-label { color: var(--text3); min-width: 60px; font-size: 11px; }
.dt-card-row-val   { font-weight: 600; color: var(--text); }

/* Document Status Indicators */
.dt-doc-status {
    padding: 0 16px 10px;
    display: flex; flex-wrap: wrap; gap: 5px;
}
.dt-doc-chip {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 9px; border-radius: 20px;
    font-size: 10px; font-weight: 600;
    cursor: pointer;
}
.dt-doc-chip.uploaded { background: var(--green-dim); color: var(--green); }
.dt-doc-chip.missing { background: var(--red-dim); color: var(--red); }
.dt-doc-chip.pending { background: var(--amber-dim); color: var(--amber); }

.dt-card-foot {
    padding: 10px 16px;
    border-top: 1px solid var(--border);
    display: flex; align-items: center; gap: 6px; flex-wrap: wrap;
}
.dt-fbt {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 5px 11px; border-radius: var(--radius-sm);
    font-size: 11px; font-weight: 600; cursor: pointer;
    border: 1px solid var(--border2); background: var(--bg3); color: var(--text2);
    font-family: var(--font-body); transition: all 0.15s; white-space: nowrap;
}
.dt-fbt:hover { background: var(--bg4); color: var(--text); }
.dt-fbt.gold  { background: var(--gold-dim);   border-color: rgba(201,168,76,0.3);   color: var(--gold); }
.dt-fbt.green { background: var(--green-dim);  border-color: rgba(74,222,128,0.25);  color: var(--green); }
.dt-fbt.blue  { background: var(--blue-dim);   border-color: rgba(59,130,246,0.25);   color: var(--blue); }
.dt-fbt.purple{ background: var(--purple-dim); border-color: rgba(167,139,250,0.25); color: var(--purple); }

.dt-ia-score {
    margin-left: auto;
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 20px;
    font-size: 10.5px; font-weight: 700;
    background: var(--purple-dim); color: var(--purple);
    border: 1px solid rgba(167,139,250,0.2);
    cursor: pointer; white-space: nowrap;
}

/* ── TABLE VIEW ── */
#dt-table-view .status-badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 600;
}
.status-pending_validation { background: rgba(59,130,246,0.15); color: #60a5fa; }
.status-pending_agent { background: rgba(251,191,36,0.15); color: #fbbf24; }
.status-pending_director { background: rgba(139,92,246,0.15); color: #a78bfa; }
.status-validated { background: rgba(74,222,128,0.15); color: #4ade80; }
.status-rejected { background: rgba(248,113,113,0.15); color: #f87171; }

/* ════ RIGHT SIDEBAR ════ */
.dt-sidebar { display: flex; flex-direction: column; gap: 16px; position: sticky; top: 76px; }

.dt-sb-panel {
    background: var(--bg2); border: 1px solid var(--border);
    border-radius: var(--radius); overflow: hidden;
}
.dt-sb-head {
    padding: 12px 15px; border-bottom: 1px solid var(--border);
    font-size: 12px; font-weight: 700; color: var(--text);
    display: flex; align-items: center; justify-content: space-between;
}
.dt-sb-body { padding: 12px 15px; }

/* Workflow Stats */
.dt-workflow-stats {
    display: flex; gap: 8px; padding: 12px 14px;
}
.dt-workflow-stat {
    flex: 1; text-align: center; padding: 8px; border-radius: var(--radius-sm);
    background: var(--bg3); border: 1px solid var(--border);
}
.dt-workflow-stat-val { font-size: 18px; font-weight: 900; font-family: var(--font-mono); }
.dt-workflow-stat-lbl { font-size: 9px; color: var(--text3); margin-top: 2px; }

/* Pending items list */
.dt-pending-item {
    display: flex; align-items: center; gap: 10px;
    padding: 9px 14px; border-bottom: 1px solid var(--border);
    cursor: pointer; transition: background 0.15s;
}
.dt-pending-item:hover { background: var(--bg3); }
.dt-pending-av {
    width: 28px; height: 28px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 10px; font-weight: 700; flex-shrink: 0;
}
.dt-pending-info { flex: 1; min-width: 0; }
.dt-pending-name { font-size: 12px; font-weight: 600; color: var(--text); }
.dt-pending-when { font-size: 10.5px; color: var(--text3); }

/* IA suggestions */
.dt-ia-suggestions { display: flex; flex-direction: column; gap: 8px; padding: 10px 14px; }
.dt-sugg-item {
    display: flex; align-items: flex-start; gap: 9px;
    padding: 9px 11px; border-radius: var(--radius-sm);
    background: var(--bg3); border: 1px solid var(--border);
    cursor: pointer; transition: border-color 0.15s;
}
.dt-sugg-item:hover { border-color: var(--gold); }
.dt-sugg-icon { font-size: 15px; flex-shrink: 0; }
.dt-sugg-text { font-size: 11.5px; color: var(--text2); line-height: 1.45; flex: 1; }
.dt-sugg-cta  { font-size: 10px; color: var(--gold); font-weight: 700; margin-top: 3px; }

/* Quick actions */
.dt-quick { display: flex; flex-direction: column; gap: 6px; padding: 10px 14px; }
.dt-qa {
    display: flex; align-items: center; gap: 8px; padding: 8px 11px;
    background: var(--bg3); border: 1px solid var(--border);
    border-radius: var(--radius-sm); font-size: 12px; font-weight: 600;
    color: var(--text2); cursor: pointer; font-family: var(--font-body);
    transition: all 0.15s;
}
.dt-qa:hover { background: var(--bg4); color: var(--text); border-color: var(--border2); }

/* ════ MODAL STYLES ════ */
.dt-modal-wide { max-width: 800px; }

.dt-form-section {
    background: var(--bg3); border: 1px solid var(--border);
    border-radius: var(--radius-sm); padding: 14px 16px; margin-bottom: 14px;
}
.dt-form-section-title {
    font-size: 10.5px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.8px; color: var(--text3); margin-bottom: 12px;
    display: flex; align-items: center; gap: 6px;
}
.dt-2col { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.dt-3col { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }

/* Document Cards */
.dt-doc-card {
    background: var(--bg2); border: 1px solid var(--border);
    border-radius: var(--radius-sm); padding: 12px;
    margin-bottom: 10px;
    display: flex; align-items: center; gap: 12px;
}
.dt-doc-icon {
    width: 40px; height: 40px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
}
.dt-doc-info { flex: 1; }
.dt-doc-name { font-weight: 700; font-size: 12px; margin-bottom: 3px; }
.dt-doc-desc { font-size: 10px; color: var(--text3); }
.dt-doc-actions { display: flex; gap: 8px; flex-shrink: 0; }

/* Attestation Preview */
.dt-attestation-preview {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    border: 1px solid var(--gold);
    border-radius: var(--radius);
    padding: 20px;
    margin-bottom: 16px;
    position: relative;
}
.dt-attestation-header {
    text-align: center;
    border-bottom: 2px solid var(--gold);
    padding-bottom: 12px;
    margin-bottom: 16px;
}
.dt-attestation-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: #92400e; }
.dt-attestation-header p { margin: 4px 0 0; font-size: 11px; color: #b45309; }
.dt-attestation-body { font-size: 12px; line-height: 1.6; color: #78350f; }
.dt-attestation-footer {
    margin-top: 20px;
    padding-top: 12px;
    border-top: 1px dashed var(--gold);
    display: flex;
    justify-content: space-between;
    font-size: 10px;
    color: #92400e;
}

/* IA Analyse Panel */
.dt-ia-analyse-panel {
    background: linear-gradient(135deg, rgba(167,139,250,0.07), rgba(201,168,76,0.05));
    border: 1px solid rgba(167,139,250,0.2); border-radius: var(--radius-sm);
    padding: 14px 16px; margin-bottom: 14px;
}
.dt-ia-analyse-title {
    font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px;
    color: var(--purple); display: flex; align-items: center; gap: 6px; margin-bottom: 10px;
}
.dt-ia-dots {
    display: flex; align-items: center; gap: 3px; margin-left: auto;
}
.dt-ia-dot {
    width: 4px; height: 4px; border-radius: 50%; background: var(--gold);
    animation: dt-think 1.3s ease-in-out infinite;
}
.dt-ia-dot:nth-child(2) { animation-delay: 0.2s; }
.dt-ia-dot:nth-child(3) { animation-delay: 0.4s; }
@keyframes dt-think { 0%,100% { opacity:0.2; transform:scale(0.8); } 50% { opacity:1; transform:scale(1.2); } }

.dt-ia-result-row {
    display: flex; align-items: center; gap: 8px;
    padding: 7px 10px; border-radius: var(--radius-sm);
    margin-bottom: 6px; font-size: 12px;
}
.dt-ia-result-row:last-child { margin-bottom: 0; }
.dt-ia-result-row.ok   { background: var(--green-dim); color: var(--green); }
.dt-ia-result-row.fail { background: var(--red-dim);   color: var(--red); }
.dt-ia-result-row.warn { background: var(--amber-dim); color: var(--amber); }
.dt-ia-result-row.info { background: var(--blue-dim);  color: var(--blue); }

/* Report Modal */
.dt-report-options {
    display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 20px;
}
.dt-report-period {
    flex: 1; text-align: center; padding: 10px; border-radius: var(--radius-sm);
    background: var(--bg3); border: 1px solid var(--border); cursor: pointer;
    transition: all 0.15s;
}
.dt-report-period:hover { border-color: var(--gold); }
.dt-report-period.selected { background: var(--gold-dim); border-color: var(--gold); color: var(--gold); font-weight: 700; }
.dt-report-period-label { font-size: 12px; font-weight: 600; }
.dt-report-period-value { font-size: 10px; color: var(--text3); margin-top: 3px; }

/* Signature Pad Simulation */
.dt-signature-area {
    border: 2px dashed var(--border2);
    border-radius: var(--radius-sm);
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.15s;
}
.dt-signature-area:hover { border-color: var(--gold); }
.dt-signature-preview {
    max-width: 200px;
    margin: 0 auto;
    font-family: 'Brush Script MT', cursive;
    font-size: 24px;
    color: var(--gold);
}

/* Report Generator Button */
.btn-report {
    background: linear-gradient(135deg, var(--purple-dim), rgba(167,139,250,0.15));
    border: 1px solid rgba(167,139,250,0.3);
    color: var(--purple);
}
.icon-spark {
    display: inline-block;
    width: 16px;
    height: 16px;
    background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="%239366ea"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>') no-repeat center;
    background-size: contain;
}
</style>

<div class="page active">
    {{-- ════════ KPI ROW ════════ --}}
    <div class="dt-kpi-row">
        <div class="dt-kpi">
            <div class="dt-kpi-icon" style="background:var(--gold-dim);">📄</div>
            <div>
                <div class="dt-kpi-val" style="color:var(--gold);" id="kpiTotal">0</div>
                <div class="dt-kpi-lbl">Total demandes</div>
                <div class="dt-kpi-delta" style="color:var(--green);">↑ +8% ce mois</div>
            </div>
        </div>
        <div class="dt-kpi">
            <div class="dt-kpi-icon" style="background:var(--blue-dim);">🔍</div>
            <div>
                <div class="dt-kpi-val" style="color:var(--blue);" id="kpiPendingAgent">0</div>
                <div class="dt-kpi-lbl">Agent (inspection)</div>
                <div class="dt-kpi-delta" style="color:var(--amber);">→ Action requise</div>
            </div>
        </div>
        <div class="dt-kpi">
            <div class="dt-kpi-icon" style="background:var(--purple-dim);">✍️</div>
            <div>
                <div class="dt-kpi-val" style="color:var(--purple);" id="kpiPendingDirector">0</div>
                <div class="dt-kpi-lbl">Directeur (signature)</div>
                <div class="dt-kpi-delta" style="color:var(--purple);">→ En attente</div>
            </div>
        </div>
        <div class="dt-kpi">
            <div class="dt-kpi-icon" style="background:var(--green-dim);">✅</div>
            <div>
                <div class="dt-kpi-val" style="color:var(--green);" id="kpiValidated">0</div>
                <div class="dt-kpi-lbl">Attestations émises</div>
                <div class="dt-kpi-delta" style="color:var(--teal);">Taux 68%</div>
            </div>
        </div>
        <div class="dt-kpi">
            <div class="dt-kpi-icon" style="background:var(--purple-dim);">🤖</div>
            <div>
                <div class="dt-kpi-val" style="color:var(--purple);">96%</div>
                <div class="dt-kpi-lbl">Fiabilité IA</div>
                <div class="dt-kpi-delta" style="color:var(--purple);">→ Analyse auto</div>
            </div>
        </div>
    </div>

    {{-- ════════ IA SMART BANNER ════════ --}}
    <div class="dt-ia-banner">
        <div class="dt-ia-orb">🤖</div>
        <div class="dt-ia-body">
            <div class="dt-ia-title">
                IA Dossier Analyser — Assistant intelligent
                <span style="font-size:10px; padding:2px 8px; background:var(--gold-dim); color:var(--gold); border-radius:20px; font-weight:700;">LIVE</span>
            </div>
            <div class="dt-ia-chips" id="iaChipsContainer"></div>
        </div>
        <div style="display:flex; gap:8px; flex-shrink:0;">
            <button class="btn-report" onclick="openReportModal()">
                <span class="icon-spark"></span>
                Générer rapport
            </button>
            <button class="btn btn-outline btn-sm" onclick="showToast('Analyse IA complète lancée', 'info')">📊 Rapport IA</button>
        </div>
    </div>

    {{-- ════════ MAIN SHELL ════════ --}}
    <div class="dt-shell">

        {{-- ══ LEFT: CARDS + TABLE ══ --}}
        <div>

            {{-- Filter/Search bar --}}
            <div class="dt-topbar">
                <div class="dt-filter-tabs">
                    <div class="dt-ftab active" onclick="dtFilter(this,'all')">Toutes (<span id="filterCountAll">0</span>)</div>
                    <div class="dt-ftab" onclick="dtFilter(this,'pending_agent')">🔍 Inspection Agent (<span id="filterCountAgent">0</span>)</div>
                    <div class="dt-ftab" onclick="dtFilter(this,'pending_director')">✍️ Signature Directeur (<span id="filterCountDirector">0</span>)</div>
                    <div class="dt-ftab" onclick="dtFilter(this,'validated')">✅ Validées (<span id="filterCountValidated">0</span>)</div>
                    <div class="dt-ftab" onclick="dtFilter(this,'rejected')">❌ Rejetées (<span id="filterCountRejected">0</span>)</div>
                </div>
                <input type="text" class="dt-search" placeholder="🔍 Rechercher par éditeur, n° dossier..." id="searchInput" oninput="dtSearch(this.value)">
                <select class="dt-select" id="statusFilter" onchange="dtFilterStatus()">
                    <option value="all">Tous les statuts</option>
                    <option value="pending_agent">Inspection Agent</option>
                    <option value="pending_director">Signature Directeur</option>
                    <option value="validated">Validé</option>
                    <option value="rejected">Rejeté</option>
                </select>
                <button class="btn btn-outline btn-sm" onclick="resetFilters()">Reset</button>
                <button class="btn btn-outline btn-sm" onclick="dtToggleView()" id="dt-view-toggle" title="Basculer vue">⊞</button>
            </div>

            {{-- CARD GRID VIEW --}}
            <div class="dt-grid" id="dt-grid"></div>

            {{-- TABLE VIEW --}}
            <div id="dt-table-view" style="display:none;" class="panel">
                <div class="panel-head">
                    <div><div class="panel-title">📋 Liste des demandes</div><div class="panel-sub">Vue tabulaire</div></div>
                    <button class="btn btn-outline btn-sm" onclick="quickActionExport()">📥 Exporter CSV</button>
                </div>
                <div class="panel-body no-pad">
                    <div class="table-wrap">
                        <table class="table">
                            <thead><tr><th>N° Dossier</th><th>Éditeur</th><th>Documents</th><th>Statut</th><th>Date dépôt</th><th>Actions</th></tr></thead>
                            <tbody id="dt-table-body"></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        {{-- ══ RIGHT SIDEBAR ══ --}}
        <div class="dt-sidebar">

            {{-- Workflow Stats --}}
            <div class="dt-sb-panel">
                <div class="dt-sb-head">📊 Workflow validation</div>
                <div class="dt-workflow-stats">
                    <div class="dt-workflow-stat"><div class="dt-workflow-stat-val" id="statAgent">0</div><div class="dt-workflow-stat-lbl">À inspecter</div></div>
                    <div class="dt-workflow-stat"><div class="dt-workflow-stat-val" id="statDirector">0</div><div class="dt-workflow-stat-lbl">À signer</div></div>
                    <div class="dt-workflow-stat"><div class="dt-workflow-stat-val" id="statCompleted">0</div><div class="dt-workflow-stat-lbl">Terminées</div></div>
                </div>
            </div>

            {{-- Pending Actions --}}
            <div class="dt-sb-panel">
                <div class="dt-sb-head">⏳ Actions en attente</div>
                <div id="pendingActionsList"></div>
            </div>

            {{-- IA Suggestions --}}
            <div class="dt-sb-panel">
                <div class="dt-sb-head">🤖 IA Recommendations</div>
                <div class="dt-ia-suggestions" id="iaSuggestionsContainer"></div>
            </div>

            {{-- Quick actions --}}
            <div class="dt-sb-panel">
                <div class="dt-sb-head">⚡ Actions rapides</div>
                <div class="dt-quick">
                    <button class="dt-qa" onclick="openReportModal()"><span class="icon-spark" style="display:inline-block; width:14px; height:14px;"></span> Générer rapport</button>
                    <button class="dt-qa" onclick="quickActionExport()">📥 Exporter toutes</button>
                    <button class="dt-qa" onclick="showToast('Analyse IA complète lancée sur tous les dossiers', 'info')">🤖 Analyse IA complète</button>
                </div>
            </div>

        </div>
    </div>

    {{-- ════════════════════════════════════════════
         MODAL — INSPECTION & VALIDATION (Agent)
    ════════════════════════════════════════════ --}}
    <div class="modal" id="inspectionModal">
        <div class="modal-content dt-modal-wide">
            <div class="modal-header">
                <div class="modal-title">🔍 Inspection des documents — Demande <span id="inspectionNumero"></span></div>
                <button class="modal-close" onclick="closeModal('inspectionModal')">✕</button>
            </div>
            <div class="modal-body">
                <div id="inspectionContent"></div>
            </div>
            <div class="modal-footer" id="inspectionModalActions"></div>
        </div>
    </div>

    {{-- MODAL — ATTESTATION PREVIEW & EDIT (Director Signature) --}}
    <div class="modal" id="attestationModal">
        <div class="modal-content dt-modal-wide">
            <div class="modal-header">
                <div class="modal-title">✍️ Attestation de validation — Signature du Directeur</div>
                <button class="modal-close" onclick="closeModal('attestationModal')">✕</button>
            </div>
            <div class="modal-body">
                <div id="attestationContent"></div>
            </div>
            <div class="modal-footer" id="attestationModalActions"></div>
        </div>
    </div>

    {{-- MODAL — GENERATE REPORT --}}
    <div class="modal" id="reportModal">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <div class="modal-title">📊 Générer rapport personnalisé</div>
                <button class="modal-close" onclick="closeModal('reportModal')">✕</button>
            </div>
            <div class="modal-body">
                <div class="dt-report-options">
                    <div class="dt-report-period" onclick="selectReportPeriod('day')" data-period="day">
                        <div class="dt-report-period-label">Aujourd'hui</div>
                        <div class="dt-report-period-value">Jour en cours</div>
                    </div>
                    <div class="dt-report-period" onclick="selectReportPeriod('week')" data-period="week">
                        <div class="dt-report-period-label">Cette semaine</div>
                        <div class="dt-report-period-value">7 jours</div>
                    </div>
                    <div class="dt-report-period" onclick="selectReportPeriod('month')" data-period="month">
                        <div class="dt-report-period-label">Ce mois</div>
                        <div class="dt-report-period-value">30 jours</div>
                    </div>
                    <div class="dt-report-period" onclick="selectReportPeriod('3months')" data-period="3months">
                        <div class="dt-report-period-label">3 mois</div>
                        <div class="dt-report-period-value">Trimestre</div>
                    </div>
                    <div class="dt-report-period" onclick="selectReportPeriod('6months')" data-period="6months">
                        <div class="dt-report-period-label">6 mois</div>
                        <div class="dt-report-period-value">Semestre</div>
                    </div>
                    <div class="dt-report-period" onclick="selectReportPeriod('year')" data-period="year">
                        <div class="dt-report-period-label">Cette année</div>
                        <div class="dt-report-period-value">365 jours</div>
                    </div>
                </div>
                <div class="form-group" style="margin-top: 16px;">
                    <label class="form-label">Période personnalisée</label>
                    <div class="dt-2col">
                        <input type="date" id="reportDateStart" class="form-input" placeholder="Date début">
                        <input type="date" id="reportDateEnd" class="form-input" placeholder="Date fin">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Statut (optionnel)</label>
                    <select id="reportStatus" class="form-select">
                        <option value="all">Tous</option>
                        <option value="pending_agent">Inspection Agent</option>
                        <option value="pending_director">Signature Directeur</option>
                        <option value="validated">Validées</option>
                        <option value="rejected">Rejetées</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('reportModal')">Annuler</button>
                <button class="btn btn-gold" onclick="generateReport()">📄 Générer PDF</button>
            </div>
        </div>
    </div>

</div>

<script>
// ============================================
// MOCK DATA with Workflow Statuses
// ============================================
let demandes = [
    {
        id: 1, numero: 'LIV-DRO-20260001', nomEditeur: 'Éditions Cérès', matricule: '1234567/A/M/001', registre: 'B123456',
        nomGerant: 'Ahmed Ben Ali', cinGerant: '12345678', email: 'contact@ceres.tn', telephone: '+216 71 123 456',
        adresse: 'Tunis', contratDroits: 'Transfert droits "Livre Tunisien" vers France', dateDepot: '2026-03-10',
        statut: 'validated', healthScore: 92, docCompleteness: 100,
        documents: { contrat: 'uploaded', rne: 'uploaded', demande: 'uploaded' },
        attestationGenerated: true, attestationContent: null, signedBy: 'Dr. Karim Ben Salah', signedAt: '2026-03-15'
    },
    {
        id: 2, numero: 'LIV-DRO-20260002', nomEditeur: 'Sud Éditions', matricule: '2345678/B/M/002', registre: 'B234567',
        nomGerant: 'Sami Mansour', cinGerant: '23456789', email: 'sami@sud-editions.tn', telephone: '+216 71 234 567',
        adresse: 'Sfax', contratDroits: 'Transfert "Poésie Moderne" vers Canada', dateDepot: '2026-03-15',
        statut: 'pending_agent', healthScore: 68, docCompleteness: 85,
        documents: { contrat: 'uploaded', rne: 'pending', demande: 'uploaded' }
    },
    {
        id: 3, numero: 'LIV-DRO-20260003', nomEditeur: 'Nirvana Press', matricule: '3456789/C/M/003', registre: 'B345678',
        nomGerant: 'Leila Trabelsi', cinGerant: '34567890', email: 'leila@nirvana.tn', telephone: '+216 71 345 678',
        adresse: 'La Marsa', contratDroits: 'Droits collection jeunesse vers Belgique', dateDepot: '2026-03-20',
        statut: 'pending_agent', healthScore: 45, docCompleteness: 60,
        documents: { contrat: 'uploaded', rne: 'missing', demande: 'uploaded' }
    },
    {
        id: 4, numero: 'LIV-DRO-20260004', nomEditeur: 'Alif Publishing', matricule: '4567890/D/M/004', registre: 'B456789',
        nomGerant: 'Mohamed Salah', cinGerant: '45678901', email: 'mohamed@alif.tn', telephone: '+216 71 456 789',
        adresse: 'Tunis', contratDroits: 'Transfert "Roman Contemporain" vers Allemagne', dateDepot: '2026-03-25',
        statut: 'pending_director', healthScore: 55, docCompleteness: 95,
        documents: { contrat: 'uploaded', rne: 'uploaded', demande: 'uploaded' },
        agentApproved: true, attestationDraft: null
    },
    {
        id: 5, numero: 'LIV-DRO-20260005', nomEditeur: 'Dar Al-Kitab', matricule: '5678901/E/M/005', registre: 'B567890',
        nomGerant: 'Fatima Ben Hassine', cinGerant: '56789012', email: 'fatima@daralkitab.tn', telephone: '+216 71 567 890',
        adresse: 'Sousse', contratDroits: 'Droits littéraires "Contes du Sahara" vers Maroc', dateDepot: '2026-04-01',
        statut: 'validated', healthScore: 88, docCompleteness: 95,
        documents: { contrat: 'uploaded', rne: 'uploaded', demande: 'uploaded' },
        attestationGenerated: true, signedBy: 'Dr. Karim Ben Salah', signedAt: '2026-04-05'
    },
    {
        id: 6, numero: 'LIV-DRO-20260006', nomEditeur: 'Planeta Ediciones', matricule: '6789012/F/M/006', registre: 'B678901',
        nomGerant: 'Youssef Hamdi', cinGerant: '67890123', email: 'youssef@planeta.tn', telephone: '+216 71 678 901',
        adresse: 'Tunis', contratDroits: 'Transfert droits auteurs "Rêves d\'Espagne" vers Espagne', dateDepot: '2026-04-05',
        statut: 'pending_director', healthScore: 72, docCompleteness: 90,
        documents: { contrat: 'uploaded', rne: 'uploaded', demande: 'uploaded' },
        agentApproved: true, attestationDraft: null
    },
    {
        id: 7, numero: 'LIV-DRO-20260007', nomEditeur: 'MIAM Publishing', matricule: '5678901/E/M/007', registre: 'B567890',
        nomGerant: 'Nadia Ben Salem', cinGerant: '56789012', email: 'nadia@miam.tn', telephone: '+216 71 567 890',
        adresse: 'Sousse', contratDroits: 'Contrat édition internationale Suisse', dateDepot: '2026-03-28',
        statut: 'rejected', healthScore: 35, docCompleteness: 40,
        documents: { contrat: 'missing', rne: 'missing', demande: 'uploaded' },
        motifRejet: 'Documents incomplets (Copie RNE manquante)'
    },
    {
        id: 8, numero: 'LIV-DRO-20260008', nomEditeur: 'Renaissance Books', matricule: '7890123/G/M/008', registre: 'B789012',
        nomGerant: 'Dr. Ibrahim El Khatib', cinGerant: '78901234', email: 'ibrahim@renaissance.tn', telephone: '+216 98 123 456',
        adresse: 'Bizerte', contratDroits: 'Transfert droits académiques vers USA', dateDepot: '2026-04-08',
        statut: 'pending_agent', healthScore: 48, docCompleteness: 55,
        documents: { contrat: 'uploaded', rne: 'pending', demande: 'uploaded' }
    }
];

// ============================================
// HELPER FUNCTIONS
// ============================================
function formatDate(d) { return d ? new Date(d).toLocaleDateString('fr-FR') : '-'; }
function getStatusLabel(statut) {
    const labels = { pending_agent:'Inspection Agent', pending_director:'Signature Directeur', validated:'Validée', rejected:'Rejetée' };
    return labels[statut] || statut;
}
function getStatusClass(statut) {
    const classes = { pending_agent:'status-pending_agent', pending_director:'status-pending_director', validated:'status-validated', rejected:'status-rejected' };
    return classes[statut] || 'status-pending_agent';
}
function getStripClass(statut) {
    if (statut === 'pending_agent') return 'pending_validation';
    if (statut === 'pending_director') return 'warning';
    if (statut === 'validated') return 'validated';
    return 'urgent';
}
function showToast(message, type = 'success') {
    let toast = document.getElementById('dt-toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'dt-toast';
        toast.style.cssText = `position:fixed; bottom:30px; left:50%; transform:translateX(-50%); padding:12px 24px; border-radius:8px; color:white; font-size:13px; font-weight:500; z-index:1100; background:${type === 'success' ? '#4ade80' : type === 'warning' ? '#fbbf24' : '#f87171'}; animation:fadeInUp 0.3s ease;`;
        document.body.appendChild(toast);
        const style = document.createElement('style');
        style.textContent = `@keyframes fadeInUp{from{opacity:0; transform:translate(-50%,20px);}to{opacity:1; transform:translate(-50%,0);}}`;
        document.head.appendChild(style);
    }
    toast.textContent = message;
    toast.style.display = 'block';
    setTimeout(() => toast.style.display = 'none', 3000);
}
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }

// ============================================
// RENDER FUNCTIONS
// ============================================
function renderAll() {
    renderKPIs();
    renderGridAndTable();
    renderSidebar();
    renderIAChips();
    renderIASuggestions();
    updateFilterCounts();
}

function renderKPIs() {
    document.getElementById('kpiTotal').innerText = demandes.length;
    document.getElementById('kpiPendingAgent').innerText = demandes.filter(d => d.statut === 'pending_agent').length;
    document.getElementById('kpiPendingDirector').innerText = demandes.filter(d => d.statut === 'pending_director').length;
    document.getElementById('kpiValidated').innerText = demandes.filter(d => d.statut === 'validated').length;
}

function updateFilterCounts() {
    document.getElementById('filterCountAll').innerText = demandes.length;
    document.getElementById('filterCountAgent').innerText = demandes.filter(d => d.statut === 'pending_agent').length;
    document.getElementById('filterCountDirector').innerText = demandes.filter(d => d.statut === 'pending_director').length;
    document.getElementById('filterCountValidated').innerText = demandes.filter(d => d.statut === 'validated').length;
    document.getElementById('filterCountRejected').innerText = demandes.filter(d => d.statut === 'rejected').length;
}

function getFilteredDemandes() {
    let search = document.getElementById('searchInput').value.toLowerCase();
    let status = document.getElementById('statusFilter').value;
    return demandes.filter(d =>
        (d.nomEditeur.toLowerCase().includes(search) || d.numero.toLowerCase().includes(search)) &&
        (status === 'all' || d.statut === status)
    );
}

function renderGridAndTable() {
    const filtered = getFilteredDemandes();
    const gridContainer = document.getElementById('dt-grid');
    gridContainer.innerHTML = filtered.map(d => {
        const stripClass = getStripClass(d.statut);
        const statusLabel = getStatusLabel(d.statut);
        const statusClass = getStatusClass(d.statut);
        const allDocsUploaded = d.documents.contrat === 'uploaded' && d.documents.rne === 'uploaded' && d.documents.demande === 'uploaded';
        const missingCount = Object.values(d.documents).filter(v => v !== 'uploaded').length;

        return `
            <div class="dt-card" data-id="${d.id}" onclick="openInspectionModal(${d.id})">
                <div class="dt-card-strip ${stripClass}"></div>
                <div class="dt-card-head">
                    <div class="dt-card-av">${d.nomEditeur.charAt(0)}${d.nomEditeur.split(' ').pop()?.charAt(0) || ''}</div>
                    <div class="dt-card-info">
                        <div class="dt-card-name">${d.nomEditeur}</div>
                        <div class="dt-card-num">${d.numero}</div>
                        <div class="dt-card-meta">📅 ${formatDate(d.dateDepot)}</div>
                    </div>
                    <div class="dt-card-badges">
                        <span class="badge ${statusClass}" style="font-size:10px;">${statusLabel}</span>
                    </div>
                </div>
                <div class="dt-card-body">
                    <div class="dt-card-row">
                        <span class="dt-card-row-icon">📊</span>
                        <span class="dt-card-row-label">Score Santé</span>
                        <span class="dt-card-row-val" style="color:${d.healthScore >= 80 ? '#4ade80' : (d.healthScore >= 60 ? '#fbbf24' : '#f87171')}">${d.healthScore}</span>
                    </div>
                </div>
                <div class="dt-doc-status">
                    <span class="dt-doc-chip ${d.documents.contrat}">📄 Contrat ${d.documents.contrat === 'uploaded' ? '✓' : (d.documents.contrat === 'pending' ? '⏳' : '✕')}</span>
                    <span class="dt-doc-chip ${d.documents.rne}">🏢 RNE ${d.documents.rne === 'uploaded' ? '✓' : (d.documents.rne === 'pending' ? '⏳' : '✕')}</span>
                    <span class="dt-doc-chip ${d.documents.demande}">📝 Demande ${d.documents.demande === 'uploaded' ? '✓' : (d.documents.demande === 'pending' ? '⏳' : '✕')}</span>
                </div>
                <div class="dt-card-foot" onclick="event.stopPropagation()">
                    <button class="dt-fbt" onclick="openInspectionModal(${d.id})">👁 Inspecter</button>
                    ${d.statut === 'pending_agent' ? `<button class="dt-fbt green" onclick="validateAsAgent(${d.id})">✓ Approuver docs</button>` : ''}
                    ${d.statut === 'pending_director' ? `<button class="dt-fbt purple" onclick="openAttestationModal(${d.id})">✍️ Signer attestation</button>` : ''}
                    ${d.statut === 'validated' ? `<button class="dt-fbt gold" onclick="downloadAttestation(${d.id})">📄 Télécharger attestation</button>` : ''}
                    <span class="dt-ia-score" onclick="showAIAnalysis(${d.id})">🤖 ${Math.round(d.healthScore * 0.7 + d.docCompleteness * 0.3)}%</span>
                </div>
            </div>
        `;
    }).join('');

    const tableBody = document.getElementById('dt-table-body');
    tableBody.innerHTML = filtered.map(d => `
        <tr onclick="openInspectionModal(${d.id})" style="cursor:pointer;">
            <td><strong>${d.numero}</strong><br><span style="font-size:11px; color:var(--text3);">${d.nomEditeur}</span></td>
            <td>${d.nomEditeur}</td>
            <td><div style="display:flex; gap:4px;">${Object.entries(d.documents).map(([key, val]) => `<span class="dt-doc-chip ${val}" style="font-size:8px;">${key}</span>`).join('')}</div></td>
            <td><span class="status-badge ${getStatusClass(d.statut)}">${getStatusLabel(d.statut)}</span></td>
            <td>${formatDate(d.dateDepot)}</td>
            <td onclick="event.stopPropagation()">
                <button class="btn btn-sm btn-outline" onclick="openInspectionModal(${d.id})">👁</button>
                ${d.statut === 'pending_agent' ? `<button class="btn btn-sm btn-success" onclick="validateAsAgent(${d.id})">✓</button>` : ''}
            </td>
        </tr>
    `).join('');
}

function renderSidebar() {
    const agentCount = demandes.filter(d => d.statut === 'pending_agent').length;
    const directorCount = demandes.filter(d => d.statut === 'pending_director').length;
    const completedCount = demandes.filter(d => d.statut === 'validated').length;
    document.getElementById('statAgent').innerText = agentCount;
    document.getElementById('statDirector').innerText = directorCount;
    document.getElementById('statCompleted').innerText = completedCount;

    const pendingList = document.getElementById('pendingActionsList');
    const pendingItems = [...demandes.filter(d => d.statut === 'pending_agent'), ...demandes.filter(d => d.statut === 'pending_director')].slice(0, 5);
    pendingList.innerHTML = pendingItems.map(d => `
        <div class="dt-pending-item" onclick="openInspectionModal(${d.id})">
            <div class="dt-pending-av" style="background:${d.statut === 'pending_agent' ? 'rgba(59,130,246,0.15)' : 'rgba(139,92,246,0.15)'}; color:${d.statut === 'pending_agent' ? '#60a5fa' : '#a78bfa'}">${d.nomEditeur.charAt(0)}</div>
            <div class="dt-pending-info">
                <div class="dt-pending-name">${d.nomEditeur}</div>
                <div class="dt-pending-when">${d.statut === 'pending_agent' ? '🔍 À inspecter' : '✍️ En attente signature'} · ${d.numero}</div>
            </div>
        </div>
    `).join('');
    if (pendingItems.length === 0) pendingList.innerHTML = '<div style="padding:15px; text-align:center; color:var(--text3);">Aucune action en attente</div>';
}

function renderIAChips() {
    const agentCount = demandes.filter(d => d.statut === 'pending_agent').length;
    const directorCount = demandes.filter(d => d.statut === 'pending_director').length;
    const missingDocsCount = demandes.filter(d => Object.values(d.documents).some(v => v === 'missing')).length;
    const container = document.getElementById('iaChipsContainer');
    container.innerHTML = `
        <div class="dt-chip" style="background:var(--blue-dim); color:var(--blue);" onclick="dtFilter(null,'pending_agent')">
            🔍 ${agentCount} dossiers à inspecter — Documents à vérifier
        </div>
        <div class="dt-chip" style="background:var(--purple-dim); color:var(--purple);" onclick="dtFilter(null,'pending_director')">
            ✍️ ${directorCount} attestations en attente de signature
        </div>
        <div class="dt-chip" style="background:var(--red-dim); color:var(--red);" onclick="showToast('Liste des dossiers avec documents manquants', 'info')">
            📄 ${missingDocsCount} dossiers avec documents manquants
        </div>
    `;
}

function renderIASuggestions() {
    const container = document.getElementById('iaSuggestionsContainer');
    const agentCount = demandes.filter(d => d.statut === 'pending_agent').length;
    const directorCount = demandes.filter(d => d.statut === 'pending_director').length;
    container.innerHTML = `
        <div class="dt-sugg-item" onclick="dtFilter(null,'pending_agent')">
            <div class="dt-sugg-icon">🔍</div>
            <div><div class="dt-sugg-text">${agentCount} dossiers en attente d'inspection documentaire</div><div class="dt-sugg-cta">→ Commencer l'inspection</div></div>
        </div>
        <div class="dt-sugg-item" onclick="dtFilter(null,'pending_director')">
            <div class="dt-sugg-icon">✍️</div>
            <div><div class="dt-sugg-text">${directorCount} attestations prêtes pour signature</div><div class="dt-sugg-cta">→ Signer maintenant</div></div>
        </div>
        <div class="dt-sugg-item" onclick="generateReport()">
            <div class="dt-sugg-icon">📊</div>
            <div><div class="dt-sugg-text">Générer un rapport d'activité personnalisé</div><div class="dt-sugg-cta">→ Créer rapport PDF</div></div>
        </div>
    `;
}

// ============================================
// INSPECTION MODAL (Agent)
// ============================================
function openInspectionModal(id) {
    const d = demandes.find(x => x.id === id);
    if (!d) return;

    document.getElementById('inspectionNumero').innerText = d.numero;
    const docStatus = {
        contrat: { label: 'Copie de contrat conclu avec l\'éditeur', desc: 'Document contractuel signé entre l\'éditeur et le demandeur' },
        rne: { label: 'Copie RNE (Registre National des Entreprises)', desc: 'Extrait RNE à jour (moins de 3 mois)' },
        demande: { label: 'Demande au nom du directeur générale du livre', desc: 'Lettre de demande officielle signée' }
    };

    document.getElementById('inspectionContent').innerHTML = `
        <div class="dt-ia-analyse-panel">
            <div class="dt-ia-analyse-title">🤖 IA — Analyse documentaire</div>
            <div class="dt-ia-result-row ${d.docCompleteness >= 80 ? 'ok' : (d.docCompleteness >= 60 ? 'warn' : 'fail')}">
                ${d.docCompleteness >= 80 ? '✓' : (d.docCompleteness >= 60 ? '⚠️' : '❌')} Complétude globale: ${d.docCompleteness}%
            </div>
            <div class="dt-ia-result-row info">📋 ${Object.values(d.documents).filter(v => v === 'uploaded').length}/3 documents téléchargés</div>
        </div>

        <div class="dt-form-section">
            <div class="dt-form-section-title">📄 Documents à inspecter</div>
            ${Object.entries(docStatus).map(([key, info]) => `
                <div class="dt-doc-card">
                    <div class="dt-doc-icon" style="background:${d.documents[key] === 'uploaded' ? 'var(--green-dim)' : (d.documents[key] === 'pending' ? 'var(--amber-dim)' : 'var(--red-dim)')}">
                        ${key === 'contrat' ? '📄' : (key === 'rne' ? '🏢' : '📝')}
                    </div>
                    <div class="dt-doc-info">
                        <div class="dt-doc-name">${info.label}</div>
                        <div class="dt-doc-desc">${info.desc}</div>
                        <div class="dt-doc-desc" style="margin-top:4px;">
                            Statut: <strong style="color:${d.documents[key] === 'uploaded' ? '#4ade80' : (d.documents[key] === 'pending' ? '#fbbf24' : '#f87171')}">
                                ${d.documents[key] === 'uploaded' ? '✓ Téléchargé' : (d.documents[key] === 'pending' ? '⏳ En attente' : '✕ Manquant')}
                            </strong>
                        </div>
                    </div>
                    <div class="dt-doc-actions">
                        ${d.documents[key] === 'uploaded' ? `<button class="btn btn-sm btn-outline" onclick="showToast('Aperçu du document ${info.label}', 'info')">👁 Aperçu</button>` : ''}
                        <button class="btn btn-sm ${d.documents[key] === 'uploaded' ? 'btn-success' : 'btn-outline'}" onclick="markDocumentStatus(${d.id}, '${key}', 'uploaded')">✓ Valider</button>
                        <button class="btn btn-sm btn-outline" onclick="markDocumentStatus(${d.id}, '${key}', 'missing')">✕ Rejeter</button>
                    </div>
                </div>
            `).join('')}
        </div>

        <div class="dt-form-section">
            <div class="dt-form-section-title">📋 Informations du demandeur</div>
            <div class="dt-2col">
                <div><strong>Éditeur:</strong> ${d.nomEditeur}</div>
                <div><strong>Matricule Fiscal:</strong> ${d.matricule}</div>
                <div><strong>Registre Commerce:</strong> ${d.registre}</div>
                <div><strong>Gérant:</strong> ${d.nomGerant}</div>
                <div><strong>Email:</strong> ${d.email}</div>
                <div><strong>Téléphone:</strong> ${d.telephone}</div>
            </div>
        </div>
    `;

    const allDocsUploaded = d.documents.contrat === 'uploaded' && d.documents.rne === 'uploaded' && d.documents.demande === 'uploaded';
    document.getElementById('inspectionModalActions').innerHTML = `
        <button class="btn btn-outline" onclick="closeModal('inspectionModal')">Fermer</button>
        ${d.statut === 'pending_agent' && allDocsUploaded ? `<button class="btn btn-gold" onclick="validateAsAgent(${d.id})">✅ Approuver tous les documents et générer attestation</button>` : ''}
        ${d.statut === 'pending_agent' && !allDocsUploaded ? `<button class="btn btn-danger" onclick="rejectDemande(${d.id})">❌ Rejeter la demande (documents insuffisants)</button>` : ''}
    `;
    openModal('inspectionModal');
}

function markDocumentStatus(id, docType, status) {
    const d = demandes.find(x => x.id === id);
    if (d && d.documents[docType]) {
        d.documents[docType] = status;
        d.docCompleteness = Math.round(Object.values(d.documents).filter(v => v === 'uploaded').length / 3 * 100);
        renderAll();
        showToast(`Document ${docType} marqué comme ${status === 'uploaded' ? 'validé' : 'manquant'}`, status === 'uploaded' ? 'success' : 'error');
        if (status === 'uploaded') openInspectionModal(id);
    }
}

function validateAsAgent(id) {
    const d = demandes.find(x => x.id === id);
    if (d && d.statut === 'pending_agent') {
        const allUploaded = d.documents.contrat === 'uploaded' && d.documents.rne === 'uploaded' && d.documents.demande === 'uploaded';
        if (!allUploaded) {
            showToast('Tous les documents doivent être validés avant approbation', 'warning');
            return;
        }
        d.statut = 'pending_director';
        d.agentApproved = true;
        d.agentApprovedAt = new Date().toISOString();
        // Generate attestation draft
        d.attestationDraft = generateAttestationContent(d);
        renderAll();
        closeModal('inspectionModal');
        showToast(`Demande ${d.numero} approuvée par l'agent. En attente signature directeur.`, 'success');
    }
}

function rejectDemande(id) {
    const d = demandes.find(x => x.id === id);
    if (d && confirm(`Confirmer le rejet de la demande ${d.numero} ?`)) {
        d.statut = 'rejected';
        d.motifRejet = 'Documents insuffisants ou non conformes après inspection';
        renderAll();
        closeModal('inspectionModal');
        showToast(`Demande ${d.numero} rejetée`, 'error');
    }
}

// ============================================
// ATTESTATION MODAL (Director Signature)
// ============================================
function generateAttestationContent(d) {
    const today = new Date().toLocaleDateString('fr-FR');
    return `
        <div class="dt-attestation-header">
            <h3>RÉPUBLIQUE TUNISIENNE</h3>
            <p>Ministère des Affaires Culturelles<br>Direction Générale du Livre</p>
            <h4 style="margin-top: 10px;">ATTESTATION DE TRANSFERT DE DROITS D'ÉDITION</h4>
        </div>
        <div class="dt-attestation-body">
            <p>Nous soussigné, Directeur Général du Livre, certifions que la demande de facilitation de transfert de droits d'édition déposée par :</p>
            <p><strong>Éditeur :</strong> ${d.nomEditeur}<br>
            <strong>N° Matricule Fiscal :</strong> ${d.matricule}<br>
            <strong>Registre de Commerce :</strong> ${d.registre}<br>
            <strong>Gérant :</strong> ${d.nomGerant}</p>
            <p>a été examinée et jugée conforme aux dispositions du Décret n° 95-1283.</p>
            <p><strong>Objet du transfert :</strong><br>${d.contratDroits || 'Contrat d\'édition internationale'}</p>
            <p>La présente attestation autorise l'éditeur à procéder au transfert des droits d'édition dans le cadre de la coopération internationale.</p>
            <p>Fait à Tunis, le ${today}</p>
        </div>
        <div class="dt-attestation-footer">
            <div>Direction Générale du Livre</div>
            <div>Cachet et signature</div>
        </div>
    `;
}

function openAttestationModal(id) {
    const d = demandes.find(x => x.id === id);
    if (!d || d.statut !== 'pending_director') return;

    const attestationHtml = d.attestationDraft || generateAttestationContent(d);
    document.getElementById('attestationContent').innerHTML = `
        <div class="dt-attestation-preview" id="attestationPreview">
            ${attestationHtml}
        </div>

        <div class="dt-form-section">
            <div class="dt-form-section-title">✏️ Éditer l'attestation (si correction nécessaire)</div>
            <textarea id="attestationEditArea" rows="15" class="form-input" style="font-family: monospace; font-size: 11px;">${attestationHtml.replace(/<[^>]*>/g, '')}</textarea>
            <div class="dt-ia-result-row info" style="margin-top: 8px;">ℹ️ L'IA suggère de vérifier les noms et dates avant signature</div>
        </div>

        <div class="dt-form-section">
            <div class="dt-form-section-title">🖊️ Signature du Directeur</div>
            <div class="dt-signature-area" onclick="simulateSignature()">
                <div id="signaturePreview" class="dt-signature-preview">_________________</div>
                <div style="font-size: 10px; color: var(--text3); margin-top: 8px;">Cliquez pour apposer la signature électronique</div>
            </div>
        </div>
    `;

    document.getElementById('attestationModalActions').innerHTML = `
        <button class="btn btn-outline" onclick="closeModal('attestationModal')">Annuler</button>
        <button class="btn btn-outline" onclick="previewAttestation()">👁 Aperçu</button>
        <button class="btn btn-gold" onclick="signAndSendAttestation(${d.id})">✍️ Signer et envoyer par email</button>
    `;
    openModal('attestationModal');
}

function previewAttestation() {
    const editedContent = document.getElementById('attestationEditArea').value;
    const previewDiv = document.getElementById('attestationPreview');
    if (previewDiv) {
        previewDiv.innerHTML = editedContent.replace(/\n/g, '<br>');
        showToast('Aperçu mis à jour', 'info');
    }
}

function simulateSignature() {
    const signatureDiv = document.getElementById('signaturePreview');
    if (signatureDiv) {
        signatureDiv.innerHTML = '<span style="font-family:\'Brush Script MT\',cursive; font-size:28px;">Dr. Karim Ben Salah</span>';
        signatureDiv.style.borderBottom = '2px solid var(--gold)';
        showToast('Signature électronique apposée', 'success');
    }
}

function signAndSendAttestation(id) {
    const d = demandes.find(x => x.id === id);
    if (d && d.statut === 'pending_director') {
        const editedContent = document.getElementById('attestationEditArea')?.value || d.attestationDraft;
        const signaturePresent = document.getElementById('signaturePreview')?.innerHTML.includes('Dr. Karim');

        if (!signaturePresent) {
            showToast('Veuillez apposer la signature du directeur', 'warning');
            return;
        }

        d.statut = 'validated';
        d.attestationGenerated = true;
        d.attestationContent = editedContent;
        d.signedBy = 'Dr. Karim Ben Salah';
        d.signedAt = new Date().toISOString();

        renderAll();
        closeModal('attestationModal');
        showToast(`Attestation signée et envoyée par email à ${d.email}`, 'success');

        // Simulate email sending
        setTimeout(() => {
            showToast(`📧 Email envoyé à ${d.email} avec l'attestation jointe`, 'success');
        }, 1000);
    }
}

function downloadAttestation(id) {
    const d = demandes.find(x => x.id === id);
    if (d && d.attestationContent) {
        const win = window.open();
        win.document.write(`
            <html>
            <head><title>Attestation_${d.numero}</title>
            <style>body { font-family: Arial, sans-serif; padding: 40px; }</style>
            </head>
            <body>${d.attestationContent}</body>
            </html>
        `);
        win.document.close();
        win.print();
        showToast(`Attestation ${d.numero} téléchargée`, 'success');
    }
}

// ============================================
// REPORT GENERATION
// ============================================
let selectedPeriod = 'month';
function selectReportPeriod(period) {
    selectedPeriod = period;
    document.querySelectorAll('.dt-report-period').forEach(el => el.classList.remove('selected'));
    document.querySelector(`.dt-report-period[data-period="${period}"]`).classList.add('selected');

    const today = new Date();
    const endDate = today.toISOString().split('T')[0];
    let startDate = new Date();

    switch(period) {
        case 'day': startDate = today; break;
        case 'week': startDate.setDate(today.getDate() - 7); break;
        case 'month': startDate.setMonth(today.getMonth() - 1); break;
        case '3months': startDate.setMonth(today.getMonth() - 3); break;
        case '6months': startDate.setMonth(today.getMonth() - 6); break;
        case 'year': startDate.setFullYear(today.getFullYear() - 1); break;
        default: startDate.setMonth(today.getMonth() - 1);
    }

    document.getElementById('reportDateStart').value = startDate.toISOString().split('T')[0];
    document.getElementById('reportDateEnd').value = endDate;
}

function generateReport() {
    const startDate = document.getElementById('reportDateStart').value;
    const endDate = document.getElementById('reportDateEnd').value;
    const statusFilter = document.getElementById('reportStatus').value;

    let filtered = [...demandes];
    if (startDate) filtered = filtered.filter(d => d.dateDepot >= startDate);
    if (endDate) filtered = filtered.filter(d => d.dateDepot <= endDate);
    if (statusFilter !== 'all') filtered = filtered.filter(d => d.statut === statusFilter);

    const reportHtml = `
        <html>
        <head>
            <title>Rapport_Transferts_Droits</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 30px; }
                h1 { color: #c9a84c; border-bottom: 2px solid #c9a84c; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background: #f5f5f5; }
                .footer { margin-top: 30px; font-size: 10px; color: #666; text-align: center; }
                .summary { margin: 20px 0; padding: 15px; background: #f9f9f9; border-radius: 8px; }
            </style>
        </head>
        <body>
            <h1>📊 Rapport de facilitation des transferts de droits d'édition</h1>
            <p>Période: ${formatDate(startDate)} au ${formatDate(endDate)} | Généré le: ${new Date().toLocaleString('fr-FR')}</p>
            <div class="summary">
                <strong>Résumé:</strong><br>
                Total demandes: ${filtered.length}<br>
                En inspection agent: ${filtered.filter(d => d.statut === 'pending_agent').length}<br>
                En attente signature: ${filtered.filter(d => d.statut === 'pending_director').length}<br>
                Validées: ${filtered.filter(d => d.statut === 'validated').length}<br>
                Rejetées: ${filtered.filter(d => d.statut === 'rejected').length}
            </div>
            <table>
                <thead><tr><th>N° Dossier</th><th>Éditeur</th><th>Date dépôt</th><th>Statut</th><th>Score Santé</th></tr></thead>
                <tbody>
                    ${filtered.map(d => `<tr><td>${d.numero}</td><td>${d.nomEditeur}</td><td>${formatDate(d.dateDepot)}</td><td>${getStatusLabel(d.statut)}</td><td>${d.healthScore}</td></tr>`).join('')}
                </tbody>
            </table>
            <div class="footer">Direction Générale du Livre - Rapport généré par IA Assistant</div>
        </body>
        </html>
    `;

    const win = window.open();
    win.document.write(reportHtml);
    win.document.close();
    win.print();
    closeModal('reportModal');
    showToast('Rapport PDF généré avec succès', 'success');
}

function openReportModal() {
    selectReportPeriod('month');
    document.getElementById('reportStatus').value = 'all';
    openModal('reportModal');
}

// ============================================
// FILTERS & ACTIONS
// ============================================
function dtFilter(el, status) {
    if (el) {
        document.querySelectorAll('.dt-ftab').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
    }
    document.getElementById('statusFilter').value = status;
    renderGridAndTable();
}

function dtFilterStatus() {
    document.querySelectorAll('.dt-ftab').forEach(t => t.classList.remove('active'));
    renderGridAndTable();
}

function dtSearch(term) { renderGridAndTable(); }

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = 'all';
    document.querySelectorAll('.dt-ftab').forEach(t => t.classList.remove('active'));
    document.querySelector('.dt-ftab').classList.add('active');
    renderGridAndTable();
}

function dtToggleView() {
    const isGrid = document.getElementById('dt-grid').style.display !== 'none';
    document.getElementById('dt-grid').style.display = isGrid ? 'none' : '';
    document.getElementById('dt-table-view').style.display = isGrid ? '' : 'none';
    document.getElementById('dt-view-toggle').textContent = isGrid ? '⊞' : '☰';
}

function quickActionExport() {
    const data = demandes.map(d => ({ numero: d.numero, editeur: d.nomEditeur, dateDepot: d.dateDepot, statut: getStatusLabel(d.statut) }));
    console.log('Export CSV:', data);
    showToast('Export CSV démarré', 'info');
}

function showAIAnalysis(id) {
    const d = demandes.find(x => x.id === id);
    if (d) showToast(`Analyse IA: Score ${Math.round(d.healthScore * 0.7 + d.docCompleteness * 0.3)}% - ${d.docCompleteness >= 80 ? 'Dossier complet' : (d.docCompleteness >= 60 ? 'Vérification recommandée' : 'Action requise')}`, 'info');
}

// ============================================
// INITIALIZATION
// ============================================
window.dtFilter = dtFilter;
window.dtToggleView = dtToggleView;
window.resetFilters = resetFilters;
window.openInspectionModal = openInspectionModal;
window.validateAsAgent = validateAsAgent;
window.openAttestationModal = openAttestationModal;
window.signAndSendAttestation = signAndSendAttestation;
window.downloadAttestation = downloadAttestation;
window.markDocumentStatus = markDocumentStatus;
window.rejectDemande = rejectDemande;
window.previewAttestation = previewAttestation;
window.simulateSignature = simulateSignature;
window.openReportModal = openReportModal;
window.selectReportPeriod = selectReportPeriod;
window.generateReport = generateReport;
window.showAIAnalysis = showAIAnalysis;
window.closeModal = closeModal;
window.quickActionExport = quickActionExport;

renderAll();
</script>


@endsection
