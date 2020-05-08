<?php
/**
 */

namespace Zoo\Frontend\Hook;

$filter_item_list = \Zoo\Helper\Data\get_filter_item_with_id(0);
foreach ($filter_item_list as $item) {
    $sac = $item['short_code'];
    add_shortcode($sac, 'Zoo\Frontend\Hook\add_zoo_ln_navigation');
}

/**
 * Hotfix for filtering products on store pages of Dokan multivendor plugin.
 */
add_action('pre_get_posts', function ($wp_query) {
    if (isset($_POST['zoo_ln_form_data']) && defined('DOING_AJAX') && DOING_AJAX) {
        parse_str($_POST['zoo_ln_form_data'], $post_option);
    } else {
        $post_option = $_GET;
    }

    if (empty($post_option['vendor_store_author_id']) || !$wp_query->is_main_query()) {
        return;
    }

    if (empty($post_option['cln_do_filter'])) {
        return $wp_query;
    }

    $selected_filter_option = get_activated_filter($post_option);
    $relation = !empty($selected_filter_option['relation']) ? $selected_filter_option['relation'] : 'AND';
    $tax_query_operator = ($relation === 'OR') ? 'IN' : 'AND'; // `tax_query` does not have `OR` operator.
    foreach ($selected_filter_option as $key => $option) {
        if ($key == 'in-stock' && $option == '1') {
            $wp_query->query_vars['meta_query'][] = array(
                'key' => '_stock_status',
                'value' => 'instock',
                'compare' => '=',
            );
        } elseif ($key == 'on-sale' && $option == '1') {
            $on_sale_products = wc_get_product_ids_on_sale();
            if ($on_sale_products) {
                $wp_query->set('post__in', array_unique($on_sale_products));
            }
        } elseif ($key == 'rating-from' && $option != '0') {
            $wp_query->query_vars['meta_query'][] = array(
                'key' => '_wc_average_rating',
                'value' => intval($option),
                'compare' => '>='
            );
        } elseif ($key == 'review-from' && $option != '0') {
            $wp_query->query_vars['meta_query'][] = array(
                'key' => '_wc_review_count',
                'value' => intval($option),
                'compare' => '>='
            );
        } elseif ($key == 'categories') {
            if (is_array($option)) {
                $wp_query->query_vars['tax_query'][] = array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $option,
                    'operator' => 'IN' // (count($option) > 1) ? $tax_query_operator : 'IN',
                );
            } elseif (is_string($option)) {
                $cats = explode(',', $option);
                $wp_query->query_vars['tax_query'][] = array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $cats,
                    'operator' => 'IN' // (count($cats) > 1) ? $tax_query_operator : 'IN',
                );
            }
        } elseif ($key == 'tags') {
            if (is_array($option)) {
                $wp_query->query_vars['tax_query'][] = array(
                    'taxonomy' => 'product_tag',
                    'field' => 'slug',
                    'terms' => $option,
                    'operator' => (count($option) > 1) ? $tax_query_operator : 'IN',
                );
            } elseif (is_string($option)) {
                $tags = explode(',', $option);
                $wp_query->query_vars['tax_query'][] = array(
                    'taxonomy' => 'product_tag',
                    'field' => 'slug',
                    'terms' => $tags,
                    'operator' => (count($tags) > 1) ? $tax_query_operator : 'IN',
                );
            }
        } elseif ($key == 'price') {
            $wp_query->query_vars['meta_query'][] = array(
                'key' => '_price',
                'value' => array(intval($option['min_price']), intval($option['max_price'])),
                'compare' => 'BETWEEN',
                'type' => 'NUMERIC'
            );
        } elseif ($key == 'attribute') {
            foreach ($option as $tax_name => $value) {
                if ('color' == $tax_name) {
                    $tax_name = 'pa_color';
                }
                $atts = (array)$value;
                $wp_query->query_vars['tax_query'][] = array(
                    'taxonomy' => $tax_name,
                    'field' => 'slug',
                    'terms' => $atts,
                    'operator' => 'IN' // (count($atts) > 1) ? $tax_query_operator : 'IN',
                );
            }
        } elseif ('range' === $key) {
            /* In case of multiple range slider filters,
            build tax query base on the selected range of each filter.
            See https://codex.wordpress.org/Class_Reference/WP_Query#Taxonomy_Parameters */
            foreach ($option as $tax => $range) {
                $valid_names = [];
                $terms = get_terms(['taxonomy' => $tax]);
                if (!$terms || $terms instanceof \WP_Error) {
                    continue;
                }
                foreach ($terms as $term) {
                    if ($term->name >= $range['min'] && $term->name <= $range['max']) {
                        $valid_names[] = $term->name;
                    }
                }
                $wp_query->query_vars['tax_query'][] = [
                    'taxonomy' => $tax,
                    'field' => 'name',
                    'terms' => $valid_names,
                    'operator' => 'IN' // (count($valid_names) > 1) ? $tax_query_operator : 'IN',
                ];
            }
        }
    }

    /* Since WP does not allowed to use the `relation` argument when there's only one inner taxonomy,
    we have to make sure there're more than 1 before adding the `relation` argument.
    See https://codex.wordpress.org/Class_Reference/WP_Query#Taxonomy_Parameters */
    if (is_array($wp_query->query_vars['tax_query']) && count($wp_query->query_vars['tax_query']) > 1) {
        $wp_query->query_vars['tax_query']['relation'] = $relation;
    }
    if (is_array($wp_query->query_vars['meta_query']) && count($wp_query->query_vars['meta_query']) > 1) {
        $wp_query->query_vars['meta_query']['relation'] = $relation;
    }
}, PHP_INT_MAX);


add_action('wp_enqueue_scripts', 'Zoo\Frontend\Hook\zoo_ln_frontend_assets', -1, 0);
add_action('pre_get_posts', 'Zoo\Frontend\Hook\process_filter', 11); // After WooCommerce
//frontend ajax hook
add_action('wp_ajax_zoo_ln_get_product_list', 'Zoo\Frontend\Ajax\zoo_ln_get_product_list');
add_action('wp_ajax_nopriv_zoo_ln_get_product_list', 'Zoo\Frontend\Ajax\zoo_ln_get_product_list');

function zoo_ln_frontend_assets()
{
    global $wp_query;

    wp_register_style('zoo-ln-style', ZOO_LN_CSSPATH . 'zoo-ln-style.css');
    wp_register_style('cleverfont', ZOO_LN_VENDOR . 'cleverfont/style.css');
    wp_register_script(
        'scrollbar',
        ZOO_LN_VENDOR . "scrollbar/jquery.scrollbar.min.js",
        array('jquery'),
        ZOO_LN_VERSION,
        true
    );
    wp_register_script(
        'tippy',
        ZOO_LN_VENDOR . "tippy/tippy.all.min.js",
        array('jquery'),
        ZOO_LN_VERSION,
        true
    );
    wp_register_script(
        'slider-pips',
        ZOO_LN_VENDOR . "slider-pips/slider-pips.min.js",
        array('jquery'),
        '1.11.4',
        true
    );
    wp_register_script(
        'zoo-ln-frontend',
        ZOO_LN_JSPATH . "frontend.min.js",
        array('jquery'),
        ZOO_LN_VERSION,
        true
    );
    wp_register_script(
        'zoo-ln-frontend-jquery-ui',
        ZOO_LN_JSPATH . "jquery-ui.min.js",
        array('jquery'),
        ZOO_LN_VERSION,
        true
    );
    wp_register_script(
        'touch-punch',
        ZOO_LN_VENDOR . "touch-punch/jquery.ui.touch-punch.min.js",
        array('jquery'),
        ZOO_LN_VERSION,
        true
    );
    $params = array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'wcLinks' => get_option('woocommerce_permalinks'),
        'shopUrl' => wc_get_page_permalink('shop'),
        'queryVars' => $GLOBALS['wp_query']->query_vars
    );

    if ($wp_query->is_tax()) {
        $params['isTax'] = 1;
        $params['queriedTerm'] = [
            'cln_queried_tax' => $wp_query->queried_object->taxonomy,
            'cln_queried_term_slug' => $wp_query->queried_object->slug
        ];
    }
    wp_localize_script(
        'zoo-ln-frontend',
        'zoo_ln_params',
        $params
    );
}

function add_zoo_ln_navigation($atts, $content, $tag)
{
    global $wp_query;
    $query_arg = $wp_query->query;
    if ($_GET) {
        $query_arg = $_GET;
    }
    if (isset($wp_query->query_vars['product_cat']) && empty($query_arg['categories'])) {
        $query_arg['categories'] = $wp_query->query_vars['product_cat'];
    }
    if (isset($wp_query->query_vars['product_tag']) && empty($query_arg['tags'])) {
        $query_arg['tags'] = $wp_query->query_vars['product_tag'];
    }
    $selected_filter_option = get_activated_filter($query_arg, true);
    require ZOO_LN_TEMPLATES_PATH . 'view.php';
}

function get_max_price()
{
    global $wpdb;
    $postmeta = $wpdb->prefix . "postmeta";
    $posts = $wpdb->prefix . "posts";

    $result = 0;

    $sql = "
    SELECT MAX(CAST(table1.meta_value AS SIGNED)) AS max FROM " . $postmeta . " AS table1
    INNER JOIN " . $posts . " AS table2 ON (table1.post_id = table2.ID)
    WHERE (table1.meta_key= '_price')
    AND (table1.meta_value != '')
    AND ((table2.post_type = 'product') OR (table2.post_type = 'product_variation'))
    AND (table2.post_status = 'publish')
    ";

    $sql_result = $wpdb->get_results($sql);

    foreach ($sql_result as $row) {
        $result = intval($row->max);
    }

    return $result;
}

function prepare_data($item_id)
{
    $data = array();

    $filter_setting_order = \Zoo\Helper\Data\get_global_config_with_name($item_id, 'filter-setting-order');
    $filter_setting_order = (array)json_decode($filter_setting_order);

    foreach ($filter_setting_order as $key => $order) {
        if ($order != '') {
            $items = explode(',', $order);
            foreach ($items as $item) {
                if ($item != '') {
                    $item_data = array();

                    $parts = explode('-', $item);
                    $item_number = array_pop($parts);
                    $item_type = implode('-', $parts);

                    $item_data['item_number'] = $item_number;
                    $item_data['item_type'] = $item_type;
                    $filter_config = \Zoo\Helper\Data\get_filter_config_with_name($item_id, $item);

                    $sac = json_decode($filter_config['filter_config_value']);
                    $sac = \Zoo\Helper\Data\objectToArray($sac);
                    $filter_config['filter_config_value'] = $sac;
                    $item_data['item_config_value'] = $filter_config;


                    $data[$key][] = $item_data;
                }
            }
        }
    }

    return $data;
}

function pagination_args($arg)
{
    if (isset($_POST['zoo_ln_form_data'])) {
        parse_str($_POST['zoo_ln_form_data'], $post_option);
        $pagination_link = ($post_option['pagination_link']);
        $arg['base'] = $pagination_link;
    }

    return $arg;
}

function get_activated_filter($post_option = array())
{
    $save_array = array();

    if (isset($_POST['zoo_ln_form_data']) && defined('DOING_AJAX') && DOING_AJAX) {
        foreach ($post_option as $key => $option) {
            if ($key == 'in-stock' && $option == '1') {
                $save_array[$key] = $option;
            } elseif ($key == 'on-sale' && $option == '1') {
                $save_array[$key] = $option;
            } elseif ($key == 'rating-from' && $option != '0') {
                $save_array[$key] = $option;
            } elseif ($key == 'review-from' && $option != '0') {
                $save_array[$key] = $option;
            } elseif ($key == 'categories' || $key == 'tags') {
                $save_array[$key] = $option;
            } elseif ($key == 'price') {
                if (!in_array('', $option)) {
                    $save_array[$key] = $option;
                }
            } elseif ($key == 'attribute') {
                $save_array[$key] = $option;
            } elseif (false !== strpos($key, 'range-min-') && $option != '') {
                /* In case of multiple range slider filters,
                store min range by taxonony which is unpredictable. */
                $tax = str_replace('range-min-', '', $key);
                $save_array['range'][$tax]['min'] = $option;
            } elseif (false !== strpos($key, 'range-max-') && $option != '') {
                /* In case of multiple range slider filters,
                store max range by taxonony which is unpredictable. */
                $tax = str_replace('range-max-', '', $key);
                $save_array['range'][$tax]['max'] = $option;
            }
        }
    } else {
        if (!empty($post_option)) {
            foreach ($post_option as $key => $option) {
                if ($key === 'in-stock' && $option == '1') {
                    $save_array[$key] = $option;
                } elseif ($key == 'on-sale' && $option == '1') {
                    $save_array[$key] = $option;
                } elseif ($key == 'rating-from' && $option != '0') {
                    $save_array[$key] = $option;
                } elseif ($key == 'review-from' && $option != '0') {
                    $save_array[$key] = $option;
                } elseif ($key == 'categories' || $key == 'tags') {
                    $save_array[$key] = is_array($option) ? $option : explode(',', $option);
                } elseif ($key == 'min_price') {
                    $save_array['price']['min_price'] = $option;
                } elseif ($key == 'max_price') {
                    $save_array['price']['max_price'] = $option;
                } elseif (false !== strpos($key, 'range-min-')) {
                    /* In case of multiple range slider filters,
                    store min range by taxonony which is unpredictable. */
                    $tax = str_replace('range-min-', '', $key);
                    $save_array['range'][$tax]['min'] = $option;
                } elseif (false !== strpos($key, 'range-max-')) {
                    /* In case of multiple range slider filters,
                    store max range by taxonony which is unpredictable. */
                    $tax = str_replace('range-max-', '', $key);
                    $save_array['range'][$tax]['max'] = $option;
                } else {
                    if (substr($key, 0, 3) === 'pa_' || 'color' === $key) {
                        if ('color' === $key) {
                            $key = 'pa_color';
                        }
                        $save_array['attribute'][$key] = explode(',', $option);
                    }
                    if ($key === 'order_type') {
                        $save_array['order'] = $option;
                    }
                    if ($key === 'order_by') {
                        $save_array['orderby'] = $option;
                    }
                    if ($key === 'relation' && is_string($option)) {
                        $save_array['relation'] = strtoupper($option);
                    }
                }
            }
        }
    }

    return $save_array;
}

function get_current_paging_index($wp_query)
{
    $args = $wp_query->query_vars;

    if (isset($args['paged']) && $args['paged'] != 1) {
        $page_index = intval($args['paged']);
        $GLOBALS['zoo_ln_data']['current_page_index'] = $page_index;
    } else {
        $GLOBALS['zoo_ln_data']['current_page_index'] = 1;
    }
}

function process_filter($wp_query, $post_option = false)
{
    if ((isset($GLOBALS['current_screen']) && $GLOBALS['current_screen']->in_admin()) || is_customize_preview()) {
        return;
    }

    $is_ajax = wp_doing_ajax();
    $is_main_query = $wp_query->is_main_query();

    if (!$is_ajax && !$is_main_query) {
        return;
    }

    if (!$post_option) {
        if (isset($_POST['zoo_ln_form_data']) && $is_ajax) {
            parse_str($_POST['zoo_ln_form_data'], $post_option);
        } else {
            if (empty($_GET)) {
                return;
            }
            $post_option = $_GET;
            if (isset($wp_query->query_vars['product_cat']) && !isset($post_option['categories'])) {
                $post_option['categories'] = $wp_query->query_vars['product_cat'];
            }
            if (isset($wp_query->query_vars['product_tag']) && !isset($post_option['tags'])) {
                $post_option['tags'] = $wp_query->query_vars['product_tag'];
            }
        }
    }

    if (empty($post_option['cln_do_filter'])) {
        return $wp_query;
    }

    get_current_paging_index($wp_query);

    if (isset($GLOBALS['zoo_ln_data'], $GLOBALS['zoo_ln_data']['need_reset_paging']) && $GLOBALS['zoo_ln_data']['need_reset_paging'] == 1) {
        $wp_query->query_vars['paged'] = 1;
        header("Location: " . get_permalink(wc_get_page_id('shop')));
        die();
    }

    if (isset($post_option['pagination_link'])) {
        add_filter('woocommerce_pagination_args', 'Zoo\Frontend\Hook\pagination_args', 10, 1);
    }

    $tax_query = $meta_query = [];
    $selected_filter_option = get_activated_filter($post_option);
    $relation = !empty($post_option['relation']) ? sanitize_text_field($post_option['relation']) : 'AND';
    $tax_query_operator = ($relation === 'OR') ? 'IN' : 'AND'; // `tax_query` does not have `OR` operator.

    // Maybe duplicate taxonomy...
    if ($is_ajax && !empty($post_option['cln_queried_tax']) && !empty($post_option['cln_queried_term_slug'])) {
        $tax_query[] = [
            'taxonomy' => sanitize_title($post_option['cln_queried_tax']),
            'field' => 'slug',
            'terms' => sanitize_title($post_option['cln_queried_term_slug'])
        ];
    }

    foreach ($selected_filter_option as $key => $option) {
        if ($key == 'in-stock' && $option == '1') {
            $meta_query[] = array(
                'key' => '_stock_status',
                'value' => 'instock',
                'compare' => '=',
            );
        } elseif ($key == 'on-sale' && $option == '1') {
            $wp_query->set('post__in', array_merge([0], wc_get_product_ids_on_sale()));
        } elseif ($key == 'rating-from' && $option != '0') {
            $meta_query[] = array(
                'key' => '_wc_average_rating',
                'value' => intval($option),
                'compare' => '>='
            );
        } elseif ($key == 'review-from' && $option != '0') {
            $meta_query[] = array(
                'key' => '_wc_review_count',
                'value' => intval($option),
                'compare' => '>='
            );
        } elseif ($key == 'categories') {
            if (is_array($option)) {
                // Fuck WP, you liar!
                foreach ($option as $cat) {
                    $tax_query[] = array(
                        'taxonomy' => 'product_cat',
                        'field' => 'slug',
                        'terms' => $cat
                    );
                }
            } elseif (is_string($option)) {
                $cats = explode(',', $option);
                foreach ($cats as $cat) {
                    $tax_query[] = array(
                        'taxonomy' => 'product_cat',
                        'field' => 'slug',
                        'terms' => $cat
                    );
                }
            }
        } elseif ($key == 'tags') {
            if (is_array($option)) {
                foreach ($option as $tag) {
                    $tax_query[] = array(
                        'taxonomy' => 'product_tag',
                        'field' => 'slug',
                        'terms' => $tag
                    );
                }
            } elseif (is_string($option)) {
                $tags = explode(',', $option);
                foreach ($tags as $tag) {
                    $tax_query[] = array(
                        'taxonomy' => 'product_tag',
                        'field' => 'slug',
                        'terms' => $tag
                    );
                }
            }
        } elseif ($key == 'price') {
            $meta_query[] = array(
                'key' => '_price',
                'value' => array(floatval($option['min_price']), floatval($option['max_price'])),
                'compare' => 'BETWEEN',
                'type' => 'DECIMAL'
            );
        } elseif ($key == 'attribute' || 0 === strpos($key, 'pa_') || $key === 'color') {
            foreach ($option as $key => $value) {
                // if (isset($wp_query->query_vars[$key])) {
                //     continue; // WP already parsed it.
                // }
                if ('color' === $key) {
                    $key = 'pa_color';
                }
                $atts = (array)$value;
                foreach ($atts as $att) {
                    $tax_query[] = array(
                        'taxonomy' => $key,
                        'field' => 'slug',
                        'terms' => $att
                    );
                }
            }
        } elseif ('range' === $key) {
            /* In case of multiple range slider filters,
            build tax query base on the selected range of each filter.
            See https://codex.wordpress.org/Class_Reference/WP_Query#Taxonomy_Parameters */
            foreach ($option as $tax => $range) {
                $terms = get_terms(['taxonomy' => $tax]);
                if (!$terms || $terms instanceof \WP_Error) {
                    continue;
                }
                foreach ($terms as $term) {
                    if ($term->name >= $range['min'] && $term->name <= $range['max']) {
                        $tax_query[] = [
                            'taxonomy' => $tax,
                            'field' => 'name',
                            'terms' => $term->name
                        ];
                    }
                }
            }
        } elseif ('orderby' === $key) {
            $order = !empty($selected_filter_option['order']) ? $selected_filter_option['order'] : 'DESC';
            $order_args = cln_get_filter_ordering_args($option, $order);
            $wp_query->set('orderby', $order_args['orderby']);
            $wp_query->set('order', $order_args['order']);
        }
    }

    $product_visibility_not_in = [];
    $product_visibility_terms = wc_get_product_visibility_term_ids();

    if (is_search() && $is_main_query) {
        $product_visibility_not_in = (array)$product_visibility_terms['exclude-from-search'];
    } else {
        $product_visibility_not_in = (array)$product_visibility_terms['exclude-from-catalog'];
    }

    if ('yes' === get_option('woocommerce_hide_out_of_stock_items')) {
        $product_visibility_not_in[] = $product_visibility_terms['outofstock'];
    }

    if (!empty($product_visibility_not_in)) {
        foreach ($product_visibility_not_in as $invisible_product) {
            $tax_query[] = [
                'taxonomy' => 'product_visibility',
                'field' => 'term_taxonomy_id',
                'terms' => $invisible_product,
                'operator' => 'NOT IN',
            ];
        }
    }

    /* Since WP does not allowed to use the `relation` argument when there's only one inner taxonomy,
    we have to make sure there're more than 1 before adding the `relation` argument.
    See https://codex.wordpress.org/Class_Reference/WP_Query#Taxonomy_Parameters */
    if (count($tax_query) > 1) {
        $tax_query['relation'] = $relation;
    }

    // if (count($meta_query) > 1) {
    //     $meta_query['relation'] = 'AND';
    // }


    $wp_query->set('post_type', 'product');
    $wp_query->set('wc_query', 'product_query');
    $wp_query->set('tax_query', $tax_query);
    $wp_query->set('meta_query', $meta_query);

    if (!empty($tax_query)) { // This is not actually a tax query.
        $wp_query->is_tax = false;
    }

    // Fix for WC version > 3.3
    add_action('woocommerce_before_shop_loop', function () {
        global $wp_query;
        wc_setup_loop([
            'is_search' => false,
            'is_shortcode' => false,
            'is_paginated' => true,
            'total_pages' => $wp_query->max_num_pages,
            'current_page' => (int)max(1, $wp_query->get('paged', 1)),
            'per_page' => (int)$wp_query->get('posts_per_page'),
            'total' => (int)$wp_query->found_posts,
            'columns' => wc_get_default_products_per_row(),
            'is_filtered' => true
        ]);
    }, PHP_INT_MAX, 0);

    if ($is_ajax) {
        return $wp_query;
    }
}

function render_list_categories($selected_slugs, $content_data)
{
    $cats = get_terms([
        'taxonomy' => 'product_cat'
    ]);

    if (is_wp_error($cats)) {
        return;
    }

    if (!isset($content_data['category-slugs']) && !empty($cats)) {
        $slugs = [];
        foreach ($cats as $cat) {
            $slugs[] = $cat->slug;
        }
        $content_data['category-slugs'] = $slugs;
    }

    return render_categories(0, $cats, $selected_slugs, $content_data);
}

function render_categories($parent_id, $categories, $selected_slugs, $content_data)
{
    global $wp_query;

    $html = '';
    $loop_categories = array_filter($categories, function ($cats) use ($parent_id) {
        return $cats->parent == $parent_id;
    });

    if (count($loop_categories)) {
        if (isset($content_data['category-slugs'])) {
            $visible_slugs = $content_data['category-slugs'];
        } else {
            $visible_slugs = [];
        }

        $visible_class = '';
        if (!isset($content_data['list-show-child'])) {
            $hidden_child = 'style="display:none"';
        } else {
            $hidden_child = '';
            $visible_class = ' active';
        }

        $is_cat = is_product_category();

        foreach ($loop_categories as $cat) {
            $current_html = '';
            $child_html = render_categories($cat->term_id, $categories, $selected_slugs, $content_data);
            if (in_array($cat->slug, $visible_slugs)) {
                $wrap_class = '';
                if (in_array($cat->slug, $selected_slugs) || ($is_cat && $cat->slug === $wp_query->queried_object->slug)) {
                    $checked = 'checked';
                    $wrap_class .= ' selected';
                } else {
                    $checked = '';
                }
                $disabled = ($is_cat && $cat->slug === $wp_query->queried_object->slug) ? ' disabled' : '';
                $current_html .= '<label class="zoo_ln_cat_filter_category_name">';
                $current_html .= '<input type="checkbox" class=""  value="' . $cat->slug . '" name="categories[]" ' . $checked . $disabled . '/>';
                $current_html .= $cat->name;
                $current_html .= '</label>';
                if (isset($content_data['show-product-count']) && $content_data['show-product-count'] == 1) {
                    // if ($child_html) {
                    //     $sum_count = $cat->count;
                    //     $sub_cats = get_terms([
                    //         'taxonomy' => 'product_cat',
                    //         'parent' => $cat->term_id,
                    //         'update_term_meta_cache' => false
                    //     ]);
                    //     foreach ($sub_cats as $sub_cat) {
                    //         $sum_count += $sub_cat->count;
                    //     }
                    //     $current_html .= '<span class="count">' . $sum_count . '</span>';
                    // } else {
                        $current_html .= '<span class="count">' . $cat->count . '</span>';
                    // }
                }
                if (strlen($child_html)) {
                    $current_html .= '<span class="zoo-ln-toggle-view' . $visible_class . '"><i class="cs-font clever-icon-down"></i></span><ul class="zoo-wrap-child-item" ' . $hidden_child . '>' . $child_html . '</ul>';
                    $wrap_class .= ' zoo-filter-has-child';
                }
                $current_html = '<li class="zoo-filter-item' . $wrap_class . '">' . $current_html . '</li>';
            } else {
                if (strlen($child_html)) {
                    $current_html .= $child_html;
                }
            }
            $html .= $current_html;
        }
    } else {
        if (isset($content_data['space'])) {
            $content_data['space'] = '';
        }
    }
    return $html;
}

function render_price($price)
{
    $currency_pos = get_option('woocommerce_currency_pos');
    $price_html = '';
    switch ($currency_pos) {
        case 'left':
            $price_html = printf('<span class="woocommerce-Price-currencySymbol">%s</span><span class="price amount woocommerce-Price-amount">%s</span>', get_woocommerce_currency_symbol(), $price);
            break;
        case 'right':
            $price_html = printf('<span class="price amount woocommerce-Price-amount">%s</span><span class="woocommerce-Price-currencySymbol">%s</span>', $price, get_woocommerce_currency_symbol());
            break;
        case 'left_space':
            $price_html = printf('<span class="woocommerce-Price-currencySymbol">%s</span> <span class="price amount woocommerce-Price-amount">%s</span>', get_woocommerce_currency_symbol(), $price);
            break;
        case 'right_space':
            $price_html = printf('<span class="price amount woocommerce-Price-amount">%s</span> <span class="woocommerce-Price-currencySymbol">%s</span>', $price, get_woocommerce_currency_symbol());
            break;
    }
    return $price_html;
}
