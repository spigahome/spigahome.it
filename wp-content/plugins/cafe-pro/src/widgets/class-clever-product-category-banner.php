<?php

namespace Cafe\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

/**
 * CleverProductCategoryBanner
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
if ( class_exists( 'WooCommerce' ) ):
	final class CleverProductCategoryBanner extends CleverWidgetBase {
		/**
		 * @return string
		 */
		function get_name() {
			return 'clever-product-category-banner';
		}

		/**
		 * @return string
		 */
		function get_title() {
			return __( 'Clever Product Category Banner', 'cafe-pro' );
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
				'label' => __( 'Category', 'cafe-pro' )
			] );
			$this->add_control( 'cat', [
				'label'       => __( 'Category', 'cafe-pro' ),
				'description' => __( 'Select category you want display', 'cafe-pro' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => $this->get_categories_for_cafe( 'product_cat' ),
			] );
			$this->add_control( 'image', [
				'label'         => __( 'Upload Image', 'cafe-pro' ),
				'description'   => __( 'Select an image for the banner.', 'cafe-pro' ),
				'type'          => Controls_Manager::MEDIA,
				'dynamic'       => [ 'active' => true ],
				'show_external' => true,
				'default'       => [
					'url' => CAFE_URI . 'assets/img/banner-placeholder.png'
				]
			] );
			$this->add_control( 'title', [
				'label'       => __( 'Title', 'cafe-pro' ),
				'placeholder' => __( 'Title for Category.', 'cafe-pro' ),
				'description' => __( 'Leave it blank if use default category name.', 'cafe-pro' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => [ 'active' => true ],
				'label_block' => true
			] );
			$this->add_control( 'show_des', [
				'label'        => __( 'Show description', 'cafe-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => __( 'Show category description.', 'cafe-pro' ),
				'return_value' => 'true',
				'default'      => 'false',
			] );
			$this->add_control( 'des', [
				'label'       => __( 'Description', 'cafe-pro' ),
				'placeholder' => __( 'Description for Category.', 'cafe-pro' ),
				'description' => __( 'Leave it blank if use default category name.', 'cafe-pro' ),
				'type'        => Controls_Manager::TEXTAREA,
				'condition'   => [
					'show_des' => 'true'
				],
				'label_block' => true
			] );
			$this->add_control( 'show_count', [
				'label'        => __( 'Show Product Count', 'cafe-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => __( 'Show product count.', 'cafe-pro' ),
				'return_value' => 'true',
				'default'      => 'false',
			] );
			$this->add_control(
				'button_divider',
				[
					'type'  => Controls_Manager::DIVIDER,
					'style' => 'thick',
				]
			);
			$this->add_control('button_label', [
				'label' => __('Button Label', 'cafe-pro'),
				'placeholder' => __('Button', 'cafe-pro'),
				'description' => __('Leave it blank if don\'t use it', 'cafe-pro'),
				'type' => Controls_Manager::TEXT,
				'dynamic' => ['active' => true],
				'default' => __('Button', 'cafe-pro'),
				'label_block' => false
			]);
			$this->add_control('button_icon', [
				'label' => __('Icon', 'cafe-pro'),
				'type' => 'clevericon'
			]);
			$this->add_control('button_icon_pos', [
				'label' => __('Icon position', 'cafe-pro'),
				'type' => Controls_Manager::SELECT,
				'default' => 'after',
				'options' => [
					'before' => __('Before', 'cafe-pro'),
					'after' => __('After', 'cafe-pro'),
				]
			]);
			$this->add_control('button_style', [
				'label' => __('Button style', 'cafe-pro'),
				'type' => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' => __('Normal', 'cafe-pro'),
					'underline' => __('Underline', 'cafe-pro'),
					'outline' => __('Outline', 'cafe-pro'),
				]
			]);
			$this->add_control(
				'css_class_divider',
				[
					'type'  => Controls_Manager::DIVIDER,
					'style' => 'thick',
				]
			);
			$this->add_control( 'css_class', [
				'label'       => __( 'Custom HTML Class', 'cafe-pro' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'You may add a custom HTML class to style element later.', 'cafe-pro' ),
			] );
			$this->end_controls_section();
			$this->start_controls_section( 'normal_style_settings', [
				'label' => __( 'Normal', 'cafe-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			] );
			$this->add_control( 'overlap', [
				'label'        => __( 'Overlap image', 'cafe-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => __( 'Content of product will show below image.', 'cafe-pro' ),
				'return_value' => 'true',
				'default'      => 'true',
			] );
			$this->add_control( 'content_align', [
				'label'     => __( 'Vertical Align', 'cafe-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'flex-start',
				'options'   => [
					'flex-start' => __( 'Top', 'cafe-pro' ),
					'center'     => __( 'Middle', 'cafe-pro' ),
					'flex-end'   => __( 'Bottom', 'cafe-pro' ),
				],
				'condition' => [
					'overlay_banner' => 'true'
				],
				'selectors' => [
					'{{WRAPPER}} .cafe-wrap-product-category-content' => 'justify-content: {{VALUE}};'
				]
			] );
			$this->add_responsive_control( 'text_align', [
				'label'     => __( 'Text Align', 'cafe-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => [
					'left'   => __( 'Left', 'cafe-pro' ),
					'center' => __( 'Center', 'cafe-pro' ),
					'right'  => __( 'Right', 'cafe-pro' ),
				],
				'selectors' => [
					'{{WRAPPER}} .cafe-product-category-content' => 'text-align: {{VALUE}};'
				]
			] );
			$this->add_responsive_control( 'dimensions', [
				'label'      => __( 'Dimensions', 'cafe-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .cafe-wrap-product-category-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			] );
			$this->add_control( 'overlay_bg_heading', [
				'label' => __( 'Overlay Background', 'cafe-pro' ),
				'type'  => Controls_Manager::HEADING
			] );
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name'     => 'overlay_bg',
					'label'    => __( 'Background Overlay', 'cafe-pro' ),
					'types'    => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .cafe-wrap-product-category-content',
				]
			);
			$this->add_control(
				'content_bg_divider',
				[
					'type'  => Controls_Manager::DIVIDER,
					'style' => 'thick',
				]
			);
			$this->add_control( 'content_bg_heading', [
				'label' => __( 'Content Background', 'cafe-pro' ),
				'type'  => Controls_Manager::HEADING
			] );
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name'     => 'content_bg',
					'label'    => __( 'Content Background', 'cafe-pro' ),
					'types'    => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .cafe-product-category-content',
				]
			);
			$this->add_control(
				'title_color_divider',
				[
					'type'  => Controls_Manager::DIVIDER,
					'style' => 'thick',
				]
			);
			$this->add_control( 'title_color_heading', [
				'label' => __( 'Title', 'cafe-pro' ),
				'type'  => Controls_Manager::HEADING
			] );
			$this->add_control( 'title_color', [
				'label'     => __( 'Title Color', 'cafe-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cafe-title, {{WRAPPER}} .cafe-product-category-content' => 'color: {{VALUE}};'
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

			$this->add_control(
				'des_color_divider',
				[
					'type'  => Controls_Manager::DIVIDER,
					'style' => 'thick',
				]
			);
			$this->add_control( 'des_color_heading', [
				'label' => __( 'Description', 'cafe-pro' ),
				'type'  => Controls_Manager::HEADING
			] );
			$this->add_control( 'des_color', [
				'label'     => __( 'Description Color', 'cafe-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cafe-description' => 'color: {{VALUE}};'
				]
			] );
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'des_typography',
					'selector' => '{{WRAPPER}} .cafe-description',
					'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				]
			);
			$this->add_control(
				'count_color_divider',
				[
					'type' => Controls_Manager::DIVIDER,
					'style' => 'thick',
				]
			);
			$this->add_control( 'count_color_heading', [
				'label' => __( 'Product Count', 'cafe-pro' ),
				'type'  => Controls_Manager::HEADING
			] );
			$this->add_control( 'count_color', [
				'label'     => __( 'Color', 'cafe-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .product-count' => 'color: {{VALUE}};'
				]
			] );
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'cont_typography',
					'selector' => '{{WRAPPER}} .product-count',
					'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				]
			);
			$this->add_control(
				'button_style_divider',
				[
					'type' => Controls_Manager::DIVIDER,
					'style' => 'thick',
				]
			);
			$this->add_control('button_style_heading', [
				'label' => __('Button', 'cafe-pro'),
				'type' => Controls_Manager::HEADING
			]);
			$this->add_control('button_color', [
				'label' => __('Button Color', 'cafe-pro'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cafe-button' => 'color: {{VALUE}};'
				]
			]);
			$this->add_control('button_bg', [
				'label' => __('Button Background', 'cafe-pro'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cafe-button.outline::after,{{WRAPPER}}  .cafe-button.normal::after' => 'background: {{VALUE}};'
				]
			]);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'button_typography',
					'selector' => '{{WRAPPER}} .cafe-button',
					'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				]
			);
			$this->add_responsive_control('button_spacing', [
				'label' => __('Spacing', 'cafe-pro'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .cafe-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]);
			$this->add_responsive_control('button_border_radius', [
				'label' => __('Border Radius', 'cafe-pro'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .cafe-button,{{WRAPPER}} .cafe-button:before,{{WRAPPER}} .cafe-button:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]);
			$this->end_controls_section();
			$this->start_controls_section( 'hover_style_settings', [
				'label' => __( 'Hover', 'cafe-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			] );
			$this->add_control( 'title_color_hover', [
				'label'     => __( 'Title Color', 'cafe-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cafe-product-category-banner:hover .cafe-title:hover' => 'color: {{VALUE}};'
				]
			] );
			$this->add_control( 'des_color_hover', [
				'label'     => __( 'Description Color', 'cafe-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cafe-product-category-banner:hover .cafe-description' => 'color: {{VALUE}};'
				]
			] );
			$this->add_control( 'button_color_hover', [
				'label'     => __( 'Button Color', 'cafe-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cafe-button:hover' => 'color: {{VALUE}};'
				]
			] );
			$this->add_control( 'button_bg_hover', [
				'label'     => __( 'Button Background', 'cafe-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cafe-button.outline:hover:after,{{WRAPPER}}  .cafe-button.normal:hover:after' => 'background: {{VALUE}};'
				]
			] );
			$this->add_control(
				'overlay_bg_hover_divider',
				[
					'type'  => Controls_Manager::DIVIDER,
					'style' => 'thick',
				]
			);
			$this->add_control( 'overlay_bg_hover_heading', [
				'label' => __( 'Background Overlay', 'cafe-pro' ),
				'type'  => Controls_Manager::HEADING
			] );
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name'     => 'overlay_bg_hover',
					'label'    => __( 'Background Overlay', 'cafe-pro' ),
					'types'    => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .cafe-product-category-banner:hover .cafe-wrap-product-category-content',
				]
			);
			$this->add_control(
				'content_bg_hover_divider',
				[
					'type'  => Controls_Manager::DIVIDER,
					'style' => 'thick',
				]
			);
			$this->add_control( 'content_bg_hover_heading', [
				'label' => __( 'Background Content', 'cafe-pro' ),
				'type'  => Controls_Manager::HEADING
			] );
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name'     => 'content_bg_hover',
					'label'    => __( 'Background Content', 'cafe-pro' ),
					'types'    => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .cafe-product-category-banner:hover .cafe-product-category-content',
				]
			);
			$this->end_controls_section();
		}

		/**
		 * Load style
		 */
		public function get_style_depends() {
			return [ 'cafe-style' ];
		}

		/**
		 * Render
		 */
		protected function render() {
			// default settings
			$settings = array_merge( [
				'cat'        => '',
				'image'      => '',
				'title'      => '',
				'show_des'   => '',
				'des'        => '',
				'show_count' => '',
				'overlap' => '',
				'button_label' => '',
				'button_icon' => '',
				'button_icon_pos' => 'after',
				'button_style' => 'normal',
				'css_class' => '',

			], $this->get_settings_for_display() );

			$this->add_inline_editing_attributes( 'title' );

			$this->add_inline_editing_attributes('button_label');

			$this->add_render_attribute( 'title', 'class', 'cafe-title' );

			$button_html_classes='cafe-button '.$settings['button_style'];
			$this->add_render_attribute('button_label', 'class', $button_html_classes);

			$this->getViewTemplate( 'template', 'product-category-banner', $settings );
		}
	}
endif;