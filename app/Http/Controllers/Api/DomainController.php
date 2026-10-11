<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BillingSetting;
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
                'message' => 'De actieve domeinprovider is nog niet volledig geconfigureerd.',
            ], 503);
        }

        $environment = BillingSetting::valueFor('openprovider_environment', 'sandbox');
        $tlds = DomainTld::active()->with([
            'servicePrice.service',
            'providerPrices' => fn ($query) => $query->where('environment', $environment)->where('period', 1),
        ])->get();
        $domainService = Service::where('fulfillment_type', 'domain')->first();
        if ($tlds->isEmpty()) {
            return response()->json([
                'name' => $name,
                'results' => [],
                'status' => 'no_tlds',
                'message' => 'Er zijn geen actieve domeinextensies ingesteld.',
            ], 422);
        }
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

                $createProviderPrice = $tld->providerPrices->firstWhere('operation', 'create');
                $results[] = [
                    'domain' => $availability['domain'] ?? $domain,
                    'available' => $availability['available'] ?? false,
                    'status' => $availability['status'] ?? 'unknown',
                    'tld' => ltrim($tld->extension, '.'),
                    'price' => number_format((float) $tld->registration_price, 2, ',', '.'),
                    'price_raw' => (float) $tld->registration_price,
                    'regular_price' => $createProviderPrice?->is_promotion && $createProviderPrice->regular_cost !== null
                        ? number_format(ceil(((float) $createProviderPrice->regular_cost * (1 + ((float) $createProviderPrice->markup_percentage / 100)) * 100) - 1.0E-9) / 100, 2, ',', '.')
                        : null,
                    'is_promotion' => (bool) $createProviderPrice?->is_promotion,
                    'promotion_ends_at' => $createProviderPrice?->promotion_ends_at?->toIso8601String(),
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
                'message' => 'De domeincontrole bij '.$provider->name().' is mislukt: '.\Illuminate\Support\Str::limit($e->getMessage(), 300),
            ], 500);
        }
    }
}
