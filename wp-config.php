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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'fastfood' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',         'U;1U;54*q9{RgCLnv%{8^F4%ibgcECoNl5.XymvaL;!4&`7f&hG9M`=I d#*#q.;' );
define( 'SECURE_AUTH_KEY',  'h%Sa+)bj /PSCO(vl.<Oiq7e]!|tROO1I5CqL8:VAy`Z XIE|rJO5:v#$%GEUP>!' );
define( 'LOGGED_IN_KEY',    '8hM:55Sjt5Fvo~$Kt(,o?(DASpuanWMKA7di<uZG8-f[504K.->.)Z!6Vw.Q/heW' );
define( 'NONCE_KEY',        'm3dq_L/Dw[fi34$2,;<}B+}dP])).5]{SF]=&Hx4I0{OK0,AXX~J]i@j7G}$X{=u' );
define( 'AUTH_SALT',        'F.)G9@S6OuFl ??|=gs7QA::u^>(*kxZ*9Z>&^|0F6x~GQZ0-tRCl7A`=HlI$hA(' );
define( 'SECURE_AUTH_SALT', 'rqIfpl|*u0MnC=@jKceWnbBY:NH`&W*q:I,w.%e#z{sAe{)wbs7_/ASnk3~dSTU~' );
define( 'LOGGED_IN_SALT',   'N1D}I@D0mMna7_6@S~W:]kDA+lQr (IuNgsNGtFuRD>9(!G9zD]!$##!TN4U~D_u' );
define( 'NONCE_SALT',       ')`LZ|G 2YE;#aAT[koUH,IBQ46^tCM3axm)JJhTzF/@W2DA->H[EmBQF?OdaGs-o' );

/**#@-*/

/**
 * WordPress database table prefix.
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

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
