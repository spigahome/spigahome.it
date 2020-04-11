<?php
/**
 */

namespace Zoo\Frontend\Ajax;

function zoo_ln_get_product_list()
{
    global $wpdb, $wp_query;

    $args = [];

    $GLOBALS['zoo_ln_data']['is_ajax'] = 1;
    $GLOBALS['zoo_ln_data']['need_reset_paging'] = 0;

    if (!isset($_POST['zoo_ln_form_data'])) {
        wp_send_json_error(__('Invalid request data!', 'clever-layered-navigation'));
    } else {
        parse_str($_POST['zoo_ln_form_data'], $post_args);
    }

    // Avoid loop hell.
    remove_action('pre_get_posts', 'Zoo\Frontend\Hook\process_filter', 11);

    // Ensure parent terms' count is sum of products count from its children as well.
    add_filter('get_terms', 'cln_correct_parent_terms_count', 10, 2);

    $q = \Zoo\Frontend\Hook\process_filter(new \WP_Query(), $post_args);

    $args = $q->query_vars;

    if (!empty($post_args['vendor_store_author_id'])) {
        $args['author'] = intval($post_args['vendor_store_author_id']);
    }

    $args['paged']          = !empty($post_args['paged']) ? intval($post_args['paged']) : 1;
    $args['posts_per_page'] = !empty($post_args['posts_per_page']) ? intval($post_args['posts_per_page']) : get_option('posts_per_page');
    // $args['meta_query']     = $q->get('meta_query');
    // $args['tax_query']      = $q->get('tax_query');
    // $args['wc_query']       = 'product_query';
    // $args['post__in']       = $q->get('post__in');

    // if (!empty($post_args['order_type'])) {
    //     $args['order'] = sanitize_key($post_args['order_type']);
    // } else {
    //     $args['order'] = 'DESC';
    // }
    //
    // switch ($post_args['order_by']) {
    //     case 'title':
    //         $args['orderby'] = 'title';
    //         break;
    //     case 'relevance':
    //         $args['orderby'] = 'relevance';
    //         break;
    //     case 'menu_order':
    //         $args['orderby'] = 'menu_order';
    //         break;
    //     case 'price':
    //         $args['orderby'] = 'meta_value_num';
    //         $args['meta_key'] = '_price';
    //         break;
    //     case 'price-desc':
    //         $args['orderby'] = 'meta_value_num';
    //         $args['meta_key'] = '_price';
    //         break;
    //     case 'popularity':
    //         $args['meta_key'] = 'total_sales';
    //         add_filter('posts_clauses', function($filtering_args) {
    //             global $wpdb;
    //             $filtering_args['orderby'] = "$wpdb->postmeta.meta_value+0 DESC, $wpdb->posts.post_date DESC";
    //             return $filtering_args;
    //         });
    //         break;
    //     case 'rating':
    //         $args['meta_key'] = '_wc_average_rating';
    //         $args['orderby'] = [
    //             'meta_value_num' => 'DESC',
    //             'ID' => 'ASC'
    //         ];
    //         break;
    //     default:
    //         $args['orderby'] = 'date';
    //         break;
    // }

    $wp_query = new \WP_Query($args);

    $wp_query->is_tax = false;

    ob_start();
    require ZOO_LN_TEMPLATES_PATH . 'product-list.php';
    $html_ul_products_content = ob_get_clean();

    ob_start();
    woocommerce_result_count();
    $html_result_count_content = ob_get_clean();

    ob_start();
    wc_get_template('loop/pagination.php');
    $html_pagination_content = ob_get_clean();

    $item_id = intval($post_args['filter_list_id']);
    $selected_filter_option = \Zoo\Frontend\Hook\get_activated_filter($post_args);

    ob_start();
    require ZOO_LN_TEMPLATES_PATH . 'view.php';
    $filter_panel = ob_get_clean();

    $return['html_ul_products_content'] = $html_ul_products_content;
    $return['html_result_count_content'] = $html_result_count_content;
    $return['html_pagination_content'] = preg_replace('/<\/*nav[^>]*>/', '', $html_pagination_content);
    $return['html_filter_panel'] = $filter_panel;
    $return['total'] = $wp_query->found_posts;
    $return['total_text'] = $wp_query->found_posts.' '.($wp_query->found_posts>1?esc_html__(' items','clever-layered-navigation'):esc_html__(' item','clever-layered-navigation'));

    wp_send_json($return);
}
