<!-- Hero Section: two-column, scroll-driven "website wordt gebouwd" in a monitor -->
<section id="hero-build" class="relative h-[240vh] bg-slate-950 text-white">
    <div class="sticky top-16 h-[calc(100vh-4rem)] overflow-hidden flex items-center" data-hero-build data-hero-parallax>
        <!-- Ambient light -->
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-primary-800/40 via-slate-950 to-slate-950"></div>
        <div class="absolute -left-40 top-1/3 h-96 w-96 rounded-full bg-accent-500/10 blur-3xl" data-parallax="0.4"></div>
        <!-- Interactive spotlight: follows the pointer -->
        <div class="hero-spotlight absolute inset-0 pointer-events-none" aria-hidden="true"></div>
        <!-- Ambient craft: subtle dot grid over the empty space -->
        <div class="hero-dotgrid absolute inset-0 pointer-events-none" aria-hidden="true" data-parallax="0.15"></div>

        <div class="relative z-10 mx-auto grid w-full max-w-7xl grid-cols-1 items-center gap-10 px-6 lg:grid-cols-[9fr_11fr] lg:gap-14">
            <!-- Left: copy stays pinned + reachable from the first frame -->
            <div>
                <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3.5 py-1.5 text-sm font-medium text-slate-200">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-accent-400 opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-accent-400"></span>
                    </span>
                    Nu beschikbaar voor nieuwe projecten
                </span>
                <h1 class="mt-6 font-heading text-4xl md:text-5xl font-bold leading-[1.05] tracking-tight">
                    Zie hoe uw website tot leven komt.
                </h1>
                <p class="mt-6 max-w-xl text-lg leading-relaxed text-slate-300">
                    Wij bouwen 'm van begin tot eind — ontwerp, techniek, hosting en onderhoud.
                    U heeft één vast aanspreekpunt en hoeft er zelf niets technisch van te weten.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row sm:items-center gap-4">
                    <a href="{{ route('contact') }}" class="btn btn-primary text-base px-7 py-3.5">
                        Plan een vrijblijvend gesprek
                    </a>
                    {{-- TODO: vervang ‹TELEFOON› door het echte telefoonnummer vóór livegang --}}
                    <a href="tel:‹TELEFOON›" class="inline-flex items-center gap-2 font-medium text-slate-200 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        Of bel ons: ‹TELEFOON›
                    </a>
                </div>
                <!-- Human element: the team you actually speak to. Real photos can replace the monograms later. -->
                <div class="mt-9 flex items-center gap-3 text-sm text-slate-400">
                    <div class="flex -space-x-2">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-primary-500 to-primary-700 text-xs font-bold text-white ring-2 ring-slate-950" aria-hidden="true">T</span>
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-secondary-500 to-secondary-700 text-xs font-bold text-white ring-2 ring-slate-950" aria-hidden="true">D</span>
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-accent-500 to-accent-700 text-xs font-bold text-white ring-2 ring-slate-950" aria-hidden="true">I</span>
                    </div>
                    <span>U spreekt met ons team — geen helpdesk, geen wachtrij.</span>
                </div>

                <div class="mt-8 flex items-center gap-2 text-sm text-slate-400" data-hero-build-hint>
                    <svg class="h-4 w-4 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                    Scroll en kijk mee terwijl de site wordt gebouwd
                </div>
            </div>

            <!-- Right: computer monitor with the building animation -->
            <div class="w-full justify-self-center lg:justify-self-end">
                <div class="hero-monitor">
                    <div class="hero-monitor-screen">
                        <div class="hero-build" data-hero-build-stage>
                            <!-- Browser chrome -->
                            <div class="hero-build-chrome hero-build-piece" data-build-piece="chrome">
                                <span class="hero-build-dot"></span><span class="hero-build-dot"></span><span class="hero-build-dot"></span>
                                <div class="hero-build-address">www.uwbedrijf.nl</div>
                            </div>

                            <!-- Scrolling site body -->
                            <div class="hero-build-viewport">
                                <div class="hero-build-site" data-hero-build-site>
                                    <div class="hero-build-nav hero-build-piece" data-build-piece="nav">
                                        <span class="hero-build-logo">Uw Bedrijf</span>
                                        <span class="hero-build-navlinks"><i></i><i></i><i></i></span>
                                    </div>
                                    <div class="hero-build-hero hero-build-piece" data-build-piece="hero">
                                        <div class="hero-build-hero-copy">
                                            <span class="hero-build-line hero-build-line-lg"></span>
                                            <span class="hero-build-line hero-build-line-md"></span>
                                            <span class="hero-build-cta"></span>
                                        </div>
                                        <div class="hero-build-hero-img"></div>
                                    </div>
                                    <div class="hero-build-cards hero-build-piece" data-build-piece="cards">
                                        <div class="hero-build-card"><span></span><i></i><i></i></div>
                                        <div class="hero-build-card"><span></span><i></i><i></i></div>
                                        <div class="hero-build-card"><span></span><i></i><i></i></div>
                                    </div>
                                    <div class="hero-build-strip hero-build-piece" data-build-piece="strip">
                                        <span class="hero-build-line hero-build-line-md"></span>
                                        <span class="hero-build-line hero-build-line-sm"></span>
                                    </div>
                                    <div class="hero-build-footer hero-build-piece" data-build-piece="footer">
                                        <i></i><i></i><i></i><i></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Idle "building" animation, shown before scrolling -->
                            <div class="hero-build-idle" data-hero-build-idle aria-hidden="true">
                                <div class="hero-build-idle-rows">
                                    <span class="hero-build-idle-bar" style="--i:0"></span>
                                    <span class="hero-build-idle-block" style="--i:1"></span>
                                    <span class="hero-build-idle-bar hero-build-idle-bar-sm" style="--i:2"></span>
                                    <div class="hero-build-idle-cards">
                                        <span style="--i:3"></span><span style="--i:4"></span><span style="--i:5"></span>
                                    </div>
                                </div>
                                <div class="hero-build-idle-sweep"></div>
                                <div class="hero-build-idle-label">Bezig met opbouwen<span>.</span><span>.</span><span>.</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="hero-monitor-neck"></div>
                    <div class="hero-monitor-base"></div>
                    <div class="hero-monitor-reflection" aria-hidden="true"></div>

                    <!-- Floating UI accents (ambient craft) -->
                    <div class="hero-badge hero-badge-tl animate-float hidden lg:inline-flex" aria-hidden="true">
                        <svg class="h-3.5 w-3.5 text-accent-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        SSL &amp; dagelijkse back-ups
                    </div>
                    <div class="hero-badge hero-badge-tr animate-float hidden lg:inline-flex" style="animation-delay: 0.7s" aria-hidden="true">
                        <svg class="h-3.5 w-3.5 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Supersnel geladen
                    </div>
                    <div class="hero-badge hero-badge-br animate-float hidden lg:inline-flex" style="animation-delay: 1.3s" aria-hidden="true">
                        <svg class="h-3.5 w-3.5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Binnen 1 werkdag antwoord
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
(function () {
    const section = document.getElementById('hero-build');
    if (!section) return;
    const stage = section.querySelector('[data-hero-build]');
    const shell = section.querySelector('[data-hero-build-stage]');
    const site = section.querySelector('[data-hero-build-site]');
    const viewport = section.querySelector('.hero-build-viewport');
    const idle = section.querySelector('[data-hero-build-idle]');
    const hint = section.querySelector('[data-hero-build-hint]');
    const pieces = Array.from(section.querySelectorAll('[data-build-piece]'));

    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const clamp = (v, lo, hi) => Math.max(lo, Math.min(hi, v));
    const ease = (t) => t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
    const band = (p, a, b) => clamp((p - a) / (b - a), 0, 1);

    const order = {
        chrome:     [0.00, 0.10],
        nav:        [0.08, 0.24],
        hero:       [0.20, 0.44],
        cards:      [0.44, 0.68],
        strip:      [0.64, 0.82],
        footer:     [0.80, 0.96],
    };

    // No-JS fallback shows the finished site (CSS default). When scripted, take control.
    shell.classList.add('is-scripted');

    if (reduce) {
        shell.classList.add('is-finished');
        if (idle) idle.style.display = 'none';
        if (hint) hint.style.display = 'none';
        return;
    }

    function apply(p) {
        pieces.forEach((el) => {
            const [a, b] = order[el.getAttribute('data-build-piece')] || [0, 1];
            const lp = ease(band(p, a, b));
            el.style.opacity = lp;
            el.style.transform = `translate3d(0, ${(1 - lp) * 24}px, 0)`;
        });
        const overflow = Math.max(0, site.scrollHeight - viewport.clientHeight);
        site.style.transform = `translate3d(0, ${-overflow * ease(clamp(p, 0, 1))}px, 0)`;
        if (idle) idle.style.opacity = clamp(1 - p * 12, 0, 1);
        if (hint) hint.style.opacity = clamp(1 - p * 5, 0, 1);
    }

    function update() {
        const total = section.offsetHeight - window.innerHeight;
        apply(clamp(-section.getBoundingClientRect().top / total, 0, 1));
    }

    window.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update);
    update();

    // Interactive background: pointer-driven spotlight + parallax
    if (!reduce) {
        const spotlight = stage.querySelector('.hero-spotlight');
        const layers = Array.from(stage.querySelectorAll('[data-parallax]'));
        // target vs. current values for smooth easing
        let tx = 0.5, ty = 0.5, cx = 0.5, cy = 0.5, raf = null, active = false;

        function onMove(e) {
            const r = stage.getBoundingClientRect();
            tx = clamp((e.clientX - r.left) / r.width, 0, 1);
            ty = clamp((e.clientY - r.top) / r.height, 0, 1);
            if (!active) { active = true; stage.classList.add('is-pointer-active'); }
            if (!raf) raf = requestAnimationFrame(render);
        }

        function render() {
            raf = null;
            cx += (tx - cx) * 0.12;
            cy += (ty - cy) * 0.12;
            if (spotlight) {
                spotlight.style.setProperty('--hero-mx', (cx * 100).toFixed(2) + '%');
                spotlight.style.setProperty('--hero-my', (cy * 100).toFixed(2) + '%');
            }
            // shift layers opposite to the pointer for a subtle depth effect
            const dx = (cx - 0.5), dy = (cy - 0.5);
            layers.forEach((el) => {
                const depth = parseFloat(el.getAttribute('data-parallax')) || 0;
                el.style.transform = `translate3d(${(-dx * depth * 40).toFixed(2)}px, ${(-dy * depth * 40).toFixed(2)}px, 0)`;
            });
            if (Math.abs(tx - cx) > 0.001 || Math.abs(ty - cy) > 0.001) {
                raf = requestAnimationFrame(render);
            }
        }

        function onLeave() {
            active = false;
            stage.classList.remove('is-pointer-active');
            tx = 0.5; ty = 0.5;
            if (!raf) raf = requestAnimationFrame(render);
        }

        stage.addEventListener('pointermove', onMove, { passive: true });
        stage.addEventListener('pointerleave', onLeave, { passive: true });
    }
})();
</script>
