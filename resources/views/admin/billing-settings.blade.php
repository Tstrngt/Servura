@extends('layouts.app')

@section('title', 'Facturatie-instellingen - Servura Admin')

@section('content')
@include('admin.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="mx-auto w-full max-w-[1600px] px-4 py-4 sm:px-6 lg:px-8">
        <div class="py-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Financieel</h1>
                    <p class="mt-1 text-sm text-gray-600">Facturen, transacties en systeeminstellingen.</p>
                </div>
            </div>
        </div>

        @include('admin.financial.partials.financial-nav')

        @if(session('success'))
            <div class="mb-4">
                <div class="rounded-md bg-green-50 p-4">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4">
                <div class="rounded-md bg-red-50 p-4">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[minmax(0,1fr)_400px]">
            <form action="{{ route('admin.financial.billing-settings.update') }}" method="POST" class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                @csrf
                @method('PUT')
                <h2 class="text-lg font-semibold text-slate-900">BTW en betaaltermijnen</h2>
                <div class="mt-6 grid grid-cols-1 gap-x-5 sm:grid-cols-2">
                    <div class="form-group">
                        <label class="form-label" for="default_vat_rate">Standaard BTW-tarief (%)</label>
                        <input class="form-input" id="default_vat_rate" name="default_vat_rate" type="number" step="0.01" min="0" max="100" required value="{{ old('default_vat_rate', $settings['default_vat_rate']) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="business_country">Vestigingsland</label>
                        <select class="form-input" id="business_country" name="business_country">
                            <option value="NL" {{ $settings['business_country'] === 'NL' ? 'selected' : '' }}>Nederland</option>
                            <option value="BE" {{ $settings['business_country'] === 'BE' ? 'selected' : '' }}>België</option>
                            <option value="DE" {{ $settings['business_country'] === 'DE' ? 'selected' : '' }}>Duitsland</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="invoice_due_days">Betaaltermijn (dagen)</label>
                        <input class="form-input" id="invoice_due_days" name="invoice_due_days" type="number" min="1" max="90" required value="{{ old('invoice_due_days', $settings['invoice_due_days']) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="suspension_grace_days">Respijt na vervaldatum (dagen)</label>
                        <input class="form-input" id="suspension_grace_days" name="suspension_grace_days" type="number" min="0" max="90" required value="{{ old('suspension_grace_days', $settings['suspension_grace_days']) }}">
                    </div>
                </div>
                <label class="mt-4 flex items-start gap-3 rounded-xl bg-amber-50 p-4 ring-1 ring-amber-200">
                    <input type="checkbox" name="country_vat_enabled" value="1" {{ old('country_vat_enabled', $settings['country_vat_enabled']) ? 'checked' : '' }} class="mt-1 rounded border-amber-300 text-primary-600">
                    <span><strong class="block text-sm text-amber-950">Landgebonden BTW inschakelen</strong><span class="mt-1 block text-sm leading-relaxed text-amber-800">Past het lokale standaardtarief toe voor EU-landen en 0% buiten de EU. Laat dit uitgeschakeld totdat internationale verkoop en fiscale validatie zijn ingericht.</span></span>
                </label>

                <h2 class="mt-8 border-t border-slate-200 pt-6 text-lg font-semibold text-slate-900">Mollie-betaalprovider</h2>
                <p class="mt-1 text-sm text-slate-600">De API-key wordt versleuteld in de database opgeslagen en is alleen zichtbaar voor de eigenaar.</p>
                <div class="mt-4 grid grid-cols-1 gap-x-5 sm:grid-cols-2">
                    <div class="form-group">
                        <label class="form-label" for="mollie_key">API-key</label>
                        <input class="form-input" id="mollie_key" name="mollie_key" type="password" autocomplete="off" placeholder="••••••" value="{{ old('mollie_key') }}">
                        <p class="mt-1 text-xs text-slate-500">Laat leeg om ongewijzigd te laten.</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="mollie_key_mode">Modus bij nieuwe invoer</label>
                        <select class="form-input" id="mollie_key_mode" name="mollie_key_mode">
                            <option value="test" {{ old('mollie_key_mode', 'test') === 'test' ? 'selected' : '' }}>Test</option>
                            <option value="live" {{ old('mollie_key_mode') === 'live' ? 'selected' : '' }}>Live</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="btn btn-primary">Instellingen opslaan</button>
                </div>
            </form>

            <aside class="h-fit space-y-6">
                <div class="rounded-2xl bg-slate-900 p-6 text-white shadow-xl">
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="text-lg font-semibold">Mollie-status</h2>
                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $mollie['configured'] ? ($mollie['mode'] === 'live' ? 'bg-emerald-400/15 text-emerald-300' : 'bg-amber-400/15 text-amber-300') : 'bg-red-400/15 text-red-300' }}">
                            {{ !$mollie['configured'] ? 'Niet ingesteld' : ($mollie['mode'] === 'live' ? 'Live' : 'Test') }}
                        </span>
                    </div>
                    <p class="mt-4 text-sm leading-relaxed text-slate-300">
                        De API-key wordt versleuteld opgeslagen in de database. Alleen de eigenaar kan deze wijzigen.
                    </p>
                </div>

                <div class="rounded-2xl bg-slate-900 p-6 text-white shadow-xl">
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="text-lg font-semibold">DirectAdmin</h2>
                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $directAdminStatus['configured'] ? 'bg-emerald-400/15 text-emerald-300' : 'bg-red-400/15 text-red-300' }}">
                            {{ $directAdminStatus['configured'] ? 'Geconfigureerd' : 'Niet ingesteld' }}
                        </span>
                    </div>
                    <p class="mt-4 text-sm leading-relaxed text-slate-300">{{ $directAdminStatus['active'] }} actieve koppeling(en), {{ $directAdminStatus['total'] }} totaal. Credentials worden versleuteld opgeslagen.</p>
                    <a href="{{ route('admin.server-connections.index') }}" class="mt-5 inline-flex w-full items-center justify-center rounded-lg bg-white/10 px-4 py-2.5 text-sm font-semibold text-white ring-1 ring-white/15 hover:bg-white/15">Serverkoppelingen beheren</a>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection
