<?php
define('WP_CACHE', true); // WP-Optimize Cache
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
define( 'DB_NAME', 'bomra' );
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
define( 'AUTH_KEY',         '^N/tLl;?/sK^F<B<P4^_4F3Ow^$rA?>/dRU[56[sUBs3+;cyV_aa$z<`?wNeiR.f' );
define( 'SECURE_AUTH_KEY',  '+@z&+-fEDQTAlq_GFaF)rK9!N~),NI3dBQkwuREw~bb V&DYe_[?bgM(nd?U>`W,' );
define( 'LOGGED_IN_KEY',    '1`&/)VHPb#cstb%~__jp0NR5R3~m&ce 9}C_oELlkhcSsQ3~5sRC)m3F +4^ZLp,' );
define( 'NONCE_KEY',        '1ytsBvrjc^?+H[gT#|e2/U3.)n?qf)j+1s`qB>cY$9D!<8mh6tXfmlH2Dye%2hF,' );
define( 'AUTH_SALT',        '5LP.38BrmCt[L,~n?4_xPlQX|^bmOWB2[f{lS$i2MaXPpR b4oyiS6/@63CV2GKE' );
define( 'SECURE_AUTH_SALT', '1DvY4=UbtG1fGLF~IhSR8B_I}]RYKaImy*$M,Z4FzVUK!MOK&&p`])fBJ1|:W &|' );
define( 'LOGGED_IN_SALT',   'Ndk~}.bm_fl!$>_Q1vu2eA[kJ~LHl]?q.%*_&(]njfO)YG(^6F`$ADY8r1G ^/l_' );
define( 'NONCE_SALT',       'n06a6wI_/`2VCL8JqWlhr76;H^.]6j&p}^7}zOFJ zS/t;/|LpQ`cM&M@-2>@w4@' );
/**#@-*/
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'bomra_';
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
/* DISABLE AUOMATIC WORDPRESS UPGRADE */
define( 'WP_AUTO_UPDATE_CORE', false );
/*Disable automatic WordPress theme updates*/
// add_filter( 'auto_update_theme', '__return_false' );
/* That's all, stop editing! Happy publishing. */
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}
/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';