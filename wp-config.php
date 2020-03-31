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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'love' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         's?I3%JQT4fcay[=1Dy*r>C32lq=&_c*$m[LQz. |2V^O?&hhLqMI#=yHY^Y[z@=y' );
define( 'SECURE_AUTH_KEY',  'K@-*b4n$Gijag$I.2b3_V@g1B7;xMO|2k#(^n4I=0U3&Q|! ps@!].k[lCmQUyV}' );
define( 'LOGGED_IN_KEY',    'tz_J%`jwzV I2BF_LorfZC:50mDzbJ@q3i$]*hH&Ri95[SSSddJ$(cB EBl{!hZ>' );
define( 'NONCE_KEY',        'S[$]ZZ^S7.Ix(:h]Xheg7+;O}^?`H7?P_EGQM4f]i.OqKg7~^L-rDv*l^gw;k@,S' );
define( 'AUTH_SALT',        'Ow4S^BtYBh*YkWt10E(hoO)l,NpYLO*YYSu.kBU!5r0$3`$g<!t[G2QFNO:cO<~%' );
define( 'SECURE_AUTH_SALT', '[2XNBp,0rBJz?ke,l3JVBdv %om!3ih!Q{.?fUTP]F4!62yM@,rYO#jQ1-54q.%i' );
define( 'LOGGED_IN_SALT',   'j1q8W|ZC0U 4?nWV1kSTFI]0l<2ifsg}D _eXevXLZv[v kazN^k$#7raCnOofUN' );
define( 'NONCE_SALT',       'tsX{~vGb|k=zE*sA3H8edEw<87)1}v-W&Jjx`/ef)oF4a*jQl_=6Q5$eY=Uxp{At' );

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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
