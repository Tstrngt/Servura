@extends('layouts.app')

@section('title', 'Servura - Professionele Websites en Hosting voor het MKB')
@section('meta-description', 'Servura helpt mkb-bedrijven met professionele websites en betrouwbare hosting. Volledig ontzorgd, van ontwerp tot onderhoud.')
@section('meta-keywords', 'webdesign, hosting, mkb, website ontwikkeling, domeinnaam, email, onderhoud')

@section('content')
<!-- Hero Section -->
<section class="hero-gradient text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 animate-slide-up">
                Professionele Websites<br>
                <span class="text-primary-200">voor het MKB</span>
            </h1>
            <p class="text-xl md:text-2xl text-primary-100 mb-8 max-w-3xl mx-auto animate-slide-up" style="animation-delay: 0.1s">
                Servura helpt mkb-ondernemers met een sterke online aanwezigheid. 
                Van ontwerp tot hosting, volledig ontzorgd.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center animate-slide-up" style="animation-delay: 0.2s">
                <a href="{{ route('contact') }}" class="btn bg-white text-primary-600 hover:bg-primary-50 text-lg px-8 py-4">
                    Gratis Adviesgesprek
                </a>
                <a href="{{ route('services.index') }}" class="btn btn-outline border-white text-white hover:bg-white hover:text-primary-600 text-lg px-8 py-4">
                    Bekijk Diensten
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Waarom Servura?
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Wij begrijpen het MKB. Geen technische jargon, alleen oplossingen die werken.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center animate-on-scroll">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Professioneel Design</h3>
                <p class="text-gray-600">
                    Moderne, responsive websites die perfect werken op elk apparaat.
                </p>
            </div>

            <div class="text-center animate-on-scroll">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Betrouwbare Hosting</h3>
                <p class="text-gray-600">
                    Snelle, veilige hosting met 99.9% uptime en dagelijkse backups.
                </p>
            </div>

            <div class="text-center animate-on-scroll">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Volledig Onderhoud</h3>
                <p class="text-gray-600">
                    Wij regelen alles: updates, security, backups en technische support.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Services Preview -->
@if($popularServices->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Onze Populaire Diensten
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Alles wat uw bedrijf nodig heeft voor een sterke online aanwezigheid.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($popularServices as $service)
                <div class="service-card {{ $service->is_popular ? 'featured' : '' }} animate-on-scroll">
                    @if($service->is_popular)
                        <div class="bg-primary-600 text-white text-center py-2 text-sm font-semibold">
                            Meest Populair
                        </div>
                    @endif
                    <div class="card-body">
                        <h3 class="text-xl font-semibold mb-2">{{ $service->title }}</h3>
                        <p class="text-gray-600 mb-4">{{ $service->short_description }}</p>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-2xl font-bold text-primary-600">{{ $service->formatted_price }}</span>
                        </div>
                        <a href="{{ route('services.show', $service) }}" class="btn btn-primary w-full">
                            Meer Informatie
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('services.index') }}" class="btn btn-outline">
                Bekijk Alle Diensten
            </a>
        </div>
    </div>
</section>
@endif

<!-- Portfolio Preview -->
@if($featuredPortfolio->count() > 0)
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Recent Werk
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Een selectie van websites die we hebben mogen maken voor onze klanten.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($featuredPortfolio as $item)
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
                        <p class="text-gray-600 mb-2">{{ $item->client_name }}</p>
                        <p class="text-gray-500 text-sm">{{ Str::limit($item->description, 100) }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('about') }}" class="btn btn-outline">
                Bekijk Al ons Werk
            </a>
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-16 bg-primary-600 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">
            Klaar om uw bedrijf online te laten groeien?
        </h2>
        <p class="text-xl text-primary-100 mb-8">
            Plan een gratis adviesgesprek en ontdek hoe wij u kunnen helpen.
        </p>
        <a href="{{ route('contact') }}" class="btn bg-white text-primary-600 hover:bg-primary-50 text-lg px-8 py-4">
            Plan Gratis Adviesgesprek
        </a>
    </div>
</section>
@endsection
