@extends('layouts.app')

@section('title', 'Ticket ' . $ticket->ticket_number . ' - Servura Admin')

@section('content')
@include('admin.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center">
                            <a href="{{ route('admin.tickets.index') }}" class="text-primary-600 hover:text-primary-500 mr-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">
                                    {{ $ticket->ticket_number }} - {{ $ticket->title }}
                                </h1>
                                <p class="mt-1 text-sm text-gray-600">
                                    Aangemaakt op {{ $ticket->created_at->format('d-m-Y H:i') }} door
                                    <a href="{{ route('admin.customers.show', $ticket->user) }}" class="text-primary-600 hover:text-primary-500">{{ $ticket->user->name }}</a>
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @if($ticket->canBeClosed())
                                <form action="{{ route('admin.tickets.close', $ticket) }}" method="POST" class="inline" onsubmit="return confirm('Weet u zeker dat u dit ticket wilt sluiten?')">
                                    @csrf
                                    <button type="submit" class="btn btn-outline">Sluiten</button>
                                </form>
                            @else
                                <form action="{{ route('admin.tickets.reopen', $ticket) }}" method="POST" class="inline" onsubmit="return confirm('Weet u zeker dat u dit ticket wilt heropenen?')">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Heropenen</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="px-4 pb-6 sm:px-0">
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
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
            </div>
        @endif

        @if(session('error'))
            <div class="px-4 pb-6 sm:px-0">
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="px-4 py-6 sm:px-0 space-y-6">
            <!-- Row: Description + Ticket details -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                <div class="lg:col-span-2 space-y-6">
                    <!-- Description and attachments -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Omschrijving</h3>
                            <div class="prose max-w-none text-gray-700">
                                <p>{!! nl2br(e($ticket->description)) !!}</p>
                            </div>

                            @if($ticket->attachments->count() > 0)
                                <div class="mt-6">
                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Bijlagen</h4>
                                    <div class="space-y-2">
                                        @foreach($ticket->attachments as $attachment)
                                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                                                <div class="flex items-center min-w-0">
                                                    <svg class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    <div class="min-w-0">
                                                        <div class="text-sm font-medium text-gray-900 truncate">
                                                            {{ $attachment->original_name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $attachment->formatted_file_size }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex space-x-2 flex-shrink-0">
                                                    @if($attachment->isImage())
                                                        <a href="{{ route('admin.tickets.attachments.preview', $attachment) }}" target="_blank" class="text-primary-600 hover:text-primary-500 text-sm">
                                                            Voorbeeld
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('admin.tickets.attachments.download', $attachment) }}" class="text-primary-600 hover:text-primary-500 text-sm">
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

                    <!-- Replies -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                Reacties ({{ $ticket->replies->count() }})
                            </h3>

                            @if($ticket->replies->count() > 0)
                                <div class="space-y-6">
                                    @foreach($ticket->replies as $reply)
                                        <div class="border-l-4 {{ $reply->is_internal ? 'border-purple-400 bg-purple-50' : ($reply->isFromCustomer() ? 'border-blue-400' : 'border-green-400') }} pl-4 py-2 rounded-r">
                                            <div class="flex items-center mb-2">
                                                <div class="flex-shrink-0 h-8 w-8">
                                                    <div class="h-8 w-8 rounded-full {{ $reply->isFromCustomer() ? 'bg-blue-100' : ($reply->is_internal ? 'bg-purple-100' : 'bg-green-100') }} flex items-center justify-center">
                                                        <span class="text-xs font-medium {{ $reply->isFromCustomer() ? 'text-blue-700' : ($reply->is_internal ? 'text-purple-700' : 'text-green-700') }}">
                                                            {{ substr($reply->user->name, 0, 2) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $reply->user->name }}
                                                        @if($reply->is_internal)
                                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                                Intern
                                                            </span>
                                                        @elseif($reply->isFromStaff())
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
                                            <div class="prose max-w-none text-gray-700">
                                                <p>{!! $reply->formatted_message !!}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <p class="text-sm text-gray-500">Er zijn nog geen reacties op dit ticket.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Reply form -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Reageren</h3>

                            <form action="{{ route('admin.tickets.reply', $ticket) }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group mb-4">
                                    <label for="message" class="form-label">Bericht</label>
                                    <textarea id="message" name="message" rows="4" class="form-textarea" placeholder="Typ uw reactie..." required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <span class="form-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="status_after_reply" class="form-label">Status na reactie</label>
                                        <select id="status_after_reply" name="status_after_reply" class="form-input">
                                            <option value="">Huidige status behouden</option>
                                            <option value="open" {{ old('status_after_reply') == 'open' ? 'selected' : '' }}>Open</option>
                                            <option value="in_progress" {{ old('status_after_reply') == 'in_progress' ? 'selected' : '' }}>In behandeling</option>
                                            <option value="waiting_for_customer" {{ old('status_after_reply') == 'waiting_for_customer' ? 'selected' : '' }}>Wacht op klant</option>
                                            <option value="resolved" {{ old('status_after_reply') == 'resolved' ? 'selected' : '' }}>Opgelost</option>
                                        </select>
                                        @error('status_after_reply')
                                            <span class="form-error">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="flex items-end">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="is_internal" value="1" {{ old('is_internal') ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700">Interne notitie (niet zichtbaar voor klant)</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label">Bijlagen</label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600 justify-center">
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

                <div class="lg:col-span-1 space-y-6">
                    <!-- Customer card -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Klant</h3>
                            <div class="flex items-center mb-4">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-sm font-medium text-blue-700">{{ substr($ticket->user->name, 0, 2) }}</span>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $ticket->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $ticket->user->email }}</div>
                                </div>
                            </div>
                            <div class="space-y-2 text-sm border-t border-gray-100 pt-3">
                                @if($ticket->user->company)
                                    <div>
                                        <span class="font-medium text-gray-700">Bedrijf:</span>
                                        <span class="text-gray-900">{{ $ticket->user->company }}</span>
                                    </div>
                                @endif
                                @if($ticket->user->phone)
                                    <div>
                                        <span class="font-medium text-gray-700">Telefoon:</span>
                                        <span class="text-gray-900">{{ $ticket->user->phone }}</span>
                                    </div>
                                @endif
                                @if($ticket->user->street || $ticket->user->city)
                                    <div>
                                        <span class="font-medium text-gray-700">Adres:</span>
                                        <span class="text-gray-900">
                                            {{ $ticket->user->street }} {{ $ticket->user->house_number }}@if($ticket->user->postal_code || $ticket->user->city),
                                            {{ $ticket->user->postal_code }} {{ $ticket->user->city }}@endif
                                        </span>
                                    </div>
                                @endif
                                @if($ticket->user->kvk_number)
                                    <div>
                                        <span class="font-medium text-gray-700">KVK:</span>
                                        <span class="text-gray-900">{{ $ticket->user->kvk_number }}</span>
                                    </div>
                                @endif
                                @if($ticket->user->vat_number)
                                    <div>
                                        <span class="font-medium text-gray-700">BTW:</span>
                                        <span class="text-gray-900">{{ $ticket->user->vat_number }}</span>
                                    </div>
                                @endif
                            </div>
                            <a href="{{ route('admin.customers.show', $ticket->user) }}" class="mt-3 inline-block text-primary-600 hover:text-primary-500 text-sm">Klantprofiel bekijken</a>
                        </div>
                    </div>

                    <!-- Ticket details -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Ticket details</h3>
                            <div class="space-y-4">
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
                                    <div class="mt-1 text-sm text-gray-900">{{ $ticket->categoryLabel }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Toegewezen aan</label>
                                    <div class="mt-1 text-sm text-gray-900">{{ $ticket->assignedTo ? $ticket->assignedTo->name : 'Nog niet toegewezen' }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Laatste activiteit</label>
                                    <div class="mt-1 text-sm text-gray-900">{{ $ticket->last_reply_at ? $ticket->last_reply_at->format('d-m-Y H:i') : $ticket->created_at->format('d-m-Y H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assignment form -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Toewijzen</h3>
                            <form action="{{ route('admin.tickets.assign', $ticket) }}" method="POST">
                                @csrf
                                <div class="form-group mb-4">
                                    <label for="assigned_to" class="form-label">Medewerker</label>
                                    <select id="assigned_to" name="assigned_to" class="form-input">
                                        <option value="">Niet toegewezen</option>
                                        @foreach($staff as $member)
                                            <option value="{{ $member->id }}" {{ old('assigned_to', $ticket->assigned_to) == $member->id ? 'selected' : '' }}>
                                                {{ $member->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('assigned_to')
                                        <span class="form-error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-outline w-full">Opslaan</button>
                            </form>
                        </div>
                    </div>

                    <!-- Edit priority/category -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Bewerken</h3>
                            <form action="{{ route('admin.tickets.update', $ticket) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="form-group mb-4">
                                    <label for="priority" class="form-label">Prioriteit</label>
                                    <select id="priority" name="priority" class="form-input" required>
                                        <option value="low" {{ old('priority', $ticket->priority) == 'low' ? 'selected' : '' }}>Laag</option>
                                        <option value="medium" {{ old('priority', $ticket->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ old('priority', $ticket->priority) == 'high' ? 'selected' : '' }}>Hoog</option>
                                        <option value="urgent" {{ old('priority', $ticket->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    </select>
                                    @error('priority')
                                        <span class="form-error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group mb-4">
                                    <label for="category" class="form-label">Categorie</label>
                                    <select id="category" name="category" class="form-input" required>
                                        <option value="technical" {{ old('category', $ticket->category) == 'technical' ? 'selected' : '' }}>Technisch</option>
                                        <option value="billing" {{ old('category', $ticket->category) == 'billing' ? 'selected' : '' }}>Facturatie</option>
                                        <option value="general" {{ old('category', $ticket->category) == 'general' ? 'selected' : '' }}>Algemeen</option>
                                        <option value="feature_request" {{ old('category', $ticket->category) == 'feature_request' ? 'selected' : '' }}>Feature verzoek</option>
                                        <option value="bug_report" {{ old('category', $ticket->category) == 'bug_report' ? 'selected' : '' }}>Bug report</option>
                                    </select>
                                    @error('category')
                                        <span class="form-error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-outline w-full">Bijwerken</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('attachments').addEventListener('change', function(e) {
    const files = e.target.files;
    const existingList = e.target.closest('.form-group').querySelector('.file-list');
    if (existingList) {
        existingList.remove();
    }

    if (files.length === 0) {
        return;
    }

    const fileList = document.createElement('div');
    fileList.className = 'file-list mt-2 space-y-1';

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

    e.target.closest('.form-group').appendChild(fileList);
});
</script>
@endsection
