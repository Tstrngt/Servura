@extends('layouts.app')

@section('title', 'Admin Dashboard - Servura')

@section('content')
<!-- Admin Navigation -->
<nav class="bg-white shadow-sm border-b border-gray-200">
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
<div class="bg-gray-50 min-h-screen">
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
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
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
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
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
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
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
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
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
                </div>
            </div>
        </div>

        <!-- Revenue Stats -->
        <div class="px-4 py-6 sm:px-0">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Maandelijkse Omzet
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        €{{ number_format($stats['monthly_revenue'], 2, ',', '.') }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-indigo-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Jaarlijkse Omzet
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        €{{ number_format($stats['yearly_revenue'], 2, ',', '.') }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-pink-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Ongelezen Berichten
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ $stats['unread_messages'] }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Tables Row -->
        <div class="px-4 py-6 sm:px-0">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Ticket Status Chart -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            Ticket Statussen
                        </h3>
                        <div class="space-y-3">
                            @foreach($ticketStatuses as $status => $count)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $this->getStatusColor($status) }}"></div>
                                        <span class="text-sm text-gray-700">{{ $this->getStatusLabel($status) }}</span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Ticket Priority Chart -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            Ticket Prioriteiten
                        </h3>
                        <div class="space-y-3">
                            @foreach($ticketPriorities as $priority => $count)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $this->getPriorityColor($priority) }}"></div>
                                        <span class="text-sm text-gray-700">{{ $this->getPriorityLabel($priority) }}</span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Tables -->
        <div class="px-4 py-6 sm:px-0">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Tickets -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Recente Tickets
                            </h3>
                            <a href="#" class="text-sm text-primary-600 hover:text-primary-500">
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
                            <a href="#" class="text-sm text-primary-600 hover:text-primary-500">
                                Bekijk alles
                            </a>
                        </div>
                        @if($unreadMessages->count() > 0)
                            <div class="space-y-3">
                                @foreach($unreadMessages as $message)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                                        <div class="flex-1">
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
                                        <div class="ml-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Nieuw
                                            </span>
                                        </div>
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
                            <a href="#" class="text-sm text-primary-600 hover:text-primary-500">
                                Bekijk alles
                            </a>
                        </div>
                        @if($recentCustomers->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentCustomers as $customer)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
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
                                    </div>
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
                            <a href="#" class="text-sm text-primary-600 hover:text-primary-500">
                                Bekijk alles
                            </a>
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

@php
function getStatusColor($status) {
    return match($status) {
        'open' => '#3B82F6',
        'in_progress' => '#EAB308',
        'waiting_for_customer' => '#F97316',
        'resolved' => '#10B981',
        'closed' => '#6B7280',
        default => '#6B7280'
    };
}

function getStatusLabel($status) {
    return match($status) {
        'open' => 'Open',
        'in_progress' => 'In behandeling',
        'waiting_for_customer' => 'Wacht op klant',
        'resolved' => 'Opgelost',
        'closed' => 'Gesloten',
        default => ucfirst($status)
    };
}

function getPriorityColor($priority) {
    return match($priority) {
        'low' => '#6B7280',
        'medium' => '#3B82F6',
        'high' => '#F97316',
        'urgent' => '#EF4444',
        default => '#6B7280'
    };
}

function getPriorityLabel($priority) {
    return match($priority) {
        'low' => 'Laag',
        'medium' => 'Medium',
        'high' => 'Hoog',
        'urgent' => 'Urgent',
        default => ucfirst($priority)
    };
}
@endphp
@endsection
