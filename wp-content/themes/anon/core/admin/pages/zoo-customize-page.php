<?php
/**
 * Zoo_Customize_Page
 *
 * @package  Zoo_Theme\Core\Admin\Classes
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */
final class Zoo_Customize_Page
{
    /**
     * Nope constructor
     */
    private function __construct()
    {

    }

    /**
     * Singleton
     */
    static function getInstance()
    {
        static $instance = null;

        if (null === $instance) {
            $instance = new self;
            add_action('admin_menu', [$instance, '_add'], 12);
        }
    }

    /**
     * Add to admin menu
     */
    function _add($context = '')
    {
		zoo_add_submenu_page(
			Zoo_Welcome_Page::SLUG,
			esc_html__('Theme Customize', 'anon'),
			esc_html__('Theme Customize', 'anon'),
			'manage_options',
			admin_url('customize.php')
		);
    }
}
Zoo_Customize_Page::getInstance();
