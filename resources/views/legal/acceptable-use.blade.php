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
    <p><em>Let op: deze policy beschrijft verantwoord en rechtmatig gebruik van de infrastructuur en diensten van {{ config('company.trade_name', 'Servura') }}. Vul de contactgegevens voor abuse-meldingen en security in voordat u deze pagina publiceert.</em></p>

    <h2>Artikel 1 — Doel</h2>
    <ul>
        <li>Deze Acceptable Use Policy bevat regels voor verantwoord en rechtmatig gebruik van de infrastructuur en diensten van {{ config('company.trade_name', 'Servura') }}.</li>
    </ul>

    <h2>Artikel 2 — Algemene verplichting</h2>
    <ul>
        <li>Klant mag de diensten niet gebruiken op een wijze die:
            <ol>
                <li>in strijd is met toepasselijke wet- of regelgeving;</li>
                <li>rechten van derden schendt;</li>
                <li>de veiligheid of integriteit van systemen aantast;</li>
                <li>andere klanten of gebruikers onevenredig hindert; of</li>
                <li>Servura blootstelt aan een concreet en ernstig juridisch of beveiligingsrisico.</li>
            </ol>
        </li>
    </ul>

    <h2>Artikel 3 — Verboden gebruik</h2>
    <ul>
        <li>Het is onder meer verboden de diensten te gebruiken voor:
            <ul>
                <li>verspreiding van malware, ransomware, virussen of andere schadelijke code;</li>
                <li>phishing of identiteitsfraude;</li>
                <li>onbevoegde toegang tot systemen of accounts;</li>
                <li>uitvoeren of faciliteren van DDoS-aanvallen;</li>
                <li>exploitatie van botnets;</li>
                <li>brute-force-aanvallen of ongeautoriseerde scans;</li>
                <li>verspreiding van onrechtmatige spam;</li>
                <li>hosting of verspreiding van materiaal dat evident in strijd is met toepasselijk recht;</li>
                <li>kennelijke inbreuk op intellectuele eigendomsrechten;</li>
                <li>misleiding of fraude;</li>
                <li>omzeiling van beveiligingsmaatregelen;</li>
                <li>gebruik dat de technische infrastructuur ernstig verstoort.</li>
            </ul>
        </li>
    </ul>

    <h2>Artikel 4 — Kwetsbaarheden</h2>
    <ul>
        <li>Klant mag beveiligingsonderzoek op systemen van Servura uitsluitend uitvoeren na voorafgaande schriftelijke toestemming.</li>
        <li>Een ontdekt beveiligingsprobleem moet zo spoedig mogelijk vertrouwelijk worden gemeld via [security e-mailadres].</li>
        <li>Kwetsbaarheden mogen niet worden misbruikt of openbaar gemaakt voordat Servura een redelijke gelegenheid heeft gehad het probleem te onderzoeken en te verhelpen.</li>
    </ul>

    <h2>Artikel 5 — Meldingen over illegale inhoud of misbruik</h2>
    <ul>
        <li>Meldingen kunnen worden ingediend via [abuse e-mailadres / meldformulier].</li>
        <li>Een melding bevat bij voorkeur:
            <ol>
                <li>een duidelijke uitleg van de vermeende overtreding;</li>
                <li>de exacte URL, het domein of andere locatie van de betreffende informatie;</li>
                <li>relevante onderbouwing;</li>
                <li>contactgegevens van de melder;</li>
                <li>een verklaring dat de melding naar beste weten juist en volledig is.</li>
            </ol>
        </li>
        <li>Servura beoordeelt meldingen zorgvuldig, objectief en naar verhouding van de omstandigheden.</li>
        <li>Servura is niet verplicht complexe civielrechtelijke geschillen tussen derden zelfstandig definitief te beslechten.</li>
    </ul>

    <h2>Artikel 6 — Maatregelen</h2>
    <ul>
        <li>Afhankelijk van aard, ernst en urgentie kan Servura:
            <ol>
                <li>aanvullende informatie opvragen;</li>
                <li>Klant waarschuwen;</li>
                <li>verwijdering of aanpassing verlangen;</li>
                <li>specifieke content tijdelijk ontoegankelijk maken;</li>
                <li>technische beperkingen toepassen;</li>
                <li>een dienst tijdelijk opschorten;</li>
                <li>de overeenkomst beëindigen indien daarvoor voldoende juridische of contractuele grond bestaat;</li>
                <li>voldoen aan een geldig bevel van een bevoegde autoriteit.</li>
            </ol>
        </li>
    </ul>

    <h2>Artikel 7 — Spoedeisende situaties</h2>
    <ul>
        <li>Servura mag zonder voorafgaande waarschuwing ingrijpen wanneer dit redelijkerwijs noodzakelijk is vanwege:
            <ol>
                <li>een acute beveiligingsdreiging;</li>
                <li>actieve verspreiding van malware;</li>
                <li>een lopende DDoS-aanval;</li>
                <li>een ernstig risico voor andere systemen of klanten;</li>
                <li>een rechtsgeldig bevel van een bevoegde autoriteit; of</li>
                <li>andere omstandigheden waarin uitstel naar redelijke verwachting ernstige schade veroorzaakt.</li>
            </ol>
        </li>
    </ul>

    <h2>Artikel 8 — Motivering</h2>
    <ul>
        <li>Wanneer Servura gehoste informatie verwijdert of de toegang daartoe beperkt, verstrekt Servura voor zover de toepasselijke wetgeving dit vereist een duidelijke motivering aan de betrokken klant, behoudens wettelijke uitzonderingen.</li>
    </ul>

    <h2>Artikel 9 — Betwisting</h2>
    <ul>
        <li>Klant kan een maatregel gemotiveerd betwisten via [e-mailadres].</li>
        <li>Servura beoordeelt de beschikbare informatie opnieuw.</li>
        <li>Indien blijkt dat een maatregel niet langer gerechtvaardigd is, wordt deze waar redelijkerwijs mogelijk opgeheven.</li>
    </ul>

    <h2>Artikel 10 — Kosten en schade</h2>
    <ul>
        <li>Kosten die Servura redelijkerwijs moet maken als rechtstreeks gevolg van aantoonbaar misbruik door Klant kunnen aan Klant worden doorberekend voor zover dit wettelijk en contractueel is toegestaan.</li>
    </ul>
@endsection
