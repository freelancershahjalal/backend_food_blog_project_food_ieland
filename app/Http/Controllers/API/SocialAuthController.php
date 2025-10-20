<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class SocialAuthController extends Controller
{
 public function redirectToProvider($provider)
    {
        $url = Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();
        return response()->json(['redirect_url' => $url]);
    }

    // 2. This endpoint handles the callback from Google/Facebook.
    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();

            // Find or create the user
            $user = User::firstOrCreate(
                ['email' => $socialUser->getEmail()],
                [
                    'name' => $socialUser->getName(),
                    'password' => bcrypt(Str::random(24)), // Create a secure random password
                    'email_verified_at' => now(), // Social accounts are considered verified
                ]
            );

            // Create an API token for the user
            $token = $user->createToken('auth_token')->plainTextToken;

            // --- THIS IS THE CRITICAL PART ---
            // We need to redirect the user back to the FRONTEND with the token.
            // The frontend will read this token from the URL and save it.
            $frontendUrl = 'http://localhost:5173/social-login-success';
            
            return redirect()->away($frontendUrl . '?token=' . $token);

        } catch (\Exception $e) {
            // If something goes wrong, redirect to a failure page on the frontend
            return redirect()->away('http://localhost:5173/login?error=social_auth_failed');
        }
    }

}
