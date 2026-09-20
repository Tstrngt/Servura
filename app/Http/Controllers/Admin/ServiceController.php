<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServicePrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $query = Service::with(['category', 'prices']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('visibility')) {
            if ($request->visibility === 'homepage') {
                $query->where('show_on_homepage', true);
            } elseif ($request->visibility === 'services_page') {
                $query->where('show_on_services_page', true);
            }
        }

        if ($request->filled('type')) {
            $query->where('service_type', $request->type);
        }

        $services = $query->ordered()->paginate(15);

        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $categories = ServiceCategory::active()->ordered()->get();
        $billingCycles = ServicePrice::CYCLES;

        return view('admin.services.create', compact('categories', 'billingCycles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'service_category_id' => 'nullable|exists:service_categories,id',
            'service_type' => 'required|in:website_pakket,hosting,custom',
            'fulfillment_type' => 'required|in:manual,directadmin',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'image_url' => 'nullable|string|max:500',
            'price' => 'nullable|numeric|min:0',
            'price_type' => 'nullable|in:eenmalig,maandelijks,jaarlijks,op-aanvraag',
            'features' => 'nullable|string',
            'popup_label' => 'nullable|string|max:100',
            'popup_badges' => 'nullable|array|max:10',
            'popup_badges.*.text' => 'required|string|max:100',
            'popup_badges.*.icon' => 'required|in:sparkles,code,device,search,server,support,shield,chart,globe,bolt',
            'popup_details' => 'nullable|array|max:12',
            'popup_details.*.title' => 'required|string|max:100',
            'popup_details.*.description' => 'required|string|max:500',
            'popup_details.*.icon' => 'required|in:sparkles,code,device,search,server,support,shield,chart,globe,bolt',
            'popup_price_note' => 'nullable|string|max:500',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'show_on_homepage' => 'boolean',
            'show_on_services_page' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'prices' => 'nullable|array',
            'prices.*.enabled' => 'boolean',
            'prices.*.amount' => 'nullable|numeric|min:0|max:99999999.99',
        ], [
            'title.required' => 'Titel is verplicht',
            'service_type.required' => 'Type is verplicht',
            'short_description.required' => 'Korte omschrijving is verplicht',
            'description.required' => 'Omschrijving is verplicht',
            'price_type.required' => 'Prijstype is verplicht',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_popular'] = $request->boolean('is_popular');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['show_on_homepage'] = $request->boolean('show_on_homepage');
        $validated['show_on_services_page'] = $request->boolean('show_on_services_page');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        // Parse features from textarea (one per line)
        if (!empty($validated['features'])) {
            $validated['features'] = array_filter(array_map('trim', explode("\n", $validated['features'])));
        } else {
            $validated['features'] = [];
        }

        $prices = $validated['prices'] ?? [];
        unset($validated['prices']);
        $this->applyLegacyPrice($validated, $prices);

        // Ensure unique slug
        $baseSlug = $validated['slug'];
        $counter = 1;
        while (Service::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $baseSlug . '-' . $counter;
            $counter++;
        }

        DB::transaction(function () use ($validated, $prices) {
            $service = Service::create($validated);
            $this->syncPrices($service, $prices);
        });

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Dienst is succesvol aangemaakt.');
    }

    public function edit(Service $service)
    {
        $service->load('prices');
        $categories = ServiceCategory::ordered()->get();
        $billingCycles = ServicePrice::CYCLES;

        return view('admin.services.edit', compact('service', 'categories', 'billingCycles'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'service_category_id' => 'nullable|exists:service_categories,id',
            'service_type' => 'required|in:website_pakket,hosting,custom',
            'fulfillment_type' => 'required|in:manual,directadmin',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'image_url' => 'nullable|string|max:500',
            'price' => 'nullable|numeric|min:0',
            'price_type' => 'nullable|in:eenmalig,maandelijks,jaarlijks,op-aanvraag',
            'features' => 'nullable|string',
            'popup_label' => 'nullable|string|max:100',
            'popup_badges' => 'nullable|array|max:10',
            'popup_badges.*.text' => 'required|string|max:100',
            'popup_badges.*.icon' => 'required|in:sparkles,code,device,search,server,support,shield,chart,globe,bolt',
            'popup_details' => 'nullable|array|max:12',
            'popup_details.*.title' => 'required|string|max:100',
            'popup_details.*.description' => 'required|string|max:500',
            'popup_details.*.icon' => 'required|in:sparkles,code,device,search,server,support,shield,chart,globe,bolt',
            'popup_price_note' => 'nullable|string|max:500',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'show_on_homepage' => 'boolean',
            'show_on_services_page' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'prices' => 'nullable|array',
            'prices.*.enabled' => 'boolean',
            'prices.*.amount' => 'nullable|numeric|min:0|max:99999999.99',
        ], [
            'title.required' => 'Titel is verplicht',
            'service_type.required' => 'Type is verplicht',
            'short_description.required' => 'Korte omschrijving is verplicht',
            'description.required' => 'Omschrijving is verplicht',
            'price_type.required' => 'Prijstype is verplicht',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_popular'] = $request->boolean('is_popular');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['show_on_homepage'] = $request->boolean('show_on_homepage');
        $validated['show_on_services_page'] = $request->boolean('show_on_services_page');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        // Parse features from textarea (one per line)
        if (!empty($validated['features'])) {
            $validated['features'] = array_filter(array_map('trim', explode("\n", $validated['features'])));
        } else {
            $validated['features'] = [];
        }

        $prices = $validated['prices'] ?? [];
        unset($validated['prices']);
        $this->applyLegacyPrice($validated, $prices);

        // Ensure unique slug (exclude current)
        $baseSlug = $validated['slug'];
        $counter = 1;
        while (Service::where('slug', $validated['slug'])->where('id', '!=', $service->id)->exists()) {
            $validated['slug'] = $baseSlug . '-' . $counter;
            $counter++;
        }

        DB::transaction(function () use ($service, $validated, $prices) {
            $service->update($validated);
            $this->syncPrices($service, $prices);
        });

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Dienst is succesvol bijgewerkt.');
    }

    private function applyLegacyPrice(array &$validated, array $prices): void
    {
        foreach (ServicePrice::CYCLES as $cycle => $label) {
            if (!empty($prices[$cycle]['enabled']) && isset($prices[$cycle]['amount'])) {
                $validated['price'] = $prices[$cycle]['amount'];
                $validated['price_type'] = match ($cycle) {
                    'monthly' => 'maandelijks',
                    'yearly' => 'jaarlijks',
                    'one_time' => 'eenmalig',
                    default => 'eenmalig',
                };
                return;
            }
        }

        $validated['price'] = null;
        $validated['price_type'] = 'op-aanvraag';
    }

    private function syncPrices(Service $service, array $prices): void
    {
        foreach (ServicePrice::CYCLES as $cycle => $label) {
            $data = $prices[$cycle] ?? [];
            if (!empty($data['enabled']) && isset($data['amount'])) {
                $service->prices()->updateOrCreate(
                    ['billing_cycle' => $cycle],
                    ['price' => $data['amount'], 'is_enabled' => true]
                );
            } else {
                $service->prices()->where('billing_cycle', $cycle)->delete();
            }
        }
    }

    public function destroy(Service $service)
    {
        // Check if service is linked to customers
        if ($service->customerServices()->count() > 0) {
            return redirect()
                ->route('admin.services.index')
                ->with('error', 'Kan dienst niet verwijderen. Er zijn nog klanten aan deze dienst gekoppeld.');
        }

        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Dienst is succesvol verwijderd.');
    }
}
