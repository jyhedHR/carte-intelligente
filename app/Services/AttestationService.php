<?php
// app/Services/AttestationService.php
namespace App\Services;

use App\Models\PdfTemplate;
use App\Models\PdfGeneration;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

class AttestationService
{
    /**
     * Generate a PDF attestation and store it on disk.
     */
    public function generate(array $data): array
    {
        Log::info('AttestationService::generate called', [
            'has_html_content'   => isset($data['htmlContent']),
            'has_pdf_template_key' => isset($data['pdfTemplateKey']),
            'process_instance_id' => $data['processInstanceId'] ?? 'unknown',
            'data_keys'          => array_keys($data)
        ]);

        $pdfTemplateKey = $data['pdfTemplateKey'] ?? null;

        if ($pdfTemplateKey) {
            $template = PdfTemplate::where('template_key', $pdfTemplateKey)
                ->where('is_active', true)
                ->first();

            if ($template) {
                Log::info('Using database template', [
                    'template_key'  => $template->template_key,
                    'template_name' => $template->name,
                    'template_type' => $template->template_type,
                ]);
                return $this->generateFromDatabaseTemplate($data, $template);
            } else {
                Log::warning("Template not found: {$pdfTemplateKey}, falling back to default");
            }
        }

        if (!empty($data['htmlContent'])) {
            Log::info('Using htmlContent from data');
            return $this->generateFromHtmlContent($data);
        }

        Log::info('Using default template');
        return $this->generateFromDefaultTemplate($data);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // mPDF Helper
    // ──────────────────────────────────────────────────────────────────────────

private function makeMpdf(string $format = 'A4'): Mpdf
{
    return new Mpdf([
        'mode'               => 'utf-8',
        'format'             => $format,
        'autoLangToFont'     => true,
        'autoScriptToLang'   => false, // ← was true; disabling prevents img stripping in RTL rewrites
    ]);
}


    // ──────────────────────────────────────────────────────────────────────────
    // Generate methods
    // ──────────────────────────────────────────────────────────────────────────

private function generateFromDatabaseTemplate(array $data, PdfTemplate $template): array
{    // TEMP DEBUG — remove after confirming
    Log::info('generateFromDatabaseTemplate', [
        'template_key'        => $template->template_key,
        'html_content_length' => strlen($template->html_content ?? ''),
        'html_preview'        => substr($template->html_content ?? '', 0, 200),
        'after_sig_resolve'   => '', // filled below
    ]);

    $templateData = $this->buildTemplateData($data, $template->id);

    $html = $this->resolveSignatureTokens($template->html_content);
    $html = $this->resolveSignatureNameTokens($html);
    $html = $this->replacePlaceholders($html, $templateData);
   Log::info('After resolveSignatureTokens', [
        'contains_img'  => str_contains($html, '<img'),
        'html_preview'  => substr($html, strpos($html, 'signature') - 20, 200),
    ]);
    // ── REMOVED the RTL wrapper — the template has its own <html>/<body> ──
    // Wrapping forced direction:rtl which flipped table layouts and
    // pushed signature <img> tags outside the visible page area.

    $filename    = $this->buildFilename($data);
    $storagePath = 'attestations/' . $filename;

    $pdf = $this->makeMpdf();
    $pdf->WriteHTML($html);  // ← pass $html directly, not $fullHtml
    Storage::disk('local')->put($storagePath, $pdf->Output('', 'S'));

    $this->storeTemplateReference($data['processInstanceId'], $template->id, $template->template_key);

    return [
        'path'          => $storagePath,
        'filename'      => $filename,
        'template_used' => $template->template_key,
    ];
    $this->recordValidity($data);
}

private function generateFromHtmlContent(array $data): array
{
    $templateData = $this->buildTemplateData($data);

    $html = $this->resolveSignatureTokens($data['htmlContent']);
    $html = $this->resolveSignatureNameTokens($html);
    $html = $this->replacePlaceholders($html, $templateData);

    // ── REMOVED the RTL wrapper (same reason as above) ──

    $filename    = $this->buildFilename($data);
    $storagePath = 'attestations/' . $filename;

    $pdf = $this->makeMpdf();
    $pdf->WriteHTML($html);  // ← pass $html directly
    Storage::disk('local')->put($storagePath, $pdf->Output('', 'S'));

    return [
        'path'          => $storagePath,
        'filename'      => $filename,
        'template_used' => 'html_content',
    ];
$this->recordValidity($data);
    }

    private function generateFromDefaultTemplate(array $data): array
    {
        $filename    = $this->buildFilename($data);
        $storagePath = 'attestations/' . $filename;

        $html = view('pdf.attestation', [
            'title'             => $data['processName']      ?? 'Attestation',
            'reference'         => $data['reference']        ?? ($data['processInstanceId'] ?? 'N/A'),
            'requesterName'     => $data['requesterName']    ?? 'N/A',
            'requesterEmail'    => $data['requesterEmail']   ?? '',
            'approvedBy'        => $data['approvedBy']       ?? 'Administration',
            'approvedAt'        => $data['approvedAt']       ?? Carbon::now()->toDateTimeString(),
            'generatedAt'       => Carbon::now()->format('d/m/Y à H:i'),
            'processInstanceId' => $data['processInstanceId'] ?? '',
            'extraFields'       => $data['extraFields']      ?? [],
        ])->render();

        $this->ensureAttestationsDir();

        $pdf = $this->makeMpdf();
        $pdf->WriteHTML($html);
        Storage::disk('local')->put($storagePath, $pdf->Output('', 'S'));

        return [
            'path'          => $storagePath,
            'filename'      => $filename,
            'template_used' => 'default',
        ];
    }

    private function flattenData(array $data, string $prefix = ''): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            $fullKey = $prefix ? $prefix . '.' . $key : $key;

            if (is_array($value)) {
                $result = array_merge($result, $this->flattenData($value, $fullKey));
            } else {
                $result[$fullKey] = $value;
            }
        }
        return $result;
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Placeholder replacement
    // ──────────────────────────────────────────────────────────────────────────

    private function replacePlaceholders(string $html, array $data): string
    {
        $flatData = $this->flattenData($data);

        return preg_replace_callback(
            '/\{\{\s*([\w.\-]+)\s*\}\}/',
            function (array $matches) use ($flatData): string {
                $key   = $matches[1];
                $value = $flatData[$key] ?? '';

                // FIXED: signature / image tokens (e.g. director.signature) must
                // be embedded as an actual <img>, never htmlspecialchars-escaped
                // text — otherwise the PDF shows a wall of raw base64 characters
                // instead of a picture. (Same fix as PdfTemplate::renderHtml()
                // and PdfTemplateApiController::replacePlaceholders().)
                if (is_string($value) && preg_match('/^data:image\/(png|jpe?g);base64,/', $value)) {
                    return '<img src="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '" style="max-height:70px;max-width:220px;">';
                }

                // FIXED: a signature token with nothing saved yet leaves a
                // visible sign-here line instead of silently rendering blank.
                if ($value === '' && str_ends_with($key, '.signature')) {
                    return '<span style="display:inline-block;width:180px;border-bottom:1px solid #999;">&nbsp;</span>';
                }

                return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
            },
            $html
        ) ?? $html;
    }

    private function getNestedValue(array $data, string $path): string
    {
        $current = $data;

        foreach (explode('.', $path) as $part) {
            if (!isset($current[$part])) {
                return '';
            }
            $current = $current[$part];
        }

        if (is_array($current)) {
            if (empty($current)) {
                return '';
            }
            if (isset($current['name'])) {
                return $current['name'];
            }
            if (isset($current['originalName'])) {
                return $current['originalName'];
            }
            if (isset($current['url']) || isset($current['path'])) {
                return $current['url'] ?? $current['path'];
            }
            return '[Données attachées]';
        }

        return (string) ($current ?? '');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Data builder
    // ──────────────────────────────────────────────────────────────────────────

    private function buildTemplateData(array $camundaData, ?int $templateId = null): array
    {
        $now        = Carbon::now();
        $approvedAt = isset($camundaData['approvedAt'])
            ? Carbon::parse($camundaData['approvedAt'])
            : $now;

        $user = null;
        if (!empty($camundaData['requesterEmail'])) {
            $user = \App\Models\User::where('email', $camundaData['requesterEmail'])->first();
        }

        $submissionData = [];
        if (!empty($camundaData['submissionData'])) {
            $submissionData = is_string($camundaData['submissionData'])
                ? (json_decode($camundaData['submissionData'], true) ?? [])
                : $camundaData['submissionData'];
        }

        // NEW: resolve the director's saved signature. There's no Auth::user()
        // in this worker (it's a Camunda polling job, not an HTTP request), so
        // we fall back to the admin of the demande's department who has a
        // signature saved — same strategy as PdfGenerationController::buildData().
        $director = $this->resolveDirectorSignature($camundaData['id_direction'] ?? null);

        return [
            'current_date' => $now->format('d/m/Y'),
            'current_time' => $now->format('H:i:s'),

            'user' => [
                'nom'            => $user->nom            ?? $submissionData['nom']            ?? '',
                'prenom'         => $user->prenom         ?? $submissionData['prenom']         ?? '',
                'email'          => $camundaData['requesterEmail'] ?? $user->email             ?? '',
                'cin'            => $user->cin             ?? $submissionData['cin']            ?? '',
                'telephone'      => $user->telephone      ?? $submissionData['telephone']      ?? '',
                'date_naissance' => $user->date_naissance ?? $submissionData['date_naissance'] ?? '',
            ],

            'demande' => [
                'reference'  => $camundaData['reference'] ?? $camundaData['demandeReference'] ?? '',
                'statut'     => 'Approuvée',
                'created_at' => $approvedAt->format('d/m/Y'),
            ],

            'director' => [
                'nom'       => $director->nom            ?? '',
                'prenom'    => $director->prenom         ?? '',
                'signature' => $director->signature_data ?? '',
            ],

            'qr_code' => $this->buildQrData($camundaData, $templateId),

            'submission' => [
                'nom_prenom'     => $camundaData['requesterName']    ?? $submissionData['nom_prenom'] ?? $submissionData['nom'] ?? '',
                'cin_numero'     => $submissionData['cin_numero']    ?? $submissionData['cin']        ?? '',
                'date_naissance' => $submissionData['date_naissance'] ?? ($user->date_naissance      ?? ''),
                'telephone'      => $submissionData['telephone']     ?? ($user->telephone             ?? ''),
                'specialite'     => $submissionData['specialite']    ?? '',
                'type_demande'   => $camundaData['processName']      ?? $submissionData['type_demande'] ?? '',
            ],

            'processName'       => $camundaData['processName']       ?? 'Attestation',
            'reference'         => $camundaData['reference']         ?? ($camundaData['processInstanceId'] ?? 'N/A'),
            'processInstanceId' => $camundaData['processInstanceId'] ?? '',
            'approvedBy'        => $camundaData['approvedBy']        ?? 'Administration',
            'approvedAt'        => $approvedAt->format('d/m/Y à H:i'),
            'requesterName'     => $camundaData['requesterName']     ?? 'N/A',
            'requesterEmail'    => $camundaData['requesterEmail']    ?? '',
            'generatedAt'       => $now->format('d/m/Y à H:i'),
            'extraFields'       => $camundaData['extraFields']       ?? [],
            'form'              => $camundaData['form']              ?? [],
        ];
    }
// Add these methods to app/Services/AttestationService.php

/**
 * Resolve {{signature.id}} tokens BEFORE other placeholder replacement
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
            // No signature found - show a sign-here line
            return '<span style="display:inline-block;width:180px;border-bottom:1px solid #999;">&nbsp;</span>';
        },
        $html
    ) ?? $html;
}

/**
 * Resolve signature name tokens {{signature.id.nom}}, {{signature.id.prenom}}, {{signature.id.fullname}}
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
    /**
     * Build the qr_code.* tokens for this generation.
     *
     * This runs from a Camunda polling worker — there's no HTTP request or
     * logged-in admin here, so (unlike PdfGenerationController) we can't
     * rely on Auth::id() for generated_by, and the demande is looked up by
     * process_instance_id rather than a route parameter. Mirrors the same
     * "track a row, QR encodes a verification URL, never bake a countdown
     * into the image itself" approach used there.
     */
    private function buildQrData(array $camundaData, ?int $templateId): array
    {
        Log::info('buildQrData() called', [
            'process_instance_id' => $camundaData['processInstanceId'] ?? 'unknown',
            'template_id'         => $templateId,
        ]);

        try {
            $processInstanceId = $camundaData['processInstanceId'] ?? null;

            $demandeId = $processInstanceId
                ? \DB::table('wfb_demandes')->where('process_instance_id', $processInstanceId)->value('id')
                : null;

            $now = Carbon::now();

            $generation = PdfGeneration::create([
                'uuid'            => (string) Str::uuid(),
                'pdf_template_id' => $templateId,
                'demande_id'      => $demandeId,
                'generated_by'    => null, // no authenticated user in this worker
                'generated_at'    => $now,
                'validity_days'   => 4,
                'expires_at'      => $now->copy()->addDays(4),
            ]);

            $verifyUrl = url('/verify/' . $generation->uuid);

            return [
                'image'      => (new QrCodeService())->renderPng($verifyUrl),
                'expires_at' => $generation->expires_at->format('d/m/Y'),
                'valid_days' => $generation->validity_days,
            ];
        } catch (\Throwable $e) {
            // Same reasoning as PdfGenerationController: never let this fail
            // silently — the {{qr_code.image}} token would otherwise just
            // vanish from the PDF with zero trace of why.
            Log::error('QR code generation failed in AttestationService', [
                'process_instance_id' => $camundaData['processInstanceId'] ?? 'unknown',
                'error'               => $e->getMessage(),
            ]);

            return [
                'image'      => '[QR indisponible — voir les logs]',
                'expires_at' => '',
                'valid_days' => '',
            ];
        }
    }

    /**
     * Resolve the department director's saved signature for this demande.
     *
     * This worker runs as a Camunda polling job — there's no logged-in admin
     * to pull a signature from, so we fall back to the admin of the
     * demande's department who has a signature saved. Mirrors the fallback
     * in PdfGenerationController::buildData().
     */
    private function resolveDirectorSignature(?int $idDirection): ?\App\Models\User
    {
        if (!$idDirection) {
            return null;
        }

        return \App\Models\User::where('id_direction', $idDirection)
            ->where('is_admin', 1)
            ->whereNotNull('signature_data')
            ->first();
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────────────────────────────────

    private function ensureAttestationsDir(): void
    {
        if (!Storage::disk('local')->exists('attestations')) {
            Storage::disk('local')->makeDirectory('attestations');
        }
    }

    private function storeTemplateReference(string $processInstanceId, int $templateId, string $templateKey): void
    {
        try {
            if (\Schema::hasTable('wfb_attestation_template_usage')) {
                \DB::table('wfb_attestation_template_usage')->insert([
                    'process_instance_id' => $processInstanceId,
                    'pdf_template_id'     => $templateId,
                    'template_key'        => $templateKey,
                    'generated_at'        => now(),
                    'created_at'          => now(),
                    'updated_at'          => now(),
                ]);
            }
            Log::info('Template used for attestation', [
                'process_instance_id' => $processInstanceId,
                'template_key'        => $templateKey,
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to store template reference: ' . $e->getMessage());
        }
    }

    private function buildFilename(array $data): string
    {
        $instanceId = preg_replace('/[^a-zA-Z0-9\-]/', '', $data['processInstanceId'] ?? Str::uuid());
        $timestamp  = Carbon::now()->format('Ymd_His');
        return "attestation_{$instanceId}_{$timestamp}.pdf";
    }

    public function findByInstanceId(string $processInstanceId): ?string
    {
        $files = Storage::disk('local')->files('attestations');

        Log::info('Looking for attestation', [
            'instance_id' => $processInstanceId,
            'files_count' => count($files),
        ]);

        foreach ($files as $file) {
            if (str_contains($file, $processInstanceId)) {
                Log::info('Found attestation', ['file' => $file]);
                return $file;
            }
        }

        Log::warning('No attestation found for instance', ['instance_id' => $processInstanceId]);
        return null;
    }

    public function exists(string $processInstanceId): bool
    {
        return $this->findByInstanceId($processInstanceId) !== null;
    }
/**
 * Record validity for the user after a successful attestation generation.
 * Called only when the linked formulaire has validity_months set.
 */
private function recordValidity(array $data): void
{
    // Load the demande to get id_formulaire
    $demande = DB::table('wfb_demandes')
        ->where('process_instance_id', $data['processInstanceId'] ?? null)
        ->first();

    if (!$demande || !$demande->id_formulaire) return;

    $formulaire = DB::table('wfb_formulaires')
        ->where('id', $demande->id_formulaire)
        ->first();

    if (!$formulaire || !$formulaire->validity_months) return; // no restriction configured

    $validFrom  = now()->toDateString();
    $validUntil = now()->addMonths($formulaire->validity_months)->toDateString();

    // Upsert — if they somehow already have a record, update it (renewal)
    DB::table('wfb_demande_validity')->updateOrInsert(
        [
            'user_id'       => $demande->user_id,
            'id_formulaire' => $demande->id_formulaire,
        ],
        [
            'id_demande'  => $demande->id,
            'valid_from'  => $validFrom,
            'valid_until' => $validUntil,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]
    );

    Log::info('[AttestationService] Validity recorded', [
        'user_id'       => $demande->user_id,
        'id_formulaire' => $demande->id_formulaire,
        'valid_until'   => $validUntil,
    ]);
}

}
