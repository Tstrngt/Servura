@extends('layouts.app')

@section('title', 'Wachtwoord Resetten - Servura')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-primary-100">
                <svg class="h-8 w-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Nieuw Wachtwoord Instellen
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Kies een nieuw wachtwoord voor uw account.
            </p>
        </div>

        <form class="mt-8 space-y-6" action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $request->token }}">

            <div class="space-y-4">
                <div>
                    <label for="email" class="form-label">E-mailadres</label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        autocomplete="email" 
                        required 
                        class="form-input"
                        value="{{ $request->email ?? old('email') }}"
                        placeholder="uw@email.nl"
                    >
                    @error('email')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password" class="form-label">Nieuw Wachtwoord</label>
                    <input 
                        id="password" 
                        name="password" 
                        type="password" 
                        autocomplete="new-password" 
                        required 
                        class="form-input"
                        placeholder="Minimaal 8 tekens"
                    >
                    @error('password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        Moet minimaal 8 tekens bevatten
                    </p>
                </div>

                <div>
                    <label for="password_confirmation" class="form-label">Bevestig Wachtwoord</label>
                    <input 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        type="password" 
                        autocomplete="new-password" 
                        required 
                        class="form-input"
                        placeholder="Herhaal uw wachtwoord"
                    >
                    @error('password_confirmation')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div>
                <button 
                    type="submit" 
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200"
                >
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-primary-500 group-hover:text-primary-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    Wachtwoord Resetten
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500">
                    Terug naar login
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
