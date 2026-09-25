@extends('emails.layout')

@section('subject', 'Uw factuur staat klaar')

@section('content')
<p>Hallo {{ $user->name }},</p>
<p>Uw factuur is opgesteld en staat klaar in het klantportaal. Hieronder vindt u de belangrijkste betaalgegevens. De volledig opgemaakte factuur is ook als PDF bijgevoegd voor uw administratie.</p>

<div class="box">
    <strong>Factuur {{ $invoice->invoice_number }}</strong><br>
    Factuurdatum: {{ $invoice->invoice_date?->format(config('site.date_format', 'd-m-Y')) }}<br>
    Totaal inclusief btw: &euro; {{ number_format($invoice->total, 2, ',', '.') }}<br>
    Vervaldatum: {{ $invoice->due_date?->format(config('site.date_format', 'd-m-Y')) }}
</div>

<p>Via onderstaande knop kunt u de factuur bekijken en, indien van toepassing, veilig online betalen. Na ontvangst van de betaling krijgt u automatisch een bevestiging.</p>
<a href="{{ $invoiceUrl }}" class="button">Bekijk en betaal factuur</a>

<p>Heeft u een vraag over een factuurregel of klopt er iets niet? Neem dan vóór de vervaldatum contact met ons op, zodat we dit samen kunnen controleren.</p>
<p class="small">Vermeld bij contact factuurnummer {{ $invoice->invoice_number }}.</p>
@endsection
