@extends('layouts.app')

@section('title', 'Opzegverzoeken - Servura Admin')

@section('content')
@include('admin.partials.sidebar')

<div class="min-h-screen bg-gray-50 lg:pl-64">
    <main class="mx-auto w-full max-w-[1600px] px-4 py-4 sm:px-6 lg:px-8">
        <header class="py-4">
            <h1 class="text-2xl font-bold text-gray-900">Klanten</h1>
            <p class="mt-1 text-sm text-gray-600">Beheer klanten, supporttickets en opzegverzoeken.</p>
        </header>

        @include('admin.partials.customers-nav')

        @if(session('success'))<div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('success') }}</div>@endif

        <div class="space-y-4">
            @forelse($cancellations as $cancellation)
                <article class="rounded-lg bg-white p-6 shadow">
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="font-heading text-lg font-bold text-slate-900">{{ $cancellation->customerService->service->title }}</h2>
                                <span class="rounded-md bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-600">{{ ucfirst($cancellation->status) }}</span>
                            </div>
                            <p class="mt-2 text-sm text-slate-500">{{ $cancellation->user->name }} · aangevraagd {{ $cancellation->requested_at->format('d-m-Y') }}</p>
                            @if($cancellation->reason)<p class="mt-3 max-w-2xl text-sm text-slate-700">{{ $cancellation->reason }}</p>@endif
                            @if($cancellation->ticket)<a href="{{ route('admin.tickets.show', $cancellation->ticket) }}" class="mt-3 inline-block text-sm font-semibold text-primary-600">Open gekoppeld ticket</a>@endif
                        </div>

                        @if($cancellation->status === 'pending')
                            <div class="grid w-full gap-3 lg:max-w-xl lg:grid-cols-[1fr_auto]">
                                <form action="{{ route('admin.service-cancellations.approve', $cancellation) }}" method="POST" class="grid gap-3 rounded-xl bg-slate-50 p-4 sm:grid-cols-2">
                                    @csrf
                                    <div><label class="form-label">Einddatum</label><input type="date" name="effective_at" value="{{ $cancellation->effective_at->format('Y-m-d') }}" required class="form-input mt-1 w-full"></div>
                                    <div><label class="form-label">Gebruikskosten</label><input type="number" step="0.01" min="0" name="estimated_usage_cost" value="{{ $cancellation->estimated_usage_cost }}" required class="form-input mt-1 w-full"></div>
                                    <div class="sm:col-span-2"><label class="form-label">Interne notitie</label><textarea name="admin_notes" rows="2" class="form-input mt-1 w-full"></textarea></div>
                                    <button type="submit" class="btn btn-primary sm:col-span-2">Goedkeuren</button>
                                </form>
                                <form action="{{ route('admin.service-cancellations.reject', $cancellation) }}" method="POST" class="rounded-xl bg-rose-50 p-4">
                                    @csrf
                                    <label class="form-label">Reden afwijzing</label>
                                    <textarea name="admin_notes" rows="3" required class="form-input mt-1 w-full lg:w-48"></textarea>
                                    <button type="submit" class="mt-3 w-full rounded-lg bg-rose-600 px-3 py-2 text-sm font-semibold text-white hover:bg-rose-700">Afwijzen</button>
                                </form>
                            </div>
                        @else
                            <dl class="grid grid-cols-2 gap-4 text-sm">
                                <div><dt class="text-slate-500">Einddatum</dt><dd class="mt-1 font-semibold text-slate-900">{{ $cancellation->effective_at->format('d-m-Y') }}</dd></div>
                                <div><dt class="text-slate-500">Kosten</dt><dd class="mt-1 font-semibold text-slate-900">€ {{ number_format($cancellation->estimated_usage_cost, 2, ',', '.') }}</dd></div>
                            </dl>
                        @endif
                    </div>
                </article>
            @empty
                <div class="rounded-lg bg-white p-12 text-center text-sm text-gray-500 shadow">Er zijn geen opzegverzoeken.</div>
            @endforelse
        </div>
        <div class="mt-6">{{ $cancellations->links() }}</div>
    </main>
</div>
@endsection
