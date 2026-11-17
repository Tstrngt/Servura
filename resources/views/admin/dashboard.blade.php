@extends('layouts.app')

@section('title', 'Admin Dashboard - Servura')

@section('content')
@include('admin.partials.sidebar')
<!-- Admin Navigation -->
<nav class="hidden bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-primary-600">
                        Servura Admin
                    </a>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <a href="{{ route('admin.dashboard') }}" class="border-primary-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Dashboard
                    </a>
                    <a href="#" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Tickets
                    </a>
                    <a href="#" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
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

<!-- Admin Dashboard Content -->
<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="sm:flex sm:items-center sm:justify-between">
                        <div class="sm:flex-auto">
                            <h1 class="text-2xl font-bold text-gray-900">
                                Admin Dashboard
                            </h1>
                            <p class="mt-2 text-sm text-gray-700">
                                Welkom bij het Servura adminportaal. Hier vindt u een overzicht van alle belangrijke statistieken en activiteiten.
                            </p>
                        </div>
                        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                            <span class="text-sm text-gray-500">
                                Laatste login: {{ Auth::user()->last_login_at ? Auth::user()->last_login_at->format('d-m-Y H:i') : 'Nooit' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="px-4 py-6 sm:px-0">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <a href="{{ route('admin.customers.index') }}" class="bg-white overflow-hidden shadow rounded-lg transition-shadow hover:shadow-md">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 012 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Totaal Klanten
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ $stats['total_customers'] }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.customers.index', ['status' => 'active']) }}" class="bg-white overflow-hidden shadow rounded-lg transition-shadow hover:shadow-md">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Actieve Klanten
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ $stats['active_customers'] }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.tickets.index', ['status' => 'open']) }}" class="bg-white overflow-hidden shadow rounded-lg transition-shadow hover:shadow-md">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Open Tickets
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ $stats['open_tickets'] }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.tickets.index', ['status' => 'overdue']) }}" class="bg-white overflow-hidden shadow rounded-lg transition-shadow hover:shadow-md">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Overdue Tickets
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ $stats['overdue_tickets'] }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Revenue Charts -->
        <div class="px-4 py-6 sm:px-0">
            @php
                $dailyMax = max(1, (float) $dailyRevenue->max('total'));
                $dailyPoints = $dailyRevenue->values()->map(function ($day, $i) use ($dailyMax) {
                    $x = 10 + $i * (580 / 29);
                    $y = 125 - ($day['total'] / $dailyMax) * 105;
                    return round($x, 1).','.round($y, 1);
                })->implode(' ');
                $monthlyMax = max(1, (float) $monthlyRevenue->max('total'));
            @endphp
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Omzet laatste 30 dagen</h3>
                            <span class="text-lg font-semibold text-gray-900">€{{ number_format($dailyRevenue->sum('total'), 2, ',', '.') }}</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-4">Totaal laatste 30 dagen</p>
                        <svg viewBox="0 0 600 140" class="w-full h-40" preserveAspectRatio="none" role="img" aria-label="Omzetgrafiek laatste 30 dagen">
                            <polygon fill="rgba(99,102,241,0.12)" points="10,135 {{ $dailyPoints }} 590,135"></polygon>
                            <polyline fill="none" stroke="#6366f1" stroke-width="2" points="{{ $dailyPoints }}"></polyline>
                        </svg>
                        <div class="flex justify-between text-xs text-gray-400 mt-1">
                            <span>{{ $dailyRevenue->first()['label'] }}</span>
                            <span>{{ $dailyRevenue->last()['label'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Omzet {{ now()->year }}</h3>
                            <span class="text-lg font-semibold text-gray-900">€{{ number_format($stats['revenue_this_year'], 2, ',', '.') }}</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-4">Totaal dit jaar</p>
                        <svg viewBox="0 0 600 170" class="w-full h-44" role="img" aria-label="Omzetgrafiek per maand">
                            @foreach($monthlyRevenue as $i => $month)
                                @php
                                    $barHeight = round(($month['total'] / $monthlyMax) * 120, 1);
                                    $x = 15 + $i * 47.5;
                                    $y = 140 - $barHeight;
                                @endphp
                                <rect x="{{ $x }}" y="{{ $y }}" width="32" height="{{ max($barHeight, 1) }}" rx="4" fill="{{ $i + 1 === (int) now()->month ? '#6366f1' : '#c7d2fe' }}">
                                    <title>{{ $month['label'] }}: €{{ number_format($month['total'], 2, ',', '.') }}</title>
                                </rect>
                                <text x="{{ $x + 16 }}" y="158" text-anchor="middle" fill="#9ca3af" font-size="11">{{ $month['label'] }}</text>
                            @endforeach
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Tables -->
        <div class="px-4 py-6 sm:px-0">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Tickets -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Recente Tickets
                            </h3>
                            <a href="{{ route('admin.tickets.index') }}" class="text-sm text-primary-600 hover:text-primary-500">
                                Bekijk alles
                            </a>
                        </div>
                        @if($recentTickets->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentTickets as $ticket)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $ticket->ticket_number }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $ticket->title }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                {{ $ticket->user->name }} • {{ $ticket->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $ticket->statusLabel['color'] }}-100 text-{{ $ticket->statusLabel['color'] }}-800">
                                                {{ $ticket->statusLabel['text'] }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-sm text-gray-500">Geen recente tickets</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Unread Messages -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Ongelezen Berichten
                            </h3>
                            @if($unreadMessages->count() > 0)
                                <form action="{{ route('admin.contact-messages.read-all') }}" method="POST" class="inline m-0">
                                    @csrf
                                    <button type="submit" class="text-sm text-primary-600 hover:text-primary-500">Alles gelezen</button>
                                </form>
                            @endif
                        </div>
                        @if($unreadMessages->count() > 0)
                            <div class="space-y-3">
                                @foreach($unreadMessages as $message)
                                    <div class="group flex items-start justify-between p-3 bg-gray-50 rounded-md">
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $message->name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $message->subject }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                {{ $message->email }} • {{ $message->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                        <form action="{{ route('admin.contact-messages.read', $message) }}" method="POST" class="ml-3 hidden group-hover:block">
                                            @csrf
                                            <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 hover:bg-white hover:text-gray-600 transition-colors" aria-label="Bericht verwijderen">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-sm text-gray-500">Geen ongelezen berichten</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Abuse Reports -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Abuse Meldingen
                            </h3>
                            <a href="{{ route('admin.abuse-reports.index') }}" class="text-sm text-primary-600 hover:text-primary-500">
                                Bekijk alles
                            </a>
                        </div>
                        @if($abuseReports->count() > 0)
                            <div class="space-y-3">
                                @foreach($abuseReports as $report)
                                    <a href="{{ route('admin.abuse-reports.show', $report) }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-md hover:bg-gray-100">
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-medium text-gray-900 truncate">
                                                {{ $report->domain ?: ($report->url ?: 'Algemene melding') }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $report->category_label }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                {{ $report->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $report->status === 'new' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ $report->status_label }}
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-sm text-gray-500">Geen openstaande meldingen</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Customers and Active Services -->
        <div class="px-4 py-6 sm:px-0">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recently Active Customers -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Actieve Klanten
                            </h3>
                            <a href="{{ route('admin.customers.index') }}" class="text-sm text-primary-600 hover:text-primary-500">
                                Bekijk alles
                            </a>
                        </div>
                        @if($activeCustomers->count() > 0)
                            <div class="space-y-3">
                                @foreach($activeCustomers as $customer)
                                    <a href="{{ route('admin.customers.show', $customer) }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-md hover:bg-gray-100">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                                    <span class="text-xs font-medium text-indigo-700">
                                                        {{ substr($customer->name, 0, 2) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $customer->name }}
                                                </div>
                                                <div class="text-xs text-gray-400">
                                                    Laatst ingelogd {{ $customer->last_login_at->diffForHumans() }}
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-sm text-gray-500">Nog geen klanten ingelogd</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Active Services -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Actieve Diensten
                            </h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $stats['active_services'] }} actief
                            </span>
                        </div>
                        @if($activeServices->count() > 0)
                            <div class="space-y-3">
                                @foreach($activeServices as $service)
                                    <a href="{{ route('admin.customers.show', [$service->user, 'tab' => 'services']) }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-md hover:bg-gray-100">
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-medium text-gray-900 truncate">
                                                {{ $service->service->title ?? 'Dienst' }}
                                            </div>
                                            <div class="text-sm text-gray-500 truncate">
                                                {{ $service->user->name }}{{ $service->domain ? ' • '.$service->domain : '' }}
                                            </div>
                                        </div>
                                        <div class="ml-4 text-sm text-gray-900 whitespace-nowrap">
                                            €{{ number_format($service->price, 2, ',', '.') }}<span class="text-xs text-gray-400">/{{ $service->price_type === 'jaarlijks' ? 'jaar' : 'mnd' }}</span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-sm text-gray-500">Geen actieve diensten</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Customers and Expiring Services -->
        <div class="px-4 py-6 sm:px-0">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Customers -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Recente Klanten
                            </h3>
                            <a href="{{ route('admin.customers.index') }}" class="text-sm text-primary-600 hover:text-primary-500">
                                Bekijk alles
                            </a>
                        </div>
                        @if($recentCustomers->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentCustomers as $customer)
                                    <a href="{{ route('admin.customers.show', $customer) }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-md hover:bg-gray-100">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <span class="text-xs font-medium text-blue-700">
                                                        {{ substr($customer->name, 0, 2) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $customer->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $customer->company }}
                                                </div>
                                                <div class="text-xs text-gray-400">
                                                    {{ $customer->created_at->format('d-m-Y') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $customer->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $customer->is_active ? 'Actief' : 'Inactief' }}
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-sm text-gray-500">Geen recente klanten</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Services Expiring Soon -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Diensten die Verlopen
                            </h3>
                            <span class="text-sm text-gray-400">Overzicht niet beschikbaar</span>
                        </div>
                        @if($expiringServices->count() > 0)
                            <div class="space-y-3">
                                @foreach($expiringServices as $service)
                                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-md">
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $service->service->title }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $service->user->name }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                Verloopt op {{ $service->end_date->format('d-m-Y') }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                {{ $service->end_date->diffInDays(now()) }} dagen
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-sm text-gray-500">Geen diensten die binnenkort verlopen</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
