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
            <h2 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 mb-6 leading-[1.05] tracking-tight">Website pakketten</h2>
            <p class="text-lg text-slate-600 max-w-2xl">Bekijk onze webdesign pakketten. Klik op een product voor alle details.</p>
        </div>

        @php
            $webdesignServices = $services->where('service_type', 'website_pakket');
        @endphp

        @if($webdesignServices->count() > 0)
            <div class="flex overflow-x-auto pb-6 gap-6 snap-x snap-mandatory scroll-smooth [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                @foreach($webdesignServices as $service)
                    @php
                        $serviceData = [
                            'title' => $service->title,
                            'short_description' => $service->short_description,
                            'description' => $service->description,
                            'formatted_price' => $service->formatted_price,
                            'features' => $service->features ?? [],
                            'image_url' => $service->image_url,
                            'slug' => $service->slug,
                        ];
                    @endphp
                    <div class="group snap-start shrink-0 w-[300px] md:w-[360px] bg-white rounded-2xl ring-1 ring-slate-200 shadow-xl shadow-slate-900/5 overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 animate-on-scroll flex flex-col">
                        @if($service->image_url)
                            <div class="h-48 overflow-hidden">
                                <img src="{{ $service->image_url }}" alt="{{ $service->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                        @else
                            <div class="h-48 bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center">
                                <span class="text-6xl font-black text-white/25 select-none">{{ mb_strtoupper(mb_substr($service->title, 0, 1)) }}</span>
                            </div>
                        @endif
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-heading text-xl font-bold text-slate-900">{{ $service->title }}</h3>
                                @if($service->is_popular)
                                    <span class="px-3 py-1 rounded-full bg-accent-100 text-accent-700 text-xs font-semibold uppercase tracking-wide">Populair</span>
                                @endif
                            </div>
                            <p class="text-slate-600 text-sm mb-6 flex-1">{{ $service->short_description }}</p>
                            <div class="flex items-baseline gap-2 mb-6">
                                <span class="text-2xl font-bold text-slate-900">{{ $service->formatted_price }}</span>
                            </div>
                            <button type="button" @click="show(@js($serviceData))" class="btn btn-primary w-full" aria-haspopup="dialog" aria-controls="service-modal">
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

    <!-- Service Modal -->
    <div id="service-modal" x-show="open" class="fixed inset-0 z-[70] flex items-center justify-center p-4 sm:p-6" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click.self="hide" role="dialog" aria-modal="true" aria-labelledby="service-modal-title">
        <div class="absolute inset-0 bg-slate-950/70 backdrop-blur-sm" aria-hidden="true"></div>
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-white rounded-2xl shadow-2xl ring-1 ring-slate-200">
            <div class="sticky top-0 z-10 flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white/95 backdrop-blur">
                <h3 id="service-modal-title" class="font-heading text-2xl font-bold text-slate-900" x-text="service?.title"></h3>
                <button type="button" @click="hide" class="p-2 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors" aria-label="Sluiten">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6">
                <template x-if="service">
                    <div>
                        <div x-show="service.image_url" class="mb-6 rounded-xl overflow-hidden">
                            <img :src="service.image_url" :alt="service.title" class="w-full h-56 object-cover">
                        </div>
                        <p class="text-slate-600 mb-6" x-text="service.short_description"></p>
                        <div class="prose prose-slate max-w-none mb-6" x-html="service.description"></div>

                        <template x-if="service.features && service.features.length > 0">
                            <div class="mb-6">
                                <h4 class="font-heading font-semibold text-slate-900 mb-3">Wat zit erin?</h4>
                                <ul class="space-y-2">
                                    <template x-for="feature in service.features" :key="feature">
                                        <li class="flex items-start text-sm text-slate-700">
                                            <svg class="w-5 h-5 text-accent-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            <span x-text="feature"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </template>

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-slate-50 rounded-xl">
                            <div>
                                <span class="text-sm text-slate-500">Investering</span>
                                <div class="text-2xl font-bold text-slate-900" x-text="service.formatted_price"></div>
                            </div>
                            <a :href="'{{ route('contact') }}?service=' + service.slug" class="btn btn-primary whitespace-nowrap">
                                Neem contact op
                            </a>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</section>

<!-- Process -->
<section class="py-24 lg:py-32 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-start">
            <div class="animate-on-scroll">
                <span class="text-accent-600 font-semibold tracking-wide uppercase text-sm mb-4 block">Werkwijze</span>
                <h2 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 mb-6 leading-[1.05] tracking-tight">Van idee tot live website</h2>
                <p class="text-lg text-slate-600 leading-relaxed">
                    Wij begeleiden u bij elke stap. U hoeft geen technische kennis te hebben; wij zorgen dat alles duidelijk en overzichtelijk blijft.
                </p>
            </div>
            <div class="space-y-6">
                @foreach($steps as $index => $step)
                    <div class="flex gap-4 animate-on-scroll">
                        <span class="flex-shrink-0 inline-flex h-10 w-10 items-center justify-center rounded-full bg-primary-50 text-primary-600 ring-1 ring-primary-100 font-heading font-bold">
                            {{ $index + 1 }}
                        </span>
                        <div>
                            <h4 class="font-heading font-semibold text-slate-900">{{ $step['title'] }}</h4>
                            <p class="mt-1 text-slate-600 leading-relaxed">{{ $step['text'] }}</p>
                        </div>
                    </div>
                @endforeach
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
