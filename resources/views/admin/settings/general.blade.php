@extends('layouts.app')

@section('title', 'Algemene instellingen - Servura Admin')

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

        <form action="{{ route('admin.settings.general.update') }}" method="POST" class="max-w-3xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            @csrf
            @method('PUT')
            <h2 class="text-lg font-semibold text-slate-900">Algemeen</h2>
            <p class="mt-1 text-sm text-slate-600">Basisgegevens van het platform en contactinformatie.</p>
            <div class="mt-6 grid grid-cols-1 gap-x-5 sm:grid-cols-2">
                <div class="form-group">
                    <label class="form-label" for="site_name">Sitenaam</label>
                    <input class="form-input" id="site_name" name="site_name" type="text" required value="{{ old('site_name', $settings['site_name']) }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="contact_email">Contact e-mailadres</label>
                    <input class="form-input" id="contact_email" name="contact_email" type="email" value="{{ old('contact_email', $settings['contact_email']) }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="contact_phone">Telefoonnummer</label>
                    <input class="form-input" id="contact_phone" name="contact_phone" type="text" value="{{ old('contact_phone', $settings['contact_phone']) }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="contact_address">Adres</label>
                    <input class="form-input" id="contact_address" name="contact_address" type="text" value="{{ old('contact_address', $settings['contact_address']) }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="da_delete_after_days">DA-account verwijderen na annulering (dagen)</label>
                    <input class="form-input" id="da_delete_after_days" name="da_delete_after_days" type="number" min="0" max="3650" value="{{ old('da_delete_after_days', $settings['da_delete_after_days']) }}">
                    <p class="mt-1 text-xs text-slate-500">Na een opzegging wordt het DirectAdmin-account geschorst en na dit aantal dagen definitief verwijderd. 0 = meteen verwijderen.</p>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="submit" class="btn btn-primary">Instellingen opslaan</button>
            </div>
        </form>

        <div class="mt-8 max-w-3xl rounded-2xl border border-red-200 bg-red-50/50 p-6">
            <h2 class="text-lg font-semibold text-red-900">Danger zone</h2>
            <p class="mt-1 text-sm text-red-700">Verwijder alle klantaccounts en alle bijbehorende gegevens: diensten, facturen, transacties, offertes, tickets, orders en betaalbetalingen. Diensten/producten, instellingen en medewerkersaccounts blijven bestaan. Dit kan niet ongedaan worden — bedoeld voor de testfase.</p>
            <form action="{{ route('admin.settings.reset-customers') }}" method="POST" class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-end" onsubmit="return confirm('Weet je het zeker? ALLE klantaccounts en hun gegevens worden definitief verwijderd.')">
                @csrf
                <div class="flex-1">
                    <label class="form-label text-red-800" for="confirm">Typ RESET om te bevestigen</label>
                    <input class="form-input border-red-300 focus:border-red-500 focus:ring-red-500" id="confirm" name="confirm" type="text" required autocomplete="off" placeholder="RESET">
                </div>
                <button type="submit" class="btn whitespace-nowrap bg-red-600 text-white hover:bg-red-700">Alle klanten verwijderen</button>
            </form>
        </div>
    </div>
</div>
@endsection
