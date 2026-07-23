<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubmissionFormulaire extends Model
{
    protected $table = 'wfb_submissions_formulaire';

    protected $fillable = [
        'id_formulaire',
        'soumission_data',
        'id_statut',
        'soumis_par',
        'id_demande',
    ];

    protected $casts = [
        'soumission_data' => 'array',
    ];

    public function formulaire(): BelongsTo
    {
        return $this->belongsTo(Formulaire::class, 'id_formulaire');
    }

    public function soumisParUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'soumis_par');
    }
}
