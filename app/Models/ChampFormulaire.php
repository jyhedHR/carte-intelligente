<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChampFormulaire extends Model
{
    protected $fillable = [
        'formulaire_id',
        'nom',
        'type',
        'obligatoire',
        'ordre',
        'validation',
    ];

    protected $casts = [
        'obligatoire' => 'boolean',
    ];

    public function formulaire(): BelongsTo
    {
        return $this->belongsTo(Formulaire::class, 'formulaire_id');
    }

    /**
     * validerValeur — mirrors the UML method.
     * Returns true if the value passes the champ's validation rules.
     */
    public function validerValeur(mixed $value): bool
    {
        if ($this->obligatoire && ($value === null || $value === '')) {
            return false;
        }
        return true; // extend with custom rules as needed
    }
}

