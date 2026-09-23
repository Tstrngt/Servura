<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->session()->get('locale')
            ?: config('site.default_language', 'nl');

        app()->setLocale($locale);

        return $next($request);
    }
}
