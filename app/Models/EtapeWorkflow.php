<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtapeWorkflow extends Model
{
    use HasFactory;

    protected $table = 'wfb_etapes_workflow'; // adjust if you used different name

    protected $fillable = [
        'id_workflow', 'nom', 'ordre', 'type', 'assigne_a_role_id',
        'date_debut', 'date_fin', 'commentaire'
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
    ];

    public function workflow()
    {
        return $this->belongsTo(Workflow::class, 'id_workflow');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'assigne_a_role_id');
    }
}
