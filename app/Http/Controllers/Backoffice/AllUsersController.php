<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Demande;
use App\Models\TypeDemande;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Department;
use App\Models\DepartmentAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\UserCreatedNotification;
use App\Notifications\UserArchivedNotification;
use App\Notifications\RoleAssignedNotification;

class AllUsersController extends Controller
{
    /**
     * Display users with filtering and stats
     */
    public function index(Request $request)
    {
        // Only Super Admin and Department Admins can access
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        // ── Build base query ──────────────────────────────────────────────
        $query = User::query();

        // Department Admins only see their own department users
        if (auth()->user()->isDepartmentAdmin()) {
            $myDept = auth()->user()->administeredDepartment;
            $query->where('id_direction', $myDept?->id);
        }

        // ── Apply filters ─────────────────────────────────────────────────
        $filter = $request->input('filter', 'tous');

        match ($filter) {
            'actifs'     => $query->where('actif', true)->whereNull('archived_at'),
            'en_attente' => $query->where('actif', false)->whereNull('archived_at'),
            'archived'   => $query->whereNotNull('archived_at'),
            'admins'     => $query->whereHas('roles', function ($q) {
                $q->whereIn('name', ['SUPER_ADMIN', 'DEPARTMENT_ADMIN', 'ADMIN']);
            }),
            'dept_admins' => $query->whereHas('roles', function ($q) {
                $q->where('name', 'DEPARTMENT_ADMIN');
            }),
            default      => $query->whereNull('archived_at'), // Default excludes archived
        };

        // ── Search ────────────────────────────────────────────────────────
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('cin', 'like', "%{$search}%");
            });
        }

        // ── Load relationships ────────────────────────────────────────────
        $query->with(['roles.permissions', 'department', 'archivedBy']);

        $users = $query->latest()->paginate(15)->withQueryString();

        // ── Calculate stats ──────────────────────────────────────────────
        $baseQuery = User::query();
        if (auth()->user()->isDepartmentAdmin()) {
            $myDept = auth()->user()->administeredDepartment;
            $baseQuery->where('id_direction', $myDept?->id);
        }

        $stats = [
            'total'       => $baseQuery->count(),
            'actifs'      => $baseQuery->clone()->where('actif', true)->whereNull('archived_at')->count(),
            'en_attente'  => $baseQuery->clone()->where('actif', false)->whereNull('archived_at')->count(),
            'archived'    => $baseQuery->clone()->whereNotNull('archived_at')->count(),
            'admins'      => $baseQuery->clone()->whereHas('roles')->count(),
            'dept_admins' => $baseQuery->clone()->whereHas('roles', function ($q) {
                $q->where('name', 'DEPARTMENT_ADMIN');
            })->count(),
        ];

        $filterCounts = [
            'tous'        => $stats['total'],
            'actifs'      => $stats['actifs'],
            'en_attente'  => $stats['en_attente'],
            'archived'    => $stats['archived'],
            'admins'      => $stats['admins'],
            'dept_admins' => $stats['dept_admins'],
        ];

        // ── Get data for modals ──────────────────────────────────────────
        $allRoles = Role::with('permissions')->get();
        $allDepartments = Department::where('active', true)->get();

        return view('backoffice.allUsers.displayUsers', compact(
            'users',
            'stats',
            'filterCounts',
            'filter',
            'allRoles',
            'allDepartments',
        ));
    }

    /**
     * Show create user form
     */
    public function create()
    {
        $departments = Department::where('active', true)->get();
        return view('backoffice.allUsers.create', compact('departments'));
    }

    /**
     * Store new user (Super Admin only, or Department Admin for their dept)
     */
    public function store(Request $request)
{
    // Authorization
    if (!auth()->user()->isSuperAdmin()) {
        abort(403, 'Only Super Admin can create users');
    }

    $validated = $request->validate([
        'nom'          => 'required|string|max:255',
        'prenom'       => 'required|string|max:255',
        'email'        => 'required|email|unique:wfb_users,email',
        'cin'          => 'required|string|unique:wfb_users,cin',
        'telephone'    => 'nullable|string',
        'id_direction' => 'nullable|exists:wfb_departments,id',
        'actif'        => 'boolean',
        'roles'        => 'nullable|array',
        'roles.*'      => 'exists:wfb_roles,id',
        'email_verified_at' => 'nullable|date',
        // FIXED: this is the validation rule for the field — it was
        // previously pasted into the User::create() array below instead,
        // which meant the literal string "nullable|boolean" was being
        // saved as the column value and the real checkbox value was
        // never read.
        'can_manage_signature' => 'nullable|boolean',
    ]);

    // Create user with temporary password
    $tempPassword = \Str::random(12);

    // Create the user
$user = User::create([
    'nom'          => $validated['nom'],
    'prenom'       => $validated['prenom'],
    'email'        => $validated['email'],
    'cin'          => $validated['cin'],
    'telephone'    => $validated['telephone'] ?? null,
    'id_direction' => $validated['id_direction'] ?? null,
    'actif'        => true,
    'mot_de_passe' => bcrypt($tempPassword),
    'is_admin'     => true,  // now works because it's in $fillable
    // DO NOT set email_verified_at here — let the user verify via email
    // FIXED: now reads the actual submitted value instead of the
    // validation-rule string, and only persists because 'can_manage_signature'
    // was added to User::$fillable.
    'can_manage_signature' => $request->boolean('can_manage_signature'),

]);
    // Assign roles if provided
    if (!empty($validated['roles'])) {
        $user->roles()->sync($validated['roles']);
    }

$user->notify(new UserCreatedNotification($user, $tempPassword));

// Envoi immédiat de l'email de vérification
if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail) {
    $user->sendEmailVerificationNotification();
}    // Send welcome notification with temporary password


    return response()->json([
        'success' => true,
        'message' => "Utilisateur créé avec succès. Email de bienvenue envoyé à {$user->email}",
        'user' => $user->load('roles')
    ]);
}

    /**
     * Show user details
     */
    public function show(User $user)
    {
        // Authorization
        if (!auth()->user()->canManageUser($user)) {
            abort(403);
        }

        $user->load(['roles.permissions', 'department', 'archivedBy', 'wfb_demandes']);
        return view('backoffice.allUsers.show', compact('user'));
    }

    /**
     * Show edit form
     */
    public function edit(User $user)
    {
        if (!auth()->user()->canManageUser($user)) {
            abort(403);
        }

        $departments = Department::where('active', true)->get();
        $user->load('roles');

        return view('backoffice.allUsers.edit', compact('user', 'departments'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        if (!auth()->user()->canManageUser($user)) {
            abort(403);
        }

        $validated = $request->validate([
            'nom'          => 'required|string|max:255',
            'prenom'       => 'required|string|max:255',
            'email'        => 'required|email|unique:wfb_users,email,' . $user->id,
            'cin'          => 'required|string|unique:wfb_users,cin,' . $user->id,
            'telephone'    => 'nullable|string',
            'id_direction' => 'nullable|exists:wfb_departments,id',
            'actif'        => 'boolean',
            // FIXED: this must be the validation RULE for the field, not a
            // live runtime boolean. A boolean here isn't a valid rule
            // definition and the field would never be reliably validated
            // or carried through into $validated.
            'can_manage_signature' => 'nullable|boolean',
        ]);

        $user->update($validated);

// Super Admin can also toggle signature permission from the edit form
if (auth()->user()->isSuperAdmin() && $request->has('can_manage_signature')) {
    $user->update(['can_manage_signature' => $request->boolean('can_manage_signature')]);
}

        return back()->with('success', 'Utilisateur mis à jour avec succès');
    }

    /**
     * Activate user
     */
    public function activate(User $user)
    {
        if (!auth()->user()->canManageUser($user)) {
            abort(403);
        }

        $user->update(['actif' => true, 'archived_at' => null]);
        return back()->with('success', "Le compte de {$user->full_name} a été activé.");
    }

    /**
     * Suspend user (deactivate)
     */
    public function suspend(User $user)
    {
        if (!auth()->user()->canManageUser($user)) {
            abort(403);
        }

        $user->update(['actif' => false]);
        return back()->with('success', "Le compte de {$user->full_name} a été suspendu.");
    }

    /**
     * Archive user (soft delete with reason)
     */
    public function archive(Request $request, User $user)
    {
        // Only Super Admin can archive
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Only Super Admin can archive users');
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $user->archive($validated['reason'], auth()->user());

        // Send notification
        $user->notify(new UserArchivedNotification($user, $validated['reason']));

        return back()->with('success', "Utilisateur {$user->full_name} archivé.");
    }

    /**
     * Restore archived user
     */
    public function restore(User $user)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        // Include archived in query
        $user = User::withTrashed()->find($user->id);

        if ($user->isArchived()) {
            $user->restoreFromArchive();
            return back()->with('success', "Utilisateur {$user->full_name} restauré.");
        }

        return back()->with('error', 'Cet utilisateur n\'est pas archivé.');
    }

    /**
     * Permanently delete user (only if Super Admin, with confirmation)
     */
    public function destroy(User $user)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $name = $user->full_name;
        User::withTrashed()->find($user->id)->forceDelete();

        return back()->with('success', "Utilisateur {$name} supprimé définitivement.");
    }

    /**
     * Assign department admin role to a user
     * Super Admin only
     */
    public function assignDepartmentAdmin(Request $request, User $user)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'department_id' => 'required|exists:wfb_departments,id',
        ]);

        $department = Department::findOrFail($validated['department_id']);

        // Assign as department admin
        $department->assignAdmin($user);

        // Notify user
        $user->notify(new RoleAssignedNotification($user, 'DEPARTMENT_ADMIN'));

        return response()->json([
            'success' => true,
            'message' => "{$user->full_name} est maintenant administrateur de {$department->name_fr}"
        ]);
    }

    /**
     * Remove department admin role
     */
    public function removeDepartmentAdmin(Request $request, User $user)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $deptAdmin = DepartmentAdmin::where('user_id', $user->id)->first();
        if ($deptAdmin) {
            $dept = $deptAdmin->department;
            $deptAdmin->delete();

            // Remove role if no other admin positions
            if (!$user->departmentAdminRecord) {
                $role = Role::where('name', 'DEPARTMENT_ADMIN')->first();
                $user->roles()->detach($role->id);
            }

            return response()->json([
                'success' => true,
                'message' => "{$user->full_name} n'est plus administrateur de {$dept->name_fr}"
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Utilisateur n\'est pas administrateur'
        ], 404);
    }

    /**
     * Manage user permissions/roles
     */
    public function managePermissions(Request $request, User $user)
    {
        // Only Super Admin
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $user->load('roles.permissions');

        if ($request->isMethod('post')) {
            $roles = $request->input('roles', []);
            $user->roles()->sync($roles);

            return response()->json([
                'success' => true,
                'message' => "Rôles mis à jour pour {$user->full_name}"
            ]);
        }

        $allRoles = Role::with('permissions')->get();
        return view('backoffice.allUsers.manage-permissions', compact('user', 'allRoles'));
    }

    /**
     * Create new permission
     * Super Admin only
     */
    public function createPermission(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name'        => 'required|string|unique:wfb_permissions,name',
            'description' => 'nullable|string',
        ]);

        $permission = Permission::create($validated);

        return response()->json([
            'success' => true,
            'permission' => $permission,
            'message' => 'Permission créée avec succès'
        ]);
    }

    /**
     * Assign permission to role
     * Super Admin only
     */
    public function assignPermissionToRole(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'role_id'       => 'required|exists:wfb_roles,id',
            'permission_id' => 'required|exists:wfb_permissions,id',
        ]);

        $role = Role::findOrFail($validated['role_id']);
        $role->permissions()->syncWithoutDetaching([$validated['permission_id']]);

        $permission = Permission::find($validated['permission_id']);

        return response()->json([
            'success' => true,
            'message' => "Permission '{$permission->name}' assignée au rôle '{$role->name_fr}'"
        ]);
    }

    /**
     * Create or update role
     * Super Admin only
     */
    public function manageRole(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'role_id'       => 'nullable|exists:wfb_roles,id',
            'name'          => 'required|string',
            'name_fr'       => 'nullable|string',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:wfb_permissions,id',
        ]);

        if ($validated['role_id'] ?? false) {
            $role = Role::findOrFail($validated['role_id']);
            $role->update([
                'name'    => $validated['name'],
                'name_fr' => $validated['name_fr'] ?? $role->name_fr,
            ]);
        } else {
            $role = Role::create([
                'name'    => $validated['name'],
                'name_fr' => $validated['name_fr'] ?? $validated['name'],
            ]);
        }

        if (!empty($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        }

        return response()->json([
            'success' => true,
            'role'    => $role->load('permissions'),
            'message' => "Rôle '{$role->name_fr}' mis à jour"
        ]);
    }

    /**
     * Get all permissions
     */
    public function getPermissions()
    {
        $permissions = Permission::orderBy('name')->get();
        return response()->json(['permissions' => $permissions]);
    }

    /**
     * Get all roles with permissions
     */
    public function getRoles()
    {
        $roles = Role::with('permissions')->get();
        return response()->json(['roles' => $roles]);
    }

    /**
     * Get departments (with admin info)
     */
    public function getDepartments()
    {
        $departments = Department::with('admin.user')->where('active', true)->get();
        return response()->json(['departments' => $departments]);
    }

    /**
     * Update user's role and departments (supports multiple departments)
     */

public function updateUserRoleAndDept(Request $request)
{
    if (!auth()->user()->isSuperAdmin()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    try {
        $validated = $request->validate([
            'user_id'          => 'required|exists:wfb_users,id',
            'role_id'          => 'required|exists:wfb_roles,id',
            'department_ids'   => 'nullable|array',
            'department_ids.*' => 'exists:wfb_departments,id',
        ]);

        $user = User::findOrFail($validated['user_id']);

        // Update Role
        $user->roles()->sync([$validated['role_id']]);

        // Update Departments
        $deptIds = $validated['department_ids'] ?? [];

        if (method_exists($user, 'departments') && $user->departments() instanceof \Illuminate\Database\Eloquent\Relations\BelongsToMany) {
            $user->departments()->sync($deptIds);
        } else {
            $user->update(['id_direction' => $deptIds[0] ?? null]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Mise à jour réussie',
            'user'    => $user->load(['roles', 'department'])
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Données invalides',
            'errors'  => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        \Log::error('Update Role/Dept Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Erreur serveur: ' . $e->getMessage()
        ], 422);
    }
}
public function updateRoleAndDept(Request $request, $userId)
{
    $user = User::findOrFail($userId);

    $validated = $request->validate([
        'role_id' => 'required|exists:wfb_roles,id',
        'department_ids' => 'nullable|array',
        'department_ids.*' => 'exists:wfb_departments,id',
    ]);

    // Update role
    $user->roles()->sync([$validated['role_id']]);

    // Update departments (multiple)
    if (!empty($validated['department_ids'])) {
        $user->departments()->sync($validated['department_ids']);

        // Also update id_direction to the first department for backward compatibility
        $user->update(['id_direction' => $validated['department_ids'][0]]);
    } else {
        // Clear departments if none selected
        $user->departments()->detach();
        $user->update(['id_direction' => null]);
    }

    return response()->json(['message' => 'User updated successfully']);
}
public function getUserDepartments($userId)
{
    $user = User::findOrFail($userId);

    return response()->json([
        'departments' => $user->departments()->get(['id', 'name', 'name_fr'])->toArray()
    ]);
}
}
