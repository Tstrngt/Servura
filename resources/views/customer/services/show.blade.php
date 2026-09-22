@extends('layouts.app')

@section('title', $customerService->service->title . ' beheren - Servura')

@section('content')
@include('customer.partials.topbar')

@php
    $isDirectAdmin = $customerService->service->fulfillment_type === 'directadmin';
    $serverConnection = $customerService->service->serverConnection;
    $service = $customerService->service;
@endphp

<div class="bg-slate-50 min-h-screen pt-32">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 pb-24">
        <div class="mb-8">
            <a href="{{ route('customer.services.index') }}" class="text-sm font-semibold text-primary-700 hover:text-primary-900">← Terug naar Mijn Diensten</a>
            <h1 class="font-heading text-3xl font-bold text-slate-900 mt-3">{{ $service->title }}</h1>
            <p class="mt-2 text-lg text-slate-500">{{ $service->short_description }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-8">
            <div class="space-y-6">
                <!-- Status card -->
                <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h2 class="font-heading text-xl font-bold text-slate-900">Status</h2>
                            <p class="mt-1 text-sm text-slate-500">Huidige status en periode van je dienst.</p>
                        </div>
                        <span class="inline-flex items-center self-start px-3 py-1 rounded-full text-sm font-medium bg-{{ $customerService->statusLabel['color'] }}-100 text-{{ $customerService->statusLabel['color'] }}-800">
                            {{ $customerService->statusLabel['text'] }}
                        </span>
                    </div>

                    <dl class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div class="bg-slate-50 rounded-xl p-4">
                            <dt class="text-slate-500">Startdatum</dt>
                            <dd class="mt-1 font-medium text-slate-900">{{ $customerService->start_date->format('d-m-Y') }}</dd>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4">
                            <dt class="text-slate-500">Einddatum huidige periode</dt>
                            <dd class="mt-1 font-medium text-slate-900">{{ $customerService->end_date ? $customerService->end_date->format('d-m-Y') : 'Onbeperkt' }}</dd>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4">
                            <dt class="text-slate-500">Prijs</dt>
                            <dd class="mt-1 font-medium text-slate-900">{{ $customerService->formatted_price }}</dd>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4">
                            <dt class="text-slate-500">Automatische verlenging</dt>
                            <dd class="mt-1 font-medium text-slate-900">{{ $customerService->auto_renew ? 'Aan' : 'Uit' }}</dd>
                        </div>
                    </dl>

                    @if($customerService->cancel_at_period_end)
                        <div class="mt-4 rounded-xl bg-amber-50 p-4 text-sm text-amber-800">
                            Deze dienst is opgezegd en loopt af op <strong>{{ $customerService->end_date?->format('d-m-Y') ?? 'onbekend' }}</strong>.
                        </div>
                    @endif
                </div>

                <!-- DirectAdmin details -->
                @if($isDirectAdmin && $serverConnection)
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 p-6">
                        <h2 class="font-heading text-xl font-bold text-slate-900 mb-5">Pakket en servergegevens</h2>

                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div class="bg-slate-50 rounded-xl p-4">
                                <dt class="text-slate-500">Paneel</dt>
                                <dd class="mt-1 font-medium text-slate-900 break-all">{{ $serverConnection->url }}</dd>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-4">
                                <dt class="text-slate-500">DirectAdmin gebruikersnaam</dt>
                                <dd class="mt-1 font-medium text-slate-900">{{ $customerService->external_username ?? 'Nog niet aangemaakt' }}</dd>
                            </div>
                            @if($customerService->domain)
                                <div class="bg-slate-50 rounded-xl p-4 sm:col-span-2">
                                    <dt class="text-slate-500">Domein</dt>
                                    <dd class="mt-1 font-medium text-slate-900">{{ $customerService->domain }}</dd>
                                </div>
                            @endif
                            @if($service->provider_package)
                                <div class="bg-slate-50 rounded-xl p-4 sm:col-span-2">
                                    <dt class="text-slate-500">Pakket</dt>
                                    <dd class="mt-1 font-medium text-slate-900">{{ $service->provider_package }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                @endif

                <!-- Service specifications -->
                @if($service->popup_details && count($service->popup_details) > 0)
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 p-6">
                        <h2 class="font-heading text-xl font-bold text-slate-900 mb-5">Pakket specificaties</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($service->popup_details as $detail)
                                <div class="flex items-start gap-3 bg-slate-50 rounded-xl p-4">
                                    @if($detail['icon'] ?? false)
                                        <span class="text-primary-600">{!! $detail['icon'] !!}</span>
                                    @endif
                                    <div class="text-sm">
                                        <div class="font-medium text-slate-900">{{ $detail['label'] ?? '' }}</div>
                                        <div class="text-slate-500">{{ $detail['value'] ?? '' }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Cancel service -->
                @if($customerService->isActive() && !$customerService->cancel_at_period_end)
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 p-6" x-data="{ open: false }">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <h2 class="font-heading text-xl font-bold text-slate-900">Dienst opzeggen</h2>
                                <p class="mt-1 text-sm text-slate-500">De dienst blijft actief tot het einde van de huidige periode.</p>
                            </div>
                            <button type="button" @click="open = true" class="btn btn-outline text-red-600 border-red-200 hover:bg-red-50">Opzeggen</button>
                        </div>
                        <div x-show="open" x-cloak class="mt-5 rounded-xl bg-red-50 p-4">
                            <p class="text-sm text-red-800 mb-4">Weet je zeker dat je deze dienst wilt opzeggen? Deze blijft actief tot <strong>{{ $customerService->end_date?->format('d-m-Y') ?? 'het einde van de periode' }}</strong>.</p>
                            <form action="{{ route('customer.services.cancel', $customerService) }}" method="POST" class="flex gap-3">
                                @csrf
                                <button type="button" @click="open = false" class="btn btn-outline">Annuleren</button>
                                <button type="submit" class="btn btn-primary bg-red-600 hover:bg-red-700 border-red-600">Ja, opzeggen</button>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Transfer -->
                <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 p-6" x-data="{ open: false }">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h2 class="font-heading text-xl font-bold text-slate-900">Overdragen</h2>
                            <p class="mt-1 text-sm text-slate-500">Draag deze dienst over naar een ander Servura-account.</p>
                        </div>
                        <button type="button" @click="open = true" class="btn btn-outline">Overdragen</button>
                    </div>
                    <div x-show="open" x-cloak class="mt-5">
                        <form action="{{ route('customer.services.transfer', $customerService) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="transfer_email" class="block text-sm font-medium text-slate-700">E-mailadres nieuwe eigenaar</label>
                                <input type="email" id="transfer_email" name="email" required class="form-input mt-1 w-full sm:max-w-md">
                            </div>
                            <div>
                                <label for="transfer_reason" class="block text-sm font-medium text-slate-700">Reden (optioneel)</label>
                                <textarea id="transfer_reason" name="reason" rows="2" class="form-input mt-1 w-full sm:max-w-md"></textarea>
                            </div>
                            <div class="flex gap-3">
                                <button type="button" @click="open = false" class="btn btn-outline">Annuleren</button>
                                <button type="submit" class="btn btn-primary">Aanvraag versturen</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Upgrade -->
                @if($service->prices->count() > 1)
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 p-6" x-data="{ open: false }">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <h2 class="font-heading text-xl font-bold text-slate-900">Upgraden</h2>
                                <p class="mt-1 text-sm text-slate-500">Vraag een ander pakket aan voor deze dienst.</p>
                            </div>
                            <button type="button" @click="open = true" class="btn btn-outline">Upgrade aanvragen</button>
                        </div>
                        <div x-show="open" x-cloak class="mt-5">
                            <form action="{{ route('customer.services.upgrade', $customerService) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="upgrade_price" class="block text-sm font-medium text-slate-700">Gewenst pakket</label>
                                    <select id="upgrade_price" name="target_service_price_id" required class="form-input mt-1 w-full sm:max-w-md">
                                        @foreach($service->prices as $price)
                                            <option value="{{ $price->id }}" {{ $customerService->service_price_id === $price->id ? 'disabled' : '' }}>
                                                {{ $price->label }} — € {{ number_format($price->price, 2, ',', '.') }}
                                                @if($customerService->service_price_id === $price->id)
                                                    (huidig)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="upgrade_reason" class="block text-sm font-medium text-slate-700">Reden (optioneel)</label>
                                    <textarea id="upgrade_reason" name="reason" rows="2" class="form-input mt-1 w-full sm:max-w-md"></textarea>
                                </div>
                                <div class="flex gap-3">
                                    <button type="button" @click="open = false" class="btn btn-outline">Annuleren</button>
                                    <button type="submit" class="btn btn-primary">Upgrade aanvragen</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar actions -->
            <aside class="space-y-6 lg:sticky lg:top-32 lg:h-fit">
                @if($isDirectAdmin && $serverConnection && $customerService->external_username)
                    <div class="bg-slate-900 rounded-2xl p-6 text-white shadow-xl">
                        <h2 class="text-lg font-semibold">DirectAdmin</h2>
                        <p class="mt-1 text-sm text-slate-300">Open het controlepaneel voor dit hostingaccount.</p>

                        <form action="{{ route('customer.services.directadmin-login', $customerService) }}" method="POST" target="_blank" class="mt-5">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center rounded-lg bg-primary-600 px-4 py-3 text-sm font-semibold text-white hover:bg-primary-500 transition-colors">
                                Open DirectAdmin
                            </button>
                        </form>

                        <form action="{{ route('customer.services.reset-password', $customerService) }}" method="POST" class="mt-3" onsubmit="return confirm('Weet je zeker dat je het DirectAdmin-wachtwoord opnieuw wilt instellen?')">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center rounded-lg bg-white/10 px-4 py-3 text-sm font-semibold text-white ring-1 ring-white/15 hover:bg-white/15 transition-colors">
                                Wachtwoord resetten
                            </button>
                        </form>

                        @if($customerService->external_password)
                            <div class="mt-5 pt-5 border-t border-white/10">
                                <label class="text-xs font-medium uppercase tracking-wide text-slate-500">Wachtwoord</label>
                                <div class="mt-2 flex items-center gap-2">
                                    <input type="password" value="{{ $customerService->external_password }}" readonly class="flex-1 rounded-lg bg-white/5 px-3 py-2 text-sm text-white ring-1 ring-white/10" id="da-password">
                                    <button type="button" onclick="const el = document.getElementById('da-password'); el.type = el.type === 'password' ? 'text' : 'password';" class="rounded-lg bg-white/10 px-3 py-2 text-xs font-medium text-white ring-1 ring-white/15 hover:bg-white/15">Tonen</button>
                                </div>
                                <p class="mt-2 text-xs text-slate-400">Dit wachtwoord wordt alleen hier getoond. Bewaar het veilig.</p>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 p-6">
                    <h3 class="font-heading text-lg font-bold text-slate-900 mb-2">Hulp nodig?</h3>
                    <p class="text-sm text-slate-500 mb-4">Loop je ergens tegenaan met deze dienst? Maak een supportticket aan.</p>
                    <a href="{{ route('customer.tickets.create') }}" class="btn btn-outline w-full justify-center">Ticket aanmaken</a>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection
