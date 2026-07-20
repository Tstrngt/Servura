@extends('layouts.app')

@section('title', 'Te Factureren - Servura Admin')

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
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Omschrijving of klant..." class="form-input w-64">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="form-input">
                        <option value="open" {{ request('status', 'open') == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="gefactureerd" {{ request('status') == 'gefactureerd' ? 'selected' : '' }}>Gefactureerd</option>
                        <option value="" {{ request('status') === '' ? 'selected' : '' }}>Alle</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-outline">Filteren</button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.financial.billable-items') }}" class="text-sm text-gray-500 hover:text-gray-700">Reset</a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="px-4 sm:px-0">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                @if($billableItems->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Klant</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Omschrijving</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aantal</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Stuksprijs</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Totaal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($billableItems as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $item->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $item->user->company }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $item->description }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($item->period_start && $item->period_end)
                                                {{ $item->period_start->format('d-m-Y') }} - {{ $item->period_end->format('d-m-Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">{{ $item->quantity }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">€{{ number_format($item->unit_price, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">€{{ number_format($item->total, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->status === 'open' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $item->status === 'open' ? 'Open' : 'Gefactureerd' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($billableItems->hasPages())
                        <div class="px-6 py-4 border-t">{{ $billableItems->withQueryString()->links() }}</div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <h3 class="text-sm font-medium text-gray-900">Geen te factureren items</h3>
                        <p class="mt-1 text-sm text-gray-500">Er zijn geen openstaande items om te factureren.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
