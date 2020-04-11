<?php
/**
 * Core Constants
 *
 * @package  Zoo_Theme\Core
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */

/**
 * Childtheme slug
 *
 * @var  string
 */
define('ZOO_CHILD_THEME_SLUG', get_option('stylesheet', 'zootemplate-child'));

/**
 * Theme settings key
 *
 * Key to store settings other than theme mods.
 *
 * @var  string
 */
define('ZOO_SETTINGS_KEY', 'zoo_theme_settings_for_' . get_option('template', 'anon'));

/**
 * Theme version
 *
 * @var  string
 */
define('ZOO_THEME_VERSION', wp_get_theme()->version);

/**
 * Theme base path
 *
 * @var  string
 */
define('ZOO_THEME_DIR', wp_normalize_path(get_template_directory() . '/'));

/**
 * Theme base uri
 *
 * @see  https://google.github.io/styleguide/htmlcssguide.xml?showone=Protocol#Protocol
 *
 * @var  string
 */
define('ZOO_THEME_URI', preg_replace('/^http(s)?:/', '', get_template_directory_uri()) . '/');

/**
 * CSS file suffix
 *
 * @var  string
 */
define('ZOO_CSS_SUFFIX', (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '.css' : '.min.css');

/**
 * JS file suffix
 *
 * @var  string
 */
define('ZOO_JS_SUFFIX', (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '.js' : '.min.js');
