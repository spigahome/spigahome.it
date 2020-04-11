<?php namespace Cafe\Widgets;

use Elementor\Scheme_Color;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;

/**
 * CleverSiteLogo
 */
final class CleverSiteLogo extends CleverWidgetBase
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
        return 'clever-site-logo';
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
        return __('Clever Site Logo', 'cafe-pro');
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
        return 'eicon-logo';
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
        return ['site', 'logo', 'identity'];
    }

    /**
     * @return array
     */
    public function get_categories()
    {
        return ['clever-builder-elements'];
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
            'section_editor',
            [
                'label' => __('Logo', 'cafe-pro'),
            ]
        );

        $this->add_control(
            'site_logo',
            [
                'label' => 'Custom Logo',
                'label_block' => true,
                'type' => Controls_Manager::MEDIA,
                'description' => __('Leave it blank you want use default logo of your site. Config default logo at Customize->Site Header-> Site Identity.', 'cafe-pro'),
            ]
        );
        $this->add_control( 'logo_height', [
            'label'     => esc_html__('Logo Height', 'cafe-lite' ),
            'description'     => esc_html__('Not include padding/margin.', 'cafe-lite' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} img, {{WRAPPER}} a' => 'height: {{SIZE}}{{UNIT}};',
            ],
            'separator'   => 'after',
        ] );
        $this->add_control(
            'sticky_logo',
            [
                'label' => 'Sticky Logo',
                'label_block' => true,
                'type' => Controls_Manager::MEDIA,
                'description' => __('This logo will appear when header is sticky.', 'cafe-pro'),
            ]
        );
        $this->add_control( 'sticky_logo_height', [
            'label'     => esc_html__('Sticky Height', 'cafe-lite' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}}.header-is-sticky img, {{WRAPPER}}.header-is-sticky a' => 'height: {{SIZE}}{{UNIT}};',
            ],
            'separator'   => 'after',
        ] );
        $this->add_responsive_control(
            'align',
            [
                'label' => __('Alignment', 'cafe-pro'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'cafe-pro'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'cafe-pro'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'cafe-pro'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
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
        $this->getViewTemplate('template', 'site-logo', $settings);
    }
}
