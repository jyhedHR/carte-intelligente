<?php
// app/Models/PdfGeneration.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PdfGeneration extends Model
{
    protected $table = 'wfb_pdf_generations';

    protected $fillable = [
        'uuid', 'pdf_template_id', 'demande_id', 'generated_by',
        'generated_at', 'validity_days', 'expires_at',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'expires_at'   => 'datetime',
    ];

    public function isExpired(): bool
    {
        return Carbon::now()->greaterThan($this->expires_at);
    }

    /**
     * Whole days left until expiry. Negative once expired.
     */
    public function daysRemaining(): int
    {
        return (int) Carbon::now()->startOfDay()
            ->diffInDays($this->expires_at->copy()->startOfDay(), false);
    }
}
