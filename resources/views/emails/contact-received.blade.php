@extends('emails.layout')

@section('subject', 'Nieuw contactbericht')

@section('content')
<p>Er is een nieuw bericht verstuurd via het contactformulier op de website.</p>

<div class="box">
    <strong>Contactgegevens</strong><br>
    Naam: {{ $contactMessage->name }}<br>
    E-mailadres: <a href="mailto:{{ $contactMessage->email }}">{{ $contactMessage->email }}</a><br>
    @if($contactMessage->company)Bedrijf: {{ $contactMessage->company }}<br>@endif
    @if($contactMessage->phone)Telefoonnummer: {{ $contactMessage->phone }}<br>@endif
    @if($contactMessage->current_website)Huidige website: {{ $contactMessage->current_website }}@endif
</div>

<p><strong>Onderwerp</strong><br>{{ $contactMessage->subject }}</p>

@if($contactMessage->looking_for)
<p><strong>Waar is de klant naar op zoek?</strong><br>{!! nl2br(e($contactMessage->looking_for)) !!}</p>
@endif

<p><strong>Bericht</strong><br>{!! nl2br(e($contactMessage->message)) !!}</p>

<p class="small">Je kunt rechtstreeks op deze e-mail antwoorden; het antwoord wordt naar {{ $contactMessage->email }} gestuurd.</p>
@endsection
