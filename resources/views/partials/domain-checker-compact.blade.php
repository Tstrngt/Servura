<div x-data="{ query: '', loading: false, result: null, error: '' }">
    <form @submit.prevent="
        if (!query.trim()) return;
        loading = true;
        error = '';
        result = null;
        fetch('{{ route('api.domains.check') }}?name=' + encodeURIComponent(query.trim()))
            .then(r => r.json())
            .then(data => { result = data; })
            .catch(() => { error = 'Er ging iets mis. Probeer het later opnieuw.'; })
            .finally(() => { loading = false; });
    " class="flex flex-col sm:flex-row gap-3">
        <label for="domain-checker-name" class="sr-only">Bedrijfsnaam of domeinnaam</label>
        <input type="text" id="domain-checker-name" x-model="query" placeholder="jouwbedrijf" class="flex-1 rounded-xl border-0 bg-white px-5 py-3.5 text-slate-900 placeholder:text-slate-400 ring-1 ring-white/20 focus:ring-2 focus:ring-white" autocomplete="off">
        <button type="submit" :disabled="loading || !query.trim()" class="btn btn-light px-6 py-3.5 disabled:opacity-60 disabled:cursor-not-allowed">
            <span x-show="!loading">Zoeken</span>
            <span x-show="loading" class="flex items-center gap-2">
                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Bezig...
            </span>
        </button>
    </form>

    <div class="mt-5">
        <template x-if="result && result.results">
            <div x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="rounded-2xl bg-white p-5 text-slate-900 shadow-xl ring-1 ring-white/20">
                <p class="text-sm text-slate-500 mb-3">Resultaten voor <span class="font-semibold text-slate-700" x-text="result.name"></span></p>

                <div class="space-y-2">
                    <template x-for="item in result.results" :key="item.domain">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 rounded-xl bg-slate-50 p-3 ring-1 ring-slate-100">
                            <div>
                                <span class="font-bold font-heading break-all block" x-text="item.domain"></span>
                                <span class="text-sm text-slate-500" x-show="item.available && item.price">€ <span x-text="item.price"></span> per jaar</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="shrink-0 inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold"
                                      :class="item.available ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20' : 'bg-rose-50 text-rose-700 ring-1 ring-rose-600/20'">
                                    <span x-text="item.available ? 'Beschikbaar' : 'Niet beschikbaar'"></span>
                                </span>
                                <a x-show="item.available && item.checkout_url" :href="item.checkout_url" class="btn btn-primary btn-sm px-3 py-1.5 text-xs">
                                    Bestellen
                                </a>
                            </div>
                        </div>
                    </template>
                </div>

                <p class="mt-3 text-xs text-slate-500">
                    Deze check is een indicatie. Er wordt pas besteld en geregistreerd na een succesvolle betaling.
                </p>
            </div>
        </template>

        <template x-if="error">
            <div x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="rounded-2xl bg-red-50 p-4 text-red-800 ring-1 ring-red-200">
                <p x-text="error"></p>
            </div>
        </template>
    </div>
</div>
