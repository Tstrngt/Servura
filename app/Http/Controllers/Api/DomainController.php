<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DomainTld;
use App\Models\Service;
use App\Models\ServicePrice;
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

        $tlds = DomainTld::active()->with('servicePrice.service')->get();
        $domainService = Service::where('fulfillment_type', 'domain')->first();
        $cacheKeyBase = 'domain_suggest_'.preg_replace('/[^a-z0-9-]/', '', $name);

        try {
            $results = [];

            foreach ($tlds as $tld) {
                $domain = $name.'.'.ltrim($tld->extension, '.');
                $cacheKey = $cacheKeyBase.'_'.ltrim($tld->extension, '.');

                $availability = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($provider, $domain) {
                    return $provider->checkAvailability($domain)->toArray();
                });

                $servicePrice = $domainService
                    ? $domainService->prices()->where('tld', $tld->extension)->where('billing_cycle', 'yearly')->where('is_enabled', true)->first()
                    : null;
                $transferPrice = null;
                if ($domainService && (float) $tld->transfer_price > 0) {
                    $transferPrice = ServicePrice::updateOrCreate(
                        [
                            'service_id' => $domainService->id,
                            'tld' => $tld->extension,
                            'billing_cycle' => 'one_time',
                        ],
                        ['price' => $tld->transfer_price, 'is_enabled' => true]
                    );
                }

                $results[] = [
                    'domain' => $availability['domain'] ?? $domain,
                    'available' => $availability['available'] ?? false,
                    'status' => $availability['status'] ?? 'unknown',
                    'tld' => ltrim($tld->extension, '.'),
                    'price' => number_format((float) $tld->registration_price, 2, ',', '.'),
                    'price_raw' => (float) $tld->registration_price,
                    'service_price_id' => $servicePrice?->id,
                    'checkout_url' => $servicePrice
                        ? route('checkout.show', ['service' => $domainService->slug]).'?domain='.urlencode($availability['domain']).'&tld='.urlencode($tld->extension).'&mode=register'
                        : null,
                    'transfer_price' => number_format((float) $tld->transfer_price, 2, ',', '.'),
                    'transfer_url' => $transferPrice
                        ? route('checkout.show', ['service' => $domainService->slug]).'?domain='.urlencode($availability['domain']).'&tld='.urlencode($tld->extension).'&mode=transfer'
                        : null,
                ];
            }

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
