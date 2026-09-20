<!DOCTYPE html>
<html lang="nl">
<body style="margin:0;background:#f1f5f9;font-family:Arial,sans-serif;color:#0f172a">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="padding:32px 16px"><tr><td align="center">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:620px;background:#ffffff;border-radius:16px;overflow:hidden">
<tr><td style="padding:28px 32px;background:#0f172a;color:#ffffff"><strong style="font-size:24px">Servura</strong></td></tr>
<tr><td style="padding:32px">
<h1 style="margin:0 0 16px;font-size:24px">Uw verlengingsfactuur staat klaar</h1>
<p style="margin:0 0 16px;line-height:1.6;color:#475569">Beste {{ $invoice->user->name }},</p>
<p style="margin:0 0 20px;line-height:1.6;color:#475569">Factuur <strong>{{ $invoice->invoice_number }}</strong> voor de volgende periode van uw dienst is aangemaakt.</p>
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:24px;background:#f8fafc;border-radius:12px"><tr><td style="padding:18px"><span style="display:block;color:#64748b;font-size:13px">Totaal</span><strong style="display:block;margin-top:4px;font-size:24px">€ {{ number_format($invoice->total, 2, ',', '.') }}</strong><span style="display:block;margin-top:8px;color:#64748b;font-size:13px">Betaal uiterlijk {{ $invoice->due_date->format('d-m-Y') }}</span></td></tr></table>
@if($invoice->payment_url)
<a href="{{ $invoice->payment_url }}" style="display:inline-block;padding:13px 20px;background:#0284c7;color:#ffffff;text-decoration:none;border-radius:10px;font-weight:bold">Factuur betalen</a>
@else
<p style="margin:0;padding:13px 16px;background:#ecfdf5;border-radius:10px;color:#065f46">De automatische incasso is gestart. U hoeft niets te doen.</p>
@endif
<p style="margin:28px 0 0;font-size:13px;line-height:1.6;color:#64748b">U kunt de factuur en betaalstatus ook bekijken in uw Servura-klantportaal.</p>
</td></tr>
</table>
</td></tr></table>
</body>
</html>
