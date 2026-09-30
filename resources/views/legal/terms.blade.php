@extends('legal.layout')

@section('title', 'Algemene Voorwaarden - '.config('company.trade_name', 'Servura'))
@section('meta-description', 'Algemene voorwaarden van '.config('company.trade_name', 'Servura').'.')
@section('meta-keywords', 'algemene voorwaarden, voorwaarden, Servura')

@section('legal-title')
    Algemene Voorwaarden
@endsection

@section('legal-meta')
    Versie {{ config('legal.versions.terms.version', '1.0') }} – laatst gewijzigd op {{ config('legal.versions.terms.effective_date', '[datum nog in te vullen]') }}
@endsection

@section('legal-content')
    <p><em>Let op: dit is een juridisch document. Zorg dat de tekst aansluit bij de werkelijke bedrijfsgegevens en dienstverlening van Servura.</em></p>

    <p>Deze juridische documentatie bestaat uit: Algemene Voorwaarden, Hostingvoorwaarden, Acceptable Use Policy, Privacyverklaring, Cookieverklaring en Verwerkersovereenkomst.</p>

    <h2>Artikel 1 — Definities</h2>
    <p>In deze algemene voorwaarden wordt verstaan onder:</p>
    <ul>
        <li><strong>Servura:</strong> de onderneming handelend onder de naam {{ config('company.trade_name', 'Servura') }}, gevestigd te {{ config('company.city', '[vestigingsplaats]') }}, ingeschreven bij de Kamer van Koophandel onder nummer {{ config('company.kvk_number', '[KvK-nummer]') }}.</li>
        <li><strong>Klant:</strong> iedere natuurlijke persoon handelend in de uitoefening van een beroep of bedrijf, of rechtspersoon, die met Servura een overeenkomst aangaat of daartoe een offerte aanvraagt.</li>
        <li><strong>Overeenkomst:</strong> iedere overeenkomst tussen Servura en Klant met betrekking tot door Servura te leveren producten of diensten.</li>
        <li><strong>Diensten:</strong> alle door Servura aangeboden of verrichte werkzaamheden, waaronder onder meer webdesign, webdevelopment, softwareontwikkeling, hosting, technisch beheer, onderhoud, consultancy en overige digitale diensten.</li>
        <li><strong>Schriftelijk:</strong> communicatie per brief of langs elektronische weg, waaronder e-mail, voor zover de identiteit van de afzender en de inhoud van de communicatie voldoende kunnen worden vastgesteld.</li>
        <li><strong>Website:</strong> iedere website, webapplicatie, applicatie of ander digitaal product dat Servura voor Klant ontwikkelt of beheert.</li>
    </ul>

    <h2>Artikel 2 — Toepasselijkheid</h2>
    <ul>
        <li>Deze algemene voorwaarden zijn van toepassing op alle offertes, aanbiedingen, overeenkomsten en diensten van Servura.</li>
        <li>Deze algemene voorwaarden zijn uitsluitend bedoeld voor zakelijke klanten.</li>
        <li>De toepasselijkheid van algemene voorwaarden van Klant wordt uitdrukkelijk uitgesloten, tenzij Servura deze schriftelijk heeft aanvaard.</li>
        <li>Afwijkingen van deze voorwaarden zijn alleen geldig wanneer deze schriftelijk tussen partijen zijn overeengekomen.</li>
        <li>Wanneer een bepaling uit de overeenkomst strijdig is met deze algemene voorwaarden, prevaleert de bepaling uit de individuele overeenkomst.</li>
        <li>Indien aanvullende voorwaarden van toepassing zijn, zoals Hostingvoorwaarden, een Service Level Agreement, Acceptable Use Policy of Verwerkersovereenkomst, maken deze deel uit van de overeenkomst.</li>
        <li>Bij onderlinge tegenstrijdigheid geldt, tenzij uitdrukkelijk anders overeengekomen, de volgende rangorde:
            <ol>
                <li>de individuele overeenkomst of offerte;</li>
                <li>een specifiek overeengekomen Service Level Agreement;</li>
                <li>de Verwerkersovereenkomst voor zover het verwerking van persoonsgegevens betreft;</li>
                <li>de Hostingvoorwaarden;</li>
                <li>de Acceptable Use Policy;</li>
                <li>deze Algemene Voorwaarden.</li>
            </ol>
        </li>
    </ul>

    <h2>Artikel 3 — Totstandkoming van de overeenkomst</h2>
    <ul>
        <li>Een aanbod of offerte van Servura is vrijblijvend, tenzij daarin uitdrukkelijk anders is vermeld.</li>
        <li>Een offerte is geldig gedurende de daarin genoemde termijn. Indien geen termijn is genoemd, is de offerte 30 dagen geldig.</li>
        <li>De overeenkomst komt tot stand zodra Klant de offerte of het aanbod schriftelijk aanvaardt, of zodra Servura met instemming van Klant met de uitvoering begint.</li>
        <li>Wijzigingen en aanvullende afspraken na totstandkoming van de overeenkomst kunnen gevolgen hebben voor prijs, planning en opleverdatum.</li>
        <li>Servura is niet gebonden aan kennelijke fouten, verschrijvingen of vergissingen in een offerte of communicatie.</li>
    </ul>

    <h2>Artikel 4 — Informatie en medewerking van Klant</h2>
    <ul>
        <li>Klant verstrekt tijdig alle informatie, materialen, toegangen, bestanden, teksten, afbeeldingen en andere gegevens die Servura redelijkerwijs nodig heeft voor de uitvoering van de overeenkomst.</li>
        <li>Klant staat ervoor in dat verstrekte informatie juist, volledig en rechtmatig is.</li>
        <li>Vertraging die ontstaat doordat Klant niet tijdig de benodigde informatie of medewerking verleent, komt voor rekening en risico van Klant.</li>
        <li>Servura mag in dat geval de planning en opleverdatum aanpassen.</li>
        <li>Eventuele extra werkzaamheden of kosten die hierdoor ontstaan, mogen aanvullend aan Klant worden gefactureerd.</li>
    </ul>

    <h2>Artikel 5 — Uitvoering van de diensten</h2>
    <ul>
        <li>Servura zal de overeenkomst naar beste inzicht, vermogen en volgens de eisen van goed vakmanschap uitvoeren.</li>
        <li>Tenzij uitdrukkelijk schriftelijk een concreet resultaat is gegarandeerd, rust op Servura een inspanningsverplichting.</li>
        <li>Servura mag voor de uitvoering van de overeenkomst derden inschakelen.</li>
        <li>Servura is bevoegd werkzaamheden in onderdelen of fasen uit te voeren.</li>
        <li>Klant kan zonder schriftelijke overeenkomst geen aanspraak maken op functionaliteiten, prestaties of eigenschappen die niet uitdrukkelijk onderdeel zijn van de overeengekomen scope.</li>
    </ul>

    <h2>Artikel 6 — Planning en termijnen</h2>
    <ul>
        <li>Opgegeven leverings-, ontwikkel- en oplevertermijnen zijn indicatief, tenzij uitdrukkelijk schriftelijk is overeengekomen dat sprake is van een fatale termijn.</li>
        <li>Servura informeert Klant wanneer redelijkerwijs voorzienbaar is dat een termijn substantieel wordt overschreden.</li>
        <li>Een vertraging geeft Klant niet automatisch recht op schadevergoeding of ontbinding.</li>
        <li>Indien een overeengekomen fatale termijn wordt overschreden, gelden de wettelijke regels voor tekortkoming en verzuim.</li>
    </ul>

    <h2>Artikel 7 — Wijzigingen en meerwerk</h2>
    <ul>
        <li>Werkzaamheden die buiten de oorspronkelijk overeengekomen scope vallen, gelden als meerwerk.</li>
        <li>Servura zal waar redelijkerwijs mogelijk vooraf aangeven welke gevolgen een wijziging heeft voor prijs en planning.</li>
        <li>Indien de noodzaak van meerwerk tijdens de uitvoering blijkt en onmiddellijke uitvoering redelijkerwijs noodzakelijk is om verdere werkzaamheden mogelijk te maken, mag Servura dit uitvoeren tegen het overeengekomen of gebruikelijke tarief.</li>
        <li>Wijzigingen die voortvloeien uit gewijzigde wensen van Klant, gewijzigde wet- of regelgeving, wijzigingen bij externe leveranciers of technische omstandigheden kunnen als meerwerk worden aangemerkt wanneer deze bij het sluiten van de overeenkomst niet redelijkerwijs waren voorzien.</li>
    </ul>

    <h2>Artikel 8 — Prijzen</h2>
    <ul>
        <li>Alle genoemde prijzen zijn exclusief btw en andere belastingen of heffingen, tenzij uitdrukkelijk anders vermeld.</li>
        <li>Kosten van externe leveranciers, licenties, domeinnamen, plug-ins, betaalproviders, software en andere diensten van derden zijn alleen inbegrepen wanneer dit uitdrukkelijk in de overeenkomst staat.</li>
        <li>Servura mag periodieke prijzen aanpassen.</li>
        <li>Bij lopende overeenkomsten kondigt Servura een prijswijziging ten minste 30 dagen vooraf schriftelijk aan.</li>
        <li>Een prijsverhoging wegens gewijzigde belastingen, overheidsheffingen of aantoonbare prijswijzigingen van specifiek voor Klant ingekochte diensten van derden kan direct worden doorberekend indien dit redelijk is en uit de overeenkomst voortvloeit.</li>
    </ul>

    <h2>Artikel 9 — Facturatie en betaling</h2>
    <ul>
        <li>Facturen moeten binnen 14 dagen na factuurdatum worden betaald, tenzij schriftelijk anders overeengekomen.</li>
        <li>Klant is verantwoordelijk voor het tijdig doorgeven van correcte factuurgegevens.</li>
        <li>Indien Klant niet tijdig betaalt, is Klant vanaf het intreden van verzuim de wettelijke handelsrente verschuldigd.</li>
        <li>Redelijke buitengerechtelijke incassokosten komen voor rekening van Klant voor zover deze wettelijk verschuldigd zijn.</li>
        <li>Servura mag werkzaamheden of diensten opschorten indien Klant, na een redelijke waarschuwing, opeisbare facturen niet betaalt.</li>
        <li>Opschorting laat de betalingsverplichtingen van Klant onverlet.</li>
        <li>Bezwaren tegen een factuur moeten zo spoedig mogelijk schriftelijk en gemotiveerd worden gemeld. Een bezwaar schort de betalingsverplichting voor het niet-betwiste gedeelte niet op.</li>
    </ul>

    <h2>Artikel 10 — Voorschotten en betaling in fasen</h2>
    <ul>
        <li>Servura mag vooraf een voorschot of betaling in termijnen verlangen.</li>
        <li>Servura is niet verplicht met werkzaamheden te beginnen voordat een overeengekomen voorschot is ontvangen.</li>
        <li>Bij projecten in fasen mag Servura verdere werkzaamheden opschorten totdat de betreffende termijn is betaald.</li>
    </ul>

    <h2>Artikel 11 — Oplevering van websites en software</h2>
    <ul>
        <li>Wanneer Servura een werk ter beoordeling aan Klant beschikbaar stelt, krijgt Klant een redelijke termijn om het werk te beoordelen.</li>
        <li>Tenzij anders overeengekomen bedraagt deze termijn vijf werkdagen.</li>
        <li>Klant meldt binnen deze termijn concrete en voldoende gespecificeerde gebreken die redelijkerwijs bij beoordeling konden worden vastgesteld.</li>
        <li>Indien Klant het werk daadwerkelijk in productie neemt of bedrijfsmatig gebruikt zonder gemotiveerd bezwaar te maken, geldt dit als aanwijzing dat het werk ten aanzien van zichtbare en redelijkerwijs kenbare gebreken is aanvaard.</li>
        <li>Aanvaarding laat aanspraken wegens verborgen gebreken of andere rechten die naar hun aard niet door acceptatie behoren te vervallen onverlet.</li>
        <li>Kleine gebreken die normaal gebruik niet wezenlijk belemmeren, vormen geen grond om oplevering als geheel te weigeren.</li>
        <li>Servura krijgt een redelijke mogelijkheid om gemelde gebreken te onderzoeken en, indien Servura daarvoor verantwoordelijk is, te herstellen.</li>
    </ul>

    <h2>Artikel 12 — Correctierondes</h2>
    <ul>
        <li>Alleen het aantal correctierondes dat in de offerte of overeenkomst is opgenomen, is inbegrepen.</li>
        <li>Een correctieronde omvat redelijke aanpassingen binnen de overeengekomen scope.</li>
        <li>Nieuwe functionaliteiten, substantiële ontwerpwijzigingen of wijzigingen van eerder goedgekeurde onderdelen kunnen als meerwerk worden aangemerkt.</li>
    </ul>

    <h2>Artikel 13 — Onderhoud en ondersteuning</h2>
    <ul>
        <li>Onderhoud en ondersteuning zijn uitsluitend inbegrepen wanneer dit schriftelijk is overeengekomen.</li>
        <li>Werkzaamheden aan software, systemen of websites van derden vallen alleen onder onderhoud indien dat uitdrukkelijk is overeengekomen.</li>
        <li>Servura kan niet garanderen dat software van derden voortdurend compatibel blijft met de door Klant gebruikte systemen.</li>
        <li>Wijzigingen van externe API's, software, plug-ins, frameworks, browsers of platformen kunnen aanvullend werk noodzakelijk maken.</li>
    </ul>

    <h2>Artikel 14 — Diensten van derden</h2>
    <ul>
        <li>Voor bepaalde diensten kan Servura gebruikmaken van diensten, software of infrastructuur van derden.</li>
        <li>Voor zover noodzakelijk kan Klant tevens gebonden zijn aan voorwaarden of licentievoorwaarden van die derden.</li>
        <li>Servura is niet verantwoordelijk voor wijzigingen, storingen of beëindiging van diensten van derden waarop Servura redelijkerwijs geen invloed heeft.</li>
        <li>Servura zal zich inspannen om negatieve gevolgen voor Klant waar redelijkerwijs mogelijk te beperken.</li>
    </ul>

    <h2>Artikel 15 — Domeinnamen</h2>
    <ul>
        <li>Registratie en beheer van domeinnamen zijn onderworpen aan de regels van de toepasselijke registry en registrar.</li>
        <li>Servura kan niet garanderen dat een gewenste domeinnaam beschikbaar blijft totdat de registratie daadwerkelijk is voltooid.</li>
        <li>Klant blijft verantwoordelijk voor de rechtmatigheid van de gekozen domeinnaam.</li>
        <li>Op verzoek van Klant zal Servura, voor zover technisch en contractueel mogelijk en na betaling van openstaande bedragen, medewerking verlenen aan verhuizing van een domeinnaam.</li>
    </ul>

    <h2>Artikel 16 — Hosting</h2>
    <ul>
        <li>Indien Servura hosting levert, zijn tevens de <a href="{{ route('legal.hosting') }}">Hostingvoorwaarden</a> en <a href="{{ route('legal.acceptable-use') }}">Acceptable Use Policy</a> van toepassing.</li>
        <li>Concrete beschikbaarheidspercentages, responstijden of hersteltijden gelden uitsluitend indien deze uitdrukkelijk in een overeenkomst of Service Level Agreement zijn opgenomen.</li>
    </ul>

    <h2>Artikel 17 — Intellectuele eigendomsrechten</h2>
    <ul>
        <li>Alle intellectuele eigendomsrechten op door Servura ontwikkelde of ter beschikking gestelde materialen blijven bij Servura of haar licentiegevers, tenzij schriftelijk anders overeengekomen.</li>
        <li>Daaronder vallen onder meer broncode, softwarecomponenten, frameworks, scripts, ontwerpen, documentatie, methodieken, templates en generieke onderdelen.</li>
        <li>Na volledige betaling verkrijgt Klant een niet-exclusief, niet-overdraagbaar en voor de duur van de betreffende intellectuele eigendomsrechten geldend gebruiksrecht voor het overeengekomen doel, tenzij schriftelijk anders is overeengekomen.</li>
        <li>Klant mag een voor hem ontwikkelde website bedrijfsmatig gebruiken en door een derde laten beheren, voor zover dit niet leidt tot ongeoorloofde verveelvoudiging of exploitatie van generieke componenten van Servura.</li>
        <li>Overdracht van intellectuele eigendomsrechten vindt uitsluitend plaats indien dit uitdrukkelijk schriftelijk is overeengekomen en voor zover een dergelijke overdracht rechtsgeldig kan plaatsvinden.</li>
        <li>Levering van broncode vindt uitsluitend plaats indien dit schriftelijk is overeengekomen.</li>
        <li>Rechten op software, lettertypen, afbeeldingen, plug-ins, open-sourcecomponenten en overige materialen van derden blijven onderworpen aan de bijbehorende licentievoorwaarden.</li>
    </ul>

    <h2>Artikel 18 — Materialen van Klant</h2>
    <ul>
        <li>Klant staat ervoor in dat het gebruik van door Klant aangeleverde teksten, afbeeldingen, handelsmerken, software, datasets en andere materialen geen rechten van derden schendt.</li>
        <li>Klant vrijwaart Servura tegen aanspraken van derden die voortvloeien uit onrechtmatig door Klant aangeleverde materialen, voor zover de aanspraak aan Klant kan worden toegerekend.</li>
        <li>Deze vrijwaring geldt niet voor zover Servura wist of redelijkerwijs moest begrijpen dat het gebruik kennelijk onrechtmatig was en desondanks zonder noodzaak doorging met dat gebruik.</li>
    </ul>

    <h2>Artikel 19 — Portfolio en naamsvermelding</h2>
    <ul>
        <li>Servura mag afgeronde werkzaamheden als referentie in haar portfolio tonen, tenzij schriftelijk anders overeengekomen.</li>
        <li>Servura zal daarbij geen vertrouwelijke informatie publiceren.</li>
        <li>Indien Klant vóór publicatie gemotiveerd aangeeft dat zwaarwegende bedrijfsbelangen zich tegen openbaarmaking verzetten, zullen partijen daarover redelijk overleg voeren.</li>
        <li>Een eventuele naams- of linkvermelding van Servura op een website van Klant wordt vooraf overeengekomen.</li>
    </ul>

    <h2>Artikel 20 — Vertrouwelijkheid</h2>
    <ul>
        <li>Partijen behandelen informatie waarvan zij weten of redelijkerwijs moeten begrijpen dat deze vertrouwelijk is als vertrouwelijk.</li>
        <li>Vertrouwelijke informatie wordt uitsluitend gebruikt voor de uitvoering van de overeenkomst.</li>
        <li>Deze verplichting geldt niet voor informatie die:
            <ol>
                <li>reeds openbaar was zonder schending van deze verplichting;</li>
                <li>rechtmatig van een derde is verkregen;</li>
                <li>zelfstandig is ontwikkeld zonder gebruik van vertrouwelijke informatie; of</li>
                <li>op grond van wet- of regelgeving of een bevoegd gegeven bevel moet worden verstrekt.</li>
            </ol>
        </li>
        <li>Indien wettelijk toegestaan, zal de ontvangende partij de andere partij vooraf informeren over een verplichte verstrekking.</li>
    </ul>

    <h2>Artikel 21 — Persoonsgegevens</h2>
    <ul>
        <li>Iedere partij verwerkt persoonsgegevens in overeenstemming met de toepasselijke privacywetgeving.</li>
        <li>Indien Servura namens Klant persoonsgegevens verwerkt en daarbij als verwerker kwalificeert, geldt de <a href="{{ route('legal.dpa') }}">Verwerkersovereenkomst</a> van Servura of een schriftelijk overeengekomen gelijkwaardige overeenkomst.</li>
        <li>Voor verwerkingen waarvoor Servura zelf het doel en de middelen bepaalt, is Servura zelfstandig verwerkingsverantwoordelijke.</li>
    </ul>

    <h2>Artikel 22 — Beveiliging</h2>
    <ul>
        <li>Servura treft passende technische en organisatorische beveiligingsmaatregelen, rekening houdend met de aard van de diensten, de stand van de techniek, uitvoeringskosten en relevante risico's.</li>
        <li>Geen enkel digitaal systeem kan volledig vrij van beveiligingsrisico's worden gegarandeerd.</li>
        <li>Klant is verantwoordelijk voor zorgvuldig beheer van wachtwoorden, accounts, API-sleutels en andere toegangsgegevens die aan Klant beschikbaar zijn gesteld.</li>
        <li>Klant meldt een vermoedelijke compromittering van toegangsgegevens zo spoedig mogelijk aan Servura.</li>
    </ul>

    <h2>Artikel 23 — Back-ups</h2>
    <ul>
        <li>Back-ups worden uitsluitend door Servura verzorgd voor zover dit onderdeel is van de overeengekomen dienst.</li>
        <li>De frequentie, retentie en herstelmogelijkheden worden bepaald in de <a href="{{ route('legal.hosting') }}">Hostingvoorwaarden</a>, offerte of toepasselijke SLA.</li>
        <li>Tenzij schriftelijk anders overeengekomen, blijft Klant verantwoordelijk voor het aanhouden van een eigen actuele kopie van bedrijfskritische gegevens.</li>
        <li>Servura kan niet garanderen dat iedere back-up in alle omstandigheden volledig of bruikbaar is.</li>
    </ul>

    <h2>Artikel 24 — Aansprakelijkheid</h2>
    <ul>
        <li>Servura is aansprakelijk voor directe schade die het rechtstreekse gevolg is van een aan Servura toerekenbare tekortkoming in de nakoming van de overeenkomst, voor zover aan de overige wettelijke vereisten voor aansprakelijkheid is voldaan.</li>
        <li>De totale aansprakelijkheid van Servura is per gebeurtenis of reeks van samenhangende gebeurtenissen beperkt tot het bedrag dat Klant in de twaalf maanden voorafgaand aan de schadeveroorzakende gebeurtenis voor de betreffende dienst aan Servura heeft betaald, met een maximum van € [bedrag].</li>
        <li>Indien de aansprakelijkheidsverzekering van Servura in het betreffende geval dekking biedt en een hoger bedrag uitkeert, is de aansprakelijkheid beperkt tot het bedrag dat de verzekeraar daadwerkelijk uitkeert, vermeerderd met het toepasselijke eigen risico.</li>
        <li>Servura is, voor zover wettelijk toegestaan, niet aansprakelijk voor indirecte schade, waaronder gevolgschade, gederfde winst, gemiste besparingen, verlies van goodwill, bedrijfsstagnatie en schade door verlies van gegevens.</li>
        <li>Beperkingen van aansprakelijkheid gelden niet voor zover schade het gevolg is van opzet of bewuste roekeloosheid van de bedrijfsleiding van Servura of voor zover een beperking naar dwingend recht niet is toegestaan.</li>
        <li>Klant neemt redelijke maatregelen om schade te voorkomen en te beperken.</li>
        <li>Servura krijgt, voor zover redelijk mogelijk, eerst gelegenheid een tekortkoming te herstellen voordat Klant vervangende schadevergoeding verlangt.</li>
    </ul>

    <h2>Artikel 25 — Overmacht</h2>
    <ul>
        <li>Servura is niet gehouden tot nakoming voor zover nakoming wordt verhinderd door een omstandigheid die niet aan Servura kan worden toegerekend.</li>
        <li>Hieronder kunnen, afhankelijk van de omstandigheden, vallen: ernstige storingen in internet- of telecommunicatie-infrastructuur, stroomstoringen, cyberaanvallen, DDoS-aanvallen, storingen bij datacenters of essentiële leveranciers, overheidsmaatregelen, oorlog, brand, natuurrampen, epidemieën en andere omstandigheden buiten de redelijke invloedssfeer van Servura.</li>
        <li>Servura zal Klant zo spoedig als redelijk mogelijk informeren wanneer overmacht wezenlijke gevolgen heeft.</li>
        <li>Indien de overmacht langer dan 60 dagen voortduurt en verdere uitvoering redelijkerwijs niet kan worden verlangd, mag iedere partij het nog niet uitgevoerde gedeelte van de overeenkomst beëindigen zonder schadeplichtigheid.</li>
    </ul>

    <h2>Artikel 26 — Looptijd van periodieke diensten</h2>
    <ul>
        <li>De looptijd staat in de offerte of overeenkomst.</li>
        <li>Indien geen looptijd is overeengekomen, geldt een overeenkomst voor periodieke diensten voor onbepaalde tijd.</li>
        <li>Een overeenkomst voor onbepaalde tijd kan schriftelijk worden opgezegd met een opzegtermijn van één maand, tenzij schriftelijk anders overeengekomen.</li>
        <li>Een overeenkomst voor bepaalde tijd eindigt op de overeengekomen einddatum, tenzij schriftelijk automatische verlenging is overeengekomen.</li>
    </ul>

    <h2>Artikel 27 — Beëindiging wegens tekortkoming</h2>
    <ul>
        <li>Indien een partij wezenlijk tekortschiet in de nakoming van de overeenkomst, stelt de andere partij haar schriftelijk in gebreke en geeft zij een redelijke termijn voor herstel, tenzij nakoming blijvend onmogelijk is of een ingebrekestelling wettelijk niet is vereist.</li>
        <li>Indien de tekortkoming na die termijn voortduurt, mag de andere partij de overeenkomst geheel of gedeeltelijk ontbinden voor zover de tekortkoming dit rechtvaardigt.</li>
        <li>Servura mag de overeenkomst met onmiddellijke ingang opschorten of beëindigen indien:
            <ol>
                <li>Klant failliet wordt verklaard;</li>
                <li>surseance van betaling wordt aangevraagd of verleend;</li>
                <li>de onderneming van Klant wordt beëindigd;</li>
                <li>Klant de diensten gebruikt voor kennelijk onrechtmatige activiteiten; of</li>
                <li>voortzetting van de dienst Servura blootstelt aan een ernstig en concreet beveiligings- of juridisch risico.</li>
            </ol>
        </li>
        <li>Servura past een onmiddellijke maatregel alleen toe voor zover dit gezien de omstandigheden redelijk en proportioneel is.</li>
    </ul>

    <h2>Artikel 28 — Gevolgen van beëindiging</h2>
    <ul>
        <li>Reeds verrichte werkzaamheden en verschuldigde bedragen blijven betaalbaar.</li>
        <li>Op verzoek van Klant zal Servura binnen redelijke grenzen meewerken aan overdracht van gegevens die op grond van de overeenkomst aan Klant toebehoren.</li>
        <li>Servura mag voor aanvullende migratie- of overdrachtswerkzaamheden het geldende tarief rekenen, tenzij de noodzaak tot migratie uitsluitend het gevolg is van een aan Servura toerekenbare tekortkoming.</li>
        <li>Verwijdering van hostinggegevens vindt plaats overeenkomstig de <a href="{{ route('legal.hosting') }}">Hostingvoorwaarden</a> en <a href="{{ route('legal.dpa') }}">Verwerkersovereenkomst</a>.</li>
    </ul>

    <h2>Artikel 29 — Klachten</h2>
    <ul>
        <li>Klant meldt klachten zo spoedig mogelijk nadat de aanleiding daarvoor is ontdekt of redelijkerwijs had kunnen worden ontdekt.</li>
        <li>Een klacht bevat voldoende informatie om Servura in staat te stellen deze te onderzoeken.</li>
        <li>Het niet onmiddellijk melden van een klacht leidt niet automatisch tot verlies van rechten, maar schade die redelijkerwijs voorkomen had kunnen worden door tijdige melding kan voor rekening van Klant blijven.</li>
        <li>Partijen proberen een klacht eerst in onderling overleg op te lossen.</li>
    </ul>

    <h2>Artikel 30 — Wijziging van deze voorwaarden</h2>
    <ul>
        <li>Servura mag deze algemene voorwaarden wijzigen indien daarvoor een redelijke aanleiding bestaat, waaronder wijzigingen in diensten, bedrijfsvoering, beveiliging, wetgeving of technische omstandigheden.</li>
        <li>Voor bestaande overeenkomsten kondigt Servura een materiële wijziging ten minste 30 dagen vooraf schriftelijk aan.</li>
        <li>Indien een wijziging de positie van Klant wezenlijk nadelig verandert, kan Klant de betreffende periodieke overeenkomst vóór de ingangsdatum van de wijziging beëindigen, tenzij de wijziging noodzakelijk is vanwege dwingende wetgeving of uitsluitend een niet-nadelige administratieve wijziging betreft.</li>
        <li>De toepasselijke versie wordt beschikbaar gesteld via de website van Servura.</li>
    </ul>

    <h2>Artikel 31 — Nietigheid</h2>
    <ul>
        <li>Indien een bepaling geheel of gedeeltelijk nietig, vernietigbaar of anderszins onafdwingbaar blijkt, blijven de overige bepalingen van kracht. Partijen vervangen de betreffende bepaling door een rechtsgeldige bepaling die doel en strekking zoveel mogelijk benadert.</li>
    </ul>

    <h2>Artikel 32 — Overdracht</h2>
    <ul>
        <li>Klant mag rechten of verplichtingen uit de overeenkomst niet zonder schriftelijke toestemming van Servura aan een derde overdragen, behoudens voor zover dit recht wettelijk niet kan worden beperkt.</li>
        <li>Servura mag de overeenkomst in het kader van overdracht van haar onderneming of activiteiten overdragen aan een rechtsopvolger, mits de continuïteit en rechten van Klant daarbij redelijkerwijs worden gewaarborgd.</li>
    </ul>

    <h2>Artikel 33 — Toepasselijk recht en geschillen</h2>
    <ul>
        <li>Op alle overeenkomsten met Servura is Nederlands recht van toepassing.</li>
        <li>Partijen zullen zich eerst inspannen een geschil in onderling overleg op te lossen.</li>
        <li>Indien dit niet lukt, wordt het geschil voorgelegd aan de volgens de Nederlandse wet bevoegde rechter.</li>
        <li>Voor zover een forumkeuze rechtsgeldig kan worden overeengekomen, is tevens bevoegd de rechter van het arrondissement waarin Servura is gevestigd.</li>
    </ul>

    <p class="mt-8 text-sm text-slate-500">
        {{ config('company.trade_name', 'Servura') }}<br>
        {{ config('company.address', '[Adres]') }}<br>
        {{ config('company.postal_code', '[Postcode]') }} {{ config('company.city', '[Plaats]') }}<br>
        KvK: {{ config('company.kvk_number', '[KvK]') }}<br>
        BTW: {{ config('company.vat_number', '[Btw]') }}<br>
        E-mail: {{ config('company.email', '[E-mail]') }}
    </p>
@endsection
