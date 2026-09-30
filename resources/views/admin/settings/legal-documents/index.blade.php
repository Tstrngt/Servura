@extends('layouts.app')
@section('title', 'Juridische documenten - Servura Admin')
@section('content')
@include('admin.partials.sidebar')
<div class="min-h-screen bg-slate-50 lg:pl-64">
    <main class="mx-auto max-w-[1600px] px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Juridische documenten</h1>
            <p class="mt-1 text-sm text-slate-600">Beheer de publieke juridische pagina’s, versies en publicaties.</p>
        </div>
        @include('admin.partials.settings-nav')

        @if(session('success'))
            <div class="mb-6 rounded-xl bg-emerald-50 p-4 text-sm text-emerald-800 ring-1 ring-emerald-200">{{ session('success') }}</div>
        @endif

        <div class="mt-6 rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Titel</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Slug</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Versie</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Ingangsdatum</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Laatst gepubliceerd</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase">Actie</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($documents as $document)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 text-sm font-medium text-slate-900">{{ $document->title }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $document->slug }}</td>
                            <td class="px-4 py-3 text-sm">
                                @if($document->isPublished())
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Gepubliceerd</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20">Concept</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $document->version }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $document->effective_date?->format('d-m-Y') ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $document->published_at?->format('d-m-Y H:i') ?? '-' }}</td>
                            <td class="px-4 py-3 text-right text-sm">
                                <a href="{{ route('admin.settings.legal-documents.edit', $document) }}" class="text-primary-600 hover:text-primary-800 font-medium">Bewerken</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-sm text-slate-500">Geen documenten gevonden.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</div>
@endsection
