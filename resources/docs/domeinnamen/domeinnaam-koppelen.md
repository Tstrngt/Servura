---
title: Hoe koppel ik mijn domeinnaam aan mijn website?
description: Zo werkt de koppeling tussen je domeinnaam en je hostingpakket.
order: 1
featured: true
last_reviewed: 2026-11-01
keywords: [domein, koppelen, website, hosting, nameservers]
related:
  - dns-wijzigen
  - domeinnaam-verhuizen
  - aan-de-slag/website-en-email-beheren
---

# Hoe koppel ik mijn domeinnaam aan mijn website?

Na deze uitleg weet je hoe je domein en hosting aan elkaar worden gekoppeld en hoe je controleert dat het werkt.

## Domeinnaam bij Servura

Bestel je een domeinnaam samen met een hostingpakket, dan wordt de koppeling **automatisch** gelegd: het domein krijgt de nameservers van je webserver. In je klantomgeving zie je bij **Mijn domeinen** → je domein → **Hosting** welk pakket gekoppeld is.

Is de koppeling niet automatisch gelegd, dan doe je dit handmatig:

1. Ga naar **Mijn domeinen** en open je domein.
2. Open het blok **Hosting** en klik op **Bestaand hostingpakket koppelen**.
3. Kies je hostingpakket en bevestig.

## Domeinnaam bij een andere aanbieder

Heeft je domein elders geregistreerd staan? Dan kun je:

- de domeinnaam **verhuizen** naar Servura (zie [Domeinnaam verhuizen](domeinnaam-verhuizen)), of
- de domeinnaam bij je huidige aanbieder laten staan en daar de **nameservers of DNS-records** aanpassen zodat het domein naar je Servura-hosting wijst. Vraag via een ticket welke instellingen je daarvoor nodig hebt.

## Controleren of het werkt

- Open je domeinnaam in de browser. Werkt het, dan is de koppeling actief.
- Nameserver- en DNS-wijzigingen hebben **verwerkingstijd** — dit kan enige uren duren voordat het overal werkt.

## Lukt het niet?

- Controleer bij **Mijn domeinen** welke nameservers actief zijn (blok **Nameservers**).
- Maak een **ticket** aan met je domeinnaam; wij controleren de koppeling mee.
