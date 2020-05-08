<?php
/**
 * View template for Clever Canvas Cart
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */

if ( null === WC()->cart ) {
    return;
}
$cart_url = wc_get_cart_url();
$cart_count = WC()->cart->get_cart_contents_count();
$cart_total = WC()->cart->get_cart_total();
$class = 'cafe-canvas-cart';
if ($settings['cart_count'] == 'yes') {
    $class .= ' count-' . $settings['cart_count_position'];
}
if ($settings['subtotal'] == 'yes') {
    $class .= ' subtotal-' . $settings['subtotal_position'];
}
if (empty($cart_count)) {
    $cart_count = '0';
    $class .= ' cart-empty';
}

$widget_id = uniqid('cafe-canvas-cart');
?>
    <input type="checkbox" id="<?php echo esc_attr($widget_id); ?>" class="cafe-toggle-input"/>
    <label for="<?php echo esc_attr($widget_id) ?>" class="<?php echo esc_attr($class) ?>">
            <span class="cafe-wrap-icon-cart">
                <i class="<?php echo esc_attr($settings['icon']) ?>"></i>
                <?php
                if ($settings['cart_count_position'] != 'inline' && $settings['cart_count'] == 'yes') {
                    ?>
                    <span class="cafe-cart-count">
                    <?php echo wp_kses_post($cart_count); ?>
                    </span>
                <?php } ?>
            </span>
        <?php if ($settings['cart_count_position'] == 'inline' || $settings['subtotal'] == 'yes') { ?>
            <span class="cafe-wrap-right-cart">
                    <?php
                    if ($settings['subtotal'] == 'yes') {
                        ?>
                        <span class="cafe-cart-subtotal">
							<?php echo wp_kses_post($cart_total); ?>
                        </span>
                    <?php }
                    if ($settings['cart_count_position'] == 'inline' && $settings['cart_count'] == 'yes') { ?>
                        (<span class="cafe-cart-count"><?php echo wp_kses_post($cart_count); ?></span>)
                    <?php }
                    ?>
                </span>
        <?php } ?>
    </label>
    <label class="cafe-canvas-cart-mask cafe-mask-close" for="<?php echo esc_attr($widget_id); ?>"></label>
    <div class="cafe-canvas-cart-content widget_shopping_cart">
        <div class="cafe-heading-cart-content">
            <?php esc_html_e('My Cart', 'cafe-pro'); ?>
            (<span class="cafe-cart-count"><?php echo esc_html(WC()->cart->get_cart_contents_count()); ?></span>)
            <span class="cafe-close-cart"><?php esc_html_e('Close', 'cafe-pro'); ?><i class="cs-font clever-icon-close"></i></span>
        </div>
        <div class="widget_shopping_cart_content">
            <?php woocommerce_mini_cart(); ?>
        </div>
    </div>
<?php
