<!-- Hero Section -->
<section class="relative h-[calc(100svh-4rem)] flex items-center justify-center overflow-hidden text-white">
    <!-- Background video -->
    <video autoplay muted loop playsinline poster="https://assets.mixkit.co/videos/3919/3919-thumb-720-0.jpg" class="absolute inset-0 w-full h-full object-cover">
        <source src="https://assets.mixkit.co/videos/3919/3919-720.mp4" type="video/mp4">
    </video>

    <!-- Overlay -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary-900/85 via-primary-800/70 to-secondary-900/85"></div>
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-accent-900/20 via-transparent to-transparent"></div>

    <div class="relative z-10 max-w-5xl mx-auto px-6 text-center">
        <span class="inline-block px-4 py-1.5 rounded-full bg-white/10 text-accent-200 text-sm font-semibold tracking-wide uppercase mb-6 border border-white/20 animate-slide-up">
            Websites & Hosting voor het MKB
        </span>
        <h1 class="font-display text-5xl md:text-7xl lg:text-8xl font-bold mb-8 animate-slide-up drop-shadow-lg leading-[1.1]" style="animation-delay: 0.1s">
            Professionele Websites<br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-200 via-accent-200 to-white">voor het MKB</span>
        </h1>
        <p class="text-xl md:text-2xl text-white/95 mb-10 max-w-3xl mx-auto animate-slide-up drop-shadow-md leading-relaxed font-light" style="animation-delay: 0.2s">
            Servura biedt de oplossing voor mkb-ondernemers van het opbouwen van een online omgeving en het hosten.
            Tot hulp bij aanpassingen.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center animate-slide-up" style="animation-delay: 0.3s">
            <a href="{{ route('contact') }}" class="btn bg-white text-primary-700 hover:bg-primary-50 hover:scale-105 font-bold text-lg px-8 py-4 shadow-xl transition-transform">Gratis Adviesgesprek</a>
            <a href="{{ route('services.index') }}" class="btn bg-white/10 backdrop-blur-sm border-2 border-white text-white hover:bg-white hover:text-primary-700 hover:border-white font-semibold text-lg px-8 py-4 shadow-xl transition-transform">Bekijk Diensten</a>
        </div>
    </div>

    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce text-white/60">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
    </div>
</section>
