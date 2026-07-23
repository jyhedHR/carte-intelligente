@extends('shared.layouts.backoffice')

@section('title', 'AI Mail Maestro')
@section('breadcrumb', 'AI Mail Maestro')

@section('content')

    {{-- ════════════════════════════════════════════════
     AI MAIL MAESTRO — STYLES
════════════════════════════════════════════════ --}}
    <style>
        .mm-kpi-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 24px;
        }
        @media (max-width: 900px) { .mm-kpi-row { grid-template-columns: repeat(2, 1fr); } }
        .mm-kpi { background: var(--bg2); border: 1px solid var(--border); border-radius: var(--radius); padding: 16px 18px; display: flex; align-items: center; gap: 14px; transition: border-color 0.2s; }
        .mm-kpi:hover { border-color: var(--border2); }
        .mm-kpi-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
        .mm-kpi-body { flex: 1; min-width: 0; }
        .mm-kpi-val { font-size: 22px; font-weight: 900; font-family: var(--font-mono); line-height: 1; margin-bottom: 3px; }
        .mm-kpi-lbl { font-size: 11px; color: var(--text3); text-transform: uppercase; letter-spacing: 0.6px; }
        .mm-kpi-delta { font-size: 10.5px; font-family: var(--font-mono); font-weight: 700; margin-top: 4px; }

        .mm-shell { display: grid; grid-template-columns: 300px 1fr 340px; gap: 18px; align-items: start; }
        @media (max-width: 1200px) { .mm-shell { grid-template-columns: 260px 1fr; } }
        @media (max-width: 860px) { .mm-shell { grid-template-columns: 1fr; } }

        .mm-col-recipients { background: var(--bg2); border: 1px solid var(--border); border-radius: var(--radius); display: flex; flex-direction: column; overflow: hidden; position: sticky; top: 76px; }
        .mm-col-head { padding: 14px 16px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; }
        .mm-col-title { font-size: 12.5px; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 7px; }
        .mm-col-count { font-size: 10px; font-family: var(--font-mono); font-weight: 700; padding: 2px 8px; background: var(--gold-dim); color: var(--gold); border-radius: 10px; }
        .mm-search { padding: 10px 14px; border-bottom: 1px solid var(--border); flex-shrink: 0; }
        .mm-search-input { width: 100%; background: var(--bg3); border: 1px solid var(--border2); border-radius: var(--radius-sm); padding: 7px 11px; font-size: 12px; color: var(--text); font-family: var(--font-body); outline: none; transition: border-color 0.18s; }
        .mm-search-input:focus { border-color: var(--gold); }
        .mm-search-input::placeholder { color: var(--text3); }
        .mm-group-pills { padding: 10px 14px; display: flex; flex-wrap: wrap; gap: 6px; border-bottom: 1px solid var(--border); flex-shrink: 0; }
        .mm-gpill { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 20px; font-size: 10.5px; font-weight: 600; cursor: pointer; border: 1px solid var(--border); background: var(--bg3); color: var(--text3); transition: all 0.15s; user-select: none; }
        .mm-gpill:hover { border-color: var(--border2); color: var(--text2); }
        .mm-gpill.active { background: var(--gold-dim); border-color: rgba(201,168,76,0.3); color: var(--gold); }
        .mm-select-bar { padding: 8px 14px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; }
        .mm-select-all-btn { font-size: 11px; font-weight: 600; color: var(--text3); cursor: pointer; background: none; border: none; font-family: var(--font-body); transition: color 0.15s; }
        .mm-select-all-btn:hover { color: var(--gold); }
        .mm-selected-count { font-size: 10.5px; font-family: var(--font-mono); color: var(--text3); }
        .mm-selected-count span { color: var(--gold); font-weight: 700; }
        .mm-recipient-list { overflow-y: auto; max-height: 520px; flex: 1; }
        .mm-recipient-item { display: flex; align-items: center; gap: 10px; padding: 10px 14px; border-bottom: 1px solid var(--border); cursor: pointer; transition: background 0.15s; user-select: none; }
        .mm-recipient-item:last-child { border-bottom: none; }
        .mm-recipient-item:hover { background: var(--bg3); }
        .mm-recipient-item.selected { background: rgba(201,168,76,0.04); }
        .mm-checkbox { width: 16px; height: 16px; border: 1.5px solid var(--border2); border-radius: 4px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all 0.15s; font-size: 10px; color: transparent; }
        .mm-recipient-item.selected .mm-checkbox { background: var(--gold); border-color: var(--gold); color: #111; }
        .mm-rec-av { width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; flex-shrink: 0; }
        .mm-rec-info { flex: 1; min-width: 0; }
        .mm-rec-name { font-size: 12px; font-weight: 600; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .mm-rec-detail { font-size: 10.5px; color: var(--text3); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .mm-rec-alert { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }

        .mm-composer-col { display: flex; flex-direction: column; gap: 16px; }
        .mm-hero-strip { background: linear-gradient(135deg, rgba(201,168,76,0.08) 0%, rgba(251,191,36,0.05) 50%, rgba(96,165,250,0.04) 100%); border: 1px solid rgba(201,168,76,0.2); border-radius: var(--radius); padding: 16px 20px; display: flex; align-items: center; gap: 16px; position: relative; overflow: hidden; }
        .mm-hero-strip::after { content: '✉️'; position: absolute; right: 20px; top: 50%; transform: translateY(-50%); font-size: 52px; opacity: 0.07; pointer-events: none; }
        .mm-hero-orb { width: 44px; height: 44px; border-radius: 12px; background: var(--gold-dim); border: 1px solid rgba(201,168,76,0.3); display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
        .mm-hero-text { flex: 1; }
        .mm-hero-title { font-size: 14px; font-weight: 700; color: var(--text); margin-bottom: 3px; display: flex; align-items: center; gap: 8px; }
        .mm-hero-sub { font-size: 12px; color: var(--text2); line-height: 1.5; }

        .mm-intent-panel, .mm-generate-panel { background: var(--bg2); border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden; }
        .mm-panel-head { padding: 13px 18px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .mm-panel-title { font-size: 12.5px; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 7px; }
        .mm-panel-sub { font-size: 11px; color: var(--text3); }
        .mm-intent-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; padding: 14px 18px; }
        @media (max-width: 700px) { .mm-intent-grid { grid-template-columns: repeat(2, 1fr); } }
        .mm-intent-card { display: flex; flex-direction: column; align-items: flex-start; gap: 6px; padding: 12px 13px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--bg3); cursor: pointer; transition: all 0.18s; user-select: none; }
        .mm-intent-card:hover { border-color: var(--border2); background: var(--bg4); }
        .mm-intent-card.active { border-color: var(--gold); background: var(--gold-dim); }
        .mm-intent-icon { font-size: 18px; }
        .mm-intent-label { font-size: 11.5px; font-weight: 700; color: var(--text); line-height: 1.3; }
        .mm-intent-desc { font-size: 10px; color: var(--text3); line-height: 1.4; }
        .mm-intent-count { font-size: 9.5px; font-family: var(--font-mono); font-weight: 700; padding: 2px 7px; border-radius: 10px; margin-top: 2px; }

        .mm-generate-head { padding: 13px 18px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 10px; }
        .mm-gen-badge { display: inline-flex; align-items: center; gap: 5px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: var(--gold); background: var(--gold-dim); padding: 3px 9px; border-radius: 20px; }
        .mm-gen-dots { display: flex; align-items: center; gap: 3px; margin-left: auto; }
        .mm-gen-dot { width: 4px; height: 4px; border-radius: 50%; background: var(--gold); animation: mm-think 1.3s ease-in-out infinite; }
        .mm-gen-dot:nth-child(2) { animation-delay: 0.2s; }
        .mm-gen-dot:nth-child(3) { animation-delay: 0.4s; }
        @keyframes mm-think { 0%,100%{opacity:0.2;transform:scale(0.8)} 50%{opacity:1;transform:scale(1.2)} }

        .mm-tone-row { padding: 10px 18px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .mm-tone-label { font-size: 11px; color: var(--text3); font-weight: 600; flex-shrink: 0; }
        .mm-tone-pills { display: flex; gap: 6px; flex-wrap: wrap; }
        .mm-tone-pill { padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; border: 1px solid var(--border); background: var(--bg3); color: var(--text3); cursor: pointer; transition: all 0.15s; user-select: none; }
        .mm-tone-pill:hover { color: var(--text2); }
        .mm-tone-pill.active { background: var(--gold-dim); border-color: rgba(201,168,76,0.3); color: var(--gold); }

        /* Language selector */
        .mm-lang-row { padding: 8px 18px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 10px; }
        .mm-lang-label { font-size: 11px; color: var(--text3); font-weight: 600; flex-shrink: 0; }
        .mm-lang-pills { display: flex; gap: 6px; }
        .mm-lang-pill { padding: 3px 11px; border-radius: 20px; font-size: 11px; font-weight: 600; border: 1px solid var(--border); background: var(--bg3); color: var(--text3); cursor: pointer; transition: all 0.15s; }
        .mm-lang-pill.active { background: var(--blue-dim); border-color: rgba(96,165,250,0.3); color: var(--blue); }

        /* AI status indicator */
        .mm-ai-status { display: flex; align-items: center; gap: 6px; font-size: 11px; padding: 6px 18px; border-bottom: 1px solid var(--border); }
        .mm-ai-status-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--green); animation: mm-pulse 2s infinite; }
        @keyframes mm-pulse { 0%,100%{opacity:1} 50%{opacity:0.4} }
        .mm-ai-status-text { color: var(--text3); }

        .mm-context-row { padding: 10px 18px; border-bottom: 1px solid var(--border); display: flex; gap: 10px; align-items: center; }
        .mm-context-input { flex: 1; background: var(--bg3); border: 1px solid var(--border2); border-radius: var(--radius-sm); padding: 7px 12px; font-size: 12px; color: var(--text); font-family: var(--font-body); outline: none; transition: border-color 0.18s; }
        .mm-context-input:focus { border-color: var(--gold); }
        .mm-context-input::placeholder { color: var(--text3); }
        .mm-btn-generate { display: flex; align-items: center; gap: 7px; padding: 9px 18px; background: linear-gradient(135deg, var(--gold), #a07830); color: #111; border: none; border-radius: var(--radius-sm); font-size: 12.5px; font-weight: 800; cursor: pointer; font-family: var(--font-body); transition: opacity 0.18s; white-space: nowrap; flex-shrink: 0; }
        .mm-btn-generate:hover { opacity: 0.88; }
        .mm-btn-generate:disabled { opacity: 0.5; cursor: not-allowed; }

        .mm-email-editor { padding: 18px; }
        .mm-email-subject-row { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid var(--border); }
        .mm-subject-label { font-size: 11px; font-weight: 700; color: var(--text3); text-transform: uppercase; letter-spacing: 0.6px; flex-shrink: 0; min-width: 56px; }
        .mm-subject-input { flex: 1; background: transparent; border: none; font-size: 13px; font-weight: 700; color: var(--text); font-family: var(--font-body); outline: none; }
        .mm-subject-input::placeholder { color: var(--text3); font-weight: 400; }
        .mm-ia-tag { display: inline-flex; align-items: center; gap: 4px; font-size: 9.5px; font-weight: 700; padding: 2px 8px; border-radius: 10px; background: var(--gold-dim); color: var(--gold); flex-shrink: 0; }
        .mm-toolbar { display: flex; align-items: center; gap: 4px; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--border); flex-wrap: wrap; }
        .mm-tool-btn { width: 28px; height: 28px; border-radius: 5px; display: flex; align-items: center; justify-content: center; font-size: 12px; cursor: pointer; background: none; border: none; color: var(--text3); font-family: var(--font-body); transition: all 0.15s; }
        .mm-tool-btn:hover { background: var(--bg3); color: var(--text2); }
        .mm-tool-sep { width: 1px; height: 16px; background: var(--border); margin: 0 3px; flex-shrink: 0; }
        .mm-tokens { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 10px; }
        .mm-token { display: inline-flex; align-items: center; gap: 4px; padding: 3px 9px; border-radius: 20px; font-size: 10.5px; font-weight: 600; background: var(--blue-dim); color: var(--blue); border: 1px solid rgba(96,165,250,0.2); cursor: pointer; transition: all 0.15s; user-select: none; }
        .mm-token:hover { background: rgba(96,165,250,0.2); }
        .mm-email-body { width: 100%; min-height: 260px; background: var(--bg3); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 14px 16px; font-size: 13px; color: var(--text2); font-family: var(--font-mono); line-height: 1.7; outline: none; resize: vertical; transition: border-color 0.18s; box-sizing: border-box; }
        .mm-email-body:focus { border-color: var(--gold); }
        .mm-send-bar { padding: 14px 18px; border-top: 1px solid var(--border); display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .mm-send-info { flex: 1; font-size: 11.5px; color: var(--text3); line-height: 1.4; }
        .mm-send-info strong { color: var(--gold); font-family: var(--font-mono); }
        .mm-btn-send { display: flex; align-items: center; gap: 7px; padding: 10px 22px; background: linear-gradient(135deg, var(--gold), #a07830); color: #111; border: none; border-radius: var(--radius-sm); font-size: 13px; font-weight: 800; cursor: pointer; font-family: var(--font-body); transition: opacity 0.18s; }
        .mm-btn-send:hover { opacity: 0.88; }
        .mm-btn-send:disabled { opacity: 0.5; cursor: not-allowed; }
        .mm-btn-schedule, .mm-btn-preview { display: flex; align-items: center; gap: 6px; padding: 10px 14px; background: var(--bg3); color: var(--text2); border: 1px solid var(--border2); border-radius: var(--radius-sm); font-size: 12px; font-weight: 600; cursor: pointer; font-family: var(--font-body); transition: all 0.18s; }
        .mm-btn-schedule:hover, .mm-btn-preview:hover { background: var(--bg4); color: var(--text); }

        .mm-sidebar { display: flex; flex-direction: column; gap: 16px; position: sticky; top: 76px; }
        .mm-sidebar-panel { background: var(--bg2); border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden; }
        .mm-sb-head { padding: 12px 16px; border-bottom: 1px solid var(--border); font-size: 12px; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 7px; justify-content: space-between; }
        .mm-sb-body { padding: 14px 16px; }
        .mm-preview-strip { display: flex; flex-direction: column; gap: 8px; max-height: 220px; overflow-y: auto; }
        .mm-preview-row { display: flex; align-items: center; gap: 9px; padding: 7px 10px; background: var(--bg3); border-radius: var(--radius-sm); border: 1px solid var(--border); }
        .mm-preview-av { width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 9px; font-weight: 700; flex-shrink: 0; }
        .mm-preview-info { flex: 1; min-width: 0; }
        .mm-preview-name { font-size: 11.5px; font-weight: 600; color: var(--text); }
        .mm-preview-email { font-size: 10px; color: var(--text3); font-family: var(--font-mono); }
        .mm-preview-status { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
        .mm-history-list { display: flex; flex-direction: column; gap: 0; }
        .mm-history-item { padding: 10px 14px; border-bottom: 1px solid var(--border); display: flex; align-items: flex-start; gap: 9px; cursor: pointer; transition: background 0.15s; }
        .mm-history-item:last-child { border-bottom: none; }
        .mm-history-item:hover { background: var(--bg3); }
        .mm-history-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; margin-top: 4px; }
        .mm-history-body { flex: 1; min-width: 0; }
        .mm-history-subject { font-size: 11.5px; font-weight: 600; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 2px; }
        .mm-history-meta { font-size: 10px; color: var(--text3); font-family: var(--font-mono); }
        .mm-history-badge { font-size: 9.5px; font-weight: 700; padding: 2px 6px; border-radius: 8px; flex-shrink: 0; margin-top: 2px; }
        .mm-ai-metric { display: flex; align-items: center; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--border); font-size: 12px; }
        .mm-ai-metric:last-child { border-bottom: none; padding-bottom: 0; }
        .mm-ai-metric-label { color: var(--text2); }
        .mm-ai-metric-val { font-family: var(--font-mono); font-weight: 700; }
        .mm-quick-actions { display: flex; flex-direction: column; gap: 6px; padding: 12px 14px; }
        .mm-qa-btn { display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: var(--bg3); border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 12px; font-weight: 600; color: var(--text2); cursor: pointer; font-family: var(--font-body); transition: all 0.15s; text-align: left; }
        .mm-qa-btn:hover { background: var(--bg4); color: var(--text); border-color: var(--border2); }
        @keyframes mm-fadein { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
        .mm-fadein { animation: mm-fadein 0.35s ease forwards; }

        .mm-preview-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 500; display: flex; align-items: center; justify-content: center; padding: 20px; opacity: 0; pointer-events: none; transition: opacity 0.25s; }
        .mm-preview-overlay.show { opacity: 1; pointer-events: all; }
        .mm-preview-box { background: var(--bg2); border: 1px solid var(--border2); border-radius: var(--radius); width: 100%; max-width: 600px; max-height: 80vh; overflow-y: auto; padding: 28px 32px; box-shadow: 0 24px 60px rgba(0,0,0,0.4); }
        .mm-preview-email-head { border-bottom: 1px solid var(--border); padding-bottom: 14px; margin-bottom: 18px; }
        .mm-preview-field { display: flex; gap: 10px; margin-bottom: 6px; font-size: 12px; }
        .mm-preview-field-lbl { color: var(--text3); min-width: 44px; font-weight: 600; }
        .mm-preview-field-val { color: var(--text2); }
        .mm-preview-email-body { font-size: 13px; color: var(--text2); line-height: 1.75; white-space: pre-line; font-family: var(--font-mono); }

        .mm-sending-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(6px); z-index: 600; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 16px; opacity: 0; pointer-events: none; transition: opacity 0.25s; }
        .mm-sending-overlay.show { opacity: 1; pointer-events: all; }
        .mm-sending-icon { font-size: 52px; animation: mm-fly 0.6s ease-in-out infinite alternate; }
        @keyframes mm-fly { from{transform:translateY(0) rotate(-5deg)} to{transform:translateY(-12px) rotate(5deg)} }
        .mm-sending-text { font-size: 18px; font-weight: 700; color: var(--text); }
        .mm-sending-sub { font-size: 13px; color: var(--text3); }
        .mm-sending-bar-track { width: 200px; height: 4px; background: var(--bg4); border-radius: 2px; overflow: hidden; }
        .mm-sending-bar-fill { height: 100%; background: linear-gradient(90deg, var(--gold), var(--gold2)); border-radius: 2px; animation: mm-load 2s ease forwards; }
        @keyframes mm-load { from{width:0%} to{width:100%} }

        /* Recipients loading skeleton */
        .mm-skeleton { background: linear-gradient(90deg, var(--bg3) 25%, var(--bg4) 50%, var(--bg3) 75%); background-size: 200% 100%; animation: mm-shimmer 1.4s infinite; border-radius: 4px; }
        @keyframes mm-shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
    </style>

    {{-- KPI ROW — dynamic from controller --}}
    <div class="mm-kpi-row">
        <div class="mm-kpi">
            <div class="mm-kpi-icon" style="background: var(--gold-dim);">👥</div>
            <div class="mm-kpi-body">
                <div class="mm-kpi-val" style="color:var(--gold);">{{ $stats['total_users'] }}</div>
                <div class="mm-kpi-lbl">Utilisateurs totaux</div>
                <div class="mm-kpi-delta" style="color:var(--text3);">→ Base destinataires</div>
            </div>
        </div>
        <div class="mm-kpi">
            <div class="mm-kpi-icon" style="background: var(--green-dim);">✅</div>
            <div class="mm-kpi-body">
                <div class="mm-kpi-val" style="color:var(--green);">{{ $stats['active_users'] }}</div>
                <div class="mm-kpi-lbl">Comptes actifs</div>
                <div class="mm-kpi-delta" style="color:var(--green);">→ Prêts à recevoir</div>
            </div>
        </div>
        <div class="mm-kpi">
            <div class="mm-kpi-icon" style="background: var(--amber-dim);">⏳</div>
            <div class="mm-kpi-body">
                <div class="mm-kpi-val" style="color:var(--amber);">{{ $stats['pending_users'] }}</div>
                <div class="mm-kpi-lbl">En attente</div>
                <div class="mm-kpi-delta" style="color:var(--amber);">→ À relancer</div>
            </div>
        </div>
        <div class="mm-kpi">
            <div class="mm-kpi-icon" style="background: var(--teal-dim);">🤖</div>
            <div class="mm-kpi-body">
                <div class="mm-kpi-val" style="color:var(--teal);">HF AI</div>
                <div class="mm-kpi-lbl">Moteur IA</div>
                <div class="mm-kpi-delta" style="color:var(--teal);" id="mm-ai-model-kpi">
                    {{ config('services.huggingface.token') ? '→ Connecté' : '→ Mode templates' }}
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN 3-COLUMN SHELL --}}
    <div class="mm-shell">

        {{-- ══ COL 1 — RECIPIENTS (dynamic from DB) ══ --}}
        <div class="mm-col-recipients">
            <div class="mm-col-head">
                <span class="mm-col-title">👥 Destinataires</span>
                <span class="mm-col-count" id="mm-sel-badge">0 sélectionné</span>
            </div>

            <div class="mm-search">
                <input type="text" class="mm-search-input" id="mm-search-input"
                    placeholder="🔍 Chercher un utilisateur..." oninput="mmFilter(this.value)">
            </div>

            <div class="mm-group-pills">
                <div class="mm-gpill active" onclick="mmGroup(this, 'all')">Tous</div>
                <div class="mm-gpill" onclick="mmGroup(this, 'active')">✅ Actifs</div>
                <div class="mm-gpill" onclick="mmGroup(this, 'pending')">⏳ En attente</div>
                <div class="mm-gpill" onclick="mmGroup(this, 'admins')">🔑 Admins</div>
                <div class="mm-gpill" onclick="mmGroup(this, 'dept_admins')">🏛 Dept. Admin</div>
            </div>

            <div class="mm-select-bar">
                <button class="mm-select-all-btn" onclick="mmSelectAll()">☑ Tout sélectionner</button>
                <span class="mm-selected-count"><span id="mm-count">0</span> / <span id="mm-total">0</span></span>
            </div>

            <div class="mm-recipient-list" id="mm-list">
                <div style="padding:20px; text-align:center; color:var(--text3); font-size:12px;">
                    <div class="mm-skeleton" style="height:40px; margin-bottom:8px;"></div>
                    <div class="mm-skeleton" style="height:40px; margin-bottom:8px;"></div>
                    <div class="mm-skeleton" style="height:40px;"></div>
                </div>
            </div>
        </div>

        {{-- ══ COL 2 — COMPOSER ══ --}}
        <div class="mm-composer-col">

            {{-- Hero strip --}}
            <div class="mm-hero-strip">
                <div class="mm-hero-orb">✉️</div>
                <div class="mm-hero-text">
                    <div class="mm-hero-title">
                        AI Mail Maestro
                        <span style="font-size:10px; padding:2px 8px; background:var(--gold-dim); color:var(--gold); border-radius:20px; font-weight:700;">
                            {{ config('services.huggingface.token') ? '🤖 HuggingFace IA' : '📋 Templates intégrés' }}
                        </span>
                    </div>
                    <div class="mm-hero-sub">
                        Sélectionnez vos destinataires, choisissez un type d'email, et laissez l'IA rédiger. Envoi SMTP institutionnel réel.
                    </div>
                </div>
            </div>

            {{-- Intent picker --}}
            <div class="mm-intent-panel">
                <div class="mm-panel-head">
                    <span class="mm-panel-title">🎯 Type d'email</span>
                    <span class="mm-panel-sub">Sélectionnez le contexte pour l'IA</span>
                </div>
                <div class="mm-intent-grid">
                    <div class="mm-intent-card active" onclick="mmSelectIntent(this, 'cnss')" data-intent="cnss">
                        <div class="mm-intent-icon">⚠️</div>
                        <div class="mm-intent-label">Affiliation CNSS</div>
                        <div class="mm-intent-desc">Renouvellement expiré</div>
                        <div class="mm-intent-count" style="background:var(--red-dim);color:var(--red);">Urgent</div>
                    </div>
                    <div class="mm-intent-card" onclick="mmSelectIntent(this, 'document')" data-intent="document">
                        <div class="mm-intent-icon">📋</div>
                        <div class="mm-intent-label">Document Manquant</div>
                        <div class="mm-intent-desc">Dossier incomplet</div>
                        <div class="mm-intent-count" style="background:var(--amber-dim);color:var(--amber);">Rappel</div>
                    </div>
                    <div class="mm-intent-card" onclick="mmSelectIntent(this, 'contrat')" data-intent="contrat">
                        <div class="mm-intent-icon">📝</div>
                        <div class="mm-intent-label">Contrat Expiré</div>
                        <div class="mm-intent-desc">Renouvellement contractuel</div>
                        <div class="mm-intent-count" style="background:var(--red-dim);color:var(--red);">Ciblé</div>
                    </div>
                    <div class="mm-intent-card" onclick="mmSelectIntent(this, 'maintenance')" data-intent="maintenance">
                        <div class="mm-intent-icon">🔧</div>
                        <div class="mm-intent-label">Maintenance</div>
                        <div class="mm-intent-desc">Interruption de service</div>
                        <div class="mm-intent-count" style="background:var(--blue-dim);color:var(--blue);">Info</div>
                    </div>
                    <div class="mm-intent-card" onclick="mmSelectIntent(this, 'hors-ligne')" data-intent="hors-ligne">
                        <div class="mm-intent-icon">📡</div>
                        <div class="mm-intent-label">Préavis Suspension</div>
                        <div class="mm-intent-desc">7 jours avant suspension</div>
                        <div class="mm-intent-count" style="background:var(--purple-dim);color:var(--purple);">Préavis</div>
                    </div>
                    <div class="mm-intent-card" onclick="mmSelectIntent(this, 'rappel')" data-intent="rappel">
                        <div class="mm-intent-icon">🔔</div>
                        <div class="mm-intent-label">Rappel Général</div>
                        <div class="mm-intent-desc">Communication admin libre</div>
                        <div class="mm-intent-count" style="background:var(--teal-dim);color:var(--teal);">Libre</div>
                    </div>
                </div>
            </div>

            {{-- IA Generator --}}
            <div class="mm-generate-panel">
                <div class="mm-generate-head">
                    <span class="mm-gen-badge">🤖 IA Rédacteur</span>
                    <span style="font-size:12px;color:var(--text2);font-weight:600;flex:1;margin-left:10px;" id="mm-ai-label">
                        {{ config('services.huggingface.token') ? 'HuggingFace Mistral-7B' : 'Templates institutionnels' }}
                    </span>
                    <div class="mm-gen-dots">
                        <div class="mm-gen-dot"></div>
                        <div class="mm-gen-dot"></div>
                        <div class="mm-gen-dot"></div>
                    </div>
                </div>

                {{-- AI Status --}}
                <div class="mm-ai-status">
                    @if(config('services.huggingface.token'))
                        <div class="mm-ai-status-dot"></div>
                        <span class="mm-ai-status-text">HuggingFace API connectée — Génération IA en temps réel</span>
                    @else
                        <div class="mm-ai-status-dot" style="background:var(--amber);"></div>
                        <span class="mm-ai-status-text">Mode templates — Ajoutez HUGGINGFACE_API_TOKEN dans .env pour l'IA</span>
                    @endif
                </div>

                {{-- Tone --}}
                <div class="mm-tone-row">
                    <span class="mm-tone-label">Ton :</span>
                    <div class="mm-tone-pills">
                        <div class="mm-tone-pill active" onclick="mmTone(this)">Formel institutionnel</div>
                        <div class="mm-tone-pill" onclick="mmTone(this)">Courtois ferme</div>
                        <div class="mm-tone-pill" onclick="mmTone(this)">Bienveillant</div>
                        <div class="mm-tone-pill" onclick="mmTone(this)">Urgent</div>
                    </div>
                </div>

                {{-- Language --}}
                <div class="mm-lang-row">
                    <span class="mm-lang-label">Langue :</span>
                    <div class="mm-lang-pills">
                        <div class="mm-lang-pill active" onclick="mmLang(this, 'fr')">🇫🇷 Français</div>
                        <div class="mm-lang-pill" onclick="mmLang(this, 'ar')">🇹🇳 العربية</div>
                        <div class="mm-lang-pill" onclick="mmLang(this, 'en')">🇬🇧 English</div>
                    </div>
                </div>

                {{-- Context note --}}
                <div class="mm-context-row">
                    <input type="text" class="mm-context-input" id="mm-context"
                        placeholder="Note contextuelle optionnelle (ex: délai 15 jours, pièce spécifique...)">
                    <button class="mm-btn-generate" id="mm-gen-btn" onclick="mmGenerate()">
                        ✨ Générer l'email
                    </button>
                </div>

                {{-- Email editor --}}
                <div class="mm-email-editor">
                    <div class="mm-email-subject-row">
                        <span class="mm-subject-label">Objet</span>
                        <input type="text" class="mm-subject-input" id="mm-subject"
                            placeholder="L'IA génère le sujet automatiquement...">
                        <span class="mm-ia-tag" id="mm-ia-tag">✨ IA</span>
                    </div>

                    <div class="mm-toolbar">
                        <button class="mm-tool-btn" title="Régénérer IA" onclick="mmGenerate()"
                            style="color:var(--gold);font-size:14px;">↺</button>
                        <button class="mm-tool-btn" onclick="mmAITransform('shorten')"
                            style="font-size:10px;width:auto;padding:0 8px;">✂️ Raccourcir</button>
                        <button class="mm-tool-btn" onclick="mmAITransform('formalize')"
                            style="font-size:10px;width:auto;padding:0 8px;">🎩 Formaliser</button>
                        <div class="mm-tool-sep"></div>
                        <button class="mm-tool-btn" title="Copier" onclick="mmCopy()">📋</button>
                        <button class="mm-tool-btn" title="Effacer" onclick="mmClear()" style="color:var(--red);">🗑</button>
                    </div>

                    {{-- Personalization tokens --}}
                    <div class="mm-tokens">
                        <span style="font-size:10px;color:var(--text3);font-weight:600;margin-right:2px;">Variables :</span>
                        <span class="mm-token" onclick="mmInsertToken('@{{NOM}}')">@{{NOM}}</span>
                        <span class="mm-token" onclick="mmInsertToken('@{{PRENOM}}')">@{{PRENOM}}</span>
                        <span class="mm-token" onclick="mmInsertToken('@{{NOM_COMPLET}}')">@{{NOM_COMPLET}}</span>
                        <span class="mm-token" onclick="mmInsertToken('@{{EMAIL}}')">@{{EMAIL}}</span>
                        <span class="mm-token" onclick="mmInsertToken('@{{CIN}}')">@{{CIN}}</span>
                        <span class="mm-token" onclick="mmInsertToken('@{{DÉPARTEMENT}}')">@{{DÉPARTEMENT}}</span>
                        <span class="mm-token" onclick="mmInsertToken('@{{DATE}}')">@{{DATE}}</span>
                    </div>

                    <textarea class="mm-email-body" id="mm-body"
                        placeholder="Cliquez sur '✨ Générer l'email' pour que l'IA rédige automatiquement..."></textarea>
                </div>

                {{-- Send bar --}}
                <div class="mm-send-bar">
                    <div class="mm-send-info">
                        Prêt à envoyer à <strong id="mm-send-count">0</strong> destinataire(s).<br>
                        <span style="font-size:10px;">L'IA remplace les variables @{{NOM}}, @{{PRENOM}}, etc. pour chaque destinataire.</span>
                    </div>
                    <button class="mm-btn-preview" onclick="mmPreview()">👁 Aperçu</button>
                    <button class="mm-btn-schedule" onclick="showToast('Planification — bientôt disponible', 'info')">🕐 Planifier</button>
                    <button class="mm-btn-send" id="mm-send-btn" onclick="mmSend()">🚀 Envoyer maintenant</button>
                </div>
            </div>

        </div>{{-- end composer col --}}

        {{-- ══ COL 3 — SIDEBAR ══ --}}
        <div class="mm-sidebar">

            {{-- Selected preview --}}
            <div class="mm-sidebar-panel">
                <div class="mm-sb-head">
                    <span style="display:flex;align-items:center;gap:7px;">👁 Destinataires sélectionnés</span>
                    <span class="mm-col-count" id="mm-sb-count">0</span>
                </div>
                <div class="mm-sb-body" style="padding:10px 14px;">
                    <div class="mm-preview-strip" id="mm-preview-strip">
                        <div style="text-align:center;padding:20px;color:var(--text3);font-size:12px;">
                            Aucun destinataire sélectionné.
                        </div>
                    </div>
                </div>
            </div>

            {{-- AI stats --}}
            <div class="mm-sidebar-panel">
                <div class="mm-sb-head">📊 Moteur IA</div>
                <div class="mm-sb-body">
                    <div class="mm-ai-metric">
                        <span class="mm-ai-metric-label">Modèle</span>
                        <span class="mm-ai-metric-val" style="color:var(--gold);font-size:10px;">
                            {{ config('services.huggingface.model', 'Templates intégrés') }}
                        </span>
                    </div>
                    <div class="mm-ai-metric">
                        <span class="mm-ai-metric-label">Serveur SMTP</span>
                        <span class="mm-ai-metric-val" style="color:var(--teal);">{{ config('mail.host', '—') }}</span>
                    </div>
                    <div class="mm-ai-metric">
                        <span class="mm-ai-metric-label">Expéditeur</span>
                        <span class="mm-ai-metric-val" style="color:var(--blue);font-size:10px;">{{ config('mail.from.address', '—') }}</span>
                    </div>
                    <div class="mm-ai-metric">
                        <span class="mm-ai-metric-label">Personnalisation</span>
                        <span class="mm-ai-metric-val" style="color:var(--green);">✓ Activée</span>
                    </div>
                    <div class="mm-ai-metric">
                        <span class="mm-ai-metric-label">Envoi réel</span>
                        <span class="mm-ai-metric-val" style="color:var(--green);">
                            {{ config('mail.from.address') ? '✓ SMTP configuré' : '⚠ .env manquant' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Quick actions --}}
            <div class="mm-sidebar-panel">
                <div class="mm-sb-head">⚡ Actions rapides</div>
                <div class="mm-quick-actions">
                    <button class="mm-qa-btn" onclick="mmSelectGroup('active')">✅ Sélectionner tous les actifs</button>
                    <button class="mm-qa-btn" onclick="mmSelectGroup('pending')">⏳ Sélectionner en attente</button>
                    <button class="mm-qa-btn" onclick="mmQuickCampaign('cnss')">⚠️ Campagne rappel urgent</button>
                    <button class="mm-qa-btn" onclick="mmQuickCampaign('maintenance')">🔧 Template maintenance</button>
                    <button class="mm-qa-btn" onclick="mmClear()">🗑 Effacer l'email</button>
                </div>
            </div>

            {{-- Send result --}}
            <div class="mm-sidebar-panel" id="mm-result-panel" style="display:none;">
                <div class="mm-sb-head">📬 Résultat d'envoi</div>
                <div class="mm-sb-body" id="mm-result-body"></div>
            </div>

        </div>{{-- end sidebar --}}

    </div>{{-- end mm-shell --}}


    {{-- EMAIL PREVIEW OVERLAY --}}
    <div class="mm-preview-overlay" id="mm-preview-overlay" onclick="mmClosePreview(event)">
        <div class="mm-preview-box" onclick="event.stopPropagation()">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <div style="font-size:14px;font-weight:700;color:var(--text);display:flex;align-items:center;gap:8px;">
                    👁 Aperçu email personnalisé
                    <span style="font-size:10px;padding:2px 8px;background:var(--gold-dim);color:var(--gold);border-radius:20px;">IA</span>
                </div>
                <button onclick="mmClosePreview()"
                    style="background:none;border:none;color:var(--text3);font-size:18px;cursor:pointer;">✕</button>
            </div>
            <div class="mm-preview-email-head">
                <div class="mm-preview-field">
                    <span class="mm-preview-field-lbl">De :</span>
                    <span class="mm-preview-field-val">{{ config('mail.from.name') }} &lt;{{ config('mail.from.address') }}&gt;</span>
                </div>
                <div class="mm-preview-field">
                    <span class="mm-preview-field-lbl">À :</span>
                    <span class="mm-preview-field-val" id="mm-preview-to">—</span>
                </div>
                <div class="mm-preview-field">
                    <span class="mm-preview-field-lbl">Objet :</span>
                    <span class="mm-preview-field-val" id="mm-preview-subject">—</span>
                </div>
            </div>
            <div class="mm-preview-email-body" id="mm-preview-body"></div>
            <div style="display:flex;gap:10px;margin-top:20px;padding-top:16px;border-top:1px solid var(--border);">
                <button class="mm-btn-schedule" onclick="mmClosePreview()">← Modifier</button>
                <button class="mm-btn-send" onclick="mmClosePreview(); mmSend();">🚀 Envoyer</button>
            </div>
        </div>
    </div>

    {{-- SENDING ANIMATION OVERLAY --}}
    <div class="mm-sending-overlay" id="mm-sending-overlay">
        <div class="mm-sending-icon">✉️</div>
        <div class="mm-sending-text" id="mm-sending-text">Envoi en cours...</div>
        <div class="mm-sending-sub" id="mm-sending-sub">Personnalisation par l'IA</div>
        <div class="mm-sending-bar-track">
            <div class="mm-sending-bar-fill" id="mm-sending-bar"></div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
     JAVASCRIPT — All real API calls
═══════════════════════════════════════════ --}}
    <script>
        // ── CSRF token for all POST requests ──
        const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

        // ── Routes (injected from Laravel) ──
        const MM_ROUTES = {
            recipients: '{{ route("mail.recipients") }}',
            generate:   '{{ route("mail.generate") }}',
            send:       '{{ route("mail.send") }}',
        };

        // ── State ──
        const mmState = {
            selected: new Map(),   // id → {id, name, email, active, department}
            allRecipients: [],     // full list from API
            filtered: [],          // currently visible
            intent: 'cnss',
            tone: 'Formel institutionnel',
            lang: 'fr',
        };

        // ════════════════════════════════════════════
        //  RECIPIENTS — Load from real DB via API
        // ════════════════════════════════════════════

        async function mmLoadRecipients(group = 'all', search = '') {
            const list = document.getElementById('mm-list');
            list.innerHTML = `<div style="padding:16px;text-align:center;color:var(--text3);font-size:12px;">
                <div class="mm-skeleton" style="height:40px;margin-bottom:8px;"></div>
                <div class="mm-skeleton" style="height:40px;margin-bottom:8px;"></div>
                <div class="mm-skeleton" style="height:40px;"></div></div>`;

            try {
                const url = new URL(MM_ROUTES.recipients, window.location.origin);
                url.searchParams.set('group', group);
                if (search) url.searchParams.set('search', search);

                const res = await fetch(url.toString(), {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
                });
                const data = await res.json();
                mmState.allRecipients = data.recipients || [];
                mmState.filtered = mmState.allRecipients;
                mmRenderList(mmState.allRecipients);
            } catch (e) {
                list.innerHTML = `<div style="padding:20px;text-align:center;color:var(--red);font-size:12px;">
                    ⚠ Erreur de chargement: ${e.message}</div>`;
            }
        }

        function mmRenderList(recipients) {
            const list = document.getElementById('mm-list');
            document.getElementById('mm-total').textContent = recipients.length;

            if (recipients.length === 0) {
                list.innerHTML = `<div style="padding:20px;text-align:center;color:var(--text3);font-size:12px;">Aucun utilisateur trouvé.</div>`;
                return;
            }

            const colors = ['teal','blue','gold','red','purple','green','amber'];
            const gradients = {
                teal:   'linear-gradient(135deg,var(--teal),#1a8f80)',
                blue:   'linear-gradient(135deg,var(--blue),#1560a8)',
                gold:   'linear-gradient(135deg,var(--gold),#a07830)',
                red:    'linear-gradient(135deg,var(--red),#a03030)',
                purple: 'linear-gradient(135deg,var(--purple),#6344c2)',
                green:  'linear-gradient(135deg,var(--green),#1a7a3a)',
                amber:  'linear-gradient(135deg,var(--amber),#b07800)',
            };

            list.innerHTML = recipients.map((u, i) => {
                const col = colors[i % colors.length];
                const initials = ((u.name || '').split(' ').map(w => w[0]).join('').substring(0, 2)).toUpperCase();
                const isSelected = mmState.selected.has(String(u.id));
                const alertColor = u.active ? 'var(--green)' : 'var(--amber)';
                const textColor = (col === 'gold' || col === 'amber') ? '#111' : '#fff';
                return `
<div class="mm-recipient-item${isSelected ? ' selected' : ''}"
     data-id="${u.id}" data-name="${u.name}" data-email="${u.email}"
     data-active="${u.active}" onclick="mmToggle(this)">
    <div class="mm-checkbox">${isSelected ? '✓' : ''}</div>
    <div class="mm-rec-av" style="background:${gradients[col]};color:${textColor};">${initials}</div>
    <div class="mm-rec-info">
        <div class="mm-rec-name">${u.name}</div>
        <div class="mm-rec-detail">${u.email} · ${u.department}</div>
    </div>
    <div class="mm-rec-alert" style="background:${alertColor};"></div>
</div>`;
            }).join('');
        }

        // ── Toggle recipient selection ──
        window.mmToggle = function(el) {
            const id   = String(el.dataset.id);
            const user = { id, name: el.dataset.name, email: el.dataset.email, active: el.dataset.active };

            if (mmState.selected.has(id)) {
                mmState.selected.delete(id);
                el.classList.remove('selected');
                el.querySelector('.mm-checkbox').textContent = '';
            } else {
                mmState.selected.set(id, user);
                el.classList.add('selected');
                el.querySelector('.mm-checkbox').textContent = '✓';
            }
            mmUpdateUI();
        };

        window.mmSelectAll = function() {
            const items = document.querySelectorAll('#mm-list .mm-recipient-item');
            const allSel = [...items].every(i => i.classList.contains('selected'));
            items.forEach(el => {
                const id   = String(el.dataset.id);
                const user = { id, name: el.dataset.name, email: el.dataset.email, active: el.dataset.active };
                if (allSel) {
                    mmState.selected.delete(id);
                    el.classList.remove('selected');
                    el.querySelector('.mm-checkbox').textContent = '';
                } else {
                    mmState.selected.set(id, user);
                    el.classList.add('selected');
                    el.querySelector('.mm-checkbox').textContent = '✓';
                }
            });
            mmUpdateUI();
        };

        // ── Filter search (client-side instant + server-side debounced) ──
        let mmSearchTimer;
        window.mmFilter = function(q) {
            clearTimeout(mmSearchTimer);
            mmSearchTimer = setTimeout(() => {
                mmLoadRecipients(mmState.currentGroup || 'all', q);
            }, 350);
        };

        // ── Group filter ──
        window.mmGroup = function(pill, group) {
            document.querySelectorAll('.mm-gpill').forEach(p => p.classList.remove('active'));
            pill.classList.add('active');
            mmState.currentGroup = group;
            const search = document.getElementById('mm-search-input').value;
            mmLoadRecipients(group, search);
        };

        // ── Select group programmatically ──
        window.mmSelectGroup = function(group) {
            const pill = [...document.querySelectorAll('.mm-gpill')]
                .find(p => p.onclick?.toString().includes(`'${group}'`));
            if (pill) { mmGroup(pill, group); }
            else { mmLoadRecipients(group); }
        };

        // ── Update UI counters ──
        window.mmUpdateUI = function() {
            const count = mmState.selected.size;
            document.getElementById('mm-sel-badge').textContent = count + ' sélectionné' + (count > 1 ? 's' : '');
            document.getElementById('mm-count').textContent = count;
            document.getElementById('mm-send-count').textContent = count;
            document.getElementById('mm-sb-count').textContent = count;

            const strip = document.getElementById('mm-preview-strip');
            if (count === 0) {
                strip.innerHTML = '<div style="text-align:center;padding:20px;color:var(--text3);font-size:12px;">Aucun destinataire sélectionné.</div>';
                return;
            }
            const colors = ['teal','blue','gold','red','purple','green','amber'];
            const grads = {teal:'linear-gradient(135deg,var(--teal),#1a8f80)',blue:'linear-gradient(135deg,var(--blue),#1560a8)',gold:'linear-gradient(135deg,var(--gold),#a07830)',red:'linear-gradient(135deg,var(--red),#a03030)',purple:'linear-gradient(135deg,var(--purple),#6344c2)',green:'linear-gradient(135deg,var(--green),#1a7a3a)',amber:'linear-gradient(135deg,var(--amber),#b07800)'};
            let html = '';
            let i = 0;
            mmState.selected.forEach(u => {
                const col = colors[i % colors.length];
                const initials = (u.name.split(' ').map(w => w[0]).join('').substring(0,2)).toUpperCase();
                const tc = (col==='gold'||col==='amber') ? '#111' : '#fff';
                const dot = u.active === 'true' || u.active === true ? 'var(--green)' : 'var(--amber)';
                html += `<div class="mm-preview-row">
                    <div class="mm-preview-av" style="background:${grads[col]};color:${tc};">${initials}</div>
                    <div class="mm-preview-info">
                        <div class="mm-preview-name">${u.name}</div>
                        <div class="mm-preview-email">${u.email}</div>
                    </div>
                    <div class="mm-preview-status" style="background:${dot}"></div>
                </div>`;
                i++;
            });
            strip.innerHTML = html;
        };

        // ════════════════════════════════════════════
        //  AI GENERATION — Real HuggingFace API call
        // ════════════════════════════════════════════

        window.mmSelectIntent = function(el, intent) {
            document.querySelectorAll('.mm-intent-card').forEach(c => c.classList.remove('active'));
            el.classList.add('active');
            mmState.intent = intent;
            mmGenerate();
        };

        window.mmTone = function(el) {
            document.querySelectorAll('.mm-tone-pill').forEach(p => p.classList.remove('active'));
            el.classList.add('active');
            mmState.tone = el.textContent.trim();
        };

        window.mmLang = function(el, lang) {
            document.querySelectorAll('.mm-lang-pill').forEach(p => p.classList.remove('active'));
            el.classList.add('active');
            mmState.lang = lang;
        };

        window.mmGenerate = async function() {
            const btn      = document.getElementById('mm-gen-btn');
            const subjectEl = document.getElementById('mm-subject');
            const bodyEl    = document.getElementById('mm-body');
            const tag       = document.getElementById('mm-ia-tag');

            btn.disabled = true;
            btn.textContent = '⏳ Génération...';
            subjectEl.style.opacity = '0.4';
            bodyEl.style.opacity = '0.4';
            tag.textContent = '⏳ IA';

            try {
                const res = await fetch(MM_ROUTES.generate, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        intent:  mmState.intent,
                        tone:    mmState.tone,
                        lang:    mmState.lang,
                        context: document.getElementById('mm-context').value,
                    }),
                });

                const data = await res.json();

                subjectEl.value = data.subject || '';
                bodyEl.value    = data.body    || '';

                const source = data.source === 'huggingface' ? '✨ HF AI' : '📋 Template';
                tag.textContent = source;
                showToast(source + ' — Email généré (' + mmState.tone + ')', 'success');

            } catch (e) {
                showToast('Erreur génération : ' + e.message, 'error');
            } finally {
                subjectEl.style.opacity = '1';
                bodyEl.style.opacity    = '1';
                btn.disabled    = false;
                btn.textContent = '✨ Générer l\'email';
            }
        };

        // Quick campaign shortcut
        window.mmQuickCampaign = function(intent) {
            const card = document.querySelector(`.mm-intent-card[data-intent="${intent}"]`);
            if (card) mmSelectIntent(card, intent);
            else { mmState.intent = intent; mmGenerate(); }
        };

        // AI transform (shorten / formalize)
        window.mmAITransform = async function(type) {
            const bodyEl = document.getElementById('mm-body');
            const current = bodyEl.value.trim();
            if (!current) { showToast('Aucun contenu à transformer', 'error'); return; }

            const fakeIntents = {
                shorten:   'raccourcir cet email en gardant l\'essentiel',
                formalize: 'formaliser davantage le ton de cet email',
            };

            showToast('⏳ Transformation IA...', 'info');
            try {
                const res = await fetch(MM_ROUTES.generate, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    body: JSON.stringify({
                        intent: mmState.intent,
                        tone: type === 'formalize' ? 'Formel institutionnel' : mmState.tone,
                        lang: mmState.lang,
                        context: fakeIntents[type] + ': ' + current.substring(0, 200),
                    }),
                });
                const data = await res.json();
                if (data.body) { bodyEl.value = data.body; showToast('✓ Transformation appliquée', 'success'); }
            } catch (e) {
                showToast('Erreur : ' + e.message, 'error');
            }
        };

        // ════════════════════════════════════════════
        //  SEND — Real SMTP via backend
        // ════════════════════════════════════════════

        window.mmSend = async function() {
            const count = mmState.selected.size;
            if (count === 0) { showToast('Sélectionnez au moins un destinataire', 'error'); return; }

            const subject = document.getElementById('mm-subject').value.trim();
            const body    = document.getElementById('mm-body').value.trim();
            if (!subject) { showToast('Veuillez saisir un objet', 'error'); return; }
            if (!body)    { showToast('Veuillez saisir un corps d\'email', 'error'); return; }

            // Show sending overlay
            const overlay = document.getElementById('mm-sending-overlay');
            const bar     = document.getElementById('mm-sending-bar');
            const txt     = document.getElementById('mm-sending-text');
            const sub     = document.getElementById('mm-sending-sub');

            overlay.classList.add('show');
            bar.style.animation = 'none';
            bar.offsetHeight;
            bar.style.animation = 'mm-load 3s ease forwards';
            txt.textContent = 'Personnalisation en cours...';
            sub.textContent = count + ' emails en préparation';

            const recipientIds = [...mmState.selected.keys()];

            try {
                setTimeout(() => {
                    txt.textContent = 'Envoi via SMTP institutionnel...';
                    sub.textContent = 'Gmail · ' + count + ' destinataires';
                }, 1200);

                const res = await fetch(MM_ROUTES.send, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        recipients:  recipientIds,
                        subject:     subject,
                        body:        body,
                        personalize: true,
                    }),
                });

                const data = await res.json();

                overlay.classList.remove('show');

                if (data.success) {
                    showToast(`✅ ${data.sent} email(s) envoyé(s) avec succès !` + (data.failed > 0 ? ` (${data.failed} échec)` : ''), 'success');
                    mmShowResult(data);
                    // Optionally clear selection after send
                    // mmState.selected.clear(); mmUpdateUI();
                } else {
                    showToast('❌ ' + (data.message || 'Erreur d\'envoi'), 'error');
                    mmShowResult(data);
                }

            } catch (e) {
                overlay.classList.remove('show');
                showToast('❌ Erreur réseau : ' + e.message, 'error');
            }

            // Unlock send button
            const sendBtn = document.getElementById('mm-send-btn');
            sendBtn.disabled = false;
        };

        function mmShowResult(data) {
            const panel = document.getElementById('mm-result-panel');
            const body  = document.getElementById('mm-result-body');
            panel.style.display = 'block';
            const color = data.success ? 'var(--green)' : 'var(--red)';
            body.innerHTML = `
                <div style="font-size:13px;font-weight:700;color:${color};margin-bottom:8px;">
                    ${data.success ? '✅' : '❌'} ${data.message}
                </div>
                <div style="font-size:11px;color:var(--text3);font-family:var(--font-mono);">
                    Envoyés: <strong style="color:var(--green);">${data.sent ?? 0}</strong> &nbsp;·&nbsp;
                    Échecs: <strong style="color:var(--red);">${data.failed ?? 0}</strong>
                </div>
                ${data.errors?.length ? `<div style="margin-top:8px;font-size:10.5px;color:var(--red);">${data.errors.join('<br>')}</div>` : ''}
            `;
            panel.scrollIntoView({ behavior: 'smooth' });
        }

        // ════════════════════════════════════════════
        //  UTILITIES
        // ════════════════════════════════════════════

        window.mmInsertToken = function(token) {
            const el = document.getElementById('mm-body');
            const s = el.selectionStart, e = el.selectionEnd;
            el.value = el.value.substring(0, s) + token + el.value.substring(e);
            el.selectionStart = el.selectionEnd = s + token.length;
            el.focus();
            showToast('Variable insérée : ' + token, 'info');
        };

        window.mmPreview = function() {
            const subject = document.getElementById('mm-subject').value;
            const body    = document.getElementById('mm-body').value;
            const first   = mmState.selected.size > 0 ? [...mmState.selected.values()][0] : null;

            document.getElementById('mm-preview-subject').textContent = subject || '—';
            document.getElementById('mm-preview-to').textContent = first
                ? first.name + ' <' + first.email + '>'
                : mmState.selected.size + ' destinataires sélectionnés';

            let preview = body;
            if (first) {
                const nameParts = first.name.split(' ');
                preview = preview
                    .replace(/@{{NOM_COMPLET}}/g, first.name)
                    .replace(/@{{NOM}}/g, nameParts[nameParts.length-1] || '')
                    .replace(/@{{PRENOM}}/g, nameParts[0] || '')
                    .replace(/@{{EMAIL}}/g, first.email)
                    .replace(/@{{DATE}}/g, new Date().toLocaleDateString('fr-FR'));
            }
            document.getElementById('mm-preview-body').textContent = preview;
            document.getElementById('mm-preview-overlay').classList.add('show');
        };

        window.mmClosePreview = function(e) {
            if (!e || e.target === document.getElementById('mm-preview-overlay')) {
                document.getElementById('mm-preview-overlay').classList.remove('show');
            }
        };

        window.mmCopy = function() {
            const body = document.getElementById('mm-body').value;
            navigator.clipboard.writeText(body).then(() => showToast('✓ Copié dans le presse-papier', 'success'));
        };

        window.mmClear = function() {
            document.getElementById('mm-subject').value = '';
            document.getElementById('mm-body').value    = '';
            document.getElementById('mm-ia-tag').textContent = '✨ IA';
            showToast('Email effacé', 'info');
        };

        // ── Init on DOM ready ──
        document.addEventListener('DOMContentLoaded', () => {
            mmLoadRecipients('all');
            // Auto-generate first template on load
            setTimeout(() => mmGenerate(), 300);
        });
    </script>

@endsection
