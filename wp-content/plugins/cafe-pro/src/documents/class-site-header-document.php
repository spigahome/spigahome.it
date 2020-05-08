<?php namespace CafePro\Documents;

use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Modules\Library\Documents\Library_Document;

/**
 * SiteHeader
 */
final class SiteHeader extends Library_Document
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
        return 'site_header';
    }

    /**
     * @return string Document title.
     */
    public static function get_title()
    {
        return __('Site Header', 'clever-elementor-addon');
    }

    /**
     * @return string
     */
    public function get_css_wrapper_selector()
    {
        return '#cafe-site-header';
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
            'class' => 'elementor elementor-' . $id . ' cafe-site-header',
        ];

        if (!empty($settings['enable_absolute_header'])) {
            $attributes['class'] .= ' cafe-absolute-header';
        }

        return $attributes;
    }

    /**
     * Override wrapper to insert `header` tag and other neccessary stuff.
     */
    public function print_elements_with_wrapper($elements_data = null)
    {
        if (!$elements_data) {
            $elements_data = $this->get_elements_data();
        }

        do_action('cafe_before_render_site_header', $elements_data); ?>

		<header id="cafe-site-header" <?php echo Utils::render_html_attributes($this->get_container_attributes()); ?>>
			<div class="elementor-inner">
				<div class="elementor-section-wrap">
					<?php $this->print_elements($elements_data); ?>
				</div>
			</div>
		</header>
		<?php

        do_action('cafe_after_render_site_header', $elements_data);
    }

    /**
     * Register controls
     */
    protected function _register_controls()
    {
        parent::_register_controls();

        $this->start_controls_section(
            'absolute_header',
            [
                'label' => __('Absolute Header', 'cafe-pro'),
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );

        $this->add_control(
            'enable_absolute_header',
            [
                'label' => __('Enable Absolute Header', 'cafe-pro'),
                'description' => __('Header will overlap to content. It helpful for create header transparent.', 'cafe-pro'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'cafe-pro'),
                'label_off' => __('No', 'cafe-pro'),
                'default' => '',
                'frontend_available' => true
            ]
        );

        $this->end_controls_section();

        // Post::register_style_controls($this);
    }

    protected function get_remote_library_config()
    {
        $config = parent::get_remote_library_config();

        $config['type'] = 'site_header';

        return $config;
    }
}
