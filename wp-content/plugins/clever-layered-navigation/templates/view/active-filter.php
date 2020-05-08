<?php
/**
 */

global $wp_query;

$display = 'none';

if ($wp_query->is_main_query() && $wp_query->is_tax()) {
    $cln_queried_tax = $wp_query->get_queried_object();
}

if (!empty($selected_filter_option)) {
    $display = 'block';
    if (isset($cln_queried_tax)) {
        if (!empty($selected_filter_option['categories']) && 'product_cat' === $cln_queried_tax->taxonomy) {
            $tax_index = array_search($cln_queried_tax->slug, $selected_filter_option['categories']);
            if (false !== $tax_index && 1 === count($selected_filter_option['categories']) && 1 === count($selected_filter_option)) {
                $display = 'none';
            }
        }
        if (!empty($selected_filter_option['tags']) && 'product_tag' === $cln_queried_tax->taxonomy) {
            $tax_index = array_search($cln_queried_tax->slug, $selected_filter_option['tags']);
            if (false !== $tax_index && 1 === count($selected_filter_option['tags']) && 1 === count($selected_filter_option)) {
                $display = 'none';
            }
        }
    }
    $filters_count = count($selected_filter_option);
    if ($filters_count === 1) {
        if (isset($selected_filter_option['orderby']) || isset($selected_filter_option['relation'])) {
            $display = 'none';
        }
        if(isset($has_filter_category)) {
            if (!$has_filter_category) {
                $display = 'none';
            }
        }
    }
}

$filter_id = mt_rand();
?>
<div id="cln-filter-item-<?php echo esc_attr($filter_id) ?>" class="zoo-filter-block zoo-active-filter" style="display: <?php echo $display; ?>">
    <h4 class="zoo-title-filter-block"><?php echo esc_html($content_data['title']); ?></h4>
    <ul class="zoo-ln-wrap-activated-filter">
        <?php
        if (isset($selected_filter_option) && isset($filters_count)) {
            require ZOO_LN_TEMPLATES_PATH . 'view/active-filter/items.php';
        }
        ?>
    </ul>
</div>
