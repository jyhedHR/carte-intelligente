<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;
protected $table = 'wfb_departments';
    protected $fillable = [
        'name',
        'name_fr',
        'permission',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Get the admin assigned to this department
     */
    public function admin()
    {
        return $this->hasOne(DepartmentAdmin::class)->latest('assigned_at');
    }

    /**
     * Get all users in this department
     */
    public function users()
    {
        return $this->hasMany(User::class, 'id_direction');
    }

    /**
     * Check if a user is the admin of this department
     */
    public function hasAdmin(User $user): bool
    {
        return $this->admin()?->user_id === $user->id;
    }

    /**
     * Assign a user as department admin
     */
    public function assignAdmin(User $user): void
    {
        // Remove current admin if exists
        DepartmentAdmin::where('department_id', $this->id)->delete();

        // Create new admin record
        DepartmentAdmin::create([
            'department_id' => $this->id,
            'user_id' => $user->id,
        ]);

        // Ensure user has the department_admin role
        $adminRole = Role::where('name', 'DEPARTMENT_ADMIN')->first();
        if ($adminRole && !$user->roles()->where('role_id', $adminRole->id)->exists()) {
            $user->roles()->attach($adminRole->id);
        }

        // Grant department-specific permission
        $permission = Permission::firstOrCreate(
            ['name' => $this->permission],
            ['description' => "Manage {$this->name_fr}"]
        );

        if (!$user->roles()->whereHas('permissions', function ($q) use ($permission) {
            $q->where('permission_id', $permission->id);
        })->exists()) {
            // Attach through role
            $adminRole?->permissions()->syncWithoutDetaching([$permission->id]);
        }
    }

    /**
     * Remove admin from this department
     */
    public function removeAdmin(): void
    {
        DepartmentAdmin::where('department_id', $this->id)->delete();
    }
    public function formulaires()
{
    return $this->hasMany(Formulaire::class, 'department_id');
}
}
