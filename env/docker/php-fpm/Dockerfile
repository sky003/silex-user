FROM php:7.1-fpm

ENV SERVICE_ROOT=/usr/share/nginx/service

RUN extDeps="git libpq-dev zlib1g-dev libicu-dev unzip"; \
	apt-get update \
	&& apt-get install -y $extDeps --no-install-recommends \
	&& docker-php-ext-install -j$(nproc) \
		pdo_pgsql \
		intl

RUN pecl install redis \
	&& pecl install xdebug \
	&& docker-php-ext-enable redis xdebug
RUN echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini 

RUN curl -o /tmp/composer-setup.php https://getcomposer.org/installer \
    && curl -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \
    && php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { unlink('/tmp/composer-setup.php'); echo 'Invalid installer' . PHP_EOL; exit(1); }" \
    && php /tmp/composer-setup.php \
    && mv composer.phar /usr/local/bin/composer

WORKDIR $SERVICE_ROOT

CMD ["php-fpm"]