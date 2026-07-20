@extends('layouts.app')

@section('title', 'Factuur Aanmaken - Servura Admin')

@section('content')
@include('admin.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Factuur Aanmaken</h1>
                    <p class="mt-1 text-sm text-gray-600">Maak een nieuwe factuur aan voor een klant.</p>
                </div>
                <a href="{{ route('admin.financial.invoices') }}" class="text-sm text-gray-500 hover:text-gray-700">Terug</a>
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

            <form method="POST" action="{{ route('admin.financial.invoices.store') }}" id="invoiceForm">
                @csrf

                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Klant</label>
                            <select name="user_id" class="form-input w-full" required>
                                <option value="">Selecteer klant...</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('user_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} {{ $customer->company ? "({$customer->company})" : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Opmerkingen</label>
                            <textarea name="notes" class="form-input w-full" rows="2">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Factuurregels</h3>

                    <div class="mb-3 grid grid-cols-12 gap-3">
                        <div class="col-span-6"><span class="block text-sm font-medium text-gray-700">Omschrijving</span></div>
                        <div class="col-span-2"><span class="block text-sm font-medium text-gray-700">Aantal</span></div>
                        <div class="col-span-3"><span class="block text-sm font-medium text-gray-700">Stuksprijs</span></div>
                        <div class="col-span-1"></div>
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
                    <button type="submit" class="btn btn-primary">Factuur Aanmaken</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var lineCount = 0;
    var container = document.getElementById('invoice-lines');
    var addBtn = document.getElementById('add-line-btn');

    function createLine() {
        var i = lineCount++;
        var row = document.createElement('div');
        row.className = 'grid grid-cols-12 gap-3 mb-3 items-end';
        row.id = 'line-' + i;
        row.innerHTML =
            '<div class="col-span-6">' +
                '<input type="text" name="lines[' + i + '][description]" class="form-input w-full" placeholder="Omschrijving" required>' +
            '</div>' +
            '<div class="col-span-2">' +
                '<input type="number" name="lines[' + i + '][quantity]" class="form-input w-full line-qty" value="1" min="1" required>' +
            '</div>' +
            '<div class="col-span-3">' +
                '<input type="number" name="lines[' + i + '][unit_price]" class="form-input w-full line-price" value="0" step="0.01" min="0" required>' +
            '</div>' +
            '<div class="col-span-1">' +
                '<button type="button" class="remove-line text-red-500 hover:text-red-700 p-2" data-line="' + i + '">' +
                    '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>' +
                '</button>' +
            '</div>';
        container.appendChild(row);
        recalc();
    }

    function recalc() {
        var subtotal = 0;
        var rows = container.querySelectorAll('[id^="line-"]');
        rows.forEach(function(row) {
            var qty = parseFloat(row.querySelector('.line-qty').value) || 0;
            var price = parseFloat(row.querySelector('.line-price').value) || 0;
            subtotal += qty * price;
        });
        var vat = subtotal * 0.21;
        var total = subtotal + vat;
        document.getElementById('calc-subtotal').textContent = formatEuro(subtotal);
        document.getElementById('calc-vat').textContent = formatEuro(vat);
        document.getElementById('calc-total').textContent = formatEuro(total);
    }

    function formatEuro(val) {
        return '\u20AC' + val.toFixed(2).replace('.', ',');
    }

    addBtn.addEventListener('click', function() {
        createLine();
    });

    container.addEventListener('click', function(e) {
        var btn = e.target.closest('.remove-line');
        if (btn) {
            var lineId = btn.getAttribute('data-line');
            var row = document.getElementById('line-' + lineId);
            if (row && container.children.length > 1) {
                row.remove();
                recalc();
            }
        }
    });

    container.addEventListener('input', function(e) {
        if (e.target.classList.contains('line-qty') || e.target.classList.contains('line-price')) {
            recalc();
        }
    });

    // Start with one line
    createLine();
});
</script>
@endsection
