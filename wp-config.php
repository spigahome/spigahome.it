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
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'wordpress' );

/** MySQL database password */
define( 'DB_PASSWORD', 'XnA1cscY' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          '#Oij6`Jg6YN{Q/d)V-+QN_J3}9DG(wxJr;~uBl(%lW?oo<MHVD3Esbdv3uP>2us2' );
define( 'SECURE_AUTH_KEY',   '4a[0&WUJp?PuFl*|X}pn)6&CmVO/681Z,d@Z)//RH_L(t3wsEtdq~oudkRGw@ms6' );
define( 'LOGGED_IN_KEY',     '91>N UpjJ C[*xd)=9c6$GLtj#Io404GlZ<*P-AOa]0_W2IN6KvREr?U*8nc yZ(' );
define( 'NONCE_KEY',         '>jDfE[Y_,%0XE@}D}DtBp>LrO8tntyB3MfvWWCSrv/6Q vvpgR,1MO`h!N~0qrmQ' );
define( 'AUTH_SALT',         '8zP[7`gGZL5ZPtRg({E%7B%I:)u$&V`[MhWIC(Pak-<?NHd?W_xH0d5<d3Di2YgC' );
define( 'SECURE_AUTH_SALT',  ';f--1`p3.K?U0wngKg=^8Nte/;Q>zfE:0aVM=4=l|}O/VQO[U4B)yt/owcUj,O?@' );
define( 'LOGGED_IN_SALT',    'e0RL6oh4*+ts0MPn7!?}QE2uP>KfNO7(y0YX$-WN~d14rJ/%/^O*901;!ef)zqYu' );
define( 'NONCE_SALT',        '+At^^q&uG.B]8Fc4j?P^6V%bg_j6_`i^9s~b>Pbf_yVx:rJ:N/XS1U2(k4hk=JMi' );
define( 'WP_CACHE_KEY_SALT', 'Wg,atd,c9,DsonVcSNemhtGfr)o:Fd+8Yq7au z%{ wwVxKyGmwNH#KRUVI5{sVn' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
