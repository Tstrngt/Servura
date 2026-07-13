@extends('layouts.app')

@section('title', 'Over Ons - Servura')
@section('meta-description', 'Lees meer over Servura en bekijk ons portfolio van websites die we hebben gemaakt voor mkb-bedrijven.')
@section('meta-keywords', 'over ons, portfolio, webdesign cases, klanten, ervaring')

@section('content')
<!-- Hero Section -->
<section class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                Over Servura
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Wij zijn Servura, uw partner voor professionele websites en betrouwbare hosting. 
                Gespecialiseerd in het MKB, met focus op resultaat en service.
            </p>
        </div>
    </div>
</section>

<!-- About Content -->
<section class="py-16 bg-gray-50">
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
                        <div class="text-3xl font-bold text-primary-600 mb-2">50+</div>
                        <div class="text-gray-600">Tevreden Klanten</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-primary-600 mb-2">99.9%</div>
                        <div class="text-gray-600">Uptime Garantie</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-8 rounded-lg shadow-sm">
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
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Ons Team
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Een klein team van professionals met passie voor webdevelopment en klantenservice.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-32 h-32 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Jan Smit</h3>
                <p class="text-gray-600 mb-2">Founder & Lead Developer</p>
                <p class="text-gray-500 text-sm">10+ jaar ervaring in webdevelopment</p>
            </div>

            <div class="text-center">
                <div class="w-32 h-32 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Lisa de Vries</h3>
                <p class="text-gray-600 mb-2">UX/UI Designer</p>
                <p class="text-gray-500 text-sm">Gespecialiseerd in gebruiksvriendelijk design</p>
            </div>

            <div class="text-center">
                <div class="w-32 h-32 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Peter Bakker</h3>
                <p class="text-gray-600 mb-2">Support Engineer</p>
                <p class="text-gray-500 text-sm">Expert in hosting en technische support</p>
            </div>
        </div>
    </div>
</section>

<!-- Portfolio Section -->
@if($portfolioItems->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Ons Portfolio
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Een selectie van websites die we hebben mogen maken voor onze klanten.
            </p>
        </div>

        <div class="portfolio-grid">
            @foreach($portfolioItems as $item)
                <div class="card animate-on-scroll">
                    @if($item->image_url)
                        <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="card-body">
                        <h3 class="text-xl font-semibold mb-2">{{ $item->title }}</h3>
                        <p class="text-primary-600 font-medium mb-2">{{ $item->client_name }}</p>
                        <p class="text-gray-600 mb-4">{{ $item->description }}</p>
                        
                        @if($item->technologies && count($item->technologies) > 0)
                            <div class="flex flex-wrap gap-2 mb-4">
                                @foreach($item->technologies as $technology)
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                                        {{ $technology }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                        
                        @if($item->website_url)
                            <a href="{{ $item->website_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline">
                                Bekijk Website
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-16 bg-primary-600 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">
            Klaar om samen te werken?
        </h2>
        <p class="text-xl text-primary-100 mb-8">
            Neem contact met ons op en ontdek hoe wij uw bedrijf online kunnen laten groeien.
        </p>
        <a href="{{ route('contact') }}" class="btn bg-white text-primary-600 hover:bg-primary-50 text-lg px-8 py-4">
            Neem Contact Op
        </a>
    </div>
</section>
@endsection
