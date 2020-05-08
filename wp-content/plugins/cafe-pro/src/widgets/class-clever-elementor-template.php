<?php namespace Cafe\Widgets;

use Elementor\Scheme_Color;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Plugin as Elementor;
use Elementor\Group_Control_Typography;

/**
 * CleverElementorTemplate
 */
final class CleverElementorTemplate extends CleverWidgetBase
{

    /**
     * Get widget name.
     *
     * Retrieve text editor widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'clever-elementor-template';
    }

    /**
     * Get widget title.
     *
     * Retrieve text editor widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('Clever Elementor Template', 'cafe-pro');
    }

    /**
     * Get widget icon.
     *
     * Retrieve text editor widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-post-content';
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @since 2.1.0
     * @access public
     *
     * @return array Widget keywords.
     */
    public function get_keywords()
    {
        return ['template', 'shortcode', 'post', 'premade'];
    }

    /**
     * Register text editor widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_menu_content',
            [
                'label' => __('Template Content', 'cafe-pro'),
            ]
        );

        $this->add_control(
            'elementor_template_slug',
            [
                'label' => __('Select a Template', 'cafe-pro'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => $this->listElementorTemplates()
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render text editor widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->renderTemplate($settings['elementor_template_slug']);
    }

    /**
     * List all elementor templates by slug
     */
    private function listElementorTemplates()
    {
        $templates = [];

        $posts = get_posts([
            'post_type' => 'elementor_library',
            'posts_per_page' => -1,
            'no_found_rows' => true,
            'update_post_meta_cache' => 0,
            'update_post_term_cache' => 0
        ]);

        if ($posts) {
            foreach ($posts as $post) {
                $templates[$post->post_name] = $post->post_title;
            }
        }

        return $templates;
    }

    /**
     * Programmatically render an elementor template.
     */
    private function renderTemplate($template_slug)
    {
        $template = get_page_by_path($template_slug, OBJECT, 'elementor_library');

        if ($template) {
            echo Elementor::instance()->frontend->get_builder_content_for_display($template->ID);
        } else {
            esc_html_e('No Elementor template found!', 'cafe-pro');
        }
    }
}
