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
# Ga naar parent directory en maak schone installatie
cd /var/www
sudo rm -rf Servura
sudo -u servura git clone https://github.com/Tstrngt/Servura.git Servura

# Ga naar de Servura directory
cd /var/www/Servura

# Maak missende directories aan
sudo mkdir -p bootstrap/cache

# Zet juiste permissions
sudo chown -R servura:www-data /var/www/Servura
sudo chmod -R 755 /var/www/Servura
sudo chmod -R 777 /var/www/Servura/storage
sudo chmod -R 777 /var/www/Servura/bootstrap/cache

# Installeer dependencies
sudo -u servura composer install --no-dev --optimize-autoloader

# Genereer application key
sudo -u servura php artisan key:generate

# Configureer .env bestand
sudo -u servura cp .env.example .env
sudo -u servura nano .env  # Gebruik database wachtwoord van provisioning script

# Draai database migraties en seeders
sudo -u servura php artisan migrate --force
sudo -u servura php artisan db:seed --force

# Optimaliseer voor productie
sudo -u servura php artisan cache:clear
sudo -u servura php artisan config:clear
sudo -u servura php artisan route:clear
sudo -u servura php artisan view:clear
sudo -u servura php artisan config:cache
sudo -u servura php artisan route:cache
sudo -u servura php artisan view:cache

# Restart services
sudo systemctl restart php8.3-fpm nginx
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
