<?php
/**
 * View template for Clever Categories widget
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */

if ( $settings['cats'] ) {

	$list_sub_cat     = '';
	$accordion_button = '';
	$cafe_list_cat_css = 'cafe-product-categories-nav';
	if($settings['css_class']!=''){
		$cafe_list_cat_css.=' '.$settings['css_class'];
    }
	if ( $settings['show_sub'] == 'yes' && $settings['enable_accordion'] == 'yes' ) {
		$cafe_list_cat_css .= ' cafe-accordion';
		$accordion_button = '<span class="cafe-btn-accordion"><i class="cs-font clever-icon-down"></i></span>';
	}
	?>
    <div class="<?php echo esc_attr($cafe_list_cat_css);?>">
        <?php
        printf('<h3 %s>%s</h3>', $this->get_render_attribute_string('title'), $settings['title']);
        ?>
        <ul class="cafe-wrap-product-categories-nav">
			<?php
			foreach ( $settings['cats'] as $product_cat_slug ) {
				$product_cat = get_term_by( 'slug', $product_cat_slug, 'product_cat' );
				$selected    = '';
				if ( isset( $product_cat->slug ) ) {
					if ( $settings['show_sub'] == 'yes' ) {
						$list_sub_cat = $this->getListProductSubCat( $product_cat->term_id, $product_cat->slug);
						if ( $list_sub_cat != '' ) {
							$list_sub_cat = $accordion_button . $list_sub_cat;
						}
					}
					echo '<li class="cafe-cat-item"><a href="' . esc_url( get_term_link( $product_cat->slug, 'product_cat' ) ) . '"
                                title="' . esc_attr( $product_cat->name ) . '">' . esc_html( $product_cat->name ) . '</a>' . $list_sub_cat . '</li>';
				}

			} ?>
        </ul>
    </div>
	<?php
}