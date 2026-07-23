<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * RedirectIfMfaNotSetup
 *
 * After email verification, force the user to set up MFA before accessing
 * any protected route. Applied to the 'web' middleware group on guarded routes.
 *
 * Excludes:
 *  - MFA routes themselves (mfa.*)
 *  - Logout
 *  - Verification routes
 */
class RedirectIfMfaNotSetup
{
    private array $except = [
        'mfa.*',
        'logout',
        'verification.*',
        'profile.avatar',   // allow avatar upload without MFA (low risk)
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        // Email not yet verified — let the verify-email middleware handle it
        if (!$user->hasVerifiedEmail()) {
            return $next($request);
        }

        // MFA already confirmed — all good
        if ($user->two_factor_confirmed_at) {
            return $next($request);
        }

        // Check if current route is in the exclusion list
        foreach ($this->except as $pattern) {
            if ($request->routeIs($pattern)) {
                return $next($request);
            }
        }

        // Redirect to MFA setup
        return redirect()->route('mfa.setup')
            ->with('info', 'Veuillez configurer la double authentification pour accéder à votre espace.');
    }
}
