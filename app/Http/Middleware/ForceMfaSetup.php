<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ForceMfaSetup Middleware
 *
 * Enforces MFA setup when Super Admin has enabled MFA for a user.
 * User cannot access the application until MFA setup is completed.
 *
 * Whitelisted routes (don't require MFA setup):
 * - /mfa/setup - MFA setup page itself
 * - /mfa/verify-setup - MFA verification
 * - /mfa/backup-codes - Backup codes display
 * - /logout - Allow logout
 * - /api/logout - API logout
 */
class ForceMfaSetup
{
    /**
     * Routes that don't require MFA to be configured (whitelisted)
     */
    private array $whitelistedRoutes = [
        'mfa.setup',
        'mfa.verify-setup',
        'mfa.backup-codes.show',
        'logout',
    ];

    /**
     * Route patterns that don't require MFA (prefix patterns)
     */
    private array $whitelistedPatterns = [
        '/mfa/',
        '/logout',
        '/api/logout',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Only check authenticated users
        if (!$user) {
            return $next($request);
        }

        // Check if MFA was forced by admin and not yet confirmed
        $mfaForcedByAdmin = $user->mfa_forced_by_admin ?? false;
        $mfaConfirmedAt = $user->two_factor_confirmed_at ?? null;

        // If no forced MFA, allow request
        if (!$mfaForcedByAdmin || $mfaConfirmedAt) {
            return $next($request);
        }

        // ── Check if current route is whitelisted ──
        if ($this->isWhitelistedRoute($request)) {
            return $next($request);
        }

        // ── MFA is forced but not completed, redirect to setup ──
        return redirect()->route('mfa.setup')
            ->with('warning', 'L\'authentification à deux facteurs est obligatoire. Veuillez configurer votre authenticateur.');
    }

    /**
     * Check if the current route is whitelisted
     */
    private function isWhitelistedRoute(Request $request): bool
    {
        $currentRoute = $request->route()?->getName();

        // Check named routes
        if ($currentRoute && in_array($currentRoute, $this->whitelistedRoutes)) {
            return true;
        }

        // Check path patterns
        $path = $request->getPathInfo();
        foreach ($this->whitelistedPatterns as $pattern) {
            if (str_starts_with($path, $pattern)) {
                return true;
            }
        }

        return false;
    }
}
