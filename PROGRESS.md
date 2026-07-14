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

**Wat**: Dashboardacties: statistiekkaarten en bestaande overzichtslinks laten navigeren.
**Status**: bezig — alleen dashboardacties vallen binnen deze stap.
**Volgende stap**: koppel kaarten aan bestaande klanten- en ticketfilters; daarna CI-build uitvoeren.

## Omgevingsstatus

- Productieserver: `servura-main-eu-one`, applicatie in `/var/www/Servura`.
- PHP-FPM 8.3 draait als `www-data`; Laravel-sessies zijn schrijfbaar onder `storage/framework/sessions`.
- Applicatieconfiguratie: `/var/www/Servura/.env`; databasecredentials: `/etc/servura/db_credentials`.
- Databaseback-ups: `/var/backups/mysql/`; bestaande 8 KB back-ups zijn niet valide voor herstel.

## Blokkades en open vragen

- Geen CI-workflow aanwezig; daardoor is geen enkele fase CI-geverifieerd.
- Frontendbuild moet nog door CI worden uitgevoerd met `axios@1.18.1`.
- Dashboardacties moeten nog via CI worden geverifieerd.
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
**Taak**: Dashboardacties geselecteerd als afgebakende huidige taak.
**Volgende**: bestaande klanten- en ticketroutes koppelen aan dashboardacties.
