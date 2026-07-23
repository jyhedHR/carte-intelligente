<?php
// app/Models/SubmissionArchive.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubmissionArchive extends Model
{
    protected $table = 'wfb_submissions_archive';

protected $fillable = [
    'original_submission_id',
    'original_demande_id',       // ← new
    'id_formulaire',
    'formulaire_titre',
    'formulaire_slug',
    'formulaire_schema',
    'soumission_data',
    'demande_data',              // ← new
    'id_statut',
    'soumis_par',
    'archived_reason',
    'archived_by',
    'original_created_at',
];

protected $casts = [
    'soumission_data'     => 'array',
    'formulaire_schema'   => 'array',
    'demande_data'        => 'array',   // ← new
    'original_created_at' => 'datetime',
];

    public function archivedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'archived_by');
    }

    public function soumisParUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'soumis_par');
    }
}
