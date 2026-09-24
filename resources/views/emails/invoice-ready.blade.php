@extends('emails.layout')

@section('subject', 'Er staat een factuur voor u klaar')

@section('content')
<p>Hallo {{ $user->name }},</p>
<p>Er staat een factuur voor u klaar in uw klantportaal.</p>

<div class="box">
    <strong>Factuur</strong><br>
    Factuurnummer: {{ $invoice->invoice_number }}<br>
    Bedrag: &euro; {{ number_format($invoice->total, 2, ',', '.') }}<br>
    Vervaldatum: {{ $invoice->due_date?->format(config('site.date_format', 'd-m-Y')) }}
</div>

<a href="{{ $invoiceUrl }}" class="button">Bekijk factuur</a>
@endsection
