@extends('layouts.app')

@section('title', 'Openprovider-instellingen - Servura Admin')

@section('content')
@include('admin.partials.sidebar')
<div class="min-h-screen bg-gray-50 lg:pl-64">
    <div class="mx-auto w-full max-w-[1600px] px-4 py-4 sm:px-6 lg:px-8">
        <div class="py-4"><h1 class="text-2xl font-bold text-gray-900">Openprovider</h1><p class="mt-1 text-sm text-gray-600">Domeinregistratie, verhuizingen en DNS via Openprovider configureren.</p></div>
        @include('admin.partials.settings-nav')
        @if(session('success'))<div class="mb-4 rounded-xl bg-green-50 p-4 text-sm text-green-700 ring-1 ring-green-200">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="mb-4 rounded-xl bg-red-50 p-4 text-sm text-red-700 ring-1 ring-red-200">{{ session('error') }}</div>@endif
        @if($errors->any())<div class="mb-4 rounded-xl bg-red-50 p-4 text-sm text-red-700 ring-1 ring-red-200"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[minmax(0,1fr)_380px]">
            <form action="{{ route('admin.settings.openprovider.update') }}" method="POST" class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                @csrf @method('PUT')
                <h2 class="text-lg font-semibold text-slate-900">API-koppeling</h2>
                <p class="mt-1 text-sm text-slate-600">Gebruik eerst een afzonderlijk Openprovider-sandboxaccount. Productiegegevens werken niet in de sandbox.</p>
                <div class="mt-6 space-y-5">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="flex items-start gap-3 rounded-xl bg-slate-50 p-4 ring-1 ring-slate-200"><input type="checkbox" name="openprovider_enabled" value="1" @checked(old('openprovider_enabled', $settings['enabled'])) class="mt-1 rounded border-slate-300 text-primary-600"><span><strong class="block text-sm">API actief</strong><span class="mt-1 block text-xs text-slate-500">Schakelt Openprovider-verzoeken in.</span></span></label>
                        <label class="flex items-start gap-3 rounded-xl bg-sky-50 p-4 ring-1 ring-sky-200"><input type="checkbox" name="use_as_default" value="1" @checked(old('use_as_default', $settings['is_default'])) class="mt-1 rounded border-sky-300 text-primary-600"><span><strong class="block text-sm">Als domeinprovider gebruiken</strong><span class="mt-1 block text-xs text-slate-500">Stuurt nieuwe checks en bestellingen naar Openprovider.</span></span></label>
                    </div>
                    <div class="form-group"><label class="form-label" for="openprovider_environment">Omgeving</label><select class="form-input" id="openprovider_environment" name="openprovider_environment"><option value="sandbox" @selected(old('openprovider_environment', $settings['environment']) === 'sandbox')>Sandbox — veilig testen</option><option value="production" @selected(old('openprovider_environment', $settings['environment']) === 'production')>Productie — echte registraties en kosten</option></select></div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="form-group"><label class="form-label" for="openprovider_username">API-gebruikersnaam</label><input class="form-input" id="openprovider_username" name="openprovider_username" type="email" value="{{ old('openprovider_username', $settings['username']) }}" autocomplete="username"></div>
                        <div class="form-group"><label class="form-label" for="openprovider_password">API-wachtwoord</label><input class="form-input" id="openprovider_password" name="openprovider_password" type="password" placeholder="{{ $settings['has_password'] ? 'Opgeslagen — vul in om te vervangen' : 'Sandbox API-wachtwoord' }}" autocomplete="new-password"><p class="mt-1 text-xs text-slate-500">Versleuteld opgeslagen en nooit teruggetoond.</p></div>
                    </div>
                    <div class="form-group"><label class="form-label" for="openprovider_customer_handle">Standaard klanthandle</label><input class="form-input font-mono" id="openprovider_customer_handle" name="openprovider_customer_handle" value="{{ old('openprovider_customer_handle', $settings['customer_handle']) }}" placeholder="XX123456-XX"><p class="mt-1 text-xs text-slate-500">Handle uit Openprovider voor eigenaar, admin, techniek en facturatie. Vereist voor registraties en verhuizingen.</p></div>
                    <div class="form-group"><label class="form-label" for="domain_default_nameservers">Openprovider nameservers</label><input class="form-input font-mono" id="domain_default_nameservers" name="domain_default_nameservers" value="{{ old('domain_default_nameservers', $settings['default_nameservers']) }}" placeholder="ns1.openprovider.nl, ns2.openprovider.be, ns3.openprovider.eu"><p class="mt-1 text-xs text-slate-500">Laat leeg om de standaardinstellingen van Openprovider te gebruiken.</p></div>
                </div>
                <div class="mt-8 flex flex-wrap items-center justify-between gap-3"><button class="btn btn-primary" type="submit">Instellingen opslaan</button><a class="btn btn-outline" href="{{ route('admin.settings.openprovider.test') }}">Verbinding testen</a></div>
            </form>
            <aside class="h-fit rounded-2xl bg-slate-900 p-6 text-white shadow-xl">
                <h3 class="text-lg font-semibold">Status</h3>
                <dl class="mt-5 space-y-3 text-sm"><div class="flex justify-between"><dt class="text-slate-400">Omgeving</dt><dd>{{ ucfirst($settings['environment']) }}</dd></div><div class="flex justify-between"><dt class="text-slate-400">Inloggegevens</dt><dd>{{ $settings['has_password'] ? 'Opgeslagen' : 'Ontbreken' }}</dd></div><div class="flex justify-between"><dt class="text-slate-400">Actieve provider</dt><dd>{{ $settings['is_default'] ? 'Openprovider' : 'Nee' }}</dd></div></dl>
                <div class="mt-5 border-t border-white/10 pt-4 text-sm"><span class="text-slate-400">Laatste verbindingstest</span><p class="mt-1 font-medium">{{ $settings['last_status'] }}</p>@if($settings['last_checked_at'])<p class="mt-1 text-xs text-slate-500">{{ $settings['last_checked_at'] }}</p>@endif</div>
                <div class="mt-6 rounded-xl bg-amber-400/10 p-4 text-xs leading-5 text-amber-100 ring-1 ring-amber-300/20">Zet productie pas aan nadat beschikbaarheid, een sandboxregistratie, verhuizing en DNS-wijziging succesvol zijn getest.</div>
            </aside>
        </div>
    </div>
</div>
@endsection
