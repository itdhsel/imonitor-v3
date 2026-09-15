<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;

class SsoController extends Controller
{
    public function handleCallback(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            return response()->json(['error' => 'SSO Token Missing'], 400);
        }

        // 1. Validate token with Laravel SSO Server
        $response = Http::withHeaders([
            'Host' => 'hsel-sso.ddev.site',
            'Accept' => 'application/json'
        ])
        ->withoutVerifying()
        ->post('https://10.27.101.103/api/sso/validate', [
            'token' => $token
        ]);

        if ($response->successful() && $response->json('valid')) {
            $ssoUser = $response->json('user');
            
            // 2. Safely extract username regardless of AD format
            $rawIdentifier = $ssoUser['username'] ?? $ssoUser['email'] ?? 'unknown_user';
            $shortUsername = str_contains($rawIdentifier, '@') 
                ? explode('@', $rawIdentifier)[0] 
                : $rawIdentifier;

            // Enforce max column length safety (truncate if longer than 30 chars)
            $shortUsername = substr($shortUsername, 0, 30);

            // 3. Find the user, or create them if they are new
            $user = User::where('login_username', $shortUsername)->first();

            if (!$user) {
                // BRAND NEW USER: Assign the default 'user' role
                $user = User::create([
                    'login_username' => $shortUsername,
                    'name'           => $ssoUser['name'] ?? $shortUsername,
                    'role'           => 'user', // Default lowest permission
                    'login_pwd'      => md5(Str::random(16)),
                    'login_stamp'    => now()
                ]);
            } else {
                // EXISTING USER: Update their timestamp/name, but PRESERVE their role
                $user->update([
                    'name'        => $ssoUser['name'] ?? $shortUsername,
                    'login_stamp' => now()
                ]);
            }

        // Return direct error payload for debugging if validation fails
        return response()->json([
            'error' => 'SSO Validation Failed',
            'sso_response' => $response->json()
        ], 403);
    }
}