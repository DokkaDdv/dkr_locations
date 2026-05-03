FROM php:8.2-cli

RUN apt-get update && apt-get install -y libpq-dev && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo pdo_pgsql pgsql

WORKDIR /app
COPY . /app/

EXPOSE 8080

CMD php -S 0.0.0.0:${PORT:-8080} -t /app
