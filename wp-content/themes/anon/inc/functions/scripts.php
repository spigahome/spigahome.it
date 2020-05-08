<?php
/**
 * Register theme Scripts/Style.
 *
 * @package     Zoo Theme
 * @version     3.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 * @des         All js, css of theme user must register in this file. Dev can remove or delete js/css don't use.
 */

/**
 * Load theme style
 */
if (!function_exists('zoo_theme_styles')) {
    function zoo_theme_styles()
    {
        // Bootstrap 4.1
        wp_enqueue_style('bootstrap', ZOO_THEME_URI . 'assets/vendor/bootstrap/bootstrap-grid.min.css');

        wp_register_style('slick', ZOO_THEME_URI . 'assets/vendor/slick/slick.css');

        if (class_exists('WooCommerce', false)) {
            wp_deregister_style('woocommerce-layout');
            wp_deregister_style('woocommerce-smallscreen');
            //Remove style don't use.
            wp_deregister_style('woocommerce_prettyPhoto_css');
            wp_deregister_style('jquery-selectBox');
            //Custom Woocommerce Css
            wp_enqueue_style('zoo-woocommerce', ZOO_THEME_URI . 'assets/css/zoo-woocommerce.css');
        }
        wp_enqueue_style('zoo-styles', ZOO_THEME_URI . 'assets/css/zoo-styles.css');

        //Load style of customize
        $zoo_auto_css = Zoo_Customize_Live_CSS::get_instance();
        wp_add_inline_style('zoo-styles', $zoo_auto_css->auto_css());

        // Main style
        wp_enqueue_style('anon', get_stylesheet_uri());

    }
}
add_action('wp_enqueue_scripts', 'zoo_theme_styles', 999);

/**
 * Load theme Script
 */
if (!function_exists('zoo_theme_scripts')) {
    function zoo_theme_scripts()
    {
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
        wp_register_script('slick', ZOO_THEME_URI . 'assets/vendor/slick/slick.min.js', array('jquery-core'), '1.9.0', true);
        wp_register_script('isotope', ZOO_THEME_URI . 'assets/vendor/isotope/isotope.pkgd.min.js', array('jquery-core'), '3.0.6', true);
        wp_enqueue_script('defer-js', ZOO_THEME_URI . 'assets/vendor/defer/defer.min.js', array('jquery-core'), '1.1.8', true);
        wp_register_script('countdown', ZOO_THEME_URI . 'assets/vendor/countdown/countdown.js', array('jquery-core'), false, true);
        wp_register_script('zoo-scripts', ZOO_THEME_URI . 'assets/js/zoo-scripts.js', array('jquery-core'), false, true);
        wp_enqueue_script('sticky-kit', ZOO_THEME_URI . 'assets/vendor/sticky-kit/jquery.sticky-kit.min.js', array('jquery-core'), false, true);

        if (class_exists('WooCommerce', false)) {
	        wp_register_script('spritespin', ZOO_THEME_URI . 'assets/vendor/spritespin/spritespin.js', array('jquery-core'), false, true);
            if (get_theme_mod('zoo_enable_quick_view', '1') == 1) {
                wp_enqueue_script('countdown');
                wp_enqueue_script('wc-add-to-cart-variation');
            }
            wp_enqueue_script('zoo-woo-ajax', ZOO_THEME_URI . 'assets/js/zoo-woo-ajax.js', array('jquery-core'), false, true);
            wp_register_script('zoo-product-embed', ZOO_THEME_URI . 'assets/js/zoo-product-embed.js', array('jquery-core'), false, true);
            wp_enqueue_script('zoo-woocommerce', ZOO_THEME_URI . 'assets/js/zoo-woocommerce.js', array('jquery-core'), false, true);
        }

        wp_enqueue_script('zoo-scripts');
    }
}
add_action('wp_enqueue_scripts', 'zoo_theme_scripts');

/**
 * Add pingback
 */
function zoo_pingback_header() {
    if ( is_singular() && pings_open() ) {
        printf( '<link rel="pingback" href="%s">' . "\n", esc_url(get_bloginfo( 'pingback_url' ) ));
    }
}
add_action( 'wp_head', 'zoo_pingback_header' );

/**
 * Ajax Url
 */
add_action('wp_enqueue_scripts', 'zoo_framework_ajax_url_render', 1000);
// Enqueue scripts for theme.
if (!function_exists('zoo_framework_ajax_url_render')) {
    function zoo_framework_ajax_url_render()
    {
        // Load custom style
        wp_add_inline_script('zoo-scripts', zoo_framework_ajax_url());
    }
}
if (!function_exists('zoo_framework_ajax_url')) {
    function zoo_framework_ajax_url()
    {
        $ajaxurl = 'var ajaxurl = "' . esc_url(admin_url('admin-ajax.php')) . '";';
        return $ajaxurl;
    }
}
