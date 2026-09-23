@extends('layouts.app')

@section('title', 'Tickets - Servura Admin')

@section('content')
@include('admin.partials.sidebar')

<div class="min-h-screen bg-gray-50 lg:pl-64">
    <main class="mx-auto w-full max-w-[1600px] px-4 py-4 sm:px-6 lg:px-8">
        <header class="py-4">
            <h1 class="text-2xl font-bold text-gray-900">Klanten</h1>
            <p class="mt-1 text-sm text-gray-600">Beheer klanten, supporttickets en opzegverzoeken.</p>
        </header>

        @include('admin.partials.customers-nav')

        <div class="mb-4 flex gap-2 overflow-x-auto pb-1">
            @foreach(['' => 'Alle tickets', 'unassigned' => 'Nieuw & onbeheerd', 'mine' => 'Aan mij toegewezen', 'waiting' => 'Wacht op klant', 'resolved' => 'Afgerond'] as $queue => $label)
                <a href="{{ route('admin.tickets.index', array_filter(['queue' => $queue])) }}" class="whitespace-nowrap rounded-lg px-4 py-2 text-sm font-medium {{ request('queue', '') === $queue ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 ring-1 ring-slate-200 hover:bg-slate-50' }}">{{ $label }}</a>
            @endforeach
        </div>

        <form method="GET" action="{{ route('admin.tickets.index') }}" class="mb-6 grid grid-cols-1 items-end gap-4 sm:grid-cols-2 xl:grid-cols-[minmax(300px,2fr)_repeat(2,minmax(170px,1fr))_auto]">
            @if(request('queue'))
                <input type="hidden" name="queue" value="{{ request('queue') }}">
            @endif
            <div>
                <label for="ticket-search" class="mb-1 block text-sm font-medium text-gray-700">Zoeken</label>
                <input id="ticket-search" name="search" value="{{ request('search') }}" placeholder="Nummer, titel of klant..." class="form-input w-full">
            </div>
            <div>
                <label for="ticket-status" class="mb-1 block text-sm font-medium text-gray-700">Status</label>
                <select id="ticket-status" name="status" class="form-input w-full">
                    <option value="">Alle statussen</option>
                    @foreach(['open' => 'Open', 'in_progress' => 'In behandeling', 'waiting_for_customer' => 'Wacht op klant', 'resolved' => 'Opgelost', 'closed' => 'Gesloten', 'overdue' => 'Verlopen'] as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="ticket-priority" class="mb-1 block text-sm font-medium text-gray-700">Prioriteit</label>
                <select id="ticket-priority" name="priority" class="form-input w-full">
                    <option value="">Alle prioriteiten</option>
                    @foreach(['low' => 'Laag', 'medium' => 'Medium', 'high' => 'Hoog', 'urgent' => 'Urgent'] as $value => $label)
                        <option value="{{ $value }}" @selected(request('priority') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" class="btn btn-outline">Filteren</button>
                @if(request()->hasAny(['search', 'status', 'priority']))
                    <a href="{{ route('admin.tickets.index', array_filter(['queue' => request('queue')])) }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Reset</a>
                @endif
            </div>
        </form>

        <div class="overflow-hidden rounded-lg bg-white shadow">
            @if($tickets->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Ticket</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Klant</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Prioriteit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Laatste activiteit</th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase text-gray-500">Acties</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($tickets as $ticket)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.tickets.show', $ticket) }}" class="text-sm font-medium text-primary-600 hover:text-primary-500">{{ $ticket->ticket_number }}</a>
                                        <p class="text-sm text-gray-500">{{ $ticket->title }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        <div>{{ $ticket->user->name }}</div>
                                        <div class="mt-1 text-xs text-slate-400">{{ $ticket->assignedTo ? 'Behandelaar: ' . $ticket->assignedTo->name : 'Nog niet opgepakt' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap"><span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-{{ $ticket->statusLabel['color'] }}-100 text-{{ $ticket->statusLabel['color'] }}-800">{{ $ticket->statusLabel['text'] }}</span></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-{{ $ticket->priorityLabel['color'] }}-100 text-{{ $ticket->priorityLabel['color'] }}-800">{{ $ticket->priorityLabel['text'] }}</span></td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ ($ticket->last_reply_at ?? $ticket->created_at)->diffForHumans() }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            @if(!$ticket->assigned_to)
                                                <form method="POST" action="{{ route('admin.tickets.claim', $ticket) }}">
                                                    @csrf
                                                    <button type="submit" class="text-sm font-medium text-emerald-600 hover:text-emerald-800">Oppakken</button>
                                                </form>
                                            @endif
                                            <a href="{{ route('admin.tickets.show', $ticket) }}" class="text-sm font-medium text-primary-600 hover:text-primary-500">Bekijk</a>
                                            <form method="POST" action="{{ route('admin.tickets.destroy', $ticket) }}" onsubmit="return confirm('Weet je zeker dat je dit ticket wilt verwijderen?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-600" title="Verwijderen" aria-label="Ticket {{ $ticket->ticket_number }} verwijderen">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-gray-200 px-6 py-4">{{ $tickets->withQueryString()->links() }}</div>
            @else
                <div class="px-6 py-12 text-center text-sm text-gray-500">Geen tickets gevonden.</div>
            @endif
        </div>
    </main>
</div>
@endsection
