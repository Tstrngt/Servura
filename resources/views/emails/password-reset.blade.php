@extends('emails.layout')

@section('subject', 'Stel een nieuw wachtwoord in')

@section('content')
<p>Hallo {{ $user->name }},</p>
<p>Er is een verzoek gedaan om het wachtwoord van uw Servura-account opnieuw in te stellen.</p>

<p><a href="{{ $resetUrl }}" class="button">Nieuw wachtwoord instellen</a></p>

<p class="small">Heeft u dit niet aangevraagd? Dan kunt u deze e-mail negeren. De link verloopt automatisch.</p>
<p class="small">Werkt de knop niet? Kopieer deze link in uw browser:<br>{{ $resetUrl }}</p>
@endsection
