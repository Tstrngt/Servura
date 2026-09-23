@extends('layouts.app')

@section('title', 'Financieel overzicht - Servura')

@section('content')
@include('customer.partials.topbar')

<div class="min-h-screen bg-slate-50 pt-32">
    <main class="mx-auto max-w-7xl px-4 py-12 pb-24 sm:px-6 lg:px-8">
        <header class="mb-10 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="font-heading text-3xl font-bold text-slate-900">Financieel overzicht</h1>
                <p class="mt-2 text-lg text-slate-500">Offertes, facturen en betalingen op één plek.</p>
            </div>
            @if($openInvoices->isNotEmpty())
                <div class="rounded-xl bg-amber-50 px-4 py-3 text-sm text-amber-900 ring-1 ring-amber-200">
                    <span class="font-semibold">{{ $openInvoices->count() }}</span> openstaande {{ $openInvoices->count() === 1 ? 'factuur' : 'facturen' }} ·
                    <span class="font-semibold">€ {{ number_format($openInvoices->sum('total'), 2, ',', '.') }}</span>
                </div>
            @endif
        </header>

        @foreach(['success' => 'emerald', 'error' => 'rose', 'info' => 'primary'] as $messageType => $color)
            @if(session($messageType))
                <div class="mb-6 rounded-xl border border-{{ $color }}-200 bg-{{ $color }}-50 p-4 text-sm text-{{ $color }}-800">{{ session($messageType) }}</div>
            @endif
        @endforeach
        @if($errors->any())
            <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">{{ $errors->first() }}</div>
        @endif

        @php
            $tabs = [
                'open' => ['label' => 'Openstaand', 'count' => $openInvoices->count()],
                'paid' => ['label' => 'Betaald', 'count' => $paidInvoices->total()],
                'quotes' => ['label' => 'Offertes', 'count' => $quotes->total()],
                'payments' => ['label' => 'Betalingen', 'count' => $transactions->total()],
            ];
        @endphp

        <nav class="mb-8 flex gap-2 overflow-x-auto pb-1" aria-label="Financiële onderdelen">
            @foreach($tabs as $key => $item)
                <a href="{{ route('customer.financial.index', ['tab' => $key]) }}" class="inline-flex shrink-0 items-center gap-2 rounded-xl px-4 py-3 text-sm font-semibold transition-colors {{ $tab === $key ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 ring-1 ring-slate-200 hover:bg-slate-50' }}">
                    {{ $item['label'] }}
                    <span class="rounded-md px-1.5 py-0.5 text-xs {{ $tab === $key ? 'bg-white/10 text-white' : 'bg-slate-100 text-slate-500' }}">{{ $item['count'] }}</span>
                </a>
            @endforeach
        </nav>

        @if($tab === 'open')
            <form action="{{ route('customer.financial.pay') }}" method="POST" x-data="{ selected: [] }" data-turbo="false">
                @csrf
                <section class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/70">
                    <div class="flex flex-col gap-3 border-b border-slate-100 p-6 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="font-heading text-xl font-bold text-slate-900">Openstaande facturen</h2>
                            <p class="mt-1 text-sm text-slate-500">Selecteer één of meerdere facturen en betaal ze in één keer.</p>
                        </div>
                        <button type="submit" :disabled="selected.length === 0" class="btn btn-primary disabled:cursor-not-allowed disabled:opacity-40">
                            <span x-text="selected.length ? selected.length + (selected.length === 1 ? ' factuur betalen' : ' facturen betalen') : 'Selecteer facturen'"></span>
                        </button>
                    </div>
                    @forelse($openInvoices as $invoice)
                        <label class="flex cursor-pointer items-start gap-4 border-b border-slate-100 p-5 transition-colors last:border-0 hover:bg-slate-50 sm:items-center">
                            <input type="checkbox" name="invoice_ids[]" value="{{ $invoice->id }}" x-model="selected" class="mt-1 h-5 w-5 rounded border-slate-300 text-primary-600 focus:ring-primary-500 sm:mt-0">
                            <span class="min-w-0 flex-1">
                                <span class="block font-semibold text-slate-900">{{ $invoice->invoice_number }}</span>
                                <span class="mt-1 block text-sm text-slate-500">Factuurdatum {{ $invoice->invoice_date->format('d-m-Y') }} · vervalt {{ $invoice->due_date->format('d-m-Y') }}</span>
                            </span>
                            <span class="text-right">
                                <span class="block font-heading text-lg font-bold tabular-nums text-slate-900">€ {{ number_format($invoice->total, 2, ',', '.') }}</span>
                                <a href="{{ route('customer.invoices.show', $invoice) }}" class="mt-1 inline-block text-xs font-semibold text-primary-600 hover:text-primary-800" onclick="event.stopPropagation()">Bekijk factuur</a>
                            </span>
                        </label>
                    @empty
                        <div class="px-6 py-16 text-center">
                            <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                            </span>
                            <h3 class="mt-4 font-semibold text-slate-900">Alles is betaald</h3>
                            <p class="mt-1 text-sm text-slate-500">Je hebt momenteel geen openstaande facturen.</p>
                        </div>
                    @endforelse
                </section>
            </form>
        @elseif($tab === 'paid')
            <section class="space-y-3">
                @forelse($paidInvoices as $invoice)
                    <a href="{{ route('customer.invoices.show', $invoice) }}" class="flex flex-col gap-3 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/70 transition hover:ring-primary-200 sm:flex-row sm:items-center sm:justify-between">
                        <div><p class="font-semibold text-slate-900">{{ $invoice->invoice_number }}</p><p class="mt-1 text-sm text-slate-500">Betaald op {{ $invoice->paid_at?->format('d-m-Y') ?? 'onbekend' }}</p></div>
                        <p class="font-heading text-lg font-bold tabular-nums text-slate-900">€ {{ number_format($invoice->total, 2, ',', '.') }}</p>
                    </a>
                @empty
                    <div class="rounded-2xl bg-white p-12 text-center text-sm text-slate-500 ring-1 ring-slate-200/70">Nog geen betaalde facturen.</div>
                @endforelse
                {{ $paidInvoices->appends(['tab' => 'paid'])->links() }}
            </section>
        @elseif($tab === 'quotes')
            <section class="space-y-3">
                @forelse($quotes as $quote)
                    <a href="{{ route('customer.quotes.show', $quote) }}" class="flex flex-col gap-3 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/70 transition hover:ring-primary-200 sm:flex-row sm:items-center sm:justify-between">
                        <div><p class="font-semibold text-slate-900">{{ $quote->quote_number }}</p><p class="mt-1 text-sm text-slate-500">Geldig tot {{ $quote->valid_until->format('d-m-Y') }}</p></div>
                        <div class="text-left sm:text-right"><p class="font-heading text-lg font-bold tabular-nums text-slate-900">€ {{ number_format($quote->total, 2, ',', '.') }}</p><span class="text-xs font-semibold text-slate-500">{{ $quote->statusLabel['text'] }}</span></div>
                    </a>
                @empty
                    <div class="rounded-2xl bg-white p-12 text-center text-sm text-slate-500 ring-1 ring-slate-200/70">Geen offertes gevonden.</div>
                @endforelse
                {{ $quotes->appends(['tab' => 'quotes'])->links() }}
            </section>
        @else
            <section class="space-y-3">
                @forelse($transactions as $transaction)
                    <div class="flex flex-col gap-3 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/70 sm:flex-row sm:items-center sm:justify-between">
                        <div><p class="font-semibold text-slate-900">{{ $transaction->description ?: $transaction->transaction_number }}</p><p class="mt-1 text-sm text-slate-500">{{ $transaction->transaction_date->format('d-m-Y') }} · {{ $transaction->payment_method ? ($transaction::PAYMENT_METHODS[$transaction->payment_method] ?? $transaction->payment_method) : 'Onbekend' }}</p></div>
                        <p class="font-heading text-lg font-bold tabular-nums text-emerald-700">€ {{ number_format($transaction->amount, 2, ',', '.') }}</p>
                    </div>
                @empty
                    <div class="rounded-2xl bg-white p-12 text-center text-sm text-slate-500 ring-1 ring-slate-200/70">Nog geen betalingen geregistreerd.</div>
                @endforelse
                {{ $transactions->appends(['tab' => 'payments'])->links() }}
            </section>
        @endif
    </main>
</div>
@endsection
