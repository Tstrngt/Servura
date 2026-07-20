@extends('layouts.app')

@section('title', "Factuur {$invoice->invoice_number} - Servura Admin")

@section('content')
@include('admin.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $invoice->invoice_number }}</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $invoice->statusLabel['color'] }}-100 text-{{ $invoice->statusLabel['color'] }}-800">
                            {{ $invoice->statusLabel['text'] }}
                        </span>
                    </p>
                </div>
                <div class="flex gap-2">
                    @if($invoice->status === 'concept')
                        <form method="POST" action="{{ route('admin.financial.invoices.mark-sent', $invoice) }}">
                            @csrf
                            <button type="submit" class="btn btn-outline">Markeer als Verzonden</button>
                        </form>
                    @endif
                    @if(in_array($invoice->status, ['concept', 'verzonden', 'vervallen']))
                        <form method="POST" action="{{ route('admin.financial.invoices.mark-paid', $invoice) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">Markeer als Betaald</button>
                        </form>
                    @endif
                    <a href="{{ route('admin.financial.invoices') }}" class="btn btn-outline">Terug</a>
                </div>
            </div>

            <!-- Invoice Details -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Klant</h3>
                        <p class="text-gray-900 font-medium">{{ $invoice->user->name }}</p>
                        @if($invoice->user->company)
                            <p class="text-gray-600">{{ $invoice->user->company }}</p>
                        @endif
                        <p class="text-gray-600 text-sm">{{ $invoice->user->email }}</p>
                        @if($invoice->user->street)
                            <p class="text-gray-600 text-sm mt-1">
                                {{ $invoice->user->street }} {{ $invoice->user->house_number }}<br>
                                {{ $invoice->user->postal_code }} {{ $invoice->user->city }}
                            </p>
                        @endif
                        @if($invoice->user->kvk_number)
                            <p class="text-gray-600 text-sm">KVK: {{ $invoice->user->kvk_number }}</p>
                        @endif
                        @if($invoice->user->vat_number)
                            <p class="text-gray-600 text-sm">BTW: {{ $invoice->user->vat_number }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <div class="space-y-1 text-sm">
                            <p><span class="text-gray-500">Factuurdatum:</span> <span class="text-gray-900">{{ $invoice->invoice_date->format('d-m-Y') }}</span></p>
                            <p><span class="text-gray-500">Vervaldatum:</span> <span class="text-gray-900">{{ $invoice->due_date->format('d-m-Y') }}</span></p>
                            @if($invoice->sent_at)
                                <p><span class="text-gray-500">Verzonden:</span> <span class="text-gray-900">{{ $invoice->sent_at->format('d-m-Y H:i') }}</span></p>
                            @endif
                            @if($invoice->paid_at)
                                <p><span class="text-gray-500">Betaald:</span> <span class="text-gray-900">{{ $invoice->paid_at->format('d-m-Y H:i') }}</span></p>
                            @endif
                        </div>
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
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Stuksprijs</th>
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
                            <td colspan="3" class="px-6 py-3 text-right text-sm font-bold text-gray-900">Totaal</td>
                            <td class="px-6 py-3 text-right text-sm font-bold text-gray-900">€{{ number_format($invoice->total, 2, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if($invoice->notes)
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Opmerkingen</h3>
                    <p class="text-gray-900 text-sm">{{ $invoice->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
