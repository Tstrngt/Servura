@extends('layouts.docs')

@section('title', 'Zoeken: ' . $query . ' - Helpcentrum - Servura')
@section('meta-description', 'Zoekresultaten voor "' . $query . '" in het Servura-helpcentrum.')

@section('content')
@php use App\Support\Docs; @endphp

<div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
    <nav class="mb-8 flex items-center gap-2 text-sm text-slate-500" aria-label="Kruimelpad">
        <a href="{{ Docs::url() ?: '/' }}" class="hover:text-primary-600">Helpcentrum</a>
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
        <span class="font-medium text-slate-900">Zoeken</span>
    </nav>

    <h1 class="font-heading text-3xl font-bold text-slate-900">Zoeken</h1>

    <form method="GET" action="{{ Docs::url() }}/zoeken" class="mt-6" role="search">
        <label for="docs-search" class="sr-only">Zoek in het helpcentrum</label>
        <div class="relative">
            <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
            <input id="docs-search" type="search" name="q" value="{{ $query }}" placeholder="Zoek een onderwerp"
                   class="w-full rounded-2xl border-0 bg-white py-3.5 pl-12 pr-28 text-base text-slate-900 ring-1 ring-slate-200 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500">
            <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 rounded-xl bg-primary-600 px-5 py-2 text-sm font-semibold text-white hover:bg-primary-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-700">Zoeken</button>
        </div>
    </form>

    @if(count($results))
        <p class="mt-6 text-sm text-slate-500">{{ count($results) }} {{ count($results) === 1 ? 'resultaat' : 'resultaten' }} voor <strong class="text-slate-800">&ldquo;{{ $query }}&rdquo;</strong></p>
        <ul class="mt-4 space-y-3">
            @foreach($results as $r)
                <li>
                    <a href="{{ Docs::url($r['category'], $r['slug']) }}" class="group block rounded-xl bg-white p-5 ring-1 ring-slate-200/70 transition hover:ring-primary-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">{{ $r['category_title'] }}</span>
                        <span class="mt-1 flex items-center gap-2 font-semibold text-slate-900 group-hover:text-primary-700">
                            {{ $r['title'] }}
                            @if($r['draft'])
                                <span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-800">Concept</span>
                            @endif
                        </span>
                        @if($r['description'])
                            <span class="mt-1 block text-sm text-slate-500">{{ $r['description'] }}</span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    @else
        <div class="mt-10 rounded-2xl bg-white p-8 text-center ring-1 ring-slate-200/70">
            <h2 class="font-heading text-lg font-bold text-slate-900">Geen artikelen gevonden voor &ldquo;{{ $query }}&rdquo;</h2>
            <p class="mx-auto mt-2 max-w-md text-sm text-slate-500">
                Probeer een andere zoekterm, bijvoorbeeld &ldquo;e-mail&rdquo;, &ldquo;inloggen&rdquo;, &ldquo;domein&rdquo; of &ldquo;factuur&rdquo;.
                Lukt het niet om het antwoord te vinden? Stel je vraag via een ticket.
            </p>
            <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ Docs::url() ?: '/' }}" class="btn btn-secondary">Bekijk alle onderwerpen</a>
                <a href="{{ Docs::siteUrl('customer.tickets.create') }}" class="btn btn-primary">Ticket aanmaken</a>
            </div>
        </div>
    @endif
</div>
@endsection
