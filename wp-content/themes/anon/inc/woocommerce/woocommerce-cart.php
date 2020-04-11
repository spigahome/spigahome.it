<?php
/**
 * WooCommerce functions for cart, check out, my account page
 * @package     Zoo Theme
 * @version     3.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 * @des         All custom functions of WooCommerce for control templates, features of cart, check out, my account page.
 */

/**
 * Free shipping cart notice
 * Allow display free shipping notice in cart and minicart with loading parse bar.
 * @uses using hook zoo_enable_free_shipping_notice, can apply to default hook of WooCommerce
 * @return Free shipping notice template with loading bar.
 *
 */

if ( get_theme_mod( 'zoo_enable_free_shipping_notice', '1' ) == '1' ) {
	if ( ! function_exists( 'zoo_free_shipping_cart_notice' ) ) {
		function zoo_free_shipping_cart_notice() {

			if ( WC()->cart->is_empty() ) {
				return;
			}
			// Get Free Shipping Methods for Rest of the World Zone & populate array $min_amounts
			$default_zone = new WC_Shipping_Zone( 0 );

			$default_methods = $default_zone->get_shipping_methods();
			foreach ( $default_methods as $key => $value ) {
				if ( $value->id === "free_shipping" ) {
					if ( $value->min_amount > 0 ) {
						$min_amounts[] = $value->min_amount;
					}
				}
			}
			// Get Free Shipping Methods for all other ZONES & populate array $min_amounts
			$delivery_zones = WC_Shipping_Zones::get_zones();
			foreach ( $delivery_zones as $key => $delivery_zone ) {
				foreach ( $delivery_zone['shipping_methods'] as $key => $value ) {
					if ( $value->id === "free_shipping" ) {
						if ( $value->min_amount > 0 ) {
							$min_amounts[] = $value->min_amount;
						}
					}
				}
			}
			// Find lowest min_amount
			if ( isset( $min_amounts ) ) {
				if ( is_array( $min_amounts ) && $min_amounts ) {
					$min_amount = min( $min_amounts );
					// Get Cart Subtotal inc. Tax excl. Shipping
					$current = WC()->cart->subtotal;
					// If Subtotal < Min Amount Echo Notice
					// and add "Continue Shopping" button
					if ( $current > 0 ) {
						$percent = round( ( $current / $min_amount ) * 100, 2 );
						$percent >= 100 ? $percent = '100' : '';
						if ( $percent < 40 ) {
							$parse_class = 'first-parse';
						} elseif ( $percent >= 40 && $percent < 80 ) {
							$parse_class = 'second-parse';
						} else {
							$parse_class = 'final-parse';
						}
						$parse_class .= ' free-shipping-required-notice';
						$added_text='<i class="cs-font clever-icon-truck"></i> ';
						if ( $current < $min_amount ) {
							$added_text .= esc_html__( 'Spend ', 'anon' ) . wc_price( $min_amount - $current ) . esc_html__( '  to get Free Shipping ', 'anon' );
						} else {
							$added_text .= esc_html__( 'Congratulations! You\'ve got free shipping!', 'anon' );
						}
						$html = '<div class="' . esc_attr( $parse_class ) . '">';
						$html .= '<div class="zoo-loading-bar"><div class="load-percent" style="width:' . esc_attr( $percent ) . '%">';
						$html .= '</div><span class="label-free-shipping">'.$added_text.'</span></div>';
						$html .= '</div>';
						echo ent2ncr( $html );
					}
				}
			}
		}
	}
	add_action( 'woocommerce_widget_shopping_cart_before_buttons', 'zoo_free_shipping_cart_notice', 5 );
	add_action( 'zoo_free_shipping_cart_notice', 'zoo_free_shipping_cart_notice', 5 );
	function zoo_body_class_enable_free_shipping_notice( $classes ) {
		$classes[] = 'free-shipping-notice-enable';

		return $classes;
	}

	add_filter( 'body_class', 'zoo_body_class_enable_free_shipping_notice' );
}

/**
 * Wrap cart content
 * Add div for wrap cart content
 * @close zoo_woocommerce_after_cart
 * @uses hook to function woocommerce_before_cart
 * @return open html wrap cart content
 */
if ( ! function_exists( 'zoo_woocommerce_before_cart' ) ) {
	function zoo_woocommerce_before_cart() {
		$allow_html = array( 'div' => array( 'class' => array() ) );
		echo wp_kses( '<div class="zoo-wrap-cart-content">', $allow_html );
	}
}
add_action( 'woocommerce_before_cart', 'zoo_woocommerce_before_cart', 5 );

/**
 * Close Wrap cart content
 * Add close div for wrap cart content
 * @uses hook to function woocommerce_after_cart
 * @return close html wrap cart content
 */
if ( ! function_exists( 'zoo_woocommerce_after_cart' ) ) {
	function zoo_woocommerce_after_cart() {
		$allow_html = array( 'div' => array( 'class' => array() ) );
		echo wp_kses( '</div>', $allow_html );
	}
}
add_action( 'woocommerce_after_cart', 'zoo_woocommerce_after_cart', 5 );

/**
 * Change Cross Sell product template location.
 * @return new location of cross sell products.
 * */
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_after_cart_table', 'woocommerce_cross_sell_display', 5 );

/**
 * Remove default return template of function woocommerce_shipping_calculator().
 * Change that function by hook zoo_shipping_calculator() for move shipping shipping-calculator template to outside table.
 * @uses override function
 * @return  false
 */
function woocommerce_shipping_calculator() {
	return false;
}

if ( ! function_exists( 'zoo_shipping_calculator' ) ) {
	function zoo_shipping_calculator() {
		if ( 'no' === get_option( 'woocommerce_enable_shipping_calc' ) || ! WC()->cart->needs_shipping() ) {
			return;
		}
		wp_enqueue_script( 'wc-country-select' );
		wc_get_template( 'cart/shipping-calculator.php' );
	}
}
add_action( 'zoo_shipping_calculator', 'zoo_shipping_calculator', 5 );

/**
 * Add button Login to Register form
 * @return button Register if that feature is active.
 * */
function zoo_login_button() {
	$allow_html = array(
		'p' => array( 'class' => array() ),
		'a' => array( 'class' => array(), 'href' => array(), 'title' => array() )
	);
	echo wp_kses( '<p class="form-row  wrap-login-toggle-button"><a href=# class="toggle-login-form button toggle-button" title="' . esc_attr__( 'Login', 'anon' ) . '">' . esc_html__( 'Login', 'anon' ) . '</a></p>', $allow_html );
}

add_action( 'woocommerce_register_form_end', 'zoo_login_button', 5 );
/**
 * Add button register to login form
 * @return button Register if that feature is active.
 * */
function zoo_register_button() {
	$allow_html = array(
		'p' => array( 'class' => array() ),
		'a' => array( 'class' => array(), 'href' => array(), 'title' => array() )
	);
	if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' && ! is_checkout() ) {
		echo wp_kses( '<p class="form-row wrap-register-toggle-button"><a href=# class="toggle-register-form button toggle-button" title="' . esc_attr__( 'Register', 'anon' ) . '">' . esc_html__( 'Register', 'anon' ) . '</a></p>', $allow_html );
	} else {
		return;
	}
}

add_action( 'woocommerce_login_form_end', 'zoo_register_button', 5 );

if ( class_exists( 'YITH_WC_Social_Login_Frontend' ) ) {
	remove_action( 'woocommerce_after_template_part', array(
		YITH_WC_Social_Login_Frontend::get_instance(),
		'social_buttons_in_checkout'
	) );
}

if(!function_exists('zoo_woo_cart_trust_badges')){
    function zoo_woo_cart_trust_badges(){
        $trust_badges=get_theme_mod('zoo_cart_trust_badges');
        if(isset($trust_badges['url'])){
            echo sprintf('<img src="%s" class="trust-badges" alt="%s"/>',$trust_badges['url'], esc_attr__('Trust badges','anon'));
        }else{
            return;
        }
    }
}
add_action('woocommerce_cart_collaterals','zoo_woo_cart_trust_badges',20);
add_action('woocommerce_review_order_after_submit','zoo_woo_cart_trust_badges',10);