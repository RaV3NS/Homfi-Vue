FROM php:7.2-fpm

MAINTAINER Pavlo Chumak <paul.chumak@gmail.com>

ENV php_conf /usr/local/etc/php/php.ini
ENV fpm_conf /usr/local/etc/php-fpm.conf


RUN apt-get update && apt-get install -y \
        ca-certificates \
        curl \
        iputils-ping \
        libcurl4 \
        libcurl4-openssl-dev \
        wget \
        mc \
        bzip2 \
        librecode0 \
        libsqlite3-0 \
        libxml2 \
        libgd-dev \
        libkrb5-dev \
        libmcrypt4 \
        libmcrypt-dev \
        libxml2-dev \
        libc-client2007e-dev \
        libmemcached-dev \
        libicu-dev \
        libpq-dev \
        freetype* \
        procps \
        net-tools \
        vim \
        nginx \
        supervisor \
        git \
        exiftool \
        --no-install-recommends

RUN mkdir /usr/share/man/man1/
RUN apt-get install -y \
        osmosis

RUN docker-php-ext-install \
        mysqli \
        pdo \
        pdo_mysql \
        curl \
        intl \
        json \
        pgsql \
        zip \
        bcmath \
        exif

RUN apt-get install -y --no-install-recommends \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libxpm-dev \
        libvpx-dev \
&& docker-php-ext-configure gd \
        --with-freetype-dir=/usr/lib/x86_64-linux-gnu/ \
        --with-jpeg-dir=/usr/lib/x86_64-linux-gnu/ \
        --with-xpm-dir=/usr/lib/x86_64-linux-gnu/ \
&& docker-php-ext-install gd

RUN pecl config-set php_ini /usr/local/etc/php/php.ini \
    && pecl install \
        mcrypt-1.0.1 \
        mongodb \
        redis \
        #xdebug \
    && docker-php-ext-enable \
        mcrypt \
        mongodb \
        redis \
        exif \
        gd \
        #xdebug \
    && curl -sS https://getcomposer.org/installer |  php -- --install-dir=/usr/local/bin --filename=composer

COPY .docker/git-crypt-0.6.0.tar.gz /tmp/

RUN cd /tmp/ \
    && tar -xzvf git-crypt-0.6.0.tar.gz \
    && cd git-crypt-0.6.0 \
    && make \
    && make install


RUN curl -sL https://deb.nodesource.com/setup_12.x | bash -
RUN apt-cache show nodejs | grep Version
RUN apt-cache show npm | grep Version
#RUN apt-get install -y nodejs=8.17.0-1nodesource
RUN apt-get install -y nodejs
RUN npm install --global cross-env
RUN rm -r /var/lib/apt/lists/*

RUN mkdir -p /var/www/ && ls -l

COPY . /var/www

RUN ls -l /var/www/ && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache


COPY .docker/php/php-ini-overrides.ini /usr/local/etc/php/conf.d/99-overrides.ini
COPY .docker/php/php-fpm.conf ${fpm_conf}
COPY .docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY .docker/nginx/php.conf /etc/nginx/php.conf
COPY .docker/nginx/host.conf /etc/nginx/conf.d/default.conf
COPY .docker/supervisord.conf /etc/supervisor/supervisord.conf
COPY .docker/supervisor.d/sys.conf /etc/supervisor/conf.d/sys.conf
COPY .docker/supervisor.d/web.conf /etc/supervisor/conf.d/web.conf
COPY .docker/run.sh /run.sh

#RUN ls -la /var/www/ && composer --working-dir=/var/www/ install \
#    && echo 1 > /var/www/composer-done

WORKDIR /var/www/

EXPOSE 8001

CMD /run.sh
