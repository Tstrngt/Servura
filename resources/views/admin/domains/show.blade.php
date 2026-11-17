@extends('layouts.app')

@section('title', $domainRegistration->domain_name . ' - Servura Admin')

@section('content')
@include('admin.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="mx-auto w-full max-w-[1600px] px-4 py-4 sm:px-6 lg:px-8">
        <div class="py-4">
            <a href="{{ route('admin.domains.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-800">← Terug naar domeinen</a>
            <h1 class="mt-2 text-2xl font-bold text-gray-900">{{ $domainRegistration->domain_name }}</h1>
        </div>

        @if(session('success'))<div class="mb-4 rounded-xl bg-green-50 p-4 text-sm text-green-700 ring-1 ring-green-200">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="mb-4 rounded-xl bg-red-50 p-4 text-sm text-red-700 ring-1 ring-red-200">{{ session('error') }}</div>@endif
        @php $label = $domainRegistration->statusLabel; @endphp
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="mb-6">
                    <span class="inline-flex items-center rounded-full bg-{{ $label['color'] }}-50 px-3 py-1 text-sm font-medium text-{{ $label['color'] }}-700 ring-1 ring-inset ring-{{ $label['color'] }}-600/20">{{ $label['text'] }}</span>
                </div>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                    <div>
                        <dt class="text-slate-500">Domein</dt>
                        <dd class="mt-1 font-medium text-slate-900">{{ $domainRegistration->domain_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Type</dt>
                        <dd class="mt-1 font-medium text-slate-900">{{ $domainRegistration->type === 'transfer' ? 'Verhuizing' : 'Registratie' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Provider</dt>
                        <dd class="mt-1 font-medium text-slate-900 uppercase">{{ $domainRegistration->provider }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Klant</dt>
                        <dd class="mt-1 font-medium text-slate-900">
                            @if($domainRegistration->user)
                                <a href="{{ route('admin.customers.show', $domainRegistration->user) }}" class="text-primary-600 hover:text-primary-800">{{ $domainRegistration->user->name }}</a>
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Order</dt>
                        <dd class="mt-1 font-medium text-slate-900">
                            @if($domainRegistration->order)
                                <a href="{{ route('admin.financial.invoices.show', $domainRegistration->order->invoice_id) }}" class="text-primary-600 hover:text-primary-800">{{ $domainRegistration->order->order_number }}</a>
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                    <div>
                        @if($domainRegistration->type === 'transfer')
                            <dt class="text-slate-500">Verhuisprijs</dt>
                            <dd class="mt-1 font-medium text-slate-900">{{ $domainRegistration->transfer_price ? '€ '.number_format((float) $domainRegistration->transfer_price, 2, ',', '.') : '-' }}</dd>
                        @else
                            <dt class="text-slate-500">Registratieprijs</dt>
                            <dd class="mt-1 font-medium text-slate-900">€ {{ number_format((float) $domainRegistration->registration_price, 2, ',', '.') }}</dd>
                        @endif
                    </div>
                    <div>
                        <dt class="text-slate-500">Verlengprijs</dt>
                        <dd class="mt-1 font-medium text-slate-900">{{ $domainRegistration->renewal_price ? '€ '.number_format((float) $domainRegistration->renewal_price, 2, ',', '.') : '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Geregistreerd op</dt>
                        <dd class="mt-1 font-medium text-slate-900">{{ $domainRegistration->registered_at?->format('d-m-Y H:i') ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Verloopt op</dt>
                        <dd class="mt-1 font-medium text-slate-900">{{ $domainRegistration->expires_at?->format('d-m-Y') ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Extern ID</dt>
                        <dd class="mt-1 font-medium text-slate-900">{{ $domainRegistration->external_id ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Automatische verlenging</dt>
                        <dd class="mt-1 font-medium text-slate-900">{{ $domainRegistration->auto_renew ? 'Ja' : 'Nee' }}</dd>
                    </div>
                </dl>

                @if($domainRegistration->error_message && in_array($domainRegistration->status, [\App\Models\DomainRegistration::STATUS_REGISTRATION_FAILED, \App\Models\DomainRegistration::STATUS_TRANSFER_FAILED], true))
                    <div class="mt-6 rounded-lg bg-red-50 p-4 ring-1 ring-red-200">
                        <h3 class="text-sm font-semibold text-red-800">Providerfout</h3>
                        <p class="mt-1 text-sm text-red-700">{{ $domainRegistration->error_message }}</p>
                    </div>
                @elseif($canRetry)
                    <div class="mt-6 rounded-lg bg-amber-50 p-4 ring-1 ring-amber-200">
                        <h3 class="text-sm font-semibold text-amber-800">Betaald, nog niet bij provider</h3>
                        <p class="mt-1 text-sm text-amber-700">De factuur is betaald, maar de domeinactie is nog niet bij de provider uitgevoerd.</p>
                    </div>
                @endif

                @if($canRetry)
                    <form method="POST" action="{{ route('admin.domains.retry', $domainRegistration) }}" class="mt-4 space-y-3" onsubmit="return confirm('De reeds betaalde domeinactie (opnieuw) bij de provider uitvoeren?')">
                        @csrf
                        @if($domainRegistration->type === \App\Models\DomainRegistration::TYPE_TRANSFER)
                            <div><label for="retry_auth_code" class="form-label">Nieuwe verhuiscode</label><input id="retry_auth_code" name="auth_code" type="text" required autocomplete="off" class="form-input mt-1 max-w-md" value="{{ old('auth_code') }}"><p class="mt-1 text-xs text-slate-500">Vraag een actuele verhuiscode op bij de huidige registrar. De bestaande code wordt veilig vervangen.</p></div>
                        @endif
                        <button type="submit" class="btn btn-primary text-sm">{{ in_array($domainRegistration->status, [\App\Models\DomainRegistration::STATUS_REGISTRATION_FAILED, \App\Models\DomainRegistration::STATUS_TRANSFER_FAILED], true) ? 'Opnieuw proberen' : 'Nu bij provider uitvoeren' }}</button>
                    </form>
                @endif
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 h-fit">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Klantenservice</h3>
                @if($domainRegistration->customerService)
                    <dl class="space-y-3 text-sm">
                        <div>
                            <dt class="text-slate-500">Status</dt>
                            <dd class="mt-1 font-medium text-slate-900">{{ $domainRegistration->customerService->statusLabel['text'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Provisioning</dt>
                            <dd class="mt-1 font-medium text-slate-900">{{ $domainRegistration->customerService->provisioning_status ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Fout</dt>
                            <dd class="mt-1 text-slate-700 break-words">{{ $domainRegistration->customerService->provisioning_error ?? '-' }}</dd>
                        </div>
                    </dl>
                @else
                    <p class="text-sm text-slate-500">Geen gekoppelde klantenservice gevonden.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
