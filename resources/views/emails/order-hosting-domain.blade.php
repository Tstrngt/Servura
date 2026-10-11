@extends('emails.layout')

@section('subject', 'Uw hosting- en domeinbestelling is ontvangen')

@section('content')
<p>Hallo {{ $user->name }},</p>
<p>We hebben uw gecombineerde bestelling voor hosting en een domeinnaam ontvangen.</p>
<div class="box">
    <strong>Uw bestelling</strong><br>
    Ordernummer: {{ $order->order_number }}<br>
    Hostingpakket: {{ $hostingLine?->service?->title ?? '-' }}<br>
    Domeinnaam: {{ $domain }}<br>
    Domeinactie: {{ $domainLine?->description ?? 'Registratie of verhuizing' }}<br>
    Totaal: &euro; {{ number_format($order->total, 2, ',', '.') }}
</div>
<p>Na verwerking van de betaling regelen we eerst de domeinregistratie of verhuizing en activeren we vervolgens het hostingpakket. U ontvangt afzonderlijk bericht zodra ieder onderdeel gereed is.</p>
<a href="{{ $orderUrl }}" class="button">Bestelling en factuur bekijken</a>
<p>Heeft u vragen? Neem dan contact met ons op via het klantportaal.</p>
@endsection
