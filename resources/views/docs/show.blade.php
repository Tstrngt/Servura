@extends('layouts.docs')

@section('title', $doc['title'] . ' - ' . $categoryMeta['title'] . ' - Servura Docs')
@section('meta-description', $doc['description'] ?: $doc['title'])

@section('content')
@php use App\Support\Docs; @endphp

<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8" x-data="{ navOpen: false }">
    <!-- Breadcrumb -->
    <nav class="mb-8 flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ Docs::url() ?: '/' }}" class="hover:text-primary-600">Documentatie</a>
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
        <span class="text-slate-700">{{ $categoryMeta['title'] }}</span>
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
        <span class="font-medium text-slate-900">{{ $doc['title'] }}</span>
    </nav>

    <div class="grid grid-cols-1 gap-10 lg:grid-cols-12">
        <!-- Sidebar -->
        <aside class="lg:col-span-3">
            <button type="button" @click="navOpen = !navOpen" class="mb-4 flex w-full items-center justify-between rounded-xl bg-white px-4 py-3 text-sm font-semibold text-slate-700 ring-1 ring-slate-200 lg:hidden">
                Navigatie
                <svg class="h-4 w-4 transition-transform" :class="navOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </button>
            <nav class="space-y-6 lg:sticky lg:top-24" :class="navOpen ? '' : 'hidden lg:block'">
                @foreach($categories as $cat)
                    <div>
                        <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider {{ $cat['slug'] === $category ? 'text-primary-600' : 'text-slate-400' }}">{{ $cat['title'] }}</p>
                        <ul class="space-y-0.5">
                            @foreach($cat['pages'] as $p)
                                <li>
                                    <a href="{{ Docs::url($cat['slug'], $p['slug']) }}"
                                       class="block rounded-lg px-3 py-2 text-sm {{ $cat['slug'] === $category && $p['slug'] === $page ? 'bg-primary-50 font-semibold text-primary-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                        {{ $p['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </nav>
        </aside>

        <!-- Article -->
        <article class="min-w-0 lg:col-span-6">
            <h1 class="font-heading text-3xl font-bold text-slate-900 sm:text-4xl">{{ $doc['title'] }}</h1>
            @if($doc['description'])
                <p class="mt-3 text-lg text-slate-500">{{ $doc['description'] }}</p>
            @endif
            <div class="prose prose-slate mt-8 max-w-none prose-headings:font-heading prose-a:text-primary-600 prose-a:no-underline hover:prose-a:underline prose-pre:bg-slate-900 prose-pre:text-slate-100 prose-code:text-primary-700">
                {!! $doc['html'] !!}
            </div>

            <!-- Prev / next within category -->
            @php
                $slugs = collect($categoryMeta['pages'])->pluck('slug')->all();
                $pos = array_search($page, $slugs, true);
                $prev = $pos !== false && $pos > 0 ? $categoryMeta['pages'][$pos - 1] : null;
                $next = $pos !== false && $pos < count($slugs) - 1 ? $categoryMeta['pages'][$pos + 1] : null;
            @endphp
            @if($prev || $next)
                <div class="mt-14 grid grid-cols-1 gap-4 border-t border-slate-200 pt-8 sm:grid-cols-2">
                    @if($prev)
                        <a href="{{ Docs::url($category, $prev['slug']) }}" class="rounded-xl bg-white p-4 ring-1 ring-slate-200 transition hover:ring-primary-300">
                            <span class="text-xs font-medium uppercase tracking-wider text-slate-400">Vorige</span>
                            <span class="mt-1 block text-sm font-semibold text-slate-900">&larr; {{ $prev['title'] }}</span>
                        </a>
                    @else
                        <span></span>
                    @endif
                    @if($next)
                        <a href="{{ Docs::url($category, $next['slug']) }}" class="rounded-xl bg-white p-4 text-right ring-1 ring-slate-200 transition hover:ring-primary-300">
                            <span class="text-xs font-medium uppercase tracking-wider text-slate-400">Volgende</span>
                            <span class="mt-1 block text-sm font-semibold text-slate-900">{{ $next['title'] }} &rarr;</span>
                        </a>
                    @endif
                </div>
            @endif
        </article>

        <!-- Table of contents -->
        @if(count($doc['toc']))
            <aside class="hidden lg:col-span-3 lg:block">
                <div class="sticky top-24">
                    <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-slate-400">Op deze pagina</p>
                    <ul class="space-y-2 border-l border-slate-200">
                        @foreach($doc['toc'] as $item)
                            <li>
                                <a href="#{{ $item['id'] }}" class="-ml-px block border-l-2 border-transparent py-0.5 text-sm text-slate-500 hover:border-primary-500 hover:text-primary-600 {{ $item['level'] === 3 ? 'pl-7' : 'pl-4' }}">
                                    {{ $item['title'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </aside>
        @endif
    </div>
</div>
@endsection
