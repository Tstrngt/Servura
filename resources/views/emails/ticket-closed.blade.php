@extends('emails.layout')

@section('subject', 'Uw aanvraag is gesloten')

@section('content')
<p>Hallo {{ $user->name }},</p>
<p>Uw aanvraag <strong>{{ $ticket->ticket_number }}</strong>: {{ $ticket->title }} is gesloten.</p>
<p>Als u nog vragen heeft, kunt u eenvoudig een nieuwe aanvraag openen via uw klantportaal.</p>
<a href="{{ $ticketUrl }}" class="button">Bekijk aanvraag</a>
@endsection
