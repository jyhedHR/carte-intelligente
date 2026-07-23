@extends('shared.layouts.backoffice')

@section('title', 'Statistiques')

@push('styles')
<style>
/* ════════════════════════════════════════════════════════════
   STATS PAGE — TABBED REDESIGN
   ════════════════════════════════════════════════════════════ */
.stats-page {
    padding: 1.25rem 1.75rem 3rem;
    max-width: 1600px;
}

/* ── Region filter ───────────────────────────────────────── */
.region-filter-bar {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 12px;
    padding: 0.6rem 1.1rem;
    margin-bottom: 1.5rem;
}
.region-filter-label {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: rgba(255,255,255,0.45);
    text-transform: uppercase;
    letter-spacing: 0.07em;
    white-space: nowrap;
}
.region-filter-select {
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.12);
    color: #fff;
    border-radius: 8px;
    padding: 0.35rem 0.75rem;
    font-size: 0.82rem;
    min-width: 180px;
    cursor: pointer;
}
.region-filter-clear {
    display: flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.78rem;
    color: rgba(255,255,255,0.45);
    text-decoration: none;
    padding: 0.3rem 0.6rem;
    border-radius: 6px;
    transition: all 0.15s;
}
.region-filter-clear:hover { color: #fff; background: rgba(255,255,255,0.06); }
.region-filter-badge {
    font-size: 0.73rem;
    font-weight: 600;
    color: #4f9cf9;
    background: rgba(79,156,249,0.12);
    border: 1px solid rgba(79,156,249,0.28);
    border-radius: 999px;
    padding: 0.2rem 0.65rem;
}

/* ── KPI Row ─────────────────────────────────────────────── */
.kpi-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 0.75rem;
    margin-bottom: 1.75rem;
}
.kpi-card {
    background: rgba(255,255,255,0.035);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 12px;
    padding: 1rem 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
    transition: border-color 0.2s, transform 0.15s;
    position: relative;
    overflow: hidden;
}
.kpi-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.03) 0%, transparent 60%);
    pointer-events: none;
}
.kpi-card:hover {
    border-color: rgba(255,255,255,0.14);
    transform: translateY(-1px);
}
.kpi-icon {
    font-size: 1.1rem;
    margin-bottom: 0.25rem;
    opacity: 0.7;
}
.kpi-value {
    font-size: 1.9rem;
    font-weight: 700;
    color: #fff;
    line-height: 1;
    letter-spacing: -0.02em;
}
.kpi-label {
    font-size: 0.7rem;
    color: rgba(255,255,255,0.5);
    text-transform: uppercase;
    letter-spacing: 0.08em;
    font-weight: 600;
}
.kpi-sub {
    font-size: 0.72rem;
    color: rgba(255,255,255,0.28);
    margin-top: 0.1rem;
}
.kpi-card.kpi-blue   { border-left: 2px solid #4f9cf9; }
.kpi-card.kpi-green  { border-left: 2px solid #4ade80; }
.kpi-card.kpi-amber  { border-left: 2px solid #fbbf24; }
.kpi-card.kpi-pink   { border-left: 2px solid #f472b6; }
.kpi-card.kpi-teal   { border-left: 2px solid #34d399; }
.kpi-card.kpi-violet { border-left: 2px solid #a78bfa; }
.kpi-card.kpi-red    { border-left: 2px solid #f87171; }
.kpi-card.kpi-orange { border-left: 2px solid #fb923c; }
.kpi-card.kpi-cyan   { border-left: 2px solid #06b6d4; }

/* ── Tab nav ─────────────────────────────────────────────── */
.stats-tabs-nav {
    display: flex;
    gap: 0.3rem;
    flex-wrap: wrap;
    margin-bottom: 1.25rem;
    border-bottom: 1px solid rgba(255,255,255,0.06);
    padding-bottom: 0.75rem;
}
.stats-tab-btn {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.45rem 1rem;
    border-radius: 8px;
    font-size: 0.78rem;
    font-weight: 600;
    border: 1px solid transparent;
    background: transparent;
    color: rgba(255,255,255,0.42);
    cursor: pointer;
    transition: all 0.18s;
    white-space: nowrap;
    letter-spacing: 0.02em;
}
.stats-tab-btn i { font-size: 0.95rem; }
.stats-tab-btn:hover {
    background: rgba(255,255,255,0.05);
    color: rgba(255,255,255,0.75);
}
.stats-tab-btn.active {
    background: rgba(79,156,249,0.12);
    border-color: rgba(79,156,249,0.35);
    color: #4f9cf9;
}
.tab-badge {
    font-size: 0.65rem;
    font-weight: 700;
    background: rgba(248,113,113,0.2);
    color: #f87171;
    border-radius: 999px;
    padding: 0.1rem 0.4rem;
    line-height: 1.4;
}

/* ── Tab panes ───────────────────────────────────────────── */
.stats-tab-pane { display: none; animation: fadeInTab 0.2s ease; }
.stats-tab-pane.active { display: block; }
@keyframes fadeInTab {
    from { opacity: 0; transform: translateY(4px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── Pane section label ──────────────────────────────────── */
.pane-section-label {
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.25);
    margin: 1.5rem 0 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.pane-section-label::after {
    content: '';
    flex: 1;
    height: 1px;
    background: rgba(255,255,255,0.05);
}

/* ── Chart grid ──────────────────────────────────────────── */
.charts-grid   { display: grid; gap: 1rem; }
.g-col-2 { grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); }
.g-col-3 { grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); }
.g-col-1 { grid-template-columns: 1fr; }

/* ── Chart card ──────────────────────────────────────────── */
.chart-card {
    background: rgba(255,255,255,0.025);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 14px;
    padding: 1.1rem 1.3rem 1.4rem;
    transition: border-color 0.2s;
}
.chart-card:hover { border-color: rgba(255,255,255,0.1); }
.chart-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.9rem;
}
.chart-card-title {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    font-size: 0.78rem;
    font-weight: 600;
    color: rgba(255,255,255,0.6);
}
.chart-card-title i { font-size: 0.95rem; color: rgba(255,255,255,0.28); }
.chart-wrap { position: relative; }

/* ── Inline stat row inside chart card ──────────────────── */
.inline-stats {
    display: flex;
    gap: 1.25rem;
    flex-wrap: wrap;
    margin-bottom: 0.85rem;
    padding-bottom: 0.85rem;
    border-bottom: 1px solid rgba(255,255,255,0.05);
}
.inline-stat { display: flex; flex-direction: column; gap: 0.1rem; }
.inline-stat-val {
    font-size: 1.25rem;
    font-weight: 700;
    color: #fff;
    letter-spacing: -0.02em;
}
.inline-stat-lbl {
    font-size: 0.66rem;
    color: rgba(255,255,255,0.38);
    text-transform: uppercase;
    letter-spacing: 0.07em;
}

/* ══════════════════════════════════════════
   LIGHT MODE OVERRIDES
   ══════════════════════════════════════════ */
body.light .region-filter-bar {
    background: #ffffff;
    border-color: rgba(0,0,0,0.09);
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
}
body.light .region-filter-label { color: rgba(0,0,0,0.5); }
body.light .region-filter-select {
    background: #f9fafb;
    border-color: rgba(0,0,0,0.14);
    color: #111827;
}
body.light .region-filter-clear { color: rgba(0,0,0,0.45); }
body.light .region-filter-clear:hover { color: #111827; background: rgba(0,0,0,0.04); }

body.light .kpi-card {
    background: #ffffff;
    border-color: rgba(0,0,0,0.09);
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
body.light .kpi-card::before { display: none; }
body.light .kpi-card:hover { border-color: rgba(0,0,0,0.16); }
body.light .kpi-value { color: #111827; }
body.light .kpi-label { color: rgba(0,0,0,0.5); }
body.light .kpi-sub   { color: rgba(0,0,0,0.38); }

body.light .stats-tabs-nav { border-bottom-color: rgba(0,0,0,0.08); }
body.light .stats-tab-btn { color: rgba(0,0,0,0.45); }
body.light .stats-tab-btn:hover { background: rgba(0,0,0,0.04); color: rgba(0,0,0,0.7); }
body.light .stats-tab-btn.active {
    background: rgba(79,156,249,0.1);
    border-color: rgba(79,156,249,0.4);
    color: #2563eb;
}

body.light .pane-section-label {
    color: rgba(0,0,0,0.35);
}
body.light .pane-section-label::after { background: rgba(0,0,0,0.07); }

body.light .chart-card {
    background: #ffffff;
    border-color: rgba(0,0,0,0.08);
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
body.light .chart-card:hover { border-color: rgba(0,0,0,0.14); }
body.light .chart-card-title { color: rgba(0,0,0,0.65); }
body.light .chart-card-title i { color: rgba(0,0,0,0.28); }
body.light .inline-stats { border-bottom-color: rgba(0,0,0,0.06); }
body.light .inline-stat-val { color: #111827; }
body.light .inline-stat-lbl { color: rgba(0,0,0,0.42); }
</style>
@endpush

@section('content')
<div class="stats-page">

{{-- ═══════════════════════════════════════════════════
     REGION FILTER
═══════════════════════════════════════════════════ --}}
<form method="GET" action="{{ url()->current() }}" id="regionFilterForm" class="region-filter-bar">
    <label for="regionSelect" class="region-filter-label">
        <i class="ti ti-filter"></i> Filtrer par région
    </label>
    <select name="region" id="regionSelect" class="region-filter-select"
            onchange="document.getElementById('regionFilterForm').submit()">
        <option value="all" {{ !$selectedRegion ? 'selected' : '' }}>Toutes les régions</option>
        @foreach($availableRegions as $region)
            <option value="{{ $region }}" {{ $selectedRegion === $region ? 'selected' : '' }}>
                {{ $region }}
            </option>
        @endforeach
    </select>
    @if($selectedRegion)
        <a href="{{ url()->current() }}" class="region-filter-clear">
            <i class="ti ti-x"></i> Réinitialiser
        </a>
        <span class="region-filter-badge">Vue filtrée : {{ $selectedRegion }}</span>
    @endif
</form>

{{-- ═══════════════════════════════════════════════════
     SINGLE KPI ROW — everything lives here, no second row
═══════════════════════════════════════════════════ --}}
<div class="kpi-row">
    <div class="kpi-card kpi-blue">
        <span class="kpi-icon"><i class="ti ti-file-stack"></i></span>
        <span class="kpi-value">{{ number_format($totalSubmissions) }}</span>
        <span class="kpi-label">Soumissions</span>
        <span class="kpi-sub">Total actives</span>
    </div>
    <div class="kpi-card kpi-amber">
        <span class="kpi-icon"><i class="ti ti-clipboard-list"></i></span>
        <span class="kpi-value">{{ number_format($totalDemandes) }}</span>
        <span class="kpi-label">Demandes</span>
        <span class="kpi-sub">Total créées</span>
    </div>
    <div class="kpi-card kpi-green">
        <span class="kpi-icon"><i class="ti ti-users"></i></span>
        <span class="kpi-value">{{ number_format($totalUsers) }}</span>
        <span class="kpi-label">Utilisateurs</span>
        <span class="kpi-sub">{{ $usersAdmin }} admins · {{ $usersArchives }} archivés</span>
    </div>
    <div class="kpi-card kpi-pink">
        <span class="kpi-icon"><i class="ti ti-forms"></i></span>
        <span class="kpi-value">{{ number_format($totalFormulaires) }}</span>
        <span class="kpi-label">Formulaires</span>
        <span class="kpi-sub">Total créés</span>
    </div>
    <div class="kpi-card kpi-cyan">
        <span class="kpi-icon"><i class="ti ti-map-pin"></i></span>
        <span class="kpi-value">{{ $totalRegionsCouvertes }}</span>
        <span class="kpi-label">Régions</span>
        <span class="kpi-sub">Couvertes</span>
    </div>
    <div class="kpi-card kpi-violet">
        <span class="kpi-icon"><i class="ti ti-git-branch"></i></span>
        <span class="kpi-value">{{ $workflowsActifs }}</span>
        <span class="kpi-label">Workflows</span>
        <span class="kpi-sub">{{ $workflowsInactifs }} inactifs</span>
    </div>
    <div class="kpi-card kpi-red">
        <span class="kpi-icon"><i class="ti ti-alert-circle"></i></span>
        <span class="kpi-value">{{ number_format($totalReclamations) }}</span>
        <span class="kpi-label">Réclamations</span>
        <span class="kpi-sub">{{ $reclEnAttente }} en attente</span>
    </div>
    <div class="kpi-card kpi-teal">
        <span class="kpi-icon"><i class="ti ti-calendar-check"></i></span>
        <span class="kpi-value">{{ number_format($validitesActives) }}</span>
        <span class="kpi-label">Validités actives</span>
        <span class="kpi-sub">{{ number_format($validitesExpirees) }} expirées</span>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════
     TAB NAV
═══════════════════════════════════════════════════ --}}
<nav class="stats-tabs-nav" role="tablist">
    <button class="stats-tab-btn active" data-tab="activite" role="tab">
        <i class="ti ti-chart-line"></i> Activité
    </button>
    <button class="stats-tab-btn" data-tab="statuts" role="tab">
        <i class="ti ti-chart-donut"></i> Statuts
    </button>
    <button class="stats-tab-btn" data-tab="formulaires" role="tab">
        <i class="ti ti-forms"></i> Formulaires
    </button>
    <button class="stats-tab-btn" data-tab="utilisateurs" role="tab">
        <i class="ti ti-users-group"></i> Utilisateurs
    </button>
    <button class="stats-tab-btn" data-tab="geo" role="tab">
        <i class="ti ti-map"></i> Géographie
    </button>
    <button class="stats-tab-btn" data-tab="notifications" role="tab">
        <i class="ti ti-bell"></i> Notifications
    </button>
    <button class="stats-tab-btn" data-tab="reclamations" role="tab">
        <i class="ti ti-alert-triangle"></i> Réclamations
        @if($reclEnAttente > 0)
            <span class="tab-badge">{{ $reclEnAttente }}</span>
        @endif
    </button>
</nav>

{{-- ═══════════════════════════════════════════════════
     TAB: ACTIVITÉ
═══════════════════════════════════════════════════ --}}
<div id="tab-activite" class="stats-tab-pane active">

    <div class="pane-section-label">Tendances sur 12 mois</div>
    <div class="charts-grid g-col-2">

        <div class="chart-card">
            <div class="chart-card-header">
                <div class="chart-card-title"><i class="ti ti-chart-line"></i> Soumissions — 12 derniers mois</div>
            </div>
            <div class="chart-wrap" style="height:220px"><canvas id="chartSubMois"></canvas></div>
        </div>

        <div class="chart-card">
            <div class="chart-card-header">
                <div class="chart-card-title"><i class="ti ti-chart-area"></i> Demandes — 12 derniers mois</div>
            </div>
            <div class="chart-wrap" style="height:220px"><canvas id="chartDemMois"></canvas></div>
        </div>

    </div>

    <div class="pane-section-label">Sessions récentes</div>
    <div class="charts-grid g-col-1">

        <div class="chart-card">
            <div class="chart-card-header">
                <div class="chart-card-title"><i class="ti ti-users"></i> Sessions utilisateurs — 14 derniers jours</div>
            </div>
            <div class="chart-wrap" style="height:200px"><canvas id="chartSessions"></canvas></div>
        </div>

    </div>

</div>

{{-- ═══════════════════════════════════════════════════
     TAB: STATUTS
═══════════════════════════════════════════════════ --}}
<div id="tab-statuts" class="stats-tab-pane">

    <div class="pane-section-label">Répartition par statut</div>
    <div class="charts-grid g-col-3">

        <div class="chart-card">
            <div class="chart-card-header">
                <div class="chart-card-title"><i class="ti ti-chart-pie"></i> Soumissions par statut</div>
            </div>
            <div class="chart-wrap" style="height:240px"><canvas id="chartSubStatut"></canvas></div>
        </div>

        <div class="chart-card">
            <div class="chart-card-header">
                <div class="chart-card-title"><i class="ti ti-chart-donut"></i> Demandes par statut</div>
            </div>
            <div class="chart-wrap" style="height:240px"><canvas id="chartDemStatut"></canvas></div>
        </div>

        <div class="chart-card">
            <div class="chart-card-header">
                <div class="chart-card-title"><i class="ti ti-file-check"></i> Formulaires par statut</div>
            </div>
            <div class="chart-wrap" style="height:240px"><canvas id="chartFormStatut"></canvas></div>
        </div>

    </div>

</div>

{{-- ═══════════════════════════════════════════════════
     TAB: FORMULAIRES
═══════════════════════════════════════════════════ --}}
<div id="tab-formulaires" class="stats-tab-pane">

    <div class="pane-section-label">Utilisation</div>
    <div class="charts-grid g-col-2">

        <div class="chart-card">
            <div class="chart-card-header">
                <div class="chart-card-title"><i class="ti ti-list-numbers"></i> Top 10 formulaires les plus soumis</div>
            </div>
            <div class="chart-wrap" style="height:{{ max(200, count($topFormulaires) * 38 + 60) }}px">
                <canvas id="chartTopForms"></canvas>
            </div>
        </div>

        @if($isSuperAdmin)
        <div class="chart-card">
            <div class="chart-card-header">
                <div class="chart-card-title"><i class="ti ti-building"></i> Soumissions par département</div>
            </div>
            <div class="chart-wrap" style="height:{{ max(200, count($submissionsParDept) * 38 + 60) }}px">
                <canvas id="chartDepts"></canvas>
            </div>
        </div>
        @endif

    </div>

    @if($pdfUsage->count() || $topFormulairesValidite->count())
    <div class="pane-section-label">PDF & Validités</div>
    <div class="charts-grid g-col-2">

        @if($pdfUsage->count())
        <div class="chart-card">
            <div class="chart-card-header">
                <div class="chart-card-title"><i class="ti ti-file-type-pdf"></i> Utilisation des templates PDF</div>
            </div>
            <div class="chart-wrap" style="height:{{ max(200, count($pdfUsage) * 40 + 60) }}px">
                <canvas id="chartPdf"></canvas>
            </div>
        </div>
        @endif

        @if($topFormulairesValidite->count())
        <div class="chart-card">
            <div class="chart-card-header">
                <div class="chart-card-title"><i class="ti ti-calendar-check"></i> Top formulaires par validités</div>
            </div>
            <div class="chart-wrap" style="height:{{ max(200, count($topFormulairesValidite) * 40 + 60) }}px">
                <canvas id="chartTopValidite"></canvas>
            </div>
        </div>
        @endif

    </div>
    @endif

</div>

{{-- ═══════════════════════════════════════════════════
     TAB: UTILISATEURS
═══════════════════════════════════════════════════ --}}
<div id="tab-utilisateurs" class="stats-tab-pane">

    <div class="pane-section-label">Rôles & Départements</div>
    <div class="charts-grid g-col-2">

        <div class="chart-card">
            <div class="chart-card-header">
                <div class="chart-card-title"><i class="ti ti-shield-check"></i> Distribution des rôles</div>
            </div>
            <div class="chart-wrap" style="height:260px"><canvas id="chartRoles"></canvas></div>
        </div>

        @if($isSuperAdmin)
        <div class="chart-card">
            <div class="chart-card-header">
                <div class="chart-card-title"><i class="ti ti-users-group"></i> Utilisateurs par département</div>
            </div>
            <div class="chart-wrap" style="height:{{ max(200, count($usersParDept) * 38 + 60) }}px">
                <canvas id="chartUsersDept"></canvas>
            </div>
        </div>
        @endif

    </div>

    @if($validationParAdmin->count())
    <div class="pane-section-label">Activité admin</div>
    <div class="charts-grid g-col-1">
        <div class="chart-card">
            <div class="chart-card-header">
                <div class="chart-card-title"><i class="ti ti-user-check"></i> Validations par admin</div>
            </div>
            <div class="chart-wrap" style="height:{{ max(200, count($validationParAdmin) * 40 + 60) }}px">
                <canvas id="chartValidAdmin"></canvas>
            </div>
        </div>
    </div>
    @endif

</div>

{{-- ═══════════════════════════════════════════════════
     TAB: GÉOGRAPHIE
═══════════════════════════════════════════════════ --}}
<div id="tab-geo" class="stats-tab-pane">

    <div class="pane-section-label">Répartition géographique</div>
    <div class="charts-grid g-col-3">

        <div class="chart-card">
            <div class="chart-card-header">
                <div class="chart-card-title"><i class="ti ti-users"></i> Utilisateurs par région</div>
            </div>
            <div class="chart-wrap" style="height:{{ max(200, count($usersParRegion) * 38 + 60) }}px">
                <canvas id="chartUsersRegion"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-card-header">
                <div class="chart-card-title"><i class="ti ti-map"></i> Demandes par région</div>
            </div>
            <div class="chart-wrap" style="height:{{ max(200, count($demandesParRegion) * 38 + 60) }}px">
                <canvas id="chartDemandesRegion"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-card-header">
                <div class="chart-card-title"><i class="ti ti-file-description"></i> Soumissions par région</div>
            </div>
            <div class="chart-wrap" style="height:{{ max(200, count($submissionsParRegion) * 38 + 60) }}px">
                <canvas id="chartSubmissionsRegion"></canvas>
            </div>
        </div>

    </div>

</div>

{{-- ═══════════════════════════════════════════════════
     TAB: NOTIFICATIONS
═══════════════════════════════════════════════════ --}}
<div id="tab-notifications" class="stats-tab-pane">

    <div class="pane-section-label">Notifications</div>
    <div class="charts-grid g-col-2">

        <div class="chart-card">
            <div class="chart-card-header">
                <div class="chart-card-title"><i class="ti ti-tag"></i> Par type</div>
            </div>
            <div class="chart-wrap" style="height:240px"><canvas id="chartNotifType"></canvas></div>
        </div>

        <div class="chart-card">
            <div class="chart-card-header">
                <div class="chart-card-title"><i class="ti ti-mail-check"></i> Lues vs Non lues</div>
            </div>
            <div class="inline-stats">
                <div class="inline-stat">
                    <span class="inline-stat-val" style="color:#4ade80;">{{ number_format($notifLues) }}</span>
                    <span class="inline-stat-lbl">Lues</span>
                </div>
                <div class="inline-stat">
                    <span class="inline-stat-val" style="color:#f87171;">{{ number_format($notifNonLues) }}</span>
                    <span class="inline-stat-lbl">Non lues</span>
                </div>
            </div>
            <div class="chart-wrap" style="height:200px"><canvas id="chartNotifLu"></canvas></div>
        </div>

    </div>

</div>

{{-- ═══════════════════════════════════════════════════
     TAB: RÉCLAMATIONS
     Note: KPI numbers are in the main row above.
     This tab is charts-only.
═══════════════════════════════════════════════════ --}}
<div id="tab-reclamations" class="stats-tab-pane">

    <div class="pane-section-label">Aperçu des réclamations</div>
    <div class="charts-grid g-col-3">

        <div class="chart-card">
            <div class="chart-card-header">
                <div class="chart-card-title"><i class="ti ti-chart-pie"></i> Par statut</div>
            </div>
            <div class="inline-stats">
                <div class="inline-stat">
                    <span class="inline-stat-val" style="color:#fb923c;">{{ $reclEnAttente }}</span>
                    <span class="inline-stat-lbl">En attente</span>
                </div>
                <div class="inline-stat">
                    <span class="inline-stat-val" style="color:#4ade80;">{{ $reclApprouvees }}</span>
                    <span class="inline-stat-lbl">Approuvées</span>
                </div>
                <div class="inline-stat">
                    <span class="inline-stat-val" style="color:#f87171;">{{ $reclRejetees }}</span>
                    <span class="inline-stat-lbl">Rejetées</span>
                </div>
            </div>
            <div class="chart-wrap" style="height:200px"><canvas id="chartReclStatut"></canvas></div>
        </div>

        <div class="chart-card">
            <div class="chart-card-header">
                <div class="chart-card-title"><i class="ti ti-adjustments"></i> Par type d'action admin</div>
            </div>
            <div class="chart-wrap" style="height:240px"><canvas id="chartReclAction"></canvas></div>
        </div>

        <div class="chart-card">
            <div class="chart-card-header">
                <div class="chart-card-title"><i class="ti ti-chart-line"></i> Évolution — 12 mois</div>
            </div>
            <div class="chart-wrap" style="height:240px"><canvas id="chartReclMois"></canvas></div>
        </div>

    </div>

    <div class="pane-section-label">Bonus & validité</div>
    <div class="charts-grid g-col-2">
        <div class="chart-card" style="display:flex; align-items:center; gap:2rem; padding:1.5rem 2rem;">
            <div style="display:flex;flex-direction:column;gap:0.2rem;">
                <span style="font-size:2.4rem;font-weight:700;color:#a78bfa;letter-spacing:-0.02em;">{{ number_format($avgBonusSubmissions, 1) }}</span>
                <span style="font-size:0.7rem;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:0.08em;">Bonus moy. par validité</span>
                <span style="font-size:0.78rem;color:rgba(255,255,255,0.28);">Soumissions supplémentaires accordées en moyenne</span>
            </div>
            <div style="width:1px;height:60px;background:rgba(255,255,255,0.07);"></div>
            <div style="display:flex;flex-direction:column;gap:0.2rem;">
                <span style="font-size:2.4rem;font-weight:700;color:#34d399;letter-spacing:-0.02em;">{{ number_format($validitesActives) }}</span>
                <span style="font-size:0.7rem;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:0.08em;">Validités actives</span>
                <span style="font-size:0.78rem;color:rgba(255,255,255,0.28);">{{ number_format($validitesExpirees) }} expirées</span>
            </div>
        </div>
    </div>

</div>

</div>{{-- .stats-page --}}
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
// ── Theme helpers ─────────────────────────────────────────────────────────────
const isLight = () => document.body.classList.contains('light');
const colors = () => {
    const light = isLight();
    return {
        TICK_COLOR:    light ? 'rgba(0,0,0,0.52)'       : 'rgba(255,255,255,0.42)',
        GRID_COLOR:    light ? 'rgba(0,0,0,0.07)'       : 'rgba(255,255,255,0.05)',
        LEGEND_COLOR:  light ? 'rgba(0,0,0,0.62)'       : 'rgba(255,255,255,0.52)',
        TOOLTIP_BG:    light ? 'rgba(255,255,255,0.98)' : 'rgba(15,15,20,0.94)',
        TOOLTIP_TITLE: light ? 'rgba(0,0,0,0.6)'        : 'rgba(255,255,255,0.65)',
        TOOLTIP_BODY:  light ? '#111'                   : '#fff',
        TOOLTIP_BORDER:light ? 'rgba(0,0,0,0.12)'       : 'rgba(255,255,255,0.08)',
    };
};

const PALETTE = [
    '#4f9cf9','#4ade80','#fbbf24','#f472b6','#a78bfa',
    '#34d399','#fb923c','#38bdf8','#e879f9','#facc15',
    '#60a5fa','#86efac','#fca5a5','#c4b5fd','#6ee7b7'
];

const STATUS_COLORS = {
    'EN_ATTENTE': '#fbbf24', 'APPROUVE': '#4ade80', 'REJETE': '#f87171',
    'EN_COURS': '#60a5fa',   'CLOTURE': '#94a3b8',
    'soumise': '#60a5fa',    'en_cours': '#fbbf24', 'en_attente': '#fb923c',
    'validee': '#4ade80',    'rejetee': '#f87171',  'cloturee': '#94a3b8',
    'Brouillon': '#fbbf24',  'Publié': '#4ade80',   'Archivé': '#94a3b8',
    'ACTIF': '#4f9cf9',
};

// ── Chart registry ────────────────────────────────────────────────────────────
const __charts = [];
function registerChart(chart) { __charts.push(chart); return chart; }

// ── Option builders ───────────────────────────────────────────────────────────
function getTooltipPlugin() {
    const c = colors();
    return {
        backgroundColor: c.TOOLTIP_BG,
        borderColor: c.TOOLTIP_BORDER,
        borderWidth: 1,
        titleColor: c.TOOLTIP_TITLE,
        bodyColor: c.TOOLTIP_BODY,
        padding: 10,
        cornerRadius: 8,
    };
}
function getAxisStyle() {
    const c = colors();
    return {
        grid:  { color: c.GRID_COLOR },
        ticks: { color: c.TICK_COLOR, font: { size: 11 } },
    };
}
function getLegendStyle() {
    const c = colors();
    return {
        display: true,
        position: 'bottom',
        labels: { color: c.LEGEND_COLOR, boxWidth: 12, font: { size: 11 }, padding: 16 }
    };
}
function baseOpts(extra = {}) {
    return {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: getTooltipPlugin(),
        },
        ...extra
    };
}
function pieOpts() {
    return {
        ...baseOpts(),
        plugins: {
            legend: getLegendStyle(),
            tooltip: getTooltipPlugin(),
        },
    };
}
function donutOpts(cutout = '62%') {
    return { ...pieOpts(), cutout };
}
function axisOpts(extra = {}) {
    const ax = getAxisStyle();
    return {
        x: { ...ax, ...extra.x },
        y: { ...ax, beginAtZero: true, ...extra.y },
    };
}

// ── PHP → JS data ─────────────────────────────────────────────────────────────
const subMoisLabels  = @json($submissionsParMois->pluck('mois'));
const subMoisData    = @json($submissionsParMois->pluck('total'));
const demMoisLabels  = @json($demandesParMois->pluck('mois'));
const demMoisData    = @json($demandesParMois->pluck('total'));
const sessionsLabels = @json($sessionsParJour->pluck('jour'));
const sessionsData   = @json($sessionsParJour->pluck('total'));
const subStatutLbls  = @json($submissionsParStatut->pluck('statut'));
const subStatutData  = @json($submissionsParStatut->pluck('total'));
const demStatutLbls  = @json($demandesParStatut->pluck('statut'));
const demStatutData  = @json($demandesParStatut->pluck('total'));
const formStatutLbls = @json($formulairesParStatut->pluck('statut'));
const formStatutData = @json($formulairesParStatut->pluck('total'));
const topFormsLbls   = @json($topFormulaires->pluck('titre'));
const topFormsData   = @json($topFormulaires->pluck('total'));
@if($isSuperAdmin)
const deptsLbls      = @json($submissionsParDept->pluck('name_fr'));
const deptsData      = @json($submissionsParDept->pluck('total'));
const usersDeptLbls  = @json($usersParDept->pluck('name_fr'));
const usersDeptData  = @json($usersParDept->pluck('total'));
@endif
const notifTypeLbls  = @json($notifParType->pluck('type'));
const notifTypeData  = @json($notifParType->pluck('total'));
const rolesLbls      = @json($rolesDistribution->pluck('name_fr'));
const rolesData      = @json($rolesDistribution->pluck('total'));
const pdfLbls        = @json($pdfUsage->pluck('name'));
const pdfData        = @json($pdfUsage->pluck('total'));
const validAdminLbls = @json($validationParAdmin->pluck('admin_name'));
const validAdminData = @json($validationParAdmin->pluck('total'));
const usersRegionLbls = @json($usersParRegion->pluck('region'));
const usersRegionData = @json($usersParRegion->pluck('total'));
const demRegionLbls   = @json($demandesParRegion->pluck('region'));
const demRegionData   = @json($demandesParRegion->pluck('total'));
const subRegionLbls   = @json($submissionsParRegion->pluck('region'));
const subRegionData   = @json($submissionsParRegion->pluck('total'));
const reclStatutLbls  = @json($reclParStatut->pluck('statut'));
const reclStatutData  = @json($reclParStatut->pluck('total'));
const reclActionLbls  = @json($reclParAction->pluck('action'));
const reclActionData  = @json($reclParAction->pluck('total'));
const reclMoisLbls    = @json($reclParMois->pluck('mois'));
const reclMoisData    = @json($reclParMois->pluck('total'));
@if($topFormulairesValidite->count())
const topValiditeLbls = @json($topFormulairesValidite->pluck('titre'));
const topValiditeData = @json($topFormulairesValidite->pluck('total'));
@endif

const RECL_STATUT_COLORS = { 'en_attente': '#fb923c', 'approuvee': '#4ade80', 'rejetee': '#f87171' };
const ACTION_LABELS = {
    'extra_submission':  'Soumissions supplémentaires',
    'reset_compteur':    'Reset compteur',
    'prolonger_validite':'Prolonger validité',
};

// ── 1. Soumissions / mois ────────────────────────────────────────────────────
registerChart(new Chart(document.getElementById('chartSubMois'), {
    type: 'line',
    data: {
        labels: subMoisLabels,
        datasets: [{
            label: 'Soumissions',
            data: subMoisData,
            borderColor: '#4f9cf9',
            backgroundColor: 'rgba(79,156,249,0.10)',
            tension: 0.4, fill: true, pointRadius: 4, pointBackgroundColor: '#4f9cf9',
        }]
    },
    options: baseOpts({ scales: axisOpts() })
}));

// ── 2. Demandes / mois ───────────────────────────────────────────────────────
registerChart(new Chart(document.getElementById('chartDemMois'), {
    type: 'line',
    data: {
        labels: demMoisLabels,
        datasets: [{
            label: 'Demandes',
            data: demMoisData,
            borderColor: '#fbbf24',
            backgroundColor: 'rgba(251,191,36,0.09)',
            tension: 0.4, fill: true, pointRadius: 4, pointBackgroundColor: '#fbbf24',
        }]
    },
    options: baseOpts({ scales: axisOpts() })
}));

// ── 3. Sessions ──────────────────────────────────────────────────────────────
registerChart(new Chart(document.getElementById('chartSessions'), {
    type: 'bar',
    data: {
        labels: sessionsLabels,
        datasets: [{
            label: 'Sessions',
            data: sessionsData,
            backgroundColor: 'rgba(167,139,250,0.6)',
            borderColor: '#a78bfa',
            borderWidth: 1, borderRadius: 5,
        }]
    },
    options: baseOpts({
        scales: axisOpts({ x: { ticks: { ...getAxisStyle().ticks, maxRotation: 45 } } })
    })
}));

// ── 4. Soumissions par statut ────────────────────────────────────────────────
registerChart(new Chart(document.getElementById('chartSubStatut'), {
    type: 'pie',
    data: {
        labels: subStatutLbls,
        datasets: [{
            data: subStatutData,
            backgroundColor: subStatutLbls.map(s => STATUS_COLORS[s] || '#94a3b8'),
            borderColor: 'rgba(128,128,128,0.12)', borderWidth: 2,
        }]
    },
    options: pieOpts()
}));

// ── 5. Demandes par statut ───────────────────────────────────────────────────
registerChart(new Chart(document.getElementById('chartDemStatut'), {
    type: 'doughnut',
    data: {
        labels: demStatutLbls,
        datasets: [{
            data: demStatutData,
            backgroundColor: demStatutLbls.map(s => STATUS_COLORS[s] || '#94a3b8'),
            borderColor: 'rgba(128,128,128,0.12)', borderWidth: 2,
        }]
    },
    options: donutOpts('62%')
}));

// ── 6. Formulaires par statut ────────────────────────────────────────────────
registerChart(new Chart(document.getElementById('chartFormStatut'), {
    type: 'doughnut',
    data: {
        labels: formStatutLbls,
        datasets: [{
            data: formStatutData,
            backgroundColor: formStatutLbls.map((s, i) => STATUS_COLORS[s] || PALETTE[i % PALETTE.length]),
            borderColor: 'rgba(128,128,128,0.12)', borderWidth: 2,
        }]
    },
    options: donutOpts('62%')
}));

// ── 7. Top formulaires ───────────────────────────────────────────────────────
registerChart(new Chart(document.getElementById('chartTopForms'), {
    type: 'bar',
    data: {
        labels: topFormsLbls,
        datasets: [{
            label: 'Soumissions',
            data: topFormsData,
            backgroundColor: topFormsLbls.map((_, i) => PALETTE[i % PALETTE.length] + 'bb'),
            borderColor:      topFormsLbls.map((_, i) => PALETTE[i % PALETTE.length]),
            borderWidth: 1, borderRadius: 4,
        }]
    },
    options: baseOpts({
        indexAxis: 'y',
        scales: {
            x: { ...getAxisStyle(), beginAtZero: true },
            y: { ...getAxisStyle(), ticks: { ...getAxisStyle().ticks, font: { size: 10 } } }
        }
    })
}));

@if($isSuperAdmin)
// ── 8. Soumissions par département ──────────────────────────────────────────
registerChart(new Chart(document.getElementById('chartDepts'), {
    type: 'bar',
    data: {
        labels: deptsLbls,
        datasets: [{
            label: 'Soumissions',
            data: deptsData,
            backgroundColor: deptsLbls.map((_, i) => PALETTE[(i + 4) % PALETTE.length] + 'bb'),
            borderColor:      deptsLbls.map((_, i) => PALETTE[(i + 4) % PALETTE.length]),
            borderWidth: 1, borderRadius: 4,
        }]
    },
    options: baseOpts({
        indexAxis: 'y',
        scales: {
            x: { ...getAxisStyle(), beginAtZero: true },
            y: getAxisStyle()
        }
    })
}));
@endif

// ── 9. Notif par type ────────────────────────────────────────────────────────
registerChart(new Chart(document.getElementById('chartNotifType'), {
    type: 'bar',
    data: {
        labels: notifTypeLbls,
        datasets: [{
            label: 'Notifications',
            data: notifTypeData,
            backgroundColor: notifTypeLbls.map((_, i) => PALETTE[i % PALETTE.length] + 'bb'),
            borderColor:      notifTypeLbls.map((_, i) => PALETTE[i % PALETTE.length]),
            borderWidth: 1, borderRadius: 5,
        }]
    },
    options: baseOpts({ scales: axisOpts() })
}));

// ── 10. Notif lues / non-lues ────────────────────────────────────────────────
registerChart(new Chart(document.getElementById('chartNotifLu'), {
    type: 'pie',
    data: {
        labels: ['Lues', 'Non lues'],
        datasets: [{
            data: [{{ $notifLues }}, {{ $notifNonLues }}],
            backgroundColor: ['rgba(74,222,128,0.75)', 'rgba(248,113,113,0.75)'],
            borderColor: ['#4ade80', '#f87171'], borderWidth: 2,
        }]
    },
    options: pieOpts()
}));

@if($isSuperAdmin)
// ── 11. Utilisateurs par département ────────────────────────────────────────
registerChart(new Chart(document.getElementById('chartUsersDept'), {
    type: 'bar',
    data: {
        labels: usersDeptLbls,
        datasets: [{
            label: 'Utilisateurs',
            data: usersDeptData,
            backgroundColor: usersDeptLbls.map((_, i) => PALETTE[(i + 2) % PALETTE.length] + 'bb'),
            borderColor:      usersDeptLbls.map((_, i) => PALETTE[(i + 2) % PALETTE.length]),
            borderWidth: 1, borderRadius: 4,
        }]
    },
    options: baseOpts({
        indexAxis: 'y',
        scales: {
            x: { ...getAxisStyle(), beginAtZero: true },
            y: getAxisStyle()
        }
    })
}));
@endif

// ── 12. Rôles ────────────────────────────────────────────────────────────────
registerChart(new Chart(document.getElementById('chartRoles'), {
    type: 'doughnut',
    data: {
        labels: rolesLbls,
        datasets: [{
            data: rolesData,
            backgroundColor: rolesLbls.map((_, i) => PALETTE[(i + 6) % PALETTE.length]),
            borderColor: 'rgba(128,128,128,0.12)', borderWidth: 2,
        }]
    },
    options: donutOpts('58%')
}));

// ── 13. PDF templates ────────────────────────────────────────────────────────
@if($pdfUsage->count())
registerChart(new Chart(document.getElementById('chartPdf'), {
    type: 'bar',
    data: {
        labels: pdfLbls,
        datasets: [{
            label: 'Générations',
            data: pdfData,
            backgroundColor: pdfLbls.map((_, i) => PALETTE[(i + 8) % PALETTE.length] + 'bb'),
            borderColor:      pdfLbls.map((_, i) => PALETTE[(i + 8) % PALETTE.length]),
            borderWidth: 1, borderRadius: 4,
        }]
    },
    options: baseOpts({
        indexAxis: 'y',
        scales: {
            x: { ...getAxisStyle(), beginAtZero: true },
            y: { ...getAxisStyle(), ticks: { ...getAxisStyle().ticks, font: { size: 10 } } }
        }
    })
}));
@endif

// ── 14. Validations par admin ────────────────────────────────────────────────
@if($validationParAdmin->count())
registerChart(new Chart(document.getElementById('chartValidAdmin'), {
    type: 'bar',
    data: {
        labels: validAdminLbls,
        datasets: [{
            label: 'Validations',
            data: validAdminData,
            backgroundColor: 'rgba(74,222,128,0.60)',
            borderColor: '#4ade80',
            borderWidth: 1, borderRadius: 4,
        }]
    },
    options: baseOpts({
        indexAxis: 'y',
        scales: {
            x: { ...getAxisStyle(), beginAtZero: true },
            y: getAxisStyle()
        }
    })
}));
@endif

// ── 15. Utilisateurs par région ──────────────────────────────────────────────
registerChart(new Chart(document.getElementById('chartUsersRegion'), {
    type: 'bar',
    data: {
        labels: usersRegionLbls,
        datasets: [{
            label: 'Utilisateurs',
            data: usersRegionData,
            backgroundColor: usersRegionLbls.map((_, i) => PALETTE[(i + 9) % PALETTE.length] + 'bb'),
            borderColor:      usersRegionLbls.map((_, i) => PALETTE[(i + 9) % PALETTE.length]),
            borderWidth: 1, borderRadius: 4,
        }]
    },
    options: baseOpts({
        indexAxis: 'y',
        scales: {
            x: { ...getAxisStyle(), beginAtZero: true },
            y: getAxisStyle()
        }
    })
}));

// ── 16. Demandes par région ──────────────────────────────────────────────────
registerChart(new Chart(document.getElementById('chartDemandesRegion'), {
    type: 'bar',
    data: {
        labels: demRegionLbls,
        datasets: [{
            label: 'Demandes',
            data: demRegionData,
            backgroundColor: demRegionLbls.map((_, i) => PALETTE[(i + 11) % PALETTE.length] + 'bb'),
            borderColor:      demRegionLbls.map((_, i) => PALETTE[(i + 11) % PALETTE.length]),
            borderWidth: 1, borderRadius: 4,
        }]
    },
    options: baseOpts({
        indexAxis: 'y',
        scales: {
            x: { ...getAxisStyle(), beginAtZero: true },
            y: getAxisStyle()
        }
    })
}));

// ── 17. Soumissions par région ───────────────────────────────────────────────
registerChart(new Chart(document.getElementById('chartSubmissionsRegion'), {
    type: 'bar',
    data: {
        labels: subRegionLbls,
        datasets: [{
            label: 'Soumissions',
            data: subRegionData,
            backgroundColor: subRegionLbls.map((_, i) => PALETTE[(i + 13) % PALETTE.length] + 'bb'),
            borderColor:      subRegionLbls.map((_, i) => PALETTE[(i + 13) % PALETTE.length]),
            borderWidth: 1, borderRadius: 4,
        }]
    },
    options: baseOpts({
        indexAxis: 'y',
        scales: {
            x: { ...getAxisStyle(), beginAtZero: true },
            y: getAxisStyle()
        }
    })
}));

// ── 18. Réclamations par statut ──────────────────────────────────────────────
registerChart(new Chart(document.getElementById('chartReclStatut'), {
    type: 'pie',
    data: {
        labels: reclStatutLbls,
        datasets: [{
            data: reclStatutData,
            backgroundColor: reclStatutLbls.map(s => RECL_STATUT_COLORS[s] || '#94a3b8'),
            borderColor: 'rgba(128,128,128,0.12)', borderWidth: 2,
        }]
    },
    options: pieOpts()
}));

// ── 19. Réclamations par action ──────────────────────────────────────────────
registerChart(new Chart(document.getElementById('chartReclAction'), {
    type: 'bar',
    data: {
        labels: reclActionLbls.map(a => ACTION_LABELS[a] || a),
        datasets: [{
            label: 'Réclamations',
            data: reclActionData,
            backgroundColor: ['rgba(79,156,249,0.7)','rgba(167,139,250,0.7)','rgba(52,211,153,0.7)'],
            borderColor:      ['#4f9cf9','#a78bfa','#34d399'],
            borderWidth: 1, borderRadius: 5,
        }]
    },
    options: baseOpts({ scales: axisOpts() })
}));

// ── 20. Réclamations / mois ──────────────────────────────────────────────────
registerChart(new Chart(document.getElementById('chartReclMois'), {
    type: 'line',
    data: {
        labels: reclMoisLbls,
        datasets: [{
            label: 'Réclamations',
            data: reclMoisData,
            borderColor: '#f87171',
            backgroundColor: 'rgba(248,113,113,0.09)',
            tension: 0.4, fill: true, pointRadius: 4, pointBackgroundColor: '#f87171',
        }]
    },
    options: baseOpts({ scales: axisOpts() })
}));

// ── 21. Top formulaires par validités ────────────────────────────────────────
@if($topFormulairesValidite->count())
registerChart(new Chart(document.getElementById('chartTopValidite'), {
    type: 'bar',
    data: {
        labels: topValiditeLbls,
        datasets: [{
            label: 'Validités',
            data: topValiditeData,
            backgroundColor: topValiditeLbls.map((_, i) => PALETTE[(i + 3) % PALETTE.length] + 'bb'),
            borderColor:      topValiditeLbls.map((_, i) => PALETTE[(i + 3) % PALETTE.length]),
            borderWidth: 1, borderRadius: 4,
        }]
    },
    options: baseOpts({
        indexAxis: 'y',
        scales: {
            x: { ...getAxisStyle(), beginAtZero: true },
            y: { ...getAxisStyle(), ticks: { ...getAxisStyle().ticks, font: { size: 10 } } }
        }
    })
}));
@endif

// ── Tab switching ─────────────────────────────────────────────────────────────
document.querySelectorAll('.stats-tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        // Deactivate all
        document.querySelectorAll('.stats-tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.stats-tab-pane').forEach(p => p.classList.remove('active'));
        // Activate clicked
        btn.classList.add('active');
        const pane = document.getElementById('tab-' + btn.dataset.tab);
        if (pane) pane.classList.add('active');
        // Chart.js can't measure hidden canvases — force a resize after reveal
        requestAnimationFrame(() => __charts.forEach(c => c.resize()));
    });
});

// ── Theme repaint ─────────────────────────────────────────────────────────────
function repaintCharts() {
    const c = colors();
    __charts.forEach(chart => {
        if (chart.options.plugins?.legend?.labels) {
            chart.options.plugins.legend.labels.color = c.LEGEND_COLOR;
        }
        if (chart.options.plugins?.tooltip) {
            Object.assign(chart.options.plugins.tooltip, getTooltipPlugin());
        }
        if (chart.options.scales) {
            Object.entries(chart.options.scales).forEach(([, axis]) => {
                if (axis.ticks) axis.ticks.color = c.TICK_COLOR;
                if (axis.grid)  axis.grid.color  = c.GRID_COLOR;
            });
        }
        chart.update();
    });
}

repaintCharts();
new MutationObserver(repaintCharts).observe(document.body, { attributes: true, attributeFilter: ['class'] });
</script>
@endpush
