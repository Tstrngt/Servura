@extends('layouts.app')

@section('title', "Transactie {$transaction->transaction_number} Bewerken - Servura Admin")

@section('content')
@include('admin.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="max-w-2xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $transaction->transaction_number }} Bewerken</h1>
                    <p class="mt-1 text-sm text-gray-600">Klant: {{ $transaction->user->name }}</p>
                </div>
                <a href="{{ route('admin.financial.transactions') }}" class="text-sm text-gray-500 hover:text-gray-700">Annuleren</a>
            </div>

            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.financial.transactions.update', $transaction) }}">
                @csrf @method('PUT')

                <div class="bg-white shadow rounded-lg p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bedrag (€)</label>
                            <input type="number" name="amount" value="{{ old('amount', $transaction->amount) }}" step="0.01" min="0.01" class="form-input w-full" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Transactiedatum</label>
                            <input type="date" name="transaction_date" value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d')) }}" class="form-input w-full" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select name="type" class="form-input w-full" required>
                                @foreach(\App\Models\Transaction::TYPES as $key => $label)
                                    <option value="{{ $key }}" {{ old('type', $transaction->type) === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Betaalmethode</label>
                            <select name="payment_method" class="form-input w-full" required>
                                @foreach(\App\Models\Transaction::PAYMENT_METHODS as $key => $label)
                                    <option value="{{ $key }}" {{ old('payment_method', $transaction->payment_method) === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="form-input w-full" required>
                                @foreach(\App\Models\Transaction::STATUSES as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', $transaction->status) === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Referentie</label>
                            <input type="text" name="reference" value="{{ old('reference', $transaction->reference) }}" class="form-input w-full" placeholder="Optioneel...">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Omschrijving</label>
                            <input type="text" name="description" value="{{ old('description', $transaction->description) }}" class="form-input w-full" placeholder="Optioneel...">
                        </div>
                    </div>

                    @if($transaction->invoice)
                        <div class="mt-6 pt-4 border-t">
                            <p class="text-sm text-gray-600">
                                Gekoppeld aan factuur:
                                <a href="{{ route('admin.financial.invoices.show', $transaction->invoice) }}" class="text-primary-600 hover:text-primary-500 font-medium">
                                    {{ $transaction->invoice->invoice_number }}
                                </a>
                            </p>
                        </div>
                    @endif

                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('admin.financial.transactions') }}" class="btn btn-outline">Annuleren</a>
                        <button type="submit" class="btn btn-primary">Opslaan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
