@extends('layouts.app')

@section('content')
<section class="relative -mt-16 pt-16 overflow-hidden bg-slate-950 text-white" data-navbar-theme="dark">
    <div class="relative max-w-7xl mx-auto px-6 py-16 lg:py-24">
        <div class="max-w-3xl animate-slide-up">
            <h1 class="font-heading text-3xl md:text-4xl lg:text-5xl font-bold leading-[1.05] tracking-tight">
                @yield('legal-title')
            </h1>
            <p class="mt-4 text-white/70">
                @yield('legal-meta')
            </p>
        </div>
    </div>
</section>

<section class="py-12 lg:py-20 bg-slate-50">
    <div class="max-w-3xl mx-auto px-6">
        <article class="prose prose-slate max-w-none">
            @yield('legal-content')
        </article>

        @hasSection('legal-form')
            <div class="mt-10">
                @yield('legal-form')
            </div>
        @endif
    </div>
</section>
@endsection
