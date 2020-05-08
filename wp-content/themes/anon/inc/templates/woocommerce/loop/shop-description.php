<?php
/**
 * Template display cover image of Archive WooCommerce Page template.
 * @since: zoo-theme 3.0.0
 * @Ver: 3.0.0
 */
if ( ! check_vendor() ) {

	if ( is_shop() ) {
		$shop_page   = get_post( wc_get_page_id( 'shop' ) );
		$description = '';
		if ( isset( $shop_page->post_content ) ) {
			$description = wc_format_content( $shop_page->post_content );
		}
		if ( $description ) {
			woocommerce_product_archive_description();
		}
	} else {
        $enable_shop_des = get_theme_mod( 'zoo_enable_cat_des', 1 );
		if ( $enable_shop_des ) {
			$thumb_img=get_theme_mod('zoo_shop_banner','');
			if(isset($thumb_img['url'])){
				$thumb_img=$thumb_img['url'];
            }
			if ( is_product_category() ) {
				global $wp_query;
				$cat          = $wp_query->get_queried_object();
				$thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
				$thumb        = wp_get_attachment_url( $thumbnail_id );
				$thumb_img       = $thumb ? $thumb : $thumb_img;
			}
			if ( $thumb_img != '' ) {
				?>
                <img src="<?php echo esc_url($thumb_img)?>" alt="<?php echo woocommerce_page_title(); ?>" class="shop-heading-image"/>
                <?php
			}
                woocommerce_taxonomy_archive_description();
		}
	}
}