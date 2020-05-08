<?php
/**
 * View template for Clever Product Deal.
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */
$product_ids = $html_action = $target = $nofollow = $date_time = $html_time = '';

if($settings['product_ids'] && is_array($settings['product_ids'])){
    $product_ids = $settings['product_ids'];
}

if ( is_front_page() ) {
    $paged = (get_query_var('page')) ? get_query_var('page') : 1;   
} else {
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; 
}
$meta_query = WC()->query->get_meta_query();
$wc_attr = array(
    'post_type' => 'product',
    'product_cat'=> $settings['filter_categories'] != '' ? implode(',', $settings['filter_categories']) : '',
    'posts_per_page' => $settings['posts_per_page'],
    'paged' => $paged,
    'orderby' => $settings['orderby'],
    'order' => $settings['order'],
    'post__not_in'=> $product_ids,
);
$product_ids_on_sale = wc_get_product_ids_on_sale();
$wc_attr['post__in'] = $product_ids_on_sale;
$wc_attr['meta_query'] = array(
    'relation' => 'AND',
    array(
        'key' => '_sale_price_dates_to',
        'value' => time(),
        'compare' => '>'
    )
);
$settings['wc_attr'] = $wc_attr; 

$cafe_wrap_class         = "woocommerce cafe-products-wrap ";
$cafe_wrap_class         .= $settings['layout_style'];
$class                  = 'grid-layout carousel';
$class                  .= ' ' . $settings['nav_position'];
$cafe_wrap_class         .= ' cafe-carousel';
$cafe_wrap_class .= '  cafe-grid-lg-' . $settings['slides_to_show']['size'] . '-cols cafe-grid-md-' . $settings['slides_to_show_tablet']['size'] . '-cols cafe-grid-' . $settings['slides_to_show_mobile']['size'] . '-cols';
$settings['autoplay'] ? $settings['autoplay'] : $settings['autoplay'] = 'false';
$settings['autoplay_tablet'] ? $settings['autoplay_tablet'] : $settings['autoplay_tablet'] = 'false';
$settings['autoplay_mobile'] ? $settings['autoplay_mobile'] : $settings['autoplay_mobile'] = 'false';

$settings['show_pag'] ? $settings['show_pag'] : $settings['show_pag'] = 'false';
$settings['show_pag_tablet'] ? $settings['show_pag_tablet'] : $settings['show_pag_tablet'] = 'false';
$settings['show_pag_mobile'] ? $settings['show_pag_mobile'] : $settings['show_pag_mobile'] = 'false';

$settings['show_nav'] ? $settings['show_nav'] : $settings['show_nav'] = 'false';
$settings['show_nav_tablet'] ? $settings['show_nav_tablet'] : $settings['show_nav_tablet'] = 'false';
$settings['show_nav_mobile'] ? $settings['show_nav_mobile'] : $settings['show_nav_mobile'] = 'false';
$settings['speed']?$settings['speed']:$settings['speed']=3000;
$cafe_json_config = '{
    "slides_to_show" : ' . $settings['slides_to_show']['size'] . ',
    "slides_to_show_tablet" : ' . $settings['slides_to_show_tablet']['size'] . ',
    "slides_to_show_mobile" : ' . $settings['slides_to_show_mobile']['size'] . ',

    "speed": ' . $settings['speed'] . ',
    "scroll": ' . $settings['scroll'] . ',

    "autoplay": ' . $settings['autoplay'] . ',
    "autoplay_tablet": ' . $settings['autoplay_tablet'] . ',
    "autoplay_mobile": ' . $settings['autoplay_mobile'] . ',

    "show_pag": ' . $settings['show_pag'] . ',
    "show_pag_tablet": ' . $settings['show_pag_tablet'] . ',
    "show_pag_mobile": ' . $settings['show_pag_mobile'] . ',

    "show_nav": ' . $settings['show_nav'] . ',
    "show_nav_tablet": ' . $settings['show_nav_tablet'] . ',
    "show_nav_mobile": ' . $settings['show_nav_mobile'] . ',

    "wrap": "ul.products"
}';

$grid_class = '  cafe-grid-lg-' . $settings['slides_to_show']['size'] . '-cols cafe-grid-md-' . $settings['slides_to_show_tablet']['size'] . '-cols cafe-grid-' . $settings['slides_to_show_mobile']['size'] .'-cols';
    $cafe_wrap_class .= $grid_class;

$product_query = new WP_Query($settings['wc_attr']);

if (isset($settings['action_link']['url']) && $settings['action_link']['url'] && $settings['action_title']) : 
    if(isset($settings['action_link']['is_external']) && $settings['action_link']['is_external']){
        $target = 'target="_blank"';
    }
    if(isset($settings['action_link']['nofollow']) && $settings['action_link']['nofollow']){
        $nofollow = 'rel="nofollow"';
    }
    $html_action .= '<div class="cafe-deal-action-link">';
    $html_action .= '<a class="action-link" href="'.$settings['action_link']['url'].'" ' .$target.' '.$nofollow.' >';
    $html_action .= $settings['action_title'];
    $html_action .= '</a>';
    $html_action .= '</div>';
    
endif; 

?>
<div class="<?php echo esc_attr($cafe_wrap_class) ?> " data-cafe-config='<?php echo esc_attr($cafe_json_config) ?>'>
    
    <div class="cafe-head-product-filter <?php echo esc_attr($settings['title'] ? 'has-border' : '');?>">
        <?php if (isset($settings['title']) && $settings['title']) : 
            printf('<h3 %s>%s</h3>',$this->get_render_attribute_string('title'), $settings['title']); 
        endif; ?>
        <?php if($settings['layout_style'] != 'default') :
            add_action('woocommerce_after_shop_loop_item_title','zoo_sold_bar', 15);
            remove_action('woocommerce_before_shop_loop_item_title', 'zoo_loop_sale_countdown', 10);
            if($settings['layout_style'] == 'layout-1'){
                $date_time = strtotime($settings['date_time']);
            }
            if($settings['layout_style'] == 'layout-2'){
                add_action('woocommerce_after_shop_loop_item', 'zoo_loop_sale_countdown', 50);
                $date_time = strtotime($settings['date_time_2']);
            }
        $html_time = date('m', $date_time) . '-' . date('d', $date_time) . '-' . date('Y', $date_time) . '-' . date('H', $date_time) . '-' . date('i', $date_time) . '-' . date('s', $date_time);
        ?>
        <?php if($date_time) : ?>
        <div class="cafe-countdown-block">
            <i class="cs-font clever-icon-clock-3"></i>
            <div class="countdown-block" data-countdown="countdown"
                data-date="<?php echo ent2ncr($html_time); ?>">
            </div>
        </div>
        <?php endif; endif; ?>
        <?php echo ent2ncr($html_action); ?>
    </div>
    <ul class="products <?php echo esc_attr($class) ?>">
        <?php 
        while ($product_query->have_posts()) : $product_query->the_post();
            wc_get_template_part( 'content', 'product' );
        endwhile;
        ?>
    </ul>
    <?php
    remove_action('woocommerce_after_shop_loop_item_title','zoo_sold_bar', 15);
    remove_action('woocommerce_after_shop_loop_item', 'zoo_loop_sale_countdown', 50);
    ?>
</div>
<?php
wp_reset_postdata();
?>