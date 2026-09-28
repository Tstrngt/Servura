@php
$domainMode = $primaryItem['domain_mode'] ?? 'register';
$domainValue = $primaryItem['domain'] ?? '';
$authCode = $primaryItem['auth_code'] ?? '';
@endphp
<section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200" x-data="{ mode: @js($domainMode), query: @js($domainValue), authCode: @js($authCode), results: [], loading: false, error: '', selected: null }">
    <h2 class="text-lg font-semibold text-slate-900">Domein voor uw hosting</h2>

    <div class="mt-4 flex gap-2 rounded-xl bg-slate-100 p-1 text-sm font-medium">
        <button type="button" @click="mode = 'register'; query = ''; results = []; selected = null; error = ''" :class="mode === 'register' ? 'bg-white text-primary-700 shadow' : 'text-slate-600 hover:text-slate-900'" class="flex-1 rounded-lg px-3 py-2 transition">Nieuw domein</button>
        <button type="button" @click="mode = 'existing'; query = ''; results = []; selected = null; error = ''" :class="mode === 'existing' ? 'bg-white text-primary-700 shadow' : 'text-slate-600 hover:text-slate-900'" class="flex-1 rounded-lg px-3 py-2 transition">Bestaand domein</button>
        <button type="button" @click="mode = 'transfer'; query = ''; results = []; selected = null; error = ''" :class="mode === 'transfer' ? 'bg-white text-primary-700 shadow' : 'text-slate-600 hover:text-slate-900'" class="flex-1 rounded-lg px-3 py-2 transition">Verhuizen</button>
    </div>

    <form x-show="mode === 'register'" class="mt-5" @submit.prevent="
        if (!query.trim()) return;
        loading = true; error = ''; results = []; selected = null;
        fetch('{{ route('api.domains.check') }}?name=' + encodeURIComponent(query.trim()))
            .then(r => r.json())
            .then(data => { results = data.results || []; })
            .catch(() => { error = 'Er ging iets mis. Probeer het later opnieuw.'; })
            .finally(() => { loading = false; });
    ">
        <div class="flex flex-col sm:flex-row gap-3">
            <input type="text" x-model="query" placeholder="jouwbedrijf" class="flex-1 rounded-xl border-slate-200 px-4 py-3 text-slate-900 shadow-sm focus:border-primary-500 focus:ring-primary-500">
            <button type="submit" :disabled="loading || !query.trim()" class="btn btn-primary px-6 py-3 disabled:opacity-60">
                <span x-show="!loading">Zoeken</span>
                <span x-show="loading" class="flex items-center gap-2"><svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Bezig...</span>
            </button>
        </div>
        <p x-show="error" x-text="error" x-transition class="mt-3 text-sm text-red-600"></p>

        <div x-show="results.length" x-cloak class="mt-5 divide-y divide-slate-100 rounded-xl border border-slate-200 bg-white">
            <template x-for="result in results" :key="result.domain">
                <div class="flex items-center justify-between gap-3 p-3" :class="selected === result.domain ? 'bg-primary-50' : ''">
                    <div>
                        <span class="font-medium text-slate-900" x-text="result.domain"></span>
                        <span class="ml-2 inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide" :class="result.available ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'" x-text="result.available ? 'Beschikbaar' : 'Bezet'"></span>
                        <p x-show="result.available && result.price" class="text-xs text-slate-500" x-text="'€ ' + result.price + ' / jaar'"></p>
                    </div>
                    <template x-if="result.available">
                        <form action="{{ route('checkout.cart.set-domain') }}" method="POST" @submit="selected = result.domain">
                            @csrf
                            <input type="hidden" name="index" value="0">
                            <input type="hidden" name="domain_mode" value="register">
                            <input type="hidden" name="domain" :value="result.domain">
                            <button type="submit" class="btn btn-primary btn-sm px-3 py-1.5">Kiezen</button>
                        </form>
                    </template>
                </div>
            </template>
        </div>
        <p x-show="results.length && !results.some(r => r.available)" class="mt-3 text-sm text-slate-600">Helaas, geen beschikbare varianten gevonden. Probeer een andere naam.</p>
    </form>

    <form x-show="mode === 'existing'" action="{{ route('checkout.cart.set-domain') }}" method="POST" class="mt-5 space-y-4">
        @csrf
        <input type="hidden" name="index" value="0">
        <input type="hidden" name="domain_mode" value="existing">
        <div>
            <label class="form-label" for="existing_domain">Uw bestaande domeinnaam</label>
            <input type="text" id="existing_domain" name="domain" x-model="query" value="{{ old('domain', $domainValue) }}" placeholder="voorbeeld.nl" class="form-input w-full">
            <p class="mt-1 text-xs text-slate-500">Dit domein blijft bij uw huidige provider staan.</p>
        </div>
        <button type="submit" class="btn btn-primary">Domein gebruiken</button>
    </form>

    <form x-show="mode === 'transfer'" action="{{ route('checkout.cart.set-domain') }}" method="POST" class="mt-5 space-y-4">
        @csrf
        <input type="hidden" name="index" value="0">
        <input type="hidden" name="domain_mode" value="transfer">
        <div>
            <label class="form-label" for="transfer_domain">Domeinnaam die u wilt verhuizen</label>
            <input type="text" id="transfer_domain" name="domain" x-model="query" value="{{ old('domain', $domainValue) }}" placeholder="voorbeeld.nl" class="form-input w-full">
        </div>
        <div>
            <label class="form-label" for="auth_code">Verhuiscode (authcode) *</label>
            <input type="text" id="auth_code" name="auth_code" x-model="authCode" value="{{ old('auth_code', $authCode) }}" required class="form-input w-full">
            <p class="mt-1 text-xs text-slate-500">Voor .nl-domeinen is de verhuiscode verplicht. Deze ontvangt u van uw huidige provider.</p>
        </div>
        <button type="submit" class="btn btn-primary">Domein verhuizen</button>
    </form>

    @error('cart.0.domain')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
    @error('cart.0.auth_code')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
</section>
