<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SsoController extends Controller
{
    public function handleCallback(Request $request)
    {
        $adUsername = $this->extractUsernameFromToken($request->token); 
        
        // Search the legacy 'userlist' table using the correct column
        $user = User::where('login_username', $adUsername)->first();

        if ($user) {
            Auth::login($user);
            $user->update(['current_session_id' => session()->getId()]);
            return redirect('/monitor');
        }

        return redirect('/')->withErrors(['Access Denied: User not found in iMonitor.']);
    }

    private function extractUsernameFromToken($token)
    {
        // Your custom token validation logic here
        return 'extracted_username';
    }
}