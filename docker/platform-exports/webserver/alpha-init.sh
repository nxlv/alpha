#!/usr/bin/env bash
echo '# Setting permissions on main directories...'
sudo chown -R web:www-data /var/www/platform /var/www/app /var/www/static

echo '# Granting Laravel-specific permissions...'
sudo chgrp -R web /var/www/platform/storage /var/www/platform/bootstrap/cache
sudo chmod -R ug+rwx /var/www/platform/storage /var/www/platform/bootstrap/cache

echo '# Granting execute access on Artisan...'
sudo chmod +x /var/www/platform/artisan

echo '# Clearing application cache...'
sudo /var/www/platform/artisan cache:clear

echo '* Regenerating autoload cache...'
cd /var/www/platform
composer dump-autoload

echo '* Starting CANNEX data update...'
/var/www/platform/artisan cannex:update

echo '# Done!'

apache2-foreground
