<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ServiceCategoryController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::withCount('services')->ordered()->get();

        return view('admin.service-categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateCategory($request);
        $validated['slug'] = $this->uniqueSlug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');
        ServiceCategory::create($validated);

        return back()->with('success', 'Productcategorie is aangemaakt.');
    }

    public function update(Request $request, ServiceCategory $serviceCategory)
    {
        $validated = $this->validateCategory($request, $serviceCategory);
        $validated['slug'] = $this->uniqueSlug($validated['name'], $serviceCategory);
        $validated['is_active'] = $request->boolean('is_active');
        $serviceCategory->update($validated);

        return back()->with('success', 'Productcategorie is bijgewerkt.');
    }

    public function destroy(ServiceCategory $serviceCategory)
    {
        if ($serviceCategory->services()->exists()) {
            return back()->with('error', 'Deze categorie bevat nog diensten en kan niet worden verwijderd.');
        }

        $serviceCategory->delete();

        return back()->with('success', 'Productcategorie is verwijderd.');
    }

    private function validateCategory(Request $request, ?ServiceCategory $category = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('service_categories')->ignore($category)],
            'description' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);
    }

    private function uniqueSlug(string $name, ?ServiceCategory $category = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $counter = 1;
        while (ServiceCategory::where('slug', $slug)->when($category, fn ($query) => $query->whereKeyNot($category->id))->exists()) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }
}
