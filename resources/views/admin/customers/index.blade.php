@extends('layouts.app')

@section('title', 'Klantenbeheer - Servura Admin')

@section('content')
@include('admin.partials.sidebar')

<div class="min-h-screen bg-gray-50 lg:pl-64">
    <main class="mx-auto w-full max-w-[1600px] px-4 py-4 sm:px-6 lg:px-8">
        <header class="flex flex-col gap-4 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Klanten</h1>
                <p class="mt-1 text-sm text-gray-600">Beheer klanten, supporttickets en opzegverzoeken.</p>
            </div>
            <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">Nieuwe klant</a>
        </header>

        @include('admin.partials.customers-nav')

        <form method="GET" action="{{ route('admin.customers.index') }}" class="mb-6 grid grid-cols-1 items-end gap-4 sm:grid-cols-2 xl:grid-cols-[minmax(260px,2fr)_repeat(2,minmax(160px,1fr))_auto]">
            <div>
                <label for="customer-search" class="mb-1 block text-sm font-medium text-gray-700">Zoeken</label>
                <input id="customer-search" type="text" name="search" class="form-input w-full" value="{{ request('search') }}" placeholder="Naam, e-mail, bedrijf...">
            </div>
            <div>
                <label for="customer-status" class="mb-1 block text-sm font-medium text-gray-700">Status</label>
                <select id="customer-status" name="status" class="form-input w-full">
                    <option value="">Alle statussen</option>
                    <option value="active" @selected(request('status') === 'active')>Actief</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactief</option>
                </select>
            </div>
            <div>
                <label for="customer-sort" class="mb-1 block text-sm font-medium text-gray-700">Sorteren</label>
                <select id="customer-sort" name="sort_by" class="form-input w-full">
                    <option value="created_at" @selected(request('sort_by') === 'created_at')>Aanmaakdatum</option>
                    <option value="name" @selected(request('sort_by') === 'name')>Naam</option>
                    <option value="email" @selected(request('sort_by') === 'email')>E-mail</option>
                    <option value="company" @selected(request('sort_by') === 'company')>Bedrijf</option>
                </select>
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" class="btn btn-outline whitespace-nowrap">Filteren</button>
                @if(request()->hasAny(['search', 'status', 'sort_by']))
                    <a href="{{ route('admin.customers.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Reset</a>
                @endif
            </div>
        </form>

        <div class="overflow-hidden rounded-lg bg-white shadow">
            @if($customers->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Klant</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Bedrijf</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Contact</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Aangemaakt</th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase text-gray-500">Acties</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($customers as $customer)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('admin.customers.show', $customer) }}" class="font-medium text-primary-600 hover:text-primary-500">{{ $customer->name }}</a>
                                        <div class="text-sm text-gray-500">ID: {{ $customer->id }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->company ?: '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $customer->email }}</div>
                                        <div class="text-sm text-gray-500">{{ $customer->phone ?: '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $customer->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">{{ $customer->is_active ? 'Actief' : 'Inactief' }}</span>
                                        @if(is_null($customer->email_verified_at))
                                            <span class="ml-1 inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800">Niet geverifieerd</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $customer->created_at->format('d-m-Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.customers.show', $customer) }}" class="text-sm font-medium text-primary-600 hover:text-primary-500">Bekijk</a>
                                            <a href="{{ route('admin.customers.edit', $customer) }}" class="text-gray-400 hover:text-gray-600" title="Bewerken" aria-label="{{ $customer->name }} bewerken">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                            <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}" onsubmit="return confirm('Weet u zeker dat u deze klant wilt verwijderen?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-600" title="Verwijderen" aria-label="{{ $customer->name }} verwijderen">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($customers->hasPages())
                    <div class="border-t border-gray-200 px-6 py-4">{{ $customers->withQueryString()->links() }}</div>
                @endif
            @else
                <div class="px-6 py-12 text-center">
                    <h2 class="text-sm font-medium text-gray-900">Geen klanten gevonden</h2>
                    <p class="mt-1 text-sm text-gray-500">Er zijn geen klanten die voldoen aan je zoekcriteria.</p>
                    <a href="{{ route('admin.customers.create') }}" class="btn btn-primary mt-6">Nieuwe klant</a>
                </div>
            @endif
        </div>
    </main>
</div>
@endsection
