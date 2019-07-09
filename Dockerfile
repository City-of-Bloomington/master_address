FROM ubuntu:latest
ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=America/Indiana/Indianapolis
RUN ln -snf /usr/share/zoneinfo/America/$TZ /etc/localtime \
    && echo $TZ > /etc/timezone

RUN apt-get update && apt-get install -y apache2
RUN a2enmod alias && \
    a2enmod headers && \
    a2enmod remoteip && \
    a2enmod rewrite

RUN apt-get install -y \
    php-common \
    php-cli \
    php-dom \
    php-json \
    php-readline \
    php-mbstring \
    php-pgsql \
    php-intl \
    php-zip \
    unzip \
    php-curl \
    php-ldap \
    php-xsl \
    composer \
    libapache2-mod-php \
    gettext \
    make \
    sassc

COPY docker/php.ini /etc/php/7.2/apache2/conf.d/local.ini
COPY docker/php.ini /etc/php/7.2/cli/conf.d/local.ini
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY docker/phpinfo.php /var/www/html/phpinfo.php


WORKDIR /srv/sites/master_address
COPY --chown=www-data:staff . /srv/sites/master_address
RUN composer install --no-plugins --no-scripts

RUN make compile

EXPOSE 80
ENTRYPOINT ["apachectl", "-D", "FOREGROUND"]
