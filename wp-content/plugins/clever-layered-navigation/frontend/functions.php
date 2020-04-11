<?php
/**
 * @mixin
 */

/**
 * Ensure parent terms show sum count
 */
function cln_correct_parent_terms_count($terms, $taxonomies)
{
    if (!isset($taxonomies[0]) || !in_array($taxonomies[0], apply_filters('woocommerce_change_term_counts', ['product_cat', 'product_tag']), true)) {
        return $terms;
    }

    $term_counts   = [];

    foreach ($terms as &$term) {
        if (is_object($term)) {
            $term_counts[$term->term_id] = isset($term_counts[$term->term_id]) ? $term_counts[$term->term_id] : get_term_meta($term->term_id, 'product_count_' . $taxonomies[0], true);

            if ('' !== $term_counts[$term->term_id]) {
                $term->count = absint($term_counts[$term->term_id]);
            }
        }
    }

    return $terms;
}

/**
 * Returns an array of arguments for ordering products based on the selected values.
 *
 * @param string $orderby Order by param.
 * @param string $order Order param.
 * @return array
 */
function cln_get_filter_ordering_args($orderby = '', $order = '')
{
    if (!$orderby) {
        $orderby_value = isset($_GET['orderby']) ? wc_clean((string) wp_unslash($_GET['orderby'])) : wc_clean(get_query_var('orderby'));
        if (!$orderby_value) {
            if (is_search()) {
                $orderby_value = 'relevance';
            } else {
                $orderby_value = apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby', 'menu_order'));
            }
        }
        $orderby_value = is_array($orderby_value) ? $orderby_value : explode('-', $orderby_value);
        $orderby       = esc_attr($orderby_value[0]);
        $order         = !empty($orderby_value[1]) ? $orderby_value[1] : $order;
    }

    $orderby = strtolower(is_array($orderby) ? (string) current($orderby) : (string) $orderby);
    $order   = strtoupper(is_array($order) ? (string) current($order) : (string) $order);
    $args    = array(
        'orderby'  => $orderby,
        'order'    => ('DESC' === $order) ? 'DESC' : 'ASC',
        'meta_key' => '',
    );

    switch ($orderby) {
        case 'id':
            $args['orderby'] = 'ID';
            break;
        case 'menu_order':
            $args['orderby'] = 'menu_order title';
            break;
        case 'title':
            $args['orderby'] = 'title';
            $args['order']   = ('DESC' === $order) ? 'DESC' : 'ASC';
            break;
        case 'relevance':
            $args['orderby'] = 'relevance';
            $args['order']   = 'DESC';
            break;
        case 'rand':
            $args['orderby'] = 'rand';
            break;
        case 'date':
            $args['orderby'] = 'date ID';
            $args['order']   = ('ASC' === $order) ? 'ASC' : 'DESC';
            break;
        case 'price':
            $callback = 'DESC' === $order ? 'cln_order_by_price_desc_post_clauses' : 'cln_order_by_price_asc_post_clauses';
            add_filter('posts_clauses', $callback);
            break;
        case 'popularity':
            add_filter('posts_clauses', 'cln_order_by_popularity_post_clauses');
            break;
        case 'rating':
            add_filter('posts_clauses', 'cln_order_by_rating_post_clauses');
            break;
    }

    return $args;
}

function cln_product_sorting_table_join_clause($sql)
{
    global $wpdb;

    if (! strstr($sql, 'wc_product_meta_lookup')) {
        $sql .= " LEFT JOIN {$wpdb->wc_product_meta_lookup} wc_product_meta_lookup ON $wpdb->posts.ID = wc_product_meta_lookup.product_id ";
    }
    return $sql;
}

/**
 * Handle numeric price sorting.
 *
 * @param array $args Query args.
 * @return array
 */
function cln_order_by_price_asc_post_clauses($args)
{
    $args['join']    = cln_product_sorting_table_join_clause($args['join']);
    $args['orderby'] = ' wc_product_meta_lookup.min_price ASC, wc_product_meta_lookup.product_id ASC ';
    return $args;
}

/**
 * Handle numeric price sorting.
 *
 * @param array $args Query args.
 * @return array
 */
function cln_order_by_price_desc_post_clauses($args)
{
    $args['join']    = cln_product_sorting_table_join_clause($args['join']);
    $args['orderby'] = ' wc_product_meta_lookup.max_price DESC, wc_product_meta_lookup.product_id DESC ';
    return $args;
}

/**
 * WP Core does not let us change the sort direction for individual orderby params - https://core.trac.wordpress.org/ticket/17065.
 *
 * This lets us sort by meta value desc, and have a second orderby param.
 *
 * @param array $args Query args.
 * @return array
 */
function cln_order_by_popularity_post_clauses($args)
{
    $args['join']    = cln_product_sorting_table_join_clause($args['join']);
    $args['orderby'] = ' wc_product_meta_lookup.total_sales DESC, wc_product_meta_lookup.product_id DESC ';
    return $args;
}

/**
 * Order by rating post clauses.
 *
 * @param array $args Query args.
 * @return array
 */
function cln_order_by_rating_post_clauses($args)
{
    $args['join']    = cln_product_sorting_table_join_clause($args['join']);
    $args['orderby'] = ' wc_product_meta_lookup.average_rating DESC, wc_product_meta_lookup.product_id DESC ';
    return $args;
}

/**
 * Count products within certain terms, taking the main WP query into consideration.
 *
 * This query allows counts to be generated based on the viewed products, not all products.
 *
 * @param  array  $term_ids Term IDs.
 * @param  string $taxonomy Taxonomy.
 * @param  string $logic Query logic.
 * @return array
 */
function cln_get_filtering_terms_count($term_ids, $taxonomy)
{
    global $wpdb, $wp_query;

    $tax_query  = WC_Query::get_main_tax_query();
    $meta_query = WC_Query::get_main_meta_query();

    if (wp_doing_ajax()) {
        $tax_query = $wp_query->get('tax_query');
        $meta_query = $wp_query->get('meta_query');
    }

    $meta_query     = new WP_Meta_Query($meta_query);
    $tax_query      = new WP_Tax_Query($tax_query);
    $meta_query_sql = $meta_query->get_sql('post', $wpdb->posts, 'ID');
    $tax_query_sql  = $tax_query->get_sql($wpdb->posts, 'ID');

    // Generate query.
    $query           = [];
    $query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) as term_count, terms.term_id as term_count_id";
    $query['from']   = "FROM {$wpdb->posts}";
    $query['join']   = "
		INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
		INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
		INNER JOIN {$wpdb->terms} AS terms USING( term_id )
		" . $tax_query_sql['join'] . $meta_query_sql['join'];

    $query['where'] = "
		WHERE {$wpdb->posts}.post_type IN ( 'product' )
		AND {$wpdb->posts}.post_status = 'publish'"
        . $tax_query_sql['where'] . $meta_query_sql['where'] .
        'AND terms.term_id IN (' . implode(',', array_map('absint', $term_ids)) . ')';

    // $search = WC_Query::get_main_search_query_sql();
    //
    // if ($search) {
    //     $query['where'] .= ' AND ' . $search;
    // }

    $query['group_by'] = 'GROUP BY terms.term_id';
    // $query = apply_filters('cln_get_filtering_term_count_query', $query);
    $query = implode(' ', $query);
    // $query_hash = md5($query);
    // $cached = (array)get_transient('cln_filtering_term_counts_' . $taxonomy);

    // if (!isset($cached[$query_hash])) {
    $results = $wpdb->get_results($query, ARRAY_A);
    $counts = array_map('absint', wp_list_pluck($results, 'term_count', 'term_count_id'));
    // $cached[$query_hash] = $counts;
    // set_transient('cln_filtering_term_counts_' . $taxonomy, $cached, DAY_IN_SECONDS);
    // }

    // return $cached[$query_hash];

    return $counts ? : [];
}

/**
 * Maybe remove unsatisfied terms
 *
 * @return array
 */
function cln_maybe_remove_unsatisfied_terms(array $terms, $tax)
{
    global $wp_query, $cln_advanced_settings;

    if ('AND' === $cln_advanced_settings['filter_logic_operator']) {
        $counts = cln_get_filtering_terms_count(wp_list_pluck($terms, 'term_id'), $tax);
        foreach ($terms as $term_index => $term_object) {
            if (isset($counts[$term_object->term_id])) {
                $terms[$term_index]->count = $counts[$term_object->term_id]; // Only count products in current filters.
            } else {
                unset($terms[$term_index]);
            }
        }
    }

    return $terms;
}
