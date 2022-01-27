FROM php:7.2-apache
# útil para fazer redirecionamento no .htaccess
RUN a2enmod rewrite 
# install e setup pdo database
RUN docker-php-ext-install pdo pdo_mysql
RUN apt-get update \
    && apt-get install -y libzip-dev \
    && apt-get install -y zlib1g-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install zip