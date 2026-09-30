@extends('layouts.app')
@section('title', 'Juridisch document bewerken - Servura Admin')
@section('content')
@include('admin.partials.sidebar')
<div class="min-h-screen bg-slate-50 lg:pl-64">
    <main class="mx-auto max-w-[1600px] px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ $document->title }}</h1>
                <p class="mt-1 text-sm text-slate-600">Slug: {{ $document->slug }} &middot; Huidige versie: {{ $document->version }}</p>
            </div>
            <a href="{{ route('admin.settings.legal-documents.index') }}" class="btn btn-outline">Terug naar overzicht</a>
        </div>
        @include('admin.partials.settings-nav')

        @if(session('success'))
            <div class="mb-6 rounded-xl bg-emerald-50 p-4 text-sm text-emerald-800 ring-1 ring-emerald-200">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="mb-6 rounded-xl bg-red-50 p-4 text-sm text-red-800 ring-1 ring-red-200">
                <ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form id="legal-document-form" method="POST" action="{{ route('admin.settings.legal-documents.update', $document) }}" class="mt-6 space-y-6">
            @csrf
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="grid gap-6 md:grid-cols-3">
                    <div>
                        <label class="form-label" for="title">Titel</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $document->title) }}" class="form-input mt-1 w-full" required>
                    </div>
                    <div>
                        <label class="form-label" for="version">Nieuwe versie</label>
                        <input type="text" id="version" name="version" value="{{ old('version', $document->version) }}" class="form-input mt-1 w-full" required>
                    </div>
                    <div>
                        <label class="form-label" for="effective_date">Ingangsdatum</label>
                        <input type="date" id="effective_date" name="effective_date" value="{{ old('effective_date', $document->effective_date?->format('Y-m-d')) }}" class="form-input mt-1 w-full">
                    </div>
                </div>
                <div class="mt-6">
                    <label class="form-label" for="content">Inhoud (HTML)</label>
                    <textarea id="content" name="content" rows="20" class="form-input mt-1 w-full font-mono text-sm">{{ old('content', $document->content) }}</textarea>
                    <p class="mt-2 text-xs text-slate-500">Je kunt hier HTML gebruiken. Plaats geen onveilige scripts; alleen vertrouwde beheerders hebben toegang tot dit formulier.</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <button type="submit" class="btn btn-outline">Opslaan als concept</button>
                <button type="submit" formaction="{{ route('admin.settings.legal-documents.preview', $document) }}" formtarget="_blank" class="btn btn-secondary">Voorbeeld</button>
                @can('legal.publish')
                    <button type="submit" formaction="{{ route('admin.settings.legal-documents.publish', $document) }}" class="btn btn-primary" onclick="return confirm('Weet je zeker dat je deze versie wilt publiceren? De huidige versie wordt bewaard in de historie.')">Publiceren</button>
                @endcan
            </div>
        </form>

        @if($document->versions->isNotEmpty())
            <div class="mt-10 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-lg font-semibold text-slate-900">Versiehistorie</h2>
                <table class="mt-4 min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Versie</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Ingangsdatum</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Gepubliceerd door</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Gepubliceerd op</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($document->versions as $version)
                            <tr>
                                <td class="px-4 py-2 text-sm text-slate-900">{{ $version->version }}</td>
                                <td class="px-4 py-2 text-sm text-slate-600">{{ $version->effective_date?->format('d-m-Y') ?? '-' }}</td>
                                <td class="px-4 py-2 text-sm text-slate-600">{{ $version->publishedBy?->name ?? '-' }}</td>
                                <td class="px-4 py-2 text-sm text-slate-600">{{ $version->published_at?->format('d-m-Y H:i') ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </main>
</div>
@endsection
