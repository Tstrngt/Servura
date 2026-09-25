@extends('layouts.app')

@section('title', 'Inloggen - Servura')

@php
    $portalAreas = [
        ['label' => 'Mijn diensten', 'text' => 'Websites, hosting en domeinen, met één klik naar DirectAdmin.', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
        ['label' => 'Financieel', 'text' => 'Facturen bekijken, downloaden en direct online betalen.', 'icon' => 'M9 14l2 2 4-4m5 8H4a1 1 0 01-1-1V5a1 1 0 011-1h16a1 1 0 011 1v14a1 1 0 01-1 1zM3 9h18'],
        ['label' => 'Offertes', 'text' => 'Voorstellen doorlezen en akkoord geven wanneer het u uitkomt.', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
        ['label' => 'Mijn aanvragen', 'text' => 'Wijzigingen doorgeven en de voortgang volgen.', 'icon' => 'M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z'],
    ];

    $notice = session('status') ?? session('success');
    $emailError = $errors->first('email');
    $passwordError = $errors->first('password');
@endphp

@section('content')
<div class="login-shell grid min-h-[calc(100dvh-4rem)] bg-white lg:grid-cols-[minmax(0,5fr)_minmax(0,6fr)]">

    {{-- Portal panel: bridges into the dark klantportaal. Content starts on the
         navbar container's left edge; the group is centered like the form and
         the help line sits on the bottom edge. --}}
    <aside class="login-panel relative hidden overflow-hidden bg-slate-950 text-white lg:flex lg:flex-col lg:justify-center lg:py-14 lg:pr-12 xl:pr-16" aria-label="Wat u in het klantportaal vindt">
        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <div class="login-glow absolute -left-40 -top-40 h-[32rem] w-[32rem] rounded-full bg-primary-600/25 blur-3xl"></div>
            <div class="login-glow login-glow-late absolute -bottom-48 right-[-10rem] h-[28rem] w-[28rem] rounded-full bg-primary-900/60 blur-3xl"></div>
            <div class="login-grid absolute inset-0 opacity-[0.07] [background-image:linear-gradient(to_right,white_1px,transparent_1px),linear-gradient(to_bottom,white_1px,transparent_1px)] [background-size:3rem_3rem] [mask-image:linear-gradient(to_bottom,black,transparent_75%)]"></div>
        </div>

        <div class="relative max-w-md">
            <p class="text-sm font-semibold text-primary-300">Klantportaal</p>
            <p class="mt-4 text-balance font-heading text-4xl font-bold leading-[1.1] tracking-tight xl:text-5xl">
                Alles van uw website op één plek.
            </p>

            <ul class="mt-8 space-y-5 xl:mt-10 xl:space-y-6" role="list">
                @foreach($portalAreas as $area)
                    <li class="flex gap-4">
                        <span class="login-tile mt-0.5 flex h-10 w-10 flex-none items-center justify-center rounded-xl bg-white/[0.06] text-primary-300 ring-1 ring-inset ring-white/10">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $area['icon'] }}" />
                            </svg>
                        </span>
                        <div>
                            <p class="font-heading text-base font-semibold text-white">{{ $area['label'] }}</p>
                            <p class="mt-1 text-sm leading-relaxed text-slate-300">{{ $area['text'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <p class="relative mt-10 text-sm text-slate-400">
            Hulp nodig bij inloggen?
            <a href="{{ route('contact') }}" class="font-semibold text-white underline decoration-white/30 underline-offset-4 transition-colors hover:decoration-white focus:outline-none focus-visible:rounded focus-visible:ring-2 focus-visible:ring-primary-300">Neem contact op</a>
        </p>
    </aside>

    {{-- Form --}}
    <section class="flex items-start justify-center px-4 pb-14 pt-10 sm:px-6 sm:pt-16 lg:items-center lg:px-12 lg:py-14">
        <div class="w-full max-w-md">
            <p class="text-sm font-semibold text-primary-700 lg:hidden">Klantportaal</p>
            <h1 class="mt-2 font-heading text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl lg:mt-0">Inloggen</h1>
            <p class="mt-3 text-base text-slate-600">Welkom terug. Log in met het e-mailadres van uw Servura-account.</p>

            @if($notice)
                <div class="login-notice mt-8 flex gap-3 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-800 ring-1 ring-inset ring-emerald-200" role="status">
                    <svg class="mt-0.5 h-5 w-5 flex-none text-emerald-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <p>{{ $notice }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="login-notice mt-8 flex gap-3 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-800 ring-1 ring-inset ring-red-200" role="alert">
                    <svg class="mt-0.5 h-5 w-5 flex-none text-red-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <form
                @class(['login-form mt-8 flex flex-col gap-5', 'is-rejected' => $emailError || $passwordError])
                action="{{ route('login') }}"
                method="POST"
                x-data="{ submitting: false, showPassword: false, capsLock: false }"
                @submit="submitting = true"
                @pageshow.window="if ($event.persisted) submitting = false"
            >
                @csrf

                <div>
                    <label for="email" class="form-label">E-mailadres</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        inputmode="email"
                        autocomplete="username"
                        autocapitalize="none"
                        spellcheck="false"
                        required
                        @if(! old('email') || $emailError) autofocus @endif
                        value="{{ old('email') }}"
                        placeholder="naam@bedrijf.nl"
                        class="form-input"
                        @if($emailError) aria-invalid="true" aria-describedby="email-error" @endif
                        @input="$el.removeAttribute('aria-invalid')"
                    >
                    @if($emailError)
                        <p id="email-error" class="form-error">{{ $emailError }}</p>
                    @endif
                </div>

                {{-- The "forgot" link sits visually in the label row but comes last
                     in the DOM, so Tab goes straight from e-mail to password. --}}
                <div class="grid grid-cols-[minmax(0,1fr)_auto] items-baseline gap-x-4">
                    <label for="password" class="form-label col-start-1 row-start-1">Wachtwoord</label>
                    <div class="relative col-span-2 row-start-2">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            :type="showPassword ? 'text' : 'password'"
                            x-ref="pw"
                            autocomplete="current-password"
                            required
                            @if(old('email') && ! $emailError) autofocus @endif
                            class="form-input !pr-12"
                            @if($passwordError) aria-invalid="true" aria-describedby="password-error" @endif
                            @input="$el.removeAttribute('aria-invalid')"
                            @keydown="capsLock = !!$event.getModifierState?.('CapsLock')"
                            @keyup="capsLock = !!$event.getModifierState?.('CapsLock')"
                            @blur="capsLock = false"
                        >
                        <button
                            type="button"
                            x-cloak
                            @click="showPassword = !showPassword; $nextTick(() => $refs.pw.focus())"
                            class="login-eye absolute inset-y-0 right-0 grid w-12 place-items-center rounded-r-xl text-slate-500 transition-colors hover:text-slate-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-primary-500"
                            :class="showPassword && 'is-open'"
                            :aria-label="showPassword ? 'Wachtwoord verbergen' : 'Wachtwoord tonen'"
                            :aria-pressed="showPassword.toString()"
                        >
                            <svg class="login-eye-shown h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg class="login-eye-hidden h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                    <div class="col-span-2" aria-live="polite">
                        <p x-show="capsLock" x-cloak class="mt-2 flex items-center gap-1.5 text-sm font-medium text-amber-700">
                            <svg class="h-4 w-4 flex-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4l7 8h-4v4H9v-4H5l7-8zM9 20h6" />
                            </svg>
                            Caps Lock staat aan
                        </p>
                    </div>
                    @if($passwordError)
                        <p id="password-error" class="form-error col-span-2">{{ $passwordError }}</p>
                    @endif
                    <a href="{{ route('password.request') }}" class="col-start-2 row-start-1 mb-2 text-sm font-semibold text-primary-700 transition-colors hover:text-primary-900 focus:outline-none focus-visible:rounded focus-visible:ring-2 focus-visible:ring-primary-500">
                        Wachtwoord vergeten?
                    </a>
                </div>

                <label for="remember-me" class="-mt-2 flex w-fit cursor-pointer items-center gap-3 py-1 text-sm text-slate-700">
                    <input
                        id="remember-me"
                        name="remember"
                        type="checkbox"
                        value="1"
                        @checked(old('remember'))
                        class="h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500"
                    >
                    Ingelogd blijven op dit apparaat
                </label>

                <button
                    type="submit"
                    class="mt-3 flex w-full items-center justify-center gap-2 rounded-xl bg-primary-600 px-4 py-3.5 text-base font-semibold text-white shadow-md shadow-primary-900/10 transition duration-150 hover:bg-primary-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 active:scale-[0.99] active:bg-primary-800 disabled:cursor-wait disabled:opacity-80"
                    :disabled="submitting"
                >
                    <svg
                        x-show="submitting"
                        x-cloak
                        x-transition:enter="transition duration-200 ease-out"
                        x-transition:enter-start="opacity-0 scale-50"
                        x-transition:enter-end="opacity-100 scale-100"
                        class="h-5 w-5 animate-spin motion-reduce:animate-none" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                        <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8v3a5 5 0 00-5 5H4z"></path>
                    </svg>
                    <span x-text="submitting ? 'Bezig met inloggen…' : 'Inloggen'">Inloggen</span>
                </button>
            </form>

            <p class="mt-10 border-t border-slate-200 pt-6 text-sm text-slate-600">
                Nog geen klant?
                <a href="{{ route('contact') }}" class="font-semibold text-primary-700 transition-colors hover:text-primary-900 focus:outline-none focus-visible:rounded focus-visible:ring-2 focus-visible:ring-primary-500">Neem contact op voor een account</a>
            </p>
        </div>
    </section>
</div>
@endsection
