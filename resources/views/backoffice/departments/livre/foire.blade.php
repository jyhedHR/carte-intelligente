@extends('shared.layouts.backoffice')

@section('page-title', 'Participation foire internationale - Direction du Livre')
@section('breadcrumb', 'Participation foire internationale')


@section('content')
<style>
/* ════════════════════════════════════════════
   FOIRE INTERNATIONALE — DESIGN SYSTEM
════════════════════════════════════════════ */

/* ── KPI Row ── */
.ff-kpi-row {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 12px;
    margin-bottom: 22px;
}
@media (max-width: 1100px) { .ff-kpi-row { grid-template-columns: repeat(3,1fr); } }
@media (max-width: 700px)  { .ff-kpi-row { grid-template-columns: repeat(2,1fr); } }

.ff-kpi {
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
.ff-kpi:hover { border-color: var(--border2); transform: translateY(-1px); }
.ff-kpi-icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}
.ff-kpi-val  { font-size: 22px; font-weight: 900; font-family: var(--font-mono); line-height: 1; }
.ff-kpi-lbl  { font-size: 10.5px; color: var(--text3); text-transform: uppercase; letter-spacing: 0.6px; margin-top: 3px; }
.ff-kpi-delta{ font-size: 10px; font-family: var(--font-mono); font-weight: 700; margin-top: 3px; }

/* ── IA Smart Banner ── */
.ff-ia-banner {
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
.ff-ia-banner::after {
    content: '🌍';
    position: absolute; right: 24px; top: 50%;
    transform: translateY(-50%);
    font-size: 56px; opacity: 0.06; pointer-events: none;
}
.ff-ia-orb {
    width: 44px; height: 44px; border-radius: 12px;
    background: var(--gold-dim);
    border: 1px solid rgba(201,168,76,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
    animation: ff-orb-pulse 3s ease-in-out infinite;
}
@keyframes ff-orb-pulse {
    0%,100% { box-shadow: 0 0 0 0 rgba(201,168,76,0.35); }
    50%      { box-shadow: 0 0 0 10px rgba(201,168,76,0); }
}
.ff-ia-body { flex: 1; }
.ff-ia-title {
    font-size: 13px; font-weight: 700; color: var(--text);
    margin-bottom: 5px; display: flex;
    align-items: center; gap: 8px;
}
.ff-ia-chips { display: flex; flex-wrap: wrap; gap: 7px; }
.ff-chip {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 11px; border-radius: 20px;
    font-size: 11px; font-weight: 600; cursor: pointer;
    transition: opacity 0.15s;
}
.ff-chip:hover { opacity: 0.8; }

/* ── Main layout ── */
.ff-shell {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 18px;
    align-items: start;
}
@media (max-width: 1060px) { .ff-shell { grid-template-columns: 1fr; } }

/* ── Filter/action bar ── */
.ff-topbar {
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
.ff-filter-tabs { display: flex; gap: 0; flex-wrap: wrap; }
.ff-ftab {
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
.ff-ftab:hover { color: var(--text2); }
.ff-ftab.active { color: var(--gold); border-bottom-color: var(--gold); }

.ff-search {
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
.ff-search:focus { border-color: var(--gold); }
.ff-search::placeholder { color: var(--text3); }

.ff-select {
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
.ff-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 14px;
    margin-bottom: 18px;
}

.ff-card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    cursor: pointer;
    transition: all 0.18s;
    animation: ff-fadein 0.3s ease forwards;
}
@keyframes ff-fadein { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
.ff-card:hover { border-color: var(--border2); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.15); }

.ff-card-strip {
    height: 4px;
    background: linear-gradient(90deg, var(--gold), var(--gold2));
}
.ff-card-strip.urgent { background: linear-gradient(90deg, #f87171, #ef4444); }
.ff-card-strip.warning { background: linear-gradient(90deg, #fbbf24, #f59e0b); }
.ff-card-strip.pending { background: linear-gradient(90deg, #60a5fa, #3b82f6); }
.ff-card-strip.validated { background: linear-gradient(90deg, #4ade80, #22c55e); }
.ff-card-strip.sous_reserve { background: linear-gradient(90deg, #f59e0b, #d97706); }

.ff-card-head {
    padding: 14px 16px 10px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    border-bottom: 1px solid var(--border);
}
.ff-card-av {
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
.ff-card-info { flex: 1; min-width: 0; }
.ff-card-name { font-size: 13.5px; font-weight: 700; color: var(--text); }
.ff-card-num  { font-size: 10px; font-family: var(--font-mono); color: var(--text3); margin-top: 2px; }
.ff-card-meta { font-size: 11px; color: var(--text2); margin-top: 3px; display: flex; align-items: center; gap: 5px; flex-wrap: wrap; }
.ff-card-badges { display: flex; flex-direction: column; align-items: flex-end; gap: 4px; flex-shrink: 0; }

.ff-card-body { padding: 12px 16px; }
.ff-card-row  { display: flex; align-items: center; gap: 8px; margin-bottom: 6px; font-size: 12px; color: var(--text2); }
.ff-card-row-icon { font-size: 13px; flex-shrink: 0; width: 18px; text-align: center; }
.ff-card-row-label { color: var(--text3); min-width: 60px; font-size: 11px; }
.ff-card-row-val   { font-weight: 600; color: var(--text); }

.ff-deadline-bar {
    padding: 8px 16px 12px;
}
.ff-deadline-row { display: flex; align-items: center; gap: 8px; margin-bottom: 4px; }
.ff-deadline-label { font-size: 10px; color: var(--text3); flex: 1; }
.ff-deadline-days  { font-size: 10.5px; font-family: var(--font-mono); font-weight: 700; }
.ff-deadline-track { height: 4px; background: var(--bg4); border-radius: 2px; overflow: hidden; }
.ff-deadline-fill  { height: 100%; border-radius: 2px; transition: width 0.6s ease; }

.ff-card-foot {
    padding: 10px 16px;
    border-top: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}
.ff-fbt {
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
.ff-fbt:hover { background: var(--bg4); color: var(--text); }
.ff-fbt.green { background: var(--green-dim); border-color: rgba(74,222,128,0.25); color: var(--green); }
.ff-fbt.blue  { background: var(--blue-dim);  border-color: rgba(59,130,246,0.25); color: var(--blue); }
.ff-fbt.amber { background: var(--amber-dim); border-color: rgba(251,191,36,0.25); color: var(--amber); }
.ff-fbt.purple{ background: var(--purple-dim); border-color: rgba(167,139,250,0.25); color: var(--purple); }

.ff-ia-score {
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
#ff-table-view .status-badge {
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
.ff-sidebar { display: flex; flex-direction: column; gap: 16px; position: sticky; top: 76px; }

.ff-sb-panel {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
}
.ff-sb-head {
    padding: 12px 15px;
    border-bottom: 1px solid var(--border);
    font-size: 12px;
    font-weight: 700;
    color: var(--text);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

/* Fair Calendar */
.ff-calendar-filters {
    padding: 12px;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    border-bottom: 1px solid var(--border);
}
.ff-cal-filter {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 600;
    cursor: pointer;
    background: var(--bg3);
    border: 1px solid var(--border);
}
.ff-cal-filter.active {
    background: var(--gold-dim);
    border-color: var(--gold);
    color: var(--gold);
}
.ff-calendar-list {
    max-height: 300px;
    overflow-y: auto;
}
.ff-cal-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-bottom: 1px solid var(--border);
    cursor: pointer;
    transition: background 0.15s;
}
.ff-cal-item:hover { background: var(--bg3); }
.ff-cal-date {
    text-align: center;
    min-width: 45px;
    padding: 4px 8px;
    border-radius: 8px;
    background: var(--bg3);
}
.ff-cal-day { font-size: 14px; font-weight: 700; }
.ff-cal-month { font-size: 9px; color: var(--text3); }
.ff-cal-info { flex: 1; }
.ff-cal-name { font-size: 12px; font-weight: 600; }
.ff-cal-country { font-size: 10px; color: var(--text3); }
.ff-cal-status { font-size: 9px; padding: 2px 6px; border-radius: 10px; }

/* Pending items */
.ff-pending-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 14px;
    border-bottom: 1px solid var(--border);
    cursor: pointer;
    transition: background 0.15s;
}
.ff-pending-item:hover { background: var(--bg3); }
.ff-pending-av {
    width: 28px; height: 28px; border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: 700;
    flex-shrink: 0;
}
.ff-pending-info { flex: 1; min-width: 0; }
.ff-pending-name { font-size: 12px; font-weight: 600; color: var(--text); }
.ff-pending-when { font-size: 10.5px; color: var(--text3); }

/* IA suggestions */
.ff-ia-suggestions { display: flex; flex-direction: column; gap: 8px; padding: 10px 14px; }
.ff-sugg-item {
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
.ff-sugg-item:hover { border-color: var(--gold); }
.ff-sugg-icon { font-size: 15px; flex-shrink: 0; }
.ff-sugg-text { font-size: 11.5px; color: var(--text2); line-height: 1.45; flex: 1; }
.ff-sugg-cta  { font-size: 10px; color: var(--gold); font-weight: 700; margin-top: 3px; }

/* Quick actions */
.ff-quick { display: flex; flex-direction: column; gap: 6px; padding: 10px 14px; }
.ff-qa {
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
.ff-qa:hover { background: var(--bg4); color: var(--text); border-color: var(--border2); }

/* ════ MODAL STYLES ════ */
.ff-modal-wide { max-width: 750px; }
.ff-form-section {
    background: var(--bg3);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 14px 16px;
    margin-bottom: 14px;
}
.ff-form-section-title {
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
.ff-2col { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

/* IA Analyse Panel */
.ff-ia-analyse-panel {
    background: linear-gradient(135deg, rgba(167,139,250,0.07), rgba(201,168,76,0.05));
    border: 1px solid rgba(167,139,250,0.2);
    border-radius: var(--radius-sm);
    padding: 14px 16px;
    margin-bottom: 14px;
}
.ff-ia-analyse-title {
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
.ff-ia-dots {
    display: flex;
    align-items: center;
    gap: 3px;
    margin-left: auto;
}
.ff-ia-dot {
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: var(--gold);
    animation: ff-think 1.3s ease-in-out infinite;
}
.ff-ia-dot:nth-child(2) { animation-delay: 0.2s; }
.ff-ia-dot:nth-child(3) { animation-delay: 0.4s; }
@keyframes ff-think { 0%,100% { opacity:0.2; transform:scale(0.8); } 50% { opacity:1; transform:scale(1.2); } }

.ff-ia-result-row {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 7px 10px;
    border-radius: var(--radius-sm);
    margin-bottom: 6px;
    font-size: 12px;
}
.ff-ia-result-row.ok   { background: var(--green-dim); color: var(--green); }
.ff-ia-result-row.fail { background: var(--red-dim);   color: var(--red); }
.ff-ia-result-row.warn { background: var(--amber-dim); color: var(--amber); }
.ff-ia-result-row.info { background: var(--blue-dim);  color: var(--blue); }

/* Ouvrages List */
.ff-ouvrages-list {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 12px;
    max-height: 200px;
    overflow-y: auto;
}
.ff-ouvrage-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px;
    border-bottom: 1px solid var(--border);
}
.ff-ouvrage-item:last-child { border-bottom: none; }
.ff-ouvrage-title { flex: 1; font-size: 12px; }

/* Attestation Preview */
.ff-attestation-preview {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    border: 1px solid var(--gold);
    border-radius: var(--radius);
    padding: 20px;
    margin-bottom: 16px;
    position: relative;
}
.ff-attestation-header {
    text-align: center;
    border-bottom: 2px solid var(--gold);
    padding-bottom: 12px;
    margin-bottom: 16px;
}
.ff-attestation-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: #92400e; }
.ff-attestation-header p { margin: 4px 0 0; font-size: 11px; color: #b45309; }
.ff-attestation-body { font-size: 12px; line-height: 1.6; color: #78350f; }
.ff-attestation-footer {
    margin-top: 20px;
    padding-top: 12px;
    border-top: 1px dashed var(--gold);
    display: flex;
    justify-content: space-between;
    font-size: 10px;
    color: #92400e;
}

/* Signature Area */
.ff-signature-area {
    border: 2px dashed var(--border2);
    border-radius: var(--radius-sm);
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.15s;
}
.ff-signature-area:hover { border-color: var(--gold); }
.ff-signature-preview {
    max-width: 200px;
    margin: 0 auto;
    font-family: 'Brush Script MT', cursive;
    font-size: 24px;
    color: var(--gold);
}

/* Rejection templates */
.ff-rejection-templates {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.ff-rejection-card {
    padding: 10px 12px;
    background: var(--bg3);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: all 0.15s;
}
.ff-rejection-card:hover { border-color: var(--gold); background: var(--bg4); }
.ff-rejection-card.selected { border-color: var(--gold); background: var(--gold-dim); }
.ff-rejection-title { font-weight: 700; font-size: 12px; margin-bottom: 3px; }
.ff-rejection-desc { font-size: 10.5px; color: var(--text3); }

/* Report Modal */
.ff-report-options {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}
.ff-report-period {
    flex: 1;
    text-align: center;
    padding: 10px;
    border-radius: var(--radius-sm);
    background: var(--bg3);
    border: 1px solid var(--border);
    cursor: pointer;
    transition: all 0.15s;
}
.ff-report-period:hover { border-color: var(--gold); }
.ff-report-period.selected { background: var(--gold-dim); border-color: var(--gold); color: var(--gold); font-weight: 700; }

/* Fair Detail Modal */
.ff-fair-detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 16px;
}
.ff-fair-stat {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 12px;
    text-align: center;
}
.ff-fair-stat-value { font-size: 22px; font-weight: 900; color: var(--gold); }
.ff-fair-stat-label { font-size: 10px; color: var(--text3); margin-top: 4px; }
.ff-participant-list {
    max-height: 300px;
    overflow-y: auto;
}
.ff-participant-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    border-bottom: 1px solid var(--border);
}
.ff-participant-badge {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: var(--gold-dim);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: 700;
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
</style>

<div class="page active">
    {{-- ════════ KPI ROW ════════ --}}
    <div class="ff-kpi-row">
        <div class="ff-kpi">
            <div class="ff-kpi-icon" style="background:var(--gold-dim);">🌍</div>
            <div>
                <div class="ff-kpi-val" style="color:var(--gold);" id="kpiTotal">0</div>
                <div class="ff-kpi-lbl">Total demandes</div>
                <div class="ff-kpi-delta" style="color:var(--green);">↑ +12% ce mois</div>
            </div>
        </div>
        <div class="ff-kpi">
            <div class="ff-kpi-icon" style="background:var(--blue-dim);">🔍</div>
            <div>
                <div class="ff-kpi-val" style="color:var(--blue);" id="kpiPending">0</div>
                <div class="ff-kpi-lbl">À inspecter</div>
                <div class="ff-kpi-delta" style="color:var(--amber);">Délai 3j max</div>
            </div>
        </div>
        <div class="ff-kpi">
            <div class="ff-kpi-icon" style="background:var(--amber-dim);">⏳</div>
            <div>
                <div class="ff-kpi-val" style="color:var(--amber);" id="kpiSousReserve">0</div>
                <div class="ff-kpi-lbl">Sous-réserve</div>
                <div class="ff-kpi-delta" style="color:var(--amber);">→ Action requise</div>
            </div>
        </div>
        <div class="ff-kpi">
            <div class="ff-kpi-icon" style="background:var(--green-dim);">✅</div>
            <div>
                <div class="ff-kpi-val" style="color:var(--green);" id="kpiValidated">0</div>
                <div class="ff-kpi-lbl">Validées</div>
                <div class="ff-kpi-delta" style="color:var(--teal);">Taux 68%</div>
            </div>
        </div>
        <div class="ff-kpi">
            <div class="ff-kpi-icon" style="background:var(--purple-dim);">🤖</div>
            <div>
                <div class="ff-kpi-val" style="color:var(--purple);">96%</div>
                <div class="ff-kpi-lbl">Fiabilité IA</div>
                <div class="ff-kpi-delta" style="color:var(--purple);">→ Recommandations</div>
            </div>
        </div>
    </div>

    {{-- ════════ AI FAIR INTELLIGENCE PANEL ════════ --}}
    <div class="ff-ia-banner">
        <div class="ff-ia-orb">🤖</div>
        <div class="ff-ia-body">
            <div class="ff-ia-title">
                AI Fair Intelligence — Analyse des foires internationales
                <span style="font-size:10px; padding:2px 8px; background:var(--gold-dim); color:var(--gold); border-radius:20px; font-weight:700;">LIVE</span>
            </div>
            <div class="ff-ia-chips" id="iaChipsContainer"></div>
        </div>
        <div style="display:flex; gap:8px; flex-shrink:0;">
            <button class="btn-report" onclick="openReportModal()">
                <span class="icon-spark"></span>
                Générer rapport
            </button>
            <button class="btn btn-outline btn-sm" onclick="showToast('Analyse IA complète lancée', 'info')">📊 Rapport IA</button>
        </div>
    </div>

    {{-- ════════ AI INSIGHT GRID ════════ --}}
    <div class="ai-insight-grid" id="aiInsightGrid"></div>

    {{-- ════════ MAIN SHELL ════════ --}}
    <div class="ff-shell">

        {{-- ══ LEFT: CARDS + TABLE ══ --}}
        <div>

            {{-- Filter/Search bar --}}
            <div class="ff-topbar">
                <div class="ff-filter-tabs">
                    <div class="ff-ftab active" onclick="ffFilter(this,'all')">Toutes (<span id="filterCountAll">0</span>)</div>
                    <div class="ff-ftab" onclick="ffFilter(this,'pending')">🔍 À inspecter (<span id="filterCountPending">0</span>)</div>
                    <div class="ff-ftab" onclick="ffFilter(this,'sous_reserve')">📋 Sous-réserve (<span id="filterCountSousReserve">0</span>)</div>
                    <div class="ff-ftab" onclick="ffFilter(this,'validated')">✅ Validées (<span id="filterCountValidated">0</span>)</div>
                    <div class="ff-ftab" onclick="ffFilter(this,'rejected')">❌ Rejetées (<span id="filterCountRejected">0</span>)</div>
                </div>
                <input type="text" class="ff-search" placeholder="🔍 Rechercher par éditeur, foire, pays..." id="searchInput" oninput="ffSearch(this.value)">
                <select class="ff-select" id="statusFilter" onchange="ffFilterStatus()">
                    <option value="all">Tous les statuts</option>
                    <option value="pending">À inspecter</option>
                    <option value="sous_reserve">Sous-réserve</option>
                    <option value="validated">Validé</option>
                    <option value="rejected">Rejeté</option>
                </select>
                <button class="btn btn-outline btn-sm" onclick="resetFilters()">Reset</button>
                <button class="btn btn-outline btn-sm" onclick="ffToggleView()" id="ff-view-toggle" title="Basculer vue">⊞</button>
            </div>

            {{-- CARD GRID VIEW --}}
            <div class="ff-grid" id="ff-grid"></div>

            {{-- TABLE VIEW --}}
            <div id="ff-table-view" style="display:none;" class="panel">
                <div class="panel-head">
                    <div><div class="panel-title">📋 Liste des demandes de participation</div><div class="panel-sub">Vue tabulaire</div></div>
                    <button class="btn btn-outline btn-sm" onclick="quickActionExport()">📥 Exporter CSV</button>
                </div>
                <div class="panel-body no-pad">
                    <div class="table-wrap">
                        <table class="table">
                            <thead>
                                <tr><th>N° Dossier</th><th>Éditeur</th><th>Foire</th><th>Pays</th><th>Date foire</th><th>Délai restant</th><th>Statut</th><th>Actions</th></tr>
                            </thead>
                            <tbody id="ff-table-body"></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        {{-- ══ RIGHT SIDEBAR ══ --}}
        <div class="ff-sidebar">

            {{-- Fair Calendar with Filters --}}
            <div class="ff-sb-panel">
                <div class="ff-sb-head">📅 Calendrier des foires</div>
                <div class="ff-calendar-filters">
                    <button class="ff-cal-filter active" onclick="filterCalendarByRegion('all')">Toutes</button>
                    <button class="ff-cal-filter" onclick="filterCalendarByRegion('europe')">Europe</button>
                    <button class="ff-cal-filter" onclick="filterCalendarByRegion('africa')">Afrique</button>
                    <button class="ff-cal-filter" onclick="filterCalendarByRegion('americas')">Amériques</button>
                    <button class="ff-cal-filter" onclick="filterCalendarByRegion('asia')">Asie</button>
                </div>
                <div class="ff-calendar-list" id="fairCalendarList"></div>
            </div>

            {{-- Pending Actions (Urgent - 3 day deadline) --}}
            <div class="ff-sb-panel">
                <div class="ff-sb-head">⏰ Actions urgentes (délai 3j)</div>
                <div id="pendingActionsList"></div>
            </div>

            {{-- IA Suggestions --}}
            <div class="ff-sb-panel">
                <div class="ff-sb-head">🤖 IA Recommendations</div>
                <div class="ff-ia-suggestions" id="iaSuggestionsContainer"></div>
            </div>

            {{-- Quick actions --}}
            <div class="ff-sb-panel">
                <div class="ff-sb-head">⚡ Actions rapides</div>
                <div class="ff-quick">
                    <button class="ff-qa" onclick="openReportModal()"><span class="icon-spark" style="display:inline-block; width:14px; height:14px;"></span> Générer rapport</button>
                    <button class="ff-qa" onclick="quickActionExport()">📥 Exporter toutes</button>
                    <button class="ff-qa" onclick="showToast('Analyse IA complète lancée', 'info')">🤖 Analyse IA complète</button>
                </div>
            </div>

        </div>
    </div>

    {{-- ════════════════════════════════════════════
         MODAL — FAIR DETAIL (Shows participants)
    ════════════════════════════════════════════ --}}
    <div class="modal" id="fairDetailModal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <div class="modal-title" id="fairDetailTitle">Détails de la foire</div>
                <button class="modal-close" onclick="closeModal('fairDetailModal')">✕</button>
            </div>
            <div class="modal-body" id="fairDetailContent"></div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('fairDetailModal')">Fermer</button>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════
         MODAL — INSPECTION & VALIDATION (Agent)
    ════════════════════════════════════════════ --}}
    <div class="modal" id="inspectionModal">
        <div class="modal-content ff-modal-wide">
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
                <div class="ff-ia-result-row info">ℹ️ L'IA suivra ce dossier et enverra des rappels à la date limite</div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('sousReserveModal')">Annuler</button>
                <button class="btn btn-amber" onclick="confirmSousReserve()">Confirmer sous-réserve</button>
            </div>
        </div>
    </div>

    {{-- MODAL — ATTESTATION (Director Signature) --}}
    <div class="modal" id="attestationModal">
        <div class="modal-content ff-modal-wide">
            <div class="modal-header">
                <div class="modal-title">✍️ Attestation de participation — Signature du Directeur</div>
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
                <div class="ff-rejection-templates" id="rejectionTemplates"></div>
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
                <div class="ff-report-options">
                    <div class="ff-report-period" onclick="selectReportPeriod('day')" data-period="day">Aujourd'hui</div>
                    <div class="ff-report-period" onclick="selectReportPeriod('week')" data-period="week">Cette semaine</div>
                    <div class="ff-report-period" onclick="selectReportPeriod('month')" data-period="month">Ce mois</div>
                    <div class="ff-report-period" onclick="selectReportPeriod('3months')" data-period="3months">3 mois</div>
                    <div class="ff-report-period" onclick="selectReportPeriod('6months')" data-period="6months">6 mois</div>
                    <div class="ff-report-period" onclick="selectReportPeriod('year')" data-period="year">Cette année</div>
                </div>
                <div class="form-group" style="margin-top: 16px;">
                    <label class="form-label">Période personnalisée</label>
                    <div class="ff-2col">
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
// MOCK DATA - FAIR PARTICIPATIONS
// ============================================
let demandes = [
    { id:1, numero:'LIV-FOI-20260001', nomEditeur:'Éditions Cérès', matricule:'1234567/A/M/001', foireNom:'Salon du Livre de Paris', pays:'France', dateDebut:'2026-04-15', dateFin:'2026-04-20', listeOuvrages:'Collection patrimoine; Livres d\'art; Histoire de la Tunisie', dateDepot:'2026-03-10', statut:'validated', daysUntil:2, deadlineDays: 2, deadlineColor: 'green', agentApproved: true, directorSigned: true, attestationGenerated: true },
    { id:2, numero:'LIV-FOI-20260002', nomEditeur:'Sud Éditions', matricule:'2345678/B/M/002', foireNom:'Foire de Francfort', pays:'Allemagne', dateDebut:'2026-10-18', dateFin:'2026-10-22', listeOuvrages:'Littérature maghrébine; Poésie contemporaine; Anthologies', dateDepot:'2026-03-15', statut:'progress', daysUntil:188, deadlineDays: 188, deadlineColor: 'green' },
    { id:3, numero:'LIV-FOI-20260003', nomEditeur:'Nirvana Press', matricule:'3456789/C/M/003', foireNom:'Salon du Livre de Montréal', pays:'Canada', dateDebut:'2026-11-20', dateFin:'2026-11-25', listeOuvrages:'Bandes dessinées; Romans jeunesse; Livres illustrés', dateDepot:'2026-03-20', statut:'pending', daysUntil:221, deadlineDays: 2, deadlineColor: 'red' },
    { id:4, numero:'LIV-FOI-20260004', nomEditeur:'Alif Publishing', matricule:'4567890/D/M/004', foireNom:'Foire de Bologne', pays:'Italie', dateDebut:'2026-03-30', dateFin:'2026-04-02', listeOuvrages:'Livres scolaires; Manuels pédagogiques', dateDepot:'2026-03-25', statut:'pending', daysUntil:-14, deadlineDays: 1, deadlineColor: 'red' },
    { id:5, numero:'LIV-FOI-20260005', nomEditeur:'Dar Al-Kitab', matricule:'5678901/E/M/005', foireNom:'Cairo International Book Fair', pays:'Égypte', dateDebut:'2026-01-25', dateFin:'2026-02-05', listeOuvrages:'Romans; Contes du Sahara; Littérature arabe classique', dateDepot:'2026-01-10', statut:'validated', daysUntil:-98, deadlineDays: 0, deadlineColor: 'green', agentApproved: true, directorSigned: true },
    { id:6, numero:'LIV-FOI-20260006', nomEditeur:'Planeta Ediciones', matricule:'6789012/F/M/006', foireNom:'Foire de Madrid', pays:'Espagne', dateDebut:'2026-05-10', dateFin:'2026-05-15', listeOuvrages:'Littérature espagnole; Traductions; Livres bilingues', dateDepot:'2026-04-01', statut:'sous_reserve', daysUntil:27, deadlineDays: 27, deadlineColor: 'yellow', sousReserveConditions: 'Attestation CNSS à jour', sousReserveDeadline: '2026-05-01' },
    { id:7, numero:'LIV-FOI-20260007', nomEditeur:'Visions Créatives', matricule:'9012345/I/M/007', foireNom:'Salon du Livre de Londres', pays:'Royaume-Uni', dateDebut:'2026-06-20', dateFin:'2026-06-25', listeOuvrages:'Art contemporain; Photographie; Design graphique', dateDepot:'2026-04-05', statut:'pending', daysUntil:68, deadlineDays: 2, deadlineColor: 'red' }
];

// International Fairs Database
const internationalFairs = [
    { name: 'Salon du Livre de Paris', country: 'France', region: 'europe', date: '2026-04-15', endDate: '2026-04-20', attendees: 300000, exhibitors: 1200, description: 'Le plus grand salon du livre en France, rendez-vous incontournable des professionnels de l\'édition.' },
    { name: 'Foire de Francfort', country: 'Allemagne', region: 'europe', date: '2026-10-18', endDate: '2026-10-22', attendees: 280000, exhibitors: 7500, description: 'La plus grande foire du livre au monde, référence internationale pour les droits d\'édition.' },
    { name: 'Cairo International Book Fair', country: 'Égypte', region: 'africa', date: '2026-01-25', endDate: '2026-02-05', attendees: 200000, exhibitors: 800, description: 'La plus ancienne et la plus grande foire du livre dans le monde arabe.' },
    { name: 'Salon du Livre de Montréal', country: 'Canada', region: 'americas', date: '2026-11-20', endDate: '2026-11-25', attendees: 150000, exhibitors: 600, description: 'Événement majeur de la francophonie nord-américaine.' },
    { name: 'Foire de Bologne', country: 'Italie', region: 'europe', date: '2026-03-30', endDate: '2026-04-02', attendees: 120000, exhibitors: 500, description: 'Spécialisée dans la littérature jeunesse et l\'illustration.' },
    { name: 'Foire de Madrid', country: 'Espagne', region: 'europe', date: '2026-05-10', endDate: '2026-05-15', attendees: 100000, exhibitors: 400, description: 'Plateforme majeure pour l\'édition espagnole et latino-américaine.' },
    { name: 'Salon du Livre de Londres', country: 'Royaume-Uni', region: 'europe', date: '2026-06-20', endDate: '2026-06-25', attendees: 130000, exhibitors: 500, description: 'Un des plus importants salons du livre au Royaume-Uni.' },
    { name: 'Tokyo International Book Fair', country: 'Japon', region: 'asia', date: '2026-07-10', endDate: '2026-07-13', attendees: 80000, exhibitors: 300, description: 'La plus grande foire du livre en Asie.' },
    { name: 'Sharjah International Book Fair', country: 'EAU', region: 'africa', date: '2026-11-01', endDate: '2026-11-11', attendees: 250000, exhibitors: 1000, description: 'Troisième plus grande foire du livre au monde.' }
];

let ffIsGrid = true;
let currentRejectId = null;
let currentSousReserveId = null;
let currentAttestationId = null;
let selectedReportPeriod = 'month';
let calendarRegionFilter = 'all';

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
function showToast(message, type = 'success') {
    let toast = document.getElementById('ff-toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'ff-toast';
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

// Calculate deadline days (3 days from deposit)
function calculateDeadlineDays(dateDepot) {
    const deposit = new Date(dateDepot);
    const today = new Date();
    const diffDays = Math.ceil((deposit - today) / (1000 * 60 * 60 * 24));
    const daysLeft = 3 - (3 - diffDays);
    return Math.max(0, daysLeft);
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
    renderFairCalendar();
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
    return demandes.filter(d =>
        (d.nomEditeur.toLowerCase().includes(search) || d.numero.toLowerCase().includes(search) || d.foireNom.toLowerCase().includes(search) || d.pays.toLowerCase().includes(search)) &&
        (status === 'all' || d.statut === status)
    );
}

function renderGridAndTable() {
    const filtered = getFilteredDemandes();

    // Render Grid
    const gridContainer = document.getElementById('ff-grid');
    gridContainer.innerHTML = filtered.map(d => {
        const stripClass = getStripClass(d.statut);
        const statusLabel = getStatusLabel(d.statut);
        const statusClass = getStatusClass(d.statut);
        const daysUntil = d.daysUntil;
        const daysText = daysUntil <= 0 ? 'Terminée' : daysUntil <= 30 ? `J-${daysUntil}` : `J+${daysUntil}`;
        const daysColor = daysUntil <= 0 ? '#f87171' : daysUntil <= 30 ? '#fbbf24' : '#4ade80';

        // Calculate deadline (3 days from deposit)
        const deadlineDays = calculateDeadlineDays(d.dateDepot);
        const deadlineColor = deadlineDays <= 0 ? '#f87171' : deadlineDays <= 1 ? '#fbbf24' : '#4ade80';
        const deadlineText = deadlineDays <= 0 ? 'Délai dépassé' : `${deadlineDays}j restants`;

        return `
            <div class="ff-card" data-id="${d.id}" onclick="openInspectionModal(${d.id})">
                <div class="ff-card-strip ${stripClass}"></div>
                <div class="ff-card-head">
                    <div class="ff-card-av">${d.nomEditeur.charAt(0)}${d.nomEditeur.split(' ').pop()?.charAt(0) || ''}</div>
                    <div class="ff-card-info">
                        <div class="ff-card-name">${d.nomEditeur}</div>
                        <div class="ff-card-num">${d.numero}</div>
                        <div class="ff-card-meta">📍 ${d.pays} • 📅 ${formatDate(d.dateDebut)}</div>
                    </div>
                    <div class="ff-card-badges">
                        <span class="badge ${statusClass}" style="font-size:10px;">${statusLabel}</span>
                    </div>
                </div>
                <div class="ff-card-body">
                    <div class="ff-card-row">
                        <span class="ff-card-row-icon">📚</span>
                        <span class="ff-card-row-label">Foire</span>
                        <span class="ff-card-row-val">${d.foireNom}</span>
                    </div>
                    <div class="ff-card-row">
                        <span class="ff-card-row-icon">📖</span>
                        <span class="ff-card-row-label">Ouvrages</span>
                        <span class="ff-card-row-val">${d.listeOuvrages.substring(0, 40)}${d.listeOuvrages.length > 40 ? '...' : ''}</span>
                    </div>
                </div>
                <div class="ff-deadline-bar">
                    <div class="ff-deadline-row">
                        <span class="ff-deadline-label">⏰ Délai de traitement (3j max)</span>
                        <span class="ff-deadline-days" style="color:${deadlineColor}">${deadlineText}</span>
                    </div>
                    <div class="ff-deadline-track">
                        <div class="ff-deadline-fill" style="width: ${Math.min(100, (3 - deadlineDays) / 3 * 100)}%; background: ${deadlineColor};"></div>
                    </div>
                </div>
                <div class="ff-card-foot" onclick="event.stopPropagation()">
                    <button class="ff-fbt" onclick="openInspectionModal(${d.id})">👁 Inspecter</button>
                    ${d.statut === 'pending' ? `<button class="ff-fbt green" onclick="validateDemande(${d.id})">✓ Approuver</button>` : ''}
                    ${d.statut === 'pending' ? `<button class="ff-fbt amber" onclick="openSousReserveModal(${d.id})">📋 Sous-réserve</button>` : ''}
                    ${d.statut === 'sous_reserve' ? `<button class="ff-fbt green" onclick="openAttestationModal(${d.id})">✍️ Signer attestation</button>` : ''}
                    ${d.statut === 'validated' ? `<button class="ff-fbt purple" onclick="downloadAttestation(${d.id})">📄 Attestation</button>` : ''}
                    <span class="ff-ia-score" onclick="showIARecommendations(${d.id})">🤖 IA</span>
                </div>
            </div>
        `;
    }).join('');

    // Render Table
    const tableBody = document.getElementById('ff-table-body');
    tableBody.innerHTML = filtered.map(d => {
        const deadlineDays = calculateDeadlineDays(d.dateDepot);
        const deadlineText = deadlineDays <= 0 ? 'Délai dépassé' : `${deadlineDays}j`;
        const deadlineColor = deadlineDays <= 0 ? '#f87171' : deadlineDays <= 1 ? '#fbbf24' : '#4ade80';
        return `
            <tr onclick="openInspectionModal(${d.id})" style="cursor:pointer;">
                <td><strong>${d.numero}</strong></td>
                <td>${d.nomEditeur}</td>
                <td>${d.foireNom}</td>
                <td>${d.pays}</td>
                <td>${formatDate(d.dateDebut)}</td>
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
        <div class="ff-pending-item" onclick="openInspectionModal(${d.id})">
            <div class="ff-pending-av" style="background:rgba(248,113,113,0.15); color:#f87171;">!</div>
            <div class="ff-pending-info">
                <div class="ff-pending-name">${d.nomEditeur}</div>
                <div class="ff-pending-when">🔴 ${calculateDeadlineDays(d.dateDepot)}j restant · ${d.foireNom}</div>
            </div>
        </div>
    `).join('');
    if (urgentItems.length === 0) pendingList.innerHTML = '<div style="padding:15px; text-align:center; color:var(--text3);">✅ Aucune action urgente</div>';
}

function renderFairCalendar() {
    const container = document.getElementById('fairCalendarList');
    let filteredFairs = [...internationalFairs];
    if (calendarRegionFilter !== 'all') {
        filteredFairs = filteredFairs.filter(f => f.region === calendarRegionFilter);
    }
    filteredFairs.sort((a, b) => new Date(a.date) - new Date(b.date));

    container.innerHTML = filteredFairs.map(fair => {
        const fairDate = new Date(fair.date);
        const participants = demandes.filter(d => d.foireNom === fair.name).length;
        return `
            <div class="ff-cal-item" onclick="showFairDetail('${fair.name}')">
                <div class="ff-cal-date">
                    <div class="ff-cal-day">${fairDate.getDate()}</div>
                    <div class="ff-cal-month">${fairDate.toLocaleDateString('fr-FR', { month: 'short' })}</div>
                </div>
                <div class="ff-cal-info">
                    <div class="ff-cal-name">${fair.name}</div>
                    <div class="ff-cal-country">📍 ${fair.country}</div>
                </div>
                <div class="ff-cal-status" style="background:rgba(201,168,76,0.15); color:var(--gold);">${participants} participant${participants > 1 ? 's' : ''}</div>
            </div>
        `;
    }).join('');
}

function filterCalendarByRegion(region) {
    calendarRegionFilter = region;
    document.querySelectorAll('.ff-cal-filter').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    renderFairCalendar();
}

function showFairDetail(fairName) {
    const fair = internationalFairs.find(f => f.name === fairName);
    const participants = demandes.filter(d => d.foireNom === fairName);
    const fairDate = new Date(fair.date);

    document.getElementById('fairDetailTitle').innerHTML = `📅 ${fair.name}`;
    document.getElementById('fairDetailContent').innerHTML = `
        <div class="ff-fair-detail-grid">
            <div class="ff-fair-stat"><div class="ff-fair-stat-value">${fair.country}</div><div class="ff-fair-stat-label">Pays</div></div>
            <div class="ff-fair-stat"><div class="ff-fair-stat-value">${fairDate.toLocaleDateString('fr-FR')}</div><div class="ff-fair-stat-label">Date début</div></div>
            <div class="ff-fair-stat"><div class="ff-fair-stat-value">${(fair.attendees / 1000).toFixed(0)}k</div><div class="ff-fair-stat-label">Visiteurs</div></div>
            <div class="ff-fair-stat"><div class="ff-fair-stat-value">${fair.exhibitors}</div><div class="ff-fair-stat-label">Exposants</div></div>
        </div>
        <div class="ff-form-section">
            <div class="ff-form-section-title">📝 Description</div>
            <p style="font-size:12px; line-height:1.5;">${fair.description}</p>
        </div>
        <div class="ff-form-section">
            <div class="ff-form-section-title">👥 Participants tunisiens (${participants.length})</div>
            <div class="ff-participant-list">
                ${participants.map(p => `
                    <div class="ff-participant-item" onclick="openInspectionModal(${p.id})">
                        <div class="ff-participant-badge">${p.nomEditeur.charAt(0)}</div>
                        <div style="flex:1;">
                            <div style="font-weight:600; font-size:12px;">${p.nomEditeur}</div>
                            <div style="font-size:10px; color:var(--text3);">${p.numero} · ${getStatusLabel(p.statut)}</div>
                        </div>
                        <span class="badge ${getStatusClass(p.statut)}" style="font-size:9px;">${getStatusLabel(p.statut)}</span>
                    </div>
                `).join('')}
                ${participants.length === 0 ? '<div style="padding:20px; text-align:center; color:var(--text3);">Aucun participant tunisien pour cette foire</div>' : ''}
            </div>
        </div>
    `;
    openModal('fairDetailModal');
}

function renderIAChips() {
    const pendingCount = demandes.filter(d => d.statut === 'pending').length;
    const urgentCount = demandes.filter(d => d.statut === 'pending' && calculateDeadlineDays(d.dateDepot) <= 1).length;
    const container = document.getElementById('iaChipsContainer');
    container.innerHTML = `
        <div class="ff-chip" style="background:var(--blue-dim); color:var(--blue);" onclick="ffFilter(null,'pending')">
            🔍 ${pendingCount} demandes à inspecter
        </div>
        <div class="ff-chip" style="background:var(--red-dim); color:var(--red);" onclick="showUrgentTasks()">
            ⚠️ ${urgentCount} dossiers urgents (délai 3j)
        </div>
        <div class="ff-chip" style="background:var(--purple-dim); color:var(--purple);" onclick="showToast('Analyse des foires recommandées', 'info')">
            🎯 5 foires recommandées
        </div>
    `;
}

function renderIASuggestions() {
    const container = document.getElementById('iaSuggestionsContainer');
    container.innerHTML = `
        <div class="ff-sugg-item" onclick="showUrgentTasks()">
            <div class="ff-sugg-icon">⚡</div>
            <div><div class="ff-sugg-text">Traitement prioritaire — dossiers avec délai ≤ 1 jour</div><div class="ff-sugg-cta">→ Voir les urgents</div></div>
        </div>
        <div class="ff-sugg-item" onclick="ffFilter(null,'sous_reserve')">
            <div class="ff-sugg-icon">📋</div>
            <div><div class="ff-sugg-text">Dossiers sous-réserve en attente de régularisation</div><div class="ff-sugg-cta">→ Voir les sous-réserve</div></div>
        </div>
        <div class="ff-sugg-item" onclick="openReportModal()">
            <div class="ff-sugg-icon">📊</div>
            <div><div class="ff-sugg-text">Générer un rapport d'activité personnalisé</div><div class="ff-sugg-cta">→ Créer rapport PDF</div></div>
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

    container.innerHTML = `
        <div class="insight-card" onclick="ffFilter(null,'pending')" style="cursor:pointer;">
            <div class="insight-value">${pendingCount}</div>
            <div class="insight-label">Demandes en attente</div>
        </div>
        <div class="insight-card" onclick="showUrgentTasks()" style="cursor:pointer;">
            <div class="insight-value" style="color:#f87171;">${urgentCount}</div>
            <div class="insight-label">Urgent (délai 3j)</div>
        </div>
        <div class="insight-card" onclick="ffFilter(null,'sous_reserve')" style="cursor:pointer;">
            <div class="insight-value" style="color:#f59e0b;">${demandes.filter(d => d.statut === 'sous_reserve').length}</div>
            <div class="insight-label">Sous-réserve</div>
        </div>
        <div class="insight-card" onclick="quickActionExport()" style="cursor:pointer;">
            <div class="insight-value">${successRate}%</div>
            <div class="insight-label">Taux validation</div>
        </div>
    `;
}

// ============================================
// IA RECOMMENDATIONS (in modal)
// ============================================
function showIARecommendations(id) {
    const d = demandes.find(x => x.id === id);
    if (!d) return;

    // Find similar fairs based on publisher's past participations
    const similarFairs = internationalFairs
        .filter(f => f.name !== d.foireNom && new Date(f.date) > new Date())
        .map(fair => {
            let score = 70;
            if (fair.country === d.pays) score += 10;
            if (fair.name.toLowerCase().includes(d.listeOuvrages.substring(0, 20).toLowerCase())) score += 5;
            return { ...fair, score: Math.min(score, 98) };
        })
        .sort((a, b) => b.score - a.score)
        .slice(0, 3);

    showToast(`IA: 3 recommandations disponibles pour ${d.nomEditeur}`, 'info');

    // Show recommendations in a dialog
    const recosHtml = similarFairs.map(f => `
        <div style="padding:10px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
            <div><strong>${f.name}</strong><br><span style="font-size:10px; color:var(--text3);">📍 ${f.country} · ${formatDate(f.date)}</span></div>
            <span style="background:var(--purple-dim); padding:4px 8px; border-radius:12px; font-size:10px;">${f.score}% match</span>
        </div>
    `).join('');

    // Create temporary modal
    let tempModal = document.createElement('div');
    tempModal.className = 'modal active';
    tempModal.innerHTML = `
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">
                <div class="modal-title">🤖 IA - Recommandations pour ${d.nomEditeur}</div>
                <button class="modal-close" onclick="this.closest('.modal').remove()">✕</button>
            </div>
            <div class="modal-body">
                <div class="ff-ia-analyse-panel">
                    <div class="ff-ia-analyse-title">Basé sur l'historique et le profil de l'éditeur</div>
                    ${recosHtml}
                </div>
                <div class="ff-ia-result-row info">ℹ️ Ces foires correspondent aux genres littéraires de l'éditeur</div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="this.closest('.modal').remove()">Fermer</button>
            </div>
        </div>
    `;
    document.body.appendChild(tempModal);
}

function showUrgentTasks() {
    ffFilter(null, 'pending');
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
    const deadlineDays = calculateDeadlineDays(d.dateDepot);
    const iaRecommendations = getIARecommendationsForRequest(d);

    document.getElementById('inspectionContent').innerHTML = `
        <div class="ff-ia-analyse-panel">
            <div class="ff-ia-analyse-title">
                🤖 IA — Analyse du dossier
                <div class="ff-ia-dots"><div class="ff-ia-dot"></div><div class="ff-ia-dot"></div><div class="ff-ia-dot"></div></div>
            </div>
            <div class="ff-ia-result-row ${deadlineDays <= 1 ? 'fail' : 'ok'}">
                ${deadlineDays <= 1 ? '⚠️' : '✓'} Délai de traitement: ${deadlineDays <= 0 ? 'Délai dépassé' : `${deadlineDays} jour(s) restant(s)`}
            </div>
            <div class="ff-ia-result-row info">📚 ${ouvragesList.length} ouvrages à présenter</div>
            <div class="ff-ia-result-row info">🎯 ${iaRecommendations}</div>
        </div>

        <div class="ff-form-section">
            <div class="ff-form-section-title">📋 Informations de la demande</div>
            <div class="ff-2col">
                <div><strong>N° Dossier:</strong> ${d.numero}</div>
                <div><strong>Date dépôt:</strong> ${formatDate(d.dateDepot)}</div>
                <div><strong>Éditeur:</strong> ${d.nomEditeur}</div>
                <div><strong>Matricule Fiscal:</strong> ${d.matricule}</div>
                <div><strong>Foire:</strong> ${d.foireNom}</div>
                <div><strong>Pays:</strong> ${d.pays}</div>
                <div><strong>Dates:</strong> ${formatDate(d.dateDebut)} - ${formatDate(d.dateFin)}</div>
            </div>
        </div>

        <div class="ff-form-section">
            <div class="ff-form-section-title">📚 Liste des ouvrages à présenter</div>
            <div class="ff-ouvrages-list">
                ${ouvragesList.map(ouvrage => `
                    <div class="ff-ouvrage-item">
                        <div class="ff-ouvrage-title">📖 ${ouvrage}</div>
                    </div>
                `).join('')}
            </div>
            <div class="ff-ia-result-row info" style="margin-top:8px;">ℹ️ L'IA recommande de vérifier la pertinence des ouvrages pour la foire cible</div>
        </div>

        <div class="ff-form-section">
            <div class="ff-form-section-title">📝 Vérification agent</div>
            <div class="ff-2col">
                <label style="display:flex; align-items:center; gap:8px;"><input type="checkbox" id="checkContrat"> Contrat avec éditeur conforme</label>
                <label style="display:flex; align-items:center; gap:8px;"><input type="checkbox" id="checkRNE"> RNE à jour</label>
                <label style="display:flex; align-items:center; gap:8px;"><input type="checkbox" id="checkDemande"> Demande officielle complète</label>
                <label style="display:flex; align-items:center; gap:8px;"><input type="checkbox" id="checkOuvrages"> Ouvrages pertinents pour la foire</label>
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

function getIARecommendationsForRequest(d) {
    // Simulate AI recommendations based on publisher and fair
    const recommendations = [
        "Cette foire correspond au profil éditorial",
        "L'éditeur a participé à des événements similaires",
        "Potentiel de droits d'exportation élevé"
    ];
    return recommendations[Math.floor(Math.random() * recommendations.length)];
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
    return `
        <div class="ff-attestation-header">
            <h3>RÉPUBLIQUE TUNISIENNE</h3>
            <p>Ministère des Affaires Culturelles<br>Direction Générale du Livre</p>
            <h4 style="margin-top: 10px;">ATTESTATION DE PARTICIPATION À UNE FOIRE INTERNATIONALE</h4>
        </div>
        <div class="ff-attestation-body">
            <p>Nous soussigné, Directeur Général du Livre, attestons que l'éditeur :</p>
            <p><strong>${d.nomEditeur}</strong><br>
            <strong>N° Matricule Fiscal :</strong> ${d.matricule}<br>
            <strong>Gérant :</strong> ${d.nomGerant || 'Non renseigné'}</p>
            <p>a été autorisé à participer à la foire internationale :</p>
            <p><strong>${d.foireNom}</strong> à <strong>${d.pays}</strong><br>
            du <strong>${formatDate(d.dateDebut)}</strong> au <strong>${formatDate(d.dateFin)}</strong></p>
            <p>Liste des ouvrages présentés :<br>
            <em>${d.listeOuvrages.replace(/;/g, ', ')}</em></p>
            <p>La présente attestation est délivrée pour servir et valoir ce que de droit.</p>
            <p>Fait à Tunis, le ${today}</p>
        </div>
        <div class="ff-attestation-footer">
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
        <div class="ff-attestation-preview" id="attestationPreview">
            ${attestationHtml}
        </div>

        <div class="ff-form-section">
            <div class="ff-form-section-title">✏️ Éditer l'attestation (si correction nécessaire)</div>
            <textarea id="attestationEditArea" rows="12" class="form-input" style="font-family: monospace; font-size: 11px;">${attestationHtml.replace(/<[^>]*>/g, '')}</textarea>
        </div>

        <div class="ff-form-section">
            <div class="ff-form-section-title">🖊️ Signature du Directeur</div>
            <div class="ff-signature-area" onclick="simulateSignature()">
                <div id="signaturePreview" class="ff-signature-preview">_________________</div>
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
            <head><title>Attestation_${d.numero}</title>
            <style>body { font-family: Arial, sans-serif; padding: 40px; }</style>
            </head>
            <body>${d.attestationContent}</body>
            </html>
        `);
        win.document.close();
        win.print();
        showToast(`Attestation ${d.numero} téléchargée`, 'success');
    } else if (d && d.statut === 'validated') {
        // Generate on the fly
        const content = generateAttestationContent(d);
        const win = window.open();
        win.document.write(`
            <html>
            <head><title>Attestation_${d.numero}</title>
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
        // Generate attestation automatically
        d.attestationContent = generateAttestationContent(d);
        d.statut = 'sous_reserve'; // Wait for director signature
        d.agentApproved = true;
        d.agentApprovedAt = new Date().toISOString();
        renderAll();
        closeModal('inspectionModal');
        showToast(`Demande ${d.numero} approuvée par l'agent. Signature directeur requise.`, 'success');

        // Open attestation modal for director
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
    { title: 'Foire non éligible', desc: 'Cette foire ne figure pas dans la liste des foires soutenues' },
    { title: 'Hors délai', desc: 'Demande déposée après la date limite' },
    { title: 'Ouvrages non conformes', desc: 'La liste des ouvrages ne correspond pas aux critères' }
];

function openRejectModal(id) {
    currentRejectId = id;
    document.getElementById('rejectionTemplates').innerHTML = rejectionTemplatesList.map(t => `
        <div class="ff-rejection-card" onclick="selectRejectionTemplate(this, '${t.desc.replace(/'/g, "\\'")}')">
            <div class="ff-rejection-title">${t.title}</div>
            <div class="ff-rejection-desc">${t.desc}</div>
        </div>
    `).join('');
    document.getElementById('rejectionReason').value = '';
    document.getElementById('rejectionNotes').value = '';
    openModal('rejectModal');
}

function selectRejectionTemplate(el, reason) {
    document.querySelectorAll('.ff-rejection-card').forEach(c => c.classList.remove('selected'));
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
    document.querySelectorAll('.ff-report-period').forEach(el => el.classList.remove('selected'));
    document.querySelector(`.ff-report-period[data-period="${period}"]`).classList.add('selected');

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
        <head><title>Rapport_Participations_Foires</title>
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
            <h1>📊 Rapport des participations aux foires internationales</h1>
            <p>Période: ${formatDate(startDate)} au ${formatDate(endDate)} | Généré le: ${new Date().toLocaleString('fr-FR')}</p>
            <div class="summary">
                <strong>Résumé:</strong><br>
                Total demandes: ${filtered.length}<br>
                À inspecter: ${filtered.filter(d => d.statut === 'pending').length}<br>
                Sous-réserve: ${filtered.filter(d => d.statut === 'sous_reserve').length}<br>
                Validées: ${filtered.filter(d => d.statut === 'validated').length}<br>
                Rejetées: ${filtered.filter(d => d.statut === 'rejected').length}
            </div>
            <table><thead><tr><th>N° Dossier</th><th>Éditeur</th><th>Foire</th><th>Pays</th><th>Statut</th></tr></thead>
            <tbody>${filtered.map(d => `<tr><td>${d.numero}</td><td>${d.nomEditeur}</td><td>${d.foireNom}</td><td>${d.pays}</td><td>${getStatusLabel(d.statut)}</td></tr>`).join('')}</tbody></table>
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
function ffFilter(el, status) {
    if (el) {
        document.querySelectorAll('.ff-ftab').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
    }
    document.getElementById('statusFilter').value = status;
    renderGridAndTable();
}

function ffFilterStatus() {
    document.querySelectorAll('.ff-ftab').forEach(t => t.classList.remove('active'));
    renderGridAndTable();
}

function ffSearch(term) { renderGridAndTable(); }

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = 'all';
    document.querySelectorAll('.ff-ftab').forEach(t => t.classList.remove('active'));
    document.querySelector('.ff-ftab').classList.add('active');
    renderGridAndTable();
}

function ffToggleView() {
    ffIsGrid = !ffIsGrid;
    document.getElementById('ff-grid').style.display = ffIsGrid ? '' : 'none';
    document.getElementById('ff-table-view').style.display = ffIsGrid ? 'none' : '';
    document.getElementById('ff-view-toggle').textContent = ffIsGrid ? '⊞' : '☰';
}

function quickActionExport() {
    console.log('Export:', demandes);
    showToast('Export CSV démarré', 'info');
}

// ============================================
// INITIALIZATION
// ============================================
window.ffFilter = ffFilter;
window.ffToggleView = ffToggleView;
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
window.showFairDetail = showFairDetail;
window.filterCalendarByRegion = filterCalendarByRegion;
window.showIARecommendations = showIARecommendations;
window.showUrgentTasks = showUrgentTasks;
window.openReportModal = openReportModal;
window.selectReportPeriod = selectReportPeriod;
window.generateReport = generateReport;
window.previewAttestation = previewAttestation;
window.simulateSignature = simulateSignature;
window.closeModal = closeModal;
window.quickActionExport = quickActionExport;

renderAll();
</script>


@endsection
