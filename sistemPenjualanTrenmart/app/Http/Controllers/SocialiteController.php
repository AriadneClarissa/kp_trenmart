<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect ke Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal login menggunakan Google');
        }

        // Cari atau buat user berdasarkan email
        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'email_verified_at' => now(),
                'is_approved' => true,  // Auto-approve Google sign-in
                'role' => 'customer',
                'password' => null,  // Tidak perlu password untuk Google login
            ]
        );

        Auth::login($user, true);  // Login dan remember

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Logout user
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
