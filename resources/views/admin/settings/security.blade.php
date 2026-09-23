@extends('layouts.app')

@section('title', 'Beveiliging - Servura Admin')

@section('content')
@include('admin.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="mx-auto w-full max-w-[1600px] px-4 py-4 sm:px-6 lg:px-8">
        <div class="py-4">
            <h1 class="text-2xl font-bold text-gray-900">Instellingen</h1>
            <p class="mt-1 text-sm text-gray-600">Anti-spam en captcha voor publieke formulieren.</p>
        </div>

        @include('admin.partials.settings-nav')

        @if(session('success'))
            <div class="mb-4"><div class="rounded-md bg-green-50 p-4"><p class="text-sm text-green-700">{{ session('success') }}</p></div></div>
        @endif
        @if($errors->any())
            <div class="mb-4"><div class="rounded-md bg-red-50 p-4">
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div></div>
        @endif

        <form action="{{ route('admin.settings.security.update') }}" method="POST" class="max-w-3xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            @csrf
            @method('PUT')
            <h2 class="text-lg font-semibold text-slate-900">Captcha</h2>
            <p class="mt-1 text-sm text-slate-600">Bescherm het contactformulier en het offerteformulier tegen robots. De sleutels haal je uit het dashboard van je provider.</p>

            <label class="mt-5 flex items-start gap-3 rounded-xl bg-slate-50 p-4 ring-1 ring-slate-200">
                <input type="checkbox" name="captcha_enabled" value="1" {{ old('captcha_enabled', $captcha['enabled']) ? 'checked' : '' }} class="mt-1 rounded border-slate-300 text-primary-600">
                <span>
                    <strong class="block text-sm text-slate-900">Captcha inschakelen</strong>
                    <span class="mt-1 block text-sm text-slate-600">Formulieren worden pas verwerkt als de captcha is opgelost.</span>
                </span>
            </label>

            <div class="mt-5 grid grid-cols-1 gap-x-5 sm:grid-cols-2">
                <div class="form-group">
                    <label class="form-label" for="captcha_provider">Provider</label>
                    <select class="form-input" id="captcha_provider" name="captcha_provider" required>
                        @foreach(\App\Services\CaptchaService::PROVIDERS as $value => $label)
                            <option value="{{ $value }}" {{ old('captcha_provider', $captcha['provider']) === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="captcha_site_key">Site key</label>
                    <input class="form-input" id="captcha_site_key" name="captcha_site_key" type="text" value="{{ old('captcha_site_key', $captcha['site_key']) }}" placeholder="Publieke sleutel">
                </div>
                <div class="form-group sm:col-span-2">
                    <label class="form-label" for="captcha_secret_key">Secret key</label>
                    <input class="form-input" id="captcha_secret_key" name="captcha_secret_key" type="password" autocomplete="off" placeholder="{{ $captcha['has_secret'] ? 'Opgeslagen — leeg laten om te behouden' : 'Geheime sleutel' }}">
                    <p class="mt-1 text-xs text-slate-500">Wordt versleuteld opgeslagen en nooit getoond.</p>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="btn btn-primary">Instellingen opslaan</button>
            </div>
        </form>
    </div>
</div>
@endsection
