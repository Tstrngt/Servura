@extends('layouts.app')

@section('title', 'Klant {{ $customer->name }} - Servura Admin')

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
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white overflow-hidden shadow rounded-lg">
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
                                Wachtwoord Resetten
                            </button>
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
                            <a href="{{ route('admin.financial.quotes.create') }}" class="btn btn-primary text-sm">Offerte Aanmaken</a>
                        </div>

                        @if($customer->customerServices->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dienst</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prijs</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Startdatum</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Einddatum</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acties</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($customer->customerServices as $cs)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $cs->service->title }}</div>
                                                    <div class="text-sm text-gray-500">{{ $cs->service->short_description }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $cs->statusLabel['color'] }}-100 text-{{ $cs->statusLabel['color'] }}-800">
                                                        {{ $cs->statusLabel['text'] }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cs->formatted_price }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cs->start_date->format('d-m-Y') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cs->end_date ? $cs->end_date->format('d-m-Y') : 'Onbeperkt' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                                    @if($cs->status === 'active')
                                                        <form method="POST" action="{{ route('admin.customers.services.cancel', [$customer, $cs]) }}" class="inline">
                                                            @csrf
                                                            <button type="submit" class="text-sm text-red-600 hover:text-red-500">Annuleren</button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <h3 class="text-sm font-medium text-gray-900">Geen diensten</h3>
                                <p class="mt-1 text-sm text-gray-500">Klik op "Dienst Toewijzen" om een dienst toe te voegen.</p>
                            </div>
                        @endif
                    </div>
                </div>

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
</script>
@endsection
