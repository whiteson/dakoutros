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
define( 'DB_NAME', 'dakoutros' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'password' );

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
define( 'AUTH_KEY',         'Al%JhJM,Dl]T{D.J4,oiA@ZIFG4mO8J[[/1l(.B=sMk;X_~vZ-d5c[n}`d``NfR<' );
define( 'SECURE_AUTH_KEY',  '(#0NH9VV1kQ7/f%,zAweuAH+1Gya_;-q 2b#qTfZ&yB&xg:_1#:B}BPMN*rD.-IC' );
define( 'LOGGED_IN_KEY',    'd_Tkrtp@TjvHaD[{.K%LG0(%#8=J&wqT|uc#?T.-~n31GMHJq^gqa7)9Y2JQ,Y}b' );
define( 'NONCE_KEY',        '@UH*-:A21:~5g-#F%RW6x2Kg}3Gs}}-:C(!xS5zBRWIrjlkW2Pj5}U3:<hVCX5ld' );
define( 'AUTH_SALT',        '+o; tR9y82IN&X0N/v[,=[n!4NzDv`<iH{QqK b8yE<fAe`+t]DypZ-yX.6|p=v*' );
define( 'SECURE_AUTH_SALT', 'mhwonStaa~NWIc,T$&fzI15cARj<xl:l#)s<$5s MDYBJ)7N8~W_r#p]XMQ<4khV' );
define( 'LOGGED_IN_SALT',   'b;.YGD%#IY{76y9wi<S7ITJ]jZ^OmTb+z6)4x>pbE/Wi8Xs({3jvqg|>6wQ#o(eu' );
define( 'NONCE_SALT',       'xkGAiQ3iH6hVL-[UK}<L205) d4@q0Ge.y%6TM;,vJ^?36mkR #&zY%5r-2tBJ@R' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wrdprsgr_';

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
