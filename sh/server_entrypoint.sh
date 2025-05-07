#!/bin/bash
set -e

DIR_SERVER="/var/www/spk"

BIN_CONSOLE="${DIR_SERVER}/bin/console"
BIN_PHPUNIT="${DIR_SERVER}/vendor/bin/phpunit"
BIN_PHP="/usr/local/bin/php"
BIN_PHP_FPM="/usr/local/sbin/php-fpm"
BIN_OPENSSL="/usr/bin/openssl"
BIN_SQLITE="/usr/bin/sqlite3"
BIN_PYTHON="/usr/bin/python3"
BIN_INSTALLPY="/usr/bin/install.py"

CONF_PRIVATE_KEY="${DIR_CONFIG}/private.key"
CONF_PUBLIC_KEY="${DIR_CONFIG}/public.key"
CONF_OAUTH_DB="${DIR_CONFIG}/oauth.db"

# Check for DB existence
php $BIN_CONSOLE doctrine:database:create
php $BIN_CONSOLE doctrine:schema:update --dump-sql --force

# Assert ownership over config root
chown -R www-data:www-data $DIR_CONFIG

# Start server
service nginx start
environment=${APP_ENV,,:-prod}
if [ "${environment}" = "test" ]; then
  echo "Starting in test mode...";
  cd $DIR_SERVER;
  APP_ENV=test APP_DEBUG=1 $BIN_PHP $BIN_PHPUNIT;
elif [ "${environment}" = "dev" ]; then
  echo "Starting in development mode...";
  APP_ENV=dev APP_DEBUG=1 $BIN_PHP_FPM;
else
  echo "Warming cache...";
  APP_ENV=prod APP_DEBUG=0 $BIN_PHP $BIN_CONSOLE cache:warmup;
  chown -R www-data:www-data $DIR_SERVER/var/cache;
  chown -R www-data:www-data $DIR_CONFIG;
  echo "Starting in production mode...";
  APP_ENV=prod APP_DEBUG=0 $BIN_PHP_FPM;
fi
