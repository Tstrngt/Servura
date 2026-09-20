@extends('layouts.app')

@section('title', 'Bestel ' . $service->title . ' - Servura')

@section('content')
@php
    $initialPrice = $service->prices->first();
    $user = auth()->user();
    $initialCountry = old('country', array_key_exists((string) $user?->country, $countries) ? $user->country : 'NL');
@endphp
<div class="min-h-screen bg-slate-50 py-12 lg:py-16">
    <form action="{{ route('checkout.store', $service) }}" method="POST" x-data="{ selected: @js((string) old('service_price_id', $initialPrice->id)), country: @js($initialCountry), prices: @js($service->prices->mapWithKeys(fn ($price) => [(string) $price->id => (float) $price->price])), rates: @js($countryRates), submitting: false, rate() { return Number(this.rates[this.country] ?? 0) } }" @submit="submitting = true" class="mx-auto grid w-full max-w-7xl grid-cols-1 gap-8 px-4 sm:px-6 lg:grid-cols-[minmax(0,1fr)_380px] lg:px-8">
        @csrf
        <div class="space-y-6">
            <div>
                <a href="{{ route('services.index') }}" class="text-sm font-semibold text-primary-700 hover:text-primary-900">← Terug naar diensten</a>
                <h1 class="mt-4 font-heading text-3xl font-bold text-slate-900 sm:text-4xl">{{ $service->title }} bestellen</h1>
                <p class="mt-2 max-w-2xl text-slate-600">Kies een betaalperiode en controleer uw factuurgegevens. Daarna wordt u doorgestuurd naar Mollie.</p>
            </div>

            <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-lg font-semibold text-slate-900">1. Kies uw betaalperiode</h2>
                <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach($service->prices as $price)
                        <label class="relative cursor-pointer rounded-xl border p-4 transition-colors" :class="selected === '{{ $price->id }}' ? 'border-primary-500 bg-primary-50 ring-2 ring-primary-500/10' : 'border-slate-200 hover:border-slate-300'">
                            <input type="radio" name="service_price_id" value="{{ $price->id }}" x-model="selected" class="sr-only">
                            <span class="block text-sm font-semibold text-slate-900">{{ $price->label }}</span>
                            <span class="mt-2 block text-xl font-bold text-slate-900">€ {{ number_format($price->price, 2, ',', '.') }}</span>
                            <span class="mt-1 block text-xs text-slate-500"><span x-text="'€ ' + Number({{ (float) $price->price }} * (1 + rate() / 100)).toLocaleString('nl-NL', {minimumFractionDigits: 2})"></span> inclusief BTW</span>
                        </label>
                    @endforeach
                </div>
                @error('service_price_id')<span class="form-error">{{ $message }}</span>@enderror
            </section>

            <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-lg font-semibold text-slate-900">2. Factuurgegevens</h2>
                @guest
                    <div class="mt-3 rounded-xl bg-blue-50 px-4 py-3 text-sm text-blue-800">Er wordt direct een Servura-klantaccount voor u aangemaakt.</div>
                @endguest
                <div class="mt-5 grid grid-cols-1 gap-x-5 sm:grid-cols-2">
                    <div class="form-group"><label class="form-label" for="name">Naam *</label><input class="form-input" id="name" name="name" required value="{{ old('name', $user?->name) }}"></div>
                    <div class="form-group"><label class="form-label" for="company">Bedrijfsnaam</label><input class="form-input" id="company" name="company" value="{{ old('company', $user?->company) }}"></div>
                    @guest
                        <div class="form-group"><label class="form-label" for="email">E-mailadres *</label><input class="form-input" id="email" name="email" type="email" required value="{{ old('email') }}"></div>
                        <div class="form-group"><label class="form-label" for="phone">Telefoonnummer</label><input class="form-input" id="phone" name="phone" value="{{ old('phone') }}"></div>
                        <div class="form-group"><label class="form-label" for="password">Wachtwoord *</label><input class="form-input" id="password" name="password" type="password" required minlength="8"></div>
                        <div class="form-group"><label class="form-label" for="password_confirmation">Herhaal wachtwoord *</label><input class="form-input" id="password_confirmation" name="password_confirmation" type="password" required minlength="8"></div>
                    @else
                        <div class="form-group sm:col-span-2"><label class="form-label" for="phone">Telefoonnummer</label><input class="form-input" id="phone" name="phone" value="{{ old('phone', $user?->phone) }}"></div>
                    @endguest
                    <div class="form-group"><label class="form-label" for="street">Straat *</label><input class="form-input" id="street" name="street" required value="{{ old('street', $user?->street) }}"></div>
                    <div class="form-group"><label class="form-label" for="house_number">Huisnummer *</label><input class="form-input" id="house_number" name="house_number" required value="{{ old('house_number', $user?->house_number) }}"></div>
                    <div class="form-group"><label class="form-label" for="postal_code">Postcode *</label><input class="form-input" id="postal_code" name="postal_code" required value="{{ old('postal_code', $user?->postal_code) }}"></div>
                    <div class="form-group"><label class="form-label" for="city">Plaats *</label><input class="form-input" id="city" name="city" required value="{{ old('city', $user?->city) }}"></div>
                    <div class="form-group"><label class="form-label" for="country">Land *</label><select class="form-input" id="country" name="country" x-model="country" required>@foreach($countries as $code => $name)<option value="{{ $code }}">{{ $name }}</option>@endforeach</select></div>
                    <div class="form-group"><label class="form-label" for="kvk_number">KvK-nummer</label><input class="form-input" id="kvk_number" name="kvk_number" value="{{ old('kvk_number', $user?->kvk_number) }}"></div>
                    <div class="form-group sm:col-span-2"><label class="form-label" for="vat_number">BTW-nummer</label><input class="form-input" id="vat_number" name="vat_number" value="{{ old('vat_number', $user?->vat_number) }}"></div>
                </div>
            </section>
        </div>

        <aside class="lg:sticky lg:top-24 lg:h-fit">
            <div class="rounded-2xl bg-slate-900 p-6 text-white shadow-xl">
                <span class="text-sm font-medium text-slate-400">Besteloverzicht</span>
                <h2 class="mt-2 text-xl font-bold">{{ $service->title }}</h2>
                <p class="mt-2 text-sm leading-relaxed text-slate-300">{{ $service->short_description }}</p>
                <dl class="mt-6 space-y-3 border-y border-white/10 py-5 text-sm">
                    <div class="flex justify-between gap-4"><dt class="text-slate-400">Exclusief BTW</dt><dd x-text="'€ ' + Number(prices[selected] || 0).toLocaleString('nl-NL', {minimumFractionDigits: 2})"></dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-slate-400">BTW (<span x-text="rate().toLocaleString('nl-NL')"></span>%)</dt><dd x-text="'€ ' + Number((prices[selected] || 0) * (rate() / 100)).toLocaleString('nl-NL', {minimumFractionDigits: 2})"></dd></div>
                    <div class="flex items-end justify-between gap-4 pt-2"><dt class="font-semibold">Totaal</dt><dd class="text-2xl font-bold" x-text="'€ ' + Number((prices[selected] || 0) * (1 + rate() / 100)).toLocaleString('nl-NL', {minimumFractionDigits: 2})"></dd></div>
                </dl>
                <fieldset class="mt-5 space-y-2">
                    <legend class="mb-2 text-sm font-semibold text-white">Betaling bij verlenging</legend>
                    <label class="flex cursor-pointer items-start gap-3 rounded-lg bg-white/5 p-3 text-sm text-slate-300 ring-1 ring-white/10"><input type="radio" name="payment_method" value="auto_debit" {{ old('payment_method', 'auto_debit') === 'auto_debit' ? 'checked' : '' }} class="mt-1 border-slate-500 bg-slate-800 text-primary-500"><span><strong class="block text-white">Automatische incasso</strong>Na de eerste betaling verlopen toekomstige verlengingen automatisch.</span></label>
                    <label class="flex cursor-pointer items-start gap-3 rounded-lg bg-white/5 p-3 text-sm text-slate-300 ring-1 ring-white/10"><input type="radio" name="payment_method" value="payment_link" {{ old('payment_method') === 'payment_link' ? 'checked' : '' }} class="mt-1 border-slate-500 bg-slate-800 text-primary-500"><span><strong class="block text-white">Factuur met betaallink</strong>U ontvangt bij iedere verlenging een nieuwe Mollie-betaallink.</span></label>
                </fieldset>
                <label class="mt-5 flex items-start gap-3 text-sm text-slate-300"><input type="checkbox" name="terms" value="1" required class="mt-1 rounded border-slate-500 bg-slate-800 text-primary-500"><span>Ik ga akkoord met de algemene voorwaarden en de betalingsverplichting.</span></label>
                @error('terms')<span class="mt-2 block text-sm text-red-300">{{ $message }}</span>@enderror
                <button type="submit" :disabled="submitting" class="btn btn-primary mt-6 w-full justify-center py-3 disabled:cursor-wait disabled:opacity-60"><span x-text="submitting ? 'Betaalpagina openen…' : 'Bestellen en betalen'"></span></button>
                <p class="mt-4 text-center text-xs text-slate-400">Veilig betalen via Mollie. Uw dienst wordt pas na bevestigde betaling geactiveerd.</p>
            </div>
        </aside>
    </form>
</div>
@endsection
