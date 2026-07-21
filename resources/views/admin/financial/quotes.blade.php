@extends('layouts.app')

@section('title', 'Offertes - Servura Admin')

@section('content')
@include('admin.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Financieel</h1>
                    <p class="mt-1 text-sm text-gray-600">Beheer facturen, transacties en offertes.</p>
                </div>
                <a href="{{ route('admin.financial.quotes.create') }}" class="btn btn-primary">Nieuwe Offerte</a>
            </div>
        </div>

        <div class="px-4 sm:px-0">
            @include('admin.financial.partials.tabs')
        </div>

        <!-- Filters -->
        <div class="px-4 sm:px-0 mb-6">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Zoeken</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Offertenummer of klant..." class="form-input w-64">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="form-input">
                        <option value="">Alle</option>
                        @foreach(\App\Models\Quote::STATUSES as $key => $label)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-outline">Filteren</button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.financial.quotes') }}" class="text-sm text-gray-500 hover:text-gray-700">Reset</a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="px-4 sm:px-0">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                @if($quotes->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Offerte</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Klant</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Datum</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Laatst gewijzigd</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Bedrag</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acties</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($quotes as $quote)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.financial.quotes.show', $quote) }}" class="text-primary-600 hover:text-primary-500">{{ $quote->quote_number }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $quote->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $quote->user->company }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $quote->quote_date->format('d-m-Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $quote->updated_at->format('d-m-Y H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">€{{ number_format($quote->total, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $quote->statusLabel['color'] }}-100 text-{{ $quote->statusLabel['color'] }}-800">
                                                {{ $quote->statusLabel['text'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('admin.financial.quotes.edit', $quote) }}" class="text-gray-400 hover:text-gray-600" title="Bewerken">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                <form method="POST" action="{{ route('admin.financial.quotes.destroy', $quote) }}" class="inline" onsubmit="return confirm('Weet je zeker dat je deze offerte wilt verwijderen?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-400 hover:text-red-600" title="Verwijderen">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($quotes->hasPages())
                        <div class="px-6 py-4 border-t">{{ $quotes->withQueryString()->links() }}</div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <h3 class="text-sm font-medium text-gray-900">Geen offertes gevonden</h3>
                        <p class="mt-1 text-sm text-gray-500">Er zijn nog geen offertes aangemaakt.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
