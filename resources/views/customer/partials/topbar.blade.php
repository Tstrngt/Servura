@php
$navItems = [
    ['route' => 'customer.dashboard', 'label' => 'Overzicht', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
    ['route' => 'customer.tickets.index', 'label' => 'Mijn aanvragen', 'icon' => 'M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z'],
    ['route' => 'customer.services.index', 'label' => 'Mijn diensten', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
    ['route' => 'customer.quotes.index', 'label' => 'Offertes', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
    ['route' => 'customer.invoices.index', 'label' => 'Facturen', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
];
@endphp

<!-- Customer Header (sticks as one block at the top) -->
<div class="sticky top-0 z-40">
    <!-- Customer Top Bar -->
    <div class="bg-slate-900 border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Left: Logo + label -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" class="flex items-center group" aria-label="Servura home">
                        <span class="logo-text text-xl font-extrabold text-white logo-mark group-hover:opacity-80 transition-opacity">Servura</span>
                        <span class="logo-dot ml-1 text-xl font-logo font-bold text-primary-500 animate-pulse-soft">.</span>
                    </a>
                    <span class="hidden sm:inline h-4 w-px bg-slate-700"></span>
                    <span class="hidden sm:inline text-sm font-medium text-slate-300">Klantportaal</span>
                </div>

                <!-- Right: Avatar + logout -->
                <div class="flex items-center gap-4">
                    <span class="hidden md:flex items-center justify-center h-9 w-9 rounded-full bg-primary-600 text-white text-sm font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Uitloggen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Sub Navigation -->
    <div class="bg-white border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center gap-1 -mb-px overflow-x-auto no-scrollbar" aria-label="Klantportaal navigatie">
                @foreach($navItems as $item)
                    @php $isActive = request()->routeIs($item['route'] . ($item['route'] === 'customer.tickets.index' ? '' : ''), $item['route'] . '*'); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="group flex items-center gap-2 whitespace-nowrap px-4 py-4 text-sm font-medium border-b-2 transition-colors {{ $isActive ? 'border-primary-500 text-primary-700' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                        <svg class="h-5 w-5 {{ $isActive ? 'text-primary-500' : 'text-slate-400 group-hover:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>
        </div>
    </div>
</div>
