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

<!-- Features Section -->
<section class="relative min-h-screen flex items-center bg-slate-50 overflow-hidden">
    <div class="absolute top-0 right-0 w-[30rem] h-[30rem] bg-primary-200/30 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
    <div class="absolute bottom-0 left-0 w-[30rem] h-[30rem] bg-accent-200/30 rounded-full blur-3xl translate-y-1/3 -translate-x-1/3"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 py-24 lg:py-32">
        <div class="text-center mb-16 animate-on-scroll">
            <span class="text-accent-600 font-semibold tracking-wide uppercase text-sm mb-3 block">Waarom Servura</span>
            <h2 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 mb-4">
                Persoonlijk, helder en betrouwbaar
            </h2>
            <p class="text-xl text-slate-600 max-w-2xl mx-auto">
                Wij staan open voor een gesprek en luisteren graag naar wat onze klanten willen. Daarna gaan we pas aan de slag.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-3xl p-8 shadow-lg border-t-4 border-primary-500 hover:-translate-y-2 transition-transform duration-300 animate-on-scroll">
                <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-primary-500/30">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="font-heading text-2xl font-bold text-slate-900 mb-3">Professioneel Design</h3>
                <p class="text-slate-600 leading-relaxed">
                    Moderne, responsive websites die perfect werken op elk apparaat.
                </p>
            </div>

            <div class="bg-white rounded-3xl p-8 shadow-lg border-t-4 border-accent-500 hover:-translate-y-2 transition-transform duration-300 animate-on-scroll">
                <div class="w-16 h-16 bg-gradient-to-br from-accent-500 to-accent-700 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-accent-500/30">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                    </svg>
                </div>
                <h3 class="font-heading text-2xl font-bold text-slate-900 mb-3">Betrouwbare Hosting</h3>
                <p class="text-slate-600 leading-relaxed">
                    Snelle, veilige hosting met 99.9% uptime en dagelijkse backups.
                </p>
            </div>

            <div class="bg-white rounded-3xl p-8 shadow-lg border-t-4 border-emerald-500 hover:-translate-y-2 transition-transform duration-300 animate-on-scroll">
                <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-emerald-500/30">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h3 class="font-heading text-2xl font-bold text-slate-900 mb-3">Volledig Onderhoud</h3>
                <p class="text-slate-600 leading-relaxed">
                    Wij regelen alles: updates, security, backups en technische support.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Services Preview -->
<section id="diensten" class="relative min-h-screen flex items-center bg-white overflow-hidden">
    <div class="absolute bottom-0 right-0 w-[35rem] h-[35rem] bg-accent-200/30 rounded-full blur-3xl translate-y-1/3 translate-x-1/3"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 py-24 lg:py-32">
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

            <div class="relative h-[420px] hidden lg:block animate-on-scroll">
                <div class="absolute top-0 left-8 w-56 h-36 rounded-2xl bg-gradient-to-br from-primary-500 to-primary-700 text-white flex items-center justify-center text-center font-semibold shadow-2xl animate-float">
                    Homepage
                </div>
                <div class="absolute top-24 right-0 w-56 h-36 rounded-2xl bg-gradient-to-br from-accent-500 to-accent-700 text-white flex items-center justify-center text-center font-semibold shadow-2xl animate-float" style="animation-delay: 1s">
                    Mobile
                </div>
                <div class="absolute bottom-24 left-0 w-56 h-36 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-white flex items-center justify-center text-center font-semibold shadow-2xl animate-float" style="animation-delay: 2s">
                    Contact
                </div>
                <div class="absolute bottom-0 right-12 w-56 h-36 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-700 text-white flex items-center justify-center text-center font-semibold shadow-2xl animate-float" style="animation-delay: 3s">
                    SEO
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
@endsection
