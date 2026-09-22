<aside class="hidden lg:fixed lg:top-0 lg:bottom-0 lg:left-0 lg:flex lg:w-64 lg:flex-col bg-white border-r border-slate-200 z-30">
    <div class="flex h-16 items-center px-6 border-b border-slate-200/70">
        <a href="{{ route('home') }}" class="flex items-center group" aria-label="Servura home">
            <span class="logo-text text-2xl font-extrabold logo-mark group-hover:opacity-80 transition-opacity">Servura</span>
            <span class="logo-dot ml-1 text-2xl font-logo font-bold text-primary-600 animate-pulse-soft">.</span>
        </a>
    </div>
    <div class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
        <a href="{{ route('customer.dashboard') }}" class="group flex items-center px-4 py-3 text-sm font-medium border-l-4 {{ request()->routeIs('customer.dashboard') ? 'border-primary-500 bg-primary-50 text-primary-700' : 'border-transparent text-slate-700 hover:bg-slate-50 hover:text-slate-900' }} rounded-r-lg">
            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('customer.dashboard') ? 'text-primary-500' : 'text-slate-400 group-hover:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Dashboard
        </a>
        <a href="{{ route('customer.tickets.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium border-l-4 {{ request()->routeIs('customer.tickets.*') ? 'border-primary-500 bg-primary-50 text-primary-700' : 'border-transparent text-slate-700 hover:bg-slate-50 hover:text-slate-900' }} rounded-r-lg">
            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('customer.tickets.*') ? 'text-primary-500' : 'text-slate-400 group-hover:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
            </svg>
            Mijn aanvragen
        </a>
        <a href="{{ route('customer.services.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium border-l-4 {{ request()->routeIs('customer.services.index') ? 'border-primary-500 bg-primary-50 text-primary-700' : 'border-transparent text-slate-700 hover:bg-slate-50 hover:text-slate-900' }} rounded-r-lg">
            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('customer.services.index') ? 'text-primary-500' : 'text-slate-400 group-hover:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            Mijn Diensten
        </a>
        <a href="{{ route('customer.quotes.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium border-l-4 {{ request()->routeIs('customer.quotes.*') ? 'border-primary-500 bg-primary-50 text-primary-700' : 'border-transparent text-slate-700 hover:bg-slate-50 hover:text-slate-900' }} rounded-r-lg">
            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('customer.quotes.*') ? 'text-primary-500' : 'text-slate-400 group-hover:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            Offertes
        </a>
        <a href="{{ route('customer.invoices.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium border-l-4 {{ request()->routeIs('customer.invoices.*') ? 'border-primary-500 bg-primary-50 text-primary-700' : 'border-transparent text-slate-700 hover:bg-slate-50 hover:text-slate-900' }} rounded-r-lg">
            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('customer.invoices.*') ? 'text-primary-500' : 'text-slate-400 group-hover:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Facturen
        </a>
    </div>
    <div class="border-t border-slate-200 p-4">
        <p class="truncate text-sm font-medium text-slate-900">{{ Auth::user()->name }}</p>
        <p class="mb-3 text-xs text-slate-500">{{ Auth::user()->email }}</p>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">Uitloggen</button>
        </form>
    </div>
</aside>
