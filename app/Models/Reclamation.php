<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reclamation extends Model
{
    protected $table = 'wfb_reclamations';

    protected $fillable = [
        'user_id',
        'id_formulaire',
        'id_demande',
        'motif',
        'statut',
        'action',
        'valeur',
        'admin_comment',
        'traite_par',
        'traite_le',
    ];

    protected $casts = [
        'traite_le' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function formulaire()
    {
        return $this->belongsTo(Formulaire::class, 'id_formulaire');
    }

    public function demande()
    {
        return $this->belongsTo(Demande::class, 'id_demande');
    }

    public function traitePar()
    {
        return $this->belongsTo(User::class, 'traite_par');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }
}
