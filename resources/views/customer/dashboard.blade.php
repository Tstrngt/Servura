@extends('layouts.app')

@section('title', 'Dashboard - Servura')

@php
    $navStartsDark = false;
@endphp

@section('content')
@include('customer.partials.topbar')

<div class="min-h-screen bg-slate-50 pt-32">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 pb-24">
        @php
            $hour = now()->format('H');
            $greeting = $hour < 12 ? 'Goedemorgen' : ($hour < 18 ? 'Goedemiddag' : 'Goedenavond');
        @endphp

        <!-- Welcome Section -->
        <div class="mb-14">
            <h1 class="text-3xl sm:text-4xl font-heading font-bold text-slate-900 tracking-tight">
                {{ $greeting }}, {{ Auth::user()->name }}
            </h1>
            <p class="mt-3 text-lg text-slate-500">
                Wat wil je vandaag met je website doen?
            </p>
        </div>

        <!-- Primary Action Cards -->
        <div class="mb-24" x-data="requestWizard()">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                <!-- Website aanpassen -->
                <button type="button" @click="open('website_aanpassen')"
                    class="group relative flex flex-col items-start text-left p-6 bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 hover:shadow-xl hover:-translate-y-1 transition-all duration-200 overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-amber-100/50 rounded-full blur-2xl group-hover:bg-amber-200/60 transition-colors"></div>
                    <span class="relative inline-flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600 mb-4 ring-1 ring-amber-100 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </span>
                    <span class="relative font-heading font-semibold text-slate-900 text-lg">Website aanpassen</span>
                    <span class="relative mt-1 text-sm text-slate-500 leading-snug">Tekst, foto's of pagina's wijzigen</span>
                </button>

                <!-- Iets toevoegen -->
                <button type="button" @click="open('iets_toevoegen')"
                    class="group relative flex flex-col items-start text-left p-6 bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 hover:shadow-xl hover:-translate-y-1 transition-all duration-200 overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-emerald-100/50 rounded-full blur-2xl group-hover:bg-emerald-200/60 transition-colors"></div>
                    <span class="relative inline-flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 mb-4 ring-1 ring-emerald-100 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </span>
                    <span class="relative font-heading font-semibold text-slate-900 text-lg">Iets toevoegen</span>
                    <span class="relative mt-1 text-sm text-slate-500 leading-snug">Pagina, blog of functionaliteit</span>
                </button>

                <!-- Website uitbreiden -->
                <button type="button" @click="open('website_uitbreiden')"
                    class="group relative flex flex-col items-start text-left p-6 bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 hover:shadow-xl hover:-translate-y-1 transition-all duration-200 overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-primary-100/50 rounded-full blur-2xl group-hover:bg-primary-200/60 transition-colors"></div>
                    <span class="relative inline-flex h-12 w-12 items-center justify-center rounded-xl bg-primary-50 text-primary-600 mb-4 ring-1 ring-primary-100 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </span>
                    <span class="relative font-heading font-semibold text-slate-900 text-lg">Website uitbreiden</span>
                    <span class="relative mt-1 text-sm text-slate-500 leading-snug">Mailbox, hosting of extra diensten</span>
                </button>

                <!-- Hulp nodig? -->
                <button type="button" @click="open('hulp_nodig')"
                    class="group relative flex flex-col items-start text-left p-6 bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 hover:shadow-xl hover:-translate-y-1 transition-all duration-200 overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-rose-100/50 rounded-full blur-2xl group-hover:bg-rose-200/60 transition-colors"></div>
                    <span class="relative inline-flex h-12 w-12 items-center justify-center rounded-xl bg-rose-50 text-rose-600 mb-4 ring-1 ring-rose-100 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </span>
                    <span class="relative font-heading font-semibold text-slate-900 text-lg">Hulp nodig?</span>
                    <span class="relative mt-1 text-sm text-slate-500 leading-snug">Probleem, vraag of ondersteuning</span>
                </button>

                <!-- Mijn website -->
                <a href="{{ $websiteUrl ?? '#' }}" target="_blank" rel="noopener"
                    class="group relative flex flex-col items-start text-left p-6 bg-slate-900 rounded-2xl shadow-lg shadow-slate-900/10 ring-1 ring-slate-800 hover:shadow-2xl hover:-translate-y-1 transition-all duration-200 overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
                    <span class="relative inline-flex h-12 w-12 items-center justify-center rounded-xl bg-white/10 text-white mb-4 ring-1 ring-white/10 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                    </span>
                    <span class="relative font-heading font-semibold text-white text-lg">Mijn website</span>
                    <span class="relative mt-1 text-sm text-slate-400 leading-snug truncate max-w-full">
                        {{ $websiteUrl ? parse_url($websiteUrl, PHP_URL_HOST) ?? 'Website bekijken' : 'Website bekijken' }}
                    </span>
                </a>
            </div>

            <!-- Wizard Modal -->
            <div x-show="openModal" style="display: none;"
                class="fixed inset-0 z-[70] overflow-y-auto"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">
                <div class="relative min-h-full flex items-center justify-center px-4 pt-10 pb-16 sm:px-6 sm:pt-12 sm:pb-20 lg:px-8 lg:pt-16 lg:pb-24">
                    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" @click="close()" aria-hidden="true"></div>
                    <div x-show="openModal"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        class="relative w-full max-w-3xl bg-white rounded-3xl shadow-2xl shadow-slate-900/20 ring-1 ring-slate-200">
                    <form action="{{ route('customer.tickets.store') }}" method="POST" enctype="multipart/form-data" @submit="submitting = true">
                        @csrf
                        <input type="hidden" name="request_type" :value="requestType">
                        <input type="hidden" name="category" :value="category">
                        <input type="hidden" name="priority" value="medium">
                        <input type="hidden" name="title" :value="generatedTitle">

                        <!-- Modal Header with accent -->
                        <div class="px-8 py-6 border-b border-slate-100 bg-white/95 backdrop-blur rounded-t-3xl">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-primary-500 to-accent-500 text-white shadow-lg shadow-primary-500/20">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </span>
                                    <div>
                                        <h3 class="font-heading text-2xl font-bold text-slate-900" x-text="title"></h3>
                                        <p class="text-sm text-slate-500 mt-1">Vul een paar vragen in, wij regelen de rest. Je mag meerdere onderdelen selecteren.</p>
                                    </div>
                                </div>
                                <button type="button" @click="close()" class="p-2 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="p-8 space-y-8">
                            <!-- Step 1: What (multiple choice) -->
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <label class="form-label block" x-text="step1Label"></label>
                                    <span class="text-xs font-medium text-primary-600 bg-primary-50 px-2 py-1 rounded-full ring-1 ring-primary-100">Meerdere opties mogelijk</span>
                                </div>
                                <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                                    <template x-for="option in step1Options" :key="option.value">
                                        <label class="relative flex items-start gap-3 p-4 rounded-2xl ring-1 cursor-pointer transition-all"
                                            :class="isSelected(option.value) ? 'bg-primary-50 ring-primary-500 shadow-sm' : 'bg-white ring-slate-200 hover:bg-slate-50'">
                                            <input type="checkbox" name="request_details[]" :value="option.value" :checked="isSelected(option.value)" @change="toggleOption(option.value)" class="mt-1 h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                                            <span class="text-sm font-semibold" :class="isSelected(option.value) ? 'text-primary-900' : 'text-slate-700'" x-text="option.label"></span>
                                        </label>
                                    </template>
                                </div>
                                <p x-show="selectedOptions.length === 0" class="mt-2 text-sm text-rose-600">Kies minimaal één optie.</p>
                            </div>

                            <!-- Step 2: Page -->
                            <div x-show="needsPage" x-cloak class="p-5 rounded-2xl bg-slate-50 ring-1 ring-slate-200">
                                <label for="wizard-page" class="form-label mb-2 block">Op welke pagina(n) moet dit?</label>
                                <select id="wizard-page" name="page" x-model="page" class="form-input bg-white">
                                    <option value="">Kies een pagina</option>
                                    <option value="Homepage">Homepage</option>
                                    <option value="Over ons">Over ons</option>
                                    <option value="Diensten">Diensten</option>
                                    <option value="Contact">Contact</option>
                                    <option value="Blog">Blog</option>
                                    <option value="Meerdere pagina's">Meerdere pagina's</option>
                                    <option value="Anders">Andere pagina</option>
                                </select>
                                <p class="mt-2 text-xs text-slate-500">Selecteer "Meerdere pagina's" als de wijziging op meer plekken moet.</p>
                            </div>

                            <!-- Step 3: Description -->
                            <div class="p-5 rounded-2xl bg-slate-50 ring-1 ring-slate-200">
                                <label for="wizard-description" class="form-label mb-2 block">Beschrijf wat je wilt veranderen</label>
                                <textarea id="wizard-description" name="description" rows="5" x-model="description" class="form-textarea bg-white" placeholder="Bijvoorbeeld: Ik wil een nieuwe foto van ons team op de homepage plaatsen en de openingstijden in de footer aanpassen." required></textarea>
                                <p class="mt-2 text-xs text-slate-500">Hoe specifieker, hoe sneller wij het kunnen uitvoeren.</p>
                            </div>

                            <!-- Step 4: File upload -->
                            <div class="p-5 rounded-2xl bg-slate-50 ring-1 ring-slate-200">
                                <label class="form-label mb-2 block">Bijlagen toevoegen (optioneel)</label>
                                <div class="flex justify-center px-6 pt-6 pb-7 border-2 border-dashed border-slate-300 rounded-xl hover:border-primary-400 transition-colors bg-white cursor-pointer" onclick="document.getElementById('wizard-attachments').click()">
                                    <div class="space-y-2 text-center">
                                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-primary-50 text-primary-600 mb-1">
                                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </span>
                                        <div class="text-sm text-slate-600">
                                            <span class="font-medium text-primary-600">Klik om bestanden te uploaden</span>
                                            <span class="hidden sm:inline"> of sleep ze hierheen</span>
                                        </div>
                                        <p class="text-xs text-slate-500">PNG, JPG, GIF, PDF, DOC, DOCX, TXT, ZIP tot 10MB per bestand</p>
                                    </div>
                                </div>
                                <input id="wizard-attachments" name="attachments[]" type="file" class="sr-only" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt,.zip">
                            </div>

                            <!-- Summary -->
                            <div class="rounded-2xl bg-gradient-to-br from-slate-900 to-slate-800 p-6 text-white shadow-lg">
                                <h4 class="font-heading font-semibold text-lg mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    Samenvatting
                                </h4>
                                <dl class="space-y-2 text-sm">
                                    <div class="flex justify-between border-b border-white/10 pb-2"><dt class="text-slate-400">Type aanvraag</dt><dd class="font-medium" x-text="requestTypeLabel"></dd></div>
                                    <div x-show="selectedOptionsLabels.length" class="flex justify-between border-b border-white/10 pb-2"><dt class="text-slate-400">Geselecteerd</dt><dd class="font-medium text-right max-w-[60%]" x-text="selectedOptionsLabels.join(', ')"></dd></div>
                                    <div x-show="page" class="flex justify-between border-b border-white/10 pb-2"><dt class="text-slate-400">Pagina</dt><dd class="font-medium" x-text="page"></dd></div>
                                    <div x-show="description" class="flex justify-between pt-1"><dt class="text-slate-400 shrink-0">Omschrijving</dt><dd class="font-medium text-right max-w-[70%] truncate" x-text="description"></dd></div>
                                </dl>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-3 px-8 py-5 border-t border-slate-100 bg-slate-50 rounded-b-3xl">
                            <button type="button" @click="close()" class="btn btn-outline">Annuleren</button>
                            <button type="submit" class="btn btn-primary px-6" :disabled="submitting || selectedOptions.length === 0" x-text="submitting ? 'Versturen...' : 'Aanvraag versturen'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Section divider + heading -->
        <div class="border-t border-slate-200 pt-16 mt-8 mb-10">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div>
                    <h2 class="font-heading text-2xl font-bold text-slate-900">Recente aanvragen</h2>
                    <p class="mt-1 text-slate-500">Bekijk hier je laatste verzoeken en hun status.</p>
                </div>
                @if($recentRequests->count() > 0)
                    <a href="{{ route('customer.tickets.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700 shrink-0">Bekijk alles</a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Recent Requests -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70">
                    <div class="p-6">
                        @if($recentRequests->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentRequests as $request)
                                    <a href="{{ route('customer.tickets.show', $request) }}" class="group flex items-start gap-4 p-4 rounded-xl bg-slate-50 hover:bg-white hover:shadow-md ring-1 ring-transparent hover:ring-slate-200 transition-all">
                                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-white text-slate-500 ring-1 ring-slate-200 shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        </span>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between gap-2">
                                                <h3 class="font-semibold text-slate-900 truncate">{{ $request->title }}</h3>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $request->statusLabel['color'] }}-100 text-{{ $request->statusLabel['color'] }}-800 shrink-0">
                                                    {{ $request->statusLabel['text'] }}
                                                </span>
                                            </div>
                                            <div class="mt-1 flex items-center gap-3 text-sm text-slate-500">
                                                <span>{{ $request->requestTypeLabel }}</span>
                                                <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                                                <span>{{ $request->created_at->format('d F Y') }}</span>
                                            </div>
                                        </div>
                                        <svg class="w-5 h-5 text-slate-300 group-hover:text-slate-500 mt-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-10">
                                <div class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400 mb-4">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <h3 class="text-sm font-medium text-slate-900">Nog geen aanvragen</h3>
                                <p class="mt-1 text-sm text-slate-500">Kies hierboven een actie om je eerste aanvraag te doen.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Website Status + Quick Support -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 p-6">
                    <h2 class="font-heading text-lg font-bold text-slate-900 mb-5">Mijn website</h2>
                    <div class="space-y-3">
                        @php
                            $statusItems = [
                                ['label' => 'Website', 'status' => 'Online', 'color' => 'emerald'],
                                ['label' => 'Hosting', 'status' => 'Actief', 'color' => 'emerald'],
                                ['label' => 'Domein', 'status' => 'Actief', 'color' => 'emerald'],
                                ['label' => 'SSL', 'status' => 'Beveiligd', 'color' => 'emerald'],
                            ];
                        @endphp
                        @foreach($statusItems as $item)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50">
                                <div class="flex items-center gap-3">
                                    <span class="h-2.5 w-2.5 rounded-full bg-{{ $item['color'] }}-500"></span>
                                    <span class="text-sm font-medium text-slate-700">{{ $item['label'] }}</span>
                                </div>
                                <span class="text-sm font-semibold text-{{ $item['color'] }}-700">{{ $item['status'] }}</span>
                            </div>
                        @endforeach
                    </div>
                    @if($websiteUrl)
                        <a href="{{ $websiteUrl }}" target="_blank" rel="noopener" class="mt-5 w-full inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800 transition-colors">
                            Website bekijken
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    @endif
                </div>

                <div class="bg-gradient-to-br from-primary-600 to-primary-700 rounded-2xl shadow-lg shadow-primary-600/20 p-6 text-white">
                    <h3 class="font-heading text-lg font-bold mb-1">Direct contact?</h3>
                    <p class="text-primary-100 text-sm mb-4">Stuur ons een bericht. We reageren binnen 24 uur.</p>
                    <a href="{{ route('customer.tickets.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-primary-700 hover:bg-primary-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        Stuur een bericht
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function requestWizard() {
    return {
        openModal: false,
        submitting: false,
        requestType: '',
        selectedOptions: [],
        page: '',
        description: '',
        title: '',
        category: 'general',

        get generatedTitle() {
            const labels = this.selectedOptionsLabels;
            const pageText = this.page ? ` - ${this.page}` : '';
            return labels.length ? `${this.title} - ${labels.join(', ')}${pageText}` : this.title;
        },

        get requestTypeLabel() {
            const labels = {
                'website_aanpassen': 'Aanpassing',
                'iets_toevoegen': 'Toevoeging',
                'website_uitbreiden': 'Uitbreiding',
                'hulp_nodig': 'Hulp'
            };
            return labels[this.requestType] || 'Aanvraag';
        },

        get step1Label() {
            const labels = {
                'website_aanpassen': 'Wat wil je aanpassen?',
                'iets_toevoegen': 'Wat wil je toevoegen?',
                'website_uitbreiden': 'Wat wil je uitbreiden?',
                'hulp_nodig': 'Waarmee heb je hulp nodig?'
            };
            return labels[this.requestType] || 'Wat wil je doen?';
        },

        get step1Name() {
            return 'request_details';
        },

        isSelected(value) {
            return this.selectedOptions.includes(value);
        },

        toggleOption(value) {
            if (this.isSelected(value)) {
                this.selectedOptions = this.selectedOptions.filter(v => v !== value);
            } else {
                this.selectedOptions.push(value);
            }
        },

        get step1Options() {
            const options = {
                'website_aanpassen': [
                    { value: 'tekst', label: 'Tekst' },
                    { value: 'foto', label: 'Foto' },
                    { value: 'pagina', label: 'Pagina' },
                    { value: 'contactgegevens', label: 'Contactgegevens' },
                    { value: 'openingstijden', label: 'Openingstijden' },
                    { value: 'anders', label: 'Anders' },
                ],
                'iets_toevoegen': [
                    { value: 'nieuwe_pagina', label: 'Nieuwe pagina' },
                    { value: 'contactformulier', label: 'Contactformulier' },
                    { value: 'foto_video', label: 'Foto/video' },
                    { value: 'nieuwe_sectie', label: 'Nieuwe sectie' },
                    { value: 'blog', label: 'Blog' },
                    { value: 'functionaliteit', label: 'Functionaliteit' },
                ],
                'website_uitbreiden': [
                    { value: 'extra_mailbox', label: 'Extra mailbox' },
                    { value: 'extra_domein', label: 'Extra domein' },
                    { value: 'extra_hosting', label: 'Extra hosting' },
                    { value: 'onderhoud', label: 'Onderhoud' },
                    { value: 'seo', label: 'SEO-diensten' },
                    { value: 'analytics', label: 'Analytics' },
                    { value: 'functionaliteit', label: 'Nieuwe functionaliteit' },
                    { value: 'anders', label: 'Andere diensten' },
                ],
                'hulp_nodig': [
                    { value: 'website_werkt_niet', label: 'Website werkt niet' },
                    { value: 'kan_niet_inloggen', label: 'Kan niet inloggen' },
                    { value: 'email_werkt_niet', label: 'E-mail werkt niet' },
                    { value: 'vraag', label: 'Ik heb een vraag' },
                    { value: 'technisch_probleem', label: 'Technisch probleem' },
                    { value: 'anders', label: 'Anders' },
                ]
            };
            return options[this.requestType] || [];
        },

        get selectedOptionsLabels() {
            return this.selectedOptions.map(value => this.step1Options.find(o => o.value === value)?.label).filter(Boolean);
        },

        get needsPage() {
            return ['website_aanpassen', 'iets_toevoegen'].includes(this.requestType) && this.selectedOptions.some(value => ['tekst','foto','pagina','contactgegevens','openingstijden','nieuwe_pagina','nieuwe_sectie','foto_video','blog'].includes(value));
        },

        open(type) {
            this.requestType = type;
            this.title = {
                'website_aanpassen': 'Website aanpassen',
                'iets_toevoegen': 'Iets toevoegen',
                'website_uitbreiden': 'Website uitbreiden',
                'hulp_nodig': 'Hulp nodig?'
            }[type] || 'Aanvraag';
            this.category = type === 'hulp_nodig' ? 'technical' : 'general';
            this.selectedOptions = [];
            this.page = '';
            this.description = '';
            this.openModal = true;
            document.body.style.overflow = 'hidden';
        },

        close() {
            this.openModal = false;
            document.body.style.overflow = 'auto';
        }
    };
}
</script>
@endsection
