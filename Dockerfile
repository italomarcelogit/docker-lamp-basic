FROM php:apache
# útil para fazer redirecionamento no .htaccess
RUN a2enmod rewrite 
# atualizando pod
RUN docker-php-ext-install pdo pdo_mysql
RUN apt-get update \
    && apt-get install -y libzip-dev \
    && apt-get install -y zlib1g-dev \
    && apt-get install -y vim \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install zip

COPY ./www /var/www/html