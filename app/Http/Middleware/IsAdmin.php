<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        $user = Auth::user();

        // Optional: Add additional admin role checking based on your system
        // For now, just check if user is authenticated
        // You can add custom logic here to verify if the user has admin privileges

        // Example (uncomment if you have an 'is_admin' or 'role' field):
        // if (!$user->is_admin && $user->role !== 'admin') {
        //     Auth::logout();
        //     return redirect()->route('admin.login')->with('error', 'Accès refusé: vous n\'êtes pas administrateur.');
        // }

        return $next($request);
    }
}
