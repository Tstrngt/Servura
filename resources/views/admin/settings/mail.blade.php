@extends('layouts.app')

@section('title', 'Mailinstellingen - Servura Admin')

@section('content')
@include('admin.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="mx-auto w-full max-w-[1600px] px-4 py-4 sm:px-6 lg:px-8">
        <div class="py-4">
            <h1 class="text-2xl font-bold text-gray-900">Instellingen</h1>
            <p class="mt-1 text-sm text-gray-600">Systeem- en integratie-instellingen beheren.</p>
        </div>

        @include('admin.partials.settings-nav')

        @if(session('success'))
            <div class="mb-4"><div class="rounded-md bg-green-50 p-4"><p class="text-sm text-green-700">{{ session('success') }}</p></div></div>
        @endif
        @if(session('error'))
            <div class="mb-4"><div class="rounded-md bg-red-50 p-4"><p class="text-sm text-red-700">{{ session('error') }}</p></div></div>
        @endif
        @if($errors->any())
            <div class="mb-4"><div class="rounded-md bg-red-50 p-4">
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div></div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[minmax(0,1fr)_400px]">
            <form action="{{ route('admin.settings.mail.update') }}" method="POST" class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                @csrf
                @method('PUT')
                <h2 class="text-lg font-semibold text-slate-900">Mailverzending</h2>
                <p class="mt-1 text-sm text-slate-600">Deze instellingen overschrijven de <code class="text-xs">.env</code>-waarden. Het wachtwoord wordt versleuteld opgeslagen.</p>

                <div class="mt-6 grid grid-cols-1 gap-x-5 sm:grid-cols-2">
                    <div class="form-group">
                        <label class="form-label" for="mail_mailer">Mailer</label>
                        <select class="form-input" id="mail_mailer" name="mail_mailer">
                            <option value="smtp" {{ old('mail_mailer', $settings['mail_mailer']) === 'smtp' ? 'selected' : '' }}>SMTP</option>
                            <option value="log" {{ old('mail_mailer', $settings['mail_mailer']) === 'log' ? 'selected' : '' }}>Log (alleen testen)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="mail_encryption">Encryptie</label>
                        <select class="form-input" id="mail_encryption" name="mail_encryption">
                            <option value="tls" {{ old('mail_encryption', $settings['mail_encryption']) === 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ old('mail_encryption', $settings['mail_encryption']) === 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="none" {{ old('mail_encryption', $settings['mail_encryption']) === 'none' ? 'selected' : '' }}>Geen</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="mail_host">SMTP-host</label>
                        <input class="form-input" id="mail_host" name="mail_host" type="text" value="{{ old('mail_host', $settings['mail_host']) }}" placeholder="smtp.jouwprovider.nl">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="mail_port">Poort</label>
                        <input class="form-input" id="mail_port" name="mail_port" type="number" min="1" max="65535" value="{{ old('mail_port', $settings['mail_port']) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="mail_username">Gebruikersnaam</label>
                        <input class="form-input" id="mail_username" name="mail_username" type="text" autocomplete="off" value="{{ old('mail_username', $settings['mail_username']) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="mail_password">Wachtwoord</label>
                        <input class="form-input" id="mail_password" name="mail_password" type="password" autocomplete="new-password" placeholder="{{ $settings['mail_password_set'] ? '••••••••' : '' }}">
                        <p class="mt-1 text-xs text-slate-500">{{ $settings['mail_password_set'] ? 'Er is een wachtwoord opgeslagen. Laat leeg om ongewijzigd te laten.' : 'Nog geen wachtwoord opgeslagen.' }}</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="mail_from_address">Afzenderadres</label>
                        <input class="form-input" id="mail_from_address" name="mail_from_address" type="email" value="{{ old('mail_from_address', $settings['mail_from_address']) }}" placeholder="no-reply@servura.nl">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="mail_from_name">Afzender naam</label>
                        <input class="form-input" id="mail_from_name" name="mail_from_name" type="text" value="{{ old('mail_from_name', $settings['mail_from_name']) }}" placeholder="Servura">
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="btn btn-primary">Instellingen opslaan</button>
                </div>
            </form>

            <aside class="h-fit space-y-6">
                <div class="rounded-2xl bg-slate-900 p-6 text-white shadow-xl">
                    <h2 class="text-lg font-semibold">Testmail versturen</h2>
                    <p class="mt-4 text-sm leading-relaxed text-slate-300">Sla eerst de instellingen op en verstuur daarna een testmail naar je eigen e-mailadres om te controleren dat alles werkt.</p>
                    <form action="{{ route('admin.settings.mail.test') }}" method="POST" class="mt-5">
                        @csrf
                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-white/10 px-4 py-2.5 text-sm font-semibold text-white ring-1 ring-white/15 hover:bg-white/15">Testmail versturen</button>
                    </form>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection
