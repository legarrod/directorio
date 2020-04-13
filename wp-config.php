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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'directorio' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'Sena123!' );

/** MySQL hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '=l6N7X],ZH~o1[a_Vl95{l>|6B&^-*rANXDh}P#m&{Z#,Um=sTjM+g?^CLBFHfy5' );
define( 'SECURE_AUTH_KEY',  'Sv]h3$r}E]SudQMfQl?ges=b$:99MsL2&>yFaM c-rI}LIk,AbOKSVu@FLvhUj&A' );
define( 'LOGGED_IN_KEY',    'u,3Oj%:It_m5s4mgg`LP!xAD2OiF1qNiE;AYFdEeFfM+z~M&yZg$SpYKaW45LQka' );
define( 'NONCE_KEY',        '3$36G*j8w[KBiR$e?@ODGE%? :3L.9A2aWjLwnsc(W{l5Ob%n#dz_oXki*)6a#&z' );
define( 'AUTH_SALT',        '6~1rHi2]GiEqXoK?a2^X?zv%!qPGB;^P9qLNbXwy7q1dVagnv*3|KZsI2WMYj?T~' );
define( 'SECURE_AUTH_SALT', '[{zP8Fm</$@yj[:48:NK1!mYX~N@_vEwtYH~ A!T,%8y^IX)lB+;6xbMO?1>E~EI' );
define( 'LOGGED_IN_SALT',   'e^}{pb>YqE7,dd{V,f^bDP)YgoP0sAA(}q1S%,)XLB2ps8X,N,lTHIY.`_I~hFPD' );
define( 'NONCE_SALT',       '5qpbbZ`% Al$Q}tQ6f4gDA#%F&xox.w=Koz(czMH0gwF^Vz&FVU7D=j|QD~49Ya+' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
