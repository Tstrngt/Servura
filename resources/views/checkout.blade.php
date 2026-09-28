@extends('layouts.app')

@section('title', 'Bestellen - Servura')

@section('content')
@php
    $user = auth()->user();
    $initialCountry = old('country', array_key_exists((string) $user?->country, $countries) ? $user->country : 'NL');
    $primaryItem = $resolved['items'][0] ?? null;
    $isDomainOrder = $primaryItem && $primaryItem['service']->fulfillment_type === 'domain';
    $requiresHostingTerms = collect($resolved['items'])->contains(fn ($i) => $i['service']->fulfillment_type === 'directadmin');
    $primaryPriceOptions = $primaryItem && ! $isDomainOrder
        ? $primaryItem['service']->prices->where('is_enabled', true)->sortBy('price')->values()
        : collect();
@endphp
<style>[x-cloak] { display: none !important; }</style>
<div class="min-h-screen bg-slate-50 py-12 lg:py-16">
    <div class="mx-auto grid w-full max-w-7xl grid-cols-1 gap-8 px-4 sm:px-6 lg:grid-cols-[minmax(0,1fr)_380px] lg:px-8"
         x-data="{ country: @js($initialCountry), rates: @js($countryRates), rate() { return Number(this.rates[this.country] ?? 0) }, submitting: false }">

        <div class="space-y-12">
            <div>
                <a href="{{ route('services.index') }}" class="text-sm font-semibold text-primary-700 hover:text-primary-900">← Terug naar diensten</a>
                <h1 class="mt-4 font-heading text-3xl font-bold text-slate-900 sm:text-4xl">Uw bestelling afronden</h1>
                <p class="mt-2 max-w-2xl text-slate-600">Controleer uw gekozen producten en vul uw factuur- en betaalgegevens in.</p>
            </div>

            @include('checkout.partials.cart-items')

            @if($primaryPriceOptions->count() > 1)
                <section class="rounded-2xl bg-slate-900 p-6 text-white shadow-xl">
                    <h2 class="text-lg font-semibold text-white">Betaalperiode</h2>
                    <p class="mt-1 text-sm text-slate-400">Kies de gewenste betaaltermijn voor {{ $primaryItem['service']->title }}.</p>
                    <div class="mt-4">
                        <label class="sr-only" for="service_price_id">Betaalperiode</label>
                        <select name="service_price_id" id="service_price_id" form="checkout-form" class="form-input w-full border-slate-600 bg-slate-800 text-white focus:border-primary-500 focus:ring-primary-500">
                            @foreach($primaryPriceOptions as $price)
                                <option value="{{ $price->id }}" {{ old('service_price_id', $primaryItem['price_model']->id ?? null) == $price->id ? 'selected' : '' }}>
                                    {{ $price->label }} — € {{ number_format($price->price, 2, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </section>
            @elseif($primaryPriceOptions->count() === 1)
                <input type="hidden" name="service_price_id" form="checkout-form" value="{{ $primaryPriceOptions->first()->id }}">
            @endif

            @if($isDomainOrder)
                @include('checkout.partials.hosting-upsell')
            @else
                @include('checkout.partials.domain-choice')
            @endif

            <form id="checkout-form" action="{{ route('checkout.store', $service) }}" method="POST" data-turbo="false" @submit="submitting = true">
                @csrf
                <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-lg font-semibold text-slate-900">Factuurgegevens</h2>

                    @guest
                        <div class="mt-5 rounded-xl bg-primary-50/40 p-5 ring-1 ring-primary-200">
                            <p class="text-sm text-slate-600">U maakt direct een account aan, zodat u uw bestelling en diensten kunt volgen.</p>
                            <p class="mt-2 text-sm text-slate-600">Heeft u al een account? <a href="{{ route('login') }}" class="font-semibold text-primary-700 hover:text-primary-900">Log hier in</a>.</p>
                        </div>
                    @endguest

                    <div class="mt-5 space-y-6">
                        <div class="grid grid-cols-1 gap-x-5 sm:grid-cols-2">
                            <div class="form-group"><label class="form-label" for="name">Naam *</label><input class="form-input" id="name" name="name" required value="{{ old('name', $user?->name) }}"></div>
                            <div class="form-group"><label class="form-label" for="company">Bedrijfsnaam</label><input class="form-input" id="company" name="company" value="{{ old('company', $user?->company) }}"></div>
                            @guest
                                <div class="form-group"><label class="form-label" for="email">E-mailadres *</label><input class="form-input" id="email" name="email" type="email" required value="{{ old('email') }}"></div>
                                <div class="form-group"><label class="form-label" for="phone">Telefoonnummer</label><input class="form-input" id="phone" name="phone" value="{{ old('phone') }}"></div>
                            @else
                                <div class="form-group sm:col-span-2"><label class="form-label" for="phone">Telefoonnummer</label><input class="form-input" id="phone" name="phone" value="{{ old('phone', $user?->phone) }}"></div>
                            @endguest
                            <div class="form-group"><label class="form-label" for="street">Straat *</label><input class="form-input" id="street" name="street" required value="{{ old('street', $user?->street) }}"></div>
                            <div class="form-group"><label class="form-label" for="house_number">Huisnummer *</label><input class="form-input" id="house_number" name="house_number" required value="{{ old('house_number', $user?->house_number) }}"></div>
                            <div class="form-group"><label class="form-label" for="postal_code">Postcode *</label><input class="form-input" id="postal_code" name="postal_code" required value="{{ old('postal_code', $user?->postal_code) }}"></div>
                            <div class="form-group"><label class="form-label" for="city">Plaats *</label><input class="form-input" id="city" name="city" required value="{{ old('city', $user?->city) }}"></div>
                            <div class="form-group"><label class="form-label" for="country">Land *</label><select class="form-input" id="country" name="country" x-model="country" required>@foreach($countries as $code => $name)<option value="{{ $code }}" {{ old('country', $initialCountry) === $code ? 'selected' : '' }}>{{ $name }}</option>@endforeach</select></div>
                            <div class="form-group"><label class="form-label" for="kvk_number">KvK-nummer</label><input class="form-input" id="kvk_number" name="kvk_number" value="{{ old('kvk_number', $user?->kvk_number) }}"></div>
                            <div class="form-group sm:col-span-2"><label class="form-label" for="vat_number">BTW-nummer</label><input class="form-input" id="vat_number" name="vat_number" value="{{ old('vat_number', $user?->vat_number) }}"></div>
                        </div>

                        @guest
                            <div class="rounded-xl bg-primary-50/40 p-5 ring-1 ring-primary-200">
                                <h3 class="text-sm font-semibold text-slate-900">Account aanmaken</h3>
                                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div class="form-group">
                                        <label class="form-label" for="password">Wachtwoord *</label>
                                        <input class="form-input" id="password" name="password" type="password" required minlength="8">
                                        <p class="mt-1 text-xs text-slate-500">Minimaal 8 tekens</p>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="password_confirmation">Herhaal wachtwoord *</label>
                                        <input class="form-input" id="password_confirmation" name="password_confirmation" type="password" required minlength="8">
                                    </div>
                                </div>
                            </div>
                        @endguest

                    </div>
                </section>
            </form>
        </div>

        <aside class="self-start lg:sticky lg:top-12 lg:h-fit">
            <div class="rounded-2xl bg-slate-900 p-6 text-white shadow-xl">
                <span class="text-sm font-medium text-slate-400">Besteloverzicht</span>
                <div class="mt-4 space-y-3">
                    @foreach($resolved['items'] as $item)
                        <div class="flex justify-between gap-4 text-sm">
                            <span class="text-slate-300">{{ $item['service']->title }} @if($item['domain'])<span class="block text-xs text-slate-400">{{ $item['domain'] }}</span>@endif</span>
                            <span class="font-medium">€ {{ number_format($item['price'], 2, ',', '.') }}</span>
                        </div>
                        @if(($item['domain_price'] ?? 0) > 0)
                            <div class="flex justify-between gap-4 text-sm">
                                <span class="text-slate-300">Domein {{ $item['domain_mode'] === 'transfer' ? 'verhuizing' : 'registratie' }} <span class="block text-xs text-slate-400">{{ $item['domain'] }}</span></span>
                                <span class="font-medium">€ {{ number_format($item['domain_price'], 2, ',', '.') }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
                <dl class="mt-6 space-y-3 border-y border-white/10 py-5 text-sm">
                    <div class="flex justify-between gap-4"><dt class="text-slate-400">Exclusief BTW</dt><dd>€ {{ number_format($resolved['subtotal'], 2, ',', '.') }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-slate-400">BTW (<span x-text="rate().toLocaleString('nl-NL')"></span>%)</dt><dd x-text="'€ ' + Number({{ (float) $resolved['subtotal'] }} * (rate() / 100)).toLocaleString('nl-NL', {minimumFractionDigits: 2})"></dd></div>
                    <div class="flex items-end justify-between gap-4 pt-2"><dt class="font-semibold">Totaal</dt><dd class="text-2xl font-bold" x-text="'€ ' + Number({{ (float) $resolved['subtotal'] }} * (1 + rate() / 100)).toLocaleString('nl-NL', {minimumFractionDigits: 2})"></dd></div>
                </dl>

                <fieldset class="mt-5 space-y-2">
                    <legend class="mb-2 text-sm font-semibold text-white">Betaling bij verlenging</legend>
                    <label class="flex cursor-pointer items-start gap-3 rounded-lg bg-white/5 p-3 text-sm text-slate-300 ring-1 ring-white/10"><input type="radio" name="payment_method" value="auto_debit" form="checkout-form" {{ old('payment_method', 'auto_debit') === 'auto_debit' ? 'checked' : '' }} class="mt-1 border-slate-500 bg-slate-800 text-primary-500"><span><strong class="block text-white">Automatische incasso</strong>Na de eerste betaling verlopen toekomstige verlengingen automatisch.</span></label>
                    <label class="flex cursor-pointer items-start gap-3 rounded-lg bg-white/5 p-3 text-sm text-slate-300 ring-1 ring-white/10"><input type="radio" name="payment_method" value="payment_link" form="checkout-form" {{ old('payment_method') === 'payment_link' ? 'checked' : '' }} class="mt-1 border-slate-500 bg-slate-800 text-primary-500"><span><strong class="block text-white">Factuur met betaallink</strong>U ontvangt bij iedere verlenging een nieuwe Mollie-betaallink.</span></label>
                </fieldset>
                <label class="mt-5 flex items-start gap-3 text-sm text-slate-300"><input type="checkbox" name="terms" value="1" form="checkout-form" required class="mt-1 rounded border-slate-500 bg-slate-800 text-primary-500"><span>Ik ga akkoord met de <a href="{{ route('legal.terms') }}" target="_blank" rel="noopener noreferrer" class="underline hover:text-white">Algemene voorwaarden</a>@if($requiresHostingTerms) en de <a href="{{ route('legal.hosting') }}" target="_blank" rel="noopener noreferrer" class="underline hover:text-white">Hostingvoorwaarden</a>@endif en de betalingsverplichting.</span></label>
                @error('terms')<span class="mt-2 block text-sm text-red-300">{{ $message }}</span>@enderror
                <button type="submit" form="checkout-form" :disabled="submitting" class="btn btn-primary mt-6 w-full justify-center py-3 disabled:cursor-wait disabled:opacity-60"><span x-text="submitting ? 'Betaalpagina openen…' : 'Bestellen en betalen'"></span></button>
                <p class="mt-4 text-center text-xs text-slate-400">Veilig betalen via Mollie. Uw dienst wordt pas na bevestigde betaling geactiveerd.</p>
            </div>
        </aside>
    </div>
</div>
@endsection
