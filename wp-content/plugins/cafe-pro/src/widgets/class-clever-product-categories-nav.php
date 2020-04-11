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
	final class CleverProductCategoriesNav extends CleverWidgetBase {
		/**
		 * @return string
		 */
		function get_name() {
			return 'clever-product-categories-nav';
		}

		/**
		 * @return string
		 */
		function get_title() {
			return __( 'Clever Product Categories Navigation', 'cafe-pro' );
		}

		/**
		 * @return string
		 */
		function get_icon() {
			return 'cs-font clever-icon-list';
		}

		/**
		 * Register controls
		 */
		protected function _register_controls() {

			$this->start_controls_section(
				'section_categories', [
				'label' => __( 'Categories', 'cafe-pro' )
			] );
			;$this->add_control( 'title', [
				'label'     => __( 'Title', 'cafe-pro' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__('Product Categories','cafe-pro'),
			] );
			$this->add_control( 'cats', [
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
				'label'     => __( 'Enable Sub categories accordion', 'cafe-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'condition' => [
					'show_sub' => 'yes',
				],
			] );$this->add_control( 'css_class', [
				'label'     => __( 'Custom css class', 'cafe-pro' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
			] );

			$this->end_controls_section();

			$this->start_controls_section( 'title_style_settings', [
				'label' => __( 'Title', 'cafe-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			] );
			$this->add_control( 'title_color', [
				'label'     => __( 'Color', 'cafe-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cafe-title' => 'color: {{VALUE}};'
				]
			] );
			$this->add_control( 'title_background', [
				'label'     => __( 'Background', 'cafe-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cafe-title' => 'background: {{VALUE}};'
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
			$this->add_control('title_space', [
				'label' => __('Space', 'cafe-pro'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .cafe-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]);
			$this->add_responsive_control('title_border_radius', [
				'label' => __('Border Radius', 'cafe-pro'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'separator'   => 'before',
				'selectors' => [
					'{{WRAPPER}} .cafe-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]);
			$this->end_controls_section();
			$this->start_controls_section( 'cat_style_settings', [
				'label' => __( 'Category', 'cafe-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			] );
			$this->add_control( 'cat_color', [
				'label'     => __( 'Color', 'cafe-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cafe-cat-item>a' => 'color: {{VALUE}};'
				]
			] );
			$this->add_control( 'cat_color_hover', [
				'label'     => __( 'Color Hover', 'cafe-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cafe-cat-item:hover>a' => 'color: {{VALUE}};'
				]
			] );
			$this->add_control( 'cat_background', [
				'label'     => __( 'Background', 'cafe-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cafe-cat-item' => 'background: {{VALUE}};'
				]
			] );
			$this->add_control( 'cat_background_hover', [
				'label'     => __( 'Background Hover', 'cafe-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cafe-cat-item:hover' => 'background: {{VALUE}};'
				]
			] );
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'cat_typography',
					'selector' => '{{WRAPPER}} .cafe-cat-item',
					'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'        => 'wrap_border',
					'label'       => __( 'Border', 'cafe-pro' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .cafe-cat-item',
					'separator'   => 'before',
				]
			);
			$this->add_responsive_control('cat_padding', [
				'label' => __('Padding', 'cafe-pro'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'separator'   => 'before',
				'selectors' => [
					'{{WRAPPER}} .cafe-cat-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]);
			$this->end_controls_section();
			$this->start_controls_section( 'sub_cat_style_settings', [
				'label' => __( 'Sub Category', 'cafe-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			] );
			$this->add_responsive_control( 'sub_cat_width', [
				'label'          => __( 'With', 'elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => [ '%', 'px' ],
				'range'          => [
					'px' => [
						'max' => 1000,
					],
				],
				'default'        => [
					'size' => 250,
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'selectors'      => [
					'{{WRAPPER}} .cafe-sub-cat' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_sub' => 'yes',
					'enable_accordion' => 'no',
				],
			] );
			$this->add_control( 'sub_cat_color', [
				'label'     => __( 'Color', 'cafe-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cafe-sub-cat .cafe-cat-item>a' => 'color: {{VALUE}};'
				]
			] );
			$this->add_control( 'sub_cat_color_hover', [
				'label'     => __( 'Color Hover', 'cafe-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cafe-sub-cat .cafe-cat-item:hover>a' => 'color: {{VALUE}};'
				]
			] );
			$this->add_control( 'sub_cat_background', [
				'label'     => __( 'Background', 'cafe-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cafe-sub-cat .cafe-cat-item' => 'background: {{VALUE}};'
				]
			] );
			$this->add_control( 'sub_cat_background_hover', [
				'label'     => __( 'Background Hover', 'cafe-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cafe-sub-cat .cafe-cat-item:hover' => 'background: {{VALUE}};'
				]
			] );
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'sub_cat_typography',
					'selector' => '{{WRAPPER}} .cafe-sub-cat .cafe-cat-item',
					'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'        => 'sub_cat_border',
					'label'       => __( 'Border', 'cafe-pro' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .cafe-sub-cat .cafe-cat-item',
					'separator'   => 'before',
				]
			);
			$this->add_responsive_control('sub_cat_padding', [
				'label' => __('Padding', 'cafe-pro'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'separator'   => 'before',
				'selectors' => [
					'{{WRAPPER}} .cafe-sub-cat .cafe-cat-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'title'             => esc_html__('Product Categories','cafe-pro'),
				'cats'             => '',
				'show_sub'         => 'no',
				'enable_accordion' => 'no',
				'css_class' => 'no',
			], $this->get_settings_for_display() );
			$this->add_inline_editing_attributes('title');
			$this->add_render_attribute('title', 'class', 'cafe-title');
			$this->getViewTemplate( 'template', 'product-categories-nav', $settings );
		}
	}
endif;