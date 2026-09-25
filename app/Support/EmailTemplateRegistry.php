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

    public const VARIABLES = [
        '{{klant_naam}}', '{{klant_email}}', '{{ticket_nummer}}', '{{ticket_titel}}',
        '{{reactie}}', '{{factuur_nummer}}', '{{offerte_nummer}}', '{{bedrag}}',
        '{{dienst_naam}}', '{{domein}}', '{{gebruikersnaam}}', '{{wachtwoord}}',
        '{{reden}}', '{{actie_url}}', '{{site_naam}}',
    ];
}
