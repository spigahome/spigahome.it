<?php
/**
 * Product Quick View Template
 */
remove_action('woocommerce_share', 'zoo_product_share', 5);
remove_action('woocommerce_single_product_summary', 'zoo_single_product_nav', 5);
remove_action('woocommerce_after_single_product_summary', 'zoo_open_after_single_product_summary', 5);
remove_action('woocommerce_after_single_product_summary', 'zoo_close_after_single_product_summary', 999);
remove_action('woocommerce_before_single_product_summary', 'zoo_open_html_sticky_content', -1);
remove_action('woocommerce_after_single_product_summary', 'zoo_close_html_sticky_content', 0);
remove_action( 'woocommerce_before_add_to_cart_button', 'zoo_single_product_extend_cart_info_button', 5 );
remove_action( 'woocommerce_single_product_summary', 'zoo_single_product_extend_cart_info_content', 100 );
remove_action( 'woocommerce_single_product_summary', 'zoo_single_product_extend_notice', 35 );
remove_action( 'woocommerce_after_add_to_cart_button', 'zoo_buy_now_button', 15 );
global $product;
?>
<div id="zoo-quickview-lb" class="zoo-product-quick-view woocommerce">
    <div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>
        <a href="#" class="close-btn close-quickview" title="<?php esc_attr__('Close', 'anon') ?>"><i
                    class="cs-font clever-icon-close"></i> </a>
        <?php
        if (post_password_required()) {
            echo get_the_password_form(); // WPCS: XSS ok.
        } else {

            add_filter('woocommerce_single_product_image_gallery_classes', 'zoo_quick_view_change_images_class');
            /**
             * Hook: woocommerce_before_single_product_summary.
             *
             * @hooked woocommerce_show_product_sale_flash - 10
             * @hooked woocommerce_show_product_images - 20
             */
            do_action('woocommerce_before_single_product_summary');
            ?>
            <div class="summary entry-summary">
                <?php
                /**
                 * Hook: woocommerce_single_product_summary.
                 *
                 * @hooked woocommerce_template_single_title - 5
                 * @hooked woocommerce_template_single_rating - 10
                 * @hooked woocommerce_template_single_price - 10
                 * @hooked woocommerce_template_single_excerpt - 20
                 * @hooked woocommerce_template_single_add_to_cart - 30
                 * @hooked woocommerce_template_single_meta - 40
                 * @hooked woocommerce_template_single_sharing - 50
                 * @hooked WC_Structured_Data::generate_product_data() - 60
                 */
                do_action('woocommerce_single_product_summary');
                ?>
            </div>
        <?php } ?>
    </div>
</div>
