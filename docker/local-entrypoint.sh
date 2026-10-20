#!/bin/sh
set -eu
if [ "${APP_ENV:-}" != local ] || [ "${DEPLOYMENT_PROFILE:-}" != demo ]; then
    echo 'This entrypoint is restricted to the local demo.' >&2
    exit 1
fi
: "${DEMO_INITIAL_PASSWORD:?Local owner password is required}"
export DEMO_OWNER_PASSWORD_HASH="$(php -r 'echo password_hash(getenv("DEMO_INITIAL_PASSWORD"), PASSWORD_BCRYPT);')"
unset DEMO_INITIAL_PASSWORD
php artisan package:discover --no-interaction
php artisan migrate --force --no-interaction
php artisan school:bootstrap-demo --no-interaction
exec php artisan serve --host=0.0.0.0 --port=8000 --no-reload
