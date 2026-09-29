@extends('layouts.app')

@section('title', $domain->domain_name . ' - Servura')

@section('content')
@include('customer.partials.topbar')

@php
$registrant = $holderContacts[0] ?? [];
@endphp

<div class="bg-slate-50 min-h-screen pt-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 pb-24">
        <!-- Page Header -->
        <div class="mb-6">
            <a href="{{ route('customer.domains.index') }}" class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-800">
                <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7-7-7 7-7"/></svg>
                Terug naar domeinen
            </a>
            <h1 class="mt-3 font-heading text-3xl font-bold text-slate-900">{{ $domain->domain_name }}</h1>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-xl bg-emerald-50 p-4 text-sm font-medium text-emerald-800 ring-1 ring-emerald-200">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 rounded-xl bg-red-50 p-4 text-sm font-medium text-red-800 ring-1 ring-red-200">{{ session('error') }}</div>
        @endif
        @if(session('info'))
            <div class="mb-6 rounded-xl bg-blue-50 p-4 text-sm font-medium text-blue-800 ring-1 ring-blue-200">{{ session('info') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Overview Card -->
            <div class="order-1 lg:order-2 lg:col-span-4">
                <div class="self-start lg:sticky lg:top-32 lg:z-10 lg:h-fit rounded-2xl bg-slate-900 p-6 sm:p-8 text-white shadow-xl shadow-slate-900/10 ring-1 ring-white/10" x-data="{ settingsOpen: false }">
                    <div class="flex items-start justify-between gap-4 mb-8">
                        <div class="min-w-0">
                            <div class="flex items-center gap-3 flex-wrap">
                                <h2 class="font-heading text-2xl font-bold text-white break-words">{{ $overview['domain_name'] }}</h2>
                                <span class="inline-flex items-center rounded-full bg-{{ $overview['status_color'] }}-100 px-3 py-1 text-xs font-semibold text-{{ $overview['status_color'] }}-800 shrink-0">{{ $overview['status'] }}</span>
                            </div>
                            <p class="mt-1 text-sm text-slate-300">{{ $overview['provider'] }} &middot; {{ $overview['registration_date'] }} tot {{ $overview['expiry_date'] }}</p>
                        </div>
                        <div class="relative shrink-0">
                            <button type="button" @click="settingsOpen = !settingsOpen" class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 text-white ring-1 ring-white/15 transition hover:bg-white/15" aria-label="Instellingen">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.483l-1.034.712c-.312.216-.472.61-.427.995.008.075.012.151.012.226 0 .075-.004.15-.012.225-.045.386.115.78.427.996l1.034.712a1.125 1.125 0 0 1 .26 1.483l-1.296 2.247a1.125 1.125 0 0 1-1.37.49l-1.217-.456c-.355-.133-.75-.072-1.075.124-.073.044-.146.087-.22.127-.332.184-.582.496-.645.87l-.213 1.28c-.09.543-.56.941-1.11.941h-2.593c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.063-.374-.313-.686-.645-.87a3.75 3.75 0 0 1-.22-.127c-.324-.196-.72-.257-1.075-.124l-1.217.456a1.125 1.125 0 0 1-1.37-.49l-1.296-2.247a1.125 1.125 0 0 1 .26-1.483l1.034-.712c.312-.216.472-.61.427-.995a3.75 3.75 0 0 1-.012-.226c0-.075.004-.15.012-.225.045-.386-.115-.78-.427-.996l-1.034-.712a1.125 1.125 0 0 1-.26-1.483l1.296-2.247a1.125 1.125 0 0 1 1.37-.49l1.217.456c.355.133.75.072 1.075-.124.073-.044.146-.087.22-.127.332-.184.582-.496.645-.87l.213-1.28Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                            </button>
                            <div x-show="settingsOpen" x-cloak @click.outside="settingsOpen = false" class="absolute right-0 z-20 mt-2 w-72 origin-top-right rounded-2xl bg-white p-2 shadow-xl ring-1 ring-slate-200">
                                <div class="px-3 py-2 text-xs font-semibold uppercase tracking-wider text-slate-500">Domeininstellingen</div>
                                <a href="{{ route('customer.domains.show', ['domain' => $domain, 'modal' => 'autorenew']) }}" @click.prevent="settingsOpen=false; window.location.hash='autorenew'" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm text-slate-700 hover:bg-slate-50">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                                    Automatische verlenging
                                </a>
                                <a href="{{ route('customer.domains.show', ['domain' => $domain, 'modal' => 'transfer-user']) }}" @click.prevent="settingsOpen=false; window.location.hash='transfer-user'" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm text-slate-700 hover:bg-slate-50">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/></svg>
                                    Overdragen naar gebruiker
                                </a>
                                <a href="{{ route('customer.domains.show', ['domain' => $domain, 'modal' => 'link-hosting']) }}" @click.prevent="settingsOpen=false; window.location.hash='link-hosting'" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm text-slate-700 hover:bg-slate-50">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/></svg>
                                    Hostingpakket koppelen
                                </a>
                                <a href="{{ route('customer.domains.show', ['domain' => $domain, 'modal' => 'unlink-hosting']) }}" @click.prevent="settingsOpen=false; window.location.hash='unlink-hosting'" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm text-slate-700 hover:bg-slate-50">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 1 1 9 0v3.75M3.75 21.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                                    Hosting ontkoppelen
                                </a>
                                <div class="my-1 h-px bg-slate-100"></div>
                                <a href="{{ route('customer.domains.show', ['domain' => $domain, 'modal' => 'cancel']) }}" @click.prevent="settingsOpen=false; window.location.hash='cancel'" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm text-rose-600 hover:bg-rose-50">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                    Product opzeggen
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between rounded-xl bg-white/5 px-4 py-3 ring-1 ring-white/10">
                            <span class="text-xs font-medium uppercase tracking-wider text-slate-400">Provider</span>
                            <span class="text-sm font-semibold text-white text-right ml-4">{{ $overview['provider'] }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl bg-white/5 px-4 py-3 ring-1 ring-white/10">
                            <span class="text-xs font-medium uppercase tracking-wider text-slate-400">Registratiedatum</span>
                            <span class="text-sm font-semibold text-white text-right ml-4">{{ $overview['registration_date'] }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl bg-white/5 px-4 py-3 ring-1 ring-white/10">
                            <span class="text-xs font-medium uppercase tracking-wider text-slate-400">Verloopt</span>
                            <span class="text-sm font-semibold text-white text-right ml-4">{{ $overview['expiry_date'] }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl bg-white/5 px-4 py-3 ring-1 ring-white/10">
                            <span class="text-xs font-medium uppercase tracking-wider text-slate-400">Automatisch verlengen</span>
                            <span class="text-sm font-semibold text-white text-right ml-4">{{ $overview['auto_renew'] ? 'Aan' : 'Uit' }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl bg-white/5 px-4 py-3 ring-1 ring-white/10">
                            <span class="text-xs font-medium uppercase tracking-wider text-slate-400">Registrar lock</span>
                            <span class="text-sm font-semibold text-white text-right ml-4">{{ $overview['registrar_lock'] ? 'Actief' : 'Niet actief' }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl bg-white/5 px-4 py-3 ring-1 ring-white/10">
                            <span class="text-xs font-medium uppercase tracking-wider text-slate-400">Hosting</span>
                            <span class="text-sm font-semibold text-white text-right ml-4">{{ $overview['hosting_package'] }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl bg-white/5 px-4 py-3 ring-1 ring-white/10">
                            <span class="text-xs font-medium uppercase tracking-wider text-slate-400">Nameservers</span>
                            <span class="text-sm font-semibold text-white text-right ml-4">{{ $overview['nameserver_status'] }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl bg-white/5 px-4 py-3 ring-1 ring-white/10">
                            <span class="text-xs font-medium uppercase tracking-wider text-slate-400">DNS</span>
                            <span class="text-sm font-semibold text-white text-right ml-4">{{ $overview['dns_status'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Settings modals -->
                <div x-data="{ hash: window.location.hash }" x-init="window.addEventListener('hashchange', () => hash = window.location.hash)" class="contents">
                    <template x-if="hash === '#autorenew'">
                        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(2,6,23,0.6)">
                            <form method="POST" action="{{ route('customer.domains.auto-renew', $domain) }}" class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-slate-200" @click.away="window.location.hash=''">
                                @csrf
                                <h3 class="font-heading text-lg font-bold text-slate-900">Automatische verlenging</h3>
                                <label class="mt-4 flex items-center gap-3">
                                    <input type="hidden" name="auto_renew" value="0">
                                    <input type="checkbox" name="auto_renew" value="1" class="h-5 w-5 rounded border-slate-300 text-primary-600" {{ $domain->auto_renew ? 'checked' : '' }}>
                                    <span class="text-sm text-slate-700">Automatische verlenging inschakelen</span>
                                </label>
                                <div class="mt-6 flex justify-end gap-3">
                                    <a href="#" class="text-sm font-semibold text-slate-600 hover:text-slate-900" @click.prevent="window.location.hash=''">Annuleren</a>
                                    <button type="submit" class="rounded-xl bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">Opslaan</button>
                                </div>
                            </form>
                        </div>
                    </template>

                    <template x-if="hash === '#transfer-user'">
                        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(2,6,23,0.6)">
                            <form method="POST" action="{{ route('customer.domains.transfer.request', $domain) }}" class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-slate-200" @click.away="window.location.hash=''">
                                @csrf
                                <h3 class="font-heading text-lg font-bold text-slate-900">Overdragen naar gebruiker</h3>
                                <p class="mt-2 text-sm text-slate-500">De ontvanger moet de overdracht accepteren. De registry-houder wordt hierdoor niet gewijzigd.</p>
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-slate-700">E-mailadres ontvanger</label>
                                    <input type="email" name="email" class="form-input mt-1 w-full" placeholder="klant@domein.nl" required>
                                </div>
                                <div class="mt-6 flex justify-end gap-3">
                                    <a href="#" class="text-sm font-semibold text-slate-600 hover:text-slate-900" @click.prevent="window.location.hash=''">Annuleren</a>
                                    <button type="submit" class="rounded-xl bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">Verstuur aanvraag</button>
                                </div>
                            </form>
                        </div>
                    </template>

                    <template x-if="hash === '#link-hosting'">
                        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(2,6,23,0.6)">
                            <form method="POST" action="{{ route('customer.domains.hosting.link', $domain) }}" class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-slate-200" @click.away="window.location.hash=''">
                                @csrf
                                <h3 class="font-heading text-lg font-bold text-slate-900">Hostingpakket koppelen</h3>
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-slate-700">Bestaand hostingpakket</label>
                                    <select name="customer_service_id" class="form-select mt-1 w-full" required>
                                        @forelse($availableHostingServices as $service)
                                            <option value="{{ $service['id'] }}">{{ $service['label'] }}</option>
                                        @empty
                                            <option value="" disabled>Geen beschikbare hostingpakketten</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="mt-6 flex justify-end gap-3">
                                    <a href="#" class="text-sm font-semibold text-slate-600 hover:text-slate-900" @click.prevent="window.location.hash=''">Annuleren</a>
                                    <button type="submit" class="rounded-xl bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700" {{ empty($availableHostingServices) ? 'disabled' : '' }}>Koppelen</button>
                                </div>
                            </form>
                        </div>
                    </template>

                    <template x-if="hash === '#unlink-hosting'">
                        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(2,6,23,0.6)">
                            <form method="POST" action="{{ route('customer.domains.hosting.unlink', $domain) }}" class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-slate-200" @click.away="window.location.hash=''">
                                @csrf
                                <h3 class="font-heading text-lg font-bold text-slate-900">Hosting ontkoppelen</h3>
                                <p class="mt-2 text-sm text-slate-500">De koppeling wordt verwijderd. Website- en e-maildata blijven op de server staan.</p>
                                <div class="mt-6 flex justify-end gap-3">
                                    <a href="#" class="text-sm font-semibold text-slate-600 hover:text-slate-900" @click.prevent="window.location.hash=''">Annuleren</a>
                                    <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">Ontkoppelen</button>
                                </div>
                            </form>
                        </div>
                    </template>

                    <template x-if="hash === '#cancel'">
                        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(2,6,23,0.6)">
                            <form method="POST" action="{{ route('customer.domains.cancel', $domain) }}" class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-slate-200" @click.away="window.location.hash=''">
                                @csrf
                                <h3 class="font-heading text-lg font-bold text-slate-900">Product opzeggen</h3>
                                <p class="mt-2 text-sm text-rose-700">Het domein wordt niet automatisch verlengd en blijft actief tot de huidige einddatum. Deze actie is deels destructief.</p>
                                <div class="mt-6 flex justify-end gap-3">
                                    <a href="#" class="text-sm font-semibold text-slate-600 hover:text-slate-900" @click.prevent="window.location.hash=''">Annuleren</a>
                                <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">Bevestigen</button>
                                </div>
                            </form>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Accordion sections -->
            <div class="order-2 lg:order-1 lg:col-span-8 space-y-4">

                <!-- Holder -->
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70" x-data="{ open: false, edit: false }">
                    <button type="button" @click="open = !open" class="flex w-full items-center justify-between">
                        <div class="flex items-center gap-4">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-primary-50 text-primary-600 ring-1 ring-primary-100">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.5-1.632Z"/></svg>
                            </span>
                            <div class="text-left">
                                <h3 class="font-heading text-lg font-bold text-slate-900">Houdergegevens</h3>
                                <p class="text-sm text-slate-500">{{ $registrant['first_name'] ?? '-' }} {{ $registrant['last_name'] ?? '' }} &middot; {{ $registrant['company_name'] ?? '' }}</p>
                            </div>
                        </div>
                        <svg class="h-5 w-5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                    </button>
                    <div x-show="open" class="pt-5">
                        <div x-show="!edit" class="space-y-4">
                            @forelse($holderContacts as $contact)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                    <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-medium uppercase tracking-wider text-slate-500">Type</p><p class="mt-1 font-semibold text-slate-900">{{ ucfirst($contact['type'] ?? 'contact') }}</p></div>
                                    <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-medium uppercase tracking-wider text-slate-500">Naam</p><p class="mt-1 font-semibold text-slate-900">{{ $contact['first_name'] ?? '' }} {{ $contact['last_name'] ?? '' }}</p></div>
                                    <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-medium uppercase tracking-wider text-slate-500">Bedrijfsnaam</p><p class="mt-1 font-semibold text-slate-900">{{ $contact['company_name'] ?? '-' }}</p></div>
                                    <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-medium uppercase tracking-wider text-slate-500">Adres</p><p class="mt-1 font-semibold text-slate-900">{{ $contact['street'] ?? '' }} {{ $contact['number'] ?? '' }}</p></div>
                                    <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-medium uppercase tracking-wider text-slate-500">Postcode &amp; plaats</p><p class="mt-1 font-semibold text-slate-900">{{ $contact['postal_code'] ?? '' }} {{ $contact['city'] ?? '' }}</p></div>
                                    <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-medium uppercase tracking-wider text-slate-500">Land</p><p class="mt-1 font-semibold text-slate-900">{{ $contact['country'] ?? '' }}</p></div>
                                    <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-medium uppercase tracking-wider text-slate-500">E-mail</p><p class="mt-1 font-semibold text-slate-900">{{ $contact['email'] ?? '' }}</p></div>
                                    <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-medium uppercase tracking-wider text-slate-500">Telefoonnummer</p><p class="mt-1 font-semibold text-slate-900">{{ $contact['phone_number'] ?? '-' }}</p></div>
                                </div>
                            @empty
                                <p class="text-sm text-slate-500">Geen houdergegevens gevonden. Klik op Vernieuwen om op te halen.</p>
                            @endforelse
                            <button type="button" @click="edit = true" class="btn btn-primary">Houdergegevens wijzigen</button>
                        </div>
                        <form x-show="edit" x-cloak method="POST" action="{{ route('customer.domains.holder.update', $domain) }}" class="space-y-4">
                            @csrf
                            @foreach($holderContacts as $i => $contact)
                                <div class="rounded-xl bg-slate-50 p-4">
                                    <input type="hidden" name="contacts[{{ $i }}][type]" value="{{ $contact['type'] ?? 'registrant' }}">
                                    <p class="text-sm font-semibold text-slate-900 mb-3">{{ ucfirst($contact['type'] ?? 'contact') }}</p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div><label class="block text-xs font-medium text-slate-600">Voornaam</label><input type="text" name="contacts[{{ $i }}][first_name]" value="{{ $contact['first_name'] ?? '' }}" class="form-input mt-1 w-full" required></div>
                                        <div><label class="block text-xs font-medium text-slate-600">Achternaam</label><input type="text" name="contacts[{{ $i }}][last_name]" value="{{ $contact['last_name'] ?? '' }}" class="form-input mt-1 w-full" required></div>
                                        <div><label class="block text-xs font-medium text-slate-600">Bedrijfsnaam</label><input type="text" name="contacts[{{ $i }}][company_name]" value="{{ $contact['company_name'] ?? '' }}" class="form-input mt-1 w-full"></div>
                                        <div><label class="block text-xs font-medium text-slate-600">KVK-nummer</label><input type="text" name="contacts[{{ $i }}][company_kvk]" value="{{ $contact['company_kvk'] ?? '' }}" class="form-input mt-1 w-full"></div>
                                        <div><label class="block text-xs font-medium text-slate-600">Straat</label><input type="text" name="contacts[{{ $i }}][street]" value="{{ $contact['street'] ?? '' }}" class="form-input mt-1 w-full" required></div>
                                        <div><label class="block text-xs font-medium text-slate-600">Huisnummer</label><input type="text" name="contacts[{{ $i }}][number]" value="{{ $contact['number'] ?? '' }}" class="form-input mt-1 w-full" required></div>
                                        <div><label class="block text-xs font-medium text-slate-600">Postcode</label><input type="text" name="contacts[{{ $i }}][postal_code]" value="{{ $contact['postal_code'] ?? '' }}" class="form-input mt-1 w-full" required></div>
                                        <div><label class="block text-xs font-medium text-slate-600">Plaats</label><input type="text" name="contacts[{{ $i }}][city]" value="{{ $contact['city'] ?? '' }}" class="form-input mt-1 w-full" required></div>
                                        <div><label class="block text-xs font-medium text-slate-600">Land (2-letterig)</label><input type="text" name="contacts[{{ $i }}][country]" value="{{ $contact['country'] ?? '' }}" maxlength="2" class="form-input mt-1 w-full" required></div>
                                        <div><label class="block text-xs font-medium text-slate-600">E-mail</label><input type="email" name="contacts[{{ $i }}][email]" value="{{ $contact['email'] ?? '' }}" class="form-input mt-1 w-full" required></div>
                                        <div><label class="block text-xs font-medium text-slate-600">Telefoon</label><input type="text" name="contacts[{{ $i }}][phone_number]" value="{{ $contact['phone_number'] ?? '' }}" class="form-input mt-1 w-full"></div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="flex items-center gap-3">
                                <button type="submit" class="btn btn-primary">Wijzigingen doorvoeren</button>
                                <button type="button" @click="edit = false" class="text-sm font-semibold text-slate-600 hover:text-slate-900">Annuleren</button>
                            </div>
                            <p class="text-xs text-slate-500">Wijzigingen worden eerst naar de provider verstuurd; de status wordt bijgewerkt zodra de provider de wijziging bevestigt.</p>
                        </form>
                    </div>
                </div>

                <!-- Nameservers -->
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70" x-data="{ open: false, edit: false, nameservers: {{ json_encode($nameservers) }} }">
                    <button type="button" @click="open = !open" class="flex w-full items-center justify-between">
                        <div class="flex items-center gap-4">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 ring-1 ring-indigo-100">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.984 0-7.546-1.21-9.79-3.168m0 0A8.961 8.961 0 0 1 3 12c0-.778.099-1.533.284-2.253m0 0A17.919 17.919 0 0 1 12 7.5c3.984 0 7.546 1.21 9.79 3.168Z"/></svg>
                            </span>
                            <div class="text-left">
                                <h3 class="font-heading text-lg font-bold text-slate-900">Nameservers</h3>
                                <p class="text-sm text-slate-500">{{ collect($nameservers)->pluck('hostname')->implode(', ') ?: 'Niet gesynchroniseerd' }}</p>
                            </div>
                        </div>
                        <svg class="h-5 w-5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                    </button>
                    <div x-show="open" class="pt-5">
                        <div x-show="!edit" class="space-y-2">
                            @forelse($nameservers as $ns)
                                <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3 text-sm">
                                    <span class="font-medium text-slate-900">{{ $ns['hostname'] }}</span>
                                    <span class="text-slate-500">{{ $ns['ipv4'] ?? '' }} {{ $ns['ipv6'] ?? '' }}</span>
                                </div>
                            @empty
                                <p class="text-sm text-slate-500">Geen nameservers gesynchroniseerd.</p>
                            @endforelse
                            <button type="button" @click="edit = true" class="mt-3 btn btn-secondary">Nameservers wijzigen</button>
                        </div>
                        <form x-show="edit" x-cloak method="POST" action="{{ route('customer.domains.nameservers.update', $domain) }}" class="space-y-3">
                            @csrf
                            <template x-for="(n, index) in nameservers" :key="index">
                                <div class="rounded-xl bg-slate-50 p-4">
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <div><label class="block text-xs font-medium text-slate-600">Hostname</label><input type="text" :name="`nameservers[${index}][hostname]`" x-model="n.hostname" class="form-input mt-1 w-full" required></div>
                                        <div><label class="block text-xs font-medium text-slate-600">IPv4</label><input type="text" :name="`nameservers[${index}][ipv4]`" x-model="n.ipv4" class="form-input mt-1 w-full"></div>
                                        <div><label class="block text-xs font-medium text-slate-600">IPv6</label><input type="text" :name="`nameservers[${index}][ipv6]`" x-model="n.ipv6" class="form-input mt-1 w-full"></div>
                                    </div>
                                    <button type="button" @click="nameservers.splice(index, 1)" class="mt-3 text-xs font-semibold text-rose-600 hover:text-rose-800">Verwijderen</button>
                                </div>
                            </template>
                            <button type="button" @click="nameservers.push({hostname:'', ipv4:null, ipv6:null})" class="text-sm font-semibold text-primary-600 hover:text-primary-800">+ Nameserver toevoegen</button>
                            <div class="flex items-center gap-3 pt-2">
                                <button type="submit" class="btn btn-primary">Nameservers opslaan</button>
                                <button type="button" @click="edit = false" class="text-sm font-semibold text-slate-600 hover:text-slate-900">Annuleren</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- DNS Records -->
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70" x-data="{ open: false, edit: false, records: {{ json_encode($dnsRecords) }} }">
                    <button type="button" @click="open = !open" class="flex w-full items-center justify-between">
                        <div class="flex items-center gap-4">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5 10.25 7l4.5 6 6-7.5M3.75 7l6.75 6 4.5-6 6 7.5"/></svg>
                            </span>
                            <div class="text-left">
                                <h3 class="font-heading text-lg font-bold text-slate-900">DNS-records</h3>
                                <p class="text-sm text-slate-500">{{ count($dnsRecords) }} records &middot; {{ $overview['dns_status'] }}</p>
                            </div>
                        </div>
                        <svg class="h-5 w-5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                    </button>
                    <div x-show="open" class="pt-5">
                        @if(! $dnsManaged)
                            <p class="text-sm text-slate-500">Dit domein gebruikt externe nameservers. DNS wordt daarom niet via Servura beheerd.</p>
                        @else
                            <div x-show="!edit" class="overflow-x-auto -mx-6 sm:mx-0 rounded-xl ring-1 ring-slate-200">
                                <table class="min-w-full divide-y divide-slate-100">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Type</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Naam</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Waarde</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">TTL</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        @forelse($dnsRecords as $entry)
                                            <tr class="text-sm">
                                                <td class="px-4 py-3 font-medium text-slate-900">{{ $entry['type'] }}</td>
                                                <td class="px-4 py-3 text-slate-600">{{ $entry['name'] }}</td>
                                                <td class="px-4 py-3 font-mono text-slate-900">{{ $entry['content'] }}</td>
                                                <td class="px-4 py-3 text-slate-600">{{ $entry['expire'] }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="px-4 py-8 text-center text-sm text-slate-500">Geen DNS-records gevonden.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" @click="edit = true" class="mt-5 btn btn-primary">DNS-records beheren</button>

                            <form x-show="edit" x-cloak method="POST" action="{{ route('customer.domains.dns.update', $domain) }}" class="space-y-3">
                                @csrf
                                <template x-for="(record, index) in records" :key="index">
                                    <div class="rounded-xl bg-slate-50 p-4">
                                        <div class="grid grid-cols-1 sm:grid-cols-5 gap-4">
                                            <div class="sm:col-span-2"><label class="block text-xs font-medium text-slate-600">Naam</label><input type="text" :name="`records[${index}][name]`" x-model="record.name" class="form-input mt-1 w-full" required></div>
                                            <div><label class="block text-xs font-medium text-slate-600">Type</label><select :name="`records[${index}][type]`" x-model="record.type" class="form-select mt-1 w-full" required>
                                                <option value="A">A</option>
                                                <option value="AAAA">AAAA</option>
                                                <option value="CNAME">CNAME</option>
                                                <option value="MX">MX</option>
                                                <option value="TXT">TXT</option>
                                                <option value="SRV">SRV</option>
                                                <option value="CAA">CAA</option>
                                                <option value="NS">NS</option>
                                            </select></div>
                                            <div><label class="block text-xs font-medium text-slate-600">TTL</label><input type="number" :name="`records[${index}][expire]`" x-model="record.expire" class="form-input mt-1 w-full" min="60" required></div>
                                            <div class="sm:col-span-5"><label class="block text-xs font-medium text-slate-600">Waarde</label><input type="text" :name="`records[${index}][content]`" x-model="record.content" class="form-input mt-1 w-full" required></div>
                                        </div>
                                        <button type="button" @click="records.splice(index, 1)" class="mt-3 text-xs font-semibold text-rose-600 hover:text-rose-800">Record verwijderen</button>
                                    </div>
                                </template>
                                <button type="button" @click="records.push({name:'', type:'A', expire:3600, content:''})" class="text-sm font-semibold text-primary-600 hover:text-primary-800">+ Record toevoegen</button>
                                <div class="flex items-center gap-3 pt-2">
                                    <button type="submit" class="btn btn-primary">DNS-records opslaan</button>
                                    <button type="button" @click="edit = false" class="text-sm font-semibold text-slate-600 hover:text-slate-900">Annuleren</button>
                                </div>
                                <p class="text-xs text-slate-500">De volledige set records wordt naar de provider verstuurd en na verwerking opnieuw opgehaald.</p>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Hosting -->
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70" x-data="{ open: false }">
                    <button type="button" @click="open = !open" class="flex w-full items-center justify-between">
                        <div class="flex items-center gap-4">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600 ring-1 ring-amber-100">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.636m-13.636 0-2.1 2.1m2.1-2.1 2.1-2.1m13.636 2.1 2.1 2.1m-2.1-2.1-2.1-2.1M6.75 18.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-10.5a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v10.5a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                            </span>
                            <div class="text-left">
                                <h3 class="font-heading text-lg font-bold text-slate-900">Hosting</h3>
                                <p class="text-sm text-slate-500">{{ $hosting['linked'] ? $hosting['package'] . ' &middot; ' . $hosting['status'] : 'Geen hosting gekoppeld' }}</p>
                            </div>
                        </div>
                        <svg class="h-5 w-5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                    </button>
                    <div x-show="open" class="pt-5">
                        @if($hosting['linked'])
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-medium uppercase tracking-wider text-slate-500">Pakket</p><p class="mt-1 font-semibold text-slate-900">{{ $hosting['package'] }}</p></div>
                                <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-medium uppercase tracking-wider text-slate-500">Status</p><p class="mt-1 font-semibold text-slate-900">{{ $hosting['status'] }}</p></div>
                                <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-medium uppercase tracking-wider text-slate-500">Server</p><p class="mt-1 font-semibold text-slate-900">{{ $hosting['server'] }}</p></div>
                                <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-medium uppercase tracking-wider text-slate-500">Hoofddomein</p><p class="mt-1 font-semibold text-slate-900">{{ $hosting['main_domain'] }}</p></div>
                            </div>
                            <div class="mt-5 flex flex-wrap gap-3">
                                <form method="POST" action="{{ route('customer.services.directadmin-login', $hosting['customer_service_id']) }}" target="_blank">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Open DirectAdmin</button>
                                </form>
                                <a href="#link-hosting" @click.prevent="window.location.hash='link-hosting'" class="btn btn-secondary">Hostingpakket wijzigen</a>
                                <form method="POST" action="{{ route('customer.domains.hosting.unlink', $domain) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary !text-rose-600 !border-rose-600 hover:!bg-rose-50 hover:!text-rose-700 focus:!ring-rose-500">Hosting ontkoppelen</button>
                                </form>
                            </div>
                        @else
                            <p class="text-sm text-slate-500 mb-4">Er is nog geen hostingpakket gekoppeld aan dit domein.</p>
                            <div class="flex flex-wrap gap-3">
                                <a href="#link-hosting" @click.prevent="window.location.hash='link-hosting'" class="btn btn-secondary">Bestaand hostingpakket koppelen</a>
                                <a href="{{ route('services.index') }}" class="btn btn-primary">Nieuw hostingpakket bestellen</a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Forwarding -->
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70" x-data="{ open: false }">
                    <button type="button" @click="open = !open" class="flex w-full items-center justify-between">
                        <div class="flex items-center gap-4">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-rose-50 text-rose-600 ring-1 ring-rose-100">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                            </span>
                            <div class="text-left">
                                <h3 class="font-heading text-lg font-bold text-slate-900">Doorsturen</h3>
                                <p class="text-sm text-slate-500">Nog niet geconfigureerd</p>
                            </div>
                        </div>
                        <svg class="h-5 w-5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                    </button>
                    <div x-show="open" class="pt-5">
                        <p class="text-sm text-slate-500">URL-doorsturing is voorbereid in het model maar nog niet actief.</p>
                    </div>
                </div>

                <!-- Transfer token -->
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70" x-data="{ open: false }">
                    <button type="button" @click="open = !open" class="flex w-full items-center justify-between">
                        <div class="flex items-center gap-4">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-50 text-cyan-600 ring-1 ring-cyan-100">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/></svg>
                            </span>
                            <div class="text-left">
                                <h3 class="font-heading text-lg font-bold text-slate-900">Verhuizen</h3>
                                <p class="text-sm text-slate-500">Verhuistoken naar andere registrar</p>
                            </div>
                        </div>
                        <svg class="h-5 w-5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                    </button>
                    <div x-show="open" class="pt-5">
                        <p class="text-sm text-slate-500 mb-4">Gebruik dit token om het domein vanuit Servura naar een andere registrar te verhuizen.</p>
                        @if(session('auth_code'))
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3" x-data="{ copied: false }">
                                <div class="relative flex-1 rounded-xl bg-slate-900 px-4 py-3 font-mono text-sm text-white tracking-widest select-all" id="auth-code">{{ session('auth_code') }}</div>
                                <button type="button" @click="navigator.clipboard.writeText(document.getElementById('auth-code').innerText); copied=true; setTimeout(() => copied=false, 2000)" class="rounded-xl bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">Kopiëren</button>
                                <p x-show="copied" x-cloak class="text-sm font-medium text-emerald-600">Gekopieerd!</p>
                            </div>
                        @else
                            <form method="POST" action="{{ route('customer.domains.authcode', $domain) }}" class="max-w-md">
                                @csrf
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-slate-700">Bevestig je wachtwoord om de verhuiscode te tonen</label>
                                    <input type="password" name="password" class="form-input mt-1 w-full" required>
                                </div>
                                <button type="submit" class="btn btn-secondary">Verhuistoken tonen</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
