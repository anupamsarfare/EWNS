<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'ewns');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         '|-$4i*Qi^g+-T=:w]L5?SnRw`05B3ly,g{z5]/b6#4Xh^wLKK@8QQEmtLA{,5S!h');
define('SECURE_AUTH_KEY',  '%(AVyFwvW&4l+%cezn+dQyB&M/`++&TuOt9~ip<O)-u:oW-U`hj1QRLc7>Wd0OG.');
define('LOGGED_IN_KEY',    'N`S1U9L.D_gDw=vF/EWh|2U,|s}S4-8`*j5d&-lMWW*|q2T.Q7)QZIc5op%p:m6@');
define('NONCE_KEY',        'lzi0HjNA9-9!cvG `xEp2wh[awnnQ&75nJ(}-o[Qe+&mmZ<Xsk,`~P=--,a]f&dH');
define('AUTH_SALT',        ')v|+H-,J|,DU/f5`U<ZPn-dVd;gPP:U+:@z#/#O[^bT$&Fa(J1#;8mWn~_r-J|Cx');
define('SECURE_AUTH_SALT', 'L?)A:*Q[k?+/2;j(>A):jp9$C}{G;Kf9%(l7_28n!I*Eu,X|pB@)[o+`@!~?=|_+');
define('LOGGED_IN_SALT',   '_g1&m2(;c7$2wLc9fR(7irPz+.9l:l&g*zr^|ho^[<7#8eP*MYoIU6C[9hK<Y:#K');
define('NONCE_SALT',       'U.CteU?A+?Q;8l,.FzQjV/,-pNcM%X5/(9`a{WU9F5l:+3bSTW1mkEMVDs!u9Fw?');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
