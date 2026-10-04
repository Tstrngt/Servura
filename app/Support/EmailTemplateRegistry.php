<?php

namespace App\Support;

class EmailTemplateRegistry
{
    public const TEMPLATES = [
        'account-created' => 'Account aangemaakt', 'verify-email' => 'E-mailadres bevestigen',
        'order-placed' => 'Bestelling geplaatst', 'hosting-activated' => 'Hosting geactiveerd',
        'service-suspended' => 'Dienst opgeschort', 'ticket-created' => 'Ticket ontvangen',
        'ticket-replied' => 'Ticketreactie', 'ticket-closed' => 'Ticket gesloten',
        'invoice-ready' => 'Factuur klaar', 'quote-ready' => 'Offerte klaar',
        'payment-confirmed' => 'Betaling ontvangen',
    ];

    public const SUBJECTS = [
        'account-created' => 'Welkom bij {{site_naam}} — bevestig uw e-mailadres', 'verify-email' => 'Bevestig uw e-mailadres',
        'order-placed' => 'Bedankt voor uw bestelling', 'hosting-activated' => 'Uw hostingaccount is actief',
        'service-suspended' => 'Uw dienst is tijdelijk opgeschort', 'ticket-created' => 'We hebben uw aanvraag ontvangen',
        'ticket-replied' => 'Er is gereageerd op uw aanvraag', 'ticket-closed' => 'Uw aanvraag is gesloten',
        'invoice-ready' => 'Uw factuur staat klaar', 'quote-ready' => 'Uw persoonlijke offerte staat klaar',
        'payment-confirmed' => 'Betaling ontvangen — bedankt',
    ];

    public const HTML = [
        'account-created' => '<p>Hallo {{klant_naam}},</p><p>Leuk dat u er bent! Uw account bij {{site_naam}} is aangemaakt met het door u gekozen wachtwoord.</p><p><a class="button" href="{{actie_url}}">E-mailadres bevestigen</a></p><p class="small">Werkt de knop niet? Kopieer deze link in uw browser: {{actie_url}}</p>',
        'verify-email' => '<p>Hallo {{klant_naam}},</p><p>Nog één stapje: bevestig uw e-mailadres om uw account volledig te activeren.</p><p><a class="button" href="{{actie_url}}">E-mailadres bevestigen</a></p><p class="small">Werkt de knop niet? Kopieer deze link in uw browser: {{actie_url}}</p>',
        'order-placed' => '<p>Hallo {{klant_naam}},</p><p>Bedankt voor uw bestelling bij {{site_naam}}! Wij gaan direct voor u aan de slag.</p><p><a class="button" href="{{actie_url}}">Bestelling bekijken</a></p><p>Heeft u vragen? Reageer via het klantportaal, wij denken graag mee.</p>',
        'hosting-activated' => '<p>Hallo {{klant_naam}},</p><p>Goed nieuws: uw hostingpakket <strong>{{dienst_naam}}</strong> is actief!</p><div class="box"><strong>Inloggegevens</strong><br>Domein: {{domein}}<br>Gebruikersnaam: {{gebruikersnaam}}<br>Wachtwoord: {{wachtwoord}}<br>DirectAdmin: {{directadmin_url}}</div><div class="box"><strong>Servergegevens</strong><br>FTP-host: {{ftp_host}}<br>Server-IP: {{server_ip}}<br>Nameserver 1: {{nameserver_1}}<br>Nameserver 2: {{nameserver_2}}<br>Nameserver 3: {{nameserver_3}}</div><p><a class="button" href="{{actie_url}}">Inloggen op DirectAdmin</a></p><p class="small">Bewaar deze gegevens goed en wijzig uw wachtwoord na de eerste keer inloggen.</p>',
        'service-suspended' => '<p>Hallo {{klant_naam}},</p><p>Uw dienst <strong>{{dienst_naam}}</strong> is tijdelijk opgeschort.</p><div class="box"><strong>Reden:</strong> {{reden}}</div><p><a class="button" href="{{actie_url}}">Klantportaal openen</a></p><p>Vragen hierover? Neem gerust contact met ons op.</p>',
        'ticket-created' => '<p>Hallo {{klant_naam}},</p><p>Dank voor uw bericht! We hebben uw aanvraag <strong>{{ticket_nummer}}</strong> in behandeling genomen.</p><div class="box">{{ticket_titel}}</div><p><a class="button" href="{{actie_url}}">Aanvraag bekijken</a></p><p class="small">We reageren zo snel mogelijk — u krijgt een e-mail zodra er een reactie is.</p>',
        'ticket-replied' => '<p>Hallo {{klant_naam}},</p><p>Er is gereageerd op uw aanvraag <strong>{{ticket_nummer}}</strong>.</p><div class="box">{{reactie}}</div><p><a class="button" href="{{actie_url}}">Bekijk en reageer</a></p>',
        'ticket-closed' => '<p>Hallo {{klant_naam}},</p><p>Uw aanvraag <strong>{{ticket_nummer}}</strong> is afgerond. Fijn dat we u hebben kunnen helpen!</p><p><a class="button" href="{{actie_url}}">Aanvraag teruglezen</a></p><p class="small">Toch nog iets nodig? U kunt altijd een nieuwe aanvraag indienen.</p>',
        'invoice-ready' => '<p>Hallo {{klant_naam}},</p><p>Uw factuur <strong>{{factuur_nummer}}</strong> van € {{bedrag}} staat klaar.</p><p><a class="button" href="{{actie_url}}">Factuur bekijken en betalen</a></p><p class="small">De factuur vindt u ook als bijlage bij deze e-mail.</p>',
        'quote-ready' => '<p>Hallo {{klant_naam}},</p><p>Goed nieuws: uw persoonlijke offerte <strong>{{offerte_nummer}}</strong> van € {{bedrag}} staat klaar!</p><p><a class="button" href="{{actie_url}}">Offerte bekijken</a></p><p class="small">Heeft u vragen of aanpassingen nodig? Laat het ons weten, wij denken graag mee.</p>',
        'payment-confirmed' => '<p>Hallo {{klant_naam}},</p><p>Bedankt! Wij hebben uw betaling van € {{bedrag}} voor factuur <strong>{{factuur_nummer}}</strong> in goede orde ontvangen.</p><p><a class="button" href="{{actie_url}}">Betaalde factuur bekijken</a></p>',
    ];

    public const VARIABLES = [
        '{{klant_naam}}', '{{klant_email}}', '{{ticket_nummer}}', '{{ticket_titel}}',
        '{{reactie}}', '{{factuur_nummer}}', '{{offerte_nummer}}', '{{bedrag}}',
        '{{dienst_naam}}', '{{domein}}', '{{gebruikersnaam}}', '{{wachtwoord}}',
        '{{directadmin_url}}', '{{ftp_host}}', '{{nameserver_1}}', '{{nameserver_2}}',
        '{{nameserver_3}}', '{{server_ip}}',
        '{{reden}}', '{{actie_url}}', '{{site_naam}}',
    ];
}
