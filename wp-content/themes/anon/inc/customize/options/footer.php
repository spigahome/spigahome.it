<?php
/**
 * Customize for Shop loop product
 */
return [
	[
		'type'           => 'section',
		'name'           => 'zoo_footer',
		'title'          => esc_html__( 'Footer', 'anon' ),
	],
	[
		'name'           => 'zoo_footer_general_settings',
		'type'           => 'heading',
		'label'          => esc_html__( 'Footer Settings', 'anon' ),
		'section'        => 'zoo_footer',
	],
	[
		'type'        => 'textarea',
		'name'        => 'zoo_footer_copy_right',
		'label'       => esc_html__( 'Copyright', 'anon' ),
		'section'     => 'zoo_footer',
		'default' => sprintf(esc_html__( '© %s ZooTemplate. All rights reserved.', 'anon' ),date("Y")),
	],
];
