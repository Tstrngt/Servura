@extends('layouts.app')

@section('title', 'Facturen - Servura Admin')

@section('content')
@include('admin.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="mx-auto w-full max-w-[1600px] px-4 py-4 sm:px-6 lg:px-8">
        <div class="py-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Financieel</h1>
                    <p class="mt-1 text-sm text-gray-600">Beheer facturen, transacties en offertes.</p>
                </div>
                <a href="{{ route('admin.financial.invoices.create') }}" class="btn btn-primary">Nieuwe Factuur</a>
            </div>
        </div>

        @include('admin.financial.partials.financial-nav')

        <!-- Filters -->
        <div class="px-4 sm:px-0 mb-6">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Zoeken</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Factuurnummer of klant..." class="form-input w-64">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="form-input">
                        <option value="">Alle</option>
                        @foreach(\App\Models\Invoice::STATUSES as $key => $label)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-outline">Filteren</button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.financial.invoices') }}" class="text-sm text-gray-500 hover:text-gray-700">Reset</a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="px-4 sm:px-0">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                @if($invoices->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Factuur</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Klant</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Datum</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vervaldatum</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Bedrag</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acties</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($invoices as $invoice)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.financial.invoices.show', $invoice) }}" class="text-primary-600 hover:text-primary-500">{{ $invoice->invoice_number }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $invoice->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $invoice->user->company }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $invoice->invoice_date->format('d-m-Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $invoice->due_date->format('d-m-Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">€{{ number_format($invoice->total, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $invoice->statusLabel['color'] }}-100 text-{{ $invoice->statusLabel['color'] }}-800">
                                                {{ $invoice->statusLabel['text'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('admin.financial.invoices.edit', $invoice) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 ring-1 ring-slate-200 transition-colors hover:bg-primary-50 hover:text-primary-700 hover:ring-primary-200" title="Bewerken" aria-label="Factuur {{ $invoice->invoice_number }} bewerken">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931ZM16.862 4.487 19.5 7.125"/></svg>
                                                </a>
                                                <form method="POST" action="{{ route('admin.financial.invoices.destroy', $invoice) }}" class="inline" onsubmit="return confirm('Weet je zeker dat je deze factuur wilt verwijderen?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 ring-1 ring-slate-200 transition-colors hover:bg-red-50 hover:text-red-600 hover:ring-red-200" title="Verwijderen" aria-label="Factuur {{ $invoice->invoice_number }} verwijderen">
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
                    @if($invoices->hasPages())
                        <div class="px-6 py-4 border-t">{{ $invoices->withQueryString()->links() }}</div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <h3 class="text-sm font-medium text-gray-900">Geen facturen gevonden</h3>
                        <p class="mt-1 text-sm text-gray-500">Er zijn nog geen facturen aangemaakt.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
