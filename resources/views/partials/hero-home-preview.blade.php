<section class="relative min-h-[calc(100svh-4rem)] overflow-hidden bg-slate-950 text-white">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-primary-700/45 via-slate-950 to-slate-950"></div>
    <div class="absolute -left-32 top-1/3 h-80 w-80 rounded-full bg-accent-500/10 blur-3xl"></div>

    <div class="relative mx-auto grid max-w-7xl grid-cols-1 items-center gap-12 px-6 py-16 lg:grid-cols-2 lg:gap-16 lg:py-20">
        <div class="max-w-2xl">
            <span class="inline-block rounded-full border border-white/20 bg-white/10 px-4 py-1.5 text-sm font-semibold uppercase tracking-wide text-accent-200">
                Websites & Hosting voor het MKB
            </span>
            <h1 class="mt-6 font-display text-5xl font-bold leading-[1.1] sm:text-6xl lg:text-7xl">
                Professionele Websites<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-200 via-accent-200 to-white">voor het MKB</span>
            </h1>
            <p class="mt-7 max-w-xl text-lg font-light leading-relaxed text-white/90 sm:text-xl">
                Servura biedt de oplossing voor mkb-ondernemers van het opbouwen van een online omgeving en het hosten.
                Tot hulp bij aanpassingen.
            </p>
            <div class="mt-9 flex flex-col gap-4 sm:flex-row">
                <a href="{{ route('contact') }}" class="btn bg-white px-8 py-4 text-lg font-bold text-primary-700 shadow-xl transition-transform hover:scale-105 hover:bg-primary-50">Gratis Adviesgesprek</a>
                <a href="{{ route('services.index') }}" class="btn border-2 border-white bg-white/10 px-8 py-4 text-lg font-semibold text-white shadow-xl backdrop-blur-sm transition-transform hover:scale-105 hover:border-white hover:bg-white hover:text-primary-700">Bekijk Diensten</a>
            </div>
        </div>

        <div class="w-full max-w-xl justify-self-center lg:justify-self-end">
            @include('partials.hero-preview')
        </div>
    </div>
</section>
