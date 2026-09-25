@extends('layouts.app')
@section('title', 'E-mailtemplates - Servura Admin')
@section('content')
@include('admin.partials.sidebar')
<div class="min-h-screen bg-slate-50 lg:pl-64"><main class="mx-auto max-w-[1600px] px-4 py-8 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-slate-900">E-mailtemplates</h1><p class="mt-1 text-sm text-slate-600">Beheer ontvanger, onderwerp en HTML-inhoud van automatische e-mails.</p>
    <div class="mt-6">@include('admin.partials.settings-nav')</div>
    @if(session('success'))<div class="mb-6 rounded-xl bg-emerald-50 p-4 text-sm text-emerald-800 ring-1 ring-emerald-200">{{ session('success') }}</div>@endif
    <div class="mb-6 rounded-2xl bg-slate-900 p-6 text-white"><h2 class="font-semibold">Beschikbare variabelen</h2><div class="mt-3 flex flex-wrap gap-2">@foreach($variables as $variable)<code class="rounded-lg bg-white/10 px-2.5 py-1 text-xs text-slate-200">{{ $variable }}</code>@endforeach</div></div>
    <div class="space-y-5">
    @foreach($templates as $template)
        <form method="POST" action="{{ route('admin.settings.email-templates.update') }}" class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200" x-data="{ mode: 'visual', html: @js($template['html']) }">
            @csrf @method('PUT')<input type="hidden" name="template_key" value="{{ $template['key'] }}">
            <div class="mb-5 flex items-center justify-between"><div><h2 class="font-semibold text-slate-900">{{ $template['label'] }}</h2><p class="text-xs text-slate-500">{{ $template['key'] }}</p></div><button class="btn btn-primary" type="submit">Opslaan</button></div>
            @if($loop->first)<div class="mb-5"><label class="form-label">Ontvanger contactformulier</label><input type="email" name="contact_form_recipient" value="{{ $recipient }}" class="form-input mt-1 w-full" required></div>@else<input type="hidden" name="contact_form_recipient" value="{{ $recipient }}">@endif
            <label class="form-label">Onderwerp</label><input name="subject" value="{{ $template['subject'] }}" class="form-input mt-1 w-full" placeholder="Leeg gebruikt de standaardtekst">
            <div class="mt-5 flex gap-2"><button type="button" @click="mode='visual'" :class="mode==='visual' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600'" class="rounded-lg px-3 py-2 text-sm font-semibold">Visueel</button><button type="button" @click="mode='html'" :class="mode==='html' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600'" class="rounded-lg px-3 py-2 text-sm font-semibold">HTML</button></div>
            <div x-show="mode==='visual'" class="mt-3"><div class="mb-2 flex gap-2"><button type="button" class="btn btn-outline px-3 py-1.5" @click="document.execCommand('bold')"><strong>B</strong></button><button type="button" class="btn btn-outline px-3 py-1.5" @click="document.execCommand('italic')"><em>I</em></button><button type="button" class="btn btn-outline px-3 py-1.5" @click="document.execCommand('insertUnorderedList')">Lijst</button><button type="button" class="btn btn-outline px-3 py-1.5" @click="const url=prompt('Link'); if(url) document.execCommand('createLink', false, url)">Link</button></div><div contenteditable="true" class="min-h-56 rounded-xl border border-slate-300 bg-white p-4 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-100" x-html="html" @input="html=$event.target.innerHTML"></div></div>
            <textarea x-show="mode==='html'" x-model="html" class="form-input mt-3 min-h-64 w-full font-mono text-xs" spellcheck="false"></textarea>
            <input type="hidden" name="html" :value="html"><p class="mt-2 text-xs text-slate-500">Laat HTML leeg om de standaard Servura-template te gebruiken.</p>
        </form>
    @endforeach
    </div>
</main></div>
@endsection
