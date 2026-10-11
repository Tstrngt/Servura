@extends('layouts.app')

@section('title', 'Domeinprijzen - Servura Admin')

@section('content')
@include('admin.partials.sidebar')
<div class="min-h-screen bg-slate-50 lg:pl-64">
    <div class="mx-auto w-full max-w-[1600px] px-4 py-4 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-4 py-4 lg:flex-row lg:items-end lg:justify-between">
            <div><h1 class="text-2xl font-bold text-slate-900">Domeinprijzen</h1><p class="mt-1 text-sm text-slate-600">Openprovider-inkoopprijzen met automatische opslag en promotieverwerking.</p></div>
            <div class="flex gap-3">
                <form method="POST" action="{{ route('admin.settings.domains.tlds.sync-prices') }}">@csrf<button class="btn btn-primary" type="submit">Nu synchroniseren</button></form>
            </div>
        </div>
        @include('admin.partials.settings-nav')
        @if(session('success'))<div class="mb-4 rounded-xl bg-emerald-50 p-4 text-sm text-emerald-800 ring-1 ring-emerald-200">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="mb-4 rounded-xl bg-red-50 p-4 text-sm text-red-800 ring-1 ring-red-200"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

        <div class="mb-6 grid gap-4 lg:grid-cols-[1fr_360px]">
            <div class="rounded-2xl bg-slate-950 p-5 text-white shadow-xl shadow-slate-900/10">
                <div class="grid gap-4 sm:grid-cols-3">
                    <div><div class="text-xs font-medium text-slate-400">Omgeving</div><div class="mt-1 font-semibold">{{ ucfirst($settings['environment']) }}</div></div>
                    <div><div class="text-xs font-medium text-slate-400">Laatste synchronisatie</div><div class="mt-1 font-semibold">{{ $settings['last_sync_at'] ?: 'Nog niet uitgevoerd' }}</div></div>
                    <div><div class="text-xs font-medium text-slate-400">Status</div><div class="mt-1 font-semibold">{{ $settings['last_sync_status'] }}</div></div>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.settings.domains.tlds.pricing-settings') }}" class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                @csrf @method('PUT')
                <label class="form-label" for="markup_percentage">Automatische opslag</label>
                <div class="mt-2 flex items-center gap-3"><input id="markup_percentage" name="markup_percentage" type="number" min="0" max="500" step="0.01" value="{{ $settings['markup'] }}" class="form-input"><span class="font-semibold text-slate-500">%</span><button class="btn btn-outline" type="submit">Opslaan</button></div>
                <p class="mt-2 text-xs text-slate-500">Verkoopprijs excl. btw = actuele inkoopprijs plus opslag, naar boven afgerond op centen.</p>
            </form>
        </div>

        <div class="mb-5 flex flex-col gap-3 rounded-xl bg-white p-4 ring-1 ring-slate-200 sm:flex-row sm:items-end sm:justify-between">
            <form method="GET" class="flex flex-wrap items-end gap-3">
                <div><label class="form-label" for="status">Status</label><select id="status" name="status" class="form-input"><option value="">Alle</option><option value="active" @selected(request('status') === 'active')>Actief</option><option value="inactive" @selected(request('status') === 'inactive')>Inactief</option></select></div>
                <label class="flex items-center gap-2 pb-2 text-sm text-slate-700"><input type="checkbox" name="promotions" value="1" @checked(request()->boolean('promotions')) class="rounded border-slate-300 text-primary-600"> Alleen promoties</label>
                <label class="flex items-center gap-2 pb-2 text-sm text-slate-700"><input type="checkbox" name="errors" value="1" @checked(request()->boolean('errors')) class="rounded border-slate-300 text-primary-600"> Alleen fouten</label>
                <button class="btn btn-outline" type="submit">Filteren</button>
            </form>
            <details><summary class="cursor-pointer text-sm font-semibold text-primary-700">TLD toevoegen</summary><form method="POST" action="{{ route('admin.settings.domains.tlds.store') }}" class="mt-3 flex flex-wrap items-end gap-3">@csrf<div><label class="form-label">Extensie</label><input name="extension" placeholder=".nl" class="form-input" required></div><div><label class="form-label">Sortering</label><input name="sort_order" type="number" value="0" class="form-input w-24"></div><label class="flex items-center gap-2 pb-2 text-sm"><input type="checkbox" name="is_active" value="1" checked> Actief</label><button class="btn btn-primary" type="submit">Toevoegen</button></form></details>
        </div>

        <div class="space-y-4">
            @forelse($tlds as $tld)
                @php
                    $prices = $tld->providerPrices->keyBy('operation');
                    $create = $prices->get('create'); $renew = $prices->get('renew'); $transfer = $prices->get('transfer');
                    $promotion = $prices->first(fn($price) => $price->is_promotion);
                    $error = $prices->first(fn($price) => filled($price->sync_error));
                @endphp
                <form method="POST" action="{{ route('admin.settings.domains.tlds.update', $tld) }}" class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
                    @csrf @method('PUT')
                    <div class="grid gap-4 border-b border-slate-100 p-5 lg:grid-cols-[150px_1fr_auto] lg:items-center">
                        <div class="flex items-center gap-3"><input name="extension" value="{{ $tld->extension }}" class="form-input w-24 font-semibold" required><select name="is_active" class="form-input"><option value="1" @selected($tld->is_active)>Actief</option><option value="0" @selected(!$tld->is_active)>Inactief</option></select></div>
                        <div class="text-sm text-slate-500">@if($promotion)<span class="font-semibold text-emerald-700">Actieve promotie</span>@if($promotion->promotion_ends_at) tot {{ $promotion->promotion_ends_at->format('d-m-Y H:i') }}@endif @else Geen actieve promotie @endif</div>
                        <div class="flex gap-2"><input type="hidden" name="sort_order" value="{{ $tld->sort_order }}"><button class="btn btn-primary text-sm" type="submit">Opslaan</button><button type="button" onclick="if(confirm('TLD verwijderen?')) document.getElementById('delete-tld-{{ $tld->id }}').submit()" class="btn btn-outline text-sm text-red-700">Verwijderen</button></div>
                    </div>
                    <div class="overflow-x-auto"><table class="min-w-full text-sm"><thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-5 py-3">Bewerking</th><th class="px-5 py-3">Inkoop</th><th class="px-5 py-3">Regulier</th><th class="px-5 py-3">Verkoop</th><th class="px-5 py-3">Handmatige override</th><th class="px-5 py-3">Synchronisatie</th></tr></thead><tbody class="divide-y divide-slate-100">
                        @foreach(['create' => 'Registratie', 'renew' => 'Verlenging', 'transfer' => 'Transfer'] as $operation => $label)
                            @php $price = $prices->get($operation); @endphp
                            <tr><td class="px-5 py-4 font-semibold text-slate-900">{{ $label }}</td><td class="px-5 py-4 tabular-nums">{{ $price?->effective_cost !== null ? $price->currency.' '.number_format((float)$price->effective_cost, 4, ',', '.') : '-' }}</td><td class="px-5 py-4 tabular-nums">{{ $price?->regular_cost !== null ? $price->currency.' '.number_format((float)$price->regular_cost, 4, ',', '.') : '-' }}</td><td class="px-5 py-4 font-semibold tabular-nums">{{ $price?->sale_price !== null ? '€ '.number_format($price->sale_price, 2, ',', '.') : '-' }}</td><td class="px-5 py-4"><input type="number" min="0" step="0.01" name="override_{{ $operation }}" value="{{ $price?->override_sale_price }}" placeholder="Automatisch" class="form-input w-36"></td><td class="px-5 py-4 text-xs text-slate-500">{{ $price?->synced_at?->format('d-m-Y H:i') ?? 'Nog niet' }}</td></tr>
                        @endforeach
                    </tbody></table></div>
                    @if($error)<div class="border-t border-red-100 bg-red-50 px-5 py-3 text-sm text-red-800">{{ $error->operation }}: {{ $error->sync_error }}</div>@endif
                </form>
                <form id="delete-tld-{{ $tld->id }}" method="POST" action="{{ route('admin.settings.domains.tlds.destroy', $tld) }}" class="hidden">@csrf @method('DELETE')</form>
            @empty
                <div class="rounded-2xl bg-white p-10 text-center text-sm text-slate-500 ring-1 ring-slate-200">Geen TLD's gevonden voor deze filters.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
