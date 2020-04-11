<?php

namespace Cafe\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;

/**
 * Clever Testimonial
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
final class CleverServices extends CleverWidgetBase
{
    /**
     * @return string
     */
    function get_name()
    {
        return 'clever-services';
    }

    /**
     * @return string
     */
    function get_title()
    {
        return esc_html__('CAFE Services', 'cafe-lite');
    }

    /**
     * @return string
     */
    function get_icon()
    {
        return 'cs-font  clever-icon-setting';
    }

    /**
     * Register controls
     */
    protected function _register_controls()
    {

        $repeater = new \Elementor\Repeater();
        $repeater->add_control('thumb', [
            'label' => esc_html__('Thumbnail', 'cafe-lite'),
            'type' => Controls_Manager::MEDIA,
        ]);
        $repeater->add_control('icon', [
            'label' => esc_html__('Icon', 'cafe-lite'),
            'default' => 'cs-font icon-plane-2',
            'type' => 'clevericon',
        ]);
        $repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'cafe-lite'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,

            ]
        );
        $repeater->add_control(
            'des',
            [
                'label' => esc_html__('Description', 'cafe-lite'),
                'type' => Controls_Manager::TEXTAREA,
            ]
        );
        $repeater->add_control('button_icon', [
            'label' => esc_html__('Button Icon', 'cafe-lite'),
            'default' => 'cs-font icon-arrow-right',
            'type' => 'clevericon',
        ]);
        $repeater->add_control(
            'button_label',
            [
                'label' => esc_html__('Button', 'cafe-lite'),
                'type' => Controls_Manager::TEXT,
            ]
        );
        $repeater->add_control( 'button_style', [
            'label'   => esc_html__('Button style', 'cafe-lite' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'normal',
            'options' => [
                'normal'    => esc_html__('Normal', 'cafe-lite' ),
                'underline' => esc_html__('Underline', 'cafe-lite' ),
                'outline'   => esc_html__('Outline', 'cafe-lite' ),
                'flat'   => esc_html__('Flat', 'cafe-lite' ),
            ]
        ] );
        $repeater->add_control(
            'target_url',
            [
                'label' => esc_html__('URL', 'cafe-lite'),
                'type' => Controls_Manager::URL,
                'description' => esc_html__('Url of page service want assign when click', 'cafe-lite'),
            ]
        );

        $this->start_controls_section('content_settings', [
            'label' => esc_html__('Content Settings', 'cafe-lite')
        ]);
        $this->add_control('content', [
            'label' => esc_html__('Services', 'cafe-lite'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'title_field' => '{{{ title }}}',
            'default' => [
                [
                    'title' => esc_html__('Service 1', 'cafe-lite'),
                ],[
                    'title' => esc_html__('Service 2', 'cafe-lite'),
                ],
            ],

        ]);
        $this->add_responsive_control(
            'content_block_align',
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
                    '{{WRAPPER}} .cafe-wrap-service-content' => 'text-align: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control( 'vertical_align', [
            'label'                => esc_html__('Vertical Align', 'cafe-lite' ),
            'type'                 => Controls_Manager::CHOOSE,
            'label_block'          => false,
            'options'              => [
                'top'    => [
                    'title' => esc_html__('Top', 'cafe-lite' ),
                    'icon'  => 'eicon-v-align-top',
                ],
                'middle' => [
                    'title' => esc_html__('Middle', 'cafe-lite' ),
                    'icon'  => 'eicon-v-align-middle',
                ],
                'bottom' => [
                    'title' => esc_html__('Bottom', 'cafe-lite' ),
                    'icon'  => 'eicon-v-align-bottom',
                ],
            ],
            'selectors'            => [
                '{{WRAPPER}} .cafe-wrap-service' => 'align-items: {{VALUE}}',
            ],
            'selectors_dictionary' => [
                'top'    => 'flex-start',
                'middle' => 'center',
                'bottom' => 'flex-end',
            ],
        ] );
        $this->end_controls_section();
        $this->start_controls_section('layout_settings', [
            'label' => esc_html__('Layout Settings', 'cafe-lite')
        ]);
        $this->add_control('layout', [
            'label' => esc_html__('Layout', 'cafe-lite'),
            'type' => Controls_Manager::SELECT,
            'default' => 'grid',
            'options' => [
                'grid' => esc_html__('Grid', 'cafe-lite'),
                'carousel' => esc_html__('Carousel', 'cafe-lite'),
            ],
            'description' => esc_html__('Layout of service.', 'cafe-lite'),
        ]);
        $this->add_control('style', [
            'label' => esc_html__('Style', 'cafe-lite'),
            'type' => Controls_Manager::SELECT,
            'default' => 'default',
            'options' => [
                'default' => esc_html__('Default', 'cafe-lite'),
                'boxed' => esc_html__('Boxed', 'cafe-lite'),
            ],
            'description' => esc_html__('Style of service.', 'cafe-lite'),
        ]);
        $this->add_control(
            'layout_type',
            [
                'label' => esc_html__('Image/Icon position', 'cafe-lite'),
                'type' => Controls_Manager::CHOOSE,
                'default'=>'left',
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
                'condition' => [
                    'style' => 'default'
                ],
            ]
        );

        $this->add_responsive_control('col', [
            'label' => esc_html__('Columns Show', 'elementor'),
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
                'layout' => 'grid'
            ],
        ]);
        $this->add_control('row', [
            'label' => esc_html__('Row', 'elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => '1',
            'condition' => [
                'layout' => 'carousel'
            ],
        ]);
        $this->add_responsive_control('slides_to_show',[
            'label'         => esc_html__('Slides to Show', 'elementor' ),
            'type'          => Controls_Manager::SLIDER,
            'range' => [
                'px' =>[
                    'min' => 1,
                    'max' => 10,
                ]
            ],
            'devices' => [ 'desktop', 'tablet', 'mobile' ],
            'desktop_default' => [
                'size' => 4,
                'unit' => 'px',
            ],
            'tablet_default' => [
                'size' => 3,
                'unit' => 'px',
            ],
            'mobile_default' => [
                'size' => 2,
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
            'description' => esc_html__('Number services scroll per time.', 'cafe-lite'),
            'condition' => [
                'layout' => 'carousel'
            ],

        ]);
        $this->add_responsive_control('show_nav', [
            'label' => esc_html__('Navigation', 'cafe-lite'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'true',
            'default' => 'false',
            'condition' => [
                'layout' => 'carousel'
            ],
        ]);
        $this->add_responsive_control('show_pag', [
            'label' => esc_html__('Pagination', 'cafe-lite'),
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
            'label' => esc_html__('Auto play speed', 'cafe-lite'),
            'type' => Controls_Manager::NUMBER,
            'default' => '3000',
            'condition' => [
                'layout' => 'carousel',
                'autoplay' => 'true'
            ],
            'description' => esc_html__('Time(ms) to next slider.', 'cafe-lite'),
        ]);
        $this->end_controls_section();

        $this->start_controls_section('service_style_settings', [
            'label' => esc_html__('Service', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('service_space', [
            'label' => esc_html__('Space', 'cafe-lite'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 300,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-service-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_control('service_bg_color', [
            'label' => esc_html__('Background', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-service' => 'background-color: {{VALUE}};'
            ]
        ]);

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'service_shadow',
                'selector' => '{{WRAPPER}} .cafe-wrap-service',
            ]
        );

        $this->add_control('overlay_heading', [
            'label' => esc_html__('Overlay background', 'cafe-lite'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);
        $this->add_control( 'bg_overlay_blend_mode', [
            'label'      => esc_html__('Blend Mode', 'cafe-lite' ),
            'type'       => Controls_Manager::SELECT,
            'options'    => [
                ''            => esc_html__('Normal', 'cafe-lite' ),
                'multiply'    => 'Multiply',
                'screen'      => 'Screen',
                'overlay'     => 'Overlay',
                'darken'      => 'Darken',
                'lighten'     => 'Lighten',
                'color-dodge' => 'Color Dodge',
                'color-burn'  => 'Color Burn',
                'hue'         => 'Hue',
                'saturation'  => 'Saturation',
                'color'       => 'Color',
                'exclusion'   => 'Exclusion',
                'luminosity'  => 'Luminosity',
            ],
            'selectors'  => [
                '{{WRAPPER}} .cafe-wrap-media:after' => 'mix-blend-mode: {{VALUE}}',
            ],
        ] );
        $this->start_controls_tabs( 'overlay_tabs' );

        $this->start_controls_tab( 'normal', [ 'label' => esc_html__('Normal', 'cafe-lite' ) ] );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'overlay_bg',
                'label' => esc_html__('Background Overlay', 'cafe-lite'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .cafe-wrap-media:before',
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab( 'hover', [ 'label' => esc_html__('Hover', 'cafe-lite' ) ] );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'hover_overlay_bg',
                'label' => esc_html__('Background Overlay', 'cafe-lite'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .cafe-wrap-media:after',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'service_border',
                'label' => esc_html__('Border', 'cafe-lite'),
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .cafe-wrap-service',
                'separator' => 'before',
            ]
        );
        $this->add_control('service_border_radius', [
            'label' => esc_html__('Border Radius', 'cafe-lite'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'separator' => 'before',
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-service' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->add_responsive_control('service_padding', [
            'label' => esc_html__('Padding', 'cafe-lite'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-service' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->end_controls_section();
        $this->start_controls_section('service_hover_style_settings', [
            'label' => esc_html__('Service Hover', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('service_bg_hv_color', [
            'label' => esc_html__('Background', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-service:hover' => 'background-color: {{VALUE}};'
            ]
        ]);$this->add_control('service_border_hv_color', [
            'label' => esc_html__('Border', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-service:hover' => 'border-color: {{VALUE}};'
            ]
        ]);
        $this->add_control('service_title_hv_color', [
            'label' => esc_html__('Title Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-service:hover .cafe-service-title' => 'color: {{VALUE}};'
            ]
        ]); $this->add_control('service_des_hv_color', [
            'label' => esc_html__('Description Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-service:hover .cafe-service-des' => 'color: {{VALUE}};'
            ]
        ]);
        $this->end_controls_section();
        $this->start_controls_section('thumb_icon_style_settings', [
            'label' => esc_html__('Thumb/Icon', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('thumb_size', [
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
                '{{WRAPPER}} .cafe-wrap-media' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .cafe-wrap-service-content' => 'width: calc(100% - {{SIZE}}{{UNIT}});',
            ],
        ]);
        $this->add_control('thumb_font_size', [
            'label' => esc_html__('Font Size', 'cafe-lite'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 300,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-media' => 'font-size: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_control('thumb_color', [
            'label' => esc_html__('Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-media' => 'color: {{VALUE}};'
            ]
        ]);
        $this->add_control('thumb_bg_color', [
            'label' => esc_html__('Background', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-media' => 'background-color: {{VALUE}};'
            ]
        ]);

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'thumb_shadow',
                'selector' => '{{WRAPPER}} .cafe-wrap-media',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'thumb_border',
                'label' => esc_html__('Border', 'cafe-lite'),
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .cafe-wrap-media',
                'separator' => 'before',
            ]
        );
        $this->add_control('thumb_border_radius', [
            'label' => esc_html__('Border Radius', 'cafe-lite'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'separator' => 'before',
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-media' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->add_responsive_control('thumb_padding', [
            'label' => esc_html__('Padding', 'cafe-lite'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-media' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->add_responsive_control('thumb_margin', [
            'label' => esc_html__('Margin', 'cafe-lite'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-media' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->end_controls_section();

        $this->start_controls_section('title_des_block_settings', [
            'label' => esc_html__('Title & Description', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('title_style', [
            'label' => esc_html__('Title', 'cafe-lite'),
            'type' => Controls_Manager::HEADING,
        ]);
        $this->add_control('title_color', [
            'label' => esc_html__('Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-service-title' => 'color: {{VALUE}};'
            ]
        ]);
        $this->add_control('title_space', [
            'label' => esc_html__('Space', 'cafe-lite'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => -100,
                    'max' => 300,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-service-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .cafe-service-title',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
            ]
        );
        $this->add_control('des_style', [
            'label' => esc_html__('Description', 'cafe-lite'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);
        $this->add_control('des_color', [
            'label' => esc_html__('Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-service-des' => 'color: {{VALUE}};'
            ]
        ]);
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'des_typography',
                'selector' => '{{WRAPPER}} .cafe-service-des',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
            ]
        );
        $this->add_control('des_space', [
            'label' => esc_html__('Space', 'cafe-lite'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 300,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-service-des' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_responsive_control('content_block_padding', [
            'label' => esc_html__('Padding', 'cafe-lite'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'separator' => 'before',
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-service-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->end_controls_section();
        $this->start_controls_section( 'section_style_button', [
            'label' => esc_html__('Button', 'cafe-lite' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'button_typography',
            'selector' => '{{WRAPPER}} .cafe-slide-btn',
            'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
        ] );

        $this->add_control( 'button_border_width', [
            'label'     => esc_html__('Border Width', 'cafe-lite' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [
                'px' => [
                    'min' => 0,
                    'max' => 20,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-button:before' => 'border-width: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_control( 'button_border_radius', [
            'label'     => esc_html__('Border Radius', 'cafe-lite' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-button' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ] );
        $this->add_responsive_control( 'button_padding', [
            'label'      => esc_html__('Padding', 'cafe-lite' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .cafe-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'separator' => 'after',
        ] );
        $this->start_controls_tabs( 'button_style_tabs' );

        $this->start_controls_tab( 'button_normal', [ 'label' => esc_html__('Normal', 'cafe-lite' ) ] );

        $this->add_control( 'button_text_color', [
            'label'     => esc_html__('Text Color', 'cafe-lite' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-button' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'button_background_color', [
            'label'     => esc_html__('Background Color', 'cafe-lite' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-button:after' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'button_border_color', [
            'label'     => esc_html__('Border Color', 'cafe-lite' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-button:before' => 'border-color: {{VALUE}};',
            ],
        ] );

        $this->end_controls_tab();

        $this->start_controls_tab( 'button_hover', [ 'label' => esc_html__('Hover', 'cafe-lite' ) ] );

        $this->add_control( 'button_hover_text_color', [
            'label'     => esc_html__('Text Color', 'cafe-lite' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-button:hover' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'button_hover_background_color', [
            'label'     => esc_html__('Background Color', 'cafe-lite' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-button:hover:after' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'button_hover_border_color', [
            'label'     => esc_html__('Border Color', 'cafe-lite' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-button:hover:before' => 'border-color: {{VALUE}};',
            ],
        ] );

        $this->end_controls_tab();
        $this->end_controls_tabs();
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
                '{{WRAPPER}} .cafe-carousel ul.slick-dots li' => 'background: {{VALUE}};'
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
                '{{WRAPPER}} .cafe-carousel ul.slick-dots li:hover,{{WRAPPER}} .cafe-carousel ul.slick-dots li.slick-active' => 'background: {{VALUE}};'
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
            'layout_type' => 'left',
            'col' => 1,
            'show_nav' => '',
            'show_pag' => '',
            'autoplay' => '',
            'autoplay_speed' => '3000',

        ], $this->get_settings_for_display());


        $this->getViewTemplate('template', 'services', $settings);
    }
}