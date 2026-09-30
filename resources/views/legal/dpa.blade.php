@extends('legal.layout')

@section('title', 'Verwerkersovereenkomst - '.config('company.trade_name', 'Servura'))
@section('meta-description', 'Verwerkersovereenkomst (DPA) van '.config('company.trade_name', 'Servura').'.')
@section('meta-keywords', 'verwerkersovereenkomst, DPA, Servura')

@section('legal-title')
    Verwerkersovereenkomst
@endsection

@section('legal-meta')
    Versie {{ config('legal.versions.dpa.version', '1.0') }} – laatst gewijzigd op {{ config('legal.versions.dpa.effective_date', '[datum nog in te vullen]') }}
@endsection

@section('legal-content')
    <p><em>Let op: deze Verwerkersovereenkomst maakt onderdeel uit van iedere overeenkomst waarbij Servura in opdracht van Klant persoonsgegevens verwerkt en daarbij als verwerker in de zin van de AVG optreedt. Vul de bedrijfsgegevens aan voordat u deze pagina publiceert.</em></p>

    <h2>Artikel 1 — Rollen</h2>
    <ul>
        <li>Klant is verwerkingsverantwoordelijke voor de persoonsgegevens die Klant via de diensten van Servura laat verwerken, tenzij uit de feitelijke omstandigheden anders voortvloeit.</li>
        <li>Servura verwerkt deze persoonsgegevens uitsluitend als verwerker en volgens gedocumenteerde instructies van Klant, behoudens wettelijke verplichtingen.</li>
        <li>Klant is verantwoordelijk voor de rechtmatigheid van de verwerking, de doeleinden van de verwerking en de instructies aan Servura.</li>
    </ul>

    <h2>Artikel 2 — Onderwerp en duur</h2>
    <ul>
        <li>Servura verwerkt persoonsgegevens voor zover noodzakelijk voor het leveren van de overeengekomen hosting-, ontwikkel-, beheer- of ondersteuningsdiensten.</li>
        <li>De verwerking duurt zolang Servura de betreffende dienst levert en zolang gegevens daarna overeenkomstig de overeenkomst of wettelijke verplichtingen worden bewaard.</li>
    </ul>

    <h2>Artikel 3 — Aard en doel van de verwerking</h2>
    <ul>
        <li>De verwerking kan onder meer bestaan uit:
            <ul>
                <li>opslaan;</li>
                <li>hosten;</li>
                <li>beschikbaar stellen;</li>
                <li>raadplegen wanneer dit noodzakelijk is voor support;</li>
                <li>beveiligen;</li>
                <li>back-uppen;</li>
                <li>herstellen;</li>
                <li>migreren;</li>
                <li>verwijderen.</li>
            </ul>
        </li>
        <li>Servura gebruikt persoonsgegevens die zij namens Klant verwerkt niet voor eigen zelfstandige commerciële doeleinden.</li>
    </ul>

    <h2>Artikel 4 — Categorieën persoonsgegevens</h2>
    <ul>
        <li>Afhankelijk van de dienst kunnen onder meer worden verwerkt:
            <ul>
                <li>naam- en contactgegevens;</li>
                <li>accountgegevens;</li>
                <li>IP-adressen;</li>
                <li>klantgegevens van Klant;</li>
                <li>inhoud van formulieren;</li>
                <li>transactie- of bestelgegevens;</li>
                <li>technische logs;</li>
                <li>gegevens die gebruikers van Klant zelf in systemen invoeren.</li>
            </ul>
        </li>
        <li>Klant informeert Servura vooraf wanneer structureel bijzondere categorieën persoonsgegevens of andere gegevens met een bijzonder hoog risico worden verwerkt.</li>
    </ul>

    <h2>Artikel 5 — Categorieën betrokkenen</h2>
    <ul>
        <li>Afhankelijk van de dienstverlening kunnen persoonsgegevens betrekking hebben op:
            <ul>
                <li>medewerkers van Klant;</li>
                <li>klanten van Klant;</li>
                <li>websitebezoekers;</li>
                <li>leveranciers;</li>
                <li>contactpersonen;</li>
                <li>gebruikers van websites of applicaties.</li>
            </ul>
        </li>
    </ul>

    <h2>Artikel 6 — Instructies</h2>
    <ul>
        <li>Servura verwerkt persoonsgegevens uitsluitend op basis van gedocumenteerde instructies van Klant.</li>
        <li>De overeenkomst, deze Verwerkersovereenkomst en schriftelijke aanvullende instructies gelden als gedocumenteerde instructies.</li>
        <li>Indien Servura redelijkerwijs van mening is dat een instructie strijdig is met de AVG of andere toepasselijke privacywetgeving, informeert Servura Klant daarover.</li>
        <li>Aanvullende instructies die buiten de overeengekomen dienstverlening vallen, kunnen als meerwerk worden berekend.</li>
    </ul>

    <h2>Artikel 7 — Geheimhouding</h2>
    <ul>
        <li>Personen die onder verantwoordelijkheid van Servura toegang hebben tot persoonsgegevens zijn gehouden aan passende vertrouwelijkheid.</li>
        <li>Toegang wordt beperkt tot personen voor wie toegang noodzakelijk is voor de uitvoering van hun werkzaamheden.</li>
    </ul>

    <h2>Artikel 8 — Beveiliging</h2>
    <ul>
        <li>Servura treft passende technische en organisatorische maatregelen zoals bedoeld in artikel 32 AVG.</li>
        <li>Daarbij wordt rekening gehouden met:
            <ul>
                <li>de stand van de techniek;</li>
                <li>uitvoeringskosten;</li>
                <li>aard, omvang, context en doeleinden van de verwerking;</li>
                <li>risico's voor betrokkenen.</li>
            </ul>
        </li>
        <li>Maatregelen kunnen onder meer bestaan uit:
            <ul>
                <li>toegangsbeveiliging;</li>
                <li>sterke authenticatie waar passend;</li>
                <li>logging;</li>
                <li>netwerkbeveiliging;</li>
                <li>patchmanagement;</li>
                <li>back-ups;</li>
                <li>autorisatiebeheer;</li>
                <li>beveiligde communicatie;</li>
                <li>procedures voor incidentbeheer.</li>
            </ul>
        </li>
        <li>Op verzoek verstrekt Servura redelijke informatie over relevante beveiligingsmaatregelen, voor zover openbaarmaking daarvan de beveiliging niet ondermijnt.</li>
    </ul>

    <h2>Artikel 9 — Datalekken</h2>
    <ul>
        <li>Servura informeert Klant zonder onredelijke vertraging nadat Servura kennis heeft genomen van een inbreuk in verband met persoonsgegevens die onder deze Verwerkersovereenkomst valt.</li>
        <li>Voor zover informatie beschikbaar is, verstrekt Servura aan Klant:
            <ul>
                <li>de aard van het incident;</li>
                <li>betrokken categorieën gegevens;</li>
                <li>vermoedelijke gevolgen;</li>
                <li>reeds genomen of voorgestelde maatregelen.</li>
            </ul>
        </li>
        <li>Indien niet alle informatie direct beschikbaar is, mag deze gefaseerd worden verstrekt.</li>
        <li>Klant blijft verantwoordelijk voor de beoordeling of een melding aan de Autoriteit Persoonsgegevens en/of betrokkenen noodzakelijk is, tenzij uit de wet anders voortvloeit.</li>
        <li>Servura verleent redelijke bijstand bij deze beoordeling.</li>
    </ul>

    <h2>Artikel 10 — Subverwerkers</h2>
    <ul>
        <li>Klant verleent Servura algemene toestemming om subverwerkers in te schakelen die noodzakelijk zijn voor de dienstverlening.</li>
        <li>Servura houdt een actuele lijst bij van relevante subverwerkers.</li>
        <li>Servura legt aan subverwerkers passende gegevensbeschermingsverplichtingen op.</li>
        <li>Servura blijft tegenover Klant verantwoordelijk voor de nakoming van de verplichtingen die de AVG aan Servura als verwerker oplegt voor zover deze verwerking door een subverwerker wordt uitgevoerd.</li>
        <li>Servura informeert Klant over voorgenomen materiële wijzigingen in subverwerkers voor zover de AVG dit vereist.</li>
        <li>Klant kan gemotiveerd bezwaar maken wanneer een nieuwe subverwerker aantoonbaar een wezenlijk privacy- of beveiligingsrisico veroorzaakt. Partijen overleggen dan over een redelijke oplossing.</li>
    </ul>

    <h2>Artikel 11 — Internationale doorgifte</h2>
    <ul>
        <li>Servura laat persoonsgegevens niet buiten de EER verwerken zonder te zorgen voor een rechtsgeldige grondslag voor doorgifte wanneer de AVG dit vereist.</li>
        <li>Indien toepasselijk kan Servura gebruikmaken van een adequaatheidsbesluit, standaardcontractbepalingen of andere rechtsgeldige waarborgen.</li>
    </ul>

    <h2>Artikel 12 — Rechten van betrokkenen</h2>
    <ul>
        <li>Indien Servura een verzoek ontvangt van een betrokkene dat betrekking heeft op persoonsgegevens waarvoor Klant verwerkingsverantwoordelijke is, stuurt Servura dit verzoek waar passend door naar Klant.</li>
        <li>Servura verleent redelijke technische en organisatorische bijstand zodat Klant aan geldige verzoeken van betrokkenen kan voldoen.</li>
        <li>Voor omvangrijke of uitzonderlijke werkzaamheden die buiten de normale dienstverlening vallen, kunnen redelijke kosten in rekening worden gebracht.</li>
    </ul>

    <h2>Artikel 13 — DPIA en toezichthouder</h2>
    <ul>
        <li>Servura verleent, rekening houdend met de aard van de verwerking en beschikbare informatie, redelijke bijstand bij:
            <ul>
                <li>gegevensbeschermingseffectbeoordelingen;</li>
                <li>voorafgaande raadpleging van een toezichthouder;</li>
                <li>beantwoording van redelijke vragen van een bevoegde toezichthouder.</li>
            </ul>
        </li>
    </ul>

    <h2>Artikel 14 — Audits</h2>
    <ul>
        <li>Servura verstrekt op redelijk verzoek informatie die noodzakelijk is om naleving van artikel 28 AVG aan te tonen.</li>
        <li>Klant mag een audit laten uitvoeren indien dit redelijkerwijs noodzakelijk is.</li>
        <li>Een audit wordt:
            <ul>
                <li>vooraf aangekondigd;</li>
                <li>tijdens normale kantooruren uitgevoerd;</li>
                <li>zodanig ingericht dat bedrijfsvoering, beveiliging en vertrouwelijkheid van andere klanten niet onnodig worden verstoord.</li>
            </ul>
        </li>
        <li>Servura mag redelijke kosten van een audit doorberekenen wanneer de audit omvangrijker is dan redelijkerwijs noodzakelijk, tenzij uit de audit een wezenlijke tekortkoming van Servura blijkt.</li>
        <li>Waar mogelijk worden bestaande onafhankelijke certificaten, auditrapporten of andere assurance-informatie gebruikt om onnodige audits te voorkomen.</li>
    </ul>

    <h2>Artikel 15 — Teruggave en verwijdering</h2>
    <ul>
        <li>Na beëindiging van de dienstverlening zal Servura, naar keuze van Klant en voor zover technisch redelijkerwijs mogelijk, persoonsgegevens retourneren of verwijderen.</li>
        <li>Actieve gegevens worden verwijderd overeenkomstig de in de <a href="{{ route('legal.hosting') }}">Hostingvoorwaarden</a> genoemde termijn.</li>
        <li>Kopieën in reguliere back-ups worden verwijderd wanneer de toepasselijke back-upretentie verloopt.</li>
        <li>Zolang gegevens uitsluitend nog in een beveiligde back-up aanwezig zijn, worden zij niet voor andere doeleinden verwerkt, behalve indien herstel noodzakelijk is.</li>
        <li>Een wettelijke bewaarplicht gaat voor op een instructie tot verwijdering.</li>
    </ul>

    <h2>Artikel 16 — Aansprakelijkheid</h2>
    <ul>
        <li>Voor aansprakelijkheid tussen Servura en Klant gelden de aansprakelijkheidsbepalingen uit de hoofdovereenkomst en <a href="{{ route('legal.terms') }}">Algemene Voorwaarden</a>, voor zover dit verenigbaar is met de AVG en ander dwingend recht.</li>
    </ul>

    <h2>Artikel 17 — Einde</h2>
    <ul>
        <li>Deze Verwerkersovereenkomst eindigt wanneer Servura geen persoonsgegevens meer namens Klant verwerkt, met dien verstande dat verplichtingen die naar hun aard moeten voortduren van kracht blijven.</li>
    </ul>

    <h2>Artikel 18 — Toepasselijk recht</h2>
    <ul>
        <li>Op deze Verwerkersovereenkomst is Nederlands recht van toepassing.</li>
        <li>Geschillen worden behandeld overeenkomstig de geschillenregeling in de <a href="{{ route('legal.terms') }}">Algemene Voorwaarden</a>.</li>
    </ul>
@endsection
