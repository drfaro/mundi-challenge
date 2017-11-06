FROM php:7.0-fpm 
RUN docker-php-ext-install pdo_mysql \ 
&& docker-php-ext-install json

RUN ["apt-get", "update"]
RUN ["apt-get", "install", "-y", "vim"]