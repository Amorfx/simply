Simply Boilerplate
==================
A WordPress project boilerplate with [Simply framework](https://github.com/Amorfx/simply-framework) and [Simply maker](https://github.com/Amorfx/simply-maker): WordPress and plugins installed with Composer, configuration in `.env`, Symfony dependency injection and a code generator.

## Requirements
- PHP 8.2, 8.3 or 8.4
- Composer 2
- WordPress 6.1+ (new projects install WordPress 7)
- MySQL 8+ or MariaDB 10.6+
- A web server whose document root is the `web/` directory

## Quick start
```bash
composer create-project clementdecou/simply my-project
cd my-project
```
Edit `.env` (created from `.env.example`):
- `WP_HOME`: the URL of the site, with its scheme (`http://localhost:8080`);
- `DB_NAME`, `DB_USER`, `DB_PASSWORD`, `DB_HOST`;
- the salts: generate them at https://roots.io/salts.html.

Point your web server to `web/`, then install WordPress from the browser or with WP-CLI (`wp core install`).

## Development environment with Docker
The project ships a development environment (PHP 8.4 with Apache, MariaDB). In `.env`, set:
```
WP_HOME=http://localhost:8080
DB_HOST=db
```
Then:
```bash
docker compose up -d --build
docker compose exec -u www-data web bash docker/setup.sh
```
`setup.sh` replaces the placeholder salts, installs the Composer dependencies and WordPress (admin account `admin`/`admin`). The site is on http://localhost:8080.

Run the commands as `www-data`, so that the files they create belong to you:
```bash
docker compose exec -u www-data web php bin/console list simply
docker compose exec -u www-data web wp plugin list
```
Settings, as environment variables or in `.env`:
- `HTTP_PORT` (default `8080`);
- `PHP_VERSION`: `8.2`, `8.3` or `8.4` (default), then `docker compose up -d --build`;
- `USER_ID` / `GROUP_ID` on Linux, when your uid/gid is not 1000 (`id -u`, `id -g`).

Compose reads only `.env` (not `.env.local`): keep the `DB_*` values there. With `DB_HOST=db`, run `bin/console` and `wp` through `docker compose exec`. To reset the database: `docker compose down -v`.

This environment is for development only and is only reachable from your machine. On macOS, two PHP requests rebuilding the Simply cache at the very same time can fail once: reload the page.

## Project structure
```
config/application.php   WordPress configuration, read from .env
config/extensions.php    Simply extensions to load (simply-maker, simply-mvc...)
web/wp/                  WordPress core (installed by Composer, not versioned)
web/content/             plugins, themes, mu-plugins and uploads
bin/console              Simply commands
```

## Simply commands
`bin/console` loads WordPress, so the database must be reachable.
```bash
php bin/console list simply            # all commands
php bin/console simply:make:plugin     # generate a plugin
php bin/console simply:cache:clear     # clear the Simply cache (run it when deploying)
php bin/console simply:debug:hook      # list the registered WordPress hooks
```

## Upgrading
See the release notes of [simply-framework](https://github.com/Amorfx/simply-framework/releases), in particular [v0.3.0](https://github.com/Amorfx/simply-framework/releases/tag/v0.3.0) (Symfony 7.4).

## Documentation
https://amorfx.github.io/simply-documentation/
