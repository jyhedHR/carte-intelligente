@extends('shared.layouts.backoffice')

@section('title', 'Analytics & Rapports')
@section('breadcrumb', 'Analytics & Rapports')

@section('content')

<style>
    /* ── Conflict Detection Styles ── */
    .cd-wrapper {
        display: flex;
        flex-direction: column;
        gap: 24px;
        margin-bottom: 32px;
    }

    /* ── Hero Banner ── */
    .cd-hero {
        background: linear-gradient(135deg, rgba(248, 113, 113, 0.08) 0%, rgba(251, 191, 36, 0.06) 50%, rgba(167, 139, 250, 0.06) 100%);
        border: 1px solid rgba(248, 113, 113, 0.2);
        border-radius: var(--radius);
        padding: 20px 24px;
        display: flex;
        align-items: center;
        gap: 20px;
        position: relative;
        overflow: hidden;
    }

    .cd-hero::before {
        content: '';
        position: absolute;
        top: -30px;
        right: -30px;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(248, 113, 113, 0.12), transparent 70%);
        pointer-events: none;
    }

    .cd-hero-pulse {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: var(--red-dim);
        border: 2px solid var(--red);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
        position: relative;
        animation: cd-pulse 2s ease-in-out infinite;
    }

    @keyframes cd-pulse {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(248, 113, 113, 0.4);
        }

        50% {
            box-shadow: 0 0 0 10px rgba(248, 113, 113, 0);
        }
    }

    .cd-hero-text {
        flex: 1;
    }

    .cd-hero-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .cd-hero-sub {
        font-size: 12px;
        color: var(--text2);
        line-height: 1.5;
    }

    .cd-hero-stat {
        text-align: center;
        padding: 12px 20px;
        background: var(--bg3);
        border-radius: var(--radius-sm);
        border: 1px solid var(--border);
        flex-shrink: 0;
    }

    .cd-hero-stat-val {
        font-size: 24px;
        font-weight: 900;
        font-family: var(--font-mono);
        color: var(--red);
        line-height: 1;
    }

    .cd-hero-stat-label {
        font-size: 10px;
        color: var(--text3);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-top: 4px;
    }

    .cd-hero-timing {
        text-align: center;
        padding: 12px 20px;
        background: var(--bg3);
        border-radius: var(--radius-sm);
        border: 1px solid var(--border);
        flex-shrink: 0;
    }

    .cd-hero-timing-val {
        font-size: 24px;
        font-weight: 900;
        font-family: var(--font-mono);
        color: var(--green);
        line-height: 1;
    }

    .cd-hero-timing-label {
        font-size: 10px;
        color: var(--text3);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-top: 4px;
    }

    /* ── Live Scan Bar ── */
    .cd-scan-panel {
        background: var(--bg2);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .cd-scan-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--text3);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        flex-shrink: 0;
        white-space: nowrap;
    }

    .cd-scan-track {
        flex: 1;
        height: 6px;
        background: var(--bg4);
        border-radius: 3px;
        overflow: hidden;
    }

    .cd-scan-fill {
        height: 100%;
        border-radius: 3px;
        background: linear-gradient(90deg, var(--green), var(--teal));
        animation: cd-scan 3s ease-in-out infinite;
        transform-origin: left;
    }

    @keyframes cd-scan {
        0% {
            width: 0%;
            opacity: 1;
        }

        70% {
            width: 100%;
            opacity: 1;
        }

        85% {
            width: 100%;
            opacity: 0.3;
        }

        100% {
            width: 0%;
            opacity: 0;
        }
    }

    .cd-scan-status {
        font-size: 11px;
        font-family: var(--font-mono);
        color: var(--green);
        flex-shrink: 0;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .cd-scan-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--green);
        animation: cd-blink 1.2s ease-in-out infinite;
    }

    @keyframes cd-blink {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.2;
        }
    }

    /* ── Two-column grid ── */
    .cd-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    @media (max-width: 900px) {
        .cd-grid {
            grid-template-columns: 1fr;
        }

        .cd-hero {
            flex-wrap: wrap;
        }
    }

    /* ── Alert Card ── */
    .cd-alert-card {
        background: var(--bg2);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .cd-alert-card.critical {
        border-left: 3px solid var(--red);
    }

    .cd-alert-card.warning {
        border-left: 3px solid var(--amber);
    }

    .cd-alert-card.resolved {
        border-left: 3px solid var(--green);
    }

    .cd-card-head {
        padding: 14px 18px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .cd-card-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
    }

    .cd-card-icon.red {
        background: var(--red-dim);
    }

    .cd-card-icon.amber {
        background: var(--amber-dim);
    }

    .cd-card-icon.green {
        background: var(--green-dim);
    }

    .cd-card-meta {
        flex: 1;
    }

    .cd-card-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--text);
        line-height: 1.3;
    }

    .cd-card-ref {
        font-size: 10px;
        font-family: var(--font-mono);
        color: var(--text3);
        margin-top: 2px;
    }

    .cd-card-badge {
        font-size: 10px;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 20px;
        flex-shrink: 0;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .cd-card-badge.critical {
        background: var(--red-dim);
        color: var(--red);
    }

    .cd-card-badge.warning {
        background: var(--amber-dim);
        color: var(--amber);
    }

    .cd-card-badge.resolved {
        background: var(--green-dim);
        color: var(--green);
    }

    .cd-card-body {
        padding: 16px 18px;
        flex: 1;
    }

    /* ── Conflict detail row ── */
    .cd-detail-row {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 14px;
    }

    .cd-detail-label {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text3);
        font-weight: 600;
    }

    .cd-detail-val {
        font-size: 12.5px;
        color: var(--text2);
        line-height: 1.5;
    }

    /* ── Conflict items list ── */
    .cd-conflict-items {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 14px;
    }

    .cd-conflict-item {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        padding: 8px 10px;
        border-radius: var(--radius-sm);
        font-size: 12px;
        color: var(--text2);
        line-height: 1.4;
    }

    .cd-conflict-item.red-bg {
        background: rgba(248, 113, 113, 0.06);
    }

    .cd-conflict-item.amber-bg {
        background: rgba(251, 191, 36, 0.06);
    }

    .cd-conflict-item-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        flex-shrink: 0;
        margin-top: 5px;
    }

    .cd-conflict-item-dot.red {
        background: var(--red);
    }

    .cd-conflict-item-dot.amber {
        background: var(--amber);
    }

    /* ── AI Solution box ── */
    .cd-solution {
        background: linear-gradient(135deg, rgba(96, 165, 250, 0.06), rgba(167, 139, 250, 0.06));
        border: 1px solid rgba(96, 165, 250, 0.2);
        border-radius: var(--radius-sm);
        padding: 12px 14px;
        margin-bottom: 14px;
    }

    .cd-solution-label {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--blue);
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .cd-solution-text {
        font-size: 12.5px;
        color: var(--text2);
        line-height: 1.55;
    }

    /* ── Card footer actions ── */
    .cd-card-foot {
        padding: 12px 18px;
        border-top: 1px solid var(--border);
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }

    .cd-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 14px;
        border-radius: var(--radius-sm);
        font-size: 11.5px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.18s;
        font-family: var(--font-body);
        white-space: nowrap;
    }

    .cd-btn-primary {
        background: var(--blue);
        color: #fff;
    }

    .cd-btn-primary:hover {
        opacity: 0.88;
    }

    .cd-btn-danger {
        background: var(--red-dim);
        color: var(--red);
        border: 1px solid rgba(248, 113, 113, 0.25);
    }

    .cd-btn-danger:hover {
        background: rgba(248, 113, 113, 0.2);
    }

    .cd-btn-outline {
        background: transparent;
        color: var(--text2);
        border: 1px solid var(--border2);
    }

    .cd-btn-outline:hover {
        background: var(--bg3);
        color: var(--text);
    }

    .cd-btn-success {
        background: var(--green-dim);
        color: var(--green);
        border: 1px solid rgba(74, 222, 128, 0.25);
    }

    .cd-btn-success:hover {
        background: rgba(74, 222, 128, 0.2);
    }

    .cd-time-ago {
        margin-left: auto;
        font-size: 10.5px;
        font-family: var(--font-mono);
        color: var(--text3);
    }

    /* ── Timeline item inside alerts ── */
    .cd-mini-timeline {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 14px;
    }

    .cd-mini-tl-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 12px;
        color: var(--text2);
    }

    .cd-mini-tl-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .cd-mini-tl-line {
        width: 1px;
        height: 12px;
        background: var(--border);
        margin-left: 3px;
        flex-shrink: 0;
    }

    .cd-mini-tl-time {
        font-family: var(--font-mono);
        font-size: 10.5px;
        color: var(--text3);
        white-space: nowrap;
    }

    /* ── Stats row ── */
    .cd-stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 14px;
    }

    .cd-stat-mini {
        background: var(--bg3);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 10px 12px;
        text-align: center;
    }

    .cd-stat-mini-val {
        font-size: 18px;
        font-weight: 900;
        font-family: var(--font-mono);
        line-height: 1;
    }

    .cd-stat-mini-lbl {
        font-size: 9.5px;
        color: var(--text3);
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-top: 4px;
    }

    /* ── Progress ring ── */
    .cd-ring-wrap {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 14px;
        background: var(--bg3);
        border-radius: var(--radius-sm);
        border: 1px solid var(--border);
        margin-bottom: 14px;
    }

    .cd-ring {
        position: relative;
        width: 54px;
        height: 54px;
        flex-shrink: 0;
    }

    .cd-ring svg {
        transform: rotate(-90deg);
    }

    .cd-ring-val {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 900;
        font-family: var(--font-mono);
        color: var(--red);
    }

    .cd-ring-info {
        flex: 1;
    }

    .cd-ring-title {
        font-size: 12.5px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 3px;
    }

    .cd-ring-sub {
        font-size: 11px;
        color: var(--text3);
    }

    /* ── Full-width alert banner ── */
    .cd-alert-banner {
        background: rgba(248, 113, 113, 0.06);
        border: 1px solid rgba(248, 113, 113, 0.25);
        border-radius: var(--radius);
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        animation: cd-glow 3s ease-in-out infinite;
    }

    @keyframes cd-glow {

        0%,
        100% {
            border-color: rgba(248, 113, 113, 0.25);
        }

        50% {
            border-color: rgba(248, 113, 113, 0.5);
            box-shadow: 0 0 20px rgba(248, 113, 113, 0.08);
        }
    }

    .cd-alert-banner-icon {
        font-size: 22px;
        flex-shrink: 0;
    }

    .cd-alert-banner-text {
        flex: 1;
    }

    .cd-alert-banner-title {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--red);
        margin-bottom: 3px;
    }

    .cd-alert-banner-sub {
        font-size: 12px;
        color: var(--text2);
    }

    /* ── Type pills ── */
    .cd-type-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 14px;
    }

    .cd-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .cd-pill.red {
        background: var(--red-dim);
        color: var(--red);
        border: 1px solid rgba(248, 113, 113, 0.2);
    }

    .cd-pill.amber {
        background: var(--amber-dim);
        color: var(--amber);
        border: 1px solid rgba(251, 191, 36, 0.2);
    }

    .cd-pill.blue {
        background: var(--blue-dim);
        color: var(--blue);
        border: 1px solid rgba(96, 165, 250, 0.2);
    }

    .cd-pill.purple {
        background: var(--purple-dim);
        color: var(--purple);
        border: 1px solid rgba(167, 139, 250, 0.2);
    }

    .cd-pill.green {
        background: var(--green-dim);
        color: var(--green);
        border: 1px solid rgba(74, 222, 128, 0.2);
    }

    /* ── Severity meter ── */
    .cd-severity {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
    }

    .cd-severity-label {
        font-size: 11px;
        color: var(--text3);
        min-width: 56px;
    }

    .cd-severity-track {
        flex: 1;
        height: 5px;
        background: var(--bg4);
        border-radius: 3px;
        overflow: hidden;
    }

    .cd-severity-fill {
        height: 100%;
        border-radius: 3px;
    }

    .cd-severity-fill.high {
        background: var(--red);
        width: 92%;
    }

    .cd-severity-fill.medium {
        background: var(--amber);
        width: 61%;
    }

    .cd-severity-fill.low {
        background: var(--green);
        width: 24%;
    }

    .cd-severity-pct {
        font-size: 11px;
        font-family: var(--font-mono);
        color: var(--text3);
        min-width: 32px;
        text-align: right;
    }

    /* ── Checklist ── */
    .cd-checklist {
        display: flex;
        flex-direction: column;
        gap: 7px;
        margin-bottom: 14px;
    }

    .cd-check-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 12px;
        padding: 7px 10px;
        border-radius: var(--radius-sm);
        background: var(--bg3);
    }

    .cd-check-box {
        width: 16px;
        height: 16px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        flex-shrink: 0;
        font-weight: 700;
    }

    .cd-check-box.ok {
        background: var(--green-dim);
        color: var(--green);
    }

    .cd-check-box.fail {
        background: var(--red-dim);
        color: var(--red);
    }

    .cd-check-box.warn {
        background: var(--amber-dim);
        color: var(--amber);
    }

    .cd-check-text {
        flex: 1;
        color: var(--text2);
    }

    .cd-check-text.fail-text {
        color: var(--red);
        font-weight: 600;
    }

    .cd-check-text.warn-text {
        color: var(--amber);
    }

    /* ── Divider ── */
    .cd-divider {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 4px 0 12px;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text3);
        font-weight: 600;
    }

    .cd-divider::before,
    .cd-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border);
    }
</style>

{{-- ────────────────────────────────────────────────────
     BLOC PRINCIPAL — MODULE IA ANTI-CONFLITS
──────────────────────────────────────────────────── --}}

<div class="panel" style="margin-bottom: 24px;">

    {{-- PANEL HEADER --}}
    <div class="panel-head">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div
                style="width: 36px; height: 36px; border-radius: 8px; background: var(--red-dim); border: 1px solid rgba(248,113,113,0.25); display: flex; align-items: center; justify-content: center; font-size: 18px;">
                ⚡</div>
            <div>
                <div class="panel-title" style="display: flex; align-items: center; gap: 8px;">
                    Détection de Conflits IA
                    <span
                        style="font-size: 10px; font-weight: 700; padding: 2px 8px; background: var(--red-dim); color: var(--red); border-radius: 20px; letter-spacing: 0.5px; text-transform: uppercase;">LIVE</span>
                </div>
                <div class="panel-sub">L'IA empêche les dramas avant qu'ils existent — analyse en temps réel à chaque
                    soumission</div>
            </div>
        </div>
        <div style="display: flex; gap: 8px; align-items: center;">
            <button class="btn btn-outline btn-sm" onclick="showToast('Rapport de conflits exporté!', 'info')">📥
                Rapport</button>
            <button class="btn btn-outline btn-sm" onclick="showToast('Paramètres IA ouverts', 'info')">⚙️ Config
                IA</button>
        </div>
    </div>

    <div class="panel-body">
        <div class="cd-wrapper">

            {{-- HERO BANNER --}}
            <div class="cd-hero">
                <div class="cd-hero-pulse">⚡</div>
                <div class="cd-hero-text">
                    <div class="cd-hero-title">
                        Module Anti-Drama — Analyse instantanée à chaque soumission
                        <span class="badge red" style="font-size: 10px;">CRITIQUE</span>
                    </div>
                    <div class="cd-hero-sub">
                        Dès qu'un artiste ou impresario soumet une requête, l'IA vérifie en live : planning,
                        représentations multiples, œuvres déjà prêtées, chevauchements contractuels, exclusivités
                        actives.
                        Un conflit détecté = une alerte rouge + une solution proposée automatiquement.
                    </div>
                </div>
                <div class="cd-hero-stat">
                    <div class="cd-hero-stat-val">7</div>
                    <div class="cd-hero-stat-label">Conflits actifs</div>
                </div>
                <div class="cd-hero-timing">
                    <div class="cd-hero-timing-val">&lt;1s</div>
                    <div class="cd-hero-timing-label">Temps détection</div>
                </div>
            </div>

            {{-- LIVE SCAN BAR --}}
            <div class="cd-scan-panel">
                <div class="cd-scan-label">🔍 Analyse en cours</div>
                <div class="cd-scan-track">
                    <div class="cd-scan-fill"></div>
                </div>
                <div class="cd-scan-status">
                    <div class="cd-scan-dot"></div>
                    Moteur IA actif — 3 soumissions en cours
                </div>
            </div>

            {{-- ACTIVE CRITICAL ALERT BANNER --}}
            <div class="cd-alert-banner">
                <div class="cd-alert-banner-icon">🚨</div>
                <div class="cd-alert-banner-text">
                    <div class="cd-alert-banner-title">Conflit critique détecté il y a 2 min — REQ-2024-0854</div>
                    <div class="cd-alert-banner-sub">Artiste Karim Mansour : double représentation détectée — Scène
                        Nationale Tunis & Festival Carthage (même date). Intervention requise avant validation.</div>
                </div>
                <button class="cd-btn cd-btn-primary" onclick="openModal('modal-conflict-detail')">Voir le conflit
                    →</button>
            </div>

            {{-- TWO-COLUMN CONFLICT GRID --}}
            <div class="cd-grid">

                {{-- CARD 1 — Conflit planning double --}}
                <div class="cd-alert-card critical">
                    <div class="cd-card-head">
                        <div class="cd-card-icon red">📅</div>
                        <div class="cd-card-meta">
                            <div class="cd-card-title">Double Représentation Détectée</div>
                            <div class="cd-card-ref">REQ-2024-0854 · Karim Mansour · Soumis 14:28</div>
                        </div>
                        <div class="cd-card-badge critical">Critique</div>
                    </div>
                    <div class="cd-card-body">
                        {{-- Type pills --}}
                        <div class="cd-type-pills">
                            <span class="cd-pill red">🗓️ Planning</span>
                            <span class="cd-pill purple">📋 Contrat</span>
                        </div>
                        {{-- Severity --}}
                        <div class="cd-severity">
                            <span class="cd-severity-label">Sévérité</span>
                            <div class="cd-severity-track">
                                <div class="cd-severity-fill high"></div>
                            </div>
                            <span class="cd-severity-pct">92%</span>
                        </div>
                        {{-- Conflict items --}}
                        <div class="cd-conflict-items">
                            <div class="cd-conflict-item red-bg">
                                <div class="cd-conflict-item-dot red"></div>
                                Scène Nationale — Tunis : 12 juin 2024, 20h00–22h30 (contrat signé)
                            </div>
                            <div class="cd-conflict-item red-bg">
                                <div class="cd-conflict-item-dot red"></div>
                                Festival Carthage (nouveau dossier) : 12 juin 2024, 19h30–22h00
                            </div>
                        </div>
                        {{-- AI solution --}}
                        <div class="cd-solution">
                            <div class="cd-solution-label">🤖 Solution IA proposée</div>
                            <div class="cd-solution-text">Reporter la représentation Festival Carthage au <strong
                                    style="color:var(--blue);">14 juin</strong> (créneau 20h disponible) ou proposer un
                                artiste remplaçant depuis votre roster — <strong style="color:var(--blue);">3 profils
                                    compatibles</strong> identifiés.</div>
                        </div>
                    </div>
                    <div class="cd-card-foot">
                        <button class="cd-btn cd-btn-primary"
                            onclick="showToast('Solution appliquée automatiquement!', 'success')">✓ Appliquer la
                            solution</button>
                        <button class="cd-btn cd-btn-outline"
                            onclick="openModal('modal-conflict-detail')">Détails</button>
                        <button class="cd-btn cd-btn-danger" onclick="showToast('Soumission bloquée', 'error')">🚫
                            Bloquer</button>
                        <span class="cd-time-ago">Il y a 2 min</span>
                    </div>
                </div>

                {{-- CARD 2 — Œuvre déjà prêtée --}}
                <div class="cd-alert-card critical">
                    <div class="cd-card-head">
                        <div class="cd-card-icon red">🖼️</div>
                        <div class="cd-card-meta">
                            <div class="cd-card-title">Œuvre Déjà Prêtée</div>
                            <div class="cd-card-ref">REQ-2024-0851 · Collection Nationale · Soumis 13:55</div>
                        </div>
                        <div class="cd-card-badge critical">Critique</div>
                    </div>
                    <div class="cd-card-body">
                        <div class="cd-type-pills">
                            <span class="cd-pill red">🖼️ Œuvre</span>
                            <span class="cd-pill amber">📦 Disponibilité</span>
                        </div>
                        <div class="cd-severity">
                            <span class="cd-severity-label">Sévérité</span>
                            <div class="cd-severity-track">
                                <div class="cd-severity-fill high"></div>
                            </div>
                            <span class="cd-severity-pct">88%</span>
                        </div>
                        {{-- Checklist IA --}}
                        <div class="cd-checklist">
                            <div class="cd-check-item">
                                <div class="cd-check-box fail">✕</div>
                                <span class="cd-check-text fail-text">Œuvre REF-OEU-00342 : prêt actif jusqu'au
                                    30/09/2024 (Louvre Abu Dhabi)</span>
                            </div>
                            <div class="cd-check-item">
                                <div class="cd-check-box warn">!</div>
                                <span class="cd-check-text warn-text">Période demandée : 15 juil – 20 sept —
                                    chevauchement total</span>
                            </div>
                            <div class="cd-check-item">
                                <div class="cd-check-box ok">✓</div>
                                <span class="cd-check-text">Artiste disponible sur la période demandée</span>
                            </div>
                        </div>
                        <div class="cd-solution">
                            <div class="cd-solution-label">🤖 Solution IA proposée</div>
                            <div class="cd-solution-text">Décaler la période de prêt à partir du <strong
                                    style="color:var(--blue);">1er octobre 2024</strong> ou substituer avec l'œuvre
                                REF-OEU-00389 (style similaire, disponible immédiatement).</div>
                        </div>
                    </div>
                    <div class="cd-card-foot">
                        <button class="cd-btn cd-btn-primary"
                            onclick="showToast('Substitution appliquée!', 'success')">✓ Substituer l'œuvre</button>
                        <button class="cd-btn cd-btn-outline" onclick="showToast('Période ajustée', 'info')">📅
                            Réajuster dates</button>
                        <span class="cd-time-ago">Il y a 37 min</span>
                    </div>
                </div>

                {{-- CARD 3 — Chevauchement contractuel --}}
                <div class="cd-alert-card warning">
                    <div class="cd-card-head">
                        <div class="cd-card-icon amber">📋</div>
                        <div class="cd-card-meta">
                            <div class="cd-card-title">Chevauchement d'Exclusivité</div>
                            <div class="cd-card-ref">REQ-2024-0849 · Sofia Amrani · Soumis 11:10</div>
                        </div>
                        <div class="cd-card-badge warning">Avertissement</div>
                    </div>
                    <div class="cd-card-body">
                        <div class="cd-type-pills">
                            <span class="cd-pill amber">⚖️ Exclusivité</span>
                            <span class="cd-pill blue">🏢 Territoire</span>
                        </div>
                        <div class="cd-severity">
                            <span class="cd-severity-label">Sévérité</span>
                            <div class="cd-severity-track">
                                <div class="cd-severity-fill medium"></div>
                            </div>
                            <span class="cd-severity-pct">61%</span>
                        </div>
                        {{-- Mini timeline --}}
                        <div class="cd-divider">Chronologie du conflit</div>
                        <div class="cd-mini-timeline">
                            <div class="cd-mini-tl-item">
                                <div class="cd-mini-tl-dot" style="background: var(--amber);"></div>
                                <div style="flex:1;">Contrat exclusivité Maghreb — Agence ArtPro (valide jusqu'au
                                    31/12/2024)</div>
                                <div class="cd-mini-tl-time">actif</div>
                            </div>
                            <div class="cd-mini-tl-line"
                                style="margin-left: 3px; margin-top: -4px; margin-bottom: -4px;"></div>
                            <div class="cd-mini-tl-item">
                                <div class="cd-mini-tl-dot" style="background: var(--red);"></div>
                                <div style="flex:1;">Nouvelle demande représentation Maroc — promoteur indépendant
                                </div>
                                <div class="cd-mini-tl-time">conflit</div>
                            </div>
                        </div>
                        <div class="cd-solution">
                            <div class="cd-solution-label">🤖 Solution IA proposée</div>
                            <div class="cd-solution-text">Contacter ArtPro pour <strong
                                    style="color:var(--blue);">accord de sous-représentation</strong> ou attendre
                                l'expiration du contrat au 01/01/2025. Probabilité accord amiable : <strong
                                    style="color:var(--green);">74%</strong>.</div>
                        </div>
                    </div>
                    <div class="cd-card-foot">
                        <button class="cd-btn cd-btn-outline" onclick="showToast('Email envoyé à ArtPro', 'info')">✉️
                            Contacter ArtPro</button>
                        <button class="cd-btn cd-btn-outline" onclick="showToast('Mis en attente', 'info')">⏸
                            Suspendre</button>
                        <span class="cd-time-ago">Il y a 3h</span>
                    </div>
                </div>

                {{-- CARD 4 — Résolu par IA --}}
                <div class="cd-alert-card resolved">
                    <div class="cd-card-head">
                        <div class="cd-card-icon green">✅</div>
                        <div class="cd-card-meta">
                            <div class="cd-card-title">Conflit Résolu Automatiquement</div>
                            <div class="cd-card-ref">REQ-2024-0843 · Leila Soltani · Résolu 09:14</div>
                        </div>
                        <div class="cd-card-badge resolved">Résolu</div>
                    </div>
                    <div class="cd-card-body">
                        <div class="cd-type-pills">
                            <span class="cd-pill green">✓ Planning</span>
                            <span class="cd-pill green">✓ Contrat</span>
                        </div>
                        {{-- Stats mini --}}
                        <div class="cd-stats-row">
                            <div class="cd-stat-mini">
                                <div class="cd-stat-mini-val" style="color: var(--red);">3</div>
                                <div class="cd-stat-mini-lbl">Conflits trouvés</div>
                            </div>
                            <div class="cd-stat-mini">
                                <div class="cd-stat-mini-val" style="color: var(--green);">3</div>
                                <div class="cd-stat-mini-lbl">Auto-résolus</div>
                            </div>
                            <div class="cd-stat-mini">
                                <div class="cd-stat-mini-val" style="color: var(--teal);">0.8s</div>
                                <div class="cd-stat-mini-lbl">Temps IA</div>
                            </div>
                        </div>
                        {{-- Ring progress --}}
                        <div class="cd-ring-wrap">
                            <div class="cd-ring">
                                <svg width="54" height="54" viewBox="0 0 54 54">
                                    <circle cx="27" cy="27" r="22" fill="none" stroke="var(--bg4)"
                                        stroke-width="5" />
                                    <circle cx="27" cy="27" r="22" fill="none"
                                        stroke="var(--green)" stroke-width="5" stroke-dasharray="138.2"
                                        stroke-dashoffset="0" stroke-linecap="round" />
                                </svg>
                                <div class="cd-ring-val" style="color:var(--green);">100%</div>
                            </div>
                            <div class="cd-ring-info">
                                <div class="cd-ring-title">Résolution complète sans intervention humaine</div>
                                <div class="cd-ring-sub">Planning réajusté · Notification envoyée · Dossier validé
                                </div>
                            </div>
                        </div>
                        <div
                            style="padding: 10px 12px; background: var(--green-dim); border-radius: var(--radius-sm); font-size: 12px; color: var(--green); border: 1px solid rgba(74,222,128,0.2);">
                            ✓ L'IA a déplacé automatiquement la représentation du 8 au 10 juin — Leila Soltani a
                            confirmé par email.
                        </div>
                    </div>
                    <div class="cd-card-foot">
                        <button class="cd-btn cd-btn-success">✓ Validé</button>
                        <button class="cd-btn cd-btn-outline" onclick="showToast('Détails du rapport', 'info')">📄
                            Rapport complet</button>
                        <span class="cd-time-ago">Résolu 09:14</span>
                    </div>
                </div>

            </div>{{-- end cd-grid --}}

        </div>{{-- end cd-wrapper --}}
    </div>{{-- end panel-body --}}
</div>{{-- end panel --}}


{{-- ═══════════════════════════════════════════════
     MODAL — DÉTAIL DU CONFLIT CRITIQUE
═══════════════════════════════════════════════ --}}
<div class="modal" id="modal-conflict-detail">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <div class="modal-title" style="display:flex; align-items:center; gap:8px;">
                🚨 Détail du Conflit — REQ-2024-0854
                <span class="cd-card-badge critical" style="font-size:10px;">Critique</span>
            </div>
            <button class="modal-close" onclick="closeModal('modal-conflict-detail')">✕</button>
        </div>
        <div class="modal-body" style="display: flex; flex-direction: column; gap: 16px;">

            {{-- Artiste info --}}
            <div
                style="display: flex; align-items: center; gap: 12px; padding: 12px; background: var(--bg3); border-radius: var(--radius-sm); border: 1px solid var(--border);">
                <div class="avatar-sm" style="width: 40px; height: 40px; font-size: 14px;">KM</div>
                <div>
                    <div style="font-size: 14px; font-weight: 700; color: var(--text);">Karim Mansour</div>
                    <div style="font-size: 11px; color: var(--text3); font-family: var(--font-mono);">Artiste ·
                        REQ-2024-0854 · Soumis à 14:28:03</div>
                </div>
                <div style="margin-left:auto; text-align:right;">
                    <div style="font-size: 10px; color: var(--text3);">Détecté en</div>
                    <div
                        style="font-size: 18px; font-weight: 900; font-family: var(--font-mono); color: var(--green);">
                        0.7s</div>
                </div>
            </div>

            {{-- Conflict timeline --}}
            <div>
                <div class="cd-divider">Chevauchements détectés</div>
                <div class="cd-conflict-items">
                    <div class="cd-conflict-item red-bg" style="padding: 12px; margin-bottom: 0;">
                        <div class="cd-conflict-item-dot red" style="width:8px;height:8px;"></div>
                        <div>
                            <strong style="color:var(--text);">Scène Nationale Tunis</strong><br>
                            <span style="font-size:11px;color:var(--text3);">12 juin 2024 · 20h00–22h30 · Contrat signé
                                le 02/04/2024</span>
                        </div>
                        <span class="badge red" style="margin-left:auto; font-size:10px; flex-shrink:0;">SIGNÉ</span>
                    </div>
                    <div class="cd-conflict-item red-bg" style="padding: 12px; margin-bottom: 0;">
                        <div class="cd-conflict-item-dot red" style="width:8px;height:8px;"></div>
                        <div>
                            <strong style="color:var(--text);">Festival Carthage (nouveau)</strong><br>
                            <span style="font-size:11px;color:var(--text3);">12 juin 2024 · 19h30–22h00 · Soumis
                                aujourd'hui</span>
                        </div>
                        <span class="badge amber" style="margin-left:auto; font-size:10px; flex-shrink:0;">EN
                            ATTENTE</span>
                    </div>
                </div>
            </div>

            {{-- IA analysis breakdown --}}
            <div>
                <div class="cd-divider">Analyse IA détaillée</div>
                <div class="cd-checklist">
                    <div class="cd-check-item">
                        <div class="cd-check-box fail">✕</div>
                        <span class="cd-check-text fail-text">Chevauchement horaire de 2h30 (19h30 → 22h00 vs 20h00 →
                            22h30)</span>
                    </div>
                    <div class="cd-check-item">
                        <div class="cd-check-box fail">✕</div>
                        <span class="cd-check-text fail-text">Distance Tunis ↔ Carthage : 18 km — déplacement
                            impossible en cours de soirée</span>
                    </div>
                    <div class="cd-check-item">
                        <div class="cd-check-box warn">!</div>
                        <span class="cd-check-text warn-text">Clause d'exclusivité ville active sur le contrat Scène
                            Nationale</span>
                    </div>
                    <div class="cd-check-item">
                        <div class="cd-check-box ok">✓</div>
                        <span class="cd-check-text">Artiste disponible les jours adjacents (13, 14, 15 juin)</span>
                    </div>
                </div>
            </div>

            {{-- AI solutions --}}
            <div class="cd-solution" style="margin-bottom:0;">
                <div class="cd-solution-label">🤖 Solutions IA — choisissez une action</div>
                <div class="cd-solution-text" style="margin-bottom: 10px;">L'IA a identifié <strong
                        style="color:var(--blue);">3 options</strong> pour résoudre ce conflit :</div>
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <div style="display:flex; align-items:center; gap:10px; padding: 8px 10px; background: var(--bg3); border-radius: var(--radius-sm); border: 1px solid var(--border); font-size: 12px; cursor:pointer;"
                        onclick="showToast('Option 1 sélectionnée', 'info')">
                        <span style="font-weight:700; color:var(--green);">①</span>
                        Reporter Festival Carthage au <strong style="color:var(--blue);">14 juin · 20h00</strong> —
                        créneau libre confirmé
                        <span class="badge green" style="margin-left:auto; font-size:9px;">Recommandé</span>
                    </div>
                    <div style="display:flex; align-items:center; gap:10px; padding: 8px 10px; background: var(--bg3); border-radius: var(--radius-sm); border: 1px solid var(--border); font-size: 12px; cursor:pointer;"
                        onclick="showToast('Option 2 sélectionnée', 'info')">
                        <span style="font-weight:700; color:var(--amber);">②</span>
                        Proposer un artiste remplaçant — <strong style="color:var(--blue);">3 profils
                            compatibles</strong> disponibles
                    </div>
                    <div style="display:flex; align-items:center; gap:10px; padding: 8px 10px; background: var(--bg3); border-radius: var(--radius-sm); border: 1px solid var(--border); font-size: 12px; cursor:pointer;"
                        onclick="showToast('Option 3 sélectionnée', 'info')">
                        <span style="font-weight:700; color:var(--text3);">③</span>
                        Négocier la levée de clause exclusivité avec Scène Nationale
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('modal-conflict-detail')">Fermer</button>
            <button class="btn" style="background: var(--blue); color:#fff;"
                onclick="showToast('Solution appliquée!', 'success'); closeModal('modal-conflict-detail')">⚡ Appliquer
                Option ①</button>
        </div>
    </div>
</div>
@endsection
