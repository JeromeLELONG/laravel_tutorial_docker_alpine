FROM php:5.6-apache

RUN apt-get -yq update
RUN apt-get install --force-yes -y git
RUN apt-get install -q -y ssmtp mailutils
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        libldap2-dev \
    libicu-dev \
     libxml2-dev \
    vim \
        wget \
        unzip \
        git \
    && docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/  \
    && docker-php-ext-install -j$(nproc) iconv intl xml soap mcrypt opcache pdo pdo_mysql mysqli mbstring ldap \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

RUN a2enmod rewrite && mkdir /composer-setup && wget https://getcomposer.org/installer -P /composer-setup && php /composer-setup/installer --install-dir=/usr/bin && rm -Rf /composer-setup

RUN apt-get install -y openssl ssl-cert net-tools
WORKDIR /var/www/html/applications/siscolidentifiant/
COPY ./php/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY ./php/default-ssl.conf /etc/apache2/sites-available/default-ssl.conf
COPY ./php/index.php /var/www/html/index.php
RUN a2enmod rewrite
RUN a2enmod ssl
RUN a2ensite default-ssl
RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/ssl/private/ssl-cert-snakeoil.key -out /etc/ssl/certs/ssl-cert-snakeoil.pem -subj "/C=AT/ST=Vienna/L=Vienna/O=Security/OU=Development/CN=example.com"

COPY ./src/siscolidentifiant /var/www/html/applications/siscolidentifiant
COPY php.ini /usr/local/etc/php/php.ini
COPY php/php.ini-development /usr/local/etc/php/php.ini-development
COPY php/php.ini-production /usr/local/etc/php/php.ini-production
COPY php/ssmtp.conf /etc/ssmtp/ssmtp.conf
RUN chmod g+r -R /etc/ssl/
RUN chmod o+r -R /etc/ssl/
RUN chmod g+x -R /etc/ssl/
RUN chmod o+x -R /etc/ssl/
RUN chown -R www-data:www-data /var/www
RUN chown -R www-data:www-data /etc/ssl/private
USER www-data:www-data
#COPY composer.json /var/www/html/project/composer.json
#RUN php /var/www/html/project/composer.phar install
EXPOSE 80
#EXPOSE 443