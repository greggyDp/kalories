FROM php:7.1.9-fpm

ADD php.ini /usr/local/etc/php/conf.d/php.ini

#Some utils
RUN apt-get update \
  && docker-php-ext-install pdo_mysql mysqli mbstring
RUN apt-get install -y apt-transport-https ca-certificates
RUN apt-get install -y zip unzip
#Memcache
RUN apt-get update \
  && apt-get install -y libmemcached-dev zlib1g-dev \
  && pecl install memcached-3.0.3 \
  && docker-php-ext-enable memcached opcache

#Composer
RUN apt-get install -y curl php5-cli git \
  && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
  && composer
#Npm
RUN apt-get install nodejs -y \
  && apt-get install npm -y
#yarn
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
  && echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list
RUN apt-get update \
  && apt-get install yarn -y