@extends('legal.layout')

@section('title', 'Acceptable Use Policy - '.config('company.trade_name', 'Servura'))
@section('meta-description', 'Acceptable Use Policy van '.config('company.trade_name', 'Servura').'.')
@section('meta-keywords', 'acceptable use policy, misbruik, hosting, Servura')

@section('legal-title')
    Acceptable Use Policy
@endsection

@section('legal-meta')
    Versie {{ config('legal.versions.acceptable_use.version', '1.0') }} – laatst gewijzigd op {{ config('legal.versions.acceptable_use.effective_date', '[datum nog in te vullen]') }}
@endsection

@section('legal-content')
    <p><em>Let op: dit is een concepttekst en nog niet juridisch gecontroleerd.</em></p>

    <h2>1. Doel</h2>
    <p>Deze Acceptable Use Policy beschrijft welk gebruik van de diensten van {{ config('company.trade_name', 'Servura') }} is toegestaan. Het doel is een veilige en stabiele omgeving voor alle klanten te waarborgen.</p>

    <h2>2. Verboden activiteiten</h2>
    <p>Het is verboden om de diensten van {{ config('company.trade_name', 'Servura') }} te gebruiken voor:</p>
    <ul>
        <li>spam, phishing of andere misleidende communicatie;</li>
        <li>malware, ransomware, virussen of andere schadelijke software;</li>
        <li>botnets of deelname hieraan;</li>
        <li>DDoS-aanvallen of deelname aan DDoS-aanvallen;</li>
        <li>ongeautoriseerde toegang tot systemen, hacking of brute-force-aanvallen;</li>
        <li>kwetsbaarheidsscans zonder schriftelijke toestemming van Servura;</li>
        <li>hosting van evident illegale content;</li>
        <li>auteursrechtinbreuk, waaronder ongeoorloofd delen van beschermd materiaal;</li>
        <li>fraude, identiteitsfraude of financiële misleiding;</li>
        <li>misbruik van accounts, credentials of toegangsrechten van derden;</li>
        <li>verspreiding van schadelijke of illegale software;</li>
        <li>misbruik van e-maildiensten, zoals open relays of ongeoorloofd verzenden van bulkmail;</li>
        <li>activiteiten die excessieve serverbelasting veroorzaken of andere klanten of infrastructuur verstoren.</li>
    </ul>

    <h2>3. Fair use</h2>
    <p>Gebruik van serverresources moet redelijk zijn ten opzichte van het gekozen pakket. Bij structureel excessief gebruik nemen wij contact op.</p>

    <h2>4. Veiligheid</h2>
    <p>Klanten zijn verantwoordelijk voor het veilig houden van hun toegangsgegevens, applicaties en content. Het is niet toegestaan om beveiligingsmaatregelen te omzeilen.</p>

    <h2>5. Melden van misbruik</h2>
    <p>Verdachte of schadelijke activiteit kunt u melden via <a href="{{ route('legal.abuse') }}">Misbruik melden</a>.</p>

    <h2>6. Gevolgen</h2>
    <p>Bij ernstig of herhaald misbruik is {{ config('company.trade_name', 'Servura') }} gerechtigd maatregelen te treffen, waaronder waarschuwingen, beperkingen, opschorting of beëindiging van de diensten, conform de overeenkomst en voorwaarden.</p>

    <h2>7. Wijzigingen</h2>
    <p>Deze policy kan worden gewijzigd. Wijzigingen worden met minimaal 30 dagen van tevoren aangekondigd.</p>
@endsection
