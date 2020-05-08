<?php
/**
 * WooCommerce ajax functions.
 * @package     Zoo Theme
 * @version     3.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 
 * @des         All functions and hooks work with ajax will store here.
 */

/**
 * Zoo Added Cart Fragments
 * @return array custom cart fragments of theme after updateFragments worked.
*/
if (!function_exists("zoo_added_cart_fragments")) {
    function zoo_added_cart_fragments($fragments)
    {
        ob_start();
        $cart = WC()->instance()->cart;
        $fragments['cart_count'] = $cart->get_cart_contents_count();
        $fragments['.total-cart-item'] = '<span class="total-cart-item">('.$cart->get_cart_contents_count().')</span>';
        $fragments['cart_subtotal'] = $cart->get_cart_subtotal();
        ob_start();
	    if (get_theme_mod('zoo_enable_free_shipping_notice', '1') == '1') {
		    zoo_free_shipping_cart_notice();
	    }
        $fragments['free_shipping_cart_notice'] = ob_get_clean();
        return $fragments;
    }
}
add_filter('woocommerce_add_to_cart_fragments', 'zoo_added_cart_fragments');

/**
 * Zoo Add to Cart Message
 * Display add to cart message after product added to cart by using ajax add to cart.
 * @return html add to cart message.
 */
if (!function_exists("zoo_add_to_cart_message")) {
    function zoo_add_to_cart_message($fragments)
    {
        $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : '';
        if (get_option('woocommerce_cart_redirect_after_add') != 'yes' && $product_id != '') {
            $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
            $fragments['zoo_add_to_cart_message'] = '<div id="zoo-add-to-cart-message">' . wc_add_to_cart_message($product_id, $quantity, true) . '</div>';
        }
        return $fragments;
    }
}
add_filter('woocommerce_add_to_cart_fragments', 'zoo_add_to_cart_message');

/**
 * Restore item cart
 * Allow use restore item remove from cart.
 * @return Restore item remove from cart.
 */
if (!function_exists('zoo_restore_cart_item')) {
    function zoo_restore_cart_item()
    {
        $cart_item_key = sanitize_text_field($_POST['cart_item_key']);
        $cart = WC()->instance()->cart;
        $restore = WC()->cart->restore_cart_item($cart_item_key);

        if ($restore) {
            $fragments['status'] = '1';
            $fragments['cart_count'] = $cart->get_cart_contents_count();
	        $fragments['.total-cart-item'] = '<span class="total-cart-item">('.$cart->get_cart_contents_count().')</span>';
            $fragments['cart_subtotal'] = $cart->get_cart_subtotal();
            $fragments['.element-cart-count'] = '<span class="element-cart-count">'.$cart->get_cart_contents_count().'</span>';
            $fragments['.total-element-cart'] = '<span class="total-element-cart">'.$cart->get_cart_subtotal().'</span>';
            ob_start();
	        if (get_theme_mod('zoo_enable_free_shipping_notice', '1') == '1') {
		        zoo_free_shipping_cart_notice();
	        }
            $fragments['free_shipping_cart_notice'] = ob_get_clean();
        } else {
            $fragments['status'] = '00';
        }
        echo json_encode($fragments);
        exit;
    }
}
add_action('wp_ajax_restore_cart_item', 'zoo_restore_cart_item');
add_action('wp_ajax_nopriv_restore_cart_item', 'zoo_restore_cart_item');

/**
 * Zoo Quick View
 * Get product quick view information.
 * @return template product quick view.
 */
if (!function_exists('zoo_quick_view')) {
    function zoo_quick_view()
    {
        global $post, $product;
        $product_id = $_POST['product_id'];
        $product = wc_get_product($product_id);
        $post = $product->post;
        setup_postdata($post);
        ob_start();
        get_template_part('inc/templates/woocommerce/product-quick', 'view');

        $output = ob_get_contents();
        ob_end_clean();
        wp_reset_postdata();
        echo ent2ncr($output);
        exit;
    }
}
add_action('wp_ajax_zoo_quick_view', 'zoo_quick_view');
add_action('wp_ajax_nopriv_zoo_quick_view', 'zoo_quick_view');