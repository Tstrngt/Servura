<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->canAccessAdmin()) {
            return $next($request);
        }

        // If user is not logged in, redirect to login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'U moet ingelogd zijn om deze pagina te bekijken.');
        }

        // If user is a customer, redirect to customer dashboard
        if (Auth::user()->isCustomer()) {
            return redirect()->route('customer.dashboard')
                ->with('error', 'Deze pagina is alleen voor medewerkers.');
        }

        // Fallback to home
        return redirect()->route('home')
            ->with('error', 'U heeft geen toegang tot deze pagina.');
    }
}
