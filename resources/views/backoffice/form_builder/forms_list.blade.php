@extends('shared.layouts.backoffice')

@section('title', 'Gestion des Formulaires')
@section('breadcrumb', 'Form Builder / Formulaires')

@section('content')



{{-- ══ PAGE HEADER ══ --}}
<div class="fml-header">
    <div class="fml-header-left">
        <div class="fml-header-icon">⊞</div>
        <div>
            <div class="fml-header-title">Gestion des Formulaires</div>
            <div class="fml-header-sub">{{ $total }} formulaire{{ $total > 1 ? 's' : '' }} · Aperçu, édition &amp; suppression</div>
        </div>
    </div>
  <div class="fml-header-right">
        <a href="{{ route('admin.archive.index') }}" class="fml-btn" style="background:var(--fml-amber-dim);border-color:rgba(251,191,36,0.3);color:var(--fml-amber);">
            📦 Archives
        </a>
        <a href="{{ route('admin.formbuilder.index') }}" class="fml-btn fml-btn-ghost">
            ← Retour au Builder
        </a>
        <a href="{{ route('admin.formbuilder.index') }}" class="fml-btn fml-btn-pub">
            + Nouveau formulaire
        </a>
    </div>
</div>

{{-- ══ STATS BAR ══ --}}
<div class="fml-stats-bar">
    <div class="fml-stat-pill fml-stat-all" onclick="filterByStatus('')">
        <span class="fml-stat-num">{{ $total }}</span>
        <span class="fml-stat-lbl">Tous</span>
    </div>
    <div class="fml-stat-pill fml-stat-active" onclick="filterByStatus('ACTIF')">
        <span class="fml-stat-num">{{ $counts['ACTIF'] ?? 0 }}</span>
        <span class="fml-stat-lbl">Actifs</span>
    </div>
    <div class="fml-stat-pill fml-stat-draft" onclick="filterByStatus('Brouillon')">
        <span class="fml-stat-num">{{ $counts['Brouillon'] ?? 0 }}</span>
        <span class="fml-stat-lbl">Brouillons</span>
    </div>
    <div class="fml-stat-pill fml-stat-archived" onclick="filterByStatus('Archivé')">
        <span class="fml-stat-num">{{ $counts['Archivé'] ?? 0 }}</span>
        <span class="fml-stat-lbl">Archivés</span>
    </div>
</div>

{{-- ══ TOOLBAR ══ --}}
<div class="fml-toolbar">
    <div class="fml-search-wrap">
        <span class="fml-search-icon">🔍</span>
        <input class="fml-search" id="fmlSearch" placeholder="Rechercher par titre, département, workflow…"
               oninput="filterForms()">
    </div>
    <select class="fml-select" id="fmlStatusFilter" onchange="filterForms()">
        <option value="">Tous les statuts</option>
        <option value="ACTIF">Actif</option>
        <option value="Brouillon">Brouillon</option>
        <option value="Archivé">Archivé</option>
    </select>
    <select class="fml-select" id="fmlDeptFilter" onchange="filterForms()">
        <option value="">Tous les départements</option>
        @foreach($departments as $dept)
            <option value="{{ $dept->id }}">{{ $dept->name_fr ?? $dept->name }}</option>
        @endforeach
    </select>
    <select class="fml-select" id="fmlSortBy" onchange="filterForms()">
        <option value="date_desc">Plus récent</option>
        <option value="date_asc">Plus ancien</option>
        <option value="name_asc">Nom A→Z</option>
        <option value="name_desc">Nom Z→A</option>
    </select>
</div>

{{-- ══ FORMS TABLE ══ --}}
<div class="fml-table-wrap">
    <table class="fml-table" id="fmlTable">
        <thead>
            <tr>
                <th style="width:36px;"></th>
                <th>Titre du formulaire</th>
                <th>Département</th>
                <th>Workflow</th>
                <th>Statut</th>
                <th>Champs</th>
                <th>Soumissions</th>
                <th>Créé le</th>
                <th style="width:140px;">Actions</th>
            </tr>
        </thead>
        <tbody id="fmlTableBody">
            @forelse($formulaires as $form)
            <tr class="fml-row"
                data-id="{{ $form->id }}"
                data-name="{{ strtolower($form->titre) }}"
                data-dept="{{ $form->department_id }}"
                data-status="{{ $form->statut }}"
                data-date="{{ $form->created_at?->timestamp ?? 0 }}"
                data-workflow-id="{{ $form->workflow_id ?? '' }}"
                data-workflow-name="{{ $form->workflow?->nom ?? '' }}">
                <td>
                    <span class="fml-form-icon">📋</span>
                </td>
                <td>
                    <div class="fml-form-title">{{ $form->titre }}</div>
                    <div class="fml-form-slug">{{ $form->slug ?? '—' }}</div>
                </td>
                <td>
                    <span class="fml-dept-tag">
                        {{ $form->department?->name_fr ?? $form->department?->name ?? '—' }}
                    </span>
                </td>
                <td>
                    @if($form->workflow)
                        <span class="fml-workflow-tag">⏳ {{ $form->workflow->nom }}</span>
                    @else
                        <span class="fml-text-muted">—</span>
                    @endif
                </td>
                <td>
                    @php
                        $statusClass = match($form->statut) {
                            'ACTIF'   => 'fml-badge-green',
                            'Archivé' => 'fml-badge-amber',
                            default   => 'fml-badge-blue',
                        };
                    @endphp
                    <span class="fml-badge {{ $statusClass }}">{{ $form->statut }}</span>
                </td>
<td>
    <span class="fml-num">{{ isset($form->schema_formio['components']) ? count($form->schema_formio['components']) : 0 }}</span>
</td>
<td>
    <span class="fml-num">{{ $form->soumissions_count ?? 0 }}</span>
</td>
                <td>
                    <span class="fml-date">{{ $form->created_at?->format('d/m/Y') ?? '—' }}</span>
                </td>
                <td>
                    <div class="fml-actions">
                        {{-- Preview --}}
                        <button class="fml-action-btn fml-action-preview"
                                title="Aperçu"
                                onclick="openPreview({{ $form->id }}, '{{ addslashes($form->titre) }}', {{ json_encode($form->schema_formio) }})">
                            👁
                        </button>
                        {{-- Assign Workflow --}}
                        <button class="fml-action-btn fml-action-workflow"
                                title="Assigner un workflow"
                                onclick="openWorkflowModal({{ $form->id }}, '{{ addslashes($form->titre) }}', {{ $form->workflow_id ?? 'null' }})">
                            ⚙️
                        </button>
                        {{-- Edit in builder --}}
                        <a class="fml-action-btn fml-action-edit"
                           href="{{ route('admin.formbuilder.edit', $form->id) }}"
                           title="Modifier dans le Builder">
                            ✏️
                        </a>
                                {{-- ✅ ADD THIS: Export Excel button --}}
        <button class="fml-action-btn fml-action-export"
                title="Exporter les soumissions en Excel"
                style="color:#16a34a;"
                onclick="openExportModal({{ $form->id }}, '{{ addslashes($form->titre) }}')">
            📊
        </button>
                        {{-- Delete --}}
                        <button class="fml-action-btn fml-action-delete"
                                title="Supprimer"
                                onclick="confirmDelete({{ $form->id }}, '{{ addslashes($form->titre) }}')">
                            🗑
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="fml-empty">
                    <div class="fml-empty-icon">📭</div>
                    <div class="fml-empty-txt">Aucun formulaire créé pour l'instant</div>
                    <a href="{{ route('admin.formbuilder.index') }}" class="fml-btn fml-btn-pub" style="margin-top:16px;">
                        + Créer un formulaire
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ══ PAGINATION ══ --}}
@if($formulaires->hasPages())
<div class="fml-pagination">
    {{ $formulaires->links() }}
</div>
@endif


{{-- ══════════════════════════════════════════════
     MODALS
══════════════════════════════════════════════ --}}

{{-- Preview Modal --}}
<div class="fml-overlay" id="fmlPreviewOverlay" onclick="closePreview()"></div>
<div class="fml-modal fml-modal-lg" id="fmlPreviewModal">
    <div class="fml-modal-head">
        <div>
            <div class="fml-modal-title">👁 Aperçu — <span id="fmlPreviewTitle"></span></div>
            <div class="fml-modal-sub">Rendu exact côté utilisateur final · lecture seule</div>
        </div>
        <button class="fml-modal-close" onclick="closePreview()">×</button>
    </div>
    <div class="fml-modal-body" style="padding:0;">
        <div class="fml-device-bar" style="padding:14px 20px 0;">
            <button class="fml-device active" onclick="setDevice(this,'100%')">🖥 Desktop</button>
            <button class="fml-device" onclick="setDevice(this,'768px')">📱 Tablette</button>
            <button class="fml-device" onclick="setDevice(this,'390px')">📱 Mobile</button>
        </div>
        {{-- iframe replaces the old div — gives Form.io a clean isolated document --}}
        <div style="padding:14px 20px 20px;">
            <div id="fmlPreviewFrameWrap" style="transition:max-width .3s;margin:0 auto;width:100%;">
                <iframe id="fmlPreviewIframe"
                        style="width:100%;height:520px;border:1px solid var(--fml-border);border-radius:var(--fml-radius-sm);background:#fff;"
                        frameborder="0"></iframe>
            </div>
        </div>
    </div>
    <div class="fml-modal-foot">
        <button class="fml-btn fml-btn-ghost" onclick="closePreview()">Fermer</button>
    </div>
</div>

{{-- Delete Confirm Modal --}}
<div class="fml-overlay" id="fmlDeleteOverlay" onclick="cancelDelete()"></div>
<div class="fml-modal fml-modal-sm" id="fmlDeleteModal">
    <div class="fml-modal-head fml-modal-head-danger">
        <div>
            <div class="fml-modal-title">🗑 Supprimer le formulaire</div>
            <div class="fml-modal-sub">Cette action est irréversible</div>
        </div>
        <button class="fml-modal-close" onclick="cancelDelete()">×</button>
    </div>
    <div class="fml-modal-body">
        <div class="fml-delete-warning">
            <div class="fml-delete-warning-icon">⚠️</div>
            <div>
                Vous allez supprimer définitivement le formulaire
                <strong id="fmlDeleteName" style="color:var(--fml-text);display:block;margin-top:6px;font-size:14px;"></strong>
            </div>
        </div>
        <div class="fml-notice fml-notice-red">
            ❌ Toutes les soumissions et champs liés à ce formulaire seront également supprimés.
        </div>
        <div class="fml-confirm-input-wrap">
            <label class="fml-label">Tapez <strong>SUPPRIMER</strong> pour confirmer</label>
            <input type="text" class="fml-input" id="fmlDeleteConfirmInput"
                   placeholder="SUPPRIMER"
                   oninput="checkDeleteConfirm()">
        </div>
    </div>
    <div class="fml-modal-foot">
        <button class="fml-btn fml-btn-ghost" onclick="cancelDelete()">Annuler</button>
        <button class="fml-btn fml-btn-danger" id="fmlDeleteBtn" disabled
                onclick="executeDelete()">🗑 Supprimer définitivement</button>
    </div>
</div>

{{-- Toast container --}}
<div id="fmlToastContainer"></div>

{{-- ── Workflow Assignment Modal ── --}}
<div class="fml-overlay" id="fmlWorkflowOverlay" onclick="closeWorkflowModal()"></div>
<div class="fml-modal fml-modal-sm" id="fmlWorkflowModal">
    <div class="fml-modal-head">
        <div>
            <div class="fml-modal-title">⚙️ Assigner un Workflow</div>
            <div class="fml-modal-sub" id="fmlWorkflowFormName">—</div>
        </div>
        <button class="fml-modal-close" onclick="closeWorkflowModal()">×</button>
    </div>
    <div class="fml-modal-body">
        <div class="fml-notice fml-notice-teal" style="margin-bottom:16px;">
            ℹ️ Le workflow sera associé à ce formulaire et déclenchera l'automatisation BPM lors des soumissions.
        </div>
        <div style="margin-bottom:14px;">
            <label class="fml-label">Workflow BPMN</label>
            <select class="fml-select" id="fmlWorkflowSelect" onchange="updateWorkflowPreview(this)" style="width:100%;padding:9px 12px;font-size:13px;">
                <option value="">— Aucun workflow —</option>
                @foreach(\App\Models\Workflow::where('actif', true)->orderBy('nom')->get() as $wf)
                    <option value="{{ $wf->id }}">{{ $wf->nom }}</option>
                @endforeach
            </select>
        </div>
        <div id="fmlWorkflowPreview" class="fml-workflow-preview" style="display:none;">
            <span class="fml-workflow-tag" id="fmlWorkflowPreviewTag"></span>
        </div>
    </div>
    <div class="fml-modal-foot">
        <button class="fml-btn fml-btn-ghost" onclick="closeWorkflowModal()">Annuler</button>
        <button class="fml-btn fml-btn-save" id="fmlWorkflowSaveBtn" onclick="saveWorkflow()">
            💾 Enregistrer
        </button>
    </div>
</div>

{{-- Hidden CSRF + routes --}}
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    const FML_ROUTES = {
        destroy:        '/admin/form-builder/{id}',
        edit:           '/admin/form-builder/{id}/edit',
        assignWorkflow: '/admin/form-builder/{id}/workflow',
    };
</script>


{{-- ══════════════════════════════════════════════
     STYLES
══════════════════════════════════════════════ --}}
<style>
    /* ── Export button style ── */
.fml-action-export { color: #16a34a; }
.fml-action-export:hover {
    background: rgba(22, 163, 74, 0.15) !important;
    border-color: rgba(22, 163, 74, 0.3) !important;
}
:root {
    --fml-bg:       var(--bg,  #0b0d0f);
    --fml-bg2:      var(--bg2, #111316);
    --fml-bg3:      var(--bg3, #181b1f);
    --fml-bg4:      var(--bg4, #1e2228);
    --fml-border:   var(--border,  rgba(255,255,255,.07));
    --fml-border2:  var(--border2, rgba(255,255,255,.12));
    --fml-text:     var(--text,  #f0f0f0);
    --fml-text2:    var(--text2, #8a8f9a);
    --fml-text3:    var(--text3, #4a4f5a);
    --fml-gold:     var(--gold,  #c9a84c);
    --fml-gold2:    var(--gold2, #e8c97a);
    --fml-gold-dim: var(--gold-dim, rgba(201,168,76,.15));
    --fml-teal:     var(--teal, #2dd4bf);
    --fml-teal-dim: var(--teal-dim, rgba(45,212,191,.12));
    --fml-red:      var(--red,  #f87171);
    --fml-red-dim:  var(--red-dim, rgba(248,113,113,.12));
    --fml-green:    var(--green, #4ade80);
    --fml-green-dim:var(--green-dim, rgba(74,222,128,.12));
    --fml-amber:    var(--amber, #fbbf24);
    --fml-amber-dim:var(--amber-dim, rgba(251,191,36,.12));
    --fml-blue:     var(--blue, #60a5fa);
    --fml-blue-dim: var(--blue-dim, rgba(96,165,250,.12));
    --fml-font:     var(--font-body, system-ui, sans-serif);
    --fml-mono:     var(--font-mono, 'SF Mono','Menlo',monospace);
    --fml-radius:   var(--radius, 10px);
    --fml-radius-sm:var(--radius-sm, 6px);
}

/* ── Header ── */
.fml-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: var(--fml-bg2);
    border: 1px solid var(--fml-border);
    border-radius: var(--fml-radius);
    padding: 14px 20px;
    margin-bottom: 10px;
    gap: 12px;
    flex-wrap: wrap;
}
.fml-header-left { display:flex; align-items:center; gap:14px; }
.fml-header-icon { font-size:28px; filter:drop-shadow(0 0 8px var(--fml-gold-dim)); }
.fml-header-title { font-size:16px; font-weight:800; color:var(--fml-text); }
.fml-header-sub   { font-size:11px; color:var(--fml-gold); margin-top:2px; }
.fml-header-right { display:flex; gap:8px; flex-wrap:wrap; }

/* ── Buttons ── */
.fml-btn {
    display:inline-flex; align-items:center; gap:5px;
    padding:8px 14px; border-radius:var(--fml-radius-sm);
    font-size:12px; font-weight:600; font-family:var(--fml-font);
    cursor:pointer; border:1px solid transparent;
    transition:all .18s; white-space:nowrap; text-decoration:none;
}
.fml-btn:active { transform:scale(.97); }
.fml-btn-ghost {
    background:var(--fml-bg3); border-color:var(--fml-border);
    color:var(--fml-text2);
}
.fml-btn-ghost:hover { background:var(--fml-bg4); color:var(--fml-text); }
.fml-btn-pub {
    background:linear-gradient(135deg,var(--fml-gold),var(--fml-gold2));
    border-color:var(--fml-gold); color:#111; font-weight:700;
    box-shadow:0 2px 8px rgba(201,168,76,.25);
}
.fml-btn-pub:hover { box-shadow:0 4px 14px rgba(201,168,76,.35); }
.fml-btn-danger {
    background:var(--fml-red-dim); border-color:rgba(248,113,113,.35);
    color:var(--fml-red); font-weight:700;
}
.fml-btn-danger:not([disabled]):hover {
    background:rgba(248,113,113,.22);
    box-shadow:0 4px 14px rgba(248,113,113,.25);
}
.fml-btn-danger[disabled] { opacity:.4; cursor:not-allowed; }

/* ── Stats bar ── */
.fml-stats-bar {
    display:flex; gap:8px; margin-bottom:10px; flex-wrap:wrap;
}
.fml-stat-pill {
    display:flex; align-items:center; gap:8px;
    padding:10px 18px; border-radius:var(--fml-radius);
    border:1px solid var(--fml-border);
    background:var(--fml-bg2); cursor:pointer;
    transition:all .18s; user-select:none;
}
.fml-stat-pill:hover { border-color:var(--fml-border2); background:var(--fml-bg3); }
.fml-stat-num { font-size:20px; font-weight:900; font-family:var(--fml-mono); }
.fml-stat-lbl { font-size:11px; color:var(--fml-text2); }
.fml-stat-all    .fml-stat-num { color:var(--fml-text); }
.fml-stat-active .fml-stat-num { color:var(--fml-green); }
.fml-stat-draft  .fml-stat-num { color:var(--fml-blue); }
.fml-stat-archived .fml-stat-num { color:var(--fml-amber); }

/* ── Toolbar ── */
.fml-toolbar {
    display:flex; gap:8px; margin-bottom:10px; flex-wrap:wrap; align-items:center;
    background:var(--fml-bg2); border:1px solid var(--fml-border);
    border-radius:var(--fml-radius); padding:10px 14px;
}
.fml-search-wrap {
    display:flex; align-items:center; gap:8px;
    background:var(--fml-bg3); border:1px solid var(--fml-border);
    border-radius:var(--fml-radius-sm); padding:0 10px; flex:1; min-width:200px;
}
.fml-search-icon { color:var(--fml-text3); font-size:13px; }
.fml-search {
    background:transparent; border:none; color:var(--fml-text);
    font-size:12.5px; padding:7px 0; width:100%; outline:none;
    font-family:var(--fml-font);
}
.fml-search::placeholder { color:var(--fml-text3); }
.fml-select {
    padding:7px 10px; background:var(--fml-bg3); border:1px solid var(--fml-border);
    border-radius:var(--fml-radius-sm); color:var(--fml-text);
    font-size:12px; cursor:pointer; outline:none; font-family:var(--fml-font);
}
.fml-select:focus { border-color:var(--fml-gold); }

/* ── Table ── */
.fml-table-wrap {
    background:var(--fml-bg2); border:1px solid var(--fml-border);
    border-radius:var(--fml-radius); overflow:hidden; margin-bottom:10px;
}
.fml-table { width:100%; border-collapse:collapse; }
.fml-table thead tr {
    background:var(--fml-bg3); border-bottom:1px solid var(--fml-border);
}
.fml-table th {
    padding:11px 14px; text-align:left; font-size:11px; font-weight:600;
    color:var(--fml-text2); text-transform:uppercase; letter-spacing:.05em;
    white-space:nowrap;
}
.fml-row {
    border-bottom:1px solid var(--fml-border);
    transition:background .15s;
}
.fml-row:last-child { border-bottom:none; }
.fml-row:hover { background:var(--fml-bg3); }
.fml-row[data-hidden="true"] { display:none; }
.fml-table td { padding:12px 14px; vertical-align:middle; }
.fml-form-icon { font-size:18px; }
.fml-form-title { font-size:13px; font-weight:700; color:var(--fml-text); }
.fml-form-slug  { font-size:10.5px; color:var(--fml-text3); margin-top:2px; font-family:var(--fml-mono); }
.fml-dept-tag {
    font-size:11px; padding:3px 9px; border-radius:20px;
    background:var(--fml-blue-dim); color:var(--fml-blue);
}
.fml-workflow-tag {
    font-size:11px; padding:3px 9px; border-radius:20px;
    background:var(--fml-teal-dim); color:var(--fml-teal);
}
.fml-text-muted { color:var(--fml-text3); font-size:12px; }
.fml-num  { font-family:var(--fml-mono); font-size:13px; font-weight:700; color:var(--fml-text); }
.fml-date { font-size:11.5px; color:var(--fml-text2); }

/* Badges */
.fml-badge {
    display:inline-block; font-size:11px; font-weight:700; padding:3px 10px;
    border-radius:20px;
}
.fml-badge-green  { background:var(--fml-green-dim);  color:var(--fml-green); }
.fml-badge-amber  { background:var(--fml-amber-dim);  color:var(--fml-amber); }
.fml-badge-blue   { background:var(--fml-blue-dim);   color:var(--fml-blue);  }
.fml-badge-red    { background:var(--fml-red-dim);    color:var(--fml-red);   }

/* Action buttons */
.fml-actions { display:flex; gap:4px; }
.fml-action-btn {
    display:inline-flex; align-items:center; justify-content:center;
    width:30px; height:30px; border-radius:var(--fml-radius-sm);
    font-size:14px; cursor:pointer; border:1px solid var(--fml-border);
    background:var(--fml-bg3); transition:all .15s; text-decoration:none;
}
.fml-action-preview:hover  { background:var(--fml-teal-dim);  border-color:rgba(45,212,191,.3); }
.fml-action-edit:hover     { background:var(--fml-gold-dim);  border-color:rgba(201,168,76,.3); }
.fml-action-delete:hover   { background:var(--fml-red-dim);   border-color:rgba(248,113,113,.3); }
.fml-action-workflow:hover { background:var(--fml-gold-dim);  border-color:rgba(201,168,76,.3); }
.fml-notice-teal {
    background:var(--fml-teal-dim); border:1px solid rgba(45,212,191,.2);
    color:var(--fml-teal);
}
.fml-btn-save {
    background:linear-gradient(135deg,var(--fml-gold),var(--fml-gold2));
    border-color:var(--fml-gold); color:#111; font-weight:700;
    box-shadow:0 2px 8px rgba(201,168,76,.25);
}
.fml-btn-save:hover { box-shadow:0 4px 14px rgba(201,168,76,.35); }
.fml-btn-save[disabled] { opacity:.4; cursor:not-allowed; }
.fml-workflow-preview { margin-top:10px; }

/* Empty state */
.fml-empty { text-align:center; padding:60px 20px !important; }
.fml-empty-icon { font-size:40px; margin-bottom:12px; opacity:.5; }
.fml-empty-txt  { font-size:14px; color:var(--fml-text2); }

/* ── Modals ── */
.fml-overlay {
    display:none; position:fixed; inset:0; background:rgba(0,0,0,.65);
    z-index:9990; backdrop-filter:blur(3px);
}
.fml-overlay.open { display:block; }
.fml-modal {
    display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%);
    background:var(--fml-bg2); border:1px solid var(--fml-border2);
    border-radius:var(--fml-radius); z-index:9999;
    box-shadow:0 20px 60px rgba(0,0,0,.6); flex-direction:column;
    max-height:90vh; overflow:hidden;
}
.fml-modal.open { display:flex; animation:fml-popIn .22s cubic-bezier(.34,1.56,.64,1); }
@keyframes fml-popIn { from{opacity:0;transform:translate(-50%,-50%) scale(.92)} to{opacity:1;transform:translate(-50%,-50%) scale(1)} }

.fml-modal-sm { width:min(460px,94vw); }
.fml-modal-lg { width:min(820px,96vw); }

.fml-modal-head {
    display:flex; align-items:flex-start; justify-content:space-between;
    padding:18px 20px; border-bottom:1px solid var(--fml-border);
    background:var(--fml-bg3); flex-shrink:0;
}
.fml-modal-head-danger { border-bottom-color:rgba(248,113,113,.3); }
.fml-modal-title { font-size:14px; font-weight:800; color:var(--fml-text); }
.fml-modal-sub   { font-size:11px; color:var(--fml-text2); margin-top:3px; }
.fml-modal-close {
    background:none; border:none; color:var(--fml-text3); cursor:pointer;
    font-size:20px; line-height:1; padding:0 2px; transition:color .15s;
}
.fml-modal-close:hover { color:var(--fml-text); }
.fml-modal-body { padding:20px; overflow-y:auto; flex:1; }
.fml-modal-foot {
    display:flex; justify-content:flex-end; gap:8px; padding:14px 20px;
    border-top:1px solid var(--fml-border); background:var(--fml-bg3); flex-shrink:0;
}

/* Device bar */
.fml-device-bar {
    display:flex; gap:6px; margin-bottom:14px;
}
.fml-device {
    padding:5px 12px; font-size:11.5px; border-radius:var(--fml-radius-sm);
    background:var(--fml-bg3); border:1px solid var(--fml-border);
    color:var(--fml-text2); cursor:pointer; transition:all .15s;
}
.fml-device.active, .fml-device:hover {
    background:var(--fml-gold-dim); border-color:rgba(201,168,76,.3);
    color:var(--fml-gold);
}


/* Delete modal specifics */
.fml-delete-warning {
    display:flex; gap:14px; align-items:flex-start;
    padding:14px; background:var(--fml-red-dim);
    border:1px solid rgba(248,113,113,.25); border-radius:var(--fml-radius-sm);
    margin-bottom:14px; font-size:13px; color:var(--fml-text2);
}
.fml-delete-warning-icon { font-size:22px; flex-shrink:0; }
.fml-notice {
    padding:10px 14px; border-radius:var(--fml-radius-sm);
    font-size:12.5px; margin-bottom:14px;
}
.fml-notice-red {
    background:var(--fml-red-dim); border:1px solid rgba(248,113,113,.2);
    color:var(--fml-red);
}
.fml-label { display:block; font-size:11.5px; font-weight:600; color:var(--fml-text2); margin-bottom:6px; }
.fml-input {
    width:100%; padding:9px 12px; background:var(--fml-bg3);
    border:1px solid var(--fml-border); border-radius:var(--fml-radius-sm);
    color:var(--fml-text); font-size:13px; outline:none;
    font-family:var(--fml-mono); box-sizing:border-box;
    transition:border-color .18s;
}
.fml-input:focus { border-color:var(--fml-gold); }
.fml-confirm-input-wrap {}

/* Pagination override */
.fml-pagination { padding:8px 0; }
.fml-pagination .pagination { gap:4px; }

/* Toast */
#fmlToastContainer { position:fixed; bottom:24px; right:24px; z-index:99999; display:flex; flex-direction:column; gap:8px; }
.fml-toast {
    padding:12px 18px; border-radius:var(--fml-radius-sm);
    font-size:13px; font-weight:500; background:var(--fml-bg3);
    border:1px solid var(--fml-border2); color:var(--fml-text);
    box-shadow:0 10px 30px rgba(0,0,0,.5); animation:fml-fadein .25s ease;
    display:flex; align-items:center; gap:8px;
}
.fml-toast-success { border-color:rgba(74,222,128,.35); background:var(--fml-green-dim); color:var(--fml-green); }
.fml-toast-error   { border-color:rgba(248,113,113,.35); background:var(--fml-red-dim);  color:var(--fml-red);   }
@keyframes fml-fadein { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
/* ── Light mode overrides ── */
body.light {
    --fml-bg:        #f4f5f7;
    --fml-bg2:       #ffffff;
    --fml-bg3:       #f0f1f3;
    --fml-bg4:       #e4e6ea;
    --fml-border:    rgba(0,0,0,.08);
    --fml-border2:   rgba(0,0,0,.14);
    --fml-text:      #111316;
    --fml-text2:     #4a4f5a;
    --fml-text3:     #9aa0ad;
    --fml-gold:      #a07828;
    --fml-gold2:     #c9a84c;
    --fml-gold-dim:  rgba(160,120,40,.12);
    --fml-teal:      #0d9488;
    --fml-teal-dim:  rgba(13,148,136,.10);
    --fml-red:       #dc2626;
    --fml-red-dim:   rgba(220,38,38,.10);
    --fml-green:     #16a34a;
    --fml-green-dim: rgba(22,163,74,.10);
    --fml-amber:     #d97706;
    --fml-amber-dim: rgba(217,119,6,.10);
    --fml-blue:      #2563eb;
    --fml-blue-dim:  rgba(37,99,235,.10);
}

/* ── Light mode table row hover ── */
body.light .fml-row:hover         { background: var(--fml-bg3); }
body.light .fml-table thead tr    { background: var(--fml-bg3); }

/* ── Light mode modal ── */
body.light .fml-modal-head,
body.light .fml-modal-foot        { background: var(--fml-bg3); }

/* ── Light mode stat pills ── */
body.light .fml-stat-all    .fml-stat-num { color: #111316; }

/* ── Light mode search & selects ── */
body.light .fml-search         { color: #111316; }
body.light .fml-search::placeholder { color: var(--fml-text3); }
</style>


{{-- ══════════════════════════════════════════════
     SCRIPTS
══════════════════════════════════════════════ --}}

<script>
// ── State ──────────────────────────────────────────────────────────────────
let _deleteTargetId   = null;
let _previewFormId    = null;

// ── Filtering & sorting ────────────────────────────────────────────────────
function filterForms() {
    const q      = document.getElementById('fmlSearch').value.toLowerCase();
    const status = document.getElementById('fmlStatusFilter').value;
    const dept   = document.getElementById('fmlDeptFilter').value;
    const sort   = document.getElementById('fmlSortBy').value;

    const rows = Array.from(document.querySelectorAll('#fmlTableBody .fml-row'));

    rows.forEach(row => {
        const matchName   = row.dataset.name.includes(q);
        const matchStatus = !status || row.dataset.status === status;
        const matchDept   = !dept   || row.dataset.dept  === dept;
        row.dataset.hidden = !(matchName && matchStatus && matchDept);
    });

    // Sort visible rows
    const tbody   = document.getElementById('fmlTableBody');
    const visible = rows.filter(r => r.dataset.hidden !== 'true');
    visible.sort((a, b) => {
        switch (sort) {
            case 'name_asc':  return a.dataset.name.localeCompare(b.dataset.name);
            case 'name_desc': return b.dataset.name.localeCompare(a.dataset.name);
            case 'date_asc':  return +a.dataset.date - +b.dataset.date;
            default:          return +b.dataset.date - +a.dataset.date;
        }
    });
    visible.forEach(r => tbody.appendChild(r));
}

function filterByStatus(s) {
    document.getElementById('fmlStatusFilter').value = s;
    filterForms();
}

// ── Preview ────────────────────────────────────────────────────────────────
// ── Preview ────────────────────────────────────────────────────────────────
let _currentFormInstance = null;

// ── Preview ────────────────────────────────────────────────────────────────
function openPreview(id, titre, schema) {
    _previewFormId = id;
    document.getElementById('fmlPreviewTitle').textContent = titre;
    document.getElementById('fmlPreviewModal').classList.add('open');
    document.getElementById('fmlPreviewOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';

    const iframe = document.getElementById('fmlPreviewIframe');

    if (!schema || !schema.components || !schema.components.length) {
        // Write a simple message into the iframe
        iframe.srcdoc = `<html><body style="display:flex;align-items:center;justify-content:center;
            height:100%;margin:0;font-family:sans-serif;color:#888;font-size:14px;">
            ⚠ Ce formulaire n'a aucun champ défini.</body></html>`;
        return;
    }

    // Build a self-contained HTML page inside the iframe
    // Form.io loads its own copy of the CDN — fully isolated from the parent page
    const schemaJson = JSON.stringify(schema);
    iframe.srcdoc = `<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/formiojs@4.21.6/dist/formio.full.min.css">
  <style>
    body { margin: 0; padding: 24px; font-family: system-ui, sans-serif; background: #fff; }
    .formio-form .btn-wizard-nav-submit,
    .formio-form button[type=submit] { display: none !important; }
  </style>
</head>
<body>
  <div id="formio-preview"></div>
  <script src="https://cdn.jsdelivr.net/npm/formiojs@4.21.6/dist/formio.full.min.js"><\/script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var schema = ${schemaJson};
      Formio.createForm(document.getElementById('formio-preview'), schema, {
        readOnly: true,
        renderMode: 'html'
      }).catch(function(e) {
        document.body.innerHTML = '<p style="color:#e74c3c;padding:20px;">Erreur: ' + e.message + '</p>';
      });
    });
  <\/script>
</body>
</html>`;
}

function closePreview() {
    document.getElementById('fmlPreviewModal').classList.remove('open');
    document.getElementById('fmlPreviewOverlay').classList.remove('open');
    document.body.style.overflow = '';
    // Clear iframe to stop any ongoing network requests
    document.getElementById('fmlPreviewIframe').srcdoc = '';
}

function setDevice(el, w) {
    document.querySelectorAll('.fml-device').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('fmlPreviewFrameWrap').style.maxWidth = w;
}

// ── Delete ─────────────────────────────────────────────────────────────────
function confirmDelete(id, name) {
    _deleteTargetId = id;
    document.getElementById('fmlDeleteName').textContent = name;
    document.getElementById('fmlDeleteConfirmInput').value = '';
    document.getElementById('fmlDeleteBtn').disabled = true;
    document.getElementById('fmlDeleteModal').classList.add('open');
    document.getElementById('fmlDeleteOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
    // Focus the confirmation input
    setTimeout(() => document.getElementById('fmlDeleteConfirmInput').focus(), 220);
}

function cancelDelete() {
    _deleteTargetId = null;
    document.getElementById('fmlDeleteModal').classList.remove('open');
    document.getElementById('fmlDeleteOverlay').classList.remove('open');
    document.body.style.overflow = '';
}

function checkDeleteConfirm() {
    const val = document.getElementById('fmlDeleteConfirmInput').value.trim();
    document.getElementById('fmlDeleteBtn').disabled = (val !== 'SUPPRIMER');
}

async function executeDelete() {
    if (!_deleteTargetId) return;

    const btn = document.getElementById('fmlDeleteBtn');
    btn.disabled = true;
    btn.textContent = '⏳ Suppression…';

    try {
        const res = await fetch(
            FML_ROUTES.destroy.replace('{id}', _deleteTargetId),
            {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            }
        );
        const json = await res.json();

if (json.success) {
    cancelDelete();
    const row = document.querySelector(`#fmlTableBody .fml-row[data-id="${_deleteTargetId}"]`);
    if (row) {
        row.style.transition = 'opacity .3s, transform .3s';
        row.style.opacity = '0';
        row.style.transform = 'translateX(20px)';
        setTimeout(() => row.remove(), 320);
    } else {
        setTimeout(() => location.reload(), 600);
    }

    const archivedNote = json.archived_count > 0
        ? ` (${json.archived_count} soumission(s) archivée(s))`
        : '';
    showToast('✅ ' + json.message + archivedNote, 'success');
}else {
            showToast('❌ ' + (json.message ?? 'Échec de la suppression'), 'error');
            btn.disabled = false;
            btn.textContent = '🗑 Supprimer définitivement';
        }
    } catch (e) {
        console.error(e);
        showToast('❌ Erreur réseau lors de la suppression', 'error');
        btn.disabled = false;
        btn.textContent = '🗑 Supprimer définitivement';
    }
}

// ── Workflow Modal ─────────────────────────────────────────────────────────
let _workflowTargetId = null;

function openWorkflowModal(formId, formTitle, currentWorkflowId) {
    _workflowTargetId = formId;
    document.getElementById('fmlWorkflowFormName').textContent = formTitle;

    const sel = document.getElementById('fmlWorkflowSelect');
    sel.value = currentWorkflowId ?? '';
    updateWorkflowPreview(sel);

    document.getElementById('fmlWorkflowModal').classList.add('open');
    document.getElementById('fmlWorkflowOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeWorkflowModal() {
    _workflowTargetId = null;
    document.getElementById('fmlWorkflowModal').classList.remove('open');
    document.getElementById('fmlWorkflowOverlay').classList.remove('open');
    document.body.style.overflow = '';
}

function updateWorkflowPreview(sel) {
    const preview = document.getElementById('fmlWorkflowPreview');
    const tag     = document.getElementById('fmlWorkflowPreviewTag');
    const label   = sel.options[sel.selectedIndex]?.text ?? '';
    if (sel.value) {
        tag.textContent = '⏳ ' + label;
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
    }
}

async function saveWorkflow() {
    if (!_workflowTargetId) return;

    const workflowId = document.getElementById('fmlWorkflowSelect').value || null;
    const btn = document.getElementById('fmlWorkflowSaveBtn');
    btn.disabled = true;
    btn.textContent = '⏳ Enregistrement…';

    try {
        const res = await fetch(
            FML_ROUTES.assignWorkflow.replace('{id}', _workflowTargetId),
            {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ workflow_id: workflowId }),
            }
        );
        const json = await res.json();

        if (json.success) {
            // Update the row's workflow cell in-place
            const row = document.querySelector(`#fmlTableBody .fml-row[data-id="${_workflowTargetId}"]`);
            if (row) {
                const wfCell = row.querySelector('td:nth-child(4)');
                if (wfCell) {
                    wfCell.innerHTML = workflowId
                        ? `<span class="fml-workflow-tag">⏳ ${json.workflow_name}</span>`
                        : `<span class="fml-text-muted">—</span>`;
                }
                row.dataset.workflowId = workflowId ?? '';
                row.dataset.workflowName = json.workflow_name ?? '';
            }
            closeWorkflowModal();
            showToast('✅ Workflow assigné avec succès', 'success');
        } else {
            showToast('❌ ' + (json.message ?? 'Échec de l\'assignation'), 'error');
        }
    } catch (e) {
        console.error(e);
        showToast('❌ Erreur réseau', 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = '💾 Enregistrer';
    }
}
</script>

<script>
// ── Toast ──────────────────────────────────────────────────────────────────
function showToast(msg, type = 'info') {
    const el = document.createElement('div');
    el.className = 'fml-toast' + (type === 'success' ? ' fml-toast-success' : type === 'error' ? ' fml-toast-error' : '');
    el.textContent = msg;
    document.getElementById('fmlToastContainer').appendChild(el);
    setTimeout(() => { el.style.opacity = '0'; el.style.transition = 'opacity .3s'; }, 3200);
    setTimeout(() => el.remove(), 3550);
}

// ── Export Modal ──────────────────────────────────────────────────────────
let _exportFormId = null;

function openExportModal(formId, formTitle) {
    _exportFormId = formId;
    document.getElementById('fmlExportFormName').textContent = formTitle;
    document.getElementById('exportDateFrom').value = '';
    document.getElementById('exportDateTo').value   = '';
    document.getElementById('exportStatut').value   = '';
    document.getElementById('fmlExportModal').classList.add('open');
    document.getElementById('fmlExportOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeExportModal() {
    _exportFormId = null;
    document.getElementById('fmlExportModal').classList.remove('open');
    document.getElementById('fmlExportOverlay').classList.remove('open');
    document.body.style.overflow = '';
}

function doExport() {
    if (!_exportFormId) return;

    const btn = document.getElementById('fmlExportBtn');
    btn.disabled = true;
    btn.textContent = '⏳ Génération…';

    const params = new URLSearchParams();
    const from   = document.getElementById('exportDateFrom').value;
    const to     = document.getElementById('exportDateTo').value;
    const statut = document.getElementById('exportStatut').value;

    if (from)   params.set('date_from', from);
    if (to)     params.set('date_to', to);
    if (statut) params.set('statut', statut);

    // Redirect to download - triggers file download
    const url = `/admin/form-builder/${_exportFormId}/export-excel?${params.toString()}`;
    window.location.href = url;

    // Reset button after a moment
    setTimeout(() => {
        btn.disabled = false;
        btn.textContent = '⬇ Télécharger Excel';
    }, 3000);

    closeExportModal();
}

// Close export modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeExportModal();
    }
});
</script>
{{-- ══ EXPORT EXCEL MODAL ══ --}}
<div class="fml-overlay" id="fmlExportOverlay" onclick="closeExportModal()"></div>
<div class="fml-modal fml-modal-sm" id="fmlExportModal">
    <div class="fml-modal-head">
        <div>
            <div class="fml-modal-title">📊 Exporter en Excel</div>
            <div class="fml-modal-sub" id="fmlExportFormName">—</div>
        </div>
        <button class="fml-modal-close" onclick="closeExportModal()">×</button>
    </div>
    <div class="fml-modal-body">
        <div class="fml-notice fml-notice-teal" style="margin-bottom:16px;">
            ℹ️ Exportez toutes les soumissions de ce formulaire au format Excel (.xlsx).
        </div>

        <div style="display:flex;flex-direction:column;gap:14px;">
            {{-- Date From --}}
            <div>
                <label class="fml-label">DATE DE DÉBUT</label>
                <input type="date" id="exportDateFrom" class="fml-input"
                       style="width:100%;padding:9px 12px;font-size:13px;">
            </div>

            {{-- Date To --}}
            <div>
                <label class="fml-label">DATE DE FIN</label>
                <input type="date" id="exportDateTo" class="fml-input"
                       style="width:100%;padding:9px 12px;font-size:13px;">
            </div>

            {{-- Status Filter --}}
            <div>
                <label class="fml-label">STATUT DEMANDE</label>
                <select id="exportStatut" class="fml-select"
                        style="width:100%;padding:9px 12px;font-size:13px;">
                    <option value="">Tous les statuts</option>
                    <option value="en_cours">En cours</option>
                    <option value="approuvee">Approuvée</option>
                    <option value="rejetee">Rejetée</option>
                    <option value="terminee">Terminée</option>
                </select>
            </div>
        </div>
    </div>
    <div class="fml-modal-foot">
        <button class="fml-btn fml-btn-ghost" onclick="closeExportModal()">Annuler</button>
        <button class="fml-btn fml-btn-pub" onclick="doExport()" id="fmlExportBtn">
            ⬇ Télécharger Excel
        </button>
    </div>
</div>
@endsection
