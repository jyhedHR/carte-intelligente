<?php

return [
    'api_url' => env('AI_API_URL', 'http://localhost:8001'),
    'timeout' => env('AI_API_TIMEOUT', 30),
    'confidence_threshold' => env('AI_CONFIDENCE_THRESHOLD', 0.85),
];
