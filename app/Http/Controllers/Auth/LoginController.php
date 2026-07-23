<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;        // ← THIS WAS MISSING
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\UserSession;


class LoginController extends Controller
{
    public function showForm()
    {
        if (str_contains(request()->url(), '/admin/login')) {
            return view('backoffice.login.login');
        }
        return view('auth.login');
    }

    /**
     * Main Login Method
     */
    public function login(Request $request)
    {
        $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $remember     = $request->boolean('remember');
        $loginInput   = $request->input('login');
        $isAdminRoute = str_contains($request->url(), '/admin/login');

        // Find user manually (supports email or CIN)
        $user = $this->findUserByLogin($loginInput);

        if (!$user || !Hash::check($request->password, $user->getAuthPassword())) {
            throw ValidationException::withMessages([
                'login' => __('auth.failed'),
            ]);
        }

        // Check if account is active
        if (!$user->actif) {
            throw ValidationException::withMessages([
                'login' => 'Votre compte est désactivé. Contactez l\'administration.',
            ]);
        }

        // If logging in through the admin form, the user must be an admin.
        // Checked here (before MFA) so a non-admin never even reaches the
        // MFA challenge for an account that will be rejected anyway.
        if ($isAdminRoute && !$user->isAdmin()) {
            throw ValidationException::withMessages([
                'login' => 'Accès refusé. Vous n\'êtes pas administrateur.',
            ]);
        }

        // ── MFA CHECK ─────────────────────────────────────
        if ($user->two_factor_confirmed_at && $user->two_factor_enabled) {
            // Do NOT login yet → Redirect to MFA challenge
            session([
                'mfa_user_id'  => $user->id,
                'mfa_remember' => $remember,
                'mfa_is_admin' => $isAdminRoute, // remember where the login came from
            ]);

            return redirect()->route('mfa.challenge');
        }

        // No MFA required → Normal login
        Auth::login($user, $remember);
        $request->session()->regenerate();

        // Track this session
        $this->trackUserSession($user, $request);

        // Redirect logic
        if ($isAdminRoute) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(
            $user->isAdmin() ? route('admin.dashboard') : route('dashboard')
        );
    }

    /**
     * Helper: Find user by email or CIN
     */
    protected function findUserByLogin(string $loginInput): ?User
    {
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            return User::where('email', $loginInput)->first();
        }

        if (is_numeric($loginInput) && strlen($loginInput) === 8) {
            return User::where('cin', $loginInput)->first();
        }

        return User::where('nom', $loginInput)->first();
    }

    /**
     * Track the user session
     */
    protected function trackUserSession(User $user, Request $request)
    {
        try {
            $userAgent = $request->userAgent() ?? 'Unknown';

            // Parse user agent to get browser and OS
            $browser = $this->parseBrowser($userAgent);
            $os = $this->parseOS($userAgent);

            UserSession::create([
                'user_id'       => $user->id,
                'session_id'    => session()->getId(),
                'browser'       => $browser,
                'os'            => $os,
                'user_agent'    => $userAgent,
                'ip_address'    => $request->ip(),
                'device_name'   => $this->getDeviceName($userAgent, $os),
                'logged_in_at'  => now(),
                'last_activity' => now(),
            ]);
        } catch (\Exception $e) {
            \Log::warning('Failed to track user session', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Parse browser from user agent
     */
    protected function parseBrowser($userAgent)
    {
        if (str_contains($userAgent, 'Chrome') && !str_contains($userAgent, 'Chromium')) {
            return 'Chrome';
        }
        if (str_contains($userAgent, 'Firefox')) {
            return 'Firefox';
        }
        if (str_contains($userAgent, 'Safari') && !str_contains($userAgent, 'Chrome')) {
            return 'Safari';
        }
        if (str_contains($userAgent, 'Edg')) {
            return 'Edge';
        }
        if (str_contains($userAgent, 'OPR') || str_contains($userAgent, 'Opera')) {
            return 'Opera';
        }
        if (str_contains($userAgent, 'Trident')) {
            return 'Internet Explorer';
        }
        return 'Unknown Browser';
    }

    /**
     * Parse OS from user agent
     */
    protected function parseOS($userAgent)
    {
        if (str_contains($userAgent, 'Windows')) {
            return 'Windows';
        }
        if (str_contains($userAgent, 'Macintosh') || str_contains($userAgent, 'Mac OS X')) {
            return 'macOS';
        }
        if (str_contains($userAgent, 'iPhone') || str_contains($userAgent, 'iPad')) {
            return 'iOS';
        }
        if (str_contains($userAgent, 'Android')) {
            return 'Android';
        }
        if (str_contains($userAgent, 'Linux')) {
            return 'Linux';
        }
        return 'Unknown OS';
    }

    /**
     * Get device name from user agent and OS
     */
    protected function getDeviceName($userAgent, $os)
    {
        if (str_contains($userAgent, 'iPhone')) {
            return 'iPhone';
        }
        if (str_contains($userAgent, 'iPad')) {
            return 'iPad';
        }
        if (str_contains($userAgent, 'Android')) {
            return 'Android Device';
        }
        if ($os === 'Windows') {
            return 'Windows PC';
        }
        if ($os === 'macOS') {
            return 'Mac';
        }
        if ($os === 'Linux') {
            return 'Linux Machine';
        }
        return 'Device';
    }

    public function logout(Request $request)
    {
        // Delete the current session record
        try {
            UserSession::where('session_id', session()->getId())->delete();
        } catch (\Exception $e) {
            \Log::warning('Failed to delete session record', ['error' => $e->getMessage()]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
