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
        'account-created' => '<p>Hallo {{klant_naam}},</p><p>Welkom bij {{site_naam}}. Uw account is aangemaakt met het door u gekozen wachtwoord.</p><p><a href="{{actie_url}}">E-mailadres bevestigen</a></p>',
        'verify-email' => '<p>Hallo {{klant_naam}},</p><p>Bevestig uw e-mailadres om uw account volledig te activeren.</p><p><a href="{{actie_url}}">E-mailadres bevestigen</a></p>',
        'order-placed' => '<p>Hallo {{klant_naam}},</p><p>Bedankt voor uw bestelling. Wij gaan ermee aan de slag.</p><p><a href="{{actie_url}}">Bestelling bekijken</a></p>',
        'hosting-activated' => '<p>Hallo {{klant_naam}},</p><p>Uw hostingpakket <strong>{{dienst_naam}}</strong> is actief.</p><p>Domein: {{domein}}<br>Gebruikersnaam: {{gebruikersnaam}}<br>Wachtwoord: {{wachtwoord}}</p><p><a href="{{actie_url}}">Inloggen op DirectAdmin</a></p>',
        'service-suspended' => '<p>Hallo {{klant_naam}},</p><p>Uw dienst <strong>{{dienst_naam}}</strong> is opgeschort.</p><p>Reden: {{reden}}</p><p><a href="{{actie_url}}">Klantportaal openen</a></p>',
        'ticket-created' => '<p>Hallo {{klant_naam}},</p><p>We hebben uw aanvraag <strong>{{ticket_nummer}}</strong> ontvangen.</p><p>{{ticket_titel}}</p><p><a href="{{actie_url}}">Aanvraag bekijken</a></p>',
        'ticket-replied' => '<p>Hallo {{klant_naam}},</p><p>Er is gereageerd op aanvraag <strong>{{ticket_nummer}}</strong>.</p><blockquote>{{reactie}}</blockquote><p><a href="{{actie_url}}">Bekijk en reageer</a></p>',
        'ticket-closed' => '<p>Hallo {{klant_naam}},</p><p>Uw aanvraag <strong>{{ticket_nummer}}</strong> is gesloten.</p><p><a href="{{actie_url}}">Aanvraag bekijken</a></p>',
        'invoice-ready' => '<p>Hallo {{klant_naam}},</p><p>Factuur <strong>{{factuur_nummer}}</strong> van € {{bedrag}} staat klaar.</p><p><a href="{{actie_url}}">Factuur bekijken en betalen</a></p>',
        'quote-ready' => '<p>Hallo {{klant_naam}},</p><p>Uw persoonlijke offerte <strong>{{offerte_nummer}}</strong> van € {{bedrag}} staat klaar.</p><p><a href="{{actie_url}}">Offerte bekijken</a></p>',
        'payment-confirmed' => '<p>Hallo {{klant_naam}},</p><p>Bedankt. Wij hebben uw betaling van € {{bedrag}} voor factuur <strong>{{factuur_nummer}}</strong> ontvangen.</p><p><a href="{{actie_url}}">Betaalde factuur bekijken</a></p>',
    ];

    public const VARIABLES = [
        '{{klant_naam}}', '{{klant_email}}', '{{ticket_nummer}}', '{{ticket_titel}}',
        '{{reactie}}', '{{factuur_nummer}}', '{{offerte_nummer}}', '{{bedrag}}',
        '{{dienst_naam}}', '{{domein}}', '{{gebruikersnaam}}', '{{wachtwoord}}',
        '{{reden}}', '{{actie_url}}', '{{site_naam}}',
    ];
}
