FROM php:latest

RUN apt-get update && apt-get install -y \
    git \
    unzip

WORKDIR /var/www/html
COPY . .

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install

EXPOSE 8080:80

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
