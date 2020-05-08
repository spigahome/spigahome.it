<?php namespace CafePro;

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
            add_action('elementor/editor/before_enqueue_scripts', [$self, '_beforeEnqueueEditorScripts'], 10, 0);
            add_action('elementor/frontend/before_enqueue_scripts', [$self, '_beforeEnqueueFrontendScripts'], 10, 0);
            add_action('elementor/frontend/after_register_styles', [$self, '_afterRegisterFrontendStyles'], 10, 0);
            add_action('elementor/frontend/after_enqueue_styles', [$self, '_afterEnqueueFrontendStyles'], 10, 0);
            add_action('elementor/frontend/after_register_scripts', [$self, '_afterRegisterFrontendScripts'], 10, 0);
            add_action('elementor/editor/after_enqueue_styles', [$self, '_afterEnqueueEditorStyles'], 10, 0);
            add_action('elementor/editor/after_register_scripts', [$self, '_afterRegisterEditorScripts'], 10, 0);
        }

        if ($return) {
            return $self;
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

        }
    }

    /**
     * @internal Used as a callback
     */
    function _previewStyles()
    {

    }

    /**
     * Load JS Templates
     *
     * @internal Used as a callback
     */
    function _printEditorTemplates()
    {

    }


    /**
     * Register frontend stylesheets
     *
     * @internal Used as a callback.
     */
    function _afterRegisterFrontendStyles()
    {

    }

    /**
     * Register frontend scripts
     *
     * @internal Used as a callback.
     */
    function _afterRegisterFrontendScripts()
    {
        wp_register_script('cafe-pro-frontend', CAFE_PRO_URI . 'assets/js/frontend.min.js', ['jquery-core'], Plugin::VERSION, true);
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

    }

    /**
     * @internal Used as a callback.
     */
    function _afterEnqueueEditorStyles()
    {
        wp_enqueue_style('cafe-pro-editor', CAFE_PRO_URI . 'assets/css/editor.min.css', [], Plugin::VERSION);
    }

    /**
     * Enqueue frontend dev scripts
     *
     * @ignore For dev purpose only. Will be removed later.
     * @internal Used as a callback.
     */
    function _beforeEnqueueFrontendScripts()
    {

    }

    /**
     * @internal Used as a callback.
     */
    function _beforeEnqueueEditorScripts()
    {
        add_filter('license.cleveraddon.com/cafe', '__return_true');
    }
}

// Initialize.
AssetsManager::instance();
