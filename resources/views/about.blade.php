@extends('layouts.app')

@section('title', 'Over Ons - Servura')
@section('meta-description', 'Lees meer over Servura en bekijk ons portfolio van websites die we hebben gemaakt voor mkb-bedrijven.')
@section('meta-keywords', 'over ons, portfolio, webdesign cases, klanten, ervaring')

@section('content')
<!-- Hero Section -->
<section class="relative h-[calc(100svh-4rem)] min-h-[36rem] flex items-center justify-center overflow-hidden">
    <!-- Background image: Rotterdam, Zuid-Holland -->
    <img
        src="https://images.unsplash.com/photo-1526505917130-857817501277?fm=jpg&q=80&w=1920&auto=format&fit=crop"
        alt="Skyline van Rotterdam in Zuid-Holland"
        class="absolute inset-0 w-full h-full object-cover"
    >
    <div class="absolute inset-0 bg-gradient-to-br from-primary-900/85 via-primary-800/75 to-primary-900/80"></div>

    <div class="relative w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-8 drop-shadow-lg">
            Over Servura
        </h1>
        <p class="text-xl lg:text-2xl text-white/95 max-w-3xl mx-auto mb-6 drop-shadow-md leading-relaxed">
            Wij zijn Servura, uw partner voor professionele websites, betrouwbare hosting en technisch onderhoud. Wij helpen ondernemers en organisaties met het opbouwen van een sterke online aanwezigheid door websites te ontwikkelen die niet alleen professioneel ogen, maar ook snel, veilig en gebruiksvriendelijk zijn.
        </p>
        <p class="text-lg text-white/90 max-w-3xl mx-auto mb-6 drop-shadow-md leading-relaxed">
            Wij geloven dat een goede website meer is dan alleen een mooi ontwerp. Daarom begeleiden wij u tijdens het volledige traject: van het eerste gesprek en het ontwerp tot de ontwikkeling, hosting, beveiliging en het doorlopende onderhoud. Zo heeft u één aanspreekpunt voor alles wat met uw website te maken heeft.
        </p>
        <p class="text-lg text-white/90 max-w-3xl mx-auto drop-shadow-md leading-relaxed">
            Met een persoonlijke aanpak, heldere communicatie en oog voor kwaliteit zorgen wij ervoor dat uw website perfect aansluit bij uw bedrijf en uw doelen. Zo kunt u zich richten op het ondernemen, terwijl wij ervoor zorgen dat uw online aanwezigheid betrouwbaar, professioneel en klaar voor de toekomst is.
        </p>
    </div>
</section>

<!-- About Content -->
<section class="py-16 bg-gradient-to-b from-slate-100 via-slate-50 to-primary-50/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-6">
                    Onze Missie
                </h2>
                <p class="text-lg text-gray-600 mb-4">
                    Servura is opgericht met een duidelijke missie: het MKB helpen met een professionele 
                    online aanwezigheid zonder de complexiteit en hoge kosten die vaak bij webdevelopment komen kijken.
                </p>
                <p class="text-lg text-gray-600 mb-4">
                    We begrijpen dat als ondernemer u geen tijd heeft voor technische details. Daarom nemen wij 
                    alles uit handen: van het eerste ontwerp tot het dagelijks onderhoud.
                </p>
                <p class="text-lg text-gray-600 mb-6">
                    Onze focus ligt op kwaliteit, betrouwbaarheid en persoonlijke service. Elke klant is uniek 
                    en verdient een website die perfect past bij het bedrijf.
                </p>
                
                <div class="grid grid-cols-2 gap-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-primary-600 mb-2">99.9% Uptime Garantie</div>
                        <div class="text-gray-600">&nbsp;</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-primary-600 mb-2">Pas klaar als jij het zegt</div>
                        <div class="text-gray-600">&nbsp;</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-slate-50/90 p-8 rounded-2xl shadow-lg shadow-primary-900/5 ring-1 ring-slate-200">
                <h3 class="text-2xl font-semibold mb-6">Onze Waarden</h3>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold mb-1">Eerlijkheid</h4>
                            <p class="text-gray-600">Transparante prijzen en heldere communicatie</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold mb-1">Kwaliteit</h4>
                            <p class="text-gray-600">Professionele oplossingen die gewoon werken</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold mb-1">Service</h4>
                            <p class="text-gray-600">Persoonlijke support en snelle reactietijd</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold mb-1">Resultaat</h4>
                            <p class="text-gray-600">Focus op uw bedrijfsdoelen en online succes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-16 bg-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Ons Team
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Een klein team van professionals met passie voor webdevelopment en klantenservice.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            @php
                $team = [
                    ['name' => 'Tim van Gorkom', 'role' => 'Founder & Lead Developer', 'bio' => 'Richting, architectuur en de technische visie van Servura.', 'initial' => 'T', 'color' => 'from-primary-500 to-primary-700'],
                    ['name' => 'Dirk van Gelderen', 'role' => 'Front-end & Back-end Developer', 'bio' => 'Bouwt solide applicaties en zorgt voor snelle, nette interfaces.', 'initial' => 'D', 'color' => 'from-secondary-600 to-secondary-800', 'image' => 'images/dirk-van-gelderen.jpg'],
                    ['name' => 'Isis van Dijk', 'role' => 'UX/UI Specialist & Design Expert', 'bio' => 'Ontwerpt intuïtieve ervaringen die bezoekers converteren.', 'initial' => 'I', 'color' => 'from-emerald-400 to-emerald-600'],
                ];
            @endphp
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
                <h3 class="text-xl font-semibold mb-1">{{ $member['name'] }}</h3>
                <p class="text-primary-600 font-medium mb-2">{{ $member['role'] }}</p>
                <p class="text-gray-500 text-sm max-w-xs mx-auto">{{ $member['bio'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Portfolio Section -->
<section class="py-16 lg:py-24 bg-gradient-to-b from-slate-100 to-primary-50/70">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Ons portfolio
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Hieronder vindt u een selectie van projecten die wij in eigen beheer hebben ontwikkeld. Deze voorbeelden geven een indruk van onze werkwijze, ontwerpstijl en de kwaliteit die u van Servura kunt verwachten.
            </p>
        </div>

        <div class="portfolio-grid">
            @foreach([1,2,3] as $i)
                <div class="card animate-on-scroll overflow-hidden group">
                    <div class="relative w-full h-56 bg-gray-100 flex items-center justify-center">
                        <span class="text-7xl font-black text-gray-200 select-none group-hover:scale-110 transition-transform duration-300">T{{ $i }}</span>
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/10 to-transparent"></div>
                    </div>
                    <div class="card-body text-center">
                        <h3 class="text-xl font-semibold mb-2">Template {{ $i }}</h3>
                        <p class="text-gray-500 text-sm">
                            Een toekomstig portfolio-item dat hier binnenkort wordt toegevoegd.
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
