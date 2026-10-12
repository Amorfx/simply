Simply Boilerplate
==================
A WordPress project boilerplate with [Simply framework](https://github.com/Amorfx/simply-framework) and [Simply maker](https://github.com/Amorfx/simply-maker): WordPress and plugins installed with Composer, configuration in `.env`, Symfony dependency injection and a code generator.

## Requirements
- PHP 8.2, 8.3 or 8.4
- Composer 2
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
