<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class ReferenceFormat extends Model
{
    use HasFactory;

    protected $table = 'wfb_reference_formats';

    protected $fillable = [
        'department_id',
        'formulaire_id',
        'prefix',
        'separator',
        'include_year',
        'include_month',
        'sequence_padding',
        'sequence_start',
        'last_sequence',
        'preview_example',
        'active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'include_year'     => 'boolean',
        'include_month'    => 'boolean',
        'active'           => 'boolean',
        'sequence_padding' => 'integer',
        'sequence_start'   => 'integer',
        'last_sequence'    => 'integer',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function formulaire()
    {
        return $this->belongsTo(Formulaire::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ── Business logic ────────────────────────────────────────────────────────

    /**
     * Build a preview string without touching the sequence counter.
     */
    public function buildPreview(): string
    {
        $sep  = $this->separator ?: '-';
        $num  = str_pad($this->sequence_start, $this->sequence_padding, '0', STR_PAD_LEFT);

        $parts = array_filter([
            strtoupper($this->prefix),
            $this->include_year  ? date('Y') : null,
            $this->include_month ? date('m') : null,
            $num,
        ]);

        return implode($sep, $parts);
    }

    /**
     * Generate the next unique reference and increment last_sequence atomically.
     * Retries up to 10 times if a collision is detected (race condition safety).
     */
    public function generateNext(int $attempts = 10): string
    {
        for ($i = 0; $i < $attempts; $i++) {
            $ref = DB::transaction(function () {
                // Lock this row so concurrent requests don't get the same number
                $format = self::lockForUpdate()->find($this->id);
                $next   = $format->last_sequence + 1;

                $sep   = $format->separator ?: '-';
                $num   = str_pad($next, $format->sequence_padding, '0', STR_PAD_LEFT);

                $parts = array_filter([
                    strtoupper($format->prefix),
                    $format->include_year  ? date('Y') : null,
                    $format->include_month ? date('m') : null,
                    $num,
                ]);

                $reference = implode($sep, $parts);

                // Check uniqueness in wfb_demandes
                if (DB::table('wfb_demandes')->where('reference', $reference)->exists()) {
                    return null; // Signal a collision — retry outside transaction
                }

                $format->update(['last_sequence' => $next]);
                return $reference;
            });

            if ($ref !== null) {
                return $ref;
            }
        }

        // Ultimate fallback: append a random suffix
        return strtoupper($this->prefix) . $this->separator . date('Y') . $this->separator . uniqid();
    }

    /**
     * Find the best matching format for a department + optional form.
     * Prefers form-specific format, falls back to department-wide.
     */
    public static function findForContext(int $departmentId, ?int $formulaireId = null): ?self
    {
        // Try form-specific first
        if ($formulaireId) {
            $format = self::where('department_id', $departmentId)
                         ->where('formulaire_id', $formulaireId)
                         ->where('active', true)
                         ->first();
            if ($format) return $format;
        }

        // Fall back to department-wide (formulaire_id IS NULL)
        return self::where('department_id', $departmentId)
                   ->whereNull('formulaire_id')
                   ->where('active', true)
                   ->first();
    }
}
