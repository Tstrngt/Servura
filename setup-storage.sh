#!/bin/bash

# Servura Storage Setup Script
# Creates necessary Laravel storage directories

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

echo "Storage directories created successfully!"
echo "For production, use: sudo chown -R www-data:www-data storage && chmod -R 755 storage && chmod -R 777 storage/framework storage/app/public storage/logs"
