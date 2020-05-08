<?php namespace Cafe\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

/**
 * CleverSiteNavMenu
 */
final class CleverSiteNavMenu extends CleverWidgetBase
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
        return 'clever-site-nav-menu';
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
        return __('Clever Site Nav Menu', 'cafe-pro');
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
        return 'cs-font clever-icon-layer';
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
        return ['site', 'nav','menu'];
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
            'section_menu',
            [
                'label' => __('Menu', 'cafe-pro'),
            ]
        );

        $this->add_control(
            'site_menu',
            [
                'label' => 'Menu',
                'type' => Controls_Manager::SELECT,
                'options' => $this->get_menus(),
                'description' => __('Select menu you want display.', 'cafe-pro'),
            ]
        );
        $this->add_control(
            'layout',
            [
                'label' => __('Layout', 'cafe-pro'),
                'type' => Controls_Manager::SELECT,
                'default'=>'horizontal',
                'options' => [
                    'horizontal' => esc_html__('Horizontal','cafe-pro'),
                    'vertical' => esc_html__('Vertical','cafe-pro'),
                    'hamburger' => esc_html__('Hamburger','cafe-pro'),
                    'slide-down' => esc_html__('Slide Down','cafe-pro'),
                ],
            ]
        );
        $this->add_control(
            'toggle_text',
            [
                'label' => __('Toggle Text', 'cafe-pro'),
                'type' => Controls_Manager::TEXT,
                'default'=>'CATEGORIES',
                'condition'     => [
                    'layout' => array('slide-down'),
                ],
            ]
        );
        $this->add_control('toggle_icon', [
            'label' => esc_html__('Toggle Icon', 'cafe-lite'),
            'default' => 'cs-font clever-icon-menu-2',
            'type' => 'clevericon',
            'condition'     => [
                'layout' => array('slide-down'),
            ],
        ]);
        $this->add_control('toggle_arrow', [
            'label' => esc_html__('Toggle Arrow', 'cafe-lite'),
            'type'  => Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Show', 'cafe-lite' ),
            'label_off' => esc_html__('Hide', 'cafe-lite' ),
            'return_value' => 'yes',
            'default'  => 'yes',
            'condition'     => [
                'layout' => array('slide-down'),
            ],
        ]);
        $this->add_control('show_menu', [
            'label' => esc_html__('Show Default', 'cafe-lite'),
            'type'  => Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Show', 'cafe-lite' ),
            'label_off' => esc_html__('Hide', 'cafe-lite' ),
            'return_value' => 'yes',
            'default'  => 'yes',
            'condition'     => [
                'layout' => array('slide-down'),
            ],
        ]);
        $this->add_control(
            'first_menu_level',
            [
                'label' => __('First Menu Level', 'cafe-pro'),
                'type' => Controls_Manager::HEADING,
                'separator'   => 'before',
            ]
        );
        $this->add_control(
            'hover_style',
            [
                'label' => __('Style', 'cafe-pro'),
                'type' => Controls_Manager::SELECT,
                'default'=>'normal',
                'options' => [
                    'normal' => esc_html__('Normal','cafe-pro'),
                    'slide' => esc_html__('Slide','cafe-pro'),
                    'underline' => esc_html__('Underline','cafe-pro'),
                    'underline-2' => esc_html__('Underline 2','cafe-pro'),
                    'line-through' => esc_html__('Line Through','cafe-pro'),
                    'line-through-2' => esc_html__('Line Through 2','cafe-pro'),
                    'double-lines' => esc_html__('Double Lines','cafe-pro'),
                ],
                'condition'     => [
                    'layout' => array('vertical','horizontal'),
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
                    '{{WRAPPER}} .cafe-menu' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'sub_menu_heading',
            [
                'label' => __('Submenu', 'cafe-pro'),
                'type' => Controls_Manager::HEADING,
                'separator'   => 'before',
            ]
        );
        $this->add_control(
            'sub_menu_arrow',
            [
                'label' => __('Submenu arrow', 'cafe-pro'),
                'type' => Controls_Manager::SELECT,
                'default'=>'none',
                'options' => [
                    'none' => esc_html__('None','cafe-pro'),
                    'arrow' => esc_html__('Arrow','cafe-pro'),
                    'caret' => esc_html__('Caret','cafe-pro'),
                    'plus' => esc_html__('Plus','cafe-pro'),
                ],
            ]
        );
        $this->add_responsive_control(
            'sub_align',
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
                    '{{WRAPPER}} .menu-item > ul' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'heading_mobile_menu',
            [
                'label' => __('Mobile/Hamburger Menu', 'cafe-pro'),
                'type' => Controls_Manager::HEADING,
                'separator'   => 'before',
            ]
        );
        $this->add_control(
            'mobile_break_point',
            [
                'label' => __('Mobile Break Point', 'cafe-pro'),
                'type' => Controls_Manager::SELECT,
                'default'=>'cafe-lg-width',
                'options' => [
                    'cafe-none-breakpoint' => esc_html__('None','cafe-pro'),
                    'cafe-lg-width' => esc_html__('Large Width(<1025px)','cafe-pro'),
                    'cafe-md-width' => esc_html__('Medium Width(<768px)','cafe-pro'),
                ],
                'condition'     => [
                    'layout' => array('vertical','horizontal'),
                ],
            ]
        );
        $this->add_control(
            'hamburger_style',
            [
                'label' => __('Style', 'cafe-pro'),
                'type' => Controls_Manager::SELECT,
                'default'=>'off-canvas',
                'options' => [
                    'off-canvas' => esc_html__('Off Canvas','cafe-pro'),
                    'off-canvas-2' => esc_html__('Off Canvas 2','cafe-pro'),
                    'fixed-tr-fs' => esc_html__('Fixed Top Right Modal','cafe-pro'),
                    'full-screen' => esc_html__('Full screen','cafe-pro'),
                    'slide-down' => esc_html__('Slide Down','cafe-pro'),
                ],
            ]
        );
        $this->add_control(
            'canvas_position',
            [
                'label' => __('Canvas Position', 'cafe-pro'),
                'type' => Controls_Manager::SELECT,
                'default'=>'left',
                'options' => [
                    'left' => esc_html__('Left','cafe-pro'),
                    'right' => esc_html__('Right','cafe-pro'),
                ],
                'condition'     => [
                    'hamburger_style' => array('off-canvas','off-canvas-2'),
                ],
            ]
        );
        $this->add_responsive_control(
            'menu_icon_align',
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
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section('section_style_toggle',[
            'label' => __('Toggle', 'cafe-pro'),
            'tab'   => Controls_Manager::TAB_STYLE,
            'condition'     => [
                    'layout' => array('slide-down'),
                ],
            ]);
            $this->add_control('toggle_color',[
                'label'         => esc_html__('Color', 'cafe-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .cafe-site-menu .toggle '  => 'color : {{VALUE}}'
                ]
            ]);
            $this->add_control('toggle_background',[
                'label'         => esc_html__('Background', 'cafe-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .cafe-site-menu .toggle'  => 'background: {{VALUE}}'
                ]
            ]);
            $this->add_responsive_control('toggle_padding',[
                'label'         => esc_html__('Padding', 'cafe-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'separator'   => 'before',
                'selectors'     => [
                    '{{WRAPPER}} .cafe-site-menu .toggle'  => 'padding:  {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]);
            $this->add_responsive_control('toggle_icon_size',[
                'label'         => __( 'Icon Size', 'cafe-pro' ),
                'type'          => Controls_Manager::SLIDER,
                'range' => [
                    'px' =>[
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'selectors'     => [
                    '{{WRAPPER}} .cafe-site-menu .toggle .icon'  => 'font-size : {{SIZE}}{{UNIT}};',
                ]
            ]);
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'          => 'toggle_typo_text',
                    'label'         => __('Typography Text', 'cafe-pro'),
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .cafe-site-menu .toggle .text',
                ]
            );
            $this->add_responsive_control('toggle_space',[
                'label'         => __( 'Space', 'cafe-pro' ),
                'type'          => Controls_Manager::SLIDER,
                'range' => [
                    'px' =>[
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'selectors'     => [
                    '{{WRAPPER}} .cafe-site-menu .toggle .text'  => 'margin-left:{{SIZE}}{{UNIT}};',
                ]
            ]);

        $this->end_controls_section();
        $this->start_controls_section(
            'section_style_menu',
            [
                'label' => __('Menu', 'cafe-pro'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'menu_typo',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                'selector'      => '{{WRAPPER}} .cafe-site-menu .menu-item>a',
            ]
        );
        $this->add_responsive_control('menu_item_space',[
            'label'         => __( 'Space', 'cafe-pro' ),
            'description'         => __( 'Space between each menu item', 'cafe-pro' ),
            'type'          => Controls_Manager::SLIDER,
            'range' => [
                'px' =>[
                    'min' => 0,
                    'max' => 100,
                ]
            ],
            'devices' => [ 'desktop', 'tablet', 'mobile' ],
            'selectors'     => [
                '{{WRAPPER}} .cafe-site-menu.horizontal .cafe-menu>.menu-item'  => 'margin:0 calc({{SIZE}}{{UNIT}}/2);',
                '{{WRAPPER}} .cafe-site-menu:not(.horizontal) .cafe-menu>.menu-item'  => 'margin:calc({{SIZE}}{{UNIT}}/2) 0;',
            ]
        ]);
        $this->start_controls_tabs('menu_style_tabs');
        $this->start_controls_tab(
            'menu_style_tab',
            [
                'label'         => esc_html__('Normal', 'cafe-pro'),
            ]
        );

        $this->add_responsive_control(
            'menu_color',
            [
                'label'         => esc_html__('Color', 'cafe-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .cafe-site-menu'  => '--menu-color: {{VALUE}}'
                ]
            ]
        );
        $this->add_responsive_control(
            'menu_bg_color',
            [
                'label'         => esc_html__('Background Color', 'cafe-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .cafe-site-menu'  => '--menu-bg: {{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'menu_style_hover_tab',
            [
                'label'         => esc_html__('Hover', 'cafe-pro'),
            ]
        );
        $this->add_responsive_control(
            'menu_hv_color',
            [
                'label'         => esc_html__('Color', 'cafe-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .cafe-site-menu'  => '--menu-hover-color: {{VALUE}}'
                ]
            ]
        );
        $this->add_responsive_control(
            'menu_bg_hv_color',
            [
                'label'         => esc_html__('Background Color', 'cafe-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .cafe-site-menu '  => '--menu-hover-bg: {{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
            'menu_mask_bg',
            [
                'label'         => esc_html__('Mask Close', 'cafe-pro'),
                'description'         => esc_html__('Background of block masking for close hamburger menu and mobile.', 'cafe-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .cafe-hamburger-mask'  => '--menu-mask-bg: {{VALUE}}'
                ]
            ]
        );
        $this->add_responsive_control(
            'menu_item_padding',
            [
                'label'         => esc_html__('Padding', 'cafe-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'separator'   => 'before',
                'selectors'     => [
                    '{{WRAPPER}} .cafe-site-menu .menu-item>a'  => 'padding:  {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );$this->add_control(
            'menu_item_border_radius',
            [
                'label'         => esc_html__('Border Radius', 'cafe-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'separator'   => 'before',
                'selectors'     => [
                    '{{WRAPPER}} .cafe-site-menu .menu-item>a'  => 'border-radius:  {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'menu_item_border',
                'label'       => __( 'Border', 'cafe-pro' ),
                'placeholder' => '',
                'default'     => '',
                'selector'    => '{{WRAPPER}} .cafe-site-menu .cafe-menu>.menu-item',
                'separator'   => 'before',
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_style_sub_menu',
            [
                'label' => __('SubMenu', 'cafe-pro'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control('section_style_sub_menu_width',[
            'label'         => __( 'Max width', 'cafe-pro' ),
            'type'          => Controls_Manager::SLIDER,
            'range' => [
                'px' =>[
                    'min' => 0,
                    'max' => 1000,
                ]
            ],
            'devices' => [ 'desktop', 'tablet', 'mobile' ],
            'selectors'     => [
                '{{WRAPPER}} .cafe-site-menu.horizontal .menu-item > ul'  => 'max-width:{{SIZE}}{{UNIT}};',
            ],
            'condition'     => [
                'layout' => array('horizontal'),
            ],
        ]);
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'submenu_typo',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                'selector'      => '{{WRAPPER}} .cafe-site-menu .menu-item .menu-item>ul',
            ]
        );
        $this->start_controls_tabs('submenu_style_tabs');
        $this->start_controls_tab(
            'submenu_style_tab',
            [
                'label'         => esc_html__('Normal', 'cafe-pro'),
            ]
        );

        $this->add_responsive_control(
            'submenu_color',
            [
                'label'         => esc_html__('Color', 'cafe-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .cafe-site-menu .menu-item .menu-item'  => '--menu-color: {{VALUE}}'
                ]
            ]
        );
        $this->add_responsive_control(
            'submenu_bg_color',
            [
                'label'         => esc_html__('Background Color', 'cafe-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .cafe-site-menu .menu-item .menu-item'  => '--menu-bg: {{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'submenu_style_hover_tab',
            [
                'label'         => esc_html__('Hover', 'cafe-pro'),
            ]
        );
        $this->add_responsive_control(
            'submenu_hv_color',
            [
                'label'         => esc_html__('Color', 'cafe-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .cafe-site-menu .menu-item .menu-item'  => '--menu-hover-color: {{VALUE}}'
                ]
            ]
        );
        $this->add_responsive_control(
            'submenu_bg_hv_color',
            [
                'label'         => esc_html__('Background Color', 'cafe-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .cafe-site-menu .menu-item .menu-item'  => '--menu-hover-bg: {{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_responsive_control(
            'submenu_item_padding',
            [
                'label'         => esc_html__('Menu Item Padding', 'cafe-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'separator'   => 'before',
                'selectors'     => [
                    '{{WRAPPER}} .cafe-site-menu .menu-item .menu-item>a'  => 'padding:  {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );$this->add_responsive_control(
            'submenu_padding',
            [
                'label'         => esc_html__('Sub Menu Padding', 'cafe-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'separator'   => 'before',
                'selectors'     => [
                    '{{WRAPPER}} .cafe-site-menu .menu-item .dropdown-submenu,{{WRAPPER}} .cafe-site-menu .mega-menu-content, {{WRAPPER}} .cafe-site-menu .menu-item>.sub-menu'  => 'padding:  {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'submenu_item_border',
                'label'       => esc_html__( 'Border', 'cafe-pro' ),
                'placeholder' => '',
                'default'     => '',
                'selector'    => '{{WRAPPER}} .cafe-site-menu .menu-item .menu-item',
                'separator'   => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'          => 'submenu_item_shadow',
                'selector'      => '{{WRAPPER}} .menu-item>ul',
                'condition'     => [
                    'dots_tooltips_switcher'    => 'yes'
                ]
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_style_menu_button',
            [
                'label' => __('Menu Button', 'cafe-pro'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs('menu_btn_style_tabs');
        $this->start_controls_tab(
            'menu_btn_style_tab',
            [
                'label'         => esc_html__('Normal', 'cafe-pro'),
            ]
        );

        $this->add_control(
            'menu_btn_color',
            [
                'label'         => esc_html__('Color', 'cafe-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .cafe-hamburger-button'  => '--menu-icon-color: {{VALUE}}'
                ]
            ]
        );
        $this->add_control(
            'menu_btn_bg_color',
            [
                'label'         => esc_html__('Background Color', 'cafe-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .cafe-hamburger-button'  => 'background: {{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'menu_btn_style_hover_tab',
            [
                'label'         => esc_html__('Hover', 'cafe-pro'),
            ]
        );
        $this->add_control(
            'menu_btn_hv_color',
            [
                'label'         => esc_html__('Color', 'cafe-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .cafe-site-menu .cafe-hamburger-button:hover'  => '--menu-icon-color: {{VALUE}}'
                ]
            ]
        );
        $this->add_control(
            'menu_btn_bg_hv_color',
            [
                'label'         => esc_html__('Background Color', 'cafe-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .cafe-hamburger-button:hover'  => 'background: {{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control('menu_btn_size',[
            'label'         => __( 'Size', 'cafe-pro' ),
            'type'          => Controls_Manager::SLIDER,
            'separator'   => 'before',
            'range' => [
                'px' =>[
                    'min' => 0,
                    'max' => 100,
                ]
            ],
            'selectors'     => [
                '{{WRAPPER}} .cafe-hamburger-button'  => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
            ]
        ]);
        $this->add_responsive_control('menu_btn_padding',[
            'label'         => __( 'Padding', 'cafe-pro' ),
            'type'          => Controls_Manager::SLIDER,
            'range' => [
                'px' =>[
                    'min' => 0,
                    'max' => 100,
                ]
            ],
            'selectors'     => [
                '{{WRAPPER}} .cafe-hamburger-button'  => 'padding:{{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .cafe-hamburger-button .cafe-hamburger-icon'  => 'width:calc(100% - 2*{{SIZE}}{{UNIT}});',
            ]
        ]);
        $this->add_control('menu_btn_border-radius',[
            'label'         => __( 'Border Radius', 'cafe-pro' ),
            'type'          => Controls_Manager::SLIDER,
            'selectors'     => [
                '{{WRAPPER}} .cafe-hamburger-button'  => 'border-radius:{{SIZE}}{{UNIT}};',
            ]
        ]);
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'menu_btn_item_border',
                'label'       => __( 'Border', 'cafe-pro' ),
                'placeholder' => '',
                'default'     => '',
                'selector'    => '{{WRAPPER}} .cafe-hamburger-button',
                'separator'   => 'before',
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
       $this->getViewTemplate('template', 'site-nav-menu', $settings);
    }

    /**
     * @return array $list A list of menu names indexed by slug.
     */
    protected function get_menus()
    {
        $list = [];

        $menus = get_terms([
            'taxonomy'   => 'nav_menu',
            'hide_empty' => false,
            'orderby'    => 'name'
        ]);

        if (!is_wp_error($menus)) {
            foreach ($menus as $menu) {
                $list[$menu->slug] = $menu->name;
            }
        }

        return $list;
    }
}
