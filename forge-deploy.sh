#!/bin/bash
# =============================================================
#  Deploy Script untuk Laravel API — Algebra Leaderboard
#  Tampal ke Forge: site -> Deployments -> Deploy Script
# =============================================================

cd $FORGE_SITE_PATH

git pull origin $FORGE_SITE_BRANCH

# Pasang dependency PHP (tanpa pakej dev, dioptimumkan).
$FORGE_COMPOSER install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Jalankan migration (cipta jadual 'scores' jika belum ada).
# DB MySQL diuruskan oleh Forge melalui "Connect to database".
php artisan migrate --force

# Cache konfigurasi & laluan untuk prestasi.
php artisan config:cache
php artisan route:cache

# Reload PHP-FPM.
( flock -w 10 9 || exit 1
    echo 'Restarting FPM...'; sudo -S service $FORGE_PHP_FPM reload ) 9>/tmp/fpmlock

echo "✅ Deploy API selesai."
