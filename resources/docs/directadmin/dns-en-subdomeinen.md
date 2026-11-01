---
title: DNS en subdomeinen in DirectAdmin
description: Wanneer beheer je DNS in DirectAdmin en hoe maak je een subdomein aan?
order: 3
---

# DNS en subdomeinen in DirectAdmin

## Wanneer beheer je DNS in DirectAdmin?

Je beheert DNS-records in DirectAdmin **alleen als je domein de nameservers van de server gebruikt** (bijvoorbeeld `ns.zxcs.nl`). Staat je domein op andere nameservers, dan beheer je DNS bij die partij.

> In je klantportaal zie je bij **DNS-records** automatisch een melding als beheer via ons niet mogelijk is.

## Een DNS-record aanpassen

1. Ga in DirectAdmin naar **DNS-beheer**.
2. Kies je domein.
3. Pas een bestaand record aan of voeg een nieuw record toe (A, CNAME, MX, TXT, ...).
4. Sla op. Houd rekening met een propagatietijd van enkele minuten tot enkele uren.

## Een subdomein aanmaken

1. Ga in DirectAdmin naar **Subdomeinbeheer**.
2. Vul de naam in, bijvoorbeeld `shop` voor `shop.jouwdomein.nl`.
3. Klik op **Aanmaken**. De map voor het subdomein wordt automatisch aangemaakt.
