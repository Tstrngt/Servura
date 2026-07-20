@extends('layouts.app')

@section('title', 'Contact - Servura')
@section('meta-description', 'Neem contact op met Servura voor een gratis adviesgesprek. Wij helpen u met professionele websites en hosting voor uw MKB bedrijf.')
@section('meta-keywords', 'contact, contactformulier, adviesgesprek, offerte, servura')

@section('content')
<!-- Hero Section -->
<section class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                Neem Contact Op
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Heeft u vragen of wilt u een vrijblijvend adviesgesprek? 
                Neem contact met ons op en wij helpen u graag verder.
            </p>
        </div>
    </div>
</section>

<!-- Contact Content -->
<section class="py-16 lg:py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Form + photo -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-stretch mb-24">
            <!-- Contact Form -->
            <div class="bg-white rounded-2xl shadow-lg p-8 lg:p-10 animate-on-scroll">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">
                    Stuur ons een bericht
                </h2>
                <p class="text-gray-500 mb-6">Vul het formulier in en we nemen binnen 48 uur contact op.</p>
                
                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <form x-data="contactForm()" @submit.prevent="submit($event)">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name" class="form-label">Naam *</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            class="form-input" 
                            required
                            x-model="formData.name"
                        >
                        @error('name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">E-mailadres *</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-input" 
                            required
                            x-model="formData.email"
                        >
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="company" class="form-label">Bedrijfsnaam</label>
                            <input 
                                type="text" 
                                id="company" 
                                name="company" 
                                class="form-input"
                                x-model="formData.company"
                            >
                        </div>

                        <div class="form-group">
                            <label for="phone" class="form-label">Telefoonnummer</label>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone" 
                                class="form-input"
                                x-model="formData.phone"
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="subject" class="form-label">Onderwerp *</label>
                        <select 
                            id="subject" 
                            name="subject" 
                            class="form-input" 
                            required
                            x-model="formData.subject"
                        >
                            <option value="">Kies een onderwerp</option>
                            <option value="Adviesgesprek">Gratis adviesgesprek</option>
                            <option value="Offerte">Offerte aanvragen</option>
                            <option value="Website">Nieuwe website</option>
                            <option value="Hosting">Hosting vraag</option>
                            <option value="Onderhoud">Website onderhoud</option>
                            <option value="Overig">Overige vraag</option>
                        </select>
                        @error('subject')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="message" class="form-label">Bericht *</label>
                        <textarea 
                            id="message" 
                            name="message" 
                            rows="5" 
                            class="form-textarea" 
                            required
                            x-model="formData.message"
                            placeholder="Beschrijf uw vraag of wensen..."
                        ></textarea>
                        @error('message')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Honeypot field -->
                    <div class="form-group" style="position: absolute; left: -9999px; top: -9999px;">
                        <label for="website">Website</label>
                        <input 
                            type="text" 
                            id="website" 
                            name="website" 
                            tabindex="-1"
                            autocomplete="off"
                        >
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <p class="text-sm text-gray-500">
                            Velden met * zijn verplicht
                        </p>
                        <button 
                            type="submit" 
                            class="btn btn-primary"
                            :disabled="submitting"
                            x-text="submitting ? 'Verzenden...' : 'Verstuur Bericht'"
                        ></button>
                    </div>
                </form>
            </div>

            <!-- Photo -->
            <div class="relative rounded-2xl overflow-hidden shadow-lg min-h-[400px] lg:min-h-full animate-on-scroll">
                <div class="absolute inset-0 bg-gradient-to-br from-primary-500 to-secondary-700"></div>
                <img 
                    src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1200&q=80" 
                    alt="Het Servura team werkt samen aan een project"
                    class="absolute inset-0 w-full h-full object-cover mix-blend-overlay"
                    onerror="this.style.display='none'"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-8 lg:p-10 text-white">
                    <p class="text-xl lg:text-2xl font-semibold leading-relaxed">
                        "Samen maken we van uw website een sterke verkoper."
                    </p>
                    <p class="mt-4 text-primary-200 font-medium">— Team Servura</p>
                </div>
            </div>
        </div>

        <!-- Contact info + FAQ centered below -->
        <div class="max-w-4xl mx-auto space-y-24">
            <!-- Contact info -->
            <div class="text-center animate-on-scroll">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Contactinformatie</h2>
                <p class="text-lg text-gray-600 mb-10">Liever direct contact? We zijn bereikbaar op onderstaande kanalen.</p>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                    <div class="flex flex-col items-center">
                        <div class="w-14 h-14 bg-primary-100 rounded-full flex items-center justify-center mb-4 text-primary-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Telefoon</h3>
                        <a href="tel:0612345678" class="text-primary-600 hover:text-primary-700 font-medium">06 123 456 78</a>
                        <p class="text-sm text-gray-500 mt-1">Maandag t/m vrijdag, 9:00 - 17:00</p>
                    </div>

                    <div class="flex flex-col items-center">
                        <div class="w-14 h-14 bg-primary-100 rounded-full flex items-center justify-center mb-4 text-primary-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">E-mail</h3>
                        <a href="mailto:info@servura.nl" class="text-primary-600 hover:text-primary-700 font-medium">info@servura.nl</a>
                        <p class="text-sm text-gray-500 mt-1">Binnen 48 uur reactie</p>
                    </div>

                    <div class="flex flex-col items-center">
                        <div class="w-14 h-14 bg-primary-100 rounded-full flex items-center justify-center mb-4 text-primary-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Reactietijd</h3>
                        <p class="text-gray-600 font-medium">Binnen 48 uur</p>
                        <p class="text-sm text-gray-500 mt-1">Spoed? Bel ons direct</p>
                    </div>
                </div>
            </div>

            <!-- FAQ -->
            <div class="animate-on-scroll">
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Veelgestelde vragen</h2>
                    <p class="text-lg text-gray-600">Staat uw vraag er niet bij? Neem gerust contact op.</p>
                </div>

                @php
                    $faqs = [
                        ['q' => 'Wat kost een nieuwe website?', 'a' => 'De kosten van een nieuwe website variëren afhankelijk van uw wensen. Een basis website start vanaf €1.500, terwijl een uitgebreide website met custom functionaliteiten vanaf €3.000 beschikbaar is. Vraag een vrijblijvende offerte aan.'],
                        ['q' => 'Hoe snel kan mijn website online?', 'a' => 'Afhankelijk van de complexiteit kan uw website binnen 2-4 weken online zijn. Een simpele website kan zelfs binnen 1-2 weken. Tijdens het adviesgesprek bespreken we de exacte planning.'],
                        ['q' => 'Bieden jullie ook onderhoud?', 'a' => 'Ja, wij bieden complete onderhoudspakketten aan. Dit omvat updates, security checks, backups en technische support. Prijs start vanaf €50 per maand.'],
                    ];
                @endphp

                <div class="space-y-5" x-data="{ open: null }">
                    @foreach($faqs as $index => $faq)
                        <div 
                            class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300"
                            :class="open === {{ $index }} ? 'ring-2 ring-primary-500 shadow-md' : 'hover:shadow-md'"
                        >
                            <button 
                                @click="open = open === {{ $index }} ? null : {{ $index }}"
                                class="w-full text-left px-6 py-5 flex justify-between items-center gap-4 focus:outline-none focus:bg-gray-50 transition-colors"
                                :aria-expanded="open === {{ $index }}"
                            >
                                <span class="font-semibold text-lg text-gray-900 leading-snug">{{ $faq['q'] }}</span>
                                <span 
                                    class="w-8 h-8 rounded-full bg-gray-100 text-primary-600 flex items-center justify-center flex-shrink-0 transition-all duration-300"
                                    :class="open === {{ $index }} ? 'bg-primary-100 rotate-180' : ''"
                                >
                                    <svg 
                                        class="w-5 h-5"
                                        fill="none" 
                                        stroke="currentColor" 
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </span>
                            </button>
                            <div 
                                x-show="open === {{ $index }}"
                                x-transition:enter="transition-all ease-out duration-300"
                                x-transition:enter-start="opacity-0 max-h-0"
                                x-transition:enter-end="opacity-100 max-h-[40rem]"
                                x-transition:leave="transition-all ease-in duration-200"
                                x-transition:leave-start="opacity-100 max-h-[40rem]"
                                x-transition:leave-end="opacity-0 max-h-0"
                                class="overflow-hidden"
                                x-cloak
                            >
                                <div class="px-6 pb-6 pt-3 text-gray-600 leading-relaxed border-t border-gray-100">
                                    {{ $faq['a'] }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-primary-600 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">
            Liever direct bellen?
        </h2>
        <p class="text-xl text-primary-100 mb-8">
            Soms is een persoonlijk gesprek de snelste weg naar een oplossing.
        </p>
        <a href="tel:0612345678" class="btn bg-white text-primary-600 hover:bg-primary-50 text-lg px-8 py-4">
            Bel Nu: 06 123 456 78
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
            subject: '',
            message: ''
        },
        
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
                    this.success = true;
                    this.formData = {
                        name: '',
                        email: '',
                        company: '',
                        phone: '',
                        subject: '',
                        message: ''
                    };
                    
                    // Redirect to show success message
                    window.location.href = '/contact?success=1';
                } else {
                    // Handle errors
                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    // Replace current content with server-rendered content
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
