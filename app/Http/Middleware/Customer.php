<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Customer
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isCustomer()) {
            return $next($request);
        }

        // If user is not logged in, redirect to login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'U moet ingelogd zijn om deze pagina te bekijken.');
        }

        // If user is not a customer, redirect based on their role
        if (Auth::user()->canAccessAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Deze pagina is alleen voor klanten.');
        }

        // Fallback to home
        return redirect()->route('home')
            ->with('error', 'U heeft geen toegang tot deze pagina.');
    }
}
