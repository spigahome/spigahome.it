<?php
/**
 * Theme functions file
 *
 * @package  Zoo_Theme
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */

/**
 * Load default constants
 *
 * @var  resource
 */
require get_template_directory() . '/core/const.php';

/**
 * Check if system meets requirements
 *
 * @see  https://developer.wordpress.org/reference/hooks/after_switch_theme/
 */
function zoo_theme_pre_activation_check($old_theme_name, WP_Theme $old_theme_object)
{
    try {
        if (version_compare(PHP_VERSION, '5.6', '<')) {
            throw new Exception(sprintf('Whoops, this theme requires %1$s version %2$s at least. Please upgrade %1$s to the latest version for better perfomance and security!', 'PHP', '5.6'));
        }

        if (version_compare($GLOBALS['wpdb']->db_version(), '5.0', '<')) {
            throw new Exception(sprintf('Whoops, this theme requires %1$s version %2$s at least. Please upgrade %1$s to the latest version for better perfomance and security.', 'MySQL', '5.6'));
        }

        if (version_compare($GLOBALS['wp_version'], '5.2', '<')) {
            throw new Exception(sprintf('Whoops, this theme requires %1$s version %2$s at least. Please upgrade %1$s to the latest version for better perfomance and security!', 'WordPress', '5.2'));
        }

        if (!defined('WP_CONTENT_DIR') || !is_writable(WP_CONTENT_DIR)) {
            throw new Exception('WordPress content directory is not writable. Please correct this directory permissions!');
        }
    } catch (Exception $e) {
        $die_msg = sprintf('<h1 class="align-center">'.esc_html__('Theme Activation Error', 'anon').'</h1><p class="zoo-active-theme-error" >%s</p><p class="align-center"><a href="%s">'.esc_html__('Return to dashboard', 'anon').'</a></p>', $e->getMessage(), get_admin_url(null, 'index.php'));
        switch_theme($old_theme_object->stylesheet);
        wp_die($die_msg, esc_html__('Theme Activation Error', 'anon'), 500);
    }

    add_option(ZOO_SETTINGS_KEY, [
        'header_scripts'  => '',
        'footer_scripts'  => '',
        'import_settings' => '',
        'enable_dev_mode' => 0,
        'enable_builtin_mega_menu' => 0,
        'mobile_breakpoint_width' => 992,
    ]);

    if (!is_child_theme()) {
        set_transient('theme_setup_wizard_redirect', '1');
    }
}
add_action('after_switch_theme', 'zoo_theme_pre_activation_check', 10, 2);

/**
 * Setup theme
 *
 * @see  https://developer.wordpress.org/reference/hooks/after_setup_theme/
 */
function zoo_theme_default_setup()
{
    $settings = get_option(ZOO_SETTINGS_KEY);

    register_nav_menus([
        'top-menu'     => esc_html__('Top Menu', 'anon'),
        'mobile-menu'  => esc_html__('Mobile Menu', 'anon'),
        'primary-menu' => esc_html__('Primary Menu', 'anon'),
    ]);

    // Load common resources
    require ZOO_THEME_DIR . 'core/common/functions/filesystem.php';
    require ZOO_THEME_DIR . 'core/common/functions/formatting.php';
    require ZOO_THEME_DIR . 'core/common/functions/customize.php';
    require ZOO_THEME_DIR . 'core/common/functions/layout.php';
    require ZOO_THEME_DIR . 'core/common/functions/plugin.php';
    require ZOO_THEME_DIR . 'core/common/functions/fonts.php';
    require ZOO_THEME_DIR . 'core/common/functions/media.php';
    require ZOO_THEME_DIR . 'core/common/functions/theme.php';
    require ZOO_THEME_DIR . 'core/common/functions/page.php';
    require ZOO_THEME_DIR . 'core/common/functions/css.php';
    require ZOO_THEME_DIR . 'core/common/hooks.php';

    // Load admin resources.
    if (is_admin()) {
        require ZOO_THEME_DIR . 'core/admin/functions/menu.php';
        if (class_exists('CleverAddons', false) && !class_exists('Clever_Mega_Menu', false) && !class_exists('CleverSoft\WpPlugin\Cmm4E\Plugin', false) && !empty($settings['enable_builtin_mega_menu'])) {
            require ZOO_THEME_DIR . 'core/admin/megamenu/class-menu-editor.php';
        }
        require ZOO_THEME_DIR . 'core/admin/pages/zoo-welcome-page.php';
        require ZOO_THEME_DIR . 'core/admin/pages/zoo-customize-page.php';
        require ZOO_THEME_DIR . 'core/admin/pages/zoo-settings-page.php';
        require ZOO_THEME_DIR . 'core/admin/migration/class-zoo-wxr-parser.php';
        require ZOO_THEME_DIR . 'core/admin/migration/class-zoo-wxr-importer.php';
        require ZOO_THEME_DIR . 'core/admin/migration/class-zoo-customize-importer.php';
        require ZOO_THEME_DIR . 'core/admin/migration/tgm-plugin-activation.php';
        require ZOO_THEME_DIR . 'core/admin/migration/class-zoo-demo-importer.php';
        require ZOO_THEME_DIR . 'core/admin/pages/zoo-setup-demo-page.php';
        require ZOO_THEME_DIR . 'core/admin/migration/class-zoo-setup-wizard.php';
        require ZOO_THEME_DIR . 'core/admin/hooks.php';
    } else { // Load public resources.
        require ZOO_THEME_DIR . 'core/public/megamenu/class-mega-menu-walker.php';
        require ZOO_THEME_DIR . 'core/public/breadcrumb/zoo-breadcrumb.php';
        require ZOO_THEME_DIR . 'core/public/functions/nav-menu.php';
        require ZOO_THEME_DIR . 'core/public/functions/pagination.php';
        require ZOO_THEME_DIR . 'core/public/functions/breadcrumb.php';
        require ZOO_THEME_DIR . 'core/public/hooks.php';
    }

    // Load extra theme functionality.
    require ZOO_THEME_DIR . 'inc/init.php';

    // Load customize resources.
    require ZOO_THEME_DIR . 'core/customize/class-zoo-customize-sanitizer.php';
    require ZOO_THEME_DIR . 'core/customize/class-zoo-customize-live-css.php';
    require ZOO_THEME_DIR . 'core/customize/class-zoo-customizer.php';
}
add_action('after_setup_theme', 'zoo_theme_default_setup', 9, 0);

///**
// * Register Elementor Locations.
// *
// * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
// */
//function hello_elementor_register_elementor_locations($elementor_theme_manager) {
//	$elementor_theme_manager->register_all_core_location(); // Full support.
//}
//add_action('elementor/theme/register_locations', 'hello_elementor_register_elementor_locations');
