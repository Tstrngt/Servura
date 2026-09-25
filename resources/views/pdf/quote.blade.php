<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>{{ $quote->quote_number }}</title>
    <style>
        @page { margin: 36px 42px; }
        body { margin: 0; color: #0f172a; font-family: DejaVu Sans, sans-serif; font-size: 11px; line-height: 1.45; }
        table { width: 100%; border-collapse: collapse; }
        .header { margin-bottom: 36px; }
        .brand { font-size: 26px; font-weight: bold; letter-spacing: -1px; }
        .dot { color: #0ea5e9; }
        .muted { color: #64748b; }
        .right { text-align: right; }
        .title { margin: 0 0 8px; font-size: 26px; }
        .addresses { margin: 28px 0 32px; }
        .address { width: 48%; vertical-align: top; padding: 18px; background: #f8fafc; border: 1px solid #e2e8f0; }
        .address-gap { width: 4%; }
        .label { margin-bottom: 8px; color: #64748b; font-size: 9px; font-weight: bold; text-transform: uppercase; letter-spacing: .7px; }
        .meta td { padding: 3px 0; }
        .proposal { margin: 0 0 26px; padding: 16px; background: #eff6ff; border-left: 3px solid #2563eb; }
        .lines th { padding: 10px 9px; color: #475569; background: #f1f5f9; font-size: 9px; text-align: left; text-transform: uppercase; }
        .lines td { padding: 11px 9px; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        .lines .number { text-align: right; white-space: nowrap; }
        .totals { width: 44%; margin: 22px 0 0 auto; }
        .totals td { padding: 6px 9px; }
        .totals .grand td { padding-top: 10px; border-top: 2px solid #0f172a; font-size: 14px; font-weight: bold; }
        .notes { margin-top: 32px; padding: 16px; background: #f8fafc; border-left: 3px solid #0ea5e9; }
        .footer { position: fixed; right: 0; bottom: -18px; left: 0; color: #94a3b8; font-size: 9px; text-align: center; }
    </style>
</head>
<body>
    <table class="header"><tr><td><div class="brand">Servura<span class="dot">.</span></div><div class="muted">Websites, hosting en digitale ondersteuning</div></td><td class="right"><h1 class="title">Offerte</h1><strong>{{ $quote->quote_number }}</strong></td></tr></table>
    <table class="addresses"><tr><td class="address"><div class="label">Van</div><strong>{{ App\Models\BillingSetting::valueFor('company_name', 'Servura') }}</strong><br>{{ App\Models\BillingSetting::valueFor('company_address', '') }}<br>@if(App\Models\BillingSetting::valueFor('company_kvk')) KvK: {{ App\Models\BillingSetting::valueFor('company_kvk') }}<br>@endif @if(App\Models\BillingSetting::valueFor('company_vat')) Btw: {{ App\Models\BillingSetting::valueFor('company_vat') }}@endif</td><td class="address-gap"></td><td class="address"><div class="label">Aan</div><strong>{{ $quote->user->company ?: $quote->user->name }}</strong><br>@if($quote->user->company)T.a.v. {{ $quote->user->name }}<br>@endif{{ trim(($quote->user->street ?? '').' '.($quote->user->house_number ?? '')) }}<br>{{ trim(($quote->user->postal_code ?? '').' '.($quote->user->city ?? '')) }}<br>{{ $quote->user->country }}</td></tr></table>
    <table class="meta" style="margin-bottom: 24px; width: 45%;"><tr><td class="muted">Offertedatum</td><td class="right">{{ $quote->quote_date->format('d-m-Y') }}</td></tr><tr><td class="muted">Geldig tot</td><td class="right">{{ $quote->valid_until->format('d-m-Y') }}</td></tr></table>
    @if($quote->proposal)<div class="proposal"><strong>Ons voorstel</strong><br>{!! nl2br(e($quote->proposal)) !!}</div>@endif
    <table class="lines"><thead><tr><th>Omschrijving</th><th class="number">Aantal</th><th class="number">Prijs</th><th class="number">Totaal</th></tr></thead><tbody>@foreach($quote->lines as $line)<tr><td>{{ $line->description }}</td><td class="number">{{ $line->quantity }}</td><td class="number">€ {{ number_format($line->unit_price, 2, ',', '.') }}</td><td class="number">€ {{ number_format($line->total, 2, ',', '.') }}</td></tr>@endforeach</tbody></table>
    <table class="totals"><tr><td class="muted">Subtotaal</td><td class="right">€ {{ number_format($quote->subtotal, 2, ',', '.') }}</td></tr><tr><td class="muted">Btw ({{ number_format($quote->vat_percentage, 0) }}%)</td><td class="right">€ {{ number_format($quote->vat_amount, 2, ',', '.') }}</td></tr><tr class="grand"><td>Totaal</td><td class="right">€ {{ number_format($quote->total, 2, ',', '.') }}</td></tr></table>
    @if($quote->notes || $quote->client_notes)<div class="notes"><strong>Toelichting</strong><br>{{ $quote->notes ?: $quote->client_notes }}</div>@endif
    <div class="footer">{{ $quote->quote_number }} · Servura · Gegenereerd op {{ now()->format('d-m-Y') }}</div>
</body>
</html>
