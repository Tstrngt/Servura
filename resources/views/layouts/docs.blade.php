<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta-description', 'Servura documentatie - handleidingen en uitleg over hosting, DirectAdmin en je klantportaal')">
    <meta name="author" content="Servura">

    <title>@yield('title', 'Documentatie - Servura')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    @vite(['resources/css/app.css'])

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <meta name="theme-color" content="#0f172a">

    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased">
    <header class="sticky top-0 z-50 border-b border-slate-200 bg-white/90 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="flex items-center" aria-label="Servura home">
                    <span class="logo-text text-2xl font-extrabold logo-mark">Servura</span>
                    <span class="logo-dot ml-1 text-2xl font-logo font-bold text-primary-600">.</span>
                </a>
                <span class="hidden sm:inline-flex items-center rounded-full bg-primary-50 px-3 py-1 text-xs font-semibold text-primary-700 ring-1 ring-primary-100">Documentatie</span>
            </div>
            <nav class="flex items-center gap-2 sm:gap-4">
                <a href="{{ route('home') }}" class="hidden sm:inline text-sm font-medium text-slate-600 hover:text-primary-600">servura.nl</a>
                @auth
                    <a href="{{ auth()->user()->canAccessAdmin() ? route('admin.dashboard') : route('customer.dashboard') }}" class="btn btn-primary !py-2 text-sm">Klantportaal</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary !py-2 text-sm">Klantportaal</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="min-h-screen">
        @yield('content')
    </main>

    <footer class="border-t border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-3 px-4 py-8 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <p>&copy; {{ date('Y') }} {{ config('company.trade_name', 'Servura') }} &middot; Documentatie</p>
            <div class="flex gap-5">
                <a href="{{ route('contact') }}" class="hover:text-primary-600">Contact</a>
                <a href="{{ route('legal.privacy') }}" class="hover:text-primary-600">Privacy</a>
                <a href="{{ route('legal.terms') }}" class="hover:text-primary-600">Voorwaarden</a>
            </div>
        </div>
    </footer>

    @vite(['resources/js/app.js'])
</body>
</html>
