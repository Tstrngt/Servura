<!-- Hero Section -->
<section class="bg-white border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-6 py-20 lg:py-28">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <!-- Left: plain-language promise + one calm action -->
            <div>
                <span class="inline-block text-primary-700 font-semibold text-sm mb-5">
                    Websites voor kleine bedrijven
                </span>
                <h1 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 leading-[1.1] mb-6">
                    Een goede website voor uw bedrijf, zonder dat u er iets technisch van hoeft te weten.
                </h1>
                <p class="text-lg md:text-xl text-slate-600 leading-relaxed mb-8 max-w-xl">
                    Ik regel alles van begin tot eind: het ontwerp, de bouw, de hosting en het onderhoud.
                    U heeft één vast aanspreekpunt — dezelfde persoon die u nu spreekt.
                </p>
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <a href="{{ route('contact') }}" class="btn btn-primary text-base px-7 py-3.5">
                        Plan een vrijblijvend gesprek
                    </a>
                    {{-- TODO: vervang ‹TELEFOON› door het echte telefoonnummer vóór livegang --}}
                    <a href="tel:‹TELEFOON›" class="inline-flex items-center gap-2 text-slate-700 font-medium hover:text-primary-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        Of bel me: ‹TELEFOON›
                    </a>
                </div>
                <p class="text-sm text-slate-500 mt-5">
                    Geen verkooppraat. Meestal binnen één werkdag antwoord.
                </p>
            </div>

            <!-- Right: the person you actually speak to. Photo slot reserved; swap the monogram for <img> later. -->
            <div class="w-full max-w-md lg:justify-self-end">
                <div class="bg-slate-50 rounded-3xl ring-1 ring-slate-200 p-8 shadow-sm">
                    <div class="flex items-center gap-5 mb-6">
                        {{-- Foto komt hier later; vervang deze monogram-cirkel door een <img class="w-20 h-20 rounded-full object-cover">. --}}
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 text-white text-2xl font-bold flex items-center justify-center flex-shrink-0" aria-hidden="true">
                            T
                        </div>
                        <div>
                            <p class="font-heading text-xl font-bold text-slate-900">Tim van Gorkom</p>
                            <p class="text-slate-500">U spreekt met mij</p>
                        </div>
                    </div>
                    <p class="text-slate-600 leading-relaxed">
                        Ik bouw uw website zelf en blijf daarna uw vaste aanspreekpunt.
                        Loopt u ergens tegenaan of wilt u iets aanpassen, dan belt u gewoon mij — geen helpdesk, geen wachtrij.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
