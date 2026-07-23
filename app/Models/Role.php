<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
protected $table = 'wfb_roles';
    protected $fillable = ['name', 'name_fr'];

    // Existing relationship - you'll change this later when you do user_role
    public function users()
    {
        return $this->belongsToMany(User::class, 'wfb_user_role', 'role_id', 'user_id');
    }

    /**
     * A role has many permissions (Many-to-Many)
     * CHANGED: pivot table from 'role_permission' to 'wfb_role_permission'
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'wfb_role_permission', 'role_id', 'permission_id');
    }

    /**
     * Helper method: Check if role has a specific permission
     */
    public function hasPermission($permissionName)
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }

    /**
     * Helper: Check multiple permissions (any or all)
     */
    public function hasAnyPermission(array $permissions)
    {
        return $this->permissions()->whereIn('name', $permissions)->exists();
    }

    public function hasAllPermissions(array $permissions)
    {
        return count($permissions) === $this->permissions()
            ->whereIn('name', $permissions)
            ->count();
    }
}
