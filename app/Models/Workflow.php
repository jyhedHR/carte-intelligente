<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    protected $table = 'wfb_workflows';

    protected $fillable = [
        'nom',
        'bpm_definition_id',
        'version',
        'actif',
        'id_formulaire',
        'creer_par'
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function formulaire()
    {
        return $this->belongsTo(Formulaire::class, 'id_formulaire');
    }
}
