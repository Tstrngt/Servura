# Ubuntu 24.04 LTS Installatie Gids - Servura

Deze gids is specifiek voor Ubuntu 24.04 LTS en zorgt voor een probleemloze installatie van de Servura webapplicatie.

## 🎯 Overzicht

Ubuntu 24.04 LTS is de stabiele keuze voor productie servers met:
- PHP 8.3 in standaard repositories
- Volledige ondersteuning tot 2029
- Uitstekende package compatibiliteit
- Geen PPA's nodig

## 📋 Vereisten

- Ubuntu 24.04 LTS server met sudo toegang
- Minimaal 2GB RAM, 4GB aanbevolen
- Minimaal 20GB opslagruimte
- Internet verbinding
- Domeinnaam (optioneel, voor SSL)

## 🚀 Complete Installatie (Stap voor Stap)

### Stap 1: Server Voorbereiden

```bash
# Update systeem
sudo apt update && sudo apt upgrade -y

# Installeer basis utilities
sudo apt install -y curl wget git unzip zip software-properties-common \
    apt-transport-https ca-certificates gnupg lsb-release \
    htop vim nano ufw fail2ban logrotate build-essential
```

### Stap 2: Clone Repository

```bash
# Clone de Servura repository
git clone https://github.com/Tstrngt/Servura.git
cd Servura

# Maak het provisioning script uitvoerbaar
chmod +x provision-ubuntu24.sh
```

### Stap 3: Draai het Provisioning Script

```bash
# Draai het complete provisioning script
sudo ./provision-ubuntu24.sh
```

**Dit script installeert automatisch:**
- ✅ PHP 8.3 met alle benodigde extensions
- ✅ Nginx webserver met optimalisatie
- ✅ MySQL database met beveiliging
- ✅ Redis cache server
- ✅ Node.js 20 LTS
- ✅ Composer package manager
- ✅ SSL certbot
- ✅ Firewall (UFW) configuratie
- ✅ Fail2ban beveiliging
- ✅ Automatische backups
- ✅ Log rotation
- ✅ Servura gebruiker en directories

### Stap 4: Configureer Domein (Optioneel)

```bash
# Bewerk Nginx configuratie
sudo nano /etc/nginx/sites-available/servura

# Vervang 'server_name _;' met uw domein:
server_name uw-domein.com www.uw-domein.com;

# Test en herstart Nginx
sudo nginx -t
sudo systemctl restart nginx
```

### Stap 5: SSL Certificaat Installeren

```bash
# Installeer SSL certificaat met Let's Encrypt
sudo certbot --nginx -d uw-domein.com -d www.uw-domein.com

# Test auto-renewal
sudo certbot renew --dry-run
```

### Stap 6: Deploy de Servura Applicatie

```bash
# Switch naar servura gebruiker
sudo su - servura

# Clone de repository in de web directory
cd /var/www/Servura
git clone https://github.com/Tstrngt/Servura.git .

# Installeer PHP dependencies
composer install --no-dev --optimize-autoloader

# Installeer Node dependencies en build assets
npm install
npm run build

# Maak .env bestand aan
cp .env.example .env

# Genereer application key
php artisan key:generate

# Bewerk .env bestand
nano .env
```

**Belangrijke .env instellingen:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://uw-domein.com

DB_PASSWORD=HET_WACHTWOORD_VAN_HET_PROVISIONING_SCRIPT
```

### Stap 7: Database Setup

```bash
# Draai database migraties
php artisan migrate --force

# Draai seeders voor testdata
php artisan db:seed --force

# Optimaliseer voor productie
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Stap 8: Permissions en Services

```bash
# Terug naar root gebruiker
exit

# Set juiste permissions
sudo chown -R servura:www-data /var/www/Servura
sudo chmod -R 755 /var/www/Servura
sudo chmod -R 777 /var/www/Servura/storage
sudo chmod -R 777 /var/www/Servura/bootstrap/cache

# Restart services
sudo systemctl restart php8.3-fpm nginx
```

### Stap 9: Test de Installatie

```bash
# Test website response
curl -I http://localhost

# Test HTTPS response
curl -I https://uw-domein.com

# Controleer service status
sudo systemctl status nginx php8.3-fpm mysql redis-server --no-pager
```

## 🔧 Handmatige Installatie (Optioneel)

Als u liever stap voor stap alles handmatig installeert:

### PHP 8.3 Installatie

```bash
# Installeer PHP 8.3 en extensions
sudo apt install -y php8.3 php8.3-fpm php8.3-mysql php8.3-xml php8.3-mbstring \
    php8.3-curl php8.3-zip php8.3-bcmath php8.3-gd php8.3-intl \
    php8.3-tokenizer php8.3-dom php8.3-pdo php8.3-pdo-mysql \
    php8.3-sqlite3 php8.3-opcache php8.3-redis php8.3-imagick
```

### Database Setup

```bash
# Start MySQL
sudo systemctl start mysql
sudo systemctl enable mysql

# Beveilig MySQL
sudo mysql_secure_installation

# Maak database en gebruiker
sudo mysql -e "CREATE DATABASE servura CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "CREATE USER 'servura'@'localhost' IDENTIFIED BY 'uw_wachtwoord';"
sudo mysql -e "GRANT ALL PRIVILEGES ON servura.* TO 'servura'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"
```

### Nginx Configuratie

```bash
# Maak site configuratie
sudo nano /etc/nginx/sites-available/servura
```

Plak de Nginx configuratie uit het provisioning script.

## 📊 Test Accounts

Na installatie kunt u inloggen met:

- **Admin**: `admin@servura.nl` / `password`
- **Klant**: `jan@bakkerijdegoudenkoren.nl` / `password`
- **Medewerker**: `medewerker@servura.nl` / `password`

## 🔍 Probleem Oplossen

### PHP Extensions Niet Beschikbaar

```bash
# Controleer geïnstalleerde PHP packages
dpkg -l | grep php8.3

# Installeer missende extensions
sudo apt install -y php8.3-gd php8.3-intl php8.3-bcmath
```

### Database Connectie Problemen

```bash
# Test database connectie
mysql -u servura -p servura

# Controleer MySQL service
sudo systemctl status mysql
```

### Permissions Problemen

```bash
# Fix storage permissions
sudo chown -R servura:www-data /var/www/Servura
sudo chmod -R 755 /var/www/Servura
sudo chmod -R 777 /var/www/Servura/storage
sudo chmod -R 777 /var/www/Servura/bootstrap/cache
```

### Composer Issues

```bash
# Update Composer
sudo composer self-update

# Clear composer cache
composer clear-cache

# Install dependencies zonder dev packages
composer install --no-dev --optimize-autoloader
```

## 🔄 Maintenance

### Updates

```bash
# Update systeem
sudo apt update && sudo apt upgrade -y

# Update applicatie
cd /var/www/Servura
sudo -u servura git pull origin main
sudo -u servura composer install --no-dev --optimize-autoloader
sudo -u servura npm install && npm run build
sudo -u servura php artisan migrate --force
```

### Backups

```bash
# Handmatige backup
sudo /usr/local/bin/backup-mysql.sh

# Backup locatie
ls -la /var/backups/mysql/
```

### Logs Bekijken

```bash
# Nginx logs
sudo tail -f /var/log/nginx/error.log
sudo tail -f /var/log/nginx/access.log

# PHP logs
sudo tail -f /var/log/php8.3-fpm.log

# Servura logs
sudo tail -f /var/log/servura/laravel.log

# MySQL logs
sudo tail -f /var/log/mysql/error.log
```

## 🛡️ Security

### Firewall Status

```bash
# Controleer firewall status
sudo ufw status verbose

# Firewall regels
sudo ufw show added
```

### Fail2Ban Status

```bash
# Controleer Fail2Ban status
sudo fail2ban-client status

# Bekijk gebande IP's
sudo fail2ban-client status sshd
```

### SSL Certificaat

```bash
# Controleer SSL certificaat
sudo certbot certificates

# Vernieuw certificaat handmatig
sudo certbot renew
```

## 📈 Performance Optimalisatie

### PHP Configuratie

```bash
# Bewerk PHP configuratie
sudo nano /etc/php/8.3/fpm/php.ini

# Belangrijke instellingen:
memory_limit = 256M
upload_max_filesize = 10M
post_max_size = 12M
max_execution_time = 300
opcache.memory_consumption=128
```

### MySQL Optimalisatie

```bash
# Bewerk MySQL configuratie
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf

# Voeg toe onder [mysqld]:
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
```

## 🎉 Succes!

Uw Servura webapplicatie staat nu volledig operationeel op Ubuntu 24.04 LTS! 

**Volgende stappen:**
1. Configureer uw domeinnaam
2. Stel email in voor notificaties
3. Maak admin accounts aan voor uw team
4. Monitor de server performance

Voor vragen of support, raadpleeg de Laravel documentatie of neem contact op met het Servura team.
