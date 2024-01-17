# "xdebug-2.9.0" for PHP<=7.4 — "xdebug" (3) for PHP>=8
ARG XDEBUG_VERSION="xdebug-2.6.0"
FROM php:7.2-apache

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
 && php composer-setup.php \
 && php -r "unlink('composer-setup.php');" \
 && mv composer.phar /usr/local/bin/composer

# Install INTL
RUN apt-get -y update \
 && apt-get install -y libicu-dev \
 && docker-php-ext-configure intl \
 && docker-php-ext-install intl

RUN apt-get install -y git


RUN apt-get install make

# Install unzip utility and libs needed by zip PHP extension
RUN apt-get install -y \
 zlib1g-dev \
 libzip-dev \
 unzip
RUN docker-php-ext-install zip

# Install Xdebug
RUN pecl install xdebug-2.9.8 \
 && docker-php-ext-enable xdebug

# Configure Xdebug

RUN echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
# && echo "xdebug.mode=develop,debug, " >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#&& echo "xdebug.remote_autostart=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
 && echo "xdebug.remote_connect_back=on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
 && echo "xdebug.remote_port=9000" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
 && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
 && echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini