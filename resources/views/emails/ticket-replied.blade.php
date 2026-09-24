@extends('emails.layout')

@section('subject', 'Er is gereageerd op uw aanvraag')

@section('content')
<p>Hallo {{ $user->name }},</p>
<p>Er is gereageerd op uw aanvraag <strong>{{ $ticket->ticket_number }}</strong>: {{ $ticket->title }}.</p>
<a href="{{ $ticketUrl }}" class="button">Bekijk reactie</a>
@endsection
