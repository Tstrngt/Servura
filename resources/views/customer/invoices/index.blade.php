@extends('layouts.app')

@section('title', 'Facturen - Servura')

@section('content')
@include('customer.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="max-w-5xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Mijn Facturen</h1>

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

            <div class="bg-white shadow rounded-lg overflow-hidden">
                @if($invoices->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Factuur</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Datum</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vervaldatum</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Bedrag</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($invoices as $invoice)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('customer.invoices.show', $invoice) }}" class="text-primary-600 hover:text-primary-500">{{ $invoice->invoice_number }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $invoice->invoice_date->format('d-m-Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $invoice->due_date->format('d-m-Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-medium">€{{ number_format($invoice->total, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $invoice->statusLabel['color'] }}-100 text-{{ $invoice->statusLabel['color'] }}-800">
                                                {{ $invoice->statusLabel['text'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            @if(in_array($invoice->status, ['verzonden', 'vervallen']))
                                                <form method="POST" action="{{ route('customer.invoices.pay', $invoice) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary text-sm py-1 px-3">Betalen</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($invoices->hasPages())
                        <div class="px-6 py-4 border-t">{{ $invoices->links() }}</div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <h3 class="text-sm font-medium text-gray-900">Geen facturen</h3>
                        <p class="mt-1 text-sm text-gray-500">U heeft nog geen facturen ontvangen.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
