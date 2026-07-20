<aside class="hidden lg:fixed lg:top-16 lg:bottom-0 lg:left-0 lg:flex lg:w-64 lg:flex-col bg-white border-r border-gray-200 z-30">
    <div class="flex-1 px-4 py-6 space-y-1">
        <a href="{{ route('customer.dashboard') }}" class="group flex items-center px-4 py-3 text-sm font-medium border-l-4 {{ request()->routeIs('customer.dashboard') ? 'border-primary-500 bg-primary-50 text-primary-700' : 'border-transparent text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('customer.dashboard') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
            </svg>
            Dashboard
        </a>
        <a href="{{ route('customer.tickets.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium border-l-4 {{ request()->routeIs('customer.tickets.*') ? 'border-primary-500 bg-primary-50 text-primary-700' : 'border-transparent text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('customer.tickets.*') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
            </svg>
            Support Tickets
        </a>
        <a href="#" class="group flex items-center px-4 py-3 text-sm font-medium border-l-4 {{ request()->routeIs('customer.services.*') ? 'border-primary-500 bg-primary-50 text-primary-700' : 'border-transparent text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('customer.services.*') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            Mijn Diensten
        </a>
        <a href="{{ route('customer.invoices.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium border-l-4 {{ request()->routeIs('customer.invoices.*') ? 'border-primary-500 bg-primary-50 text-primary-700' : 'border-transparent text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('customer.invoices.*') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Facturen
        </a>
    </div>
    <div class="border-t border-gray-200 p-4">
        <p class="truncate text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
        <p class="mb-3 text-xs text-gray-500">{{ Auth::user()->email }}</p>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Uitloggen</button>
        </form>
    </div>
</aside>
