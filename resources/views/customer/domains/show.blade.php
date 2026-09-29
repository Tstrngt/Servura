@extends('layouts.app')

@section('title', $domain->domain_name . ' - Servura')

@section('content')
@include('customer.partials.topbar')

@php
    $canSetNameservers = in_array(\Transip\Api\Library\Entity\Tld::CAPABILITY_CANSETNAMESERVERS, $tldCapabilities['capabilities'] ?? []);
    $canSetContacts = in_array(\Transip\Api\Library\Entity\Tld::CAPABILITY_CANSETCONTACTS, $tldCapabilities['capabilities'] ?? []);
    $requiresAuthCode = in_array(\Transip\Api\Library\Entity\Tld::CAPABILITY_REQUIRESAUTHCODE, $tldCapabilities['capabilities'] ?? []);
@endphp

<div class="bg-slate-50 min-h-screen pt-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 pb-24">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
            <div>
                <a href="{{ route('customer.domains.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-800">&larr; Terug naar domeinen</a>
                <h1 class="mt-3 font-heading text-3xl font-bold text-slate-900">{{ $domain->domain_name }}</h1>
            </div>
            <div class="shrink-0 flex items-center gap-3">
                <form action="{{ route('customer.domains.sync', $domain) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-secondary">Vernieuwen</button>
                </form>
            </div>
        </div>

        <!-- Overview -->
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70 mb-8">
            <h2 class="font-heading text-xl font-bold text-slate-900 mb-5">Overzicht</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 text-sm">
                <div>
                    <dt class="text-slate-500">Status</dt>
                    @php $label = $domain->statusLabel; @endphp
                    <dd class="mt-1"><span class="inline-flex items-center rounded-full bg-{{ $label['color'] }}-50 px-2.5 py-0.5 text-xs font-medium text-{{ $label['color'] }}-700 ring-1 ring-inset ring-{{ $label['color'] }}-600/20">{{ $label['text'] }}</span></dd>
                </div>
                <div>
                    <dt class="text-slate-500">Type</dt>
                    <dd class="mt-1 font-medium text-slate-900">{{ $domain->type === 'transfer' ? 'Verhuizing' : 'Registratie' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Provider</dt>
                    <dd class="mt-1 font-medium text-slate-900 uppercase">{{ $domain->provider }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">{{ $domain->type === 'transfer' ? 'Verhuisdatum' : 'Registratiedatum' }}</dt>
                    <dd class="mt-1 font-medium text-slate-900">{{ $domain->registered_at?->format('d-m-Y') ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Verloopt</dt>
                    <dd class="mt-1 font-medium text-slate-900">{{ $domain->expires_at?->format('d-m-Y') ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Automatische verlenging</dt>
                    <dd class="mt-1 font-medium text-slate-900">{{ $domain->auto_renew ? 'Ja' : 'Nee' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Registrar lock</dt>
                    <dd class="mt-1 font-medium text-slate-900">{{ $domain->registrar_lock ? 'Actief' : 'Niet actief' }}</dd>
                </div>
                <div class="sm:col-span-2 lg:col-span-1">
                    <dt class="text-slate-500">Laatste synchronisatie</dt>
                    <dd class="mt-1 font-medium text-slate-900">{{ $domain->provider_info_synced_at?->diffForHumans() ?? 'Nog niet gesynchroniseerd' }}</dd>
                </div>
            </div>

            @if($domain->error_message)
                <div class="mt-6 rounded-lg bg-red-50 p-4 text-sm text-red-800">
                    <strong>Melding:</strong> {{ $domain->error_message }}
                </div>
            @endif
        </div>

        <!-- Holder details -->
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70 mb-8" x-data="{ edit: false }">
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-heading text-xl font-bold text-slate-900">Houdergegevens</h2>
                @if($canSetContacts && count($contacts) > 0)
                    <button type="button" @click="edit = !edit" class="text-sm font-semibold text-primary-600 hover:text-primary-800" x-text="edit ? 'Annuleren' : 'Wijzigen'"></button>
                @endif
            </div>

            @if(! $canSetContacts)
                <p class="text-sm text-slate-500">Voor deze extensie kunnen houdergegevens niet via het klantportaal worden gewijzigd. Neem contact op met support.</p>
            @elseif(count($contacts) === 0)
                <p class="text-sm text-slate-500">Houdergegevens worden opgehaald uit TransIP.</p>
            @else
                <div x-show="!edit">
                    <div class="space-y-4">
                        @foreach($contacts as $contact)
                            <div class="rounded-xl bg-slate-50 p-4 text-sm">
                                <p class="font-semibold text-slate-900 mb-1">{{ ucfirst($contact['type'] ?? 'contact') }}</p>
                                <p class="text-slate-700">{{ $contact['first_name'] ?? '' }} {{ $contact['last_name'] ?? '' }}</p>
                                @if(($contact['company_name'] ?? '') !== '')
                                    <p class="text-slate-500">{{ $contact['company_name'] }}</p>
                                @endif
                                <p class="text-slate-500">{{ $contact['street'] ?? '' }} {{ $contact['number'] ?? '' }}</p>
                                <p class="text-slate-500">{{ $contact['postal_code'] ?? '' }} {{ $contact['city'] ?? '' }}</p>
                                <p class="text-slate-500">{{ $contact['country'] ?? '' }}</p>
                                <p class="text-slate-500">{{ $contact['email'] ?? '' }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <form x-show="edit" x-cloak method="POST" action="{{ route('customer.domains.holder.update', $domain) }}" class="space-y-6">
                    @csrf
                    @foreach($contacts as $index => $contact)
                        <div class="rounded-xl bg-slate-50 p-4">
                            <input type="hidden" name="contacts[{{ $index }}][type]" value="{{ $contact['type'] ?? 'registrant' }}">
                            <p class="text-sm font-semibold text-slate-900 mb-3">{{ ucfirst($contact['type'] ?? 'contact') }}</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div><label class="block text-xs font-medium text-slate-600">Voornaam</label><input type="text" name="contacts[{{ $index }}][first_name]" value="{{ $contact['first_name'] ?? '' }}" class="form-input mt-1" required></div>
                                <div><label class="block text-xs font-medium text-slate-600">Achternaam</label><input type="text" name="contacts[{{ $index }}][last_name]" value="{{ $contact['last_name'] ?? '' }}" class="form-input mt-1" required></div>
                                <div><label class="block text-xs font-medium text-slate-600">Bedrijf</label><input type="text" name="contacts[{{ $index }}][company_name]" value="{{ $contact['company_name'] ?? '' }}" class="form-input mt-1"></div>
                                <div><label class="block text-xs font-medium text-slate-600">KVK-nummer</label><input type="text" name="contacts[{{ $index }}][company_kvk]" value="{{ $contact['company_kvk'] ?? '' }}" class="form-input mt-1"></div>
                                <div><label class="block text-xs font-medium text-slate-600">Straat</label><input type="text" name="contacts[{{ $index }}][street]" value="{{ $contact['street'] ?? '' }}" class="form-input mt-1" required></div>
                                <div><label class="block text-xs font-medium text-slate-600">Huisnummer</label><input type="text" name="contacts[{{ $index }}][number]" value="{{ $contact['number'] ?? '' }}" class="form-input mt-1" required></div>
                                <div><label class="block text-xs font-medium text-slate-600">Postcode</label><input type="text" name="contacts[{{ $index }}][postal_code]" value="{{ $contact['postal_code'] ?? '' }}" class="form-input mt-1" required></div>
                                <div><label class="block text-xs font-medium text-slate-600">Plaats</label><input type="text" name="contacts[{{ $index }}][city]" value="{{ $contact['city'] ?? '' }}" class="form-input mt-1" required></div>
                                <div><label class="block text-xs font-medium text-slate-600">Land (2-letterig)</label><input type="text" name="contacts[{{ $index }}][country]" value="{{ $contact['country'] ?? '' }}" maxlength="2" class="form-input mt-1" required></div>
                                <div><label class="block text-xs font-medium text-slate-600">E-mail</label><input type="email" name="contacts[{{ $index }}][email]" value="{{ $contact['email'] ?? '' }}" class="form-input mt-1" required></div>
                                <div><label class="block text-xs font-medium text-slate-600">Telefoon</label><input type="text" name="contacts[{{ $index }}][phone_number]" value="{{ $contact['phone_number'] ?? '' }}" class="form-input mt-1"></div>
                            </div>
                        </div>
                    @endforeach
                    <div class="flex items-center gap-3">
                        <button type="submit" class="btn btn-primary">Wijzigingen doorvoeren</button>
                        <button type="button" @click="edit = false" class="text-sm font-semibold text-slate-600 hover:text-slate-900">Annuleren</button>
                    </div>
                    <p class="text-xs text-slate-500">Wijzigingen worden eerst naar TransIP verstuurd; de status wordt bijgewerkt zodra TransIP de wijziging bevestigt.</p>
                </form>
            @endif
        </div>

        <!-- Nameservers -->
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70 mb-8" x-data="{ edit: false }">
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-heading text-xl font-bold text-slate-900">Nameservers</h2>
                @if($canSetNameservers)
                    <button type="button" @click="edit = !edit" class="text-sm font-semibold text-primary-600 hover:text-primary-800" x-text="edit ? 'Annuleren' : 'Wijzigen'"></button>
                @endif
            </div>

            @if(! $canSetNameservers)
                <p class="text-sm text-slate-500">Voor deze extensie kunnen nameservers niet via het klantportaal worden gewijzigd. Neem contact op met support.</p>
            @else
                <div x-show="!edit" class="space-y-3">
                    @forelse($domain->current_nameservers ?? [] as $ns)
                        <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3 text-sm">
                            <span class="font-medium text-slate-900">{{ $ns['hostname'] ?? '-' }}</span>
                            <span class="text-slate-500">{{ $ns['ipv4'] ?? '' }} {{ $ns['ipv6'] ?? '' }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Geen nameservers gesynchroniseerd. Klik op "Vernieuwen" om deze op te halen.</p>
                    @endforelse
                </div>

                <form x-show="edit" x-cloak method="POST" action="{{ route('customer.domains.nameservers.update', $domain) }}" class="space-y-4">
                    @csrf
                    <div class="space-y-3" x-data="{ nameservers: {{ json_encode(array_values($domain->current_nameservers ?? [['hostname' => '', 'ipv4' => '', 'ipv6' => '']])) }} }">
                        <template x-for="(ns, index) in nameservers" :key="index">
                            <div class="rounded-xl bg-slate-50 p-4">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div><label class="block text-xs font-medium text-slate-600">Hostname</label><input type="text" :name="`nameservers[${index}][hostname]`" x-model="ns.hostname" class="form-input mt-1" required></div>
                                    <div><label class="block text-xs font-medium text-slate-600">IPv4</label><input type="text" :name="`nameservers[${index}][ipv4]`" x-model="ns.ipv4" class="form-input mt-1"></div>
                                    <div><label class="block text-xs font-medium text-slate-600">IPv6</label><input type="text" :name="`nameservers[${index}][ipv6]`" x-model="ns.ipv6" class="form-input mt-1"></div>
                                </div>
                                <button type="button" @click="nameservers.splice(index, 1)" class="mt-3 text-xs font-semibold text-rose-600 hover:text-rose-800">Verwijderen</button>
                            </div>
                        </template>
                        <button type="button" @click="nameservers.push({hostname:'', ipv4:'', ipv6:''})" class="text-sm font-semibold text-primary-600 hover:text-primary-800">+ Nameserver toevoegen</button>
                    </div>
                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="btn btn-primary">Nameservers opslaan</button>
                        <button type="button" @click="edit = false" class="text-sm font-semibold text-slate-600 hover:text-slate-900">Annuleren</button>
                    </div>
                    <p class="text-xs text-slate-500">Wijzigingen worden eerst naar TransIP verstuurd; de status wordt bijgewerkt zodra TransIP de wijziging bevestigt.</p>
                </form>
            @endif
        </div>

        <!-- DNS -->
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70 mb-8">
            <h2 class="font-heading text-xl font-bold text-slate-900 mb-3">DNS &amp; nameservers</h2>
            @if($domain->is_dns_managed_by_servura)
                <p class="text-sm text-slate-500 mb-4">Domein gebruikt Servura-nameservers. DNS-records worden hier in een volgende fase beheerbaar.</p>
            @else
                <p class="text-sm text-slate-500 mb-4">Domein gebruikt externe nameservers. DNS-beheer vindt plaats bij de partij die de nameservers beheert.</p>
            @endif
        </div>

        <!-- Hosting -->
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70 mb-8">
            <h2 class="font-heading text-xl font-bold text-slate-900 mb-3">Hosting</h2>
            @if($domain->hostedCustomerService)
                <p class="text-sm text-slate-500">Gekoppeld aan <span class="font-semibold text-slate-900">{{ $domain->hostedCustomerService->service->title }}</span>.</p>
            @else
                <p class="text-sm text-slate-500">Er is nog geen Servura-hostingpakket aan dit domein gekoppeld. Deze koppeling komt in een volgende fase beschikbaar.</p>
            @endif
        </div>

        <!-- Forwarding -->
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70 mb-8">
            <h2 class="font-heading text-xl font-bold text-slate-900 mb-3">Doorsturen</h2>
            <p class="text-sm text-slate-500">URL-doorsturing is voorbereid in het model maar nog niet actief. De redirect-infrastructuur wordt pas ingeschakeld wanneer deze technisch beschikbaar is.</p>
        </div>

        <!-- Transfer -->
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70 mb-8">
            <h2 class="font-heading text-xl font-bold text-slate-900 mb-3">Verhuizen</h2>
            @if($requiresAuthCode)
                <p class="text-sm text-slate-500 mb-4">Voor deze extensie is een verhuiscode vereist om het domein naar een andere provider te verhuizen.</p>
                <form method="POST" action="{{ route('customer.domains.authcode', $domain) }}" class="max-w-md">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-slate-700">Bevestig je wachtwoord om de verhuiscode te tonen</label>
                        <input type="password" name="password" class="form-input mt-1" required>
                    </div>
                    <button type="submit" class="btn btn-secondary">Verhuiscode tonen</button>
                </form>
                @if(session('auth_code'))
                    <div class="mt-4 rounded-lg bg-slate-100 p-4">
                        <p class="text-xs text-slate-500 uppercase tracking-wide">Verhuiscode</p>
                        <p class="mt-1 select-all font-mono text-sm font-semibold text-slate-900">{{ session('auth_code') }}</p>
                    </div>
                @endif
            @else
                <p class="text-sm text-slate-500">Voor deze extensie is geen verhuiscode nodig. Neem contact op voor een externe verhuizing.</p>
            @endif
        </div>

        <!-- Product settings -->
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70">
            <h2 class="font-heading text-xl font-bold text-slate-900 mb-3">Productinstellingen</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                <div>
                    <dt class="text-slate-500">Automatische verlenging</dt>
                    <dd class="mt-1 font-medium text-slate-900">{{ $domain->auto_renew ? 'Ingeschakeld' : 'Uitgeschakeld' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Registrar lock</dt>
                    <dd class="mt-1 font-medium text-slate-900">{{ $domain->registrar_lock ? 'Actief' : 'Niet actief' }}</dd>
                </div>
            </div>
            <p class="mt-4 text-sm text-slate-500">Opzeggen en verdere productinstellingen worden in een volgende fase toegevoegd.</p>
        </div>
    </div>
</div>
@endsection
