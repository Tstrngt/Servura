<aside class="hidden lg:fixed lg:top-0 lg:bottom-0 lg:left-0 lg:flex lg:w-64 lg:flex-col bg-slate-900 border-r border-slate-800 z-40">
    <div class="flex h-16 items-center px-6 border-b border-slate-800">
        <a href="{{ route('home') }}" class="flex items-center group" aria-label="Servura home">
            <span class="logo-text text-2xl font-extrabold text-white logo-mark group-hover:opacity-80 transition-opacity">Servura</span>
            <span class="logo-dot ml-1 text-2xl font-logo font-bold text-primary-500 animate-pulse-soft">.</span>
        </a>
    </div>
    <div class="flex-1 flex flex-col justify-end p-4">
        <div class="rounded-xl bg-slate-800/50 p-4 border border-slate-700">
            <p class="truncate text-sm font-medium text-white">{{ Auth::user()->name }}</p>
            <p class="truncate text-xs text-slate-400 mb-3">{{ Auth::user()->email }}</p>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-600 px-3 py-2 text-sm font-medium text-slate-200 transition-colors">Uitloggen</button>
            </form>
        </div>
    </div>
</aside>
