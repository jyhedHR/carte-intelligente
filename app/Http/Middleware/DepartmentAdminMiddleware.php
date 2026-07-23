<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Middleware to check if user is Department Admin
 */
class DepartmentAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isDepartmentAdmin()) {
            abort(403, 'Unauthorized - Department Admin access required');
        }

        return $next($request);
    }
}


