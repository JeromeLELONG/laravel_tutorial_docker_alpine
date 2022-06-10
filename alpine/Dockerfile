#FROM ulsmith/alpine-apache-php8

#ADD /public /app/
#ADD /composer.json /app/
#ADD /config /app/
#ADD /module /app/
#ADD /README.md /app/
#ADD /composer.lock /app/
#ADD /init_autoloader.php /app/
#ADD /Vagrantfile /app/
#ADD /composer.phar /app/
#ADD /LICENSE.txt /app/
#ADD /vendor /app/

#RUN chown -R apache:apache /app
FROM alpine:3.16

ARG BUILD_DATE
ARG VCS_REF
ENV APPLICATION_ENV development

# PHP_INI_DIR to be symmetrical with official php docker image
ENV PHP_INI_DIR /etc/php/7.1

# When using Composer, disable the warning about running commands as root/super user
ENV COMPOSER_ALLOW_SUPERUSER=1

COPY php.ini /etc/php/7.1/php.ini

# Persistent runtime dependencies
ARG DEPS="\
        freetype-dev \
        libpng-dev \
        libpng-dev \
        jpeg-dev \
        libjpeg-turbo-dev \
        libmcrypt-dev \
        php8-pecl-mcrypt \
        php8 \
        php8-phar \
        php8-bcmath \
        php8-calendar \
        php8-mbstring \
        php8-exif \
        php8-ftp \
        php8-openssl \
        php8-zip \
        php8-sysvsem \
        php8-sysvshm \
        php8-sysvmsg \
        php8-shmop \
        php8-sockets \
        php8-zlib \
        php8-bz2 \
        php8-curl \
        php8-simplexml \
        php8-xml \
        php8-opcache \
        php8-dom \
        php8-xmlreader \
        php8-xmlwriter \
        php8-tokenizer \
        php8-ctype \
        php8-session \
        php8-fileinfo \
        php8-iconv \
        php8-json \
        php8-posix \
        php8-pdo \
        php8-ldap \
        php8-pdo_mysql \
        php8-soap \
        php8-gd \
        php8-apache2 \
        curl \
        poppler-utils \
        ca-certificates \
        runit \
        apache2-proxy \
        apache2 \
        apache2-utils \
"


RUN apk add --no-cache tzdata && cp -r -f /usr/share/zoneinfo/Europe/Paris /etc/localtime

RUN apk update && apk add nodejs && apk add --update npm

RUN set -x \
    && apk add --no-cache $DEPS \
    && mkdir -p /run/apache2 \
    && ln -sf /dev/stdout /var/log/apache2/access.log \
    && ln -sf /dev/stderr /var/log/apache2/error.log

COPY tags/apache /
#RUN a2enmod rewrite

COPY ./httpd.conf /etc/apache2/httpd.conf
RUN adduser apache xfs
RUN adduser apache www-data

COPY ./runit-wrapper /sbin/runit-wrapper
RUN chmod 755 /sbin/runit-wrapper



EXPOSE 80

CMD ["/sbin/runit-wrapper"]