<?php

use Symfony\Component\Dotenv\Dotenv;

/**
 * Directory containing all the site's files
 */
$rootDir = dirname(__DIR__);
define('APP_ROOT_CONFIG', $rootDir . '/config');

/**
 * Document Root
 */
$webDir = $rootDir . '/web';

function _simply_env_define(string $var, bool $required = false, $default = null): void {
    if (isset($_ENV[$var]) && !defined($var)) {
        define($var, $_ENV[$var]);
    } elseif (true === $required && !defined($var) && !isset($_ENV[$var])) {
        throw new InvalidArgumentException('The env var ' . $var . ' must be defined in .env file.');
    } elseif(!isset($_ENV[$var]) && !is_null($default)) {
        define($var, $default);
    }
}

$dotEnv = new Dotenv();
$dotEnv->loadEnv($rootDir . '/.env');

/**
 * URLs
 */
_simply_env_define('WP_HOME', true);
_simply_env_define('WP_SITEURL');

/**
 * Custom Content Directory
 */
define('CONTENT_DIR', '/content');
define('WP_CONTENT_DIR', $webDir . CONTENT_DIR);
define('WP_CONTENT_URL', WP_HOME . CONTENT_DIR);

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
_simply_env_define('DB_NAME');
/** MySQL database username */
_simply_env_define('DB_USER');
/** MySQL database password */
_simply_env_define('DB_PASSWORD');

/** MySQL hostname */
define('DB_HOST', $_ENV['DB_HOST'] ?: 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', $_ENV['DB_CHARSET'] ?: 'utf8mb4');
/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**
 * Custom Settings
 */
define('AUTOMATIC_UPDATER_DISABLED', true);
_simply_env_define('DISABLE_WP_CRON', false, false);
// Disable the plugin and theme file editor in the admin
define('DISALLOW_FILE_EDIT', true);
// Disable plugin and theme updates and installation from the admin
define('DISALLOW_FILE_MODS', true);
// Limit the number of post revisions that WordPress stores (true (default WP): store every revision)
_simply_env_define('WP_POST_REVISIONS', false, true);

/**
 * Authentication Unique Keys and Salts.
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
_simply_env_define('AUTH_KEY');
_simply_env_define('SECURE_AUTH_KEY');
_simply_env_define('LOGGED_IN_KEY');
_simply_env_define('NONCE_KEY');
_simply_env_define('AUTH_SALT');
_simply_env_define('SECURE_AUTH_SALT');
_simply_env_define('LOGGED_IN_SALT');
_simply_env_define('NONCE_SALT');
_simply_env_define('WP_CACHE_KEY_SALT');

/**
 * WordPress Database Table prefix.
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = $_ENV['DB_PREFIX'];

/**
 * Allow WordPress to detect HTTPS when used behind a reverse proxy or a load balancer
 * See https://codex.wordpress.org/Function_Reference/is_ssl#Notes
 */
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}

define('WP_DEBUG', $_ENV['APP_DEBUG'] === 'true');

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
    define('ABSPATH', $webDir . '/wp/');
}
