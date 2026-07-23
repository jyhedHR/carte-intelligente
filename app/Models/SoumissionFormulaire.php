<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SoumissionFormulaire extends Model
{
    protected $table = 'wfb_soumission_formulaires';

    protected $fillable = [
        'id_form',
        'soumission_data',
        'id_statut',
        'soumis_par',
    ];

    protected $casts = [
        'soumission_data' => 'array',  // auto JSON encode/decode
    ];

    // ── Relationships ──────────────────────────────

    public function formulaire(): BelongsTo
    {
        return $this->belongsTo(Formulaire::class, 'id_form');
    }

    public function soumisParUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'soumis_par');
    }

    // ── Methods ────────────────────────────────────

    /**
     * validerValeur — validates all submitted data against the form's champs.
     * Returns array of errors (empty = valid).
     */
    public function validerValeur(array $data): array
    {
        $errors = [];
        $champs = $this->formulaire->champs;

        foreach ($champs as $champ) {
            $value = $data[$champ->nom] ?? null;
            if (!$champ->validerValeur($value)) {
                $errors[$champ->nom] = "Le champ \"{$champ->nom}\" est obligatoire.";
            }
        }

        return $errors;
    }
}
