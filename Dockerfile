FROM php:latest

RUN apt-get update
RUN apt-get install -y git unzip
RUN apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html
COPY . .

RUN composer install

EXPOSE 8081:80

CMD ["sh", "-c", "php artisan migrate && php artisan serve --host=0.0.0.0 --port=80"]
