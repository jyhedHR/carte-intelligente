<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AIFeedback extends Model
{
    protected $fillable = [
        'demande_id',
        'ai_prediction',
        'human_correction',
        'was_correct',
        'action',
        'user_id',
        'comment',
    ];

    protected $casts = [
        'was_correct' => 'boolean',
    ];

    public function demande()
    {
        return $this->belongsTo(Demande::class, 'demande_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
