@extends('layouts.app')

@section('title', 'Offerteformulier - Servura Admin')

@section('content')
@include('admin.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="mx-auto w-full max-w-[1600px] px-4 py-4 sm:px-6 lg:px-8">
        <div class="py-4">
            <h1 class="text-2xl font-bold text-gray-900">Instellingen</h1>
            <p class="mt-1 text-sm text-gray-600">Stel het publieke offerteformulier samen.</p>
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

        <form action="{{ route('admin.settings.formbuilder.update') }}" method="POST" class="max-w-4xl">
            @csrf
            @method('PUT')

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-lg font-semibold text-slate-900">Antwoordopties</h2>
                <p class="mt-1 text-sm text-slate-600">
                    Per groep één optie per regel. Voeg optioneel een toelichting toe na een sluisteken:
                    <code class="rounded bg-slate-100 px-1">Label | Toelichting</code>.
                    Laat een groep leeg om de standaardopties te gebruiken.
                </p>

                <div class="mt-6 space-y-6">
                    @foreach(\App\Support\QuoteFormFields::GROUPS as $key => $label)
                        <div class="form-group">
                            <label class="form-label" for="field_{{ $key }}">{{ $label }}</label>
                            <textarea class="form-input font-mono text-sm" id="field_{{ $key }}" name="fields[{{ $key }}]" rows="{{ max(4, substr_count($lines[$key] ?? '', "\n") + 2) }}">{{ old("fields.$key", $lines[$key] ?? '') }}</textarea>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 flex items-center justify-between border-t border-slate-200 pt-5">
                    <a href="{{ route('quote.builder') }}" target="_blank" class="text-sm font-medium text-primary-600 hover:text-primary-800">Bekijk het formulier</a>
                    <button type="submit" class="btn btn-primary">Formulier opslaan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
