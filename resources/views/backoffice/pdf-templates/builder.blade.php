@extends('shared.layouts.backoffice')

@section('title', 'Simple PDF Template Builder')
@section('breadcrumb', 'Configuration / Templates PDF / Simple Builder')

@section('content')
<style>
    .simple-builder-container {
        display: flex;
        gap: 20px;
        height: calc(100vh - 140px);
    }

    .editor-panel {
        flex: 2;
        background: var(--bg2);
        border-radius: 12px;
        border: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .fields-panel {
        flex: 1;
        background: var(--bg2);
        border-radius: 12px;
        border: 1px solid var(--border);
        display: flex;
        flex-direction: column;
    }

    .panel-header {
        padding: 16px;
        border-bottom: 1px solid var(--border);
        background: var(--bg3);
    }

    .panel-header h3 {
        margin: 0;
        font-size: 16px;
    }

    .panel-body {
        padding: 16px;
        flex: 1;
        overflow-y: auto;
    }

    .template-editor {
        width: 100%;
        height: 100%;
        min-height: 500px;
        padding: 16px;
        font-family: monospace;
        font-size: 14px;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: var(--bg3);
        color: var(--text);
        resize: vertical;
    }

    .field-card {
        background: var(--bg3);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .field-card:hover {
        border-color: var(--gold);
        transform: translateX(4px);
    }

    .field-name {
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 4px;
    }

    .field-code {
        font-family: monospace;
        font-size: 11px;
        color: var(--gold);
        background: rgba(201,168,76,0.1);
        padding: 4px 6px;
        border-radius: 4px;
        display: inline-block;
    }

    .preview-area {
        margin-top: 16px;
        padding: 16px;
        background: white;
        border-radius: 8px;
        color: #333;
        max-height: 400px;
        overflow-y: auto;
    }

    .btn-group {
        display: flex;
        gap: 10px;
        margin-top: 16px;
    }

    .template-name-input {
        width: 100%;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid var(--border);
        background: var(--bg3);
        color: var(--text);
        margin-bottom: 16px;
    }
</style>

<div class="simple-builder-container">
    <!-- Editor Panel -->
    <div class="editor-panel">
        <div class="panel-header">
            <h3>✏️ Template Content Editor</h3>
        </div>
        <div class="panel-body">
            <input type="text" id="template-name" class="template-name-input" placeholder="Template Name (e.g., Official Certificate)" value="{{ $template->name ?? '' }}">
            <textarea id="template-content" class="template-editor" placeholder="Write your template content here...

Example:
========================================
        CERTIFICATE OF ACKNOWLEDGMENT
========================================

This is to certify that @{{user.name}}
with Social Security Number @{{user.ssn}}
is hereby recognized.

Date: @{{current_date}}
Signature: ___________________

========================================">{{ $template->html_content ?? '' }}</textarea>

            <div class="btn-group">
                <button class="btn btn-gold" onclick="saveTemplate()">💾 Save Template</button>
                <button class="btn btn-ghost" onclick="previewTemplate()">👁️ Preview PDF</button>
                <button class="btn btn-ghost" onclick="window.location.href='{{ route('admin.pdf-templates.index') }}'">← Back</button>
            </div>

            <div class="preview-area" id="live-preview">
                <strong>Live Preview:</strong>
                <div id="preview-content" style="margin-top: 10px; white-space: pre-wrap;"></div>
            </div>
        </div>
    </div>

    <!-- Fields Panel -->
    <div class="fields-panel">
        <div class="panel-header">
            <h3>📋 Available Fields (Click to insert)</h3>
        </div>
        <div class="panel-body">
            <div class="field-card" onclick="insertField('{{'user.name'}}')">
                <div class="field-name">👤 User Full Name</div>
                <div class="field-code">@{{'user.name'}}</div>
            </div>
            <div class="field-card" onclick="insertField('@{{'user.email'}}')">
                <div class="field-name">📧 Email Address</div>
                <div class="field-code">@{{'user.email'}}</div>
            </div>
            <div class="field-card" onclick="insertField('@{{'user.phone'}}')">
                <div class="field-name">📱 Phone Number</div>
                <div class="field-code">@{{'user.phone'}}</div>
            </div>
            <div class="field-card" onclick="insertField('{{'user.ssn'}}')">
                <div class="field-name">🆔 Social Security Number</div>
                <div class="field-code">@{{'user.ssn'}}</div>
            </div>
            <div class="field-card" onclick="insertField('@{{'user.birthdate'}}')">
                <div class="field-name">🎂 Birth Date</div>
                <div class="field-code">@{{'user.birthdate'}}</div>
            </div>
            <div class="field-card" onclick="insertField('@{{'user.address'}}')">
                <div class="field-name">🏠 Address</div>
                <div class="field-code">@{{'user.address'}}</div>
            </div>
            <div class="field-card" onclick="insertField('@{{'user.department'}}')">
                <div class="field-name">🏢 Department</div>
                <div class="field-code">@{{'user.department'}}</div>
            </div>
            <div class="field-card" onclick="insertField('@{{'user.position'}}')">
                <div class="field-name">💼 Position</div>
                <div class="field-code">@{{'user.position'}}</div>
            </div>
            <div class="field-card" onclick="insertField('@{{'user.employee_id'}}')">
                <div class="field-name">🆔 Employee ID</div>
                <div class="field-code">@{{'user.employee_id'}}</div>
            </div>
            <div class="field-card" onclick="insertField('{{'current_date'}}')">
                <div class="field-name">📅 Current Date</div>
                <div class="field-code">@{{'current_date'}}</div>
            </div>
            <div class="field-card" onclick="insertField('{{'current_time'}}')">
                <div class="field-name">⏰ Current Time</div>
                <div class="field-code">@{{'current_time'}}</div>
            </div>
        </div>
    </div>
</div>

<script>
let currentTemplateId = {{ $template->id ?? 'null' }};
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

// Insert field at cursor position
function insertField(fieldName) {
    const textarea = document.getElementById('template-content');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const text = textarea.value;
    const fieldText = '{{' + fieldName + '}}';

    textarea.value = text.substring(0, start) + fieldText + text.substring(end);
    textarea.focus();
    textarea.setSelectionRange(start + fieldText.length, start + fieldText.length);

    updateLivePreview();
}

// Update live preview
function updateLivePreview() {
    let content = document.getElementById('template-content').value;
    // Show preview with sample data
    let preview = content
        .replace(/\{\{user\.name\}\}/g, '<span style="background:#e8f5e9; padding:2px 4px; border-radius:3px;">John Doe</span>')
        .replace(/\{\{user\.email\}\}/g, '<span style="background:#e8f5e9; padding:2px 4px; border-radius:3px;">john@example.com</span>')
        .replace(/\{\{user\.phone\}\}/g, '<span style="background:#e8f5e9; padding:2px 4px; border-radius:3px;">+216 12 345 678</span>')
        .replace(/\{\{user\.ssn\}\}/g, '<span style="background:#e8f5e9; padding:2px 4px; border-radius:3px;">123-45-6789</span>')
        .replace(/\{\{user\.birthdate\}\}/g, '<span style="background:#e8f5e9; padding:2px 4px; border-radius:3px;">1990-01-01</span>')
        .replace(/\{\{user\.address\}\}/g, '<span style="background:#e8f5e9; padding:2px 4px; border-radius:3px;">123 Main St, Tunis</span>')
        .replace(/\{\{user\.department\}\}/g, '<span style="background:#e8f5e9; padding:2px 4px; border-radius:3px;">Ministry of Culture</span>')
        .replace(/\{\{user\.position\}\}/g, '<span style="background:#e8f5e9; padding:2px 4px; border-radius:3px;">Senior Officer</span>')
        .replace(/\{\{user\.employee_id\}\}/g, '<span style="background:#e8f5e9; padding:2px 4px; border-radius:3px;">EMP001</span>')
        .replace(/\{\{current_date\}\}/g, '<span style="background:#e8f5e9; padding:2px 4px; border-radius:3px;">' + new Date().toLocaleDateString() + '</span>')
        .replace(/\{\{current_time\}\}/g, '<span style="background:#e8f5e9; padding:2px 4px; border-radius:3px;">' + new Date().toLocaleTimeString() + '</span>')
        .replace(/\n/g, '<br>');

    document.getElementById('preview-content').innerHTML = preview;
}

// Save template
async function saveTemplate() {
    const name = document.getElementById('template-name').value.trim();
    const content = document.getElementById('template-content').value;

    if (!name) {
        toast('Please enter a template name', 'error');
        return;
    }

    // Convert plain text to HTML with proper formatting
    const htmlContent = '<div style="font-family: Arial, sans-serif; padding: 40px; line-height: 1.6;">' +
        content.replace(/\n/g, '<br>').replace(/ /g, '&nbsp;') +
        '</div>';

    const data = {
        name: name,
        template_key: name.toLowerCase().replace(/[^a-z0-9]/g, '_'),
        html_content: htmlContent,
        page_size: 'A4',
        description: ''
    };

    try {
        let url, method;

        if (currentTemplateId && currentTemplateId !== 'null') {
            url = '/admin/api/pdf-templates/' + currentTemplateId;
            method = 'PUT';
        } else {
            url = '/admin/api/pdf-templates/html-template';
            method = 'POST';
        }

        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
            toast('Template saved successfully!', 'success');
            if (result.template && result.template.id) {
                currentTemplateId = result.template.id;
            }
        } else {
            toast('Error: ' + (result.error || 'Unknown error'), 'error');
        }
    } catch (err) {
        console.error('Save error:', err);
        toast('Failed to save template', 'error');
    }
}

// Preview PDF
async function previewTemplate() {
    if (!currentTemplateId || currentTemplateId === 'null') {
        toast('Please save the template first', 'error');
        return;
    }
    window.open('/admin/api/pdf-templates/' + currentTemplateId + '/preview', '_blank');
}

// Toast notification
function toast(msg, type) {
    const t = document.createElement('div');
    t.textContent = msg;
    t.style.cssText = 'position:fixed;bottom:20px;right:20px;padding:12px 18px;border-radius:8px;z-index:10000;background:' +
        (type === 'success' ? '#166534' : '#7f1d1d') + ';color:white;z-index:9999;';
    document.body.appendChild(t);
    setTimeout(function() { t.remove(); }, 3000);
}

// Live preview on input
document.getElementById('template-content').addEventListener('input', updateLivePreview);
document.getElementById('template-content').addEventListener('keyup', updateLivePreview);

// Initialize preview
updateLivePreview();
</script>
@endsection
