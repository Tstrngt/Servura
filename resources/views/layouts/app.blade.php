<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta-description', 'Servura - Professionele websites en hosting voor het MKB')">
    <meta name="keywords" content="@yield('meta-keywords', 'webdesign, hosting, mkb, websites, servura')">
    <meta name="author" content="Servura">
    
    <title>@yield('title', 'Servura - MKB Websites en Hosting')</title>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Styles -->
    @vite(['resources/css/app.css'])
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Open Graph -->
    <meta property="og:title" content="@yield('og:title', 'Servura - MKB Websites en Hosting')">
    <meta property="og:description" content="@yield('og:description', 'Servura helpt mkb-bedrijven met professionele websites en hosting')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og:image', asset('images/og-image.jpg'))">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter:title', 'Servura - MKB Websites en Hosting')">
    <meta name="twitter:description" content="@yield('twitter:description', 'Servura helpt mkb-bedrijven met professionele websites en hosting')">
    <meta name="twitter:image" content="@yield('twitter:image', asset('images/og-image.jpg'))">
</head>
<body class="bg-gray-50" x-data="{ mobileMenu: false }">
    <!-- Page transition loader (only on public/customer pages) -->
    @unless(request()->routeIs('admin.*'))
    <div id="page-loader" class="fixed inset-0 z-[60] flex items-center justify-center bg-white/95 backdrop-blur-sm transition-opacity duration-500">
        <div class="flex flex-col items-center">
            <span class="logo-text text-4xl font-extrabold logo-mark mb-4 animate-pulse-soft">Servura</span>
            <svg class="animate-spin h-6 w-6 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="mt-3 text-sm font-medium text-gray-500">Laden...</span>
        </div>
    </div>
    @endunless

    <!-- Navigation -->
    @unless(request()->routeIs('admin.*'))
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center group" aria-label="Servura home">
                        <span class="logo-text text-2xl font-extrabold logo-mark group-hover:opacity-80 transition-opacity">Servura</span>
                        <span class="ml-1 text-2xl font-logo font-bold text-primary-600 animate-pulse-soft">.</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'nav-link active' : 'nav-link' }}">
                            Home
                        </a>
                        <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'nav-link active' : 'nav-link' }}">
                            Over Ons
                        </a>
                        <a href="{{ route('services.index') }}" class="{{ request()->routeIs('services.*') ? 'nav-link active' : 'nav-link' }}">
                            Diensten
                        </a>
                        <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'nav-link active' : 'nav-link' }}">
                            Contact
                        </a>
                    </div>
                </div>

                <!-- CTA Button -->
                <div class="hidden md:flex items-center gap-3">
                    @guest
                        <a href="{{ route('login') }}" class="nav-link">
                            Inloggen
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-primary">
                            Offerte Aanvragen
                        </a>
                    @else
                        @include('partials.notifications', ['bellClass' => 'text-gray-500 hover:text-primary-600'])
                        <a href="{{ auth()->user()->canAccessAdmin() ? route('admin.dashboard') : route('customer.dashboard') }}" class="nav-link">
                            {{ auth()->user()->canAccessAdmin() ? 'Adminportaal' : 'Klantportaal' }}
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">Uitloggen</button>
                        </form>
                    @endguest
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button @click="mobileMenu = !mobileMenu" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500">
                        <svg class="h-6 w-6" :class="mobileMenu ? 'hidden' : 'block'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg class="h-6 w-6" :class="mobileMenu ? 'block' : 'hidden'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="md:hidden" x-show="mobileMenu" x-transition>
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white border-t border-gray-200">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50">
                    Home
                </a>
                <a href="{{ route('about') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50">
                    Over Ons
                </a>
                <a href="{{ route('services.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50">
                    Diensten
                </a>
                <a href="{{ route('contact') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50">
                    Contact
                </a>
                <div class="pt-4 pb-3 border-t border-gray-200 space-y-2">
                    @guest
                        <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50">
                            Inloggen
                        </a>
                        <a href="{{ route('contact') }}" class="block w-full text-center btn btn-primary">
                            Offerte Aanvragen
                        </a>
                    @else
                        <a href="{{ auth()->user()->canAccessAdmin() ? route('admin.dashboard') : route('customer.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50">
                            {{ auth()->user()->canAccessAdmin() ? 'Adminportaal' : 'Klantportaal' }}
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="block w-full text-center btn btn-primary">Uitloggen</button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </nav>
    @endunless

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @unless(request()->routeIs('admin.*'))
    <footer class="footer {{ request()->routeIs('home') ? 'mt-0' : 'mt-16' }}">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="col-span-1 md:col-span-2">
                    <h3 class="logo-text text-2xl font-bold logo-mark mb-4">Servura<span class="text-primary-400">.</span></h3>
                    <p class="text-gray-300 mb-4">
                        Servura biedt de oplossing voor mkb-ondernemers van het opbouwen van een online omgeving en het hosten. Tot hulp bij aanpassingen.
                    </p>
                    <div class="flex space-x-4">
                        <!-- Social media links can be added here -->
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Snelle Links</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white">Home</a></li>
                        <li><a href="{{ route('about') }}" class="text-gray-300 hover:text-white">Over Ons</a></li>
                        <li><a href="{{ route('services.index') }}" class="text-gray-300 hover:text-white">Diensten</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-300 hover:text-white">Contact</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-gray-300">
                        <li>Neem contact met ons op via het contactformulier</li>
                        <li>We reageren binnen 48 uur</li>
                        <li>Gratis adviesgesprek</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Servura. Alle rechten voorbehouden.</p>
            </div>
        </div>
    </footer>
    @endunless

    <!-- Page transition script -->
    <script>
        (function() {
            const loader = document.getElementById('page-loader');
            if (!loader) return;

            function hideLoader() {
                setTimeout(() => {
                    loader.classList.add('opacity-0', 'pointer-events-none');
                }, 200);
            }

            window.addEventListener('pageshow', function(e) {
                if (e.persisted) hideLoader();
            });
            window.addEventListener('beforeunload', function() {
                loader.classList.remove('opacity-0', 'pointer-events-none');
            });

            hideLoader();
        })();
    </script>

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
</body>
</html>
