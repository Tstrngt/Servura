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
<section class="relative overflow-hidden bg-slate-950 text-white">
    <img
        src="https://images.unsplash.com/photo-1526505917130-857817501277?fm=jpg&q=80&w=1920&auto=format&fit=crop"
        alt="Skyline van Rotterdam in Zuid-Holland"
        class="absolute inset-0 w-full h-full object-cover opacity-15"
    >
    <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-950/90 to-slate-950/60"></div>

    <div class="relative max-w-7xl mx-auto px-6 py-24 lg:py-32">
        <div class="max-w-2xl animate-slide-up">
            <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3.5 py-1.5 text-sm font-medium text-slate-200">
                <span class="relative flex h-2 w-2">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-accent-400 opacity-75"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-accent-400"></span>
                </span>
                Over Servura
            </span>
            <h1 class="mt-6 font-heading text-4xl md:text-5xl font-bold leading-[1.05] tracking-tight">
                Uw partner voor een sterke online aanwezigheid.
            </h1>
            <p class="mt-6 max-w-xl text-lg leading-relaxed text-white/90">
                Wij helpen ondernemers en organisaties met professionele websites, betrouwbare hosting en persoonlijke ondersteuning — van het eerste gesprek tot het doorlopende onderhoud.
            </p>
        </div>
    </div>
</section>

<!-- Wie wij zijn -->
<section class="relative overflow-hidden bg-white py-24 lg:py-32">
    <div class="max-w-7xl mx-auto px-6 relative">
        <!-- Floating UI cards -->
        <div class="hidden lg:block">
            <!-- Browser window card -->
            <div class="absolute top-0 left-0 z-10 w-64 rotate-[-6deg] animate-float">
                <div class="rounded-xl bg-white ring-1 ring-slate-200 shadow-xl overflow-hidden">
                    <div class="h-8 bg-slate-100 flex items-center gap-1.5 px-3">
                        <span class="w-2.5 h-2.5 rounded-full bg-slate-300"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-slate-300"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-slate-300"></span>
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="h-24 rounded-lg bg-gradient-to-br from-primary-100 to-primary-50"></div>
                        <div class="h-3 w-3/4 rounded bg-slate-100"></div>
                        <div class="h-3 w-1/2 rounded bg-slate-100"></div>
                        <div class="grid grid-cols-3 gap-2 pt-1">
                            <div class="h-14 rounded bg-slate-50"></div>
                            <div class="h-14 rounded bg-slate-50"></div>
                            <div class="h-14 rounded bg-slate-50"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile phone card -->
            <div class="absolute top-8 right-0 z-10 w-40 rotate-[5deg] animate-float" style="animation-delay: 1s">
                <div class="rounded-[2rem] bg-slate-900 p-2 shadow-xl">
                    <div class="h-72 rounded-[1.5rem] bg-white p-3 space-y-3">
                        <div class="h-32 rounded-xl bg-gradient-to-br from-accent-100 to-accent-50"></div>
                        <div class="h-3 w-3/4 rounded bg-slate-100"></div>
                        <div class="h-3 w-1/2 rounded bg-slate-100"></div>
                        <div class="h-10 rounded-lg bg-primary-100"></div>
                        <div class="h-24 rounded-lg bg-slate-50"></div>
                    </div>
                </div>
            </div>

            <!-- Stats card -->
            <div class="absolute bottom-0 left-12 z-10 w-48 rotate-[3deg] animate-float" style="animation-delay: 2s">
                <div class="rounded-xl bg-white ring-1 ring-slate-200 shadow-xl p-5">
                    <div class="text-sm text-slate-500 mb-1">Online groei</div>
                    <div class="text-3xl font-bold text-slate-900">+42%</div>
                    <div class="h-16 mt-3 rounded-lg bg-gradient-to-t from-primary-100 to-transparent"></div>
                </div>
            </div>

            <!-- Checklist card -->
            <div class="absolute bottom-12 right-12 z-10 w-52 rotate-[-4deg] animate-float" style="animation-delay: 3s">
                <div class="rounded-xl bg-white ring-1 ring-slate-200 shadow-xl p-5 space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        </span>
                        <span class="h-2.5 w-24 rounded bg-slate-100"></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        </span>
                        <span class="h-2.5 w-28 rounded bg-slate-100"></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        </span>
                        <span class="h-2.5 w-20 rounded bg-slate-100"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Central content -->
        <div class="relative z-20 max-w-3xl mx-auto text-center pt-8 lg:py-20 animate-on-scroll">
            <span class="text-accent-600 font-semibold tracking-wide uppercase text-sm mb-4 block">Wie wij zijn</span>
            <h2 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 mb-6 leading-[1.05] tracking-tight">
                Websites die werken, zonder de technische last
            </h2>
            <p class="text-lg text-slate-600 leading-relaxed mb-8 max-w-2xl mx-auto">
                Servura is opgericht met een duidelijke missie: het MKB helpen met een professionele online aanwezigheid zonder de complexiteit en hoge kosten die vaak bij webdevelopment komen kijken. U hoeft er niets technisch van te weten; wij nemen alles uit handen.
            </p>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-center gap-4">
                <a href="{{ route('contact') }}" class="btn btn-primary text-base px-7 py-3.5">
                    Plan een vrijblijvend gesprek
                </a>
                <a href="{{ route('services.index') }}" class="btn btn-outline text-base px-7 py-3.5">
                    Bekijk onze diensten
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Ons team -->
<section class="py-24 lg:py-28 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="mb-14 animate-on-scroll">
            <span class="text-accent-600 font-semibold tracking-wide uppercase text-sm mb-4 block">Ons team</span>
            <h2 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 mb-4 leading-[1.05] tracking-tight">De mensen achter Servura</h2>
            <p class="max-w-2xl text-lg text-slate-600 leading-relaxed">
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
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-start">
            <div class="animate-on-scroll">
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
        <div class="mb-14 animate-on-scroll">
            <span class="text-accent-600 font-semibold tracking-wide uppercase text-sm mb-4 block">Portfolio</span>
            <h2 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 mb-4 leading-[1.05] tracking-tight">Het werk van ons team</h2>
            <p class="max-w-2xl text-lg text-slate-600 leading-relaxed">
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

<script>
(function () {
    const hero = document.querySelector('section');
    if (!hero) return;
    const img = hero.querySelector('img');
    if (!img) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    function update() {
        const rect = hero.getBoundingClientRect();
        const p = Math.max(0, Math.min(rect.top / window.innerHeight, 1));
        img.style.transform = `translate3d(0, ${p * 32}px, 0) scale(1.05)`;
    }

    window.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update);
    update();
})();
</script>
@endsection
