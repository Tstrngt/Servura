# Ubuntu 26.04 LTS Installatie Gids

Deze gids is specifiek voor Ubuntu 26.04 LTS en lost de bekende problemen op met het provisioning script.

## 🔧 Problemen met Huidig Script

Het huidige `provision.sh` script heeft een paar problemen op Ubuntu 26.04:
1. **PHP PPA**: Ondrej's PPA kan problemen hebben op Ubuntu 26.04
2. **Node.js 20**: Mogelijk niet beschikbaar via standaard repositories
3. **Package dependencies**: Sommige packages zijn niet beschikbaar of hebben andere namen

## 📋 Vereisten

- Ubuntu 26.04 LTS server met sudo toegang
- Minimaal 2GB RAM, 4GB aanbevolen
- Minimaal 20GB opslagruimte
- Internet verbinding

## 🚀 Stappenplan Installatie

### Stap 1: Server Voorbereiden

```bash
# Update systeem
sudo apt update && sudo apt upgrade -y

# Installeer basis utilities
sudo apt install -y curl wget git unzip zip software-properties-common \
    apt-transport-https ca-certificates gnupg lsb-release \
    htop vim ufw fail2ban logrotate build-essential
```

### Stap 2: PHP Installeren (Automatische Detectie)

Het nieuwe provisioning script detecteert automatisch de beschikbare PHP versie:

```bash
# Gebruik het verbeterde provisioning script
sudo chmod +x provision-ubuntu26-fixed.sh
sudo ./provision-ubuntu26-fixed.sh
```

**Wat dit script doet:**
- Detecteert automatisch de beschikbare PHP versie (8.1, 8.2, of 8.3)
- Installeert de juiste PHP extensions
- Valt terug op Ondrej's PPA indien nodig
- Configureert alles correct voor de gedetecteerde versie

**Handmatige PHP installatie (optioneel):**
```bash
# Controleer beschikbare PHP versies
apt-cache search ^php[0-9]+\.[0-9]+$ | awk '{print $1}'

# Installeer de gevonden versie (vervang 8.2 met de gevonden versie)
sudo apt install -y php8.2 php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring \
    php8.2-curl php8.2-zip php8.2-bcmath php8.2-gd php8.2-intl \
    php8.2-tokenizer php8.2-dom php8.2-pdo php8.2-pdo-mysql \
    php8.2-sqlite3 php8.2-opcache
```

### Stap 3: Web Server en Database

```bash
# Installeer Nginx
sudo apt install -y nginx

# Installeer MySQL Server
sudo apt install -y mysql-server

# Installeer Redis
sudo apt install -y redis-server
```

### Stap 4: Node.js en Composer

```bash
# Installeer Node.js 18 (stabiel voor Ubuntu 26.04)
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Controleer versies
node --version  # Moet v18.x.x zijn
npm --version   # Moet 9.x.x zijn

# Installeer Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer
```

### Stap 5: SSL Certbot

```bash
# Installeer Certbot
sudo apt install -y certbot python3-certbot-nginx
```

### Stap 6: Servura Gebruiker en Directories

```bash
# Maak servura gebruiker aan
sudo useradd -m -s /bin/bash servura
sudo usermod -aG www-data servura

# Maak directories aan
sudo mkdir -p /var/www/Servura
sudo mkdir -p /var/log/servura
sudo mkdir -p /etc/servura
sudo mkdir -p /var/backups/mysql

# Set permissions
sudo chown -R servura:www-data /var/www/Servura
sudo chown -R servura:adm /var/log/servura
sudo chmod -R 755 /var/www/Servura
```

### Stap 7: PHP Configuratie

```bash
# Backup originele PHP configuratie
sudo cp /etc/php/8.3/fpm/php.ini /etc/php/8.3/fpm/php.ini.backup

# PHP configuratie voor productie
sudo tee /etc/php/8.3/fpm/php.ini > /dev/null << 'EOF'
memory_limit = 256M
upload_max_filesize = 10M
post_max_size = 12M
max_execution_time = 300
max_input_vars = 3000
date.timezone = Europe/Amsterdam
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
EOF

# PHP-FPM pool configuratie
sudo tee /etc/php/8.3/fpm/pool.d/servura.conf > /dev/null << 'EOF'
[servura]
user = www-data
group = www-data
listen = /run/php/php8.3-fpm-servura.sock
listen.owner = www-data
listen.group = www-data
listen.mode = 0660
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 500
chdir = /var/www/Servura
php_admin_value[open_basedir] = /var/www/Servura/:/tmp/
php_admin_value[upload_tmp_dir] = /tmp
php_admin_value[session.save_path] = /tmp
EOF
```

### Stap 8: Nginx Configuratie

```bash
# Nginx site configuratie
sudo tee /etc/nginx/sites-available/servura > /dev/null << 'EOF'
server {
    listen 80;
    server_name your-domain.com;  # VERVANG DIT
    root /var/www/Servura/public;
    index index.php index.html index.htm;

    client_max_body_size 10M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.3-fpm-servura.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_param PATH_INFO $fastcgi_path_info;
        fastcgi_read_timeout 300;
    }

    location ~ /\.ht {
        deny all;
    }

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_types text/plain text/css text/xml text/javascript application/javascript application/xml+rss application/json;

    # Static file caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff|woff2|ttf|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
EOF

# Activeer site
sudo ln -sf /etc/nginx/sites-available/servura /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default

# Test configuratie
sudo nginx -t
```

### Stap 9: Database Setup

```bash
# Start en enable MySQL
sudo systemctl start mysql
sudo systemctl enable mysql

# Secure MySQL (non-interactive)
sudo mysql -e "DELETE FROM mysql.user WHERE User='';"
sudo mysql -e "DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');"
sudo mysql -e "DROP DATABASE IF EXISTS test;"
sudo mysql -e "DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';"
sudo mysql -e "FLUSH PRIVILEGES;"

# Maak database en gebruiker
DB_PASSWORD=$(openssl rand -base64 32 | tr -d "=+/" | cut -c1-25)
echo "DB_PASSWORD=$DB_PASSWORD" | sudo tee /etc/servura/db_credentials
sudo chmod 600 /etc/servura/db_credentials

sudo mysql -e "CREATE DATABASE servura CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "CREATE USER 'servura'@'localhost' IDENTIFIED BY '$DB_PASSWORD';"
sudo mysql -e "GRANT ALL PRIVILEGES ON servura.* TO 'servura'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

# Toon database wachtwoord
echo "=================================="
echo "DATABASE WACHTWOORD:"
echo "$DB_PASSWORD"
echo "=================================="
echo "Sla dit wachtwoord op!"
```

### Stap 10: Firewall en Security

```bash
# Configureer UFW firewall
sudo ufw --force reset
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow ssh
sudo ufw allow 'Nginx Full'
sudo ufw --force enable

# Configureer Fail2Ban
sudo systemctl enable fail2ban
sudo systemctl restart fail2ban
```

### Stap 11: Services Starten

```bash
# Start en enable services
sudo systemctl enable php8.3-fpm nginx mysql redis-server
sudo systemctl restart php8.3-fpm nginx redis-server

# Controleer status
sudo systemctl status php8.3-fpm nginx mysql redis-server --no-pager
```

### Stap 12: Clone en Deploy Applicatie

```bash
# Switch naar servura gebruiker
sudo su - servura

# Clone repository
cd /var/www/Servura
git clone https://github.com/Tstrngt/Servura.git .

# Installeer dependencies
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Maak .env bestand aan
cp .env.example .env

# Genereer application key
php artisan key:generate

# Bewerk .env bestand (vervang UW_GEBRUIKERSNAAM met Tstrngt)
nano .env
```

**Belangrijke .env instellingen:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_PASSWORD=HET_WACHTWOORD_VUIT_STAP_9
```

### Stap 13: Database Migraties en Seeding

```bash
# Blijf als servura gebruiker
cd /var/www/Servura

# Draai migraties
php artisan migrate --force

# Draai seeders
php artisan db:seed --force

# Optimaliseer voor productie
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Storage permissions
chmod -R 777 storage bootstrap/cache
```

### Stap 14: Final Setup

```bash
# Terug naar root gebruiker
exit

# Set ownership
sudo chown -R servura:www-data /var/www/Servura

# Restart services
sudo systemctl restart php8.3-fpm nginx

# Test website
curl -I http://localhost
```

### Stap 15: SSL Certificaten

```bash
# Vervang your-domain.com met uw domein
sudo certbot --nginx -d your-domain.com

# Test auto-renewal
sudo certbot renew --dry-run
```

## 🔍 Probleem Oplossen

### PHP Extensions Niet Beschikbaar
```bash
# Controleer beschikbare PHP packages
apt-cache search php8.3

# Installeer missing extensions
sudo apt install -y php8.3-gd php8.3-intl php8.3-bcmath
```

### Composer Issues
```bash
# Update Composer
sudo composer self-update

# Clear composer cache
composer clear-cache
```

### Node.js Versie Problemen
```bash
# Verwijder en installeer opnieuw
sudo apt remove nodejs
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs
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
sudo chown -R servura:www-data /var/www/servura
sudo chmod -R 755 /var/www/servura
sudo chmod -R 777 /var/www/servura/storage
sudo chmod -R 777 /var/www/servura/bootstrap/cache
```

## 📞 Test de Installatie

1. **Website**: `http://your-domain.com`
2. **PHP Info**: `http://your-domain.com/test.php` (tijdelijk bestand maken)
3. **Database Connectie**: Check Laravel error logs

## 🔄 Maintenance

```bash
# Update systeem
sudo apt update && sudo apt upgrade -y

# Backup database
sudo /usr/local/bin/backup-mysql.sh

# Update applicatie
cd /var/www/Servura && sudo -u servura ./deploy.sh
```

Deze gids zou alle bekende problemen met Ubuntu 26.04 moeten oplossen!
