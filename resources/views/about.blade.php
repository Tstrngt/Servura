@extends('layouts.app')

@section('title', 'Over Ons - Servura')
@section('meta-description', 'Lees meer over Servura en het team. Bekijk het persoonlijke portfolio van onze developers en designers.')
@section('meta-keywords', 'over ons, team, portfolio, webdesign, developers')

@section('content')
@php
    $team = [
        [
            'name' => 'Tim van Gorkom',
            'role' => 'Founder & Lead Developer',
            'bio' => 'Richting, architectuur en de technische visie van Servura.',
            'portfolio_summary' => 'Specialist in Laravel-architectuur, API-koppelingen en schaalbare backends voor het MKB.',
            'initial' => 'T',
            'color' => 'from-primary-500 to-primary-700',
            'portfolio_url' => '#tim-portfolio',
        ],
        [
            'name' => 'Dirk van Gelderen',
            'role' => 'Front-end & Back-end Developer',
            'bio' => 'Bouwt solide applicaties en zorgt voor snelle, nette interfaces.',
            'portfolio_summary' => 'Expert in Tailwind CSS, Blade-componenten en het bouwen van interactieve webervaringen.',
            'initial' => 'D',
            'color' => 'from-secondary-600 to-secondary-800',
            'image' => 'images/dirk-van-gelderen.jpg',
            'portfolio_url' => '#dirk-portfolio',
        ],
        [
            'name' => 'Isis van Dijk',
            'role' => 'UX/UI Specialist & Design Expert',
            'bio' => 'Ontwerpt intuïtieve ervaringen die bezoekers converteren.',
            'portfolio_summary' => 'Richt zich op gebruikersonderzoek, design systems en visuele hierarchie die resultaat oplevert.',
            'initial' => 'I',
            'color' => 'from-accent-500 to-accent-700',
            'portfolio_url' => '#isis-portfolio',
        ],
    ];
@endphp

<!-- Hero Section -->
<section class="relative overflow-hidden bg-secondary-900 text-white">
    <img
        src="https://images.unsplash.com/photo-1526505917130-857817501277?fm=jpg&q=80&w=1920&auto=format&fit=crop"
        alt="Skyline van Rotterdam in Zuid-Holland"
        class="absolute inset-0 w-full h-full object-cover opacity-20"
    >
    <div class="absolute inset-0 bg-gradient-to-r from-secondary-900/95 via-secondary-900/85 to-secondary-900/40"></div>

    <div class="relative max-w-7xl mx-auto px-6 py-24 lg:py-32">
        <div class="max-w-2xl animate-slide-up">
            <span class="text-accent-300 font-semibold tracking-wide uppercase text-sm mb-4 block">Over Servura</span>
            <h1 class="font-display text-5xl sm:text-6xl lg:text-7xl font-bold mb-6 leading-[1.05]">
                Uw partner voor een sterke online aanwezigheid
            </h1>
            <p class="text-lg md:text-xl text-white/90 mb-8 leading-relaxed">
                Wij helpen ondernemers en organisaties met professionele websites, betrouwbare hosting en persoonlijke ondersteuning — van het eerste gesprek tot het doorlopende onderhoud.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('contact') }}" class="btn bg-white text-secondary-900 hover:bg-gray-100 font-bold shadow-lg">
                    Gratis adviesgesprek
                </a>
                <a href="{{ route('services.index') }}" class="btn border-2 border-white text-white hover:bg-white hover:text-secondary-900 font-semibold">
                    Bekijk diensten
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Wie wij zijn -->
<section class="py-24 lg:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">
            <div class="lg:col-span-5 animate-on-scroll">
                <span class="text-accent-600 font-semibold tracking-wide uppercase text-sm mb-4 block">Wie wij zijn</span>
                <h2 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 mb-6 leading-[1.05] tracking-tight">
                    Websites die werken, zonder de technische last
                </h2>
                <p class="text-lg text-slate-600 leading-relaxed mb-6">
                    Servura is opgericht met een duidelijke missie: het MKB helpen met een professionele online aanwezigheid zonder de complexiteit en hoge kosten die vaak bij webdevelopment komen kijken.
                </p>
                <p class="text-lg text-slate-600 leading-relaxed">
                    U hoeft er niets technisch van te weten; wij nemen alles uit handen. Van ontwerp en ontwikkeling tot hosting, beveiliging en doorlopend onderhoud.
                </p>
            </div>

            <div class="lg:col-span-7 animate-on-scroll">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-10">
                    @php
                        $values = [
                            ['title' => 'Eerlijkheid', 'text' => 'Transparante prijzen en heldere communicatie, zonder verborgen kosten.', 'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                            ['title' => 'Kwaliteit', 'text' => 'Professionele oplossingen die stabiel, veilig en gebruiksvriendelijk zijn.', 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
                            ['title' => 'Service', 'text' => 'Persoonlijke support met korte lijnen en snelle reactietijd.', 'icon' => 'M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3'],
                            ['title' => 'Resultaat', 'text' => 'Focus op uw bedrijfsdoelen en meetbare online groei.', 'icon' => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z'],
                        ];
                    @endphp

                    @foreach($values as $value)
                    <div class="group">
                        <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-primary-50 text-primary-600 ring-1 ring-primary-100 transition-colors duration-300 group-hover:bg-primary-600 group-hover:text-white">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="{{ $value['icon'] }}" />
                            </svg>
                        </span>
                        <h3 class="mt-5 font-heading text-xl font-bold text-slate-900">{{ $value['title'] }}</h3>
                        <p class="mt-2 text-slate-600 leading-relaxed">{{ $value['text'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Ons team -->
<section class="py-24 lg:py-28 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="max-w-2xl mb-14 animate-on-scroll">
            <span class="text-accent-600 font-semibold tracking-wide uppercase text-sm mb-4 block">Ons team</span>
            <h2 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 mb-4 leading-[1.05] tracking-tight">De mensen achter Servura</h2>
            <p class="text-lg text-slate-600 leading-relaxed">
                Een klein team van professionals met passie voor webdevelopment, design en klantenservice.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($team as $member)
            <div class="group text-center animate-on-scroll">
                <div class="relative w-32 h-32 mx-auto mb-6">
                    <div class="absolute inset-0 rounded-full bg-gradient-to-br {{ $member['color'] }} opacity-20 blur-xl group-hover:opacity-40 transition-opacity duration-300"></div>
                    <div class="relative w-full h-32 rounded-full bg-gradient-to-br {{ $member['color'] }} text-white text-4xl font-bold flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-300 overflow-hidden">
                        @if(isset($member['image']) && file_exists(public_path($member['image'])))
                            <img src="{{ asset($member['image']) }}" alt="{{ $member['name'] }}" class="w-full h-full object-cover" style="object-position: 50% 0%; transform: scale(1.25); transform-origin: top center;">
                        @else
                            {{ $member['initial'] }}
                        @endif
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-slate-900 mb-1">{{ $member['name'] }}</h3>
                <p class="text-primary-600 font-medium text-sm mb-3">{{ $member['role'] }}</p>
                <p class="text-slate-500 text-sm max-w-xs mx-auto leading-relaxed">{{ $member['bio'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Werkwijze -->
<section class="py-24 lg:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-start animate-on-scroll">
            <div>
                <span class="text-accent-600 font-semibold tracking-wide uppercase text-sm mb-4 block">Werkwijze</span>
                <h2 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 mb-6 leading-[1.05] tracking-tight">
                    Persoonlijk, van begin tot eind
                </h2>
                <p class="text-lg text-slate-600 leading-relaxed">
                    Wij geloven dat een goede website meer is dan alleen een mooi ontwerp. Daarom begeleiden wij u tijdens het volledige traject: van het eerste gesprek en het ontwerp tot de ontwikkeling, hosting, beveiliging en het doorlopende onderhoud.
                </p>
            </div>

            <div class="space-y-6">
                <div class="flex gap-4">
                    <span class="flex-shrink-0 inline-flex h-10 w-10 items-center justify-center rounded-full bg-primary-50 text-primary-600 ring-1 ring-primary-100">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                    </span>
                    <div>
                        <h4 class="font-heading font-semibold text-slate-900">Één vast aanspreekpunt</h4>
                        <p class="mt-1 text-slate-600 leading-relaxed">Geen callcenters of wachtrijen. U spreekt altijd met iemand die uw project kent.</p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <span class="flex-shrink-0 inline-flex h-10 w-10 items-center justify-center rounded-full bg-primary-50 text-primary-600 ring-1 ring-primary-100">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M20.25 8.511c0 1.281-.809 2.443-2.027 2.845-1.011.327-2.036.35-3.065.07a.75.75 0 00-.92.92c.28 1.029.257 2.054-.07 3.065C13.954 16.191 12.792 17 11.511 17H3.75a.75.75 0 01-.75-.75V6.75a.75.75 0 01.75-.75h7.761c1.281 0 2.443.809 2.845 2.027.327 1.011.35 2.036.07 3.065a.75.75 0 00.92.92c1.029-.28 2.054-.257 3.065.07C19.441 6.308 20.25 7.47 20.25 8.511z" /></svg>
                    </span>
                    <div>
                        <h4 class="font-heading font-semibold text-slate-900">Helder en transparant</h4>
                        <p class="mt-1 text-slate-600 leading-relaxed">Wij vertalen techniek naar gewone taal en houden u bij elke stap op de hoogte.</p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <span class="flex-shrink-0 inline-flex h-10 w-10 items-center justify-center rounded-full bg-primary-50 text-primary-600 ring-1 ring-primary-100">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" /></svg>
                    </span>
                    <div>
                        <h4 class="font-heading font-semibold text-slate-900">Veilig en betrouwbaar</h4>
                        <p class="mt-1 text-slate-600 leading-relaxed">SSL, dagelijkse back-ups en bewaking draaien op de achtergrond — u merkt er niets van.</p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <span class="flex-shrink-0 inline-flex h-10 w-10 items-center justify-center rounded-full bg-primary-50 text-primary-600 ring-1 ring-primary-100">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M15.59 14.37a6 6 0 01-5.84 7.38h-4.6m5.84-7.38a6 6 0 00-5.84-7.38h-4.6m5.84 7.38v2.61m5.84-2.61a6 6 0 015.84 7.38h-4.6m5.84-7.38a6 6 0 00-5.84-7.38h-4.6m5.84 7.38v2.61M12 12h.008v.008H12V12z" /></svg>
                    </span>
                    <div>
                        <h4 class="font-heading font-semibold text-slate-900">Op maat, geen templates</h4>
                        <p class="mt-1 text-slate-600 leading-relaxed">Elke website sluit aan bij uw bedrijf, doelen en doelgroep.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Portfolio (persoonlijk) -->
<section class="py-24 lg:py-28 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="max-w-2xl mb-14 animate-on-scroll">
            <span class="text-accent-600 font-semibold tracking-wide uppercase text-sm mb-4 block">Portfolio</span>
            <h2 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 mb-4 leading-[1.05] tracking-tight">Het werk van ons team</h2>
            <p class="text-lg text-slate-600 leading-relaxed">
                Ontdek het persoonlijke portfolio van elk teamlid. Hier delen we onze individuele expertise, projecten en specialisaties.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($team as $member)
                <div class="card group animate-on-scroll flex flex-col overflow-hidden">
                    <div class="relative w-full h-56 bg-gradient-to-br {{ $member['color'] }} flex items-center justify-center overflow-hidden">
                        <span class="text-7xl font-black text-white/25 select-none group-hover:scale-110 transition-transform duration-300">{{ $member['initial'] }}</span>
                    </div>
                    <div class="card-body flex-1 flex flex-col">
                        <h3 class="text-xl font-semibold text-slate-900 mb-1">{{ $member['name'] }}</h3>
                        <p class="text-sm text-primary-600 font-medium mb-3">{{ $member['role'] }}</p>
                        <p class="text-slate-600 text-sm leading-relaxed mb-5 flex-1">{{ $member['portfolio_summary'] }}</p>
                        <a href="{{ $member['portfolio_url'] }}" class="inline-flex items-center text-sm font-semibold text-primary-600 hover:text-primary-700">
                            Bekijk portfolio
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section (gelijk aan homepage) -->
<section class="relative overflow-hidden bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900">
    <div class="absolute -top-24 -right-16 h-96 w-96 rounded-full bg-accent-400/20 blur-3xl"></div>
    <div class="absolute -bottom-32 -left-16 h-96 w-96 rounded-full bg-primary-400/20 blur-3xl"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-6 py-24 lg:py-28 text-center">
        <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3.5 py-1.5 text-sm font-medium text-primary-50 backdrop-blur animate-on-scroll">
            <span class="relative flex h-2 w-2">
                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-accent-300 opacity-75"></span>
                <span class="relative inline-flex h-2 w-2 rounded-full bg-accent-300"></span>
            </span>
            Nu beschikbaar voor nieuwe projecten
        </span>
        <h2 class="mt-7 font-heading text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-[1.05] tracking-tight animate-on-scroll">
            Klaar om uw website tot leven te zien komen?
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
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
                Of bel ons direct
            </a>
        </div>
    </div>
</section>
@endsection
