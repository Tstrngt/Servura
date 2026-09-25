@extends('emails.layout')

@section('subject', 'Betaling ontvangen')

@section('content')
<p>Hallo {{ $user->name }},</p>
<p>Bedankt, wij hebben uw betaling voor factuur <strong>{{ $invoice->invoice_number }}</strong> in goede orde ontvangen en verwerkt.</p>

<div class="box">
    <strong>Betalingsgegevens</strong><br>
    Factuurnummer: {{ $invoice->invoice_number }}<br>
    Ontvangen bedrag: &euro; {{ number_format($invoice->total, 2, ',', '.') }}<br>
    Betaald op: {{ $invoice->paid_at?->format(config('site.date_format', 'd-m-Y')) }}
</div>

<p>Eventuele gekoppelde diensten worden nu automatisch geactiveerd of door ons verder in behandeling genomen. U vindt de betaalde factuur ook als PDF in de bijlage.</p>
<a href="{{ $invoiceUrl }}" class="button">Bekijk betaalde factuur</a>

<p class="small">Heeft u vragen over deze betaling? Neem dan contact met ons op onder vermelding van {{ $invoice->invoice_number }}.</p>
@endsection
