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
    <img
        src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?fm=jpg&q=80&w=1920&auto=format&fit=crop"
        alt="Laptop met website op een bureau"
        class="absolute inset-0 w-full h-full object-cover opacity-10"
    >
    <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-950/90 to-slate-950/60"></div>

    <div class="relative max-w-7xl mx-auto px-6 py-24 lg:py-32">
        <div class="max-w-2xl animate-slide-up">
            <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3.5 py-1.5 text-sm font-medium text-slate-200 mb-6">
                <span class="relative flex h-2 w-2">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-accent-400 opacity-75"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-accent-400"></span>
                </span>
                Duidelijke pakketten, heldere prijzen
            </span>
            <h1 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold leading-[1.05] tracking-tight">
                Websites en hosting die met uw bedrijf meegroeien.
            </h1>
            <p class="mt-6 max-w-xl text-lg leading-relaxed text-white/90">
                Kies het pakket dat past bij uw ambities. Wij regelen ontwerp, techniek, hosting en onderhoud — zodat u zich kunt richten op ondernemen.
            </p>
            <div class="mt-8 flex flex-col sm:flex-row sm:items-center gap-4">
                <a href="#pakketten" class="btn btn-light text-base px-7 py-3.5">
                    Bekijk de pakketten
                </a>
                <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 font-medium text-slate-200 hover:text-white transition-colors">
                    Of vraag direct een offerte aan
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Guarantees -->
<section class="relative bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 py-16">
        <div class="rounded-2xl bg-white ring-1 ring-slate-200/80 shadow-sm animate-on-scroll">
            <dl class="grid grid-cols-2 lg:grid-cols-4 divide-y divide-slate-100 lg:divide-y-0 lg:divide-x">
                <div class="flex items-center gap-3 px-6 py-5">
                    <svg class="h-5 w-5 flex-shrink-0 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75C4.5 20.496 5.004 21 5.625 21h10.5c.621 0 1.125-.504 1.125-1.125v-6.75c0-.621-.504-1.125-1.125-1.125H6.75z"/></svg>
                    <div>
                        <dt class="text-sm font-semibold text-slate-900">SSL-beveiliging</dt>
                        <dd class="text-sm text-slate-500">Standaard inbegrepen</dd>
                    </div>
                </div>
                <div class="flex items-center gap-3 px-6 py-5">
                    <svg class="h-5 w-5 flex-shrink-0 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125v-11.25m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75"/></svg>
                    <div>
                        <dt class="text-sm font-semibold text-slate-900">Dagelijkse back-ups</dt>
                        <dd class="text-sm text-slate-500">Automatisch geregeld</dd>
                    </div>
                </div>
                <div class="flex items-center gap-3 px-6 py-5">
                    <svg class="h-5 w-5 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                    <div>
                        <dt class="text-sm font-semibold text-slate-900">99,9% uptime</dt>
                        <dd class="text-sm text-slate-500">Uw site blijft online</dd>
                    </div>
                </div>
                <div class="flex items-center gap-3 px-6 py-5">
                    <svg class="h-5 w-5 flex-shrink-0 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <dt class="text-sm font-semibold text-slate-900">Binnen 1 werkdag</dt>
                        <dd class="text-sm text-slate-500">Antwoord op uw vraag</dd>
                    </div>
                </div>
            </dl>
        </div>
    </div>
</section>

<!-- Packages -->
<section id="pakketten" class="relative bg-slate-50 overflow-hidden py-24 lg:py-32">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16 animate-on-scroll">
            <span class="text-accent-600 font-semibold tracking-wide uppercase text-sm mb-4 block">Onze pakketten</span>
            <h2 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 mb-6 leading-[1.05] tracking-tight">Kies wat bij u past</h2>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">Elk pakket is volledig ontzorgd. Hosting, onderhoud en support zitten standaard inbegrepen.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($services as $service)
                <div class="group bg-white rounded-2xl ring-1 ring-slate-200 shadow-xl shadow-slate-900/5 overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 animate-on-scroll flex flex-col">
                    @if($service->image_url)
                        <div class="h-48 overflow-hidden">
                            <img src="{{ $service->image_url }}" alt="{{ $service->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                    @else
                        <div class="h-48 bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center">
                            <span class="text-6xl font-black text-white/25 select-none">{{ mb_strtoupper(mb_substr($service->title, 0, 1)) }}</span>
                        </div>
                    @endif
                    <div class="p-8 flex-1 flex flex-col">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-heading text-2xl font-bold text-slate-900">{{ $service->title }}</h3>
                            @if($service->is_popular)
                                <span class="px-3 py-1 rounded-full bg-accent-100 text-accent-700 text-xs font-semibold uppercase tracking-wide">Populair</span>
                            @endif
                        </div>
                        <p class="text-slate-600 mb-6">{{ $service->short_description }}</p>

                        @if($service->features && count($service->features) > 0)
                            <ul class="space-y-3 mb-8 flex-1">
                                @foreach(array_slice($service->features, 0, 6) as $feature)
                                    <li class="flex items-start text-sm text-slate-700">
                                        <svg class="w-5 h-5 text-accent-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        <div class="flex items-baseline gap-2 mb-6">
                            <span class="text-3xl font-bold text-slate-900">{{ $service->formatted_price }}</span>
                        </div>

                        <a href="{{ route('services.show', $service) }}" class="btn btn-primary w-full">
                            Bekijk {{ $service->title }}
                        </a>
                    </div>
                </div>
            @endforeach
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
