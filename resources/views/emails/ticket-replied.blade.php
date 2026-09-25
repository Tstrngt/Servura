@extends('emails.layout')

@section('subject', 'Er is gereageerd op uw aanvraag')

@section('content')
<p>Hallo {{ $user->name }},</p>
<p>Er is een nieuwe reactie geplaatst op uw aanvraag <strong>{{ $ticket->ticket_number }}</strong>: {{ $ticket->title }}.</p>

<div class="box">
    <strong>Reactie van {{ $reply->user->name }}</strong><br><br>
    {!! nl2br(e($reply->message)) !!}
</div>

<p>Via uw klantportaal kunt u de volledige aanvraag bekijken en direct reageren.</p>
<a href="{{ $ticketUrl }}" class="button">Bekijk en reageer</a>
@endsection
