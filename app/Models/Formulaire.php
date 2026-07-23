<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Formulaire extends Model
{
    protected $table = 'wfb_formulaires';

    protected $fillable = [
        'titre',
        'slug',
        'version',
        'statut',
        'department_id',
        'workflow_id',
        'schema_formio',
        'validity_months',
        'max_submissions',
    ];

    protected $casts = [
        'schema_formio' => 'array',
    ];

    public function setTitreAttribute(string $value): void
    {
        $this->attributes['titre'] = $value;
        $this->attributes['slug']  = Str::slug($value);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }

    public function soumissions(): HasMany      // ← clean, works for ->count() and withCount()
    {
        return $this->hasMany(SubmissionFormulaire::class, 'id_formulaire');
    }

    public function typeFormulaire()
    {
        return null;
    }
    public function rules()
{
    return $this->hasMany(\App\Models\FormRule::class, 'formulaire_id')
                ->where('active', true);
}
}
