---
title: "Mijn e-mail werkt niet: wat kan ik controleren?"
description: Eenvoudige controles bij problemen met inloggen, ontvangen of versturen.
order: 4
featured: true
last_reviewed: 2026-11-01
keywords: [email werkt niet, geen mail, versturen lukt niet, inloggen mail, probleem]
related:
  - email-instellen-apparaat
  - webmail
  - domeinnamen/dns-wijzigen
---

# Mijn e-mail werkt niet: wat kan ik controleren?

Loop de controles na die bij jouw situatie passen. Werkt niets hiervan, maak dan een **ticket** aan.

## Ik kan niet inloggen

- Gebruik je je **volledige e-mailadres** als gebruikersnaam (bijv. `info@jouwdomein.nl`, niet alleen `info`)?
- Test je wachtwoord eerst via **webmail**. Werkt het daar wel, dan staat er een verkeerde instelling in je mailprogramma.
- Wachtwoord vergeten? Wijzig het in **DirectAdmin** onder **E-mailaccounts**.

## Ik ontvang geen e-mail

- Kijk eerst in je **spammap**.
- Test vanaf een ander adres — komt er helemaal niets aan, dan kan het MX-record of de mailbox opslag een rol spelen.
- Controleer of je **domeinnaam actief** is en niet is verlopen (klantomgeving → **Mijn domeinen**).
- Is je mailbox vol? In DirectAdmin zie je de opslag per account; verhoog het limiet of ruim op.

## Ik kan geen e-mail versturen

- Staat **verificatie voor de uitgaande (SMTP) server** aan in je mailprogramma? Zonder aanmelden bij de uitgaande server wordt versturen vaak geweigerd.
- Gebruik je de juiste uitgaande server en een beveiligde verbinding (SSL/TLS)?
- Krijg je een **foutmelding of bounce** terug? De tekst daarin verklapt vaak de oorzaak — bewaar die voor support.

## Geef dit door aan support

- Je **e-mailadres** en **domeinnaam**
- Of inloggen op **webmail** wel lukt
- De **exacte foutmelding** (of een bounce) en wanneer het probleem begon

> We vragen nooit om je wachtwoord — ook niet via een ticket.
