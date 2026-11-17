<?php

namespace App\Services;

use App\Models\BillingSetting;
use App\Models\DomainTld;
use App\Models\DomainTldPrice;
use App\Models\Service;
use App\Models\ServicePrice;
use App\Services\Domains\DomainProviderFactory;
use App\Services\Domains\OpenProviderProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class OpenProviderPriceSyncService
{
    public function run(): array
    {
        $provider = DomainProviderFactory::make('openprovider');
        if (! $provider instanceof OpenProviderProvider || ! $provider->canAuthenticate()) {
            throw new RuntimeException('Openprovider API-inloggegevens ontbreken voor de geselecteerde omgeving.');
        }

        $environment = BillingSetting::valueFor('openprovider_environment', 'sandbox');
        $markup = max(0, BillingSetting::decimal('domain_price_markup_percentage', 10));
        $result = ['success' => 0, 'failed' => 0];

        DomainTld::orderBy('id')->each(function (DomainTld $tld) use ($provider, $environment, $markup, &$result) {
            foreach (DomainTldPrice::OPERATIONS as $operation) {
                try {
                    $this->syncOperation($provider, $tld, $environment, $operation, 1, $markup);
                    $result['success']++;
                } catch (Throwable $e) {
                    if ($provider->isRateLimited()) {
                        throw new RuntimeException('Prijssynchronisatie gestopt: Openprovider blokkeert de API tijdelijk wegens te veel verzoeken.', previous: $e);
                    }
                    DomainTldPrice::updateOrCreate(
                        ['domain_tld_id' => $tld->id, 'environment' => $environment, 'operation' => $operation, 'period' => 1],
                        ['markup_percentage' => $markup, 'sync_error' => $e->getMessage()]
                    );
                    Log::warning('Openprovider TLD price sync failed', [
                        'extension' => $tld->extension, 'operation' => $operation, 'environment' => $environment, 'error' => $e->getMessage(),
                    ]);
                    $result['failed']++;
                }
            }

            $this->applyCurrentPrices($tld->fresh(), $environment);
        });

        BillingSetting::setValue('domain_price_last_sync_at', now()->toDateTimeString());
        BillingSetting::setValue('domain_price_last_sync_status', $result['failed'] ? "{$result['failed']} prijsacties mislukt" : 'Alle prijzen succesvol bijgewerkt');

        return $result;
    }

    private function syncOperation(OpenProviderProvider $provider, DomainTld $tld, string $environment, string $operation, int $period, float $markup): void
    {
        $data = $provider->getPrice($tld->extension, $operation, $period);
        $effective = data_get($data, 'price.reseller.price');
        $currency = data_get($data, 'price.reseller.currency');
        if (! is_numeric($effective) || blank($currency)) {
            throw new RuntimeException('Openprovider gaf geen toepasbare resellerprijs terug.');
        }

        $isPromotion = (bool) ($data['is_promotion'] ?? false);
        $regular = data_get($data, 'tier_price.reseller.price');
        $regular = is_numeric($regular) ? (float) $regular : (float) $effective;
        $promotion = $data['promotion_data'] ?? [];

        DomainTldPrice::updateOrCreate(
            ['domain_tld_id' => $tld->id, 'environment' => $environment, 'operation' => $operation, 'period' => $period],
            [
                'currency' => strtoupper($currency),
                'regular_cost' => $regular,
                'effective_cost' => (float) $effective,
                'calculated_sale_price' => $this->salePrice((float) $effective, $markup),
                'markup_percentage' => $markup,
                'is_premium' => (bool) ($data['is_premium'] ?? false),
                'is_promotion' => $isPromotion,
                'promotion_starts_at' => $isPromotion ? $this->date($promotion['start_date'] ?? null) : null,
                'promotion_ends_at' => $isPromotion ? $this->date($promotion['end_date'] ?? null) : null,
                'promotion_data' => $isPromotion ? $promotion : null,
                'synced_at' => now(),
                'sync_error' => null,
            ]
        );
    }

    private function applyCurrentPrices(DomainTld $tld, string $environment): void
    {
        $prices = $tld->providerPrices()->where('environment', $environment)->where('period', 1)->get()->keyBy('operation');
        $create = $prices->get('create');
        $renew = $prices->get('renew');
        $transfer = $prices->get('transfer');

        $updates = [];
        if ($create?->sale_price !== null && $create->currency === 'EUR') {
            $updates['registration_price'] = $create->sale_price;
            $updates['cost_price'] = $create->effective_cost;
        }
        if ($renew?->sale_price !== null && $renew->currency === 'EUR') {
            $updates['renewal_price'] = $renew->sale_price;
        }
        if ($transfer?->sale_price !== null && $transfer->currency === 'EUR') {
            $updates['transfer_price'] = $transfer->sale_price;
        }
        if ($updates) {
            $tld->update($updates);
            $this->syncServicePrices($tld->fresh());
        }
    }

    private function syncServicePrices(DomainTld $tld): void
    {
        $service = Service::where('fulfillment_type', 'domain')->first();
        if (! $service) {
            return;
        }

        ServicePrice::updateOrCreate(
            ['service_id' => $service->id, 'billing_cycle' => 'yearly', 'tld' => $tld->extension],
            ['price' => $tld->is_active ? $tld->registration_price : 0, 'is_enabled' => $tld->is_active]
        );
        ServicePrice::updateOrCreate(
            ['service_id' => $service->id, 'billing_cycle' => 'one_time', 'tld' => $tld->extension],
            ['price' => $tld->is_active ? $tld->transfer_price : 0, 'is_enabled' => $tld->is_active && (float) $tld->transfer_price > 0]
        );
    }

    private function salePrice(float $cost, float $markup): float
    {
        return ceil(($cost * (1 + ($markup / 100)) * 100) - 1.0E-9) / 100;
    }

    private function date(mixed $value): ?Carbon
    {
        if (blank($value)) {
            return null;
        }

        return is_numeric($value) ? Carbon::createFromTimestamp((int) $value) : Carbon::parse($value);
    }
}
