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
    <p><em>Let op: dit is een concepttekst en nog niet juridisch gecontroleerd.</em></p>

    <h2>1. Wie is Servura?</h2>
    <p>
        {{ config('company.legal_name', '[Juridische bedrijfsnaam nog in te vullen]') }}, handelend onder de naam {{ config('company.trade_name', 'Servura') }},
        gevestigd te {{ config('company.city', '[Plaats]') }}.
    </p>
    <ul>
        <li>Adres: {{ config('company.address', '[Adres]') }}, {{ config('company.postal_code', '[Postcode]') }} {{ config('company.city', '[Plaats]') }}</li>
        <li>KvK-nummer: {{ config('company.kvk_number', '[KvK-nummer]') }}</li>
        <li>Btw-identificatienummer: {{ config('company.vat_number', '[Btw-nummer]') }}</li>
        <li>E-mail: {{ config('company.email', '[E-mail]') }}</li>
        <li>Privacycontact: {{ config('company.privacy_email', config('company.email', '[E-mail]')) }}</li>
    </ul>

    <h2>2. Welke persoonsgegevens verwerken wij?</h2>
    <p>Afhankelijk van de dienst kunnen wij de volgende gegevens verwerken:</p>
    <ul>
        <li>Naam en contactgegevens (e-mailadres, telefoonnummer);</li>
        <li>Bedrijfsgegevens en functie;</li>
        <li>Adresgegevens voor facturering;</li>
        <li>Gegevens over gebruik van onze website en diensten;</li>
        <li>Accountgegevens voor het klantportaal;</li>
        <li>Technische gegevens zoals IP-adres, browser en apparaatinformatie (logbestanden);</li>
        <li>Betaalgegevens via onze betaalprovider.</li>
    </ul>

    <h2>3. Waarvoor verwerken wij persoonsgegevens?</h2>
    <ul>
        <li>Het uitvoeren van overeenkomsten (offertes, projecten, hosting);</li>
        <li>Facturering en betaling;</li>
        <li>Klantcommunicatie en support;</li>
        <li>Beheer van het klantportaal;</li>
        <li>Verbetering van onze website en diensten;</li>
        <li>Naleving van wettelijke verplichtingen.</li>
    </ul>

    <h2>4. Rechtsgronden</h2>
    <p>Wij verwerken persoonsgegevens op basis van:</p>
    <ul>
        <li>uitvoering van de overeenkomst;</li>
        <li>wettelijke verplichting;</li>
        <li>gerechtvaardigd belang, zoals beveiliging en kwaliteitsverbetering;</li>
        <li>toestemming, bijvoorbeeld voor nieuwsbrieven en niet-noodzakelijke cookies.</li>
    </ul>

    <h2>5. Contact- en offerteaanvragen</h2>
    <p>Gegevens uit contact- en offerteformulieren gebruiken wij om uw vraag te beantwoorden en, indien gewenst, een offerte op te stellen. Deze gegevens bewaren wij gedurende
        <strong>{{ config('legal.retention.contact_request') ?? '[bewaartermijn nog in te vullen]' }}</strong>
        na afhandeling van de aanvraag, tenzij u klant wordt.</p>

    <h2>6. Klantaccounts / klantportaal</h2>
    <p>Voor het klantportaal verwerken wij accountgegevens, contactgegevens en gegevens over uw diensten en facturen. Deze gegevens bewaren wij gedurende de looptijd van de overeenkomst en daarna gedurende
        <strong>{{ config('legal.retention.customer_account') ?? '[bewaartermijn nog in te vullen]' }}</strong>
        op basis van wettelijke bewaarplichten.</p>

    <h2>7. Hosting en technisch beheer</h2>
    <p>Voor hosting en technisch beheer verwerken wij technische gegevens zoals logbestanden, domeinnamen en gebruiksdata. Deze gegevens gebruiken wij voor beveiliging, onderhoud en het oplossen van storingen.</p>

    <h2>8. E-mailverkeer</h2>
    <p>Wij gebruiken uw e-mailadres voor communicatie over onze diensten, facturen en technische meldingen. Voor commerciële e-mails vragen wij aparte toestemming.</p>

    <h2>9. Analytics en cookies</h2>
    <p>Wij gebruiken alleen analytische of marketingcookies na uw toestemming. Noodzakelijke cookies zijn altijd actief. Zie onze <a href="{{ route('legal.cookies') }}">Cookieverklaring</a> voor meer informatie.</p>

    <h2>10. Externe dienstverleners / verwerkers</h2>
    <p>Voor bepaalde diensten maken wij gebruik van externe partijen. Een actuele lijst vindt u onderaan deze verklaring.</p>
    @if(count(config('legal.subprocessors', [])) > 0)
        <ul>
            @foreach(config('legal.subprocessors', []) as $subprocessor)
                <li>
                    <strong>{{ $subprocessor['name'] }}</strong> – {{ $subprocessor['purpose'] }}
                    ({{ $subprocessor['country'] }})
                    @if(!empty($subprocessor['privacy_url']))
                        – <a href="{{ $subprocessor['privacy_url'] }}" target="_blank" rel="noopener noreferrer">privacyverklaring</a>
                    @endif
                </li>
            @endforeach
        </ul>
    @else
        <p><em>Er zijn nog geen subprocessors geconfigureerd. Voeg deze toe zodra partijen daadwerkelijk worden ingeschakeld.</em></p>
    @endif

    <h2>11. Eventuele doorgifte buiten de EER</h2>
    <p>Persoonsgegevens worden in principe binnen de Europese Economische Ruimte (EER) verwerkt. Indien gegevens toch buiten de EER worden doorgegeven, zorgen wij voor passende waarborgen.</p>

    <h2>12. Bewaartermijnen</h2>
    <ul>
        <li>Contactaanvragen: {{ config('legal.retention.contact_request') ?? '[nog in te vullen]' }}</li>
        <li>Offerteaanvragen: {{ config('legal.retention.quote_request') ?? '[nog in te vullen]' }}</li>
        <li>Klantaccounts en facturatie: {{ config('legal.retention.customer_account') ?? '[nog in te vullen]' }}</li>
        <li>Analytics: {{ config('legal.retention.analytics') ?? '[nog in te vullen]' }}</li>
    </ul>

    <h2>13. Beveiliging</h2>
    <p>Wij treffen passende technische en organisatorische maatregelen om uw persoonsgegevens te beveiligen, zoals versleutelde verbindingen en toegangsbeheer.</p>

    <h2>14. Rechten van betrokkenen</h2>
    <p>U heeft recht op inzage, rectificatie, verwijdering, beperking van verwerking en dataportabiliteit. Ook kunt u bezwaar maken tegen verwerking op basis van gerechtvaardigd belang.</p>

    <h2>15. Intrekken van toestemming</h2>
    <p>Toestemming voor bijvoorbeeld nieuwsbrieven of cookies kunt u te allen tijde intrekken via het klantportaal, de afmeldlink in e-mails of door contact op te nemen met het privacycontact.</p>

    <h2>16. Klacht indienen bij de Autoriteit Persoonsgegevens</h2>
    <p>U heeft het recht een klacht in te dienen bij de Autoriteit Persoonsgegevens als u van mening bent dat wij niet zorgvuldig met uw gegevens omgaan.</p>

    <h2>17. Wijzigingen in de privacyverklaring</h2>
    <p>Wij kunnen deze privacyverklaring wijzigen. Belangrijke wijzigingen communiceren wij actief.</p>

    <h2>18. Contact</h2>
    <p>Vragen over deze privacyverklaring kunt u sturen aan:</p>
    <p>
        {{ config('company.trade_name', 'Servura') }}<br>
        T.a.v. privacy<br>
        {{ config('company.email', '[E-mail]') }}<br>
        {{ config('company.privacy_email', config('company.email', '[E-mail]')) }}
    </p>
@endsection
