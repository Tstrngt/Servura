@extends('layouts.app')

@section('title', 'Serverkoppelingen - Servura Admin')

@section('content')
@include('admin.partials.sidebar')
<div class="min-h-screen bg-gray-50 lg:pl-64">
    <div class="mx-auto w-full max-w-[1600px] px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6"><h1 class="text-2xl font-bold text-slate-900">Serverkoppelingen</h1><p class="mt-1 text-sm text-slate-600">Beheer infrastructuurproviders voor automatische provisioning.</p></div>
        @include('admin.partials.services-nav')

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-[400px_minmax(0,1fr)]">
            <form action="{{ route('admin.server-connections.store') }}" method="POST" class="h-fit rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                @csrf
                <h2 class="text-lg font-semibold text-slate-900">Nieuwe serverkoppeling</h2>
                <p class="mb-5 mt-1 text-sm text-slate-500">Credentials worden versleuteld opgeslagen.</p>
                <div class="form-group"><label class="form-label" for="name">Naam *</label><input class="form-input" id="name" name="name" required value="{{ old('name') }}" placeholder="DirectAdmin productie"></div>
                <div class="form-group"><label class="form-label" for="provider">Provider *</label><select class="form-input" id="provider" name="provider" required>@foreach($providers as $value => $label)<option value="{{ $value }}">{{ $label }}</option>@endforeach</select></div>
                <div class="form-group"><label class="form-label" for="url">Paneel-URL *</label><input class="form-input" id="url" name="url" type="url" required value="{{ old('url') }}" placeholder="https://server.example.com:2222"></div>
                <div class="grid grid-cols-1 gap-x-4 sm:grid-cols-2">
                    <div class="form-group"><label class="form-label" for="username">Reseller *</label><input class="form-input" id="username" name="username" required value="{{ old('username') }}"></div>
                    <div class="form-group"><label class="form-label" for="password">Wachtwoord *</label><input class="form-input" id="password" name="password" type="password" required autocomplete="new-password"></div>
                    <div class="form-group"><label class="form-label" for="shared_ip">Gedeeld IP *</label><input class="form-input" id="shared_ip" name="shared_ip" required value="{{ old('shared_ip') }}"></div>
                    <div class="form-group"><label class="form-label" for="timeout">Timeout</label><input class="form-input" id="timeout" name="timeout" type="number" min="5" max="120" value="{{ old('timeout', 20) }}"></div>
                </div>
                <div class="mb-5 flex flex-wrap gap-5 text-sm text-slate-700"><label class="flex items-center gap-2"><input type="checkbox" name="verify_ssl" value="1" checked class="rounded border-slate-300 text-primary-600"> SSL verifiëren</label><label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300 text-primary-600"> Actief</label></div>
                <button type="submit" class="btn btn-primary w-full">Serverkoppeling toevoegen</button>
            </form>

            <div class="space-y-5">
                @forelse($connections as $connection)
                    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                        <div class="mb-5 flex flex-col gap-3 border-b border-slate-200 pb-4 sm:flex-row sm:items-center sm:justify-between">
                            <div><div class="flex items-center gap-2"><h2 class="font-semibold text-slate-900">{{ $connection->name }}</h2><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $connection->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">{{ $connection->is_active ? 'Actief' : 'Inactief' }}</span></div><p class="mt-1 text-xs text-slate-500">{{ $connection->provider_label }} · {{ $connection->services_count }} gekoppelde producten</p></div>
                            <div class="flex items-center gap-2">
                                @if($connection->last_test_status)<span class="text-xs font-medium {{ $connection->last_test_status === 'success' ? 'text-emerald-700' : 'text-red-600' }}">{{ $connection->last_test_status === 'success' ? 'Verbinding geslaagd' : 'Verbinding mislukt' }}</span>@endif
                                <form action="{{ route('admin.server-connections.test', $connection) }}" method="POST">@csrf<button type="submit" class="btn btn-outline px-3 py-2 text-sm">Test verbinding</button></form>
                            </div>
                        </div>
                        <form action="{{ route('admin.server-connections.update', $connection) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 gap-x-4 md:grid-cols-2 xl:grid-cols-3">
                                <div class="form-group"><label class="form-label">Naam</label><input class="form-input" name="name" required value="{{ $connection->name }}"></div>
                                <div class="form-group"><label class="form-label">Provider</label><select class="form-input" name="provider">@foreach($providers as $value => $label)<option value="{{ $value }}" {{ $connection->provider === $value ? 'selected' : '' }}>{{ $label }}</option>@endforeach</select></div>
                                <div class="form-group"><label class="form-label">Paneel-URL</label><input class="form-input" name="url" type="url" required value="{{ $connection->url }}"></div>
                                <div class="form-group"><label class="form-label">Reseller</label><input class="form-input" name="username" required value="{{ $connection->username }}"></div>
                                <div class="form-group"><label class="form-label">Nieuw wachtwoord</label><input class="form-input" name="password" type="password" autocomplete="new-password" placeholder="Ongewijzigd laten"></div>
                                <div class="form-group"><label class="form-label">Gedeeld IP</label><input class="form-input" name="shared_ip" required value="{{ $connection->shared_ip }}"></div>
                                <div class="form-group"><label class="form-label">Timeout</label><input class="form-input" name="timeout" type="number" min="5" max="120" value="{{ $connection->timeout }}"></div>
                                <div class="flex items-center gap-5 text-sm text-slate-700 md:col-span-2"><label class="flex items-center gap-2"><input type="checkbox" name="verify_ssl" value="1" {{ $connection->verify_ssl ? 'checked' : '' }} class="rounded border-slate-300 text-primary-600"> SSL verifiëren</label><label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" {{ $connection->is_active ? 'checked' : '' }} class="rounded border-slate-300 text-primary-600"> Actief</label></div>
                            </div>
                            @if($connection->last_test_message)<p class="mb-4 rounded-lg bg-slate-50 px-3 py-2 text-xs text-slate-600">Laatste test: {{ $connection->last_test_message }} @if($connection->last_tested_at)({{ $connection->last_tested_at->format('d-m-Y H:i') }})@endif</p>@endif
                            <div class="flex justify-end"><button type="submit" class="btn btn-primary">Wijzigingen opslaan</button></div>
                        </form>
                        @if($connection->services_count === 0)<form action="{{ route('admin.server-connections.destroy', $connection) }}" method="POST" class="mt-3 flex justify-end" onsubmit="return confirm('Deze serverkoppeling verwijderen?')">@csrf @method('DELETE')<button type="submit" class="text-xs font-medium text-red-600 hover:text-red-800">Serverkoppeling verwijderen</button></form>@endif
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center text-sm text-slate-500">Nog geen serverkoppelingen ingesteld.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
