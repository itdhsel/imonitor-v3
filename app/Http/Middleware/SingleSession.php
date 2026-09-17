<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SingleSession
{
    public function handle(Request $request, Closure $next)
    {
        // If the user is logged in, check if their session matches the database
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->current_session_id !== session()->getId()) {
                Auth::logout();
                // Redirect back to SSO or a logged-out page
                return redirect('/')->withErrors(['error' => 'You have been logged out because your account was accessed from another device.']);
            }
        }

        return $next($request);
    }
}