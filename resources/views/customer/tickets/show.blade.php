@extends('layouts.app')

@section('title', 'Ticket {{ $ticket->ticket_number }} - Servura')

@section('content')
@include('customer.partials.sidebar')

<!-- Ticket Content -->
<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="max-w-6xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <a href="{{ route('customer.tickets.index') }}" class="text-primary-600 hover:text-primary-500 mr-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">
                                    {{ $ticket->ticket_number }} - {{ $ticket->title }}
                                </h1>
                                <p class="mt-1 text-sm text-gray-600">
                                    Aangemaakt op {{ $ticket->created_at->format('d-m-Y H:i') }}
                                </p>
                            </div>
                        </div>
                        @if($ticket->canBeClosed())
                            <form action="{{ route('customer.tickets.close', $ticket) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="btn btn-outline" onclick="return confirm('Weet u zeker dat u dit ticket wilt sluiten?')">
                                    Ticket Sluiten
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Ticket Info -->
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $ticket->statusLabel['color'] }}-100 text-{{ $ticket->statusLabel['color'] }}-800">
                                    {{ $ticket->statusLabel['text'] }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Prioriteit</label>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $ticket->priorityLabel['color'] }}-100 text-{{ $ticket->priorityLabel['color'] }}-800">
                                    {{ $ticket->priorityLabel['text'] }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Categorie</label>
                            <div class="mt-1 text-sm text-gray-900">
                                {{ $ticket->categoryLabel }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Toegewezen aan</label>
                            <div class="mt-1 text-sm text-gray-900">
                                {{ $ticket->assignedTo ? $ticket->assignedTo->name : 'Nog niet toegewezen' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ticket Description and Attachments -->
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                <span class="text-sm font-medium text-gray-700">
                                    {{ substr($ticket->user->name, 0, 2) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $ticket->user->name }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $ticket->created_at->format('d-m-Y H:i') }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="prose max-w-none">
                        <p>{{ nl2br(e($ticket->description)) }}</p>
                    </div>

                    @if($ticket->attachments->count() > 0)
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Bijlagen</h4>
                            <div class="space-y-2">
                                @foreach($ticket->attachments as $attachment)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $attachment->original_name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $attachment->formatted_file_size }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            @if($attachment->isImage())
                                                <a href="{{ route('customer.tickets.attachments.preview', $attachment) }}" target="_blank" class="text-primary-600 hover:text-primary-500 text-sm">
                                                    Voorbeeld
                                                </a>
                                            @endif
                                            <a href="{{ route('customer.tickets.attachments.download', $attachment) }}" class="text-primary-600 hover:text-primary-500 text-sm">
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Replies -->
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Reacties ({{ $ticket->replies->count() }})
                    </h3>

                    @if($ticket->replies->count() > 0)
                        <div class="space-y-6">
                            @foreach($ticket->replies as $reply)
                                <div class="border-l-4 {{ $reply->isFromCustomer() ? 'border-blue-400' : 'border-green-400' }} pl-4">
                                    <div class="flex items-center mb-2">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full {{ $reply->isFromCustomer() ? 'bg-blue-100' : 'bg-green-100' }} flex items-center justify-center">
                                                <span class="text-xs font-medium {{ $reply->isFromCustomer() ? 'text-blue-700' : 'text-green-700' }}">
                                                    {{ substr($reply->user->name, 0, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $reply->user->name }}
                                                @if($reply->isFromStaff())
                                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Servura
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $reply->created_at->format('d-m-Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="prose max-w-none">
                                        <p>{{ $reply->formatted_message }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Geen reacties</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Er zijn nog geen reacties op dit ticket.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Reply Form -->
        @if($ticket->canBeReplied())
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

                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Reageren
                        </h3>

                        <form action="{{ route('customer.tickets.reply', $ticket) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group mb-4">
                                <label for="message" class="form-label">Uw reactie</label>
                                <textarea 
                                    id="message" 
                                    name="message" 
                                    rows="4" 
                                    class="form-textarea" 
                                    required
                                    placeholder="Typ uw reactie..."
                                ></textarea>
                                @error('message')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
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

                            <div class="flex justify-end">
                                <button type="submit" class="btn btn-primary">
                                    Reactie Versturen
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <div class="px-4 py-6 sm:px-0">
                <div class="bg-gray-100 border border-gray-300 rounded-lg p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Ticket is gesloten</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Dit ticket is gesloten en kan niet meer beantwoord worden.
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
// File upload preview (same as create form)
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
    
    const existingList = e.target.parentElement.querySelector('.mt-2');
    if (existingList) {
        existingList.remove();
    }
    
    e.target.parentElement.appendChild(fileList);
});

// Auto-scroll to latest reply
window.addEventListener('load', function() {
    const replies = document.querySelectorAll('[class*="border-l-4"]');
    if (replies.length > 0) {
        replies[replies.length - 1].scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});
</script>
@endsection
