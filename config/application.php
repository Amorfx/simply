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

function _simply_env_define(string $var, bool $required = false, $default = null, bool $boolean = false): void {
    if (isset($_ENV[$var]) && !defined($var)) {
        $value = $_ENV[$var];
        // "true"/"false" from .env are strings: convert them for boolean settings only (a password may be "false")
        if ($boolean) {
            $value = filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? $value;
        }
        define($var, $value);
    } elseif (true === $required && !defined($var) && !isset($_ENV[$var])) {
        throw new InvalidArgumentException('The env var ' . $var . ' must be defined in .env file.');
    } elseif (!isset($_ENV[$var]) && !is_null($default) && !defined($var)) {
        define($var, $default);
    }
}

$dotEnv = new Dotenv();
$dotEnv->loadEnv($rootDir . '/.env');

/**
 * URLs
 */
_simply_env_define('WP_HOME', true);
_simply_env_define('WP_SITEURL', false, WP_HOME . '/wp');

/**
 * Custom Content Directory
 */
define('CONTENT_DIR', '/content');
define('WP_CONTENT_DIR', $webDir . CONTENT_DIR);
define('WP_CONTENT_URL', WP_HOME . CONTENT_DIR);

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
_simply_env_define('DB_NAME', true);
/** MySQL database username */
_simply_env_define('DB_USER', true);
/** MySQL database password */
_simply_env_define('DB_PASSWORD', true);

/** MySQL hostname */
_simply_env_define('DB_HOST', false, 'localhost');

/** Database Charset to use in creating database tables. */
_simply_env_define('DB_CHARSET', false, 'utf8mb4');
/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**
 * Custom Settings
 */
define('AUTOMATIC_UPDATER_DISABLED', true);
_simply_env_define('DISABLE_WP_CRON', false, false, true);
// Disable the plugin and theme file editor in the admin
define('DISALLOW_FILE_EDIT', true);
// Disable plugin and theme updates and installation from the admin
define('DISALLOW_FILE_MODS', true);
// Limit the number of post revisions that WordPress stores (true (default WP): store every revision, false: none, or a number)
if (isset($_ENV['WP_POST_REVISIONS']) && !defined('WP_POST_REVISIONS')) {
    $revisions = strtolower(trim($_ENV['WP_POST_REVISIONS']));
    define('WP_POST_REVISIONS', match (true) {
        $revisions === 'true' => true,
        $revisions === 'false' => false,
        is_numeric($revisions) => (int) $revisions,
        default => $_ENV['WP_POST_REVISIONS'],
    });
}
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
// Required: a silent default could point an existing site to other tables
$table_prefix = $_ENV['DB_PREFIX'] ?? throw new InvalidArgumentException('The env var DB_PREFIX must be defined in .env file.');

/**
 * Allow WordPress to detect HTTPS when used behind a reverse proxy or a load balancer
 * See https://codex.wordpress.org/Function_Reference/is_ssl#Notes
 */
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}

define('WP_DEBUG', filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOL));

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
    define('ABSPATH', $webDir . '/wp/');
}
