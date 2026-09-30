@extends('legal.layout')

@section('title', 'Cookieverklaring - '.config('company.trade_name', 'Servura'))
@section('meta-description', 'Cookieverklaring van '.config('company.trade_name', 'Servura').'.')
@section('meta-keywords', 'cookies, cookieverklaring, Servura')

@section('legal-title')
    Cookieverklaring
@endsection

@section('legal-meta')
    Versie {{ config('legal.versions.cookies.version', '1.0') }} – laatst gewijzigd op {{ config('legal.versions.cookies.effective_date', '[datum nog in te vullen]') }}
@endsection

@section('legal-content')
    <p><em>Let op: deze cookieverklaring moet voor publicatie worden aangepast aan de daadwerkelijke cookies en technologieën die op de website worden gebruikt.</em></p>

    <h2>1. Wat zijn cookies?</h2>
    <ul>
        <li>Cookies en vergelijkbare technieken zijn kleine gegevensbestanden die bij een bezoek aan een website op uw apparaat kunnen worden opgeslagen of uitgelezen.</li>
    </ul>

    <h2>2. Welke soorten cookies gebruiken wij?</h2>
    <ul>
        <li>Servura kan gebruikmaken van:
            <ul>
                <li><strong>Noodzakelijke cookies</strong> — Deze cookies zijn noodzakelijk om de website technisch goed en veilig te laten functioneren. Voor deze cookies is geen toestemming vereist voor zover zij strikt noodzakelijk zijn voor de door de bezoeker gevraagde dienst.</li>
                <li><strong>Beperkt analytische cookies</strong> — Wij kunnen privacyvriendelijke analytische cookies gebruiken om te begrijpen hoe de website wordt gebruikt. Voor zover dergelijke cookies slechts geringe gevolgen voor de persoonlijke levenssfeer hebben en aan de wettelijke voorwaarden voldoen, kan toestemming niet noodzakelijk zijn.</li>
                <li><strong>Overige analytische en marketingcookies</strong> — Cookies waarmee bezoekers uitgebreider worden gevolgd, profielen worden opgebouwd of informatie voor marketingdoeleinden wordt gebruikt, worden uitsluitend geplaatst nadat daarvoor geldige toestemming is verkregen, voor zover de wet dit vereist.</li>
            </ul>
        </li>
    </ul>

    <h2>3. Cookies die momenteel worden gebruikt</h2>
    <p>De onderstaande tabel moet overeenkomen met de daadwerkelijke technische configuratie van de website.</p>

    @if(count(config('legal.cookies', [])) > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm border border-slate-200">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="px-4 py-2">Cookie</th>
                        <th class="px-4 py-2">Doel</th>
                        <th class="px-4 py-2">Type</th>
                        <th class="px-4 py-2">Bewaartermijn</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(config('legal.cookies', []) as $cookie)
                        <tr class="border-t border-slate-200">
                            <td class="px-4 py-2 font-medium">{{ $cookie['name'] }}</td>
                            <td class="px-4 py-2">{{ $cookie['purpose'] }}</td>
                            <td class="px-4 py-2">{{ $cookie['category'] }}</td>
                            <td class="px-4 py-2">{{ $cookie['duration'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm border border-slate-200">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="px-4 py-2">Cookie</th>
                        <th class="px-4 py-2">Doel</th>
                        <th class="px-4 py-2">Type</th>
                        <th class="px-4 py-2">Bewaartermijn</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t border-slate-200">
                        <td class="px-4 py-2 font-medium">XSRF-TOKEN</td>
                        <td class="px-4 py-2">Bescherming tegen misbruik van webformulieren en verzoeken</td>
                        <td class="px-4 py-2">Noodzakelijk</td>
                        <td class="px-4 py-2">[werkelijke termijn]</td>
                    </tr>
                    <tr class="border-t border-slate-200">
                        <td class="px-4 py-2 font-medium">servura_session</td>
                        <td class="px-4 py-2">In stand houden van de technische gebruikerssessie</td>
                        <td class="px-4 py-2">Noodzakelijk</td>
                        <td class="px-4 py-2">[werkelijke termijn]</td>
                    </tr>
                    <tr class="border-t border-slate-200">
                        <td class="px-4 py-2 font-medium">cookie_consent</td>
                        <td class="px-4 py-2">Opslaan van de gemaakte cookievoorkeur</td>
                        <td class="px-4 py-2">Noodzakelijk</td>
                        <td class="px-4 py-2">[werkelijke termijn]</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="mt-2 text-sm text-slate-500"><em>Indien analytics-, marketing-, embedded media- of andere cookies worden toegevoegd, moet deze tabel vóór gebruik worden aangevuld.</em></p>
    @endif

    <h2>4. Toestemming</h2>
    <ul>
        <li>Wanneer toestemming vereist is:
            <ul>
                <li>worden de betreffende cookies niet geplaatst voordat toestemming is gegeven;</li>
                <li>is toestemming een actieve keuze;</li>
                <li>worden niet-noodzakelijke categorieën niet vooraf aangevinkt;</li>
                <li>kan toestemming later weer worden ingetrokken;</li>
                <li>leidt weigering van niet-noodzakelijke cookies niet tot het onmogelijk maken van normaal gebruik van de basiswebsite, tenzij daarvoor een geldige wettelijke grond bestaat.</li>
            </ul>
        </li>
    </ul>

    <h2>5. Cookievoorkeuren wijzigen</h2>
    <ul>
        <li>U kunt uw voorkeuren aanpassen via de cookie-instellingen op de website.</li>
        <li>Het intrekken van toestemming heeft geen terugwerkende kracht.</li>
    </ul>

    <h2>6. Cookies verwijderen</h2>
    <ul>
        <li>U kunt cookies daarnaast via uw browser verwijderen of blokkeren. Hierdoor kunnen bepaalde onderdelen van de website minder goed functioneren.</li>
    </ul>

    <h2>7. Diensten van derden</h2>
    <ul>
        <li>Indien de website externe content bevat, bijvoorbeeld video's, kaarten, lettertypen of andere embeds, kunnen externe aanbieders cookies of vergelijkbare technieken gebruiken.</li>
        <li>Niet-noodzakelijke externe diensten worden, waar vereist, pas geladen nadat toestemming is gegeven.</li>
    </ul>

    <h2>8. Wijzigingen</h2>
    <ul>
        <li>Servura kan deze cookieverklaring wijzigen wanneer de website of gebruikte technologie verandert.</li>
        <li>De actuele versie is beschikbaar via <a href="https://servura.nl/cookies" target="_blank" rel="noopener noreferrer">https://servura.nl/cookies</a>.</li>
    </ul>

    <button type="button" @click="openCookieConsent" class="btn btn-primary mt-6">
        Cookievoorkeuren wijzigen
    </button>
@endsection
