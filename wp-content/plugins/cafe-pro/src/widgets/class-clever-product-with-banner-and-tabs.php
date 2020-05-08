<?php

namespace Cafe\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;

/**
 * CleverProductwithBannerandTabs
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
if ( class_exists( 'WooCommerce' ) ):
	final class CleverProductwithBannerandTabs extends CleverWidgetBase {
		/**
		 * @return string
		 */
		function get_name() {
			return 'clever-product-with-banner-and-tabs';
		}

		/**
		 * @return string
		 */
		function get_title() {
			return __( 'Clever Product With Banner And Tabs', 'cafe-pro' );
		}

		/**
		 * @return string
		 */
		function get_icon() {
			return 'cs-font clever-icon-cart-3';
		}

		/**
		 * Register controls
		 */
		protected function _register_controls() {
			$this->start_controls_section(
				'section_title', [
				'label' => __( 'Tabs Filter', 'cafe-pro' )
			] );

			$this->add_control( 'title', [
				'label'   => __( 'Tabs Filter', 'cafe-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'CAFE Woo', 'cafe-pro' ),
			] );
			$this->add_control( 'style', [
				'label'       => __( 'Style', 'cafe-pro' ),
				'description' => __( '', 'cafe-pro' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'style-1',
				'options'     => [
					'style-1' => __( 'Style 1', 'cafe-pro' ),
					'style-2' => __( 'Style 2', 'cafe-pro' ),
				],
			] );
			$this->add_control( 'shop_all_label', [
				'label'       => __( 'Shop All Label', 'cafe-pro' ),
				'description' => __( '', 'cafe-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'	=> 'Shop Now',
				'condition'     => [
					'style' => 'style-1',
				],
				
			] );
			$this->add_control( 'shop_all_icon', [
				'label'       => __( 'Shop All Icon', 'cafe-pro' ),
				'description' => __( '', 'cafe-pro' ),
				'type'        => Controls_Manager::ICON,
				'default'	=> '',
				'condition'     => [
					'style' => 'style-1',
				],
				
			] );
			$this->add_control('shop_all_link', [
                'label'         => __('Shop All link', 'cafe-pro'),
                'description'   => __('', 'cafe-pro'),
                'type'          => Controls_Manager::URL,
                'condition'     => [
					'style' => 'style-1',
				],
            ]);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_image', [
				'label' => __( 'Image', 'cafe-pro' )
			] );

			$this->add_control( 'show_image', [
				'label'       => __( 'Show image', 'cafe-pro' ),
				'description' => __( '', 'cafe-pro' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'center',
				'options'     => [
					'left'   => __( 'Left', 'cafe-pro' ),
					'center' => __( 'Center', 'cafe-pro' ),
					'right'  => __( 'Right', 'cafe-pro' ),
					'none'   => __( 'None', 'cafe-pro' ),
				],
			] );

			$this->add_control( 'image', [
				'label' => __( 'Chooose image', 'cafe-pro' ),
				'type'  => Controls_Manager::MEDIA,
			] );
			$this->add_control( 'content_title', [
				'label'       => __( 'Title', 'cafe-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Tile', 'cafe-pro' ),
				'label_block' => true,
			] );

			$this->add_control( 'content_description', [
				'label'      => __( 'Description', 'cafe-pro' ),
				'type'       => Controls_Manager::TEXTAREA,
				'default'    => __( 'Content here!', 'cafe-pro' ),
				'show_label' => false,
			] );

			$this->add_control( 'button_text', [
				'label'   => __( 'Button Text', 'cafe-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Click Here', 'cafe-pro' ),
			] );
			$this->add_control( 'link', [
				'label'       => __( 'Link', 'cafe-pro' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://yoursite.com', 'cafe-pro' ),
			] );

			$this->end_controls_section();

			$this->start_controls_section(
				'section_filter', [
				'label' => __( 'Filter', 'cafe-pro' )
			] );

			$this->add_control( 'filter_categories', [
				'label'       => __( 'Categories', 'cafe-pro' ),
				'description' => __( '', 'cafe-pro' ),
				'type'        => Controls_Manager::SELECT2,
				'default'     => '',
				'multiple'    => true,
				'options'     => $this->get_categories_for_cafe( 'product_cat', 2 ),
			] );
			$this->add_control( 'show_sub', [
				'label'   => __( 'Show Sub categories', 'cafe-pro' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
			] );
			$this->add_control( 'enable_accordion', [
				'label'   => __( 'Enable Sub categories accordion', 'cafe-pro' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
				'condition'      => [
					'show_sub' => 'yes',
				],
			] );
			$this->add_control( 'default_category', [
				'label'       => __( 'Default categories', 'cafe-pro' ),
				'description' => __( '', 'cafe-pro' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => $this->get_categories_for_cafe( 'product_cat' ),
			] );
			$this->add_control( 'asset_type', [
				'label'       => __( 'Asset type', 'cafe-pro' ),
				'description' => __( '', 'cafe-pro' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'all',
				'options'     => $this->get_woo_asset_type_for_cafe(),
			] );

			// Filter default
			$this->add_control( 'product_ids', [
				'label'       => __( 'Exclude product IDs', 'cafe-pro' ),
				'description' => __( '', 'cafe-pro' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => $this->get_list_posts( 'product' ),
			] );
			$this->add_control( 'orderby', [
				'label'       => __( 'Order by', 'cafe-pro' ),
				'description' => __( '', 'cafe-pro' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'date',
				'options'     => $this->get_woo_order_by_for_cafe(),
			] );
			$this->add_control( 'order', [
				'label'       => __( 'Order', 'cafe-pro' ),
				'description' => __( '', 'cafe-pro' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'desc',
				'options'     => $this->get_woo_order_for_cafe(),
			] );

			$this->add_control( 'posts_per_page', [
				'label'       => __( 'Products per pages', 'cafe-pro' ),
				'description' => __( '', 'cafe-pro' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 6,
			] );
			$this->add_responsive_control( 'columns', [
				'label'           => __( 'Columns for row', 'cafe-pro' ),
				'type'            => Controls_Manager::SLIDER,
				'range'           => [
					'col' => [
						'min' => 1,
						'max' => 6,
					]
				],
				'devices'         => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => [
					'size' => 4,
					'unit' => 'col',
				],
				'tablet_default'  => [
					'size' => 3,
					'unit' => 'col',
				],
				'mobile_default'  => [
					'size' => 2,
					'unit' => 'col',
				],

			] );

			$this->end_controls_section();

			$this->start_controls_section( 'normal_style_settings', [
				'label' => __( 'Layout', 'cafe-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			] );

            $this->add_control('wrap_heading', [
                'label' => __('Wrapper', 'cafe-pro'),
                'type' => Controls_Manager::HEADING
            ]);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'        => 'wrap_border',
					'label'       => __( 'Wrap Border', 'cafe-pro' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .cafe-product-banner-and-tabs',
				]
			);
			$this->add_control('title_style_divider',[
                    'type' => Controls_Manager::DIVIDER,
                    'style' => 'thick',
                ]
            );
            $this->add_control('title_color_heading', [
                'label' => __('Title', 'cafe-pro'),
                'type' => Controls_Manager::HEADING
            ]);

			$this->add_control( 'title_color', [
				'label'     => __( 'Title Color', 'cafe-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cafe-title' => 'color: {{VALUE}};'
				]
			] );
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'title_typography',
					'selector' => '{{WRAPPER}} .cafe-title',
					'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				]
			);
			$this->add_control( 'title_background', [
				'label'     => __( 'Title Background', 'cafe-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cafe-title' => 'background: {{VALUE}};'
				]
			] );
			$this->add_responsive_control('title_padding', [
				'label' => __('Title Padding', 'cafe-pro'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .cafe-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]);
			$this->add_control('item_style_divider',[
                    'type' => Controls_Manager::DIVIDER,
                    'style' => 'thick',
                ]
            );
            $this->add_control('item_heading', [
                'label' => __('Filter Tabs', 'cafe-pro'),
                'type' => Controls_Manager::HEADING
            ]);
            $this->add_control( 'item_color', [
				'label'     => __( 'Item Color', 'cafe-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cafe-head-product-filter ul li a' => 'color: {{VALUE}};'
				]
			] );
			$this->add_control( 'item_color_hover', [
				'label'     => __( 'Item Color Hover', 'cafe-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cafe-head-product-filter ul li a:hover,{{WRAPPER}} .cafe-head-product-filter ul li a.active,' => 'color: {{VALUE}};'
				]
			] );
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'item_typography',
					'label'     => __( 'Item Typo', 'cafe-pro' ),
					'selector' => '{{WRAPPER}} .cafe-head-product-filter ul li a',
					'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				]
			);
			$this->add_responsive_control('filter_item_padding', [
				'label' => __('Item Padding', 'cafe-pro'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .cafe-ajax-load li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]);
			$this->add_control( 'shop_all_color', [
				'label'     => __( 'Shop All Color', 'cafe-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cafe-head-product-filter ul li.shop-now-button a' => 'color: {{VALUE}};'
				]
			] );
			$this->add_control( 'shop_all_color_hover', [
				'label'     => __( 'Shop All Color Hover', 'cafe-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cafe-head-product-filter ul li.shop-now-button a:hover' => 'color: {{VALUE}};'
				]
			] );
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'shop_all_typography',
					'label'     => __( 'Shop All Typo', 'cafe-pro' ),
					'selector' => '{{WRAPPER}} .cafe-head-product-filter ul li.shop-now-button a',
					'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'shop_all_icon_typography',
					'label'     => __( 'Shop All Icon Typo', 'cafe-pro' ),
					'selector' => '{{WRAPPER}} .cafe-head-product-filter ul li.shop-now-button a i',
					'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				]
			);
			$this->add_responsive_control( 'item_width', [
					'label'          => __( 'Filter Tabs Width', 'elementor' ),
					'type'           => Controls_Manager::SLIDER,
					'size_units'     => [ '%', 'px' ],
					'range'          => [
						'px' => [
							'max' => 1000,
						],
					],
					'default'        => [
						'size' => 100,
						'unit' => '%',
					],
					'tablet_default' => [
						'unit' => '%',
					],
					'mobile_default' => [
						'unit' => '%',
					],
					'selectors'      => [
						'{{WRAPPER}} .cafe-head-product-filter' => 'width: {{SIZE}}{{UNIT}};',
					],
					'condition'      => [
						'style' => 'style-1',
					],
				]
			);
			$this->add_responsive_control('item_dimensions', [
	            'label' => __('Filter Tabs Padding', 'cafe-pro'),
	            'type' => Controls_Manager::DIMENSIONS,
	            'size_units' => ['px', '%', 'em'],
	            'selectors' => [
	                '{{WRAPPER}} .cafe-head-product-filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	            ],
	        ]);
	        $this->add_control('banner_style_divider',[
                    'type' => Controls_Manager::DIVIDER,
                    'style' => 'thick',
                ]
            );
            $this->add_control('banner_heading', [
                'label' => __('Banner', 'cafe-pro'),
                'type' => Controls_Manager::HEADING
            ]);
            $this->add_control( 'banner_title_color', [
				'label'     => __( 'Banner Title Color', 'cafe-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wrap-content h3' => 'color: {{VALUE}};'
				]
			] );
			
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'banner_title_typography',
					'label'     => __( 'Banner Title Typo', 'cafe-pro' ),
					'selector' => '{{WRAPPER}} .wrap-content h3',
					'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				]
			);
			$this->add_responsive_control('banner_title_padding', [
				'label' => __('Banner Title Padding', 'cafe-pro'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wrap-content h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]);
			$this->add_control( 'banner_des_color', [
				'label'     => __( 'Banner Description Color', 'cafe-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wrap-content .des' => 'color: {{VALUE}};'
				]
			] );
			
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'banner_des_typography',
					'label'     => __( 'Banner Description Typo', 'cafe-pro' ),
					'selector' => '{{WRAPPER}} .wrap-content .des',
					'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				]
			);
			$this->add_responsive_control('banner_des_padding', [
				'label' => __('Banner Description Padding', 'cafe-pro'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wrap-content .des' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]);
			$this->add_control( 'banner_button_color', [
				'label'     => __( 'Banner Button Color', 'cafe-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wrap-content .button' => 'color: {{VALUE}};'
				]
			] );
			$this->add_control( 'banner_button_color_hover', [
				'label'     => __( 'Banner Button Color Hover', 'cafe-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wrap-content .button:hover' => 'color: {{VALUE}};'
				]
			] );
			$this->add_control( 'banner_button_bg', [
				'label'     => __( 'Banner Button Background', 'cafe-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wrap-content .button' => 'background: {{VALUE}};'
				]
			] );
			$this->add_control( 'banner_button_bg_hover', [
				'label'     => __( 'Banner Button Background Hover', 'cafe-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wrap-content .button:hover' => 'background: {{VALUE}};'
				]
			] );
			
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'banner_button_typography',
					'label'     => __( 'Banner Button Typo', 'cafe-pro' ),
					'selector' => '{{WRAPPER}} .wrap-content .button',
					'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				]
			);
			$this->add_responsive_control('banner_button_padding', [
				'label' => __('Banner Button Padding', 'cafe-pro'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wrap-content .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]);
			$this->add_responsive_control('banner_button_position_top', [
				'label' => __('Banner Button Position Top', 'cafe-pro'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wrap-content .button' => 'top: {{SIZE}}{{UNIT}};',
				],
			]);
			$this->add_responsive_control('banner_button_position_left', [
				'label' => __('Banner Button Position Left', 'cafe-pro'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wrap-content .button' => 'left: {{SIZE}}{{UNIT}};',
				],
			]);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'        => 'banner_button_border',
					'label'       => __( 'Button', 'cafe-pro' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .wrap-content .button',
				]
			);
			$this->add_responsive_control('banner_button_radius', [
				'label' => __('Banner Button Border Radius', 'cafe-pro'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wrap-content .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]);
			$this->add_responsive_control( 'image_banner_width', [
					'label'          => __( 'Image Banner Width', 'elementor' ),
					'type'           => Controls_Manager::SLIDER,
					'size_units'     => [ '%', 'px' ],
					'range'          => [
						'px' => [
							'max' => 1000,
						],
					],
					'default'        => [
						'size' => 100,
						'unit' => '%',
					],
					'tablet_default' => [
						'unit' => '%',
					],
					'mobile_default' => [
						'unit' => '%',
					],
					'selectors'      => [
						'{{WRAPPER}} .cafe-product-banner' => 'width: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control('image_banner_dimensions', [
	            'label' => __('Image Banner Padding', 'cafe-pro'),
	            'type' => Controls_Manager::DIMENSIONS,
	            'size_units' => ['px', '%', 'em'],
	            'selectors' => [
	                '{{WRAPPER}} .cafe-product-banner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	            ],
	            
	        ]);
	        $this->add_responsive_control('image_banner_min_height', [
	            'label' => __('Image Banner Min Height', 'cafe-pro'),
	            'type' => Controls_Manager::SLIDER,
	            'size_units' => ['px','em'],
	            'range'          => [
					'px' => [
						'max' => 1500,
					],
				],
	            'selectors' => [
	                '{{WRAPPER}} .cafe-product-banner' => 'min-height: {{SIZE}}{{UNIT}};',
	            ],
	            
	        ]);
	        $this->add_control('products_style_divider',[
                    'type' => Controls_Manager::DIVIDER,
                    'style' => 'thick',
                ]
            );
            $this->add_control('products_heading', [
                'label' => __('Products', 'cafe-pro'),
                'type' => Controls_Manager::HEADING
            ]);
			$this->add_responsive_control( 'products_width', [
				'label'          => __( 'Products With', 'elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => [ '%', 'px' ],
				'range'          => [
					'px' => [
						'max' => 1000,
					],
				],
				'default'        => [
					'size' => 100,
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'selectors'      => [
					'{{WRAPPER}} .products' => 'width: {{SIZE}}{{UNIT}};',
				],
			] );
			$this->add_responsive_control('products_dimensions', [
	            'label' => __('Padding', 'cafe-pro'),
	            'type' => Controls_Manager::DIMENSIONS,
	            'size_units' => ['px', '%', 'em'],
	            'selectors' => [
	                '{{WRAPPER}} .products' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	            ],
	            
	        ]);

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
			return [ 'cafe-script' ];
		}

		/**
		 * Render
		 */
		protected function render() {
			// default settings
			$settings = array_merge( [
				'title'             => '',
				'style'             => 'style-1',
				'show_image'        => '',
				'image'             => '',
				'link_image'        => '',
				'filter_categories' => '',
				'show_sub'          => 'no',
				'enable_accordion'          => 'no',
				'default_category'  => '',
				'asset_type'        => 'all',
				'product_ids'       => '',
				'orderby'           => 'date',
				'order'             => 'desc',
				'posts_per_page'    => 6,
				'columns'           => '',

			], $this->get_settings_for_display() );

			$this->add_inline_editing_attributes( 'title' );

			$this->add_render_attribute( 'title', 'class', 'cafe-title' );

			$this->getViewTemplate( 'template', 'product-with-banner-and-tabs', $settings );
		}
	}
endif;