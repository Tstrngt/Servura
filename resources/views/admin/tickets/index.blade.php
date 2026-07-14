@extends('layouts.app')

@section('title', 'Tickets - Servura Admin')

@section('content')
@include('admin.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h1 class="text-2xl font-bold text-gray-900">Tickets</h1>
                    <p class="mt-2 text-sm text-gray-700">Overzicht van alle supporttickets.</p>
                </div>
            </div>
        </div>

        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <form method="GET" action="{{ route('admin.tickets.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-4">
                        <input name="search" value="{{ request('search') }}" placeholder="Zoek op nummer, titel of klant" class="form-input md:col-span-2">
                        <select name="status" class="form-input">
                            <option value="">Alle statussen</option>
                            @foreach(['open' => 'Open', 'in_progress' => 'In behandeling', 'waiting_for_customer' => 'Wacht op klant', 'resolved' => 'Opgelost', 'closed' => 'Gesloten', 'overdue' => 'Verlopen'] as $value => $label)
                                <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <div class="flex gap-2">
                            <select name="priority" class="form-input flex-1">
                                <option value="">Alle prioriteiten</option>
                                @foreach(['low' => 'Laag', 'medium' => 'Medium', 'high' => 'Hoog', 'urgent' => 'Urgent'] as $value => $label)
                                    <option value="{{ $value }}" @selected(request('priority') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="px-4 pb-6 sm:px-0">
            <div class="overflow-hidden bg-white shadow rounded-lg">
                @if($tickets->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Ticket</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Klant</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Prioriteit</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Laatste activiteit</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($tickets as $ticket)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-medium text-gray-900">{{ $ticket->ticket_number }}</p>
                                            <p class="text-sm text-gray-500">{{ $ticket->title }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $ticket->user->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $ticket->statusLabel['text'] }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $ticket->priorityLabel['text'] }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ ($ticket->last_reply_at ?? $ticket->created_at)->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-3">{{ $tickets->links() }}</div>
                @else
                    <div class="px-6 py-12 text-center text-sm text-gray-500">Geen tickets gevonden.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
