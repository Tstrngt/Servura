@extends('layouts.app')

@section('title', 'DirectAdmin login - ' . $customerService->service->title . ' - Servura')

@section('content')
@include('customer.partials.topbar')

<div class="bg-slate-50 min-h-screen pt-32">
    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-12 pb-24 text-center">
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/70 p-8">
            <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-primary-100 text-primary-600 mb-4">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                </svg>
            </span>
            <h1 class="font-heading text-2xl font-bold text-slate-900">DirectAdmin openen</h1>
            <p class="mt-2 text-slate-500">Je wordt doorgestuurd naar het DirectAdmin controlepaneel.</p>

            <form id="da-login-form" action="{{ $loginUrl }}" method="POST" target="_blank" class="mt-6">
                <input type="hidden" name="referer" value="/">
                <input type="hidden" name="username" value="{{ $username }}">
                <input type="hidden" name="password" value="{{ $password }}">
                <button type="submit" class="btn btn-primary w-full justify-center">Nu inloggen</button>
            </form>

            <p class="mt-4 text-xs text-slate-400">Als het automatisch inloggen niet werkt, kun je de gegevens ook handmatig invoeren op het DirectAdmin inlogscherm.</p>
        </div>
    </div>
</div>

<script>
    document.getElementById('da-login-form').submit();
</script>
@endsection
