<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowTaskConfig extends Model
{
    protected $table = 'wfb_workflow_task_configs';

    protected $fillable = [
        'workflow_id',
        'task_id',
        'task_name',
        'description',
        'custom_actions',
        'custom_fields',
        'required_for_roles',
        'visibility_rules',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'custom_actions' => 'array',
        'custom_fields' => 'array',
        'required_for_roles' => 'array',
        'visibility_rules' => 'array',
    ];

    public function workflow()
    {
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }
}
