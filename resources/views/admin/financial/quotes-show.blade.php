@extends('layouts.app')

@section('title', "Offerte {$quote->quote_number} - Servura Admin")

@section('content')
@include('admin.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif

            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $quote->quote_number }}</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Klant: {{ $quote->user->name }} {{ $quote->user->company ? "({$quote->user->company})" : '' }}
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.financial.quotes.edit', $quote) }}" class="btn btn-outline text-sm">Bewerken</a>
                    <form method="POST" action="{{ route('admin.financial.quotes.destroy', $quote) }}" onsubmit="return confirm('Weet je zeker dat je deze offerte wilt verwijderen?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline text-red-600 border-red-300 hover:bg-red-50 text-sm">Verwijderen</button>
                    </form>
                    <a href="{{ route('admin.financial.quotes') }}" class="text-sm text-gray-500 hover:text-gray-700">Terug</a>
                </div>
            </div>

            <!-- Status & Info -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $quote->statusLabel['color'] }}-100 text-{{ $quote->statusLabel['color'] }}-800">
                        {{ $quote->statusLabel['text'] }}
                    </span>
                    <form method="POST" action="{{ route('admin.financial.quotes.status', $quote) }}" class="flex items-center space-x-2">
                        @csrf
                        <select name="status" class="form-input text-sm">
                            @foreach(\App\Models\Quote::STATUSES as $key => $label)
                                <option value="{{ $key }}" {{ $quote->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-outline text-sm">Wijzig</button>
                    </form>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Offertedatum</span>
                        <div class="font-medium">{{ $quote->quote_date->format('d-m-Y') }}</div>
                    </div>
                    <div>
                        <span class="text-gray-500">Geldig tot</span>
                        <div class="font-medium">{{ $quote->valid_until->format('d-m-Y') }}</div>
                    </div>
                    <div>
                        <span class="text-gray-500">Verzonden op</span>
                        <div class="font-medium">{{ $quote->sent_at ? $quote->sent_at->format('d-m-Y H:i') : '-' }}</div>
                    </div>
                    <div>
                        <span class="text-gray-500">Geaccepteerd op</span>
                        <div class="font-medium">{{ $quote->accepted_at ? $quote->accepted_at->format('d-m-Y H:i') : '-' }}</div>
                    </div>
                    <div>
                        <span class="text-gray-500">Laatst gewijzigd</span>
                        <div class="font-medium">{{ $quote->updated_at->format('d-m-Y H:i') }}</div>
                    </div>
                </div>
            </div>

            <!-- Proposal -->
            @if($quote->proposal)
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Voorstel</h3>
                    <div class="prose prose-sm max-w-none text-gray-700">{!! nl2br(e($quote->proposal)) !!}</div>
                </div>
            @endif

            <!-- Lines Table -->
            <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Omschrijving</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aantal</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Stuksprijs</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Korting (%)</th>
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
                                <td class="px-6 py-4 text-sm text-right">
                                    @if($line->discount > 0)
                                        <span class="text-red-600">{{ number_format($line->discount, 1) }}%</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 text-right font-medium">€{{ number_format($line->total, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="4" class="px-6 py-3 text-sm text-gray-500 text-right">Subtotaal</td>
                            <td class="px-6 py-3 text-sm font-medium text-gray-900 text-right">€{{ number_format($quote->subtotal, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="px-6 py-3 text-sm text-gray-500 text-right">BTW ({{ number_format($quote->vat_percentage, 0) }}%)</td>
                            <td class="px-6 py-3 text-sm font-medium text-gray-900 text-right">€{{ number_format($quote->vat_amount, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="px-6 py-3 text-sm font-bold text-gray-900 text-right">Totaal</td>
                            <td class="px-6 py-3 text-lg font-bold text-gray-900 text-right">€{{ number_format($quote->total, 2, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Notes -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                @if($quote->client_notes)
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Klantnotitie <span class="text-xs text-gray-400">(zichtbaar voor klant)</span></h3>
                        <p class="text-sm text-gray-700">{!! nl2br(e($quote->client_notes)) !!}</p>
                    </div>
                @endif
                @if($quote->internal_notes)
                    <div class="bg-yellow-50 shadow rounded-lg p-6 border border-yellow-200">
                        <h3 class="text-sm font-medium text-yellow-700 mb-2">Interne notitie <span class="text-xs">(niet zichtbaar voor klant)</span></h3>
                        <p class="text-sm text-yellow-800">{!! nl2br(e($quote->internal_notes)) !!}</p>
                    </div>
                @endif
            </div>

            @if($quote->converted_invoice_id)
                <div class="p-4 bg-green-50 rounded-lg border border-green-200 mb-6">
                    <p class="text-sm text-green-700">
                        Omgezet naar factuur:
                        <a href="{{ route('admin.financial.invoices.show', $quote->converted_invoice_id) }}" class="font-medium underline">
                            {{ $quote->convertedInvoice?->invoice_number ?? 'Bekijk factuur' }}
                        </a>
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
