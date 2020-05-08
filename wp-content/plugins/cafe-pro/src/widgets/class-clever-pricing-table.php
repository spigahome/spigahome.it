<?php namespace Cafe\Widgets;

use Elementor\Controls_Manager;
use Cafe\Clever_Custom_Control;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;

/**
 * Clever Pricing Table
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
final class CleverPricingTable extends CleverWidgetBase {
	/**
	 * @return string
	 */
	function get_name() {
		return 'clever-pricing-table';
	}

	/**
	 * @return string
	 */
	function get_title() {
		return __( 'Clever Pricing Table', 'cafe-pro' );
	}

	/**
	 * @return string
	 */
	function get_icon() {
		return 'cs-font clever-icon-online-shopping';
	}

	/**
	 * Register controls
	 */
	protected function _register_controls() {
		$repeater = new \Elementor\Repeater();
		$repeater->start_controls_tabs( 'feature_repeater' );

		$repeater->start_controls_tab( 'content', [ 'label' => __( 'Content', 'cafe-pro' ) ] );
		$repeater->add_control( 'title', [
			'label'       => __( 'Title', 'cafe-pro' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => __( 'This is features', 'cafe-pro' ),
			'label_block' => true,
		] );
		$repeater->add_control( 'icon', [
			'label'   => __( 'Icon', 'cafe-pro' ),
			'type'    => 'clevericon',
			'default' => 'cs-font clever-icon-check',
		] );
		$repeater->end_controls_tab();
		$repeater->start_controls_tab( 'style', [ 'label' => __( 'Style', 'cafe-pro' ) ] );
		$repeater->add_control( 'color', [
			'label'     => __( 'Color', 'cafe-pro' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}}',
			],
		] );
		$repeater->add_control( 'background_color', [
			'label'     => __( 'Background Color', 'cafe-pro' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
			],
		] );
		$repeater->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			]
		);
		$repeater->end_controls_tab();

		$this->start_controls_section( 'heading_section', [
			'label' => __( 'Heading', 'cafe-pro' )
		] );
		$this->add_control( 'title', [
			'label'       => __( 'Title', 'cafe-pro' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => __( 'Pricing Table', 'cafe-pro' ),
			'description' => __( 'This text is heading content', 'cafe-pro' ),
		] );
		$this->add_control( 'title_tag',
			[
				'label'       => __( 'HTML Tag', 'cafe-pro' ),
				'description' => __( 'Select a heading tag for the title. Headings are defined with H1 to H6 tags.', 'cafe-pro' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'h3',
				'options'     => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'p' => 'p',
					'div' => 'div',
					'span' => 'span',
				],
				'label_block' => true,
			] );
		$this->add_control( 'des', [
			'label'       => __( 'Description', 'cafe-pro' ),
			'default'     => '',
			'type'        => Controls_Manager::TEXTAREA,
			'description' => __( 'This text is heading content', 'cafe-pro' ),
		] );
		$this->end_controls_section();
		$this->start_controls_section( 'price_section', [
			'label' => __( 'Price', 'cafe-pro' )
		] );
		$this->add_control( 'currency', [
			'label'   => __( 'Currency', 'cafe-pro' ),
			'default' => '$',
			'type'    => Controls_Manager::TEXT,
		] );
		$this->add_control( 'price', [
			'label'       => __( 'Price', 'cafe-pro' ),
			'default'     => '49',
			'type'        => Controls_Manager::TEXT,
			'description' => __( 'Price for display', 'cafe-pro' ),
		] );
		$this->add_control( 'original_price', [
			'label'       => __( 'Original Price', 'cafe-pro' ),
			'type'        => Controls_Manager::TEXT,
			'description' => __( 'Original Price before sale for display', 'cafe-pro' ),
		] );
		$this->add_control( 'duration', [
			'label'       => __( 'Period', 'cafe-pro' ),
			'default'     => __( '/Month', 'cafe-pro' ),
			'type'        => Controls_Manager::TEXT,
			'description' => __( 'Original Price before sale for display', 'cafe-pro' ),
		] );
		$this->add_control( 'duration_des', [
			'label'   => __( 'Description', 'cafe-pro' ),
			'default' => __( '', 'cafe-pro' ),
			'type'    => Controls_Manager::TEXT,
		] );
		$this->end_controls_section();
		$this->start_controls_section( 'features_section', [
			'label' => __( 'Features', 'cafe-pro' )
		] );
		$this->add_control( 'features_list', [
			'label'       => __( 'Features list', 'cafe-pro' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'title_field' => '{{{ title }}}',
			'default'     => [
				[
					'title' => __( 'This is features 1', 'cafe-pro' ),
				],
				[
					'title' => __( 'This is features 2', 'cafe-pro' ),
				],
				[
					'title' => __( 'This is features 3', 'cafe-pro' ),
				],
			]
		] );
		$this->end_controls_section();
		$this->end_controls_section();
		$this->start_controls_section( 'button_section', [
			'label' => __( 'Button', 'cafe-pro' )
		] );
		$this->add_control( 'button_text', [
			'label'       => __( 'Text', 'cafe-pro' ),
			'type'        => Controls_Manager::TEXT,
			'description' => __( 'Text display inside button', 'cafe-pro' ),
			'default'     => __( 'Get Started', 'cafe-pro' ),
		] );
		$this->add_control( 'button_icon', [
			'label' => __( 'Icon', 'cafe-pro' ),
			'type'  => 'clevericon'
		] );
		$this->add_control( 'button_icon_pos', [
			'label'   => __( 'Icon position', 'cafe-pro' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'after',
			'options' => [
				'before' => __( 'Before', 'cafe-pro' ),
				'after'  => __( 'After', 'cafe-pro' ),
			]
		] );
		$this->add_control( 'link_type', [
			'label'   => __( 'Link Type', 'cafe-pro' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'url',
			'options' => [
				'url'  => __( 'URL', 'cafe-pro' ),
				'page' => __( 'Page', 'cafe-pro' ),
			]
		] );
		$this->add_control( 'link', [
			'label'       => __( 'Link', 'cafe-pro' ),
			'type'        => Controls_Manager::URL,
			'description' => __( 'Redirect link when click to banner.', 'cafe-pro' ),
			'separator'   => 'after',
			'condition'   => [
				'link_type' => 'url',
			]
		] );
		$this->add_control( 'link_page',
			[
				'label'     => __( 'Page', 'cafe-pro' ),
				'type'      => Controls_Manager::SELECT2,
				'options'   => $this->getPages(),
				'condition' => [
					'link_type' => 'page'
				],
				'multiple'  => false,
				'separator' => 'after'
			]
		);
		$this->add_control(
			'link_page_new_window',
			[
				'label'        => __( 'Open new window', 'cafe-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'link_type' => 'page'
				],
			]
		);
		$this->add_control( 'button_style', [
			'label'   => __( 'Button style', 'cafe-pro' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'normal',
			'options' => [
				'normal'    => __( 'Normal', 'cafe-pro' ),
				'underline' => __( 'Underline', 'cafe-pro' ),
				'outline'   => __( 'Outline', 'cafe-pro' ),
				'flat'      => __( 'Flat', 'cafe-pro' ),
				'slide'     => __( 'Slide', 'cafe-pro' ),
			]
		] );
		$this->end_controls_section();
		$this->start_controls_section( 'badge_section', [
			'label' => __( 'Badge', 'cafe-pro' )
		] );
		$this->add_control( 'badge_text', [
			'label'       => __( 'Text', 'cafe-pro' ),
			'type'        => Controls_Manager::TEXT,
			'description' => __( 'Text display inside badge', 'cafe-pro' ),
			'default'     => __( 'New!', 'cafe-pro' ),
		] );
		$this->add_control( 'badge_icon', [
			'label' => __( 'Icon', 'cafe-pro' ),
			'type'  => 'clevericon'
		] );
		$this->add_control( 'badge_type', [
			'label'   => __( 'Badge type', 'cafe-pro' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'banner',
			'options' => [
				'banner' => __( 'Banner', 'cafe-pro' ),
				'circle' => __( 'Circle', 'cafe-pro' ),

			]
		] );
		$this->end_controls_section();

        /*Heading style*/
        $this->start_controls_section( 'general_style_settings', [
            'label' => __( 'General', 'cafe-pro' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'general_style', [
            'label'   => __( 'Style', 'cafe-pro' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'default',
            'options' => [
                'default' => __( 'Default', 'cafe-pro' ),
                'style-1' => __( 'Style-1', 'cafe-pro' ),

            ]
        ] );
		$this->end_controls_section();

		/*Heading style*/
		$this->start_controls_section( 'heading_style_settings', [
			'label' => __( 'Heading Block', 'cafe-pro' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'heading_block_bg',
				'label'    => __( 'Background Block', 'cafe-pro' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .cafe-wrap-block-heading',
                'condition' => [
                    'general_style' => 'default'
                ],
			]
		);
        $this->add_control( 'heading_block_bg_normal', [
            'label'     => __( 'Background', 'cafe-pro' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-pricing-table.style-1 .cafe-wrap-block-heading' => '--background-color: {{VALUE}};background-color: {{VALUE}};'
            ]
        ] );
		$this->add_responsive_control(
			'heading_block_align',
			[
				'label'     => __( 'Content Align', 'cafe-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'cafe-pro' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'cafe-pro' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'cafe-pro' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .cafe-wrap-block-heading' => 'text-align: {{VALUE}};'
				]
			]
		);
		$this->add_responsive_control( 'heading_block_padding', [
			'label'      => __( 'Padding', 'cafe-pro' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .cafe-wrap-block-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'heading_border',
				'label'       => __( 'Border', 'cafe-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .cafe-wrap-block-heading',
				'separator'   => 'after',
			]
		);
		$this->add_control( 'title_color', [
			'label'     => __( 'Title Color', 'cafe-pro' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .cafe-heading' => 'color: {{VALUE}};'
			]
		] );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .cafe-heading',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			]
		);
		$this->add_responsive_control( 'title_padding', [
			'label'      => __( 'Padding', 'cafe-pro' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'separator'  => 'after',
			'selectors'  => [
				'{{WRAPPER}} .cafe-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );
		$this->add_control( 'des_color', [
			'label'     => __( 'Description Color', 'cafe-pro' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .cafe-des' => 'color: {{VALUE}};'
			]
		] );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'des_typography',
				'selector' => '{{WRAPPER}} .cafe-des',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			]
		);
		$this->add_responsive_control( 'des_padding', [
			'label'      => __( 'Description Padding', 'cafe-pro' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'separator'  => 'after',
			'selectors'  => [
				'{{WRAPPER}} .cafe-des' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );
		$this->end_controls_section();
		/*Heading style*/
		$this->start_controls_section( 'price_style_settings', [
			'label' => __( 'Price Block', 'cafe-pro' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );
		$this->add_control( 'price_color', [
			'label'     => __( 'Price Color', 'cafe-pro' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .cafe-price' => 'color: {{VALUE}};'
			]
		] );
		$this->add_control( 'price_block_color', [
			'label'     => __( 'Background Block Color', 'cafe-pro' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .cafe-wrap-price' => 'background-color: {{VALUE}};'
			]
		] );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} .cafe-price',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			]
		);
		$this->add_control( 'o_price_heading', [
			'label' => __( 'Original Price', 'cafe-pro' ),
			'type'  => Controls_Manager::HEADING,
		] );
		$this->add_control( 'o_price_color', [
			'label'     => __( 'Price Color', 'cafe-pro' ),
			'separator' => 'before',
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .cafe-original-price' => 'color: {{VALUE}};'
			]
		] );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'o_price_typography',
				'selector' => '{{WRAPPER}} .cafe-original-price',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			]
		);
		$this->add_control( 'duration_color', [
			'label'     => __( 'Period Color', 'cafe-pro' ),
			'separator' => 'before',
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .cafe-duration' => 'color: {{VALUE}};'
			]
		] );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'duration_typography',
				'selector' => '{{WRAPPER}} .cafe-duration',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			]
		);
		$this->add_control( 'duration_display_type', [
			'label'     => __( 'Display type', 'cafe-pro' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'inline',
			'options'   => [
				'inline' => __( 'Inline', 'cafe-pro' ),
				'block'  => __( 'Block', 'cafe-pro' ),
			],
			'selectors' => [
				'{{WRAPPER}} .cafe-duration' => 'display: {{VALUE}};'
			]
		] );
		$this->add_control( 'duration_des_color', [
			'label'     => __( 'Period Description Color', 'cafe-pro' ),
			'separator' => 'before',
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .cafe-duration-des' => 'color: {{VALUE}};'
			]
		] );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'duration_des_typography',
				'selector' => '{{WRAPPER}} .cafe-duration-des',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			]
		);
		$this->add_responsive_control( 'price_block_padding', [
			'label'      => __( 'Price Block Padding', 'cafe-pro' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'separator'  => 'before',
			'selectors'  => [
				'{{WRAPPER}} .cafe-wrap-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );
		$this->end_controls_section();
		$this->start_controls_section( 'feature_style_settings', [
			'label' => __( 'Features Block', 'cafe-pro' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );
		$this->add_control( 'feature_color_settings', [
			'label'     => __( 'Color', 'cafe-pro' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .cafe-wrap-list-features' => 'color: {{VALUE}};'
			]
		] );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'feature_typo',
				'selector' => '{{WRAPPER}} .cafe-wrap-list-features',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			]
		);
		$this->add_control( 'feature_icon_color_settings', [
			'label'     => __( 'Icon Color', 'cafe-pro' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .cafe-wrap-list-features i' => 'color: {{VALUE}};'
			]
		] );
		$this->add_control( 'feature_icon_size_settings', [
			'label'      => __( 'Icon size', 'cafe-pro' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .cafe-wrap-list-features i' => 'font-size: {{SIZE}}{{UNIT}};',
			],
		] );
		$this->add_control( 'feature_icon_space_settings', [
			'label'      => __( 'Icon space', 'cafe-pro' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .cafe-wrap-list-features i' => 'padding-right: {{SIZE}}{{UNIT}};',
			],
		] );
		$this->add_control( 'feature_space_settings', [
			'label'      => __( 'Space', 'cafe-pro' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .cafe-wrap-list-features .cafe-feature' => 'padding: {{SIZE}}{{UNIT}} 0 {{SIZE}}{{UNIT}};',
			],
		] );
		$this->add_responsive_control(
			'features_block_align',
			[
				'label'     => __( 'Content Align', 'cafe-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'cafe-pro' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'cafe-pro' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'cafe-pro' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .cafe-wrap-list-features' => 'text-align: {{VALUE}};'
				]
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'features_block_bg',
				'label'    => __( 'Background Block', 'cafe-pro' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .cafe-wrap-list-features',
			]
		);
		$this->add_responsive_control( 'features_block_padding', [
			'label'      => __( 'Padding', 'cafe-pro' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .cafe-wrap-list-features' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'features_border',
				'label'       => __( 'Border', 'cafe-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .cafe-wrap-list-features',

			]
		);
		$this->add_control( 'feature_item_border_heading', [
			'label'     => __( 'Item Border', 'cafe-pro' ),
			'separator' => 'before',
			'type'      => Controls_Manager::HEADING
		] );
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'feature_item_border',
				'label'       => __( 'Item Border', 'cafe-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .cafe-feature',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section( 'button_block_settings', [
			'label' => __( 'Button Block', 'cafe-pro' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'button_block_bg',
				'label'    => __( 'Background Block', 'cafe-pro' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .cafe-wrap-button',
			]
		);
		$this->add_responsive_control(
			'button_block_align',
			[
				'label'     => __( 'Content Align', 'cafe-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'cafe-pro' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'cafe-pro' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'cafe-pro' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .cafe-wrap-button' => 'text-align: {{VALUE}};'
				]
			]
		);
		$this->add_responsive_control( 'button_block_padding', [
			'label'      => __( 'Padding', 'cafe-pro' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .cafe-wrap-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'button_block_border',
				'label'       => __( 'Border', 'cafe-pro' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .cafe-wrap-button',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section( 'button_style_settings', [
			'label' => __( 'Button', 'cafe-pro' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );
		$this->add_control( 'button_width', [
			'label'      => __( 'Button Width', 'cafe-pro' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ '%', 'px' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 1000,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .cafe-button ' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .cafe-button',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			]
		);
		$this->add_control( 'button_color', [
			'label'     => __( 'Color', 'cafe-pro' ),
			'separator' => 'before',
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .cafe-button' => 'color: {{VALUE}};'
			]
		] );
		$this->add_control( 'button_color_hover', [
			'label'     => __( 'Color Hover', 'cafe-pro' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .cafe-button:hover' => 'color: {{VALUE}};'
			]
		] );
		$this->add_control( 'button_bg', [
			'label'     => __( 'Background Color', 'cafe-pro' ),
			'type'      => Controls_Manager::COLOR,
			'separator' => 'before',
			'selectors' => [
				'{{WRAPPER}} .cafe-button:after,{{WRAPPER}} .cafe-button:before' => 'background-color: {{VALUE}};'
			]
		] );
		$this->add_control( 'button_bg_hover', [
			'label'     => __( 'Background Color Hover', 'cafe-pro' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .cafe-button:hover:after,{{WRAPPER}} .cafe-button:hover:before' => 'background-color: {{VALUE}};'
			]
		] );
		$this->add_control( 'button_border_color', [
			'label'     => __( 'Border Color', 'cafe-pro' ),
			'type'      => Controls_Manager::COLOR,
			'separator' => 'before',
			'selectors' => [
				'{{WRAPPER}} .cafe-button:before' => 'border-color: {{VALUE}};'
			]
		] );
		$this->add_control( 'button_border_color_hover', [
			'label'     => __( 'Border Color Hover', 'cafe-pro' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .cafe-button:hover:before' => 'border-color: {{VALUE}};'
			]
		] );
		$this->add_responsive_control( 'button_style_border_radius', [
			'label'      => __( 'Border radius', 'cafe-pro' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'separator'  => 'before',
			'selectors'  => [
				'{{WRAPPER}} .cafe-button, {{WRAPPER}} .cafe-button:before, {{WRAPPER}} .cafe-button:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );
		$this->add_responsive_control( 'button_style_padding', [
			'label'      => __( 'Padding', 'cafe-pro' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'separator'  => 'before',
			'selectors'  => [
				'{{WRAPPER}} .cafe-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );
		$this->end_controls_section();
		$this->start_controls_section( 'badge_style_settings', [
			'label' => __( 'Badge', 'cafe-pro' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );
		$this->add_control( 'badge_size', [
			'label'     => __( 'Badge size', 'cafe-pro' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 1000,
				]
			],
			'selectors' => [
				'{{WRAPPER}} .cafe-badge.banner .wrap-badge' => 'border-width: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .cafe-badge.circle'             => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
			],
		] );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'badge_typography',
				'selector' => '{{WRAPPER}} .cafe-badge .badge-text',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			]
		);
		$this->add_control( 'badge_color', [
			'label'     => __( 'Color', 'cafe-pro' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .cafe-badge .badge-text' => 'color: {{VALUE}};'
			]
		] );
		$this->add_control( 'badge_bg_color', [
			'label'     => __( 'Background Color', 'cafe-pro' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .cafe-badge.circle'             => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .cafe-badge.banner .wrap-badge' => 'border-right-color: {{VALUE}};',
			]
		] );
		$this->add_responsive_control( 'badge_style_padding', [
			'label'      => __( 'Padding', 'cafe-pro' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'separator'  => 'before',
			'selectors'  => [
				'{{WRAPPER}} .cafe-badge .badge-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );
		$this->end_controls_section();
	}

	/**
	 * Load style
	 */
	public function get_style_depends() {
		return [ 'cafe-style' ];
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
	public function get_script_depends() {
		return [ 'typed', 'cafe-script' ];
	}

	/**
	 * Render
	 */
	protected function render() {
		$settings = array_merge( [ // default settings
			'title'                => esc_html__( 'Get Started', 'cafe-pro' ),
			'title_tag'            => 'h3',
			'des'                  => esc_html__( 'This is Description', 'cafe-pro' ),
			'currency'             => '$',
			'price'                => '49',
			'original_price'       => '',
			'duration'             => esc_html__( '/Month', 'cafe-pro' ),
			'duration_des'         => '',
			'features_list'        => '',
			'button_text'          => esc_html__( 'Button', 'cafe-pro' ),
			'button_icon'          => '',
			'button_icon_pos'      => 'after',
			'link_type'            => '',
			'link'                 => '',
			'link_page'            => '',
			'link_page_new_window' => '',
			'button_style'         => 'normal',
			'badge_text'           => esc_html__( 'New', 'cafe-pro' ),
			'badge_icon'           => '',
			'badge_type'           => 'banner',
			'general_style'        => 'default',
		], $this->get_settings_for_display() );

		$this->add_inline_editing_attributes( 'title' );
		$this->add_render_attribute( 'title', 'class', 'cafe-heading' );
		$this->add_inline_editing_attributes( 'des' );
		$this->add_render_attribute( 'des', 'class', 'cafe-des' );
		$this->add_inline_editing_attributes( 'badge_text' );

		$this->add_render_attribute( 'badge_text', 'class', 'badge-text' );

		$this->add_inline_editing_attributes( 'button_text' );
		$button_class = 'cafe-button ' . $settings['button_style'];
		$this->add_render_attribute( 'button_text', 'class', $button_class );

		$this->getViewTemplate( 'template', 'pricing-table', $settings );
	}
}
