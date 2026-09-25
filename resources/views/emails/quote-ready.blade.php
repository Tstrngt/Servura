@extends('emails.layout')

@section('subject', 'Uw persoonlijke offerte staat klaar')

@section('content')
<p>Hallo {{ $user->name }},</p>
<p>Bedankt voor uw aanvraag. Op basis van uw wensen hebben we een persoonlijke offerte samengesteld. Daarin vindt u een helder overzicht van ons voorstel, de werkzaamheden en de bijbehorende investering.</p>

<div class="box">
    <strong>Offerte {{ $quote->quote_number }}</strong><br>
    Offertedatum: {{ $quote->quote_date?->format(config('site.date_format', 'd-m-Y')) }}<br>
    Geldig tot: {{ $quote->valid_until?->format(config('site.date_format', 'd-m-Y')) }}<br>
    Totaal inclusief btw: &euro; {{ number_format($quote->total, 2, ',', '.') }}
</div>

<p>De volledig opgemaakte offerte is als PDF bij deze e-mail gevoegd. In uw klantportaal kunt u de offerte ook online bekijken en direct accepteren of afwijzen.</p>
<a href="{{ $quoteUrl }}" class="button">Bekijk en beoordeel offerte</a>

<p>Heeft u nog vragen of wilt u iets aanpassen? Laat het ons weten; we denken graag met u mee voordat u akkoord geeft.</p>
<p class="small">Vermeld bij contact offertenummer {{ $quote->quote_number }}, dan kunnen we u sneller helpen.</p>
@endsection
