<?php
/**
 * Meta box for theme
 *
 * @package     Zoo Theme
 * @version     1.0.0
 * @core        3.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 */

if ( class_exists( 'RWMB_Loader' ) ):
	add_filter( 'rwmb_meta_boxes', 'zoo_meta_box_options' );
	if ( ! function_exists( 'zoo_meta_box_options' ) ) {
		function zoo_meta_box_options() {
			$zoo_prefix       = "zoo_";
			$zoo_meta_boxes   = array();
			$zoo_meta_boxes[] = array(
				'id'      => $zoo_prefix . 'single_post_heading',
				'title'   => esc_html__( 'Sidebar Config', 'anon' ),
				'pages'   => array( 'post' ),
				'context' => 'side',
				'fields'  => array(
					array(
						'id'      => $zoo_prefix . "blog_single_sidebar_config",
						'type'    => 'select',
						'options' => array(
							'inherit' => esc_html__( 'Inherit', 'anon' ),
							'left'    => esc_html__( 'Left', 'anon' ),
							'right'   => esc_html__( 'Right', 'anon' ),
							''    => esc_html__( 'None', 'anon' ),
						),
						'std'     => 'inherit',
						'desc'    => esc_html__( 'Select sidebar layout you want set for this post.', 'anon' )
					),
				)
			);
			//All page
			$zoo_meta_boxes[] = array(
				'id'      => $zoo_prefix . 'layout_single_heading',
				'title'   => esc_html__( 'Layout Single Product', 'anon' ),
				'pages'   => array( 'product' ),
				'context' => 'advanced',
				'fields'  => array(
					array(
						'name'    => esc_html__( 'Layout Options', 'anon' ),
						'id'      => $zoo_prefix . "single_product_layout",
						'type'    => 'select',
						'options' => array(
							'inherit'          => esc_html__( 'Inherit', 'anon' ),
							'vertical-thumb'   => esc_html__( 'Product V1', 'anon' ),
							'horizontal-thumb' => esc_html__( 'Product V2', 'anon' ),
							'carousel'         => esc_html__( 'Product V3', 'anon' ),
							'grid-thumb'       => esc_html__( 'Product V4', 'anon' ),
							'sticky-1'         => esc_html__( 'Product V5', 'anon' ),
							'sticky-2'         => esc_html__( 'Product V6', 'anon' ),
							'sticky-3'         => esc_html__( 'Product V7', 'anon' ),
							//'accordion'        => esc_html__( 'Product V7', 'anon' ),
							'custom'           => esc_html__( 'Custom', 'anon' ),
						),
						'std'     => 'inherit',
					),
					array(
						'name'    => esc_html__( 'Content Options', 'anon' ),
						'id'      => $zoo_prefix . "single_product_content_layout",
						'type'    => 'select',
						'options' => array(
							'inherit'        => esc_html__( 'Inherit', 'anon' ),
							'right_content'  => esc_html__( 'Right Content', 'anon' ),
							'left_content'   => esc_html__( 'Left Content', 'anon' ),
							'full_content'   => esc_html__( 'Full width Content', 'anon' ),
							'sticky_content' => esc_html__( 'Sticky Content', 'anon' ),
						),
						'std'     => 'inherit',
					),
					array(
						'name'    => esc_html__( 'Gallery Options', 'anon' ),
						'id'      => $zoo_prefix . "product_gallery_layout",
						'type'    => 'select',
						'options' => array(
							'inherit'        => esc_html__( 'Inherit', 'anon' ),
							'vertical-left'  => esc_html__( 'Vertical Left Thumb', 'anon' ),
							'vertical-right' => esc_html__( 'Vertical Right Thumb', 'anon' ),
							'horizontal'     => esc_html__( 'Horizontal', 'anon' ),
							'slider'         => esc_html__( 'Slider', 'anon' ),
							'grid'           => esc_html__( 'Grid', 'anon' ),
							'sticky'         => esc_html__( 'Sticky', 'anon' ),
						),
						'std'     => 'inherit',
					),
					array(
						'name'    => esc_html__( 'Gallery Columns', 'anon' ),
						'id'      => $zoo_prefix . "product_gallery_columns",
						'type'    => 'select',
						'options' => array(
							'6' => esc_html__( '6', 'anon' ),
							'5' => esc_html__( '5', 'anon' ),
							'4' => esc_html__( '4', 'anon' ),
							'3' => esc_html__( '3', 'anon' ),
							'2' => esc_html__( '2', 'anon' ),
							'1' => esc_html__( '1', 'anon' ),
							'inherit' => esc_html__( 'Inherit', 'anon' ),
						),
						'std'     => 'inherit',
					),
                    array(
						'name'    => esc_html__( 'Display tab inside summary', 'anon' ),
						'id'      => $zoo_prefix . "add_tab_to_summary",
						'type'    => 'select',
						'options' => array(
							'no' => esc_html__( 'No', 'anon' ),
							'true' => esc_html__( 'Yes', 'anon' ),
							'inherit' => esc_html__( 'Inherit', 'anon' ),
						),
						'std'     => 'inherit',
					),
				)
			);
			$zoo_meta_boxes[] = array(
				'id'      => $zoo_prefix . 'single_product_image_360_heading',
				'title'   => esc_html__( 'Product image 360 view', 'anon' ),
				'pages'   => array( 'product' ),
				'context' => 'advanced',
				'fields'  => array(
					array(
						'id'   => $zoo_prefix . "single_product_image_360",
						'name' => esc_html__( 'Images', 'anon' ),
						'type' => 'image_advanced',
						'desc' => esc_html__( 'Images for 360 degree view.', 'anon' )
					),
				)
			);
			$zoo_meta_boxes[] = array(
				'id'      => $zoo_prefix . 'single_product_video_heading',
				'title'   => esc_html__( 'Product Video', 'anon' ),
				'pages'   => array( 'product' ),
				'context' => 'side',
				'fields'  => array(
					array(
						'id'   => $zoo_prefix . "single_product_video",
						'type' => 'oembed',
						'desc' => esc_html__( 'Enter your embed video url.', 'anon' )
					),
				)
			);
			$zoo_meta_boxes[] = array(
				'id'      => $zoo_prefix . 'single_product_new_heading',
				'title'   => esc_html__( 'Assign product is New', 'anon' ),
				'pages'   => array( 'product' ),
				'context' => 'side',
				'fields'  => array(
					array(
						'id'   => $zoo_prefix . "single_product_new",
						'std'  => '0',
						'type' => 'checkbox',
						'desc' => esc_html__( 'Is New Product.', 'anon' )
					),
				)
			);
			$zoo_meta_boxes[] = array(
				'id'      => 'title_meta_box',
				'title'   => esc_html__( 'Layout Options', 'anon' ),
				'pages'   => array( 'page', 'post' ),
				'context' => 'advanced',
				'fields'  => array(
					array(
						'name' => esc_html__( 'Title & Breadcrumbs Options', 'anon' ),
						'desc' => esc_html__( '', 'anon' ),
						'id'   => $zoo_prefix . "heading_title",
						'type' => 'heading'
					),
					array(
						'name' => esc_html__( 'Disable Title', 'anon' ),
						'desc' => esc_html__( '', 'anon' ),
						'id'   => $zoo_prefix . "disable_title",
						'std'  => '0',
						'type' => 'checkbox'
					),
					array(
						'name' => esc_html__( 'Disable Breadcrumbs', 'anon' ),
						'desc' => esc_html__( '', 'anon' ),
						'id'   => $zoo_prefix . "disable_breadcrumbs",
						'std'  => '0',
						'type' => 'checkbox'
					),
					array(
						'name' => esc_html__( 'Page Layout', 'anon' ),
						'desc' => esc_html__( '', 'anon' ),
						'id'   => $zoo_prefix . "body_heading",
						'type' => 'heading'
					),
					array(
						'name'    => esc_html__( 'Layout Options', 'anon' ),
						'id'      => $zoo_prefix . "site_layout",
						'type'    => 'select',
						'options' => array(
							'inherit'    => esc_html__( 'Inherit', 'anon' ),
							'normal'     => esc_html__( 'Normal', 'anon' ),
							'boxed'      => esc_html__( 'Boxed', 'anon' ),
							'full-width' => esc_html__( 'Full Width', 'anon' ),
						),
						'std'     => 'inherit',
					),
					array(
						'name' => esc_html__( 'Page Max Width', 'anon' ),
						'desc' => esc_html__( 'Accept only number. If not set, it will follow customize config.', 'anon' ),
						'id'   => $zoo_prefix . "site_max_width",
						'type' => 'number'
					),
				)
			);

			return $zoo_meta_boxes;
		}
	}
endif;