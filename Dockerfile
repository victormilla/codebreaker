FROM ubuntu:18.04

# Maybe in the future we can use `apt` instead of `apt-get`, but for now it is not stable, according to
# a warning message you get: "WARNING: apt does not have a stable CLI interface. Use with caution in scripts."

RUN apt-get update && apt-get upgrade -yq

# Disable motd

RUN chmod -x /etc/update-motd.d/* && \
    mkdir -p /root/.cache && \
    touch /root/.cache/motd.legal-displayed && \
    mkdir -p /var/www/.cache && \
    touch /var/www/.cache/motd.legal-displayed

# Install dependencies

RUN apt-get update && apt-get install -yq \
        nano \
        wget \
        man \
        netcat \
        iputils-ping \
        net-tools \
        git \
        subversion \
        curl \
        zlib1g-dev \
        unzip \
        postgresql-client \
        xlsx2csv \
        xvfb \
        cutycapt

        # nano              -> just a nice editor
        # wget              -> nice tool to download and debug
        # man               -> linux manual, for reference
        # netcat            -> to test that the XDebug port is listening
        # iputils-ping      -> nice to test network connectivity
        # net-tools         -> nice to debug network issues: ifconfig & netstat
        # git               -> for composer
        # subversion        -> for composer
        # curl              -> for composer
        # zlib1g-dev        -> for composer
        # unzip             -> for composer
        # postgresql-client -> for postgres
        # xlsx2csv          -> for dataloom
        # xvfb              -> for marketing
        # cutycapt          -> for marketing


# Install nginx
    # (And increase limit of HTTP request)

RUN apt-get update && apt-get install -yq nginx && \
    sed "/http {/a client_max_body_size 20M;" -i /etc/nginx/nginx.conf

# Install PHP

RUN ln -fs /usr/share/zoneinfo/Europe/London /etc/localtime && \
    apt-get update && apt-get install -yq \
        software-properties-common

# I am not deleting this line that was on the previous RUN because probably during the life of 18.04 we will need to
# use the ondrej repositories again to access a newer version of PHP
RUN LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php

RUN apt-get update && apt-get install -yq \
        php7.3-fpm \
        php7.3-sqlite \
        php7.3-mysql \
        php7.3-pgsql \
        php7.3-xml \
        php7.3-intl \
        php7.3-mbstring \
        php7.3-zip \
        php7.3-apc \
        php7.3-curl \
        php7.3-gd \
        php7.3-bz2 \
	    php7.3-soap

        # php7.3-fpm      -> core PHP
        # php7.3-sqlite   -> SQLite database driver
        # php7.3-mysql    -> MySQL database driver
        # php7.3-pgsql    -> Postgres database driver
        # php7.3-xml      -> XML handling (Required for Symfony)
        # php7.3-intl     -> Required for Symfony validators
        # php7.3-mbstring -> Required for Behat & PHPUnit
        # php7.3-zip      -> Required for PHPUnit
        # php7.3-apc      -> Bytecode performance (Recommended by Symfony)
        # php7.3-curl     -> Not truly required, but handy to have
        # php7.3-gd       -> Required for SixthForm Fixtures
        # php7.3-bz2      -> Not truly required, but handy to have
        # php7.3-soap     -> Required for Connector (WebService Support)

# Output PHP logs

RUN sed -i 's/.daemonize =.*/daemonize = no/' /etc/php/7.3/fpm/php-fpm.conf \
    && sed -i 's|error_log =.*|error_log = /proc/self/fd/2|' /etc/php/7.3/fpm/php-fpm.conf \
    && sed -i 's/.catch_workers_output =.*/catch_workers_output = yes/' /etc/php/7.3/fpm/pool.d/www.conf

# Create folder for fpm socket

RUN mkdir /run/php

# PECL (For some PHP dependencies)

RUN apt-get update && apt-get install -yq \
        php-pear \
        php7.3-dev

# PHP Debugging tools

RUN pecl install vld-beta

# Install Imagick (Required for anguloceramicart, to manipulate images)

RUN apt-get update \
    && apt-get install -yq \
        libmagickwand-dev \
    && pecl install imagick-beta \
    && echo "extension=imagick.so" > /etc/php/7.3/mods-available/imagick.ini \
    && phpenmod imagick

# Symfony shortcuts

ADD sf.sh /usr/bin/sf
ADD debugsf.sh /usr/bin/debugsf
ADD debugphp.sh /usr/bin/debugphp

# Give permissions on the log folder (For some Symfony projects to write the logs there)

RUN chmod go+w /var/log

# Install composer

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

# PHP XDebug

# This is the ugly alternative way in case the below, nicer way, doesn't work
#RUN cd /tmp && \
#    wget http://xdebug.org/files/xdebug-2.6.0beta1.tgz && \
#    tar -xvzf xdebug-2.6.0beta1.tgz && \
#    cd xdebug-2.6.0beta1 && \
#    phpize && \
#    ./configure && \
#    make && \
#    cp modules/xdebug.so /usr/lib/php/20170718

RUN apt-get update && apt-get install -yq php7.3-xdebug

RUN echo "AcceptEnv XDEBUG_REMOTE_HOST" >> /etc/ssh/sshd_config && \
    echo "AcceptEnv XDEBUG_REMOTE_PORT" >> /etc/ssh/sshd_config

# Remove installation files

RUN apt-get remove -y \
        build-essential && \
    apt-get -y autoremove && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Bash and www-data profile configuration

ADD .bashrc /root/.bashrc
ADD .bashrc /var/www/.bashrc
RUN cp ~/.profile /var/www/.profile && \
    chown www-data:www-data /var/www/.profile /var/www/.bashrc /var/www

# Run

WORKDIR /var/www

ADD run.sh /run.sh

RUN chmod a+x /run.sh

CMD /run.sh
