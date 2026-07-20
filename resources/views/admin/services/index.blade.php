@extends('layouts.app')

@section('title', 'Diensten Beheren - Servura Admin')

@section('content')
@include('admin.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="px-4 py-6 sm:px-0">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Diensten</h1>
                    <p class="mt-1 text-sm text-gray-600">Beheer alle diensten en pakketten.</p>
                </div>
                <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
                    Nieuwe Dienst
                </a>
            </div>
        </div>

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
        <div class="px-4 sm:px-0 mb-6">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Zoeken</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Titel of omschrijving..." class="form-input w-64">
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
                <button type="submit" class="btn btn-outline">Filteren</button>
                @if(request()->hasAny(['search', 'status', 'type', 'visibility']))
                    <a href="{{ route('admin.services.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Reset</a>
                @endif
            </form>
        </div>

        <!-- Services Table -->
        <div class="px-4 sm:px-0">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                @if($services->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dienst</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prijs</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Zichtbaar op</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Volgorde</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acties</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($services as $service)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $service->title }}</div>
                                            <div class="text-sm text-gray-500 truncate max-w-xs">{{ $service->short_description }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $service->service_type_label }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $service->formatted_price }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $service->is_active ? 'Actief' : 'Inactief' }}
                                            </span>
                                            @if($service->is_popular)
                                                <span class="ml-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Populair
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $service->sort_order }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('admin.services.edit', $service) }}" class="text-primary-600 hover:text-primary-900 mr-3">Bewerken</a>
                                            <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="inline" onsubmit="return confirm('Weet je zeker dat je deze dienst wilt verwijderen?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Verwijderen</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($services->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200">
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
