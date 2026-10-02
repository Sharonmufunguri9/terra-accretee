FROM php:8.4-apache
WORKDIR /var/www/html

COPY composer.json ./

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

RUN composer install --no-interaction --prefer-dist --no-progress --no-scripts --no-plugins

COPY . /var/www/html

RUN a2enmod rewrite
EXPOSE 80
CMD ["apache2-foreground"]
