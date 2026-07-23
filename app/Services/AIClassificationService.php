<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIClassificationService
{
    protected string $apiUrl;
    protected int $timeout;

    public function __construct()
    {
        $this->apiUrl = config('ai.api_url', 'http://localhost:8001');
        $this->timeout = config('ai.timeout', 30);
    }

    /**
     * Classify a text using the AI API
     */
    public function classify(string $text, ?string $demandeId = null, ?string $directionId = null): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->post($this->apiUrl . '/api/v1/classify', [
                    'texte_libre' => $text,
                    'demande_id' => $demandeId,
                    'direction_id' => $directionId,
                    'locale' => app()->getLocale(),
                ]);

            if ($response->failed()) {
                Log::error('IA API error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return $this->getFallbackResponse();
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('IA API exception', ['message' => $e->getMessage()]);
            return $this->getFallbackResponse();
        }
    }

    /**
     * Send human feedback to improve the model
     */
    public function sendFeedback(
        string $demandeId,
        string $aiPrediction,
        ?string $humanCorrection,
        bool $wasCorrect,
        string $action,
        int $agentId,
        ?string $comment = null
    ): array {
        try {
            $response = Http::timeout($this->timeout)
                ->post($this->apiUrl . '/api/v1/feedback', [
                    'demande_id' => $demandeId,
                    'ai_prediction' => $aiPrediction,
                    'human_correction' => $humanCorrection,
                    'was_correct' => $wasCorrect,
                    'action' => $action,
                    'agent_id' => $agentId,
                    'comment' => $comment,
                    'timestamp' => now()->toIso8601String(),
                ]);

            if ($response->failed()) {
                Log::warning('IA Feedback error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('IA Feedback exception', ['message' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Get model health status
     */
    public function health(): array
    {
        try {
            $response = Http::timeout(5)
                ->get($this->apiUrl . '/health');

            if ($response->failed()) {
                return ['status' => 'unhealthy', 'error' => $response->body()];
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('IA Health check failed', ['message' => $e->getMessage()]);
            return ['status' => 'unhealthy', 'error' => $e->getMessage()];
        }
    }

    /**
     * Get detailed model metrics (accuracy, drift, etc.)
     */
    public function getMetrics(): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get($this->apiUrl . '/api/v1/admin/metrics');

            if ($response->failed()) {
                return ['error' => 'Unable to fetch metrics'];
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('IA Metrics error', ['message' => $e->getMessage()]);
            return ['error' => $e->getMessage()];
        }
    }

    private function getFallbackResponse(): array
    {
        return [
            'workflow_key' => null,
            'label' => null,
            'direction' => null,
            'confidence_score' => 0.0,
            'is_ambiguous' => true,
            'suggestion_auto' => false,
            'fallback' => true,
            'reasoning' => 'Service IA temporairement indisponible'
        ];
    }
}
