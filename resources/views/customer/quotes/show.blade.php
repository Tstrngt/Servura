@extends('layouts.app')

@section('title', "Offerte {$quote->quote_number} - Servura")

@section('content')
@include('customer.partials.topbar')

<div class="bg-slate-50 min-h-screen pt-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 pb-24">
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 rounded-xl p-4 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-rose-50 border border-rose-200 rounded-xl p-4 text-sm text-rose-800">{{ session('error') }}</div>
        @endif

        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                <div>
                    <h1 class="font-heading text-3xl font-bold text-slate-900">{{ $quote->quote_number }}</h1>
                    <p class="mt-1 text-lg text-slate-500">Offertedatum: {{ $quote->quote_date->format('d-m-Y') }} • Geldig tot: {{ $quote->valid_until->format('d-m-Y') }}</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $quote->statusLabel['color'] }}-100 text-{{ $quote->statusLabel['color'] }}-800">
                    {{ $quote->statusLabel['text'] }}
                </span>
            </div>

            @if($quote->notes)
                <div class="mb-6 p-4 bg-primary-50 rounded-xl border border-primary-200">
                    <p class="text-sm text-primary-800">{{ $quote->notes }}</p>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 overflow-hidden mb-6">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Omschrijving</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Aantal</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Stuksprijs</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Totaal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @foreach($quote->lines as $line)
                            <tr>
                                <td class="px-6 py-4 text-sm text-slate-900">
                                    {{ $line->description }}
                                    @if($line->service_id)
                                        <span class="ml-2 inline-flex px-2 py-0.5 text-xs bg-primary-100 text-primary-700 rounded">Product</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-900 text-right">{{ $line->quantity }}</td>
                                <td class="px-6 py-4 text-sm text-slate-900 text-right">€{{ number_format($line->unit_price, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm text-slate-900 text-right font-medium">€{{ number_format($line->total, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-50">
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-sm text-slate-500 text-right">Subtotaal</td>
                            <td class="px-6 py-3 text-sm font-medium text-slate-900 text-right">€{{ number_format($quote->subtotal, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-sm text-slate-500 text-right">BTW ({{ number_format($quote->vat_percentage, 0) }}%)</td>
                            <td class="px-6 py-3 text-sm font-medium text-slate-900 text-right">€{{ number_format($quote->vat_amount, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-sm font-bold text-slate-900 text-right">Totaal</td>
                            <td class="px-6 py-3 text-lg font-bold text-slate-900 text-right">€{{ number_format($quote->total, 2, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if($quote->status === 'verzonden')
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 p-6 bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70">
                    <p class="text-sm text-slate-600">Ga je akkoord met deze offerte? Na akkoord wordt de betaling direct gestart.</p>
                    <div class="flex space-x-3">
                        <form method="POST" action="{{ route('customer.quotes.reject', $quote) }}">
                            @csrf
                            <button type="submit" class="btn btn-outline text-red-600 border-red-300 hover:bg-red-50" onclick="return confirm('Weet je zeker dat je deze offerte wilt afwijzen?')">Afwijzen</button>
                        </form>
                        <form method="POST" action="{{ route('customer.quotes.accept', $quote) }}" data-turbo="false">
                            @csrf
                            <button type="submit" class="btn btn-primary">Akkoord & Betalen</button>
                        </form>
                    </div>
                </div>
            @elseif($quote->status === 'geaccepteerd')
                <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-200">
                    <p class="text-sm text-emerald-700">Deze offerte is geaccepteerd en omgezet naar een factuur.</p>
                </div>
            @elseif($quote->status === 'afgewezen')
                <div class="p-4 bg-rose-50 rounded-xl border border-rose-200">
                    <p class="text-sm text-rose-700">Deze offerte is afgewezen.</p>
                </div>
            @endif
    </div>
</div>
@endsection
