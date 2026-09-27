@extends('legal.layout')

@section('title', 'Verwerkersovereenkomst - '.config('company.trade_name', 'Servura'))
@section('meta-description', 'Concept verwerkersovereenkomst (DPA) van '.config('company.trade_name', 'Servura').'.')
@section('meta-keywords', 'verwerkersovereenkomst, DPA, Servura')

@section('legal-title')
    Verwerkersovereenkomst
@endsection

@section('legal-meta')
    Concept – juridische controle vereist
@endsection

@section('legal-content')
    <p><em>Let op: dit document is een concept en uitsluitend bedoeld als voorbereiding. Het wordt op verzoek aan zakelijke klanten verstrekt wanneer Servura als verwerker optreedt. Juridische controle is vereist voordat het wordt gebruikt.</em></p>

    <h2>1. Partijen</h2>
    <p>Deze verwerkersovereenkomst wordt aangegaan tussen:</p>
    <ul>
        <li><strong>Opdrachtgever:</strong> de klant die persoonsgegevens verwerkt als verwerkingsverantwoordelijke;</li>
        <li><strong>Verwerker:</strong> {{ config('company.legal_name', '[Juridische bedrijfsnaam]') }}, handelend onder de naam {{ config('company.trade_name', 'Servura') }}.</li>
    </ul>

    <h2>2. Onderwerp verwerking</h2>
    <p>Servura verwerkt persoonsgegevens namens de Opdrachtgever ten behoeve van hosting, technisch beheer, websiteontwikkeling of andere overeengekomen diensten.</p>

    <h2>3. Duur</h2>
    <p>De verwerkersovereenkomst geldt gedurende de looptijd van de hoofdovereenkomst en eindigt bij beëindiging daarvan.</p>

    <h2>4. Aard en doel van verwerking</h2>
    <p>De verwerking betreft opslag, hosting, onderhoud en technische ondersteuning van gegevens die de Opdrachtgever aanlevert. Het doel is uitvoering van de hoofdovereenkomst.</p>

    <h2>5. Categorieën persoonsgegevens</h2>
    <p>Gegevens die de Opdrachtgever opslaat op de systemen van Servura, waaronder naam-, contact-, account- en eventuele bijzondere persoonsgegevens die de Opdrachtgever zelf verwerkt.</p>

    <h2>6. Categorieën betrokkenen</h2>
    <p>Medewerkers, klanten, prospects en andere personen van wie de Opdrachtgever gegevens verwerkt in de systemen die Servura beheert.</p>

    <h2>7. Instructies opdrachtgever</h2>
    <p>Servura verwerkt persoonsgegevens uitsluitend op instructie van de Opdrachtgever en niet voor eigen doeleinden.</p>

    <h2>8. Geheimhouding</h2>
    <p>Personen die toegang hebben tot persoonsgegevens zijn gehouden tot geheimhouding.</p>

    <h2>9. Beveiliging</h2>
    <p>Servura treft passende technische en organisatorische maatregelen om persoonsgegevens te beveiligen.</p>

    <h2>10. Subprocessors</h2>
    <p>Servura kan gebruikmaken van subprocessors. Een actuele lijst wordt op verzoek verstrekt of is opgenomen in de Privacyverklaring.</p>

    <h2>11. Datalekken</h2>
    <p>Servura informeert de Opdrachtgever zo spoedig mogelijk na ontdekking van een datalek en biedt ondersteuning bij meldingsplichten.</p>

    <h2>12. Rechten betrokkenen</h2>
    <p>Servura ondersteunt de Opdrachtgever bij het afhandelen van verzoeken van betrokkenen, voor zover technisch mogelijk.</p>

    <h2>13. Audits</h2>
    <p>De Opdrachtgever kan, na afspraak, een audit uitvoeren of een derde partij inschakelen om naleving te toetsen.</p>

    <h2>14. Einde overeenkomst</h2>
    <p>Na beëindiging verwijdert of retourneert Servura de persoonsgegevens, tenzij een wettelijke bewaarplicht geldt.</p>

    <h2>15. Wissen / retourneren gegevens</h2>
    <p>Servura verstrekt de gegevens in een gangbaar formaat op verzoek van de Opdrachtgever en verwijdert vervolgens de gegevens uit de systemen.</p>
@endsection
