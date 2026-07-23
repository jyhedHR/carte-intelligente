@extends('shared.layouts.backoffice')

@section('title', 'BPMN Modeler - Workflow Designer')
@section('breadcrumb', 'Workflows / BPMN Modeler')

@section('content')
<style>
    /* DROPDOWN WORKFLOWS*/
    /* Condition dropdown styling */
.condition-select, .condition-textarea {
    font-family: 'Courier New', monospace;
    font-size: 12px;
}

.condition-select option {
    font-family: monospace;
    padding: 4px;
}

.condition-select option[value=""] {
    color: var(--text3);
    font-style: italic;
}
/* Add to your existing style section */
.fbb-select {
    padding: 6px 10px;
    background: var(--bg3, #1a1a2e);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm, 6px);
    color: var(--text, #f0f0f0);
    font-size: 12px;
    font-family: var(--font-body);
    cursor: pointer;
    transition: border-color 0.18s;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%238a8f9a'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    padding-right: 28px;
}

.fbb-select:focus {
    outline: none;
    border-color: var(--gold, #c9a84c);
}

.fbb-select option {
    background: var(--bg3, #1a1a2e);
    color: var(--text, #f0f0f0);
}
    /*end dropdown style BAHA ^^*/

    /* ══ Custom action color picker ══ */
    .color-swatch-btn:hover { border-color: var(--gold); transform: scale(1.05); }
    .color-swatch-option:hover { border-color: var(--text, #f0f0f0) !important; transform: scale(1.12); }
    .color-swatch-option { transition: transform 0.12s, border-color 0.12s; }

    /* ══ Custom field dropdown-choice builder ══ */
    .field-option-input:focus {
        outline: none;
        border-color: var(--gold) !important;
    }
    .modeler-wrapper {
        display: flex;
        flex-direction: column;
        gap: 16px;
        height: calc(100vh - 140px);
    }
    .modeler-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        flex-shrink: 0;
    }
    .modeler-header h1 {
        font-size: 20px;
        font-weight: 700;
        color: var(--gold);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .modeler-actions { display: flex; gap: 8px; flex-wrap: wrap; }
    .modeler-body { display: flex; gap: 16px; flex: 1; min-height: 0; }

    /* Canvas */
    .modeler-canvas-wrap {
        flex: 1;
        min-width: 0;
        background: var(--bg2);
        border-radius: 12px;
        border: 1px solid var(--border);
        overflow: visible;
        position: relative;
        display: flex;
        flex-direction: column;
        /* ensure bpmn-js palette isn't hidden under the app nav */
        margin-left: 8px;
    }
    #bpmn-canvas {
        flex: 1;
        width: 100%;
        height: 100%;
        min-height: 500px;
        position: relative;
    }
    #bpmn-canvas .djs-container svg { background: var(--bg2) !important; }

    /* Sidebar */
    .modeler-sidebar {
        width: 300px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        gap: 12px;
        overflow-y: auto;
        overflow-x: hidden;
        transition: width 0.28s cubic-bezier(.4,0,.2,1),
                    opacity 0.22s ease,
                    margin-left 0.28s cubic-bezier(.4,0,.2,1);
    }
    .sidebar-card {
        background: var(--bg2);
        border-radius: 10px;
        border: 1px solid var(--border);
        padding: 16px;
    }
    .sidebar-card-label {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text3);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .field-group { margin-bottom: 12px; }
    .field-group:last-child { margin-bottom: 0; }
    .field-label {
        display: block;
        font-size: 11px;
        font-weight: 600;
        color: var(--text3);
        margin-bottom: 5px;
    }
    .field-input, .field-textarea {
        width: 100%;
        padding: 8px 10px;
        background: var(--bg3, #1a1a2e);
        border: 1px solid var(--border);
        border-radius: 6px;
        color: var(--text);
        font-size: 12.5px;
        box-sizing: border-box;
        transition: border-color 0.2s;
    }
    .field-input:focus, .field-textarea:focus {
        outline: none;
        border-color: var(--gold);
    }
    .field-textarea { resize: vertical; min-height: 72px; }
    .field-hint { font-size: 10px; color: var(--text3); margin-top: 4px; line-height: 1.4; }

    /* ══ Task Assignment Panel ══ */
    .task-panel { border-color: var(--gold); background: rgba(201,168,76,0.03); }
    .task-panel .sidebar-card-label { color: var(--gold); }
    .task-panel-empty {
        font-size: 12px;
        color: var(--text3);
        text-align: center;
        padding: 10px 0;
        line-height: 1.7;
    }
    .task-id-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: var(--bg3, #1a1a2e);
        border: 1px solid var(--border);
        border-radius: 5px;
        padding: 4px 8px;
        font-size: 11.5px;
        color: var(--gold);
        margin-bottom: 12px;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Tabs */
    .assign-type-tabs { display: flex; gap: 4px; margin-bottom: 12px; }
    .assign-tab {
        flex: 1;
        padding: 6px 4px;
        border-radius: 5px;
        font-size: 10.5px;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid var(--border);
        background: var(--bg3, #1a1a2e);
        color: var(--text3);
        text-align: center;
        transition: all 0.15s;
    }
    .assign-tab.active {
        background: rgba(201,168,76,0.15);
        border-color: var(--gold);
        color: var(--gold);
    }

    /* ══ ADMIN PICKER ══ */
    .admin-picker-wrap {
        position: relative;
    }
    .admin-search-input {
        width: 100%;
        padding: 8px 10px 8px 30px;
        background: var(--bg3, #1a1a2e);
        border: 1px solid var(--border);
        border-radius: 6px;
        color: var(--text);
        font-size: 12.5px;
        box-sizing: border-box;
        transition: border-color 0.2s;
    }
    .admin-search-input:focus {
        outline: none;
        border-color: var(--gold);
    }
    .admin-search-icon {
        position: absolute;
        left: 8px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text3);
        font-size: 14px;
        pointer-events: none;
        line-height: 1;
    }
    .admin-dropdown {
        position: absolute;
        top: calc(100% + 4px);
        left: 0; right: 0;
        background: var(--bg2);
        border: 1px solid var(--gold);
        border-radius: 7px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 100;
        box-shadow: 0 8px 24px rgba(0,0,0,0.4);
        display: none;
    }
    .admin-dropdown.open { display: block; }
    .admin-dropdown-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 10px;
        cursor: pointer;
        transition: background 0.12s;
        border-bottom: 1px solid var(--border);
    }
    .admin-dropdown-item:last-child { border-bottom: none; }
    .admin-dropdown-item:hover { background: rgba(201,168,76,0.1); }
    .admin-dropdown-item.selected { background: rgba(201,168,76,0.15); }
    .admin-avatar {
        width: 26px; height: 26px;
        border-radius: 50%;
        background: rgba(201,168,76,0.15);
        color: var(--gold);
        display: flex; align-items: center; justify-content: center;
        font-size: 9px; font-weight: 700;
        flex-shrink: 0;
        letter-spacing: 0.3px;
    }
    .admin-item-info { flex: 1; min-width: 0; }
    .admin-item-name {
        font-size: 12px;
        font-weight: 600;
        color: var(--text);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .admin-item-email {
        font-size: 10px;
        color: var(--text3);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .admin-selected-badge {
        display: none;
        align-items: center;
        gap: 6px;
        margin-top: 6px;
        padding: 5px 8px;
        background: rgba(201,168,76,0.12);
        border: 1px solid rgba(201,168,76,0.3);
        border-radius: 5px;
    }
    .admin-selected-badge.visible { display: flex; }
    .admin-selected-name { font-size: 11.5px; font-weight: 600; color: var(--gold); flex: 1; }
    .admin-clear-btn {
        background: none; border: none;
        color: var(--text3); font-size: 14px;
        cursor: pointer; padding: 0; line-height: 1;
    }
    .admin-clear-btn:hover { color: var(--text); }
    .admin-loading {
        padding: 12px;
        text-align: center;
        font-size: 11.5px;
        color: var(--text3);
    }
    .admin-empty {
        padding: 12px;
        text-align: center;
        font-size: 11.5px;
        color: var(--text3);
    }

    /* Tag input */
    .tag-input-wrap {
        background: var(--bg3, #1a1a2e);
        border: 1px solid var(--border);
        border-radius: 6px;
        padding: 5px 8px;
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        min-height: 38px;
        cursor: text;
        transition: border-color 0.2s;
    }
    .tag-input-wrap:focus-within { border-color: var(--gold); }
    .tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: rgba(201,168,76,0.15);
        border: 1px solid rgba(201,168,76,0.3);
        border-radius: 4px;
        padding: 2px 7px;
        font-size: 11px;
        color: var(--gold);
    }
    .tag-remove {
        cursor: pointer;
        opacity: 0.6;
        font-size: 14px;
        line-height: 1;
        background: none;
        border: none;
        color: var(--gold);
        padding: 0;
    }
    .tag-remove:hover { opacity: 1; }
    .tag-raw-input {
        border: none;
        background: transparent;
        color: var(--text);
        font-size: 12px;
        outline: none;
        flex: 1;
        min-width: 80px;
        padding: 2px 0;
    }

    /* Apply button */
    .btn-apply {
        width: 100%;
        padding: 8px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        border: 1px solid var(--gold);
        background: rgba(201,168,76,0.15);
        color: var(--gold);
        transition: background 0.15s;
        margin-top: 10px;
    }
    .btn-apply:hover { background: rgba(201,168,76,0.28); }

    /* Task summary */
    .task-summary-list {
        display: flex;
        flex-direction: column;
        gap: 5px;
        max-height: 200px;
        overflow-y: auto;
    }
    .task-summary-item {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        padding: 7px 9px;
        background: var(--bg3, #1a1a2e);
        border-radius: 6px;
        border: 1px solid var(--border);
        cursor: pointer;
        transition: border-color 0.15s;
        font-size: 11.5px;
    }
    .task-summary-item:hover { border-color: var(--gold); }
    .task-summary-item.has-assignment { border-left: 3px solid var(--gold); }
    .task-summary-name {
        font-weight: 600;
        color: var(--text);
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 175px;
    }
    .task-summary-assign { font-size: 10.5px; color: var(--text3); }
    .task-summary-assign span { color: var(--gold); }
    .task-dot {
        width: 7px; height: 7px;
        border-radius: 50%;
        background: var(--border);
        flex-shrink: 0;
        margin-top: 4px;
    }
    .task-dot.assigned { background: var(--gold); box-shadow: 0 0 5px rgba(201,168,76,0.5); }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 7px;
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: opacity 0.15s, transform 0.1s;
        white-space: nowrap;
    }
    .btn:active { transform: scale(0.97); }
    .btn:hover  { opacity: 0.88; }
    .btn-gold    { background: linear-gradient(135deg, var(--gold), var(--gold2, #d4a93a)); color: #111; }
    .btn-ghost   { background: var(--bg3, #1a1a2e); border: 1px solid var(--border); color: var(--text); }
    .btn-success { background: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.4); color: #4ade80; }
    .btn-full    { width: 100%; justify-content: center; margin-bottom: 8px; }
    .btn-full:last-child { margin-bottom: 0; }

    /* Templates */
    .template-list { display: flex; flex-direction: column; gap: 6px; }
    .template-item {
        padding: 10px 12px;
        background: var(--bg3, #1a1a2e);
        border-radius: 7px;
        cursor: pointer;
        transition: all 0.18s;
        border: 1px solid transparent;
    }
    .template-item:hover { border-color: var(--gold); }
    .template-name { font-size: 12.5px; font-weight: 600; color: var(--text); margin-bottom: 3px; }
    .template-desc { font-size: 11px; color: var(--text3); }

    /* Deploy status */
    .deploy-status { padding: 12px; border-radius: 8px; font-size: 12.5px; line-height: 1.6; display: none; }
    .deploy-status.show    { display: block; }
    .deploy-status.loading { background: rgba(201,168,76,0.1); border: 1px solid var(--gold); color: var(--gold); }
    .deploy-status.success { background: rgba(34,197,94,0.1);  border: 1px solid #4ade80;     color: #4ade80; }
    .deploy-status.error   { background: rgba(239,68,68,0.1);  border: 1px solid #f87171;     color: #f87171; }

    /* Toast */
    .bpmn-toast {
        position: fixed; bottom: 24px; right: 24px;
        padding: 12px 18px; border-radius: 8px;
        font-size: 13px; font-weight: 500; z-index: 9999;
        animation: toastIn 0.25s ease; pointer-events: none;
    }
    @keyframes toastIn {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .bpmn-toast.success { background: #166534; border: 1px solid #4ade80; color: #bbf7d0; }
    .bpmn-toast.error   { background: #7f1d1d; border: 1px solid #f87171; color: #fecaca; }
    .bpmn-toast.info    { background: #1e3a5f; border: 1px solid var(--gold); color: var(--gold); }

    /* Toolbar */
    .canvas-toolbar {
        display: flex; align-items: center; gap: 6px;
        padding: 8px 12px;
        background: var(--bg3, #1a1a2e);
        border-bottom: 1px solid var(--border);
        border-radius: 12px 12px 0 0;
        flex-shrink: 0;
        flex-wrap: nowrap;
        overflow: hidden;
        min-width: 0;
    }
    .canvas-toolbar span { font-size: 11px; color: var(--text3); }
    .toolbar-btn {
        padding: 5px 10px; border-radius: 5px;
        font-size: 11.5px; font-weight: 600; cursor: pointer;
        background: var(--bg2); border: 1px solid var(--border);
        color: var(--text); transition: all 0.15s;
    }
    .toolbar-btn:hover { border-color: var(--gold); color: var(--gold); }

    /* ══ COLLAPSIBLE SIDEBAR ══ */
    .modeler-sidebar.collapsed {
        width: 0 !important;
        opacity: 0;
        overflow: hidden;
        margin-left: 0 !important;
        pointer-events: none;
        flex-shrink: 0;
    }
    /* The collapse toggle button pinned to the right edge of the canvas */
    .sidebar-toggle-btn {
        position: absolute;
        top: 50%;
        right: -14px;
        transform: translateY(-50%);
        z-index: 50;
        width: 28px;
        height: 52px;
        border-radius: 0 8px 8px 0;
        background: var(--bg3, #1a1a2e);
        border: 1px solid var(--border);
        border-left: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--text3);
        font-size: 13px;
        transition: background 0.15s, color 0.15s, right 0.28s cubic-bezier(.4,0,.2,1);
        line-height: 1;
    }
    .sidebar-toggle-btn:hover {
        background: rgba(201,168,76,0.12);
        color: var(--gold);
        border-color: var(--gold);
    }
    /* When sidebar is collapsed, button shifts to right edge of canvas (flush) */
    .modeler-body.sidebar-collapsed .sidebar-toggle-btn {
        right: -14px;
    }

    /* ══ FULLSCREEN MODE ══ */
    .modeler-wrapper.fullscreen-mode {
        position: fixed;
        inset: 0;
        z-index: 9000;
        height: 100vh !important;
        background: var(--bg, #0f0f1a);
        padding: 0;
        border-radius: 0;
        gap: 0;
    }
    .modeler-wrapper.fullscreen-mode .modeler-header {
        display: none;
    }
    .modeler-wrapper.fullscreen-mode .modeler-body {
        flex: 1;
        min-height: 0;
        padding: 0;
        gap: 0;
    }
    .modeler-wrapper.fullscreen-mode .modeler-canvas-wrap {
        border-radius: 0;
        border: none;
    }
    /* Fullscreen toolbar button */
    .toolbar-btn-fullscreen {
        margin-left: auto;
        padding: 5px 10px; border-radius: 5px;
        font-size: 11.5px; font-weight: 600; cursor: pointer;
        background: var(--bg2); border: 1px solid var(--border);
        color: var(--text); transition: all 0.15s;
        display: flex; align-items: center; gap: 5px;
    }
    .toolbar-btn-fullscreen:hover { border-color: var(--gold); color: var(--gold); }
    /* ESC hint overlay during fullscreen */
    .fullscreen-esc-hint {
        display: none;
        position: fixed;
        top: 14px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 9100;
        background: rgba(15,15,26,0.88);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 6px 16px;
        font-size: 11.5px;
        color: var(--text3);
        pointer-events: none;
        backdrop-filter: blur(4px);
    }
    .modeler-wrapper.fullscreen-mode ~ .fullscreen-esc-hint,
    body.in-fullscreen .fullscreen-esc-hint {
        display: block;
    }

    /*candiate users*/
        /* Add multi-select specific styles */
    .multi-select-container {
        position: relative;
        width: 100%;
    }
    .multi-select-btn {
        width: 100%;
        padding: 8px 10px;
        background: var(--bg3, #1a1a2e);
        border: 1px solid var(--border);
        border-radius: 6px;
        color: var(--text);
        font-size: 12.5px;
        text-align: left;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .multi-select-btn:hover {
        border-color: var(--gold);
    }
    .multi-select-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: var(--bg2);
        border: 1px solid var(--gold);
        border-radius: 6px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
    }
    .multi-select-dropdown.open {
        display: block;
    }
    .multi-select-option {
        padding: 8px 10px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: 1px solid var(--border);
    }
    .multi-select-option:hover {
        background: rgba(201,168,76,0.1);
    }
    .multi-select-option input {
        margin: 0;
        width: 16px;
        height: 16px;
        cursor: pointer;
    }
    .selected-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-top: 8px;
    }
    .selected-tag {
        background: rgba(201,168,76,0.15);
        border: 1px solid rgba(201,168,76,0.3);
        border-radius: 4px;
        padding: 2px 8px;
        font-size: 11px;
        color: var(--gold);
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .selected-tag-remove {
        cursor: pointer;
        opacity: 0.6;
        font-size: 14px;
        line-height: 1;
        background: none;
        border: none;
        color: var(--gold);
        padding: 0;
    }
    .selected-tag-remove:hover {
        opacity: 1;
    }
</style>

<div class="modeler-wrapper">

<div class="modeler-header">
    <h1>
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="7" height="7" rx="1"/>
            <rect x="14" y="3" width="7" height="7" rx="1"/>
            <rect x="3" y="14" width="7" height="7" rx="1"/>
            <path d="M17.5 14v7M14 17.5h7"/>
        </svg>
        BPMN Workflow Modeler
    </h1>
    <div class="modeler-actions">
        <button onclick="newDiagram()" class="btn btn-ghost">📄 Nouveau</button>
        <button onclick="openXML()" class="btn btn-ghost">📂 Ouvrir</button>

        <!-- ==================== NEW: WORKFLOW LOADER ==================== -->
        <select id="loadWorkflowSelect"
                class="fbb-select"
                style="min-width: 200px;"
                onchange="loadDeployedWorkflow(this.value)">
            <option value="">📋 Charger un workflow déployé...</option>
        </select>

        <button onclick="refreshWorkflowList()"
                class="btn btn-ghost"
                title="Rafraîchir la liste">🔄</button>

        <button onclick="deleteSelectedWorkflow()"
                class="btn btn-ghost"
                style="color:#f87171;"
                title="Supprimer le workflow sélectionné">🗑️</button>
        <!-- ==================== END NEW ==================== -->

        <button onclick="saveDiagram()" class="btn btn-gold">Sauvegarder</button>
        <button onclick="deployToCamunda()" class="btn btn-success">Déployer</button>
        <button onclick="downloadDiagram()" class="btn btn-ghost">Export BPMN</button>
    </div>
</div>

    <div class="modeler-body">

        {{-- Canvas --}}
        <div class="modeler-canvas-wrap">
            {{-- Sidebar collapse toggle button --}}
            <button class="sidebar-toggle-btn" id="sidebarToggleBtn"
                    onclick="toggleSidebar()"
                    title="Réduire / Afficher le panneau latéral">
                <span id="sidebarToggleIcon">›</span>
            </button>
            <div class="canvas-toolbar">
                <button class="toolbar-btn" onclick="zoomIn()">＋</button>
                <button class="toolbar-btn" onclick="zoomOut()">－</button>
                <button class="toolbar-btn" onclick="zoomFit()">⊞ Fit</button>
                <button class="toolbar-btn" onclick="undoAction()">↩ Undo</button>
                <button class="toolbar-btn" onclick="redoAction()">↪ Redo</button>
                <span id="diagramStatus">Prêt</span>
                <button class="toolbar-btn-fullscreen" id="fullscreenBtn" onclick="toggleFullscreen()" title="Plein écran (Esc pour quitter)">
                    <svg id="fsIconExpand" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M8 3H5a2 2 0 00-2 2v3M21 8V5a2 2 0 00-2-2h-3M16 21h3a2 2 0 002-2v-3M3 16v3a2 2 0 002 2h3"/>
                    </svg>
                    <svg id="fsIconCompress" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="display:none">
                        <path d="M8 3v3a2 2 0 01-2 2H3M21 8h-3a2 2 0 01-2-2V3M3 16h3a2 2 0 012 2v3M16 21v-3a2 2 0 012-2h3"/>
                    </svg>
                    Plein écran
                </button>
            </div>
            <div id="bpmn-canvas"></div>
        </div>

        {{-- Sidebar --}}
        <div class="modeler-sidebar">

            {{-- Process Info --}}
            <div class="sidebar-card">
                <div class="sidebar-card-label">⚙ Processus</div>
                <div class="field-group">
                    <label class="field-label">ID du processus</label>
                    <input type="text" id="processId" class="field-input"
                           placeholder="ex: validation_demande" value="demande_validation">
                </div>
                <div class="field-group">
                    <label class="field-label">Nom du processus</label>
                    <input type="text" id="processName" class="field-input"
                           placeholder="Nom lisible" value="Validation de Demande">
                </div>
                <div class="field-group">
                    <label class="field-label">Historique TTL</label>
                    <input type="text" id="processTTL" class="field-input" placeholder="P30D" value="P30D">
                    <div class="field-hint">P30D = 30 jours · P90D = 90 jours · P1Y = 1 an</div>
                </div>
                <div class="field-group">
                    <label class="field-label">Description</label>
                    <textarea id="processDescription" class="field-textarea" placeholder="Description optionnelle..."></textarea>
                </div>
            </div>
{{-- ══ GROUP MANAGEMENT PANEL ══ --}}
<div class="sidebar-card">
    <div class="sidebar-card-label">👥 Gestion des Groupes Camunda</div>

    <button class="btn btn-ghost btn-full" onclick="openGroupManagementModal()">
        ➕ Gestion des Groupes
    </button>

    <div class="field-hint" style="margin-top: 8px;">
        Créez des groupes et assignez des administrateurs pour les utiliser dans les tâches.
    </div>
</div>
{{-- ══ TASK ASSIGNMENT PANEL ══ --}}
<div class="sidebar-card task-panel">
    <div class="sidebar-card-label">👤 Affectation de tâche</div>

    {{-- Empty state --}}
    <div id="taskPanelEmpty" class="task-panel-empty">
        Cliquez sur une <strong>User Task</strong> dans le diagramme pour configurer son affectation
    </div>

    {{-- Active state --}}
    <div id="taskPanelForm" style="display:none;">

        <div class="task-id-badge">📋 <span id="selectedTaskName">—</span></div>

        {{-- Assignment type tabs --}}
        <div class="assign-type-tabs">
            <div class="assign-tab active" id="tab-assignee"         onclick="switchAssignTab('assignee')">Assignee</div>
            <div class="assign-tab"        id="tab-candidateGroups"  onclick="switchAssignTab('candidateGroups')">Groupes</div>
            <div class="assign-tab"        id="tab-candidateUsers"   onclick="switchAssignTab('candidateUsers')">Utilisateurs</div>
            <div class="assign-tab" id="tab-customization" onclick="switchAssignTab('customization')">⚙️ Personnalisation</div>
        </div>

        {{-- Assignee Panel (Admin Dropdown) --}}
        <div id="panel-assignee">
            <label class="field-label">camunda:assignee (Administrateur)</label>

            <select id="assigneeSelect" class="field-input" onchange="onAssigneeSelect(this.value)">
                <option value="">— Choisir un administrateur —</option>
            </select>

            <div class="field-hint" style="margin-top: 8px;">
                Ou utiliser une expression EL (ex: <em>${initiator}</em>)
            </div>
            <input type="text" id="input-assignee-el" class="field-input mt-2"
                   placeholder="${initiator} ou ${approval_user}"
                   oninput="onElInput(this.value)">

            <input type="hidden" id="input-assignee" value="">
        </div>
{{-- PERSONALIZATION PANEL --}}
<div id="panel-customization" style="display:none;">
    <div class="field-group">
        <label class="field-label">📝 Description de la tâche</label>
        <textarea id="taskDescription" class="field-input" rows="3"
                  placeholder="Instructions pour les gestionnaires/directeurs…"></textarea>
    </div>

    <div class="field-group">
        <label class="field-label">👥 Rôles autorisés</label>
        <div style="display:flex; gap:8px; flex-wrap:wrap;">
            <label style="display:flex; align-items:center; gap:6px; cursor:pointer;">
                <input type="checkbox" id="roleManager" class="role-checkbox" value="manager">
                <span>Gestionnaire</span>
            </label>
            <label style="display:flex; align-items:center; gap:6px; cursor:pointer;">
                <input type="checkbox" id="roleDirector" class="role-checkbox" value="director">
                <span>Directeur</span>
            </label>
            <label style="display:flex; align-items:center; gap:6px; cursor:pointer;">
                <input type="checkbox" id="roleAdmin" class="role-checkbox" value="admin">
                <span>Administrateur</span>
            </label>
        </div>
    </div>

    <div class="field-group">
        <label class="field-label">🎯 Actions personnalisées</label>
        <div id="customActionsContainer" style="display:flex; flex-direction:column; gap:8px;">
            <!-- Actions will be added dynamically here -->
        </div>
        <button type="button" class="btn-small" onclick="addCustomAction()"
                style="margin-top:8px; background:var(--gold); color:#111; padding:6px 12px; border:none; border-radius:6px; cursor:pointer; font-size:12px;">
            + Ajouter une action
        </button>
    </div>

    <div class="field-group">
        <label class="field-label">📋 Champs de formulaire personnalisés</label>
        <div id="customFieldsContainer" style="display:flex; flex-direction:column; gap:8px;">
            <!-- Fields will be added dynamically here -->
        </div>
        <button type="button" class="btn-small" onclick="addCustomField()"
                style="margin-top:8px; background:var(--gold); color:#111; padding:6px 12px; border:none; border-radius:6px; cursor:pointer; font-size:12px;">
            + Ajouter un champ
        </button>
    </div>

    <div class="field-hint" style="margin-top:12px;">
        La personnalisation sera affichée dans le popup de tâche pendant l'exécution du workflow.
    </div>
</div>
{{-- Candidate Groups Panel --}}
<div id="panel-candidateGroups" style="display:none;">
    <label class="field-label">camunda:candidateGroups — Sélectionner des administrateurs</label>

    {{-- Search input with dropdown --}}
    <div style="position:relative;margin-bottom:6px;">
        <input type="text" id="groupsSearchInput" class="field-input"
               placeholder="🔍 Rechercher un admin…"
               oninput="filterGroupsDropdown(this.value)"
               onfocus="openGroupsDropdown()"
               autocomplete="off">
        {{-- This is the dropdown container --}}
        <div id="groupsDropdown" style="
            display:none;
            position:absolute;
            top:calc(100% + 4px);
            left:0; right:0;
            background:var(--bg2);
            border:1px solid var(--gold);
            border-radius:7px;
            max-height:180px;
            overflow-y:auto;
            z-index:9999;
            box-shadow:0 8px 24px rgba(0,0,0,0.4);
        ">
            {{-- Items rendered here by JS --}}
        </div>
    </div>

    {{-- Selected admins as tags --}}
    <div style="font-size:10px;color:var(--text3);margin-bottom:5px;">Sélectionnés :</div>
    <div class="tag-input-wrap" id="groupsTagWrap" style="min-height:36px;cursor:default;">
        <span id="groupsNoSelection" style="font-size:11px;color:var(--text3);padding:2px 0;">
            Aucun administrateur sélectionné
        </span>
    </div>

    <div class="field-hint" style="margin-top:6px;">
        Tous les admins sélectionnés pourront réclamer cette tâche dans Camunda.
    </div>
</div>

        <!-- Apply Button - Important: Put it here so it always shows -->
        <button class="btn-apply" onclick="applyTaskAssignment(); saveTaskPersonalization()"">
            ✓ Appliquer l'affectation
        </button>

    </div>
</div>


{{-- ══ SERVICE TASK CONFIGURATION PANEL ══ --}}
<div class="sidebar-card task-panel" id="serviceTaskPanel" style="display: none;">
    <div class="sidebar-card-label">⚙️ Service Task Configuration</div>

    <div id="serviceTaskEmpty" class="task-panel-empty">
        Cliquez sur une <strong>Service Task</strong> dans le diagramme pour configurer son type externe
    </div>

    <div id="serviceTaskForm" style="display:none;">
        <div class="task-id-badge">🔧 <span id="selectedServiceTaskName">—</span></div>

        <div class="field-group">
            <label class="field-label">Implementation Type</label>
            <select id="serviceTaskType" class="field-input" onchange="onServiceTaskTypeChange(this.value)">
                <option value="">— Sélectionner —</option>
                <option value="external">External (Worker externe)</option>
                <option value="connector">Connector (HTTP/REST)</option>
                <option value="class">Classe Java</option>
            </select>
        </div>

        <div id="externalTopicGroup" style="display:none;">
            <div class="field-group">
                <label class="field-label">📋 Topic Name (pour worker Laravel)</label>

                <!-- Dropdown for easy selection -->
                <select id="serviceTaskTopicSelect" class="field-input"
                        style="margin-bottom: 8px;"
                        onchange="onTopicSelect(this.value)">
                    <option value="">— Sélectionner un topic —</option>
                    <option value="generate-attestation">📄 generate-attestation (Génération attestation PDF)</option>
                    <option value="send-email-notification">📧 send-email-notification (Envoi notification email)</option>
                    <option value="generate-report">📊 generate-report (Génération rapport)</option>
                    <option value="process-payment">💰 process-payment (Traitement paiement)</option>
                </select>

                <div style="position: relative;">
                    <label class="field-label" style="font-size: 10px;">Ou saisir manuellement :</label>
                    <input type="text" id="serviceTaskTopic" class="field-input"
                           placeholder="generate-attestation, send-email-notification, ..."
                           oninput="onManualTopicInput(this.value)">
                </div>

                <div class="field-hint" style="margin-top: 8px;">
                    ⚡ Le worker Laravel écoute le topic <strong>generate-attestation</strong> pour générer les attestations PDF.
                </div>
            </div>
        </div>

        <div id="connectorGroup" style="display:none;">
            <div class="field-group">
                <label class="field-label">🌐 Connector ID</label>
                <input type="text" id="serviceTaskConnectorId" class="field-input"
                       placeholder="http-connector, soap-connector, ...">
            </div>
        </div>

        <div id="classGroup" style="display:none;">
            <div class="field-group">
                <label class="field-label">📦 Java Class</label>
                <input type="text" id="serviceTaskClass" class="field-input"
                       placeholder="com.example.MyJavaDelegate">
            </div>
        </div>

        <div class="field-group">
            <label class="field-label">🏷️ Task Priority (optionnel)</label>
            <input type="number" id="serviceTaskPriority" class="field-input"
                   placeholder="0-100" value="50">
        </div>

        <div class="field-group">
            <label class="field-label">⏱️ Retry Timeout (ms)</label>
            <input type="number" id="serviceTaskRetryTimeout" class="field-input"
                   placeholder="5000" value="5000">
        </div>

        <button class="btn-apply" onclick="applyServiceTaskConfig()">
            ✓ Appliquer la configuration Service Task
        </button>
    </div>
</div>
        <!-- ==================== NEW: GATEWAY CONFIGURATION PANEL ==================== -->
        {{-- ══ GATEWAY CONFIGURATION PANEL ══ --}}
        <div class="sidebar-card task-panel" id="gatewayPanel" style="display: none;">
            <div class="sidebar-card-label">🔀 Configuration de la passerelle (Gateway)</div>

            <div id="gatewayEmpty" class="task-panel-empty">
                Cliquez sur une <strong>Exclusive Gateway</strong> dans le diagramme pour configurer ses conditions
            </div>

            <div id="gatewayForm" style="display:none;">
                <div class="task-id-badge">🔀 <span id="selectedGatewayName">—</span></div>

                <div class="field-group">
                    <label class="field-label">Type de passerelle</label>
                    <select id="gatewayType" class="field-input" onchange="onGatewayTypeChange(this.value)">
                        <option value="exclusive">Exclusive (XOR) - Une seule sortie</option>
                        <option value="parallel">Parallel (AND) - Toutes les sorties</option>
                        <option value="inclusive">Inclusive (OR) - Une ou plusieurs sorties</option>
                        <option value="event">Event-based</option>
                    </select>
                    <div class="field-hint">Définit le comportement de la passerelle</div>
                </div>

                <div class="field-group">
                    <label class="field-label">Séquence de sortie par défaut</label>
                    <select id="defaultFlowSelect" class="field-input">
                        <option value="">— Aucune (pas de flux par défaut) —</option>
                    </select>
                    <div class="field-hint">
                        Le flux qui sera emprunté si aucune autre condition n'est remplie.
                        <strong>Obligatoire pour les passerelles exclusives.</strong>
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Conditions des flux de sortie</label>
                    <div id="outgoingFlowsList" style="margin-top: 8px; max-height: 300px; overflow-y: auto;">
                        <div class="fbb-loading-row" style="text-align: center; padding: 20px;">Chargement des flux...</div>
                    </div>
                    <div class="field-hint" style="margin-top: 8px;">
                        Définissez les conditions pour chaque flux.<br>
                        Exemple: <code>${approved == true}</code> ou <code>${montant > 1000}</code>
                    </div>
                </div>

                <button class="btn-apply" onclick="applyGatewayConfig()">
                    ✓ Appliquer la configuration
                </button>
            </div>
        </div>
        <!-- ==================== END GATEWAY PANEL ==================== -->

            {{-- Task summary list --}}
            <div class="sidebar-card" id="taskSummaryCard" style="display:none;">
                <div class="sidebar-card-label">📋 Récapitulatif des tâches</div>
                <div class="task-summary-list" id="taskSummaryList"></div>
            </div>
            <!-- ==================== NEW: GATEWAY SUMMARY CARD ==================== -->
            {{-- Gateway summary list --}}
            <div class="sidebar-card" id="gatewaySummaryCard" style="display:none;">
                <div class="sidebar-card-label">🔀 Passerelles configurées</div>
                <div class="task-summary-list" id="gatewaySummaryList"></div>
            </div>
            <!-- ==================== END GATEWAY SUMMARY ==================== -->
            {{-- Actions --}}
            <div class="sidebar-card">
                <div class="sidebar-card-label">🔧 Actions</div>
                <button onclick="validateDiagram()" class="btn btn-ghost btn-full">🔍 Valider le diagramme</button>
                <button onclick="zoomFit()"          class="btn btn-ghost btn-full">🔎 Ajuster la vue</button>
            </div>
            <!-- ==================== NEW: QUICK ACTIONS ==================== -->
            {{-- ══ QUICK ADD SERVICE TASK - PROMINENT BUTTON ══ --}}
            <div class="sidebar-card" style="border: 2px solid var(--gold); background: linear-gradient(135deg, rgba(201,168,76,0.05), rgba(201,168,76,0.02));">
                <div class="sidebar-card-label" style="color: var(--gold);">⚡ Actions Rapides</div>

                <button onclick="addAttestationServiceTask()"
                        class="btn btn-gold"
                        style="width: 100%; justify-content: center; gap: 8px; font-size: 13px; padding: 12px; margin-bottom: 8px;">
                    Ajouter une tâche "Générer Attestation"
                </button>
                <div class="field-hint" style="text-align: center;">
                    Crée et configure automatiquement une Service Task avec le topic <strong>generate-attestation</strong>
                </div>
            </div>
            {{-- Templates --}}
            <div class="sidebar-card">
                <div class="sidebar-card-label">📋 Templates</div>
                <div class="template-list">
                    <!-- Existing User Task Templates -->
                    <div class="template-item" onclick="loadTemplate('approval')">
                        <div class="template-name">Approbation simple</div>
                        <div class="template-desc">Soumission → Admin → Fin</div>
                    </div>
                    <div class="template-item" onclick="loadTemplate('two-step')">
                        <div class="template-name">Deux niveaux</div>
                        <div class="template-desc">Admin → Directeur (groupe) → Fin</div>
                    </div>
                    <div class="template-item" onclick="loadTemplate('gateway')">
                        <div class="template-name">Avec passerelle</div>
                        <div class="template-desc">Examen → Gateway → Accepté/Refusé</div>
                    </div>
                    <div class="template-item" onclick="loadTemplate('multi-step')">
                        <div class="template-name">Multi-étapes</div>
                        <div class="template-desc">agents_niveau1 → chefs → directeurs</div>
                    </div>

                    <!-- ==================== NEW SERVICE TASK TEMPLATES ==================== -->
                    <div class="template-item" onclick="loadServiceTaskTemplate('attestation')">
                        <div class="template-name">📄 Tâche: Générer Attestation</div>
                        <div class="template-desc">Service Task externe: generate-attestation</div>
                    </div>
                    <div class="template-item" onclick="loadServiceTaskTemplate('email')">
                        <div class="template-name">📧 Tâche: Envoyer Email</div>
                        <div class="template-desc">Service Task externe: send-email-notification</div>
                    </div>
                    <!-- ================================================================ -->
                </div>
            </div>

            <div id="deployStatus" class="deploy-status"></div>
{{-- ══ MODAL — GROUP MANAGEMENT ══ --}}
<div id="modal-group-management" class="modal">
    <div class="modal-content" style="max-width: 700px;">
        <div class="modal-header">
            <div class="modal-title">👥 Gestion des Groupes Camunda</div>
            <button class="modal-close" onclick="closeModal('modal-group-management')">×</button>
        </div>
        <div class="modal-body">

            {{-- Create Group Form --}}
            <div style="background: var(--bg3); border-radius: 8px; padding: 16px; margin-bottom: 20px;">
                <div style="font-weight: 700; margin-bottom: 12px; color: var(--gold);">➕ Créer un nouveau groupe</div>
                <div class="field-group">
                    <label class="field-label">ID du groupe</label>
                    <input type="text" id="newGroupId" class="field-input" placeholder="ex: step1_admins">
                </div>
                <div class="field-group">
                    <label class="field-label">Nom du groupe</label>
                    <input type="text" id="newGroupName" class="field-input" placeholder="ex: Administrateurs Étape 1">
                </div>
                <button class="btn btn-gold btn-full" onclick="createGroup()">Créer le groupe</button>
            </div>
{{-- Sync Users Button --}}
<div style="background: var(--bg3); border-radius: 8px; padding: 16px; margin-bottom: 20px;">
    <div style="font-weight: 700; margin-bottom: 12px; color: var(--gold);">🔄 Synchronisation des utilisateurs</div>
    <div class="field-hint" style="margin-bottom: 12px;">
        Synchronisez tous les administrateurs Laravel vers Camunda pour pouvoir les ajouter aux groupes.
    </div>
    <button class="btn btn-gold btn-full" onclick="syncUsersToCamunda()">
        🔄 Synchroniser les utilisateurs
    </button>
</div>
            {{-- Groups List --}}
            <div style="font-weight: 700; margin-bottom: 10px; color: var(--gold);">📋 Groupes existants</div>
            {{-- Search bar --}}
            <div style="position: relative; margin-bottom: 10px;">
                <span style="position:absolute;left:9px;top:50%;transform:translateY(-50%);color:var(--text3);font-size:14px;pointer-events:none;">🔍</span>
                <input type="text" id="groupsListSearch"
                       class="field-input"
                       style="padding-left: 30px;"
                       placeholder="Rechercher un groupe…"
                       oninput="filterGroupsList(this.value)">
            </div>
            <div id="groupsList" style="max-height: 360px; overflow-y: auto;">
                <div class="ap-loading-row" style="text-align: center; padding: 20px;">Chargement des groupes...</div>
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('modal-group-management')">Fermer</button>
        </div>
    </div>
</div>

{{-- Modal for managing group members --}}
<div id="modal-group-members" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <div class="modal-title">👥 Membres du groupe: <span id="groupMembersTitle">—</span></div>
            <button class="modal-close" onclick="closeModal('modal-group-members')">×</button>
        </div>
        <div class="modal-body">
            <div style="margin-bottom: 16px;">
                <label class="field-label">Ajouter un administrateur</label>

                {{-- Search-powered admin picker --}}
                <div style="position:relative;" id="memberPickerWrap">
                    <span style="position:absolute;left:9px;top:50%;transform:translateY(-50%);color:var(--text3);font-size:13px;pointer-events:none;">🔍</span>
                    <input type="text" id="memberSearchInput" class="field-input"
                           style="padding-left:30px;"
                           placeholder="Rechercher par nom, email, rôle…"
                           autocomplete="off"
                           oninput="filterMemberPicker(this.value)"
                           onfocus="openMemberPicker()">
                    {{-- hidden value holder --}}
                    <input type="hidden" id="addUserSelect" value="">
                    {{-- Dropdown --}}
                    <div id="memberPickerDropdown" style="
                        display:none;
                        position:absolute;
                        top:calc(100% + 4px);
                        left:0; right:0;
                        background:var(--bg2);
                        border:1px solid var(--gold);
                        border-radius:8px;
                        max-height:240px;
                        overflow-y:auto;
                        z-index:9999;
                        box-shadow:0 8px 24px rgba(0,0,0,0.45);
                    "></div>
                </div>

                {{-- Selected admin preview --}}
                <div id="memberPickerSelected" style="display:none;
                    margin-top:8px; padding:8px 10px;
                    background:rgba(201,168,76,0.1);
                    border:1px solid rgba(201,168,76,0.35);
                    border-radius:7px;
                    display:none; align-items:center; gap:10px;">
                    <div style="flex:1;min-width:0;">
                        <div id="memberPickerSelectedName" style="font-size:12.5px;font-weight:700;color:var(--gold);"></div>
                        <div id="memberPickerSelectedMeta" style="font-size:11px;color:var(--text3);margin-top:2px;"></div>
                    </div>
                    <button onclick="clearMemberPicker()" style="background:none;border:none;color:var(--text3);font-size:16px;cursor:pointer;line-height:1;padding:0;" title="Effacer">×</button>
                </div>

                <button class="btn btn-gold btn-full" style="margin-top:10px;" onclick="addUserToCurrentGroup()">➕ Ajouter au groupe</button>
            </div>

            <div style="font-weight: 700; margin-bottom: 12px; color: var(--gold);">📋 Membres actuels</div>
            <div id="groupMembersList" style="max-height: 280px; overflow-y: auto;">
                <div class="ap-loading-row" style="text-align: center; padding: 20px;">Chargement...</div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('modal-group-members')">Fermer</button>
        </div>
    </div>
</div>
        </div>
    </div>
</div>


<script>
   async function syncUsersToCamunda() {
    const btn = event.target;
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = '⏳ Synchronisation...';

    try {
        const response = await fetch('/api/workflows/groups/sync-users', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'Content-Type': 'application/json'
            }
        });

        const result = await response.json();

        if (result.success) {
            let message = '📊 Résultats de la synchronisation:\n\n';
            let created = 0;
            let exists = 0;
            let failed = 0;

            result.results.forEach(r => {
                if (r.status === 'created') {
                    message += `✅ ${r.email}: Créé\n`;
                    created++;
                } else if (r.status === 'exists') {
                    message += `ℹ️ ${r.email}: Déjà existant\n`;
                    exists++;
                } else {
                    message += `❌ ${r.email}: Échec - ${r.error}\n`;
                    failed++;
                }
            });

            message += `\n📈 Total: ${created} créés, ${exists} existants, ${failed} échecs`;
            alert(message);

            if (created > 0) {
                toast(`${created} utilisateur(s) créé(s) dans Camunda`, 'success');
                // Refresh the user dropdown
                await fetchAdmins();
            }
        } else {
            toast(`❌ Erreur: ${result.error}`, 'error');
        }
    } catch (err) {
        toast(`❌ Erreur: ${err.message}`, 'error');
        console.error('Sync error:', err);
    } finally {
        btn.disabled = false;
        btn.textContent = originalText;
    }
}
    // ════════════════════════════════════════════════════════════
//  GROUP MANAGEMENT
// ════════════════════════════════════════════════════════════
// Define CSRF token for API calls
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';
let currentGroupId = null;
let allCamundaGroups = [];

async function openGroupManagementModal() {
    openModal('modal-group-management');
    await loadGroups();
}

async function loadGroups() {
    const container = document.getElementById('groupsList');
    container.innerHTML = '<div class="ap-loading-row" style="text-align: center; padding: 20px;">Chargement des groupes...</div>';
    // reset search
    const searchEl = document.getElementById('groupsListSearch');
    if (searchEl) searchEl.value = '';

    try {
        const response = await fetch('/api/workflows/groups', {
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json'
            }
        });

        const result = await response.json();

        if (result.success && result.groups) {
            allCamundaGroups = result.groups;

            if (allCamundaGroups.length === 0) {
                container.innerHTML = '<div style="text-align: center; padding: 20px; color: var(--text3);">Aucun groupe trouvé. Créez votre premier groupe !</div>';
                return;
            }

            container.innerHTML = allCamundaGroups.map(group => `
                <div style="background: var(--bg3); border-radius: 8px; padding: 12px; margin-bottom: 12px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <div>
                            <strong style="color: var(--gold);">${escapeHtml(group.name)}</strong>
                            <div style="font-size: 11px; color: var(--text3);">ID: ${escapeHtml(group.id)}</div>
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <button class="btn-ghost" style="padding: 4px 8px; font-size: 11px;" onclick="manageGroupMembers('${group.id}', '${escapeHtml(group.name)}')">
                                👥 Gérer
                            </button>
                            <button class="btn-ghost" style="padding: 4px 8px; font-size: 11px; color: var(--red);" onclick="deleteGroup('${group.id}')">
                                🗑️ Supprimer
                            </button>
                        </div>
                    </div>
                    <div style="font-size: 11px; color: var(--text3);">
                        Type: ${group.type || 'WORKFLOW'}
                    </div>
                </div>
            `).join('');
        } else {
            container.innerHTML = '<div style="text-align: center; padding: 20px; color: var(--red);">Erreur lors du chargement des groupes</div>';
        }
    } catch (err) {
        console.error('Error loading groups:', err);
        container.innerHTML = '<div style="text-align: center; padding: 20px; color: var(--red);">Erreur: ' + err.message + '</div>';
    }
}

function filterGroupsList(query) {
    const q = query.trim().toLowerCase();
    if (!allCamundaGroups || !allCamundaGroups.length) return;

    const filtered = q
        ? allCamundaGroups.filter(g =>
            (g.name || '').toLowerCase().includes(q) ||
            (g.id   || '').toLowerCase().includes(q) ||
            (g.type || '').toLowerCase().includes(q))
        : allCamundaGroups;

    const container = document.getElementById('groupsList');
    if (!filtered.length) {
        container.innerHTML = `<div style="text-align:center;padding:20px;color:var(--text3);">Aucun groupe trouvé pour « ${escapeHtml(query)} »</div>`;
        return;
    }
    container.innerHTML = filtered.map(group => `
        <div style="background: var(--bg3); border-radius: 8px; padding: 12px; margin-bottom: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <div>
                    <strong style="color: var(--gold);">${escapeHtml(group.name)}</strong>
                    <div style="font-size: 11px; color: var(--text3);">ID: ${escapeHtml(group.id)}</div>
                </div>
                <div style="display: flex; gap: 8px;">
                    <button class="btn-ghost" style="padding: 4px 8px; font-size: 11px;" onclick="manageGroupMembers('${group.id}', '${escapeHtml(group.name)}')">
                        👥 Gérer
                    </button>
                    <button class="btn-ghost" style="padding: 4px 8px; font-size: 11px; color: var(--red);" onclick="deleteGroup('${group.id}')">
                        🗑️ Supprimer
                    </button>
                </div>
            </div>
            <div style="font-size: 11px; color: var(--text3);">
                Type: ${group.type || 'WORKFLOW'}
            </div>
        </div>
    `).join('');
}

// Also invalidate the cache when a new group is created so next open reflects it
async function createGroup() {
    const groupId   = document.getElementById('newGroupId').value.trim();
    const groupName = document.getElementById('newGroupName').value.trim();

    if (!groupId || !groupName) { toast('Veuillez remplir tous les champs', 'error'); return; }

    const btn = event.target;
    btn.disabled    = true;
    btn.textContent = '⏳ Création…';

    try {
        const res    = await fetch('/api/workflows/groups', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body:    JSON.stringify({ group_id: groupId, group_name: groupName, group_type: 'WORKFLOW' })
        });
        const result = await res.json();

        if (result.success) {
            toast(`✅ Groupe "${groupName}" créé avec succès`, 'success');
            document.getElementById('newGroupId').value   = '';
            document.getElementById('newGroupName').value = '';

            // Invalidate cache so the groups dropdown refreshes next time it opens
            groupsCacheFetched    = false;
            allCamundaGroupsCache = [];

            await loadGroups();   // refresh the management modal list
        } else {
            toast(`❌ Erreur: ${result.error}`, 'error');
        }
    } catch (err) {
        toast(`❌ Erreur: ${err.message}`, 'error');
    } finally {
        btn.disabled    = false;
        btn.textContent = 'Créer le groupe';
    }
}
async function manageGroupMembers(groupId, groupName) {
    currentGroupId = groupId;
    document.getElementById('groupMembersTitle').textContent = groupName;

    // Reset the member picker
    clearMemberPicker();

    openModal('modal-group-members');
    await loadGroupMembers(groupId);
}

// ════════════════════════════════════════════════════════════
//  MEMBER PICKER (search-powered admin selector)
// ════════════════════════════════════════════════════════════
let memberPickerSelected = null; // { email, fullName, role, department }

function getRoleBadgeColor(role) {
    if (!role) return '#8a8f9a';
    const r = role.toLowerCase();
    if (r.includes('super')) return '#c9a84c';
    if (r.includes('département') || r.includes('departement')) return '#60a5fa';
    return '#a78bfa';
}

function buildMemberPickerRow(admin, query) {
    const q   = (query || '').toLowerCase();
    const hl  = (str) => {
        if (!q || !str) return escapeHtml(str || '');
        const idx = str.toLowerCase().indexOf(q);
        if (idx === -1) return escapeHtml(str);
        return escapeHtml(str.slice(0, idx))
             + `<mark style="background:rgba(201,168,76,0.35);color:var(--gold);border-radius:2px;">${escapeHtml(str.slice(idx, idx + q.length))}</mark>`
             + escapeHtml(str.slice(idx + q.length));
    };

    const roleColor = getRoleBadgeColor(admin.role);
    const roleBadge = `<span style="
        display:inline-block;
        padding:1px 7px;
        border-radius:4px;
        font-size:9.5px;
        font-weight:700;
        background:${roleColor}22;
        border:1px solid ${roleColor}55;
        color:${roleColor};
        letter-spacing:0.04em;
        margin-bottom:3px;
    ">${escapeHtml(admin.role || 'Admin')}</span>`;

    const deptLine = admin.department
        ? `<span style="font-size:10px;color:var(--text3);">🏢 ${hl(admin.department)}</span> · `
        : '';

    return `
    <div class="member-picker-item" data-email="${escapeHtml(admin.email)}"
         onclick="selectMemberPickerAdmin('${escapeHtml(admin.email)}')"
         style="display:flex;align-items:flex-start;gap:10px;padding:9px 12px;cursor:pointer;
                border-bottom:1px solid var(--border);transition:background 0.12s;">
        <div style="width:32px;height:32px;border-radius:50%;
                    background:rgba(201,168,76,0.12);color:var(--gold);
                    display:flex;align-items:center;justify-content:center;
                    font-size:11px;font-weight:700;flex-shrink:0;">
            ${escapeHtml((admin.prenom||'?')[0].toUpperCase())}${escapeHtml((admin.nom||'?')[0].toUpperCase())}
        </div>
        <div style="flex:1;min-width:0;">
            ${roleBadge}
            <div style="font-size:12.5px;font-weight:600;color:var(--text);">${hl(admin.fullName)}</div>
            <div style="font-size:10.5px;color:var(--text3);margin-top:1px;">
                ${deptLine}${hl(admin.email)}
            </div>
        </div>
    </div>`;
}

function openMemberPicker() {
    filterMemberPicker(document.getElementById('memberSearchInput').value);
}

function filterMemberPicker(query) {
    const dropdown = document.getElementById('memberPickerDropdown');
    if (!allAdmins || !allAdmins.length) {
        dropdown.innerHTML = '<div style="padding:12px;text-align:center;color:var(--text3);font-size:12px;">⏳ Chargement des administrateurs…</div>';
        dropdown.style.display = 'block';
        return;
    }

    const q = (query || '').trim().toLowerCase();
    const filtered = q
        ? allAdmins.filter(a =>
            (a.fullName   || '').toLowerCase().includes(q) ||
            (a.email      || '').toLowerCase().includes(q) ||
            (a.role       || '').toLowerCase().includes(q) ||
            (a.department || '').toLowerCase().includes(q))
        : allAdmins;

    if (!filtered.length) {
        dropdown.innerHTML = `<div style="padding:12px;text-align:center;color:var(--text3);font-size:12px;">Aucun administrateur trouvé</div>`;
    } else {
        dropdown.innerHTML = filtered.slice(0, 50).map(a => buildMemberPickerRow(a, q)).join('');
        // hover effect
        dropdown.querySelectorAll('.member-picker-item').forEach(el => {
            el.addEventListener('mouseenter', () => el.style.background = 'rgba(201,168,76,0.08)');
            el.addEventListener('mouseleave', () => el.style.background = '');
        });
    }
    dropdown.style.display = 'block';
}

function selectMemberPickerAdmin(email) {
    const admin = allAdmins.find(a => a.email === email || a.camundaId === email);
    if (!admin) return;

    memberPickerSelected = admin;
    document.getElementById('addUserSelect').value = admin.email;
    document.getElementById('memberSearchInput').value = admin.fullName;
    document.getElementById('memberPickerDropdown').style.display = 'none';

    // Show preview badge
    const roleColor = getRoleBadgeColor(admin.role);
    document.getElementById('memberPickerSelectedName').textContent = admin.fullName;
    document.getElementById('memberPickerSelectedMeta').innerHTML =
        `<span style="color:${roleColor}">${escapeHtml(admin.role || 'Admin')}</span>`
        + (admin.department ? ` · 🏢 ${escapeHtml(admin.department)}` : '')
        + ` · ${escapeHtml(admin.email)}`;

    const preview = document.getElementById('memberPickerSelected');
    preview.style.display = 'flex';
}

function clearMemberPicker() {
    memberPickerSelected = null;
    const inp = document.getElementById('memberSearchInput');
    const hid = document.getElementById('addUserSelect');
    const dd  = document.getElementById('memberPickerDropdown');
    const pre = document.getElementById('memberPickerSelected');
    if (inp) inp.value = '';
    if (hid) hid.value = '';
    if (dd)  dd.style.display = 'none';
    if (pre) pre.style.display = 'none';
}

// Close picker when clicking outside
document.addEventListener('click', function(e) {
    const wrap = document.getElementById('memberPickerWrap');
    const dd   = document.getElementById('memberPickerDropdown');
    if (wrap && dd && !wrap.contains(e.target)) {
        dd.style.display = 'none';
    }
});

async function loadGroupMembers(groupId) {
    const container = document.getElementById('groupMembersList');
    container.innerHTML = '<div style="text-align:center;padding:20px;color:var(--text3);">⏳ Chargement des membres...</div>';

    try {
        console.log('Loading members for group:', groupId);
        console.log('CSRF token:', CSRF);

        const response = await fetch(`/api/workflows/groups/${groupId}/members`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin'  // Important: send cookies
        });

        console.log('Response status:', response.status);

        if (!response.ok) {
            const errorText = await response.text();
            console.error('Error response:', errorText);
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        const members = await response.json();
        console.log('Group members from API:', members);

        if (!members || members.length === 0) {
            container.innerHTML = '<div style="text-align:center;padding:20px;color:var(--text3);">👥 Aucun membre dans ce groupe</div>';
            return;
        }

        let membersHtml = '';

        for (const member of members) {
            const userId = member.userId;
            const displayName = member.displayName || userId;
            const email = member.email || '';

            membersHtml += `
            <div style="background:var(--bg2);border-radius:8px;padding:12px;margin-bottom:8px;
                        display:flex;justify-content:space-between;align-items:center;
                        border:1px solid var(--border);">
                <div>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:32px;height:32px;border-radius:50%;background:rgba(201,168,76,0.15);
                                    display:flex;align-items:center;justify-content:center;">
                            <span style="font-size:14px;">👤</span>
                        </div>
                        <div>
                            <strong style="color:var(--text);font-size:13px;">${escapeHtml(displayName)}</strong>
                            ${email ? `<div style="font-size:11px;color:var(--text3);margin-top:2px;">📧 ${escapeHtml(email)}</div>` : ''}
                            <div style="font-size:10px;color:var(--text3);margin-top:2px;font-family:monospace;">🆔 ${escapeHtml(userId)}</div>
                        </div>
                    </div>
                </div>
                <button class="btn-ghost"
                        style="padding:5px 12px;font-size:11px;color:#f87171;border-color:rgba(248,113,113,0.3);border-radius:6px;cursor:pointer;"
                        onclick="removeUserFromGroup('${escapeHtml(userId)}')">
                    ❌ Retirer
                </button>
            </div>`;
        }

        container.innerHTML = membersHtml;

        const summary = document.createElement('div');
        summary.style.cssText = 'margin-top:12px;padding:8px;background:rgba(201,168,76,0.05);border-radius:6px;text-align:center;font-size:11px;color:var(--text3);';
        summary.innerHTML = `📊 Total: ${members.length} membre(s) dans ce groupe`;
        container.appendChild(summary);

    } catch (err) {
        console.error('Error loading members:', err);
        container.innerHTML = `<div style="text-align:center;padding:20px;color:#f87171;">
            ❌ Erreur: ${escapeHtml(err.message)}<br>
            <span style="font-size:10px;margin-top:8px;display:block;">Vérifiez que vous êtes connecté</span>
        </div>`;
    }
}

async function addUserToCurrentGroup() {
    const userId = document.getElementById('addUserSelect').value;

    if (!userId) {
        toast('Veuillez sélectionner un administrateur', 'error');
        return;
    }

    const btn = event.target;
    btn.disabled = true;
    btn.textContent = '⏳ Ajout...';

    try {
        const response = await fetch('/api/workflows/groups/add-user', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                group_id: currentGroupId,
                user_id: userId
            })
        });

        const result = await response.json();

        if (result.success) {
            toast(`✅ ${userId} ajouté au groupe`, 'success');
            await loadGroupMembers(currentGroupId);
            await loadGroups(); // Refresh groups list to update member counts
        } else {
            toast(`❌ Erreur: ${result.error}`, 'error');
        }
    } catch (err) {
        toast(`❌ Erreur: ${err.message}`, 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Ajouter';
    }
}
async function removeUserFromGroup(userId) {
    if (!confirm('Retirer cet utilisateur du groupe ?')) return;

    try {
        const response = await fetch('/api/workflows/groups/remove-user', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF
            },
            body: JSON.stringify({
                group_id: currentGroupId,
                user_id: userId
            })
        });

        const result = await response.json();

        if (result.success) {
            toast('✅ Utilisateur retiré du groupe', 'success');
            await loadGroupMembers(currentGroupId);
        } else {
            toast(`❌ Erreur: ${result.error}`, 'error');
        }
    } catch (err) {
        toast(`❌ Erreur: ${err.message}`, 'error');
    }
}

async function deleteGroup(groupId) {
    if (!confirm('Supprimer ce groupe ? Cette action est irréversible.')) return;

    try {
        const response = await fetch(`/api/workflows/groups/${groupId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': CSRF
            }
        });

        const result = await response.json();

        if (result.success) {
            toast('✅ Groupe supprimé', 'success');
            await loadGroups();
        } else {
            toast(`❌ Erreur: ${result.error}`, 'error');
        }
    } catch (err) {
        toast(`❌ Erreur: ${err.message}`, 'error');
    }
}

// Update the candidate groups panel to show actual Camunda groups instead of individual admins
async function loadCamundaGroups() {
    try {
        const response = await fetch('/api/workflows/groups', {
            headers: { 'X-CSRF-TOKEN': CSRF }
        });
        const result = await response.json();

        if (result.success) {
            return result.groups;
        }
        return [];
    } catch (err) {
        console.error('Error loading Camunda groups:', err);
        return [];
    }
}

// Replace the candidate groups dropdown to show groups instead of individual admins

async function openGroupsDropdown() {
    const dd = document.getElementById('groupsDropdown');
    if (!dd) return;

    // Show loading state immediately
    dd.innerHTML = '<div style="padding:12px;text-align:center;font-size:11.5px;color:var(--text3);">Chargement des groupes…</div>';
    dd.style.display = 'block';

    await fetchCamundaGroups();

    renderGroupsDropdown(document.getElementById('groupsSearchInput')?.value || '');

    // Close on outside click
    setTimeout(() => {
        document.addEventListener('click', closeGroupsDropdownOnOutside);
    }, 0);
}

// Update selected groups state (store group IDs instead of admin objects)
let selectedCandidateGroups = []; // Array of group IDs

function toggleCandidateGroup(groupId, groupName) {
    const idx = selectedCandidateGroups.findIndex(g => g.id === groupId);
    if (idx >= 0) {
        selectedCandidateGroups.splice(idx, 1);
    } else {
        selectedCandidateGroups.push({ id: groupId, name: groupName });
    }
    renderSelectedGroups();
    // Re-render dropdown in place to update checkmarks (without re-fetching)
    renderGroupsDropdown(document.getElementById('groupsSearchInput')?.value || '');
}
function renderSelectedGroups() {
    const wrap  = document.getElementById('groupsTagWrap');
    const noSel = document.getElementById('groupsNoSelection');
    if (!wrap) return;

    wrap.querySelectorAll('.tag').forEach(t => t.remove());

    if (!selectedCandidateGroups.length) {
        if (noSel) noSel.style.display = '';
        return;
    }

    if (noSel) noSel.style.display = 'none';

    selectedCandidateGroups.forEach(group => {
        const tag = document.createElement('span');
        tag.className = 'tag';
        tag.innerHTML = `👥 ${escHtml(group.name)}
            <button class="tag-remove"
                    onclick="removeSelectedGroup('${escHtml(group.id)}')"
                    title="Retirer">×</button>`;
        wrap.appendChild(tag);
    });
}


function removeSelectedGroup(groupId) {
    selectedCandidateGroups = selectedCandidateGroups.filter(g => g.id !== groupId);
    renderSelectedGroups();
    renderGroupsDropdown(document.getElementById('groupsSearchInput')?.value || '');
}
// Populate groups panel when opening a task that already has candidateGroups set
async function populateGroupsPicker(candidateGroupsValue = '') {
    selectedCandidateGroups = [];

    if (!candidateGroupsValue) {
        renderSelectedGroups();
        return;
    }

    // Ensure cache is populated so we can resolve names
    await fetchCamundaGroups();

    const groupIds = candidateGroupsValue.split(',').map(s => s.trim()).filter(Boolean);
    groupIds.forEach(groupId => {
        // Try to resolve name from cache
        const match = allCamundaGroupsCache.find(g => g.id === groupId);
        selectedCandidateGroups.push({
            id:   groupId,
            name: match ? match.name : groupId   // fallback: show ID if group not found
        });
    });

    renderSelectedGroups();
}





/* ═══════════════════════════════════════════════════════════════
   BPMN MODELER — Clean Admin Assignee Picker
═══════════════════════════════════════════════════════════════ */

let modeler         = null;
let currentXML      = null;
let selectedElement = null;
let activeAssignTab = 'assignee';
const tagState      = { users: [] }; // groups now handled separately

// Admin state
let allAdmins            = [];
let adminsFetched        = false;
let groupsSelectedAdmins = []; // Array of admin objects selected for candidateGroups



// ════════════════════════════════════════════════════════════
//  FETCH ADMINS
// ════════════════════════════════════════════════════════════
async function fetchAdmins() {
    if (adminsFetched) return;
    try {
        const response = await fetch('{{ route("admin.workflows.admins") }}', {
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json'
            }
        });
        if (!response.ok) throw new Error('Failed to fetch admins');
        allAdmins = await response.json();
        adminsFetched = true;
        populateAssigneeDropdown();
        console.log('✅ Admins loaded:', allAdmins.length);
    } catch (err) {
        console.error('Error fetching admins:', err);
        toast('Erreur lors du chargement des administrateurs', 'error');
    }
}


// ════════════════════════════════════════════════════════════
//  ASSIGNEE PANEL (single admin dropdown)
// ════════════════════════════════════════════════════════════
function populateAssigneeDropdown() {
    const select = document.getElementById('assigneeSelect');
    if (!select) return;
    select.innerHTML = '<option value="">— Choisir un administrateur —</option>';
    allAdmins.forEach(admin => {
        const opt = document.createElement('option');
        opt.value = admin.camundaId || admin.email;
        opt.textContent = `${admin.fullName} (${admin.email})`;
        select.appendChild(opt);
    });
}

function onAssigneeSelect(value) {
    document.getElementById('input-assignee').value = value || '';
    if (value) document.getElementById('input-assignee-el').value = '';
}

function onElInput(val) {
    document.getElementById('input-assignee').value = val.trim();
    if (val.trim()) {
        const select = document.getElementById('assigneeSelect');
        if (select) select.value = '';
    }
}

// Safe clear function
function clearAdminSelection() {
    const hidden  = document.getElementById('input-assignee');
    const elInput = document.getElementById('input-assignee-el');
    const select  = document.getElementById('assigneeSelect');
    if (hidden)  hidden.value = '';
    if (elInput) elInput.value = '';
    if (select)  select.value = '';
}

// Populate when a User Task is selected
async function populateAssigneePicker(assigneeValue = '') {
    if (!adminsFetched) await fetchAdmins();
    clearAdminSelection();
    if (!assigneeValue) return;

    const hidden  = document.getElementById('input-assignee');
    const elInput = document.getElementById('input-assignee-el');
    const select  = document.getElementById('assigneeSelect');
    if (!hidden) return;

    hidden.value = assigneeValue;
    if (assigneeValue.includes('$') || assigneeValue.includes('{')) {
        if (elInput) elInput.value = assigneeValue;
    } else {
        const match = allAdmins.find(a => a.camundaId === assigneeValue || a.email === assigneeValue);
        if (match && select) select.value = assigneeValue;
        else if (elInput) elInput.value = assigneeValue;
    }
}
// ════════════════════════════════════════════════════════════
//  CANDIDATE GROUPS PANEL (multi-select admin dropdown)
// ════════════════════════════════════════════════════════════

//function openGroupsDropdown() {
  //  if (!adminsFetched) {
    //    fetchAdmins().then(() => {
      //      renderGroupsDropdown('');
        //    document.getElementById('groupsDropdown').style.display = 'block';
        //});
        //return;
    //}
    //renderGroupsDropdown(document.getElementById('groupsSearchInput')?.value || '');
    //document.getElementById('groupsDropdown').style.display = 'block';

    // Close on outside click — use a named function so we can remove it
    //setTimeout(() => {
      //  document.addEventListener('click', closeGroupsDropdownOnOutside);
    //}, 0);
//}

function closeGroupsDropdownOnOutside(e) {
    const dd    = document.getElementById('groupsDropdown');
    const input = document.getElementById('groupsSearchInput');
    if (!dd) return;
    if (!dd.contains(e.target) && e.target !== input) {
        dd.style.display = 'none';
        document.removeEventListener('click', closeGroupsDropdownOnOutside);
    }
}
// Safe JSON decode helper (avoids HTML attribute escaping issues)
function decodeAndParse(encoded) {
    try {
        return JSON.parse(decodeURIComponent(encoded));
    } catch(e) {
        console.error('decodeAndParse failed', e);
        return {};
    }
}

function filterGroupsDropdown(query) {
    const dd = document.getElementById('groupsDropdown');
    if (dd) dd.style.display = 'block';
    renderGroupsDropdown(query);
}

function renderGroupsDropdown(query = '') {
    const dd = document.getElementById('groupsDropdown');
    if (!dd) return;

    const q        = query.toLowerCase().trim();
    const filtered = allCamundaGroupsCache.filter(g =>
        !q ||
        (g.name || '').toLowerCase().includes(q) ||
        (g.id   || '').toLowerCase().includes(q)
    );

    if (!filtered.length) {
        dd.innerHTML = `<div style="padding:12px;text-align:center;font-size:11.5px;color:var(--text3);">
            ${allCamundaGroupsCache.length === 0
                ? 'Aucun groupe trouvé. Créez des groupes via "Gérer les groupes".'
                : 'Aucun résultat pour cette recherche.'}
        </div>`;
        return;
    }

    dd.innerHTML = filtered.map(group => {
        const isSelected = selectedCandidateGroups.some(s => s.id === group.id);
        const memberCount = group.member_count !== undefined ? ` · ${group.member_count} membre(s)` : '';

        return `
            <div class="admin-dropdown-item ${isSelected ? 'selected' : ''}"
                 onclick="toggleCandidateGroup('${escHtml(group.id)}', '${escHtml(group.name)}')"
                 style="cursor:pointer;">
                <div class="admin-avatar" style="font-size:14px;">👥</div>
                <div class="admin-item-info">
                    <div class="admin-item-name">${escHtml(group.name)}</div>
                    <div class="admin-item-email">ID: ${escHtml(group.id)}${memberCount}</div>
                </div>
                <span style="font-size:16px;color:var(--gold);flex-shrink:0;width:16px;text-align:center;"
                      id="grp-check-${escHtml(group.id)}">
                    ${isSelected ? '✓' : ''}
                </span>
            </div>`;
    }).join('');
}


function toggleGroupAdmin(admin) {
    if (!admin || !admin.email) return;
    const idx = groupsSelectedAdmins.findIndex(s => s.email === admin.email);
    if (idx >= 0) {
        groupsSelectedAdmins.splice(idx, 1);
    } else {
        groupsSelectedAdmins.push(admin);
    }
    renderGroupsTags();
    renderGroupsDropdown(document.getElementById('groupsSearchInput')?.value || '');
}

function removeGroupAdmin(email) {
    groupsSelectedAdmins = groupsSelectedAdmins.filter(a => a.email !== email);
    renderGroupsTags();
    renderGroupsDropdown(document.getElementById('groupsSearchInput')?.value || '');
}

function renderGroupsTags() {
    const wrap = document.getElementById('groupsTagWrap');
    const noSel = document.getElementById('groupsNoSelection');
    if (!wrap) return;

    // Remove existing tags (keep the noSelection span)
    wrap.querySelectorAll('.tag').forEach(t => t.remove());

    if (!groupsSelectedAdmins.length) {
        if (noSel) noSel.style.display = '';
        return;
    }

    if (noSel) noSel.style.display = 'none';

    groupsSelectedAdmins.forEach(admin => {
        const tag = document.createElement('span');
        tag.className = 'tag';
        tag.innerHTML = `
            ${escHtml(admin.fullName || admin.email)}
            <button class="tag-remove"
                    onclick="removeGroupAdmin('${escHtml(admin.email)}')"
                    title="Retirer">×</button>`;
        wrap.appendChild(tag);
    });
}

// Populate groups panel when opening a task with existing candidateGroups
function populateGroupsPicker(candidateGroupsValue = '') {
    groupsSelectedAdmins = [];

    if (!candidateGroupsValue) {
        renderGroupsTags();
        return;
    }

    // candidateGroups stored as comma-separated emails
    const emails = candidateGroupsValue.split(',').map(s => s.trim()).filter(Boolean);
    emails.forEach(email => {
        const match = allAdmins.find(a => a.email === email || a.camundaId === email);
        if (match) {
            groupsSelectedAdmins.push(match);
        } else {
            // Unknown value — keep it as a pseudo-admin object
            groupsSelectedAdmins.push({ email, fullName: email, camundaId: email });
        }
    });

    renderGroupsTags();
}

// ════════════════════════════════════════════════════════════
//  DEFAULT XML & TEMPLATES (unchanged)
// ════════════════════════════════════════════════════════════
const DEFAULT_XML = `<?xml version="1.0" encoding="UTF-8"?>
<bpmn:definitions
    xmlns:bpmn="http://www.omg.org/spec/BPMN/20100524/MODEL"
    xmlns:bpmndi="http://www.omg.org/spec/BPMN/20100524/DI"
    xmlns:dc="http://www.omg.org/spec/DD/20100524/DC"
    xmlns:di="http://www.omg.org/spec/DD/20100524/DI"
    xmlns:camunda="http://camunda.org/schema/1.0/bpmn"
    id="Definitions_1" targetNamespace="http://bpmn.io/schema/bpmn">
  <bpmn:process id="demande_validation" name="Validation de Demande" isExecutable="true" camunda:historyTimeToLive="P30D">
    <bpmn:startEvent id="StartEvent_1" name="Début"><bpmn:outgoing>Flow_1</bpmn:outgoing></bpmn:startEvent>
    <bpmn:userTask id="Task_1" name="Validation Admin" camunda:assignee="admin">
      <bpmn:incoming>Flow_1</bpmn:incoming><bpmn:outgoing>Flow_2</bpmn:outgoing>
    </bpmn:userTask>
    <bpmn:endEvent id="EndEvent_1" name="Fin"><bpmn:incoming>Flow_2</bpmn:incoming></bpmn:endEvent>
    <bpmn:sequenceFlow id="Flow_1" sourceRef="StartEvent_1" targetRef="Task_1"/>
    <bpmn:sequenceFlow id="Flow_2" sourceRef="Task_1" targetRef="EndEvent_1"/>
  </bpmn:process>
  <bpmndi:BPMNDiagram id="BPMNDiagram_1">
    <bpmndi:BPMNPlane id="BPMNPlane_1" bpmnElement="demande_validation">
      <bpmndi:BPMNShape id="StartEvent_1_di" bpmnElement="StartEvent_1">
        <dc:Bounds x="152" y="172" width="36" height="36"/>
        <bpmndi:BPMNLabel><dc:Bounds x="145" y="215" width="50" height="14"/></bpmndi:BPMNLabel>
      </bpmndi:BPMNShape>
      <bpmndi:BPMNShape id="Task_1_di" bpmnElement="Task_1"><dc:Bounds x="250" y="150" width="100" height="80"/></bpmndi:BPMNShape>
      <bpmndi:BPMNShape id="EndEvent_1_di" bpmnElement="EndEvent_1">
        <dc:Bounds x="422" y="172" width="36" height="36"/>
        <bpmndi:BPMNLabel><dc:Bounds x="419" y="215" width="42" height="14"/></bpmndi:BPMNLabel>
      </bpmndi:BPMNShape>
      <bpmndi:BPMNEdge id="Flow_1_di" bpmnElement="Flow_1">
        <di:waypoint x="188" y="190"/><di:waypoint x="250" y="190"/>
      </bpmndi:BPMNEdge>
      <bpmndi:BPMNEdge id="Flow_2_di" bpmnElement="Flow_2">
        <di:waypoint x="350" y="190"/><di:waypoint x="422" y="190"/>
      </bpmndi:BPMNEdge>
    </bpmndi:BPMNPlane>
  </bpmndi:BPMNDiagram>
</bpmn:definitions>`;

const TEMPLATES = {
    approval: `<?xml version="1.0" encoding="UTF-8"?>
<bpmn:definitions xmlns:bpmn="http://www.omg.org/spec/BPMN/20100524/MODEL" xmlns:bpmndi="http://www.omg.org/spec/BPMN/20100524/DI" xmlns:dc="http://www.omg.org/spec/DD/20100524/DC" xmlns:di="http://www.omg.org/spec/DD/20100524/DI" xmlns:camunda="http://camunda.org/schema/1.0/bpmn" id="Definitions_1" targetNamespace="http://bpmn.io/schema/bpmn">
  <bpmn:process id="approbation_simple" name="Approbation Simple" isExecutable="true" camunda:historyTimeToLive="P30D">
    <bpmn:startEvent id="Start_1" name="Soumission"><bpmn:outgoing>F1</bpmn:outgoing></bpmn:startEvent>
    <bpmn:userTask id="Task_Admin" name="Validation Admin" camunda:assignee="admin"><bpmn:incoming>F1</bpmn:incoming><bpmn:outgoing>F2</bpmn:outgoing></bpmn:userTask>
    <bpmn:endEvent id="End_1" name="Terminé"><bpmn:incoming>F2</bpmn:incoming></bpmn:endEvent>
    <bpmn:sequenceFlow id="F1" sourceRef="Start_1" targetRef="Task_Admin"/>
    <bpmn:sequenceFlow id="F2" sourceRef="Task_Admin" targetRef="End_1"/>
  </bpmn:process>
  <bpmndi:BPMNDiagram id="D1"><bpmndi:BPMNPlane id="P1" bpmnElement="approbation_simple">
    <bpmndi:BPMNShape id="S1" bpmnElement="Start_1"><dc:Bounds x="152" y="172" width="36" height="36"/></bpmndi:BPMNShape>
    <bpmndi:BPMNShape id="S2" bpmnElement="Task_Admin"><dc:Bounds x="250" y="150" width="100" height="80"/></bpmndi:BPMNShape>
    <bpmndi:BPMNShape id="S3" bpmnElement="End_1"><dc:Bounds x="422" y="172" width="36" height="36"/></bpmndi:BPMNShape>
    <bpmndi:BPMNEdge id="E1" bpmnElement="F1"><di:waypoint x="188" y="190"/><di:waypoint x="250" y="190"/></bpmndi:BPMNEdge>
    <bpmndi:BPMNEdge id="E2" bpmnElement="F2"><di:waypoint x="350" y="190"/><di:waypoint x="422" y="190"/></bpmndi:BPMNEdge>
  </bpmndi:BPMNPlane></bpmndi:BPMNDiagram>
</bpmn:definitions>`,

    'two-step': `<?xml version="1.0" encoding="UTF-8"?>
<bpmn:definitions xmlns:bpmn="http://www.omg.org/spec/BPMN/20100524/MODEL" xmlns:bpmndi="http://www.omg.org/spec/BPMN/20100524/DI" xmlns:dc="http://www.omg.org/spec/DD/20100524/DC" xmlns:di="http://www.omg.org/spec/DD/20100524/DI" xmlns:camunda="http://camunda.org/schema/1.0/bpmn" id="Definitions_1" targetNamespace="http://bpmn.io/schema/bpmn">
  <bpmn:process id="deux_niveaux" name="Approbation Deux Niveaux" isExecutable="true" camunda:historyTimeToLive="P30D">
    <bpmn:startEvent id="Start_1" name="Soumission"><bpmn:outgoing>F1</bpmn:outgoing></bpmn:startEvent>
    <bpmn:userTask id="Task_Admin" name="Validation Admin" camunda:assignee="admin"><bpmn:incoming>F1</bpmn:incoming><bpmn:outgoing>F2</bpmn:outgoing></bpmn:userTask>
    <bpmn:userTask id="Task_Dir" name="Validation Directeur" camunda:candidateGroups="directeurs"><bpmn:incoming>F2</bpmn:incoming><bpmn:outgoing>F3</bpmn:outgoing></bpmn:userTask>
    <bpmn:endEvent id="End_1" name="Terminé"><bpmn:incoming>F3</bpmn:incoming></bpmn:endEvent>
    <bpmn:sequenceFlow id="F1" sourceRef="Start_1" targetRef="Task_Admin"/>
    <bpmn:sequenceFlow id="F2" sourceRef="Task_Admin" targetRef="Task_Dir"/>
    <bpmn:sequenceFlow id="F3" sourceRef="Task_Dir" targetRef="End_1"/>
  </bpmn:process>
  <bpmndi:BPMNDiagram id="D1"><bpmndi:BPMNPlane id="P1" bpmnElement="deux_niveaux">
    <bpmndi:BPMNShape id="S1" bpmnElement="Start_1"><dc:Bounds x="152" y="172" width="36" height="36"/></bpmndi:BPMNShape>
    <bpmndi:BPMNShape id="S2" bpmnElement="Task_Admin"><dc:Bounds x="250" y="150" width="100" height="80"/></bpmndi:BPMNShape>
    <bpmndi:BPMNShape id="S3" bpmnElement="Task_Dir"><dc:Bounds x="410" y="150" width="100" height="80"/></bpmndi:BPMNShape>
    <bpmndi:BPMNShape id="S4" bpmnElement="End_1"><dc:Bounds x="572" y="172" width="36" height="36"/></bpmndi:BPMNShape>
    <bpmndi:BPMNEdge id="E1" bpmnElement="F1"><di:waypoint x="188" y="190"/><di:waypoint x="250" y="190"/></bpmndi:BPMNEdge>
    <bpmndi:BPMNEdge id="E2" bpmnElement="F2"><di:waypoint x="350" y="190"/><di:waypoint x="410" y="190"/></bpmndi:BPMNEdge>
    <bpmndi:BPMNEdge id="E3" bpmnElement="F3"><di:waypoint x="510" y="190"/><di:waypoint x="572" y="190"/></bpmndi:BPMNEdge>
  </bpmndi:BPMNPlane></bpmndi:BPMNDiagram>
</bpmn:definitions>`,

    gateway: `<?xml version="1.0" encoding="UTF-8"?>
<bpmn:definitions xmlns:bpmn="http://www.omg.org/spec/BPMN/20100524/MODEL" xmlns:bpmndi="http://www.omg.org/spec/BPMN/20100524/DI" xmlns:dc="http://www.omg.org/spec/DD/20100524/DC" xmlns:di="http://www.omg.org/spec/DD/20100524/DI" xmlns:camunda="http://camunda.org/schema/1.0/bpmn" id="Definitions_1" targetNamespace="http://bpmn.io/schema/bpmn">
  <bpmn:process id="avec_gateway" name="Approbation avec Passerelle" isExecutable="true" camunda:historyTimeToLive="P30D">
    <bpmn:startEvent id="Start_1" name="Soumission"><bpmn:outgoing>F1</bpmn:outgoing></bpmn:startEvent>
    <bpmn:userTask id="Task_Review" name="Examen" camunda:candidateGroups="agents_niveau1"><bpmn:incoming>F1</bpmn:incoming><bpmn:outgoing>F2</bpmn:outgoing></bpmn:userTask>
    <bpmn:exclusiveGateway id="Gateway_1" name="Décision"><bpmn:incoming>F2</bpmn:incoming><bpmn:outgoing>F_yes</bpmn:outgoing><bpmn:outgoing>F_no</bpmn:outgoing></bpmn:exclusiveGateway>
    <bpmn:endEvent id="End_Accept" name="Accepté"><bpmn:incoming>F_yes</bpmn:incoming></bpmn:endEvent>
    <bpmn:endEvent id="End_Reject" name="Refusé"><bpmn:incoming>F_no</bpmn:incoming></bpmn:endEvent>
    <bpmn:sequenceFlow id="F1" sourceRef="Start_1" targetRef="Task_Review"/>
    <bpmn:sequenceFlow id="F2" sourceRef="Task_Review" targetRef="Gateway_1"/>
    <bpmn:sequenceFlow id="F_yes" name="Oui" sourceRef="Gateway_1" targetRef="End_Accept"/>
    <bpmn:sequenceFlow id="F_no" name="Non" sourceRef="Gateway_1" targetRef="End_Reject"/>
  </bpmn:process>
  <bpmndi:BPMNDiagram id="D1"><bpmndi:BPMNPlane id="P1" bpmnElement="avec_gateway">
    <bpmndi:BPMNShape id="S1" bpmnElement="Start_1"><dc:Bounds x="152" y="172" width="36" height="36"/></bpmndi:BPMNShape>
    <bpmndi:BPMNShape id="S2" bpmnElement="Task_Review"><dc:Bounds x="250" y="150" width="100" height="80"/></bpmndi:BPMNShape>
    <bpmndi:BPMNShape id="GW1" bpmnElement="Gateway_1" isMarkerVisible="true"><dc:Bounds x="415" y="165" width="50" height="50"/></bpmndi:BPMNShape>
    <bpmndi:BPMNShape id="S3" bpmnElement="End_Accept"><dc:Bounds x="532" y="112" width="36" height="36"/></bpmndi:BPMNShape>
    <bpmndi:BPMNShape id="S4" bpmnElement="End_Reject"><dc:Bounds x="532" y="232" width="36" height="36"/></bpmndi:BPMNShape>
    <bpmndi:BPMNEdge id="E1" bpmnElement="F1"><di:waypoint x="188" y="190"/><di:waypoint x="250" y="190"/></bpmndi:BPMNEdge>
    <bpmndi:BPMNEdge id="E2" bpmnElement="F2"><di:waypoint x="350" y="190"/><di:waypoint x="415" y="190"/></bpmndi:BPMNEdge>
    <bpmndi:BPMNEdge id="E3" bpmnElement="F_yes"><di:waypoint x="440" y="165"/><di:waypoint x="440" y="130"/><di:waypoint x="532" y="130"/></bpmndi:BPMNEdge>
    <bpmndi:BPMNEdge id="E4" bpmnElement="F_no"><di:waypoint x="440" y="215"/><di:waypoint x="440" y="250"/><di:waypoint x="532" y="250"/></bpmndi:BPMNEdge>
  </bpmndi:BPMNPlane></bpmndi:BPMNDiagram>
</bpmn:definitions>`,

    'multi-step': `<?xml version="1.0" encoding="UTF-8"?>
<bpmn:definitions xmlns:bpmn="http://www.omg.org/spec/BPMN/20100524/MODEL" xmlns:bpmndi="http://www.omg.org/spec/BPMN/20100524/DI" xmlns:dc="http://www.omg.org/spec/DD/20100524/DC" xmlns:di="http://www.omg.org/spec/DD/20100524/DI" xmlns:camunda="http://camunda.org/schema/1.0/bpmn" id="Definitions_1" targetNamespace="http://bpmn.io/schema/bpmn">
  <bpmn:process id="multi_etapes" name="Approbation Multi-Étapes" isExecutable="true" camunda:historyTimeToLive="P30D">
    <bpmn:startEvent id="Start_1" name="Soumission"><bpmn:outgoing>F1</bpmn:outgoing></bpmn:startEvent>
    <bpmn:userTask id="Task_Svc" name="Validation Service" camunda:candidateGroups="agents_niveau1"><bpmn:incoming>F1</bpmn:incoming><bpmn:outgoing>F2</bpmn:outgoing></bpmn:userTask>
    <bpmn:userTask id="Task_Chef" name="Validation Chef" camunda:candidateGroups="chefs_service"><bpmn:incoming>F2</bpmn:incoming><bpmn:outgoing>F3</bpmn:outgoing></bpmn:userTask>
    <bpmn:userTask id="Task_Dir" name="Validation Directeur" camunda:candidateGroups="directeurs"><bpmn:incoming>F3</bpmn:incoming><bpmn:outgoing>F4</bpmn:outgoing></bpmn:userTask>
    <bpmn:endEvent id="End_1" name="Terminé"><bpmn:incoming>F4</bpmn:incoming></bpmn:endEvent>
    <bpmn:sequenceFlow id="F1" sourceRef="Start_1" targetRef="Task_Svc"/>
    <bpmn:sequenceFlow id="F2" sourceRef="Task_Svc" targetRef="Task_Chef"/>
    <bpmn:sequenceFlow id="F3" sourceRef="Task_Chef" targetRef="Task_Dir"/>
    <bpmn:sequenceFlow id="F4" sourceRef="Task_Dir" targetRef="End_1"/>
  </bpmn:process>
  <bpmndi:BPMNDiagram id="D1"><bpmndi:BPMNPlane id="P1" bpmnElement="multi_etapes">
    <bpmndi:BPMNShape id="S1" bpmnElement="Start_1"><dc:Bounds x="152" y="172" width="36" height="36"/></bpmndi:BPMNShape>
    <bpmndi:BPMNShape id="S2" bpmnElement="Task_Svc"><dc:Bounds x="250" y="150" width="100" height="80"/></bpmndi:BPMNShape>
    <bpmndi:BPMNShape id="S3" bpmnElement="Task_Chef"><dc:Bounds x="410" y="150" width="100" height="80"/></bpmndi:BPMNShape>
    <bpmndi:BPMNShape id="S4" bpmnElement="Task_Dir"><dc:Bounds x="570" y="150" width="100" height="80"/></bpmndi:BPMNShape>
    <bpmndi:BPMNShape id="S5" bpmnElement="End_1"><dc:Bounds x="732" y="172" width="36" height="36"/></bpmndi:BPMNShape>
    <bpmndi:BPMNEdge id="E1" bpmnElement="F1"><di:waypoint x="188" y="190"/><di:waypoint x="250" y="190"/></bpmndi:BPMNEdge>
    <bpmndi:BPMNEdge id="E2" bpmnElement="F2"><di:waypoint x="350" y="190"/><di:waypoint x="410" y="190"/></bpmndi:BPMNEdge>
    <bpmndi:BPMNEdge id="E3" bpmnElement="F3"><di:waypoint x="510" y="190"/><di:waypoint x="570" y="190"/></bpmndi:BPMNEdge>
    <bpmndi:BPMNEdge id="E4" bpmnElement="F4"><di:waypoint x="670" y="190"/><di:waypoint x="732" y="190"/></bpmndi:BPMNEdge>
  </bpmndi:BPMNPlane></bpmndi:BPMNDiagram>
</bpmn:definitions>`
};

// ════════════════════════════════════════════════════════════
//  INIT
// ════════════════════════════════════════════════════════════
function initModeler() {
    if (typeof BpmnJS === 'undefined') {
        document.getElementById('bpmn-canvas').innerHTML =
            '<div style="padding:40px;color:#f87171;text-align:center">❌ BpmnJS not loaded. Check CDN script in layout head.</div>';
        return;
    }

    modeler = new BpmnJS({
        container: '#bpmn-canvas',
        keyboard: { bindTo: window }
    });

    modeler.on('commandStack.changed', () => {
        setStatus('Modifié — non sauvegardé');
        refreshTaskSummary();
    });

modeler.on('selection.changed', ({ newSelection }) => {
    if (newSelection.length === 1) {
        const element = newSelection[0];
        const elementType = element.type;

        if (elementType === 'bpmn:UserTask') {
            closeServiceTaskPanel();
            closeGatewayPanel();
            openTaskPanel(element);
        } else if (elementType === 'bpmn:ServiceTask') {
            closeTaskPanel();
            closeGatewayPanel();
            openServiceTaskPanel(element);
        } else if (elementType === 'bpmn:ExclusiveGateway' ||
                   elementType === 'bpmn:ParallelGateway' ||
                   elementType === 'bpmn:InclusiveGateway' ||
                   elementType === 'bpmn:EventBasedGateway') {
            closeTaskPanel();
            closeServiceTaskPanel();
            openGatewayPanel(element);
        } else {
            closeTaskPanel();
            closeServiceTaskPanel();
            closeGatewayPanel();
        }
    } else {
        closeTaskPanel();
        closeServiceTaskPanel();
        closeGatewayPanel();
    }
});

    // Pre-fetch admins in background so dropdown is instant on first click
    fetchAdmins();

    importXML(DEFAULT_XML);
}

// ════════════════════════════════════════════════════════════
//  TASK PANEL OPEN / CLOSE / TABS
// ════════════════════════════════════════════════════════════
function openTaskPanel(element) {
    selectedElement = element;
    const bo = element.businessObject;

    document.getElementById('taskPanelEmpty').style.display = 'none';
    document.getElementById('taskPanelForm').style.display  = 'block';
    document.getElementById('selectedTaskName').textContent = bo.name || bo.id || 'Tâche';

    // Sanitize: treat the string "null" as empty (Camunda bug from previous deploys)
    const sanitize = v => (!v || v === 'null' || v.trim() === 'null') ? '' : v.trim();

    const assignee        = sanitize(bo.get('camunda:assignee'));
    const candidateGroups = sanitize(bo.get('camunda:candidateGroups'));
    const candidateUsers  = sanitize(bo.get('camunda:candidateUsers'));

    // If the element still has "null" string attributes, clean them right now
    if (bo.get('camunda:assignee')        === 'null') delete bo.$attrs['camunda:assignee'];
    if (bo.get('camunda:candidateGroups') === 'null') delete bo.$attrs['camunda:candidateGroups'];
    if (bo.get('camunda:candidateUsers')  === 'null') delete bo.$attrs['camunda:candidateUsers'];

    populateAssigneePicker(assignee);
    populateGroupsPicker(candidateGroups);

    tagState.users = candidateUsers ? candidateUsers.split(',').map(s => s.trim()).filter(Boolean) : [];
    renderTags('users');

    // Auto-select the right tab based on what's actually set
    if (candidateGroups)     switchAssignTab('candidateGroups');
    else if (candidateUsers) switchAssignTab('candidateUsers');
    else                     switchAssignTab('assignee');
}



function closeTaskPanel() {
    selectedElement = null;
    document.getElementById('taskPanelEmpty').style.display = 'block';
    document.getElementById('taskPanelForm').style.display  = 'none';
    clearAdminSelection();
    groupsSelectedAdmins = [];
    renderGroupsTags();
}

function switchAssignTab(tab) {
    activeAssignTab = tab;
    ['assignee', 'candidateGroups', 'candidateUsers'].forEach(t => {
        const tabEl = document.getElementById('tab-' + t);
        const panel = document.getElementById('panel-' + t);
        if (tabEl) tabEl.classList.toggle('active', t === tab);
        if (panel) panel.style.display = (t === tab) ? 'block' : 'none';
    });

    // Visual cue: dim the apply button label based on active mode
    const btn = document.querySelector('.btn-apply');
    if (btn) {
        const labels = {
            assignee:        '✓ Appliquer l\'assignee',
            candidateGroups: '✓ Appliquer les groupes',
            candidateUsers:  '✓ Appliquer les utilisateurs',
        };
        btn.textContent = labels[tab] || '✓ Appliquer l\'affectation';
    }
}

// ════════════════════════════════════════════════════════════
//  APPLY ASSIGNMENT
// ════════════════════════════════════════════════════════════
// applyTaskAssignment — writes group IDs (not emails) into camunda:candidateGroups
function applyTaskAssignment() {
    if (!selectedElement || !modeler) return;

    const modeling = modeler.get('modeling');
    const bo = selectedElement.businessObject;

    // Read active tab value
    const assignee        = (document.getElementById('input-assignee')?.value || '').trim();
    const candidateGroups = selectedCandidateGroups.map(g => g.id).join(',');
    const candidateUsers  = tagState.users.join(',');

    // Validate that the active tab has a value
    if (activeAssignTab === 'assignee' && !assignee) {
        toast('⚠️ Saisissez un assignee ou choisissez un administrateur', 'error');
        return;
    }
    if (activeAssignTab === 'candidateGroups' && !candidateGroups) {
        toast('⚠️ Sélectionnez au moins un groupe', 'error');
        return;
    }
    if (activeAssignTab === 'candidateUsers' && !candidateUsers) {
        toast('⚠️ Saisissez au moins un utilisateur candidat', 'error');
        return;
    }

    // CRITICAL: use modelerUtils to remove attributes cleanly
    // In bpmn-js, you must delete the property from the businessObject directly
    // then call updateProperties with only what you want to set.
    // Setting a key to undefined in updateProperties removes it from XML.

    const props = {};

    if (activeAssignTab === 'assignee') {
        props['camunda:assignee']        = assignee;
        // Explicitly delete the others from the business object
        delete bo.$attrs['camunda:candidateGroups'];
        delete bo.$attrs['camunda:candidateUsers'];
        // Also clear via standard properties if they exist
        if (bo.get('camunda:candidateGroups') !== undefined) props['camunda:candidateGroups'] = undefined;
        if (bo.get('camunda:candidateUsers')  !== undefined) props['camunda:candidateUsers']  = undefined;

    } else if (activeAssignTab === 'candidateGroups') {
        props['camunda:candidateGroups'] = candidateGroups;
        delete bo.$attrs['camunda:assignee'];
        delete bo.$attrs['camunda:candidateUsers'];
        if (bo.get('camunda:assignee')       !== undefined) props['camunda:assignee']       = undefined;
        if (bo.get('camunda:candidateUsers') !== undefined) props['camunda:candidateUsers'] = undefined;

    } else if (activeAssignTab === 'candidateUsers') {
        props['camunda:candidateUsers']  = candidateUsers;
        delete bo.$attrs['camunda:assignee'];
        delete bo.$attrs['camunda:candidateGroups'];
        if (bo.get('camunda:assignee')        !== undefined) props['camunda:assignee']        = undefined;
        if (bo.get('camunda:candidateGroups') !== undefined) props['camunda:candidateGroups'] = undefined;
    }

    modeling.updateProperties(selectedElement, props);

    toast(`✓ Affectation appliquée sur « ${selectedElement.businessObject.name || selectedElement.id} »`, 'success');
    refreshTaskSummary();
}
// ════════════════════════════════════════════════════════════
//  TASK SUMMARY
// ════════════════════════════════════════════════════════════
function focusTask(elementId) {
    const registry  = modeler.get('elementRegistry');
    const selection = modeler.get('selection');
    const canvas    = modeler.get('canvas');
    const el        = registry.get(elementId);
    if (el) { selection.select(el); canvas.scrollToElement(el); }
}


function refreshTaskSummary() {
    if (!modeler) return;
    const userTasks = modeler.get('elementRegistry').filter(el => el.type === 'bpmn:UserTask');
    const card = document.getElementById('taskSummaryCard');
    const list = document.getElementById('taskSummaryList');

    if (!userTasks.length) { if (card) card.style.display = 'none'; return; }
    if (card) card.style.display = 'block';

    list.innerHTML = userTasks.map(el => {
        const bo = el.businessObject;
        const a  = bo.get('camunda:assignee')        || '';
        const g  = bo.get('camunda:candidateGroups') || '';
        const u  = bo.get('camunda:candidateUsers')  || '';
        const hasAss = !!(a || g || u);

        let aDisplay = a;
        if (a && !a.includes('$')) {
            const match = allAdmins.find(adm => adm.camundaId === a || adm.email === a);
            if (match) aDisplay = match.fullName;
        }

        // For groups, resolve emails to names
        let gDisplay = g;
        if (g) {
            gDisplay = g.split(',').map(email => {
                const match = allAdmins.find(adm => adm.email === email.trim() || adm.camundaId === email.trim());
                return match ? match.fullName : email.trim();
            }).join(', ');
        }

        let label = '—';
        if (a) label = `assignee: <span>${escHtml(aDisplay)}</span>`;
        else if (g) label = `groupes: <span>${escHtml(gDisplay)}</span>`;
        else if (u) label = `users: <span>${escHtml(u)}</span>`;

        return `<div class="task-summary-item ${hasAss ? 'has-assignment' : ''}" onclick="focusTask('${escHtml(el.id)}')">
                  <div class="task-dot ${hasAss ? 'assigned' : ''}"></div>
                  <div style="min-width:0;">
                    <div class="task-summary-name">${escHtml(bo.name || bo.id)}</div>
                    <div class="task-summary-assign">${label}</div>
                  </div>
                </div>`;
    }).join('');
}
// ════════════════════════════════════════════════════════════
//  TAG INPUT (candidateGroups / candidateUsers)
// ════════════════════════════════════════════════════════════

function handleTagKey(event, type) {
    if (event.key === 'Enter' || event.key === ',') {
        event.preventDefault();
        const val = event.target.value.replace(/,/g, '').trim();
        if (val && !tagState[type].includes(val)) {
            tagState[type].push(val);
            renderTags(type);
        }
        event.target.value = '';
    } else if (event.key === 'Backspace' && event.target.value === '') {
        tagState[type].pop();
        renderTags(type);
    }
}


function removeTag(type, index) {
    tagState[type].splice(index, 1);
    renderTags(type);
}

function renderTags(type) {
    const wrapId  = 'usersTagWrap';
    const inputId = 'usersRawInput';
    const wrap    = document.getElementById(wrapId);
    const rawInput = document.getElementById(inputId);
    if (!wrap || !rawInput) return;
    wrap.querySelectorAll('.tag').forEach(t => t.remove());
    tagState[type].forEach((val, i) => {
        const tag = document.createElement('span');
        tag.className = 'tag';
        tag.innerHTML = `${escHtml(val)}<button class="tag-remove" onclick="removeTag('${type}',${i})">×</button>`;
        wrap.insertBefore(tag, rawInput);
    });
}


// ════════════════════════════════════════════════════════════
//  XML IMPORT / EXPORT
// ════════════════════════════════════════════════════════════

function importXML(xml) {
    if (!modeler) return;
    const result = modeler.importXML(xml);
    if (result && typeof result.then === 'function') {
        result.then(() => {
            modeler.get('canvas').zoom('fit-viewport');
            currentXML = xml;
            setStatus('Prêt');
            refreshTaskSummary();
        }).catch(err => {
            console.error(err);
            toast('Erreur import: ' + err.message, 'error');
        });
    } else {
        modeler.get('canvas').zoom('fit-viewport');
        currentXML = xml;
        setStatus('Prêt');
        refreshTaskSummary();
    }
}

function loadTemplate(name) {
    if (!TEMPLATES[name]) { toast('Template introuvable', 'error'); return; }
    if (confirm('Charger ce template? Les modifications non sauvegardées seront perdues.')) {
        importXML(TEMPLATES[name]);
        toast('Template chargé', 'success');
    }
}

function newDiagram() {
    if (!confirm('Créer un nouveau diagramme ? Les modifications non sauvegardées seront perdues.')) return;

    const processId = prompt("Entrez le nouvel ID du processus (ex: carte_pro, pret_fnap):", "nouveau_processus");
    if (!processId) return;

    document.getElementById('processId').value = processId;
    document.getElementById('processName').value = "Nom du " + processId;
    document.getElementById('processDescription').value = "";

    // Reset to default template with new ID
    let newXml = DEFAULT_XML;
    newXml = updateProcessIdInXML(newXml, processId, "Nom du " + processId);

    importXML(newXml);
    toast('Nouveau diagramme créé', 'success');
}

async function saveDiagram() {
    if (!modeler) return;
    try {
        const { xml } = await modeler.saveXML({ format: true });
        const pid = document.getElementById('processId').value || 'draft';
        localStorage.setItem('bpmn_draft_' + pid, JSON.stringify({
            xml,
            processId:   pid,
            processName: document.getElementById('processName').value,
            description: document.getElementById('processDescription').value,
            savedAt:     new Date().toISOString()
        }));
        currentXML = xml;
        setStatus('Sauvegardé localement ✓');
        toast('Diagramme sauvegardé localement', 'success');
    } catch (err) { toast('Erreur: ' + err.message, 'error'); }
}

async function downloadDiagram() {
    if (!modeler) return;
    try {
        const { xml }  = await modeler.saveXML({ format: true });
        const fname    = (document.getElementById('processId').value || 'workflow') + '.bpmn';
        const a        = Object.assign(document.createElement('a'), {
            href:     URL.createObjectURL(new Blob([xml], { type: 'application/xml' })),
            download: fname
        });
        a.click();
        toast('Fichier téléchargé', 'success');
    } catch (err) { toast('Erreur: ' + err.message, 'error'); }
}

function openXML() {
    const input = Object.assign(document.createElement('input'), { type: 'file', accept: '.bpmn,.xml' });
    input.onchange = e => {
        const file = e.target.files[0];
        if (!file) return;
        const r = new FileReader();
        r.onload = ev => { importXML(ev.target.result); toast('Chargé: ' + file.name, 'success'); };
        r.readAsText(file);
    };
    input.click();
}

async function validateDiagram() {
    if (!modeler) return;

    try {
        await modeler.saveXML({ format: true });

        // === 1. Validate User Tasks assignment ===
        const unassigned = modeler.get('elementRegistry')
            .filter(el => el.type === 'bpmn:UserTask')
            .filter(el => {
                const bo = el.businessObject;
                return !bo.get('camunda:assignee') &&
                       !bo.get('camunda:candidateGroups') &&
                       !bo.get('camunda:candidateUsers');
            });

        // === 2. Validate Gateways ===
        const gateways = modeler.get('elementRegistry').filter(el =>
            el.type === 'bpmn:ExclusiveGateway' ||
            el.type === 'bpmn:ParallelGateway' ||
            el.type === 'bpmn:InclusiveGateway'
        );

        const invalidGateways = [];

        gateways.forEach(gw => {
            const bo = gw.businessObject;
            const outgoing = bo.outgoing || [];
            const isExclusive = gw.type === 'bpmn:ExclusiveGateway';

            if (isExclusive && outgoing.length > 1) {
                const missingConditions = [];

                outgoing.forEach(flow => {
                    const flowBo = flow.businessObject;
                    const isDefault = flowBo.get('camunda:default') === flow.id;

                    if (!isDefault && !flowBo.conditionExpression) {
                        missingConditions.push(flow.name || flow.id);
                    }
                });

                if (missingConditions.length > 0) {
                    invalidGateways.push({
                        name: bo.name || gw.id,
                        issue: `Flux sans condition: ${missingConditions.join(', ')}`
                    });
                }

                // Check for default flow
                const hasDefault = outgoing.some(flow =>
                    flow.businessObject.get('camunda:default') === flow.id
                );

                if (!hasDefault) {
                    invalidGateways.push({
                        name: bo.name || gw.id,
                        issue: 'Aucun flux par défaut défini'
                    });
                }
            }
        });

        // === 3. Build messages ===
        let warnings = [];
        let errors = [];

        if (unassigned.length) {
            const names = unassigned.map(el => el.businessObject.name || el.id).join(', ');
            warnings.push(`${unassigned.length} tâche(s) sans affectation: ${names}`);
        }

        if (invalidGateways.length) {
            invalidGateways.forEach(g => {
                errors.push(`Passerelle "${g.name}": ${g.issue}`);
            });
        }

        // === 4. Show appropriate toast ===
        if (errors.length) {
            toast(`❌ ${errors.length} erreur(s) de passerelle:\n${errors.join('\n')}`, 'error');
        } else if (warnings.length) {
            toast(`⚠️ ${warnings.join(', ')}`, 'error');
        } else {
            toast('✅ Diagramme valide !', 'success');
        }

    } catch (err) {
        toast('❌ ' + err.message, 'error');
    }
}

// ════════════════════════════════════════════════════════════
//  DEPLOY
// ════════════════════════════════════════════════════════════

async function deployToCamunda() {
    if (!modeler) return;
       // ════════════════════════════════════════════════════════════
    // VALIDATE ALL SERVICE TASKS HAVE TOPICS
    // ════════════════════════════════════════════════════════════
    if (!validateServiceTasksBeforeDeploy()) {
        return; // Stop deployment if validation fails
    }

    let processId   = document.getElementById('processId').value.trim();
    let processName = document.getElementById('processName').value.trim() || processId;
    const description = document.getElementById('processDescription').value.trim();
    const ttl         = document.getElementById('processTTL').value || 'P30D';

    if (!processId) {
        toast('❌ Veuillez saisir un ID de processus', 'error');
        return;
    }

    setDeployStatus('loading', '⏳ Déploiement en cours…');

    try {
        let { xml } = await modeler.saveXML({ format: true });
        xml = updateProcessIdInXML(xml, processId, processName, ttl);

        const fd = new FormData();
        fd.append('file', new Blob([xml], { type: 'text/xml' }), processId + '.bpmn');
        fd.append('processId', processId);
        fd.append('processName', processName);
        fd.append('description', description);

        const res = await fetch('{{ route("admin.workflows.deploy") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: fd
        });

        const result = await res.json();

        if (result.success) {
            setDeployStatus('success', `✅ Déployé avec succès !\nProcess Key: ${result.processDefinitionKey}`);

            // === SAUVEGARDE DES ASSIGNATIONS ===
            await saveTaskAssignmentsToDatabase(processId, xml);

            // Sauvegarde générale du workflow
            const dbResult = await saveToDatabase(processId, processName, description, xml, result.deploymentId);

            if (dbResult?.success) {
                toast(`🚀 Workflow "${processName}" déployé et assignations enregistrées`, 'success');
            }
        } else {
            setDeployStatus('error', '❌ ' + (result.error || 'Échec du déploiement'));
        }
    } catch (err) {
        console.error(err);
        setDeployStatus('error', '❌ Erreur: ' + err.message);
    }
}

async function saveTaskAssignmentsToDatabase(processKey, xml) {
    try {
        const parser = new DOMParser();
        const doc = parser.parseFromString(xml, "application/xml");

        const assignments = [];     // For User Tasks
        const serviceConfigs = [];  // For Service Tasks

        // ==================== USER TASKS ====================
        const userTasks = doc.querySelectorAll("bpmn\\:userTask, userTask");
        userTasks.forEach(task => {
            const taskId   = task.getAttribute("id");
            const taskName = task.getAttribute("name") || taskId;
            const assignee = task.getAttribute("camunda:assignee");
            const candidateGroups = task.getAttribute("camunda:candidateGroups");

            if (assignee && !assignee.includes('${')) {
                assignments.push({
                    task_id: taskId,
                    task_name: taskName,
                    process_key: processKey,
                    admin_user_id: assignee,
                    assignment_type: 'assignee',
                    assigned_at: new Date().toISOString()
                });
            } else if (candidateGroups) {
                assignments.push({
                    task_id: taskId,
                    task_name: taskName,
                    process_key: processKey,
                    admin_user_id: candidateGroups,
                    assignment_type: 'candidateGroups',
                    assigned_at: new Date().toISOString()
                });
            }
        });

        // ==================== SERVICE TASKS ====================
        const serviceTasks = doc.querySelectorAll("bpmn\\:serviceTask, serviceTask");

        serviceTasks.forEach(task => {
            const taskId   = task.getAttribute("id");
            const taskName = task.getAttribute("name") || taskId;
            const type     = task.getAttribute("camunda:type");
            const topic    = task.getAttribute("camunda:topic");
            const className = task.getAttribute("camunda:class");
            const connectorId = task.getAttribute("camunda:connectorId"); // if using connector

            if (type || topic || className) {
                serviceConfigs.push({
                    task_id: taskId,
                    task_name: taskName,
                    process_key: processKey,
                    implementation_type: type || (topic ? 'external' : (className ? 'class' : 'unknown')),
                    topic: topic || null,
                    java_class: className || null,
                    connector_id: connectorId || null,
                    configured_at: new Date().toISOString()
                });

                // Optional: Console feedback (useful during development)
                if (type === 'external' && topic) {
                    console.log(`✅ Service Task "${taskName}" → External Worker | Topic: ${topic}`);
                } else if (className) {
                    console.log(`✅ Service Task "${taskName}" → Java Class: ${className}`);
                }
            }
        });

        // ==================== SAVE TO DATABASE ====================
        if (assignments.length === 0 && serviceConfigs.length === 0) {
            console.log("ℹ️ No assignments or service tasks to save.");
            return;
        }

        const payload = {
            process_key: processKey,
            assignments: assignments,
            service_tasks: serviceConfigs   // ← New field
        };

        await fetch('{{ route("admin.api.workflows.task-assignments.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(payload)
        });

        console.log(`💾 Saved ${assignments.length} user task assignments + ${serviceConfigs.length} service task configs`);

    } catch (e) {
        console.warn('⚠️ saveTaskAssignmentsToDatabase failed (non-blocking):', e);
    }
}
/**
 * Updates the process id, name and historyTimeToLive in the BPMN XML
 */
function updateProcessIdInXML(xml, newProcessId, newProcessName, ttl = 'P30D') {
    // Replace process id
    xml = xml.replace(
        /<bpmn:process[^>]*id="[^"]*"/i,
        `<bpmn:process id="${newProcessId}"`
    );

    // Replace process name if present
    xml = xml.replace(
        /<bpmn:process[^>]*name="[^"]*"/i,
        (match) => {
            return match.replace(/name="[^"]*"/i, `name="${newProcessName}"`);
        }
    );

    // Add or update historyTimeToLive
    if (xml.includes('historyTimeToLive')) {
        xml = xml.replace(
            /camunda:historyTimeToLive="[^"]*"/i,
            `camunda:historyTimeToLive="${ttl}"`
        );
    } else {
        xml = xml.replace(
            /<bpmn:process([^>]*)>/i,
            `<bpmn:process$1 camunda:historyTimeToLive="${ttl}">`
        );
    }

    return xml;
}

async function saveToDatabase(processId, processName, description, xml, deploymentId) {
    try {
        const res = await fetch('{{ route("admin.workflows.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                process_id:    processId,
                name:          processName,
                description,
                bpmn_xml:      xml,
                deployment_id: deploymentId,
                version:       '1.0'
            })
        });
        return await res.json();
    } catch (err) {
        console.warn('DB save failed (non-critical):', err.message);
        return null;
    }
}

// ════════════════════════════════════════════════════════════
//  UTILITIES
// ════════════════════════════════════════════════════════════

function zoomFit()    { modeler?.get('canvas').zoom('fit-viewport'); }
function zoomIn()     { const c = modeler?.get('canvas'); if (c) c.zoom(c.zoom() * 1.2); }
function zoomOut()    { const c = modeler?.get('canvas'); if (c) c.zoom(c.zoom() * 0.8); }
function undoAction() { modeler?.get('commandStack').undo(); }
function redoAction() { modeler?.get('commandStack').redo(); }

function setStatus(msg) {
    const el = document.getElementById('diagramStatus');
    if (el) el.textContent = msg;
}

function setDeployStatus(type, msg) {
    const el = document.getElementById('deployStatus');
    if (el) {
        el.className = 'deploy-status show ' + type;
        el.style.whiteSpace = 'pre-line';
        el.textContent = msg;
        if (type === 'success') setTimeout(() => el.classList.remove('show'), 8000);
    }
}

function toast(msg, type = 'info') {
    const t = document.createElement('div');
    t.className = 'bpmn-toast ' + type;
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 3500);
}

function escHtml(s) {
    return String(s || '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

document.addEventListener('DOMContentLoaded', () => {
    initModeler();
});


let allCamundaGroupsCache = [];      // cache so we don't re-fetch on every keystroke
let groupsCacheFetched    = false;

async function fetchCamundaGroups() {
    if (groupsCacheFetched) return allCamundaGroupsCache;
    try {
        const res = await fetch('/api/workflows/groups', {
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        });
        const result = await res.json();
        allCamundaGroupsCache = result.success ? (result.groups || []) : [];
        groupsCacheFetched    = true;
    } catch (e) {
        console.error('Failed to fetch Camunda groups:', e);
        allCamundaGroupsCache = [];
    }
    return allCamundaGroupsCache;
}

// ════════════════════════════════════════════════════════════
//  SERVICE TASK HANDLING
// ════════════════════════════════════════════════════════════

let selectedServiceTaskElement = null;

function openServiceTaskPanel(element) {
    document.getElementById('serviceTaskPanel').style.display = 'block';
    selectedServiceTaskElement = element;
    const bo = element.businessObject;

    document.getElementById('serviceTaskEmpty').style.display = 'none';
    document.getElementById('serviceTaskForm').style.display = 'block';
    document.getElementById('selectedServiceTaskName').textContent = bo.name || bo.id || 'Service Task';

    const implementationType = bo.get('camunda:type') || bo.$attrs?.['camunda:type'] || '';
    const topic = bo.get('camunda:topic') || bo.$attrs?.['camunda:topic'] || '';
    const connectorId = bo.get('camunda:connectorId') || bo.$attrs?.['camunda:connectorId'] || '';
    const javaClass = bo.get('camunda:class') || bo.$attrs?.['camunda:class'] || '';
    const priority = bo.get('camunda:taskPriority') || bo.$attrs?.['camunda:taskPriority'] || '50';
    const retryTimeout = bo.get('camunda:retryTimeout') || bo.$attrs?.['camunda:retryTimeout'] || '5000';

    // === NEW: Get saved PDF Template ===
    const savedPdfTemplate = bo.get('camunda:pdfTemplate') ||
                            bo.$attrs?.['camunda:pdfTemplate'] || '';

    // Set basic fields
    document.getElementById('serviceTaskType').value = implementationType;
    onServiceTaskTypeChange(implementationType);

    // Set topic in both manual input and dropdown
    if (topic) {
        document.getElementById('serviceTaskTopic').value = topic;
        const select = document.getElementById('serviceTaskTopicSelect');
        if (select) {
            let found = false;
            for (let i = 0; i < select.options.length; i++) {
                if (select.options[i].value === topic) {
                    select.value = topic;
                    found = true;
                    break;
                }
            }
            if (!found) select.value = '';
        }
    } else {
        document.getElementById('serviceTaskTopic').value = '';
        const select = document.getElementById('serviceTaskTopicSelect');
        if (select) select.value = '';
    }

    if (connectorId) document.getElementById('serviceTaskConnectorId').value = connectorId;
    if (javaClass) document.getElementById('serviceTaskClass').value = javaClass;
    document.getElementById('serviceTaskPriority').value = priority;
    document.getElementById('serviceTaskRetryTimeout').value = retryTimeout;

    // === FIX: Restore PDF Template Selection ===
    if (topic === 'generate-attestation' || savedPdfTemplate) {
        const pdfGroup = document.getElementById('pdfTemplateGroup');
        if (pdfGroup) pdfGroup.style.display = 'block';

        loadPdfTemplatesForSelect().then(() => {
            const sel = document.getElementById('pdfTemplateKey');
            if (sel && savedPdfTemplate) {
                sel.value = savedPdfTemplate;
            }
        });
    }

}
// ════════════════════════════════════════════════════════════
//  QUICK ADD ATTESTATION SERVICE TASK
// ════════════════════════════════════════════════════════════

function addAttestationServiceTask() {
    if (!modeler) {
        toast('❌ Modeler non initialisé', 'error');
        return;
    }

    const elementFactory = modeler.get('elementFactory');
    const canvas = modeler.get('canvas');
    const modeling = modeler.get('modeling');

    // Get center of current viewport
    const viewport = canvas.viewbox();
    const centerX = viewport.x + viewport.width / 2;
    const centerY = viewport.y + viewport.height / 2;

    // Create a new service task at center position
    const newTask = elementFactory.createShape({
        type: 'bpmn:ServiceTask',
        x: centerX - 60,
        y: centerY - 40,
        width: 120,
        height: 80
    });

    // Add the shape to the canvas
    modeling.createShape(newTask, { x: centerX - 60, y: centerY - 40 }, canvas.getRootElement());

    // Apply attestation configuration
    setTimeout(() => {
        const elementRegistry = modeler.get('elementRegistry');
        // Find the newly created service task (it will be the one with the generated ID)
        const serviceTasks = elementRegistry.filter(el => el.type === 'bpmn:ServiceTask');
        const newServiceTask = serviceTasks[serviceTasks.length - 1];

        if (newServiceTask) {
            modeling.updateProperties(newServiceTask, {
                name: 'Générer Attestation',
                'camunda:type': 'external',
                'camunda:topic': 'generate-attestation',
                'camunda:taskPriority': '50',
                'camunda:retryTimeout': '5000'
            });

            // Also update the business object attributes directly to ensure they're saved
            const bo = newServiceTask.businessObject;
            bo.$attrs['camunda:type'] = 'external';
            bo.$attrs['camunda:topic'] = 'generate-attestation';

            toast('✅ Service Task "Générer Attestation" ajoutée et configurée', 'success');

            // Auto-select the new task to show its configuration
            setTimeout(() => {
                const selection = modeler.get('selection');
                selection.select(newServiceTask);
                canvas.scrollToElement(newServiceTask);
            }, 100);
        }
    }, 50);
}
function closeServiceTaskPanel() {
    document.getElementById('serviceTaskPanel').style.display = 'none'; // ← this
    selectedServiceTaskElement = null;
    document.getElementById('serviceTaskEmpty').style.display = 'block';
    document.getElementById('serviceTaskForm').style.display = 'none';

    // Reset form
    document.getElementById('serviceTaskType').value = '';
    document.getElementById('serviceTaskTopic').value = '';
    document.getElementById('serviceTaskConnectorId').value = '';
    document.getElementById('serviceTaskClass').value = '';
    document.getElementById('serviceTaskPriority').value = '50';
    document.getElementById('serviceTaskRetryTimeout').value = '5000';

    document.getElementById('externalTopicGroup').style.display = 'none';
    document.getElementById('connectorGroup').style.display = 'none';
    document.getElementById('classGroup').style.display = 'none';
}

function onServiceTaskTypeChange(type) {
    document.getElementById('externalTopicGroup').style.display = type === 'external' ? 'block' : 'none';
    document.getElementById('connectorGroup').style.display = type === 'connector' ? 'block' : 'none';
    document.getElementById('classGroup').style.display = type === 'class' ? 'block' : 'none';

    // Afficher le sélecteur de template PDF si le topic est generate-attestation
    const topic = document.getElementById('serviceTaskTopic').value;
    const pdfGroup = document.getElementById('pdfTemplateGroup');
    if (pdfGroup) {
        pdfGroup.style.display = (type === 'external' && topic === 'generate-attestation') ? 'block' : 'none';
    }
}


function applyServiceTaskConfig() {
    if (!selectedServiceTaskElement || !modeler) return;

    const modeling = modeler.get('modeling');
    const bo = selectedServiceTaskElement.businessObject;
    const type = document.getElementById('serviceTaskType').value;

    if (!type) {
        toast('⚠️ Veuillez sélectionner un type d\'implémentation', 'error');
        return;
    }

    const props = {
        'camunda:type': type
    };

    // Clear previous implementation-specific attributes
    delete bo.$attrs['camunda:topic'];
    delete bo.$attrs['camunda:connectorId'];
    delete bo.$attrs['camunda:class'];
    delete bo.$attrs['camunda:taskPriority'];
    delete bo.$attrs['camunda:retryTimeout'];
    delete bo.$attrs['camunda:pdfTemplate']; // Nouvel attribut

    // Set type-specific properties
    if (type === 'external') {
        const topic = document.getElementById('serviceTaskTopic').value.trim();
        if (!topic) {
            toast('⚠️ Le topic est obligatoire pour une tâche externe', 'error');
            return;
        }
        props['camunda:topic'] = topic;

        // Si c'est generate-attestation, enregistrer le template PDF sélectionné
        if (topic === 'generate-attestation') {
            const pdfTemplateKey = document.getElementById('pdfTemplateKey')?.value;
            if (pdfTemplateKey) {
                props['camunda:pdfTemplate'] = pdfTemplateKey;
                toast(`📄 Template PDF "${pdfTemplateKey}" associé à la tâche`, 'success');
            } else {
                toast('⚠️ Veuillez sélectionner un template PDF', 'warning');
            }
        }
    } else if (type === 'connector') {
        const connectorId = document.getElementById('serviceTaskConnectorId').value.trim();
        if (connectorId) props['camunda:connectorId'] = connectorId;
    } else if (type === 'class') {
        const javaClass = document.getElementById('serviceTaskClass').value.trim();
        if (javaClass) props['camunda:class'] = javaClass;
    }


    const priority = document.getElementById('serviceTaskPriority').value;
    if (priority && priority !== '50') {
        props['camunda:taskPriority'] = priority;
    }

    const retryTimeout = document.getElementById('serviceTaskRetryTimeout').value;
    if (retryTimeout && retryTimeout !== '5000') {
        props['camunda:retryTimeout'] = retryTimeout;
    }

    modeling.updateProperties(selectedServiceTaskElement, props);

    toast(`✓ Service Task "${bo.name || selectedServiceTaskElement.id}" configurée`, 'success');
}
function loadServiceTaskTemplate(type) {
    if (!modeler) return;

    let taskConfig = {};

    if (type === 'attestation') {
        taskConfig = {
            name: 'Générer Attestation',
            type: 'external',
            topic: 'generate-attestation',
            retryTimeout: '5000'
        };
    } else if (type === 'email') {
        taskConfig = {
            name: 'Envoyer Notification Email',
            type: 'external',
            topic: 'send-email-notification',
            retryTimeout: '5000'
        };
    }

    // Add a new service task at a default position
    const elementFactory = modeler.get('elementFactory');
    const canvas = modeler.get('canvas');
    const modeling = modeler.get('modeling');

    const viewport = canvas.viewbox();
    const centerX = viewport.x + viewport.width / 2;
    const centerY = viewport.y + viewport.height / 2;

    const newTask = elementFactory.createShape({
        type: 'bpmn:ServiceTask',
        x: centerX - 50,
        y: centerY - 40,
        width: 100,
        height: 80
    });

    modeling.createShape(newTask, { x: centerX - 50, y: centerY - 40 }, canvas.getRootElement());

    // Apply configuration
    setTimeout(() => {
        const registry = modeler.get('elementRegistry');
        const taskElement = registry.find(el => el.type === 'bpmn:ServiceTask' && el.id !== newTask.id);
        if (taskElement) {
            modeling.updateProperties(taskElement, {
                name: taskConfig.name,
                'camunda:type': taskConfig.type,
                'camunda:topic': taskConfig.topic,
                'camunda:retryTimeout': taskConfig.retryTimeout
            });
            toast(`✓ Tâche "${taskConfig.name}" ajoutée et configurée`, 'success');
        }
    }, 100);
}
function validateServiceTasksBeforeDeploy() {
    if (!modeler) return true;

    const serviceTasks = modeler.get('elementRegistry').filter(el => el.type === 'bpmn:ServiceTask');
    const missingTopics = [];

    serviceTasks.forEach(task => {
        const bo = task.businessObject;
        const type = bo.get('camunda:type');

        // If it's an external task, it MUST have a topic
        if (type === 'external') {
            const topic = bo.get('camunda:topic');
            if (!topic) {
                missingTopics.push({
                    id: task.id,
                    name: bo.name || task.id
                });
            }
        }
    });

    if (missingTopics.length > 0) {
        const names = missingTopics.map(t => `"${t.name}"`).join(', ');
        toast(`❌ ${missingTopics.length} Service Task(s) externe(s) sans topic: ${names}. Veuillez configurer un topic.`, 'error');
        return false;
    }

    return true;
}
// ════════════════════════════════════════════════════════════
//  LOAD DEPLOYED WORKFLOWS FROM CAMUNDA
// ════════════════════════════════════════════════════════════

let currentWorkflowKey = null;

/**
 * Fetch and populate the dropdown with deployed workflows
 */
async function refreshWorkflowList() {
    const select = document.getElementById('loadWorkflowSelect');
    if (!select) return;

    select.innerHTML = '<option value="">⏳ Chargement des workflows...</option>';

    try {
        const response = await fetch('/api/workflows/deployed', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        const workflows = await response.json();

        if (!workflows.length) {
            select.innerHTML = '<option value="">📋 Aucun workflow déployé</option>';
            return;
        }

        let options = '<option value="">📋 Sélectionner un workflow...</option>';

        workflows.forEach(wf => {
            const status = wf.active ? '✅' : '⭕';
            options += `<option value="${wf.id}" data-process-key="${wf.process_key}" data-name="${wf.name}" data-version="${wf.version}">
                ${status} ${wf.name} (${wf.process_key}) v${wf.version}
            </option>`;
        });

        select.innerHTML = options;

        // Restore previously selected if any
        if (currentWorkflowKey) {
            for (let i = 0; i < select.options.length; i++) {
                const opt = select.options[i];
                if (opt.getAttribute('data-process-key') === currentWorkflowKey) {
                    select.value = opt.value;
                    break;
                }
            }
        }

    } catch (err) {
        console.error('Error loading workflows:', err);
        select.innerHTML = '<option value="">❌ Erreur de chargement</option>';
        toast('❌ Impossible de charger les workflows déployés', 'error');
    }
}

/**
 * Load a deployed workflow's BPMN XML from Camunda
 */
async function loadDeployedWorkflow(workflowId) {
    if (!workflowId) return;

    const select = document.getElementById('loadWorkflowSelect');
    const selectedOption = select.options[select.selectedIndex];
    const processKey = selectedOption?.getAttribute('data-process-key');
    const workflowName = selectedOption?.getAttribute('data-name');

    if (!processKey) {
        toast('⚠️ Impossible de récupérer la clé du processus', 'error');
        select.value = '';
        return;
    }

    setDeployStatus('loading', `⏳ Chargement du workflow "${workflowName}"...`);

    try {
        // Use Laravel API endpoint to get the BPMN XML
        const response = await fetch(`/api/workflows/${processKey}/bpmn-xml`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        const data = await response.json();
        const bpmnXml = data.bpmn20Xml;

        if (!bpmnXml) {
            throw new Error('No BPMN XML found in response');
        }

        // Update UI with loaded workflow info
        document.getElementById('processId').value = processKey;
        document.getElementById('processName').value = workflowName || processKey;

        // Import the XML into the modeler
        importXML(bpmnXml);

        currentWorkflowKey = processKey;

        setDeployStatus('success', `✅ Workflow "${workflowName}" chargé avec succès !`);
        toast(`✅ Workflow "${workflowName}" chargé`, 'success');

    } catch (err) {
        console.error('Error loading workflow:', err);
        setDeployStatus('error', `❌ Erreur: ${err.message}`);
        toast(`❌ Erreur lors du chargement: ${err.message}`, 'error');
        select.value = '';
    }
}


/**
 * Delete the currently selected workflow from Camunda
 */
async function deleteSelectedWorkflow() {
    const select = document.getElementById('loadWorkflowSelect');
    const selectedOption = select.options[select.selectedIndex];
    const processKey = selectedOption?.getAttribute('data-process-key');
    const workflowName = selectedOption?.getAttribute('data-name');
    const workflowId = select.value;

    if (!workflowId || !processKey) {
        toast('⚠️ Veuillez sélectionner un workflow à supprimer', 'error');
        return;
    }

    if (!confirm(`⚠️ ATTENTION: Voulez-vous vraiment supprimer le workflow "${workflowName}" (${processKey})?\n\nCette action est irréversible et supprimera toutes les instances de ce workflow dans Camunda.`)) {
        return;
    }

    setDeployStatus('loading', `⏳ Suppression du workflow "${workflowName}"...`);

    try {
        // Use Laravel API endpoint to delete the workflow
        const response = await fetch(`/api/workflows/${processKey}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json'
            }
        });

        const result = await response.json();

        if (result.success) {
            setDeployStatus('success', `✅ Workflow "${workflowName}" supprimé`);
            toast(`✅ Workflow "${workflowName}" supprimé avec succès`, 'success');

            // Refresh the dropdown
            await refreshWorkflowList();

            // Clear current workflow from UI if it was the one deleted
            if (currentWorkflowKey === processKey) {
                document.getElementById('processId').value = '';
                document.getElementById('processName').value = '';
                currentWorkflowKey = null;
            }
        } else {
            throw new Error(result.error || 'Erreur lors de la suppression');
        }

    } catch (err) {
        console.error('Error deleting workflow:', err);
        setDeployStatus('error', `❌ Erreur: ${err.message}`);
        toast(`❌ Erreur lors de la suppression: ${err.message}`, 'error');
    }
}



// Auto-refresh workflow list when modeler is ready
setTimeout(() => {
    refreshWorkflowList();
}, 1000);

// ════════════════════════════════════════════════════════════
//  GATEWAY HANDLING
// ════════════════════════════════════════════════════════════

let selectedGatewayElement = null;
let outgoingFlows = [];

function openGatewayPanel(element) {
    document.getElementById('gatewayPanel').style.display = 'block';
    selectedGatewayElement = element;
    const bo = element.businessObject;

    document.getElementById('gatewayEmpty').style.display = 'none';
    document.getElementById('gatewayForm').style.display = 'block';
    document.getElementById('selectedGatewayName').textContent = bo.name || bo.id || 'Passerelle';

    // Get current gateway type
    const gatewayType = bo.$attrs['camunda:type'] || 'exclusive';
    document.getElementById('gatewayType').value = gatewayType.toLowerCase();

    // Load outgoing flows
    loadOutgoingFlows();
}

function closeGatewayPanel() {
    document.getElementById('gatewayPanel').style.display = 'none';
    selectedGatewayElement = null;
    document.getElementById('gatewayEmpty').style.display = 'block';
    document.getElementById('gatewayForm').style.display = 'none';
    outgoingFlows = [];
}

function loadOutgoingFlows() {
    const container = document.getElementById('outgoingFlowsList');
    if (!selectedGatewayElement) return;

    const bo = selectedGatewayElement.businessObject;
    const elementRegistry = modeler.get('elementRegistry');

    // Get all sequence flows that start from this gateway
    const allFlows = elementRegistry.filter(el => el.type === 'bpmn:SequenceFlow');
    const outgoing = allFlows.filter(flow => flow.businessObject.sourceRef === bo);

    outgoingFlows = [];

    if (outgoing.length === 0) {
        container.innerHTML = '<div style="text-align:center;padding:20px;color:var(--text3);">⚠️ Aucun flux de sortie connecté à cette passerelle.<br><small>Assurez-vous d\'avoir connecté la passerelle à des tâches ou événements.</small></div>';
        document.getElementById('defaultFlowSelect').innerHTML = '<option value="">— Aucune (pas de flux par défaut) —</option>';
        return;
    }

    // Get current default flow
    const defaultFlowId = bo.get('camunda:default');

    // Build default flow dropdown
    let defaultOptions = '<option value="">— Aucune (pas de flux par défaut) —</option>';
    outgoing.forEach(flow => {
        const flowBo = flow.businessObject;
        const flowName = flowBo.name || flow.id;
        defaultOptions += `<option value="${flow.id}" ${defaultFlowId === flow.id ? 'selected' : ''}>${escapeHtml(flowName)}</option>`;
    });
    document.getElementById('defaultFlowSelect').innerHTML = defaultOptions;

    // Predefined condition options
    const conditionOptions = [
        { value: '', label: '— Aucune condition (flux par défaut) —' },
        { value: '${approved == true}', label: '✅ Approuvé (${approved == true})' },
        { value: '${approved == false}', label: '❌ Rejeté (${approved == false})' },
        { value: '${decision == "approved"}', label: '✅ Décision: Approuvé (${decision == "approved"})' },
        { value: '${decision == "rejected"}', label: '❌ Décision: Rejeté (${decision == "rejected"})' },
        { value: '${amount > 1000}', label: '💰 Montant > 1000 (${amount > 1000})' },
        { value: '${amount <= 1000}', label: '💰 Montant ≤ 1000 (${amount <= 1000})' },
        { value: '${status == "pending"}', label: '⏳ Statut: En attente (${status == "pending"})' },
        { value: '${status == "approved"}', label: '✅ Statut: Approuvé (${status == "approved"})' },
        { value: '${status == "rejected"}', label: '❌ Statut: Rejeté (${status == "rejected"})' },
    ];

    // Build conditions list
    let html = '';

    for (const flow of outgoing) {
        const flowBo = flow.businessObject;
        const flowId = flow.id;
        const flowName = flowBo.name || flow.id;

        // Get existing condition expression
        let conditionExpression = '';
        if (flowBo.conditionExpression) {
            conditionExpression = flowBo.conditionExpression.body || '';
        }

        // Build options HTML with current value selected
        let optionsHtml = '';
        for (const opt of conditionOptions) {
            const selected = (conditionExpression === opt.value) ? 'selected' : '';
            optionsHtml += `<option value="${escapeHtml(opt.value)}" ${selected}>${escapeHtml(opt.label)}</option>`;
        }

        html += `
        <div style="background:var(--bg3);border-radius:8px;padding:12px;margin-bottom:10px;border:1px solid var(--border);">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                <strong style="color:var(--text);">🔀 ${escapeHtml(flowName)}</strong>
                <span style="font-size:10px;color:var(--text3);">ID: ${escapeHtml(flowId)}</span>
            </div>
            <div class="field-group">
                <label class="field-label" style="font-size:10px;">Condition (Expression EL)</label>
                <select class="field-input condition-select" id="condition_select_${flowId}"
                        style="margin-bottom: 8px; font-family: monospace;"
                        onchange="updateConditionTextarea('${flowId}', this.value)">
                    ${optionsHtml}
                </select>
                <div style="position: relative;">
                    <label class="field-label" style="font-size: 9px; color: var(--text3);">Ou écrire une condition personnalisée :</label>
                    <textarea class="field-input condition-textarea" id="condition_textarea_${flowId}" rows="2"
                              placeholder="Ex: \${montant > 1000 &amp;&amp; status == 'pending'}"
                              style="font-family:monospace; margin-top: 4px;"
                              oninput="updateConditionSelect('${flowId}', this.value)">${escapeHtml(conditionExpression)}</textarea>
                </div>
                <div class="field-hint" style="margin-top: 6px;">
                    💡 <strong>Variables disponibles:</strong> approved, decision, amount, status, userId, userEmail, etc.
                </div>
            </div>
        </div>`;
    }

    container.innerHTML = html;
}

// Helper functions to sync dropdown and textarea
function updateConditionTextarea(flowId, value) {
    const textarea = document.getElementById(`condition_textarea_${flowId}`);
    if (textarea) {
        textarea.value = value;
        // Trigger change event to update any other logic
        textarea.dispatchEvent(new Event('input'));
    }
}

function updateConditionSelect(flowId, value) {
    const select = document.getElementById(`condition_select_${flowId}`);
    if (select) {
        // Check if value matches any option
        let found = false;
        for (let i = 0; i < select.options.length; i++) {
            if (select.options[i].value === value) {
                select.value = value;
                found = true;
                break;
            }
        }
        if (!found) {
            select.value = '';
            // Add a temporary custom option indicator
            select.style.borderColor = '#f87171';
            setTimeout(() => { select.style.borderColor = ''; }, 1500);
        }
    }
}

function applyGatewayConfig() {
    if (!selectedGatewayElement || !modeler) return;

    const modeling = modeler.get('modeling');
    const elementRegistry = modeler.get('elementRegistry');
    const moddle = modeler.get('moddle');
    const bo = selectedGatewayElement.businessObject;

    const gatewayType = document.getElementById('gatewayType').value;
    const defaultFlowId = document.getElementById('defaultFlowSelect').value;

    const props = {};

    // Set gateway type
    if (gatewayType !== 'exclusive') {
        props['camunda:type'] = gatewayType;
    } else {
        delete bo.$attrs['camunda:type'];
    }

    // Set default flow (for exclusive gateways)
    if (defaultFlowId && gatewayType === 'exclusive') {
        props['camunda:default'] = defaultFlowId;
    } else {
        delete bo.$attrs['camunda:default'];
    }

    modeling.updateProperties(selectedGatewayElement, props);

    // Get outgoing flows
    const allFlows = elementRegistry.filter(el => el.type === 'bpmn:SequenceFlow');
    const outgoing = allFlows.filter(flow => flow.businessObject.sourceRef === bo);

    // Update conditions on outgoing flows
    for (const flow of outgoing) {
        const flowId = flow.id;

        // Get condition value from textarea (preferred) or dropdown
        const textarea = document.getElementById(`condition_textarea_${flowId}`);
        const conditionValue = textarea ? textarea.value.trim() : '';

        const flowBo = flow.businessObject;

        if (conditionValue) {
            // Create a proper condition expression using moddle
            try {
                // First, remove any existing condition expression
                if (flowBo.conditionExpression) {
                    modeling.updateProperties(flow, {
                        'conditionExpression': undefined
                    });
                }

                // Create new condition expression
                const conditionExpression = moddle.create('bpmn:FormalExpression', {
                    body: conditionValue
                });

                modeling.updateProperties(flow, {
                    'conditionExpression': conditionExpression
                });

            } catch (err) {
                console.error('Error setting condition:', err);
                toast(`❌ Erreur lors de la définition de la condition pour "${flowBo.name || flow.id}"`, 'error');
            }
        } else {
            // Remove condition if exists
            modeling.updateProperties(flow, {
                'conditionExpression': undefined
            });
        }
    }

    toast(`✓ Configuration de la passerelle "${selectedGatewayElement.businessObject.name || selectedGatewayElement.id}" appliquée`, 'success');
    refreshGatewaySummary();
}
function refreshGatewaySummary() {
    if (!modeler) return;
    const gateways = modeler.get('elementRegistry').filter(el =>
        el.type === 'bpmn:ExclusiveGateway' ||
        el.type === 'bpmn:ParallelGateway' ||
        el.type === 'bpmn:InclusiveGateway' ||
        el.type === 'bpmn:EventBasedGateway'
    );

    const summaryCard = document.getElementById('gatewaySummaryCard');
    const list = document.getElementById('gatewaySummaryList');

    if (!gateways.length) {
        if (summaryCard) summaryCard.style.display = 'none';
        return;
    }

    if (summaryCard) summaryCard.style.display = 'block';
    if (!list) return;

    list.innerHTML = gateways.map(gw => {
        const bo = gw.businessObject;
        const outgoing = bo.outgoing || [];
        const hasDefault = bo.get('camunda:default');

        let conditionsCount = 0;
        for (const flow of outgoing) {
            const flowBo = flow.businessObject;
            if (flowBo.conditionExpression) conditionsCount++;
        }

        return `
        <div class="task-summary-item has-assignment" onclick="focusGateway('${escapeHtml(gw.id)}')">
            <div class="task-dot assigned"></div>
            <div style="min-width:0;">
                <div class="task-summary-name">🎯 ${escapeHtml(bo.name || gw.id)}</div>
                <div class="task-summary-assign">
                    ${outgoing.length} flux | ${conditionsCount} condition(s) | ${hasDefault ? '✔️ Flux par défaut' : '⚠️ Pas de flux par défaut'}
                </div>
            </div>
        </div>`;
    }).join('');
}

function focusGateway(elementId) {
    const registry = modeler.get('elementRegistry');
    const selection = modeler.get('selection');
    const canvas = modeler.get('canvas');
    const el = registry.get(elementId);
    if (el) {
        selection.select(el);
        canvas.scrollToElement(el);
        openGatewayPanel(el);
    }
}

// ════════════════════════════════════════════════════════════
//  TOPIC DROPDOWN HELPERS
// ════════════════════════════════════════════════════════════



function onManualTopicInput(value) {
    const select = document.getElementById('serviceTaskTopicSelect');
    if (select) {
        // Check if manual input matches any preset option
        let found = false;
        for (let i = 0; i < select.options.length; i++) {
            if (select.options[i].value === value) {
                select.value = value;
                found = true;
                break;
            }
        }
        if (!found && select) {
            select.value = '';
        }
    }

    // Show helpful message for generate-attestation
    if (value === 'generate-attestation') {
        const hint = document.querySelector('#externalTopicGroup .field-hint');
        if (hint) {
            hint.style.borderLeft = '3px solid #4ade80';
            hint.style.paddingLeft = '10px';
            hint.style.background = 'rgba(74, 222, 128, 0.05)';
        }
    }
}

//fazet taa il pdf dynamic ou kol pdfme ^^ baha nahitou no more needed

// ════════════════════════════════════════════════════════════
//  SIDEBAR COLLAPSE
// ════════════════════════════════════════════════════════════
let sidebarCollapsed = false;

function toggleSidebar() {
    const sidebar  = document.querySelector('.modeler-sidebar');
    const body     = document.querySelector('.modeler-body');
    const icon     = document.getElementById('sidebarToggleIcon');
    const btn      = document.getElementById('sidebarToggleBtn');

    sidebarCollapsed = !sidebarCollapsed;

    if (sidebarCollapsed) {
        sidebar.classList.add('collapsed');
        body.classList.add('sidebar-collapsed');
        icon.textContent = '‹';
        btn.title = 'Afficher le panneau latéral';
    } else {
        sidebar.classList.remove('collapsed');
        body.classList.remove('sidebar-collapsed');
        icon.textContent = '›';
        btn.title = 'Réduire le panneau latéral';
    }

    // Give the CSS transition time to finish, then re-fit the diagram
    setTimeout(() => {
        if (modeler) {
            try { modeler.get('canvas').zoom('fit-viewport'); } catch(e) {}
        }
    }, 310);
}

// ════════════════════════════════════════════════════════════
//  FULLSCREEN MODE
// ════════════════════════════════════════════════════════════
let isFullscreen = false;

function toggleFullscreen() {
    const wrapper   = document.querySelector('.modeler-wrapper');
    const expandIco = document.getElementById('fsIconExpand');
    const compIco   = document.getElementById('fsIconCompress');
    const btn       = document.getElementById('fullscreenBtn');
    let   hint      = document.getElementById('fsEscHint');

    // Create hint element once
    if (!hint) {
        hint = document.createElement('div');
        hint.id = 'fsEscHint';
        hint.className = 'fullscreen-esc-hint';
        hint.textContent = 'Mode plein écran — appuyez sur Échap pour quitter';
        document.body.appendChild(hint);
    }

    isFullscreen = !isFullscreen;

    if (isFullscreen) {
        wrapper.classList.add('fullscreen-mode');
        document.body.classList.add('in-fullscreen');
        expandIco.style.display = 'none';
        compIco.style.display   = 'inline';
        btn.title = 'Quitter le plein écran (Échap)';
        hint.style.display = 'block';
        // Lock page scroll
        document.body.style.overflow = 'hidden';
    } else {
        wrapper.classList.remove('fullscreen-mode');
        document.body.classList.remove('in-fullscreen');
        expandIco.style.display = 'inline';
        compIco.style.display   = 'none';
        btn.title = 'Plein écran';
        hint.style.display = 'none';
        document.body.style.overflow = '';
    }

    // Re-fit diagram after layout shift
    setTimeout(() => {
        if (modeler) {
            try { modeler.get('canvas').zoom('fit-viewport'); } catch(e) {}
        }
    }, 80);
}

// ESC key exits fullscreen
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && isFullscreen) {
        toggleFullscreen();
    }
});
// ════ PERSONALIZATION FUNCTIONS ════

function switchAssignTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('[id^="panel-"]').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.assign-tab').forEach(el => el.classList.remove('active'));

    // Show selected tab
    const panel = document.getElementById('panel-' + tabName);
    const tab = document.getElementById('tab-' + tabName);
    if (panel) panel.style.display = 'block';
    if (tab) tab.classList.add('active');
}

let customActionsCount = 0;
let customFieldsCount = 0;

// Predefined color palette for custom actions (name -> hex)
const ACTION_COLOR_PALETTE = [
    { name: 'gold',   hex: '#c9a84c' },
    { name: 'green',  hex: '#2ecc71' },
    { name: 'red',    hex: '#e74c3c' },
    { name: 'blue',   hex: '#3498db' },
    { name: 'purple', hex: '#9b59b6' },
    { name: 'orange', hex: '#e67e22' },
    { name: 'teal',   hex: '#1abc9c' },
    { name: 'pink',   hex: '#e84393' },
    { name: 'yellow', hex: '#f1c40f' },
    { name: 'gray',   hex: '#8a8f9a' },
];

function actionColorHex(name) {
    const found = ACTION_COLOR_PALETTE.find(c => c.name === name);
    return found ? found.hex : '#c9a84c';
}

function addCustomAction() {
    const container = document.getElementById('customActionsContainer');
    const actionId = customActionsCount++;

    const swatches = ACTION_COLOR_PALETTE.map(c => `
        <div class="color-swatch-option" title="${c.name}"
             onclick="selectActionColor(${actionId}, '${c.name}', '${c.hex}')"
             style="width:22px; height:22px; border-radius:5px; cursor:pointer;
                    background:${c.hex}; border:2px solid transparent;"></div>
    `).join('');

    const actionHtml = `
        <div class="custom-action" id="action-${actionId}" style="
            display:flex; gap:8px; background:var(--bg3); padding:10px;
            border-radius:6px; align-items:center;">
            <input type="text" placeholder="Nom de l'action (ex: Approuver)"
                   class="action-name" style="flex:1; padding:6px 10px; border:1px solid var(--border); border-radius:4px; font-size:12px; background:var(--bg2); color:var(--text);">
            <div class="action-color-picker" style="position:relative;">
                <button type="button" class="color-swatch-btn" id="colorBtn-${actionId}"
                        onclick="toggleColorPicker(${actionId})" title="Choisir une couleur"
                        style="width:30px; height:30px; border-radius:6px; border:2px solid var(--border);
                               cursor:pointer; background:${actionColorHex('gold')};"></button>
                <input type="hidden" class="action-color" value="gold">
                <div class="color-picker-dropdown" id="colorDropdown-${actionId}" style="
                    display:none; position:absolute; top:calc(100% + 6px); right:0;
                    background:var(--bg2); border:1px solid var(--border); border-radius:8px;
                    padding:8px; z-index:9999; box-shadow:0 8px 24px rgba(0,0,0,0.4); width:132px;">
                    <div style="display:grid; grid-template-columns:repeat(5,1fr); gap:6px;">
                        ${swatches}
                    </div>
                </div>
            </div>
            <button type="button" onclick="removeElement('action-${actionId}')"
                    style="padding:6px 12px; background:var(--red); color:white; border:none; border-radius:4px; cursor:pointer; font-size:12px;">
                ✕
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', actionHtml);
}

function toggleColorPicker(actionId) {
    // Close any other open pickers first
    document.querySelectorAll('.color-picker-dropdown').forEach(el => {
        if (el.id !== 'colorDropdown-' + actionId) el.style.display = 'none';
    });
    const dropdown = document.getElementById('colorDropdown-' + actionId);
    if (dropdown) dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
}

function selectActionColor(actionId, colorName, hex) {
    const row = document.getElementById('action-' + actionId);
    if (!row) return;
    row.querySelector('.action-color').value = colorName;
    const btn = document.getElementById('colorBtn-' + actionId);
    if (btn) btn.style.background = hex;
    const dropdown = document.getElementById('colorDropdown-' + actionId);
    if (dropdown) dropdown.style.display = 'none';
}

// Close color pickers when clicking outside of them
document.addEventListener('click', function(e) {
    if (!e.target.closest('.action-color-picker')) {
        document.querySelectorAll('.color-picker-dropdown').forEach(el => el.style.display = 'none');
    }
});

let fieldOptionCounters = {};

function addCustomField() {
    const container = document.getElementById('customFieldsContainer');
    const fieldId = customFieldsCount++;
    fieldOptionCounters[fieldId] = 0;

    const fieldHtml = `
        <div class="custom-field" id="field-${fieldId}" style="
            display:flex; flex-direction:column; gap:8px;
            background:var(--bg3); padding:10px; border-radius:6px;">
            <div style="display:grid; grid-template-columns:1fr 1fr auto; gap:8px;">
                <input type="text" placeholder="Libellé du champ"
                       class="field-label-input" style="padding:6px 10px; border:1px solid var(--border); border-radius:4px; font-size:12px; background:var(--bg2); color:var(--text);">
                <select class="field-type-select" onchange="toggleFieldOptionsUI(${fieldId}, this.value)"
                        style="padding:6px 10px; border:1px solid var(--border); border-radius:4px; font-size:12px; background:var(--bg2); color:var(--text);">
                    <option value="text">Texte</option>
                    <option value="textarea">Texte long</option>
                    <option value="select">Liste déroulante</option>
                    <option value="checkbox">Case à cocher</option>
                    <option value="date">Date</option>
                </select>
                <button type="button" onclick="removeElement('field-${fieldId}')"
                        style="padding:6px 12px; background:var(--red); color:white; border:none; border-radius:4px; cursor:pointer; font-size:12px;">
                    ✕
                </button>
            </div>
            <div class="field-options-wrap" id="fieldOptions-${fieldId}" style="
                display:none; flex-direction:column; gap:6px;
                border-left:2px solid var(--gold); padding-left:10px; margin-left:2px;">
                <div style="font-size:10px; color:var(--text3); text-transform:uppercase; letter-spacing:0.05em;">
                    Choix de la liste déroulante
                </div>
                <div class="field-options-list" id="fieldOptionsList-${fieldId}" style="display:flex; flex-direction:column; gap:6px;"></div>
                <button type="button" class="btn-small" onclick="addFieldOption(${fieldId})"
                        style="align-self:flex-start; background:var(--gold); color:#111; border:none;
                               border-radius:4px; padding:4px 10px; font-size:11px; cursor:pointer;">
                    + Ajouter un choix
                </button>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', fieldHtml);
}

function toggleFieldOptionsUI(fieldId, type) {
    const wrap = document.getElementById('fieldOptions-' + fieldId);
    if (!wrap) return;
    if (type === 'select') {
        wrap.style.display = 'flex';
        // Auto-add two starter choices the first time this becomes a dropdown
        const list = document.getElementById('fieldOptionsList-' + fieldId);
        if (list && list.children.length === 0) {
            addFieldOption(fieldId);
            addFieldOption(fieldId);
        }
    } else {
        wrap.style.display = 'none';
    }
}

function addFieldOption(fieldId) {
    const list = document.getElementById('fieldOptionsList-' + fieldId);
    if (!list) return;
    const optionId = fieldOptionCounters[fieldId]++;
    const n = list.children.length + 1;

    const optionHtml = `
        <div class="field-option-row" id="fieldOption-${fieldId}-${optionId}" style="display:flex; gap:6px; align-items:center;">
            <input type="text" class="field-option-input" placeholder="Choix ${n} (ex: Urgent)"
                   style="flex:1; padding:5px 8px; border:1px solid var(--border); border-radius:4px; font-size:11.5px; background:var(--bg2); color:var(--text);">
            <button type="button" onclick="removeElement('fieldOption-${fieldId}-${optionId}')"
                    style="background:none; border:none; color:var(--text3); cursor:pointer; font-size:14px; line-height:1; padding:2px 4px;"
                    title="Supprimer ce choix">✕</button>
        </div>
    `;
    list.insertAdjacentHTML('beforeend', optionHtml);
}

function removeElement(elementId) {
    const element = document.getElementById(elementId);
    if (element) element.remove();
}

function collectRequiredRoles() {
    return Array.from(document.querySelectorAll('.role-checkbox:checked'))
        .map(cb => cb.value);
}

function collectCustomActions() {
    return Array.from(document.querySelectorAll('#customActionsContainer .custom-action'))
        .map(row => ({
            name: row.querySelector('.action-name')?.value.trim() || '',
            color: row.querySelector('.action-color')?.value.trim() || 'gold',
        }))
        .filter(action => action.name !== '');
}

function collectCustomFields() {
    return Array.from(document.querySelectorAll('#customFieldsContainer .custom-field'))
        .map(row => {
            const label = row.querySelector('.field-label-input')?.value.trim() || '';
            const type = row.querySelector('.field-type-select')?.value || 'text';
            const field = {
                name: label
                    .toLowerCase()
                    .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // strip accents
                    .replace(/[^a-z0-9]+/g, '_')
                    .replace(/^_+|_+$/g, ''),
                label: label,
                type: type,
            };
            if (type === 'select') {
                field.options = Array.from(row.querySelectorAll('.field-option-input'))
                    .map(input => input.value.trim())
                    .filter(value => value !== '');
            }
            return field;
        })
        .filter(field => field.label !== '' && field.name !== '');
}

function saveTaskPersonalization() {
    // Use the element actually tracked by the panel (set in openTaskPanel / cleared in closeTaskPanel).
    // window.currentBpmnTaskId was never populated anywhere (onTaskSelected is never invoked),
    // so relying on it always failed this check.
    const taskId = selectedElement?.id;

    if (!taskId) {
        alert('❌ Veuillez d\'abord sélectionner une tâche dans le diagramme');
        return;
    }

    const workflowId = document.getElementById('modelContainer')?.getAttribute('data-workflow-id') || null;

    const personalizationData = {
        task_id: taskId,
        task_name: selectedElement?.businessObject?.name || taskId,
        workflow_id: workflowId,
        description: document.getElementById('taskDescription')?.value || '',
        instructions: document.getElementById('taskInstructions')?.value || '',
        custom_actions: collectCustomActions(),
        custom_fields: collectCustomFields(),
        required_for_roles: collectRequiredRoles(),
    };

    console.log('[v0] Sending personalization data:', personalizationData);

    fetch('/api/workflows/task-configs', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Accept': 'application/json',
        },
        body: JSON.stringify(personalizationData),
    })
    .then(response => {
        console.log('[v0] Response status:', response.status);

        if (!response.ok) {
            return response.text().then(text => {
                console.error('[v0] Error response:', text);
                throw new Error(`HTTP ${response.status}: ${text}`);
            });
        }

        return response.json();
    })
    .then(data => {
        console.log('[v0] Success response:', data);

        if (data.success) {
            toast('✓ Tâche personnalisée avec succès', 'success');
            console.log('[v0] Personalization saved successfully');
        } else {
            toast('✗ Erreur: ' + (data.message || 'Impossible de sauvegarder'), 'error');
        }
    })
    .catch(error => {
        console.error('[v0] Fetch error:', error);
        toast('✗ Erreur réseau: ' + error.message, 'error');
    });
}

// ✓ NEW: When a task is selected in the modeler, store its ID globally
function onTaskSelected(taskId, taskName) {
    window.currentBpmnTaskId = taskId;
    window.currentBpmnTaskName = taskName;
    console.log('[v0] BPMN Task selected:', { taskId, taskName });
}

// When task is selected, load its personalization
function loadTaskPersonalization(taskId) {
    fetch(`/api/workflow-tasks/${taskId}/personalization`)
        .then(res => res.json())
        .then(data => {
            if (data && data.personalization) {
                const p = data.personalization;
                document.getElementById('taskDescription').value = p.description || '';

                // Set roles
                p.allowedRoles.forEach(role => {
                    const checkbox = document.getElementById('role' + role.charAt(0).toUpperCase() + role.slice(1));
                    if (checkbox) checkbox.checked = true;
                });

                // Load custom actions
                document.getElementById('customActionsContainer').innerHTML = '';
                customActionsCount = 0;
                p.customActions.forEach(action => {
                    addCustomAction();
                    const container = document.getElementById('customActionsContainer');
                    const lastAction = container.lastChild;
                    const actionId = lastAction.id.replace('action-', '');
                    lastAction.querySelector('.action-name').value = action.name;
                    selectActionColor(actionId, action.color, actionColorHex(action.color));
                });

                // Load custom fields
                document.getElementById('customFieldsContainer').innerHTML = '';
                customFieldsCount = 0;
                fieldOptionCounters = {};
                p.customFields.forEach(field => {
                    addCustomField();
                    const container = document.getElementById('customFieldsContainer');
                    const lastField = container.lastChild;
                    const fieldId = lastField.id.replace('field-', '');
                    lastField.querySelector('.field-label-input').value = field.label;
                    lastField.querySelector('.field-type-select').value = field.type;

                    if (field.type === 'select') {
                        toggleFieldOptionsUI(fieldId, 'select');
                        const optionsList = document.getElementById('fieldOptionsList-' + fieldId);
                        optionsList.innerHTML = ''; // clear the 2 auto-added starter choices
                        fieldOptionCounters[fieldId] = 0;
                        (field.options || []).forEach(optionValue => {
                            addFieldOption(fieldId);
                            const rows = optionsList.querySelectorAll('.field-option-input');
                            rows[rows.length - 1].value = optionValue;
                        });
                    }
                });
            }
        })
        .catch(err => console.error('[v0] Erreur chargement:', err));
}
</script>


@endsection
