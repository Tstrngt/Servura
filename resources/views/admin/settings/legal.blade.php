@extends('layouts.app')

@section('title', 'Juridische instellingen - Servura Admin')

@section('content')
@include('admin.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="mx-auto w-full max-w-[1600px] px-4 py-4 sm:px-6 lg:px-8">
        <div class="py-4">
            <h1 class="text-2xl font-bold text-gray-900">Instellingen</h1>
            <p class="mt-1 text-sm text-gray-600">Juridische documenten, bewaartermijnen en hostingwaarden beheren.</p>
        </div>

        @include('admin.partials.settings-nav')

        @if(session('success'))
            <div class="mb-4"><div class="rounded-md bg-green-50 p-4"><p class="text-sm text-green-700">{{ session('success') }}</p></div></div>
        @endif
        @if(session('error'))
            <div class="mb-4"><div class="rounded-md bg-red-50 p-4"><p class="text-sm text-red-700">{{ session('error') }}</p></div></div>
        @endif
        @if($errors->any())
            <div class="mb-4"><div class="rounded-md bg-red-50 p-4">
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div></div>
        @endif

        <form action="{{ route('admin.settings.legal.update') }}" method="POST" class="max-w-4xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            @csrf
            @method('PUT')

            <h2 class="text-lg font-semibold text-slate-900">Documentversies</h2>
            <p class="mt-1 text-sm text-slate-600">Wordt getoond onderaan juridische pagina's en gebruikt bij het loggen van akkoordverklaringen.</p>
            <div class="mt-6 grid grid-cols-1 gap-x-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach([
                    ['legal_terms_version', 'legal_terms_effective_date', 'Algemene voorwaarden'],
                    ['legal_privacy_version', 'legal_privacy_effective_date', 'Privacyverklaring'],
                    ['legal_cookies_version', 'legal_cookies_effective_date', 'Cookieverklaring'],
                    ['legal_hosting_terms_version', 'legal_hosting_terms_effective_date', 'Hostingvoorwaarden'],
                    ['legal_acceptable_use_version', 'legal_acceptable_use_effective_date', 'Acceptable Use Policy'],
                ] as [$versionKey, $dateKey, $label])
                    <div class="form-group">
                        <label class="form-label" for="{{ $versionKey }}">{{ $label }} – versie</label>
                        <input class="form-input" id="{{ $versionKey }}" name="{{ $versionKey }}" type="text" value="{{ old($versionKey, $settings[$versionKey]) }}" placeholder="bijv. 1.0">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="{{ $dateKey }}">{{ $label }} – ingangsdatum</label>
                        <input class="form-input" id="{{ $dateKey }}" name="{{ $dateKey }}" type="text" value="{{ old($dateKey, $settings[$dateKey]) }}" placeholder="bijv. 01-01-2025">
                    </div>
                @endforeach
            </div>

            <div class="mt-8 border-t border-slate-200 pt-6">
                <h2 class="text-lg font-semibold text-slate-900">Bewaartermijnen</h2>
                <p class="mt-1 text-sm text-slate-600">Gebruik bijvoorbeeld "7 jaar", "2 jaar na beëindiging" of "3 maanden".</p>
                <div class="mt-4 grid grid-cols-1 gap-x-5 sm:grid-cols-2">
                    <div class="form-group">
                        <label class="form-label" for="legal_retention_contact_request">Contactaanvragen</label>
                        <input class="form-input" id="legal_retention_contact_request" name="legal_retention_contact_request" type="text" value="{{ old('legal_retention_contact_request', $settings['legal_retention_contact_request']) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="legal_retention_quote_request">Offerteaanvragen</label>
                        <input class="form-input" id="legal_retention_quote_request" name="legal_retention_quote_request" type="text" value="{{ old('legal_retention_quote_request', $settings['legal_retention_quote_request']) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="legal_retention_customer_account">Klantaccounts</label>
                        <input class="form-input" id="legal_retention_customer_account" name="legal_retention_customer_account" type="text" value="{{ old('legal_retention_customer_account', $settings['legal_retention_customer_account']) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="legal_retention_analytics">Analytics-gegevens</label>
                        <input class="form-input" id="legal_retention_analytics" name="legal_retention_analytics" type="text" value="{{ old('legal_retention_analytics', $settings['legal_retention_analytics']) }}">
                    </div>
                </div>
            </div>

            <div class="mt-8 border-t border-slate-200 pt-6">
                <h2 class="text-lg font-semibold text-slate-900">Hosting en SLA</h2>
                <p class="mt-1 text-sm text-slate-600">Deze waarden worden gebruikt in de hostingvoorwaarden en op relevante plekken op de site.</p>
                <div class="mt-4 grid grid-cols-1 gap-x-5 sm:grid-cols-2">
                    <div class="form-group">
                        <label class="form-label" for="legal_hosting_backup_frequency">Back-upfrequentie</label>
                        <input class="form-input" id="legal_hosting_backup_frequency" name="legal_hosting_backup_frequency" type="text" value="{{ old('legal_hosting_backup_frequency', $settings['legal_hosting_backup_frequency']) }}" placeholder="bijv. dagelijks">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="legal_hosting_backup_retention">Back-upretentie</label>
                        <input class="form-input" id="legal_hosting_backup_retention" name="legal_hosting_backup_retention" type="text" value="{{ old('legal_hosting_backup_retention', $settings['legal_hosting_backup_retention']) }}" placeholder="bijv. 30 dagen">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="legal_hosting_recovery_time">Hersteltermijn</label>
                        <input class="form-input" id="legal_hosting_recovery_time" name="legal_hosting_recovery_time" type="text" value="{{ old('legal_hosting_recovery_time', $settings['legal_hosting_recovery_time']) }}" placeholder="bijv. 4 uur">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="legal_hosting_uptime_sla">Uptime-SLA</label>
                        <input class="form-input" id="legal_hosting_uptime_sla" name="legal_hosting_uptime_sla" type="text" value="{{ old('legal_hosting_uptime_sla', $settings['legal_hosting_uptime_sla']) }}" placeholder="bijv. 99,9% indien overeengekomen">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="legal_hosting_support_response_time">Reactietijd support</label>
                        <input class="form-input" id="legal_hosting_support_response_time" name="legal_hosting_support_response_time" type="text" value="{{ old('legal_hosting_support_response_time', $settings['legal_hosting_support_response_time']) }}" placeholder="bijv. 1 werkdag">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="legal_hosting_fair_use_storage">Fair-use opslag</label>
                        <input class="form-input" id="legal_hosting_fair_use_storage" name="legal_hosting_fair_use_storage" type="text" value="{{ old('legal_hosting_fair_use_storage', $settings['legal_hosting_fair_use_storage']) }}" placeholder="bijv. 10 GB per pakket">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="legal_hosting_fair_use_traffic">Fair-use dataverkeer</label>
                        <input class="form-input" id="legal_hosting_fair_use_traffic" name="legal_hosting_fair_use_traffic" type="text" value="{{ old('legal_hosting_fair_use_traffic', $settings['legal_hosting_fair_use_traffic']) }}" placeholder="bijv. 100 GB per maand">
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="btn btn-primary">Juridische instellingen opslaan</button>
            </div>
        </form>
    </div>
</div>
@endsection
