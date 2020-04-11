<?php
/**
 * Customize for Shop loop product
 */
return [
	[
		'type'           => 'section',
		'name'           => 'zoo_cart',
		'title'          => esc_html__( 'Cart Page', 'anon' ),
		'panel'          => 'woocommerce',
		'theme_supports' => 'woocommerce'
	],
	[
		'name'           => 'zoo_cart_general_settings',
		'type'           => 'heading',
		'label'          => esc_html__( 'General Settings', 'anon' ),
		'section'        => 'zoo_cart',
		'theme_supports' => 'woocommerce'
	],
	[
		'type'        => 'image',
		'name'        => 'zoo_cart_trust_badges',
		'label'       => esc_html__( 'Trust Badges', 'anon' ),
		'section'     => 'zoo_cart',
		'description' => esc_html__( 'Security & trust badges logo on cart & checkout pages.', 'anon' ),
	],
];
