@extends('shared.layouts.backoffice')

@section('title', 'Simple PDF Template Builder')
@section('breadcrumb', 'Configuration / Templates PDF / Simple Builder')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">

<style>
    .simple-builder-container { display: flex; gap: 20px; height: calc(100vh - 140px); }
    .editor-panel { flex: 2; background: var(--bg2); border-radius: 12px; border: 1px solid var(--border); display: flex; flex-direction: column; overflow: hidden; }
    .fields-panel { flex: 1; background: var(--bg2); border-radius: 12px; border: 1px solid var(--border); display: flex; flex-direction: column; overflow: hidden; }
    .panel-header { padding: 12px 16px; border-bottom: 1px solid var(--border); background: var(--bg3); flex-shrink: 0; }
    .panel-header h3 { margin: 0; font-size: 15px; }
    .panel-body { padding: 16px; flex: 1; overflow-y: auto; }
    .field-card { background: var(--bg3); border: 1px solid var(--border); border-radius: 8px; padding: 10px 12px; margin-bottom: 8px; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 10px; }
    .field-card:hover { border-color: var(--gold); transform: translateX(4px); }
    .field-name { font-weight: 600; font-size: 13px; flex: 1; }
    .field-code { font-family: monospace; font-size: 10px; color: var(--gold); background: rgba(201,168,76,0.1); padding: 3px 6px; border-radius: 4px; white-space: nowrap; }
    .btn-group { display: flex; gap: 8px; padding: 12px 16px; border-top: 1px solid var(--border); background: var(--bg3); flex-shrink: 0; flex-wrap: wrap; }
    .template-name-input { width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg3); color: var(--text); margin-bottom: 10px; font-size: 14px; box-sizing: border-box; }
    .editor-top { padding: 12px 16px 0; flex-shrink: 0; }
    .editor-wrap { flex: 1; display: flex; flex-direction: column; overflow: hidden; min-height: 0; padding: 0 16px; }

    /* Tabs */
    .tab-bar { display: flex; gap: 4px; margin-bottom: 8px; flex-shrink: 0; }
    .tab-btn { padding: 6px 14px; border-radius: 6px 6px 0 0; border: 1px solid var(--border); border-bottom: none; background: var(--bg3); color: var(--text2); font-size: 13px; cursor: pointer; transition: all 0.15s; }
    .tab-btn.active { background: var(--bg2); color: var(--text); font-weight: 600; border-color: var(--gold); }
    .tab-panel { display: none; flex: 1; flex-direction: column; min-height: 0; }
    .tab-panel.active { display: flex; }

    /* Quill */
    .ql-container { font-family: Arial, sans-serif; font-size: 14px; border: 1px solid var(--border) !important; border-top: none !important; border-radius: 0 0 8px 8px; flex: 1; overflow: hidden; display: flex; flex-direction: column; }
    .ql-editor { flex: 1; overflow-y: auto; min-height: 250px; color: #222; background: white; }
    .ql-toolbar { border: 1px solid var(--border) !important; border-radius: 8px 8px 0 0; background: var(--bg3); flex-wrap: wrap; flex-shrink: 0; }
    .ql-toolbar .ql-stroke { stroke: var(--text2); }
    .ql-toolbar .ql-fill { fill: var(--text2); }
    .ql-toolbar .ql-picker-label { color: var(--text2); }
    .ql-toolbar button:hover .ql-stroke, .ql-toolbar button.ql-active .ql-stroke { stroke: var(--gold); }
    .ql-toolbar button:hover .ql-fill, .ql-toolbar button.ql-active .ql-fill { fill: var(--gold); }
    .ql-rtl-toggle { font-size: 11px; font-weight: 700; padding: 2px 6px; border-radius: 4px; border: 1px solid var(--border); cursor: pointer; background: var(--bg3); color: var(--text2); }
    .ql-rtl-toggle.active { background: var(--gold); color: #000; border-color: var(--gold); }

    /* HTML source editor */
    .html-source-editor { flex: 1; width: 100%; font-family: 'Courier New', monospace; font-size: 12px; border: 1px solid var(--border); border-radius: 8px; background: #1e1e2e; color: #cdd6f4; padding: 16px; resize: none; line-height: 1.6; box-sizing: border-box; }

    /* Field group headers */
    .field-group-title { font-size: 11px; font-weight: 700; color: var(--text3); text-transform: uppercase; letter-spacing: 0.5px; margin: 14px 0 6px; }
    .field-group-title:first-child { margin-top: 0; }

    /* Modal */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9000; align-items: center; justify-content: center; }
    .modal-overlay.open { display: flex; }
    .modal-box { background: var(--bg2); border: 1px solid var(--border); border-radius: 12px; padding: 24px; width: 480px; max-width: 95vw; }
    .modal-box h3 { margin: 0 0 16px; font-size: 16px; }
    .modal-field { margin-bottom: 14px; }
    .modal-field label { display: block; font-size: 12px; color: var(--text2); margin-bottom: 4px; font-weight: 600; }
    .modal-field input, .modal-field textarea { width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg3); color: var(--text); font-size: 14px; box-sizing: border-box; }
    .modal-actions { display: flex; gap: 8px; justify-content: flex-end; margin-top: 20px; }

    /* PDF viewer placeholder */
    .pdf-stored-notice { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--text2); gap: 12px; }
    .pdf-stored-notice .pdf-icon { font-size: 64px; opacity: 0.5; }

    /* PATCH: Token chip styling */
    .token-chip {
        display: inline-block;
        background: rgba(201,168,76,0.18);
        color: var(--gold, #c9a84c);
        border: 1px solid rgba(201,168,76,0.4);
        border-radius: 4px;
        padding: 0 5px;
        font-family: monospace;
        font-size: 12px;
        line-height: 1.5;
        cursor: default;
        user-select: none;
        white-space: nowrap;
    }
</style>

{{--
    yes
--}}
<script>
    window.__builderData = {
        templateId:      "{{ $template->id ?? '' }}",
        templateContent: {!! json_encode($template->html_content ?? '') !!},
        templateType:    "{{ $template->template_type ?? 'html' }}",
        templateName:    "{{ $template->name ?? '' }}",
        linkedFormId:    "{{ $template->linked_form_id ?? '' }}",
        backUrl:         "{{ route('admin.pdf-templates.index') }}"
    };
</script>

<div class="simple-builder-container">
    <!-- ── Editor Panel ── -->
    <div class="editor-panel">
        <div class="editor-top">
            <input type="text" id="template-name" class="template-name-input"
                   placeholder="Template Name (e.g., Official Certificate)"
                   value="{{ $template->name ?? '' }}">
        </div>
<!-- After the template-name input -->
<div style="margin-bottom:10px;">
    <label style="display:block;font-size:11px;font-weight:600;color:var(--text3);margin-bottom:5px;">
        📋 Lier à un formulaire (attestation générée automatiquement)
    </label>
    <select id="linked-form-id" class="template-name-input" style="margin-bottom:0;">
        <option value="">— Aucun formulaire lié (pas d'attestation) —</option>
    </select>
    <div style="font-size:10px;color:var(--text3);margin-top:4px;">
        Quand ce formulaire arrive au statut final, cette attestation sera générée.
    </div>
</div>
        <!-- Tab bar -->
        <div class="editor-wrap">
            <div class="tab-bar">
                {{--yes
                --}}
                <button class="tab-btn active" id="tab-visual" onclick="builderSwitchTab('visual')">✏️ Visual Editor</button>
                <button class="tab-btn"        id="tab-html"   onclick="builderSwitchTab('html')">  &lt;/&gt; HTML Source</button>
                <button class="tab-btn"        id="tab-pdf"    onclick="builderSwitchTab('pdf')">   📄 Stored PDF</button>
            </div>

            <!-- Visual (Quill) tab -->
            <div class="tab-panel active" id="panel-visual">
                <div id="quill-toolbar">
                    <span class="ql-formats">
                        <select class="ql-font">
                            <option value="">Sans Serif</option>
                            <option value="serif">Serif</option>
                            <option value="monospace">Monospace</option>
                            <option value="arial">Arial</option>
                            <option value="times">Times New Roman</option>
                        </select>
                        <select class="ql-size">
                            <option value="10px">10</option>
                            <option value="12px">12</option>
                            <option value="14px" selected>14</option>
                            <option value="16px">16</option>
                            <option value="18px">18</option>
                            <option value="24px">24</option>
                            <option value="32px">32</option>
                            <option value="48px">48</option>
                        </select>
                    </span>
                    <span class="ql-formats">
                        <button class="ql-bold"></button>
                        <button class="ql-italic"></button>
                        <button class="ql-underline"></button>
                        <button class="ql-strike"></button>
                    </span>
                    <span class="ql-formats">
                        <select class="ql-color"></select>
                        <select class="ql-background"></select>
                    </span>
                    <span class="ql-formats">
                        <button class="ql-align" value=""></button>
                        <button class="ql-align" value="center"></button>
                        <button class="ql-align" value="right"></button>
                        <button class="ql-align" value="justify"></button>
                    </span>
                    <span class="ql-formats">
                        <button class="ql-list" value="ordered"></button>
                        <button class="ql-list" value="bullet"></button>
                        <button class="ql-indent" value="-1"></button>
                        <button class="ql-indent" value="+1"></button>
                    </span>
                    <span class="ql-formats">
                        <button class="ql-link"></button>
                        <button class="ql-image"></button>
                        <button class="ql-table-insert" title="Insert Table">⊞</button>
                    </span>
                    <span class="ql-formats">
                        <button class="ql-rtl-toggle" id="rtl-toggle" title="Toggle RTL/LTR">RTL</button>
                    </span>
                    <span class="ql-formats">
                        <button class="ql-clean"></button>
                    </span>
                </div>
                <div id="quill-editor"></div>
            </div>

            <!-- HTML Source tab -->
            <div class="tab-panel" id="panel-html">
                <textarea id="html-source" class="html-source-editor"
                          placeholder="Paste or write raw HTML here...
You can use field tokens like @{{user.nom}}, @{{demande.reference}}, etc."></textarea>
            </div>

            <!-- Stored PDF tab -->
            <div class="tab-panel" id="panel-pdf">
                <div class="pdf-stored-notice" id="pdf-notice-empty">
                    <div class="pdf-icon">📄</div>
                    <p>No PDF imported yet.</p>
                    <button class="btn btn-gold" onclick="openImportModal()">📥 Import PDF</button>
                </div>
                <div class="pdf-stored-notice" id="pdf-notice-loaded" style="display:none;">
                    <div class="pdf-icon">✅</div>
                    <p id="pdf-loaded-name" style="font-weight:600;color:var(--text);"></p>
                    <p style="font-size:12px;">This template serves the stored PDF directly.</p>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;justify-content:center;">
                        <button class="btn btn-ghost" onclick="previewTemplate()">👁️ View PDF</button>
                        <button class="btn btn-ghost" onclick="downloadPdf()">💾 Download</button>
                        <button class="btn btn-ghost" onclick="openImportModal()">🔄 Replace PDF</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-group">
            <button class="btn btn-gold"  onclick="saveTemplate()">💾 Save</button>
            <button class="btn btn-ghost" onclick="previewTemplate()">👁️ Preview PDF</button>
            <button class="btn btn-ghost" onclick="openImportModal()">📥 Import PDF</button>
            <button class="btn btn-ghost" id="back-btn">← Back</button>
            <span style="font-size:11px;color:var(--text3);align-self:center;margin-left:auto;">
                💡 Click a field on the right to insert at cursor
            </span>
        </div>
    </div>

    <!-- ── Fields Panel ── -->
    <div class="fields-panel">
        <div class="panel-header">
            <h3>📋 Available Fields</h3>
        </div>
        <div class="panel-body">
            <div class="field-group-title">👤 User</div>
            <div class="field-card" onclick="insertField('user.nom')"><span class="field-name">First Name</span><span class="field-code">@{{user.nom}}</span></div>
            <div class="field-card" onclick="insertField('user.prenom')"><span class="field-name">Last Name</span><span class="field-code">@{{user.prenom}}</span></div>
            <div class="field-card" onclick="insertField('user.email')"><span class="field-name">Email</span><span class="field-code">@{{user.email}}</span></div>
            <div class="field-card" onclick="insertField('user.cin')"><span class="field-name">National ID</span><span class="field-code">@{{user.cin}}</span></div>
            <div class="field-card" onclick="insertField('user.telephone')"><span class="field-name">Phone</span><span class="field-code">@{{user.telephone}}</span></div>

            <div class="field-group-title">📄 Request</div>
            <div class="field-card" onclick="insertField('demande.reference')"><span class="field-name">Reference</span><span class="field-code">@{{demande.reference}}</span></div>
            <div class="field-card" onclick="insertField('demande.statut')"><span class="field-name">Status</span><span class="field-code">@{{demande.statut}}</span></div>
            <div class="field-card" onclick="insertField('demande.created_at')"><span class="field-name">Created Date</span><span class="field-code">@{{demande.created_at}}</span></div>

            <div class="field-group-title">📝 Submission</div>
            <div class="field-card" onclick="insertField('submission.nom_prenom')"><span class="field-name">Full Name</span><span class="field-code">@{{submission.nom_prenom}}</span></div>
            <div class="field-card" onclick="insertField('submission.cin_numero')"><span class="field-name">CIN Number</span><span class="field-code">@{{submission.cin_numero}}</span></div>
            <div class="field-card" onclick="insertField('submission.date_naissance')"><span class="field-name">Birth Date</span><span class="field-code">@{{submission.date_naissance}}</span></div>
            <div class="field-card" onclick="insertField('submission.telephone')"><span class="field-name">Phone</span><span class="field-code">@{{submission.telephone}}</span></div>
            <div class="field-card" onclick="insertField('submission.specialite')"><span class="field-name">Specialty</span><span class="field-code">@{{submission.specialite}}</span></div>
            <div class="field-card" onclick="insertField('submission.type_demande')"><span class="field-name">Request Type</span><span class="field-code">@{{submission.type_demande}}</span></div>

<div class="field-group-title">✍️ Signatures disponibles</div>
<div id="signatures-list" style="margin-bottom:10px;">
    <p style="font-size:11px;color:var(--text3);text-align:center;padding:8px 0;margin:0;">
        Chargement...
    </p>
</div>
<!-- Keep the old director signature fields for backward compatibility -->
<div class="field-group-title" style="margin-top:12px;font-size:10px;color:var(--text3);opacity:0.6;">
    ⚠️ Héritage (directeur)
</div>
<div class="field-card" onclick="insertField('director.signature')" style="opacity:0.6;">
    <span class="field-name" style="font-size:11px;">Signature du directeur</span>
    <span class="field-code" style="font-size:9px;">@{{director.signature}}</span>
</div>
<div class="field-card" onclick="insertField('director.nom')" style="opacity:0.6;">
    <span class="field-name" style="font-size:11px;">Nom directeur</span>
    <span class="field-code" style="font-size:9px;">@{{director.nom}}</span>
</div>
<div class="field-card" onclick="insertField('director.prenom')" style="opacity:0.6;">
    <span class="field-name" style="font-size:11px;">Prénom directeur</span>
    <span class="field-code" style="font-size:9px;">@{{director.prenom}}</span>
</div>

            <div class="field-group-title">🔲 QR Code de validité</div>
            <div class="field-card" onclick="insertField('qr_code.image')" title="Insère l'image du QR code (vérification en ligne)">
                <span class="field-name">QR Code</span>
                <span class="field-code">@{{qr_code.image}}</span>
            </div>
            <div class="field-card" onclick="insertField('qr_code.expires_at')" title="Date d'expiration du document">
                <span class="field-name" style="font-size:12px;">Date d'expiration</span>
                <span class="field-code" style="font-size:9px;">@{{qr_code.expires_at}}</span>
            </div>
            <p style="font-size:10px;color:var(--text3);margin:4px 0 0;line-height:1.5;">
                Le scan renvoie vers une page qui calcule le temps restant en temps réel — la validité ne dépend pas de la date d'impression.
            </p>

            <div class="field-group-title">🔧 System</div>
            <div class="field-card" onclick="insertField('current_date')"><span class="field-name">Current Date</span><span class="field-code">@{{current_date}}</span></div>
            <div class="field-card" onclick="insertField('current_time')"><span class="field-name">Current Time</span><span class="field-code">@{{current_time}}</span></div>
            <div class="field-card" onclick="insertTableTemplate()"><span class="field-name">📊 Insert Table</span><span class="field-code">2×3 table</span></div>
<!-- ── Dynamic Form Fields ── -->
<div class="field-group-title" style="margin-top:18px;display:flex;align-items:center;justify-content:space-between;">
    <span>📋 Champs Formulaire</span>
    <span id="form-fields-count" style="font-size:10px;color:var(--gold);font-weight:400;"></span>
</div>
<p style="font-size:11px;color:var(--text3);margin:0 0 8px;line-height:1.5;">
    Sélectionnez un formulaire pour voir ses champs disponibles.
</p>

<!-- Form selector -->
<select id="form-selector"
        style="width:100%;padding:7px 10px;border-radius:6px;border:1px solid var(--border);
               background:var(--bg3);color:var(--text);font-size:13px;margin-bottom:8px;
               cursor:pointer;"
        onchange="loadFormSchemaFields()">
    <option value="">— Choisir un formulaire —</option>
</select>

<div id="dynamic-fields-container" style="min-height:40px;">
    <p style="font-size:11px;color:var(--text3);text-align:center;padding:12px 0;margin:0;">
        Aucun champ chargé
    </p>
</div>
        </div>
    </div>
</div>

<!-- ── Import PDF Modal ── -->
<div class="modal-overlay" id="import-modal">
    <div class="modal-box">
        <h3>📥 Import PDF File</h3>
        <div class="modal-field">
            <label>Template Name *</label>
            <input type="text" id="import-name" placeholder="e.g., Official Certificate">
        </div>
        <div class="modal-field">
            <label>Unique Key *</label>
            <input type="text" id="import-key" placeholder="e.g., official_certificate">
        </div>
        <div class="modal-field">
            <label>Description</label>
            <textarea id="import-desc" rows="2" placeholder="Optional description..."></textarea>
        </div>
        <div class="modal-field">
            <label>PDF File * (max 20MB)</label>
            <input type="file" id="import-file" accept=".pdf">
        </div>
        <div class="modal-actions">
            <button class="btn btn-ghost" onclick="closeImportModal()">Cancel</button>
            <button class="btn btn-gold"  onclick="doImportPdf()">📥 Import & Save</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

<style>
.ql-font-arial    { font-family: Arial, sans-serif; }
.ql-font-times    { font-family: 'Times New Roman', serif; }
.ql-font-serif    { font-family: Georgia, serif; }
.ql-font-monospace{ font-family: 'Courier New', monospace; }
</style>
@verbatim
<script>
// ─────────────────────────────────────────────────────────────────────────────
const jsData      = window.__builderData;
let currentId     = jsData.templateId   || null;
let currentType   = jsData.templateType || 'html';
const CSRF        = document.querySelector('meta[name="csrf-token"]')?.content || '';
let isRtl         = false;
let activeTab     = 'visual';

// ── Back button ────────────────────────────────────────────────────────────
document.getElementById('back-btn').addEventListener('click', () => {
    window.location.href = jsData.backUrl;
});

// ── Quill setup ────────────────────────────────────────────────────────────
const Font = Quill.import('formats/font');
Font.whitelist = ['serif', 'monospace', 'arial', 'times'];
Quill.register(Font, true);

const Size = Quill.import('attributors/style/size');
Size.whitelist = ['10px','12px','14px','16px','18px','24px','32px','48px'];
Quill.register(Size, true);

const quill = new Quill('#quill-editor', {
    theme: 'snow',
    modules: { toolbar: '#quill-toolbar' },
    placeholder: 'Start writing your template here...',
});

// ── Load existing content ──────────────────────────────────────────────────
if (currentType === 'pdf' && currentId) {
    showPdfTab(true);
} else if (jsData.templateContent) {
    quill.clipboard.dangerouslyPasteHTML(jsData.templateContent);
    document.getElementById('html-source').value = jsData.templateContent;
}

// ── RTL toggle ────────────────────────────────────────────────────────────
const rtlBtn = document.getElementById('rtl-toggle');
rtlBtn.addEventListener('click', () => {
    isRtl = !isRtl;
    rtlBtn.classList.toggle('active', isRtl);
    const ed = document.querySelector('.ql-editor');
    ed.setAttribute('dir', isRtl ? 'rtl' : 'ltr');
    ed.style.textAlign = isRtl ? 'right' : 'left';
    quill.getLines().forEach(line => {
        const idx = quill.getIndex(line);
        quill.formatLine(idx, 1, 'direction', isRtl ? 'rtl' : false, 'user');
        quill.formatLine(idx, 1, 'align',     isRtl ? 'right' : false, 'user');
    });
});

// ── Table-insert toolbar button ────────────────────────────────────────────
document.querySelector('.ql-table-insert').addEventListener('click', insertTableTemplate);

// ── PATCH: Tab switching — syncs chips properly ────────────────────────────
// When switching FROM visual TO html: convert chips to raw tokens in textarea.
// When switching FROM html TO visual: re-render so chips appear correctly.
function builderSwitchTab(tab) {
    activeTab = tab;

    const tabBtns   = document.querySelectorAll('.tab-btn');
    const tabPanels = document.querySelectorAll('.tab-panel');

    if (!tabBtns.length || !tabPanels.length) {
        console.warn('[builder] Tabs not found yet');
        return;
    }

    if (tab === 'html') {
        // Sync Quill → textarea, converting chips back to raw tokens
        const visualHtml = quill.root.innerHTML || '';
        document.getElementById('html-source').value = cleanHtmlForPdf(visualHtml);
    }

    if (tab === 'visual') {
        // Sync textarea → Quill (chips will re-render from the span markup)
        const src = document.getElementById('html-source').value || '';
        if (src.trim()) quill.clipboard.dangerouslyPasteHTML(src);
    }

    // Update active states
    tabBtns.forEach(b  => b.classList.remove('active'));
    tabPanels.forEach(p => p.classList.remove('active'));

    const activeTabBtn = document.getElementById('tab-' + tab);
    const activePanel  = document.getElementById('panel-' + tab);

    if (activeTabBtn) activeTabBtn.classList.add('active');
    if (activePanel)  activePanel.classList.add('active');
}

function showPdfTab(loaded) {
    document.getElementById('pdf-notice-empty').style.display  = loaded ? 'none' : 'flex';
    document.getElementById('pdf-notice-loaded').style.display = loaded ? 'flex' : 'none';
    if (loaded) {
        document.getElementById('pdf-loaded-name').textContent =
            document.getElementById('template-name').value || 'Imported PDF';
    }
    builderSwitchTab('pdf');
}

// ── PATCH: insertField() — inserts styled non-editable chip in Quill ───────
function insertField(fieldName) {
    const token = '{{' + fieldName + '}}';

    // HTML source tab: plain text insertion
    if (activeTab === 'html') {
        const ta    = document.getElementById('html-source');
        const start = ta.selectionStart;
        const end   = ta.selectionEnd;
        ta.value    = ta.value.substring(0, start) + token + ta.value.substring(end);
        ta.focus();
        ta.setSelectionRange(start + token.length, start + token.length);
        return;
    }

    // Visual (Quill) tab: insert as a styled non-editable span chip.
    // The data-token attribute holds the raw {{token}} value which
    // cleanHtmlForPdf() converts back before sending to the server.
    quill.focus();
    const range = quill.getSelection(true);

    const chipHtml = `<span class="token-chip" data-token="${token}" contenteditable="false">${token}</span>`;

    quill.clipboard.dangerouslyPasteHTML(range.index, chipHtml + '&nbsp;');
    quill.setSelection(range.index + 2, 0, 'user');
}

// ── Insert table ───────────────────────────────────────────────────────────
function insertTableTemplate() {
    const tableHtml = `<table border="1" cellpadding="6" cellspacing="0"
           style="border-collapse:collapse;width:100%;margin:12px 0;">
        <thead><tr style="background:#f0f0f0;">
            <th style="border:1px solid #999;padding:6px;text-align:left;">Header 1</th>
            <th style="border:1px solid #999;padding:6px;text-align:left;">Header 2</th>
            <th style="border:1px solid #999;padding:6px;text-align:left;">Header 3</th>
        </tr></thead>
        <tbody>
            <tr>
                <td style="border:1px solid #999;padding:6px;">Cell 1</td>
                <td style="border:1px solid #999;padding:6px;">Cell 2</td>
                <td style="border:1px solid #999;padding:6px;">Cell 3</td>
            </tr>
            <tr>
                <td style="border:1px solid #999;padding:6px;">Cell 4</td>
                <td style="border:1px solid #999;padding:6px;">Cell 5</td>
                <td style="border:1px solid #999;padding:6px;">Cell 6</td>
            </tr>
        </tbody>
    </table>`;

    if (activeTab === 'html') {
        const ta    = document.getElementById('html-source');
        const start = ta.selectionStart;
        ta.value    = ta.value.substring(0, start) + tableHtml + ta.value.substring(start);
        return;
    }

    quill.focus();
    const range = quill.getSelection(true);
    quill.clipboard.dangerouslyPasteHTML(range.index, tableHtml);
}

// ── PATCH: cleanHtmlForPdf() — converts chip spans back to raw {{tokens}} ──
// This is the critical step that makes AttestationService::replacePlaceholders()
// work correctly. Quill re-encodes {{ as &#123;&#123; in getSemanticHTML(),
// but chip spans survive serialization with data-token intact.
function cleanHtmlForPdf(html) {
    if (!html) return '';

    // Step 1: Replace chip spans → raw token value from data-token attribute
    // Using new RegExp to avoid Blade template engine misreading regex syntax
    html = html.replace(
        new RegExp('<span[^>]*data-token="([^"]+)"[^>]*>.*?<\\/span>', 'gi'),
        function(match, token) { return token; }
    );

    // Step 2: &#123;&#123;field&#125;&#125; → {{field}}
    html = html.replace(
        new RegExp('&#123;&#123;([^&]+?)&#125;&#125;', 'g'),
        '{{$1}}'
    );

    // Step 3: &lbrace;&lbrace;field&rbrace;&rbrace; → {{field}}
    html = html.replace(
        new RegExp('&lbrace;&lbrace;([^&]+?)&rbrace;&rbrace;', 'g'),
        '{{$1}}'
    );

    // Step 4: Strip leftover chip spans (safety net)
    html = html.replace(
        new RegExp('<span class="token-chip"[^>]*>', 'gi'),
        ''
    );

    // Step 5: Clean up &nbsp; after tokens
    html = html.replace(/\}\}\s*&nbsp;/g, '}} ');

    return html;
}

// ── PATCH: getCurrentHtml() — uses cleanHtmlForPdf() before sending ────────
// ── FIXED: getCurrentHtml() with proper Arabic + RTL support ───────────────
function getCurrentHtml() {
    let raw = '';

    if (activeTab === 'html') {
        raw = document.getElementById('html-source').value || '';
    } else {
        raw = quill.root.innerHTML || '';
        if (!raw || raw === '<p><br></p>') {
            raw = document.getElementById('html-source').value || '';
        }
    }

    raw = cleanHtmlForPdf(raw);

    return `
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <style>
        @font-face {
            font-family: 'Amiri';
            src: url('https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap');
        }
        body {
            font-family: 'Amiri', Arial, sans-serif !important;
            direction: rtl;
            text-align: right;
            line-height: 2.0;
            font-size: 15px;
            padding: 40px 50px;
        }
        .token-chip {
            direction: ltr !important;
            background: #f0f0f0;
            padding: 2px 6px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    ${raw}
</body>
</html>`;
}
// ── Save ───────────────────────────────────────────────────────────────────
async function saveTemplate() {
    const name = document.getElementById('template-name').value.trim();
    if (!name) { toast('Please enter a template name', 'error'); return; }

    // PDF templates: only update metadata, not HTML
    if (currentType === 'pdf' && currentId) {
        const res    = await fetch('/admin/api/pdf-templates/' + currentId, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({
                name,
                template_key: name.toLowerCase().replace(/[^a-z0-9]/g, '_'),
                page_size: 'A4'
            })
        });
        const result = await res.json();
        if (result.success) toast('Saved!', 'success');
        else toast('Error: ' + result.error, 'error');
        return;
    }

    const htmlContent = getCurrentHtml();
    const stripped = htmlContent.replace(/<[^>]*>/g, '').trim();
    if (!stripped) {
        toast('Cannot save empty template — add some content first', 'error');
        return;
    }

    const data = {
        name:           document.getElementById('template-name').value.trim(),
        template_key:  name.toLowerCase().replace(/[^a-z0-9]/g, '_'),
        html_content:  htmlContent,
        page_size:     'A4',
        linked_form_id: document.getElementById('linked-form-id').value || null,
        description:   'Created with Simple Template Builder'
    };

    try {
        const url    = currentId
            ? '/admin/api/pdf-templates/' + currentId
            : '/admin/api/pdf-templates/html-template';
        const method = currentId ? 'PUT' : 'POST';

        const res    = await fetch(url, {
            method,
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify(data)
        });
        const result = await res.json();

        if (result.success) {
            toast('Template saved!', 'success');
            if (result.template?.id) {
                currentId   = result.template.id;
                currentType = 'html';
            }
        } else {
            toast('Error: ' + (result.error || 'Unknown error'), 'error');
        }
    } catch (err) {
        toast('Failed: ' + err.message, 'error');
    }
}

// ── Preview ────────────────────────────────────────────────────────────────
function previewTemplate() {
    if (!currentId) { toast('Save first', 'error'); return; }
    window.open('/admin/api/pdf-templates/' + currentId + '/preview', '_blank');
}

function downloadPdf() {
    if (!currentId) return;
    window.open('/admin/api/pdf-templates/' + currentId + '/download-source', '_blank');
}

// ── Import PDF modal ───────────────────────────────────────────────────────
function openImportModal() {
    const name = document.getElementById('template-name').value.trim();
    if (name) {
        document.getElementById('import-name').value = name;
        document.getElementById('import-key').value  = name.toLowerCase().replace(/[^a-z0-9]/g, '_');
    }
    document.getElementById('import-modal').classList.add('open');
}

function closeImportModal() {
    document.getElementById('import-modal').classList.remove('open');
}

document.getElementById('import-modal').addEventListener('click', function(e) {
    if (e.target === this) closeImportModal();
});

async function doImportPdf() {
    const name = document.getElementById('import-name').value.trim();
    const key  = document.getElementById('import-key').value.trim();
    const desc = document.getElementById('import-desc').value;
    const file = document.getElementById('import-file').files[0];

    if (!name || !key)  { toast('Name and key are required', 'error'); return; }
    if (!file)          { toast('Please select a PDF file',  'error'); return; }
    if (file.size > 20 * 1024 * 1024) { toast('File exceeds 20MB', 'error'); return; }

    const formData = new FormData();
    formData.append('file',         file);
    formData.append('name',         name);
    formData.append('template_key', key);
    formData.append('description',  desc);

    toast('Uploading...', 'info');

    try {
        const res    = await fetch('/admin/api/pdf-templates/import-pdf', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF },
            body: formData
        });
        const result = await res.json();

        if (result.success) {
            currentId   = result.template.id;
            currentType = 'pdf';
            document.getElementById('template-name').value = name;
            closeImportModal();
            showPdfTab(true);
            toast('PDF imported successfully!', 'success');
        } else {
            toast('Error: ' + (result.error || 'Unknown'), 'error');
        }
    } catch (err) {
        toast('Failed: ' + err.message, 'error');
    }
}

// ── Toast ──────────────────────────────────────────────────────────────────
function toast(msg, type) {
    const t  = document.createElement('div');
    t.textContent = msg;
    const bg = type === 'success' ? '#166534' : type === 'info' ? '#1e3a5f' : '#7f1d1d';
    t.style.cssText = `position:fixed;bottom:20px;right:20px;padding:12px 18px;
        border-radius:8px;z-index:10000;color:white;background:${bg};
        box-shadow:0 4px 12px rgba(0,0,0,0.3);font-size:14px;`;
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 3000);
}

// ── Init ───────────────────────────────────────────────────────────────────
if (currentType === 'pdf' && currentId) {
    showPdfTab(true);
}

// ── Dynamic form fields loader ─────────────────────────────────────────────
// ── Load all forms into the selector on page load ──────────────────────────
async function initFormSelector() {
    try {
        const res   = await fetch('/api/forms/all-active');
        const forms = await res.json();

        // Populate the field-preview selector (existing)
        const fieldSel = document.getElementById('form-selector');
        // Populate the link selector (new)
        const linkSel  = document.getElementById('linked-form-id');

        (forms || []).forEach(f => {
            const label = f.titre + (f.department_name ? ' — ' + f.department_name : '');

            const opt1 = document.createElement('option');
            opt1.value = f.id;
            opt1.textContent = label;
            fieldSel.appendChild(opt1);

            const opt2 = document.createElement('option');
            opt2.value = f.id;
            opt2.textContent = label;
            linkSel.appendChild(opt2);
        });

        // Restore saved link
        const saved = window.__builderData.linkedFormId;
        if (saved) linkSel.value = saved;

    } catch (e) {
        console.warn('Could not load forms list:', e);
    }
}

// ── Load fields from form schema (no submission needed) ────────────────────
async function loadFormSchemaFields() {
    const formId    = document.getElementById('form-selector').value;
    const container = document.getElementById('dynamic-fields-container');
    const counter   = document.getElementById('form-fields-count');

    if (!formId) {
        container.innerHTML = '<p style="font-size:11px;color:var(--text3);text-align:center;padding:12px 0;margin:0;">Aucun champ chargé</p>';
        counter.textContent = '';
        return;
    }

    container.innerHTML = '<p style="font-size:11px;color:var(--text3);text-align:center;padding:12px 0;margin:0;">⏳ Chargement...</p>';

    try {
        const res    = await fetch('/admin/api/pdf-templates/form-schema-fields/' + formId);
        const result = await res.json();

        if (!result.fields || result.fields.length === 0) {
            container.innerHTML = '<p style="font-size:11px;color:#f87171;text-align:center;padding:12px 0;margin:0;">Aucun champ trouvé dans ce formulaire</p>';
            counter.textContent = '';
            return;
        }

        counter.textContent = result.fields.length + ' champs';

        container.innerHTML = result.fields.map(f => {
            const escaped = f.token.replace(/'/g, "\\'");
            return `
                <div class="field-card" onclick="insertField('${escaped}')" title="${f.label} (${f.type})">
                    <span class="field-name" style="font-size:12px;">${f.label}</span>
                    <span class="field-code" style="font-size:10px;">{{${f.token}}}</span>
                </div>
            `;
        }).join('');

    } catch (err) {
        container.innerHTML = `<p style="font-size:11px;color:#f87171;text-align:center;padding:8px 0;margin:0;">Erreur : ${err.message}</p>`;
    }
}

// Call on page load
initFormSelector();
// Add this to the @verbatim script section in simple-builder.blade.php

async function loadAvailableSignatures() {
    const container = document.getElementById('signatures-list');
    if (!container) return;

    try {
        const res = await fetch('/admin/api/pdf-templates/available-signatures');
        const result = await res.json();

        if (!result.signatures || result.signatures.length === 0) {
            container.innerHTML = `
                <p style="font-size:11px;color:var(--text3);text-align:center;padding:8px 0;margin:0;">
                    Aucune signature enregistrée
                </p>
                <p style="font-size:10px;color:var(--text3);text-align:center;padding:4px 0;margin:0;">
                    Un administrateur doit d'abord sauvegarder une signature dans ses paramètres.
                </p>
            `;
            return;
        }

        container.innerHTML = result.signatures.map(s => `
            <div class="field-card" onclick="insertField('${s.token}')" title="Insérer la signature de ${s.name}">
                <img src="${s.signature}" style="height:24px;max-width:60px;object-fit:contain;background:#fff;border-radius:3px;border:1px solid #e5e7eb;padding:2px;">
                <span class="field-name" style="font-size:12px;">${s.name}</span>
                <span class="field-code" style="font-size:9px;">{{${s.token}}}</span>
            </div>
        `).join('');

        // Also add name tokens for each signature
        result.signatures.forEach(s => {
            // Add nom token
            container.innerHTML += `
                <div class="field-card" onclick="insertField('${s.token}.nom')" title="Insérer le nom de ${s.name}" style="border-left:3px solid #c9a84c;opacity:0.8;">
                    <span class="field-name" style="font-size:11px;">${s.name} (nom)</span>
                    <span class="field-code" style="font-size:9px;">{{${s.token}.nom}}</span>
                </div>
            `;
            // Add prenom token
            container.innerHTML += `
                <div class="field-card" onclick="insertField('${s.token}.prenom')" title="Insérer le prénom de ${s.name}" style="border-left:3px solid #c9a84c;opacity:0.8;">
                    <span class="field-name" style="font-size:11px;">${s.name} (prénom)</span>
                    <span class="field-code" style="font-size:9px;">{{${s.token}.prenom}}</span>
                </div>
            `;
            // Add fullname token
            container.innerHTML += `
                <div class="field-card" onclick="insertField('${s.token}.fullname')" title="Insérer le nom complet de ${s.name}" style="border-left:3px solid #c9a84c;opacity:0.8;">
                    <span class="field-name" style="font-size:11px;">${s.name} (complet)</span>
                    <span class="field-code" style="font-size:9px;">{{${s.token}.fullname}}</span>
                </div>
            `;
        });

    } catch (err) {
        container.innerHTML = `
            <p style="font-size:11px;color:#f87171;text-align:center;padding:8px 0;margin:0;">
                Erreur: ${err.message}
            </p>
        `;
    }
}

// Call on page load
loadAvailableSignatures();
</script>
@endverbatim
@endsection
