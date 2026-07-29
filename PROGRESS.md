# Projectvoortgang - Servura

## Statusoverzicht

| Fase | Taak | Status |
|------|------|--------|
| 1 | Serverprovisioning en deploy | bezig |
| 2 | Publieke website | grotendeels af — hero redesign op main |
| 3 | Authenticatie en klantenportaal | grotendeels af — facturen/offertes zichtbaar voor klant |
| 4 | Ticketsysteem | grotendeels af |
| 5 | Adminportaal en zijbalk | grotendeels af — financiële module (facturen, offertes, transacties, logs) op main |
| 6 | CI / testautomatisering | open |

## Huidige taak

**Wat**: Verbeter de publieke "Over ons"-pagina (`resources/views/about.blade.php`): helderder hero, consistente fonts met homepage, volgorde Wie wij zijn / Team / Werkwijze / Portfolio / CTA, persoonlijke portfolio-links per teamlid, CTA gelijk aan homepage.
**Status**: af — Vite-build lokaal geslaagd; hero gestript tot badge/heading/tekst, Wie-wij-zijn vervangen door floating UI-cards rondom centrale heading + CTAs.
**Volgende stap**: commit + push naar `main`, daarna op de server `git pull` en `npm run build`.

**Open details:**
- Portfolio-links voor Tim, Dirk en Isis zijn placeholders (`#...`).
- Telefoonnummer in CTA is `‹TELEFOON›` (TODO).

## Omgevingsstatus

- Lokale branch: `main`.
- Productieserver: `servura-main-eu-one`, applicatie in `/var/www/Servura`.
- PHP-FPM 8.3 draait als `www-data`; Laravel-sessies zijn schrijfbaar onder `storage/framework/sessions`.
- Applicatieconfiguratie: `/var/www/Servura/.env`; databasecredentials: `/etc/servura/db_credentials`.
- Databaseback-ups: `/var/backups/mysql/`; bestaande 8 KB back-ups zijn niet valide voor herstel.

## Blokkades en open vragen

- Geen CI-workflow aanwezig; daardoor is geen enkele fase CI-geverifieerd.
- Frontendbuild moet nog door CI worden uitgevoerd met `axios@1.18.1`.
- Admin `Diensten` en `Content` hebben nog geen route/controller en staan niet in de zijbalk.
- Bevestig welke CI-provider en branchbescherming gebruikt moeten worden.

## Hervattingsinstructie

1. Lees dit bestand en `DECISIONS.md`.
2. Controleer `git status --short` en de actuele CI-uitkomst.
3. Werk de taak uit `Huidige taak` af.
4. Commit en push elke voltooide code-stap samen met dit bestand.
5. Leg blockers vast na twee opeenvolgende CI-failures op hetzelfde punt.

## Laatste update

**Datum**: 2026-07-29
**Taak**: Merge `Tims-idea` → `main`; financiële module en hero redesign nu op main.
**Volgende**: CI-workflow toevoegen of expliciete vervolgtaak uitvoeren.
