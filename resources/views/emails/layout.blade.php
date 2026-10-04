<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('subject', config('site.name', config('app.name')))</title>
    <style>
        body { margin: 0; padding: 0; background-color: #eef2f7; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #334155; }
        .preheader { display: none; max-height: 0; overflow: hidden; mso-hide: all; }
        .wrapper { max-width: 600px; margin: 0 auto; padding: 32px 16px; }
        .card { background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 12px 32px rgba(15, 23, 42, 0.08); }
        .header { background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 55%, #38bdf8 130%); color: #ffffff; padding: 36px 40px; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 800; letter-spacing: 0.2px; }
        .header p { margin: 8px 0 0; font-size: 13px; color: #dbeafe; }
        .content { padding: 36px 40px 28px; line-height: 1.7; font-size: 15px; color: #334155; }
        .content p { margin: 0 0 16px; }
        .content a { color: #2563eb; }
        .button { display: inline-block; margin: 8px 0 22px; padding: 14px 30px; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #ffffff !important; text-decoration: none; border-radius: 9999px; font-weight: 700; font-size: 15px; }
        .box { background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px 20px; margin: 20px 0; line-height: 1.7; }
        .small { font-size: 13px; color: #64748b; }
        .footer { padding: 24px 40px 8px; text-align: center; font-size: 12px; color: #94a3b8; line-height: 1.8; }
        .footer a { color: #2563eb; text-decoration: none; }
    </style>
</head>
<body>
    <span class="preheader">@yield('preheader')</span>
    <div class="wrapper">
        <div class="card">
            <div class="header">
                <h1>{{ config('site.name', config('app.name')) }}</h1>
                <p>Hosting, domeinen en websites onder één dak</p>
            </div>
            <div class="content">
                @yield('content')
            </div>
        </div>
        <div class="footer">
            {{ config('company.legal_name', config('site.name', config('app.name'))) }}
            @if(config('company.address'))
                · {{ config('company.address') }}@if(config('company.postal_code')), {{ config('company.postal_code') }}@endif @if(config('company.city')){{ config('company.city') }}@endif
            @endif
            @if(config('company.kvk_number')) · KvK {{ config('company.kvk_number') }}@endif
            <br>
            @if(config('company.email'))<a href="mailto:{{ config('company.email') }}">{{ config('company.email') }}</a>@endif
            @if(config('company.website')) · <a href="{{ config('company.website') }}">{{ config('company.website') }}</a>@endif
            <br>Dit is een automatisch bericht, reageren op deze e-mail is niet mogelijk.
        </div>
    </div>
</body>
</html>
