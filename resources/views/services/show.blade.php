@extends('layouts.app')

@section('title', $service->title . ' - Servura')
@section('meta-description', $service->short_description)
@section('meta-keywords', $service->title . ', servura, mkb, ' . strtolower($service->title))

@section('content')
<!-- Hero Section -->
<section class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                    {{ $service->title }}
                </h1>
                <p class="text-xl text-gray-600 mb-6">
                    {{ $service->short_description }}
                </p>
                <div class="flex items-center mb-6">
                    <span class="text-3xl font-bold text-primary-600">{{ $service->formatted_price }}</span>
                </div>
                <a href="{{ route('contact') }}" class="btn btn-primary text-lg px-8 py-4">
                    Vraag Offerte Aan
                </a>
            </div>
            
            @if($service->image_url)
                <div>
                    <img src="{{ $service->image_url }}" alt="{{ $service->title }}" class="w-full rounded-lg shadow-lg">
                </div>
            @else
                <div class="bg-gray-200 rounded-lg h-96 flex items-center justify-center">
                    <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Service Details -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">
                        Wat is inbegrepen?
                    </h2>
                    
                    @if($service->features && count($service->features) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                            @foreach($service->features as $feature)
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-gray-700">{{ $feature }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                    <div class="prose prose-lg max-w-none">
                        {!! $service->description !!}
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div>
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-24">
                    <h3 class="text-xl font-semibold mb-4">Snelle Info</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <div class="text-sm text-gray-500">Prijs</div>
                            <div class="text-xl font-bold text-primary-600">{{ $service->formatted_price }}</div>
                        </div>
                        
                        <div>
                            <div class="text-sm text-gray-500">Type</div>
                            <div class="text-gray-900">{{ ucfirst($service->price_type) }}</div>
                        </div>
                        
                        <div>
                            <div class="text-sm text-gray-500">Ondersteuning</div>
                            <div class="text-gray-900">Inbegrepen</div>
                        </div>
                        
                        <div>
                            <div class="text-sm text-gray-500">Levering</div>
                            <div class="text-gray-900">Binnen 2-4 weken</div>
                        </div>
                    </div>
                    
                    <div class="mt-6 space-y-3">
                        <a href="{{ route('contact') }}?service={{ $service->slug }}" class="btn btn-primary w-full">
                            Vraag Offerte Aan
                        </a>
                        <a href="tel:0612345678" class="btn btn-outline w-full">
                            Bel Ons Direct
                        </a>
                    </div>
                    
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="font-semibold">14 Dagen Geld Terug</span>
                        </div>
                        <p class="text-sm text-gray-600">
                            Niet tevreden? U krijgt uw geld terug.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Services -->
@if($relatedServices->count() > 0)
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Andere Diensten
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Bekijk ook deze gerelateerde diensten die interessant kunnen zijn.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($relatedServices as $relatedService)
                <div class="card animate-on-scroll">
                    @if($relatedService->image_url)
                        <img src="{{ $relatedService->image_url }}" alt="{{ $relatedService->title }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    @endif
                    
                    <div class="card-body">
                        <h3 class="text-xl font-semibold mb-2">{{ $relatedService->title }}</h3>
                        <p class="text-gray-600 mb-4">{{ $relatedService->short_description }}</p>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-lg font-bold text-primary-600">{{ $relatedService->formatted_price }}</span>
                        </div>
                        <a href="{{ route('services.show', $relatedService) }}" class="btn btn-outline w-full">
                            Bekijk Details
                        </a>
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
            Klaar om te beginnen?
        </h2>
        <p class="text-xl text-primary-100 mb-8">
            Neem contact met ons op voor een gratis adviesgesprek en persoonlijke offerte.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('contact') }}?service={{ $service->slug }}" class="btn bg-white text-primary-600 hover:bg-primary-50 text-lg px-8 py-4">
                Vraag Offerte Aan
            </a>
            <a href="tel:0612345678" class="btn btn-outline border-white text-white hover:bg-white hover:text-primary-600 text-lg px-8 py-4">
                Bel: 06 123 456 78
            </a>
        </div>
    </div>
</section>
@endsection
