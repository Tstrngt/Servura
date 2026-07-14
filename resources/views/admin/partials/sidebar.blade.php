<aside class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col bg-slate-900 text-slate-200">
    <div class="flex h-16 items-center justify-between px-6 border-b border-slate-700">
        <a href="{{ route('admin.dashboard') }}" class="text-lg font-bold text-white">Servura Admin</a>
        @include('partials.notifications', [
            'bellClass' => 'text-slate-300 hover:text-white',
            'dropdownClass' => 'right-0 mt-2 w-64',
        ])
    </div>
    <nav class="flex-1 px-3 py-6 space-y-2">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
            Dashboard
        </a>
        <a href="{{ route('admin.tickets.index') }}" class="flex items-center rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('admin.tickets.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
            Tickets
        </a>
        <a href="{{ route('admin.customers.index') }}" class="flex items-center rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('admin.customers.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
            Klanten
        </a>
    </nav>
    <div class="border-t border-slate-700 p-4">
        <p class="truncate text-sm font-medium text-white">{{ Auth::user()->name }}</p>
        <p class="mb-3 text-xs text-slate-400">{{ Auth::user()->role === 'admin' ? 'Administrator' : 'Medewerker' }}</p>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full rounded-lg border border-slate-600 px-3 py-2 text-sm font-medium hover:bg-slate-800">Uitloggen</button>
        </form>
    </div>
</aside>
<div class="lg:hidden sticky top-0 z-40 flex h-14 items-center justify-between bg-slate-900 px-4 text-white">
    <a href="{{ route('admin.dashboard') }}" class="font-bold">Servura Admin</a>
    <div class="flex gap-3 text-sm">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.tickets.index') }}">Tickets</a>
        <a href="{{ route('admin.customers.index') }}">Klanten</a>
    </div>
</div>
