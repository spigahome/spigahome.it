<?php
/**
 * Admin Hooks
 *
 * Hooks used on admin screens only.
 *
 * @package  Zoo_Theme\Core\Admin
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */

/**
 * @see  https://developer.wordpress.org/reference/hooks/admin_enqueue_scripts/
 */
add_action('admin_enqueue_scripts', function($hook_suffix)
{
    if (isset($_GET['page']) && 'zoo-theme-settings' === $_GET['page']) {
        wp_add_inline_style('dashicons', '#wpcontent{padding-left:0 !important}');
    }

    if (isset($_GET['page']) && 'zoo-theme-setup-demo' === $_GET['page']) {
        wp_add_inline_style('dashicons', '#wpcontent #wpbody #wpbody-content .notice{display:none !important}');
    }

    wp_enqueue_media();

    wp_enqueue_style('zoo-theme-admin', ZOO_THEME_URI.'core/assets/css/admin.min.css', array('dashicons'), ZOO_THEME_VERSION);

    wp_enqueue_script('zoo-theme-admin', ZOO_THEME_URI.'core/assets/js/admin.min.js', array('jquery-core'), ZOO_THEME_VERSION, true);
    wp_localize_script('zoo-theme-admin', 'zooAdminL10n', [
        'install' => esc_html__('Install', 'anon'),
        'installing' => esc_html__('Installing...', 'anon'),
        'installed' => esc_html__('Installed', 'anon'),
        'uninstall' => esc_html__('Uninstall', 'anon'),
        'uninstalling' => esc_html__('Uninstalling...', 'anon')
    ]);
});
