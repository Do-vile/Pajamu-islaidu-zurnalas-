FROM php:8.3-cli

# Install SQLite dependencies
RUN apt-get update \
    && apt-get install -y sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo_sqlite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app
COPY . /app

# Ensure SQLite directory exists and is writable
RUN mkdir -p /app/data && chmod -R 777 /app/data

ENV PORT=10000
EXPOSE 10000

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT} public/router.php"]
