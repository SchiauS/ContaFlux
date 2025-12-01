#!/bin/bash
set -e

# Create storage directories if they don't exist
if [ ! -d /app/storage/app/public/ ]; then
	mkdir -p /app/storage/app/public/;
fi

if [ ! -d /app/storage/framework/cache/data/ ]; then
	mkdir -p /app/storage/framework/cache/data/;
fi

if [ ! -d /app/storage/framework/sessions/ ]; then
	mkdir -p /app/storage/framework/sessions/;
fi

if [ ! -d /app/storage/framework/testing/ ]; then
	mkdir -p /app/storage/framework/sessions/;
fi

if [ ! -d /app/storage/framework/views/ ]; then
	mkdir -p /app/storage/framework/views/;
fi

if [ ! -d /app/storage/logs/ ]; then
	mkdir -p /app/storage/logs/;
fi

# Link filesystem paths
/usr/local/bin/php artisan storage:link

# Run database migrations
/usr/local/bin/php artisan migrate --force --no-interaction

# Run Apache webserver
apache2-foreground
