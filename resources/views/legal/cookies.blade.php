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
    <p><em>Let op: dit is een concepttekst en nog niet juridisch gecontroleerd.</em></p>

    <h2>Wat zijn cookies?</h2>
    <p>Cookies zijn kleine tekstbestanden die door uw browser worden opgeslagen. Wij gebruiken cookies om onze website goed te laten werken, gebruik te analyseren en eventueel marketingdoeleinden te ondersteunen.</p>

    <h2>Welke categorieën gebruikt Servura?</h2>
    <ul>
        <li><strong>Noodzakelijk:</strong> altijd actief, nodig voor basisfunctionaliteit en beveiliging.</li>
        <li><strong>Analytisch:</strong> inzicht in het gebruik van de website. Alleen na toestemming.</li>
        <li><strong>Marketing:</strong> gebruikt voor relevante advertenties en campagnes. Alleen na toestemming.</li>
    </ul>

    <h2>Noodzakelijke cookies</h2>
    <p>Deze cookies zijn nodig voor de technische werking van de website en kunnen niet worden uitgeschakeld.</p>

    <h2>Analytische cookies</h2>
    <p>Wij gebruiken analytische cookies alleen als u hiervoor toestemming geeft. Zij helpen ons begrijpen hoe bezoekers de website gebruiken.</p>

    <h2>Marketingcookies</h2>
    <p>Marketingcookies worden alleen geplaatst na expliciete toestemming en worden gebruikt voor gepersonaliseerde marketing.</p>

    <h2>Derde partijen</h2>
    <p>Derde partijen plaatsen cookies alleen na toestemming. Zie de onderstaande tabel voor de huidige cookies.</p>

    <h2>Cookietabel</h2>
    @if(count(config('legal.cookies', [])) > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm border border-slate-200">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="px-4 py-2">Cookie</th>
                        <th class="px-4 py-2">Aanbieder</th>
                        <th class="px-4 py-2">Doel</th>
                        <th class="px-4 py-2">Categorie</th>
                        <th class="px-4 py-2">Duur</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(config('legal.cookies', []) as $cookie)
                        <tr class="border-t border-slate-200">
                            <td class="px-4 py-2 font-medium">{{ $cookie['name'] }}</td>
                            <td class="px-4 py-2">{{ $cookie['provider'] }}</td>
                            <td class="px-4 py-2">{{ $cookie['purpose'] }}</td>
                            <td class="px-4 py-2">{{ $cookie['category'] }}</td>
                            <td class="px-4 py-2">{{ $cookie['duration'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p><em>Er zijn nog geen cookies geconfigureerd.</em></p>
    @endif

    <h2>Bewaartermijnen</h2>
    <p>De bewaartermijn verschilt per cookie. Zie de cookietabel voor concrete termijnen.</p>

    <h2>Cookievoorkeuren wijzigen</h2>
    <p>U kunt uw cookievoorkeuren op elk moment wijzigen via de onderstaande knop.</p>

    <button type="button" @click="openCookieConsent" class="btn btn-primary mt-4">
        Cookievoorkeuren wijzigen
    </button>
@endsection
