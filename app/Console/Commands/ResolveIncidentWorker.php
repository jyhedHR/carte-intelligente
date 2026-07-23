<?php
// app/Console/Commands/ResolveIncidentWorker.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ResolveIncidentWorker extends Command
{
    protected $signature = 'camunda:resolve-incident
                            {--process-instance= : Process instance ID whose incidents to resolve}
                            {--incident-id=      : Specific incident ID to resolve}
                            {--topic=failing-external-call : Topic to complete after resolving}';

    protected $description = 'Resolve a Camunda incident by setting retries back to 1 and completing the task';

    private string $base     = 'http://localhost:8080/engine-rest';
    private string $workerId = 'laravel-incident-resolver';

    public function handle(): int
    {
        $instanceId = $this->option('process-instance');
        $incidentId = $this->option('incident-id');
        $topic      = $this->option('topic');

        $this->info("╔══════════════════════════════════════════════╗");
        $this->info("║     Camunda Incident Resolver                ║");
        $this->info("╚══════════════════════════════════════════════╝");

        // ── Find incidents ─────────────────────────────────────────────
        $query = $incidentId
            ? ['incidentId' => $incidentId]
            : ['processInstanceId' => $instanceId];

        $incidentsResponse = Http::withBasicAuth('demo', 'demo')
            ->get("{$this->base}/incident", $query);

        $incidents = $incidentsResponse->json() ?? [];

        if (empty($incidents)) {
            $this->warn("⚠️  No incidents found.");
            return self::SUCCESS;
        }

        $this->info("Found " . count($incidents) . " incident(s) to resolve:");
        $this->newLine();

        foreach ($incidents as $incident) {
            $iId        = $incident['id'];
            $jobId      = $incident['configuration'] ?? null; // For failed jobs, config = jobId
            $activityId = $incident['activityId'] ?? 'N/A';
            $message    = $incident['incidentMessage'] ?? 'N/A';

            $this->line("  Incident ID  : {$iId}");
            $this->line("  Activity     : {$activityId}");
            $this->line("  Message      : {$message}");
            $this->line("  Job ID       : " . ($jobId ?? 'N/A'));
            $this->newLine();

            if ($jobId) {
                // ── Step 1: Set retries back to 1 so Camunda unlocks the job ──
                $retriesResponse = Http::withBasicAuth('demo', 'demo')
                    ->put("{$this->base}/job/{$jobId}/retries", ['retries' => 1]);

                if ($retriesResponse->successful() || $retriesResponse->status() === 204) {
                    $this->info("  ✅ Retries set back to 1 — incident cleared");
                } else {
                    $this->error("  ❌ Failed to set retries: " . $retriesResponse->body());
                    continue;
                }

                // ── Step 2: FetchAndLock then complete successfully ────────
                sleep(1);

                $lockResponse = Http::withBasicAuth('demo', 'demo')
                    ->post("{$this->base}/external-task/fetchAndLock", [
                        'workerId' => $this->workerId,
                        'maxTasks' => 1,
                        'topics'   => [[
                            'topicName'    => $topic,
                            'lockDuration' => 30000,
                        ]],
                    ]);

                $tasks = $lockResponse->json() ?? [];

                if (!empty($tasks)) {
                    $taskId = $tasks[0]['id'];

                    $completeResponse = Http::withBasicAuth('demo', 'demo')
                        ->post("{$this->base}/external-task/{$taskId}/complete", [
                            'workerId'  => $this->workerId,
                            'variables' => [],
                        ]);

                    if ($completeResponse->successful() || $completeResponse->status() === 204) {
                        $this->info("  ✅ Task completed — process will continue");
                    } else {
                        $this->warn("  ⚠️  Could not complete task (may need manual completion in Cockpit)");
                    }
                } else {
                    $this->warn("  ⚠️  No task found to complete — retries were reset, check Cockpit");
                }
            }

            $this->newLine();
        }

        $this->info("Done! Check Cockpit to verify the process continued.");
        return self::SUCCESS;
    }
}
