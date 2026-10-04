@extends('layouts.app')

@section('title', 'Algemene instellingen - Servura Admin')

@section('content')
@include('admin.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="mx-auto w-full max-w-[1600px] px-4 py-4 sm:px-6 lg:px-8">
        <div class="py-4">
            <h1 class="text-2xl font-bold text-gray-900">Instellingen</h1>
            <p class="mt-1 text-sm text-gray-600">Systeem- en integratie-instellingen beheren.</p>
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

        <form action="{{ route('admin.settings.general.update') }}" method="POST" class="max-w-3xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            @csrf
            @method('PUT')
            <h2 class="text-lg font-semibold text-slate-900">Algemeen</h2>
            <p class="mt-1 text-sm text-slate-600">Platforminstellingen voor taal, land en datumweergave. Bedrijfs- en contactgegevens stel je hieronder één keer in.</p>
            <div class="mt-6 grid grid-cols-1 gap-x-5 sm:grid-cols-2">
                <div class="form-group">
                    <label class="form-label" for="date_format">Datumformaat</label>
                    <select class="form-input" id="date_format" name="date_format" required>
                        @foreach(['d-m-Y' => '31-12-2025 (d-m-Y)', 'd/m/Y' => '31/12/2025 (d/m/Y)', 'Y-m-d' => '2025-12-31 (Y-m-d)', 'm/d/Y' => '12/31/2025 (m/d/Y)'] as $value => $label)
                            <option value="{{ $value }}" {{ old('date_format', $settings['date_format']) === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="default_country">Standaardland</label>
                    <select class="form-input" id="default_country" name="default_country" required>
                        @foreach(['NL' => 'Nederland', 'BE' => 'België', 'DE' => 'Duitsland', 'GB' => 'Verenigd Koninkrijk'] as $value => $label)
                            <option value="{{ $value }}" {{ old('default_country', $settings['default_country']) === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="default_language">Standaardtaal</label>
                    <select class="form-input" id="default_language" name="default_language" required>
                        @foreach(['nl' => 'Nederlands', 'en' => 'Engels', 'de' => 'Duits', 'fr' => 'Frans'] as $value => $label)
                            <option value="{{ $value }}" {{ old('default_language', $settings['default_language']) === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">
                <label class="flex items-start gap-3 rounded-xl bg-slate-50 p-4 ring-1 ring-slate-200">
                    <input type="checkbox" name="language_menu_enabled" value="1" {{ old('language_menu_enabled', $settings['language_menu_enabled']) ? 'checked' : '' }} class="mt-1 rounded border-slate-300 text-primary-600">
                    <span><strong class="block text-sm text-slate-900">Taalmenu in de navigatie</strong><span class="mt-1 block text-sm text-slate-600">Toont een taalkeuze in de header (voorbereiding op meertaligheid).</span></span>
                </label>
                <label class="flex items-start gap-3 rounded-xl bg-slate-50 p-4 ring-1 ring-slate-200">
                    <input type="checkbox" name="newsletter_enabled" value="1" {{ old('newsletter_enabled', $settings['newsletter_enabled']) ? 'checked' : '' }} class="mt-1 rounded border-slate-300 text-primary-600">
                    <span><strong class="block text-sm text-slate-900">Nieuwsbrief aanmelden in footer</strong><span class="mt-1 block text-sm text-slate-600">Toont een aanmeldformulier in de footer van de site.</span></span>
                </label>
            </div>

            <div class="mt-8 border-t border-slate-200 pt-6">
                <h3 class="text-base font-semibold text-slate-900">Bedrijfsgegevens</h3>
                <p class="mt-1 text-sm text-slate-600">Deze gegevens worden gebruikt op de footer, contactpagina, e-mails en juridische pagina's. Je vult ze slechts één keer in.</p>
                <div class="mt-4 grid grid-cols-1 gap-x-5 sm:grid-cols-2">
                    <div class="form-group">
                        <label class="form-label" for="company_legal_name">Juridische bedrijfsnaam</label>
                        <input class="form-input" id="company_legal_name" name="company_legal_name" type="text" value="{{ old('company_legal_name', $settings['company_legal_name']) }}" placeholder="Bijv. Servura B.V.">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="company_trade_name">Handelsnaam</label>
                        <input class="form-input" id="company_trade_name" name="company_trade_name" type="text" value="{{ old('company_trade_name', $settings['company_trade_name']) }}" placeholder="Servura">
                        <p class="mt-1 text-xs text-slate-500">Wordt ook als sitenaam gebruikt.</p>
                    </div>
                    <div class="form-group sm:col-span-2">
                        <label class="form-label" for="company_address">Adres</label>
                        <input class="form-input" id="company_address" name="company_address" type="text" value="{{ old('company_address', $settings['company_address']) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="company_postal_code">Postcode</label>
                        <input class="form-input" id="company_postal_code" name="company_postal_code" type="text" value="{{ old('company_postal_code', $settings['company_postal_code']) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="company_city">Plaats</label>
                        <input class="form-input" id="company_city" name="company_city" type="text" value="{{ old('company_city', $settings['company_city']) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="company_country">Land</label>
                        <input class="form-input" id="company_country" name="company_country" type="text" value="{{ old('company_country', $settings['company_country']) }}" placeholder="Nederland">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="company_kvk_number">KvK-nummer</label>
                        <input class="form-input" id="company_kvk_number" name="company_kvk_number" type="text" value="{{ old('company_kvk_number', $settings['company_kvk_number']) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="company_vat_number">BTW-nummer</label>
                        <input class="form-input" id="company_vat_number" name="company_vat_number" type="text" value="{{ old('company_vat_number', $settings['company_vat_number']) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="company_email">Zakelijk e-mailadres</label>
                        <input class="form-input" id="company_email" name="company_email" type="email" value="{{ old('company_email', $settings['company_email']) }}" placeholder="support@servura.nl">
                        <p class="mt-1 text-xs text-slate-500">Dit is het contactadres dat op de site en in e-mails wordt getoond.</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="company_phone">Telefoonnummer</label>
                        <input class="form-input" id="company_phone" name="company_phone" type="text" value="{{ old('company_phone', $settings['company_phone']) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="company_website">Website</label>
                        <input class="form-input" id="company_website" name="company_website" type="url" value="{{ old('company_website', $settings['company_website']) }}" placeholder="https://servura.nl">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="company_privacy_email">Privacy-contact e-mail</label>
                        <input class="form-input" id="company_privacy_email" name="company_privacy_email" type="email" value="{{ old('company_privacy_email', $settings['company_privacy_email']) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="company_abuse_email">Abuse e-mailadres</label>
                        <input class="form-input" id="company_abuse_email" name="company_abuse_email" type="email" value="{{ old('company_abuse_email', $settings['company_abuse_email']) }}" placeholder="abuse@servura.nl">
                    </div>
                </div>
            </div>

            <div class="mt-8 border-t border-slate-200 pt-6">
                <h3 class="text-base font-semibold text-slate-900">Social media</h3>
                <p class="mt-1 text-sm text-slate-600">Ingevulde links verschijnen automatisch met het bijbehorende icoon in de footer van de hele site.</p>
                <div class="mt-4 grid grid-cols-1 gap-x-5 sm:grid-cols-2">
                    @foreach(['instagram' => 'Instagram', 'linkedin' => 'LinkedIn', 'facebook' => 'Facebook', 'x' => 'X (Twitter)', 'youtube' => 'YouTube', 'tiktok' => 'TikTok'] as $key => $label)
                        <div class="form-group">
                            <label class="form-label" for="social_{{ $key }}">{{ $label }}</label>
                            <input class="form-input" id="social_{{ $key }}" name="social_{{ $key }}" type="url" placeholder="https://…" value="{{ old("social_$key", $settings["social_$key"]) }}">
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="btn btn-primary">Instellingen opslaan</button>
            </div>
        </form>

        <div class="mt-8 max-w-3xl rounded-2xl border border-red-200 bg-red-50/50 p-6">
            <h2 class="text-lg font-semibold text-red-900">Danger zone</h2>
            <p class="mt-1 text-sm text-red-700">Verwijder alle klantaccounts en alle bijbehorende gegevens: diensten, facturen, transacties, offertes, tickets, orders en betaalbetalingen. Diensten/producten, instellingen en medewerkersaccounts blijven bestaan. Dit kan niet ongedaan worden — bedoeld voor de testfase.</p>
            <form action="{{ route('admin.settings.reset-customers') }}" method="POST" class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-end" onsubmit="return confirm('Weet je het zeker? ALLE klantaccounts en hun gegevens worden definitief verwijderd.')">
                @csrf
                <div class="flex-1">
                    <label class="form-label text-red-800" for="confirm">Typ RESET om te bevestigen</label>
                    <input class="form-input border-red-300 focus:border-red-500 focus:ring-red-500" id="confirm" name="confirm" type="text" required autocomplete="off" placeholder="RESET">
                </div>
                <button type="submit" class="btn whitespace-nowrap bg-red-600 text-white hover:bg-red-700">Alle klanten verwijderen</button>
            </form>
        </div>
    </div>
</div>
@endsection
