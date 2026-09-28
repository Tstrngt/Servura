@extends('layouts.app')

@section('title', $domain->domain_name . ' - Servura')

@section('content')
@include('customer.partials.topbar')

<div class="bg-slate-50 min-h-screen pt-32">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 pb-24">
        <div class="mb-10">
            <a href="{{ route('customer.domains.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-800">← Terug naar domeinen</a>
            <h1 class="mt-3 font-heading text-3xl font-bold text-slate-900">{{ $domain->domain_name }}</h1>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70">
            @php $label = $domain->statusLabel; @endphp
            <div class="flex items-center gap-3 mb-6">
                <span class="inline-flex items-center rounded-full bg-{{ $label['color'] }}-50 px-3 py-1 text-sm font-medium text-{{ $label['color'] }}-700 ring-1 ring-inset ring-{{ $label['color'] }}-600/20">{{ $label['text'] }}</span>
            </div>

            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                <div>
                    <dt class="text-slate-500">Domein</dt>
                    <dd class="mt-1 font-medium text-slate-900">{{ $domain->domain_name }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Provider</dt>
                    <dd class="mt-1 font-medium text-slate-900 uppercase">{{ $domain->provider }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Registratiedatum</dt>
                    <dd class="mt-1 font-medium text-slate-900">{{ $domain->registered_at?->format('d-m-Y') ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Verloop-/verlengdatum</dt>
                    <dd class="mt-1 font-medium text-slate-900">{{ $domain->expires_at?->format('d-m-Y') ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Automatische verlenging</dt>
                    <dd class="mt-1 font-medium text-slate-900">{{ $domain->auto_renew ? 'Ja' : 'Nee' }}</dd>
                </div>
                @if($domain->error_message)
                    <div class="sm:col-span-2">
                        <dt class="text-slate-500">Melding</dt>
                        <dd class="mt-1 p-3 rounded-lg bg-red-50 text-red-800 text-xs">{{ $domain->error_message }}</dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>
</div>
@endsection
