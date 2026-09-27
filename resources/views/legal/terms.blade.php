@extends('legal.layout')

@section('title', 'Algemene voorwaarden - '.config('company.trade_name', 'Servura'))
@section('meta-description', 'Algemene voorwaarden van '.config('company.trade_name', 'Servura').'.')
@section('meta-keywords', 'algemene voorwaarden, voorwaarden, Servura')

@section('legal-title')
    Algemene voorwaarden
@endsection

@section('legal-meta')
    Versie {{ config('legal.versions.terms.version', '1.0') }} – laatst gewijzigd op {{ config('legal.versions.terms.effective_date', '[datum nog in te vullen]') }}
@endsection

@section('legal-content')
    <p><em>Let op: dit is een concepttekst en nog niet juridisch gecontroleerd.</em></p>

    <h2>1. Definities</h2>
    <p>In deze algemene voorwaarden wordt verstaan onder:</p>
    <ul>
        <li><strong>Servura:</strong> {{ config('company.legal_name', '[Juridische bedrijfsnaam nog in te vullen]') }}, handelend onder de naam {{ config('company.trade_name', 'Servura') }}.</li>
        <li><strong>Klant:</strong> de natuurlijke of rechtspersoon die een overeenkomst aangaat met Servura.</li>
        <li><strong>Overeenkomst:</strong> de schriftelijke of elektronische overeenkomst waarbij Servura diensten aan de Klant levert.</li>
        <li><strong>Diensten:</strong> onder meer webdesign, webontwikkeling, hosting, onderhoud en support.</li>
    </ul>

    <h2>2. Toepasselijkheid</h2>
    <p>Deze voorwaarden zijn van toepassing op alle aanbiedingen, offertes en overeenkomsten van Servura. Afwijkingen gelden slechts indien uitdrukkelijk schriftelijk of per e-mail overeengekomen.</p>

    <h2>3. Offertes</h2>
    <p>Alle offertes zijn vrijblijvend en geldig gedurende de in de offerte vermelde termijn. Offertes zijn gebaseerd op de bij opdracht verstrekte informatie. Wijzigingen in de opdracht kunnen leiden tot prijsaanpassingen.</p>

    <h2>4. Totstandkoming overeenkomst</h2>
    <p>Een overeenkomst komt tot stand na schriftelijke of elektronische acceptatie van de offerte door de Klant, dan wel na betaling van een factuur of aanbetaling.</p>

    <h2>5. Prijzen</h2>
    <p>Alle prijzen zijn exclusief btw, tenzij anders vermeld. Prijzen gelden voor de in de offerte vastgelegde werkzaamheden. Meerwerk wordt separaat in rekening gebracht na overleg.</p>

    <h2>6. Btw</h2>
    <p>Op alle diensten wordt btw berekend conform de geldende Nederlandse wetgeving. Bij buitenlandse klanten kan afwijkende btw-regeling van toepassing zijn.</p>

    <h2>7. Betaling</h2>
    <p>Facturen dienen binnen de op de factuur vermelde termijn te worden voldaan. Bij niet-tijdige betaling is Servura gerechtigd het werk op te schorten en aanspraak te maken op de wettelijke rente en incassokosten.</p>

    <h2>8. Uitvoering van werkzaamheden</h2>
    <p>Servura voert de overeengekomen werkzaamheden naar beste inzicht en vakmanschap uit. De Klant verschaft tijdig alle benodigde informatie, content en toegang.</p>

    <h2>9. Verplichtingen van de klant</h2>
    <p>De Klant is verantwoordelijk voor de juistheid en volledigheid van aangeleverde gegevens. De Klant vrijwaart Servura voor aanspraken van derden met betrekking tot door de Klant aangeleverde content.</p>

    <h2>10. Oplevering</h2>
    <p>De diensten worden opgeleverd conform de in de offerte beschreven specificaties. Een website wordt in principline opgeleverd na akkoord van de Klant.</p>

    <h2>11. Acceptatie</h2>
    <p>Indien de Klant binnen {{ config('legal.hosting.support_response_time', '[termijn nog in te vullen]') }} na oplevering geen gemotiveerde bezwaren meldt, geldt het werk als geaccepteerd.</p>

    <h2>12. Wijzigingen</h2>
    <p>Wijzigingen in de opdracht na totstandkoming van de overeenkomst worden schriftelijk vastgelegd en kunnen invloed hebben op prijs en planning.</p>

    <h2>13. Meerwerk</h2>
    <p>Meerwerk wordt op basis van nacalculatie of vooraf overeengekomen tarieven in rekening gebracht.</p>

    <h2>14. Websites en software</h2>
    <p>Servura levert websites en applicaties op basis van bestaande frameworks, libraries en maatwerk. De Klant krijgt een gebruiksrecht, tenzij anders overeengekomen.</p>

    <h2>15. Intellectueel eigendom</h2>
    <p>Tot volledige betaling blijft alle door Servura ontwikkelde werk eigendom van Servura. Na betaling ontvangt de Klant het overeengekomen gebruiksrecht.</p>

    <h2>16. Licenties</h2>
    <p>Voor gebruikte software, plugins of thema’s kunnen afzonderlijke licentievoorwaarden gelden. De Klant is verantwoordelijk voor het naleven daarvan.</p>

    <h2>17. Hosting</h2>
    <p>Hostingdiensten worden geleverd conform de <a href="{{ route('legal.hosting') }}">hostingvoorwaarden</a>. Servura zet zich in voor een stabiele dienstverlening, maar geeft geen onrealistische garanties tenzij dit schriftelijk is overeengekomen.</p>

    <h2>18. Onderhoud</h2>
    <p>Onderhoudspakketten worden per kalendermaand of jaar gefactureerd. Details staan in de offerte of het desbetreffende hosting-/onderhoudsabonnement.</p>

    <h2>19. Support</h2>
    <p>Support wordt geleverd op basis van het gekozen pakket en binnen de overeengekomen reactietermijn, indien van toepassing.</p>

    <h2>20. Domeinnamen</h2>
    <p>Domeinregistratie geschiedt namens de Klant. De Klant blijft eigenaar van het geregistreerde domein. Servura kan domeinbeheer als dienst verlenen.</p>

    <h2>21. Beschikbaarheid</h2>
    <p>Concreet beschikbaarheidspercentage of SLA is alleen van toepassing indien dit uitdrukkelijk in de offerte of een aparte SLA is opgenomen.</p>

    <h2>22. Back-ups</h2>
    <p>Servura maakt back-ups volgens het gekozen hosting- of onderhoudspakket. Herstel uit back-ups geschiedt op aanvraag.</p>

    <h2>23. Beveiliging</h2>
    <p>Servura treft passende technische en organisatorische maatregelen om diensten te beveiligen. De Klant is zelf verantwoordelijk voor het veilig gebruiken van toegangsgegevens.</p>

    <h2>24. Aansprakelijkheid</h2>
    <p>De aansprakelijkheid van Servura is beperkt tot directe schade die het gevolg is van opzet of grove schuld, en tot maximaal het factuurbedrag van de betreffende dienst.</p>

    <h2>25. Overmacht</h2>
    <p>Servura is niet gehouden tot het nakomen van verplichtingen indien dit door overmacht wordt verhinderd.</p>

    <h2>26. Duur</h2>
    <p>De looptijd van abonnementen en onderhoudspakketten staat vermeld in de offerte of het klantportaal.</p>

    <h2>27. Opzegging</h2>
    <p>Opzegging dient schriftelijk of via het klantportaal te geschieden met inachtneming van de overeengekomen opzegtermijn.</p>

    <h2>28. Opschorting</h2>
    <p>Servura mag diensten tijdelijk opschorten bij niet-nakoming door de Klant of bij ernstig misbruik van de diensten.</p>

    <h2>29. Beëindiging</h2>
    <p>Na beëindiging wordt data conform de afspraken opgeleverd of verwijderd. Zie ook de <a href="{{ route('legal.hosting') }}">hostingvoorwaarden</a>.</p>

    <h2>30. Gegevens na beëindiging</h2>
    <p>De Klant is zelf verantwoordelijk voor het exporteren van eigen gegevens voorafgaand aan beëindiging. Servura kan geëxporteerde data leveren tegen vergoeding.</p>

    <h2>31. Klachten</h2>
    <p>Klachten over de uitvoering van de overeenkomst dienen zo spoedig mogelijk, maar uiterlijk binnen {{ config('legal.hosting.support_response_time', '[termijn nog in te vullen]') }} na constatering, schriftelijk of per e-mail te worden gemeld.</p>

    <h2>32. Wijziging voorwaarden</h2>
    <p>Servura kan deze voorwaarden wijzigen. Wijzigingen worden met minimaal 30 dagen van tevoren aangekondigd.</p>

    <h2>33. Toepasselijk recht</h2>
    <p>Op deze voorwaarden en alle overeenkomsten is Nederlands recht van toepassing. Geschillen worden voorgelegd aan de bevoegde rechter in Nederland.</p>

    <p class="mt-8 text-sm text-slate-500">
        {{ config('company.trade_name', 'Servura') }}<br>
        {{ config('company.address', '[Adres]') }}<br>
        {{ config('company.postal_code', '[Postcode]') }} {{ config('company.city', '[Plaats]') }}<br>
        KvK: {{ config('company.kvk_number', '[KvK]') }}<br>
        BTW: {{ config('company.vat_number', '[Btw]') }}<br>
        E-mail: {{ config('company.email', '[E-mail]') }}
    </p>
@endsection
