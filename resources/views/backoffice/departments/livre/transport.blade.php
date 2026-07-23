@extends('shared.layouts.backoffice')

@section('page-title', 'Couverture frais transport - Direction du Livre')
@section('breadcrumb', 'Couverture frais transport')


@section('content')
<style>
/* ════════════════════════════════════════════
   TRANSPORT — DESIGN SYSTEM
   Aligned with droits & foire modules
════════════════════════════════════════════ */

/* ── KPI Row ── */
.tr-kpi-row {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 12px;
    margin-bottom: 22px;
}
@media (max-width: 1100px) { .tr-kpi-row { grid-template-columns: repeat(3,1fr); } }
@media (max-width: 700px)  { .tr-kpi-row { grid-template-columns: repeat(2,1fr); } }

.tr-kpi {
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
.tr-kpi:hover { border-color: var(--border2); transform: translateY(-1px); }
.tr-kpi-icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}
.tr-kpi-val  { font-size: 22px; font-weight: 900; font-family: var(--font-mono); line-height: 1; }
.tr-kpi-lbl  { font-size: 10.5px; color: var(--text3); text-transform: uppercase; letter-spacing: 0.6px; margin-top: 3px; }
.tr-kpi-delta{ font-size: 10px; font-family: var(--font-mono); font-weight: 700; margin-top: 3px; }

/* ── IA Smart Banner ── */
.tr-ia-banner {
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
.tr-ia-banner::after {
    content: '🚚';
    position: absolute; right: 24px; top: 50%;
    transform: translateY(-50%);
    font-size: 56px; opacity: 0.06; pointer-events: none;
}
.tr-ia-orb {
    width: 44px; height: 44px; border-radius: 12px;
    background: var(--gold-dim);
    border: 1px solid rgba(201,168,76,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
    animation: tr-orb-pulse 3s ease-in-out infinite;
}
@keyframes tr-orb-pulse {
    0%,100% { box-shadow: 0 0 0 0 rgba(201,168,76,0.35); }
    50%      { box-shadow: 0 0 0 10px rgba(201,168,76,0); }
}
.tr-ia-body { flex: 1; }
.tr-ia-title {
    font-size: 13px; font-weight: 700; color: var(--text);
    margin-bottom: 5px; display: flex;
    align-items: center; gap: 8px;
}
.tr-ia-chips { display: flex; flex-wrap: wrap; gap: 7px; }
.tr-chip {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 11px; border-radius: 20px;
    font-size: 11px; font-weight: 600; cursor: pointer;
    transition: opacity 0.15s;
}
.tr-chip:hover { opacity: 0.8; }

/* ── Main layout ── */
.tr-shell {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 18px;
    align-items: start;
}
@media (max-width: 1060px) { .tr-shell { grid-template-columns: 1fr; } }

/* ── Filter/action bar ── */
.tr-topbar {
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
.tr-filter-tabs { display: flex; gap: 0; flex-wrap: wrap; }
.tr-ftab {
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
.tr-ftab:hover { color: var(--text2); }
.tr-ftab.active { color: var(--gold); border-bottom-color: var(--gold); }

.tr-search {
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
.tr-search:focus { border-color: var(--gold); }
.tr-search::placeholder { color: var(--text3); }

.tr-select {
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
.tr-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
    gap: 14px;
    margin-bottom: 18px;
}

.tr-card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    cursor: pointer;
    transition: all 0.18s;
    animation: tr-fadein 0.3s ease forwards;
}
@keyframes tr-fadein { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
.tr-card:hover { border-color: var(--border2); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.15); }

.tr-card-strip {
    height: 4px;
    background: linear-gradient(90deg, var(--gold), var(--gold2));
}
.tr-card-strip.urgent { background: linear-gradient(90deg, #f87171, #ef4444); }
.tr-card-strip.warning { background: linear-gradient(90deg, #fbbf24, #f59e0b); }
.tr-card-strip.pending { background: linear-gradient(90deg, #60a5fa, #3b82f6); }
.tr-card-strip.validated { background: linear-gradient(90deg, #4ade80, #22c55e); }
.tr-card-strip.sous_reserve { background: linear-gradient(90deg, #f59e0b, #d97706); }

.tr-card-head {
    padding: 14px 16px 10px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    border-bottom: 1px solid var(--border);
}
.tr-card-av {
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
.tr-card-info { flex: 1; min-width: 0; }
.tr-card-name { font-size: 13.5px; font-weight: 700; color: var(--text); }
.tr-card-num  { font-size: 10px; font-family: var(--font-mono); color: var(--text3); margin-top: 2px; }
.tr-card-meta { font-size: 11px; color: var(--text2); margin-top: 3px; display: flex; align-items: center; gap: 5px; flex-wrap: wrap; }
.tr-card-badges { display: flex; flex-direction: column; align-items: flex-end; gap: 4px; flex-shrink: 0; }

.tr-card-body { padding: 12px 16px; }
.tr-card-row  { display: flex; align-items: center; gap: 8px; margin-bottom: 6px; font-size: 12px; color: var(--text2); }
.tr-card-row-icon { font-size: 13px; flex-shrink: 0; width: 18px; text-align: center; }
.tr-card-row-label { color: var(--text3); min-width: 60px; font-size: 11px; }
.tr-card-row-val   { font-weight: 600; color: var(--text); }

.tr-deadline-bar {
    padding: 8px 16px 12px;
}
.tr-deadline-row { display: flex; align-items: center; gap: 8px; margin-bottom: 4px; }
.tr-deadline-label { font-size: 10px; color: var(--text3); flex: 1; }
.tr-deadline-days  { font-size: 10.5px; font-family: var(--font-mono); font-weight: 700; }
.tr-deadline-track { height: 4px; background: var(--bg4); border-radius: 2px; overflow: hidden; }
.tr-deadline-fill  { height: 100%; border-radius: 2px; transition: width 0.6s ease; }

.tr-card-foot {
    padding: 10px 16px;
    border-top: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}
.tr-fbt {
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
.tr-fbt:hover { background: var(--bg4); color: var(--text); }
.tr-fbt.green { background: var(--green-dim); border-color: rgba(74,222,128,0.25); color: var(--green); }
.tr-fbt.blue  { background: var(--blue-dim);  border-color: rgba(59,130,246,0.25); color: var(--blue); }
.tr-fbt.amber { background: var(--amber-dim); border-color: rgba(251,191,36,0.25); color: var(--amber); }
.tr-fbt.purple{ background: var(--purple-dim); border-color: rgba(167,139,250,0.25); color: var(--purple); }

.tr-ia-score {
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
#tr-table-view .status-badge {
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

/* ════ RIGHT SIDEBAR ════ */
.tr-sidebar { display: flex; flex-direction: column; gap: 16px; position: sticky; top: 76px; }

.tr-sb-panel {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
}
.tr-sb-head {
    padding: 12px 15px;
    border-bottom: 1px solid var(--border);
    font-size: 12px;
    font-weight: 700;
    color: var(--text);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

/* Destinations fréquentes */
.tr-destination-list {
    padding: 8px 0;
}
.tr-destination-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border-bottom: 1px solid var(--border);
    cursor: pointer;
}
.tr-destination-item:hover { background: var(--bg3); }
.tr-destination-name { flex: 1; font-weight: 600; font-size: 12px; }
.tr-destination-bar {
    width: 80px;
    height: 6px;
    background: var(--bg4);
    border-radius: 3px;
    overflow: hidden;
}
.tr-destination-fill { height: 100%; background: var(--gold); border-radius: 3px; }
.tr-destination-count { font-size: 11px; color: var(--text3); min-width: 35px; text-align: right; }

/* Pending items */
.tr-pending-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 14px;
    border-bottom: 1px solid var(--border);
    cursor: pointer;
    transition: background 0.15s;
}
.tr-pending-item:hover { background: var(--bg3); }
.tr-pending-av {
    width: 28px; height: 28px; border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: 700;
    flex-shrink: 0;
}
.tr-pending-info { flex: 1; min-width: 0; }
.tr-pending-name { font-size: 12px; font-weight: 600; color: var(--text); }
.tr-pending-when { font-size: 10.5px; color: var(--text3); }

/* IA suggestions */
.tr-ia-suggestions { display: flex; flex-direction: column; gap: 8px; padding: 10px 14px; }
.tr-sugg-item {
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
.tr-sugg-item:hover { border-color: var(--gold); }
.tr-sugg-icon { font-size: 15px; flex-shrink: 0; }
.tr-sugg-text { font-size: 11.5px; color: var(--text2); line-height: 1.45; flex: 1; }
.tr-sugg-cta  { font-size: 10px; color: var(--gold); font-weight: 700; margin-top: 3px; }

/* Quick actions */
.tr-quick { display: flex; flex-direction: column; gap: 6px; padding: 10px 14px; }
.tr-qa {
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
.tr-qa:hover { background: var(--bg4); color: var(--text); border-color: var(--border2); }

/* Trends Chart */
.tr-trends-chart {
    padding: 12px;
}
.tr-trend-bars {
    display: flex;
    justify-content: space-around;
    align-items: flex-end;
    height: 140px;
    margin-bottom: 12px;
}
.tr-trend-bar-item {
    text-align: center;
    flex: 1;
}
.tr-trend-bar {
    width: 40px;
    margin: 0 auto;
    background: linear-gradient(180deg, var(--gold), var(--gold2));
    border-radius: 4px 4px 0 0;
    transition: height 0.3s ease;
    cursor: pointer;
}
.tr-trend-label {
    font-size: 10px;
    color: var(--text3);
    margin-top: 6px;
}
.tr-trend-summary {
    display: flex;
    justify-content: space-between;
    padding: 10px 0 0;
    border-top: 1px solid var(--border);
    font-size: 11px;
}
.tr-trend-up { color: var(--green); }
.tr-trend-down { color: var(--red); }

/* ════ MODAL STYLES ════ */
.tr-modal-wide { max-width: 750px; }
.tr-form-section {
    background: var(--bg3);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 14px 16px;
    margin-bottom: 14px;
}
.tr-form-section-title {
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
.tr-2col { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

/* IA Analyse Panel */
.tr-ia-analyse-panel {
    background: linear-gradient(135deg, rgba(167,139,250,0.07), rgba(201,168,76,0.05));
    border: 1px solid rgba(167,139,250,0.2);
    border-radius: var(--radius-sm);
    padding: 14px 16px;
    margin-bottom: 14px;
}
.tr-ia-analyse-title {
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
.tr-ia-dots {
    display: flex;
    align-items: center;
    gap: 3px;
    margin-left: auto;
}
.tr-ia-dot {
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: var(--gold);
    animation: tr-think 1.3s ease-in-out infinite;
}
.tr-ia-dot:nth-child(2) { animation-delay: 0.2s; }
.tr-ia-dot:nth-child(3) { animation-delay: 0.4s; }
@keyframes tr-think { 0%,100% { opacity:0.2; transform:scale(0.8); } 50% { opacity:1; transform:scale(1.2); } }

.tr-ia-result-row {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 7px 10px;
    border-radius: var(--radius-sm);
    margin-bottom: 6px;
    font-size: 12px;
}
.tr-ia-result-row.ok   { background: var(--green-dim); color: var(--green); }
.tr-ia-result-row.fail { background: var(--red-dim);   color: var(--red); }
.tr-ia-result-row.warn { background: var(--amber-dim); color: var(--amber); }
.tr-ia-result-row.info { background: var(--blue-dim);  color: var(--blue); }

/* Ouvrages List */
.tr-ouvrages-list {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 12px;
    max-height: 200px;
    overflow-y: auto;
}
.tr-ouvrage-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px;
    border-bottom: 1px solid var(--border);
}
.tr-ouvrage-item:last-child { border-bottom: none; }
.tr-ouvrage-title { flex: 1; font-size: 12px; }
.tr-ouvrage-qty { font-size: 11px; color: var(--gold); font-weight: 600; }

/* Attestation Preview */
.tr-attestation-preview {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    border: 1px solid var(--gold);
    border-radius: var(--radius);
    padding: 20px;
    margin-bottom: 16px;
    position: relative;
}
.tr-attestation-header {
    text-align: center;
    border-bottom: 2px solid var(--gold);
    padding-bottom: 12px;
    margin-bottom: 16px;
}
.tr-attestation-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: #92400e; }
.tr-attestation-header p { margin: 4px 0 0; font-size: 11px; color: #b45309; }
.tr-attestation-body { font-size: 12px; line-height: 1.6; color: #78350f; }
.tr-attestation-footer {
    margin-top: 20px;
    padding-top: 12px;
    border-top: 1px dashed var(--gold);
    display: flex;
    justify-content: space-between;
    font-size: 10px;
    color: #92400e;
}

/* Signature Area */
.tr-signature-area {
    border: 2px dashed var(--border2);
    border-radius: var(--radius-sm);
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.15s;
}
.tr-signature-area:hover { border-color: var(--gold); }
.tr-signature-preview {
    max-width: 200px;
    margin: 0 auto;
    font-family: 'Brush Script MT', cursive;
    font-size: 24px;
    color: var(--gold);
}

/* Estimator Modal */
.tr-estimator-modal .tr-estimator-field {
    margin-bottom: 16px;
}
.tr-estimator-field label {
    display: block;
    font-size: 11px;
    font-weight: 600;
    margin-bottom: 5px;
    color: var(--text2);
}
.tr-estimator-field input, .tr-estimator-field select {
    width: 100%;
    padding: 10px 12px;
    background: var(--bg3);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    color: var(--text);
    font-size: 13px;
}
.tr-estimator-result {
    background: var(--gold-dim);
    border-radius: var(--radius-sm);
    padding: 16px;
    text-align: center;
    margin-top: 16px;
}
.tr-estimator-result .result-label {
    font-size: 10px;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 5px;
}
.tr-estimator-result .result-value {
    font-size: 28px;
    font-weight: 900;
    color: var(--gold);
}
.tr-estimator-result .result-confidence {
    font-size: 10px;
    color: var(--text3);
    margin-top: 5px;
}

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

/* Additional Filters Row */
.tr-filters-row {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 16px;
}
.tr-filters-row .tr-select {
    min-width: 140px;
}
.tr-date-range {
    display: flex;
    gap: 8px;
    align-items: center;
}
.tr-date-range input {
    padding: 7px 10px;
    background: var(--bg3);
    border: 1px solid var(--border2);
    border-radius: var(--radius-sm);
    font-size: 12px;
    color: var(--text);
}
</style>

<div class="page active">
    {{-- ════════ KPI ROW ════════ --}}
    <div class="tr-kpi-row">
        <div class="tr-kpi">
            <div class="tr-kpi-icon" style="background:var(--gold-dim);">🚚</div>
            <div>
                <div class="tr-kpi-val" style="color:var(--gold);" id="kpiTotal">0</div>
                <div class="tr-kpi-lbl">Total demandes</div>
                <div class="tr-kpi-delta" style="color:var(--green);">↑ +15% ce mois</div>
            </div>
        </div>
        <div class="tr-kpi">
            <div class="tr-kpi-icon" style="background:var(--blue-dim);">🔍</div>
            <div>
                <div class="tr-kpi-val" style="color:var(--blue);" id="kpiPending">0</div>
                <div class="tr-kpi-lbl">À inspecter</div>
                <div class="tr-kpi-delta" style="color:var(--amber);">Délai 3j max</div>
            </div>
        </div>
        <div class="tr-kpi">
            <div class="tr-kpi-icon" style="background:var(--amber-dim);">📋</div>
            <div>
                <div class="tr-kpi-val" style="color:var(--amber);" id="kpiSousReserve">0</div>
                <div class="tr-kpi-lbl">Sous-réserve</div>
                <div class="tr-kpi-delta" style="color:var(--amber);">→ Action requise</div>
            </div>
        </div>
        <div class="tr-kpi">
            <div class="tr-kpi-icon" style="background:var(--green-dim);">✅</div>
            <div>
                <div class="tr-kpi-val" style="color:var(--green);" id="kpiValidated">0</div>
                <div class="tr-kpi-lbl">Validées</div>
                <div class="tr-kpi-delta" style="color:var(--teal);">Taux 72%</div>
            </div>
        </div>
        <div class="tr-kpi">
            <div class="tr-kpi-icon" style="background:var(--purple-dim);">🤖</div>
            <div>
                <div class="tr-kpi-val" style="color:var(--purple);">94%</div>
                <div class="tr-kpi-lbl">Fiabilité IA</div>
                <div class="tr-kpi-delta" style="color:var(--purple);">→ Estimation</div>
            </div>
        </div>
    </div>

    {{-- ════════ AI TRANSPORT INTELLIGENCE PANEL ════════ --}}
    <div class="tr-ia-banner">
        <div class="tr-ia-orb">🤖</div>
        <div class="tr-ia-body">
            <div class="tr-ia-title">
                AI Transport Intelligence — Logistique et optimisation
                <span style="font-size:10px; padding:2px 8px; background:var(--gold-dim); color:var(--gold); border-radius:20px; font-weight:700;">LIVE</span>
            </div>
            <div class="tr-ia-chips" id="iaChipsContainer"></div>
        </div>
        <div style="display:flex; gap:8px; flex-shrink:0;">
            <button class="btn-report" onclick="openEstimatorModal()">
                <span class="icon-spark"></span>
                Estimateur de frais
            </button>
            <button class="btn-report" onclick="openReportModal()">
                <span class="icon-spark"></span>
                Générer rapport
            </button>
        </div>
    </div>

    {{-- ════════ AI INSIGHT GRID ════════ --}}
    <div class="ai-insight-grid" id="aiInsightGrid"></div>

    {{-- ════════ MAIN SHELL ════════ --}}
    <div class="tr-shell">

        {{-- ══ LEFT: CARDS + TABLE ══ --}}
        <div>

            {{-- Filter/Search bar --}}
            <div class="tr-topbar">
                <div class="tr-filter-tabs">
                    <div class="tr-ftab active" onclick="trFilter(this,'all')">Toutes (<span id="filterCountAll">0</span>)</div>
                    <div class="tr-ftab" onclick="trFilter(this,'pending')">🔍 À inspecter (<span id="filterCountPending">0</span>)</div>
                    <div class="tr-ftab" onclick="trFilter(this,'sous_reserve')">📋 Sous-réserve (<span id="filterCountSousReserve">0</span>)</div>
                    <div class="tr-ftab" onclick="trFilter(this,'validated')">✅ Validées (<span id="filterCountValidated">0</span>)</div>
                    <div class="tr-ftab" onclick="trFilter(this,'rejected')">❌ Rejetées (<span id="filterCountRejected">0</span>)</div>
                </div>
                <input type="text" class="tr-search" placeholder="🔍 Rechercher par éditeur, pays..." id="searchInput" oninput="trSearch(this.value)">
                <select class="tr-select" id="statusFilter" onchange="trFilterStatus()">
                    <option value="all">Tous les statuts</option>
                    <option value="pending">À inspecter</option>
                    <option value="sous_reserve">Sous-réserve</option>
                    <option value="validated">Validé</option>
                    <option value="rejected">Rejeté</option>
                </select>
                <button class="btn btn-outline btn-sm" onclick="resetFilters()">Reset</button>
                <button class="btn btn-outline btn-sm" onclick="trToggleView()" id="tr-view-toggle" title="Basculer vue">⊞</button>
            </div>

            {{-- Additional Filters Row --}}
            <div class="tr-filters-row">
                <select id="countryFilter" class="tr-select" onchange="applyAdditionalFilters()">
                    <option value="all">Tous les pays</option>
                    <option value="France">France</option>
                    <option value="Canada">Canada</option>
                    <option value="Belgique">Belgique</option>
                    <option value="Suisse">Suisse</option>
                    <option value="Allemagne">Allemagne</option>
                    <option value="Maroc">Maroc</option>
                    <option value="Espagne">Espagne</option>
                </select>
                <select id="volumeFilter" class="tr-select" onchange="applyAdditionalFilters()">
                    <option value="all">Tous les volumes</option>
                    <option value="small">Petit (&lt;10 cartons)</option>
                    <option value="medium">Moyen (10-50 cartons)</option>
                    <option value="large">Grand (&gt;50 cartons)</option>
                </select>
                <div class="tr-date-range">
                    <input type="date" id="dateStart" placeholder="Date début" onchange="applyAdditionalFilters()">
                    <span>→</span>
                    <input type="date" id="dateEnd" placeholder="Date fin" onchange="applyAdditionalFilters()">
                </div>
            </div>

            {{-- CARD GRID VIEW --}}
            <div class="tr-grid" id="tr-grid"></div>

            {{-- TABLE VIEW --}}
            <div id="tr-table-view" style="display:none;" class="panel">
                <div class="panel-head">
                    <div><div class="panel-title">📋 Liste des demandes de transport</div><div class="panel-sub">Vue tabulaire</div></div>
                    <button class="btn btn-outline btn-sm" onclick="quickActionExport()">📥 Exporter CSV</button>
                </div>
                <div class="panel-body no-pad">
                    <div class="table-wrap">
                        <table class="table">
                            <thead>
                                <tr><th>N° Dossier</th><th>Éditeur</th><th>Pays</th><th>Cartons</th><th>Poids total</th><th>Frais estimés</th><th>Délai restant</th><th>Statut</th><th>Actions</th></tr>
                            </thead>
                            <tbody id="tr-table-body"></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        {{-- ══ RIGHT SIDEBAR ══ --}}
        <div class="tr-sidebar">

            {{-- Destinations fréquentes --}}
            <div class="tr-sb-panel">
                <div class="tr-sb-head">📍 Destinations fréquentes</div>
                <div class="tr-destination-list" id="destinationList"></div>
            </div>

            {{-- Tendances des expéditions --}}
            <div class="tr-sb-panel">
                <div class="tr-sb-head">📈 Tendances mensuelles</div>
                <div class="tr-trends-chart" id="trendsChart"></div>
            </div>

            {{-- Actions urgentes (délai 3j) --}}
            <div class="tr-sb-panel">
                <div class="tr-sb-head">⏰ Actions urgentes (délai 3j)</div>
                <div id="pendingActionsList"></div>
            </div>

            {{-- IA Suggestions --}}
            <div class="tr-sb-panel">
                <div class="tr-sb-head">🤖 IA Recommendations</div>
                <div class="tr-ia-suggestions" id="iaSuggestionsContainer"></div>
            </div>

            {{-- Quick actions --}}
            <div class="tr-sb-panel">
                <div class="tr-sb-head">⚡ Actions rapides</div>
                <div class="tr-quick">
                    <button class="tr-qa" onclick="openEstimatorModal()">💰 Estimateur de frais</button>
                    <button class="tr-qa" onclick="openReportModal()"><span class="icon-spark" style="display:inline-block; width:14px; height:14px;"></span> Générer rapport</button>
                    <button class="tr-qa" onclick="quickActionExport()">📥 Exporter toutes</button>
                </div>
            </div>

        </div>
    </div>

    {{-- ════════════════════════════════════════════
         MODAL — INSPECTION & VALIDATION (Agent)
    ════════════════════════════════════════════ --}}
    <div class="modal" id="inspectionModal">
        <div class="modal-content tr-modal-wide">
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
                <div class="tr-ia-result-row info">ℹ️ L'IA suivra ce dossier et enverra des rappels à la date limite</div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('sousReserveModal')">Annuler</button>
                <button class="btn btn-amber" onclick="confirmSousReserve()">Confirmer sous-réserve</button>
            </div>
        </div>
    </div>

    {{-- MODAL — ATTESTATION (Director Signature) --}}
    <div class="modal" id="attestationModal">
        <div class="modal-content tr-modal-wide">
            <div class="modal-header">
                <div class="modal-title">✍️ Attestation de transport — Signature du Directeur</div>
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
                <div class="tr-rejection-templates" id="rejectionTemplates"></div>
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

    {{-- MODAL — ESTIMATEUR DE FRAIS --}}
    <div class="modal" id="estimatorModal">
        <div class="modal-content" style="max-width: 450px;">
            <div class="modal-header">
                <div class="modal-title">💰 Estimateur de frais de transport IA</div>
                <button class="modal-close" onclick="closeModal('estimatorModal')">✕</button>
            </div>
            <div class="modal-body">
                <div class="tr-estimator-field">
                    <label>Pays de destination</label>
                    <select id="estCountry" onchange="calculateEstimate()">
                        <option value="">Sélectionner un pays</option>
                        <option value="France">France</option>
                        <option value="Canada">Canada</option>
                        <option value="Belgique">Belgique</option>
                        <option value="Suisse">Suisse</option>
                        <option value="Allemagne">Allemagne</option>
                        <option value="Maroc">Maroc</option>
                        <option value="Espagne">Espagne</option>
                        <option value="Italie">Italie</option>
                        <option value="Sénégal">Sénégal</option>
                    </select>
                </div>
                <div class="tr-estimator-field">
                    <label>Nombre de cartons</label>
                    <input type="number" id="estCartons" placeholder="Ex: 10" oninput="calculateEstimate()">
                </div>
                <div class="tr-estimator-field">
                    <label>Poids par carton (kg)</label>
                    <input type="number" id="estWeight" placeholder="Ex: 8" value="5" oninput="calculateEstimate()">
                </div>
                <div class="tr-estimator-result" id="estimatorResult">
                    <div class="result-label">Estimation IA</div>
                    <div class="result-value" id="estCost">0 TND</div>
                    <div class="result-confidence" id="estConfidence"></div>
                </div>
                <div class="tr-ia-result-row info" style="margin-top:12px;">ℹ️ Basé sur les tarifs moyens et l'historique des expéditions</div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('estimatorModal')">Fermer</button>
                <button class="btn btn-gold" onclick="applyEstimateToForm()">Appliquer à la demande</button>
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
                <div class="tr-report-options" style="display:flex; gap:12px; flex-wrap:wrap; margin-bottom:20px;">
                    <div class="tr-report-period" onclick="selectReportPeriod('day')" data-period="day" style="flex:1; text-align:center; padding:10px; background:var(--bg3); border-radius:var(--radius-sm); cursor:pointer;">Aujourd'hui</div>
                    <div class="tr-report-period" onclick="selectReportPeriod('week')" data-period="week" style="flex:1; text-align:center; padding:10px; background:var(--bg3); border-radius:var(--radius-sm); cursor:pointer;">Cette semaine</div>
                    <div class="tr-report-period" onclick="selectReportPeriod('month')" data-period="month" style="flex:1; text-align:center; padding:10px; background:var(--bg3); border-radius:var(--radius-sm); cursor:pointer;">Ce mois</div>
                    <div class="tr-report-period" onclick="selectReportPeriod('3months')" data-period="3months" style="flex:1; text-align:center; padding:10px; background:var(--bg3); border-radius:var(--radius-sm); cursor:pointer;">3 mois</div>
                    <div class="tr-report-period" onclick="selectReportPeriod('6months')" data-period="6months" style="flex:1; text-align:center; padding:10px; background:var(--bg3); border-radius:var(--radius-sm); cursor:pointer;">6 mois</div>
                    <div class="tr-report-period" onclick="selectReportPeriod('year')" data-period="year" style="flex:1; text-align:center; padding:10px; background:var(--bg3); border-radius:var(--radius-sm); cursor:pointer;">Cette année</div>
                </div>
                <div class="form-group" style="margin-top: 16px;">
                    <label class="form-label">Période personnalisée</label>
                    <div class="tr-2col">
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
                <div class="form-group">
                    <label class="form-label">Pays (optionnel)</label>
                    <select id="reportCountry" class="form-select">
                        <option value="all">Tous</option>
                        <option value="France">France</option>
                        <option value="Canada">Canada</option>
                        <option value="Belgique">Belgique</option>
                        <option value="Suisse">Suisse</option>
                        <option value="Maroc">Maroc</option>
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
// MOCK DATA - TRANSPORT REQUESTS
// ============================================
let demandes = [
    { id:1, numero:'LIV-TRA-20260001', nomEditeur:'Éditions Cérès', matricule:'1234567/A/M/001', pays:'France', nbCartons:15, poidsCarton:12, listeOuvrages:'Collection patrimoine (150 exemplaires); Livres d\'art (50 exemplaires)', dateDepot:'2026-03-10', statut:'validated', shippingCost:450, agentApproved:true, directorSigned:true },
    { id:2, numero:'LIV-TRA-20260002', nomEditeur:'Sud Éditions', matricule:'2345678/B/M/002', pays:'Canada', nbCartons:25, poidsCarton:15, listeOuvrages:'Poésie moderne (200 exemplaires); Anthologies (100 exemplaires)', dateDepot:'2026-03-15', statut:'progress', shippingCost:1250 },
    { id:3, numero:'LIV-TRA-20260003', nomEditeur:'Nirvana Press', matricule:'3456789/C/M/003', pays:'Belgique', nbCartons:10, poidsCarton:8, listeOuvrages:'Collection jeunesse (80 exemplaires); BD (20 exemplaires)', dateDepot:'2026-03-20', statut:'pending', shippingCost:280 },
    { id:4, numero:'LIV-TRA-20260004', nomEditeur:'Dar Al-Kitab', matricule:'5678901/E/M/004', pays:'Maroc', nbCartons:30, poidsCarton:14, listeOuvrages:'Romans (200 exemplaires); Contes du Sahara (100 exemplaires)', dateDepot:'2026-04-01', statut:'sous_reserve', shippingCost:680, sousReserveConditions:'Attestation CNSS à jour', sousReserveDeadline:'2026-05-15' },
    { id:5, numero:'LIV-TRA-20260005', nomEditeur:'Alif Publishing', matricule:'4567890/D/M/005', pays:'Allemagne', nbCartons:8, poidsCarton:10, listeOuvrages:'Livres scolaires (60 exemplaires); Manuels (20 exemplaires)', dateDepot:'2026-03-25', statut:'pending', shippingCost:320 },
    { id:6, numero:'LIV-TRA-20260006', nomEditeur:'Visions Créatives', matricule:'9012345/I/M/006', pays:'Suisse', nbCartons:20, poidsCarton:11, listeOuvrages:'Art contemporain (100 exemplaires); Photographie (80 exemplaires); Design (20 exemplaires)', dateDepot:'2026-04-05', statut:'pending', shippingCost:560 },
    { id:7, numero:'LIV-TRA-20260007', nomEditeur:'Planeta Ediciones', matricule:'6789012/F/M/007', pays:'Espagne', nbCartons:12, poidsCarton:9, listeOuvrages:'Littérature espagnole (80 exemplaires); Traductions (40 exemplaires)', dateDepot:'2026-04-08', statut:'validated', shippingCost:340, agentApproved:true, directorSigned:true },
    { id:8, numero:'LIV-TRA-20260008', nomEditeur:'Étoile du Sahel', matricule:'8901234/H/M/008', pays:'France', nbCartons:45, poidsCarton:13, listeOuvrages:'Histoire régionale (300 exemplaires); Patrimoine (150 exemplaires)', dateDepot:'2026-04-10', statut:'pending', shippingCost:1755 },
    { id:9, numero:'LIV-TRA-20260009', nomEditeur:'Renaissance Books', matricule:'7890123/G/M/009', pays:'Canada', nbCartons:60, poidsCarton:16, listeOuvrages:'Académique (400 exemplaires); Recherche (200 exemplaires)', dateDepot:'2026-04-12', statut:'pending', shippingCost:5280 },
    { id:10, numero:'LIV-TRA-20260010', nomEditeur:'MIAM Publishing', matricule:'5678901/E/M/010', pays:'Belgique', nbCartons:18, poidsCarton:10, listeOuvrages:'Littérature jeunesse (150 exemplaires)', dateDepot:'2026-04-15', statut:'sous_reserve', shippingCost:540, sousReserveConditions:'Justificatif de participation à la foire', sousReserveDeadline:'2026-05-20' }
];

// Shipping cost rates per country (TND per kg)
const shippingRates = {
    'France': 2.5,
    'Belgique': 2.8,
    'Suisse': 3.2,
    'Allemagne': 2.9,
    'Espagne': 2.7,
    'Italie': 2.6,
    'Canada': 5.5,
    'Maroc': 3.8,
    'Tunisie': 2.2,
    'Sénégal': 4.5
};

let trIsGrid = true;
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
function calculateShippingCost(pays, cartons, poidsCarton) {
    const rate = shippingRates[pays] || 3.0;
    const totalWeight = cartons * (poidsCarton || 5);
    return Math.round(totalWeight * rate);
}
function showToast(message, type = 'success') {
    let toast = document.getElementById('tr-toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'tr-toast';
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
    renderAIInsights();
    renderDestinations();
    renderTrendsChart();
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
    let country = document.getElementById('countryFilter')?.value || 'all';
    let volume = document.getElementById('volumeFilter')?.value || 'all';
    let dateStart = document.getElementById('dateStart')?.value;
    let dateEnd = document.getElementById('dateEnd')?.value;

    return demandes.filter(d => {
        let match = true;
        if (search && !d.nomEditeur.toLowerCase().includes(search) && !d.numero.toLowerCase().includes(search) && !d.pays.toLowerCase().includes(search)) match = false;
        if (status !== 'all' && d.statut !== status) match = false;
        if (country !== 'all' && d.pays !== country) match = false;
        if (volume !== 'all') {
            if (volume === 'small' && d.nbCartons >= 10) match = false;
            if (volume === 'medium' && (d.nbCartons < 10 || d.nbCartons > 50)) match = false;
            if (volume === 'large' && d.nbCartons <= 50) match = false;
        }
        if (dateStart && d.dateDepot < dateStart) match = false;
        if (dateEnd && d.dateDepot > dateEnd) match = false;
        return match;
    });
}

function applyAdditionalFilters() {
    renderGridAndTable();
}

function renderGridAndTable() {
    const filtered = getFilteredDemandes();

    // Render Grid
    const gridContainer = document.getElementById('tr-grid');
    gridContainer.innerHTML = filtered.map(d => {
        const stripClass = getStripClass(d.statut);
        const statusLabel = getStatusLabel(d.statut);
        const statusClass = getStatusClass(d.statut);
        const totalWeight = (d.poidsCarton || 5) * d.nbCartons;
        const estimatedCost = calculateShippingCost(d.pays, d.nbCartons, d.poidsCarton || 5);
        const deadlineDays = Math.max(0, calculateDeadlineDays(d.dateDepot));
        const deadlineColor = deadlineDays <= 0 ? '#f87171' : deadlineDays <= 1 ? '#fbbf24' : '#4ade80';
        const deadlineText = deadlineDays <= 0 ? 'Délai dépassé' : `${deadlineDays}j restants`;

        return `
            <div class="tr-card" data-id="${d.id}" onclick="openInspectionModal(${d.id})">
                <div class="tr-card-strip ${stripClass}"></div>
                <div class="tr-card-head">
                    <div class="tr-card-av">${d.nomEditeur.charAt(0)}${d.nomEditeur.split(' ').pop()?.charAt(0) || ''}</div>
                    <div class="tr-card-info">
                        <div class="tr-card-name">${d.nomEditeur}</div>
                        <div class="tr-card-num">${d.numero}</div>
                        <div class="tr-card-meta">📍 ${d.pays} • 📅 ${formatDate(d.dateDepot)}</div>
                    </div>
                    <div class="tr-card-badges">
                        <span class="badge ${statusClass}" style="font-size:10px;">${statusLabel}</span>
                    </div>
                </div>
                <div class="tr-card-body">
                    <div class="tr-card-row">
                        <span class="tr-card-row-icon">📦</span>
                        <span class="tr-card-row-label">Cartons</span>
                        <span class="tr-card-row-val">${d.nbCartons} cartons</span>
                    </div>
                    <div class="tr-card-row">
                        <span class="tr-card-row-icon">⚖️</span>
                        <span class="tr-card-row-label">Poids total</span>
                        <span class="tr-card-row-val">${totalWeight} kg</span>
                    </div>
                    <div class="tr-card-row">
                        <span class="tr-card-row-icon">💰</span>
                        <span class="tr-card-row-label">Frais estimés</span>
                        <span class="tr-card-row-val">${estimatedCost} TND</span>
                    </div>
                </div>
                <div class="tr-deadline-bar">
                    <div class="tr-deadline-row">
                        <span class="tr-deadline-label">⏰ Délai de traitement (3j max)</span>
                        <span class="tr-deadline-days" style="color:${deadlineColor}">${deadlineText}</span>
                    </div>
                    <div class="tr-deadline-track">
                        <div class="tr-deadline-fill" style="width: ${Math.min(100, (3 - deadlineDays) / 3 * 100)}%; background: ${deadlineColor};"></div>
                    </div>
                </div>
                <div class="tr-card-foot" onclick="event.stopPropagation()">
                    <button class="tr-fbt" onclick="openInspectionModal(${d.id})">👁 Inspecter</button>
                    ${d.statut === 'pending' ? `<button class="tr-fbt green" onclick="validateDemande(${d.id})">✓ Approuver</button>` : ''}
                    ${d.statut === 'pending' ? `<button class="tr-fbt amber" onclick="openSousReserveModal(${d.id})">📋 Sous-réserve</button>` : ''}
                    ${d.statut === 'sous_reserve' ? `<button class="tr-fbt green" onclick="openAttestationModal(${d.id})">✍️ Signer attestation</button>` : ''}
                    ${d.statut === 'validated' ? `<button class="tr-fbt purple" onclick="downloadAttestation(${d.id})">📄 Attestation</button>` : ''}
                    <span class="tr-ia-score" onclick="showIARecommendations(${d.id})">🤖 IA</span>
                </div>
            </div>
        `;
    }).join('');

    // Render Table
    const tableBody = document.getElementById('tr-table-body');
    tableBody.innerHTML = filtered.map(d => {
        const totalWeight = (d.poidsCarton || 5) * d.nbCartons;
        const estimatedCost = calculateShippingCost(d.pays, d.nbCartons, d.poidsCarton || 5);
        const deadlineDays = Math.max(0, calculateDeadlineDays(d.dateDepot));
        const deadlineText = deadlineDays <= 0 ? 'Délai dépassé' : `${deadlineDays}j`;
        const deadlineColor = deadlineDays <= 0 ? '#f87171' : deadlineDays <= 1 ? '#fbbf24' : '#4ade80';
        return `
            <tr onclick="openInspectionModal(${d.id})" style="cursor:pointer;">
                <td><strong>${d.numero}</strong></td>
                <td>${d.nomEditeur}</td>
                <td>${d.pays}</td>
                <td>${d.nbCartons}</td>
                <td>${totalWeight} kg</td>
                <td>${estimatedCost} TND</td>
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
    // Pending actions with urgency (deadline <= 1 day)
    const urgentItems = demandes.filter(d => d.statut === 'pending' && calculateDeadlineDays(d.dateDepot) <= 1).slice(0, 5);
    const pendingList = document.getElementById('pendingActionsList');
    pendingList.innerHTML = urgentItems.map(d => `
        <div class="tr-pending-item" onclick="openInspectionModal(${d.id})">
            <div class="tr-pending-av" style="background:rgba(248,113,113,0.15); color:#f87171;">!</div>
            <div class="tr-pending-info">
                <div class="tr-pending-name">${d.nomEditeur}</div>
                <div class="tr-pending-when">🔴 ${calculateDeadlineDays(d.dateDepot)}j restant · ${d.pays} · ${d.nbCartons} cartons</div>
            </div>
        </div>
    `).join('');
    if (urgentItems.length === 0) pendingList.innerHTML = '<div style="padding:15px; text-align:center; color:var(--text3);">✅ Aucune action urgente</div>';
}

function renderDestinations() {
    const container = document.getElementById('destinationList');
    const destCount = {};
    demandes.forEach(d => { destCount[d.pays] = (destCount[d.pays] || 0) + 1; });
    const sorted = Object.entries(destCount).sort((a, b) => b[1] - a[1]);
    const maxCount = sorted[0]?.[1] || 1;

    container.innerHTML = sorted.map(([country, count]) => `
        <div class="tr-destination-item" onclick="filterByCountry('${country}')">
            <span class="tr-destination-name">📍 ${country}</span>
            <div class="tr-destination-bar">
                <div class="tr-destination-fill" style="width: ${(count / maxCount) * 100}%"></div>
            </div>
            <span class="tr-destination-count">${count} exp.</span>
        </div>
    `).join('');
}

function renderTrendsChart() {
    const container = document.getElementById('trendsChart');
    const monthlyData = {};
    demandes.forEach(d => {
        const month = new Date(d.dateDepot).toLocaleDateString('fr-FR', { month: 'short' });
        monthlyData[month] = (monthlyData[month] || 0) + d.nbCartons;
    });
    const months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'];
    const data = months.map(month => ({ label: month, value: monthlyData[month] || 0 }));
    const maxValue = Math.max(...data.map(d => d.value), 1);

    container.innerHTML = `
        <div class="tr-trend-bars">
            ${data.map(item => `
                <div class="tr-trend-bar-item">
                    <div class="tr-trend-bar" style="height: ${(item.value / maxValue) * 100}px; width: 30px;" title="${item.value} cartons"></div>
                    <div class="tr-trend-label">${item.label}</div>
                </div>
            `).join('')}
        </div>
        <div class="tr-trend-summary">
            <span class="tr-trend-up">📈 +23% vs mois dernier</span>
            <span>Moyenne: ${Math.round(data.reduce((s, d) => s + d.value, 0) / data.length)} cartons/mois</span>
        </div>
    `;
}

function renderIAChips() {
    const pendingCount = demandes.filter(d => d.statut === 'pending').length;
    const urgentCount = demandes.filter(d => d.statut === 'pending' && calculateDeadlineDays(d.dateDepot) <= 1).length;
    const totalCartons = demandes.reduce((sum, d) => sum + d.nbCartons, 0);
    const container = document.getElementById('iaChipsContainer');
    container.innerHTML = `
        <div class="tr-chip" style="background:var(--blue-dim); color:var(--blue);" onclick="trFilter(null,'pending')">
            🔍 ${pendingCount} demandes à inspecter
        </div>
        <div class="tr-chip" style="background:var(--red-dim); color:var(--red);" onclick="showUrgentTasks()">
            ⚠️ ${urgentCount} dossiers urgents (délai 3j)
        </div>
        <div class="tr-chip" style="background:var(--green-dim); color:var(--green);" onclick="showToast('Total cartons expédiés: ${totalCartons}', 'info')">
            📦 ${totalCartons} cartons expédiés
        </div>
    `;
}

function renderIASuggestions() {
    const container = document.getElementById('iaSuggestionsContainer');
    container.innerHTML = `
        <div class="tr-sugg-item" onclick="showUrgentTasks()">
            <div class="tr-sugg-icon">⚡</div>
            <div><div class="tr-sugg-text">Traitement prioritaire — dossiers avec délai ≤ 1 jour</div><div class="tr-sugg-cta">→ Voir les urgents</div></div>
        </div>
        <div class="tr-sugg-item" onclick="trFilter(null,'sous_reserve')">
            <div class="tr-sugg-icon">📋</div>
            <div><div class="tr-sugg-text">Dossiers sous-réserve en attente de régularisation</div><div class="tr-sugg-cta">→ Voir les sous-réserve</div></div>
        </div>
        <div class="tr-sugg-item" onclick="openEstimatorModal()">
            <div class="tr-sugg-icon">💰</div>
            <div><div class="tr-sugg-text">Estimer les frais de transport pour une nouvelle demande</div><div class="tr-sugg-cta">→ Calculer</div></div>
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
    const totalCartons = demandes.reduce((sum, d) => sum + d.nbCartons, 0);

    container.innerHTML = `
        <div class="insight-card" onclick="trFilter(null,'pending')" style="cursor:pointer;">
            <div class="insight-value">${pendingCount}</div>
            <div class="insight-label">Demandes en attente</div>
        </div>
        <div class="insight-card" onclick="showUrgentTasks()" style="cursor:pointer;">
            <div class="insight-value" style="color:#f87171;">${urgentCount}</div>
            <div class="insight-label">Urgent (délai 3j)</div>
        </div>
        <div class="insight-card" onclick="showToast('Total cartons expédiés: ${totalCartons}', 'info')" style="cursor:pointer;">
            <div class="insight-value">${totalCartons}</div>
            <div class="insight-label">Cartons expédiés</div>
        </div>
        <div class="insight-card" onclick="quickActionExport()" style="cursor:pointer;">
            <div class="insight-value">${successRate}%</div>
            <div class="insight-label">Taux validation</div>
        </div>
    `;
}

// ============================================
// ESTIMATOR MODAL
// ============================================
function openEstimatorModal() {
    document.getElementById('estCountry').value = '';
    document.getElementById('estCartons').value = '';
    document.getElementById('estWeight').value = '5';
    document.getElementById('estCost').innerHTML = '0 TND';
    document.getElementById('estConfidence').innerHTML = '';
    openModal('estimatorModal');
}

function calculateEstimate() {
    const country = document.getElementById('estCountry').value;
    const cartons = parseInt(document.getElementById('estCartons').value) || 0;
    const weight = parseInt(document.getElementById('estWeight').value) || 5;

    if (!country || cartons === 0) return;

    const rate = shippingRates[country] || 3.0;
    const totalWeight = cartons * weight;
    const estimatedCost = totalWeight * rate;
    const confidence = Math.floor(Math.random() * 15) + 85;

    document.getElementById('estCost').innerHTML = `${Math.round(estimatedCost)} TND`;
    document.getElementById('estConfidence').innerHTML = `Confiance IA: ${confidence}%`;
}

function applyEstimateToForm() {
    const country = document.getElementById('estCountry').value;
    const cartons = parseInt(document.getElementById('estCartons').value) || 0;
    const weight = parseInt(document.getElementById('estWeight').value) || 5;

    if (!country || cartons === 0) {
        showToast('Veuillez sélectionner un pays et saisir le nombre de cartons', 'warning');
        return;
    }

    showToast(`Estimation appliquée: ${country}, ${cartons} cartons, ${weight}kg/carton`, 'success');
    closeModal('estimatorModal');
}

// ============================================
// IA RECOMMENDATIONS
// ============================================
function showIARecommendations(id) {
    const d = demandes.find(x => x.id === id);
    if (!d) return;

    const recommendations = [
        `Optimisation: Regrouper les envois vers ${d.pays} peut réduire les coûts de 15%`,
        `Le délai moyen pour ${d.pays} est de 5-7 jours ouvrés`,
        `Documentation: Vérifier les factures pro forma avant expédition`
    ];

    let tempModal = document.createElement('div');
    tempModal.className = 'modal active';
    tempModal.innerHTML = `
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">
                <div class="modal-title">🤖 IA - Recommandations transport</div>
                <button class="modal-close" onclick="this.closest('.modal').remove()">✕</button>
            </div>
            <div class="modal-body">
                <div class="tr-ia-analyse-panel">
                    <div class="tr-ia-analyse-title">Basé sur l'historique des expéditions</div>
                    ${recommendations.map(rec => `<div class="tr-ia-result-row info">💡 ${rec}</div>`).join('')}
                </div>
                <div class="tr-ia-result-row ok">✓ Coût estimé: ${calculateShippingCost(d.pays, d.nbCartons, d.poidsCarton || 5)} TND</div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="this.closest('.modal').remove()">Fermer</button>
            </div>
        </div>
    `;
    document.body.appendChild(tempModal);
}

function showUrgentTasks() {
    trFilter(null, 'pending');
    showToast('Affichage des dossiers urgents (délai ≤ 1 jour)', 'warning');
}

// ============================================
// INSPECTION MODAL (Agent)
// ============================================
function openInspectionModal(id) {
    const d = demandes.find(x => x.id === id);
    if (!d) return;

    document.getElementById('inspectionNumero').innerText = d.numero;
    const ouvragesList = d.listeOuvrages.split(';').map(o => o.trim());
    const totalWeight = (d.poidsCarton || 5) * d.nbCartons;
    const estimatedCost = calculateShippingCost(d.pays, d.nbCartons, d.poidsCarton || 5);
    const deadlineDays = Math.max(0, calculateDeadlineDays(d.dateDepot));

    document.getElementById('inspectionContent').innerHTML = `
        <div class="tr-ia-analyse-panel">
            <div class="tr-ia-analyse-title">
                🤖 IA — Analyse du dossier
                <div class="tr-ia-dots"><div class="tr-ia-dot"></div><div class="tr-ia-dot"></div><div class="tr-ia-dot"></div></div>
            </div>
            <div class="tr-ia-result-row ${deadlineDays <= 1 ? 'fail' : 'ok'}">
                ${deadlineDays <= 1 ? '⚠️' : '✓'} Délai de traitement: ${deadlineDays <= 0 ? 'Délai dépassé' : `${deadlineDays} jour(s) restant(s)`}
            </div>
            <div class="tr-ia-result-row info">📦 ${d.nbCartons} cartons · ${totalWeight} kg · ${estimatedCost} TND estimés</div>
            <div class="tr-ia-result-row info">📚 ${ouvragesList.length} ouvrages référencés</div>
        </div>

        <div class="tr-form-section">
            <div class="tr-form-section-title">📋 Informations de la demande</div>
            <div class="tr-2col">
                <div><strong>N° Dossier:</strong> ${d.numero}</div>
                <div><strong>Date dépôt:</strong> ${formatDate(d.dateDepot)}</div>
                <div><strong>Éditeur:</strong> ${d.nomEditeur}</div>
                <div><strong>Matricule Fiscal:</strong> ${d.matricule}</div>
                <div><strong>Pays destination:</strong> ${d.pays}</div>
                <div><strong>Cartons:</strong> ${d.nbCartons}</div>
                <div><strong>Poids total:</strong> ${totalWeight} kg</div>
                <div><strong>Frais estimés:</strong> ${estimatedCost} TND</div>
            </div>
        </div>

        <div class="tr-form-section">
            <div class="tr-form-section-title">📚 Liste des ouvrages et nombre de cartons</div>
            <div class="tr-ouvrages-list">
                ${ouvragesList.map(ouvrage => `
                    <div class="tr-ouvrage-item">
                        <div class="tr-ouvrage-title">📖 ${ouvrage}</div>
                    </div>
                `).join('')}
                <div class="tr-ouvrage-item" style="border-top:1px solid var(--border); margin-top:8px; padding-top:8px;">
                    <div class="tr-ouvrage-title"><strong>Total cartons:</strong> ${d.nbCartons}</div>
                </div>
            </div>
        </div>

        <div class="tr-form-section">
            <div class="tr-form-section-title">📝 Vérification agent</div>
            <div class="tr-2col">
                <label style="display:flex; align-items:center; gap:8px;"><input type="checkbox" id="checkContrat"> Contrat avec éditeur conforme</label>
                <label style="display:flex; align-items:center; gap:8px;"><input type="checkbox" id="checkRNE"> RNE à jour</label>
                <label style="display:flex; align-items:center; gap:8px;"><input type="checkbox" id="checkOuvrages"> Liste ouvrages complète</label>
                <label style="display:flex; align-items:center; gap:8px;"><input type="checkbox" id="checkTransport"> Transporteur sélectionné</label>
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
    const totalWeight = (d.poidsCarton || 5) * d.nbCartons;
    const estimatedCost = calculateShippingCost(d.pays, d.nbCartons, d.poidsCarton || 5);

    return `
        <div class="tr-attestation-header">
            <h3>RÉPUBLIQUE TUNISIENNE</h3>
            <p>Ministère des Affaires Culturelles<br>Direction Générale du Livre</p>
            <h4 style="margin-top: 10px;">ATTESTATION DE PRISE EN CHARGE DES FRAIS DE TRANSPORT</h4>
        </div>
        <div class="tr-attestation-body">
            <p>Nous soussigné, Directeur Général du Livre, attestons que l'éditeur :</p>
            <p><strong>${d.nomEditeur}</strong><br>
            <strong>N° Matricule Fiscal :</strong> ${d.matricule}</p>
            <p>bénéficie d'une prise en charge des frais de transport pour l'expédition d'ouvrages vers :</p>
            <p><strong>${d.pays}</strong></p>
            <p>Détails de l'expédition :<br>
            - Nombre de cartons : ${d.nbCartons}<br>
            - Poids total : ${totalWeight} kg<br>
            - Montant pris en charge : ${estimatedCost} TND</p>
            <p>Liste des ouvrages expédiés :<br>
            <em>${d.listeOuvrages.replace(/;/g, ', ')}</em></p>
            <p>La présente attestation est délivrée pour servir et valoir ce que de droit.</p>
            <p>Fait à Tunis, le ${today}</p>
        </div>
        <div class="tr-attestation-footer">
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
        <div class="tr-attestation-preview" id="attestationPreview">
            ${attestationHtml}
        </div>

        <div class="tr-form-section">
            <div class="tr-form-section-title">✏️ Éditer l'attestation (si correction nécessaire)</div>
            <textarea id="attestationEditArea" rows="12" class="form-input" style="font-family: monospace; font-size: 11px;">${attestationHtml.replace(/<[^>]*>/g, '')}</textarea>
        </div>

        <div class="tr-form-section">
            <div class="tr-form-section-title">🖊️ Signature du Directeur</div>
            <div class="tr-signature-area" onclick="simulateSignature()">
                <div id="signaturePreview" class="tr-signature-preview">_________________</div>
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
            <head><title>Attestation_Transport_${d.numero}</title>
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
            <head><title>Attestation_Transport_${d.numero}</title>
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
// VALIDATION & REJECTION
// ============================================
function validateDemande(id) {
    const d = demandes.find(x => x.id === id);
    if (d && d.statut === 'pending') {
        d.attestationContent = generateAttestationContent(d);
        d.statut = 'sous_reserve';
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
    { title: 'Documents incomplets', desc: 'Manque attestation CNSS, registre de commerce...' },
    { title: 'Hors délai', desc: 'Demande déposée après la date limite' },
    { title: 'Informations erronées', desc: 'Matricule fiscal ou adresse invalide' },
    { title: 'Volume non conforme', desc: 'Le nombre de cartons dépasse la limite autorisée' }
];

function openRejectModal(id) {
    currentRejectId = id;
    document.getElementById('rejectionTemplates').innerHTML = rejectionTemplatesList.map(t => `
        <div class="tr-rejection-card" style="padding:10px 12px; background:var(--bg3); border:1px solid var(--border); border-radius:var(--radius-sm); cursor:pointer; margin-bottom:8px;" onclick="selectRejectionTemplate(this, '${t.desc.replace(/'/g, "\\'")}')">
            <div style="font-weight:700; font-size:12px;">${t.title}</div>
            <div style="font-size:10.5px; color:var(--text3);">${t.desc}</div>
        </div>
    `).join('');
    document.getElementById('rejectionReason').value = '';
    document.getElementById('rejectionNotes').value = '';
    openModal('rejectModal');
}

function selectRejectionTemplate(el, reason) {
    document.querySelectorAll('.tr-rejection-card').forEach(c => c.classList.remove('selected'));
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
    document.querySelectorAll('.tr-report-period').forEach(el => el.classList.remove('selected'));
    const selected = document.querySelector(`.tr-report-period[data-period="${period}"]`);
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
    const countryFilter = document.getElementById('reportCountry').value;

    let filtered = [...demandes];
    if (startDate) filtered = filtered.filter(d => d.dateDepot >= startDate);
    if (endDate) filtered = filtered.filter(d => d.dateDepot <= endDate);
    if (statusFilter !== 'all') filtered = filtered.filter(d => d.statut === statusFilter);
    if (countryFilter !== 'all') filtered = filtered.filter(d => d.pays === countryFilter);

    const totalCartons = filtered.reduce((sum, d) => sum + d.nbCartons, 0);
    const totalWeight = filtered.reduce((sum, d) => sum + (d.poidsCarton || 5) * d.nbCartons, 0);
    const totalCost = filtered.reduce((sum, d) => sum + calculateShippingCost(d.pays, d.nbCartons, d.poidsCarton || 5), 0);

    const reportHtml = `
        <html>
        <head><title>Rapport_Transport</title>
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
            <h1>📊 Rapport des demandes de transport</h1>
            <p>Période: ${formatDate(startDate)} au ${formatDate(endDate)} | Généré le: ${new Date().toLocaleString('fr-FR')}</p>
            <div class="summary">
                <strong>Résumé:</strong><br>
                Total demandes: ${filtered.length}<br>
                Total cartons: ${totalCartons}<br>
                Poids total: ${totalWeight} kg<br>
                Coût total estimé: ${totalCost} TND<br>
                À inspecter: ${filtered.filter(d => d.statut === 'pending').length}<br>
                Validées: ${filtered.filter(d => d.statut === 'validated').length}
            </div>
            <table><thead><tr><th>N° Dossier</th><th>Éditeur</th><th>Pays</th><th>Cartons</th><th>Poids</th><th>Coût estimé</th><th>Statut</th></tr></thead>
            <tbody>${filtered.map(d => `<tr><td>${d.numero}</td><td>${d.nomEditeur}</td><td>${d.pays}</td><td>${d.nbCartons}</td><td>${(d.poidsCarton || 5) * d.nbCartons} kg</td><td>${calculateShippingCost(d.pays, d.nbCartons, d.poidsCarton || 5)} TND</td><td>${getStatusLabel(d.statut)}</td></tr>`).join('')}</tbody>
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
    document.getElementById('reportCountry').value = 'all';
    openModal('reportModal');
}

// ============================================
// FILTERS & ACTIONS
// ============================================
function trFilter(el, status) {
    if (el) {
        document.querySelectorAll('.tr-ftab').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
    }
    document.getElementById('statusFilter').value = status;
    renderGridAndTable();
}

function trFilterStatus() {
    document.querySelectorAll('.tr-ftab').forEach(t => t.classList.remove('active'));
    renderGridAndTable();
}

function trSearch(term) { renderGridAndTable(); }

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = 'all';
    document.getElementById('countryFilter').value = 'all';
    document.getElementById('volumeFilter').value = 'all';
    document.getElementById('dateStart').value = '';
    document.getElementById('dateEnd').value = '';
    document.querySelectorAll('.tr-ftab').forEach(t => t.classList.remove('active'));
    document.querySelector('.tr-ftab').classList.add('active');
    renderGridAndTable();
}

function filterByCountry(country) {
    document.getElementById('searchInput').value = country;
    renderGridAndTable();
    showToast(`Filtré par: ${country}`);
}

function trToggleView() {
    trIsGrid = !trIsGrid;
    document.getElementById('tr-grid').style.display = trIsGrid ? '' : 'none';
    document.getElementById('tr-table-view').style.display = trIsGrid ? 'none' : '';
    document.getElementById('tr-view-toggle').textContent = trIsGrid ? '⊞' : '☰';
}

function quickActionExport() {
    console.log('Export:', demandes);
    showToast('Export CSV démarré', 'info');
}

// ============================================
// INITIALIZATION
// ============================================
window.trFilter = trFilter;
window.trToggleView = trToggleView;
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
window.openEstimatorModal = openEstimatorModal;
window.calculateEstimate = calculateEstimate;
window.applyEstimateToForm = applyEstimateToForm;
window.showIARecommendations = showIARecommendations;
window.showUrgentTasks = showUrgentTasks;
window.openReportModal = openReportModal;
window.selectReportPeriod = selectReportPeriod;
window.generateReport = generateReport;
window.previewAttestation = previewAttestation;
window.simulateSignature = simulateSignature;
window.filterByCountry = filterByCountry;
window.applyAdditionalFilters = applyAdditionalFilters;
window.closeModal = closeModal;
window.quickActionExport = quickActionExport;

renderAll();
</script>


@endsection
