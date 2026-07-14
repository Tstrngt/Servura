# Projectvoortgang - Servura

## Statusoverzicht

| Fase | Taak | Status |
|------|------|--------|
| 1 | Serverprovisioning en deploy | bezig |
| 2 | Publieke website | bezig |
| 3 | Authenticatie en klantenportaal | bezig |
| 4 | Ticketsysteem | bezig |
| 5 | Adminportaal, zijbalk en ticketoverzicht | bezig |

## Huidige taak

**Wat**: Frontendbuild herstellen: ontbrekende `axios`-dependency.
**Status**: geblokkeerd — `resources/js/bootstrap.js` importeert `axios`, dat niet in `package.json` staat.
**Volgende stap**: verifieer `axios` met `npm view axios version` op de server, voeg de exacte versie toe en laat CI de build uitvoeren.

## Omgevingsstatus

- Productieserver: `servura-main-eu-one`, applicatie in `/var/www/Servura`.
- PHP-FPM 8.3 draait als `www-data`; Laravel-sessies zijn schrijfbaar onder `storage/framework/sessions`.
- Applicatieconfiguratie: `/var/www/Servura/.env`; databasecredentials: `/etc/servura/db_credentials`.
- Databaseback-ups: `/var/backups/mysql/`; bestaande 8 KB back-ups zijn niet valide voor herstel.

## Blokkades en open vragen

- Geen CI-workflow aanwezig; daardoor is geen enkele fase CI-geverifieerd.
- Axios-versie moet nog via npm op de server worden geverifieerd.
- Bevestig welke CI-provider en branchbescherming gebruikt moeten worden.
- Admin `Diensten` en `Content` hebben nog geen route/controller en staan niet in de zijbalk.

## Hervattingsinstructie

1. Lees dit bestand en `DECISIONS.md`.
2. Controleer `git status --short` en de actuele CI-uitkomst.
3. Maak CI de eerstvolgende afgebakende taak.
4. Werk alleen de taak uit `Huidige taak` af.
5. Werk dit bestand bij en commit het met de code.

## Laatste update

**Datum**: 2026-07-14
**Taak**: Frontendbuild geblokkeerd door ontbrekende Axios-dependency.
**Volgende**: Axios-versie verifiëren met npm op de server.
