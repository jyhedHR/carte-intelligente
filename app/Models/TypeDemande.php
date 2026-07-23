<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeDemande extends Model
{
    use HasFactory;

    protected $table = 'wfb_type_demandes'; // specify table name if needed

    protected $fillable = ['code', 'libelle', 'libelle_fr'];

    public function demandes()
    {
        return $this->hasMany(Demande::class, 'id_type');
    }

    public function formulaires()
    {
        return $this->hasMany(Formulaire::class, 'id_type');
    }
}
