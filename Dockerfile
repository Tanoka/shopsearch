FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    unzip
RUN docker-php-ext-install zip

COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN composer self-update

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

RUN apt -y update
RUN apt -y install git

RUN pecl install xdebug-3.2.1 \
	&& docker-php-ext-enable xdebug

WORKDIR /var/www/mytheresa
COPY . /var/www/mytheresa

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-interaction 

RUN cp phpunit.xml.dist phpunit.xml

# Database created and populated
RUN php bin/console doctrine:database:create
RUN php bin/console doctrine:migrations:migrate
RUN php bin/console --env=test --no-interaction doctrine:fixtures:load --group=basic

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
