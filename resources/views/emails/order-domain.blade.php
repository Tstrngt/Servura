@extends('emails.layout')

@section('subject', 'Uw domeinbestelling is ontvangen')

@section('content')
<p>Hallo {{ $user->name }},</p>
<p>We hebben uw bestelling voor <strong>{{ $domain }}</strong> ontvangen.</p>
<div class="box">
    <strong>Domeinbestelling</strong><br>
    Ordernummer: {{ $order->order_number }}<br>
    Domeinnaam: {{ $domain }}<br>
    Actie: {{ $domainLine?->description ?? 'Domeinregistratie' }}<br>
    Totaal: &euro; {{ number_format($order->total, 2, ',', '.') }}
</div>
<p>Na verwerking van de betaling starten we de registratie of verhuizing. U ontvangt opnieuw bericht zodra het domein actief is.</p>
<a href="{{ $orderUrl }}" class="button">Bestelling en factuur bekijken</a>
<p>Heeft u vragen over de registratie of verhuizing? Neem dan contact met ons op via het klantportaal.</p>
@endsection
