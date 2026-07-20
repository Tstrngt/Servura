@extends('layouts.app')

@section('title', 'Nieuwe Dienst - Servura Admin')

@section('content')
@include('admin.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="px-4 py-6 sm:px-0">
            <div class="flex items-center">
                <a href="{{ route('admin.services.index') }}" class="text-primary-600 hover:text-primary-500 mr-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Nieuwe Dienst</h1>
                    <p class="mt-1 text-sm text-gray-600">Voeg een nieuw product of dienst toe.</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="px-4 sm:px-0">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <form action="{{ route('admin.services.store') }}" method="POST">
                        @csrf

                        <h3 class="text-md font-semibold text-gray-900 mb-4 border-b pb-2">Algemeen</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="form-group">
                                <label for="title" class="form-label">Titel *</label>
                                <input type="text" id="title" name="title" class="form-input" required value="{{ old('title') }}" placeholder="Naam van de dienst">
                                @error('title')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="service_type" class="form-label">Type *</label>
                                <select id="service_type" name="service_type" class="form-input" required>
                                    <option value="website_pakket" {{ old('service_type') == 'website_pakket' ? 'selected' : '' }}>Website Pakket</option>
                                    <option value="hosting" {{ old('service_type') == 'hosting' ? 'selected' : '' }}>Hosting</option>
                                    <option value="custom" {{ old('service_type') == 'custom' ? 'selected' : '' }}>Custom Pakket</option>
                                </select>
                                @error('service_type')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="image_url" class="form-label">Afbeelding URL</label>
                                <input type="text" id="image_url" name="image_url" class="form-input" value="{{ old('image_url') }}" placeholder="https://...">
                                @error('image_url')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-6">
                            <label for="short_description" class="form-label">Korte omschrijving *</label>
                            <input type="text" id="short_description" name="short_description" class="form-input" required value="{{ old('short_description') }}" placeholder="Korte samenvatting (max 500 tekens)">
                            @error('short_description')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-6">
                            <label for="description" class="form-label">Volledige omschrijving *</label>
                            <textarea id="description" name="description" rows="6" class="form-textarea" required placeholder="Gedetailleerde beschrijving van de dienst...">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <h3 class="text-md font-semibold text-gray-900 mb-4 border-b pb-2">Prijs</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="form-group">
                                <label for="price" class="form-label">Prijs (€)</label>
                                <input type="number" id="price" name="price" class="form-input" step="0.01" min="0" value="{{ old('price') }}" placeholder="0.00">
                                @error('price')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="price_type" class="form-label">Prijstype *</label>
                                <select id="price_type" name="price_type" class="form-input" required>
                                    <option value="eenmalig" {{ old('price_type') == 'eenmalig' ? 'selected' : '' }}>Eenmalig</option>
                                    <option value="maandelijks" {{ old('price_type') == 'maandelijks' ? 'selected' : '' }}>Maandelijks</option>
                                    <option value="jaarlijks" {{ old('price_type') == 'jaarlijks' ? 'selected' : '' }}>Jaarlijks</option>
                                    <option value="op-aanvraag" {{ old('price_type') == 'op-aanvraag' ? 'selected' : '' }}>Op aanvraag</option>
                                </select>
                                @error('price_type')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <h3 class="text-md font-semibold text-gray-900 mb-4 border-b pb-2">Kenmerken</h3>

                        <div class="form-group mb-6">
                            <label for="features" class="form-label">Features (één per regel)</label>
                            <textarea id="features" name="features" rows="5" class="form-textarea" placeholder="Onbeperkt bandbreedte&#10;24/7 support&#10;Dagelijkse backups">{{ old('features') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Voer elke feature op een nieuwe regel in.</p>
                            @error('features')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <h3 class="text-md font-semibold text-gray-900 mb-4 border-b pb-2">Zichtbaarheid & Instellingen</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="form-group">
                                <label for="sort_order" class="form-label">Volgorde</label>
                                <input type="number" id="sort_order" name="sort_order" class="form-input" min="0" value="{{ old('sort_order', 0) }}" placeholder="0">
                                <p class="mt-1 text-sm text-gray-500">Lager nummer = eerder weergegeven.</p>
                                @error('sort_order')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="space-y-4 mb-6">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Actief (dienst is beschikbaar)</span>
                            </label>
                            <br>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="show_on_homepage" value="1" {{ old('show_on_homepage') ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Tonen op homepage</span>
                            </label>
                            <br>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="show_on_services_page" value="1" {{ old('show_on_services_page', true) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Tonen op diensten pagina</span>
                            </label>
                            <br>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_popular" value="1" {{ old('is_popular') ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Markeren als populair</span>
                            </label>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('admin.services.index') }}" class="btn btn-outline">Annuleren</a>
                            <button type="submit" class="btn btn-primary">Dienst Aanmaken</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
