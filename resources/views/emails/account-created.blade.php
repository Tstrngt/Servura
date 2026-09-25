@extends('emails.layout')

@section('subject', 'Welkom bij '.config('site.name', config('app.name')))

@section('content')
<p>Hallo {{ $user->name }},</p>
<p>Welkom bij {{ config('site.name', config('app.name')) }}. Er is een account voor u aangemaakt, zodat u de status van uw aanvraag en bestellingen kunt volgen.</p>

<div class="box">
    <strong>Uw inloggegevens</strong><br>
    E-mailadres: {{ $user->email }}<br>
    Wachtwoord: het door u gekozen wachtwoord
</div>

<p>Bevestig uw e-mailadres via onderstaande knop:</p>
<a href="{{ $verificationUrl }}" class="button">E-mailadres bevestigen</a>

<p class="small">Werkt de knop niet? Kopieer deze link in uw browser: <br>{{ $verificationUrl }}</p>

<p>Na bevestiging kunt u inloggen op <a href="{{ route('login') }}">{{ route('login') }}</a>.</p>
@endsection
