<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#020617">
    <title>{{ $code }} - {{ $title }} | Servura</title>
    @vite(['resources/css/app.css'])
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
</head>
<body class="min-h-[100dvh] bg-slate-950 text-white antialiased">
    <main class="relative isolate flex min-h-[100dvh] items-center overflow-hidden px-6 py-16">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(37,99,235,0.16),transparent_32%),radial-gradient(circle_at_85%_75%,rgba(14,165,233,0.10),transparent_30%)]"></div>
        <div class="pointer-events-none absolute inset-0 opacity-30 [background-image:linear-gradient(rgba(148,163,184,0.06)_1px,transparent_1px),linear-gradient(90deg,rgba(148,163,184,0.06)_1px,transparent_1px)] [background-size:48px_48px]"></div>

        <div class="relative mx-auto w-full max-w-6xl">
            <a href="{{ route('home') }}" class="inline-flex items-center text-2xl font-extrabold tracking-tight text-white transition-opacity duration-200 hover:opacity-80 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-4 focus:ring-offset-slate-950">
                Servura
            </a>

            <div class="mt-16 grid items-end gap-10 lg:grid-cols-[minmax(0,1fr)_auto] lg:gap-20">
                <div class="max-w-2xl">
                    <p class="font-mono text-sm font-semibold tracking-[0.18em] text-blue-300">FOUTCODE {{ $code }}</p>
                    <h1 class="mt-5 text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">{{ $title }}</h1>
                    <p class="mt-6 max-w-xl text-lg leading-8 text-slate-300">{{ $message }}</p>

                    <div class="mt-10 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('home') }}" class="inline-flex min-h-12 items-center justify-center rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white transition-[background-color,transform] duration-150 hover:bg-blue-500 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-4 focus:ring-offset-slate-950">
                            Naar de homepage
                        </a>
                        <button type="button" onclick="history.length > 1 ? history.back() : window.location.assign(@js(route('home')))" class="inline-flex min-h-12 items-center justify-center rounded-xl border border-slate-700 bg-slate-900/70 px-6 py-3 font-semibold text-slate-100 transition-[border-color,background-color,transform] duration-150 hover:border-slate-500 hover:bg-slate-800 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-4 focus:ring-offset-slate-950">
                            Ga terug
                        </button>
                    </div>
                </div>

                <div class="select-none text-right font-mono text-[clamp(7rem,22vw,16rem)] font-bold leading-none tracking-[-0.09em] text-slate-900 [text-shadow:0_0_1px_rgba(148,163,184,0.55)]" aria-hidden="true">
                    {{ $code }}
                </div>
            </div>

            <div class="mt-16 border-t border-slate-800 pt-6 text-sm text-slate-500">
                Blijft het probleem bestaan? <a href="{{ route('contact') }}" class="font-medium text-slate-300 underline decoration-slate-600 underline-offset-4 hover:text-white">Neem contact met ons op</a>.
            </div>
        </div>
    </main>
</body>
</html>
