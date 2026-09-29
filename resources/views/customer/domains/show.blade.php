@extends('layouts.app')

@section('title', $domain->domain_name . ' - Servura')

@section('content')
@include('customer.partials.topbar')

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

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Overview Card (sticky on desktop, top on mobile) -->
            <div class="order-1 lg:order-2 lg:col-span-4">
                <div class="self-start lg:sticky lg:top-32 lg:z-10 lg:h-fit rounded-2xl bg-slate-900 p-6 sm:p-8 text-white shadow-xl shadow-slate-900/10 ring-1 ring-white/10" x-data="{ settingsOpen: false, modal: null, toast: null }" @keydown.escape.window="settingsOpen = false; modal = null">
            <div class="flex items-start justify-between gap-4 mb-8">
                <div>
                    <div class="flex items-center gap-3">
                        <h2 class="font-heading text-2xl font-bold text-white">{{ $overview['domain_name'] }}</h2>
                        <span class="inline-flex items-center rounded-full bg-{{ $overview['status_color'] }}-100 px-3 py-1 text-xs font-semibold text-{{ $overview['status_color'] }}-800">{{ $overview['status'] }}</span>
                    </div>
                    <p class="mt-1 text-sm text-slate-300">{{ $overview['provider'] }} &middot; {{ $overview['registration_date'] }} tot {{ $overview['expiry_date'] }}</p>
                </div>
                <div class="relative">
                    <button type="button" @click="settingsOpen = !settingsOpen" class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 text-white ring-1 ring-white/15 transition hover:bg-white/15" aria-label="Instellingen">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.483l-1.034.712c-.312.216-.472.61-.427.995.008.075.012.151.012.226 0 .075-.004.15-.012.225-.045.386.115.78.427.996l1.034.712a1.125 1.125 0 0 1 .26 1.483l-1.296 2.247a1.125 1.125 0 0 1-1.37.49l-1.217-.456c-.355-.133-.75-.072-1.075.124-.073.044-.146.087-.22.127-.332.184-.582.496-.645.87l-.213 1.28c-.09.543-.56.941-1.11.941h-2.593c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.063-.374-.313-.686-.645-.87a3.75 3.75 0 0 1-.22-.127c-.324-.196-.72-.257-1.075-.124l-1.217.456a1.125 1.125 0 0 1-1.37-.49l-1.296-2.247a1.125 1.125 0 0 1 .26-1.483l1.034-.712c.312-.216.472-.61.427-.995a3.75 3.75 0 0 1-.012-.226c0-.075.004-.15.012-.225.045-.386-.115-.78-.427-.996l-1.034-.712a1.125 1.125 0 0 1-.26-1.483l1.296-2.247a1.125 1.125 0 0 1 1.37-.49l1.217.456c.355.133.75.072 1.075-.124.073-.044.146-.087.22-.127.332-.184.582-.496.645-.87l.213-1.28Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                    </button>
                    <div x-show="settingsOpen" x-cloak @click.outside="settingsOpen = false" class="absolute right-0 z-20 mt-2 w-72 origin-top-right rounded-2xl bg-white p-2 shadow-xl ring-1 ring-slate-200">
                        <div class="px-3 py-2 text-xs font-semibold uppercase tracking-wider text-slate-500">Domeininstellingen</div>
                        <button type="button" @click="settingsOpen=false; modal='autorenew'" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm text-slate-700 hover:bg-slate-50">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                            Automatische verlenging
                        </button>
                        <button type="button" @click="settingsOpen=false; modal='transfer-user'" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm text-slate-700 hover:bg-slate-50">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/></svg>
                            Overdragen naar gebruiker
                        </button>
                        <button type="button" @click="settingsOpen=false; modal='link-hosting'" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm text-slate-700 hover:bg-slate-50">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/></svg>
                            Hostingpakket koppelen
                        </button>
                        <button type="button" @click="settingsOpen=false; modal='unlink-hosting'" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm text-slate-700 hover:bg-slate-50">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 1 1 9 0v3.75M3.75 21.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                            Hosting ontkoppelen
                        </button>
                        <div class="my-1 h-px bg-slate-100"></div>
                        <button type="button" @click="settingsOpen=false; modal='cancel'" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm text-rose-600 hover:bg-rose-50">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                            Product opzeggen
                        </button>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between rounded-xl bg-white/5 px-4 py-3 ring-1 ring-white/10">
                    <span class="text-xs font-medium uppercase tracking-wider text-slate-400">Provider</span>
                    <span class="text-sm font-semibold text-white">{{ $overview['provider'] }}</span>
                </div>
                <div class="flex items-center justify-between rounded-xl bg-white/5 px-4 py-3 ring-1 ring-white/10">
                    <span class="text-xs font-medium uppercase tracking-wider text-slate-400">Registratiedatum</span>
                    <span class="text-sm font-semibold text-white">{{ $overview['registration_date'] }}</span>
                </div>
                <div class="flex items-center justify-between rounded-xl bg-white/5 px-4 py-3 ring-1 ring-white/10">
                    <span class="text-xs font-medium uppercase tracking-wider text-slate-400">Verloopt</span>
                    <span class="text-sm font-semibold text-white">{{ $overview['expiry_date'] }}</span>
                </div>
                <div class="flex items-center justify-between rounded-xl bg-white/5 px-4 py-3 ring-1 ring-white/10">
                    <span class="text-xs font-medium uppercase tracking-wider text-slate-400">Automatisch verlengen</span>
                    <span class="text-sm font-semibold text-white">{{ $overview['auto_renew'] ? 'Aan' : 'Uit' }}</span>
                </div>
                <div class="flex items-center justify-between rounded-xl bg-white/5 px-4 py-3 ring-1 ring-white/10">
                    <span class="text-xs font-medium uppercase tracking-wider text-slate-400">Registrar lock</span>
                    <span class="text-sm font-semibold text-white">{{ $overview['registrar_lock'] ? 'Actief' : 'Niet actief' }}</span>
                </div>
                <div class="flex items-center justify-between rounded-xl bg-white/5 px-4 py-3 ring-1 ring-white/10">
                    <span class="text-xs font-medium uppercase tracking-wider text-slate-400">Hosting</span>
                    <span class="text-sm font-semibold text-white">{{ $overview['hosting_package'] }}</span>
                </div>
                <div class="flex items-center justify-between rounded-xl bg-white/5 px-4 py-3 ring-1 ring-white/10">
                    <span class="text-xs font-medium uppercase tracking-wider text-slate-400">Nameservers</span>
                    <span class="text-sm font-semibold text-white">{{ $overview['nameserver_status'] }}</span>
                </div>
                <div class="flex items-center justify-between rounded-xl bg-white/5 px-4 py-3 ring-1 ring-white/10">
                    <span class="text-xs font-medium uppercase tracking-wider text-slate-400">DNS</span>
                    <span class="text-sm font-semibold text-white">{{ $overview['dns_status'] }}</span>
                </div>
            </div>

            <!-- Toast -->
            <div x-show="toast" x-cloak x-transition class="mt-6 rounded-lg bg-emerald-500/20 p-3 text-sm font-medium text-emerald-100 ring-1 ring-emerald-500/30" x-text="toast"></div>

            <!-- Settings Modals -->
            <template x-if="modal">
                <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(2,6,23,0.6)">
                    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-slate-200" @click.away="modal=null">
                        <h3 class="font-heading text-lg font-bold text-slate-900" x-text="{
                            'autorenew': 'Automatische verlenging',
                            'transfer-user': 'Overdragen naar andere Servura-gebruiker',
                            'link-hosting': 'Hostingpakket koppelen',
                            'unlink-hosting': 'Hosting ontkoppelen',
                            'cancel': 'Product opzeggen'
                        }[modal]"></h3>

                        <!-- Auto renew -->
                        <div x-show="modal === 'autorenew'" class="mt-4">
                            <label class="flex items-center gap-3">
                                <input type="checkbox" checked class="h-5 w-5 rounded border-slate-300 text-primary-600" x-ref="autoRenewCheck">
                                <span class="text-sm text-slate-700">Automatische verlenging inschakelen</span>
                            </label>
                        </div>

                        <!-- Transfer user -->
                        <div x-show="modal === 'transfer-user'" class="mt-4 space-y-3">
                            <p class="text-sm text-slate-500">Vul het e-mailadres in van de bestaande Servura-gebruiker die dit domein moet overnemen.</p>
                            <input type="email" class="form-input w-full" placeholder="e-mailadres@domein.nl">
                        </div>

                        <!-- Link hosting -->
                        <div x-show="modal === 'link-hosting'" class="mt-4 space-y-3">
                            <label class="block text-sm font-medium text-slate-700">Bestaand hostingpakket</label>
                            <select class="form-select w-full">
                                <option>Webhosting Start</option>
                                <option>Webhosting Plus</option>
                                <option>Webhosting Pro</option>
                            </select>
                        </div>

                        <!-- Unlink hosting -->
                        <div x-show="modal === 'unlink-hosting'" class="mt-4">
                            <p class="text-sm text-slate-500">De koppeling wordt verwijderd. Website- en e-maildata blijven op de server staan.</p>
                        </div>

                        <!-- Cancel -->
                        <div x-show="modal === 'cancel'" class="mt-4 space-y-3">
                            <p class="text-sm text-rose-700">Dit zet het domein op niet-verlengen. Deze actie is deels destructief.</p>
                            <label class="block text-sm font-medium text-slate-700">Typ <span class="font-mono font-semibold">{{ $domain->domain_name }}</span> ter bevestiging</label>
                            <input type="text" class="form-input w-full" placeholder="{{ $domain->domain_name }}">
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-3">
                            <button type="button" @click="modal=null" class="text-sm font-semibold text-slate-600 hover:text-slate-900">Annuleren</button>
                            <button type="button" @click="toast='Actie opgeslagen (dummy)'; modal=null" class="rounded-xl px-4 py-2 text-sm font-semibold text-white" :class="modal === 'cancel' ? 'bg-rose-600 hover:bg-rose-700' : 'bg-primary-600 hover:bg-primary-700'">Bevestigen</button>
                        </div>
                    </div>
                </div>
            </template>
                </div>
            </div>

            <!-- Accordion sections -->
            <div class="order-2 lg:order-1 lg:col-span-8 space-y-4">

                <!-- Accordion: Holder -->
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70" x-data="{ open: false, modal: false }">
            <button type="button" @click="open = !open" class="flex w-full items-center justify-between">
                <div class="flex items-center gap-4">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-primary-50 text-primary-600 ring-1 ring-primary-100">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.5-1.632Z"/></svg>
                    </span>
                    <div class="text-left">
                        <h3 class="font-heading text-lg font-bold text-slate-900">Houdergegevens</h3>
                        <p class="text-sm text-slate-500">{{ $holder['first_name'] }} {{ $holder['last_name'] }} &middot; {{ $holder['company_name'] }}</p>
                    </div>
                </div>
                <svg class="h-5 w-5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </button>
            <div x-show="open" class="pt-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-medium uppercase tracking-wider text-slate-500">Type houder</p><p class="mt-1 font-semibold text-slate-900">{{ $holder['type'] }}</p></div>
                    <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-medium uppercase tracking-wider text-slate-500">Naam</p><p class="mt-1 font-semibold text-slate-900">{{ $holder['first_name'] }} {{ $holder['last_name'] }}</p></div>
                    <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-medium uppercase tracking-wider text-slate-500">Bedrijfsnaam</p><p class="mt-1 font-semibold text-slate-900">{{ $holder['company_name'] }}</p></div>
                    <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-medium uppercase tracking-wider text-slate-500">Adres</p><p class="mt-1 font-semibold text-slate-900">{{ $holder['street'] }} {{ $holder['number'] }}</p></div>
                    <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-medium uppercase tracking-wider text-slate-500">Postcode &amp; plaats</p><p class="mt-1 font-semibold text-slate-900">{{ $holder['postal_code'] }} {{ $holder['city'] }}</p></div>
                    <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-medium uppercase tracking-wider text-slate-500">Land</p><p class="mt-1 font-semibold text-slate-900">{{ $holder['country'] }}</p></div>
                    <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-medium uppercase tracking-wider text-slate-500">E-mail</p><p class="mt-1 font-semibold text-slate-900">{{ $holder['email'] }}</p></div>
                    <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-medium uppercase tracking-wider text-slate-500">Telefoonnummer</p><p class="mt-1 font-semibold text-slate-900">{{ $holder['phone_number'] }}</p></div>
                </div>
                <button type="button" @click="modal=true" class="mt-5 btn btn-primary">Houdergegevens wijzigen</button>

                <template x-if="modal">
                    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(2,6,23,0.6)">
                        <div class="w-full max-w-2xl rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-slate-200" @click.away="modal=false">
                            <h4 class="font-heading text-lg font-bold text-slate-900">Houdergegevens wijzigen</h4>
                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div><label class="block text-xs font-medium text-slate-600">Voornaam</label><input type="text" value="{{ $holder['first_name'] }}" class="form-input mt-1 w-full"></div>
                                <div><label class="block text-xs font-medium text-slate-600">Achternaam</label><input type="text" value="{{ $holder['last_name'] }}" class="form-input mt-1 w-full"></div>
                                <div><label class="block text-xs font-medium text-slate-600">Bedrijfsnaam</label><input type="text" value="{{ $holder['company_name'] }}" class="form-input mt-1 w-full"></div>
                                <div><label class="block text-xs font-medium text-slate-600">Straat</label><input type="text" value="{{ $holder['street'] }}" class="form-input mt-1 w-full"></div>
                                <div><label class="block text-xs font-medium text-slate-600">Huisnummer</label><input type="text" value="{{ $holder['number'] }}" class="form-input mt-1 w-full"></div>
                                <div><label class="block text-xs font-medium text-slate-600">Postcode</label><input type="text" value="{{ $holder['postal_code'] }}" class="form-input mt-1 w-full"></div>
                                <div><label class="block text-xs font-medium text-slate-600">Plaats</label><input type="text" value="{{ $holder['city'] }}" class="form-input mt-1 w-full"></div>
                                <div><label class="block text-xs font-medium text-slate-600">Land</label><input type="text" value="{{ $holder['country'] }}" class="form-input mt-1 w-full"></div>
                                <div><label class="block text-xs font-medium text-slate-600">E-mail</label><input type="email" value="{{ $holder['email'] }}" class="form-input mt-1 w-full"></div>
                                <div><label class="block text-xs font-medium text-slate-600">Telefoon</label><input type="text" value="{{ $holder['phone_number'] }}" class="form-input mt-1 w-full"></div>
                            </div>
                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button" @click="modal=false" class="text-sm font-semibold text-slate-600 hover:text-slate-900">Annuleren</button>
                                <button type="button" @click="modal=false; open=false; alert('Houdergegevens opgeslagen (dummy)')" class="rounded-xl bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">Opslaan</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Accordion: Nameservers -->
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70 mb-4" x-data="{ open: false, modal: false }">
            <button type="button" @click="open = !open" class="flex w-full items-center justify-between">
                <div class="flex items-center gap-4">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 ring-1 ring-indigo-100">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.984 0-7.546-1.21-9.79-3.168m0 0A8.961 8.961 0 0 1 3 12c0-.778.099-1.533.284-2.253m0 0A17.919 17.919 0 0 1 12 7.5c3.984 0 7.546 1.21 9.79 3.168Z"/></svg>
                    </span>
                    <div class="text-left">
                        <h3 class="font-heading text-lg font-bold text-slate-900">Nameservers</h3>
                        <p class="text-sm text-slate-500">{{ collect($nameservers)->pluck('hostname')->implode(', ') }}</p>
                    </div>
                </div>
                <svg class="h-5 w-5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </button>
            <div x-show="open" class="pt-5">
                <div class="space-y-2">
                    @foreach($nameservers as $ns)
                        <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3 text-sm">
                            <span class="font-medium text-slate-900">{{ $ns['hostname'] }}</span>
                            <span class="text-slate-500">{{ $ns['ipv4'] ?? '' }} {{ $ns['ipv6'] ?? '' }}</span>
                        </div>
                    @endforeach
                </div>
                <button type="button" @click="modal=true" class="mt-5 btn btn-secondary">Nameservers wijzigen</button>

                <template x-if="modal">
                    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(2,6,23,0.6)">
                        <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-slate-200" @click.away="modal=false" x-data="{ ns: {{ json_encode($nameservers) }} }">
                            <h4 class="font-heading text-lg font-bold text-slate-900">Nameservers wijzigen</h4>
                            <div class="mt-4 space-y-3">
                                <template x-for="(n, index) in ns" :key="index">
                                    <div class="flex gap-2">
                                        <input type="text" x-model="n.hostname" class="form-input flex-1" placeholder="Hostname">
                                        <button type="button" @click="ns.splice(index,1)" class="text-rose-600 hover:text-rose-800 text-sm">Verwijder</button>
                                    </div>
                                </template>
                                <button type="button" @click="ns.push({hostname:'', ipv4:null, ipv6:null})" class="text-sm font-semibold text-primary-600 hover:text-primary-800">+ Nameserver toevoegen</button>
                            </div>
                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button" @click="modal=false" class="text-sm font-semibold text-slate-600 hover:text-slate-900">Annuleren</button>
                                <button type="button" @click="modal=false; open=false; alert('Nameservers opgeslagen (dummy)')" class="rounded-xl bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">Opslaan</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Accordion: DNS Records -->
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70 mb-4" x-data="{ open: false, modal: false }">
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
                <div class="overflow-x-auto -mx-6 sm:mx-0 rounded-xl ring-1 ring-slate-200">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Naam</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Waarde</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">TTL</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase">Actie</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach($dnsRecords as $i => $record)
                                <tr class="text-sm">
                                    <td class="px-4 py-3 font-medium text-slate-900">{{ $record['type'] }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $record['name'] }}</td>
                                    <td class="px-4 py-3 font-mono text-slate-900">{{ $record['content'] }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $record['expire'] }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <button type="button" @click="modal='edit-{{ $i }}'" class="text-xs font-semibold text-primary-600 hover:text-primary-800">Wijzigen</button>
                                        <button type="button" @click="confirm('Record verwijderen (dummy)')" class="ml-3 text-xs font-semibold text-rose-600 hover:text-rose-800">Verwijderen</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="button" @click="modal='add'" class="mt-5 btn btn-primary">+ Record toevoegen</button>

                <!-- Add/Edit modals (simplified: one generic modal controlled by string) -->
                <template x-if="modal === 'add' || modal && modal.startsWith('edit-')">
                    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(2,6,23,0.6)">
                        <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-slate-200" @click.away="modal=false">
                            <h4 class="font-heading text-lg font-bold text-slate-900" x-text="modal === 'add' ? 'DNS-record toevoegen' : 'DNS-record wijzigen'"></h4>
                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div><label class="block text-xs font-medium text-slate-600">Type</label><select class="form-select mt-1 w-full"><option>A</option><option>AAAA</option><option>CNAME</option><option>MX</option><option>TXT</option><option>SRV</option><option>CAA</option><option>NS</option></select></div>
                                <div><label class="block text-xs font-medium text-slate-600">Naam</label><input type="text" class="form-input mt-1 w-full" placeholder="@ of www"></div>
                                <div><label class="block text-xs font-medium text-slate-600">TTL</label><input type="number" class="form-input mt-1 w-full" value="300"></div>
                                <div class="sm:col-span-2"><label class="block text-xs font-medium text-slate-600">Waarde</label><input type="text" class="form-input mt-1 w-full" placeholder="IP, doel of tekst"></div>
                            </div>
                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button" @click="modal=false" class="text-sm font-semibold text-slate-600 hover:text-slate-900">Annuleren</button>
                                <button type="button" @click="modal=false; alert('DNS-record opgeslagen (dummy)')" class="rounded-xl bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">Opslaan</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Accordion: Hosting -->
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70 mb-4" x-data="{ open: false, modal: false }">
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
                        <button type="button" class="btn btn-primary">Open DirectAdmin</button>
                        <button type="button" @click="modal='change'" class="btn btn-secondary">Hostingpakket wijzigen</button>
                        <button type="button" @click="modal='unlink'" class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-rose-600 ring-1 ring-rose-200 hover:bg-rose-50">Hosting ontkoppelen</button>
                    </div>
                @else
                    <p class="text-sm text-slate-500 mb-4">Er is nog geen hostingpakket gekoppeld aan dit domein.</p>
                    <div class="flex flex-wrap gap-3">
                        <button type="button" @click="modal='link-existing'" class="btn btn-secondary">Bestaand hostingpakket koppelen</button>
                        <a href="{{ route('services.index') }}" class="btn btn-primary">Nieuw hostingpakket bestellen</a>
                    </div>
                @endif

                <template x-if="modal">
                    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(2,6,23,0.6)">
                        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-slate-200" @click.away="modal=false">
                            <h4 class="font-heading text-lg font-bold text-slate-900" x-text="modal === 'unlink' ? 'Hosting ontkoppelen' : 'Hosting wijzigen'"></h4>
                            <p class="mt-2 text-sm text-slate-500" x-text="modal === 'unlink' ? 'De koppeling wordt verwijderd. Website- en e-maildata blijven bestaan.' : 'Kies het gewenste hostingpakket.'"></p>
                            <div x-show="modal !== 'unlink'" class="mt-4">
                                <select class="form-select w-full">
                                    <option>Webhosting Start</option>
                                    <option>Webhosting Plus</option>
                                    <option>Webhosting Pro</option>
                                </select>
                            </div>
                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button" @click="modal=false" class="text-sm font-semibold text-slate-600 hover:text-slate-900">Annuleren</button>
                                <button type="button" @click="modal=false; alert('Hostingactie opgeslagen (dummy)')" class="rounded-xl px-4 py-2 text-sm font-semibold text-white" :class="modal === 'unlink' ? 'bg-rose-600 hover:bg-rose-700' : 'bg-primary-600 hover:bg-primary-700'">Bevestigen</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Accordion: Forwarding -->
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70 mb-4" x-data="{ open: false, modal: false }">
            <button type="button" @click="open = !open" class="flex w-full items-center justify-between">
                <div class="flex items-center gap-4">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-rose-50 text-rose-600 ring-1 ring-rose-100">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                    </span>
                    <div class="text-left">
                        <h3 class="font-heading text-lg font-bold text-slate-900">Doorsturen</h3>
                        <p class="text-sm text-slate-500">{{ $forwarding['enabled'] ? $forwarding['type'] . ' &rarr; ' . $forwarding['target'] : 'Niet ingesteld' }}</p>
                    </div>
                </div>
                <svg class="h-5 w-5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </button>
            <div x-show="open" class="pt-5">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                    <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-medium uppercase tracking-wider text-slate-500">Status</p><p class="mt-1 font-semibold text-slate-900">{{ $forwarding['enabled'] ? 'Aan' : 'Uit' }}</p></div>
                    <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-medium uppercase tracking-wider text-slate-500">Type</p><p class="mt-1 font-semibold text-slate-900">{{ $forwarding['type'] }}</p></div>
                    <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-medium uppercase tracking-wider text-slate-500">Doel</p><p class="mt-1 font-semibold text-slate-900">{{ $forwarding['target'] }}</p></div>
                </div>
                <button type="button" @click="modal=true" class="mt-5 btn btn-secondary">Doorsturen instellen</button>

                <template x-if="modal">
                    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(2,6,23,0.6)">
                        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-slate-200" @click.away="modal=false">
                            <h4 class="font-heading text-lg font-bold text-slate-900">Doorsturen instellen</h4>
                            <div class="mt-4 space-y-4">
                                <label class="flex items-center gap-2"><input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-primary-600"> <span class="text-sm text-slate-700">Doorsturing inschakelen</span></label>
                                <div><label class="block text-xs font-medium text-slate-600">Type</label><select class="form-select mt-1 w-full"><option value="301">301 (permanent)</option><option value="302">302 (tijdelijk)</option></select></div>
                                <div><label class="block text-xs font-medium text-slate-600">Doel-URL</label><input type="url" class="form-input mt-1 w-full" placeholder="https://..." value="{{ $forwarding['target'] }}"></div>
                            </div>
                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button" @click="modal=false" class="text-sm font-semibold text-slate-600 hover:text-slate-900">Annuleren</button>
                                <button type="button" @click="modal=false; alert('Doorsturing opgeslagen (dummy)')" class="rounded-xl bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">Opslaan</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Accordion: Transfer -->
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70 mb-4" x-data="{ open: false, visible: false, copied: false }">
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
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <div class="relative flex-1 rounded-xl bg-slate-900 px-4 py-3 font-mono text-sm text-white tracking-widest select-all" x-text="visible ? '{{ $transferToken['token'] }}' : '••••••••••••'">
                    </div>
                    <button type="button" @click="visible = !visible" class="rounded-xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200" x-text="visible ? 'Verbergen' : 'Verhuistoken tonen'"></button>
                    <button type="button" @click="navigator.clipboard.writeText('{{ $transferToken['token'] }}'); copied=true; setTimeout(() => copied=false, 2000)" class="rounded-xl bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">Kopiëren</button>
                </div>
                <p x-show="copied" x-cloak x-transition class="mt-2 text-sm font-medium text-emerald-600">Token gekopieerd naar klembord.</p>
            </div>
        </div>
            </div>
        </div>
    </div>
</div>
@endsection
