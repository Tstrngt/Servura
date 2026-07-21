@extends('layouts.app')

@section('title', 'Offerte Aanmaken - Servura Admin')

@section('content')
@include('admin.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Offerte Aanmaken</h1>
                    <p class="mt-1 text-sm text-gray-600">Maak een offerte aan met producten en/of vrije regels.</p>
                </div>
                <a href="{{ route('admin.financial.quotes') }}" class="text-sm text-gray-500 hover:text-gray-700">Terug</a>
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

            <form method="POST" action="{{ route('admin.financial.quotes.store') }}" id="quoteForm">
                @csrf

                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Klant</label>
                            <select name="user_id" class="form-input w-full" required>
                                <option value="">Selecteer klant...</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">
                                        {{ $customer->name }} {{ $customer->company ? "({$customer->company})" : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Geldig (dagen)</label>
                            <input type="number" name="valid_days" value="30" min="1" max="365" class="form-input w-full">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Voorstel</label>
                            <textarea name="proposal" class="form-input w-full" rows="3" placeholder="Beschrijving van het voorstel..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Klantnotitie <span class="text-xs text-gray-400">(zichtbaar voor klant)</span></label>
                            <textarea name="client_notes" class="form-input w-full" rows="2" placeholder="Wordt getoond aan de klant..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Interne notitie <span class="text-xs text-gray-400">(alleen team)</span></label>
                            <textarea name="internal_notes" class="form-input w-full" rows="2" placeholder="Niet zichtbaar voor klant..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Offerteregels</h3>
                    <p class="text-sm text-gray-500 mb-4">Voeg producten (diensten) toe of typ vrije regels. Producten worden als dienst gekoppeld aan de klant.</p>

                    <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <label class="block text-xs font-medium text-blue-700 mb-1">Product toevoegen uit catalogus</label>
                        <div class="flex gap-2">
                            <select id="product-select" class="form-input flex-1 text-sm">
                                <option value="">Kies een product...</option>
                                @foreach($services as $svc)
                                    <option value="{{ $svc->id }}" data-title="{{ $svc->title }}" data-price="{{ $svc->price }}" data-type="{{ $svc->price_type }}">
                                        {{ $svc->title }} — €{{ number_format($svc->price, 2, ',', '.') }} / {{ $svc->price_type }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" id="add-product-btn" class="btn btn-primary text-sm">Toevoegen</button>
                        </div>
                    </div>

                    <div class="mb-3 grid grid-cols-12 gap-3">
                        <div class="col-span-4"><span class="block text-sm font-medium text-gray-700">Omschrijving</span></div>
                        <div class="col-span-1"><span class="block text-sm font-medium text-gray-700">Aantal</span></div>
                        <div class="col-span-2"><span class="block text-sm font-medium text-gray-700">Stuksprijs</span></div>
                        <div class="col-span-2"><span class="block text-sm font-medium text-gray-700">Korting (%)</span></div>
                        <div class="col-span-2"><span class="block text-sm font-medium text-gray-700">Totaal</span></div>
                        <div class="col-span-1"></div>
                    </div>

                    <div id="quote-lines"></div>

                    <button type="button" id="add-line-btn" class="mt-2 text-sm text-primary-600 hover:text-primary-500 font-medium">
                        + Vrije regel toevoegen
                    </button>

                    <div class="mt-6 border-t pt-4 text-right">
                        <div class="text-sm text-gray-600">Subtotaal: <span class="font-medium" id="calc-subtotal">€0,00</span></div>
                        <div class="text-sm text-gray-600">BTW (21%): <span class="font-medium" id="calc-vat">€0,00</span></div>
                        <div class="text-lg font-bold text-gray-900 mt-1">Totaal: <span id="calc-total">€0,00</span></div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn btn-primary">Offerte Aanmaken</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var lineCount = 0;
    var container = document.getElementById('quote-lines');

    function createLine(description, quantity, unitPrice, serviceId, priceType) {
        var i = lineCount++;
        var row = document.createElement('div');
        row.className = 'grid grid-cols-12 gap-3 mb-3 items-center';
        row.id = 'line-' + i;

        var serviceInput = serviceId ? '<input type="hidden" name="lines[' + i + '][service_id]" value="' + serviceId + '">' : '';
        var badge = serviceId ? '<span class="inline-block ml-1 px-1.5 py-0.5 bg-blue-100 text-blue-700 text-xs rounded">Product</span>' : '';

        row.innerHTML =
            '<div class="col-span-4">' +
                '<input type="text" name="lines[' + i + '][description]" class="form-input w-full text-sm" value="' + (description || '') + '" placeholder="Omschrijving" required>' +
                badge + serviceInput +
            '</div>' +
            '<div class="col-span-1">' +
                '<input type="number" name="lines[' + i + '][quantity]" class="form-input w-full text-sm line-qty" value="' + (quantity || 1) + '" min="1" required>' +
            '</div>' +
            '<div class="col-span-2">' +
                '<input type="number" name="lines[' + i + '][unit_price]" class="form-input w-full text-sm line-price" value="' + (unitPrice || 0) + '" step="0.01" min="0" required>' +
            '</div>' +
            '<div class="col-span-2">' +
                '<input type="number" name="lines[' + i + '][discount]" class="form-input w-full text-sm line-discount" value="0" step="0.01" min="0" max="100" placeholder="0">' +
            '</div>' +
            '<div class="col-span-2 text-sm font-medium line-total">€0,00</div>' +
            '<div class="col-span-1">' +
                '<button type="button" class="remove-line text-red-500 hover:text-red-700" data-line="' + i + '">' +
                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>' +
                '</button>' +
            '</div>';
        container.appendChild(row);
        recalc();
    }

    function recalc() {
        var subtotal = 0;
        container.querySelectorAll('[id^="line-"]').forEach(function(row) {
            var qty = parseFloat(row.querySelector('.line-qty').value) || 0;
            var price = parseFloat(row.querySelector('.line-price').value) || 0;
            var discount = parseFloat(row.querySelector('.line-discount').value) || 0;
            var lineTotal = qty * price;
            var total = discount > 0 ? lineTotal * (1 - (discount / 100)) : lineTotal;
            total = Math.max(0, total);
            subtotal += total;
            row.querySelector('.line-total').textContent = formatEuro(total);
        });
        document.getElementById('calc-subtotal').textContent = formatEuro(subtotal);
        document.getElementById('calc-vat').textContent = formatEuro(subtotal * 0.21);
        document.getElementById('calc-total').textContent = formatEuro(subtotal * 1.21);
    }

    function formatEuro(val) { return '\u20AC' + val.toFixed(2).replace('.', ','); }

    // Add product from catalog
    document.getElementById('add-product-btn').addEventListener('click', function() {
        var sel = document.getElementById('product-select');
        var opt = sel.options[sel.selectedIndex];
        if (!opt.value) return;
        createLine(opt.getAttribute('data-title'), 1, opt.getAttribute('data-price'), opt.value, opt.getAttribute('data-type'));
        sel.selectedIndex = 0;
    });

    // Add free line
    document.getElementById('add-line-btn').addEventListener('click', function() {
        createLine('', 1, 0, null, '-');
    });

    // Remove line
    container.addEventListener('click', function(e) {
        var btn = e.target.closest('.remove-line');
        if (btn) {
            document.getElementById('line-' + btn.getAttribute('data-line')).remove();
            recalc();
        }
    });

    // Recalc on input
    container.addEventListener('input', function(e) {
        if (e.target.classList.contains('line-qty') || e.target.classList.contains('line-price') || e.target.classList.contains('line-discount')) recalc();
    });
});
</script>
@endsection
