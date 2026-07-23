<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/workflows/*',     // This excludes all your workflow routes
        'chat/*',              // Exclude chat API endpoints for CSRF protection
        'chat/ask',            // Explicit route
        'chat/status',         // Explicit route
        'chat/health',         // Explicit route
    ];
}
