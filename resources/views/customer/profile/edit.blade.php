@extends('layouts.app')

@section('title', 'Mijn profiel - Servura')

@section('content')
@include('customer.partials.topbar')

<div class="min-h-screen bg-slate-50 pt-32">
    <main class="mx-auto max-w-7xl px-4 py-12 pb-24 sm:px-6 lg:px-8">
        <header class="mb-10">
            <h1 class="font-heading text-3xl font-bold text-slate-900">Mijn profiel</h1>
            <p class="mt-2 text-lg text-slate-500">Beheer je contact-, bedrijfs- en inloggegevens.</p>
        </header>

        @if(session('success'))
            <div class="mb-8 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-[minmax(0,1fr)_22rem] lg:items-start">
            <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-8">
                @csrf
                @method('PUT')

                <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70 sm:p-8">
                    <h2 class="font-heading text-xl font-bold text-slate-900">Contactgegevens</h2>
                    <p class="mt-1 text-sm text-slate-500">Deze gegevens gebruiken we in het klantportaal en op documenten.</p>

                    <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label for="name" class="form-label">Naam</label>
                            <input id="name" name="name" value="{{ old('name', $user->name) }}" required class="form-input mt-1 w-full">
                            @error('name') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="phone" class="form-label">Telefoonnummer</label>
                            <input id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input mt-1 w-full">
                            @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label for="email" class="form-label">E-mailadres</label>
                            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required class="form-input mt-1 w-full">
                            <p class="mt-2 text-xs text-slate-500">Bij een wijziging vragen we hieronder om je huidige wachtwoord.</p>
                            @error('email') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label for="current_password" class="form-label">Huidig wachtwoord bij e-mailwijziging</label>
                            <input id="current_password" type="password" name="current_password" autocomplete="current-password" class="form-input mt-1 w-full">
                            @error('current_password') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>

                <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70 sm:p-8">
                    <h2 class="font-heading text-xl font-bold text-slate-900">Bedrijfsgegevens</h2>
                    <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="company" class="form-label">Bedrijfsnaam</label>
                            <input id="company" name="company" value="{{ old('company', $user->company) }}" class="form-input mt-1 w-full">
                        </div>
                        <div>
                            <label for="street" class="form-label">Straat</label>
                            <input id="street" name="street" value="{{ old('street', $user->street) }}" class="form-input mt-1 w-full">
                        </div>
                        <div>
                            <label for="house_number" class="form-label">Huisnummer</label>
                            <input id="house_number" name="house_number" value="{{ old('house_number', $user->house_number) }}" class="form-input mt-1 w-full">
                        </div>
                        <div>
                            <label for="postal_code" class="form-label">Postcode</label>
                            <input id="postal_code" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" class="form-input mt-1 w-full">
                        </div>
                        <div>
                            <label for="city" class="form-label">Plaats</label>
                            <input id="city" name="city" value="{{ old('city', $user->city) }}" class="form-input mt-1 w-full">
                        </div>
                        <div>
                            <label for="country" class="form-label">Land</label>
                            <input id="country" name="country" value="{{ old('country', $user->country) }}" class="form-input mt-1 w-full">
                        </div>
                        <div>
                            <label for="kvk_number" class="form-label">KvK-nummer</label>
                            <input id="kvk_number" name="kvk_number" value="{{ old('kvk_number', $user->kvk_number) }}" class="form-input mt-1 w-full">
                        </div>
                        <div>
                            <label for="vat_number" class="form-label">Btw-nummer</label>
                            <input id="vat_number" name="vat_number" value="{{ old('vat_number', $user->vat_number) }}" class="form-input mt-1 w-full">
                        </div>
                    </div>
                </section>

                <div class="flex justify-end">
                    <button type="submit" class="btn btn-primary">Wijzigingen opslaan</button>
                </div>
            </form>

            <aside class="space-y-8 lg:self-start">
                <section class="rounded-2xl bg-slate-900 p-6 text-white shadow-xl shadow-slate-900/10">
                    <h2 class="font-heading text-lg font-bold">Bedrijfslogo</h2>
                    <div class="mt-5 flex h-24 w-24 items-center justify-center overflow-hidden rounded-2xl bg-white/10 ring-1 ring-white/15">
                        @if($user->profile_logo_path)
                            <img src="{{ Storage::url($user->profile_logo_path) }}" alt="Logo van {{ $user->company ?: $user->name }}" class="h-full w-full object-cover">
                        @else
                            <span class="text-3xl font-bold">{{ strtoupper(substr($user->company ?: $user->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <form action="{{ route('customer.profile.logo') }}" method="POST" enctype="multipart/form-data" class="mt-5">
                        @csrf
                        @method('PUT')
                        <label for="profile_logo" class="block text-sm font-medium text-slate-200">Nieuw logo uploaden</label>
                        <input id="profile_logo" name="profile_logo" type="file" accept=".jpg,.jpeg,.png,.webp" class="mt-2 block w-full text-xs text-slate-300 file:mr-3 file:rounded-lg file:border-0 file:bg-white/10 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-white hover:file:bg-white/15">
                        @error('profile_logo') <p class="mt-2 text-xs text-rose-300">{{ $message }}</p> @enderror
                        <p class="mt-2 text-xs leading-relaxed text-slate-400">PNG, JPG of WebP. Maximaal 2 MB.</p>
                        <div class="mt-4 flex gap-2">
                            <button type="submit" class="flex-1 rounded-lg bg-primary-600 px-3 py-2 text-sm font-semibold text-white hover:bg-primary-500">Logo opslaan</button>
                            @if($user->profile_logo_path)
                                <button type="submit" name="remove_profile_logo" value="1" class="rounded-lg bg-white/10 px-3 py-2 text-sm font-semibold text-white hover:bg-white/15">Verwijderen</button>
                            @endif
                        </div>
                    </form>
                </section>

                <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70">
                    <h2 class="font-heading text-lg font-bold text-slate-900">Wachtwoord wijzigen</h2>
                    <form action="{{ route('customer.profile.password') }}" method="POST" class="mt-5 space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="password_current" class="form-label">Huidig wachtwoord</label>
                            <input id="password_current" type="password" name="current_password" required class="form-input mt-1 w-full">
                        </div>
                        <div>
                            <label for="password" class="form-label">Nieuw wachtwoord</label>
                            <input id="password" type="password" name="password" required class="form-input mt-1 w-full">
                        </div>
                        <div>
                            <label for="password_confirmation" class="form-label">Herhaal nieuw wachtwoord</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required class="form-input mt-1 w-full">
                        </div>
                        <button type="submit" class="btn btn-outline w-full justify-center">Wachtwoord wijzigen</button>
                    </form>
                </section>
            </aside>
        </div>
    </main>
</div>
@endsection
