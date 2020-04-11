<?php
/**
 * Plugin Name: Clever Layered Navigation
 * Description: WooCommerce AJAX Layered Navigation, WooCommerce Product Filter adds advanced product filtering to your WooCommerce shop. <a href="https://cleveraddon.com" target="_blank">Documentation</a> | <a href="https://cleveraddon.com" target="_blank">ChangeLogs</a>
 * Version: 1.3.8
 * Author: CleverAddon
 * Author URI: https://cleveraddon.com/
 * Requires at least: 4.6.1
 * Tested up to: 5.2.4
 *
 * WC requires at least: 3.3.2
 * WC tested up to: 3.8.0
 *
 * Text Domain: clever-layered-navigation
 * Domain Path: languages/
 *
 * @package clever-layered-navigation
 * @author cleversoft.co
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Cheating, Uh!
}


//defined all Constant
$plugin_path = plugin_dir_path( __FILE__ )."/";
$plugin_url = plugin_dir_url( __FILE__ );

define( 'ZOO_LN_VERSION', '1.3.8' );
define( 'ZOO_LN_DIRPATH', $plugin_path );
define( 'ZOO_LN_TEMPLATES_PATH', $plugin_path."templates/" );
define( 'ZOO_LN_URL', $plugin_url );
define( 'ZOO_LN_ADMIN_MENU_SLUG', 'zoo-ln-settings' );
define( 'ZOO_LN_JSPATH', $plugin_url."assets/js/" );
define( 'ZOO_LN_CSSPATH', $plugin_url."assets/css/" );
define( 'ZOO_LN_VENDOR', $plugin_url."assets/vendor/" );
define( 'ZOO_LN_GALLERYPATH', $plugin_url."assets/images/" );


//include helper functions
require_once(plugin_dir_path(__FILE__) . 'helper/data.php');


//router admin or frontend
if (Zoo\Helper\Data\zoo_ln_check_woocommerce_active()) {
    if (is_admin()) {
        //add hook admin
        require_once(ZOO_LN_DIRPATH . 'admin/hook.php');
        require_once ZOO_LN_DIRPATH.'admin/ajax.php';
    }
    require_once(ZOO_LN_DIRPATH . 'frontend/ajax.php');
    require_once(ZOO_LN_DIRPATH . 'frontend/hook.php');
} else {
    if (is_admin()) {
        /** @internal */
        function zoo_cln_woo_missing_notice()
        {
            echo '<div class="error"><p>' . sprintf(__('Clever Layered Navigation requires WooCommerce to be installed and active. You can download %s here.', 'clever-layered-navigation'), '<a href="http://www.google.com/" target="_blank">WooCommerce</a>') . '</p></div>';
        }

        /** @internal */
        function zoo_cln_plugin_deactivate()
        {
            deactivate_plugins(plugin_basename(__FILE__));
            add_action('admin_notices', 'zoo_cln_woo_missing_notice');
            if (isset($_GET['activate'])) {
                unset($_GET['activate']);
            }
        }
        add_action('admin_init', 'zoo_cln_plugin_deactivate');
    }
}

// register Foo_Widget widget
function zoo_ln_register_widget() {
    require ZOO_LN_DIRPATH.'admin/widget.php';
    register_widget( 'Zoo_Ln_Widget' );
}

add_action( 'widgets_init', 'zoo_ln_register_widget' );
function zoo_ln_admin_script()
{
    wp_enqueue_style('zoo-ln', ZOO_LN_CSSPATH . 'admin/zoo-ln-style.css');
}
add_action( 'admin_enqueue_scripts', 'zoo_ln_admin_script' );



// On `plugins_loaded` functions
function clever_layered_nav_plugin_loaded()
{
    // Make sure translation is available.
    load_plugin_textdomain('clever-layered-navigation', false, dirname(plugin_basename( __FILE__ )) . '/languages');

    // Load functions.
    require __DIR__ . '/frontend/functions.php';
}
add_action('plugins_loaded', 'clever_layered_nav_plugin_loaded', 11, 0);
