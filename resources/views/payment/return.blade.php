@extends('layouts.app')

@section('title', match ($status) {
    'success' => 'Betaling geslaagd - Servura',
    'failed' => 'Betaling mislukt - Servura',
    default => 'Betaling verwerken - Servura',
})

@php
    $hasBatch = isset($paymentBatch);
    $detailRoute = $hasBatch
        ? route('customer.financial.index')
        : route('customer.invoices.show', $invoice);
@endphp

@section('content')
<div class="min-h-[60vh] flex items-center justify-center bg-slate-50 px-4 py-16 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200 text-center">
            @if($status === 'success')
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100">
                    <svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h1 class="mt-6 font-heading text-2xl font-bold text-slate-900">Betaling geslaagd</h1>
                <p class="mt-2 text-slate-600">We hebben uw betaling ontvangen. Uw bestelling wordt nu verwerkt.</p>
                <a href="{{ $detailRoute }}" class="btn btn-primary mt-6 inline-flex justify-center w-full">Bekijk overzicht</a>

            @elseif($status === 'failed')
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100">
                    <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h1 class="mt-6 font-heading text-2xl font-bold text-slate-900">Betaling mislukt of geannuleerd</h1>
                <p class="mt-2 text-slate-600">De betaling is niet afgerond. U kunt het opnieuw proberen via uw factuur of betaaloverzicht.</p>
                <a href="{{ $detailRoute }}" class="btn btn-primary mt-6 inline-flex justify-center w-full">Opnieuw betalen</a>

            @else
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-amber-100">
                    <svg class="h-8 w-8 text-amber-600 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </div>
                <h1 class="mt-6 font-heading text-2xl font-bold text-slate-900">Betaling verwerken</h1>
                <p class="mt-2 text-slate-600">We wachten op de definitieve bevestiging van Mollie. Dit duurt meestal een paar seconden.</p>
                <p class="mt-4 text-sm text-slate-500">Deze pagina hoeft u niet te vernieuwen. U ontvangt een e-mail zodra de betaling is bevestigd.</p>
                <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary mt-6 inline-flex justify-center w-full">Naar dashboard</a>
            @endif
        </div>
    </div>
</div>
@endsection
