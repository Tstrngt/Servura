@extends('layouts.app')

@section('title', 'Facturatie-instellingen - Servura Admin')

@section('content')
@include('admin.partials.sidebar')
<div class="min-h-screen bg-gray-50 lg:pl-64">
    <div class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-7">
            <h1 class="text-2xl font-bold text-slate-900">Facturatie-instellingen</h1>
            <p class="mt-1 text-sm text-slate-600">Beheer BTW, betaaltermijnen en controleer de Mollie-configuratie.</p>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[minmax(0,1fr)_360px]">
            <form action="{{ route('admin.billing-settings.update') }}" method="POST" class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                @csrf
                @method('PUT')
                <h2 class="text-lg font-semibold text-slate-900">BTW en betaaltermijnen</h2>
                <div class="mt-6 grid grid-cols-1 gap-x-5 sm:grid-cols-2">
                    <div class="form-group"><label class="form-label" for="default_vat_rate">Standaard BTW-tarief (%)</label><input class="form-input" id="default_vat_rate" name="default_vat_rate" type="number" step="0.01" min="0" max="100" required value="{{ old('default_vat_rate', $settings['default_vat_rate']) }}"></div>
                    <div class="form-group"><label class="form-label" for="business_country">Vestigingsland</label><select class="form-input" id="business_country" name="business_country"><option value="NL" {{ $settings['business_country'] === 'NL' ? 'selected' : '' }}>Nederland</option><option value="BE" {{ $settings['business_country'] === 'BE' ? 'selected' : '' }}>België</option><option value="DE" {{ $settings['business_country'] === 'DE' ? 'selected' : '' }}>Duitsland</option></select></div>
                    <div class="form-group"><label class="form-label" for="invoice_due_days">Betaaltermijn (dagen)</label><input class="form-input" id="invoice_due_days" name="invoice_due_days" type="number" min="1" max="90" required value="{{ old('invoice_due_days', $settings['invoice_due_days']) }}"></div>
                    <div class="form-group"><label class="form-label" for="suspension_grace_days">Respijt na vervaldatum (dagen)</label><input class="form-input" id="suspension_grace_days" name="suspension_grace_days" type="number" min="0" max="90" required value="{{ old('suspension_grace_days', $settings['suspension_grace_days']) }}"></div>
                </div>
                <label class="flex items-start gap-3 rounded-xl bg-amber-50 p-4 ring-1 ring-amber-200">
                    <input type="checkbox" name="country_vat_enabled" value="1" {{ old('country_vat_enabled', $settings['country_vat_enabled']) ? 'checked' : '' }} class="mt-1 rounded border-amber-300 text-primary-600">
                    <span><strong class="block text-sm text-amber-950">Landgebonden BTW inschakelen</strong><span class="mt-1 block text-sm leading-relaxed text-amber-800">Past het lokale standaardtarief toe voor EU-landen en 0% buiten de EU. Laat dit uitgeschakeld totdat internationale verkoop en fiscale validatie zijn ingericht.</span></span>
                </label>
                <div class="mt-6 flex justify-end"><button type="submit" class="btn btn-primary">Instellingen opslaan</button></div>
            </form>

            <aside class="h-fit rounded-2xl bg-slate-900 p-6 text-white shadow-xl">
                <div class="flex items-center justify-between gap-4"><h2 class="text-lg font-semibold">Mollie</h2><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $mollie['configured'] ? ($mollie['mode'] === 'live' ? 'bg-emerald-400/15 text-emerald-300' : 'bg-amber-400/15 text-amber-300') : 'bg-red-400/15 text-red-300' }}">{{ !$mollie['configured'] ? 'Niet ingesteld' : ($mollie['mode'] === 'live' ? 'Live' : 'Test') }}</span></div>
                <p class="mt-4 text-sm leading-relaxed text-slate-300">De geheime API-key wordt om veiligheidsredenen niet in het adminpanel of de database opgeslagen.</p>
                <div class="mt-5 rounded-xl bg-white/5 p-4 ring-1 ring-white/10"><span class="block text-xs font-medium uppercase tracking-wide text-slate-500">Serverbestand</span><code class="mt-2 block break-all text-sm text-slate-200">/var/www/Servura/.env</code><span class="mt-3 block text-xs font-medium uppercase tracking-wide text-slate-500">Variabele</span><code class="mt-2 block text-sm text-slate-200">MOLLIE_KEY=••••••••</code></div>
                <p class="mt-4 text-xs leading-relaxed text-slate-400">Voer na een wijziging op de server <code class="text-slate-300">php artisan config:clear</code> en daarna <code class="text-slate-300">php artisan config:cache</code> uit.</p>

                <div class="mt-7 border-t border-white/10 pt-6">
                    <div class="flex items-center justify-between gap-4"><h2 class="text-lg font-semibold">DirectAdmin</h2><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $directAdminStatus['configured'] ? 'bg-emerald-400/15 text-emerald-300' : 'bg-red-400/15 text-red-300' }}">{{ $directAdminStatus['configured'] ? 'Geconfigureerd' : 'Niet ingesteld' }}</span></div>
                    <p class="mt-4 text-sm leading-relaxed text-slate-300">Provisioning gebruikt een reselleraccount. Het wachtwoord blijft uitsluitend in de serveromgeving.</p>
                    <div class="mt-5 rounded-xl bg-white/5 p-4 ring-1 ring-white/10"><span class="block text-xs font-medium uppercase tracking-wide text-slate-500">URL</span><code class="mt-2 block break-all text-sm text-slate-200">{{ $directAdminStatus['url'] ?: 'DIRECTADMIN_URL niet ingesteld' }}</code><span class="mt-3 block text-xs font-medium uppercase tracking-wide text-slate-500">Reseller</span><code class="mt-2 block text-sm text-slate-200">{{ $directAdminStatus['username'] ?: 'DIRECTADMIN_USERNAME niet ingesteld' }}</code><span class="mt-3 block text-xs font-medium uppercase tracking-wide text-slate-500">Gedeeld IP</span><code class="mt-2 block text-sm text-slate-200">{{ $directAdminStatus['shared_ip'] ?: 'DIRECTADMIN_SHARED_IP niet ingesteld' }}</code></div>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection
