@extends('layouts.app')

@section('title', 'Diensten Beheren - Servura Admin')

@section('content')
@include('admin.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="mx-auto w-full max-w-[1600px] px-4 py-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="py-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Diensten</h1>
                    <p class="mt-1 text-sm text-gray-600">Beheer alle diensten en pakketten.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.service-categories.index') }}" class="btn btn-outline">Categorieën</a>
                    <a href="{{ route('admin.services.create') }}" class="btn btn-primary">Nieuwe Dienst</a>
                </div>
            </div>
        </div>

        @include('admin.partials.services-nav')

        @if(session('success'))
            <div class="px-4 sm:px-0 mb-4">
                <div class="rounded-md bg-green-50 p-4">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="px-4 sm:px-0 mb-4">
                <div class="rounded-md bg-red-50 p-4">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Filters -->
        <div class="mb-6 rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200 sm:p-5">
            <form method="GET" class="grid grid-cols-1 items-end gap-4 sm:grid-cols-2 xl:grid-cols-[minmax(260px,2fr)_repeat(3,minmax(150px,1fr))_auto]">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Zoeken</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Titel of omschrijving..." class="form-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="form-input">
                        <option value="">Alle</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actief</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactief</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type" class="form-input">
                        <option value="">Alle</option>
                        <option value="website_pakket" {{ request('type') == 'website_pakket' ? 'selected' : '' }}>Website Pakket</option>
                        <option value="hosting" {{ request('type') == 'hosting' ? 'selected' : '' }}>Hosting</option>
                        <option value="custom" {{ request('type') == 'custom' ? 'selected' : '' }}>Custom Pakket</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Zichtbaarheid</label>
                    <select name="visibility" class="form-input">
                        <option value="">Alle</option>
                        <option value="homepage" {{ request('visibility') == 'homepage' ? 'selected' : '' }}>Homepage</option>
                        <option value="services_page" {{ request('visibility') == 'services_page' ? 'selected' : '' }}>Diensten pagina</option>
                    </select>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" class="btn btn-outline whitespace-nowrap">Filteren</button>
                    @if(request()->hasAny(['search', 'status', 'type', 'visibility']))
                        <a href="{{ route('admin.services.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-800">Wissen</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Services Table -->
        <div>
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
                @if($services->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[1050px] table-fixed divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="w-[28%] px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Dienst</th>
                                    <th class="w-[13%] px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Type</th>
                                    <th class="w-[13%] px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Prijs</th>
                                    <th class="w-[13%] px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                                    <th class="w-[14%] px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Zichtbaar op</th>
                                    <th class="w-[7%] px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Volgorde</th>
                                    <th class="w-[12%] px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Acties</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($services as $service)
                                    <tr class="transition-colors hover:bg-slate-50/80">
                                        <td class="px-4 py-4">
                                            <div class="truncate text-sm font-semibold text-slate-900">{{ $service->title }}</div>
                                            <div class="mt-0.5 truncate text-sm text-slate-500">{{ $service->short_description }}</div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            <div class="truncate font-medium">{{ $service->category?->name ?? $service->service_type_label }}</div>
                                            <div class="mt-0.5 truncate text-xs text-slate-500">{{ $service->fulfillment_type === 'directadmin' ? ($service->serverConnection?->name ?? 'Geen server gekoppeld') : 'Handmatig' }}</div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            <div class="font-medium">{{ $service->formatted_price }}</div>
                                            @if($service->prices->count() > 1)
                                                <div class="mt-0.5 text-xs text-slate-500">{{ $service->prices->count() }} prijsperioden</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $service->is_active ? 'Actief' : 'Inactief' }}
                                            </span>
                                            @if($service->is_popular)
                                                <span class="ml-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Populair
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="flex flex-wrap gap-1">
                                                @if($service->show_on_homepage)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Homepage</span>
                                                @endif
                                                @if($service->show_on_services_page)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">Diensten</span>
                                                @endif
                                                @if(!$service->show_on_homepage && !$service->show_on_services_page)
                                                    <span class="text-xs text-gray-400">Nergens</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $service->sort_order }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.services.edit', $service) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 ring-1 ring-slate-200 transition-colors hover:bg-primary-50 hover:text-primary-700 hover:ring-primary-200" title="Bewerken" aria-label="{{ $service->title }} bewerken">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931ZM16.862 4.487 19.5 7.125"/></svg>
                                                </a>
                                                <form action="{{ route('admin.services.destroy', $service) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je deze dienst wilt verwijderen?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 ring-1 ring-slate-200 transition-colors hover:bg-red-50 hover:text-red-600 hover:ring-red-200" title="Verwijderen" aria-label="{{ $service->title }} verwijderen">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0V4.477c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($services->hasPages())
                        <div class="px-4 py-4 border-t border-gray-200">
                            {{ $services->withQueryString()->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Geen diensten gevonden</h3>
                        <p class="mt-1 text-sm text-gray-500">Maak een nieuwe dienst aan om te beginnen.</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.services.create') }}" class="btn btn-primary">Nieuwe Dienst</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
