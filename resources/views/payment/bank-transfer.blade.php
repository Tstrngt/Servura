@extends('layouts.app')

@section('title', 'Bankoverschrijving - Servura')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center bg-slate-50 px-4 py-16 sm:px-6 lg:px-8">
    <div class="w-full max-w-lg">
        <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-primary-100">
                <svg class="h-8 w-8 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-6V3m0 11v3m4-11v3M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </div>

            <h1 class="mt-6 text-center font-heading text-2xl font-bold text-slate-900">Bankoverschrijving voltooien</h1>
            <p class="mt-2 text-center text-slate-600">Gebruik onderstaande gegevens om het bedrag over te maken. Zodra de betaling bij ons binnen is, wordt uw bestelling geactiveerd.</p>

            <div class="mt-6 rounded-xl bg-slate-50 p-6 ring-1 ring-slate-200">
                <dl class="space-y-4 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">Factuur</dt>
                        <dd class="font-medium text-slate-900">{{ $invoice->invoice_number }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">Te betalen</dt>
                        <dd class="font-medium text-slate-900">€ {{ number_format((float) $invoice->total, 2, ',', '.') }}</dd>
                    </div>
                    @if($details->bankName ?? null)
                        <div class="flex justify-between gap-4">
                            <dt class="text-slate-500">Bank</dt>
                            <dd class="font-medium text-slate-900">{{ $details->bankName }}</dd>
                        </div>
                    @endif
                    @if($details->bankAccount ?? null)
                        <div class="flex justify-between gap-4">
                            <dt class="text-slate-500">Rekeningnummer</dt>
                            <dd class="font-medium text-slate-900">{{ $details->bankAccount }}</dd>
                        </div>
                    @endif
                    @if($details->bankBic ?? null)
                        <div class="flex justify-between gap-4">
                            <dt class="text-slate-500">BIC</dt>
                            <dd class="font-medium text-slate-900">{{ $details->bankBic }}</dd>
                        </div>
                    @endif
                    @if($details->transferReference ?? null)
                        <div class="flex flex-col gap-1 sm:flex-row sm:justify-between">
                            <dt class="text-slate-500">Betalingskenmerk</dt>
                            <dd class="break-all font-mono font-semibold text-slate-900">{{ $details->transferReference }}</dd>
                        </div>
                    @endif
                    @if($details->creditorIdentifier ?? null)
                        <div class="flex justify-between gap-4">
                            <dt class="text-slate-500">Crediteur identificatie</dt>
                            <dd class="font-medium text-slate-900">{{ $details->creditorIdentifier }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <p class="mt-4 text-center text-xs text-slate-500">U ontvangt een bevestiging zodra de betaling is verwerkt. Dit kan één tot drie werkdagen duren.</p>

            <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary mt-6 inline-flex w-full justify-center">Naar dashboard</a>
        </div>
    </div>
</div>
@endsection
