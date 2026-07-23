<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormRule extends Model
{
    protected $table = 'wfb_form_rules';

    protected $fillable = [
        'formulaire_id', 'type', 'value', 'scope', 'message', 'active',
    ];

    protected $casts = [
        'value'  => 'array',
        'active' => 'boolean',
    ];

    public function formulaire()
    {
        return $this->belongsTo(Formulaire::class, 'formulaire_id');
    }
}
