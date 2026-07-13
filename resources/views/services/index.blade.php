@extends('layouts.app')

@section('title', 'Diensten - Servura')
@section('meta-description', 'Bekijk alle diensten van Servura: webdesign, hosting, onderhoud en meer. Professionele oplossingen voor het MKB.')
@section('meta-keywords', 'diensten, webdesign, hosting, onderhoud, mkb, website ontwikkeling')

@section('content')
<!-- Hero Section -->
<section class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                Onze Diensten
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Alles wat uw bedrijf nodig heeft voor een professionele online aanwezigheid. 
                Van een nieuwe website tot betrouwbare hosting en ongoing support.
            </p>
        </div>
    </div>
</section>

<!-- Services Grid -->
@if($services->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($services as $service)
                <div class="service-card {{ $service->is_popular ? 'featured' : '' }} animate-on-scroll">
                    @if($service->is_popular)
                        <div class="bg-primary-600 text-white text-center py-2 text-sm font-semibold">
                            Meest Populair
                        </div>
                    @endif
                    
                    @if($service->image_url)
                        <img src="{{ $service->image_url }}" alt="{{ $service->title }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    @endif
                    
                    <div class="card-body">
                        <h3 class="text-xl font-semibold mb-2">{{ $service->title }}</h3>
                        <p class="text-gray-600 mb-4">{{ $service->short_description }}</p>
                        
                        @if($service->features && count($service->features) > 0)
                            <ul class="space-y-2 mb-6">
                                @foreach(array_slice($service->features, 0, 3) as $feature)
                                    <li class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                                @if(count($service->features) > 3)
                                    <li class="text-sm text-gray-500">
                                        En nog {{ count($service->features) - 3 }} meer...
                                    </li>
                                @endif
                            </ul>
                        @endif
                        
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
    </div>
</section>
@else
<section class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-white p-8 rounded-lg shadow-sm">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Geen diensten beschikbaar</h3>
            <p class="text-gray-600 mb-4">
 Er zijn momenteel geen diensten beschikbaar. Neem contact met ons op voor een maatwerk oplossing.
            </p>
            <a href="{{ route('contact') }}" class="btn btn-primary">
                Neem Contact Op
            </a>
        </div>
    </div>
</section>
@endif

<!-- Process Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Ons Proces
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Van eerste contact tot lancering, wij begeleiden u bij elke stap.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-12 h-12 bg-primary-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 font-bold">
                    1
                </div>
                <h3 class="text-lg font-semibold mb-2">Kennismaking</h3>
                <p class="text-gray-600 text-sm">
                    Gratis adviesgesprek om uw wensen en doelen te bespreken.
                </p>
            </div>

            <div class="text-center">
                <div class="w-12 h-12 bg-primary-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 font-bold">
                    2
                </div>
                <h3 class="text-lg font-semibold mb-2">Voorstel</h3>
                <p class="text-gray-600 text-sm">
                    Maatwerk voorstel met duidelijke prijs en planning.
                </p>
            </div>

            <div class="text-center">
                <div class="w-12 h-12 bg-primary-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 font-bold">
                    3
                </div>
                <h3 class="text-lg font-semibold mb-2">Ontwikkeling</h3>
                <p class="text-gray-600 text-sm">
                    Professionele ontwikkeling met regelmatige updates.
                </p>
            </div>

            <div class="text-center">
                <div class="w-12 h-12 bg-primary-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 font-bold">
                    4
                </div>
                <h3 class="text-lg font-semibold mb-2">Lancering</h3>
                <p class="text-gray-600 text-sm">
                    Lancering en ongoing support voor optimaal resultaat.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-primary-600 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">
            Welke dienst past bij uw bedrijf?
        </h2>
        <p class="text-xl text-primary-100 mb-8">
            Plan een gratis adviesgesprek en ontdek de beste oplossing voor uw bedrijf.
        </p>
        <a href="{{ route('contact') }}" class="btn bg-white text-primary-600 hover:bg-primary-50 text-lg px-8 py-4">
            Plan Adviesgesprek
        </a>
    </div>
</section>
@endsection
