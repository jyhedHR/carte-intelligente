<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeFormulaire extends Model
{
    protected $fillable = ['nom', 'langue', 'nom_du_pro'];

    public function formulaires(): HasMany
    {
        return $this->hasMany(Formulaire::class, 'type_formulaire_id');
    }
}
