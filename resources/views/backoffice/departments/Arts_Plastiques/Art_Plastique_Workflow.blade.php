@extends('shared.layouts.backoffice')

@section('title', 'Moteur de Workflows — Arts Plastiques')
@section('breadcrumb', 'Workflows Arts Plastiques')

@section('content')

    {{-- ══════════════════════════════════════════════════════
     STYLES
══════════════════════════════════════════════════════ --}}
    <style>
        /* ── KPI row ── */
        .ap-kpi-row {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        @media(max-width:1100px) {
            .ap-kpi-row {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media(max-width:700px) {
            .ap-kpi-row {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .ap-kpi {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: border-color .2s;
        }

        .ap-kpi:hover {
            border-color: var(--border2);
        }

        .ap-kpi-icon {
            width: 38px;
            height: 38px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
        }

        .ap-kpi-val {
            font-size: 22px;
            font-weight: 900;
            font-family: var(--font-mono);
            line-height: 1;
        }

        .ap-kpi-lbl {
            font-size: 10px;
            color: var(--text3);
            text-transform: uppercase;
            letter-spacing: .6px;
            margin-top: 3px;
        }

        .ap-kpi-skeleton {
            height: 22px;
            background: var(--bg4);
            border-radius: 4px;
            animation: ap-shimmer 1.4s infinite;
        }

        @keyframes ap-shimmer {
            0% {
                opacity: .5
            }

            50% {
                opacity: 1
            }

            100% {
                opacity: .5
            }
        }

        /* ── IA banner ── */
        .ap-ia-banner {
            background: linear-gradient(135deg, rgba(45, 212, 191, .07), rgba(96, 165, 250, .05));
            border: 1px solid rgba(45, 212, 191, .2);
            border-radius: var(--radius);
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        .ap-ia-banner::after {
            content: '🎨';
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 52px;
            opacity: .06;
            pointer-events: none;
        }

        .ap-ia-orb {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--teal-dim);
            border: 1px solid rgba(45, 212, 191, .3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
            animation: ap-pulse 3s ease-in-out infinite;
        }

        @keyframes ap-pulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(45, 212, 191, .35);
            }

            50% {
                box-shadow: 0 0 0 8px rgba(45, 212, 191, 0);
            }
        }

        .ap-ia-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 5px;
        }

        .ap-ia-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
        }

        .ap-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 11px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity .15s;
        }

        .ap-chip:hover {
            opacity: .8;
        }

        /* ── Layout ── */
        .ap-shell {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 18px;
            align-items: start;
        }

        @media(max-width:1020px) {
            .ap-shell {
                grid-template-columns: 1fr;
            }
        }

        /* ── Filter bar ── */
        .ap-filterbar {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 10px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .ap-ftabs {
            display: flex;
            gap: 0;
        }

        .ap-ftab {
            padding: 6px 14px;
            font-size: 12px;
            font-weight: 600;
            color: var(--text3);
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all .15s;
            white-space: nowrap;
        }

        .ap-ftab:hover {
            color: var(--text2);
        }

        .ap-ftab.active {
            color: var(--teal);
            border-bottom-color: var(--teal);
        }

        .ap-search {
            flex: 1;
            min-width: 160px;
            background: var(--bg3);
            border: 1px solid var(--border2);
            border-radius: var(--radius-sm);
            padding: 6px 11px;
            font-size: 12px;
            color: var(--text);
            font-family: var(--font-body);
            outline: none;
            transition: border-color .18s;
        }

        .ap-search:focus {
            border-color: var(--teal);
        }

        /* ── Process cards ── */
        .ap-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .ap-card {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            transition: border-color .2s, transform .15s;
        }

        .ap-card:hover {
            border-color: var(--border2);
            transform: translateY(-1px);
        }

        .ap-card[data-color="gold"] {
            border-left: 3px solid var(--gold);
        }

        .ap-card[data-color="teal"] {
            border-left: 3px solid var(--teal);
        }

        .ap-card[data-color="purple"] {
            border-left: 3px solid var(--purple);
        }

        .ap-card[data-color="blue"] {
            border-left: 3px solid var(--blue);
        }

        .ap-card-head {
            padding: 16px 18px 12px;
            display: flex;
            align-items: flex-start;
            gap: 13px;
            border-bottom: 1px solid var(--border);
        }

        .ap-card-icon {
            width: 44px;
            height: 44px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
            flex-shrink: 0;
        }

        .ap-card-meta {
            flex: 1;
            min-width: 0;
        }

        .ap-card-num {
            font-size: 9.5px;
            font-family: var(--font-mono);
            font-weight: 700;
            color: var(--text3);
            text-transform: uppercase;
            letter-spacing: .9px;
            margin-bottom: 4px;
        }

        .ap-card-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text);
            line-height: 1.3;
        }

        .ap-card-output {
            font-size: 11px;
            color: var(--text3);
            margin-top: 4px;
        }

        .ap-card-badges {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 5px;
            flex-shrink: 0;
        }

        .ap-card-stats {
            display: flex;
            gap: 0;
            border-bottom: 1px solid var(--border);
        }

        .ap-cstat {
            flex: 1;
            text-align: center;
            padding: 10px 0;
            border-right: 1px solid var(--border);
        }

        .ap-cstat:last-child {
            border-right: none;
        }

        .ap-cstat-val {
            font-size: 18px;
            font-weight: 900;
            font-family: var(--font-mono);
        }

        .ap-cstat-lbl {
            font-size: 9.5px;
            color: var(--text3);
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-top: 2px;
        }

        /* ── BPMN flow ── */
        .ap-flow-row {
            padding: 12px 18px;
            border-bottom: 1px solid var(--border);
            overflow-x: auto;
        }

        .ap-flow-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .7px;
            color: var(--text3);
            font-weight: 700;
            margin-bottom: 8px;
        }

        .ap-flow {
            display: flex;
            align-items: flex-start;
            gap: 0;
            min-width: max-content;
        }

        .ap-fnode {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            flex-shrink: 0;
        }

        .ap-fnode-box {
            padding: 7px 12px;
            border-radius: 7px;
            border: 1.5px solid var(--border2);
            background: var(--bg3);
            font-size: 10.5px;
            font-weight: 600;
            color: var(--text2);
            text-align: center;
            max-width: 100px;
            line-height: 1.3;
            white-space: nowrap;
        }

        .ap-fnode-box.f-done {
            background: var(--green-dim);
            border-color: var(--green);
            color: var(--green);
        }

        .ap-fnode-box.f-active {
            background: var(--teal-dim);
            border-color: var(--teal);
            color: var(--teal);
            animation: ap-node-glow 2s ease-in-out infinite;
        }

        .ap-fnode-box.f-end {
            background: var(--gold-dim);
            border-color: var(--gold);
            color: var(--gold);
        }

        @keyframes ap-node-glow {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(45, 212, 191, .4);
            }

            50% {
                box-shadow: 0 0 0 5px rgba(45, 212, 191, 0);
            }
        }

        .ap-fnode-actor {
            font-size: 9px;
            color: var(--text3);
            text-align: center;
            max-width: 100px;
            line-height: 1.2;
        }

        .ap-connector {
            display: flex;
            align-items: center;
            flex-shrink: 0;
            padding-top: 18px;
        }

        .ap-connector-line {
            width: 28px;
            height: 1.5px;
            background: var(--border2);
        }

        .ap-connector-arr {
            color: var(--text3);
            font-size: 10px;
            margin-left: -2px;
        }

        .ap-card-foot {
            display: flex;
            gap: 8px;
            padding: 12px 18px;
        }

        .ap-foot-btn {
            flex: 1;
            padding: 8px;
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-weight: 600;
            font-family: var(--font-body);
            border: 1px solid var(--border);
            background: var(--bg3);
            color: var(--text2);
            cursor: pointer;
            transition: all .18s;
        }

        .ap-foot-btn:hover {
            border-color: var(--border2);
            color: var(--text);
        }

        .ap-foot-btn.gold {
            background: var(--gold);
            border-color: var(--gold);
            color: #111;
            font-weight: 700;
        }

        .ap-foot-btn.gold:hover {
            background: var(--gold2);
        }

        /* ── Sidebar ── */
        .ap-sidebar {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .ap-side-panel {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .ap-side-head {
            padding: 12px 14px;
            border-bottom: 1px solid var(--border);
            font-size: 12px;
            font-weight: 700;
            color: var(--text2);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .ap-side-body {
            padding: 14px;
        }

        .ap-task-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-bottom: 1px solid var(--border);
            cursor: pointer;
            transition: background .15s;
        }

        .ap-task-row:hover {
            background: var(--bg3);
        }

        .ap-task-row:last-child {
            border-bottom: none;
        }

        .ap-task-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .ap-task-name {
            flex: 1;
            font-size: 12px;
            font-weight: 600;
            color: var(--text);
        }

        .ap-task-meta {
            font-size: 10px;
            color: var(--text3);
            font-family: var(--font-mono);
        }

        /* ── Table ── */
        .ap-tbl {
            width: 100%;
            border-collapse: collapse;
            font-size: 12.5px;
        }

        .ap-tbl th {
            padding: 9px 12px;
            text-align: left;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--text3);
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .ap-tbl td {
            padding: 10px 12px;
            border-bottom: 1px solid var(--border);
            color: var(--text2);
            vertical-align: middle;
        }

        .ap-tbl tr:last-child td {
            border-bottom: none;
        }

        .ap-tbl tr:hover td {
            background: var(--bg3);
        }

        /* Action buttons in table */
        .ap-act-btn {
            padding: 4px 10px;
            border-radius: 5px;
            font-size: 11px;
            font-weight: 700;
            font-family: var(--font-body);
            border: 1px solid;
            cursor: pointer;
            transition: all .15s;
            white-space: nowrap;
        }

        .ap-act-approve {
            background: var(--green-dim);
            border-color: rgba(74, 222, 128, .4);
            color: var(--green);
        }

        .ap-act-approve:hover {
            background: var(--green);
            color: #111;
        }

        .ap-act-reject {
            background: var(--red-dim);
            border-color: rgba(248, 113, 113, .4);
            color: var(--red);
        }

        .ap-act-reject:hover {
            background: var(--red);
            color: #fff;
        }

        .ap-act-view {
            background: var(--bg4);
            border-color: var(--border2);
            color: var(--text2);
        }

        .ap-act-view:hover {
            border-color: var(--border2);
            color: var(--text);
        }

        /* ── Modal detail tabs ── */
        .ap-modal-tabs {
            display: flex;
            gap: 0;
            border-bottom: 1px solid var(--border);
            margin-bottom: 16px;
        }

        .ap-mtab {
            padding: 10px 16px;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--text3);
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all .15s;
        }

        .ap-mtab:hover {
            color: var(--text2);
        }

        .ap-mtab.active {
            color: var(--teal);
            border-bottom-color: var(--teal);
        }

        /* ── Start modal ── */
        .wf-start-field {
            margin-bottom: 14px;
        }

        .wf-start-label {
            display: block;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--text3);
            margin-bottom: 5px;
        }

        .wf-start-input {
            width: 100%;
            padding: 9px 12px;
            background: var(--bg3);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text);
            font-family: var(--font-body);
            font-size: 13px;
            transition: border-color .18s;
        }

        .wf-start-input:focus {
            outline: none;
            border-color: var(--teal);
        }

        /* ── Approve/Reject modal ── */
        .ap-action-banner {
            padding: 16px;
            border-radius: var(--radius-sm);
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .ap-action-approve-banner {
            background: var(--green-dim);
            border: 1px solid rgba(74, 222, 128, .3);
        }

        .ap-action-reject-banner {
            background: var(--red-dim);
            border: 1px solid rgba(248, 113, 113, .3);
        }

        /* ── Empty / Loading states ── */
        .ap-empty {
            text-align: center;
            padding: 40px 20px;
            color: var(--text3);
        }

        .ap-empty-icon {
            font-size: 32px;
            opacity: .3;
            margin-bottom: 10px;
        }

        .ap-loading-row td {
            text-align: center;
            padding: 30px;
            color: var(--text3);
            font-size: 12px;
        }

        /* ── Live badge ── */
        .ap-live {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 10px;
            font-weight: 700;
            color: var(--green);
        }

        .ap-live-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--green);
            animation: ap-pulse 2s infinite;
        }

        /* ── Polling indicator ── */
        .ap-sync {
            font-size: 10px;
            color: var(--text3);
            font-family: var(--font-mono);
        }
    </style>

    {{-- ══ LIVE KPI ROW ══ --}}
    <div class="ap-kpi-row" id="apGlobalKpis">
        @foreach ([['id' => 'gkpi-total', 'icon' => '⚙️', 'lbl' => 'Total instances', 'color' => 'blue'], ['id' => 'gkpi-active', 'icon' => '🔄', 'lbl' => 'En cours', 'color' => 'teal'], ['id' => 'gkpi-done', 'icon' => '✅', 'lbl' => 'Terminées', 'color' => 'green'], ['id' => 'gkpi-pending', 'icon' => '⏳', 'lbl' => 'En attente décision', 'color' => 'amber'], ['id' => 'gkpi-blocked', 'icon' => '🔴', 'lbl' => 'Bloquées', 'color' => 'red']] as $k)
            <div class="ap-kpi">
                <div class="ap-kpi-icon" style="background:var(--{{ $k['color'] }}-dim);">{{ $k['icon'] }}</div>
                <div>
                    <div class="ap-kpi-val" id="{{ $k['id'] }}" style="color:var(--{{ $k['color'] }});">
                        <div class="ap-kpi-skeleton" style="width:40px;"></div>
                    </div>
                    <div class="ap-kpi-lbl">{{ $k['lbl'] }}</div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ══ IA BANNER ══ --}}
    <div class="ap-ia-banner">
        <div class="ap-ia-orb">🤖</div>
        <div style="flex:1;">
            <div class="ap-ia-title">
                Assistant IA — Moteur de Workflows Arts Plastiques
                <span class="ap-live"><span class="ap-live-dot"></span>Live Camunda</span>
            </div>
            <div class="ap-ia-chips">
                <span class="ap-chip" style="background:var(--amber-dim);color:var(--amber);" id="chip-pending">⏳ ... en
                    attente</span>
                <span class="ap-chip" style="background:var(--red-dim);color:var(--red);" id="chip-blocked">🔴 ...
                    bloquées</span>
                <span class="ap-chip" style="background:var(--green-dim);color:var(--green);" id="chip-done">✅ Terminées ce
                    mois</span>
                <span class="ap-chip" style="background:var(--blue-dim);color:var(--blue);">📊 Camunda 7 · REST API</span>
            </div>
        </div>
        <div>
            <span class="ap-sync" id="apSyncTime">Synchronisation…</span>
        </div>
    </div>

    {{-- ══ FILTER BAR ══ --}}
    <div class="ap-filterbar">
        <div class="ap-ftabs">
            <div class="ap-ftab active" onclick="apSetFilter(this,'all')">Tous les processus</div>
            <div class="ap-ftab" onclick="apSetFilter(this,'active')">En cours</div>
            <div class="ap-ftab" onclick="apSetFilter(this,'pending')">À valider</div>
            <div class="ap-ftab" onclick="apSetFilter(this,'done')">Terminés</div>
        </div>
        <input class="ap-search" placeholder="🔍 Rechercher une instance, référence…" id="apSearchInput"
            oninput="apSearchFilter(this.value)">
        <button class="btn btn-outline btn-sm" onclick="apRefreshAll()">🔄 Actualiser</button>
        <button class="btn btn-gold btn-sm" onclick="apOpenStartModal()">+ Nouvelle instance</button>
    </div>

    {{-- ══ MAIN SHELL ══ --}}
    <div class="ap-shell">

        {{-- ── LEFT: Process Cards ── --}}
        <div>
            <div class="ap-grid" id="ap-grid">
                {{-- Cartes générées dynamiquement par JS --}}
                @for ($i = 0; $i < 4; $i++)
                    <div class="ap-card" style="opacity:.4;">
                        <div class="ap-card-head">
                            <div class="ap-card-icon" style="background:var(--bg4);"></div>
                            <div class="ap-card-meta">
                                <div class="ap-kpi-skeleton" style="width:80px;margin-bottom:6px;"></div>
                                <div class="ap-kpi-skeleton" style="width:200px;"></div>
                            </div>
                        </div>
                        <div class="ap-card-stats">
                            <div class="ap-cstat">
                                <div class="ap-kpi-skeleton" style="width:30px;margin:0 auto;"></div>
                            </div>
                            <div class="ap-cstat">
                                <div class="ap-kpi-skeleton" style="width:30px;margin:0 auto;"></div>
                            </div>
                            <div class="ap-cstat">
                                <div class="ap-kpi-skeleton" style="width:30px;margin:0 auto;"></div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        {{-- ── RIGHT: Sidebar ── --}}
        <div class="ap-sidebar">

            {{-- Pending tasks ── --}}
            <div class="ap-side-panel">
                <div class="ap-side-head">
                    ⏳ Tâches en attente
                    <span class="badge red" id="sideTaskCount">…</span>
                </div>
                <div id="sideTaskList">
                    <div class="ap-loading-row" style="padding:20px;text-align:center;color:var(--text3);font-size:12px;">
                        Chargement…</div>
                </div>
            </div>

            {{-- Quick stats per process ── --}}
            <div class="ap-side-panel">
                <div class="ap-side-head">📊 Répartition par processus</div>
                <div class="ap-side-body" id="sideBreakdown">
                    @for ($i = 0; $i < 4; $i++)
                        <div style="margin-bottom:12px;">
                            <div class="ap-kpi-skeleton" style="width:140px;margin-bottom:6px;"></div>
                            <div class="ap-kpi-skeleton" style="width:100%;height:4px;"></div>
                        </div>
                    @endfor
                </div>
            </div>

            {{-- Last activity ── --}}
            <div class="ap-side-panel">
                <div class="ap-side-head">📋 Activité récente</div>
                <div id="sideActivity">
                    <div style="padding:20px;text-align:center;color:var(--text3);font-size:12px;">Chargement…</div>
                </div>
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
     MODAL — DETAIL PROCESSUS + INSTANCES CAMUNDA
══════════════════════════════════════════════════════ --}}
    <div id="modal-ap-detail" class="modal">
        <div class="modal-content" style="max-width:860px;">
            <div class="modal-header">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div id="apd-icon" style="font-size:26px;"></div>
                    <div>
                        <div class="modal-title" id="apd-title">—</div>
                        <div style="font-size:11px;color:var(--text3);margin-top:2px;" id="apd-key"></div>
                    </div>
                </div>
                <button class="modal-close" onclick="closeModal('modal-ap-detail')">×</button>
            </div>
            <div class="modal-body">

                {{-- KPIs inline ── --}}
                <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:16px;">
                    <div
                        style="background:var(--bg3);border:1px solid var(--border);border-radius:8px;padding:12px;text-align:center;">
                        <div style="font-size:20px;font-weight:900;font-family:var(--font-mono);color:var(--blue);"
                            id="apd-total">—</div>
                        <div style="font-size:10px;color:var(--text3);">Total</div>
                    </div>
                    <div
                        style="background:var(--bg3);border:1px solid var(--border);border-radius:8px;padding:12px;text-align:center;">
                        <div style="font-size:20px;font-weight:900;font-family:var(--font-mono);color:var(--teal);"
                            id="apd-active">—</div>
                        <div style="font-size:10px;color:var(--text3);">En cours</div>
                    </div>
                    <div
                        style="background:var(--bg3);border:1px solid var(--border);border-radius:8px;padding:12px;text-align:center;">
                        <div style="font-size:20px;font-weight:900;font-family:var(--font-mono);color:var(--green);"
                            id="apd-done">—</div>
                        <div style="font-size:10px;color:var(--text3);">Terminées</div>
                    </div>
                    <div
                        style="background:var(--bg3);border:1px solid var(--border);border-radius:8px;padding:12px;text-align:center;">
                        <div style="font-size:20px;font-weight:900;font-family:var(--font-mono);color:var(--amber);"
                            id="apd-tasks">—</div>
                        <div style="font-size:10px;color:var(--text3);">Tâches ouvertes</div>
                    </div>
                </div>

                {{-- Tabs ── --}}
                <div class="ap-modal-tabs">
                    <div class="ap-mtab active" onclick="apDetailTab(this,'aptc-instances')">📋 Instances</div>
                    <div class="ap-mtab" onclick="apDetailTab(this,'aptc-tasks')">⚙️ Tâches</div>
                    <div class="ap-mtab" onclick="apDetailTab(this,'aptc-history')">📜 Historique</div>
                    <div class="ap-mtab" onclick="apDetailTab(this,'aptc-ia')">🤖 IA</div>
                </div>

                {{-- Tab: Instances ── --}}
                <div id="aptc-instances">
                    <div style="display:flex;gap:8px;margin-bottom:12px;flex-wrap:wrap;align-items:center;">
                        <span style="font-size:12px;color:var(--text2);">Instances Camunda en temps réel</span>
                        <span class="ap-live"><span class="ap-live-dot"></span>Live</span>
                        <div style="margin-left:auto;display:flex;gap:6px;">
                            <button class="btn btn-outline btn-sm" onclick="apRefreshDetail()">🔄 Actualiser</button>
                            <button class="btn btn-gold btn-sm" onclick="apStartFromDetail()">+ Nouvelle instance</button>
                        </div>
                    </div>
                    <div style="overflow-x:auto;">
                        <table class="ap-tbl" style="min-width:700px;">
                            <thead>
                                <tr>
                                    <th>Instance ID</th>
                                    <th>Business Key</th>
                                    <th>Démarrage</th>
                                    <th>Durée</th>
                                    <th>Statut</th>
                                    <th>Tâche courante</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="apd-instances-body">
                                <tr class="ap-loading-row">
                                    <td colspan="7">Chargement depuis Camunda…</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tab: Tâches ── --}}
                <div id="aptc-tasks" style="display:none;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                        <span style="font-size:12px;color:var(--text2);">Tâches ouvertes — assignables</span>
                        <button class="btn btn-outline btn-sm" onclick="apRefreshTasks()">🔄 Actualiser</button>
                    </div>
                    <div style="overflow-x:auto;">
                        <table class="ap-tbl">
                            <thead>
                                <tr>
                                    <th>Tâche</th>
                                    <th>Assignée à</th>
                                    <th>Créée le</th>
                                    <th>Échéance</th>
                                    <th>Priorité</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="apd-tasks-body">
                                <tr class="ap-loading-row">
                                    <td colspan="6">Chargement…</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tab: Historique ── --}}
                <div id="aptc-history" style="display:none;">
                    <div style="overflow-x:auto;">
                        <table class="ap-tbl">
                            <thead>
                                <tr>
                                    <th>Instance ID</th>
                                    <th>Démarrage</th>
                                    <th>Fin</th>
                                    <th>Durée</th>
                                    <th>Statut final</th>
                                </tr>
                            </thead>
                            <tbody id="apd-history-body">
                                <tr class="ap-loading-row">
                                    <td colspan="5">Chargement…</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tab: IA ── --}}
                <div id="aptc-ia" style="display:none;">
                    <div
                        style="padding:16px;background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius);margin-bottom:14px;">
                        <div style="font-size:13px;font-weight:700;color:var(--text);margin-bottom:8px;">🤖 Analyse IA du
                            processus</div>
                        <div id="apd-ia-reco" style="font-size:12.5px;color:var(--text2);line-height:1.7;">Chargement…
                        </div>
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;" id="apd-ia-kpis"></div>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('modal-ap-detail')">Fermer</button>
                <button class="btn btn-gold" id="apd-start-btn" onclick="apStartFromDetail()">+ Lancer une
                    instance</button>
            </div>
        </div>
    </div>

    {{-- ══ MODAL — DÉMARRER UNE INSTANCE ══ --}}
    <div id="modal-ap-start" class="modal">
        <div class="modal-content" style="max-width:520px;">
            <div class="modal-header">
                <div class="modal-title">⚡ Démarrer une instance Camunda</div>
                <button class="modal-close" onclick="closeModal('modal-ap-start')">×</button>
            </div>
            <div class="modal-body">
                <div
                    style="padding:10px 14px;background:var(--teal-dim);border:1px solid rgba(45,212,191,.25);border-radius:var(--radius-sm);margin-bottom:16px;font-size:12.5px;color:var(--teal);">
                    🔗 Connexion Camunda active · Processus : <strong id="startProcessKey">—</strong>
                </div>
                <div class="wf-start-field">
                    <label class="wf-start-label">Clé métier (Business Key)</label>
                    <input class="wf-start-input" id="startBizKey" placeholder="Ex: FNAP-2024-001">
                </div>
                <div class="wf-start-field">
                    <label class="wf-start-label">Demandeur</label>
                    <input class="wf-start-input" id="startDemandeur" placeholder="Nom du demandeur">
                </div>
                <div class="wf-start-field">
                    <label class="wf-start-label">Institution</label>
                    <input class="wf-start-input" id="startInstitution" placeholder="Ex: Musée d'Art Moderne">
                </div>
                <div class="wf-start-field">
                    <label class="wf-start-label">Type de demande</label>
                    <select class="wf-start-input" id="startType">
                        <option value="consultation">Consultation</option>
                        <option value="exposition">Exposition</option>
                        <option value="recherche">Recherche</option>
                        <option value="pret">Prêt</option>
                    </select>
                </div>
                <div class="wf-start-field">
                    <label class="wf-start-label">Notes / Description</label>
                    <textarea class="wf-start-input" id="startNotes" rows="3" placeholder="Description de la demande…"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('modal-ap-start')">Annuler</button>
                <button class="btn btn-gold" onclick="apConfirmStart()" id="startBtn">⚡ Démarrer</button>
            </div>
        </div>
    </div>

    {{-- ══ MODAL — APPROUVER UNE TÂCHE AVEC IA ══ --}}
    <div id="modal-ap-approve" class="modal">
        <div class="modal-content" style="max-width:580px;">
            <div class="modal-header">
                <div class="modal-title">✅ Approuver — Compléter la tâche</div>
                <button class="modal-close" onclick="closeModal('modal-ap-approve')">×</button>
            </div>
            <div class="modal-body">

                {{-- 🤖 SUGGESTION IA (NOUVEAU) --}}
                <div id="ia-suggestion-block" style="display:none; margin-bottom:16px; padding:14px; background:linear-gradient(135deg, rgba(45,212,191,.1), rgba(96,165,250,.05)); border:1px solid rgba(45,212,191,.3); border-radius:12px;">
                    <div style="display:flex; align-items:center; gap:12px; margin-bottom:10px;">
                        <div style="width:36px; height:36px; background:var(--teal-dim); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:18px;">🤖</div>
                        <div>
                            <div style="font-size:12px; font-weight:700; color:var(--teal);">💡 Suggestion IA</div>
                            <div style="font-size:10px; color:var(--text3);">Le workflow recommandé par l'intelligence artificielle</div>
                        </div>
                    </div>
                    <div id="ia-suggestion-content" style="background:var(--bg3); border-radius:8px; padding:12px;">
                        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                            <div>
                                <div style="font-size:13px; font-weight:600; color:var(--text);" id="ia-workflow-label">—</div>
                                <div style="font-size:10px; color:var(--text3); margin-top:4px;" id="ia-workflow-key">—</div>
                            </div>
                            <div style="text-align:center;">
                                <div style="font-size:18px; font-weight:900; font-family:var(--font-mono);" id="ia-confidence">—</div>
                                <div style="font-size:9px; color:var(--text3);">Confiance</div>
                            </div>
                        </div>
                        <div style="margin-top:10px; padding-top:10px; border-top:1px solid var(--border); font-size:11px; color:var(--text3);" id="ia-reasoning"></div>
                    </div>
                </div>

                <div class="ap-action-banner ap-action-approve-banner">
                    <span style="font-size:24px;">✅</span>
                    <div>
                        <div style="font-weight:700; font-size:13px; color:var(--green);">Approuver la demande</div>
                        <div style="font-size:12px; color:var(--text2); margin-top:3px;">Tâche : <strong id="approveTaskName">—</strong></div>
                    </div>
                </div>

                <div class="wf-start-field">
                    <label class="wf-start-label">Commentaire d'approbation</label>
                    <textarea class="wf-start-input" id="approveComment" rows="3" placeholder="Motif d'approbation, conditions…"></textarea>
                </div>

                <div class="wf-start-field">
                    <label class="wf-start-label">Décision</label>
                    <select class="wf-start-input" id="approveDecision">
                        <option value="approved">✅ Approuvé sans réserve</option>
                        <option value="approved_conditions">✅ Approuvé avec conditions</option>
                        <option value="deferred">⏳ Différé — complément requis</option>
                    </select>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('modal-ap-approve')">Annuler</button>
                <button class="btn" style="background:var(--green);color:#111;font-weight:700;" onclick="apConfirmApprove()">✅ Confirmer l'approbation</button>
            </div>
        </div>
    </div>

    {{-- ══ MODAL — REJETER UNE TÂCHE ══ --}}
    <div id="modal-ap-reject" class="modal">
        <div class="modal-content" style="max-width:480px;">
            <div class="modal-header">
                <div class="modal-title">❌ Rejeter — Compléter la tâche</div>
                <button class="modal-close" onclick="closeModal('modal-ap-reject')">×</button>
            </div>
            <div class="modal-body">
                <div class="ap-action-banner ap-action-reject-banner">
                    <span style="font-size:24px;">❌</span>
                    <div>
                        <div style="font-weight:700;font-size:13px;color:var(--red);">Rejeter la demande</div>
                        <div style="font-size:12px;color:var(--text2);margin-top:3px;">Tâche : <strong
                                id="rejectTaskName">—</strong></div>
                    </div>
                </div>
                <div class="wf-start-field">
                    <label class="wf-start-label">Motif de rejet <span style="color:var(--red);">*</span></label>
                    <select class="wf-start-input" id="rejectReason">
                        <option value="dossier_incomplet">Dossier incomplet</option>
                        <option value="non_eligible">Demandeur non éligible</option>
                        <option value="documents_manquants">Documents manquants</option>
                        <option value="hors_delai">Hors délai réglementaire</option>
                        <option value="autre">Autre motif</option>
                    </select>
                </div>
                <div class="wf-start-field">
                    <label class="wf-start-label">Commentaire détaillé</label>
                    <textarea class="wf-start-input" id="rejectComment" rows="3" placeholder="Expliquez le motif de rejet…"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('modal-ap-reject')">Annuler</button>
                <button class="btn" style="background:var(--red);color:#fff;font-weight:700;"
                    onclick="apConfirmReject()">❌ Confirmer le rejet</button>
            </div>
        </div>
    </div>

    {{-- ══ MODAL — DÉTAIL INSTANCE ══ --}}
    <div id="modal-ap-instance" class="modal">
        <div class="modal-content" style="max-width:580px;">
            <div class="modal-header">
                <div>
                    <div class="modal-title">📋 Détail Instance Camunda</div>
                    <div style="font-size:11px;color:var(--text3);margin-top:2px;" id="instDetailId">—</div>
                </div>
                <button class="modal-close" onclick="closeModal('modal-ap-instance')">×</button>
            </div>
            <div class="modal-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px;"
                    id="instDetailFields">
                </div>
                <div>
                    <div
                        style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--text3);margin-bottom:8px;">
                        Tâches de cette instance</div>
                    <div id="instDetailTasks"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('modal-ap-instance')">Fermer</button>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
     JAVASCRIPT — CAMUNDA FULL DYNAMIC
══════════════════════════════════════════════════════ --}}
    <script>
'use strict';

// ════ CONFIG ════
let AP_PROCESSES = [];                    // ← Will be loaded dynamically
const AP_STEPS = {};                      // ← Fallback steps (initially empty, can be populated)

// ════ STATE ════
let _currentProcessKey = '';
let _currentProcessId = 0;
let _currentTaskId = '';
let _currentTaskName = '';
let _allInstances = {};
let _allTasks = {};
let _globalStats = { total:0, active:0, done:0, pending:0, blocked:0 };
let _filterMode = 'all';
let _pollTimer = null;

// ════ CSRF ════
const CSRF = document.querySelector('meta[name=csrf-token]')?.content || '';

// ════ UTILS ════
const $ = id => document.getElementById(id);

const fmt = dt => dt ? new Date(dt).toLocaleDateString('fr-FR', {
    day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit'
}) : '—';

const dur = (start, end) => {
    if (!start) return '—';
    const ms = (end ? new Date(end) : new Date()) - new Date(start);
    const d = Math.floor(ms / 86400000);
    const h = Math.floor((ms % 86400000) / 3600000);
    return d > 0 ? `${d}j ${h}h` : `${h}h`;
};

const stateLabel = s => ({
    'ACTIVE': 'En cours', 'SUSPENDED': 'Suspendu', 'COMPLETED': 'Terminé', 'EXTERNALLY_TERMINATED': 'Annulé'
}[s] || s || 'En cours');

const stateColor = s => ({
    'ACTIVE': 'teal', 'SUSPENDED': 'amber', 'COMPLETED': 'green', 'EXTERNALLY_TERMINATED': 'red'
}[s] || 'blue');

// ════ API CALLS ════
async function apiFetch(url, opts = {}) {
    const res = await fetch(url, {
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF,
            'Accept': 'application/json'
        },
        ...opts
    });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
}

// ════ LOAD PROCESSES DYNAMICALLY ════
async function apLoadProcesses() {
    try {
        const response = await apiFetch('/api/workflows/deployed');

        AP_PROCESSES = Array.isArray(response) ? response.map(wf => ({
            id: wf.id || wf.bpm_definition_id || wf.process_key,
            key: wf.bpm_definition_id || wf.process_key || wf.key,
            icon: wf.icon || '⚙️',
            num: wf.num || wf.code || wf.id || 'P-XXX',
            color: wf.color || 'blue',
            delay: wf.delay || 'Variable',
            title: wf.nom || wf.name || wf.title || wf.key,
            output: wf.output || 'Résultat du processus'
        })) : [];

        console.log('✅ Loaded', AP_PROCESSES.length, 'workflows from backend');

    } catch (e) {
        console.error('Failed to load workflows dynamically:', e);
        // Fallback to hardcoded (only if you really need it)
        AP_PROCESSES = [
            // your original 4 processes here if you want a safe fallback
        ];
    }
}

// ════ MAIN LOAD FUNCTION ════
// ════ MAIN LOAD FUNCTION (FIXED) ════
async function apLoadAll() {
    $('apSyncTime').textContent = 'Synchronisation…';

    if (AP_PROCESSES.length === 0) {
        await apLoadProcesses();
    }

    $('ap-grid').innerHTML = '';   // Clear previous cards

    let globalTotal = 0, globalActive = 0, globalDone = 0;

    // Process one by one to avoid too many parallel requests
    for (const proc of AP_PROCESSES) {
        let kpis = { total: 0, en_cours: 0, termines: 0 };

        try {
            const kpiResponse = await apiFetch(`/api/workflows/${proc.key}/kpis`);
            kpis = kpiResponse || kpis;
        } catch (e) {
            console.warn(`KPI failed for ${proc.key}`, e);
        }

        globalTotal  += Number(kpis.total)    || 0;
        globalActive += Number(kpis.en_cours) || 0;
        globalDone   += Number(kpis.termines) || 0;

        // Build card (now safely handles missing flow)
        const cardHtml = await buildProcessCard(proc, kpis);
        $('ap-grid').insertAdjacentHTML('beforeend', cardHtml);
    }

    // Update Global KPIs
    $('gkpi-total').textContent  = globalTotal;
    $('gkpi-active').textContent = globalActive;
    $('gkpi-done').textContent   = globalDone;

    _buildSideBreakdown();
    _buildSideActivity();
    await _loadAllTasks();

    $('apSyncTime').textContent = 'Mis à jour ' + new Date().toLocaleTimeString('fr-FR');
}
// Build single process card with safe flow loading
async function buildProcessCard(proc, kpis) {
    let steps = [];

    // Try to get real steps from Camunda (new endpoint)
    try {
        const flowData = await apiFetch(`/api/workflows/${proc.key}/flow`);
        steps = Array.isArray(flowData.steps) ? flowData.steps : [];
    } catch (e) {
        console.warn(`Flow not available for ${proc.key}, using fallback`, e);
        // Fallback to your old hardcoded steps
        steps = AP_STEPS[proc.key] || [
            {label: 'Soumission', actor: 'Demandeur'},
            {label: 'Validation', actor: 'Admin'},
            {label: 'Finalisation', actor: 'Système'}
        ];
    }

    const stepHtml = steps.map((s, i) => {
        const conn = i < steps.length - 1
            ? `<div class="ap-connector"><div class="ap-connector-line"></div><div class="ap-connector-arr">▶</div></div>`
            : '';
        return `
            <div class="ap-fnode">
                <div class="ap-fnode-box">${escHtml(s.label || 'Étape')}</div>
                <div class="ap-fnode-actor">${escHtml(s.actor || '—')}</div>
            </div>${conn}`;
    }).join('');

    return `
    <div class="ap-card" data-id="${proc.id}" data-key="${proc.key}" data-color="${proc.color || 'blue'}">
        <div class="ap-card-head">
            <div class="ap-card-icon" style="background:var(--${proc.color || 'blue'}-dim);">${proc.icon || '⚙️'}</div>
            <div class="ap-card-meta">
                <div class="ap-card-num">${proc.num || ''} · ${proc.delay || ''}</div>
                <div class="ap-card-title">${escHtml(proc.title)}</div>
                <div class="ap-card-output">📄 ${escHtml(proc.output || 'Résultat du processus')}</div>
            </div>
            <div class="ap-card-badges">
                <span class="badge teal" style="font-size:10px;">${kpis.en_cours||0} actives</span>
                <span class="badge green" style="font-size:10px;">${kpis.termines||0} terminées</span>
            </div>
        </div>
        <div class="ap-card-stats">
            <div class="ap-cstat"><div class="ap-cstat-val" style="color:var(--blue);">${kpis.total||0}</div><div class="ap-cstat-lbl">Total</div></div>
            <div class="ap-cstat"><div class="ap-cstat-val" style="color:var(--teal);">${kpis.en_cours||0}</div><div class="ap-cstat-lbl">En cours</div></div>
            <div class="ap-cstat"><div class="ap-cstat-val" style="color:var(--green);">${kpis.termines||0}</div><div class="ap-cstat-lbl">Terminées</div></div>
        </div>
        <div class="ap-flow-row">
            <div class="ap-flow-label">Flux BPMN</div>
            <div class="ap-flow">${stepHtml}</div>
        </div>
        <div class="ap-card-foot">
            <button class="ap-foot-btn" onclick="apOpenDetail(${proc.id},'${proc.key}')">👁 Voir instances</button>
            <button class="ap-foot-btn gold" onclick="apOpenStart('${proc.key}')">+ Instance</button>
        </div>
    </div>`;
}

// Simple HTML escape helper
function escHtml(unsafe) {
    return String(unsafe || '')
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;");
}
// ════ BUILD CARD (unchanged except minor safety) ════
// Replace the old _buildCard function with this improved version


        // ════ OPEN DETAIL MODAL ════
        window.apOpenDetail = async function(id, key) {
            const proc = AP_PROCESSES.find(p => p.id === id);
            if (!proc) return;

            _currentProcessKey = key || proc.key;
            _currentProcessId = id;

            $('apd-icon').textContent = proc.icon;
            $('apd-title').textContent = proc.title;
            $('apd-key').textContent = proc.key;

            // Reset tabs
            apDetailTab(document.querySelector('.ap-mtab'), 'aptc-instances');
            openModal('modal-ap-detail');

            // Load data
            await _loadDetailData(_currentProcessKey);
        };
async function _loadDetailData(key) {
    $('apd-instances-body').innerHTML =
        '<tr class="ap-loading-row"><td colspan="7">🔄 Chargement depuis Camunda…</td></tr>';

    try {
        const [instances, userTasksResponse] = await Promise.all([
            apiFetch(`/api/workflows/${key}/instances`),
            apiFetch('/api/workflows/user-tasks')
        ]);

        const allInstances = Array.isArray(instances) ? instances : [];
        const userTasks    = Array.isArray(userTasksResponse) ? userTasksResponse : [];

        // Use only processInstanceId — the canonical Camunda field
        const myInstanceIds = new Set(
            userTasks
                .map(t => t.processInstanceId)   // ← always use processInstanceId, never processInstanceID
                .filter(Boolean)
        );

        // Only show instances where the current user has an active task
        const filteredInstances = allInstances.filter(inst => myInstanceIds.has(inst.id));

        _allInstances[key] = filteredInstances;

        $('apd-total').textContent  = allInstances.length;      // global count stays accurate
        $('apd-active').textContent = filteredInstances.length; // user's slice

        _renderInstancesTable(key);
        await _loadDetailTasks(key);

    } catch (e) {
        console.error(e);
        $('apd-instances-body').innerHTML =
            `<tr class="ap-loading-row"><td colspan="7">⚠️ Erreur : ${e.message}</td></tr>`;
    }
}
        // ════ RENDER INSTANCES TABLE ════
        // Replace the actions column in _renderInstancesTable
function _renderInstancesTable(key) {
    const insts = _allInstances[key] || [];

    if (!insts.length) {
        $('apd-instances-body').innerHTML =
            '<tr class="ap-loading-row"><td colspan="7">Aucune instance pour ce processus</td></tr>';
        return;
    }

    $('apd-instances-body').innerHTML = insts.map(inst => {
        const state = inst.state || inst.status || 'ACTIVE';
        const color = stateColor(state);
        const label = stateLabel(state);
        const id = inst.ref || inst.id || '—';
        const bk = inst.name || inst.businessKey || '—';

        return `<tr>
            <td>
                <code style="background:var(--bg4);padding:2px 6px;border-radius:4px;font-size:10.5px;cursor:pointer;color:var(--teal);"
                      onclick="apViewInstance('${id}','${key}')"
                      title="Voir détail">${id.substring(0,20)}…</code>
            </td>
            <td><strong>${escapeHtml(bk)}</strong></td>
            <td style="font-size:11px;color:var(--text3);">${inst.startTime ? fmt(inst.startTime) : '—'}</td>
            <td style="font-size:11px;font-family:var(--font-mono);">${dur(inst.startTime, inst.endTime)}</td>
            <td><span class="badge ${color}">${label}</span></td>
            <td style="font-size:11px;color:var(--text2);" id="task-cell-${id}">
                <span style="color:var(--text3);">Chargement…</span>
            </td>
            <td>
    <div style="display:flex;gap:5px;flex-wrap:wrap;">
        <button class="ap-act-btn ap-act-view" onclick="apViewInstance('${id}','${key}')">👁 Voir</button>
        ${state === 'ACTIVE' || state === 'En cours' ?
            `<button class="ap-act-btn ap-act-approve" onclick="apOpenApprove('${id}')">✅ Approuver</button>
             <button class="ap-act-btn ap-act-reject"  onclick="apOpenReject('${id}')">❌ Rejeter</button>` : ''}
    </div>
</td>
        </tr>`;
    }).join('');

    // Load current task per instance (async)
    insts.forEach(inst => {
        const id = inst.ref || inst.id;
        if (id && (inst.state === 'ACTIVE' || inst.status === 'En cours' || !inst.state)) {
            _loadCurrentTask(id);
        }
    });
}

        // ════ LOAD CURRENT TASK FOR INSTANCE ════
        async function _loadCurrentTask(instanceId) {
            try {
                // ✅ Correct endpoint (GET)
                const tasks = await apiFetch(`/api/workflows/instances/${instanceId}/tasks`);

                const cell = $(`task-cell-${instanceId}`);
                if (cell && Array.isArray(tasks) && tasks.length > 0) {
                    const t = tasks[0];
                    cell.innerHTML =
                        `<span style="font-size:11px;color:var(--teal);">${t.name || 'Tâche en cours'}</span>`;
                } else if (cell) {
                    cell.innerHTML = `<span style="color:var(--text3);font-size:11px;">Aucune tâche active</span>`;
                }
            } catch (e) {
                console.warn(`Failed to load task for instance ${instanceId}`, e);
                const cell = $(`task-cell-${instanceId}`);
                if (cell) cell.innerHTML = `<span style="color:var(--text3);font-size:11px;">—</span>`;
            }
        }

        // ════ LOAD DETAIL TASKS ════
       async function _loadDetailTasks(key) {
    try {
        const userTasks = await apiFetch('/api/workflows/user-tasks');
        const tasks = Array.isArray(userTasks) ? userTasks : [];

        $('apd-tasks').textContent = tasks.length;

        if (!tasks.length) {
            $('apd-tasks-body').innerHTML =
                '<tr class="ap-loading-row"><td colspan="6">Aucune tâche assignée à vous</td></tr>';
            return;
        }

        $('apd-tasks-body').innerHTML = tasks.map(task => `
            <tr>
                <td><strong style="font-size:12px;">${escHtml(task.name || task.taskDefinitionKey || '—')}</strong></td>
                <td style="font-size:11px;color:var(--text2);">${escHtml(task.assignee || '—')}</td>
                <td style="font-size:11px;font-family:var(--font-mono);">${task.created ? fmt(task.created) : '—'}</td>
                <td style="font-size:11px;">${task.due ? fmt(task.due) : '—'}</td>
                <td>
                    <span style="font-size:11px;font-family:var(--font-mono);color:var(--teal);">
                        ${task.priority || 50}
                    </span>
                </td>
                <td>
                    <div style="display:flex;gap:5px;flex-wrap:wrap;">
                        <button class="ap-act-btn ap-act-approve"
                                onclick="apOpenApproveTask('${task.id}','${escHtml(task.name||'')}')">✅ Approuver</button>
                        <button class="ap-act-btn ap-act-reject"
                                onclick="apOpenRejectTask('${task.id}','${escHtml(task.name||'')}')">❌ Rejeter</button>
                    </div>
                </td>
            </tr>
        `).join('');

    } catch (e) {
        $('apd-tasks-body').innerHTML = `<tr class="ap-loading-row"><td colspan="6">Erreur de chargement</td></tr>`;
    }
}

        // ════ LOAD HISTORY ════
        async function apLoadHistory() {
            $('apd-history-body').innerHTML = '<tr class="ap-loading-row"><td colspan="5">🔄 Chargement…</td></tr>';
            try {
                const data = await apiFetch(`/api/workflows/${_currentProcessKey}/instances`);
                const insts = Array.isArray(data) ? data : [];

                if (!insts.length) {
                    $('apd-history-body').innerHTML =
                        '<tr class="ap-loading-row"><td colspan="5">Aucun historique</td></tr>';
                    return;
                }

                $('apd-history-body').innerHTML = insts.map(inst => {
                    const state = inst.state || inst.status || '—';
                    return `<tr>
                <td><code style="font-size:10.5px;background:var(--bg4);padding:2px 6px;border-radius:3px;">${(inst.ref||inst.id||'—').substring(0,20)}…</code></td>
                <td style="font-size:11px;">${inst.startTime ? fmt(inst.startTime) : '—'}</td>
                <td style="font-size:11px;">${inst.endTime  ? fmt(inst.endTime)   : '—'}</td>
                <td style="font-size:11px;font-family:var(--font-mono);">${dur(inst.startTime, inst.endTime)}</td>
                <td><span class="badge ${stateColor(state)}">${stateLabel(state)}</span></td>
            </tr>`;
                }).join('');
            } catch (e) {
                $('apd-history-body').innerHTML =
                `<tr class="ap-loading-row"><td colspan="5">⚠️ ${e.message}</td></tr>`;
            }
        }

        // ════ VIEW INSTANCE DETAIL ════
       // ════ VIEW INSTANCE DETAIL WITH FORM DATA ════
window.apViewInstance = async function(instanceId, key) {
    $('instDetailId').textContent = instanceId;
    $('instDetailFields').innerHTML = '<div style="color:var(--text3);font-size:12px;">Chargement des données...</div>';

    openModal('modal-ap-instance');

    try {
        // Fetch basic instance info + variables
        const [variablesResponse, tasksResponse] = await Promise.all([
            apiFetch(`/api/workflows/instances/${instanceId}/variables`),
            apiFetch(`/api/workflows/instances/${instanceId}/tasks`)
        ]);

        const vars = variablesResponse.variables || {};
        const tasks = Array.isArray(tasksResponse) ? tasksResponse : [];

        // Build nice display of form data
        let fieldsHtml = '';

        if (Object.keys(vars).length > 0) {
            fieldsHtml += `<div style="margin-bottom:16px;">
                <div style="font-size:11px;font-weight:700;color:var(--text3);margin-bottom:8px;">📋 Données du Formulaire</div>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:10px;">`;

            Object.entries(vars).forEach(([fieldName, value]) => {
                if (value === null || value === undefined || value === '') return;

                const label = fieldName.replace(/([A-Z])/g, ' $1')
                                     .replace(/^./, str => str.toUpperCase())
                                     .replace(/_/g, ' ');

                fieldsHtml += `
                    <div style="background:var(--bg3);border:1px solid var(--border);border-radius:8px;padding:12px;">
                        <div style="font-size:10px;color:var(--text3);text-transform:uppercase;margin-bottom:4px;">${escHtml(label)}</div>
                        <div style="font-size:13px;font-weight:600;color:var(--text);word-break:break-all;">
                            ${escHtml(value)}
                        </div>
                    </div>`;
            });

            fieldsHtml += `</div></div>`;
        } else {
            fieldsHtml += `<div style="color:var(--text3);font-style:italic;">Aucune donnée de formulaire trouvée</div>`;
        }

        // Add current tasks info
        if (tasks.length > 0) {
            fieldsHtml += `
                <div style="margin-top:20px;">
                    <div style="font-size:11px;font-weight:700;color:var(--text3);margin-bottom:8px;">⚙️ Tâches Actives</div>
                    ${tasks.map(t => `
                        <div style="background:var(--bg3);border:1px solid var(--border);border-radius:8px;padding:10px;margin-bottom:8px;">
                            <strong>${escHtml(t.name || t.taskDefinitionKey)}</strong><br>
                            <small style="color:var(--text3);">Assignée à : ${escHtml(t.assignee || 'Non assignée')}</small>
                        </div>
                    `).join('')}
                </div>`;
        }

        $('instDetailFields').innerHTML = fieldsHtml;

    } catch (e) {
        console.error(e);
        $('instDetailFields').innerHTML = `
            <div style="color:var(--red);padding:20px;text-align:center;">
                Erreur lors du chargement des données : ${e.message}
            </div>`;
    }
};

        // ════ START INSTANCE ════
        window.apOpenStart = function(key) {
            _currentProcessKey = key;
            const proc = AP_PROCESSES.find(p => p.key === key);
            $('startProcessKey').textContent = key + (proc ? ` — ${proc.title}` : '');
            $('startBizKey').value = '';
            $('startDemandeur').value = '';
            $('startInstitution').value = '';
            $('startNotes').value = '';
            openModal('modal-ap-start');
        };

        window.apOpenStartModal = function() {
            const first = AP_PROCESSES[0];
            apOpenStart(first.key);
        };

        window.apStartFromDetail = function() {
            closeModal('modal-ap-detail');
            apOpenStart(_currentProcessKey);
        };

        window.apConfirmStart = async function() {
            const btn = $('startBtn');
            btn.disabled = true;
            btn.textContent = '⏳ Démarrage…';

            const variables = {
                businessKey: $('startBizKey').value,
                demandeur: $('startDemandeur').value,
                institution: $('startInstitution').value,
                typeDemande: $('startType').value,
                notes: $('startNotes').value,
                dateCreation: new Date().toISOString(),
            };

            try {
                const result = await apiFetch(`/api/workflows/${_currentProcessKey}/start`, {
                    method: 'POST',
                    body: JSON.stringify({
                        variables
                    })
                });

                if (result.id) {
                    closeModal('modal-ap-start');
                    showToast(`✅ Instance créée — ID: ${result.id.substring(0,12)}…`, 'success');
                    setTimeout(() => {
                        apRefreshAll();
                        apOpenDetail(_currentProcessId, _currentProcessKey);
                    }, 1500);
                } else {
                    showToast('⚠️ Réponse Camunda inattendue', 'error');
                    console.error('[Camunda] Start result:', result);
                }
            } catch (e) {
                showToast(`❌ Erreur démarrage : ${e.message}`, 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = '⚡ Démarrer';
            }
        };

        // ════ APPROVE (via task completion) ════
        window.apOpenApprove = async function(instanceId) {
            try {
                const tasks = await apiFetch(`/api/workflows/instances/${instanceId}/tasks`);

                if (tasks && tasks.length > 0) {
                    const task = tasks[0];
                    _currentTaskId = task.id;
                    _currentTaskName = task.name || 'Tâche';
                    document.getElementById('approveTaskName').textContent = task.name || instanceId.substring(0, 20) + '…';
                    document.getElementById('approveComment').value = '';

                    // Charger la suggestion IA
                    await loadIaSuggestionForTask(instanceId);

                    openModal('modal-ap-approve');
                } else {
                    showToast('❌ Aucune tâche active trouvée pour cette instance', 'error');
                }
            } catch (e) {
                showToast('❌ Erreur: Impossible de charger la tâche', 'error');
                console.error(e);
            }
        };
        window.apOpenApproveTask = function(taskId, taskName) {
    _currentTaskId = taskId;
    _currentTaskName = taskName;
    $('approveTaskName').textContent = taskName || taskId.substring(0, 20) + '…';
    $('approveComment').value = '';
    openModal('modal-ap-approve');
};
        window.apConfirmApprove = async function () {
    const commentEl = document.getElementById('approveComment');
    const decisionEl = document.getElementById('approveDecision');

    const comment = commentEl ? commentEl.value.trim() : '';
    const decision = decisionEl ? decisionEl.value : null;

    const btn = document.querySelector('#modal-ap-approve .btn-gold');

    if (btn) {
        btn.disabled = true;
        btn.textContent = '⏳ Traitement...';
    }

    try {
        const response = await fetch(`/api/workflows/tasks/${_currentTaskId}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                comment,
                decision
            })
        });

        const result = await response.json();

        if (response.ok && result.success) {
            closeModal('modal-ap-approve');
            showToast('✅ Tâche approuvée avec succès !', 'success');

            setTimeout(() => {
                apRefreshAll();
                if (_currentProcessKey) {
                    _loadDetailData(_currentProcessKey);
                }
            }, 1000);
        } else {
            showToast(`❌ Erreur: ${result.error || 'Impossible d\'approuver'}`, 'error');
        }
    } catch (e) {
        showToast(`❌ Erreur: ${e.message}`, 'error');
        console.error('Approve error:', e);
    } finally {
        if (btn) {
            btn.disabled = false;
            btn.textContent = "✅ Confirmer l'approbation";
        }
    }
};
        // ════ REJECT ════
       window.apOpenReject = async function(instanceId) {
    try {
        const tasks = await apiFetch(`/api/workflows/instances/${instanceId}/tasks`);

        if (tasks && tasks.length > 0) {
            const task = tasks[0];
            _currentTaskId = task.id;
            _currentTaskName = task.name || 'Tâche';
            $('rejectTaskName').textContent = task.name || instanceId.substring(0, 20) + '…';
            $('rejectComment').value = '';
            openModal('modal-ap-reject');
        } else {
            showToast('❌ Aucune tâche active trouvée', 'error');
        }
    } catch (e) {
        showToast('❌ Erreur: Impossible de charger la tâche', 'error');
    }
};

window.apOpenRejectTask = function(taskId, taskName) {
    _currentTaskId = taskId;
    _currentTaskName = taskName;
    $('rejectTaskName').textContent = taskName || taskId.substring(0, 20) + '…';
    $('rejectComment').value = '';
    openModal('modal-ap-reject');
};

window.apConfirmReject = async function() {
    const reason = $('rejectReason').value;
    const comment = $('rejectComment').value.trim();

    if (!comment) {
        showToast('⚠️ Veuillez entrer un commentaire de rejet', 'error');
        return;
    }

    const btn = document.querySelector('#modal-ap-reject .btn');
    if (btn) {
        btn.disabled = true;
        btn.textContent = '⏳ Traitement...';
    }

    try {
        const response = await fetch(`/api/workflows/tasks/${_currentTaskId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                reason: reason,
                comment: comment
            })
        });

        const result = await response.json();

        if (response.ok && result.success) {
            closeModal('modal-ap-reject');
            showToast('❌ Tâche rejetée', 'success');

            setTimeout(() => {
                apRefreshAll();
                if (_currentProcessKey) {
                    _loadDetailData(_currentProcessKey);
                }
            }, 1000);
        } else {
            showToast(`❌ Erreur: ${result.error || 'Impossible de rejeter'}`, 'error');
        }
    } catch (e) {
        showToast(`❌ Erreur: ${e.message}`, 'error');
        console.error('Reject error:', e);
    } finally {
        if (btn) {
            btn.disabled = false;
            btn.textContent = '❌ Confirmer le rejet';
        }
    }
};
        // ════ SIDEBAR TASKS ════
// ════ LOAD ONLY USER'S OWN TASKS (SECURE) ════
// ════ LOAD ONLY TASKS ASSIGNED TO CURRENT LOGGED-IN USER ════
async function _loadAllTasks() {
    try {
        const response = await apiFetch('/api/workflows/user-tasks');

        const tasks = Array.isArray(response) ? response : [];

        $('sideTaskCount').textContent = tasks.length;

        if (tasks.length === 0) {
            $('sideTaskList').innerHTML = `
                <div style="padding:20px;text-align:center;color:var(--text3);font-size:12.5px;">
                    Aucune tâche assignée à vous pour le moment
                </div>`;
            return;
        }

        let html = '';
        tasks.forEach(task => {
            html += `
                <div class="ap-task-row" onclick="apOpenApproveTask('${task.id}', '${escHtml(task.name || 'Tâche')}')">
                    <div class="ap-task-dot" style="background:var(--amber);"></div>
                    <div style="flex:1; min-width:0;">
                        <div class="ap-task-name">${escHtml(task.name || 'Tâche sans nom')}</div>
                        <div class="ap-task-meta">
                            ${escHtml(task.processDefinitionName || task.process_name || 'Processus')}
                            • ${task.created ? fmt(task.created) : '—'}
                        </div>
                    </div>
                </div>`;
        });

        $('sideTaskList').innerHTML = html;

    } catch (err) {
        console.error('Failed to load user tasks:', err);
        $('sideTaskList').innerHTML = `
            <div style="padding:16px;color:var(--red);text-align:center;">
                Erreur de chargement des tâches
            </div>`;
    }
}
        // ════ SIDEBAR BREAKDOWN ════
        window._buildSideBreakdown = function() {
            const max = Math.max(...AP_PROCESSES.map(p => (_allInstances[p.key] || []).length), 1);
            $('sideBreakdown').innerHTML = AP_PROCESSES.map(p => {
                const cnt = (_allInstances[p.key] || []).length;
                const pct = Math.round((cnt / max) * 100);
                return `<div style="margin-bottom:12px;">
            <div style="display:flex;justify-content:space-between;font-size:11.5px;color:var(--text2);margin-bottom:5px;">
                <span>${p.icon} ${p.title.substring(0,22)}…</span>
                <span style="font-family:var(--font-mono);font-weight:700;color:var(--text);">${cnt}</span>
            </div>
            <div style="height:4px;background:var(--bg4);border-radius:2px;overflow:hidden;">
                <div style="height:100%;background:var(--${p.color});width:${pct}%;border-radius:2px;transition:width .5s;"></div>
            </div>
        </div>`;
            }).join('');
        }
window.apOpenTaskDetail = function(taskId, processInstanceId) {
    // Tu peux ouvrir le modal d'approbation directement ou afficher plus d'infos
    _currentTaskId = taskId;
    // Option : ouvrir directement le modal d'approbation
    apOpenApproveTask(taskId, 'Tâche en cours');
};
        // ════ SIDEBAR ACTIVITY ════
        window._buildSideActivity = function() {
            const activities = [];
            AP_PROCESSES.forEach(p => {
                (_allInstances[p.key] || []).slice(0, 2).forEach(inst => {
                    activities.push({
                        icon: p.icon,
                        proc: p.title,
                        id: (inst.ref || inst.id || '—').substring(0, 10),
                        time: inst.startTime ? fmt(inst.startTime) : '—',
                        state: inst.state || 'ACTIVE',
                    });
                });
            });
            activities.sort((a, b) => 0); // keep order

            if (!activities.length) {
                $('sideActivity').innerHTML =
                    '<div style="padding:16px;text-align:center;font-size:12px;color:var(--text3);">Aucune activité</div>';
                return;
            }

            $('sideActivity').innerHTML = activities.slice(0, 6).map(a => `
        <div class="ap-task-row">
            <div class="ap-task-dot" style="background:var(--${stateColor(a.state)});"></div>
            <div>
                <div class="ap-task-name">${a.icon} ${a.proc.substring(0,22)}…</div>
                <div class="ap-task-meta">${a.id} · ${a.time}</div>
            </div>
            <span class="badge ${stateColor(a.state)}" style="font-size:9px;">${stateLabel(a.state)}</span>
        </div>
    `).join('');
        }

        // ════ IA ANALYSIS ════
        window._renderIaAnalysis = function(key, kpis) {
            const proc = AP_PROCESSES.find(p => p.key === key);
            const total = kpis.total || 0;
            const act = kpis.en_cours || 0;
            const done = kpis.termines || 0;
            const rate = total ? Math.round((done / total) * 100) : 0;

            $('apd-ia-reco').innerHTML = `
        <p style="margin-bottom:10px;">
            📊 <strong>${total} instances totales</strong> pour ce processus.
            Taux de complétion : <strong style="color:${rate>70?'var(--green)':rate>40?'var(--amber)':'var(--red)'};">${rate}%</strong>
        </p>
        <p style="margin-bottom:10px;">
            ${act > 5 ? '⚠️ <strong>Charge élevée</strong> — ' + act + ' instances actives simultanées. Envisagez de répartir la charge.' :
                        '✅ Charge normale — ' + act + ' instance(s) en cours.'}
        </p>
        <p>🤖 Recommandation : ${rate < 50 ? 'Identifier les blocages dans les étapes intermédiaires. Plusieurs instances semblent stagner.' :
            'Processus bien géré. Optimisation possible sur les délais de validation (étape 3).'}</p>`;

            $('apd-ia-kpis').innerHTML = [{
                    label: 'Taux complétion',
                    val: `${rate}%`,
                    color: rate > 70 ? 'green' : rate > 40 ? 'amber' : 'red'
                },
                {
                    label: 'En cours',
                    val: act,
                    color: 'teal'
                },
                {
                    label: 'Terminées',
                    val: done,
                    color: 'green'
                },
            ].map(k => `
        <div style="background:var(--bg3);border:1px solid var(--border);border-radius:8px;padding:12px;text-align:center;">
            <div style="font-size:20px;font-weight:900;font-family:var(--font-mono);color:var(--${k.color});">${k.val}</div>
            <div style="font-size:10px;color:var(--text3);margin-top:3px;">${k.label}</div>
        </div>`).join('');
        }

        // ════ DETAIL TABS ════
        window.apDetailTab = function(el, panelId) {
            document.querySelectorAll('.ap-mtab').forEach(t => t.classList.remove('active'));
            if (el) el.classList.add('active');
            ['aptc-instances', 'aptc-tasks', 'aptc-history', 'aptc-ia'].forEach(id => {
                const p = $(id);
                if (p) p.style.display = id === panelId ? '' : 'none';
            });
            if (panelId === 'aptc-history') apLoadHistory();
            if (panelId === 'aptc-tasks') _loadDetailTasks(_currentProcessKey);
        };

        // ════ FILTER ════
        window.apSetFilter = function(el, mode) {
            _filterMode = mode;
            document.querySelectorAll('.ap-ftab').forEach(t => t.classList.remove('active'));
            el.classList.add('active');
            apRefreshAll();
        };

        window.apSearchFilter = function(q) {
            const ql = q.toLowerCase();
            document.querySelectorAll('.ap-card').forEach(card => {
                const title = card.querySelector('.ap-card-title')?.textContent.toLowerCase() || '';
                card.style.display = !ql || title.includes(ql) ? '' : 'none';
            });
        };

        // ════ REFRESH ════
        window.apRefreshAll = () => apLoadAll();
        window.apRefreshDetail = () => _loadDetailData(_currentProcessKey);
        window.apRefreshTasks = () => _loadDetailTasks(_currentProcessKey);

        // ════ POLLING (auto-refresh every 30s) ════
        window._startPolling = function() {
            _pollTimer = setInterval(() => {
                apLoadAll();
            }, 30000);
        }

        // ════ INIT ════
        document.addEventListener('DOMContentLoaded', () => {
            apLoadAll();
            _startPolling();
        });

        // Cleanup on page leave
        window.addEventListener('beforeunload', () => {
            if (_pollTimer) clearInterval(_pollTimer);
        });

<<<<<<< HEAD


        // ════ IA SUGGESTION FOR TASK ════
async function loadIaSuggestionForTask(instanceId) {
    const iaBlock = document.getElementById('ia-suggestion-block');
    const iaWorkflowLabel = document.getElementById('ia-workflow-label');
    const iaWorkflowKey = document.getElementById('ia-workflow-key');
    const iaConfidence = document.getElementById('ia-confidence');
    const iaReasoning = document.getElementById('ia-reasoning');

    // Afficher le chargement
    iaBlock.style.display = 'block';
    iaWorkflowLabel.textContent = 'Chargement de la suggestion...';
    iaWorkflowKey.textContent = '—';
    iaConfidence.textContent = '—';
    iaReasoning.textContent = '';

    try {
        // Récupérer les variables de l'instance
        const varsResponse = await apiFetch(`/api/workflows/instances/${instanceId}/variables`);
        const vars = varsResponse.variables || {};

        // Construire le texte à classifier à partir des variables du formulaire
        let textToClassify = '';
        for (const [key, value] of Object.entries(vars)) {
            if (value && typeof value === 'string' && value.length > 0) {
                textToClassify += value + ' ';
            } else if (value && typeof value === 'object') {
                textToClassify += JSON.stringify(value) + ' ';
            }
        }

        if (textToClassify.length === 0) {
            iaWorkflowLabel.textContent = 'Aucune donnée disponible pour la suggestion IA';
            iaConfidence.textContent = '0%';
            return;
        }

        // Limiter la longueur
        if (textToClassify.length > 2000) textToClassify = textToClassify.substring(0, 2000);

        // Appeler l'API FastAPI
        const response = await fetch('http://localhost:8001/api/v1/classify', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ texte_libre: textToClassify })
        });

        const iaResult = await response.json();

        if (iaResult.workflow_key) {
            const confidencePercent = Math.round(iaResult.confidence_score * 100);
            const confidenceColor = iaResult.confidence_score >= 0.85 ? '#31c48d' : (iaResult.confidence_score >= 0.7 ? '#f59e0b' : '#ef4444');

            iaWorkflowLabel.textContent = iaResult.label || iaResult.workflow_key;
            iaWorkflowKey.textContent = iaResult.workflow_key;
            iaConfidence.innerHTML = `${confidencePercent}%`;
            iaConfidence.style.color = confidenceColor;
            iaReasoning.textContent = iaResult.reasoning || 'Aucun détail fourni';

            // Stocker pour utilisation dans l'approbation
            window._currentIaSuggestion = iaResult.workflow_key;
            window._currentIaConfidence = iaResult.confidence_score;
        } else {
            iaWorkflowLabel.textContent = 'Service IA indisponible';
            iaConfidence.textContent = '0%';
            window._currentIaSuggestion = null;
        }

    } catch (e) {
        console.warn('IA suggestion failed:', e);
        iaWorkflowLabel.textContent = '⚠️ Service IA temporairement indisponible';
        iaConfidence.textContent = '0%';
        window._currentIaSuggestion = null;
    }
}
=======
// Add this function
function showToast(message, type = 'info') {
    // Remove existing toasts
    const existingToasts = document.querySelectorAll('.custom-toast');
    existingToasts.forEach(toast => toast.remove());

    // Create toast container if it doesn't exist
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 10000;
            display: flex;
            flex-direction: column;
            gap: 10px;
        `;
        document.body.appendChild(toastContainer);
    }

    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'custom-toast';

    let bgColor, textColor;
    switch(type) {
        case 'success':
            bgColor = '#4ade80';
            textColor = '#111';
            break;
        case 'error':
            bgColor = '#ef4444';
            textColor = '#fff';
            break;
        default:
            bgColor = '#c9a84c';
            textColor = '#111';
    }

    toast.style.cssText = `
        background: ${bgColor};
        color: ${textColor};
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        animation: slideInRight 0.3s ease;
        max-width: 400px;
        min-width: 250px;
        font-family: var(--font-body);
    `;
    toast.textContent = message;

    toastContainer.appendChild(toast);

    // Remove after 4 seconds
    setTimeout(() => {
        toast.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

// Add CSS animations if not present
if (!document.querySelector('#toast-styles')) {
    const style = document.createElement('style');
    style.id = 'toast-styles';
    style.textContent = `
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
}

>>>>>>> feature
    </script>

@endsection
