@extends('layouts.app')

@section('title', 'TransIP-instellingen - Servura Admin')

@section('content')
@include('admin.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="mx-auto w-full max-w-[1600px] px-4 py-4 sm:px-6 lg:px-8">
        <div class="py-4">
            <h1 class="text-2xl font-bold text-gray-900">Integraties</h1>
            <p class="mt-1 text-sm text-gray-600">TransIP-API configureren.</p>
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

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[minmax(0,1fr)_380px]">
            <form action="{{ route('admin.settings.transip.update') }}" method="POST" class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                @csrf
                @method('PUT')

                <h2 class="text-lg font-semibold text-slate-900">TransIP API</h2>
                <p class="mt-1 text-sm text-slate-600">Configureer de API-toegang voor het lezen van domeinbeschikbaarheid en TLD-prijzen.</p>

                <div class="mt-6 space-y-5">
                    <label class="flex items-start gap-3 rounded-xl bg-slate-50 p-4 ring-1 ring-slate-200">
                        <input type="checkbox" name="transip_enabled" value="1" {{ old('transip_enabled', $settings['enabled']) ? 'checked' : '' }} class="mt-1 rounded border-slate-300 text-primary-600">
                        <span><strong class="block text-sm text-slate-900">API actief</strong><span class="mt-1 block text-sm text-slate-600">Schakel TransIP-koppeling in voor de publieke domeinchecker.</span></span>
                    </label>

                    <div class="grid grid-cols-1 gap-x-5 sm:grid-cols-2">
                        <div class="form-group">
                            <label class="form-label" for="transip_username">TransIP gebruikersnaam</label>
                            <input class="form-input" id="transip_username" name="transip_username" type="text" value="{{ old('transip_username', $settings['username']) }}" placeholder="jouwaccount">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="transip_whitelist_only">Whitelist-only token</label>
                            <select class="form-input" id="transip_whitelist_only" name="transip_whitelist_only">
                                <option value="0" {{ old('transip_whitelist_only', $settings['whitelist_only']) ? '' : 'selected' }}>Nee</option>
                                <option value="1" {{ old('transip_whitelist_only', $settings['whitelist_only']) ? 'selected' : '' }}>Ja</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="transip_private_key">Private Key</label>
                        <textarea class="form-input font-mono" id="transip_private_key" name="transip_private_key" rows="6" placeholder="{{ $settings['has_private_key'] ? 'Opgeslagen — vul in om te vervangen' : 'Plak hier je private key' }}">{{ $settings['has_private_key'] ? '' : old('transip_private_key', '') }}</textarea>
                        <p class="mt-1 text-xs text-slate-500">Wordt versleuteld opgeslagen en nooit volledig getoond.</p>
                    </div>

                    <div class="form-group sm:col-span-2">
                        <label class="form-label" for="transip_default_nameservers">Standaard nameservers (gescheiden door komma)</label>
                        <input class="form-input font-mono" id="transip_default_nameservers" name="transip_default_nameservers" type="text" value="{{ old('transip_default_nameservers', $settings['default_nameservers']) }}" placeholder="ns1.servura.nl, ns2.servura.nl">
                        <p class="mt-1 text-xs text-slate-500">Laat leeg om de TransIP-default nameservers te gebruiken.</p>
                    </div>

                    <div class="form-group sm:col-span-2">
                        <label class="form-label" for="transip_test_domains">Dummy / test domeinen (één per regel of komma)</label>
                        <textarea class="form-input font-mono" id="transip_test_domains" name="transip_test_domains" rows="3" placeholder="voorbeeld-servura-test.nl, testdomein.be">{{ old('transip_test_domains', $settings['test_domains']) }}</textarea>
                        <p class="mt-1 text-xs text-slate-500">Deze domeinen worden altijd als beschikbaar getoond en bij bestelling niet écht bij TransIP geregistreerd. Handig voor testen.</p>
                    </div>
                </div>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <button type="submit" class="btn btn-primary">Instellingen opslaan</button>
                    <a href="{{ route('admin.settings.transip.test') }}" class="btn btn-outline text-center">Verbinding testen</a>
                </div>
            </form>

            <div class="h-fit rounded-2xl bg-slate-900 p-6 text-white shadow-xl">
                <h3 class="text-lg font-semibold">Status</h3>
                <div class="mt-4 space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Ingesteld</span>
                        <span class="font-medium">{{ $settings['has_private_key'] ? 'Ja' : 'Nee' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Actief</span>
                        <span class="font-medium">{{ $settings['enabled'] ? 'Ja' : 'Nee' }}</span>
                    </div>
                    <div class="border-t border-white/10 pt-3">
                        <span class="text-slate-400">Laatste check</span>
                        <p class="mt-1 font-medium">{{ $settings['last_status'] }}</p>
                        @if($settings['last_checked_at'])
                            <p class="mt-1 text-xs text-slate-500">{{ $settings['last_checked_at'] }}</p>
                        @endif
                    </div>
                </div>
                <div class="mt-6 rounded-xl bg-white/5 p-4 text-xs text-slate-300 ring-1 ring-white/10">
                    <p>De koppeling gebruikt nu read-only domeinchecks en TransIP-registratie na bevestigde betaling. Transfers en DNS-wijzigingen volgen in een latere fase.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
