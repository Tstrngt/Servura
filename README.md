# Servura - Webomgeving voor MKB Websites en Hosting

[![License](https://img.shields.io/badge/License-Proprietary-red.svg)](LICENSE)
[![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20.svg)](https://laravel.com/)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4.svg)](https://www.php.net/)

Professionele webomgeving voor Servura, een Nederlands bedrijf dat mkb-bedrijven helpt hun website en hosting naar een hoger niveau te tillen.

## 📁 Repository

**Repository URL**: `https://github.com/Tstrngt/Servura`

*Vervang `UW_GEBRUIKERSNAAM` met uw daadwerkelijke GitHub gebruikersnaam.*

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

## 🚀 Quick Start

1. **Clone de repository**:
   ```bash
   git clone https://github.com/Tstrngt/Servura.git
   cd Servura
   ```

2. **Installeer dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Configureer omgeving**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   # Bewerk .env met database credentials
   ```

4. **Database setup**:
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Start de applicatie**:
   ```bash
   npm run dev
   php artisan serve
   ```

6. **Login met test accounts**:
   - Admin: `admin@servura.nl` / `password`
   - Klant: `jan@bakkerijdegoudenkoren.nl` / `password`

## 📋 Installatie

### 🎯 Ubuntu 24.04 LTS - Aanbevolen

**Ubuntu 24.04 LTS is de stabiele en aanbevolen keuze voor productie servers.**

1. **Clone de repository op de server:**
```bash
git clone https://github.com/Tstrngt/Servura.git
cd Servura
```

2. **Draai het provisioning script:**
```bash
sudo chmod +x provision-ubuntu24.sh
sudo ./provision-ubuntu24.sh
```

3. **Deploy de applicatie:**
```bash
# Als servura gebruiker
sudo su - servura
cd /var/www/Servura
git clone https://github.com/Tstrngt/Servura.git .
composer install --no-dev --optimize-autoloader
php artisan key:generate
cp .env.example .env
nano .env  # Configureer met database wachtwoord
php artisan migrate --force
php artisan db:seed --force
```

**Gedetailleerde instructies:** Zie `INSTALLATION_UBUNTU24.md`

---

### ⚠️ Ubuntu 26.04 LTS - Experimenteel

Ubuntu 26.04 is experimenteel en kan problemen hebben. Gebruik alleen als u specifiek deze versie nodig heeft:

```bash
sudo chmod +x provision-ubuntu26-no-ppa.sh
sudo ./provision-ubuntu26-no-ppa.sh
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
php artisan serve
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
