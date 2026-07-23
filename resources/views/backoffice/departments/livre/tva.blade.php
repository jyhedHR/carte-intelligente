@extends('shared.layouts.backoffice')

@section('page-title', 'Matériaux exonérés TVA - Direction du Livre')
@section('breadcrumb', 'Matériaux exonérés TVA')

@section('content')
<style>
/* ════════════════════════════════════════════
   TVA EXEMPTION — DESIGN SYSTEM
   Aligned with other modules
════════════════════════════════════════════ */

/* ── KPI Row ── */
.tva-kpi-row {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 12px;
    margin-bottom: 22px;
}
@media (max-width: 1100px) { .tva-kpi-row { grid-template-columns: repeat(3,1fr); } }
@media (max-width: 700px)  { .tva-kpi-row { grid-template-columns: repeat(2,1fr); } }

.tva-kpi {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 14px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: border-color 0.2s, transform 0.15s;
    cursor: default;
}
.tva-kpi:hover { border-color: var(--border2); transform: translateY(-1px); }
.tva-kpi-icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}
.tva-kpi-val  { font-size: 22px; font-weight: 900; font-family: var(--font-mono); line-height: 1; }
.tva-kpi-lbl  { font-size: 10.5px; color: var(--text3); text-transform: uppercase; letter-spacing: 0.6px; margin-top: 3px; }
.tva-kpi-delta{ font-size: 10px; font-family: var(--font-mono); font-weight: 700; margin-top: 3px; }

/* ── IA Smart Banner ── */
.tva-ia-banner {
    background: linear-gradient(135deg, rgba(201,168,76,0.08), rgba(167,139,250,0.06));
    border: 1px solid rgba(201,168,76,0.22);
    border-radius: var(--radius);
    padding: 14px 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 22px;
    position: relative;
    overflow: hidden;
}
.tva-ia-banner::after {
    content: '📋';
    position: absolute; right: 24px; top: 50%;
    transform: translateY(-50%);
    font-size: 56px; opacity: 0.06; pointer-events: none;
}
.tva-ia-orb {
    width: 44px; height: 44px; border-radius: 12px;
    background: var(--gold-dim);
    border: 1px solid rgba(201,168,76,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
    animation: tva-orb-pulse 3s ease-in-out infinite;
}
@keyframes tva-orb-pulse {
    0%,100% { box-shadow: 0 0 0 0 rgba(201,168,76,0.35); }
    50%      { box-shadow: 0 0 0 10px rgba(201,168,76,0); }
}
.tva-ia-body { flex: 1; }
.tva-ia-title {
    font-size: 13px; font-weight: 700; color: var(--text);
    margin-bottom: 5px; display: flex;
    align-items: center; gap: 8px;
}
.tva-ia-chips { display: flex; flex-wrap: wrap; gap: 7px; }
.tva-chip {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 11px; border-radius: 20px;
    font-size: 11px; font-weight: 600; cursor: pointer;
    transition: opacity 0.15s;
}
.tva-chip:hover { opacity: 0.8; }

/* ── Main layout ── */
.tva-shell {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 18px;
    align-items: start;
}
@media (max-width: 1060px) { .tva-shell { grid-template-columns: 1fr; } }

/* ── Filter/action bar ── */
.tva-topbar {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 16px;
}
.tva-filter-tabs { display: flex; gap: 0; flex-wrap: wrap; }
.tva-ftab {
    padding: 6px 14px;
    font-size: 12px;
    font-weight: 600;
    color: var(--text3);
    cursor: pointer;
    user-select: none;
    border-bottom: 2px solid transparent;
    transition: all 0.15s;
    white-space: nowrap;
}
.tva-ftab:hover { color: var(--text2); }
.tva-ftab.active { color: var(--gold); border-bottom-color: var(--gold); }

.tva-search {
    flex: 1;
    min-width: 180px;
    background: var(--bg3);
    border: 1px solid var(--border2);
    border-radius: var(--radius-sm);
    padding: 7px 12px;
    font-size: 12px;
    color: var(--text);
    font-family: var(--font-body);
    outline: none;
}
.tva-search:focus { border-color: var(--gold); }
.tva-search::placeholder { color: var(--text3); }

.tva-select {
    background: var(--bg3);
    border: 1px solid var(--border2);
    border-radius: var(--radius-sm);
    padding: 7px 11px;
    font-size: 12px;
    color: var(--text2);
    cursor: pointer;
    font-family: var(--font-body);
    outline: none;
}

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
.tva-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
    gap: 14px;
    margin-bottom: 18px;
}

.tva-card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    cursor: pointer;
    transition: all 0.18s;
    animation: tva-fadein 0.3s ease forwards;
}
@keyframes tva-fadein { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
.tva-card:hover { border-color: var(--border2); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.15); }

.tva-card-strip {
    height: 4px;
    background: linear-gradient(90deg, var(--gold), var(--gold2));
}
.tva-card-strip.urgent { background: linear-gradient(90deg, #f87171, #ef4444); }
.tva-card-strip.warning { background: linear-gradient(90deg, #fbbf24, #f59e0b); }
.tva-card-strip.pending { background: linear-gradient(90deg, #60a5fa, #3b82f6); }
.tva-card-strip.validated { background: linear-gradient(90deg, #4ade80, #22c55e); }
.tva-card-strip.sous_reserve { background: linear-gradient(90deg, #f59e0b, #d97706); }

.tva-card-head {
    padding: 14px 16px 10px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    border-bottom: 1px solid var(--border);
}
.tva-card-av {
    width: 42px; height: 42px; border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 800;
    flex-shrink: 0;
    background: linear-gradient(135deg, var(--gold), #a07830);
    color: #111;
}
.tva-card-info { flex: 1; min-width: 0; }
.tva-card-name { font-size: 13.5px; font-weight: 700; color: var(--text); }
.tva-card-num  { font-size: 10px; font-family: var(--font-mono); color: var(--text3); margin-top: 2px; }
.tva-card-meta { font-size: 11px; color: var(--text2); margin-top: 3px; display: flex; align-items: center; gap: 5px; flex-wrap: wrap; }
.tva-card-badges { display: flex; flex-direction: column; align-items: flex-end; gap: 4px; flex-shrink: 0; }

.tva-card-body { padding: 12px 16px; }
.tva-card-row  { display: flex; align-items: center; gap: 8px; margin-bottom: 6px; font-size: 12px; color: var(--text2); }
.tva-card-row-icon { font-size: 13px; flex-shrink: 0; width: 18px; text-align: center; }
.tva-card-row-label { color: var(--text3); min-width: 60px; font-size: 11px; }
.tva-card-row-val   { font-weight: 600; color: var(--text); }

.tva-deadline-bar {
    padding: 8px 16px 12px;
}
.tva-deadline-row { display: flex; align-items: center; gap: 8px; margin-bottom: 4px; }
.tva-deadline-label { font-size: 10px; color: var(--text3); flex: 1; }
.tva-deadline-days  { font-size: 10.5px; font-family: var(--font-mono); font-weight: 700; }
.tva-deadline-track { height: 4px; background: var(--bg4); border-radius: 2px; overflow: hidden; }
.tva-deadline-fill  { height: 100%; border-radius: 2px; transition: width 0.6s ease; }

.tva-card-foot {
    padding: 10px 16px;
    border-top: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}
.tva-fbt {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 5px 11px;
    border-radius: var(--radius-sm);
    font-size: 11px;
    font-weight: 600;
    cursor: pointer;
    border: 1px solid var(--border2);
    background: var(--bg3);
    color: var(--text2);
    transition: all 0.15s;
    white-space: nowrap;
}
.tva-fbt:hover { background: var(--bg4); color: var(--text); }
.tva-fbt.green { background: var(--green-dim); border-color: rgba(74,222,128,0.25); color: var(--green); }
.tva-fbt.blue  { background: var(--blue-dim);  border-color: rgba(59,130,246,0.25); color: var(--blue); }
.tva-fbt.amber { background: var(--amber-dim); border-color: rgba(251,191,36,0.25); color: var(--amber); }
.tva-fbt.purple{ background: var(--purple-dim); border-color: rgba(167,139,250,0.25); color: var(--purple); }

.tva-ia-score {
    margin-left: auto;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 10.5px;
    font-weight: 700;
    background: var(--purple-dim);
    color: var(--purple);
    border: 1px solid rgba(167,139,250,0.2);
    cursor: pointer;
    white-space: nowrap;
}

/* ── TABLE VIEW ── */
#tva-table-view .status-badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 600;
}
.status-pending { background: rgba(59,130,246,0.15); color: #60a5fa; }
.status-progress { background: rgba(251,191,36,0.15); color: #fbbf24; }
.status-validated { background: rgba(74,222,128,0.15); color: #4ade80; }
.status-rejected { background: rgba(248,113,113,0.15); color: #f87171; }
.status-sous_reserve { background: rgba(245,158,11,0.15); color: #f59e0b; }

/* AI Badge */
.ai-badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 600;
}
.ai-badge.eligible { background: rgba(74,222,128,0.15); color: #4ade80; }
.ai-badge.non-eligible { background: rgba(248,113,113,0.15); color: #f87171; }

/* Reference List */
.tva-reference-section {
    margin-bottom: 22px;
}
.tva-reference-header {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 12px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
}
.tva-reference-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 700;
    font-size: 13px;
}
.tva-reference-list {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-top: none;
    border-radius: 0 0 var(--radius) var(--radius);
    padding: 16px;
    margin-top: -1px;
}
.reference-category {
    margin-bottom: 16px;
}
.category-title {
    font-weight: 700;
    font-size: 12px;
    margin-bottom: 8px;
    color: var(--gold);
}
.category-items {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.reference-tag {
    padding: 4px 12px;
    background: var(--bg3);
    border: 1px solid var(--border);
    border-radius: 20px;
    font-size: 11px;
}

/* ════ RIGHT SIDEBAR ════ */
.tva-sidebar { display: flex; flex-direction: column; gap: 16px; position: sticky; top: 76px; }

.tva-sb-panel {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
}
.tva-sb-head {
    padding: 12px 15px;
    border-bottom: 1px solid var(--border);
    font-size: 12px;
    font-weight: 700;
    color: var(--text);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

/* Exempt Materials Summary */
.tva-materials-summary {
    padding: 12px;
}
.tva-material-stat {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid var(--border);
    font-size: 12px;
}
.tva-material-stat:last-child { border-bottom: none; }

/* Pending items */
.tva-pending-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 14px;
    border-bottom: 1px solid var(--border);
    cursor: pointer;
    transition: background 0.15s;
}
.tva-pending-item:hover { background: var(--bg3); }
.tva-pending-av {
    width: 28px; height: 28px; border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: 700;
    flex-shrink: 0;
}
.tva-pending-info { flex: 1; min-width: 0; }
.tva-pending-name { font-size: 12px; font-weight: 600; color: var(--text); }
.tva-pending-when { font-size: 10.5px; color: var(--text3); }

/* IA suggestions */
.tva-ia-suggestions { display: flex; flex-direction: column; gap: 8px; padding: 10px 14px; }
.tva-sugg-item {
    display: flex;
    align-items: flex-start;
    gap: 9px;
    padding: 9px 11px;
    border-radius: var(--radius-sm);
    background: var(--bg3);
    border: 1px solid var(--border);
    cursor: pointer;
    transition: border-color 0.15s;
}
.tva-sugg-item:hover { border-color: var(--gold); }
.tva-sugg-icon { font-size: 15px; flex-shrink: 0; }
.tva-sugg-text { font-size: 11.5px; color: var(--text2); line-height: 1.45; flex: 1; }
.tva-sugg-cta  { font-size: 10px; color: var(--gold); font-weight: 700; margin-top: 3px; }

/* Quick actions */
.tva-quick { display: flex; flex-direction: column; gap: 6px; padding: 10px 14px; }
.tva-qa {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 11px;
    background: var(--bg3);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    font-size: 12px;
    font-weight: 600;
    color: var(--text2);
    cursor: pointer;
    transition: all 0.15s;
}
.tva-qa:hover { background: var(--bg4); color: var(--text); border-color: var(--border2); }

/* ════ MODAL STYLES ════ */
.tva-modal-wide { max-width: 750px; }
.tva-form-section {
    background: var(--bg3);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 14px 16px;
    margin-bottom: 14px;
}
.tva-form-section-title {
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: var(--text3);
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.tva-2col { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

/* IA Analyse Panel */
.tva-ia-analyse-panel {
    background: linear-gradient(135deg, rgba(167,139,250,0.07), rgba(201,168,76,0.05));
    border: 1px solid rgba(167,139,250,0.2);
    border-radius: var(--radius-sm);
    padding: 14px 16px;
    margin-bottom: 14px;
}
.tva-ia-analyse-title {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: var(--purple);
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 10px;
}
.tva-ia-dots {
    display: flex;
    align-items: center;
    gap: 3px;
    margin-left: auto;
}
.tva-ia-dot {
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: var(--gold);
    animation: tva-think 1.3s ease-in-out infinite;
}
.tva-ia-dot:nth-child(2) { animation-delay: 0.2s; }
.tva-ia-dot:nth-child(3) { animation-delay: 0.4s; }
@keyframes tva-think { 0%,100% { opacity:0.2; transform:scale(0.8); } 50% { opacity:1; transform:scale(1.2); } }

.tva-ia-result-row {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 7px 10px;
    border-radius: var(--radius-sm);
    margin-bottom: 6px;
    font-size: 12px;
}
.tva-ia-result-row.ok   { background: var(--green-dim); color: var(--green); }
.tva-ia-result-row.fail { background: var(--red-dim);   color: var(--red); }
.tva-ia-result-row.warn { background: var(--amber-dim); color: var(--amber); }
.tva-ia-result-row.info { background: var(--blue-dim);  color: var(--blue); }

/* Materials List */
.tva-materials-list {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 12px;
    max-height: 250px;
    overflow-y: auto;
}
.tva-material-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px;
    border-bottom: 1px solid var(--border);
}
.tva-material-item:last-child { border-bottom: none; }
.tva-material-name { flex: 1; font-size: 12px; }
.tva-material-status { font-size: 10px; padding: 2px 8px; border-radius: 12px; }

/* Attestation Preview */
.tva-attestation-preview {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    border: 1px solid var(--gold);
    border-radius: var(--radius);
    padding: 20px;
    margin-bottom: 16px;
    position: relative;
}
.tva-attestation-header {
    text-align: center;
    border-bottom: 2px solid var(--gold);
    padding-bottom: 12px;
    margin-bottom: 16px;
}
.tva-attestation-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: #92400e; }
.tva-attestation-header p { margin: 4px 0 0; font-size: 11px; color: #b45309; }
.tva-attestation-body { font-size: 12px; line-height: 1.6; color: #78350f; }
.tva-attestation-footer {
    margin-top: 20px;
    padding-top: 12px;
    border-top: 1px dashed var(--gold);
    display: flex;
    justify-content: space-between;
    font-size: 10px;
    color: #92400e;
}

/* Signature Area */
.tva-signature-area {
    border: 2px dashed var(--border2);
    border-radius: var(--radius-sm);
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.15s;
}
.tva-signature-area:hover { border-color: var(--gold); }
.tva-signature-preview {
    max-width: 200px;
    margin: 0 auto;
    font-family: 'Brush Script MT', cursive;
    font-size: 24px;
    color: var(--gold);
}

/* AI Suggestion Panel inside modal */
.tva-ai-suggestion-panel {
    background: linear-gradient(135deg, rgba(167,139,250,0.07), rgba(201,168,76,0.05));
    border: 1px solid rgba(167,139,250,0.2);
    border-radius: var(--radius-sm);
    padding: 14px 16px;
    margin-bottom: 14px;
}
.tva-suggestion-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
}
.tva-suggestion-icon { font-size: 18px; }

/* AI Insight Grid */
.ai-insight-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 22px;
}
.ai-insight-grid .insight-card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 16px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
}
.ai-insight-grid .insight-card:hover {
    border-color: var(--gold);
    transform: translateY(-2px);
}
.ai-insight-grid .insight-value {
    font-size: 28px;
    font-weight: 900;
    font-family: var(--font-mono);
    color: var(--gold);
}
.ai-insight-grid .insight-label {
    font-size: 11px;
    color: var(--text3);
    margin-top: 5px;
}

/* Additional Filters */
.tva-filters-row {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 16px;
}
</style>

<div class="page active">
    {{-- ════════ KPI ROW ════════ --}}
    <div class="tva-kpi-row">
        <div class="tva-kpi">
            <div class="tva-kpi-icon" style="background:var(--gold-dim);">📋</div>
            <div>
                <div class="tva-kpi-val" style="color:var(--gold);" id="kpiTotal">0</div>
                <div class="tva-kpi-lbl">Total demandes</div>
                <div class="tva-kpi-delta" style="color:var(--green);">↑ +18% ce mois</div>
            </div>
        </div>
        <div class="tva-kpi">
            <div class="tva-kpi-icon" style="background:var(--blue-dim);">🔍</div>
            <div>
                <div class="tva-kpi-val" style="color:var(--blue);" id="kpiPending">0</div>
                <div class="tva-kpi-lbl">À inspecter</div>
                <div class="tva-kpi-delta" style="color:var(--amber);">Délai 3j max</div>
            </div>
        </div>
        <div class="tva-kpi">
            <div class="tva-kpi-icon" style="background:var(--amber-dim);">📋</div>
            <div>
                <div class="tva-kpi-val" style="color:var(--amber);" id="kpiSousReserve">0</div>
                <div class="tva-kpi-lbl">Sous-réserve</div>
                <div class="tva-kpi-delta" style="color:var(--amber);">→ Action requise</div>
            </div>
        </div>
        <div class="tva-kpi">
            <div class="tva-kpi-icon" style="background:var(--green-dim);">✅</div>
            <div>
                <div class="tva-kpi-val" style="color:var(--green);" id="kpiValidated">0</div>
                <div class="tva-kpi-lbl">Validées</div>
                <div class="tva-kpi-delta" style="color:var(--teal);">Taux 78%</div>
            </div>
        </div>
        <div class="tva-kpi">
            <div class="tva-kpi-icon" style="background:var(--purple-dim);">🤖</div>
            <div>
                <div class="tva-kpi-val" style="color:var(--purple);">96%</div>
                <div class="tva-kpi-lbl">Fiabilité IA</div>
                <div class="tva-kpi-delta" style="color:var(--purple);">→ Classification</div>
            </div>
        </div>
    </div>

    {{-- ════════ AI TVA INTELLIGENCE PANEL ════════ --}}
    <div class="tva-ia-banner">
        <div class="tva-ia-orb">🤖</div>
        <div class="tva-ia-body">
            <div class="tva-ia-title">
                AI TVA Intelligence — Classification intelligente des matériaux
                <span style="font-size:10px; padding:2px 8px; background:var(--gold-dim); color:var(--gold); border-radius:20px; font-weight:700;">LIVE</span>
            </div>
            <div class="tva-ia-chips" id="iaChipsContainer"></div>
        </div>
        <div style="display:flex; gap:8px; flex-shrink:0;">
            <button class="btn-report" onclick="openReportModal()">
                <span class="icon-spark"></span>
                Générer rapport
            </button>
            <button class="btn btn-outline btn-sm" onclick="toggleReferenceList()">📋 Liste exonérée</button>
        </div>
    </div>

    {{-- ════════ AI INSIGHT GRID ════════ --}}
    <div class="ai-insight-grid" id="aiInsightGrid"></div>

    {{-- TVA Reference List (Collapsible) --}}
    <div class="tva-reference-section">
        <div class="tva-reference-header" onclick="toggleReferenceList()">
            <div class="tva-reference-title">
                <span>📋</span>
                <span>Liste des matériaux exonérés de TVA</span>
                <span class="tva-badge" style="font-size:10px; padding:2px 8px; background:var(--gold-dim); border-radius:20px;">Mise à jour: Avril 2026</span>
            </div>
            <span>▼</span>
        </div>
        <div class="tva-reference-list" id="tvaReferenceList" style="display: none;">
            <div class="reference-category">
                <div class="category-title">📄 Papiers</div>
                <div class="category-items">
                    <span class="reference-tag">Papier offset 80g</span>
                    <span class="reference-tag">Papier recyclé</span>
                    <span class="reference-tag">Papier couché</span>
                    <span class="reference-tag">Papier journal</span>
                    <span class="reference-tag">Papier kraft</span>
                    <span class="reference-tag">Papier sans bois</span>
                </div>
            </div>
            <div class="reference-category">
                <div class="category-title">🖨️ Encres</div>
                <div class="category-items">
                    <span class="reference-tag">Encre végétale</span>
                    <span class="reference-tag">Encre écologique</span>
                    <span class="reference-tag">Encre à base d'eau</span>
                    <span class="reference-tag">Encre UV</span>
                    <span class="reference-tag">Encre soja</span>
                </div>
            </div>
            <div class="reference-category">
                <div class="category-title">📦 Cartonnage</div>
                <div class="category-items">
                    <span class="reference-tag">Carton de couverture</span>
                    <span class="reference-tag">Carton recyclé</span>
                    <span class="reference-tag">Carton compact</span>
                    <span class="reference-tag">Carton gris</span>
                </div>
            </div>
            <div class="reference-category">
                <div class="category-title">🔧 Autres</div>
                <div class="category-items">
                    <span class="reference-tag">Colle sans solvant</span>
                    <span class="reference-tag">Fil de reliure écologique</span>
                    <span class="reference-tag">Pelliculage biodégradable</span>
                    <span class="reference-tag">Ruban adhésif papier</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ════════ MAIN SHELL ════════ --}}
    <div class="tva-shell">

        {{-- ══ LEFT: CARDS + TABLE ══ --}}
        <div>

            {{-- Filter/Search bar --}}
            <div class="tva-topbar">
                <div class="tva-filter-tabs">
                    <div class="tva-ftab active" onclick="tvaFilter(this,'all')">Toutes (<span id="filterCountAll">0</span>)</div>
                    <div class="tva-ftab" onclick="tvaFilter(this,'pending')">🔍 À inspecter (<span id="filterCountPending">0</span>)</div>
                    <div class="tva-ftab" onclick="tvaFilter(this,'sous_reserve')">📋 Sous-réserve (<span id="filterCountSousReserve">0</span>)</div>
                    <div class="tva-ftab" onclick="tvaFilter(this,'validated')">✅ Validées (<span id="filterCountValidated">0</span>)</div>
                    <div class="tva-ftab" onclick="tvaFilter(this,'rejected')">❌ Rejetées (<span id="filterCountRejected">0</span>)</div>
                </div>
                <input type="text" class="tva-search" placeholder="🔍 Rechercher par éditeur, matériau..." id="searchInput" oninput="tvaSearch(this.value)">
                <select class="tva-select" id="statusFilter" onchange="tvaFilterStatus()">
                    <option value="all">Tous les statuts</option>
                    <option value="pending">À inspecter</option>
                    <option value="sous_reserve">Sous-réserve</option>
                    <option value="validated">Validé</option>
                    <option value="rejected">Rejeté</option>
                </select>
                <button class="btn btn-outline btn-sm" onclick="resetFilters()">Reset</button>
                <button class="btn btn-outline btn-sm" onclick="tvaToggleView()" id="tva-view-toggle" title="Basculer vue">⊞</button>
            </div>

            {{-- Additional Filters --}}
            <div class="tva-filters-row">
                <select id="aiFilter" class="tva-select" onchange="applyAdditionalFilters()">
                    <option value="all">Toutes suggestions IA</option>
                    <option value="eligible">Suggestions éligibles</option>
                    <option value="non_eligible">Suggestions non éligibles</option>
                    <option value="pending_review">En attente validation</option>
                </select>
            </div>

            {{-- CARD GRID VIEW --}}
            <div class="tva-grid" id="tva-grid"></div>

            {{-- TABLE VIEW --}}
            <div id="tva-table-view" style="display:none;" class="panel">
                <div class="panel-head">
                    <div><div class="panel-title">📋 Liste des demandes d'exonération TVA</div><div class="panel-sub">Vue tabulaire</div></div>
                    <button class="btn btn-outline btn-sm" onclick="quickActionExport()">📥 Exporter CSV</button>
                </div>
                <div class="panel-body no-pad">
                    <div class="table-wrap">
                        <table class="table">
                            <thead>
                                <tr><th>N° Dossier</th><th>Éditeur</th><th>Matériau</th><th>Suggestion IA</th><th>Confiance</th><th>Délai restant</th><th>Statut</th><th>Actions</th></tr>
                            </thead>
                            <tbody id="tva-table-body"></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        {{-- ══ RIGHT SIDEBAR ══ --}}
        <div class="tva-sidebar">

            {{-- Résumé des matériaux exonérés --}}
            <div class="tva-sb-panel">
                <div class="tva-sb-head">📊 Matériaux exonérés</div>
                <div class="tva-materials-summary" id="materialsSummary"></div>
            </div>

            {{-- Actions urgentes (délai 3j) --}}
            <div class="tva-sb-panel">
                <div class="tva-sb-head">⏰ Actions urgentes (délai 3j)</div>
                <div id="pendingActionsList"></div>
            </div>

            {{-- IA Suggestions --}}
            <div class="tva-sb-panel">
                <div class="tva-sb-head">🤖 IA Recommendations</div>
                <div class="tva-ia-suggestions" id="iaSuggestionsContainer"></div>
            </div>

            {{-- Quick actions --}}
            <div class="tva-sb-panel">
                <div class="tva-sb-head">⚡ Actions rapides</div>
                <div class="tva-quick">
                    <button class="tva-qa" onclick="openReportModal()"><span class="icon-spark" style="display:inline-block; width:14px; height:14px;"></span> Générer rapport</button>
                    <button class="tva-qa" onclick="quickActionExport()">📥 Exporter toutes</button>
                    <button class="tva-qa" onclick="toggleReferenceList()">📋 Voir liste exonérée</button>
                </div>
            </div>

        </div>
    </div>

    {{-- ════════════════════════════════════════════
         MODAL — INSPECTION & VALIDATION (Agent)
    ════════════════════════════════════════════ --}}
    <div class="modal" id="inspectionModal">
        <div class="modal-content tva-modal-wide">
            <div class="modal-header">
                <div class="modal-title">🔍 Inspection de la demande — <span id="inspectionNumero"></span></div>
                <button class="modal-close" onclick="closeModal('inspectionModal')">✕</button>
            </div>
            <div class="modal-body" id="inspectionContent"></div>
            <div class="modal-footer" id="inspectionModalActions"></div>
        </div>
    </div>

    {{-- MODAL — SOUS-RÉSERVE --}}
    <div class="modal" id="sousReserveModal">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <div class="modal-title">📋 Approbation sous-réserve</div>
                <button class="modal-close" onclick="closeModal('sousReserveModal')">✕</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Conditions à remplir *</label>
                    <textarea class="form-input" id="sousReserveConditions" rows="4" placeholder="Listez les conditions à remplir pour validation finale..."></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Date limite</label>
                    <input type="date" class="form-input" id="sousReserveDeadline">
                </div>
                <div class="form-group">
                    <label class="form-label">Notes complémentaires</label>
                    <textarea class="form-input" id="sousReserveNotes" rows="2" placeholder="Notes internes..."></textarea>
                </div>
                <div class="tva-ia-result-row info">ℹ️ L'IA suivra ce dossier et enverra des rappels à la date limite</div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('sousReserveModal')">Annuler</button>
                <button class="btn btn-amber" onclick="confirmSousReserve()">Confirmer sous-réserve</button>
            </div>
        </div>
    </div>

    {{-- MODAL — ATTESTATION (Director Signature) --}}
    <div class="modal" id="attestationModal">
        <div class="modal-content tva-modal-wide">
            <div class="modal-header">
                <div class="modal-title">✍️ Attestation d'exonération TVA — Signature du Directeur</div>
                <button class="modal-close" onclick="closeModal('attestationModal')">✕</button>
            </div>
            <div class="modal-body" id="attestationContent"></div>
            <div class="modal-footer" id="attestationModalActions"></div>
        </div>
    </div>

    {{-- MODAL — REJET --}}
    <div class="modal" id="rejectModal">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <div class="modal-title">❌ Rejeter la demande</div>
                <button class="modal-close" onclick="closeModal('rejectModal')">✕</button>
            </div>
            <div class="modal-body">
                <div class="tva-rejection-templates" id="rejectionTemplates"></div>
                <div class="form-group" style="margin-top: 16px;">
                    <label class="form-label">Ou saisir un motif personnalisé</label>
                    <textarea class="form-input" id="rejectionReason" rows="3" placeholder="Motif du rejet..."></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Notes internes (optionnel)</label>
                    <textarea class="form-input" id="rejectionNotes" rows="2" placeholder="Notes pour l'historique..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('rejectModal')">Annuler</button>
                <button class="btn btn-red" onclick="confirmReject()">Confirmer le rejet</button>
            </div>
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
                <div class="tva-report-options" style="display:flex; gap:12px; flex-wrap:wrap; margin-bottom:20px;">
                    <div class="tva-report-period" onclick="selectReportPeriod('day')" data-period="day" style="flex:1; text-align:center; padding:10px; background:var(--bg3); border-radius:var(--radius-sm); cursor:pointer;">Aujourd'hui</div>
                    <div class="tva-report-period" onclick="selectReportPeriod('week')" data-period="week" style="flex:1; text-align:center; padding:10px; background:var(--bg3); border-radius:var(--radius-sm); cursor:pointer;">Cette semaine</div>
                    <div class="tva-report-period" onclick="selectReportPeriod('month')" data-period="month" style="flex:1; text-align:center; padding:10px; background:var(--bg3); border-radius:var(--radius-sm); cursor:pointer;">Ce mois</div>
                    <div class="tva-report-period" onclick="selectReportPeriod('3months')" data-period="3months" style="flex:1; text-align:center; padding:10px; background:var(--bg3); border-radius:var(--radius-sm); cursor:pointer;">3 mois</div>
                    <div class="tva-report-period" onclick="selectReportPeriod('6months')" data-period="6months" style="flex:1; text-align:center; padding:10px; background:var(--bg3); border-radius:var(--radius-sm); cursor:pointer;">6 mois</div>
                    <div class="tva-report-period" onclick="selectReportPeriod('year')" data-period="year" style="flex:1; text-align:center; padding:10px; background:var(--bg3); border-radius:var(--radius-sm); cursor:pointer;">Cette année</div>
                </div>
                <div class="form-group" style="margin-top: 16px;">
                    <label class="form-label">Période personnalisée</label>
                    <div class="tva-2col">
                        <input type="date" id="reportDateStart" class="form-input">
                        <input type="date" id="reportDateEnd" class="form-input">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Statut</label>
                    <select id="reportStatus" class="form-select">
                        <option value="all">Tous</option>
                        <option value="pending">À inspecter</option>
                        <option value="sous_reserve">Sous-réserve</option>
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
// MOCK DATA - TVA EXEMPTION REQUESTS
// ============================================
let demandes = [
    { id:1, numero:'LIV-TVA-20260001', nomEditeur:'Imprimerie Tunisienne', matricule:'1234567/A/M/001', materiau:'Papier offset 80g', quantite:'5000 ramettes', justificatif:'Facture papier', dateDepot:'2026-03-10', statut:'validated', aiSuggestion:'eligible', aiConfidence:98, aiReviewed:true, humanDecision:'approve', attestationGenerated:true },
    { id:2, numero:'LIV-TVA-20260002', nomEditeur:'Sud Impression', matricule:'2345678/B/M/002', materiau:'Encre végétale', quantite:'200 litres', justificatif:'Certificat origine', dateDepot:'2026-03-15', statut:'sous_reserve', aiSuggestion:'eligible', aiConfidence:94, aiReviewed:false, humanDecision:null, sousReserveConditions:'Attestation fournisseur requise', sousReserveDeadline:'2026-05-15' },
    { id:3, numero:'LIV-TVA-20260003', nomEditeur:'Nirvana Print', matricule:'3456789/C/M/003', materiau:'Carton de couverture', quantite:'3000 feuilles', justificatif:'Devis fournisseur', dateDepot:'2026-03-20', statut:'pending', aiSuggestion:'eligible', aiConfidence:91, aiReviewed:false, humanDecision:null },
    { id:4, numero:'LIV-TVA-20260004', nomEditeur:'Dar Al-Kitab', matricule:'5678901/E/M/004', materiau:'Papier recyclé', quantite:'10000 ramettes', justificatif:'Certificat écologique', dateDepot:'2026-04-01', statut:'validated', aiSuggestion:'eligible', aiConfidence:99, aiReviewed:true, humanDecision:'approve', attestationGenerated:true },
    { id:5, numero:'LIV-TVA-20260005', nomEditeur:'Alif Publishing', matricule:'4567890/D/M/005', materiau:'Colle sans solvant', quantite:'500 litres', justificatif:'Fiche technique', dateDepot:'2026-03-28', statut:'pending', aiSuggestion:'eligible', aiConfidence:87, aiReviewed:false, humanDecision:null },
    { id:6, numero:'LIV-TVA-20260006', nomEditeur:'Visions Créatives', matricule:'9012345/I/M/006', materiau:'Plastique PVC', quantite:'1000 feuilles', justificatif:'Facture fournisseur', dateDepot:'2026-04-05', statut:'rejected', aiSuggestion:'non_eligible', aiConfidence:96, aiReviewed:true, humanDecision:'reject', motifRejet:'Matériau non éligible' },
    { id:7, numero:'LIV-TVA-20260007', nomEditeur:'Planeta Ediciones', matricule:'6789012/F/M/007', materiau:'Papier offset 100g', quantite:'2000 ramettes', justificatif:'Facture', dateDepot:'2026-04-08', statut:'pending', aiSuggestion:'eligible', aiConfidence:96, aiReviewed:false, humanDecision:null }
];

// Exempt materials database
const exemptMaterials = [
    'Papier offset', 'Papier recyclé', 'Papier couché', 'Papier journal', 'Papier kraft', 'Papier sans bois',
    'Encre végétale', 'Encre écologique', 'Encre à base d\'eau', 'Encre UV', 'Encre soja',
    'Carton de couverture', 'Carton recyclé', 'Carton compact', 'Carton gris',
    'Colle sans solvant', 'Fil de reliure écologique', 'Pelliculage biodégradable', 'Ruban adhésif papier'
];

let tvaIsGrid = true;
let currentRejectId = null;
let currentSousReserveId = null;
let currentAttestationId = null;
let selectedReportPeriod = 'month';

// ============================================
// HELPER FUNCTIONS
// ============================================
function formatDate(d) { return d ? new Date(d).toLocaleDateString('fr-FR') : '-'; }
function getStatusLabel(statut) {
    const labels = { pending:'À inspecter', progress:'En cours', validated:'Validé', rejected:'Rejeté', sous_reserve:'Sous-réserve' };
    return labels[statut] || statut;
}
function getStatusClass(statut) {
    const classes = { pending:'status-pending', progress:'status-progress', validated:'status-validated', rejected:'status-rejected', sous_reserve:'status-sous_reserve' };
    return classes[statut] || 'status-pending';
}
function getStripClass(statut) {
    if (statut === 'pending') return 'pending';
    if (statut === 'sous_reserve') return 'sous_reserve';
    if (statut === 'progress') return 'warning';
    if (statut === 'validated') return 'validated';
    return 'urgent';
}
function calculateDeadlineDays(dateDepot) {
    const deposit = new Date(dateDepot);
    const today = new Date();
    const diffDays = Math.ceil((deposit - today) / (1000 * 60 * 60 * 24));
    return 3 - (3 - diffDays);
}
function showToast(message, type = 'success') {
    let toast = document.getElementById('tva-toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'tva-toast';
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
// AI MATERIAL ANALYSIS
// ============================================
function analyzeMaterialWithAI(materialName, callback) {
    const isExempt = exemptMaterials.some(m => materialName.toLowerCase().includes(m.toLowerCase()));
    const confidence = Math.floor(Math.random() * 15) + 85;
    if (callback) callback({ eligible: isExempt, confidence: confidence, material: materialName });
    return { eligible: isExempt, confidence: confidence };
}

// ============================================
// RENDER FUNCTIONS
// ============================================
function renderAll() {
    renderKPIs();
    renderGridAndTable();
    renderSidebar();
    renderIAChips();
    renderIASuggestions();
    renderAIInsights();
    renderMaterialsSummary();
    updateFilterCounts();
}

function renderKPIs() {
    document.getElementById('kpiTotal').innerText = demandes.length;
    document.getElementById('kpiPending').innerText = demandes.filter(d => d.statut === 'pending').length;
    document.getElementById('kpiSousReserve').innerText = demandes.filter(d => d.statut === 'sous_reserve').length;
    document.getElementById('kpiValidated').innerText = demandes.filter(d => d.statut === 'validated').length;
}

function updateFilterCounts() {
    document.getElementById('filterCountAll').innerText = demandes.length;
    document.getElementById('filterCountPending').innerText = demandes.filter(d => d.statut === 'pending').length;
    document.getElementById('filterCountSousReserve').innerText = demandes.filter(d => d.statut === 'sous_reserve').length;
    document.getElementById('filterCountValidated').innerText = demandes.filter(d => d.statut === 'validated').length;
    document.getElementById('filterCountRejected').innerText = demandes.filter(d => d.statut === 'rejected').length;
}

function getFilteredDemandes() {
    let search = document.getElementById('searchInput').value.toLowerCase();
    let status = document.getElementById('statusFilter').value;
    let aiFilter = document.getElementById('aiFilter')?.value || 'all';

    return demandes.filter(d => {
        let match = true;
        if (search && !d.nomEditeur.toLowerCase().includes(search) && !d.numero.toLowerCase().includes(search) && !d.materiau.toLowerCase().includes(search)) match = false;
        if (status !== 'all' && d.statut !== status) match = false;
        if (aiFilter !== 'all') {
            if (aiFilter === 'eligible' && d.aiSuggestion !== 'eligible') match = false;
            if (aiFilter === 'non_eligible' && d.aiSuggestion !== 'non_eligible') match = false;
            if (aiFilter === 'pending_review' && d.aiReviewed !== false) match = false;
        }
        return match;
    });
}

function applyAdditionalFilters() {
    renderGridAndTable();
}

function renderGridAndTable() {
    const filtered = getFilteredDemandes();

    // Render Grid
    const gridContainer = document.getElementById('tva-grid');
    gridContainer.innerHTML = filtered.map(d => {
        const stripClass = getStripClass(d.statut);
        const statusLabel = getStatusLabel(d.statut);
        const statusClass = getStatusClass(d.statut);
        const deadlineDays = Math.max(0, calculateDeadlineDays(d.dateDepot));
        const deadlineColor = deadlineDays <= 0 ? '#f87171' : deadlineDays <= 1 ? '#fbbf24' : '#4ade80';
        const deadlineText = deadlineDays <= 0 ? 'Délai dépassé' : `${deadlineDays}j restants`;
        const aiBadgeClass = d.aiSuggestion === 'eligible' ? 'eligible' : 'non-eligible';
        const aiBadgeText = d.aiSuggestion === 'eligible' ? '✓ Éligible' : '⚠️ Non éligible';

        return `
            <div class="tva-card" data-id="${d.id}" onclick="openInspectionModal(${d.id})">
                <div class="tva-card-strip ${stripClass}"></div>
                <div class="tva-card-head">
                    <div class="tva-card-av">${d.nomEditeur.charAt(0)}${d.nomEditeur.split(' ').pop()?.charAt(0) || ''}</div>
                    <div class="tva-card-info">
                        <div class="tva-card-name">${d.nomEditeur}</div>
                        <div class="tva-card-num">${d.numero}</div>
                        <div class="tva-card-meta">📅 ${formatDate(d.dateDepot)}</div>
                    </div>
                    <div class="tva-card-badges">
                        <span class="badge ${statusClass}" style="font-size:10px;">${statusLabel}</span>
                    </div>
                </div>
                <div class="tva-card-body">
                    <div class="tva-card-row">
                        <span class="tva-card-row-icon">📄</span>
                        <span class="tva-card-row-label">Matériau</span>
                        <span class="tva-card-row-val">${d.materiau}</span>
                    </div>
                    <div class="tva-card-row">
                        <span class="tva-card-row-icon">📦</span>
                        <span class="tva-card-row-label">Quantité</span>
                        <span class="tva-card-row-val">${d.quantite}</span>
                    </div>
                    <div class="tva-card-row">
                        <span class="tva-card-row-icon">🤖</span>
                        <span class="tva-card-row-label">IA Suggestion</span>
                        <span class="tva-card-row-val"><span class="ai-badge ${aiBadgeClass}">${aiBadgeText} (${d.aiConfidence}%)</span></span>
                    </div>
                </div>
                <div class="tva-deadline-bar">
                    <div class="tva-deadline-row">
                        <span class="tva-deadline-label">⏰ Délai de traitement (3j max)</span>
                        <span class="tva-deadline-days" style="color:${deadlineColor}">${deadlineText}</span>
                    </div>
                    <div class="tva-deadline-track">
                        <div class="tva-deadline-fill" style="width: ${Math.min(100, (3 - deadlineDays) / 3 * 100)}%; background: ${deadlineColor};"></div>
                    </div>
                </div>
                <div class="tva-card-foot" onclick="event.stopPropagation()">
                    <button class="tva-fbt" onclick="openInspectionModal(${d.id})">👁 Inspecter</button>
                    ${d.statut === 'pending' ? `<button class="tva-fbt green" onclick="validateDemande(${d.id})">✓ Approuver</button>` : ''}
                    ${d.statut === 'pending' ? `<button class="tva-fbt amber" onclick="openSousReserveModal(${d.id})">📋 Sous-réserve</button>` : ''}
                    ${d.statut === 'sous_reserve' ? `<button class="tva-fbt green" onclick="openAttestationModal(${d.id})">✍️ Signer attestation</button>` : ''}
                    ${d.statut === 'validated' ? `<button class="tva-fbt purple" onclick="downloadAttestation(${d.id})">📄 Attestation</button>` : ''}
                    <span class="tva-ia-score" onclick="showIARecommendations(${d.id})">🤖 IA</span>
                </div>
            </div>
        `;
    }).join('');

    // Render Table
    const tableBody = document.getElementById('tva-table-body');
    tableBody.innerHTML = filtered.map(d => {
        const deadlineDays = Math.max(0, calculateDeadlineDays(d.dateDepot));
        const deadlineText = deadlineDays <= 0 ? 'Délai dépassé' : `${deadlineDays}j`;
        const deadlineColor = deadlineDays <= 0 ? '#f87171' : deadlineDays <= 1 ? '#fbbf24' : '#4ade80';
        const aiBadgeClass = d.aiSuggestion === 'eligible' ? 'eligible' : 'non-eligible';
        const aiBadgeText = d.aiSuggestion === 'eligible' ? 'Éligible' : 'Non éligible';
        return `
            <tr onclick="openInspectionModal(${d.id})" style="cursor:pointer;">
                <td><strong>${d.numero}</strong></td>
                <td>${d.nomEditeur}</td>
                <td>${d.materiau}</td>
                <td><span class="ai-badge ${aiBadgeClass}">${aiBadgeText}</span></td>
                <td>${d.aiConfidence}%</span></td>
                <td><span style="color:${deadlineColor}; font-weight:600;">${deadlineText}</span></td>
                <td><span class="status-badge ${getStatusClass(d.statut)}">${getStatusLabel(d.statut)}</span></td>
                <td onclick="event.stopPropagation()">
                    <button class="btn btn-sm btn-outline" onclick="openInspectionModal(${d.id})">👁</button>
                    ${d.statut === 'pending' ? `<button class="btn btn-sm btn-success" onclick="validateDemande(${d.id})">✓</button>` : ''}
                </td>
             </tr>
        `;
    }).join('');
}

function renderSidebar() {
    const urgentItems = demandes.filter(d => d.statut === 'pending' && calculateDeadlineDays(d.dateDepot) <= 1).slice(0, 5);
    const pendingList = document.getElementById('pendingActionsList');
    pendingList.innerHTML = urgentItems.map(d => `
        <div class="tva-pending-item" onclick="openInspectionModal(${d.id})">
            <div class="tva-pending-av" style="background:rgba(248,113,113,0.15); color:#f87171;">!</div>
            <div class="tva-pending-info">
                <div class="tva-pending-name">${d.nomEditeur}</div>
                <div class="tva-pending-when">🔴 ${calculateDeadlineDays(d.dateDepot)}j restant · ${d.materiau}</div>
            </div>
        </div>
    `).join('');
    if (urgentItems.length === 0) pendingList.innerHTML = '<div style="padding:15px; text-align:center; color:var(--text3);">✅ Aucune action urgente</div>';
}

function renderMaterialsSummary() {
    const container = document.getElementById('materialsSummary');
    const categoryCount = {
        papiers: demandes.filter(d => d.materiau.toLowerCase().includes('papier')).length,
        encres: demandes.filter(d => d.materiau.toLowerCase().includes('encre')).length,
        cartons: demandes.filter(d => d.materiau.toLowerCase().includes('carton')).length,
        autres: demandes.filter(d => !d.materiau.toLowerCase().includes('papier') && !d.materiau.toLowerCase().includes('encre') && !d.materiau.toLowerCase().includes('carton')).length
    };
    container.innerHTML = `
        <div class="tva-material-stat"><span>📄 Papiers</span><span>${categoryCount.papiers} demandes</span></div>
        <div class="tva-material-stat"><span>🖨️ Encres</span><span>${categoryCount.encres} demandes</span></div>
        <div class="tva-material-stat"><span>📦 Cartons</span><span>${categoryCount.cartons} demandes</span></div>
        <div class="tva-material-stat"><span>🔧 Autres</span><span>${categoryCount.autres} demandes</span></div>
    `;
}

function renderIAChips() {
    const pendingCount = demandes.filter(d => d.statut === 'pending').length;
    const eligibleCount = demandes.filter(d => d.aiSuggestion === 'eligible' && !d.aiReviewed).length;
    const container = document.getElementById('iaChipsContainer');
    container.innerHTML = `
        <div class="tva-chip" style="background:var(--blue-dim); color:var(--blue);" onclick="tvaFilter(null,'pending')">
            🔍 ${pendingCount} demandes à inspecter
        </div>
        <div class="tva-chip" style="background:var(--green-dim); color:var(--green);" onclick="showPendingAISuggestions()">
            🤖 ${eligibleCount} suggestions IA à valider
        </div>
        <div class="tva-chip" style="background:var(--purple-dim); color:var(--purple);" onclick="toggleReferenceList()">
            📋 ${exemptMaterials.length} matériaux exonérés
        </div>
    `;
}

function renderIASuggestions() {
    const container = document.getElementById('iaSuggestionsContainer');
    container.innerHTML = `
        <div class="tva-sugg-item" onclick="showUrgentTasks()">
            <div class="tva-sugg-icon">⚡</div>
            <div><div class="tva-sugg-text">Traitement prioritaire — dossiers avec délai ≤ 1 jour</div><div class="tva-sugg-cta">→ Voir les urgents</div></div>
        </div>
        <div class="tva-sugg-item" onclick="showPendingAISuggestions()">
            <div class="tva-sugg-icon">🤖</div>
            <div><div class="tva-sugg-text">Suggestions IA en attente de validation humaine</div><div class="tva-sugg-cta">→ Valider maintenant</div></div>
        </div>
        <div class="tva-sugg-item" onclick="toggleReferenceList()">
            <div class="tva-sugg-icon">📋</div>
            <div><div class="tva-sugg-text">Consulter la liste officielle des matériaux exonérés</div><div class="tva-sugg-cta">→ Afficher la liste</div></div>
        </div>
    `;
}

function renderAIInsights() {
    const container = document.getElementById('aiInsightGrid');
    if (!container) return;

    const pendingCount = demandes.filter(d => d.statut === 'pending').length;
    const validatedCount = demandes.filter(d => d.statut === 'validated').length;
    const successRate = Math.round((validatedCount / demandes.length) * 100);
    const urgentCount = demandes.filter(d => d.statut === 'pending' && calculateDeadlineDays(d.dateDepot) <= 1).length;
    const avgConfidence = Math.round(demandes.reduce((sum, d) => sum + d.aiConfidence, 0) / demandes.length);

    container.innerHTML = `
        <div class="insight-card" onclick="tvaFilter(null,'pending')" style="cursor:pointer;">
            <div class="insight-value">${pendingCount}</div>
            <div class="insight-label">Demandes en attente</div>
        </div>
        <div class="insight-card" onclick="showUrgentTasks()" style="cursor:pointer;">
            <div class="insight-value" style="color:#f87171;">${urgentCount}</div>
            <div class="insight-label">Urgent (délai 3j)</div>
        </div>
        <div class="insight-card" onclick="showPendingAISuggestions()" style="cursor:pointer;">
            <div class="insight-value" style="color:#a78bfa;">${demandes.filter(d => !d.aiReviewed).length}</div>
            <div class="insight-label">À valider humain</div>
        </div>
        <div class="insight-card" onclick="quickActionExport()" style="cursor:pointer;">
            <div class="insight-value">${avgConfidence}%</div>
            <div class="insight-label">Confiance IA moyenne</div>
        </div>
    `;
}

function showPendingAISuggestions() {
    const pending = demandes.filter(d => !d.aiReviewed);
    if (pending.length > 0) {
        showToast(`${pending.length} demandes en attente de validation humaine`, 'warning');
    } else {
        showToast('Toutes les demandes ont été validées', 'success');
    }
}

function showUrgentTasks() {
    tvaFilter(null, 'pending');
    showToast('Affichage des dossiers urgents (délai ≤ 1 jour)', 'warning');
}

function showIARecommendations(id) {
    const d = demandes.find(x => x.id === id);
    if (!d) return;

    const isExempt = exemptMaterials.some(m => d.materiau.toLowerCase().includes(m.toLowerCase()));
    const similarMaterials = exemptMaterials.filter(m => m.toLowerCase().includes(d.materiau.toLowerCase().substring(0, 5))).slice(0, 3);

    let tempModal = document.createElement('div');
    tempModal.className = 'modal active';
    tempModal.innerHTML = `
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">
                <div class="modal-title">🤖 IA - Analyse du matériau</div>
                <button class="modal-close" onclick="this.closest('.modal').remove()">✕</button>
            </div>
            <div class="modal-body">
                <div class="tva-ia-analyse-panel">
                    <div class="tva-ia-analyse-title">Analyse basée sur la liste des exonérations</div>
                    <div class="tva-ia-result-row ${isExempt ? 'ok' : 'fail'}">
                        ${isExempt ? '✓' : '❌'} ${d.materiau} ${isExempt ? 'est éligible' : "n'est pas éligible"} à l'exonération TVA
                    </div>
                    <div class="tva-ia-result-row info">🎯 Confiance: ${d.aiConfidence}%</div>
                    ${similarMaterials.length > 0 ? `<div class="tva-ia-result-row info">📋 Matériaux similaires éligibles: ${similarMaterials.join(', ')}</div>` : ''}
                </div>
                <div class="tva-ia-result-row info">ℹ️ L'agent doit comparer avec la liste officielle avant validation</div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="this.closest('.modal').remove()">Fermer</button>
            </div>
        </div>
    `;
    document.body.appendChild(tempModal);
}

// ============================================
// INSPECTION MODAL (Agent)
// ============================================
function openInspectionModal(id) {
    const d = demandes.find(x => x.id === id);
    if (!d) return;

    document.getElementById('inspectionNumero').innerText = d.numero;
    const deadlineDays = Math.max(0, calculateDeadlineDays(d.dateDepot));
    const isExempt = exemptMaterials.some(m => d.materiau.toLowerCase().includes(m.toLowerCase()));
    const exemptStatus = isExempt ? '✅ Ce matériau figure dans la liste des exonérations' : '❌ Ce matériau ne figure PAS dans la liste des exonérations';

    // Get similar exempt materials for comparison
    const similarExempt = exemptMaterials.filter(m => m.toLowerCase().includes(d.materiau.toLowerCase().substring(0, 5)) || d.materiau.toLowerCase().includes(m.toLowerCase().substring(0, 5))).slice(0, 5);

    document.getElementById('inspectionContent').innerHTML = `
        <div class="tva-ia-analyse-panel">
            <div class="tva-ia-analyse-title">
                🤖 IA — Classification intelligente
                <div class="tva-ia-dots"><div class="tva-ia-dot"></div><div class="tva-ia-dot"></div><div class="tva-ia-dot"></div></div>
            </div>
            <div class="tva-ia-result-row ${deadlineDays <= 1 ? 'fail' : 'ok'}">
                ${deadlineDays <= 1 ? '⚠️' : '✓'} Délai de traitement: ${deadlineDays <= 0 ? 'Délai dépassé' : `${deadlineDays} jour(s) restant(s)`}
            </div>
            <div class="tva-ia-result-row ${isExempt ? 'ok' : 'fail'}">
                ${exemptStatus}
            </div>
            <div class="tva-ia-result-row info">🤖 Suggestion IA: <strong>${d.aiSuggestion === 'eligible' ? 'Éligible' : 'Non éligible'}</strong> avec ${d.aiConfidence}% de confiance</div>
        </div>

        <div class="tva-form-section">
            <div class="tva-form-section-title">📋 Informations de la demande</div>
            <div class="tva-2col">
                <div><strong>N° Dossier:</strong> ${d.numero}</div>
                <div><strong>Date dépôt:</strong> ${formatDate(d.dateDepot)}</div>
                <div><strong>Éditeur:</strong> ${d.nomEditeur}</div>
                <div><strong>Matricule Fiscal:</strong> ${d.matricule}</div>
                <div><strong>Matériau:</strong> ${d.materiau}</div>
                <div><strong>Quantité:</strong> ${d.quantite}</div>
            </div>
        </div>

        <div class="tva-form-section">
            <div class="tva-form-section-title">📄 Justificatif fourni</div>
            <div class="tva-materials-list">
                <div class="tva-material-item">
                    <div class="tva-material-name">📎 ${d.justificatif || 'Document fourni par l\'éditeur'}</div>
                    <button class="btn btn-sm btn-outline" onclick="showToast('Aperçu du justificatif', 'info')">👁 Aperçu</button>
                </div>
            </div>
        </div>

        <div class="tva-form-section">
            <div class="tva-form-section-title">📋 Liste officielle des matériaux exonérés (référence)</div>
            <div class="tva-materials-list" style="max-height: 150px;">
                ${similarExempt.map(m => `<div class="tva-material-item"><div class="tva-material-name">✓ ${m}</div></div>`).join('')}
                ${similarExempt.length === 0 ? '<div class="tva-material-item">Aucun matériau similaire dans la liste officielle</div>' : ''}
                <div class="tva-material-item" style="border-top:1px solid var(--border); margin-top:8px; padding-top:8px;">
                    <button class="btn btn-sm btn-outline" onclick="toggleReferenceList()">📋 Voir la liste complète</button>
                </div>
            </div>
        </div>

        <div class="tva-form-section">
            <div class="tva-form-section-title">📝 Vérification agent</div>
            <div class="tva-2col">
                <label style="display:flex; align-items:center; gap:8px;"><input type="checkbox" id="checkMateriau"> Matériau conforme à la liste</label>
                <label style="display:flex; align-items:center; gap:8px;"><input type="checkbox" id="checkJustificatif"> Justificatif valide</label>
                <label style="display:flex; align-items:center; gap:8px;"><input type="checkbox" id="checkQuantite"> Quantité justifiée</label>
                <label style="display:flex; align-items:center; gap:8px;"><input type="checkbox" id="checkConformite"> Conforme à l'arrêté</label>
            </div>
        </div>
    `;

    document.getElementById('inspectionModalActions').innerHTML = `
        <button class="btn btn-outline" onclick="closeModal('inspectionModal')">Fermer</button>
        <button class="btn btn-amber" onclick="openSousReserveModal(${d.id})">📋 Approuver sous-réserve</button>
        <button class="btn btn-danger" onclick="openRejectModal(${d.id})">❌ Rejeter</button>
        <button class="btn btn-gold" onclick="validateDemande(${d.id})">✅ Approuver et générer attestation</button>
    `;
    openModal('inspectionModal');
}

// ============================================
// SOUS-RÉSERVE
// ============================================
function openSousReserveModal(id) {
    currentSousReserveId = id;
    document.getElementById('sousReserveConditions').value = '';
    document.getElementById('sousReserveDeadline').value = '';
    document.getElementById('sousReserveNotes').value = '';
    openModal('sousReserveModal');
}

function confirmSousReserve() {
    const conditions = document.getElementById('sousReserveConditions').value;
    if (!conditions) { showToast('Veuillez indiquer les conditions', 'warning'); return; }
    const d = demandes.find(x => x.id === currentSousReserveId);
    if (d) {
        d.statut = 'sous_reserve';
        d.sousReserveConditions = conditions;
        d.sousReserveDeadline = document.getElementById('sousReserveDeadline').value;
        d.sousReserveNotes = document.getElementById('sousReserveNotes').value;
        renderAll();
        closeModal('sousReserveModal');
        closeModal('inspectionModal');
        showToast(`Demande ${d.numero} approuvée sous-réserve`, 'success');
    }
}

// ============================================
// ATTESTATION (Director Signature)
// ============================================
function generateAttestationContent(d) {
    const today = new Date().toLocaleDateString('fr-FR');
    const isExempt = exemptMaterials.some(m => d.materiau.toLowerCase().includes(m.toLowerCase()));

    return `
        <div class="tva-attestation-header">
            <h3>RÉPUBLIQUE TUNISIENNE</h3>
            <p>Ministère des Affaires Culturelles<br>Direction Générale du Livre</p>
            <h4 style="margin-top: 10px;">ATTESTATION D'EXONÉRATION DE TVA</h4>
        </div>
        <div class="tva-attestation-body">
            <p>Nous soussigné, Directeur Général du Livre, attestons que l'éditeur :</p>
            <p><strong>${d.nomEditeur}</strong><br>
            <strong>N° Matricule Fiscal :</strong> ${d.matricule}</p>
            <p>bénéficie de l'exonération de TVA pour l'acquisition des matériaux suivants :</p>
            <p><strong>${d.materiau}</strong><br>
            Quantité : ${d.quantite}</p>
            <p>Ces matériaux sont utilisés dans l'impression des livres et figurent sur la liste des produits exonérés conformément à l'arrêté du Ministre des Finances.</p>
            <p>La présente attestation est délivrée pour servir et valoir ce que de droit.</p>
            <p>Fait à Tunis, le ${today}</p>
        </div>
        <div class="tva-attestation-footer">
            <div>Direction Générale du Livre</div>
            <div>Cachet et signature</div>
        </div>
    `;
}

function openAttestationModal(id) {
    const d = demandes.find(x => x.id === id);
    if (!d) return;
    currentAttestationId = id;

    const attestationHtml = d.attestationContent || generateAttestationContent(d);
    document.getElementById('attestationContent').innerHTML = `
        <div class="tva-attestation-preview" id="attestationPreview">
            ${attestationHtml}
        </div>

        <div class="tva-form-section">
            <div class="tva-form-section-title">✏️ Éditer l'attestation (si correction nécessaire)</div>
            <textarea id="attestationEditArea" rows="12" class="form-input" style="font-family: monospace; font-size: 11px;">${attestationHtml.replace(/<[^>]*>/g, '')}</textarea>
        </div>

        <div class="tva-form-section">
            <div class="tva-form-section-title">🖊️ Signature du Directeur</div>
            <div class="tva-signature-area" onclick="simulateSignature()">
                <div id="signaturePreview" class="tva-signature-preview">_________________</div>
                <div style="font-size: 10px; color: var(--text3); margin-top: 8px;">Cliquez pour apposer la signature électronique</div>
            </div>
        </div>
    `;

    document.getElementById('attestationModalActions').innerHTML = `
        <button class="btn btn-outline" onclick="closeModal('attestationModal')">Annuler</button>
        <button class="btn btn-outline" onclick="previewAttestation()">👁 Aperçu</button>
        <button class="btn btn-gold" onclick="signAndSendAttestation()">✍️ Signer et envoyer par email</button>
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

function signAndSendAttestation() {
    const d = demandes.find(x => x.id === currentAttestationId);
    if (d) {
        const editedContent = document.getElementById('attestationEditArea')?.value;
        const signaturePresent = document.getElementById('signaturePreview')?.innerHTML.includes('Dr. Karim');

        if (!signaturePresent) {
            showToast('Veuillez apposer la signature du directeur', 'warning');
            return;
        }

        d.statut = 'validated';
        d.attestationGenerated = true;
        d.attestationContent = editedContent;
        d.aiReviewed = true;
        d.humanDecision = 'approve';
        d.directorSigned = true;
        d.signedAt = new Date().toISOString();

        renderAll();
        closeModal('attestationModal');
        showToast(`Attestation signée et envoyée par email à ${d.nomEditeur}`, 'success');

        setTimeout(() => {
            showToast(`📧 Email envoyé avec l'attestation jointe`, 'success');
        }, 1000);
    }
}

function downloadAttestation(id) {
    const d = demandes.find(x => x.id === id);
    if (d && d.attestationContent) {
        const win = window.open();
        win.document.write(`
            <html>
            <head><title>Attestation_TVA_${d.numero}</title>
            <style>body { font-family: Arial, sans-serif; padding: 40px; }</style>
            </head>
            <body>${d.attestationContent}</body>
            </html>
        `);
        win.document.close();
        win.print();
        showToast(`Attestation ${d.numero} téléchargée`, 'success');
    } else if (d && d.statut === 'validated') {
        const content = generateAttestationContent(d);
        const win = window.open();
        win.document.write(`
            <html>
            <head><title>Attestation_TVA_${d.numero}</title>
            <style>body { font-family: Arial, sans-serif; padding: 40px; }</style>
            </head>
            <body>${content}</body>
            </html>
        `);
        win.document.close();
        win.print();
    }
}

// ============================================
// VALIDATION
// ============================================
function validateDemande(id) {
    const d = demandes.find(x => x.id === id);
    if (d && d.statut === 'pending') {
        d.attestationContent = generateAttestationContent(d);
        d.statut = 'sous_reserve';
        d.aiReviewed = true;
        d.humanDecision = 'approve';
        d.agentApproved = true;
        d.agentApprovedAt = new Date().toISOString();
        renderAll();
        closeModal('inspectionModal');
        showToast(`Demande ${d.numero} approuvée par l'agent. Signature directeur requise.`, 'success');

        setTimeout(() => {
            openAttestationModal(d.id);
        }, 500);
    }
}

// ============================================
// REJECTION
// ============================================
const rejectionTemplatesList = [
    { title: 'Matériau non éligible', desc: 'Ce matériau ne figure pas dans la liste des exonérations TVA' },
    { title: 'Documents incomplets', desc: 'Justificatif manquant ou invalide' },
    { title: 'Quantité excessive', desc: 'La quantité dépasse les limites autorisées' },
    { title: 'Hors délai', desc: 'Demande déposée après la date limite' }
];

function openRejectModal(id) {
    currentRejectId = id;
    document.getElementById('rejectionTemplates').innerHTML = rejectionTemplatesList.map(t => `
        <div class="tva-rejection-card" style="padding:10px 12px; background:var(--bg3); border:1px solid var(--border); border-radius:var(--radius-sm); cursor:pointer; margin-bottom:8px;" onclick="selectRejectionTemplate(this, '${t.desc.replace(/'/g, "\\'")}')">
            <div style="font-weight:700; font-size:12px;">${t.title}</div>
            <div style="font-size:10.5px; color:var(--text3);">${t.desc}</div>
        </div>
    `).join('');
    document.getElementById('rejectionReason').value = '';
    document.getElementById('rejectionNotes').value = '';
    openModal('rejectModal');
}

function selectRejectionTemplate(el, reason) {
    document.querySelectorAll('.tva-rejection-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('rejectionReason').value = reason;
}

function confirmReject() {
    const reason = document.getElementById('rejectionReason').value;
    if (!reason) { showToast('Veuillez saisir un motif', 'error'); return; }
    const notes = document.getElementById('rejectionNotes').value;
    const d = demandes.find(x => x.id === currentRejectId);
    if (d) {
        d.statut = 'rejected';
        d.motifRejet = reason;
        d.rejectionNotes = notes;
        d.aiReviewed = true;
        d.humanDecision = 'reject';
        renderAll();
        closeModal('rejectModal');
        closeModal('inspectionModal');
        showToast(`Demande ${d.numero} rejetée`, 'error');
    }
}

// ============================================
// REPORT GENERATION
// ============================================
function selectReportPeriod(period) {
    selectedReportPeriod = period;
    document.querySelectorAll('.tva-report-period').forEach(el => el.classList.remove('selected'));
    const selected = document.querySelector(`.tva-report-period[data-period="${period}"]`);
    if (selected) selected.classList.add('selected');

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
        <head><title>Rapport_TVA</title>
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
            <h1>📊 Rapport des demandes d'exonération TVA</h1>
            <p>Période: ${formatDate(startDate)} au ${formatDate(endDate)} | Généré le: ${new Date().toLocaleString('fr-FR')}</p>
            <div class="summary">
                <strong>Résumé:</strong><br>
                Total demandes: ${filtered.length}<br>
                À inspecter: ${filtered.filter(d => d.statut === 'pending').length}<br>
                Sous-réserve: ${filtered.filter(d => d.statut === 'sous_reserve').length}<br>
                Validées: ${filtered.filter(d => d.statut === 'validated').length}<br>
                Rejetées: ${filtered.filter(d => d.statut === 'rejected').length}<br>
                Confiance IA moyenne: ${Math.round(filtered.reduce((sum, d) => sum + d.aiConfidence, 0) / filtered.length || 0)}%
            </div>
            <table><thead><tr><th>N° Dossier</th><th>Éditeur</th><th>Matériau</th><th>Suggestion IA</th><th>Statut</th></tr></thead>
            <tbody>${filtered.map(d => `<tr><td>${d.numero}</td><td>${d.nomEditeur}</td><td>${d.materiau}</td><td>${d.aiSuggestion === 'eligible' ? 'Éligible' : 'Non éligible'} (${d.aiConfidence}%)</td><td>${getStatusLabel(d.statut)}</td></tr>`).join('')}</tbody>
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
function tvaFilter(el, status) {
    if (el) {
        document.querySelectorAll('.tva-ftab').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
    }
    document.getElementById('statusFilter').value = status;
    renderGridAndTable();
}

function tvaFilterStatus() {
    document.querySelectorAll('.tva-ftab').forEach(t => t.classList.remove('active'));
    renderGridAndTable();
}

function tvaSearch(term) { renderGridAndTable(); }

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = 'all';
    document.getElementById('aiFilter').value = 'all';
    document.querySelectorAll('.tva-ftab').forEach(t => t.classList.remove('active'));
    document.querySelector('.tva-ftab').classList.add('active');
    renderGridAndTable();
}

function tvaToggleView() {
    tvaIsGrid = !tvaIsGrid;
    document.getElementById('tva-grid').style.display = tvaIsGrid ? '' : 'none';
    document.getElementById('tva-table-view').style.display = tvaIsGrid ? 'none' : '';
    document.getElementById('tva-view-toggle').textContent = tvaIsGrid ? '⊞' : '☰';
}

function quickActionExport() {
    console.log('Export:', demandes);
    showToast('Export CSV démarré', 'info');
}

function toggleReferenceList() {
    const list = document.getElementById('tvaReferenceList');
    if (list.style.display === 'none') {
        list.style.display = 'block';
        showToast('Liste des matériaux exonérés affichée', 'info');
    } else {
        list.style.display = 'none';
    }
}

// ============================================
// INITIALIZATION
// ============================================
window.tvaFilter = tvaFilter;
window.tvaToggleView = tvaToggleView;
window.resetFilters = resetFilters;
window.openInspectionModal = openInspectionModal;
window.validateDemande = validateDemande;
window.openSousReserveModal = openSousReserveModal;
window.confirmSousReserve = confirmSousReserve;
window.openAttestationModal = openAttestationModal;
window.signAndSendAttestation = signAndSendAttestation;
window.downloadAttestation = downloadAttestation;
window.openRejectModal = openRejectModal;
window.confirmReject = confirmReject;
window.selectRejectionTemplate = selectRejectionTemplate;
window.showIARecommendations = showIARecommendations;
window.showUrgentTasks = showUrgentTasks;
window.openReportModal = openReportModal;
window.selectReportPeriod = selectReportPeriod;
window.generateReport = generateReport;
window.previewAttestation = previewAttestation;
window.simulateSignature = simulateSignature;
window.applyAdditionalFilters = applyAdditionalFilters;
window.toggleReferenceList = toggleReferenceList;
window.closeModal = closeModal;
window.quickActionExport = quickActionExport;

renderAll();
</script>


@endsection
