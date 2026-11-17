@extends('layouts.docs')

@section('title', $categoryMeta['title'] . ' - Helpcentrum - Servura')
@section('meta-description', $categoryMeta['description'] ?: 'Alle artikelen over ' . $categoryMeta['title'] . ' bij Servura.')

@section('content')
@php use App\Support\Docs; @endphp

<div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
    <nav class="mb-8 flex items-center gap-2 text-sm text-slate-500" aria-label="Kruimelpad">
        <a href="{{ Docs::url() ?: '/' }}" class="hover:text-primary-600">Helpcentrum</a>
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
        <span class="font-medium text-slate-900">{{ $categoryMeta['title'] }}</span>
    </nav>

    <h1 class="font-heading text-3xl font-bold text-slate-900 sm:text-4xl">{{ $categoryMeta['title'] }}</h1>
    @if($categoryMeta['description'])
        <p class="mt-3 text-lg text-slate-500">{{ $categoryMeta['description'] }}</p>
    @endif

    <ul class="mt-8 space-y-3">
        @foreach($categoryMeta['pages'] as $p)
            <li>
                <a href="{{ Docs::url($categoryMeta['slug'], $p['slug']) }}" class="group flex items-start justify-between gap-4 rounded-xl bg-white p-5 ring-1 ring-slate-200/70 transition hover:ring-primary-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500">
                    <div>
                        <span class="flex items-center gap-2 font-semibold text-slate-900 group-hover:text-primary-700">
                            {{ $p['title'] }}
                            @if($p['draft'] ?? false)
                                <span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-800">Concept</span>
                            @endif
                        </span>
                        @if($p['description'])
                            <span class="mt-1 block text-sm text-slate-500">{{ $p['description'] }}</span>
                        @endif
                    </div>
                    <svg class="mt-1 h-5 w-5 shrink-0 text-slate-300 transition group-hover:text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                </a>
            </li>
        @endforeach
    </ul>

    <div class="mt-10">
        <a href="{{ Docs::url() ?: '/' }}" class="inline-flex items-center gap-1 text-sm font-semibold text-primary-600 hover:text-primary-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
            Terug naar het helpcentrum
        </a>
    </div>
</div>
@endsection
