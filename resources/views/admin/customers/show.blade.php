@extends('layouts.app')

@section('title', 'Klant ' . $customer->name . ' - Servura Admin')

@section('content')
@include('admin.partials.sidebar')
<!-- Admin Navigation -->
<nav class="hidden bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-primary-600">
                        Servura Admin
                    </a>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <a href="{{ route('admin.dashboard') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Dashboard
                    </a>
                    <a href="#" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Tickets
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Klanten
                    </a>
                    <a href="#" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Diensten
                    </a>
                    <a href="#" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Content
                    </a>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8">
                        <div class="h-8 w-8 rounded-full bg-primary-100 flex items-center justify-center">
                            <span class="text-sm font-medium text-primary-700">
                                {{ substr(Auth::user()->name, 0, 2) }}
                            </span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-gray-900">
                            {{ Auth::user()->name }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ Auth::user()->role === 'admin' ? 'Administrator' : 'Medewerker' }}
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="btn btn-outline text-sm">
                            Uitloggen
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Customer Details Content -->
<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="mx-auto w-full max-w-[1600px] px-4 py-4 sm:px-6 lg:px-8">
        @if(session('success'))<div class="mb-4 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-800 ring-1 ring-emerald-200">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="mb-4 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-800 ring-1 ring-red-200">{{ session('error') }}</div>@endif
        @if($errors->any())<div class="mb-4 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-800 ring-1 ring-red-200"><strong>Controleer de invoer.</strong><ul class="mt-1 list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <!-- Header -->
        <div class="px-4 py-6 sm:px-0">
            <div class="overflow-hidden">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <a href="{{ route('admin.customers.index') }}" class="text-primary-600 hover:text-primary-500 mr-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-lg font-medium text-blue-700">
                                            {{ substr($customer->name, 0, 2) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h1 class="text-2xl font-bold text-gray-900">
                                        {{ $customer->name }}
                                    </h1>
                                    <p class="mt-1 text-sm text-gray-600">
                                        {{ $customer->company ?: 'Geen bedrijf' }} • Klant ID: {{ $customer->id }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <form method="POST" action="{{ route('admin.customers.toggle-status', $customer) }}" class="inline">
                                @csrf
                                <button type="submit" class="btn {{ $customer->is_active ? 'btn-outline' : 'btn-primary' }}">
                                    {{ $customer->is_active ? 'Deactiveren' : 'Activeren' }}
                                </button>
                            </form>
                            <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-outline">
                                Bewerken
                            </a>
                            <button onclick="openPasswordModal()" class="btn btn-outline">
                                Wachtwoord resetten
                            </button>
                            <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}" onsubmit="return confirm('Weet je zeker dat je deze klant wilt verwijderen? Dit kan niet ongedaan worden gemaakt.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline border-red-300 text-red-600 hover:bg-red-50">Verwijderen</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Info and Stats -->
        <div class="px-4 py-6 sm:px-0">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Customer Info -->
                <div class="lg:col-span-1">
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Klantinformatie</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Status</label>
                                    <div class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $customer->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $customer->is_active ? 'Actief' : 'Inactief' }}
                                        </span>
                                        @if(is_null($customer->email_verified_at))
                                            <span class="ml-1 inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800">E-mail niet geverifieerd</span>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">E-mailadres</label>
                                    <div class="mt-1 text-sm text-gray-900">
                                        {{ $customer->email }}
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Telefoonnummer</label>
                                    <div class="mt-1 text-sm text-gray-900">
                                        {{ $customer->phone ?: '-' }}
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Bedrijf</label>
                                    <div class="mt-1 text-sm text-gray-900">
                                        {{ $customer->company ?: '-' }}
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Adres</label>
                                    <div class="mt-1 text-sm text-gray-900">
                                        @if($customer->street || $customer->city)
                                            {{ $customer->street }} {{ $customer->house_number }}<br>
                                            {{ $customer->postal_code }} {{ $customer->city }}<br>
                                            {{ $customer->country }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">KVK-nummer</label>
                                    <div class="mt-1 text-sm text-gray-900">
                                        {{ $customer->kvk_number ?: '-' }}
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">BTW-nummer</label>
                                    <div class="mt-1 text-sm text-gray-900">
                                        {{ $customer->vat_number ?: '-' }}
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Aangemaakt op</label>
                                    <div class="mt-1 text-sm text-gray-900">
                                        {{ $customer->created_at->format('d-m-Y H:i') }}
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Laatste login</label>
                                    <div class="mt-1 text-sm text-gray-900">
                                        {{ $customer->last_login_at ? $customer->last_login_at->format('d-m-Y H:i') : 'Nooit' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="lg:col-span-2">
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Statistieken</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="text-2xl font-bold text-gray-900">{{ $stats['total_services'] }}</div>
                                    <div class="text-sm text-gray-600">Totaal Diensten</div>
                                </div>
                                <div class="bg-green-50 rounded-lg p-4">
                                    <div class="text-2xl font-bold text-green-600">{{ $stats['active_services'] }}</div>
                                    <div class="text-sm text-gray-600">Actieve Diensten</div>
                                </div>
                                <div class="bg-blue-50 rounded-lg p-4">
                                    <div class="text-2xl font-bold text-blue-600">{{ $stats['total_tickets'] }}</div>
                                    <div class="text-sm text-gray-600">Totaal Tickets</div>
                                </div>
                                <div class="bg-yellow-50 rounded-lg p-4">
                                    <div class="text-2xl font-bold text-yellow-600">{{ $stats['open_tickets'] }}</div>
                                    <div class="text-sm text-gray-600">Open Tickets</div>
                                </div>
                                <div class="bg-purple-50 rounded-lg p-4">
                                    <div class="text-2xl font-bold text-purple-600">€{{ number_format($stats['monthly_cost'], 2, ',', '.') }}</div>
                                    <div class="text-sm text-gray-600">Maandelijkse Kosten</div>
                                </div>
                                <div class="bg-indigo-50 rounded-lg p-4">
                                    <div class="text-2xl font-bold text-indigo-600">€{{ number_format($stats['yearly_cost'], 2, ',', '.') }}</div>
                                    <div class="text-sm text-gray-600">Jaarlijkse Kosten</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="px-4 sm:px-0 mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <a href="{{ route('admin.customers.show', $customer) }}" class="{{ !request('tab') || request('tab') === 'overview' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Overzicht
                    </a>
                    <a href="{{ route('admin.customers.show', [$customer, 'tab' => 'services']) }}" class="{{ request('tab') === 'services' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Diensten ({{ $stats['total_services'] }})
                    </a>
                    <a href="{{ route('admin.customers.show', [$customer, 'tab' => 'invoices']) }}" class="{{ request('tab') === 'invoices' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Facturen ({{ $stats['total_invoices'] ?? 0 }})
                    </a>
                    @can('customer-emails.view')
                        <a href="{{ route('admin.customers.show', [$customer, 'tab' => 'emails']) }}" class="{{ request('tab') === 'emails' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            E-mails ({{ $emailLogs->count() }})
                        </a>
                    @endcan
                    <a href="{{ route('admin.customers.show', [$customer, 'tab' => 'tickets']) }}" class="{{ request('tab') === 'tickets' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Tickets ({{ $stats['total_tickets'] }})
                    </a>
                </nav>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="px-4 py-6 sm:px-0">
            @if(!request('tab') || request('tab') === 'overview')
                <!-- Overview Tab -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recente Activiteit</h3>
                    @if($customer->tickets->count() > 0)
                        <div class="space-y-3">
                            @foreach($customer->tickets as $ticket)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                                    <div class="flex-1">
                                        <a href="{{ route('admin.tickets.show', $ticket) }}" class="block hover:text-primary-600">
                                            <div class="text-sm font-medium text-gray-900">{{ $ticket->ticket_number }}</div>
                                            <div class="text-sm text-gray-500">{{ $ticket->title }}</div>
                                        </a>
                                        <div class="text-xs text-gray-400">{{ $ticket->created_at->format('d-m-Y H:i') }}</div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $ticket->statusLabel['color'] }}-100 text-{{ $ticket->statusLabel['color'] }}-800">
                                        {{ $ticket->statusLabel['text'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500">Geen recente activiteit.</p>
                    @endif
                </div>

            @elseif(request('tab') === 'services')
                <!-- Services Tab -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Diensten</h3>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="openServiceModal('assign')" class="btn btn-outline text-sm">Nieuwe dienst toewijzen</button>
                                <button type="button" onclick="openServiceModal('import-da')" class="btn btn-outline text-sm">Bestaand DA-account koppelen</button>
                                <a href="{{ route('admin.financial.quotes.create') }}" class="btn btn-primary text-sm">Offerte Aanmaken</a>
                            </div>
                        </div>

                        @if($customer->customerServices->count() > 0)
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dienst</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prijs</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acties</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($customer->customerServices as $cs)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $cs->service->title }}</div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $cs->statusLabel['color'] }}-100 text-{{ $cs->statusLabel['color'] }}-800">
                                                    {{ $cs->statusLabel['text'] }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $cs->formatted_price }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                                {{ $cs->start_date->format('d-m-Y') }} — {{ $cs->end_date ? $cs->end_date->format('d-m-Y') : '∞' }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                                <button type="button" onclick="openServiceModal({{ $cs->id }})" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 ring-1 ring-slate-200 transition-colors hover:bg-primary-50 hover:text-primary-700 hover:ring-primary-200" title="Dienst beheren" aria-label="{{ $cs->service->title }} beheren">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15.75A3.75 3.75 0 1 0 12 8.25a3.75 3.75 0 0 0 0 7.5Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12a7.5 7.5 0 0 0-.105-1.25l2.03-1.58-2-3.464-2.48 1a7.5 7.5 0 0 0-2.16-1.25L14.43 2.8h-4l-.36 2.656a7.5 7.5 0 0 0-2.16 1.25l-2.48-1-2 3.464 2.03 1.58a7.5 7.5 0 0 0 0 2.5l-2.03 1.58 2 3.464 2.48-1a7.5 7.5 0 0 0 2.16 1.25l.36 2.656h4l.36-2.656a7.5 7.5 0 0 0 2.16-1.25l2.48 1 2-3.464-2.03-1.58A7.5 7.5 0 0 0 19.5 12Z"/></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center py-8">
                                <h3 class="text-sm font-medium text-gray-900">Geen diensten</h3>
                                <p class="mt-1 text-sm text-gray-500">Klik op "Dienst Toewijzen" om een dienst toe te voegen.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Dienst toewijzen modal --}}
                <div id="serviceModal-assign" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 p-4 backdrop-blur-sm">
                    <div class="relative mx-auto mt-10 w-full max-w-xl rounded-2xl bg-white shadow-2xl ring-1 ring-slate-200">
                        <div class="flex items-start justify-between border-b border-slate-200 px-6 py-4">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">Dienst toewijzen</h3>
                                <p class="mt-0.5 text-xs text-slate-500">Wijs een product direct toe aan {{ $customer->name }} — er wordt een actieve dienst aangemaakt.</p>
                            </div>
                            <button type="button" onclick="closeServiceModal('assign')" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <form method="POST" action="{{ route('admin.customers.services.store', $customer) }}" class="space-y-4 px-6 py-5">
                            @csrf
                            <div>
                                <label class="form-label" for="assign_service_id">Dienst *</label>
                                <select id="assign_service_id" name="service_id" class="form-input" required>
                                    <option value="">Kies een dienst…</option>
                                    @foreach($availableServices as $availableService)
                                        <option value="{{ $availableService->id }}">{{ $availableService->title }} — {{ $availableService->formatted_price }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="form-label" for="assign_price">Prijs (leeg = standaardprijs)</label>
                                    <input id="assign_price" type="number" name="price" min="0" step="0.01" class="form-input" placeholder="0.00">
                                </div>
                                <div>
                                    <label class="form-label" for="assign_price_type">Prijstype</label>
                                    <select id="assign_price_type" name="price_type" class="form-input">
                                        <option value="">Standaard</option>
                                        <option value="eenmalig">Eenmalig</option>
                                        <option value="maandelijks">Maandelijks</option>
                                        <option value="jaarlijks">Jaarlijks</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="form-label" for="assign_domain">Domein (verplicht voor DirectAdmin-diensten)</label>
                                <input id="assign_domain" type="text" name="domain" class="form-input" placeholder="klantdomein.nl">
                            </div>
                            <div class="flex justify-end gap-3 border-t border-slate-200 pt-4">
                                <button type="button" onclick="closeServiceModal('assign')" class="btn btn-outline">Annuleren</button>
                                <button type="submit" class="btn btn-primary">Toewijzen</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Bestaand DirectAdmin-account koppelen --}}
                <div id="serviceModal-import-da" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 p-4 backdrop-blur-sm">
                    <div class="relative mx-auto my-6 w-full max-w-3xl rounded-2xl bg-white shadow-2xl ring-1 ring-slate-200">
                        <div class="flex items-start justify-between border-b border-slate-200 px-6 py-4">
                            <div><h3 class="text-lg font-semibold text-slate-900">Bestaand DirectAdmin-account koppelen</h3><p class="mt-1 max-w-2xl text-xs leading-5 text-slate-500">Controleert gebruiker, pakket en domein op DirectAdmin. Er wordt geen account aangemaakt, geen factuur gegenereerd en geen e-mail verzonden.</p></div>
                            <button type="button" onclick="closeServiceModal('import-da')" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600" aria-label="Sluiten"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                        </div>
                        <form method="POST" action="{{ route('admin.customers.services.import-directadmin', $customer) }}" class="space-y-5 px-6 py-5">
                            @csrf
                            <input type="hidden" name="import_existing_directadmin" value="1">
                            <div class="rounded-xl bg-sky-50 p-4 text-sm leading-6 text-sky-900 ring-1 ring-sky-200"><strong>Veilige import:</strong> Servura leest het bestaande account alleen ter controle. DirectAdmin-gegevens en het wachtwoord blijven ongewijzigd.</div>
                            <div><label class="form-label" for="import_service_id">Servura-product *</label><select id="import_service_id" name="service_id" class="form-input" required><option value="">Kies het gekoppelde maatwerkproduct…</option>@foreach($availableServices->where('fulfillment_type', 'directadmin') as $availableService)<option value="{{ $availableService->id }}" @selected(old('service_id') == $availableService->id)>{{ $availableService->title }} - {{ $availableService->provider_package ?: $availableService->directadmin_package }}</option>@endforeach</select></div>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div><label class="form-label" for="import_external_username">Bestaande DA-gebruikersnaam *</label><input id="import_external_username" name="external_username" class="form-input" required value="{{ old('external_username') }}" autocomplete="off"></div>
                                <div><label class="form-label" for="import_domain">Hoofddomein *</label><input id="import_domain" name="domain" class="form-input" required value="{{ old('domain') }}" placeholder="stc-de-rijnstreek.nl"></div>
                                <div><label class="form-label" for="import_price">Terugkerend bedrag excl. btw *</label><input id="import_price" type="number" name="price" min="0" step="0.01" class="form-input" required value="{{ old('price') }}"></div>
                                <div><label class="form-label" for="import_billing_cycle">Facturatiecyclus *</label><select id="import_billing_cycle" name="billing_cycle" class="form-input" required>@foreach($billingCycles as $cycle => $label)<option value="{{ $cycle }}" @selected(old('billing_cycle', 'yearly') === $cycle)>{{ $label }}</option>@endforeach</select></div>
                                <div><label class="form-label" for="import_start_date">Oorspronkelijke startdatum *</label><input id="import_start_date" type="date" name="start_date" class="form-input" required value="{{ old('start_date') }}"></div>
                                <div><label class="form-label" for="import_period_start">Huidige periode vanaf *</label><input id="import_period_start" type="date" name="current_period_start" class="form-input" required value="{{ old('current_period_start') }}"></div>
                                <div><label class="form-label" for="import_period_end">Betaald t/m</label><input id="import_period_end" type="date" name="current_period_end" class="form-input" value="{{ old('current_period_end') }}"></div>
                                <div><label class="form-label" for="import_next_invoice">Eerste factuurdatum in Servura</label><input id="import_next_invoice" type="date" name="next_invoice_date" class="form-input" value="{{ old('next_invoice_date') }}"></div>
                                <div><label class="form-label" for="import_payment_method">Betaalmethode *</label><select id="import_payment_method" name="payment_method" class="form-input"><option value="payment_link">Factuur met betaallink</option><option value="auto_debit">Automatische incasso</option></select></div>
                            </div>
                            <label class="flex items-center gap-2 text-sm text-slate-700"><input type="checkbox" name="auto_renew" value="1" checked class="rounded border-slate-300 text-primary-600"> Automatisch verlengen en vanaf de eerste factuurdatum factureren</label>
                            <div class="flex justify-end gap-3 border-t border-slate-200 pt-4"><button type="button" onclick="closeServiceModal('import-da')" class="btn btn-outline">Annuleren</button><button type="submit" class="btn btn-primary" onclick="return confirm('Bestaand DirectAdmin-account controleren en koppelen? Er wordt niets op DirectAdmin gewijzigd.')">Account controleren en koppelen</button></div>
                        </form>
                    </div>
                </div>

                {{-- Service beheer-modals --}}
                @foreach($customer->customerServices as $cs)
                    <div id="serviceModal-{{ $cs->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 p-4 backdrop-blur-sm">
                        <div class="relative mx-auto mt-10 w-full max-w-3xl rounded-2xl bg-white shadow-2xl ring-1 ring-slate-200">
                            <div class="flex items-start justify-between border-b border-slate-200 px-6 py-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-900">{{ $cs->service->title }}</h3>
                                    <p class="mt-0.5 text-xs text-slate-500">
                                        {{ $cs->formatted_price }} •
                                        <span class="inline-flex items-center rounded-full bg-{{ $cs->statusLabel['color'] }}-100 px-2 py-0.5 font-medium text-{{ $cs->statusLabel['color'] }}-800">{{ $cs->statusLabel['text'] }}</span>
                                        • {{ $cs->start_date->format('d-m-Y') }} — {{ $cs->end_date ? $cs->end_date->format('d-m-Y') : '∞' }}
                                    </p>
                                </div>
                                <button type="button" onclick="closeServiceModal({{ $cs->id }})" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                            <div class="max-h-[70vh] space-y-6 overflow-y-auto px-6 py-5">
                                {{-- Facturatie & renewal --}}
                                <section>
                                    <h4 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Facturatie & verlenging</h4>
                                    <form method="POST" action="{{ route('admin.customers.services.renewal.update', [$customer, $cs]) }}" class="mt-3">
                                        @csrf
                                        @method('PATCH')
                                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                                            <div><label class="form-label">Status</label><select name="status" class="form-input">@foreach(['active' => 'Actief', 'inactive' => 'Inactief', 'suspended' => 'In afwachting / geschorst', 'cancelled' => 'Geannuleerd', 'expired' => 'Verlopen'] as $statusValue => $statusLabel)<option value="{{ $statusValue }}" {{ $cs->status === $statusValue ? 'selected' : '' }}>{{ $statusLabel }}</option>@endforeach</select></div>
                                            <div><label class="form-label">Terugkerend bedrag (€)</label><input type="number" name="price" min="0" step="0.01" class="form-input" required value="{{ $cs->price }}"></div>
                                            <div><label class="form-label">Betaalperiode</label><select name="billing_cycle" class="form-input">@foreach($billingCycles as $cycle => $label)<option value="{{ $cycle }}" {{ $cs->billing_cycle === $cycle ? 'selected' : '' }}>{{ $label }}</option>@endforeach</select></div>
                                            <div><label class="form-label">Periode vanaf</label><input type="date" name="current_period_start" class="form-input" required value="{{ $cs->current_period_start?->format('Y-m-d') ?? $cs->start_date?->format('Y-m-d') }}"></div>
                                            <div><label class="form-label">Periode tot</label><input type="date" name="current_period_end" class="form-input" value="{{ $cs->current_period_end?->format('Y-m-d') }}"></div>
                                            <div><label class="form-label">Vervaldatum</label><input type="date" name="end_date" class="form-input" value="{{ $cs->end_date?->format('Y-m-d') }}"></div>
                                            <div><label class="form-label">Volgende factuurdatum</label><input type="date" name="next_invoice_date" class="form-input" value="{{ $cs->next_invoice_date?->format('Y-m-d') }}"></div>
                                            <div><label class="form-label">Betaalmethode</label><select name="payment_method" class="form-input"><option value="payment_link" {{ $cs->payment_method === 'payment_link' ? 'selected' : '' }}>Factuur met betaallink</option><option value="auto_debit" {{ $cs->payment_method === 'auto_debit' ? 'selected' : '' }}>Automatische incasso</option></select></div>
                                        </div>
                                        <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                            <label class="flex items-center gap-2 text-sm text-slate-700"><input type="checkbox" name="auto_renew" value="1" {{ $cs->auto_renew ? 'checked' : '' }} class="rounded border-slate-300 text-primary-600"> Automatisch verlengen</label>
                                            <button type="submit" class="btn btn-outline">Instellingen opslaan</button>
                                        </div>
                                    </form>
                                    <div class="mt-3 flex items-center justify-between rounded-xl bg-slate-50 p-3 ring-1 ring-slate-200">
                                        <p class="text-xs leading-relaxed text-slate-500">Zet de volgende factuurdatum op vandaag of eerder, sla op en verwerk daarna de renewal.</p>
                                        <form method="POST" action="{{ route('admin.customers.services.renewal.process', [$customer, $cs]) }}" onsubmit="return confirm('Renewal nu verwerken? Dit maakt een echte factuur en start de ingestelde Mollie-betaalmethode.')">
                                            @csrf
                                            <button type="submit" class="btn btn-primary whitespace-nowrap" {{ !$cs->auto_renew || $cs->billing_cycle === 'one_time' ? 'disabled' : '' }}>Renewal nu verwerken</button>
                                        </form>
                                    </div>
                                </section>

                                {{-- DirectAdmin --}}
                                @if($cs->service->fulfillment_type === 'directadmin')
                                    <section class="border-t border-slate-200 pt-5">
                                        <h4 class="text-sm font-semibold uppercase tracking-wide text-slate-500">DirectAdmin</h4>
                                        <dl class="mt-3 grid grid-cols-2 gap-3 text-xs sm:grid-cols-4">
                                            <div class="rounded-lg bg-slate-50 p-3 ring-1 ring-slate-200"><dt class="text-slate-400">Domein</dt><dd class="mt-0.5 font-medium text-slate-900">{{ $cs->domain ?? 'Niet ingevuld' }}</dd></div>
                                            <div class="rounded-lg bg-slate-50 p-3 ring-1 ring-slate-200"><dt class="text-slate-400">Gebruikersnaam</dt><dd class="mt-0.5 font-medium text-slate-900">{{ $cs->external_username ?? '-' }}</dd></div>
                                            <div class="rounded-lg bg-slate-50 p-3 ring-1 ring-slate-200"><dt class="text-slate-400">Provisioning</dt><dd class="mt-0.5 font-medium text-slate-900">{{ $cs->provisioning_status }}</dd></div>
                                            <div class="rounded-lg bg-slate-50 p-3 ring-1 ring-slate-200"><dt class="text-slate-400">Provisioned</dt><dd class="mt-0.5 font-medium text-slate-900">{{ $cs->provisioned_at?->format('d-m-Y H:i') ?? '-' }}</dd></div>
                                        </dl>
                                        @if($cs->provisioning_error)
                                            <p class="mt-3 rounded-lg bg-red-50 p-3 text-xs text-red-700 ring-1 ring-red-100">{{ $cs->provisioning_error }}</p>
                                        @endif
                                        @if($cs->suspension_reason === 'domain_required')
                                            <p class="mt-3 rounded-lg bg-amber-50 p-3 text-xs text-amber-800 ring-1 ring-amber-100">Deze dienst is betaald maar wacht op een domein voordat het hostingaccount wordt aangemaakt.</p>
                                        @endif
                                        <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-end">
                                            <form method="POST" action="{{ route('admin.customers.services.domain', [$customer, $cs]) }}" class="flex flex-1 items-end gap-2">
                                                @csrf
                                                <div class="flex-1"><label class="form-label">Domein</label><input type="text" name="domain" class="form-input" required value="{{ $cs->domain }}" placeholder="klantdomein.nl"></div>
                                                <button type="submit" class="btn btn-outline whitespace-nowrap">Domein opslaan</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.customers.services.provision', [$customer, $cs]) }}" onsubmit="return confirm('DirectAdmin-account nu (opnieuw) aanmaken?')">
                                                @csrf
                                                <button type="submit" class="btn btn-primary whitespace-nowrap" {{ !$cs->domain ? 'disabled' : '' }}>Provisioning starten</button>
                                            </form>
                                        </div>
                                    </section>
                                @endif
                            </div>

                            <div class="flex items-center justify-between border-t border-slate-200 px-6 py-4">
                                <form method="POST" action="{{ route('admin.customers.services.destroy', [$customer, $cs]) }}" onsubmit="return confirm('Dienst definitief verwijderen? Gekoppelde facturen en tickets blijven bestaan maar verliezen de koppeling.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-500">Dienst verwijderen</button>
                                </form>
                                <div class="flex items-center gap-2">
                                    @if($cs->status === 'active')
                                        <form method="POST" action="{{ route('admin.customers.services.cancel', [$customer, $cs]) }}" onsubmit="return confirm('Weet je zeker dat je deze dienst wilt annuleren?')">
                                            @csrf
                                            <button type="submit" class="btn btn-outline border-red-200 text-red-600 hover:bg-red-50">Annuleren</button>
                                        </form>
                                    @endif
                                    <button type="button" onclick="closeServiceModal({{ $cs->id }})" class="btn btn-outline">Sluiten</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            @elseif(request('tab') === 'invoices')
                <!-- Invoices Tab -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Facturen</h3>
                        @if(isset($invoices) && $invoices->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Factuurnummer</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Datum</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Bedrag</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($invoices as $invoice)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <a href="{{ route('admin.financial.invoices.show', $invoice) }}" class="text-primary-600 hover:text-primary-500 font-medium">{{ $invoice->invoice_number }}</a>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $invoice->invoice_date->format('d-m-Y') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-medium">€{{ number_format($invoice->total, 2, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $invoice->statusLabel['color'] }}-100 text-{{ $invoice->statusLabel['color'] }}-800">
                                                        {{ $invoice->statusLabel['text'] }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 text-center py-8">Geen facturen voor deze klant.</p>
                        @endif
                    </div>
                </div>

            @elseif(request('tab') === 'emails')
                @can('customer-emails.view')
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-5">
                        @can('customer-emails.send')
                            <div class="rounded-lg bg-white p-6 shadow lg:col-span-2">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Nieuwe e-mail</h3>
                                    <p class="mt-1 text-sm text-gray-500">Wordt verzonden naar {{ $customer->email }}.</p>
                                </div>
                                <form method="POST" action="{{ route('admin.customers.emails.standard', $customer) }}" class="mt-6 rounded-xl border border-sky-200 bg-sky-50 p-4">
                                    @csrf
                                    <label for="standard_email_template" class="form-label">Standaard e-mail</label>
                                    <select id="standard_email_template" name="template" class="form-input" required>
                                        <option value="account-created">Welkomstmail / account aangemaakt</option>
                                        @if(!$customer->email_verified_at)
                                            <option value="verify-email">E-mailadres bevestigen</option>
                                        @endif
                                        <option value="password-reset">Wachtwoord opnieuw instellen</option>
                                    </select>
                                    <p class="mt-2 text-xs leading-5 text-sky-800">Links voor verificatie en wachtwoordherstel worden bij verzending opnieuw en veilig aangemaakt.</p>
                                    <button class="btn btn-outline mt-3 text-sm" type="submit" onclick="return confirm('Deze standaard e-mail nu naar de klant verzenden?')">Standaard e-mail verzenden</button>
                                </form>
                                <div class="my-6 flex items-center gap-3"><div class="h-px flex-1 bg-gray-200"></div><span class="text-xs font-medium uppercase tracking-wide text-gray-400">of eigen bericht</span><div class="h-px flex-1 bg-gray-200"></div></div>
                                <form method="POST" action="{{ route('admin.customers.emails.send', $customer) }}" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label for="email_subject" class="form-label">Onderwerp</label>
                                        <input id="email_subject" name="subject" class="form-input" maxlength="255" required value="{{ old('subject') }}">
                                    </div>
                                    <div>
                                        <label for="email_message" class="form-label">Bericht</label>
                                        <textarea id="email_message" name="message" rows="10" class="form-input" maxlength="20000" required>{{ old('message') }}</textarea>
                                        <p class="mt-1 text-xs text-gray-500">De Servura e-mailopmaak en handtekening worden automatisch toegepast.</p>
                                    </div>
                                    <button type="submit" class="btn btn-primary">E-mail verzenden</button>
                                </form>
                            </div>
                        @endcan
                        <div class="rounded-lg bg-white shadow {{ auth()->user()->can('customer-emails.send') ? 'lg:col-span-3' : 'lg:col-span-5' }}">
                            <div class="border-b border-gray-200 px-6 py-4">
                                <h3 class="text-lg font-medium text-gray-900">Verzendhistorie</h3>
                                <p class="mt-1 text-sm text-gray-500">De laatste 100 e-mails en verzendpogingen.</p>
                            </div>
                            <div class="divide-y divide-gray-200">
                                @forelse($emailLogs as $emailLog)
                                    <details class="group px-6 py-4">
                                        <summary class="flex cursor-pointer list-none items-start justify-between gap-4">
                                            <div class="min-w-0">
                                                <div class="truncate text-sm font-medium text-gray-900">{{ $emailLog->subject }}</div>
                                                <div class="mt-1 text-xs text-gray-500">
                                                    {{ $emailLog->sent_at?->format('d-m-Y H:i') ?? $emailLog->created_at->format('d-m-Y H:i') }}
                                                    · {{ $emailLog->template ? 'Systeemmail' : 'Handmatig' }}
                                                    @if($emailLog->sender) · door {{ $emailLog->sender->name }} @endif
                                                </div>
                                            </div>
                                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $emailLog->status === 'sent' ? 'bg-green-100 text-green-800' : ($emailLog->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">
                                                {{ $emailLog->status === 'sent' ? 'Verzonden' : ($emailLog->status === 'failed' ? 'Mislukt' : 'Bezig') }}
                                            </span>
                                        </summary>
                                        <div class="mt-4 rounded-lg border border-gray-200 bg-gray-50 p-4 text-sm">
                                            <div class="mb-3 text-xs text-gray-500">Aan: {{ $emailLog->recipient_name }} &lt;{{ $emailLog->recipient_email }}&gt;</div>
                                            @if($emailLog->error_message)
                                                <div class="mb-3 rounded-md bg-red-50 p-3 text-red-800">{{ $emailLog->error_message }}</div>
                                            @endif
                                            <iframe class="h-96 w-full rounded-md bg-white" sandbox srcdoc="{{ $emailLog->body_html }}" title="Voorbeeld van {{ $emailLog->subject }}"></iframe>
                                        </div>
                                    </details>
                                @empty
                                    <p class="px-6 py-10 text-center text-sm text-gray-500">Voor deze klant zijn nog geen e-mails geregistreerd.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @else
                    @php abort(403) @endphp
                @endcan

            @elseif(request('tab') === 'tickets')
                <!-- Tickets Tab -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Tickets</h3>
                        @if($customer->tickets->count() > 0)
                            <div class="space-y-3">
                                @foreach($customer->tickets as $ticket)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                                        <div class="flex-1">
                                            <a href="{{ route('admin.tickets.show', $ticket) }}" class="block hover:text-primary-600">
                                                <div class="text-sm font-medium text-gray-900">{{ $ticket->ticket_number }}</div>
                                                <div class="text-sm text-gray-500">{{ $ticket->title }}</div>
                                            </a>
                                            <div class="text-xs text-gray-400">{{ $ticket->created_at->format('d-m-Y H:i') }}</div>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $ticket->statusLabel['color'] }}-100 text-{{ $ticket->statusLabel['color'] }}-800">
                                            {{ $ticket->statusLabel['text'] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 text-center py-8">Geen tickets voor deze klant.</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Password Reset Modal -->
<div id="passwordModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900">Wachtwoord Resetten</h3>
            <p class="mt-2 text-sm text-gray-600">
                Voer een nieuw wachtwoord in voor {{ $customer->name }}.
            </p>
            <form method="POST" action="{{ route('admin.customers.reset-password', $customer) }}" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nieuw Wachtwoord</label>
                    <input type="password" name="password" required class="form-input mt-1" placeholder="Minimaal 8 tekens">
                    @error('password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Bevestig Wachtwoord</label>
                    <input type="password" name="password_confirmation" required class="form-input mt-1" placeholder="Herhaal wachtwoord">
                    @error('password_confirmation')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closePasswordModal()" class="btn btn-outline">
                        Annuleren
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Resetten
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openPasswordModal() {
    document.getElementById('passwordModal').classList.remove('hidden');
}
function closePasswordModal() {
    document.getElementById('passwordModal').classList.add('hidden');
}
function openServiceModal(id) {
    document.getElementById('serviceModal-' + id).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeServiceModal(id) {
    document.getElementById('serviceModal-' + id).classList.add('hidden');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('[id^="serviceModal-"]:not(.hidden)').forEach(function (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        });
    }
});
document.addEventListener('click', function (e) {
    if (e.target.id && e.target.id.startsWith('serviceModal-')) {
        e.target.classList.add('hidden');
        document.body.style.overflow = '';
    }
});
@if(old('import_existing_directadmin'))
document.addEventListener('DOMContentLoaded', function () { openServiceModal('import-da'); });
@endif
</script>
@endsection
