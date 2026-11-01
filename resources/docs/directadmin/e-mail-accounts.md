---
title: E-mailaccounts aanmaken
description: Maak en beheer e-mailadressen op je eigen domein in DirectAdmin.
order: 2
---

# E-mailaccounts aanmaken

Je maakt eenvoudig zakelijke e-mailadressen aan op je eigen domein, bijvoorbeeld `info@jouwdomein.nl`.

## Nieuw e-mailaccount aanmaken

1. Log in op **DirectAdmin** (via je klantportaal of direct).
2. Ga naar **E-mailbeheer** → **E-mailaccounts**.
3. Klik op **Account aanmaken**.
4. Vul de gewenste naam in (bijv. `info`), kies een sterk wachtwoord en stel eventueel een quotum in.
5. Klik op **Aanmaken**.

## E-mail instellen op je apparaten

Gebruik deze gegevens in je mailprogramma (Outlook, Apple Mail, telefoon):

| Instelling | Waarde |
|---|---|
| Inkomend (IMAP) | `mail.jouwdomein.nl`, poort `993` (SSL) |
| Inkomend (POP3) | `mail.jouwdomein.nl`, poort `995` (SSL) |
| Uitgaand (SMTP) | `mail.jouwdomein.nl`, poort `465` of `587` (SSL) |
| Gebruikersnaam | je volledige e-mailadres |

> **Tip:** gebruik altijd IMAP zodat je e-mail op al je apparaten gesynchroniseerd blijft.

## Webmail

Geen zin om een mailprogramma in te stellen? Je kunt ook via de browser mailen via de webmail van DirectAdmin.
