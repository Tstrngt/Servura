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

**Wat**: Iteratie op de publieke `/diensten`-pagina (`resources/views/services/index.blade.php`): hero teruggebracht tot titel + subtekst, productrij horizontaal scrollbaar gemaakt met database-gekoppelde webdesign-pakketten, modaal toegevoegd dat bij 'Bekijk product' alle details toont met contact-CTA.
**Status**: af — hero is nu basic, garanties-section verwijderd, producten staan in een snap-scroll rij (`service_type = website_pakket`), modaal gebruikt Alpine.js en toont titel, omschrijving, lange beschrijving, features, prijs en 'Neem contact op'-knop, `npm run build` succesvol.
**Volgende stap**: visueel verifiëren in de browser (desktop + mobiel, test modaal), committen en pushen.

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

**Datum**: 2026-08-01
**Taak**: Iteratie op `/diensten`-pagina (`resources/views/services/index.blade.php`): basic hero, horizontaal scrollbare webdesign-productrij uit database, modaal met productdetails en contact-CTA; build gecompileerd.
**Volgende**: Visuele verificatie en commit/push naar `Servura/main`.
