# Gunakan base image PHP dengan FPM
FROM php:8.0-fpm

COPY .docker/php/php.ini /usr/local/etc/php/
COPY .docker/php/docker.conf /usr/local/etc/php-fpm.d/docker.conf
COPY .docker/php/.bashrc /root/

# Instal ekstensi PHP yang dibutuhkan
RUN apt-get update \
  && apt-get install nano -y build-essential libxml2-dev zlib1g-dev default-mysql-client curl gnupg procps vim git unzip libaio1 libnss3 libzip-dev libpq-dev wget curl \
  && docker-php-ext-install zip pdo_mysql pdo_pgsql pgsql soap

RUN apt-get install -y libicu-dev \
  && docker-php-ext-configure intl \
  && docker-php-ext-install intl

# Download oracle packages and install OCI8
RUN curl -o instantclient-basic-linux.x64-19.6.0.0.0dbru.zip https://download.oracle.com/otn_software/linux/instantclient/19600/instantclient-basic-linux.x64-19.6.0.0.0dbru.zip \
    && unzip instantclient-basic-linux.x64-19.6.0.0.0dbru.zip -d /usr/lib/oracle/ \
    && rm instantclient-basic-linux.x64-19.6.0.0.0dbru.zip \
    && curl -o instantclient-sdk-linux.x64-19.6.0.0.0dbru.zip https://download.oracle.com/otn_software/linux/instantclient/19600/instantclient-sdk-linux.x64-19.6.0.0.0dbru.zip \
    && unzip instantclient-sdk-linux.x64-19.6.0.0.0dbru.zip -d /usr/lib/oracle/ \
    && rm instantclient-sdk-linux.x64-19.6.0.0.0dbru.zip \
    && echo /usr/lib/oracle/instantclient_19_6 > /etc/ld.so.conf.d/oracle-instantclient.conf \
    && ldconfig

ENV LD_LIBRARY_PATH /usr/lib/oracle/instantclient_19_6

# Install PHP extensions: Laravel needs also zip, mysqli and bcmath which
# are not included in default image. Also install our compiled oci8 extensions.
RUN docker-php-ext-configure oci8 --with-oci8=instantclient,/usr/lib/oracle/instantclient_19_6 \
    && docker-php-ext-install -j$(nproc) oci8

# Set work directory
WORKDIR /var/www/html

# Salin semua file proyek
# COPY smile/ /var/www/html/smile

# Berikan izin
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

WORKDIR /root
EXPOSE 9000
WORKDIR /var/www/html
CMD ["php-fpm"]
