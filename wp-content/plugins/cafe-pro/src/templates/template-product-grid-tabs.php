<?php
/**
 * View template for Clever Product Grid Tabs.
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */

$product_ids = $default_asset = '';

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
    'posts_per_page' => $settings['posts_per_page'],
    'paged' => $paged,
    'orderby' => $settings['orderby'],
    'order' => $settings['order'],
    'post__not_in'=> $product_ids,
);

if($settings['default_category'] != '' && $settings['default_category'] != 'all'){
    $wc_attr['product_cat'] = $settings['default_category'];
}
else{
    if($settings['filter_categories']){
        $wc_attr['product_cat'] = implode(',', $settings['filter_categories']);
    }
}
$default_asset = $settings['asset_type'];


switch ($default_asset) {
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

$cafe_wrap_class         = "woocommerce cafe-products-wrap append-class";
$class                  = 'grid-layout';
$grid_class = '  cafe-grid-lg-' . $settings['columns']['size'] . '-cols cafe-grid-md-' . $settings['columns_tablet']['size'] . '-cols cafe-grid-' . $settings['columns_mobile']['size'] .'-cols';
        $cafe_wrap_class .= $grid_class;

if(function_exists('zoo_product_hover_effect')) {
	$class .= ' hover-effect-' . zoo_product_hover_effect();
}

$product_query = new WP_Query($settings['wc_attr']);

$filter_arr = array(

    'asset_type'            => $settings['asset_type'],
    'product_ids'           => $settings['product_ids'],
    'orderby'               => $settings['orderby'],
    'order'                 => $settings['order'],
    'posts_per_page'        => $settings['posts_per_page'],
);

?>
<div class="<?php echo esc_attr($cafe_wrap_class) ?> " 
    data-args='<?php echo json_encode($filter_arr); ?>'
    data-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
    <div class="cafe-head-product-filter has-tabs <?php echo esc_attr($settings['title'] ? 'has-border' : '');?>">
        <?php if (isset($settings['title']) && $settings['title'] != '') : 
            printf('<h3 %s>%s</h3>',$this->get_render_attribute_string('title'), $settings['title']); 
        endif; ?>
        <?php if($settings['cate_asset'] == 'cate') { ?>
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
                        echo '<li><a href="' . esc_url(get_term_link($product_cat->slug, 'product_cat')) . '"
                            class="' . esc_attr($selected) . '" 
                            data-type="product_cat" data-value="' . esc_attr($product_cat->slug) . '" 
                            title="' . esc_attr($product_cat->name) . '">' . esc_html($product_cat->name) . '</a></li>';
                    }
                    
                } 
            }

            ?>
        </ul>
        <?php } ?>
        <?php if($settings['cate_asset'] == 'asset') { ?>
        <ul class="cafe-ajax-load filter-asset">
            <?php
            $asset_title = '';
            switch ($settings['default_asset']) {
                case 'featured':
                    $asset_title =  esc_html__('Featured','cafe-pro');
                    break;
                case 'onsale':
                    $asset_title =  esc_html__('On Sale','cafe-pro');
                    break;
                case 'deal':
                    $asset_title =  esc_html__('Deal','cafe-pro');
                    break;
                case 'latest':
                    $asset_title =  esc_html__('New Arrivals','cafe-pro');
                    break;
                case 'best-selling':
                    $asset_title =  esc_html__('Best Seller','cafe-pro');
                    break;
                case 'toprate':
                    $asset_title =  esc_html__('Top Rate','cafe-pro');
                    break;
                default:
                    break;
            } 
            if($asset_title) { ?>
                <li class="cvca-ajax-load">
                    <a href="#" class="active" data-type="asset_type" data-value="<?php echo esc_attr($settings['default_asset']) ?>" title="<?php echo esc_attr($asset_title); ?>"><?php echo esc_attr($asset_title); ?></a>
                </li>
            
            <?php 
            } 
        } 
        $html = '';
        if($settings['filter_assets']){

            foreach ($settings['filter_assets'] as $val) {
                switch ($val) {
                    case 'featured':
                        $html .= $settings['default_asset'] != 'featured'? '<li><a href="#" data-type="asset_type" data-value="featured" title="'.esc_html__('Featured','cafe-pro').'">'.esc_html__('Featured','cafe-pro').'</a></li>' : '';
                        break;
                    case 'onsale':
                        $html .= $settings['default_asset'] != 'onsale'? '<li><a href="#" data-type="asset_type" data-value="onsale" title="'.esc_html__('On Sale','cafe-pro').'">'.esc_html__('On Sale','cafe-pro').'</a></li>' : '';
                        break;
                    case 'deal':
                        $html .= $settings['default_asset'] != 'deal'? '<li><a href="#" data-type="asset_type" data-value="deal" title="'.esc_html__('Deal','cafe-pro').'">'.esc_html__('Deal','cafe-pro').'</a></li>' : '';
                        break;
                    case 'latest':
                        $html .= $settings['default_asset'] != 'latest'? '<li><a href="#" data-type="asset_type" data-value="latest" title="'.esc_html__('New Arrivals','cafe-pro').'">'.esc_html__('New Arrivals','cafe-pro').'</a></li>' : '';
                        break;
                    case 'best-selling':
                        $html .= $settings['default_asset'] != 'best-selling'? '<li><a href="#" data-type="asset_type" data-value="best-selling" title="'.esc_html__('Best Seller','cafe-pro').'">'.esc_html__('Best Seller','cafe-pro').'</a></li>' : '';
                        break;
                    case 'toprate':
                        $html .= $settings['default_asset'] != 'toprate'? '<li><a href="#" data-type="asset_type" data-value="toprate" title="'.esc_html__('Top Rate','cafe-pro').'">'.esc_html__('Top Rate','cafe-pro').'</a></li>' : '';
                        break;
                    default:
                        break;
                }
            } 
        } 

        echo ent2ncr($html); ?>

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