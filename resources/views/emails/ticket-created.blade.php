@extends('emails.layout')

@section('subject', 'We hebben uw aanvraag ontvangen')

@section('content')
<p>Hallo {{ $user->name }},</p>
<p>We hebben uw aanvraag ontvangen. Binnen 48 uur nemen we contact met u op.</p>

<div class="box">
    <strong>Ticketnummer:</strong> {{ $ticket->ticket_number }}<br>
    <strong>Onderwerp:</strong> {{ $ticket->title }}
</div>

<a href="{{ $ticketUrl }}" class="button">Bekijk aanvraag</a>
@endsection
