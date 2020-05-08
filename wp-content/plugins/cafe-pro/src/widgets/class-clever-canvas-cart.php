<?php namespace Cafe\Widgets;

use Elementor\Scheme_Color;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;

/**
 * CleverSiteLogo
 */
final class CleverCanvasCart extends CleverWidgetBase
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
        return 'clever-canvas-cart';
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
        return __('Clever Canvas Cart', 'cafe-pro');
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
        return 'cs-font clever-icon-cart-8';
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
        return ['site', 'cart', 'canvas'];
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
                'label' => __('Cart', 'cafe-pro'),
            ]
        );
        $this->add_control( 'icon', [
            'label'   => __( 'Cart Icon', 'cafe-pro' ),
            'type'    => 'clevericon',
            'default' => 'cs-font clever-icon-cart-8',
        ] );
        $this->add_control(
            'subtotal',
            [
                'label'        => __( 'Cart subtotal', 'cafe-pro' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'no'
            ]
        );
        $this->add_control( 'subtotal_position', [
            'label'   => __( 'Subtotal position', 'cafe-pro' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'after',
            'options' => [
                'before' => __( 'Before', 'cafe-pro' ),
                'after' => __( 'After', 'cafe-pro' ),

            ],
            'condition' => [
                'subtotal' => 'yes'
            ],
        ] );
        $this->add_control(
            'cart_count',
            [
                'label'        => __( 'Cart count', 'cafe-pro' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes'
            ]
        );
        $this->add_control( 'cart_count_position', [
            'label'   => __( 'Cart count position', 'cafe-pro' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'top-right',
            'options' => [
                'top-right' => __( 'Top Right', 'cafe-pro' ),
                'top-left' => __( 'Top left', 'cafe-pro' ),
                'center' => __( 'Center', 'cafe-pro' ),
                'inline' => __( 'Inline', 'cafe-pro' ),
                'custom' => __( 'Custom', 'cafe-pro' ),
            ],
            'condition' => [
                'cart_count' => 'yes'
            ],
        ] );
        $this->add_control(
            'cart_count_position_custom',
            [
                'label' => esc_html__('Custom Position', 'cafe-pro'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'separator' => 'after',
                'selectors' => [
                    '{{WRAPPER}} .cafe-cart-count' => 'margin:  {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'cart_count_position' => 'custom'
                ],
            ]
        );
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
            'section_cart_icon_style',
            [
                'label' => __('Cart Icon Style', 'cafe-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control('cart_icon_wrap_size', [
            'label' => __('Block Size', 'cafe-pro'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-icon-cart' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
            ]
        ]);
        $this->add_responsive_control('cart_icon_size', [
            'label' => __('Icon Size', 'cafe-pro'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-icon-cart' => 'font-size:{{SIZE}}{{UNIT}}',
            ]
        ]);
        $this->add_responsive_control('cart_icon_border_radius', [
            'label' => __('Border Radius', 'cafe-pro'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ]
            ],
            'separator'   => 'after',
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-icon-cart' => 'border-radius:{{SIZE}}{{UNIT}}',
            ]
        ]);
        $this->start_controls_tabs('cart_icon_tabs');
        $this->start_controls_tab(
            'cart_icon_tab',
            [
                'label'         => esc_html__('Normal', 'cafe-pro'),
            ]
        );
        $this->add_control(
            'cart_icon_btn_color',
            [
                'label' => esc_html__('Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-wrap-icon-cart' => '--color: {{VALUE}}'
                ]
            ]
        );
        $this->add_control(
            'cart_icon_btn_bg',
            [
                'label' => esc_html__('Background Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-wrap-icon-cart' => 'background-color: {{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'cart_icon_hover_tab',
            [
                'label'         => esc_html__('Hover', 'cafe-pro'),
            ]
        );
        $this->add_control(
            'cart_icon_btn_hv_color',
            [
                'label' => esc_html__('Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-canvas-cart:hover .cafe-wrap-icon-cart' => '--color: {{VALUE}}'
                ]
            ]
        );
        $this->add_control(
            'cart_icon_btn_hv_bg',
            [
                'label' => esc_html__('Background Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-canvas-cart:hover .cafe-wrap-icon-cart' => 'background-color: {{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'cart_icon_btn_border',
                'label'       => __( 'Border', 'cafe-pro' ),
                'placeholder' => '',
                'default'     => '',
                'selector'    => '{{WRAPPER}} .cafe-wrap-icon-cart',
                'separator'   => 'before',
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_cart_count_style',
            [
                'label' => __('Cart Count Style', 'cafe-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'count_typo',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .cafe-cart-count',
            ]
        );
        $this->add_responsive_control('cart_count_size', [
            'label' => __('Block Size', 'cafe-pro'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-icon-cart .cafe-cart-count' => 'min-width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
            ]
        ]);
        $this->add_responsive_control('cart_count_border_radius', [
            'label' => __('Border Radius', 'cafe-pro'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ]
            ],
            'separator'   => 'after',
            'selectors' => [
                '{{WRAPPER}} .cafe-cart-count' => 'border-radius:{{SIZE}}{{UNIT}}',
            ]
        ]);
        $this->start_controls_tabs('cart_count_tabs');
        $this->start_controls_tab(
            'cart_count_tab',
            [
                'label'         => esc_html__('Normal', 'cafe-pro'),
            ]
        );
        $this->add_control(
            'cart_count_color',
            [
                'label' => esc_html__('Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-canvas-cart' => '--count-color: {{VALUE}}'
                ]
            ]
        );
        $this->add_control(
            'cart_count_bg',
            [
                'label' => esc_html__('Background Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-cart-count' => '--count-bg-color: {{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'cart_count_hover_tab',
            [
                'label'         => esc_html__('Hover', 'cafe-pro'),
            ]
        );
        $this->add_control(
            'cart_count_hv_color',
            [
                'label' => esc_html__('Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-canvas-cart:hover' => '--count-color: {{VALUE}}'
                ]
            ]
        );
        $this->add_control(
            'cart_count_hv_bg',
            [
                'label' => esc_html__('Background Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-canvas-cart:hover' => '--count-bg-color: {{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section(
            'section_subtotal_style',
            [
                'label' => __('Subtotal Style', 'cafe-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subtotal_typo',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .cafe-cart-subtotal',
            ]
        );
        $this->add_responsive_control('subtotal_size', [
            'label' => __('Block Size', 'cafe-pro'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-cart-subtotal' => 'min-width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
            ]
        ]);
        $this->add_responsive_control('subtotal_border_radius', [
            'label' => __('Border Radius', 'cafe-pro'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ]
            ],
            'separator'   => 'after',
            'selectors' => [
                '{{WRAPPER}} .cafe-cart-subtotal' => 'border-radius:{{SIZE}}{{UNIT}}',
            ]
        ]);
        $this->start_controls_tabs('subtotal_tabs');
        $this->start_controls_tab(
            'subtotal_tab',
            [
                'label'         => esc_html__('Normal', 'cafe-pro'),
            ]
        );
        $this->add_control(
            'subtotal_color',
            [
                'label' => esc_html__('Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-cart-subtotal .amount' => 'color: {{VALUE}}'
                ]
            ]
        );
        $this->add_control(
            'subtotal_bg',
            [
                'label' => esc_html__('Background Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-wrap-right-cart' => 'background-color: {{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'subtotal_hover_tab',
            [
                'label'         => esc_html__('Hover', 'cafe-pro'),
            ]
        );
        $this->add_control(
            'subtotal_hv_color',
            [
                'label' => esc_html__('Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-canvas-cart:hover .cafe-cart-subtotal .amount' => 'color: {{VALUE}}'
                ]
            ]
        );
        $this->add_control(
            'subtotal_hv_bg',
            [
                'label' => esc_html__('Background Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-canvas-cart:hover .cafe-wrap-right-cart' => '--count-bg-color: {{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
            'subtotal_padding',
            [
                'label' => esc_html__('Padding', 'cafe-pro'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .cafe-wrap-right-cart' => 'padding:  {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        $this->getViewTemplate('template', 'canvas-cart', $settings);
    }
}
