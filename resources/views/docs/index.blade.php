@extends('layouts.docs')

@section('title', 'Documentatie - Servura')
@section('meta-description', 'Handleidingen en uitleg over je Servura-hosting: DirectAdmin, e-mail, domeinen en het klantportaal.')

@section('content')
@php use App\Support\Docs; @endphp

<!-- Hero -->
<section class="border-b border-slate-200 bg-slate-900 text-white">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8">
        <p class="text-sm font-semibold uppercase tracking-widest text-primary-400">Helpcentrum</p>
        <h1 class="mt-3 font-heading text-4xl font-bold sm:text-5xl">Hoe kunnen we helpen?</h1>
        <p class="mt-4 max-w-2xl text-lg text-slate-300">
            Handleidingen en uitleg over je hostingpakket, DirectAdmin, e-mail, domeinen en het klantportaal.
        </p>
    </div>
</section>

<!-- Categories -->
<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse($categories as $cat)
            <div class="flex flex-col rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70 transition hover:shadow-md hover:ring-primary-200">
                <h2 class="font-heading text-xl font-bold text-slate-900">{{ $cat['title'] }}</h2>
                @if($cat['description'])
                    <p class="mt-2 text-sm text-slate-500">{{ $cat['description'] }}</p>
                @endif
                <ul class="mt-5 space-y-2 border-t border-slate-100 pt-4">
                    @foreach(array_slice($cat['pages'], 0, 5) as $p)
                        <li>
                            <a href="{{ Docs::url($cat['slug'], $p['slug']) }}" class="group flex items-center gap-2 text-sm font-medium text-slate-700 hover:text-primary-600">
                                <svg class="h-4 w-4 shrink-0 text-slate-400 transition group-hover:text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                                {{ $p['title'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
                @php $first = $cat['pages'][0] ?? null; @endphp
                @if($first)
                    <a href="{{ Docs::url($cat['slug'], $first['slug']) }}" class="mt-auto pt-5 inline-flex items-center gap-1 text-sm font-semibold text-primary-600 hover:text-primary-700">
                        Alle artikelen
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    </a>
                @endif
            </div>
        @empty
            <p class="text-slate-500">Er is nog geen documentatie beschikbaar.</p>
        @endforelse
    </div>

    <!-- Contact CTA -->
    <div class="mt-12 rounded-2xl bg-slate-900 p-8 text-white sm:p-10">
        <div class="flex flex-col items-start justify-between gap-6 sm:flex-row sm:items-center">
            <div>
                <h2 class="font-heading text-2xl font-bold">Kom je er niet uit?</h2>
                <p class="mt-2 text-slate-300">Ons team helpt je graag verder via het ticketingsysteem of per e-mail.</p>
            </div>
            <a href="{{ route('contact') }}" class="shrink-0 rounded-xl bg-primary-600 px-6 py-3 text-sm font-semibold text-white hover:bg-primary-500">Contact opnemen</a>
        </div>
    </div>
</section>
@endsection
