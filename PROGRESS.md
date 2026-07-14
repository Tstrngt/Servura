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
**Status**: bezig — `axios@1.18.1` is met `npm view axios version` op de server geverifieerd en in `package.json` vastgelegd; CI-build ontbreekt.
**Volgende stap**: push deze commit, voer de CI-build uit en leg de pass/fail-uitkomst vast.

## Omgevingsstatus

- Productieserver: `servura-main-eu-one`, applicatie in `/var/www/Servura`.
- PHP-FPM 8.3 draait als `www-data`; Laravel-sessies zijn schrijfbaar onder `storage/framework/sessions`.
- Applicatieconfiguratie: `/var/www/Servura/.env`; databasecredentials: `/etc/servura/db_credentials`.
- Databaseback-ups: `/var/backups/mysql/`; bestaande 8 KB back-ups zijn niet valide voor herstel.

## Blokkades en open vragen

- Geen CI-workflow aanwezig; daardoor is geen enkele fase CI-geverifieerd.
- Frontendbuild moet nog door CI worden uitgevoerd met `axios@1.18.1`.
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
**Taak**: `axios@1.18.1` toegevoegd na npm-verificatie op de server.
**Volgende**: CI-build uitvoeren en uitkomst vastleggen.
