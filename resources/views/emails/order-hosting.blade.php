@extends('emails.layout')

@section('subject', 'Uw hostingbestelling is ontvangen')

@section('content')
<p>Hallo {{ $user->name }},</p>
<p>Bedankt voor uw hostingbestelling. We hebben uw aanvraag ontvangen en bereiden het hostingaccount voor.</p>
<div class="box">
    <strong>Hostingbestelling</strong><br>
    Ordernummer: {{ $order->order_number }}<br>
    Hostingpakket: {{ $hostingLine?->service?->title ?? '-' }}<br>
    Domeinnaam: {{ $domain ?: 'Wordt later gekoppeld' }}<br>
    Totaal: &euro; {{ number_format($order->total, 2, ',', '.') }}
</div>
<p>Na verwerking van de betaling activeren we het hostingpakket. De inlog- en servergegevens ontvangt u in een afzonderlijke activatiemail.</p>
<a href="{{ $orderUrl }}" class="button">Bestelling en factuur bekijken</a>
<p>Heeft u vragen over de inrichting? Neem dan contact met ons op via het klantportaal.</p>
@endsection
