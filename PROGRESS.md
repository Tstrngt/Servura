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

**Wat**: Navbar krijgt een glass-effect (semi-transparant + blur) waarbij de kleur van het "Servura"-logo en de nav-links meeveren met de sectie die er onder zit (donker op donkere hero's, licht elders).
**Status**: af — `.site-navbar` in `resources/css/app.css` gebruikt `backdrop-filter: blur` + translucent achtergrond; `resources/js/app.js` heeft een IntersectionObserver die kijkt welke `[data-navbar-theme="dark"]`-sectie zich achter de sticky navbar bevindt en `.is-dark` toggelt. Alle donkere hero/CTA-secties op home, about, contact, services/index en services/show hebben dit attribuut gekregen.
**Volgende stap**: `npm run build` + visueel verifiëren op alle pagina's, committen en pushen.

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
