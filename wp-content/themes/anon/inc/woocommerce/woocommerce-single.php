<?php
/**
 * WooCommerce Single Product functions for control features and templates.
 * @package     Zoo Theme
 * @version     3.0.0
 * @author      Zootemplate
 * @link        http://www.zootemplate.com
 * @copyright   Copyright (c) 2017 Zootemplate
 * @des         All custom functions, hooks, template of single product WooCommerce.
 */


/**
 * Add custom image size
 */
add_image_size('zoo_gallery_thumb', get_theme_mod('zoo_gallery_thumbnail_width','120'), get_theme_mod('zoo_gallery_thumbnail_height','120'), get_theme_mod('zoo_gallery_thumbnail_crop','0'));
add_filter('image_size_names_choose', 'zoo_custom_gallery_img_sizes');
if(!function_exists('zoo_custom_gallery_img_sizes')){
    function zoo_custom_gallery_img_sizes($imgsizes)
    {
        return array_merge($imgsizes, array(
            'zoo_gallery_thumb' => esc_html__('Custom Product Gallery Thumbnail', 'anon'),
        ));
    }
}

/**
 * Change Image thumbnail gallery size
 *
 * */
add_filter( 'woocommerce_gallery_thumbnail_size', function( $size ) {
    return 'zoo_gallery_thumb';
} );

/**
 * Add breadcrumb for single product.
 */
add_action( 'woocommerce_before_main_content', 'zoo_single_product_top_nav',5 );
if ( ! function_exists( 'zoo_single_product_top_nav' ) ) {
	function zoo_single_product_top_nav() {
		if ( get_theme_mod( 'zoo_enable_single_product_nav', '0' ) == 1 ) {
			zoo_single_product_nav();
		}
	}
}

/**
 *  Add theme support product gallery light box and zoom if that features is activated.
 * @uses: Must hook to action after_setup_theme for it work.
 */
if ( ! function_exists( 'zoo_enable_product_gallery_features' ) ) {
	function zoo_enable_product_gallery_features() {
		if ( get_theme_mod( 'zoo_enable_product_lb', '1' ) == 1 ) {
			add_theme_support( 'wc-product-gallery-lightbox' );
		}
		if ( get_theme_mod( 'zoo_enable_product_zoom', '1' ) == 1 ) {
			add_theme_support( 'wc-product-gallery-zoom' );
		}
	}
}
add_action( 'after_setup_theme', 'zoo_enable_product_gallery_features' );


/**
 * Zoo get product tabs setting
 * @return string tab setting follow customize option or product option.
 **/
function zoo_product_tabs_setting() {
	$tab_setting        = get_theme_mod( 'zoo_product_tabs_setting', 'tabs' );
	$zoo_layout_gallery = zoo_single_product_layout();
	if ( $zoo_layout_gallery != 'custom' ) {
		switch ( $zoo_layout_gallery ) {
			case 'sticky-1':
				$tab_setting = 'accordion';
				break;
			case 'sticky-2':
				$tab_setting = 'accordion';
				break;
			case 'accordion':
				$tab_setting = 'accordion';
				break;
			case 'carousel':
				$tab_setting = 'accordion';
				break;
			default:
				$tab_setting = 'tabs';
		}
	}

	return $tab_setting;
}

/**
 * Remove single add to cart button
 * If catalog mod is enable, cart button will remove.
 * @uses override function woocommerce_template_single_add_to_cart and return false
 * @return false
 *
 */
if ( zoo_enable_catalog_mod() ) {
	function woocommerce_template_single_add_to_cart() {
		return false;
	}
}

/**
 * Change product thumbnail item display same time.
 * If Gallery layout is slider, this value will apply for main product slider.
 */
if ( ! function_exists( 'zoo_product_thumb_cols' ) ) {
	function zoo_product_thumb_cols() {
	    $cols = get_theme_mod( 'zoo_product_thumb_cols', '3' );
	    $product_col=get_post_meta(get_the_ID(),'zoo_product_gallery_columns',true);
	    if($product_col!='inherit'&&$product_col!=''){
		    $cols= $product_col;
        }
        return $cols;
	}
}
add_filter( 'woocommerce_product_thumbnails_columns', 'zoo_product_thumb_cols' );

/**
 * Remove product tabs heading
 */
add_filter( 'woocommerce_product_description_heading', '__return_null' );
add_filter( 'woocommerce_product_additional_information_heading', '__return_null' );

/**
 * Add wrapper for single product page with class layout of single product page
 * @return css class wrapper single product
 * */
if ( ! function_exists( 'zoo_before_single_product' ) ) {
	function zoo_before_single_product() {
		$allow_html = array( 'div' => array( 'class' => array() ) );
		$classes    = 'wrap-single-product-content';
		switch ( zoo_single_product_content_layout() ) {
			case 'left_content':
				$classes .= ' left-content-layout content-half-width';
				break;
			case 'sticky_content':
				wp_enqueue_script( 'sticky-kit' );
				$classes .= ' sticky-content-layout content-half-width';
				break;
			case 'center':
				$classes .= ' center-layout';
				break;
			case 'full_content':
				$classes .= ' full-content-layout';
				break;
			default:
				$classes .= ' right-content-layout content-half-width';
				break;
		};
		$classes .= ' product-' . zoo_product_gallery_layout() . '-gallery';
		echo wp_kses( '<div class="' . $classes . '">', $allow_html );
	}
}
add_action( 'woocommerce_before_single_product', 'zoo_before_single_product' );

/**
 * End Add wrapper for single product page
 * */
if ( ! function_exists( 'zoo_after_single_product' ) ) {
	function zoo_after_single_product() {
		echo '</div>';
	}
}
add_action( 'woocommerce_after_single_product', 'zoo_after_single_product' );

/**
 * zoo_oembed_dataparse
 * normally video iframe for product video lightbox.
 * @uses Use hook for add to oembed_dataparse
 * @return parameter of video iframe.
 */
if ( ! function_exists( 'zoo_oembed_dataparse' ) ) {
	function zoo_oembed_dataparse( $return, $data, $url ) {
		if ( false === strpos( $return, 'youtube.com' ) ) {
			return $return;
		}
		$id     = explode( 'watch?v=', $url );
		$add_id = str_replace( 'allowfullscreen>', 'allowfullscreen id="yt-' . $id[1] . '">', $return );
		$add_id = str_replace( '?feature=oembed', '?enablejsapi=1', $add_id );

		return $add_id;
	}
}
add_filter( 'oembed_dataparse', 'zoo_oembed_dataparse', 10, 3 );
/**
 * Single product video
 * @uses Use hook for add to single product
 * @return Video button template for show video light box when click to.
 *
 * */
if ( ! function_exists( 'zoo_product_video' ) ) {
	function zoo_product_video() {
		$zoo_product_video = get_post_meta( get_the_ID(), 'zoo_single_product_video', true );
		if ( $zoo_product_video != '' ) {
			$zoo_product_video_url = parse_url( $zoo_product_video );
			if ( $zoo_product_video_url['host'] == 'vimeo.com' ) {
				wp_enqueue_script( 'vimeoapi', 'https://player.vimeo.com/api/player.js', true );
				$zoo_embed_class = 'vimeo-embed';
			}
			if ( $zoo_product_video_url['host'] == 'youtube.com' || $zoo_product_video_url['host'] == 'www.youtube.com' ) {
				wp_enqueue_script( 'youtube-api', 'https://www.youtube.com/player_api', true );
				$zoo_embed_class = 'youtube-embed';
			}
			$zoo_pv_html = '<div class="wrap-product-video ' . $zoo_embed_class . '"><a href="' . $zoo_product_video . '"  class="button video-lb-control" title="' . esc_attr__( "Watch Video", 'anon' ) . '"><i class="cs-font clever-icon-play-1"></i><span>' . esc_html__( "Watch Video", 'anon' ) . '</span></a>';
			$zoo_pv_html .= '<div class="mask-product-video"><i class="cs-font clever-icon-close-1"></i></div>' . wp_oembed_get( $zoo_product_video ) . '</div>';
			echo ent2ncr( $zoo_pv_html );
			wp_enqueue_script( 'zoo-product-embed' );
		} else {
			return false;
		}
	}
}
/**
 * Product extended feature
 * Display product images 360 view and Product Video block.
 * @uses Use hook for display
 *
 */
if ( ! function_exists( 'zoo_product_extended_feature' ) ) {
	function zoo_product_extended_feature() {
		echo '<div class="product-extended-button">';
		/**
		 * Call function zoo_product_video
		 * Load Template Product video for display
		 * */
		zoo_product_video();
		/**
		 * Load Template Product Image 360 view for display
		 * */
		get_template_part( 'inc/templates/woocommerce/single-product/product-image-360', 'view' );
		echo '</div>';
	}
}
add_action( 'woocommerce_before_single_product_summary', 'zoo_product_extended_feature', 15 );

/**
 * Open html wrap woocommerce_before_single_product_summary
 * @uses hook to woocommerce_before_single_product_summary for open html wrap woocommerce_before_single_product_summary
 * @return html open woocommerce_before_single_product_summary
 */
if ( ! function_exists( 'zoo_open_before_single_product_summary' ) ) {
	function zoo_open_before_single_product_summary() {
		$allow_html = array( 'div' => array( 'class' => array() ) );
		echo wp_kses( '<div class="wrap-single-product-images">', $allow_html );
	}
}
add_action( 'woocommerce_before_single_product_summary', 'zoo_open_before_single_product_summary', 0 );

/**
 * Close html wrap woocommerce_before_single_product_summary
 * @uses hook to woocommerce_before_single_product_summary for open html wrap woocommerce_before_single_product_summary
 * @return html close woocommerce_before_single_product_summary
 */
if ( ! function_exists( 'zoo_close_before_single_product_summary' ) ) {
	function zoo_close_before_single_product_summary() {
		$allow_html = array( 'div' => array( 'class' => array() ) );
		echo wp_kses( '</div>', $allow_html );
	}
}
add_action( 'woocommerce_before_single_product_summary', 'zoo_close_before_single_product_summary', 99 );

/**
 * Change gallery class
 * This will apply class of gallery layout user config in customize/product option.
 */
function zoo_change_product_gallery_class() {

	if ( isset( $_POST['product_id'] ) ) {
		$gallery_layout = zoo_product_gallery_layout( $_POST['product_id'] );
	} else {
		$gallery_layout = zoo_product_gallery_layout();
	}

	$gallery_class   = array(
		'images',
		'zoo-product-gallery',
		( has_post_thumbnail() ? 'with-images' : 'without-images' )
	);
	$gallery_class[] = 'gallery-' . apply_filters( 'woocommerce_product_thumbnails_columns', 4 ) . '-cols';
	if ( $gallery_layout == 'vertical-left' || $gallery_layout == 'vertical-right' ) {
		$gallery_class[] = $gallery_layout;
		$gallery_class[] = 'vertical-gallery';
	} else {
		$gallery_class[] = $gallery_layout . '-gallery';
		if ($gallery_layout == 'grid' || $gallery_layout == 'sticky') {
			wp_enqueue_script( 'sticky-kit' );
			$gallery_class[] = 'none-slider';
		}
	}
	if ($gallery_layout != 'grid' ) {
		wp_enqueue_style( 'slick' );
		wp_enqueue_script( 'slick' );
	}

	return $gallery_class;
}

add_filter( 'woocommerce_single_product_image_gallery_classes', 'zoo_change_product_gallery_class' );

/**
 * Product sold between 24h
 * @uses hook to location want add this template
 * @return string template with numbers product sold.
 */
if ( ! function_exists( 'zoo_product_enable_sold_per_day' ) ) {
	function zoo_product_enable_sold_per_day() {
	    if(get_theme_mod('zoo_product_enable_sold_per_day',1)==1) {
		    get_template_part( 'inc/templates/woocommerce/single-product/product', 'sold' );
	    }
	}
}
add_action( 'zoo_product_enable_sold_per_day', 'zoo_product_enable_sold_per_day', 0 );

/**
 * Product countdown sale template
 * @uses hook to location want add this template
 * @return template with sale countdown time.
 */
if ( ! function_exists( 'zoo_single_product_sale_countdown' ) ) {
	function zoo_single_product_sale_countdown() {
		get_template_part( 'inc/templates/woocommerce/global/sale-count', 'down' );
	}
}
add_action( 'woocommerce_single_product_summary', 'zoo_single_product_sale_countdown', 25 );

/**
 * Product countdown sale template variable
 * @uses hook to location want add this template
 * @return template with sale countdown time.
 */
if ( ! function_exists( 'zoo_filter_woocommerce_available_variation' ) ) {

	function zoo_filter_woocommerce_available_variation( $variation_get_max_purchase_quantity, $instance, $variation ) {

        $variation_get_max_purchase_quantity['availability_html']=wc_get_stock_html( $variation ).zoo_sale_count_down_variable_html($variation);
	    return $variation_get_max_purchase_quantity; 
	}; 
}
// add the filter 
add_filter( 'woocommerce_available_variation', 'zoo_filter_woocommerce_available_variation', 10, 3 ); 
if(!function_exists('zoo_sale_count_down_variable_html')){
	function zoo_sale_count_down_variable_html($variation){
		$sale_date = strtotime($variation->get_date_on_sale_to());
		$stock = $variation->get_stock_quantity();
		wp_enqueue_script('countdown');
		$html = '';
		if($sale_date > time()) {
			$html .= '<div class="zoo-countdown">';
			$html .= '<span class="label-product-countdown">'.esc_html__("Hurry Up! Sales end in","anon").'</span>';
			$html .= '<div class="countdown-block" data-countdown="countdown" data-date=" '.esc_attr(date("m", $sale_date) . "-" . date("d", $sale_date) . "-" . date("Y", $sale_date) . "-" . date("H", $sale_date) . "-" . date("i", $sale_date) . "-" . date("s", $sale_date)).' ">';
		    $html .=  '</div>';
			$html .= '</div>';
			if(get_theme_mod('zoo_product_stock_countdown',1)==1){
				$zoo_base_stock = get_post_meta( get_the_ID(), '_zoo_number_product_on_event', true );
				if($zoo_base_stock!=0 && $stock!=NULL) {
	                $stock_parse = '';
	                $loading_percent = ( $stock / $zoo_base_stock ) * 100;
	                if ($loading_percent < 40) {
	                    $stock_parse = 'final-parse';
	                } elseif ($loading_percent >= 40 && $loading_percent < 80) {
	                    $stock_parse = 'second-parse';
	                } else {
	                    $stock_parse = 'first-parse';
	                }
	                
	                $html .= '<div class="zoo-stock-countdown">';
	                $html .= '<span class="label-product-stock-countdown">';
	                $html .= esc_html__( 'Hurry! Only ', 'anon').'<b class="number-product">'.$stock.'</b>'.esc_html__(' left in Stock.', 'anon' );
	                $html .= '</span>';
	                $html .= '<div class="stock-countdown-bar">';
	                $html .= '<span class="inner-stock-countdown-bar '. esc_attr($stock_parse).'" style="width:'.esc_attr($loading_percent) . '%"></span>';
	                $html .= '</div>';
	                $html .= '</div>';
	            }

			}
		}
		return $html;
	}
}

/**
 * Product extend cart info button
 * @uses hook to location want add this template
 * @return string template display button size guide, delivery & return, ask about product.
 */
if ( ! function_exists( 'zoo_single_product_extend_cart_info_button' ) ) {
	function zoo_single_product_extend_cart_info_button() {
		get_template_part( 'inc/templates/woocommerce/single-product/extend-cart', 'info-button' );
	}
}
add_action( 'woocommerce_before_add_to_cart_button', 'zoo_single_product_extend_cart_info_button', 5 );

/**
 * Product extend cart info content
 * @uses hook to location want add this template
 * @return string template display content size guide, delivery & return, ask about product.
 */
if ( ! function_exists( 'zoo_single_product_extend_cart_info_content' ) ) {
	function zoo_single_product_extend_cart_info_content() {
		get_template_part( 'inc/templates/woocommerce/single-product/extend-cart', 'info-content' );
	}
}
add_action( 'woocommerce_single_product_summary', 'zoo_single_product_extend_cart_info_content', 100 );

/**
 * Product extend notice
 * @uses hook to location want add this template
 * @return template display shipping time, count visiter, free shipping amount.
 */
if ( ! function_exists( 'zoo_single_product_extend_notice' ) ) {
	function zoo_single_product_extend_notice() {
		get_template_part( 'inc/templates/woocommerce/single-product/extend', 'notice' );
	}
}
add_action( 'woocommerce_single_product_summary', 'zoo_single_product_extend_notice', 35 );

/**
 * Change layout of product tabs
 * This will add wrapper for product tabs, allow user select layout for product tabs is tabs or accordion.
 * @uses Remove hook woocommerce_output_product_data_tabs and replace by zoo_product_data_tabs with wrapper and new layout.
 * @return new product tabs layout with layout is tabs of accordion.
 */
function zoo_product_data_tabs() {
	$allow_html = array(
		'div' => array( 'class' => array() )
	);
	$layout     = zoo_product_tabs_setting() . '-layout';
	echo wp_kses( '<div class="zoo-product-data-tabs ' . esc_attr( $layout ) . '">', $allow_html );
	woocommerce_output_product_data_tabs();
	echo wp_kses( '</div>', $allow_html );
}

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );

/**
 * Add Product share template
 * zoo_product_share function to hook woocommerce_share for display share template
 */
if ( ! function_exists( 'zoo_product_share' ) ) {
	function zoo_product_share() {
		get_template_part( 'inc/templates/woocommerce/single-product/share' );
	}
}
if ( zoo_enable_product_share() ) {
	add_action( 'woocommerce_share', 'zoo_product_share', 5 );
}

/**
 * Single Product nav
 * Display next/previous product.
 * @uses Use hook for display
 *
 */
if ( ! function_exists( 'zoo_single_product_nav' ) ) {
	function zoo_single_product_nav() {
		get_template_part( 'inc/templates/woocommerce/single-product/single-product', 'nav' );
	}
}
/**
 * Load option with page option control.
 * */

add_action( 'template_redirect', 'zoo_add_wrap_for_single_product_content' );
function zoo_add_wrap_for_single_product_content() {
	/**
	 * Change tabs follow layout
	 */
	if ( zoo_add_tab_to_summary() ) {
		add_action( 'woocommerce_single_product_summary', 'zoo_product_data_tabs', 99 );
	} else {
		add_action( 'woocommerce_after_single_product_summary', 'zoo_product_data_tabs', 10 );
	}
	/**
	 * Add wrap for images and short content of product single with layout sticky
	 * @uses hook to woocommerce_after_single_product_summary and woocommerce_before_single_product_summary
	 * @return  html for wrap block
	 */
	if ( zoo_single_product_content_layout() == 'sticky_content' ) {
		add_action( 'woocommerce_before_single_product_summary', 'zoo_open_html_sticky_content', - 1 );
		add_action( 'woocommerce_after_single_product_summary', 'zoo_close_html_sticky_content', 0 );
	}
	/**
	 * Add wrap for summary and product tabs content of product single with layout full content
	 * @uses hook to woocommerce_after_single_product_summary and woocommerce_before_single_product_summary
	 * @return  html for wrap block
	 */
	if ( zoo_single_product_content_layout() == 'full_content' ) {
		add_action( 'woocommerce_before_single_product_summary', 'zoo_open_html_container_full_content', 999 );
		add_action( 'woocommerce_single_product_summary', 'zoo_open_wrap_html_summary_content', - 100 );
		add_action( 'woocommerce_single_product_summary', 'zoo_close_wrap_html_summary_content', 90 );
		add_action( 'woocommerce_after_single_product_summary', 'zoo_close_html_container_full_content', 999 );
	}
}

/**
 * Add label to top product summary follow hook woocommerce_single_product_summary
 * @uses hook to woocommerce_single_product_summary
 * @return html label new.
 */
function zoo_single_product_new_label() {
	global $product;
	if ( get_post_meta( $product->get_id(), 'zoo_single_product_new', true ) == 1 ) {
		?>
        <span class="zoo-new-label"><?php esc_html_e( 'New', 'anon' ); ?></span>
		<?php
	}
}

add_action( 'woocommerce_single_product_summary', 'zoo_single_product_new_label', 0 );

/**
 * Move woocommerce_show_product_sale_flash from woocommerce_before_single_product_summary to woocommerce_single_product_summary
 */
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_show_product_sale_flash', 0 );


/**
 * Add stock label with product not is grouped to top woocommerce_single_product_summary
 * @uses add to hook woocommerce_single_product_summary
 * @return html stock label
 */
function zoo_wc_get_stock_html() {
	global $product;
	$availability = $product->get_availability();
	$label= $availability['class']!='out-of-stock'?esc_html__('In stock','anon'):$availability['availability'];
	$html='<p class="zoo-single-stock-label stock '. $availability['class'].'">'.wp_kses_post( $label ).'</p>';
	echo apply_filters( 'zoo_get_stock_html', $html);
}

add_action( 'woocommerce_single_product_summary', 'zoo_wc_get_stock_html', 0 );

/**
 * Change location of single product rating
**/
remove_action('woocommerce_single_product_summary','woocommerce_template_single_rating',10);
add_action('zoo_custom_product_meta','woocommerce_template_single_rating',0);

/**
 * Zoo single product custom Meta.
 * Add Product Meta to before price.
 * @uses hook to woocommerce_template_single_price
 * @return html meta
 */
if ( ! function_exists( 'zoo_single_product_meta' ) ) {
	function zoo_single_product_meta() {
		get_template_part( 'inc/templates/woocommerce/single-product/product-custom', 'meta' );
	}
}
add_action( 'woocommerce_single_product_summary', 'zoo_single_product_meta', 5 );

/**
 * Add open html wrap for images and short content of product single with layout sticky
 * @uses hook to woocommerce_after_single_product_summary and woocommerce_before_single_product_summary
 * @return open html for wrap block
 */
function zoo_open_html_sticky_content() {
	$allow_html = array( 'div' => array( 'class' => array() ) );
	echo wp_kses( '<div class="wrap-sticky-content-block">', $allow_html );
}

/**
 * Add close html wrap for images and short content of product single with layout sticky
 * @uses hook to woocommerce_after_single_product_summary and woocommerce_after_single_product_summary
 * @return close html for wrap block
 */
function zoo_close_html_sticky_content() {
	$allow_html = array( 'div' => array( 'class' => array() ) );
	echo wp_kses( '</div>', $allow_html );
}

/**
 * Add open html wrap for summary and product tabs content of product single with layout full content
 * @uses hook to woocommerce_before_single_product_summary and woocommerce_before_single_product_summary
 * @return  html for wrap block
 */
function zoo_open_html_container_full_content() {
	$allow_html = array( 'div' => array( 'class' => array() ) );
	echo wp_kses( '<div class="container">', $allow_html );
}

/**
 * Add open html wrap for summary and product tabs content of product single with layout full content
 * @uses hook to woocommerce_single_product_summary and zoo_open_wrap_html_summary_content
 * @return  html for wrap block
 */
function zoo_open_wrap_html_summary_content() {
	$allow_html = array( 'div' => array( 'class' => array() ) );
	echo wp_kses( '<div class="wrap-summary">', $allow_html );
}

/**
 * Add close html wrap for summary and product tabs content of product single with layout full content
 * @uses hook to woocommerce_single_product_summary and zoo_open_wrap_html_summary_content
 * @return  html for wrap block
 */
function zoo_close_wrap_html_summary_content() {
	$allow_html = array( 'div' => array( 'class' => array() ) );
	echo wp_kses( '</div>', $allow_html );
}

/**
 * Add close html wrap for summary and product tabs content of product single with layout full content
 * @uses hook to woocommerce_after_single_product_summary and zoo_close_html_container_full_content
 * @return  html for wrap block
 */
function zoo_close_html_container_full_content() {
	$allow_html = array( 'div' => array( 'class' => array() ) );
	echo wp_kses( '</div>', $allow_html );
}

/**
 * Related Products
 */
if ( get_theme_mod( 'zoo_enable_related_products', 1 ) == 1 ) {
	/**
	 * zoo_related_products_loop_start
	 * Change html of woocommerce_product_loop_start for template related product
	 * @return: html open product loop start of related product with config.
	 */
	if ( ! function_exists( 'zoo_related_products_loop_start' ) ) {
		function zoo_related_products_loop_start() {
			//Get option from customize
			$layout      = get_theme_mod( 'zoo_related_products_layout', 'carousel' );
			$cols        = get_theme_mod( 'zoo_related_products_cols', 4 );
			$data_config = '{"cols":"' . $cols . '"}';
            $css_class= "products zoo-products hover-effect-" . zoo_product_hover_effect();
			if ( $layout == 'grid' ) {
				$css_class .= " grid-layout";
				$css_class .= ' grid-lg-' . $cols . '-cols grid-md-' . zoo_shop_columns( "tablet" ) . '-cols grid-' . zoo_shop_columns( "mobile" ) . '-cols';

				return '<ul class="' . esc_attr( $css_class ) . '" data-zoo-config=\'' . esc_attr( $data_config ) . '\'>';
			} else {
				wp_enqueue_style( 'slick' );
				wp_enqueue_script( 'slick' );
				$css_class .= " carousel-layout carousel";

				return '<ul class="' . esc_attr( $css_class ) . '" data-zoo-config=\'' . esc_attr( $data_config ) . '\'>';
			}
		}
	}

	/**
	 * zoo_woo_related_products_limit
	 * Change number related products by using filter woocommerce_output_related_products_args
	 * @return: number product per page for related product.
	 */
	if ( ! function_exists( 'zoo_related_products_number' ) ) {
		function zoo_related_products_number( $args ) {
			add_filter( 'woocommerce_product_loop_start', 'zoo_related_products_loop_start' );
			$args['posts_per_page'] = get_theme_mod( 'zoo_related_products_number', '4' );

			return $args;
		}
	}
	add_filter( 'woocommerce_output_related_products_args', 'zoo_related_products_number' );
}

/**
 * Control related product.
 * Remove related product hook if disable related products.
 * @return: remove template single-product/related.php by hook woocommerce_output_related_products.
 */
if ( get_theme_mod( 'zoo_enable_related_products', 1 ) != 1 ) {
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products',20 );
}

/**
 * Move upsell after related product
 */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 25 );

/**
 * zoo_upsell_products_loop_start
 * Change html of woocommerce_product_loop_start for template upsell products
 * @return: html open product loop start of upsell products with config.
 * @use: zoo_change_upsell_products_loop_start for apply to loop start
 */
if ( ! function_exists( 'zoo_upsell_products_loop_start' ) ) {
	function zoo_upsell_products_loop_start() {
		$allow_html  = array( 'ul' => array( 'class' => array(), 'data-zoo-config' => array() ) );
		$layout      = get_theme_mod( 'zoo_upsell_products_layout', 'grid' );
		$cols        = get_theme_mod( 'zoo_upsell_products_cols', 4 );
		$data_config = '{"cols":"' . $cols . '"}';
		if ( $layout == 'grid' ) {
			$css_class = "products zoo-products grid-layout";
			$css_class .= ' grid-lg-' . $cols . '-cols grid-md-' . zoo_shop_columns( "tablet" ) . '-cols grid-' . zoo_shop_columns( "mobile" ) . '-cols';
		} else {
			wp_enqueue_style( 'slick' );
			wp_enqueue_script( 'slick' );
			$css_class = "products zoo-products carousel-layout carousel";
		}

		return wp_kses( '<ul class="' . $css_class . '" data-zoo-config=\'' . $data_config . '\'>', $allow_html );
	}
}
/**
 * zoo_change_upsell_products_loop_start
 * @return: upsell products loop start template.
 * @use hook to woocommerce_after_single_product_summary and must load before uppsell_product_display.
 */
if ( ! function_exists( 'zoo_change_upsell_products_loop_start' ) ) {
	function zoo_change_upsell_products_loop_start() {
		add_filter( 'woocommerce_product_loop_start', 'zoo_upsell_products_loop_start' );

		return false;
	}
}
add_action( 'woocommerce_after_single_product_summary', 'zoo_change_upsell_products_loop_start', 5 );

/**
 * Recently Viewed Products cookie.
 * @base: Base on function wc_track_product_view of WooCommerce.
 * @des: Allow add cookie for woocommerce_recently_viewed on case widget not woocommerce_recently_viewed_products active. It need for function zoo_recently_viewed_product below.
 * */
if ( ! function_exists( 'zoo_track_product_view' ) && get_theme_mod( 'zoo_enable_recently_viewed_product', 1 ) == 1 ) {
	function zoo_track_product_view() {
		if ( is_active_widget( false, false, 'woocommerce_recently_viewed_products', true ) || ! is_singular( 'product' ) ) {
			return;
		}

		global $post;
		if ( empty( $_COOKIE['woocommerce_recently_viewed'] ) ) {
			$viewed_products = array();
		} else {
			$viewed_products = wp_parse_id_list( (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) );
		}
		$keys = array_flip( $viewed_products );
		if ( isset( $keys[ $post->ID ] ) ) {
			unset( $viewed_products[ $keys[ $post->ID ] ] );
		}
		$viewed_products[] = $post->ID;
		if ( count( $viewed_products ) > 15 ) {
			array_shift( $viewed_products );
		}

		// Store for session only.
		wc_setcookie( 'woocommerce_recently_viewed', implode( '|', $viewed_products ) );
	}

	add_action( 'template_redirect', 'zoo_track_product_view', 20 );
}

/**
 * Recently Viewed Products.
 * @uses: Hook function zoo_recently_viewed_product to default WooCommerce function want use.
 * */
if ( ! function_exists( 'zoo_recently_viewed_product' ) && get_theme_mod( 'zoo_enable_recently_viewed_product', 1 ) == 1 ) {
	function zoo_recently_viewed_product() {
		$viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', wp_unslash( str_replace( get_the_ID(), '', $_COOKIE['woocommerce_recently_viewed'] ) ) ) : array();
		$viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );

		if ( empty( $viewed_products ) ) {
			return;
		}

		$allow_html = array(
			'section' => array( 'class' => array() ),
			'ul'      => array( 'class' => array(), 'data-zoo-config' => array() ),
			'h2'      => array( 'class' => array() )
		);
		//Get option from customize
		$number = get_theme_mod( 'zoo_recently_viewed_product_number', 8 );
		$layout = get_theme_mod( 'zoo_recently_viewed_product_layout', 'carousel' );
		$cols   = get_theme_mod( 'zoo_recently_viewed_product_cols', 4 );

		$query_args = array(
			'posts_per_page' => $number,
			'no_found_rows'  => 1,
			'post_status'    => 'publish',
			'post_type'      => 'product',
			'post__in'       => $viewed_products,
			'orderby'        => 'post__in',
		);

		if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'outofstock',
					'operator' => 'NOT IN',
				),
			);
		}

		$the_query = new WP_Query( apply_filters( 'woocommerce_recently_viewed_products_widget_query_args', $query_args ) );
		$content   = '';
		if ( $the_query->have_posts() ) {
			if ( $layout == 'grid' && zoo_highlight_featured() == '1' ) {
				wp_enqueue_script( 'isotope' );
			}
			if ( $layout == 'carousel' ) {
				wp_enqueue_style( 'slick' );
				wp_enqueue_script( 'slick' );
			}
			ob_start();
			echo wp_kses( '<section class="recently-viewed-product products">', $allow_html );
			echo wp_kses( '<h2>', $allow_html );
			esc_html_e( 'Recently viewed products', 'anon' );
			echo wp_kses( '</h2>', $allow_html );
			$wrap_class = 'products zoo-product ' . $layout . ' ' . $layout . '-layout  hover-effect-' . zoo_product_hover_effect();
			echo wp_kses( '<ul class="' . $wrap_class . '" data-zoo-config=\'{"cols":"' . $cols . '"}\'>', $allow_html );
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				wc_get_template_part( 'content', 'product' );
			}
			echo wp_kses( '</ul>', $allow_html );
			echo wp_kses( '</section>', $allow_html );
			$content = ob_get_clean();
		}

		wp_reset_postdata();
		echo wp_kses_post( $content );

	}

	add_action( 'woocommerce_after_single_product_summary', 'zoo_recently_viewed_product', 25 );
}

/**
 * Buy Now button
 * Auto redirect to cart after product added to cart.
 * @uses hook to woocommerce_after_add_to_cart_button
 * @return template of Buy Now button
 */
if ( ! function_exists( 'zoo_buy_now_button' ) ) {
	function zoo_buy_now_button() {
		if ( get_theme_mod( 'zoo_enable_buy_now', '0' ) == 1 ) {
			get_template_part( 'inc/templates/woocommerce/single-product/buy', 'now' );
			}
	}
}
add_action( 'woocommerce_after_add_to_cart_button', 'zoo_buy_now_button', 15 );

/**
 * Sticky Add to cart
 * Show up when user scroll to bottom.
 * @uses hook to woocommerce_after_single_product
 * @return template of Sticky Add to cart
 */
if ( ! function_exists( 'zoo_sticky_add_to_cart' ) ) {
	function zoo_sticky_add_to_cart() {
		if ( get_theme_mod( 'zoo_enable_sticky_add_to_cart', '1' ) == 1 ) {
			global $product;
			$html = '<div id="zoo-sticky-add-to-cart" class="zoo-sticky-add-to-cart"><div class="container"><div class="wrap-product-content"> ';
			$html .= '<h3 class="product-title">' . get_the_title() . '</h3>';
			$html .= '<p class="price">' . $product->get_price_html() . '</p>';
			$html .= '</div><a href="#" class="button button-sticky-add-to-cart" title="' . esc_attr__( 'Add to cart', 'anon' ) . '">' . esc_html__( 'Add to cart', 'anon' ) . '</a>';
			$html .= '</div></div>';
			echo ent2ncr( $html );
		}
	}
}
add_action( 'woocommerce_after_single_product', 'zoo_sticky_add_to_cart', 5 );

/**
 * Wrap quantity, wishlist and compare button.
 */
function zoo_open_wrap_qty_template() {
	$allow_html = array( 'div' => array( 'class' => array() ) );
	echo wp_kses( '<div class="wrap-group-qty">', $allow_html );
}

add_action( 'woocommerce_before_add_to_cart_quantity', 'zoo_open_wrap_qty_template', 0 );
function zoo_close_wrap_qty_template() {
	global $product;
	$product_type = $product->get_type();
	if ( $product_type != 'grouped' && $product_type != 'external' && $product_type != 'bundle' ) {
		$allow_html = array( 'div' => array(), 'class' => array() );
		echo wp_kses( '</div>', $allow_html );
	}
}

add_action( 'woocommerce_after_add_to_cart_button', 'zoo_close_wrap_qty_template', 5 );