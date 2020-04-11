<?php namespace CafePro;
if(!class_exists('\Elementor\Plugin')) {
    return;
}
use Elementor\Controls_Manager;
use Elementor\Plugin as Elementor;
use Elementor\Core\Documents_Manager;

/**
 * DocumentsManager
 */
final class DocumentsManager
{
    /**
     * @var string
     */
    const GLOBAL_HEADER_META_KEY = 'cafe_use_as_global_header';

    /**
     * @var string
     */
    const GLOBAL_FOOTER_META_KEY = 'cafe_use_as_global_footer';

    /**
     * Constructor
     */
    function __construct()
    {
        $current_theme = wp_get_theme();

        $this->global_header_key = 'cafe_global_header_template_for_' . $current_theme->Template;
        $this->global_footer_key = 'cafe_global_footer_template_for_' . $current_theme->Template;

        add_action('get_header', [$this, '_maybeRenderSiteHeader'], 9);
        add_action('get_footer', [$this, '_maybeRenderSiteFooter'], 9);
        add_action('template_include', [$this, '_includeDocTemplate'], 11);
        add_action('elementor/documents/register', [$this, '_registerCustomDocTypes']);
        add_action('elementor/documents/register_controls', [$this, '_addControls'], PHP_INT_MAX);
        add_action('elementor/editor/after_save', [$this, '_maybeSetDefaultHeaderFooter'], 10, 2);
    }

    /**
     * @internal Used as a callback
     */
    function _registerCustomDocTypes(Documents_Manager $manager)
    {
        require __DIR__ . '/documents/class-site-header-document.php';
        require __DIR__ . '/documents/class-site-footer-document.php';

        $manager->register_document_type('site_header', Documents\SiteHeader::class);
        $manager->register_document_type('site_footer', Documents\SiteFooter::class);
    }

    /**
     * @internal Callback
     */
    function _includeDocTemplate($template)
    {
        if (is_singular()) {
            $document = Elementor::$instance->documents->get_doc_for_frontend(get_the_ID());
            if ($document) {
                if (($document instanceof Documents\SiteHeader || $document instanceof Documents\SiteFooter)) {
                    return CAFE_PRO_DIR . 'src/templates/header-footer-builder.php';
                }
            }
        }

        return $template;
    }

    /**
     * @internal Callback
     */
    function _maybeRenderSiteHeader($name)
    {
        $site_header_id = false;
        $page_id = $this->getCurrentPageId();
        $header_name = get_post_meta($page_id, 'clever_elementor_header_template', true);

        if (!$header_name || 'inherit' === $header_name) {
            $headers = get_posts([
                'fields' => 'ids',
                'post_type' => 'elementor_library',
                'post_status' => 'publish',
                'meta_key' => self::GLOBAL_HEADER_META_KEY,
                'ignore_sticky_posts' => true,
                'nopaging' => true,
                'no_found_rows' => true,
                'posts_per_page' => 1
            ]);
            $site_header_id = !empty($headers[0]) ? $headers[0] : false;
        } else {
            $header = get_page_by_path($header_name, OBJECT, 'elementor_library');
            if ($header) {
                $site_header_id = $header->ID;
            }
        }

        if ($site_header_id) {
            require CAFE_PRO_DIR . 'src/templates/site-header.php';
    		$templates = [];
    		$name = (string) $name;
    		if ( '' !== $name ) {
    			$templates[] = "header-{$name}.php";
    		}
    		$templates[] = 'header.php';
    		// Avoid running wp_head hooks again
    		remove_all_actions('wp_head');
            // Hide current theme header in tmp buffer.
    		ob_start();
    		locate_template($templates, true);
    		ob_get_clean();
        }
    }

    /**
     * @internal Callback
     */
    function _maybeRenderSiteFooter($name)
    {
        $site_footer_id = false;
        $page_id = $this->getCurrentPageId();
        $footer_name = get_post_meta($page_id, 'clever_elementor_footer_template', true);

        if (!$footer_name || 'inherit' === $footer_name) {
            $footers = get_posts([
                'fields' => 'ids',
                'post_type' => 'elementor_library',
                'post_status' => 'publish',
                'meta_key' => self::GLOBAL_FOOTER_META_KEY,
                'ignore_sticky_posts' => true,
                'nopaging' => true,
                'no_found_rows' => true,
                'posts_per_page' => 1
            ]);
            $site_footer_id = !empty($footers[0]) ? $footers[0] : false;
        } else {
            $footer = get_page_by_path($footer_name, OBJECT, 'elementor_library');
            if ($footer) {
                $site_footer_id = $footer->ID;
            }
        }

        if ($site_footer_id) {
            require CAFE_PRO_DIR . 'src/templates/site-footer.php';
    		$templates = [];
    		$name = (string) $name;
    		if ( '' !== $name ) {
    			$templates[] = "footer-{$name}.php";
    		}
    		$templates[] = 'footer.php';
    		// Avoid running wp_footer hooks again
    		remove_all_actions('wp_footer');
            // Hide current theme footer in tmp buffer.
    		ob_start();
    		locate_template($templates, true);
    		ob_get_clean();
        }
    }

   /**
     * @internal  Used as a callback
     */
    function _addControls($document)
    {
        $post = $document->get_post();
        $type = get_post_meta($post->ID, '_elementor_template_type', true);

        if ('site_header' === $type || 'site_footer' === $type) {
            if ('site_header' === $type) {
                $current = get_post_meta(self::GLOBAL_HEADER_META_KEY);
            } else {
                $current = get_post_meta(self::GLOBAL_FOOTER_META_KEY);
            }
    		$document->start_injection([
    			'of' => 'post_status'
    		]);
    		$document->add_control(
    			'use_as_default',
    			[
    				'label' => esc_html__('Use as default', 'cafe-pro'),
    				'type' => Controls_Manager::SWITCHER,
                    'default' => false,
                    'return_value' => $post->post_name,
                    'description' => sprintf(__('If multiple %ss being set as default, the last one marked as default will be used.', 'cafe-pro'), str_replace('_', ' ', $type))
    			]
    		);
            $document->end_injection();
        }
    }

   /**
     * @internal  Used as a callback
     */
    function _maybeSetDefaultHeaderFooter($post_id, $editor_data)
    {
        $post = get_post($post_id);
        $type = get_post_meta($post_id, '_elementor_template_type', true);
        $settings = get_post_meta($post_id, '_elementor_page_settings', true);

        if ('site_header' === $type) {
            if ($settings['use_as_default'] && $post->post_name === $settings['use_as_default']) {
                update_post_meta($post_id, self::GLOBAL_HEADER_META_KEY, $settings['use_as_default']);
                $headers = get_posts([
                    'fields' => 'ids',
                    'post_type' => 'elementor_library',
                    'post_status' => 'publish',
                    'meta_key' => '_elementor_template_type',
                    'meta_value' => 'site_header',
                    'ignore_sticky_posts' => true,
                    'nopaging' => true,
                    'no_found_rows' => true,
                    'posts_per_page' => -1,
                    'update_post_meta_cache' => false,
                    'update_post_term_cache' => false
                ]);
                if ($headers) { // TODO: Unset other global headers
                    foreach ($headers as $header_id) {
                        if ($post_id == $header_id) {
                            continue;
                        }
                        $header_settings = (array)get_post_meta($header_id, '_elementor_page_settings', true);
                        $header_settings['use_as_default'] = false;
                        delete_post_meta($header_id, self::GLOBAL_HEADER_META_KEY);
                        update_post_meta($header_id, '_elementor_page_settings', $header_settings);
                    }
                }
            } else {
                delete_post_meta($post_id, self::GLOBAL_HEADER_META_KEY);
            }
        } elseif ('site_footer' === $type) {
            if ($settings['use_as_default'] && $post->post_name === $settings['use_as_default']) {
                update_post_meta($post_id, self::GLOBAL_FOOTER_META_KEY, $settings['use_as_default']);
                $footers = get_posts([
                    'fields' => 'ids',
                    'post_type' => 'elementor_library',
                    'post_status' => 'publish',
                    'meta_key' => '_elementor_template_type',
                    'meta_value' => 'site_footer',
                    'ignore_sticky_posts' => true,
                    'nopaging' => true,
                    'no_found_rows' => true,
                    'posts_per_page' => -1,
                    'update_post_meta_cache' => false,
                    'update_post_term_cache' => false
                ]);
                if ($footers) {
                    foreach ($footers as $footer_id) {
                        if ($post_id == $footer_id) {
                            continue;
                        }
                        $footer_settings = (array)get_post_meta($footer_id, '_elementor_page_settings', true);
                        $footer_settings['use_as_default'] = false;
                        delete_post_meta($footer_id, self::GLOBAL_FOOTER_META_KEY);
                        update_post_meta($footer_id, '_elementor_page_settings', $footer_settings);
                    }
                }
            } else {
                delete_post_meta($footer_id, self::GLOBAL_FOOTER_META_KEY);
            }
        } else {
            return;
        }
    }

    /**
     * @return int
     */
    private function getCurrentPageId()
    {
        global $wp_query;

        if (!$wp_query->is_main_query()) {
            return 0;
        }

        if ($wp_query->is_home() && $wp_query->is_front_page()) {
            return 0;
        } elseif ($wp_query->is_home() && !$wp_query->is_front_page()) {
            return (int)get_option('page_for_posts');
        } elseif (!$wp_query->is_home() && $wp_query->is_front_page()) {
            return (int)get_option('page_on_front');
        } elseif (function_exists('is_shop') && is_shop()) {
            return wc_get_page_id('shop');
        } elseif (isset($wp_query->post->ID)) {
            return (int)$wp_query->post->ID;
        } else {
            return 0;
        }
    }
}

return new DocumentsManager();
