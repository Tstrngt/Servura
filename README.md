# Servura - Webomgeving voor MKB Websites en Hosting

Professionele webomgeving voor Servura, een Nederlands bedrijf dat mkb-bedrijven helpt hun website en hosting naar een hoger niveau te tillen.

## Projectstructuur

Dit project is opgedeeld in 5 fasen:

1. **Fase 1**: Serverprovisioning en deploy-pipeline
2. **Fase 2**: Publieke website met beheerbare content
3. **Fase 3**: Authenticatie en klantenportaal
4. **Fase 4**: Ticketsysteem
5. **Fase 5**: Volledig adminportaal

## Technische Keuzes

Zie `DECISIONS.md` voor gedetailleerde technische beslissingen en afwegingen.

## Voortgang

De actuele projectstatus wordt bijgehouden in `PROGRESS.md`. Dit document is de enige bron van waarheid over de projectstatus.

## Installatie

### Server Setup

1. Clone de repository op de server:
```bash
git clone <repository-url>
cd servura
```

2. Draai het provisioning script:
```bash
sudo chmod +x provision.sh
sudo ./provision.sh
```

3. Configureer omgevingsvariabelen:
```bash
cp .env.example .env
# Bewerk .env met de juiste waarden
```

4. Deploy de applicatie:
```bash
./deploy.sh
```

### Lokale Ontwikkeling

1. Installeer dependencies:
```bash
composer install
npm install
```

2. Start de development server:
```bash
npm run dev
```

## Beheer

- Database backups: `/var/backups/mysql/`
- Logs: `/var/log/servura/`
- Configuratie: `/etc/servura/`

## Security

- Wachtwoorden gehasht met bcrypt
- HTTPS via Let's Encrypt
- Firewall met UFW
- Rate limiting op auth endpoints
- Dagelijkse security updates

## Licentie

Proprietary - Servura
