@extends('layouts.app')

@section('title', "Offerte {$quote->quote_number} - Servura")

@section('content')
@include('customer.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">{{ session('error') }}</div>
            @endif

            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $quote->quote_number }}</h1>
                    <p class="mt-1 text-sm text-gray-600">Offertedatum: {{ $quote->quote_date->format('d-m-Y') }} • Geldig tot: {{ $quote->valid_until->format('d-m-Y') }}</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $quote->statusLabel['color'] }}-100 text-{{ $quote->statusLabel['color'] }}-800">
                    {{ $quote->statusLabel['text'] }}
                </span>
            </div>

            @if($quote->notes)
                <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-sm text-blue-700">{{ $quote->notes }}</p>
                </div>
            @endif

            <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Omschrijving</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aantal</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Stuksprijs</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Totaal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($quote->lines as $line)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $line->description }}
                                    @if($line->service_id)
                                        <span class="ml-2 inline-flex px-2 py-0.5 text-xs bg-blue-100 text-blue-700 rounded">Product</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 text-right">{{ $line->quantity }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 text-right">€{{ number_format($line->unit_price, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 text-right font-medium">€{{ number_format($line->total, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-sm text-gray-500 text-right">Subtotaal</td>
                            <td class="px-6 py-3 text-sm font-medium text-gray-900 text-right">€{{ number_format($quote->subtotal, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-sm text-gray-500 text-right">BTW ({{ number_format($quote->vat_percentage, 0) }}%)</td>
                            <td class="px-6 py-3 text-sm font-medium text-gray-900 text-right">€{{ number_format($quote->vat_amount, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-sm font-bold text-gray-900 text-right">Totaal</td>
                            <td class="px-6 py-3 text-lg font-bold text-gray-900 text-right">€{{ number_format($quote->total, 2, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if($quote->status === 'verzonden')
                <div class="flex justify-between items-center p-6 bg-white rounded-lg shadow">
                    <p class="text-sm text-gray-600">Ga je akkoord met deze offerte? Na akkoord wordt de betaling direct gestart.</p>
                    <div class="flex space-x-3">
                        <form method="POST" action="{{ route('customer.quotes.reject', $quote) }}">
                            @csrf
                            <button type="submit" class="btn btn-outline text-red-600 border-red-300 hover:bg-red-50" onclick="return confirm('Weet je zeker dat je deze offerte wilt afwijzen?')">Afwijzen</button>
                        </form>
                        <form method="POST" action="{{ route('customer.quotes.accept', $quote) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">Akkoord & Betalen</button>
                        </form>
                    </div>
                </div>
            @elseif($quote->status === 'geaccepteerd')
                <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                    <p class="text-sm text-green-700">Deze offerte is geaccepteerd en omgezet naar een factuur.</p>
                </div>
            @elseif($quote->status === 'afgewezen')
                <div class="p-4 bg-red-50 rounded-lg border border-red-200">
                    <p class="text-sm text-red-700">Deze offerte is afgewezen.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
