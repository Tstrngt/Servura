@extends('emails.layout')

@section('subject', 'Bevestig uw e-mailadres')

@section('content')
<p>Hallo {{ $user->name }},</p>
<p>Bevestig uw e-mailadres door op onderstaande knop te klikken. Zo weet u zeker dat u alle belangrijke berichten van uw account ontvangt.</p>

<a href="{{ $verificationUrl }}" class="button">E-mailadres bevestigen</a>

<p class="small">Werkt de knop niet? Kopieer deze link in uw browser:<br>{{ $verificationUrl }}</p>
@endsection
