@php
$navItems = [
    ['route' => 'customer.dashboard', 'label' => 'Overzicht', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
    ['route' => 'customer.tickets.index', 'label' => 'Mijn aanvragen', 'icon' => 'M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z'],
    ['route' => 'customer.services.index', 'label' => 'Mijn diensten', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
    ['route' => 'customer.financial.index', 'label' => 'Financieel', 'icon' => 'M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.75A.75.75 0 013 4.5h.75m0 0H21m-17.25 0h16.5m0 0v.75A.75.75 0 0021 6h.75m0 0v-.75A.75.75 0 0021 4.5h-.75m1.5 1.5v9m0 0v.75a.75.75 0 01-.75.75h-.75m1.5-1.5H2.25m0 0v.75c0 .414.336.75.75.75h.75m-1.5-1.5v-9m0 0h1.5m16.5 0h1.5m-1.5 0v.75c0 .414.336.75.75.75h.75M3.75 6v.75A.75.75 0 013 7.5h-.75m9.75 6a3 3 0 100-6 3 3 0 000 6z'],

    ['route' => 'customer.profile.edit', 'label' => 'Profiel', 'icon' => 'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.118a7.5 7.5 0 0115 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.5-1.632z'],
];
$unreadNotifications = Auth::user()->notifications()->unread()->limit(6)->get();
@endphp

<!-- Customer Header (sticks as one block at the top) -->
<div class="sticky top-0 z-40" x-data="{ notificationsOpen: false, mobileNavOpen: false }">
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
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <button type="button" @click="notificationsOpen = !notificationsOpen" class="relative inline-flex h-10 w-10 items-center justify-center rounded-xl text-slate-300 transition hover:bg-white/10 hover:text-white" aria-label="Meldingen openen" :aria-expanded="notificationsOpen">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                            @if($unreadNotifications->isNotEmpty())
                                <span class="absolute right-1.5 top-1.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-bold text-white">{{ min($unreadNotifications->count(), 9) }}</span>
                            @endif
                        </button>
                        <div x-show="notificationsOpen" x-cloak @click.outside="notificationsOpen = false" class="absolute right-0 mt-3 w-[min(22rem,calc(100vw-2rem))] overflow-hidden rounded-2xl bg-white text-slate-900 shadow-2xl ring-1 ring-slate-200">
                            <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                                <h2 class="font-heading font-bold">Meldingen</h2>
                                @if($unreadNotifications->isNotEmpty())
                                    <form action="{{ route('notifications.read-all') }}" method="POST">@csrf<button class="text-xs font-semibold text-primary-600 hover:text-primary-800">Alles gelezen</button></form>
                                @endif
                            </div>
                            <div class="max-h-80 overflow-y-auto">
                                @forelse($unreadNotifications as $notification)
                                    <form action="{{ route('notifications.read', $notification) }}" method="POST" class="border-b border-slate-100 last:border-0">
                                        @csrf
                                        <button class="w-full px-4 py-3 text-left transition hover:bg-slate-50">
                                            <span class="block text-sm font-semibold text-slate-900">{{ $notification->title }}</span>
                                            <span class="mt-1 block text-xs leading-relaxed text-slate-500">{{ $notification->message }}</span>
                                            <span class="mt-2 block text-[11px] text-slate-400">{{ $notification->created_at->diffForHumans() }}</span>
                                        </button>
                                    </form>
                                @empty
                                    <div class="px-5 py-10 text-center text-sm text-slate-500">Je hebt geen nieuwe meldingen.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('customer.profile.edit') }}" class="hidden md:flex h-9 w-9 items-center justify-center overflow-hidden rounded-xl bg-primary-600 text-sm font-bold text-white ring-1 ring-white/10" aria-label="Profiel openen">
                        @if(Auth::user()->profile_logo_path)
                            <img src="{{ Storage::url(Auth::user()->profile_logo_path) }}" alt="" class="h-full w-full object-cover">
                        @else
                            {{ strtoupper(substr(Auth::user()->company ?: Auth::user()->name, 0, 1)) }}
                        @endif
                    </a>
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
            <nav class="hidden items-center gap-1 -mb-px sm:flex" aria-label="Klantportaal navigatie">
                @foreach($navItems as $item)
                    @php
                        $isActive = request()->routeIs($item['route'], $item['route'] . '*')
                            || ($item['route'] === 'customer.financial.index' && request()->routeIs('customer.invoices.*', 'customer.quotes.*'));
                    @endphp
                    <a href="{{ route($item['route']) }}" class="group flex items-center gap-2 whitespace-nowrap border-b-2 px-3 py-4 text-sm font-medium transition-colors lg:px-4 {{ $isActive ? 'border-primary-500 text-primary-700' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' }}">
                        <svg class="h-5 w-5 {{ $isActive ? 'text-primary-500' : 'text-slate-400 group-hover:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/></svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="py-2 sm:hidden">
                <button type="button" @click="mobileNavOpen = !mobileNavOpen" class="flex w-full items-center justify-between rounded-xl px-3 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50" :aria-expanded="mobileNavOpen">
                    <span>Klantportaal navigatie</span>
                    <svg class="h-5 w-5 transition-transform" :class="mobileNavOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/></svg>
                </button>
                <nav x-show="mobileNavOpen" x-cloak class="grid grid-cols-2 gap-2 pb-3 pt-2" aria-label="Mobiele klantportaal navigatie">
                    @foreach($navItems as $item)
                        @php
                            $isActive = request()->routeIs($item['route'], $item['route'] . '*')
                                || ($item['route'] === 'customer.financial.index' && request()->routeIs('customer.invoices.*', 'customer.quotes.*'));
                        @endphp
                        <a href="{{ route($item['route']) }}" class="flex items-center gap-2 rounded-xl px-3 py-3 text-sm font-medium {{ $isActive ? 'bg-primary-50 text-primary-700 ring-1 ring-primary-100' : 'bg-slate-50 text-slate-600' }}">
                            <svg class="h-5 w-5 {{ $isActive ? 'text-primary-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/></svg>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>
    </div>
</div>
