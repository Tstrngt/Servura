#!/bin/bash

# Servura Storage Setup Script
# Creates necessary Laravel storage directories and cache table

echo "Setting up Laravel storage directories..."

# Create framework directories
mkdir -p storage/framework/cache
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/testing
mkdir -p storage/framework/views

# Create other necessary directories
mkdir -p storage/app/public
mkdir -p storage/logs

# Set permissions (for development, production permissions should be set differently)
chmod -R 777 storage/framework
chmod -R 777 storage/app/public
chmod -R 777 storage/logs

# Create cache table if it doesn't exist
echo "Creating cache table..."
mysql -u root -p$(cat /etc/servura/db_credentials | cut -d'=' -f2) servura << 'EOF' 2>/dev/null || mysql servura << 'EOF'
CREATE TABLE IF NOT EXISTS cache (
    `key` varchar(255) not null primary key,
    value longtext not null,
    expiration int not null
) engine=InnoDB charset=utf8mb4 collate=utf8mb4_unicode_ci;
EOF

echo "Storage directories and cache table created successfully!"
echo "For production, use: sudo chown -R www-data:www-data storage && chmod -R 755 storage && chmod -R 777 storage/framework storage/app/public storage/logs"
