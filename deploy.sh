#!/bin/bash

# Servura Local Development Deployment Script
# For testing deployment pipeline before server deployment

set -e

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

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

log "Starting Servura deployment..."

# Check if composer is available
if ! command -v composer &> /dev/null; then
    error "Composer is not installed or not in PATH"
fi

# Check if npm is available
if ! command -v npm &> /dev/null; then
    error "npm is not installed or not in PATH"
fi

# Install PHP dependencies
log "Installing PHP dependencies..."
if [ ! -d "vendor" ]; then
    composer install --no-dev --optimize-autoloader
else
    log "Vendor directory exists, skipping composer install"
fi

# Install Node dependencies and build
log "Installing Node dependencies..."
if [ ! -d "node_modules" ]; then
    npm install
fi

log "Building frontend assets..."
npm run build

# Create necessary directories if they don't exist
log "Creating necessary directories..."
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/testing
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set permissions (Unix/Linux systems)
if [[ "$OSTYPE" == "linux-gnu"* ]] || [[ "$OSTYPE" == "darwin"* ]]; then
    log "Setting file permissions..."
    chmod -R 755 storage bootstrap/cache
    chmod -R 777 storage/framework/cache storage/framework/sessions storage/framework/testing storage/framework/views storage/logs bootstrap/cache
fi

# Check if .env exists
if [ ! -f ".env" ]; then
    warn ".env file not found, copying from .env.example"
    if [ -f ".env.example" ]; then
        cp .env.example .env
        warn "Please configure your .env file with appropriate settings"
    else
        error ".env.example file not found"
    fi
fi

# Generate application key if not set
if ! grep -q "APP_KEY=" .env || grep -q "APP_KEY=$" .env; then
    log "Generating application key..."
    if [ -d "vendor" ]; then
        php artisan key:generate --force
    else
        warn "Cannot generate key - vendor directory not available"
    fi
fi

# Run database migrations if artisan is available
if [ -f "artisan" ] && [ -d "vendor" ]; then
    log "Running database migrations..."
    php artisan migrate --force || warn "Migration failed - this is expected if database is not configured"
    
    # Clear caches
    log "Clearing caches..."
    php artisan cache:clear || true
    php artisan config:clear || true
    php artisan route:clear || true
    php artisan view:clear || true
    
    # Optimize for production
    log "Optimizing for production..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

log "Deployment completed successfully!"
echo ""
echo "=================================="
echo "DEPLOYMENT SUMMARY:"
echo "=================================="
echo "✓ PHP dependencies installed"
echo "✓ Frontend assets built"
echo "✓ Directories created"
echo "✓ Permissions set"
echo "✓ Application configured"
echo ""
echo "NEXT STEPS:"
echo "1. Configure your .env file with database credentials"
echo "2. Run 'php artisan migrate' to set up database tables"
echo "3. Run 'php artisan serve' to start development server"
echo "4. Visit http://localhost:8000 to test"
echo ""
echo "FOR PRODUCTION DEPLOYMENT:"
echo "1. Upload files to server"
echo "2. Run 'sudo ./provision.sh' on the server"
echo "3. Configure domain and SSL"
echo "4. Run './deploy.sh' in /var/www/servura"
