<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, $roles)) {
            // Block access if user doesn't have the required role
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}