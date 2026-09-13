@extends('layouts.app')

@section('title', 'Contact - Servura')
@section('meta-description', 'Neem contact op met Servura voor een gratis adviesgesprek. Wij helpen u met professionele websites en hosting voor uw MKB bedrijf.')
@section('meta-keywords', 'contact, contactformulier, adviesgesprek, offerte, servura')

@section('content')
<!-- Hero Section -->
<section class="relative -mt-16 pt-16 overflow-hidden bg-slate-950 text-white" data-navbar-theme="dark">
    <div class="absolute inset-0 opacity-40 pointer-events-none" style="background-image: radial-gradient(rgba(255,255,255,0.06) 1px, transparent 1px); background-size: 32px 32px;"></div>
    <div class="absolute top-1/4 -left-20 w-[28rem] h-[28rem] rounded-full bg-primary-600/15 blur-3xl"></div>
    <div class="absolute bottom-0 right-0 w-[30rem] h-[30rem] rounded-full bg-accent-500/10 blur-3xl"></div>

    <div class="relative max-w-7xl mx-auto px-6 py-24 lg:py-32">
        <div class="max-w-2xl animate-slide-up">
            <h1 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold leading-[1.05] tracking-tight mb-6">
                Klaar om online te groeien?
            </h1>
            <p class="text-lg md:text-xl text-white/80 leading-relaxed max-w-xl">
                Vertel ons waar u naar op zoek bent. We nemen binnen 48 uur contact op voor een vrijblijvend gesprek.
            </p>
        </div>
    </div>
</section>

<!-- Contact Form + Info -->
<section id="contact-form" class="relative py-24 lg:py-32 bg-slate-50 overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[40rem] h-[20rem] bg-primary-400/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
            <!-- Form -->
            <div class="lg:col-span-7 xl:col-span-8 relative">
                <div class="relative bg-white rounded-3xl shadow-xl shadow-slate-900/5 ring-1 ring-slate-200 p-8 lg:p-10 overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-primary-500 via-accent-400 to-emerald-400"></div>
                    <div class="absolute -top-16 -right-16 w-40 h-40 bg-primary-50 rounded-full blur-2xl opacity-60 pointer-events-none"></div>

                    <div class="relative">
                        <span class="inline-flex items-center rounded-full bg-primary-50 px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] text-primary-700 mb-4">Direct contact</span>
                        <h2 class="font-heading text-3xl font-bold text-slate-900 mb-2">Stuur ons een bericht</h2>
                        <p class="text-slate-500 mb-8">Vul het formulier in en we nemen binnen 48 uur contact op.</p>

                        @if(session('success'))
                            <div class="bg-emerald-50 border-l-4 border-emerald-400 p-4 mb-6 rounded-r-lg">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-emerald-700">{{ session('success') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <form x-data="contactForm()" @submit.prevent="submit($event)">
                            @csrf

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label for="name" class="form-label">Naam *</label>
                                    <input type="text" id="name" name="name" class="form-input" required x-model="formData.name">
                                    @error('name')<span class="form-error">{{ $message }}</span>@enderror
                                </div>

                                <div class="form-group">
                                    <label for="email" class="form-label">E-mailadres *</label>
                                    <input type="email" id="email" name="email" class="form-input" required x-model="formData.email">
                                    @error('email')<span class="form-error">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label for="company" class="form-label">Bedrijfsnaam</label>
                                    <input type="text" id="company" name="company" class="form-input" x-model="formData.company">
                                </div>

                                <div class="form-group">
                                    <label for="phone" class="form-label">Telefoonnummer</label>
                                    <input type="tel" id="phone" name="phone" class="form-input" x-model="formData.phone">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="current_website" class="form-label">Huidige website of online aanwezigheid</label>
                                <input type="text" id="current_website" name="current_website" class="form-input" x-model="formData.current_website" placeholder="www.voorbeeld.nl of 'nog geen website'">
                            </div>

                            <div class="form-group">
                                <label for="looking_for" class="form-label">Waar bent u naar op zoek?</label>
                                <textarea id="looking_for" name="looking_for" rows="4" class="form-textarea" x-model="formData.looking_for" placeholder="Bijvoorbeeld: een nieuwe website, meer leads, betere vindbaarheid, hosting, onderhoud..."></textarea>
                                @error('looking_for')<span class="form-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group">
                                <label for="subject" class="form-label">Onderwerp *</label>
                                <select id="subject" name="subject" class="form-input" required x-model="formData.subject">
                                    <option value="">Kies een onderwerp</option>
                                    <option value="Adviesgesprek">Gratis adviesgesprek</option>
                                    <option value="Offerte">Offerte aanvragen</option>
                                    <option value="Website">Nieuwe website</option>
                                    <option value="Hosting">Hosting vraag</option>
                                    <option value="Onderhoud">Website onderhoud</option>
                                    <option value="Overig">Overige vraag</option>
                                </select>
                                @error('subject')<span class="form-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group">
                                <label for="message" class="form-label">Bericht *</label>
                                <textarea id="message" name="message" rows="5" class="form-textarea" required x-model="formData.message" placeholder="Beschrijf uw vraag of wensen..."></textarea>
                                @error('message')<span class="form-error">{{ $message }}</span>@enderror
                            </div>

                            <!-- Honeypot field -->
                            <div class="form-group" style="position: absolute; left: -9999px; top: -9999px;">
                                <label for="website">Website</label>
                                <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-2">
                                <p class="text-sm text-slate-500">Velden met * zijn verplicht</p>
                                <button type="submit" class="btn btn-primary px-7 py-3.5 shadow-lg shadow-primary-500/25 disabled:cursor-not-allowed disabled:opacity-60" :disabled="submitting" x-text="submitting ? 'Verzenden...' : 'Verstuur Bericht'"></button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Floating accents -->
                <div class="hidden xl:block absolute top-32 -left-8 animate-float" style="animation-delay: 0.5s">
                    <div class="w-14 h-14 rounded-2xl bg-white/80 backdrop-blur ring-1 ring-white/50 shadow-xl flex items-center justify-center text-primary-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25H4.5a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15A2.25 2.25 0 002.25 6.75m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                    </div>
                </div>
                <div class="hidden xl:block absolute bottom-40 -right-6 animate-float" style="animation-delay: 1.5s">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-primary-500 to-accent-500 shadow-xl shadow-primary-500/25 flex items-center justify-center text-white">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Info cards -->
            <div class="lg:col-span-5 xl:col-span-4 space-y-6">
                <div class="group bg-white rounded-2xl p-6 ring-1 ring-slate-200 shadow-lg shadow-slate-900/5 hover:-translate-y-1 transition-all duration-300">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-primary-100 text-primary-600 mb-4 group-hover:bg-primary-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                    </span>
                    <h3 class="font-heading font-semibold text-slate-900 mb-1">Telefoon</h3>
                    <a href="tel:0612345678" class="text-primary-600 hover:text-primary-700 font-medium">06 123 456 78</a>
                    <p class="text-sm text-slate-500 mt-1">Maandag t/m vrijdag, 9:00 - 17:00</p>
                </div>

                <div class="group bg-white rounded-2xl p-6 ring-1 ring-slate-200 shadow-lg shadow-slate-900/5 hover:-translate-y-1 transition-all duration-300">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-accent-100 text-accent-600 mb-4 group-hover:bg-accent-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25H4.5a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15A2.25 2.25 0 002.25 6.75m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                    </span>
                    <h3 class="font-heading font-semibold text-slate-900 mb-1">E-mail</h3>
                    <a href="mailto:info@servura.nl" class="text-primary-600 hover:text-primary-700 font-medium">info@servura.nl</a>
                    <p class="text-sm text-slate-500 mt-1">Binnen 48 uur reactie</p>
                </div>

                <div class="group bg-white rounded-2xl p-6 ring-1 ring-slate-200 shadow-lg shadow-slate-900/5 hover:-translate-y-1 transition-all duration-300">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    <h3 class="font-heading font-semibold text-slate-900 mb-1">Reactietijd</h3>
                    <p class="text-slate-700 font-medium">Binnen 48 uur</p>
                    <p class="text-sm text-slate-500 mt-1">Spoed? Bel ons direct</p>
                </div>

                <div class="group rounded-2xl p-6 bg-gradient-to-br from-slate-900 to-slate-800 text-white shadow-xl shadow-slate-900/20 hover:-translate-y-1 transition-all duration-300">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-white/10 text-accent-300 mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    </span>
                    <h3 class="font-heading font-semibold mb-1">Servura</h3>
                    <p class="text-sm text-slate-300 leading-relaxed">Werkzaam door heel Nederland. Altijd een vast aanspreekpunt voor uw project.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="relative py-24 lg:py-28 bg-white overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[36rem] h-[16rem] bg-accent-400/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-3xl mx-auto px-6">
        <div class="text-center mb-12 animate-on-scroll">
            <h2 class="font-heading text-3xl md:text-4xl font-bold text-slate-900 mb-4">Veelgestelde vragen</h2>
            <p class="text-lg text-slate-600">Staat uw vraag er niet bij? Neem gerust contact op.</p>
        </div>

        @php
            $faqs = [
                ['q' => 'Wat kost een nieuwe website?', 'a' => 'De kosten van een nieuwe website variëren afhankelijk van uw wensen. Een basis website start vanaf €1.500, terwijl een uitgebreide website met custom functionaliteiten vanaf €3.000 beschikbaar is. Vraag een vrijblijvende offerte aan.'],
                ['q' => 'Hoe snel kan mijn website online?', 'a' => 'Afhankelijk van de complexiteit kan uw website binnen 2-4 weken online zijn. Een simpele website kan zelfs binnen 1-2 weken. Tijdens het adviesgesprek bespreken we de exacte planning.'],
                ['q' => 'Bieden jullie ook onderhoud?', 'a' => 'Ja, wij bieden complete onderhoudspakketten aan. Dit omvat updates, security checks, backups en technische support. Prijs start vanaf €50 per maand.'],
            ];
        @endphp

        <div class="space-y-4">
            @foreach($faqs as $faq)
                <details class="group rounded-2xl bg-slate-50 ring-1 ring-slate-200 overflow-hidden transition-all duration-300 hover:shadow-md hover:bg-white open:bg-white open:shadow-lg open:shadow-primary-500/5 open:ring-primary-500/30">
                    <summary class="w-full cursor-pointer text-left px-6 py-5 flex justify-between items-center gap-4 focus:outline-none transition-colors">
                        <span class="font-heading font-semibold text-lg text-slate-900 leading-snug">{{ $faq['q'] }}</span>
                        <span class="w-9 h-9 rounded-full bg-white ring-1 ring-slate-200 text-primary-600 flex items-center justify-center flex-shrink-0 transition-all duration-300 group-open:bg-primary-600 group-open:text-white group-open:rotate-180">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </span>
                    </summary>
                    <div class="px-6 pb-6 pt-2 text-slate-600 leading-relaxed border-t border-slate-100">
                        {{ $faq['a'] }}
                    </div>
                </details>
            @endforeach
        </div>
    </div>
</section>

<!-- Final CTA -->
<section class="relative overflow-hidden py-20 lg:py-24 bg-slate-950 text-white" data-navbar-theme="dark">
    <div class="absolute inset-0 opacity-30 pointer-events-none" style="background-image: radial-gradient(rgba(255,255,255,0.05) 1px, transparent 1px); background-size: 32px 32px;"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[36rem] h-[36rem] rounded-full bg-primary-600/10 blur-3xl pointer-events-none"></div>

    <div class="relative max-w-4xl mx-auto px-6 text-center">
        <h2 class="font-heading text-3xl md:text-4xl font-bold mb-4">Bekijk wat wij voor u kunnen betekenen</h2>
        <p class="text-lg text-white/70 max-w-2xl mx-auto mb-8">Van een nieuwe website tot betrouwbare hosting en onderhoud. Wij regelen het complete traject.</p>
        <a href="{{ route('services.index') }}" class="btn btn-primary px-8 py-4 text-base shadow-lg shadow-primary-500/25">
            Ontdek onze diensten
        </a>
    </div>
</section>

<script>
function contactForm() {
    return {
        submitting: false,
        success: false,
        formData: {
            name: '',
            email: '',
            company: '',
            phone: '',
            current_website: '',
            subject: '',
            looking_for: '',
            message: ''
        },

        async submit(event) {
            this.submitting = true;

            try {
                const response = await fetch(event.target.action, {
                    method: 'POST',
                    body: new FormData(event.target),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content'),
                        'Accept': 'text/html',
                    }
                });

                if (response.ok) {
                    this.success = true;
                    this.formData = {
                        name: '',
                        email: '',
                        company: '',
                        phone: '',
                        current_website: '',
                        subject: '',
                        looking_for: '',
                        message: ''
                    };

                    window.location.href = '/contact?success=1';
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
