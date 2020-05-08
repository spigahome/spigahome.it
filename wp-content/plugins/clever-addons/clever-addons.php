<?php
/**
 * Plugin Name: Clever Addons
 * Plugin URI:  http://wpplugin.zootemplate.com/clever-addons
 * Description: WordPress theme framework from Zootemplate.
 * Author:      Zootemplate
 * Version:     1.0.0
 * Author URI:  http://zootemplate.com
 * Text Domain: clever-addons
 */
final class CleverAddons
{
    /**
     * Version
     *
     * @var    string
     */
    const VERSION = '1.0.0';

    /**
     * Option name
     *
     * @var    string
     */
    const OPTION_NAME = 'zoo_framework_settings';

    /**
     * Hook suffix
     *
     * @var  string
     */
    public $hook_suffix;

    /**
     * Plugin settings
     *
     * @var    array
     */
    private $settings;

    /**
     * Constructor
     */
    private function __construct()
    {
        $this->settings = get_option(self::OPTION_NAME) ? : array();
        $this->settings['basedir']  = __DIR__ . '/';
        $this->settings['baseuri']  = preg_replace('/^http(s)?:/', '', plugins_url('/', __FILE__));
    }

    /**
     * Singleton
     */
    static function getInstance()
    {
        static $instance = null;

        if (null === $instance) {
            $instance = new self;
            register_activation_hook(__FILE__, array($instance, '_activate'));
            register_deactivation_hook(__FILE__, array($instance, '_deactivate'));
            add_action('plugins_loaded', array($instance, '_install'), 10, 0);
            register_uninstall_hook(__FILE__, 'CleverAddons::_uninstall');
        }
    }

    /**
     * Do activation
     *
     * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
     *
     * @see    https://developer.wordpress.org/reference/functions/register_activation_hook/
     *
     * @param    bool    $network    Whether activating this plugin on network or a single site.
     */
    function _activate($network)
    {
        try {
            $this->preActivate();
        } catch (Exception $e) {
            exit($e->getMessage());
        }

        add_option(self::OPTION_NAME, array(
            'header_scripts'  => '',
            'footer_scripts'  => '',
            'google_map_api'  => '',
            'google_font_api' => '',
            'import_settings' => '',
        ));
    }

    /**
     * Do installation
     *
     * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
     *
     * @see    https://developer.wordpress.org/reference/hooks/plugins_loaded/
     */
    function _install()
    {
        load_plugin_textdomain('zoo-framework', false, $this->settings['basedir'] . 'i18n');

        require $this->settings['basedir'] . 'vendor/vaf/vp-post-formats-ui.php';
        require $this->settings['basedir'] . 'src/helpers/formatting.php';
        require $this->settings['basedir'] . 'src/helpers/custom-functions.php';
        require $this->settings['basedir'] . 'src/classes/class-zoo-logger.php';
        require $this->settings['basedir'] . 'src/classes/class-clever-one-pixel.php';
        require $this->settings['basedir'] . 'src/post-types/service.php';
        require $this->settings['basedir'] . 'src/post-types/teammate.php';
        require $this->settings['basedir'] . 'src/post-types/testimonial.php';
        require $this->settings['basedir'] . 'src/metaboxes/default.php';
        if(!function_exists('clever_elementor_header_attributes_metabox')) {
            require $this->settings['basedir'] . 'src/metaboxes/header-attributes.php';
        }
        if(!function_exists('clever_elementor_footer_attributes_metabox')) {
            require $this->settings['basedir'] . 'src/metaboxes/footer-attributes.php';
        }
        require $this->settings['basedir'] . 'src/widgets/about-me.php';
        require $this->settings['basedir'] . 'src/widgets/icon-field.php';
        require $this->settings['basedir'] . 'src/widgets/img-hover.php';
        require $this->settings['basedir'] . 'src/widgets/payment-list.php';
        require $this->settings['basedir'] . 'src/widgets/widget-social.php';
        require $this->settings['basedir'] . 'src/widgets/recent-post.php';

        if (function_exists('Sensei')) {
            require $this->settings['basedir'] . 'src/classes/class-zoo-sensei-settings-import-export.php';
        }
    }

    /**
     * Do deactivation
     *
     * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
     *
     * @see    https://developer.wordpress.org/reference/functions/register_deactivation_hook/
     *
     * @param    bool    $network    Whether deactivating this plugin on network or a single site.
     */
    function _deactivate($network)
    {
        flush_rewrite_rules(false);
    }

    /**
     * Do uninstallation
     *
     * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
     *
     * @see    https://developer.wordpress.org/reference/functions/register_uninstall_hook/
     */
    static function _uninstall()
    {
        delete_option(self::OPTION_NAME);
    }

    /**
     * Pre-activation
     */
    private function preActivate()
    {
        if (version_compare($GLOBALS['wp_version'], '4.7', '<')) {
            throw new Exception( sprintf('Whoops, Zoo Theme requires %1$s version %2$s at least. Please delete this theme and upgrade %1$s to latest version for better perfomance and security.', 'WordPress', '4.7') );
        }

        if (version_compare(PHP_VERSION, '5.6', '<')) {
            throw new Exception( sprintf('Whoops, Zoo Theme requires %1$s version %2$s at least. Please delete this theme and upgrade %1$s to latest version for better perfomance and security.', 'PHP', '5.6') );
        }
    }
}
CleverAddons::getInstance();
