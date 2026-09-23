@extends('layouts.app')

@section('title', 'Mijn aanvragen - Servura')

@section('content')
@include('customer.partials.topbar')

<!-- Tickets Content -->
<div class="bg-slate-50 min-h-screen pt-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 pb-24">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
            <div>
                <h1 class="font-heading text-3xl font-bold text-slate-900">Mijn aanvragen</h1>
                <p class="mt-2 text-lg text-slate-500">Beheer je support tickets en volg de status van je aanvragen.</p>
            </div>
            <div class="shrink-0">
                <a href="{{ route('customer.tickets.create') }}" class="btn btn-primary">
                    Nieuw Ticket
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                    <select class="form-input" id="status-filter">
                                <option value="">Alle statussen</option>
                                <option value="open">Open</option>
                                <option value="in_progress">In behandeling</option>
                                <option value="waiting_for_customer">Wacht op klant</option>
                                <option value="resolved">Opgelost</option>
                                <option value="closed">Gesloten</option>
                            </select>
                        </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Prioriteit</label>
                    <select class="form-input" id="priority-filter">
                                <option value="">Alle prioriteiten</option>
                                <option value="low">Laag</option>
                                <option value="medium">Medium</option>
                                <option value="high">Hoog</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Categorie</label>
                    <select class="form-input" id="category-filter">
                                <option value="">Alle categorieën</option>
                                <option value="technical">Technisch</option>
                                <option value="billing">Facturatie</option>
                                <option value="general">Algemeen</option>
                                <option value="feature_request">Feature verzoek</option>
                                <option value="bug_report">Bug report</option>
                            </select>
                        </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">&nbsp;</label>
                    <button type="button" class="btn btn-outline w-full" onclick="resetFilters()">
                        Reset Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Tickets Table -->
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 p-6">
                    @if($tickets->count() > 0)
                        <div class="space-y-3 md:hidden">
                            @foreach($tickets as $ticket)
                                <a href="{{ route('customer.tickets.show', $ticket) }}" class="block rounded-xl bg-slate-50 p-4 ring-1 ring-slate-200">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0"><p class="text-xs font-semibold text-slate-500">{{ $ticket->ticket_number }}</p><h2 class="mt-1 truncate font-semibold text-slate-900">{{ $ticket->title }}</h2></div>
                                        <span class="shrink-0 rounded-full bg-{{ $ticket->statusLabel['color'] }}-100 px-2.5 py-1 text-xs font-medium text-{{ $ticket->statusLabel['color'] }}-800">{{ $ticket->statusLabel['text'] }}</span>
                                    </div>
                                    <p class="mt-3 text-sm leading-relaxed text-slate-500">{{ Str::limit($ticket->description, 110) }}</p>
                                    <div class="mt-4 flex items-center justify-between text-xs text-slate-500"><span>{{ $ticket->categoryLabel }}</span><span>{{ ($ticket->last_reply_at ?? $ticket->created_at)->diffForHumans() }}</span></div>
                                </a>
                            @endforeach
                        </div>
                        <div class="hidden overflow-x-auto md:block">
                            <table class="min-w-full divide-y divide-slate-100">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                            Ticket #
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                            Titel
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                            Prioriteit
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                            Categorie
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                            Laatste reactie
                                        </th>
                                        <th class="relative px-6 py-3">
                                            <span class="sr-only">Bekijk</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-100">
                                    @foreach($tickets as $ticket)
                                        <tr class="hover:bg-slate-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                                {{ $ticket->ticket_number }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-slate-900">
                                                    {{ $ticket->title }}
                                                </div>
                                                <div class="text-sm text-slate-500">
                                                    {{ Str::limit($ticket->description, 100) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $ticket->statusLabel['color'] }}-100 text-{{ $ticket->statusLabel['color'] }}-800">
                                                    {{ $ticket->statusLabel['text'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $ticket->priorityLabel['color'] }}-100 text-{{ $ticket->priorityLabel['color'] }}-800">
                                                    {{ $ticket->priorityLabel['text'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                                {{ $ticket->categoryLabel }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                                {{ $ticket->last_reply_at ? $ticket->last_reply_at->diffForHumans() : $ticket->created_at->diffForHumans() }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('customer.tickets.show', $ticket) }}" class="text-primary-600 hover:text-primary-900">
                                                    Bekijk
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $tickets->links() }}
                        </div>
                @else
                    <div class="text-center py-12">
                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400 mb-4">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </span>
                        <h3 class="text-sm font-medium text-slate-900">Geen tickets</h3>
                        <p class="mt-1 text-sm text-slate-500">Je hebt nog geen support tickets aangemaakt.</p>
                        <div class="mt-5">
                            <a href="{{ route('customer.tickets.create') }}" class="btn btn-primary">Maak je eerste ticket aan</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function resetFilters() {
    document.getElementById('status-filter').value = '';
    document.getElementById('priority-filter').value = '';
    document.getElementById('category-filter').value = '';
    navigateTo('{{ route('customer.tickets.index') }}');
}

// Filter functionality
document.getElementById('status-filter').addEventListener('change', applyFilters);
document.getElementById('priority-filter').addEventListener('change', applyFilters);
document.getElementById('category-filter').addEventListener('change', applyFilters);

function applyFilters() {
    const status = document.getElementById('status-filter').value;
    const priority = document.getElementById('priority-filter').value;
    const category = document.getElementById('category-filter').value;

    const params = new URLSearchParams();
    if (status) params.append('status', status);
    if (priority) params.append('priority', priority);
    if (category) params.append('category', category);

    const url = params.toString() ? '{{ route('customer.tickets.index') }}?' + params.toString() : '{{ route('customer.tickets.index') }}';
    navigateTo(url);
}

function navigateTo(url) {
    if (window.Turbo) {
        window.Turbo.visit(url);
    } else {
        window.location.href = url;
    }
}
</script>
@endsection
