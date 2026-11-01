---
title: Nameservers en DNS-records
description: Welke nameservers gebruikt je domein en waar beheer je je DNS-records?
order: 1
---

# Nameservers en DNS-records

De nameservers bepalen waar de DNS-zone van je domein wordt beheerd.

## Welke nameservers zijn er?

| Situatie | Nameservers | DNS beheren via |
|---|---|---|
| Domein met hosting bij Servura | Server-nameservers, bijv. `ns.zxcs.nl` | DirectAdmin |
| Domein op Servura/Openprovider DNS | `ns*.openprovider.nl / .be / .eu` | Klantportaal → DNS-records |
| Externe DNS (Cloudflare, etc.) | Nameservers van die partij | Bij die partij |

## Nameservers wijzigen

1. Ga in het klantportaal naar **Mijn domeinen** en open je domein.
2. Open het blok **Nameservers** en klik op **Nameservers wijzigen**.
3. Vul minimaal twee nameservers in en sla op.
4. De wijziging wordt bij de registrar verwerkt; dit kan tot 24 uur duren.

> Bestel je een hostingpakket **samen met een domein**? Dan worden de nameservers automatisch op die van je webserver gezet.

## DNS-records beheren in het portaal

Onder **DNS-records** bij je domein zie en bewerk je de DNS-zone, maar alleen als het domein op de Servura/Openprovider-nameservers staat. Bij externe nameservers tonen we een melding dat beheer via dit portaal niet mogelijk is.

SOA- en NS-records zijn systeemrecords en kunnen niet worden aangepast.
