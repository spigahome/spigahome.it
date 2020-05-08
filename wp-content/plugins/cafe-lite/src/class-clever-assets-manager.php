<?php namespace Cafe;

/**
 * AssetsManager
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
final class AssetsManager
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
    static function instance($return = false)
    {
        static $self = null;

        if (null === $self) {
            $self = new self;
            add_action('admin_enqueue_scripts', [$self, '_loadAdminAssets']);
            add_action('elementor/editor/footer', [$self, '_printEditorTemplates']);
            add_action('elementor/preview/enqueue_styles', [$self, '_previewStyles']);
            add_action('elementor/editor/before_enqueue_scripts', [$self, '_beforeEnqueueEditorScripts'], 11, 0);
            add_action('elementor/frontend/before_enqueue_scripts', [$self, '_beforeEnqueueFrontendScripts'], 10, 0);
            add_action('elementor/frontend/after_register_styles', [$self, '_afterRegisterFrontendStyles'], 10, 0);
            add_action('elementor/frontend/after_enqueue_styles', [$self, '_afterEnqueueFrontendStyles'], 10, 0);
            add_action('elementor/frontend/after_register_scripts', [$self, '_afterRegisterFrontendScripts'], 10, 0);
            add_action('elementor/editor/after_enqueue_styles', [$self, '_afterEnqueueEditorStyles'], 10, 0);
            add_action('elementor/editor/after_register_scripts', [$self, '_afterRegisterEditorScripts'], 10, 0);
            global $wp_customize;
            if ( !isset( $wp_customize ) ) {
                add_action('wp_enqueue_scripts', [$self, '_load_elementor_css_in_head']);
            }
        }

        if ($return) {
            return $self;
        }
    }

    /**
     * Load Elementor styles on all pages in the head to avoid CSS files being loaded in the footer
     */
    function _load_elementor_css_in_head(){
        if(class_exists('\Elementor\Plugin')){
            $elementor =  \Elementor\Plugin::instance();
            $elementor->frontend->enqueue_styles();
        }
        if(class_exists('\ElementorPro\Plugin')){
            $elementor =  \ElementorPro\Plugin::instance();
            $elementor->enqueue_styles();
        }
        if(class_exists('\Elementor\CSS_File')){
            $elementor_page_id = 3167; // Elementor page ID serving as a template (for a header or footer)
            $css_file = new \Elementor\CSS_File( $elementor_page_id );
            $css_file->enqueue();
        }
    }


    /**
     * Register and enqueue admin assets
     *
     * @internal Used as a callback.
     */
    function _loadAdminAssets($hook_suffix)
    {
        if ('cafe-plugin-settings' === $hook_suffix) {
            wp_enqueue_style('cafe-admin', CAFE_URI.'assets/css/admin.min.css', [], Plugin::VERSION);
            wp_enqueue_script('cafe-admin', CAFE_URI.'assets/js/admin.min.js', [], Plugin::VERSION);
        }
    }

    /**
     * @internal Used as a callback
     */
    function _previewStyles()
    {
		wp_enqueue_style('cafe-preview', CAFE_URI . 'assets/css/preview.min.css', [], Plugin::VERSION);
    }

    /**
     * Load JS Templates
     *
     * @internal Used as a callback
     */
    function _printEditorTemplates()
    {
        require CAFE_DIR . 'src/templates/editor.php';
    }


    /**
     * Register frontend stylesheets
     *
     * @internal Used as a callback.
     */
    function _afterRegisterFrontendStyles()
    {
        wp_register_style('cleverfont', CAFE_URI.'assets/vendor/cleverfont/style.css', [], Plugin::VERSION);
    }

    /**
     * Register frontend scripts
     *
     * @internal Used as a callback.
     */
    function _afterRegisterFrontendScripts()
    {
	    wp_register_script('vimeoapi', 'https://player.vimeo.com/api/player.js', true );
	    wp_register_script('youtube-api', 'https://www.youtube.com/player_api', true );
	    wp_register_script('typed', CAFE_URI.'assets/vendor/typed/typed.min.js', ['jquery-core'], Plugin::VERSION, true);
        wp_register_script('countdown', CAFE_URI.'assets/vendor/countdown/countdown.js', ['jquery-core'], Plugin::VERSION, true);
        wp_register_script('spritespin', CAFE_URI.'assets/vendor/spritespin/spritespin.js', ['jquery-core'], Plugin::VERSION, true);
        wp_register_script('infinite-scroll', CAFE_URI.'assets/vendor/infinite-scroll/infinite-scroll.pkgd.min.js', ['jquery-core'], '3.0.5', true);
        wp_register_script('twentytwenty', CAFE_URI.'assets/vendor/twentytwenty/jquery.twentytwenty.js', ['jquery-core'], Plugin::VERSION, true);
        wp_register_script('event-move', CAFE_URI.'assets/vendor/twentytwenty/jquery.event.move.js', ['jquery-core'], Plugin::VERSION, true);
        wp_register_script('cafe-script', CAFE_URI.'assets/js/frontend.min.js', ['jquery-core'], Plugin::VERSION, true);
    }

    /**
     * @internal Used as a callback
     */
    function _afterRegisterEditorScripts()
    {
        $this->_afterRegisterFrontendScripts();
    }

    /**
     * Enqueue frontend stylesheets
     *
     * @internal Used as a callback.
     */
    function _afterEnqueueFrontendStyles()
    {
        wp_enqueue_style('cleverfont');
        wp_enqueue_style('cafe-style', CAFE_URI.'assets/css/frontend.min.css', [], Plugin::VERSION);
    }

    /**
     * @internal Used as a callback.
     */
    function _afterEnqueueEditorStyles()
    {
        $this->_afterEnqueueFrontendStyles();

		wp_enqueue_style('cafe-editor', CAFE_URI . 'assets/css/editor.min.css', [], Plugin::VERSION);
    }

    /**
     * Enqueue frontend dev scripts
     *
     * @ignore For dev purpose only. Will be removed later.
     * @internal Used as a callback.
     */
    function _beforeEnqueueFrontendScripts()
    {
        wp_localize_script('cafe-script', 'cafeFrontendConfig', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('CafeFrontendNonce'),
        ]);

        wp_enqueue_script('cafe-script');
    }

    /**
     * @internal Used as a callback.
     */
    function _beforeEnqueueEditorScripts()
    {
		wp_enqueue_script('cafe-editor', CAFE_URI . 'assets/js/editor.min.js', ['jquery', 'underscore', 'backbone-marionette'], Plugin::VERSION, true);

		wp_localize_script('cafe-editor', 'TemplateKitConf', array(
            'tabs'  => array(
				'page'    => array(
					'title'    => esc_html__('HomePages', 'cafe-lite'),
					'data'     => [],
					'sources'  => array('cafe-local-template', 'cafe-remote-template'),
					'settings' => array(
						'show_title'    => true,
						'show_keywords' => true,
					),
				),
				'subpage'    => array(
					'title'    => esc_html__('SubPages', 'cafe-lite'),
					'data'     => [],
					'sources'  => array('cafe-local-template', 'cafe-remote-template'),
					'settings' => array(
						'show_title'    => true,
						'show_keywords' => true,
					),
				),
				'section' => array(
					'title'    => esc_html__('Sections', 'cafe-lite'),
					'data'     => [],
					'sources'  => array('cafe-local-template', 'cafe-remote-template'),
					'settings' => array(
						'show_title'    => true,
						'show_keywords' => true,
					),
				),
			),
            'defaultTab' => 'page',
			'modalRegions'  => array(
				'modalHeader'  => '.dialog-header',
				'modalContent' => '.dialog-message',
			),
			'license'       => array(
				'activated' => apply_filters('license.cleveraddon.com/cafe', false),
				'link'      => 'https://cleveraddon.com/clever-addon-for-elementor/',
			),
            'previewBaseUrl' => 'https://rest.cleveraddon.com/?elementor_library='
		));
    }
}

// Initialize.
AssetsManager::instance();
