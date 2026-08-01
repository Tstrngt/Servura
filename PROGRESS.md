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

**Wat**: Redesign van de publieke `/diensten`-pagina (`resources/views/services/index.blade.php`) geïnspireerd op de home- en over-ons pagina.
**Status**: af — volledige herschrijving met dark hero + Unsplash-achtergrond, guarantees-strip overgenomen van home, service-pakketten als uniforme `rounded-2xl` kaarten met afbeelding/placeholder, proces-sectie in de stijl van over-ons (tekst links, genummerde lijst rechts), CTA overgenomen van over-ons met `cta-grid`, `npm run build` succesvol.
**Volgende stap**: visueel verifiëren in de browser (desktop + mobiel), committen en pushen.

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
**Taak**: Redesign van `/diensten`-pagina (`resources/views/services/index.blade.php`) geïnspireerd op home en over-ons: dark hero, guarantees, pakketkaarten, proces-sectie en CTA in dezelfde stijl; build gecompileerd.
**Volgende**: Visuele verificatie en commit/push naar `Servura/main`.
