<?php
/**
 * View of Product filter by price
 */
$filter_id = mt_rand();
if(wp_is_mobile()){
    wp_enqueue_script('touch-punch');
}
?>
<div id="cln-filter-item-<?php echo esc_attr($filter_id) ?>" class="zoo_ln_price zoo-filter-block zoo-filter-by-price slider-price">
    <h4 class="zoo-title-filter-block"><?php echo esc_html($content_data['title']); ?></h4>
    <?php
    $price_min = 0;
    $price_max = \Zoo\Frontend\Hook\get_max_price();

    if (isset($selected_filter_option['price']) && isset($selected_filter_option['price']['min_price'])) $price_from = $selected_filter_option['price']['min_price'];
    else $price_from = '';
    if (isset($selected_filter_option['price']) && isset($selected_filter_option['price']['max_price'])) $price_to = $selected_filter_option['price']['max_price'];
    else $price_to = ''; ?>
    <div class="zoo-ln-slider-range"></div>
    <input type="hidden" class="amount"  style="border:0;" value="">
    <input class="price-from" name="price[min_price]" value="<?php echo $price_from; ?>" type="hidden">
    <input class="price-to" name="price[max_price]" value="<?php echo $price_to; ?>" type="hidden">
    <input class="price-min" value="<?php echo($price_min); ?>" type="hidden">
    <input class="price-max" value="<?php echo($price_max); ?>" type="hidden">
    <div class="zoo-slider-price-amount"><?php echo esc_html__('Price:', 'clever-layered-navigation') ?>
        <span class="zoo-price-form">
                    <?php
                    if ($price_from == '') {
                        $price_from = $price_min;
                    }
                    \Zoo\Frontend\Hook\render_price($price_from);
                    ?>
            </span>-
        <span class="zoo-price-to">
             <?php
             if ($price_to == '') {
                 $price_to = $price_max;
             }
             \Zoo\Frontend\Hook\render_price($price_to);
             ?>
            </span>
    </div>
</div>
