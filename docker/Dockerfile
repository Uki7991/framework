FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    libonig-dev \
    supervisor

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql pdo mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

COPY ./composer.json /var/www/html
COPY ./composer.lock /var/www/html

WORKDIR /var/www/html

RUN composer install

COPY . /var/www/html

COPY --chown=www:www . /var/www/html

RUN chmod -R a+rw /var/log/supervisor

COPY ./docker/supervisord.conf /etc/supervisord.conf
COPY ./docker/supervisor.d /etc/supervisor.d

COPY ./docker/run.sh /tmp
RUN ["chmod", "+x", "/tmp/run.sh"]

USER www

EXPOSE 8000
ENTRYPOINT ["/tmp/run.sh"]