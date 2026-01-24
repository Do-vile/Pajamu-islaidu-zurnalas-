FROM php:8.3-cli

WORKDIR /app

RUN docker-php-ext-install pdo_sqlite

COPY . /app

RUN mkdir -p /app/data \
    && chmod -R 777 /app/data

ENV PORT=10000
EXPOSE 10000

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT} public/router.php"]
