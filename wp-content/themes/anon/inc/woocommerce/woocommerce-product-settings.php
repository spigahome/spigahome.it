<?php
/**
 * Zoo WooCommerce advanced product tabs settings.
 * Add more settings for product.
 * @package     Zoo Theme
 * @version     1.0.0
 * @core        3.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2019 ZooTemplate
 */

/**
 * zoo_advanced_product_tab
 * Use for add custom advanced tab to product settings page.
 * @uses Apply to filter woocommerce_product_data_tabs
 *
 */
function zoo_advanced_product_tab( $tabs ) {
	$tabs['zoo_adv_tab'] = array(
		'label'  => esc_html__( 'Zoo Advanced', 'anon' ),
		'target' => 'zoo_adv_tab',
		'class'  => array( 'zoo_adv_tab_show' ),
	);

	return $tabs;
}

add_filter( 'woocommerce_product_data_tabs', 'zoo_advanced_product_tab' );

/**
 * Contents of zoo advanced product tab.
 */
function zoo_advanced_product_tab_content() {
	global $post;
	// Note the 'id' attribute needs to match the 'target' parameter set above
	?>
    <div id='zoo_adv_tab' class='panel woocommerce_options_panel'>
    <div class='options_group'><?php
		woocommerce_wp_text_input( array(
			'id'                => '_zoo_number_product_on_event',
			'label'             => __( 'Number product on event', 'anon' ),
			'desc_tip'          => 'true',
			'description'       => __( 'Use for display product stock countdown  bar. Product must on sale and Manage stock is enable.', 'anon' ),
			'type'              => 'number',
			'custom_attributes' => array(
				'min'  => '0',
				'step' => '1',
			),
		) );
		?>
    </div>
    </div>
    <?php
}

add_action( 'woocommerce_product_data_panels', 'zoo_advanced_product_tab_content' );

/**
 * Save contents of zoo advanced product tab.
 */
function zoo_advanced_product_tab_save_content( $post_id ) {
    //Update zoo_number_product_on_event
	$zoo_number_product_on_event = isset( $_POST['_zoo_number_product_on_event'] ) ?$_POST['_zoo_number_product_on_event']: '0';
	update_post_meta( $post_id, '_zoo_number_product_on_event', $zoo_number_product_on_event );

}
add_action( 'woocommerce_process_product_meta_simple', 'zoo_advanced_product_tab_save_content');
add_action( 'woocommerce_process_product_meta_variable', 'zoo_advanced_product_tab_save_content');