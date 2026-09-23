@extends('layouts.app')

@section('title', 'Betaalprovider-instellingen - Servura Admin')

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
            <form action="{{ route('admin.settings.payments.update') }}" method="POST" class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                @csrf
                @method('PUT')
                <h2 class="text-lg font-semibold text-slate-900">Mollie-betaalprovider</h2>
                <p class="mt-1 text-sm text-slate-600">De API-key wordt versleuteld in de database opgeslagen en is alleen zichtbaar voor de eigenaar.</p>
                <div class="mt-6 grid grid-cols-1 gap-x-5 sm:grid-cols-2">
                    <div class="form-group">
                        <label class="form-label" for="mollie_key">API-key</label>
                        <input class="form-input" id="mollie_key" name="mollie_key" type="password" autocomplete="off" placeholder="••••••" value="{{ old('mollie_key') }}">
                        <p class="mt-1 text-xs text-slate-500">Laat leeg om ongewijzigd te laten.</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="mollie_key_mode">Modus bij nieuwe invoer</label>
                        <select class="form-input" id="mollie_key_mode" name="mollie_key_mode">
                            <option value="test" {{ old('mollie_key_mode', 'test') === 'test' ? 'selected' : '' }}>Test</option>
                            <option value="live" {{ old('mollie_key_mode') === 'live' ? 'selected' : '' }}>Live</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="btn btn-primary">Instellingen opslaan</button>
                </div>
            </form>

            <aside class="h-fit space-y-6">
                <div class="rounded-2xl bg-slate-900 p-6 text-white shadow-xl">
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="text-lg font-semibold">Mollie-status</h2>
                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $mollie['configured'] ? ($mollie['mode'] === 'live' ? 'bg-emerald-400/15 text-emerald-300' : 'bg-amber-400/15 text-amber-300') : 'bg-red-400/15 text-red-300' }}">
                            {{ !$mollie['configured'] ? 'Niet ingesteld' : ($mollie['mode'] === 'live' ? 'Live' : 'Test') }}
                        </span>
                    </div>
                    <p class="mt-4 text-sm leading-relaxed text-slate-300">
                        De API-key wordt versleuteld opgeslagen in de database. Alleen de eigenaar kan deze wijzigen.
                    </p>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection
