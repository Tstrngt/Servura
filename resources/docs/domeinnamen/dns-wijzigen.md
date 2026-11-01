---
title: Hoe wijzig ik mijn DNS-instellingen?
description: Waar je DNS-records beheert en wat de belangrijkste recordtypes doen.
order: 3
featured: true
last_reviewed: 2026-11-01
keywords: [dns, records, a-record, cname, mx, txt, nameservers, zone]
related:
  - domeinnaam-koppelen
  - domeinnaam-verhuizen
  - e-mail/email-werkt-niet
---

# Hoe wijzig ik mijn DNS-instellingen?

DNS (Domain Name System) vertaalt je domeinnaam naar de server waar je website en e-mail staan.

## Waar beheer ik DNS?

Dat hangt af van je **nameservers** — zie bij **Mijn domeinen** → je domein → **Nameservers**:

- Staat je domein op de **Servura/Openprovider-nameservers**? Dan beheer je de records direct in je klantomgeving onder **DNS-records**.
- Staat je domein op de **nameservers van je webserver** (bijv. de `ns.zxcs`-servers)? Dan beheer je DNS in **DirectAdmin** onder *DNS-beheer*. Het portaal laat dit zien met een melding.
- Staat je domein op **externe nameservers** (bijv. Cloudflare)? Dan beheer je DNS bij die partij.

## De belangrijkste recordtypes

| Type | Waarvoor? |
|---|---|
| **A** | Verwijst je domein naar een IP-adres (je website) |
| **CNAME** | Verwijst een subdomein naar een andere naam (bijv. `www`) |
| **MX** | Bepaalt welke mailserver je e-mail ontvangt |
| **TXT** | O.a. verificaties en spambeveiliging (SPF) |

## Een record toevoegen of wijzigen (in het portaal)

1. Open je domein en klap **DNS-records** open.
2. Klik op **DNS-records beheren** en daarna **+ Nieuw record**, of klik op het potloodje bij een bestaand record.
3. Kies het type, vul naam en waarde in en sla op.

SOA- en NS-records zijn systeemrecords — die kun je niet zelf aanpassen.

## Let op

- DNS-wijzigingen kunnen de werking van je **website én e-mail** beïnvloeden.
- Wijzigingen hebben verwerkingstijd; effect is niet meteen overal zichtbaar.

## Lukt het niet?

Twijfel je welke waarde je moet invullen? Maak een **ticket** aan en vermeld wat je wilt bereiken — wij denken mee.
