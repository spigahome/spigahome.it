<?php
/**
 * View template for Clever Product Carousel with Category Tabs.
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */

$product_ids = '';
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
    'product_cat'=> $settings['default_category'] != '' ? $settings['default_category'] : implode(',', $settings['filter_categories']),
    'posts_per_page' => $settings['posts_per_page'],
    'paged' => $paged,
    'orderby' => $settings['orderby'],
    'order' => $settings['order'],
    'post__not_in'=> $product_ids,
);
switch ($settings['asset_type']) {
    case 'featured':
    $meta_query[] = array(
        array(
            'taxonomy' => 'product_visibility',
            'field'    => 'name',
            'terms'    => 'featured',
            'operator' => 'IN'
        ),
    );
    $wc_attr['tax_query'] = $meta_query;
    break;
    case 'onsale':
    $product_ids_on_sale = wc_get_product_ids_on_sale();
    $wc_attr['post__in'] = $product_ids_on_sale;
    break;
    case 'best-selling':
    $wc_attr['meta_key'] = 'total_sales';
    $wc_attr['orderby']  = 'meta_value_num';
    break;
    case 'latest':
    $wc_attr['orderby'] = 'date';
    break;
    case 'toprate':
    $wc_attr['orderby'] = 'meta_value_num';
    $wc_attr['meta_key'] = '_wc_average_rating';
    $wc_attr['order'] = 'DESC';
    break;
    case 'deal':
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
    break;
    default:
    break;
}
$settings['wc_attr'] = $wc_attr; 

$cafe_wrap_class        = "woocommerce cafe-products-wrap append-class";
$class                  = 'grid-layout carousel';
$class                  .= ' ' . $settings['nav_position'];
$cafe_wrap_class        .= ' cafe-carousel';
$cafe_wrap_class .= '  cafe-grid-lg-' . $settings['slides_to_show']['size'] . '-cols cafe-grid-md-' . $settings['slides_to_show_tablet']['size'] . '-cols cafe-grid-' . $settings['slides_to_show_mobile']['size'] . '-cols';
$cafe_json_config       = '';
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

if(function_exists('zoo_product_hover_effect')) {
	$class .= ' hover-effect-' . zoo_product_hover_effect();
}

$product_query = new WP_Query($settings['wc_attr']);

$filter_arr = array(

    'filter_categories'     => $settings['filter_categories'],
    'asset_type'            => $settings['asset_type'],
    'product_ids'           => $settings['product_ids'],
    'orderby'               => $settings['orderby'],
    'order'                 => $settings['order'],
    'posts_per_page'        => $settings['posts_per_page'],
);

?>
<div class="<?php echo esc_attr($cafe_wrap_class) ?> " 
    data-args='<?php echo json_encode($filter_arr); ?>'
    data-cafe-config="<?php echo  esc_attr($cafe_json_config);?>"
    data-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
    <div class="cafe-head-product-filter has-tabs <?php echo esc_attr($settings['title'] ? 'has-border' : '');?>">
        <?php if (isset($settings['title']) && $settings['title'] != '') : 
            printf('<h3 %s>%s</h3>',$this->get_render_attribute_string('title'), $settings['title']); 
        endif; ?>
        <ul class="cafe-ajax-load filter-cate">
            <?php
            if($settings['default_category'] && isset(get_term_by('slug',$settings['default_category'], 'product_cat')->name)){
                echo '<li><a href="'.get_term_link($settings['default_category'], 'product_cat').'" class="active" data-type="product_cat" data-value="'.$settings['default_category'].'" >' . get_term_by('slug',$settings['default_category'], 'product_cat')->name . '</a></li>';
            }
            if($settings['filter_categories']){
                foreach ($settings['filter_categories'] as $product_cat_slug) {
                    $product_cat = get_term_by('slug', $product_cat_slug, 'product_cat');
                    $selected = '';
                    if(isset($product_cat->slug)){
                        if (isset($settings['wc_attr']['product_cat']) && $settings['wc_attr']['product_cat'] == $product_cat->slug) {
                            $selected = 'cafe-selected';
                        }
                        echo '<li><a class="' . esc_attr($selected) . '" 
                        data-type="product_cat" data-value="' . esc_attr($product_cat->slug) . '" 
                        href="' . esc_url(get_term_link($product_cat->slug, 'product_cat')) . '" 
                        title="' . esc_attr($product_cat->name) . '">' . esc_html($product_cat->name) . '</a></li>';
                    }
                    
                } 
            }

            ?>
        </ul>

    </div>
    <ul class="products <?php echo esc_attr($class) ?>">
        <?php 
        while ($product_query->have_posts()) : $product_query->the_post();
            wc_get_template_part( 'content', 'product' );
        endwhile;
        ?>
    </ul>
</div>
<?php
wp_reset_postdata();
?>