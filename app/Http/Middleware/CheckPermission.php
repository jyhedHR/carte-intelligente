<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
   public function handle(Request $request, Closure $next, $permission)
{
    if (!auth()->user()->hasPermission($permission)) {
        abort(403);
    }

    return $next($request);
}

}
