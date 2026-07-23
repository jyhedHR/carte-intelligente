@extends('shared.layouts.backoffice')

@section('title', 'Gestion des Templates PDF')
@section('breadcrumb', 'Configuration / Templates PDF')

@section('content')
<style>
    .pdf-templates-container {
        display: flex;
        gap: 20px;
        height: calc(100vh - 140px);
    }

    .templates-sidebar {
        width: 320px;
        flex-shrink: 0;
        background: var(--bg2);
        border-radius: 12px;
        border: 1px solid var(--border);
        display: flex;
        flex-direction: column;
    }

    .templates-list {
        flex: 1;
        overflow-y: auto;
        padding: 16px;
    }

    .template-card {
        background: var(--bg3);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .template-card:hover {
        border-color: var(--gold);
        transform: translateX(4px);
    }

    .template-card.active {
        border-color: var(--gold);
        background: rgba(201,168,76,0.05);
    }

    .template-name {
        font-weight: 700;
        color: var(--text);
        margin-bottom: 4px;
    }

    .template-key {
        font-size: 10px;
        color: var(--text3);
        font-family: monospace;
    }

    .template-description {
        font-size: 11px;
        color: var(--text2);
        margin-top: 8px;
    }

    .template-actions {
        display: flex;
        gap: 8px;
        margin-top: 10px;
    }

    .designer-container {
        flex: 1;
        background: var(--bg2);
        border-radius: 12px;
        border: 1px solid var(--border);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .designer-toolbar {
        padding: 12px 16px;
        background: var(--bg3);
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    #pdf-designer {
        flex: 1;
        min-height: 500px;
        background: white;
    }
</style>

<div class="pdf-templates-container">
    <div class="templates-sidebar">
<div style="padding: 16px; border-bottom: 1px solid var(--border);">
    <button class="btn btn-gold btn-full" onclick="createNewTemplate()">
        + Nouveau template PDF
    </button>
<!-- ADD THIS NEW BUTTON -->
<button class="btn btn-gold btn-full" style="margin-top: 8px; background: #059669;" onclick="openHtmlBuilder()">
    🎨 HTML Visual Builder
</button>
    <!-- Existing JSON import -->
    <button class="btn btn-ghost btn-full" style="margin-top: 8px;" onclick="importTemplate()">
        📂 Importer un template (JSON)
    </button>

    <!-- NEW: PDF/DOCX Import -->
    <button class="btn btn-ghost btn-full" style="margin-top: 8px;" onclick="openFileImportModal()">
        📄 Importer PDF/DOCX
    </button>
    <button class="btn btn-gold btn-full" style="margin-top: 8px; background: #0891b2;" onclick="openSimpleBuilder()">
    ✏️ Simple Text Builder
</button>
</div>
        <div class="templates-list" id="templatesList">
            <div class="loading">Chargement des templates...</div>
        </div>
    </div>

    <div class="designer-container">
        <div class="designer-toolbar">
            <div>
                <strong id="currentTemplateName">Aucun template sélectionné</strong>
                <span id="currentTemplateKey" style="font-size: 11px; color: var(--text3); margin-left: 8px;"></span>
            </div>
<div>
    <button class="btn btn-ghost" onclick="previewOriginalPdf()">📄 Original PDF</button>
    <button class="btn btn-ghost" onclick="downloadOriginalFile()">💾 Download Original</button>
    <button class="btn btn-ghost" onclick="previewPdf()">👁️ Aperçu Final</button>
    <button class="btn btn-gold" onclick="saveCurrentTemplate()">💾 Sauvegarder</button>
    <button class="btn btn-ghost" onclick="exportTemplate()">📥 Exporter</button>
    <button class="btn btn-ghost" style="color: #f87171;" onclick="deleteCurrentTemplate()">🗑️ Supprimer</button>
</div>
        </div>
        <div id="pdf-designer"></div>
    </div>
</div>

{{-- Modal pour les propriétés du template --}}
<div id="modal-template-props" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <div class="modal-title">Propriétés du template</div>
            <button class="modal-close" onclick="closeModal('modal-template-props')">×</button>
        </div>
        <div class="modal-body">
            <div class="field-group">
                <label class="field-label">Nom du template</label>
                <input type="text" id="templateName" class="field-input" placeholder="ex: Attestation officielle">
            </div>
            <div class="field-group">
                <label class="field-label">Clé unique</label>
                <input type="text" id="templateKey" class="field-input" placeholder="ex: attestation_officielle">
                <div class="field-hint">Identifiant unique pour référencer ce template dans les workflows</div>
            </div>
            <div class="field-group">
                <label class="field-label">Description</label>
                <textarea id="templateDescription" class="field-textarea" rows="3" placeholder="Description du template..."></textarea>
            </div>
            <div class="field-group">
                <label class="field-label">Page Size</label>
                <select id="templatePageSize" class="field-input">
                    <option value="A4">A4</option>
                    <option value="Letter">Letter</option>
                    <option value="Legal">Legal</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('modal-template-props')">Annuler</button>
            <button class="btn btn-gold" onclick="saveTemplateProperties()">Enregistrer</button>
        </div>
    </div>
</div>
{{-- === NEW: File Import Modal (PDF/DOCX) === --}}
<div id="modal-file-import" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <div class="modal-title">Import PDF or Word Document</div>
            <button class="modal-close" onclick="closeModal('modal-file-import')">×</button>
        </div>
        <div class="modal-body">
            <div class="field-group">
                <label class="field-label">Template Name</label>
                <input type="text" id="importTemplateName" class="field-input" placeholder="e.g., Official Certificate">
            </div>
            <div class="field-group">
                <label class="field-label">Unique Key</label>
                <input type="text" id="importTemplateKey" class="field-input" placeholder="e.g., official_certificate">
            </div>
            <div class="field-group">
                <label class="field-label">Description (optional)</label>
                <textarea id="importTemplateDescription" class="field-textarea" rows="2"></textarea>
            </div>
            <div class="field-group">
                <label class="field-label">Page Size</label>
                <select id="importPageSize" class="field-input">
                    <option value="A4">A4</option>
                    <option value="Letter">Letter</option>
                    <option value="Legal">Legal</option>
                </select>
            </div>
            <div class="field-group">
                <label class="field-label">Choose File (PDF or DOCX)</label>
                <input type="file" id="importFileInput" accept=".pdf,.docx" class="field-input">
                <div class="field-hint">Max 20MB. PDF/Word documents will be converted to editable templates.</div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('modal-file-import')">Cancel</button>
            <button class="btn btn-gold" onclick="uploadAndImportFile()">Import & Convert</button>
        </div>
    </div>
</div>
<script type="module">
    // Définition de CSRF
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

    let pdfDesigner = null;
    let currentTemplate = null;
    let currentTemplateId = null;
    let allTemplates = [];

    // ════════════════════════════════════════════════════════════
    //  UTILITAIRES
    // ════════════════════════════════════════════════════════════

    function toast(msg, type = 'info') {
        const t = document.createElement('div');
        t.className = 'bpmn-toast ' + type;
        t.textContent = msg;
        t.style.cssText = 'position:fixed;bottom:20px;right:20px;padding:12px 18px;border-radius:8px;z-index:10000;';
        if (type === 'success') t.style.background = '#166534';
        if (type === 'error') t.style.background = '#7f1d1d';
        if (type === 'info') t.style.background = '#1e3a5f';
        t.style.color = 'white';
        document.body.appendChild(t);
        setTimeout(() => t.remove(), 3500);
    }

    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) modal.style.display = 'flex';
    }

    window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) modal.style.display = 'none';
    };

    function escapeHtml(str) {
        return String(str || '').replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }

    // ════════════════════════════════════════════════════════════
    //  CHARGEMENT DES TEMPLATES
    // ════════════════════════════════════════════════════════════

async function loadTemplates() {
    try {
        const response = await fetch('/admin/api/pdf-templates', {
            headers: { 'X-CSRF-TOKEN': CSRF }
        });
        allTemplates = await response.json();

        const container = document.getElementById('templatesList');

        if (!allTemplates.length) {
            container.innerHTML = `
                <div style="text-align: center; padding: 40px; color: var(--text3);">
                    📄 Aucun template PDF<br>
                    <small>Cliquez sur "Nouveau template" pour commencer</small>
                </div>`;
            return;
        }

        // Use the new card renderer
        container.innerHTML = allTemplates.map(tpl =>
            updateTemplateCardWithSource(tpl)
        ).join('');

    } catch (err) {
        console.error('Error loading templates:', err);
        document.getElementById('templatesList').innerHTML = '<div style="padding: 20px; color: var(--red);">Erreur de chargement</div>';
    }
}
// ════════════════════════════════════════════════════════════
//  ORIGINAL PDF PREVIEW & DOWNLOAD
// ════════════════════════════════════════════════════════════

window.previewOriginalPdf = async function() {
    if (!currentTemplateId) {
        toast('Aucun template sélectionné', 'error');
        return;
    }

    try {
        window.open(`/admin/api/pdf-templates/${currentTemplateId}/preview-original`, '_blank');
    } catch (err) {
        console.error(err);
        toast('Impossible d\'ouvrir l\'aperçu du PDF original', 'error');
    }
};

window.downloadOriginalFile = async function() {
    if (!currentTemplateId) {
        toast('Aucun template sélectionné', 'error');
        return;
    }

    try {
        window.open(`/admin/api/pdf-templates/${currentTemplateId}/download-source`, '_blank');
    } catch (err) {
        console.error(err);
        toast('Échec du téléchargement du fichier original', 'error');
    }
};
    // ════════════════════════════════════════════════════════════
    //  SELECTION D'UN TEMPLATE
    // ════════════════════════════════════════════════════════════

    window.selectTemplate = async function(templateId) {
        const template = allTemplates.find(t => t.id === templateId);
        if (!template) return;

        currentTemplateId = templateId;
        currentTemplate = template;

        document.querySelectorAll('.template-card').forEach(card => {
            card.classList.remove('active');
        });

        const clickedCard = document.querySelector(`.template-card:has(button[onclick*="${templateId}"])`);
        if (clickedCard) clickedCard.classList.add('active');

        document.getElementById('currentTemplateName').textContent = template.name;
        document.getElementById('currentTemplateKey').textContent = template.template_key;

        await initDesigner(template);
    };
// ════════════════════════════════════════════════════════════
//  HELPERS
// ════════════════════════════════════════════════════════════

// Fields that must keep their content (static/readOnly display values)
// Only wipe content on truly dynamic input fields
function sanitizeSchemas(schemas) {
    return schemas.map(page =>
        page.map(field => {
            const f = { ...field };

            // Fix nulls that crash pdfme
            if (f.backgroundColor === null) f.backgroundColor = '';
            if (f.fontName === null) delete f.fontName;

            // Per-type null fixes
            if (f.type === 'table') {
                if (f.headStyles?.backgroundColor === null) f.headStyles = { ...f.headStyles, backgroundColor: '' };
                if (f.bodyStyles?.backgroundColor === null) f.bodyStyles = { ...f.bodyStyles, backgroundColor: '' };
                if (f.bodyStyles?.alternateBackgroundColor === null) f.bodyStyles = { ...f.bodyStyles, alternateBackgroundColor: '' };

                // Table content must be a valid JSON string of a 2D array
                if (!f.content || typeof f.content !== 'string') {
                    const cols = (f.head || []).length || 1;
                    f.content = JSON.stringify([Array(cols).fill('')]);
                } else {
                    // Validate it's parseable
                    try { JSON.parse(f.content); } catch {
                        const cols = (f.head || []).length || 1;
                        f.content = JSON.stringify([Array(cols).fill('')]);
                    }
                }
            }

            if (f.type === 'multiVariableText') {
                // Content must be a valid JSON object string of variable values
                if (!f.content || typeof f.content !== 'string') {
                    const defaults = {};
                    (f.variables || []).forEach(v => { defaults[v] = ''; });
                    f.content = JSON.stringify(defaults);
                } else {
                    try { JSON.parse(f.content); } catch {
                        const defaults = {};
                        (f.variables || []).forEach(v => { defaults[v] = ''; });
                        f.content = JSON.stringify(defaults);
                    }
                }
            }

            if (f.type === 'svg') {
                // SVG content must be a valid SVG string
                if (!f.content || typeof f.content !== 'string' || !f.content.trim().startsWith('<')) {
                    f.content = '';
                }
            }

            if (f.type === 'line') {
                f.content = '';
            }

            // text fields: keep content exactly as stored — readOnly fields use it
            // as static display text / expressions, editable fields use it as default

            return f;
        })
    );
}

// ════════════════════════════════════════════════════════════
//  basePdf strategy
// ════════════════════════════════════════════════════════════

function resolveBasePdf(storedBasePdf) {
    if (!storedBasePdf) {
        // Brand new template
        return { width: 210, height: 297, padding: [20, 20, 20, 20] };
    }

    if (typeof storedBasePdf === 'object' && storedBasePdf.width) {
        // Already the correct object format — use as-is (preserves staticSchema too)
        return storedBasePdf;
    }

    // It's a base64 string from an old save — fall back to A4 object
    // Note: fields positioned against the old PDF may be slightly off,
    // but this is unavoidable without the original PDF
    return { width: 210, height: 297, padding: [20, 20, 20, 20] };
}

// Build the basePdf object — always use dimension object, never base64
// Preserve staticSchema if the original template had one
function buildBasePdf(storedTemplate) {
    const { BLANK_PDF_DIMENSIONS } = { BLANK_PDF_DIMENSIONS: { width: 210, height: 297, padding: [20, 20, 20, 20] } };

    // If the stored basePdf is already an object (not a string), use it
    const stored = storedTemplate?.basePdf;
    if (stored && typeof stored === 'object' && stored.width) {
        // It's already in the correct format — preserve staticSchema too
        return stored;
    }

    // Otherwise fall back to A4 dimensions
    return { width: 210, height: 297, padding: [20, 20, 20, 20] };
}

// ════════════════════════════════════════════════════════════
//  INITIALISATION DU DESIGNER PDFME
// ════════════════════════════════════════════════════════════


async function initDesigner(template) {
    const { Designer } = await import('@pdfme/ui');
    const { BLANK_PDF } = await import('@pdfme/common');
    const { text, line, rectangle, ellipse, image, svg, table, multiVariableText } = await import('@pdfme/schemas');

    const plugins = {
        Text: text,
        Line: line,
        Rectangle: rectangle,
        Ellipse: ellipse,
        Image: image,
        SVG: svg,
        Table: table,
        MultiVariableText: multiVariableText,
    };

    let pdfmeTemplate;

    if (template?.pdfme_template) {
        const stored = template.pdfme_template;

        pdfmeTemplate = {
            basePdf: buildBasePdf(stored),
            schemas: sanitizeSchemas(stored.schemas || [[]]),
        };
    } else {
        // Brand new blank template
        pdfmeTemplate = {
            basePdf: { width: 210, height: 297, padding: [20, 20, 20, 20] },
            schemas: [[
                {
                    name: 'title',
                    type: 'text',
                    position: { x: 20, y: 20 },
                    width: 170,
                    height: 15,
                    fontSize: 18,
                    fontColor: '#c9a84c',
                    readOnly: false,
                    content: '',
                },
                {
                    name: 'content',
                    type: 'text',
                    position: { x: 20, y: 50 },
                    width: 170,
                    height: 60,
                    fontSize: 10,
                    readOnly: false,
                    content: '',
                },
                {
                    name: 'generated_at',
                    type: 'text',
                    position: { x: 20, y: 270 },
                    width: 100,
                    height: 10,
                    fontSize: 8,
                    readOnly: false,
                    content: '',
                }
            ]]
        };
    }

    if (pdfDesigner) {
        pdfDesigner.destroy();
        pdfDesigner = null;
    }

    pdfDesigner = new Designer({
        domContainer: document.getElementById('pdf-designer'),
        template: pdfmeTemplate,
        plugins,
    });
}


// ════════════════════════════════════════════════════════════
//  CRÉATION D'UN NOUVEAU TEMPLATE  — name modal FIRST
// ════════════════════════════════════════════════════════════

window.createNewTemplate = function() {
    currentTemplate = null;
    currentTemplateId = null;

    document.getElementById('templateName').value = '';
    document.getElementById('templateKey').value = '';
    document.getElementById('templateDescription').value = '';
    document.getElementById('templatePageSize').value = 'A4';

    openModal('modal-template-props');
};

// ✅ FIX 3: Don't call initDesigner(null) — pass currentTemplate so it keeps the blank canvas
window.saveTemplateProperties = function() {
    const name = document.getElementById('templateName').value.trim();
    const key = document.getElementById('templateKey').value.trim();
    const description = document.getElementById('templateDescription').value;
    const pageSize = document.getElementById('templatePageSize').value;

    if (!name || !key) {
        toast('Veuillez remplir le nom et la clé', 'error');
        return;
    }

    currentTemplate = {
        name,
        template_key: key,
        description,
        page_size: pageSize,
        pdfme_template: null
    };

    document.getElementById('currentTemplateName').textContent = name;
    document.getElementById('currentTemplateKey').textContent = key;

    closeModal('modal-template-props');

    // ✅ Only init designer if it's not already running (new template scenario)
    if (!pdfDesigner) {
        initDesigner(null);
    }

    toast(`Nouveau template "${name}" créé. Commencez votre design !`, 'success');
};



    window.saveTemplateProperties = function() {
        const name = document.getElementById('templateName').value.trim();
        const key = document.getElementById('templateKey').value.trim();
        const description = document.getElementById('templateDescription').value;
        const pageSize = document.getElementById('templatePageSize').value;

        if (!name || !key) {
            toast('Veuillez remplir le nom et la clé', 'error');
            return;
        }

        currentTemplate = {
            name: name,
            template_key: key,
            description: description,
            page_size: pageSize,
            pdfme_template: null
        };

        closeModal('modal-template-props');
        initDesigner(null);
        toast(`Nouveau template "${name}" créé. Commencez votre design !`, 'success');
    };

    // ════════════════════════════════════════════════════════════
    //  SAUVEGARDE DU TEMPLATE
    // ════════════════════════════════════════════════════════════

// ════════════════════════════════════════════════════════════
//  SAUVEGARDE — prompt for name if not set yet
// ════════════════════════════════════════════════════════════

window.saveCurrentTemplate = async function() {
    if (!pdfDesigner) {
        toast('Aucun designer actif', 'error');
        return;
    }

    // ✅ FIX 4: If template has no name yet, open the modal first, then save after
    if (!currentTemplate?.name) {
        pendingSave = true;   // flag — see saveTemplateProperties below
        openModal('modal-template-props');
        return;
    }

    await doSave();
};

let pendingSave = false;

// Patch saveTemplateProperties to handle the pending save flow
const _origSaveProps = window.saveTemplateProperties;
window.saveTemplateProperties = function() {
    _origSaveProps();
    if (pendingSave) {
        pendingSave = false;
        // Give the modal close animation a tick
        setTimeout(() => doSave(), 100);
    }
};

async function doSave() {
    const rawTemplate = pdfDesigner.getTemplate();

    // Save schemas + basePdf as returned by designer
    // Designer always returns basePdf as the object we gave it
    const templateToSave = {
        schemas: rawTemplate.schemas,
        basePdf: rawTemplate.basePdf,  // keep exactly what the designer has
    };

    const data = {
        name:            currentTemplate?.name         || 'Template sans nom',
        template_key:    currentTemplate?.template_key || 'temp_' + Date.now(),
        description:     currentTemplate?.description  || '',
        page_size:       currentTemplate?.page_size    || 'A4',
        pdfme_template:  templateToSave
    };

    try {
        const url     = currentTemplateId ? `/admin/api/pdf-templates/${currentTemplateId}` : '/admin/api/pdf-templates';
        const method  = currentTemplateId ? 'PUT' : 'POST';
        const response = await fetch(url, {
            method,
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify(data)
        });

        const result = await response.json();
        if (result.success) {
            toast('✅ Template sauvegardé avec succès', 'success');
            currentTemplateId = result.template.id;
            currentTemplate = { ...currentTemplate, ...result.template };
            await loadTemplates();
        } else {
            toast('❌ Erreur: ' + (result.error || 'Erreur inconnue'), 'error');
        }
    } catch (err) {
        console.error('Save error:', err);
        toast('❌ Erreur lors de la sauvegarde', 'error');
    }
}



    // ════════════════════════════════════════════════════════════
    //  APERÇU DU PDF
    // ════════════════════════════════════════════════════════════


// ════════════════════════════════════════════════════════════
//  APERÇU — inputs use field names, not content on schema
// ════════════════════════════════════════════════════════════



window.previewPdf = async function() {
    if (!pdfDesigner) { toast('Aucun template actif', 'error'); return; }

    const template = pdfDesigner.getTemplate();
    const { generate } = await import('@pdfme/generator');
    const { text, line, rectangle, ellipse, image, svg, table, multiVariableText } = await import('@pdfme/schemas');

    // Build one sample input row covering all non-readOnly fields
    const fields = template.schemas?.[0] || [];
    const inputRow = {};
    fields.forEach(f => {
        if (f.readOnly) return;
        if (f.type === 'multiVariableText') {
            try {
                const vars = JSON.parse(f.content || '{}');
                Object.keys(vars).forEach(k => { if (!vars[k]) vars[k] = `[${k}]`; });
                inputRow[f.name] = JSON.stringify(vars);
            } catch { inputRow[f.name] = f.content || ''; }
        } else if (f.type === 'table') {
            inputRow[f.name] = f.content || JSON.stringify([Array((f.head||[]).length).fill('—')]);
        } else {
            inputRow[f.name] = `[${f.name}]`;
        }
    });

    try {
        const pdf = await generate({
            template,
            inputs: [inputRow],
            plugins: { text, line, rectangle, ellipse, image, svg, table, multiVariableText }
        });
        const blob = new Blob([pdf], { type: 'application/pdf' });
        window.open(URL.createObjectURL(blob));
    } catch (err) {
        console.error('Preview error:', err);
        toast('Erreur aperçu: ' + err.message, 'error');
    }
};

    // ════════════════════════════════════════════════════════════
    //  SUPPRESSION
    // ════════════════════════════════════════════════════════════

    window.deleteTemplate = async function(templateId) {
        if (!confirm('Supprimer ce template définitivement ?')) return;

        try {
            const response = await fetch(`/admin/api/pdf-templates/${templateId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF }
            });

            const result = await response.json();

            if (result.success) {
                toast('✅ Template supprimé', 'success');
                await loadTemplates();
                if (currentTemplateId === templateId) {
                    currentTemplate = null;
                    currentTemplateId = null;
                    document.getElementById('currentTemplateName').textContent = 'Aucun template sélectionné';
                }
            } else {
                toast('❌ Erreur: ' + result.error, 'error');
            }

        } catch (err) {
            toast('❌ Erreur lors de la suppression', 'error');
        }
    };

    window.deleteCurrentTemplate = function() {
        if (currentTemplateId) {
            deleteTemplate(currentTemplateId);
        }
    };

    // ════════════════════════════════════════════════════════════
    //  IMPORT/EXPORT
    // ════════════════════════════════════════════════════════════

    window.exportTemplate = function() {
        if (!pdfDesigner) return;

        const template = pdfDesigner.getTemplate();
        const dataStr = JSON.stringify(template, null, 2);
        const blob = new Blob([dataStr], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `template_${currentTemplate?.template_key || 'export'}.json`;
        a.click();
        URL.revokeObjectURL(url);
        toast('Template exporté', 'success');
    };

// ════════════════════════════════════════════════════════════
//  IMPORT — handle both pdfme export formats
// ════════════════════════════════════════════════════════════

window.importTemplate = function() {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = '.json';
    input.onchange = async (e) => {
        const file = e.target.files[0];
        const fileText = await file.text();
        try {
            const imported = JSON.parse(fileText);

            // DON'T convert basePdf here — keep whatever the file has.
            // If it's a real PDF base64, the designer will use it.
            // The warning about page breaks is expected and harmless.
            if (imported.schemas) {
                imported.schemas = sanitizeSchemas(imported.schemas);
            }

            if (!pdfDesigner) {
                toast('Veuillez d\'abord créer ou sélectionner un template', 'error');
                return;
            }

            pdfDesigner.updateTemplate(imported);
            toast('Template importé avec succès', 'success');
        } catch (err) {
            console.error('Import error:', err);
            toast('Fichier invalide: ' + err.message, 'error');
        }
    };
    input.click();
};


    window.duplicateTemplate = function(templateId) {
        const template = allTemplates.find(t => t.id === templateId);
        if (template) {
            document.getElementById('templateName').value = template.name + ' (copie)';
            document.getElementById('templateKey').value = template.template_key + '_copy';
            document.getElementById('templateDescription').value = template.description;
            openModal('modal-template-props');
        }
    };

    // Initialisation
    document.addEventListener('DOMContentLoaded', () => {
        loadTemplates();
        initDesigner(null);
    });


    // ════════════════════════════════════════════════════════════
//  NEW: FILE IMPORT (PDF/DOCX → Editable Template)
// ════════════════════════════════════════════════════════════

window.openFileImportModal = function() {
    // Add modal if not exists
    if (!document.getElementById('modal-file-import')) {
        // Note: We already added the HTML statically, so this is just a safety check
        console.log('Modal should already exist');
    }

    // Clear form
    document.getElementById('importTemplateName').value = '';
    document.getElementById('importTemplateKey').value = '';
    document.getElementById('importTemplateDescription').value = '';
    document.getElementById('importPageSize').value = 'A4';
    document.getElementById('importFileInput').value = '';

    openModal('modal-file-import');
};

window.uploadAndImportFile = async function() {
    const name = document.getElementById('importTemplateName').value.trim();
    const key = document.getElementById('importTemplateKey').value.trim();
    const description = document.getElementById('importTemplateDescription').value;
    const pageSize = document.getElementById('importPageSize').value;
    const fileInput = document.getElementById('importFileInput');

    if (!name || !key) {
        toast('Please provide name and key', 'error');
        return;
    }

    if (!fileInput.files || !fileInput.files[0]) {
        toast('Please select a PDF or DOCX file', 'error');
        return;
    }

    const file = fileInput.files[0];
    const formData = new FormData();
    formData.append('file', file);
    formData.append('name', name);
    formData.append('template_key', key);
    formData.append('description', description);
    formData.append('page_size', pageSize);

    toast('Uploading and converting file...', 'info');

    try {
        const response = await fetch('/admin/api/pdf-templates/import-file', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF
            },
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            toast('File imported and converted successfully!', 'success');
            closeModal('modal-file-import');
            await loadTemplates();

            if (result.template && result.template.id) {
                await selectTemplate(result.template.id);
            }
        } else {
            toast('Error: ' + (result.error || 'Unknown error'), 'error');
        }
    } catch (err) {
        console.error('Import error:', err);
        toast('Failed to import file: ' + err.message, 'error');
    }
};

window.downloadSourceFile = async function(templateId) {
    try {
        window.open(`/admin/api/pdf-templates/${templateId}/download-source`, '_blank');
    } catch (err) {
        toast('Failed to download source file', 'error');
    }
};

// Enhanced template card with "Download Original" button
function updateTemplateCardWithSource(template) {
    const hasSource = !!template.source_file_type;
    return `
        <div class="template-card ${currentTemplateId === template.id ? 'active' : ''}"
             onclick="selectTemplate(${template.id})">
            <div class="template-name">${escapeHtml(template.name)}</div>
            <div class="template-key">${escapeHtml(template.template_key)}</div>
            ${template.description ? `<div class="template-description">${escapeHtml(template.description.substring(0, 60))}${template.description.length > 60 ? '...' : ''}</div>` : ''}
            <div class="template-actions">
                ${hasSource ? `<button class="btn-ghost" style="padding: 4px 8px; font-size: 11px;" onclick="event.stopPropagation(); downloadSourceFile(${template.id})">📥 Original</button>` : ''}
                <button class="btn-ghost" style="padding: 4px 8px; font-size: 11px;" onclick="event.stopPropagation(); duplicateTemplate(${template.id})">📋 Dupliquer</button>
                <button class="btn-ghost" style="padding: 4px 8px; font-size: 11px; color: #f87171;" onclick="event.stopPropagation(); deleteTemplate(${template.id})">🗑️</button>
            </div>
        </div>
    `;
}
window.openHtmlBuilder = function() {
    window.location.href = '/admin/pdf-templates/builder';
};

// Also update the template card to have an "Edit with HTML Builder" button
function updateTemplateCardWithSource(template) {
    const hasSource = !!template.source_file_type;
    return `
        <div class="template-card ${currentTemplateId === template.id ? 'active' : ''}"
             onclick="selectTemplate(${template.id})">
            <div class="template-name">${escapeHtml(template.name)}</div>
            <div class="template-key">${escapeHtml(template.template_key)}</div>
            ${template.description ? `<div class="template-description">${escapeHtml(template.description.substring(0, 60))}${template.description.length > 60 ? '...' : ''}</div>` : ''}
            <div class="template-actions">
                <button class="btn-ghost" style="padding: 4px 8px; font-size: 11px;" onclick="event.stopPropagation(); editWithHtmlBuilder(${template.id})">🎨 HTML Builder</button>
                ${hasSource ? `<button class="btn-ghost" style="padding: 4px 8px; font-size: 11px;" onclick="event.stopPropagation(); downloadSourceFile(${template.id})">📥 Original</button>` : ''}
                <button class="btn-ghost" style="padding: 4px 8px; font-size: 11px;" onclick="event.stopPropagation(); duplicateTemplate(${template.id})">📋 Dupliquer</button>
                <button class="btn-ghost" style="padding: 4px 8px; font-size: 11px; color: #f87171;" onclick="event.stopPropagation(); deleteTemplate(${template.id})">🗑️</button>
            </div>
        </div>
    `;
}

window.editWithHtmlBuilder = function(templateId) {
    window.location.href = `/admin/pdf-templates/builder/${templateId}`;
};

window.openSimpleBuilder = function() {
    window.location.href = '/admin/pdf-templates/simple-builder';
};
</script>
@endsection
