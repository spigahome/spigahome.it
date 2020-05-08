<?php namespace CafePro;

/**
 * Plugin Name: Clever Addons Pro for Elementor
 * Plugin URI:  http://www.zootemplate.com/wordpress-plugins/clever-addons-for-elementor
 * Description: Add premium features to Clever Addons for Elementor. | <a href="http://doc.zootemplate.com/cafe-pro/">Documentation</a> | <a href="http://doc.zootemplate.com/cafe-pro/#/changelog">ChangeLogs</a>
 * Author:      CleverSoft
 * Version:     1.2.0
 * Text Domain: cafe-pro
 * Requires PHP: 5.6
 */

use Exception;

/**
 * Plugin container.
 */
final class Plugin
{
    /**
     * Version
     *
     * @var string
     */
    const VERSION = '1.2.0';

    /**
     * Settings
     *
     * @var array
     */
    private $settings;

    /**
     * Constructor
     */
    public function __construct(array $settings = [])
    {
        $this->settings = $settings;

        // Define constants.
        define('CAFE_PRO_DIR', __DIR__ . '/');
        define('CAFE_PRO_URI', str_replace(['http:', 'https:'], '', plugins_url('/', __FILE__)));

        // Bind important events.
        add_action('plugins_loaded', [$this, '_install'], 10, 0);
        add_action('activate_cafe-pro/cafe-pro.php', [$this, '_activate']);
        add_action('deactivate_cafe-pro/cafe-pro.php', [$this, '_deactivate']);
        add_action('elementor/init', [$this, '_registerWidgetsCategory'], 10, 0);
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, '_quickLinks']);
    }

    /**
     * Add quick access links
     *
     * @internal Callback.
     */
	public function _quickLinks($links)
    {
		$settings_link = sprintf('<a href="%1$s">%2$s</a>', admin_url( 'admin.php?page=clever-addons-for-elementor'), __('Settings', 'cafe-pro') );

		array_unshift($links, $settings_link);

		return $links;
	}

    /**
     * Do activation
     *
     * @internal Used as a callback.
     *
     * @see https://developer.wordpress.org/reference/functions/register_activation_hook/
     *
     * @param bool $network Whether to activate this plugin on network or a single site.
     */
    public function _activate($network)
    {

    }

    /**
     * Do installation
     *
     * @internal Used as a callback.
     *
     * @see https://developer.wordpress.org/reference/hooks/plugins_loaded/
     */
    public function _install()
    {
        add_action('admin_notices', function () {
            if (!current_user_can('activate_plugins') || is_plugin_active('cafe-lite/cafe-lite.php')) {
                return;
            } elseif (!is_plugin_active('cafe-lite/cafe-lite.php')) {
                $activation_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=cafe-lite/cafe-lite.php&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_cafe-lite/cafe-lite.php');
                $message = __('<strong>Clever Addons Pro for Elementor</strong> requires Clever Addons for Elementor plugin to be active. Please install and activate Clever Addons for Elementor!', 'cafe-pro');
                $button_text = __('Activate Clever Addons for Elementor', 'cafe-pro');
            } else {
                $activation_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=cafe-lite'), 'install-plugin_cafe-lite');
                $message = __('<strong>Clever Addons Pro for Elementor</strong> requires Clever Addons for Elementor plugin to be active. Please install and activate Clever Addons for Elementor!', 'cafe-pro');
                $button_text = __('Install Clever Addons for Elementor', 'cafe-pro');
            }
            $button = '<p><a href="' . $activation_url . '" class="button-primary">' . $button_text . '</a></p>';
            printf('<div class="error"><p>%1$s</p>%2$s</div>', $message, $button);
        }, 10, 0);

        // Make sure translation is available.
        load_textdomain( 'cafe-pro', CAFE_PRO_DIR.'languages/cafe-lite-'.determine_locale().'.mo' );
        load_plugin_textdomain('cafe-pro', false, CAFE_PRO_DIR.'languages');

        // Load resources.
        require CAFE_PRO_DIR . 'src/class-clever-widgets-manager.php';
        require CAFE_PRO_DIR . 'src/class-clever-assets-manager.php';
        require CAFE_PRO_DIR . 'src/class-clever-controls-manager.php';
        require CAFE_PRO_DIR . 'src/class-clever-documents-manager.php';
        require CAFE_PRO_DIR . 'src/class-clever-admin-pages-manager.php';
        require CAFE_PRO_DIR . 'src/class-clever-functions.php';
        require CAFE_PRO_DIR . 'src/metaboxes/header-attributes.php';
        require CAFE_PRO_DIR . 'src/metaboxes/footer-attributes.php';
    }

    /**
     * Do deactivation
     *
     * @internal Used as a callback.
     *
     * @see https://developer.wordpress.org/reference/functions/register_deactivation_hook/
     *
     * @param bool $network  Whether to deactivate this plugin on network or a single site.
     */
    public function _deactivate($network)
    {
    }

    /**
     * Register builder category
     *
     * @internal
     */
    function _registerWidgetsCategory()
    {
        $elementor = \Elementor\Plugin::instance();

        $elementor->elements_manager->add_category('clever-builder-elements', [
            'title' => esc_html__('Header/Footer Elements', 'cafe-pro'),
            'icon'  => 'fa fa-asterisk',
        ], 5);
    }

    /**
     * Pre-activation check
     *
     * @throws Exception
     */
    private function preActivate()
    {
        if (version_compare(PHP_VERSION, '5.6', '<')) {
            throw new Exception('This plugin requires PHP version 5.6 at least!');
        }

        if (version_compare($GLOBALS['wp_version'], '4.7', '<')) {
            throw new Exception('This plugin requires WordPress version 4.7 at least!');
        }

        if (!class_exists('Cafe\Plugin')) {
            throw new Exception('This plugin requires Cafe lite version 1.0.0 at least. Please install and activate the latest version of Elementor Page Builder!');
        }

        if (!class_exists('Elementor\Plugin')) {
            throw new Exception('This plugin requires Elementor Page Builder version 2.3.2 at least. Please install and activate the latest version of Elementor Page Builder!');
        }
    }
}

// Initialize plugin.
return new Plugin();
