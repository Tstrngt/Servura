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
                        Klant: {{ $invoice->user->name }} {{ $invoice->user->company ? "({$invoice->user->company})" : '' }}
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.financial.invoices.edit', $invoice) }}" class="btn btn-outline text-sm">Bewerken</a>
                    <form method="POST" action="{{ route('admin.financial.invoices.destroy', $invoice) }}" onsubmit="return confirm('Weet je zeker dat je deze factuur wilt verwijderen?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline text-red-600 border-red-300 hover:bg-red-50 text-sm">Verwijderen</button>
                    </form>
                    <a href="{{ route('admin.financial.invoices') }}" class="text-sm text-gray-500 hover:text-gray-700">Terug</a>
                </div>
            </div>

            <!-- Status & Info -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $invoice->statusLabel['color'] }}-100 text-{{ $invoice->statusLabel['color'] }}-800">
                        {{ $invoice->statusLabel['text'] }}
                    </span>
                    <form method="POST" action="{{ route('admin.financial.invoices.status', $invoice) }}" class="flex items-center space-x-2">
                        @csrf
                        <select name="status" class="form-input text-sm">
                            @foreach(\App\Models\Invoice::STATUSES as $key => $label)
                                <option value="{{ $key }}" {{ $invoice->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-outline text-sm">Wijzig</button>
                    </form>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Factuurdatum</span>
                        <div class="font-medium">{{ $invoice->invoice_date->format('d-m-Y') }}</div>
                    </div>
                    <div>
                        <span class="text-gray-500">Vervaldatum</span>
                        <div class="font-medium">{{ $invoice->due_date->format('d-m-Y') }}</div>
                    </div>
                    <div>
                        <span class="text-gray-500">Verzonden</span>
                        <div class="font-medium">{{ $invoice->sent_at ? $invoice->sent_at->format('d-m-Y H:i') : '-' }}</div>
                    </div>
                    <div>
                        <span class="text-gray-500">Betaald</span>
                        <div class="font-medium">{{ $invoice->paid_at ? $invoice->paid_at->format('d-m-Y H:i') : '-' }}</div>
                    </div>
                </div>
                @if($invoice->quote_id)
                    <div class="mt-4 pt-4 border-t">
                        <p class="text-sm text-gray-600">
                            Voortgekomen uit offerte:
                            <a href="{{ route('admin.financial.quotes.show', $invoice->quote_id) }}" class="text-primary-600 hover:text-primary-500 font-medium">
                                {{ $invoice->quote?->quote_number ?? 'Bekijk offerte' }}
                            </a>
                        </p>
                    </div>
                @endif
            </div>

            <!-- Tabs -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex space-x-8">
                    <button type="button" class="inv-tab whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-primary-500 text-primary-600" data-tab="overview">Overzicht</button>
                    <button type="button" class="inv-tab whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="payments">Betalingen</button>
                    <button type="button" class="inv-tab whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="notes">Notities</button>
                </nav>
            </div>

            <!-- Tab: Overzicht -->
            <div id="tab-overview" class="inv-tab-content">
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
                            <div class="text-3xl font-bold text-gray-900">€{{ number_format($invoice->total, 2, ',', '.') }}</div>
                            <div class="text-sm text-gray-500 mt-1">incl. BTW</div>
                        </div>
                    </div>
                </div>

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
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Opmerkingen (klant)</h3>
                        <p class="text-gray-900 text-sm">{!! nl2br(e($invoice->notes)) !!}</p>
                    </div>
                @endif
            </div>

            <!-- Tab: Betalingen -->
            <div id="tab-payments" class="inv-tab-content hidden">
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Betalingen</h3>
                        @php $totalPaid = $invoice->transactions->where('status', 'voltooid')->sum('amount'); @endphp
                        <div class="text-sm text-gray-600">
                            Betaald: <span class="font-medium text-green-600">€{{ number_format($totalPaid, 2, ',', '.') }}</span>
                            / €{{ number_format($invoice->total, 2, ',', '.') }}
                        </div>
                    </div>

                    @if($invoice->transactions->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200 mb-6">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Datum</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Methode</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Omschrijving</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Bedrag</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($invoice->transactions as $tx)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $tx->transaction_date->format('d-m-Y') }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ \App\Models\Transaction::PAYMENT_METHODS[$tx->payment_method] ?? $tx->payment_method }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $tx->description }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 text-right font-medium">€{{ number_format($tx->amount, 2, ',', '.') }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $tx->statusLabel['color'] }}-100 text-{{ $tx->statusLabel['color'] }}-800">
                                                {{ $tx->statusLabel['text'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-sm text-gray-500 mb-6">Nog geen betalingen geregistreerd.</p>
                    @endif

                    <div class="border-t pt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Handmatig betaling toevoegen</h4>
                        <form method="POST" action="{{ route('admin.financial.invoices.payments.store', $invoice) }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            @csrf
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Bedrag</label>
                                <input type="number" name="amount" step="0.01" min="0.01" class="form-input w-full text-sm" required placeholder="0,00">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Betaalmethode</label>
                                <select name="payment_method" class="form-input w-full text-sm" required>
                                    @foreach(\App\Models\Transaction::PAYMENT_METHODS as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Omschrijving</label>
                                <input type="text" name="description" class="form-input w-full text-sm" placeholder="Optioneel...">
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="btn btn-primary text-sm w-full">Toevoegen</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tab: Notities -->
            <div id="tab-notes" class="inv-tab-content hidden">
                <div class="bg-yellow-50 shadow rounded-lg p-6 border border-yellow-200 mb-6">
                    <h3 class="text-sm font-medium text-yellow-700 mb-3">Interne notities <span class="text-xs">(niet zichtbaar voor klant)</span></h3>
                    @if($invoice->internal_notes)
                        <div class="prose prose-sm max-w-none text-yellow-900 mb-4 whitespace-pre-line">{{ $invoice->internal_notes }}</div>
                    @else
                        <p class="text-sm text-yellow-700 mb-4">Nog geen notities.</p>
                    @endif

                    <form method="POST" action="{{ route('admin.financial.invoices.notes.store', $invoice) }}" class="border-t border-yellow-300 pt-4">
                        @csrf
                        <textarea name="internal_notes" rows="3" class="form-input w-full text-sm mb-2" placeholder="Voeg een notitie toe..." required></textarea>
                        <button type="submit" class="btn btn-outline text-sm">Notitie Opslaan</button>
                    </form>
                </div>

                @if($logs->count() > 0)
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-3">Logboek</h3>
                        <div class="space-y-2">
                            @foreach($logs as $log)
                                <div class="flex items-start text-sm">
                                    <span class="text-gray-400 w-36 flex-shrink-0">{{ $log->created_at->format('d-m-Y H:i') }}</span>
                                    <span class="text-gray-700">{{ $log->description }}</span>
                                    @if($log->performedBy)
                                        <span class="text-gray-400 ml-2">— {{ $log->performedBy->name }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var tabs = document.querySelectorAll('.inv-tab');
    var contents = document.querySelectorAll('.inv-tab-content');
    tabs.forEach(function(tab) {
        tab.addEventListener('click', function() {
            tabs.forEach(function(t) { t.classList.remove('border-primary-500', 'text-primary-600'); t.classList.add('border-transparent', 'text-gray-500'); });
            contents.forEach(function(c) { c.classList.add('hidden'); });
            tab.classList.add('border-primary-500', 'text-primary-600');
            tab.classList.remove('border-transparent', 'text-gray-500');
            document.getElementById('tab-' + tab.getAttribute('data-tab')).classList.remove('hidden');
        });
    });
});
</script>
@endsection
