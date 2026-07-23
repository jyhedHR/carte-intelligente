<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DepartmentAdmin extends Model
{
    use HasFactory;

    protected $table = 'wfb_department_admins';

    protected $fillable = [
        'department_id',
        'user_id',
        'assigned_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    /**
     * Get the department
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the user who is the admin
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
