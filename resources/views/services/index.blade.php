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
                Alles voor uw website, van start tot groei
            </h1>
            <p class="mt-6 max-w-xl text-lg leading-relaxed text-white/90">
                Van ontwerp en ontwikkeling tot hosting en onderhoud. Kies het pakket dat bij uw organisatie past en breid later eenvoudig uit wanneer dat nodig is.
            </p>
        </div>
    </div>
</section>

<!-- Webdesign Producten -->
<section id="pakketten" class="relative bg-slate-50 pt-12 lg:pt-16 pb-12 lg:pb-16"
    x-data="{ open: false, service: null, show(s) { this.service = s; this.open = true; document.body.style.overflow = 'hidden'; }, hide() { this.open = false; this.service = null; document.body.style.overflow = 'auto'; } }"
    @keydown.escape.window="hide">
    <div class="max-w-7xl mx-auto px-6">
        <div class="mb-12 animate-on-scroll">
            <span class="text-accent-600 font-semibold tracking-wide uppercase text-sm mb-4 block">Onze diensten</span>
            <h2 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 mb-6 leading-[1.05] tracking-tight">Kies het pakket dat bij u past</h2>
            <p class="text-lg text-slate-600 max-w-2xl">Van een compacte website tot uitgebreidere oplossingen voor bedrijven die meer nodig hebben. Elk pakket bevat een duidelijke basis en kan waar nodig worden uitgebreid.</p>
        </div>

        @php
            $webdesignServices = $services->where('service_type', 'website_pakket')->where('slug', '!=', 'test')->take(3);
        @endphp

        @if($webdesignServices->count() > 0)
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
                    <div class="group relative bg-white rounded-3xl transition-all duration-300 animate-on-scroll flex flex-col {{ $isRecommended ? 'z-10 ring-2 ring-accent-400 shadow-xl shadow-primary-900/10 md:-translate-y-3' : 'ring-1 ring-slate-200 shadow-lg shadow-slate-900/5 hover:-translate-y-1 hover:shadow-xl' }}">
                        @if($isRecommended)
                            <span class="absolute -top-3 right-4 z-20 inline-flex items-center rounded-full bg-gradient-to-r from-primary-500 to-accent-500 px-3 py-1 text-xs font-bold text-white shadow-md">
                                Aanbevolen
                            </span>
                        @endif
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
                        </div>
                    </div>
                @endforeach
            </div>


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
<section class="bg-slate-50 pt-12 lg:pt-16 pb-24 lg:pb-32">
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
        <div class="relative mt-14">
            <div class="grid items-stretch gap-6 md:grid-cols-2 lg:grid-cols-4 lg:gap-5">
                @foreach($steps as $index => $step)
                    <article class="group relative z-10 flex h-full flex-col rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-[transform,box-shadow] duration-200 hover:shadow-xl hover:-translate-y-1 hover:shadow-primary-900/10 sm:p-6">
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
                Serverlocaties in Europa en de Verenigde Staten
            </span>
            <h2 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-[1.05] tracking-tight">
                Snel, veilig en <span class="text-emerald-400">altijd online</span>
            </h2>
            <p class="text-lg text-slate-400 leading-relaxed">
                Snelle hosting, dagelijkse back-ups en SSL zijn standaard inbegrepen. Onze hosting draait in professionele datacenters in Europa en de Verenigde Staten, zodat we per website een passende serverlocatie kunnen kiezen.
            </p>
        </div>

        @php
            $hostingServices = $services->where('service_type', 'hosting');
            // TODO: vervang door de daadwerkelijke datacenterlocaties zodra de infrastructuur is bevestigd.
            // Coördinaten zijn procentuele posities op de wereldkaart-afbeelding (lat/lng omgerekend naar equirectangular %).
            $datacenters = [
                ['city' => 'Ede', 'country' => 'Nederland', 'flag' => '🇳🇱', 'badge' => 'Primair', 'left' => 49.6, 'top' => 35.1,
                    'blurb' => 'Onze standaardlocatie voor Nederlandse en Europese websites, met een snelle verbinding en lage latency.',
                    'address' => 'Ede, Gelderland', 'specs' => ['Tier III+ datacenter', 'Redundante stroomvoorziening', '10 Gbps netwerkaansluiting', '24/7 bewaking ter plaatse']],
                ['city' => 'Frankfurt', 'country' => 'Duitsland', 'flag' => '🇩🇪', 'badge' => 'Backup', 'left' => 50.4, 'top' => 36.2,
                    'blurb' => 'Centrale Europese locatie voor websites en diensten met bezoekers verspreid over Europa.',
                    'address' => 'Frankfurt am Main', 'specs' => ['DE-CIX internetknooppunt', 'Automatische failover', 'N+1 koeling', 'ISO 27001 gecertificeerd']],
                ['city' => 'Helsinki', 'country' => 'Finland', 'flag' => '🇫🇮', 'badge' => 'Duurzaam', 'left' => 51.9, 'top' => 30.6,
                    'blurb' => 'Europese locatie voor extra spreiding en specifieke hostingbehoeften.',
                    'address' => 'Helsinki', 'specs' => ['100% hernieuwbare energie', 'Natuurlijke koeling', 'Lage latency Noord-Europa', 'Duurzaamheidscertificering']],
                ['city' => 'Ashburn', 'country' => 'Verenigde Staten', 'flag' => '🇺🇸', 'badge' => 'Noord-Amerika', 'left' => 28.9, 'top' => 38.9,
                    'blurb' => 'Amerikaanse locatie voor websites en diensten die dichter bij bezoekers in Noord-Amerika moeten draaien.',
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
            <h3 class="font-heading text-2xl font-bold text-white mb-6">
                Serverlocaties in Europa en de Verenigde Staten
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
        </div>
    </div>
</section>

@endsection
