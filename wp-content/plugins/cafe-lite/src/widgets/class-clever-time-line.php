<?php namespace Cafe\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;

/**
 * Clever Time Line
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
final class CleverTimeLine extends CleverWidgetBase
{
    /**
     * @return string
     */
    function get_name()
    {
        return 'clever-time-line';
    }

    /**
     * @return string
     */
    function get_title()
    {
        return esc_html__('CAFE Time Line', 'cafe-lite');
    }

    /**
     * @return string
     */
    function get_icon()
    {
        return 'cs-font clever-icon-news-list';
    }

    /**
     * Register controls
     */
    protected function _register_controls()
    {
        $repeater = new \Elementor\Repeater();
        $repeater->add_control('date', [
            'label' => esc_html__('Date', 'cafe-lite'),
            'type' => Controls_Manager::TEXT,
            'description' => esc_html__('Date of time line.', 'cafe-lite'),
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
        $this->start_controls_section('settings', [
            'label' => esc_html__('Settings', 'cafe-lite')
        ]);
        $this->add_control('timeline', [
            'label' => esc_html__('Time Line Content', 'cafe-lite'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'title_field' => '{{{ date }}}',
            'separator' => 'after',
        ]);
        $this->add_control('layout', [
            'label' => esc_html__('Layout', 'cafe-lite'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'list' => esc_html__('List', 'cafe-lite'),
                'carousel' => esc_html__('Carousel', 'cafe-lite'),
            ],
        ]);
        $this->add_responsive_control('cols', [
            'label' => esc_html__('Columns', 'cafe-lite'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 10,
                ],
            ],
            'devices' => ['desktop', 'tablet', 'mobile'],
            'desktop_default' => [
                'size' => 3,
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
            'conditions' => [
                'terms' => [
                    [
                        'name' => 'layout',
                        'operator' => '==',
                        'value' => 'carousel',
                    ],
                ],
            ],
        ]);
        $this->add_control('center_mode', [
            'label' => esc_html__('Center mode', 'cafe-lite'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'true',
            'default' => 'true',
            'conditions' => [
                'terms' => [
                    [
                        'name' => 'layout',
                        'operator' => '==',
                        'value' => 'carousel',
                    ],
                ],
            ],
        ]);
        $this->add_control('speed', [
            'label' => esc_html__('Carousel: Speed to Scroll', 'cafe-lite'),
            'description' => esc_html__('', 'cafe-lite'),
            'type' => Controls_Manager::NUMBER,
            'default' => 5000,
            'conditions' => [
                'terms' => [
                    [
                        'name' => 'layout',
                        'operator' => '==',
                        'value' => 'carousel',
                    ],
                ],
            ],
        ]);
        $this->add_control('scroll', [
            'label' => esc_html__('Carousel: Slide to Scroll', 'cafe-lite'),
            'description' => esc_html__('', 'cafe-lite'),
            'type' => Controls_Manager::NUMBER,
            'default' => 1,
            'conditions' => [
                'terms' => [
                    [
                        'name' => 'layout',
                        'operator' => '==',
                        'value' => 'carousel',
                    ],
                ],
            ],
        ]);
        $this->add_responsive_control('autoplay', [
            'label' => esc_html__('Carousel: Auto Play', 'cafe-lite'),
            'description' => esc_html__('', 'cafe-lite'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Show', 'cafe-lite'),
            'label_off' => esc_html__('Hide', 'cafe-lite'),
            'return_value' => 'true',
            'default' => 'true',
            'conditions' => [
                'terms' => [
                    [
                        'name' => 'layout',
                        'operator' => '==',
                        'value' => 'carousel',
                    ],
                ],
            ],
        ]);
        $this->add_responsive_control('show_pag', [
            'label' => esc_html__('Carousel: Pagination', 'cafe-lite'),
            'description' => esc_html__('', 'cafe-lite'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Show', 'cafe-lite'),
            'label_off' => esc_html__('Hide', 'cafe-lite'),
            'return_value' => 'true',
            'default' => 'true',
            'conditions' => [
                'terms' => [
                    [
                        'name' => 'layout',
                        'operator' => '==',
                        'value' => 'carousel',
                    ],
                ],
            ],
        ]);
        $this->add_responsive_control('show_nav', [
            'label' => esc_html__('Carousel: Navigation', 'cafe-lite'),
            'description' => esc_html__('', 'cafe-lite'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Show', 'cafe-lite'),
            'label_off' => esc_html__('Hide', 'cafe-lite'),
            'return_value' => 'true',
            'default' => 'true',
            'conditions' => [
                'terms' => [
                    [
                        'name' => 'layout',
                        'operator' => '==',
                        'value' => 'carousel',
                    ],
                ],
            ],
        ]);
        $this->end_controls_section();
        $this->start_controls_section('style_settings', [
            'label' => esc_html__('Style', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_responsive_control(
            'text_align',
            [
                'label' => esc_html__('Content Align', 'cafe-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'cafe-lite'),
                        'icon' => 'fa fa-align-left',
                    ], 'center' => [
                        'title' => esc_html__('Center', 'cafe-lite'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'cafe-lite'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .cafe-timeline-item' => 'text-align: {{VALUE}};'
                ]
            ]
        );
        $this->add_control('border_color', [
            'label' => esc_html__('Border Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'description' => esc_html__('Border color in left side', 'cafe-lite'),
            'selectors' => [
                '{{WRAPPER}} .cafe-timeline' => 'border-color: {{COLOR}}',
            ],
        ]);
        $this->add_control('border_width', [
            'label' => esc_html__('Border width', 'cafe-lite'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 200,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-timeline' => 'border-left-width: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_control('dot_color', [
            'label' => esc_html__('Dot Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'description' => esc_html__('Dot color in left side', 'cafe-lite'),
            'selectors' => [
                '{{WRAPPER}} .cafe-timeline .cafe-timeline-date::before' => 'color: {{COLOR}}',
                '{{WRAPPER}} .cafe-timeline .cafe-timeline-date::after' => 'background-color: {{COLOR}}',
            ],
        ]);
        $this->add_control('dot_size', [
            'label' => esc_html__('Dot size', 'cafe-lite'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 200,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-timeline .cafe-timeline-date::before' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .cafe-timeline .cafe-timeline-date::after' => 'width: calc({{SIZE}}{{UNIT}} - 10px);height:  calc({{SIZE}}{{UNIT}} - 10px);'
            ],
        ]);
        $this->add_control('date_style_heading', [
            'label' => esc_html__('Date', 'cafe-lite'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'date_typography',
                'selector' => '{{WRAPPER}} .cafe-timeline .cafe-timeline-date',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
            ]
        );
        $this->add_control('date_color', [
            'label' => esc_html__('Date Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-timeline .cafe-timeline-date' => 'color: {{COLOR}}',
            ],
        ]);
        $this->add_control('title_style_heading', [
            'label' => esc_html__('Title', 'cafe-lite'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .cafe-timeline .cafe-timeline-title',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
            ]
        );
        $this->add_control('title_color', [
            'label' => esc_html__('Title Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-timeline .cafe-timeline-title' => 'color: {{COLOR}}',
            ],
        ]);
        $this->add_control('des_style_heading', [
            'label' => esc_html__('Description', 'cafe-lite'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'des_typography',
                'selector' => '{{WRAPPER}} .cafe-timeline .cafe-timeline-description',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
            ]
        );
        $this->add_control('des_color', [
            'label' => esc_html__('Description Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-timeline .cafe-timeline-description' => 'color: {{COLOR}}',
            ],
        ]);
        $this->end_controls_section();
        $this->start_controls_section('style_center_mod_settings', [
            'label' => esc_html__('Center Mode', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
            'conditions' => [
                'terms' => [
                    [
                        'name' => 'layout',
                        'operator' => '==',
                        'value' => 'carousel',
                    ], [
                        'name' => 'center_mode',
                        'operator' => '==',
                        'value' => 'true',
                    ],
                ],
            ],
        ]);
        $this->add_control('date_center_style_heading', [
            'label' => esc_html__('Date', 'cafe-lite'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'date_center_typography',
                'selector' => '{{WRAPPER}} .cafe-timeline-item.slick-center .cafe-timeline-date',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
            ]
        );
        $this->add_control('date_center_color', [
            'label' => esc_html__('Date Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-timeline-item.slick-center .cafe-timeline-date' => 'color: {{COLOR}}',
            ],
        ]);
        $this->add_control('title_center_style_heading', [
            'label' => esc_html__('Title', 'cafe-lite'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_center_typography',
                'selector' => '{{WRAPPER}} .cafe-timeline-item.slick-center .cafe-timeline-title',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
            ]
        );
        $this->add_control('title_center_color', [
            'label' => esc_html__('Title Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-timeline-item.slick-center .cafe-timeline-title' => 'color: {{COLOR}}',
            ],
        ]);
        $this->add_control('des_center_style_heading', [
            'label' => esc_html__('Description', 'cafe-lite'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'des_center_typography',
                'selector' => '{{WRAPPER}} .cafe-timeline-item.slick-center.cafe-timeline-description',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
            ]
        );
        $this->add_control('des_center_color', [
            'label' => esc_html__('Description Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-timeline-item.slick-center .cafe-timeline-description' => 'color: {{COLOR}}',
            ],
        ]);
        $this->add_control('center_bottom_space', [
            'label' => esc_html__('Space', 'cafe-lite'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 200,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-timeline .slick-center .cafe-head-timeline-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->end_controls_section();

        $this->start_controls_section('style_carousel_navigation_settings', [
            'label' => esc_html__('Navigation', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
            'conditions' => [
                'terms' => [
                    [
                        'name' => 'layout',
                        'operator' => '==',
                        'value' => 'carousel',
                    ],
                    [
                        'name' => 'show_nav',
                        'operator' => '==',
                        'value' => 'true',
                    ],
                ],
            ],
        ]);
        $this->start_controls_tabs('navigation_carousel_tabs');

        $this->start_controls_tab('normal', ['label' => esc_html__('Normal', 'cafe-lite')]);

        $this->add_control('carousel_navigation_color', [
            'label' => esc_html__('Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-carousel ul.slick-dots li' => 'background: {{COLOR}}',
            ],
        ]);
        $this->add_control('carousel_navigation_width', [
            'label' => esc_html__('Width', 'cafe-lite'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 200,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-carousel ul.slick-dots li' => 'width:{{SIZE}}{{UNIT}};',
            ],
        ]);$this->add_control('carousel_navigation_height', [
            'label' => esc_html__('Height', 'cafe-lite'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 200,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-carousel ul.slick-dots li' => 'height:{{SIZE}}{{UNIT}}',
            ],
        ]);
        $this->add_control('carousel_navigation_space', [
            'label' => esc_html__('Space', 'cafe-lite'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 200,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-carousel ul.slick-dots li' => 'margin:0 calc({{SIZE}}{{UNIT}}/2);',
            ],
        ]);
        $this->add_control('carousel_navigation_border_radius', [
            'label' => esc_html__('Border Radius', 'cafe-lite'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px','%'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 200,
                ],'%' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-carousel ul.slick-dots li' => 'border-radius:{{SIZE}}{{UNIT}}',
            ],
        ]);
        $this->end_controls_tab();

        $this->start_controls_tab('hover', ['label' => esc_html__('Hover/Active', 'cafe-lite')]);
        $this->add_control('carousel_navigation_color_hover', [
            'label' => esc_html__('Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-carousel ul.slick-dots li.slick-active,{{WRAPPER}} .cafe-carousel ul.slick-dots li:hover' => 'background: {{COLOR}}',
            ],
        ]);
        $this->add_control('carousel_navigation_width_hover', [
            'label' => esc_html__('Width', 'cafe-lite'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 200,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-carousel ul.slick-dots li.active,{{WRAPPER}} .cafe-carousel ul.slick-dots li:hover' => 'width:{{SIZE}}{{UNIT}};',
            ],
        ]);        $this->add_control('carousel_navigation_height_hover', [
            'label' => esc_html__('Height', 'cafe-lite'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 200,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-carousel ul.slick-dots li.active,{{WRAPPER}} .cafe-carousel ul.slick-dots li:hover' => 'height:{{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_control('carousel_navigation_border_radius_hover', [
            'label' => esc_html__('Border Radius', 'cafe-lite'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px','%'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 200,
                ],'%' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-carousel ul.slick-dots li.active,{{WRAPPER}} .cafe-carousel ul.slick-dots li:hover' => 'border-radius:{{SIZE}}{{UNIT}}',
            ],
        ]);
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
        $this->start_controls_section('style_carousel_pagination_settings', [
            'label' => esc_html__('Pagination', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
            'conditions' => [
                'terms' => [
                    [
                        'name' => 'layout',
                        'operator' => '==',
                        'value' => 'carousel',
                    ],
                    [
                        'name' => 'show_pag',
                        'operator' => '==',
                        'value' => 'true',
                    ],
                ],
            ],
        ]);
        $this->add_control( 'pag_arrow_left_icon', [
            'label'     => esc_html__('Arrow Left', 'cafe-lite' ),
            'type'      => 'clevericon',
        ] );
        $this->add_control( 'pag_arrow_right_icon', [
            'label'     => esc_html__('Arrow Right', 'cafe-lite' ),
            'type'      => 'clevericon',
        ] );
        $this->add_control('carousel_pag_size', [
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
                '{{WRAPPER}} .cafe-carousel .cafe-carousel-btn' => 'height:{{SIZE}}{{UNIT}};line-height:{{SIZE}}{{UNIT}};width:{{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_control('carousel_pag_icon_size', [
            'label' => esc_html__('Icon Size', 'cafe-lite'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 300,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-carousel .cafe-carousel-btn' => 'font-size:{{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_control('carousel_pag_border_radius', [
            'label' => esc_html__('Border Radius', 'cafe-lite'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px','%'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 200,
                ],'%' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-carousel .cafe-carousel-btn' => 'border-radius:{{SIZE}}{{UNIT}}',
            ],
        ]);
        $this->start_controls_tabs('pag_carousel_tabs');

        $this->start_controls_tab('pag_carousel_normal', ['label' => esc_html__('Normal', 'cafe-lite')]);

        $this->add_control('pag_navigation_color', [
            'label' => esc_html__('Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-carousel .cafe-carousel-btn' => 'color: {{COLOR}}',
            ],
        ]);
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'pag_border',
                'label' => esc_html__('Border', 'cafe-lite'),
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .cafe-carousel .cafe-carousel-btn',
                'separator' => 'before',
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab('pag_carousel_hover', ['label' => esc_html__('Hover', 'cafe-lite')]);
        $this->add_control('carousel_page_color_hover', [
            'label' => esc_html__('Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-carousel .cafe-carousel-btn:hover' => 'color: {{COLOR}}',
            ],
        ]);
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'pag_border_hover',
                'label' => esc_html__('Border', 'cafe-lite'),
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .cafe-carousel .cafe-carousel-btn:hover',
                'separator' => 'before',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

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
     * Render
     */
    protected function render()
    {
        $settings = array_merge([ // default settings
            'timeline' => '',
            'layout' => 'list',
            'center_mode' => 'true',
            'cols' => '3',
        ], $this->get_settings_for_display());

        $this->getViewTemplate('template', 'timeline', $settings);
    }
}
