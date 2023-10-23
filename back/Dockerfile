FROM php:8.0.30-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git

RUN apt-get update && apt-get install -y supervisor

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY . .

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN composer install

EXPOSE 8000 6001

CMD ["supervisord", "-n"]



