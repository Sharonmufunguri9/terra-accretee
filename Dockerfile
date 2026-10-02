FROM php:8.4-apache
WORKDIR /var/www/html

COPY composer.json composer.lock ./

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

RUN composer install --no-dev --no-interaction --prefer-dist --no-progress --no-scripts --no-plugins

COPY . /var/www/html

RUN a2enmod rewrite
EXPOSE 10000
CMD ["sh", "-c", "sed -i \"s/Listen 80/Listen ${PORT:-10000}/\" /etc/apache2/ports.conf && sed -i \"s/<VirtualHost \\*:80>/<VirtualHost *:${PORT:-10000}>/\" /etc/apache2/sites-available/000-default.conf && exec apache2-foreground"]
