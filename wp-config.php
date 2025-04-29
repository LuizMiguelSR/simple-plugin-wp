<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'contato' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

if ( !defined('WP_CLI') ) {
    define( 'WP_SITEURL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
    define( 'WP_HOME',    $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
}



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
define( 'AUTH_KEY',         '5Yj8xTNsghRl5Kc3UFTosd5KgUEXkDCuIrdnxEKkvaadJQkNAtSwSEZGrBn12JuY' );
define( 'SECURE_AUTH_KEY',  'mzzDtHmFZUIyQlqTMM3iuIA1SLOsCQ2Q3oWdLaV1MBuH1M5Zdjs56QnzO2U8hW33' );
define( 'LOGGED_IN_KEY',    'cy30Qs9WYm5pPSn1lbr2tZRh0H2Sze53AyOPrxpZ23wbr2xuHfXYwY8vKnLaV3ak' );
define( 'NONCE_KEY',        '9uLkyJs3k0CRgRDakvatSNlCM9GBsWAVfScyP9kpJNCuJDZg7Zh3QqraiBPwKngv' );
define( 'AUTH_SALT',        '6vcjR9KGbzJAd4L8FaV9YEdQTRehgqZvf3celnyC4JSYKE8YpFuzceiuO7mdpMVL' );
define( 'SECURE_AUTH_SALT', 'lP3Za1rqzyLgSqejvSdvyfYDGWAKCV5kn3f5P2I3ofmaWi2S7PaQGF2mMU7PXprk' );
define( 'LOGGED_IN_SALT',   'a62WEwkXVmREoKUKsVtwLzkQsuYD86adVMWcpymCMcdDDJHWFixGyC0Cbv1FQ3Vb' );
define( 'NONCE_SALT',       'OUTJvR9XFdayWKEo9x70MCZiAAGzpvCXe9WKix1mfrBXa308CPKR01a6Xl13Atro' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
