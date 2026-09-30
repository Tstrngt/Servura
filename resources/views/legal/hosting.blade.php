@extends('legal.layout')

@section('title', 'Hostingvoorwaarden - '.config('company.trade_name', 'Servura'))
@section('meta-description', 'Hostingvoorwaarden van '.config('company.trade_name', 'Servura').'.')
@section('meta-keywords', 'hostingvoorwaarden, hosting, Servura')

@section('legal-title')
    Hostingvoorwaarden
@endsection

@section('legal-meta')
    Versie {{ config('legal.versions.hosting_terms.version', '1.0') }} – laatst gewijzigd op {{ config('legal.versions.hosting_terms.effective_date', '[datum nog in te vullen]') }}
@endsection

@section('legal-content')
    <p><em>Let op: deze Hostingvoorwaarden gelden aanvullend op de <a href="{{ route('legal.terms') }}">Algemene Voorwaarden</a> van Servura. Vul de specifieke waarden voor uw pakketten in voordat u deze pagina publiceert.</em></p>

    <h2>Artikel 1 — Hostingdienst</h2>
    <ul>
        <li>Servura stelt aan Klant servercapaciteit, opslag, dataverkeer en/of aanverwante hostingdiensten beschikbaar zoals omschreven in de overeenkomst.</li>
        <li>De exacte specificaties van het hostingpakket blijken uit de offerte, bestelbevestiging of het klantportaal.</li>
        <li>Hosting betreft een inspanningsverplichting, tenzij in een afzonderlijke SLA uitdrukkelijk concrete serviceniveaus zijn gegarandeerd.</li>
    </ul>

    <h2>Artikel 2 — Beschikbaarheid</h2>
    <ul>
        <li>Servura streeft naar een zo hoog mogelijke beschikbaarheid.</li>
        <li>Er geldt geen gegarandeerd uptimepercentage tenzij uitdrukkelijk een SLA van toepassing is verklaard.</li>
        <li>Indien een SLA geldt, wordt beschikbaarheid uitsluitend berekend volgens de daarin opgenomen meetmethode.</li>
        <li>Gepland onderhoud, overmacht en omstandigheden die volgens de SLA buiten de berekening vallen, worden niet als downtime meegerekend voor zover dit in de SLA is bepaald.</li>
    </ul>

    <h2>Artikel 3 — Onderhoud</h2>
    <ul>
        <li>Servura mag onderhoud uitvoeren wanneer dit noodzakelijk is voor beveiliging, stabiliteit, prestaties of technische ontwikkeling.</li>
        <li>Gepland onderhoud wordt waar redelijkerwijs mogelijk vooraf aangekondigd.</li>
        <li>Spoedeisend beveiligingsonderhoud kan zonder voorafgaande aankondiging plaatsvinden.</li>
        <li>Servura probeert verstoring voor Klant zoveel mogelijk te beperken.</li>
    </ul>

    <h2>Artikel 4 — Fair use en capaciteit</h2>
    <ul>
        <li>Klant gebruikt de hostingdienst overeenkomstig het overeengekomen pakket en de <a href="{{ route('legal.acceptable-use') }}">Acceptable Use Policy</a>.</li>
        <li>Gebruik dat structureel buitensporige belasting veroorzaakt en andere klanten of infrastructuur wezenlijk benadeelt, kan aanleiding zijn voor overleg over technische aanpassingen of een ander pakket.</li>
        <li>Servura zal, behoudens urgente beveiligings- of stabiliteitsproblemen, Klant eerst waarschuwen voordat structurele beperkingen worden toegepast.</li>
    </ul>

    <h2>Artikel 5 — Beveiliging</h2>
    <ul>
        <li>Servura treft passende technische en organisatorische maatregelen voor haar hostinginfrastructuur.</li>
        <li>Klant blijft verantwoordelijk voor de beveiliging van eigen applicaties, accounts en inhoud voor zover deze onder beheer van Klant vallen.</li>
        <li>Klant installeert geen bewust kwetsbare of niet-ondersteunde software wanneer dit de infrastructuur in gevaar kan brengen.</li>
        <li>Servura mag een systeem tijdelijk isoleren wanneer dit redelijkerwijs noodzakelijk is om een acute beveiligingsdreiging te beperken.</li>
    </ul>

    <h2>Artikel 6 — Back-ups</h2>
    <ul>
        <li>Back-ups worden uitgevoerd volgens het voor het betreffende pakket overeengekomen back-upbeleid.</li>
        <li>Voor het standaardpakket geldt, indien van toepassing:
            <ul>
                <li>back-upfrequentie: [invullen];</li>
                <li>bewaartermijn: [invullen];</li>
                <li>geografische opslaglocatie: [invullen].</li>
            </ul>
        </li>
        <li>Back-ups zijn bedoeld als noodvoorziening en vormen geen vervanging voor een eigen back-upstrategie van Klant.</li>
        <li>Klant wordt geadviseerd zelfstandig kopieën te bewaren van bedrijfskritische gegevens.</li>
        <li>Servura garandeert niet dat iedere individuele back-up onder alle omstandigheden volledig of herstelbaar is.</li>
        <li>Indien een gegarandeerde RPO of RTO geldt, moet deze uitdrukkelijk in een SLA worden opgenomen.</li>
    </ul>

    <h2>Artikel 7 — Herstel</h2>
    <ul>
        <li>Herstel uit een beschikbare back-up wordt uitgevoerd binnen een redelijke termijn.</li>
        <li>Een specifieke hersteltijd geldt uitsluitend indien deze in een SLA is gegarandeerd.</li>
        <li>Herstel op verzoek van Klant kan aanvullend in rekening worden gebracht indien de oorzaak niet aan Servura kan worden toegerekend.</li>
    </ul>

    <h2>Artikel 8 — Dataverkeer en opslag</h2>
    <ul>
        <li>Limieten voor opslag, dataverkeer, CPU, geheugen, processen of overige resources worden in het hostingpakket vermeld.</li>
        <li>Indien Klant structureel boven de overeengekomen capaciteit uitkomt, neemt Servura contact op over uitbreiding of aanpassing.</li>
        <li>Bij een acute bedreiging voor de stabiliteit van de infrastructuur mag Servura tijdelijk technische maatregelen nemen.</li>
    </ul>

    <h2>Artikel 9 — Misbruik</h2>
    <ul>
        <li>Gebruik van hostingdiensten is onderworpen aan de <a href="{{ route('legal.acceptable-use') }}">Acceptable Use Policy</a>.</li>
        <li>Bij vermoedelijk misbruik kan Servura onderzoek doen voor zover dit noodzakelijk en wettelijk toegestaan is.</li>
        <li>Maatregelen worden zoveel mogelijk beperkt tot hetgeen noodzakelijk is om het risico of de overtreding te beëindigen.</li>
    </ul>

    <h2>Artikel 10 — Datalocatie</h2>
    <ul>
        <li>Hostinggegevens worden primair verwerkt in [Nederland/EER/land invullen].</li>
        <li>Indien gegevens buiten de Europese Economische Ruimte worden verwerkt, zorgt Servura voor zover vereist voor een geldige doorgiftegrondslag onder de AVG.</li>
        <li>Nadere informatie over subverwerkers wordt verstrekt in de <a href="{{ route('legal.privacy') }}">privacyverklaring</a> of <a href="{{ route('legal.dpa') }}">verwerkersovereenkomst</a>.</li>
    </ul>

    <h2>Artikel 11 — Migratie en beëindiging</h2>
    <ul>
        <li>Na beëindiging krijgt Klant gedurende [bijvoorbeeld 14 of 30] dagen gelegenheid om beschikbare klantgegevens te exporteren, tenzij onmiddellijk verwijderen wettelijk noodzakelijk is.</li>
        <li>Na afloop van deze termijn mag Servura actieve productiegegevens verwijderen.</li>
        <li>Gegevens kunnen daarna nog tijdelijk aanwezig zijn in back-ups totdat de toepasselijke back-upretentie afloopt.</li>
        <li>Back-ups worden niet opnieuw in productie gebracht uitsluitend om verwijderde gegevens beschikbaar te stellen, tenzij dit technisch mogelijk is en afzonderlijk wordt overeengekomen.</li>
        <li>Servura zal waar redelijkerwijs mogelijk medewerking verlenen aan migratie naar een andere aanbieder.</li>
        <li>Voor migratiewerkzaamheden kan Servura haar gebruikelijke tarief rekenen.</li>
    </ul>

    <h2>Artikel 12 — Service Level Agreement</h2>
    <ul>
        <li>Een SLA geldt alleen wanneer deze uitdrukkelijk schriftelijk onderdeel is gemaakt van de overeenkomst.</li>
        <li>De SLA beschrijft ten minste:
            <ul>
                <li>het beschikbaarheidspercentage;</li>
                <li>de meetmethode;</li>
                <li>onderhoudsvensters;</li>
                <li>responstijden;</li>
                <li>prioriteitscategorieën;</li>
                <li>eventuele hersteltijden;</li>
                <li>uitzonderingen;</li>
                <li>eventuele service credits.</li>
            </ul>
        </li>
        <li>Marketinguitingen of algemene streefpercentages vormen geen SLA tenzij zij uitdrukkelijk als garantie zijn overeengekomen.</li>
    </ul>
@endsection
