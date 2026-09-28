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
            'name' => ['required', 'string', 'max:63', 'regex:/^[a-zA-Z0-9-]+$/', 'not_regex:/\./'],
        ]);

        $name = mb_strtolower(trim($validated['name']));

        $provider = DomainProviderFactory::default();

        if (! $provider || ! $provider->isConfigured()) {
            return response()->json([
                'name' => $name,
                'results' => [],
                'status' => 'unconfigured',
            ], 503);
        }

        $tlds = config('domains.default_tlds', ['nl']);
        $cacheKeyBase = 'domain_suggest_'.preg_replace('/[^a-z0-9-]/', '', $name);

        try {
            $results = [];

            foreach ($tlds as $tld) {
                $domain = $name.'.'.$tld;
                $cacheKey = $cacheKeyBase.'_'.$tld;

                $result = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($provider, $domain) {
                    return $provider->checkAvailability($domain)->toArray();
                });

                $results[] = $result;
            }

            // Beschikbare domeinen bovenaan
            usort($results, fn ($a, $b) => ($b['available'] ?? false) <=> ($a['available'] ?? false));

            return response()->json([
                'name' => $name,
                'results' => $results,
                'status' => 'ok',
            ]);
        } catch (Throwable $e) {
            Log::warning('Domain check controller error', [
                'name' => $name,
                'provider' => $provider?->name(),
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'name' => $name,
                'results' => [],
                'status' => 'error',
                'message' => 'Er ging iets mis. Probeer het later opnieuw.',
            ], 500);
        }
    }
}
