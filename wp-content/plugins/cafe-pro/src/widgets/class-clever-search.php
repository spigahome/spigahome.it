<?php namespace Cafe\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;

/**
 * CleverSiteLogo
 */
final class CleverSearch extends CleverWidgetBase
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
        return 'clever-search';
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
        return __('Clever Search', 'cafe-pro');
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
        return 'eicon-search';
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
        return ['site', 'search', 'form'];
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
            'section_layout',
            [
                'label' => __('Search Layout', 'cafe-pro'),
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => 'Layout',
                'type' => Controls_Manager::SELECT,
                'description' => __('Layout of search form.', 'cafe-pro'),
                'default' => 'normal',
                'options' => [
                    'normal' => esc_html__('Normal', 'cafe-pro'),
                    'slide-down' => esc_html__('Slide Down', 'cafe-pro'),
                    'modal' => esc_html__('Modal', 'cafe-pro'),
                ],
            ]
        );
        $this->add_control('placeholder', [
            'label' => __('Placeholder', 'cafe-pro'),
            'type' => Controls_Manager::TEXT,
            'default' => esc_html__('Type to search', 'cafe-pro'),
        ]);
        $this->add_control('submit_icon', [
            'label' => __('Search Submit Icon', 'cafe-pro'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'true',
            'default' => 'true',
        ]);
        $this->add_control('submit_label', [
            'label' => __('Search submit label', 'cafe-pro'),
            'type' => Controls_Manager::TEXT,
            'default' => esc_html__('Search', 'cafe-pro'),
            'description' => esc_html__('Leave it blank if want disable.', 'cafe-pro'),
        ]);
        $this->end_controls_section();
        $this->start_controls_section(
            'section_options',
            [
                'label' => __('Search Options', 'cafe-pro'),
            ]
        );
        $this->add_control('search_product_only', [
            'label' => __('Search Product Only', 'cafe-pro'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'true',
            'default' => 'false',
        ]);
        $this->add_control('show_product_categories', [
            'label' => __('Show Product Categories', 'cafe-pro'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'true',
            'default' => 'false',
            'condition'     => [
                'search_product_only' => 'true',
            ],
        ]);
        $this->add_control('enable_ajax_search', [
            'label' => __('Enable Ajax Search', 'cafe-pro'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'true',
            'default' => 'false',
        ]);
        $this->add_control(
            'result_layout',
            [
                'label' => 'Result layout',
                'type' => Controls_Manager::SELECT,
                'description' => __('Layout of ajax search result.', 'cafe-pro'),
                'default' => 'list',
                'options' => [
                    'list' => esc_html__('List', 'cafe-pro'),
                    'grid' => esc_html__('Grid', 'cafe-pro'),
                ],
                'condition'     => [
                    'enable_ajax_search' => 'true',
                ],
            ]
        );
        $this->add_responsive_control('columns',[
            'label'         => esc_html__('Columns for row', 'cafe-lite' ),
            'type'          => Controls_Manager::SLIDER,
            'range' => [
                'col' =>[
                    'min' => 1,
                    'max' => 6,
                ]
            ],
            'devices' => [ 'desktop', 'tablet', 'mobile' ],
            'desktop_default' => [
                'size' => 2,
                'unit' => 'col',
            ],
            'tablet_default' => [
                'size' => 2,
                'unit' => 'col',
            ],
            'mobile_default' => [
                'size' => 1,
                'unit' => 'col',
            ],
            'condition'     => [
                'enable_ajax_search' => 'true',
            ],
        ]);
        $this->add_control('max_result', [
            'label' => __('Maximum result', 'cafe-pro'),
            'type' => Controls_Manager::NUMBER,
            'default' => esc_html__('6', 'cafe-pro'),
            'description' => esc_html__('Maximum result display after search.', 'cafe-pro'),
            'condition'     => [
                'enable_ajax_search' => 'true',
            ],
        ]);
        $this->end_controls_section();
        $this->start_controls_section(
            'section_search_icon_style',
            [
                'label' => __('Search Icon', 'cafe-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'layout' => array('slide-down','modal'),
                ],
            ]
        );
        $this->add_responsive_control(
            'search_icon_align',
            [
                'label' => __('Icon Alignment', 'cafe-pro'),
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
                    '{{WRAPPER}} .cafe-wrap-search' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->start_controls_tabs('search_icon_tabs');
        $this->start_controls_tab(
            'search_icon_tab',
            [
                'label'         => esc_html__('Normal', 'cafe-pro'),
            ]
        );
        $this->add_control(
            'search_icon_btn_color',
            [
                'label' => esc_html__('Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-search-toggle-button' => 'color: {{VALUE}}'
                ]
            ]
        );
        $this->add_control(
            'search_icon_btn_bg',
            [
                'label' => esc_html__('Background Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-search-toggle-button' => 'background-color: {{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'search_icon_hover_tab',
            [
                'label'         => esc_html__('Hover', 'cafe-pro'),
            ]
        );
        $this->add_control(
            'search_icon_btn_hv_color',
            [
                'label' => esc_html__('Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-search-toggle-button:hover' => 'color: {{VALUE}}'
                ]
            ]
        );
        $this->add_control(
            'search_icon_btn_hv_bg',
            [
                'label' => esc_html__('Background Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-search-toggle-button:hover' => 'background-color: {{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_responsive_control('search_icon_btn_size', [
            'label' => __('Size', 'cafe-pro'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ]
            ],
            'separator'   => 'before',
            'selectors' => [
                '{{WRAPPER}} .cafe-search-toggle-button' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
            ]
        ]);
        $this->add_responsive_control('search_icon_btn_font_size', [
            'label' => __('Font Size', 'cafe-pro'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-search-toggle-button' => 'font-size:{{SIZE}}{{UNIT}};',
            ]
        ]);
        $this->add_responsive_control('search_icon_btn_border_radius', [
            'label' => __('Border Radius', 'cafe-pro'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-search-toggle-button' => 'border-radius:{{SIZE}}{{UNIT}};',
            ]
        ]);

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'search_icon_btn_border',
                'label'       => __( 'Border', 'cafe-pro' ),
                'placeholder' => '',
                'default'     => '',
                'selector'    => '{{WRAPPER}} .cafe-search-toggle-button',
                'separator'   => 'before',
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Search Form', 'cafe-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'bg_color',
            [
                'label' => esc_html__('Background Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-wrap-search' => '--bg-color: {{VALUE}}'
                ]
            ]
        );
        $this->add_control(
            'mask_bg_color',
            [
                'label' => esc_html__('Background Overlay Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-search-mask' => 'background: {{VALUE}}'
                ],
                'condition'     => [
                    'layout' => array('slide-down'),
                ],
            ]
        );
        $this->add_control(
            'close_btn_color',
            [
                'label' => esc_html__('Close Button Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-search-close' => 'color: {{VALUE}}'
                ],
                'condition'     => [
                    'layout' => array('slide-down','modal'),
                ],
            ]
        );
        $this->add_responsive_control('close_btn_size', [
            'label' => __('Close button size', 'cafe-pro'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ]
            ],
            'devices' => ['desktop', 'tablet', 'mobile'],
            'selectors' => [
                '{{WRAPPER}} .cafe-search-close' => 'font-size:{{SIZE}}{{UNIT}};',
            ]
        ]);
        $this->add_responsive_control('form_height', [
            'label' => __('Height', 'cafe-pro'),
            'description' => __('Search form height. Apply for all item inside', 'cafe-pro'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ]
            ],
            'devices' => ['desktop', 'tablet', 'mobile'],
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-search' => '--form-height:{{SIZE}}{{UNIT}};',
            ]
        ]);
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'search_form_border',
                'label'       => __( 'Border', 'cafe-pro' ),
                'placeholder' => '',
                'default'     => '',
                'selector'    => '{{WRAPPER}} .cafe-wrap-search-fields',
                'separator'   => 'before',
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_search_field_style',
            [
                'label' => __('Search Field', 'cafe-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'search_field_color',
            [
                'label' => esc_html__('Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-wrap-search-fields .cafe-search-field' => 'color: {{VALUE}}'
                ]
            ]
        );
        $this->add_control(
            'search_field_bg',
            [
                'label' => esc_html__('Background Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-wrap-search-fields .cafe-search-field' => 'background-color: {{VALUE}}'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'search_field_typo',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .cafe-search-field',
            ]
        );
        $this->add_control(
            'search_field_padding',
            [
                'label' => esc_html__('Padding', 'cafe-pro'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .cafe-wrap-search-fields .cafe-search-field' => 'padding:  {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_cat_field_style',
            [
                'label' => __('Product Categories', 'cafe-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'search_cat_color',
            [
                'label' => esc_html__('Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-wrap-search-fields .cafe-wrap-product-cat' => '--form-color: {{VALUE}}'
                ]
            ]
        );
        $this->add_control(
            'search_cat_bg',
            [
                'label' => esc_html__('Background Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-wrap-search-fields .cafe-wrap-product-cat' => 'background-color: {{VALUE}}'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'search_cat_typo',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .cafe-wrap-product-cat',
            ]
        );
        $this->add_control(
            'search_cat_padding',
            [
                'label' => esc_html__('Padding', 'cafe-pro'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .cafe-wrap-search-fields .cafe-product-cat' => 'padding:  {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_search_submit_style',
            [
                'label' => __('Search submit button', 'cafe-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'search_sm_btn_color',
            [
                'label' => esc_html__('Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-wrap-search-fields .cafe-search-submit' => 'color: {{VALUE}}'
                ]
            ]
        );
        $this->add_control(
            'search_sm_btn_bg',
            [
                'label' => esc_html__('Background Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-wrap-search-fields .cafe-search-submit' => 'background-color: {{VALUE}}'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'search_sm_btn_typo',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .cafe-search-submit',
            ]
        );
        $this->add_control(
            'search_sm_btn_padding',
            [
                'label' => esc_html__('Padding', 'cafe-pro'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .cafe-wrap-search-fields .cafe-search-submit' => 'padding:  {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        $this->getViewTemplate('template', 'search', $settings);
    }
}
