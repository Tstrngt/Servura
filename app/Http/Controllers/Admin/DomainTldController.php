<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SyncOpenProviderDomainPrices;
use App\Models\BillingSetting;
use App\Models\DomainTld;
use App\Models\DomainTldPrice;
use App\Models\Service;
use App\Models\ServicePrice;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DomainTldController extends Controller
{
    private function authorizeOwner(): void
    {
        abort_unless(auth()->user()?->isOwner(), 403, 'Alleen eigenaars hebben toegang.');
    }

    public function index(Request $request)
    {
        $this->authorizeOwner();
        $environment = BillingSetting::valueFor('openprovider_environment', 'sandbox');
        $query = DomainTld::with(['providerPrices' => fn ($query) => $query->where('environment', $environment)->where('period', 1)])
            ->orderBy('sort_order')->orderBy('extension');
        if ($request->string('status')->toString() === 'active') {
            $query->where('is_active', true);
        } elseif ($request->string('status')->toString() === 'inactive') {
            $query->where('is_active', false);
        }
        if ($request->boolean('promotions')) {
            $query->whereHas('providerPrices', fn ($price) => $price->where('environment', $environment)->where('is_promotion', true));
        }
        if ($request->boolean('errors')) {
            $query->whereHas('providerPrices', fn ($price) => $price->where('environment', $environment)->whereNotNull('sync_error'));
        }

        $tlds = $query->get();
        $settings = [
            'environment' => $environment,
            'markup' => BillingSetting::decimal('domain_price_markup_percentage', 10),
            'last_sync_at' => BillingSetting::valueFor('domain_price_last_sync_at', ''),
            'last_sync_status' => BillingSetting::valueFor('domain_price_last_sync_status', 'Nog niet gesynchroniseerd'),
        ];

        return view('admin.domains.tlds', compact('tlds', 'settings'));
    }

    public function store(Request $request)
    {
        $this->authorizeOwner();

        $validated = $request->validate([
            'extension' => ['required', 'string', 'max:50', 'regex:/^\.[a-z0-9\-]+$/i', 'unique:domain_tlds,extension'],
            'is_active' => 'boolean',
            'sort_order' => ['nullable', 'integer'],
        ]);

        $tld = DomainTld::create([
            'extension' => strtolower($validated['extension']),
            'is_active' => $request->boolean('is_active', true),
            'registration_price' => 0,
            'renewal_price' => 0,
            'transfer_price' => 0,
            'cost_price' => null,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        $this->syncServicePrice($tld);
        SyncOpenProviderDomainPrices::dispatchAfterResponse();

        return back()->with('success', 'TLD toegevoegd. De Openprovider-prijs wordt opgehaald.');
    }

    public function update(Request $request, DomainTld $tld)
    {
        $this->authorizeOwner();

        $validated = $request->validate([
            'extension' => ['required', 'string', 'max:50', 'regex:/^\.[a-z0-9\-]+$/i', 'unique:domain_tlds,extension,'.$tld->id],
            'is_active' => 'boolean',
            'override_create' => ['nullable', 'numeric', 'min:0'],
            'override_renew' => ['nullable', 'numeric', 'min:0'],
            'override_transfer' => ['nullable', 'numeric', 'min:0'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $tld->update([
            'extension' => strtolower($validated['extension']),
            'is_active' => $request->boolean('is_active', false),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);
        $environment = BillingSetting::valueFor('openprovider_environment', 'sandbox');
        foreach (DomainTldPrice::OPERATIONS as $operation) {
            $value = $validated['override_'.$operation] ?? null;
            DomainTldPrice::where('domain_tld_id', $tld->id)
                ->where('environment', $environment)
                ->where('operation', $operation)
                ->where('period', 1)
                ->update(['override_sale_price' => filled($value) ? $value : null]);
        }
        $prices = $tld->providerPrices()->where('environment', $environment)->where('period', 1)->get()->keyBy('operation');
        $tld->update(array_filter([
            'registration_price' => $prices->get('create')?->sale_price,
            'renewal_price' => $prices->get('renew')?->sale_price,
            'transfer_price' => $prices->get('transfer')?->sale_price,
        ], fn ($value) => $value !== null));
        $this->syncServicePrice($tld->fresh());

        return back()->with('success', 'TLD en prijsoverschrijvingen bijgewerkt.');
    }

    public function updatePricingSettings(Request $request)
    {
        $this->authorizeOwner();
        $validated = $request->validate([
            'markup_percentage' => ['required', 'numeric', 'min:0', 'max:500'],
        ]);
        BillingSetting::setValue('domain_price_markup_percentage', (string) $validated['markup_percentage']);

        return back()->with('success', 'Opslagpercentage opgeslagen. Synchroniseer nu om alle verkoopprijzen opnieuw te berekenen.');
    }

    public function syncPrices()
    {
        $this->authorizeOwner();
        SyncOpenProviderDomainPrices::dispatchAfterResponse();

        return back()->with('success', 'Prijssynchronisatie is gestart. Vernieuw de pagina over enkele ogenblikken.');
    }

    public function destroy(DomainTld $tld)
    {
        $this->authorizeOwner();

        $this->removeServicePrice($tld);
        $tld->delete();

        return back()->with('success', 'TLD verwijderd.');
    }

    private function syncServicePrice(DomainTld $tld): void
    {
        $service = Service::where('fulfillment_type', 'domain')->first();
        if (! $service) {
            return;
        }

        $enabled = $tld->is_active;
        $registrationPrice = $tld->is_active ? $tld->registration_price : 0;
        $transferPrice = $tld->is_active ? ($tld->transfer_price ?? 0) : 0;

        ServicePrice::updateOrCreate(
            [
                'service_id' => $service->id,
                'billing_cycle' => 'yearly',
                'tld' => $tld->extension,
            ],
            [
                'price' => $registrationPrice,
                'is_enabled' => $enabled,
            ]
        );

        ServicePrice::updateOrCreate(
            [
                'service_id' => $service->id,
                'billing_cycle' => 'one_time',
                'tld' => $tld->extension,
            ],
            [
                'price' => $transferPrice,
                'is_enabled' => $enabled && $transferPrice > 0,
            ]
        );

        ServicePrice::where('service_id', $service->id)
            ->whereIn('billing_cycle', ['yearly', 'one_time'])
            ->where('tld', $tld->extension)
            ->get()
            ->each(function (ServicePrice $price) use ($registrationPrice, $transferPrice, $enabled) {
                $expected = $price->billing_cycle === 'yearly' ? $registrationPrice : $transferPrice;
                $shouldEnable = $enabled && ($price->billing_cycle === 'yearly' || $expected > 0);
                if ((float) $price->price !== (float) $expected || $price->is_enabled !== $shouldEnable) {
                    $price->update(['price' => $expected, 'is_enabled' => $shouldEnable]);
                }
            });
    }

    private function removeServicePrice(DomainTld $tld): void
    {
        $service = Service::where('fulfillment_type', 'domain')->first();
        if (! $service) {
            return;
        }

        ServicePrice::where('service_id', $service->id)
            ->whereIn('billing_cycle', ['yearly', 'one_time'])
            ->where('tld', $tld->extension)
            ->delete();
    }
}
