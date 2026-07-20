@extends('layouts.app')

@section('title', 'Factuur Aanmaken - Servura Admin')

@section('content')
@include('admin.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Factuur Aanmaken</h1>
                    <p class="mt-1 text-sm text-gray-600">Maak een nieuwe factuur aan voor een klant.</p>
                </div>
                <a href="{{ route('admin.financial.invoices') }}" class="text-sm text-gray-500 hover:text-gray-700">Terug</a>
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

            <form method="POST" action="{{ route('admin.financial.invoices.store') }}" x-data="{
                lines: [{ description: '', quantity: 1, unit_price: 0 }],
                addLine() { this.lines.push({ description: '', quantity: 1, unit_price: 0 }); },
                removeLine(index) { this.lines.splice(index, 1); },
                subtotal() { return this.lines.reduce((sum, l) => sum + (l.quantity * l.unit_price), 0); },
                vat() { return this.subtotal() * 0.21; },
                total() { return this.subtotal() + this.vat(); }
            }">
                @csrf

                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Klant</label>
                            <select name="user_id" class="form-input w-full" required>
                                <option value="">Selecteer klant...</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('user_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} {{ $customer->company ? "({$customer->company})" : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Opmerkingen</label>
                            <textarea name="notes" class="form-input w-full" rows="2">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Factuurregels</h3>

                    <template x-for="(line, index) in lines" :key="index">
                        <div class="grid grid-cols-12 gap-3 mb-3 items-end">
                            <div class="col-span-6">
                                <label x-show="index === 0" class="block text-sm font-medium text-gray-700 mb-1">Omschrijving</label>
                                <input type="text" :name="'lines['+index+'][description]'" x-model="line.description" class="form-input w-full" required>
                            </div>
                            <div class="col-span-2">
                                <label x-show="index === 0" class="block text-sm font-medium text-gray-700 mb-1">Aantal</label>
                                <input type="number" :name="'lines['+index+'][quantity]'" x-model.number="line.quantity" min="1" class="form-input w-full" required>
                            </div>
                            <div class="col-span-3">
                                <label x-show="index === 0" class="block text-sm font-medium text-gray-700 mb-1">Stuksprijs</label>
                                <input type="number" :name="'lines['+index+'][unit_price]'" x-model.number="line.unit_price" step="0.01" min="0" class="form-input w-full" required>
                            </div>
                            <div class="col-span-1">
                                <button type="button" x-on:click="removeLine(index)" x-show="lines.length > 1" class="text-red-500 hover:text-red-700 p-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    </template>

                    <button type="button" x-on:click="addLine()" class="mt-2 text-sm text-primary-600 hover:text-primary-500 font-medium">
                        + Regel toevoegen
                    </button>

                    <div class="mt-6 border-t pt-4 text-right">
                        <div class="text-sm text-gray-600">
                            Subtotaal: <span class="font-medium" x-text="'€' + subtotal().toFixed(2).replace('.', ',')"></span>
                        </div>
                        <div class="text-sm text-gray-600">
                            BTW (21%): <span class="font-medium" x-text="'€' + vat().toFixed(2).replace('.', ',')"></span>
                        </div>
                        <div class="text-lg font-bold text-gray-900 mt-1">
                            Totaal: <span x-text="'€' + total().toFixed(2).replace('.', ',')"></span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn btn-primary">Factuur Aanmaken</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
