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
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicon-48x48.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <meta name="theme-color" content="#0f172a">

    <style>[x-cloak] { display: none !important; }</style>

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
    <div id="page-loader" data-turbo-permanent class="fixed inset-0 z-[60] flex items-center justify-center bg-white/95 backdrop-blur-sm transition-opacity duration-500">
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
    @unless(request()->routeIs('admin.*') || request()->routeIs('customer.*'))
    @php
        // Only these pages open with a dark hero section; render the navbar's
        // dark glass theme server-side for them so there's no JS timing gap
        // at the very top of the page (scrollY 0). Everything else (login,
        // dashboards, services.show, ...) opens light.
        $navStartsDark = request()->routeIs('home', 'about', 'contact', 'services.index');
    @endphp
    <nav class="site-navbar sticky top-0 z-50 {{ $navStartsDark ? 'is-dark' : '' }}" data-navbar>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid h-16 grid-cols-[minmax(0,1fr)_auto] items-center md:grid-cols-3">
                <!-- Logo -->
                <div class="justify-self-start">
                    <a href="{{ route('home') }}" class="flex items-center group" aria-label="Servura home">
                        <span class="logo-text text-2xl font-extrabold logo-mark group-hover:opacity-80 transition-opacity">Servura</span>
                        <span class="logo-dot ml-1 text-2xl font-logo font-bold text-primary-600 animate-pulse-soft">.</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex justify-self-center items-center space-x-1">
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

                <!-- CTA + Login -->
                <div class="hidden md:flex items-center gap-3 justify-self-end">
                    @if(config('site.language_menu'))
                        <div class="relative" x-data="{ langOpen: false }">
                            <button type="button" @click="langOpen = !langOpen" class="icon-btn inline-flex h-10 items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 text-xs font-semibold uppercase tracking-wide text-slate-600 hover:border-primary-300 hover:text-primary-600 hover:bg-primary-50 transition-colors" aria-label="Taal kiezen">
                                {{ strtoupper(app()->getLocale()) }}
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="langOpen" @click.outside="langOpen = false" x-transition class="absolute right-0 top-12 z-50 w-40 overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-slate-200" style="display:none">
                                @foreach(['nl' => 'Nederlands', 'en' => 'English', 'de' => 'Deutsch', 'fr' => 'Français'] as $locale => $label)
                                    <form method="POST" action="{{ route('locale.switch') }}">
                                        @csrf
                                        <input type="hidden" name="locale" value="{{ $locale }}">
                                        <button type="submit" class="block w-full px-4 py-2.5 text-left text-sm {{ app()->getLocale() === $locale ? 'font-semibold text-primary-600 bg-primary-50' : 'text-slate-600 hover:bg-slate-50' }}">{{ $label }}</button>
                                    </form>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @guest
                        <a href="{{ route('login') }}" title="Inloggen" aria-label="Inloggen" class="icon-btn inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 hover:border-primary-300 hover:text-primary-600 hover:bg-primary-50 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </a>
                        <a href="{{ route('quote.builder') }}" class="btn btn-primary nav-cta">
                            Offerte Aanvragen
                        </a>
                    @else
                        @include('partials.notifications', ['bellClass' => 'text-gray-500 hover:text-primary-600'])
                        <a href="{{ auth()->user()->canAccessAdmin() ? route('admin.dashboard') : route('customer.dashboard') }}" title="{{ auth()->user()->canAccessAdmin() ? 'Adminportaal' : 'Klantportaal' }}" aria-label="{{ auth()->user()->canAccessAdmin() ? 'Adminportaal' : 'Klantportaal' }}" class="icon-btn inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 hover:border-primary-300 hover:text-primary-600 hover:bg-primary-50 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary nav-cta">Uitloggen</button>
                        </form>
                    @endguest
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden justify-self-end">
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
                        <a href="{{ route('quote.builder') }}" class="block w-full text-center btn btn-primary">
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

    <!-- Main Content -->
    <main>
        <!-- Success/Error Messages (not on admin pages or login; those views handle their own placement) -->
        @if(!request()->routeIs('admin.*') && !request()->routeIs('login'))
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4">
                    <div class="max-w-7xl mx-auto flex">
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
                <div class="bg-red-50 border-l-4 border-red-400 p-4">
                    <div class="max-w-7xl mx-auto flex">
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
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    @unless(request()->routeIs('admin.*'))
    <footer class="footer {{ request()->routeIs('home') || request()->routeIs('about') || request()->routeIs('services.*') || request()->routeIs('contact') || request()->routeIs('login') ? 'mt-0' : 'mt-16' }}">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 lg:gap-12">
                <!-- Company Info -->
                <div class="max-w-2xl">
                    <h3 class="logo-text text-2xl font-bold logo-mark mb-4">Servura<span class="text-primary-400">.</span></h3>
                    <p class="text-gray-300 mb-4">
                        Professionele websites, webhosting en technisch beheer voor het MKB. Persoonlijk contact en duidelijke afspraken.
                    </p>
                    @php
                        $socialLinks = [
                            'instagram' => ['label' => 'Instagram', 'path' => 'M7.75 2h8.5A5.75 5.75 0 0122 7.75v8.5A5.75 5.75 0 0116.25 22h-8.5A5.75 5.75 0 012 16.25v-8.5A5.75 5.75 0 017.75 2zM16 11.37A4 4 0 1111.37 8 4 4 0 0116 11.37zM17.5 6.5h.01'],
                            'linkedin' => ['label' => 'LinkedIn', 'path' => 'M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-4 0v7h-4v-7a6 6 0 016-6zM2 9h4v12H2zM4 2a2 2 0 110 4 2 2 0 010-4z'],
                            'facebook' => ['label' => 'Facebook', 'path' => 'M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z'],
                            'youtube' => ['label' => 'YouTube', 'path' => 'M22.54 6.42a2.78 2.78 0 00-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 00-1.94 2A29 29 0 001 12a29 29 0 00.46 5.58 2.78 2.78 0 001.94 2C5.12 20 12 20 12 20s6.88 0 8.6-.46a2.78 2.78 0 001.94-2A29 29 0 0023 12a29 29 0 00-.46-5.58zM9.75 15.02V8.98L15.5 12z'],
                            'tiktok' => ['label' => 'TikTok', 'path' => 'M15 3v10.5a4.5 4.5 0 11-4.5-4.5M15 3c.6 2.4 2.1 3.9 4.5 4.5'],
                            'x' => ['label' => 'X', 'path' => 'M4 4l16 16M20 4L4 20'],
                        ];
                    @endphp
                    <div class="flex flex-wrap gap-3">
                        @foreach($socialLinks as $key => $social)
                            @if(config('social.' . $key))
                                <a href="{{ config('social.' . $key) }}" target="_blank" rel="noopener noreferrer" class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white/5 text-slate-300 ring-1 ring-white/10 transition hover:bg-white/10 hover:text-white" aria-label="Servura op {{ $social['label'] }}">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $social['path'] }}"/></svg>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Quick Links -->
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Snelle links</h4>
                        <ul class="space-y-2">
                            <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white">Home</a></li>
                            <li><a href="{{ route('about') }}" class="text-gray-300 hover:text-white">Over ons</a></li>
                            <li><a href="{{ route('services.index') }}" class="text-gray-300 hover:text-white">Diensten</a></li>
                            <li><a href="{{ route('contact') }}" class="text-gray-300 hover:text-white">Contact</a></li>
                        </ul>
                    </div>

                    <!-- Services -->
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Diensten</h4>
                        <ul class="space-y-2">
                            <li><a href="{{ route('services.index') }}" class="text-gray-300 hover:text-white">Websites</a></li>
                            <li><a href="{{ route('services.index') }}#hosting" class="text-gray-300 hover:text-white">Webhosting</a></li>
                            <li><a href="{{ route('services.index') }}" class="text-gray-300 hover:text-white">Onderhoud / beheer</a></li>
                        </ul>
                    </div>

                    <!-- Legal -->
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Juridisch</h4>
                        <ul class="space-y-2">
                            <li><a href="{{ route('legal.terms') }}" class="text-gray-300 hover:text-white">Algemene voorwaarden</a></li>
                            <li><a href="{{ route('legal.privacy') }}" class="text-gray-300 hover:text-white">Privacyverklaring</a></li>
                            <li><a href="{{ route('legal.cookies') }}" class="text-gray-300 hover:text-white">Cookieverklaring</a></li>
                            <li><a href="{{ route('legal.hosting') }}" class="text-gray-300 hover:text-white">Hostingvoorwaarden</a></li>
                            <li><a href="{{ route('legal.acceptable-use') }}" class="text-gray-300 hover:text-white">Acceptable Use Policy</a></li>
                            <li><a href="{{ route('legal.abuse') }}" class="text-gray-300 hover:text-white">Misbruik melden</a></li>
                            <li><button type="button" onclick="window.openCookieConsent()" class="text-left text-gray-300 hover:text-white">Cookievoorkeuren</button></li>
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Contact</h4>
                        <ul class="space-y-2 text-gray-300 text-sm">
                            <li>{{ config('company.legal_name', config('company.trade_name', 'Servura')) }}</li>
                            <li>{{ config('company.address', '[Adres]') }}</li>
                            <li>{{ config('company.postal_code', '[Postcode]') }} {{ config('company.city', '[Plaats]') }}</li>
                            <li>KvK: {{ config('company.kvk_number', '[KvK]') }}</li>
                            <li class="pt-1"><a href="mailto:{{ config('company.email', '#') }}" class="hover:text-white">{{ config('company.email', '[E-mail]') }}</a></li>
                            @if(config('company.phone'))
                                <li><a href="tel:{{ config('company.phone') }}" class="hover:text-white">{{ config('company.phone') }}</a></li>
                            @endif
                        </ul>

                        @if(config('site.newsletter_enabled'))
                            <div class="mt-6">
                                <h4 class="text-sm font-semibold mb-2 text-white">Nieuwsbrief</h4>
                                @if(session('newsletter_success'))
                                    <p class="text-sm text-emerald-400">{{ session('newsletter_success') }}</p>
                                @else
                                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex gap-2">
                                        @csrf
                                        <input type="email" name="email" required placeholder="jouw@email.nl" class="w-full min-w-0 rounded-lg border-0 bg-white/5 px-3 py-2 text-sm text-white placeholder:text-slate-400 ring-1 ring-white/10 focus:ring-2 focus:ring-primary-400">
                                        <button type="submit" class="shrink-0 rounded-lg bg-primary-600 px-3 py-2 text-sm font-semibold text-white hover:bg-primary-500">Aanmelden</button>
                                    </form>
                                    @error('email')
                                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                    @enderror
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Servura. Alle rechten voorbehouden.</p>
            </div>
        </div>
    </footer>
    @endunless

    <!-- Cookie consent banner & modal -->
    <div x-data="cookieConsent" x-init="init" aria-live="polite">
        <!-- Banner -->
        <div x-show="bannerOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="translate-y-full opacity-0" x-cloak class="fixed bottom-0 left-0 right-0 z-[70] bg-slate-900 text-white shadow-2xl" style="display: none;">
            <div class="max-w-7xl mx-auto px-4 py-5 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="max-w-2xl">
                        <p class="text-sm md:text-base text-slate-100">
                            Wij gebruiken cookies. Noodzakelijke cookies zijn altijd actief. Analytische en marketingcookies gebruiken wij alleen met uw toestemming.
                            <a href="{{ route('legal.cookies') }}" class="underline hover:text-white text-slate-300">Meer informatie</a>.
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <button type="button" @click="openPreferences" class="text-sm px-4 py-2 rounded-lg text-slate-200 hover:text-white hover:bg-white/10 transition">Voorkeuren</button>
                        <button type="button" @click="acceptNecessary" class="text-sm px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 text-white transition">Alleen noodzakelijk</button>
                        <button type="button" @click="acceptAll" class="text-sm px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-500 text-white font-medium transition">Alles accepteren</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preferences modal -->
        <div x-show="modalOpen" x-cloak class="fixed inset-0 z-[80]" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="cookie-modal-title">
            <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm transition-opacity" @click="modalOpen = false"></div>
            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div x-show="modalOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl p-6 md:p-8">
                    <h2 id="cookie-modal-title" class="font-heading text-2xl font-bold text-slate-900 mb-2">Cookievoorkeuren</h2>
                    <p class="text-slate-500 text-sm mb-6">Beheer hieronder uw cookievoorkeuren. U kunt deze keuze later altijd wijzigen via de footer.</p>

                    <div class="space-y-4">
                        <div class="flex items-start justify-between gap-4 rounded-xl bg-slate-50 p-4">
                            <div>
                                <h3 class="font-semibold text-slate-900">Noodzakelijk</h3>
                                <p class="text-sm text-slate-500">Nodig voor de werking van de website en beveiliging.</p>
                            </div>
                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Altijd aan</span>
                        </div>

                        <div class="flex items-start justify-between gap-4 rounded-xl border border-slate-200 p-4">
                            <div>
                                <h3 class="font-semibold text-slate-900">Analytisch</h3>
                                <p class="text-sm text-slate-500">Helpt ons begrijpen hoe bezoekers de website gebruiken.</p>
                            </div>
                            <label class="relative inline-flex cursor-pointer items-center">
                                <input type="checkbox" x-model="consent.analytics" class="sr-only peer">
                                <span class="h-6 w-11 rounded-full bg-slate-200 peer-focus:ring-2 peer-focus:ring-primary-500 peer-checked:bg-primary-600 after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:transition peer-checked:after:translate-x-5"></span>
                            </label>
                        </div>

                        <div class="flex items-start justify-between gap-4 rounded-xl border border-slate-200 p-4">
                            <div>
                                <h3 class="font-semibold text-slate-900">Marketing</h3>
                                <p class="text-sm text-slate-500">Wordt gebruikt voor relevante advertenties en campagnes.</p>
                            </div>
                            <label class="relative inline-flex cursor-pointer items-center">
                                <input type="checkbox" x-model="consent.marketing" class="sr-only peer">
                                <span class="h-6 w-11 rounded-full bg-slate-200 peer-focus:ring-2 peer-focus:ring-primary-500 peer-checked:bg-primary-600 after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:transition peer-checked:after:translate-x-5"></span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col-reverse sm:flex-row gap-3">
                        <button type="button" @click="modalOpen = false" class="w-full sm:w-auto px-5 py-2.5 rounded-xl text-slate-600 hover:bg-slate-100 font-medium">Annuleren</button>
                        <button type="button" @click="savePreferences(consent.analytics, consent.marketing)" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white font-medium">Voorkeuren opslaan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
