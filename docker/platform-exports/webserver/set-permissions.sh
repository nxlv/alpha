#!/usr/bin/env bash
echo '# Setting permissions on main directories...'
chown -R application:www-data /var/www/html/platform /var/www/html/app /var/www/html/static

echo '# Granting Laravel-specific permissions...'
chgrp -R application /var/www/html/platform/storage /var/www/html/platform/bootstrap/cache
chmod -R ug+rwx /var/www/html/platform/storage /var/www/html/platform/bootstrap/cache

echo '# Granting execute access on Artisan...'
chmod +x /var/www/html/platform/artisan

echo '# Clearing application cache...'
/var/www/html/platform/artisan cache:clear

# echo '# Setting file permissions for individual files...'
# find /var/www/html -type f -exec chmod 644 {} \;
# echo '# Setting file permissions for individual directories...'
# find /var/www/html -type d -exec chmod 755 {} \;

echo '# Done!'

echo '# Restarting Apache...'

exec 'apache2-foreground'