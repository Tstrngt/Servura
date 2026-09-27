@extends('layouts.app')

@section('title', 'Abuse-melding #'.$abuseReport->id.' - Servura Admin')

@section('content')
@include('admin.partials.sidebar')

<div class="min-h-screen bg-gray-50 lg:pl-64">
    <main class="mx-auto w-full max-w-[1600px] px-4 py-4 sm:px-6 lg:px-8">
        <header class="py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Abuse-melding #{{ $abuseReport->id }}</h1>
                <p class="mt-1 text-sm text-gray-600">Binnengekomen op {{ $abuseReport->created_at->format(config('site.date_format', 'd-m-Y H:i')) }}</p>
            </div>
            <a href="{{ route('admin.abuse-reports.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-500">← Terug naar overzicht</a>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Details</h2>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <h3 class="text-xs font-medium uppercase text-gray-500">Type</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $abuseReport->category_label }}</p>
                        </div>
                        <div>
                            <h3 class="text-xs font-medium uppercase text-gray-500">Domein</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $abuseReport->domain ?: '-' }}</p>
                        </div>
                        <div>
                            <h3 class="text-xs font-medium uppercase text-gray-500">URL</h3>
                            <p class="mt-1 text-sm text-gray-900 break-all"><a href="{{ $abuseReport->url }}" target="_blank" rel="noopener noreferrer" class="text-primary-600 hover:underline">{{ $abuseReport->url }}</a></p>
                        </div>
                        <div>
                            <h3 class="text-xs font-medium uppercase text-gray-500">Omschrijving</h3>
                            <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $abuseReport->description }}</p>
                        </div>
                        <div>
                            <h3 class="text-xs font-medium uppercase text-gray-500">Waarom is dit onrechtmatig of schadelijk?</h3>
                            <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $abuseReport->reason }}</p>
                        </div>
                        @if($abuseReport->attachment_path)
                            <div>
                                <h3 class="text-xs font-medium uppercase text-gray-500">Bijlage</h3>
                                <p class="mt-1 text-sm text-gray-900">{{ basename($abuseReport->attachment_path) }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Melder</h2>
                    </div>
                    <div class="px-6 py-4 space-y-2 text-sm">
                        <p><strong>Naam:</strong> {{ $abuseReport->name }}</p>
                        <p><strong>E-mail:</strong> <a href="mailto:{{ $abuseReport->email }}" class="text-primary-600 hover:underline">{{ $abuseReport->email }}</a></p>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Status wijzigen</h2>
                    </div>
                    <div class="px-6 py-4">
                        <form action="{{ route('admin.abuse-reports.update-status', $abuseReport) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="form-input w-full mt-1">
                                    @foreach(App\Models\AbuseReport::STATUSES as $key => $label)
                                        <option value="{{ $key }}" {{ $abuseReport->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-full">Opslaan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
