@extends('layouts.app')

@section('title', "Factuur {$invoice->invoice_number} - Servura")

@section('content')
@include('customer.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('info'))
                <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded">
                    {{ session('info') }}
                </div>
            @endif

            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $invoice->invoice_number }}</h1>
                    <p class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $invoice->statusLabel['color'] }}-100 text-{{ $invoice->statusLabel['color'] }}-800">
                            {{ $invoice->statusLabel['text'] }}
                        </span>
                    </p>
                </div>
                <div class="flex gap-2 items-center">
                    @if(in_array($invoice->status, ['verzonden', 'vervallen']))
                        <form method="POST" action="{{ route('customer.invoices.pay', $invoice) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">Nu Betalen</button>
                        </form>
                    @endif
                    <a href="{{ route('customer.invoices.index') }}" class="btn btn-outline">Terug</a>
                </div>
            </div>

            <!-- Invoice Info -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-1 text-sm">
                        <p><span class="text-gray-500">Factuurdatum:</span> <span class="text-gray-900">{{ $invoice->invoice_date->format('d-m-Y') }}</span></p>
                        <p><span class="text-gray-500">Vervaldatum:</span> <span class="text-gray-900">{{ $invoice->due_date->format('d-m-Y') }}</span></p>
                    </div>
                    <div class="text-right space-y-1 text-sm">
                        @if($invoice->paid_at)
                            <p><span class="text-gray-500">Betaald op:</span> <span class="text-green-600 font-medium">{{ $invoice->paid_at->format('d-m-Y H:i') }}</span></p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Invoice Lines -->
            <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Omschrijving</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aantal</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Prijs</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Totaal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($invoice->lines as $line)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $line->description }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 text-right">{{ $line->quantity }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 text-right">€{{ number_format($line->unit_price, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 text-right">€{{ number_format($line->total, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-right text-sm text-gray-500">Subtotaal</td>
                            <td class="px-6 py-3 text-right text-sm font-medium text-gray-900">€{{ number_format($invoice->subtotal, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-right text-sm text-gray-500">BTW ({{ $invoice->vat_percentage }}%)</td>
                            <td class="px-6 py-3 text-right text-sm font-medium text-gray-900">€{{ number_format($invoice->vat_amount, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-right font-bold text-gray-900">Totaal</td>
                            <td class="px-6 py-3 text-right font-bold text-gray-900">€{{ number_format($invoice->total, 2, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if($invoice->notes)
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Opmerkingen</h3>
                    <p class="text-gray-900 text-sm">{{ $invoice->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
