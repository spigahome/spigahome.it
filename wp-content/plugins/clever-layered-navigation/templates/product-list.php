<?php
/**
 */

if (have_posts()) {
    while (have_posts()) : the_post();
    
    /**
     * woocommerce_shop_loop hook.
     *
     * @hooked WC_Structured_Data::generate_product_data() - 10
     */
    do_action('woocommerce_shop_loop');

    wc_get_template_part('content', 'product');

    endwhile; // end of the loop.
} elseif (!woocommerce_product_subcategories(['before' => woocommerce_product_loop_start(false), 'after' => woocommerce_product_loop_end(false)])) {

    /**
     * woocommerce_no_products_found hook.
     *
     * @hooked wc_no_products_found - 10
     */
    do_action('woocommerce_no_products_found');
}
