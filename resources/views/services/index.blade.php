@extends('layouts.app')

@section('title', 'Diensten - Servura')
@section('meta-description', 'Bekijk alle diensten van Servura: webdesign, hosting, onderhoud en meer. Professionele oplossingen voor het MKB.')
@section('meta-keywords', 'diensten, webdesign, hosting, onderhoud, mkb, website ontwikkeling')

@section('content')
@php
$steps = [
    ['title' => 'Kennismaking', 'text' => 'Iedere samenwerking begint met een vrijblijvend kennismakingsgesprek. Tijdens dit gesprek bespreken we uw bedrijf, doelgroep, wensen en doelstellingen. Op basis daarvan geven we advies over de beste oplossing voor uw online aanwezigheid.'],
    ['title' => 'Voorstel', 'text' => 'Na het kennismakingsgesprek ontvangt u een helder en overzichtelijk voorstel. Hierin beschrijven we de werkzaamheden, planning, investering en eventuele aanvullende mogelijkheden, zodat u precies weet waar u aan toe bent.'],
    ['title' => 'Ontwikkeling', 'text' => 'Na akkoord starten we met het ontwerpen en ontwikkelen van uw website. Tijdens dit proces houden we u op de hoogte van de voortgang en is er ruimte voor feedback, zodat het eindresultaat volledig aansluit bij uw verwachtingen.'],
    ['title' => 'Lancering', 'text' => 'Wanneer de website volledig is getest en goedgekeurd, verzorgen wij de livegang. Ook na de lancering blijven wij beschikbaar voor hosting, onderhoud, beveiligingsupdates en ondersteuning, zodat uw website veilig, snel en altijd optimaal blijft presteren.'],
];
@endphp

<!-- Hero Section -->
<section class="relative -mt-16 pt-16 overflow-hidden bg-slate-950 text-white" data-navbar-theme="dark">
    <div class="relative max-w-7xl mx-auto px-6 py-24 lg:py-32">
        <div class="max-w-2xl animate-slide-up">
            <h1 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold leading-[1.05] tracking-tight">
                Websites en hosting die met uw bedrijf meegroeien.
            </h1>
            <p class="mt-6 max-w-xl text-lg leading-relaxed text-white/90">
                Kies het pakket dat past bij uw ambities. Wij regelen ontwerp, techniek, hosting en onderhoud — zodat u zich kunt richten op ondernemen.
            </p>
        </div>
    </div>
</section>

<!-- Webdesign Producten -->
<section id="pakketten" class="relative bg-slate-50 py-24 lg:py-32"
    x-data="{ open: false, service: null, show(s) { this.service = s; this.open = true; document.body.style.overflow = 'hidden'; }, hide() { this.open = false; this.service = null; document.body.style.overflow = 'auto'; } }"
    @keydown.escape.window="hide">
    <div class="max-w-7xl mx-auto px-6">
        <div class="mb-12 animate-on-scroll">
            <span class="text-accent-600 font-semibold tracking-wide uppercase text-sm mb-4 block">Onze diensten</span>
            <h2 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 mb-6 leading-[1.05] tracking-tight">Onze pakketten</h2>
            <p class="text-lg text-slate-600 max-w-2xl">Kies het plan dat past bij uw bedrijf. Alle pakketten zijn volledig ontzorgd.</p>
        </div>

        @php
            $webdesignServices = $services->where('service_type', 'website_pakket')->where('slug', '!=', 'test')->take(3);
        @endphp

        @if($webdesignServices->count() > 0)
            @php $recommendedService = null; @endphp
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
                @foreach($webdesignServices as $index => $service)
                    @php
                        $serviceData = [
                            'title' => $service->title,
                            'short_description' => $service->short_description,
                            'description' => $service->description,
                            'formatted_price' => $service->formatted_price,
                            'features' => $service->features ?? [],
                            'image_url' => $service->image_url,
                            'slug' => $service->slug,
                            'popup_label' => $service->popup_label,
                            'popup_badges' => $service->popup_badges ?? [],
                            'popup_details' => $service->popup_details ?? [],
                            'popup_price_note' => $service->popup_price_note,
                        ];
                        $isRecommended = $index === 1;
                        $gradients = [
                            'from-primary-500 to-primary-700',
                            'from-accent-500 to-accent-700',
                            'from-secondary-500 to-secondary-700',
                        ];
                    @endphp
                    <div class="group relative bg-white rounded-3xl transition-all duration-300 animate-on-scroll flex flex-col {{ $isRecommended ? 'md:-translate-y-8 md:scale-110 z-20 ring-4 ring-accent-400 shadow-[0_0_0_6px_rgba(14,165,233,0.28),0_0_0_16px_rgba(20,184,166,0.20),0_30px_70px_-15px_rgba(14,165,233,0.45)]' : 'ring-1 ring-slate-200 shadow-xl shadow-slate-900/5 hover:-translate-y-2 hover:shadow-2xl' }}">
                        <div class="overflow-hidden rounded-t-3xl">
                            @if($isRecommended)
                                <div class="h-2 bg-gradient-to-r from-primary-500 to-accent-500"></div>
                            @else
                                <div class="h-2 bg-gradient-to-r {{ $gradients[$index % 3] }}"></div>
                            @endif

                            @if($service->image_url)
                                <div class="h-48 overflow-hidden">
                                    <img src="{{ $service->image_url }}" alt="{{ $service->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>
                            @else
                                <div class="h-48 bg-gradient-to-br {{ $isRecommended ? 'from-primary-500 to-accent-600' : $gradients[$index % 3] }} flex items-center justify-center">
                                    <span class="text-6xl font-black text-white/25 select-none">{{ mb_strtoupper(mb_substr($service->title, 0, 1)) }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="p-8 flex-1 flex flex-col">
                            <div class="mb-6">
                                <h3 class="font-heading text-2xl font-bold text-slate-900 mb-2">{{ $service->title }}</h3>
                                <p class="text-slate-600 text-sm">{{ $service->short_description }}</p>
                            </div>

                            <div class="flex items-baseline gap-1 mb-6">
                                <span class="text-4xl font-bold text-slate-900">{{ $service->formatted_price }}</span>
                            </div>

                            @if($service->features && count($service->features) > 0)
                                <ul class="space-y-3 mb-8 flex-1">
                                    @foreach(array_slice($service->features, 0, 5) as $feature)
                                        <li class="flex items-start text-sm text-slate-700">
                                            <svg class="w-5 h-5 {{ $isRecommended ? 'text-accent-500' : 'text-primary-500' }} mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            <button type="button" @click="show(@js($serviceData))" class="btn {{ $isRecommended ? 'btn-primary' : 'btn-outline' }} w-full" aria-haspopup="dialog" aria-controls="service-modal">
                                Bekijk product
                            </button>
                            @if($isRecommended)
                                @php $recommendedService = $service; @endphp
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if($recommendedService)
                <div class="mt-8 flex justify-center">
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gradient-to-r from-primary-500 to-accent-500 text-white text-sm font-bold uppercase tracking-wide shadow-lg">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        Aanbevolen: {{ $recommendedService->title }}
                    </span>
                </div>
            @endif
        @else
            <p class="text-slate-600">Geen webdesign pakketten gevonden.</p>
        @endif
    </div>

    <!-- Service Modal (dummy layout voor goedkeuring) -->
    <div id="service-modal" x-show="open" class="fixed inset-0 z-[70] flex items-center justify-center p-4 sm:p-6" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click.self="hide" role="dialog" aria-modal="true" aria-labelledby="service-modal-title">
        <div class="absolute inset-0 bg-slate-950/70 backdrop-blur-sm" aria-hidden="true"></div>
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative w-full max-w-4xl max-h-[90vh] overflow-y-auto bg-white rounded-3xl shadow-2xl shadow-slate-900/25 ring-1 ring-slate-200">
            <div class="sticky top-0 z-10 flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white/95 backdrop-blur">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-primary-500 to-accent-500 text-white shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904C9.12 15.12 8.25 13.612 8.25 11.25c0-2.846 1.75-5.25 4.5-5.25 2.75 0 4.5 2.404 4.5 5.25 0 2.362-.87 3.87-1.563 4.654M12 21a1.5 1.5 0 01-1.5-1.5 1.5 1.5 0 01-1.5-1.5m3 0a1.5 1.5 0 01-1.5-1.5 1.5 1.5 0 01-1.5-1.5m-3-13.5c0-1.875 1.5-3.375 3.375-3.375.336 0 .66.042.975.12M9.75 12c0-1.875 1.5-3.375 3.375-3.375.336 0 .66.042.975.12"/></svg>
                    </span>
                    <h3 id="service-modal-title" class="font-heading text-2xl font-bold text-slate-900" x-text="service?.title"></h3>
                </div>
                <button type="button" @click="hide" class="p-2 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors" aria-label="Sluiten">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="relative h-56 sm:h-64 overflow-hidden bg-gradient-to-br from-primary-600 to-accent-600">
                <img x-show="service?.image_url" :src="service?.image_url" :alt="service?.title" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/40 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8">
                    <span x-show="service?.popup_label" x-text="service?.popup_label" class="inline-flex items-center rounded-full bg-white/20 backdrop-blur px-3 py-1 text-xs font-semibold text-white ring-1 ring-white/30 mb-3"></span>
                    <p class="max-w-2xl text-lg text-white/90 leading-relaxed" x-text="service?.short_description"></p>
                </div>
                <div class="absolute top-4 right-4 rounded-2xl bg-white/95 backdrop-blur px-5 py-3 shadow-xl ring-1 ring-white/20 text-center">
                    <span class="block text-xs text-slate-500 uppercase tracking-wide">Investering</span>
                    <span class="block text-2xl font-bold text-slate-900" x-text="service?.formatted_price"></span>
                </div>
            </div>

            <div class="p-6 sm:p-8">
                <div x-show="service?.popup_badges?.length" class="flex flex-wrap gap-2 mb-8">
                    <template x-for="(badge, index) in service?.popup_badges || []" :key="index">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-primary-50 px-3 py-1 text-sm font-medium text-primary-700 ring-1 ring-primary-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" x-html="window.serviceIconSvg(badge.icon || ['sparkles', 'code', 'shield'][index] || 'sparkles')"></svg>
                            <span x-text="badge.text || badge"></span>
                        </span>
                    </template>
                </div>

                <div x-show="service?.popup_details?.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-10">
                    <template x-for="(detail, index) in service?.popup_details || []" :key="index">
                        <div class="group rounded-2xl bg-slate-50 p-5 ring-1 ring-slate-200 hover:bg-white hover:shadow-lg hover:shadow-primary-500/10 hover:-translate-y-1 transition-all duration-300">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-primary-100 text-primary-600 mb-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" x-html="window.serviceIconSvg(detail.icon || ['sparkles', 'device', 'code', 'search', 'server', 'support'][index] || 'sparkles')"></svg>
                            </span>
                            <h4 class="font-heading font-semibold text-slate-900 mb-1" x-text="detail.title"></h4>
                            <p class="text-sm text-slate-600 leading-relaxed" x-text="detail.description"></p>
                        </div>
                    </template>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 rounded-2xl bg-gradient-to-r from-slate-900 to-slate-800 p-6 text-white shadow-xl">
                    <div>
                        <span class="text-sm text-slate-300">Investering</span>
                        <div class="text-3xl font-bold" x-text="service?.formatted_price"></div>
                        <p x-show="service?.popup_price_note" class="text-sm text-slate-400 mt-1" x-text="service?.popup_price_note"></p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a :href="'{{ route('contact') }}?service=' + service?.slug" class="btn btn-primary whitespace-nowrap px-6 py-3">
                            Neem contact op
                        </a>
                        <a :href="'{{ route('quote.builder') }}?service=' + service?.slug + '&subject=Offerte'" class="btn bg-white/10 text-white hover:bg-white/20 ring-1 ring-white/20 whitespace-nowrap px-6 py-3">
                            Vraag offerte aan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Process / Roadmap -->
<section class="bg-slate-50 py-24 lg:py-32">
    <div class="max-w-7xl mx-auto px-6">
        <div class="max-w-2xl mx-auto text-center mb-16 lg:mb-20 animate-on-scroll">
            <span class="text-accent-600 font-semibold tracking-wide uppercase text-sm mb-4 block">Werkwijze</span>
            <h2 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 mb-6 leading-[1.05] tracking-tight">Van idee tot live website</h2>
            <p class="text-lg text-slate-600 leading-relaxed">
                Wij begeleiden u bij elke stap. U hoeft geen technische kennis te hebben; wij zorgen dat alles duidelijk en overzichtelijk blijft.
            </p>
        </div>

        @php
            $buildLabels = ['Idee bepalen', 'Blauwdruk maken', 'Doorontwikkelen', 'Website lanceren'];
        @endphp
        <div class="relative mt-14" x-data="{ rocketFlying: false, rocketStyle: '', launchRocket(element) { if (this.rocketFlying) return; const rect = element.querySelector('[data-shuttle]').getBoundingClientRect(); this.rocketStyle = `--rocket-x:${rect.left + rect.width / 2}px;--rocket-y:${rect.top + rect.height / 2}px;--rocket-travel-y:${-(rect.top + 220)}px`; this.rocketFlying = true; setTimeout(() => this.rocketFlying = false, 2900) } }">
            <div x-show="rocketFlying" :style="rocketStyle" class="site-rocket-launch pointer-events-none fixed z-[70] text-primary-500" style="display: none;" aria-hidden="true">
                <svg class="h-28 w-28 drop-shadow-2xl" viewBox="0 0 48 64" fill="none"><path d="M24 3C15 12 13 28 15 43h18c2-15 0-31-9-40Z" fill="#e2e8f0" stroke="#0ea5e9" stroke-width="2"/><path d="m15 30-10 15 11-3m17-12 10 15-11-3" fill="#94a3b8" stroke="#0ea5e9" stroke-width="2"/><path d="M19 43v8m10-8v8" stroke="#334155" stroke-width="3"/><path class="site-rocket-flame" d="M18 52 24 63l6-11" fill="#22d3ee" stroke="#0ea5e9" stroke-width="1.5"/><circle cx="24" cy="22" r="4" fill="#082f49" stroke="#67e8f9" stroke-width="1.5"/></svg>
            </div>
            <div x-show="rocketFlying" :style="rocketStyle" class="site-rocket-smoke pointer-events-none fixed z-[65]" style="display: none;" aria-hidden="true">
                <span></span><span></span><span></span><span></span><span></span>
            </div>
            <div class="grid items-stretch gap-6 md:grid-cols-2 lg:grid-cols-4 lg:gap-5">
                @foreach($steps as $index => $step)
                    <article @if($index === 3) @mouseenter="launchRocket($el)" @focusin="launchRocket($el)" tabindex="0" @endif class="group relative z-10 flex h-full flex-col rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-[transform,box-shadow] duration-200 hover:shadow-xl hover:-translate-y-1 hover:shadow-primary-900/10 sm:p-6">
                        <div class="relative h-52 overflow-hidden rounded-xl bg-slate-950 p-5 text-white sm:h-60 lg:h-48 lg:p-4">
                            <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#38bdf8 1px, transparent 1px); background-size: 16px 16px;"></div>
                            @if($index === 0)
                                <div class="absolute left-5 top-6 w-28 rounded-xl rounded-bl-sm bg-white p-3 shadow-lg transition-transform duration-200 group-hover:-translate-y-1"><div class="h-2 w-16 rounded bg-slate-300"></div><div class="mt-2 h-2 w-10 rounded bg-slate-200"></div></div>
                                <div class="absolute bottom-6 right-5 w-24 rounded-xl rounded-br-sm bg-primary-500 p-3 shadow-lg transition-transform duration-200 group-hover:translate-y-1"><div class="h-2 w-14 rounded bg-white/80"></div><div class="mt-2 h-2 w-9 rounded bg-white/50"></div></div>
                            @elseif($index === 1)
                                <div class="absolute inset-6 rounded-lg border-2 border-dashed border-primary-300 bg-primary-950/80 p-3 transition-colors duration-200 group-hover:border-cyan-300"><div class="h-3 w-20 rounded border border-primary-300"></div><div class="mt-3 h-10 rounded border border-primary-300/70"></div><div class="mt-3 grid grid-cols-3 gap-2"><span class="h-8 rounded border border-primary-300/60"></span><span class="h-8 rounded border border-primary-300/60"></span><span class="h-8 rounded border border-primary-300/60"></span></div></div>
                            @elseif($index === 2)
                                <div class="absolute inset-6 rounded-lg bg-white p-3 shadow-xl transition-transform duration-200 group-hover:scale-[1.02]"><div class="flex gap-1"><span class="h-1.5 w-1.5 rounded-full bg-rose-400"></span><span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span><span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span></div><div class="mt-3 h-9 rounded bg-primary-200"></div><div class="mt-3 grid grid-cols-2 gap-2"><span class="h-9 rounded bg-slate-100"></span><span class="h-9 rounded bg-slate-100"></span></div></div><span class="absolute bottom-4 right-4 rounded-lg bg-slate-900 px-2 py-1 font-mono text-xs text-cyan-300">&lt;/&gt;</span>
                            @else
                                <div class="absolute inset-6 rounded-lg bg-white p-3 shadow-xl transition-transform duration-200 group-hover:-translate-y-1"><div class="flex items-center justify-between"><div class="h-2 w-16 rounded bg-slate-200"></div><span class="rounded-full bg-emerald-100 px-2 py-1 text-[9px] font-bold text-emerald-700">LIVE</span></div><div class="mt-3 h-12 rounded bg-gradient-to-r from-primary-300 to-cyan-200"></div><div class="mt-3 flex gap-2"><span class="h-7 flex-1 rounded bg-slate-100"></span><span class="h-7 flex-1 rounded bg-slate-100"></span></div></div><span class="absolute right-4 top-4 h-3 w-3 rounded-full bg-cyan-300 shadow-[0_0_18px_6px_rgba(103,232,249,.45)]"></span>
                            @endif
                        </div>
                        <div class="mt-5 flex min-w-0 flex-1 flex-col">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-xs font-bold uppercase tracking-wider text-primary-600">{{ $buildLabels[$index] }}</span>
                                <span class="font-mono text-xs text-slate-400">0{{ $index + 1 }}/04</span>
                            </div>
                            <h3 class="mt-2 font-heading text-xl font-bold text-slate-900">{{ $step['title'] }}</h3>
                            <p class="mt-3 text-sm leading-7 text-slate-600">{{ $step['text'] }}</p>
                            <div class="relative mt-auto h-40 overflow-hidden rounded-xl bg-slate-950 ring-1 ring-slate-800" aria-label="Shuttlebouw fase {{ $index + 1 }} van 4">
                                <div class="absolute inset-0 opacity-20" style="background-image: linear-gradient(rgba(56,189,248,.18) 1px, transparent 1px), linear-gradient(90deg, rgba(56,189,248,.18) 1px, transparent 1px); background-size: 18px 18px;"></div>
                                <div class="absolute inset-x-3 bottom-4 h-1 rounded bg-slate-700"></div>
                                @if($index <= 2)
                                    <div class="absolute bottom-5 left-3 h-24 w-9 border-x-2 border-t-2 border-slate-500"><span class="absolute left-0 top-5 h-px w-full rotate-[32deg] bg-slate-500"></span><span class="absolute left-0 top-12 h-px w-full -rotate-[32deg] bg-slate-500"></span><span class="absolute left-0 top-[4.2rem] h-px w-full rotate-[32deg] bg-slate-500"></span></div>
                                @endif
                                @if($index >= 1)
                                    <div class="absolute right-3 top-4 h-2 w-20 bg-slate-500"><span class="absolute right-0 top-0 h-20 w-2 bg-slate-500"></span><span class="absolute left-5 top-2 h-10 w-px bg-cyan-300/70"></span><span class="absolute left-[1.05rem] top-11 h-2 w-2 rounded-full border border-cyan-300"></span></div>
                                @endif
                                @if($index === 2)<div class="absolute bottom-5 right-5 flex gap-1"><span class="h-2 w-2 animate-pulse rounded-full bg-emerald-400"></span><span class="h-2 w-2 rounded-full bg-amber-400"></span></div>@endif
                                @if($index <= 2)
                                    <div class="absolute bottom-5 left-[24%] z-20 h-8 w-5 text-cyan-300"><span class="absolute left-1.5 top-0 h-2 w-2 rounded-full bg-current"></span><span class="absolute left-2 top-2 h-4 w-1 rounded bg-current"></span><span class="absolute left-0 top-3 h-1 w-5 rotate-[-18deg] bg-current"></span><span class="absolute bottom-0 left-1 h-3 w-1 -rotate-12 bg-current"></span><span class="absolute bottom-0 right-1 h-3 w-1 rotate-12 bg-current"></span></div>
                                    <div class="absolute bottom-5 right-[20%] z-20 h-8 w-5 text-primary-300"><span class="absolute left-1.5 top-0 h-2 w-2 rounded-full bg-current"></span><span class="absolute left-2 top-2 h-4 w-1 rounded bg-current"></span><span class="absolute left-0 top-3 h-1 w-5 rotate-12 bg-current"></span><span class="absolute bottom-0 left-1 h-3 w-1 -rotate-12 bg-current"></span><span class="absolute bottom-0 right-1 h-3 w-1 rotate-12 bg-current"></span></div>
                                @endif
                                @if($index === 3)
                                    <div class="absolute bottom-4 left-[36%] h-3 w-24 -translate-x-1/2 rounded-t bg-slate-600"></div>
                                    <div class="absolute bottom-7 right-[10%] h-28 w-12 border-2 border-slate-500 bg-slate-900/80"><span class="absolute inset-x-0 top-5 h-px rotate-[28deg] bg-slate-500"></span><span class="absolute inset-x-0 top-12 h-px -rotate-[28deg] bg-slate-500"></span><span class="absolute inset-x-0 top-[4.7rem] h-px rotate-[28deg] bg-slate-500"></span><span class="absolute inset-x-0 top-4 h-1 bg-slate-500"></span><span class="absolute inset-x-0 top-10 h-1 bg-slate-500"></span><span class="absolute inset-x-0 top-[4.1rem] h-1 bg-slate-500"></span></div>
                                    <div class="absolute right-[19%] top-8 h-2 w-[38%] origin-right bg-slate-500"></div>
                                    <div class="absolute right-[19%] top-[4.1rem] h-2 w-[32%] origin-right bg-slate-600"></div>
                                    <div class="absolute right-[19%] top-[6.1rem] h-2 w-[26%] origin-right bg-slate-500"></div>
                                @endif
                                <svg @if($index === 3) data-shuttle @endif class="absolute bottom-4 h-28 w-24 -translate-x-1/2 transition-transform duration-200 {{ $index === 3 ? 'left-[36%] group-hover:-translate-y-1' : 'left-1/2' }}" viewBox="0 0 48 64" fill="none" aria-hidden="true">
                                    <path d="M24 4C16 13 14 27 15 45h18c1-18-1-32-9-41Z" stroke="#0ea5e9" stroke-width="2" stroke-dasharray="{{ $index === 0 ? '4 3' : '0' }}"/>
                                    <path d="M24 8v36M16 31h16" stroke="#64748b" stroke-width="1.5"/>
                                    @if($index >= 1)<path d="M24 5C18 14 17 27 18 42h12c1-15 0-28-6-37Z" fill="#e2e8f0"/><path d="M18 24h12M18 34h12" stroke="#94a3b8"/>@endif
                                    @if($index >= 2)<path d="M19 43v8m10-8v8" stroke="#334155" stroke-width="3"/><path d="M19 52 24 62l5-10" fill="#67e8f9" stroke="#0ea5e9" stroke-width="1.5"/>@endif
                                    @if($index === 3)<path d="m16 29-9 15 10-3m15-12 9 15-10-3" fill="#cbd5e1" stroke="#0ea5e9" stroke-width="1.5"/><circle cx="24" cy="21" r="4" fill="#082f49" stroke="#67e8f9" stroke-width="1.5"/>@endif
                                </svg>
                                <span class="absolute left-3 top-2 font-mono text-[10px] font-semibold text-cyan-200">ASSEMBLY {{ ($index + 1) * 25 }}%</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Webhosting -->
<section class="relative py-24 lg:py-32 bg-slate-950 text-white overflow-hidden" data-navbar-theme="dark">
    <div class="absolute top-0 left-1/4 w-[30rem] h-[30rem] bg-emerald-500/10 rounded-full blur-3xl" aria-hidden="true"></div>
    <div class="absolute bottom-0 right-1/4 w-[30rem] h-[30rem] bg-cyan-500/10 rounded-full blur-3xl" aria-hidden="true"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6">
        <div class="max-w-2xl mb-16 lg:mb-20 animate-on-scroll">
            <span class="inline-flex items-center gap-2 font-mono text-emerald-400 text-sm mb-4">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                &gt; webhosting --status online
            </span>
            <h2 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-[1.05] tracking-tight">
                Snel, veilig en <span class="text-emerald-400">altijd online</span>
            </h2>
            <p class="text-lg text-slate-400 leading-relaxed">
                Kies het hostingpakket dat bij uw website past. Onze servers draaien in Europese datacenters, zodat uw bezoekers altijd een snelle verbinding hebben.
            </p>
        </div>

        @php
            $hostingServices = $services->where('service_type', 'hosting');
            // TODO: vervang door de daadwerkelijke datacenterlocaties zodra de infrastructuur is bevestigd.
            // Coördinaten zijn procentuele posities op de wereldkaart-afbeelding (lat/lng omgerekend naar equirectangular %).
            $datacenters = [
                ['city' => 'Ede', 'country' => 'Nederland', 'flag' => '🇳🇱', 'badge' => 'Primair', 'left' => 49.6, 'top' => 35.1,
                    'blurb' => 'Onze hoofdlocatie: snelle SSD-servers op een kort, direct netwerk.',
                    'address' => 'Ede, Gelderland', 'specs' => ['Tier III+ datacenter', 'Redundante stroomvoorziening', '10 Gbps netwerkaansluiting', '24/7 bewaking ter plaatse']],
                ['city' => 'Frankfurt', 'country' => 'Duitsland', 'flag' => '🇩🇪', 'badge' => 'Backup', 'left' => 50.4, 'top' => 36.2,
                    'blurb' => 'Redundante back-uplocatie voor extra uitvalveiligheid en snelle failover.',
                    'address' => 'Frankfurt am Main', 'specs' => ['DE-CIX internetknooppunt', 'Automatische failover', 'N+1 koeling', 'ISO 27001 gecertificeerd']],
                ['city' => 'Helsinki', 'country' => 'Finland', 'flag' => '🇫🇮', 'badge' => 'Duurzaam', 'left' => 51.9, 'top' => 30.6,
                    'blurb' => 'Groene locatie met natuurlijke koeling voor een lage CO2-voetafdruk.',
                    'address' => 'Helsinki', 'specs' => ['100% hernieuwbare energie', 'Natuurlijke koeling', 'Lage latency Noord-Europa', 'Duurzaamheidscertificering']],
                ['city' => 'Ashburn', 'country' => 'Verenigde Staten', 'flag' => '🇺🇸', 'badge' => 'Noord-Amerika', 'left' => 28.9, 'top' => 38.9,
                    'blurb' => 'Amerikaanse locatie in ‘Data Center Alley’ voor lage latency richting Noord-Amerikaanse bezoekers.',
                    'address' => 'Ashburn, VA', 'specs' => ['Directe transatlantische verbinding', 'Redundante uplinks', '24/7 support', 'DDoS-bescherming']],
            ];
        @endphp

        <!-- Hosting package cards -->
        <div class="space-y-4 mb-16 lg:mb-20"
            x-data="{ hOpen: false, h: null, showH(s) { this.h = s; this.hOpen = true; document.body.style.overflow = 'hidden'; }, hideH() { this.hOpen = false; this.h = null; document.body.style.overflow = 'auto'; } }"
            @keydown.escape.window="hideH()">
            @foreach($hostingServices as $service)
                @php
                    $isPop = $service->is_popular;
                    $hostingData = [
                        'title' => $service->title,
                        'short_description' => $service->short_description,
                        'description' => $service->description,
                        'formatted_price' => $service->formatted_price,
                        'features' => $service->features ?? [],
                        'slug' => $service->slug,
                        'checkout_url' => $service->prices->isNotEmpty() ? route('checkout.show', $service) : route('contact') . '?service=' . $service->slug,
                    ];
                @endphp
                <div class="relative rounded-2xl px-5 py-4 sm:px-6 sm:py-5 bg-white/[0.03] backdrop-blur ring-1 {{ $isPop ? 'ring-emerald-400/50 shadow-[0_0_30px_-12px_rgba(52,211,153,0.35)]' : 'ring-white/10' }} animate-on-scroll">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="font-heading text-lg font-bold text-white truncate">{{ $service->title }}</h3>
                                @if($isPop)
                                    <span class="font-mono text-[10px] text-emerald-400 uppercase tracking-wide shrink-0">// populair</span>
                                @endif
                            </div>
                            <p class="text-sm text-slate-400 truncate">{{ $service->short_description }}</p>
                        </div>
                        <div class="font-mono text-2xl font-bold {{ $isPop ? 'text-emerald-400' : 'text-cyan-400' }} shrink-0">
                            {{ $service->formatted_price }}
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <button type="button" @click="showH(@js($hostingData))" class="btn bg-white/10 text-white hover:bg-white/20 ring-1 ring-white/20 px-4 py-2 text-sm">
                                Meer info
                            </button>
                            <a href="{{ $service->prices->isNotEmpty() ? route('checkout.show', $service) : route('contact') . '?service=' . $service->slug }}" class="btn px-4 py-2 text-sm {{ $isPop ? 'bg-emerald-500 text-slate-950 hover:bg-emerald-400' : 'bg-white/10 text-white hover:bg-white/20 ring-1 ring-white/20' }}">
                                {{ $service->prices->isNotEmpty() ? 'Bestel direct' : 'Neem contact op' }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Hosting detail modal -->
            <div x-show="hOpen" class="fixed inset-0 z-[70] flex items-center justify-center p-4 sm:p-6" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click.self="hideH()" role="dialog" aria-modal="true">
                <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" aria-hidden="true"></div>
                <div x-show="hOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-slate-900 rounded-2xl shadow-2xl ring-1 ring-white/10">
                    <div class="sticky top-0 z-10 flex items-center justify-between px-6 py-4 border-b border-white/10 bg-slate-900/95 backdrop-blur">
                        <h3 class="font-heading text-2xl font-bold text-white" x-text="h?.title"></h3>
                        <button type="button" @click="hideH()" class="p-2 rounded-full text-slate-400 hover:text-white hover:bg-white/10 transition-colors" aria-label="Sluiten">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="p-6">
                        <template x-if="h">
                            <div>
                                <p class="text-slate-400 mb-6" x-text="h.short_description"></p>
                                <div class="prose prose-invert prose-sm max-w-none mb-6" x-html="h.description"></div>
                                <template x-if="h.features && h.features.length > 0">
                                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-6 font-mono text-sm text-slate-300">
                                        <template x-for="feature in h.features" :key="feature">
                                            <li class="flex items-start">
                                                <span class="text-emerald-400 mr-2">$</span>
                                                <span x-text="feature"></span>
                                            </li>
                                        </template>
                                    </ul>
                                </template>
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-white/5 rounded-xl">
                                    <div>
                                        <span class="text-sm text-slate-500">Investering</span>
                                        <div class="font-mono text-2xl font-bold text-emerald-400" x-text="h.formatted_price"></div>
                                    </div>
                                    <a :href="h.checkout_url" class="btn bg-emerald-500 text-slate-950 hover:bg-emerald-400 whitespace-nowrap">
                                        Bestel direct
                                    </a>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Datacenter map -->
        <div class="animate-on-scroll">
            <h3 class="font-heading text-2xl font-bold text-white mb-6 flex items-center gap-2">
                <span class="font-mono text-emerald-400 text-lg">&gt;_</span> Onze datacenterlocaties
            </h3>

            <div x-data="{ active: 0, dcOpen: false, dc: null, showDc(d) { this.dc = d; this.dcOpen = true; document.body.style.overflow = 'hidden'; }, hideDc() { this.dcOpen = false; this.dc = null; document.body.style.overflow = 'auto'; } }"
                @keydown.escape.window="hideDc()"
                class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
                <!-- Info panel: 1/3 -->
                <div class="lg:col-span-1 rounded-2xl bg-white/[0.03] ring-1 ring-white/10 p-6 flex flex-col">
                    @foreach($datacenters as $i => $dc)
                        <div x-show="active === {{ $i }}" x-cloak class="flex-1 flex flex-col">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-3xl">{{ $dc['flag'] }}</span>
                                <span class="font-mono text-[11px] uppercase tracking-wide px-2.5 py-1 rounded-full bg-emerald-400/10 text-emerald-400 ring-1 ring-emerald-400/30">{{ $dc['badge'] }}</span>
                            </div>
                            <h4 class="font-heading text-xl font-bold text-white mb-1">{{ $dc['city'] }}</h4>
                            <p class="font-mono text-xs text-slate-500 mb-4">{{ $dc['country'] }}</p>
                            <p class="text-sm text-slate-400 leading-relaxed mb-4">{{ $dc['blurb'] }}</p>
                            <button type="button" @click="showDc(@js($dc))" class="inline-flex items-center gap-1.5 self-start rounded-full px-3.5 py-1.5 text-xs font-mono ring-1 ring-emerald-400/30 text-emerald-400 hover:bg-emerald-400/10 transition-colors">
                                Meer informatie
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </button>
                            <div class="mt-auto pt-6 flex items-center gap-2 font-mono text-xs text-slate-500">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                status: operationeel
                            </div>
                        </div>
                    @endforeach

                    <div class="flex flex-wrap gap-2 mt-6 pt-6 border-t border-white/10">
                        @foreach($datacenters as $i => $dc)
                            <button type="button" @click="active = {{ $i }}"
                                class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-mono ring-1 transition-colors"
                                :class="active === {{ $i }} ? 'bg-emerald-400 text-slate-950 ring-emerald-400' : 'bg-white/5 text-slate-300 ring-white/10 hover:ring-emerald-400/40'">
                                {{ $dc['flag'] }} {{ $dc['city'] }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- World map: 2/3 -->
                <div class="lg:col-span-2 lg:self-start relative w-full aspect-[8/5] rounded-2xl overflow-hidden ring-1 ring-white/10 bg-slate-950">
                    <div class="absolute inset-0" style="mask-image: linear-gradient(to bottom, transparent, black 10%, black 90%, transparent); -webkit-mask-image: linear-gradient(to bottom, transparent, black 10%, black 90%, transparent);">
                        <div class="absolute inset-0" style="background-color: #34d399; -webkit-mask-image: url('https://upload.wikimedia.org/wikipedia/commons/e/ec/World_map_%28blue_dots%29.svg'); -webkit-mask-size: 100% 100%; -webkit-mask-repeat: no-repeat; -webkit-mask-position: center; mask-image: url('https://upload.wikimedia.org/wikipedia/commons/e/ec/World_map_%28blue_dots%29.svg'); mask-size: 100% 100%; mask-repeat: no-repeat; mask-position: center;"></div>
                        <svg viewBox="0 0 800 400" preserveAspectRatio="none" class="absolute inset-0 w-full h-full pointer-events-none select-none">
                            <defs>
                                <linearGradient id="map-path-gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="#22d3ee" stop-opacity="0" />
                                    <stop offset="15%" stop-color="#22d3ee" stop-opacity="1" />
                                    <stop offset="85%" stop-color="#22d3ee" stop-opacity="1" />
                                    <stop offset="100%" stop-color="#22d3ee" stop-opacity="0" />
                                </linearGradient>
                            </defs>
                            <path class="map-flightpath" d="M396.6,140.4 Q400,125.4 403.3,144.6" fill="none" stroke="url(#map-path-gradient)" stroke-width="1.5" :class="{ 'is-drawn': active === 1 }" />
                            <path class="map-flightpath" d="M396.6,140.4 Q394,97.3 415.4,122.3" fill="none" stroke="url(#map-path-gradient)" stroke-width="1.5" :class="{ 'is-drawn': active === 2 }" />
                            <path class="map-flightpath" d="M396.6,140.4 Q320,70.4 231.5,155.5" fill="none" stroke="url(#map-path-gradient)" stroke-width="1.5" :class="{ 'is-drawn': active === 3 }" />
                        </svg>
                    </div>
                    @foreach($datacenters as $i => $dc)
                        <button type="button" @click="active = {{ $i }}"
                            class="absolute -translate-x-1/2 -translate-y-1/2"
                            style="left: {{ $dc['left'] }}%; top: {{ $dc['top'] }}%;"
                            aria-label="{{ $dc['city'] }}">
                            @if($i > 0)
                                <span class="absolute -inset-2 rounded-full bg-purple-500 opacity-20 animate-ping" :class="active === {{ $i }} ? 'opacity-0' : 'opacity-20'" style="animation-delay: {{ $i * 0.4 }}s"></span>
                                <span class="absolute -inset-2 rounded-full bg-blue-500 animate-ping" :class="active === {{ $i }} ? 'opacity-60' : 'opacity-0'"></span>
                            @endif
                            <span class="relative block h-3 w-3 rounded-full ring-2 ring-slate-950 shadow-[0_0_8px_rgba(59,130,246,0.8)] transition-transform duration-200"
                                :class="active === {{ $i }} ? 'bg-blue-500 scale-150' : 'bg-purple-500 hover:scale-125'"></span>
                        </button>
                    @endforeach
                </div>

                <!-- Datacenter detail modal -->
                <div x-show="dcOpen" class="fixed inset-0 z-[70] flex items-center justify-center p-4 sm:p-6" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click.self="hideDc()" role="dialog" aria-modal="true">
                    <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" aria-hidden="true"></div>
                    <div x-show="dcOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="relative w-full max-w-lg max-h-[90vh] overflow-y-auto bg-slate-900 rounded-2xl shadow-2xl ring-1 ring-white/10">
                        <div class="sticky top-0 z-10 flex items-center justify-between px-6 py-4 border-b border-white/10 bg-slate-900/95 backdrop-blur">
                            <div class="flex items-center gap-2">
                                <span class="text-2xl" x-text="dc?.flag"></span>
                                <h3 class="font-heading text-xl font-bold text-white" x-text="dc?.city"></h3>
                            </div>
                            <button type="button" @click="hideDc()" class="p-2 rounded-full text-slate-400 hover:text-white hover:bg-white/10 transition-colors" aria-label="Sluiten">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <div class="p-6">
                            <template x-if="dc">
                                <div>
                                    <span class="font-mono text-[11px] uppercase tracking-wide px-2.5 py-1 rounded-full bg-emerald-400/10 text-emerald-400 ring-1 ring-emerald-400/30" x-text="dc.badge"></span>
                                    <p class="font-mono text-sm text-slate-500 mt-3" x-text="dc.address"></p>
                                    <p class="text-sm text-slate-300 leading-relaxed mt-3 mb-6" x-text="dc.blurb"></p>
                                    <template x-if="dc.specs && dc.specs.length > 0">
                                        <ul class="space-y-2 font-mono text-sm text-slate-300">
                                            <template x-for="spec in dc.specs" :key="spec">
                                                <li class="flex items-start">
                                                    <span class="text-emerald-400 mr-2">$</span>
                                                    <span x-text="spec"></span>
                                                </li>
                                            </template>
                                        </ul>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="relative overflow-hidden bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900" data-navbar-theme="dark">
    <div class="absolute -top-24 -right-16 h-96 w-96 rounded-full bg-accent-400/20 blur-3xl"></div>
    <div class="absolute -bottom-32 -left-16 h-96 w-96 rounded-full bg-primary-400/20 blur-3xl"></div>
    <div class="absolute inset-0 opacity-[0.15] cta-grid" aria-hidden="true"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-6 py-24 lg:py-28 text-center">
        <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3.5 py-1.5 text-sm font-medium text-primary-50 backdrop-blur animate-on-scroll">
            <span class="relative flex h-2 w-2">
                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-accent-300 opacity-75"></span>
                <span class="relative inline-flex h-2 w-2 rounded-full bg-accent-300"></span>
            </span>
            Nu beschikbaar voor nieuwe projecten
        </span>
        <h2 class="mt-7 font-heading text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-[1.05] tracking-tight animate-on-scroll">
            Klaar om uw nieuwe website te starten?
        </h2>
        <p class="mt-6 mx-auto max-w-xl text-lg text-primary-100 leading-relaxed animate-on-scroll">
            Plan een vrijblijvend gesprek. Wij denken met u mee, u zit nergens aan vast en binnen 1 werkdag hoort u van ons.
        </p>
        <div class="mt-10 flex flex-col sm:flex-row sm:items-center sm:justify-center gap-4 animate-on-scroll">
            <a href="{{ route('contact') }}" class="btn btn-light text-base px-8 py-4">
                Plan een vrijblijvend gesprek
            </a>
            {{-- TODO: vervang ‹TELEFOON› door het echte telefoonnummer vóór livegang --}}
            <a href="tel:‹TELEFOON›" class="inline-flex items-center justify-center gap-2 font-medium text-primary-50 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                Of bel ons direct
            </a>
        </div>
    </div>
</section>

@endsection
