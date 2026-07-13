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
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div>
                <div class="bg-white rounded-lg shadow-sm p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">
                        Stuur ons een bericht
                    </h2>
                    
                    @if(session('success'))
                        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
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

                    <form x-data="contactForm()" @submit.prevent="submit($event)" class="contact-form">
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

                        <!-- Honeypot field - hidden from users but visible to bots -->
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

                        <div class="flex items-center justify-between">
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
            </div>

            <!-- Contact Info -->
            <div>
                <div class="bg-white rounded-lg shadow-sm p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">
                        Contactinformatie
                    </h2>
                    
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-semibold text-gray-900">Telefoon</h3>
                                <p class="text-gray-600">
                                    <a href="tel:0612345678" class="hover:text-primary-600">06 123 456 78</a>
                                </p>
                                <p class="text-sm text-gray-500">Maandag t/m vrijdag, 9:00 - 17:00</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-semibold text-gray-900">E-mail</h3>
                                <p class="text-gray-600">
                                    <a href="mailto:info@servura.nl" class="hover:text-primary-600">info@servura.nl</a>
                                </p>
                                <p class="text-sm text-gray-500">Binnen 24 uur reactie</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-semibold text-gray-900">Reactietijd</h3>
                                <p class="text-gray-600">Wij streven naar een reactie binnen 24 uur</p>
                                <p class="text-sm text-gray-500">Spoed? Bel ons direct</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ -->
                <div class="bg-white rounded-lg shadow-sm p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">
                        Veelgestelde Vragen
                    </h2>
                    
                    <div class="space-y-4" x-data="{ open: 0 }">
                        <div class="border-b border-gray-200">
                            <button @click="open = open === 1 ? 0 : 1" class="w-full text-left py-4 flex justify-between items-center">
                                <span class="font-medium text-gray-900">Wat kost een nieuwe website?</span>
                                <svg class="w-5 h-5 text-gray-500" :class="open === 1 ? 'transform rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open === 1" x-collapse>
                                <p class="text-gray-600 pb-4">
                                    De kosten van een nieuwe website variëren afhankelijk van uw wensen. 
                                    Een basis website start vanaf €1.500, terwijl een uitgebreide website 
                                    met custom functionaliteiten vanaf €3.000 beschikbaar is. Vraag een vrijblijvende offerte aan.
                                </p>
                            </div>
                        </div>

                        <div class="border-b border-gray-200">
                            <button @click="open = open === 2 ? 0 : 2" class="w-full text-left py-4 flex justify-between items-center">
                                <span class="font-medium text-gray-900">Hoe snel kan mijn website online?</span>
                                <svg class="w-5 h-5 text-gray-500" :class="open === 2 ? 'transform rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open === 2" x-collapse>
                                <p class="text-gray-600 pb-4">
                                    Afhankelijk van de complexiteit kan uw website binnen 2-4 weken online zijn. 
                                    Een simpele website kan zelfs binnen 1-2 weken. Tijdens het adviesgesprek 
                                    bespreken we de exacte planning.
                                </p>
                            </div>
                        </div>

                        <div class="border-b border-gray-200">
                            <button @click="open = open === 3 ? 0 : 3" class="w-full text-left py-4 flex justify-between items-center">
                                <span class="font-medium text-gray-900">Bieden jullie ook onderhoud?</span>
                                <svg class="w-5 h-5 text-gray-500" :class="open === 3 ? 'transform rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open === 3" x-collapse>
                                <p class="text-gray-600 pb-4">
                                    Ja, wij bieden complete onderhoudspakketten aan. Dit omvat updates, 
                                    security checks, backups en technische support. Prijs start vanaf €50 per maand.
                                </p>
                            </div>
                        </div>
                    </div>
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
