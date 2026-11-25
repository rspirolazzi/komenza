#!/bin/sh
set -e

if [ "$RUN_MIGRATIONS" = "true" ]; then
    runuser -u www-data -- php artisan migrate --force
fi

exec docker-php-entrypoint "$@"
