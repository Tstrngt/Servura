<!DOCTYPE html>
<html lang="nl">
<body style="margin:0;background:#f1f5f9;font-family:Arial,sans-serif;color:#0f172a">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="padding:32px 16px"><tr><td align="center">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:620px;background:#ffffff;border-radius:16px;overflow:hidden">
<tr><td style="padding:28px 32px;background:#0f172a;color:#ffffff"><strong style="font-size:24px">Servura</strong></td></tr>
<tr><td style="padding:32px">
<h1 style="margin:0 0 16px;font-size:24px">Uw hostingaccount is gereed</h1>
<p style="margin:0 0 20px;line-height:1.6;color:#475569">Beste {{ $customerService->user->name }}, het hostingaccount voor <strong>{{ $customerService->domain }}</strong> is automatisch aangemaakt.</p>
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:24px;background:#f8fafc;border-radius:12px"><tr><td style="padding:18px;line-height:1.9"><strong>Domein:</strong> {{ $customerService->domain }}<br><strong>Gebruikersnaam:</strong> {{ $customerService->external_username }}<br><strong>Tijdelijk wachtwoord:</strong> {{ $customerService->external_password }}<br><strong>DirectAdmin:</strong> {{ rtrim(config('directadmin.url'), '/') }}</td></tr></table>
<p style="margin:0;line-height:1.6;color:#475569">Log in en wijzig het tijdelijke wachtwoord zo snel mogelijk. Bewaar deze gegevens op een veilige plek.</p>
</td></tr></table>
</td></tr></table>
</body>
</html>
