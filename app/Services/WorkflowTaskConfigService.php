<?php

namespace App\Services;

use App\Models\WorkflowTaskConfig;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WorkflowTaskConfigService
{
    /**
     * Get or create task configuration
     */
    public function getTaskConfig(string $taskId, ?int $workflowId = null)
    {
        return WorkflowTaskConfig::where('task_id', $taskId)
            ->where(function ($q) use ($workflowId) {
                if ($workflowId) {
                    $q->where('workflow_id', $workflowId)->orWhereNull('workflow_id');
                }
            })
            ->latest()
            ->first();
    }

    /**
     * Save or update task configuration
     */
    public function saveTaskConfig(array $data)
    {
        try {
            $taskId = $data['task_id'] ?? null;
            if (!$taskId) {
                throw new \Exception('task_id is required');
            }

            $config = WorkflowTaskConfig::updateOrCreate(
                [
                    'task_id' => $taskId,
                    'workflow_id' => $data['workflow_id'] ?? null,
                ],
                [
                    'task_name' => $data['task_name'] ?? 'Unnamed Task',
                    'description' => $data['description'] ?? null,
                    'custom_actions' => $data['custom_actions'] ?? [],
                    'custom_fields' => $data['custom_fields'] ?? [],
                    'required_for_roles' => $data['required_for_roles'] ?? ['manager', 'director'],
                    'visibility_rules' => $data['visibility_rules'] ?? [],
                    'updated_by' => auth()->id(),
                ]
            );

            Log::info('Task config saved', [
                'task_id' => $taskId,
                'workflow_id' => $data['workflow_id'] ?? null,
                'config_id' => $config->id,
            ]);

            return [
                'success' => true,
                'config' => $config,
            ];
        } catch (\Exception $e) {
            Log::error('Error saving task config', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get all task configs for a workflow
     */
    public function getWorkflowTaskConfigs(int $workflowId)
    {
        return WorkflowTaskConfig::where('workflow_id', $workflowId)
            ->get()
            ->toArray();
    }

    /**
     * Validate custom action
     * Matches the shape actually produced by the modeler UI and consumed by the
     * task popup: { name, color }. (Previously required 'label' and a 'type'
     * enum that nothing ever sent, so every save with a custom action failed
     * validation with a 422.)
     */
    public function validateCustomAction(array $action): bool
    {
        return isset($action['name']) && is_string($action['name']) && trim($action['name']) !== '';
    }

    /**
     * Validate custom field
     * The popup renderer reads field.name (used for the input id / submitted
     * variable key), field.label, and field.type, so all three are required.
     */
    public function validateCustomField(array $field): bool
    {
        return isset($field['name'], $field['label'], $field['type'])
            && trim($field['name']) !== ''
            && trim($field['label']) !== ''
            && in_array($field['type'], ['text', 'textarea', 'select', 'checkbox', 'radio', 'date', 'email']);
    }

    /**
     * Delete task config
     */
    public function deleteTaskConfig(int $configId)
    {
        try {
            WorkflowTaskConfig::destroy($configId);
            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Build task popup data for display
     */
    public function buildTaskPopupData(string $taskId, ?int $workflowId = null)
    {
        $config = $this->getTaskConfig($taskId, $workflowId);

        if (!$config) {
            return [
                'task_id' => $taskId,
                'task_name' => 'Untitled Task',
                'description' => '',
                'custom_actions' => [],
                'custom_fields' => [],
                'required_for_roles' => [],
            ];
        }

        return [
            'task_id' => $config->task_id,
            'task_name' => $config->task_name,
            'description' => $config->description,
            'custom_actions' => $config->custom_actions ?? [],
            'custom_fields' => $config->custom_fields ?? [],
            'required_for_roles' => $config->required_for_roles ?? [],
            'visibility_rules' => $config->visibility_rules ?? [],
        ];
    }
}
