<?php namespace Cafe\Widgets;

use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;

/**
 * CleverSlider
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
final class CleverSliderPro extends CleverWidgetBase
{
    public function get_name()
    {
        return 'clever-slider-pro';
    }

    public function get_title()
    {
        return esc_html__('Clever Slider Pro', 'cafe-lite');
    }

    public function get_icon()
    {
        return 'cs-font clever-icon-slider';
    }

    public function get_keywords()
    {
        return ['slides', 'slider'];
    }

    public function get_style_depends()
    {
        return ['cleverfont'];
    }

    public function get_script_depends()
    {
        return ['imagesloaded', 'jquery-slick', 'cafe-pro-frontend'];
    }

    protected function _register_controls()
    {


        $this->start_controls_section('section_slides', [
            'label' => esc_html__('Slides', 'cafe-lite'),
        ]);
        $repeater = new Repeater();
        $repeater->add_control('slide_name', [
            'label'       => esc_html__('Name', 'cafe-lite'),
            'type'        => Controls_Manager::TEXT,
            'placeholder'     => esc_html__('Slide Name', 'cafe-lite'),
            'default'     => esc_html__('Slide Name', 'cafe-lite')
        ]);

        $repeater->add_control('slide_template', [
            'label'     => esc_html__('Template', 'cafe-lite'),
            'type'      => Controls_Manager::SELECT,
            'default'   => '',
            'options' => $this->list_section_templates(),
        ]);

        $this->add_control('slides', [
            'label'       => esc_html__('Slides', 'cafe-lite'),
            'type'        => Controls_Manager::REPEATER,
            'show_label'  => true,
            'fields'      => $repeater->get_controls(),
            'default'     => [],
            'title_field' => '{{{ slide_name }}}',
        ]);

        $this->add_responsive_control('slides_height', [
            'label'      => esc_html__('Container Height', 'cafe-lite'),
            'type'       => Controls_Manager::SLIDER,
            'range'      => [
                'px' => [
                    'min' => 100,
                    'max' => 1000,
                ],
                'vh' => [
                    'min' => 10,
                    'max' => 100,
                ],
            ],
            'default'    => [
                'size' => 400,
            ],
            'size_units' => [ 'px', 'vh'],
            'selectors'  => [
                '{{WRAPPER}} .slick-slide' => 'height: {{SIZE}}{{UNIT}};',
            ],
            'separator'  => 'before',
        ]);
        $this->add_responsive_control('slides_width', [
            'label'      => esc_html__('Container Width', 'cafe-lite'),
            'type'       => Controls_Manager::SLIDER,
            'range'      => [
                'px' => [
                    'min' => 100,
                    'max' => 2000,
                ],
                'vw' => [
                    'min' => 10,
                    'max' => 100,
                ],'%' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'default'    => [
                'size' => 1400,
            ],
            'size_units' => [ 'px', 'vw','%' ],
            'selectors'  => [
                '{{WRAPPER}} .slick-slide' => 'width: {{SIZE}}{{UNIT}};',
            ]
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_slider_options', [
            'label' => esc_html__('Slider Settings', 'cafe-lite'),
            'type'  => Controls_Manager::SECTION,
        ]);
        $this->add_responsive_control('show_arrows', [
            'label'          => esc_html__('Show Arrows', 'cafe-lite'),
            'type'           => Controls_Manager::SWITCHER,
            'default'        => 'yes',
            'tablet_default' => 'yes',
            'mobile_default' => 'no'
        ]);
        $this->add_responsive_control('show_dots', [
            'label'   => esc_html__('Show Dots', 'cafe-lite'),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);
        $this->add_control('pause_on_hover', [
            'label'   => esc_html__('Pause on Hover', 'cafe-lite'),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('autoplay', [
            'label'   => esc_html__('Autoplay', 'cafe-lite'),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('autoplay_speed', [
            'label'     => esc_html__('Autoplay Speed', 'cafe-lite'),
            'type'      => Controls_Manager::NUMBER,
            'default'   => 5000,
            'condition' => [
                'autoplay' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}} .slick-slide-bg' => 'animation-duration: calc({{VALUE}}ms*1.2); transition-duration: calc({{VALUE}}ms)',
            ],
        ]);

        $this->add_control('infinite', [
            'label'   => esc_html__('Infinite Loop', 'cafe-lite'),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('transition_speed', [
            'label'   => esc_html__('Transition Speed (ms)', 'cafe-lite'),
            'type'    => Controls_Manager::NUMBER,
            'default' => 500,
        ]);

        $this->end_controls_section();

        $this->start_controls_section('heading_style_arrows', [
            'label' => esc_html__('Arrows', 'cafe-lite'),
            'tab'   => Controls_Manager::TAB_STYLE,
            'condition' => [
                'show_arrows' => 'yes',
            ],
        ]);

        $this->add_control('arrow_left_icon', [
            'label'     => esc_html__('Arrow Left', 'cafe-lite'),
            'type'      => 'clevericon',
            'condition' => [
                'show_arrows' => 'yes',
            ],
        ]);
        $this->add_control('arrow_right_icon', [
            'label'     => esc_html__('Arrow Right', 'cafe-lite'),
            'type'      => 'clevericon',
            'condition' => [
                'show_arrows' => 'yes',
            ],
        ]);
        $this->add_control('arrows_position', [
            'label'     => esc_html__('Arrows Position', 'cafe-lite'),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'inside',
            'options'   => [
                'inside'  => esc_html__('Inside', 'cafe-lite'),
                'outside' => esc_html__('Outside', 'cafe-lite'),
            ],
            'condition' => [
                'show_arrows' => 'yes',
            ],
        ]);

        $this->add_control('arrows_size', [
            'label'     => esc_html__('Arrows Size', 'cafe-lite'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [
                'px' => [
                    'min' => 20,
                    'max' => 60,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-slider-wrapper .slick-slider .slick-arrow:before' => 'font-size: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'show_arrows' => 'yes',
            ],
        ]);
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'arrows_border',
                'label' => __('Border', 'cafe-pro'),
                'placeholder' => '',
                'default' => '',
                'selector' => '{{WRAPPER}} .cafe-slider-wrapper .slick-arrow',
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control('arrows_border_radius', [
            'label' => __('Border Radius', 'cafe-pro'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 200,
                ]
            ],
            'separator' => 'after',
            'selectors' => [
                '{{WRAPPER}} .cafe-slider-wrapper .slick-arrow' => 'border-radius:{{SIZE}}{{UNIT}}',
            ]
        ]);
        $this->add_control('arrows_color', [
            'label'     => esc_html__('Arrows Color', 'cafe-lite'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-slider-wrapper .slick-arrow' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'show_arrows' => 'yes',
            ],
        ]);
        $this->add_control('arrows_color_hover', [
            'label'     => esc_html__('Arrows Color Hover', 'cafe-lite'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-slider-wrapper .slick-arrow:hover' => 'color: {{VALUE}};border-color: {{VALUE}};',
            ],
            'condition' => [
                'show_arrows' => 'yes',
            ],
        ]);
        $this->add_responsive_control('arrows_padding', [
            'label' => __('padding', 'cafe-pro'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'separator' => 'before',
            'selectors' => [
                '{{WRAPPER}} .cafe-slider-wrapper .slick-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->end_controls_section();
        $this->start_controls_section('heading_style_dots', [
            'label' => esc_html__('Dots', 'cafe-lite'),
            'tab'   => Controls_Manager::TAB_STYLE,
            'condition' => [
                'show_dots' => 'yes',
            ],
        ]);
        $this->add_control('dots_style', [
            'label'     => esc_html__('Dots Style', 'cafe-lite'),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'dots',
            'options'   => [
                'dots'  => esc_html__('Dots', 'cafe-lite'),
                'line'  => esc_html__('Line', 'cafe-lite'),
                'group' => esc_html__('Group', 'cafe-lite'),
            ],
            'condition' => [
                'show_dots' => 'yes',
            ],
        ]);

        $this->add_control('dots_position', [
            'label'     => esc_html__('Dots Position', 'cafe-lite'),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'inside',
            'options'   => [
                'outside' => esc_html__('Outside', 'cafe-lite'),
                'inside'  => esc_html__('Inside', 'cafe-lite'),
            ],
            'condition' => [
                'show_dots' => 'yes',
            ],
        ]);

        $this->add_control('dots_size', [
            'label'     => esc_html__('Dots Size', 'cafe-lite'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [
                'px' => [
                    'min' => 5,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-slider-wrapper .cafe-slider-slides .slick-dots li button'  => 'width: {{SIZE}}{{UNIT}}, height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .cafe-slider-wrapper .cafe-slider-slides .slick-group li button' => 'width: {{SIZE}}{{UNIT}}, height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .cafe-slider-wrapper .cafe-slider-slides > ul.slick-line button' => 'width: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'show_dots' => 'yes',
            ],
        ]);

        $this->add_control('dots_color', [
            'label'     => esc_html__('Dots Color', 'cafe-lite'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-slider-wrapper .cafe-slider-slides > ul.slick-group li.slick-active button, {{WRAPPER}} .cafe-slider-wrapper .cafe-slider-slides > ul.slick-group li:hover button'=> 'color: {{VALUE}};background-color: {{VALUE}}',
                '{{WRAPPER}} .cafe-slider-wrapper .cafe-slider-slides .slick-dots li button, {{WRAPPER}} .cafe-slider-wrapper .cafe-slider-slides .slick-line li button'                      => 'background-color: {{VALUE}}',
                '{{WRAPPER}} .cafe-slider-wrapper .cafe-slider-slides .slick-group li button'                                                                                                 => 'color: {{VALUE}}',
                ],
            'condition' => [
                'show_dots' => 'yes',
            ],
        ]);

        $this->end_controls_section();
    }

    /**
     * @return array
     */
    protected function list_section_templates()
    {
        $templates = [];

        $posts = get_posts([
            'post_type' => 'elementor_library',
            'meta_key' => '_elementor_template_type',
            'meta_value' => 'section',
            'posts_per_page' => -1,
            'no_found_rows' => true,
            'update_post_meta_cache' => 0,
            'update_post_term_cache' => 0
        ]);

        if ($posts) {
            $current_post_id = isset($_GET['post']) ? $_GET['post'] : 0;
            foreach ($posts as $post) {
                if ($post->ID == $current_post_id) { // TODO: exclude current template.
                    continue;
                }
                $templates[$post->post_name] = $post->post_title;
            }
        }

        return $templates;
    }

    /**
     * Render
     */
    protected function render()
    {
        $this->getViewTemplate('template', 'slider-pro', $this->get_settings());
    }
}
