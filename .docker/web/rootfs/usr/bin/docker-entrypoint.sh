#!/bin/sh
# Set shell options:
#   -e, exit immediately if a command exits with a non-zero status
set -e

# optimize
php artisan optimize --no-interaction
rm -rf bootstrap/cache/routes*

exec "$@"
