<?php
// app/Services/CamundaService.php
namespace App\Services;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class CamundaService
{
    protected string $base = 'http://localhost:8080/engine-rest';

    public function getProcessDefinitions(): array
    {
        return Http::get("{$this->base}/process-definition", ['latestVersion' => true])
            ->json() ?? [];
    }

    // Get running (active) instances
    public function getRunningInstances(string $processKey): array
    {
        return Http::get("{$this->base}/process-instance", [
            'processDefinitionKey' => $processKey,
            'active' => true   // Important: only active ones
        ])->json() ?? [];
    }

    // Get finished (completed) historic instances count or list
    public function getFinishedInstancesCount(string $processKey): int
    {
        $response = Http::get("{$this->base}/history/process-instance/count", [
            'processDefinitionKey' => $processKey,
            'finished' => true
        ]);

        return $response->json()['count'] ?? 0;
    }

    // Get list of historic instances (for the instances table)
    public function getHistoricInstances(string $processKey): array
    {
        return Http::get("{$this->base}/history/process-instance", [
            'processDefinitionKey' => $processKey,
            'finished' => false,   // change to true if you want only completed
            'sortBy' => 'startTime',
            'sortOrder' => 'desc'
        ])->json() ?? [];
    }

    public function getTasks(string $processInstanceId): array
    {
        return Http::get("{$this->base}/task", ['processInstanceId' => $processInstanceId])
            ->json() ?? [];
    }

public function startInstance(string $processKey, array $variables = []): array
{
    if (empty($processKey)) {
        Log::error('[startInstance] processKey is empty!');
        return ['_error' => 'processKey is empty'];
    }

    $formattedVariables = [];

    foreach ($variables as $key => $value) {
        // Handle file arrays - convert to JSON string
        if (is_array($value)) {
            // Check if it's a file info array
            if (isset($value['path']) || isset($value['name'])) {
                // Convert to JSON string to pass to Camunda
                $formattedVariables[$key] = [
                    'value' => json_encode($value),
                    'type' => 'String'
                ];
                Log::info("[startInstance] Added file variable as JSON", ['key' => $key]);
            } else {
                // Skip other arrays that can't be handled
                Log::warning("[startInstance] Skipping array variable", ['key' => $key]);
            }
            continue;
        }

        // Handle null values
        if (is_null($value)) {
            continue;
        }

        // Handle base64 strings (files)
        if (is_string($value) && (strpos($value, 'data:') === 0 || strlen($value) > 5000)) {
            // Store file info instead of full base64
            Log::info("[startInstance] Skipping large string (likely base64 file)", [
                'key' => $key,
                'length' => strlen($value)
            ]);
            continue;
        }

        // Handle scalar values normally
        if (is_string($value) || is_numeric($value) || is_bool($value)) {
            $formattedVariables[$key] = [
                'value' => $value,
                'type'  => is_bool($value) ? 'Boolean' :
                           (is_int($value) || is_float($value) ? 'Integer' : 'String')
            ];
        }
    }

    $payload = [
        'variables' => $formattedVariables,
        'withVariablesInReturn' => true
    ];

    $response = Http::post("{$this->base}/process-definition/key/{$processKey}/start", $payload);

    Log::info('[startInstance] Camunda response', [
        'processKey' => $processKey,
        'status'     => $response->status(),
        'variables_count' => count($formattedVariables),
    ]);

    if (!$response->successful()) {
        Log::error('[startInstance] Failed to start process', [
            'processKey' => $processKey,
            'status' => $response->status(),
            'body' => $response->body()
        ]);
    }

    return $response->json() ?? [];
}



public function completeTask(string $taskId, array $variables = []): array
{
    try {
        $response = Http::withBasicAuth('demo', 'demo')
            ->post("{$this->base}/task/{$taskId}/complete", [
                'variables' => $variables
            ]);

        // 204 No Content is success for complete task
        if ($response->status() === 204) {
            return ['success' => true, 'status' => 204];
        }

        if ($response->successful()) {
            return ['success' => true, 'data' => $response->json()];
        }

        \Log::error('Camunda complete task failed', [
            'taskId' => $taskId,
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        return [
            'success' => false,
            'error' => "Camunda error: " . $response->body()
        ];

    } catch (\Exception $e) {
        \Log::error('Exception in completeTask', [
            'taskId' => $taskId,
            'error' => $e->getMessage()
        ]);

        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}



public function getTasksByAssignee(string $assignee): array
{
    return Http::get("{$this->base}/task", [
        'assignee' => $assignee,
        'active'   => true
    ])->json() ?? [];
}
/**
 * Récupère les tâches où l'utilisateur est dans candidateUsers ou candidateGroups
 * (utile si tu passes plus tard à ce mode)
 */
public function getTasksForUser(string $userIdentifier, array $groups = []): array
{
    $query = ['active' => true];

    if (!empty($groups)) {
        $query['candidateGroups'] = implode(',', $groups);
    } else {
        $query['assignee'] = $userIdentifier;   // mode actuel
    }

    return Http::get("{$this->base}/task", $query)->json() ?? [];
}


public function getTasksWithCandidateUser(string $userId): array
{
    return Http::get("{$this->base}/task", [
        'candidateUser' => $userId
    ])->json() ?? [];
}

public function getTasksWithCandidateGroups(array $groups): array
{
    if (empty($groups)) return [];

    return Http::get("{$this->base}/task", [
        'candidateGroups' => implode(',', $groups)
    ])->json() ?? [];
}
/**
 * Get user groups
 */
public function getUserGroups(string $email, int $laravelUserId = null): array
{
    try {
        $camundaUserId = $this->generateCamundaUserId($email, $laravelUserId);

        $response = Http::withBasicAuth('demo', 'demo')
            ->get("{$this->base}/group", [
                'member' => $camundaUserId
            ]);

        if (!$response->successful()) {
            return [];
        }

        $groups = $response->json() ?? [];
        return array_column($groups, 'id');

    } catch (\Exception $e) {
        return [];
    }
}
/**
 * Get all available groups from Camunda (for modeler dropdown)
 */
// app/Services/CamundaService.php - Add these methods

/**
 * Create a new group in Camunda
 */
public function createGroup(string $groupId, string $groupName, string $groupType = 'WORKFLOW'): array
{
    try {
        $response = Http::withBasicAuth('demo', 'demo')
            ->post("{$this->base}/group/create", [
                'id' => $groupId,
                'name' => $groupName,
                'type' => $groupType
            ]);

        if (!$response->successful()) {
            \Log::error('Failed to create group in Camunda', [
                'groupId' => $groupId,
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return ['success' => false, 'error' => $response->body()];
        }

        return ['success' => true, 'data' => $response->json()];

    } catch (\Exception $e) {
        \Log::error('Error creating group', ['error' => $e->getMessage()]);
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Add a user to a group in Camunda
 */
public function addUserToGroup(string $groupId, string $email, int $laravelUserId = null): array
{
    try {
        $camundaUserId = $this->generateCamundaUserId($email, $laravelUserId);

        $response = Http::withBasicAuth('demo', 'demo')
            ->put("{$this->base}/group/{$groupId}/members/{$camundaUserId}");

        if (!$response->successful()) {
            return ['success' => false, 'error' => $response->body()];
        }

        return ['success' => true];

    } catch (\Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}


/**
 * Remove a user from a group
 */
public function removeUserFromGroup(string $groupId, string $email, int $laravelUserId = null): array
{
    try {
        $camundaUserId = $this->generateCamundaUserId($email, $laravelUserId);

        $response = Http::withBasicAuth('demo', 'demo')
            ->delete("{$this->base}/group/{$groupId}/members/{$camundaUserId}");

        if (!$response->successful()) {
            return ['success' => false, 'error' => $response->body()];
        }

        return ['success' => true];

    } catch (\Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Get all groups from Camunda
 */
public function getAllGroups(): array
{
    try {
        $response = Http::withBasicAuth('demo', 'demo')
            ->get("{$this->base}/group");

        if (!$response->successful()) {
            return [];
        }

        return $response->json() ?? [];

    } catch (\Exception $e) {
        \Log::error('Error fetching all groups', ['error' => $e->getMessage()]);
        return [];
    }
}

/**
 * Get members of a specific group
 */
public function getGroupMembers(string $groupId): array
{
    try {
        $response = Http::withBasicAuth('demo', 'demo')
            ->get("{$this->base}/user", [
                'memberOfGroup' => $groupId,
            ]);

        if (!$response->successful()) {
            \Log::warning('getGroupMembers failed', [
                'groupId' => $groupId,
                'status'  => $response->status(),
                'body'    => $response->body(),
            ]);
            return [];
        }

        // Camunda returns array of user profile objects:
        // [{ "id": "nerd70843", "firstName": "...", "lastName": "...", "email": "..." }]
        return $response->json() ?? [];

    } catch (\Exception $e) {
        \Log::error('Error fetching group members', [
            'groupId' => $groupId,
            'error'   => $e->getMessage(),
        ]);
        return [];
    }
}


/**
 * Delete a group from Camunda
 */
public function deleteGroup(string $groupId): array
{
    try {
        $response = Http::withBasicAuth('demo', 'demo')
            ->delete("{$this->base}/group/{$groupId}");

        if (!$response->successful()) {
            return ['success' => false, 'error' => $response->body()];
        }

        return ['success' => true];

    } catch (\Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
/**
 * Create a user in Camunda with a valid ID
 */
public function createUser(string $email, string $firstName, string $lastName, int $laravelUserId = null, string $password = 'password'): array
{
    try {
        $camundaUserId = $this->generateCamundaUserId($email, $laravelUserId);

        $payload = [
            'profile' => [
                'id' => $camundaUserId,
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
            ],
            'credentials' => [
                'password' => $password
            ]
        ];

        \Log::info('Creating user in Camunda', [
            'email' => $email,
            'camundaUserId' => $camundaUserId,
            'payload' => $payload
        ]);

        $response = Http::withBasicAuth('demo', 'demo')
            ->post("{$this->base}/user/create", $payload);

        if (!$response->successful()) {
            \Log::error('Failed to create user in Camunda', [
                'email' => $email,
                'camundaUserId' => $camundaUserId,
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return ['success' => false, 'error' => $response->body()];
        }

        return ['success' => true, 'camundaUserId' => $camundaUserId];

    } catch (\Exception $e) {
        \Log::error('Error creating user', ['error' => $e->getMessage()]);
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Check if a user exists in Camunda
 */
public function userExists(string $email, int $laravelUserId = null): bool
{
    try {
        $camundaUserId = $this->generateCamundaUserId($email, $laravelUserId);
        $response = Http::withBasicAuth('demo', 'demo')
            ->get("{$this->base}/user/{$camundaUserId}/profile");

        return $response->successful();
    } catch (\Exception $e) {
        return false;
    }
}

/**
 * Generate a valid Camunda user ID from email or user data
 * Camunda only allows: lowercase letters (a-z) and numbers (0-9)
 * No underscores, no dots, no special characters, no @
 */
private function generateCamundaUserId(string $email, int $userId = null): string
{
    // Extract username from email (before @)
    $username = explode('@', $email)[0];

    // Remove all special characters, keep only letters and numbers
    $cleanId = preg_replace('/[^a-z0-9]/', '', strtolower($username));

    // If result is empty or too short, use admin + user ID
    if (strlen($cleanId) < 3 && $userId) {
        $cleanId = 'admin' . $userId;
    }

    // If still empty, use a fallback
    if (empty($cleanId)) {
        $cleanId = 'user' . ($userId ?? rand(100, 999));
    }

    return $cleanId;
}
}
