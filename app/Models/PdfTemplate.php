<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PdfTemplate extends Model
{
    protected $table = 'wfb_pdf_templates';

    protected $fillable = [
        'name',
        'template_key',
        'description',
        'page_size',
        'html_content',
        'available_fields',
        'field_mappings',
        'is_active',
        'created_by',
        'source_file_content',
        'source_file_type',
        'template_type',
        'linked_form_id',
    ];

    protected $casts = [
        'available_fields' => 'array',
        'field_mappings'   => 'array',
        'is_active'        => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isHtmlTemplate(): bool
    {
        return $this->template_type === 'html' || !empty($this->html_content);
    }

    public function isPdfTemplate(): bool
    {
        return $this->template_type === 'pdf' && !empty($this->source_file_content);
    }

public function renderHtml(array $data = []): string
{
    $html = $this->html_content ?? '';

    if (empty($html)) {
        return '<h1 style="color:red;">No HTML content in this template</h1>';
    }

    // ── ADD THESE TWO LINES ──────────────────────────────────────────────
    // resolveSignatureTokens() does a DB lookup by user ID — it must run
    // BEFORE flattenData/str_replace, because {{signature.N}} is never
    // in the $data array and would otherwise be stripped by the final
    // preg_replace('/\{\{[^}]+\}\}/', '', $html) cleaner.
    $html = $this->resolveSignatureTokens($html);
    $html = $this->resolveSignatureNameTokens($html);
    // ─────────────────────────────────────────────────────────────────────

    $flat = $this->flattenData($data);

    foreach ($flat as $key => $value) {
        if (is_string($value) && preg_match('/^data:image\/(png|jpe?g);base64,/', $value)) {
            $imgTag = '<img src="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '" style="max-height:70px;max-width:220px;">';
            $html = str_replace('{{' . $key . '}}', $imgTag, $html);
            continue;
        }

        if ($value === '' && str_ends_with($key, '.signature')) {
            $placeholder = '<span style="display:inline-block;width:180px;border-bottom:1px solid #999;">&nbsp;</span>';
            $html = str_replace('{{' . $key . '}}', $placeholder, $html);
            continue;
        }

        if (is_string($value) || is_numeric($value) || is_bool($value)) {
            $safeValue = htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
            $html = str_replace('{{' . $key . '}}', $safeValue, $html);
        }
    }

    $html = str_replace('{{current_date}}', now()->format('d/m/Y'), $html);
    $html = str_replace('{{current_time}}', now()->format('H:i:s'), $html);
    $html = str_replace('{{page_break}}', '<div style="page-break-before:always;"></div>', $html);

    // This cleaner now only strips genuinely missing tokens,
    // not signature tokens (already resolved above)
    $html = preg_replace('/\{\{[^}]+\}\}/', '', $html);

    return $html;
}


private function flattenData(array $data, string $prefix = ''): array
{
    $result = [];

    foreach ($data as $key => $value) {
        $fullKey = $prefix ? $prefix . '.' . $key : $key;

        if (is_array($value)) {
            // Recursively flatten nested arrays
            $result = array_merge($result, $this->flattenData($value, $fullKey));
        } else {
            $result[$fullKey] = $value;
        }
    }

    return $result;
}
/**
 * Resolve {{signature.id}} tokens
 */
private function resolveSignatureTokens(string $html): string
{
    return preg_replace_callback(
        '/\{\{\s*signature\.(\d+)\s*\}\}/',
        function (array $m) {
            $user = \App\Models\User::find((int) $m[1]);
            if ($user && $user->signature_data) {
                return '<img src="' . htmlspecialchars($user->signature_data, ENT_QUOTES, 'UTF-8')
                     . '" style="max-height:70px;max-width:220px;">';
            }
            return '<span style="display:inline-block;width:180px;border-bottom:1px solid #999;">&nbsp;</span>';
        },
        $html
    ) ?? $html;
}

/**
 * Resolve signature name tokens
 */
private function resolveSignatureNameTokens(string $html): string
{
    // {{signature.id.nom}}
    $html = preg_replace_callback(
        '/\{\{\s*signature\.(\d+)\.nom\s*\}\}/',
        function (array $m) {
            $user = \App\Models\User::find((int) $m[1]);
            return $user ? htmlspecialchars($user->nom ?? '', ENT_QUOTES, 'UTF-8') : '';
        },
        $html
    ) ?? $html;

    // {{signature.id.prenom}}
    $html = preg_replace_callback(
        '/\{\{\s*signature\.(\d+)\.prenom\s*\}\}/',
        function (array $m) {
            $user = \App\Models\User::find((int) $m[1]);
            return $user ? htmlspecialchars($user->prenom ?? '', ENT_QUOTES, 'UTF-8') : '';
        },
        $html
    ) ?? $html;

    // {{signature.id.fullname}}
    $html = preg_replace_callback(
        '/\{\{\s*signature\.(\d+)\.fullname\s*\}\}/',
        function (array $m) {
            $user = \App\Models\User::find((int) $m[1]);
            if ($user) {
                return htmlspecialchars(trim($user->prenom . ' ' . $user->nom), ENT_QUOTES, 'UTF-8');
            }
            return '';
        },
        $html
    ) ?? $html;

    return $html;
}
}
