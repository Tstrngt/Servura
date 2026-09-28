@extends('layouts.app')

@section('title', 'Domeinchecker - Servura')
@section('meta-description', 'Controleer eenvoudig of een domeinnaam nog beschikbaar is.')

@section('content')
<section class="relative bg-slate-900 py-20 lg:py-28 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="font-heading text-4xl md:text-5xl font-bold mb-4">Domeinchecker</h1>
            <p class="text-lg text-white/80 max-w-2xl mx-auto">Controleer of je gewenste domeinnaam nog vrij is.</p>
        </div>

        <div x-data="{ query: '', loading: false, result: null, error: '' }" class="mx-auto max-w-2xl">
            <form @submit.prevent="
                if (!query.trim()) return;
                loading = true;
                error = '';
                result = null;
                fetch('{{ route('api.domains.check') }}?domain=' + encodeURIComponent(query.trim()))
                    .then(r => r.json())
                    .then(data => { result = data; })
                    .catch(() => { error = 'Er ging iets mis. Probeer het later opnieuw.'; })
                    .finally(() => { loading = false; });
            " class="flex flex-col sm:flex-row gap-3">
                <label for="domain" class="sr-only">Domeinnaam</label>
                <input type="text" id="domain" x-model="query" placeholder="jouwbedrijf.nl" class="flex-1 rounded-xl border-0 bg-white/10 px-5 py-3.5 text-white placeholder:text-white/50 ring-1 ring-white/20 focus:ring-2 focus:ring-primary-400" autocomplete="off">
                <button type="submit" :disabled="loading || !query.trim()" class="btn btn-primary px-8 py-3.5 disabled:opacity-60 disabled:cursor-not-allowed">
                    <span x-show="!loading">Zoeken</span>
                    <span x-show="loading" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Bezig...
                    </span>
                </button>
            </form>

            <div class="mt-8">
                <template x-if="result">
                    <div class="rounded-2xl bg-white p-6 text-slate-900 shadow-xl ring-1 ring-slate-200">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <p class="text-sm text-slate-500">Domeinnaam</p>
                                <p class="text-2xl font-bold font-heading break-all" x-text="result.domain"></p>
                            </div>
                            <span class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold"
                                  :class="result.available ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20' : 'bg-rose-50 text-rose-700 ring-1 ring-rose-600/20'">
                                <template x-if="result.available">Beschikbaar</template>
                                <template x-if="!result.available">Niet beschikbaar</template>
                            </span>
                        </div>
                        <p class="mt-4 text-sm text-slate-600">
                            Deze check is een indicatie. Er wordt in deze fase nog niets geregistreerd of besteld.
                        </p>
                    </div>
                </template>

                <template x-if="error">
                    <div class="rounded-2xl bg-red-50 p-5 text-red-800 ring-1 ring-red-200">
                        <p x-text="error"></p>
                    </div>
                </template>
            </div>
        </div>
    </div>
</section>
@endsection
