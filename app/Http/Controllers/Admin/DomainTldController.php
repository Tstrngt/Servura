<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DomainTld;
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

    public function index()
    {
        $this->authorizeOwner();

        $tlds = DomainTld::orderBy('sort_order')->orderBy('extension')->get();

        return view('admin.domains.tlds', compact('tlds'));
    }

    public function store(Request $request)
    {
        $this->authorizeOwner();

        $validated = $request->validate([
            'extension' => ['required', 'string', 'max:50', 'regex:/^\.[a-z0-9\-]+$/i', 'unique:domain_tlds,extension'],
            'is_active' => 'boolean',
            'registration_price' => ['required', 'numeric', 'min:0'],
            'renewal_price' => ['required', 'numeric', 'min:0'],
            'transfer_price' => ['nullable', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $tld = DomainTld::create([
            'extension' => strtolower($validated['extension']),
            'is_active' => $request->boolean('is_active', true),
            'registration_price' => $validated['registration_price'],
            'renewal_price' => $validated['renewal_price'],
            'transfer_price' => $validated['transfer_price'] ?? 0,
            'cost_price' => $validated['cost_price'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        $this->syncServicePrice($tld);

        return back()->with('success', 'TLD toegevoegd.');
    }

    public function update(Request $request, DomainTld $tld)
    {
        $this->authorizeOwner();

        $validated = $request->validate([
            'extension' => ['required', 'string', 'max:50', 'regex:/^\.[a-z0-9\-]+$/i', 'unique:domain_tlds,extension,'.$tld->id],
            'is_active' => 'boolean',
            'registration_price' => ['required', 'numeric', 'min:0'],
            'renewal_price' => ['required', 'numeric', 'min:0'],
            'transfer_price' => ['nullable', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $tld->update([
            'extension' => strtolower($validated['extension']),
            'is_active' => $request->boolean('is_active', false),
            'registration_price' => $validated['registration_price'],
            'renewal_price' => $validated['renewal_price'],
            'transfer_price' => $validated['transfer_price'] ?? 0,
            'cost_price' => $validated['cost_price'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        $this->syncServicePrice($tld);

        return back()->with('success', 'TLD bijgewerkt.');
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

        $price = $tld->is_active ? $tld->registration_price : 0;
        $enabled = $tld->is_active;

        ServicePrice::updateOrCreate(
            [
                'service_id' => $service->id,
                'billing_cycle' => 'yearly',
                'tld' => $tld->extension,
            ],
            [
                'price' => $price,
                'is_enabled' => $enabled,
            ]
        );

        ServicePrice::where('service_id', $service->id)
            ->where('billing_cycle', 'yearly')
            ->where('tld', $tld->extension)
            ->where('price', '<>', $price)
            ->update(['price' => $price, 'is_enabled' => $enabled]);
    }

    private function removeServicePrice(DomainTld $tld): void
    {
        $service = Service::where('fulfillment_type', 'domain')->first();
        if (! $service) {
            return;
        }

        ServicePrice::where('service_id', $service->id)
            ->where('billing_cycle', 'yearly')
            ->where('tld', $tld->extension)
            ->delete();
    }
}
