<?php
/**
 * View template for Clever List Tags
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */

$cafe_wrap_class         = "woocommerce cafe-products-wrap";
$class                  = 'grid-layout carousel';
$class                  .= ' ' . $settings['nav_position'];
$cafe_wrap_class         .= ' cafe-carousel';
$cafe_json_config = '';
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

?>
<div class="<?php echo esc_attr($cafe_wrap_class) ?> " data-cafe-config='<?php echo esc_attr($cafe_json_config) ?>'>
    
    <div class="cafe-head-product-filter <?php echo esc_attr($settings['title'] ? 'has-border' : '');?>">
        <?php if (isset($settings['title']) && $settings['title'] != '') : 
            printf('<h3 %s>%s</h3>',$this->get_render_attribute_string('title'), $settings['title']); 
        endif; ?>
    </div>
    
    <ul class="products <?php echo esc_attr($class) ?>">
        <?php 
        if($settings['filter_tags']){
            foreach ($settings['filter_tags'] as $tag) {
                $tags = get_term_by('slug', $tag, 'product_tag');
                if(isset($tags->term_id)){
                    echo '<li class="product tag-item">';
                    echo '<a href="'.esc_url(get_tag_link($tags->term_id)).'" title="'.esc_attr($tags->name).'">';
                    echo  esc_attr($tags->name);
                    echo '</a></li>';
                }
            }
        }
        
        ?>
    </ul>
</div>
<?php
wp_reset_postdata();
?>