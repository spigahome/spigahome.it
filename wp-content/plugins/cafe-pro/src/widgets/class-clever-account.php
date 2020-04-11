<?php namespace Cafe\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;

/**
 * CleverSiteLogo
 */
if (!class_exists('WooCommerce')) {
    return;
}

final class CleverAccount extends CleverWidgetBase
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
        return 'clever-account';
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
        return __('Clever Account', 'cafe-pro');
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
        return 'cs-font clever-icon-user-6';
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
        return ['site', 'account', 'woocommerce'];
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
                'label' => __('Account', 'cafe-pro'),
            ]
        );
        $this->add_control('icon', [
            'label' => __('Account Icon', 'cafe-pro'),
            'type' => 'clevericon',
            'default' => 'cs-font clever-icon-user-6',
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
            'description' => __('Accept default follow WooCommerce if leave it blank.', 'cafe-pro'),
            'type' => Controls_Manager::TEXT,
            'default' => '',
            'condition' => [
                'show_label' => 'yes'
            ],
        ]);
        $this->add_control('ext_block_align', [
            'label' => __('Extend block link position', 'cafe-pro'),
            'description' => __('Position of extend link block when user login and Link Only feature enable.', 'cafe-pro'),
            'type' => Controls_Manager::SELECT,
            'default' => 'left',
            'options' => [
                'left' => __('Left', 'cafe-pro'),
                'right' => __('Right', 'cafe-pro'),
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
            'section_acc_icon_style',
            [
                'label' => __('Account Icon Style', 'cafe-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control('acc_icon_wrap_size', [
            'label' => __('Block Size', 'cafe-pro'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-account-btn i' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
            ]
        ]);
        $this->add_responsive_control('acc_icon_size', [
            'label' => __('Icon Size', 'cafe-pro'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-account-btn>i' => 'font-size:{{SIZE}}{{UNIT}}',
            ]
        ]);
        $this->add_responsive_control('acc_icon_border_radius', [
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
                '{{WRAPPER}} .cafe-account-btn>i' => 'border-radius:{{SIZE}}{{UNIT}}',
            ]
        ]);
        $this->start_controls_tabs('acc_icon_tabs');
        $this->start_controls_tab(
            'acc_icon_tab',
            [
                'label' => esc_html__('Normal', 'cafe-pro'),
            ]
        );
        $this->add_control(
            'acc_icon_btn_color',
            [
                'label' => esc_html__('Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-account-btn i' => 'color: {{VALUE}}'
                ]
            ]
        );
        $this->add_control(
            'acc_icon_btn_bg',
            [
                'label' => esc_html__('Background Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-account-btn i' => 'background-color: {{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'acc_icon_hover_tab',
            [
                'label' => esc_html__('Hover', 'cafe-pro'),
            ]
        );
        $this->add_control(
            'acc_icon_btn_hv_color',
            [
                'label' => esc_html__('Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-wrap-account:hover .cafe-account-btn i' => 'color: {{VALUE}}'
                ]
            ]
        );
        $this->add_control(
            'acc_icon_btn_hv_bg',
            [
                'label' => esc_html__('Background Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-wrap-account:hover .cafe-account-btn i' => 'background-color: {{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'acc_icon_btn_border',
                'label' => __('Border', 'cafe-pro'),
                'placeholder' => '',
                'default' => '',
                'selector' => '{{WRAPPER}} .cafe-account-btn>i',
                'separator' => 'before',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_acc_label_style',
            [
                'label' => __('Account Label Style', 'cafe-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control('acc_label_wrap_size', [
            'label' => __('Space', 'cafe-pro'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-account-btn i' => 'margin-right:{{SIZE}}{{UNIT}};',
            ]
        ]);
        $this->add_control(
            'acc_label_color',
            [
                'label' => esc_html__('Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-account-btn' => '--color: {{VALUE}}'
                ]
            ]
        );
        $this->add_control(
            'acc_label_color_hv',
            [
                'label' => esc_html__('Color Hover', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-wrap-account:hover .cafe-account-btn' => '--color: {{VALUE}}'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'acc_label_typo',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .cafe-account-btn',
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_extend_link_style',
            [
                'label' => __('Extended Link Style', 'cafe-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'acc_extend_link_typo',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation li',
            ]
        );
        $this->add_control(
            'acc_extend_link_color',
            [
                'label' => esc_html__('Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-MyAccount-navigation li a' => '--color: {{VALUE}}'
                ]
            ]
        );
        $this->add_control(
            'acc_extend_link_hv',
            [
                'label' => esc_html__('Color Hover', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-MyAccount-navigation li a:hover' => '--color: {{VALUE}}'
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
        $this->getViewTemplate('template', 'account', $settings);
    }
}
