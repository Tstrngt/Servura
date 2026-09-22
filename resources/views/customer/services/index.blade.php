@extends('layouts.app')

@section('title', 'Mijn Diensten - Servura')

@section('content')
@include('customer.partials.topbar')

<div class="bg-slate-50 min-h-screen pt-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 pb-24">
        <!-- Page Header -->
        <div class="mb-10">
            <h1 class="font-heading text-3xl font-bold text-slate-900">Mijn Diensten</h1>
            <p class="mt-2 text-lg text-slate-500">Overzicht van je actieve en historische diensten.</p>
        </div>

        <!-- Active Services -->
        @if($activeServices->count() > 0)
            <div class="mb-10">
                <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 p-6">
                    <h3 class="font-heading text-xl font-bold text-slate-900 mb-5">Actieve Diensten</h3>
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($activeServices as $customerService)
                            @php
                                $isExpiringSoon = $expiringSoon->contains('id', $customerService->id);
                            @endphp
                            <div class="bg-slate-50 rounded-xl p-5 ring-1 ring-slate-200">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-heading font-semibold text-slate-900">{{ $customerService->service->title }}</h4>
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Actief</span>
                                        @if($isExpiringSoon)
                                            <span class="group relative inline-flex items-center justify-center">
                                                <svg class="w-5 h-5 text-amber-500" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                                <span class="absolute bottom-full right-0 mb-2 hidden w-56 group-hover:block bg-slate-900 text-white text-xs rounded-lg px-3 py-2 shadow-lg z-10 text-left">
                                                    Deze dienst verloopt op {{ $customerService->end_date->format('d-m-Y') }}. Neem contact op voor verlenging.
                                                    <span class="absolute top-full right-1 -mt-1 border-4 border-transparent border-t-slate-900"></span>
                                                </span>
                                            </span>
                                        @endif
                                    </div>
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
@endsection
