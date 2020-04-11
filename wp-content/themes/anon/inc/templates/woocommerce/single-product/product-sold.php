<?php
/**
 * Product sold inside 24h
 * @package     Zoo Theme
 * @version     3.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 *
 */

if ( class_exists( 'CleverAddons' ) ):
    global $product;
    $availability = $product->get_availability();
    if ( $availability['class'] != 'out-of-stock' ) {
        $fake_data     = intval( get_theme_mod( 'zoo_product_sold_fake_data', 10 ) );
        $fake_max_data = intval( get_theme_mod( 'zoo_product_sold_fake_max_data', 50 ) );
        if ( $fake_max_data > $fake_data ) {
            $sold = rand( $fake_data, $fake_max_data );
            ?>
            <div class="zoo-product-sold-day">
                <i class="cs-font clever-icon-fire">
                </i>
                <span class="total-product-sold"><?php echo esc_attr( $sold ); ?></span>
                <?php
                echo esc_html__( 'sold in last', 'anon' );
                ?>
                <span class="current-hours"> <?php print date( 'H' );
                    echo esc_html__( ' hours', 'anon' ) ?></span>
            </div>
            <?php
        }
    }
endif;