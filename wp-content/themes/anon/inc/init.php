<?php
/**
 * Theme functions and definitions
 *
 * @package     Zoo Theme
 * @version     1.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 
 */

/**
 * Theme functions
 * All theme functions will load below.
 */
include ZOO_THEME_DIR.'inc/functions/helper.php';
include ZOO_THEME_DIR.'inc/functions/features.php';
include ZOO_THEME_DIR.'inc/functions/sidebars.php';
include ZOO_THEME_DIR.'inc/functions/scripts.php';
include ZOO_THEME_DIR.'inc/functions/plugins.php';
include ZOO_THEME_DIR.'inc/functions/functions.php';
include ZOO_THEME_DIR.'inc/functions/lazy-img.php';
include ZOO_THEME_DIR.'inc/functions/hooks.php';


/**
 * WooCommerce theme functions
 * All hooks, functions, features will load below.
 */
if (class_exists('WooCommerce', false)) {
    require ZOO_THEME_DIR.'inc/woocommerce/woocommerce.php';
}

/**
 * Theme customize and metaboxes
 */
require ZOO_THEME_DIR.'inc/metaboxes/meta-boxes.php';
require ZOO_THEME_DIR.'inc/customize/customize-style.php';

/**
 * Custom function for Edd plugin.
 * All theme functions will load below.
 */
include ZOO_THEME_DIR.'inc/edd/custom-edd.php';
/**
 * WooCommerce theme functions
 * All hooks, functions, features will load below.
 */
if (class_exists('Cafe\Plugin', false)) {
    require ZOO_THEME_DIR.'cafe/cafe-custom-widgets.php';
}