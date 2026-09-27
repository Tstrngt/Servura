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
    <p><em>Let op: dit is een concepttekst en nog niet juridisch gecontroleerd.</em></p>

    <h2>1. Dienstomschrijving</h2>
    <p>Servura biedt hostingdiensten aan, waaronder opslag van websitebestanden, databases, e-maildiensten en technisch beheer.</p>

    <h2>2. Hostingpakketten</h2>
    <p>De specificaties van hostingpakketten staan vermeld in de offerte, het klantportaal of op de website. Aanpassingen zijn mogelijk na overleg.</p>

    <h2>3. Opslag</h2>
    <p>Elk hostingpakket heeft een maximale opslagruimte. Bij overschrijding nemen wij contact op om een passende oplossing te bespreken.</p>

    <h2>4. Dataverkeer</h2>
    <p>Dataverkeer is onderworpen aan fair-use. Bij structureel excessief gebruik kan een upgrade of maatregel worden voorgesteld.</p>

    <h2>5. Fair use</h2>
    <p>Gebruik moet redelijk zijn ten opzichte van het gekozen pakket en andere klanten. Bij twijfel nemen wij contact op.</p>

    <h2>6. Serverlocaties</h2>
    <p>Hosting vindt plaats in Europese datacenters, tenzij anders is overeengekomen. De actuele locaties staan vermeld op de website.</p>

    <h2>7. Serveronderhoud</h2>
    <p>Onderhoud wordt waar mogelijk buiten kantooruren gepland. Wij proberen storingen door onderhoud te beperken.</p>

    <h2>8. Beschikbaarheid</h2>
    <p>Servura streeft naar een hoge beschikbaarheid. Een concrete uptime-garantie geldt alleen indien deze schriftelijk als SLA is overeengekomen. Huidige SLA: <strong>{{ config('legal.hosting.uptime_sla') ?? '[nog in te vullen]' }}</strong>.</p>

    <h2>9. Storingen</h2>
    <p>Bij storingen meldt de Klant dit via het klantportaal of per e-mail. Wij lossen storingen zo spoedig mogelijk op.</p>

    <h2>10. Back-ups</h2>
    <p>Servura maakt back-ups volgens het gekozen pakket. Frequentie: <strong>{{ config('legal.hosting.backup_frequency') ?? '[nog in te vullen]' }}</strong>. Retentie: <strong>{{ config('legal.hosting.backup_retention') ?? '[nog in te vullen]' }}</strong>.</p>

    <h2>11. Herstel van back-ups</h2>
    <p>Herstel op aanvraag wordt uitgevoerd binnen een redelijke termijn. Hersteltermijn: <strong>{{ config('legal.hosting.recovery_time') ?? '[nog in te vullen]' }}</strong>.</p>

    <h2>12. Beveiliging</h2>
    <p>Servura treft passende technische en organisatorische maatregelen. De Klant blijft verantwoordelijk voor de beveiliging van eigen toepassingen, wachtwoorden en content.</p>

    <h2>13. E-maildiensten</h2>
    <p>E-maildiensten worden geleverd conform het gekozen pakket. De Klant mag de e-maildiensten niet gebruiken voor spam of andere verboden activiteiten.</p>

    <h2>14. Domeinnamen</h2>
    <p>Servura kan domeinregistratie en DNS-beheer verzorgen. Het eigendom van het domein blijft bij de Klant.</p>

    <h2>15. Migraties</h2>
    <p>Migraties worden op aanvraag uitgevoerd. Wij informeren vooraf over mogelijke risico's en kosten.</p>

    <h2>16. Technisch beheer</h2>
    <p>Technisch beheer omvat onder meer updates, monitoring en beveiligingsmaatregelen. De exacte omvang staat in het gekozen pakket.</p>

    <h2>17. Verboden gebruik</h2>
    <p>Zie de <a href="{{ route('legal.acceptable-use') }}">Acceptable Use Policy</a> voor een lijst van verboden activiteiten.</p>

    <h2>18. Excessief resourcegebruik</h2>
    <p>Bij excessief gebruik dat andere klanten hindert, nemen wij contact op. Bij ernstige situaties kunnen wij tijdelijk maatregelen treffen.</p>

    <h2>19. Opschorting</h2>
    <p>Servura mag diensten opschorten bij niet-betaling of ernstig misbruik, conform de algemene voorwaarden.</p>

    <h2>20. Misbruik</h2>
    <p>Misbruik kan worden gemeld via <a href="{{ route('legal.abuse') }}">Misbruik melden</a>.</p>

    <h2>21. Beëindiging</h2>
    <p>Beëindiging verloopt conform de algemene voorwaarden en de overeengekomen opzegtermijn.</p>

    <h2>22. Data-export</h2>
    <p>De Klant kan voor beëindiging een export van eigen gegevens aanvragen.</p>

    <h2>23. Verwijdering van gegevens</h2>
    <p>Na beëindiging worden gegevens binnen een redelijke termijn verwijderd, tenzij een wettelijke bewaarplicht geldt.</p>

    <h2>24. Aansprakelijkheid</h2>
    <p>De aansprakelijkheid van Servura is beperkt conform de algemene voorwaarden.</p>

    <h2>25. Eventuele SLA</h2>
    <p>Een Service Level Agreement geldt alleen indien deze schriftelijk of via de offerte is overeengekomen.</p>
@endsection
