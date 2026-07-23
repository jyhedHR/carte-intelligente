{{-- resources/views/backoffice/allUsers/modals/create-admin-modal.blade.php --}}
{{-- Enhanced user-friendly admin creation modal --}}

<div id="createAdminModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="createAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{-- Header --}}
            <div class="modal-header bg-gradient-primary text-white border-0">
                <div>
                    <h5 class="modal-title" id="createAdminModalLabel">
                        <i class="fas fa-user-tie mr-2"></i>Créer un nouvel administrateur
                    </h5>
                    <small class="text-white-50">Remplissez les informations ci-dessous</small>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- Body --}}
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                <form id="createAdminForm" onsubmit="createAdminUser(event)">
                    @csrf

                    {{-- Progress Indicator --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="progress" style="height: 3px;">
                                <div class="progress-bar" id="formProgress" style="width: 0%;"></div>
                            </div>
                            <small class="text-muted">
                                Étape <span id="currentStep">1</span> sur 3
                            </small>
                        </div>
                    </div>

                    {{-- SECTION 1: Personal Information --}}
                    <div class="form-section mb-4">
                        <div class="section-header mb-3">
                            <h6 class="font-weight-bold text-dark mb-0">
                                <i class="fas fa-user-circle text-primary mr-2"></i>Informations personnelles
                            </h6>
                            <small class="text-muted">Champs marqués avec * sont obligatoires</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="adminPrenom" class="font-weight-600">Prénom *</label>
                                    <input
                                        type="text"
                                        id="adminPrenom"
                                        name="prenom"
                                        class="form-control form-control-lg"
                                        placeholder="Ex: Jean"
                                        required
                                        autocomplete="off"
                                    >
                                    <small class="form-text text-muted">Le prénom de l'administrateur</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="adminNom" class="font-weight-600">Nom *</label>
                                    <input
                                        type="text"
                                        id="adminNom"
                                        name="nom"
                                        class="form-control form-control-lg"
                                        placeholder="Ex: Dupont"
                                        required
                                        autocomplete="off"
                                    >
                                    <small class="form-text text-muted">Le nom de famille</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="adminEmail" class="font-weight-600">Email *</label>
                            <div class="input-group input-group-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0">
                                        <i class="fas fa-envelope text-muted"></i>
                                    </span>
                                </div>
                                <input
                                    type="email"
                                    id="adminEmail"
                                    name="email"
                                    class="form-control border-left-0"
                                    placeholder="jean.dupont@example.com"
                                    required
                                    autocomplete="off"
                                >
                            </div>
                            <small class="form-text text-muted">Cet email sera utilisé pour se connecter</small>
                            <div id="emailFeedback" class="mt-2"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="adminCin" class="font-weight-600">CIN/ID *</label>
                                    <input
                                        type="text"
                                        id="adminCin"
                                        name="cin"
                                        class="form-control form-control-lg"
                                        placeholder="12345678"
                                        required
                                        autocomplete="off"
                                    >
                                    <small class="form-text text-muted">Numéro d'identification unique</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="adminTelephone" class="font-weight-600">Téléphone</label>
                                    <div class="input-group input-group-lg">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0">
                                                <i class="fas fa-phone text-muted"></i>
                                            </span>
                                        </div>
                                        <input
                                            type="tel"
                                            id="adminTelephone"
                                            name="telephone"
                                            class="form-control border-left-0"
                                            placeholder="+216 50 000 000"
                                            autocomplete="off"
                                        >
                                    </div>
                                    <small class="form-text text-muted">Numéro de contact (optionnel)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 2: Department Assignment --}}
                    <div class="form-section mb-4">
                        <div class="section-header mb-3">
                            <h6 class="font-weight-bold text-dark mb-0">
                                <i class="fas fa-building text-info mr-2"></i>Direction
                            </h6>
                            <small class="text-muted">Assignez l'administrateur à une direction (optionnel)</small>
                        </div>

                        <div class="form-group">
                            <label for="adminDirection" class="font-weight-600">Sélectionner une direction</label>
                            <select
                                id="adminDirection"
                                name="id_direction"
                                class="form-control form-control-lg"
                            >
                                <option value="">-- Aucune direction assignée --</option>
                                <option value="1">🎵 Direction de la musique et danse</option>
                                <option value="2">📺 Direction des arts audio-visuels</option>
                                <option value="3">🎨 Direction des arts plastiques</option>
                                <option value="4">🎭 Direction des arts scéniques</option>
                                <option value="5">📚 Direction générale du Livre</option>
                                <option value="6">💼 Unité d'encadrement des investisseurs</option>
                            </select>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i>
                                Les administrateurs sans direction peuvent gérer tous les utilisateurs
                            </small>
                        </div>
                    </div>

                    {{-- SECTION 3: Admin Role --}}
                    <div class="form-section">
                        <div class="section-header mb-3">
                            <h6 class="font-weight-bold text-dark mb-0">
                                <i class="fas fa-crown text-warning mr-2"></i>Statut administrateur
                            </h6>
                            <small class="text-muted">Définir les permissions d'accès</small>
                        </div>

                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-lightbulb mr-2"></i>
                            <strong>Conseil:</strong> Les administrateurs ont accès aux fonctionnalités de gestion des utilisateurs
                            <button type="button" class="close" data-dismiss="alert" aria-label="Fermer">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="custom-control custom-switch custom-control-lg mb-3">
                            <input
                                type="checkbox"
                                class="custom-control-input"
                                id="adminIsAdmin"
                                onchange="toggleAdminRoles()"
                            >
                            <label class="custom-control-label font-weight-600" for="adminIsAdmin">
                                Accorder les droits administrateur
                            </label>
                        </div>

                        {{-- Admin Roles Section --}}
                        <div id="adminRolesSection" style="display: none;" class="mt-4">
                            <div class="alert alert-warning" role="alert">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong>Important:</strong> Sélectionnez au moins un rôle pour les administrateurs
                            </div>

                            <label class="font-weight-600 mb-3">Rôles à assigner *</label>
                            <div id="rolesContainer" class="roles-grid">
                                {{-- Populated by JavaScript --}}
                            </div>
                        </div>

                        {{-- Permissions Preview --}}
                        <div id="permissionsPreview" style="display: none;" class="mt-4">
                            <div class="card border-info bg-light">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-shield-alt mr-2"></i>Aperçu des permissions
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div id="selectedPermissions" class="permission-badges">
                                        <span class="badge badge-secondary">Aucune permission sélectionnée</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Validation Summary --}}
                    <div id="validationSummary" class="alert alert-danger mt-4" style="display: none;">
                        <h6 class="font-weight-bold mb-2">
                            <i class="fas fa-times-circle mr-2"></i>Erreurs de validation:
                        </h6>
                        <ul id="validationList" class="mb-0">
                            {{-- Populated by JavaScript --}}
                        </ul>
                    </div>

                </form>
            </div>

            {{-- Footer --}}
            <div class="modal-footer bg-light border-top">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal"
                >
                    <i class="fas fa-times mr-2"></i>Annuler
                </button>
                <button
                    type="submit"
                    form="createAdminForm"
                    class="btn btn-primary btn-lg"
                    id="submitBtn"
                >
                    <i class="fas fa-check mr-2"></i>Créer l'administrateur
                </button>
            </div>

        </div>
    </div>
</div>

{{-- Styles --}}
<style>
.form-section {
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 0.5rem;
    border-left: 4px solid #007bff;
}

.section-header {
    padding-bottom: 1rem;
    border-bottom: 2px solid #dee2e6;
}

.roles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1rem;
}

.role-option {
    position: relative;
    padding: 1rem;
    border: 2px solid #dee2e6;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
}

.role-option:hover {
    border-color: #007bff;
    box-shadow: 0 2px 8px rgba(0, 123, 255, 0.1);
    transform: translateY(-2px);
}

.role-option.selected {
    border-color: #28a745;
    background: #f0f8f5;
    box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
}

.role-option input[type="checkbox"] {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.role-name {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.25rem;
    padding-right: 2rem;
}

.role-desc {
    font-size: 0.85rem;
    color: #666;
    line-height: 1.4;
}

.permission-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.permission-badges .badge {
    padding: 0.5rem 0.75rem;
    font-size: 0.85rem;
}

.modal-lg {
    max-width: 650px;
}

@media (max-width: 576px) {
    .roles-grid {
        grid-template-columns: 1fr;
    }
}

input:invalid {
    border-color: #dc3545 !important;
}

input:valid {
    border-color: #28a745 !important;
}
</style>

{{-- Scripts --}}
<script>
let allRoles = [];
let rolePermissions = {};

// Open modal
function openCreateAdminModal() {
    $('#createAdminModal').modal('show');
    loadAdminRoles();
    updateFormProgress();
}

// Close modal
function closeCreateAdminModal() {
    $('#createAdminModal').modal('hide');
    resetAdminForm();
}

// Reset form
function resetAdminForm() {
    document.getElementById('createAdminForm').reset();
    document.getElementById('adminIsAdmin').checked = false;
    document.getElementById('adminRolesSection').style.display = 'none';
    document.getElementById('permissionsPreview').style.display = 'none';
    document.getElementById('validationSummary').style.display = 'none';
    updateFormProgress();
}

// Load roles from backend
async function loadAdminRoles() {
    try {
        const response = await fetch("{{ route('admin.roles.list') }}");
        const data = await response.json();

        allRoles = data.roles.filter(role => role.name !== 'SUPER_ADMIN');

        allRoles.forEach(role => {
            rolePermissions[role.id] = role.permissions || [];
        });

        renderRoleCards();
    } catch (error) {
        console.error('Error loading roles:', error);
        showAlert('Erreur lors du chargement des rôles', 'danger');
    }
}

// Render role cards
function renderRoleCards() {
    const container = document.getElementById('rolesContainer');
    container.innerHTML = '';

    allRoles.forEach(role => {
        const permissions = rolePermissions[role.id] || [];
        const permCount = permissions.length;

        const card = document.createElement('div');
        card.className = 'role-option';
        card.dataset.roleId = role.id;
        card.onclick = () => toggleRoleSelection(card, role.id);

        card.innerHTML = `
            <input type="checkbox" value="${role.id}" style="cursor: pointer;">
            <div class="role-name">${role.name_fr || role.name}</div>
            <div class="role-desc">${role.name}</div>
            ${permCount > 0 ? `<small class="text-muted d-block mt-2"><i class="fas fa-key mr-1"></i>${permCount} permission(s)</small>` : ''}
        `;

        container.appendChild(card);
    });
}

// Toggle role selection
function toggleRoleSelection(card, roleId) {
    card.classList.toggle('selected');
    const checkbox = card.querySelector('input[type="checkbox"]');
    checkbox.checked = !checkbox.checked;
    updatePermissionsPreview();
    updateFormProgress();
}

// Update permissions preview
function updatePermissionsPreview() {
    const selectedCheckboxes = document.querySelectorAll('#rolesContainer input[type="checkbox"]:checked');
    const permissionsSet = new Set();

    selectedCheckboxes.forEach(checkbox => {
        const roleId = checkbox.value;
        if (rolePermissions[roleId]) {
            rolePermissions[roleId].forEach(perm => {
                permissionsSet.add(perm.name);
            });
        }
    });

    const previewDiv = document.getElementById('selectedPermissions');

    if (permissionsSet.size > 0) {
        previewDiv.innerHTML = Array.from(permissionsSet)
            .map(perm => `<span class="badge badge-info">${perm}</span>`)
            .join('');
    } else {
        previewDiv.innerHTML = '<span class="badge badge-secondary">Aucune permission</span>';
    }
}

// Toggle admin roles visibility
function toggleAdminRoles() {
    const checkbox = document.getElementById('adminIsAdmin');
    const rolesSection = document.getElementById('adminRolesSection');
    const previewSection = document.getElementById('permissionsPreview');

    if (checkbox.checked) {
        rolesSection.style.display = 'block';
        previewSection.style.display = 'block';
        setTimeout(() => rolesSection.scrollIntoView({ behavior: 'smooth' }), 100);
    } else {
        rolesSection.style.display = 'none';
        previewSection.style.display = 'none';
        document.querySelectorAll('#rolesContainer input[type="checkbox"]').forEach(cb => {
            cb.checked = false;
            cb.closest('.role-option').classList.remove('selected');
        });
    }
    updateFormProgress();
}

// Update progress bar
function updateFormProgress() {
    const form = document.getElementById('createAdminForm');
    const prenom = document.getElementById('adminPrenom').value;
    const nom = document.getElementById('adminNom').value;
    const email = document.getElementById('adminEmail').value;
    const cin = document.getElementById('adminCin').value;
    const isAdmin = document.getElementById('adminIsAdmin').checked;
    const hasRoles = document.querySelectorAll('#rolesContainer input[type="checkbox"]:checked').length > 0;

    let completed = 0;
    let total = 4;

    if (prenom && nom) completed++;
    if (email) completed++;
    if (cin) completed++;
    if (!isAdmin || hasRoles) completed++;

    const progress = Math.round((completed / total) * 100);
    document.getElementById('formProgress').style.width = progress + '%';
    document.getElementById('currentStep').textContent = Math.min(completed + 1, 3);
}

// Validate form
function validateForm() {
    const errors = [];
    const prenom = document.getElementById('adminPrenom').value.trim();
    const nom = document.getElementById('adminNom').value.trim();
    const email = document.getElementById('adminEmail').value.trim();
    const cin = document.getElementById('adminCin').value.trim();
    const isAdmin = document.getElementById('adminIsAdmin').checked;
    const selectedRoles = document.querySelectorAll('#rolesContainer input[type="checkbox"]:checked');

    if (!prenom) errors.push('Le prénom est obligatoire');
    if (!nom) errors.push('Le nom est obligatoire');
    if (!email) errors.push('L\'email est obligatoire');
    if (!email.includes('@')) errors.push('Email invalide');
    if (!cin) errors.push('Le CIN/ID est obligatoire');
    if (isAdmin && selectedRoles.length === 0) errors.push('Sélectionnez au moins un rôle pour les administrateurs');

    return errors;
}

// Show validation errors
function showValidationErrors(errors) {
    const summaryDiv = document.getElementById('validationSummary');
    const listDiv = document.getElementById('validationList');

    if (errors.length > 0) {
        listDiv.innerHTML = errors.map(err => `<li>${err}</li>`).join('');
        summaryDiv.style.display = 'block';
        summaryDiv.scrollIntoView({ behavior: 'smooth' });
        return false;
    } else {
        summaryDiv.style.display = 'none';
        return true;
    }
}

// Create admin user
async function createAdminUser(event) {
    event.preventDefault();

    // Validate
    const errors = validateForm();
    if (!showValidationErrors(errors)) return;

    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Création en cours...';

    try {
        const prenom = document.getElementById('adminPrenom').value;
        const nom = document.getElementById('adminNom').value;
        const email = document.getElementById('adminEmail').value;
        const cin = document.getElementById('adminCin').value;
        const telephone = document.getElementById('adminTelephone').value || null;
        const id_direction = document.getElementById('adminDirection').value || null;
        const isAdmin = document.getElementById('adminIsAdmin').checked;

        const selectedRoles = [];
        if (isAdmin) {
            document.querySelectorAll('#rolesContainer input[type="checkbox"]:checked').forEach(checkbox => {
                selectedRoles.push(parseInt(checkbox.value));
            });
        }

        const response = await fetch("{{ route('admin.users.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                prenom,
                nom,
                email,
                cin,
                telephone,
                id_direction: id_direction ? parseInt(id_direction) : null,
                is_admin: isAdmin,
                roles: selectedRoles,
                actif: true
            })
        });

        const data = await response.json();

        if (response.ok && data.success) {
            showAlert(`✓ ${data.message}`, 'success');
            closeCreateAdminModal();

            // Reload page after 1.5 seconds
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert(`✗ ${data.message || 'Erreur lors de la création'}`, 'danger');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('✗ Erreur réseau: ' + error.message, 'danger');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
}

// Show alert
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    `;
    document.body.appendChild(alertDiv);

    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Update progress on form input
document.addEventListener('input', updateFormProgress);

// Load roles when modal is opened
$('#createAdminModal').on('show.bs.modal', function() {
    loadAdminRoles();
});
</script>
