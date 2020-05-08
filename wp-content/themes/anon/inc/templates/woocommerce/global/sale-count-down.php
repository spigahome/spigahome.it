<?php
/**
 * Sale Count down template
 * Display count down end sale
 * @package     Zoo Theme
 * @version     3.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $post, $product;

if ($product->is_on_sale()) :
$zoo_date_sale = get_post_meta( get_the_ID(), '_sale_price_dates_to', true );
if($zoo_date_sale > time()) {
    wp_enqueue_script('countdown');
    ?>
    <div class="zoo-countdown">
        <?php
        if(is_single()){
            ?>
            <span class="label-product-countdown">
                <?php esc_html_e('Hurry Up! Sales end in:','anon');?>
            </span>
            <?php
        }
        ?>
        <div class="countdown-block" data-countdown="countdown"
             data-date="<?php echo esc_attr(date('m', $zoo_date_sale) . '-' . date('d', $zoo_date_sale) . '-' . date('Y', $zoo_date_sale) . '-' . date('H', $zoo_date_sale) . '-' . date('i', $zoo_date_sale) . '-' . date('s', $zoo_date_sale)); ?>">
        </div>
    </div>
	<?php
	if(is_single() && get_theme_mod('zoo_product_stock_countdown',1)==1){
		$zoo_base_stock = get_post_meta( get_the_ID(), '_zoo_number_product_on_event', true );
		$stock = $product->get_stock_quantity();
		if($zoo_base_stock!=0 && $stock!=NULL) {
		    $stock_parse='';
		    $loading_percent=($stock/$zoo_base_stock)*100;
			if ($loading_percent < 40) {
				$stock_parse = 'final-parse';
			} elseif ($loading_percent >= 40 && $loading_percent < 80) {
				$stock_parse = 'second-parse';
			} else {
				$stock_parse = 'first-parse';
			}
			?>
            <div class="zoo-stock-countdown">
                <span class="label-product-stock-countdown">
                    <?php printf( esc_html__( 'Hurry! Only ', 'anon').'<b class="number-product">%s</b>'.esc_html__(' left in stock:', 'anon' ),$stock); ?>
                </span>
                <div class="stock-countdown-bar">
                    <span class="inner-stock-countdown-bar <?php echo esc_attr($stock_parse) ?>" style="width:<?php echo esc_attr($loading_percent)?>%"></span>
                </div>
            </div>
			<?php
		}
	}
	?>
    <?php
}
endif;