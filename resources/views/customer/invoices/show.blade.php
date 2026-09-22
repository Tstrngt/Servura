@extends('layouts.app')

@section('title', "Factuur {$invoice->invoice_number} - Servura")

@section('content')
@include('customer.partials.topbar')

<div class="bg-slate-50 min-h-screen pt-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 pb-24">
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 rounded-xl p-4 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif
        @if(session('info'))
            <div class="mb-6 bg-primary-50 border border-primary-200 rounded-xl p-4 text-sm text-primary-800">
                {{ session('info') }}
            </div>
        @endif

        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
            <div>
                <h1 class="font-heading text-3xl font-bold text-slate-900">{{ $invoice->invoice_number }}</h1>
                <p class="mt-2">
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
            <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 p-6 mb-6">
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-1 text-sm">
                        <p><span class="text-slate-500">Factuurdatum:</span> <span class="text-slate-900">{{ $invoice->invoice_date->format('d-m-Y') }}</span></p>
                        <p><span class="text-slate-500">Vervaldatum:</span> <span class="text-slate-900">{{ $invoice->due_date->format('d-m-Y') }}</span></p>
                    </div>
                    <div class="text-right space-y-1 text-sm">
                        @if($invoice->paid_at)
                            <p><span class="text-slate-500">Betaald op:</span> <span class="text-emerald-600 font-medium">{{ $invoice->paid_at->format('d-m-Y H:i') }}</span></p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Invoice Lines -->
            <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 overflow-hidden mb-6">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Omschrijving</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Aantal</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Prijs</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Totaal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @foreach($invoice->lines as $line)
                            <tr>
                                <td class="px-6 py-4 text-sm text-slate-900">{{ $line->description }}</td>
                                <td class="px-6 py-4 text-sm text-slate-900 text-right">{{ $line->quantity }}</td>
                                <td class="px-6 py-4 text-sm text-slate-900 text-right">€{{ number_format($line->unit_price, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm text-slate-900 text-right">€{{ number_format($line->total, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-50">
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-right text-sm text-slate-500">Subtotaal</td>
                            <td class="px-6 py-3 text-right text-sm font-medium text-slate-900">€{{ number_format($invoice->subtotal, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-right text-sm text-slate-500">BTW ({{ $invoice->vat_percentage }}%)</td>
                            <td class="px-6 py-3 text-right text-sm font-medium text-slate-900">€{{ number_format($invoice->vat_amount, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-right font-bold text-slate-900">Totaal</td>
                            <td class="px-6 py-3 text-right font-bold text-slate-900">€{{ number_format($invoice->total, 2, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if($invoice->notes)
                <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 p-6">
                    <h3 class="font-heading text-sm font-medium text-slate-900 mb-2">Opmerkingen</h3>
                    <p class="text-slate-700 text-sm">{{ $invoice->notes }}</p>
                </div>
            @endif
    </div>
</div>
@endsection
