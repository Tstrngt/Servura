@extends('emails.layout')

@section('subject', 'Uw hostingaccount is actief')

@section('content')
<p>Hallo {{ $user->name }},</p>
<p>Uw hostingpakket voor <strong>{{ $service->domain }}</strong> is actief. Hieronder vindt u de inloggegevens voor DirectAdmin.</p>

@php($server = $service->service?->serverConnection)
<div class="box">
    <strong>Inloggegevens DirectAdmin</strong><br>
    Gebruikersnaam: {{ $service->external_username }}<br>
    Wachtwoord: {{ $service->external_password }}<br>
    Domein: {{ $service->domain }}
    @if($server?->url)<br>DirectAdmin: <a href="{{ $server->url }}">{{ $server->url }}</a>@endif
</div>

@if($server)
<div class="box">
    <strong>Servergegevens</strong><br>
    FTP-host: {{ $server->ftp_host ?: 'ftp.'.$service->domain }}<br>
    Server-IP: {{ $server->shared_ip }}
    @if($server->nameserver_1)<br>Nameserver 1: {{ $server->nameserver_1 }}@endif
    @if($server->nameserver_2)<br>Nameserver 2: {{ $server->nameserver_2 }}@endif
    @if($server->nameserver_3)<br>Nameserver 3: {{ $server->nameserver_3 }}@endif
</div>
@endif

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
