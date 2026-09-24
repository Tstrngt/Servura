@extends('emails.layout')

@section('subject', 'Bedankt voor uw bestelling')

@section('content')
<p>Hallo {{ $user->name }},</p>
<p>Bedankt voor uw bestelling bij {{ config('site.name', config('app.name')) }}. We hebben uw order ontvangen en gaan direct voor u aan de slag.</p>

<div class="box">
    <strong>Bestelling</strong><br>
    Ordernummer: {{ $order->order_number }}<br>
    Dienst: {{ $order->service->title ?? '-' }}<br>
    Totaal: &euro; {{ number_format($order->total, 2, ',', '.') }}
</div>

<p>U kunt de status van uw bestelling volgen via uw account:</p>
<a href="{{ $orderUrl }}" class="button">Bekijk mijn bestelling</a>

<p>Heeft u vragen? Neem contact met ons op via het klantportaal.</p>
@endsection
