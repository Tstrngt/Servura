@extends('layouts.docs')

@section('title', 'Helpcentrum - Servura')
@section('meta-description', 'Handleidingen en uitleg over je website, domeinnaam, e-mail en account bij Servura.')

@section('content')
@php use App\Support\Docs; @endphp

<!-- Hero + search -->
<section class="border-b border-slate-200 bg-slate-900 text-white">
    <div class="mx-auto max-w-3xl px-4 py-16 text-center sm:px-6 sm:py-20">
        <h1 class="font-heading text-4xl font-bold sm:text-5xl">Waar kunnen we je mee helpen?</h1>
        <p class="mt-4 text-lg text-slate-300">
            Hier vind je uitleg over je website, domeinnaam, e-mail en account bij Servura — stap voor stap, in gewone taal.
        </p>
        <form method="GET" action="{{ Docs::url() }}/zoeken" class="mt-8" role="search">
            <label for="docs-search" class="sr-only">Zoek in het helpcentrum</label>
            <div class="relative">
                <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                <input id="docs-search" type="search" name="q" placeholder="Zoek een onderwerp, bijv. 'e-mail instellen' of 'nameservers'"
                       class="w-full rounded-2xl border-0 bg-white py-4 pl-12 pr-28 text-base text-slate-900 shadow-lg ring-1 ring-slate-200 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500">
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 rounded-xl bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-white">Zoeken</button>
            </div>
        </form>

        <!-- Directe links -->
        <div class="mt-8 grid grid-cols-1 gap-3 text-left sm:grid-cols-3">
            <a href="{{ Docs::url('aan-de-slag', 'inloggen-klantomgeving') }}" class="group rounded-xl bg-white/10 p-4 ring-1 ring-white/15 transition hover:bg-white/15 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-400">
                <span class="block text-sm font-semibold text-white">Inloggen op mijn klantomgeving</span>
                <span class="mt-1 block text-xs text-slate-300">Waar log ik in en wat als mijn wachtwoord niet werkt?</span>
            </a>
            <a href="{{ Docs::url('e-mail', 'email-instellen-apparaat') }}" class="group rounded-xl bg-white/10 p-4 ring-1 ring-white/15 transition hover:bg-white/15 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-400">
                <span class="block text-sm font-semibold text-white">E-mail instellen</span>
                <span class="mt-1 block text-xs text-slate-300">Zo zet je je e-mail op je telefoon of computer.</span>
            </a>
            <div class="rounded-xl bg-white/10 p-4 ring-1 ring-white/15">
                <span class="block text-sm font-semibold text-white">Mijn website of e-mail werkt niet</span>
                <div class="mt-2 flex flex-wrap gap-2">
                    <a href="{{ Docs::url('website-en-hosting', 'website-niet-bereikbaar') }}" class="rounded-lg bg-white/15 px-2.5 py-1 text-xs font-medium text-white hover:bg-white/25 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-400">Website</a>
                    <a href="{{ Docs::url('e-mail', 'email-werkt-niet') }}" class="rounded-lg bg-white/15 px-2.5 py-1 text-xs font-medium text-white hover:bg-white/25 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-400">E-mail</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Veelgebruikte handleidingen -->
@php
    $featured = collect($categories)->flatMap(fn ($c) => collect($c['pages'])->where('featured')->map(fn ($p) => $p + ['category' => $c['slug'], 'category_title' => $c['title']]))->take(6);
@endphp
@if($featured->isNotEmpty())
<section class="border-b border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <h2 class="font-heading text-lg font-bold text-slate-900">Veelgebruikte handleidingen</h2>
        <ul class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($featured as $p)
                <li>
                    <a href="{{ Docs::url($p['category'], $p['slug']) }}" class="flex items-center gap-3 rounded-xl bg-slate-50 px-4 py-3 ring-1 ring-slate-200/70 transition hover:bg-primary-50 hover:ring-primary-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500">
                        <svg class="h-5 w-5 shrink-0 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                        <span class="text-sm font-medium text-slate-800">{{ $p['title'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>
@endif

<!-- Categorieën -->
<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
    <h2 class="sr-only">Onderwerpen</h2>
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse($categories as $cat)
            <a href="{{ Docs::url($cat['slug']) }}" class="flex flex-col rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70 transition hover:shadow-md hover:ring-primary-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500">
                <h3 class="font-heading text-xl font-bold text-slate-900">{{ $cat['title'] }}</h3>
                @if($cat['description'])
                    <p class="mt-2 text-sm text-slate-500">{{ $cat['description'] }}</p>
                @endif
                <ul class="mt-4 space-y-1.5 border-t border-slate-100 pt-4">
                    @foreach(array_slice($cat['pages'], 0, 4) as $p)
                        <li class="flex items-center gap-2 text-sm text-slate-600">
                            <span class="h-1 w-1 shrink-0 rounded-full bg-slate-300"></span>
                            {{ $p['title'] }}
                        </li>
                    @endforeach
                </ul>
                <span class="mt-auto inline-flex items-center gap-1 pt-5 text-sm font-semibold text-primary-600">
                    Alle {{ count($cat['pages']) }} artikelen
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </span>
            </a>
        @empty
            <p class="text-slate-500">Er is nog geen documentatie beschikbaar.</p>
        @endforelse
    </div>

    <!-- Support CTA -->
    <div class="mt-12 rounded-2xl bg-slate-900 p-8 text-white sm:p-10">
        <div class="flex flex-col items-start justify-between gap-6 sm:flex-row sm:items-center">
            <div>
                <h2 class="font-heading text-2xl font-bold">Kom je er niet uit?</h2>
                <p class="mt-2 text-slate-300">Stel je vraag via een ticket in het klantportaal, dan helpt ons team je verder.</p>
            </div>
            <a href="{{ Docs::siteUrl('customer.tickets.create') }}" class="shrink-0 rounded-xl bg-primary-600 px-6 py-3 text-sm font-semibold text-white hover:bg-primary-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-white">Ticket aanmaken</a>
        </div>
    </div>
</section>
@endsection
