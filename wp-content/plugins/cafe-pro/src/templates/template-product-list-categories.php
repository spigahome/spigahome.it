<?php
/**
 * View template for Clever Product List Categories
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */

$cafe_wrap_class    = 'cafe-product-categories-list cafe-wrap';
$class              = $settings['children_cate'] .' '.$settings['image_position'];
$grid_class         = '';
if($settings['layout']=='grid') {
    $grid_class = '  cafe-grid-lg-' . $settings['columns']['size'] . '-cols cafe-grid-md-' . $settings['columns_tablet']['size'] . '-cols cafe-grid-' . $settings['columns_mobile']['size'] .'-cols';
}
$cafe_json_config='';
if($settings['layout']=='carousel') {
    $class    .= ' grid-layout carousel';
    $cafe_wrap_class .= ' cafe-carousel';
    $cafe_wrap_class .= ' ' . $settings['nav_position'];
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

        "wrap": "ul.cafe-row"
    }';
}
$cafe_wrap_class .= $grid_class;
?>
<div class="<?php echo esc_attr($cafe_wrap_class) ?> " data-cafe-config='<?php echo esc_attr($cafe_json_config) ?>'>
    <div class="cafe-head-product-filter <?php echo esc_attr($settings['title'] ? 'has-border' : '');?>">
        <?php if (isset($settings['title']) && $settings['title'] != '') : 
            printf('<h3 %s>%s</h3>',$this->get_render_attribute_string('title'), $settings['title']); 
        endif; ?>
    </div>
    <ul class="product-categories cafe-row <?php echo esc_attr($class) ?>">
        <?php 
        if($settings['feature']=='multi'){
            if($settings['children_cate'] == 'sub_cate'){
                foreach ($settings['filter_parent_categories'] as $cat) {
                    $cat_opp = get_term_by('slug', $cat, 'product_cat');
                    if(isset($cat_opp->term_id)) {
                        $children = get_term_children($cat_opp->term_id,'product_cat');
                        if($cat_opp->term_id){
                            echo '<li class="cafe-col category wrap-category-item"><div class="wrap-content-category-item"><div class="category-image">';
                            $thumbnail_id = get_term_meta( $cat_opp->term_id, 'thumbnail_id', true );
                            if($thumbnail_id){ 
                                echo wp_get_attachment_image($thumbnail_id, 'woocommerce_thumbnail');
                            }
                            $cat_name=get_the_category_by_ID($cat_opp->term_id);
                            $cat_url=get_category_link($cat_opp->term_id);
                            echo '</div><div class="category-content"><h3 class="product-category-heading">';
                            echo '<a href="'.esc_url($cat_url).'" title="'.esc_attr($cat_name).'">';
                            echo esc_attr($cat_name);
                            echo '</a>';
                            echo '</h3>';
                            $i=0;
                            foreach ($children as $key => $child) {
                                echo '<p class="category-item">';
                                echo '<a href="'.esc_url(get_category_link($child)).'" title="'.esc_attr(get_the_category_by_ID($child)).'">';
                                echo esc_attr(get_the_category_by_ID($child));
                                echo '</a>';
                                echo '</p>';
                                $i++;
                                if($i==(int)$settings['max_sub_cat']){
                                    break;
                                }
                            }
                            if($settings['show_view_more']){
                                ?>
                                <a href="<?php echo esc_url($cat_url);?>" class="view-more" title="<?php echo esc_attr($cat_name)?>">   <?php echo esc_attr($settings['show_view_more_text'])?> 
                                <i class="cs-font clever-icon-arrow-right"></i> 
                            </a>
                            <?php
                            }
                        echo '</div></div></li>';
                        }
                    }
                }
            }
            else{
                foreach ($settings['filter_categories'] as $cat) {
                    $cat_opp = get_term_by('slug', $cat, 'product_cat');
                    if(isset($cat_opp->term_id)){
                        echo '<li class="cafe-col category wrap-category-item"><div class="wrap-content-category-item"><div class="category-image">';
                        $thumbnail_id = get_term_meta( $cat_opp->term_id, 'thumbnail_id', true );
                        if($thumbnail_id){
                            echo wp_get_attachment_image($thumbnail_id, 'woocommerce_thumbnail');
                        }

                        echo '</div><div class="category-content wrap-product-loop-detail"><h3 class="product-category-heading">';
                        echo '<a href="'.esc_url(get_category_link($cat_opp->term_id)).'" title="'.esc_attr(get_the_category_by_ID($cat_opp->term_id)).'">';
                        echo esc_attr(get_the_category_by_ID($cat_opp->term_id));
                        echo '</a>';
                        echo '</h3>';
                        echo '</div></div></li>';
                    }

                }
            }
        }
        if($settings['feature']=='repeat'){
            if($settings['children_cate'] == 'sub_cate'){
                foreach ($settings['repeater'] as $value) {
                    $cat = $value['category'];
                    $cat_opp = get_term_by('slug', $cat, 'product_cat');
                    if(isset($cat_opp->term_id)) {
                        $children = get_term_children($cat_opp->term_id,'product_cat');
                        if($cat_opp->term_id){
                            echo '<li class="cafe-col category wrap-category-item"><div class="wrap-content-category-item"><div class="category-image">';
                            $thumbnail_id = $value['image']['id'];
                            if($thumbnail_id){ 
                                echo wp_get_attachment_image($thumbnail_id, 'woocommerce_thumbnail');
                            }
                            $cat_name=get_the_category_by_ID($cat_opp->term_id);
                            $cat_url=get_category_link($cat_opp->term_id);
                            echo '</div><div class="category-content"><h3 class="product-category-heading">';
                            echo '<a href="'.esc_url($cat_url).'" title="'.esc_attr($cat_name).'">';
                            echo esc_attr($cat_name);
                            echo '</a>';
                            echo '</h3>';
                            $i=0;
                            foreach ($children as $key => $child) {
                                echo '<p class="category-item">';
                                echo '<a href="'.esc_url(get_category_link($child)).'" title="'.esc_attr(get_the_category_by_ID($child)).'">';
                                echo esc_attr(get_the_category_by_ID($child));
                                echo '</a>';
                                echo '</p>';
                                $i++;
                                if($i==(int)$settings['max_sub_cat']){
                                    break;
                                }
                            }
                            if($settings['show_view_more']){
                                ?>
                                <a href="<?php echo esc_url($cat_url);?>" class="view-more" title="<?php echo esc_attr($cat_name)?>">   <?php echo esc_attr($settings['show_view_more_text'])?> 
                                <i class="cs-font clever-icon-arrow-right"></i> 
                            </a>
                            <?php
                            }
                        echo '</div></div></li>';
                        }
                    }
                }
            }
            else{
                foreach ($settings['repeater'] as $value) {
                    $cat = $value['category'];
                    $cat_opp = get_term_by('slug', $cat, 'product_cat');
                    if(isset($cat_opp->term_id)){
                        echo '<li class="cafe-col category wrap-category-item"><div class="wrap-content-category-item"><div class="category-image">';
                        $thumbnail_id = $value['image']['id'];
                        if($thumbnail_id){
                            echo wp_get_attachment_image($thumbnail_id, 'woocommerce_thumbnail');
                        }

                        echo '</div><div class="category-content wrap-product-loop-detail"><h3 class="product-category-heading">';
                        echo '<a href="'.esc_url(get_category_link($cat_opp->term_id)).'" title="'.esc_attr(get_the_category_by_ID($cat_opp->term_id)).'">';
                        echo esc_attr(get_the_category_by_ID($cat_opp->term_id));
                        echo '</a>';
                        echo '</h3>';
                        echo '</div></div></li>';
                    }

                }
            }
        }
    ?>

    </ul>
</div>
<?php
wp_reset_postdata();
?>