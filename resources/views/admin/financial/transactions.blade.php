@extends('layouts.app')

@section('title', 'Transacties - Servura Admin')

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
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nummer, klant of omschrijving..." class="form-input w-64">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type" class="form-input">
                        <option value="">Alle</option>
                        @foreach(\App\Models\Transaction::TYPES as $key => $label)
                            <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="form-input">
                        <option value="">Alle</option>
                        @foreach(\App\Models\Transaction::STATUSES as $key => $label)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kwartaal</label>
                    <select name="quarter" class="form-input">
                        <option value="">Alle</option>
                        <option value="1" {{ request('quarter') == '1' ? 'selected' : '' }}>Q1 (jan-mrt)</option>
                        <option value="2" {{ request('quarter') == '2' ? 'selected' : '' }}>Q2 (apr-jun)</option>
                        <option value="3" {{ request('quarter') == '3' ? 'selected' : '' }}>Q3 (jul-sep)</option>
                        <option value="4" {{ request('quarter') == '4' ? 'selected' : '' }}>Q4 (okt-dec)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jaar</label>
                    <input type="number" name="year" value="{{ request('year', date('Y')) }}" class="form-input w-24" min="2020" max="2030">
                </div>
                <button type="submit" class="btn btn-outline">Filteren</button>
                @if(request()->hasAny(['search', 'type', 'status', 'quarter']))
                    <a href="{{ route('admin.financial.transactions') }}" class="text-sm text-gray-500 hover:text-gray-700">Reset</a>
                @endif
            </form>
        </div>

        <!-- BTW / Omzet Summary -->
        <div class="px-4 sm:px-0 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white shadow rounded-lg p-4">
                    <div class="text-sm text-gray-500">Totaal ontvangen (incl. BTW)</div>
                    <div class="text-2xl font-bold text-gray-900">€{{ number_format($summary['total_revenue'], 2, ',', '.') }}</div>
                </div>
                <div class="bg-white shadow rounded-lg p-4">
                    <div class="text-sm text-gray-500">Omzet (excl. BTW)</div>
                    <div class="text-2xl font-bold text-green-600">€{{ number_format($summary['net_revenue'], 2, ',', '.') }}</div>
                </div>
                <div class="bg-white shadow rounded-lg p-4">
                    <div class="text-sm text-gray-500">BTW (21%)</div>
                    <div class="text-2xl font-bold text-blue-600">€{{ number_format($summary['total_vat'], 2, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="px-4 sm:px-0">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                @if($transactions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nummer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Klant</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Datum</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Bedrag</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Betaalmethode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($transactions as $transaction)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $transaction->transaction_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $transaction->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $transaction->user->company }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->transaction_date->format('d-m-Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \App\Models\Transaction::TYPES[$transaction->type] ?? $transaction->type }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">€{{ number_format($transaction->amount, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \App\Models\Transaction::PAYMENT_METHODS[$transaction->payment_method] ?? $transaction->payment_method ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $transaction->statusLabel['color'] }}-100 text-{{ $transaction->statusLabel['color'] }}-800">
                                                {{ $transaction->statusLabel['text'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($transactions->hasPages())
                        <div class="px-6 py-4 border-t">{{ $transactions->withQueryString()->links() }}</div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <h3 class="text-sm font-medium text-gray-900">Geen transacties gevonden</h3>
                        <p class="mt-1 text-sm text-gray-500">Er zijn nog geen transacties geregistreerd.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
