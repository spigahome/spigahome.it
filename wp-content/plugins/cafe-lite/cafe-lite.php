<?php namespace Cafe;

/**
 * Plugin Name: Clever Addons for Elementor
 * Plugin URI:  http://www.zootemplate.com/wordpress-plugins/clever-addons-for-elementor
 * Description: An ultimate addon for Elementor Page Builder. | <a href=" https://doc.cleveraddon.com/clever-addon-for-elementor/">Documentation</a>
 * Author:      CleverSoft
 * Version:     2.0.1
 * Text Domain: cafe-lite
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
    const VERSION = '2.0.1';

    /**
     * Option key
     *
     * @var string
     */
    const OPTION_NAME = 'cafe_plugin_settings';

    /**
     * Settings
     *
     * @var array
     */
    private $settings;

    /**
     * Constructor
     */
    function __construct(array $settings = [])
    {
        $this->settings = $settings;

        // Define constants.
        define('CAFE_DIR', __DIR__ . '/');
        define('CAFE_URI', str_replace(['http:', 'https:'], '', plugins_url('/', __FILE__)));

        // Bind important events.
        add_action('plugins_loaded', [$this, '_install'], 10, 0);
        add_action('activate_cafe-lite/cafe-lite.php', [$this, '_activate']);
        add_action('deactivate_cafe-lite/cafe-lite.php', [$this, '_deactivate']);
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), [ $this, '_quickLinks' ]);
    }

    /**
     * Add quick access links
     *
     * @internal Callback.
     */
	public function _quickLinks($links)
    {
		$settings_link = sprintf('<a href="%1$s">%2$s</a>', admin_url( 'admin.php?page=clever-addons-for-elementor'), __('Settings', 'cafe-lite') );

		array_unshift( $links, $settings_link );

        $links['go_pro'] = sprintf('<a style="font-weight:700;color:#ff9421" href="%1$s" target="_blank" class="plugin-gopro">%2$s</a>', esc_url('https://cleveraddon.com/clever-addon-for-elementor/'), __('Go Pro', 'cafe-lite'));

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
    function _activate($network)
    {
        // Maybe do something on activation.
    }

    /**
     * Do installation
     *
     * @internal Used as a callback.
     *
     * @see https://developer.wordpress.org/reference/hooks/plugins_loaded/
     */
    function _install()
    {
	    if(!did_action('elementor/loaded')) {
		    add_action('admin_notices', function() {
			    if(!is_plugin_active('elementor/elementor.php')) {
				    if(!current_user_can('activate_plugins')) return;
				    $activation_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=elementor/elementor.php&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_elementor/elementor.php');
				    $message = esc_html__('<strong>Clever Elementor Addon</strong> requires Elementor Page Builder plugin to be active. Please install and activate Elementor Page Builder!', 'cafe-lite');
				    $button_text = esc_html__('Activate Elementor', 'cafe-lite');
			    } else {
				    if(!current_user_can('activate_plugins')) return;
				    $activation_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=elementor'), 'install-plugin_elementor');
				    $message = esc_html__('<strong>Clever Elementor Addon</strong> requires Elementor Page Builder plugin to be active. Please install and activate Elementor Page Builder!', 'cafe-lite');
				    $button_text = esc_html__('Install Elementor', 'cafe-lite');
			    }
			    $button = '<p><a href="' . $activation_url . '" class="button-primary">' . $button_text . '</a></p>';
			    printf('<div class="error"><p>%1$s</p>%2$s</div>', $message, $button);
		    }, 10, 0);
	    }

        // Make sure translation is available.
        load_textdomain( 'cafe-lite', CAFE_DIR.'languages/cafe-lite-'.determine_locale().'.mo' );
        load_plugin_textdomain('cafe-lite', false, CAFE_DIR.'languages');

        if (is_admin()) {
            require CAFE_DIR.'src/class-cafe-settings-page.php';
        }

        // Load resources.
        require CAFE_DIR . 'src/helpers/helper.php';
        if (class_exists('WooCommerce')) {
            require CAFE_DIR . 'src/helpers/wc.php';
        }
        require CAFE_DIR . 'src/class-clever-elements-category.php';
        require CAFE_DIR . 'src/class-clever-widgets-manager.php';
        require CAFE_DIR . 'src/class-clever-assets-manager.php';
        require CAFE_DIR . 'src/class-clever-controls-manager.php';
        require CAFE_DIR . 'src/class-cafe-template-kit.php';

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
    function _deactivate($network)
    {
        // Maybe do something on deactivation.
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

        if (!class_exists('Elementor\Plugin')) {
            throw new Exception('This plugin requires Elementor Page Builder version 2.3.2 at least. Please install and activate the latest version of Elementor Page Builder!');
        }
    }
}

// Initialize plugin.
return new Plugin((array)get_option(Plugin::OPTION_NAME, []));
