<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    use HasFactory;

    // The table associated with the model (if not the plural form)
    protected $table = 'wfb_demandes';

    // Fillable attributes (adjust based on your migration)
    protected $fillable = [
        'reference',
        'title',
        'description',
        'user_id',
        'id_direction',
        'id_type',
        'id_formulaire',
        'statut',
        'lien',
        'process_instance_id',
        'workflow_status',
        'workflow_error',
        'completed_at',
        'workflow_id',
        'assigned_to',
        'deadline_at',
        'workload_priority',
        'status',
        'ai_suggestion',
        'ai_confidence',
        'ai_is_ambiguous',
        'ai_suggestion_auto',
        'ai_metadata',
        'workflow_key',
        'camunda_process_id',
        'validated_at',
        'validated_by',
    ];

    // Casts (optional)
    protected $casts = [
        'statut' => 'string',
        'ai_confidence' => 'float',
        'ai_is_ambiguous' => 'boolean',
        'ai_suggestion_auto' => 'boolean',
        'ai_metadata' => 'array',
        'validated_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship to direction
public function direction()
{
    return $this->belongsTo(\App\Models\Department::class, 'id_direction');
}

    // Relationship to type_demande
    public function type()
    {
        return $this->belongsTo(TypeDemande::class, 'id_type');
    }

    // Relationship to formulaire
    public function formulaire()
    {
        return $this->belongsTo(Formulaire::class, 'id_formulaire');
    }

    public function hasAiSuggestion(): bool
    {
        return !is_null($this->ai_suggestion);
    }

    public function isAiConfident(): bool
    {
        return ($this->ai_confidence ?? 0) >= 0.85;
    }

    public function validator()  // L'agent qui a validé
    {
        return $this->belongsTo(User::class, 'validated_by');
    }
}
