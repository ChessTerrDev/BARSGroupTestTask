ARG ALPINE_VERSION=3.16
FROM alpine:${ALPINE_VERSION}

# Setup document root
WORKDIR /var/www/html

# Install packages and remove default server definition
RUN apk add --no-cache \
  curl \
  nginx \
  php81 \
  php81-ctype \
  php81-curl \
  php81-dom \
  php81-fpm \
  php81-gd \
  php81-intl \
  php81-mbstring \
  php81-mysqli \
  php81-opcache \
  php81-openssl \
  php81-phar \
  php81-session \
  php81-xml \
  php81-xmlreader \
  php81-xmlwriter \
  php81-tokenizer \
  php81-zlib \
  supervisor

# Create symlink so programs depending on `php` still function
RUN ln -s /usr/bin/php81 /usr/bin/php

# Installing composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN rm -rf composer-setup.php

# Configure nginx
COPY .docker/nginx.conf /etc/nginx/nginx.conf

# Configure PHP-FPM
COPY .docker/fpm-pool.conf /etc/php81/php-fpm.d/www.conf
COPY .docker/php.ini /etc/php81/conf.d/custom.ini

# Configure supervisord
COPY .docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Make sure files/folders needed by the processes are accessable when they run under the nobody user
RUN chown -R nobody.nobody /var/www/html /run /var/lib/nginx /var/log/nginx /usr/local/bin/

# Switch to use a non-root user from here on
USER nobody

# Add application
COPY --chown=nobody ./ /var/www/html/

RUN composer install

RUN chmod 777 /var/www/html/data/lpu.json

# Expose the port nginx is reachable on
#EXPOSE 8080

# Let supervisord start nginx & php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Configure a healthcheck to validate that everything is up&running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:80/fpm-ping