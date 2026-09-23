<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>{{ $invoice->invoice_number }}</title>
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
        .meta td { padding: 3px 0; }
        .addresses { margin: 28px 0 32px; }
        .address { width: 48%; vertical-align: top; padding: 18px; background: #f8fafc; border: 1px solid #e2e8f0; }
        .address-gap { width: 4%; }
        .label { margin-bottom: 8px; color: #64748b; font-size: 9px; font-weight: bold; text-transform: uppercase; letter-spacing: .7px; }
        .lines th { padding: 10px 9px; color: #475569; background: #f1f5f9; font-size: 9px; text-align: left; text-transform: uppercase; }
        .lines td { padding: 11px 9px; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        .lines .number { text-align: right; white-space: nowrap; }
        .totals { width: 44%; margin: 22px 0 0 auto; }
        .totals td { padding: 6px 9px; }
        .totals .grand td { padding-top: 10px; border-top: 2px solid #0f172a; font-size: 14px; font-weight: bold; }
        .status { display: inline-block; margin-top: 10px; padding: 5px 9px; color: #065f46; background: #d1fae5; border-radius: 4px; font-size: 9px; font-weight: bold; text-transform: uppercase; }
        .notes { margin-top: 32px; padding: 16px; background: #f8fafc; border-left: 3px solid #0ea5e9; }
        .footer { position: fixed; right: 0; bottom: -18px; left: 0; color: #94a3b8; font-size: 9px; text-align: center; }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td><div class="brand">Servura<span class="dot">.</span></div><div class="muted">Websites, hosting en digitale ondersteuning</div></td>
            <td class="right"><h1 class="title">Factuur</h1><strong>{{ $invoice->invoice_number }}</strong></td>
        </tr>
    </table>

    <table class="addresses">
        <tr>
            <td class="address">
                <div class="label">Van</div>
                <strong>{{ App\Models\BillingSetting::valueFor('company_name', 'Servura') }}</strong><br>
                {{ App\Models\BillingSetting::valueFor('company_address', '') }}<br>
                @if(App\Models\BillingSetting::valueFor('company_kvk')) KvK: {{ App\Models\BillingSetting::valueFor('company_kvk') }}<br>@endif
                @if(App\Models\BillingSetting::valueFor('company_vat')) Btw: {{ App\Models\BillingSetting::valueFor('company_vat') }}@endif
            </td>
            <td class="address-gap"></td>
            <td class="address">
                <div class="label">Aan</div>
                <strong>{{ $invoice->user->company ?: $invoice->user->name }}</strong><br>
                @if($invoice->user->company)T.a.v. {{ $invoice->user->name }}<br>@endif
                {{ trim(($invoice->user->street ?? '') . ' ' . ($invoice->user->house_number ?? '')) }}<br>
                {{ trim(($invoice->user->postal_code ?? '') . ' ' . ($invoice->user->city ?? '')) }}<br>
                {{ $invoice->user->country }}
            </td>
        </tr>
    </table>

    <table class="meta" style="margin-bottom: 24px; width: 45%;">
        <tr><td class="muted">Factuurdatum</td><td class="right">{{ $invoice->invoice_date->format('d-m-Y') }}</td></tr>
        <tr><td class="muted">Vervaldatum</td><td class="right">{{ $invoice->due_date->format('d-m-Y') }}</td></tr>
        @if($invoice->paid_at)<tr><td class="muted">Betaald op</td><td class="right">{{ $invoice->paid_at->format('d-m-Y') }}</td></tr>@endif
    </table>

    <table class="lines">
        <thead><tr><th>Omschrijving</th><th class="number">Aantal</th><th class="number">Prijs</th><th class="number">Totaal</th></tr></thead>
        <tbody>
            @foreach($invoice->lines as $line)
                <tr>
                    <td>{{ $line->description }}</td>
                    <td class="number">{{ $line->quantity }}</td>
                    <td class="number">€ {{ number_format($line->unit_price, 2, ',', '.') }}</td>
                    <td class="number">€ {{ number_format($line->total, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr><td class="muted">Subtotaal</td><td class="right">€ {{ number_format($invoice->subtotal, 2, ',', '.') }}</td></tr>
        <tr><td class="muted">Btw ({{ number_format($invoice->vat_percentage, 0) }}%)</td><td class="right">€ {{ number_format($invoice->vat_amount, 2, ',', '.') }}</td></tr>
        <tr class="grand"><td>Totaal</td><td class="right">€ {{ number_format($invoice->total, 2, ',', '.') }}</td></tr>
    </table>

    @if($invoice->status === 'betaald')<div class="right"><span class="status">Betaald</span></div>@endif

    @if($invoice->notes)<div class="notes"><strong>Opmerking</strong><br>{{ $invoice->notes }}</div>@endif

    <div class="footer">{{ $invoice->invoice_number }} · Servura · Gegenereerd op {{ now()->format('d-m-Y') }}</div>
</body>
</html>
