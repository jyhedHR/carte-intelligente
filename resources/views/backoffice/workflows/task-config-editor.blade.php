{{-- Task Configuration Editor Popup --}}
<div id="taskConfigModal" class="modal modal-hidden" style="display:none;">
    <div class="modal-overlay" onclick="closeTaskConfigModal()"></div>
    <div class="modal-content" style="max-width: 700px;">
        <div class="modal-header">
            <h3>🎨 Configurer la Tâche Personnalisée</h3>
            <button class="btn-close" onclick="closeTaskConfigModal()">✕</button>
        </div>

        <div class="modal-body" style="max-height: 600px; overflow-y: auto;">
            <form id="taskConfigForm">
                {{-- Task Identity --}}
                <div class="config-section">
                    <h4 class="section-title">📋 Identité de la Tâche</h4>
                    <input type="hidden" id="taskConfigId" />
                    <input type="hidden" id="taskId" />

                    <div class="form-group">
                        <label>Nom de la Tâche</label>
                        <input type="text" id="taskName" class="form-control"
                               placeholder="Ex: Validation du Directeur" required>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea id="taskDescription" class="form-control"
                                  placeholder="Instructions détaillées pour les gestionnaires..."
                                  rows="4"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Rôles Autorisés</label>
                        <div class="role-checkboxes">
                            <label class="checkbox-label">
                                <input type="checkbox" name="roles" value="manager" checked> Gestionnaire
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="roles" value="director" checked> Directeur
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="roles" value="admin"> Administrateur
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Custom Actions --}}
                <div class="config-section">
                    <div class="section-header">
                        <h4 class="section-title">⚡ Actions Personnalisées</h4>
                        <button type="button" class="btn btn-sm btn-gold" onclick="addCustomAction()">
                            + Ajouter une action
                        </button>
                    </div>

                    <div id="customActionsList">
                        {{-- Actions will be added here --}}
                    </div>
                </div>

                {{-- Custom Form Fields --}}
                <div class="config-section">
                    <div class="section-header">
                        <h4 class="section-title">📝 Champs Personnalisés</h4>
                        <button type="button" class="btn btn-sm btn-gold" onclick="addCustomField()">
                            + Ajouter un champ
                        </button>
                    </div>

                    <div id="customFieldsList">
                        {{-- Fields will be added here --}}
                    </div>
                </div>
            </form>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-ghost" onclick="closeTaskConfigModal()">Annuler</button>
            <button type="button" class="btn btn-gold" onclick="saveTaskConfig()">✓ Enregistrer</button>
        </div>
    </div>
</div>

<style>
.modal {
    position: fixed;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-hidden {
    display: none !important;
}

.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    z-index: -1;
}

.modal-content {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: 12px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 20px 60px rgba(0,0,0,0.4);
}

.modal-header, .modal-footer {
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.modal-footer {
    border-bottom: none;
    border-top: 1px solid var(--border);
    gap: 8px;
}

.modal-header h3 {
    margin: 0;
    color: var(--text);
    font-size: 16px;
    flex: 1;
}

.modal-body {
    padding: 20px;
    flex: 1;
    overflow-y: auto;
}

.btn-close {
    background: none;
    border: none;
    color: var(--text3);
    font-size: 20px;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-close:hover {
    color: var(--text);
}

.config-section {
    margin-bottom: 24px;
    padding: 16px;
    background: var(--bg3);
    border: 1px solid var(--border);
    border-radius: 8px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.section-title {
    margin: 0;
    font-size: 13px;
    font-weight: 700;
    color: var(--gold);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.form-group {
    margin-bottom: 12px;
}

.form-group:last-child {
    margin-bottom: 0;
}

.form-group label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--text2);
    margin-bottom: 6px;
}

.form-control {
    width: 100%;
    padding: 8px 10px;
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: 6px;
    color: var(--text);
    font-size: 13px;
    font-family: var(--font-body);
}

.form-control:focus {
    outline: none;
    border-color: var(--gold);
}

.role-checkboxes {
    display: flex;
    gap: 12px;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    font-size: 12px;
    color: var(--text2);
}

.checkbox-label input[type="checkbox"] {
    margin: 0;
    width: 16px;
    height: 16px;
    cursor: pointer;
}

.action-item, .field-item {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: 6px;
    padding: 12px;
    margin-bottom: 8px;
    display: flex;
    gap: 8px;
}

.action-item > div, .field-item > div {
    flex: 1;
}

.action-item input, .field-item input, .action-item select, .field-item select {
    width: 100%;
    padding: 6px 8px;
    background: var(--bg3);
    border: 1px solid var(--border);
    border-radius: 4px;
    color: var(--text);
    font-size: 12px;
    margin-bottom: 6px;
}

.action-item input:last-child, .field-item input:last-child,
.action-item select:last-child, .field-item select:last-child {
    margin-bottom: 0;
}

.btn-remove {
    background: rgba(248,113,113,0.15);
    border: 1px solid rgba(248,113,113,0.3);
    color: #f87171;
    padding: 6px 8px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
    font-weight: 600;
    align-self: flex-start;
}

.btn-remove:hover {
    background: rgba(248,113,113,0.25);
}

.btn {
    padding: 8px 14px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: all 0.15s;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 11px;
}

.btn-gold {
    background: var(--gold);
    color: #111;
}

.btn-gold:hover {
    opacity: 0.9;
}

.btn-ghost {
    background: var(--bg3);
    border: 1px solid var(--border);
    color: var(--text);
}

.btn-ghost:hover {
    border-color: var(--gold);
    color: var(--gold);
}
</style>

<script>
let customActionsData = [];
let customFieldsData = [];

function openTaskConfigModal(taskId, taskName = 'Unnamed Task') {
    document.getElementById('taskId').value = taskId;
    document.getElementById('taskName').value = taskName;
    document.getElementById('taskConfigModal').style.display = 'flex';

    // Load existing config
    loadTaskConfig(taskId);
}

function closeTaskConfigModal() {
    document.getElementById('taskConfigModal').style.display = 'none';
    customActionsData = [];
    customFieldsData = [];
}

function loadTaskConfig(taskId) {
    fetch(`/api/workflows/task-configs/${taskId}`)
        .then(res => res.json())
        .then(data => {
            if (data.success && data.data) {
                document.getElementById('taskName').value = data.data.task_name || '';
                document.getElementById('taskDescription').value = data.data.description || '';

                customActionsData = data.data.custom_actions || [];
                customFieldsData = data.data.custom_fields || [];

                renderCustomActions();
                renderCustomFields();

                // Set role checkboxes
                const roles = data.data.required_for_roles || [];
                document.querySelectorAll('input[name="roles"]').forEach(cb => {
                    cb.checked = roles.includes(cb.value);
                });
            }
        })
        .catch(err => console.error('Error loading task config:', err));
}

function addCustomAction() {
    customActionsData.push({
        name: `action_${Date.now()}`,
        label: '',
        type: 'custom',
        color: 'blue'
    });
    renderCustomActions();
}

function renderCustomActions() {
    const container = document.getElementById('customActionsList');
    container.innerHTML = customActionsData.map((action, idx) => `
        <div class="action-item">
            <div>
                <input type="text" placeholder="Nom de l'action"
                       value="${action.name}"
                       onchange="customActionsData[${idx}].name = this.value">
                <input type="text" placeholder="Étiquette (ex: Approuver)"
                       value="${action.label}"
                       onchange="customActionsData[${idx}].label = this.value">
                <select onchange="customActionsData[${idx}].type = this.value">
                    <option value="approve" ${action.type === 'approve' ? 'selected' : ''}>Approuver</option>
                    <option value="reject" ${action.type === 'reject' ? 'selected' : ''}>Rejeter</option>
                    <option value="custom" ${action.type === 'custom' ? 'selected' : ''}>Personnalisé</option>
                </select>
            </div>
            <button type="button" class="btn-remove" onclick="customActionsData.splice(${idx}, 1); renderCustomActions();">
                ✕ Supprimer
            </button>
        </div>
    `).join('');
}

function addCustomField() {
    customFieldsData.push({
        name: `field_${Date.now()}`,
        label: '',
        type: 'text',
        required: false
    });
    renderCustomFields();
}

function renderCustomFields() {
    const container = document.getElementById('customFieldsList');
    container.innerHTML = customFieldsData.map((field, idx) => `
        <div class="field-item">
            <div>
                <input type="text" placeholder="Nom du champ"
                       value="${field.name}"
                       onchange="customFieldsData[${idx}].name = this.value">
                <input type="text" placeholder="Étiquette (ex: Commentaire)"
                       value="${field.label}"
                       onchange="customFieldsData[${idx}].label = this.value">
                <select onchange="customFieldsData[${idx}].type = this.value">
                    <option value="text" ${field.type === 'text' ? 'selected' : ''}>Texte</option>
                    <option value="textarea" ${field.type === 'textarea' ? 'selected' : ''}>Texte long</option>
                    <option value="select" ${field.type === 'select' ? 'selected' : ''}>Sélection</option>
                    <option value="checkbox" ${field.type === 'checkbox' ? 'selected' : ''}>Case à cocher</option>
                    <option value="date" ${field.type === 'date' ? 'selected' : ''}>Date</option>
                </select>
                <label class="checkbox-label">
                    <input type="checkbox" ${field.required ? 'checked' : ''}
                           onchange="customFieldsData[${idx}].required = this.checked">
                    Obligatoire
                </label>
            </div>
            <button type="button" class="btn-remove" onclick="customFieldsData.splice(${idx}, 1); renderCustomFields();">
                ✕ Supprimer
            </button>
        </div>
    `).join('');
}

function saveTaskConfig() {
    const roles = Array.from(document.querySelectorAll('input[name="roles"]:checked'))
        .map(cb => cb.value);

    const payload = {
        task_id: document.getElementById('taskId').value,
        task_name: document.getElementById('taskName').value,
        description: document.getElementById('taskDescription').value,
        custom_actions: customActionsData,
        custom_fields: customFieldsData,
        required_for_roles: roles
    };

    fetch('/api/workflows/task-configs', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast('✓ Configuration sauvegardée', 'success');
            closeTaskConfigModal();
        } else {
            showToast('✕ Erreur: ' + (data.error || 'Impossible de sauvegarder'), 'error');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showToast('✕ Erreur réseau', 'error');
    });
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `bpmn-toast ${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
}
</script>
