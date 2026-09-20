<!DOCTYPE html>
<html lang="nl">
<body style="margin:0;background:#f1f5f9;font-family:Arial,sans-serif;color:#0f172a">
@php
    $isSuspended = $type === 'suspended';
    $heading = match($type) {
        'due_reminder' => 'Herinnering: uw factuur vervalt vandaag',
        'overdue_reminder' => 'Uw factuur is nog niet betaald',
        default => 'Uw dienst is tijdelijk geschorst',
    };
@endphp
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="padding:32px 16px"><tr><td align="center">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:620px;background:#ffffff;border-radius:16px;overflow:hidden">
<tr><td style="padding:28px 32px;background:#0f172a;color:#ffffff"><strong style="font-size:24px">Servura</strong></td></tr>
<tr><td style="padding:32px">
<h1 style="margin:0 0 16px;font-size:24px">{{ $heading }}</h1>
<p style="margin:0 0 18px;line-height:1.6;color:#475569">Beste {{ $invoice->user->name }},</p>
@if($isSuspended)
<p style="margin:0 0 20px;line-height:1.6;color:#475569">Omdat factuur <strong>{{ $invoice->invoice_number }}</strong> na de respijttermijn nog niet is betaald, is de gekoppelde dienst tijdelijk geschorst. Na een bevestigde betaling wordt de dienst automatisch hersteld.</p>
@else
<p style="margin:0 0 20px;line-height:1.6;color:#475569">Factuur <strong>{{ $invoice->invoice_number }}</strong> met vervaldatum {{ $invoice->due_date->format('d-m-Y') }} staat nog open. Betaal op tijd om onderbreking van uw dienst te voorkomen.</p>
@endif
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:24px;background:#f8fafc;border-radius:12px"><tr><td style="padding:18px"><span style="display:block;color:#64748b;font-size:13px">Openstaand bedrag</span><strong style="display:block;margin-top:4px;font-size:24px">€ {{ number_format($invoice->total, 2, ',', '.') }}</strong></td></tr></table>
<a href="{{ route('customer.invoices.show', $invoice) }}" style="display:inline-block;padding:13px 20px;background:#0284c7;color:#ffffff;text-decoration:none;border-radius:10px;font-weight:bold">Bekijk en betaal factuur</a>
<p style="margin:28px 0 0;font-size:13px;line-height:1.6;color:#64748b">Heeft u al betaald? Dan kunt u deze e-mail als niet verzonden beschouwen. De betaalstatus wordt automatisch verwerkt.</p>
</td></tr></table>
</td></tr></table>
</body>
</html>
