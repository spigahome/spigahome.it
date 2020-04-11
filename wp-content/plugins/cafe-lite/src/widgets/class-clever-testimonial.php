<?php

namespace Cafe\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;

/**
 * Clever Testimonial
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
final class CleverTestimonial extends CleverWidgetBase
{
    /**
     * @return string
     */
    function get_name()
    {
        return 'clever-testimonial';
    }

    /**
     * @return string
     */
    function get_title()
    {
        return esc_html__('CAFE Testimonial', 'cafe-lite');
    }

    /**
     * @return string
     */
    function get_icon()
    {
        return 'cs-font clever-icon-quote-1';
    }

    /**
     * Register controls
     */
    protected function _register_controls()
    {

        $repeater = new \Elementor\Repeater();
        $repeater->add_control('author_avatar', [
            'label' => esc_html__('Avatar', 'cafe-lite'),
            'type' => Controls_Manager::MEDIA,
        ]);
        $repeater->add_control(
            'author',
            [
                'label' => esc_html__('Author', 'cafe-lite'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'author_des',
            [
                'label' => esc_html__('Author Description', 'cafe-lite'),
                'type' => Controls_Manager::TEXT,
            ]
        );
        $repeater->add_control('star', [
            'label' => esc_html__('Star', 'elementor'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 5,
                ]
            ],

        ]);
        $repeater->add_control(
            'testimonial_content',
            [
                'label' => esc_html__('Testimonial Content', 'cafe-lite'),
                'type' => Controls_Manager::TEXTAREA,
            ]
        );

        $this->start_controls_section('content_settings', [
            'label' => esc_html__('Content Settings', 'cafe-lite')
        ]);
        $this->add_control('content', [
            'label' => esc_html__('Content', 'cafe-lite'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'title_field' => '{{{ author }}}',
            'default' => [
                [
                    'author' => esc_html__('Testimonial 1', 'cafe-lite'),
                ],
                [
                    'author' => esc_html__('Testimonial 2', 'cafe-lite'),
                ],
                [
                    'author' => esc_html__('Testimonial 3', 'cafe-lite'),
                ],
            ],

        ]);
        $this->end_controls_section();
        $this->start_controls_section('layout_settings', [
            'label' => esc_html__('Layout Settings', 'cafe-lite')
        ]);
        $this->add_control('show_quotation', [
            'label' => esc_html__('Enable Quotation icon', 'cafe-lite'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'true',
            'default' => 'false',
        ]);
        $this->add_control('layout', [
            'label' => esc_html__('Layout', 'cafe-lite'),
            'type' => Controls_Manager::SELECT,
            'default' => 'grid',
            'options' => [
                'grid' => esc_html__('Grid', 'cafe-lite'),
                'carousel' => esc_html__('Carousel', 'cafe-lite'),
            ],
            'description' => esc_html__('Layout of testimonial.', 'cafe-lite'),
        ]);
        $this->add_control('style', [
            'label' => esc_html__('Style', 'cafe-lite'),
            'type' => Controls_Manager::SELECT,
            'default' => 'default',
            'options' => [
                'default' => esc_html__('Default', 'cafe-lite'),
                'style-1' => esc_html__('Style 1', 'cafe-lite'),
                'style-2' => esc_html__('Style 2', 'cafe-lite'),
                'style-3' => esc_html__('Style 3', 'cafe-lite'),
            ],
            'description' => esc_html__('style of testimonial.', 'cafe-lite'),
        ]);
        $this->add_responsive_control('columns', [
            'label' => esc_html__('Columns For Row', 'elementor'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 6,
                ]
            ],
            'devices' => ['desktop', 'tablet', 'mobile'],
            'desktop_default' => [
                'size' => 1,
                'unit' => 'px',
            ],
            'tablet_default' => [
                'size' => 1,
                'unit' => 'px',
            ],
            'mobile_default' => [
                'size' => 1,
                'unit' => 'px',
            ],
            'condition' => [
                'layout' => 'grid'
            ],
        ]);
        $this->add_responsive_control('slides_to_show', [
            'label' => esc_html__('Slide To Show', 'elementor'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 10,
                ]
            ],
            'devices' => ['desktop', 'tablet', 'mobile'],
            'desktop_default' => [
                'size' => 1,
                'unit' => 'px',
            ],
            'tablet_default' => [
                'size' => 1,
                'unit' => 'px',
            ],
            'mobile_default' => [
                'size' => 1,
                'unit' => 'px',
            ],
            'condition' => [
                'layout' => 'carousel'
            ],
        ]);
        $this->add_control('scroll', [
            'label' => esc_html__('Slider to scroll', 'cafe-lite'),
            'type' => Controls_Manager::NUMBER,
            'default' => '1',
            'description' => esc_html__('Number testimonial scroll per time.', 'cafe-lite'),
            'condition' => [
                'layout' => 'carousel'
            ],

        ]);
        $this->add_responsive_control('show_nav', [
            'label' => esc_html__('Enable Navigation', 'cafe-lite'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'true',
            'default' => 'false',
            'condition' => [
                'layout' => 'carousel'
            ],
        ]);
        $this->add_responsive_control('show_pag', [
            'label' => esc_html__('Enable Pagination', 'cafe-lite'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'true',
            'default' => 'false',
            'condition' => [
                'layout' => 'carousel'
            ],
        ]);
        $this->add_responsive_control('autoplay', [
            'label' => esc_html__('Auto Play', 'cafe-lite'),
            'description' => esc_html__('', 'cafe-lite'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'true',
            'default' => 'false',
            'condition' => [
                'layout' => 'carousel'
            ],
        ]);
        $this->add_control('speed', [
            'label' => esc_html__('Speed', 'cafe-lite'),
            'type' => Controls_Manager::NUMBER,
            'default' => '3000',
            'condition' => [
                'layout' => 'carousel',
                'autoplay' => 'true'
            ],
            'description' => esc_html__('Time(ms) to next slider.', 'cafe-lite'),
        ]);
        $this->add_control('show_avatar', [
            'label' => esc_html__('Show user avatar', 'cafe-lite'),
            'description' => esc_html__('', 'cafe-lite'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'true',
            'default' => 'false',
        ]);
        $this->add_control('show_des', [
            'label' => esc_html__('Show user description', 'cafe-lite'),
            'description' => esc_html__('', 'cafe-lite'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'true',
            'default' => 'false',
        ]);
        $this->add_control('show_star', [
            'label' => esc_html__('Show Star', 'cafe-lite'),
            'description' => esc_html__('', 'cafe-lite'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'true',
            'default' => 'false',
        ]);
        $this->add_control('css_class', [
            'label' => esc_html__('Custom HTML Class', 'cafe-lite'),
            'type' => Controls_Manager::TEXT,
            'description' => esc_html__('You may add a custom HTML class to style element later.', 'cafe-lite'),
        ]);
        $this->end_controls_section();
        $this->start_controls_section('heading_quotation_settings', [
            'label' => esc_html__('Quotation icon', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('quotation_color', [
            'label' => esc_html__('Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-quotation' => 'color: {{VALUE}};'
            ]
        ]);
        $this->add_control('quotation_bg_color', [
            'label' => esc_html__('Background', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-quotation' => 'background-color: {{VALUE}};'
            ]
        ]);
        $this->add_control('quotation_icon_size', [
            'label' => esc_html__('Icon Size', 'cafe-lite'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 200,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-quotation' => 'font-size: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_control('quotation_size', [
            'label' => esc_html__('Size', 'cafe-lite'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 200,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-quotation' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_control('quotation_radius', [
            'label' => esc_html__('Border Radius', 'cafe-lite'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 200,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-quotation' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_control('quotation_spacing', [
            'label' => esc_html__('Spacing', 'cafe-lite'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 200,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-quotation' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_responsive_control(
            'quotation_align',
            [
                'label' => esc_html__('Align', 'cafe-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'cafe-lite'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'none' => [
                        'title' => esc_html__('Center', 'cafe-lite'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'cafe-lite'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .cafe-quotation' => 'float: {{VALUE}};'
                ]
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section('heading_content_block_settings', [
            'label' => esc_html__('Content Block', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('content_block_bg_color', [
            'label' => esc_html__('Background', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-content' => 'background-color: {{VALUE}};'
            ]
        ]);
        $this->add_responsive_control(
            'content_block_align',
            [
                'label' => esc_html__('Align', 'cafe-lite'),
                'type' => Controls_Manager::CHOOSE,
                'separator' => 'before',
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'cafe-lite'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'none' => [
                        'center' => esc_html__('Center', 'cafe-lite'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'cafe-lite'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .cafe-wrap-content' => 'text-align: {{VALUE}};'
                ]
            ]
        );
        $this->add_control('info_block_heading', [
            'label' => esc_html__('Info Block', 'cafe-lite'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'after',
        ]);
        $this->add_responsive_control(
            'info_block_align',
            [
                'label' => esc_html__('Align Item', 'cafe-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'cafe-lite'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'center' => esc_html__('Center', 'cafe-lite'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Right', 'cafe-lite'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .cafe-wrap-testimonial-info' => 'justify-content: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'info_block_text_align',
            [
                'label' => esc_html__('Text Align', 'cafe-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'cafe-lite'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'center' => esc_html__('Center', 'cafe-lite'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'cafe-lite'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .cafe-wrap-testimonial-info' => 'text-align: {{VALUE}};'
                ]
            ]
        );

        $this->add_control('content_block_border_radius', [
            'label' => esc_html__('Border Radius', 'cafe-lite'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'separator' => 'before',
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'content_block_border',
                'label' => esc_html__('Border', 'cafe-lite'),
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .cafe-wrap-content',
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control('content_block_padding', [
            'label' => esc_html__('Padding', 'cafe-lite'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->add_responsive_control('content_block_margin', [
            'label' => esc_html__('Margin', 'cafe-lite'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .cafe-testimonial-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->end_controls_section();
        $this->start_controls_section('heading_avatar_settings', [
            'label' => esc_html__('Avatar', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('avatar_size', [
            'label' => esc_html__('Size', 'cafe-lite'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 300,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-avatar' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_control('avatar_bg_color', [
            'label' => esc_html__('Background', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-avatar' => 'background-color: {{VALUE}};'
            ]
        ]);

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'avatar_shadow',
                'selector' => '{{WRAPPER}} .cafe-wrap-avatar',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'avatar_border',
                'label' => esc_html__('Border', 'cafe-lite'),
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .cafe-wrap-avatar',
                'separator' => 'before',
            ]
        );
        $this->add_control('avatar_border_radius', [
            'label' => esc_html__('Border Radius', 'cafe-lite'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'separator' => 'before',
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-avatar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->add_responsive_control('avatar_padding', [
            'label' => esc_html__('Padding', 'cafe-lite'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-avatar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->add_responsive_control('avatar_margin', [
            'label' => esc_html__('Margin', 'cafe-lite'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-avatar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->end_controls_section();
        $this->start_controls_section('heading_content_settings', [
            'label' => esc_html__('Content', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('content_color', [
            'label' => esc_html__('Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-testimonial-content' => 'color: {{VALUE}};'
            ]
        ]);
        $this->add_control('content_bg_color', [
            'label' => esc_html__('Background', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-testimonial-content' => 'background: {{VALUE}};',
                '{{WRAPPER}} .cafe-testimonial-content:after' => 'border-top-color: {{VALUE}};'
            ]
        ]);
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .cafe-testimonial-content',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'content_border',
                'label' => esc_html__('Border', 'cafe-lite'),
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .cafe-testimonial-content',
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'content_shadow',
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .cafe-testimonial-content',
            ]
        );
        $this->add_responsive_control('content_padding', [
            'label' => esc_html__('Padding', 'cafe-lite'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'separator' => 'before',
            'selectors' => [
                '{{WRAPPER}} .cafe-testimonial-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->add_responsive_control('content_margin', [
            'label' => esc_html__('Margin', 'cafe-lite'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .cafe-testimonial-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->end_controls_section();
        $this->start_controls_section('heading_author_settings', [
            'label' => esc_html__('Author', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('author_color', [
            'label' => esc_html__('Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-testimonial-author' => 'color: {{VALUE}};'
            ]
        ]);
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'author_typography',
                'selector' => '{{WRAPPER}} .cafe-testimonial-author',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'author_border',
                'label' => esc_html__('Border', 'cafe-lite'),
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .cafe-testimonial-author',
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control('author_padding', [
            'label' => esc_html__('Padding', 'cafe-lite'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'separator' => 'before',
            'selectors' => [
                '{{WRAPPER}} .cafe-testimonial-author' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->add_responsive_control('author_margin', [
            'label' => esc_html__('Margin', 'cafe-lite'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .cafe-testimonial-author' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->end_controls_section();
        $this->start_controls_section('heading_des_settings', [
            'label' => esc_html__('Author Description', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('des_color', [
            'label' => esc_html__('Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-testimonial-des' => 'color: {{VALUE}};'
            ]
        ]);
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'des_typography',
                'selector' => '{{WRAPPER}} .cafe-testimonial-des',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'des_border',
                'label' => esc_html__('Border', 'cafe-lite'),
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .cafe-testimonial-des',
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control('des_padding', [
            'label' => esc_html__('Padding', 'cafe-lite'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'separator' => 'before',
            'selectors' => [
                '{{WRAPPER}} .cafe-testimonial-des' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->add_responsive_control('des_margin', [
            'label' => esc_html__('Margin', 'cafe-lite'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .cafe-testimonial-des' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->end_controls_section();
        $this->start_controls_section('heading_star_settings', [
            'label' => esc_html__('Stars', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('base_star_color', [
            'label' => esc_html__('Base Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-testimonial-rate .cafe-rate-star::before' => 'color: {{VALUE}};'
            ]
        ]);
        $this->add_control('star_color', [
            'label' => esc_html__('Rate Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-testimonial-rate .cafe-rate-star::after' => 'color: {{VALUE}};'
            ]
        ]);

        $this->add_control('star_size', [
            'label' => esc_html__('Size', 'cafe-lite'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-testimonial-rate .cafe-rate-star' => 'font-size: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_responsive_control('star_margin', [
            'label' => esc_html__('Margin', 'cafe-lite'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .cafe-testimonial-rate' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->end_controls_section();
        $this->start_controls_section('slider_nav_settings', [
            'label' => esc_html__('Navigation & Pagination', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'layout' => 'carousel'
            ],
        ]);
        $this->add_control('slider_nav_color', [
            'label' => esc_html__('Navigation Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-carousel .cafe-carousel-btn' => 'color: {{VALUE}};'
            ]
        ]);
        $this->add_control('slider_pag_color', [
            'label' => esc_html__('Pagination Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-carousel ul.slick-dots li' => 'background: {{VALUE}};--pag-color:{{VALUE}}'
            ]
        ]);
        $this->add_control('slider_nav_hover_color_heading', [
            'label' => esc_html__('Hover & Active', 'cafe-lite'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'after',
        ]);

        $this->add_control('slider_nav_hover_color', [
            'label' => esc_html__('Navigation Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-carousel .cafe-carousel-btn:hover' => 'color: {{VALUE}};'
            ]
        ]);
        $this->add_control('slider_pag_hover_color', [
            'label' => esc_html__('Pagination Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-carousel ul.slick-dots li:hover,{{WRAPPER}} .cafe-carousel ul.slick-dots li.slick-active' => 'background: {{VALUE}};--pag-color:{{VALUE}}'
            ]
        ]);
        $this->end_controls_section();
    }

    /**
     * Load style
     */
    public function get_style_depends()
    {
        return ['cafe-style'];
    }

    /**
     * Retrieve the list of scripts the image carousel widget depended on.
     *
     * Used to set scripts dependencies required to run the widget.
     *
     * @since 1.3.0
     * @access public
     *
     * @return array Widget scripts dependencies.
     */
    public function get_script_depends()
    {
        return ['jquery-slick', 'cafe-script'];
    }

    /**
     * Render
     */
    protected function render()
    {
        $settings = array_merge([ // default settings
            'content' => '',
            'layout' => 'grid',
            'style' => 'default',
            'col' => 1,
            'show_quotation' => '',
            'show_des' => '',
            'show_nav' => '',
            'show_pag' => '',
            'show_avatar' => '',
            'show_star' => 'false',
            'autoplay' => '',
            'autoplay_speed' => '3000',
            'css_class' => '',

        ], $this->get_settings_for_display());


        $this->getViewTemplate('template', 'testimonial', $settings);
    }
}