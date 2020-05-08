<?php
/**
 * Product extend cart info template
 * @package     Zoo Theme
 * @version     3.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 *
 */
if(class_exists('\Elementor\Plugin')) {
    if (\Elementor\Plugin::$instance->preview->is_preview_mode()) {
        return;
    }
}
?>
<div class="wrap-content-popup-page">
    <?php
    if ( get_theme_mod( 'zoo_single_product_cart_size_guide', '' ) != '' ) {
        $size_page = get_post( get_theme_mod( 'zoo_single_product_cart_size_guide') );
        if ( $size_page != null ) {
            ?>
            <div class="content-popup-page size-guide-content">
                <?php
                echo apply_filters( 'the_content', $size_page->post_content ); ?>
            </div>
            <?php
        }
    }
    if ( get_theme_mod( 'zoo_single_product_cart_delivery', '' ) != '' ) {
        $delivery_page = get_post( get_theme_mod( 'zoo_single_product_cart_delivery') );
        if ( $delivery_page != null ) {
            ?>
            <div class="content-popup-page delivery-return-content">
                <?php echo apply_filters( 'the_content', $delivery_page->post_content ); ?>
            </div>
            <?php
        }
    }
    if ( get_theme_mod( 'zoo_single_product_cart_ask_product', '' ) != '' ) {
        $ask_page = get_post( get_theme_mod( 'zoo_single_product_cart_ask_product') );
        if ( $ask_page != null ) {
            ?>
            <div class="content-popup-page ask-product-content">
                <?php echo apply_filters( 'the_content', $ask_page->post_content ); ?>
            </div>
            <?php
        }
    }
    ?>
    <span class="close-popup-page"><i class="cs-font clever-icon-close"></i></span>
</div>
<div class="close-zoo-extend-cart-info">
</div>
