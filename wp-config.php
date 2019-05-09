<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

if (file_exists(dirname(__FILE__).'/local-config.php')) {
	include(dirname(__FILE__).'/local-config.php');
}
else {

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', '');

/** MySQL database username */
define('DB_USER', '');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/** The URL website. */
define('WP_HOME', 'http://website.lc');
define('WP_SITEURL', WP_HOME);

}

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '55fc9hojluto5rzuqzodrjbjqmsvziigpi7njzp2rfay63wvnu8vdol08isdw4xa');
define('SECURE_AUTH_KEY',  'o8jnqddlbw4nhwdvwnulzzpv5duxvo4ak0sob8fdbe2tyg1dmwl1nqx1d7kxwtz3');
define('LOGGED_IN_KEY',    'vc2io3lwc1xdv9yphdb3f67sbtkknbjzynpkl9kuebkm3ir53q6ohkrxs2hqkknt');
define('NONCE_KEY',        'cwchwglchmkgk8mz67lc5bp4fkik3etyzuocfoub6zgfvdyebmosjpnxivjjomqm');
define('AUTH_SALT',        'mqaxcyrurg1gyv9dxwrrdk6f5gkfsdcs7chbfyqkrdvyyvtpcxtfhdrltmu1mous');
define('SECURE_AUTH_SALT', 'yedjkmvuvua2sz6hplsuyzuio4uhbuka4yxvwguq3lx2dz4gkgmpisacbtslaco7');
define('LOGGED_IN_SALT',   '9ysfsms6hrphbmks8iw5vwzws0cssrklgidrbvsi0v64dyh3zcvm7b9qo9jgigxz');
define('NONCE_SALT',       '4pjfuep7r7gvh1tvsbyor273mb7iqvp2arzmicqjuhee9ysjgsjc4cfxx8dgbtzn');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpbt_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);
define( 'WP_MEMORY_LIMIT', '256M' );
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
