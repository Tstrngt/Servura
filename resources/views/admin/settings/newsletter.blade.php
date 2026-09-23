@extends('layouts.app')

@section('title', 'Nieuwsbrief - Servura Admin')

@section('content')
@include('admin.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="mx-auto w-full max-w-[1600px] px-4 py-4 sm:px-6 lg:px-8">
        <div class="py-4">
            <h1 class="text-2xl font-bold text-gray-900">Instellingen</h1>
            <p class="mt-1 text-sm text-gray-600">Nieuwsbrief-inschrijvingen beheren.</p>
        </div>

        @include('admin.partials.settings-nav')

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Aanmeldingen</h2>
                    <p class="mt-1 text-sm text-slate-600">
                        Het aanmeldformulier staat in de footer zodra "Nieuwsbrief inschakelen" aan staat onder
                        <a href="{{ route('admin.settings.general') }}" class="font-medium text-primary-600 hover:text-primary-800">Instellingen → Algemeen</a>.
                        Elke aanmelder heeft een unieke afmeldlink voor toekomstige nieuwsbrieven.
                    </p>
                </div>
                <span class="inline-flex w-fit items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $enabled ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                    {{ $enabled ? 'Formulier actief' : 'Formulier uit' }}
                </span>
            </div>

            @if($subscribers->count())
                <div class="mt-5 overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium uppercase text-slate-500">E-mail</th>
                                <th class="px-4 py-2 text-left text-xs font-medium uppercase text-slate-500">Naam</th>
                                <th class="px-4 py-2 text-left text-xs font-medium uppercase text-slate-500">Aangemeld</th>
                                <th class="px-4 py-2 text-left text-xs font-medium uppercase text-slate-500">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($subscribers as $subscriber)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-slate-900">{{ $subscriber->email }}</td>
                                    <td class="px-4 py-2 text-sm text-slate-600">{{ $subscriber->name ?: '-' }}</td>
                                    <td class="px-4 py-2 text-sm text-slate-600">{{ $subscriber->subscribed_at?->format('d-m-Y H:i') }}</td>
                                    <td class="px-4 py-2 text-sm">
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $subscriber->unsubscribed_at ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }}">
                                            {{ $subscriber->unsubscribed_at ? 'Afgemeld' : 'Actief' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $subscribers->links() }}</div>
            @else
                <p class="mt-5 text-sm text-slate-500">Nog geen aanmeldingen.</p>
            @endif
        </div>
    </div>
</div>
@endsection
