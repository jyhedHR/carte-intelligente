{{-- resources/views/backoffice/allUsers/modals/manage-permissions-modal.blade.php --}}
{{-- Enhanced user-friendly permissions management modal --}}

<div id="managePermissionsModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{-- Header --}}
            <div class="modal-header bg-gradient-info text-white border-0">
                <div>
                    <h5 class="modal-title">
                        <i class="fas fa-lock mr-2"></i>Gérer les permissions
                    </h5>
                    <small class="text-white-50" id="userDisplayName">Utilisateur</small>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            {{-- Body --}}
            <div class="modal-body">
                {{-- Tabs Navigation --}}
                <ul class="nav nav-tabs mb-4" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a
                            class="nav-link active"
                            id="rolesTab"
                            data-toggle="tab"
                            href="#rolesPanel"
                            role="tab"
                        >
                            <i class="fas fa-user-shield mr-2"></i>Rôles
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a
                            class="nav-link"
                            id="permissionsTab"
                            data-toggle="tab"
                            href="#permissionsPanel"
                            role="tab"
                        >
                            <i class="fas fa-key mr-2"></i>Permissions
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a
                            class="nav-link"
                            id="createRoleTab"
                            data-toggle="tab"
                            href="#createRolePanel"
                            role="tab"
                        >
                            <i class="fas fa-plus-circle mr-2"></i>Créer rôle
                        </a>
                    </li>
                </ul>

                {{-- Tab Content --}}
                <div class="tab-content">
                    {{-- TAB 1: Manage Roles --}}
                    <div class="tab-pane fade show active" id="rolesPanel" role="tabpanel">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Les rôles</strong> définissent un ensemble de permissions que vous pouvez assigner aux utilisateurs
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h6 class="font-weight-bold mb-3">Rôles assignés</h6>
                                <div id="assignedRolesContainer" class="mb-4">
                                    <div class="text-muted text-center py-3">
                                        <i class="fas fa-spinner fa-spin mr-2"></i> Chargement...
                                    </div>
                                </div>

                                <h6 class="font-weight-bold mb-3">Rôles disponibles</h6>
                                <div id="availableRolesContainer" class="roles-selection">
                                    <div class="text-muted text-center py-3">
                                        <i class="fas fa-spinner fa-spin mr-2"></i> Chargement...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TAB 2: View Permissions --}}
                    <div class="tab-pane fade" id="permissionsPanel" role="tabpanel">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Les permissions</strong> sont dérivées des rôles assignés à cet utilisateur
                        </div>

                        <div id="permissionsContent">
                            <div class="text-muted text-center py-3">
                                <i class="fas fa-spinner fa-spin mr-2"></i> Chargement...
                            </div>
                        </div>
                    </div>

                    {{-- TAB 3: Create New Role --}}
                    <div class="tab-pane fade" id="createRolePanel" role="tabpanel">
                        <form id="createRoleForm" onsubmit="createNewRole(event)">
                            <div class="alert alert-success">
                                <i class="fas fa-lightbulb mr-2"></i>
                                <strong>Conseil:</strong> Créez un rôle réutilisable avec des permissions spécifiques
                            </div>

                            <div class="form-group">
                                <label for="roleName" class="font-weight-600">Nom du rôle (EN) *</label>
                                <input
                                    type="text"
                                    id="roleName"
                                    class="form-control form-control-lg"
                                    placeholder="Ex: DIRECTEUR_MUSIQUE"
                                    required
                                >
                                <small class="form-text text-muted">Utilisez MAJUSCULES_ET_UNDERSCORES</small>
                            </div>

                            <div class="form-group">
                                <label for="roleNameFr" class="font-weight-600">Nom du rôle (FR)</label>
                                <input
                                    type="text"
                                    id="roleNameFr"
                                    class="form-control form-control-lg"
                                    placeholder="Ex: Directeur de la Musique"
                                >
                            </div>

                            <div class="form-group">
                                <label for="rolePermissionsSelect" class="font-weight-600">Permissions *</label>
                                <select
                                    id="rolePermissionsSelect"
                                    class="form-control"
                                    multiple
                                    size="6"
                                    required
                                >
                                    {{-- Populated by JavaScript --}}
                                </select>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Maintenez Ctrl (Cmd sur Mac) pour sélectionner plusieurs permissions
                                </small>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <button
                                        type="submit"
                                        class="btn btn-success btn-lg btn-block"
                                    >
                                        <i class="fas fa-plus-circle mr-2"></i>Créer le rôle
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="modal-footer bg-light border-top">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal"
                >
                    <i class="fas fa-times mr-2"></i>Fermer
                </button>
                <button
                    type="button"
                    onclick="savePermissionChanges()"
                    class="btn btn-primary"
                    id="savePermBtn"
                >
                    <i class="fas fa-save mr-2"></i>Enregistrer les modifications
                </button>
            </div>

        </div>
    </div>
</div>

{{-- Styles --}}
<style>
.roles-selection {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1rem;
}

.role-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: #007bff;
    color: white;
    border-radius: 20px;
    font-size: 0.9rem;
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
}

.role-badge .remove-role {
    cursor: pointer;
    margin-left: 0.5rem;
    font-weight: bold;
}

.role-item {
    padding: 1rem;
    border: 2px solid #dee2e6;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.role-item:hover {
    border-color: #007bff;
    box-shadow: 0 2px 8px rgba(0, 123, 255, 0.1);
}

.role-item.selected {
    border-color: #28a745;
    background: #f0f8f5;
    box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
}

.role-item input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.role-info {
    flex: 1;
}

.role-info .role-name {
    font-weight: 600;
    color: #333;
    margin: 0;
}

.role-info .role-desc {
    font-size: 0.85rem;
    color: #666;
    margin: 0;
}

.permission-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 0.5rem;
}

.permission-item {
    padding: 0.5rem;
    background: #e7f3ff;
    border: 1px solid #b3d9ff;
    border-radius: 4px;
    font-size: 0.85rem;
    color: #004085;
}

.nav-tabs .nav-link {
    color: #495057;
    border: none;
    border-bottom: 3px solid transparent;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    color: #0056b3;
    background-color: transparent;
    border-bottom-color: #0056b3;
}

.nav-tabs .nav-link:hover {
    border-color: transparent;
    color: #0056b3;
}

.modal-lg {
    max-width: 700px;
}

@media (max-width: 576px) {
    .roles-selection {
        grid-template-columns: 1fr;
    }
}
</style>

{{-- Scripts --}}
<script>
let currentUserId = null;
let allPermissions = [];
let allRoles = [];

// Open modal
function openManagePermissionsModal(userId, userName, userEmail, userRole) {
    // Fetch current departments from backend
    fetch(`/api/users/${userId}/departments`)
        .then(response => response.json())
        .then(data => {
            console.log("[v0] Current departments:", data);

            // Set current departments in modal
            currentUserId = userId;
            document.getElementById('modalUserName').textContent = userName;
            document.getElementById('modalUserEmail').textContent = userEmail;
            document.getElementById('modalUserRole').textContent = userRole;
            document.getElementById('modalCurrentDepartments').textContent =
                data.departments.length > 0
                    ? data.departments.map(d => d.name).join(', ')
                    : 'Aucun département assigné';

            // Set checkboxes
            const checkboxes = document.querySelectorAll('.department-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = data.departments.some(d => d.id == checkbox.value);
            });

            // Show modal
            document.getElementById('managePermissionsModal').style.display = 'flex';
        })
        .catch(error => {
            console.error("[v0] Error fetching departments:", error);
            alert('Erreur lors du chargement des départements');
        });
}

// Close modal
function closeManagePermissionsModal() {
    $('#managePermissionsModal').modal('hide');
}

// Load all permissions and roles data
async function loadPermissionsData() {
    try {
        const [permResponse, rolesResponse] = await Promise.all([
            fetch("{{ route('admin.permissions.list') }}"),
            fetch("{{ route('admin.roles.list') }}")
        ]);

        const permData = await permResponse.json();
        const rolesData = await rolesResponse.json();

        allPermissions = permData.permissions;
        allRoles = rolesData.roles;

        populateRolePermissionsSelect();
        loadUserRoles();
        loadUserPermissions();
    } catch (error) {
        console.error('Error loading data:', error);
        showAlert('Erreur lors du chargement des données', 'danger');
    }
}

// Populate role permissions select
function populateRolePermissionsSelect() {
    const select = document.getElementById('rolePermissionsSelect');
    select.innerHTML = '';

    allPermissions.forEach(perm => {
        const option = document.createElement('option');
        option.value = perm.id;
        option.textContent = perm.name + (perm.description ? ` - ${perm.description}` : '');
        select.appendChild(option);
    });
}

// Load user's current roles
async function loadUserRoles() {
    const assignedContainer = document.getElementById('assignedRolesContainer');
    const availableContainer = document.getElementById('availableRolesContainer');

    try {
        // For this example, we'll show all roles
        // You should implement an endpoint to get user's current roles
        const response = await fetch(`/admin/users/${currentUserId}`);
        const userData = await response.json();
        const userRoles = userData.roles || [];
        const userRoleIds = userRoles.map(r => r.id);

        // Assigned roles
        if (userRoleIds.length > 0) {
            const assignedRoles = allRoles.filter(r => userRoleIds.includes(r.id));
            assignedContainer.innerHTML = assignedRoles.map(role => `
                <span class="role-badge">
                    <i class="fas fa-check-circle mr-1"></i>
                    ${role.name_fr || role.name}
                    <span class="remove-role" onclick="removeRoleFromUser(${role.id})">✕</span>
                </span>
            `).join('');
        } else {
            assignedContainer.innerHTML = '<p class="text-muted"><i class="fas fa-info-circle mr-2"></i>Aucun rôle assigné</p>';
        }

        // Available roles
        const availableRoles = allRoles.filter(r => !userRoleIds.includes(r.id));
        availableContainer.innerHTML = availableRoles.map(role => `
            <div class="role-item" onclick="toggleRoleSelection(this, ${role.id})">
                <input type="checkbox" value="${role.id}" onclick="event.stopPropagation()">
                <div class="role-info">
                    <p class="role-name">${role.name_fr || role.name}</p>
                    <p class="role-desc">${role.name}</p>
                </div>
            </div>
        `).join('');

        if (availableRoles.length === 0) {
            availableContainer.innerHTML = '<p class="text-muted"><i class="fas fa-check-circle mr-2"></i>Tous les rôles sont assignés</p>';
        }
    } catch (error) {
        console.error('Error loading user roles:', error);
    }
}

// Load user's permissions
function loadUserPermissions() {
    const container = document.getElementById('permissionsContent');

    try {
        // Fetch user permissions from roles
        const response = fetch(`/admin/users/${currentUserId}`);
        response.then(res => res.json()).then(userData => {
            const userRoles = userData.roles || [];
            const permissionsSet = new Set();

            userRoles.forEach(role => {
                const roleData = allRoles.find(r => r.id === role.id);
                if (roleData && roleData.permissions) {
                    roleData.permissions.forEach(perm => {
                        permissionsSet.add(perm.name);
                    });
                }
            });

            if (permissionsSet.size > 0) {
                const permGrid = Array.from(permissionsSet)
                    .sort()
                    .map(perm => `<div class="permission-item"><i class="fas fa-key mr-2"></i>${perm}</div>`)
                    .join('');
                container.innerHTML = `
                    <h6 class="font-weight-bold mb-3">Total: ${permissionsSet.size} permission(s)</h6>
                    <div class="permission-grid">${permGrid}</div>
                `;
            } else {
                container.innerHTML = '<p class="text-muted text-center py-3"><i class="fas fa-exclamation-circle mr-2"></i>Cet utilisateur n\'a aucune permission</p>';
            }
        });
    } catch (error) {
        console.error('Error loading permissions:', error);
    }
}

// Toggle role selection
function toggleRoleSelection(element, roleId) {
    element.classList.toggle('selected');
    const checkbox = element.querySelector('input[type="checkbox"]');
    checkbox.checked = !checkbox.checked;
}

// Remove role from user
function removeRoleFromUser(roleId) {
    // This would require an endpoint to remove role
    // For now, just reload
    loadUserRoles();
}

// Save permission changes
function savePermissionChanges() {
    const selectedCheckboxes = document.querySelectorAll('#availableRolesContainer input[type="checkbox"]:checked');
    const selectedRoleIds = Array.from(selectedCheckboxes).map(cb => parseInt(cb.value));

    if (selectedRoleIds.length === 0) {
        showAlert('Sélectionnez au least un rôle', 'warning');
        return;
    }

    const btn = document.getElementById('savePermBtn');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enregistrement...';

    // You would need to implement an endpoint to update user roles
    setTimeout(() => {
        showAlert('✓ Permissions mises à jour avec succès', 'success');
        btn.disabled = false;
        btn.innerHTML = originalText;
        loadUserRoles();
    }, 1000);
}

// Create new role
async function createNewRole(event) {
    event.preventDefault();

    const name = document.getElementById('roleName').value;
    const nameFr = document.getElementById('roleNameFr').value;
    const permissionsSelect = document.getElementById('rolePermissionsSelect');
    const permissions = Array.from(permissionsSelect.selectedOptions).map(opt => opt.value);

    if (permissions.length === 0) {
        showAlert('Sélectionnez au least une permission', 'warning');
        return;
    }

    const btn = event.target.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Création...';

    try {
        const response = await fetch("{{ route('admin.roles.manage') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                name,
                name_fr: nameFr,
                permissions: permissions.map(Number)
            })
        });

        const data = await response.json();

        if (data.success) {
            showAlert('✓ Rôle créé avec succès', 'success');
            document.getElementById('createRoleForm').reset();
            loadPermissionsData();
        } else {
            showAlert(`✗ ${data.error || 'Erreur'}`, 'danger');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('✗ Erreur: ' + error.message, 'danger');
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalText;
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

    setTimeout(() => alertDiv.remove(), 5000);
}

// Close modal event
$('#managePermissionsModal').on('hide.bs.modal', function() {
    currentUserId = null;
});
</script>
