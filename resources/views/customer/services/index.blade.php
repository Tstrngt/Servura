@extends('layouts.app')

@section('title', 'Mijn Diensten - Servura')

@section('content')
@include('customer.partials.topbar')

<div class="bg-slate-50 min-h-screen pt-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 pb-24">
        <!-- Page Header -->
        <div class="mb-10">
            <h1 class="font-heading text-3xl font-bold text-slate-900">Mijn Diensten</h1>
            <p class="mt-2 text-lg text-slate-500">Overzicht van al uw diensten, kosten en contractinformatie.</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-5 mb-10">
            <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 p-5 flex items-center gap-4">
                <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary-600 ring-1 ring-primary-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </span>
                <div>
                    <p class="text-sm font-medium text-slate-500">Totaal Diensten</p>
                    <p class="text-2xl font-bold text-slate-900 font-heading">{{ $stats['total_services'] }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 p-5 flex items-center gap-4">
                <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                <div>
                    <p class="text-sm font-medium text-slate-500">Actieve Diensten</p>
                    <p class="text-2xl font-bold text-slate-900 font-heading">{{ $stats['active_services'] }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 p-5 flex items-center gap-4">
                <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600 ring-1 ring-amber-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                <div>
                    <p class="text-sm font-medium text-slate-500">Maandelijkse Kosten</p>
                    <p class="text-2xl font-bold text-slate-900 font-heading">€{{ number_format($stats['monthly_cost'], 2, ',', '.') }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 p-5 flex items-center gap-4">
                <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 ring-1 ring-indigo-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                <div>
                    <p class="text-sm font-medium text-slate-500">Jaarlijkse Kosten</p>
                    <p class="text-2xl font-bold text-slate-900 font-heading">€{{ number_format($stats['yearly_cost'], 2, ',', '.') }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 p-5 flex items-center gap-4">
                <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-600 ring-1 ring-rose-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </span>
                <div>
                    <p class="text-sm font-medium text-slate-500">Open Tickets</p>
                    <p class="text-2xl font-bold text-slate-900 font-heading">{{ $stats['open_tickets'] }}</p>
                </div>
            </div>
        </div>

        <!-- Active Services -->
        @if($activeServices->count() > 0)
            <div class="mb-10">
                <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 p-6">
                    <h3 class="font-heading text-xl font-bold text-slate-900 mb-5">Actieve Diensten</h3>
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($activeServices as $customerService)
                            <div class="bg-slate-50 rounded-xl p-5 ring-1 ring-slate-200">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-heading font-semibold text-slate-900">{{ $customerService->service->title }}</h4>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Actief</span>
                                </div>
                                <p class="text-sm text-slate-600 mb-3">{{ $customerService->service->short_description }}</p>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-slate-500">{{ $customerService->formatted_price }}</span>
                                    @if($customerService->end_date)
                                        <span class="text-slate-500">t/m {{ $customerService->end_date->format('d-m-Y') }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Services Expiring Soon -->
        @if($expiringSoon->count() > 0)
            <div class="mb-10">
                <div class="bg-amber-50 border-l-4 border-amber-400 rounded-r-xl p-5">
                    <div class="flex gap-3">
                        <div class="shrink-0 pt-0.5">
                            <svg class="h-5 w-5 text-amber-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-heading text-sm font-semibold text-amber-900">Diensten die binnenkort verlopen</h3>
                            <ul class="mt-2 text-sm text-amber-800 list-disc list-inside space-y-1">
                                @foreach($expiringSoon as $customerService)
                                    <li>{{ $customerService->service->title }} - verloopt op {{ $customerService->end_date->format('d-m-Y') }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- All Services Table -->
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 p-6">
            <h3 class="font-heading text-xl font-bold text-slate-900 mb-5">Alle Diensten</h3>
                    @if($allServices->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Dienst</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Prijs</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Startdatum</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Einddatum</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-100">
                                    @foreach($allServices as $customerService)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-slate-900">{{ $customerService->service->title }}</div>
                                                <div class="text-sm text-slate-500">{{ $customerService->service->short_description }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $customerService->statusLabel['color'] }}-100 text-{{ $customerService->statusLabel['color'] }}-800">
                                                    {{ $customerService->statusLabel['text'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">{{ $customerService->formatted_price }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">{{ $customerService->start_date->format('d-m-Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">{{ $customerService->end_date ? $customerService->end_date->format('d-m-Y') : 'Onbeperkt' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $allServices->links() }}
                        </div>
                @else
                    <div class="text-center py-12">
                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400 mb-4">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </span>
                        <h3 class="text-sm font-medium text-slate-900">Geen diensten</h3>
                        <p class="mt-1 text-sm text-slate-500">Je hebt nog geen diensten afgenomen.</p>
                        <div class="mt-5">
                            <a href="{{ route('contact') }}" class="btn btn-primary">Neem contact op</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
