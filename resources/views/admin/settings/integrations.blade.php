@extends('layouts.app')

@section('title', 'Integraties - Servura Admin')

@section('content')
@include('admin.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="mx-auto w-full max-w-[1600px] px-4 py-4 sm:px-6 lg:px-8">
        <div class="py-4">
            <h1 class="text-2xl font-bold text-gray-900">Integraties</h1>
            <p class="mt-1 text-sm text-gray-600">Koppel externe diensten aan Servura.</p>
        </div>

        @include('admin.partials.settings-nav')

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <a href="{{ route('admin.settings.transip') }}" class="group relative rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 transition hover:shadow-md">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-900">TransIP</h2>
                    @if($transip['configured'])
                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Ingesteld</span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-slate-50 px-2.5 py-0.5 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-500/10">Niet ingesteld</span>
                    @endif
                </div>
                <p class="mt-2 text-sm text-slate-600">Domeinbeschikbaarheid, TLD-prijzen en later registratie/transfers via TransIP.</p>
                <p class="mt-4 text-sm text-slate-500">
                    Status: <span class="font-medium text-slate-700">{{ $transip['last_status'] }}</span>
                    @if($transip['last_checked_at'])
                        <span class="block text-xs text-slate-400">Laatste check: {{ $transip['last_checked_at'] }}</span>
                    @endif
                </p>
                <div class="mt-5 flex items-center text-sm font-semibold text-primary-600 group-hover:text-primary-700">
                    Instellingen <span aria-hidden="true" class="ml-1">→</span>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
