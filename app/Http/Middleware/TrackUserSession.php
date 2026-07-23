<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserSession;

class TrackUserSession
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $sessionId = session()->getId();
            $ipAddress = $request->ip();
            $userAgent = $request->header('User-Agent');

            // Find or create session
            $session = UserSession::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'session_id' => $sessionId,
                ],
                [
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                    'logged_in_at' => now(),
                ]
            );

            // Update last activity
            $session->update(['last_activity' => now()]);

            // Clean old sessions (older than 30 days)
            UserSession::where('user_id', $user->id)
                ->where('last_activity', '<', now()->subDays(30))
                ->delete();

            // Clean up orphaned sessions (no matching Laravel session in store)
            $this->cleanOrphanedSessions($user->id);
        }

        return $next($request);
    }

    /**
     * Clean up sessions that no longer exist in Laravel's session store
     */
    private function cleanOrphanedSessions($userId)
    {
        try {
            $sessions = UserSession::where('user_id', $userId)->get();
            $driver = config('session.driver');

            foreach ($sessions as $session) {
                $sessionExists = false;

                if ($driver === 'file') {
                    $sessionPath = storage_path('framework/sessions/' . $session->session_id);
                    $sessionExists = file_exists($sessionPath);
                } elseif ($driver === 'database') {
                    $sessionExists = \DB::table(config('session.table'))
                        ->where('id', $session->session_id)
                        ->exists();
                } elseif ($driver === 'redis') {
                    $sessionExists = \Cache::store('redis')->has('PHPREDIS_SESSION:' . $session->session_id);
                }

                // If session doesn't exist in store anymore, delete our record
                if (!$sessionExists) {
                    $session->delete();
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to clean orphaned sessions', ['error' => $e->getMessage()]);
        }
    }
}
