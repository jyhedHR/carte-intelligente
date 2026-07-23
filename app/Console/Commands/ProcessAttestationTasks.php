<?php
// app/Console/Commands/ProcessAttestationTasks.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\AttestationService;
use App\Services\NotificationService;
use App\Models\User;
use Carbon\Carbon;

class ProcessAttestationTasks extends Command
{
    protected $signature   = 'camunda:process-attestations
                                {--once : Run a single fetch cycle instead of looping}
                                {--interval=5 : Polling interval in seconds}';

    protected $description = 'Poll Camunda for generate-attestation external tasks and process them';

    private string $base         = 'http://localhost:8080/engine-rest';
    private string $workerId     = 'laravel-attestation-worker';
    private string $topic        = 'generate-attestation';
    private int    $lockDuration = 30_000;

    public function __construct(
        private AttestationService  $attestationService,
        private NotificationService $notificationService,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $once     = $this->option('once');
        $interval = (int) $this->option('interval');

        $this->info("[{$this->topic}] Worker started. Topic: {$this->topic}");

        do {
            $this->fetchAndProcess();
            if (!$once) sleep($interval);
        } while (!$once);

        return self::SUCCESS;
    }

    private function fetchAndProcess(): void
    {
        $tasks = $this->fetchLockedTasks();

        if (empty($tasks)) return;

        $this->info("[{$this->topic}] Fetched " . count($tasks) . " task(s).");

        foreach ($tasks as $task) {
            $this->processTask($task);
        }
    }

    private function fetchLockedTasks(): array
    {
        try {
            $response = Http::withBasicAuth('demo', 'demo')
                ->post("{$this->base}/external-task/fetchAndLock", [
                    'workerId' => $this->workerId,
                    'maxTasks' => 5,
                    'topics'   => [[
                        'topicName'    => $this->topic,
                        'lockDuration' => $this->lockDuration,
                        // Request all variables needed including the template key
                        'variables'    => [
                            'userId', 'userEmail', 'userName',
                            'demandeur_id', 'demandeur_email', 'demandeur_nom', 'demandeur_prenom',
                            'processName', 'reference', 'businessKey',
                            'approvedBy', 'commentAdmin', 'approvedAt',
                            'submissionData', 'demandeReference', // Add these for template data
                        ],
                    ]],
                ]);

            if (!$response->successful()) {
                Log::error('Camunda fetchAndLock failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return [];
            }

            return $response->json() ?? [];

        } catch (\Exception $e) {
            Log::error('Exception in fetchLockedTasks', ['error' => $e->getMessage()]);
            return [];
        }
    }

    private function processTask(array $task): void
    {
        $externalTaskId    = $task['id'];
        $processInstanceId = $task['processInstanceId'];
        $variables         = $task['variables'] ?? [];

        // CRITICAL FIX: Read pdfTemplateKey from extensionProperties
        // This is where Camunda stores custom extension attributes like camunda:pdfTemplate
        $pdfTemplateKey = null;


// NEW: resolve pdfTemplateKey from the form linked to this demande
$pdfTemplateKey = $this->resolvePdfTemplateKeyFromForm($processInstanceId);

// Keep method 3 (process variable) as fallback only:
if (!$pdfTemplateKey) {
    $pdfTemplateKey = $this->extractVar($variables, 'pdfTemplateKey');
}

        $this->info("  → Processing task {$externalTaskId} (instance: {$processInstanceId}, pdfTemplate: " . ($pdfTemplateKey ?? 'default') . ")");

        Log::info('Processing attestation task', [
            'externalTaskId'    => $externalTaskId,
            'processInstanceId' => $processInstanceId,
            'pdfTemplateKey'    => $pdfTemplateKey,
            'hasExtensionProps' => isset($task['extensionProperties']),
            'extensionProps'    => $task['extensionProperties'] ?? [],
            'rawVariables'      => array_keys($variables),
        ]);

        try {
            // 1. Resolve requester
            $userId    = $this->extractVar($variables, 'userId');
            $userEmail = $this->extractVar($variables, 'userEmail');

            $user = $this->resolveUser($userId, $userEmail, $processInstanceId);

            if (!$user) {
                $this->error("  ✗ Could not resolve user (userId={$userId}, userEmail={$userEmail})");
                $this->failTask($externalTaskId, "Could not resolve requester user for instance {$processInstanceId}");
                return;
            }

            $this->info("  → User resolved: {$user->email} (id={$user->id})");

            // 2. Build requester name safely
            $requesterName = $this->resolveUserName($user, $variables);

            // 3. Build approvedAt safely
            $approvedAtRaw = $this->extractVar($variables, 'approvedAt');
            $approvedAt    = $this->toIsoDate($approvedAtRaw);

            // 4. Build PDF data payload
            $processName = $this->extractVar($variables, 'processName') ?? 'Demande artistique';
            $reference   = $this->extractVar($variables, 'reference')
                        ?? $this->extractVar($variables, 'businessKey')
                        ?? $processInstanceId;
// Load submission data from DB using the demande linked to this process instance
$submissionFlat = [];
$demande = \DB::table('wfb_demandes')
    ->where('process_instance_id', $processInstanceId)
    ->first(['id', 'id_direction']);

if ($demande) {
    $submission = \DB::table('wfb_submissions_formulaire')
        ->where('id_demande', $demande->id)
        ->orderByDesc('created_at')
        ->first();

    if ($submission) {
        $raw  = json_decode($submission->soumission_data, true) ?? [];
        $submissionFlat = isset($raw['data']) && is_array($raw['data']) ? $raw['data'] : $raw;
    }
}
            $data = [
                'processInstanceId' => $processInstanceId,
                'processName'       => $processName,
                'reference'         => $reference,
                'requesterName'     => $requesterName,
                'requesterEmail'    => $user->email,
                'approvedBy'        => $this->extractVar($variables, 'approvedBy') ?? 'Administration',
                'approvedAt'        => $approvedAt,
                'submissionData'    => $this->extractVar($variables, 'submissionData') ?? '{}',
                'demandeReference'  => $this->extractVar($variables, 'demandeReference') ?? $reference,
                'extraFields'       => [],
                'form'              => $submissionFlat,
                // NEW: needed so AttestationService can resolve the director's
                // saved signature for {{director.signature}} tokens. There's no
                // Auth::user() in this worker, so we fall back to the department.
                'id_direction'      => $demande->id_direction ?? null,
            ];

            // 5. CRITICAL: Resolve PDF template from DB if a key was found
            if ($pdfTemplateKey) {
                $template = \DB::table('wfb_pdf_templates')
                    ->where('template_key', $pdfTemplateKey)
                    ->where('is_active', 1)
                    ->first();

                if ($template) {
                    $data['pdfTemplateKey']  = $template->template_key;
                    $data['htmlContent']     = $template->html_content;   // Raw HTML with {{placeholders}}
                    $data['templateName']    = $template->name;
                    $data['templateType']    = $template->template_type;  // 'html' or 'pdfme'

                    // If it's a PDF template with source_file_content, pass that too
                    if ($template->template_type === 'pdf' && $template->source_file_content) {
                        $data['pdfSourceContent'] = $template->source_file_content;
                    }

                    $this->info("  → Using PDF template: {$template->name} ({$template->template_key})");

                    // Log template usage for audit trail
                    $this->logTemplateUsage($processInstanceId, $template->id, $template->template_key);
                } else {
                    Log::warning("PDF template '{$pdfTemplateKey}' not found or inactive — falling back to default", [
                        'processInstanceId' => $processInstanceId,
                        'template_key' => $pdfTemplateKey
                    ]);
                    $this->warn("  ⚠ Template '{$pdfTemplateKey}' not found, using default generator");
                }
            } else {
                $this->info("  → No PDF template key found, using default attestation template");
            }

            Log::info('PDF data prepared', [
                'hasHtmlContent' => isset($data['htmlContent']),
                'templateType' => $data['templateType'] ?? 'default',
                'processInstanceId' => $processInstanceId
            ]);

            // 6. Generate PDF (AttestationService will use htmlContent if present)
            $result = $this->attestationService->generate($data);

            $this->info("  → PDF written: {$result['path']}");
            Log::info('PDF generated successfully', $result);

            // 7. Create ATTESTATION notification
            $this->notificationService->createNotification(
                userId:            $user->id,
                message:           "📄 Votre attestation pour « {$processName} » est prête. Cliquez pour la télécharger.",
                type:              'ATTESTATION',
                canal:             'WEB',
                processInstanceId: $processInstanceId,
                taskId:            null,
                adminComment:      null,
                reference:         $reference,
            );

            $this->info("  → ATTESTATION notification created for user {$user->email}");

            // 8. Complete the external task in Camunda
            $this->completeExternalTask($externalTaskId, [
                'attestationGenerated' => ['value' => true, 'type' => 'Boolean'],
                'attestationFilename'  => ['value' => $result['filename'], 'type' => 'String'],
                'attestationPath'      => ['value' => $result['path'], 'type' => 'String'],
                'templateUsed'         => ['value' => $pdfTemplateKey ?? 'default', 'type' => 'String'],
            ]);

            $this->info("  ✔ Task {$externalTaskId} completed");

        } catch (\Throwable $e) {
            Log::error('Failed to process attestation task', [
                'externalTaskId'    => $externalTaskId,
                'processInstanceId' => $processInstanceId,
                'error'             => $e->getMessage(),
                'file'              => $e->getFile() . ':' . $e->getLine(),
                'trace'             => $e->getTraceAsString(),
            ]);

            $this->error("  ✗ Exception: " . $e->getMessage() . " in " . $e->getFile() . ':' . $e->getLine());
            $this->failTask($externalTaskId, $e->getMessage());
        }
    }

    /**
     * NEW: Fetch PDF template key from the task definition if not available in variables
     */
    private function fetchPdfTemplateFromTaskDefinition(array $task, string $processInstanceId): ?string
    {
        try {
            // Get the process definition ID from the task
            $processDefinitionId = $task['processDefinitionId'] ?? null;

            if (!$processDefinitionId) {
                return null;
            }

            // Fetch the BPMN XML for this process definition
            $response = Http::withBasicAuth('demo', 'demo')
                ->get("{$this->base}/process-definition/{$processDefinitionId}/xml");

            if ($response->successful()) {
                $bpmnXml = $response->json()['bpmn20Xml'] ?? '';

                // Parse XML to find the service task with camunda:pdfTemplate
                $dom = new \DOMDocument();
                if (@$dom->loadXML($bpmnXml)) {
                    $xpath = new \DOMXPath($dom);
                    $xpath->registerNamespace('bpmn', 'http://www.omg.org/spec/BPMN/20100524/MODEL');
                    $xpath->registerNamespace('camunda', 'http://camunda.org/schema/1.0/bpmn');

                    // Find service task by ID (from task['activityId'])
                    $activityId = $task['activityId'] ?? '';
                    if ($activityId) {
                        $nodes = $xpath->query("//bpmn:serviceTask[@id='{$activityId}']/@camunda:pdfTemplate");
                        if ($nodes && $nodes->length > 0) {
                            return $nodes->item(0)->value;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning('Failed to fetch PDF template from task definition', [
                'error' => $e->getMessage(),
                'processInstanceId' => $processInstanceId
            ]);
        }

        return null;
    }

    /**
     * NEW: Log which template was used for audit purposes
     */
    private function logTemplateUsage(string $processInstanceId, int $templateId, string $templateKey): void
    {
        try {
            \DB::table('wfb_attestation_template_usage')->insert([
                'process_instance_id' => $processInstanceId,
                'pdf_template_id' => $templateId,
                'template_key' => $templateKey,
                'generated_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to log template usage', ['error' => $e->getMessage()]);
        }
    }

    // ... (rest of your existing helper methods remain the same)

    private function completeExternalTask(string $externalTaskId, array $variables = []): void
    {
        $response = Http::withBasicAuth('demo', 'demo')
            ->post("{$this->base}/external-task/{$externalTaskId}/complete", [
                'workerId'  => $this->workerId,
                'variables' => $variables,
            ]);

        if (!$response->successful()) {
            throw new \RuntimeException(
                "Failed to complete external task {$externalTaskId}: HTTP {$response->status()} — {$response->body()}"
            );
        }
    }

    private function failTask(string $externalTaskId, string $errorMessage): void
    {
        try {
            Http::withBasicAuth('demo', 'demo')
                ->post("{$this->base}/external-task/{$externalTaskId}/failure", [
                    'workerId'     => $this->workerId,
                    'errorMessage' => $errorMessage,
                    'retries'      => 0,
                    'retryTimeout' => 0,
                ]);
        } catch (\Exception $e) {
            Log::error('Could not report task failure to Camunda', ['error' => $e->getMessage()]);
        }
    }

    private function resolveUser(mixed $userId, ?string $userEmail, string $processInstanceId): ?User
    {
        // 1. Try direct passed variables
        if ($userId) {
            $user = User::find((int) $userId);
            if ($user) return $user;
        }

        if ($userEmail) {
            $user = User::where('email', $userEmail)->first();
            if ($user) return $user;
        }

        // 2. Fetch ALL variables from Camunda history
        try {
            $response = Http::withBasicAuth('demo', 'demo')
                ->get("{$this->base}/history/variable-instance", [
                    'processInstanceId' => $processInstanceId,
                ]);

            if ($response->successful()) {
                $variables = collect($response->json())->pluck('value', 'name')->toArray();

                // Try demandeur_id
                if (isset($variables['demandeur_id'])) {
                    $user = User::find((int) $variables['demandeur_id']);
                    if ($user) return $user;
                }

                // Try userId
                if (isset($variables['userId'])) {
                    $user = User::find((int) $variables['userId']);
                    if ($user) return $user;
                }

                // Try demandeur_email
                if (isset($variables['demandeur_email'])) {
                    $user = User::where('email', $variables['demandeur_email'])->first();
                    if ($user) return $user;
                }

                // Try userEmail
                if (isset($variables['userEmail'])) {
                    $user = User::where('email', $variables['userEmail'])->first();
                    if ($user) return $user;
                }
            }
        } catch (\Exception $e) {
            Log::warning("History API lookup failed", ['error' => $e->getMessage()]);
        }

        return null;
    }

    private function resolveUserName(User $user, array $variables): string
    {
        $fromVar = $this->extractVar($variables, 'userName');
        if (!empty($fromVar)) return $fromVar;

        if (!empty($user->name)) return $user->name;

        $prenom = $user->prenom ?? '';
        $nom    = $user->nom    ?? '';
        $full   = trim("{$prenom} {$nom}");
        if (!empty($full)) return $full;

        return explode('@', $user->email)[0];
    }

    private function toIsoDate(?string $value): ?string
    {
        if (empty($value)) return null;

        try {
            return Carbon::parse($value)->toIso8601String();
        } catch (\Exception) {
            return null;
        }
    }

    private function extractVar(array $variables, string $key): mixed
    {
        return $variables[$key]['value'] ?? null;
    }
private function resolvePdfTemplateKeyFromForm(string $processInstanceId): ?string
{
    // 1. Find the demande for this process instance
    $demande = \DB::table('wfb_demandes')
        ->where('process_instance_id', $processInstanceId)
        ->first(['id', 'id_formulaire']);
// ADD THIS:
Log::info('Submission lookup debug', [
    'processInstanceId' => $processInstanceId,
    'demande_found'     => $demande ? $demande->id : null,
    'submission_flat'   => $submissionFlat ?? 'not set yet',
]);
    if (!$demande || !$demande->id_formulaire) {
        return null;
    }

    // 2. Read the pdf_template_key from the linked form
    $form = \DB::table('wfb_formulaires')
        ->where('id', $demande->id_formulaire)
        ->value('pdf_template_key');

    return $form ?: null;
}

    }
