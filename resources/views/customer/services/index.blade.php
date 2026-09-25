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
                            <a href="{{ route('customer.services.show', $customerService) }}" class="group block bg-slate-50 rounded-xl p-5 ring-1 ring-slate-200 hover:ring-primary-300 hover:bg-slate-100 transition-all">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-heading font-semibold text-slate-900 group-hover:text-primary-700">{{ $customerService->service->title }}</h4>
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Actief</span>
                                        @if($isExpiringSoon)
                                            <span class="group relative inline-flex items-center justify-center" tabindex="0" aria-label="Deze dienst verloopt binnenkort">
                                                <svg class="w-5 h-5 text-amber-500" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                                <span class="absolute bottom-full right-0 mb-2 hidden w-56 group-hover:block group-focus:block bg-slate-900 text-white text-xs rounded-lg px-3 py-2 shadow-lg z-10 text-left">
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
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- All Services Table -->
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 p-6">
            <h3 class="font-heading text-xl font-bold text-slate-900 mb-5">Alle Diensten</h3>
            @if($allServices->count() > 0)
                <div class="space-y-3 sm:hidden">
                    @foreach($allServices as $customerService)
                        <a href="{{ route('customer.services.show', $customerService) }}" class="block rounded-xl bg-slate-50 p-4 ring-1 ring-slate-200">
                            <div class="flex items-start justify-between gap-3"><h4 class="font-semibold text-slate-900">{{ $customerService->service->title }}</h4><span class="shrink-0 rounded-full bg-{{ $customerService->statusLabel['color'] }}-100 px-2.5 py-1 text-xs font-medium text-{{ $customerService->statusLabel['color'] }}-800">{{ $customerService->statusLabel['text'] }}</span></div>
                            <p class="mt-2 text-sm text-slate-500">{{ $customerService->service->short_description }}</p>
                            <div class="mt-4 flex items-center justify-between text-sm">
                                <span class="font-semibold text-slate-900">{{ $customerService->formatted_price }}</span>
                                <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-primary-600" aria-hidden="true">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15.75A3.75 3.75 0 1 0 12 8.25a3.75 3.75 0 0 0 0 7.5Z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12a7.5 7.5 0 0 0-.105-1.25l2.03-1.58-2-3.464-2.48 1a7.5 7.5 0 0 0-2.16-1.25L14.43 2.8h-4l-.36 2.656a7.5 7.5 0 0 0-2.16 1.25l-2.48-1-2 3.464 2.03 1.58a7.5 7.5 0 0 0 0 2.5l-2.03 1.58 2 3.464 2.48-1a7.5 7.5 0 0 0 2.16 1.25l.36 2.656h4l.36-2.656a7.5 7.5 0 0 0 2.16-1.25l2.48 1 2-3.464-2.03-1.58A7.5 7.5 0 0 0 19.5 12Z"/>
                                    </svg>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="-mx-6 hidden overflow-x-auto sm:mx-0 sm:block sm:rounded-b-2xl">
                    <table class="w-full table-fixed min-w-[640px] divide-y divide-slate-100">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="w-[30%] px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Dienst</th>
                                <th class="w-[15%] px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="w-[18%] px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Prijs</th>
                                <th class="w-[14%] px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Startdatum</th>
                                <th class="w-[14%] px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Einddatum</th>
                                <th class="w-[9%] px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actie</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @foreach($allServices as $customerService)
                                <tr role="link" tabindex="0" aria-label="Beheer dienst {{ $customerService->service->title }}" data-href="{{ route('customer.services.show', $customerService) }}" onclick="window.location.href = this.dataset.href" onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); window.location.href = this.dataset.href; }" class="cursor-pointer transition-colors duration-150 hover:bg-slate-50 focus:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-primary-500">
                                    <td class="px-6 py-4">
                                        <div class="truncate text-sm font-medium text-slate-900" title="{{ $customerService->service->title }}">{{ $customerService->service->title }}</div>
                                        <div class="truncate text-sm text-slate-500" title="{{ $customerService->service->short_description }}">{{ $customerService->service->short_description }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $customerService->statusLabel['color'] }}-100 text-{{ $customerService->statusLabel['color'] }}-800">
                                            {{ $customerService->statusLabel['text'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">{{ $customerService->formatted_price }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">{{ $customerService->start_date->format('d-m-Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">{{ $customerService->end_date ? $customerService->end_date->format('d-m-Y') : 'Onbeperkt' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                        <a href="{{ route('customer.services.show', $customerService) }}" tabindex="-1" aria-label="Beheer dienst {{ $customerService->service->title }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 transition-colors duration-150 hover:bg-primary-50 hover:text-primary-600" onclick="event.stopPropagation()">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15.75A3.75 3.75 0 1 0 12 8.25a3.75 3.75 0 0 0 0 7.5Z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12a7.5 7.5 0 0 0-.105-1.25l2.03-1.58-2-3.464-2.48 1a7.5 7.5 0 0 0-2.16-1.25L14.43 2.8h-4l-.36 2.656a7.5 7.5 0 0 0-2.16 1.25l-2.48-1-2 3.464 2.03 1.58a7.5 7.5 0 0 0 0 2.5l-2.03 1.58 2 3.464 2.48-1a7.5 7.5 0 0 0 2.16 1.25l.36 2.656h4l.36-2.656a7.5 7.5 0 0 0 2.16-1.25l2.48 1 2-3.464-2.03-1.58A7.5 7.5 0 0 0 19.5 12Z"/>
                                            </svg>
                                            <span class="sr-only">Dienst beheren</span>
                                        </a>
                                    </td>
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
