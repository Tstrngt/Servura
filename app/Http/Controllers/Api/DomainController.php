<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Domains\DomainProviderFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

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

        $result = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($provider, $domain) {
            try {
                return $provider->checkAvailability($domain)->toArray();
            } catch (\Throwable $e) {
                Log::warning('Domain check failed', [
                    'domain' => $domain,
                    'provider' => $provider->name(),
                    'message' => $e->getMessage(),
                ]);

                return [
                    'domain' => $domain,
                    'available' => false,
                    'status' => 'error',
                    'tld' => null,
                    'actions' => [],
                ];
            }
        });

        return response()->json($result);
    }
}
