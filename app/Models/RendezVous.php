<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RendezVous extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rendez_vous';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_demande',
        'date_releve',
        'motif',
        'statut',
        'lieu',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date_releve' => 'datetime',
        'statut' => 'string',
    ];

    /**
     * Get the demande that owns the rendez‑vous.
     */
    public function demande()
    {
        return $this->belongsTo(Demande::class, 'id_demande');
    }
}
