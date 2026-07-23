@extends('shared.layouts.backoffice')

@section('title', 'Prises de Vue d\'Œuvres')
@section('breadcrumb', 'Prises de Vue d\'Œuvres')

@section('content')
{{-- ══════════════════════════════════════════════════════════════
     MODE COLLABORATION FANTÔME — IA MÉDIATEUR
     À insérer dans photos.blade.php avant @endsection
     Placer après le panel "Demandes de Prises de Vue"
══════════════════════════════════════════════════════════════ --}}

<style>
/* ════════════════════════════════════════════
   COLLAB FANTÔME — DESIGN SYSTEM
════════════════════════════════════════════ */

/* ── Wrapper ── */
.cf-wrapper {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-bottom: 8px;
}

/* ── Hero Header ── */
.cf-hero {
    display: flex;
    align-items: center;
    gap: 18px;
    padding: 20px 24px;
    background: linear-gradient(135deg,
        rgba(167,139,250,0.08) 0%,
        rgba(96,165,250,0.06) 50%,
        rgba(45,212,191,0.05) 100%);
    border: 1px solid rgba(167,139,250,0.2);
    border-radius: var(--radius);
    position: relative;
    overflow: hidden;
}

.cf-hero::after {
    content: '👻';
    position: absolute;
    right: 24px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 64px;
    opacity: 0.06;
    pointer-events: none;
}

.cf-hero-orb {
    width: 48px; height: 48px;
    border-radius: 14px;
    background: var(--purple-dim);
    border: 1px solid rgba(167,139,250,0.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
    position: relative;
}

.cf-hero-orb::before {
    content: '';
    position: absolute;
    inset: -3px;
    border-radius: 17px;
    border: 1px solid rgba(167,139,250,0.15);
    animation: cf-orbit 4s linear infinite;
}

@keyframes cf-orbit {
    0%   { opacity: 1; transform: scale(1); }
    50%  { opacity: 0.4; transform: scale(1.1); }
    100% { opacity: 1; transform: scale(1); }
}

.cf-hero-body { flex: 1; }

.cf-hero-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.cf-hero-sub {
    font-size: 12px;
    color: var(--text2);
    line-height: 1.5;
    max-width: 560px;
}

.cf-hero-stats {
    display: flex;
    gap: 12px;
    flex-shrink: 0;
}

.cf-hero-stat {
    text-align: center;
    padding: 10px 16px;
    background: rgba(0,0,0,0.2);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
}

.cf-hero-stat-val {
    font-size: 20px;
    font-weight: 900;
    font-family: var(--font-mono);
    line-height: 1;
}

.cf-hero-stat-lbl {
    font-size: 9.5px;
    color: var(--text3);
    text-transform: uppercase;
    letter-spacing: 0.7px;
    margin-top: 3px;
}

/* ── Main split layout ── */
.cf-split {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 20px;
    align-items: start;
}

@media (max-width: 960px) {
    .cf-split { grid-template-columns: 1fr; }
    .cf-hero-stats { display: none; }
}

/* ── Chat / collaboration panel ── */
.cf-chat-panel {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.cf-chat-head {
    padding: 14px 18px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 10px;
}

.cf-chat-head-title {
    font-size: 13px;
    font-weight: 700;
    color: var(--text);
    flex: 1;
}

.cf-chat-head-sub {
    font-size: 11px;
    color: var(--text3);
}

.cf-thread-selector {
    padding: 0 18px;
    border-bottom: 1px solid var(--border);
    display: flex;
    gap: 0;
    overflow-x: auto;
}

.cf-thread-tab {
    padding: 10px 16px;
    font-size: 12px;
    font-weight: 600;
    color: var(--text3);
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all 0.18s;
    white-space: nowrap;
    user-select: none;
}

.cf-thread-tab:hover { color: var(--text2); }

.cf-thread-tab.active {
    color: var(--purple);
    border-bottom-color: var(--purple);
}

/* ── Message stream ── */
.cf-messages {
    padding: 20px 18px;
    display: flex;
    flex-direction: column;
    gap: 16px;
    min-height: 360px;
    max-height: 420px;
    overflow-y: auto;
}

/* Artist bubble */
.cf-msg {
    display: flex;
    gap: 10px;
    align-items: flex-start;
    animation: cf-fadein 0.3s ease forwards;
}

@keyframes cf-fadein {
    from { opacity: 0; transform: translateY(6px); }
    to   { opacity: 1; transform: translateY(0); }
}

.cf-msg.artist { flex-direction: row; }
.cf-msg.admin  { flex-direction: row-reverse; }
.cf-msg.ia     { flex-direction: column; align-items: stretch; }

.cf-av {
    width: 32px; height: 32px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 700;
    flex-shrink: 0;
}

.cf-av.artist-av { background: linear-gradient(135deg, var(--teal), #1a8f80); color: #fff; }
.cf-av.admin-av  { background: linear-gradient(135deg, var(--gold), #a07830); color: #111; }
.cf-av.ia-av     { background: linear-gradient(135deg, var(--purple), #6344c2); color: #fff; font-size: 14px; }

.cf-bubble {
    max-width: 76%;
    padding: 10px 13px;
    border-radius: 12px;
    font-size: 12.5px;
    line-height: 1.55;
}

.cf-msg.artist .cf-bubble {
    background: var(--bg3);
    border: 1px solid var(--border);
    color: var(--text2);
    border-top-left-radius: 3px;
}

.cf-msg.admin .cf-bubble {
    background: var(--gold-dim);
    border: 1px solid rgba(201,168,76,0.2);
    color: var(--text);
    border-top-right-radius: 3px;
}

.cf-msg-meta {
    font-size: 10px;
    color: var(--text3);
    margin-top: 4px;
    font-family: var(--font-mono);
}

.cf-msg.admin .cf-msg-meta { text-align: right; }

/* ── IA Mediator card ── */
.cf-ia-card {
    background: linear-gradient(135deg, rgba(167,139,250,0.07), rgba(96,165,250,0.05));
    border: 1px solid rgba(167,139,250,0.25);
    border-radius: var(--radius);
    overflow: hidden;
}

.cf-ia-card-head {
    padding: 10px 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    border-bottom: 1px solid rgba(167,139,250,0.15);
}

.cf-ia-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: var(--purple);
    background: var(--purple-dim);
    padding: 3px 9px;
    border-radius: 20px;
}

.cf-ia-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--text2);
}

.cf-ia-thinking {
    display: flex;
    align-items: center;
    gap: 4px;
    margin-left: auto;
}

.cf-ia-dot {
    width: 4px; height: 4px;
    border-radius: 50%;
    background: var(--purple);
    animation: cf-think 1.2s ease-in-out infinite;
}
.cf-ia-dot:nth-child(2) { animation-delay: 0.2s; }
.cf-ia-dot:nth-child(3) { animation-delay: 0.4s; }

@keyframes cf-think {
    0%, 100% { opacity: 0.2; transform: translateY(0); }
    50%       { opacity: 1;   transform: translateY(-3px); }
}

.cf-ia-reformat {
    padding: 12px 14px;
    font-size: 12.5px;
    color: var(--text2);
    line-height: 1.6;
    border-bottom: 1px solid rgba(167,139,250,0.1);
}

.cf-ia-reformat strong { color: var(--purple); }

/* ── 3 Solutions ── */
.cf-solutions {
    padding: 12px 14px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.cf-solution-item {
    display: flex;
    gap: 10px;
    padding: 10px 12px;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border);
    background: var(--bg3);
    cursor: pointer;
    transition: all 0.18s;
    align-items: flex-start;
}

.cf-solution-item:hover {
    border-color: rgba(167,139,250,0.4);
    background: rgba(167,139,250,0.06);
}

.cf-solution-item.selected {
    border-color: var(--purple);
    background: rgba(167,139,250,0.1);
}

.cf-sol-num {
    width: 22px; height: 22px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 800;
    flex-shrink: 0;
    margin-top: 1px;
}

.cf-sol-num.s1 { background: var(--green-dim);  color: var(--green); }
.cf-sol-num.s2 { background: var(--amber-dim);  color: var(--amber); }
.cf-sol-num.s3 { background: var(--blue-dim);   color: var(--blue); }

.cf-sol-body { flex: 1; }

.cf-sol-title {
    font-size: 12.5px;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 3px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.cf-sol-desc {
    font-size: 11.5px;
    color: var(--text2);
    line-height: 1.45;
}

.cf-sol-tag {
    font-size: 9.5px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 10px;
    letter-spacing: 0.4px;
    flex-shrink: 0;
    margin-top: 2px;
}

.cf-sol-tag.green  { background: var(--green-dim);  color: var(--green); }
.cf-sol-tag.amber  { background: var(--amber-dim);  color: var(--amber); }
.cf-sol-tag.blue   { background: var(--blue-dim);   color: var(--blue); }

/* ── 1-click validate bar ── */
.cf-validate-bar {
    padding: 12px 14px;
    border-top: 1px solid rgba(167,139,250,0.15);
    display: flex;
    gap: 8px;
    align-items: center;
}

.cf-btn-validate {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 9px 16px;
    background: linear-gradient(135deg, var(--purple), #6344c2);
    color: #fff;
    border: none;
    border-radius: var(--radius-sm);
    font-size: 12.5px;
    font-weight: 700;
    cursor: pointer;
    font-family: var(--font-body);
    transition: opacity 0.18s;
}

.cf-btn-validate:hover { opacity: 0.88; }

.cf-btn-letter {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 9px 14px;
    background: var(--bg3);
    color: var(--text2);
    border: 1px solid var(--border2);
    border-radius: var(--radius-sm);
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    font-family: var(--font-body);
    transition: all 0.18s;
    white-space: nowrap;
}

.cf-btn-letter:hover { background: var(--bg4); color: var(--text); }

/* ── Chat composer ── */
.cf-composer {
    padding: 12px 18px;
    border-top: 1px solid var(--border);
    display: flex;
    gap: 8px;
    align-items: flex-end;
}

.cf-compose-input {
    flex: 1;
    background: var(--bg3);
    border: 1px solid var(--border2);
    border-radius: var(--radius-sm);
    padding: 9px 13px;
    font-size: 12.5px;
    color: var(--text);
    font-family: var(--font-body);
    resize: none;
    min-height: 38px;
    max-height: 100px;
    outline: none;
    transition: border-color 0.18s;
}

.cf-compose-input:focus { border-color: var(--purple); }
.cf-compose-input::placeholder { color: var(--text3); }

.cf-compose-send {
    width: 38px; height: 38px;
    border-radius: var(--radius-sm);
    background: var(--purple);
    border: none;
    color: #fff;
    font-size: 15px;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: opacity 0.18s;
    flex-shrink: 0;
}

.cf-compose-send:hover { opacity: 0.85; }

.cf-compose-ia-btn {
    height: 38px;
    padding: 0 12px;
    border-radius: var(--radius-sm);
    background: var(--purple-dim);
    border: 1px solid rgba(167,139,250,0.25);
    color: var(--purple);
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    display: flex; align-items: center; gap: 5px;
    font-family: var(--font-body);
    transition: all 0.18s;
    white-space: nowrap;
    flex-shrink: 0;
}

.cf-compose-ia-btn:hover { background: rgba(167,139,250,0.18); }

/* ── Right sidebar ── */
.cf-sidebar {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

/* ── Active requests mini list ── */
.cf-request-list {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
}

.cf-list-head {
    padding: 12px 16px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.cf-list-title {
    font-size: 12px;
    font-weight: 700;
    color: var(--text);
}

.cf-list-count {
    font-size: 10px;
    font-family: var(--font-mono);
    font-weight: 700;
    padding: 2px 8px;
    background: var(--purple-dim);
    color: var(--purple);
    border-radius: 10px;
}

.cf-request-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 11px 16px;
    border-bottom: 1px solid var(--border);
    cursor: pointer;
    transition: background 0.15s;
    position: relative;
}

.cf-request-item:last-child { border-bottom: none; }
.cf-request-item:hover { background: var(--bg3); }

.cf-request-item.active-thread {
    background: rgba(167,139,250,0.06);
    border-left: 3px solid var(--purple);
}

.cf-req-av {
    width: 30px; height: 30px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 700;
    flex-shrink: 0;
}

.cf-req-info { flex: 1; min-width: 0; }

.cf-req-name {
    font-size: 12px;
    font-weight: 600;
    color: var(--text);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.cf-req-work {
    font-size: 10.5px;
    color: var(--text3);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.cf-req-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    flex-shrink: 0;
}

.cf-req-dot.pending  { background: var(--amber); }
.cf-req-dot.waiting  { background: var(--blue); }
.cf-req-dot.resolved { background: var(--green); }

/* ── AI performance panel ── */
.cf-perf-panel {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
}

.cf-perf-head {
    padding: 12px 16px;
    border-bottom: 1px solid var(--border);
    font-size: 12px;
    font-weight: 700;
    color: var(--text);
    display: flex;
    align-items: center;
    gap: 7px;
}

.cf-perf-body {
    padding: 14px 16px;
    display: flex;
    flex-direction: column;
    gap: 11px;
}

.cf-metric {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.cf-metric-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 11.5px;
}

.cf-metric-label { color: var(--text2); }

.cf-metric-val {
    font-family: var(--font-mono);
    font-weight: 700;
    font-size: 12px;
}

.cf-metric-bar {
    height: 4px;
    background: var(--bg4);
    border-radius: 2px;
    overflow: hidden;
}

.cf-metric-fill {
    height: 100%;
    border-radius: 2px;
    transition: width 0.8s ease;
}

/* ── Letter preview panel ── */
.cf-letter-panel {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
}

.cf-letter-head {
    padding: 12px 16px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.cf-letter-title {
    font-size: 12px;
    font-weight: 700;
    color: var(--text);
    display: flex;
    align-items: center;
    gap: 6px;
}

.cf-letter-body {
    padding: 14px 16px;
    font-size: 11.5px;
    color: var(--text2);
    line-height: 1.65;
    font-family: var(--font-mono);
    background: var(--bg3);
    margin: 0 12px 12px;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border);
    border-left: 3px solid var(--purple);
    max-height: 170px;
    overflow-y: auto;
}

.cf-letter-footer {
    padding: 0 12px 12px;
    display: flex;
    gap: 6px;
}

/* ── Step indicator (breadcrumb) ── */
.cf-steps {
    display: flex;
    align-items: center;
    gap: 0;
    padding: 12px 18px;
    border-bottom: 1px solid var(--border);
    overflow-x: auto;
}

.cf-step {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 600;
    color: var(--text3);
    white-space: nowrap;
    flex-shrink: 0;
}

.cf-step.done  { color: var(--green); }
.cf-step.active { color: var(--purple); }

.cf-step-num {
    width: 18px; height: 18px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 9px; font-weight: 800;
    background: var(--bg4);
    color: var(--text3);
}

.cf-step.done  .cf-step-num { background: var(--green-dim);  color: var(--green); }
.cf-step.active .cf-step-num { background: var(--purple-dim); color: var(--purple); }

.cf-step-arrow {
    margin: 0 6px;
    color: var(--text3);
    font-size: 10px;
}

/* ── No-scroll message ── */
.cf-no-email-tag {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 700;
    background: var(--teal-dim);
    color: var(--teal);
    border: 1px solid rgba(45,212,191,0.2);
    letter-spacing: 0.3px;
}
</style>

{{-- ────────────────────────────────────────────────────────────
     BLOC PRINCIPAL — MODE COLLABORATION FANTÔME
──────────────────────────────────────────────────────────── --}}

<div class="panel" style="margin-bottom: 24px;">

    {{-- PANEL HEADER --}}
    <div class="panel-head">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 36px; height: 36px; border-radius: 8px; background: var(--purple-dim); border: 1px solid rgba(167,139,250,0.3); display: flex; align-items: center; justify-content: center; font-size: 18px;">👻</div>
            <div>
                <div class="panel-title" style="display: flex; align-items: center; gap: 10px;">
                    Mode Collaboration Fantôme
                    <span class="cf-no-email-tag">✉️ Zéro aller-retour email</span>
                </div>
                <div class="panel-sub">L'IA joue le rôle de médiateur — elle reformule, propose 3 compromis, génère le courrier officiel. Admin valide en 1 clic.</div>
            </div>
        </div>
        <div style="display: flex; gap: 8px;">
            <button class="btn btn-outline btn-sm" onclick="showToast('Historique exporté', 'info')">📥 Historique</button>
            <button class="btn btn-outline btn-sm" onclick="openModal('modal-cf-settings')">⚙️ Config IA</button>
        </div>
    </div>

    <div class="panel-body">
        <div class="cf-wrapper">

            {{-- HERO STATS --}}
            <div class="cf-hero">
                <div class="cf-hero-orb">👻</div>
                <div class="cf-hero-body">
                    <div class="cf-hero-title">
                        IA Médiateur — Actif sur 4 demandes en cours
                        <span class="badge" style="background: var(--purple-dim); color: var(--purple); font-size: 10px;">BÊTA</span>
                    </div>
                    <div class="cf-hero-sub">
                        Plus de 15 allers-retours par email supprimés. L'IA reformule la demande brute de l'artiste, analyse le contexte, propose 3 solutions calibrées, génère le courrier officiel — l'admin valide en 1 clic.
                    </div>
                </div>
                <div class="cf-hero-stats">
                    <div class="cf-hero-stat">
                        <div class="cf-hero-stat-val" style="color: var(--purple);">-87%</div>
                        <div class="cf-hero-stat-lbl">Emails évités</div>
                    </div>
                    <div class="cf-hero-stat">
                        <div class="cf-hero-stat-val" style="color: var(--green);">94%</div>
                        <div class="cf-hero-stat-lbl">Satisfaction</div>
                    </div>
                    <div class="cf-hero-stat">
                        <div class="cf-hero-stat-val" style="color: var(--teal);">2.4h</div>
                        <div class="cf-hero-stat-lbl">Délai moy.</div>
                    </div>
                </div>
            </div>

            {{-- MAIN SPLIT --}}
            <div class="cf-split">

                {{-- LEFT — CHAT + IA MEDIATOR --}}
                <div class="cf-chat-panel">

                    {{-- Thread tabs --}}
                    <div class="cf-thread-selector">
                        <div class="cf-thread-tab active" onclick="cfSwitchTab(this, 'thread-1')">Marie Laurent · Abstraction IV</div>
                        <div class="cf-thread-tab" onclick="cfSwitchTab(this, 'thread-2')">Jean Dupont · Sculptures</div>
                        <div class="cf-thread-tab" onclick="cfSwitchTab(this, 'thread-3')">Thomas Renault · Murales</div>
                    </div>

                    {{-- Step progress --}}
                    <div class="cf-steps">
                        <div class="cf-step done">
                            <div class="cf-step-num">✓</div>Demande reçue
                        </div>
                        <span class="cf-step-arrow">›</span>
                        <div class="cf-step done">
                            <div class="cf-step-num">✓</div>IA reformule
                        </div>
                        <span class="cf-step-arrow">›</span>
                        <div class="cf-step active">
                            <div class="cf-step-num">3</div>3 solutions proposées
                        </div>
                        <span class="cf-step-arrow">›</span>
                        <div class="cf-step">
                            <div class="cf-step-num">4</div>Validation admin
                        </div>
                        <span class="cf-step-arrow">›</span>
                        <div class="cf-step">
                            <div class="cf-step-num">5</div>Courrier généré
                        </div>
                    </div>

                    {{-- Message thread --}}
                    <div class="cf-messages" id="cf-messages">

                        {{-- Artist raw message --}}
                        <div class="cf-msg artist">
                            <div class="cf-av artist-av">ML</div>
                            <div>
                                <div class="cf-bubble">
                                    Bonjour, je veux une photo de mon œuvre "Abstraction IV" pour mon portfolio mais j'ai besoin que ce soit fait rapidement, genre dans 2-3 jours maximum, et je voudrais un photographe professionnel. Est ce que c'est possible ? Merci
                                </div>
                                <div class="cf-msg-meta">Marie Laurent · Artiste · 09:14</div>
                            </div>
                        </div>

                        {{-- IA reformulation + 3 solutions --}}
                        <div class="cf-msg ia">
                            <div class="cf-ia-card">
                                <div class="cf-ia-card-head">
                                    <span class="cf-ia-badge">👻 IA Médiateur</span>
                                    <span class="cf-ia-label">Demande analysée et reformulée</span>
                                    <div class="cf-ia-thinking">
                                        <div class="cf-ia-dot"></div>
                                        <div class="cf-ia-dot"></div>
                                        <div class="cf-ia-dot"></div>
                                    </div>
                                </div>
                                <div class="cf-ia-reformat">
                                    <strong>Reformulation officielle :</strong> Mme Marie Laurent demande une séance de photographie professionnelle pour l'œuvre <strong>«&nbsp;Abstraction IV&nbsp;»</strong> dans un délai de <strong>2 à 3 jours ouvrés</strong>, destinée à un usage portfolio personnel. Priorité haute — délai contraint.
                                </div>
                                <div class="cf-solutions">
                                    <div style="font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: var(--text3); margin-bottom: 4px;">3 Solutions proposées par l'IA</div>
                                    <div class="cf-solution-item selected" onclick="cfSelectSolution(this, 1)">
                                        <div class="cf-sol-num s1">1</div>
                                        <div class="cf-sol-body">
                                            <div class="cf-sol-title">
                                                Séance prioritaire — Pierre Moreau
                                                <span class="cf-sol-tag green">Recommandé</span>
                                            </div>
                                            <div class="cf-sol-desc">Photographe disponible demain (J+1). Créneau 10h–12h au studio. Livraison sous 24h. Coût standard.</div>
                                        </div>
                                    </div>
                                    <div class="cf-solution-item" onclick="cfSelectSolution(this, 2)">
                                        <div class="cf-sol-num s2">2</div>
                                        <div class="cf-sol-body">
                                            <div class="cf-sol-title">
                                                Séance J+2 — Sophie Bernard
                                                <span class="cf-sol-tag amber">Alternative</span>
                                            </div>
                                            <div class="cf-sol-desc">Créneau après-midi. Spécialiste œuvres abstraites. Retouches incluses. Délai légèrement supérieur.</div>
                                        </div>
                                    </div>
                                    <div class="cf-solution-item" onclick="cfSelectSolution(this, 3)">
                                        <div class="cf-sol-num s3">3</div>
                                        <div class="cf-sol-body">
                                            <div class="cf-sol-title">
                                                Reportage in situ — J+3
                                                <span class="cf-sol-tag blue">Premium</span>
                                            </div>
                                            <div class="cf-sol-desc">Photographe déplacé dans l'atelier de l'artiste. Ambiance naturelle. Délai max autorisé. Tarif +30%.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="cf-validate-bar">
                                    <button class="cf-btn-validate" onclick="cfValidate()">
                                        ⚡ Valider la solution ① en 1 clic
                                    </button>
                                    <button class="cf-btn-letter" onclick="openModal('modal-cf-letter')">
                                        📄 Voir courrier
                                    </button>
                                </div>
                            </div>
                            <div style="font-size: 10px; color: var(--text3); margin-top: 5px; font-family: var(--font-mono); text-align: center;">
                                IA Médiateur · Analyse en 0.9s · 09:14:47
                            </div>
                        </div>

                        {{-- Admin response --}}
                        <div class="cf-msg admin" id="cf-admin-reply" style="display:none;">
                            <div class="cf-av admin-av">AD</div>
                            <div>
                                <div class="cf-bubble">
                                    ✓ Solution ① validée. Pierre Moreau notifié. Courrier officiel généré et envoyé à Mme Laurent.
                                </div>
                                <div class="cf-msg-meta">Administrateur · Vient de valider</div>
                            </div>
                        </div>

                    </div>{{-- end cf-messages --}}

                    {{-- Composer --}}
                    <div class="cf-composer">
                        <textarea class="cf-compose-input" placeholder="Ajouter un commentaire ou une instruction à l'IA..." rows="1" id="cf-compose-text"></textarea>
                        <button class="cf-compose-ia-btn" onclick="cfReformulate()">👻 Reformuler</button>
                        <button class="cf-compose-send" onclick="cfSendMessage()" title="Envoyer">›</button>
                    </div>

                </div>{{-- end cf-chat-panel --}}

                {{-- RIGHT SIDEBAR --}}
                <div class="cf-sidebar">

                    {{-- Active requests --}}
                    <div class="cf-request-list">
                        <div class="cf-list-head">
                            <span class="cf-list-title">Demandes en cours</span>
                            <span class="cf-list-count">4 actives</span>
                        </div>

                        <div class="cf-request-item active-thread" onclick="cfSwitchThread(this)">
                            <div class="cf-req-av" style="background: linear-gradient(135deg, var(--teal), #1a8f80); color:#fff;">ML</div>
                            <div class="cf-req-info">
                                <div class="cf-req-name">Marie Laurent</div>
                                <div class="cf-req-work">Abstraction IV — Prise de vue</div>
                            </div>
                            <div class="cf-req-dot pending"></div>
                        </div>

                        <div class="cf-request-item" onclick="cfSwitchThread(this)">
                            <div class="cf-req-av" style="background: linear-gradient(135deg, var(--blue), #1560a8); color:#fff;">JD</div>
                            <div class="cf-req-info">
                                <div class="cf-req-name">Jean Dupont</div>
                                <div class="cf-req-work">Sculptures Urbaines — Délai ext.</div>
                            </div>
                            <div class="cf-req-dot waiting"></div>
                        </div>

                        <div class="cf-request-item" onclick="cfSwitchThread(this)">
                            <div class="cf-req-av" style="background: linear-gradient(135deg, var(--gold), #a07830); color:#111;">SM</div>
                            <div class="cf-req-info">
                                <div class="cf-req-name">Sophie Martin</div>
                                <div class="cf-req-work">Installations — Résolu ✓</div>
                            </div>
                            <div class="cf-req-dot resolved"></div>
                        </div>

                        <div class="cf-request-item" onclick="cfSwitchThread(this)">
                            <div class="cf-req-av" style="background: linear-gradient(135deg, var(--red), #a03030); color:#fff;">TR</div>
                            <div class="cf-req-info">
                                <div class="cf-req-name">Thomas Renault</div>
                                <div class="cf-req-work">Peintures Murales — Rejet contesté</div>
                            </div>
                            <div class="cf-req-dot pending"></div>
                        </div>
                    </div>

                    {{-- AI Performance --}}
                    <div class="cf-perf-panel">
                        <div class="cf-perf-head">📊 Performance IA ce mois</div>
                        <div class="cf-perf-body">
                            <div class="cf-metric">
                                <div class="cf-metric-row">
                                    <span class="cf-metric-label">Solutions acceptées</span>
                                    <span class="cf-metric-val" style="color: var(--green);">91%</span>
                                </div>
                                <div class="cf-metric-bar">
                                    <div class="cf-metric-fill" style="width:91%; background: var(--green);"></div>
                                </div>
                            </div>
                            <div class="cf-metric">
                                <div class="cf-metric-row">
                                    <span class="cf-metric-label">Solution ① choisie</span>
                                    <span class="cf-metric-val" style="color: var(--purple);">68%</span>
                                </div>
                                <div class="cf-metric-bar">
                                    <div class="cf-metric-fill" style="width:68%; background: var(--purple);"></div>
                                </div>
                            </div>
                            <div class="cf-metric">
                                <div class="cf-metric-row">
                                    <span class="cf-metric-label">Emails économisés</span>
                                    <span class="cf-metric-val" style="color: var(--teal);">247</span>
                                </div>
                                <div class="cf-metric-bar">
                                    <div class="cf-metric-fill" style="width:82%; background: var(--teal);"></div>
                                </div>
                            </div>
                            <div class="cf-metric">
                                <div class="cf-metric-row">
                                    <span class="cf-metric-label">Délai moyen résolution</span>
                                    <span class="cf-metric-val" style="color: var(--amber);">2.4h</span>
                                </div>
                                <div class="cf-metric-bar">
                                    <div class="cf-metric-fill" style="width:45%; background: var(--amber);"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Quick actions --}}
                    <div style="background: var(--bg2); border: 1px solid var(--border); border-radius: var(--radius); padding: 14px 16px; display: flex; flex-direction: column; gap: 7px;">
                        <div style="font-size: 12px; font-weight: 700; color: var(--text); margin-bottom: 4px;">Actions rapides</div>
                        <button class="btn btn-outline btn-sm" style="justify-content: flex-start; gap: 7px; font-size: 11.5px;" onclick="openModal('modal-cf-letter')">
                            📄 Générer courrier officiel
                        </button>
                        <button class="btn btn-outline btn-sm" style="justify-content: flex-start; gap: 7px; font-size: 11.5px;" onclick="showToast('Résumé de négociation généré', 'info')">
                            📝 Résumé de négociation
                        </button>
                        <button class="btn btn-outline btn-sm" style="justify-content: flex-start; gap: 7px; font-size: 11.5px;" onclick="showToast('Artiste notifié par l\'IA', 'success')">
                            ✉️ Notifier l'artiste (IA)
                        </button>
                        <button class="btn btn-outline btn-sm" style="justify-content: flex-start; gap: 7px; font-size: 11.5px;" onclick="showToast('Escalade envoyée au responsable', 'info')">
                            🔺 Escalader au responsable
                        </button>
                    </div>

                </div>{{-- end cf-sidebar --}}

            </div>{{-- end cf-split --}}

        </div>{{-- end cf-wrapper --}}
    </div>{{-- end panel-body --}}
</div>{{-- end panel --}}


{{-- ═══════════════════════════════════════════════════════
     MODAL — COURRIER OFFICIEL GÉNÉRÉ PAR L'IA
═══════════════════════════════════════════════════════ --}}
<div class="modal" id="modal-cf-letter">
    <div class="modal-content" style="max-width: 560px;">
        <div class="modal-header">
            <div class="modal-title" style="display:flex; align-items:center; gap:8px;">
                📄 Courrier Officiel — Généré par l'IA
                <span style="font-size:10px; padding: 2px 8px; background: var(--purple-dim); color: var(--purple); border-radius: 20px; font-weight:700;">IA</span>
            </div>
            <button class="modal-close" onclick="closeModal('modal-cf-letter')">✕</button>
        </div>
        <div class="modal-body" style="display: flex; flex-direction: column; gap: 14px;">

            {{-- Letter meta --}}
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Destinataire</label>
                    <input type="text" class="form-input" value="Mme Marie Laurent" readonly>
                </div>
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Référence</label>
                    <input type="text" class="form-input" value="REQ-PHOTO-2024-0156" readonly>
                </div>
            </div>

            {{-- Letter content --}}
            <div class="cf-letter-panel" style="margin:0;">
                <div class="cf-letter-head">
                    <span class="cf-letter-title">👻 Lettre générée automatiquement</span>
                    <span style="font-size: 10px; color: var(--text3); font-family: var(--font-mono);">Relecture recommandée</span>
                </div>
                <div class="cf-letter-body" style="max-height: 220px; margin: 0; border-radius: 0; border: none; border-left: none; background: var(--bg2);">
Madame Marie Laurent,

Nous avons bien reçu votre demande de prise de vue pour l'œuvre «&nbsp;Abstraction IV&nbsp;» en date du 15 avril 2024.

Après analyse, nous avons le plaisir de vous confirmer la planification suivante :

• Photographe assigné : M. Pierre Moreau
• Date de la séance : 16 avril 2024 (J+1)
• Créneau horaire : 10h00 – 12h00
• Lieu : Studio institutionnel — Salle B
• Livraison des clichés : sous 24h après la séance

Nous vous remercions de votre confiance et restons disponibles pour toute question.

Cordialement,
La Direction Artistique
                </div>
            </div>

            {{-- Tone selector --}}
            <div class="form-group" style="margin:0;">
                <label class="form-label">Ton du courrier</label>
                <select class="form-select" onchange="showToast('Courrier mis à jour avec le nouveau ton', 'info')">
                    <option>Formel — institutionnel</option>
                    <option>Chaleureux — collaboratif</option>
                    <option>Concis — professionnel</option>
                </select>
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('modal-cf-letter')">Annuler</button>
            <button class="btn btn-outline" onclick="showToast('Copié dans le presse-papier', 'info')">📋 Copier</button>
            <button class="btn btn-gold" onclick="showToast('Courrier envoyé à Mme Laurent!', 'success'); closeModal('modal-cf-letter')">✉️ Envoyer</button>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     MODAL — CONFIGURATION IA
═══════════════════════════════════════════════════════ --}}
<div class="modal" id="modal-cf-settings">
    <div class="modal-content" style="max-width: 480px;">
        <div class="modal-header">
            <div class="modal-title">⚙️ Configuration IA Médiateur</div>
            <button class="modal-close" onclick="closeModal('modal-cf-settings')">✕</button>
        </div>
        <div class="modal-body" style="display: flex; flex-direction: column; gap: 14px;">
            <div class="form-group" style="margin:0;">
                <label class="form-label">Nombre de solutions proposées</label>
                <select class="form-select">
                    <option>3 solutions (recommandé)</option>
                    <option>2 solutions</option>
                    <option>5 solutions</option>
                </select>
            </div>
            <div class="form-group" style="margin:0;">
                <label class="form-label">Ton de reformulation</label>
                <select class="form-select">
                    <option>Formel institutionnel</option>
                    <option>Neutre professionnel</option>
                    <option>Chaleureux collaboratif</option>
                </select>
            </div>
            <div class="form-group" style="margin:0;">
                <label class="form-label">Délai d'analyse IA</label>
                <select class="form-select">
                    <option>Temps réel (recommandé)</option>
                    <option>5 minutes</option>
                    <option>Manuel uniquement</option>
                </select>
            </div>
            <div class="form-group" style="margin:0;">
                <label class="form-label">Génération automatique du courrier</label>
                <select class="form-select">
                    <option>Après validation admin</option>
                    <option>Proposé pour relecture</option>
                    <option>Désactivé</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('modal-cf-settings')">Annuler</button>
            <button class="btn btn-gold" onclick="showToast('Configuration sauvegardée', 'success'); closeModal('modal-cf-settings')">Sauvegarder</button>
        </div>
    </div>
</div>

{{-- ── JS interactions ── --}}
<script>
// Switch thread tab
window.cfSwitchTab = function(el, threadId) {
    el.closest('.cf-thread-selector').querySelectorAll('.cf-thread-tab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    showToast('Thread chargé : ' + el.textContent.trim(), 'info');
}

// Select solution
window.cfSelectSolution = function(el, num) {
    el.closest('.cf-solutions').querySelectorAll('.cf-solution-item').forEach(s => s.classList.remove('selected'));
    el.classList.add('selected');
    const btn = el.closest('.cf-ia-card').querySelector('.cf-btn-validate');
    if (btn) btn.textContent = '⚡ Valider la solution ❯ N°' + num + ' en 1 clic';
}

// Validate
window.cfValidate = function() {
    const replyEl = document.getElementById('cf-admin-reply');
    if (replyEl) {
        replyEl.style.display = 'flex';
        replyEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
    showToast('✓ Solution validée — Courrier généré et envoyé!', 'success');
}

// Switch thread from sidebar
window.cfSwitchThread = function(el) {
    el.closest('.cf-request-list').querySelectorAll('.cf-request-item').forEach(i => i.classList.remove('active-thread'));
    el.classList.add('active-thread');
    const name = el.querySelector('.cf-req-name').textContent;
    showToast('Fil ouvert : ' + name, 'info');
}

// Reformulate button
window.cfReformulate = function() {
    const txt = document.getElementById('cf-compose-text');
    if (txt && txt.value.trim()) {
        showToast('👻 IA reformule votre message...', 'info');
        setTimeout(() => showToast('✓ Message reformulé et 3 solutions générées', 'success'), 1800);
    } else {
        showToast('Saisissez un message à reformuler', 'info');
    }
}

// Send message
window.cfSendMessage = function() {
    const txt = document.getElementById('cf-compose-text');
    if (!txt || !txt.value.trim()) return;
    const messages = document.getElementById('cf-messages');
    const bubble = document.createElement('div');
    bubble.className = 'cf-msg admin';
    bubble.innerHTML = `
        <div class="cf-av admin-av">AD</div>
        <div>
            <div class="cf-bubble">${txt.value.trim()}</div>
            <div class="cf-msg-meta">Administrateur · À l'instant</div>
        </div>`;
    messages.appendChild(bubble);
    messages.scrollTop = messages.scrollHeight;
    txt.value = '';
    showToast('Message envoyé', 'success');
}
</script>
@endsection
