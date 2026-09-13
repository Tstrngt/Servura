@extends('layouts.app')

@section('title', 'Offerte samenstellen - Servura')
@section('meta-description', 'Stel eenvoudig uw offerte samen voor een nieuwe website, webshop of online platform bij Servura.')
@section('meta-keywords', 'offerte, website offerte, offerte samenstellen, webdesign offerte')

@section('content')
<!-- Hero Section -->
<section class="relative -mt-16 pt-16 overflow-hidden bg-slate-950 text-white" data-navbar-theme="dark">
    <div class="absolute inset-0 opacity-40 pointer-events-none" style="background-image: radial-gradient(rgba(255,255,255,0.06) 1px, transparent 1px); background-size: 32px 32px;"></div>
    <div class="absolute top-1/4 -left-20 w-[28rem] h-[28rem] rounded-full bg-primary-600/15 blur-3xl"></div>
    <div class="absolute bottom-0 right-0 w-[30rem] h-[30rem] rounded-full bg-accent-500/10 blur-3xl"></div>

    <div class="relative max-w-7xl mx-auto px-6 py-24 lg:py-32">
        <div class="max-w-2xl animate-slide-up">
            <h1 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold leading-[1.05] tracking-tight mb-6">
                Offerte samenstellen
            </h1>
            <p class="text-lg md:text-xl text-white/80 leading-relaxed max-w-xl">
                Geef uw wensen door en ontdek binnen 48 uur wat wij voor u kunnen betekenen. Geen verplichtingen.
            </p>
        </div>
    </div>
</section>

<!-- Quote Builder Form -->
<section class="relative py-24 lg:py-32 bg-slate-50 overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[44rem] h-[22rem] bg-primary-400/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-6">
        @if(session('success') || request('success'))
            <div class="max-w-4xl mx-auto mb-10">
                <div class="bg-emerald-50 border-l-4 border-emerald-400 p-5 rounded-r-xl flex items-start gap-3">
                    <svg class="h-6 w-6 text-emerald-500 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h3 class="font-heading font-semibold text-emerald-800">Bedankt voor uw aanvraag</h3>
                        <p class="text-emerald-700 mt-1">We hebben uw offerte-aanvraag ontvangen en nemen binnen 48 uur contact met u op.</p>
                    </div>
                </div>
            </div>
        @endif

        <form x-data="quoteBuilder()" @submit.prevent="submit($event)" action="{{ route('quote.builder.store') }}" method="POST" class="max-w-4xl mx-auto">
            @csrf

            <!-- Doel -->
            <div class="mb-14 animate-on-scroll">
                <div class="flex items-start gap-4 mb-6">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-100 text-primary-600 font-heading font-bold text-sm shrink-0">1</span>
                    <div>
                        <h2 class="font-heading text-2xl font-bold text-slate-900">Wat is het doel van uw website?</h2>
                        <p class="text-slate-500 mt-1">Eenvoudige brochurewebsites beginnen vaak rond € 1.500 tot € 3.000; lead-generatiesites rond € 3.000 tot € 6.000.</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @php
                        $goals = [
                            ['id' => 'goal_brochure', 'value' => 'Visitekaartje / online brochure', 'label' => 'Visitekaartje / online brochure', 'note' => 'Informatie over uw bedrijf tonen'],
                            ['id' => 'goal_leads', 'value' => 'Meer leads en aanvragen', 'label' => 'Meer leads en aanvragen', 'note' => 'Bezoekers omzetten in contactaanvragen'],
                            ['id' => 'goal_sales', 'value' => 'Producten of diensten verkopen', 'label' => 'Producten of diensten verkopen', 'note' => 'Webshop of boekingssysteem'],
                            ['id' => 'goal_service', 'value' => 'Service richting klanten', 'label' => 'Service richting klanten', 'note' => 'Klantenportaal of informatiehub'],
                        ];
                    @endphp
                    @foreach($goals as $goal)
                        <label class="relative flex items-start gap-4 rounded-2xl bg-white p-5 ring-1 ring-slate-200 shadow-sm cursor-pointer hover:shadow-md hover:-translate-y-0.5 transition-all has-[:checked]:ring-primary-500 has-[:checked]:bg-primary-50/30">
                            <input type="radio" name="goal" value="{{ $goal['value'] }}" class="mt-1 h-4 w-4 text-primary-600 border-slate-300 focus:ring-primary-500" required>
                            <div>
                                <span class="font-semibold text-slate-900 block">{{ $goal['label'] }}</span>
                                <span class="text-sm text-slate-500">{{ $goal['note'] }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Pagina's + bezoekers -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-14 animate-on-scroll items-stretch">
                <div class="h-full flex flex-col">
                    <div class="flex items-start gap-4 mb-4 flex-1">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-100 text-primary-600 font-heading font-bold text-sm shrink-0">2</span>
                        <div>
                            <h2 class="font-heading text-xl font-bold text-slate-900">Hoeveel pagina's verwacht u?</h2>
                            <p class="text-sm text-slate-500 mt-1">Per extra pagina rekenen veel bureaus € 150 tot € 400.</p>
                        </div>
                    </div>
                    <select name="pages" class="form-input mt-auto" required>
                        <option value="">Kies een optie</option>
                        <option value="1-5">1 - 5 pagina's</option>
                        <option value="6-10">6 - 10 pagina's</option>
                        <option value="11-20">11 - 20 pagina's</option>
                        <option value="21-50">21 - 50 pagina's</option>
                        <option value="50+">Meer dan 50 pagina's</option>
                    </select>
                </div>

                <div class="h-full flex flex-col">
                    <div class="flex items-start gap-4 mb-4 flex-1">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-100 text-primary-600 font-heading font-bold text-sm shrink-0">3</span>
                        <div>
                            <h2 class="font-heading text-xl font-bold text-slate-900">Verwachte bezoekers per maand</h2>
                            <p class="text-sm text-slate-500 mt-1">Hoge traffic vraagt meer performance en hosting.</p>
                        </div>
                    </div>
                    <select name="visitors" class="form-input mt-auto" required>
                        <option value="">Kies een optie</option>
                        <option value="<1000">Minder dan 1.000</option>
                        <option value="1000-5000">1.000 - 5.000</option>
                        <option value="5000-25000">5.000 - 25.000</option>
                        <option value="25000+">Meer dan 25.000</option>
                    </select>
                </div>
            </div>

            <!-- Ontwerp + huidige website -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-14 animate-on-scroll items-stretch">
                <div class="h-full flex flex-col">
                    <div class="flex items-start gap-4 mb-4 flex-1">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-100 text-primary-600 font-heading font-bold text-sm shrink-0">4</span>
                        <div>
                            <h2 class="font-heading text-xl font-bold text-slate-900">Ontwerp & huisstijl</h2>
                            <p class="text-sm text-slate-500 mt-1">Een logo of huisstijl traject loopt vaak van € 750 tot € 2.500.</p>
                        </div>
                    </div>
                    <select name="design" class="form-input mt-auto" required>
                        <option value="">Kies een optie</option>
                        <option value="existing">Ik heb al een huisstijl / logo</option>
                        <option value="new">Ik wil een nieuw logo en huisstijl</option>
                        <option value="advice">Ik wil voorbeelden en advies</option>
                    </select>
                </div>

                <div class="h-full flex flex-col">
                    <div class="flex items-start gap-4 mb-4 flex-1">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-100 text-primary-600 font-heading font-bold text-sm shrink-0">5</span>
                        <div>
                            <h2 class="font-heading text-xl font-bold text-slate-900">Huidige website</h2>
                            <p class="text-sm text-slate-500 mt-1">Zodat wij kunnen zien wat er al is.</p>
                        </div>
                    </div>
                    <input type="text" name="current_website" class="form-input mt-auto" placeholder="www.voorbeeld.nl of 'nog geen website'">
                </div>
            </div>

            <!-- Functionaliteiten -->
            <div class="mb-14 animate-on-scroll">
                <div class="flex items-start gap-4 mb-6">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-100 text-primary-600 font-heading font-bold text-sm shrink-0">6</span>
                    <div>
                        <h2 class="font-heading text-2xl font-bold text-slate-900">Welke functionaliteiten heeft u nodig?</h2>
                        <p class="text-slate-500 mt-1">Webshops starten vaak vanaf € 5.000; maatwerk koppelingen lopen vaak vanaf € 1.000.</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @php
                        $features = [
                            ['id' => 'cms', 'label' => 'CMS (zelf beheren)', 'note' => 'Inbegrepen bij de meeste websites'],
                            ['id' => 'blog', 'label' => 'Blog / nieuws', 'note' => 'Vaak € 300 - € 800 extra'],
                            ['id' => 'forms', 'label' => 'Contact- / leadformulieren', 'note' => 'Standaard bij de meeste pakketten'],
                            ['id' => 'seo', 'label' => 'SEO-basis', 'note' => 'Vaak € 500 - € 1.500'],
                            ['id' => 'webshop', 'label' => 'Webshop / betalingen', 'note' => 'Vanaf € 5.000 bij de meeste bureaus'],
                            ['id' => 'multilingual', 'label' => 'Meertalig', 'note' => 'Vaak € 500 - € 1.500 per taal'],
                            ['id' => 'crm', 'label' => 'Koppeling CRM / ERP', 'note' => 'Vaak vanaf € 1.000'],
                            ['id' => 'portal', 'label' => 'Klantenportaal / login', 'note' => 'Vaak € 2.000 - € 5.000'],
                            ['id' => 'booking', 'label' => 'Afspraken systeem', 'note' => 'Vaak € 750 - € 2.000'],
                        ];
                    @endphp
                    @foreach($features as $feature)
                        <label class="relative flex items-start gap-3 rounded-2xl bg-white p-4 ring-1 ring-slate-200 shadow-sm cursor-pointer hover:shadow-md hover:-translate-y-0.5 transition-all has-[:checked]:ring-primary-500 has-[:checked]:bg-primary-50/30">
                            <input type="checkbox" name="features[]" value="{{ $feature['label'] }}" class="mt-1 h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                            <div>
                                <span class="font-semibold text-slate-900 text-sm block">{{ $feature['label'] }}</span>
                                <span class="text-xs text-slate-400">{{ $feature['note'] }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Content -->
            <div class="mb-14 animate-on-scroll">
                <div class="flex items-start gap-4 mb-6">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-100 text-primary-600 font-heading font-bold text-sm shrink-0">7</span>
                    <div>
                        <h2 class="font-heading text-2xl font-bold text-slate-900">Content & teksten</h2>
                        <p class="text-slate-500 mt-1">Teksten laten schrijven kost vaak € 50 tot € 150 per pagina; fotografie begint vaak vanaf € 500.</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <label class="relative flex items-start gap-3 rounded-2xl bg-white p-4 ring-1 ring-slate-200 shadow-sm cursor-pointer hover:shadow-md hover:-translate-y-0.5 transition-all has-[:checked]:ring-primary-500 has-[:checked]:bg-primary-50/30">
                        <input type="checkbox" name="content[]" value="Eigen teksten en beelden" class="mt-1 h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                        <span class="font-semibold text-slate-900 text-sm">Ik lever teksten en beelden zelf aan</span>
                    </label>
                    <label class="relative flex items-start gap-3 rounded-2xl bg-white p-4 ring-1 ring-slate-200 shadow-sm cursor-pointer hover:shadow-md hover:-translate-y-0.5 transition-all has-[:checked]:ring-primary-500 has-[:checked]:bg-primary-50/30">
                        <input type="checkbox" name="content[]" value="Teksten laten schrijven" class="mt-1 h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                        <span class="font-semibold text-slate-900 text-sm">Ik wil hulp bij teksten</span>
                    </label>
                    <label class="relative flex items-start gap-3 rounded-2xl bg-white p-4 ring-1 ring-slate-200 shadow-sm cursor-pointer hover:shadow-md hover:-translate-y-0.5 transition-all has-[:checked]:ring-primary-500 has-[:checked]:bg-primary-50/30">
                        <input type="checkbox" name="content[]" value="Fotografie / beelden" class="mt-1 h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                        <span class="font-semibold text-slate-900 text-sm">Ik wil fotografie / beelden</span>
                    </label>
                </div>
            </div>

            <!-- Tijdlijn + budget range -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-14 animate-on-scroll items-stretch">
                <div class="h-full flex flex-col">
                    <div class="flex items-start gap-4 mb-4 flex-1">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-100 text-primary-600 font-heading font-bold text-sm shrink-0">8</span>
                        <div>
                            <h2 class="font-heading text-xl font-bold text-slate-900">Gewenste oplevering</h2>
                            <p class="text-sm text-slate-500 mt-1">Snellere oplevering is vaak mogelijk tegen een kleine meerprijs.</p>
                        </div>
                    </div>
                    <select name="timeline" class="form-input mt-auto" required>
                        <option value="">Kies een optie</option>
                        <option value="asap">Zo snel mogelijk</option>
                        <option value="1-2months">Binnen 1 - 2 maanden</option>
                        <option value="3-6months">Binnen 3 - 6 maanden</option>
                        <option value="flexible">Geen haast</option>
                    </select>
                </div>

                <div class="h-full flex flex-col">
                    <div class="flex items-start gap-4 mb-4 flex-1">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-100 text-primary-600 font-heading font-bold text-sm shrink-0">9</span>
                        <div>
                            <h2 class="font-heading text-xl font-bold text-slate-900">Indicatie budget (optioneel)</h2>
                            <p class="text-sm text-slate-500 mt-1">Helpt ons een passend voorstel te doen.</p>
                        </div>
                    </div>
                    <select name="budget" class="form-input mt-auto">
                        <option value="">Kies een budgetindicatie</option>
                        <option value="<2500">Minder dan € 2.500</option>
                        <option value="2500-5000">€ 2.500 - € 5.000</option>
                        <option value="5000-10000">€ 5.000 - € 10.000</option>
                        <option value="10000-25000">€ 10.000 - € 25.000</option>
                        <option value="25000+">Meer dan € 25.000</option>
                    </select>
                </div>
            </div>

            <!-- Extra info -->
            <div class="mb-14 animate-on-scroll">
                <div class="flex items-start gap-4 mb-4">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-100 text-primary-600 font-heading font-bold text-sm shrink-0">10</span>
                    <div>
                        <h2 class="font-heading text-2xl font-bold text-slate-900">Extra informatie</h2>
                        <p class="text-slate-500 mt-1">Voorbeelden, wensen of andere details die we moeten weten.</p>
                    </div>
                </div>
                <textarea name="notes" rows="5" class="form-textarea" placeholder="Beschrijf kort uw plannen, voorbeelden van websites die u mooi vindt, of specifieke wensen..."></textarea>
            </div>

            <!-- Contactgegevens -->
            <div class="mb-14 animate-on-scroll">
                <div class="flex items-start gap-4 mb-6">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-100 text-primary-600 font-heading font-bold text-sm shrink-0">11</span>
                    <h2 class="font-heading text-2xl font-bold text-slate-900">Uw contactgegevens</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div class="form-group sm:col-span-2">
                        <label for="name" class="form-label">Naam *</label>
                        <input type="text" id="name" name="name" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="company" class="form-label">Bedrijfsnaam</label>
                        <input type="text" id="company" name="company" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">E-mailadres *</label>
                        <input type="email" id="email" name="email" class="form-input" required>
                    </div>
                    <div class="form-group sm:col-span-2">
                        <label for="phone" class="form-label">Telefoonnummer</label>
                        <input type="tel" id="phone" name="phone" class="form-input">
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-6 pt-6 border-t border-slate-200 animate-on-scroll">
                <p class="text-sm text-slate-500">Velden met * zijn verplicht. Wij gebruiken uw gegevens alleen voor deze offerte-aanvraag.</p>
                <button type="submit" class="btn btn-primary px-8 py-3.5 shadow-lg shadow-primary-500/25 disabled:cursor-not-allowed disabled:opacity-60" :disabled="submitting" x-text="submitting ? 'Verzenden...' : 'Offerte-aanvraag versturen'"></button>
            </div>
        </form>
    </div>
</section>

<script>
function quoteBuilder() {
    return {
        submitting: false,
        async submit(event) {
            this.submitting = true;
            try {
                const response = await fetch(event.target.action, {
                    method: 'POST',
                    body: new FormData(event.target),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'text/html',
                    }
                });

                if (response.ok) {
                    window.location.href = '{{ route('quote.builder') }}?success=1';
                } else {
                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    document.documentElement.innerHTML = doc.documentElement.innerHTML;
                }
            } catch (error) {
                console.error('Form submission error:', error);
            } finally {
                this.submitting = false;
            }
        }
    }
}
</script>
@endsection
