@extends('layouts.app')

@section('title', 'Nieuwe aanvraag - Servura')

@section('content')
@include('customer.partials.topbar')

<!-- Create Ticket Content -->
<div class="bg-slate-50 min-h-screen pt-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 pb-24">
        <!-- Header -->
        <div class="mb-10">
            <div class="flex items-center gap-4">
                <a href="{{ route('customer.tickets.index') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white text-slate-500 ring-1 ring-slate-200 hover:text-primary-600 hover:ring-primary-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="font-heading text-3xl font-bold text-slate-900">Nieuwe aanvraag</h1>
                    <p class="mt-1 text-lg text-slate-500">Maak een nieuw support ticket aan voor technische hulp of vragen.</p>
                </div>
            </div>
        </div>

        <!-- Create Ticket Form -->
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 p-6 sm:p-8">
                    @if(session('success'))
                        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-6 flex gap-3">
                            <svg class="h-5 w-5 text-emerald-500 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <p class="text-sm text-emerald-800">{{ session('success') }}</p>
                        </div>
                    @endif

                    <form action="{{ route('customer.tickets.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="form-group">
                                <label for="title" class="form-label">Titel *</label>
                                <input 
                                    type="text" 
                                    id="title" 
                                    name="title" 
                                    class="form-input" 
                                    required
                                    value="{{ old('title') }}"
                                    placeholder="Korte beschrijving van je probleem"
                                >
                                @error('title')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="category" class="form-label">Categorie *</label>
                                <select id="category" name="category" class="form-input" required>
                                    <option value="">Kies een categorie</option>
                                    <option value="technical" {{ old('category') == 'technical' ? 'selected' : '' }}>Technisch</option>
                                    <option value="billing" {{ old('category') == 'billing' ? 'selected' : '' }}>Facturatie</option>
                                    <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>Algemeen</option>
                                    <option value="feature_request" {{ old('category') == 'feature_request' ? 'selected' : '' }}>Feature verzoek</option>
                                    <option value="bug_report" {{ old('category') == 'bug_report' ? 'selected' : '' }}>Bug report</option>
                                </select>
                                @error('category')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="form-group">
                                <label for="priority" class="form-label">Prioriteit *</label>
                                <select id="priority" name="priority" class="form-input" required>
                                    <option value="">Kies een prioriteit</option>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Laag</option>
                                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Hoog</option>
                                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                                @error('priority')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                                <p class="mt-1 text-sm text-slate-500">
                                    Kies 'Urgent' alleen bij storingen die je bedrijf direct beïnvloeden.
                                </p>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Bijlagen</label>
                                <div class="mt-1 flex justify-center px-6 pt-6 pb-7 border-2 border-dashed border-slate-300 rounded-xl hover:border-primary-400 transition-colors bg-white cursor-pointer" onclick="document.getElementById('attachments').click()">
                                    <div class="space-y-1 text-center">
                                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-primary-50 text-primary-600 mb-2">
                                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0-3 3m3-3 3 3M6.75 19.5h10.5a2.25 2.25 0 0 0 2.25-2.25v-10.5a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v10.5a2.25 2.25 0 0 0 2.25 2.25Z"/>
                                            </svg>
                                        </span>
                                        <div class="flex text-sm text-slate-600 justify-center">
                                            <label for="attachments" class="relative cursor-pointer rounded-md font-medium text-primary-600 hover:text-primary-500" onclick="event.stopPropagation()">
                                                <span>Upload bestanden</span>
                                                <input id="attachments" name="attachments[]" type="file" class="sr-only" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt,.zip">
                                            </label>
                                            <p class="pl-1">of sleep en zet neer</p>
                                        </div>
                                        <p class="text-xs text-slate-500">
                                            PNG, JPG, GIF, PDF, DOC, DOCX, TXT, ZIP tot 10MB per bestand
                                        </p>
                                    </div>
                                </div>
                                @error('attachments.*')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-6">
                            <label for="description" class="form-label">Beschrijving *</label>
                            <textarea 
                                id="description" 
                                name="description" 
                                rows="8" 
                                class="form-textarea" 
                                required
                                placeholder="Beschrijf je probleem of vraag zo gedetailleerd mogelijk..."
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                            <p class="mt-1 text-sm text-slate-500">
                                Voeg zoveel mogelijk details toe: wat is het probleem, wanneer trad het op, welke stappen heb je al genomen?
                            </p>
                        </div>

                        <div class="bg-primary-50 border border-primary-200 rounded-xl p-4 mb-6 flex gap-3">
                            <svg class="h-5 w-5 text-primary-500 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <p class="text-sm text-primary-800">
                                <strong>Response tijd:</strong> Wij streven ernaar om binnen 24 uur te reageren op je ticket. Urgente tickets worden zo snel mogelijk behandeld.
                            </p>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('customer.tickets.index') }}" class="btn btn-outline">
                                Annuleren
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Ticket Aanmaken
                            </button>
                        </div>
                    </form>
            </div>
        </div>
    </div>

<script>
// File upload preview
document.getElementById('attachments').addEventListener('change', function(e) {
    const files = e.target.files;
    const fileList = document.createElement('div');
    fileList.className = 'mt-2 space-y-1';
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        
        const fileItem = document.createElement('div');
        fileItem.className = 'flex items-center text-sm text-slate-600';
        fileItem.innerHTML = `
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            ${file.name} (${fileSize} MB)
        `;
        
        fileList.appendChild(fileItem);
    }
    
    // Replace any existing file list
    const existingList = e.target.parentElement.querySelector('.mt-2');
    if (existingList) {
        existingList.remove();
    }
    
    e.target.parentElement.appendChild(fileList);
});
</script>
@endsection
