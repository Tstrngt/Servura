<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'E-mailadres is verplicht',
            'email.email' => 'Voer een geldig e-mailadres in',
            'password.required' => 'Wachtwoord is verplicht',
        ]);

        // Rate limiting check
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            
            throw ValidationException::withMessages([
                'email' => ['Te veel pogingen. Probeer het over :seconds seconden opnieuw.'],
            ]);
        }

        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $this->incrementLoginAttempts($request);
            
            throw ValidationException::withMessages([
                'email' => ['Deze combinatie van e-mailadres en wachtwoord is onjuist.'],
            ]);
        }

        $request->session()->regenerate();
        
        // Update last login
        Auth::user()->updateLastLogin();

        // Clear login attempts
        $this->clearLoginAttempts($request);

        // Redirect based on user role
        $user = Auth::user();
        if ($user->canAccessAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        } else {
            return redirect()->intended(route('customer.dashboard'));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Determine if the user has too many failed login attempts.
     */
    protected function hasTooManyLoginAttempts(Request $request)
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request),
            5, // max attempts
            60 // decay in seconds
        );
    }

    /**
     * Increment the login attempts for the user.
     */
    protected function incrementLoginAttempts(Request $request)
    {
        $this->limiter()->hit(
            $this->throttleKey($request),
            60
        );
    }

    /**
     * Clear the login attempts for the user.
     */
    protected function clearLoginAttempts(Request $request)
    {
        $this->limiter()->clear($this->throttleKey($request));
    }

    /**
     * Fire the lockout event.
     */
    protected function fireLockoutEvent(Request $request)
    {
        event(new \Illuminate\Auth\Events\Lockout($request));
    }

    /**
     * Get the throttle key for the given request.
     */
    protected function throttleKey(Request $request)
    {
        return strtolower($request->input('email')).'|'.$request->ip();
    }

    /**
     * Get the rate limiter instance.
     */
    protected function limiter()
    {
        return app(\Illuminate\Cache\RateLimiter::class);
    }
}
