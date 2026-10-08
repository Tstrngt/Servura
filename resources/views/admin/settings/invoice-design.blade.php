@extends('layouts.app')
@section('title', 'Factuurontwerp - Servura Admin')
@section('content')
@include('admin.partials.sidebar')
<main class="min-h-screen bg-slate-50 lg:pl-64">
<div class="mx-auto max-w-[1600px] px-4 py-8 sm:px-6 lg:px-8">
    <div class="mb-7 flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
        <div><p class="text-sm font-semibold text-sky-700">Instellingen / Facturatie</p><h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">Factuurontwerp</h1><p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Beoordeel en configureer de nieuwe factuur. Dit concept wordt pas gebruikt nadat je het zelf publiceert.</p></div>
        <div class="flex flex-wrap items-center gap-3"><span class="rounded-lg bg-amber-50 px-3 py-2 text-sm font-semibold text-amber-800 ring-1 ring-amber-200">Concept v{{ $draft->version }}</span><a class="btn btn-outline" href="{{ route('admin.financial.billing-settings.edit') }}">Facturatie-instellingen</a></div>
    </div>

    @if(session('success'))<div class="mb-6 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-800 ring-1 ring-emerald-200">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="mb-6 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-800 ring-1 ring-red-200"><strong>Controleer de invoer.</strong><ul class="mt-1 list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

    <div class="grid gap-7 xl:grid-cols-[390px_minmax(0,1fr)]">
        <aside class="space-y-6">
            <form method="POST" action="{{ route('admin.financial.invoice-design.update') }}" class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                @csrf @method('PUT')
                <h2 class="text-lg font-semibold text-slate-950">Ontwerpinstellingen</h2><p class="mt-1 text-sm text-slate-500">Wijzigingen blijven in concept.</p>
                <div class="mt-6 space-y-5">
                    <div><label class="form-label" for="logo_variant">Logo op factuur</label><select class="form-input" id="logo_variant" name="logo_variant"><option value="dark" @selected($settings['logo_variant']==='dark')>Donkerblauw op wit</option><option value="light" @selected($settings['logo_variant']==='light')>Wit op donker</option></select></div>
                    <div class="grid grid-cols-2 gap-4"><div><label class="form-label" for="primary_color">Donkerblauw</label><input class="form-input h-11" id="primary_color" type="color" name="primary_color" value="{{ $settings['primary_color'] }}"></div><div><label class="form-label" for="accent_color">Accentkleur</label><input class="form-input h-11" id="accent_color" type="color" name="accent_color" value="{{ $settings['accent_color'] }}"></div></div>
                    <div><label class="form-label" for="iban">IBAN</label><input class="form-input" id="iban" name="iban" value="{{ old('iban', $settings['iban']) }}" placeholder="NL00 BANK 0000 0000 00"></div>
                    <div><label class="form-label" for="account_holder">Rekeninghouder</label><input class="form-input" id="account_holder" name="account_holder" value="{{ old('account_holder', $settings['account_holder']) }}"></div>
                    <div><label class="form-label" for="payment_text">Betaaltekst</label><textarea class="form-input" id="payment_text" name="payment_text" rows="3">{{ old('payment_text', $settings['payment_text']) }}</textarea></div>
                    <div><label class="form-label" for="footer_text">Factuurfooter</label><textarea class="form-input" id="footer_text" name="footer_text" rows="3">{{ old('footer_text', $settings['footer_text']) }}</textarea></div>
                </div>
                <button class="btn btn-primary mt-6 w-full" type="submit">Concept opslaan</button>
            </form>

            <section class="rounded-2xl bg-slate-950 p-6 text-white">
                <h2 class="text-lg font-semibold">Officiële logo’s</h2><p class="mt-1 text-sm leading-6 text-slate-300">Beide varianten komen uit het centrale assetbeheer.</p>
                <div class="mt-5 space-y-3"><div class="rounded-xl bg-white p-4"><img src="{{ asset('images/servura-logo-dark.png') }}" alt="Servura donkerblauw logo" class="h-auto w-full"></div><div class="rounded-xl bg-[#071d3b] p-4 ring-1 ring-white/15"><img src="{{ asset('images/servura-logo-light.png') }}" alt="Servura wit logo" class="h-auto w-full"></div></div>
            </section>
        </aside>

        <section class="min-w-0 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between"><div><h2 class="text-lg font-semibold text-slate-950">PDF-preview</h2><p class="mt-1 text-sm text-slate-500">De watermerk-preview heeft geen invloed op bestaande facturen.</p></div><div class="flex flex-wrap gap-2"><select id="preview-scenario" class="form-input min-w-52">@foreach($scenarios as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach</select><a id="preview-download" class="btn btn-outline whitespace-nowrap" href="{{ route('admin.financial.invoice-design.preview', ['scenario'=>'actual','download'=>1]) }}">PDF downloaden</a></div></div>
            <div class="overflow-hidden rounded-xl bg-slate-200 ring-1 ring-slate-300"><iframe id="invoice-preview" title="Voorbeeld van het conceptfactuurontwerp" class="h-[1050px] w-full bg-white" src="{{ route('admin.financial.invoice-design.preview', ['scenario'=>'actual']) }}"></iframe></div>
        </section>
    </div>

    <section class="mt-7 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between"><div><h2 class="text-lg font-semibold text-slate-950">Publicatie</h2><p class="mt-1 max-w-3xl text-sm leading-6 text-slate-600">Publiceren activeert dit ontwerp alleen voor facturen die daarna definitief worden uitgegeven. Bestaande facturen behouden hun gekoppelde versie.</p></div><form method="POST" action="{{ route('admin.financial.invoice-design.publish') }}" onsubmit="return confirm('Factuurontwerp v{{ $draft->version }} publiceren voor nieuwe facturen?')">@csrf<button class="btn btn-primary whitespace-nowrap" type="submit">Factuurontwerp publiceren</button></form></div>
        @if($versions->isNotEmpty())<div class="mt-6 overflow-x-auto"><table class="min-w-full text-sm"><thead><tr class="border-b border-slate-200 text-left text-xs uppercase tracking-wide text-slate-500"><th class="py-3">Versie</th><th>Status</th><th>Gepubliceerd</th><th class="text-right">Actie</th></tr></thead><tbody>@foreach($versions as $version)<tr class="border-b border-slate-100"><td class="py-4 font-semibold">v{{ $version->version }}</td><td>{{ ucfirst($version->status) }}</td><td>{{ $version->published_at?->format('d-m-Y H:i') ?: '-' }}</td><td class="text-right">@if($version->status === 'archived')<form method="POST" action="{{ route('admin.financial.invoice-design.restore', $version) }}">@csrf<button class="text-sm font-semibold text-sky-700 hover:text-sky-900">Deze versie herstellen</button></form>@else<span class="text-emerald-700">Actief</span>@endif</td></tr>@endforeach</tbody></table></div>@endif
    </section>
</div>
</main>
<script>
const scenario = document.getElementById('preview-scenario');
const frame = document.getElementById('invoice-preview');
const download = document.getElementById('preview-download');
scenario.addEventListener('change', () => {
    const base = @json(route('admin.financial.invoice-design.preview'));
    frame.src = `${base}?scenario=${encodeURIComponent(scenario.value)}`;
    download.href = `${base}?scenario=${encodeURIComponent(scenario.value)}&download=1`;
});
</script>
@endsection
