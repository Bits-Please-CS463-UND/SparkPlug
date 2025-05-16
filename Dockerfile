# syntax=docker/dockerfile:1-labs
FROM php:8.3-fpm AS private_install_pkgs
RUN apt-get update
RUN apt-get install libgmp-dev git unzip socat sqlite3 nginx libsqlite3-dev libicu-dev -y
RUN docker-php-ext-configure intl
RUN docker-php-ext-install gmp intl pdo_sqlite

FROM private_install_pkgs AS private_spk_skeleton
RUN <<EOF
mkdir -p /var/run/html
chown -R www-data:www-data /var/run/html
mkdir -p /var/www/html
chown -R www-data:www-data /var/www
cat <<DONE > /usr/local/etc/php-fpm.d/zz-docker.conf
[global]
daemonize = no

[www]
user = www-data
group = www-data
listen = /var/run/html/php8.3-fpm.sock
listen.mode = 0666
DONE
EOF

FROM private_spk_skeleton AS private_buildenv_node
USER www-data
RUN <<EOF
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.1/install.sh | bash;
export NVM_DIR="$HOME/.nvm";
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh";
nvm install node;
nvm install-latest-npm;
EOF
USER root

FROM private_buildenv_node AS private_src_assets
COPY --parents --chown=www-data:www-data  \
    ./assets                    \
    ./public                    \
    package.json                \
    package-lock.json           \
    webpack.config.js           \
    tsconfig.json               \
    /var/www/html/

FROM private_src_assets AS private_src_assets_compiled
WORKDIR /var/www/html
USER www-data
RUN <<EOF
export NVM_DIR="$HOME/.nvm";
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh";
nvm use node;
npm install;
npm run build;
EOF
USER root

FROM private_spk_skeleton AS private_buildenv_composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV PATH="$PATH:/usr/local/bin"

FROM private_buildenv_composer AS private_src_php
COPY --parents --chown=www-data:www-data \
    bin                        \
    config                     \
    public                     \
    src                        \
    templates                  \
    composer.json              \
    composer.lock              \
    /var/www/html/

FROM private_src_php AS private_src_and_vendor
WORKDIR /var/www/html
USER www-data
RUN <<EOF
touch /var/www/html/.env;
DATABASE_DSN="doctrine://default" composer install --optimize-autoloader;
EOF
USER root

FROM private_spk_skeleton AS private_all_src
COPY --from=private_src_and_vendor /var/www/html /var/www/html
COPY --from=private_src_assets_compiled /var/www/html/public/build /var/www/html/public/build

FROM private_all_src AS private_copy_config
COPY --chmod=755 sh/server_entrypoint.sh /entrypoint.sh
COPY container /

FROM private_all_src AS sparkplug
HEALTHCHECK CMD socat -u OPEN:/dev/null UNIX-CONNECT:/var/run/html/php8.3-fpm.sock;
COPY default /etc/nginx/sites-enabled/default
COPY ./sh/server_entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]
USER root