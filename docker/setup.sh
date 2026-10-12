#!/usr/bin/env bash
# Installs the dependencies and WordPress in the development environment. Safe to re-run.
# Usage: docker compose exec -u www-data web bash docker/setup.sh
set -euo pipefail
cd /var/www/project

if [ ! -f .env ]; then
    cp .env.example .env
    echo "Created .env from .env.example"
fi

# Replace the placeholder salts with random values
for key in AUTH_KEY SECURE_AUTH_KEY LOGGED_IN_KEY NONCE_KEY AUTH_SALT SECURE_AUTH_SALT LOGGED_IN_SALT NONCE_SALT; do
    if grep -q "^${key}='generateme'" .env; then
        sed -i "s|^${key}='generateme'|${key}='$(php -r 'echo bin2hex(random_bytes(32));')'|" .env
    fi
done

composer install --no-interaction

if ! output=$(wp db check 2>&1); then
    echo "$output" >&2
    echo "Cannot use the database: in .env, set DB_HOST=db and the DB_NAME, DB_USER, DB_PASSWORD used by compose.yaml." >&2
    echo "If you changed these values after the first start, recreate the database volume: docker compose down -v" >&2
    exit 1
fi

if ! wp core is-installed 2>/dev/null; then
    # Notices go to stderr, so that they cannot end up in the URL
    home=$(php -d display_errors=stderr -r 'require "vendor/autoload.php"; require "config/application.php"; echo WP_HOME;')
    wp core install \
        --url="${home}" \
        --title="Simply" \
        --admin_user=admin \
        --admin_password=admin \
        --admin_email=admin@example.test \
        --skip-email
    wp rewrite structure '/%postname%/' >/dev/null
    echo "WordPress installed: ${home} (admin: ${home}/wp/wp-admin, admin/admin, development only)"
else
    echo "WordPress is already installed."
fi
