@extends('layouts.app')

@section('title', 'Domein TLD\'s - Servura Admin')

@section('content')
@include('admin.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="mx-auto w-full max-w-[1600px] px-4 py-4 sm:px-6 lg:px-8">
        <div class="py-4">
            <h1 class="text-2xl font-bold text-gray-900">Domein TLD's</h1>
            <p class="mt-1 text-sm text-gray-600">Beheer beschikbare extensies, verkoopprijzen en sortering.</p>
        </div>

        @include('admin.partials.settings-nav')

        @if(session('success'))
            <div class="mb-4"><div class="rounded-md bg-green-50 p-4"><p class="text-sm text-green-700">{{ session('success') }}</p></div></div>
        @endif
        @if($errors->any())
            <div class="mb-4"><div class="rounded-md bg-red-50 p-4">
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div></div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="lg:col-span-1">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-lg font-semibold text-slate-900 mb-4">TLD toevoegen</h2>
                    <form action="{{ route('admin.settings.domains.tlds.store') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="form-label" for="extension">Extensie</label>
                                <input type="text" id="extension" name="extension" value="{{ old('extension') }}" placeholder=".nl" class="form-input" required>
                                <p class="mt-1 text-xs text-slate-500">Begin met een punt, bijv. .nl</p>
                            </div>
                            <div>
                                <label class="form-label" for="registration_price">Registratieprijs (€)</label>
                                <input type="number" step="0.01" min="0" id="registration_price" name="registration_price" value="{{ old('registration_price') }}" class="form-input" required>
                            </div>
                            <div>
                                <label class="form-label" for="renewal_price">Verlengprijs (€)</label>
                                <input type="number" step="0.01" min="0" id="renewal_price" name="renewal_price" value="{{ old('renewal_price') }}" class="form-input" required>
                            </div>
                            <div>
                                <label class="form-label" for="transfer_price">Transferprijs (€)</label>
                                <input type="number" step="0.01" min="0" id="transfer_price" name="transfer_price" value="{{ old('transfer_price') }}" class="form-input">
                            </div>
                            <div>
                                <label class="form-label" for="cost_price">Inkoopprijs (€)</label>
                                <input type="number" step="0.01" min="0" id="cost_price" name="cost_price" value="{{ old('cost_price') }}" class="form-input">
                            </div>
                            <div>
                                <label class="form-label" for="sort_order">Sortering</label>
                                <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" class="form-input">
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="is_active" name="is_active" value="1" class="h-4 w-4 rounded border-slate-300 text-primary-600" {{ old('is_active', '1') ? 'checked' : '' }}>
                                <label for="is_active" class="text-sm text-slate-700">Actief</label>
                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="btn btn-primary w-full">Toevoegen</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Extensie</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase">Registratie</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase">Verlenging</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase">Transfer</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-slate-500 uppercase">Actie</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                @forelse($tlds as $tld)
                                    <tr>
                                        <form action="{{ route('admin.settings.domains.tlds.update', $tld) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <td class="px-4 py-3 align-top">
                                                <input type="text" name="extension" value="{{ $tld->extension }}" class="form-input text-sm w-24" required>
                                            </td>
                                            <td class="px-4 py-3 align-top">
                                                <select name="is_active" class="form-input text-sm">
                                                    <option value="1" {{ $tld->is_active ? 'selected' : '' }}>Actief</option>
                                                    <option value="0" {{ ! $tld->is_active ? 'selected' : '' }}>Inactief</option>
                                                </select>
                                            </td>
                                            <td class="px-4 py-3 align-top">
                                                <input type="number" step="0.01" min="0" name="registration_price" value="{{ number_format((float) $tld->registration_price, 2, '.', '') }}" class="form-input text-sm w-28 text-right" required>
                                            </td>
                                            <td class="px-4 py-3 align-top">
                                                <input type="number" step="0.01" min="0" name="renewal_price" value="{{ number_format((float) $tld->renewal_price, 2, '.', '') }}" class="form-input text-sm w-28 text-right" required>
                                            </td>
                                            <td class="px-4 py-3 align-top">
                                                <input type="number" step="0.01" min="0" name="transfer_price" value="{{ $tld->transfer_price ? number_format((float) $tld->transfer_price, 2, '.', '') : '' }}" class="form-input text-sm w-28 text-right">
                                            </td>
                                            <td class="px-4 py-3 align-top">
                                                <div class="flex items-center justify-center gap-2">
                                                    <input type="hidden" name="cost_price" value="{{ $tld->cost_price ? number_format((float) $tld->cost_price, 2, '.', '') : '' }}">
                                                    <input type="hidden" name="sort_order" value="{{ $tld->sort_order }}">
                                                    <button type="submit" class="text-sm font-medium text-primary-600 hover:text-primary-800">Opslaan</button>
                                                    <button type="button" onclick="if(confirm('TLD verwijderen?')) { document.getElementById('delete-tld-{{ $tld->id }}').submit(); }" class="text-sm font-medium text-red-600 hover:text-red-800">Verwijderen</button>
                                                </div>
                                            </td>
                                        </form>
                                        <form id="delete-tld-{{ $tld->id }}" action="{{ route('admin.settings.domains.tlds.destroy', $tld) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">Nog geen TLD's ingesteld.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
