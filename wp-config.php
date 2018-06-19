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
define('DB_NAME', 'sportklubben');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'password');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ']><h[N M.4Ek]A%)F]@w*d=-GWS&9}G2h&S>`Ma#WbFf{_z)BUi5/o.d8?{|H]D ');
define('SECURE_AUTH_KEY',  'CiE3sRtWRaEIA<WBW6EQK_]Woh.NwY%)t*yB-5B&K`nOr-~GgwtTO+74;KjW_oii');
define('LOGGED_IN_KEY',    '{|SYwv9Cr>}}m$&7$+`kY*vQ6YDcbMWs;D?xq/xk{hAvxD^BF&(W9,-8@c)T@g+}');
define('NONCE_KEY',        'M|LSFo*jeG+.mna_iu*rH]{#X+.TroL }-e2T@s@`^?+>1@eGkn0Ugu.XQ~maE,W');
define('AUTH_SALT',        'Tv1X9fuIZ$+Z8@LcFru=$9l%t>yWf{>?@mQL7T.IUf`#i#[L8{*jqiae_f0j+PY)');
define('SECURE_AUTH_SALT', '3[zw?2-C}A.=Fzx0q(DhzSGVOO,{/<2,i{D#7o;;9$Jm35Xma7;V:/$(w3z*.>w]');
define('LOGGED_IN_SALT',   '[/*MMd4d;@M2FJy,|SLXzm<s(3!<Ncq4%sR@8NR;X(3oswI^~el<Hge+3P_T7,YJ');
define('NONCE_SALT',       'DfC5nhu;vaY!#8]6[4EgL~Wu#EH_!So_~.gU3]y}}*jvB|Q9$0. #fg/R$ a8z6:');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
