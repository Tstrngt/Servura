#!/bin/bash

# Servura Server Provisioning Script
# Ubuntu 26.04 LTS - Complete server setup

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Logging
log() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')] $1${NC}"
}

warn() {
    echo -e "${YELLOW}[$(date +'%Y-%m-%d %H:%M:%S')] WARNING: $1${NC}"
}

error() {
    echo -e "${RED}[$(date +'%Y-%m-%d %H:%M:%S')] ERROR: $1${NC}"
    exit 1
}

# Check if running as root
if [[ $EUID -ne 0 ]]; then
   error "This script must be run as root (use sudo)"
fi

log "Starting Servura server provisioning..."

# Update system
log "Updating system packages..."
apt update && apt upgrade -y

# Install basic utilities
log "Installing basic utilities..."
apt install -y curl wget git unzip zip software-properties-common \
    apt-transport-https ca-certificates gnupg lsb-release \
    htop vim ufw fail2ban logrotate

# Add PHP repository
log "Adding PHP repository..."
add-apt-repository ppa:ondrej/php -y
apt update

# Install PHP 8.3 and extensions
log "Installing PHP 8.3 and extensions..."
apt install -y php8.3 php8.3-fpm php8.3-mysql php8.3-xml php8.3-mbstring \
    php8.3-curl php8.3-zip php8.3-bcmath php8.3-gd php8.3-intl \
    php8.3-tokenizer php8.3-dom php8.3-pdo php8.3-pdo-mysql \
    php8.3-redis php8.3-opcache php8.3-imagick

# Install Nginx
log "Installing Nginx..."
apt install -y nginx

# Install MySQL
log "Installing MySQL Server..."
apt install -y mysql-server

# Install Redis
log "Installing Redis..."
apt install -y redis-server

# Install Composer
log "Installing Composer..."
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# Install Node.js and npm
log "Installing Node.js..."
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs

# Install Certbot for SSL
log "Installing Certbot..."
apt install -y certbot python3-certbot-nginx

# Create servura user
log "Creating servura user..."
if ! id "servura" &>/dev/null; then
    useradd -m -s /bin/bash servura
    usermod -aG www-data servura
fi

# Setup directories
log "Creating application directories..."
mkdir -p /var/www/servura
mkdir -p /var/log/servura
mkdir -p /etc/servura
mkdir -p /var/backups/mysql

# Set permissions
chown -R servura:www-data /var/www/servura
chown -R servura:adm /var/log/servura
chmod -R 755 /var/www/servura

# Configure PHP-FPM
log "Configuring PHP-FPM..."
cp /etc/php/8.3/fpm/php.ini /etc/php/8.3/fpm/php.ini.backup

# PHP optimizations for production
cat > /etc/php/8.3/fpm/php.ini << 'EOF'
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

# Configure PHP-FPM pool
cat > /etc/php/8.3/fpm/pool.d/servura.conf << 'EOF'
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
chdir = /var/www/servura
php_admin_value[open_basedir] = /var/www/servura/:/tmp/
php_admin_value[upload_tmp_dir] = /tmp
php_admin_value[session.save_path] = /tmp
EOF

# Configure Nginx
log "Configuring Nginx..."
cat > /etc/nginx/sites-available/servura << 'EOF'
server {
    listen 80;
    server_name _;
    root /var/www/servura/public;
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

# Enable site
ln -sf /etc/nginx/sites-available/servura /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

# Test Nginx configuration
nginx -t

# Configure MySQL
log "Configuring MySQL..."
systemctl start mysql
systemctl enable mysql

# Secure MySQL installation (non-interactive)
mysql -e "DELETE FROM mysql.user WHERE User='';"
mysql -e "DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');"
mysql -e "DROP DATABASE IF EXISTS test;"
mysql -e "DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';"
mysql -e "FLUSH PRIVILEGES;"

# Create database and user
log "Creating Servura database..."
DB_PASSWORD=$(openssl rand -base64 32)
echo "DB_PASSWORD=$DB_PASSWORD" > /etc/servura/db_credentials
chmod 600 /etc/servura/db_credentials

mysql -e "CREATE DATABASE servura CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -e "CREATE USER 'servura'@'localhost' IDENTIFIED BY '$DB_PASSWORD';"
mysql -e "GRANT ALL PRIVILEGES ON servura.* TO 'servura'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"

# Configure Redis
log "Configuring Redis..."
sed -i 's/supervised no/supervised systemd/' /etc/redis/redis.conf
systemctl restart redis-server

# Configure Firewall
log "Configuring UFW firewall..."
ufw --force reset
ufw default deny incoming
ufw default allow outgoing
ufw allow ssh
ufw allow 'Nginx Full'
ufw --force enable

# Configure Fail2Ban
log "Configuring Fail2Ban..."
cat > /etc/fail2ban/jail.local << 'EOF'
[DEFAULT]
bantime = 3600
findtime = 600
maxretry = 3
destemail = root@localhost
sender = root@localhost
mta = sendmail

[sshd]
enabled = true
port = ssh
logpath = /var/log/auth.log
maxretry = 3

[nginx-http-auth]
enabled = true
port = http,https
logpath = /var/log/nginx/error.log

[nginx-limit-req]
enabled = true
port = http,https
logpath = /var/log/nginx/error.log

[php-url-fopen]
enabled = true
port = http,https
logpath = /var/log/nginx/error.log
EOF

systemctl restart fail2ban

# Setup automatic security updates
log "Configuring automatic security updates..."
apt install -y unattended-upgrades
cat > /etc/apt/apt.conf.d/50unattended-upgrades << 'EOF'
Unattended-Upgrade::Allowed-Origins {
    "${distro_id}:${distro_codename}";
    "${distro_id}:${distro_codename}-security";
    "${distro_id}ESMApps:${distro_codename}-apps-security";
    "${distro_id}ESM:${distro_codename}-infra-security";
};
Unattended-Upgrade::Automatic-Reboot "false";
Unattended-Upgrade::Remove-Unused-Dependencies "true";
EOF

cat > /etc/apt/apt.conf.d/20auto-upgrades << 'EOF'
APT::Periodic::Update-Package-Lists "1";
APT::Periodic::Download-Upgradeable-Packages "1";
APT::Periodic::AutocleanInterval "7";
APT::Periodic::Unattended-Upgrade "1";
EOF

# Setup database backup script
log "Setting up database backup script..."
cat > /usr/local/bin/backup-mysql.sh << 'EOF'
#!/bin/bash

set -euo pipefail
umask 077

BACKUP_DIR="/var/backups/mysql"
DB_NAME="servura"
DB_USER="servura"
DB_PASSWORD=$(cut -d'=' -f2 /etc/servura/db_credentials)
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="$BACKUP_DIR/servura_backup_$DATE.sql.gz"
TEMP_BACKUP_FILE=$(mktemp "$BACKUP_DIR/.servura_backup_${DATE}_XXXXXX.sql.gz")

trap 'rm -f "$TEMP_BACKUP_FILE"' EXIT

MYSQL_PWD="$DB_PASSWORD" mysqldump --no-tablespaces --single-transaction --routines --events --triggers -u"$DB_USER" "$DB_NAME" | gzip > "$TEMP_BACKUP_FILE"
gzip -t "$TEMP_BACKUP_FILE"
mv "$TEMP_BACKUP_FILE" "$BACKUP_FILE"
trap - EXIT

find "$BACKUP_DIR" -name "servura_backup_*.sql.gz" -mtime +14 -delete
chmod 600 "$BACKUP_FILE"
chown root:root "$BACKUP_FILE"

echo "Backup completed: $BACKUP_FILE"
EOF

chmod +x /usr/local/bin/backup-mysql.sh

# Add to crontab for daily backups at 2 AM
(crontab -l 2>/dev/null; echo "0 2 * * * /usr/local/bin/backup-mysql.sh") | crontab -

# Setup log rotation
log "Configuring log rotation..."
cat > /etc/logrotate.d/servura << 'EOF'
/var/log/servura/*.log {
    daily
    missingok
    rotate 30
    compress
    delaycompress
    notifempty
    create 644 servura adm
    postrotate
        systemctl reload php8.3-fpm
    endscript
}
EOF

# Create environment file template
log "Creating environment file template..."
cat > /var/www/servura/.env.example << 'EOF'
APP_NAME=Servura
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://your-domain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=servura
DB_USERNAME=servura
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=redis
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=redis
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@servura.nl
MAIL_FROM_NAME="${APP_NAME}"
EOF

chown servura:www-data /var/www/servura/.env.example

# Start and enable services
log "Starting and enabling services..."
systemctl enable php8.3-fpm
systemctl enable nginx
systemctl enable mysql
systemctl enable redis-server
systemctl enable fail2ban

systemctl restart php8.3-fpm
systemctl restart nginx
systemctl restart redis-server

# Create deployment script
log "Creating deployment script..."
cat > /var/www/servura/deploy.sh << 'EOF'
#!/bin/bash

# Servura Deployment Script

set -e

log() {
    echo "[$(date +'%Y-%m-%d %H:%M:%S')] $1"
}

log "Starting deployment..."

# Navigate to project directory
cd /var/www/servura

# Pull latest changes
if [ -d ".git" ]; then
    log "Pulling latest changes from Git..."
    git pull origin main
else
    log "Initializing Git repository..."
    # This should be replaced with actual clone command
    log "Please clone the repository first"
    exit 1
fi

# Install dependencies
log "Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

log "Installing Node dependencies..."
npm install
npm run build

# Run database migrations
log "Running database migrations..."
php artisan migrate --force

# Clear caches
log "Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
log "Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
log "Setting permissions..."
chown -R servura:www-data /var/www/servura
chmod -R 755 /var/www/servura
chmod -R 777 /var/www/servura/storage
chmod -R 777 /var/www/servura/bootstrap/cache

# Restart services
log "Restarting services..."
systemctl restart php8.3-fpm
systemctl restart nginx

log "Deployment completed successfully!"
EOF

chmod +x /var/www/servura/deploy.sh
chown servura:www-data /var/www/servura/deploy.sh

# Display completion message
log "Server provisioning completed!"
echo ""
echo "=================================="
echo "IMPORTANT INFORMATION:"
echo "=================================="
echo "Database password saved to: /etc/servura/db_credentials"
echo "Web root: /var/www/servura"
echo "Deployment script: /var/www/servura/deploy.sh"
echo ""
echo "NEXT STEPS:"
echo "1. Configure your domain name in Nginx config"
echo "2. Set up SSL with: certbot --nginx -d your-domain.com"
echo "3. Copy .env.example to .env and configure"
echo "4. Deploy your application with: ./deploy.sh"
echo ""
echo "Services running:"
systemctl is-active nginx php8.3-fpm mysql redis-server fail2ban
echo ""
echo "Firewall status:"
ufw status
