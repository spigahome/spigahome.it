<?php

/*
* Woocommerce helpers
*/

/*
* Function ajax filter
*/
if (!function_exists('cafe_ajax_product_filter')) {
    function cafe_ajax_product_filter()
    {
        $product_ids = $asset_type = $filter_categories = $posts_per_page = $orderby = $order = null;
        if (isset($_POST['product_ids'])) {
            $product_ids = sanitize_text_field(wp_unslash($_POST['product_ids']));
        }
        if (isset($_POST['asset_type'])) {
            $asset_type = sanitize_text_field($_POST['asset_type']);
        }
        if (isset($_POST['filter_categories'])) {
            $filter_categories = sanitize_text_field($_POST['filter_categories']);
        }
        if (isset($_POST['posts_per_page'])) {
            $posts_per_page = intval($_POST['posts_per_page']);
        }
        if (isset($_POST['orderby'])) {
            $orderby = sanitize_text_field($_POST['orderby']);
        }
        if (isset($_POST['order'])) {
            $order = strtoupper(sanitize_text_field($_POST['order']));
        }

        if (is_front_page()) {
            $paged = (get_query_var('page')) ? get_query_var('page') : 1;
        } else {
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        }

        $wc_attr = array(
            'post_type' => 'product',
            'product_cat' =>  $filter_categories,
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,
            'orderby' => $orderby,
            'order' => $order,
            'post__not_in'=> $product_ids,
        );


        if ($asset_type) {
            switch ($asset_type) {
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
        }


        if (isset($_POST['product_attribute']) && isset($_POST['attribute_value'])) {
            if (is_array($_POST['product_attribute'])) {
                foreach ($_POST['product_attribute'] as $key => $value) {
                    $tax_query[] = array(
                        'taxonomy' => $value,
                        'terms'    => array_map('sanitize_title', (array)$_POST['attribute_value'][$key]),
                        'field'    => 'slug',
                        'operator' => 'IN'
                    );
                }
            } else {
                $tax_query[] = array(
                    'taxonomy' => sanitize_title($_POST['product_attribute']),
                    'terms'    => array_map('sanitize_title', (array)$_POST['attribute_value']),
                    'field'    => 'slug',
                    'operator' => 'IN'
                );
            }
        }

        if (isset($_POST['product_tag'])) {
            $wc_attr['product_tag'] = sanitize_title($_POST['product_tag']);
        }

        if (isset($_POST['price_filter']) && $_POST['price_filter'] > 0) {
            $min = (intval($_POST['price_filter']) - 1)*intval($_POST['price_filter_range']);
            $max = intval($_POST['price_filter'])*intval($_POST['price_filter_range']);
            $meta_query[] = array(
                'key'     => '_price',
                'value'   => array($min, $max),
                'compare' => 'BETWEEN',
                'type'    => 'NUMERIC'
            );
        }

        if (isset($_POST['s']) && $_POST['s'] != '') {
            $wc_attr['s'] = esc_attr($_POST['s']);
        }

        $product_query = new WP_Query($wc_attr);
        ob_start(); ?>
        <ul class="products">
            <?php while ($product_query->have_posts()) {
            $product_query->the_post();
            wc_get_template_part('content', 'product');
        } ?>
        </ul>
        <?php
        $output = ob_get_contents();
        ob_end_clean();
        wp_reset_postdata();
        echo ent2ncr($output);
    }

    add_action('wp_ajax_cafe_ajax_product_filter', 'cafe_ajax_product_filter');
    add_action('wp_ajax_nopriv_cafe_ajax_product_filter', 'cafe_ajax_product_filter');
}

/*
* Get sold bar
*/

if (!function_exists('zoo_sold_bar')) {
    function zoo_sold_bar()
    {
        $result = array();
        $result['sold'] = (int)get_post_meta(get_the_ID(), 'total_sales', true);
        $result['stock'] = (int)get_post_meta(get_the_ID(), '_stock', true);
        if ($result['stock']) {
            $percent = $result['sold'] != 0 ? ($result['sold']/($result['sold'] + $result['stock']))*100 : 0;

            $parse_class = '';
            if ($percent < 40) {
                $parse_class = 'first-parse';
            } elseif ($percent >= 40 && $percent < 80) {
                $parse_class = 'second-parse';
            } else {
                $parse_class = 'final-parse';
            } ?>
        <div class="sold-bar <?php echo esc_attr($parse_class); ?>">
            <?php if ($result['sold'] != 0): ?>
            <h4 class="sold-label">
                <?php echo esc_html__('Only', 'cafe-lite'); ?>
                <span><?php echo esc_attr($result['stock']); ?></span>
                <?php echo esc_attr($result['stock']) > 1 ? esc_html__('items ', 'cafe-lite') : esc_html__('item ', 'cafe-lite'); ?>
                <?php echo esc_html__('left in stock!', 'cafe-lite'); ?>
            </h4>
            <?php else: ?>
            <h4 class="sold-label">
                <?php echo esc_html__('Buy', 'cafe-lite'); ?>
                <span><?php echo esc_attr($result['stock']); ?></span>
                <?php echo esc_attr($result['stock']) > 1 ? esc_html__('items ', 'cafe-lite') : esc_html__('item ', 'cafe-lite'); ?>
                <?php echo esc_html__(' in stock!', 'cafe-lite'); ?>
            </h4>
            <?php endif; ?>
            <div class="sold-percent">
                <span style="width:<?php echo esc_attr($percent); ?>%"></span>
            </div>
            <?php if (!is_product()): ?>
            <div class="sold-bar-count">
                <span><?php echo esc_html__('Sold: ', 'cafe-lite'); ?><label><?php  echo esc_attr($result['sold']) .'/'.esc_attr($result['stock'] + $result['sold']); ?></label></span>
            </div>
            <?php endif; ?>
        </div>
        <?php
        }
    }
}
