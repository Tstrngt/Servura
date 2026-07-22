@extends('layouts.app')

@section('title', 'Servura - Professionele Websites en Hosting voor het MKB')
@section('meta-description', 'Servura helpt mkb-bedrijven met professionele websites en betrouwbare hosting. Volledig ontzorgd, van ontwerp tot onderhoud.')
@section('meta-keywords', 'webdesign, hosting, mkb, website ontwikkeling, domeinnaam, email, onderhoud')

@section('content')
@php
$baseService = \App\Models\Service::active()->homepage()->ordered()->first();
@endphp

@if(config('features.hero_preview_v2'))
    @include('partials.hero-home-preview')
@else
    @include('partials.hero-home')
@endif

<!-- Waarom Servura -->
<section class="relative bg-gradient-to-b from-slate-50 via-slate-50 to-white overflow-hidden">
    <div class="absolute top-0 right-0 w-[30rem] h-[30rem] bg-primary-200/30 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
    <!-- Accent glow fades toward the seam so it flows into "Wat wij doen" below -->
    <div class="absolute bottom-0 left-0 w-[34rem] h-[34rem] bg-accent-200/25 rounded-full blur-3xl -translate-x-1/3"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 py-24 lg:py-28">
        <!-- Asymmetric intro: statement left, context right -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-end mb-16 lg:mb-20">
            <div class="lg:col-span-7 animate-on-scroll">
                <span class="text-accent-600 font-semibold tracking-wide uppercase text-sm mb-4 block">Waarom Servura</span>
                <h2 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 leading-[1.05] tracking-tight">
                    Persoonlijk, helder<br class="hidden sm:block"> en betrouwbaar
                </h2>
            </div>
            <div class="lg:col-span-5 animate-on-scroll">
                <p class="text-lg text-slate-600 leading-relaxed max-w-md">
                    Wij luisteren eerst naar wat u wilt bereiken en gaan daarna pas bouwen.
                    Geen jargon, geen wachtrij — één team dat uw website van begin tot eind verzorgt.
                </p>
            </div>
        </div>

        <!-- Three promises as an editorial index, separated by hairlines -->
        <div class="grid grid-cols-1 md:grid-cols-3 border-t border-slate-200">
            <div class="group py-10 md:py-12 md:pr-10 md:border-r border-slate-200 animate-on-scroll">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-primary-50 text-primary-600 ring-1 ring-primary-100 transition-colors duration-300 group-hover:bg-primary-600 group-hover:text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                </span>
                <h3 class="mt-6 font-heading text-2xl font-bold text-slate-900">Persoonlijk</h3>
                <p class="mt-3 text-slate-600 leading-relaxed">
                    U overlegt altijd met dezelfde mensen die uw site bouwen. Eén vast aanspreekpunt — geen helpdesk, geen wachtrij.
                </p>
            </div>

            <div class="group py-10 md:py-12 md:px-10 border-t md:border-t-0 md:border-r border-slate-200 animate-on-scroll">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-accent-50 text-accent-600 ring-1 ring-accent-100 transition-colors duration-300 group-hover:bg-accent-600 group-hover:text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                </span>
                <h3 class="mt-6 font-heading text-2xl font-bold text-slate-900">Helder</h3>
                <p class="mt-3 text-slate-600 leading-relaxed">
                    U hoeft er niets technisch van te weten. Wij vertalen de techniek naar gewone taal en houden u bij elke stap op de hoogte.
                </p>
            </div>

            <div class="group py-10 md:py-12 md:pl-10 border-t md:border-t-0 border-slate-200 animate-on-scroll">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100 transition-colors duration-300 group-hover:bg-emerald-600 group-hover:text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                </span>
                <h3 class="mt-6 font-heading text-2xl font-bold text-slate-900">Betrouwbaar</h3>
                <p class="mt-3 text-slate-600 leading-relaxed">
                    Altijd online, altijd veilig. SSL, dagelijkse back-ups en bewaking draaien op de achtergrond — u merkt er niets van.
                </p>
            </div>
        </div>

        <!-- Proof strip: the guarantees, made visible -->
        <div class="mt-14 rounded-2xl bg-white ring-1 ring-slate-200/80 shadow-sm animate-on-scroll">
            <dl class="grid grid-cols-2 lg:grid-cols-4 divide-y divide-slate-100 lg:divide-y-0 lg:divide-x">
                <div class="flex items-center gap-3 px-6 py-5">
                    <svg class="h-5 w-5 flex-shrink-0 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                    <div>
                        <dt class="text-sm font-semibold text-slate-900">SSL-beveiliging</dt>
                        <dd class="text-sm text-slate-500">Standaard inbegrepen</dd>
                    </div>
                </div>
                <div class="flex items-center gap-3 px-6 py-5">
                    <svg class="h-5 w-5 flex-shrink-0 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75"/></svg>
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

<!-- Services Preview -->
<section id="diensten" class="relative bg-white overflow-hidden">
    <!-- Continues the accent glow from "Waarom Servura": centre sits below the seam
         so only its soft top edge meets the boundary, matching the fading Waarom glow above -->
    <div class="absolute top-0 left-0 w-[34rem] h-[34rem] bg-accent-200/25 rounded-full blur-3xl -translate-x-1/3"></div>
    <div class="absolute bottom-0 right-0 w-[35rem] h-[35rem] bg-accent-200/30 rounded-full blur-3xl translate-y-1/3 translate-x-1/3"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 py-24 lg:py-28">
        <div class="text-center mb-16 animate-on-scroll">
            <span class="text-primary-600 font-semibold tracking-wide uppercase text-sm mb-3 block">Wat wij doen</span>
            <h2 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 mb-4">
                Een website, compleet ontzorgd
            </h2>
            <p class="text-xl text-slate-600 max-w-2xl mx-auto">
                Van ontwerp tot hosting, wij regelen het voor u.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="animate-on-scroll">
                <h3 class="font-heading text-3xl lg:text-4xl font-bold text-slate-900 mb-4">
                    {{ $baseService->title ?? 'Professionele websites voor het MKB' }}
                </h3>
                <p class="text-slate-600 text-lg mb-8 leading-relaxed">
                    {{ $baseService->short_description ?? 'Wij bouwen snelle, responsive websites die uw bedrijf online presenteren en meer klanten aantrekken.' }}
                </p>
                <ul class="space-y-4 mb-8">
                    <li class="flex items-center text-slate-700">
                        <span class="w-8 h-8 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </span>
                        Compleet design voor alle toestellen
                    </li>
                    <li class="flex items-center text-slate-700">
                        <span class="w-8 h-8 rounded-full bg-accent-100 text-accent-600 flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </span>
                        Hosting
                    </li>
                    <li class="flex items-center text-slate-700">
                        <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </span>
                        Aanpasbaarheid en vertrouwen
                    </li>
                </ul>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('services.index') }}" class="btn btn-primary">
                        Bekijk opties
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline">
                        Offerte aanvragen
                    </a>
                </div>
            </div>

            <div class="relative h-[440px] hidden lg:block animate-on-scroll" aria-hidden="true">
                <!-- Soft brand glow behind the cluster -->
                <div class="absolute inset-8 rounded-[2rem] bg-gradient-to-br from-primary-100/60 to-accent-100/50 blur-2xl"></div>

                <!-- Capability cards: real content, real depth, gently floating -->
                <div class="absolute top-2 left-6 w-60 rounded-2xl bg-white/90 backdrop-blur ring-1 ring-slate-900/5 shadow-xl shadow-slate-900/10 p-5 animate-float">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-primary-50 text-primary-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h12A2.25 2.25 0 0020.25 14.25V3M3.75 3h16.5M3.75 3H2.25m18 0h1.5m-15 18h9M8.25 21v-4.5M15.75 21v-4.5"/></svg>
                        </span>
                        <div>
                            <p class="font-heading font-bold text-slate-900 leading-tight">Ontwerp op maat</p>
                            <p class="text-sm text-slate-500">In uw eigen huisstijl</p>
                        </div>
                    </div>
                </div>

                <div class="absolute top-28 right-2 w-56 rounded-2xl bg-white/90 backdrop-blur ring-1 ring-slate-900/5 shadow-xl shadow-slate-900/10 p-5 animate-float" style="animation-delay: 1s">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-accent-50 text-accent-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>
                        </span>
                        <div>
                            <p class="font-heading font-bold text-slate-900 leading-tight">Mobiel-first</p>
                            <p class="text-sm text-slate-500">Vlekkeloos op elk scherm</p>
                        </div>
                    </div>
                </div>

                <div class="absolute bottom-24 left-0 w-56 rounded-2xl bg-white/90 backdrop-blur ring-1 ring-slate-900/5 shadow-xl shadow-slate-900/10 p-5 animate-float" style="animation-delay: 2s">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        </span>
                        <div>
                            <p class="font-heading font-bold text-slate-900 leading-tight">Vindbaar in Google</p>
                            <p class="text-sm text-slate-500">SEO vanaf de basis</p>
                        </div>
                    </div>
                </div>

                <div class="absolute bottom-0 right-10 w-60 rounded-2xl bg-white/90 backdrop-blur ring-1 ring-slate-900/5 shadow-xl shadow-slate-900/10 p-5 animate-float" style="animation-delay: 3s">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-primary-50 text-primary-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                        </span>
                        <div>
                            <p class="font-heading font-bold text-slate-900 leading-tight">Supersnel geladen</p>
                            <p class="text-sm text-slate-500">Bezoekers haken niet af</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Portfolio Cinematic -->
<section id="portfolio-cinematic" class="relative h-[500vh] -mb-px bg-slate-950 text-white">
    <!-- Background ambience -->
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-primary-900/30 via-slate-950 to-slate-950"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[80rem] h-[80rem] bg-accent-900/10 rounded-full blur-3xl"></div>

    <!-- Sticky stage -->
    <div id="portfolio-stage" class="sticky top-0 h-screen w-full overflow-hidden" style="perspective: 1400px;">
        <!-- Intro heading -->
        <div id="portfolio-heading" class="absolute top-12 md:top-16 left-0 right-0 text-center z-30 px-6 transition-opacity duration-300">
            <span class="text-accent-400 font-semibold tracking-[0.2em] uppercase text-sm mb-3 block">Portfolio</span>
            <h2 class="font-heading text-4xl md:text-6xl lg:text-7xl font-bold mb-4">Ons portfolio</h2>
            <p class="text-lg md:text-xl text-slate-300 max-w-2xl mx-auto">Scroll per scherm om de templates te verkennen.</p>
        </div>

        <!-- Progress dots -->
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 z-30 flex gap-3">
            @foreach([1,2,3] as $i)
                <div class="portfolio-dot w-2 h-2 rounded-full bg-slate-600 transition-all duration-300" data-index="{{ $i }}"></div>
            @endforeach
        </div>

        <!-- Animated cards -->
        <div id="portfolio-cards" class="absolute inset-0 z-10">
            @foreach([1,2,3] as $i)
                <div class="portfolio-card absolute top-1/2 left-1/2 w-72 h-52 sm:w-80 sm:h-56 md:w-96 md:h-72 rounded-3xl bg-gradient-to-br from-slate-800 to-slate-950 border border-white/10 shadow-2xl shadow-primary-900/30 flex items-center justify-center will-change-transform" data-index="{{ $i }}">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-primary-900/40 via-transparent to-transparent rounded-3xl"></div>
                    <span class="relative z-10 text-7xl sm:text-8xl md:text-9xl font-black text-transparent bg-clip-text bg-gradient-to-br from-primary-300 to-accent-300 select-none">T{{ $i }}</span>
                </div>
            @endforeach
        </div>

        <!-- Info panels - centered, no border, always readable -->
        <div id="portfolio-info" class="absolute inset-0 z-20 pointer-events-none">
            @foreach([1,2,3] as $i)
                <div class="portfolio-info absolute inset-0 flex items-center justify-center pointer-events-auto px-6 md:px-12 opacity-0 will-change-transform" data-index="{{ $i }}">
                    <div class="w-full max-w-xl lg:max-w-2xl text-center">
                    <span class="text-accent-400 text-sm font-semibold tracking-[0.2em] uppercase mb-2 block">Project {{ $i }}</span>
                    <h3 class="font-heading text-3xl md:text-5xl lg:text-6xl font-bold mb-5">Template {{ $i }}</h3>
                    <p class="text-base md:text-lg lg:text-xl text-slate-300 mb-6 leading-relaxed">
                        Deze template laat zien hoe een moderne, conversiegerichte website eruit kan zien. Met een heldere structuur, snelle laadtijden en een design dat op elk apparaat optimaal werkt, maakt u direct een professionele indruk. De kleuren, afbeeldingen en teksten zijn volledig aanpasbaar naar uw huisstijl, zodat u een unieke online uitstraling krijgt die aansluit bij uw merk. De template is bovendien voorbereid op uitbreidingen, zodat uw website moeiteloos mee kan groeien met uw ambities. Neem contact op om de mogelijkheden voor uw project te bespreken.
                    </p>

                    <div class="space-y-3 mb-8">
                        <div class="flex items-center justify-center text-slate-200">
                            <span class="w-2 h-2 rounded-full bg-accent-400 mr-3 shadow-[0_0_10px_rgba(45,212,191,0.6)]"></span>
                            Modern, responsive ontwerp
                        </div>
                        <div class="flex items-center justify-center text-slate-200">
                            <span class="w-2 h-2 rounded-full bg-primary-400 mr-3 shadow-[0_0_10px_rgba(56,189,248,0.6)]"></span>
                            Geoptimaliseerd voor snelheid
                        </div>
                        <div class="flex items-center justify-center text-slate-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 mr-3 shadow-[0_0_10px_rgba(52,211,153,0.6)]"></span>
                            Duidelijke call-to-actions
                        </div>
                    </div>

                    <a href="{{ route('contact') }}" class="text-accent-300 hover:text-accent-200 font-semibold inline-flex items-center justify-center group/link">
                        Meer informatie
                        <svg class="w-5 h-5 ml-2 transform group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<script>
(function () {
    const section = document.getElementById('portfolio-cinematic');
    if (!section) return;

    const stage = document.getElementById('portfolio-stage');
    const heading = document.getElementById('portfolio-heading');
    const cards = Array.from(stage.querySelectorAll('.portfolio-card'));
    const infos = Array.from(stage.querySelectorAll('.portfolio-info'));
    const dots = Array.from(stage.querySelectorAll('.portfolio-dot'));

    const clamp = (v, lo, hi) => Math.max(lo, Math.min(hi, v));
    const lerp = (a, b, t) => a + (b - a) * t;
    const ease = t => t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t;

    const LEFT_X = -0.30;
    const RIGHT_X = 0.30;
    const STACK_Y = 0.16;

    function getState(p) {
        if (p < 0.15) return { from: null, to: null, t: p / 0.15 };                 // intro row
        if (p < 0.25) return { from: null, to: 0, t: (p - 0.15) / 0.10 };            // intro -> T1
        if (p < 0.35) return { from: 0, to: 0, t: 1 };                              // T1 hold
        if (p < 0.45) return { from: 0, to: 1, t: (p - 0.35) / 0.10 };              // T1 -> T2
        if (p < 0.55) return { from: 1, to: 1, t: 1 };                              // T2 hold
        if (p < 0.65) return { from: 1, to: 2, t: (p - 0.55) / 0.10 };              // T2 -> T3
        if (p < 0.75) return { from: 2, to: 2, t: 1 };                              // T3 hold
        if (p < 0.85) return { from: 2, to: null, t: (p - 0.75) / 0.10 };            // T3 -> outro
        return { from: null, to: null, t: 1 };                                       // outro row
    }

    function getHoldState(activeIdx, W, H) {
        const positions = [];
        for (let i = 0; i < 3; i++) {
            if (activeIdx === null) {
                positions.push({ x: (i - 1) * W * 0.18, y: 0, scale: 1, opacity: 1, rotY: 0, rotZ: 0, z: 15 });
            } else if (i === activeIdx) {
                positions.push({ x: LEFT_X * W, y: 0, scale: 1.12, opacity: 1, rotY: 6, rotZ: 0, z: 25 });
            } else {
                const order = [(activeIdx + 1) % 3, (activeIdx + 2) % 3];
                const stackIdx = order.indexOf(i);
                positions.push({
                    x: RIGHT_X * W,
                    y: (stackIdx === 0 ? -1 : 1) * H * STACK_Y,
                    scale: 0.78,
                    opacity: 0.55,
                    rotY: -6,
                    rotZ: stackIdx === 0 ? -3 : 3,
                    z: 15
                });
            }
        }

        const infoStates = infos.map((_, i) => ({
            opacity: activeIdx === i ? 1 : 0,
            scale: activeIdx === i ? 1 : 0.96,
            x: 0
        }));

        return { cardPositions: positions, infoStates };
    }

    function update() {
        const rect = section.getBoundingClientRect();
        const total = section.offsetHeight - window.innerHeight;
        let p = total > 0 ? -rect.top / total : 0;
        p = clamp(p, 0, 1);

        const W = window.innerWidth;
        const H = window.innerHeight;

        const state = getState(p);
        const t = ease(state.t);

        const from = getHoldState(state.from, W, H);
        const to = getHoldState(state.to, W, H);

        const headOp = p < 0.12 ? 1 : clamp(1 - (p - 0.12) / 0.08, 0, 1);
        heading.style.opacity = headOp;
        heading.style.pointerEvents = headOp > 0.1 ? 'auto' : 'none';

        let activeIdx = -1;
        if (state.to !== null) activeIdx = state.to;
        else if (state.from !== null) activeIdx = state.from;
        dots.forEach((dot, i) => {
            dot.classList.toggle('bg-accent-400', i === activeIdx);
            dot.classList.toggle('bg-slate-600', i !== activeIdx);
            dot.classList.toggle('scale-125', i === activeIdx);
            dot.classList.toggle('w-4', i === activeIdx);
        });

        cards.forEach((card, i) => {
            const a = from.cardPositions[i];
            const b = to.cardPositions[i];
            const x = lerp(a.x, b.x, t);
            const y = lerp(a.y, b.y, t);
            const scale = lerp(a.scale, b.scale, t);
            const opacity = lerp(a.opacity, b.opacity, t);
            const rotY = lerp(a.rotY, b.rotY, t);
            const rotZ = lerp(a.rotZ, b.rotZ, t);
            const z = t < 0.5 ? a.z : b.z;

            card.style.opacity = opacity;
            card.style.zIndex = z;
            card.style.transform = `translate(-50%, -50%) translate3d(${x}px, ${y}px, 0) scale(${scale}) rotateY(${rotY}deg) rotateZ(${rotZ}deg)`;
        });

        infos.forEach((info, i) => {
            const a = from.infoStates[i];
            const b = to.infoStates[i];
            const opacity = lerp(a.opacity, b.opacity, t);
            const scale = lerp(a.scale, b.scale, t);
            const x = lerp(a.x, b.x, t);

            if (opacity > 0.01) {
                info.style.opacity = opacity;
                info.style.transform = `scale(${scale})`;
                info.style.pointerEvents = 'auto';
            } else {
                info.style.opacity = 0;
                info.style.transform = 'scale(0.96)';
                info.style.pointerEvents = 'none';
            }
        });
    }

    window.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update);
    update();
})();
</script>

<!-- Closing CTA -->
<section class="relative overflow-hidden bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900">
    <!-- Ambient depth -->
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
            Klaar om uw website<br class="hidden sm:block"> tot leven te zien komen?
        </h2>
        <p class="mt-6 mx-auto max-w-xl text-lg text-primary-100 leading-relaxed animate-on-scroll">
            Plan een vrijblijvend gesprek. Wij denken met u mee, u zit nergens aan vast
            en binnen 1 werkdag hoort u van ons.
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
