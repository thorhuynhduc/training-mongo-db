FROM alpine:3.21
LABEL Maintainer="Thor <thorhuynhduc@gmail.com>" \
      Description="Lightweight container with Nginx & PHP-FPM 8.4 based on Alpine Linux."

# Install packages
RUN apk --no-cache add bash zlib zlib-dev php84 php84-fpm php84-bcmath php84-ctype php84-json php84-fileinfo \
    php84-mbstring php84-openssl acl php84-pdo_pgsql php84-curl php84-pdo php84-tokenizer php84-xml \
    php84-opcache nginx curl openssh openssl runit openrc php84-simplexml php84-dom php84-xmlwriter \
    php84-sockets php84-phar git php84-gd php84-iconv php84-xmlreader php84-zip php84-session php84-sodium php84-imap php84-pgsql php84-zlib nano \
    php84-pecl-xdebug php84-pecl-redis redis php84-mongodb

RUN apk add --no-cache -X http://dl-cdn.alpinelinux.org/alpine/edge/testing
#  php84-pecl-grpc

# fix work iconv library with alphine
RUN apk add --no-cache --repository http://dl-cdn.alpinelinux.org/alpine/edge/community/ --allow-untrusted gnu-libiconv
ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so php

RUN cp /usr/bin/php84 /usr/bin/php

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Configure nginx
COPY nginx.conf /etc/nginx/nginx.conf
# Configure nginx - default server
COPY ./conf.d/default.conf /etc/nginx/conf.d/default.conf

# Configure PHP-FPM
COPY fpm-pool.conf /etc/php84/php-fpm.d/www.conf
COPY php.ini /etc/php84/conf.d/custom.ini

# Configure runit boot script
COPY boot.sh /sbin/boot.sh

RUN adduser -D -u 1000 -g 1000 -s /bin/sh www && \
    mkdir -p /var/www/html && \
    mkdir -p /var/cache/nginx && \
    chown -R www:www /var/www/html && \
    chown -R www:www /run && \
    chown -R www:www /var/lib/nginx && \
    chown -R www:www /var/log/nginx

COPY nginx.run /etc/service/nginx/run
COPY php.run /etc/service/php/run

RUN chmod +x /etc/service/nginx/run \
    && chmod +x /etc/service/php/run \
    && ls -al /var/www/html/

WORKDIR /var/www/html

# Expose the port nginx is reachable on
EXPOSE 80

# Let boot start nginx & php-fpm
CMD ["sh", "/sbin/boot.sh"]

