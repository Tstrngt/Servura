@extends('layouts.app')

@section('title', 'Abuse-meldingen - Servura Admin')

@section('content')
@include('admin.partials.sidebar')

<div class="min-h-screen bg-gray-50 lg:pl-64">
    <main class="mx-auto w-full max-w-[1600px] px-4 py-4 sm:px-6 lg:px-8">
        <header class="py-4">
            <h1 class="text-2xl font-bold text-gray-900">Abuse-meldingen</h1>
            <p class="mt-1 text-sm text-gray-600">Beheer meldingen van misbruik of illegale content.</p>
        </header>

        <div class="overflow-hidden rounded-lg bg-white shadow">
            @if($reports->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Domein / URL</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Melder</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Datum</th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase text-gray-500">Acties</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($reports as $report)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">#{{ $report->id }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $report->category_label }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        <div class="max-w-xs truncate">{{ $report->domain ?: '-' }}</div>
                                        <div class="max-w-xs truncate text-xs text-gray-500">{{ $report->url }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $report->name }}<br><span class="text-xs text-gray-500">{{ $report->email }}</span></td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-800">
                                            {{ $report->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $report->created_at->format(config('site.date_format', 'd-m-Y H:i')) }}</td>
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <a href="{{ route('admin.abuse-reports.show', $report) }}" class="text-primary-600 hover:text-primary-500">Bekijk</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $reports->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center text-gray-500">
                    <p>Er zijn nog geen abuse-meldingen binnengekomen.</p>
                </div>
            @endif
        </div>
    </main>
</div>
@endsection
