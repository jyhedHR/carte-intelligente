<?php
// app/Console/Commands/SimulateIncidentWorker.php
//f root taa proj fama "incident-test-fixed.bpmn" deployih f camunda amalou form ou kol ammer il form then runi il worker hetha it should simulate an incident
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SimulateIncidentWorker extends Command
{
    protected $signature = 'camunda:simulate-incident
                            {--process-instance= : Specific process instance ID to target}
                            {--topic=failing-external-call : The external task topic to listen on}
                            {--retries=0 : Set retries to this value after failing (0 = instant incident)}';

    protected $description = 'Simulate a Camunda incident by fetching and deliberately failing an external task';

    private string $base     = 'http://localhost:8080/engine-rest';
    private string $workerId = 'laravel-incident-simulator';

    public function handle(): int
    {
        $topic     = $this->option('topic');
        $targetPid = $this->option('process-instance');
        $retries   = (int) $this->option('retries');

        $this->info("╔══════════════════════════════════════════════╗");
        $this->info("║     Camunda Incident Simulator               ║");
        $this->info("╚══════════════════════════════════════════════╝");
        $this->info("Topic   : {$topic}");
        $this->info("Retries : {$retries} (0 = instant incident)");
        if ($targetPid) {
            $this->info("Target  : {$targetPid}");
        }
        $this->newLine();

        // ── Step 1: FetchAndLock ────────────────────────────────────────
        $this->info("⏳ Fetching external tasks for topic [{$topic}]...");

        $response = Http::withBasicAuth('demo', 'demo')
            ->post("{$this->base}/external-task/fetchAndLock", [
                'workerId'  => $this->workerId,
                'maxTasks'  => 10,
                'topics'    => [[
                    'topicName'    => $topic,
                    'lockDuration' => 30000,
                ]],
            ]);

        if (!$response->successful()) {
            $this->error("❌ fetchAndLock failed: HTTP {$response->status()}");
            $this->error($response->body());
            return self::FAILURE;
        }

        $tasks = $response->json() ?? [];

        if (empty($tasks)) {
            $this->warn("⚠️  No tasks found for topic [{$topic}].");
            $this->newLine();
            $this->line("Possible reasons:");
            $this->line("  • No process instance is waiting on this topic");
            $this->line("  • The task is already locked by another worker");
            $this->newLine();
            $this->line("Try starting an instance first:");
            $this->line("  curl -u demo:demo -X POST \\");
            $this->line("    http://localhost:8080/engine-rest/process-definition/key/incident-test-process/start \\");
            $this->line("    -H 'Content-Type: application/json' -d '{}'");
            return self::SUCCESS;
        }

        // Filter by process instance if requested
        if ($targetPid) {
            $tasks = array_filter($tasks, fn($t) => ($t['processInstanceId'] ?? '') === $targetPid);
            $tasks = array_values($tasks);
        }

        $this->info("✅ Found " . count($tasks) . " task(s). Failing them all to trigger incidents...");
        $this->newLine();

        $incidentCount = 0;

        foreach ($tasks as $task) {
            $taskId     = $task['id'];
            $instanceId = $task['processInstanceId'] ?? 'unknown';
            $taskName   = $task['topicName'] ?? $topic;

            $this->line("  → Task ID         : {$taskId}");
            $this->line("    Process Instance : {$instanceId}");
            $this->line("    Topic            : {$taskName}");

            // ── Step 2: Report failure with retries=0 ──────────────────
            // When retries reaches 0, Camunda automatically creates an incident
            $failResponse = Http::withBasicAuth('demo', 'demo')
                ->post("{$this->base}/external-task/{$taskId}/failure", [
                    'workerId'     => $this->workerId,
                    'errorMessage' => 'SIMULATED INCIDENT: External API call failed intentionally for testing purposes.',
                    'errorDetails' => "Stack trace (simulated):\n"
                        . "App\\Services\\ExternalApiService::call() line 42\n"
                        . "Connection refused: http://external-api.example.com/endpoint\n"
                        . "Timeout after 30000ms",
                    'retries'      => $retries,       // 0 = instant incident
                    'retryTimeout' => 0,
                ]);

            if ($failResponse->successful() || $failResponse->status() === 204) {
                $this->info("    ✅ Task failed → retries set to {$retries}");

                if ($retries === 0) {
                    $this->info("    🔴 INCIDENT CREATED for instance {$instanceId}");
                    $incidentCount++;

                    // Verify the incident was actually created
                    sleep(1);
                    $this->verifyIncident($instanceId, $taskId);
                } else {
                    $this->warn("    ⚠️  Task will retry {$retries} more time(s) before becoming an incident");
                }
            } else {
                $this->error("    ❌ Failed to report task failure: HTTP {$failResponse->status()}");
                $this->error("    " . $failResponse->body());
            }

            $this->newLine();
        }

        // ── Summary ────────────────────────────────────────────────────
        $this->newLine();
        $this->info("╔══════════════════════════════════════════════╗");
        if ($incidentCount > 0) {
            $this->info("║  🔴 {$incidentCount} incident(s) created successfully!      ║");
        } else {
            $this->info("║  ✅ Tasks processed (check retries setting)  ║");
        }
        $this->info("╚══════════════════════════════════════════════╝");
        $this->newLine();
        $this->line("👉 Check Camunda Cockpit → Processes → Incident Test Process");
        $this->line("   http://localhost:8080/camunda/app/cockpit/default/#/processes");
        $this->newLine();
        $this->line("To RESOLVE the incident later, run:");
        $this->line("   php artisan camunda:resolve-incident --process-instance=<id>");

        return self::SUCCESS;
    }

    /**
     * Verify the incident was created and display its details
     */
    private function verifyIncident(string $instanceId, string $taskId): void
    {
        $response = Http::withBasicAuth('demo', 'demo')
            ->get("{$this->base}/incident", [
                'processInstanceId' => $instanceId,
            ]);

        if ($response->successful()) {
            $incidents = $response->json() ?? [];
            if (!empty($incidents)) {
                foreach ($incidents as $incident) {
                    $this->line("    📋 Incident ID      : " . ($incident['id'] ?? 'N/A'));
                    $this->line("    📋 Incident Type    : " . ($incident['incidentType'] ?? 'N/A'));
                    $this->line("    📋 Incident Message : " . ($incident['incidentMessage'] ?? 'N/A'));
                    $this->line("    📋 Activity ID      : " . ($incident['activityId'] ?? 'N/A'));
                }
            } else {
                $this->warn("    ⏳ Incident not yet visible (may take a moment)");
            }
        }
    }
}
