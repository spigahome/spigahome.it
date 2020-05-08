<?php namespace Cafe\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;

/**
 * CleverSiteLogo
 */
if (!class_exists('WooCommerce') || (get_theme_mod( 'zoo_enable_compare', '1' ) != 1 || !class_exists( 'CleverAddons' ))) {
    return;
}

final class CleverCompare extends CleverWidgetBase
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
        return 'clever-compare';
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
        return __('Clever Compare', 'cafe-pro');
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
        return 'cs-font clever-icon-refresh-5';
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
        return ['site', 'compare', 'woocommerce'];
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
                'label' => __('Wishtlist', 'cafe-pro'),
            ]
        );
        $this->add_control('icon', [
            'label' => __('Compare Icon', 'cafe-pro'),
            'type' => 'clevericon',
            'default' => 'cs-font clever-icon-refresh-5',
        ]);
        $this->add_control(
            'link_only',
            [
                'label' => __('Link Only', 'cafe-pro'),
                'description' => __('Redirect to account page when click', 'cafe-pro'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'no'
            ]
        );
        $this->add_control(
            'show_count',
            [
                'label' => __('Show Count', 'cafe-pro'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'no'
            ]
        );
        $this->add_control( 'count_position', [
            'label'   => __( 'Count position', 'cafe-pro' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'top-right',
            'options' => [
                'top-right' => __( 'Top right', 'cafe-pro' ),
                'top-left' => __( 'Top left', 'cafe-pro' ),
                'center' => __( 'Center', 'cafe-pro' ),
            ],
            'condition' => [
                'show_count' => 'yes'
            ],
        ] );
        $this->add_control(
            'show_label',
            [
                'label' => __('Show Label', 'cafe-pro'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'no'
            ]
        );
        $this->add_control('label', [
            'label' => __('Label', 'cafe-pro'),
            'type' => Controls_Manager::TEXT,
            'default' => __('Compare', 'cafe-pro'),
            'condition' => [
                'show_label' => 'yes'
            ],
        ]);
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
        $this->start_controls_section(
            'section_compare_icon_style',
            [
                'label' => __('Icon', 'cafe-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control('compare_icon_wrap_size', [
            'label' => __('Block Size', 'cafe-pro'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-wlcp-icon i' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
            ]
        ]);
        $this->add_responsive_control('compare_icon_size', [
            'label' => __('Icon Size', 'cafe-pro'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-wlcp-icon>i' => 'font-size:{{SIZE}}{{UNIT}}',
            ]
        ]);
        $this->add_responsive_control('compare_icon_border_radius', [
            'label' => __('Border Radius', 'cafe-pro'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ]
            ],
            'separator' => 'after',
            'selectors' => [
                '{{WRAPPER}} .cafe-wlcp-icon>i' => 'border-radius:{{SIZE}}{{UNIT}}',
            ]
        ]);
        $this->start_controls_tabs('compare_icon_tabs');
        $this->start_controls_tab(
            'compare_icon_tab',
            [
                'label' => esc_html__('Normal', 'cafe-pro'),
            ]
        );
        $this->add_control(
            'compare_icon_btn_color',
            [
                'label' => esc_html__('Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-wlcp-icon i' => 'color: {{VALUE}}'
                ]
            ]
        );
        $this->add_control(
            'compare_icon_btn_bg',
            [
                'label' => esc_html__('Background Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-wlcp-icon i' => 'background-color: {{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'compare_icon_hover_tab',
            [
                'label' => esc_html__('Hover', 'cafe-pro'),
            ]
        );
        $this->add_control(
            'compare_icon_btn_hv_color',
            [
                'label' => esc_html__('Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-wlcp:hover i' => 'color: {{VALUE}}'
                ]
            ]
        );
        $this->add_control(
            'compare_icon_btn_hv_bg',
            [
                'label' => esc_html__('Background Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-wlcp:hover i' => 'background-color: {{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'compare_icon_btn_border',
                'label' => __('Border', 'cafe-pro'),
                'placeholder' => '',
                'default' => '',
                'selector' => '{{WRAPPER}} .cafe-wlcp-icon>i',
                'separator' => 'before',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_compare_label_style',
            [
                'label' => __('Label', 'cafe-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_label' => 'yes'
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'compare_label_typo',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .cafe-wlcp-label',
            ]
        );
        $this->add_responsive_control('compare_label_space', [
            'label' => __('Space', 'cafe-pro'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-wlcp-icon' => 'margin-right:{{SIZE}}{{UNIT}}',
            ]
        ]);
        $this->add_control(
            'compare_label_color',
            [
                'label' => esc_html__('Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-wlcp-label' => 'color: {{VALUE}}'
                ]
            ]
        );
        $this->add_control(
            'compare_label_hv_color',
            [
                'label' => esc_html__('Color hover', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-wlcp:hover .cafe-wlcp-label' => 'color: {{VALUE}}'
                ]
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_compare_counter_style',
            [
                'label' => __('Count', 'cafe-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_count' => 'yes'
                ],
            ]
        );
        $this->add_responsive_control('compare_counter_size', [
            'label' => __('Size', 'cafe-pro'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-wlcp-counter' => 'min-width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
            ]
        ]);
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'compare_counter_typo',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .cafe-wlcp-counter',
            ]
        );
        $this->add_responsive_control('compare_counter_border_radius', [
            'label' => __('Border Radius', 'cafe-pro'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ]
            ],
            'separator' => 'after',
            'selectors' => [
                '{{WRAPPER}} .cafe-wlcp-counter' => 'border-radius:{{SIZE}}{{UNIT}}',
            ]
        ]);
        $this->add_control(
            'compare_counter_btn_color',
            [
                'label' => esc_html__('Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-wlcp-counter' => 'color: {{VALUE}}'
                ]
            ]
        );
        $this->add_control(
            'compare_counter_btn_bg',
            [
                'label' => esc_html__('Background Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-wlcp-counter' => 'background-color: {{VALUE}}'
                ]
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
        $this->getViewTemplate('template', 'compare', $settings);
    }
}
