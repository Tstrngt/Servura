<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title>{{ $invoice->invoice_number }}</title>
<style>
@page { margin: 30px 40px 55px; }
* { box-sizing: border-box; }
body { margin: 0; color: #172033; font-family: DejaVu Sans, sans-serif; font-size: 9.5px; line-height: 1.45; }
table { width: 100%; border-collapse: collapse; }
.header { margin-bottom: 28px; }
.logo { width: 172px; height: auto; }
.document-title { margin: 0; color: {{ $settings['primary_color'] }}; font-size: 26px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; }
.document-number { margin-top: 4px; color: #526175; font-size: 11px; }
.accent-line { height: 3px; margin: 0 0 24px; background: {{ $settings['accent_color'] }}; }
.meta { margin-bottom: 26px; }
.meta td { width: 50%; vertical-align: top; }
.meta-card { min-height: 118px; padding: 15px 17px; border: 1px solid #dbe3ed; background: #fff; }
.meta-gap { width: 14px !important; }
.label { margin-bottom: 7px; color: {{ $settings['primary_color'] }}; font-size: 8px; font-weight: bold; letter-spacing: .8px; text-transform: uppercase; }
.name { margin-bottom: 3px; color: #081b37; font-size: 11px; font-weight: bold; }
.invoice-meta { margin-bottom: 23px; border-top: 1px solid #dbe3ed; border-bottom: 1px solid #dbe3ed; }
.invoice-meta td { padding: 9px 8px; }
.invoice-meta .k { color: #68778b; font-size: 8px; text-transform: uppercase; }
.invoice-meta .v { color: #071d3b; font-weight: bold; }
.status { display: inline-block; padding: 4px 8px; color: #fff; background: {{ $invoice->status === 'betaald' ? '#16744a' : ($invoice->status === 'gecrediteerd' ? '#65558f' : $settings['primary_color']) }}; font-size: 8px; font-weight: bold; text-transform: uppercase; }
.lines { page-break-inside: auto; }
.lines thead { display: table-header-group; }
.lines tr { page-break-inside: avoid; page-break-after: auto; }
.lines th { padding: 9px 7px; color: #fff; background: {{ $settings['primary_color'] }}; font-size: 7.7px; text-align: left; text-transform: uppercase; }
.lines td { padding: 10px 7px; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
.lines .num { text-align: right; white-space: nowrap; }
.description { color: #101d31; font-weight: bold; }
.detail { margin-top: 3px; color: #66758a; font-size: 8px; }
.summary { margin-top: 20px; }
.payment { width: 52%; padding-right: 28px; vertical-align: top; }
.payment-box { padding: 14px 16px; border-left: 3px solid {{ $settings['accent_color'] }}; background: #f4f7fb; }
.payment-box a { color: {{ $settings['primary_color'] }}; font-weight: bold; }
.totals-wrap { width: 48%; vertical-align: top; }
.totals td { padding: 4px 6px; }
.totals .total td { padding-top: 8px; border-top: 2px solid {{ $settings['primary_color'] }}; color: {{ $settings['primary_color'] }}; font-size: 13px; font-weight: bold; }
.totals .outstanding td { padding-top: 7px; color: {{ $settings['primary_color'] }}; font-weight: bold; }
.muted { color: #68778b; }
.notes { margin-top: 20px; padding: 12px 14px; border: 1px solid #dbe3ed; }
.footer { position: fixed; right: 0; bottom: -38px; left: 0; padding-top: 8px; border-top: 1px solid #dbe3ed; color: #718096; font-size: 7.5px; }
.footer .page:after { content: counter(page); }
.preview { position: fixed; top: 250px; left: 75px; z-index: -1; color: rgba(14,165,233,.09); font-size: 66px; font-weight: bold; transform: rotate(-30deg); }
</style>
</head>
<body>
@if($isPreview)<div class="preview">CONCEPT PREVIEW</div>@endif
<table class="header"><tr>
<td><img class="logo" src="{{ $settings['logo_variant'] === 'light' ? $logoLight : $logoDark }}" alt="Servura"></td>
<td style="text-align:right"><h1 class="document-title">{{ $invoice->document_type === 'credit' ? 'Creditfactuur' : 'Factuur' }}</h1><div class="document-number">{{ $invoice->invoice_number }}</div></td>
</tr></table>
<div class="accent-line"></div>
<table class="meta"><tr>
<td><div class="meta-card"><div class="label">Van</div><div class="name">{{ $issuer['name'] ?: $issuer['trade_name'] }}</div>{{ $issuer['address'] }}<br>{{ $issuer['postal_code'] }} {{ $issuer['city'] }}<br>{{ $issuer['country'] }}<br><br>KvK {{ $issuer['kvk'] }}<br>Btw-id {{ $issuer['vat'] }}<br>{{ $issuer['email'] }}@if($issuer['phone'])<br>{{ $issuer['phone'] }}@endif</div></td>
<td class="meta-gap"></td>
<td><div class="meta-card"><div class="label">Factuur aan</div><div class="name">{{ $customer['company'] ?: $customer['name'] }}</div>@if($customer['company'])T.a.v. {{ $customer['name'] }}<br>@endif{{ $customer['address'] }}<br>{{ $customer['postal_code'] }} {{ $customer['city'] }}<br>{{ $customer['country'] }}@if($customer['vat'])<br><br>Btw-id {{ $customer['vat'] }}@endif<br>Klantnummer {{ $customer['number'] }}</div></td>
</tr></table>
<table class="invoice-meta"><tr>
<td><div class="k">Factuurdatum</div><div class="v">{{ $invoice->invoice_date->format('d-m-Y') }}</div></td>
<td><div class="k">Vervaldatum</div><div class="v">{{ $invoice->due_date->format('d-m-Y') }}</div></td>
<td><div class="k">Betalingskenmerk</div><div class="v">{{ $invoice->invoice_number }}</div></td>
<td style="text-align:right"><span class="status">{{ $invoice->statusLabel['text'] }}</span>@if($invoice->paid_at)<div class="detail">{{ $invoice->paid_at->format('d-m-Y') }}</div>@endif</td>
</tr></table>
<table class="lines"><thead><tr><th style="width:43%">Omschrijving</th><th class="num" style="width:8%">Aantal</th><th class="num" style="width:15%">Prijs excl.</th><th class="num" style="width:10%">Btw</th><th class="num" style="width:16%">Bedrag excl.</th></tr></thead><tbody>
@foreach($invoice->lines as $line)<tr>
<td><div class="description">{{ $line->description }}</div>@if($line->service_reference)<div class="detail">Dienst: {{ $line->service_reference }}</div>@endif @if($line->period_start && $line->period_end)<div class="detail">Periode: {{ $line->period_start->format('d-m-Y') }} t/m {{ $line->period_end->format('d-m-Y') }}</div>@endif</td>
<td class="num">{{ number_format($line->quantity, 0, ',', '.') }}</td><td class="num">€ {{ number_format($line->unit_price, 2, ',', '.') }}</td><td class="num">{{ number_format($line->vat_percentage ?? $invoice->vat_percentage, 0) }}%</td><td class="num">€ {{ number_format((float)$line->total - (float)$line->discount_amount, 2, ',', '.') }}</td>
</tr>@endforeach
</tbody></table>
<table class="summary"><tr>
<td class="payment"><div class="payment-box"><div class="label">Betaalinformatie</div>@if($invoice->status === 'betaald')Deze factuur is volledig betaald.@else{{ $settings['payment_text'] }}<br><br>@if($settings['iban'])IBAN: <strong>{{ $settings['iban'] }}</strong><br>Rekeninghouder: {{ $settings['account_holder'] }}<br>Kenmerk: {{ $invoice->invoice_number }}@endif @if($invoice->payment_url)<br><br><a href="{{ $invoice->payment_url }}">Online betalen</a>@endif @endif</div></td>
<td class="totals-wrap"><table class="totals"><tr><td class="muted">Subtotaal excl. btw</td><td class="num">€ {{ number_format($invoice->subtotal, 2, ',', '.') }}</td></tr>@if((float)$invoice->discount_amount !== 0.0)<tr><td class="muted">Korting</td><td class="num">- € {{ number_format(abs($invoice->discount_amount), 2, ',', '.') }}</td></tr>@endif @foreach($vatGroups as $vat)<tr><td class="muted">Btw {{ number_format($vat['rate'], 0) }}% over € {{ number_format($vat['base'], 2, ',', '.') }}</td><td class="num">€ {{ number_format($vat['amount'], 2, ',', '.') }}</td></tr>@endforeach<tr class="total"><td>Totaal incl. btw</td><td class="num">€ {{ number_format($invoice->total, 2, ',', '.') }}</td></tr><tr><td class="muted">Reeds betaald</td><td class="num">€ {{ number_format($paidAmount, 2, ',', '.') }}</td></tr><tr class="outstanding"><td>Nog te betalen</td><td class="num">€ {{ number_format($outstandingAmount, 2, ',', '.') }}</td></tr></table></td>
</tr></table>
@if($invoice->notes)<div class="notes"><strong>Toelichting</strong><br>{!! nl2br(e($invoice->notes)) !!}</div>@endif
<div class="footer"><table><tr><td>{{ $issuer['website'] }} | {{ $issuer['email'] }} | {{ $settings['footer_text'] }}</td><td style="text-align:right">{{ $invoice->invoice_number }} | pagina <span class="page"></span></td></tr></table></div>
</body></html>
