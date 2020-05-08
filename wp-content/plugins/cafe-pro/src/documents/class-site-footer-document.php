<?php namespace CafePro\Documents;

use Elementor\Utils;
use Elementor\Plugin as Elementor;
use Elementor\Core\DocumentTypes\Post;
use Elementor\Modules\Library\Documents\Library_Document;

/**
 * SiteFooter
 */
final class SiteFooter extends Library_Document
{
    /**
     * Get document properties.
     *
     * Retrieve the document properties.
     *
     * @since 2.0.0
     * @access public
     * @static
     *
     * @return array Document properties.
     */
    public static function get_properties()
    {
        $properties = parent::get_properties();

        $properties['support_wp_page_templates'] = false;

        return $properties;
    }

    /**
     * Get document name.
     *
     * Retrieve the document name.
     *
     * @since 2.0.0
     * @access public
     *
     * @return string Document name.
     */
    public function get_name()
    {
        return 'site_footer';
    }

    /**
     * Get document title.
     *
     * Retrieve the document title.
     *
     * @since 2.0.0
     * @access public
     * @static
     *
     * @return string Document title.
     */
    public static function get_title()
    {
        return __('Site Footer', 'clever-elementor-addon');
    }

    /**
     * @since 2.1.3
     * @access public
     */
    public function get_css_wrapper_selector()
    {
        return '#cafe-site-footer';
    }

    /**
     * Override container attributes
     */
    public function get_container_attributes()
    {
        $id = $this->get_main_id();

        $settings = $this->get_frontend_settings();

        $attributes = [
            'data-elementor-type' => $this->get_name(),
            'data-elementor-id' => $id,
            'class' => 'elementor elementor-' . $id . ' cafe-site-footer',
        ];

        if (!Elementor::$instance->preview->is_preview_mode($id)) {
            $attributes['data-elementor-settings'] = wp_json_encode($this->get_frontend_settings());
        }

        return $attributes;
    }

    /**
     * Override wrapper to insert `footer` tag and other neccessary stuff.
     */
    public function print_elements_with_wrapper($elements_data = null)
    {
        if (!$elements_data) {
            $elements_data = $this->get_elements_data();
        }

        do_action('cafe_before_render_site_footer', $elements_data); ?>

		<footer id="cafe-site-footer" <?php echo Utils::render_html_attributes($this->get_container_attributes()); ?>>
			<div class="elementor-inner">
				<div class="elementor-section-wrap">
					<?php $this->print_elements($elements_data); ?>
				</div>
			</div>
		</footer>
		<?php

        do_action('cafe_after_render_site_footer', $elements_data);
    }

    /**
     * @since 2.0.0
     * @access protected
     */
    protected function _register_controls()
    {
        parent::_register_controls();

        // Post::register_hide_title_control($this);

        // Post::register_style_controls($this);
    }

    protected function get_remote_library_config()
    {
        $config = parent::get_remote_library_config();

        $config['type'] = 'site_footer';

        return $config;
    }
}
