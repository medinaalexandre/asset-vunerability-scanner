FROM phpswoole/swoole:6.1.0-php8.3

WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN docker-php-ext-install pcntl

RUN \
    set -ex && \
    pecl channel-update pecl.php.net && \
    pecl install xdebug-stable

RUN XDEBUG_SO_PATH=$(find /usr/local/lib/php/extensions/ -name xdebug.so) && \
    echo "zend_extension=${XDEBUG_SO_PATH}" >> /usr/local/etc/php/php.ini && \
    echo "xdebug.mode=debug,develop,coverage" >> /usr/local/etc/php/php.ini && \
    echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/php.ini && \
    echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/php.ini && \
    echo "xdebug.client_port=9003" >> /usr/local/etc/php/php.ini

RUN groupadd -g 1000 appuser && \
    useradd -u 1000 -g 1000 -m -s /bin/bash appuser || true

CMD ["php", "artisan", "octane:start", "--server=swoole", "--host=0.0.0.0", "--port=8000"]

EXPOSE 8000
