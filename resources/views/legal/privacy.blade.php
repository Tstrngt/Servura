@extends('legal.layout')

@section('title', 'Privacyverklaring - '.config('company.trade_name', 'Servura'))
@section('meta-description', 'Privacyverklaring van '.config('company.trade_name', 'Servura').'.')
@section('meta-keywords', 'privacyverklaring, privacy, Servura, GDPR')

@section('legal-title')
    Privacyverklaring
@endsection

@section('legal-meta')
    Versie {{ config('legal.versions.privacy.version', '1.0') }} – laatst gewijzigd op {{ config('legal.versions.privacy.effective_date', '[datum nog in te vullen]') }}
@endsection

@section('legal-content')
    <p><em>Let op: vul alle placeholder-gegevens aan met de werkelijke bedrijfsgegevens en bewaartermijnen voordat u deze pagina publiceert.</em></p>

    <h2>1. Wie is verantwoordelijk?</h2>
    <p>Servura is verantwoordelijk voor de verwerking van persoonsgegevens zoals beschreven in deze privacyverklaring.</p>
    <p>
        {{ config('company.legal_name', '[volledige handelsnaam/rechtsvorm]') }}<br>
        {{ config('company.address', '[adres]') }}<br>
        {{ config('company.postal_code', '[postcode]') }} {{ config('company.city', '[vestigingsplaats]') }}<br>
        KvK: {{ config('company.kvk_number', '[KvK-nummer]') }}<br>
        E-mail: {{ config('company.email', '[privacy e-mailadres]') }}<br>
        Website: <a href="https://servura.nl" target="_blank" rel="noopener noreferrer">https://servura.nl</a>
    </p>

    <h2>2. Welke persoonsgegevens verwerken wij?</h2>
    <p>Afhankelijk van uw relatie met Servura kunnen wij onder meer verwerken:</p>
    <ul>
        <li>naam;</li>
        <li>bedrijfsnaam;</li>
        <li>adresgegevens;</li>
        <li>e-mailadres;</li>
        <li>telefoonnummer;</li>
        <li>factuurgegevens;</li>
        <li>KvK- en btw-gegevens;</li>
        <li>inhoud van correspondentie;</li>
        <li>offerte- en contractgegevens;</li>
        <li>betaalinformatie;</li>
        <li>accountgegevens;</li>
        <li>technische logs;</li>
        <li>IP-adressen;</li>
        <li>beveiligingsinformatie;</li>
        <li>supportverzoeken;</li>
        <li>gegevens die u zelf via formulieren of andere communicatie verstrekt.</li>
    </ul>
    <p>Wij verwerken niet méér persoonsgegevens dan redelijkerwijs nodig is voor het betreffende doel.</p>

    <h2>3. Waarom verwerken wij persoonsgegevens?</h2>

    <h3>Contact en communicatie</h3>
    <p>Wij verwerken contactgegevens en correspondentie om vragen te beantwoorden en contact met u te onderhouden.</p>
    <p><strong>Grondslag:</strong> gerechtvaardigd belang of, wanneer de communicatie betrekking heeft op het aangaan of uitvoeren van een overeenkomst, noodzakelijkheid voor de overeenkomst.</p>

    <h3>Offertes en overeenkomsten</h3>
    <p>Wij verwerken gegevens om offertes op te stellen, afspraken te maken en overeenkomsten uit te voeren.</p>
    <p><strong>Grondslag:</strong> noodzakelijk voor het aangaan of uitvoeren van een overeenkomst.</p>

    <h3>Facturatie en administratie</h3>
    <p>Wij verwerken gegevens om facturen op te stellen, betalingen te administreren en aan fiscale en administratieve verplichtingen te voldoen.</p>
    <p><strong>Grondslag:</strong> uitvoering van de overeenkomst en wettelijke verplichting.</p>

    <h3>Hosting, beheer en beveiliging</h3>
    <p>Wij kunnen technische gegevens, logs en IP-adressen verwerken voor beveiliging, fraudepreventie, storingsonderzoek en bescherming van onze systemen.</p>
    <p><strong>Grondslag:</strong> gerechtvaardigd belang bij veilige en betrouwbare dienstverlening en, waar van toepassing, uitvoering van de overeenkomst.</p>

    <h3>Wettelijke verplichtingen</h3>
    <p>Wanneer wij wettelijk verplicht zijn gegevens te verstrekken of te bewaren, verwerken wij deze op grond van de betreffende wettelijke verplichting.</p>

    <h3>Marketing</h3>
    <p>Wij gebruiken persoonsgegevens voor direct marketing alleen wanneer daarvoor een geldige rechtsgrond bestaat. Waar toestemming is vereist, vragen wij die vooraf.</p>
    <p>U kunt zich altijd afmelden voor elektronische marketing waarvoor een afmeldmogelijkheid moet worden aangeboden.</p>

    <h2>4. Persoonsgegevens die wij namens klanten verwerken</h2>
    <ul>
        <li>Bij hosting, beheer en ontwikkeling kunnen wij persoonsgegevens verwerken die door een klant in diens website, applicatie of systeem zijn opgeslagen.</li>
        <li>In die situatie kan de klant verwerkingsverantwoordelijke zijn en Servura verwerker.</li>
        <li>Op die verwerking is de <a href="{{ route('legal.dpa') }}">Verwerkersovereenkomst</a> van Servura of een afzonderlijk overeengekomen verwerkersovereenkomst van toepassing.</li>
    </ul>

    <h2>5. Bewaartermijnen</h2>
    <p>Wij bewaren persoonsgegevens niet langer dan noodzakelijk voor het doel waarvoor zij zijn verzameld, tenzij een wettelijke bewaarplicht een langere termijn vereist.</p>
    <p>Wij hanteren in beginsel:</p>
    <ul>
        <li>Contactaanvragen: maximaal 6 maanden na afronding van het contact, tenzij de gegevens nodig blijven voor een overeenkomst, geschil of andere gerechtvaardigde reden.</li>
        <li>Niet-geaccepteerde offertes: maximaal 2 jaar nadat duidelijk is geworden dat de offerte niet wordt geaccepteerd, tenzij een kortere termijn passend is.</li>
        <li>Klant- en contractgegevens: gedurende de overeenkomst en daarna zolang dit redelijkerwijs noodzakelijk is voor administratie, rechtsvorderingen en naleving van wettelijke verplichtingen.</li>
        <li>Facturen en fiscale basisadministratie: in beginsel 7 jaar, of langer wanneer een bijzondere wettelijke bewaartermijn geldt.</li>
        <li>Supportinformatie: in beginsel maximaal 2 jaar na afhandeling, tenzij langere bewaring noodzakelijk is.</li>
        <li>Beveiligings- en serverlogs: [werkelijke termijn invullen, bijvoorbeeld 30/90/180 dagen], tenzij een concreet beveiligingsincident langere bewaring noodzakelijk maakt.</li>
        <li>Back-ups: volgens de in de Hostingvoorwaarden en interne back-upregeling genoemde retentietermijnen.</li>
    </ul>

    <h2>6. Met wie delen wij persoonsgegevens?</h2>
    <p>Wij delen persoonsgegevens alleen wanneer dit noodzakelijk is voor onze dienstverlening, bedrijfsvoering of wettelijke verplichtingen.</p>
    <p>Categorieën ontvangers kunnen zijn:</p>
    <ul>
        <li>hosting- en datacenterleveranciers;</li>
        <li>domeinregistrars;</li>
        <li>e-mailproviders;</li>
        <li>boekhoud- en administratiedienstverleners;</li>
        <li>betaalproviders;</li>
        <li>IT- en beveiligingsleveranciers;</li>
        <li>juridisch of financieel adviseurs;</li>
        <li>bevoegde overheidsinstanties wanneer wij wettelijk verplicht zijn gegevens te verstrekken.</li>
    </ul>
    <p>Wij verkopen persoonsgegevens niet.</p>

    <h2>7. Verwerkers en subverwerkers</h2>
    <ul>
        <li>Wanneer een externe dienstverlener namens ons persoonsgegevens verwerkt, sluiten wij waar vereist passende afspraken over gegevensbescherming.</li>
        <li>Voor diensten waarbij Servura zelf als verwerker optreedt, kan Servura subverwerkers inschakelen overeenkomstig de toepasselijke <a href="{{ route('legal.dpa') }}">Verwerkersovereenkomst</a>.</li>
        <li>Actuele belangrijke subverwerkers:
            <ul>
                <li>[naam hosting/datacenter] — [doel] — [land];</li>
                <li>[naam e-mailprovider] — [doel] — [land];</li>
                <li>[naam back-upprovider] — [doel] — [land];</li>
                <li>[naam monitoringprovider] — [doel] — [land].</li>
            </ul>
        </li>
    </ul>
    <p><em>Deze lijst moet vóór publicatie worden aangepast aan de werkelijk gebruikte leveranciers.</em></p>

    <h2>8. Doorgifte buiten de EER</h2>
    <ul>
        <li>Indien persoonsgegevens buiten de Europese Economische Ruimte worden verwerkt, zorgen wij voor een rechtsgeldige grondslag voor de doorgifte, bijvoorbeeld een adequaatheidsbesluit of toepasselijke passende waarborgen.</li>
        <li>Meer informatie hierover kan worden opgevraagd via {{ config('company.email', '[privacy e-mailadres]') }}.</li>
    </ul>

    <h2>9. Beveiliging</h2>
    <ul>
        <li>Wij treffen passende technische en organisatorische maatregelen om persoonsgegevens te beschermen tegen verlies, onbevoegde toegang, misbruik en andere ongeoorloofde verwerking.</li>
        <li>De maatregelen worden afgestemd op de aard van de gegevens, risico's, stand van de techniek en uitvoeringskosten.</li>
    </ul>

    <h2>10. Uw privacyrechten</h2>
    <p>Afhankelijk van de omstandigheden heeft u onder de AVG onder meer het recht om:</p>
    <ul>
        <li>uw persoonsgegevens in te zien;</li>
        <li>onjuiste gegevens te laten corrigeren;</li>
        <li>gegevens te laten verwijderen;</li>
        <li>verwerking te laten beperken;</li>
        <li>bezwaar te maken tegen bepaalde verwerkingen;</li>
        <li>gegevens over te laten dragen wanneer het recht op dataportabiliteit van toepassing is;</li>
        <li>gegeven toestemming in te trekken.</li>
    </ul>
    <p>Een verzoek kan worden gestuurd naar {{ config('company.email', '[privacy e-mailadres]') }}.</p>
    <p>Wij kunnen aanvullende informatie vragen wanneer dit redelijkerwijs nodig is om uw identiteit vast te stellen.</p>

    <h2>11. Klacht indienen</h2>
    <ul>
        <li>Heeft u een klacht over onze verwerking van persoonsgegevens, neem dan bij voorkeur eerst contact met ons op.</li>
        <li>U heeft daarnaast het recht een klacht in te dienen bij de Autoriteit Persoonsgegevens.</li>
    </ul>

    <h2>12. Geautomatiseerde besluitvorming</h2>
    <ul>
        <li>Servura neemt [geen] besluiten die uitsluitend zijn gebaseerd op geautomatiseerde verwerking en die voor betrokkenen rechtsgevolgen hebben of hen anderszins in aanmerkelijke mate treffen.</li>
        <li>Indien dit in de toekomst verandert, wordt deze privacyverklaring daarop aangepast.</li>
    </ul>

    <h2>13. Wijzigingen</h2>
    <ul>
        <li>Servura kan deze privacyverklaring aanpassen wanneer dienstverlening, wetgeving of gegevensverwerkingen wijzigen.</li>
        <li>De meest recente versie is beschikbaar via <a href="https://servura.nl/privacy" target="_blank" rel="noopener noreferrer">https://servura.nl/privacy</a>.</li>
    </ul>
@endsection
