FROM mcr.microsoft.com/devcontainers/php:8.3

RUN apt-get update && \
    export DEBIAN_FRONTEND=noninteractive && \
    apt-get install -y mariadb-client && \
    apt-get clean -y && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql
