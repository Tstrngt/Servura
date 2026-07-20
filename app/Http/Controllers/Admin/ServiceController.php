<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
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
        $query = Service::query();

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
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'service_type' => 'required|in:website_pakket,hosting,custom',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'image_url' => 'nullable|string|max:500',
            'price' => 'nullable|numeric|min:0',
            'price_type' => 'required|in:eenmalig,maandelijks,jaarlijks,op-aanvraag',
            'features' => 'nullable|string',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'show_on_homepage' => 'boolean',
            'show_on_services_page' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
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

        // Ensure unique slug
        $baseSlug = $validated['slug'];
        $counter = 1;
        while (Service::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $baseSlug . '-' . $counter;
            $counter++;
        }

        Service::create($validated);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Dienst is succesvol aangemaakt.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'service_type' => 'required|in:website_pakket,hosting,custom',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'image_url' => 'nullable|string|max:500',
            'price' => 'nullable|numeric|min:0',
            'price_type' => 'required|in:eenmalig,maandelijks,jaarlijks,op-aanvraag',
            'features' => 'nullable|string',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'show_on_homepage' => 'boolean',
            'show_on_services_page' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
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

        // Ensure unique slug (exclude current)
        $baseSlug = $validated['slug'];
        $counter = 1;
        while (Service::where('slug', $validated['slug'])->where('id', '!=', $service->id)->exists()) {
            $validated['slug'] = $baseSlug . '-' . $counter;
            $counter++;
        }

        $service->update($validated);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Dienst is succesvol bijgewerkt.');
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
