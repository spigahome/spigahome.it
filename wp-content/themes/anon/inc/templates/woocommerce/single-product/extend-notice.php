<?php
/**
 * Product extend notice template
 * @package     Zoo Theme
 * @version     3.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 *
 */
if(class_exists('CleverAddons')):
    ?>
    <ul class="zoo-extend-notice">
        <?php
        //Get order time notice
        if(get_theme_mod('zoo_product_get_order',1)==1){
            global $product;
            $availability = $product->get_availability();
            if ( $availability['class'] != 'out-of-stock' ) {
                $order_day = get_theme_mod('zoo_product_get_order_day', 3);
                $getOrderDate = Date(get_option('date_format'), strtotime('+' . $order_day . " days"));
                ?>
                <li class="zoo-extend-notice-item zoo-get-order-notice">
                    <i class="cs-font clever-icon-clock-2"></i>
                    <span class="extend-notice-label">
                <?php
                echo esc_html__('Order in The Next', 'anon');
                ?>
                        <span class="end-of-day" data-timezone="<?php echo get_option('gmt_offset');  ?>" data-hours="<?php esc_attr_e('hours', 'anon') ?>"
                              data-minutes="<?php esc_attr_e('minutes', 'anon') ?>">
                </span>
                    <?php
                    echo esc_html__(' to get it by', 'anon');
                    ?>
                    <span class="get-order-date">
                    <?php echo esc_html($getOrderDate); ?>
                </span>
                    </span>
                </li>
                <?php
            }
        }
        //Visitor
        if(get_theme_mod('zoo_product_get_visitor',1)==1 && class_exists('CleverOnePixel')){
            ?>
            <li class="zoo-extend-notice-item zoo-count-visitor-notice">
                <i class="cs-font clever-icon-team"></i>
                <span class="extend-notice-label">
                <?php
                esc_html_e('Real Time ','anon');
                echo CleverOnePixel::countVisitors();
                esc_html_e(' Visitor Right Now','anon');
                ?>
            </span>
            </li>
            <?php
        }
        //Free shipping amount notice
        if ( get_theme_mod( 'zoo_product_free_shipping_notice', '1' ) == 1 ) {
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
                    $current = WC()->cart->subtotal;
                    $min_amount = min( $min_amounts );
                    ?>
                    <li class="zoo-extend-notice-item zoo-free-shipping-notice-amount">
                        <i class="cs-font clever-icon-truck"></i>
                        <span class="extend-notice-label">
                    <?php
                    if ($current < $min_amount) {
                        echo esc_html__( 'Spend ', 'anon' ) . '<span class="free-shipping-amount">' . wc_price( $min_amount - $current ) . '</span>' . esc_html__( ' to get Free Shipping', 'anon' );
                    }else {
                        esc_html_e('Congratulations! You\'ve got free shipping!', 'anon');
                    }
                    ?>
                    </span>
                    </li>
                <?php }
            }
        }
        if(get_theme_mod('zoo_product_guarantee_safe_checkout','')!=''){
            ?>
            <li class="zoo-extend-notice-item zoo-guarantee-safe-checkout-logo">
        <span class="heading-guarantee-safe-checkout-logo"><?php
            esc_html_e('Guarantee safe checkout','anon');
            ?></span>
                <div class="wrap-logo-payments">
                    <img src="<?php echo esc_url(get_theme_mod('zoo_product_guarantee_safe_checkout','')['url']);?>" alt="<?php
                    esc_attr_e('Guarantee safe checkout','anon');
                    ?>"/>
                </div>
            </li>
            <?php
        }
        ?>
    </ul>
<?php endif;?>