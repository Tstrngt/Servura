<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function verify(Request $request, string $token)
    {
        $user = User::where('email_verification_token', $token)->first();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Deze verificatielink is ongeldig of verlopen.');
        }

        if (! $user->email_verified_at) {
            $user->update([
                'email_verified_at' => now(),
                'email_verification_token' => null,
            ]);
        }

        return redirect()->route('login')->with('success', 'Uw e-mailadres is bevestigd. U kunt nu inloggen.');
    }
}
