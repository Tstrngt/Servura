<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Domains\DomainProviderFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class DomainController extends Controller
{
    public function check(Request $request)
    {
        $validated = $request->validate([
            'domain' => ['required', 'string', 'max:255', 'regex:/^(?!-)[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)+$/'],
        ]);

        $domain = mb_strtolower(trim($validated['domain']));

        $provider = DomainProviderFactory::default();

        if (! $provider || ! $provider->isConfigured()) {
            return response()->json([
                'domain' => $domain,
                'available' => false,
                'status' => 'unconfigured',
                'tld' => null,
            ], 503);
        }

        $cacheKey = 'domain_check_'.preg_replace('/[^a-z0-9.-]/', '', $domain);

        try {
            $result = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($provider, $domain) {
                return $provider->checkAvailability($domain)->toArray();
            });

            return response()->json($result);
        } catch (Throwable $e) {
            Log::warning('Domain check controller error', [
                'domain' => $domain,
                'provider' => $provider?->name(),
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'domain' => $domain,
                'available' => false,
                'status' => 'error',
                'tld' => null,
                'message' => 'Er ging iets mis. Probeer het later opnieuw.',
            ], 500);
        }
    }
}
