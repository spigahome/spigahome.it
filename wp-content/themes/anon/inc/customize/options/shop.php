<?php
/**
 * Customize for Shop loop product
 */
return [
	[
		'type'           => 'section',
		'name'           => 'zoo_shop',
		'title'          => esc_html__( 'Shop Page', 'anon' ),
		'panel'          => 'woocommerce',
		'theme_supports' => 'woocommerce'
	],
	[
		'name'           => 'zoo_shop_general_settings',
		'type'           => 'heading',
		'label'          => esc_html__( 'General Settings', 'anon' ),
		'section'        => 'zoo_shop',
		'theme_supports' => 'woocommerce'
	],
	[
		'type'        => 'number',
		'name'        => 'zoo_products_number_items',
		'label'       => esc_html__( 'Product Per Page', 'anon' ),
		'section'     => 'zoo_shop',
		'description' => esc_html__( 'Number product display per page.', 'anon' ),
		'input_attrs' => array(
			'min'   => 0,
			'max'   => 100,
			'class' => 'zoo-range-slider'
		),
		'default'     => 9
	],
	[
		'name'           => 'zoo_enable_catalog_mod',
		'type'           => 'checkbox',
		'section'        => 'zoo_shop',
		'label'          => esc_html__( 'Enable Catalog Mod', 'anon' ),
		'checkbox_label' => esc_html__( 'Will be enabled if checked.', 'anon' ),
		'theme_supports' => 'woocommerce',
		'default'        => 0
	],
	[
		'name'           => 'zoo_enable_free_shipping_notice',
		'type'           => 'checkbox',
		'section'        => 'zoo_shop',
		'label'          => esc_html__( 'Enable Free Shipping Notice', 'anon' ),
		'checkbox_label' => esc_html__( 'Free shipping thresholds will show in cart if checked.', 'anon' ),
		'theme_supports' => 'woocommerce',
		'default'        => 1,
	],
	[
		'name'           => 'zoo_enable_shop_heading',
		'type'           => 'checkbox',
		'section'        => 'zoo_shop',
		'label'          => esc_html__( 'Enable Shop Heading', 'anon' ),
		'checkbox_label' => esc_html__( 'Display product archive title and description.', 'anon' ),
		'theme_supports' => 'woocommerce',
		'default'        => 1,
	],[
		'name'           => 'zoo_enable_cat_des',
		'type'           => 'checkbox',
		'section'        => 'zoo_shop',
		'label'          => esc_html__( 'Enable Product Category Description', 'anon' ),
		'checkbox_label' => esc_html__( 'Display product description and thumbnail.', 'anon' ),
		'theme_supports' => 'woocommerce',
		'default'        => 1,
	],
	[
		'name'        => 'zoo_shop_banner',
		'type'        => 'image',
		'section'     => 'zoo_shop',
		'title'       => esc_html__( 'Shop banner', 'anon' ),
		'description' => esc_html__( 'Banner image display at top Products page. It will override by Category image.', 'anon' ),
		'required'    => [ 'zoo_enable_shop_heading', '==', '1' ],
	],
	[
		'name'           => 'zoo_shop_layout_settings',
		'type'           => 'heading',
		'label'          => esc_html__( 'Layout Settings', 'anon' ),
		'section'        => 'zoo_shop',
		'theme_supports' => 'woocommerce'
	],
	[
		'name'    => 'zoo_shop_sidebar',
		'type'    => 'select',
		'section' => 'zoo_shop',
		'title'   => esc_html__( 'Shop Sidebar', 'anon' ),
		'default' => 'left',
		'choices' => [
			'top'        => esc_html__( 'Top (Horizontal)', 'anon' ),
			'left'       => esc_html__( 'Left', 'anon' ),
			'right'      => esc_html__( 'Right', 'anon' ),
			'off-canvas' => esc_html__( 'Off canvas', 'anon' ),
		]
	],
	[
		'type'           => 'checkbox',
		'name'           => 'zoo_shop_full_width',
		'label'          => esc_html__( 'Enable Shop Full Width', 'anon' ),
		'section'        => 'zoo_shop',
		'default'        => '0',
		'checkbox_label' => esc_html__( 'Shop layout will full width if enabled.', 'anon' ),
	],
	[
		'type'           => 'checkbox',
		'name'           => 'zoo_enable_shop_loop_item_border',
		'label'          => esc_html__( 'Enable Product border', 'anon' ),
		'section'        => 'zoo_shop',
		'default'        => '0',
		'checkbox_label' => esc_html__( 'Enable border for product item.', 'anon' ),
	],
	[
		'type'           => 'checkbox',
		'name'           => 'zoo_enable_highlight_featured_product',
		'label'          => esc_html__( 'Enable High light Featured Product', 'anon' ),
		'section'        => 'zoo_shop',
		'default'        => '0',
		'checkbox_label' => esc_html__( 'Featured product will display bigger more than another product.', 'anon' ),
	],
	[
		'type'        => 'number',
		'name'        => 'zoo_shop_loop_item_gutter',
		'label'       => esc_html__( 'Product Gutter', 'anon' ),
		'section'     => 'zoo_shop',
		'description' => esc_html__( 'White space between product item.', 'anon' ),
		'input_attrs' => array(
			'min'   => 0,
			'max'   => 100,
			'class' => 'zoo-range-slider'
		),
		'default'     => 30
	],
	[
		'name'        => 'zoo_shop_cols_desktop',
		'type'        => 'number',
		'label'       => esc_html__( 'Shop loop columns', 'anon' ),
		'description' => esc_html__( 'Number product per row in shop page.', 'anon' ),
		'section'     => 'zoo_shop',
		'input_attrs' => array(
			'min'   => 1,
			'max'   => 6,
			'class' => 'zoo-range-slider'
		),
		'default'     => 4
	],
	[
		'name'        => 'zoo_shop_cols_tablet',
		'type'        => 'number',
		'label'       => esc_html__( 'Shop loop columns on Tablet', 'anon' ),
		'section'     => 'zoo_shop',
		'unit'        => false,
		'input_attrs' => array(
			'min'   => 1,
			'max'   => 6,
			'class' => 'zoo-range-slider'
		),
		'default'     => 2,
	],
	[
		'name'        => 'zoo_shop_cols_mobile',
		'type'        => 'number',
		'label'       => esc_html__( 'Shop loop columns on Mobile', 'anon' ),
		'section'     => 'zoo_shop',
		'input_attrs' => array(
			'min'   => 1,
			'max'   => 6,
			'class' => 'zoo-range-slider'
		),
		'default'     => 2,
	],
	[
		'name'           => 'zoo_shop_product_item_settings',
		'type'           => 'heading',
		'label'          => esc_html__( 'Product Item Settings', 'anon' ),
		'section'        => 'zoo_shop',
		'theme_supports' => 'woocommerce'
	],
    [
        'name' => 'zoo_product_hover_effect',
        'type' => 'select',
        'section' => 'zoo_shop',
        'title' => esc_html__('Hover Effect', 'anon'),
        'description' => esc_html__('Hover Effect of product item when hover.', 'anon'),
        'default' => 'default',
        'choices' => [
            'default' => esc_html__('Default', 'anon'),
            'style-2' => esc_html__('Style 2', 'anon'),
            'style-3' => esc_html__('Style 3', 'anon'),
            'style-4' => esc_html__('Style 4', 'anon'),
            'style-5' => esc_html__('Style 5', 'anon'),
            'style-6' => esc_html__('Style 6', 'anon'),
        ]
    ],
	[
		'name'           => 'zoo_enable_shop_loop_cart',
		'type'           => 'checkbox',
		'section'        => 'zoo_shop',
		'label'          => esc_html__( 'Enable Shop Loop Cart', 'anon' ),
		'checkbox_label' => esc_html__( 'Button Add to cart will show if checked.', 'anon' ),
		'theme_supports' => 'woocommerce',
		'default'        => 0,
		'required'       => [ 'zoo_enable_catalog_mod', '!=', 1 ],
	],
	[
		'name'    => 'zoo_shop_cart_icon',
		'type'    => 'icon',
		'section' => 'zoo_shop',
		'title'   => esc_html__( 'Cart icon', 'anon' ),
		'default' => [
			'type' => 'zoo-icon',
			'icon' => 'zoo-icon-cart'
		]
	],
	[
		'type'           => 'checkbox',
		'name'           => 'zoo_enable_alternative_images',
		'label'          => esc_html__( 'Enable Alternative Image', 'anon' ),
		'section'        => 'zoo_shop',
		'default'        => '1',
		'checkbox_label' => esc_html__( 'Alternative Image will show if checked.', 'anon' ),
	],
	[
		'type'    => 'select',
		'name'    => 'zoo_sale_type',
		'label'   => esc_html__( 'Sale label type display', 'anon' ),
		'section' => 'zoo_shop',
		'default' => 'text',
		'choices' => [
			'numeric' => esc_html__( 'Numeric', 'anon' ),
			'text'    => esc_html__( 'Text', 'anon' ),
		]
	],
	[
		'type'           => 'checkbox',
		'name'           => 'zoo_enable_shop_new_label',
		'label'          => esc_html__( 'Show New Label', 'anon' ),
		'section'        => 'zoo_shop',
		'default'        => '1',
		'checkbox_label' => esc_html__( 'Stock New will show if checked.', 'anon' ),
	],
	[
		'type'           => 'checkbox',
		'name'           => 'zoo_enable_shop_stock_label',
		'label'          => esc_html__( 'Show Stock Label', 'anon' ),
		'section'        => 'zoo_shop',
		'default'        => '1',
		'checkbox_label' => esc_html__( 'Stock label will show if checked.', 'anon' ),
	],
	[
		'type'           => 'checkbox',
		'name'           => 'zoo_enable_quick_view',
		'label'          => esc_html__( 'Enable Quick View', 'anon' ),
		'section'        => 'zoo_shop',
		'default'        => '1',
		'checkbox_label' => esc_html__( 'Button quick view will show if checked.', 'anon' ),
	],
	[
		'type'           => 'checkbox',
		'name'           => 'zoo_enable_shop_loop_rating',
		'label'          => esc_html__( 'Show rating', 'anon' ),
		'section'        => 'zoo_shop',
		'default'        => '1',
		'checkbox_label' => esc_html__( 'Show rating in product item if checked.', 'anon' ),
	],
	/*Product image thumb for gallery*/
	[
		'name'           => 'zoo_gallery_thumbnail_heading',
		'type'           => 'heading',
		'label'          => esc_html__( 'Gallery Thumbnail', 'anon' ),
		'section'        => 'woocommerce_product_images',
		'theme_supports' => 'woocommerce'
	],
	[
		'type'           => 'number',
		'name'           => 'zoo_gallery_thumbnail_width',
		'label'          => esc_html__( 'Gallery Thumbnail Width', 'anon' ),
		'section'        => 'woocommerce_product_images',
		'default'        => '120',
		'description' => esc_html__( 'Max width of image for gallery thumbnail.', 'anon' ),
	],
	[
		'type'           => 'number',
		'name'           => 'zoo_gallery_thumbnail_height',
		'label'          => esc_html__( 'Gallery Thumbnail Height', 'anon' ),
		'section'        => 'woocommerce_product_images',
		'default'        => '120',
	],
	[
		'type'           => 'checkbox',
		'name'           => 'zoo_gallery_thumbnail_crop',
		'label'          => esc_html__( 'Crop', 'anon' ),
		'section'        => 'woocommerce_product_images',
		'default'        => '0',
		'checkbox_label' => esc_html__( 'Crop Gallery Thumbnail.', 'anon' ),
	],
];
