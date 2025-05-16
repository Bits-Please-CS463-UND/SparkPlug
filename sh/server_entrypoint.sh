#!/bin/bash
set -e

DIR_SERVER="/var/www/html"

BIN_CONSOLE="${DIR_SERVER}/bin/console"
BIN_PHPUNIT="${DIR_SERVER}/vendor/bin/phpunit"
BIN_PHP="/usr/local/bin/php"
BIN_PHP_FPM="/usr/local/sbin/php-fpm"
BIN_OPENSSL="/usr/bin/openssl"
BIN_SQLITE="/usr/bin/sqlite3"

export APP_DEBUG=0
export APP_ENV=prod
export DEFAULT_URL='localhost'

# Check for DB existence
php $BIN_CONSOLE doctrine:schema:update --dump-sql --force;
chown www-data:www-data $DIR_SERVER/var/data.sqlite;

# Start server
service nginx start
echo "Warming cache...";
$BIN_PHP $BIN_CONSOLE cache:warmup;
chown -R www-data:www-data $DIR_SERVER/var;
echo "Starting in production mode...";
cd /var/www/html;
$BIN_PHP_FPM;
