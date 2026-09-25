@extends('layouts.app')

@section('title', 'Ticket ' . $ticket->ticket_number . ' - Servura')

@section('content')
@include('customer.partials.topbar')

<!-- Ticket Content -->
<div class="bg-slate-50 min-h-screen pt-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 pb-24">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
            <div class="flex items-center gap-4">
                <a href="{{ route('customer.tickets.index') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white text-slate-500 ring-1 ring-slate-200 hover:text-primary-600 hover:ring-primary-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="font-heading text-3xl font-bold text-slate-900">{{ $ticket->ticket_number }} - {{ $ticket->title }}</h1>
                    <p class="mt-1 text-lg text-slate-500">Aangemaakt op {{ $ticket->created_at->format('d-m-Y H:i') }}</p>
                </div>
            </div>
            @if($ticket->canBeClosed())
                <form action="{{ route('customer.tickets.close', $ticket) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="btn btn-outline" onclick="return confirm('Weet je zeker dat je dit ticket wilt sluiten?')">Ticket sluiten</button>
                </form>
            @endif
        </div>

        <!-- Ticket Info -->
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Status</label>
                    <div class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $ticket->statusLabel['color'] }}-100 text-{{ $ticket->statusLabel['color'] }}-800">{{ $ticket->statusLabel['text'] }}</span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Prioriteit</label>
                    <div class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $ticket->priorityLabel['color'] }}-100 text-{{ $ticket->priorityLabel['color'] }}-800">{{ $ticket->priorityLabel['text'] }}</span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Categorie</label>
                    <div class="mt-1 text-sm text-slate-900">{{ $ticket->categoryLabel }}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Toegewezen aan</label>
                    <div class="mt-1 text-sm text-slate-900">{{ $ticket->assignedTo ? $ticket->assignedTo->name : 'Nog niet toegewezen' }}</div>
                </div>
            </div>
        </div>

        <!-- Ticket Description and Attachments -->
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 p-6 mb-8">
            <div class="flex items-center mb-5">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-primary-100 text-primary-700 text-sm font-semibold">
                    {{ substr($ticket->user->name, 0, 2) }}
                </span>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-slate-900">
                                {{ $ticket->user->name }}
                            </div>
                            <div class="text-sm text-slate-500">
                                {{ $ticket->created_at->format('d-m-Y H:i') }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="prose max-w-none">
                        <p>{!! nl2br(e($ticket->description)) !!}</p>
                    </div>

                    @if($ticket->attachments->count() > 0)
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-slate-900 mb-3">Bijlagen</h4>
                            <div class="space-y-2">
                                @foreach($ticket->attachments as $attachment)
                                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-md">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-slate-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <div>
                                                <div class="text-sm font-medium text-slate-900">
                                                    {{ $attachment->original_name }}
                                                </div>
                                                <div class="text-sm text-slate-500">
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

        <!-- Replies -->
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 p-6 mb-8">
            <h3 class="font-heading text-xl font-bold text-slate-900 mb-5">
                Reacties ({{ $ticket->replies->count() }})
            </h3>

                    @if($ticket->replies->count() > 0)
                        <div class="space-y-6">
                            @foreach($ticket->replies as $reply)
                                <div class="border-l-4 {{ $reply->isFromCustomer() ? 'border-primary-400' : 'border-emerald-400' }} pl-4">
                                    <div class="flex items-center mb-2">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full {{ $reply->isFromCustomer() ? 'bg-primary-100' : 'bg-emerald-100' }} flex items-center justify-center">
                                                <span class="text-xs font-medium {{ $reply->isFromCustomer() ? 'text-primary-700' : 'text-emerald-700' }}">
                                                    {{ substr($reply->user->name, 0, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-slate-900">
                                                {{ $reply->user->name }}
                                                @if($reply->isFromStaff())
                                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                        Servura
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-sm text-slate-500">
                                                {{ $reply->created_at->format('d-m-Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="prose max-w-none">
                                        <p>{!! $reply->formatted_message !!}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-slate-900">Geen reacties</h3>
                            <p class="mt-1 text-sm text-slate-500">
                                Er zijn nog geen reacties op dit ticket.
                            </p>
                        </div>
                    @endif
                </div>

        <!-- Reply Form -->
        @if($ticket->canBeReplied())
            <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 p-6 sm:p-8">
                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-6 flex gap-3">
                        <svg class="h-5 w-5 text-emerald-500 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm text-emerald-800">{{ session('success') }}</p>
                    </div>
                @endif

                <h3 class="font-heading text-xl font-bold text-slate-900 mb-5">Reageren</h3>

                <form action="{{ route('customer.tickets.reply', $ticket) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group mb-5">
                        <label for="message" class="form-label">Je reactie</label>
                        <textarea
                            id="message"
                            name="message"
                            rows="4"
                            class="form-textarea"
                            required
                            placeholder="Typ je reactie..."
                        ></textarea>
                        @error('message')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-5">
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

                    <div class="flex justify-end">
                        <button type="submit" class="btn btn-primary">Reactie versturen</button>
                    </div>
                </form>
            </div>
        @else
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 text-center">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-amber-100 text-amber-500 mb-4">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </span>
                <h3 class="text-sm font-medium text-slate-900">Ticket is gesloten</h3>
                <p class="mt-1 text-sm text-slate-500">Dit ticket is gesloten en kan niet meer beantwoord worden.</p>
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
        fileItem.className = 'flex items-center text-sm text-slate-600';
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
