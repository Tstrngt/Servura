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
            </div>
            <div class="mt-6 flex justify-end">
                <button type="submit" class="btn btn-primary">Instellingen opslaan</button>
            </div>
        </form>
    </div>
</div>
@endsection
