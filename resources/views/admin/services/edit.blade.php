@extends('layouts.app')

@section('title', 'Dienst Bewerken - Servura Admin')

@section('content')
@include('admin.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="mx-auto w-full max-w-[1600px] px-4 py-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="py-4">
            <div class="flex items-center">
                <a href="{{ route('admin.services.index') }}" class="text-primary-600 hover:text-primary-500 mr-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dienst Bewerken</h1>
                    <p class="mt-1 text-sm text-gray-600">{{ $service->title }}</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div>
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
                <div class="p-5 sm:p-6 lg:p-8">
                    <form action="{{ route('admin.services.update', $service) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h3 class="text-md font-semibold text-gray-900 mb-4 border-b pb-2">Algemeen</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="form-group">
                                <label for="title" class="form-label">Titel *</label>
                                <input type="text" id="title" name="title" class="form-input" required value="{{ old('title', $service->title) }}" placeholder="Naam van de dienst">
                                @error('title')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="service_type" class="form-label">Type *</label>
                                <select id="service_type" name="service_type" class="form-input" required>
                                    <option value="website_pakket" {{ old('service_type', $service->service_type) == 'website_pakket' ? 'selected' : '' }}>Website Pakket</option>
                                    <option value="hosting" {{ old('service_type', $service->service_type) == 'hosting' ? 'selected' : '' }}>Hosting</option>
                                    <option value="custom" {{ old('service_type', $service->service_type) == 'custom' ? 'selected' : '' }}>Custom Pakket</option>
                                </select>
                                @error('service_type')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="image_url" class="form-label">Afbeelding URL</label>
                                <input type="text" id="image_url" name="image_url" class="form-input" value="{{ old('image_url', $service->image_url) }}" placeholder="https://...">
                                @error('image_url')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-6">
                            <label for="short_description" class="form-label">Korte omschrijving *</label>
                            <input type="text" id="short_description" name="short_description" class="form-input" required value="{{ old('short_description', $service->short_description) }}" placeholder="Korte samenvatting (max 500 tekens)">
                            @error('short_description')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-6">
                            <label for="description" class="form-label">Volledige omschrijving *</label>
                            <textarea id="description" name="description" rows="4" class="form-textarea" required placeholder="Gedetailleerde beschrijving van de dienst...">{{ old('description', $service->description) }}</textarea>
                            @error('description')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-8 xl:grid-cols-2">
                            <section>
                                <h3 class="text-md font-semibold text-gray-900 mb-4 border-b pb-2">Prijs</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="form-group">
                                <label for="price" class="form-label">Prijs (€)</label>
                                <input type="number" id="price" name="price" class="form-input" step="0.01" min="0" value="{{ old('price', $service->price) }}" placeholder="0.00">
                                @error('price')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="price_type" class="form-label">Prijstype *</label>
                                <select id="price_type" name="price_type" class="form-input" required>
                                    <option value="eenmalig" {{ old('price_type', $service->price_type) == 'eenmalig' ? 'selected' : '' }}>Eenmalig</option>
                                    <option value="maandelijks" {{ old('price_type', $service->price_type) == 'maandelijks' ? 'selected' : '' }}>Maandelijks</option>
                                    <option value="jaarlijks" {{ old('price_type', $service->price_type) == 'jaarlijks' ? 'selected' : '' }}>Jaarlijks</option>
                                    <option value="op-aanvraag" {{ old('price_type', $service->price_type) == 'op-aanvraag' ? 'selected' : '' }}>Op aanvraag</option>
                                </select>
                                @error('price_type')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>
                                </div>
                            </section>

                            <section>
                                <h3 class="text-md font-semibold text-gray-900 mb-4 border-b pb-2">Kenmerken</h3>

                        <div class="form-group mb-6">
                            <label for="features" class="form-label">Features (één per regel)</label>
                            <textarea id="features" name="features" rows="4" class="form-textarea" placeholder="Onbeperkt bandbreedte&#10;24/7 support&#10;Dagelijkse backups">{{ old('features', is_array($service->features) ? implode("\n", $service->features) : '') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Voer elke feature op een nieuwe regel in.</p>
                            @error('features')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                            </section>
                        </div>

                        <div x-data="popupEditor(@js(['badges' => old('popup_badges', $service->popup_badges ?? []), 'details' => old('popup_details', $service->popup_details ?? [])]))" class="mb-8 rounded-2xl border border-slate-200 bg-slate-50/70 p-5 sm:p-6">
                            <div class="mb-6 flex items-start justify-between gap-4 border-b border-slate-200 pb-5">
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-900">Pakket-popup</h3>
                                    <p class="mt-1 text-sm text-slate-500">Stel de inhoud en iconen samen zoals bezoekers ze zien.</p>
                                </div>
                                <span class="rounded-full bg-primary-50 px-3 py-1 text-xs font-semibold text-primary-700 ring-1 ring-primary-100">Visuele editor</span>
                            </div>

                            <div class="form-group mb-7">
                                <label for="popup_label" class="form-label">Label boven de omschrijving</label>
                                <input type="text" id="popup_label" name="popup_label" class="form-input" value="{{ old('popup_label', $service->popup_label) }}" placeholder="Bijvoorbeeld: Meest gekozen">
                                @error('popup_label')<span class="form-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="mb-8">
                                <div class="mb-3 flex flex-col items-start justify-between gap-3 sm:flex-row sm:items-center">
                                    <div><h4 class="text-sm font-semibold text-slate-900">Badges</h4><p class="text-xs text-slate-500">Korte voordelen boven de detailblokken.</p></div>
                                    <button type="button" @click="addBadge" class="inline-flex items-center gap-2 rounded-lg bg-white px-3 py-2 text-sm font-semibold text-primary-700 shadow-sm ring-1 ring-slate-200 transition-colors hover:bg-primary-50 active:scale-[.98]">+ Badge toevoegen</button>
                                </div>
                                <div x-show="badges.length === 0" class="rounded-xl border border-dashed border-slate-300 bg-white px-4 py-6 text-center text-sm text-slate-500">Nog geen badges toegevoegd.</div>
                                <div class="grid grid-cols-1 gap-3 2xl:grid-cols-2">
                                    <template x-for="(badge, index) in badges" :key="index">
                                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-[48px_minmax(0,1fr)_150px_40px] sm:items-center rounded-xl bg-white p-3 shadow-sm ring-1 ring-slate-200">
                                            <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-primary-50 text-primary-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" x-html="iconSvg(badge.icon)"></svg></span>
                                            <input type="text" :name="`popup_badges[${index}][text]`" x-model="badge.text" class="form-input" placeholder="Bijvoorbeeld: Maatwerk design" required>
                                            <select :name="`popup_badges[${index}][icon]`" x-model="badge.icon" class="form-input" aria-label="Icoon badge"><template x-for="icon in icons" :key="icon[0]"><option :value="icon[0]" x-text="icon[1]"></option></template></select>
                                            <button type="button" @click="removeBadge(index)" class="flex h-10 w-10 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-red-50 hover:text-red-600 active:scale-[.96]" aria-label="Badge verwijderen">×</button>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="mb-7">
                                <div class="mb-3 flex flex-col items-start justify-between gap-3 sm:flex-row sm:items-center">
                                    <div><h4 class="text-sm font-semibold text-slate-900">Detailblokken</h4><p class="text-xs text-slate-500">Uitgebreide voordelen met een eigen icoon.</p></div>
                                    <button type="button" @click="addDetail" class="inline-flex items-center gap-2 rounded-lg bg-white px-3 py-2 text-sm font-semibold text-primary-700 shadow-sm ring-1 ring-slate-200 transition-colors hover:bg-primary-50 active:scale-[.98]">+ Blok toevoegen</button>
                                </div>
                                <div x-show="details.length === 0" class="rounded-xl border border-dashed border-slate-300 bg-white px-4 py-8 text-center text-sm text-slate-500">Voeg een detailblok toe om een pakketvoordeel uit te lichten.</div>
                                <div class="grid grid-cols-1 gap-4 2xl:grid-cols-2">
                                    <template x-for="(detail, index) in details" :key="index">
                                        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
                                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-[48px_minmax(0,1fr)_150px_40px] sm:items-center">
                                                <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-accent-50 text-accent-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" x-html="iconSvg(detail.icon)"></svg></span>
                                                <input type="text" :name="`popup_details[${index}][title]`" x-model="detail.title" class="form-input" placeholder="Titel van het voordeel" required>
                                                <select :name="`popup_details[${index}][icon]`" x-model="detail.icon" class="form-input" aria-label="Icoon detailblok"><template x-for="icon in icons" :key="icon[0]"><option :value="icon[0]" x-text="icon[1]"></option></template></select>
                                                <button type="button" @click="removeDetail(index)" class="flex h-10 w-10 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-red-50 hover:text-red-600 active:scale-[.96]" aria-label="Detailblok verwijderen">×</button>
                                            </div>
                                            <textarea :name="`popup_details[${index}][description]`" x-model="detail.description" rows="2" class="form-textarea mt-3" placeholder="Beschrijf kort wat de klant hiervan merkt." required></textarea>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="popup_price_note" class="form-label">Toelichting bij de prijs</label>
                                <textarea id="popup_price_note" name="popup_price_note" rows="2" class="form-textarea" placeholder="Bijvoorbeeld: Eenmalig, exclusief onderhoud.">{{ old('popup_price_note', $service->popup_price_note) }}</textarea>
                                @error('popup_price_note')<span class="form-error">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <h3 class="text-md font-semibold text-gray-900 mb-4 border-b pb-2">Zichtbaarheid & Instellingen</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="form-group">
                                <label for="sort_order" class="form-label">Volgorde</label>
                                <input type="number" id="sort_order" name="sort_order" class="form-input" min="0" value="{{ old('sort_order', $service->sort_order) }}" placeholder="0">
                                <p class="mt-1 text-sm text-gray-500">Lager nummer = eerder weergegeven.</p>
                                @error('sort_order')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
                            <label class="flex items-center rounded-xl bg-slate-50 px-4 py-3 ring-1 ring-slate-200">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Actief (dienst is beschikbaar)</span>
                            </label>
                            <label class="flex items-center rounded-xl bg-slate-50 px-4 py-3 ring-1 ring-slate-200">
                                <input type="checkbox" name="show_on_homepage" value="1" {{ old('show_on_homepage', $service->show_on_homepage) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Tonen op homepage</span>
                            </label>
                            <label class="flex items-center rounded-xl bg-slate-50 px-4 py-3 ring-1 ring-slate-200">
                                <input type="checkbox" name="show_on_services_page" value="1" {{ old('show_on_services_page', $service->show_on_services_page) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Tonen op diensten pagina</span>
                            </label>
                            <label class="flex items-center rounded-xl bg-slate-50 px-4 py-3 ring-1 ring-slate-200">
                                <input type="checkbox" name="is_popular" value="1" {{ old('is_popular', $service->is_popular) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Markeren als populair</span>
                            </label>
                        </div>

                        @if($service->customerServices()->count() > 0)
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                                <p class="text-sm text-blue-700">
                                    <strong>Let op:</strong> Deze dienst is aan {{ $service->customerServices()->count() }} klant(en) gekoppeld.
                                </p>
                            </div>
                        @endif

                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('admin.services.index') }}" class="btn btn-outline">Annuleren</a>
                            <button type="submit" class="btn btn-primary">Wijzigingen Opslaan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
