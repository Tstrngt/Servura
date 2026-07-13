@extends('layouts.app')

@section('title', 'Nieuw Support Ticket - Servura')

@section('content')
<!-- Customer Navigation -->
<nav class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('customer.dashboard') }}" class="text-xl font-bold text-primary-600">
                        Servura
                    </a>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <a href="{{ route('customer.dashboard') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Dashboard
                    </a>
                    <a href="{{ route('customer.tickets.index') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Support Tickets
                    </a>
                    <a href="#" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Mijn Diensten
                    </a>
                </div>
            </div>
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="btn btn-outline text-sm">
                            Uitloggen
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Create Ticket Content -->
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <a href="{{ route('customer.tickets.index') }}" class="text-primary-600 hover:text-primary-500 mr-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">
                                Nieuw Support Ticket
                            </h1>
                            <p class="mt-1 text-sm text-gray-600">
                                Maak een nieuw support ticket aan voor technische hulp of vragen.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Ticket Form -->
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
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
                                    placeholder="Korte beschrijving van uw probleem"
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
                                <p class="mt-1 text-sm text-gray-500">
                                    Kies 'Urgent' alleen bij storingen die uw bedrijf direct beïnvloeden.
                                </p>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Bijlagen</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="attachments" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                                <span>Upload bestanden</span>
                                                <input id="attachments" name="attachments[]" type="file" class="sr-only" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt,.zip">
                                            </label>
                                            <p class="pl-1">of sleep en zet neer</p>
                                        </div>
                                        <p class="text-xs text-gray-500">
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
                                placeholder="Beschrijf uw probleem of vraag zo gedetailleerd mogelijk..."
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                Voeg zoveel mogelijk details toe: wat is het probleem, wanneer trad het op, welke stappen heeft u al genomen?
                            </p>
                        </div>

                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        <strong>Response tijd:</strong> Wij streven ernaar om binnen 24 uur te reageren op uw ticket. 
                                        Urgente tickets worden zo snel mogelijk behandeld.
                                    </p>
                                </div>
                            </div>
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
        fileItem.className = 'flex items-center text-sm text-gray-600';
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
