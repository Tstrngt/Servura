@extends('layouts.app')

@section('title', "Factuur {$invoice->invoice_number} Bewerken - Servura Admin")

@section('content')
@include('admin.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $invoice->invoice_number }} Bewerken</h1>
                    <p class="mt-1 text-sm text-gray-600">Klant: {{ $invoice->user->name }}</p>
                </div>
                <a href="{{ route('admin.financial.invoices.show', $invoice) }}" class="text-sm text-gray-500 hover:text-gray-700">Annuleren</a>
            </div>

            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.financial.invoices.update', $invoice) }}">
                @csrf @method('PUT')

                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Opmerkingen (klant)</label>
                            <textarea name="notes" class="form-input w-full" rows="2">{{ old('notes', $invoice->notes) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Interne notitie <span class="text-xs text-gray-400">(alleen team)</span></label>
                            <textarea name="internal_notes" class="form-input w-full" rows="2">{{ old('internal_notes', $invoice->internal_notes) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Factuurregels</h3>

                    <div class="mb-3 grid grid-cols-12 gap-3">
                        <div class="col-span-5"><span class="block text-sm font-medium text-gray-700">Omschrijving</span></div>
                        <div class="col-span-1"><span class="block text-sm font-medium text-gray-700">Aantal</span></div>
                        <div class="col-span-2"><span class="block text-sm font-medium text-gray-700">Stuksprijs</span></div>
                        <div class="col-span-2"><span class="block text-sm font-medium text-gray-700">Totaal</span></div>
                        <div class="col-span-2"></div>
                    </div>

                    <div id="invoice-lines"></div>

                    <button type="button" id="add-line-btn" class="mt-2 text-sm text-primary-600 hover:text-primary-500 font-medium">
                        + Regel toevoegen
                    </button>

                    <div class="mt-6 border-t pt-4 text-right">
                        <div class="text-sm text-gray-600">Subtotaal: <span class="font-medium" id="calc-subtotal">€0,00</span></div>
                        <div class="text-sm text-gray-600">BTW (21%): <span class="font-medium" id="calc-vat">€0,00</span></div>
                        <div class="text-lg font-bold text-gray-900 mt-1">Totaal: <span id="calc-total">€0,00</span></div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn btn-primary">Factuur Opslaan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var lineCount = 0;
    var container = document.getElementById('invoice-lines');

    function createLine(description, quantity, unitPrice, vatRate, discount, reference, periodStart, periodEnd) {
        var i = lineCount++;
        var row = document.createElement('div');
        row.className = 'grid grid-cols-12 gap-3 mb-4 items-end rounded-xl bg-slate-50 p-3 ring-1 ring-slate-200';
        row.id = 'line-' + i;
        row.innerHTML =
            '<div class="col-span-5"><input type="text" name="lines[' + i + '][description]" class="form-input w-full text-sm" value="' + (description || '').replace(/"/g, '&quot;') + '" required></div>' +
            '<div class="col-span-1"><input type="number" name="lines[' + i + '][quantity]" class="form-input w-full text-sm line-qty" value="' + (quantity || 1) + '" min="1" required></div>' +
            '<div class="col-span-2"><input type="number" name="lines[' + i + '][unit_price]" class="form-input w-full text-sm line-price" value="' + (unitPrice || 0) + '" step="0.01" {{ $invoice->document_type === 'credit' ? 'max="0"' : 'min="0"' }} required></div>' +
            '<div class="col-span-2 text-sm font-medium line-total">€0,00</div>' +
            '<div class="col-span-2"><button type="button" class="remove-line text-red-500 hover:text-red-700" data-line="' + i + '">Verwijder</button></div>' +
            '<div class="col-span-3"><label class="text-xs text-slate-500">Dienst / domein</label><input type="text" name="lines[' + i + '][service_reference]" class="form-input w-full" value="' + (reference || '').replace(/&/g, '&amp;').replace(/"/g, '&quot;') + '"></div>' +
            '<div class="col-span-2"><label class="text-xs text-slate-500">Periode vanaf</label><input type="date" name="lines[' + i + '][period_start]" class="form-input w-full" value="' + (periodStart || '') + '"></div>' +
            '<div class="col-span-2"><label class="text-xs text-slate-500">Periode t/m</label><input type="date" name="lines[' + i + '][period_end]" class="form-input w-full" value="' + (periodEnd || '') + '"></div>' +
            '<div class="col-span-2"><label class="text-xs text-slate-500">Btw %</label><input type="number" name="lines[' + i + '][vat_percentage]" class="form-input w-full line-vat" value="' + (vatRate ?? 21) + '" step="0.01" min="0" max="100"></div>' +
            '<div class="col-span-2"><label class="text-xs text-slate-500">Korting excl.</label><input type="number" name="lines[' + i + '][discount_amount]" class="form-input w-full line-discount" value="' + (discount || 0) + '" step="0.01" {{ $invoice->document_type === 'credit' ? 'max="0"' : 'min="0"' }}></div>';
        container.appendChild(row);
        recalc();
    }

    function recalc() {
        var subtotal = 0, discount = 0, vat = 0;
        container.querySelectorAll('[id^="line-"]').forEach(function(row) {
            var qty = parseFloat(row.querySelector('.line-qty').value) || 0;
            var price = parseFloat(row.querySelector('.line-price').value) || 0;
            var lineDiscount = parseFloat(row.querySelector('.line-discount').value) || 0;
            var rate = parseFloat(row.querySelector('.line-vat').value) || 0;
            var total = qty * price;
            subtotal += total; discount += lineDiscount; vat += Math.round((total - lineDiscount) * rate) / 100;
            row.querySelector('.line-total').textContent = formatEuro(total - lineDiscount);
        });
        document.getElementById('calc-subtotal').textContent = formatEuro(subtotal);
        document.getElementById('calc-vat').textContent = formatEuro(vat);
        document.getElementById('calc-total').textContent = formatEuro(subtotal - discount + vat);
    }

    function formatEuro(val) { return '\u20AC' + val.toFixed(2).replace('.', ','); }

    document.getElementById('add-line-btn').addEventListener('click', function() { createLine('', 1, 0, 21, 0, '', '', ''); });
    container.addEventListener('click', function(e) { var btn = e.target.closest('.remove-line'); if (btn && container.children.length > 1) { document.getElementById('line-' + btn.getAttribute('data-line')).remove(); recalc(); } });
    container.addEventListener('input', function(e) { if (e.target.matches('.line-qty, .line-price, .line-vat, .line-discount')) recalc(); });

    @foreach($invoice->lines as $line)
    createLine(@json($line->description), {{ $line->quantity }}, {{ $line->unit_price }}, {{ $line->vat_percentage ?? $invoice->vat_percentage }}, {{ $line->discount_amount ?? 0 }}, @json($line->service_reference), @json($line->period_start?->format('Y-m-d')), @json($line->period_end?->format('Y-m-d')));
    @endforeach
});
</script>
@endsection
