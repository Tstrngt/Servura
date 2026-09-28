@extends('layouts.app')

@section('title', 'Mijn Domeinen - Servura')

@section('content')
@include('customer.partials.topbar')

<div class="bg-slate-50 min-h-screen pt-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 pb-24">
        <div class="mb-10">
            <h1 class="font-heading text-3xl font-bold text-slate-900">Mijn Domeinen</h1>
            <p class="mt-2 text-lg text-slate-500">Overzicht van je geregistreerde domeinen.</p>
        </div>

        @if($domains->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
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
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $domain->domain_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php $label = $domain->statusLabel; @endphp
                                        <span class="inline-flex items-center rounded-full bg-{{ $label['color'] }}-50 px-2.5 py-0.5 text-xs font-medium text-{{ $label['color'] }}-700 ring-1 ring-inset ring-{{ $label['color'] }}-600/20">{{ $label['text'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $domain->registered_at?->format('d-m-Y') ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $domain->expires_at?->format('d-m-Y') ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $domain->auto_renew ? 'Ja' : 'Nee' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                        <a href="{{ route('customer.domains.show', $domain) }}" class="text-primary-600 hover:text-primary-800 font-medium">Details</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="rounded-2xl bg-white p-8 ring-1 ring-slate-200 text-center">
                <p class="text-slate-600">Je hebt nog geen domeinen geregistreerd.</p>
                <a href="{{ route('domains.checker') }}" class="mt-4 inline-flex items-center text-primary-600 hover:text-primary-800 font-medium">Domein zoeken</a>
            </div>
        @endif
    </div>
</div>
@endsection
