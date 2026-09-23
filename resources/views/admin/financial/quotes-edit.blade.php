@extends('layouts.app')

@section('title', "Offerte {$quote->quote_number} Bewerken - Servura Admin")

@section('content')
@include('admin.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="mx-auto w-full {{ $ticket ? 'max-w-[1600px]' : 'max-w-4xl' }} py-6 sm:px-6 lg:px-8">
        <div class="{{ $ticket ? 'grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_400px] gap-6 items-start' : '' }}">
        <div class="px-4 py-6 sm:px-0">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $quote->quote_number }} Bewerken</h1>
                    <p class="mt-1 text-sm text-gray-600">Klant: {{ $quote->user->name }}</p>
                </div>
                <a href="{{ route('admin.financial.quotes.show', $quote) }}" class="text-sm text-gray-500 hover:text-gray-700">Annuleren</a>
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

            <form method="POST" action="{{ route('admin.financial.quotes.update', $quote) }}" id="quoteForm">
                @csrf @method('PUT')

                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Geldig (dagen vanaf nu)</label>
                            <input type="number" name="valid_days" value="30" min="1" max="365" class="form-input w-full">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Voorstel</label>
                            <textarea name="proposal" class="form-input w-full" rows="3">{{ old('proposal', $quote->proposal) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Klantnotitie <span class="text-xs text-gray-400">(zichtbaar voor klant)</span></label>
                            <textarea name="client_notes" class="form-input w-full" rows="2">{{ old('client_notes', $quote->client_notes) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Interne notitie <span class="text-xs text-gray-400">(alleen voor team)</span></label>
                            <textarea name="internal_notes" class="form-input w-full" rows="2">{{ old('internal_notes', $quote->internal_notes) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Offerteregels</h3>

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
                    <button type="submit" class="btn btn-primary">Offerte Opslaan</button>
                </div>
            </form>
        </div>

        @if($ticket)
            <aside class="xl:sticky xl:top-6 space-y-6 px-4 py-6 sm:px-0">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center justify-between gap-3">
                            <h3 class="text-lg font-medium text-gray-900">Aanvraag {{ $ticket->ticket_number }}</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $ticket->statusLabel['color'] }}-100 text-{{ $ticket->statusLabel['color'] }}-800">{{ $ticket->statusLabel['text'] }}</span>
                        </div>
                        <p class="mt-1 text-sm text-gray-600">{{ $ticket->title }} — {{ $ticket->user->name }}</p>
                        @if($ticket->customerService?->service)
                            <p class="mt-1 text-sm text-gray-600">Dienst: <span class="font-medium text-gray-900">{{ $ticket->customerService->service->title }}</span></p>
                        @endif

                        @if($ticket->request_details)
                            <div class="mt-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Uitvraag</h4>
                                <ul class="text-sm text-gray-600 space-y-1.5">
                                    @foreach($ticket->request_details as $detail)
                                        <li class="rounded-lg bg-gray-50 px-3 py-2">{{ $detail }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Omschrijving</h4>
                            <div class="max-h-48 overflow-y-auto rounded-lg bg-gray-50 p-3 text-sm text-gray-600">{!! nl2br(e($ticket->description)) !!}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Conversatie</h3>
                        <div class="max-h-96 space-y-4 overflow-y-auto pr-1">
                            @forelse($ticket->replies->where('is_internal', false)->take(-8) as $reply)
                                <div class="rounded-lg bg-gray-50 p-3">
                                    <div class="flex items-center justify-between text-xs text-gray-500">
                                        <span class="font-medium text-gray-700">{{ $reply->user->name }}</span>
                                        <span>{{ $reply->created_at->format('d-m-Y H:i') }}</span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-600">{!! nl2br(e($reply->message)) !!}</p>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Nog geen reacties.</p>
                            @endforelse
                        </div>
                        <a href="{{ route('admin.tickets.show', $ticket) }}" class="mt-4 inline-block text-sm font-medium text-primary-600 hover:text-primary-500">Ticket openen</a>
                    </div>
                </div>
            </aside>
        @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var lineCount = 0;
    var container = document.getElementById('quote-lines');

    function createLine(description, quantity, unitPrice, discount, serviceId, priceType) {
        var i = lineCount++;
        var row = document.createElement('div');
        row.className = 'grid grid-cols-12 gap-3 mb-3 items-center';
        row.id = 'line-' + i;
        var serviceInput = serviceId ? '<input type="hidden" name="lines[' + i + '][service_id]" value="' + serviceId + '">' : '';
        var badge = serviceId ? '<span class="inline-block ml-1 px-1.5 py-0.5 bg-blue-100 text-blue-700 text-xs rounded">Product</span>' : '';
        row.innerHTML =
            '<div class="col-span-4">' +
                '<input type="text" name="lines[' + i + '][description]" class="form-input w-full text-sm" value="' + (description || '').replace(/"/g, '&quot;') + '" required>' +
                badge + serviceInput +
            '</div>' +
            '<div class="col-span-1">' +
                '<input type="number" name="lines[' + i + '][quantity]" class="form-input w-full text-sm line-qty" value="' + (quantity || 1) + '" min="1" required>' +
            '</div>' +
            '<div class="col-span-2">' +
                '<input type="number" name="lines[' + i + '][unit_price]" class="form-input w-full text-sm line-price" value="' + (unitPrice || 0) + '" step="0.01" min="0" required>' +
            '</div>' +
            '<div class="col-span-2">' +
                '<input type="number" name="lines[' + i + '][discount]" class="form-input w-full text-sm line-discount" value="' + (discount || 0) + '" step="0.01" min="0" max="100">' +
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

    document.getElementById('add-product-btn').addEventListener('click', function() {
        var sel = document.getElementById('product-select');
        var opt = sel.options[sel.selectedIndex];
        if (!opt.value) return;
        createLine(opt.getAttribute('data-title'), 1, opt.getAttribute('data-price'), 0, opt.value, opt.getAttribute('data-type'));
        sel.selectedIndex = 0;
    });

    document.getElementById('add-line-btn').addEventListener('click', function() {
        createLine('', 1, 0, 0, null, null);
    });

    container.addEventListener('click', function(e) {
        var btn = e.target.closest('.remove-line');
        if (btn) { document.getElementById('line-' + btn.getAttribute('data-line')).remove(); recalc(); }
    });

    container.addEventListener('input', function(e) {
        if (e.target.classList.contains('line-qty') || e.target.classList.contains('line-price') || e.target.classList.contains('line-discount')) recalc();
    });

    // Load existing lines
    @foreach($quote->lines as $line)
    createLine(@json($line->description), {{ $line->quantity }}, {{ $line->unit_price }}, {{ $line->discount ?? 0 }}, {!! $line->service_id ? $line->service_id : 'null' !!}, @json($line->service?->price_type));
    @endforeach
});
</script>
@endsection
