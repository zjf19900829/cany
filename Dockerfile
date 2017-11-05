FROM php:5.6-apache
RUN apt-get update && apt-get install -y \
		libfreetype6-dev \
		libjpeg62-turbo-dev \
		libmcrypt-dev \
		libpng-dev \
	&& docker-php-ext-install -j$(nproc) iconv mcrypt \
	&& docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
	&& docker-php-ext-install -j$(nproc) gd

RUN apt-get update && apt-get install -y libmemcached-dev zlib1g-dev \
	&& pecl install memcached-2.2.0 \
	&& docker-php-ext-enable memcached

RUN pecl install memcache
RUN docker-php-ext-enable memcache

RUN docker-php-ext-install pdo_mysql



COPY www/ /var/www/html/
EXPOSE 80
RUN cp /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/
