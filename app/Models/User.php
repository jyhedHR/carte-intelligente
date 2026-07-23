<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Demande;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Department;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, SoftDeletes, HasApiTokens;
protected $table = 'wfb_users';
    /**
     * Mass-assignable fields
     */
protected $fillable = [
    'nom',
    'prenom',
    'email',
    'cin',
    'mot_de_passe',
    'telephone',
    'langue',
    'actif',
    'region',
    'id_direction',
    'archived_at',
    'archived_by',
    'archive_reason',
    'is_admin',
    'email_verified_at',

    // ==================== SIGNATURE FIELDS ====================
    'signature_data',
    'can_manage_signature',
    // ============================================================

    // ==================== MFA FIELDS ====================
    'two_factor_secret',
    'two_factor_recovery_codes',
    'two_factor_enabled',
    'two_factor_confirmed_at',
    'mfa_forced_by_admin',
    'mfa_enforced_at',
    // ====================================================
];

    /**
     * Hidden from serialization
     */
    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    /**
     * Casts
     */
protected $casts = [
    'email_verified_at' => 'datetime',
    'archived_at'       => 'datetime',
    'actif'             => 'boolean',
    'is_admin'          => 'boolean',
    'can_manage_signature'   => 'boolean',
    'two_factor_enabled'     => 'boolean',
    'two_factor_confirmed_at'=> 'datetime',
    'mfa_forced_by_admin'    => 'boolean',
    'mfa_enforced_at'        => 'datetime',
];

    /**
     * Tell Laravel which column contains the password
     */
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    /**
     * Get full name
     */
    public function getFullNameAttribute()
    {
        return trim("{$this->prenom} {$this->nom}");
    }

    /**
     * Check if user is archived
     */
    public function isArchived(): bool
    {
        return $this->archived_at !== null;
    }

    /**
     * Archive user (soft delete)
     */
    public function archive(string $reason = null, User $archivedBy = null): void
    {
        $this->update([
            'archived_at' => now(),
            'archived_by' => $archivedBy?->id ?? auth()->id(),
            'archive_reason' => $reason,
            'actif' => false, // Also deactivate
        ]);
    }

    /**
     * Restore archived user
     */
    public function restoreFromArchive(): void
    {
        $this->update([
            'archived_at' => null,
            'archived_by' => null,
            'archive_reason' => null,
            'actif' => true,
        ]);
    }

    /**
     * Get the user who archived this user
     */
    public function archivedBy()
    {
        return $this->belongsTo(User::class, 'archived_by');
    }

    /**
     * Get department
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'id_direction');
    }

    /**
     * Get demandes
     */
    public function demandes()
    {
        return $this->hasMany(Demande::class, 'user_id');
    }

    /**
     * Many-to-Many: User has many roles
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'wfb_user_role', 'user_id', 'role_id');
    }

    /**
     * Helper: Check if user has a specific role
     */
    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    /**
     * Check if user is Super Admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('SUPER_ADMIN');
    }

    /**
     * Check if user is Department Admin
     */
    public function isDepartmentAdmin(): bool
    {
        return $this->hasRole('DEPARTMENT_ADMIN');
    }

    /**
     * Check if user is regular admin (any admin type)
     */
    public function isAdmin(): bool
    {
        return $this->isSuperAdmin() || $this->isDepartmentAdmin();
    }

    /**
     * Get department admin record if user is a department admin
     */
    public function departmentAdminRecord()
    {
        return $this->hasOne(DepartmentAdmin::class);
    }

    /**
     * Get the department this user administers (if they're a department admin)
     */
    public function administeredDepartment()
    {
        return $this->hasOneThrough(
            Department::class,
            DepartmentAdmin::class,
            'user_id',
            'id',
            'id',
            'department_id'
        );
    }

    /**
     * Get all permissions through roles
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'wfb_user_role', 'user_id', 'role_id')
            ->join('wfb_role_permission', 'wfb_user_role.role_id', '=', 'wfb_role_permission.role_id')
            ->select('wfb_permissions.*')
            ->distinct();
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission($permissionName): bool
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($permissionName) {
            $query->where('name', $permissionName);
        })->exists();
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($permissions) {
            $query->whereIn('name', $permissions);
        })->exists();
    }

    /**
     * Check if user has all given permissions
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * For Department Admins: check if they can manage a user (same department)
     */
    public function canManageUser(User $targetUser): bool
    {
        // Super Admin can manage anyone
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Department Admin can only manage users in their department
        if ($this->isDepartmentAdmin()) {
            $myDepartment = $this->administeredDepartment;
            return $targetUser->id_direction === $myDepartment?->id;
        }

        return false;
    }

    /**
     * Get users that this user can manage
     * Super Admin gets all, Department Admin gets department users only
     */
    public function getManagedUsers()
    {
        if ($this->isSuperAdmin()) {
            return User::query();
        }

        if ($this->isDepartmentAdmin()) {
            $myDepartment = $this->administeredDepartment;
            return User::where('id_direction', $myDepartment?->id);
        }

        return User::whereNull('id'); // Return empty query

        }
public function getDepartmentPermission(): ?string
{
    if ($this->isSuperAdmin()) return null;

    $adminRecord = $this->departmentAdminRecord()->with('department')->first();
    if ($adminRecord?->department) {
        return $adminRecord->department->permission;
    }

    return $this->department?->permission ?? null;
}

public function canSeeDepartment(string $permissionSlug): bool
{
    if ($this->isSuperAdmin()) return true;
    return $this->getDepartmentPermission() === $permissionSlug;
}

    /**
     * Get all active sessions for this user
     */
    public function sessions()
    {
        return $this->hasMany(UserSession::class);
    }

    /**
     * Get only active sessions (last 24 hours)
     */
    public function activeSessions()
    {
        return $this->sessions()
            ->where('last_activity', '>', now()->subHours(24))
            ->orderByDesc('last_activity');
    }
    /**
 * Many-to-Many: User has many departments
 */
public function departments()
{
    return $this->belongsToMany(Department::class, 'user_department', 'user_id', 'department_id');
}
        }
