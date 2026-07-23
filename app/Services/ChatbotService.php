<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class ChatbotService
{
    protected $baseUrl;
    protected $timeout;

    public function __construct()
    {
        $this->baseUrl = env('AI_API_URL', 'http://localhost:8001');
        $this->timeout = env('AI_API_TIMEOUT', 30);
    }

    /**
     * Send a question to the chatbot with improved error handling
     */
    public function chat(string $message, string $sessionId = null, string $ipAddress = null): array
    {
        try {
            $sessionId = $sessionId ?? $this->generateSessionId();
            $ipAddress = $ipAddress ?? request()->ip();

            Log::debug('Chat request initiated', [
                'message_length' => strlen($message),
                'session_id' => $sessionId,
            ]);

            $response = Http::timeout($this->timeout)
                ->post("{$this->baseUrl}/api/v1/chat", [
                    'message' => $message,
                    'session_id' => $sessionId,
                    'ip_address' => $ipAddress,
                ]);

            if ($response->failed()) {
                $statusCode = $response->status();

                // Handle different error scenarios
                if ($statusCode === 408 || $statusCode === 504) {
                    Log::warning('API timeout', ['status' => $statusCode]);
                    return $this->getTimeoutResponse();
                }

                if ($statusCode === 429) {
                    Log::warning('API quota exceeded', ['status' => $statusCode]);
                    return $this->getQuotaExceededResponse();
                }

                if ($statusCode >= 500) {
                    Log::error('API service error', ['status' => $statusCode]);
                    return $this->getServiceUnavailableResponse();
                }

                Log::error('API request failed', [
                    'status' => $statusCode,
                    'url' => "{$this->baseUrl}/api/v1/chat"
                ]);

                return $this->getFallbackResponse($message);
            }

            $data = $response->json();

            // Ensure response has required fields with defaults
            return array_merge([
                'session_id' => $sessionId,
                'reply' => 'Réponse reçue',
                'level' => null,
                'confidence' => 0,
                'timestamp' => now()->toIso8601String(),
                'is_error' => false
            ], $data);

        } catch (\Exception $e) {
            $message = $e->getMessage();

            // Categorize timeout errors
            if (stripos($message, 'timeout') !== false ||
                stripos($message, 'timed out') !== false ||
                get_class($e) === 'Illuminate\Http\Client\ConnectionException') {
                Log::warning('Connection timeout', ['error' => get_class($e)]);
                return $this->getTimeoutResponse();
            }

            // Network errors
            if (stripos($message, 'connection') !== false) {
                Log::warning('Network error', ['error' => get_class($e)]);
                return $this->getNetworkErrorResponse();
            }

            Log::error('Chatbot service error', [
                'exception' => get_class($e),
                'message' => $message
            ]);

            return $this->getFallbackResponse($message);
        }
    }

    /**
     * Get chatbot service status
     */
    public function getStatus(): array
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/health");

            if ($response->failed()) {
                return ['status' => 'error', 'message' => 'Service unavailable'];
            }

            return $response->json();

        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Fallback response when FastAPI is unavailable
     */
    protected function getFallbackResponse(string $message): array
    {
        return [
            'session_id' => $this->generateSessionId(),
            'reply' => 'Le service de consultation est temporairement indisponible. Nous revenons au plus vite. Veuillez réessayer dans quelques instants.',
            'suggestions' => ['Contactez notre support', 'Consultez notre FAQ'],
            'confidence' => 0,
            'level' => null,
            'is_error' => true,
            'error_category' => 'service_unavailable',
            'processing_time_ms' => 0,
            'quota_info' => null,
            'timestamp' => now()->toIso8601String()
        ];
    }

    /**
     * Response when request times out
     */
    protected function getTimeoutResponse(): array
    {
        return [
            'session_id' => $this->generateSessionId(),
            'reply' => 'Nous prenons un peu plus de temps que prévu pour traiter votre question. Veuillez patienter ou réessayer dans un instant.',
            'suggestions' => ['Réessayer', 'Contacter le support'],
            'confidence' => 0,
            'level' => null,
            'is_error' => true,
            'error_category' => 'timeout',
            'retry_available' => true,
            'processing_time_ms' => 0,
            'quota_info' => null,
            'timestamp' => now()->toIso8601String()
        ];
    }

    /**
     * Response when quota is exceeded
     */
    protected function getQuotaExceededResponse(): array
    {
        return [
            'session_id' => $this->generateSessionId(),
            'reply' => 'Le volume de demandes est actuellement élevé et nous passons en mode simplifié. Vous recevez une réponse directe de notre base de connaissances.',
            'suggestions' => ['Réessayer plus tard'],
            'confidence' => 0,
            'level' => null,
            'is_error' => true,
            'error_category' => 'quota_exceeded',
            'fallback_mode_active' => true,
            'processing_time_ms' => 0,
            'quota_info' => null,
            'timestamp' => now()->toIso8601String()
        ];
    }

    /**
     * Response when service is unavailable
     */
    protected function getServiceUnavailableResponse(): array
    {
        return [
            'session_id' => $this->generateSessionId(),
            'reply' => 'Le service est temporairement indisponible. Nos équipes travaillent pour rétablir le service au plus vite. Veuillez réessayer.',
            'suggestions' => ['Réessayer', 'Contacter le support'],
            'confidence' => 0,
            'level' => null,
            'is_error' => true,
            'error_category' => 'service_unavailable',
            'processing_time_ms' => 0,
            'quota_info' => null,
            'timestamp' => now()->toIso8601String()
        ];
    }

    /**
     * Response for network errors
     */
    protected function getNetworkErrorResponse(): array
    {
        return [
            'session_id' => $this->generateSessionId(),
            'reply' => 'Nous rencontrons une difficulté temporaire de connexion. C\'est généralement rapide à résoudre. Veuillez réessayer.',
            'suggestions' => ['Réessayer', 'Vérifier votre connexion'],
            'confidence' => 0,
            'level' => null,
            'is_error' => true,
            'error_category' => 'network_error',
            'retry_available' => true,
            'processing_time_ms' => 0,
            'quota_info' => null,
            'timestamp' => now()->toIso8601String()
        ];
    }

    /**
     * Generate or retrieve session ID
     * Handles both public (anonymous) and authenticated users
     */
    protected function generateSessionId(): string
    {
        // Case 1: Check if user is logged in
        if (auth()->id()) {
            return 'user_' . auth()->id();
        }

        // Case 2: Guest/Public user - use existing session or create new
        if (!session()->has('chat_session_id')) {
            session()->put('chat_session_id', 'anon_' . uniqid() . '_' . time());
        }

        return session()->get('chat_session_id');
    }
}
