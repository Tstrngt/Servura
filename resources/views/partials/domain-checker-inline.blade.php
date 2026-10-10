<section class="relative bg-slate-50 py-16 lg:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <span class="inline-flex items-center rounded-full bg-primary-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-primary-700">Domeinchecker</span>
            <h2 class="mt-3 font-heading text-3xl md:text-4xl font-bold text-slate-900">Is uw domein nog vrij?</h2>
            <p class="mt-2 text-slate-600">Vul een naam in en zie direct welke extensies beschikbaar zijn.</p>
        </div>

        <div x-data="{ query: '', loading: false, result: null, error: '' }"
             class="relative mx-auto max-w-5xl lg:transition-[min-height] lg:duration-[1250ms] lg:[transition-timing-function:cubic-bezier(0.16,1,0.3,1)] motion-reduce:transition-none"
             :class="loading || (result && result.results) ? 'lg:min-h-[22rem]' : (error ? 'lg:min-h-[8rem]' : 'lg:min-h-[3.25rem]')">
            <div class="mx-auto max-w-xl transition-transform duration-[1250ms] motion-reduce:transition-none lg:absolute lg:inset-x-0 lg:top-1/2 lg:-translate-y-1/2"
                 :class="result && result.results ? 'lg:-translate-x-[17rem]' : 'lg:translate-x-0'">
                <form @submit.prevent="
                    if (!query.trim()) return;
                    loading = true;
                    error = '';
                    result = null;
                    fetch('{{ route('api.domains.check') }}?name=' + encodeURIComponent(query.trim()))
                        .then(async r => { const data = await r.json(); if (!r.ok) throw new Error(data.message || 'De domeincontrole kon niet worden uitgevoerd.'); return data; })
                        .then(data => { result = data; })
                        .catch(e => { error = e.message || 'Er ging iets mis. Probeer het later opnieuw.'; })
                        .finally(() => { loading = false; });
                " class="flex flex-col gap-3 sm:flex-row">
                    <label for="domain-checker-name" class="sr-only">Bedrijfsnaam of domeinnaam</label>
                    <input type="text" id="domain-checker-name" x-model="query" placeholder="jouwbedrijf" class="flex-1 rounded-xl border border-slate-200 bg-white px-5 py-3.5 text-slate-900 shadow-sm ring-1 ring-slate-200 placeholder:text-slate-400 focus:border-primary-500 focus:ring-primary-500" autocomplete="off">
                    <button type="submit" :disabled="loading || !query.trim()" class="btn btn-primary px-6 py-3.5 transition-transform duration-150 active:scale-[0.97] disabled:cursor-not-allowed disabled:opacity-60">
                        <span x-show="!loading">Zoeken</span>
                        <span x-show="loading" class="flex items-center gap-2">
                            <svg class="h-5 w-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Bezig...
                        </span>
                    </button>
                </form>

                <template x-if="error">
                    <div x-transition.opacity.duration.200ms x-cloak class="mt-4 rounded-xl bg-red-50 p-4 text-sm text-red-800 ring-1 ring-red-200">
                        <p x-text="error"></p>
                    </div>
                </template>
            </div>

            <template x-if="result && result.results">
                <div x-transition:enter="transition duration-[750ms] [transition-timing-function:cubic-bezier(0.16,1,0.3,1)] motion-reduce:transition-opacity"
                     x-transition:enter-start="opacity-0 translate-x-6 motion-reduce:translate-x-0"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     x-cloak
                     class="mt-6 rounded-2xl bg-white p-4 text-slate-900 shadow-lg ring-1 ring-slate-200 lg:absolute lg:right-0 lg:top-1/2 lg:mt-0 lg:w-[29rem] lg:-translate-y-1/2">
                    <p class="mb-2 text-sm text-slate-500">Resultaten voor <span class="font-semibold text-slate-700" x-text="result.name"></span></p>

                    <ul class="divide-y divide-slate-100">
                        <template x-for="item in result.results" :key="item.domain">
                            <li class="flex items-center justify-between gap-3 py-1.5">
                                <div class="flex min-w-0 items-baseline gap-1.5">
                                    <span class="truncate text-sm font-semibold text-slate-900" x-text="item.domain"></span>
                                    <span class="shrink-0 text-[10px] text-slate-500" x-show="item.available && item.price" x-text="'€ ' + item.price + '/jr'"></span>
                                </div>
                                <div class="flex shrink-0 items-center gap-1.5">
                                    <span class="inline-flex rounded-full px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wide"
                                          :class="item.available ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'"
                                          x-text="item.available ? 'Beschikbaar' : 'Bezet'">
                                    </span>
                                    <a x-show="item.available && item.checkout_url" :href="item.checkout_url" class="inline-flex items-center justify-center rounded-full bg-primary-600 px-2.5 py-1.5 text-[14px] font-semibold leading-none text-white shadow-sm transition-[background-color,transform] duration-150 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-1 active:scale-[0.97]">
                                        Bestel
                                    </a>
                                    <a x-show="!item.available && item.transfer_url" :href="item.transfer_url" class="inline-flex items-center justify-center rounded-full bg-sky-100 px-2.5 py-1.5 text-[12px] font-semibold leading-none text-sky-800 transition-colors hover:bg-sky-200">
                                        Verhuizen
                                    </a>
                                </div>
                            </li>
                        </template>
                    </ul>

                    <p class="mt-2 text-[10px] leading-snug text-slate-400">
                        Indicatie. Er wordt pas besteld na een succesvolle betaling.
                    </p>
                </div>
            </template>
        </div>
    </div>
</section>
