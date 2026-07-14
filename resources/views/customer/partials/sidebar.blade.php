<aside class="hidden lg:fixed lg:top-16 lg:bottom-0 lg:left-0 lg:flex lg:w-64 lg:flex-col bg-slate-900 text-slate-200 z-30">
    <div class="flex-1 px-3 py-6 space-y-2">
        <a href="{{ route('customer.dashboard') }}" class="flex items-center rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('customer.dashboard') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
            </svg>
            Dashboard
        </a>
        <a href="{{ route('customer.tickets.index') }}" class="flex items-center rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('customer.tickets.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
            </svg>
            Support Tickets
        </a>
        <a href="#" class="flex items-center rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('customer.services.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            Mijn Diensten
        </a>
    </div>
    <div class="border-t border-slate-700 p-4">
        <p class="truncate text-sm font-medium text-white">{{ Auth::user()->name }}</p>
        <p class="mb-3 text-xs text-slate-400">{{ Auth::user()->email }}</p>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full rounded-lg border border-slate-600 px-3 py-2 text-sm font-medium hover:bg-slate-800">Uitloggen</button>
        </form>
    </div>
</aside>
