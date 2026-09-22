@extends('layouts.app')

@section('title', 'Dashboard - Servura')

@php
    $navStartsDark = false;
@endphp

@section('content')
@include('customer.partials.sidebar')

<div class="min-h-screen bg-slate-50 lg:pl-64">
    <!-- Top bar with subtle gradient -->
    <div class="bg-white border-b border-slate-200/60 sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Klantportaal</p>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm text-slate-600 hidden sm:inline">{{ Auth::user()->email }}</span>
                <span class="h-8 w-8 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center text-sm font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </span>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @php
            $hour = now()->format('H');
            $greeting = $hour < 12 ? 'Goedemorgen' : ($hour < 18 ? 'Goedemiddag' : 'Goedenavond');
        @endphp

        <!-- Welcome Section -->
        <div class="mb-10">
            <h1 class="text-3xl sm:text-4xl font-heading font-bold text-slate-900 tracking-tight">
                {{ $greeting }}, {{ Auth::user()->name }}
            </h1>
            <p class="mt-2 text-lg text-slate-500">
                Wat wil je vandaag met je website doen?
            </p>
        </div>

        <!-- Primary Action Cards -->
        <div class="mb-12" x-data="requestWizard()">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-5">
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
                class="fixed inset-0 z-[70] flex items-center justify-center p-4 sm:p-6"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">
                <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" @click="close()" aria-hidden="true"></div>
                <div x-show="openModal"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    class="relative w-full max-w-xl max-h-[90vh] overflow-y-auto bg-white rounded-3xl shadow-2xl ring-1 ring-slate-200">
                    <form action="{{ route('customer.tickets.store') }}" method="POST" enctype="multipart/form-data" @submit="submitting = true">
                        @csrf
                        <input type="hidden" name="request_type" :value="requestType">
                        <input type="hidden" name="category" :value="category">
                        <input type="hidden" name="priority" value="medium">
                        <input type="hidden" name="title" :value="generatedTitle">

                        <div class="sticky top-0 z-10 flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white/95 backdrop-blur">
                            <div>
                                <h3 class="font-heading text-xl font-bold text-slate-900" x-text="title"></h3>
                                <p class="text-sm text-slate-500">Vul een paar vragen in, wij regelen de rest.</p>
                            </div>
                            <button type="button" @click="close()" class="p-2 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div class="p-6 sm:p-8 space-y-6">
                            <!-- Step 1: What -->
                            <div>
                                <label class="form-label mb-3 block" x-text="step1Label"></label>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    <template x-for="option in step1Options" :key="option.value">
                                        <label class="relative flex flex-col items-center text-center p-4 rounded-xl ring-1 cursor-pointer transition-all"
                                            :class="selectedOption === option.value ? 'bg-primary-50 ring-primary-500 text-primary-700 shadow-sm' : 'bg-white ring-slate-200 text-slate-700 hover:bg-slate-50'">
                                            <input type="radio" :name="step1Name" :value="option.value" x-model="selectedOption" class="sr-only" required>
                                            <span class="text-sm font-semibold" x-text="option.label"></span>
                                        </label>
                                    </template>
                                </div>
                            </div>

                            <!-- Step 2: Page -->
                            <div x-show="needsPage" x-cloak>
                                <label for="wizard-page" class="form-label mb-2 block">Op welke pagina moet dit?</label>
                                <select id="wizard-page" name="page" x-model="page" class="form-input">
                                    <option value="">Kies een pagina</option>
                                    <option value="Homepage">Homepage</option>
                                    <option value="Over ons">Over ons</option>
                                    <option value="Diensten">Diensten</option>
                                    <option value="Contact">Contact</option>
                                    <option value="Blog">Blog</option>
                                    <option value="Anders">Andere pagina</option>
                                </select>
                            </div>

                            <!-- Step 3: Description -->
                            <div>
                                <label for="wizard-description" class="form-label mb-2 block">Beschrijf wat je wilt veranderen</label>
                                <textarea id="wizard-description" name="description" rows="4" x-model="description" class="form-textarea" placeholder="Bijvoorbeeld: Ik wil een nieuwe foto van ons team op de homepage plaatsen." required></textarea>
                            </div>

                            <!-- Step 4: File upload -->
                            <div>
                                <label class="form-label mb-2 block">Bijlage toevoegen (optioneel)</label>
                                <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:border-primary-400 transition-colors bg-slate-50">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-10 w-10 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-slate-600 justify-center">
                                            <label for="wizard-attachments" class="relative cursor-pointer rounded-md font-medium text-primary-600 hover:text-primary-500">
                                                <span>Upload bestanden</span>
                                                <input id="wizard-attachments" name="attachments[]" type="file" class="sr-only" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt,.zip">
                                            </label>
                                        </div>
                                        <p class="text-xs text-slate-500">PNG, JPG, GIF, PDF, DOC, DOCX, TXT, ZIP tot 10MB</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Summary -->
                            <div class="rounded-xl bg-slate-50 p-4 ring-1 ring-slate-200">
                                <h4 class="font-semibold text-slate-900 mb-2">Samenvatting</h4>
                                <dl class="space-y-1 text-sm">
                                    <div class="flex justify-between"><dt class="text-slate-500">Type</dt><dd class="font-medium text-slate-900" x-text="requestTypeLabel"></dd></div>
                                    <div x-show="selectedOptionLabel" class="flex justify-between"><dt class="text-slate-500">Wat</dt><dd class="font-medium text-slate-900" x-text="selectedOptionLabel"></dd></div>
                                    <div x-show="page" class="flex justify-between"><dt class="text-slate-500">Pagina</dt><dd class="font-medium text-slate-900" x-text="page"></dd></div>
                                </dl>
                            </div>
                        </div>

                        <div class="sticky bottom-0 flex items-center justify-between gap-3 px-6 py-4 border-t border-slate-100 bg-slate-50 rounded-b-3xl">
                            <button type="button" @click="close()" class="btn btn-outline">Annuleren</button>
                            <button type="submit" class="btn btn-primary" :disabled="submitting" x-text="submitting ? 'Versturen...' : 'Aanvraag versturen'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Requests -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70">
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                        <h2 class="font-heading text-lg font-bold text-slate-900">Recente aanvragen</h2>
                        @if($recentRequests->count() > 0)
                            <a href="{{ route('customer.tickets.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">Bekijk alles</a>
                        @endif
                    </div>
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
        selectedOption: '',
        page: '',
        description: '',
        title: '',
        category: 'general',

        get generatedTitle() {
            const optionLabel = this.selectedOptionLabel;
            const pageText = this.page ? ` - ${this.page}` : '';
            return optionLabel ? `${this.title} - ${optionLabel}${pageText}` : this.title;
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
            return 'request_detail';
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

        get selectedOptionLabel() {
            return this.step1Options.find(o => o.value === this.selectedOption)?.label || '';
        },

        get needsPage() {
            return ['website_aanpassen', 'iets_toevoegen'].includes(this.requestType) && ['tekst','foto','pagina','contactgegevens','openingstijden','nieuwe_pagina','nieuwe_sectie','foto_video','blog'].includes(this.selectedOption);
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
            this.selectedOption = '';
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
