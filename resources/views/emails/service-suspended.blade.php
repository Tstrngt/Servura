@extends('emails.layout')

@section('subject', 'Uw dienst is tijdelijk opgeschort')

@section('content')
<p>Hallo {{ $user->name }},</p>
<p>Uw dienst <strong>{{ $service->service->title ?? '-' }}</strong> is tijdelijk opgeschort. Reden: {{ $reason }}.</p>

<p>Log in op uw klantportaal om de situatie te bekijken en indien nodig een betaling te doen of contact met ons op te nemen.</p>
<a href="{{ $dashboardUrl }}" class="button">Naar mijn account</a>
@endsection
