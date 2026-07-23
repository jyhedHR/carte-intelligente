<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowTaskAssignment extends Model
{
    use HasFactory;

    protected $table = 'wfb_workflow_task_assignments';

    protected $fillable = [
        'task_id',
        'task_name',
        'process_key',
        'instance_id',
        'admin_user_id',
        'assigned_at',
        'completed_at',
    ];

    protected $casts = [
        'assigned_at'   => 'datetime',
        'completed_at'  => 'datetime',
    ];

    // Relations
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }

    public function workflow()
    {
        return $this->belongsTo(Workflow::class, 'process_key', 'bpm_definition_id');
    }

    /**
     * Marquer une tâche comme complétée
     */
    public function complete()
    {
        $this->update(['completed_at' => now()]);
    }
}
