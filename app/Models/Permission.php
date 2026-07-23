<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
 protected $table = 'wfb_permissions';
    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * A permission belongs to many roles
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'wfb_role_permission', 'permission_id', 'role_id');
    }
}
