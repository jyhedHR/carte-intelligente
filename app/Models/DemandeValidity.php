<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandeValidity extends Model
{
    protected $table = 'wfb_demande_validity';

    protected $fillable = [
        'user_id', 'id_formulaire', 'id_demande', 'valid_from', 'valid_until',
    ];

    protected $casts = [
        'valid_from'  => 'date',
        'valid_until' => 'date',
    ];

    public function isExpired(): bool
    {
        return $this->valid_until->isPast();
    }

    public function remainingDays(): int
    {
        return max(0, now()->diffInDays($this->valid_until, false));
    }
}
