<?php
/**
 * View template for Single Product widget
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */

$wrapClass = 'woocommerce cafe-single-product '.$settings['css_class'];
$product_id = $settings['product_id'];
$args = array(
    'post_type' => 'product',
    'posts_per_page' => 1,
    'post__in' => array($product_id)
);
$the_query = new WP_Query($args);
if ($the_query->have_posts()):
    while ($the_query->have_posts()): $the_query->the_post();
        if (!post_password_required()) {
            remove_action('woocommerce_share', 'zoo_product_share', 5);
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
            remove_action('woocommerce_single_product_summary', 'zoo_single_product_nav', 5);
            remove_action('woocommerce_after_single_product_summary', 'zoo_open_after_single_product_summary', 5);
            remove_action('woocommerce_after_single_product_summary', 'zoo_close_after_single_product_summary', 999);
            remove_action('woocommerce_before_single_product_summary', 'zoo_open_html_sticky_content', -1);
            remove_action('woocommerce_after_single_product_summary', 'zoo_close_html_sticky_content', 0);
            remove_action('woocommerce_before_single_product_summary', 'zoo_top_single_product', -10);

            remove_action('woocommerce_before_single_product', 'zoo_sticky_add_to_cart', 5);
	        do_action( 'woocommerce_before_single_product' );

            ?>
            <div class="<?php echo esc_attr($wrapClass) ?>">
                <div id="product-<?php the_ID(); ?>" <?php wc_product_class(); ?>>
                    <?php
                    add_filter('woocommerce_single_product_image_gallery_classes', function (){
                        return array('images', 'zoo-product-gallery', 'vertical-gallery', 'vertical-left',(has_post_thumbnail() ? 'with-images' : 'without-images'));
                    });
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
                </div>
            </div>
            <?php
	        do_action( 'woocommerce_after_single_product' );
        }
    endwhile;
endif;
wp_reset_postdata();