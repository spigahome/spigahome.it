<?php
/**
 * Template display cover image of Archive WooCommerce Page template.
 * @since: zoo-theme 3.0.0
 * @Ver: 3.0.0
 */
if(is_product()){
    return;
}
if (!check_vendor()) {
    $enable_shop_heading = get_theme_mod('zoo_enable_shop_heading', 1);
    $count_text = wc_get_loop_prop('total');
    if ($count_text > 1) {
        $count_text .= esc_html__(' Items', 'anon');
    } else {
        $count_text .= esc_html__(' Item', 'anon');
    }
    if (is_shop()) {
        if ($enable_shop_heading && get_post_meta(get_the_id(), 'zoo_disable_title', true) != 1) {
            ?>
            <h2 class="shop-title"><?php echo woocommerce_page_title(); ?> <span
                        class="total-product">(<?php echo esc_html($count_text); ?>)</span></h2>
            <?php
        }
    } else {
        if ($enable_shop_heading) {
            ?>
            <h2 class="shop-title"><?php echo woocommerce_page_title(); ?> <span
                        class="total-product">(<?php echo esc_html($count_text); ?>)</span></h2>
            <?php
        }
    }
}