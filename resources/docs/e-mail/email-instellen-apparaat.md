---
title: Hoe stel ik mijn e-mail in op mijn telefoon of computer?
description: De instellingen die je nodig hebt voor Outlook, Apple Mail en je telefoon.
order: 3
featured: true
draft: true
keywords: [email instellen, outlook, iphone, android, imap, pop3, smtp, poort, mail app, telefoon]
related:
  - emailadres-aanmaken
  - webmail
  - email-werkt-niet
---

# Hoe stel ik mijn e-mail in op mijn telefoon of computer?

> **Let op:** dit artikel is concept. De exacte servernamen en poorten zijn afhankelijk van de server waarop jouw pakket staat. Je vindt de juiste gegevens in de activatiemail van je hostingpakket of via een ticket.

Na deze uitleg weet je welke gegevens je nodig hebt om je e-mail in een mailprogramma te zetten.

## Wat heb je nodig?

- Je **e-mailadres** en **mailbox-wachtwoord**
- De **serverinstellingen** van je hostingpakket — die staan in de activatiemail of vragen wij voor je op

## Welke instellingen heb je nodig?

Elk mailprogramma vraagt om dezelfde vijf gegevens:

| Instelling | Wat vul je in? |
|---|---|
| Gebruikersnaam | Je **volledige e-mailadres** |
| Wachtwoord | Het wachtwoord van je mailbox |
| Inkomende server (IMAP) | De mailserver uit je instellingsgegevens, met SSL/TLS |
| Uitgaande server (SMTP) | Dezelfde mailserver, met SSL/TLS en **verificatie aan** |
| Accounttype | Kies **IMAP** (houdt je mail op alle apparaten gelijk) |

> **IMAP of POP3?** Kies IMAP. Met POP3 haalt je apparaat mail van de server weg; met IMAP blijft alles gesynchroniseerd tussen telefoon, computer en webmail.

## Inkomend versus uitgaand

- **Inkomend** haalt je mail op: dat is IMAP (of POP3).
- **Uitgaand** verstuurt je mail: dat is SMTP. Zet **verificatie/authenticatie** voor uitgaand altijd aan — gebruikersnaam en wachtwoord zijn hetzelfde als bij inkomend.

## Belangrijkste apps

- **iPhone/iPad**: Instellingen → Mail → Accounts → Account toevoegen → Overig → Mailaccount toevoegen.
- **Android (Gmail/Outlook-app)**: Account toevoegen → Overig/IMAP, vul de gegevens handmatig in.
- **Outlook (computer)**: Bestand → Account toevoegen → geavanceerde opties → handmatig instellen → IMAP.
- **Apple Mail (Mac)**: Mail → Account toevoegen → Andere mailaccount → vul de gegevens in.

## Lukt het niet?

- Controleer dat **verificatie voor uitgaande mail** aan staat — dat is de meest gemaakte fout.
- Komt er geen mail binnen maar versturen lukt wel (of andersom)? Controleer per deel (inkomend/uitgaand) de servernaam, poort en beveiliging.
- Zie [Mijn e-mail werkt niet](email-werkt-niet) of maak een **ticket** aan — wij zoeken de juiste instellingen voor je uit.
