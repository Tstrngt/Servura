<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('subject', config('site.name', config('app.name')))</title>
    <style>
        body { margin: 0; padding: 0; background-color: #f4f6f8; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #334155; }
        .wrapper { max-width: 600px; margin: 24px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .header { background: #0f172a; color: #ffffff; padding: 28px 32px; text-align: center; }
        .header h1 { margin: 0; font-size: 22px; font-weight: 700; }
        .content { padding: 32px; line-height: 1.6; font-size: 15px; }
        .content p { margin: 0 0 14px; }
        .button { display: inline-block; margin: 8px 0 20px; padding: 12px 24px; background: #2563eb; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600; }
        .box { background: #f8fafc; border-left: 4px solid #2563eb; padding: 16px; margin: 16px 0; border-radius: 8px; }
        .footer { background: #f8fafc; padding: 20px 32px; text-align: center; font-size: 12px; color: #64748b; }
        .small { font-size: 13px; color: #64748b; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>{{ config('site.name', config('app.name')) }}</h1>
        </div>
        <div class="content">
            @yield('content')
        </div>
        <div class="footer">
            {{ config('site.name', config('app.name')) }} · {{ config('site.location', '') }}
            <br>Dit is een automatisch bericht, reageren op deze e-mail is niet mogelijk.
        </div>
    </div>
</body>
</html>
