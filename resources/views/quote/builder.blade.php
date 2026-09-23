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

            @if($errors->any())
                <div class="mb-10">
                    <div class="bg-red-50 border-l-4 border-red-400 p-5 rounded-r-xl">
                        <h3 class="font-heading font-semibold text-red-800">Controleer uw invoer</h3>
                        <ul class="mt-2 list-disc list-inside text-sm text-red-700 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Dienst -->
            <div class="mb-14 animate-on-scroll">
                <div class="flex items-start gap-4 mb-6">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-100 text-primary-600 font-heading font-bold text-sm shrink-0">0</span>
                    <div>
                        <h2 class="font-heading text-2xl font-bold text-slate-900">Waarvoor vraagt u een offerte aan?</h2>
                        <p class="text-slate-500 mt-1">Uw aanvraag wordt direct gekoppeld aan deze dienst in uw klantportaal.</p>
                    </div>
                </div>
                @if($service)
                    <input type="hidden" name="service" value="{{ $service->slug }}">
                    <div class="flex items-center gap-4 rounded-2xl bg-white p-5 ring-1 ring-primary-200 shadow-sm">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-primary-100 text-primary-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        <div>
                            <span class="font-semibold text-slate-900 block">{{ $service->title }}</span>
                            <a href="{{ route('quote.builder') }}" class="text-sm text-primary-600 hover:text-primary-800">Andere dienst kiezen</a>
                        </div>
                    </div>
                @else
                    <select name="service" class="form-input" required>
                        <option value="">Kies een dienst</option>
                        @foreach($services as $option)
                            <option value="{{ $option->slug }}" {{ old('service') === $option->slug ? 'selected' : '' }}>{{ $option->title }}</option>
                        @endforeach
                    </select>
                    @error('service')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                @endif
            </div>

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
                    @foreach($form['goals'] as $goal)
                        <label class="relative flex items-start gap-4 rounded-2xl bg-white p-5 ring-1 ring-slate-200 shadow-sm cursor-pointer hover:shadow-md hover:-translate-y-0.5 transition-all has-[:checked]:ring-primary-500 has-[:checked]:bg-primary-50/30">
                            <input type="radio" name="goal" value="{{ $goal['label'] }}" class="mt-1 h-4 w-4 text-primary-600 border-slate-300 focus:ring-primary-500" required>
                            <div>
                                <span class="font-semibold text-slate-900 block">{{ $goal['label'] }}</span>
                                @if($goal['note'] !== '')<span class="text-sm text-slate-500">{{ $goal['note'] }}</span>@endif
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
                        @foreach($form['pages'] as $option)
                            <option value="{{ $option }}" {{ old('pages') === $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
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
                        @foreach($form['visitors'] as $option)
                            <option value="{{ $option }}" {{ old('visitors') === $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
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
                        @foreach($form['design'] as $option)
                            <option value="{{ $option['label'] }}" {{ old('design') === $option['label'] ? 'selected' : '' }}>{{ $option['label'] }}</option>
                        @endforeach
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
                    @foreach($form['features'] as $feature)
                        <label class="relative flex items-start gap-3 rounded-2xl bg-white p-4 ring-1 ring-slate-200 shadow-sm cursor-pointer hover:shadow-md hover:-translate-y-0.5 transition-all has-[:checked]:ring-primary-500 has-[:checked]:bg-primary-50/30">
                            <input type="checkbox" name="features[]" value="{{ $feature['label'] }}" class="mt-1 h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                            <div>
                                <span class="font-semibold text-slate-900 text-sm block">{{ $feature['label'] }}</span>
                                @if($feature['note'] !== '')<span class="text-xs text-slate-400">{{ $feature['note'] }}</span>@endif
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
                    @foreach($form['content'] as $option)
                        <label class="relative flex items-start gap-3 rounded-2xl bg-white p-4 ring-1 ring-slate-200 shadow-sm cursor-pointer hover:shadow-md hover:-translate-y-0.5 transition-all has-[:checked]:ring-primary-500 has-[:checked]:bg-primary-50/30">
                            <input type="checkbox" name="content[]" value="{{ $option['label'] }}" class="mt-1 h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                            <span class="font-semibold text-slate-900 text-sm">{{ $option['label'] }}</span>
                        </label>
                    @endforeach
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
                        @foreach($form['timeline'] as $option)
                            <option value="{{ $option }}" {{ old('timeline') === $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
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
                        @foreach($form['budget'] as $option)
                            <option value="{{ $option }}" {{ old('budget') === $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
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

            @include('partials.captcha')

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
                    window.location.href = response.redirected ? response.url : '{{ route('quote.builder') }}?success=1';
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
