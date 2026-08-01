@extends('layouts.app')

@section('title', 'Diensten - Servura')
@section('meta-description', 'Bekijk alle diensten van Servura: webdesign, hosting, onderhoud en meer. Professionele oplossingen voor het MKB.')
@section('meta-keywords', 'diensten, webdesign, hosting, onderhoud, mkb, website ontwikkeling')

@section('content')
@php
$featuredServices = $services->sortBy('sort_order')->values()->take(3);
$first = $featuredServices[0] ?? null;
$second = $featuredServices[1] ?? null;
$third = $featuredServices[2] ?? null;

$steps = [
    ['title' => 'Kennismaking', 'text' => 'Iedere samenwerking begint met een vrijblijvend kennismakingsgesprek. Tijdens dit gesprek bespreken we uw bedrijf, doelgroep, wensen en doelstellingen. Op basis daarvan geven we advies over de beste oplossing voor uw online aanwezigheid.', 'color' => 'primary'],
    ['title' => 'Voorstel', 'text' => 'Na het kennismakingsgesprek ontvangt u een helder en overzichtelijk voorstel. Hierin beschrijven we de werkzaamheden, planning, investering en eventuele aanvullende mogelijkheden, zodat u precies weet waar u aan toe bent.', 'color' => 'accent'],
    ['title' => 'Ontwikkeling', 'text' => 'Na akkoord starten we met het ontwerpen en ontwikkelen van uw website. Tijdens dit proces houden we u op de hoogte van de voortgang en is er ruimte voor feedback, zodat het eindresultaat volledig aansluit bij uw verwachtingen.', 'color' => 'secondary'],
    ['title' => 'Lancering', 'text' => 'Wanneer de website volledig is getest en goedgekeurd, verzorgen wij de livegang. Ook na de lancering blijven wij beschikbaar voor hosting, onderhoud, beveiligingsupdates en ondersteuning, zodat uw website veilig, snel en altijd optimaal blijft presteren.', 'color' => 'emerald'],
];
@endphp

<!-- Hero Section -->
<section class="relative -mt-16 pt-40 lg:pt-48 pb-24 lg:pb-32 overflow-hidden bg-gradient-to-br from-primary-900 via-primary-800 to-secondary-900 text-white" data-navbar-theme="dark">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-accent-900/20 via-transparent to-transparent" aria-hidden="true"></div>

    <div class="relative z-10 max-w-5xl mx-auto px-6 text-center">
        <h1 class="font-heading text-4xl md:text-6xl lg:text-7xl font-bold mb-6 leading-[1.05] tracking-tight animate-slide-up">
            Diensten die <span class="text-accent-200">uw groei</span> versnellen
        </h1>
        <p class="text-xl md:text-2xl text-primary-100 max-w-3xl mx-auto mb-10 animate-slide-up" style="animation-delay: 0.1s">
            Drie heldere pakketten, van start-up tot uitgebreide webshop. Kies wat bij uw ambities past.
        </p>
        <div class="animate-slide-up" style="animation-delay: 0.2s">
            <a href="#pakketten" class="btn btn-light text-lg px-8 py-4 hover:scale-105 transition-transform">
                Bekijk de pakketten
            </a>
        </div>
    </div>
</section>

@if($first)
<!-- Featured Service -->
<section id="basis" class="relative py-20 lg:py-28 bg-gradient-to-b from-slate-50 via-white to-slate-50 overflow-hidden">
    <div class="absolute top-0 right-0 w-[35rem] h-[35rem] bg-primary-200/30 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3" aria-hidden="true"></div>
    <div class="absolute bottom-0 left-0 w-[30rem] h-[30rem] bg-accent-200/30 rounded-full blur-3xl translate-y-1/3 -translate-x-1/3" aria-hidden="true"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6">
        <div class="text-center mb-16 animate-on-scroll">
            <span class="inline-block px-4 py-1.5 rounded-full bg-accent-100 text-accent-700 text-sm font-semibold tracking-wide uppercase mb-4">Basis pakket</span>
            <h2 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 mb-4 tracking-tight leading-[1.05]">{{ $first->title }}</h2>
            <p class="text-xl text-slate-600 max-w-2xl mx-auto">{{ $first->short_description }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="animate-on-scroll">
                <div class="prose prose-lg text-slate-600 mb-8">{!! $first->description !!}</div>

                <div class="flex items-baseline gap-4 mb-8">
                    <span class="text-5xl font-bold text-slate-900">{{ $first->formatted_price }}</span>
                    <span class="text-slate-500">eenmalig</span>
                </div>

                <a href="{{ route('services.show', $first) }}" class="btn btn-primary text-lg px-8 py-4 shadow-lg hover:shadow-xl transition-all">
                    Bekijk details
                </a>
            </div>

            <div class="relative h-[460px] hidden lg:block animate-on-scroll">
                <!-- Soft brand glow behind the cluster -->
                <div class="absolute inset-8 rounded-[2rem] bg-gradient-to-br from-primary-100/60 to-accent-100/50 blur-2xl" aria-hidden="true"></div>

                <!-- Capability cards: real content, real depth, gently floating -->
                <div class="absolute top-4 left-8 w-64 rounded-2xl bg-white/90 backdrop-blur ring-1 ring-slate-900/5 shadow-xl shadow-slate-900/10 p-5 animate-float">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-primary-50 text-primary-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h12A2.25 2.25 0 0020.25 14.25V3M3.75 3h16.5M3.75 3H2.25m18 0h1.5m-15 18h9M8.25 21v-4.5M15.75 21v-4.5"/></svg>
                        </span>
                        <div>
                            <p class="font-heading font-bold text-slate-900 leading-tight">Homepage ontwerp</p>
                            <p class="text-sm text-slate-500">Op maat gemaakt</p>
                        </div>
                    </div>
                </div>

                <div class="absolute top-28 right-2 w-60 rounded-2xl bg-white/90 backdrop-blur ring-1 ring-slate-900/5 shadow-xl shadow-slate-900/10 p-5 animate-float" style="animation-delay: 1s">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-accent-50 text-accent-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>
                        </span>
                        <div>
                            <p class="font-heading font-bold text-slate-900 leading-tight">Mobiel vriendelijk</p>
                            <p class="text-sm text-slate-500">Vlekkeloos op elk scherm</p>
                        </div>
                    </div>
                </div>

                <div class="absolute bottom-24 left-0 w-60 rounded-2xl bg-white/90 backdrop-blur ring-1 ring-slate-900/5 shadow-xl shadow-slate-900/10 p-5 animate-float" style="animation-delay: 2s">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                        <div>
                            <p class="font-heading font-bold text-slate-900 leading-tight">Contactformulier</p>
                            <p class="text-sm text-slate-500">Altijd bereikbaar</p>
                        </div>
                    </div>
                </div>

                <div class="absolute bottom-0 right-12 w-60 rounded-2xl bg-white/90 backdrop-blur ring-1 ring-slate-900/5 shadow-xl shadow-slate-900/10 p-5 animate-float" style="animation-delay: 3s">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        </span>
                        <div>
                            <p class="font-heading font-bold text-slate-900 leading-tight">Eigen input</p>
                            <p class="text-sm text-slate-500">Uw wensen centraal</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

@if($second || $third)
<!-- Comparison Section -->
<section id="pakketten" class="py-16 lg:py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-12 animate-on-scroll">
            <span class="text-accent-600 font-semibold tracking-wide uppercase text-sm mb-3 block">Doorontwikkeling</span>
            <h2 class="font-heading text-3xl md:text-4xl font-bold text-slate-900 mb-4 tracking-tight leading-[1.05]">Doorontwikkel uw pakket</h2>
            <p class="text-xl text-slate-600 max-w-2xl mx-auto">Elke volgende optie bevat alles van de vorige, plus extra mogelijkheden.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @if($second)
            <div class="group bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden hover:shadow-xl transition-all duration-300 animate-on-scroll">
                <div class="h-2 bg-gradient-to-r from-secondary-500 to-accent-500"></div>
                <div class="p-8 lg:p-10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-heading text-2xl font-bold text-slate-900">{{ $second->title }}</h3>
                        <span class="px-3 py-1 rounded-full bg-secondary-100 text-secondary-700 text-sm font-semibold">Stap 2</span>
                    </div>
                    <p class="text-slate-600 mb-6">{{ $second->short_description }}</p>

                    @if($second->features && count($second->features) > 0)
                        <ul class="space-y-3 mb-8">
                            @foreach(array_slice($second->features, 0, 6) as $feature)
                                <li class="flex items-start text-sm text-slate-700">
                                    <svg class="w-5 h-5 text-accent-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="text-3xl font-bold text-slate-900 mb-6">{{ $second->formatted_price }}</div>
                    <a href="{{ route('services.show', $second) }}" class="btn btn-primary w-full">
                        Bekijk {{ $second->title }}
                    </a>
                </div>
            </div>
            @endif

            @if($third)
            <div class="group bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden hover:shadow-xl transition-all duration-300 animate-on-scroll">
                <div class="h-2 bg-gradient-to-r from-amber-500 to-rose-500"></div>
                <div class="p-8 lg:p-10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-heading text-2xl font-bold text-slate-900">{{ $third->title }}</h3>
                        <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-sm font-semibold">Stap 3</span>
                    </div>
                    <p class="text-slate-600 mb-6">{{ $third->short_description }}</p>

                    @if($third->features && count($third->features) > 0)
                        <ul class="space-y-3 mb-8">
                            @foreach(array_slice($third->features, 0, 6) as $feature)
                                <li class="flex items-start text-sm text-slate-700">
                                    <svg class="w-5 h-5 text-amber-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="text-3xl font-bold text-slate-900 mb-6">{{ $third->formatted_price }}</div>
                    <a href="{{ route('services.show', $third) }}" class="btn btn-primary w-full">
                        Bekijk {{ $third->title }}
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

<!-- Process Section -->
<section class="py-16 lg:py-24 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-12 animate-on-scroll">
            <span class="text-primary-600 font-semibold tracking-wide uppercase text-sm mb-3 block">Werkwijze</span>
            <h2 class="font-heading text-3xl md:text-4xl font-bold text-slate-900 mb-4 tracking-tight leading-[1.05]">Ons Proces</h2>
            <p class="text-xl text-slate-600 max-w-2xl mx-auto">Van eerste contact tot lancering, wij begeleiden u bij elke stap.</p>
        </div>

        <div class="relative">
            <!-- Connecting line between step circles on large screens -->
            <div class="hidden lg:block absolute top-6 left-[12.5%] right-[12.5%] h-0.5 bg-slate-200" aria-hidden="true"></div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($steps as $index => $step)
                    @php
                        $colorClasses = [
                            'primary' => 'bg-primary-500',
                            'accent' => 'bg-accent-500',
                            'secondary' => 'bg-secondary-500',
                            'emerald' => 'bg-emerald-500',
                        ][$step['color']];
                    @endphp
                    <div class="relative flex flex-col items-center text-center animate-on-scroll">
                        <div class="relative z-10 w-12 h-12 {{ $colorClasses }} text-white rounded-full flex items-center justify-center mb-4 font-bold text-lg shadow-md">
                            {{ $index + 1 }}
                        </div>
                        <h3 class="font-heading text-xl font-bold text-slate-900 mb-2">{{ $step['title'] }}</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">{{ $step['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-br from-primary-700 via-accent-600 to-primary-900 text-white relative overflow-hidden" data-navbar-theme="dark">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-white/10 via-transparent to-transparent" aria-hidden="true"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
        <h2 class="font-heading text-3xl md:text-4xl font-bold mb-4 tracking-tight leading-[1.05]">
            Welke dienst past bij uw bedrijf?
        </h2>
        <p class="text-xl text-white/90 mb-8">
            Plan een gratis adviesgesprek en ontdek de beste oplossing voor uw bedrijf.
        </p>
        <a href="{{ route('contact') }}" class="btn btn-light text-lg px-8 py-4 hover:scale-105 transition-transform">
            Plan een gratis adviesgesprek
        </a>
    </div>
</section>
@endsection
