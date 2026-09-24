@extends('emails.layout')

@section('subject', 'Uw hostingaccount is actief')

@section('content')
<p>Hallo {{ $user->name }},</p>
<p>Uw hostingpakket voor <strong>{{ $service->domain }}</strong> is actief. Hieronder vindt u de inloggegevens voor DirectAdmin.</p>

<div class="box">
    <strong>Inloggegevens DirectAdmin</strong><br>
    Gebruikersnaam: {{ $service->external_username }}<br>
    Wachtwoord: {{ $service->external_password }}<br>
    Domein: {{ $service->domain }}
</div>

<p>U kunt inloggen via DirectAdmin. Vanuit uw klantportaal kunt u ook met één klik inloggen:</p>
<a href="{{ $loginUrl }}" class="button">Inloggen op DirectAdmin</a>

<h3 style="font-size:16px;margin-top:24px;">Eerste stappen met DirectAdmin</h3>
<ul>
    <li>Log in om uw bestanden, databases en e-mailaccounts te beheren.</li>
    <li>Via "File Manager" upload u eenvoudig uw website.</li>
    <li>Onder "Email Accounts" maakt u professionele mailboxen aan.</li>
    <li>DNS en subdomeinen beheert u onder "DNS Management".</li>
</ul>

<p>Heeft u hulp nodig? Open een ticket via uw klantportaal, dan helpen we u graag.</p>
@endsection
