<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * This has been slightly modified (to read environment variables) for use in Docker.
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// IMPORTANT: this file needs to stay in-sync with https://github.com/WordPress/WordPress/blob/master/wp-config-sample.php
// (it gets parsed by the upstream wizard in https://github.com/WordPress/WordPress/blob/f27cb65e1ef25d11b535695a660e7282b98eb742/wp-admin/setup-config.php#L356-L392)

// a helper function to lookup "env_FILE", "env", then fallback
if (!function_exists('getenv_docker')) {
	// https://github.com/docker-library/wordpress/issues/588 (WP-CLI will load this file 2x)
	function getenv_docker($env, $default) {
		if ($fileEnv = getenv($env . '_FILE')) {
			return rtrim(file_get_contents($fileEnv), "\r\n");
		}
		else if (($val = getenv($env)) !== false) {
			return $val;
		}
		else {
			return $default;
		}
	}
}

define('WP_HOME','http://127.0.0.1');
define('WP_SITEURL','http://127.0.0.1');

define('FS_METHOD','direct');
define( 'AUTOSAVE_INTERVAL', 60*60*60*24*365 ); // Set autosave interval to 1x per year
define( 'EMPTY_TRASH_DAYS',  0 ); // Empty trash now: Zero days
define( 'WP_POST_REVISIONS', false );
define( 'DISABLE_WP_CRON', true ); // sends an XHR request with timestamp to server on every page load which queries db for posts that may need to be published and does so; :gross:

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', getenv_docker('WORDPRESS_DB_NAME', 'wordpress') );

/** Database username */
define( 'DB_USER', getenv_docker('WORDPRESS_DB_USER', 'example username') );

/** Database password */
define( 'DB_PASSWORD', getenv_docker('WORDPRESS_DB_PASSWORD', 'example password') );

/**
 * Docker image fallback values above are sourced from the official WordPress installation wizard:
 * https://github.com/WordPress/WordPress/blob/f9cc35ebad82753e9c86de322ea5c76a9001c7e2/wp-admin/setup-config.php#L216-L230
 * (However, using "example username" and "example password" in your database is strongly discouraged.  Please use strong, random credentials!)
 */

define( 'AUTOSAVE_INTERVAL', 60*60*60*24*365 ); // Set autosave interval to 1x per year
define( 'EMPTY_TRASH_DAYS',  0 ); // Empty trash now: Zero days
define( 'WP_POST_REVISIONS', false );
define( 'DISABLE_WP_CRON', true ); // sends an XHR request with timestamp to server on every page load which queries db for posts that may need to be published and does so; :gross:

/** Database hostname */
define( 'DB_HOST', getenv_docker('WORDPRESS_DB_HOST', 'mysql') );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', getenv_docker('WORDPRESS_DB_CHARSET', 'utf8') );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', getenv_docker('WORDPRESS_DB_COLLATE', '') );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         getenv_docker('WORDPRESS_AUTH_KEY',         'b9c5548f1702bdbf66ab1eda4ceea4786fd64c41') );
define( 'SECURE_AUTH_KEY',  getenv_docker('WORDPRESS_SECURE_AUTH_KEY',  '296e85f0bcf1a502fda584949ce2dd6b5145c62b') );
define( 'LOGGED_IN_KEY',    getenv_docker('WORDPRESS_LOGGED_IN_KEY',    'a5ac8d45c509ee9807ee01e2434ca81f736f6770') );
define( 'NONCE_KEY',        getenv_docker('WORDPRESS_NONCE_KEY',        '0d0a6a107fa4624883029a2b94b26e327f63b894') );
define( 'AUTH_SALT',        getenv_docker('WORDPRESS_AUTH_SALT',        '37870a466610a9eb1bdf32850ec80fc376e45c90') );
define( 'SECURE_AUTH_SALT', getenv_docker('WORDPRESS_SECURE_AUTH_SALT', 'bf126c285a8feae707df67cc15b97d6ee3bf51c6') );
define( 'LOGGED_IN_SALT',   getenv_docker('WORDPRESS_LOGGED_IN_SALT',   '6d725ae0ddd5fcf4908483678110073e01acf8d5') );
define( 'NONCE_SALT',       getenv_docker('WORDPRESS_NONCE_SALT',       'e64a200f522e4775bae23b65c379537f8a4df864') );
// (See also https://wordpress.stackexchange.com/a/152905/199287)

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = getenv_docker('WORDPRESS_TABLE_PREFIX', 'wp_');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', !!getenv_docker('WORDPRESS_DEBUG', '') );

/* Add any custom values between this line and the "stop editing" line. */

// If we're behind a proxy server and using HTTPS, we need to alert WordPress of that fact
// see also https://wordpress.org/support/article/administration-over-ssl/#using-a-reverse-proxy
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) {
	$_SERVER['HTTPS'] = 'on';
}
// (we include this by default because reverse proxying is extremely common in container environments)

if ($configExtra = getenv_docker('WORDPRESS_CONFIG_EXTRA', '')) {
	eval($configExtra);
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
