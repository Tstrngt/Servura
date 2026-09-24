@extends('emails.layout')

@section('subject', 'Er staat een offerte voor u klaar')

@section('content')
<p>Hallo {{ $user->name }},</p>
<p>Er staat een offerte voor u klaar in uw klantportaal.</p>

<div class="box">
    <strong>Offerte</strong><br>
    Offertenummer: {{ $quote->quote_number }}<br>
    Totaal: &euro; {{ number_format($quote->total, 2, ',', '.') }}
</div>

<a href="{{ $quoteUrl }}" class="button">Bekijk offerte</a>
@endsection
