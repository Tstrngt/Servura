@extends('layouts.app')

@section('title', 'Mijn Domeinen - Servura')

@section('content')
@include('customer.partials.topbar')

<div class="bg-slate-50 min-h-screen pt-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 pb-24">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
            <div>
                <h1 class="font-heading text-3xl font-bold text-slate-900">Mijn Domeinen</h1>
                <p class="mt-2 text-lg text-slate-500">Overzicht van je geregistreerde en verhuizende domeinen.</p>
            </div>
            <div class="shrink-0">
                <a href="{{ route('domains.checker') }}" class="btn btn-primary">
                    Domein zoeken
                </a>
            </div>
        </div>

        <!-- Active Domains -->
        @php
            $activeDomains = $domains->filter(fn ($domain) => in_array($domain->status, [\App\Models\DomainRegistration::STATUS_ACTIVE, \App\Models\DomainRegistration::STATUS_TRANSFER_ACTIVE], true));
        @endphp

        @if($activeDomains->count() > 0)
            <div class="mb-10">
                <div class="rounded-2xl bg-slate-900 p-6 text-white shadow-xl shadow-slate-900/10 ring-1 ring-white/10">
                    <h3 class="mb-5 font-heading text-xl font-bold text-white">Actieve Domeinen</h3>
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($activeDomains as $domain)
                            <a href="{{ route('customer.domains.show', $domain) }}" class="group block rounded-xl bg-white/10 p-5 ring-1 ring-white/15 transition-colors duration-150 hover:bg-white/15 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-400">
                                <div class="mb-2 flex items-center justify-between">
                                    <h4 class="font-heading font-semibold text-white transition-colors duration-150 group-hover:text-primary-300">{{ $domain->domain_name }}</h4>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Actief</span>
                                </div>
                                <p class="mb-3 text-sm text-slate-300">Verloopt op {{ $domain->expires_at?->format('d-m-Y') ?? '-' }}</p>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-slate-300">{{ $domain->auto_renew ? 'Autom. verlenging aan' : 'Autom. verlenging uit' }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- All Domains Table -->
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 p-6">
            <h3 class="font-heading text-xl font-bold text-slate-900 mb-5">Alle Domeinen</h3>
            @if($domains->count() > 0)
                <div class="space-y-3 sm:hidden">
                    @foreach($domains as $domain)
                        @php $label = $domain->statusLabel; @endphp
                        <a href="{{ route('customer.domains.show', $domain) }}" class="block rounded-xl bg-slate-50 p-4 ring-1 ring-slate-200">
                            <div class="flex items-start justify-between gap-3">
                                <h4 class="font-semibold text-slate-900">{{ $domain->domain_name }}</h4>
                                <span class="shrink-0 rounded-full bg-{{ $label['color'] }}-100 px-2.5 py-1 text-xs font-medium text-{{ $label['color'] }}-800">{{ $label['text'] }}</span>
                            </div>
                            <div class="mt-4 grid grid-cols-2 gap-2 text-sm text-slate-500">
                                <div>Geregistreerd: <span class="text-slate-900">{{ $domain->registered_at?->format('d-m-Y') ?? '-' }}</span></div>
                                <div>Verloopt: <span class="text-slate-900">{{ $domain->expires_at?->format('d-m-Y') ?? '-' }}</span></div>
                                <div>Autom. verlenging: <span class="text-slate-900">{{ $domain->auto_renew ? 'Ja' : 'Nee' }}</span></div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="-mx-6 hidden sm:mx-0 sm:block sm:rounded-b-2xl">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Domein</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Geregistreerd</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Verloopt</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Autom. verlenging</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actie</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @foreach($domains as $domain)
                                @php $label = $domain->statusLabel; @endphp
                                <tr role="link" tabindex="0" aria-label="Bekijk domein {{ $domain->domain_name }}" data-href="{{ route('customer.domains.show', $domain) }}" onclick="window.location.href = this.dataset.href" onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); window.location.href = this.dataset.href; }" class="cursor-pointer transition-colors duration-150 hover:bg-slate-50 focus:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-primary-500">
                                    <td class="px-6 py-4">
                                        <div class="truncate pr-4 text-sm font-medium text-slate-900" title="{{ $domain->domain_name }}">{{ $domain->domain_name }}</div>
                                        <div class="truncate pr-4 text-sm text-slate-500">{{ $domain->type === \App\Models\DomainRegistration::TYPE_TRANSFER ? 'Verhuizing' : 'Registratie' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $label['color'] }}-100 text-{{ $label['color'] }}-800">
                                            {{ $label['text'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">{{ $domain->registered_at?->format('d-m-Y') ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">{{ $domain->expires_at?->format('d-m-Y') ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">{{ $domain->auto_renew ? 'Ja' : 'Nee' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                        <a href="{{ route('customer.domains.show', $domain) }}" tabindex="-1" aria-label="Bekijk domein {{ $domain->domain_name }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 transition-colors duration-150 hover:bg-primary-50 hover:text-primary-600" onclick="event.stopPropagation()">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"/>
                                                <circle cx="12" cy="12" r="2.75"/>
                                            </svg>
                                            <span class="sr-only">Domein bekijken</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400 mb-4">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                    </span>
                    <h3 class="text-sm font-medium text-slate-900">Geen domeinen</h3>
                    <p class="mt-1 text-sm text-slate-500">Je hebt nog geen domeinen geregistreerd.</p>
                    <div class="mt-5">
                        <a href="{{ route('domains.checker') }}" class="btn btn-primary">Domein zoeken</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
