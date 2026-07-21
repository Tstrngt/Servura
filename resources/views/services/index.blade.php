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
<section class="relative py-24 lg:py-32 overflow-hidden bg-gradient-to-br from-primary-900 via-primary-800 to-secondary-900 text-white">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-accent-900/20 via-transparent to-transparent"></div>

    <div class="relative z-10 max-w-5xl mx-auto px-6 text-center">
        <h1 class="font-display text-4xl md:text-6xl lg:text-7xl font-bold mb-6 leading-tight animate-slide-up">
            Diensten die <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-200 via-accent-200 to-white">uw groei</span> versnellen
        </h1>
        <p class="text-xl md:text-2xl text-primary-100 max-w-3xl mx-auto mb-10 animate-slide-up" style="animation-delay: 0.1s">
            Drie heldere pakketten, van start-up tot uitgebreide webshop. Kies wat bij uw ambities past.
        </p>
        <div class="animate-slide-up" style="animation-delay: 0.2s">
            <a href="#pakketten" class="btn bg-white text-primary-700 hover:bg-primary-50 font-bold text-lg px-8 py-4 shadow-xl transition-transform hover:scale-105">
                Bekijk de pakketten
            </a>
        </div>
    </div>
</section>

@if($first)
<!-- Featured Service -->
<section id="basis" class="relative py-20 lg:py-28 bg-gradient-to-b from-slate-50 via-white to-slate-50 overflow-hidden">
    <div class="absolute top-0 right-0 w-[35rem] h-[35rem] bg-primary-200/30 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
    <div class="absolute bottom-0 left-0 w-[30rem] h-[30rem] bg-accent-200/30 rounded-full blur-3xl translate-y-1/3 -translate-x-1/3"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6">
        <div class="text-center mb-16 animate-on-scroll">
            <span class="inline-block px-4 py-1.5 rounded-full bg-accent-100 text-accent-700 text-sm font-semibold tracking-wide uppercase mb-4">Basis pakket</span>
            <h2 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 mb-4">{{ $first->title }}</h2>
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
                <div class="absolute top-0 left-8 w-56 h-36 rounded-2xl bg-gradient-to-br from-primary-500 to-primary-700 text-white flex items-center justify-center text-center font-semibold shadow-2xl animate-float">
                    Homepage ontwerp
                </div>
                <div class="absolute top-24 right-0 w-56 h-36 rounded-2xl bg-gradient-to-br from-accent-500 to-accent-700 text-white flex items-center justify-center text-center font-semibold shadow-2xl animate-float" style="animation-delay: 1s">
                    Mobiel vriendelijk
                </div>
                <div class="absolute bottom-24 left-0 w-56 h-36 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-white flex items-center justify-center text-center font-semibold shadow-2xl animate-float" style="animation-delay: 2s">
                    Contactformulier
                </div>
                <div class="absolute bottom-0 right-12 w-56 h-36 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-700 text-white flex items-center justify-center text-center font-semibold shadow-2xl animate-float" style="animation-delay: 3s">
                    Eigen input
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
            <h2 class="font-heading text-3xl md:text-4xl font-bold text-slate-900 mb-4">Doorontwikkel uw pakket</h2>
            <p class="text-xl text-slate-600 max-w-2xl mx-auto">Elke volgende optie bevat alles van de vorige, plus extra mogelijkheden.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @if($second)
            <div class="group bg-white rounded-3xl shadow-lg border border-slate-100 overflow-hidden hover:shadow-xl transition-all duration-300 animate-on-scroll">
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
            <div class="group bg-white rounded-3xl shadow-lg border border-slate-100 overflow-hidden hover:shadow-xl transition-all duration-300 animate-on-scroll">
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
            <h2 class="font-heading text-3xl md:text-4xl font-bold text-slate-900 mb-4">Ons Proces</h2>
            <p class="text-xl text-slate-600 max-w-2xl mx-auto">Van eerste contact tot lancering, wij begeleiden u bij elke stap.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-14 gap-y-8 mt-20 lg:mt-24">
            @foreach($steps as $index => $step)
                @php
                    $colorClasses = [
                        'primary' => 'bg-primary-500',
                        'accent' => 'bg-accent-500',
                        'secondary' => 'bg-secondary-500',
                        'emerald' => 'bg-emerald-500',
                    ][$step['color']];
                    $arrowClasses = [
                        'primary' => 'text-primary-400',
                        'accent' => 'text-accent-400',
                        'secondary' => 'text-secondary-400',
                        'emerald' => 'text-emerald-400',
                    ][$step['color']];
                @endphp
                <div class="relative bg-slate-50 rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 {{ $index % 2 === 0 ? 'lg:-translate-y-10' : 'lg:translate-y-10' }}">
                    <div class="w-12 h-12 {{ $colorClasses }} text-white rounded-full flex items-center justify-center mb-4 font-bold text-lg shadow-md">
                        {{ $index + 1 }}
                    </div>
                    <h3 class="font-heading text-xl font-bold text-slate-900 mb-2">{{ $step['title'] }}</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">{{ $step['text'] }}</p>

                    @if($index < count($steps) - 1)
                        <div class="hidden lg:flex absolute -right-10 w-8 items-center justify-center {{ $arrowClasses }} z-10" style="top: 1.25rem; bottom: auto; transform: rotate({{ $index % 2 === 0 ? '45' : '-45' }}deg);">
                            <span class="process-arrow inline-flex">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 12h14m-5-5 5 5-5 5"></path>
                                </svg>
                            </span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-br from-primary-700 via-accent-600 to-primary-900 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-white/10 via-transparent to-transparent"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
        <h2 class="font-display text-3xl md:text-4xl font-bold mb-4">
            Welke dienst past bij uw bedrijf?
        </h2>
        <p class="text-xl text-white/90 mb-8">
            Plan een gratis adviesgesprek en ontdek de beste oplossing voor uw bedrijf.
        </p>
        <a href="{{ route('contact') }}" class="btn bg-white text-primary-700 hover:bg-slate-100 text-lg px-8 py-4 shadow-xl font-bold transition-transform hover:scale-105">
            Plan een gratis adviesgesprek
        </a>
    </div>
</section>
@endsection
